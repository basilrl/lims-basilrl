<?php
defined('BASEPATH') or exit('No direct script access allowed');
class RegulationConfiguration_model extends MY_Model
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

  public function get_regulationconfig_list($limit = NULL, $start = NULL, $search = NULL, $sortby = NULL, $order = NULL, $where = NULL, $count = NULL)
  {
    if ($sortby != NULL || $order != NULL) {
      $this->db->order_by($sortby, $order);
    } else {
      $this->db->order_by('crc.reg_conf_id', 'DESC');
    }

    if ($where) {
      $this->db->where($where);
    }

    if ($search != NULL && $search != 'NULL') {
      $search = trim($search);
      $this->db->group_start();
      $this->db->like('cust.customer_name', $search);
      $this->db->or_like('cont.contact_name', $search);
      $this->db->or_like('ap.admin_fname', $search);
      $this->db->or_like('crc.created_date', $search);
      $this->db->group_end();
    }

    $this->db->select('crc.reg_conf_id, cust.customer_name, cont.contact_name, ap.admin_fname, crc.created_date, crc.status');
    $this->db->from('cps_regulations_configuration as crc');
    $this->db->join('cust_customers as cust', 'cust.customer_id = crc.customer_id', 'left');
    $this->db->join('contacts as cont', 'cont.contact_id = crc.contact_id', 'left');
    $this->db->join('mst_country as msc', 'msc.country_id = crc.country_id', 'left');
    $this->db->join('mst_divisions as msd', 'msd.division_id = crc.division_id', 'left');
    $this->db->join('notified_body as nob', 'nob.notified_body_id = crc.notified_body_id', 'left');
    $this->db->join('admin_profile as ap', 'ap.uidnr_admin = crc.created_by', 'left');
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

  public function get_user_logData($reg_conf_id)
  {
    $this->db->select('CONCAT(ap.admin_fname," ", ap.admin_lname) as created_by,crc.created_date as date');
    $this->db->from('cps_regulations_configuration as crc');
    $this->db->join('admin_profile as ap', 'ap.uidnr_admin = crc.created_by', 'left');
    $this->db->where('crc.reg_conf_id', $reg_conf_id);
    $this->db->order_by('crc.reg_conf_id', 'DESC');
    $result = $this->db->get();

    if ($result->num_rows() > 0) {
      return $result->result();
    } else {
      return false;
    }
  }

  public function fetch_cust_name()
  {
    $query = $this->db->select('cust.customer_name, cust.customer_id')
      ->from('cust_customers as cust')
      ->get();
    if ($query->num_rows() > 0) {
      return $query->result();
    } else {
      return false;
    }
  }

  public function fetch_contact_name()
  {
    $query = $this->db->select('contact.contact_id, contact.contact_name')
      ->from('contacts as contact')
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

  public function fetch_country()
  {
    $query = $this->db->select('msc.country_id, msc.country_name')
      ->from('mst_country as msc')
      ->get();
    if ($query->num_rows() > 0) {
      return $query->result();
    } else {
      return false;
    }
  }

  public function fetch_notify_bodies()
  {
    $query = $this->db->select('nob.notified_body_id, nob.notified_body_name')
      ->from('notified_body as nob')
      ->get();
    if ($query->num_rows() > 0) {
      return $query->result();
    } else {
      return false;
    }
  }

  public function fetch_sample_categories()
  {
    $query = $this->db->select('msd.division_id, msd.division_name')
      ->from('mst_divisions as msd')
      ->get();
    if ($query->num_rows() > 0) {
      return $query->result();
    } else {
      return false;
    }
  }

  public function extract_cont_name($fetch_type)
  {
    $query = $this->db->select('cont.contact_name, cont.contact_id')
      ->from('contacts as cont')
      ->where('cont.contacts_customer_id', $fetch_type)
      ->get();
    if ($query->num_rows() > 0) {
      return $query->result();
    } else {
      return false;
    }
  }

  public function get_regulationconfig_data($reg_conf_id){
    $this->db->select('crc.reg_conf_id, crc.created_by, crc.status, crc.customer_id, crc.division_id, crc.country_id, crc.notified_body_id, crc.contact_id');
    $this->db->from('cps_regulations_configuration as crc');
    $this->db->join('cust_customers as cust', 'cust.customer_id = crc.customer_id', 'left');
    $this->db->join('contacts as cont', 'cont.contact_id = crc.contact_id', 'left');
    $this->db->join('mst_country as msc', 'msc.country_id = crc.country_id', 'left');
    $this->db->join('mst_divisions as msd', 'msd.division_id = crc.division_id', 'left');
    $this->db->join('notified_body as nob', 'nob.notified_body_id = crc.notified_body_id', 'left');
    $this->db->join('admin_profile as ap', 'ap.uidnr_admin = crc.created_by', 'left');
    $this->db->where('crc.reg_conf_id', $reg_conf_id);
    $query = $this->db->get();
    if($query->num_rows() > 0){
      return $query->row();
    }
    else{
      return false;
    }
  }

  public function update_regconfig_status($reg_conf_id) {
    $query = $this->db->get_where('cps_regulations_configuration', $reg_conf_id);
    $row = $query->row();
    if ($row->status == '0')
        $post = array('status' => '1');
    else
        $post = array('status' => '0');
    $this->db->update('cps_regulations_configuration', $post, $reg_conf_id);
    if ($this->db->affected_rows() > 0)
        return $post;
    else
        return false;;
}


public function get_log_data($id)
{

  $where = array();
  $where['ul.source_module'] = 'RegulatiConfiguration';
  $where['ul.record_id'] = $id;

  $this->db->select('ul.action_taken,ul.created_on as taken_at,ul.text, CONCAT(ap.admin_fname," ",ap.admin_lname) as taken_by');
  $this->db->from('user_log_history ul');
  $this->db->join('admin_profile ap','ul.created_by = ap.uidnr_admin','left');
  $this->db->order_by('ul.id','DESC');
  $this->db->where($where);
  $result = $this->db->get();
  // echo $this->db->last_query();die;
  if($result->num_rows()>0){
    return $result->result();
  }
  else{
    return false;
  }


}
}
