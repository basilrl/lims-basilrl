<?php

defined('BASEPATH') or exit('No direct script access allowed');

class HolidayList extends MY_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('HolidayList_model');
        $this->check_session();
    }

    public function index(){
        $where = NULL;
        $search = NULL;
        $sortby = NULL;
        $order = NULL;

        $base_url = 'HolidayList/index';
        if ($this->uri->segment('3') != NULL && $this->uri->segment('3') != 'NULL') {
            $mon_no = $this->uri->segment('3');
            $data['mon_no'] =  base64_decode($mon_no);
            $base_url .= '/' . $mon_no;
            $where['MONTH(msp.holiday_date)'] = base64_decode($mon_no);
        } else {
            $base_url .= '/NULL';
            $data['mon_no'] = NULL;
        }

        if ($this->uri->segment('4') != NULL && $this->uri->segment('4') != 'NULL') {
            $year_no = $this->uri->segment('4');
            $data['year_no'] =  base64_decode($year_no);
            $base_url .= '/' . $year_no;
            $where['YEAR(msp.holiday_date)'] = base64_decode($year_no);
        } else {
            $base_url .= '/NULL';
            $data['year_no'] = NULL;
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

        $total_row = $this->HolidayList_model->get_holiday_list(NULL, NULL, $search, NULL, NULL, $where, '1');
        $config = $this->pagination($base_url, $total_row, 10, 9);
        $data["links"] = $config["links"];
        $data['created_by_name'] = $this->HolidayList_model->fetch_created_person();
        $data['holilist'] = $this->HolidayList_model->get_holiday_list($config["per_page"], $config['page'], $search, $sortby, $order, $where);
        $page_no = $this->uri->segment('9');
        $start = (int)$page_no + 1;
        $end = (($data['holilist']) ? count($data['holilist']) : 0) + (($page_no) ? $page_no : 0);
        $data['result_count'] = "Showing " . $start . " - " . $end . " of " . $total_row . " Results";
        if ($order == NULL || $order == 'NULL') {
            $data['order'] = ($order) ? "DESC" : "ASC";
        } else {
            $data['order'] = ($order == "ASC") ? "DESC" : "ASC";
        }
        $this->load_view('holidaylist/manage_holidays', $data);
    }

    public function add_holiday(){
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
        $this->form_validation->set_rules('holiday_date', 'Holiday Date', 'required|trim|is_unique[mst_holidays.holiday_date]');
        $this->form_validation->set_rules('holiday_reason', 'Holiday Reason', 'required|trim|is_unique[mst_holidays.holiday_reason]');
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
            $store_data['holiday_date'] = $fetch_data['holiday_date'];
            $store_data['holiday_reason'] = $fetch_data['holiday_reason'];
            $store_data['created_by'] = $checkUser['user_data']->uidnr_admin;
            $store_data['created_on'] = date("Y-m-d");
            if (!empty($fetch_data)) {
                $insert_data = $this->HolidayList_model->insert_data('mst_holidays', $store_data);
                if ($insert_data) {
                    $log_deatils = array(
                        'text'          => "Added Holiday with name ".$store_data['holiday_reason'],
                        'created_by'    => $store_data['created_by'],
                        'created_on'    => $store_data['created_on'],
                        'record_id'     => $insert_data,
                        'source_module' => 'HolidayList',
                        'action_taken'  => 'add_holiday'
                    );

                    $log = $this->HolidayList_model->insert_data('user_log_history',$log_deatils);
                   
                    if($log){
                        $this->session->set_flashdata('success', 'Holiday added Successfully');
                        $data = array(
                            'status' => 1,
                            'msg' => 'Holiday added Successfully'
                        );
                    }
                    else{
                        $this->session->set_flashdata('error', 'Error in Maintaining Add Holiday Log');
                        $data = array(
                            'status' => 0,
                            'msg' => 'Error in Maintaining Add Holiday Log'
                        );
                    }
                } else {
                    $this->session->set_flashdata('error', 'Error in adding Holiday');
                    $data = array(
                        'status' => 0,
                        'msg' => 'Error in adding Holiday'
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

    public function edit_holiday(){
        $holiday_id = $this->input->post('holiday_id');
        $data = $this->HolidayList_model->fetch_holiday_for_edit($holiday_id);
        if ($data) {
            echo json_encode($data);
        } else {
            echo false;
        }
    }

    public function update_holiday(){
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
        $this->form_validation->set_rules('holiday_date', 'Holiday Date', 'required|trim|callback_update_holi_date');
        $this->form_validation->set_rules('holiday_reason', 'Holiday Reason', 'required|trim|callback_update_holi_reason');
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
            $store_data['holiday_date'] = $fetch_data['holiday_date'];
            $store_data['holiday_reason'] = $fetch_data['holiday_reason'];
            $store_data['updated_by'] = $checkUser['user_data']->uidnr_admin;
            $store_data['updated_on'] = date("Y-m-d h:i:s");
            $where['holiday_id'] = $fetch_data['holiday_id'];
            if (!empty($fetch_data)) {
                $data_updated = $this->HolidayList_model->update_data('mst_holidays', $store_data, $where);
                if ($data_updated) {
                    $log_deatils = array(
                        'text'          => "Updated Holiday with name ".$store_data['holiday_reason'],
                        'created_by'    => $store_data['updated_by'],
                        'created_on'    => $store_data['updated_on'],
                        'record_id'     => $where['holiday_id'],
                        'source_module' => 'HolidayList',
                        'action_taken'  => 'update_holiday'
                    );
    
                    $log = $this->HolidayList_model->insert_data('user_log_history',$log_deatils);
                    if($log){
                        $this->session->set_flashdata('success', 'Holiday Details Updated Successfully');
                        $data = array(
                            'status' => 1,
                            'msg' => 'Holiday Updated Successfully'
                        );
                    }
                    else{
                        $this->session->set_flashdata('error', 'Error in Mainatining Update Holiday Log');
                        $data = array(
                            'status' => 0,
                            'msg' => 'Error in Mainatining Update Holiday Log'
                        );
                    }
                } else {
                    $this->session->set_flashdata('error', 'Error in Updating Holiday Details');
                    $data = array(
                        'status' => 0,
                        'msg' => 'Error in Updating Holiday'
                    );
                }
            } else {
                $this->session->set_flashdata('error', 'Data Not Found !!');
                $data = array(
                    'status' => 0,
                    'error' => 'Data Not Found'
                );
            }
        }
        echo json_encode($data);
    }

    public function delete_holiday(){
        if (!empty($this->input->get())) {
            $status = $this->HolidayList_model->delete_holiday($this->input->get());
            if ($status) {
                $log_deatils = array(
                    'text'          => "Deleted Holiday",
                    'created_by'    => $this->admin_id(),
                    'created_on'    => date('Y-m-d H:i:s'),
                    'record_id'     => $this->input->get(),
                    'source_module' => 'HolidayList',
                    'action_taken'  => 'delete_holiday'
                );

                $this->HolidayList_model->insert_data('user_log_history',$log_deatils);
                $this->session->set_flashdata('success', 'Holiday Deleted sucessfully...');
                redirect(base_url() . 'HolidayList/');
            } else {
                $this->session->set_flashdata('error', 'Error in deleting Holiday !!!');
                redirect(base_url() . 'HolidayList/');
            }
        }
    }
    
    public function get_holiday_log(){
		$holiday_id = $this->input->post('holiday_id');
		$data = $this->HolidayList_model->get_holiday_log($holiday_id);
		echo json_encode($data);
	}

    // added by millan on 08-April-2021
    public function update_holi_date($field){
        $update_form = $this->input->post();
        $check_fileds = array();
        $check_fileds['holiday_date'] = $update_form['holiday_date'];
        $check_fileds['holiday_id NOT IN ('.$update_form['holiday_id'].')'] =  NULL;
        if(!empty($update_form) && !empty($update_form['holiday_id']) ){
            $check_in = $this->HolidayList_model->get_row('*', 'mst_holidays' , $check_fileds);
            if($check_in){
                $this->form_validation->set_message('update_holi_date', 'The {field} field can not be the same. "It Must be Unique!!"');
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
    public function update_holi_reason($field){
        $update_form = $this->input->post();
        $check_fileds = array();
        $check_fileds['LOWER(holiday_reason)'] = strtolower($update_form['holiday_reason']);
        $check_fileds['holiday_id NOT IN ('.$update_form['holiday_id'].')'] =  NULL;
        if(!empty($update_form) && !empty($update_form['holiday_id']) ){
            $check_in = $this->HolidayList_model->get_row('*', 'mst_holidays' , $check_fileds);
            if($check_in){
                $this->form_validation->set_message('update_holi_reason', 'The {field} field can not be the same. "It Must be Unique!!"');
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
