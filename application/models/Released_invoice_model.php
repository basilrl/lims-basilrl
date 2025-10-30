<?php 

defined('BASEPATH') OR exit('No direct script access allowed');

class Released_invoice_model extends MY_Model{

    function __construct(){
        parent::__construct();
    }

    public function get_invoice_list($limit = NULL, $start = NULL,$where,$search,$count = null)
    {
        $this->db->limit($limit, $start);
        if (count($where)>0) {
            $this->db->group_start();
            $this->db->where($where);
            $this->db->group_end();
        }
        if ($search) {
            $this->db->group_start();
            $this->db->like('LOWER(sr.gc_no)',strtolower($search));
            $this->db->or_like('LOWER(tr.trf_ref_no)',strtolower($search));
            $this->db->group_end();
        }
      //  $where_in = ("(gr.status='Report Approved' OR gr.status = 'Report Generated') AND (sr.status='Report Approved' OR sr.status = 'Report Generated')");
        // $this->db->limit($limit, $start);
        $where_in = ("mi.uploadfilepath !=''" );
        $this->db->order_by('iv.invoiced_id', 'DESC');
        $this->db->group_start();
        $this->db->where($where_in);
        $this->db->group_end();
        // $this->db->where('st.status <>', 'Completed');
        $this->db->select("sr.ulr_no,sr.sample_desc,sr.gc_no,sr.sample_reg_id as sample_reg_id,
	   CASE WHEN tr.trf_service_type ='Regular' AND  ( tr.service_days IS NULL OR service_days='') THEN CONCAT(tr.trf_service_type,' 3 Days')
       WHEN tr.trf_service_type ='Express' THEN CONCAT(tr.trf_service_type,' 2 Days')
       WHEN tr.trf_service_type ='Urgent'  THEN CONCAT(tr.trf_service_type,' 1 Days')
	   WHEN tr.service_days IS NOT NULL OR tr.service_days!='' THEN CONCAT(tr.trf_service_type,' ',tr.service_days,'Days') END AS sample_service_type,
	   cc.customer_name as client,tr.trf_ref_no,mst.sample_type_name as product_name,sr.received_date,sr.status,
	   sr.lab_completion_date_time,due_date,sr.qty_received,sr.seal_no,mi.uploadfilepath,iv.report_num");
        $this->db->from('invoice_proforma ip');
        $this->db->join('sample_registration sr', 'sr.sample_reg_id = ip.proforma_invoice_sample_reg_id', 'inner');
        $this->db->join('trf_registration tr', 'tr.trf_id = sr.trf_registration_id', 'inner');
        $this->db->join('cust_customers as cc', 'cc.customer_id = tr.trf_applicant', 'left');
        $this->db->join('mst_sample_types as mst', 'mst.sample_type_id = sr.sample_registration_sample_type_id', 'left');
        $this->db->join('Invoices as iv', 'iv.proforma_invoice_id = ip.proforma_invoice_id', 'inner');
        $this->db->join('manual_invoice as mi', 'iv.invoiced_id = mi.invoice_id', 'inner');
        $this->db->group_by('iv.invoiced_id');
        $query = $this->db->get();
        // print_r($this->db->last_query());die;
        if ($count) {
            return $query->num_rows();
        } else {
            if ($query->num_rows() > 0) {
                return $query->result();
            } else {
                return false;
            }
        }
        
    }

 
}

