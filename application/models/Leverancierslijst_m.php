<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Leverancierslijst_m extends MY_Model{
	public function get_list_leve($table_name, $company_id, $order_by=null){
		$this->db->select("leve.*, b_leve.name AS leveranciers, b_l.name AS locatie, b_i.name AS inkoopcategorien, b_ei.name AS eenheid, b_ed.name AS eenheden, b_k.name AS kleinste, b_s2.statiegeld AS omdoos, b_s.statiegeld AS verpakking, b_s.price AS statiegeld");
		$this->db->from('leverancierslijst leve');
		$this->db->join('basic_leveranciers b_leve', 'leve.leveranciers_id = b_leve.id AND b_leve.company_id = ' . $company_id, 'left');
		$this->db->join('basic_locatie b_l', 'leve.locatie_id = b_l.id AND b_l.company_id = ' . $company_id, 'left');
		$this->db->join('basic_inkoopcategorien b_i', 'leve.inkoopcategorien_id = b_i.id AND b_i.company_id = ' . $company_id, 'left');
		$this->db->join('basic_eenheid b_ei', 'leve.eenheid_id = b_ei.id AND b_ei.company_id = ' . $company_id, 'left');
		$this->db->join('basic_eenheden b_ed', 'leve.eenheden_id = b_ed.id', 'left');
		$this->db->join('basic_kleinste b_k', 'leve.kleinste_eenheid_id = b_k.id', 'left');
		$this->db->join('basic_statiegeld b_s2', 'leve.statiegeld_omdoos_id = b_s2.id AND b_s2.company_id = ' . $company_id, 'left');
		$this->db->join('basic_statiegeld b_s', 'leve.statiegeld_id = b_s.id AND b_s.company_id = ' . $company_id, 'left');
		$this->db->where('leve.company_id', $company_id);
		$this->db->order_by('geef_productnaam', 'asc');

		return $this->db->get()->result_array();
	}

	public function get_item_byId($id, $company_id){
		$company_id = $this->session->user_data['company_id'];

		$this->db->select("leve.*, b_l.name AS locatie, b_i.name AS inkoopcategorien, b_ei.name AS eenheid, b_ed.name AS eenheden, b_k.name AS kleinste, b_s2.statiegeld AS omdoos, b_s.statiegeld AS verpakking, b_s.price AS statiegeld");
		$this->db->from('leverancierslijst leve');
		$this->db->join('basic_locatie b_l', 'leve.locatie_id = b_l.id AND b_l.company_id = ' . $company_id, 'left');
		$this->db->join('basic_inkoopcategorien b_i', 'leve.inkoopcategorien_id = b_i.id AND b_i.company_id = ' . $company_id, 'left');
		$this->db->join('basic_eenheid b_ei', 'leve.eenheid_id = b_ei.id AND b_ei.company_id = ' . $company_id, 'left');
		$this->db->join('basic_eenheden b_ed', 'leve.eenheden_id = b_ed.id', 'left');
		$this->db->join('basic_kleinste b_k', 'leve.kleinste_eenheid_id = b_k.id', 'left');
		$this->db->join('basic_statiegeld b_s2', 'leve.statiegeld_omdoos_id = b_s2.id AND b_s2.company_id = ' . $company_id, 'left');
		$this->db->join('basic_statiegeld b_s', 'leve.statiegeld_id = b_s.id AND b_s.company_id = ' . $company_id, 'left');
		$this->db->where('leve.id', $id);
		return $this->db->get()->row_array();
	} 
}
?>