<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Webinar_model extends MY_Model
{

    public function __construct()
    {
        parent::__construct();
    }


    public  function get_list($per_page, $page = 0, $search, $where, $count = NULL)
    {
        if ($per_page != NULL || $page != NULL) {
            $this->db->limit($per_page, $page);
        }
        $this->db->order_by('webinar.id', 'DESC');
        if (!empty($where)) {
            $this->db->group_start();
            $this->db->where($where);
            $this->db->group_end();
        }
        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('webinar.host_name', $search);
            $this->db->or_like('webinar.title', $search);
            $this->db->or_like('webinar.profile', $search);
            $this->db->or_like('webinar.desc', $search);
            $this->db->or_like('CONCAT(admin_profile.admin_fname ,"", admin_profile.admin_lname)', $search);
            $this->db->group_end();
        }
        $this->db->select(' webinar.*, CONCAT(admin_profile.admin_fname ," ", admin_profile.admin_lname) as created_by');
        $this->db->from('webinar');
        $this->db->join('admin_profile','admin_profile.uidnr_admin=webinar.created_by','left');
        $result = $this->db->get();
        if ($count == '1') {
            return $result->num_rows();
        } else {
            if ($result->num_rows() > 0) {
                return $result->result();
            } else {
                return false;
            }
        }
    }
    
}
