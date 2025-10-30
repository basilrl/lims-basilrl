<?php
defined('BASEPATH') or exit('No direct script access allowed');

class RegulationConfiguration extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('RegulationConfiguration_model', 'RCM');
        $this->check_session();
        $checkUser = $this->session->userdata('user_data');
        $this->user = $checkUser->uidnr_admin;
        $this->form_validation->set_error_delimiters('<div class="error" style="color:red">', '</div>');
    }

    public function index()
    {
        $where = NULL;
        $search = NULL;
        $sortby = NULL;
        $order = NULL;

        $base_url = 'RegulationConfiguration/index';

        if ($this->uri->segment('3') != NULL && $this->uri->segment('3') != 'NULL') {
            $id_customer = $this->uri->segment('3');
            $data['id_customer'] =  base64_decode($id_customer);
            $base_url .= '/' . $id_customer;
            $where['cust.customer_id'] = base64_decode($id_customer);
        } else {
            $base_url .= '/NULL';
            $data['id_customer'] = NULL;
        }

        if ($this->uri->segment('4') != NULL && $this->uri->segment('4') != 'NULL') {
            $id_contact = $this->uri->segment('4');
            $data['id_contact'] =  base64_decode($id_contact);
            $base_url .= '/' . $id_contact;
            $where['cont.contact_id'] = base64_decode($id_contact);
        } else {
            $base_url .= '/NULL';
            $data['id_contact'] = NULL;
        }

        if ($this->uri->segment('5') != NULL && $this->uri->segment('5') != 'NULL') {
            $created_pesron = $this->uri->segment('5');
            $data['created_pesron'] =  base64_decode($created_pesron);
            $base_url .= '/' . $created_pesron;
            $where['ap.uidnr_admin'] = base64_decode($created_pesron);
        } else {
            $base_url .= '/NULL';
            $data['created_pesron'] = NULL;
        }

        if ($this->uri->segment('6') != NULL && $this->uri->segment('6') != 'NULL') {
            $status = $this->uri->segment('6');
            $data['status'] =  base64_decode($status);
            $base_url .= '/' . $status;
            $where['crc.status'] = base64_decode($status);
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
            $base_url .= '/' . base64_decode($sortby);
        } else {
            $base_url .= '/NULL';
        }

        if ($this->uri->segment('9') != NULL && $this->uri->segment('9') != 'NULL') {
            $order = $this->uri->segment('9');
            $base_url .= '/' . base64_decode($order);
        } else {
            $base_url .= '/NULL';
        }

        $total_row = $this->RCM->get_regulationconfig_list(NULL, NULL, $search, NULL, NULL, $where, '1');
        $config = $this->pagination($base_url, $total_row, 10, 10);
        $data["links"] = $config["links"];
        $data['cust_name'] = $this->RCM->fetch_cust_name();
        $data['contact_name'] = $this->RCM->fetch_contact_name();
        $data['created_by_name'] = $this->RCM->fetch_created_person();
        $data['countries'] = $this->RCM->fetch_country();
        $data['noti_bodies'] = $this->RCM->fetch_notify_bodies();
        $data['samp_cat'] = $this->RCM->fetch_sample_categories();
        $data['regulationconfig_list'] = $this->RCM->get_regulationconfig_list($config["per_page"], $config['page'], $search, $sortby, $order, $where);
        $page_no = $this->uri->segment('10');
        $start = (int)$page_no + 1;
        $end = (($data['regulationconfig_list']) ? count($data['regulationconfig_list']) : 0) + (($page_no) ? $page_no : 0);
        $data['result_count'] = "Showing " . $start . " - " . $end . " of " . $total_row . " Results";
        if ($order == NULL || $order == 'NULL') {
            $data['order'] = ($order) ? "DESC" : "ASC";
        } else {
            $data['order'] = ($order == "ASC") ? "DESC" : "ASC";
        }
        $this->load_view('regulationconfiguration/regulationconfig_listing', $data);
    }

    public function extract_cont_name()
    {
        $fetch_data = $this->input->post();
        $fetch_type = $fetch_data['contacts_customer_id'];
        echo json_encode($this->RCM->extract_cont_name($fetch_type));
    }

    public function add_regulationconfig()
    {
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
        $this->form_validation->set_rules('customer_id', 'Customer Name', 'required');
        $this->form_validation->set_rules('status', 'Status', 'required');
        if ($this->form_validation->run() == false) {
            $data = array(
                'errors' => $this->form_validation->error_array(),
                'status' => 0,
                'msg'=>'fill all required fields'
            );
        } else {
            $checkUser = $this->session->userdata();
            $store_data = array();
            $fetch_data = $this->input->post();
            $country_id = implode(",", $fetch_data['country_id']);
            $notified_body_id = implode(",", $fetch_data['notified_body_id']);
            $division_id = implode(",", $fetch_data['division_id']);
            $store_data['customer_id'] = $fetch_data['customer_id'];
            $store_data['country_id'] = $country_id;
            $store_data['notified_body_id'] = $notified_body_id;
            $store_data['division_id'] = $division_id;
            $store_data['status'] = $fetch_data['status'];
            $store_data['contact_id'] = $fetch_data['contact_id'];
            $store_data['created_by'] = $checkUser['user_data']->uidnr_admin;
            $store_data['created_date'] = date("Y-m-d h:i:s");
            if (!empty($fetch_data)) {
                $insert_data = $this->RCM->insert_data('cps_regulations_configuration', $store_data);
              
                if ($insert_data) {
                
                    $log= $this->user_log_update($insert_data,'REGULATION CONFIGURATION ADDED','add_regulationconfig');
                    if($log){
                       
                        $data = array(
                            'status' => 1,
                            'msg'=>'Regulation Configuration added Successfully'
                        );
                        $this->session->set_flashdata('success', 'Regulation Configuration added Successfully');
                    }
                    else{
                        $data = array(
                            'status' => 0,
                            'msg'=>'error in generating log'
                        );
                    }

                    $data = array(
                        'status' => 1,
                        'msg'=>'Regulation Configuration added Successfully'
                    );
                } else {
                    $this->session->set_flashdata('error', 'Error in adding Regulation Configuration');
                    $data = array(
                        'status' => 0,
                        'msg'=>'Error in adding Regulation Configuration'
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

    public function edit_regulationconfiguration()
    {
        $reg_conf_id = $this->input->post('reg_conf_id');
        $data = $this->RCM->get_regulationconfig_data($reg_conf_id);
        if ($data) {
            $data->country_id = !empty($data->country_id) ? explode(',', $data->country_id) : '';
            $data->division_id = !empty($data->division_id) ? explode(',', $data->division_id) : '';
            $data->notified_body_id = !empty($data->notified_body_id) ? explode(',', $data->notified_body_id) : '';
        }
        echo json_encode($data);
    }

    public function update_regulationconfiguration()
    {
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
        $this->form_validation->set_rules('customer_id', 'Customer Name', 'required');
        $this->form_validation->set_rules('status', 'Status', 'required');
        if ($this->form_validation->run() == false) {
            $data = array(
                'errors' => $this->form_validation->error_array(),
                'status' => 0,
                'msg'=>'fill all required fields'
            );
        } else {
            $store_data = array();
            $checkUser = $this->session->userdata();
            $fetch_data = $this->input->post();
            $country_id = implode(",", $fetch_data['country_id']);
            $notified_body_id = implode(",", $fetch_data['notified_body_id']);
            $division_id = implode(",", $fetch_data['division_id']);
            $store_data['customer_id'] = $fetch_data['customer_id'];
            $store_data['country_id'] = $country_id;
            $store_data['notified_body_id'] = $notified_body_id;
            $store_data['division_id'] = $division_id;
            $store_data['status'] = $fetch_data['status'];
            $store_data['contact_id'] = $fetch_data['contact_id'];
            $store_data['created_by'] = $checkUser['user_data']->uidnr_admin;
            $store_data['created_date'] = date("Y-m-d h:i:s");
            $where['reg_conf_id'] = $fetch_data['reg_conf_id'];
            if (!empty($fetch_data)) {
                $data_updated = $this->RCM->update_data('cps_regulations_configuration', $store_data, $where);
                if ($data_updated) {
                    $this->session->set_flashdata('success', 'Regulation Configuration Updated Successfully');
                    $log= $this->user_log_update($where['reg_conf_id'],'REGULATION CONFIGURATION UPDATED','update_regulationconfiguration');
                    if($log){
                        $this->session->set_flashdata('success', 'Regulation Configuration Updated Successfully');
                        $data = array(
                            'status' => 1,
                            'msg'=>'Regulation Configuration Updated Successfully'
                        );
                    }
                    else{
                        $data = array(
                            'status' => 0,
                            'msg'=>'error in generating log'
                        );
                    }
                } else {
                    $this->session->set_flashdata('error', 'Error in Updating Regulation Configuration');
                    $data = array(
                        'status' => 0
                    );
                }
            } else {
                $this->session->set_flashdata('error', 'Data Not Found !!');
                $data = array(
                    'status' => 0
                );
            }
        }
        echo json_encode($data);
    }

    public function get_user_log_data()
    {
        $reg_conf_id = $this->input->post('reg_conf_id');
        $data = $this->RCM->get_user_logData($reg_conf_id);
        echo json_encode($data);
    }

    public function regconfig_status() {
        if (!empty($this->input->post())) {
           $id =$this->input->post('reg_conf_id');
            $status = $this->RCM->update_regconfig_status($this->input->post());
            if ($status) {
                
                $log = $this->user_log_update($id,'STATUS UPDATED','regconfig_status');
                if($log){
                    $this->session->set_flashdata('success', 'Regulation Configuration Status Updated successfully');
                    echo json_encode(array('msg' => 'upadte status'));
                }
                else{
                    $this->session->set_flashdata('error', 'Error in Updating Regulation Configuration Status');
                    echo json_encode(array('msg' => 'not upadte status'));
                }
               
            } else {
                $this->session->set_flashdata('error', 'Error in Updating Regulation Configuration Status');
                echo json_encode(array('msg' => 'not upadte status'));
            }
        }
    }

    public function get_log_data()
	{
		$id = $this->input->post('id');
		$data = $this->RCM->get_log_data($id);
		echo json_encode($data);
	}


	public function user_log_update($id,$text,$action){
		$data = array();
		$data['source_module'] = 'RegulatiConfiguration';
		$data['record_id'] = $id;
		$data['created_on'] = date("Y-m-d h:i:s");
		$data['created_by'] = $this->user;
		$data['action_taken'] = $action;
		$data['text'] = $text;

		$result = $this->RCM->insert_data('user_log_history',$data);
		if($result){
			return true;
		}
		else{
			return false;
		}

}
}
