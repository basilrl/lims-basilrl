<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Accreditation_model extends MY_Model
{

    function __construct()
    {
        parent::__construct();
    }

    public function get_accreditation($start, $end, $search, $sortby, $order, $count = NULL)
    {
        $this->db->select('acc.acc_id, acc.title, acc.upload_filename, acc.scope_filename, ct.country_name, mb.branch_name, acc.acc_standard, acc.certificate_no, acc.validity, DATE_FORMAT(acc.uploaded_on,"%d-%m-%y") as uploaded_on, acc.uploaded_by, admin.admin_fname');
        $this->db->join('mst_country ct', 'ct.country_id = acc.country_id', 'left');
        $this->db->join('admin_profile as admin', 'admin.uidnr_admin = acc.uploaded_by');
        $this->db->join('mst_branches mb', 'mb.branch_id = acc.branch_id', 'left');
        ($search['certificate_no'] != 'NULL') ? $this->db->like('acc.certificate_no', $search['certificate_no']) : '';
        ($search['country_name'] != 'NULL') ? $this->db->where('ct.country_name', $search['country_name']) : '';
        ($search['branch_name'] != 'NULL') ? $this->db->where('mb.branch_name', $search['branch_name']) : '';

        if (!$count) {
            $this->db->limit($start, $end);
        }
        if ($sortby != 'NULL' || $order != 'NULL') {
            $this->db->order_by($sortby, $order);
        } else {
            $this->db->order_by('acc_id', 'desc');
        }

        $query = $this->db->get('cps_accreditation acc');

        if ($count) {
            return $query->num_rows();
        } elseif ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return [];
        }
    }
    public function fetch_branch_name()
    {
        $query = $this->db->select('mb.branch_id, mb.branch_name')
            ->from('mst_branches mb')
            ->order_by('mb.branch_name', 'ASC')
            ->get();
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }
    public function fetch_created_person()
    {
        $query = $this->db->select('ap.admin_fname as uploaded_by, ap.uidnr_admin')
            ->from('admin_profile ap')
            ->order_by('ap.admin_fname', 'asc')
            ->get();
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    public function fetch_country()
    {
        $query = $this->db->select('msc.country_id, msc.country_name')
            ->from('mst_country msc')
            ->where('status', '1')
            ->order_by('msc.country_name', 'ASC')
            ->get();
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }



    public function get_accredit_log($acc_id)
    {
        $query = $this->db->select('action_taken, created_on as taken_at, text,concat(admin_fname," ",admin_lname) as taken_by')
            ->join('admin_profile', 'user_log_history.created_by = admin_profile.uidnr_admin')
            ->where('source_module', 'Accreditation')
            ->where('record_id', $acc_id)
            ->order_by('id', 'desc')
            ->get('user_log_history');
        //  echo $this->db->last_query(); die;
        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return false;
        }
    }
}
