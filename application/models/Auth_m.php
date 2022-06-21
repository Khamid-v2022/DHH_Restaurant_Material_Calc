<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Auth_m extends CI_Model
{
	private $table = "admin";					//Admin table
	
	// Admin manage
	public function get_member($where){
		$this->db->where($where);
		return $this->db->get($this->table)->row_array();
	}

	public function get_members(){
		return $this->db->get($this->table)->result_array();
	}

	public function insert_member($info){
		$this->db->insert($this->table, $info);
		return $this->db->insert_id();
	}
	
	public function update_member($info, $where){
		$this->db->where($where);
		$this->db->update($this->table, $info);
	}


	public function get_max_companyId(){
		$this->db->select('IFNULL(MAX(company_id), 0) AS max_company_id');
		return $this->db->get('admin')->row_array();
	}

	public function get_companies(){
		$query = "SELECT a.*, b.count 
		FROM (
			SELECT * 
			FROM admin WHERE ROLE = '1') a 
		LEFT JOIN (
			SELECT company_id, COUNT(id) AS COUNT 
			FROM admin WHERE ROLE != '0' 
			GROUP BY company_id) b 
		ON a.company_id = b.company_id";
		$result = $this->db->query($query);
		return $result->result_array();
	}
}

?>