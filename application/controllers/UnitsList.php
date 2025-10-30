<?php

defined('BASEPATH') or exit('No direct script access allowed');

class UnitsList extends MY_Controller
{

    function __construct(){
        parent::__construct();
        $this->load->model('UnitsList_model');
        $this->check_session();
    }

    public function index(){
        $where = NULL;
        $search = NULL;
        $sortby = NULL;
        $order = NULL;

        $base_url = 'UnitsList/index'; 

        if ($this->uri->segment('3') != NULL && $this->uri->segment('3') != 'NULL') {
            $id_unit = $this->uri->segment('3');
            $data['id_unit'] =  base64_decode($id_unit);
            $base_url .= '/' . $id_unit;
            $where['un.unit_id'] = base64_decode($id_unit);
        } else {
            $base_url .= '/NULL';
            $data['id_unit'] = NULL;
        }

        if ($this->uri->segment('4') != NULL && $this->uri->segment('4') != 'NULL') {
            $type_un = $this->uri->segment('4');
            $data['type_un'] =  base64_decode($type_un);
            $base_url .= '/' . $type_un;
            $where['un.unit_type'] = base64_decode($type_un);
        } else {
            $base_url .= '/NULL';
            $data['type_un'] = NULL;
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
            $search = $this->uri->segment('6');
            $data['search'] =  base64_decode($search);
            $base_url .= '/' . $search;
            $search = base64_decode($search);
        } else {
            $base_url .= '/NULL';
            $data['search'] = NULL;
        }

        if ($this->uri->segment('7') != NULL && $this->uri->segment('7') != 'NULL') {
            $sortby = $this->uri->segment('7');
            $base_url .= '/' . $sortby;
        } else {
            $base_url .= '/NULL';
        }

        if ($this->uri->segment('8') != NULL && $this->uri->segment('8') != 'NULL') {
            $order = $this->uri->segment('8');
            $base_url .= '/' . $order;
        } else {
            $base_url .= '/NULL';
        }

        $total_row = $this->UnitsList_model->get_units_list(NULL, NULL, $search, NULL, NULL, $where, '1');
        $config = $this->pagination($base_url, $total_row, 10, 9);
        $data["links"] = $config["links"];
        $data['units_nm'] = $this->UnitsList_model->fetch_units_name();
        $data['created_by_name'] = $this->UnitsList_model->fetch_created_person();
        $data['un_listing'] = $this->UnitsList_model->get_units_list($config["per_page"], $config['page'], $search, $sortby, $order, $where);
        $page_no = $this->uri->segment('9');
        $start = (int)$page_no + 1;
        $end = (($data['un_listing']) ? count($data['un_listing']) : 0) + (($page_no) ? $page_no : 0);
        $data['result_count'] = "Showing " . $start . " - " . $end . " of " . $total_row . " Results";
        if ($order == NULL || $order == 'NULL') {
            $data['order'] = ($order) ? "DESC" : "ASC";
        } else {
            $data['order'] = ($order == "ASC") ? "DESC" : "ASC";
        }
        $this->load_view('unitlist/manage_unit', $data);
    }

