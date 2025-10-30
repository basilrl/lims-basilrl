<?php
defined('BASEPATH') or exit('No direct access allowed');

class Division_model extends MY_Model{
    function __construct(){
        parent::__construct();
    }

    public function get_division_list($limit = NULL, $start = NULL, $search = NULL, $sortby = NULL, $order = NULL, $where = NULL, $count = NULL){
        if ($sortby != NULL || $order != NULL) {
          $this->db->order_by($sortby, $order);
        } else {
          $this->db->order_by('msd.division_id', 'DESC');
        }
    
        if ($where) {
          $this->db->where($where);
        }
    
        if ($search != NULL && $search != 'NULL') {
          $search = trim($search);
          $this->db->group_start();
          $this->db->like('msd.division_name', $search);
          $this->db->or_like('msd.created_on', $search);
          $this->db->or_like('ap.admin_fname', $search);
          $this->db->or_like('msd.division_code', $search);
          $this->db->group_end();
        }
    
        $this->db->select('msd.division_id, msd.division_name, msd.division_code,msd.erpdivision_code, msd.created_on, msd.status, ap.admin_fname');
        $this->db->from('mst_divisions as msd');
        $this->db->join('admin_profile as ap', 'ap.uidnr_admin = msd.created_by', 'left');
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

    // added by millan on 10-04-2021
    public function fetch_division_for_edit($data) {
        $query = $this->db->select('msd.*')
                ->from('mst_divisions msd')
                ->where('msd.division_id', $data)
                ->get();
        if ($query) {
            return $query->row();
        } else {
            return false;
        }
    }

    // added by millan on 10-04-2021
    public function update_division_status($division_id) {
        $query = $this->db->get_where('mst_divisions', $division_id);
        $row = $query->row();
        if ($row->status == '0')
            $post = array('status' => '1');
        else
            $post = array('status' => '0');
        $this->db->update('mst_divisions', $post, $division_id);
        if ($this->db->affected_rows() > 0)
            return $post;
        else
            return false;;
    }

    // added by millan on 10-04-2021
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

    // added by millan on 10-04-2021
    public function fetch_division_name(){
        $query = $this->db->select('msd.division_name')
            ->from('mst_divisions msd')
            ->where('msd.status', '1')
            ->order_by('msd.division_name', 'ASC')
            ->get();
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    // added by millan on 10-04-2021
    public function fetch_division_code(){
        $query = $this->db->select('msd.division_code')
            ->from('mst_divisions msd')
            ->where('msd.status', '1')
            ->order_by('msd.division_code', 'ASC')
            ->get();
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    // added by millan on 10-04-2021
    public function get_division_log($division_id){
        $query = $this->db->select('action_taken, created_on as taken_at, text,concat(admin_fname," ",admin_lname) as taken_by')
            ->join('admin_profile', 'user_log_history.created_by = admin_profile.uidnr_admin')
            ->where('source_module', 'Division')
            ->where('record_id', $division_id)
            ->order_by('id', 'desc')
            ->get('user_log_history');
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }
}
?>