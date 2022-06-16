<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends MY_Controller {

	public function __construct()
    {
        parent::__construct();
    }

	public function index()
	{
		$data['primary_menu'] = 'Home';
		$this->load->view('header', $data);
		$this->load->view('home');
		$this->load->view('template/footer');
	}
}
