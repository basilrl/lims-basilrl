<?php
defined('BASEPATH') or exit('No direct script access allowed');
class State_model extends MY_Model
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

  public function get_state_list($limit = NULL, $start = NULL, $search = NULL, $sortby = NULL, $order = NULL, $where = NULL, $count = NULL)
  {
    if ($sortby != NULL || $order != NULL) {
      $this->db->order_by($sortby, $order);
    } else {
      $this->db->order_by('msp.province_id', 'DESC');
    }

    if ($where) {
      $this->db->where($where);
    }

    if ($search != NULL && $search != 'NULL') {
      $search = trim($search);
      $this->db->group_start();
      $this->db->like('msp.province_name', $search);
      $this->db->or_like('msc.country_name', $search);
      $this->db->or_like('ap.admin_fname', $search);
      $this->db->or_like('msp.created_on', $search);
      $this->db->group_end();
    }

    $this->db->select('msp.province_id, msp.province_name, msp.state_code, msp.status, msp.created_on, ap.admin_fname as created_by, msc.country_name, msp.updated_on, msp.updated_by');
    $this->db->from('mst_provinces as msp');
    $this->db->join('mst_country as msc', 'msc.country_id = msp.mst_provinces_country_id', 'left');
    $this->db->join('admin_profile as ap', 'ap.uidnr_admin = msp.created_by', 'left');
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

  public function get_user_logData($province_id)
  {
    $this->db->select('CONCAT(ap.admin_fname," ", ap.admin_lname) as created_by, msp.created_on, msp.updated_on, msp.updated_by');
    $this->db->from('mst_provinces as msp');
    $this->db->join('admin_profile as ap', 'ap.uidnr_admin = msp.created_by', 'left');
    $this->db->where('msp.province_id', $province_id);
    $this->db->order_by('msp.province_id', 'DESC');
    $result = $this->db->get();
    if ($result->num_rows() > 0) {
      return $result->result();
    } else {
      return false;
    }
  }

  public function fetch_country_name()
  {
    $query = $this->db->select('msc.country_id, msc.country_name')
      ->from('mst_country as msc')
      ->order_by('msc.country_name', 'ASC')
      ->get();
    if ($query->num_rows() > 0) {
      return $query->result();
    } else {
      return false;
    }
  }

  public function fetch_state_name()
  {
    $query = $this->db->select('msp.province_id, msp.province_name')
      ->from('mst_provinces as msp')
      ->order_by('msp.province_name', 'ASC')
      ->get();
    if ($query->num_rows() > 0) {
      return $query->result();
    } else {
      return false;
    }
  }

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

  public function get_state_data($province_id)
  {
    $this->db->select('msp.*');
    $this->db->from('mst_provinces as msp');
    $this->db->where('msp.province_id', $province_id);
    $query = $this->db->get();
    if ($query->num_rows() > 0) {
      return $query->row();
    } else {
      return false;
    }
  }

  public function update_state_status($province_id)
  {
    $query = $this->db->get_where('mst_provinces', $province_id);
    $row = $query->row();
    if ($row->status == '0')
      $post = array('status' => '1');
    else
      $post = array('status' => '0');
    $this->db->update('mst_provinces', $post, $province_id);
    if ($this->db->affected_rows() > 0)
      return $post;
    else
      return false;;
  }

  // added by millan on 09-04-2021
  public function get_state_log($province_id)
  {
    $query = $this->db->select('action_taken, created_on as taken_at, text,concat(admin_fname," ",admin_lname) as taken_by')
      ->join('admin_profile', 'user_log_history.created_by = admin_profile.uidnr_admin')
      ->where('source_module', 'State')
      ->where('record_id', $province_id)
      ->order_by('id', 'desc')
      ->get('user_log_history');
    if ($query->num_rows() > 0) {
      return $query->result_array();
    }
    return false;
  }
}
