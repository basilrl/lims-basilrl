<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Invoice_register_model extends MY_Model
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

  public function get_invoice_register_list($limit = NULL, $start = NULL, $search = NULL, $sortby = NULL, $order = NULL, $where = NULL, $start_date=NULL,$end_date=NULL,$count = NULL)
  {

    if ($sortby != NULL || $order != NULL) {
      $this->db->order_by($sortby, $order);
    } else {
      $this->db->order_by('invoice.proforma_invoice_id', 'DESC');
    }

    if ($where) {
      $this->db->where($where);
    }

    $currency = "(SELECT currency_name FROM mst_currency ms WHERE ms.currency_id = (SELECT CASE WHEN open_trf_currency_id IS NOT NULL AND open_trf_currency_id!= 0 THEN open_trf_currency_id ELSE (SELECT quotes_currency_id FROM quotes WHERE quote_id = trf_quote_id) END FROM trf_registration LEFT JOIN  sample_registration ON trf_id = trf_registration_id WHERE sample_reg_id = invoice.proforma_invoice_sample_reg_id))";

    if ($search != NULL && $search != 'NULL') {
      $search = trim($search);
      $this->db->group_start();
      $this->db->like('invoice.proforma_invoice_number', $search);
      $this->db->or_like('DATE_FORMAT(invoice.proforma_invoice_date, "%d-%b-%Y")', $search);
      $this->db->or_like('cust.customer_name', $search);
      $this->db->or_like('invoice.total_amount', $search);
      $this->db->or_like("(".$currency.")", $search);
      $this->db->or_like('trf.open_trf_customer_type', $search);
      $this->db->or_like('invoice.proforma_invoice_job_type', $search);
      $this->db->or_like('CONCAT(ap.admin_fname," ",ap.admin_lname)', $search);
      $this->db->group_end();
    }

    
$end_date =  date("Y-m-d", strtotime("$end_date +1 day"));
    $this->db->select('invoice.proforma_invoice_number,DATE_FORMAT(invoice.proforma_invoice_date, "%d-%b-%Y") as proforma_invoice_date,cust.customer_name,invoice.total_amount, '.$currency.' as currency_name, trf.open_trf_customer_type as customer_type,invoice.proforma_invoice_job_type,CONCAT(ap.admin_fname," ",ap.admin_lname) as created_by');
    $this->db->from('invoice_proforma invoice');
    $this->db->join('cust_customers as cust','cust.customer_id=invoice.invoice_proforma_customer_id','left');
    $this->db->join('sample_registration sr','sr.sample_reg_id=invoice.proforma_invoice_sample_reg_id','left');
    $this->db->join('trf_registration trf','trf.trf_id=sr.trf_registration_id','left');
    $this->db->join('quotes qt','qt.quote_id=trf.trf_quote_id','left');
    $this->db->where("invoice.invoice_proforma_invoice_status_id","9");
    $this->db->join('admin_profile ap', 'invoice.created_by = ap.uidnr_admin', 'left');
    if($start_date!=NULL){
        $this->db->where("invoice.proforma_invoice_date >= '$start_date' AND invoice.proforma_invoice_date <= '$end_date'");
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

  public function get_AutoList_report($col,$table,$search = NULL,$like,$where=NULL){
		
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