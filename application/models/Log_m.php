<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Log_m extends CI_Model
{
	private $ticket_table = "ticket";
	private $meeging_table = "committee_meetings";
	private $event_table = "events";
	
	// for Admin
	public function get_tickets($from_date, $to_date){
		$this->db->select("ticket.*, CONCAT(s.first_name, ' ', s.last_name) AS supervisor_name, s.email");
		$this->db->from($this->ticket_table);
		$this->db->join('supervisor s', 'ticket.supervisor_id = s.id', 'left');
		$this->db->where("submit_date >= '" . $from_date . "' AND submit_date <= '" . $to_date . "'");
		// 
		$this->db->where("is_submited", "y");
		return $this->db->get()->result_array();
	}

	// for User
	public function get_ticket($supervisor_id, $date){
		$this->db->select("*");
		$this->db->from($this->ticket_table);
		$this->db->where('supervisor_id', $supervisor_id);
		$this->db->where("DATE_FORMAT(DATE(submit_date),'%m/%d/%Y')", $date);
		return $this->db->get()->row_array();
	}

	// for Admin
	public function get_ticket_where($where){
		$this->db->select("ticket.*, CONCAT(s.first_name, ' ', s.last_name) AS supervisor_name, s.email");
		$this->db->from($this->ticket_table);
		$this->db->join('supervisor s', 'ticket.supervisor_id = s.id', 'left');
		$this->db->where($where);
		return $this->db->get()->row_array();
	}

	public function add_ticket($info){
		$this->db->insert($this->ticket_table, $info);
		return $this->db->insert_id();
	}

	public function update_ticket($info, $where){
		$this->db->where($where);
		$this->db->update($this->ticket_table, $info);
	}

	// meetings
	public function get_meeting_logs($where){
		$this->db->select('c.id, DATE_FORMAT(DATE(c.date),"%m/%d/%Y") as date, c.hours, c.miles, c.hourspay, c.hourspay_more, c.mileagepay, c.totalpay, committees.committee');
		$this->db->from('committee_meetings c');
		$this->db->join('committees', 'c.committee_id = committees.id', 'left');
		$this->db->where($where);
		return $this->db->get()->result_array();
	}


	public function get_total_hourspay($ticket_id, $date){
		$this->db->select('IFNULL(SUM(hourspay), 0) AS total_hourpay');
		$this->db->from('committee_meetings');
		$this->db->where('ticket_id', $ticket_id);
		$this->db->where('date', $date);
		return $this->db->get()->row_array()['total_hourpay'];
	}

	public function get_total_hourspay_for_update($ticket_id, $date, $id){
		$this->db->select('IFNULL(SUM(hourspay), 0) AS total_hourpay');
		$this->db->from('committee_meetings');
		$this->db->where('ticket_id', $ticket_id);
		$this->db->where('date', $date);
		$this->db->where('id != ' . $id);
		return $this->db->get()->row_array()['total_hourpay'];
	}


	public function get_meeting_log($meeting_id){
		$this->db->select('committee_meetings.*, committees.committee, DATE_FORMAT(DATE(date),"%m/%d/%Y") as formated_date');
		$this->db->from($this->meeging_table);
		$this->db->join('committees', 'committee_meetings.committee_id = committees.id', 'left');
		$this->db->where('committee_meetings.id', $meeting_id);
		return $this->db->get()->row_array();
	}

	public function add_meeting_log($info){
		$this->db->insert($this->meeging_table, $info);
		return $this->db->insert_id();
	}

	public function delete_meeting_log($where){
		$this->db->where($where);
		$this->db->delete($this->meeging_table);
	}

	public function update_meeting_log($info, $where){
		$this->db->where($where);
		$this->db->update($this->meeging_table, $info);
	}

	// events
	public function get_event_logs($where){
		$this->db->select('*, DATE_FORMAT(DATE(date),"%m/%d/%Y") as formated_date');
		$this->db->from($this->event_table);
		$this->db->where($where);
		return $this->db->get()->result_array();
	}

	public function get_event_log($event_id){
		$this->db->select('*, DATE_FORMAT(DATE(date),"%m/%d/%Y") as formated_date');
		$this->db->from($this->event_table);
		$this->db->where('id', $event_id);
		return $this->db->get()->row_array();
	}

	public function add_event_log($info){
		$this->db->insert($this->event_table, $info);
		return $this->db->insert_id();
	}

	public function update_event_log($info, $where){
		$this->db->where($where);
		$this->db->update($this->event_table, $info);
	}

	public function delete_event_log($where){
		$this->db->where($where);
		$this->db->delete($this->event_table);
	}
}

?>