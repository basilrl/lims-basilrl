<?php
defined('BASEPATH') or exit('No direct access allowed');

class Division extends MY_Controller{
    function __construct(){
        parent::__construct();
        $this->load->model('Division_model','dm');
        $this->check_session();
    }        

    public function index(){
        $where = NULL;
        $search = NULL;
        $sortby = NULL;
        $order = NULL;

        $base_url = 'Division/index';
        if ($this->uri->segment('3') != NULL && $this->uri->segment('3') != 'NULL') {
            $name_divs = $this->uri->segment('3');
            $data['name_divs'] =  base64_decode($name_divs);
            $base_url .= '/' . $name_divs;
            $where['msd.division_name'] = base64_decode($name_divs);
        } else {
            $base_url .= '/NULL';
            $data['name_divs'] = NULL;
        }

        if ($this->uri->segment('4') != NULL && $this->uri->segment('4') != 'NULL') {
            $code_divs = $this->uri->segment('4');
            $data['code_divs'] =  base64_decode($code_divs);
            $base_url .= '/' . $code_divs;
            $where['msd.division_code'] = base64_decode($code_divs);
        } else {
            $base_url .= '/NULL';
            $data['code_divs'] = NULL;
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

        $total_row = $this->dm->get_division_list(NULL, NULL, $search, NULL, NULL, $where, '1');
        $config = $this->pagination($base_url, $total_row, 10, 10);
        $data["links"] = $config["links"];
        $data['dn_names'] = $this->dm->fetch_division_name();
        $data['dn_codes'] = $this->dm->fetch_division_code();
        $data['created_by_name'] = $this->dm->fetch_created_person();
        $data['divs_list'] = $this->dm->get_division_list($config["per_page"], $config['page'], $search, $sortby, $order, $where);
        $page_no = $this->uri->segment('10');
        $start = (int)$page_no + 1;
        $end = (($data['divs_list']) ? count($data['divs_list']) : 0) + (($page_no) ? $page_no : 0);
        $data['result_count'] = "Showing " . $start . " - " . $end . " of " . $total_row . " Results";
        if ($order == NULL || $order == 'NULL') {
            $data['order'] = ($order) ? "DESC" : "ASC";
        } else {
            $data['order'] = ($order == "ASC") ? "DESC" : "ASC";
        }
        $this->load_view('division/manage_division', $data);
    }

    public function add_division(){
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
        $this->form_validation->set_rules('division_name', 'Division Name', 'required|trim|is_unique[mst_divisions.division_name]');
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
            $store_data['division_name'] = $fetch_data['division_name'];
            $store_data['division_code'] = ($fetch_data['division_code']) ? $fetch_data['division_code'] : "" ;
            $store_data['erpdivision_code'] = ($fetch_data['erpdivision_code']) ? $fetch_data['erpdivision_code'] : "" ;
            $store_data['status'] = $fetch_data['status'];
            $store_data['division_type'] = 1;
            $store_data['created_by'] = $checkUser['user_data']->uidnr_admin;
            $store_data['created_on'] = date("Y-m-d h:i:s");
            if (!empty($fetch_data)) {
                $insert_data = $this->dm->insert_data('mst_divisions', $store_data);
                if ($insert_data) {
                    $log_deatils = array(
                        'text'          => "Added Division with Division Name ".$store_data['division_name'],
                        'created_by'    => $store_data['created_by'],
                        'created_on'    => $store_data['created_on'],
                        'record_id'     => $insert_data,
                        'source_module' => 'Division',
                        'action_taken'  => 'add_division'
                    );

                    $log = $this->dm->insert_data('user_log_history',$log_deatils);
                    if($log){
                        $this->session->set_flashdata('success', 'Division Added Successfully');
                        $data = array(
                            'status' => 1,
                            'msg' => 'Division Added Successfully'
                        );
                    }
                    else{
                        $this->session->set_flashdata('error', 'Error in Maintaining Division Add Log ');
                        $data = array(
                            'status' => 0,
                            'msg' => 'Error in Maintaining Division Add Log'
                        );
                    }
                    
                } else {
                    $this->session->set_flashdata('error', 'Error in adding Division');
                    $data = array(
                        'status' => 0,
                        'msg' => 'Error in adding Division'
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

    public function fetch_division_for_edit(){
        $division_id = $this->input->post('division_id');
        $data = $this->dm->fetch_division_for_edit($division_id);
        echo json_encode($data);
    }

    public function update_division(){
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
        $this->form_validation->set_rules('division_name', 'Division Name', 'required|trim|callback_update_divname');
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
            // print_r($fetch_data); die;
            $store_data['division_name'] = $fetch_data['division_name'];
            $store_data['division_code'] = ($fetch_data['division_code']) ? $fetch_data['division_code'] : "" ;
            $store_data['erpdivision_code'] = ($fetch_data['erpdivision_code']) ? $fetch_data['erpdivision_code'] : "" ;
            $store_data['division_type'] = ($fetch_data['division_type']) ? $fetch_data['division_type'] : 1 ;
            $store_data['updated_by'] = $checkUser['user_data']->uidnr_admin;
            $store_data['updated_on'] = date("Y-m-d h:i:s");
            $where['division_id'] = $fetch_data['division_id'];
            if (!empty($fetch_data)) {
                $data_updated = $this->dm->update_data('mst_divisions', $store_data, $where);
                if ($data_updated) {
                    $log_deatils = array(
                        'text'          => "Updated Division with Division Name ".$store_data['division_name'],
                        'created_by'    => $store_data['updated_by'],
                        'created_on'    => $store_data['updated_on'],
                        'record_id'     => $where['division_id'],
                        'source_module' => 'Division',
                        'action_taken'  => 'update_division'
                    );
    
                    $log = $this->dm->insert_data('user_log_history',$log_deatils);
                    if($log){
                        $this->session->set_flashdata('success', 'Division Updated Successfully');
                        $data = array(
                            'status' => 1,
                            'msg' => 'Division Updated Successfully'
                        );
                    }
                    else{
                        $this->session->set_flashdata('error', 'Error in Maintaining Update Division Log');
                        $data = array(
                            'status' => 0,
                            'msg' => 'Error in Maintaining Update Division Log'
                        );
                    }
                } else {
                    $this->session->set_flashdata('error', 'Error in Updating Division');
                    $data = array(
                        'status' => 0,
                        'msg' => 'Error in Updating Division Details'
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

    // added by millan on 10-April-2021
    public function division_status() {
        if (!empty($this->input->post())) {
            $checkUser = $this->session->userdata();
            $data_fetch = $this->dm->fetch_division_for_edit($this->input->post('division_id'));
            $division_name = $data_fetch->division_name;
            $status = $this->dm->update_division_status($this->input->post());
            if ($status) {
                $log_deatils = array(
                    'text'          => "Updated Division Status with Division name ".$division_name,
                    'created_by'    => $checkUser['user_data']->uidnr_admin,
                    'created_on'    => date('Y-m-d h:i:s'),
                    'record_id'     => $this->input->post('division_id'),
                    'source_module' => 'Division',
                    'action_taken'  => 'division_status'
                );
                $log = $this->dm->insert_data('user_log_history',$log_deatils);
                if($log){
                    $this->session->set_flashdata('success', 'Division Status Updated Successfully');
                    $data = array(
                        'status' => 1,
                        'msg' => 'Division Status Updated Successfully'
                    );
                }
                else{
                    $this->session->set_flashdata('error', 'Error in Maintaining Division Status Log');
                    $data = array(
                        'status' => 0,
                        'msg' => 'Error in Maintaining Division Status Log'
                    );
                }
            } else {
                $this->session->set_flashdata('error', 'Error in Updating Division Status');
                $data = array(
                    'status' => 0,
                    'msg' => 'Error in Updating Division Status'
                );
            }
        }
        echo json_encode($data);
    }

    // added by millan on 10-April-2021
    public function get_division_log(){
		$division_id = $this->input->post('division_id');
		$data = $this->dm->get_division_log($division_id);
		echo json_encode($data);
	}

    // added by millan on 10-April-2021
    public function update_divname($field){
        $update_form = $this->input->post();
        $check_fileds = array();
        $check_fileds['LOWER(division_name)'] = strtolower($update_form['division_name']);
        $check_fileds['division_id NOT IN ('.$update_form['division_id'].')'] =  NULL;
        if(!empty($update_form) && !empty($update_form['division_id']) ){
            $check_in = $this->dm->get_row('*', 'mst_divisions' , $check_fileds);
            if($check_in){
                $this->form_validation->set_message('update_divname', 'The {field} field can not be the same. "It Must be Unique!!"');
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
?>