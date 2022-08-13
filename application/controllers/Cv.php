<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cv extends MY_Controller {

	public function __construct(){
        parent::__construct();
        $this->load->model('calculatie_m');
        $this->load->model('leverancierslijst_m');
    }


	public function index() {
		
		$data['primary_menu'] = 'Database verkoopproducten';
		
		$this->load->view('header', $data);
		$this->load->view('cv_db', $data);
		$this->load->view('template/footer');
	}

	public function get_list(){
		$list = $this->calculatie_m->get_tickets($this->session->user_data['company_id']);
		
		$data = [];
		$index = 0;
		
		for($index = 0; $index < count($list); $index++){

			$edit = "<button type='button' class='btn border-info text-info-600 btn-flat btn-icon' onclick='edit_ticket(" . $list[$index]['id'] . ")' title='edit'><i class='icon-pencil'></i></button>";
			$bin = "<button type='button' class='btn border-warning text-warning-600 btn-flat btn-icon' onclick='delete_ticket(" . $list[$index]['id'] . ")' title='delete'><i class='icon-bin'></i></button>";
			$copy = "<button type='button' class='btn border-primary text-primary btn-flat btn-icon' onclick='copy_ticket(" . $list[$index]['id'] . ")' title='copy'><i class='icon-copy3'></i></button>";

			$nettor = 0;
			if($list[$index]['btw_factor'] != 0){
				$top = $list[$index]['advies_verkoopprijs'];
				if($list[$index]['handmatige_verkoopprijs'] > 0)
					$top = $list[$index]['handmatige_verkoopprijs'];
				$nettor = number_format($top / $list[$index]['btw_factor'], 7, ',', '.');
			}
			
			$array_item = array(
				$edit . $copy . $bin, 
				$list[$index]['name'], 
				$list[$index]['ink_name'], 
				$list[$index]['eenheid_name'], 
				$nettor==0 ? '-' : '€  ' . $nettor, 
				$list[$index]['kleinste_name'], 
				'€  ' . number_format($list[$index]['advies_verkoopprijs'], 7, ',', '.'), 
				'€  ' . number_format($list[$index]['handmatige_verkoopprijs'], 7, ',', '.'),  
				$list[$index]['btw_name'] . '%', 
				$list[$index]['btw_factor']
			);
			
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

}
