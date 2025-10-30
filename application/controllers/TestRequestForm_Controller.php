<?php  
/**
 * 
 */
class TestRequestForm_Controller extends MY_Controller
{
	 
	function __construct(){
		parent::__construct();
		$this->load->model('TestRequestForm','trf');
		$this->check_session();
	}

	public function add_open_trf(){
		$checkUser = $this->session->userdata('user_data');
		$data['applicant'] = $this->trf->get_applicant_name();
		$data['agent'] = $this->trf->get_agent_name();
		$data['third_party'] = $this->trf->get_third_party();
		$data['products'] = $this->trf->get_products();
		$data['country'] = $this->trf->get_country();
		$data['currency'] = $this->trf->get_currency();
		$data['application_care_instruction'] = $this->trf->get_application_care_instruction();
		$data['crm_user_list'] = $this->trf->get_crm_user();
		$data['division_list'] = $this->trf->get_division();
		$data['temp_reg'] = $this->trf->get_temp_reg();
		$data['branchs']=$this->trf->get_result('branch_id,branch_name','mst_branches', ['status > '=>0]);
		$custom_field[0] = [];
		$data['custom_field'] = $custom_field;
		
		// Check whether form is subitted or not
		if ($this->input->post()) {
			$temp_ref_id 		= $this->input->post('temp_ref_id');
			$customer_type 		= $this->input->post('trf_customer_type');
			$customer_id		= $this->input->post('trf_applicant');
			$service 			= $this->input->post('trf_service_type');
			$applicant 			= $this->input->post('trf_applicant');
			$buyer 				= ($this->input->post('trf_buyer')) ? $this->input->post('trf_buyer') : 0;
			$agent 				= ($this->input->post('trf_agent') == "Select Agent") ? 0 : $this->input->post('trf_agent');
			//$trf_contact		= $this->input->post('trf_contact');
			$trf_contact = $this->input->post('trf_contact');
			if ($trf_contact > 0) {
				$trf_contact = implode(",", $this->input->post('trf_contact'));
			} else
				$trf_contact = '';

			$trf_thirdparty 	= $this->input->post('trf_thirdparty');
			$trf_sample_ref_id	= $this->input->post('trf_sample_ref_id');
			$trf_sample_desc	= $this->input->post('trf_sample_desc');
			$invoice_to			= $this->input->post('invoice_to');
			$invtocontant = $this->input->post('trf_invoice_to_contact');
			if ($invtocontant > 0) {
				$trf_invoice_to_contact = implode(",", $this->input->post('trf_invoice_to_contact'));
			} else
				$trf_invoice_to_contact = '';
			$trf_no_of_sample	= $this->input->post('trf_no_of_sample');
			$cc_id				= $this->input->post('cc_id');
			$trf_cc 			= $this->input->post('trf_cc');
			$bcc_id				= $this->input->post('bcc_id');
			$trf_bcc			= $this->input->post('trf_bcc');
			$trf_client_ref_no	= $this->input->post('trf_client_ref_no');
			$trf_country_destination = $this->input->post('trf_country_destination');
			$trf_country_orgin	= $this->input->post('trf_country_orgin');
			$open_trf_currency_id = $this->input->post('open_trf_currency_id');
			$open_trf_exchange_rate = $this->input->post('open_trf_exchange_rate');
			$trf_product 		= $this->input->post('trf_product');
			$trf_end_use		= $this->input->post('trf_end_use');
			$tat_date			= $this->input->post('tat_date');
			$division			= $this->input->post('division');
			$reported_to		= $this->input->post('reported_to');	 	
		 	$crm_user_id		= $this->input->post('crm_user_id');
		 	$sample_return_to	= $this->input->post('sample_return_to');
		 	$care_instruction 	= $this->input->post('care_instruction');
		 	$sample_pickup_services = $this->input->post('sample_pickup_services');
			$dynamic			= $this->input->post('dynamic_field')?$this->input->post('dynamic_field'):''; 
			$trf_branch			= ($this->input->post('trf_branch')?$this->input->post('trf_branch'):$checkUser->branch_id); 
		 	$sales_person	= $this->input->post('sales_person');
			// Check test name
			$test = $this->input->post('test');
			 
			//  Form validation
			$this->form_validation->set_rules('trf_customer_type','Customer Type','required');
			// $this->form_validation->set_rules('open_trf_customer_id','Customer','required');
			$this->form_validation->set_rules('trf_service_type','Service Type','required');
			$this->form_validation->set_rules('trf_applicant','Applicant','required');
			$this->form_validation->set_rules('trf_buyer','Buyer','required');
			$this->form_validation->set_rules('trf_contact[]','Contact Person','required');
			$this->form_validation->set_rules('trf_sample_desc','Sample Description','required');
			$this->form_validation->set_rules('invoice_to','Invoice to','required');
			$this->form_validation->set_rules('trf_invoice_to_contact[]','Invoice to Contact','required');
			// $this->form_validation->set_rules('trf_country_destination','Country Of Destination','required');
			$this->form_validation->set_rules('open_trf_currency_id','Country Currency','required');
			$this->form_validation->set_rules('open_trf_exchange_rate','Exchange Rate','required');
			// $this->form_validation->set_rules('trf_country_orgin','Country Of Origin','required');
			$this->form_validation->set_rules('trf_product','Product','required');
			// $this->form_validation->set_rules('trf_end_use','End Use','required');
			$this->form_validation->set_rules('division','Division','required');
			$this->form_validation->set_rules('crm_user_id[]','CRM User','required');
			$this->form_validation->set_rules('trf_no_of_sample','No. of sample','required');
			$this->form_validation->set_rules('test[]','Test Name and Price','required');
			$this->form_validation->set_rules('sample_return_to[]','Sample Return to','required');
			$this->form_validation->set_rules('reported_to[]','Reported to','required');
			$this->form_validation->set_rules('trf_branch','Branch','required');
			$this->form_validation->set_rules('sales_person','Sales Person','required');



			if ($this->form_validation->run()) {
				$record = array(
					'trf_applicant'			=> $applicant,
					'trf_contact'			=> (is_array($trf_contact)) ? implode(",", $trf_contact) : $trf_contact,
					'trf_sample_ref_id'		=> $trf_sample_ref_id,
					'trf_invoice_to'		=> $invoice_to,
					'trf_invoice_to_contact' => $trf_invoice_to_contact,
					'trf_product'			=> $trf_product,
					'work_id'				=> 0,
					'trf_buyer'				=> $buyer,
					'trf_agent'				=> (isset($agent) && !empty($agent)) ? $agent : 0,
					'trf_sample_desc'		=> $trf_sample_desc,
					'trf_no_of_sample'		=> $trf_no_of_sample,
					'trf_country_destination' => $trf_country_destination,
					'trf_end_use'			=> $trf_end_use,
					'trf_quote_id'			=> 0,
					'trf_client_ref_no'		=> $trf_client_ref_no,
					'quote_customer_id'		=> 0,
					'reported_to'			=> (is_array($reported_to)) ? implode(",", $reported_to) : $reported_to,
					'sample_return_to'		=> (is_array($sample_return_to)) ? implode(",", $sample_return_to) : $sample_return_to,
					'open_trf_currency_id'	=> $open_trf_currency_id,
					'open_trf_customer_id'	=> $customer_id,
					'open_trf_customer_type' => $customer_type,
					'open_trf_exchange_rate' => $open_trf_exchange_rate,
					'trf_thirdparty'		=> (isset($trf_thirdparty) && !empty($trf_thirdparty)) ? $trf_thirdparty : 0,
					'trf_cc'				=> ($trf_cc) ? implode(",", $trf_cc) : '',
					'cc_type'				=> ($cc_id) ? implode(",", $cc_id) : '',
					'trf_bcc'				=> ($trf_bcc) ? implode(",", $trf_bcc) : '',
					'bcc_type'				=> ($bcc_id) ? implode(",", $bcc_id) : '',
					'trf_country_orgin'		=> $trf_country_orgin,
					'trf_branch'			=> $trf_branch,
					'tat_date'				=> (!empty($tat_date))?$tat_date:'0000-00-00 00:00:00',
					'trf_type'				=> "Open TRF",
					'division'				=> $division,
					'create_on'				=> date('Y-m-d H:i'),
					'updated_by'			=> $this->admin_id(),
					'crm_user_id'			=> implode(',',$crm_user_id),
					'sample_pickup_services' => $sample_pickup_services,
					'temp_ref_id'			=> isset($temp_ref_id) ? $temp_ref_id : 0,
					'clone_trf_id'			=> 0,
					'sales_person'			=> $sales_person,
					'regulation_desc'		=> $this->input->post('regulation_desc'),
					'trf_package_id'		=> $this->input->post('test_package'),
					'trf_protocol_id'		=> $this->input->post('test_protocol'),
					'wash_care'		        => $this->input->post('wash_care')
				);
  				// Added by Saurabh on 02-08-2021 to save regulation image
				if(!empty($_FILES['regulation_image']['name'])){
					$image['type'] = $_FILES["regulation_image"]["type"];
					$image['name'] = $_FILES["regulation_image"]["name"];
					$image['tmp_name'] = $_FILES["regulation_image"]["tmp_name"];
					$valid_file = $this->check_valid_file_upload($image);
					$record['regulation_image'] = $valid_file['image'];
				}
				// Added by Saurabh on 02-08-2021 to save regulation image
   
				if ($service >= 2 && $service <= 30) {
					$record['service_days'] = $service;
					$record['trf_service_type'] = "Regular";
				} else {
					$record['service_days'] = '';
					$record['trf_service_type'] = $service;
				}

				// save application care provided instruction
				$care_instructions = $this->input->post('dynamic');
				foreach ($care_instructions as $care) {
					$care['description'] = $care['description'];
					$care_instruction[] = $care;
				}
				if (!empty($dynamic)) {
					foreach ($dynamic as $key => $dynamic_values) {
						if (!empty($dynamic_values)) {
							if($dynamic_values[0] == 'Style No.' || $dynamic_values[0] == 'Style No' || $dynamic_values[0] == 'Style Number'){
								$record['style_number'] = $dynamic_values[1];
							}
							$dyn_values[] = $dynamic_values;
						} else {
							$dyn_values = [];
						}
					}
				} else {
					$dyn_values = [];
				}
				// echo "<pre>"; print_r($record);
				// echo "<pre>"; print_r($dyn_values);
				// echo "<pre>"; print_r($test);
				// echo "<pre>"; print_r($care_instruction);
				// die;
				// Save open trf data
				$save = $this->trf->save_open_trf($record, $dyn_values, $test, $care_instruction);

				if ($save['success']) {
					$msg = "TRF with " . $save['unique_id'] . " is created";

					$user_log = array(
						'module'	=> 'jobs',
						'old_status' => '',
						'new_status' => 'New',
						'operation'	 => 'add_open_trf',
						'source_module'	=> 'TestRequestForm_Controller',
						'action_message' => 'Open TRF created',
						'trf_id'	=> $save['inserted_id'],
						'uidnr_admin'	=> $this->admin_id()
					);
   
					$save_log = $this->trf->save_user_log($user_log);
					echo json_encode(["message" => 'Open TRF with ' . $save['unique_id'] . ' is created', "status" => 1]);
					exit;
					// $this->session->set_flashdata('success', 'Open TRF with ' . $save['unique_id'] . ' is created');
					// return redirect('open-trf-list');
				} else {
					echo json_encode(["message" => "Something Went Wrong!.", "status" => 0]);
					exit;
				}
			} else {
				echo json_encode(["message" => "Something Went Wrong!.","error" => $this->form_validation->error_array(), "status" => 0]);
				exit;
				// // Get customer 
				// $data['customer_user_id'] = $this->trf->get_customer($customer_type);
				// // Get Buyer
				// $data['buyers'] = $this->trf->get_buyer_name($applicant);
				// // Get Agent
				// $data['buyers'] = $this->trf->get_buyer_name($applicant);
				// // Get Contact Person
				// $data['contact_person'] = $this->trf->get_contact_person($applicant);
				// // Contact person invoice to
				// if (!empty($invoice_to)) {
				// 	if ($invoice_to == "Factory") {
				// 		$user = $applicant;
				// 	} elseif ($invoice_to == "Buyer") {
				// 		$user = $buyer;
				// 	} elseif ($invoice_to == "Agent") {
				// 		$user = $agent;
				// 	} else {
				// 		$user = $trf_thirdparty;
				// 	}
				// 	$data['cp_invoice_to'] = $this->trf->get_contact_name($user);
				// }
				// // CC Contact
				// if (!empty($cc_id)) {
				// 	$cc_user = "";
				// 	if (in_array("Factory", $cc_id)) {
				// 		if (empty($cc_user)) {
				// 			$cc_user = $applicant;
				// 		} else {
				// 			$cc_user = $cc_user . "," . $applicant;
				// 		}
				// 	}
				// 	if (in_array("Buyer", $cc_id)) {
				// 		if (empty($cc_user)) {
				// 			$cc_user = $buyer;
				// 		} else {
				// 			$cc_user = $cc_user . "," . $buyer;
				// 		}
				// 	}
				// 	if (in_array("Agent", $cc_id)) {
				// 		if (empty($cc_user)) {
				// 			$cc_user = $agent;
				// 		} else {
				// 			$cc_user = $cc_user . "," . $agent;
				// 		}
				// 	}
				// 	if (in_array("ThirdParty", $cc_id)) {
				// 		if (empty($cc_user)) {
				// 			$cc_user = $trf_thirdparty;
				// 		} else {
				// 			$cc_user = $cc_user . "," . $trf_thirdparty;
				// 		}
				// 	}
				// 	$data['cc_contact'] = $this->trf->get_contact_name($cc_user);
				// }
				// // Get BCC contact
				// if (!empty($bcc_id)) {
				// 	$bcc_user = "";
				// 	if (in_array("Factory", $bcc_id)) {
				// 		if (empty($bcc_user)) {
				// 			$bcc_user = $applicant;
				// 		} else {
				// 			$bcc_user = $bcc_user . "," . $applicant;
				// 		}
				// 	}
				// 	if (in_array("Buyer", $bcc_id)) {
				// 		if (empty($bcc_user)) {
				// 			$bcc_user = $buyer;
				// 		} else {
				// 			$bcc_user = $bcc_user . "," . $buyer;
				// 		}
				// 	}
				// 	if (in_array("Agent", $bcc_id)) {
				// 		if (empty($bcc_user)) {
				// 			$bcc_user = $agent;
				// 		} else {
				// 			$bcc_user = $bcc_user . "," . $agent;
				// 		}
				// 	}
				// 	if (in_array("ThirdParty", $bcc_id)) {
				// 		if (empty($bcc_user)) {
				// 			$bcc_user = $trf_thirdparty;
				// 		} else {
				// 			$bcc_user = $bcc_user . "," . $trf_thirdparty;
				// 		}
				// 	}
				// 	$data['bcc_contact'] = $this->trf->get_contact_name($bcc_user);
				// }

				// if (!empty($trf_product)) {
				// 	// Get tests
				// 	$data['test'] = $this->trf->get_test_name($trf_product);

				// 	$fields = $this->trf->check_fields($trf_product);
				// 	if (!empty($fields)) {
				// 		foreach ($fields as $key => $value) {
				// 			$field 	= '<tr  data-row=' . $key . '>';
				// 			$field .= '<td>';
				// 			$field .= '<input type="text" class="form-control form-control-sm" value="' . $value['registration_fields_name'] . '" name="dynamic_field[' . $key . '][0]">';
				// 			$field .= '</td>';
				// 			$field .= '<td>';
				// 			$field .= '<input type="text" name="dynamic_field[' . $key . '][1]" class="form-control form-control-sm">';
				// 			$field .= '</td>';
				// 			$field .= '<td><a href="javascript:void(0)" title="Remove Row" class="remove_row btn btn-danger">X</a></td>';
				// 			$field .= '</tr>';
				// 			$custom_field[] = $field;
				// 		}
				// 	} else {
				// 		$custom_field = [];
				// 	}
				// } else{
				// 	$custom_field = [];
				// }
				// $data['custom_field'] = $custom_field;
				}
				
			}
			$this->load_view('trf/add_open_trf',$data);
		} 

