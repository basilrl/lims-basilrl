<?php

use Mpdf\Tag\Em;

defined('BASEPATH') or exit('No direct script access allowed');

class Quotes extends MY_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Quotes_model', 'quotes_model');
		$this->check_session();
		$checkUser = $this->session->userdata('user_data');
		$this->user = $checkUser->uidnr_admin;
		$this->form_validation->set_error_delimiters('<div class="error" style="color:red">', '</div>');
		set_time_limit(0);
		ini_set('memory_limit', '-1');
	}

	public function index()
	{
		$where = NULL;
		$search = NULL;
		$sortby = NULL;
		$order = NULL;
		$from_date = NULL;
		$to_date = NULL;

		$customer_id = $this->uri->segment('3');
		$quotes_status = $this->uri->segment('4');
		$created_by_id = $this->uri->segment('5');
		$from_date = $this->uri->segment('6');
		$to_date = $this->uri->segment('7');
		$search = $this->uri->segment('8');
		$sortby = $this->uri->segment('9');
		$order = $this->uri->segment('10');

		$base_url = 'Quotes/index';

		if ($customer_id != '' && $customer_id != 'NULL') {
			$base_url .= '/' . $customer_id;
			$where['cus.customer_id'] = $customer_id;
			$data['customer_id'] = $customer_id;
			$data['customer_name'] = $this->quotes_model->get_row('customer_name', 'cust_customers', 'customer_id' . '=' . $where['cus.customer_id']);
		} else {
			$base_url .= '/NULL';
			$data['customer_id'] = NULL;
			$data['customer_name'] = NULL;
		}
		if ($quotes_status != '' && $quotes_status != 'NULL') {
			$base_url .= '/' . $quotes_status;
			$data['quote_status'] = $quotes_status;
			$where['qt.quote_status'] = base64_decode($quotes_status);
		} else {
			$base_url .= '/NULL';
			$data['quote_status'] = NULL;
		}

		if ($created_by_id != '' && $created_by_id != 'NULL') {
			$base_url .= '/' . $created_by_id;
			$where['qt.created_by'] = $created_by_id;
			$data['created_by_id'] = $created_by_id;
			$data['created_by'] = $this->quotes_model->get_row('CONCAT(admin_fname," ",admin_lname) as created_by', 'admin_profile', ['uidnr_admin' => $where['qt.created_by']]);
		} else {
			$base_url .= '/NULL';
			$data['created_by_id'] = NULL;
			$data['created_by'] = NULL;
		}

		if ($from_date != NULL && $from_date != 'NULL') {
			$data['from_date'] =  base64_decode($from_date);
			$from_date = base64_decode($from_date);
			$where["DATE(qt.created_on) >= '$from_date' "] = null;
		} else {
			$base_url .= '/NULL';
			$data['from_date'] = NULL;
		}

		if ($to_date != NULL && $to_date != 'NULL') {
			$data['to_date'] =  base64_decode($to_date);
			$to_date = base64_decode($to_date);
			$where["DATE(qt.created_on) <= '$to_date'"] = null;
		} else {
			$base_url .= '/NULL';
			$data['to_date'] = NULL;
		}

		if ($search != NULL && $search != 'NULL') {

			$data['search'] =  base64_decode($search);
			$base_url .= '/' . $search;
			$search = base64_decode($search);
		} else {

			$base_url .= '/NULL';
			$data['search'] = NULL;
			$search = NULL;
		}
		if ($sortby != NULL && $sortby != 'NULL') {
			$base_url .= '/' . $sortby;
		} else {
			$base_url .= '/NULL';
			$sortby = NULL;
		}
		if ($order != NULL && $order != 'NULL') {
			$base_url .= '/' . $order;
		} else {
			$base_url .= '/NULL';
			$order = NULL;
		}

		$total_row = $this->quotes_model->get_quotes_list(NULL, NULL, $search, NULL, NULL, $where, '1');
		$config = $this->pagination($base_url, $total_row, 10, 11);

		$data["links"] = $config["links"];
		$data['quotes_list'] = $this->quotes_model->get_quotes_list($config["per_page"], $config['page'], $search, $sortby, $order, $where);

		$page_no = $this->uri->segment('11');
		$start = (int)$page_no + 1;

		$end = (($data['quotes_list']) ? count($data['quotes_list']) : 0) + (($page_no) ? $page_no : 0);
		$data['result_count'] = "Showing " . $start . " - " . $end . " of " . $total_row . " Results";


		if ($order == NULL || $order == 'NULL') {
			$data['order'] = ($order) ? "DESC" : "ASC";
		} else {
			$data['order'] = ($order == "ASC") ? "DESC" : "ASC";
		}
		$this->load_view('quotes/quotes_list', $data);
	}


	public function get_quotes_status()
	{
		$data = $this->quotes_model->get_result('quote_status', 'quotes', NULL);
		$data = json_decode(json_encode($data), true);
		$data = $this->makeNumericArray($data, 'quote_status');
		$data = array_unique($data);
		echo json_encode($data);
	}


	public function quotes_form($op = NULL, $data = array())
	{

		if ($data && count($data) > 0) {
			$data['item'] = $data['quote_data'];
			unset($data['quote_data']);
		}


		if ($this->uri->segment('2') == "edit_quote") {
			$edit_data['msg'] = 'EDIT';
		}
		if ($this->uri->segment('2') == "revise_quote") {
			$edit_data['msg'] = 'REVISE';
		}

		if ($op == "add_quote" || $op == "") {

			$data['title'] = "Add New";
			$data['card_title'] = "Add New Quote";
			$data['reference_no'] = "";
			$data['opportunity_name'] = "";
			$data['customer_type'] = "";
			$data['customer_id'] = "";
			$data['contact_id'] = "";
			$data['quote_date'] = date('Y-m-d');
			$data['discussion_date'] = date('Y-m-d');
			$date = $data['discussion_date'];
			$data['quote_valid_date'] = date("Y-m-d", strtotime("$date +1 month"));
			$data['currency_id'] = "";
			$data['desgination_id'] = "";
			$data['approver_id'] = "";
			$data['action'] = "add_quote";
			$data['discussion_date'] = date('Y-m-d');
			$data['quote_subject'] = "";
			$data['salutation'] = "";
			$data['terms_conditions'] = "";
			$data['additional_notes'] = "";
			$data['sample_retention'] = "";
			$data['payment_terms'] = "";
			$data['quote_id'] = "";
			$data['test_data'] = "";
			$data['package_data'] = "";
			$data['protocol_data'] = "";
			$data['sample_type_id'] = "";
			$data['ref'] = "";
			$data['branch_id'] = "";
			$data['msg'] = "";
			$data['show_price'] = 0;
			$data['show_discount'] = 0;
			$data['show_test_method'] = 0;
			$data['show_division'] = 0;
			$data['show_total_amount'] = 0;
			$data['show_products'] = 0;
			$data['allow_about_us'] = 0;
			$data['mark_package'] = 0;
			$data['branch_disable_status'] = '';
			$data['contact_details'] = '';
			$this->load_view('quotes/quotes_form', $data);
		}
		if ($op == "edit_quote") {
			$data['title'] = "Update";
			$data['card_title'] = "Update Quote";
			$data['reference_no'] = $data['item']->reference_no;
			$data['opportunity_name'] = $data['item']->opportunity_name;
			$data['customer_type'] = $data['item']->customer_type;
			$data['customer_id'] = $data['item']->quotes_customer_id;
			$data['branch_id'] = $data['item']->quotes_branch_id;
			$data['contact_id'] = $data['item']->quotes_contact_id;
			$data['quote_date'] = date('Y-m-d', strtotime($data['item']->quote_date));
			$data['discussion_date'] = date('Y-m-d', strtotime($data['item']->discussion_date));
			$data['quote_valid_date'] = date('Y-m-d', strtotime($data['item']->quote_valid_date));
			$data['currency_id'] = $data['item']->currency_id;
			$data['desgination_id'] = $data['item']->desgination_id;
			$data['approver_id'] = $data['item']->approver_id;
			$data['sample_type_id'] = $data['item']->sample_type_id;
			$data['quote_subject'] = html_entity_decode($data['item']->quote_subject);
			$data['salutation'] = html_entity_decode($data['item']->salutation);
			$data['terms_conditions'] = html_entity_decode($data['item']->terms_conditions);
			$data['additional_notes'] = html_entity_decode($data['item']->additional_notes);
			$data['sample_retention'] = html_entity_decode($data['item']->sample_retention);
			$data['payment_terms'] = html_entity_decode($data['item']->payment_terms);
			$data['about_us_details'] = html_entity_decode($data['item']->about_us_details);
			$data['action'] = "edit_quote";
			$data['quote_id'] = $data['item']->quote_id;
			$data['ref'] = $data['item']->buyer_self_ref;
			$data['show_price'] = $data['item']->show_price;
			$data['show_discount'] = $data['item']->show_discount;
			$data['show_test_method'] = $data['item']->show_test_method;
			$data['show_division'] = $data['item']->show_division;
			$data['show_total_amount'] = $data['item']->show_total_amount;
			$data['show_products'] = $data['item']->show_products;
			$data['allow_about_us'] = $data['item']->allow_about_us;
			$data['allow_about_us'] = 0;
			$data['branch_disable_status'] = 'disabled';
			$data['contact_details'] = $data['item']->contact_details;
			$data['mark_package'] = 0;
			// pre_r($data); die;
			$this->load_view('quotes/quotes_form', $data);
		}
	}



	function load_branches()
	{
		$branch = $this->quotes_model->get_result('branch_id,branch_name', 'mst_branches');
		echo json_encode($branch);
	}

	public function add_quote_old()
	{

		$data = NULL;
		$data = $this->input->post();
		unset($data['msg']);
		$this->form_validation->set_rules('customer_type', 'Customer Type', 'required');
		$this->form_validation->set_rules('quotes_customer_id', 'Customer', 'required');
		$this->form_validation->set_rules('quotes_branch_id', 'Branch', 'required');
		$this->form_validation->set_rules('quotes_currency_id', 'Currency', 'required');
		$this->form_validation->set_rules('quote_signing_authority_designation_id', 'Approver Designation', 'required');
		$this->form_validation->set_rules('quotes_signing_authority_id', 'Approver', 'required');
		$this->form_validation->set_rules('additional_notes', 'Remarks', 'required');


		if ($this->form_validation->run()) {
			$data = $this->filterData($data);
			$result = $this->quotes_model->insert_quotes($data);

			if (isset($_FILES)) {
				$valid_file = $this->check_valid_pdf($_FILES['attach_file']);
				if ($valid_file) {
					$file = $this->multiple_upload_image($_FILES['attach_file']);
					if ($file) {
						$data['attach_file'] = $file['aws_path'];
					} else {
						$msg = array(
							'status' => 0,
							'msg' => 'Error in uploading file'
						);
					}
				} else {
					$msg = array(
						'status' => 0,
						'msg' => 'Please choose valid PDF file'
					);
				}
			} else {
				$data['attach_file'] = NULL;
			}



			if ($result) {
				$msg = array(
					'status' => 1,
					'msg' => 'Quote added successfully.'
				);
				$this->session->set_flashdata('success', 'Quote added successfully with Reference No  ' . $result['reference_no']);
			} else {
				$msg = array(
					'status' => 0,
					'msg' => 'Error in adding quote'
				);
			}
		} else {
			$msg = array(
				'status' => 0,
				'msg' => 'Please fill all required details.',
				'errors' => $this->form_validation->error_array()
			);
		}

		echo json_encode($msg);
	}

	public function add_quote()
	{
		$data = NULL;
		$data = $this->input->post();
		// pre_r($data); die;
		unset($data['msg']);
		$this->form_validation->set_rules('customer_type', 'Customer Type', 'required');
		$this->form_validation->set_rules('quotes_customer_id', 'Customer', 'required');
		$this->form_validation->set_rules('quotes_branch_id', 'Branch', 'required');
		$this->form_validation->set_rules('quotes_currency_id', 'Currency', 'required');
		$this->form_validation->set_rules('quote_signing_authority_designation_id', 'Approver Designation', 'required');
		$this->form_validation->set_rules('quotes_signing_authority_id', 'Approver', 'required');
		$this->form_validation->set_rules('additional_notes', 'Remarks', 'required');

		if ($this->form_validation->run()) {
			/**
			 * Check if file is uploaded
			 */
			if (isset($_FILES)) {
				$valid_file = $this->check_valid_pdf($_FILES['attach_file']);
				if ($valid_file) {
					$file = $this->multiple_upload_image($_FILES['attach_file']);
					if ($file) {
						$quote_array['attach_file'] = $file['aws_path'];
					} else {
						$msg = array(
							'status' => 0,
							'msg' => 'Error in uploading file'
						);
					}
				} else {
					$msg = array(
						'status' => 0,
						'msg' => 'Please choose valid PDF file'
					);
				}
			} else {
				$quote_array['attach_file'] = NULL;
			}
			/** 
			 * set quotes table data
			 */
			$quote_array = [
				'customer_type'								=> $data['customer_type'],
				'quotes_customer_id'						=> $data['quotes_customer_id'],
				'quote_subject'								=> $data['quote_subject'],
				'salutation'								=> $data['salutation'],
				'terms_conditions'							=> $data['terms_conditions'],
				'additional_notes'							=> $data['additional_notes'],
				'quotes_branch_id'							=> $data['quotes_branch_id'],
				'quote_signing_authority_designation_id'	=> $data['quote_signing_authority_designation_id'],
				'quotes_signing_authority_id'				=> $data['quotes_signing_authority_id'],
				'reference_no'								=> $data['reference_no'],
				'quote_date'								=> $data['quote_date'],
				'quotes_contact_id'							=> $data['quotes_contact_id'],
				'sample_retention'							=> $data['sample_retention'],
				'quote_valid_date'							=> $data['quote_valid_date'],
				'quote_exchange_rate'						=> $data['quote_exchange_rate'],
				'payment_terms'								=> $data['payment_terms'],
				'discussion_date'							=> $data['discussion_date'],
				'buyer_self_ref'							=> $data['buyer_self_ref'],
				'show_discount'								=> (array_key_exists('show_discount', $data)) ? $data['show_discount'] : 0,
				'quotes_opportunity_name'					=> $data['quotes_opportunity_name'],
				'quote_value'								=> $data['quote_value'],
				'quotes_currency_id'						=> $data['quotes_currency_id'],
				'created_by'								=> $this->admin_id(),
				'created_on'								=> date('Y-m-d H:i:s'),
				'quote_status'								=> "Draft",
				'show_price'								=> $data['show_price'],
				'show_discount'								=> $data['show_discount'],
				'show_test_method'							=> $data['show_test_method'],
				'show_division'								=> $data['show_division'],
				'show_total_amount'							=> $data['show_total_amount'],
				'show_products'								=> $data['show_products'],
				'allow_about_us'							=> $data['allow_about_us'],
				'notes_details'								=> html_entity_decode($data['notes_details']),
				'contact_details'							=> html_entity_decode($data['contact_details']),

			];
			// Get customer name

			$result = $this->quotes_model->insert_data('quotes', $quote_array);
			/**
			 * Set data for test to save in works table
			 */
			if (array_key_exists('test_data', $data)) {
				foreach ($data['test_data'] as $key => $value) {
					if ($key == 0) {
						$works_array = [
							'work_job_type_id'		=> $result,
							'works_sample_type_id'	=> $value['work_sample_type_id'],
							'work_job_type'			=> "Quote",
							'product_type'			=> "Test",
							'works_status'			=> 1,
							'works_job_id'			=> 0,
							'works_customer_id'		=> $data['quotes_customer_id']
						];
						if (!empty($value['work_sample_type_id'])) {
							$where['sample_type_id'] = $value['work_sample_type_id'];
							$work_analysis_pck[$key]['works_sample_type_id'] = $value['work_sample_type_id'];
							$sample_type_name = $this->quotes_model->get_row('sample_type_name', 'mst_sample_types', $where);
							if ($sample_type_name) {
								$works_array['work_sample_name'] = $sample_type_name->sample_type_name;
							}
						}
						$work_id = $this->quotes_model->insert_data('works', $works_array);
					}
					/**
					 * Set data for tests to save in works_analysis_test table
					 */
					$works_analysis_test = [
						'work_id'			=> $work_id,
						'work_test_id'		=> $value['test_id'],
						'rate_per_test'		=> $value['price'],
						'original_cost'		=> $value['price'],
						'total_cost'		=> $value['price'],
						'discount'			=> $value['discount'],
						'applicable_charge' => $value['applicable_charge']
					];
					$test_data_array[] = $works_analysis_test;
				}
				$save_test = $this->quotes_model->insert_multiple_data('works_analysis_test', $test_data_array);
			}
			/**
			 * Set data for package
			 */
			if (array_key_exists('package_data', $data)) {
				foreach ($data['package_data'] as $key => $package) {
					if ($key == 0) {
						$package_works_array = [
							'work_job_type_id'		=> $result,
							'works_sample_type_id'	=> $package['works_sample_type_id'],
							'work_job_type'			=> "Quote",
							'product_type'			=> "Package",
							'discount'				=> $package['discount'],
							'total_cost'			=> $package['total_cost'],
							'rate_per_package'		=> $package['price'],
							'original_cost'			=> $package['price'],
							'works_status'			=> 1,
							'works_job_id'			=> 0,
							'works_customer_id'		=> $data['quotes_customer_id'],
							'product_type_id'		=> $package['package_id']
						];
						if (!empty($package['works_sample_type_id'])) {
							$where['sample_type_id'] = $package['works_sample_type_id'];
							$work_analysis_pck[$key]['works_sample_type_id'] = $package['works_sample_type_id'];
							$sample_type_name = $this->quotes_model->get_row('sample_type_name', 'mst_sample_types', $where);
							if ($sample_type_name) {
								$package_works_array['work_sample_name'] = $sample_type_name->sample_type_name;
							}
						}
						$work_id = $this->quotes_model->insert_data('works', $package_works_array);
					}
					// Set data for tests to save in works_analysis_test table
					$works_analysis_test_package = [
						'work_id'			=> $work_id,
						'work_test_id'		=> $package['test_id'],
					];
					$test_data_package_array[] = $works_analysis_test_package;
				}
				$test_data_package_array = $this->quotes_model->insert_multiple_data('works_analysis_test', $test_data_package_array);
			}
			/**
			 * Set Data For Protocol
			 */
			if (array_key_exists('protocol_data', $data)) {
				foreach ($data['protocol_data'] as $key => $protocol) {
					if ($key == 0) {
						$protocol_works_array = [
							'work_job_type_id'		=> $result,
							'works_sample_type_id'	=> $protocol['works_sample_type_id'],
							'work_job_type'			=> "Quote",
							'product_type'			=> "Protocol",
							'discount'				=> $protocol['discount'],
							'total_cost'			=> $protocol['total_cost'],
							'original_cost'			=> $protocol['price'],
							'rate_per_package'		=> $protocol['price'],
							'works_status'			=> 1,
							'works_job_id'			=> 0,
							'works_customer_id'		=> $data['quotes_customer_id'],
							'product_type_id'		=> $protocol['protocol_id']
						];
						if (!empty($protocol['works_sample_type_id'])) {
							$where['sample_type_id'] = $protocol['works_sample_type_id'];
							$work_analysis_pck[$key]['works_sample_type_id'] = $protocol['works_sample_type_id'];
							$sample_type_name = $this->quotes_model->get_row('sample_type_name', 'mst_sample_types', $where);
							if ($sample_type_name) {
								$protocol_works_array['work_sample_name'] = $sample_type_name->sample_type_name;
							}
						}
						$work_id = $this->quotes_model->insert_data('works', $protocol_works_array);
					}
					// Set data for tests to save in works_analysis_test table
					$works_analysis_test_protocol = [
						'work_id'			=> $work_id,
						'work_test_id'		=> $protocol['test_id'],
					];
					$test_data_protocol_array[] = $works_analysis_test_protocol;
				}
				$test_data_protocol_array = $this->quotes_model->insert_multiple_data('works_analysis_test', $test_data_protocol_array);
			}

			if ($result) {
				// UPDATE Reference number for the quotation
				$confiq['mst_branch_id'] = $where2['branch_id'] = $data['quotes_branch_id'];
				$this->db->select_max('mst_serail_no');
				$this->db->from('mst_quote_seral_number');
				$this->db->where('mst_branch_id', $data['quotes_branch_id']);
				$serial_no =  $this->db->get()->row();
				$quote_number_q = $serial_no->mst_serail_no;
				$quote_number_q = $quote_number_q + 1;
				$rand = str_pad($quote_number_q, 5, "0", STR_PAD_LEFT);
				$confiq['mst_serail_no'] = $quote_number_q;
				$confiq['created_on'] = date("Y-m-d H:i:s");
				$confiq['created_by'] = $this->user;
				$this->db->insert('mst_quote_seral_number', $confiq);
				$branch = $this->quotes_model->get_row('branch_code', 'mst_branches', $where2);
				$res['reference_no'] = 'GC/' . $branch->branch_code . '/' . date('Y') . '/' . $rand;
				$this->db->where('quote_id', $result);
				$this->db->update('quotes', $res);
				$this->db->where('work_job_type_id', $result);
				$this->db->update('works', $res);
				$this->update_intro_about($result);
				$logDetails = array(
					'module' => 'sales',
					'old_status' => '',
					'new_status' => "Draft",
					'action_message' => 'Quote Created',
					'quote_id' => $result,
					'uidnr_admin' => $this->admin_id()
				);

				$status = $this->quotes_model->insert_data('sales_activity_log', $logDetails);
				$msg = array(
					'status' => 1,
					'msg' => 'Quote added successfully.'
				);
				$this->session->set_flashdata('success', 'Quote added successfully with Reference No  ' . $result['reference_no']);
			} else {
				$msg = array(
					'status' => 0,
					'msg' => 'Error in adding quote'
				);
			}
		} else {
			$msg = array(
				'status' => 0,
				'msg' => 'Please fill all required details.',
				'errors' => $this->form_validation->error_array()
			);
		}

		echo json_encode($msg);
	}

	/**
	 * Added by Saurabh on 14-06-2022 to set Introduction and About us
	 */
	public function update_intro_about($quote_id)
	{
		$where = array('quote_id' => $quote_id);
		$result = $this->quotes_model->get_generate_details($where);
		$version_number = "";
		if ($result->version_number != "") {
			$version = $result->version_number;
			if ($version == 0) {
				$version_number = "";
			} else if ($version > 0) {
				$version_number = "_V." . $version;
			}
		} else {
			$version_number = "";
		}
		$quote_array['about_us_details'] = '
<div class="center_logo" style="text-align: center;"> <img width="800px" height="" src="https://cpslims-prod.s3.ap-south-1.amazonaws.com/format/quote_logo.png" alt="" style="padding: 120px;"><div style="font-size:30px; color: grey;font-weight:bold;font-family: Cambria, Cochin, Georgia, Times, "Times New Roman", serif;text-align:center; margin-top: 80px;">TEXTILES</div>
<p style="font-size:25px; color: grey;font-weight:bold;font-family: Cambria, Cochin, Georgia, Times, "Times New Roman", serif;" >Quote Proposal For</p> 
<div style="background-color: #005ce6;color: white;height: auto;width: auto;padding: 10px;font-family: Arial, Helvetica, sans-serif !important;display:inline-block;font-size:30px;font-weight:bold">' . $result->customer_name . '</div>
<p style="color: grey;font-family: "Times New Roman" font-size:15px;margin-top: 70px;">' . $result->quote_date . '</p> </div> <div class="content" style="color: grey;font-weight:bold;font-family: Cambria, Cochin, Georgia, Times, "Times New Roman", serif;page-break-inside: auto !important;"> 
<h1>About Us</h1>
<p style="font-size: 14px; line-height: 20px;letter-spacing:0px; text-align: justify"> GEO-CHEM, founded in 1964, is an independent inspection and testing company with our regional headquarters in Dubai and branches across India, we are today one of the largest and reputable inspection and testing organizations in India. </p>
<p style="font-size: 14px; line-height: 20px;letter-spacing:0px; text-align: justify"> We are renowned as cargo inspectors and surveyors and have proven our expertise in Inspection, Survey and Testing of diverse export, import and locally traded cargos and commodities. An Independent, unbiased and quality driven inspection and testing company, Geo-Chem today has a strong reputation world- wide. </p>
<p style="font-size: 14px; line-height: 20px;letter-spacing:0px; text-align: justify"> Our services are available through a network of branch offices and associates, supported by an excellent infrastructure of ultramodern facilities, communication system and staff strength with vast experience in the industry. </p>
<p style="font-size: 14px; line-height: 20px;letter-spacing:0px; text-align: justify"> Through years of offering dedicated and professional services to our clients, we have built up enough confidence in the international trade to merit 100 per cent exclusive nominations from our clientele despite stiff competition. Geo-Chem&#39;s international client list is ample testimony to the confidence the company enjoys the world over with significantly low levels of complaints and claims, Geo- Chem focuses on client service, cost effective solutions and adheres to its time frame commitment. </p>
<p style="font-size: 14px; line-height: 20px;letter-spacing:0px; text-align: justify"><img src="https://cpslims-prod.s3.ap-south-1.amazonaws.com/format/logos.png" alt=""></p>
<div style="page-break-inside: auto !important;">
   <h1>Textiles Testing</h1>
   <p style="font-size: 14px; line-height: 20px;letter-spacing:0px; text-align: justify">Geo Chem commences textile, fabric and garment testing from Yarn/ fabric samples up to the finished goods. We provide testing for the items including active wear, pyjamas, jeans, scarves, shirts, sweaters, outerwear, belts, valets bedding, curtains, towels, soft home furnishing, carpets, rugs, bathmats and others. We measure the attributes and performance comfort properties of any type of clothing in varied environmental conditions.</p>
   <h1>Testing services for apparel &amp; textile items:</h1>
   <ul class="test_list" style="font-size: 15px;line-height: 20px;letter-spacing: 1px;">
      <li style="margin: 10px !important;">Fibre Composition analysis</li>
      <li style="margin: 10px !important;">Dimensional stability Test (shrinkage test)</li>
      <li style="margin: 10px !important;">Stain Resistance/ Stain release performance Test</li>
      <li style="margin: 10px !important;">Water absorbency/ Water repellence Test</li>
      <li style="margin: 10px !important;">pH test</li>
      <li style="margin: 10px !important;">Seam strength, Seam Slippage, Seam bursting Test</li>
      <li style="margin: 10px !important;">Fabric Weight / GSM Test (for knitted&amp; woven garments)</li>
      <li style="margin: 10px !important;">Antibacterial finish durability Test</li>
      <li style="margin: 10px !important;">Extractable Heavy metals</li>
      <li style="margin: 10px !important;">Breathability Test, Air Permeability test (sportswear)</li>
      <li style="margin: 10px !important;">Colour matching/ colour staining tests</li>
      <li style="margin: 10px !important;">REACH – SVHC Test</li>
      <li style="margin: 10px !important;">RSL (Restricted Substance Test)</li>
      <li style="margin: 10px !important;">Appearance Test ( after wash/ dry clean cycle)</li>
      <li style="margin: 10px !important;">Flammability Testing (Apparels, Nightwear, Carpets, Soft Toys, etc.)</li>
      <li style="margin: 10px !important;">Zip Quality Test ( Zipper strength, Zipper reciprocation test)</li>
      <li style="margin: 10px !important;">Peel bond Strength Test (Pasted materials)</li>
      <li style="margin: 10px !important;">Azo test, PCP, Formaldehyde, APEO/ NPEO, phthalates, etc.</li>
   </ul>
</div>
</div>';
		$quote_array['introduction'] = '<table style="border:1px solid black"> <tr> <td> <p style="text-transform: uppercase;"><b>Dear &nbsp;&nbsp;' . $result->contact_name . ',</b></p><p><b>' . $result->customer_name . ',</b></p><p><b>' . $result->contact_address . '</b></p></td></tr></table> <p>We are pleased to offer you with our special price offer for merchandise quality testing for</p><table class="post_details"> <tr> <td colspan="2"><b>TO : ' . $result->customer_name . '</b></td></tr><tr> <td><b>ATTN : </b>&nbsp;&nbsp;' . $result->salutation . '&nbsp;&nbsp;' . $result->contact_name . '<br>&nbsp;&nbsp;' . $result->contact_designation . '</td><td><b>FROM : </b>' . $result->quote_created_user . '<br>' . $result->admin_designation_name . '</td></tr><tr> <td><b>QUOTE NO : </b>&nbsp;&nbsp;' . $result->reference_no . '</td><td><b>PAGES : </b>{nb}</td></tr><tr> <td><b>DATE : </b>&nbsp;&nbsp;' . $result->quote_date . '</td><td><b>TEL NO : </b>&nbsp;&nbsp;' . $result->admin_telephone . '</td></tr><tr> <td> <p><b>ADDRESS : </b>&nbsp;&nbsp;' . $result->contact_address . '</p><p><b>Tel :</b>&nbsp;&nbsp;' . $result->contact_telephone . '</p><p><b>Email :</b>&nbsp;&nbsp;' . $result->contact_email . '</p></td><td> <b>EMAIL : &nbsp;&nbsp; ' . $result->admin_email . '</b> </td></tr><tr> <td><b>Discussion Dated :</b>&nbsp;&nbsp;' . $result->discussion_date . $version_number . '</td><td><b>Quote Validity :</b>&nbsp;&nbsp;' . $result->quote_valid_date . '</td></tr><tr> <td colspan="2"><b>Remarks : </b>' . $result->additional_notes . '</td></tr><tr> <td colspan="2"><b>Buyer/self reference : </b>' . $result->buyer_self_ref . '</td></tr></table>';
		// pre_r($quote_array); die;
		$update = $this->db->update('quotes', $quote_array, ['quote_id' => $quote_id]);
	}

	public function edit_quote($quote_id)
	{
		$this->edit_quote_check($quote_id, 'EDIT');
	}

	public function revise_quote($quote_id)
	{
		$this->edit_quote_check($quote_id, 'REVISE');
	}

	public function edit_quote_check($quote_id, $msg = NULL)
	{
		$quote_id = base64_decode($quote_id);

		$test_ids = $package_id = $data['package_data'] = $data['test_data'] = NULL;
		$where['quote_id'] = $quote_id;
		$data['quote_data'] = $this->quotes_model->get_quote_data($where);

		$data['test_data'] = NULL;
		$data['package_data'] = NULL;
		$data['protocol_data'] = NULL;
		$data['msg'] = $msg;


		$tests = $this->quotes_model->get_tests_data_Testing($quote_id);
		if ($tests && count($tests) > 0) {
			$data['test_data'] = $tests;
		} else {
			$data['test_data'] = NULL;
		}

		$package = $this->quotes_model->get_tests_data_package($quote_id);
		if ($package && count($package) > 0) {
			$data['package_data'] = $package;
		} else {
			$data['package_data'] = NULL;
		}

		$protocol = $this->quotes_model->get_tests_data_protocol($quote_id);
		if ($protocol && count($protocol) > 0) {
			$data['protocol_data'] = $protocol;
		} else {
			$data['protocol_data'] = NULL;
		}
		// if (array_key_exists('package_id', $data['quote_data'])) {
		// 	$package_id = $data['quote_data']->package_id;
		// 	if (!empty($package_id)) {
		// 		$package_id = explode(',', $package_id);
		// 		$result = $this->quotes_model->get_package_details($package_id, $quote_id);

			// 		if ($result && count($result) > 0) {
		// 			$package_data = array();
		// 			foreach ($result as $key => $value) {
		// 				$some_data['type'] = '1';
		// 				$some_data['currency_id'] = $data['quote_data']->currency_id;
		// 				$package_data = $this->quotes_model->get_tests_by_pac_id($package_id[$key], $some_data);
		// 				echo "<pre>"; print_r($package_data); die;
		// 				if ($package_data && count($package_data) > 0) {
		// 					foreach ($package_data as $key1 => $value1) {
		// 						$package_data[$key1]->rate = $value->rate;
		// 						$package_data[$key1]->cost = $value->cost;
		// 						$package_data[$key1]->total_cost = $value->total_cost;
		// 						$package_data[$key1]->discount = $value->discount;
		// 						$package_data[$key1]->package_id = $value1->package_id;
		// 						$package_data[$key1]->count = count($package_data);
		// 					}
		// 					$data['package_data'] = $package_data;
		// 				} else {
		// 					$package_data = NULL;
		// 				}
		// 			}
		// 		}
		// 	}
		// }

		// if (array_key_exists('protocol_id', $data['quote_data'])) {

		// 	$protocol_id = $data['quote_data']->protocol_id;
		// 	if (!empty($protocol_id)) {
		// 		$protocol_id = explode(',', $protocol_id);
		// 		$result = $this->quotes_model->get_protocol_details($protocol_id, $quote_id);

		// 		if ($result && count($result) > 0) {
		// 			$protocol_data = array();
		// 			foreach ($result as $key => $value) {
		// 				$some_data['type'] = '2';
		// 				$some_data['currency_id'] = $data['quote_data']->currency_id;
		// 				$protocol_data = $this->quotes_model->get_tests_by_pac_id($protocol_id[$key], $some_data);

		// 				$key2 = 0;
		// 				if ($protocol_data && count($protocol_data) > 0) {
		// 					foreach ($protocol_data as $key2 => $value2) {
		// 						$protocol_data[$key2]->rate = $value->rate;
		// 						$protocol_data[$key2]->cost = $value->cost;
		// 						$protocol_data[$key2]->total_cost = $value->total_cost;
		// 						$protocol_data[$key2]->discount = $value->discount;
		// 						$protocol_data[$key2]->protocol_id = $value2->package_id;
		// 						$protocol_data[$key2]->count = count($protocol_data);
		// 						$key2++;
		// 					}
		// 					$data['protocol_data'] = $protocol_data;
		// 				} else {
		// 					$protocol_data = NULL;
		// 				}
		// 			}
		// 		}
		// 	}
		// }
		// pre_r($data); die;
		$this->quotes_form('edit_quote', $data);
	}

	public function get_details_of_test($test_ids, $data)
	{


		if (!empty($test_ids)) {
			$test_ids = explode(',', $test_ids);
			$tests = $this->quotes_model->get_test_details(NULL, NULL, $test_ids);
			$test_rates = explode(',', $data->rate_per_test);
			$test_discount = explode(',', $data->discount);
			$applicable_charge = explode(',', $data->applicable_charge);
			$work_sample_type_id = explode(',', $data->sample_type_id);


			$key = 0;

			foreach ($tests as $key => $value) {

				$tests[$key]->price = $test_rates[$key];
				$tests[$key]->discount = $test_discount[$key];
				$tests[$key]->applicable_charge = $applicable_charge[$key];
				$tests[$key]->work_sample_type_id = $work_sample_type_id[$key];
				$where5['sample_type_id'] = $work_sample_type_id[$key];
				$sample_type_name = $this->quotes_model->get_row('sample_type_name', 'mst_sample_types', $where5);
				$tests[$key]->work_sample_name = $sample_type_name->sample_type_name;
				$key++;
			}
			return $tests;
		} else {
			return false;
		}
	}




	public function GeneratePDF_quotes($quote_id, $aws_condition = NULL)
	{
		$html = NULL;
		$upload = "NO DATA IS AVAIALABLE";
		$genPDF = $where = array();
		$where['quote_id'] = $quote_id;
		$genPDF['test_data'] = $genPDF['package_data'] = $genPDF['protocol_data'] =  NULL;
		$genPDF['data'] = $this->quotes_model->get_quote_data($where);

		$sum_amount = 0;

		if ($genPDF['data'] && count($genPDF['data'])) {
			if ($genPDF['data']->reference_no) {
				$gc_no = str_replace('/', '_', $genPDF['data']->reference_no) . "_" . date('Y-m') . ".pdf";
			} else {
				$gc_no = "document" . rand(1000, 9999) . "_" . date('Y-m') . ".pdf";
			}
			$test_ids = $genPDF['data']->test_id;

			if ($genPDF['data']->admin_signature) {
				$sig = $this->getS3Url($genPDF['data']->admin_signature);
				if ($sig) {
					$genPDF['data']->admin_signature = $sig;
				} else {
					$genPDF['data']->admin_signature = "NO SIGNATURE FOUND PLASSE UPLOAD!";
				}
			} else {
				$genPDF['data']->admin_signature = "NO SIGNATURE FOUND PLASSE UPLOAD!";
			}

			if ($genPDF['data']->approver_signature) {
				$sig = $this->getS3Url($genPDF['data']->approver_signature);

				if ($sig) {
					$genPDF['data']->approver_signature = $sig;
				} else {
					$genPDF['data']->approver_signature = "NO SIGNATURE FOUND PLASSE UPLOAD!";
				}
			} else {
				$genPDF['data']->approver_signature = "NO SIGNATURE FOUND PLASSE UPLOAD!";
			}


			$tests = $this->quotes_model->get_tests_data_Testing($quote_id);
			if ($tests && count($tests) > 0) {
				$genPDF['test_data'] = $tests;

				if ($genPDF['test_data'] && count($genPDF['test_data']) > 0) {
					$t = $genPDF['test_data'];
					foreach ($t as $k => $ts) {
						$sum_amount += $ts->applicable_charge;
					}
				}
			}

			// $package_id = $genPDF['data']->package_id;

			// if (!empty($package_id)) {
			// $package_id = explode(',', $package_id);

			$result = $this->quotes_model->get_package_details($quote_id);

			if ($result && count($result) > 0) {
				$package_data = array();

				foreach ($result as $key => $value) {
					$some_data['type'] = '1';
					$some_data['currency_id'] = $genPDF['data']->currency_id;
					$package_data = $this->quotes_model->get_tests_by_pac_id($value->package_id, $some_data);

					if ($package_data && count($package_data) > 0) {
						foreach ($package_data as $key1 => $value1) {
							$package_data[$key1]->rate = $value->rate;
							$package_data[$key1]->cost = $value->cost;
							$package_data[$key1]->total_cost = $value->total_cost;
							$package_data[$key1]->discount = $value->discount;
							$package_data[$key1]->sample_type_id = $value1->works_sample_type_id;

							$where1['sample_type_id'] = $value1->works_sample_type_id;

							$sample_type_name = $this->quotes_model->get_row('sample_type_name', 'mst_sample_types', $where1);

							if ($sample_type_name) {
								$package_data[$key1]->sample_type_name = $sample_type_name->sample_type_name;
							}
						}

						$package_tests =  $package_data_pdf = array();

						$where_pc['package_id'] = $value->package_id;
						$package_name = $this->quotes_model->get_row('package_name', 'packages', $where_pc);

						foreach ($package_data as $kc => $vall) {
							if ($kc == 0) {
								$package_data_pdf['discount'] = $vall->discount;
								$package_data_pdf['package_name'] = $package_name->package_name;
								$package_data_pdf['product_name'] = $vall->sample_type_name;
								$package_data_pdf['total_cost'] = $vall->total_cost;
								$package_data_pdf['cost'] = $vall->cost;
							}
							array_push($package_tests, $vall->test_name);
						}
						$package_data_pdf['test_name'] = $package_tests;
						$genPDF['package_data'] = $package_data_pdf;

						if ($genPDF['package_data'] && count($genPDF['package_data']) > 0) {
							$p = $genPDF['package_data'];

							$sum_amount = $p['total_cost'];
						}
					} else {
						$package_data = NULL;
					}
				}
			}
			// } else {
			// 	$package_id = "";
			// }
			// pre_r($genPDF); die;
			// $protocol_id = $genPDF['data']->protocol_id;
			// if (!empty($protocol_id)) {
			// 	$protocol_id = explode(',', $protocol_id);

			$result = $this->quotes_model->get_protocol_details($quote_id);

			if ($result && count($result) > 0) {
				$protocol_data = array();

				foreach ($result as $key => $value) {
					$some_data['type'] = '2';
					$some_data['currency_id'] = $genPDF['data']->currency_id;
					$protocol_data = $this->quotes_model->get_tests_by_pac_id($value->protocol_id, $some_data);

					if ($protocol_data && count($protocol_data) > 0) {
						foreach ($protocol_data as $key2 => $value2) {
							$protocol_data[$key2]->rate = $value->rate;
							$protocol_data[$key2]->cost = $value->cost;
							$protocol_data[$key2]->total_cost = $value->total_cost;
							$protocol_data[$key2]->discount = $value->discount;
							$protocol_data[$key2]->sample_type_id = $value2->works_sample_type_id;

							$where2['sample_type_id'] = $value2->works_sample_type_id;

							$sample_type_name = $this->quotes_model->get_row('sample_type_name', 'mst_sample_types', $where2);

							if ($sample_type_name) {
								$protocol_data[$key2]->sample_type_name = $sample_type_name->sample_type_name;
							}
						}
						$protocol_tests =  $protocol_data_pdf = array();
						// echo $value->protocol_id; die;
						$where_p['protocol_id'] = $value->protocol_id;
						$protocol_name = $this->quotes_model->get_row('protocol_name', 'protocols', $where_p);

						foreach ($protocol_data as $k => $val) {
							if ($k == 0) {
								$protocol_data_pdf['discount'] = $val->discount;
								$protocol_data_pdf['protocol_name'] = $protocol_name->protocol_name;
								$protocol_data_pdf['product_name'] = $val->sample_type_name;
								$protocol_data_pdf['total_cost'] = $val->total_cost;
								$protocol_data_pdf['cost'] = $val->cost;
							}
							array_push($protocol_tests, $val->test_name);
						}
						$protocol_data_pdf['test_name'] = $protocol_tests;
						$genPDF['protocol_data'] = $protocol_data_pdf;

						if ($genPDF['protocol_data'] && count($genPDF['protocol_data']) > 0) {
							$pt = $genPDF['protocol_data'];
							$sum_amount = $pt['total_cost'];
						}
					} else {
						$protocol_data = NULL;
					}
				}
			}
			// } else {
			// 	$package_id = "";
			// }


			$customer_id = $genPDF['data']->quotes_customer_id;
			$country_id = $state = NULL;
			$where_customer = array('customer_id' => $customer_id);
			$customer_data = $this->quotes_model->get_row('cust_customers_country_id,cust_customers_province_id,cust_customers_location_id,non_taxable', 'cust_customers', $where_customer);
			if ($customer_data && count($customer_data) > 0) {
				if (!empty($customer_data->cust_customers_country_id)) {
					$country_id = $customer_data->cust_customers_country_id;
				} else {
					$country_id = NULL;
				}

				if (!empty($customer_data->cust_customers_province_id)) {
					$state_id = $customer_data->cust_customers_province_id;
					$state = $this->quotes_model->get_row('province_name', 'mst_provinces', ['province_id' => $state_id]);
					if ($state && count($state) > 0) {
						if (!empty($state->province_name)) {
							$state = $state->province_name;
						} else {
							$state = NULL;
						}
					}
				} else {
					$state = NULL;
				}


				if ($customer_data->non_taxable != NULL || $customer_data->non_taxable != "") {
					$non_taxable = $customer_data->non_taxable;
				} else {
					$non_taxable = NULL;
				}
			}

			if (!empty($genPDF['data']->currency_id)) {
				$currency_id = $genPDF['data']->currency_id;
				$currency_decimal = $this->quotes_model->get_row('currency_decimal', 'mst_currency', ['currency_id' => $currency_id]);
				if ($currency_decimal && count($currency_decimal) > 0) {
					$currency_decimal = $currency_decimal->currency_decimal;
				} else {
					$currency_decimal = NULL;
				}
			};


			if (!empty($genPDF['data']->quotes_branch_id)) {
				$quotes_branch_id = $genPDF['data']->quotes_branch_id;
				$branch_country_id = $this->quotes_model->get_row('mst_branches_country_id', 'mst_branches', ['branch_id' => $quotes_branch_id]);
				if ($branch_country_id && count($quotes_branch_id) > 0) {
					$branch_country_id = $branch_country_id->mst_branches_country_id;
				}
			} else {
				$branch_country_id = NULL;
			}

			if (!empty($genPDF['data']->currency_id)) {
				$currency_id = $genPDF['data']->currency_id;
				$currency_code = $this->quotes_model->get_row('currency_code', 'mst_currency', ['currency_id' => $currency_id]);
				if ($currency_code && count($currency_code) > 0) {
					$currency_code = $currency_code->currency_code;
					$genPDF['data']->currency_code = $currency_code;
				}
			}


			$gst = $this->get_gst_calculation_for_quotes($branch_country_id, $non_taxable, $country_id, $state, $sum_amount, $currency_decimal);


			if (!empty($gst)) {
				$genPDF['data']->gst = $gst;
			} else {
				$genPDF['data']->gst = 0;
			}

			$branch_id = $genPDF['data']->quotes_branch_id;

			$branch_det = $this->quotes_model->get_row('branch_name,branch_telephone,branch_address', 'mst_branches', ['branch_id' => $branch_id]);

			if ($branch_det && count($branch_det) > 0) {
				$genPDF['data']->branch_name = $branch_det->branch_name;
				$genPDF['data']->branch_telephone = $branch_det->branch_telephone;
				$genPDF['data']->branch_address = $branch_det->branch_address;
			} else {
				$genPDF['data']->branch_name = '';
				$genPDF['data']->branch_telephone = '';
				$genPDF['data']->branch_address = '';
			}

			// echo "<pre>"; print_r($genPDF);die;
			if ($branch_id == '1') {
				$html = $this->load->view('quotes/gurugramPdf', $genPDF, true);
				//$html = $this->load->view('quotes/dubaiPdf', $genPDF, true);
			}
			if ($branch_id == '2') {
				$html = $this->load->view('quotes/dubaiPdf', $genPDF, true);
			}
			if ($branch_id == '4') {
				$html = $this->load->view('quotes/bangladeshpdf', $genPDF, true);
			}


			$this->load->library('M_pdf');
			$this->m_pdf->pdf->charset_in = 'UTF-8';
			$this->m_pdf->pdf->setAutoTopMargin = 'stretch';
			$this->m_pdf->pdf->lang = 'ar';
			$this->m_pdf->pdf->autoLangToFont = true;
			$this->m_pdf->pdf->WriteHTML($html);


			if ($aws_condition == 'S') {
				$pdf_file = $this->m_pdf->pdf->Output($gc_no, $aws_condition);
				$upload = $this->report_upload_aws($pdf_file, $gc_no);
				return $upload;
			} elseif ($aws_condition == 'F') {
				$file_name = LOCAL_PATH . '/' . $gc_no;
				$this->m_pdf->pdf->Output($file_name, $aws_condition);
				return $file_name;
			} else {
				$this->m_pdf->pdf->Output($gc_no . '.pdf', 'I');
			}
		}
	}



	public function download_quotePDF($path)
	{
		$path = base64_decode($path);
		$this->load->helper('download');
		$pdf_path    =   file_get_contents($path);
		$pdf_name    =   basename($path);
		force_download($pdf_name, $pdf_path);
	}


	public function get_packagesProtocol()
	{
		$data = array();
		$data['type'] = $this->input->post('type');
		$data['currency_id'] = $this->input->post('currency_id');
		$data['sample_type_id'] = $this->input->post('sample_type_id');
		$manage_packages_protocols = $this->quotes_model->get_packages_protocols($data);
		echo json_encode($manage_packages_protocols);
	}

	public function update_quote_old()
	{

		$quote_id = $this->input->post('quote_id');
		$data = $this->input->post();

		$this->form_validation->set_rules('customer_type', 'Customer Type', 'required');
		$this->form_validation->set_rules('quotes_customer_id', 'Customer', 'required');
		// $this->form_validation->set_rules('quotes_branch_id', 'Branch', 'required');
		$this->form_validation->set_rules('quotes_currency_id', 'Currency', 'required');
		$this->form_validation->set_rules('quote_signing_authority_designation_id', 'Approver designation', 'required');
		$this->form_validation->set_rules('quotes_signing_authority_id', 'Approver', 'required');
		$this->form_validation->set_rules('additional_notes', 'Remarks', 'required');

		if ($this->form_validation->run()) {
			$data = $this->filterData($data);
			if (isset($_FILES)) {
				$valid_file = $this->check_valid_pdf($_FILES['attach_file']);
				if ($valid_file) {

					$file = $this->multiple_upload_image($_FILES['attach_file']);
					if ($file) {
						$data['attach_file'] = $file['aws_path'];
					} else {
						$msg = array(
							'status' => 0,
							'msg' => 'Error in uploading file'
						);
					}
				} else {
					$msg = array(
						'status' => 0,
						'msg' => 'Please choose valid PDF file'
					);
				}
			} else {
				$data['attach_file'] = NULL;
			}


			unset($data['created_by']);
			$result = $this->quotes_model->update_quotes($data, $quote_id);
			if ($result) {
				$msg = array(
					'status' => 1,
					'msg' => 'Quote Updated successfully.'
				);
				$this->session->set_flashdata('success', 'Quote Updated successfully');
			} else {
				$msg = array(
					'status' => 0,
					'msg' => 'Error in updating quote'
				);
			}
		} else {

			$msg = array(
				'status' => 0,
				'msg' => 'Please fill all required details.',
				'errors' => $this->form_validation->error_array()
			);
		}

		echo json_encode($msg);
	}

	public function update_quote()
	{
		$data = NULL;
		$quote_id = $this->input->post('quote_id');
		$data = $this->input->post();

		unset($data['msg']);
		$this->form_validation->set_rules('customer_type', 'Customer Type', 'required');
		$this->form_validation->set_rules('quotes_customer_id', 'Customer', 'required');
		// $this->form_validation->set_rules('quotes_branch_id', 'Branch', 'required');
		$this->form_validation->set_rules('quotes_currency_id', 'Currency', 'required');
		$this->form_validation->set_rules('quote_signing_authority_designation_id', 'Approver Designation', 'required');
		$this->form_validation->set_rules('quotes_signing_authority_id', 'Approver', 'required');
		$this->form_validation->set_rules('additional_notes', 'Remarks', 'required');

		if ($this->form_validation->run()) {
			/**
			 * Check if file is uploaded
			 */
			if (isset($_FILES)) {
				$valid_file = $this->check_valid_pdf($_FILES['attach_file']);
				if ($valid_file) {
					$file = $this->multiple_upload_image($_FILES['attach_file']);
					if ($file) {
						$quote_array['attach_file'] = $file['aws_path'];
					} else {
						$msg = array(
							'status' => 0,
							'msg' => 'Error in uploading file'
						);
					}
				} else {
					$msg = array(
						'status' => 0,
						'msg' => 'Please choose valid PDF file'
					);
				}
			} else {
				$quote_array['attach_file'] = NULL;
			}
			/** 
			 * set quotes table data
			 */
			$quote_array = [
				'customer_type'								=> $data['customer_type'],
				'quotes_customer_id'						=> $data['quotes_customer_id'],
				'quote_subject'								=> $data['quote_subject'],
				'salutation'								=> $data['salutation'],
				'terms_conditions'							=> $data['terms_conditions'],
				'additional_notes'							=> $data['additional_notes'],
				// 'quotes_branch_id'							=> $data['quotes_branch_id'],
				'quote_signing_authority_designation_id'	=> $data['quote_signing_authority_designation_id'],
				'quotes_signing_authority_id'				=> $data['quotes_signing_authority_id'],
				'reference_no'								=> $data['reference_no'],
				'quote_date'								=> $data['quote_date'],
				'quotes_contact_id'							=> $data['quotes_contact_id'],
				'sample_retention'							=> $data['sample_retention'],
				'quote_valid_date'							=> $data['quote_valid_date'],
				'quote_exchange_rate'						=> $data['quote_exchange_rate'],
				'payment_terms'								=> $data['payment_terms'],
				'discussion_date'							=> $data['discussion_date'],
				'buyer_self_ref'							=> $data['buyer_self_ref'],
				'show_discount'								=> (array_key_exists('show_discount', $data)) ? $data['show_discount'] : 0,
				'quotes_opportunity_name'					=> $data['quotes_opportunity_name'],
				'quote_value'								=> $data['quote_value'],
				'quotes_currency_id'						=> $data['quotes_currency_id'],
				'quote_status'								=> "Draft",
				'show_price'								=> $data['show_price'],
				'show_discount'								=> $data['show_discount'],
				'show_test_method'							=> $data['show_test_method'],
				'show_division'								=> $data['show_division'],
				'show_total_amount'							=> $data['show_total_amount'],
				'show_products'								=> $data['show_products'],
				'allow_about_us'							=> $data['allow_about_us'],
				'notes_details'								=> html_entity_decode($data['notes_details']),
				'contact_details'							=> html_entity_decode($data['contact_details'])
			];
			$result = $this->quotes_model->update_data('quotes', $quote_array, ['quote_id' => $quote_id]);
			$this->update_intro_about($quote_id);
			$updateData['updated_on'] = date("Y-m-d");
			$updateData['updated_by'] = $this->user;
			$updateData['quote_status'] = "Draft";
			$where_quote['quote_id'] = $quote_id;
			$version_no = $this->quotes_model->get_row('version_number', 'quotes', $where_quote);
			$version_no = $version_no->version_number;
			if (empty($version_no)) {
				$version_no = 1;
			} else {
				$version_no += 1;
			}
			$updateData['version_number'] = $version_no;
			$this->db->where('quote_id', $quote_id);
			$result = $this->db->update('quotes', $updateData);
			/**
			 * Delete old records from works and works_analysis_test table
			 */
			$work_ids = $this->db->select('GROUP_CONCAT(work_id) as work_ids')->where('work_job_type_id', $quote_id)->get('works')->row_array();
			if (!empty($work_ids)) {
				$this->db->where_in('work_id', explode(',', $work_ids['work_ids']));
				$this->db->delete('works');

				$this->db->where_in('work_id', explode(',', $work_ids['work_ids']));
				$this->db->delete('works_analysis_test');
			}
			/**
			 * Set data for test to save in works table
			 */
			// pre_r($data); die;
			if (array_key_exists('test_data', $data)) {
				foreach ($data['test_data'] as $key => $value) {
					
					if ($key == 0) {
						$works_array = [
							'work_job_type_id'		=> $quote_id,
							'works_sample_type_id'	=> $value['work_sample_type_id'],
							'work_job_type'			=> "Quote",
							'product_type'			=> "Test",
							'works_status'			=> 1,
							'works_job_id'			=> 0,
							'works_customer_id'		=> $data['quotes_customer_id']
						];
						if (!empty($value['work_sample_type_id'])) {
							$where['sample_type_id'] = $value['work_sample_type_id'];
							$work_analysis_pck[$key]['works_sample_type_id'] = $value['work_sample_type_id'];
							$sample_type_name = $this->quotes_model->get_row('sample_type_name', 'mst_sample_types', $where);
							if ($sample_type_name) {
								$works_array['work_sample_name'] = $sample_type_name->sample_type_name;
							}
						}
						$work_id = $this->quotes_model->insert_data('works', $works_array);
						// $work_id = 1;
					}
					/**
					 * Set data for tests to save in works_analysis_test table
					 */
					$works_analysis_test = [
						'work_id'			=> $work_id,
						'work_test_id'		=> $value['test_id'],
						'rate_per_test'		=> $value['price'],
						'original_cost'		=> $value['price'],
						'total_cost'		=> $value['price'],
						'discount'			=> $value['discount'],
						'applicable_charge' => $value['applicable_charge']
					];
					$test_data_array[] = $works_analysis_test;
				}
				// pre_r($test_data_array); die;
				$save_test = $this->quotes_model->insert_multiple_data('works_analysis_test', $test_data_array);
			}
			/**
			 * Set data for package
			 */
			if (array_key_exists('package_data', $data)) {
				foreach ($data['package_data'] as $key => $package) {
					if ($key == 0) {
						$package_works_array = [
							'work_job_type_id'		=> $quote_id,
							'works_sample_type_id'	=> $package['works_sample_type_id'],
							'work_job_type'			=> "Quote",
							'product_type'			=> "Package",
							'discount'				=> $package['discount'],
							'total_cost'			=> $package['total_cost'],
							'rate_per_package'		=> $package['price'],
							'original_cost'			=> $package['price'],
							'works_status'			=> 1,
							'works_job_id'			=> 0,
							'works_customer_id'		=> $data['quotes_customer_id'],
							'product_type_id'		=> $package['package_id']
						];
						if (!empty($package['works_sample_type_id'])) {
							$where['sample_type_id'] = $package['works_sample_type_id'];
							$work_analysis_pck[$key]['works_sample_type_id'] = $package['works_sample_type_id'];
							$sample_type_name = $this->quotes_model->get_row('sample_type_name', 'mst_sample_types', $where);
							if ($sample_type_name) {
								$package_works_array['work_sample_name'] = $sample_type_name->sample_type_name;
							}
						}
						$work_id = $this->quotes_model->insert_data('works', $package_works_array);
					}
					// Set data for tests to save in works_analysis_test table
					$works_analysis_test_package = [
						'work_id'			=> $work_id,
						'work_test_id'		=> $package['test_id'],
					];
					$test_data_package_array[] = $works_analysis_test_package;
				}
				$test_data_package_array = $this->quotes_model->insert_multiple_data('works_analysis_test', $test_data_package_array);
			}
			/**
			 * Set Data For Protocol
			 */
			if (array_key_exists('protocol_data', $data)) {
				foreach ($data['protocol_data'] as $key => $protocol) {
					if ($key == 0) {
						$protocol_works_array = [
							'work_job_type_id'		=> $quote_id,
							'works_sample_type_id'	=> $protocol['works_sample_type_id'],
							'work_job_type'			=> "Quote",
							'product_type'			=> "Protocol",
							'discount'				=> $protocol['discount'],
							'total_cost'			=> $protocol['total_cost'],
							'original_cost'			=> $protocol['price'],
							'rate_per_package'		=> $protocol['price'],
							'works_status'			=> 1,
							'works_job_id'			=> 0,
							'works_customer_id'		=> $data['quotes_customer_id'],
							'product_type_id'		=> $protocol['protocol_id']
						];
						if (!empty($protocol['works_sample_type_id'])) {
							$where['sample_type_id'] = $protocol['works_sample_type_id'];
							$work_analysis_pck[$key]['works_sample_type_id'] = $protocol['works_sample_type_id'];
							$sample_type_name = $this->quotes_model->get_row('sample_type_name', 'mst_sample_types', $where);
							if ($sample_type_name) {
								$protocol_works_array['work_sample_name'] = $sample_type_name->sample_type_name;
							}
						}
						$protocol_work_id = $this->quotes_model->insert_data('works', $protocol_works_array);
						// echo $this->db->last_query(); die;
					}
					// Set data for tests to save in works_analysis_test table
					$works_analysis_test_protocol = [
						'work_id'			=> $protocol_work_id,
						'work_test_id'		=> $protocol['test_id'],
					];
					$test_data_protocol_array[] = $works_analysis_test_protocol;
				}
				$test_data_protocol_array = $this->quotes_model->insert_multiple_data('works_analysis_test', $test_data_protocol_array);
			}

			if ($result) {
				$logDetails = array(
					'module' => 'sales',
					'old_status' => '',
					'new_status' => "Draft",
					'action_message' => 'Quote Updated',
					'quote_id' => $result,
					'uidnr_admin' => $this->admin_id()
				);

				$status = $this->quotes_model->insert_data('sales_activity_log', $logDetails);
				$msg = array(
					'status' => 1,
					'msg' => 'Quote added successfully.'
				);
				$this->session->set_flashdata('success', 'Quote added successfully with Reference No  ' . $result['reference_no']);
			} else {
				$msg = array(
					'status' => 0,
					'msg' => 'Error in adding quote'
				);
			}
		} else {
			$msg = array(
				'status' => 0,
				'msg' => 'Please fill all required details.',
				'errors' => $this->form_validation->error_array()
			);
		}

		echo json_encode($msg);
	}


	public function filterData($data)
	{
		if ($data && count($data) > 0) {

			$data['quote_subject'] = htmlentities($data['quote_subject']);
			$data['salutation'] = htmlentities($data['salutation']);
			$data['terms_conditions'] = htmlentities($data['terms_conditions']);
			$data['payment_terms'] = htmlentities($data['payment_terms']);
			$data['sample_retention'] = htmlentities($data['sample_retention']);
			$data['additional_notes'] = htmlentities($data['additional_notes']);
			$sample_type_name = NULL;

			$exchange_rate = $data['quote_exchange_rate'];
			$test_data = NULL;
			$packages_data = NULL;
			$work_analysis_test = NULL;
			$work_analysis_pck = NULL;
			$package_test_data = NULL;
			$work_perameter = NULL;
			$work = NULL;
			$protocol_data = NULL;
			$work_analysis_protocol = NULL;
			$protocol_test_data = NULL;
			$work_perameter_protocol = NULL;

			// for work anaylis test
			$work_analysis_test = array();
			// for work
			$work = array();
			if (array_key_exists('test_data', $data)) {
				if ($data['test_data'] && count($data['test_data']) > 0) {
					$test_data = $data['test_data'];
					$key = 0;
					foreach ($test_data as $key => $value) {
						$rate_per_test = $value['price'];


						$work_analysis_test[$key]['work_test_id'] = $value['test_id'];
						$work_analysis_test[$key]['rate_per_test'] = $rate_per_test;
						$work_analysis_test[$key]['total_cost'] = $rate_per_test;
						$work_analysis_test[$key]['original_cost'] = $rate_per_test;
						$work_analysis_test[$key]['discount'] = $value['discount'];
						$work_analysis_test[$key]['applicable_charge'] = $value['applicable_charge'];

						// for work

						if (!empty($value['work_sample_type_id'])) {
							$where['sample_type_id'] = $value['work_sample_type_id'];
							$work[$key]['works_sample_type_id'] = $value['work_sample_type_id'];
							$sample_type_name = $this->quotes_model->get_row('sample_type_name', 'mst_sample_types', $where);

							if ($sample_type_name && count($sample_type_name) > 0) {
								$work[$key]['work_sample_name'] = $sample_type_name->sample_type_name;
							} else {
								$work[$key]['work_sample_name'] = "";
							}
						}



						$work[$key]['work_job_type'] = "Quote";
						$work[$key]['product_type'] = "Test";
						$work[$key]['discount'] = $value['discount'];
						$work[$key]['total_cost'] = $rate_per_test;
						$work[$key]['product_type_id'] = '0';
						$work[$key]['works_status'] = 1;
						$work[$key]['works_job_id'] = 0;
						$work[$key]['works_customer_id'] = $data['quotes_customer_id'];
						$work[$key]['works_test_standard_id'] = 0;
						$work[$key]['reference_no'] = "";

						$key++;
					}
				}
			}

			if (array_key_exists('package_data', $data)) {

				if ($data['package_data'] && count($data['package_data']) > 0) {
					$packages_data = $data['package_data'];
					$package_price = 0;
					// for package
					$work_analysis_pck = array();
					$package_test_data = array();
					// for work_anaylis package 

					$work_perameter = array();
					if ($packages_data && count($packages_data) > 0) {
						$key = 0;
						foreach ($packages_data as $key => $value) {
							if (array_key_exists('price', $value)) {
								$package_price = $value['price'];
								if (array_key_exists('discount', $value)) {

									$discount_pack = $value['discount'];
								} else {
									$discount_pack = 0;
								}
							}
							if (!empty($value['works_sample_type_id'])) {
								$where['sample_type_id'] = $value['works_sample_type_id'];
								$work_analysis_pck[$key]['works_sample_type_id'] = $value['works_sample_type_id'];
								$sample_type_name = $this->quotes_model->get_row('sample_type_name', 'mst_sample_types', $where);
								if ($sample_type_name) {
									$work_analysis_pck[$key]['work_sample_name'] = $sample_type_name->sample_type_name;
								}
							}

							$work_analysis_pck[$key]['works_customer_id'] = $data['quotes_customer_id'];
							$work_analysis_pck[$key]['work_job_type'] = "Quote";
							$work_analysis_pck[$key]['discount'] = $discount_pack;
							$work_analysis_pck[$key]['total_cost'] = $package_price;
							$work_analysis_pck[$key]['no_of_sample'] = '0';
							$work_analysis_pck[$key]['scheduled'] = 'NO';
							$work_analysis_pck[$key]['is_sampling_cost'] = 'NO';
							$work_analysis_pck[$key]['works_status'] = 1;
							$work_analysis_pck[$key]['product_type'] = 'Package';

							// work anaylis package

							$package_test_data[$key]['work_package_id'] = $value['package_id'];
							$package_test_data[$key]['rate_per_package'] = $package_price;
							$package_test_data[$key]['total_cost'] = ($package_price) - (($package_price) * ($discount_pack / 100));

							$package_test_data[$key]['original_cost'] = $package_price;

							$work_perameter[$key]['work_analysis_test_id'] = $package_test_data[$key]['work_package_id'];
							$work_perameter[$key]['work_analysis_type'] = 'Package';
							$work_perameter[$key]['parameter_id'] = $value['test_id'];
							$key++;
						}
					}
				}
			}
			if (array_key_exists('protocol_data', $data)) {

				if ($data['protocol_data'] && count($data['protocol_data']) > 0) {
					$protocol_data = $data['protocol_data'];
					$protocol_price = 0;
					$protocol_total_cost = 0;
					$discount_proto = 0;
					// for package
					$work_analysis_protocol = array();
					$protocol_test_data = array();
					// for work_anaylis package 
					$work_perameter_protocol = array();
					if ($protocol_data && count($protocol_data) > 0) {
						$key = 0;
						foreach ($protocol_data as $key => $value) {
							// print_r($value);die;
							if (array_key_exists('price', $value)) {
								$protocol_price = $value['price'];

								if (array_key_exists('discount', $value)) {

									$discount_proto = $value['discount'];
								} else {
									$discount_proto = 0;
								}
								$protocol_total_cost = $protocol_price - ($protocol_price * ($discount_proto / 100));
								$protocol_total_cost = round($protocol_total_cost, 2);
							}

							if (!empty($value['works_sample_type_id'])) {
								$where['sample_type_id'] = $value['works_sample_type_id'];
								$work_analysis_protocol[$key]['works_sample_type_id'] = $value['works_sample_type_id'];
								$sample_type_name = $this->quotes_model->get_row('sample_type_name', 'mst_sample_types', $where);
								if ($sample_type_name) {
									$work_analysis_protocol[$key]['work_sample_name'] = $sample_type_name->sample_type_name;
								}
							}



							$work_analysis_protocol[$key]['works_customer_id'] = $data['quotes_customer_id'];
							$work_analysis_protocol[$key]['work_job_type'] = "Quote";
							$work_analysis_protocol[$key]['total_cost'] = $protocol_price;
							$work_analysis_protocol[$key]['no_of_sample'] = '0';
							$work_analysis_protocol[$key]['discount'] = $discount_proto;
							$work_analysis_protocol[$key]['scheduled'] = 'NO';
							$work_analysis_protocol[$key]['is_sampling_cost'] = '0';
							$work_analysis_protocol[$key]['works_status'] = 1;
							$work_analysis_protocol[$key]['product_type'] = 'Protocol';

							// work anaylis package

							$protocol_test_data[$key]['work_package_id'] = $value['protocol_id'];
							$protocol_test_data[$key]['rate_per_package'] = $protocol_price;
							$protocol_test_data[$key]['total_cost'] = $protocol_total_cost;
							$protocol_test_data[$key]['original_cost'] = $protocol_price;

							$work_perameter_protocol[$key]['work_analysis_test_id'] = $protocol_test_data[$key]['work_package_id'];
							$work_perameter_protocol[$key]['work_analysis_type'] = 'Protocol';
							$work_perameter_protocol[$key]['parameter_id'] = $value['test_id'];
							$key++;
						}
					}
				}
			}

			$customer_id = $data['quotes_customer_id'];
			$country_id = $state = NULL;
			$where_customer = array('customer_id' => $customer_id);
			$customer_data = $this->quotes_model->get_row('cust_customers_country_id,cust_customers_province_id,cust_customers_location_id,non_taxable', 'cust_customers', $where_customer);
			if ($customer_data && count($customer_data) > 0) {
				if (!empty($customer_data->cust_customers_country_id)) {
					$country_id = $customer_data->cust_customers_country_id;
				} else {
					$country_id = NULL;
				}

				if (!empty($customer_data->cust_customers_province_id)) {
					$state_id = $customer_data->cust_customers_province_id;
					$state = $this->quotes_model->get_row('province_name', 'mst_provinces', ['province_id' => $state_id]);
					if ($state && count($state) > 0) {
						if (!empty($state->province_name)) {
							$state = $state->province_name;
						} else {
							$state = NULL;
						}
					}
				} else {
					$state = NULL;
				}


				if ($customer_data->non_taxable != NULL || $customer_data->non_taxable != "") {
					$non_taxable = $customer_data->non_taxable;
				} else {
					$non_taxable = NULL;
				}
			}

			if (!empty($data['quotes_branch_id'])) {
				$quotes_branch_id = $data['quotes_branch_id'];
			} else {
				if (!empty($data['quote_id'])) {
					$branch = $this->quotes_model->get_row('quotes_branch_id', 'quotes', ['quote_id' => $data['quote_id']]);
					if ($branch && count($branch) > 0) {
						$quotes_branch_id = $branch->quotes_branch_id;
					}
				} else {
					$branch_country_id = '';
				}
			}

			$branch_country_id = $this->quotes_model->get_row('mst_branches_country_id', 'mst_branches', ['branch_id' => $quotes_branch_id]);
			if ($branch_country_id && count($quotes_branch_id) > 0) {
				$branch_country_id = $branch_country_id->mst_branches_country_id;
			}

			if (!empty($data['quotes_currency_id'])) {
				$currency_id = $data['quotes_currency_id'];
				$currency_decimal = $this->quotes_model->get_row('currency_decimal', 'mst_currency', ['currency_id' => $currency_id]);
				if ($currency_decimal && count($currency_decimal) > 0) {
					$currency_decimal = $currency_decimal->currency_decimal;
				} else {
					$currency_decimal = NULL;
				}
			};

			if ($quotes_branch_id != '1') {
				$gst = $this->get_gst_calculation_for_quotes($branch_country_id, $non_taxable, $country_id, $state, $data['quote_value'], $currency_decimal, true);
				$data['quote_value'] = $data['quote_value'] + $gst;
			}

			$Total_data = array();
			$data['discount_type'] = 'common';
			$data['quote_date'] = date('Y-m-d', strtotime($data['quote_date']));
			$data['discussion_date'] = date('Y-m-d', strtotime($data['discussion_date']));
			$data['quote_valid_date'] = date('Y-m-d', strtotime($data['quote_valid_date']));
			$data['created_by'] = $this->user;
			$data['created_on'] = date("Y-m-d");
			$data['quote_status'] = 'Draft';
			$data['quote_value_in_base_currency'] = $data['quote_value'];
			$data['quotes_country_id'] = $data['quotes_currency_id'];
			$data['quotes_opportunity_id'] = 0;


			unset($data['sample_type']);
			unset($data['sample_type_id']);
			unset($data['quotes_branch_name']);
			unset($data['test_data']);
			unset($data['package_data']);
			unset($data['protocol_data']);
			unset($data['sample_type_category_quote']);
			unset($data['quote_id']);

			$Total_data = array(
				'data' => $data,
				'work_analysis_test' => $work_analysis_test,
				'work' => $work,
				'work_analysis_pck' => $work_analysis_pck,
				'package_test_data' => $package_test_data,
				'work_perameter' => $work_perameter,
				'work_analysis_protocol' => $work_analysis_protocol,
				'protocol_test_data' => $protocol_test_data,
				'work_perameter_protocol' => $work_perameter_protocol

			);

			return $Total_data;
		}
		return false;
	}

	public function get_customer_by_type()
	{
		$where = $type = NULL;
		$type = $this->input->post('type');
		$where['customer_type'] = $type;
		$where['isactive'] = 'Active';
		$data = $this->quotes_model->get_result('customer_id , customer_name', 'cust_customers', $where);
		echo json_encode($data);
	}

	public function get_contact_by_customer_id()
	{
		$where  = NULL;
		$customer_id = $this->input->post('customer_id');
		$where['contacts_customer_id'] = $customer_id;
		$where['status'] = '1';
		$data = $this->quotes_model->get_result('contact_id,contact_name', 'contacts', $where);
		echo json_encode($data);
	}

	public function get_product_by_category_id()
	{
		$where  = NULL;
		$type_category_id = $this->input->post('type_category_id');
		$where['type_category_id'] = $type_category_id;
		$where['status'] = '1';
		$data = $this->quotes_model->get_result('sample_type_id,sample_type_name', 'mst_sample_types', $where);
		echo json_encode($data);
	}

	public function get_product_type()
	{
		$data = array();
		$sample_type_id = $this->input->post('sample_type_id');

		$where  = NULL;
		$where['packages_sample_type_id'] = $sample_type_id;
		$packages = $this->quotes_model->get_row('package_id', 'packages', $where);
		$where  = NULL;
		$where['protocol_sample_type_id']  = $sample_type_id;
		$protocols = $this->quotes_model->get_row('protocol_id', 'protocols', $where);

		if (!empty($packages->package_id) && !empty($protocols->protocol_id)) {
			$data = array(
				'0' => "Test",
				'1' => "Packages",
				'2' => "Protocols"
			);
		}

		if (!empty($packages->package_id) && empty($protocols->protocol_id)) {
			$data = array(
				'0' => "Test",
				'1' => "Packages",
			);
		}

		if (empty($packages->package_id) && !empty($protocols->protocol_id)) {
			$data = array(
				'0' => "Test",
				'2' => "Protocols"
			);
		}

		if (empty($packages->package_id) && empty($protocols->protocol_id)) {
			$data = array(
				'0' => "Test",
			);
		}


		echo json_encode($data);
	}

	public function get_tests()
	{
		$sample_type_id = $currency_id = NULL;
		$sample_type_id = $this->input->post('sample_type_id');
		$currency_id = $this->input->post('currency_id');
		$data = $this->quotes_model->get_test_details($sample_type_id, $currency_id, NULL);
		echo json_encode($data);
	}

	public function get_tests_by_division()
	{
		$sample_type_id = $currency_id = $div_id = NULL;
		$sample_type_id = $this->input->post('sample_type_id');
		$currency_id = $this->input->post('currency_id');
		$div_id = $this->input->post('div_id');
		$data = $this->quotes_model->get_test_details_division_wise($sample_type_id, $currency_id, $div_id);
		echo json_encode($data);
	}
	public function get_tests_by_division_window()
	{
		$sample_type_id = $currency_id = $div_id = $test_id =  NULL;
		$sample_type_id = $this->input->post('sample_type_id');
		$currency_id = $this->input->post('currency_id');
		$div_id = $this->input->post('div_id');
		$test_id = $this->input->post('test_id');
		$data = $this->quotes_model->get_test_details_division_wise($sample_type_id, $currency_id, $div_id, $test_id);
		echo json_encode($data);
	}

	public function get_test_container_window()
	{
		$test_ids =  $data = $currency_id = array();
		$test_ids = $this->input->post('test_ids');
		$currency_id = $this->input->post('currency_id');
		$data = $this->quotes_model->get_test_details(NULL, $currency_id, $test_ids);
		echo json_encode($data);
	}

	public function get_country()
	{
		$data = $this->quotes_model->get_result('country_id,country_name', 'mst_country', NULL);
		echo json_encode($data);
	}
	public function get_currency()
	{
		$data = $this->quotes_model->get_result('currency_id,currency_code,currency_name,exchange_rate', 'mst_currency', NULL);
		echo json_encode($data);
	}
	public function get_product_cat()
	{
		$data = $this->quotes_model->get_result('sample_category_id,sample_category_name', 'mst_sample_category', NULL);
		echo json_encode($data);
	}

	public function get_state_by_country_id()
	{
		$where = $country_id = NULL;
		$country_id = $this->input->post('country_id');
		$where['mst_provinces_country_id'] = $country_id;
		$where['status'] = '1';
		$data = $this->quotes_model->get_result('province_id,province_name', 'mst_provinces', $where);
		echo json_encode($data);
	}

	public function get_area_by_state_id()
	{
		$where = $mst_locations_province_id = NULL;
		$mst_locations_province_id = $this->input->post('mst_locations_province_id');
		$where['mst_locations_province_id'] = $mst_locations_province_id;
		$where['status'] = '1';
		$data = $this->quotes_model->get_result('location_id,location_name', 'mst_locations', $where);
		echo json_encode($data);
	}

	public function submit_contacts()
	{
		$data = array();
		$data = $this->input->post();
		$valid = $this->form_validation->run('add_contact');
		if ($valid) {
			$data = $this->quotes_model->insert_data_into_table('contacts', $data);
			if ($data) {
				$msg = array(
					'status' => 1,
					'msg' => "Contact Added Successfully"
				);
			} else {
				$msg = array(
					'status' => 0,
					'msg' => "Error in adding contacts"
				);
			}
		} else {
			$msg = array(
				'status' => 0,
				'errors' => $this->form_validation->error_array(),
				'msg' => "Please fill required details"
			);
		}
		echo json_encode($msg);
	}

	public function submit_customers()
	{
		$data = array();
		$data = $this->input->post();
		if (empty($data['customer_type'])) {
			$msg = array(
				'status' => 0,
				'msg' => "First Select Customer Type"
			);
		} else {
			$valid = $this->form_validation->run('add_customers_quote');
			if ($valid) {
				$data = $this->quotes_model->insert_data_into_table('cust_customers', $data);
				if ($data) {
					$msg = array(
						'status' => 1,
						'msg' => "Customer Added Successfully"
					);
				} else {
					$msg = array(
						'status' => 0,
						'msg' => "Error in adding customer"
					);
				}
			} else {
				$msg = array(
					'status' => 0,
					'errors' => $this->form_validation->error_array(),
					'msg' => "Please fill required details"
				);
			}
		}

		echo json_encode($msg);
	}

	public function save_test_data()
	{
		$data = array();
		$where = NULL;
		$data = $this->input->post();
		$data['total_price'] = 0;
		$data['total_discount'] = 0;
		$data['total_applicable_charge'] = 0;
		if ($data['sample_type'] == "0") {
			$data['sample_type'] = "Test";
		}
		if ($data['sample_type'] == "1") {
			$data['sample_type'] = "Packages";
		}
		if ($data['sample_type'] == "2") {
			$data['sample_type'] = "Protocols";
		}
		$where['sample_type_id'] = $data['sample_type_id'];
		$data['product_name'] = $this->quotes_model->get_row('sample_type_name', 'mst_sample_types', $where)->sample_type_name;

		foreach ($data['test_data'] as $key => $value) {
			$data['total_price'] += $value['price'];
			$data['total_discount'] += $value['discount'];
			$data['total_applicable_charge'] += $value['applicable_charge'];
		}

		echo json_encode($data);
	}

	public function makeNumericArray($data = array(), $select)
	{
		$arr = [];
		if ($data && count($data) > 0) {
			foreach ($data as $key => $value) {
				array_push($arr, $value[$select]);
			}
			return $arr;
		} else {
			return false;
		}
	}

	public function designation()
	{
		$des_id = $data = $where = $where2 = NULL;
		$where['cfg_Name'] = 'SALES_MANAGER';
		$des_id = $this->quotes_model->get_row('cfg_Value', 'sys_configuration', $where);
		if ($des_id && count($des_id) > 0) {
			$des_id = $des_id->cfg_Value;
		}
		$where2['designation_id'] = $des_id;
		$data = $this->quotes_model->get_result('designation_id,designation_name', 'mst_designations', $where2);
		echo json_encode($data);
	}

	public function get_approver()
	{
		$data = $where = NULL;
		$where = $this->input->post('des_id');

		$data = $this->quotes_model->get_BASIL_employee($where);
		echo json_encode($data);
	}

	public function check_valid_pdf($array = array())
	{

		if (!empty($array['name'])) {
			if ($array['type'] = 'application/pdf') {
				return true;
			} else {
				return false;
			}
		}
		return false;
	}

	public function generate_quote()
	{
		$data = array();
		$where = NULL;

		$d = $this->input->post();

		// $data['show_test_method'] = $this->input->post('show_test_method');

		// $data['allow_about_us'] = $this->input->post('allow_about_us');

		// if ($data['allow_about_us'] == '1') {
		// 	$data['about_us_details'] = html_entity_decode($d['about_us_details']);
		// } else {
		// 	$data['about_us_details'] = "";
		// }

		// $data['notes_details'] = html_entity_decode($d['notes_details']);

		// if (array_key_exists('terms_details', $d)) {
		// 	$data['terms_details'] = html_entity_decode($d['terms_details']);
		// } else {
		// 	$data['terms_details'] = NULL;
		// }

		// $data['contact_details'] = html_entity_decode($d['contact_details']);

		$quote_id = $this->input->post('quote_id');
		$where['quote_id'] = $quote_id;
		$data['quote_status'] = "Awaiting Approval";
		// $data['show_discount'] = $this->input->post('show_discount');
		// $data['show_price'] = $this->input->post('show_price');
		// $data['show_division'] = $this->input->post('show_division');
		// $data['show_total_amount'] = $this->input->post('show_total_amount');
		// $data['show_products'] = $this->input->post('show_products');
		// $data['introduction'] = $this->input->post('introduction');
		// $data['updated_by'] = $this->user;
		// $data['updated_on'] = date("Y-m-d");

		$result = $this->quotes_model->generate_quote($data, $where);

		if ($result) {

			$mail_result = $this->send_approval_by_mail($quote_id);
			if ($mail_result) {
				$msg = array(
					'status' => 1,
					'msg' => 'Quote Generated'
				);
				$this->session->set_flashdata('success', $mail_result);
			} else {
				$this->session->set_flashdata('success', 'Quote Generated but not send for approval');
			}
		} else {
			$msg = array(
				'status' => 0,
				'msg' => 'Something went wrong!'
			);
		}
		echo json_encode($msg);
	}


	public function awaiting()
	{
		$data = array();
		$where = NULL;
		$quote_id = $this->input->post('quote_id');
		$where['quote_id'] = $quote_id;
		$data['quote_status'] = "Awaiting Approval";
		$data['updated_by'] = $this->user;
		$data['updated_on'] = date("Y-m-d");
		$result = $this->quotes_model->generate_quote($data, $where);

		if ($result) {
			$msg = array(
				'status' => 1,
				'msg' => 'PDF Generated'
			);

			$this->session->set_flashdata('success', 'PDF Generated');
		} else {
			$msg = array(
				'status' => 0,
				'msg' => 'Something went wrong!'
			);
		}
		echo json_encode($msg);
	}

	public function approve_quotes()
	{
		$data = array();
		$where = NULL;
		$quote_id = $this->input->post('quote_id');

		$where['quote_id'] = $quote_id;
		$data['quote_status'] = "Approved";
		$data['updated_by'] = $this->user;
		$data['updated_on'] = date("Y-m-d");
		$aws_condition = 'S';
		$result = $this->GeneratePDF_quotes($quote_id, $aws_condition);
		// pre_r($result);die;

		if ($result && is_array($result)) {
			$data['approve_pdf_path'] = $result['aws_path'];
			$check = $this->quotes_model->approve_quotes($data, $where);
			if ($check) {
				$msg = array(
					'status' => 1,
					'msg' => 'Quote Approved'
				);
				$this->session->set_flashdata('success', 'Quote Approved');
			} else {
				$msg = array(
					'status' => 0,
					'msg' => 'Quote is not approving by technical error!'
				);
			}
		} else {
			$msg = array(
				'status' => 0,
				'msg' => 'PDF is not generating!'
			);
		}

		echo json_encode($msg);
	}

	public function update_discount()
	{
		$where['quote_id'] = $this->input->post('quote_id');
		$data['show_discount'] = $this->input->post('show_discount');
		$result = $this->quotes_model->update_data('quotes', $data, $where);
		if ($result) {
			$msg = array(
				'status' => 1,
				'msg' => 'Discount updated!'
			);
		} else {
			$msg = array(
				'status' => 0,
				'msg' => 'Error in updating discount!'
			);
		}

		echo json_encode($msg);
	}

	public function load_divisions()
	{
		$result = $this->quotes_model->get_result('division_id,division_name', 'mst_divisions', NULL);
		echo json_encode($result);
	}

	public function load_divisions_selected()
	{
		$division_ids = $this->input->post('div_ids');
		$result = $this->quotes_model->loaddivisions_selected($division_ids);
		echo json_encode($result);
	}

	public function get_products_by_division()
	{
		$data = array();
		$division_ids = $this->input->post('div_ids');
		if ($division_ids) {

			$sample_type_ids = $this->quotes_model->get_sample_data($division_ids);
			if ($sample_type_ids && count($sample_type_ids)) {
				$sample_type_ids = explode(',', $sample_type_ids->sample_type_id);
				$data = $this->quotes_model->get_sample_name_by_id_array($sample_type_ids);
			}
		}


		echo json_encode($data);
	}

	// ADD TEST CODE START

	public function load_lab_types()
	{
		$data = $this->quotes_model->get_result('lab_type_id as lab_id,lab_type_name as lab_name', 'mst_lab_type');
		echo json_encode($data);
	}

	public function load_units()
	{
		$data = $this->quotes_model->get_result('unit_id,unit as unit_name', 'units');
		echo json_encode($data);
	}

	public function load_products()
	{
		$data = $this->quotes_model->get_result('sample_type_id as id,sample_type_name as name', 'mst_sample_types');
		echo json_encode($data);
	}


	public function save_tests()
	{
		$data = $this->input->post();
		$this->form_validation->set_rules('test_division_id', 'Division', 'required');
		$this->form_validation->set_rules('test_sample_type_id', 'Product', 'required');
		$this->form_validation->set_rules('test_lab_type_id', 'Lab type', 'required');
		$this->form_validation->set_rules('test_name', 'Test Name', 'required|is_unique[tests.test_name]');
		$this->form_validation->set_rules('test_method', 'Test method', 'required|is_unique[tests.test_method]');
		$this->form_validation->set_rules('minimum_quantity', 'Min. sample Qty unit', 'required');
		$this->form_validation->set_rules('minimum_quantity_units', 'Unit', 'required');
		$this->form_validation->set_rules('test_service_type[]', 'Service Type', 'required');
		$this->form_validation->set_rules('units', 'Report Unit', 'required');
		$sub_contract = array();
		if (array_key_exists('sub_contract', $data)) {

			$sub_contract = $data['sub_contract'];
			unset($data['sub_contract']);
			$sub_contract['created_by'] = $this->user;
			$sub_contract['created_on'] = date("Y-m-d h:i:s", time());
		}


		if ($this->form_validation->run() == TRUE) {
			$data['is_available_customerportal'] = '1';
			$data['test_status'] = '1';
			$data['created_on'] = date("Y-m-d h:i:s", time());
			$data['created_by'] = $this->user;
			$service = $this->input->post('test_service_type');
			if ($service && count($service) > 0) {
				$service = implode(',', $service);
				$data['test_service_type'] = $service;
			}

			$sub_perameter = $this->crate_sub_perameter($data);
			$price_listlog = $this->pricelist_log();
			$result = $this->quotes_model->insert_test_data($data, $sub_perameter, $price_listlog, $sub_contract);
			if ($result) {
				$response = array(
					'status' => 1,
					'msg' => 'TEST ADDED SUCCESSFULLY'
				);
			} else {
				$response = array(
					'status' => 0,
					'msg' => 'ERROR IN TEST ADDING'
				);
			}
		} else {
			$response = array(
				'status' => 0,
				'msg' => 'PLEASE FILL ALL REQUIRED FIELDS',
				'errors' => $this->form_validation->error_array()
			);
		}

		echo json_encode($response);
	}

	public function crate_sub_perameter($data)
	{

		if ($data && count($data) > 0) {
			$sub = array();
			$sub['test_parameters_disp_name'] = $data['test_name'];
			$sub['test_parameters_name'] = $data['test_name'];
			$sub['type'] = 'Calculated';
			$sub['show_in_report'] = 'Yes';
			$sub['mandatory'] = 'Yes';
			$sub['formula'] = '';
			$sub['created_by'] = $this->user;
			$sub['created_on'] = date("Y-m-d h:i:s", time());
			return $sub;
		} else {
			return false;
		}
	}

	public function pricelist_log()
	{
		$plog = array();
		$plog['updated_on'] = date("Y-m-d h:i:s", time());
		$plog['updated_by'] = $this->user;
		$plog['new_price'] = 0;
		$plog['pricelist_type'] = 'Test';
		$plog['old_price'] = 0;

		return $plog;
	}

	public function show_user_log_history()
	{
		$quote_id = $this->input->post('quote_id');
		$data = $this->quotes_model->get_log_history($quote_id);
		echo json_encode($data);
	}


	public function check_approver($quote_id = NULL, $json = false)
	{
		if ($quote_id == NULL) {
			$quote_id = $this->input->post('quote_id');
		}

		$where = array();
		$where['quote_id'] = $quote_id;
		$data = $this->quotes_model->get_row('quotes_signing_authority_id', 'quotes', $where);
		if ($data && count($data) > 0) {
			$aprrover = $data->quotes_signing_authority_id;
		} else {
			$aprrover = false;
		}

		if ($aprrover && $aprrover == $this->user) {
			$data = true;
		} else {
			$data = false;
		}
		if ($json) {
			return $data;
		} else {
			echo json_encode($data);
		}
	}


	public function get_package_protocol_data_testing()
	{
		$result = false;
		$id = $this->input->post('id');
		$data['currency_id'] = $this->input->post('currency_id');
		$data['type'] = $this->input->post('type');
		if (!empty($id)) {
			$result = $this->quotes_model->get_tests_by_pac_id($id, $data);
		}
		echo json_encode($result);
	}

	public function excel_export()
	{

		$data = $this->session->userdata('excel_query');

		if ($data && count($data) > 0) {
			$this->load->library('excel');
			$tmpfname = "example.xls";

			$excelReader = PHPExcel_IOFactory::createReaderForFile($tmpfname);
			$objPHPExcel = $excelReader->load($tmpfname);

			$objPHPExcel->getProperties()->setCreator("GEO-CHEM")
				->setLastModifiedBy("GEO-CHEM")
				->setTitle("Office 2007 XLS Quotes Document")
				->setSubject("Office 2007 XLS Quotes Document")
				->setDescription("Description for Quotes Document")
				->setKeywords("phpexcel office codeigniter php")
				->setCategory("Quotes details file");


			$objPHPExcel->setActiveSheetIndex(0);
			$objPHPExcel->getActiveSheet()->setCellValue('A2', "SL NO.");
			$objPHPExcel->getActiveSheet()->setCellValue('B2', "CUSTOMER");
			$objPHPExcel->getActiveSheet()->setCellValue('C2', "QUOTE REF NO.");
			$objPHPExcel->getActiveSheet()->setCellValue('D2', "QUOTE DATE");
			$objPHPExcel->getActiveSheet()->setCellValue('E2', "QUOTE VALUE");
			$objPHPExcel->getActiveSheet()->setCellValue('F2', "QUOTE STATUS");
			$objPHPExcel->getActiveSheet()->setCellValue('G2', "CREATED BY");
			$objPHPExcel->getActiveSheet()->setCellValue('H2', "CREATED ON");

			$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
			$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
			$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
			$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
			$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
			$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
			$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
			$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);



			$i = 3;
			foreach ($data as $key => $value) {

				$objPHPExcel->getActiveSheet()->setCellValue('A' . $i, ($i - 2));
				$objPHPExcel->getActiveSheet()->setCellValue('B' . $i, ($value->customer_name) ? $value->customer_name : '');
				$objPHPExcel->getActiveSheet()->setCellValue('C' . $i, ($value->reference_no) ? $value->reference_no : '');
				$objPHPExcel->getActiveSheet()->setCellValue('D' . $i, ($value->quote_date) ? $value->quote_date : '');
				$objPHPExcel->getActiveSheet()->setCellValue('E' . $i, ($value->quote_value) ? $value->quote_value : '');
				$objPHPExcel->getActiveSheet()->setCellValue('F' . $i, ($value->quote_status) ? $value->quote_status : '');
				$objPHPExcel->getActiveSheet()->setCellValue('G' . $i, ($value->created_by) ? $value->created_by : '');
				$objPHPExcel->getActiveSheet()->setCellValue('H' . $i, ($value->created_on) ? $value->created_on : '');
				$i++;
			}

			// Set Font Color, Font Style and Font Alignment
			$stil = array(
				'borders' => array(
					'allborders' => array(
						'style' => PHPExcel_Style_Border::BORDER_THIN,
						'color' => array('rgb' => '000000')
					)
				),
				'alignment' => array(
					'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
				)
			);
			// Merge Cells
			$objPHPExcel->getActiveSheet()->mergeCells('A1:H1');
			$objPHPExcel->getActiveSheet()->setCellValue('A1', "GEO CHEM QUOTES DETAILS");
			$objPHPExcel->getActiveSheet()->getStyle('A1:H1')->applyFromArray($stil);

			$filename = 'Quotes_details-' . date('Y-m-d-s') . ".xls";
			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
			ob_end_clean();
			header('Content-type: application/vnd.ms-excel');
			header('Content-Disposition: attachment; filename=' . $filename);
			$objWriter->save('php://output');
		}
	}


	public function generate_details()
	{
		$quote_id = $this->input->post('quote_id');
		if (!empty($quote_id)) {
			$where = array('quote_id' => $quote_id);
			$result = $this->quotes_model->get_generate_details($where);
			$result->additional_notes = html_entity_decode($result->additional_notes);
		} else {
			$result = false;
		}

		echo json_encode($result);
	}

	public function  get_gst_calculation_for_quotes($branch_country_id, $non_taxable, $country_id, $state, $amt, $currency_decimal, $list = NULL)
	{


		$branch_country_code = $this->quotes_model->get_row('country_code', 'mst_country', ['country_id' => $branch_country_id]);
		if ($branch_country_code && count($branch_country_code) > 0) {
			$branch_country_code = $branch_country_code->country_code;
		} else {
			$branch_country_code = NULL;
		}

		$customer_country_code = $this->quotes_model->get_row('country_code', 'mst_country', ['country_id' => $country_id]);
		if ($customer_country_code && count($customer_country_code) > 0) {
			$customer_country_code = $customer_country_code->country_code;
		} else {
			$customer_country_code = NULL;
		}



		$IGST = $this->quotes_model->get_fields_by_id("sys_configuration", "cfg_Value", "IGST", "cfg_Name")[0]['cfg_Value'];

		if (empty($IGST)) {
			$IGST = 0;
		}

		$SGST = $this->quotes_model->get_fields_by_id("sys_configuration", "cfg_Value", "SGST", "cfg_Name")[0]['cfg_Value'];

		if (empty($SGST)) {
			$SGST = 0;
		}

		$CGST = $this->quotes_model->get_fields_by_id("sys_configuration", "cfg_Value", "CGST", "cfg_Name")[0]['cfg_Value'];

		if (empty($CGST)) {
			$CGST = 0;
		}

		$UTGST = $this->quotes_model->get_fields_by_id("sys_configuration", "cfg_Value", "UTGST", "cfg_Name")[0]['cfg_Value'];

		if (empty($UTGST)) {
			$UTGST = 0;
		}

		$VAT = $this->quotes_model->get_fields_by_id("sys_configuration", "cfg_Value", "VAT", "cfg_Name")[0]['cfg_Value'];

		if (empty($VAT)) {
			$VAT = 0;
		}

		$BDT_VAT = $this->quotes_model->get_fields_by_id("sys_configuration", "cfg_Value", "BDT_VAT", "cfg_Name")[0]['cfg_Value'];

		if (empty($BDT_VAT)) {
			$BDT_VAT = 0;
		}

		$templateVars['TAX'] = '';
		$templateVars['TAX'] .= '<tr><td width="50%"></td><td></td><td></td><td>Total Amount</td><td>' . number_format($amt, $currency_decimal) . '</td></tr><br>';


		if ($non_taxable == 0) {

			if ($branch_country_code == 'IND' && $customer_country_code == 'IND') {
				if ($this->quotes_model->gstCalculation_quotes($state, 'IGST', $amt, $IGST, $branch_country_code) > 0) {
					$gst = $this->quotes_model->gstCalculation_quotes($state, 'IGST', $amt, $IGST, $branch_country_code);
					$templateVars['TAX'] .= '<tr><td></td><td></td><td></td><td>IGST @ ' . $IGST . '%</td><td>' . number_format($gst, $currency_decimal) . '</td></tr><br>';
				}
				if ($this->quotes_model->gstCalculation_quotes($state, 'SGST', $amt, $SGST, $branch_country_code) > 0) {
					$gst = $this->quotes_model->gstCalculation_quotes($state, 'SGST', $amt, $SGST, $branch_country_code);
					$templateVars['TAX'] .= '<tr><td></td><td></td><td></td><td>SGST @ ' . $SGST . '%</td><td>' . number_format($gst, $currency_decimal) . '</td></tr><br>';
					$gst = (2 * $gst);
				}
				if ($this->quotes_model->gstCalculation_quotes($state, 'CGST', $amt, $CGST, $branch_country_code) > 0) {


					$templateVars['TAX'] .= '<tr><td></td><td></td><td></td><td>CGST @ ' . $CGST . '%</td><td>' . number_format($this->quotes_model->gstCalculation_quotes($state, 'CGST', $amt, $CGST), $currency_decimal) . '</td></tr><br>';
				}
				if ($this->quotes_model->gstCalculation_quotes($state, 'UTGST', $amt, $UTGST, $branch_country_code)) {
					$gst = $this->quotes_model->gstCalculation_quotes($state, 'UTGST', $amt, $UTGST, $branch_country_code);

					$templateVars['TAX'] .= '<tr><td></td><td></td><td></td><td>UTGST @ ' . $UTGST . '%</td><td>' . number_format($gst, $currency_decimal) . '</td></tr><br>';
				}
			} else if (($branch_country_code == 'UAE' && $customer_country_code == 'UAE') || ($branch_country_code == 'AE' && $customer_country_code == 'AE')) {
				if ($this->quotes_model->gstCalculation_quotes($state, 'VAT', $amt, $VAT, $branch_country_code)) {
					$gst = $this->quotes_model->gstCalculation_quotes($state, 'VAT', $amt, $VAT, $branch_country_code);
					$templateVars['TAX'] .= '<tr><td></td><td></td><td></td><td>VAT @ ' . $VAT . '%</td><td>' . number_format($gst, $currency_decimal) . '</td></tr><br>';
				}
			} else if ($branch_country_code == 'BAD' && $customer_country_code == 'BAD') {

				if ($this->quotes_model->gstCalculation_quotes($state, 'VAT', $amt, $BDT_VAT, $branch_country_code)) {
					$gst = $this->quotes_model->gstCalculation_quotes($state, 'VAT', $amt, $BDT_VAT, $branch_country_code);
					$templateVars['TAX'] .= '<tr><td></td><td></td><td></td><td>VAT @ ' . $BDT_VAT . '%</td><td>' . number_format($gst, $currency_decimal) . '</td></tr><br>';
				}
			} else {


				$gst = round($amt * 5 / 100);

				$templateVars['TAX'] .= '<tr><td></td><td></td><td></td><td>VAT @ ' . $VAT . '%</td><td>' . number_format($gst, $currency_decimal) . '</td></tr><br>';
			}
		}

		$templateVars['TAX'] .= '<tr><td></td><td></td><td></td><td>_________________</td><td>_________________</td></tr><br>';
		$templateVars['TAX'] .= '<tr><td></td><td></td><td></td><td>Total (inc.tax)</td><td>' . (number_format(($amt + $gst), $currency_decimal)) . '</td></tr>';

		if ($list != NULL) {
			return $gst;
		} else {
			return $templateVars;
		}
	}

	// SEND APPROVAL TO BHARAT SAXENA 

	public function send_approval_by_mail($quote_id)
	{

		$approver = $this->quotes_model->get_approver_by_quote_id($quote_id);
		// print_r($approver); die;
		if ($approver && count($approver) > 0) {

			$approver_email = $approver->email;
			$approver_id = $approver->id;
			if ($approver_id == QUOTE_APPROVER) {
				$aws_condition = 'F';
				$file_path = $this->GeneratePDF_quotes($quote_id, $aws_condition);
				// pre_r($file_path); die;
				if (!empty($file_path)) {
					$msg = '<table>';
					$msg = 	'Dear Sir</br></br>';
					$msg .= 'The following Quotation is generated.</br></br>';
					$msg .= 'Check Quotation pdf </br></br>';
					$msg .= 'This Quotation is created by ' . $approver->created_by . '. This Quotation is waiting for your Approval please Approve it as soon as possible by clicking below mentioned Approve link</br></br>';

					$msg .= '<a href="' . base_url('Approve_quotes/index/' . base64_encode($quote_id)) . '/' . base64_encode($approver_id) . '" style="background-color:blue;color:white">Approve</a>';
					$msg .= '<table>';

					$result = send_mail_function($approver_email, NULL, NULL, $msg, 'APPROVAL OF QUOTATION', NULL, $file_path, false);

					if ($result) {
						$this->unlink_file($file_path);
						$msg = ['status' => 1, 'msg' => 'Quote Approved Successfully and approval sent !'];
						return $msg;
					} else {
						return false;
					}
				} else {
					return false;
				}
			} else {
				$msg = ['status' => 1, 'msg' => 'Quote Approved Successfully'];
				return $msg;
			}
		}




		// CODE END
	}

	public function unlink_file($file_path)
	{
		if (file_exists($file_path)) {
			return	unlink($file_path);
		} else {
			return false;
		}
	}

	public function update_customer_acceptance()
	{
		$this->form_validation->set_rules('approval_status', 'Approval Status', 'required');
		if ($this->form_validation->run()) {
			$quote_id = $this->input->post('quote_id');
			$data_array = array(
				'client_comment'	=> $this->input->post('client_comment'),
				'quote_status'		=> $this->input->post('approval_status')
			);
			$update = $this->quotes_model->update_data('quotes', $data_array, ['quote_id' => $quote_id]);
			if ($update) {
				echo json_encode(['status' => 1, 'message' => 'Data saved successfully.']);
			} else {
				echo json_encode(['status' => 0, 'message' => 'Something went wrong!.']);
			}
		} else {
			echo json_encode(['status' => 0, 'message' => 'Validation Error', 'errors' => $this->form_validation->error_array()]);
		}
	}

	// Added by Saurabh on 07-06-2022
	public function fetch_all_quotation()
	{
		/**
		 * Fetch all quotation
		 */
		$fetch_quotes = $this->db->select('quote_id')->get('quotes')->result_array();
		foreach ($fetch_quotes as $quote) {
			/**
			 * Select unique data
			 */
			$fetch_works = $this->db->select('works_customer_id, works_sample_type_id, product_type, work_job_type_id')
				->where('work_job_type_id', $quote['quote_id'])
				->distinct()
				->get('works')
				->result_array();

			foreach ($fetch_works as $works) {
				/**
				 * Select work id according to unique data
				 */
				echo "<pre>";
				echo "Select unique ID";
				$work_id = $this->db->select('work_id, product_type, works_sample_type_id, work_job_type_id')
					->where('works_customer_id', $works['works_customer_id'])
					->where('works_sample_type_id', $works['works_sample_type_id'])
					->where('work_job_type_id', $works['work_job_type_id'])
					->where('product_type', $works['product_type'])
					// ->group_by('product_type')
					->get('works');
				$result = $work_id->result_array();
				foreach ($result as $key => $data) {
					if ($key > 0) {
						/**
						 * Set status deleted for the duplicate records
						 */
						$this->db->update('works', ['is_deleted' => 1], ['work_id' => $data['work_id']]);
						echo "<pre>";
						echo $this->db->last_query();
					}
				}
			}
		}
		/**
		 * Select work id for the deleted records
		 */
		echo "<pre>";
		echo "Selected Deleted Work IDS";
		$select_deleted = $this->db->select('work_id, work_job_type_id, works_sample_type_id, is_deleted')->where('is_deleted', 1)->where('product_type', 'Test')->get('works')->result_array();
		foreach ($select_deleted as $del) {
			$active = $this->db->select('work_id, is_deleted, product_type, work_job_type_id, works_sample_type_id')
				->where('work_job_type_id', $del['work_job_type_id'])
				->where('works_sample_type_id', $del['works_sample_type_id'])
				->where('is_deleted', 0)
				->where('product_type', 'Test')
				->get('works');
			$result = $active->row_array();
			/**
			 * Update work id for the deleted records
			 */
			$this->db->update('works_analysis_test', ['work_id' => $result['work_id']], ['work_id' => $del['work_id']]);
			echo "<pre>";
			echo $this->db->last_query();
		}

		/**
		 * Process to set Package Data
		 */
		echo "<pre>";
		echo "Update Active Record IDS For Package";
		$select_deleted = $this->db->select('work_id, work_job_type_id, works_sample_type_id, is_deleted')->where('is_deleted', 1)->where('product_type', 'Package')->get('works')->result_array();
		foreach ($select_deleted as $del) {
			$active = $this->db->select('work_id, is_deleted, product_type, work_job_type_id, works_sample_type_id')
				->where('work_job_type_id', $del['work_job_type_id'])
				->where('works_sample_type_id', $del['works_sample_type_id'])
				->where('is_deleted', 0)
				->where('product_type', 'Package')
				->get('works');
			$result = $active->row_array();
			/**
			 * Update work id for the deleted records
			 */
			$this->db->update('works_analysis_package', ['work_id' => $result['work_id']], ['work_id' => $del['work_id']]);
			$this->db->update('work_analysis_test_parameters', ['work_id' => $result['work_id']], ['work_id' => $del['work_id']]);
			echo "<pre>";
			echo $this->db->last_query();
		}
		/**
		 * Process to set Protocol Data
		 */
		echo "<pre>";
		echo "Update Active Record IDS For Protocol";
		$select_deleted = $this->db->select('work_id, work_job_type_id, works_sample_type_id, is_deleted')->where('is_deleted', 1)->where('product_type', 'Protocol')->get('works');
		$deleted_records = $select_deleted->result_array();
		foreach ($deleted_records as $del) {
			$active = $this->db->select('work_id, is_deleted, product_type, work_job_type_id, works_sample_type_id')
				->where('work_job_type_id', $del['work_job_type_id'])
				->where('works_sample_type_id', $del['works_sample_type_id'])
				->where('is_deleted', 0)
				->where('product_type', 'Protocol')
				->get('works');
			$result = $active->row_array();
			/**
			 * Update work id for the deleted records
			 */
			$this->db->update('works_analysis_package', ['work_id' => $result['work_id']], ['work_id' => $del['work_id']]);
			$this->db->update('work_analysis_test_parameters', ['work_id' => $result['work_id']], ['work_id' => $del['work_id']]);
			echo "<pre>";
			echo $this->db->last_query();
			/**
			 * Delete redundant work ids
			 */
			// $delete = $this->db->delete('works',['is_deketed' => 1]);
			/**
			 * Bring Package and Protocol price and id in the works table
			 */
			echo "<pre>";
			echo "Update works table For Packages and Protocols";
			$get_work_ids_query = $this->db->select('work_id')
				->where('is_deleted', 0)
				->group_start()
				->where('product_type', 'Package')
				->or_where('product_type', 'Protocol')
				->group_end()
				->get('works');
			$get_work_ids = $get_work_ids_query->result_array();
			foreach ($get_work_ids as $ids) {
				$package_protocol_price_query = $this->db->select('work_id, work_package_id, rate_per_package, original_cost,total_cost')->where('work_id', $ids['work_id'])->get('works_analysis_package');
				if ($package_protocol_price_query->num_rows() > 0) {
					$package_protocol_price = $package_protocol_price_query->row_array();
					$data_array = [
						'total_cost' 			=> $package_protocol_price['total_cost'],
						'product_type_id'		=> $package_protocol_price['work_package_id'],
						'rate_per_package'		=> $package_protocol_price['rate_per_package'],
						'original_cost'			=> $package_protocol_price['original_cost']
					];
					$this->db->update('works', $data_array, ['work_id' => $ids['work_id']]);
					/**
					 * Bring parameters data in works_analysis_test table
					 */
					$work_analysis_test_parameters = $this->db->select('parameter_id')->where('work_id', $ids['work_id'])->get('work_analysis_test_parameters')->row_array();
					$test_data = [
						'work_id'			=> $ids['work_id'],
						'work_test_id'		=> $work_analysis_test_parameters['parameter_id']
					];
					$insert_test_data = $this->db->insert('works_analysis_test', $test_data);
					echo "<pre>";
					echo $this->db->last_query();
				}
			}
		}
	}

	// Added by Saurabh on 16-06-2022 to get quote contact person
	public function get_contact_division()
	{
		$key = ($this->input->get('key')) ? $this->input->get('key') : NULL;
		$data = $this->quotes_model->get_contact_division($key);
		echo json_encode($data);
	}

	// Added by Saurabh on 22-06-2022 to clone quotation
	public function clone_quotation(){
		$quote_id = $this->input->post('quote_id');
		// Get Data of Quotation
		$quote_data = $this->quotes_model->get_data_by_id_array('quotes','*',$quote_id,'quote_id');
		$quote_array = [
			'customer_type'								=> $quote_data['customer_type'],
			'quotes_customer_id'						=> $quote_data['quotes_customer_id'],
			'quote_subject'								=> $quote_data['quote_subject'],
			'salutation'								=> $quote_data['salutation'],
			'terms_conditions'							=> $quote_data['terms_conditions'],
			'additional_notes'							=> $quote_data['additional_notes'],
			'quote_discount'							=> $quote_data['quote_discount'],
			'common_discount'							=> $quote_data['common_discount'],
			'discount_type'								=> $quote_data['discount_type'],
			'quotes_currency_id'						=> $quote_data['quotes_currency_id'],
			'quotes_country_id'							=> $quote_data['quotes_country_id'],
			'quotes_branch_id'							=> $quote_data['quotes_branch_id'],
			'quote_signing_authority_designation_id'	=> $quote_data['quote_signing_authority_designation_id'],
			'quotes_signing_authority_id'				=> $quote_data['quotes_signing_authority_id'],
			'quote_status'								=> 'Draft',
			'created_on'								=> date("Y-m-d H:i:s"),
			'created_by'								=> $this->admin_id(),
			'quote_value'								=> $quote_data['quote_value'],
			'quote_value_in_base_currency'				=> $quote_data['quote_value_in_base_currency'],
			'quote_date'								=> $quote_data['quote_date'],
			'quotes_opportunity_id'						=> $quote_data['quotes_opportunity_id'],
			'quotes_opportunity_name'					=> $quote_data['quotes_opportunity_name'],
			'quotes_contact_id'							=> $quote_data['quotes_contact_id'],
			'sample_retention'							=> $quote_data['sample_retention'],
			'job_no_po'									=> $quote_data['job_no_po'],
			'quote_valid_date'							=> $quote_data['quote_valid_date'],
			'sub_para_req'								=> $quote_data['sub_para_req'],
			'disc_show_req'								=> $quote_data['disc_show_req'],
			'template_id'								=> $quote_data['template_id'],
			'quote_exchange_rate'						=> $quote_data['quote_exchange_rate'],
			'quote_total_charge'						=> $quote_data['quote_total_charge'],
			'payment_terms'								=> $quote_data['payment_terms'],
			'generated_preview'							=> $quote_data['generated_preview'],
			'discussion_date'							=> $quote_data['discussion_date'],
			'deactivate_text'							=> $quote_data['deactivate_text'],
			'show_discount'								=> $quote_data['show_discount'],
			'buyer_self_ref'							=> $quote_data['buyer_self_ref'],
			'allow_about_us'							=> $quote_data['allow_about_us'],
			'about_us_details'							=> $quote_data['about_us_details'],
			'notes_details'								=> $quote_data['notes_details'],
			'terms_details'								=> $quote_data['terms_details'],
			'show_test_method'							=> $quote_data['show_test_method'],
			'contact_details'							=> $quote_data['contact_details'],
			'show_price'								=> $quote_data['show_price'],
			'introduction'								=> $quote_data['introduction'],
			'show_division'								=> $quote_data['show_division'],
			'show_total_amount'							=> $quote_data['show_total_amount'],
			'show_products'								=> $quote_data['show_products'],
			'client_comment'							=> $quote_data['client_comment'],
			'attach_file'								=> $quote_data['attach_file']
		];
		$confiq['mst_branch_id'] = $where2['branch_id'] = $quote_data['quotes_branch_id'];
		$this->db->select_max('mst_serail_no');
		$this->db->from('mst_quote_seral_number');
		$this->db->where('mst_branch_id', $quote_data['quotes_branch_id']);
		$result =  $this->db->get()->row();
		$quote_number_q = $result->mst_serail_no;
        $quote_number_q = $quote_number_q + 1;
        $rand = str_pad($quote_number_q, 5, "0", STR_PAD_LEFT);
        $confiq['mst_serail_no'] = $quote_number_q;
        $confiq['created_on'] = date("Y-m-d H:i:s");
        $confiq['created_by'] = $this->user;
        $result = $this->db->insert('mst_quote_seral_number', $confiq);
		$branch = $this->quotes_model->get_row('branch_code', 'mst_branches', $where2);
		$quote_array['reference_no'] = 'GC/' . $branch->branch_code . '/' . date('Y') . '/' . $rand;
		$save = $this->quotes_model->insert_data('quotes',$quote_array);
		// Get data from works table
		$works_data = $this->quotes_model->get_fields_by_id('works','*',$quote_id,'work_job_type_id');
		if(!empty($works_data)){
			foreach($works_data as $works){
				unset($works['work_job_type_id']);
				$work_id = $works['work_id'];
				unset($works['work_id']);
				$works['work_job_type_id'] = $save;
				$works['reference_no'] = $quote_array['reference_no'];
				$works_id = $this->quotes_model->insert_data('works',$works);
				//  Get Data from work_analysis_test
				$test_data = $this->quotes_model->get_fields_by_id('works_analysis_test','*',$work_id,'work_id');
				if(!empty($test_data)){
					foreach($test_data as $test){
						unset($test['work_id']);
						unset($test['works_analysis_test_id']);
						$test['work_id'] = $works_id;
						$this->quotes_model->insert_data('works_analysis_test',$test);
					}
				}
			}
		}
		if($save){
			echo json_encode(['status' => 1,'message' => 'Quote cloned successfully.']);
		} else {
			echo json_encode(['status' => 0,'message' => 'Something went wrong!.']);
		}
	} 

	public function get_contact_details(){
		$division_id = $this->input->post('contact_division_id');
		$data = $this->quotes_model->get_data_by_id_array('quote_contact_details', 'contact_person_details', $division_id, 'division');
		echo json_encode($data);
	}

	public function save_package_protocol(){
		$to_add = $this->input->post('to_add');
		$protocol_name = $this->input->post('protocol_name');	 
		$protocol_price = $this->input->post('protocol_price');	 
		$product_id = $this->input->post('product_id');	 
		$test_ids = explode(',',$this->input->post('test_ids'));	
		$currency_id = $this->input->post('currency_id'); 
		$test_price = $this->input->post('test_price'); 
		$save = false;
		// Set protocol data
		if($to_add == '2'){
			$check_duplicate = $this->db->where('protocol_name',$protocol_name)->get('protocols')->num_rows();
			if($check_duplicate > 0){
				echo json_encode(['status' => 0,'message' => 'Protocol already exists!.']);
				exit;
			} else {
				$protocol_data = array(
					'protocol_name'				=> $protocol_name,
					'protocol_price'			=> $protocol_price,
					'protocol_type'				=> 'GoeChem CPS',
					'protocol_sample_type_id'	=> $product_id
				);
				$save = $this->db->insert('protocols',$protocol_data);
				$protocol_id = $this->db->insert_id();
				foreach($test_ids as $test_id){
					$method =  $this->db->get_where('tests',['test_id' => $test_id])->row_array();
					// echo "<pre>"; print_r($test_id);
					$test_array = array(
						'protocol_id'				=> $protocol_id,
						'protocol_test_id'			=> $test_id,
						'protocol_test_method'		=> $method['test_method'],
						'protocol_test_sort_order'	=> 1,
						'currency_id'				=> $currency_id,
						'test_price'				=> $test_price
					);
					$this->db->insert('protocol_tests',$test_array);
				}
				// Save price data
				$price_data = array(
					'type'					=> 'Protocol',
					'type_id'				=> $protocol_id,
					'currency_id'			=> $currency_id,
					'price'					=> $protocol_price
				);
				$this->db->insert('pricelist',$price_data);
			}
		} 
		if($to_add == '1'){
			$check_duplicate = $this->db->where('package_name',$protocol_name)->get('packages')->num_rows();
			if($check_duplicate > 0){
				echo json_encode(['status' => 0,'message' => 'Package already exists!.']);
				exit;
			} else {
				$package_data = array(
					'package_name'					=> $protocol_name,
					'package_type'					=> 1,
					'packages_sample_type_id'		=> $product_id,
					'package_status'				=> 1,
					'created_on'					=> date('Y-m-d H:i:s'),
					'created_by'					=> $this->admin_id(),
					'package_price'					=> $protocol_price
				);
				$save = $this->db->insert('packages',$package_data);
				$protocol_id = $this->db->insert_id();
				foreach($test_ids as $test_id){
					$method =  $this->db->get_where('tests',['test_id' => $test_id])->row_array();
					$test_array = array(
						'test_package_packages_id'	=> $protocol_id,
						'test_package_test_id'		=> $test_id,
						'package_test_method'		=> $method['test_method'],
						'package_test_sort_order'	=> 1,
						'test_package_division_id'	=> 0,
						'test_package_lab_id'		=> 0,
						'created_on'				=> date('Y-m-d H:i:s'),
						'created_by'				=> $this->admin_id(),
						'currency_id'				=> $currency_id,
						'test_price'				=> $test_price
					);
					$this->db->insert('test_packages',$test_array);
				}
				// Save price data
				$price_data = array(
					'type'					=> 'Package',
					'type_id'				=> $protocol_id,
					'currency_id'			=> $currency_id,
					'price'					=> $protocol_price
				);
				$this->db->insert('pricelist',$price_data);
			}
		}
		if($save){
			echo json_encode(['status' => 1,'message' => 'Data saved successfully.','package_id' => $protocol_id]);
		} else {
			echo json_encode(['status' => 0,'message' => 'Something went wrong!.']);
		}
	}
}
