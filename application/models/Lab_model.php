<?php
defined('BASEPATH') or exit('No direct access allowed');

class Lab_model extends MY_Model{
    function __construct(){
        parent::__construct();
    }

    public function get_lab_list($limit = NULL, $start = NULL, $search = NULL, $sortby, $order, $where, $count = NULL){
        if ($sortby != NULL || $order != NULL) {
            $this->db->order_by($sortby, $order);
        } else {
            $this->db->order_by('msl.lab_id', 'desc');
        }
        if ($where) {
            $this->db->where($where);
        }
        if ($search != NULL && $search != 'NULL') {
            $search = trim($search);
            $this->db->group_start();
            $this->db->like('msl.lab_name', $search);
            $this->db->or_like('msb.branch_name', $search);
            $this->db->or_like('msd.division_name', $search);
            $this->db->or_like('mlt.lab_type_name', $search);
            $this->db->or_like('ap.admin_fname', $search);
            $this->db->or_like('msl.created_on', $search);
            $this->db->or_like('msl.status', $search);
            $this->db->group_end();
        }
        $query = $this->db->select('msl.lab_id, msl.lab_name, msb.branch_name, msd.division_name, mlt.lab_type_name, ap.admin_fname, msl.status, msl.created_on, mlt.lab_type_id, msd.division_id, ap.uidnr_admin, msb.branch_id')
            ->from('mst_labs as msl')
            ->join('admin_profile as ap', 'ap.uidnr_admin = msl.created_by', 'left')
            ->join('mst_branches msb','msb.branch_id = msl.mst_labs_branch_id', 'left')
            ->join('mst_divisions msd','msd.division_id = msl.mst_labs_division_id', 'left')
            ->join('mst_lab_type mlt','mlt.lab_type_id = msl.mst_labs_lab_type_id', 'left')
            ->limit($limit, $start)
            ->get();
            if ($count) {
                return $query->num_rows();
            } else {
                if ($query->num_rows() > 0)
                return $query->result();
            else
                return false;
        }
    }

    /* added by millan */
    public function fetch_branch_name(){
        $query = $this->db->select('msb.branch_id, msb.branch_name')
                ->from('mst_branches msb')
                ->order_by('msb.branch_name', 'ASC')
                ->get();
        if($query->num_rows() > 0){
            return $query->result();
        }
        else{
            return false;
        }
    }

    /* added by millan */
    public function fetch_divisions(){
        $query = $this->db->select('msd.division_id, msd.division_name')
            ->from('mst_divisions msd')
            ->order_by('msd.division_name','asc')
            ->where('msd.status','1')
            ->get();
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    /* added by millan */
    public function fetch_lab_types(){
        $query = $this->db->select('mlt.lab_type_id, mlt.lab_type_name')
            ->from('mst_lab_type mlt')
            ->order_by('mlt.lab_type_name','asc')
            ->where('mlt.status','1')
            ->get();
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    /* added by millan */
    public function fetch_labs(){
        $query = $this->db->select('msl.lab_id, msl.lab_name')
            ->from('mst_labs msl')
            ->order_by('msl.lab_name','asc')
            ->where('msl.status','1')
            ->get();
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    /* added by millan */
    public function fetch_created_person(){
        $query = $this->db->select('CONCAT(ap.admin_fname, " ", ap.admin_lname) as created_by, ap.uidnr_admin')
            ->from('admin_profile ap')
            ->order_by('ap.admin_fname','asc')
            ->get();
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }
    
    /* added by millan*/
    public function update_lab_status($lab_id){
        $query = $this->db->get_where('mst_labs', $lab_id);
        $row = $query->row();
        if ($row->status == '0')
            $post = array('status' => '1');
        else
            $post = array('status' => '0');
        $this->db->update('mst_labs', $post, $lab_id);
        if ($this->db->affected_rows() > 0)
            return $post;
        else
            return false;;
    }

    /* added by millan*/
    public function get_user_logData($lab_id){
        $this->db->select('CONCAT(ap.admin_fname," ", ap.admin_lname) as created_by, msl.created_on as date');
        $this->db->from('mst_labs as msl');
        $this->db->join('admin_profile as ap', 'ap.uidnr_admin = msl.created_by', 'left');
        $this->db->where('msl.lab_id', $lab_id);
        $this->db->order_by('msl.lab_id', 'DESC');
        $result = $this->db->get();
        if ($result->num_rows() > 0) {
            return $result->result();
        } else {
            return false;
        }
    }

    // added by millan on 09-04-2021
    public function fetch_lab_for_edit($data){
        $query = $this->db->get_where('mst_labs', $data);
        if ($query) {
            return $query->row();
        } else {
            return false;
        }
    }

    // added by millan
    public function get_lab_log($lab_id){
        $query = $this->db->select('action_taken, created_on as taken_at, text,concat(admin_fname," ",admin_lname) as taken_by')
            ->join('admin_profile', 'user_log_history.created_by = admin_profile.uidnr_admin')
            ->where('source_module', 'Lab')
            ->where('record_id', $lab_id)
            ->order_by('id', 'desc')
            ->get('user_log_history');
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }
}
?>