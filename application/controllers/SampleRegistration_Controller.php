<?php
defined('BASEPATH') or exit('No direct script access allowed');

class SampleRegistration_Controller extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->check_session();
        $this->load->model('SampleRegistration', 'sr');
        $this->load->model('TestRequestForm', 'trf');
        set_time_limit(0);
        ini_set('memory_limit', '-1');
    }

    public function add_sample($id)
    {

        $this->form_validation->set_rules('branch_name', 'Branch', 'required')
            ->set_rules('sample_registration_sample_type_id', 'Product', 'required')
            ->set_rules('qty_unit', 'Qty. Unit', 'required')
            ->set_rules('sample_desc', 'Sample Description', 'required')
            ->set_rules('qty_received', 'Qty Received', 'required')
            ->set_rules('quantity_desc', 'Qty. Description', 'required')
            ->set_rules('assign_sample_registered_to_lab_id', 'Primary Lab', 'required');
            // ->set_rules('griddata[]', 'Test', 'required');
        
        $data['product_details'] = $this->sr->get_product_details($id);
        $product_id = $data['product_details']->pid;
        $data['trf_data'] = $this->sr->get_fields_by_id("trf_registration", "trf_sample_desc, open_trf_customer_id,product_custom_fields, trf_quote_id", $id, "trf_id");
        $data['application_care_instruction'] = $this->sr->get_application_care_instruction();
        $data['selected_application_care_instruction'] = $this->sr->get_fields_by_id('trf_apc_instruction', '*', $id, 'trf_id');
        $data['test_specification'] = $this->sr->get_test_spec($product_id);
        $table = "mst_branches";
        $status = "1";
        $columns = "branch_id,branch_name";
        $data['branches'] = $this->sr->get_columns_data($table, $columns, $status);
        $data['units'] = $this->sr->get_units();
        $data['selected_test'] = $this->sr->get_selected_test($id);
        $data['trf_id'] = $id;

        $data['tests']  = $this->sr->get_test_name($product_id);

        if ($this->input->post()) {
            $division['division'] = $this->sr->get_fields_by_id($table = "trf_registration", $columns = "division", $id = $id, $where = "trf_id");
            if ($this->form_validation->run()) {
                $record = array(
                    'collection_date'                       => $this->input->post('collection_date'),
                    'received_date'                         => $this->input->post('received_date'),
                    'sample_registration_sample_type_id'    => $this->input->post('sample_registration_sample_type_id'),
                    'sample_desc'                           => $this->input->post('sample_desc'),
                    'seal_no'                               => $this->input->post('seal_no'),
                    'qty_received'                          => $this->input->post('qty_received'),
                    'qty_received'                          => $this->input->post('qty_received'),
                    'create_by'                             => $this->session->userdata('user_data')->uidnr_admin,
                    'create_on'                             => date('Y-m-d H:i:s'),
                    'sample_customer_id'                    => $this->input->post('sample_customer_id'),
                    'status'                                => 'Registered',
                    'qty_unit'                              => $this->input->post('qty_unit'),
                    'trf_registration_id'                   => $id,
                    'division_id'                           => $division['division'][0]['division'],
                    'sample_registration_branch_id'         => $this->input->post('branch_name'),
                    'sample_retain_period'                  => $this->input->post('sample_retain_status'),
                    'quantity_desc'                         => $this->input->post('quantity_desc'),
                    'gc_number'                             => $this->input->post('gc_number'),
                    'sample_registered_to_lab_id'           => $this->input->post('assign_sample_registered_to_lab_id')
                );
                $send_email = $this->input->post('send_mail');
                $test = $this->input->post('test');

                $dynamic = $this->input->post('dynamic_field');

                $dyn_values = [];
                if (!empty($dynamic)) {
                    foreach ($dynamic as $dynamic_values) {
                        if (!empty($dynamic_values)) {
                            $dynamic_value[0] = html_escape($dynamic_values[0]);
                            $dynamic_value[1] = html_escape($dynamic_values[1]);
                            $dyn_values[] = $dynamic_value;
                        }
                    }
                }
                $care_instruction = $this->input->post('dynamic');
                $save = $this->sr->save_sample_registration($record, $dyn_values, $test, $care_instruction);
                // echo $this->db->last_query(); die;
                if ($save['success']) {
                    $logdetails = array(
                        'module'    => 'Samples',
                        'operation' => 'add_sample',
                        'source_module' => 'SampleRegistration',
                        'uidnr_admin'   => $this->admin_id(),
                        'log_activity_on'   => date('Y-m-d H:i:s'),
                        'action_message'    => 'sample registered',
                        'sample_reg_id'     => $save['sample_reg_id'],
                        'send_acknowledgement_mail' => $send_email
                    );
                    // echo "<pre>"; print_r($logdetails); die;
                    $this->sr->save_user_log($logdetails);
                    $this->session->set_flashdata('success', 'Sample Registered');
                    if ($send_email == 'Yes') {
                        $sample_reg_id = $save['sample_reg_id'];
                        return redirect('SampleRegistration_Controller/send_acknowledgement_mail/' . $sample_reg_id . '/sample_registration');
                    } else {
                        return redirect('sample-list');
                    }
                } else {
                    $this->session->set_flashdata('false', 'Something went wrong!');
                }
            } else {
                $trf_id = $id;
                // $branch = $this->input->post('branch_name');
                $data['labs_id'] = $this->sr->get_labs_by_branch($trf_id);
                // print_r($data); die;
            }
        }
        $trf_id = $id;
        // $branch = $this->input->post('branch_name');
        // print_r($data); die;
        $data['labs_id'] = $this->sr->get_labs_by_branch($trf_id);
        // echo $this->db->last_query(); die;
        // echo "<pre>"; print_r($data); die;

        $this->load_view('sample_registration/add_sample', $data);
    }

    public function get_labs_by_branch()
    {
        $trf_id = $this->input->post('trf_id');
        // $branch = $this->input->post('branch_id');
        $labs = $this->sr->get_labs_by_branch($trf_id);
        echo json_encode($labs);
    }

    public function sample_register_listing($page = 0, $trf = null, $customer_name = null, $product = null, $created_on = null, $ulr_no = null, $gc_number = null, $buyer = null, $status = null, $division = null, $style_no = null, $start_date = null, $end_date = null, $applicant = null, $labtype = null, $startdue = null, $enddue = null, $report_remark = null,$report_review = null, $year = null, $month = null) // dashboard
    {
        //$this->output->enable_profiler(true);
        $checkUser = $this->session->userdata('user_data');
        $cust_where = NULL;
        $buyer_where = ['customer_type' => 'Buyer'];

        if (exist_val('Branch/Wise', $this->session->userdata('permission'))) {
            $multibranch = $this->session->userdata('branch_ids');
            $buyer_where['mst_branch_id IN (' . $multibranch . ') '] = null;
            $cust_where['mst_branch_id IN (' . $multibranch . ') '] = null;
        }
        $data['customer'] = $this->sr->get_result("customer_id,CONCAT(customer_name,' (Address - ',address,')') as customer_name", "cust_customers", $cust_where);
        $data['buyer'] = $this->sr->get_result("customer_id,customer_name", "cust_customers", $buyer_where);
        $data['products'] = $this->sr->get_products();
        $data['sample_status'] = $this->sr->get_status();
        $data['division'] = $this->sr->get_fields("mst_divisions", "division_id,division_name");
        $per_page = "10";
        $page = $this->uri->segment(3);
        if ($page != 0) {
            $page = ($page - 1) * $per_page;
        } else {
            $page = 0;
        }

        $total_count = $this->sr->get_registered_sample($per_page, $page, $trf, $customer_name, $product, $created_on, $ulr_no, $gc_number, $buyer, $status, $division, $style_no, $start_date, $end_date, $applicant, $labtype, $startdue, $enddue, $report_remark, $report_review, $year, $month, $count = true); // dashboard
        $config['full_tag_open']    = '<div class="pagging text-center"><nav><ul class="pagination">';
        $config['full_tag_close']   = '</ul></nav></div>';
        $config['num_tag_open']     = '<li class="page-item"><span class="page-link">';
        $config['num_tag_close']    = '</span></li>';
        $config['cur_tag_open']     = '<li class="page-item active"><span class="page-link">';
        $config['cur_tag_close']    = '<span class="sr-only">(current)</span></span></li>';
        $config['next_tag_open']    = '<li class="page-item"><span class="page-link">';
        $config['next_tag_close']  = '<span aria-hidden="true"></span></span></li>';
        $config['prev_tag_open']    = '<li class="page-item"><span class="page-link">';
        $config['prev_tag_close']  = '</span></li>';
        $config['first_tag_open']   = '<li class="page-item"><span class="page-link">';
        $config['first_tag_close'] = '</span></li>';
        $config['last_tag_open']    = '<li class="page-item"><span class="page-link">';
        $config['last_tag_close']  = '</span></li>';
        $config['base_url'] = base_url() . "SampleRegistration_Controller/sample_register_listing";
        $config['use_page_numbers'] = TRUE;
        $config['uri_segment'] = 3;
        $config['total_rows'] = $total_count;
        $config['per_page'] = $per_page;
        $this->pagination->initialize($config);
        $data['pagination'] = $this->pagination->create_links();
        $data['sample_registered'] = $this->sr->get_registered_sample($per_page, $page, $trf, $customer_name, $product, $created_on, $ulr_no, $gc_number, $buyer, $status, $division, $style_no, $start_date, $end_date, $applicant, $labtype, $startdue, $enddue, $report_remark,$report_review , $year, $month); //dashboard

        // AJIT CODE 24-02-2021
        $this->sr->get_registered_sample(NULL, NULL, $trf, $customer_name, $product, $created_on, $ulr_no, $gc_number, $buyer, $status, $division, $style_no, $start_date, $end_date, $applicant, $labtype, $startdue, $enddue, $report_remark,$report_review , $year, $month, $count = true); // dashboard
        $this->session->set_userdata('excel_query', $this->db->last_query());
        // END

        if ($total_count > 0) {
            $start = (int)$page + 1;
        } else {
            $start = 0;
        }
        $end = (($data['sample_registered']) ? count($data['sample_registered']) : 0) + (($page) ? $page : 0);
        $data['result_count'] = "Showing " . $start . " - " . $end . " of " . $total_count . " Results";
        $data['service_type_data'] = $this->trf->get_service_type();
        //echo "<pre>"; print_r($data['sample_registered']); die;
         $data['report_reviewer'] = $this->sr->get_reviewer(); // report reviewer
        $this->load_view('sample_registration/sample-list', $data);
    }

    public function send_for_evaluation()
    {
        $id = $this->input->post('sample_id');
        $data = array(
            'status'    => 'Sample Sent for Evaluation',
            'previous_status'   => 'Registered'
        );
        $update = $this->sr->update_status($data, $id);
        if ($update) {
            $this->session->set_flashdata('success', 'Sample sent for evaluation');
            $result = true;
        } else {
            $this->session->set_flashdata('false', 'Something went wrong!');
            $result = false;
        }
        echo json_encode($result);
    }

    public function get_sample_details()
    {
        $id = $this->input->post('sample_id');
        $data['sample_detail'] = $this->sr->get_sample_detail($id);
        $data['test_detail'] = $this->sr->sample_selected_test($id);
        echo json_encode($data);
    }

    // Get sample log
    public function get_sample_log()
    {
        $sample_id = $this->input->post('sample_id');
        $data = $this->sr->get_sample_log($sample_id);
        echo json_encode($data);
    }

    public function add_test_for_sample()
    {
        $test_id    = $this->input->post('test_id');
        $sample_id  = $this->input->post('sample_id');
        $test_method  = $this->input->post('test_method');

        $save_test = $this->sr->add_test_for_sample($test_id, $sample_id, $test_method);
        $data['test_detail'] = $this->sr->sample_selected_test($sample_id);
        echo json_encode($data);
    }

    public function remove_test_for_sample()
    {
        $sample_test_id = $this->input->post('sample_test_id');
        $sample_id = $this->input->post('sample_id');
        $remove = $this->sr->remove_data($table = 'sample_test', $column = 'sample_test_id', $sample_test_id);
        $old_status_query = $this->db->select('status')->from('sample_registration')->where('sample_reg_id', $sample_id)->get();
        $old_status = $old_status_query->row()->status;
        $logDetails = array(
            'module' => 'Samples',
            'old_status' => $old_status,
            'new_status' => '',
            'sample_reg_id' => $sample_id,
            'sample_assigned_lab_id' => '',
            'action_message' => 'Test removed',
            'report_id' => '',
            'report_status' => '',
            'test_ids' => '',
            'test_names' => '',
            'test_newstatus' => '',
            'test_oldStatus' => '',
            'test_assigned_to' => '',
            'uidnr_admin'   => $this->admin_id(),
            'operation' => 'remove_test_for_sample'
        );

        $this->sr->save_user_log($logDetails);
        $record_data = $this->sr->get_row('record_finding_id', 'record_finding_details', ['sample_test_id' => $sample_test_id, 'sample_registration_id' => $sample_id]);
        if (!empty($record_data->record_finding_id)) {
            $delete_data = $this->sr->delete_record_finding_data($record_data->record_finding_id);
        }
        $data['test_detail'] = $this->sr->sample_selected_test($sample_id);
        echo json_encode($data);
    }

    public function get_sample_parts()
    {
        $sample_id = $this->input->post('sample_id');
        $data = $this->sr->get_parts_by_sample($sample_id);
        echo json_encode($data);
    }

    public function save_sample_parts()
    {
        $sample_id = $this->input->post('sample_id');
        $part_id = $this->input->post('part_id');
        $part_name = $this->input->post('part_name');
        $part_desc = $this->input->post('part_desc');
        $record = array(
            'parts_sample_reg_id'     => $sample_id,
            'part_name'               => $part_name,
            'parts_desc'              => $part_desc
        );

        $save_update = $this->sr->save_sample_parts($record, $part_id);
        if ($save_update) {
            $status = $save_update['status'];
            $comment = $save_update['comment'];
            if ($status) {
                $this->session->set_flashdata('success', $comment);
            } else {
                $this->session->set_flashdata('false', $comment);
            }
        }
        $data = $this->sr->get_parts_by_sample($sample_id);
        echo json_encode($data);
    }

    public function delete_part()
    {
        $sample_id = $this->input->post('sample_id');
        $part_id = $this->input->post('part_id');
        $delete = $this->db->delete('parts', ['part_id' => $part_id]);
        if ($delete) {
            $old_status_query = $this->db->select('status')->from('sample_registration')->where('sample_reg_id', $sample_id)->get();
            $old_status = $old_status_query->row()->status;
            $logDetails = array(
                'module' => 'Samples',
                'old_status' => $old_status,
                'new_status' => '',
                'sample_reg_id' => $sample_id,
                'sample_assigned_lab_id' => '',
                'action_message' => 'Part deleted',
                'report_id' => '',
                'report_status' => '',
                'test_ids' => '',
                'test_names' => '',
                'test_newstatus' => '',
                'test_oldStatus' => '',
                'test_assigned_to' => '',
                'uidnr_admin'   => $this->admin_id(),
                'operation' => 'delete_part'
            );

            $this->sr->save_user_log($logDetails);
            $this->session->set_flashdata('success', 'Part deleted');
        } else {
            $this->session->set_flashdata('false', 'something went wrong!.');
        }
        $data = $this->sr->get_parts_by_sample($sample_id);
        echo json_encode($data);
    }

    public function get_part_details()
    {
        $part_id = $this->input->post('part_id');
        $data = $this->sr->get_fields_by_id($table = "parts", $columns = "part_id,parts_sample_reg_id,part_name,parts_desc", $id = $part_id, $where = "part_id");
        echo json_encode($data);
    }

    public function assign_part_sample()
    {
        $sample_test_id = $this->input->post('sample_test_id');
        $sample_reg_id = $this->input->post('sample_reg_id');
        $part_id = $this->input->post('part_id');
        if (is_array($part_id)) {
            // echo 1;
            $part_ids = implode(",", $part_id);
        } else {
            // echo 2;
            $part_ids = $part_id;
        }

        $update = $this->sr->update_sample_test($sample_test_id, $part_ids);
        if ($update) {
            $old_status_query = $this->db->select('status')->from('sample_registration')->where('sample_reg_id', $sample_reg_id)->get();
            $old_status = $old_status_query->row()->status;
            $logDetails = array(
                'module' => 'Samples',
                'old_status' => $old_status,
                'new_status' => '',
                'sample_reg_id' => $sample_reg_id,
                'sample_assigned_lab_id' => '',
                'action_message' => 'Part assigned to sample',
                'report_id' => '',
                'report_status' => '',
                'test_ids' => '',
                'test_names' => '',
                'test_newstatus' => '',
                'test_oldStatus' => '',
                'test_assigned_to' => '',
                'uidnr_admin'   => $this->admin_id(),
                'operation' => 'assign_part_sample'
            );

            $this->sr->save_user_log($logDetails);
            $this->session->set_flashdata('success', 'Data saved successfully');
        } else {
            $this->session->set_flashdata('false', 'Something went wrong!');
        }
        $status_query = $this->db->select('status')->from('sample_registration')->where('sample_reg_id', $sample_reg_id)->get();
        $status = $status_query->row()->status;
        $data['sample_status'] = $status;
        $data['test_detail'] = $this->sr->sample_selected_test($sample_reg_id);
        echo json_encode($data);
    }

    public function save_evaluation()
    {
        $input = $this->input->post();
        $save = $this->sr->save_evaluation($input);
        if ($save) {
            echo json_encode(["message" => "Data Saved successfully", "status" => 1]);
        } else {
            echo json_encode(["message" => "Something went wrong!.", "status" => 0]);
        }
    }

    public function upload_image()
    {
        $this->form_validation->set_rules('sample_image[]', 'Sample Images', 'trim|callback_file_check');
        if ($this->form_validation->run()) {
            for ($count = 0; $count < count($_FILES["sample_image"]["name"]); $count++) {

                $image['type'] = $_FILES["sample_image"]["type"][$count];
                $image['name'] = $_FILES["sample_image"]["name"][$count];
                $image['tmp_name'] = $_FILES["sample_image"]["tmp_name"][$count];
                $valid_file = $this->check_valid_file_upload($image);
                if ($valid_file) {
                    $data['image_file_path'] = $valid_file['image'];
                    $data['image_thumb_file_path'] = $valid_file['thumb'];
                    $data['sample_reg_id'] = $this->input->post('sample_reg_id');
                    $data['created_by'] = $this->admin_id();
                    $data['created_on'] = date('Y-m-d H:i:s');
                    $mult[] = $data;
                } else {
                    echo json_encode(["message" => "Please select a JPEG image", "status" => 0]);
                    exit;
                }
            }
            $result = $this->sr->insert_multiple_data("sample_photos", $mult);
            if ($result) {
                echo json_encode(["message" => "Images saved successfully", "status" => 1]);
            } else {
                echo json_encode(["message" => "Something went wrong!.", "status" => 0]);
            }
        } else {
            echo json_encode(["message" => "Something Wrong", "error" => $this->form_validation->error_array(), "status" => 0]);
        }
    }

    public function check_valid_file_upload($file_name)
    {
        if ($file_name['name'] != '' && $file_name['type'] == 'image/jpeg' || $file_name['type'] == 'image/jpg' || $file_name['type'] == 'image/png') {
            $image = $this->multiple_upload_image($file_name);
            if (!empty($image)) {
                $img['image'] = $image['aws_path'];
                $thumb_name = $this->generate_image_thumbnail($file_name['name'], $file_name['tmp_name'], THUMB_PATH);
                $thumb = $this->upload_thumb_aws(THUMB_PATH . $thumb_name, $thumb_name);
                $img['thumb'] = $thumb['aws_path'];
                $result = true;
            } else {
                $result = false;
            }
        } else {
            $result = false;
        }

        if ($result == false) {
            return false;
        } else {
            return $img;
        }
    }

    public function send_acknowledgement_mail($sample_reg_id)
    {
        if ($this->input->post()) {
            $to = $this->input->post('to');
            $cc = $this->input->post('cc');
            $bcc = $this->input->post('bcc');
            $subject = $this->input->post('subject');
            $message = $this->input->post('message');
            $from = FROM;
            $mail = send_acknowledgement_mail($to, $from, $cc, $bcc, $message, $subject);
            if ($mail) {
                $this->sr->update_data('sample_registration',['acknowledgement_mail_status' => 1],['sample_reg_id' => $sample_reg_id]);
                $old_status_query = $this->db->select('status')->from('sample_registration')->where('sample_reg_id', $sample_reg_id)->get();
                $old_status = $old_status_query->row()->status;
                $log_details = array(
                    'module'    => 'Samples',
                    'operation' => 'send_acknowledgement_mail',
                    'source_module' => 'SampleRegistration',
                    'sample_reg_id' => $sample_reg_id,
                    'uidnr_admin'   => $this->admin_id(),
                    'log_activity_on'   => date('Y-m-d H:i:s'),
                    'action_message'    => 'Sample Acknowledgement mail sent',
                    'to_users'          => $to,
                    'cc_users'          => $cc,
                    'bcc_users'         => $bcc,
                    'old_status'        => $old_status,
                    'new_status'        => $old_status
                );
                $this->sr->save_user_log($log_details);
                echo json_encode(["message" => "Mail sent successfully", "status" => 1]);
            } else {
                echo json_encode(["message" => "Something went wrong!.", "status" => 0]);
            }
            exit;
        }
        $send_email = $this->sr->send_email($sample_reg_id, "sample_registration");
        $send_email['sample_reg_id'] = $sample_reg_id;
        // echo "<pre>"; print_r($send_email); die;
        $this->load_view('template/compose_acknowledgement_mail', $send_email);
    }

    public function record_finding_pdf()
    {
        $sample_reg_id = $this->input->post('sample_reg_id');
        $lab_type_id = ($this->input->post('lab_type_id')) ? $this->input->post('lab_type_id') : null;
        $data = $this->sr->get_worksheet_details($sample_reg_id, $lab_type_id);
        echo json_encode($data);
    }

    public function get_barcode()
    {
        $sample_reg_id = $this->input->post('sample_reg_id');
        $data = $this->sr->get_fields_by_id('sample_registration', 'barcode_path', $sample_reg_id, 'sample_reg_id');
        echo json_encode($data[0]);
    }

    public function generate_performa_invoice()
    {

        $sample_reg_id = $this->input->post('sample_reg_id');
        $where['sample_reg_id'] = $sample_reg_id;
        $data = $this->sr->get_result("*", "sample_registration", $where);
        /* added by millan on 25-06-2021 */
        $checkUser = $this->session->userdata('user_data');
        $data['created_by'] = $checkUser->uidnr_admin;
        /* added by millan on 25-06-2021 */
        $result = $this->sr->generate_performa_INVOICE($sample_reg_id, $data);
        if ($result) {
            $msg = array(
                'status' => 1,
                'msg' => 'Performa Invoice Successfully Generated'
            );
        } else {
            $msg = array(
                'status' => 0,
                'msg' => 'Error in Generating Performa Invoice'
            );
        }

        echo json_encode($msg);
    }

    /*  added by millan on 15-01-2021 */
    // public function send_sample_for_manual_report()
    // {
    //     $sample_reg_id = $this->input->post('sample_reg_id');
    //     $checkUser = $this->session->userdata('user_data');
    //     $generated_on = date('Y-m-d H:i:s');
    //     $generated_by = $checkUser->uidnr_admin;
    //     $data = array('status' => 'Sample Sent for Manual Reporting', 'previous_status' => 'Registered');
    //     $update_data = $this->sr->update_status($data, $sample_reg_id);
    //     if ($update_data) {
    //         $fetch_gc = $this->sr->fetch_data_for_gc($sample_reg_id);
    //         $params['data'] =  base_url('Render/download_qr?sample_reg_id=' . base64_encode($sample_reg_id));
    //         $this->load->library('Ciqrcode');
    //         $params['level'] = 'H';
    //         $params['size'] = 2;
    //         $params['savename'] = LOCAL_PATH . (($fetch_gc) ? $fetch_gc->gc_no : rand(0000, 9999)) . '.png';
    //         $this->ciqrcode->generate($params); // generate QR 
    //         $filepath = basename($params['savename']);
    //         $qrcode = $this->uploadQRcode($filepath);   // uploading QR ON AWS
    //         if ($qrcode) {
    //             $insert_gc = array('qr_code_name' => $qrcode['aws_path'], 'report_num' => $fetch_gc->gc_no, 'sample_reg_id' => $sample_reg_id, 'report_type' => 'Manual Report', 'generated_date' => $generated_on, 'generated_by' => $generated_by);
    //             if ($insert_gc) {

    //                 $exist = $this->sr->get_row('report_id', 'generated_reports', ['report_num' => $fetch_gc->gc_no, 'sample_reg_id' => $sample_reg_id]);
    //                 if ($exist) {
    //                     $insert_gr = $this->sr->update_data('generated_reports', ['qr_code_name' => $qrcode['aws_path']], ['report_id' => $exist->report_id]);
    //                 } else {
    //                     $insert_gr = $this->sr->insert_data('generated_reports', $insert_gc);
    //                 }

    //                 if ($insert_gr) {
    //                     $this->session->set_flashdata('success', 'QR code stored successfully !!');
    //                     $result = true;
    //                 } else {
    //                     $this->session->set_flashdata('error', 'Error in storing QR code !!');
    //                     $result = false;
    //                 }
    //                 $this->session->set_flashdata('success', 'Qr Code Generated Successfully');
    //                 $result = true;
    //             }
    //         } else {
    //             $this->session->set_flashdata('error', 'Error in Generating QR Code');
    //             $result = false;
    //         }
    //     } else {
    //         $this->session->set_flashdata('error', 'Error in Sending Sample For Manual Reporting');
    //         $result = false;
    //     }
    //     echo json_encode($result);
    // }
    /* added by millan on 15-Jan-2021 */

    /*  added by saurabh on 23-062021 */
    public function send_sample_for_manual_report()
    {
        $sample_reg_id = $this->input->post('sample_reg_id');
        $checkUser = $this->session->userdata('user_data');
        $generated_on = date('Y-m-d H:i:s');
        $generated_by = $checkUser->uidnr_admin;
        $data = array('status' => 'Sample Sent for Manual Reporting', 'previous_status' => 'Registered');
        $update_data = $this->sr->update_status($data, $sample_reg_id);
        if ($update_data) {
            $fetch_gc = $this->sr->fetch_data_for_gc($sample_reg_id);

            $params['data'] =  base_url('Render/download_qr?sample_reg_id=' . base64_encode($sample_reg_id));
            $this->load->library('Ciqrcode');
            $params['level'] = 'H';
            $params['size'] = 2;
            $params['savename'] = LOCAL_PATH . (($fetch_gc) ? $fetch_gc->gc_no : rand(0000, 9999)) . '.png';
            $this->ciqrcode->generate($params); // generate QR 
            $filepath = basename($params['savename']);
            $qrcode = $this->uploadQRcode($filepath);  // uploading QR ON AWS
            if ($qrcode) {
                $insert_gc = array('qr_code_name' => $qrcode['aws_path'], 'report_num' => $fetch_gc->gc_no, 'sample_reg_id' => $sample_reg_id, 'report_type' => 'Manual Report', 'generated_date' => $generated_on, 'generated_by' => $generated_by);
                if ($insert_gc) {

                    // $exist = $this->sr->get_row('report_id', 'generated_reports', ['report_num' => $fetch_gc->gc_no, 'sample_reg_id' => $sample_reg_id]);
                    // if ($exist) {
                    //     $insert_gr = $this->sr->update_data('generated_reports', ['qr_code_name' => $qrcode['aws_path']], ['report_id' => $exist->report_id]);
                    // } else {
                    $insert_gr = $this->sr->insert_data('generated_reports', $insert_gc);
                    // }

                    if ($insert_gr) {
                        $this->session->set_flashdata('success', 'QR code stored successfully !!');
                        // Added by Saurabh on 16-08-2021
                        $result = ['status' => 1, 'message' => 'QR code stored successfully !!'];
                        // $result = true;
                    } else {
                        $this->session->set_flashdata('error', 'Error in storing QR code !!');
                        // Added by Saurabh on 16-08-2021
                        $result = ['status' => 0, 'message' => 'Error in storing QR code !!'];
                        // $result = false;
                    }
                    $this->session->set_flashdata('success', 'Qr Code Generated Successfully');
                    // Added by Saurabh on 16-08-2021
                    $result = ['status' => 1, 'message' => 'Qr Code Generated Successfully'];
                    // $result = true;
                }
            } else {
                $this->session->set_flashdata('error', 'Error in Generating QR Code');
                $result = ['status' => 0, 'message' => 'Error in storing QR code !!'];
                // $result = false;
            }
        } else {
            $this->session->set_flashdata('error', 'Error in Sending Sample For Manual Reporting');
            $result = ['status' => 0, 'message' => 'Error in Sending Sample For Manual Reporting'];
            // $result = false;
        }
        echo json_encode($result);
    }
    /* added by saurabh on 23-06-2021 */

    /* added by millan on 18-Jan-2021 */
    public function upload_manual_report()
    {
        $sample_reg_id = $this->input->post('sample_reg_id');
        if ($sample_reg_id) {
            if ($_FILES['manual_report_pdf']['name'] != '' && !empty($_FILES['manual_report_pdf']['name'])) {
                $report_upload = $this->multiple_upload_image($_FILES['manual_report_pdf']);
                $upload_report_path = $report_upload['aws_path'];

                $data_update = array('manual_report_file' => $upload_report_path);
                if ($report_upload) {
                    $update_pdf_path = $this->sr->update_data('generated_reports', $data_update, array('sample_reg_id' => $sample_reg_id));
                    if ($update_pdf_path) {
                        $this->session->set_flashdata('success', 'Manual Report Pdf path Stored Successfully');
                    } else {
                        $this->session->set_flashdata('error', 'Error in storing Manual Report Pdf path');
                    }
                    $this->session->set_flashdata('success', 'Manual Report Pdf Uploaded Successfully');
                } else {
                    $this->session->set_flashdata('error', 'Error in Uploading Pdf on AWS');
                }
                $this->session->set_flashdata('success', 'Manual Report Pdf Uploaded Successfully on AWS');
            } else {
                $this->session->set_flashdata('error', 'Error in Pdf');
            }
        } else {
            $this->session->set_flashdata('error', 'Data Not Found !!!');
            redirect($_SERVER['HTTP_REFERER']);
        }
    }
    /* added by millan on 18-Jan-2021 */

    /* added by millan on 19-Jan-2021 */
    public function show_qr_code()
    {
        $sample_reg_id = $this->input->post();
        $sample_id = $sample_reg_id['sample_reg_id'];
        if ($sample_reg_id) {
            $qr = $this->sr->fetch_qr_code($sample_id);
            if ($qr) {
                $msg = array('qr_path' => $qr->qr_code_name);
            } else {
                $msg = array('qr_path' => '');
            }
        }
        echo json_encode($msg);
    }

    /* added by millan on 19-Jan-2021 for download pdf */
    // public function download_pdf()
    // {
    //     $get = $this->input->get();
    //     $where = base64_decode($get['sample_reg_id']);
    //     $path = $this->sr->download_pdf_aws($where);
    //     if ($path) {
    //         if ($path->manual_report_file) {
    //             $this->load->helper('download');
    //             $pdf_path    =   file_get_contents($path->manual_report_file);
    //             $pdf_name    =   basename($path->manual_report_file);
    //             force_download($pdf_name, $pdf_path);
    //         } else {
    //             echo '<h1>NO RECORD FOUND</h1>';
    //         }
    //     } else {
    //         echo '<h1>“This pdf stands cancelled. Please do not transact based on this cancelled pdf. Geo Chem will not be liable for any issues, financial, legal or otherwise, based on using this cancelled pdf for any purpose.</h1>';
    //     }
    // }

    public function download_pdf()
    {
        $get = $this->input->get();
        $sample_reg_id = base64_decode($get['sample_reg_id']);
        $report_id = base64_decode($get['report_id']);
        $path = $this->sr->download_pdf_aws($sample_reg_id, $report_id);
        if ($path) {
            if ($path->manual_report_file) {
                $this->load->helper('download');
                $pdf_path    =   file_get_contents($path->manual_report_file);
                $pdf_name    =   ($path->original_file_name != '')?(basename($path->original_file_name)):(basename($path->manual_report_file));
                force_download($pdf_name, $pdf_path);
            } else {
                echo '<h1>NO RECORD FOUND</h1>';
            }
        } else {
            echo '<h1>“This pdf stands cancelled. Please do not transact based on this cancelled pdf. Geo Chem will not be liable for any issues, financial, legal or otherwise, based on using this cancelled pdf for any purpose.</h1>';
        }
    }

    public function download_QRCODE($path)
    {
        $path = base64_decode($path);
        $this->load->helper('download');
        $pdf_path    =   file_get_contents($path);
        $pdf_name    =   basename($path);
        force_download($pdf_name, $pdf_path);
    }

    public function get_sample_images()
    {
        $sample_reg_id = $this->input->post('sample_reg_id');
        $data = $this->sr->get_fields_by_id("sample_photos", 'image_file_path', $sample_reg_id, "sample_reg_id");
        echo json_encode($data);
    }
    public function file_check($file)
    {
        $files = $_FILES['sample_image']['name'];
        foreach ($files as $key => $value) {
            if (empty($value)) {
                $this->form_validation->set_message('file_check', 'THIS FILE REQUIRED PLEASE SELECT');
                return false;
            }
        }
        return true;
    }
    /*---------------------------clone sample-----------------------------------*/
    public function clone_sample()
    {
        $id = $this->input->post();
        $trf_id = $id['trf_id'];
        $sample_id = $id['sample_id'];
        $sample_test = $this->sr->get_sample_test($sample_id);
        $data = $this->trf->get_clone_trf_data($trf_id);
        $app_care = $this->trf->get_app_care_data($trf_id);
        $tat_date = $id['tat_date']; // sample clone changes
        $service_type = $id['service_type'];// sample clone changes
        $test = $this->trf->get_trf_test($trf_id);

        $checkUser = $this->session->userdata('user_data');
        $this->user = $checkUser->uidnr_admin;

        $today = date("Ymd");
        $serial_no_query = $this->trf->get_trf_serial_no($data->trf_branch);
        // echo $this->db->last_query(); die;					  
        $serial_number = $serial_no_query->serial_no + 1;

        // save trf number config
        $config['branch_id'] = $data->trf_branch;
        $config['division_id'] = $data->division;
        $config['serial_no'] = $serial_number;
        $config['created_on'] = date('Y-m-d H:i:s');

        $save_config = $this->db->insert('trf_number_confiq', $config);

        $rand = str_pad($serial_number, 6, "0", STR_PAD_LEFT);
        $trf_num = 'TRF/' . $today . '/' . $rand;
        /*-------------Due Date calculation-------------// sample clone changes------------------ */
        // if (!empty($data->tat_date) && strtotime($data->tat_date) != 0 && $data->tat_date != '0000-00-00 00:00:00') {
        //     // Changed by saurabh on 19-08-2021
        //     $due_date = date('Y-m-d', strtotime($data->tat_date));
        // } else {
        //     if ($data->trf_service_type === 'Regular') {
        //         if ($data->service_days > 1) {
        //             $includingToday = $data->service_days - 1;
        //         } else {
        //             $includingToday = 2;
        //         }

        //         $days = ' ' . $includingToday . ' Days';
        //         $due_date = $this->sr->calculateDueDate(date('Y-m-d H:i', strtotime(date('Y-m-d H:i:s'))), $includingToday);
        //     } elseif ($data->trf_service_type === 'Express') {
        //         $includingToday = 1;
        //         $due_date = $this->sr->calculateDueDate(date('Y-m-d H:i', strtotime(date('Y-m-d H:i:s'))), $includingToday);
        //     } else if ($data->trf_service_type === 'Express3') {
        //         $includingToday = 2;
        //         $due_date = $this->sr->calculateDueDate(date('Y-m-d H:i', strtotime(date('Y-m-d H:i:s'))), $includingToday);
        //     } else {
        //         $includingToday = 0;
        //         $due_date = $this->sr->calculateDueDate(date('Y-m-d H:i', strtotime(date('Y-m-d H:i:s'))), $includingToday, true);
        //     }
        // }

        $service_type_single = $this->trf->get_service_type_single($service_type);  
					
        if ($service_type >= 5 && $service_type <= 16) {
            
            $record['trf_service_type'] = "Regular";
            $record['service_days'] = $service_type_single['service_value'];

        } elseif($service_type== 1) {
        
            $record['trf_service_type'] = $service_type_single['service_type'];
            $record['service_days'] = '';
        }
        elseif($service_type == 2) {
        
            $record['trf_service_type'] = $service_type_single['service_type'];
            $record['service_days'] = '';
        }
        elseif($service_type == 3) {
         
            $record['trf_service_type'] = $service_type_single['service_type'];
            $record['service_days'] = '';
        }
        elseif($service_type == 4) {

            $record['trf_service_type'] = $service_type_single['service_type'];
            $record['service_days'] = '';
        }
        elseif($service_type == 17) {

            $record['trf_service_type'] = $service_type_single['service_type'];
            $record['service_days'] = '';
        }



     if ($record['trf_service_type'] === 'Regular') {
        if ($service_type > 1) {
            $includingToday = $service_type - 1;
        } else {
            $includingToday = 2;
        }

        $days = ' ' . $includingToday . ' Days';
        $due_date = $this->sr->calculateDueDate(date('Y-m-d H:i', strtotime(date('Y-m-d H:i:s'))), $includingToday);
    } elseif ($record['trf_service_type'] === 'Express') {
        $includingToday = 1;
        $due_date = $this->sr->calculateDueDate(date('Y-m-d H:i', strtotime(date('Y-m-d H:i:s'))), $includingToday);
    } else if ($record['trf_service_type'] === 'Express3') {
        $includingToday = 2;
        $due_date = $this->sr->calculateDueDate(date('Y-m-d H:i', strtotime(date('Y-m-d H:i:s'))), $includingToday);
    }  else if ($record['trf_service_type'] === 'Same Day') {
        $includingToday = 0;
        $due_date = $this->sr->calculateDueDate(date('Y-m-d H:i', strtotime(date('Y-m-d H:i:s'))), $includingToday);
    } else {
        $includingToday = 0;
        $due_date = $this->sr->calculateDueDate(date('Y-m-d H:i', strtotime(date('Y-m-d H:i:s'))), $includingToday, true);
    }
    // sample clone changes
    if($tat_date != null || $tat_date != '' || !empty($tat_date) ){
        $due_date = $tat_date;
    }else {
        $due_date = $due_date;
}
        /*-----end--------Due Date calculation------------------------------- */
        $trf_record = array(
            "trf_service_type" => $data->trf_service_type,
            "service_days" =>  $record['service_days'],
            // "service_days" => $data->service_days,
            "trf_applicant" => $data->trf_applicant,
            "trf_contact" => $data->trf_contact,
            "trf_sample_ref_id" => $data->trf_sample_ref_id,
            "trf_invoice_to" => $data->trf_invoice_to,
            "trf_invoice_to_contact" => $data->trf_invoice_to_contact,
            "trf_product" => $data->trf_product,
            "work_id" => $data->work_id,
            "trf_buyer" => $data->trf_buyer,
            "trf_agent" => $data->trf_agent,
            "trf_sample_desc" => $data->trf_sample_desc,
            "trf_no_of_sample" => $data->trf_no_of_sample,
            "trf_country_destination" => $data->trf_country_destination,
            "trf_end_use" => $data->trf_end_use,
            "trf_quote_id" => $data->trf_quote_id,
            "trf_client_ref_no" => $data->trf_client_ref_no,
            "trf_ref_no" =>  $trf_num,
            "trf_status" => 'New',
            "tfr_signature" => $data->tfr_signature,
            "quote_customer_id" => $data->quote_customer_id,
            "trf_applicant" => $data->trf_applicant,
            "reported_to" => $data->reported_to,
            "sample_return_to" => $data->sample_return_to,
            "open_trf_currency_id" => $data->open_trf_currency_id,
            "open_trf_customer_id" => $data->open_trf_customer_id,
            "open_trf_customer_type" => $data->open_trf_customer_type,
            "open_trf_exchange_rate" => $data->open_trf_exchange_rate,
            "trf_regitration_type" => $data->trf_regitration_type,
            "care_instruction" => $data->care_instruction,
            "trf_thirdparty" => $data->trf_thirdparty,
            "trf_cc" => $data->trf_cc,
            "cc_type" => $data->cc_type,
            "trf_bcc" => $data->trf_bcc,
            "bcc_type" => $data->bcc_type,
            "trf_country_orgin" => $data->trf_country_orgin,
            "trf_branch" => $data->trf_branch,
            "tat_date" => $tat_date,// sample clone changes
            "trf_customer_remarks" => $data->trf_customer_remarks,
            "trf_type" => $data->trf_type,
            "division" => $data->division,
            "crm_user_id" => $data->crm_user_id,
            "sample_pickup_services" => $data->sample_pickup_services,
            "temp_ref_id" => $data->temp_ref_id,
            "product_custom_fields" => $data->product_custom_fields,
            "updated_by" => $this->user,
            "create_on" => date('Y-m-d H:i:s'),
            "clone_trf_id" => $trf_id,
            'sales_person'  => $data->sales_person,
            'trf_package_id'  => $data->trf_package_id,
            'trf_protocol_id'  => $data->trf_protocol_id,
        );

        $trf_ids = $this->trf->insert_data('trf_registration', $trf_record);
        if ($trf_ids) {
            if ($app_care) {
                foreach ($app_care as $value) {
                    $app_care_records = array(
                        "trf_id" => $trf_ids,
                        "description" => $value->description,
                        "image_sequence" => $value->image_sequence,
                        "application_care_id" => $value->application_care_id,
                        "created_by" => $this->user,
                        "image" => $value->image,
                    );
                    $app_care_ids = $this->trf->insert_data('trf_apc_instruction', $app_care_records);
                }
            }
            if ($test) {
                foreach ($test as $value1) {
                    $trf_test_records = array(
                        "trf_test_trf_id" => $trf_ids,
                        "trf_test_test_id" => $value1->trf_test_test_id,
                        "trf_test_status" => 'new',
                        "trf_work_id" => $value1->trf_work_id,
                        'trf_test_test_method_id'   => $value1->trf_test_test_method_id,
                        'trf_test_quote_type'   => $value1->trf_test_quote_type,
                        'trf_test_quote_id'   => $value1->trf_test_quote_id,
                        'trf_test_protocol_id'   => $value1->trf_test_protocol_id,
                        'trf_test_package_id'   => $value1->trf_test_package_id,
                        'rate_per_test'         => $value1->rate_per_test
                    );
                    $trf_test_ids = $this->trf->insert_data('trf_test', $trf_test_records);
                }
            }


            $sample_data = $this->sr->get_sample_data($sample_id);
            $sample_record = array(
                "collection_date" => $sample_data->collection_date,
                "received_date" => date('Y-m-d H:i:s'),
                "sample_source_type" => $sample_data->sample_source_type,
                "sample_registration_sample_type_id" => $sample_data->sample_registration_sample_type_id,
                "vessel_transformer_id" => $sample_data->vessel_transformer_id,
                "sample_desc" => $sample_data->sample_desc,
                "seal_no" => $sample_data->seal_no,
                "qty_received" => $sample_data->qty_received,
                "work_id" => $sample_data->work_id,
                "sample_customer_id" => $sample_data->sample_customer_id,
                "type" => $sample_data->type,
                "sample_registration_test_standard_id" => $sample_data->sample_registration_test_standard_id,
                "sample_retain_status" => $sample_data->sample_retain_status,
                "sample_registered_to_lab_id" =>  $sample_data->sample_registered_to_lab_id,
                "sample_registration_branch_id" => $sample_data->sample_registration_branch_id,
                "no_labs_assigned" => $sample_data->no_labs_assigned,
                "sample_retain_expirydate" => $sample_data->sample_retain_expirydate,
                "quantity_desc" => $sample_data->quantity_desc,
                "qty_unit" => $sample_data->qty_unit,
                "create_on" => date('Y-m-d H:i:s'),
                "create_by" => $this->user,
                "trf_registration_id" => $trf_ids,
                "price_type" => $sample_data->price_type,
                "division_id" => $sample_data->division_id,
                "due_date" => $due_date,
                "clone_sample_reg_id" =>  $sample_id
            );

            $save_sample = $this->sr->save_sample_clone_data($sample_record);
            if ($save_sample) {

                if ($sample_test) {
                    foreach ($sample_test as $s_test) {
                        $sample_test_record = array(
                            "sample_test_sample_reg_id" => $save_sample,
                            "sample_test_test_id" => $s_test->sample_test_test_id,
                            "sample_test_lab_id" => $s_test->sample_test_lab_id,
                            "sample_test_quote_type" => $s_test->sample_test_quote_type,
                            "sample_test_quote_id" => $s_test->sample_test_quote_id,
                            "sample_test_protocol_id" => $s_test->sample_test_protocol_id,
                            "sample_test_package_id" => $s_test->sample_test_package_id,
                            "sample_test_work_id" => $s_test->sample_test_work_id,
                            'rate_per_test'         => $s_test->rate_per_test
                        );
                        $sample_test_ids = $this->trf->insert_data('sample_test', $sample_test_record);
                    }
                }
            }
        }
        if ($save_sample) {
            $message = array("msg" => "Sample Registered Successfully ", "status" => 1);
            $this->session->set_flashdata('success', 'Sample Registered Successfully');
        } else {
            $message = array("msg" => "Error while saving! ", "status" => 0);
            $this->session->set_flashdata('success', 'Error while saving!');
        }
        echo json_encode($message);
    }

    /**--------------end clone sample------------------------------------- */

    // Added by saurabh for login cancel sample on 08-02-2021
    public function cancel_sample()
    {
        $sample_reg_id  = $this->input->post('sample_reg_id');
        $comment        = $this->input->post('comment');
        $save = $this->sr->login_cancel_sample($sample_reg_id, $comment);
        if ($save) {
            echo json_encode(["message" => "Data saved successfully.", "status" => 1]);
        } else {
            echo json_encode(["message" => "Something went wrong!.", "status" => 0]);
        }
    }
    // Added by saurabh for login cancel sample on 08-02-2021

    // Added by saurabh to check GC Number on 10-02-2021
    public function check_gc_number()
    {
        $trf_id     = $this->input->post('trf_id');
        $branch     = $this->input->post('branch');
        $gc_no      = $this->input->post('gc_no');
        $check = $this->sr->check_gc_number($trf_id, $branch, $gc_no);
        if ($check['status'] == 0) {
            echo json_encode(["message" => "Basil Report Number available.", "status" => 1, "gc_number" => $check['gc_number']]);
        } else {
            echo json_encode(["message" => "Basil Report Number is already in use.", "status" => 0, "gc_number" => $check['gc_number']]);
        }
    }
    // Added by saurabh to check GC Number on 10-02-2021

    // Added by Saurabh to get test lab name on 19-02-2021
    public function get_test_labs()
    {
        $sample_reg_id = $this->input->post('sample_reg_id');
        $data = $this->sr->get_lab_name($sample_reg_id);
        echo json_encode($data);
    }
    // Added by Saurabh to get test lab name on 19-02-2021


    // added by ajit 24-02-2021
    public function excel_export()
    {

        $query = $this->session->userdata('excel_query');

        if ($query) {
            $data = $this->db->query($query)->result();

            if ($data && count($data) > 0) {
                $this->load->library('excel');
                $tmpfname = "example.xls";

                $excelReader = PHPExcel_IOFactory::createReaderForFile($tmpfname);
                $objPHPExcel = $excelReader->load($tmpfname);

                $objPHPExcel->getProperties()->setCreator("GEO-CHEM")
                    ->setLastModifiedBy("GEO-CHEM")
                    ->setTitle("Office 2007 XLS Sample Registration Document")
                    ->setSubject("Office 2007 XLS Sample Registration Document")
                    ->setDescription("Description for Sample Registration Document")
                    ->setKeywords("phpexcel office codeigniter php")
                    ->setCategory("Sample Registration details file");


                $objPHPExcel->setActiveSheetIndex(0);
                $objPHPExcel->getActiveSheet()->setCellValue('A1', "SL NO.");
                $objPHPExcel->getActiveSheet()->setCellValue('B1', "Basil Report Number");
                $objPHPExcel->getActiveSheet()->setCellValue('C1', "ULR No.");
                $objPHPExcel->getActiveSheet()->setCellValue('D1', "Product");
                $objPHPExcel->getActiveSheet()->setCellValue('E1', "TRF Reference No.");
                $objPHPExcel->getActiveSheet()->setCellValue('F1', "Customer");
                $objPHPExcel->getActiveSheet()->setCellValue('G1', "Buyer");
                $objPHPExcel->getActiveSheet()->setCellValue('H1', "Status");
                $objPHPExcel->getActiveSheet()->setCellValue('I1', "Created By");
                $objPHPExcel->getActiveSheet()->setCellValue('J1', "Created On");
                $objPHPExcel->getActiveSheet()->setCellValue('K1', "Due Date");
                $objPHPExcel->getActiveSheet()->setCellValue('L1', "Tat Date");
                $objPHPExcel->getActiveSheet()->setCellValue('M1', "Comment");
                $objPHPExcel->getActiveSheet()->setCellValue('N1', "TRF Service Type");
                $objPHPExcel->getActiveSheet()->setCellValue('O1', "Sample Description");
                $objPHPExcel->getActiveSheet()->setCellValue('P1', "Report Generate Date");
                $objPHPExcel->getActiveSheet()->setCellValue('Q1', "Report Release Date");
                $objPHPExcel->getActiveSheet()->setCellValue('R1', "Style Number");
                $objPHPExcel->getActiveSheet()->setCellValue('S1', "Color");
                $objPHPExcel->getActiveSheet()->setCellValue('T1', "Invoice Status"); // new change
                $objPHPExcel->getActiveSheet()->setCellValue('U1', "Report Reviewer Name"); // new change

                $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('N')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('O')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('P')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('R')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('S')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('T')->setAutoSize(true); // new change
                $objPHPExcel->getActiveSheet()->getColumnDimension('U')->setAutoSize(true); // new change

                $i = 2;
                foreach ($data as $key => $value) {
                    $dynamic = json_decode($value->product_custom_fields);
                    $color = '';
                    $style_no = '';
                    if(!empty($dynamic)){
                    foreach ($dynamic as $dynamic_values) {
                        if (!empty($dynamic_values)) {
                             if (!empty($dynamic_values)) {
                                if(strpos(strtolower($dynamic_values[0]),strtolower('Style No')) !== false || strpos(strtolower($dynamic_values[0]),strtolower('Style No.')) !== false || strpos(strtolower($dynamic_values[0]),strtolower('Style Number')) !== false){
                                    $style_no = $dynamic_values[1];
                                }
                                if(strtolower(trim($dynamic_values[0])) == strtolower('Color') || strtolower(trim($dynamic_values[0])) == strtolower('Colour')){
                                    $color = $dynamic_values[1];
                                }
                            }
                            if($dynamic_values[0] == 'Color'){
								$color = $dynamic_values[1];
							}
                        }
                    }
                }
                    $objPHPExcel->getActiveSheet()->setCellValue('A' . $i, ($i - 1));
                    $objPHPExcel->getActiveSheet()->setCellValue('B' . $i, ($value->gc_no) ? $value->gc_no : '');
                    $objPHPExcel->getActiveSheet()->setCellValue('C' . $i, ($value->ulr_no) ? $value->ulr_no : '');
                    $objPHPExcel->getActiveSheet()->setCellValue('D' . $i, ($value->sample_type_name) ? $value->sample_type_name : '');
                    $objPHPExcel->getActiveSheet()->setCellValue('E' . $i, ($value->trf_ref_no) ? $value->trf_ref_no : '');
                    $objPHPExcel->getActiveSheet()->setCellValue('F' . $i, ($value->customer) ? $value->customer : '');
                    $objPHPExcel->getActiveSheet()->setCellValue('G' . $i, ($value->buyer) ? $value->buyer : '');
                    $objPHPExcel->getActiveSheet()->setCellValue('H' . $i, ($value->status) ? $value->status : '');
                    $objPHPExcel->getActiveSheet()->setCellValue('I' . $i, ($value->created_by) ? $value->created_by : '');
                    $objPHPExcel->getActiveSheet()->setCellValue('J' . $i, ($value->created_on) ? $value->created_on : '');
                    $objPHPExcel->getActiveSheet()->setCellValue('K' . $i, ($value->due_date) ? $value->due_date : '');
                    $objPHPExcel->getActiveSheet()->setCellValue('L' . $i, ($value->tat_date) ? $value->tat_date : '');
                    $objPHPExcel->getActiveSheet()->setCellValue('M' . $i, ($value->comment) ? $value->comment : '');
                    $objPHPExcel->getActiveSheet()->setCellValue('N' . $i, ($value->trf_service_type) ? $value->trf_service_type : '');
                    $objPHPExcel->getActiveSheet()->setCellValue('O' . $i, ($value->sample_desc) ? $value->sample_desc : '');
                    $objPHPExcel->getActiveSheet()->setCellValue('P' . $i, ($value->generated_date) ? $value->generated_date : '');
                    $objPHPExcel->getActiveSheet()->setCellValue('Q' . $i, ($value->report_release_time) ? $value->report_release_time : '');
                    $objPHPExcel->getActiveSheet()->setCellValue('R' . $i, $style_no ? $style_no : '');
                    $objPHPExcel->getActiveSheet()->setCellValue('S' . $i, $color ? $color : '');
                    $objPHPExcel->getActiveSheet()->setCellValue('T' . $i, $value->marked_invoice == '1' ? 'Un-Marked' : 'Marked'); // new change
                    $objPHPExcel->getActiveSheet()->setCellValue('U' . $i, $value->admin_fname ? $value->admin_fname : ''); // new change
                    $i++;
                }

                $filename = 'sample_registration_details-' . date('Y-m-d-s') . ".xls";
                $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
                ob_end_clean();
                header('Content-type: application/vnd.ms-excel');
                header('Content-Disposition: attachment; filename=' . $filename);
                $objWriter->save('php://output');
            }
        }
    }


    // code end

    // ajit code start 30-03-2021

    public function release_to_client_all()
    {
        if ($this->session->has_userdata('release_id')) {
            $sample_reg_ids = $this->session->userdata('release_id');

            if ($sample_reg_ids && count($sample_reg_ids)) {
                $p = array();
                $i = 0;
                foreach ($sample_reg_ids as $key => $value) {
                    $p[$i] = $value;
                    $i++;
                }
                $sample_reg_ids = $p;
            } else {
                $sample_reg_ids = false;
            }
        } else {
            $sample_reg_ids = false;
        }


        if ($sample_reg_ids) {
            $result =  $this->sr->update_relase_to($sample_reg_ids);
        } else {
            $result = false;
        }

        if ($result) {
            if ($this->session->has_userdata('release_id')) {
                $this->session->unset_userdata('release_id');
            }
            if ($this->session->has_userdata('release_all')) {
                $this->session->set_userdata('release_all', 0);
            }
            $msg = array(
                'status' => '1',
                'msg' => 'Report Released to client successfully'
            );
        } else {

            $msg = array(
                'status' => '0',
                'msg' => 'Error in Releasing'
            );
        }

        echo json_encode($msg);
    }


    public function make_session()
    {
        $sample_reg_id = $this->input->post('sample_reg_id');
        $status = $this->input->post('status');
        $all = $this->input->post('chek_all');
        if ($sample_reg_id != null) {

            if ($status == 'true') {
                if ($this->session->has_userdata('release_id')) {
                    $release = $this->session->userdata('release_id');
                    $release[$sample_reg_id] = $sample_reg_id;
                } else {
                    $release = [$sample_reg_id => $sample_reg_id];
                }
            } else {
                if ($this->session->has_userdata('release_id')) {
                    $release = $this->session->userdata('release_id');
                    unset($release[$sample_reg_id]);
                } else {
                    $release = array();
                }
            }
        } else {
            if ($status == 'true') {
                $release = array();
                $ids = $this->sr->get_result('sample_reg_id', 'sample_registration', ['status' => 'Report Generated', 'released_to_client' => '0']);
                if ($ids && count($ids) > 0) {
                    foreach ($ids as $key => $value) {
                        $release[$value->sample_reg_id] = $value->sample_reg_id;
                    }
                } else {
                    $release = array();
                }
            } else {
                $release = array();
            }
        }

        if ($all == 'true') {
            $this->session->set_userdata('release_all', '1');
        } else {
            $this->session->set_userdata('release_all', '0');
        }

        $this->session->set_userdata('release_id', $release);
    }
    // end

    // function to send status update to the customer
    public function get_sample_status_report()
    {
        $customers = $this->sr->get_customers_for_update();
        if (!empty($customers)) {
            foreach ($customers as $cust) {
                $status = $this->sr->get_sample_status_report($cust['customer_id']);
                $table = '<table cellspacing="0" border="1" cellpadding="5">';
                $table .= '<thead>';
                $table .= '<tr>';
                $table .= '<th>SL No</th><th>Basil Report Number</th><th>TRF Number</th><th>Sample Status</th><th>Client</th><th>Buyer</th><th>TAT Date</th><th>Test Result</th>';
                $table .= '</tr>';
                $table .= '</thead>';
                $table .= '<tbody>';

                $this->load->library('excel');
                $tmpfname = "example.xls";

                $excelReader = PHPExcel_IOFactory::createReaderForFile($tmpfname);
                $objPHPExcel = $excelReader->load($tmpfname);

                $objPHPExcel->getProperties()->setCreator("GEO-CHEM")
                    ->setLastModifiedBy("GEO-CHEM")
                    ->setTitle("Sample Registration Status Update")
                    ->setSubject("Sample Registration Status Update")
                    ->setDescription("Sample Registration Status Update")
                    ->setKeywords("Sample Registration Status Update")
                    ->setCategory("Sample Registration Status Update");


                $objPHPExcel->setActiveSheetIndex(0);
                $objPHPExcel->getActiveSheet()->setCellValue('A1', "SL NO.");
                $objPHPExcel->getActiveSheet()->setCellValue('B1', "Basil Report Number");
                $objPHPExcel->getActiveSheet()->setCellValue('C1', "TRF Reference No.");
                $objPHPExcel->getActiveSheet()->setCellValue('D1', "Customer");
                $objPHPExcel->getActiveSheet()->setCellValue('E1', "Buyer");
                $objPHPExcel->getActiveSheet()->setCellValue('F1', "Status");
                $objPHPExcel->getActiveSheet()->setCellValue('G1', "Tat Date");
                $objPHPExcel->getActiveSheet()->setCellValue('H1', "Test Result");

                $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);

                $i = 1;
                foreach ($status as $report) {
                    $tat_date = '';
                    if ($report['tat_date'] != '' && $report['tat_date'] != '0000-00-00 00:00:00') {
                        $tat_date = date('Y-m-d', strtotime($report['tat_date']));
                    }
                    $table .= '<tr>';
                    $table .= '<td>' . $i . '</td>';
                    $table .= '<td>' . $report['gc_no'] . '</td>';
                    $table .= '<td>' . $report['trf_ref_no'] . '</td>';
                    $table .= '<td>' . $report['status'] . '</td>';
                    $table .= '<td>' . $report['client'] . '</td>';
                    $table .= '<td>' . $report['buyer'] . '</td>';
                    $table .= '<td>' . $tat_date . '</td>';
                    $table .= '<td>' . $report['test_result'] . '</td>';
                    $table .= '</tr>';
                    $i++;

                    $objPHPExcel->getActiveSheet()->setCellValue('A' . $i, ($i - 1));
                    $objPHPExcel->getActiveSheet()->setCellValue('B' . $i, ($report['gc_no']) ? $report['gc_no'] : '');
                    $objPHPExcel->getActiveSheet()->setCellValue('C' . $i, ($report['trf_ref_no']) ? $report['trf_ref_no'] : '');
                    $objPHPExcel->getActiveSheet()->setCellValue('D' . $i, ($report['client']) ? $report['client'] : '');
                    $objPHPExcel->getActiveSheet()->setCellValue('E' . $i, ($report['buyer']) ? $report['buyer'] : '');
                    $objPHPExcel->getActiveSheet()->setCellValue('F' . $i, ($report['status']) ? $report['status'] : '');
                    $objPHPExcel->getActiveSheet()->setCellValue('G' . $i, ($tat_date));
                    $objPHPExcel->getActiveSheet()->setCellValue('H' . $i, ($report['test_result']) ? $report['test_result'] : '');
                }
                $table .= '</tbody>';
                $table .= '</table>';

                $filename = LOCAL_PATH . 'sample_registration_details-' . date('Y-m-d-s') . rand(000, 999) . ".xls";
                $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
                $objWriter->save($filename);
                $message = '<p>Dear Sir,</p>';
                $message .= '<p>Kindly find weekly report of your sample as follows.</p>';
                $message .= $table;
                $message .= 'Please find same in excel format as attachment.';
                $message .= '<p>Regards</p>';
                $message .= '<p>BASIL</p>';
                $send = send_mail_while_Release_to_Client($to = 'developer.cps01@basilrl.com', $from = NULL, $cc = NULL, $bcc = NULL, $msg = $message, $sub = 'Sample Status Update', $attachment_file = $filename, $attachment_path = NULL, $report = false);
                if ($send) {
                    unlink($filename);
                    echo "Mail sent";
                }
            }
        }
    }
    // function to send status update to the customer ends here

    public function get_revenue()
    {
        $data = $this->sr->get_revenue();
        // echo "<pre>";print_r($data['revenue']); die;
        $this->load->library('excel');
        $tmpfname = "example.xls";

        $excelReader = PHPExcel_IOFactory::createReaderForFile($tmpfname);
        $objPHPExcel = $excelReader->load($tmpfname);

        $objPHPExcel->getProperties()->setCreator("GEO-CHEM")
            ->setLastModifiedBy("GEO-CHEM")
            ->setTitle("Sample Registration Status Update")
            ->setSubject("Sample Registration Status Update")
            ->setDescription("Sample Registration Status Update")
            ->setKeywords("Sample Registration Status Update")
            ->setCategory("Sample Registration Status Update");


        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getActiveSheet()->setCellValue('A1', "SL NO.");
        $objPHPExcel->getActiveSheet()->setCellValue('B1', "Year");
        $objPHPExcel->getActiveSheet()->setCellValue('C1', "Month");
        $objPHPExcel->getActiveSheet()->setCellValue('D1', "Revenue");


        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);

        $i = 2;
        foreach ($data['revenue'] as $revenue) {
            $objPHPExcel->getActiveSheet()->setCellValue('A' . $i, ($i - 1));
            $objPHPExcel->getActiveSheet()->setCellValue('B' . $i, ($revenue['year']) ? $revenue['year'] : '');
            $objPHPExcel->getActiveSheet()->setCellValue('C' . $i, ($revenue['month']) ? $revenue['month'] : '');
            $objPHPExcel->getActiveSheet()->setCellValue('D' . $i, ($revenue['amount']) ? $revenue['amount'] : '');
            $i++;
        }

        $i = $i + 3;
        $objPHPExcel->getActiveSheet()->setCellValue('A' . $i, "SL NO.");
        $objPHPExcel->getActiveSheet()->setCellValue('B' . $i, "Year");
        $objPHPExcel->getActiveSheet()->setCellValue('C' . $i, "Revenue");

        $i = $i + 1;

        foreach ($data['revenue_by_year'] as $key => $revenue) {
            $objPHPExcel->getActiveSheet()->setCellValue('A' . $i, ($key + 1));
            $objPHPExcel->getActiveSheet()->setCellValue('B' . $i, ($revenue['year']) ? $revenue['year'] : '');
            $objPHPExcel->getActiveSheet()->setCellValue('C' . $i, ($revenue['amount']) ? $revenue['amount'] : '');
            $i++;
        }

        $filename = 'revenue_details-' . date('Y-m-d-s') . rand(000, 999) . ".xls";
        ob_end_clean();
        header('Content-type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename=' . $filename);
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
    }

    public function get_customer_count()
    {
        $data = $this->sr->get_customer();
        $this->load->library('excel');
        $tmpfname = "example.xls";

        $excelReader = PHPExcel_IOFactory::createReaderForFile($tmpfname);
        $objPHPExcel = $excelReader->load($tmpfname);

        $objPHPExcel->getProperties()->setCreator("GEO-CHEM")
            ->setLastModifiedBy("GEO-CHEM")
            ->setTitle("Sample Registration Status Update")
            ->setSubject("Sample Registration Status Update")
            ->setDescription("Sample Registration Status Update")
            ->setKeywords("Sample Registration Status Update")
            ->setCategory("Sample Registration Status Update");


        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getActiveSheet()->setCellValue('A1', "Year");
        $objPHPExcel->getActiveSheet()->setCellValue('B1', "Customer Count");


        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);

        $i = 2;
        foreach ($data['customers'] as $cust) {
            $objPHPExcel->getActiveSheet()->setCellValue('A' . $i, ($cust['year']) ? $cust['year'] : '');
            $objPHPExcel->getActiveSheet()->setCellValue('B' . $i, ($cust['customer_count']) ? $cust['customer_count'] : '');
            $i++;
        }
        $filename = 'customer_count-' . date('Y-m-d-s') . rand(000, 999) . ".xls";
        ob_end_clean();
        header('Content-type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename=' . $filename);
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
    }


    // Save partial report count ans status
    // public function save_partial_report_status()
    // {
    //     $status = $this->input->post('partial_report_generate');
    //     $this->form_validation->set_rules('partial_report_generate', 'Status', 'required');
    //     if ($status == 1) {
    //         $this->form_validation->set_rules('partial_report_count', 'Count', 'required');
    //     }

    //     if ($this->form_validation->run()) {
    //         $sample_reg_id = $this->input->post('sample_reg_id');
    //         $count = $this->input->post('partial_report_count');

    //         $data_array = array(
    //             'partial_report_generate'   => $status,
    //             'partial_report_count'      => $count
    //         );

    //         $update = $this->sr->update_data('sample_registration', $data_array, ['sample_reg_id' => $sample_reg_id]);
    //         if ($update) {
    //             echo json_encode(['status' => 1, 'message' => 'Data saved successfully']);
    //         } else {
    //             echo json_encode(['status' => 0, 'message' => 'Something went wrong!.']);
    //         }
    //     } else {
    //         echo json_encode(["message" => "Something Went Wrong!.", "error" => $this->form_validation->error_array(), "status" => 0]);
    //     }
    // }
    // Save partial report count ans status

    // Partial / Revise report sample listing
    public function partial_revise_sample($page = 0, $trf = null, $customer_name = null, $product = null, $created_on = null, $ulr_no = null, $gc_number = null, $buyer = null, $status = null, $division = null, $style_no = null, $start_date = null, $end_date = null)
    {
        $checkUser = $this->session->userdata('user_data');
        $cust_where = NULL;
        $buyer_where = ['customer_type' => 'Buyer'];

        if (exist_val('Branch/Wise', $this->session->userdata('permission'))) {
            $multibranch = $this->session->userdata('branch_ids');
            $buyer_where['mst_branch_id IN (' . $multibranch . ') '] = null;
            $cust_where['mst_branch_id IN (' . $multibranch . ') '] = null;
        }
        $data['customer'] = $this->sr->get_result("customer_id,customer_name", "cust_customers", $cust_where);
        $data['buyer'] = $this->sr->get_result("customer_id,customer_name", "cust_customers", $buyer_where);
        $data['products'] = $this->sr->get_products();
        $data['sample_status'] = $this->sr->get_status();
        $data['division'] = $this->sr->get_fields("mst_divisions", "division_id,division_name");
        $per_page = "10";
        $page = $this->uri->segment(3);
        if ($page != 0) {
            $page = ($page - 1) * $per_page;
        } else {
            $page = 0;
        }

        $total_count = $this->sr->get_partial_sample_list($per_page, $page, $trf, $customer_name, $product, $created_on, $ulr_no, $gc_number, $buyer, $status, $division, $style_no, $start_date, $end_date, $count = true);
        $config['full_tag_open']    = '<div class="pagging text-center"><nav><ul class="pagination">';
        $config['full_tag_close']   = '</ul></nav></div>';
        $config['num_tag_open']     = '<li class="page-item"><span class="page-link">';
        $config['num_tag_close']    = '</span></li>';
        $config['cur_tag_open']     = '<li class="page-item active"><span class="page-link">';
        $config['cur_tag_close']    = '<span class="sr-only">(current)</span></span></li>';
        $config['next_tag_open']    = '<li class="page-item"><span class="page-link">';
        $config['next_tag_close']  = '<span aria-hidden="true"></span></span></li>';
        $config['prev_tag_open']    = '<li class="page-item"><span class="page-link">';
        $config['prev_tag_close']  = '</span></li>';
        $config['first_tag_open']   = '<li class="page-item"><span class="page-link">';
        $config['first_tag_close'] = '</span></li>';
        $config['last_tag_open']    = '<li class="page-item"><span class="page-link">';
        $config['last_tag_close']  = '</span></li>';
        $config['base_url'] = base_url() . "SampleRegistration_Controller/sample_register_listing";
        $config['use_page_numbers'] = TRUE;
        $config['uri_segment'] = 3;
        $config['total_rows'] = $total_count;
        $config['per_page'] = $per_page;
        $this->pagination->initialize($config);
        $data['pagination'] = $this->pagination->create_links();
        $data['partial_report'] = $this->sr->get_partial_sample_list($per_page, $page, $trf, $customer_name, $product, $created_on, $ulr_no, $gc_number, $buyer, $status, $division, $style_no, $start_date, $end_date);
        //   echo "<pre>"; print_r($data['partial_report']); die;
        $this->load_view('sample_registration/revise_sample', $data);
    }

    // Added by saurabh on 30-06-2021 to get all report_pdf
    public function get_all_report_pdf()
    {
        $sample_reg_id = $this->input->post('sample_reg_id');
        $data = $this->sr->get_all_report_pdf($sample_reg_id);
        echo json_encode($data);
    }

    // Save partial report count ans status updated on 30-06-2021 by millan
    public function save_partial_report_status()
    {
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
        $this->form_validation->set_rules('gc_number', 'Basil Report NUMBER', 'required|trim');
        $this->form_validation->set_rules('report_type', 'REPORT TYPE', 'required');
        $this->form_validation->set_rules('report_reason', 'REASON FOR OPTING REPORT TYPE', 'required|trim');
        if ($this->form_validation->run() == false) {
            $data = array(
                'error' => $this->form_validation->error_array(),
                'status' => 0,
                'msg'   => 'All fields are required'
            );
        } else {
            $checkUser = $this->session->userdata();
            $data_get = $this->input->post();
            $update_data = array();
            $update_data['report_type'] = $data_get['report_type'];
            // $update_data['report_reason'] = $data_get['report_reason'];
            $generated_by = $checkUser['user_data']->uidnr_admin;
            $generated_on = date('y-m-d h:i:s');
            $where['sample_reg_id'] = $data_get['sample_reg_id'];
            // $where['gc_no'] = $data_get['gc_number'];
            if (!empty($data_get)) {
                $get_gc = $this->sr->fetch_gc_data($data_get['gc_number']);
                if (empty($get_gc) && $get_gc == "") {
                    // $this->sr->update_data('sample_registration', $update_data, $where);
                    $params['data'] =  base_url('Render/download_report?report_number=' . base64_encode($data_get['gc_number']));
                    $this->load->library('Ciqrcode');
                    $params['level'] = 'H';
                    $params['size'] = 2;
                    $params['savename'] = LOCAL_PATH . $data_get['gc_number'] . '.png';
                    $this->ciqrcode->generate($params); // generate QR 
                    $filepath = basename($params['savename']);
                    $qrcode = $this->uploadQRcode($filepath);   // uploading QR ON AWS
                    if ($qrcode) {
                        if ($data_get['report_type'] == 1) {
                            $report_status = 'Revised Report Generated';
                        } else if ($data_get['report_type'] == 2) {
                            $report_status = 'Partial Report Generated';
                        }
                        $insert_gc = array('qr_code_name' => $qrcode['aws_path'], 'report_num' => $data_get['gc_number'], 'sample_reg_id' => $data_get['sample_reg_id'], 'generated_date' => $generated_on, 'generated_by' => $generated_by, 'report_reason' => $data_get['report_reason'], 'revise_report_type' => $data_get['report_type'], 'report_status' => $report_status, 'revise_report_num' => $data_get['gc_number']);
                        if ($insert_gc) {
                            $insert_rp =  $this->sr->insert_data('generated_reports', $insert_gc);
                            // echo $this->db->last_query();
                            if ($insert_rp) {
                                $this->session->set_flashdata('success', 'QR CODE Generated Successfully and data inserted successfully');
                                $data = array(
                                    'status' => 1,
                                    'msg' => 'QR CODE Generated Successfully and data inserted successfully'
                                );
                            } else {
                                $this->session->set_flashdata('error', 'Error in Storing Data');
                                $data = array(
                                    'status' => 0,
                                    'msg' => 'Error in Storing Data'
                                );
                            }
                        }
                    } else {
                        $this->session->set_flashdata('error', 'Error in Generating QR CODE');
                        $data = array(
                            'status' => 0,
                            'msg' => 'Error in Generating QR CODE'
                        );
                    }
                } else {
                    $this->session->set_flashdata('error', 'Change Basil Report Number before precedding further');
                    $data = array(
                        'status' => 0,
                        'msg' => 'Change Basil Report Number before precedding further'
                    );
                }
            } else {
                $this->session->set_flashdata('error', 'Data Not Found');
                $data = array(
                    'status' => 0,
                    'msg' => 'Data Not Found'
                );
            }
        }
        echo json_encode($data);
    }
    // Save partial report count ans status updated on 30-06-2021 by millan ends


    //function to export flipkart report upload date, added by saurabh on 20-09-2021
    public function export_flipkart_data()
    {
        $this->db->select('sr.gc_no, sr.ulr_no, sr.status, sr.comment, sr.released_to_client, sample_type_name, tr.trf_ref_no, customer.customer_name as customer, buyer.customer_name as buyer,tr.tat_date, admin_fname as created_by, sr.due_date, sr.comment, CONCAT(trf_service_type," ",service_days) as trf_service_type , sample_desc, generated_date, DATE_FORMAT(sr.create_on, "%d-%b-%Y") as created_on');
        $this->db->from('sample_registration sr');
        $this->db->join('trf_registration tr', 'sr.trf_registration_id = tr.trf_id');
        $this->db->join('mst_sample_types', 'sample_type_id = sr.sample_registration_sample_type_id');
        $this->db->join('cust_customers customer', 'customer.customer_id=tr.trf_applicant', 'left');
        $this->db->join('cust_customers buyer', 'buyer.customer_id=tr.trf_buyer', 'left');
        $this->db->join('admin_profile', 'sr.create_by = uidnr_admin','left');
        $this->db->join('generated_reports as gr', 'gr.sample_reg_id = sr.sample_reg_id AND (gr.additional_report_flag <> 1 AND  gr.revise_report <> "1" )', 'left');
        $this->db->order_by('sr.sample_reg_id', 'desc');
        $this->db->group_by('sr.sample_reg_id');
        $this->db->where('trf_applicant', '633');
        $this->db->where('trf_buyer', '634');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $data = $query->result();
            if ($data && count($data) > 0) {
                $this->load->library('excel');
                $tmpfname = "example.xls";

                $excelReader = PHPExcel_IOFactory::createReaderForFile($tmpfname);
                $objPHPExcel = $excelReader->load($tmpfname);

                $objPHPExcel->getProperties()->setCreator("GEO-CHEM")
                    ->setLastModifiedBy("GEO-CHEM")
                    ->setTitle("Office 2007 XLS Sample Registration Document")
                    ->setSubject("Office 2007 XLS Sample Registration Document")
                    ->setDescription("Description for Sample Registration Document")
                    ->setKeywords("phpexcel office codeigniter php")
                    ->setCategory("Sample Registration details file");


                $objPHPExcel->setActiveSheetIndex(0);
                $objPHPExcel->getActiveSheet()->setCellValue('A1', "SL NO.");
                $objPHPExcel->getActiveSheet()->setCellValue('B1', "Basil Report Number");
                $objPHPExcel->getActiveSheet()->setCellValue('C1', "ULR No.");
                $objPHPExcel->getActiveSheet()->setCellValue('D1', "Product");
                $objPHPExcel->getActiveSheet()->setCellValue('E1', "TRF Reference No.");
                $objPHPExcel->getActiveSheet()->setCellValue('F1', "Customer");
                $objPHPExcel->getActiveSheet()->setCellValue('G1', "Buyer");
                $objPHPExcel->getActiveSheet()->setCellValue('H1', "Status");
                $objPHPExcel->getActiveSheet()->setCellValue('I1', "Created By");
                $objPHPExcel->getActiveSheet()->setCellValue('J1', "Created On");
                $objPHPExcel->getActiveSheet()->setCellValue('K1', "Due Date");
                $objPHPExcel->getActiveSheet()->setCellValue('L1', "Report Upload Date");
                $objPHPExcel->getActiveSheet()->setCellValue('M1', "Tat Date");
                $objPHPExcel->getActiveSheet()->setCellValue('N1', "Comment");
                $objPHPExcel->getActiveSheet()->setCellValue('O1', "TRF Service Type");
                $objPHPExcel->getActiveSheet()->setCellValue('P1', "Sample Description");

                $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('N')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('O')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('P')->setAutoSize(true);



                $i = 2;
                foreach ($data as $key => $value) {

                    $objPHPExcel->getActiveSheet()->setCellValue('A' . $i, ($i - 1));
                    $objPHPExcel->getActiveSheet()->setCellValue('B' . $i, ($value->gc_no) ? $value->gc_no : '');
                    $objPHPExcel->getActiveSheet()->setCellValue('C' . $i, ($value->ulr_no) ? $value->ulr_no : '');
                    $objPHPExcel->getActiveSheet()->setCellValue('D' . $i, ($value->sample_type_name) ? $value->sample_type_name : '');
                    $objPHPExcel->getActiveSheet()->setCellValue('E' . $i, ($value->trf_ref_no) ? $value->trf_ref_no : '');
                    $objPHPExcel->getActiveSheet()->setCellValue('F' . $i, ($value->customer) ? $value->customer : '');
                    $objPHPExcel->getActiveSheet()->setCellValue('G' . $i, ($value->buyer) ? $value->buyer : '');
                    $objPHPExcel->getActiveSheet()->setCellValue('H' . $i, ($value->status) ? $value->status : '');
                    $objPHPExcel->getActiveSheet()->setCellValue('I' . $i, ($value->created_by) ? $value->created_by : '');
                    $objPHPExcel->getActiveSheet()->setCellValue('J' . $i, ($value->created_on) ? $value->created_on : '');
                    $objPHPExcel->getActiveSheet()->setCellValue('K' . $i, ($value->due_date) ? $value->due_date : '');
                    $objPHPExcel->getActiveSheet()->setCellValue('L' . $i, ($value->generated_date) ? $value->generated_date : '');
                    $objPHPExcel->getActiveSheet()->setCellValue('M' . $i, ($value->tat_date) ? $value->tat_date : '');
                    $objPHPExcel->getActiveSheet()->setCellValue('N' . $i, ($value->comment) ? $value->comment : '');
                    $objPHPExcel->getActiveSheet()->setCellValue('O' . $i, ($value->trf_service_type) ? $value->trf_service_type : '');
                    $objPHPExcel->getActiveSheet()->setCellValue('P' . $i, ($value->sample_desc) ? $value->sample_desc : '');
                    $i++;
                }

                $filename = 'sample_registration_details-' . date('Y-m-d-s') . ".xls";
                $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
                ob_end_clean();
                header('Content-type: application/vnd.ms-excel');
                header('Content-Disposition: attachment; filename=' . $filename);
                $objWriter->save('php://output');
            }
        }
    }
    //function to export flipkart report upload date, added by saurabh on 20-09-2021

    // Added by Saurabh on 08-11-2021 to change sample status
    public function change_status(){
        $sample_reg_id = $this->input->post('sample_reg_id');
        $sample_status = $this->input->post('sample_status');
        $old_status_query = $this->db->select('status')->from('sample_registration')->where('sample_reg_id',$sample_reg_id)->get();
        $old_status = $old_status_query->row()->status;
        $update = $this->db->update('sample_registration',['status' => $sample_status],['sample_reg_id' => $sample_reg_id]);
        if($sample_status == "Send For Record Finding"){
            $data = array('status' => 'Record Enter Done');
            $update_test = $this->db->where('sample_test_sample_reg_id', $sample_reg_id)
                ->update('sample_test', $data);
        }
        if($update){
            $logDetails = array(
                'module' => 'Samples',
                'old_status' => $old_status,
                'new_status' => $sample_status,
                'sample_reg_id' => $sample_reg_id,
                'sample_assigned_lab_id' => '',
                'action_message' => 'Updated sample status',
                'report_id' => '',
                'report_status' => '',
                'test_ids' => '',
                'test_names' => '',
                'test_newstatus' => '',
                'test_oldStatus' => '',
                'test_assigned_to' => '',
                'uidnr_admin'   => $this->admin_id(),
                'operation' => 'change_status',
                'log_activity_on' => date("Y-m-d H:i:s")
            );
            
            $this->sr->save_user_log($logDetails);
            echo json_encode(['status' => 1, 'message' => 'The sample status is updated successfully.']);
        } else {
            echo json_encode(['status' => 0, 'message' => 'Something went wrong!.']);
        }
    }

    public function generate_barcode()
    {
        // Get gc no for the sample registration
        $sample_reg_id = $this->input->post('sample_reg_id');
        $sample_data = $this->db->select('gc_no')->where('sample_reg_id', $sample_reg_id)->get('sample_registration')->row_array();
        $unique = $sample_data['gc_no'];
        $filepath = base_url() . "assets/barcode/";
        @chmod($filepath, 0755);
        $localpath = "assets/barcode/";
        $text = $unique;
        $size = "30";
        $orientation = "horizontal";
        $code_type = "code128";
        $print = true;
        $sizefactor = "1";
        $text = str_replace('/', '-', $text);
        $image_data = barcode($filepath, $text, $size, $orientation, $code_type, $print, $sizefactor);
        $filename = $text . ".png";
        $file = $this->sr->uploadBarcode($filename);
        $barcode_file = $file['aws_path'];

        $record['barcode_no'] = $filename;
        $record['barcode_path'] = $barcode_file;

        if (!empty($barcode_file)) {
            $update = $this->db->update('sample_registration', $record, ['sample_reg_id' => $sample_reg_id]);
            if ($update) {
                echo json_encode(['status' => 1, 'message' => 'Barcode generated successfully']);
            } else {
                echo json_encode(['status' => 0, 'message' => 'Something went wrong!.']);
            }
        } else {
            echo json_encode(['status' => 0, 'message' => 'Something went wrong!.']);
        }
    }

    // sample clone changes
    public function getDueDateByServiceType(){
        $service_type = $this->input->post('service_type');
        $service_type_text = $this->input->post('service_type_text');
        $receivedate = date("Y-m-d H:i:s");


            if ($service_type == 1) {
                $includingToday = 2;
                $due_date = $this->sr->calculateDueDate(date('Y-m-d H:i', strtotime($receivedate)), $includingToday);
            } elseif ($service_type == 2) {
                $includingToday = 1;
                $due_date = $this->sr->calculateDueDate(date('Y-m-d H:i', strtotime($receivedate)), $includingToday);
            } else if ($service_type == 3) {
                $includingToday = 2;
                $due_date = $this->sr->calculateDueDate(date('Y-m-d H:i', strtotime($receivedate)), $includingToday);
            }  else if ($service_type == 4) {
                $includingToday = 0;
                $due_date = $this->sr->calculateDueDate(date('Y-m-d H:i', strtotime(date('Y-m-d H:i:s'))), $includingToday);
            } else if ($service_type == 17) {
                $includingToday = 0;
                $due_date = $this->sr->calculateDueDate(date('Y-m-d H:i', strtotime($receivedate)), $includingToday, true);
            }else{
               $num = filter_var($service_type_text, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                $includingToday = $num  - 1;
                $due_date = $this->sr->calculateDueDate(date('Y-m-d H:i', strtotime($receivedate)), $includingToday, false);
            }
            
            $due_date = date("Y-m-d", strtotime($due_date));
            echo json_encode(['status' => 1, 'data' => $due_date]);
         
    }

      public function markedInvoice(){
        $sample_reg_id = $this->input->post('sample_reg_id');
        $res = $this->db->where('sample_reg_id',$sample_reg_id)->update('sample_registration',['marked_invoice'=>'2']);
        if($res){
              echo json_encode(['status' => 1, 'message' => 'The sample invoice is updated successfully.']);
        } else {
            echo json_encode(['status' => 0, 'message' => 'Something went wrong!.']);
        }
    }
}
