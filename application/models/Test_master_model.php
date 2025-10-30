<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Test_master_model extends MY_Model
{

	public function __construct()
	{
		parent::__construct();
	}

	public function get_test_list($limit = NULL, $start = NULL, $search = NULL, $sortby, $order, $where, $count = NULL)
	{
		$this->db->limit($limit, $start);
		if ($count == NULL) {
			if ($sortby != NULL || $order = NULL) {
				$this->db->order_by($sortby, $order);
			} else {
				$this->db->order_by('ts.test_id', 'DESC');
			}
		}
		if ($where) {
			$this->db->where($where);
		}

		if ($search != NULL) {
			$search = trim($search);
			$this->db->group_start();
			$this->db->like('ts.test_name', $search);
			// $this->db->or_like('ts.test_method', $search);
			// $this->db->or_like('div.division_name', $search);
			// $this->db->or_like('lab.lab_type_name', $search);
			// $this->db->or_like('ts.created_on', $search);
			// $this->db->or_like('admin.admin_fname', $search);
			// $this->db->or_like('ts.is_available_customerportal', $search);
			// $this->db->or_like('ts.method_type', $search);
			$this->db->group_end();
		}

		$result = $this->db->select("ts.*,lab.lab_type_name as lab_name,div.division_name as div_name,report.unit as report_unit, min_qty.unit as min_qty_unit, IF(ts.is_available_customerportal='0','NO','YES') as online_show,DATE_FORMAT(ts.created_on, '%d-%b-%Y') as created_on,admin.admin_fname as created_by,ts.method_type, tm.test_method_name as test_method")
			->from("tests as ts")
			->join("mst_lab_type as lab", "lab.lab_type_id=ts.test_lab_type_id", "left")
			->join("mst_divisions as div", "div.division_id=ts.test_division_id", "left")
			->join("units as report", "report.unit_id=ts.units", "left")
			->join("units as min_qty", "min_qty.unit_id=ts.minimum_quantity_units", "left")
			->join("admin_profile as admin", "admin.uidnr_admin=ts.created_by", "left")
			->join("mst_test_methods tm", "tm.test_method_id = ts.test_method_id", "left")
			->get();

		if ($count) {
			return $result->num_rows();
		} else {
			if ($result) {
				return $result->result();
			} else {
				return false;
			}
		}
	}

	//  insert tests
	public function insert_test($products, $filterdata, $sub_perameter, $price_listlog, $sub_contract)
	{

		$this->db->trans_begin();
		$this->db->insert('tests', $filterdata);
		$test_id = $this->db->insert_id();

		$sub_perameter['test_parameters_test_id'] = $price_listlog['pricelist_log_test_id'] = $test_id;
		// $this->db->insert('test_parameters', $sub_perameter); // Commented by CHANDAN --10-05-2022
		$this->db->insert('pricelist_log', $price_listlog);

		$data = array();
		$data['source_module'] = 'Test_master';
		$data['record_id'] = $test_id;
		$data['created_on'] = date("Y-m-d h:i:s");
		$data['created_by'] = $this->user;
		$data['action_taken'] = 'add_test';
		$data['text'] = 'Test added with name ' . $filterdata['test_name'];

		$this->insert_data('user_log_history', $data);

		foreach ($products as $value) {
			$prod['test_sample_type_sample_type_id'] = $value;
			$prod['test_sample_type_test_id'] = $test_id;
			$this->db->insert('test_sample_type', $prod);
		}

		if ($sub_contract && count($sub_contract) > 0) {
			$sub_contract['test_id'] = $test_id;
			$this->db->insert('test_sub_contract_details', $sub_contract);
		}

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			return false;
		} else {
			$this->db->trans_commit();
			return true;
		}
	}

	// insert test end


	// update test
	public function update_test($data, $products, $test_id, $sub_contract)
	{
		$this->db->trans_begin();
		$this->db->where('test_id ', $test_id)
			->update('tests', $data);

		$this->db->where('test_sample_type_test_id', $test_id)
			->delete('test_sample_type');

		foreach ($products as $key => $value) {
			$multi[$key] = ['test_sample_type_sample_type_id' => $value, 'test_sample_type_test_id' => $test_id];
		}

		$this->db->insert_batch('test_sample_type', $multi);

		if ($data['method_type'] == 'IHTM') {
			$this->db->where('test_id', $test_id);
			$this->db->delete('test_sub_contract_details');
		} else {
			if ($sub_contract && count($sub_contract) > 0) {
				$getSubContract = $this->db->select('*')->from('test_sub_contract_details')->where('test_id', $test_id)->get()->num_rows();
				$sub_contract['test_id'] = $test_id;
				if ($getSubContract > 0) {
					$this->db->where('test_id', $test_id);
					$this->db->update('test_sub_contract_details', $sub_contract);
				} else {
					$this->db->insert('test_sub_contract_details', $sub_contract);
				}
			}
		}

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			return false;
		} else {
			$this->db->trans_commit();
			return true;
		}
	}

	// update test end

	public function check_dupli($data, $test_id = NULL)
	{

		$this->db->select('test_name,test_method');
		$this->db->from('tests');
		$this->db->group_start();
		$this->db->where('LOWER(test_name)', strtolower($data['test_name']));
		$this->db->where('test_method_id', strtolower($data['test_method_id']));
		$this->db->group_end();
		if ($test_id != NULL) {
			$this->db->where_not_in('test_id', [$test_id]);
		}
		$result = $this->db->get();
		// echo $this->db->last_query();die;
		if ($result->num_rows() == "0") {
			return true;
		} else {
			return false;
		}
	}


	// get products by product ids array;

	public function get_products_of_test($product_ids)
	{
		$this->db->select('sample_type_id as id,sample_type_name as name');
		$this->db->where_in('sample_type_id', $product_ids);
		$products = $this->db->get('mst_sample_types');

		if ($products->num_rows() > 0) {
			return $products->result();
		} else {
			return false;
		}
	}



	public function getIDByName($select = "*", $id, $like = NULL, $where = NULL, $table)
	{
		$this->db->group_start();
		$this->db->like($where, trim($like));
		$this->db->group_end();
		$this->db->select($select);
		$this->db->from($table);
		$id = $this->db->get();
		if ($id->num_rows() > 0) {
			return $id->row();
		} else {
			return false;
		}
	}

	public function insert_import_test($data = NULL, $product = NULL)
	{
		$test = NULL;
		$this->db->trans_begin();
		foreach ($data as $key => $value) {
			$this->db->insert('tests', $value);
			$id = $this->db->insert_id();
			$this->db->where('test_sample_type_test_id', $id)
				->delete('test_sample_type');
			foreach ($product as $key => $value) {
				$test['test_sample_type_sample_type_id'] = $value;
				$test['test_sample_type_test_id'] = $id;
				$this->db->insert('test_sample_type', $test);
			}
		}


		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			return false;
		} else {
			$this->db->trans_commit();
			return true;
		}
	}


	public function get_price_lists_of_tests($test_id)
	{
		$this->db->select('distinct(mst_currency.currency_id),mst_currency.currency_code,mst_country.country_code as country_name,mst_country.country_code as country_code,if(pricelist.price is null,0,pricelist.price) as price');
		$this->db->from('mst_branches');
		$this->db->join("mst_currency", "mst_currency.currency_id=mst_branches.mst_branches_currency_id AND mst_branches.status='1'", "inner");
		$this->db->join("mst_country", "mst_country.country_id=mst_branches.mst_branches_country_id  AND mst_currency.status='1'", "inner");
		$this->db->join("pricelist", "pricelist.currency_id=mst_currency.currency_id and pricelist.pricelist_test_id ='" . $test_id . "' and pricelist.type='Test' ", "left");
		$this->db->group_by('mst_currency.currency_id');
		$result = $this->db->get();

		if ($result) {
			return $result->result();
		} else {
			return false;
		}
	}



	public function get_log_data($id)
	{
		$where = array();
		$where['ul.source_module'] = 'Test_master';
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

	// Added by CHANDAN --09-04-2022
	public function view_parameter($test_id)
	{
		$this->db->select('pa.test_parameters_id, pa.clouse, pa.test_parameters_name, pa.test_parameters_disp_name, pa.category, pa.priority_order, pa.result_component_type, pa.show_in_report, pa.mandatory, pa.field_type, pa.min_values, pa.max_values, pa.parameter_limit, pa.requirement, pa.created_on, units.unit, CONCAT(ap.admin_fname," ",ap.admin_lname) as created_by');
		$this->db->from('test_parameters pa');
		$this->db->join('units', 'units.unit_id = pa.test_parameters_unit', 'left');
		$this->db->join('admin_profile ap', 'pa.created_by = ap.uidnr_admin', 'left');
		$this->db->where(['pa.test_parameters_test_id' => $test_id, 'pa.is_delete' => 0]);
		$this->db->order_by('pa.test_parameters_id', 'DESC');
		$result = $this->db->get();
		//echo $this->db->last_query(); die;
		return ($result->num_rows() > 0) ? $result->result() : false;
	}

	public function export_all_tests($where, $search)
	{
		if ($where) {
			$this->db->where($where);
		}
		if ($search != NULL) {
			$search = trim($search);
			$this->db->group_start();
			$this->db->like('ts.test_name', $search, 'after');
			$this->db->group_end();
		}
		$result = $this->db->select("ts.*,lab.lab_type_name as lab_name,div.division_name as div_name,report.unit as report_unit, min_qty.unit as min_qty_unit, IF(ts.is_available_customerportal='0','NO','YES') as online_show,DATE_FORMAT(ts.created_on, '%d-%b-%Y') as created_on,admin.admin_fname as created_by,ts.method_type")
			->from("tests as ts")
			->join("mst_lab_type as lab", "lab.lab_type_id=ts.test_lab_type_id", "left")
			->join("mst_divisions as div", "div.division_id=ts.test_division_id", "left")
			->join("units as report", "report.unit_id=ts.units", "left")
			->join("units as min_qty", "min_qty.unit_id=ts.minimum_quantity_units", "left")
			->join("admin_profile as admin", "admin.uidnr_admin=ts.created_by", "left")
			->get();
		return ($result->num_rows() > 0) ? $result->result() : false;
	}

	// Added by Saurabh on 02-12-2024 to get test method
	public function get_test_method($key){
		$this->db->select('tm.test_method_id as id, tm.test_method_name as name, tm.test_method_name as full_name');
		($key != NULL) ? $this->db->where('tm.test_method_name',$key) : '';
		$query = $this->db->get('mst_test_methods tm');
		if($query->num_rows() > 0){
			return $query->result_array();
		}
		return [];
	}

	// Added by Saurabh on 02-12-2024 to get test method
	public function get_test_details($test_id){
		$this->db->select('test_id, test_name, test_division_id, test_service_type, div.division_name as div_name, test_lab_type_id, lab.lab_type_name as lab_name, minimum_quantity, minimum_quantity_units, min_qty.unit as min_qty_unit, units, report.unit as report_unit, test_turn_around_time, test_status, method_type, tm.test_method_id, tm.test_method_name, is_available_customerportal');
		$this->db->join("mst_lab_type as lab", "lab.lab_type_id=ts.test_lab_type_id", "left");
		$this->db->join("mst_divisions as div", "div.division_id=ts.test_division_id", "left");
		$this->db->join("units as report", "report.unit_id=ts.units", "left");
		$this->db->join("units as min_qty", "min_qty.unit_id=ts.minimum_quantity_units", "left");
		$this->db->join("mst_test_methods tm", "tm.test_method_id = ts.test_method_id", "left");
		$this->db->where('test_id', $test_id);
		$query = $this->db->get('tests ts');
		if($query->num_rows() > 0){
			return $query->result();
		}
	}
}
