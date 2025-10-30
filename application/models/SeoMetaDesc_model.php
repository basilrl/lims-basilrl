<?php

defined('BASEPATH') or exit('No direct access allowed');

class SeoMetaDesc_model extends MY_Model{
    
    public function __construct() {
        parent::__construct();
    }
    
      public function seo_meta_desc_list($limit = NULL, $start = NULL, $search = NULL, $sortby, $order, $where, $count = NULL){
        $this->db->limit($limit, $start);
        if ($sortby != NULL || $order != NULL) {
            $this->db->order_by($sortby, $order);
        } else {
            $this->db->order_by('manual.created_date', 'desc');
        }
        $this->db->where($where);
        
        if ($search != NULL) {
             $this->db->or_like('cps_meta_content.page_title', $search);
            
        }
        $this->db->select('manual.page_id, manual.page_title, manual.page_url, manual.description,'
                . ' manual.keywords, manual.created_date, admin.admin_fname')
                ->from('cps_meta_content as manual')
                ->join('admin_profile as admin', 'admin.uidnr_admin=manual.created_by', 'left');
            
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
     
     public function add_cps_meta_content($insertData){
       return  $this->db->insert('cps_meta_content', $insertData); 
     }
     
     public function getSeoMeta($pid){
         return $this->db->select('*')
                 ->from('cps_meta_content')
                 ->where('page_id', $pid)
                 ->get()
                 ->row_array();
     }
     
     public function update_cps_meta_content($updateData, $page_id){
         return $this->db->update('cps_meta_content', $updateData, ['page_id' => $page_id]);
     }
}