	public function get_agent_name()
	{
		$key = $this->input->get('key');
		$data = $this->trf->get_agent_name($key);
		echo json_encode($data);
	}

	public function get_third_party()
	{
		$key = $this->input->get('key');
		$data = $this->trf->get_third_party($key);
		echo json_encode($data);
	}
	

	public function open_trf_list()
	{
		//$this->output->enable_profiler(true);
		$data['products'] = $this->trf->get_products();
		$checkUser = $this->session->userdata('user_data');
        $cust_where = NULL;
        $buyer_where = ['customer_type'=>'Buyer'];

		// if(exist_val('Branch/Wise',$this->session->userdata('permission'))){
        //     $multibranch = $this->session->userdata('branch_ids');
        //     $buyer_where['mst_branch_id IN ('.$multibranch.') ']=null;
        //     $cust_where['mst_branch_id IN ('.$multibranch.') ']=null;
        // }
		
        $data['customer'] = $this->trf->get_result( "customer_id,customer_name","cust_customers",$cust_where);
        $data['buyer'] = $this->trf->get_result("customer_id,customer_name","cust_customers", $buyer_where);
		
		// $data['division'] = $this->trf->get_fields("mst_divisions", "division_id,division_name");
		// Changed by saurabh on 11-08-2021
		$data['division'] = $this->trf->get_fields_by_id("mst_divisions","*",'1','status');
		// Changed by saurabh on 11-08-2021
		$this->load_view('trf/open_trf_list', $data);
	}

