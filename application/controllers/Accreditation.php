<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Accreditation extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Accreditation_model', 'am');
        $this->load->helper('download');
        $this->check_session();
        $checkUser = $this->session->userdata('user_data');
        $this->user = $checkUser->uidnr_admin;
    }

    public function index()
    {
        $per_page = "10";
        $country_name = $this->uri->segment('3');
        $branch_name = $this->uri->segment('4');
        $certificate_no = $this->uri->segment('5');
        $sortby = $this->uri->segment('6');
        $order = $this->uri->segment('7');
        $page = $this->uri->segment('8');
        $base_url = 'Accreditation/index';

        if ($country_name != NULL && $country_name != 'NULL') {
            $data['name'] = base64_decode($country_name);
            $base_url  .= '/' . $country_name;
            $search['country_name'] = base64_decode($country_name);
        } else {
            $search['country_name'] = 'NULL';
            $data['name'] = 'NULL';
            $base_url  .= '/NULL';
        }

        if ($branch_name != NULL  && $branch_name != 'NULL') {
            $data['name'] = base64_decode($branch_name);
            $base_url  .= '/' . $branch_name;
            $search['branch_name'] = base64_decode($branch_name);
        } else {
            $search['branch_name'] = 'NULL';
            $data['name'] = 'NULL';
            $base_url  .= '/NULL';
        }
        if ($certificate_no != NULL && $certificate_no != 'NULL') {
            $data['number'] = base64_decode($certificate_no);
            $base_url  .= '/' . $certificate_no;
            $search['certificate_no'] = base64_decode($certificate_no);
        } else {
            $search['certificate_no'] = 'NULL';
            $data['number'] = 'NULL';
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
        $total_count = $this->am->get_accreditation($per_page, $page, $search, NULL, NULL, $count = true);
        $data['pagination'] = $this->pagination($base_url, $total_count, $per_page, 8);
        $data['accreditation'] = $this->am->get_accreditation($per_page, $page, $search, $sortby, $order);
        // echo "<pre>";echo $this->db->last_query();die;
        $start = ($total_count > 0) ? ($page + 1) : 0;
        $end = (($data['accreditation']) ? count($data['accreditation']) : 0) + (($page) ? $page : 0);
        $data['result_count'] = "Showing " . $start . " - " . $end . " of " . $total_count . " Results";
        $data['search'] = $search;
        $data['start'] = $start;
        $data['admin_profile'] = $this->am->fetch_created_person();
        $data['countries'] = $this->am->fetch_country();
        $data['brn_names'] = $this->am->fetch_branch_name();
        if ($order == NULL || $order == 'NULL') {
            $data['order'] = ($order) ? "DESC" : "ASC";
        } else {
            $data['order'] = ($order == "ASC") ? "DESC" : "ASC";
        }

        $this->load_view('accreditation/accreditation_list', $data);
    }

    public function add_accreditation()
    {
        $data['country_name'] = $this->am->fetch_country('mst_country');
        // echo "<pre>"; print_r( $data['country']); die;
        $data['branch_name'] = $this->am->fetch_branch_name();
        $this->form_validation->set_rules('title', 'Title', 'required');
        $this->form_validation->set_rules('country_id', 'Country', 'required');
        $this->form_validation->set_rules('branch_id', 'Branch', 'required');
        $this->form_validation->set_rules('validity', 'Validity', 'required');
        $this->form_validation->set_rules('acc_standard', 'Accreditation Standard', 'required');
        $this->form_validation->set_rules('certificate_no', 'Certificate Number', 'required');

        if ($this->form_validation->run() == true) {

            if ($_FILES['upload_filename']['name'] != "") {
                $file = $this->multiple_upload_image($_FILES['upload_filename']);
                if ($file) {
                    $data['file_path1'] = $file['aws_path'];
                }
            }

            if ($_FILES['scope_filename']['name'] != "") {
                $file = $this->multiple_upload_image($_FILES['scope_filename']);
                if ($file) {
                    $data['file_path2'] = $file['aws_path'];
                }
            }

            $upload_filename = empty($data['file_path1']) ? NULL : $data['file_path1'];
            $scope_filename = empty($data['file_path2']) ? NULL : $data['file_path2'];

            $data_array = array(
                'title'           => $this->input->post('title'),
                'country_id'      => $this->input->post('country_id'),
                'branch_id'       => $this->input->post('branch_id'),
                'validity'        => $this->input->post('validity'),
                'acc_standard'    => $this->input->post('acc_standard'),
                'certificate_no'  => $this->input->post('certificate_no'),
                'upload_filename' => $upload_filename,
                'scope_filename'  => $scope_filename,
                'uploaded_by'     => $this->session->userdata('user_data')->uidnr_admin,
                'uploaded_on'     => date('Y-m-d H:i:s')
            );
            $save = $this->am->insert_data('cps_accreditation', $data_array);
            if ($save) {
                $accreditation_log = array(
                    'source_module'   => 'Accreditation',
                    'action_taken'        => 'Add_accreditation',
                    'created_by'    => $this->admin_id(),
                    'record_id'  => $save,
                    'created_on' => date('Y-m-d H:i:s'),
                    'text'  => 'Added accreditation'
                );

                $this->db->insert('user_log_history', $accreditation_log);
                //   echo $this->db->last_query(); die;
                $this->session->set_flashdata('success', 'Accreditation Added Successfully!');
                return redirect('Accreditation/index');
            } else {
                $this->session->set_flashdata('false', 'Something went wrong!.');
            }
        } else {

            $this->load_view('accreditation/add_accreditation', $data);
        }
    }

    public function edit_accreditation()
    {
        $acc_id = base64_decode($this->input->get('acc_id'));
        $data['accreditation'] = $this->am->get_data_by_id('cps_accreditation', $acc_id, 'acc_id');
        //  echo "<pre>"; print_r( $data['accreditation']);
        $data['country_name'] = $this->am->fetch_country();
        $data['branch_name'] = $this->am->fetch_branch_name();
        $this->form_validation->set_rules('title', 'Title', 'required');
        $this->form_validation->set_rules('country_id', 'Country', 'required');
        $this->form_validation->set_rules('branch_id', 'Branch', 'required');
        $this->form_validation->set_rules('validity', 'validity', 'required');
        $this->form_validation->set_rules('acc_standard', 'Accreditation Standard', 'required');
        $this->form_validation->set_rules('certificate_no', 'Certificate Number', 'required');
        // $this->form_validation->set_rules('upload_filename', 'Upload filename', 'required');
        // $this->form_validation->set_rules('scope_filename', 'Scope filename', 'required');

        if ($this->form_validation->run() == true) {
            if ($_FILES['upload_filename']['name'] != "") {
                $file = $this->multiple_upload_image($_FILES['upload_filename']);
                if ($file) {
                    $data['file_path1'] = $file['aws_path'];
                }
            }

            if ($_FILES['scope_filename']['name'] != "") {
                $file = $this->multiple_upload_image($_FILES['scope_filename']);
                if ($file) {
                    $data['file_path2'] = $file['aws_path'];
                }
            }

            $upload_filename = empty($data['file_path1']) ? NULL : $data['file_path1'];
            $scope_filename = empty($data['file_path2']) ? NULL : $data['file_path2'];

            $data_array = array(
                'title'                 => $this->input->post('title'),
                'country_id'                 => $this->input->post('country_id'),
                'branch_id'                 => $this->input->post('branch_id'),
                'validity'                 => $this->input->post('validity'),
                'acc_standard'                 => $this->input->post('acc_standard'),
                'certificate_no'           => $this->input->post('certificate_no'),
                'upload_filename'               => $upload_filename,
                'scope_filename'             => $scope_filename,
                'uploaded_by'                  => $this->session->userdata('user_data')->uidnr_admin,
                'uploaded_on'             => date('Y-m-d H:i:s')

            );

            $update = $this->am->update_data('cps_accreditation', $data_array,  ['acc_id' => $acc_id]);
            if ($update) {
                $accreditation_log = array(
                    'source_module'   => 'Accreditation',
                    'action_taken'        => 'edit_accreditation',
                    'created_by'    => $this->admin_id(),
                    'record_id'  => $acc_id,
                    'created_on' => date('Y-m-d H:i:s'),
                    'text'  => 'Updated accreditation'
                );
                $this->db->insert('user_log_history', $accreditation_log);
                // echo $this->db->last_query(); die;
                $this->session->set_flashdata('success', 'Accreditation updated Successfully!');
                return redirect('Accreditation/index');
            } else {
                $this->session->set_flashdata('false', 'Something went wrong!.');
            }
        } else {

            $this->load_view('accreditation/add_accreditation', $data);
        }
    }



    public function download_file_accreditation($path)
    {

        $path = base64_decode($path);
        $this->load->helper('download');
        $pdf_path = file_get_contents($path);
        $pdf_name = basename($path);
        force_download($pdf_name, $pdf_path);
    }

    public function get_log_data()
    {
        $acc_id = $this->input->post('acc_id');
        $data = $this->am->get_accredit_log($acc_id);

        echo json_encode($data);
    }
    public function user_log_update($acc_id, $text, $action)
    {
        $data = array();
        $data['source_module'] = 'Accredidation';
        $data['record_id'] = '$acc_id';
        $data['uploaded_on'] = date("Y-m-d h:i:s");
        $data['uploaded_by'] = $this->user;
        $data['action_taken'] = $action;
        $data['text'] = $text;

        $result = $this->am->insert_data('user_log_history', $data);
        // echo "<pre>"; print_r($result);die;
        if ($result) {
            return true;
        } else {
            return false;
        }
    }
}
