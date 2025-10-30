<?php

class Customers_model extends CI_Model
{

  public function get_Customers($customer_name = null,$nav_customer_code = null, $customer_id = 0 , $gstin = 0)
  {
     
    if(!empty($customer_name) && $customer_name != null){
      $this->db->or_like('customer_name', $customer_name);
    }

    if(!empty($nav_customer_code) &&  $nav_customer_code != null){
      $this->db->where('nav_customer_code', $nav_customer_code);
    }

    if($customer_id != 0){
      $this->db->where('customer_id', $customer_id);
    }

    if($gstin != 0){
      $this->db->where('gstin', $gstin);
    }

    $this->db->select('*');
    $this->db->from('cust_customers');
    
   
    $query = $this->db->get();
    // echo "<pre>"; print_r( $this->db->last_query()); die;
    return $query->result();
  
  }

  public function insert_Customers($Customers)
  {
    $id = $code = $country_id = NULL;
    $country_id =  $Customers['cust_customers_country_id'];
    //print_r($country_id);die;
    $this->db->select('country_code');
    $this->db->from('mst_country');
    $this->db->where('country_id', $country_id);
    $code = $this->db->get();

    if ($code->num_rows() > 0) {
      $code = $code->row()->country_code;
    } else {
      $code = "";
    }

    $Customers = $this->db->insert('cust_customers', $Customers);
//echo $this->db->last_query();die;
    if ($Customers) {
      $id = $this->db->insert_id();
      if (!empty($id)) {
        $Customers = array();
        $Customers['customer_code'] = $code . $id;
        $this->db->where('customer_id', $id);
        $result = $this->db->update('cust_customers', $Customers);
        return $result;
      }
    }
  }



  public function update_Customers($nav_customer_code, $data)
  {
    $this->db->where('nav_customer_code', $nav_customer_code);
    return $this->db->update('cust_customers', $data);
  }
}
