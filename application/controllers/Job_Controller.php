<?php
class Job_Controller extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->check_session();
        $this->load->model('Job_model', 'jm');
    }


    public function index()
    {
        $where = NULL;
        $search = NULL;
        $sortby = NULL;
        $order = NULL;

        $base_url = 'Job_Controller/index';
        if ($this->uri->segment('3') != NULL && $this->uri->segment('3') != 'NULL') {
            $depart_id = $this->uri->segment('3');
            $data['depart_id'] =  $depart_id;
            $base_url .= '/' . $depart_id;
            $where['depart_id'] = base64_decode($depart_id);
        } else {
            $base_url .= '/NULL';
            $data['depart_id'] = 'NULL';
            $where['depart_id'] = 'NULL';
        }

        if ($this->uri->segment('4') != NULL && $this->uri->segment('4') != 'NULL') {
            $job_status = $this->uri->segment('4');
            $data['job_status'] =  $job_status;
            $base_url .= '/' . $job_status;
            $where['job_status'] = base64_decode($job_status);
        } else {
            $base_url .= '/NULL';
            $data['job_status'] = 'NULL';
            $where['job_status'] = 'NULL';
        }

        if ($this->uri->segment('5') != NULL && $this->uri->segment('5') != 'NULL') {
            $job_title = $this->uri->segment('5');
            $data['job_title'] =  $job_title;
            $base_url .= '/' . $job_title;
            $where['job_title'] = base64_decode($job_title);
        } else {
            $base_url .= '/NULL';
            $data['job_title'] = 'NULL';
            $where['job_title'] = 'NULL';
        }
        if ($this->uri->segment('6') != NULL && $this->uri->segment('6') != 'NULL') {
            $sortby = $this->uri->segment('6');
            $base_url .= '/' . $sortby;
            $data['sortby'] = $sortby;
        } else {
            $base_url .= '/NULL';
            $data['sortby'] = NULL;
        }

        if ($this->uri->segment('7') != NULL && $this->uri->segment('7') != 'NULL') {
            $order = $this->uri->segment('7');
            $base_url .= '/' . $order;
            $data['order'] = $order;
        } else {
            $base_url .= '/NULL';
            $data['order'] = 'NULL';
        }

        $total_row = $this->jm->get_services_list(NULL, NULL, $where, $sortby, $order, true);
        $config = $this->pagination($base_url, $total_row, 10, 8);

        $data["links"] = $config["links"];
        $data['dept_name'] = $this->jm->get_departments(NULL);

        $data['services'] = $this->jm->get_services_list($config["per_page"], $config['page'], $where, $sortby, $order);
        $page_no = $this->uri->segment('8');
        if ($total_row > 0) {
            $start = (int)$page_no + 1;
        } else {
            $start = 0;
        }
        $end = (($data['services']) ? count($data['services']) : 0) + (($page_no) ? $page_no : 0);
        $data['result_count'] = "Showing " . $start . " - " . $end . " of " . $total_row . " Results";
        if ($order == NULL || $order == 'NULL') {
            $data['order'] = ($order) ? "DESC" : "ASC";
        } else {
            $data['order'] = ($order == "ASC") ? "DESC" : "ASC";
        }

        $this->load_view('job_discription/job_list', $data);
    }

    public function load_add_form()
    {
        $result['data'] = '';
        $result['dept_name'] = $this->jm->get_departments();
        $this->load_view('job_discription/add_discription', $result);
    }

    public function savedata()
    {   
        // define your validation rules here
        $this->form_validation->set_rules('job_dis', 'Job Dis', 'required');
        $this->form_validation->set_rules('job_title', 'Job Title', 'required');
        $this->form_validation->set_rules('job_posted', 'Job Posted', 'required');
        $this->form_validation->set_rules('job_status', 'Job Status', 'required');
        $this->form_validation->set_rules('job_depart', 'Job Depart', 'required');
        // this line will check validation is success or not
        if ($this->form_validation->run()) {
            // if validation is success save data in database and redirect to list
            $store_data = array(
                'depart_id' => $this->input->post('job_depart'),
                'job_discription' => htmlentities($this->input->post('job_dis')),
                'job_title' => $this->input->post('job_title'),
                'job_posted' => $this->input->post('job_posted'),
                'job_status' => $this->input->post('job_status'),
                'created_by' => $this->admin_id()
            );
            $data_added = $this->jm->insert_data('job_discription', $store_data);
            if ($data_added) {
                $log_deatils = array(
                    'text'          => "Added Job with Job Title ".$store_data['job_title'],
                    'created_by'    => $store_data['created_by'],
                    'created_on'    => $store_data['job_posted'],
                    'record_id'     => $data_added,
                    'source_module' => 'Job_Controller',
                    'action_taken'  => 'savedata'
                );
                $log = $this->jm->insert_data('user_log_history',$log_deatils);
                if($log){
                    $this->session->set_flashdata('success', 'Job Added Successfully');
                    return redirect('Job_Controller/index');
                }
                else{
                    $this->session->set_flashdata('error', 'Error in Maintaining Job Add Log ');
                    return redirect('Job_Controller/index');
                }   
            }else {
                $this->session->set_flashdata('error', 'Error in adding Job');
                return redirect('Job_Controller/index');
            }
        } else {
            // reload view form 
            $result['dept_name'] = $this->jm->get_departments(NULL);
            $this->load_view('job_discription/add_discription', $result);
            $this->session->set_flashdata('error', 'Something Went Wrong !!!');
        }
    }

    public function edit_job($id)
    {
        $result['data'] = $this->jm->displayrecordsById($id);
        $result['dept_name'] = $this->jm->get_departments(NULL);

        if ($this->input->post()) {
            $this->form_validation->set_rules('job_dis', 'Job Dis', 'required');
            $this->form_validation->set_rules('job_title', 'Job Title', 'required');
            $this->form_validation->set_rules('job_posted', 'Job Posted', 'required');
            $this->form_validation->set_rules('job_status', 'Job Status', 'required');
            $this->form_validation->set_rules('job_depart', 'Job Depart', 'required');

            if ($this->form_validation->run()) {
                $test_standards = $this->input->post('test_standards');
                $data_array = array(
                    'job_discription'   => htmlentities($this->input->post('job_dis')),
                    'job_title'       => $this->input->post('job_title'),
                    'job_posted'     => $this->input->post('job_posted'),
                    'job_status'    => $this->input->post('job_status'),
                    'depart_id'    => $this->input->post('job_depart'),
                    'created_by' => $this->admin_id()
                );
                $this->db->where('id', $id);
                $save = $this->db->update('job_discription', $data_array);

                if ($save) {
                    $log_deatils = array(
                        'text'          => "Updated services",
                        'created_by'    => $this->admin_id(),
                        'created_on'    => date('Y-m-d H:i:s'),
                        'record_id'     => $id,
                        'source_module' => 'Job_Controller',
                        'action_taken'  => 'edit_job'
                    );

                    $log = $this->jm->insert_data('user_log_history', $log_deatils);
                    $this->session->set_flashdata('success', 'Data updated successfully.');
                    return redirect('Job_Controller/index');
                } else {
                    $this->session->set_flashdata('success', 'Something went wrong!.');
                }
            }
        }
        $this->load_view('job_discription/add_discription', $result);
    }


    public function deletedata($id)
    {
        $this->jm->deleterecords($id);
        redirect(base_url('Job_Controller'));
    }

    public function user_status_changed()
    {
        $id = $this->input->post('id');
        $status = $this->input->post('status');

        if ($status == '1') {
            $user_status = '0';
        } else {
            $user_status = '1';
        }

        $data = array('job_status' => $user_status);

        $this->db->where('id', $id);
        $this->db->update('job_discription', $data); //Update status here


        $this->session->set_flashdata('msg', "User status has been changed successfully.");
        $this->session->set_flashdata('msg_class', 'alert-success');

        redirect(base_url('Job_Controller'));
    }


    public function updatedata($id)
    {
        $id = $this->input->get('id');
        print_r($this->jm->displayrecordsById($id));

        $result['data'] = $this->jm->displayrecordsById($id);
        $this->load->view('job_discription/add_discription', $result);

        if ($this->input->post('submit')) {

            $job_dis = $this->input->post('job_dis');
            $job_title = $this->input->post('job_title');
            $job_posted = $this->input->post('job_posted');
            $job_status = $this->input->post('job_status');
            $job_depart = $this->input->post('job_depart');
            $this->jm->updaterecords($job_dis, $job_title, $job_posted, $job_status, $job_depart, $id);

            redirect(base_url('Job_Controller'));
        }
    }
}
