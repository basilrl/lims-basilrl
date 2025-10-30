<?php

defined('BASEPATH') or exit('No direct access allowed');

class Backlogs_model extends MY_Model
{

    function __construct()
    {
        parent::__construct();
    }


    public function get_due_data($where = NULL,$limit=NULL,$offset=NULL)
    {
        // div.division_name,cust.customer_name as client,buyer.customer_name as buyer_name,mst.sample_type_name as product,trf.trf_ref_no as trf_no,trf.trf_service_type as service_type,sr.received_date,sr.status,sr.insufficient_remark
        $this->db->select('sr.gc_no,sr.sample_reg_id,DATE(sr.due_date) as due_date, sr.division_id,sr.sample_desc,div.division_name');
        $this->db->from('sample_registration sr');
        $this->db->join('trf_registration as tr','tr.trf_id=sr.trf_registration_id');
        $this->db->join('mst_divisions div','div.division_id = sr.division_id','left');
        $this->db->where($where);
        $this->db->where_not_in('sr.status', ['Login Cancelled']);
        $this->db->order_by('sr.gc_no', 'DESC');

        if($limit!=NULL){
            $this->db->limit($limit,$offset);
        }
        $result = $this->db->get();

        if ($result->num_rows() > 0) {
            return $result->result();
        } else {
            return false;
        }
    }

    public function get_due_data_onlY_gc($where = NULL,$limit=NULL,$offset=NULL){
        $this->db->select('sr.gc_no,sr.sample_reg_id,DATE(sr.due_date) as due_date');
        $this->db->from('sample_registration sr');
        $this->db->where($where);
        $this->db->where_not_in('sr.status', ['Login Cancelled']);
        $this->db->order_by('sr.gc_no', 'DESC');

        if($limit!=NULL){
            $this->db->limit($limit,$offset);
        }
        $result = $this->db->get();

        if ($result->num_rows() > 0) {
            return $result->result();
        } else {
            return false;
        }
    }

    public function excel_export_backlog($where){
        
        $this->db->select('sr.gc_no,sr.sample_reg_id, sr.division_id,div.division_name,cust.customer_name as client,buyer.customer_name as buyer_name,mst.sample_type_name as product,trf.trf_ref_no as trf_no,sr.received_date,sr.status,sr.insufficient_remark,trf.trf_service_type as service_type,DATE(sr.due_date) as due_date,sr.sample_desc,gr.report_num,DATE_FORMAT( gr.mr_result_ready_date, "%d-%m-%Y %H:%i" ) as report_release_date,DATE_FORMAT(inv.generated_date, "%d-%m-%Y %H:%i" ) AS invoice_date,ap.admin_fname AS report_released_by,sr.manual_report_remark as remark');
        $this->db->from('sample_registration sr');
        $this->db->join('mst_divisions div','div.division_id = sr.division_id','left');
        $this->db->join('cust_customers cust','cust.customer_id = sr.sample_customer_id','left');
        $this->db->join('trf_registration trf','trf.trf_id = sr.trf_registration_id','left');
        $this->db->join('cust_customers buyer','buyer.customer_id = trf.trf_buyer','left');
        $this->db->join('mst_sample_types mst','mst.sample_type_id = sr.sample_registration_sample_type_id','left');
        $this->db->join('generated_reports gr','gr.sample_reg_id = sr.sample_reg_id','left');
        $this->db->join('Invoices inv','inv.report_num = gr.report_num','left');
        $this->db->join('invoice_proforma invp','invp.proforma_invoice_id=inv.proforma_invoice_id','left');
        $this->db->join('admin_profile ap','sr.create_by = ap.uidnr_admin','left');

        $this->db->where($where);
        $this->db->where_not_in('sr.status', ['Login Cancelled']);
        $this->db->order_by('sr.gc_no', 'DESC');

     
        $result = $this->db->get();
    
        if ($result->num_rows() > 0) {
            return $result->result();
        } else {
            return false;
        }
    }
   
