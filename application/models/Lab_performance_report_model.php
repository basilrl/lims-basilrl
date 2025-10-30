<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Lab_performance_report_model extends MY_Model
{
  public function __construct()
  {
    parent::__construct();
    $this->db->trans_start();
  }

  public function __destruct()
  {
    $this->db->trans_complete();
  }

  public function get_lab_performance_list($limit = NULL, $start = NULL, $search = NULL, $sortby = NULL, $order = NULL, $where = NULL, $start_date=NULL,$end_date=NULL,$count = NULL)
  {

    if ($sortby != NULL || $order != NULL) {
      $this->db->order_by($sortby, $order);
    } else {
      $this->db->order_by('lab.lab_id', 'DESC');
    }

    if ($where) {
      $this->db->where($where);
    }

    if ($search != NULL && $search != 'NULL') {
      $search = trim($search);
      $this->db->group_start();
      $this->db->like('TRIM(lab.lab_name)', $search);
      $this->db->or_like('(select count(st1.assigned_to) from sample_test st1 where st1.status="Complete" and st1.sample_test_assigned_lab_id=lab.lab_id)',$search);
      $this->db->or_like('(select count(st2.assigned_to) from sample_test st2 where st2.status!="Complete" and st2.sample_test_assigned_lab_id=lab.lab_id )',$search);
      $this->db->group_end();
    }
    
    $this->db->select('lab.lab_name as lab,(select count(st1.assigned_to) from sample_test st1 where st1.status="Complete" and st1.sample_test_assigned_lab_id=lab.lab_id) as completed_tests,(select count(st2.assigned_to) from sample_test st2 where st2.status!="Complete" and st2.sample_test_assigned_lab_id=lab.lab_id ) as assigned_tests');
    $this->db->from('mst_labs as lab');
    
   
    $this->db->limit($limit, $start);
    $result = $this->db->get();
    
    if ($result->num_rows() > 0) {
      if ($count != NULL) {
        
        return $result->num_rows();
      } else {
        return $result->result();
      }
    } else {
      return false;
    }
  }

  public function get_AutoList_lab($col,$table,$search = NULL,$like,$where=NULL){
		
    $this->db->select($col)
                    ->from($table);
            if($where!=NULL){
                    $this->db->where($where);
                }
            if($search!=NULL){
                $this->db->like($like,trim($search));
            }
            $this->db->order_by($like,'asc');
            $this->db->limit(20);
    $result = $this->db->get();

    // print_r($this->db->last_query());exit;

if($result->num_rows()>0){
    return $result->result();
}
else{
    return false;
}					
}

 
}