<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Hold_sample_model extends MY_Model
{

    function __construct()
    {
        parent::__construct();
    }

    public function get_hold_status()
    {
        $data =  $this->db->select('hold_sample_status.*')->from('hold_sample_status')->get();
        if ($data) {
            return $data->result_array();
        } else {
            return false;
        }
    }

    public function get_reason($data)
    {
        $query =  $this->db->select('shr.previous_Status,hss.hold_Reason,shr.hold_reason,shr.sample_reg_id,concat(ap.admin_fname," ",ap.admin_lname) as created_by,shr.created_on')
            ->from('sample_hold_remark shr')
            ->join('hold_sample_status hss', 'hss.status_id = shr.hold_remark', 'left')->join('admin_profile ap', 'ap.uidnr_admin = shr.done_by', 'left')
            ->where('sample_reg_id', $data['sample_reg_id'])->where('hold_remark!=','NULL')
            ->get();
        // echo $this->db->last_Query();die;
        if ($query) {
            return $query->result_array();
        } else {
            return false;
        }
    }
    public function get_unhold_reason($data)
    {
        $query =  $this->db->select('shr.unhold_reason,shr.sample_reg_id,concat(ap.admin_fname," ",ap.admin_lname) as created_by,shr.created_on')
            ->join('admin_profile ap', 'ap.uidnr_admin = shr.done_by', 'left')
            ->from('sample_hold_remark shr')
            ->where('sample_reg_id', $data['sample_reg_id'])
            ->where('unhold_reason!= ','NULL')
            ->get();
        // echo $this->db->last_Query();die;
        if ($query) {
            return $query->result_array();
        } else {
            return false;
        }
    }
}
