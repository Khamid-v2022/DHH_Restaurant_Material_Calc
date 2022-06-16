<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Calculatie_recepten extends MY_Controller {

	public function __construct(){
        parent::__construct();
        $this->load->model('calculatie_re_m');
        $this->load->model('leverancierslijst_m');
    }


	public function index() {
		
		$data['primary_menu'] = 'Calculatie Recepten';

		$data['mode'] = 0;

		$data['basic_inkoopcategoriens'] = $this->calculatie_re_m->get_list('basic_inkoopcategorien');
		$data['leverancierslijsts'] = $this->leverancierslijst_m->get_list("");
	
		$data['basic_eenhedens'] = $this->calculatie_re_m->get_list('basic_eenheden');
		$data['basic_omzetgroepens'] = $this->calculatie_re_m->get_list('basic_omzetgroepen');
		$data['basic_kleinstes'] = $this->calculatie_re_m->get_list('basic_kleinste');
		$data['basic_eenheids'] = $this->calculatie_re_m->get_list('basic_eenheid');
		$data['basic_locaties'] = $this->calculatie_re_m->get_list('basic_locatie');
		$data['basic_btws'] = $this->calculatie_re_m->get_list('basic_btw');

		$data['recept_id'] = $this->calculatie_re_m->get_max_receptId()['max_recepten_id'] + 1;

		$this->load->view('header', $data);
		$this->load->view('calculatie_recepten', $data);
		$this->load->view('template/footer');
	}

	// $mode: edit or new? edit = 1, new = 0
	public function edit_ticket($ticket_id, $mode = 1){
		$data['primary_menu'] = 'Calculatie Recepten';

		$data['mode'] = $mode;
		
		$data['ticket'] = $this->calculatie_re_m->get_item('recepten_ticket', array('id' => $ticket_id));

		$data['basic_inkoopcategoriens'] = $this->calculatie_re_m->get_list('basic_inkoopcategorien');
		$data['leverancierslijsts'] = $this->leverancierslijst_m->get_list("");
		
		if($data['ticket']){
			$where['recepten_ticket_id'] = $data['ticket']['id'];
			$data['lists'] = $this->calculatie_re_m->get_ticket_inkoopartikelens_disposables($where);
		}
		
		$data['basic_eenhedens'] = $this->calculatie_re_m->get_list('basic_eenheden');
		$data['basic_omzetgroepens'] = $this->calculatie_re_m->get_list('basic_omzetgroepen');
		
		$data['basic_kleinstes'] = $this->calculatie_re_m->get_list('basic_kleinste');
		$data['basic_eenheids'] = $this->calculatie_re_m->get_list('basic_eenheid');
		$data['basic_locaties'] = $this->calculatie_re_m->get_list('basic_locatie');
		$data['basic_btws'] = $this->calculatie_re_m->get_list('basic_btw');

		$this->load->view('header', $data);
		$this->load->view('calculatie_recepten', $data);
		$this->load->view('template/footer');
	}

	public function get_leverancierslijst_info($id){
		$item = $this->leverancierslijst_m->get_item_byId($id);
		$this->generate_json($item);
	}

	public function get_eenheden_info($id){
		$item = $this->calculatie_re_m->get_item('basic_eenheden', array('id'=>$id));
		$this->generate_json($item);
	}

	public function save_ticket(){
		$req = $this->input->post();
		
		$mode = $req['mode'];
		unset($req['mode']);

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
		$this->calculatie_re_m->update_item('recepten_ticket', $req, $where);


		// delete & save inkoopartikelens, disposables
		$this->calculatie_re_m->delete_item('recepten_ticket_inkoopatikelen_disposable', array("recepten_ticket_id"=>$ticket_id));
		if(isset($list)){
			foreach($list as $item){
				$item_info['recepten_ticket_id'] = $ticket_id;
				$item_info['leverancierslijst_id'] = $item['leverancierslijst_id'];
				$item_info['netto_prijs'] = $item['netto_prijs'];
				$item_info['eenheid_kleinste'] = $item['eenheid_kleinste'];
				$item_info['benodigde'] = $item['benodigde'];
				$item_info['kostprijs'] = $item['kostprijs'];
				$item_info['type'] = $item['type'];
				if(!isset($item['id'])){
					$item_info['created_at'] = date("Y-m-d H:i:s");
				}
				$this->calculatie_re_m->add_item('recepten_ticket_inkoopatikelen_disposable', $item_info);
			}
		}


		$info['geef_productnaam'] = $req['recept_naam'];
		$info['leveranciers_id'] = 4; 		//static =>De Hoge Heide
		$info['locatie_id'] = $req['locatie_id'];
		$info['inkoopcategorien_id'] = $req['inkoopcategorie_id'];
		$info['artikelnummer'] = $req['recept_id'];
		$info['prijs_van'] = $req['total'];
		$info['aantal_verpakkingen'] = $req['aantal_verpakkingen'];
		$info['eenheid_id'] = $req['eenheid_id'];
		
		if($info['aantal_verpakkingen'] > 0)
			$info['prijs_per'] = number_format($req['total'] / $req['aantal_verpakkingen'], 7);
		
		$info['inhoud_van'] = $req['total_gewicht'];
		$info['eenheden_id'] = $req['eenheden_id'];
		
		if(isset($info['prijs_per']) && $info['inhoud_van'] > 0)
			$info['prijs_per_eenheid'] = number_format($info['prijs_per'] / $info['inhoud_van'], 7);
		
		$info['kleinste_eenheid_id'] = $req['kleinste_id'];
		$info['netto_stuks_prijs'] = $req['total'];
		$info['recep_ticket_id'] = $ticket_id;
		//5 $info['statiegeld_id'] = 
		// $info['statiegeld_price'] = 
		if($mode == '1'){
			$where_leve['recep_ticket_id'] = $ticket_id;
			$this->calculatie_re_m->update_item('leverancierslijst', $info, $where_leve);
		}else{
			$this->calculatie_re_m->add_item('leverancierslijst', $info);
		}

		$this->generate_json("Saved!");

	}

	public function create_ticket(){
		$info['created_at'] = date("Y-m-d H:i:s");
		$info['recept_id'] = $this->calculatie_re_m->get_max_receptId()['max_recepten_id'] + 1;
		return $this->calculatie_re_m->add_item('recepten_ticket', $info);

	}

	public function create_ticket_ajax(){
		$ticket_id = $this->create_ticket();
		$this->generate_json($ticket_id);
	}

	public function delete_ticket($ticket_id){
		$where['recepten_ticket_id'] = $ticket_id;
		$this->calculatie_re_m->delete_item('recepten_ticket_inkoopatikelen_disposable', $where);
		$this->calculatie_re_m->delete_item('recepten_ticket', array('id'=>$ticket_id));
		$this->generate_json("Deleted!");
	}

}
