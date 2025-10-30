<?php 
defined('BASEPATH') or exit('No direct script access allowed');

class Buyer_field_model extends CI_Model
{
	public function __construct()
    {
        parent::__construct();
    }

	// Insert single buyer field and return inserted ID
    public function insert_buyer_field($data)
    {
        $this->db->insert('buyer_fields', $data);
        return $this->db->insert_id();
    }

    // Insert a batch of custom fields
	public function insert_custom_fields_batch($custom_fields)
	{
		foreach ($custom_fields as &$field) {
			if (isset($field['custom_field_name']) && is_array($field['custom_field_name'])) {
				$field['custom_field_name'] = implode(', ', $field['custom_field_name']);
			}
		}

		if (!empty($custom_fields) && is_array($custom_fields)) {
			$this->db->insert_batch('buyer_custom_fields', $custom_fields);
			return ($this->db->affected_rows() > 0);
		}
		return false;
	}

	// fetch all record 
	// public function fetchData()
	// {
	// 	$this->db->select('
	// 		bf.buyer_field_id,
	// 		cc.customer_name,
	// 		bf.created_by,
	// 		bf.created_on
	// 	');
	// 	$this->db->from('buyer_fields bf');
	// 	$this->db->join('cust_customers cc', 'cc.customer_id = bf.buyer_id', 'left');
	// 	$this->db->order_by('bf.buyer_field_id', 'desc');

	// 	$query = $this->db->get();

	// 	return $query->num_rows() > 0 ? $query->result() : [];
	// }

	public function fetchData($limit = 50, $offset = 0, $search = '')
	{
		$this->db->select('
			bf.buyer_field_id,
			cc.customer_name,
			bf.created_by,
			bf.created_on,
			bf.status
		');
		$this->db->from('buyer_fields bf');
		$this->db->join('cust_customers cc', 'cc.customer_id = bf.buyer_id', 'left');

		if (!empty($search)) {
			$this->db->like('cc.customer_name', $search); // search by customer name
		}

		$this->db->order_by('bf.buyer_field_id', 'desc');
		$this->db->limit($limit, $offset);

		$query = $this->db->get();

		if (!$query) {
			log_message('error', 'DB error in fetchData: ' . $this->db->last_query());
			return [];
		}

		return $query->result_array();
	}


	public function countAllData($search = '')
	{
		$this->db->from('buyer_fields bf');
		$this->db->join('cust_customers cc', 'cc.customer_id = bf.buyer_id', 'left');

		if (!empty($search)) {
			$this->db->like('cc.customer_name', $search); 
		}

		return $this->db->count_all_results(); 
	}



	// by id
	public function get_buyer_field_by_id($buyer_field_id)
	{
    	return $this->db->get_where('buyer_fields', ['buyer_field_id' => $buyer_field_id])->row_array();
	}

	public function get_custom_fields($buyer_field_id)
    {
        return $this->db->get_where('buyer_custom_fields', ['buyer_field_id' => $buyer_field_id])->result_array(); //
    }

	public function selected_applicant_name($customer_id)
	{
		$this->db->select('customer_id as id, CONCAT(customer_name) as name, CONCAT(customer_name,address) as full_name');
		$this->db->from('cust_customers');
		$this->db->where('isactive', 'Active');
		$this->db->where('customer_id', $customer_id);
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->result_array();
		}
		return [];
	}

	public function get_all_applicants()
	{
		$this->db->select('customer_id as id, customer_name as name');
		$this->db->from('cust_customers');
		$this->db->where('isactive', 'Active');
		$query = $this->db->get();
		return $query->result_array();
	}

	public function update_buyer_field($buyer_field_id, $data)
    {
        return $this->db->update('buyer_fields', $data, ['buyer_field_id' => $buyer_field_id]);
    }

	// public function delete_custom_fields($buyer_field_id)
    // {
    //     return $this->db->delete('buyer_custom_fields', ['buyer_field_id' => $buyer_field_id]);
    // }
	public function update_custom_field($custom_id, $data)
	{
		return $this->db->update('buyer_custom_fields', $data, ['custom_field_id' => $custom_id]);
	}

	public function SoftDeleteupdate($buyer_field_id, $update_data)
	{
		return $this->db->where('buyer_field_id', $buyer_field_id)->update('buyer_fields', $update_data);

		// Debug query
	}

	public function check_buyer_exists($buyer_id)
	{
		$this->db->where('buyer_id', $buyer_id);
		$query = $this->db->get('buyer_fields');
		// 		echo $this->db->last_query(); die;
		// die();

		return $query->num_rows() > 0;
	}

	public function delete_single_custom_field($custom_field_id)
	{
		return $this->db->delete('buyer_custom_fields', ['custom_field_id' => $custom_field_id]);
	}



}
