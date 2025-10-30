<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Buyer_manual_model extends MY_Model
{

    function __construct()
    {
        parent::__construct();
    }
    public function buyer_manual_list($per_page = NULL, $page = 0, $where, $buyer, $title, $count = NULL)
    {
        $this->db->limit($per_page, $page);
        if (count($where) > 0) {
            $this->db->group_start();
            $this->db->where($where);
            $this->db->group_end();
        }
        if ($buyer) {
            $this->db->group_start();
            $this->db->or_like('LOWER(cc.customer_name)', strtolower($buyer));
            $this->db->group_end();
        }
        if ($title) {
            $this->db->group_start();
            $this->db->or_like('LOWER(cbm.title)', strtolower($title));
            $this->db->group_end();
        }
        $this->db->select("cbm.*,ap.admin_fname,admin_lname,cc.customer_name");
        $this->db->join('admin_profile ap', 'ap.uidnr_admin = cbm.created_by', 'left');
        $this->db->join('cust_customers cc', 'cc.customer_id = cbm.buyer_name', 'left');
       
        $this->db->order_by('cbm.created_date', 'DESC');
        $this->db->from('cps_buyermanual cbm');

        $query = $this->db->get();
        // echo $this->db->last_query();die;
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
public function get_buyer(){
   $query = $this->db->select('cc.customer_name,cc.customer_id')->from('cust_customers cc')->where('cc.customer_type','Buyer')->where('cc.customer_id!=',4)->order_by('cc.customer_name','asc')->get();
   if($query){
       return $query->result();
   }
   else{
       return false;
   }
}
public function created_by(){
    $this->db->select("distinct(concat(ap.admin_fname ,' ', ap.admin_lname)) as created_by,ap.uidnr_admin");
    $this->db->from('admin_profile ap');
    $this->db->join('cps_buyermanual cbm', 'ap.uidnr_admin = cbm.created_by', 'left');
   
    // ->get();
    $query = $this->db->get();
    // echo $this->db->last_query();die;
   if($query){
       return $query->result();
   }
   else{
       return false;
   }
}

    

   
    public function edit_buyer_manual($data)
    {
        // print_r($data);die;
        $query =  $this->db->select('cbm.*,cc.customer_name')
        ->join('cust_customers cc', 'cc.customer_id = cbm.buyer_name', 'left')
            ->where('cbm.buyer_manual_id', $data['buyer_manual_id'])
            ->from('cps_buyermanual cbm')->get();
        //   echo $this->db->last_query();die;
        if ($query) {
            return $query->row_array();
        } else {
            return false;
        }
    }
    public function update_record_finding($update_data)
    {
        // print_r($data);die;
        $query =  $this->db->where('record_finding_id', $update_data['id'])->update('record_finding_details', $update_data['update_data']);
        if ($query) {
            return true;
        } else {
            return false;
        }
    }

    public function get_buyer_manual_log($buyer_manual_id)
    {
    $query = $this->db->select('action_taken, created_on as taken_at, text,concat(admin_fname," ",admin_lname) as taken_by')
                        ->join('admin_profile','user_log_history.created_by = admin_profile.uidnr_admin')
                        ->where('source_module','Buyer_manual')
                        ->where('record_id',$buyer_manual_id)
                        ->order_by('id','desc')
                        ->get(' user_log_history');
    if($query->num_rows() > 0){
        return $query->result_array();
    }
    return [];
    }

  
}
