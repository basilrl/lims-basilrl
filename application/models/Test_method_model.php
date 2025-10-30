<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Test_method_model extends MY_Model
{

    function __construct()
    {
        parent::__construct();
    }

    public function get_testmethod($start, $end, $search, $sortby, $order, $count = NULL)
    {
        $this->db->select('test_method_id, test_method_name,mst_test_methods.created_on, admin.admin_fname');

        $this->db->join('admin_profile as admin', 'admin.uidnr_admin = mst_test_methods.created_by', 'left');
        ($search['test_method_name'] != 'NULL') ? $this->db->like('mst_test_methods.test_method_name', $search['test_method_name']) : '';
        ($search['uidnr_admin'] != 'NULL') ? $this->db->where('admin.uidnr_admin', $search['uidnr_admin']) : '';
        if (!$count) {
            $this->db->limit($start, $end);
        }
        if ($sortby != 'NULL' || $order != 'NULL') {
            $this->db->order_by($sortby, $order);
        } else {
            $this->db->order_by('test_method_id', 'desc');
        }

        $query = $this->db->get('mst_test_methods');
        //   echo $this->db->last_query();


        if ($count) {
            return $query->num_rows();
        } elseif ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return [];
        }
    }

    public function get_accredit_log($testmethod_id)
    {

        $query = $this->db->select('action_taken, created_on as taken_at, text,concat(admin_fname," ",admin_lname) as taken_by')
            ->join('admin_profile', 'user_log_history.created_by = admin_profile.uidnr_admin')
            ->where('source_module', 'Test_method')
            ->where('record_id', $testmethod_id)
            ->distinct()
            ->order_by('id', 'desc')
            ->get('user_log_history');
        //   echo $this->db->last_query(); die;
        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return false;
        }
    }
    public function fetch_created_person()
    {
        $query = $this->db->select('ap.admin_fname as created_by, ap.uidnr_admin')
            ->from('admin_profile ap')
            ->order_by('ap.admin_fname', 'asc')
            ->get();
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    public function fetch_testmethod_for_edit($data)
    {
        $query = $this->db->get_where('mst_test_methods', $data);
        // echo $this->db->last_query(); die;
        if ($query) {
            return $query->row();
        } else {
            return false;
        }
    }
}
