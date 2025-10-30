<?php

/**
 * 
 */
class TestRequestForm extends MY_Model
{

	function __construct()
	{
		parent::__construct();
	}

	public function get_customer($type, $key = null)
	{
		$this->db->select('customer_id as id,customer_name as name, customer_name as full_name');
		$this->db->from('cust_customers');
		$this->db->where('customer_type', $type);
		if (exist_val('Branch/Wise', $this->session->userdata('permission'))) {
			$multibranch = $this->session->userdata('branch_ids');
			$this->db->group_start();
			$this->db->where(['cust_customers.mst_branch_id IN (' . $multibranch . ') ' => null]); //branch_id
			$this->db->group_end();
		}
               // $this->db->where('isactive','Active');
		$this->db->order_by('customer_name', 'asc');
		($key != null) ? $this->db->like('cust_customers.customer_name', $key, 'after') : '';
		$this->db->limit(10);
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->result_array();
		}
		return [];
	}

	// Added by Saurabh on 21-07-2021 to get selected customer
	public function get_selected_customer($customer_id){
		$this->db->select('customer_id as id,customer_name as name, customer_name as full_name');
		$this->db->where('customer_id',$customer_id);
		$query = $this->db->get('cust_customers');
		if($query->num_rows() > 0){
			return $query->result_array();
		}
		return [];
	}

	public function get_buyer()
	{
		$this->db->select('customer_id as id,customer_name as name')
			->from('cust_customers')
			->where('customer_type', 'Buyer');
               // $this->db->where('isactive','Active');
		if (exist_val('Branch/Wise', $this->session->userdata('permission'))) {
			$multibranch = $this->session->userdata('branch_ids');
			$this->db->group_start();
			$this->db->where(['cust_customers.mst_branch_id IN (' . $multibranch . ') ' => null]); //branch_id
			$this->db->group_end();
		}
		$query = $this->db->get();

		if ($query->num_rows() > 0) {
			return $query->result_array();
		}
		return [];
	}

	public function get_applicant_name($key = null)
	{
		$this->db->select('customer_id as id, CONCAT(customer_name,address) as name, CONCAT(customer_name,address) as full_name');
		$this->db->from('cust_customers');
		//$this->db->where('isactive', 'Active');
		if (exist_val('Branch/Wise', $this->session->userdata('permission'))) {
			$multibranch = $this->session->userdata('branch_ids');
			$this->db->group_start();
			$this->db->where(['cust_customers.mst_branch_id IN (' . $multibranch . ') ' => null]); //branch_id
			$this->db->group_end();
		}
		$this->db->order_by('customer_name', 'asc');
		$this->db->limit(10);
		($key != null) ? $this->db->like('cust_customers.customer_name', $key, 'after') : '';
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->result_array();
		}
		return [];
	}

	public function selected_applicant_name($applicant)
	{
		$this->db->select('customer_id as id, CONCAT(customer_name,address) as name, CONCAT(customer_name,address) as full_name');
		$this->db->from('cust_customers');
		$this->db->where('isactive', 'Active');
		$this->db->where('customer_id', $applicant);
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->result_array();
		}
		return [];
	}

	// public function get_sales_person($key){
	// 	$this->db->select('ap.uidnr_admin as id, concat(ap.admin_fname," ",ap.admin_lname) as name, concat(ap.admin_fname," ",ap.admin_lname) as full_name');
	// 	$this->db->join('admin_users au','au.uidnr_admin = ap.uidnr_admin');
	// 	if($key != NULL){
	// 		$this->db->group_start();
	// 		($key != NULL)?$this->db->like('admin_fname',$key):'';
	// 		($key != NULL)?$this->db->or_like('admin_fname',$key):'';
	// 		$this->db->group_end();
	// 	}
	// 	$this->db->group_start();
	// 	$this->db->where('id_admin_role',7);
	// 	$this->db->or_where('id_admin_role',3);
	// 	$this->db->or_where('id_admin_role',33);
	// 	$this->db->group_end();
	// 	$this->db->where('au.admin_active','1');
	// 	$this->db->limit(30);
	// 	$query = $this->db->get('admin_profile ap');
	// 	echo $this->db->last_query(); die;
	// 	if($query->num_rows() > 0){
	// 		return $query->result_array();
	// 	}
	// 	return [];
	// }

	// Changes for sales person name 17-06-2021 by Saurabh 
	public function get_sales_person($key){
		$this->db->select('ap.uidnr_admin as id, concat(ap.admin_fname," ",ap.admin_lname) as name, concat(ap.admin_fname," ",ap.admin_lname) as full_name');
		$this->db->join('admin_users au','au.uidnr_admin = ap.uidnr_admin');
		$this->db->join('admin_role','au.id_admin_role = admin_role.id_admin_role');
		if($key != NULL){
			$this->db->group_start();
			($key != NULL)?$this->db->like('admin_fname',$key):'';
			($key != NULL)?$this->db->or_like('admin_lname',$key):'';
			$this->db->group_end();
		}
		$this->db->where('sales_person','1');
		$this->db->where('au.admin_active','1');
                $this->db->order_by('name','asc');
		$this->db->limit(30);
		$query = $this->db->get('admin_profile ap');
		// echo $this->db->last_query(); die;
		if($query->num_rows() > 0){
			return $query->result_array();
		}
		return [];
	}

	public function get_agent_name()
	{
		$this->db->select('customer_id as id, customer_name as name, customer_name as full_name')
			->from('cust_customers')
			->where('customer_type', 'Agent')
			->where('isactive', 'Active');
		if (exist_val('Branch/Wise', $this->session->userdata('permission'))) {
			$multibranch = $this->session->userdata('branch_ids');
			$this->db->group_start();
			$this->db->where(['cust_customers.mst_branch_id IN (' . $multibranch . ') ' => null]); //branch_id
			$this->db->group_end();
		}
		$query = $this->db->get();

		if ($query->num_rows() > 0) {
			return $query->result_array();
		}
		return [];
	}

	public function get_contact_person($applicant, $key = null)
	{
		$this->db->select('contact_id as id,contact_name as name, contact_name as full_name');
		$this->db->from('contacts');
		$this->db->join('cust_customers', 'customer_id=contacts_customer_id');
		$this->db->where('contacts.status', '1');
		$this->db->where('customer_id', $applicant);
		$this->db->limit(10);
		if (exist_val('Branch/Wise', $this->session->userdata('permission'))) {
			$multibranch = $this->session->userdata('branch_ids');
			$this->db->group_start();
			$this->db->where(['cust_customers.mst_branch_id IN (' . $multibranch . ') ' => null]); //branch_id
			$this->db->group_end();
		}
		($key != null) ? $this->db->like('contact_name', $key, 'after') : '';
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->result_array();
		}
		return [];
	}

	public function get_buyer_name($applicant, $key = null)
	{
		$search = ($key != null) ? 'and customer_name like "' . $key . '%" and limit 10' : '';
		$query = $this->db->query("SELECT customer_id as id,customer_name as name, customer_name as full_name FROM cust_customers 
                 INNER JOIN  buyer_factory ON  buyer_id=customer_id 
                 WHERE  customer_type='Buyer' AND isactive='Active' AND factory_id='$applicant' $search
                 UNION
                 SELECT  customer_id as id,customer_name as name, customer_name as full_name FROM cust_customers 
                 INNER JOIN  buyer_agent  ON  buyer_id=customer_id 
                 WHERE  customer_type='Buyer' AND isactive='Active' AND agent_id='$applicant' $search");
		if ($query->num_rows() > 0) {
			return $query->result_array();
		}
		return [];
	}

	public function get_third_party($key = null)
	{
		$this->db->select('customer_id as id, customer_name as name, customer_name as full_name');
		$this->db->from('cust_customers');
		$this->db->where('customer_type', 'Thirdparty');
		$this->db->where('isactive', 'Active');
		if (exist_val('Branch/Wise', $this->session->userdata('permission'))) {
			$multibranch = $this->session->userdata('branch_ids');
			$this->db->group_start();
			$this->db->where(['cust_customers.mst_branch_id IN (' . $multibranch . ') ' => null]); //branch_id
			$this->db->group_end();
		}
		$this->db->limit(10);
		($key != null) ? $this->db->like('customer_name', $key, 'after') : '';
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->result_array();
		}
		return [];
	}
	// Added by saurabh on 21-09-2021
	public function get_selected_third_party($third_party){
		$this->db->select('customer_id as id, customer_name as name, customer_name as full_name');
		$this->db->from('cust_customers');
		$this->db->where('customer_id',$third_party);
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

	public function get_crm_user()
	{
		$this->db->select("admin_users.uidnr_admin,concat(admin_profile.admin_fname,' ',admin_profile.admin_lname) as user_name");
		$this->db->from('admin_users');
		$this->db->join('admin_profile', 'admin_users.uidnr_admin=admin_profile.uidnr_admin','left');
		$this->db->join('operator_profile', 'operator_profile.uidnr_admin=admin_users.uidnr_admin');
		$this->db->where('admin_users.crm_flag', '1');

		if (exist_val('Branch/Wise', $this->session->userdata('permission'))) {
			$multibranch = $this->session->userdata('branch_ids');
			$this->db->group_start();
			$this->db->where(['operator_profile.default_branch_id IN (' . $multibranch . ') ' => null]); //branch_id
			$this->db->group_end();
		}
		$query = $this->db->get();

		if ($query->num_rows() > 0) {
			return $query->result_array();
		}
		return [];
	}

	public function get_contact_name($user, $key = null)
	{
		$this->db->select('contact_id as id,contact_name as name, contact_name as full_name');
		$this->db->from('contacts');
		$this->db->join('cust_customers', 'customer_id=contacts_customer_id');
		$this->db->where('contacts.status', '1');
		if (exist_val('Branch/Wise', $this->session->userdata('permission'))) {
			$multibranch = $this->session->userdata('branch_ids');
			$this->db->group_start();
			$this->db->where(['cust_customers.mst_branch_id IN (' . $multibranch . ') ' => null]); //branch_id
			$this->db->group_end();
		}
		$this->db->where("customer_id IN ('" . $user . "')", NULL, false);
		$this->db->limit(10);
		($key != null) ? $this->db->like('contact_name', $key, 'after') : '';
		$query = $this->db->get();
		// echo $this->db->last_query(); die;
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

	public function get_test_name($product_id, $key = null, $limit = null)
	{
		$admin_currency_id = 1;
		$this->db->select("test_id as id, test_name AS name,test_name AS full_name");
		$this->db->from('tests');
		$this->db->join('test_sample_type stm', 'test_id = stm.test_sample_type_test_id');
		$this->db->group_start();
		$this->db->where(['test_sample_type_sample_type_id' => $product_id, 'test_status' => 'Active']);
		$this->db->group_end();
		($key != null) ? $this->db->like("tests.test_name", $key) : '';
		($key != null) ? $this->db->or_like("tests.test_method", $key) : '';
		($limit != null) ? $this->db->limit($limit) : '';
		$this->db->order_by('name', 'ASC');
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->result_array();
		}
		return [];
	}

	public function save_open_trf($record, $dynamic, $test = '', $care_instruction = '')
	{
		// echo "<pre>"; print_r($test); die;
		$this->db->trans_begin();
		// save trf fields
		if (!empty($dynamic)) {
			$record['product_custom_fields'] = json_encode($dynamic);
		} else {
			$record['product_custom_fields'] = null;
		}

		//  $sql = 'INSERT into trf_registration set 
		// trf_service_type="'.$record['trf_service_type'].'",
		// service_days="'.$record['service_days'].'",
		// trf_applicant="'.$record['trf_applicant'].'",
		// trf_contact="'.$record['trf_contact'].'",
		// trf_sample_ref_id="'.$record['trf_sample_ref_id'].'",
		// trf_invoice_to="'.$record['trf_invoice_to'].'",
		// trf_invoice_to_contact="'.$record['trf_invoice_to_contact'].'",
		// trf_product="'.$record['trf_product'].'",
		// trf_buyer="'.$record['trf_buyer'].'",
		// trf_agent="'.$record['trf_agent'].'",
		// trf_sample_desc="'.$record['trf_sample_desc'].'",
		// trf_no_of_sample="'.$record['trf_no_of_sample'].'",
		// trf_country_destination="'.$record['trf_country_destination'].'",
		// trf_end_use="'.$record['trf_end_use'].'",
		// trf_client_ref_no="'.$record['trf_client_ref_no'].'",
		// reported_to="'.$record['reported_to'].'",
		// sample_return_to="'.$record['sample_return_to'].'",
		// open_trf_currency_id="'.$record['open_trf_currency_id'].'",
		// open_trf_customer_id="'.$record['open_trf_customer_id'].'",
		// open_trf_customer_type="'.$record['open_trf_customer_type'].'",
		// open_trf_exchange_rate="'.$record['open_trf_exchange_rate'].'",
		// create_on="'.$record['create_on'].'",
		// trf_thirdparty="'.$record['trf_thirdparty'].'",
		// trf_cc="'.$record['trf_cc'].'",
		// cc_type="'.$record['cc_type'].'",
		// trf_bcc="'.$record['trf_bcc'].'",
		// bcc_type="'.$record['bcc_type'].'",
		// trf_country_orgin="'.$record['trf_country_orgin'].'",
		// trf_branch="'.$record['trf_branch'].'",
		// trf_type="'.$record['trf_type'].'",
		// division="'.$record['division'].'",
		// crm_user_id="'.$record['crm_user_id'].'",
		// sample_pickup_services="'.$record['sample_pickup_services'].'",
		// temp_ref_id="'.$record['temp_ref_id'].'",
		//         updated_by = "'.$this->admin_id().'",
		// product_custom_fields="'.$record['product_custom_fields'].'"';

		$save_trf = $this->db->insert('trf_registration', $record);
		// echo $this->db->last_query();
		// echo $this->db->last_query();
		$insert_id = $this->db->insert_id();

		// Check test data
		if (!empty($test)) {
			
			foreach ($test as $test_data) {
				$test_data['trf_test_trf_id'] = $insert_id;
				$test_data['trf_test_status'] = 'New';
				$test_data['trf_work_id'] = 0;
				// $test_data['trf_test_test_id'] = $value['trf_test_test_id'];
				// $test_data['trf_test_test_method_id'] = $value['trf_test_test_method_id'];
				$insert_test = $this->db->insert('trf_test', $test_data);
				// echo $this->db->last_query();
			}
		}

		if (!empty($care_instruction)) {
			foreach ($care_instruction as $key => $care_instructions) {
				$care_instructions['created_by'] = $this->admin_id();
				$care_instructions['created_on'] = date('Y-m-d H:i:s');
				$care_instructions['trf_id'] = $insert_id;
				$data[$key] = $care_instructions;
			}
			// echo "<pre>"; print_r($data); die;
			$this->insert_multiple_data('trf_apc_instruction', $data);
			// echo $this->db->last_query();
		}
		// Save dynamic fields
		//Check fields id
		$query_field_id = $this->db->select('registration_fields_id')
			->from('registration_fields')
			->where('registration_fields_sample_type_id', $record['trf_product'])
			->where('status', '0')
			->get();

		if ($query_field_id->num_rows() > 0) {
			$field_id = $query_field_id->result_array();
		}


		// Generate unique number
		$today = date("Ymd");
		$serial_no_query = $this->db->select_max('serial_no')
			->from('trf_number_confiq')
			->where('year(created_on)', date('Y'))
			//   ->where('branch_id',$record['trf_branch'])
			->get()->row();
		// echo $this->db->last_query(); die;	

		$serial_number = $serial_no_query->serial_no + 1;

		// save trf number config
		$config['branch_id'] = $record['trf_branch'];
		$config['division_id'] = $record['division'];
		$config['serial_no'] = $serial_number;
		$config['created_on'] = date('Y-m-d H:i:s');

		$save_config = $this->db->insert('trf_number_confiq', $config);
		// echo $this->db->last_query();
		$rand = str_pad($serial_number, 6, "0", STR_PAD_LEFT);
		$unique['trf_ref_no'] = 'TRF/' . $today . '/' . $rand;

		// Update trf reference number
		$update_trf = $this->db->update('trf_registration', $unique, ['trf_id' => $insert_id]);
		// echo $this->db->last_query();
		// Commit the process
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			return $result = array('success' => false);
		} else {
			$this->db->trans_commit();
			return $result = array('success' => true, 'unique_id' => $unique['trf_ref_no'], 'inserted_id' => $insert_id);
		}
	}

	public function count_open_trf($trf_ref_no, $cust_name, $product, $created_on, $created_by, $service_type, $buyer, $status, $division)
	{
		$this->db->select("count(DISTINCT trf_id) as count");
		$this->db->from('trf_registration trf_reg');
		$this->db->join('mst_sample_types', 'sample_type_id = trf_product');
		$this->db->join('quotes', 'quote_id = trf_quote_id', 'left');
		// $this->db->where('trf_status','New');
		// $this->db->or_where('trf_status','Sample Received');

		// Added by Saurabh on 11-08-2021 to remove controllex division
		$this->db->where('trf_reg.division !=','34');
		// Added by Saurabh on 11-08-2021 to remove controllex division

		// Added by saurabh on 01-02-2022 to show division wise list
		$checkUser = $this->session->userdata('user_data');
		$default_division = $checkUser->default_division_id;
		$assigned_division = $checkUser->user_divisions;
		$this->db->group_start();
		$this->db->where('division',$default_division);
		$this->db->or_where_in('division',explode(',',$assigned_division));
		$this->db->group_end();
		// Added by saurabh on 01-02-2022 to show division wise list

		if (exist_val('Branch/Wise', $this->session->userdata('permission'))) {
			$multibranch = $this->session->userdata('branch_ids');
			$this->db->group_start();
			$this->db->where(['trf_reg.trf_branch IN (' . $multibranch . ') ' => null]); //branch_id
			$this->db->group_end();
		}

		if ($trf_ref_no != "null") {
			$this->db->group_start();
			$this->db->like('trf_ref_no', trim(base64_decode($trf_ref_no)));
			$this->db->group_end();
		}
		if ($cust_name != "null") {
			$this->db->where('open_trf_customer_id', $cust_name);
		}
		if ($product != "null") {
			$this->db->where('trf_product', $product);
		}
		if ($division != "null") {
			$this->db->where('division', $division);
		}
		if ($created_on != "null") {
			$this->db->where('date(trf_reg.create_on)', trim(base64_decode($created_on)));
		}
		if ($buyer != "null") {
			$this->db->where('trf_reg.trf_buyer', $buyer);
		}
		if ($status != "null") {
			$this->db->where('trf_reg.trf_status', trim(base64_decode($status)));
		}
		$query = $this->db->get();
		// echo $this->db->last_query(); exit();
		$result = $query->row();
		return $result->count;
	}

	public function get_open_trf($start, $end, $trf_ref_no, $cust_name, $product, $created_on, $created_by, $service_type, $column, $order, $buyer, $status, $division, $applicant,$count = null)
	{
		// echo $column; exit;
		$this->db->select("trf_id,trf_sample_ref_id,(CASE WHEN trf_service_type ='Regular' AND  ( service_days IS NULL OR service_days='') THEN CONCAT(trf_service_type,' 3 Days') WHEN trf_service_type ='Express' THEN CONCAT('Express',' 2 Days') WHEN trf_service_type ='Express3' THEN CONCAT('Express',' 3 Days') WHEN trf_service_type ='Urgent'  THEN CONCAT(trf_service_type,' 1 Days') WHEN service_days IS NOT NULL OR service_days!='' THEN CONCAT(trf_service_type,' ',service_days,' Days') END) AS trf_service_type , sample_type_name,trf_ref_no,trf_status, reference_no,trf_regitration_type, cust_customers.customer_name as client, contact_name as contact, trf_reg.create_on, admin_fname AS updated_by,DATE_FORMAT(trf_reg.tat_date,'%d-%m-%Y') as tat_date, IF(buyer.customer_name is NULL,'N/A',buyer.customer_name) as buyer, sr.status as sample_status, applicant.customer_name as applicant_name");
		$this->db->join('cust_customers', 'customer_id=trf_applicant', 'left');
		$this->db->join('sample_registration sr', 'trf_id = trf_registration_id', 'left');
		$this->db->join('cust_customers buyer', 'buyer.customer_id=trf_buyer', 'left');
		$this->db->join('cust_customers applicant', 'applicant.customer_id=trf_applicant', 'left');
		$this->db->join('contacts', 'contact_id=trf_contact', 'left');
		$this->db->join('admin_profile', 'uidnr_admin=trf_reg.updated_by', 'left');
		$this->db->join('mst_sample_types', 'sample_type_id=trf_product');
		$this->db->join('quotes', 'quote_id=trf_quote_id', 'left');
		$this->db->from('trf_registration as trf_reg');
		// $this->db->where('trf_status','New');
		// $this->db->or_where('trf_status','Sample Received');

		// Added by Saurabh on 11-08-2021 to remove controllex division
		$this->db->where('trf_reg.division !=','34');
		// Added by Saurabh on 11-08-2021 to remove controllex division

		// Added by saurabh on 01-02-2022 to show division wise list
		// $checkUser = $this->session->userdata('user_data');
		// $default_division = $checkUser->default_division_id;
		// $assigned_division = $checkUser->user_divisions;
		// $assigned_customer = $checkUser->assigned_customer;

		// $this->db->group_start();
		// $this->db->where('division',$default_division);
		// $this->db->or_where_in('division',explode(',',$assigned_division));
		// if(!empty($assigned_customer)){			
		// 	$this->db->or_where_in('open_trf_customer_id',explode(',',$assigned_customer));
		// }
		// $this->db->group_end();
		
		// Added by saurabh on 01-02-2022 to show division wise list
		
		if (exist_val('Branch/Wise', $this->session->userdata('permission'))) {
			$multibranch = $this->session->userdata('branch_ids');
			$this->db->group_start();
			$this->db->where(['trf_reg.trf_branch IN (' . $multibranch . ') ' => null]); //branch_id
			$this->db->group_end();
		}

		if ($trf_ref_no != "null") {
			$this->db->group_start();
			$this->db->like('trf_ref_no', trim(base64_decode($trf_ref_no)));
			$this->db->group_end();
		}
		if ($applicant != "null") {
			$this->db->where('trf_applicant', $applicant);
		}
		if ($cust_name != "null") {
			$this->db->where('open_trf_customer_id', $cust_name);
		}
		if ($product != "null") {
			$this->db->where('trf_product', $product);
		}
		if ($division != "null") {
			$this->db->where('division', $division);
		}
		if ($created_on != "null") {
			$this->db->where('date(trf_reg.create_on)', trim(base64_decode($created_on)));
		}
		if ($buyer != "null") {
			$this->db->where('trf_reg.trf_buyer', $buyer);
		}
		if ($status != "null") {
			$this->db->where('trf_reg.trf_status', trim(base64_decode($status)));
		}
		if ($column != "null") {
			if ($order == "null") {
				$this->db->order_by($column, 'desc');
			} else {
				$this->db->order_by($column, $order);
			}
		} else {
			$this->db->order_by('trf_reg.trf_id', 'desc');
		}

		(!$count)?$this->db->limit($start,$end):'';
		$query = $this->db->get();
		// echo $this->db->last_query(); die;
		if ($count) {
			return $query->num_rows();
		} else {
			if ($query->num_rows() > 0) {
				return $query->result_array();
			} else {
				return false;
			}
		}
	}

	public function open_trf_selected_test($trf_id)
	{
		// $this->db->select('group_concat(test_id) as id');
		// $this->db->join('tests', 'trf_test_test_id = test_id');
		// $query = $this->db->get_where('trf_test', ['trf_test_trf_id' => $trf_id]);
		// // echo $this->db->last_query(); die;
		// if ($query->num_rows() > 0) {
		// 	return $query->row_array();
		// }
		// return [];

		$this->db->select('ts.test_id, ts.test_name, tm.test_method_id, tm.test_method_name, tt.trf_test_id, trf_test_trf_id, tt.trf_work_id, tt.trf_test_quote_type, tt.trf_test_quote_id, tt.trf_test_protocol_id, tt.trf_test_package_id');
		$this->db->join('tests ts','ts.test_id = tt.trf_test_test_id');
		$this->db->join('mst_test_methods tm','tm.test_method_id = ts.test_method_id');
		$this->db->where('trf_test_trf_id',$trf_id);
		$query = $this->db->get('trf_test tt');
		if($query->num_rows() > 0){
			return $query->result_array();
		}
		return [];
	}

	public function update_open_trf($record, $dynamic, $test, $id, $care_instruction)
	{
		$this->db->trans_begin();
		// Update TRF details
		if (!empty($dynamic)) {
			$record['product_custom_fields'] = json_encode($dynamic);
		} else {
			$record['product_custom_fields'] = null;
		}
		// $sql = "UPDATE trf_registration set 
		// trf_service_type='".$record["trf_service_type"]."',
		// service_days='".$record["service_days"]."',
		// trf_applicant='".$record["trf_applicant"]."',
		// trf_contact='".$record["trf_contact"]."',
		// trf_sample_ref_id='".$record["trf_sample_ref_id"]."',
		// trf_invoice_to='".$record["trf_invoice_to"]."',
		// trf_invoice_to_contact='".$record["trf_invoice_to_contact"]."',
		// trf_product='".$record["trf_product"]."',
		// trf_buyer='".$record["trf_buyer"]."',
		// trf_agent='".$record["trf_agent"]."',
		// trf_sample_desc='".htmlentities($record["trf_sample_desc"])."',
		// trf_no_of_sample='".$record["trf_no_of_sample"]."',
		// trf_country_destination='".$record["trf_country_destination"]."',
		// trf_end_use='".$record["trf_end_use"]."',
		// trf_client_ref_no='".$record["trf_client_ref_no"]."',
		// reported_to='".$record["reported_to"]."',
		// sample_return_to='".$record["sample_return_to"]."',
		// open_trf_currency_id='".$record["open_trf_currency_id"]."',
		// open_trf_customer_id='".$record["open_trf_customer_id"]."',
		// open_trf_customer_type='".$record["open_trf_customer_type"]."',
		// open_trf_exchange_rate='".$record["open_trf_exchange_rate"]."',
		// create_on='".$record["create_on"]."',
		// trf_thirdparty='".$record["trf_thirdparty"]."',
		// trf_cc='".$record['trf_cc']."',
		// cc_type='".$record['cc_type']."',
		// trf_bcc='".$record['trf_bcc']."',
		// bcc_type='".$record['bcc_type']."',
		// trf_country_orgin='".$record['trf_country_orgin']."',
		// trf_branch='".$record["trf_branch"]."',
		// trf_type='".$record['trf_type']."',
		// division='".$record['division']."',
		// crm_user_id='".$record["crm_user_id"]."',
		// sample_pickup_services='".$record["sample_pickup_services"]."',
		// temp_ref_id='".$record["temp_ref_id"]."',
		//         updated_by = '".$this->admin_id()."',
		// product_custom_fields='".$record["product_custom_fields"]."' where trf_id='".$id."'";
		$update_trf = $this->db->update('trf_registration', $record, ['trf_id' => $id]);

		// delete application care provided instruction for the TRF
		$this->db->delete('trf_apc_instruction', ['trf_id' => $id]);
		// Insert new application care provided instruction
		if (!empty($care_instruction)) {
			foreach ($care_instruction as $key => $care_instructions) {
				$care_instructions['created_by'] = $this->admin_id();
				$care_instructions['created_on'] = date('Y-m-d H:i:s');
				$care_instructions['trf_id'] = $id;
				$data[$key] = $care_instructions;
			}

			$this->insert_multiple_data('trf_apc_instruction', $data);
		}
		// Check test data
		if (!empty($test)) {
			$this->db->delete('trf_test', ['trf_test_trf_id' => $id]);
			// echo $this->db->last_query();
			
			foreach ($test as $test_data) {
				$test_data['trf_test_trf_id'] = $id;
				$test_data['trf_test_status'] = 'New';
				$insert_test = $this->db->insert('trf_test', $test_data);
			}
		}



		// Commit the process
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			return $result = array('success' => false);
		} else {
			$this->db->trans_commit();
			return $result = array('success' => true, 'unique_id' => $record['trf_ref_no']);
		}
	}

	public function send_sample_received($id)
	{
		$trf_id = $id;
		$this->db->select('trf_status');
		$this->db->from('trf_registration');
		$this->db->where('trf_id', $id);
		$trf_status = $this->db->get()->row();

		$data = array(
			'trf_status' => 'Sample Received'
		);
		$this->db->where('trf_id', $id);
		$status =	$this->db->update('trf_registration', $data);

		if ($status) {
			return $trf_status->trf_status;
		} else {
			return false;
		}
	}

	public function count_sample_received($trf_ref_no, $cust_name, $product, $created_on, $created_by, $service_type)
	{
		$this->db->select("count(DISTINCT trf_id) as count");
		$this->db->from('trf_registration');
		$this->db->join('mst_sample_types', 'sample_type_id = trf_product');
		$this->db->join('quotes', 'quote_id = trf_quote_id', 'left');
		$this->db->where('trf_status', 'Sample Received');
		if ($trf_ref_no != "null") {
			$this->db->like('trf_ref_no', base64_decode($trf_ref_no));
		}
		if ($cust_name != "null") {
			$this->db->where('open_trf_customer_id', $cust_name);
		}
		if ($product != "null") {
			$this->db->where('trf_product', $product);
		}
		if ($created_on != "null") {
			$this->db->where('date(trf_registration.create_on)', base64_decode($created_on));
		}
		$query = $this->db->get();
		$result = $query->row();
		return $result->count;
	}

	public function sample_received_grid($start, $end, $trf_ref_no, $cust_name, $product, $created_on, $created_by, $service_type, $column, $order, $count = NULL)
	{
		// echo $column; exit;
		$this->db->select("trf_id,trf_sample_ref_id,(CASE WHEN trf_service_type ='Regular' AND  ( service_days IS NULL OR service_days='') THEN CONCAT(trf_service_type,' 3 Days') WHEN trf_service_type ='Express2' THEN CONCAT('Express',' 2 Days') WHEN trf_service_type ='Express3' THEN CONCAT('Express',' 3 Days') WHEN trf_service_type ='Urgent'  THEN CONCAT(trf_service_type,' 1 Days') WHEN service_days IS NOT NULL OR service_days!='' THEN CONCAT(trf_service_type,' ',service_days,' Days') END) AS trf_service_type , sample_type_name,trf_ref_no,trf_status, reference_no,trf_regitration_type, customer_name as client, contact_name as contact, DATE_FORMAT(trf_reg.create_on,'%d-%b-%Y %H:%i') AS create_on, admin_fname AS updated_by");
		$this->db->join('cust_customers', 'customer_id=trf_applicant', 'left');
		$this->db->join('contacts', 'contact_id=trf_contact', 'left');
		$this->db->join('admin_profile', 'uidnr_admin=trf_reg.updated_by', 'left');
		$this->db->join('mst_sample_types', 'sample_type_id=trf_product');
		$this->db->join('quotes', 'quote_id=trf_quote_id', 'left');
		$this->db->from('trf_registration as trf_reg');
		$this->db->where('trf_status', 'Sample Received');
		if ($trf_ref_no != "null") {
			$this->db->group_start();
			$this->db->like('trf_ref_no', base64_decode($trf_ref_no));
			$this->db->group_end();
		}
		if ($cust_name != "null") {
			$this->db->where('open_trf_customer_id', $cust_name);
		}
		if ($product != "null") {
			$this->db->where('trf_product', $product);
		}
		if ($created_on != "null") {
			$this->db->where('date(trf_reg.create_on)', base64_decode($created_on));
		}
		if ($column != "null") {
			if ($order == "null") {
				$this->db->order_by($column, 'desc');
			} else {
				$this->db->order_by($column, $order);
			}
		}

		$this->db->limit($start, $end);
		$query = $this->db->get();
		// echo $this->db->last_query(); die;
		if ($count) {
			return $query->num_rows();
		} else {
			if ($query->num_rows() > 0) {
				return $query->result_array();
			} else {
				return false;
			}
		}
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

	public function selected_sales_person($sp_id)
	{
		$this->db->select('uidnr_admin as id, concat(admin_fname," ",admin_lname) as name');
		$this->db->where('uidnr_admin',$sp_id);
		$query = $this->db->get('admin_profile');
		if($query->num_rows() > 0){
			return $query->row_array();
		}
		return [];
	}
	/**------------------------clone trf---------------- */
	public function get_clone_trf_data($trf_id)
	{

		$res = $this->db->select('*')->from('trf_registration')->where('trf_id', $trf_id)->get();

		if ($res->num_rows() > 0) {

			return $res->row();
		} else {
			return false;
		}
	}

	public function get_app_care_data($trf_id)
	{

		$res = $this->db->select('*')->from('trf_apc_instruction')->where('trf_id', $trf_id)->get();

		if ($res->num_rows() > 0) {

			return $res->result();
		} else {
			return false;
		}
	}

	public function get_trf_test($trf_id)
	{

		$res = $this->db->select('*')->from('trf_test')->where('trf_test_trf_id', $trf_id)->get();

		if ($res->num_rows() > 0) {

			return $res->result();
		} else {
			return false;
		}
	}

	public function get_trf_serial_no($branch)
	{

		$res =	$this->db->select_max('serial_no')
			->from('trf_number_confiq')
			->where('year(created_on)', date('Y'))
			->where('branch_id', $branch)
			->get();

		if ($res) {

			return $res->row();
		} else {
			return false;
		}
	}
	/**------------------end ----------clone trf---------------- */

	public function get_log($trf_id)
	{
		$query = $this->db->select('operation, log_activity_on, action_message,concat(admin_fname," ",admin_lname) as taken_by')
			->join('admin_profile', 'jobs_activity_log.uidnr_admin = admin_profile.uidnr_admin')
			->where('trf_id', $trf_id)
			->order_by('jobs_activity_log_id', 'desc')
			->get(' jobs_activity_log');
		if ($query->num_rows() > 0) {
			return $query->result_array();
		}
		return [];
	}
/* added by millan on 08-10-2021 */
	public function check_trf_id($trf_id){
		$query = $this->db->select('sr.sample_reg_id')
			->from('sample_registration sr')
			->where('sr.trf_registration_id',$trf_id)
			->get();
		if($query->num_rows() > 0){
			return $query->row();
		}else{
			return false;
		}
	}
	/* added by millan on 08-10-2021 */

	// Added by Saurabh to get test protocol on 13-10-2021
	public function get_test_protocol($key){
		$this->db->select('protocol_name as name, protocol_name as full_name, protocol_id as id');
		($key != NULL)?$this->db->like('protocol_name',$key):'';
		$this->db->order_by('protocol_id','desc');
		$query = $this->db->get('protocols');
		if($query->num_rows() > 0){
			return $query->result_array();
		}
		return [];
	}

	// Added by Saurabh on 13-10-2021 to get protocol based product and tests
	public function get_protocol_product_test($protocol_id)
	{
		$result = [];
		$this->db->select('sample_type_name, sample_type_id');
		$this->db->join('protocols','sample_type_id = protocol_sample_type_id');
		$this->db->where('protocol_id',$protocol_id);
		$query = $this->db->get('mst_sample_types');
		if($query->num_rows() > 0){
			$result['product'] = $query->row_array();
		}

		$this->db->select("test_id, test_name, tm.test_method_name, tm.test_method_id, protocol_id");
		$this->db->where('pt.protocol_id',$protocol_id);
		$this->db->from('protocol_tests pt');
		$this->db->join('tests as test','test.test_id= pt.protocol_test_id','left');
		$this->db->join('mst_test_methods tm','test.test_method_id = tm.test_method_id');
		$this->db->order_by('pt.protocol_test_sort_order','ASC');
		$test_query = $this->db->get();
		if($test_query->num_rows()>0){
		$result['tests'] = $test_query->result_array();
		}
		return $result;
	}

	// Added by Saurabh on 13-10-2021 to get test packages
	public function get_test_package($key,$buyer){
		$this->db->select('package_id as id, package_name as name, package_name as full_name');
		($buyer != NULL)?$this->db->where('buyer',$buyer):''; // new 23
		($key != NULL)?$this->db->like('package_name',$key):'';
		$this->db->order_by('package_id','desc');
		$query = $this->db->get('packages');
		if($query->num_rows() > 0){
			return $query->result_array();
		}
		return [];
	}

	// Added by Saurabh on 13-10-2021 to get package based product and tests
	public function get_package_product_test($package_id)
	{
		$result = [];
		$this->db->select('sample_type_name, sample_type_id');
		$this->db->join('packages','sample_type_id = packages_sample_type_id');
		$this->db->where('package_id',$package_id);
		$query = $this->db->get('mst_sample_types');
		if($query->num_rows() > 0){
			$result['product'] = $query->row_array();
		}

		$this->db->select("test_id, test_name, tm.test_method_name, tm.test_method_id, test_package_packages_id");
		$this->db->where('pt.test_package_packages_id',$package_id);
		$this->db->from('test_packages pt');
		$this->db->join('tests as test','test.test_id= pt.test_package_test_id','left');
		$this->db->join('mst_test_methods tm','test.test_method_id = tm.test_method_id');
		$this->db->order_by('pt.package_test_sort_order','ASC');
		$test_query = $this->db->get();
		if($test_query->num_rows()>0){
		$result['tests'] = $test_query->result_array();
		}
		return $result;
	}

	// Added by Saurabh on 13-10-2021 to get package name
	public function package_details($package_id){
		$this->db->select('package_id, package_name');
		$this->db->where('package_id',$package_id);
		$query = $this->db->get('packages');
		if($query->num_rows() > 0){
			return $query->row_array();
		}
		return [];
	}

	// Added by Saurabh on 13-10-2021 to get protocol name
	public function protocol_details($protocol_id){
		$this->db->select('protocol_id, protocol_name');
		$this->db->where('protocol_id',$protocol_id);
		$query = $this->db->get('protocols');
		if($query->num_rows() > 0){
			return $query->row_array();
		}
		return [];
	}

	// Added by saurabh on 19-10-2021 to get package test
    public function get_package_test($package_id){
        $this->db->select("test_id as id, concat(test_name,'(',test_method,')') AS name");
		$this->db->where('pt.test_package_packages_id',$package_id);
		$this->db->from('test_packages pt');
		$this->db->join('tests as test','test.test_id= pt.test_package_test_id','left');
		$this->db->order_by('pt.package_test_sort_order','ASC');
		$test_query = $this->db->get();
        if($test_query->num_rows() > 0){
            return $test_query->result_array();
        }
        return [];
    }

	// Added by saurabh on 19-10-2021 to get package test
    public function get_protocol_test($protocol_id){
        $this->db->select("test_id as id, concat(test_name,'(',test_method,')') AS name");
		$this->db->where('pt.protocol_id',$protocol_id);
		$this->db->from('protocol_tests pt');
		$this->db->join('tests as test','test.test_id= pt.protocol_test_id','left');
		$this->db->order_by('pt.protocol_test_sort_order','ASC');
		$test_query = $this->db->get();
        if($test_query->num_rows() > 0){
            return $test_query->result_array();
        }
        return [];
    }

	// Added by Saurabh on 16-05-2022 to get test method
	public function get_test_method($test_id, $key){
		$this->db->select('tm.test_method_id as id, tm.test_method_name as name, tm.test_method_name as full_name');
		$this->db->join('mst_test_methods as tm','tm.test_method_id = ts.test_method_id');
		($key != NULL) ? $this->db->where('tm.test_method_name',$key) : '';
		$this->db->where('test_id',$test_id);
		$query = $this->db->get('tests ts');
		if($query->num_rows() > 0){
			return $query->result_array();
		}
		return [];
	}
	public function get_factory_name()
	{
        $this->db->select('customer_id as id, customer_name as name, address, CONCAT(customer_name,address) as full_name');
		$this->db->from('cust_customers');
		$this->db->where('customer_type', 'factory');
		/*if (exist_val('Branch/Wise', $this->session->userdata('permission'))) {
			$multibranch = $this->session->userdata('branch_ids');
			$this->db->group_start();
			$this->db->where(['cust_customers.mst_branch_id IN (' . $multibranch . ') ' => null]); //branch_id
			$this->db->group_end();
		}
        */
               // $this->db->where('isactive','Active');
		$this->db->order_by('customer_name', 'asc');		
		$query = $this->db->get();
        //echo $this->db->last_query(); die;
		if ($query->num_rows() > 0) {
			return $query->result_array();
		}
		return [];
	}
	public function get_customer_applicant_name($customer_type,$key)
	{
        $this->db->select('customer_id as id, customer_name as name, address, CONCAT(customer_name,address) as full_name');
		$this->db->from('cust_customers');
		$this->db->where('customer_type', $customer_type);
		/*if (exist_val('Branch/Wise', $this->session->userdata('permission'))) {
			$multibranch = $this->session->userdata('branch_ids');
			$this->db->group_start();
			$this->db->where(['cust_customers.mst_branch_id IN (' . $multibranch . ') ' => null]); //branch_id
			$this->db->group_end();
		}
        */
               // $this->db->where('isactive','Active');
		$this->db->order_by('customer_name', 'asc');		
		$query = $this->db->get();
        // echo $this->db->last_query(); die;
		if ($query->num_rows() > 0) {
			return $query->result_array();
		}
		return [];
	}
	public function get_service_type()
	{
        $this->db->select('*');
		$this->db->from('service_type');	
		$this->db->order_by('service_type_id', 'asc');		
		$query = $this->db->get();
       // echo $this->db->last_query(); die;
		if ($query->num_rows() > 0) {
			return $query->result_array();
		}
		return [];
	}

	public function get_service_type_single($service)
	{
        $this->db->select('*');
		$this->db->from('service_type');	
		$this->db->where('service_type_id', $service);	
		$query = $this->db->get();
       // echo $this->db->last_query(); die;
		if ($query->num_rows() > 0) {
			return $query->row_array();
		}
		return [];
	}
}
