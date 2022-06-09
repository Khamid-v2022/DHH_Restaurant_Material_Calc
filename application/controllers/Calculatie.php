<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH . 'controllers/Base_Controller.php';
class Calculatie extends Base_Controller {

	public function __construct(){
        parent::__construct();
        $this->load->model('calculatie_m');
        $this->load->model('leverancierslijst_m');
    }


	public function index() {
		
		$data['primary_menu'] = 'Calculatie Verkoopproducten';
		
		// $data['ticket'] = $this->calculatie_m->get_last_unsubmitted_ticket();
		$data['leverancierslijsts'] = $this->leverancierslijst_m->get_list("");
		// if($data['ticket']){
		// 	$where['ticket_id'] = $data['ticket']['id'];
		// 	$data['lists'] = $this->calculatie_m->get_ticket_inkoopartikelens_disposables($where);
		// }

		$data['basic_margegroepens'] = $this->calculatie_m->get_list('basic_margegroepen');
		$data['basic_bezorgings'] = $this->calculatie_m->get_list('basic_bezorging');
		$data['basic_btws'] = $this->calculatie_m->get_list('basic_btw');
		$data['basic_omzetgroepens'] = $this->calculatie_m->get_list('basic_omzetgroepen');
		$data['basic_inkoopcategoriens'] = $this->calculatie_m->get_list('basic_inkoopcategorien');
		$data['basic_eenhedens'] = $this->calculatie_m->get_list('basic_eenheden');
		$data['basic_kleinstes'] = $this->calculatie_m->get_list('basic_kleinste');

		$this->load->view('admin/header', $data);
		$this->load->view('admin/calculatie', $data);
		$this->load->view('template/footer');
	}

	public function edit_ticket($ticket_id){
		$data['primary_menu'] = 'Calculatie Verkoopproducten';
		
		$data['ticket'] = $this->calculatie_m->get_ticket($ticket_id);
		$data['leverancierslijsts'] = $this->leverancierslijst_m->get_list("");
		if($data['ticket']){
			$where['ticket_id'] = $data['ticket']['id'];
			$data['lists'] = $this->calculatie_m->get_ticket_inkoopartikelens_disposables($where);
		}

		$data['basic_margegroepens'] = $this->calculatie_m->get_list('basic_margegroepen');
		$data['basic_bezorgings'] = $this->calculatie_m->get_list('basic_bezorging');
		$data['basic_btws'] = $this->calculatie_m->get_list('basic_btw');
		$data['basic_omzetgroepens'] = $this->calculatie_m->get_list('basic_omzetgroepen');
		$data['basic_inkoopcategoriens'] = $this->calculatie_m->get_list('basic_inkoopcategorien');
		$data['basic_eenhedens'] = $this->calculatie_m->get_list('basic_eenheden');
		$data['basic_kleinstes'] = $this->calculatie_m->get_list('basic_kleinste');

		$this->load->view('admin/header', $data);
		$this->load->view('admin/calculatie', $data);
		$this->load->view('template/footer');
	}

	public function copy_ticket($ticket_id){
		$ticket_info = $this->calculatie_m->get_item('ticket', array('id'=>$ticket_id));
		unset($ticket_info['id']);
		$ticket_info['created_at'] = date('Y-m-d H:i:s');
		$ticket_info['submitted_at'] = NULL;
		$ticket_info['updated_at'] = NULL;
		$ticket_info['is_calculated'] = '0';
		$new_ticket_id = $this->calculatie_m->add_item('ticket', $ticket_info);

		$ticket_items = $this->calculatie_m->get_list_where('ticket_inkoopartikelens_disposables', array('ticket_id'=>$ticket_id));
		foreach($ticket_items as $item){
			unset($item['id']);
			$item['ticket_id'] = $new_ticket_id;
			$item['created_at'] = date('Y-m-d H:i:s');
			$this->calculatie_m->add_item('ticket_inkoopartikelens_disposables', $item);
		}
		$this->edit_ticket($new_ticket_id);
	}

	public function get_leverancierslijst_info($id){
		$item = $this->leverancierslijst_m->get_item_byId($id);
		$this->generate_json($item);
	}

	public function get_margegroepen_info($id){
		$this->generate_json($this->calculatie_m->get_item('basic_margegroepen', array('id'=>$id)));
	}

	public function get_bezorging_info($id){
		$this->generate_json($this->calculatie_m->get_item('basic_bezorging', array('id'=>$id)));
	}

	public function get_btw_info($id){
		$this->generate_json($this->calculatie_m->get_item('basic_btw', array('id'=>$id)));
	}

	public function save_ticket_inkoopartikelens_disposables(){
		$req = $this->input->post();
		
		$ticket_id = $req['ticket_id'];
		if($ticket_id == '0'){
			// create ticket
			$ticket_id = $this->create_ticket();
		}

		$req['ticket_id'] = $ticket_id;
		$req['created_at'] = date("Y-m-d H:i:s");
		$this->calculatie_m->add_item('ticket_inkoopartikelens_disposables', $req);
		$this->generate_json("Saved!");
	}

	public function save_ticket(){
		$req = $this->input->post();

		if(!empty($req['item_list'])){
			$list = $req['item_list'];
			unset($req['item_list']);
		}
		
		$ticket_id = $req['id'];
		if($ticket_id == '0'){
			// create ticket			
			$ticket_id = $this->create_ticket();
		}

		$req['id'] = $ticket_id;
		$req['submitted_at'] = date("Y-m-d H:i:s");
		$req['is_calculated'] = '1';

		$where['id'] = $ticket_id;
		$this->calculatie_m->update_item('ticket', $req, $where);


		// delete & save inkoopartikelens, disposables

		$this->calculatie_m->delete_item('ticket_inkoopartikelens_disposables', array("ticket_id"=>$ticket_id));

		if(isset($list)){
			foreach($list as $item){
				$info['ticket_id'] = $ticket_id;
				$info['leverancierslijst_id'] = $item['leverancierslijst_id'];
				$info['netto_prijs'] = $item['netto_prijs'];
				$info['eenheid_kleinste'] = $item['eenheid_kleinste'];
				$info['benodigde_hoeveelheid'] = $item['benodigde_hoeveelheid'];
				$info['kostprijs'] = $item['kostprijs'];
				$info['type'] = $item['type'];
				if(!isset($item['id'])){
					$info['created_at'] = date("Y-m-d H:i:s");
				}
				$this->calculatie_m->add_item('ticket_inkoopartikelens_disposables', $info);
			}
		}
		$this->generate_json("Saved!");

	}

	public function create_ticket(){
		$info['created_at'] = date("Y-m-d H:i:s");
		return $this->calculatie_m->add_item('ticket', $info);
	}

	public function create_ticket_ajax(){
		$ticket_id = $this->create_ticket();
		$this->generate_json($ticket_id);
	}

	public function delete_ticket($ticket_id){
		$where['ticket_id'] = $ticket_id;
		$this->calculatie_m->delete_item('ticket_inkoopartikelens_disposables', $where);
		$this->calculatie_m->delete_item('ticket', array('id'=>$ticket_id));
		$this->generate_json("Deleted!");
	}

}
