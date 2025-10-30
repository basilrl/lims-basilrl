<?php

use Mpdf\Tag\Q;

defined('BASEPATH') or exit('No direct script access allowed');

class Website_users_model extends MY_Model
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




    public function get_website_users($limit = NULL, $start = NULL, $search = NULL, $sortby = NULL, $order = NULL, $where = NULL, $count = NULL)
    {

        if ($sortby != NULL || $order != NULL) {
            $this->db->order_by($sortby, $order);
        } else {
            $this->db->order_by('users.customer_login_id', 'DESC');
        }

        if ($where) {
            $this->db->where($where);
        }

        if ($search != NULL && $search != 'NULL') {
            $search = trim($search);
            $this->db->group_start();
            $this->db->like('contact.email', $search);
            $this->db->or_like('contact.contact_name', $search);
            $this->db->or_like('users.customer_type', $search);
            $this->db->or_like('cust.customer_name', $search);
            $this->db->or_like('users.customer_login_status', $search);
            $this->db->group_end();
        }


        $this->db->select('users.customer_login_id,contact.contact_id,contact.email as customer_login_username,contact.contact_name,users.customer_type,cust.customer_name,users.customer_login_status');
        $this->db->from('customer_login users');
        $this->db->join('contacts contact', 'contact.contact_id = users.cl_contact_id', 'left');
        $this->db->join('cust_customers cust', 'cust.customer_id = contact.contacts_customer_id', 'left');

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


    public function get_AutoList_website($col, $table, $search = NULL, $like, $where = NULL)
    {

        $this->db->select($col)
            ->from($table);
        if ($where != NULL) {
            $this->db->where($where);
        }
        if ($search != NULL) {
            $this->db->like($like, trim($search));
        }
        $this->db->order_by($like, 'asc');
        $this->db->limit(20);
        $result = $this->db->get();

        if ($result->num_rows() > 0) {
            return $result->result();
        } else {
            return false;
        }
    }


    public function get_website_user_data($id)
    {
        $this->db->select('users.customer_login_id,contact.contact_id,users.customer_login_username,contact.contact_name,users.customer_type,cust.customer_name,users.customer_login_status,contact.contacts_customer_id as customer_id');
        $this->db->from('customer_login users');
        $this->db->join('contacts contact', 'contact.contact_id = users.cl_contact_id', 'left');
        $this->db->join('cust_customers cust', 'cust.customer_id = contact.contacts_customer_id', 'left');
        $this->db->where('users.customer_login_id', $id);
        $result = $this->db->get();

        if ($result->num_rows() > 0) {
            return $result->row();
        } else {
            return false;
        }
    }

    public function delete_user($id)
    {
        $this->db->where('customer_login_id', $id);
        $result = $this->db->delete('customer_login');
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    public function get_log_data($id)
    {

        $where = array();
        $where['ul.source_module'] = 'Website_users';
        $where['ul.record_id'] = $id;

        $this->db->select('ul.action_taken,ul.created_on as taken_at,ul.text, CONCAT(ap.admin_fname," ",ap.admin_lname) as taken_by');
        $this->db->from('user_log_history ul');
        $this->db->join('admin_profile ap', 'ul.created_by = ap.uidnr_admin', 'left');
        $this->db->order_by('ul.id', 'DESC');
        $this->db->where($where);
        $result = $this->db->get();
        // echo $this->db->last_query();die;
        if ($result->num_rows() > 0) {
            return $result->result();
        } else {
            return false;
        }
    }
    public function fetch_controller()
    {
        $query = $this->db->distinct()->select('controller_name')->get('front_functions');
        if ($query->result())
            return $query->result();
        else
            return false;
    }

    public function fetch_functions($post)
    {
        $query = $this->db->distinct('function_name')->get_where('front_functions', $post);
        if ($query->result())
            return $query->result();
        else
            return false;
    }
    public function fetch_permission($post)
    {
        $data = array();
        $query = $this->db->select('function_id')->where($post)->get('front_set_permission');
        if ($query->row_array()) {
            $result = $query->row_array();
            $data = explode(',', $result['function_id']);
            return $data;
        } else
            return false;
    }

    public function save_permission($contact_id, $functionID)
    {
        $data = array('contact_id' => $contact_id, 'function_id' => $functionID);
        $prev_perm_id = $this->db->select('permission_id')->where('contact_id', $contact_id)->get('front_set_permission')->row_array();
        if ($prev_perm_id) {
            $update = $this->db->update('front_set_permission', $data, ['permission_id' => $prev_perm_id['permission_id']]);
            if ($update) return true;
        } else {
            $query = $this->db->insert('front_set_permission', $data);
            if ($query)  return true;
        }
        return false;
    }
}
