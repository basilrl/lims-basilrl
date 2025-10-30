<?php

class QuoteTrf extends MY_Model
{

	function __construct()
	{
		parent::__construct();
	}

	public function get_applicant_name()
	{
		$this->db->select('customer_id, CONCAT(customer_name,address) as customer_name')
			->from('cust_customers')
			->where('isactive', 'Active')
			->order_by('customer_name', 'asc');
		if (exist_val('Listing/branch_wise', $this->session->userdata('permission'))) {
			$checkUser = $this->session->userdata('user_data');
			$this->db->where('cust_customers.mst_branch_id', $checkUser->branch_id); //branch_id
		}
		$query = $this->db->get();

		if ($query->num_rows() > 0) {
			return $query->result_array();
		}
		return [];
	}

	public function get_sales_person($key)
	{
		$this->db->select('ap.uidnr_admin as id, concat(ap.admin_fname," ",ap.admin_lname) as name, concat(ap.admin_fname," ",ap.admin_lname) as full_name');
		$this->db->join('admin_users au', 'au.uidnr_admin = ap.uidnr_admin');
		$this->db->join('admin_role', 'au.id_admin_role = admin_role.id_admin_role');
		if ($key != NULL) {
			$this->db->group_start();
			($key != NULL) ? $this->db->like('admin_fname', $key) : '';
			($key != NULL) ? $this->db->or_like('admin_lname', $key) : '';
			$this->db->group_end();
		}
		$this->db->where('sales_person', '1');
		$this->db->where('au.admin_active', '1');
		$this->db->limit(30);
		$query = $this->db->get('admin_profile ap');
		if ($query->num_rows() > 0) {
			return $query->result_array();
		}
		return [];
	}

	public function get_agent_name()
	{
		$this->db->select('customer_id, customer_name')
			->from('cust_customers')
			->where('customer_type', 'Agent')
			->where('isactive', 'Active');
		if (exist_val('Listing/branch_wise', $this->session->userdata('permission'))) {
			$checkUser = $this->session->userdata('user_data');
			$this->db->where('cust_customers.mst_branch_id', $checkUser->branch_id); //branch_id
		}
		$query = $this->db->get();

		if ($query->num_rows() > 0) {
			return $query->result_array();
		}
		return [];
	}

	public function get_country()
	{
		$query = $this->db->select('country_id,country_name')
			->from('mst_country')
			->where('status', '1')
			->get();

		if ($query->num_rows() > 0) {
			return $query->result_array();
		}
		return [];
	}

	public function get_currency()
	{
		$query = $this->db->select('currency_id,currency_name')
			->from('mst_currency')
			->where('status', '1')
			->get();

		if ($query->num_rows() > 0) {
			return $query->result_array();
		}
		return [];
	}

