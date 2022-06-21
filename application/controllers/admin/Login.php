<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require __DIR__ . '/../../../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require __DIR__ . '/../../../vendor/phpmailer/phpmailer/src/PHPMailer.php';
require __DIR__ . '/../../../vendor/phpmailer/phpmailer/src/SMTP.php';
require __DIR__ . '/../../../vendor/phpmailer/phpmailer/src/Exception.php';

class Login extends CI_Controller {

	public function __construct()
    {
        parent::__construct();
        $this->load->model('auth_m');
    }

	public function index()
	{
		$this->load->view('admin/header');
		$this->load->view('admin/login');
		$this->load->view('template/footer');
	}

	public function sign_in(){
		$email = strtolower($this->input->post('email'));
		$user_pass = $this->input->post('user_pass');

		$where['email'] = $email;
		$where['user_pass'] = sha1($user_pass);
		$where['role'] = '0';	//super admin role
		
		$info = $this->auth_m->get_member($where);
		if(empty($info)){
			echo "no";
			return;
		}
		$info['is_loggedin'] = true;
		$this->session->set_userdata('admin_data', $info);
		echo 'yes';
	}

	public function sign_out(){
		$this->session->sess_destroy();
        redirect('admin/login');
	}


	public function update_password(){
		$req = $this->input->post();
		$where['id'] = $req['id'];
		$where['user_pass'] = sha1($req['old_pass']);
		$info = $this->auth_m->get_member($where);
		if(empty($info)){
			echo "no";
			exit();
		}
		
		$update_where['id'] = $req['id'];
		$update_info['user_pass'] = sha1($req['new_pass']);
		$this->auth_m->update_member($update_info, $update_where);
		echo 'yes';
	}

	public function update_profile(){
		$req = $this->input->post();
		$where1 = "email = '" . strtolower($req['email']) . "' AND id != " . $this->session->admin_data['id'];
		$user_exist = $this->auth_m->get_member($where1);
		if($user_exist){
			echo 'no';
			return;
		}

		$where['id'] = $this->session->admin_data['id'];
		$update['email'] = strtolower($req['email']);
		$update['user_name'] = "Super Admin";
		$this->auth_m->update_member($update, $where);
		
		$info = $this->auth_m->get_member($where);
		$info['is_loggedin'] = true;
		$this->session->set_userdata('admin_data', $info);
		echo 'yes';
	}


	public function forgot_password(){
		$email = strtolower($this->input->post('email'));

		$where['email'] = $email;
		$where['role'] = '0';	//super admin role
		$info = $this->auth_m->get_member($where);
		if(empty($info)){
			echo "no";
			return;
		}

		$new_password = $this->randomPassword();
		
		// reset password
		$update_info['user_pass'] = sha1($new_password);

		$where_update['id'] = $info['id'];
		$this->auth_m->update_member($update_info, $where_update);

		$result = $this->send_email($email, $new_password);
		if($result){
			echo 'ok';
		}else{
			echo 'failed';	
		}
		return;
	}


	private function randomPassword() {
	    $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
	    $pass = array();
	    $alphaLength = strlen($alphabet) - 1;
	    for ($i = 0; $i < 8; $i++) {
	        $n = rand(0, $alphaLength);
	        $pass[] = $alphabet[$n];
	    }
	    return implode($pass);
	}

	private function send_email($email, $password){
		$mail = new PHPMailer;

		try {
		    //Server settings
		    $mail->isSMTP();
		   
		   	// $mail->SMTPDebug = 4;
		    
		    $mail->Host       = MAIL_HOST;  
		    $mail->SMTPAuth   = true;       
		    $mail->Username   = MAIL_USER;    
		    $mail->Password   = MAIL_PASS; 
		    $mail->CharSet =  "utf-8";
		    $mail->SMTPSecure = 'tls';
		    $mail->Port       = 587; 
		    $mail->setFrom(MAIL_TO_MAIL, 'Do not reply');
 
		    $mail->addAddress($email); 
		    
		    $mail->isHTML(true);                                  
		    $mail->Subject = "Please reset password!";
		    $mail->Body    = "<p>Please log in with this password: <b>" . $password . "</b></p>";
		    $mail->send();
		    return true;
		} catch (Exception $e) {
			// return $mail->ErrorInfo;
			return false;
		}
	}

}
