<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Report_generated_model extends MY_Model
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

  public function get_report_list($limit = NULL, $start = NULL, $search = NULL, $sortby = NULL, $order = NULL, $where = NULL, $start_date=NULL,$end_date=NULL,$count = NULL)
  {

    if ($sortby != NULL || $order != NULL) {
      $this->db->order_by($sortby, $order);
    } else {
      $this->db->order_by('rp.report_id', 'DESC');
    }

    if ($where) {
      $this->db->where($where);
    }


    if ($search != NULL && $search != 'NULL') {
      $search = trim($search);
      $this->db->group_start();
      $this->db->like('rp.report_num', $search);
      $this->db->or_like('DATE_FORMAT(rp.generated_date,"%d %b %Y")', $search);
      $this->db->or_like('sample.sample_type_name', $search);
      $this->db->or_like('cust.customer_name', $search);
      $this->db->or_like('buyer.customer_name', $search);
      $this->db->or_like('agent.customer_name', $search);
      $this->db->or_like('thirdparty.customer_name', $search);
      $this->db->or_like('rp.status', $search);
      $this->db->or_like('country.country_name', $search);
      $this->db->or_like('concat(ap.admin_fname," ",ap.admin_lname)', $search);
      $this->db->group_end();
    }
   
    $this->db->select('rp.report_num,DATE_FORMAT(rp.generated_date,"%d %b %Y") as report_date,sample.sample_type_name as product_name,cust.customer_name as customer_name,buyer.customer_name as buyer_name,agent.customer_name as agent_name,thirdparty.customer_name as thirdparty_name,rp.status,country.country_name,concat(ap.admin_fname," ",ap.admin_lname) as created_by');
    $this->db->from('generated_reports rp');
   
    $this->db->join('sample_registration sr','sr.sample_reg_id=rp.sample_reg_id','left');
    $this->db->join('mst_sample_types sample','sample.sample_type_id = sr.sample_registration_sample_type_id','left');
    $this->db->join('trf_registration trf','sr.trf_registration_id = trf.trf_id','left');
    $this->db->join('cust_customers cust','cust.customer_id = trf.trf_applicant','left');
    $this->db->join('cust_customers buyer','buyer.customer_id = trf.trf_buyer','left');
    $this->db->join('cust_customers agent','agent.customer_id = trf.trf_agent','left');
    $this->db->join('cust_customers thirdparty','thirdparty.customer_id = trf.trf_thirdparty','left');
    $this->db->join('mst_country country','country.country_id = trf.trf_country_orgin','left');
    $this->db->join('admin_profile ap','ap.uidnr_admin =  rp.report_generated_by','left');
    $end_date =  date("Y-m-d", strtotime("$end_date +1 day"));
    if($start_date!=NULL){
     
        $this->db->where(" rp.generated_date >= '$start_date' AND rp.generated_date <= '$end_date'");
    }
   
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

  public function get_AutoList_report_generated($col,$table,$search = NULL,$like,$where=NULL){
		
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