	public function get_customer_name()
	{
		$type = $this->input->get('customer_type');
		$key = ($this->input->get('key'))?$this->input->get('key'):null;
		$data = $this->trf->get_customer($type,$key);
		echo json_encode($data);
	}

	public function get_contact_person_name()
	{
		$applicant = $this->input->get('applicant');
		$key = $this->input->get('key');
		$data = $this->trf->get_contact_person($applicant,$key);
		echo json_encode($data);
	}

	public function get_buyer_name()
	{
		$applicant = $this->input->get('applicant');
		$key = ($this->input->get('key'))?$this->input->get('key'):null;
		$data = $this->trf->get_buyer_name($applicant,$key);
		echo json_encode($data);
	}

	public function get_exchange_rate(){
		$currency = $this->input->post('country_currency');
		$data = $this->trf->get_exchange_rate($currency);
		echo json_encode($data);
	}

	public function get_applicant()
	{
		$key = $this->input->get('key');
		$data = $this->trf->get_applicant_name($key);
		echo json_encode($data);
	}

	// Functoin to get Invoice Contact, CC, and BCC contacts
	public function get_contact_name()
	{
		$user = $this->input->get('user');
		$key = $this->input->get('key');
		$data = $this->trf->get_contact_name($user,$key);
		echo json_encode($data);
	}

	public function get_test_name()
	{
		$product_id = ($this->input->get()) ? $this->input->get('product') : $this->input->post('product');
		$key = ($this->input->get('key')) ? $this->input->get('key') : null;
		$data  = $this->trf->get_test_name($product_id, $key); //Updated by Saurabh on 12-10-2021
		echo json_encode($data);
	}

	public function get_custom_fields()
	{
		$product_id = $this->input->post('product_id');
		$fields = $this->trf->check_fields($product_id);
		if (!empty($fields)) {
			foreach ($fields as $key => $value) {
				$field 	= '<tr  data-row=' . $key . '>';
				$field .= '<td>';
				$field .= '<input type="text" class="form-control form-control-sm" value="' . $value['registration_fields_name'] . '" name="dynamic_field[' . $key . '][0]">';
				$field .= '</td>';
				$field .= '<td>';
				$field .= '<input type="text" name="dynamic_field[' . $key . '][1]" class="form-control form-control-sm">';
				$field .= '</td>';
				$field .= '<td><a href="javascript:void(0)" title="Remove Row" class="remove_row btn btn-danger">X</a></td>';
				$field .= '</tr>';
				$custom_field[] = $field;
			}
		} else {
			$custom_field = [];
		}
		// echo "<pre>"; print_r($custom_field); die;
		echo json_encode($custom_field);
	}

	public function open_trf_record($page = 0, $trf_ref_no, $cust_name, $product, $created_on, $created_by, $service_type, $column, $order, $buyer, $status, $division,$applicant)
	{
		$per_page = 10;
		if ($page != 0) {
			$page = ($page - 1) * $per_page;
		}

		$total_count = $this->trf->get_open_trf($per_page, $page, $trf_ref_no, $cust_name, $product, $created_on, $created_by, $service_type, $column, $order, $buyer, $status, $division,$applicant,true); 
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
		$config['base_url'] = base_url() . "open-trf-records";
		$config['use_page_numbers'] = TRUE;
		$config['uri_segment'] = 3;
		$config['total_rows'] = $total_count;
		$config['per_page'] = $per_page;
		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();

		// echo $order; die;
		$data['result'] = $this->trf->get_open_trf($per_page, $page, $trf_ref_no, $cust_name, $product, $created_on, $created_by, $service_type, $column, $order, $buyer, $status, $division,$applicant);
		// echo "<pre>"; print_r($data['result']); die;
		$data['row'] = $page;
		if ($order == "null") {
			$data['order'] = "desc";
		} else {
			$data['order'] = $order;
		}
		if($total_count > 0){
            $start = (int)$page + 1;
        } else {
            $start = 0;
        }
		// echo count($data['result']); die;
		$end = (($data['result']) ? count($data['result']) : 0) + (($page) ? $page : 0);
        // $end = (($data['result']) ? count($data['result']) : 0) + (($page) ? $page : 0);
        $data['result_count'] = "Showing " . $start . " - " . $end . " of " . $total_count . " Results";
		
		echo json_encode($data);
	}

