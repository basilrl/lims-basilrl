<?php

defined('BASEPATH') or exit('No direct script access allowed');

class LabType extends MY_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('LabType_model');
        $this->check_session();
    }

    public function index(){
        $where = NULL;
        $search = NULL;
        $sortby = NULL;
        $order = NULL;

        $base_url = 'LabType/index';

        if ($this->uri->segment('3') != NULL && $this->uri->segment('3') != 'NULL') {
            $id_lab_type = $this->uri->segment('3');
            $data['id_lab_type'] =  base64_decode($id_lab_type);
            $base_url .= '/' . $id_lab_type;
            $where['mlt.lab_type_id'] = base64_decode($id_lab_type);
        } else {
            $base_url .= '/NULL';
            $data['id_lab_type'] = NULL;
        }

        if ($this->uri->segment('4') != NULL && $this->uri->segment('4') != 'NULL') {
            $id_division = $this->uri->segment('4');
            $data['id_division'] =  base64_decode($id_division);
            $base_url .= '/' . $id_division;
            $where['msd.division_id'] = base64_decode($id_division);
        } else {
            $base_url .= '/NULL';
            $data['id_division'] = NULL;
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
            $where['mlt.status'] = base64_decode($id_status);
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

        $total_row = $this->LabType_model->get_labtype_list(NULL, NULL, $search, NULL, NULL, $where, '1');
        $config = $this->pagination($base_url, $total_row, 10, 10);
        $data["links"] = $config["links"];
        $data['divisions'] = $this->LabType_model->fetch_division();
        $data['lab_types'] = $this->LabType_model->fetch_lab_types();
        $data['created_by_name'] = $this->LabType_model->fetch_created_person();
        $data['labtype_list'] = $this->LabType_model->get_labtype_list($config["per_page"], $config['page'], $search, $sortby, $order, $where);
        $page_no = $this->uri->segment('10');
        $start = (int)$page_no + 1;
        $end = (($data['labtype_list']) ? count($data['labtype_list']) : 0) + (($page_no) ? $page_no : 0);
        $data['result_count'] = "Showing " . $start . " - " . $end . " of " . $total_row . " Results";
        if ($order == NULL || $order == 'NULL') {
            $data['order'] = ($order) ? "DESC" : "ASC";
        } else {
            $data['order'] = ($order == "ASC") ? "DESC" : "ASC";
        }
        $this->load_view('labtype/manage_labtype', $data);
    }

    public function add_labtype(){
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
        $this->form_validation->set_rules('lab_type_name', 'Lab Type Name', 'required|trim|is_unique[mst_lab_type.lab_type_name]');
        $this->form_validation->set_rules('mst_lab_type_division_id', 'Division', 'required');
        $this->form_validation->set_rules('status', 'Status', 'required');
        if ($this->form_validation->run() == false) {
            $data = array(
                'errors' => $this->form_validation->error_array(),
                'status' => 0,
                'msg' => 'Please Fill All Required Fields'
            );
        } else {
            $checkUser = $this->session->userdata();
            $store_data = array();
            $fetch_data = $this->input->post();
            $store_data['lab_type_name'] = $fetch_data['lab_type_name'];
            $store_data['mst_lab_type_division_id'] = $fetch_data['mst_lab_type_division_id'];
            $store_data['status'] = $fetch_data['status'];
            $store_data['created_by'] = $checkUser['user_data']->uidnr_admin;
            $store_data['created_on'] = date("Y-m-d h:i:s");
            if (!empty($fetch_data)) {
                $insert_data = $this->LabType_model->insert_data('mst_lab_type', $store_data);
                if ($insert_data) {
                    $log_deatils = array(
                        'text'          => "Added Lab Type with name ".$store_data['lab_type_name'],
                        'created_by'    => $store_data['created_by'],
                        'created_on'    => $store_data['created_on'],
                        'record_id'     => $insert_data,
                        'source_module' => 'LabType',
                        'action_taken'  => 'add_labtype'
                    );
    
                    $log = $this->LabType_model->insert_data('user_log_history',$log_deatils);
                    if($log){
                        $this->session->set_flashdata('success', 'Lab Type added Successfully');
                        $data = array(
                            'status' => 1,
                            'msg' => 'Lab Type Added Successfully'
                        );
                    }
                    else{
                        $this->session->set_flashdata('error', 'Error in Maintaining Add Lab Type Log');
                        $data = array(
                            'status' => 0,
                            'msg' => 'Error in Maintaining Add Lab Type Log'
                        );
                    }      
                } else {
                    $this->session->set_flashdata('error', 'Error in adding Lab Type');
                    $data = array(
                        'status' => 0,
                        'msg' => 'Error in Adding Lab Type'
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

    public function delete_lab_type(){
        if (!empty($this->input->get())) {
            $status = $this->LabType_model->delete_lab_type($this->input->get());
            if ($status) {
                $log_deatils = array(
                    'text'          => "Deleted Lab Type",
                    'created_by'    => $this->admin_id(),
                    'created_on'    => date('Y-m-d H:i:s'),
                    'record_id'     => $this->input->get(),
                    'source_module' => 'LabType',
                    'action_taken'  => 'delete_lab_type'
                );

                $log = $this->LabType_model->insert_data('user_log_history',$log_deatils);
                if($log){
                    $this->session->set_flashdata('success', 'Lab Type Deleted sucessfully...');
                    redirect(base_url() . 'LabType/');
                }
                else{
                    $this->session->set_flashdata('error', 'Error in Maintaining Delete Lab Type Log');
                    redirect(base_url() . 'LabType/');
                }
            } else {
                $this->session->set_flashdata('error', 'Error in deleting Lab Type !!!');
                redirect(base_url() . 'LabType/');
            }
        }
    }

    public function fetch_labtype_for_edit(){
        if (!empty($this->input->post())) {
            $fetch_data = $this->LabType_model->fetch_labtype_for_edit($this->input->post());
            if ($fetch_data) {
                echo json_encode($fetch_data);
            } else {
                echo false;
            }
        }
    }

    public function update_labtype(){
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>'); 
        $this->form_validation->set_rules('lab_type_name', 'Lab Type Name', 'required|trim|callback_update_lab_type_name');
        $this->form_validation->set_rules('mst_lab_type_division_id', 'Division', 'required|trim');
        if ($this->form_validation->run() == false) {
            $data = array(
                'errors' => $this->form_validation->error_array(),
                'status' => 0,
                'msg' => 'Please Fill All Required Fields'
            );
        } else {
            $store_data = array();
            $checkUser = $this->session->userdata();
            $fetch_data = $this->input->post();
            $store_data['lab_type_name'] = $fetch_data['lab_type_name'];  
            $store_data['mst_lab_type_division_id'] = $fetch_data['mst_lab_type_division_id'];  
            $store_data['updated_by'] = $checkUser['user_data']->uidnr_admin;
            $store_data['updated_on'] = date("Y-m-d h:i:s");
            $where['lab_type_id'] = $fetch_data['lab_type_id'];
            if (!empty($fetch_data)) {
                $data_updated = $this->LabType_model->update_data('mst_lab_type', $store_data, $where);
                if ($data_updated) {
                    $log_deatils = array(
                        'text'          => "Updated Lab Type with name ".$store_data['lab_type_name'],
                        'created_by'    => $store_data['updated_by'],
                        'created_on'    => $store_data['updated_on'],
                        'record_id'     => $where['lab_type_id'],
                        'source_module' => 'LabType',
                        'action_taken'  => 'update_labtype'
                    );
    
                    $log = $this->LabType_model->insert_data('user_log_history',$log_deatils);
                    if($log){
                        $this->session->set_flashdata('success', 'Lab Type Updated Successfully');
                        $data = array(
                            'status' => 1,
                            'msg' => 'Lab Type Updated Successfully'
                        );
                    }
                    else{
                        $this->session->set_flashdata('error', 'Error in Maintaining Lab Type Updation Log');
                        $data = array(
                            'status' => 0,
                            'msg' => 'Error in Maintaining Lab Type Updation Log'
                        );
                    }
                } else {
                    $this->session->set_flashdata('error', 'Error in Updating Lab Type');
                    $data = array(
                        'status' => 0,
                        'msg' => 'Error in Updating Lab Type'
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

    public function labtype_status(){
        if (!empty($this->input->post())) {
            $checkUser = $this->session->userdata();
            $data_fetch = $this->LabType_model->fetch_labtype_for_edit($this->input->post());
            $lab_type_name = $data_fetch->lab_type_name;
            $status = $this->LabType_model->update_labtype_status($this->input->post());
            if ($status) {
                $log_deatils = array(
                    'text'          => "Updated Lab Type Status with name ".$lab_type_name,
                    'created_by'    => $checkUser['user_data']->uidnr_admin,
                    'created_on'    => date('Y-m-d h:i:s'),
                    'record_id'     => $this->input->post('lab_type_id'),
                    'source_module' => 'LabType',
                    'action_taken'  => 'labtype_status'
                );
                $log = $this->LabType_model->insert_data('user_log_history',$log_deatils);
                if($log){
                    $this->session->set_flashdata('success', 'Lab Type Status Updated Successfully');
                    $data = array(
                        'status' => 1,
                        'msg' => 'Lab Type Status Updated Successfully'
                    );
                }
                else{
                    $this->session->set_flashdata('error', 'Error in Maintaining Lab Type Status Log');
                    $data = array(
                        'status' => 0,
                        'msg' => 'Error in Maintaining Lab Type Status Log'
                    );
                }
            } else {
                $this->session->set_flashdata('error', 'Error in Updating Lab Type Status');
                $data = array(
                    'status' => 0,
                    'msg' => 'Error in Updating Lab Type Status'
                );
            }
        }
        echo json_encode($data);
    }

    // added by millan on 08-April-2021 
    public function update_lab_type_name($field){
        $update_form = $this->input->post();
        $check_fileds = array();
        $check_fileds['LOWER(lab_type_name)'] = $update_form['lab_type_name'];
        $check_fileds['mst_lab_type_division_id'] = $update_form['mst_lab_type_division_id'];
        $check_fileds['lab_type_id NOT IN ('.$update_form['lab_type_id'].')'] =  NULL;
        if(!empty($update_form) && !empty($update_form['lab_type_id']) ){
            $check_in = $this->LabType_model->get_row('*', 'mst_lab_type' , $check_fileds);
            if($check_in){
                $this->form_validation->set_message('update_lab_type_name', 'The {field} field can not be the same. "It Must be Unique!!"');
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

    // added by millan on 08-April-2021
    public function get_lab_type_log(){
		$lab_type_id = $this->input->post('lab_type_id');
		$data = $this->LabType_model->get_lab_type_log($lab_type_id);
		echo json_encode($data);
	}
}
