<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Monthly_sales_report_model extends MY_Model
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

  public function get_monthly_sales_list($limit = NULL, $start = NULL, $search = NULL, $sortby = NULL, $order = NULL, $where = NULL, $start_date=NULL,$end_date=NULL,$count = NULL)
  {

    if ($sortby != NULL || $order != NULL) {
      $this->db->order_by($sortby, $order);
    } else {
      $this->db->order_by('sr.sample_reg_id', 'DESC');
    }

    if ($where) {
      $this->db->where($where);
    }


    if ($search != NULL && $search != 'NULL') {
      $search = trim($search);
      $this->db->group_start();
      $this->db->like('sr.gc_no', $search);
      $this->db->or_like('cust.customer_name', $search);
      $this->db->or_like('DATE_FORMAT(sr.create_on, "%d-%b-%Y")', $search);
      $this->db->or_like('((select SUM(applicable_charge) from sample_test where sr.sample_reg_id=sample_test_sample_reg_id))', $search);
      $this->db->or_like('sr.status', $search);
      $this->db->group_end();
    }

    $end_date =  date("Y-m-d", strtotime("$end_date +1 day"));

    $this->db->select('sr.gc_no,cust.customer_name,DATE_FORMAT(sr.create_on, "%d-%b-%Y") as create_on,(select SUM(applicable_charge) from sample_test where sr.sample_reg_id=sample_test_sample_reg_id) as estimate_value, sr.status');
    $this->db->from('sample_registration sr');
    $this->db->join('cust_customers as cust','cust.customer_id=sr.sample_customer_id','left');
    $this->db->where('sr.released_to_client',"1");

    if($start_date!=NULL){
        $this->db->where("sr.create_on >= '$start_date' AND sr.create_on <= '$end_date'");
    }
   
    $this->db->limit($limit, $start);
    $result = $this->db->get();
  // query();die;
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

  public function get_AutoList_sales($col,$table,$search = NULL,$like,$where=NULL){
		
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