<?php

class LabType_model extends MY_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function insert_labtype($inserted_data){
        $query = $this->db->insert('mst_lab_type', $inserted_data);
        return $this->db->insert_id();
    }

    public function get_labtype_list($limit = NULL, $start = NULL, $search = NULL, $sortby = NULL, $order = NULL, $where = NULL, $count = NULL){
        if ($sortby != NULL || $order != NULL) {
            $this->db->order_by($sortby, $order);
        } else {
            $this->db->order_by('mlt.lab_type_id', 'DESC');
        }

        if ($where) {
            $this->db->where($where);
        }

        if ($search != NULL && $search != 'NULL') {
            $search = trim($search);
            $this->db->group_start();
            $this->db->like('mlt.lab_type_name', $search);
            $this->db->or_like('msd.division_name', $search);
            $this->db->or_like('ap.admin_fname', $search);
            $this->db->or_like('mlt.created_on', $search);
            $this->db->group_end();
        }

        $this->db->select('mlt.lab_type_id, mlt.lab_type_name, msd.division_name, ap.admin_fname, mlt.created_on, mlt.status');
        $this->db->from('mst_lab_type mlt');
        $this->db->join('mst_divisions as msd', 'msd.division_id = mlt.mst_lab_type_division_id', 'left');
        $this->db->join('admin_profile as ap', 'ap.uidnr_admin = mlt.created_by', 'left');
        $this->db->limit($limit, $start);
        $result = $this->db->get();
        if ($result->num_rows() > 0) {
            if ($count != NULL) {
                return $result->num_rows();
            } else {
                return $result->result();
            }
        } else {
            return false;
        }
    }

    public function delete_lab_type($lab_type_id){
        $this->db->where($lab_type_id);
        $query = $this->db->delete('mst_lab_type');
        return $query;
    }

    public function fetch_labtype_for_edit($data){
        $query = $this->db->get_where('mst_lab_type', $data);
        if ($query) {
            return $query->row();
        } else {
            return false;
        }
    }

    public function update_labtype($post){
        $checkUser = (array) $this->session->userdata('user_data');
        $this->db->where('lab_type_id', $post['lab_type_id']);
        $this->db->update('mst_lab_type', array('lab_type_name' => $post['lab_type_name'], 'mst_lab_type_division_id' => $post['mst_lab_type_division_id'], 'updated_on' => date('Y-m-d H:i:s'), 'updated_by' => $checkUser['uidnr_admin']));
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function update_labtype_status($lab_type_id){
        $query = $this->db->get_where('mst_lab_type', $lab_type_id);
        $row = $query->row();
        if ($row->status == '0')
            $post = array('status' => '1');
        else
            $post = array('status' => '0');
        $this->db->update('mst_lab_type', $post, $lab_type_id);
        if ($this->db->affected_rows() > 0)
            return $post;
        else
            return false;;
    }

    public function fetch_division(){
        $this->db->select('division_id, division_name, status');
        $query = $this->db->get('mst_divisions');
        if ($query->result_array())
            return $query->result_array();
        else
            return false;
    }

    /* added by millan */
    public function fetch_lab_types(){
        $query = $this->db->select('mlt.lab_type_id, mlt.lab_type_name')
            ->from('mst_lab_type mlt')
            ->order_by('mlt.lab_type_name', 'asc')
            ->where('mlt.status', '1')
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
            ->order_by('ap.admin_fname', 'asc')
            ->get();
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    // added by millan
    public function get_lab_type_log($lab_type_id){
        $query = $this->db->select('action_taken, created_on as taken_at, text,concat(admin_fname," ",admin_lname) as taken_by')
            ->join('admin_profile', 'user_log_history.created_by = admin_profile.uidnr_admin')
            ->where('source_module', 'LabType')
            ->where('record_id', $lab_type_id)
            ->order_by('id', 'desc')
            ->get('user_log_history');
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }
}