    public function due_gc_sample_details($sample_reg_id)
    {
        $this->db->select('CASE WHEN (sr.sample_desc) IS NOT NULL THEN (sr.sample_desc) ELSE "-" END as sample_desc, CASE WHEN (sr.seal_no) IS NOT NULL THEN (sr.seal_no) ELSE "-" END as seal_no,  CASE WHEN (sr.qty_received) IS NOT NULL THEN (sr.qty_received) ELSE "-" END as qty_received, CASE WHEN (un.unit) IS NOT NULL THEN (un.unit) ELSE (sr.qty_unit) END AS unit_name , sr.barcode_path as barcode, CASE WHEN (Select test_standard_name from mst_test_standards where test_standard_id = sr.sample_registration_test_standard_id) IS NULL THEN "None" ELSE (Select test_standard_name from mst_test_standards where test_standard_id = sr.sample_registration_test_standard_id) END AS  test_specification, cust.customer_name as client, CASE WHEN (branch.branch_name) IS NOT NULL THEN (branch.branch_name) ELSE "-" END AS branch_name, CASE WHEN(GROUP_CONCAT(contact.contact_name SEPARATOR " / ")) IS NOT NULL THEN GROUP_CONCAT(contact.contact_name SEPARATOR " / ") ELSE "-" END AS contact, CONCAT( ap.admin_fname," ",ap.admin_lname) as create_by, CASE WHEN (DATE_FORMAT(sr.collection_date,"%d-%b-%Y %H:%i")) IS NOT NULL THEN (DATE_FORMAT(sr.collection_date,"%d-%b-%Y %H:%i")) ELSE "-" END AS collection_time, sr.gc_no, CASE WHEN (sr.price_type) IS NOT NULL THEN (sr.price_type) ELSE "-" END as price_type,  CASE WHEN (sr.quantity_desc) IS NOT NULL THEN (sr.quantity_desc) ELSE "-" END as quantity_desc, CASE WHEN (DATE_FORMAT(sr.received_date,"%d-%b-%Y %H:%i")) IS NOT NULL THEN (DATE_FORMAT(sr.received_date,"%d-%b-%Y %H:%i")) ELSE "-" END AS received_date,  CASE WHEN (sr.status) IS NOT NULL THEN (sr.status) ELSE "-" END status, CASE WHEN trf.tat_date IS NOT NULL THEN DATE_FORMAT(trf.tat_date,"%d-%b-%Y %H:%i") ELSE "-" END AS tat_date, CASE WHEN sr.sample_retain_period = 0 THEN "NA" ELSE CONCAT(sr.sample_retain_period," Days") END AS sample_retain_period, mst.sample_type_name as sample_type_name,DATE_FORMAT(sr.due_date,"%d-%b-%Y") as due_date'); 
        $this->db->from('sample_registration sr');
        $this->db->join('cust_customers cust','cust.customer_id = sr.sample_customer_id','left');
        $this->db->join('trf_registration trf','trf.trf_id = sr.trf_registration_id','left');
        $this->db->join('contacts contact','contact.contact_id = trf.trf_contact','left');
        $this->db->join('admin_profile ap','sr.create_by = ap.uidnr_admin','left');
        $this->db->join('mst_sample_types mst','mst.sample_type_id = sr.sample_registration_sample_type_id','left');
        $this->db->join('units un','un.unit_id = sr.qty_unit','left');
        $this->db->join('mst_branches branch','branch.branch_id = sr.sample_registration_branch_id','left');
        $this->db->where('sr.sample_reg_id',$sample_reg_id);

        $result = $this->db->get();
        // echo $this->db->last_query();die;
        if($result->num_rows()>0){
            return $result->row();
        }
        else{
            return false;
        }

    }

    public function get_auto_list_gc($col,$table,$search = NULL,$like,$where=NULL){
		
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
