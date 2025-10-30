<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Contacts extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('customer_management_model/Contacts_model');
        $this->check_session();
        $checkUser = $this->session->userdata('user_data');
		$this->user = $checkUser->uidnr_admin;
    }

    public function index() {

        // echo $this->uri->segment('3');die;
        $customer_type = $this->uri->segment('4');
        $name_customer = $this->uri->segment('5');
        $name_contact = $this->uri->segment('6');

        $type_contact = $this->uri->segment('7');
        $created_by = $this->uri->segment('8');
        $search = $this->uri->segment('9');
        $status = $this->uri->segment('10');
        $sortby = $this->uri->segment('11');
        $order = $this->uri->segment('12');
        $page_no = $this->uri->segment('13');
        


        $where = NULL;
        $base_url = 'customer_management/Contacts/index';
        if ($customer_type != NULL  && $customer_type != 'NULL') {
            $data['customer_type'] = base64_decode($customer_type);
            $base_url  .= '/' . $customer_type;
            $where['contact.customer_type'] = base64_decode($customer_type); 
        } else {
            $data['customer_type'] = 'NULL';
            $base_url  .= '/NULL';
        }
        if ($name_customer != NULL  && $name_customer != 'NULL') {
            $data['name_customer'] = base64_decode($name_customer);
            $base_url  .= '/' . $name_customer;
            $where['cust.customer_name'] = base64_decode($name_customer); 
        } else {
            $data['name_customer'] = 'NULL';
            $base_url  .= '/NULL';
        }
        if ($name_contact != NULL  && $name_contact != 'NULL') {
            $data['name_contact'] = base64_decode($name_contact);
            $base_url  .= '/' . $name_contact;
            $where['contact.contact_name'] = base64_decode($name_contact); 
        } else {
            $data['name_contact'] = 'NULL';
            $base_url  .= '/NULL';
        }
        if ($type_contact != NULL  && $type_contact != 'NULL') {
            $data['type_contact'] = base64_decode($type_contact);
            $base_url  .= '/' . $type_contact;
            $where['contact.type'] = base64_decode($type_contact); 
        } else {
            $data['type_contact'] = 'NULL';
            $base_url  .= '/NULL';
        }
        if ($created_by != NULL  && $created_by != 'NULL') {
            $data['created_by'] = base64_decode($created_by);
            $base_url  .= '/' . $created_by;
            $where['ap.uidnr_admin'] = base64_decode($created_by); 
        } else {
            $data['created_by'] = 'NULL';
            $base_url  .= '/NULL';
        }
        if ($search != NULL && $search != 'NULL') {
            $data['search'] =  base64_decode($search);
            $base_url .= '/' . $search;
            $search = base64_decode($search);
        } else {
            $base_url .= '/NULL';
            $data['search'] = '';
            $search = "NULL";
        }

        if ($status != NULL  && $status != 'NULL') {
            $data['status'] = base64_decode($status);
            $base_url  .= '/' . $status;
            $where['contact.status'] = base64_decode($status); 
        } else {
            $data['status'] = 'NULL';
            $base_url  .= '/NULL';
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
        $total_row = $this->Contacts_model->fetch_contacts_list(NULL, NULL, $search, NULL, NULL, $where, '1');
        $config = $this->pagination($base_url, $total_row,10,13);
        $data["links"] = $config["links"];
        $data['result'] = $this->Contacts_model->fetch_contacts_list($config["per_page"], $config['page'],$search,$sortby,$order, $where);
        $start = (int)$page_no + 1;
        $end = (($data['result']) ? count($data['result']) : 0) + (($page_no) ? $page_no : 0);
        $data['result_count'] = "Showing " . $start . " - " . $end . " of " . $total_row . " Results";
        $data['cust_name'] = $this->Contacts_model->fetch_cust_name();
        $data['contacts_name'] = $this->Contacts_model->fetch_contact_name();
        $data['created_by_name'] = $this->Contacts_model->fetch_created_person();
        $data['countries'] = $this->Contacts_model->fetch_country();
        if ($order == NULL || $order == 'NULL') {
            $data['order'] = ($order) ? "DESC" : "ASC";
        } else {
            $data['order'] = ($order == "ASC") ? "DESC" : "ASC";
        }
        $this->load_view('customer_management/manage_contacts', $data);
    }

    public function contact_status() {
      $contact_id = $this->input->post('contact_id');
        if (!empty($contact_id)) {
            $status = $this->Contacts_model->update_contact_status($contact_id);

            if ($status) {
                $log = $this->user_log_update_CONTACT($contact_id,'STATUS UPDATED','CONTACT STATUS');
                if($log){
                    $result = array(
                        'status'=>1,
                        'msg'=>'Contact status updated'
                    );
                    $this->session->set_flashdata('success', 'Contact status updated');

                }
                else{
                    $result = array(
                        'status'=>0,
                        'msg'=>'error in generating log'
                    );
                }
                
            } else {
                $result = array(
                    'status'=>0,
                    'msg'=>'error in updating status'
                );
               
            }
        }
        echo json_encode($result);
    }

    public function extract_cust_name(){
        $fetch_data = $this->input->post();
        $fetch_type = $fetch_data['customer_type'];
        echo json_encode($this->Contacts_model->extract_cust_name($fetch_type, $customer_id=NULL));
    }

    public function extract_state(){
        $fetch_data = $this->input->post();
        $fetch_id = $fetch_data['country_id'];
        echo json_encode($this->Contacts_model->extract_state($fetch_id));
    }

    public function add_contact()
    {
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
        // $this->form_validation->set_rules('contact_salutation', 'Contact Salutation', 'required|trim|min_length[2]|max_length[5]');
        // $this->form_validation->set_rules('customer_type', 'Customer Type', 'required');
        // $this->form_validation->set_rules('contacts_designation_id', 'Contact Designation', 'required');
        $this->form_validation->set_rules('telephone', 'Telephone', 'required|trim');
        $this->form_validation->set_rules('type', 'Type', 'required');
        $this->form_validation->set_rules('contact_name', 'Contact Name', 'required|is_unique[contacts.contact_name]');
        // $this->form_validation->set_rules('contacts_customer_id', 'Customer Name', 'required');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[contacts.email]');
        // $this->form_validation->set_rules('mobile_no', 'Mobile Number', 'required|trim');
        // $this->form_validation->set_rules('note', 'note', 'required|trim');
        // $this->form_validation->set_rules('country_id', 'Country', 'required');
        // $this->form_validation->set_rules('state_id', 'State', 'required');
        if ($this->form_validation->run() == false) {
            $data = array(
                'error' => $this->form_validation->error_array(),
                'status' => 0,
                'msg'=>'fill all required fields'
            );
        } else {
            $checkUser = $this->session->userdata();
            $store_data = array();
            $fetch_data = $this->input->post();
            $store_data['contact_salutation'] = $fetch_data['contact_salutation'];
            $store_data['customer_type'] = $fetch_data['customer_type'];
            $store_data['contacts_designation_id'] = $fetch_data['contacts_designation_id'];
            $store_data['telephone'] = $fetch_data['telephone'];
            $store_data['type'] = $fetch_data['type'];
            $store_data['contact_name'] = $fetch_data['contact_name'];
            $store_data['contacts_customer_id'] = $fetch_data['contacts_customer_id'];
            $store_data['email'] = $fetch_data['email'];
            $store_data['mobile_no'] = $fetch_data['mobile_no'];
            $store_data['country_id'] = $fetch_data['country_id'];
            $store_data['state_id'] = $fetch_data['state_id'];
            $store_data['note'] = $fetch_data['note'];
            $store_data['created_by'] = $checkUser['user_data']->uidnr_admin;
            $store_data['created_on'] = date("Y-m-d h:i:s");
            if (!empty($fetch_data)) {
                $insert_data = $this->Contacts_model->insert_data('contacts', $store_data);
                
                if($insert_data){

                    $log = $this->user_log_update_CONTACT($insert_data,'CONTACT ADDED WITH NAME '.$store_data['contact_name'],'ADD CONTACT');
                    if($log){
                        $data = array(
                            'status' => 1,
                            'msg'=>'Contact added Successfully'
                        );
                    }
                    else{
                        $data = array(
                            'status' => 0,
                            'msg'=>'Error in generating log'
                        ); 
                    }
                    
                }
                else {
                    $this->session->set_flashdata('error', 'Error in adding Contact');
                    $data = array(
                        'status' => 0,
                        'msg'=>'Error in adding Contact'
                    );
                }
            } else {
                $this->session->set_flashdata('error', 'Data Not Found !!');
                $data = array(
                    'status' => 0,
                    'msg'=>'Data Not Found !!'
                );
            }
        }
        echo json_encode($data);
    }

    public function fetch_contact_data(){
        $id = $this->input->post();
        $contact_id = $id['contact_id'];
        $data = $this->Contacts_model->fetch_cont_details($contact_id);
        echo json_encode($data);
    }

    public function update_contact()
    {
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
        // $this->form_validation->set_rules('contact_salutation', 'Contact Salutation', 'required|trim|min_length[2]|max_length[5]');
        // $this->form_validation->set_rules('customer_type', 'Customer Type', 'required');
        // $this->form_validation->set_rules('contacts_designation_id', 'Contact Designation', 'required');
        $this->form_validation->set_rules('telephone', 'Telephone', 'required|trim');
        $this->form_validation->set_rules('type', 'Type', 'required');
        $this->form_validation->set_rules('contact_name', 'Contact Name', 'required');
        // $this->form_validation->set_rules('contacts_customer_id', 'Customer Name', 'required');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
        // $this->form_validation->set_rules('mobile_no', 'Mobile Number', 'required|trim');
        // $this->form_validation->set_rules('note', 'note', 'required|trim');
        // $this->form_validation->set_rules('country_id', 'Country', 'required');
        // $this->form_validation->set_rules('state_id', 'State', 'required');
        if ($this->form_validation->run() == false) {
            $data = array(
                'error' => $this->form_validation->error_array(),
                'status' => 0,
                'msg'=>'fill all required fields'
            );
        } else {
            $checkUser = $this->session->userdata();
            $data_store = array();
            $fetch_data = $this->input->post();
            $data_store['contact_salutation'] = $fetch_data['contact_salutation'];
            $data_store['customer_type'] = $fetch_data['customer_type'];
            $data_store['contacts_designation_id'] = $fetch_data['contacts_designation_id'];
            $data_store['telephone'] = $fetch_data['telephone'];
            $data_store['type'] = $fetch_data['type'];
            $data_store['contact_name'] = $fetch_data['contact_name'];
            $data_store['contacts_customer_id'] = $fetch_data['contacts_customer_id'];
            $data_store['email'] = $fetch_data['email'];
            $data_store['mobile_no'] = $fetch_data['mobile_no'];
            $data_store['country_id'] = $fetch_data['country_id'];
            $data_store['state_id'] = $fetch_data['state_id'];
            $data_store['note'] = $fetch_data['note'];
            $data_store['updated_by'] = $checkUser['user_data']->uidnr_admin;
            $data_store['updated_on'] = date("Y-m-d h:i:s");

           
            $where['contact_id'] = $fetch_data['contact_id'];
            if (!empty($fetch_data)) {
                
                $update_data = $this->Contacts_model->update_data('contacts', $data_store, $where); 
            
                if($update_data){
                   
                    $log = $this->user_log_update_CONTACT($fetch_data['contact_id'],'CONTACT UPDATED WITH NAME '.$fetch_data['contact_name'],'UPDATE CONTACT');
                    if($log){
                        $data = array(
                            'status' => 1,
                            'msg'=>'Contact Updated Successfully'
                        );
                    }
                    else{
                        $data = array(
                            'status' => 0,
                            'msg'=>'Error in generating log'
                        ); 
                    }
                   
                }
                else {
                    $this->session->set_flashdata('error', 'Error in Updating Contact');
                    $data = array(
                        'status' => 0,
                        'msg'=>'Error in Updating Contact'
                    );
                }
            } else {
                $this->session->set_flashdata('error', 'Data Not Found !!');
                $data = array(
                    'status' => 0,
                    'msg'=>'Data Not Found !!'
                );
            }
        }
        echo json_encode($data);
    }

    public function get_contact_log_CONTACT()
	{
		$contact_id = $this->input->post('contact_id');
		$data = $this->Contacts_model->get_contact_log_CONTACT($contact_id);
		echo json_encode($data);
	}


    public function user_log_update_CONTACT($contact_id,$text,$action){
		$data = array();
		$data['source_module'] = 'Contacts';
		$data['record_id'] = $contact_id;
		$data['created_on'] = date("Y-m-d h:i:s");
		$data['created_by'] = $this->user;
		$data['action_taken'] = $action;
		$data['text'] = $text;

		$result = $this->Contacts_model->insert_data('user_log_history',$data);
		if($result){
			return true;
		}
		else{
			return false;
		}
}
}