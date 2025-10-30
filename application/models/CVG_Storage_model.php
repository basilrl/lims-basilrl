<?php

defined('BASEPATH') or exit('No direct access allowed');

class CVG_Storage_model extends MY_Model{
    
    public function __construct() {
        parent::__construct();
        $checkUser = $this->session->userdata('user_data');
		$this->user = $checkUser->uidnr_admin;
    }

      public function cvg_storage_list($limit = NULL, $start = NULL, $search = NULL, $sortby, $order, $where, $count = NULL){
        $this->db->limit($limit, $start);
        if ($sortby != NULL || $order != NULL) {
            $this->db->order_by($sortby, $order);
        } else {
            $this->db->order_by('cvg.create_on', 'desc');
        }
        $this->db->where($where);
        
        if ($search != NULL) {
             $this->db->or_like('cvg.title', $search);
            
        }
        
        $this->db->select('cvg.*, mst_country.country_name, admin.admin_fname, accredited_by.accredited_by')
                ->from('cvg_storage as cvg')
               ->join('admin_profile as admin', 'admin.uidnr_admin = cvg.create_by', 'left')
               ->join('accredited_by', 'accredited_by.accredited_by_id = cvg.accredited_by', 'left')
               ->join('mst_country', 'mst_country.country_id = cvg.country_id', 'left');
            
         $query = $this->db->get();
         
          if ($count === '1') {
            return $query->num_rows();
        }
        else{
            if ($query->result_array()) {
                return $query->result_array();
            } else {
                return false;
            }
        }
     }
     
     public function add_cvg_storage($insertData){
       $result =  $this->db->insert('cvg_storage', $insertData);
       if($result){
        $data = array();
        $data['source_module'] = 'CVG_Storage';
        $data['record_id'] = $this->db->insert_id();
        $data['created_on'] = date("Y-m-d h:i:s");
        $data['created_by'] = $this->user;
        $data['action_taken'] = 'add_cvg_storage';
        $data['text'] = 'cvg storage added';
      
        $result = $this->insert_data('user_log_history',$data);
       
        if($result){
            return true;
        }
        else{
            return false;
        }
       }
       else{
        return false;
         } 
     }
     
     public function getCvgStorage($cs_id){
         return $this->db->select('*')
                 ->from('cvg_storage')
                 ->where('cvg_storage_id', $cs_id)
                 ->get()
                 ->row_array();
     }
     
     public function update_cvg_storage($updateData, $page_id){
         $result =  $this->db->update('cvg_storage', $updateData, ['cvg_storage_id' => $page_id]);
         if($result){
            $data = array();
            $data['source_module'] = 'CVG_Storage';
            $data['record_id'] = $page_id;
            $data['created_on'] = date("Y-m-d h:i:s");
            $data['created_by'] = $this->user;
            $data['action_taken'] = 'update_cvg_storage';
            $data['text'] = 'cvg storage updated';
            $result = $this->insert_data('user_log_history',$data);
            if($result){
                return true;
            }
            else{
                return false;
            }
         }
         else{
             return false;
         }
        
  
        
     }
     
     public function get_accredited_by(){
         try {
             
          return $this->db->select('accredited_by_id, accredited_by')
                          ->from('accredited_by')
                          ->where('status', 1)
                          ->get()->result_array(); 
         } catch (Exception $ex) {
           return false;  
         } 
     }


     public function get_log_data($id)
     {
   
       $where = array();
       $where['ul.source_module'] = 'CVG_Storage';
       $where['ul.record_id'] = $id;
   
       $this->db->select('ul.action_taken,ul.created_on as taken_at,ul.text, CONCAT(ap.admin_fname," ",ap.admin_lname) as taken_by');
       $this->db->from('user_log_history ul');
       $this->db->join('admin_profile ap','ul.created_by = ap.uidnr_admin','left');
       $this->db->order_by('ul.id','DESC');
       $this->db->where($where);
       $result = $this->db->get();
       // echo $this->db->last_query();die;
       if($result->num_rows()>0){
         return $result->result();
       }
       else{
         return false;
       }
     
   
     }
}



