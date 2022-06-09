<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH . 'controllers/Base_Controller.php';

class Home extends Base_Controller {

	public function __construct()
    {
        parent::__construct();
    }

	public function index()
	{
		$data['primary_menu'] = 'Home';
		$this->load->view('admin/header', $data);
		$this->load->view('admin/home');
		$this->load->view('template/footer');
	}
}
