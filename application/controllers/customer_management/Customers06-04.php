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


	public function index($customer_id = NULL, $customer_type = NULL,$accope_customer = NULL, $search = NULL, $sortby = NULL, $order = NULL, $page_no = NULL)
	{
		// echo $accope_customer;die;
		$where = NULL;
		$base_url = 'customers';

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
		if ($accope_customer != '' && $accope_customer != 'NULL') {
			$base_url .= '/' . $accope_customer;
			$data['accope_customer'] = $accope_customer;
			$where['cust.accop_cust'] = $accope_customer;

		} else {	
			$base_url .= '/NULL';
			$data['accope_customer'] = NULL;
		}

		if ($customer_type != '' && $customer_type != 'NULL') {
			$base_url .= '/' . $customer_type;
			$data['customer_type'] = $customer_type;
			$where['cust.customer_type'] = $customer_type;
		} else {
			$base_url .= '/NULL';
			$data['customer_type'] = NULL;
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
		$config = $this->pagination($base_url, $total_row, 10, 8);
		$data["links"] = $config["links"];
		$data['customers_list'] = $this->customers_model->customers_list($config["per_page"], $config['page'], $search, $sortby, $order, $where);
		$start = (int)$page_no + 1;
		$end = (($data['customers_list']) ? count($data['customers_list']) : 0) + (($page_no) ? $page_no : 0);
		$data['result_count'] = "Showing " . $start . " - " . $end . " of " . $total_row . " Results";


		if ($order == NULL || $order == 'NULL') {
			$data['order'] = ($order) ? "DESC" : "ASC";
		} else {
			$data['order'] = ($order == "ASC") ? "DESC" : "ASC";
		}

		$this->load_view('customer_management/customers', $data);
	}


	public function add_customers()
	{
		$data = NULL;
		$data = $this->input->post();
		$list['branchs']=$this->customers_model->get_result('branch_id,branch_name','mst_branches', ['status > '=>0]);
		
		$this->form_validation->set_rules('customer_name','Customer name','required');
		$this->form_validation->set_rules('customer_type','Customer Type','required');
		$this->form_validation->set_rules('mst_branch_id','Branch','required');
		$this->form_validation->set_rules('email','Email','required|is_unique[cust_customers.email]');
		$this->form_validation->set_rules('address','Address','required');
		// $this->form_validation->set_rules('telephone','Telephone','required');
		$this->form_validation->set_rules('mobile','Mobile','required');
		$this->form_validation->set_rules('city','City','required');
		$this->form_validation->set_rules('po_box','Pin','required');
		$this->form_validation->set_rules('credit','Credit','required');
		$this->form_validation->set_rules('cust_customers_country_id','Country','required');
		// $this->form_validation->set_rules('cust_customers_province_id','State','required');
		$this->form_validation->set_rules('isactive','Status','required');
	// print_r($this->form_validation->error_array());die;
		
		if ($this->form_validation->run()) {
			
			$data = $this->filterData($data);	
			if ($data && count($data) > 0) {
				$result = $this->customers_model->insertCustomers($data);
				if ($result['success']) {
					$log_deatils = array(
						'text'          => "Added Customer with name ".$this->input->post('customer_name'),
						'created_by'    => $this->admin_id(),
						'created_on'    => date('Y-m-d H:i:s'),
						'source_module' => 'Customers',
						'record_id'		=> $result['inserted_id'],
						'action_taken'  => 'add_customers'
					);
	
					$this->customers_model->insert_data('user_log_history',$log_deatils);
					$this->session->set_flashdata('success', 'Customer added successfully');
					redirect('customers');
				} else {
					echo validation_errors();
					$this->session->set_flashdata('error', 'Error in adding Customer');
				}
			}
		} else {
			$this->load_view('customer_management/customers_form', $list);
		}
	}

	public function edit_customers($customer_id = NULL)
	{
		$data = NULL;
		$data['data'] = $this->customers_model->get_customers($customer_id);
		$data['branchs']=$this->customers_model->get_result('branch_id,branch_name','mst_branches', ['status > '=>0]);
		if ($data && count($data) > 0) {
			$this->load_view('customer_management/customers_form', $data);
		}
	}

	public function update_customers($customer_id = NULL)
	{
		$data = NULL;
		$data = $this->input->post();

		$this->form_validation->set_rules('customer_name','Customer name','required');
		$this->form_validation->set_rules('customer_type','Customer Type','required');
	
		$this->form_validation->set_rules('address','Address','required');
		// $this->form_validation->set_rules('telephone','Telephone','required');
		$this->form_validation->set_rules('mobile','Mobile','required');
		$this->form_validation->set_rules('city','City','required');
		$this->form_validation->set_rules('po_box','Pin','required');
		
		$this->form_validation->set_rules('cust_customers_country_id','Country','required');
		// $this->form_validation->set_rules('cust_customers_province_id','State','required');
		$this->form_validation->set_rules('isactive','Status','required');
		$this->form_validation->set_rules('credit','Credit','required');
		
		if ($this->form_validation->run()) {
			$data = $this->filterData($data);
			if ($data && count($data) > 0) {
				$result = $this->customers_model->updateCustomers($data, $customer_id);
				if ($result) {
					$log_deatils = array(
						'text'          => "Updated Customer with name ".$this->input->post('customer_name'),
						'created_by'    => $this->admin_id(),
						'created_on'    => date('Y-m-d H:i:s'),
						'record_id'		=> $customer_id,
						'source_module' => 'Customers',
						'action_taken'  => 'update_customers'
					);
	
					$this->customers_model->insert_data('user_log_history',$log_deatils);
					$this->session->set_flashdata('success', 'Customer Updated successfully');
					redirect('customers');
				} else {
					$this->session->set_flashdata('error', 'Customer in updating factory');
				}
			}
		} else {
			$this->session->set_flashdata('error', 'Fill all required fields');
			$setData = $this->update_data_set($data,$customer_id);
			$this->load_view('customer_management/customers_form',$setData);
			
		}
	}

public function update_data_set($data,$customer_id){
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
		$this->form_validation->set_rules('contact_name','Contact Name','required');
		$this->form_validation->set_rules('email','Contact Email','required|valid_email');
		$this->form_validation->set_rules('type','Type','required');
		$customer_type = $this->customers_model->get_row('customer_type','cust_customers','customer_id='.$customer_id);
		if($customer_type && count($customer_type)){
			$data['customer_type'] = $customer_type->customer_type;
		}
		if ($this->form_validation->run()) {
			if ($data && count($data) > 0) {
				$duplicate = $this->customers_model->check_duplicate($data);
				if ($duplicate) {
					
					$result = $this->customers_model->insert_data('contacts', $data);
					
					if ($result) {
						$log_deatils = array(
							'text'          => "Added contact with name ".$this->input->post('contact_name'),
							'created_by'    => $this->admin_id(),
							'created_on'    => date('Y-m-d H:i:s'),
							'record_id'		=> $result,
							'source_module' => 'Customers',
							'action_taken'  => 'add_contact'
						);
		
						$this->customers_model->insert_data('user_log_history',$log_deatils);
						$msg = array(
							'status' => 1,
							'msg' => 'Contact added successfully'
						);
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
		if ($contact_id) {
			$data = $this->customers_model->deleteContact($contact_id);
			if ($data) {
				$log_deatils = array(
					'text'          => "Contact deleted",
					'created_by'    => $this->admin_id(),
					'created_on'    => date('Y-m-d H:i:s'),
					'record_id'		=> $contact_id,
					'source_module' => 'Customers',
					'action_taken'  => 'delete_contact'
				);

				$this->customers_model->insert_data('user_log_history',$log_deatils);
				$msg = array(
					'status' => 1,
					'msg' => 'Contact deleted successfully'
				);
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
		if ($communication_id) {
			$data = $this->customers_model->deleteCommunications($communication_id);
			if ($data) {
				$log_deatils = array(
					'text'          => "Deleted communication",
					'created_by'    => $this->admin_id(),
					'created_on'    => date('Y-m-d H:i:s'),
					'record_id'		=> $communication_id,
					'source_module' => 'Customers',
					'action_taken'  => 'delete_communications'
				);

				$this->customers_model->insert_data('user_log_history',$log_deatils);
				$msg = array(
					'status' => 1,
					'msg' => 'Communication deleted successfully'
				);
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
		if ($opportunity_id) {
			$data = $this->customers_model->deleteOpportunity($opportunity_id);
			if ($data) {
				$log_deatils = array(
					'text'          => "Deleted opportunity",
					'created_by'    => $this->admin_id(),
					'created_on'    => date('Y-m-d H:i:s'),
					'record_id'		=> $opportunity_id,
					'source_module' => 'Customers',
					'action_taken'  => 'delete_opportunity'
				);

				$this->customers_model->insert_data('user_log_history',$log_deatils);
				$msg = array(
					'status' => 1,
					'msg' => 'Opportunity deleted successfully'
				);
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
		$this->form_validation->set_rules('comm_communications_contact_id','Contact name','required');
		$this->form_validation->set_rules('subject','Subject','required');
		$this->form_validation->set_rules('date_of_communication','Date of communication','required');
		$this->form_validation->set_rules('follow_up_date','Follow up date','required');
		$this->form_validation->set_rules('communication_mode','Communication mode','required');
		$this->form_validation->set_rules('medium','Medium','required');
		$this->form_validation->set_rules('connected_to','Connected to','required');
		
		$customer_type = $this->customers_model->get_row('customer_type','cust_customers','customer_id='.$customer_id);
		if($customer_type && count($customer_type)){
			$data['customer_type'] = $customer_type->customer_type;
		}

		if ($this->form_validation->run()) {
			if ($data && count($data) > 0) {
				$data = $this->filterData($data);
				unset($data['non_taxable']);
				$mail_data = $data;
				$data = $this->customers_model->insert_data('comm_communications', $data);
				if ($data) {
					$log_deatils = array(
						'text'          => "Added Communication with subject ".$this->input->post('subject'),
						'created_by'    => $this->admin_id(),
						'created_on'    => date('Y-m-d H:i:s'),
						'record_id'		=> $data,
						'source_module' => 'Customers',
						'action_taken'  => 'add_communication'
					);
	
					$this->customers_model->insert_data('user_log_history',$log_deatils);
					$msg = array(
						'status' => 1,
						'msg' => "Communication added successfully"
					);
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
		$customer_type = $this->customers_model->get_row('customer_type','cust_customers','customer_id='.$customer_id);
		if($customer_type && count($customer_type)){
			$data['opportunity_customer_type'] = $customer_type->customer_type;
		}
		$this->form_validation->set_rules('opportunity_name','Opportunity Name','required');
		$this->form_validation->set_rules('types','Types','required');
		$this->form_validation->set_rules('opportunity_value','Opportunity Value','required');
		$this->form_validation->set_rules('estimated_closure_date','Estimated closure date','required');
		$this->form_validation->set_rules('opportunity_contact_id','Contact Name','required');
		$this->form_validation->set_rules('op_assigned_to','Assign to','required');
		$this->form_validation->set_rules('description','Description','required|trim|min_length[200]');
		
		if ($this->form_validation->run()) {
			if ($data && count($data) > 0) {
				$data = $this->filterData($data);
				unset($data['non_taxable']);
				$data = $this->customers_model->insert_data('opportunity', $data);
				if ($data) {
					$log_deatils = array(
						'text'          => "Added opportunity with name ".$this->input->post('opportunity_name'),
						'created_by'    => $this->admin_id(),
						'created_on'    => date('Y-m-d H:i:s'),
						'record_id'		=> $data,
						'source_module' => 'Customers',
						'action_taken'  => 'add_opportunity'
					);
	
					$this->customers_model->insert_data('user_log_history',$log_deatils);
					$msg = array(
						'status' => 1,
						'msg' => "Opportunity added successfully"
					);
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

		if(($map_type=="Agent" && $type=="Buyer")||($map_type=="Agent" && $type=="Factory")||($map_type=="Factory" && $type=="Buyer")||($map_type=="Thirdparty" && $type=="Buyer")||($map_type=="Thirdparty" && $type=="Factory")||($map_type=="Thirdparty" && $type=="Agent")){

			$table = strtolower($type)."_".strtolower($map_type);
		}
		else{
		
			$table = strtolower($map_type)."_".strtolower($type);
		}

		$where[strtolower($map_type)."_id"] = $customer_id;
		$id = strtolower($type)."_id";
		
	
		$not_in = $this->customers_model->get_result($id, $table, $where);
		if ($not_in && count($not_in) > 0) {
			foreach ($not_in as $key => $value) {
				array_push($data, $value->$id);
			}
		}

	
		$list = $this->customers_model->get_map_listing(NULL, $type, $data,$search);
		echo json_encode($list);
	}


	public function map_customers(){
		$customer_id = $map_id = $type =$map_type = $data = NULL;
		$customer_id = $this->input->post('customer_id');
		$map_id = $this->input->post('map_id');
		$type = $this->input->post('type');
		$map_type = $this->input->post('map_type');

		
		if(($map_type=="Agent" && $type=="Buyer")||($map_type=="Agent" && $type=="Factory")||($map_type=="Factory" && $type=="Buyer")||($map_type=="Thirdparty" && $type=="Buyer")||($map_type=="Thirdparty" && $type=="Factory")||($map_type=="Thirdparty" && $type=="Agent")){

			$table = strtolower($type)."_".strtolower($map_type);
		}
		else{
		
			$table = strtolower($map_type)."_".strtolower($type);
		}

		$data[strtolower($type)."_id"]=$map_id;
		$data[strtolower($map_type)."_id"] = $customer_id;
		$result = $this->customers_model->insert_map_details($table,$data);
		
		if($result){
			$log_deatils = array(
				'text'          => "Contact mapping",
				'created_by'    => $this->admin_id(),
				'created_on'    => date('Y-m-d H:i:s'),
				'source_module' => 'Customers',
				'action_taken'  => 'map_customers'
			);

			$this->customers_model->insert_data('user_log_history',$log_deatils);
			$msg = array(
				'status'=>1,
				'msg'=>$type." mapped to ".$map_type." successfully"
			);
		}
		else{
			$msg = array(
				'status'=>0,
				'msg'=>"Error in ".$type." mapped to ".$map_type
			);
		}

		echo json_encode($msg);
	}

	public function remove_mapped_customers(){
		$customer_id = $un_map_id = $type =$map_type = $where1 = $where2 = NULL;
		$customer_id = $this->input->post('customer_id');
		$un_map_id = $this->input->post('un_map_id');
		$type = $this->input->post('type');
		$map_type = $this->input->post('map_type');

		if(($map_type=="Agent" && $type=="Buyer")||($map_type=="Agent" && $type=="Factory")||($map_type=="Factory" && $type=="Buyer")||($map_type=="Thirdparty" && $type=="Buyer")||($map_type=="Thirdparty" && $type=="Factory")||($map_type=="Thirdparty" && $type=="Agent")){

			$table = strtolower($type)."_".strtolower($map_type);
		}
		else{
		
			$table = strtolower($map_type)."_".strtolower($type);
		}
	
		
		$where1[$table.".".strtolower($map_type)."_id"] = $customer_id;
		$where2[$table.".".strtolower($type)."_id"] = $un_map_id;
		$result = $this->customers_model->delete_map_details($table,$where1,$where2);
		
		if($result){
			$log_deatils = array(
				'text'          => "Mapping deleted",
				'created_by'    => $this->admin_id(),
				'created_on'    => date('Y-m-d H:i:s'),
				'source_module' => 'Customers',
				'action_taken'  => 'remove_mapped_customers'
			);

			$this->customers_model->insert_data('user_log_history',$log_deatils);
			$msg = array(
				'status'=>1,
				'msg'=>$type." Removed to ".$map_type." successfully"
			);
		}
		else{
			$msg = array(
				'status'=>0,
				'msg'=>"Error in ".$type." Remove to ".$map_type
			);
		}

		echo json_encode($msg);
	}

	public function mapped_listing(){
		
		$customer_id = $type = $list = $map_type = $where1 = $where2=  $join_table = $condition = $search =   NULL;
		$customer_id = $this->input->post('customer_id');
		$type = $this->input->post('type');
		$map_type = $this->input->post('map_type');
		$search = $this->input->post('search');

		if(($map_type=="Agent" && $type=="Buyer")||($map_type=="Agent" && $type=="Factory")||($map_type=="Factory" && $type=="Buyer")||($map_type=="Thirdparty" && $type=="Buyer")||($map_type=="Thirdparty" && $type=="Factory")||($map_type=="Thirdparty" && $type=="Agent")){

			$join_table = strtolower($type)."_".strtolower($map_type)." as join";
		   
		}
		else{
			$join_table = strtolower($map_type)."_".strtolower($type)." as join";
			
		}
		
		$condition = "join.".strtolower($type)."_id=cs.customer_id";
		$where1['cs.customer_type'] = $type;
		$where2["join.".strtolower($map_type)."_id"]=$customer_id;
		
		$list = $this->customers_model->get_mapped_listing($join_table,$condition,$where1,$where2,$search);
		echo json_encode($list);
	}

	public function load_assign_to(){
		$designation_id2 = SALES_EXECUTIVE;
		$designation_id1 = SALES_MANAGER;
		$result = $this->customers_model->op_assign_to($designation_id2,$designation_id1);
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
					$this->customers_model->update_data('cust_customers', ['accop_cust' => 0], ['customer_id' => $id]);
				} else {
					$this->customers_model->update_data('cust_customers', ['accop_cust' => 1], ['customer_id' => $id]);
				}
				
				$this->session->set_flashdata('success', 'MARK ACCOPS CUSTOMER.');
				redirect($_SERVER['HTTP_REFERER']);
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


	public function view_cust_details(){
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

}
