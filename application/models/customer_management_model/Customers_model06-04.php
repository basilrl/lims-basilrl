<?php

use phpDocumentor\Reflection\Types\Null_;

defined('BASEPATH') or exit('No direct script access allowed');

class Customers_model extends MY_Model
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

  public function customers_list($limit = NULL, $start = NULL, $search = NULL, $sortby = NULL, $order = NULL, $where = NULL, $count = NULL)
  {
   
    if ($sortby != NULL || $order != NULL) {
      $this->db->order_by($sortby, $order);
    } else {
      $this->db->order_by('cust.customer_id', 'DESC');
    }

    if ($where) {
      $this->db->where($where);
    }

    if(exist_val('Listing/branch_wise',$this->session->userdata('permission'))){
      $checkUser = $this->session->userdata('user_data');
      $this->db->where('cust.mst_branch_id', $checkUser->branch_id); //branch_id
    }
    
    // echo $search;die;
    if ($search != NULL && $search != 'NULL') {
      $search = trim($search);
      $this->db->group_start();
      $this->db->like('cust.customer_name', $search);
			$this->db->or_like('cust.customer_code', $search);
      $this->db->or_like('cust.customer_type', $search);
      $this->db->or_like('cust.gstin', $search);
      $this->db->or_like('cust.email', $search);
      $this->db->or_like('cust.telephone', $search);
      $this->db->or_like('country.country_name', $search);
      $this->db->or_like('cust.created_by', $search);
      $this->db->or_like('cust.isactive',$search);
      $this->db->or_like('DATE_FORMAT(cust.created_on,"%M %d %Y %h:%i:%s %p")',$search);
      $this->db->or_like('CONCAT(admin_fname,"",admin_lname)',$search);
      $this->db->group_end();
    }

    $this->db->select('cust.customer_id as customer_id,cust.customer_code as customer_code,cust.customer_name as customer_name,cust.customer_type as customer_type,cust.email as email,cust.gstin as gstin, cust.created_by as created_by, DATE_FORMAT(cust.created_on,"%M %d %Y %h:%i:%s %p") as created_on,cust.isactive as status,cust.telephone as telephone, country.country_id as country_id,country.country_name as country_name,CONCAT(admin_fname," ",admin_lname) as created_by,cust.accop_cust');
    $this->db->from('cust_customers cust');
    $this->db->join('mst_country country', 'country.country_id=cust.cust_customers_country_id and country.status="1"', 'left');
    $this->db->join('admin_profile admin', 'admin.uidnr_admin=cust.created_by', 'left');
    $this->db->order_by('cust.customer_id', 'DESC');
    $this->db->limit($limit, $start);
    $result = $this->db->get();
      // echo $this->db->last_query();die;
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

  public function get_customer_details($customer_id){
    $this->db->select('cust.customer_id as customer_id,cust.customer_code as customer_code,cust.customer_name as customer_name,cust.customer_type as customer_type,cust.email as email,cust.gstin as gstin, cust.created_by as created_by, DATE_FORMAT(cust.created_on,"%M %d %Y %h:%i:%s %p") as created_on,cust.isactive as status,cust.telephone as telephone, country.country_id as country_id,country.country_name as country_name,CONCAT(admin_fname," ",admin_lname) as created_by,cust.accop_cust,cust.mobile,cust.address,cust.city,cust.po_box,cust.retention_period,state.province_name as state_name,loc.location_name as location_name');
    $this->db->from('cust_customers cust');
    $this->db->join('mst_country country', 'country.country_id=cust.cust_customers_country_id and country.status="1"', 'left');
    $this->db->join('mst_provinces state', 'state.province_id=cust.cust_customers_province_id and state.status="1"', 'left');
    $this->db->join('mst_locations loc', 'loc.	location_id=cust.cust_customers_location_id and loc.status="1"', 'left');
    $this->db->join('admin_profile admin', 'admin.uidnr_admin=cust.created_by', 'left');
    $this->db->where('cust.customer_id',$customer_id);
    $result =$this->db->get();
    if($result->num_rows()>0){
      return $result->row();
    }
    else{
      return false;
    }
  }


  public function insertCustomers($data = NULL)
  {
    $id = $code = $country_id = NULL;
    $country_id =  $data['cust_customers_country_id'];
    $this->db->select('country_code');
    $this->db->from('mst_country');
    $this->db->where('country_id', $country_id);
    $code = $this->db->get();
    if ($code->num_rows() > 0) {
      $code = $code->row()->country_code;
    } else {
      $code = "";
    }

    $data = $this->db->insert('cust_customers', $data);
    // echo $this->db->last_query(); die;
    if ($data) {
      $id = $this->db->insert_id();
      if (!empty($id)) {
        $data = array();
        $data['customer_code'] = $code . $id;
        $this->db->where('customer_id', $id);
        $result = $this->db->update('cust_customers', $data);
        return ['success' => true,'inserted_id' => $id];
      } else {
        return ['success' => false];
      }
    } else {
      return ['success' => false];
    }
  }

  public function updateCustomers($data, $customer_id)
  {

    $this->db->where('customer_id', $customer_id);
    $result = $this->db->update('cust_customers', $data);
    if ($result) {
      return true;
    } else {
      return false;
    }
  }


  public function get_customers($customer_id)
  {
    $this->db->select('cust.*,country.country_name,state.province_name as state_name,location.location_name as location_name,CONCAT(admin_fname," ",admin_lname) as created_by,cust.isactive as isactive');
    $this->db->from('cust_customers cust');
    $this->db->where('customer_id', $customer_id);
    $this->db->join('mst_country country', 'country.country_id=cust.cust_customers_country_id and country.status="1"', 'left');
    $this->db->join('mst_provinces state', 'state.province_id=cust.cust_customers_province_id and state.status="1"', 'left');
    $this->db->join('mst_locations location', 'location.location_id=cust.cust_customers_location_id and location.status="1"', 'left');
    $this->db->join('admin_profile admin', 'admin.uidnr_admin=cust.created_by', 'left');
    $result =  $this->db->get();
    if ($result->num_rows() > 0) {
      return $result->row();
    } else {
      return false;
    }
  }


  public function check_duplicate($data){
    $this->db->select('contacts_customer_id');
    $this->db->from('contacts');
    $this->db->where('contacts_customer_id',$data['contacts_customer_id']);
    $this->db->where('email',$data['email']);

    if(!empty($data['contact_id'])){
      $this->db->where('contact_id',$data['contact_id']);
    }
    else{
      $data['contact_id'] = NULL;
    }
    $result = $this->db->get();

    if($result->num_rows()=="0"){
      return true;
    }
    else{
      return false;
    }
  }

  public function load_contacts($customer_id){
    $this->db->select('c.contacts_customer_id as customer_id,c.contacts_designation_id, c.contact_name, c.type, c.email, c.address, c.telephone ,c.mobile_no, c.note ,c.contact_id,if(c.status= "0" , "Inactive","Active") as leads_status,CONCAT(admin_fname," ",admin_lname) as created_by,DATE_FORMAT(c.created_on, "%d-%b-%Y %H:%i") AS created_on');
    $this->db->from('contacts c');
    $this->db->join('admin_profile admin', 'admin.uidnr_admin=c.created_by','left');
    $this->db->where('c.contacts_customer_id',$customer_id);
    $this->db->order_by('c.contact_id');
    $result =$this->db->get();
    
    if($result->num_rows()>0){
      return $result->result();
    }
    else{
      return false;
    }
  }

  public function loadCommunications($customer_id){
    $this->db->select('contact.contact_id,contact.contact_name,com.communication_id, com.subject , com.comm_communications_customer_id as customer_id,com.note,com.communication_mode, DATE_FORMAT(com.date_of_communication, "%d-%b-%Y") as date_of_communication,com.medium,com.connected_to,DATE_FORMAT(com.created_on, "%d-%b-%Y %H:%i") AS created_on,CONCAT(admin_fname," ",admin_lname) as created_by');
    $this->db->from('comm_communications com');
    $this->db->join('admin_profile admin', 'admin.uidnr_admin=com.created_by','left');
    $this->db->join('contacts contact','contact.contact_id = com.comm_communications_contact_id','left');
    $this->db->where('com.comm_communications_customer_id',$customer_id);
    $this->db->order_by('com.communication_id');
    $result =$this->db->get();
    
    if($result->num_rows()>0){
      return $result->result();
    }
    else{
      return false;
    }
  }
  
  

  public function deleteContact($country_id){
    $this->db->where('contact_id',$country_id);
    $result = $this->db->delete('contacts');

    if($result){
      return true;
    }
    else{
      return false;
    }
  }

  public function deleteCommunications($communication_id){
    $this->db->where('communication_id',$communication_id);
    $result = $this->db->delete('comm_communications');

    if($result){
      return true;
    }
    else{
      return false;
    }
  }

  public function deleteOpportunity($opportunity_id){
    $this->db->where('opportunity_id',$opportunity_id);
    $result = $this->db->delete('opportunity');

    if($result){
      return true;
    }
    else{
      return false;
    }
  }
  

  

  public function loadReference_no($customer_id){
    $this->db->select('quote_id as reference_id,reference_no as reference');
    $this->db->from('quotes');
    $this->db->where('quotes_customer_id',$customer_id);
    $this->db->order_by('reference_no');
    $result = $this->db->get();
    if($result->num_rows()>0){
      return $result->result();
    }
    else{
      return false;
    }
  }

  public function loadOportunity($customer_id){
    $this->db->select('opportunity.*,opportunity.opportunity_value as opportunity_value,opportunity.types as types,opportunity.estimated_closure_date as estimated_closure_date,opportunity_id as reference_id,opportunity_name as reference');
    $this->db->from('opportunity');
    $this->db->where('opportunity_customer_id',$customer_id);
    $this->db->order_by('opportunity_name');
    $result = $this->db->get();
    if($result->num_rows()>0){
      return $result->result();
    }
    else{
      return false;
    }
  }

 
  public function get_type($customer_id=NULL,$where_not_in=NULL){
    $this->db->select('customer_type');
    $this->db->from('cust_customers');
    if($customer_id!=NULL){
      $this->db->where('customer_id',$customer_id);
    }
    if($where_not_in!=NULL){
      $this->db->where_not_in('customer_type',$where_not_in);
    }
    $this->db->where_not_in('customer_type',"");
    $this->db->group_by('customer_type');
    $type = $this->db->get();
    if($type->num_rows()>0){
      return $type->result();
    }
    else{
      return false;
    }
  }


  public function get_map_listing($customer_id=NULL,$type=NULL,$data,$search=NULL){
    $this->db->select('cs.customer_id,cs.customer_name,cs.customer_code,cs.city');
    $this->db->from('cust_customers cs');
    if($type!=NULL){
      $this->db->where('cs.customer_type',$type);
    }
    else{
      return false;
    }
    
    if($data && count($data)>0){
      $this->db->where_not_in('cs.customer_id',$data);
    }
    if($customer_id!=NULL){
      $this->db->where('cs.customer_id',$customer_id);
    }
    
    if($search!=NULL){
      $this->db->group_start();
      $this->db->like('cs.customer_name',$search);
      $this->db->group_end();
    }
   
    $this->db->order_by('cs.customer_id','DESC');
    $result = $this->db->get();

    // echo $this->db->last_query();die;
    if($result->num_rows()){
      return $result->result();
    }
    else{
      return false;
    }
  }


  public function insert_map_details($table,$data){
      $result = $this->db->insert($table,$data);
      if($result){
        return true;
      }
      else{
        return false;
      }
  }

  public function delete_map_details($table,$where1,$where2){
    $this->db->where($where1);
    $this->db->where($where2);
    $result = $this->db->delete($table);


    if($result){
      return true;
    }
    else{
      return false;
    }
  }


  public function get_mapped_listing($join_table,$condition,$where1,$where2,$search=NULL){
    $this->db->select('cs.customer_id,cs.customer_name,cs.customer_code,cs.city');
    $this->db->from('cust_customers cs');
    $this->db->join($join_table,$condition,'left');
    $this->db->where($where1);
    $this->db->where($where2);
    if($search!=NULL){
      $this->db->group_start();
      $this->db->like('cs.customer_name',$search);
      $this->db->group_end();
    }
    $result = $this->db->get();
  
    if($result->num_rows()>0){
      return $result->result();
    }
    else{
      return false;
    }
  }

  public function op_assign_to($designation_id2,$designation_id1){
    $this->db->select('admin_profile.uidnr_admin as user_id,CONCAT( admin_profile.admin_fname," ",admin_profile.admin_lname) as user_name,admin_users.admin_active as admin_active');
    $this->db->from('admin_profile');
    $this->db->join('operator_profile','operator_profile.uidnr_admin=admin_profile.uidnr_admin','inner');
    $this->db->join('admin_users','admin_users.uidnr_admin=admin_profile.uidnr_admin','inner');
    $this->db->where('admin_users.admin_active','1');
    $this->db->where_in('operator_profile.admin_designation',[$designation_id2,$designation_id1]);
    $this->db->order_by('admin_profile.admin_fname','ASC');
    $result = $this->db->get();
    // echo $this->db->last_query();die;
    if($result->num_rows()>0){
      return $result->result();
    }
    else{
      return false;
    }
  }

  public function get_result_by_search($col,$table,$where,$like=NULL){
    $this->db->select($col);
    $this->db->from($table);
    if($like!=NULL){
      $this->db->group_start();
      $this->db->like($like);
      $this->db->group_end();
    }
    $this->db->where($where);
    $result = $this->db->get();
    echo $this->db->last_query();die;
    if($result->num_rows()>0){
      return $result->result();
    }
    else{
      return false;
    }

  }

  public function get_customer_log($customer_id)
    {
    $query = $this->db->select('action_taken, created_on as taken_at, text,concat(admin_fname," ",admin_lname) as taken_by')
                        ->join('admin_profile','user_log_history.created_by = admin_profile.uidnr_admin')
                        ->where('source_module','Customers')
                        ->where('record_id',$customer_id)
                        ->order_by('id','desc')
                        ->get(' user_log_history');
    if($query->num_rows() > 0){
        return $query->result_array();
    }
    return [];
    }

}
