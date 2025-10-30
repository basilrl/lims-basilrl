<?php
defined('BASEPATH') or exit('No direct access allowed');

class Branch_model extends MY_Model
{
    function __construct()
    {
        parent::__construct();
    }

    public function get_branch_list($limit = NULL, $start = NULL, $search = NULL, $sortby, $order, $where, $count = NULL)
    {
        if ($sortby != NULL || $order != NULL) {
            $this->db->order_by($sortby, $order);
        } else {
            $this->db->order_by('msb.branch_id', 'desc');
        }
        if ($where) {
            $this->db->where($where);
        }
        if ($search != NULL && $search != 'NULL') {
            $search = trim($search);
            $this->db->group_start();
            $this->db->or_like('msb.branch_name', $search);
            $this->db->like('msb.branch_code', $search);
            $this->db->or_like('msc.country_name', $search);
            $this->db->or_like('msp.province_name', $search);
            $this->db->or_like('msb.branch_telephone', $search);
            $this->db->or_like('ap.admin_fname', $search);
            $this->db->or_like('msb.created_on', $search);
            $this->db->group_end();
        }
        $query = $this->db->select('msb.branch_id, msb.branch_code, msb.branch_name, msc.country_name, msp.province_name, msb.branch_telephone, ap.admin_fname, msb.status, msb.created_on')
            ->from('mst_branches as msb')
            ->join('admin_profile as ap', 'ap.uidnr_admin = msb.created_by', 'left')
            ->join('mst_provinces msp', 'msp.province_id = msb.mst_state_id', 'left')
            ->join('mst_country msc', 'msc.country_id = msb.mst_branches_country_id', 'left')
            ->limit($limit, $start)
            ->get();
        if ($count) {
            return $query->num_rows();
        } else {
            if ($query->num_rows() > 0)
                return $query->result();
            else
                return false;
        }
    }

    /* added by millan */
    public function fetch_branch_code()
    {
        $query = $this->db->select('msb.branch_code')
            ->from('mst_branches msb')
            ->order_by('msb.branch_code', 'ASC')
            ->get();
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    /* added by millan */
    public function fetch_branch_name()
    {
        $query = $this->db->select('msb.branch_id, msb.branch_name')
            ->from('mst_branches msb')
            ->order_by('msb.branch_name', 'ASC')
            ->get();
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    /* added by millan */
    public function fetch_created_person()
    {
        $query = $this->db->select('CONCAT(ap.admin_fname, " ", ap.admin_lname) as created_by, ap.uidnr_admin')
            ->from('admin_profile ap')
            ->order_by('ap.admin_fname', 'asc')
            ->get();
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    /* added by millan */
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

    /* added by millan */
    public function fetch_state()
    {
        $query = $this->db->select('msp.province_id, msp.province_name')
            ->from('mst_provinces msp')
            ->order_by('msp.province_name', 'ASC')
            ->get();
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    /* added by millan*/
    public function update_branch_status($branch_id)
    {
        $query = $this->db->get_where('mst_branches', $branch_id);
        $row = $query->row();
        if ($row->status == '0')
            $post = array('status' => '1');
        else
            $post = array('status' => '0');
        $this->db->update('mst_branches', $post, $branch_id);
        if ($this->db->affected_rows() > 0)
            return $post;
        else
            return false;;
    }

    /* added by millan*/
    public function get_branch_log($branch_id)
    {
        $query = $this->db->select('action_taken, created_on as taken_at, text,concat(admin_fname," ",admin_lname) as taken_by')
            ->join('admin_profile', 'user_log_history.created_by = admin_profile.uidnr_admin')
            ->where('source_module', 'Branch')
            ->where('record_id', $branch_id)
            ->order_by('id', 'desc')
            ->get(' user_log_history');
        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return false;
        }
    }

    /* added by millan */
    public function fetch_branch_for_edit($data)
    {
        $query = $this->db->select('msb.*')
            ->from('mst_branches msb')
            ->get();
        if ($query) {
            return $query->row();
        } else {
            return false;
        }
    }
}
