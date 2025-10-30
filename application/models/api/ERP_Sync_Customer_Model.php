<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ERP_Sync_Customer_Model extends MY_Model
{
    public function check_customers_data($column_name, $value)
    {
        if (!empty($column_name) && !empty($value)) {
            $this->db->select('customer_id');
            $this->db->from('cust_customers');
            $this->db->where('LOWER(' . $column_name . ')', strtolower($value));
            $this->db->where('nav_customer_code IS NULL', NULL, FALSE);
            $query = $this->db->get();
            return ($query->num_rows() > 0) ? $query->result() : FALSE;
        } else {
            return NULL;
        }
    }

    public function check_not_updated_customers()
    {
        return $this->db->select('customer_id')->from('cust_customers')->where('nav_customer_code IS NULL', NULL, FALSE)->count_all_results();
    }
}
