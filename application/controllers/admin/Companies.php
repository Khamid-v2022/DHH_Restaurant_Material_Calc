<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require __DIR__ . '/../../../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require __DIR__ . '/../../../vendor/phpmailer/phpmailer/src/PHPMailer.php';
require __DIR__ . '/../../../vendor/phpmailer/phpmailer/src/SMTP.php';
require __DIR__ . '/../../../vendor/phpmailer/phpmailer/src/Exception.php';

require_once APPPATH . 'controllers/admin/Base_Controller.php';

class Companies extends Base_Controller {

	public function __construct(){
        parent::__construct();
        $this->load->model('function_m');
        $this->load->model('auth_m');
    }

	public function index() {
		$data['primary_menu'] = 'Companies';
		
		$this->load->view('admin/header', $data);
		$this->load->view('admin/companies', $data);
		$this->load->view('template/footer');
	}

	public function get_users(){
		$list = $this->function_m->get_list_where('admin', array('role' => '1'));
	
		$data = [];
		
		for($index = 0; $index < count($list); $index++){
			$edit = "<button type='button' class='btn border-info text-info-600 btn-flat btn-icon' onclick='edit_user(\"" . $list[$index]['user_name'] . "\", \"" . $list[$index]['email'] . "\", \"" . $list[$index]['company_name'] . "\", " . $list[$index]['id'] . ")' title='edit'><i class='icon-pencil'></i></button>";
			$bin = "<button type='button' class='btn border-warning text-warning-600 btn-flat btn-icon position-right' onclick='delete_user(" . $list[$index]['id'] . ")' title='delete'><i class='icon-bin'></i></button>";
			$resset_pass = "<button type='button' class='btn border-success text-success btn-flat btn-icon position-right' onclick='reset_password(" . $list[$index]['id'] . ")' title='reset password'><i class='icon-unlocked2'></i></button>";

			if($list[$index]['role'] == "1")
				$role = "Admin";
			else
				$role = "User";
			$array_item = array($list[$index]['company_name'], $list[$index]['user_name'], $list[$index]['email'], $role, $edit . $bin . $resset_pass);

			$data[] = $array_item;
		}

		$result = array(      
	        "recordsTotal" => count($list),
	        "recordsFiltered" => count($list),
	        "data" => $data
	    );

	    echo json_encode($result);
	    exit();
	}

	public function save_company(){
		$req = $this->input->post();
		switch($req['action_type']){
			case 'add':
				$info['email'] = $req['email'];
				$exist_item = $this->function_m->get_item('admin', $info);
				if($exist_item){
					$this->generate_json("This email already registred!", false);
					return;
				}

				unset($req['action_type']);
				unset($req['sel_id']);

				// get max company_id
				$req['company_id'] = intval($this->auth_m->get_max_companyId()['max_company_id']) + 1;
				
				$req['user_pass'] = sha1("Admin123456!");
				$req['email'] = strtolower($req['email']);
				$req['role'] = '1';			//company admin

				$this->function_m->add_item('admin', $req);

				

				// create basic functions table rows for this company
				
				$this->copy_basic_tables($req['company_id']);
				
				$this->generate_json("Saved");
				$this->send_email($req['email']);
				break;
			case 'edit':
				$where1 = "email = '" . strtolower($req['email']) . "' AND id != " . $req['sel_id'];
				$user_exist = $this->auth_m->get_member($where1);
				if($user_exist){
					$this->generate_json("This email is being used by another user", false);
					return;
				}

				$where['id'] = $req['sel_id'];
				unset($req['action_type']);
				unset($req['sel_id']);
				
				$req['email'] = strtolower($req['email']);

				$this->function_m->update_item('admin', $req, $where);
				$this->generate_json("Updated");
				break;
		}
	}

	public function copy_basic_tables($new_company_id){
		foreach(BASIC_COPY_TABLES as $table_name){
			$list = $this->function_m->get_list_where($table_name, array('company_id' => '0'));
			for($index = 0; $index < count($list); $index++){
				unset($list[$index]['id']);
				$list[$index]['company_id'] = $new_company_id;
			}

			$this->function_m->add_list($table_name, $list);
		}
	}

	public function delete_user($id){
		$info = $this->function_m->get_item('admin', array('id'=>$id));

		$this->function_m->delete_item('admin', array('id'=>$id));
		// Check if there is an admin in the same company
		$exist = $this->function_m->get_list_where('admin', array('company_id' => $info['company_id']));
		if(count($exist) == 0){
			foreach(COMPANY_INFO_TABLES as $table_name){
				$this->function_m->delete_item($table_name, array('company_id' =>  $info['company_id']));
			}
		}


		$this->generate_json("Deleted!");
	}

	public function format_password($id){
		$info['user_pass'] = sha1("123456");
		$this->function_m->update_item('admin', $info, array("id"=>$id));
		$this->generate_json("The password has been reset to Admin123456!.");
	}

	private function send_email($email){
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
		    $mail->setFrom(MAIL_TO_MAIL, 'donotreply');
 
		    $mail->addAddress($email); 
		    
		    $mail->isHTML(true);                                  
		    $mail->Subject = "Welcome";
		    $mail->Body    = "<p>Welcome to DHH</p>";
		    $mail->Body    = "<p>Password: Admin123456!</p>";
		    $mail->Body    = "<p>Please login and reset password</p>";
		    $mail->Body    .= "<a href='https://dhh.calculatie.restaurant/'>https://dhh.calculatie.restaurant/</a>";
		    $mail->send();
		    return true;
		} catch (Exception $e) {
			// return $mail->ErrorInfo;
			return false;
		}
	}

}