	public function edit_open_trf($id)
	{
		$checkUser = $this->session->userdata('user_data');
		$data['branchs']=$this->trf->get_result('branch_id,branch_name','mst_branches', ['status > '=>0]);
		$table = "trf_registration";
		$column_name = "trf_id";
		$data['trf'] = $this->trf->get_data_by_id($table, $id, $column_name);
		$trf_id = $data['trf']->trf_id; // updated by millan on 08-10-2021
		$data['temp_reg'] = $this->trf->get_temp_reg();
		// Get selected application care provided instruction 
		$data['selected_application_care_instruction'] = $this->trf->get_fields_by_id('trf_apc_instruction', '*', $id, 'trf_id');
		// Get Customer user id
		// Changed by Saurabh on 21-07-2021 to get selected customer
		$customer_id = $data['trf']->open_trf_customer_id;
		$data['customer_user_id'] = $this->trf->get_selected_customer($customer_id);
		// Applicant data
		$applicant = $data['trf']->trf_applicant;
		$third_party = $data['trf']->trf_thirdparty;
		$data['applicant'] = $this->trf->selected_applicant_name($applicant);
		// Get buyers
		$data['buyers'] = $this->trf->get_buyer_name($applicant);
		// Agent Data
		$data['agent'] = $this->trf->get_agent_name();
		// Get third party 
		// Changed by saurabh on 21-09-2021
		$data['third_party'] = $this->trf->get_selected_third_party($third_party);
		// Get contact person
		$data['contact_person'] = $this->trf->get_contact_person($applicant);
		// Product list
		$data['products'] = $this->trf->get_products();
		// Country list
		$data['country'] = $this->trf->get_country();
		// Currency list
		$data['currency'] = $this->trf->get_currency();
		// Application care instruction
		$data['application_care_instruction'] = $this->trf->get_application_care_instruction();
		// CRM user list
		$data['crm_user_list'] = $this->trf->get_crm_user();
		// Division list
		$data['division_list'] = $this->trf->get_division();
		// selected sales person
		$data['sales_person'] = $this->trf->selected_sales_person($data['trf']->sales_person);
		// Contact person invoice to
		$invoice_to = $data['trf']->trf_invoice_to;
		if ($invoice_to == "Factory") {
			$user = $data['trf']->trf_applicant;
		} elseif ($invoice_to == "Buyer") {
			$user = $data['trf']->trf_buyer;
		} elseif ($invoice_to == "Agent") {
			$user = $data['trf']->trf_agent;
		} else {
			$user = $data['trf']->trf_thirdparty;
		}
		 
		$data['cp_invoice_to'] = $this->trf->get_contact_name($user);
		// Get cc Contact
		$cc_type = explode(",", $data['trf']->cc_type);
		$cc_user = "";
		if (in_array("Factory", $cc_type)) {
			if (empty($cc_user)) {
				$cc_user = $data['trf']->trf_applicant;
			} else {
				$cc_user = $cc_user . "," . $data['trf']->trf_applicant;
			}
		}
		if (in_array("Buyer", $cc_type)) {
			if (empty($cc_user)) {
				$cc_user = $data['trf']->trf_buyer;
			} else {
				$cc_user = $cc_user . "," . $data['trf']->trf_buyer;
			}
		}
		if (in_array("Agent", $cc_type)) {
			if (empty($cc_user)) {
				$cc_user = $data['trf']->trf_agent;
			} else {
				$cc_user = $cc_user . "," . $data['trf']->trf_agent;
			}
		}
		if (in_array("ThirdParty", $cc_type)) {
			if (empty($cc_user)) {
				$cc_user = $data['trf']->trf_thirdparty;
			} else {
				$cc_user = $cc_user . "," . $data['trf']->trf_thirdparty;
			}
		}

		$data['cc_contact'] = $this->trf->get_contact_name($cc_user);
		// Get bcc Contact
		$bcc_type = explode(",", $data['trf']->bcc_type);
		$bcc_user = "";
		if (in_array("Factory", $bcc_type)) {
			if (empty($bcc_user)) {
				$bcc_user = $data['trf']->trf_applicant;
			} else {
				$bcc_user = $bcc_user . "," . $data['trf']->trf_applicant;
			}
		}
		if (in_array("Buyer", $bcc_type)) {
			if (empty($bcc_user)) {
				$bcc_user = $data['trf']->trf_buyer;
			} else {
				$bcc_user = $bcc_user . "," . $data['trf']->trf_buyer;
			}
		}
		if (in_array("Agent", $bcc_type)) {
			if (empty($bcc_user)) {
				$bcc_user = $data['trf']->trf_agent;
			} else {
				$bcc_user = $bcc_user . "," . $data['trf']->trf_agent;
			}
		}
		if (in_array("ThirdParty", $bcc_type)) {
			if (empty($bcc_user)) {
				$bcc_user = $data['trf']->trf_thirdparty;
			} else {
				$bcc_user = $bcc_user . "," . $data['trf']->trf_thirdparty;
			}
		}
		$data['bcc_contact'] = $this->trf->get_contact_name($bcc_user);
		// Get test by product
		$product_id = $data['trf']->trf_product;
		$data['test'] = $this->trf->get_test_name($product_id);
		if(!empty($data['trf']->trf_package_id)){
            $data['test']  = $this->trf->get_package_test($data['trf']->trf_package_id);
        } elseif(!empty($data['trf']->trf_protocol_id)){
            $data['test']  = $this->trf->get_protocol_test($data['trf']->trf_protocol_id);
        } else {
            $data['test']  = $this->trf->get_test_name($product_id);
        }
		// Selected tests
		$data['selected_test'] = $this->trf->open_trf_selected_test($id);
		// echo "<pre>"; print_r($data['selected_test']); die;
		$custom_field = [];
		
		if (!empty($data['trf']->product_custom_fields)) {
			$fields = json_decode($data['trf']->product_custom_fields);
			
			foreach ($fields as $key => $value) {
				$field 	= '<tr  data-row=' . $key . '>';
				$field .= '<td>';
				$field .= '<input type="text" class="form-control form-control-sm" value="' . $value[0] . '" name="dynamic_field[' . $key . '][0]">';
				$field .= '</td>';
				$field .= '<td>';
				$field .= '<input type="text" name="dynamic_field[' . $key . '][1]" class="form-control form-control-sm" value="' . $value[1] . '">';
				$field .= '</td>';
				$field .= '<td><a href="javascript:void(0)" title="Remove Row" class="remove_row btn btn-danger">X</a></td>';
				$field .= '</tr>';
				$custom_field[] = $field;
			}
		} 
			
	
		$data['custom_field'] = $custom_field;

		// Get Package name added by saurabh on 13-10-2021
		if (!empty($data['trf']->trf_package_id)) {
			$package_id = $data['trf']->trf_package_id;
			$data['package'] = $this->trf->package_details($package_id);
		}
		// Get protocol name added by saurabh on 13-10-2021
		if (!empty($data['trf']->trf_protocol_id)) {
			$protocol_id = $data['trf']->trf_protocol_id;
			$data['protocol'] = $this->trf->protocol_details($protocol_id);
		}
		// echo "<pre>"; print_r($custom_field); die;
		// Create Variables to save data
		if ($this->input->post()) {
			$temp_ref_id 		= $this->input->post('temp_ref_id');
			$customer_type 		= $this->input->post('trf_customer_type');
			$customer_id		= $this->input->post('trf_applicant');
			$service 			= $this->input->post('trf_service_type');
			$applicant 			= $this->input->post('trf_applicant');
			$buyer 				= $this->input->post('trf_buyer');
			$agent 				= $this->input->post('trf_agent');
			$trf_contact		= $this->input->post('trf_contact');
			$trf_thirdparty 	= $this->input->post('trf_thirdparty');
			$trf_sample_ref_id	= $this->input->post('trf_sample_ref_id');
			$trf_sample_desc	= $this->input->post('trf_sample_desc');
			$invoice_to			= $this->input->post('invoice_to');
			$invtocontant = $this->input->post('trf_invoice_to_contact');
			if ($invtocontant > 0) {
				$trf_invoice_to_contact = implode(",", $this->input->post('trf_invoice_to_contact'));
			} else {
				$trf_invoice_to_contact = '';
			}
			$trf_no_of_sample	= $this->input->post('trf_no_of_sample');
			$cc_id				= $this->input->post('cc_id');
			$trf_cc 			= $this->input->post('trf_cc');
			$bcc_id				= $this->input->post('bcc_id');
			$trf_bcc			= $this->input->post('trf_bcc');
			$trf_client_ref_no	= $this->input->post('trf_client_ref_no');
			$trf_country_destination = $this->input->post('trf_country_destination');
			$trf_country_orgin	= $this->input->post('trf_country_orgin');
			$open_trf_currency_id = $this->input->post('open_trf_currency_id');
			$open_trf_exchange_rate = $this->input->post('open_trf_exchange_rate');
			$trf_product 		= $this->input->post('trf_product');
			$trf_end_use		= $this->input->post('trf_end_use');
			$tat_date			= $this->input->post('tat_date');
			$division			= $this->input->post('division');
			$reported_to		= $this->input->post('reported_to');
			$crm_user_id		= $this->input->post('crm_user_id');
			$sample_return_to	= $this->input->post('sample_return_to');
			$care_instruction 	= $this->input->post('care_instruction');
			$sample_pickup_services = $this->input->post('sample_pickup_services');
			$dynamic			= $this->input->post('dynamic_field') ? $this->input->post('dynamic_field') : '';
			$trf_branch			= ($this->input->post('trf_branch')?$this->input->post('trf_branch'):$checkUser->branch_id);			
			// Check test name
			$test = $this->input->post('test');
			$sales_person = $this->input->post('sales_person');

			//  Form validation
			$this->form_validation->set_rules('trf_customer_type', 'Customer Type', 'required');
			// $this->form_validation->set_rules('open_trf_customer_id', 'Customer', 'required');
			$this->form_validation->set_rules('trf_service_type', 'Service Type', 'required');
			$this->form_validation->set_rules('trf_applicant', 'Applicant', 'required');
			$this->form_validation->set_rules('trf_buyer','Buyer','required');
			$this->form_validation->set_rules('trf_contact[]', 'Contact Person', 'required');
			$this->form_validation->set_rules('trf_sample_desc', 'Sample Description', 'required');
			$this->form_validation->set_rules('invoice_to', 'Invoice to', 'required');
			$this->form_validation->set_rules('trf_invoice_to_contact[]', 'Invoice to Contact', 'required');
			// $this->form_validation->set_rules('trf_country_destination','Country Of Destination','required');
			$this->form_validation->set_rules('open_trf_currency_id', 'Country Currency', 'required');
			$this->form_validation->set_rules('open_trf_exchange_rate', 'Exchange Rate', 'required');
			// $this->form_validation->set_rules('trf_country_orgin','Country Of Origin','required');
			$this->form_validation->set_rules('trf_product', 'Product', 'required');
			// $this->form_validation->set_rules('trf_end_use','End Use','required');
			$this->form_validation->set_rules('division', 'Division', 'required');
			$this->form_validation->set_rules('crm_user_id[]', 'CRM User', 'required');
			$this->form_validation->set_rules('trf_no_of_sample', 'No. of sample', 'required');
			// Commented by Saurabh on 18-08-2021, not to change test data
			// $this->form_validation->set_rules('griddata[]', 'Test Name and Price', 'required');
			$this->form_validation->set_rules('sample_return_to[]', 'Sample Return to', 'required');
			$this->form_validation->set_rules('reported_to[]', 'Reported to', 'required');
			$this->form_validation->set_rules('trf_branch','Branch','required');
			$this->form_validation->set_rules('sales_person','Sales Person','required');

			if ($this->form_validation->run()) {
			$record = array(
					'trf_applicant'			=> $applicant,
					'trf_contact'			=> (is_array($trf_contact)) ? implode(",", $trf_contact) : $trf_contact,
					'trf_sample_ref_id'		=> $trf_sample_ref_id,
					'trf_invoice_to'		=> $invoice_to,
					'trf_invoice_to_contact' => $trf_invoice_to_contact,
					'trf_product'			=> $trf_product,
					'work_id'				=> 0,
					'trf_buyer'				=> $buyer,
					'trf_branch'			=> $trf_branch,
					'trf_agent'				=> (isset($agent) && !empty($agent)) ? $agent : 0,
					'trf_sample_desc'		=> $trf_sample_desc,
					'trf_no_of_sample'		=> $trf_no_of_sample,
					'trf_country_destination' => $trf_country_destination,
					'trf_end_use'			=> $trf_end_use,
					'trf_quote_id'			=> 0,
					'trf_client_ref_no'		=> $trf_client_ref_no,
					'quote_customer_id'		=> 0,
					'reported_to'			=> (is_array($reported_to)) ? implode(",", $reported_to) : $reported_to,
					'sample_return_to'		=> (is_array($sample_return_to)) ? implode(",", $sample_return_to) : $sample_return_to,
					'open_trf_currency_id'	=> $open_trf_currency_id,
					'open_trf_customer_id'	=> $customer_id,
					'open_trf_customer_type' => $customer_type,
					'open_trf_exchange_rate' => $open_trf_exchange_rate,
					'trf_thirdparty'		=> (isset($trf_thirdparty) && !empty($trf_thirdparty)) ? $trf_thirdparty : 0,
					'trf_cc'				=> ($trf_cc) ? implode(",", $trf_cc) : '',
					'cc_type'				=> ($cc_id) ? implode(",", $cc_id) : '',
					'trf_bcc'				=> ($trf_bcc) ? implode(",", $trf_bcc) : '',
					'bcc_type'				=> ($bcc_id) ? implode(",", $bcc_id) : '',
					'trf_country_orgin'		=> $trf_country_orgin,
					'tat_date'				=> (!empty($tat_date)) ? $tat_date : '0000-00-00 00:00:00',
					'trf_type'				=> "Open TRF",
					'division'				=> $division,
					'create_on'				=> date('Y-m-d H:i'),
					// 'updated_by'			=> $this->admin_id(),
					'crm_user_id'			=> implode(',',$crm_user_id),
					'sample_pickup_services' => $sample_pickup_services,
					'temp_ref_id'			=> isset($temp_ref_id) ? $temp_ref_id : 0,
					'clone_trf_id'			=> 0,
					'trf_ref_no'			=> $data['trf']->trf_ref_no,
					'sales_person'			=> $sales_person,
					'regulation_desc'		=> $this->input->post('regulation_desc'),
					'trf_package_id'		=> $this->input->post('test_package'),
					'trf_protocol_id'		=> $this->input->post('test_protocol'),
					'wash_care'		        => $this->input->post('wash_care')
				);
				// Added by Saurabh on 02-08-2021 to save regulation image
				if(!empty($_FILES['regulation_image']['name'])){
					$image['type'] = $_FILES["regulation_image"]["type"];
					$image['name'] = $_FILES["regulation_image"]["name"];
					$image['tmp_name'] = $_FILES["regulation_image"]["tmp_name"];
					$valid_file = $this->check_valid_file_upload($image);
					$record['regulation_image'] = $valid_file['image'];
				}
				// Added by Saurabh on 02-08-2021 to save regulation image
   
				if ($service >= 2 && $service <= 30) {
					$record['service_days'] = $service;
					$record['trf_service_type'] = "Regular";
				} else {
					$record['service_days'] = '';
					$record['trf_service_type'] = $service;
				}
				$dyn_values = [];
				$record['style_number'] = NULL;
				if(!empty($dynamic)){
					foreach ($dynamic as $key => $dynamic_values) {
						if (!empty($dynamic_values)) {
							if($dynamic_values[0] == 'Style No.' || $dynamic_values[0] == 'Style No' || $dynamic_values[0] == 'Style Number'){
								$record['style_number'] = $dynamic_values[1];
							} 
							$dynamic_value[0] = html_escape($dynamic_values[0]);
							$dynamic_value[1] = html_escape($dynamic_values[1]);
							$dyn_values[] = $dynamic_value;
						} 
					}
				}
				

				// save application care provided instruction
				$care_instructions = $this->input->post('dynamic');
				$care_instruction = [];
				if(!empty($care_instructions)){
					foreach ($care_instructions as $care) {
						$care['description'] = html_escape($care['description']);
						$care_instruction[] = $care;
					}
				}
				
				/* added by millan on 08-10-2021 */
				if( (!empty($trf_id) && $trf_id!="") && (!empty($record['tat_date']) && $record['tat_date']!="0000-00-00 00:00:00") ){
					$check_sr = $this->trf->check_trf_id($trf_id);
					if($check_sr && !empty($check_sr)){
						$this->trf->update_data('sample_registration', array('due_date' => $record['tat_date']), array('trf_registration_id' => $trf_id, 'sample_reg_id' => $check_sr->sample_reg_id));
					}
				}
				/* aaded by millan on 08-10-2021 */
				// echo "<pre>"; print_r($test); die;
				// if($checkUser->uidnr_admin == 1){
				// 	echo '<pre>'; print_r($record); die;
				// }
				$update = $this->trf->update_open_trf($record,$dyn_values,$test,$id,$care_instruction);
   
				if ($update['success']) {
					
					$msg = "TRF with ".$data['trf']->trf_ref_no." is updated";

					// Check sample registration for the TRF
					$query = $this->db->select('sample_reg_id, sample_registration_branch_id')
									  ->where('trf_registration_id',$id)
									  ->get('sample_registration');
					if($query->num_rows() > 0){
						$result = $query->row_array();
						$sample_reg_id = $result['sample_reg_id'];
						$sample_registration_branch_id = $result['sample_registration_branch_id'];

						// Update sample registration data
						$this->db->update('sample_registration',['sample_desc' => $trf_sample_desc, 'sample_customer_id' => $customer_id],['sample_reg_id' => $sample_reg_id]);

						// Update sample test
						$trf_type = $this->trf->get_data_by_id("trf_registration",$id,"trf_type");
						// Commented by Saurabh on 18-08-2021, not to change test data

						// if(!empty($test)){
						// 	$this->db->delete('sample_test',['sample_test_sample_reg_id' => $sample_reg_id]);
						// 	$gridCount = count($test);
						// 		$testIds = '';
						// 		if ($gridCount > 0) {
						// 			foreach ($test as $gridTest) {
						// 				$testIds .= $gridTest;
						// 				if ($gridCount > 1)
						// 					$testIds .= ',';
						// 			}
						// 		}
						// 		$getLabids_query = $this->db->select('group_concat(Distinct (lab_id)) as lab_id')
						// 						   ->from('mst_labs lb')
						// 						   ->join('tests ts','ts.test_lab_type_id = lb.mst_labs_lab_type_id','left')
						// 						   ->join('mst_lab_type mlt','mlt.lab_type_id=ts.test_lab_type_id')
						// 						   ->where('lb.mst_labs_branch_id',$sample_registration_branch_id)
						// 						   ->where_in('ts.test_id',$test)
						// 						   ->get();
						// 		if($getLabids_query->num_rows() > 0){
						// 			$getLab = $getLabids_query->row();
						// 			$getlab_reg1['no_labs_assigned'] = array_unique(explode(",", $getLab->lab_id));
						// 			$getlab_reg2['no_labs_assigned'] = implode(",", $getlab_reg1['no_labs_assigned']);
						// 			$this->db->update('sample_registration',$getlab_reg2,['sample_reg_id' => $sample_reg_id]);
						// 		} else {
						// 			$getLab = [];
						// 		}
				
						// 	// Get lab id for tests
						// 	foreach($test as $tests){
						// 		$labid_query = $this->db->select('group_concat(Distinct lab_id) as lab_id')
						// 								->from('mst_labs lb')
						// 								->join('tests ts','ts.test_lab_type_id = lb.mst_labs_lab_type_id','left')
						// 								->join('mst_lab_type mlt','mlt.lab_type_id=ts.test_lab_type_id')
						// 								->where('lb.mst_labs_branch_id',$sample_registration_branch_id)
						// 								->where('ts.test_id',$tests)
						// 								->get();
						// 		if($labid_query->num_rows() > 0){
						// 			$lab_id = $labid_query->row()->lab_id;
						// 		} else {
						// 			$lab_id = "";
						// 		}
				
						// 		if($trf_type == "TRF"){
						// 			// Columns to add query goes here
						// 		} else {
						// 			// Columns to add query goes here
						// 		}
						// 		$test_data['sample_test_lab_id'] = $lab_id;
						// 		$test_data['sample_test_test_id'] = $tests;
						// 		$test_data['sample_test_sample_reg_id'] = $sample_reg_id;
						// 		$test_data['sample_test_parameters'] = "";
						// 		/* Columns to add
						// 			Add rate per test
						// 			Discount
						// 			applicable charge
						// 		*/
						// 		$this->db->insert('sample_test',$test_data);
						// 	}
						// }
						// Commented by Saurabh on 18-08-2021, not to change test data

						// Update custom fields
						if(!empty($dynamic)){
							foreach ($dynamic as $key => $dynamic_values) {
								if (!empty($dynamic_values)) {
									
									$dynamic_value[0] = html_escape($dynamic_values[0]);
									$dynamic_value[1] = html_escape($dynamic_values[1]);
									$dyn_values[] = $dynamic_value;
								} 
							}
							if(!empty($dyn_values)){
								$custom['product_custom_fields'] = json_encode($dyn_values);
							} else {
								$custom['product_custom_fields'] = null;
							}
							$this->db->update('sample_registration',$custom,['sample_reg_id' => $sample_reg_id]);
						}
						
					}
   
					$user_log = array(
						'module'	=> 'jobs',
						'old_status' => '',
						// 'new_status' => 'New',
						'action_message' => 'Open TRF Updated',
						'trf_id'	=> $id,
						'source_module'	=> 'TestRequestForm_Controller',
						'operation'		=> 'edit_open_trf',
						'uidnr_admin'	=> $this->admin_id(),
						'log_activity_on' => date("Y-m-d H:i:s")
					);
   
					$save_log = $this->trf->save_user_log($user_log);
					echo json_encode(["message" => $msg, "status" => 1]);
					exit;
				} else {
					echo json_encode(["message" => "Something Went Wrong!.", "status" => 0]);
					exit;
				}
			} else {
				echo json_encode(["message" => "Something Went Wrong!.","error" => $this->form_validation->error_array(), "status" => 0]);
				exit;
				// if (!empty($dynamic)) {
				// 	foreach ($dynamic as $key => $value) {
				// 		$field 	= '<tr  data-row=' . $key . '>';
				// 		$field .= '<td>';
				// 		$field .= '<input type="text" class="form-control form-control-sm" value="' . $value[0] . '" name="dynamic_field[' . $key . '][0]">';
				// 		$field .= '</td>';
				// 		$field .= '<td>';
				// 		$field .= '<input type="text" name="dynamic_field[' . $key . '][1]" class="form-control form-control-sm" value="' . $value[1] . '">';
				// 		$field .= '</td>';
				// 		$field .= '<td><a href="javascript:void(0)" title="Remove Row" class="remove_row btn btn-danger">X</a></td>';
				// 		$field .= '</tr>';
				// 		$custom_field[] = "";
				// 	}
				// } else {
				// 	$custom_field = "";
				// }
				// $data['custom_field'] = $custom_field;
			}
		}
		// echo "<pre>";print_r($data); die;
		$this->load_view('trf/add_open_trf', $data);
	}

