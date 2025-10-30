<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Website_users extends MY_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Website_users_model', 'web_model');
		$this->check_session();
		$checkUser = $this->session->userdata('user_data');
		$this->user = $checkUser->uidnr_admin;
		$this->form_validation->set_error_delimiters('<div class="error" style="color:red">', '</div>');
	}

    public function index(){
        $where = NULL;
		$search = NULL;
		$sortby = NULL;
		$order = NULL;
        $base_url = 'Website_users/index';

		if ($this->uri->segment('3') != NULL && $this->uri->segment('3') != 'NULL') {
            $client_type = $this->uri->segment('3');
          
			$data['client_type'] =  base64_decode($client_type);
			$base_url .= '/' . $client_type;
			$where['users.customer_type'] = base64_decode($client_type);
		} else {
            $base_url .= '/NULL';
			$data['client_type'] = NULL;
		}

		if ($this->uri->segment('4') != NULL && $this->uri->segment('4') != 'NULL') {
            $customer_id = $this->uri->segment('4');
          
			$data['customer_id'] =  base64_decode($customer_id);
            $customer_name = $this->web_model->get_row("customer_name",'cust_customers',['customer_id'=>$data['customer_id']]);

            if($customer_name && count($customer_name)>0){
                $data['customer_name'] = $customer_name->customer_name;
            }
			$base_url .= '/' . $customer_id;
			$where['cust.customer_id'] = base64_decode($customer_id);
		} else {
            $base_url .= '/NULL';
			$data['customer_id'] = NULL;
            $data['customer_name'] = NULL;
		}

		if ($this->uri->segment('5') != NULL && $this->uri->segment('5') != 'NULL') {
            $contact_id = $this->uri->segment('5');
          
			$data['contact_id'] =  base64_decode($contact_id);
            $contact_name = $this->web_model->get_row("contact_name",'contacts',['contact_id'=>$data['contact_id']]);

            if($contact_name && count($contact_name)>0){
                $data['contact_name'] = $contact_name->contact_name;
            }
			$base_url .= '/' . $contact_id;
			$where['contact.contact_id'] = base64_decode($contact_id);
		} else {
            $base_url .= '/NULL';
			$data['contact_id'] = NULL;
            $data['contact_name'] = NULL;
		}

		if ($this->uri->segment('6') != NULL && $this->uri->segment('6') != 'NULL') {
            $status = $this->uri->segment('6');
			$data['status'] =  base64_decode($status);
			$base_url .= '/' . $status;
			$where['users.customer_login_status'] = base64_decode($status);
    
		} else {
            $base_url .= '/NULL';
			$data['status'] = NULL;
		}

		if ($this->uri->segment('7') != NULL && $this->uri->segment('7') != 'NULL') {
            $search = $this->uri->segment('7');
			$data['search'] =  base64_decode($search);
			$base_url .= '/' . $search;
			$search = base64_decode($search);
    
		} else {
            $base_url .= '/NULL';
			$data['search'] = NULL;
		}

        if ($this->uri->segment('8') != NULL && $this->uri->segment('8') != 'NULL') {
            $sortby = $this->uri->segment('8');
			$base_url .= '/' . $sortby;
            $sortby = base64_decode($sortby);
		} else {
			$base_url .= '/NULL';
			$sortby = NULL;
		}
		if ($this->uri->segment('9') != NULL && $this->uri->segment('9') != 'NULL') {
            $order = $this->uri->segment('9');
			$base_url .= '/' . $order;
		} else {
			$base_url .= '/NULL';
			$order = NULL;
		}

        $total_row = $this->web_model->get_website_users(NULL, NULL, $search, NULL, NULL, $where,true);
		$config = $this->pagination($base_url, $total_row, 10, 10);

        $data['users_list'] = $this->web_model->get_website_users($config["per_page"], $config['page'], $search, $sortby, $order, $where);
		$data["links"] = $config["links"];
       
        $page_no = $this->uri->segment('10');
		$start = (int)$page_no + 1;
		$end = (($data['users_list']) ? count($data['users_list']) : 0) + (($page_no) ? $page_no : 0);
		$data['result_count'] = "Showing " . $start . " - " . $end . " of " . $total_row . " Results";


		if ($order == NULL || $order == 'NULL') {
			$data['order'] = ($order) ? "DESC" : "ASC";
		} else {
			$data['order'] = ($order == "ASC") ? "DESC" : "ASC";
		}

		 $this->load_view('website_users/website_users',$data);
        
    }


	public function get_auto_list_website(){
        // pre_r($this->input->post());die;
        $search = $this->input->post('key');
        $where = (array)(json_decode(stripslashes($this->input->post('where'))));
        $like = $this->input->post('like');
		$select = $this->input->post('select');
        $table = $this->input->post('table');
        $result = $this->web_model->get_AutoList_website($select,$table,$search,$like,$where);

        echo json_encode($result);
    }


	public function update_status($id){
		if(!empty($id)){
			$status = $this->web_model->get_row('customer_login_status','customer_login',['customer_login_id'=>$id]);
			if($status && count($status)>0){
				$status = $status->customer_login_status;
				if($status=="Active"){
					$data['customer_login_status'] = 'Inactive';
				}
				else{
					$data['customer_login_status'] = 'Active';
				}

				$result = $this->web_model->update_data('customer_login',$data,['customer_login_id'=>$id]);
				if($result){

					$log = $this->user_log_update($id,'WEBSITE USER STATUS UPDATED','update_status');
					if($log){
						$this->session->set_flashdata('success','Status Updated Successfully');
						redirect($_SERVER['HTTP_REFERER']);
					}
					else{
						$this->session->set_flashdata('error','Error in generating log!');
						redirect($_SERVER['HTTP_REFERER']);
					}
					
				}
				else{
					$this->session->set_flashdata('error','Error in Status Updating!');
					redirect($_SERVER['HTTP_REFERER']);
				}
			}
		}
	}


	public function get_dropdown_by_ajax_website(){
        $select = $this->input->post('select');
        $from = $this->input->post('from');
        $where = (array)(json_decode(stripslashes($this->input->post('where'))));
        $data = $this->web_model->get_result($select,$from,$where);

        echo json_encode($data);
    }

	public function get_email(){
		$id = $this->input->post('where_con');
		$email = $this->web_model->get_row('email','contacts',['contact_id'=>$id]);
		echo json_encode($email);
	}


	public function add_users(){
		$this->form_validation->set_rules('customer_type','Customer Type','required');
		$this->form_validation->set_rules('contacts_customer_id','Customer','required');
		$this->form_validation->set_rules('cl_contact_id','Contact','required');
		$this->form_validation->set_rules('customer_login_username','User Name','required|is_unique[customer_login.customer_login_username]');
		$this->form_validation->set_rules('customer_login_password','Password','required');
		$this->form_validation->set_rules('confirm_password','Password','required');
		$this->form_validation->set_rules('customer_login_status','Status','required');

		if($this->form_validation->run()){
			$data = $this->input->post();
			unset($data['contacts_customer_id']);
			unset($data['confirm_password']);
			unset($data['customer_login_id']);
			$data['customer_login_password'] = md5($data['customer_login_password']);
			$result = $this->web_model->insert_data('customer_login',$data);
			if($result){
				$log = $this->user_log_update($result,'WEBSITE USER ADDED','add_users');
				if($log){
					$msg = array(
						'status'=>1,
						'msg'=>'User Added Successfully.'
					);
				}
				else{
					$msg = array(
						'status'=>0,
						'msg'=>'error in generating log'
					);
				}
				
				$this->session->set_flashdata('success','User Added Successfully.');
			}
			else{
				$msg = array(
					'status'=>0,
					'msg'=>'Error in Adding User!'
				);
			}
		}
		else{
			$msg = array(
				'status'=>0,
				'msg'=>'Fill all required fields!',
				'errors'=>$this->form_validation->error_array()
			);
		}
		
		
		echo json_encode($msg);
	}

	public function update_users(){
		$this->form_validation->set_rules('customer_type','Customer Type','required');
		$this->form_validation->set_rules('contacts_customer_id','Customer','required');
		$this->form_validation->set_rules('cl_contact_id','Contact','required');
		$this->form_validation->set_rules('customer_login_username','User Name','required');
		$this->form_validation->set_rules('customer_login_status','Status','required');

		if($this->form_validation->run()){
			$data = $this->input->post();
			$id = $data['customer_login_id'];
			unset($data['contacts_customer_id']);
			unset($data['confirm_password']);
			unset($data['customer_login_id']);
			if(!empty($data['customer_login_password'])){
				$data['customer_login_password'] = md5($data['customer_login_password']);	
			}
			else{
				unset($data['customer_login_password']);
			}
			
			$result = $this->web_model->update_data('customer_login',$data,['customer_login_id'=>$id]);
			if($result){

				$log = $this->user_log_update($id,'WEBSITE USER UPDATED','update_users');
				if($log){
					$msg = array(
						'status'=>1,
						'msg'=>'User Update Successfully.'
					);
				}
				else{
					$msg = array(
						'status'=>0,
						'msg'=>'error in generating log'
					);
				}
				
				$this->session->set_flashdata('success','User Updated Successfully.');
			}
			else{
				$msg = array(
					'status'=>0,
					'msg'=>'Error in Updating User!'
				);
			}
		}
		else{
			$msg = array(
				'status'=>0,
				'msg'=>'Fill all required fields!',
				'errors'=>$this->form_validation->error_array()
			);
		}
		
		
		echo json_encode($msg);
	}


	public function edit_user(){
		$id = $this->input->post('id');
		$data = $this->web_model->get_website_user_data($id);
		echo json_encode($data);
	}


	public function delete_user(){
		$id = $this->input->post('id');
		$result = $this->web_model->delete_user($id);
		if($result){

			$log = $this->user_log_update($id,'WEBSITE USER DELETED','delete_user');
				if($log){
					$msg = array(
						'status'=>1,
						'msg'=>'User Deleted Succefully'
					);
				}
				else{
					$msg = array(
						'status'=>0,
						'msg'=>'error in generating log'
					);
				}
			
			$this->session->set_flashdata('success','User Deleted Succefully');
		}
		else{
			$msg = array(
				'status'=>0,
				'msg'=>'Error in Deleting user'
			);
		}
		echo json_encode($msg);
	}

	public function get_log_data()
	{
		$id = $this->input->post('id');
		$data = $this->web_model->get_log_data($id);
		echo json_encode($data);
	}


	public function user_log_update($id,$text,$action){
		$data = array();
		$data['source_module'] = 'Website_users';
		$data['record_id'] = $id;
		$data['created_on'] = date("Y-m-d h:i:s");
		$data['created_by'] = $this->user;
		$data['action_taken'] = $action;
		$data['text'] = $text;

		$result = $this->web_model->insert_data('user_log_history',$data);
		if($result){
			return true;
		}
		else{
			return false;
		}
	}
	public function fetch_list()
	{
		$controller = $this->fetch_controller();
		if ($controller) {
			$html = '<div class="col-4" ><div class="list-group" id="list-tab" role="tablist" style=" max-height:40vh; overflow-y:auto;">';
			$functions_html = '<div class="col-8"><div class="tab-content" id="nav-tabContent">';
			foreach ($controller as $key => $value) {
				$functions = $this->fetch_functions(['controller_name' => $value->controller_name]);
				$functions_html .= '<div class="tab-pane fade" id="list-' . $value->controller_name . '" role="tabpanel" aria-labelledby="list-home-list"><div class="row">';
				$html .= '<a class="list-group-item list-group-item-action" id="list-' . $value->controller_name . '-list" data-toggle="list" href="#list-' . $value->controller_name . '" role="tab" aria-selected="true">' . $value->controller_name . "</a>";
				foreach ($functions as $key1 => $value1) {
					$functions_html .=  '<div class="col-sm-4"><div class="form-check form-check-inline"><input class="form-check-input" id="form-check-input-' . $value1->function_id . '" type="checkbox" name="function_id[]" value="' . $value1->function_id . '"><label class="form-check-label" for="form-check-input-' . $value1->function_id . '">' . $value1->alias . "</label></div></div>";
				}
				$functions_html .= '</div></div>';
			}
			$html .= ' </div></div>';
			$functions_html .= '</div></div>';
			$controller = $html . $functions_html;
		} else {
			$controller = false;
		}
		echo json_encode($controller);
	}
	public function fetch_functions($controller)
	{
		return $this->web_model->fetch_functions($controller);
	}
	public function fetch_controller()
	{
		return $this->web_model->fetch_controller();
	}
	public function fetch_permission()
	{
		$post = $this->input->post();
		$post['contact_id'] = ($post['contact_id']);
		$functions = $this->web_model->fetch_permission($post);
		if ($functions)
			echo json_encode($functions);
		else
			echo false;
	}
	public function save_permission()
	{
		$this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');
        $this->form_validation->set_rules('contact_id', 'UNIQUE ID', 'trim|required');
        if ($this->form_validation->run() == TRUE) {
            $contact_id = ($this->input->post('contact_id'));
            $functionID = $this->input->post('function_id');
			if ($functionID) {
				if (count($functionID) > 1) {
					$functionID = implode(',', $functionID);
				} else {
					$functionID = current($functionID);
				}
			} else {
				$functionID='';
			}
			
            
            $functions = $this->web_model->save_permission($contact_id, $functionID);
            if ($functions) {
				$log = $this->user_log_update($contact_id, 'Updated permission', 'save_permission');
                // $this->log_history(['action' => 'Updated permission for Role id :-'.$contact_id,'id' => $contact_id, 'source_module' => 'Role', 'action_taken' => 'Edited Role']);
                $msg = array(
                    'status' => 1,
                    'msg' => 'PERMISSION SET SUCCESSFULLY'
                );
            } else {
                $msg = array(
                    'status' => 0,
                    'msg' => 'PERMISSION NOT SET'
                );
            }
        } else {
            $msg = array(
                'status' => 0,
                'errors' => validation_errors(),
                'msg' => 'PLEASE ENTER VALID TEXT'
            );
        }
        echo json_encode($msg);
	}
}
