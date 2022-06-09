<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Leverancierslijst_m extends MY_Model{
	public function get_list($table_name, $order_by=null){
		$this->db->select("leve.*, b_leve.name AS leveranciers, b_l.name AS locatie, b_i.name AS inkoopcategorien, b_ei.name AS eenheid, b_ed.name AS eenheden, b_k.name AS kleinste, b_s.statiegeld AS verpakking, b_s.price AS statiegeld");
		$this->db->from('leverancierslijst leve');
		$this->db->join('basic_leveranciers b_leve', 'leve.leveranciers_id = b_leve.id', 'left');
		$this->db->join('basic_locatie b_l', 'leve.locatie_id = b_l.id', 'left');
		$this->db->join('basic_inkoopcategorien b_i', 'leve.inkoopcategorien_id = b_i.id', 'left');
		$this->db->join('basic_eenheid b_ei', 'leve.eenheid_id = b_ei.id', 'left');
		$this->db->join('basic_eenheden b_ed', 'leve.eenheden_id = b_ed.id', 'left');
		$this->db->join('basic_kleinste b_k', 'leve.kleinste_eenheid_id = b_k.id', 'left');
		$this->db->join('basic_statiegeld b_s', 'leve.statiegeld_id = b_s.id', 'left');
		$this->db->order_by('geef_productnaam', 'asc');
		return $this->db->get()->result_array();
	}

	public function get_item_byId($id){
		$this->db->select("leve.*, b_l.name AS locatie, b_i.name AS inkoopcategorien, b_ei.name AS eenheid, b_ed.name AS eenheden, b_k.name AS kleinste, b_s.statiegeld AS verpakking, b_s.price AS statiegeld");
		$this->db->from('leverancierslijst leve');
		$this->db->join('basic_locatie b_l', 'leve.locatie_id = b_l.id', 'left');
		$this->db->join('basic_inkoopcategorien b_i', 'leve.inkoopcategorien_id = b_i.id', 'left');
		$this->db->join('basic_eenheid b_ei', 'leve.eenheid_id = b_ei.id', 'left');
		$this->db->join('basic_eenheden b_ed', 'leve.eenheden_id = b_ed.id', 'left');
		$this->db->join('basic_kleinste b_k', 'leve.kleinste_eenheid_id = b_k.id', 'left');
		$this->db->join('basic_statiegeld b_s', 'leve.statiegeld_id = b_s.id', 'left');
		$this->db->where('leve.id', $id);
		return $this->db->get()->row_array();
	} 
}
?>