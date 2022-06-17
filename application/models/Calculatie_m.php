<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Calculatie_m extends MY_Model{

	public function get_ticket($ticket_id, $company_id){
		$this->db->select("t.*, m1.margegroepen AS margegroepen1, m1.marge AS marge1, m2.margegroepen AS margegroepen2, m2.marge AS marge2, b.bezorging AS bezorging_name, b.bezorgfee, btw.btw AS btw_name, btw.btw_factor");
		$this->db->from("ticket t");
		$this->db->join('basic_margegroepen m1', 'm1.id = t.margegroepen_id1 AND m1.company_id = ' . $company_id, 'left');
		$this->db->join('basic_margegroepen m2', 'm2.id = t.margegroepen_id2 AND m2.company_id = ' . $company_id, 'left');
		$this->db->join('basic_bezorging b', 'b.id = t.bezorging_id AND b.company_id = ' . $company_id, 'left');
		$this->db->join('basic_btw btw', 'btw.id = t.btw_id AND btw.company_id = ' . $company_id, 'left');
		$this->db->where('t.id', $ticket_id);
		return $this->db->get()->row_array();
	}

	public function get_tickets($company_id){
		$this->db->select("t.*, m1.margegroepen AS margegroepen1, m1.marge AS marge1, m2.margegroepen AS margegroepen2, m2.marge AS marge2, b.bezorging AS bezorging_name, b.bezorgfee, btw.btw AS btw_name, btw.btw_factor, ink.name AS ink_name, een.name AS eenheid_name, kle.name AS kleinste_name");
		$this->db->from("ticket t");
		$this->db->join('basic_margegroepen m1', 'm1.id = t.margegroepen_id1 AND m1.company_id = ' . $company_id, 'left');
		$this->db->join('basic_margegroepen m2', 'm2.id = t.margegroepen_id2 AND m2.company_id = ' . $company_id, 'left');
		$this->db->join('basic_bezorging b', 'b.id = t.bezorging_id AND b.company_id = ' . $company_id, 'left');
		$this->db->join('basic_btw btw', 'btw.id = t.btw_id AND btw.company_id = ' . $company_id, 'left');
		$this->db->join('basic_inkoopcategorien ink', 'ink.id = t.inkoopcategorien_id AND ink.company_id = ' . $company_id, 'left');
		$this->db->join('basic_eenheden een', 'een.id = t.eenheden_id', 'left');
		$this->db->join('basic_kleinste kle', 'kle.id = t.kleinste_id', 'left');
		$this->db->where('is_calculated', '1');
		$this->db->where('t.company_id', $company_id);
		$this->db->order_by('t.created_at', 'DESC');
		return $this->db->get()->result_array();
	}

	public function get_ticket_inkoopartikelens_disposables($where, $company_id){
		$this->db->select("t_i.*, l.geef_productnaam as ink_dis_name");
		$this->db->from('ticket_inkoopartikelens_disposables t_i');
		$this->db->join('leverancierslijst l', 'l.id = t_i.leverancierslijst_id AND l.company_id = ' . $company_id, 'left');
		$this->db->where($where);
		$this->db->where('t_i.company_id', $company_id);
		return $this->db->get()->result_array();
	}



	public function get_tickets_by_leverancierId($leverancierslijst_id, $company_id){
		$this->db->distinct();
		$this->db->select("ticket_id");
		$this->db->from('ticket_inkoopartikelens_disposables');
		$this->db->where('leverancierslijst_id', $leverancierslijst_id);
		$this->db->where('company_id', $company_id);
		return $this->db->get()->result_array();
	}

	public function get_subTotal($ticket_id, $type, $company_id){
		$this->db->select("IFNULL(SUM(kostprijs), 0) AS sub_total");
		$this->db->from('ticket_inkoopartikelens_disposables');
		$this->db->where('ticket_id', $ticket_id);
		$this->db->where('type', $type);
		$this->db->where('company_id', $company_id);
		return $this->db->get()->row_array();
	}

	public function get_detailed_ticket_info($ticket_id, $company_id){
		$this->db->select("ticket.*, IFNULL(m1.marge, 0) AS marge1, IFNULL(m2.marge, 0) AS marge2,  IFNULL(b.bezorgfee, 0) AS bezorgfee, IFNULL(btw.btw_factor, 0) AS btw_factor");
		$this->db->from("ticket");
		$this->db->join('basic_margegroepen m1', 'ticket.margegroepen_id1 = m1.id AND m1.company_id = ' . $company_id, 'left');
		$this->db->join('basic_margegroepen m2', 'ticket.margegroepen_id2 = m2.id AND m2.company_id = ' . $company_id, 'left');
		$this->db->join('basic_bezorging b', 'ticket.bezorging_id = b.id AND b.company_id = ' . $company_id, 'left');
		$this->db->join('basic_btw btw', 'ticket.btw_id = btw.id AND btw.company_id = ' . $company_id, 'left');
		$this->db->where('ticket.id', $ticket_id);
		$this->db->where('ticket.company_id', $company_id);
		return $this->db->get()->row_array();
	}
}

?>