	public function send_sample_received($id){
        $result = $this->trf->send_sample_received($id);
		if ($result) {
			$msg = "Sample Recieved Successfully";

			$user_log = array(
				'module'	=> 'jobs',
				'old_status' => $result,
				'new_status' => 'Sample Received',
				'source_module'	=> 'TestRequestForm_Controller',
				'operation'	=> 'send_sample_received',
				'action_message' => 'Sample Received',
				'trf_id'	=> $id,
				'uidnr_admin'	=> $this->admin_id()
			);

			$save_log = $this->trf->save_user_log($user_log);
			$this->session->set_flashdata('success', $msg);
			return redirect('open-trf-list');
		} else {
			$this->session->set_flashdata('error', 'Error while saving data.');
			return redirect('open-trf-list');
		}
	}

	public function sample_received_view()
	{

		$data['products'] = $this->trf->get_products();
		$columns = "customer_id,customer_name";
		$table = "cust_customers";
		$data['customer'] = $this->trf->get_fields($table, $columns);
		//$this->load_view('trf/open_trf_list',$data);
		$this->load_view('trf/sample_received_list', $data);
	}

	public function sample_received_records($page = 0, $trf_ref_no, $cust_name, $product, $created_on, $created_by, $service_type, $column, $order)
	{
		$per_page = 5;
		if ($page != 0) {
			$page = ($page - 1) * $per_page;
		}

		$total_count = $this->trf->count_sample_received($trf_ref_no, $cust_name, $product, $created_on, $created_by, $service_type);
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
		$config['base_url'] = base_url() . "sample-received-records";
		$config['use_page_numbers'] = TRUE;
		$config['uri_segment'] = 2;
		$config['total_rows'] = $total_count;
		$config['per_page'] = $per_page;
		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();

		// echo $order; die;
        $data['result'] = $this->trf->sample_received_grid($per_page,$page,$trf_ref_no,$cust_name,$product,$created_on,$created_by,$service_type,$column,$order);
		$data['row'] = $page;
		if($order == "null"){
			$data['order'] = "desc";
		} else {
			$data['order'] = $order;
		}
		$end = (($data['result'])?count($data['result']):0) + (($page) ? $page : 0);
		$data['result_count'] = "Showing " . $page . " - " . $end . " of " . $total_count . " Results";
        echo json_encode($data);
	}

