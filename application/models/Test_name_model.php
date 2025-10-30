<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Test_name_model extends MY_Model
{

    function __construct()
    {
        parent::__construct();
    }
    public function get_testname($start, $end, $search, $sortby, $order, $count = NULL)
    {
        $this->db->select('test_id, test_name,cps_test_list.created_on, admin.admin_fname');

        $this->db->join('admin_profile as admin', 'admin.uidnr_admin = cps_test_list.created_by');
        ($search['test_name'] != 'NULL') ? $this->db->like('cps_test_list.test_name', $search['test_name']) : '';
        ($search['uidnr_admin'] != 'NULL') ? $this->db->where('admin.uidnr_admin', $search['uidnr_admin']) : '';
        if (!$count) {
            $this->db->limit($start, $end);
        }
        if ($sortby != 'NULL' || $order != 'NULL') {
            $this->db->order_by($sortby, $order);
        } else {
            $this->db->order_by('test_id', 'desc');
        }

        $query = $this->db->get('cps_test_list');
        //   echo $this->db->last_query();


        if ($count) {
            return $query->num_rows();
        } elseif ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return [];
        }
    }

    public function get_accredit_log($test_id)
    {

        $query = $this->db->select('action_taken, created_on as taken_at, text,concat(admin_fname," ",admin_lname) as taken_by')
            ->join('admin_profile', 'user_log_history.created_by = admin_profile.uidnr_admin')
            ->where('source_module', 'Test_Name')
            ->where('record_id', $test_id)
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

    public function fetch_testname_for_edit($data)
    {
        $query = $this->db->get_where('cps_test_list', $data);
        // echo $this->db->last_query(); die;
        if ($query) {
            return $query->row();
        } else {
            return false;
        }
    }

    // public function update_testname($post)
    // {
    //     $checkUser = (array) $this->session->userdata('user_data');
    //     $this->db->where('test_id', $post['test_id']);
    //     //  $notify_body = implode("," , $post['test_id']);
    //     $result = $this->db->update('cps_test_list', array('test_name' => $post['test_name'], 'updated_on' => date('Y-m-d H:i:s'), 'updated_by' => $checkUser['uidnr_admin']));
    //     if ($result) {
    //         return true;
    //     } else {
    //         return false;
    //     }
    // }
}
