<?php

class Quote_trf extends MY_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('QuoteTrf', 'trf');
		$this->check_session();
	}
	public function index()
	{
		$data['applicant'] = $this->trf->get_applicant_name();
		$data['agent'] = $this->trf->get_agent_name();
		$data['third_party'] = $this->trf->get_third_party();
		$data['country'] = $this->trf->get_country();
		$data['currency'] = $this->trf->get_currency();
		$data['application_care_instruction'] = $this->trf->get_application_care_instruction();
		$data['crm_user_list'] = $this->trf->get_crm_user();
		$data['division_list'] = $this->trf->get_division();
		$data['temp_reg'] = $this->trf->get_temp_reg();
		$data['branchs']=$this->trf->get_result('branch_id,branch_name','mst_branches', ['status > '=>0]);
		$custom_field[0] = [];
		$data['custom_field'] = $custom_field;
		$this->load_view('trf/quotes_trf', $data);
	}
	public function quote_ref_no_store()
	{
		$post = $this->input->post();
		$result = $this->trf->quote_ref_no_store($post);
		echo json_encode($result);
	}
	public function product_store()
	{
		$post = $this->input->post();
		$result = $this->trf->product_store($post);
		echo json_encode($result);
	}
	public function tests_store()
	{
		$post = $this->input->post();
		$result = $this->trf->tests_store($post);
		echo json_encode($result);
	}
	public function quoteTrf()
	{
		//  Form validation
		$this->form_validation->set_rules('trf_customer_type', 'Customer Type', 'required');
		// $this->form_validation->set_rules('open_trf_customer_id', 'Customer', 'required');
		$this->form_validation->set_rules('trf_service_type', 'Service Type', 'required');
		$this->form_validation->set_rules('trf_applicant', 'Applicant', 'required');
		$this->form_validation->set_rules('trf_buyer', 'Buyer', 'required');
		$this->form_validation->set_rules('trf_contact', 'Contact Person', 'required');
		$this->form_validation->set_rules('trf_sample_desc', 'Sample Description', 'required');
		$this->form_validation->set_rules('invoice_to', 'Invoice to', 'required');
		$this->form_validation->set_rules('trf_invoice_to_contact', 'Invoice to Contact', 'required');
		$this->form_validation->set_rules('quote_ref[]', 'Quote Ref', 'required');
		$this->form_validation->set_rules('trf_country_destination', 'Country Of Destination', 'required');
		$this->form_validation->set_rules('open_trf_currency_id', 'Country Currency', 'required');
		$this->form_validation->set_rules('open_trf_exchange_rate', 'Exchange Rate', 'required');
		$this->form_validation->set_rules('trf_country_orgin', 'Country Of Origin', 'required');
		$this->form_validation->set_rules('trf_product', 'Product', 'required');
		$this->form_validation->set_rules('trf_end_use', 'End Use', 'required');
		$this->form_validation->set_rules('division', 'Division', 'required');
		$this->form_validation->set_rules('crm_user_id[]', 'CRM User', 'required');
		$this->form_validation->set_rules('trf_no_of_sample', 'No. of sample', 'required');
		// $this->form_validation->set_rules('griddata[]', 'Test Name and Price', 'required');
		$this->form_validation->set_rules('reported_to', 'REPORT TO', 'required');
		$this->form_validation->set_rules('sample_return_to', 'Sample Return To', 'required');
		$this->form_validation->set_rules('sales_person','Sales Person','required');
		// Check form validation for dynamic fields
		$product_id = $this->input->post('trf_product');
		$fields = $this->trf->check_fields($product_id);

		// Commented by Saurabh on 13-05-2022
		// if (!empty($fields)) {
		// 	foreach ($fields as $value) {
		// 		if ($value['registration_fields_mandatory'] == "Yes") {
		// 			$this->form_validation->set_rules("dynamic[trf_registrationfield_fields_" . $value['registration_fields_id'] . "]", $value['registration_fields_name'], "required");
		// 		}
		// 	}
		// }

		if ($this->form_validation->run() == true) {
			$checkUser = $this->session->userdata('user_data');
			$post = $this->input->post();
			$record = array(
				'trf_applicant'			=> $post['trf_applicant'],
				'trf_contact'			=> $post['trf_contact'],
				'trf_sample_ref_id'		=> $post['trf_sample_ref_id'],
				'trf_invoice_to'		=> $post['invoice_to'],
				'trf_invoice_to_contact' => $post['trf_invoice_to_contact'],
				'trf_product'			=> $post['trf_product'],
				'trf_buyer'				=>	isset($post['trf_buyer']) ? $post['trf_buyer'] : '',
				'trf_agent'				=> isset($post['trf_agent']) ? $post['trf_agent'] : '',
				'trf_sample_desc'		=> $post['trf_sample_desc'],
				'trf_no_of_sample'		=> $post['trf_no_of_sample'],
				'trf_country_destination' => $post['trf_country_destination'],
				'trf_end_use'			=> $post['trf_end_use'],
				'reported_to'			=> $post['reported_to'],
				'open_trf_currency_id'	=> $post['open_trf_currency_id'],
				'open_trf_customer_id'	=> $post['trf_applicant'],
				'open_trf_exchange_rate' => $post['open_trf_exchange_rate'],
				'trf_thirdparty'		=> isset($post['trf_thirdparty']) ? $post['trf_thirdparty'] : '',
				'trf_cc'				=> isset($post['trf_cc']) ? implode(",", $post['trf_cc']) : '',
				'trf_bcc'				=> isset($post['trf_bcc']) ? implode(",", $post['trf_bcc']) : '',
				'trf_country_orgin'		=> $post['trf_country_orgin'],
				'tat_date'				=> ($post['tat_date']) ? (date('Y-m-d H:i:s', strtotime($post['tat_date']))) : '',
				'division'				=> $post['division'],
				'cc_type'				=> isset($post['cc_id']) ? implode(",", $post['cc_id']) : '',
				'bcc_type'				=> isset($post['bcc_id']) ? implode(",", $post['bcc_id']) : '',
				'sample_return_to'		=> $post['sample_return_to'],
				'trf_type'				=> "TRF",
				'create_on'				=> date('Y-m-d H:i'),
				'crm_user_id'			=> implode(",",$post['crm_user_id']),
				'open_trf_customer_type' => $post['trf_customer_type'],
				'trf_client_ref_no'		=> $post['trf_client_ref_no'],
				'sample_pickup_services' => $post['sample_pickup_services'],
				'temp_ref_id'			=> isset($post['']) ? $post[''] : 0,
				'trf_regitration_type'  => 'Online TRF',
				'sales_person'			=> $post['sales_person'],
				'trf_quote_id'			=> implode(',',$this->input->post('quote_ref'))
			);
			if ($post['trf_service_type'] >= 2 && $post['trf_service_type'] <= 10) {
				$record['service_days'] = $post['trf_service_type'];
				$record['trf_service_type'] = 'Regular';
			}
			$record['updated_by'] = $checkUser->uidnr_admin;
			
			$record['trf_branch'] = $post['trf_branch'];
			if (!empty($post['dynamic_field']) && count($post['dynamic_field']) > 0) {
				$record['product_custom_fields'] = json_encode($post['dynamic_field']);
			} else {
				$record['product_custom_fields'] = null;
			}
			$gridData = $post['test'];
			$this->db->trans_start();
			$trf_id = $this->trf->insert_data('trf_registration', $record);
			if (!empty($post['test']) && count($post['test']) > 0) {
				$gridData = $post['test'];
				$multiple = array();
				foreach ($gridData as $gridD) {
					$gridD['trf_test_trf_id'] = $trf_id;
					$gridD['trf_test_status'] = 'New';
					$this->db->insert('trf_test',$gridD);
				}
				// $trf_test_status = $this->trf->insert_multiple_data("trf_test ", $multiple);
			}
			if (!empty($post['dynamic'])) {
				foreach ($post['dynamic'] as $key => $care_instructions) {
					$care_instructions['created_by'] = $this->admin_id();
					$care_instructions['created_on'] = date('Y-m-d H:i:s');
					$care_instructions['trf_id'] = $trf_id;
					$multiple_care[] = $care_instructions;
				}
				$trf_test_status = $this->trf->insert_multiple_data('trf_apc_instruction', $multiple_care);
			}
			$serial_no = $this->trf->get_row('MAX(serial_no) as serial_no', 'trf_number_confiq', ['year(created_on)=year(now())' => null]);
			$today = date("Ymd");
			$serial_no = (int)$serial_no->serial_no + 1;
			$confiq['branch_id'] = $record['trf_branch'];
			$confiq['division_id'] = $record['division'];
			$confiq['serial_no'] = $serial_no;
			$confiq['created_on'] = date("Y-m-d H:i:s");
			$this->trf->insert_data("trf_number_confiq", $confiq);
			$rand = str_pad($serial_no, 6, "0", STR_PAD_LEFT);
			$unique['trf_ref_no'] = 'TRF/' . $today . '/' . $rand;
			$status = $this->trf->update_data("trf_registration", $unique, ['trf_id' => $trf_id]);
			$this->db->trans_complete();
			if ($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				$msg = array('status' => 0, 'msg' => 'ERROR WHILE GENERATE TRF No.' . $unique['trf_ref_no']);
			} else {
				$this->db->trans_commit();
				$this->session->set_flashdata('success', 'SUCCESSFULLY GENERATE TRF No. ' . $unique['trf_ref_no']);
				$msg = array('status' => 1, 'msg' => 'SUCCESSFULLY GENERATE TRF No.' . $unique['trf_ref_no']);
			}
		} else {
			$msg = array(
				'stauts' => 0,
				'error' => $this->form_validation->error_array(),
				'msg' => 'something Wrong'
			);
		}
		echo json_encode($msg);
	}

	public function get_customer_name(){
		$type = $this->input->post('customer_type');
		$data = $this->trf->get_customer($type);
		echo json_encode($data);
	}

	public function get_contact_person_name(){
		$applicant = $this->input->post('applicant');
		$data = $this->trf->get_contact_person($applicant);
		echo json_encode($data);
	}

	public function get_buyer_name(){
		$applicant = $this->input->post('applicant');
		$data = $this->trf->get_buyer_name($applicant);
		echo json_encode($data);
	}

	public function get_exchange_rate(){
		$currency = $this->input->post('country_currency');
		$data = $this->trf->get_exchange_rate($currency);
		echo json_encode($data);
	}

	// Functoin to get Invoice Contact, CC, and BCC contacts
	public function get_contact_name(){
		$user = $this->input->post('user');
		$data = $this->trf->get_contact_name($user);
		echo json_encode($data);
	}

	public function get_test_name(){
		$product_id = $this->input->post('product');
		$data  = $this->trf->get_test_name($product_id);
		echo json_encode($data);
	}
	public function get_custom_fields(){
		$product_id = $this->input->post('product_id');
		$fields = $this->trf->check_fields($product_id);
		if (!empty($fields)) {
			foreach ($fields as $key => $value) {
				$field 	= '<tr  data-row='.$key.'>';
				$field .= '<td>';
				$field .= '<input type="text" class="form-control form-control-sm" value="'.$value['registration_fields_name'].'" name="dynamic_field['.$key.'][0]">';
				$field .= '</td>';
				$field .= '<td>';
				$field .= '<input type="text" name="dynamic_field['.$key.'][1]" class="form-control form-control-sm">';
				$field .= '</td>';
				$field .= '<td><a href="javascript:void(0)" title="Remove Row" class="remove_row btn btn-danger">X</a></td>';
				$field .= '</tr>';
				$custom_field[] = $field;
			}
		} else{
			$custom_field = [];
		}
		// echo "<pre>"; print_r($custom_field); die;
		echo json_encode($custom_field);
	}
	public function get_care_instruction_image(){
		$care_instruction_id = $this->input->post('care_provided');
		$image = $this->trf->get_fields_by_id('application_care_instruction','instruction_image',$care_instruction_id,'instruction_id')[0]['instruction_image'];
		$url = $this->getS3Url1($image);
		echo json_encode($url);
	}

	public function get_sales_person()
	{
		$key = ($this->input->get('key'))?$this->input->get('key'):NULL;
		$data = $this->trf->get_sales_person($key);
		echo json_encode($data);
	}
}
