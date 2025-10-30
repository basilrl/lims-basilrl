<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Invoice_import_model extends MY_Model
{

    public function __construct()
    {
        parent::__construct();
    }


    public  function listing($per_page, $page = 0, $search, $where, $count = NULL)
    {
        if ($per_page != NULL || $page != NULL) {
            $this->db->limit($per_page, $page);
        }
        $this->db->order_by('payment_due.id', 'ASC');
        if (!empty($where)) {
            $this->db->group_start();
            $this->db->where($where);
            $this->db->group_end();
        }
        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('payment_due.customer_id', $search);
            $this->db->or_like('payment_due.customer_name_excel', $search);
            $this->db->group_end();
        }
        $this->db->select('payment_due.*,CONCAT(admin_profile.admin_fname, " " ,admin_profile.admin_lname ) as created_by');
        $this->db->from('payment_due');
        $this->db->join('admin_profile','admin_profile.uidnr_admin=payment_due.created_by','left');
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
    public function invoice_details_fetch($where)
    {
        $this->db->select('invoice_due_details.*');
        $this->db->from('invoice_due_details');
        $this->db->join('payment_due','payment_due.customer_id=invoice_due_details.customer_code','left');
        $this->db->where($where);
        $result = $this->db->get();
        if ($result->num_rows() > 0) {
            return $result->result();
        } else {
            return false;
        }
    }

    public function customer_contacts_email($where)
    {
        $this->db->select('contacts.email');
        $this->db->from('payment_due');
        $this->db->join('cust_customers','LOWER(payment_due.customer_name_excel)=LOWER(cust_customers.customer_name)','left');
        $this->db->join('contacts','cust_customers.customer_id=contacts.contacts_customer_id','left');
        $this->db->where($where);
        $result = $this->db->get();
        if ($result->num_rows() > 0) {
            return $result->result();
        } else {
            return false;
        }
    }
    public function invoice_insert($table,$data=array())
    {
        $result = 	$this->db->insert($table,$data);
		if($result){
			return true;
		}
		else{
			return false;
		}
    }
    public function log($table,$data=array())
    {
        $result = 	$this->db->insert($table,$data);
		if($result){
			return true;
		}
		else{
			return false;
		}
    }

}