	public function get_care_instruction_image(){
		$care_instruction_id = $this->input->post('care_provided');
		$image = $this->trf->get_fields_by_id('application_care_instruction','instruction_image',$care_instruction_id,'instruction_id')[0]['instruction_image'];
		$url = $this->getS3Url1($image);
		echo json_encode($url);
	}
 /**--------------------------clone trf---------------- */
	public function clone_trf(){
		$trf_id = $this->input->post('trf_id');
		$data = $this->trf->get_clone_trf_data($trf_id);
		
		$app_care = $this->trf->get_app_care_data($trf_id);
		
		$test = $this->trf->get_trf_test($trf_id);
		
		$checkUser = $this->session->userdata('user_data');
		$this->user = $checkUser->uidnr_admin;
		
		$today = date("Ymd");
		$serial_no_query = $this->trf->get_trf_serial_no($data->trf_branch);
		// echo $this->db->last_query(); die;					  
		$serial_number = $serial_no_query->serial_no + 1;
		
		// save trf number config
		$config['branch_id'] = $data->trf_branch;
		$config['division_id'] = $data->division;
		$config['serial_no'] = $serial_number;
		$config['created_on'] = date('Y-m-d H:i:s');

		$save_config = $this->db->insert('trf_number_confiq',$config);

		$rand = str_pad($serial_number, 6, "0", STR_PAD_LEFT);
        $trf_num = 'TRF/' . $today . '/' . $rand;

		$trf_record = array(
			"trf_service_type" => $data->trf_service_type,
			"service_days" => $data->service_days,
			"trf_applicant" => $data->trf_applicant,
			"trf_contact" => $data->trf_contact,
			"trf_sample_ref_id" => $data->trf_sample_ref_id,
			"trf_invoice_to" => $data->trf_invoice_to,
			"trf_invoice_to_contact" => $data->trf_invoice_to_contact,
			"trf_product" => $data->trf_product,
			"work_id" => $data->work_id,
			"trf_buyer" => $data->trf_buyer,
			"trf_agent" => $data->trf_agent,
			"trf_sample_desc" => $data->trf_sample_desc,
			"trf_no_of_sample" => $data->trf_no_of_sample,
			"trf_country_destination" => $data->trf_country_destination,
			"trf_end_use" => $data->trf_end_use,
			"trf_quote_id" => $data->trf_quote_id,
			"trf_client_ref_no" => $data->trf_client_ref_no,
			"trf_ref_no" =>  $trf_num,
			"trf_status" => 'New',
			"tfr_signature" => $data->tfr_signature,
			"quote_customer_id" => $data->quote_customer_id,
			"trf_applicant" => $data->trf_applicant,
			"reported_to" => $data->reported_to,
			"sample_return_to" => $data->sample_return_to,
			"open_trf_currency_id" => $data->open_trf_currency_id,
			"open_trf_customer_id" => $data->open_trf_customer_id,
			"open_trf_customer_type" => $data->open_trf_customer_type,
			"open_trf_exchange_rate" => $data->open_trf_exchange_rate,
			"trf_regitration_type" => $data->trf_regitration_type,
			"care_instruction" => $data->care_instruction,
			"trf_thirdparty" => $data->trf_thirdparty,
			"trf_cc" => $data->trf_cc,
			"cc_type" => $data->cc_type,
			"trf_bcc" => $data->trf_bcc,
			"bcc_type" => $data->bcc_type,
			"trf_country_orgin" => $data->trf_country_orgin,
			"trf_branch" => $data->trf_branch,
			"tat_date" => $data->tat_date,
			"trf_customer_remarks" => $data->trf_customer_remarks,
			"trf_type" => $data->trf_type,
			"division" => $data->division,
			"crm_user_id" => $data->crm_user_id,
			"sample_pickup_services" => $data->sample_pickup_services,
			"temp_ref_id" => $data->temp_ref_id,
			"product_custom_fields" => $data->product_custom_fields,
			"updated_by" => $this->user,
			"create_on" => date('Y-m-d H:i:s'),
			"clone_trf_id" => $trf_id,
			'sales_person'	=> $data->sales_person,
			'trf_package_id' => $data->trf_package_id,
			'trf_protocol_id' => $data->trf_protocol_id
		);

		$trf_id = $this->trf->insert_data('trf_registration', $trf_record);
		if ($trf_id) {
			if ($app_care) {
				foreach ($app_care as $value) {
					$app_care_records = array(
						"trf_id" => $trf_id,
						"description" => $value->description,
						"image_sequence" => $value->image_sequence,
						"application_care_id" => $value->application_care_id,
						"created_by" => $this->user,
						"image" => $value->image,
					);
					$app_care_ids = $this->trf->insert_data('trf_apc_instruction', $app_care_records);
				}
			}
			if ($test) {
				foreach ($test as $value1) {
					$trf_test_records = array(
						"trf_test_trf_id" => $trf_id,
						"trf_test_test_id" => $value1->trf_test_test_id,
						"trf_test_status" => 'new',
						"trf_work_id" => $value1->trf_work_id,
						'trf_test_test_method_id' => $value1->trf_test_test_method_id,
						'trf_test_quote_type' => $value1->trf_test_quote_type,
						'trf_test_quote_id' => $value1->trf_test_quote_id,
						'trf_test_protocol_id' => $value1->trf_test_protocol_id,
						'trf_test_package_id' => $value1->trf_test_package_id,
						'rate_per_test'			=> $value1->rate_per_test
					);
					$trf_test_ids = $this->trf->insert_data('trf_test', $trf_test_records);
				}
			}
			$user_log = array(
				'module'	=> 'jobs',
				'source_module'	=> 'TestRequestForm_Controller',
				'operation'	=> 'clone_trf',
				'action_message' => 'TRF cloned',
				'trf_id'	=> $trf_id,
				'uidnr_admin'	=> $this->admin_id()
			);

			$save_log = $this->trf->save_user_log($user_log);

			$message = array("msg" => "TRF Generated Successfully ", "status" => 1);
			$this->session->set_flashdata('success', 'TRF Generated Successfully');
		} else {
			$message = array("msg" => "Error while saving! ", "status" => 0);
			$this->session->set_flashdata('success', 'Error while saving!');
		}
		echo json_encode($message);
	}
	/**------------------end ----------clone trf---------------- */

