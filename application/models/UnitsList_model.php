<?php

class UnitsList_model extends MY_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function insert_unit($post)
    {
        $q = $this->db->insert('units', $post);
        return $this->db->insert_id();
    }

    public function get_units_list($limit = NULL, $start = NULL, $search = NULL, $sortby = NULL, $order = NULL, $where = NULL, $count = NULL)
    {
        if ($sortby != NULL || $order != NULL) {
            $this->db->order_by($sortby, $order);
        } else {
            $this->db->order_by('un.unit_id', 'DESC');
        }

        if ($where) {
            $this->db->where($where);
        }

        if ($search != NULL && $search != 'NULL') {
            $search = trim($search);
            $this->db->group_start();
            $this->db->like('un.unit', $search);
            $this->db->or_like('un.unit_type', $search);
            $this->db->or_like('ap.admin_fname', $search);
            $this->db->or_like('un.created_on', $search);
            $this->db->group_end();
        }

        $this->db->select('un.unit_id, un.unit, un.unit_type, ap.admin_fname, un.created_on');
        $this->db->from('units as un');
        $this->db->join('admin_profile as ap', 'ap.uidnr_admin = un.created_by', 'left');
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

    public function delete_unit($unit_id){
        $this->db->where($unit_id);
        $query = $this->db->delete('units');
        return $query;
    }

    public function fetch_unit_for_edit($post)
    {
        $query = $this->db->get_where('units', $post);
        return $query->row();
    }

    public function update_unit($post)
    {
        $this->db->where('unit_id', $post['unit_id']);
        $this->db->update('units', array('unit' => $post['unit'], 'unit_type' => $post['unit_type'], 'updated_on' => date('Y-m-d H:i:s')));
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    // added by millan on 23-03-2021
    public function fetch_units_name()
    {
        $query = $this->db->select('unit_id, unit, unit_type')
            ->from('units')
            ->get();
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    // added by millan on 23-03-2021
    public function fetch_created_person()
    {
        $query = $this->db->select('CONCAT(ap.admin_fname, " ", ap.admin_lname) as created_by, ap.admin_fname, ap.admin_lname,  ap.uidnr_admin')
            ->from('admin_profile as ap')
            ->get();
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    public function get_unit_log($unit_id){
        $query = $this->db->select('action_taken, created_on as taken_at, text,concat(admin_fname," ",admin_lname) as taken_by')
            ->join('admin_profile', 'user_log_history.created_by = admin_profile.uidnr_admin')
            ->where('source_module', 'UnitsList')
            ->where('record_id', $unit_id)
            ->order_by('id', 'desc')
            ->get('user_log_history');
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }
}
