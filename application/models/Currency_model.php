<?php
defined('BASEPATH') or exit('No direct access allowed');

class Currency_model extends MY_Model{
    function __construct(){
        parent::__construct();
    }


    public function get_currency_list($limit = NULL, $start = NULL, $search = NULL, $sortby = NULL, $order = NULL, $where = NULL, $count = NULL){
        if ($sortby != NULL || $order != NULL) {
          $this->db->order_by($sortby, $order);
        } else {
          $this->db->order_by('msc.currency_id', 'DESC');
        }
    
        if ($where) {
          $this->db->where($where);
        }
    
        if ($search != NULL && $search != 'NULL') {
          $search = trim($search);
          $this->db->group_start();
          $this->db->like('msc.currency_code', $search);
          $this->db->or_like('msc.currency_name', $search);
          $this->db->or_like('msc.created_on', $search);
          $this->db->or_like('msc.currency_basic_unit', $search);
          $this->db->or_like('msc.currency_fractional_unit', $search);
          $this->db->or_like('ap.admin_fname', $search);
          $this->db->group_end();
        }
    
        $this->db->select('msc.currency_id, msc.currency_code, msc.currency_name, msc.exchange_rate, msc.currency_basic_unit, msc.currency_fractional_unit, msc.created_on, msc.status, ap.admin_fname');
        $this->db->from('mst_currency as msc');
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

    // added by millan
    public function fetch_currency_for_edit($data) {
        $query = $this->db->select('msc.*')
                ->from('mst_currency msc')
                ->where('msc.currency_id', $data)
                ->get();
        if ($query) {
            return $query->row();
        } else {
            return false;
        }
    }

    // added by millan
    public function update_currency_status($currency_id) {
        $query = $this->db->get_where('mst_currency', $currency_id);
        $row = $query->row();
        if ($row->status == '0')
            $post = array('status' => '1');
        else
            $post = array('status' => '0');
        $this->db->update('mst_currency', $post, $currency_id);
        if ($this->db->affected_rows() > 0)
            return $post;
        else
            return false;;
    }

    // added by millan
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

    // added by millan
    public function fetch_currency_name(){
        $query = $this->db->select('msc.currency_name, msc.currency_id')
            ->from('mst_currency msc')
            ->where('msc.status', '1')
            ->order_by('msc.currency_name', 'ASC')
            ->get();
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    // added by millan
    public function fetch_currency_code(){
        $query = $this->db->select('msc.currency_code')
            ->from('mst_currency msc')
            ->where('msc.status', '1')
            ->order_by('msc.currency_code', 'ASC')
            ->get();
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    // added by millan
    public function fetch_basic_unit(){
        $query = $this->db->select('msc.currency_basic_unit')
            ->from('mst_currency msc')
            ->where('msc.status', '1')
            ->order_by('msc.currency_basic_unit', 'ASC')
            ->get();
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    // added by millan
    public function fetch_fractional_unit(){
        $query = $this->db->select('msc.currency_fractional_unit')
            ->from('mst_currency msc')
            ->where('msc.status', '1')
            ->order_by('msc.currency_fractional_unit', 'ASC')
            ->get();
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    // added by millan on 09-04-2021
    public function get_currency_log($currency_id){
        $query = $this->db->select('action_taken, created_on as taken_at, text,concat(admin_fname," ",admin_lname) as taken_by')
            ->join('admin_profile', 'user_log_history.created_by = admin_profile.uidnr_admin')
            ->where('source_module', 'Currency')
            ->where('record_id', $currency_id)
            ->order_by('id', 'desc')
            ->get('user_log_history');
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }

    // added by millan on 13-04-2021
    public function fetch_currency_exchange_details($where) {
        $query = $this->db->select('msc.currency_name, ce.ex_rate, ce.ex_id')
                ->from('mst_currency msc')
                ->join('currency_exchage ce', 'ce.primary_curr_id = msc.currency_id', 'left')
                ->where($where)
                ->get();
        if ($query) {
            return $query->row_array();
        } else {
            return false;
        }
    }

    // added by millan on 13-04-2021
    public function fetch_crncy_ex_details($data) {
        $query = $this->db->select('ce.ex_curr_id, ce.primary_curr_id, ce.ex_id')
                ->from('mst_currency msc')
                ->join('currency_exchage ce', 'ce.primary_curr_id = msc.currency_id', 'left')
                ->where('ce.primary_curr_id', $data)
                ->get();
        if ($query) {
            return $query->row_array();
        } else {
            return false;
        }
    }
}
?>