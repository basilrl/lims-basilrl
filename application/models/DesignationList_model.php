<?php

class DesignationList_model extends MY_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function insert_designation($inserted_data)
    {
        $query = $this->db->insert('mst_designations', $inserted_data);
        return $this->db->insert_id();
    }

    public function get_designation_list($limit = NULL, $start = NULL, $search = NULL, $sortby = NULL, $order = NULL, $where = NULL, $count = NULL){
        if ($sortby != NULL || $order != NULL) {
          $this->db->order_by($sortby, $order);
        } else {
          $this->db->order_by('msd.designation_id', 'DESC');
        }
    
        if ($where) {
          $this->db->where($where);
        }
    
        if ($search != NULL && $search != 'NULL') {
          $search = trim($search);
          $this->db->group_start();
          $this->db->like('msd.designation_name', $search);
          $this->db->or_like('msd.created_on', $search);
          $this->db->or_like('ap.admin_fname', $search);
          $this->db->or_like('ar.admin_role_name', $search);
          $this->db->group_end();
        }
    
        $this->db->select('msd.designation_id, msd.designation_name, msd.created_on, msd.status, ap.admin_fname, ar.admin_role_name');
        $this->db->from('mst_designations as msd');
        $this->db->join('admin_profile as ap', 'ap.uidnr_admin = msd.created_by', 'left');
        $this->db->join('admin_role as ar', 'ar.id_admin_role = msd.report_to', 'left');
        $this->db->limit($limit, $start);
        $result = $this->db->get();
        if ($result->num_rows() > 0) {
            if ($count != NULL) {
                return $result->num_rows();
        } else {
            return $result->result();
          }
        } 
        else {
          return false;
        }
    }

    public function delete_designation($designation_id){
        $this->db->where($designation_id);
        $query = $this->db->delete('mst_designations');
        return $query;
    }

    public function fetch_designation_for_edit($data){
        $query = $this->db->select('msd.*')
                ->from('mst_designations msd')
                ->where('msd.designation_id', $data)
                ->get();
        if ($query) {
            return $query->row();
        } else {
            return false;
        }
    }

    public function update_designation($post){
        $checkUser = (array) $this->session->userdata('user_data');
        $this->db->where('designation_id', $post['designation_id']);
        $this->db->update('mst_designations', array('designation_name' => $post['designation_name'], 'report_to' => $post['report_to'], 'updated_on' => date('Y-m-d H:i:s'), 'updated_by' => $checkUser['uidnr_admin']));
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function update_designation_status($designation_id){
        $query = $this->db->get_where('mst_designations', $designation_id);
        $row = $query->row();
        if ($row->status == '0')
            $post = array('status' => '1');
        else
            $post = array('status' => '0');
        $this->db->update('mst_designations', $post, $designation_id);
        if ($this->db->affected_rows() > 0)
            return $post;
        else
            return false;;
    }

    public function fetch_report_to(){
        $query = $this->db->select('ar.id_admin_role, ar.admin_role_name')
                ->from('admin_role ar')
                ->order_by('ar.admin_role_name', 'ASC')
                ->get();
        if ($query->num_rows() > 0){
            return $query->result();
        }
        else{
            return false;
        }
    }

    public function fetch_created_person(){
        $query = $this->db->select('CONCAT(ap.admin_fname, " ", ap.admin_lname) as created_by, ap.admin_fname, ap.admin_lname,  ap.uidnr_admin')
            ->from('admin_profile as ap')
            ->order_by('ap.admin_fname', 'ASC')
            ->get();
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    public function fetch_designation_name(){
        $query = $this->db->select('msd.designation_id, msd.designation_name')
            ->from('mst_designations msd')
            ->where('msd.status', '1')
            ->order_by('msd.designation_name')
            ->get();
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    // added by millan on 09-04-2021
    public function get_designation_log($designation_id){
        $query = $this->db->select('action_taken, created_on as taken_at, text,concat(admin_fname," ",admin_lname) as taken_by')
            ->join('admin_profile', 'user_log_history.created_by = admin_profile.uidnr_admin')
            ->where('source_module', 'DesignationList')
            ->where('record_id', $designation_id)
            ->order_by('id', 'desc')
            ->get('user_log_history');
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }
}