	public function test_name_selected()
	{
		$post = $this->input->get();
		echo json_encode($this->trf->open_trf_selected_test($post['id']));
	}

	// Get TRF log
	public function get_log()
	{
		$trf_id = $this->input->post('trf_id');
		$data = $this->trf->get_log($trf_id);
		echo json_encode($data);
	}

	public function get_sales_person()
	{
		$key = ($this->input->get('key'))?$this->input->get('key'):NULL;
		$data = $this->trf->get_sales_person($key);
		echo json_encode($data);
	}
// Added by Saurabh on 02-08-2021 to upload regulation image
	public function check_valid_file_upload($file_name)
    {
        if ($file_name['name'] != '' && $file_name['type'] == 'image/jpeg' || $file_name['type'] == 'image/jpg' || $file_name['type'] == 'image/png') {
            $image = $this->multiple_upload_image($file_name);
            if (!empty($image)) {
                $img['image'] = $image['aws_path'];
                $thumb_name = $this->generate_image_thumbnail($file_name['name'], $file_name['tmp_name'], THUMB_PATH);
                $thumb = $this->upload_thumb_aws(THUMB_PATH . $thumb_name, $thumb_name);
                $img['thumb'] = $thumb['aws_path'];
                $result = true;
            } else {
                $result = false;
            }
        } else {
            $result = false;
        }

        if ($result == false) {
            return false;
        } else {
            return $img;
        }
    }

