<?php

defined('BASEPATH') or exit('No direct access allowed');

class Sample_tracking_model extends MY_Model
{

    function __construct()
    {
        parent::__construct();
    }


    public function sample_details($where){
        $this->db->select('sr.status as final_status,sample.sample_type_name as sample_name,CONCAT(ap.admin_fname," ",ap.admin_lname) as created_by,DATE_FORMAT(trf.create_on,"%d-%b-%Y %H:%i") AS create_on,sr.collection_date,DATE_FORMAT(sr.received_date,"%d-%b-%Y %H:%i") AS received_date,sr.sample_desc,sr.qty_received,sr.test_completed_on,sr.gc_no,sr.type,sr.sample_retain_period,cus.customer_name as customer_name,buyer.customer_name as buyer_name,DATE_FORMAT(sr.due_date,"%d-%b-%Y") AS due_date,CASE WHEN (lab.lab_name IS NOT NULL) THEN lab.lab_name ELSE "NO LAB ASSIGN!" END as lab_name');
        $this->db->from('sample_registration sr');
        $this->db->join('trf_registration trf','sr.trf_registration_id = trf.trf_id','left');
        $this->db->join('cust_customers cus','cus.customer_id = trf.trf_applicant','left');
        $this->db->join('cust_customers buyer','buyer.customer_id = trf.trf_buyer','left');
        $this->db->join('contacts con','con.contact_id = trf.trf_contact','left');
        $this->db->join('mst_sample_types sample','sample.sample_type_id = sr.sample_registration_sample_type_id','left');
        $this->db->join('admin_profile ap','ap.uidnr_admin = sr.create_by','left');
        $this->db->join('mst_labs lab','lab.lab_id = sr.sample_registered_to_lab_id','left');
       
        $this->db->where($where);
        $result = $this->db->get();
        if($result->num_rows()>0){
            return $result->row();
        }
        else{
            return false;
        }
    }
    

    public function sample_tracking_status($where){
        $this->db->select('s_log.new_status as status,DATE_FORMAT(s_log.log_activity_on,"%d-%b-%Y %H:%i") AS log_activity_on,CONCAT(ap.admin_fname," ",ap.admin_lname) as status_created_by,s_log.log_activity_on as date_on');
        $this->db->from('sample_reg_activity_log s_log');
        $this->db->join('sample_registration sr','sr.sample_reg_id = s_log.sample_reg_id','left');
        
        if(!empty($where['sr.sample_reg_id'])){
            $this->db->where($where);
        }
        else{
            return false;
        }
        $this->db->join('admin_profile ap','ap.uidnr_admin = s_log.uidnr_admin','left');
      
        $query1 =  $this->db->get_compiled_select();

        $this->db->select('s_log.new_status as status,DATE_FORMAT(s_log.log_activity_on,"%d-%b-%Y %H:%i") AS log_activity_on,CONCAT(ap.admin_fname," ",ap.admin_lname) as status_created_by,s_log.log_activity_on as date_on');
        $this->db->from('jobs_activity_log s_log');
        $this->db->join('sample_registration sr','sr.trf_registration_id = s_log.trf_id','left');

        if(!empty($where['sr.sample_reg_id'])){
            $this->db->where($where);
        }
        else{
            return false;
        }
        $this->db->join('admin_profile ap','ap.uidnr_admin = s_log.uidnr_admin','left');
        // $this->db->order_by('s_log.log_activity_on','ASC');
        // $this->db->group_by('s_log.new_status');
        $query2 = $this->db->get_compiled_select();
        
        $query = $this->db->query('SELECT * FROM ('.$query1 . ' UNION ALL ' . $query2.') AS table1 GROUP BY status ORDER BY date_on ASC');
        //   echo $this->db->last_query();die;
          if ($query->num_rows() > 0) {
              return $query->result();
          } else {
              return false;
          }
        
       
    }

   
    public function get_auto_track_gc($col,$table,$search = NULL,$like,$where=NULL){
		
        $this->db->select($col)
                        ->from($table);
                if($where!=NULL){
                        $this->db->where($where);
                    }
                if($search!=NULL){
                    $this->db->like($like,trim($search));
                }
                $this->db->order_by($like,'asc');

                $this->db->limit(10);
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