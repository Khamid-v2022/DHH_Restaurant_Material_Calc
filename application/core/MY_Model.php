<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Model extends CI_Model
{
	public function get_list($table_name, $order_by = null){
		if($order_by)
			$this->db->order_by($order_by);
		return $this->db->get($table_name)->result_array();
	}

	public function get_list_where($table_name, $where){
		$this->db->where($where);
		return $this->db->get($table_name)->result_array();
	}

	public function get_item($table_name, $where){
		$this->db->where($where);
		return $this->db->get($table_name)->row_array();
	}

	public function add_item($table_name, $info){
		$this->db->insert($table_name, $info);
		return $this->db->insert_id();
	}

	public function add_list($table_name, $list){
		return $this->db->insert_batch($table_name, $list);
	}

	public function update_item($table_name, $info, $where){
		$this->db->where($where);
		$this->db->update($table_name, $info);
	}

	public function delete_item($table_name, $where){
		$this->db->where($where);
		$this->db->delete($table_name);
	}
}

?>