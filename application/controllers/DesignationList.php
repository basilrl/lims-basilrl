<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class DesignationList extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('DesignationList_model', 'DLM');
        $this->check_session();
    }

    public function index(){
        $where = NULL;
        $search = NULL;
        $sortby = NULL;
        $order = NULL;

        $base_url = 'DesignationList/index';
        if ($this->uri->segment('3') != NULL && $this->uri->segment('3') != 'NULL') {
            $id_desg = $this->uri->segment('3');
            $data['id_desg'] =  base64_decode($id_desg);
            $base_url .= '/' . $id_desg;
            $where['msd.designation_id'] = base64_decode($id_desg);
        } else {
            $base_url .= '/NULL';
            $data['id_desg'] = NULL;
        }

        if ($this->uri->segment('4') != NULL && $this->uri->segment('4') != 'NULL') {
            $id_report_to = $this->uri->segment('4');
            $data['id_report_to'] =  base64_decode($id_report_to);
            $base_url .= '/' . $id_report_to;
            $where['ar.id_admin_role'] = base64_decode($id_report_to);
        } else {
            $base_url .= '/NULL';
            $data['id_report_to'] = NULL;
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
            $where['msd.status'] = base64_decode($id_status);
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

        $total_row = $this->DLM->get_designation_list(NULL, NULL, $search, NULL, NULL, $where, '1');
        $config = $this->pagination($base_url, $total_row, 10, 10);
        $data["links"] = $config["links"];
        $data['dn_names'] = $this->DLM->fetch_designation_name();
        $data['reports_to'] = $this->DLM->fetch_report_to();
        $data['created_by_name'] = $this->DLM->fetch_created_person();
        $data['desig_list'] = $this->DLM->get_designation_list($config["per_page"], $config['page'], $search, $sortby, $order, $where);
        $page_no = $this->uri->segment('10');
        $start = (int)$page_no + 1;
        $end = (($data['desig_list']) ? count($data['desig_list']) : 0) + (($page_no) ? $page_no : 0);
        $data['result_count'] = "Showing " . $start . " - " . $end . " of " . $total_row . " Results";
        if ($order == NULL || $order == 'NULL') {
            $data['order'] = ($order) ? "DESC" : "ASC";
        } else {
            $data['order'] = ($order == "ASC") ? "DESC" : "ASC";
        }
        $this->load_view('designationlist/manage_designation', $data);
    }

    public function add_designation(){
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
        $this->form_validation->set_rules('designation_name', 'Designation Name', 'required|trim|is_unique[mst_designations.designation_name]');
        $this->form_validation->set_rules('report_to', 'Report To', 'required');
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
            $store_data['designation_name'] = $fetch_data['designation_name'];
            $store_data['report_to'] = $fetch_data['report_to'];
            $store_data['status'] = $fetch_data['status'];
            $store_data['created_by'] = $checkUser['user_data']->uidnr_admin;
            $store_data['created_on'] = date("Y-m-d h:i:s");
            if (!empty($fetch_data)) {
                $insert_data = $this->DLM->insert_data('mst_designations', $store_data);
                if ($insert_data) {
                    $log_deatils = array(
                        'text'          => "Added Designation with Designation Name ".$store_data['designation_name'],
                        'created_by'    => $store_data['created_by'],
                        'created_on'    => $store_data['created_on'],
                        'record_id'     => $insert_data,
                        'source_module' => 'DesignationList',
                        'action_taken'  => 'add_designation'
                    );

                    $log = $this->DLM->insert_data('user_log_history',$log_deatils);
                    if($log){
                        $this->session->set_flashdata('success', 'Designation Added Successfully');
                        $data = array(
                            'status' => 1,
                            'msg' => 'Designation Added Successfully'
                        );
                    }
                    else{
                        $this->session->set_flashdata('error', 'Error in Maintaining Designation Add Log ');
                        $data = array(
                            'status' => 0,
                            'msg' => 'Error in Maintaining Designation Add Log'
                        );
                    }
                    
                } else {
                    $this->session->set_flashdata('error', 'Error in adding Designation');
                    $data = array(
                        'status' => 0,
                        'msg' => 'Error in adding Designation'
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

    public function fetch_designation_for_edit(){
        $designation_id = $this->input->post('designation_id');
        $data = $this->DLM->fetch_designation_for_edit($designation_id);
        echo json_encode($data);
    }

    public function update_designation(){
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
        $this->form_validation->set_rules('designation_name', 'Designation Name', 'required|trim|callback_update_desgname');
        $this->form_validation->set_rules('report_to', 'Report To', 'required');
        if ($this->form_validation->run() == false) {
            $data = array(
                'error' => $this->form_validation->error_array(),
                'status' => 0,
                'msg' => 'Please Fill All Required Fields'
            );
        } else {
            $store_data = array();
            $checkUser = $this->session->userdata();
            $fetch_data = $this->input->post();
            $store_data['designation_name'] = $fetch_data['designation_name'];
            $store_data['report_to'] = $fetch_data['report_to'];
            $store_data['updated_by'] = $checkUser['user_data']->uidnr_admin;
            $store_data['updated_on'] = date("Y-m-d h:i:s");
            $where['designation_id'] = $fetch_data['designation_id'];
            if (!empty($fetch_data)) {
                $data_updated = $this->DLM->update_data('mst_designations', $store_data, $where);
                if ($data_updated) {
                    $log_deatils = array(
                        'text'          => "Updated Designation with Designation Name ".$store_data['designation_name'],
                        'created_by'    => $store_data['updated_by'],
                        'created_on'    => $store_data['updated_on'],
                        'record_id'     => $where['designation_id'],
                        'source_module' => 'DesignationList',
                        'action_taken'  => 'update_designation'
                    );
    
                    $log = $this->DLM->insert_data('user_log_history',$log_deatils);
                    if($log){
                        $this->session->set_flashdata('success', 'Designation Updated Successfully');
                        $data = array(
                            'status' => 1,
                            'msg' => 'Designation Updated Successfully'
                        );
                    }
                    else{
                        $this->session->set_flashdata('error', 'Error in Maintaining Update Designation Log');
                        $data = array(
                            'status' => 0,
                            'msg' => 'Error in Maintaining Update Designation Log'
                        );
                    }
                } else {
                    $this->session->set_flashdata('error', 'Error in Updating Designation');
                    $data = array(
                        'status' => 0,
                        'msg' => 'Error in Updating Designation Details'
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

    public function designation_status() {
        if (!empty($this->input->post())) {
            $checkUser = $this->session->userdata();
            $data_fetch = $this->DLM->fetch_designation_for_edit($this->input->post('designation_id'));
            $designation_name = $data_fetch->designation_name;
            $status = $this->DLM->update_designation_status($this->input->post());
            if ($status) {
                $log_deatils = array(
                    'text'          => "Updated Designation Status with Designation name ".$designation_name,
                    'created_by'    => $checkUser['user_data']->uidnr_admin,
                    'created_on'    => date('Y-m-d h:i:s'),
                    'record_id'     => $this->input->post('designation_id'),
                    'source_module' => 'DesignationList',
                    'action_taken'  => 'designation_status'
                );
                $log = $this->DLM->insert_data('user_log_history',$log_deatils);
                if($log){
                    $this->session->set_flashdata('success', 'Designation Status Updated Successfully');
                    $data = array(
                        'status' => 1,
                        'msg' => 'Designation Status Updated Successfully'
                    );
                }
                else{
                    $this->session->set_flashdata('error', 'Error in Maintaining Designation Status Log');
                    $data = array(
                        'status' => 0,
                        'msg' => 'Error in Maintaining Designation Status Log'
                    );
                }
            } else {
                $this->session->set_flashdata('error', 'Error in Updating Designation Status');
                $data = array(
                    'status' => 0,
                    'msg' => 'Error in Updating Designation Status'
                );
            }
        }
        echo json_encode($data);
    }

    // // added by millan on 09-April-2021
    public function update_desgname($field){
        $update_form = $this->input->post();
        $check_fileds = array();
        $check_fileds['LOWER(designation_name)'] = strtolower($update_form['designation_name']);
        $check_fileds['report_to'] = $update_form['report_to'];
        $check_fileds['designation_id NOT IN ('.$update_form['designation_id'].')'] =  NULL;
        if(!empty($update_form) && !empty($update_form['designation_id']) ){
            $check_in = $this->DLM->get_row('*', 'mst_designations' , $check_fileds);
            if($check_in){
                $this->form_validation->set_message('update_desgname', 'The {field} field can not be the same. "It Must be Unique!!"');
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
    public function get_designation_log(){
		$designation_id = $this->input->post('designation_id');
		$data = $this->DLM->get_designation_log($designation_id);
		echo json_encode($data);
	}

}
