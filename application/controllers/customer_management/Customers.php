<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Customers extends MY_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('customer_management_model/Customers_model', 'customers_model');
		$this->check_session();
		$checkUser = $this->session->userdata('user_data');
		$this->user = $checkUser->uidnr_admin;
		$this->form_validation->set_error_delimiters('<div class="error" style="color:red">', '</div>');
	}

	public function index()
	{
		$where = NULL;
		$from_date = NULL;
		$to_date = NULL;
		$customer_id = $this->uri->segment('4');
		$customer_type = $this->uri->segment('5');
		$accope_customer = $this->uri->segment('6');
		$weekly_yes = $this->uri->segment('7');
		$created_by = $this->uri->segment('8');
		$from_date = $this->uri->segment('9');
		$to_date = $this->uri->segment('10');
		$search = $this->uri->segment('11');
		$state = $this->uri->segment('12');
		$city = $this->uri->segment('13');
		$sortby = $this->uri->segment('14');
		$order = $this->uri->segment('15');
		$page_no = $this->uri->segment('16');

		$base_url = 'customer_management/Customers/index';

		if ($customer_id != '' && $customer_id != 'NULL') {
			$base_url .= '/' . $customer_id;
			$where['cust.customer_id'] = $customer_id;
			$data['customer_id'] = $customer_id;
			$data['customer_name'] = $this->customers_model->get_row('customer_name', 'cust_customers', 'customer_id' . '=' . $where['cust.customer_id']);
		} else {
			$base_url .= '/NULL';
			$data['customer_id'] = NULL;
			$data['customer_name'] = NULL;
		}


		if ($customer_type != '' && $customer_type != 'NULL') {
			$base_url .= '/' . $customer_type;
			$data['customer_type'] = $customer_type;
			$where['cust.customer_type'] = $customer_type;
		} else {
			$base_url .= '/NULL';
			$data['customer_type'] = NULL;
		}

		if ($accope_customer != '' && $accope_customer != 'NULL') {
			$base_url .= '/' . $accope_customer;
			$data['accope_customer'] = $accope_customer;
			$where['cust.accop_cust'] = $accope_customer;
		} else {
			$base_url .= '/NULL';
			$data['accope_customer'] = NULL;
		}

		if ($weekly_yes != '' && $weekly_yes != 'NULL') {
			$base_url .= '/' . $weekly_yes;
			$data['weekly_yes'] = $weekly_yes;
			$where['cust.sample_week_report'] = $weekly_yes;
		} else {
			$base_url .= '/NULL';
			$data['weekly_yes'] = NULL;
		}
		if ($created_by != NULL  && $created_by != 'NULL') {
			$data['created_by'] = base64_decode($created_by);
			$base_url  .= '/' . $created_by;
			$where['admin.uidnr_admin'] = base64_decode($created_by);
		} else {
			$data['created_by'] = 'NULL';
			$base_url  .= '/NULL';
		}

		if ($from_date != NULL && $from_date != 'NULL') {
			$base_url .= '/' . $from_date;
			$data['from_date'] =  base64_decode($from_date);
			$from_date = base64_decode($from_date);
			$where["DATE(cust.created_on) >= '" . $from_date . "' "] = null;
		} else {
			$base_url .= '/NULL';
			$data['from_date'] = NULL;
		}

		if ($to_date != NULL && $to_date != 'NULL') {
			$base_url .= '/' . $to_date;
			$data['to_date'] =  base64_decode($to_date);
			$to_date = base64_decode($to_date);
			$where["DATE(cust.created_on) <= '" . $to_date . "'"] = null;
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
		if ($state != '' && $state != 'NULL') {
			$base_url .= '/' . $state;
			$data['state_id'] = base64_decode($state);
			$where['cust.cust_customers_province_id'] = base64_decode($state);
		} else {
			$base_url .= '/NULL';
			$data['state_id'] = NULL;
		}

		if ($city != '' && $city != 'NULL') {
			$base_url .= '/' . $city;
			$data['city'] = base64_decode($city);
			$where['cust.cust_customers_location_id'] = base64_decode($city);
		} else {
			$base_url .= '/NULL';
			$data['city'] = NULL;
		}

		if ($sortby != NULL && $sortby != 'NULL') {
			$base_url .= '/' . $sortby;
			$sortby = base64_decode($sortby);
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

		$count = 1;
		$total_row = $this->customers_model->customers_list(NULL, NULL, $search, NULL, NULL, $where, '1');
		$config = $this->pagination($base_url, $total_row, 10, 16);
		$data["links"] = $config["links"];
		$data['customers_list'] = $this->customers_model->customers_list($config["per_page"], $config['page'], $search, $sortby, $order, $where);
		$data['created_by_name'] = $this->customers_model->fetch_created_person();
		$data['states'] = $this->customers_model->fetch_state_list();
		$data['cities'] = $this->customers_model->fetch_city();
		// excel export update 12-04-2021
		$this->customers_model->customers_list(NULL, NULL, $search, $sortby, $order, $where);
		$this->session->set_userdata('excel_query', $this->db->last_query());
		// end

		$start = (int)$page_no + 1;
		$end = (($data['customers_list']) ? count($data['customers_list']) : 0) + (($page_no) ? $page_no : 0);
		$data['result_count'] = "Showing " . $start . " - " . $end . " of " . $total_row . " Results";


		if ($order == NULL || $order == 'NULL') {
			$data['order'] = ($order) ? "DESC" : "ASC";
		} else {
			$data['order'] = ($order == "ASC") ? "DESC" : "ASC";
		}
		// echo "<pre>"; print_r($data); die;
		$this->load_view('customer_management/customers', $data);
	}

	// Added by CHANDAN --14-07-2022
	private function _addUpdateCustomerERPSync($data, $nav_customer_code)
	{
		$cust_type = (isset($data['cust_type']) && ($data['cust_type'] == 'Registered')) ? 1 : 0;
		// $gstin=((array_key_exists('cust_type', $data)&&($cust_type==0))?$data['gstin']:"N/A");
		$gstin = (array_key_exists('gstin', $data) && ($cust_type == 1)) ? $data['gstin'] : "";
		$address1 = substr($data['address'], 0, 50);
		$address2 = substr($data['address'], 50, 50);
		$address3 = substr($data['address'], 100, 50);

		$stateData = $this->customers_model->get_row('state_code', 'mst_provinces', ['province_id' => $data['cust_customers_province_id']]);

		$state_code = (isset($stateData->state_code) && !empty($stateData->state_code)) ? $stateData->state_code : 'OI';

		if (isset($data['regi_type'])) {
			if ($data['regi_type'] == 'GSTIN') {
				$regi_type = 1;
			} else if ($data['regi_type'] == 'UID') {
				$regi_type = 2;
			} else {
				$regi_type = 3;
			}
		} else {
			$regi_type = 0;
		}

		// echo "<pre>"; print_r($data); die;
		$post_data = array(
			"cust_Type" 				=> $cust_type,
			"customerName" 				=> $data['customer_name'],
			"emailId" 					=> $data['email'],
			"cust_Country" 				=> $data['cust_customers_country_id'],
			"customer_City" 			=> $data['city'],
			"cust_Credit" 				=> "",	// INT
			"creditLimit" 				=> (!empty($data['credit_limit'])) ? $data['credit_limit'] : "",
			"nav_Cust_Code" 			=> (!empty($nav_customer_code)) ? $nav_customer_code : "",
			"newCreation" 				=> (!empty($nav_customer_code)) ? "FALSE" : "TRUE",
			"address" 					=> $address1,
			"address2" 					=> $address2,
			"postCode" 					=> $data['po_box'],
			"customerContact" 			=> $data['mobile'],
			"statecode" 				=> $state_code,
			"phoneNo" 					=> $data['mobile'],
			"faxNo" 					=> "",
			"blocked" 					=> "FALSE",
			"PANNO" 					=> (!empty($data['pan'])) ? $data['pan'] : "",
			// "paymentTermCode" 			=> (!empty($data['credit'])) ? $data['credit'] : "",
			// "paymentTermCode" 			=> "",
			// "gSTRegNO" 					=> $data['gstin'],
			"gSTRegNO" 					=> $gstin,
			"gSTRegType" 				=> $regi_type,
			"gSTCustType" 				=> $cust_type,
			"eCommerOper" 				=> "",
			"natureofServise" 			=> "",
			"customerType" 				=> "",
			"parentGroup" 				=> "",
			"classificationOfComm" 		=> "",
			"customerCate" 				=> "",
			"periodicofBilling" 		=> "",
			"importantComplianceTerm" 	=> "",
			"billingCycle" 				=> "",
			"renewalConditions" 		=> "",
			"tDSCertificateReceivable" 	=> "",
			"tANNo" 					=> (!empty($data['tan'])) ? $data['tan'] : "",
			"testLab" 					=> "",
			"eMD" 						=> "",
			"eMDParentGroup" 			=> "",
			"eMDAmount" 				=> "",
			"noteModified" 				=> date('Y-m-d'),
			"address3" 					=> $address3
		);

		if ($data['credit'] == "Advance") {
			$post_data['paymentTermCode'] = "ADV";
		} else if ($data['credit'] == "14 Days") {
			$post_data['paymentTermCode'] = "14 DAYS";
		} else if ($data['credit'] == "30 Days") {
			$post_data['paymentTermCode'] = "30 DAYS";
		} else if ($data['credit'] == "60 Days") {
			$post_data['paymentTermCode'] = "60 DAYS";
		} else if ($data['credit'] == "90 Days") {
			$post_data['paymentTermCode'] = "90 DAYS";
		} else {
			$post_data['paymentTermCode'] = "";
		}
		$post_data = json_encode($post_data);
		// echo "<pre>"; print_r($post_data); die;
		$url = (empty($nav_customer_code)) ? ERP_ADD_CUSTOMER_URL : ERP_UPDATE_CUSTOMER_URL;

		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_URL, $url);
		if (empty($nav_customer_code)) {
			curl_setopt($ch, CURLOPT_POST, true);
		} else {
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
		}
		curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		$resp = curl_exec($ch);
		curl_close($ch);
		// echo "<pre>"; print_r($resp); die;
		return json_decode($resp);
	}
	// End...


	public function add_customers()
	{
		$cust['branchs'] = $this->customers_model->get_result('branch_id, branch_name', 'mst_branches', ['status > ' => 0]);
		if ($this->input->post('submitCustomer')) {
			$data = NULL;
			$data = $this->input->post();

			$this->form_validation->set_rules('customer_name', 'Customer name', 'required');
			$this->form_validation->set_rules('customer_type', 'Customer Type', 'required');
			$this->form_validation->set_rules('email', 'Email', 'required'); 	// UPDATED BY SAURABH ON 22-11-2021, as said by sir
			$this->form_validation->set_rules('address', 'Address', 'required|max_length[150]');
			$this->form_validation->set_rules('city', 'City', 'required');
			$this->form_validation->set_rules('mobile', 'Mobile', 'required');
			$this->form_validation->set_rules('credit', 'Credit', 'required');
			$this->form_validation->set_rules('cust_customers_country_id', 'Country', 'required');
			$this->form_validation->set_rules('retention_period', 'Retention Period', 'required');
			
			$this->form_validation->set_rules('isactive', 'Status', 'required');
			$this->form_validation->set_rules('credit_limit', 'Credit limit', 'required');
			$this->form_validation->set_rules('po_box', 'Pin Code', 'required');
			if($this->input->post('cust_customers_country_id') == 1){
				$this->form_validation->set_rules('pan', 'Pan NO.', 'required');
				$this->form_validation->set_rules('cust_customers_province_id', 'State', 'required');
			}
			// updated by kamal singh on 20th of september 2022
			if (array_key_exists('cust_type', $data)) {
				if ($data['cust_type'] == "Registered") {
					$this->form_validation->set_rules('gstin', 'GSTIN', 'required');
				}
			}
			if (array_key_exists('tan_mendatory_flag', $data)) {
				if ($data['tan_mendatory_flag'] == "yes") {
					$this->form_validation->set_rules('tan', 'TAN', 'required');
				}
			}

			if ($this->form_validation->run()) {

				$data = $this->filterData($data);
				if ($data && count($data) > 0) {
					// created by kamal singh

					$data['nav_customer_code'] = "";
					unset($data['submitCustomer']);
					$result = $this->customers_model->insertCustomers($data);
					if ($result) {
						$this->session->set_flashdata('success', 'Customer added successfully');
						redirect('customers');
					} else {
						$this->session->set_flashdata('error', 'Error in adding Customer');
					}
					$isERPFlag = true;
					//					if (IS_ERP_SYNC_CUSTOMERS) {
					//						$erpStatus = $this->_addUpdateCustomerERPSync($data, NULL);
					//						if (!empty($erpStatus->Status) && $erpStatus->Status_code != "SUCCESS") {
					//							$this->session->set_flashdata('error', $erpStatus->Messsage);
					//						} else {
					//							$isERPFlag = true;
					//						}
					//					}
					//					if ($isERPFlag && !empty($erpStatus->Data->m_StringValue)) {
					//						
					//					} else {
					//						$this->session->set_flashdata('error', $erpStatus->Messsage);
					//					}
				}
			}
		}
		$this->load_view('customer_management/customers_form', $cust);
	}

	public function edit_customers($customer_id = NULL)
	{
		$data = NULL;
		$data['branchs'] = $this->customers_model->get_result('branch_id,branch_name', 'mst_branches', ['status > ' => 0]);
		$data['data'] = $this->customers_model->get_customers($customer_id);

		$data['gen_post'] = $this->customers_model->get_data_by_id_array('posting_group', "posting_group_name, posting_group_id", $data['data']->gen, 'posting_group_id');

		$data['vat_post'] = $this->customers_model->get_data_by_id_array('posting_group', "posting_group_name, posting_group_id", $data['data']->vat, 'posting_group_id');

		$data['excise_post'] = $this->customers_model->get_data_by_id_array('posting_group', "posting_group_name, posting_group_id", $data['data']->excise, 'posting_group_id');

		$data['custom_post'] = $this->customers_model->get_data_by_id_array('posting_group', "posting_group_name, posting_group_id", $data['data']->cust_post, 'posting_group_id');

		if ($data && count($data) > 0) {
			$this->load_view('customer_management/customers_form', $data);
		}
	}
	function getCustomerCode($name)
	{
		$url = "http://103.172.86.135:54321/LIMS/NAV/GetCustomerCode?Name=" . urlencode($name);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_URL, $url);
		$result = curl_exec($ch);
		curl_close($ch);
		$data = json_decode($result);

		if ($data->Status) {
			return $data->Data->m_StringValue;
		} else {
			return "";
		}
	}
	public function update_customers($customer_id = NULL)
	{
		if ($this->input->post('submitCustomer') == 'Update') {
			$data = NULL;
			$data = $this->input->post();

			$this->form_validation->set_rules('customer_name', 'Customer name', 'required');
			$this->form_validation->set_rules('customer_type', 'Customer Type', 'required');
			$this->form_validation->set_rules('email', 'Email', 'required');
			$this->form_validation->set_rules('address', 'Address', 'required|max_length[150]');
			$this->form_validation->set_rules('city', 'City', 'required');
			$this->form_validation->set_rules('mobile', 'Mobile', 'required');
			$this->form_validation->set_rules('retention_period', 'Retention Period', 'required');
			$this->form_validation->set_rules('credit', 'Credit', 'required');
			$this->form_validation->set_rules('cust_customers_country_id', 'Country', 'required');
			
			$this->form_validation->set_rules('isactive', 'Status', 'required');
			$this->form_validation->set_rules('credit_limit', 'Credit limit', 'required');
			$this->form_validation->set_rules('po_box', 'Pin Code', 'required');
			if($this->input->post('cust_customers_country_id') == 1){
				$this->form_validation->set_rules('pan', 'Pan NO.', 'required');
				$this->form_validation->set_rules('cust_customers_province_id', 'State', 'required');
			}
			if (array_key_exists('cust_type', $data)) {
				if ($data['cust_type'] == "Registered") {
					$this->form_validation->set_rules('gstin', 'GSTIN', 'required');
				}
			}
			if (array_key_exists('tan_mendatory_flag', $data)) {
				if ($data['tan_mendatory_flag'] == "yes") {
					$this->form_validation->set_rules('tan', 'TAN', 'required');
				}
			}
			if ($this->form_validation->run()) {
				$data = $this->filterData($data);
				if ($data && count($data) > 0) {


					$getNavCustCode = $this->customers_model->get_row('nav_customer_code', 'cust_customers', ['customer_id' => $customer_id]);


					$isERPFlag = false;
					if (IS_ERP_SYNC_CUSTOMERS && empty($getNavCustCode->nav_customer_code)) {
						$erpStatus = $this->_addUpdateCustomerERPSync($data, $getNavCustCode->nav_customer_code);

						if ($erpStatus->Status_code != 'ERROR') {
							$isERPFlag = true;
						} else if ($erpStatus->Status_code == 'ERROR') {
							$nvdata['nav_customer_code'] = $this->getCustomerCode($data['customer_name']);

							$this->db->where('customer_id', $customer_id);
							$this->db->update('cust_customers', $nvdata);

							// 		echo $this->db->last_query();
							// 		echo "<pre>";
							// 	echo $customer_id;
							// 	print_r($nvdata);
							//  print_r($data);
							//  exit;
							$isERPFlag = true;
						} else {
							$this->session->set_flashdata('error', $erpStatus->Messsage);
							$setData = $this->update_data_set($data, $customer_id);
							$this->load_view('customer_management/customers_form', $setData);
						}
					} else {
						$isERPFlag = true;
					}
					if ($isERPFlag) {
						unset($data['submitCustomer']);
						$result = $this->customers_model->updateCustomers($data, $customer_id);
						// echo $result; die;
						//echo $this->db->last_query(); die;
						if ($result) {
							// echo "kama"; die;
							if (!empty($result['nav_customer_code']) && IS_ERP_INTEGRATED) {
								$this->_update_customer($result);
							}
							$log = $this->user_log_update($customer_id, 'CUSTOMER UPDATED', 'UPDATE CUSTOMERS');
							if ($log) {
								$this->session->set_flashdata('success', 'Customer Updated successfully');
								redirect('customers');
							} else {
								$this->session->set_flashdata('error', 'Error in generating log');
							}
						} else {
							$this->session->set_flashdata('error', 'Error in updating customers');
						}
					} else {
						$this->session->set_flashdata('error', 'Error on ERP updating customers');
					}
				}
			} else {
				$this->session->set_flashdata('error', 'Fill all required fields');
				$setData = $this->update_data_set($data, $customer_id);
				$this->load_view('customer_management/customers_form', $setData);
			}
		}
	}

	public function update_data_set($data, $customer_id)
	{
		$setData = array();
		$setData['title'] = "UPDATE CUSTOMER";
		$setData['active_title'] = 'Update';
		$setData['action'] = base_url('update_customers' . '/' . $customer_id);
		$setData['customer_name'] = set_value('customer_name');
		$setData['customer_code'] = set_value('customer_code');
		$setData['factory'] = set_select('customer_type', 'Factory', true);
		$setData['buyer'] = set_select('customer_type', 'Buyer', true);
		$setData['thirdparty'] = set_select('customer_type', 'Thirdparty', true);
		$setData['agent'] = set_select('customer_type', 'Agent', true);
		$setData['email'] = set_value('email');
		$setData['telephone'] = set_value('telephone');
		$setData['mobile'] =   set_value('mobile');
		$setData['address'] =  set_value('address');
		$setData['city'] = set_value('city');
		$setData['po_box'] = set_value('po_box');
		$setData['cust_customers_country_id'] = set_value('cust_customers_country_id');
		$setData['country_name'] = set_value('country_name');
		$setData['cust_customers_province_id'] = set_value('cust_customers_province_id');
		$setData['state_name'] = set_value('state_name');
		$setData['cust_customers_location_id'] = set_value('cust_customers_location_id');
		$setData['location_name'] = set_value('location_name');
		$setData['web'] = set_value('web');
		$setData['pan'] = set_value('pan');
		$setData['tan'] = set_value('tan');
		$setData['gstin'] = set_value('gstin');
		$setData['retention_period'] = set_value('retention_period');
		// $non_taxable = set_value('non_taxable');
		$setData['button'] = "Update";
		return $setData;
	}

	public function filterData($data)
	{
		if ($data && count($data) > 0) {

			$data['created_by'] = $this->user;
			if (empty($data['non_taxable'])) {
				$data['non_taxable'] = 0;
			}
			$data['created_on'] = date("Y-m-d h:i:s");
			unset($data['country_name']);
			unset($data['state_name']);
			unset($data['location_name']);
			return $data;
		} else {
			return false;
		}
	}


	public function add_contact()
	{
		$duplicate = null;
		$data = NULL;
		$data = $this->input->post();
		$customer_id = $data['contacts_customer_id'];
		$data = $this->filterData($data);
		unset($data['non_taxable']);
		$this->form_validation->set_rules('contact_name', 'Contact Name', 'required');
		$this->form_validation->set_rules('email', 'Contact Email', 'required|valid_email');
		$this->form_validation->set_rules('type', 'Type', 'required');
		$this->form_validation->set_rules('telephone', 'Telephone', 'required');
		$customer_type = $this->customers_model->get_row('customer_type', 'cust_customers', 'customer_id=' . $customer_id);

		//                echo "<pre>";
		//                print_r($customer_type);
		//                exit;
		if ($customer_type && !empty($customer_type)) {
			$data['customer_type'] = $customer_type->customer_type;
		}
		if ($this->form_validation->run()) {
			if ($data && count($data) > 0) {
				$duplicate = $this->customers_model->check_duplicate($data);
				if ($duplicate) {

					$result = $this->customers_model->insert_data('contacts', $data);
					if ($result) {
						$log = $this->user_log_update($customer_id, 'CONTACT ADDED WITH NAME ' . $data['contact_name'], 'ADD CONTACT');

						if ($log) {

							$msg = array(
								'status' => 1,
								'msg' => 'Contact added successfully'
							);
						} else {
							$msg = array(
								'status' => 0,
								'msg' => 'Error in generating log'
							);
						}
					} else {
						$msg = array(
							'status' => 0,
							'msg' => 'Error in adding contact'
						);
					}
				} else {
					$msg = array(
						'status' => 0,
						'msg' => 'This contact already exists'
					);
				}
			}
		} else {
			$msg = array(
				'status' => 0,
				'msg' => 'Please fill Required fields',
				'errors' => $this->form_validation->error_array()
			);
		}

		echo json_encode($msg);
	}


	public function load_contacts()
	{
		$customer_id = NULL;
		$customer_id = $this->input->post('customer_id');
		if ($customer_id) {
			$data = $this->customers_model->load_contacts($customer_id);
		}

		echo json_encode($data);
	}


	public function delete_contact()
	{
		$contact_id = $this->input->post('contact_id');
		$customer_id = $this->input->post('customer_id');

		$contact_name = $this->customers_model->get_row('contact_name', 'contacts', ['contact_id' => $contact_id]);

		if ($contact_name && count($contact_name)) {
			$contact_name  = $contact_name->contact_name;
		}

		if ($contact_id) {
			$data = $this->customers_model->deleteContact($contact_id);
			if ($data) {
				$log = 	$this->user_log_update($customer_id, 'CONTACT DELETED WITH NAME ' . $contact_name, 'DELETE CONTACT');

				if ($log) {
					$msg = array(
						'status' => 1,
						'msg' => 'Contact deleted successfully'
					);
				} else {
					$msg = array(
						'status' => 0,
						'msg' => 'Error in generating log'
					);
				}
			} else {
				$msg = array(
					'status' => 0,
					'msg' => 'Error in deleting contact'
				);
			}
		}

		echo json_encode($msg);
	}

	public function delete_communications()
	{
		$communication_id = $this->input->post('communication_id');
		$customer_id = $this->input->post('customer_id');

		$contact_id = $this->customers_model->get_row('comm_communications_contact_id', 'comm_communications', ['communication_id' => $communication_id]);
		if ($contact_id && count($contact_id)) {
			$contact_id  = $contact_id->comm_communications_contact_id;
			if ($contact_id) {
				$contact_name = $this->customers_model->get_row('contact_name', 'contacts', ['contact_id' => $contact_id]);
				if ($contact_name && count($contact_name)) {
					$contact_name  = $contact_name->contact_name;
				}
			}
		}


		if ($communication_id) {
			$data = $this->customers_model->deleteCommunications($communication_id);
			if ($data) {

				$log = $this->user_log_update($customer_id, 'COMMUNICATION DELETED WITH CONTACT ' . $contact_name, 'DELETE COMMUNICATION');
				if ($log) {
					$msg = array(
						'status' => 1,
						'msg' => 'Communication deleted successfully'
					);
				} else {
					$msg = array(
						'status' => 0,
						'msg' => 'Error in generating log'
					);
				}
			} else {
				$msg = array(
					'status' => 0,
					'msg' => 'Error in deleting communication'
				);
			}
		}

		echo json_encode($msg);
	}

	public function delete_opportunity()
	{
		$opportunity_id = $this->input->post('opportunity_id');
		$customer_id = $this->input->post('customer_id');

		$opportunity_name = $this->customers_model->get_row('opportunity_name', 'opportunity', ['opportunity_id' => $opportunity_id]);
		if ($opportunity_name && count($opportunity_name)) {
			$opportunity_name  = $opportunity_name->opportunity_name;
		}


		if ($opportunity_id) {
			$data = $this->customers_model->deleteOpportunity($opportunity_id);
			if ($data) {
				$log = $this->user_log_update($customer_id, 'OPPORTUNITY DELETED WITH NAME ' . $opportunity_name, 'DELETE OPPORTUNITY');

				if ($log) {
					$msg = array(
						'status' => 1,
						'msg' => 'Opportunity deleted successfully'
					);
				} else {
					$msg = array(
						'status' => 0,
						'msg' => 'Error in generating log'
					);
				}
			} else {
				$msg = array(
					'status' => 0,
					'msg' => 'Error in deleting Opportunity'
				);
			}
		}

		echo json_encode($msg);
	}




	public function load_reference_no()
	{
		$customer_id = NULL;
		$customer_id = $this->input->post('customer_id');
		if ($customer_id) {
			$data = $this->customers_model->loadReference_no($customer_id);
		}

		echo json_encode($data);
	}

	public function load_oportunity()
	{
		$customer_id = NULL;
		$customer_id = $this->input->post('customer_id');
		if ($customer_id) {
			$data = $this->customers_model->loadOportunity($customer_id);
		}

		echo json_encode($data);
	}


	public function add_communication()
	{
		$data = NULL;
		$data = $this->input->post();
		$customer_id = $data['comm_communications_customer_id'];
		$this->form_validation->set_rules('comm_communications_contact_id', 'Contact name', 'required');
		$this->form_validation->set_rules('subject', 'Subject', 'required');
		$this->form_validation->set_rules('date_of_communication', 'Date of communication', 'required');
		$this->form_validation->set_rules('follow_up_date', 'Follow up date', 'required');
		$this->form_validation->set_rules('communication_mode', 'Communication mode', 'required');
		$this->form_validation->set_rules('medium', 'Medium', 'required');
		$this->form_validation->set_rules('connected_to', 'Connected to', 'required');

		$customer_type = $this->customers_model->get_row('customer_type', 'cust_customers', 'customer_id=' . $customer_id);
		if ($customer_type && count($customer_type)) {
			$data['customer_type'] = $customer_type->customer_type;
		}

		$contact_id = $data['comm_communications_contact_id'];
		if ($contact_id) {
			$contact_name = $this->customers_model->get_row('contact_name', 'contacts', ['contact_id' => $contact_id]);
			if ($contact_name && count($contact_name)) {
				$contact_name  = $contact_name->contact_name;
			}
		}
		if ($this->form_validation->run()) {
			if ($data && count($data) > 0) {
				$data = $this->filterData($data);
				unset($data['non_taxable']);
				$mail_data = $data;
				$data = $this->customers_model->insert_data('comm_communications', $data);
				if ($data) {

					$log = $this->user_log_update($customer_id, 'COMMUNICATION ADDED WITH CONTACT ' . $contact_name, 'ADD COMMUNICATION');

					if ($log) {
						$msg = array(
							'status' => 1,
							'msg' => "Communication added successfully"
						);
					} else {
						$msg = array(
							'status' => 0,
							'msg' => 'Error in generating log'
						);
					}
				} else {
					$msg = array(
						'status' => 0,
						'msg' => "Error in adding communications"
					);
				}
			}
		} else {
			$msg = array(
				'status' => 0,
				'msg' => 'Please fill Required fields',
				'errors' => $this->form_validation->error_array()
			);
		}

		echo json_encode($msg);
	}



	public function load_communications()
	{
		$customer_id = NULL;
		$customer_id = $this->input->post('customer_id');
		if ($customer_id) {
			$data = $this->customers_model->loadCommunications($customer_id);
		}

		echo json_encode($data);
	}

	public function add_opportunity()
	{
		$data = NULL;
		$data = $this->input->post();
		$customer_id = $data['opportunity_customer_id'];
		$customer_type = $this->customers_model->get_row('customer_type', 'cust_customers', 'customer_id=' . $customer_id);
		if ($customer_type && count($customer_type)) {
			$data['opportunity_customer_type'] = $customer_type->customer_type;
		}
		$this->form_validation->set_rules('opportunity_name', 'Opportunity Name', 'required');
		$this->form_validation->set_rules('types', 'Types', 'required');
		$this->form_validation->set_rules('opportunity_value', 'Opportunity Value', 'required');
		$this->form_validation->set_rules('estimated_closure_date', 'Estimated closure date', 'required');
		$this->form_validation->set_rules('opportunity_contact_id', 'Contact Name', 'required');
		$this->form_validation->set_rules('op_assigned_to', 'Assign to', 'required');
		$this->form_validation->set_rules('description', 'Description', 'required|trim|min_length[20]');
		$opportunity_name = $data['opportunity_name'];
		if ($this->form_validation->run()) {
			if ($data && count($data) > 0) {
				$data = $this->filterData($data);
				unset($data['non_taxable']);
				$data = $this->customers_model->insert_data('opportunity', $data);
				if ($data) {

					$log = $this->user_log_update($customer_id, 'OPPORTUNITY ADDED WITH NAME ' . $opportunity_name, 'ADD OPPORTUNITY');
					if ($log) {
						$msg = array(
							'status' => 1,
							'msg' => "Opportunity added successfully"
						);
					} else {
						$msg = array(
							'status' => 0,
							'msg' => 'Error in generating log'
						);
					}
				} else {
					$msg = array(
						'status' => 0,
						'msg' => "Error in adding opportunity"
					);
				}
			}
		} else {
			$msg = array(
				'status' => 0,
				'msg' => 'Please fill Required fields',
				'errors' => $this->form_validation->error_array()
			);
		}
		echo json_encode($msg);
	}


	public function manage_relationship_type()
	{
		$customer_type =  NULL;
		$data = array();
		$customer_type = $this->input->post('customer_type');
		$data = $this->customers_model->get_type(NULL, $customer_type);

		echo json_encode($data);
	}

	public function map_listing()
	{
		$data = array();
		$customer_id = $type = $list = $map_type = $where = $not_in = $id = $table = $keep =  NULL;
		$customer_id = $this->input->post('customer_id');
		$type = $this->input->post('type');
		$map_type = $this->input->post('map_type');
		$search = $this->input->post('search');

		if (($map_type == "Agent" && $type == "Buyer") || ($map_type == "Agent" && $type == "Factory") || ($map_type == "Factory" && $type == "Buyer") || ($map_type == "Thirdparty" && $type == "Buyer") || ($map_type == "Thirdparty" && $type == "Factory") || ($map_type == "Thirdparty" && $type == "Agent")) {

			$table = strtolower($type) . "_" . strtolower($map_type);
		} else {

			$table = strtolower($map_type) . "_" . strtolower($type);
		}

		$where[strtolower($map_type) . "_id"] = $customer_id;
		$id = strtolower($type) . "_id";


		$not_in = $this->customers_model->get_result($id, $table, $where);
		if ($not_in && count($not_in) > 0) {
			foreach ($not_in as $key => $value) {
				array_push($data, $value->$id);
			}
		}


		$list = $this->customers_model->get_map_listing(NULL, $type, $data, $search);

		echo json_encode($list);
	}


	public function map_customers()
	{
		$customer_id = $map_id = $type = $map_type = $data = NULL;
		$customer_id = $this->input->post('customer_id');
		$map_id = $this->input->post('map_id');
		if ($map_id) {
			$map_customer_name = $this->customers_model->get_row('customer_name', 'cust_customers', ['customer_id' => $map_id]);
			if ($map_customer_name && count($map_customer_name)) {
				$map_customer_name  = $map_customer_name->customer_name;
			}
		}
		$type = $this->input->post('type');
		$map_type = $this->input->post('map_type');


		if (($map_type == "Agent" && $type == "Buyer") || ($map_type == "Agent" && $type == "Factory") || ($map_type == "Factory" && $type == "Buyer") || ($map_type == "Thirdparty" && $type == "Buyer") || ($map_type == "Thirdparty" && $type == "Factory") || ($map_type == "Thirdparty" && $type == "Agent")) {

			$table = strtolower($type) . "_" . strtolower($map_type);
		} else {

			$table = strtolower($map_type) . "_" . strtolower($type);
		}


		$data[strtolower($type) . "_id"] = $map_id;
		$data[strtolower($map_type) . "_id"] = $customer_id;
		$result = $this->customers_model->insert_map_details($table, $data);


		if ($result) {
			$log = $this->user_log_update($customer_id, $type . ' ' . $map_customer_name . ' MAPPED TO ' . $map_type, 'MAP CUSTOMERS');

			if ($log) {
				$msg = array(
					'status' => 1,
					'msg' => $type . " mapped to " . $map_type . " successfully"
				);
			} else {
				$msg = array(
					'status' => 0,
					'msg' => 'Error in generating log'
				);
			}
		} else {
			$msg = array(
				'status' => 0,
				'msg' => "Error in " . $type . " mapped to " . $map_type
			);
		}

		echo json_encode($msg);
	}

	public function remove_mapped_customers()
	{
		$customer_id = $un_map_id = $type = $map_type = $where1 = $where2 = NULL;
		$customer_id = $this->input->post('customer_id');
		$un_map_id = $this->input->post('un_map_id');
		$type = $this->input->post('type');
		$map_type = $this->input->post('map_type');

		if ($un_map_id) {
			$unmap_customer_name = $this->customers_model->get_row('customer_name', 'cust_customers', ['customer_id' => $un_map_id]);
			if ($unmap_customer_name && count($unmap_customer_name)) {
				$unmap_customer_name  = $unmap_customer_name->customer_name;
			}
		}

		if (($map_type == "Agent" && $type == "Buyer") || ($map_type == "Agent" && $type == "Factory") || ($map_type == "Factory" && $type == "Buyer") || ($map_type == "Thirdparty" && $type == "Buyer") || ($map_type == "Thirdparty" && $type == "Factory") || ($map_type == "Thirdparty" && $type == "Agent")) {

			$table = strtolower($type) . "_" . strtolower($map_type);
		} else {

			$table = strtolower($map_type) . "_" . strtolower($type);
		}


		$where1[$table . "." . strtolower($map_type) . "_id"] = $customer_id;
		$where2[$table . "." . strtolower($type) . "_id"] = $un_map_id;
		$result = $this->customers_model->delete_map_details($table, $where1, $where2);

		if ($result) {

			$log = $this->user_log_update($customer_id, $type . ' ' . $unmap_customer_name . ' REMOVED TO ' . $map_type, 'REMOVED MAP CUSTOMERS');

			if ($log) {
				$msg = array(
					'status' => 1,
					'msg' => $type . " Removed to " . $map_type . " successfully"
				);
			} else {
				$msg = array(
					'status' => 0,
					'msg' => 'Error in generating log'
				);
			}
		} else {
			$msg = array(
				'status' => 0,
				'msg' => "Error in " . $type . " Remove to " . $map_type
			);
		}

		echo json_encode($msg);
	}

	public function mapped_listing()
	{

		$customer_id = $type = $list = $map_type = $where1 = $where2 =  $join_table = $condition = $search =   NULL;
		$customer_id = $this->input->post('customer_id');
		$type = $this->input->post('type');
		$map_type = $this->input->post('map_type');
		$search = $this->input->post('search');

		if (($map_type == "Agent" && $type == "Buyer") || ($map_type == "Agent" && $type == "Factory") || ($map_type == "Factory" && $type == "Buyer") || ($map_type == "Thirdparty" && $type == "Buyer") || ($map_type == "Thirdparty" && $type == "Factory") || ($map_type == "Thirdparty" && $type == "Agent")) {

			$join_table = strtolower($type) . "_" . strtolower($map_type) . " as join";
		} else {
			$join_table = strtolower($map_type) . "_" . strtolower($type) . " as join";
		}

		$condition = "join." . strtolower($type) . "_id=cs.customer_id";
		$where1['cs.customer_type'] = $type;
		$where2["join." . strtolower($map_type) . "_id"] = $customer_id;

		$list = $this->customers_model->get_mapped_listing($join_table, $condition, $where1, $where2, $search);
		//echo $this->db->last_query(); die;
		echo json_encode($list);
	}

	public function load_assign_to()
	{
		$designation_id2 = SALES_EXECUTIVE;
		$designation_id1 = SALES_MANAGER;
		$result = $this->customers_model->op_assign_to($designation_id2, $designation_id1);
		echo json_encode($result);
	}

	function send_mail($to = NULL, $from = NULL, $cc = NULL, $msg = NULL, $sub = NULL, $attachment_file = NULL, $attachment_path = NULL, $report = false)
	{

		$CI = &get_instance();
		$CI->load->library('email');

		$message = $msg;

		if (is_array($to) && count($to) > 1)
			$to_user = implode(',', $to); // convert email array to string
		else
			$to_user = $to;

		if (is_array($cc) && count($cc) > 1)
			$cc_user = implode(',', $cc); // convert email array to string
		else
			$cc_user = $cc;

		$config['protocol'] = PROTOCOL;
		$config['smtp_host'] = HOST;
		$config['smtp_user'] = USER;
		$config['smtp_pass'] = PASS;
		$config['smtp_port'] = PORT;
		$config['newline'] = "\r\n";
		$config['smtp_crypto'] = CRYPTO;
		$config['charset'] = 'utf-8';
		$config['newline'] = "\r\n";
		$config['mailtype'] = 'html';
		$CI->email->initialize($config);
		$CI->email->from(FROM, 'BASIL');
		if ($report) {
			$CI->email->to($to_user);
		} else {
			$CI->email->to($to_user);
		}
		if ($cc_user) {
			$CI->email->cc($cc_user);
		}
		$CI->email->cc($cc);
		$CI->email->subject($sub);
		$CI->email->message($message);
		if ($attachment_path) {
			if (is_array($attachment_path)) {
				for ($i = 0; $i < count($attachment_path); $i++) {
					$CI->email->attach($attachment_path[$i]);
				}
			} else {
				$CI->email->attach($attachment_path);
			}
		}
		$bool = $CI->email->send();

		if ($attachment_path != '' || $attachment_path != NULL) {

			unlink($attachment_path);
		}

		if ($bool) {
			return true;
		} else {
			show_error($CI->email->print_debugger());
		}
	}


	// public function mail_to_assigny($data){
	// 	if(array_key_exists('opportunity_customer_id',$data)){
	// 		$customer_id = $data[''];
	// 	}
	// 	else{

	// 	}

	// }


	// LAKSHAY CODE 25-02-2021
	public function mark_cust($id)
	{
		$id = base64_decode($id);
		if ($id > 0) {
			$cust = $this->customers_model->get_row('customer_name,accop_cust', 'cust_customers', ['customer_id' => $id]);
			if ($cust) {
				$this->customers_model->update_data('cust_customers', ['accop_cust' => 0], ['LOWER(customer_name)' => strtolower($cust->customer_name)]);
				if ($cust->accop_cust > 0) {
					$result = 	$this->customers_model->update_data('cust_customers', ['accop_cust' => 0], ['customer_id' => $id]);
				} else {
					$result = $this->customers_model->update_data('cust_customers', ['accop_cust' => 1], ['customer_id' => $id]);
				}

				// AJIT CODE START 07-04-2021
				if ($result) {
					$log = $this->user_log_update($id, 'MARKED AS ACCOPS CUSTOMER', 'MARK ACCOPS CUSTOMER');
					if ($log) {
						$this->session->set_flashdata('success', 'MARK ACCOPS CUSTOMER.');
						redirect($_SERVER['HTTP_REFERER']);
					} else {
						$this->session->set_flashdata('error', 'Error in generating log');
						redirect($_SERVER['HTTP_REFERER']);
					}
				} else {
					$this->session->set_flashdata('error', 'NO RECORD FOUND.');
					redirect($_SERVER['HTTP_REFERER']);
				}

				// END

			} else {
				$this->session->set_flashdata('error', 'NO RECORD FOUND.');
				redirect($_SERVER['HTTP_REFERER']);
			}
		} else {
			$this->session->set_flashdata('success', 'NO RECORD');
			redirect($_SERVER['HTTP_REFERER']);
		}
	}
	// END


	// saurabh 04-05-2021
	public function weekly_report($id)
	{
		$id = base64_decode($id);
		if ($id > 0) {
			$cust = $this->customers_model->get_row('customer_name,sample_week_report', 'cust_customers', ['customer_id' => $id]);
			if ($cust) {
				$this->customers_model->update_data('cust_customers', ['sample_week_report' => 0], ['LOWER(customer_name)' => strtolower($cust->customer_name)]);
				if ($cust->sample_week_report > 0) {
					$result = 	$this->customers_model->update_data('cust_customers', ['sample_week_report' => 0], ['customer_id' => $id]);
				} else {
					$result = $this->customers_model->update_data('cust_customers', ['sample_week_report' => 1], ['customer_id' => $id]);
				}

				if ($result) {
					$log = $this->user_log_update($id, 'Get Weekly Report Of Sample', 'weekly_report');
					if ($log) {
						$this->session->set_flashdata('success', 'Get Weekly Report Of Sample.');
						redirect($_SERVER['HTTP_REFERER']);
					} else {
						$this->session->set_flashdata('error', 'Error in generating log');
						redirect($_SERVER['HTTP_REFERER']);
					}
				} else {
					$this->session->set_flashdata('error', 'NO RECORD FOUND.');
					redirect($_SERVER['HTTP_REFERER']);
				}
			} else {
				$this->session->set_flashdata('error', 'NO RECORD FOUND.');
				redirect($_SERVER['HTTP_REFERER']);
			}
		} else {
			$this->session->set_flashdata('success', 'NO RECORD');
			redirect($_SERVER['HTTP_REFERER']);
		}
	}
	// saurabh 04-05-2021


	public function view_cust_details()
	{
		$customer_id = $this->input->post('customer_id');
		$data = $this->customers_model->get_customer_details($customer_id);
		echo json_encode($data);
	}

	// Get division log
	public function get_customer_log()
	{
		$customer_id = $this->input->post('customer_id');
		$data = $this->customers_model->get_customer_log($customer_id);
		echo json_encode($data);
	}


	public function user_log_update($customer_id, $text, $action)
	{
		$data = array();
		$data['source_module'] = 'Customers';
		$data['record_id'] = $customer_id;
		$data['created_on'] = date("Y-m-d h:i:s");
		$data['created_by'] = $this->user;
		$data['action_taken'] = $action;
		$data['text'] = $text;

		$result = $this->customers_model->insert_data('user_log_history', $data);
		if ($result) {
			return true;
		} else {
			return false;
		}
	}


	// UPDATE EXCEL EXPORT 12-04-2021
	public function export_customers()
	{

		$query = $this->session->userdata('excel_query');
		$data = $this->db->query($query)->result();

		if ($data && count($data) > 0) {
			$this->load->library('excel');
			$tmpfname = "example.xls";

			$excelReader = PHPExcel_IOFactory::createReaderForFile($tmpfname);
			$objPHPExcel = $excelReader->load($tmpfname);

			$objPHPExcel->getProperties()->setCreator("GEO-CHEM")
				->setLastModifiedBy("GEO-CHEM")
				->setTitle("Office 2007 XLS Customers Document")
				->setSubject("Office 2007 XLS Customers Document")
				->setDescription("Description for Customers Document")
				->setKeywords("phpexcel office codeigniter php")
				->setCategory("Customers details file");


			$objPHPExcel->setActiveSheetIndex(0);
			$objPHPExcel->getActiveSheet()->setCellValue('A1', "SL NO.");
			$objPHPExcel->getActiveSheet()->setCellValue('B1', "CUSTOMER CODE");
			$objPHPExcel->getActiveSheet()->setCellValue('C1', "CUSTOMER NAME");
			$objPHPExcel->getActiveSheet()->setCellValue('D1', "CUSTOMER TYPE");
			$objPHPExcel->getActiveSheet()->setCellValue('E1', "EMAIL");
			$objPHPExcel->getActiveSheet()->setCellValue('F1', "CREATED BY");
			$objPHPExcel->getActiveSheet()->setCellValue('G1', "CREATED ON");
			$objPHPExcel->getActiveSheet()->setCellValue('H1', "TELEPHONE");
			$objPHPExcel->getActiveSheet()->setCellValue('I1', "MOBILE");
			$objPHPExcel->getActiveSheet()->setCellValue('J1', "ADDRESS");
			$objPHPExcel->getActiveSheet()->setCellValue('K1', "CITY");
			$objPHPExcel->getActiveSheet()->setCellValue('L1', "PIN CODE");
			$objPHPExcel->getActiveSheet()->setCellValue('M1', "COUNTRY");
			$objPHPExcel->getActiveSheet()->setCellValue('N1', "WEBSITE");
			$objPHPExcel->getActiveSheet()->setCellValue('O1', "STATUS");
			$objPHPExcel->getActiveSheet()->setCellValue('P1', "CREDIT");
			$objPHPExcel->getActiveSheet()->setCellValue('Q1', "PAN NO.");
			$objPHPExcel->getActiveSheet()->setCellValue('R1', "TAN NO.");
			$objPHPExcel->getActiveSheet()->setCellValue('S1', "GSTIN");
			$objPHPExcel->getActiveSheet()->setCellValue('T1', "RETENTION PERIOD");



			$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
			$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
			$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
			$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
			$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
			$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
			$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
			$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
			$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
			$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
			$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
			$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
			$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);
			$objPHPExcel->getActiveSheet()->getColumnDimension('N')->setAutoSize(true);
			$objPHPExcel->getActiveSheet()->getColumnDimension('O')->setAutoSize(true);
			$objPHPExcel->getActiveSheet()->getColumnDimension('P')->setAutoSize(true);
			$objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setAutoSize(true);
			$objPHPExcel->getActiveSheet()->getColumnDimension('R')->setAutoSize(true);
			$objPHPExcel->getActiveSheet()->getColumnDimension('S')->setAutoSize(true);
			$objPHPExcel->getActiveSheet()->getColumnDimension('T')->setAutoSize(true);


			$i = 2;
			foreach ($data as $key => $value) {

				$objPHPExcel->getActiveSheet()->setCellValue('A' . $i, ($i - 1));
				$objPHPExcel->getActiveSheet()->setCellValue('B' . $i, ($value->customer_code) ? $value->customer_code : '');
				$objPHPExcel->getActiveSheet()->setCellValue('C' . $i, ($value->customer_name) ? $value->customer_name : '');
				$objPHPExcel->getActiveSheet()->setCellValue('D' . $i, ($value->customer_type) ? $value->customer_type : '');
				$objPHPExcel->getActiveSheet()->setCellValue('E' . $i, ($value->email) ? $value->email : '');
				$objPHPExcel->getActiveSheet()->setCellValue('F' . $i, ($value->created_by) ? $value->created_by : '');
				$objPHPExcel->getActiveSheet()->setCellValue('G' . $i, ($value->created_on) ? $value->created_on : '');

				$objPHPExcel->getActiveSheet()->setCellValue('H' . $i, ($value->telephone) ? $value->telephone : '');

				$objPHPExcel->getActiveSheet()->setCellValue('I' . $i, ($value->mobile) ? $value->mobile : '');
				$objPHPExcel->getActiveSheet()->setCellValue('J' . $i, ($value->address) ? $value->address : '');
				$objPHPExcel->getActiveSheet()->setCellValue('K' . $i, ($value->city) ? $value->city : '');
				$objPHPExcel->getActiveSheet()->setCellValue('L' . $i, ($value->po_box) ? $value->po_box : '');
				$objPHPExcel->getActiveSheet()->setCellValue('M' . $i, ($value->country_name) ? $value->country_name : '');

				$objPHPExcel->getActiveSheet()->setCellValue('N' . $i, ($value->web) ? $value->web : '');

				$objPHPExcel->getActiveSheet()->setCellValue('O' . $i, ($value->status) ? $value->status : '');

				$objPHPExcel->getActiveSheet()->setCellValue('P' . $i, ($value->credit) ? $value->credit : '');

				$objPHPExcel->getActiveSheet()->setCellValue('Q' . $i, ($value->pan) ? $value->pan : '');
				$objPHPExcel->getActiveSheet()->setCellValue('R' . $i, ($value->tan) ? $value->tan : '');
				$objPHPExcel->getActiveSheet()->setCellValue('S' . $i, ($value->gstin) ? $value->gstin : '');
				$objPHPExcel->getActiveSheet()->setCellValue('T' . $i, ($value->retention_period) ? $value->retention_period : '');
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


			$filename = 'Customers_details-' . date('Y-m-d-s') . ".xls";
			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
			ob_end_clean();
			header('Content-type: application/vnd.ms-excel');
			header('Content-Disposition: attachment; filename=' . $filename);
			$objWriter->save('php://output');
		}
	}


	// public function view_quotations(){
	// 	$customer_id = $this->input->post('customer_id');
	// 	$data = $this->customers_model->get_quotation_by_customer_id($customer_id);
	// 	echo json_encode($data);
	// }

	public function view_quotations($search, $page, $customer_id)
	{

		$where = NULL;
		if (!empty($search) && $search != 'NULL') {
			$search = base64_decode($search);
		} else {
			$search = NULL;
		}
		$per_page = 5;
		if ($page != 0) {
			$page = ($page - 1) * $per_page;
		}
		$total_row = $this->customers_model->get_quotation_by_customer_id(NULL, NULL, $search, $where, '1', $customer_id);

		$config = array();
		$config['base_url'] = base_url('customer_management/Customers/view_quotations');
		$config['use_page_numbers'] = TRUE;
		$config['total_rows'] = $total_row;
		$config['per_page'] = $per_page;
		$config["uri_segment"] = '5';
		$config['full_tag_open']    = '<div class="pagging text-center"><nav><ul class="pagination">';
		$config['full_tag_close']   = '</ul></nav></div>';
		$config['num_tag_open']     = '<li class="page-item"><span class="page-link">';
		$config['num_tag_close']    = '</span></li>';
		$config['cur_tag_open']     = '<li class="page-item active"><span class="page-link">';
		$config['cur_tag_close']    = '<span class="sr-only">(current)</span></span></li>';
		$config['next_tag_open']    = '<li class="page-item"><span class="page-link">';
		$config['next_tag_close']  = '<span aria-hidden="true"></span></span></li>';
		$config['prev_tag_open']    = '<li class="page-item"><span class="page-link">';
		$config['prev_tag_close']  = '</span></li>';
		$config['first_tag_open']   = '<li class="page-item"><span class="page-link">';
		$config['first_tag_close'] = '</span></li>';
		$config['last_tag_open']    = '<li class="page-item"><span class="page-link">';
		$config['last_tag_close']  = '</span></li>';
		$data["page"] = ($this->uri->segment('5')) ? $this->uri->segment('5') : 0;
		$this->pagination->initialize($config);
		$data['links'] =  $this->pagination->create_links();
		$result = $this->customers_model->get_quotation_by_customer_id($per_page, $page, $search, $where, NULL, $customer_id);
		$html = '';
		if ($result) {
			foreach ($result as $value) {
				$page++;
				$html .= '<tr>';
				$html .= '<th scope="col">' . $page . '</th>';
				$html .= '<td>' . $value->reference_no . '</td>';
				$html .= '<td>' . $value->quote_date . '</td>';
				$html .= '<td>' . $value->quote_value . '</td>';
				$html .= '<td>' . $value->quote_status . '</td>';
				$html .= '<td>' . $value->created_by . '</td>';
				$html .= '</tr>';
			}
		} else {
			$html .= '<tr><td colspan="6"><h4>NO RECORD FOUND</h4></td></tr>';
		}
		$data['result'] = $html;
		echo json_encode($data);
	}



	public function upload_customer_logo()
	{

		$id = $this->input->post();
		$data = $_FILES['sign_path'];

		if (!empty($data['name'])) {


			$logo_aws =  $this->multiple_upload_image($data);

			if ($logo_aws) {
				$logo_aws = str_replace('https://s3.ap-south-1.amazonaws.com/', 's3://', $logo_aws['aws_path']);

				$result = $this->customers_model->update_data('cust_customers', ['customer_logo_filepath' => $logo_aws], ['customer_id' => $id['customer_id']]);
			} else {
				$result = false;
			}
			// echo $result;die;
			if ($result) {
				$log_deatils = array(
					'text'          => "Customer logo uploaded",
					'created_by'    => $this->user,
					'created_on'    => date('Y-m-d H:i:s'),
					'record_id'     => $id['customer_id'],
					'source_module' => 'Customers',
					'action_taken'  => 'upload_customer_logo'
				);

				$result =  $this->customers_model->insert_data('user_log_history', $log_deatils);
				if ($result) {
					$msg = array(
						'msg' => 'Customer logo uploaded successfully',
						'status' => '1'
					);
				} else {
					$msg = array(
						'msg' => 'error in generating log',
						'status' => '0'
					);
				}
			} else {
				$msg = array(
					'msg' => 'Error while Uploading logo',
					'status' => '0'
				);
			}
		} else {
			$msg = array(
				'msg' => 'No File Selected',
				'status' => '0'
			);
		}
		echo json_encode($msg);
	}

	public function get_url_logo($logo_aws)
	{
		return str_replace('s3://', 'https://s3.ap-south-1.amazonaws.com/', $logo_aws);
	}

	public function get_logo()
	{
		$data = $this->input->post();
		$logo = $this->customers_model->get_row('customer_logo_filepath', 'cust_customers', ['customer_id' => $data['customer_id']]);
		if ($logo) {

			$logo = $this->get_url_logo($logo->customer_logo_filepath);
		}
		echo json_encode($logo);
	}

	//22-07-2021
	private function _checkCustomerOnBC365($customer)
	{
		$url = CUST_GET_API;
		$search_string = $customer['customer_name'];
		$q  = '%24filter=';
		$q1 = 'Name eq ' . "'$search_string'";
		$url .= $q . str_replace("+", "%20", urlencode($q1));

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			ERP_AUTH_KEY

		));
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		$result = curl_exec($ch);

		$r = json_decode($result);
		curl_close($ch);

		if (isset($r->value) && empty($r->value)) {
			return $this->_addCustomerOnBC365($customer);
		} elseif (isset($r->value) && !empty($r->value)) {
			$this->db->update('cust_customers', ['nav_customer_code' => $r->value[0]->CustomerNo, 'accop_cust' => 1], ['customer_name' => $customer['customer_name']]);
			if (empty($r->value[0]->LIMSCustomerId)) {
				$customer['nav_customer_code'] = $r->value[0]->CustomerNo;
				$this->_update_customer($customer);
			}
		}
		return ['message' => "alredy exists!"];
	}

	private function _addCustomerOnBC365($customer)
	{

		$data = [
			"Line_No" => 0,
			"customerNo" => "",
			"LIMSCustomerId" => $customer['customer_code'],
			"namE" => $customer['customer_name'],
			"Name2" => "",
			"Address" => $customer['address'],
			"Address2" => "",
			"countryCode" => $this->_setCountryCode($customer['cust_customers_country_id']), //
			"City" => $customer['city'],
			"State" => "",
			"PostCode" => "",
			"EMail" => $customer['email'],
			"LocationCode" => "",
			"CreditLimit" => (float) $customer['credit_limit'],
			"CreditDays" => $this->_setCreditDays($customer['credit']), //credit $customer['credit'] ? : $this->_setCreditDays($customer['credit'])
			"UserID" => "NAV.CPS",
			"TelNo" => $customer['telephone']
		];


		$payload = json_encode($data);
		$url = CUST_ADD_API;
		$ch = curl_init($url);

		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		// Return result of POST request
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		// Get information about last transfer
		curl_setopt($ch, CURLINFO_HEADER_OUT, true);

		// Use POST request
		curl_setopt($ch, CURLOPT_POST, true);

		// Set payload for POST request
		curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);

		// Set HTTP Header for POST request 
		curl_setopt(
			$ch,
			CURLOPT_HTTPHEADER,
			array(
				ERP_AUTH_KEY,
				'Content-Type: application/json',
				'Content-Length: ' . strlen($payload)
			)
		);
		// Execute a cURL session
		$result = curl_exec($ch);

		$r = json_decode($result);
		if (isset($r->customerNo) && !empty($r->customerNo)) {
			$this->db->update('cust_customers',  ['nav_customer_code' => $r->customerNo, 'accop_cust' => 1],  ['customer_name' => $customer['customer_name']]);
		}
		$status = curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
		curl_close($ch);
		return ['status' => $status, 'response_data' => $result];
	}

	private function _setCreditDays($creditDays)
	{
		$credit_days = "1";
		switch ($creditDays) {
			case "30 Days":
				$credit_days = "2";
				break;
			case "45 Days":
				$credit_days = "3";
				break;
		}
		return $credit_days;
	}

	private function _setCountryCode($country_id)
	{
		$country_code = "";
		if (!empty($country_id)) {
			$country = $this->db->get_where('mst_country', ['country_id' => $country_id])->row_array();
			$country_code = $country['bc365_county_id'];
		}

		return $country_code;
	}

	private function formatUri($queryString)
	{
		return str_replace(
			['$', ' '],
			$queryString
		);
	}

	private function _update_customer($customer)
	{
		$data = [
			"Line_No" => 0,
			"customerNo" => $customer['nav_customer_code'],
			"LIMSCustomerId" => $customer['customer_code'],
			"namE" => $customer['customer_name'],
			"Name2" => "",
			"Address" => $customer['address'],
			"Address2" => "",
			"countryCode" => $this->_setCountryCode($customer['cust_customers_country_id']),
			"City" => $customer['city'],
			"State" => "",
			"PostCode" => "",
			"EMail" => $customer['email'],
			"LocationCode" => "",
			"CreditLimit" => (float) $customer['credit_limit'],
			"CreditDays" => $this->_setCreditDays($customer['credit']), //credit $customer['credit'] ? : $this->_setCreditDays($customer['credit'])
			"UserID" => "NAV.CPS",
			"TelNo" => $customer['telephone']
		];
		// Convert the PHP array into a JSON format
		$payload = json_encode($data);
		$url = CUST_UPDATE_API;
		// Initialise new cURL session
		$ch = curl_init($url);

		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		// Return result of POST request
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		// Get information about last transfer
		curl_setopt($ch, CURLINFO_HEADER_OUT, true);

		// Use POST request
		curl_setopt($ch, CURLOPT_POST, true);

		// Set payload for POST request
		curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);

		// Set HTTP Header for POST request 
		curl_setopt(
			$ch,
			CURLOPT_HTTPHEADER,
			array(
				ERP_AUTH_KEY,
				'Content-Type: application/json',
				'Content-Length: ' . strlen($payload)
			)
		);
		// Execute a cURL session
		$result = curl_exec($ch);

		// Close cURL session
		curl_close($ch);
	}

	// END


	// Added by Saurabh to upload approve credit on 11-10-2021
	public function upload_credit_approve_doc()
	{
		$customer_id = $this->input->post('customer_id');

		$this->form_validation->set_rules('customer_id', 'Customer Name', 'required');
		if (empty($_FILES['credit_approve_doc']['name'])) {
			$this->form_validation->set_rules('credit_approve_doc', 'Document', 'required');
		}

		if ($this->form_validation->run()) {
			$file = $_FILES['credit_approve_doc'];
			$aws_path = $this->multiple_upload_image($file);
			$update = $this->db->update('cust_customers', ['credit_approved_doc' => $aws_path['aws_path']], ['customer_id' => $customer_id]);
			if ($update) {
				echo json_encode(['status' => 1, 'message' => 'File uploaded successfully']);
			} else {
				echo json_encode(['status' => 0, 'message' => 'Something went wrong.']);
			}
		} else {
			echo json_encode(['status' => 0, 'message' => 'Something went wrong.', 'error' => $this->form_validation->error_array()]);
		}
	}

	// Added by saurabh on 11-10-2021
	public function download_pdf()
	{
		$get = $this->input->get();
		$where = base64_decode($get['customer_id']);
		$path = $this->customers_model->download_pdf_aws($where);
		if ($path) {
			if ($path->credit_approved_doc) {
				$this->load->helper('download');
				$pdf_path    =   file_get_contents($path->credit_approved_doc);
				$pdf_name    =   basename($path->credit_approved_doc);
				force_download($pdf_name, $pdf_path);
			} else {
				echo '<h1>NO RECORD FOUND</h1>';
			}
		} else {
			echo '<h1>“This pdf stands cancelled. Please do not transact based on this cancelled pdf. Geo Chem will not be liable for any issues, financial, legal or otherwise, based on using this cancelled pdf for any purpose.</h1>';
		}
	}

	// Added by CHANDAN --14-07-2022
	public function gen_posting_group()
	{
		$key = ($this->input->get('key')) ? $this->input->get('key') : null;
		echo json_encode($this->customers_model->gen_posting_group($key));
	}

	public function vat_posting_group()
	{
		$key = ($this->input->get('key')) ? $this->input->get('key') : null;
		echo json_encode($this->customers_model->vat_posting_group($key));
	}

	public function excise_posting_group()
	{
		$key = ($this->input->get('key')) ? $this->input->get('key') : null;
		echo json_encode($this->customers_model->excise_posting_group($key));
	}
	public function customer_posting_group()
	{
		$key = ($this->input->get('key')) ? $this->input->get('key') : null;
		echo json_encode($this->customers_model->customer_posting_group($key));
	}

	// start upload logo vipin
	public function logo_path()
	{
		//echo "dd"; die;
		$customer_id = $this->input->post('customer_id');

		$this->form_validation->set_rules('customer_id', 'Customer Name', 'required');
		if (empty($_FILES['logo_path']['name'])) {
			$this->form_validation->set_rules('logo_path', 'Logo', 'required');
		}

		if ($this->form_validation->run()) {
			$file = $_FILES['logo_path'];
			//echo "<pre>"; print_r($file); die;
			$aws_path = $this->multiple_upload_image($file);
			$update = $this->db->update('cust_customers', ['logo_path' => $aws_path['aws_path']], ['customer_id' => $customer_id]);
			if ($update) {
				echo json_encode(['status' => 1, 'message' => 'Logo uploaded successfully']);
			} else {
				echo json_encode(['status' => 0, 'message' => 'Something went wrong.']);
			}
		} else {
			echo json_encode(['status' => 0, 'message' => 'Something went wrong.', 'error' => $this->form_validation->error_array()]);
		}
	}

	public function get_logo_path()
	{		
		$customer_id = $this->input->post('customer_id');
		$logo_path = $this->customers_model->get_logo_path($customer_id);
		if($logo_path['logo_path'] != ''){			
			//echo $logo_path['logo_path']; die;
			$logo = $logo_path['logo_path'];
			echo json_encode(['status' => 1, 'logo_path' => $logo]);
		} else{
			echo json_encode(['status' => 0, 'logo_path' => '']);
		}		
		
	}

	
	// end upload logo vipin

}
