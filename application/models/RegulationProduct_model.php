<?php

class RegulationProduct_model extends MY_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function insert_regpro($inserted_data)
    {
        $query = $this->db->insert('cps_regulations_products', $inserted_data);
        return $this->db->insert_id();
    }

    public function fetch_regpro_list($limit = NULL, $start = NULL, $search = NULL, $sortby = NULL, $order = NULL, $where = NULL, $count = NULL){
        if ($sortby != NULL || $order != NULL) {
            $this->db->order_by($sortby, $order);
        } else {
            $this->db->order_by('crp.reg_product_id', 'DESC');
        }
        if ($where) {
            $this->db->where($where);
        }

        if ($search != NULL && $search != 'NULL') {
            $search = trim($search);
            $this->db->group_start();
            $this->db->like('crp.reg_product_title', $search);
            $this->db->or_like('ap.admin_fname', $search);
            $this->db->or_like('crp.created_date', $search);
            $this->db->group_end();
        }
    
        $query = $this->db->select('crp.reg_product_id, crp.reg_product_title, nb.notified_body_name, crp.status, ap.admin_fname, crp.created_date')
                ->from('cps_regulations_products as crp')
                ->join('notified_body as nb', 'nb.notified_body_id = crp.notified_body_id', 'left')
                ->join('admin_profile ap', 'ap.uidnr_admin = crp.created_by', 'left')
                ->limit($limit, $start)
                ->get();
        if ($query->num_rows() > 0) {
            if ($count != NULL) {
                return $query->num_rows();
            } else {
                return $query->result();
            }
        } else {
            return false;
        }
    }

    public function delete_regpro($reg_product_id){
        $this->db->where($reg_product_id);
        $query = $this->db->delete('cps_regulations_products');
        return $query;
    }

    public function fetch_regpro_for_edit($data){
        $query = $this->db->get_where('cps_regulations_products', $data);
        if ($query) {
            return $query->row();
        } else {
            return false;
        }
    }

    public function update_regpro($post){
        $checkUser = (array) $this->session->userdata('user_data');
        $this->db->where('reg_product_id', $post['reg_product_id']);
        $notify_body = implode("," , $post['notified_body_id']);
        $result = $this->db->update('cps_regulations_products', array('reg_product_title' => $post['reg_product_title'], 'notified_body_id' => $notify_body, 'updated_on' => date('Y-m-d H:i:s'), 'updated_by' => $checkUser['uidnr_admin']));
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    public function update_regpro_status($reg_product_id){
        $query = $this->db->get_where('cps_regulations_products', $reg_product_id);
        $row = $query->row();
        if ($row->status == '0')
            $post = array('status' => '1');
        else
            $post = array('status' => '0');
        $this->db->update('cps_regulations_products', $post, $reg_product_id);
        if ($this->db->affected_rows() > 0)
            return $post;
        else
            return false;;
    }

    public function fetch_notified_body(){
        $this->db->select('notified_body_id, notified_body_name');
        $query = $this->db->get('notified_body');
        if ($query->result_array())
            return $query->result_array();
        else
            return false;
    }

    // added by  millan
    public function fetch_pro_title(){
        $query = $this->db->select('crp.reg_product_id, crp.reg_product_title')
                ->from('cps_regulations_products as crp')
                ->get();
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    // added by millan on 21-03-2021
    public function fetch_created_person(){
        $query = $this->db->select('CONCAT(ap.admin_fname, " ", ap.admin_lname) as created_by, ap.admin_fname, ap.admin_lname,  ap.uidnr_admin')
                ->from('admin_profile as ap')
                ->get();
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    
    public function get_log_data($id)
    {
  
      $where = array();
      $where['ul.source_module'] = 'RegulationProduct';
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
