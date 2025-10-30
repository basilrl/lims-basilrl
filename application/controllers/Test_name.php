<?php
defined('BASEPATH') or exit('No direct access allowed');

class Test_name extends MY_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->check_session();
        $this->load->model('Test_name_model', 'tnm');
    }

    public function index()
    {
        $per_page = "10";
        $created_by = $this->uri->segment(3);
        $test_name = $this->uri->segment(4);
        $sortby = $this->uri->segment(5);
        $order = $this->uri->segment(6);
        $page = $this->uri->segment(7);
        $base_url = 'Test_name/index';

        if ($created_by != NULL && $created_by != 'NULL') {
            $data['uidnr_admin'] = base64_decode($created_by);
            $base_url  .= '/' . $created_by;
            $search['uidnr_admin'] = base64_decode($created_by);
        } else {
            $search['uidnr_admin'] = 'NULL';
            $data['uidnr_admin'] = 'NULL';
            $base_url  .= '/NULL';
        }
        if ($test_name != NULL && $test_name != 'NULL') {
            $data['name'] = base64_decode($test_name);
            $base_url  .= '/' . $test_name;
            $search['test_name'] = base64_decode($test_name);
        } else {
            $search['test_name'] = 'NULL';
            $data['name'] = 'NULL';
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
        $total_count = $this->tnm->get_testname($per_page, $page, $search, NULL, NULL, $count = true);
        $data['pagination'] = $this->pagination($base_url, $total_count, $per_page, 7);
        $data['testname'] = $this->tnm->get_testname($per_page, $page, $search, $sortby, $order);
        // echo "<pre>";echo $this->db->last_query();die;
        $start = ($total_count > 0) ? ($page + 1) : 0;
        $end = (($data['testname']) ? count($data['testname']) : 0) + (($page) ? $page : 0);
        $data['result_count'] = "Showing " . $start . " - " . $end . " of " . $total_count . " Results";
        $data['search'] = $search;
        $data['start'] = $start;
        $data['admin_profile'] = $this->tnm->fetch_created_person();
        $data['created_by_name'] = $this->tnm->fetch_created_person();
        // echo "<pre>"; print_r( $data['created_by_name']); die;
        if ($order == NULL || $order == 'NULL') {
            $data['order'] = ($order) ? "DESC" : "ASC";
        } else {
            $data['order'] = ($order == "ASC") ? "DESC" : "ASC";
        }

        $this->load_view('test_name/test_name_list', $data);
    }

    public function add_testname()
    {
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
        $this->form_validation->set_rules('test_name', 'Test Name', 'required');

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
            $store_data['test_name'] = $fetch_data['test_name'];
            $store_data['created_by'] = $checkUser['user_data']->uidnr_admin;
            $store_data['created_on'] = date("Y-m-d h:i:s");
            if (!empty($fetch_data)) {
                $save = $this->tnm->insert_data('cps_test_list', $store_data);
                if ($save) {
                    $details_log = array(
                        'text'          => "Added Test_name with Test_name ".$store_data['test_name'],
                        'created_by'    => $store_data['created_by'],
                        'created_on'    => $store_data['created_on'],
                        'record_id'     => $save,
                        'source_module' => 'Test_Name',
                        'action_taken'  =>'Add_Test Name'
                    );

                    $log = $this->tnm->insert_data('user_log_history',$details_log);
                    if ($log) {
                        $this->session->set_flashdata('success', 'Testname added Successfully');
                        $data = array(
                            'status' => 1,
                            'msg' => 'Testname Added Successfully'
                        );
                    } else {
                        $this->session->set_flashdata('error', 'Error in Maintaining Testname Add Log ');
                        $data = array(
                            'status' => 0,
                            'msg' => 'Error in Maintaining testname Add Log'
                        );
                    }
                } else {
                    $this->session->set_flashdata('error', 'Error in adding testname');
                    $data = array(
                        'status' => 0,
                        'msg' => 'Error in adding testname'
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

    public function fetch_testname_for_edit()
    {
        if (!empty($this->input->post())) {
            $fetch_data = $this->tnm->fetch_testname_for_edit($this->input->post());
            if ($fetch_data) {
                if (!empty($fetch_data->test_id)) {
                    (strpos($fetch_data->test_id, ",")) ? explode(',', $fetch_data->test_id) : $fetch_data->test_id;
                }
                echo json_encode($fetch_data);
            } else {
                echo false;
            }
        }
    }
    public function update_testname(){
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
        $this->form_validation->set_rules('test_name', 'Test Name', 'required');
       
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
            $store_data['test_name'] = $fetch_data['test_name'];
            $store_data['updated_by'] = $checkUser['user_data']->uidnr_admin;
            $store_data['updated_on'] = date("Y-m-d h:i:s");
            $where['test_id'] = $fetch_data['test_id'];
            if (!empty($fetch_data['test_id'])) {
                $update = $this->tnm->update_data('cps_test_list', $store_data, $where);
              
                if ($update) {
                    $details_log = array(
                        'text'          => "Updated Test_name with Test Name ".$store_data['test_name'],
                        'created_by'    => $store_data['updated_by'],
                        'created_on'    => $store_data['updated_on'],
                        'record_id'     => $where['test_id'],
                        'source_module' => 'Test_name',
                        'action_taken'  => 'update_Testname'
                    );
    
                    $log = $this->tnm->insert_data('user_log_history',$details_log);
                    if($log){
                        $this->session->set_flashdata('success', 'Testname Details Updated Successfully');
                        $data = array(
                            'status' => 1,
                            'msg' => 'Testname Details Updated Successfully'
                        );
                    }
                    else{
                        $this->session->set_flashdata('error', 'Error in Maintaining Update testname Log');
                        $data = array(
                            'status' => 0,
                            'msg' => 'Error in Maintaining Update testname Log'
                        );
                    }
                } else {
                    $this->session->set_flashdata('error', 'Error in Updating Testname Details');
                    $data = array(
                        'status' => 0,
                        'msg' => 'Error in Updating Testname Details'
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

    public function get_log_data()
    {
        $test_id = $this->input->post('test_id');
        $data = $this->tnm->get_accredit_log($test_id);

        echo json_encode($data);
    }

   
}
