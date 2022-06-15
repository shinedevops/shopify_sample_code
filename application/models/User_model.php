<?php

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_model extends CI_model
{
	public function __construct() {
        parent::__construct();
    }
	
	/* Select Query Single Rows Start */
	public function select_row($tablename, $condition) {
		$this->db->select('*');
		$this->db->from($tablename);
		$this->db->where($condition);
		if($query=$this->db->get()){
			return $query->row_array();
		}else{
			return false;
		}
	}

	// Insert Query
	public function insert_db($tablename, $data){
		$query = $this->db->insert($tablename, $data);
		if($query){
			return true;
		}else{
			return false;
		}
	}

	// Update Query
	public function update_db($tablename,$wherecondition,$setvalue){
		$this->db->where($wherecondition);
		$query= $this->db->update($tablename,$setvalue);
		if($query){
			return true;
		}else{
			return false;
		}
	}

	// Select Query to Check Rows Exist
	public function store_check($tablename,$wherecondition){
		$this->db->select('*');
		$this->db->from($tablename);
		$this->db->where($wherecondition);
		$query=$this->db->get();
		return $query->num_rows();
	}

	// Delete Query Rows Start
	public function delete_db($tablename,$where_condition){
		$this->db->where($where_condition);
		$query =  $this->db->delete($tablename);
        if($query){
			return true;
		}else{
			return false;
		}
	}
    
}