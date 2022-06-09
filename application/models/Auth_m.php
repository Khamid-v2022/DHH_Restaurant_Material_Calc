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
}

?>