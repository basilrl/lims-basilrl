<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Country extends MY_Controller
{
    
    public function __construct(){
        parent::__construct();
        $this->load->model('Country_model', 'CM');
        $this->check_session();
    }

    public function index(){
        $where = NULL;
        $search = NULL;
        $sortby = NULL;
        $order = NULL;

        $base_url = 'Country/index';
        if ($this->uri->segment('3') != NULL && $this->uri->segment('3') != 'NULL') {
            $id_cont = $this->uri->segment('3');
            $data['id_cont'] =  base64_decode($id_cont);
            $base_url .= '/' . $id_cont;
            $where['msc.country_id'] = base64_decode($id_cont);
        } else {
            $base_url .= '/NULL';
            $data['id_cont'] = NULL;
        }

        if ($this->uri->segment('4') != NULL && $this->uri->segment('4') != 'NULL') {
            $id_cont_code = $this->uri->segment('4');
            $data['id_cont_code'] =  base64_decode($id_cont_code);
            $base_url .= '/' . $id_cont_code;
            $where['msc.country_id'] = base64_decode($id_cont_code);
        } else {
            $base_url .= '/NULL';
            $data['id_cont_code'] = NULL;
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
            $id_status = $this->uri->segment('6');
            $data['id_status'] =  base64_decode($id_status);
            $base_url .= '/' . $id_status;
            $where['msc.status'] = base64_decode($id_status);
        } else {
            $base_url .= '/NULL';
            $data['id_status'] = "NULL";
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
        } else {
            $base_url .= '/NULL';
        }

        if ($this->uri->segment('9') != NULL && $this->uri->segment('9') != 'NULL') {
            $order = $this->uri->segment('9');
            $base_url .= '/' . $order;
        } else {
            $base_url .= '/NULL';
        }

        $total_row = $this->CM->get_country_list(NULL, NULL, $search, NULL, NULL, $where, '1');
        $config = $this->pagination($base_url, $total_row, 10, 10);
        $data["links"] = $config["links"];
        $data['countries'] = $this->CM->fetch_country();
        $data['cont_codes'] = $this->CM->fetch_country_code();
        $data['created_by_name'] = $this->CM->fetch_created_person();
        $data['countries_list'] = $this->CM->get_country_list($config["per_page"], $config['page'], $search, $sortby, $order, $where);
        $page_no = $this->uri->segment('10');
        $start = (int)$page_no + 1;
        $end = (($data['countries_list']) ? count($data['countries_list']) : 0) + (($page_no) ? $page_no : 0);
        $data['result_count'] = "Showing " . $start . " - " . $end . " of " . $total_row . " Results";
        if ($order == NULL || $order == 'NULL') {
            $data['order'] = ($order) ? "DESC" : "ASC";
        } else {
            $data['order'] = ($order == "ASC") ? "DESC" : "ASC";
        }
        $this->load_view('country/manage_country', $data);
    }

    public function add_country(){
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
        $this->form_validation->set_rules('country_name', 'Country Name', 'required|trim|is_unique[mst_country.country_name]');
        $this->form_validation->set_rules('country_code', 'Country Code', 'required|trim|is_unique[mst_country.country_code]');
        $this->form_validation->set_rules('status', 'Status', 'required');
        if ($this->form_validation->run() == false) {
            $data = array(
                'error' => $this->form_validation->error_array(),
                'status' => 0,
                'msg' => 'Please Fill All Required Fields'
            );
        } else {
            $checkUser = $this->session->userdata();
            $store_data = array();
            $fetch_data = $this->input->post();
            $store_data['country_name'] = $fetch_data['country_name'];
            $store_data['country_code'] = $fetch_data['country_code'];
            $store_data['status'] = $fetch_data['status'];
            $store_data['created_by'] = $checkUser['user_data']->uidnr_admin;
            $store_data['created_on'] = date("Y-m-d h:i:s");
            if (!empty($fetch_data)) {
                $insert_data = $this->CM->insert_data('mst_country', $store_data);
                if ($insert_data) {
                    $log_deatils = array(
                        'text'          => "Added Country with country name ".$store_data['country_name'],
                        'created_by'    => $store_data['created_by'],
                        'created_on'    => $store_data['created_on'],
                        'record_id'     => $insert_data,
                        'source_module' => 'Country',
                        'action_taken'  => 'add_country'
                    );

                    $log = $this->CM->insert_data('user_log_history',$log_deatils);
                    if($log){
                        $this->session->set_flashdata('success', 'Country added Successfully');
                        $data = array(
                            'status' => 1,
                            'msg' => 'Country Added Successfully'
                        );
                    }
                    else{
                        $this->session->set_flashdata('error', 'Error in Maintaining Country Add Log ');
                        $data = array(
                            'status' => 0,
                            'msg' => 'Error in Maintaining Country Add Log'
                        );
                    }
                    
                } else {
                    $this->session->set_flashdata('error', 'Error in adding Country');
                    $data = array(
                        'status' => 0,
                        'msg' => 'Error in adding Country'
                    );
                }
            } else {
                $this->session->set_flashdata('error', 'Data Not Found !!');
                $data = array(
                    'status' => 0,
                    'msg' => 'Data Not Found'
                );
            }
        }
        echo json_encode($data);
    }

    public function edit_country(){
        $country_id = $this->input->post('country_id');
        $data = $this->CM->get_country_data($country_id);
        echo json_encode($data);
    }

    public function update_country(){
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
        $this->form_validation->set_rules('country_name', 'Country Name', 'required|trim|callback_update_name');
        $this->form_validation->set_rules('country_code', 'Country Code', 'required|trim|callback_update_code');
        if ($this->form_validation->run() == false) {
            $data = array(
                'error' => $this->form_validation->error_array(),
                'status' => 0,
                'msg' => 'Please Fill Al Required Fields'
            );
        } else {
            $store_data = array();
            $checkUser = $this->session->userdata();
            $fetch_data = $this->input->post();
            $store_data['country_name'] = $fetch_data['country_name'];
            $store_data['country_code'] = $fetch_data['country_code'];
            $store_data['updated_by'] = $checkUser['user_data']->uidnr_admin;
            $store_data['updated_on'] = date("Y-m-d h:i:s");
            $where['country_id'] = $fetch_data['country_id'];
            if (!empty($fetch_data)) {
                $data_updated = $this->CM->update_data('mst_country', $store_data, $where);
                if ($data_updated) {
                    $log_deatils = array(
                        'text'          => "Updated Country with Country Name ".$store_data['country_name'],
                        'created_by'    => $store_data['updated_by'],
                        'created_on'    => $store_data['updated_on'],
                        'record_id'     => $where['country_id'],
                        'source_module' => 'Country',
                        'action_taken'  => 'update_country'
                    );
    
                    $log = $this->CM->insert_data('user_log_history',$log_deatils);
                    if($log){
                        $this->session->set_flashdata('success', 'Country Details Updated Successfully');
                        $data = array(
                            'status' => 1,
                            'msg' => 'Country Details Updated Successfully'
                        );
                    }
                    else{
                        $this->session->set_flashdata('error', 'Error in Maintaining Update Country Log');
                        $data = array(
                            'status' => 0,
                            'msg' => 'Error in Maintaining Update Country Log'
                        );
                    }
                } else {
                    $this->session->set_flashdata('error', 'Error in Updating Country Details');
                    $data = array(
                        'status' => 0,
                        'msg' => 'Error in Updating Country Details'
                    );
                }
            } else {
                $this->session->set_flashdata('error', 'Data Not Found !!');
                $data = array(
                    'status' => 0,
                    'msg' => 'Data Not Found !!'
                );
            }
        }
        echo json_encode($data);
    }

    public function cont_status() {
        if (!empty($this->input->post())) {
            $checkUser = $this->session->userdata();
            $data_fetch = $this->CM->get_country_data($this->input->post('country_id'));
            $country_name = $data_fetch->country_name;
            $status = $this->CM->update_cont_status($this->input->post());
            if ($status) {
                $log_deatils = array(
                    'text'          => "Updated Country Status with Country name ".$country_name,
                    'created_by'    => $checkUser['user_data']->uidnr_admin,
                    'created_on'    => date('Y-m-d h:i:s'),
                    'record_id'     => $this->input->post('country_id'),
                    'source_module' => 'Country',
                    'action_taken'  => 'cont_status'
                );
                $log = $this->CM->insert_data('user_log_history',$log_deatils);
                if($log){
                    $this->session->set_flashdata('success', 'Country Status Updated Successfully');
                    $data = array(
                        'status' => 1,
                        'msg' => 'Country Status Updated Successfully'
                    );
                }
                else{
                    $this->session->set_flashdata('error', 'Error in Maintaining Country Status Log');
                    $data = array(
                        'status' => 0,
                        'msg' => 'Error in Maintaining Country Status Log'
                    );
                }
            } else {
                $this->session->set_flashdata('error', 'Error in Updating Country Status');
                $data = array(
                    'status' => 0,
                    'msg' => 'Error in Updating Country Status'
                );
            }
        }
        echo json_encode($data);
    }

    // added by millan on 09-April-2021
    public function update_name($field){
        $update_form = $this->input->post();
        $check_fileds = array();
        $check_fileds['LOWER(country_name)'] = strtolower($update_form['country_name']);
        $check_fileds['country_id NOT IN ('.$update_form['country_id'].')'] =  NULL;
        if(!empty($update_form) && !empty($update_form['country_id']) ){
            $check_in = $this->CM->get_row('*', 'mst_country' , $check_fileds);
            if($check_in){
                $this->form_validation->set_message('update_name', 'The {field} field can not be the same. "It Must be Unique!!"');
                return FALSE;
            }
            else
            {
                return TRUE;
            }
        }
        else{
            return false;
        }
    }

    // added by millan on 09-April-2021
    public function update_code($field){
        $update_form = $this->input->post();
        $check_fileds = array();
        $check_fileds['LOWER(country_code)'] = strtolower($update_form['country_code']);
        $check_fileds['country_id NOT IN ('.$update_form['country_id'].')'] =  NULL;
        if(!empty($update_form) && !empty($update_form['country_id']) ){
            $check_in = $this->CM->get_row('*', 'mst_country' , $check_fileds);
            if($check_in){
                $this->form_validation->set_message('update_code', 'The {field} field can not be the same. "It Must be Unique!!"');
                return FALSE;
            }
            else
            {
                return TRUE;
            }
        }
        else{
            return false;
        }
    }

    // added by millan on 09-April-2021
    public function get_country_log(){
		$country_id = $this->input->post('country_id');
		$data = $this->CM->get_country_log($country_id);
		echo json_encode($data);
	}

    // added by ajit on 20-05-2021
    public function test_country(){
        $checkUser = $this->session->userdata();
        $country_id = base64_decode($this->uri->segment('3'));
        if(base64_decode($this->uri->segment('4'))){
            $post['test_country_flag']='0';
            $msg = "Un-Marked";
        } else {
            $post['test_country_flag']='1';
            $msg = "Marked";
        }
        $result = $this->CM->update_data('mst_country', $post, ['country_id'=>$country_id]);
        if($result){
            $log_deatils = array(
                'text'          => $msg." Country Successfully",
                'created_by'    => $checkUser['user_data']->uidnr_admin,
                'created_on'    => date('Y-m-d h:i:s'),
                'record_id'     => $country_id,
                'source_module' => 'Country',
                'action_taken'  => 'test_country'
            );
            $this->CM->insert_data('user_log_history',$log_deatils);
            $this->session->set_flashdata('success', "Country ".$msg." Successfully");
            redirect($_SERVER['HTTP_REFERER']);
        } else{
            $this->session->set_flashdata('error', 'Error in '.$msg.' country');
            redirect($_SERVER['HTTP_REFERER']);
        }
    }


    // Added by Saurabh on 29-06-2021
    public function lab_location(){
        $checkUser = $this->session->userdata();
        $country_id = base64_decode($this->uri->segment('3'));
        if(base64_decode($this->uri->segment('4'))){
            $post['lab_location_flag']='0';
            $msg = "Un-Marked";
        } else {
            $post['lab_location_flag']='1';
            $msg = "Marked";
        }
        $result = $this->CM->update_data('mst_country', $post, ['country_id'=>$country_id]);
        if($result){
            $log_deatils = array(
                'text'          => $msg." Country Successfully",
                'created_by'    => $checkUser['user_data']->uidnr_admin,
                'created_on'    => date('Y-m-d h:i:s'),
                'record_id'     => $country_id,
                'source_module' => 'Country',
                'action_taken'  => 'lab_location'
            );
            $this->CM->insert_data('user_log_history',$log_deatils);
            $this->session->set_flashdata('success', "Country ".$msg." Successfully");
            redirect($_SERVER['HTTP_REFERER']);
        } else{
            $this->session->set_flashdata('error', 'Error in '.$msg.' country');
            redirect($_SERVER['HTTP_REFERER']);
        }
    }

        // Added by Saurabh on 29-06-2021
        public function product_destination(){
            $checkUser = $this->session->userdata();
            $country_id = base64_decode($this->uri->segment('3'));
            if(base64_decode($this->uri->segment('4'))){
                $post['product_destination_flag']='0';
                $msg = "Un-Marked";
            } else {
                $post['product_destination_flag']='1';
                $msg = "Marked";
            }
            $result = $this->CM->update_data('mst_country', $post, ['country_id'=>$country_id]);
            if($result){
                $log_deatils = array(
                    'text'          => $msg." Country Successfully",
                    'created_by'    => $checkUser['user_data']->uidnr_admin,
                    'created_on'    => date('Y-m-d h:i:s'),
                    'record_id'     => $country_id,
                    'source_module' => 'Country',
                    'action_taken'  => 'product_destination'
                );
                $this->CM->insert_data('user_log_history',$log_deatils);
                $this->session->set_flashdata('success', "Country ".$msg." Successfully");
                redirect($_SERVER['HTTP_REFERER']);
            } else{
                $this->session->set_flashdata('error', 'Error in '.$msg.' country');
                redirect($_SERVER['HTTP_REFERER']);
            }
        }
}
