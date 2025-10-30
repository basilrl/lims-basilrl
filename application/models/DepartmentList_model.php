<?php

class DepartmentList_model extends MY_Model {

    public function __construct() {
        parent::__construct();
    }

    public function insert_department($post) {
        $q = $this->db->insert('mst_departments', $post);
        return $this->db->insert_id();
    }

    public function get_department_list($limit = NULL, $start = NULL, $search = NULL, $sortby = NULL, $order = NULL, $where = NULL, $count = NULL){
        if ($sortby != NULL || $order != NULL) {
          $this->db->order_by($sortby, $order);
        } else {
          $this->db->order_by('msd.dept_id', 'DESC');
        }
    
        if ($where) {
          $this->db->where($where);
        }
    
        if ($search != NULL && $search != 'NULL') {
          $search = trim($search);
          $this->db->group_start();
          $this->db->like('msd.dept_code', $search);
          $this->db->or_like('msd.created_on', $search);
          $this->db->or_like('ap.admin_fname', $search);
          $this->db->or_like('msd.dept_name', $search);
          $this->db->group_end();
        }
    
        $this->db->select('msd.dept_id, msd.dept_code, msd.created_on, msd.status, ap.admin_fname, msd.dept_name');
        $this->db->from('mst_departments as msd');
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

    public function delete_department($dept_id) {
        $this->db->where($dept_id);
        $query = $this->db->delete('mst_departments');
        return $query;
    }

    public function fetch_department_for_edit($data) {
        $query = $this->db->select('msd.*')
                ->from('mst_departments msd')
                ->where('msd.dept_id', $data)
                ->get();
        if ($query) {
            return $query->row();
        } else {
            return false;
        }
    }

    public function update_department($post) {
        $this->db->where('dept_id', $post['dept_id']);
        $this->db->update('mst_departments', array('dept_code' => $post['dept_code'], 'dept_name' => $post['dept_name'], 'updated_on' =>date('Y-m-d H:i:s')));        
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function update_department_status($dept_id) {
        $query = $this->db->get_where('mst_departments', $dept_id);
        $row = $query->row();
        if ($row->status == '0')
            $post = array('status' => '1');
        else
            $post = array('status' => '0');
        $this->db->update('mst_departments', $post, $dept_id);
        if ($this->db->affected_rows() > 0)
            return $post;
        else
            return false;;
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

    public function fetch_department_name(){
        $query = $this->db->select('msd.dept_name')
            ->from('mst_departments msd')
            ->where('msd.status', '1')
            ->order_by('msd.dept_name', 'ASC')
            ->get();
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    public function fetch_department_code(){
        $query = $this->db->select('msd.dept_code')
            ->from('mst_departments msd')
            ->where('msd.status', '1')
            ->order_by('msd.dept_code', 'ASC')
            ->get();
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    // added by millan on 09-04-2021
    public function get_department_log($dept_id){
        $query = $this->db->select('action_taken, created_on as taken_at, text,concat(admin_fname," ",admin_lname) as taken_by')
            ->join('admin_profile', 'user_log_history.created_by = admin_profile.uidnr_admin')
            ->where('source_module', 'DepartmentList')
            ->where('record_id', $dept_id)
            ->order_by('id', 'desc')
            ->get('user_log_history');
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }
}
