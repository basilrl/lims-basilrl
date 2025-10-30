<?php

defined('BASEPATH') or exit('No direct script access allowed');

class DepartmentList extends MY_Controller
{

    function __construct(){
        parent::__construct();
        $this->load->model('DepartmentList_model', 'DTM');
        $this->check_session();
    }

    public function index(){
        $where = NULL;
        $search = NULL;
        $sortby = NULL;
        $order = NULL;

        $base_url = 'DepartmentList/index';
        if ($this->uri->segment('3') != NULL && $this->uri->segment('3') != 'NULL') {
            $name_dept = $this->uri->segment('3');
            $data['name_dept'] =  base64_decode($name_dept);
            $base_url .= '/' . $name_dept;
            $where['msd.dept_name'] = base64_decode($name_dept);
        } else {
            $base_url .= '/NULL';
            $data['name_dept'] = NULL;
        }

        if ($this->uri->segment('4') != NULL && $this->uri->segment('4') != 'NULL') {
            $code_dept = $this->uri->segment('4');
            $data['code_dept'] =  base64_decode($code_dept);
            $base_url .= '/' . $code_dept;
            $where['msd.dept_code'] = base64_decode($code_dept);
        } else {
            $base_url .= '/NULL';
            $data['code_dept'] = NULL;
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

        $total_row = $this->DTM->get_department_list(NULL, NULL, $search, NULL, NULL, $where, '1');
        $config = $this->pagination($base_url, $total_row, 10, 10);
        $data["links"] = $config["links"];
        $data['dn_names'] = $this->DTM->fetch_department_name();
        $data['dn_codes'] = $this->DTM->fetch_department_code();
        $data['created_by_name'] = $this->DTM->fetch_created_person();
        $data['depts_list'] = $this->DTM->get_department_list($config["per_page"], $config['page'], $search, $sortby, $order, $where);
        $page_no = $this->uri->segment('9');
        $start = (int)$page_no + 1;
        $end = (($data['depts_list']) ? count($data['depts_list']) : 0) + (($page_no) ? $page_no : 0);
        $data['result_count'] = "Showing " . $start . " - " . $end . " of " . $total_row . " Results";
        if ($order == NULL || $order == 'NULL') {
            $data['order'] = ($order) ? "DESC" : "ASC";
        } else {
            $data['order'] = ($order == "ASC") ? "DESC" : "ASC";
        }
        $this->load_view('departmentlist/manage_department', $data);
    }

    public function add_department(){
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
        $this->form_validation->set_rules('dept_name', 'Department Name', 'required|trim|is_unique[mst_departments.dept_name]');
        $this->form_validation->set_rules('dept_code', 'Department Code', 'required|trim|is_unique[mst_departments.dept_code]');
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
            $store_data['dept_name'] = $fetch_data['dept_name'];
            $store_data['dept_code'] = $fetch_data['dept_code'];
            $store_data['status'] = $fetch_data['status'];
            $store_data['created_by'] = $checkUser['user_data']->uidnr_admin;
            $store_data['created_on'] = date("Y-m-d h:i:s");
            if (!empty($fetch_data)) {
                $insert_data = $this->DTM->insert_data('mst_departments', $store_data);
                if ($insert_data) {
                    $log_deatils = array(
                        'text'          => "Added Department with Department Name ".$store_data['dept_name'],
                        'created_by'    => $store_data['created_by'],
                        'created_on'    => $store_data['created_on'],
                        'record_id'     => $insert_data,
                        'source_module' => 'DepartmentList',
                        'action_taken'  => 'add_department'
                    );

                    $log = $this->DTM->insert_data('user_log_history',$log_deatils);
                    if($log){
                        $this->session->set_flashdata('success', 'Department Added Successfully');
                        $data = array(
                            'status' => 1,
                            'msg' => 'Department Added Successfully'
                        );
                    }
                    else{
                        $this->session->set_flashdata('error', 'Error in Maintaining Department Add Log ');
                        $data = array(
                            'status' => 0,
                            'msg' => 'Error in Maintaining Department Add Log'
                        );
                    }
                    
                } else {
                    $this->session->set_flashdata('error', 'Error in adding Department');
                    $data = array(
                        'status' => 0,
                        'msg' => 'Error in adding Department'
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

    public function fetch_department_for_edit(){
        $dept_id = $this->input->post('dept_id');
        $data = $this->DTM->fetch_department_for_edit($dept_id);
        echo json_encode($data);
    }

    public function update_department(){
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
        $this->form_validation->set_rules('dept_name', 'Department Name', 'required|trim|callback_update_deptname');
        $this->form_validation->set_rules('dept_code', 'Department Code', 'required|trim|callback_update_deptcode');
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
            $store_data['dept_name'] = $fetch_data['dept_name'];
            $store_data['dept_code'] = $fetch_data['dept_code'];
            $store_data['updated_by'] = $checkUser['user_data']->uidnr_admin;
            $store_data['updated_on'] = date("Y-m-d h:i:s");
            $where['dept_id'] = $fetch_data['dept_id'];
            if (!empty($fetch_data)) {
                $data_updated = $this->DTM->update_data('mst_departments', $store_data, $where);
                if ($data_updated) {
                    $log_deatils = array(
                        'text'          => "Updated Department with Department Name ".$store_data['dept_name'],
                        'created_by'    => $store_data['updated_by'],
                        'created_on'    => $store_data['updated_on'],
                        'record_id'     => $where['dept_id'],
                        'source_module' => 'DepartmentList',
                        'action_taken'  => 'update_department'
                    );
    
                    $log = $this->DTM->insert_data('user_log_history',$log_deatils);
                    if($log){
                        $this->session->set_flashdata('success', 'Department Updated Successfully');
                        $data = array(
                            'status' => 1,
                            'msg' => 'Department Updated Successfully'
                        );
                    }
                    else{
                        $this->session->set_flashdata('error', 'Error in Maintaining Update Department Log');
                        $data = array(
                            'status' => 0,
                            'msg' => 'Error in Maintaining Update Department Log'
                        );
                    }
                } else {
                    $this->session->set_flashdata('error', 'Error in Updating Department');
                    $data = array(
                        'status' => 0,
                        'msg' => 'Error in Updating Department Details'
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

    public function department_status() {
        if (!empty($this->input->post())) {
            $checkUser = $this->session->userdata();
            $data_fetch = $this->DTM->fetch_department_for_edit($this->input->post('dept_id'));
            $dept_name = $data_fetch->dept_name;
            $status = $this->DTM->update_department_status($this->input->post());
            if ($status) {
                $log_deatils = array(
                    'text'          => "Updated Department Status with Department name ".$dept_name,
                    'created_by'    => $checkUser['user_data']->uidnr_admin,
                    'created_on'    => date('Y-m-d h:i:s'),
                    'record_id'     => $this->input->post('dept_id'),
                    'source_module' => 'DepartmentList',
                    'action_taken'  => 'department_status'
                );
                $log = $this->DTM->insert_data('user_log_history',$log_deatils);
                if($log){
                    $this->session->set_flashdata('success', 'Department Status Updated Successfully');
                    $data = array(
                        'status' => 1,
                        'msg' => 'Department Status Updated Successfully'
                    );
                }
                else{
                    $this->session->set_flashdata('error', 'Error in Maintaining Department Status Log');
                    $data = array(
                        'status' => 0,
                        'msg' => 'Error in Maintaining Department Status Log'
                    );
                }
            } else {
                $this->session->set_flashdata('error', 'Error in Updating Department Status');
                $data = array(
                    'status' => 0,
                    'msg' => 'Error in Updating Department Status'
                );
            }
        }
        echo json_encode($data);
    }

    // added by millan on 09-April-2021
    public function update_deptname($field){
        $update_form = $this->input->post();
        $check_fileds = array();
        $check_fileds['LOWER(dept_name)'] = strtolower($update_form['dept_name']);
        $check_fileds['dept_id NOT IN ('.$update_form['dept_id'].')'] =  NULL;
        if(!empty($update_form) && !empty($update_form['dept_id']) ){
            $check_in = $this->DTM->get_row('*', 'mst_departments' , $check_fileds);
            if($check_in){
                $this->form_validation->set_message('update_deptname', 'The {field} field can not be the same. "It Must be Unique!!"');
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
    public function update_deptcode($field){
        $update_form = $this->input->post();
        $check_fileds = array();
        $check_fileds['LOWER(dept_code)'] = strtolower($update_form['dept_code']);
        $check_fileds['dept_id NOT IN ('.$update_form['dept_id'].')'] =  NULL;
        if(!empty($update_form) && !empty($update_form['dept_id']) ){
            $check_in = $this->DTM->get_row('*', 'mst_departments' , $check_fileds);
            if($check_in){
                $this->form_validation->set_message('update_deptcode', 'The {field} field can not be the same. "It Must be Unique!!"');
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
    public function get_department_log(){
		$dept_id = $this->input->post('dept_id');
		$data = $this->DTM->get_department_log($dept_id);
		echo json_encode($data);
	}
}
