<?php
defined('BASEPATH') or exit('No direct script access allowed');

class State extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('State_model', 'SM');
        $this->check_session();
        $checkUser = $this->session->userdata('user_data');
        $this->user = $checkUser->uidnr_admin;
    }

    public function index()
    {
        $where = NULL;
        $search = NULL;
        $sortby = NULL;
        $order = NULL;

        $base_url = 'State/index';

        if ($this->uri->segment('3') != NULL && $this->uri->segment('3') != 'NULL') {
            $id_country = $this->uri->segment('3');
            $data['id_country'] =  base64_decode($id_country);
            $base_url .= '/' . $id_country;
            $where['msc.country_id'] = base64_decode($id_country);
        } else {
            $base_url .= '/NULL';
            $data['id_country'] = NULL;
        }

        if ($this->uri->segment('4') != NULL && $this->uri->segment('4') != 'NULL') {
            $id_state = $this->uri->segment('4');
            $data['id_state'] =  base64_decode($id_state);
            $base_url .= '/' . $id_state;
            $where['msp.province_id'] = base64_decode($id_state);
        } else {
            $base_url .= '/NULL';
            $data['id_state'] = NULL;
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
            $where['msp.status'] = base64_decode($id_status);
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

        $total_row = $this->SM->get_state_list(NULL, NULL, $search, NULL, NULL, $where, '1');
        $config = $this->pagination($base_url, $total_row, 10, 10);
        $data["links"] = $config["links"];
        $data['countries'] = $this->SM->fetch_country_name();
        $data['states'] = $this->SM->fetch_state_name();
        $data['created_by_name'] = $this->SM->fetch_created_person();
        $data['state_list'] = $this->SM->get_state_list($config["per_page"], $config['page'], $search, $sortby, $order, $where);
        $page_no = $this->uri->segment('10');
        $start = (int)$page_no + 1;
        $end = (($data['state_list']) ? count($data['state_list']) : 0) + (($page_no) ? $page_no : 0);
        $data['result_count'] = "Showing " . $start . " - " . $end . " of " . $total_row . " Results";
        if ($order == NULL || $order == 'NULL') {
            $data['order'] = ($order) ? "DESC" : "ASC";
        } else {
            $data['order'] = ($order == "ASC") ? "DESC" : "ASC";
        }
        $this->load_view('state/state_listing', $data);
    }

    public function add_state()
    {
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
        $this->form_validation->set_rules('mst_provinces_country_id', 'Country Name', 'required');
        $this->form_validation->set_rules('province_name', 'State Name', 'required|is_unique[mst_provinces.province_name]');
        $this->form_validation->set_rules('state_code', 'State Code', 'required');
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
            $store_data['mst_provinces_country_id'] = $fetch_data['mst_provinces_country_id'];
            $store_data['province_name'] = $fetch_data['province_name'];
            $store_data['state_code'] = $fetch_data['state_code'];
            $store_data['status'] = $fetch_data['status'];
            $store_data['created_by'] = $checkUser['user_data']->uidnr_admin;
            $store_data['created_on'] = date("Y-m-d h:i:s");
            if (!empty($fetch_data)) {
                $insert_data = $this->SM->insert_data('mst_provinces', $store_data);
                if ($insert_data) {
                    $log_deatils = array(
                        'text'          => "Added State with State name " . $store_data['province_name'],
                        'created_by'    => $store_data['created_by'],
                        'created_on'    => $store_data['created_on'],
                        'record_id'     => $insert_data,
                        'source_module' => 'State',
                        'action_taken'  => 'add_state'
                    );

                    $log = $this->SM->insert_data('user_log_history', $log_deatils);
                    if ($log) {
                        $this->session->set_flashdata('success', 'State added Successfully');
                        $data = array(
                            'status' => 1,
                            'msg' => 'State Added Successfully'
                        );
                    } else {
                        $this->session->set_flashdata('error', 'State added Successfully');
                        $data = array(
                            'status' => 0,
                            'msg' => 'Error in Maintaining State Add Log'
                        );
                    }
                } else {
                    $this->session->set_flashdata('error', 'Error in adding State');
                    $data = array(
                        'status' => 0,
                        'msg' => 'Error in Adding State'
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

    public function edit_state()
    {
        $province_id = $this->input->post('province_id');
        $data = $this->SM->get_state_data($province_id);
        echo json_encode($data);
    }

    public function update_state()
    {
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
        $this->form_validation->set_rules('mst_provinces_country_id', 'Country Name', 'required');
        $this->form_validation->set_rules('province_name', 'State Name', 'required|callback_update_sname');
        $this->form_validation->set_rules('state_code', 'State Code', 'required');
        if ($this->form_validation->run() == false) {
            $data = array(
                'errors' => $this->form_validation->error_array(),
                'status' => 0,
                'msg' => 'Please Fill Al Required Fields'
            );
        } else {
            $store_data = array();
            $checkUser = $this->session->userdata();
            $fetch_data = $this->input->post();
            $store_data['mst_provinces_country_id'] = $fetch_data['mst_provinces_country_id'];
            $store_data['province_name'] = $fetch_data['province_name'];
            $store_data['state_code'] = $fetch_data['state_code'];
            $store_data['updated_by'] = $checkUser['user_data']->uidnr_admin;
            $store_data['updated_on'] = date("Y-m-d h:i:s");
            $where['province_id'] = $fetch_data['province_id'];
            if (!empty($fetch_data)) {
                $data_updated = $this->SM->update_data('mst_provinces', $store_data, $where);
                if ($data_updated) {
                    $log_deatils = array(
                        'text'          => "Updated State with State Name " . $store_data['province_name'],
                        'created_by'    => $store_data['updated_by'],
                        'created_on'    => $store_data['updated_on'],
                        'record_id'     => $where['province_id'],
                        'source_module' => 'State',
                        'action_taken'  => 'update_state'
                    );

                    $log = $this->SM->insert_data('user_log_history', $log_deatils);
                    if ($log) {
                        $this->session->set_flashdata('success', 'State Updated Successfully');
                        $data = array(
                            'status' => 1,
                            'msg' => 'State Updated Successfully'
                        );
                    } else {
                        $this->session->set_flashdata('error', 'Error in Maintaining State Update Log');
                        $data = array(
                            'status' => 0,
                            'msg' => 'Error in Maintaining State Update Log'
                        );
                    }
                } else {
                    $this->session->set_flashdata('error', 'Error in Updating State');
                    $data = array(
                        'status' => 0,
                        'msg' => 'Error in Updating State'
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


    public function state_status()
    {
        if (!empty($this->input->post())) {
            $checkUser = $this->session->userdata();
            $data_fetch = $this->SM->get_state_data($this->input->post('province_id'));
            $province_name = $data_fetch->province_name;
            $status = $this->SM->update_state_status($this->input->post());
            if ($status) {
                $log_deatils = array(
                    'text'          => "Updated State Status with State name " . $province_name,
                    'created_by'    => $checkUser['user_data']->uidnr_admin,
                    'created_on'    => date('Y-m-d h:i:s'),
                    'record_id'     => $this->input->post('province_id'),
                    'source_module' => 'State',
                    'action_taken'  => 'state_status'
                );
                $log = $this->SM->insert_data('user_log_history', $log_deatils);
                if ($log) {
                    $this->session->set_flashdata('success', 'State Status Updated Successfully');
                    $data = array(
                        'status' => 1,
                        'msg' => 'State Status Updated Successfully'
                    );
                } else {
                    $this->session->set_flashdata('error', 'Error in Maintaining State Status Log');
                    $data = array(
                        'status' => 0,
                        'msg' => 'Error in Maintaining State Status Log'
                    );
                }
            } else {
                $this->session->set_flashdata('error', 'Error in Updating State Status');
                $data = array(
                    'status' => 0,
                    'msg' => 'Error in Updating State Status'
                );
            }
        }
        echo json_encode($data);
    }

    // added by millan on 09-April-2021
    public function update_sname($field)
    {
        $update_form = $this->input->post();
        $check_fileds = array();
        $check_fileds['LOWER(province_name)'] = strtolower($update_form['province_name']);
        $check_fileds['province_id NOT IN (' . $update_form['province_id'] . ')'] =  NULL;
        if (!empty($update_form) && !empty($update_form['province_id'])) {
            $check_in = $this->SM->get_row('*', 'mst_provinces', $check_fileds);
            if ($check_in) {
                $this->form_validation->set_message('update_sname', 'The {field} field can not be the same. "It Must be Unique!!"');
                return FALSE;
            } else {
                return TRUE;
            }
        } else {
            return false;
        }
    }

    // added by millan on 09-April-2021
    public function get_state_log()
    {
        $province_id = $this->input->post('province_id');
        $data = $this->SM->get_state_log($province_id);
        echo json_encode($data);
    }
}
