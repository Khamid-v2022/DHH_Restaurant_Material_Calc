<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Voorraadtelling_m extends MY_Model{
	public function get_list_join($table_name, $order_by, $where, $company_id){
		$this->db->select("leve.*, b_leve.name AS leveranciers, b_l.name AS locatie, b_i.name AS inkoopcategorien, b_ei.name AS eenheid, b_ed.name AS eenheden, b_k.name AS kleinste, b_s.statiegeld AS verpakking, b_s.price AS statiegeld");
		$this->db->from('leverancierslijst_copy leve');
		$this->db->join('basic_leveranciers b_leve', 'leve.leveranciers_id = b_leve.id AND b_leve.company_id = ' . $company_id, 'left');
		$this->db->join('basic_locatie b_l', 'leve.locatie_id = b_l.id AND b_l.company_id = ' . $company_id, 'left');
		$this->db->join('basic_inkoopcategorien b_i', 'leve.inkoopcategorien_id = b_i.id AND b_i.company_id = ' . $company_id, 'left');
		$this->db->join('basic_eenheid b_ei', 'leve.eenheid_id = b_ei.id AND b_ei.company_id = ' . $company_id, 'left');
		$this->db->join('basic_eenheden b_ed', 'leve.eenheden_id = b_ed.id', 'left');
		$this->db->join('basic_kleinste b_k', 'leve.kleinste_eenheid_id = b_k.id', 'left');
		$this->db->join('basic_statiegeld b_s', 'leve.statiegeld_id = b_s.id AND b_s.company_id = ' . $company_id, 'left');
		// $this->db->join('basic_statiegeld b_s', 'leve.statiegeld_los = b_s.id AND b_s.company_id = ' . $company_id, 'left');
		$this->db->where($where);
		$this->db->order_by($order_by);
		return $this->db->get()->result_array();
	}

	public function get_stock_list($id, $company_id){
		$query = "SELECT leve.*,b_leve.name AS leveranciers, b_l.name AS locatie, b_i.name AS inkoopcategorien, b_ei.name AS eenheid, b_ed.name AS eenheden, b_k.name AS kleinste, b_s.statiegeld AS verpakking, b_s.price AS statiegeld, IFNULL(puc.num, 0) AS num 
		FROM (
			SELECT * 
			FROM leverancierslijst_copy 
			WHERE voorraadtelling_id = " . $id . ") leve 
		INNER JOIN basic_leveranciers b_leve ON leve.leveranciers_id = b_leve.id AND b_leve.company_id = " . $company_id . " 
		INNER JOIN basic_locatie b_l ON leve.locatie_id = b_l.id AND b_l.company_id = " . $company_id  . " 
		INNER JOIN basic_inkoopcategorien b_i ON leve.inkoopcategorien_id = b_i.id AND b_i.company_id = " . $company_id . " 
		INNER JOIN basic_eenheid b_ei ON leve.eenheid_id = b_ei.id AND b_ei.company_id = " . $company_id . " 
		INNER JOIN basic_eenheden b_ed ON leve.eenheden_id = b_ed.id 
		INNER JOIN basic_kleinste b_k ON leve.kleinste_eenheid_id = b_k.id ";
		
		$query .= " INNER JOIN basic_statiegeld b_s ON leve.statiegeld_id = b_s.id AND b_s.company_id = " . $company_id ;
		// $query .= " INNER JOIN basic_statiegeld b_s ON leve.statiegeld_los = b_s.id AND b_s.company_id = " . $company_id ;
		
		$query .= " LEFT JOIN (	
			SELECT leve_copy_id, COUNT(id) num FROM voorraadteling_puchase WHERE company_id = " . $company_id . "
			GROUP BY leve_copy_id) puc 
		ON leve.id = puc.leve_copy_id 
		ORDER BY geef_productnaam ASC";

		$result = $this->db->query($query);
		return $result->result_array();
	}

	public function get_stock_list_by_location($id, $location_id, $company_id){
		$query = "SELECT leve.*, IFNULL(puc.num, 0) AS num 
		FROM (
			SELECT id, geef_productnaam 
			FROM leverancierslijst_copy 
			WHERE voorraadtelling_id = " . $id . " AND locatie_id = " . $location_id . ") leve 
		LEFT JOIN (	
			SELECT leve_copy_id, COUNT(id) num FROM voorraadteling_puchase WHERE company_id = " . $company_id . " 
			GROUP BY leve_copy_id) puc 
		ON leve.id = puc.leve_copy_id 
		ORDER BY geef_productnaam ASC";

		$result = $this->db->query($query);
		return $result->result_array();
	}

	public function get_stock_histories($leve_copy_id, $company_id){
		$query = "SELECT copy.geef_productnaam, his.*, copy.inhoud_van, copy.prijs_van, b_s.statiegeld, b_s2.statiegeld AS statiegeld2, copy.prijs_per_eenheid
		FROM ( 
			SELECT * 
			FROM voorraadteling_puchase 
			WHERE leve_copy_id = " . $leve_copy_id . " AND company_id = " . $company_id . ") his 
		LEFT JOIN leverancierslijst_copy copy ON his.leve_copy_id = copy.id AND copy.company_id = " . $company_id . "
		INNER JOIN basic_statiegeld b_s ON his.statiegeld_id = b_s.id AND b_s.company_id = " . $company_id . "
		INNER JOIN basic_statiegeld b_s2 ON his.statiegeld_los = b_s2.id AND b_s2.company_id = " . $company_id . " 
		ORDER BY created_at DESC";
		
		$result = $this->db->query($query);
		return $result->result_array();

	}
}
?>