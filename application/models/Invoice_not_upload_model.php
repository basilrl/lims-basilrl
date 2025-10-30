<?php

defined('BASEPATH') or exit('No direct access allowed');

class Invoice_not_upload_model extends MY_Model
{

    function __construct()
    {
        parent::__construct();
    }

   

    
    public function get_not_upload_invoice($where,$offset=NULL,$search=NULL,$limit=NULL){

        //   $arr = [];
        // $where_not_in = array();
        // $this->db->select("report_num");
        // $this->db->from("manual_invoice");
        // $this->db->where("uploadfilepath!='' and Invoice_Amount!='' and Invoice_Amount!='0' and Invoice_Amount!=0 ");
        // $where_not_in = $this->db->get()->result();
        // foreach($where_not_in as $key=>$value){
        //    $arr[$key] = $value->report_num;
        // }
    
        $this->db->select('sr.sample_reg_id,CASE WHEN (sr.gc_no) IS NOT NULL THEN (sr.gc_no) ELSE "-" END as gc_no,CASE WHEN (cus.customer_name) IS NOT NULL THEN (cus.customer_name) ELSE "-" END as customer_name,
        CASE WHEN (div.division_name) IS NOT NULL THEN (div.division_name) ELSE "-" END as division_name, CASE WHEN (sr.status) IS NOT NULL THEN (sr.status) ELSE "-" END as status, CASE WHEN (sr.collection_date) IS NOT NULL THEN (sr.collection_date) ELSE "-" END as collection_date,CASE WHEN (sr.received_date) IS NOT NULL THEN (sr.received_date) ELSE "-" END as received_date, CASE WHEN (sr.sample_desc) IS NOT NULL THEN (sr.sample_desc) ELSE "-" END as sample_desc,CASE WHEN (sr.qty_received) IS NOT NULL THEN (sr.qty_received) ELSE "-" END as qty_received,CASE WHEN (sr.create_on) IS NOT NULL THEN (sr.create_on) ELSE "-" END as create_on,concat(ap.admin_fname," ",ap.admin_lname) as create_by,CASE WHEN (sr.due_date) IS NOT NULL THEN (sr.due_date) ELSE "-" END as due_date');
        $this->db->from('sample_registration sr');
        $this->db->join('trf_registration trf','trf.trf_id = sr.trf_registration_id','left');
        $this->db->join('mst_divisions div','div.division_id = sr.division_id','left');
        $this->db->join('cust_customers cus','cus.customer_id=trf.trf_applicant','left');
        $this->db->join('admin_profile ap','ap.uidnr_admin=sr.create_by','left');
        $this->db->where('sr.marked_invoice','1');
        if (count($where) > 0) {
            $this->db->where($where);
        }

        
        // $arr_chunk = array_chunk($arr, 25);

        // if (count($arr_chunk) > 0) {
        //     $this->db->group_start();
        //     foreach ($arr_chunk as $gc) {
        //         $this->db->where_not_in('sr.gc_no', $gc);
        //     }
        //      $this->db->group_end();
        // }
        $this->db->order_by('sr.sample_reg_id', 'DESC');
       

        if($search!=NULL){
            $search = trim($search);
            $this->db->group_start();
            $this->db->like("sr.gc_no",$search);
            $this->db->group_end();
        }
     
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

    public function get_particular_details($sample_reg_id){
          $arr = [];
        $where_not_in = array();
        $this->db->select("report_num");
        $this->db->from("manual_invoice");
        $this->db->where("uploadfilepath!='' and Invoice_Amount!='' and Invoice_Amount!='0' and Invoice_Amount!=0 ");
        $where_not_in = $this->db->get()->result();
        foreach($where_not_in as $key=>$value){
           $arr[$key] = $value->report_num;
        }
        
     
        $this->db->select('sr.sample_reg_id,CASE WHEN (sr.gc_no) IS NOT NULL THEN (sr.gc_no) ELSE "-" END as gc_no,CASE WHEN (cus.customer_name) IS NOT NULL THEN (cus.customer_name) ELSE "-" END as customer_name,
        CASE WHEN (div.division_name) IS NOT NULL THEN (div.division_name) ELSE "-" END as division_name, CASE WHEN (sr.status) IS NOT NULL THEN (sr.status) ELSE "-" END as status, CASE WHEN (sr.collection_date) IS NOT NULL THEN (sr.collection_date) ELSE "-" END as collection_date,CASE WHEN (sr.received_date) IS NOT NULL THEN (sr.received_date) ELSE "-" END as received_date, CASE WHEN (sr.sample_desc) IS NOT NULL THEN (sr.sample_desc) ELSE "-" END as sample_desc,CASE WHEN (sr.qty_received) IS NOT NULL THEN (sr.qty_received) ELSE "-" END as qty_received,CASE WHEN (sr.create_on) IS NOT NULL THEN (sr.create_on) ELSE "-" END as create_on,concat(ap.admin_fname," ",ap.admin_lname) as create_by,CASE WHEN (sr.due_date) IS NOT NULL THEN (sr.due_date) ELSE "-" END as due_date');
        $this->db->from('sample_registration sr');
        $this->db->join('trf_registration trf','trf.trf_id = sr.trf_registration_id','left');
        $this->db->join('mst_divisions div','div.division_id = sr.division_id','left');
        $this->db->join('cust_customers cus','cus.customer_id=trf.trf_applicant','left');
        $this->db->join('admin_profile ap','ap.uidnr_admin=sr.create_by','left');
        $this->db->where('sr.sample_reg_id',$sample_reg_id);
        
        // /dashboard
        $arr_chunk = array_chunk($arr, 25);
        if(count($arr_chunk) > 0){
        $this->db->group_start();
        foreach ($arr_chunk as $gc) {
            $this->db->where_not_in('sr.gc_no', $gc);
        }
        $this->db->group_end();
    }
        $this->db->order_by('sr.sample_reg_id', 'DESC');
       
        $result = $this->db->get();
        if($result->num_rows()>0){
            return $result->row();
        }
        else{
            return false;
        }
    }

    public function get_auto_list_invoice($col,$table,$search = NULL,$like,$where=NULL){
		
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
