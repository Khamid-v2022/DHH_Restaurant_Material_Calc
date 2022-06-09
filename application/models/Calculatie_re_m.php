<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Calculatie_re_m extends MY_Model{
	
	public function get_last_unsubmitted_ticket(){
		$this->db->select("t.*");
		$this->db->from("recepten_ticket t");
		$this->db->where('is_calculated', '0');
		$this->db->order_by('t.created_at', 'DESC');
		$this->db->limit(1);
		return $this->db->get()->row_array();
	}

	public function get_tickets(){
		$this->db->select("t.*, ink.name AS ink_name, een.name AS eenheden_name, omz.name AS omz_name, kle.name AS kleinste_name, heid.name AS eenheid_name, lo.name AS locatie, btw.btw AS btw, btw.btw_factor AS factor");
		$this->db->from("recepten_ticket t");
		$this->db->join('basic_inkoopcategorien ink', 'ink.id = t.inkoopcategorie_id', 'left');
		$this->db->join('basic_eenheden een', 'een.id = t.eenheden_id', 'left');
		$this->db->join('basic_omzetgroepen omz', 'omz.id = t.omzetgroepen_id', 'left');
		$this->db->join('basic_kleinste kle', 'kle.id = t.kleinste_id', 'left');
		$this->db->join('basic_eenheid heid', 'heid.id = t.eenheid_id', 'left');
		$this->db->join('basic_locatie lo', 'lo.id = t.locatie_id', 'left');
		$this->db->join('basic_btw btw', 'btw.id = t.btw_id', 'left');

		$this->db->where('is_calculated', '1');
		$this->db->order_by('t.created_at', 'DESC');
		return $this->db->get()->result_array();
	}



	public function get_ticket_inkoopartikelens_disposables($where){
		$this->db->select("t_i.*, l.geef_productnaam as ink_dis_name");
		$this->db->from('recepten_ticket_inkoopatikelen_disposable t_i');
		$this->db->join('leverancierslijst l', 'l.id = t_i.leverancierslijst_id', 'left');
		$this->db->where($where);
		return $this->db->get()->result_array();
	}

	public function get_max_receptId(){
		$this->db->select('MAX(artikelnummer) AS max_recepten_id');
		return $this->db->get('leverancierslijst')->row_array();
	}



	public function get_tickets_by_leverancierId($leverancierslijst_id){
		$this->db->distinct();
		$this->db->select("recepten_ticket_id AS ticket_id");
		$this->db->from('recepten_ticket_inkoopatikelen_disposable');
		$this->db->where('leverancierslijst_id', $leverancierslijst_id);
		return $this->db->get()->result_array();
	}

	public function get_subTotal($ticket_id, $type){
		$this->db->select("IFNULL(SUM(kostprijs), 0) AS sub_total");
		$this->db->from('recepten_ticket_inkoopatikelen_disposable');
		$this->db->where('recepten_ticket_id', $ticket_id);
		$this->db->where('type', $type);
		return $this->db->get()->row_array();
	}
}

?>