<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'controllers/Base_Controller.php';

class Cr_db extends Base_Controller {

	public function __construct(){
        parent::__construct();
        $this->load->model('calculatie_re_m');
        $this->load->model('leverancierslijst_m');
    }


	public function index() {
		
		$data['primary_menu'] = 'Database recepten';
		
		$this->load->view('admin/header', $data);
		$this->load->view('admin/cr_db', $data);
		$this->load->view('template/footer');
	}

	public function get_list(){
		$list = $this->calculatie_re_m->get_tickets();
		
		$data = [];
		$index = 0;

		$leveranciernaam = '';
		$leveranciernaam_list = $this->calculatie_re_m->get_list('basic_leveranciernaam');
		if(count($leveranciernaam_list) > 0){
			$leveranciernaam = $leveranciernaam_list[0]['name'];
		}
		
		for($index = 0; $index < count($list); $index++){

			$edit = "<button type='button' class='btn border-info text-info-600 btn-flat btn-icon' onclick='edit_ticket(" . $list[$index]['id'] . ")' title='edit'><i class='icon-pencil'></i></button>";
			$bin = "<button type='button' class='btn border-warning text-warning-600 btn-flat btn-icon' onclick='delete_ticket(" . $list[$index]['id'] . ")' title='delete'><i class='icon-bin'></i></button>";
			$netto_prijs_kleinste = '-';
			if($list[$index]['total_gewicht'] > 0){
				$netto_prijs_kleinste = number_format($list[$index]['total'] / $list[$index]['total_gewicht'], 7);
			}

			$array_item = array($edit . $bin, $list[$index]['recept_naam'], $leveranciernaam, $list[$index]['ink_name'], $list[$index]['recept_id'], $list[$index]['verlies_procentage'] . '%', "-", '€  ' . number_format($list[$index]['total'], 7, ',', '.'), $list[$index]['total_gewicht'], $list[$index]['eenheden_name'], $netto_prijs_kleinste, $list[$index]['kleinste_name']);
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
