<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Country_model extends MY_Model
{
  public function __construct()
  {
    parent::__construct();
    $this->db->trans_start();
  }

  public function __destruct()
  {
    $this->db->trans_complete();
  }

  public function get_country_list($limit = NULL, $start = NULL, $search = NULL, $sortby = NULL, $order = NULL, $where = NULL, $count = NULL){
    if ($sortby != NULL || $order != NULL) {
      $this->db->order_by($sortby, $order);
    } else {
      $this->db->order_by('msc.country_id', 'DESC');
    }

    if ($where) {
      $this->db->where($where);
    }

    if ($search != NULL && $search != 'NULL') {
      $search = trim($search);
      $this->db->group_start();
      $this->db->like('msc.country_code', $search);
      $this->db->or_like('msc.country_name', $search);
      $this->db->or_like('ap.admin_fname', $search);
      $this->db->or_like('msc.created_on', $search);
      $this->db->group_end();
    }

    $this->db->select('msc.country_id, msc.country_code, msc.country_name, msc.status, msc.created_on, ap.admin_fname,msc.test_country_flag, lab_location_flag, product_destination_flag');
    $this->db->from('mst_country as msc');
    $this->db->join('admin_profile as ap', 'ap.uidnr_admin = msc.created_by', 'left');
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

  public function get_user_logData($country_id){
    $this->db->select('CONCAT(ap.admin_fname," ", ap.admin_lname) as created_by, msc.created_on as date, CONCAT(app.admin_fname," ", app.admin_lname) updated_by, msc.updated_on');
    $this->db->from('mst_country as msc');
    $this->db->join('admin_profile as ap', 'ap.uidnr_admin = msc.created_by', 'left');
    $this->db->join('admin_profile as app', 'app.uidnr_admin = msc.updated_by', 'left');
    $this->db->where('msc.country_id', $country_id);
    $this->db->order_by('msc.country_id', 'DESC');
    $result = $this->db->get();
    if ($result->num_rows() > 0) {
      return $result->result();
    } else {
      return false;
    }
  }

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

  public function fetch_country(){
    $query = $this->db->select('msc.country_id, msc.country_name')
      ->from('mst_country as msc')
      ->get();
    if ($query->num_rows() > 0) {
      return $query->result();
    } else {
      return false;
    }
  }

  public function fetch_country_code(){
    $query = $this->db->select('msc.country_id, msc.country_code')
      ->from('mst_country as msc')
      ->get();
    if ($query->num_rows() > 0) {
      return $query->result();
    } else {
      return false;
    }
  }

  public function get_country_data($country_id){
    $this->db->select('msc.country_id, ap.admin_fname, msc.status, msc.country_code, msc.country_name, msc.created_on');
    $this->db->from('mst_country as msc');
    $this->db->join('admin_profile as ap', 'ap.uidnr_admin = msc.created_by', 'left');
    $this->db->where('msc.country_id', $country_id);
    $query = $this->db->get();
    if($query->num_rows() > 0){
      return $query->row();
    }
    else{
      return false;
    }
  }

  public function update_cont_status($country_id) {
    $query = $this->db->get_where('mst_country', $country_id);
    $row = $query->row();
    if ($row->status == '0')
        $post = array('status' => '1');
    else
        $post = array('status' => '0');
    $this->db->update('mst_country', $post, $country_id);
    if ($this->db->affected_rows() > 0)
        return $post;
    else
        return false;
  }

  // added by millan on 09-04-2021
  public function get_country_log($country_id){
    $query = $this->db->select('action_taken, created_on as taken_at, text,concat(admin_fname," ",admin_lname) as taken_by')
        ->join('admin_profile', 'user_log_history.created_by = admin_profile.uidnr_admin')
        ->where('source_module', 'Country')
        ->where('record_id', $country_id)
        ->order_by('id', 'desc')
        ->get('user_log_history');
    if ($query->num_rows() > 0) {
        return $query->result_array();
    }
    return false;
  }
}
