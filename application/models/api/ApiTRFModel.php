<?php
class ApiTRFModel extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}

	public function getCustomer($buyer_id)
	{
		// Get buyer id
		$buyer_query = $this->db->select('customer_id')->where('basil_customer_details_id', $buyer_id)->get('cust_customers')->row_array();
		if (!empty($buyer_query['customer_id'])) {
			$buyer_lims_id = $buyer_query['customer_id'];
			$this->db->select('customer_id, customer_name');
			$this->db->join('cust_customers', 'factory_id = customer_id');
			$this->db->where('customer_type', 'Factory');
			$this->db->where('buyer_id', $buyer_lims_id);
			$this->db->distinct('factory_id');
			$query = $this->db->get('buyer_factory');
			if ($query->num_rows() > 0) {
				return $query->result_array();
			} else {
				return FALSE;
			}
		} else {
			return false;
		}
	}

	public function getServiceType()
	{
		$this->db->select('service_type_id, service_type_name');
		// $this->db->where('customer_id', $customerId);
		$query = $this->db->get('service_type');
		if ($query->num_rows() > 0) {
			return $query->result_array();
		} else {
			return FALSE;
		}
	}

	public function getSupplier($buyer_id)
	{
		$buyer_query = $this->db->select('customer_id')->where('basil_customer_details_id', $buyer_id)->get('cust_customers')->row_array();
		if (!empty($buyer_query['customer_id'])) {
			$buyer_lims_id = $buyer_query['customer_id'];
			$this->db->select('customer_id, customer_name');
			$this->db->join('cust_customers', 'thirdparty_id = customer_id');
			$this->db->where('customer_type', 'Thirdparty');
			$this->db->where('buyer_id', $buyer_lims_id);
			$this->db->distinct('thirdparty_id');
			$query = $this->db->get('buyer_thirdparty');
			if ($query->num_rows() > 0) {
				return $query->result_array();
			} else {
				return FALSE;
			}
		} else {
			return false;
		}
	}

	public function getAgent($buyer_id)
	{
		$buyer_query = $this->db->select('customer_id')->where('basil_customer_details_id', $buyer_id)->get('cust_customers')->row_array();
		if (!empty($buyer_query['customer_id'])) {
			$buyer_lims_id = $buyer_query['customer_id'];
			$this->db->select('customer_id, customer_name');
			$this->db->join('cust_customers', 'agent_id = customer_id');
			$this->db->where('customer_type', 'Agent');
			$this->db->where('buyer_id', $buyer_lims_id);
			$this->db->distinct('agent_id');
			$query = $this->db->get('buyer_agent');
			if ($query->num_rows() > 0) {
				return $query->result_array();
			} else {
				return FALSE;
			}
		} else {
			return false;
		}
	}

	public function getContactPerson($applicant_id)
	{
		$this->db->select('contact_id, contact_name');
		$this->db->where('contacts_customer_id', $applicant_id);
		$query = $this->db->get('contacts');
		if ($query->num_rows() > 0) {
			return $query->result_array();
		} else {
			return FALSE;
		}
	}

	public function getDestCountry()
	{
		$this->db->select('country_id, country_name');
		$query = $this->db->get('mst_country');
		if ($query->num_rows() > 0) {
			return $query->result_array();
		} else {
			return FALSE;
		}
	}

	public function getCurrency()
	{
		$this->db->select('currency_id, currency_name');
		$query = $this->db->get('mst_currency');
		if ($query->num_rows() > 0) {
			return $query->result_array();
		} else {
			return FALSE;
		}
	}

	public function getDivision()
	{
		$this->db->select('division_id, division_name');
		$query = $this->db->get('mst_divisions');
		if ($query->num_rows() > 0) {
			return $query->result_array();
		} else {
			return FALSE;
		}
	}

	public function getProduct()
	{
		$this->db->select('sample_type_id, sample_type_name');
		$query = $this->db->get('mst_sample_types');
		if ($query->num_rows() > 0) {
			return $query->result_array();
		} else {
			return FALSE;
		}
	}

	public function save_open_trf($record, $dynamic, $test = [], $care_instruction = [])
	{
		$this->db->trans_begin();
		// save trf fields
		if (!empty($dynamic)) {
			$record['product_custom_fields'] = json_encode($dynamic);
		} else {
			$record['product_custom_fields'] = null;
		}

		$save_trf = $this->db->insert('trf_registration', $record);
		// echo $this->db->last_query();
		$insert_id = $this->db->insert_id();

		// Check test data
		if (!empty($test)) {

			foreach ($test as $test_data) {
				$test_data->trf_test_trf_id = $insert_id;
				$test_data->trf_test_status = 'New';
				$test_data->trf_work_id = 0;
				$insert_test = $this->db->insert('trf_test', $test_data);
			}
		}

		if (!empty($care_instruction)) {
			foreach ($care_instruction as $key => $care_instructions) {
				$care_instructions->created_by = 1;
				$care_instructions->created_on = date('Y-m-d H:i:s');
				$care_instructions->trf_id = $insert_id;
				$data[$key] = $care_instructions;
			}
			$this->db->insert_batch('trf_apc_instruction', $data);
		}

		// Generate unique number
		$today = date("Ymd");
		$serial_no_query = $this->db->select_max('serial_no')
			->from('trf_number_confiq')
			->where('year(created_on)', date('Y'))
			//   ->where('branch_id',$record['trf_branch'])
			->get()->row();

		$serial_number = $serial_no_query->serial_no + 1;

		// save trf number config
		// $config['branch_id'] = $record['trf_branch'];
		$config['division_id'] = $record['division'];
		$config['serial_no'] = $serial_number;
		$config['created_on'] = date('Y-m-d H:i:s');

		$save_config = $this->db->insert('trf_number_confiq', $config);
		$rand = str_pad($serial_number, 6, "0", STR_PAD_LEFT);
		$unique['trf_ref_no'] = 'TRF/' . $today . '/' . $rand;

		// Update trf reference number
		$update_trf = $this->db->update('trf_registration', $unique, ['trf_id' => $insert_id]);
		// Commit the process
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			return $result = array('success' => false);
		} else {
			$this->db->trans_commit();
			return $result = array('success' => true, 'unique_id' => $unique['trf_ref_no'], 'inserted_id' => $insert_id);
		}
	}

	public function getTRF($buyer_id, $keyword, $applicant, $product, $from_date, $to_date, $limit, $start, $count = false)
	{
		$buyer_query = $this->db->select('customer_id')->where('basil_customer_details_id', $buyer_id)->get('cust_customers')->row_array();
		$this->db->select('trf_id, trf_ref_no, customer_name, sample_type_name, trf_sample_ref_id, tr.create_on, tat_date, service_type_name, trf_status');
		$this->db->join('cust_customers', 'customer_id = trf_applicant');
		$this->db->join('mst_sample_types', 'sample_type_id = trf_product');
		$this->db->join('service_type', 'service_type_id = trf_service_type');
		($keyword != 'NULL') ? $this->db->like('trf_ref_no', $keyword) : '';
		($applicant != 'NULL') ? $this->db->where('trf_applicant', $applicant) : '';
		($product != 'NULL') ? $this->db->where('trf_product', $product) : '';
		if ($from_date != 'NULL' && $to_date != 'NULL') {
			$this->db->where('date(tr.create_on) >=', $from_date);
			$this->db->where('date(tr.create_on) <=', $to_date);
		} elseif ($from_date != 'NULL') {
			$this->db->where('date(tr.create_on)', $from_date);
		} elseif ($to_date != 'NULL') {
			$this->db->where('date(tr.create_on)', $to_date);
		}
		$this->db->where('trf_buyer', $buyer_query['customer_id']);
		$this->db->where_in('tr.trf_status', ['New', 'Sample Received']);
		$this->db->order_by('trf_id', 'desc');
		if (!$count) {
			$this->db->limit($limit, $start);
		}
		$query = $this->db->get('trf_registration tr');
		if ($count) {
			return $query->num_rows();
		}
		// echo '<pre>';echo $this->db->last_query();
		if ($query->num_rows() > 0) {
			return $query->result_array();
		}
		return false;
	}

	public function getTRFDetails($trf_id)
	{
		$this->db->select('trf_id, trf_ref_no, applicant.customer_name as applicant_name, sample_type_name, trf_sample_ref_id, tr.create_on, tat_date, service_type_name, trf_status, contact_name, currency_name, sample_return_to, division_name, supplier.customer_name as supplier_name, agent.customer_name as agent_name, reported_to, trf_invoice_to, dest.country_name as destination_country, origin.country_name as origin_country, trf_end_use, trf_client_ref_no, sample_pickup_services, product_custom_fields, trf_applicant, trf_thirdparty, trf_agent, trf_product, trf_service_type, trf_country_destination, trf_country_orgin, division, trf_contact, open_trf_currency_id, trf_sample_desc, trf_invoice_to, trf_no_of_sample');
		$this->db->join('cust_customers applicant', 'applicant.customer_id = trf_applicant');
		$this->db->join('cust_customers supplier', 'supplier.customer_id = trf_thirdparty', 'left');
		$this->db->join('cust_customers agent', 'agent.customer_id = trf_agent', 'left');
		$this->db->join('mst_sample_types', 'sample_type_id = trf_product', 'left');
		$this->db->join('service_type', 'service_type_id = trf_service_type', 'left');
		$this->db->join('mst_currency', 'currency_id = open_trf_currency_id', 'left');
		$this->db->join('mst_country dest', 'dest.country_id = trf_country_destination', 'left');
		$this->db->join('mst_country origin', 'origin.country_id = trf_country_orgin', 'left');
		$this->db->join('mst_divisions', 'division_id = division', 'left');
		$this->db->join('contacts', 'contact_id = trf_contact', 'left');
		$this->db->where('trf_id', $trf_id);
		$query = $this->db->get('trf_registration tr');
		// echo $this->db->last_query(); die;
		if ($query->num_rows() > 0) {
			return $query->row_array();
		}
		return false;
	}

	public function getTrfTest($trf_id)
	{
		$this->db->select('trf_test_id, test_id, test_name, mst_test_methods.test_method_id, test_method_name');
		$this->db->join('tests', 'test_id = trf_test_test_id');
		$this->db->join('mst_test_methods', 'mst_test_methods.test_method_id = tests.test_method_id');
		$this->db->where('trf_test_trf_id', $trf_id);
		// $this->db->where('is_deleted', 0);
		$query = $this->db->get('trf_test');
		// echo $this->db->last_query(); die;
		if ($query->num_rows() > 0) {
			return $query->result_array();
		}
		return false;
	}

	public function getTrfAPCInstruction($trf_id)
	{
		$this->db->select('apc_instruction_id, application_care_id, instruction_name, description, image_sequence, instruction_image');
		$this->db->join('application_care_instruction', 'instruction_id = application_care_id');
		$this->db->where('trf_id', $trf_id);
		// $this->db->where('is_deleted', 0);
		$query = $this->db->get('trf_apc_instruction');
		if ($query->num_rows() > 0) {
			return $query->result_array();
		}
		return false;
	}

	public function update_open_trf($trf_id, $record, $dynamic, $test = [], $care_instruction = [])
	{
		$this->db->trans_begin();
		// save trf fields
		if (!empty($dynamic)) {
			$record['product_custom_fields'] = json_encode($dynamic);
		} else {
			$record['product_custom_fields'] = null;
		}
		$save_trf = $this->db->update('trf_registration', $record, ['trf_id' => $trf_id]);
		// Check test data
		if (!empty($test)) {
			foreach ($test as $test_data) {
				if ($test_data->trf_test_id) {
					$update = $this->db->update('trf_test', $test_data, ['trf_test_id' => $test_data->trf_test_id]);
				} else {
					$insert_test = $this->db->insert('trf_test', $test_data);
				}
			}
		}

		if (!empty($care_instruction)) {
			foreach ($care_instruction as $key => $care_instructions) {
				if (!empty($care_instructions->apc_instruction_id)) {
					$this->db->update('trf_apc_instruction', $care_instructions, ['apc_instruction_id' => $care_instructions->apc_instruction_id]);
				} else {
					$care_instructions->created_by = 1;
					$care_instructions->created_on = date('Y-m-d H:i:s');
					$care_instructions->trf_id = $trf_id;
					$data[$key] = $care_instructions;
				}
			}
			if (empty($care_instruction[0]->apc_instruction_id)) {
				$this->db->insert_batch('trf_apc_instruction', $data);
			}
		}

		// Commit the process
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			return $result = array('success' => false);
		} else {
			$this->db->trans_commit();
			return $result = array('success' => true);
		}
	}

	public function getSelectedCustomer($applicant)
	{
		$buyer_query = $this->db->select('customer_id')->where('basil_customer_details_id', $applicant)->get('cust_customers')->row_array();
		$this->db->select('customer_id, customer_name');
		$this->db->join('trf_registration', 'trf_applicant = customer_id');
		$this->db->where('trf_buyer', $buyer_query['customer_id']);
		$this->db->distinct('trf_applicant');
		$query = $this->db->get('cust_customers');
		if ($query->num_rows() > 0) {
			return $query->result_array();
		}
		return false;
	}

	public function getSelectedProduct($applicant)
	{
		$buyer_query = $this->db->select('customer_id')->where('basil_customer_details_id', $applicant)->get('cust_customers')->row_array();
		$this->db->select('sample_type_id, sample_type_name');
		$this->db->join('trf_registration', 'sample_type_id = trf_product');
		$this->db->where('trf_buyer', $buyer_query['customer_id']);
		$this->db->distinct('trf_product');
		$query = $this->db->get('mst_sample_types');
		if ($query->num_rows() > 0) {
			return $query->result_array();
		}
		return false;
	}
	public function dashboarddata($basil_customer_details_id)
	{

		$buyer_query = $this->db->select('customer_id')->where('basil_customer_details_id', $basil_customer_details_id)->get('cust_customers')->row_array();
		$this->db->select('trf_id, trf_ref_no,sr.gc_no,sr.create_on as sample_registration_date,sr.due_date,sr.barcode_path,gr.status as booking_status,'
			. ' customer_name, sample_type_name, trf_sample_ref_id, tr.create_on, tat_date, service_type_name, trf_status, manual_report_file');
		$this->db->join('sample_registration sr', 'trf_id = trf_registration_id', 'left');
		$this->db->join('generated_reports gr', 'sr.sample_reg_id = gr.sample_reg_id');
		$this->db->join('cust_customers', 'customer_id = trf_applicant', 'left');
		$this->db->join('mst_sample_types', 'sample_type_id = trf_product', 'left');
		$this->db->join('service_type', 'service_type_id = trf_service_type', 'left');
		$this->db->where('trf_buyer', $buyer_query['customer_id']);
		$this->db->group_start();
		$this->db->where('gr.status', 'Report Generated');
		$this->db->where('sr.status', 'Report Generated');
		$this->db->or_where('sr.released_to_client', 1);
		$this->db->group_end();
		$query = $this->db->get('trf_registration tr');
		$data['report_published'] = $query->num_rows();

		$this->db->select('trf_id, trf_ref_no,sr.gc_no,sr.create_on as sample_registration_date,sr.due_date,sr.barcode_path,sr.status as booking_status, customer_name, sample_type_name, trf_sample_ref_id, tr.create_on, tat_date, service_type_name, trf_status');
		$this->db->join('sample_registration sr', 'trf_id = trf_registration_id', 'left');
		$this->db->join('cust_customers', 'customer_id = trf_applicant', 'left');
		$this->db->join('mst_sample_types', 'sample_type_id = trf_product', 'left');
		$this->db->join('service_type', 'service_type_id = trf_service_type', 'left');
		$this->db->where('trf_buyer', $buyer_query['customer_id']);
		$this->db->where_in('sr.status', ['Registered', 'Sample Sent for Evaluation', 'Evaluation Completed', 'Sample Sent for Manual Reporting']);
		$this->db->order_by('trf_id', 'desc');
		$query = $this->db->get('trf_registration tr');
		$data['track_count'] = $query->num_rows();

		$this->db->select('trf_id, trf_ref_no, customer_name, sample_type_name, trf_sample_ref_id, tr.create_on, tat_date, service_type_name, trf_status');
		$this->db->join('cust_customers', 'customer_id = trf_applicant', 'left');
		$this->db->join('mst_sample_types', 'sample_type_id = trf_product', 'left');
		$this->db->join('service_type', 'service_type_id = trf_service_type', 'left');
		$this->db->where('trf_buyer', $buyer_query['customer_id']);
		$this->db->where_in('tr.trf_status', ['New']);

		$query = $this->db->get('trf_registration tr');
		$data['trf_new_count'] = $query->num_rows();
		$this->db->select('trf_id, trf_ref_no, customer_name, sample_type_name, trf_sample_ref_id, tr.create_on, tat_date, service_type_name, trf_status');
		$this->db->join('cust_customers', 'customer_id = trf_applicant', 'left');
		$this->db->join('mst_sample_types', 'sample_type_id = trf_product', 'left');
		$this->db->join('service_type', 'service_type_id = trf_service_type', 'left');
		$this->db->where('trf_buyer', $buyer_query['customer_id']);
		$this->db->where_in('tr.trf_status', ['Sample Received']);

		$query = $this->db->get('trf_registration tr');
		$data['sample_recieved_count'] = $query->num_rows();

		return $data;
	}
}
