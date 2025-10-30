<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Test_method extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->check_session();
        $this->load->model('Test_method_model', 'tmm');
    }
    public function index()
    {
        $per_page = "10";
        $created_by = $this->uri->segment(3);
        $test_method = $this->uri->segment(4);
        $sortby = $this->uri->segment(5);
        $order = $this->uri->segment(6);
        $page = $this->uri->segment(7);
        $base_url = 'Test_method/index';
        if ($created_by != NULL && $created_by != 'NULL') {
            $data['uidnr_admin'] = base64_decode($created_by);
            $base_url  .= '/' . $created_by;
            $search['uidnr_admin'] = base64_decode($created_by);
        } else {
            $search['uidnr_admin'] = 'NULL';
            $data['uidnr_admin'] = 'NULL';
            $base_url  .= '/NULL';
        }
        if ($test_method != NULL && $test_method != 'NULL') {
            $data['method'] = base64_decode($test_method);
            $base_url  .= '/' . $test_method;
            $search['test_method_name'] = base64_decode($test_method);
        } else {
            $search['test_method_name'] = 'NULL';
            $data['method'] = 'NULL';
            $base_url  .= '/NULL';
        }
        if ($sortby != NULL || $sortby != '') {
            $base_url .= '/' . $sortby;
        } else {
            $base_url .= '/NULL';
            $sortby = 'NULL';
        }
        if ($order != NULL || $order != '') {
            $base_url .= '/' . $order;
        } else {
            $base_url .= '/NULL';
            $order = 'NULL';
        }
        $total_count = $this->tmm->get_testmethod($per_page, $page, $search, NULL, NULL, $count = true);
        $data['pagination'] = $this->pagination($base_url, $total_count, $per_page, 7);
        $data['testmethod'] = $this->tmm->get_testmethod($per_page, $page, $search, $sortby, $order);
        $start = ($total_count > 0) ? ($page + 1) : 0;
        $end = (($data['testmethod']) ? count($data['testmethod']) : 0) + (($page) ? $page : 0);
        $data['result_count'] = "Showing " . $start . " - " . $end . " of " . $total_count . " Results";
        $data['search'] = $search;
        $data['start'] = $start;
        $data['admin_profile'] = $this->tmm->fetch_created_person();
        $data['created_by_name'] = $this->tmm->fetch_created_person();
        if ($order == NULL || $order == 'NULL') {
            $data['order'] = ($order) ? "DESC" : "ASC";
        } else {
            $data['order'] = ($order == "ASC") ? "DESC" : "ASC";
        }
        $this->load_view('test_method/test_method_list', $data);
    }

     public function add_testmethod()
    {
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
        $this->form_validation->set_rules('test_method', 'Test Method', 'required|is_unique[mst_test_methods.test_method_name]');
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
            $store_data['test_method_name'] = $fetch_data['test_method'];
            $store_data['created_by'] = $checkUser['user_data']->uidnr_admin;
            $store_data['created_on'] = date("Y-m-d h:i:s");
            if (!empty($fetch_data)) {
                $save = $this->tmm->insert_data('mst_test_methods', $store_data);
                if ($save) {
                    $details_log = array(
                        'text'          => "Added Test_method with Test_method ".$store_data['test_method_name'],
                        'created_by'    => $store_data['created_by'],
                        'created_on'    => $store_data['created_on'],
                        'record_id'     => $save,
                        'source_module' => 'Test_method',
                        'action_taken'  =>'Add Test Method'
                    );

                    $log = $this->tmm->insert_data('user_log_history',$details_log);
                    if ($log) {
                        $this->session->set_flashdata('success', 'Testmethod added Successfully');
                        $data = array(
                            'status' => 1,
                            'msg' => 'Testmethod Added Successfully'
                        );
                    } else {
                        $this->session->set_flashdata('error', 'Error in Maintaining Testmethod Add Log ');
                        $data = array(
                            'status' => 0,
                            'msg' => 'Error in Maintaining testmethod Add Log'
                        );
                    }
                } else {
                    $this->session->set_flashdata('error', 'Error in adding testmethod');
                    $data = array(
                        'status' => 0,
                        'msg' => 'Error in adding testmethod'
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

    public function update_testmethod(){
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
        $this->form_validation->set_rules('test_method', 'Test Method', 'required|callback_check_method');
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
            $store_data['test_method_name'] = $fetch_data['test_method'];
            $store_data['updated_by'] = $checkUser['user_data']->uidnr_admin;
            $store_data['updated_on'] = date("Y-m-d h:i:s");
            $where['test_method_id'] = $fetch_data['test_method_id'];
            if (!empty($fetch_data['test_method_id'])) {
                $update = $this->tmm->update_data('mst_test_methods', $store_data, $where);
              
                if ($update) {
                    $details_log = array(
                        'text'          => "Updated Test_method with Test Method ".$store_data['test_method_name'],
                        'created_by'    => $store_data['updated_by'],
                        'created_on'    => $store_data['updated_on'],
                        'record_id'     => $where['test_method_id'],
                        'source_module' => 'Test_method',
                        'action_taken'  => 'update_Testmethod'
                    );
    
                    $log = $this->tmm->insert_data('user_log_history',$details_log);
                    if($log){
                        $this->session->set_flashdata('success', 'Testmethod Details Updated Successfully');
                        $data = array(
                            'status' => 1,
                            'msg' => 'Testmethod Details Updated Successfully'
                        );
                    }
                    else{
                        $this->session->set_flashdata('error', 'Error in Maintaining Update testmethod Log');
                        $data = array(
                            'status' => 0,
                            'msg' => 'Error in Maintaining Update testmethod Log'
                        );
                    }
                } else {
                    $this->session->set_flashdata('error', 'Error in Updating Testmethod Details');
                    $data = array(
                        'status' => 0,
                        'msg' => 'Error in Updating Testmethod Details'
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

    public function fetch_testmethod_for_edit()
    {
        if (!empty($this->input->post())) {
            $fetch_data = $this->tmm->fetch_testmethod_for_edit($this->input->post());
            if ($fetch_data) {
                if (!empty($fetch_data->test_method_id)) {
                    (strpos($fetch_data->test_method_id, ",")) ? explode(',', $fetch_data->test_method_id) : $fetch_data->test_method_id;
                }
                echo json_encode($fetch_data);
            } else {
                echo false;
            }
        }
    }


    public function get_log_data()
    {
        $test_method_id = $this->input->post('test_method_id');
        $data = $this->tmm->get_accredit_log($test_method_id);
        echo json_encode($data);
    }

    public function check_method($field){
        $update_form = $this->input->post();
        $check_fileds = array();
        $check_fileds['LOWER(test_method_name)'] = strtolower(trim($update_form['test_method']));
        $check_fileds['test_method_id NOT IN ('.$update_form['test_method_id'].')'] =  NULL;
        if(!empty($update_form) && !empty($update_form['test_method_id']) ){
            $check_in = $this->tmm->get_row('*', 'mst_test_methods' , $check_fileds);
            // echo $this->db->last_query(); die;s
            if($check_in){
                $this->form_validation->set_message('check_method', 'The {field} field can not be the same. "It Must be Unique!!"');
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