    public function add_unit(){
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>'); 
        $this->form_validation->set_rules('unit', 'Unit Name', 'required|trim|is_unique[units.unit]');
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
            $store_data['unit'] = $fetch_data['unit'];
            $store_data['unit_type'] = $fetch_data['unit_type'];
            $store_data['created_by'] = $checkUser['user_data']->uidnr_admin;
            $store_data['created_on'] = date("Y-m-d h:i:s");
            if (!empty($fetch_data)) {
                $insert_data = $this->UnitsList_model->insert_data('units', $store_data);
                if ($insert_data) {
                    $log_deatils = array(
                        'text'          => "Added Unit with name ".$store_data['unit'],
                        'created_by'    => $store_data['created_by'],
                        'created_on'    => $store_data['created_on'],
                        'record_id'     => $insert_data,
                        'source_module' => 'UnitsList',
                        'action_taken'  => 'add_unit'
                    );
    
                    $log = $this->UnitsList_model->insert_data('user_log_history',$log_deatils);
                    if($log){
                        $this->session->set_flashdata('success', 'Unit added Successfully');
                        $data = array(
                            'status' => 1,
                            'msg' => 'Unit Added Successfully'
                        );
                    }
                    else{
                        $this->session->set_flashdata('error', 'Error in Maintaining Unit Add Log');
                        $data = array(
                            'status' => 0,
                            'msg' => 'Error in Maintaining Unit Add Log'
                        );
                    }
                } else {
                    $this->session->set_flashdata('error', 'Error in adding Unit');
                    $data = array(
                        'status' => 0,
                        'msg' => 'Error in adding Unit'
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

    public function fetch_unit_for_edit(){
        if (!empty($this->input->post())) {
            $data = $this->UnitsList_model->fetch_unit_for_edit($this->input->post());
            if ($data) {
                echo json_encode($data);
            } else {
                echo false;
            }
        }
    }

    public function update_unit(){
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>'); 
        $this->form_validation->set_rules('unit', 'Unit Name', 'required|trim|callback_update_unit_name');
        if ($this->form_validation->run() == false) {
            $data = array(
                'errors' => $this->form_validation->error_array(),
                'status' => 0,
                'msg'=>'Please Fill All Required Fields'
            );
        } else {
            $store_data = array();
            $checkUser = $this->session->userdata();
            $fetch_data = $this->input->post();
            $store_data['unit'] = $fetch_data['unit']; 
            $store_data['unit_type'] = $fetch_data['unit_type'];  
            $store_data['updated_by'] = $checkUser['user_data']->uidnr_admin;
            $store_data['updated_on'] = date("Y-m-d h:i:s");
            $where['unit_id'] = $fetch_data['unit_id'];
            if (!empty($fetch_data)) {
                $data_updated = $this->UnitsList_model->update_data('units', $store_data, $where);
                if ($data_updated) {
                    $log_deatils = array(
                        'text'          => "Updated Unit with name ".$store_data['unit'],
                        'created_by'    => $store_data['updated_by'],
                        'created_on'    => $store_data['updated_on'],
                        'record_id'     => $where['unit_id'],
                        'source_module' => 'UnitsList',
                        'action_taken'  => 'update_unit'
                    ); 
                    $log = $this->UnitsList_model->insert_data('user_log_history',$log_deatils);
                    if($log){
                        $this->session->set_flashdata('success', 'Unit Updated Successfully');
                        $data = array(
                            'status' => 1,
                            'msg' => 'Unit Updated Successfully'
                        );
                    }
                    else{
                        $this->session->set_flashdata('error', 'Error in Maintaining Unit Updation Log');
                        $data = array(
                            'status' => 0,
                            'msg' => 'Error in Maintaining Unit Updation Log'
                        );
                    }
                } else {
                    $this->session->set_flashdata('error', 'Error in Updating Unit');
                    $data = array(
                        'status' => 0,
                        'msg'=> 'Error in Updating Unit'
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

    public function fetch_units(){
        $department = $this->UnitsList_model->fetch_units();
        echo json_encode($department);
    }

    public function delete_unit(){
        if (!empty($this->input->get())) {
            $status = $this->UnitsList_model->delete_unit($this->input->get());
            if ($status) {
                $log_deatils = array(
                    'text'          => "Deleted Unit",
                    'created_by'    => $this->admin_id(),
                    'created_on'    => date('Y-m-d H:i:s'),
                    'record_id'     => $this->input->get(),
                    'source_module' => 'UnitsList',
                    'action_taken'  => 'delete_unit'
                );

                $this->UnitsList_model->insert_data('user_log_history',$log_deatils);
                $this->session->set_flashdata('success', 'Unit Deleted sucessfully...');
                redirect(base_url() . 'UnitsList/');
            } else {
                $this->session->set_flashdata('error', 'Error in deleting Unit !!!');
                redirect(base_url() . 'UnitsList/');
            }
        }
    }

    
	public function get_unit_log(){
		$unit_id = $this->input->post('unit_id');
		$data = $this->UnitsList_model->get_unit_log($unit_id);
		echo json_encode($data);
	}

    // added by millan on 08-April-2021
    public function update_unit_name($field){
        $update_form = $this->input->post();
        $check_fileds = array();
        $check_fileds['unit'] = $update_form['unit'];
        $check_fileds['unit_id NOT IN ('.$update_form['unit_id'].')'] =  NULL;
        if(!empty($update_form) && !empty($update_form['unit_id']) ){
            $check_in = $this->UnitsList_model->get_row('*', 'units' , $check_fileds);
            if($check_in){
                $this->form_validation->set_message('update_unit_name', 'The {field} field can not be the same. "It Must be Unique!!"');
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
}
