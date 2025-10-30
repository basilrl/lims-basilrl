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


	public function index($customer_id = NULL, $customer_type = NULL, $search = NULL, $sortby = NULL, $order = NULL, $page_no = NULL)
	{
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
		$config = $this->pagination($base_url, $total_row, 10, 7);
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
		$valid = $this->form_validation->run('add_customers');
		if ($valid) {
			$data = $this->filterData($data);
			if ($data && count($data) > 0) {
				$result = $this->customers_model->insertCustomers($data);
				if ($result) {
					$this->session->set_flashdata('success', 'Customer added successfully');
					redirect('customers');
				} else {
					$this->session->set_flashdata('error', 'Error in adding Customer');
				}
			}
		} else {
			$this->load_view('customer_management/customers_form', NULL);
		}
	}

	public function edit_customers($customer_id = NULL)
	{
		$data = NULL;
		$data['data'] = $this->customers_model->get_customers($customer_id);
		if ($data && count($data) > 0) {
			$this->load_view('customer_management/customers_form', $data);
		}
	}

	public function update_customers($customer_id = NULL)
	{
		$data = NULL;
		$data = $this->input->post();
		$valid = $this->form_validation->run('add_customers');
		if ($valid) {
			$data = $this->filterData($data);
			if ($data && count($data) > 0) {
				$result = $this->customers_model->updateCustomers($data, $customer_id);
				if ($result) {
					$this->session->set_flashdata('success', 'Customer Updated successfully');
					redirect('customers');
				} else {
					$this->session->set_flashdata('error', 'Customer in updating factory');
				}
			}
		} else {
			$this->load_view('customer_management/Customers_form', NULL);
		}
	}




	public function filterData($data)
	{
		if ($data && count($data) > 0) {

			$data['created_by'] = $this->user;
			if (empty($data['non_taxable'])) {
				$data['non_taxable'] = 0;
			}
			$data['created_on'] = date("Y-m-d");
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
		$data = $this->filterData($data);
		unset($data['non_taxable']);
		$valid = $this->form_validation->run('add_contact');
		if ($valid) {
			if ($data && count($data) > 0) {
				$duplicate = $this->customers_model->check_duplicate($data);
				if ($duplicate) {

					$result = $this->customers_model->insert_data('contacts', $data);
					if ($result) {
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
		$valid = $this->form_validation->run('add_communication');

		if ($valid) {
			if ($data && count($data) > 0) {
				$data = $this->filterData($data);
				unset($data['non_taxable']);
				$data = $this->customers_model->insert_data('comm_communications', $data);
				if ($data) {
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
	
		$valid = $this->form_validation->run('add_opportunity');
		if ($valid) {
			if ($data && count($data) > 0) {
				$data = $this->filterData($data);
				unset($data['non_taxable']);
				$data = $this->customers_model->insert_data('opportunity', $data);
				if ($data) {
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
		$customer_id = $type = $list = $map_type = $where =  NULL;
		$customer_id = $this->input->post('customer_id');
		$type = $this->input->post('type');
		$map_type = $this->input->post('map_type');


		if ($map_type == 'Factory'  && $type == 'Agent') {

			$where['factory_id'] = $customer_id;
			$not_in = $this->customers_model->get_result('agent_id', 'factory_agent', $where);

			if ($not_in && count($not_in) > 0) {
				foreach ($not_in as $key => $value) {
					array_push($data, $value->agent_id);
				}
			}
		} elseif ($map_type == 'Factory'  && $type == 'Thirdparty') {
			$where['factory_id'] = $customer_id;
			$not_in = $this->customers_model->get_result('thirdparty_id', 'factory_thirdparty', $where);
			if ($not_in && count($not_in) > 0) {
				foreach ($not_in as $key => $value) {
					array_push($data, $value->thirdparty_id);
				}
			}
		} elseif ($map_type == 'Buyer'  && $type == 'Factory') {
			$where['buyer_id'] = $customer_id;
			$not_in = $this->customers_model->get_result('factory_id', 'buyer_factory', $where);

			if ($not_in && count($not_in) > 0) {
				foreach ($not_in as $key => $value) {
					array_push($data, $value->factory_id);
				}
			}
		} elseif ($map_type == 'Buyer'  && $type == 'Agent') {
			$where['buyer_id'] = $customer_id;
			$not_in = $this->customers_model->get_result('agent_id', 'buyer_agent', $where);

			if ($not_in && count($not_in) > 0) {
				foreach ($not_in as $key => $value) {
					array_push($data, $value->agent_id);
				}
			}
			
		} elseif ($map_type == 'Buyer'  && $type == 'Thirdparty') {
			$where['buyer_id'] = $customer_id;
			$not_in = $this->customers_model->get_result('thirdparty_id', 'buyer_thirdparty', $where);
			if ($not_in && count($not_in) > 0) {
				foreach ($not_in as $key => $value) {
					array_push($data, $value->thirdparty_id);
				}
			}
		} elseif ($map_type == 'Agent'  && $type == 'Thirdparty') {
			$where['agent_id'] = $customer_id;
			$not_in = $this->customers_model->get_result('thirdparty_id', 'agent_thirdparty', $where);

			if ($not_in && count($not_in) > 0) {
				foreach ($not_in as $key => $value) {
					array_push($data, $value->thirdparty_id);
				}
			}
		}
		else{
			$data = NULL;
			$type = NULL;
		} 
		
		$list = $this->customers_model->get_map_listing(NULL, $type, $data);
		echo json_encode($list);
	}


	public function map_customers(){
		$customer_id = $map_id = $type =$map_type = $data = NULL;
		$customer_id = $this->input->post('customer_id');
		$map_id = $this->input->post('map_id');
		$type = $this->input->post('type');
		$map_type = $this->input->post('map_type');
		$data[strtolower($type)."_id"]=$map_id;
		$data[strtolower($map_type)."_id"] = $customer_id;
		$table = strtolower($map_type)."_".strtolower($type);
		$result = $this->customers_model->insert_map_details($table,$data);
		
		if($result){
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
	
		$table = strtolower($map_type)."_".strtolower($type);
		$where1[$table.".".strtolower($map_type)."_id"] = $customer_id;
		$where2[$table.".".strtolower($type)."_id"] = $un_map_id;
		$result = $this->customers_model->delete_map_details($table,$where1,$where2);
		
		if($result){
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
		
		$customer_id = $type = $list = $map_type = $where1 = $where2=  $join_table = $condition =   NULL;
		$customer_id = $this->input->post('customer_id');
		$type = $this->input->post('type');
		$map_type = $this->input->post('map_type');

		$join_table = strtolower($map_type)."_".strtolower($type)." as join";
		$condition = "join.".strtolower($type)."_id=cs.customer_id";
		$where1['cs.customer_type'] = $type;
		$where2["join.".strtolower($map_type)."_id"]=$customer_id;
		$list = $this->customers_model->get_mapped_listing($join_table,$condition,$where1,$where2);
		echo json_encode($list);
	}

	public function load_assign_to(){
		$designation_id2 = SALES_EXECUTIVE;
		$designation_id1 = SALES_MANAGER;
		$result = $this->customers_model->op_assign_to($designation_id2,$designation_id1);
		echo json_encode($result);
	}


}
