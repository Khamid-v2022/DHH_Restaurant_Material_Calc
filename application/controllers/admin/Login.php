<?php
defined('BASEPATH') OR exit('No direct script access allowed');

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


	// public function update_password(){
	// 	$req = $this->input->post();
	// 	$where['id'] = $req['id'];
	// 	$where['password'] = sha1($req['old_pass']);
	// 	$info = $this->auth_m->get_member($where);
	// 	if(empty($info)){
	// 		echo "no";
	// 		exit();
	// 	}
		
	// 	$update_where['id'] = $req['id'];
	// 	$update_info['password'] = sha1($req['new_pass']);
	// 	$this->auth_m->update_member($update_info, $update_where);
	// 	echo 'yes';
	// }

	// public function update_profile(){
	// 	$req = $this->input->post();
	// 	$where1 = "email = '" . strtolower($req['email']) . "' AND id != " . $this->session->admin_data['id'];
	// 	$user_exist = $this->auth_m->get_member($where1);
	// 	if($user_exist){
	// 		echo 'no';
	// 		return;
	// 	}

	// 	$where['id'] = $this->session->admin_data['id'];
	// 	$update['email'] = strtolower($req['email']);
	// 	$this->auth_m->update_member($update, $where);
		
	// 	$info = $this->auth_m->get_member($where);
	// 	$info['is_loggedin'] = true;
	// 	$this->session->set_userdata('admin_data', $info);
	// 	echo 'yes';
	// }

}