	public function get_third_party()
	{
		$this->db->select('customer_id, customer_name')
			->from('cust_customers')
			->where('customer_type', 'Thirdparty')
			->where('isactive', 'Active');
		if (exist_val('Listing/branch_wise', $this->session->userdata('permission'))) {
			$checkUser = $this->session->userdata('user_data');
			$this->db->where('cust_customers.mst_branch_id', $checkUser->branch_id); //branch_id
		}
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->result_array();
		}
		return [];
	}

	public function quote_ref_no_store($post)
	{
		$this->db->select('quote_id,reference_no,quotes_currency_id');
		// $this->db->where('quote_status', 'Cps Approved');
		// $this->db->or_where('quote_status', 'Approved'); //Chnaged by saurabh on 05-05-2022
		$this->db->or_where('quote_status', 'Client Approved');
		$this->db->where_in('quotes_customer_id', $post['id']);
		$result = $this->db->get('quotes');
		if ($result->num_rows() > 0) {
			return $result->result();
		} else {
			return false;
		}
	}
	public function product_store($id)
	{
		$this->db->select('sample_type_id,sample_type_name,wk.work_id,wk.product_type');
		$this->db->join('works wk', 'wk.works_sample_type_id=mst_sample_types.sample_type_id');
		$this->db->where_in('work_job_type_id', $id['id']);
		$this->db->order_by('lower(sample_type_name)', 'ASC');
		$this->db->group_by('sample_type_id');
		$result = $this->db->get('mst_sample_types');
		// echo $this->db->last_query(); die;
		if ($result->num_rows() > 0) {
			return $result->result();
		} else {
			return false;
		}
	}
	public function tests_store_old($post)
	{
		/**
		 * Updated by Saurabh on 10-05-2022
		 */
		$protocol = [];
		$package = [];
		$test = [];
		$this->db->select('GROUP_CONCAT(work_id) as work_ids, GROUP_CONCAT(DISTINCT(product_type)) as product_type, quote_id');
		$this->db->where_in('wk.work_job_type_id', $post['quote_id']);
		$this->db->where('wk.works_sample_type_id', $post['product_id']);
		$this->db->join('quotes','wk.work_job_type_id = quote_id');
		$res = $this->db->get('works as wk');
		$row = $res->row_array();
		$product_type_array = explode(',',$row['product_type']);
		if (in_array('Protocol', $product_type_array)) {
			$work_ids_data = $this->db->select('GROUP_CONCAT(work_id) as work_ids')->where_in('work_job_type_id',$post['quote_id'])->where('product_type',"Protocol")->get('works')->row_array();
			$this->db->select("ts.test_id,ts.test_name, w_t.work_id, tm.test_method_name, tm.test_method_id, product_type, quote_id");
			$this->db->join('work_analysis_test_parameters w_t', 'w_t.work_id=wk.work_id');
			$this->db->join('protocols proto', 'proto.protocol_id=w_t.work_analysis_test_id');
			$this->db->join('protocol_tests tp', 'tp.protocol_id=proto.protocol_id');
			$this->db->join('tests ts', 'ts.test_id=tp.protocol_test_id');
			$this->db->join('mst_test_methods tm','tm.test_method_id = ts.test_method_id');
			$this->db->join('quotes','wk.work_job_type_id = quote_id');
			$this->db->where('wk.works_sample_type_id', $post['product_id']);
			$this->db->where_in('wk.work_id', explode(',', $work_ids_data['work_ids']));
			$this->db->where_in('wk.work_job_type_id', $post['quote_id']);
			$this->db->group_by('ts.test_id');
			$result = $this->db->get('works wk');
			// echo $this->db->last_query(); 
			if ($result->num_rows() == 0) {
				$sql = "Select  ts.test_id, ts.test_name, tm.test_method_name, tm.test_method_id, wt.work_id, product_type, quote_id
                            FROM works wk
                            INNER JOIN works_analysis_test wt ON wk.work_id=wt.work_id
                            INNER JOIN tests ts on ts.test_id=wt.work_test_id
							INNER JOIN mst_test_methods tm on tm.test_method_id = ts.test_method_id
							INNER JOIN quotes on work_job_type_id = quote_id
                            LEFT JOIN work_analysis_test_parameters wtp ON wtp.work_analysis_test_id =wt.work_test_id AND wtp.work_id=wk.work_id
                            LEFT JOIN protocols pc ON pc.protocol_id=wk.product_type_id
                            WHERE work_job_type = 'Quote' AND wk.product_type='Protocol' AND work_job_type_id=" . $post['quote_id'][0];
				$result = $this->db->query($sql);
				// echo $this->db->last_query(); die;
				$protocol = $result->result_array();
				// echo "<pre>"; print_r($protocol);
			}

		} 
		if (in_array('Package', $product_type_array)) {
			$work_ids_data = $this->db->select('GROUP_CONCAT(work_id) as work_ids')->where_in('work_job_type_id',$post['quote_id'])->where('product_type',"Package")->get('works')->row_array();
			$this->db->select("ts.test_id,ts.test_name, w_t.work_id, tm.test_method_name, tm.test_method_id, product_type, quote_id");
			$this->db->join('work_analysis_test_parameters w_t', 'w_t.work_id=wk.work_id');
			$this->db->join('packages ps', 'ps.package_id=w_t.work_analysis_test_id');
			$this->db->join('test_packages tp', 'tp.test_package_packages_id=ps.package_id');
			$this->db->join('tests ts', 'ts.test_id=tp.test_package_test_id');
			$this->db->join('mst_test_methods tm','tm.test_method_id = ts.test_method_id');
			$this->db->where('wk.works_sample_type_id', $post['product_id']);
			$this->db->where_in('wk.work_id', explode(',', $work_ids_data['work_ids']));
			$this->db->join('quotes','wk.work_job_type_id = quote_id');
			$this->db->where_in('wk.work_job_type_id', $post['quote_id']);
			$this->db->group_by('ts.test_id');
			$result = $this->db->get('works wk');
			
			if ($result->num_rows() == 0) {
				$sql = "Select  ts.test_id, ts.test_name, tm.test_method_name, tm.test_method_id, wt.work_id, product_type, quote_id
                            FROM works wk
                            INNER JOIN works_analysis_test wt ON wk.work_id=wt.work_id
                            INNER JOIN tests ts on ts.test_id=wt.work_test_id
							INNER JOIN mst_test_methods tm on tm.test_method_id = ts.test_method_id
                            LEFT JOIN work_analysis_test_parameters wtp ON wtp.work_analysis_test_id =wt.work_test_id AND wtp.work_id=wk.work_id
							INNER JOIN quotes on work_job_type_id = quote_id
                            LEFT JOIN packages pk ON pk.package_id=wk.product_type_id
                            WHERE work_job_type = 'Quote' AND wk.product_type='Package' AND work_job_type_id='{$post['quote_id']}'";
				$result = $this->db->query($sql);
				$package = $result->result_array();
				// echo "<pre>"; print_r($package);
			}
		} 
		if (in_array('Test', $product_type_array)){
			$work_ids_data = $this->db->select('GROUP_CONCAT(work_id) as work_ids')->where_in('work_job_type_id',$post['quote_id'])->where('product_type',"Test")->get('works')->row_array();
			$this->db->select("ts.test_id,ts.test_name, w_t.work_id, tm.test_method_name, tm.test_method_id, product_type, quote_id");
			$this->db->join('works_analysis_test w_t', 'w_t.work_id=wk.work_id');
			$this->db->join('tests ts', 'ts.test_id=w_t.work_test_id');
			$this->db->join('mst_test_methods tm','tm.test_method_id = ts.test_method_id');
			$this->db->join('quotes','wk.work_job_type_id = quote_id');
			$this->db->where('wk.works_sample_type_id', $post['product_id']);
			$this->db->where_in('wk.work_id', explode(',', $work_ids_data['work_ids']));
			$this->db->where_in('wk.work_job_type_id', $post['quote_id']);
			$result = $this->db->get('works wk');
			echo $this->db->last_query();
			$test = $result->result_array();
			// echo "<pre>"; print_r($test);
		}
		
		die;
		// if ($result->num_rows() > 0) {
		// 	return $result->result();
		// } else {
		// 	return false;
		// }
	}

	public function tests_store($post){
		$query = $this->db->select('GROUP_CONCAT(product_type) as product_type')->where('works_sample_type_id',$post['product_id'])->where_in('work_job_type_id',$post['quote_id'])->get('works');
		if($query->num_rows() > 0){
			$result = $query->row_array();
			$product_type_array = explode(",",$result['product_type']);
			$test_result['test_data'] = [];
			$test_result['package_data'] = [];
			$test_result['protocol_data'] = [];
			if(in_array('Test',$product_type_array)){
				$this->db->select('works.work_id, test_id, test_name, methods.test_method_name, methods.test_method_id, product_type, work_job_type_id as quote_id, applicable_charge as total_cost, product_type_id');
				$this->db->join('works_analysis_test wat','wat.work_id = works.work_id');
				$this->db->join('tests','work_test_id = test_id');
				$this->db->join('mst_test_methods methods','tests.test_method_id = methods.test_method_id');
				$this->db->where('product_type','Test');
				$this->db->where('works_sample_type_id',$post['product_id']);
				$this->db->where_in('work_job_type_id',$post['quote_id']);
				$test_query = $this->db->get('works');
				$test_result['test_data'] = $test_query->result_array();
			}
			if(in_array('Package',$product_type_array)){
				$this->db->select('works.work_id, test_id, test_name, methods.test_method_name, methods.test_method_id, product_type, work_job_type_id as quote_id, works.total_cost, product_type_id');
				$this->db->join('works_analysis_test wat','wat.work_id = works.work_id');
				$this->db->join('tests','work_test_id = test_id');
				$this->db->join('mst_test_methods methods','tests.test_method_id = methods.test_method_id');
				$this->db->where('product_type','Package');
				$this->db->where('works_sample_type_id',$post['product_id']);
				$this->db->where_in('work_job_type_id',$post['quote_id']);
				$test_query = $this->db->get('works');
				$test_result['package_data'] = $test_query->result_array();
			}
			if(in_array('Protocol',$product_type_array)){
				$this->db->select('works.work_id, test_id, test_name, methods.test_method_name, methods.test_method_id, product_type, work_job_type_id as quote_id, works.total_cost, product_type_id');
				$this->db->join('works_analysis_test wat','wat.work_id = works.work_id');
				$this->db->join('tests','work_test_id = test_id');
				$this->db->join('mst_test_methods methods','tests.test_method_id = methods.test_method_id');
				$this->db->where('product_type','Protocol');
				$this->db->where('works_sample_type_id',$post['product_id']);
				$this->db->where_in('work_job_type_id',$post['quote_id']);
				$test_query = $this->db->get('works');
				$test_result['protocol_data'] = $test_query->result_array();
			}
			return array_merge($test_result['test_data'],$test_result['package_data'],$test_result['protocol_data']);
		}
		return false;
	}

	public function get_crm_user()
	{
		$this->db->select("admin_users.uidnr_admin,concat(admin_profile.admin_fname,' ',admin_profile.admin_lname) as user_name");
		$this->db->from('admin_users');
		$this->db->join('admin_profile', 'admin_users.uidnr_admin=admin_profile.uidnr_admin','left');
		$this->db->where('admin_users.crm_flag', '1');
		$query = $this->db->get();

		if ($query->num_rows() > 0) {
			return $query->result_array();
		}
		return [];
	}


	public function get_division()
	{
		$query = $this->db->select('division_id, division_name')
			->from('mst_divisions')
			->where('status', '1')
			->order_by('division_name', 'asc')
			->get();

		if ($query->num_rows() > 0) {
			return $query->result_array();
		}
		return [];
	}

	public function get_temp_reg()
	{
		$this->db->select('temp_reg_id, temp_no');
		$query = $this->db->get('temporary_registration');
		if ($query->num_rows() > 0) {
			return $query->result_array();
		}
		return [];
	}

	public function get_contact_person($applicant)
	{
		$query = $this->db->select('contact_id,contact_name')
			->from('contacts')
			->join('cust_customers', 'customer_id=contacts_customer_id')
			->where('contacts.status', '1')
			->where('customer_id', $applicant)
			->get();
		if ($query->num_rows() > 0) {
			return $query->result_array();
		}
		return [];
	}

	public function get_buyer_name($applicant)
	{
		$query = $this->db->query("SELECT customer_id,customer_name FROM cust_customers 
                 INNER JOIN  buyer_factory ON  buyer_id=customer_id 
                 WHERE  customer_type='Buyer' AND isactive='Active' AND factory_id='$applicant'
                 UNION
                 SELECT customer_id,customer_name FROM cust_customers 
                 INNER JOIN  buyer_agent  ON  buyer_id=customer_id 
                 WHERE  customer_type='Buyer' AND isactive='Active' AND agent_id='$applicant'");

		if ($query->num_rows() > 0) {
			return $query->result_array();
		}
		return [];
	}

	public function get_exchange_rate($currency)
	{
		$query = $this->db->select('ex_rate as exchange_rate')
			->from('currency_exchage')
			->where('ex_curr_id', $currency)
			->where('primary_curr_id', '1')
			->get();

		if ($query->num_rows() > 0) {
			return $query->result_array();
		}
		return [];
	}


	public function get_contact_name($user)
	{
		$query = $this->db->select('contact_id,contact_name')
			->from('contacts')
			->join('cust_customers', 'customer_id=contacts_customer_id')
			->where('contacts.status', '1')
			->where("customer_id IN ('" . $user . "')", NULL, false)
			->get();
		if ($query->num_rows() > 0) {
			return $query->result_array();
		}
		return [];
	}

	public function get_test_name($product_id)
	{
		$admin_currency_id = 1;
		$this->db->select("test_id, concat(test_name,'(',test_method,')') AS test_name, pricelist.price AS test_price");
		$this->db->from('tests');
		$this->db->join('test_sample_type stm', 'test_id = stm.test_sample_type_test_id');
		$this->db->join('pricelist', 'pricelist.pricelist_test_id=tests.test_id AND pricelist.currency_id=' . $admin_currency_id, 'left');
		$this->db->where('test_sample_type_sample_type_id', $product_id);
		$this->db->where('test_status', 'Active');
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->result_array();
		}
		return [];
	}
	public function get_customer($type)
	{
		$this->db->select('customer_id,customer_name')
			->from('cust_customers')
			->where('customer_type', $type)
			->order_by('customer_name', 'asc');
		if (exist_val('Listing/branch_wise', $this->session->userdata('permission'))) {
			$checkUser = $this->session->userdata('user_data');
			$this->db->where('cust_customers.mst_branch_id', $checkUser->branch_id); //branch_id
		}
		$query = $this->db->get();

		if ($query->num_rows() > 0) {
			return $query->result_array();
		}
		return [];
	}
}
