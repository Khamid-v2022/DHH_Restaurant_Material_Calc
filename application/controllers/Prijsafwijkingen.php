<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Prijsafwijkingen extends MY_Controller {

	public function __construct()
    {
        parent::__construct();
        $this->load->model('prijsafwijkingen_m');
    }

	public function index()
	{
		$data['primary_menu'] = 'Prijsafwijkingen';
		
		$this->load->view('header', $data);
		$this->load->view('prijsafwijkingen', $data);
		$this->load->view('template/footer');
	}

	public function get_list(){
		$list1 = $this->prijsafwijkingen_m->get_list_where("recepten_ticket", array("is_checked"=>"0"));
		$list2 = $this->prijsafwijkingen_m->get_list_where("ticket", array("is_checked"=>"0"));
		
		$data = [];
		$index = 0;
		
		for($index = 0; $index < count($list1); $index++){

			$approve = "<button type='button' class='btn border-info text-info-600 btn-flat btn-icon' onclick='approve(" . $list1[$index]['id'] . ", 1)' title='ACCEPTEER '><i class='icon-stack-check'></i></button>";
			$manual = "<button type='button' class='btn border-primary text-primary btn-flat btn-icon' onclick='manual(" . $list1[$index]['id'] . ", " . $list1[$index]['total'] . ", 1)' title='HANDMATIG'><i class='icon-touch'></i></button>";
			$reject = "<button type='button' class='btn border-warning text-warning-600 btn-flat btn-icon' onclick='reject(" . $list1[$index]['id'] . ", 1)' title='NEGEER'><i class='icon-stack-cancel'></i></button>";

			
			if($list1[$index]['old_price'] != 0){
				$chage_rate = number_format((abs($list1[$index]['total'] - $list1[$index]['old_price']) / $list1[$index]['old_price'] * 100), 2);
			}else{
				$chage_rate = 100;
			}

			$array_item = array($approve . $reject . $manual,  $list1[$index]['recept_naam'], $list1[$index]['old_price'], $list1[$index]['total'], $chage_rate . ' %', "Recept");
			$data[] = $array_item;
		}

		for($index = 0; $index < count($list2); $index++){

			$approve = "<button type='button' class='btn border-info text-info-600 btn-flat btn-icon' onclick='approve(" . $list2[$index]['id'] . ", 2)' title='ACCEPTEER '><i class='icon-stack-check'></i></button>";
			$manual = "<button type='button' class='btn border-primary text-primary btn-flat btn-icon' onclick='manual(" . $list2[$index]['id'] . ", " . $list2[$index]['advies_verkoopprijs'] . ", 2)' title='HANDMATIG'><i class='icon-touch'></i></button>";
			$reject = "<button type='button' class='btn border-warning text-warning-600 btn-flat btn-icon' onclick='reject(" . $list2[$index]['id'] . ", 2)' title='NEGEER'><i class='icon-stack-cancel'></i></button>";


			if($list2[$index]['old_price'] != 0){
				$chage_rate = number_format((abs($list2[$index]['advies_verkoopprijs'] - $list2[$index]['old_price']) / $list2[$index]['old_price'] * 100), 2);
			}else{
				$chage_rate = 100;
			}

			$array_item = array($approve . $reject . $manual,  $list2[$index]['name'], $list2[$index]['old_price'], $list2[$index]['advies_verkoopprijs'], $chage_rate . ' %', "Verkoopproduct");
			$data[] = $array_item;
		}

		$result = array(      
	        "recordsTotal" => count($list1 + $list2),
	        "recordsFiltered" => count($list1 + $list2),
	        "data" => $data
	    );

	    echo json_encode($result);
	    exit();

	}

	public function approve($id, $type){
		// type: 1=>recepten_ticket, 2=>ticket
		$update_info['is_checked'] = '1';
		if($type == '1'){
			$table_name = 'recepten_ticket';
		}else{
			$table_name = 'ticket';
		}
		$this->prijsafwijkingen_m->update_item($table_name, $update_info, array('id'=>$id));
		$this->generate_json("Updated!");
	}


	public function reject($id, $type){
		// type: 1=>recepten_ticket, 2=>ticket
		$update_info['is_checked'] = '2';
		if($type == '1'){
			$table_name = 'recepten_ticket';
		}else{
			$table_name = 'ticket';
		}
		
		$old_info = $this->prijsafwijkingen_m->get_item($table_name, array('id'=>$id));

		if($type == '1'){
			$update_info['total'] = $old_info['old_price'];
		}else{
			$update_info['advies_verkoopprijs'] = $old_info['old_price'];
		}

		$this->prijsafwijkingen_m->update_item($table_name, $update_info, array('id'=>$id));
		$this->generate_json("Rejected!");
	}

	public function save_manual_price(){
		$req = $this->input->post();


		if($req['type'] == '1'){
			$update_info['total'] = $req['handmatig_prijs'];
			$table_name = 'recepten_ticket';
		}else{
			$update_info['advies_verkoopprijs'] = $req['handmatig_prijs'];
			$table_name = 'ticket';
		}

		$update_info['updated_at'] = date("Y-m-d H:i:s");

		$where['id'] = $req['id'];

		$this->prijsafwijkingen_m->update_item($table_name, $update_info, $where);
		$this->generate_json("Updated");
	}
	
}
?>