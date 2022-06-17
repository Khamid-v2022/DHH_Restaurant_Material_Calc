<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Calculatie_re_m extends MY_Model{


	public function get_tickets($company_id){
		$this->db->select("t.*, ink.name AS ink_name, een.name AS eenheden_name, omz.name AS omz_name, kle.name AS kleinste_name, heid.name AS eenheid_name, lo.name AS locatie, btw.btw AS btw, btw.btw_factor AS factor");
		$this->db->from("recepten_ticket t");
		$this->db->join('basic_inkoopcategorien ink', 'ink.id = t.inkoopcategorie_id AND ink.company_id = ' . $company_id, 'left');
		$this->db->join('basic_eenheden een', 'een.id = t.eenheden_id', 'left');
		$this->db->join('basic_omzetgroepen omz', 'omz.id = t.omzetgroepen_id AND omz.company_id = ' . $company_id, 'left');
		$this->db->join('basic_kleinste kle', 'kle.id = t.kleinste_id', 'left');
		$this->db->join('basic_eenheid heid', 'heid.id = t.eenheid_id AND heid.company_id = ' . $company_id, 'left');
		$this->db->join('basic_locatie lo', 'lo.id = t.locatie_id AND lo.company_id = ' . $company_id, 'left');
		$this->db->join('basic_btw btw', 'btw.id = t.btw_id AND btw.company_id = ' . $company_id, 'left');

		$this->db->where('is_calculated', '1');
		$this->db->where('t.company_id', $company_id);
		$this->db->order_by('t.created_at', 'DESC');
		return $this->db->get()->result_array();
	}



	public function get_ticket_inkoopartikelens_disposables($where, $company_id){
		$this->db->select("t_i.*, l.geef_productnaam as ink_dis_name");
		$this->db->from('recepten_ticket_inkoopatikelen_disposable t_i');
		$this->db->join('leverancierslijst l', 'l.id = t_i.leverancierslijst_id AND t_i.company_id = ' . $company_id, 'left');
		$this->db->where($where);
		return $this->db->get()->result_array();
	}

	public function get_max_receptId($company_id){
		$this->db->select('MAX(artikelnummer) AS max_recepten_id');
		$this->db->where('company_id', $company_id);
		return $this->db->get('leverancierslijst')->row_array();
	}



	public function get_tickets_by_leverancierId($leverancierslijst_id, $company_id){
		$this->db->distinct();
		$this->db->select("recepten_ticket_id AS ticket_id");
		$this->db->from('recepten_ticket_inkoopatikelen_disposable');
		$this->db->where('leverancierslijst_id', $leverancierslijst_id);
		$this->db->where('company_id', $company_id);
		return $this->db->get()->result_array();
	}

	public function get_subTotal($ticket_id, $type, $company_id){
		$this->db->select("IFNULL(SUM(kostprijs), 0) AS sub_total");
		$this->db->from('recepten_ticket_inkoopatikelen_disposable');
		$this->db->where('recepten_ticket_id', $ticket_id);
		$this->db->where('type', $type);
		return $this->db->get()->row_array();
	}
}

?>