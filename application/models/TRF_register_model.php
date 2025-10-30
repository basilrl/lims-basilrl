<?php
defined('BASEPATH') or exit('No direct script access allowed');
class TRF_register_model extends MY_Model
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

  public function get_trf_register_list($limit = NULL, $start = NULL, $search = NULL, $sortby = NULL, $order = NULL, $where = NULL, $start_date=NULL,$end_date=NULL,$count = NULL)
  {

    if ($sortby != NULL || $order != NULL) {
      $this->db->order_by($sortby, $order);
    } else {
      $this->db->order_by('trf.trf_id', 'DESC');
    }

    if ($where) {
      $this->db->where($where);
    }

    $service =  "(CASE WHEN trf.trf_service_type ='Regular' AND  ( trf.service_days IS NULL OR trf.service_days='') THEN CONCAT(trf.trf_service_type,' 3 Days')
    WHEN trf.trf_service_type ='Express' THEN CONCAT(trf.trf_service_type,' 2 Days')
    WHEN trf.trf_service_type ='Urgent'  THEN CONCAT(trf.trf_service_type,' 1 Days')
    WHEN trf.service_days IS NOT NULL OR trf.service_days!='' THEN CONCAT(trf.trf_service_type,' ',trf.service_days,' Days') END)";

    if ($search != NULL && $search != 'NULL') {
      $search = trim($search);
      $this->db->group_start();
      $this->db->like('trf.trf_ref_no', $search);
      $this->db->or_like('sr.gc_no', $search);
      $this->db->or_like('cust.customer_name', $search);
      $this->db->or_like('DATE_FORMAT(trf.create_on, "%d-%b-%Y")', $search);
      $this->db->or_like('trf.trf_regitration_type', $search);
      $this->db->or_like('('.$service.')', $search);
      $this->db->or_like('trf.trf_status', $search);
      $this->db->or_like('con.contact_name', $search);
      $this->db->or_like('buyer.customer_name', $search);
      $this->db->or_like('agent.customer_name', $search);
      $this->db->or_like('sample.sample_type_name', $search);
      $this->db->or_like('country.country_name', $search);
      $this->db->or_like('trf.trf_end_use', $search);
      $this->db->or_like('trf.trf_sample_desc', $search);
      $this->db->group_end();
    }

    
    $end_date =  date("Y-m-d", strtotime("$end_date +1 day"));
    
    $this->db->select('trf.trf_ref_no,sr.gc_no,cust.customer_name,DATE_FORMAT(trf.create_on, "%d-%b-%Y") as create_on,trf.trf_regitration_type,'.$service.' as trf_service_type,trf.trf_status,con.contact_name,buyer.customer_name as buyer_name,agent.customer_name as agent_name,sample.sample_type_name as product_name,country.country_name,trf.trf_end_use,trf.trf_sample_desc');
    $this->db->from('trf_registration trf');
    $this->db->join('sample_registration sr','sr.trf_registration_id = trf.trf_id','left');
    $this->db->join('cust_customers cust','cust.customer_id = trf.trf_applicant','left');
    $this->db->join('cust_customers buyer','buyer.customer_id = trf.trf_buyer','left');
    $this->db->join('cust_customers agent','agent.customer_id = trf.trf_agent','left');
    $this->db->join('contacts con','con.contact_id = trf.trf_invoice_to_contact','left');
    $this->db->join('mst_sample_types sample','sample.sample_type_id = trf.trf_product','left');
    $this->db->join('mst_country country','country.country_id = trf.trf_country_destination','left');

    if($start_date!=NULL){
     
        $this->db->where(" trf.create_on >= '$start_date' AND trf.create_on <= '$end_date'");
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

  public function get_AutoList_trf($col,$table,$search = NULL,$like,$where=NULL){
		
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