	// Added by Saurabh on 13-10-2021 to get test protocol
	public function get_test_protocol(){
		$key = ($this->input->get('key'))?$this->input->get('key'):NULL;
		$data = $this->trf->get_test_protocol($key);
		echo json_encode($data);
	}

	// Added by Saurabh to get protocol based test and product
	public function get_protocol_product_test(){
		$protocol_id = $this->input->post('protocol_id');
		$data = $this->trf->get_protocol_product_test($protocol_id);
		echo json_encode($data);
	}

	// Added by Saurabh on 13-10-2021 to get test packages
	public function get_test_package(){
		$key = ($this->input->get('key'))?$this->input->get('key'):NULL;
		$buyer = ($this->input->get('buyer'))?$this->input->get('buyer'):NULL; // new 23
		$data = $this->trf->get_test_package($key,$buyer);
		echo json_encode($data);
	}

	// Added by Saurabh on 13-10-2021 to get tests and product of test package
	public function get_package_product_test(){
		$package_id = $this->input->post('package_id');
		$data = $this->trf->get_package_product_test($package_id);
		echo json_encode($data);
	}

	// Added by Saurabh on 16-05-2022 to get test method
	public function get_test_method()
	{
		$key = ($this->input->get('key'))?$this->input->get('key'):NULL;
		$test_id = $this->input->get('test_id');
		$data = $this->trf->get_test_method($test_id, $key);
		echo json_encode($data);
	}

	// Added by Saurabh on 18-05-2022 to delete test
	public function delete_trf_test(){
		$test_id = $this->input->post('trf_test_id');
		$trf_id  = $this->input->post('trf_id');

		$delete = $this->db->delete('trf_test',['trf_test_id' => $test_id]);
		if($delete){
			$user_log = array(
				'module'	=> 'jobs',
				'source_module'	=> 'TestRequestForm_Controller',
				'operation'	=> 'delete_trf_test',
				'action_message' => 'Test deleted',
				'trf_id'	=> $trf_id,
				'uidnr_admin'	=> $this->admin_id()
			);

			$save_log = $this->trf->save_user_log($user_log);
			echo json_encode(['status' => 1 ,'message' => 'Test removed successfully.']);
		} else {
			echo json_encode(['status' => 0 ,'message' => 'Something went wrong!.']);
		}
	}

	public function getBuyerCustomFields(){
	
		$buyer  = $this->input->post('buyer');
        
		$getCustomValue = $this->db->where('buyer_id',$buyer)->select('custom_field_name')->from('buyer_custom_fields')->join('buyer_fields','buyer_fields.buyer_field_id = buyer_custom_fields.buyer_field_id')->get();

		if($getCustomValue->num_rows() > 0){
			echo json_encode(['status' => 1 ,'message' => 'successfully.','data'=>$getCustomValue->result_array()]);
		}else{
			echo json_encode(['status' => 0 ,'message' => 'Something went wrong!.','data'=>0]);
		}
		
	}

}
