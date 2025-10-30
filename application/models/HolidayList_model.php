<?php

class HolidayList_model extends MY_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function get_holiday_list($limit = NULL, $start = NULL, $search = NULL, $sortby = NULL, $order = NULL, $where = NULL, $count = NULL){
        if ($sortby != NULL || $order != NULL) {
          $this->db->order_by($sortby, $order);
        } else {
          $this->db->order_by('msp.holiday_id', 'DESC');
        }
    
        if ($where) {
            $this->db->where($where);
        }
    
        if ($search != NULL && $search != 'NULL') {
          $search = trim($search);
          $this->db->group_start();
          $this->db->like('msp.holiday_reason', $search);
          $this->db->or_like('msp.holiday_date', $search);
          $this->db->or_like('ap.admin_fname', $search);
          $this->db->or_like('msp.created_on', $search);
          $this->db->group_end();
        }
    
        $this->db->select('msp.holiday_id, msp.holiday_date, msp.holiday_reason, msp.created_on, ap.admin_fname');
        $this->db->from('mst_holidays as msp');
        $this->db->join('admin_profile as ap', 'ap.uidnr_admin = msp.created_by', 'left');
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
    
    public function fetch_holiday_for_edit($holiday_id){
        $this->db->select('holiday_date, holiday_reason');
        $this->db->from('mst_holidays');
        $this->db->where('holiday_id', $holiday_id);
        $query = $this->db->get();
        if($query->num_rows() > 0){
            return $query->row();
        }
        else {
            return false;
        }
    }

    public function delete_holiday($holiday_id){
        $this->db->where($holiday_id);
        $query = $this->db->delete('mst_holidays');
        return $query;
    }

    // added by millan on 17-03-2021
    public function get_user_logData($holiday_id){
        $this->db->select('CONCAT(ap.admin_fname," ", ap.admin_lname) as created_by, msp.created_on as date, CONCAT(app.admin_fname," ", app.admin_lname) updated_by, msp.updated_on');
        $this->db->from('mst_holidays as msp');
        $this->db->join('admin_profile as ap', 'ap.uidnr_admin = msp.created_by', 'left');
        $this->db->join('admin_profile as app', 'app.uidnr_admin = msp.updated_by', 'left');
        $this->db->where('msp.holiday_id', $holiday_id);
        $this->db->order_by('msp.holiday_id', 'DESC');
        $result = $this->db->get();
        if ($result->num_rows() > 0) {
          return $result->result();
        } else {
          return false;
        }
    }

    // added by millan on 17-03-2021
    public function fetch_created_person(){
        $query = $this->db->select('CONCAT(ap.admin_fname, " ", ap.admin_lname) as created_by, ap.admin_fname, ap.admin_lname,  ap.uidnr_admin')
          ->from('admin_profile as ap')
          ->get();
        if ($query->num_rows() > 0) {
          return $query->result();
        } else {
          return false;
        }
    }

    // added by millan
    public function get_holiday_log($holiday_id){
      $query = $this->db->select('action_taken, created_on as taken_at, text,concat(admin_fname," ",admin_lname) as taken_by')
          ->join('admin_profile', 'user_log_history.created_by = admin_profile.uidnr_admin')
          ->where('source_module', 'HolidayList')
          ->where('record_id', $holiday_id)
          ->order_by('id', 'desc')
          ->get('user_log_history');
      if ($query->num_rows() > 0) {
          return $query->result_array();
      }
      return false;
  }
}
