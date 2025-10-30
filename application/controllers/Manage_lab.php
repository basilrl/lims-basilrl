<?php

use Mpdf\Tag\Em;

class Manage_lab extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('Manage_lab_model', 'mlm');
        $this->check_session();
        $checkUser = $this->session->userdata('user_data');
        $this->user = $checkUser->uidnr_admin;
    }
    public function index($applicant = 'NULL', $buyer = 'NULL', $divison = 'NULL', $product = 'NULL', $search_gc = 'NULL', $search_trf = 'NULL', $start_date = 'NULL', $end_date = 'NULL', $stauts = 'NULL')
    {
        //$this->output->enable_profiler(true);
        $where = array();
        $search_trf1 = $search_gc1 = NULL;
        // echo $applicant . " " . $product . " " . $search . " " . $start_date . " " . $end_date . " " . $stauts;
        $base_url = base_url() . "Manage_lab/index";
        $base_url .= '/' . (($applicant != 'NULL') ? $applicant : 'NULL');
        $base_url .= '/' . (($buyer != 'NULL') ? $buyer : 'NULL');
        $base_url .= '/' . (($divison != 'NULL') ? $divison : 'NULL');
        $base_url .= '/' . (($product != 'NULL') ? $product : 'NULL');
        $base_url .= '/' . (($search_gc != 'NULL') ? ($search_gc) : 'NULL');
        $base_url .= '/' . (($search_trf != 'NULL') ? ($search_trf) : 'NULL');
        $base_url .= '/' . (($start_date != 'NULL') ? $start_date : 'NULL');
        $base_url .= '/' . (($end_date != 'NULL') ? $end_date : 'NULL');
        $base_url .= '/' . (($stauts != 'NULL') ? ($stauts) : 'NULL');
        $data['applicant_id'] = ($applicant != 'NULL') ? $applicant : 'NULL';
        $data['buyer_id'] = ($buyer != 'NULL') ? $buyer : 'NULL';
        $data['product_id'] = ($product != 'NULL') ? $product : 'NULL';
        $data['search_gc'] = ($search_gc != 'NULL') ? base64_decode($search_gc) : 'NULL';
        $data['search_trf'] = ($search_trf != 'NULL') ? base64_decode($search_trf) : 'NULL';
        $data['start_date'] = ($start_date != 'NULL') ? $start_date : 'NULL';
        $data['end_date'] = ($end_date != 'NULL') ? $end_date : 'NULL';
        $data['stauts'] = ($stauts != 'NULL') ? base64_decode($stauts) : 'NULL';
        if ($applicant != 'NULL') {
            $customer = $this->mlm->get_row('customer_name', 'cust_customers', ['customer_id' => $applicant]);
            if ($customer) {
                $data['applicant_name'] = $customer->customer_name;
            } else {
                $data['applicant_name'] = 'NULL';
            }
        } else {
            $data['applicant_name'] = 'NULL';
        }

        if ($buyer != 'NULL') {
            $customer = $this->mlm->get_row('customer_name', 'cust_customers', ['customer_id' => $buyer]);
            if ($customer) {
                $data['buyer_name'] = $customer->customer_name;
            } else {
                $data['buyer_name'] = 'NULL';
            }
        } else {
            $data['buyer_name'] = 'NULL';
        }

        if ($product != 'NULL') {
            $customer = $this->mlm->get_row('sample_type_name', 'mst_sample_types', ['sample_type_id' => $product]);
            if ($customer) {
                $data['product_name'] = $customer->sample_type_name;
            } else {
                $data['product_name'] = 'NULL';
            }
        } else {
            $data['product_name'] = 'NULL';
        }
        if ($applicant != 'NULL') {
            $where['tr.trf_applicant'] = $applicant;
        }
        if ($buyer != 'NULL') {
            $where['tr.trf_buyer'] = $buyer;
        }
        if ($divison != 'NULL') {
            $where['sr.division_id'] = $divison;
            $data['divison'] = $divison;
        } else {
            $data['divison'] = 'NULL';
        }
        if ($product != 'NULL') {
            $where['sr.sample_registration_sample_type_id'] = $product;
        }
        if ($search_gc != 'NULL') {
            $search_gc1 = trim(base64_decode($search_gc));
        }
        if ($search_trf != 'NULL') {
            $search_trf1 = trim(base64_decode($search_trf));
        }
        if ($start_date != 'NULL') {
            $where['sr.received_date >='] = ($start_date);
        }
        if ($end_date != 'NULL') {
            $where['sr.received_date <='] = ($end_date);
        }
        if ($stauts != 'NULL') {
            $where['sr.status'] = base64_decode($stauts);
        }
        $this->load->library('pagination');
        $config = array();
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
        $config["total_rows"] = $this->mlm->accepted_sample_list(NULL, NULL, $where, $search_gc1, $search_trf1, '1');
        $config["per_page"] = 10;
        $config["uri_segment"] = 12;
        $config["base_url"] = $base_url;
        $config1 = $this->pagination->initialize($config);
        $page = ($this->uri->segment(12)) ? $this->uri->segment(12) : 0;
        $data['accepted_sample'] = $this->mlm->accepted_sample_list($config1->per_page, $page, $where, $search_gc1, $search_trf1);
        $data['divisions'] = $this->mlm->get_default_division();
        $total_count = $this->mlm->accepted_sample_list(NULL, NULL, $where, $search_gc1, $search_trf1, '1');
        if ($total_count > 0) {
            $start = (int)$page + 1;
            $end = (($data['accepted_sample']) ? count($data['accepted_sample']) : 0) + (($page) ? $page : 0);
            $data['result_count'] = "Showing " . $start . " - " . $end . " of " . $total_count . " Results";
        } else {
            $data['result_count'] = '';
        }
        $data["links"] = $this->pagination->create_links();
        $this->load_view('manage_lab/accepted_sample', $data);
    }
    public function Assign_test()
    {
        $sample_reg_id = $this->input->post('sample_reg_id');
        $result['head'] =  $this->mlm->get_assign_head($sample_reg_id);
        $result['test'] =  $this->mlm->get_assign_test($sample_reg_id);
        $result['analysis'] = $this->mlm->get_analysis();
        echo json_encode($result);
    }

    /* added by millan on 20-07-2021 */
    public function manual_report_listing($applicant = 'NULL', $buyer = 'NULL', $divison = 'NULL', $product = 'NULL', $search_gc = 'NULL', $search_trf = 'NULL', $start_date = 'NULL', $end_date = 'NULL')
    {
        $where = array();
        $search_trf1 = $search_gc1 = NULL;
        $base_url = "Manage_lab/manual_report_listing";
        $base_url .= '/' . (($applicant != 'NULL') ? $applicant : 'NULL');
        $base_url .= '/' . (($buyer != 'NULL') ? $buyer : 'NULL');
        $base_url .= '/' . (($divison != 'NULL') ? $divison : 'NULL');
        $base_url .= '/' . (($product != 'NULL') ? $product : 'NULL');
        $base_url .= '/' . (($search_gc != 'NULL') ? ($search_gc) : 'NULL');
        $base_url .= '/' . (($search_trf != 'NULL') ? ($search_trf) : 'NULL');
        $base_url .= '/' . (($start_date != 'NULL') ? $start_date : 'NULL');
        $base_url .= '/' . (($end_date != 'NULL') ? $end_date : 'NULL');
        $data['applicant_id'] = ($applicant != 'NULL') ? $applicant : 'NULL';
        $data['buyer_id'] = ($buyer != 'NULL') ? $buyer : 'NULL';
        $data['product_id'] = ($product != 'NULL') ? $product : 'NULL';
        $data['search_gc'] = ($search_gc != 'NULL') ? base64_decode($search_gc) : 'NULL';
        $data['search_trf'] = ($search_trf != 'NULL') ? base64_decode($search_trf) : 'NULL';
        $data['start_date'] = ($start_date != 'NULL') ? $start_date : 'NULL';
        $data['end_date'] = ($end_date != 'NULL') ? $end_date : 'NULL';
        if ($applicant != 'NULL') {
            $customer = $this->mlm->get_row('customer_name', 'cust_customers', ['customer_id' => $applicant]);
            if ($customer) {
                $data['applicant_name'] = $customer->customer_name;
            } else {
                $data['applicant_name'] = 'NULL';
            }
        } else {
            $data['applicant_name'] = 'NULL';
        }

        if ($buyer != 'NULL') {
            $customer = $this->mlm->get_row('customer_name', 'cust_customers', ['customer_id' => $buyer]);
            if ($customer) {
                $data['buyer_name'] = $customer->customer_name;
            } else {
                $data['buyer_name'] = 'NULL';
            }
        } else {
            $data['buyer_name'] = 'NULL';
        }

        if ($product != 'NULL') {
            $customer = $this->mlm->get_row('sample_type_name', 'mst_sample_types', ['sample_type_id' => $product]);
            if ($customer) {
                $data['product_name'] = $customer->sample_type_name;
            } else {
                $data['product_name'] = 'NULL';
            }
        } else {
            $data['product_name'] = 'NULL';
        }
        if ($applicant != 'NULL') {
            $where['tr.trf_applicant'] = $applicant;
        }
        if ($buyer != 'NULL') {
            $where['tr.trf_buyer'] = $buyer;
        }
        if ($divison != 'NULL') {
            $where['sr.division_id'] = $divison;
            $data['divison'] = $divison;
        } else {
            $data['divison'] = 'NULL';
        }
        if ($product != 'NULL') {
            $where['sr.sample_registration_sample_type_id'] = $product;
        }
        if ($search_gc != 'NULL') {
            $search_gc1 = trim(base64_decode($search_gc));
        }
        if ($search_trf != 'NULL') {
            $search_trf1 = trim(base64_decode($search_trf));
        }
        if ($start_date != 'NULL') {
            $where['date(sr.received_date) >='] = ($start_date); //Updated by Saurabh on 15-03-2022
        }
        if ($end_date != 'NULL') {
            $where['date(sr.received_date) <='] = ($end_date); //Updated by Saurabh on 15-03-2022
        }
        $total_row = $this->mlm->manual_report_listing(NULL, NULL, $where,  $search_gc1, $search_trf1, '1');

        $page = ($this->uri->segment(11)) ? $this->uri->segment(11) : 0;
        $config = $this->pagination($base_url, $total_row, 10, 11);

        $data["links"] = $config["links"];
        $data['divisions'] = $this->mlm->get_default_division();

        $data['report_listing'] =  $this->mlm->manual_report_listing($config["per_page"], $page, $where,  $search_gc1, $search_trf1);
        if ($total_row > 0) {
            $start = (int)$page + 1;
        } else {
            $start = 0;
        }
        $end = (($data['report_listing']) ? count($data['report_listing']) : 0) + (($page) ? $page : 0);
        $data['result_count'] = "Showing " . $start . " - " . $end . " of " . $total_row . " Results";
        $this->load_view('manage_lab/manual_report_listing', $data);
    }
    /* ends */

    public function SaveAssignTest()
    {
        $data = $this->input->post();
        $result = $this->mlm->SaveAssignTest($data);
        if ($result) {
            $this->session->set_flashdata('success', 'Task Assigned Successfully');
            redirect($_SERVER['HTTP_REFERER']);
        } else {
            $this->session->set_flashdata('false', 'Something went wrong!.');
        }
    }


    public function record_finding($applicant = 'NULL', $buyer = 'NULL', $divison = 'NULL', $product = 'NULL', $search_gc = 'NULL', $search_trf = 'NULL', $start_date = 'NULL', $end_date = 'NULL')
    {
        $where = array();
        $search_trf1 = $search_gc1 = NULL;
        $base_url = "Manage_lab/record_finding";
        $base_url .= '/' . (($applicant != 'NULL') ? $applicant : 'NULL');
        $base_url .= '/' . (($buyer != 'NULL') ? $buyer : 'NULL');
        $base_url .= '/' . (($divison != 'NULL') ? $divison : 'NULL');
        $base_url .= '/' . (($product != 'NULL') ? $product : 'NULL');
        $base_url .= '/' . (($search_gc != 'NULL') ? ($search_gc) : 'NULL');
        $base_url .= '/' . (($search_trf != 'NULL') ? ($search_trf) : 'NULL');
        $base_url .= '/' . (($start_date != 'NULL') ? $start_date : 'NULL');
        $base_url .= '/' . (($end_date != 'NULL') ? $end_date : 'NULL');
        // $base_url .= '/' . (($stauts != 'NULL') ? ($stauts) : 'NULL');
        $data['applicant_id'] = ($applicant != 'NULL') ? $applicant : 'NULL';
        $data['buyer_id'] = ($buyer != 'NULL') ? $buyer : 'NULL';
        $data['product_id'] = ($product != 'NULL') ? $product : 'NULL';
        $data['search_gc'] = ($search_gc != 'NULL') ? base64_decode($search_gc) : 'NULL';
        $data['search_trf'] = ($search_trf != 'NULL') ? base64_decode($search_trf) : 'NULL';
        $data['start_date'] = ($start_date != 'NULL') ? $start_date : 'NULL';
        $data['end_date'] = ($end_date != 'NULL') ? $end_date : 'NULL';
        // $data['stauts'] = ($stauts != 'NULL') ? base64_decode($stauts) : 'NULL';
        if ($applicant != 'NULL') {
            $customer = $this->mlm->get_row('customer_name', 'cust_customers', ['customer_id' => $applicant]);
            if ($customer) {
                $data['applicant_name'] = $customer->customer_name;
            } else {
                $data['applicant_name'] = 'NULL';
            }
        } else {
            $data['applicant_name'] = 'NULL';
        }

        if ($buyer != 'NULL') {
            $customer = $this->mlm->get_row('customer_name', 'cust_customers', ['customer_id' => $buyer]);
            if ($customer) {
                $data['buyer_name'] = $customer->customer_name;
            } else {
                $data['buyer_name'] = 'NULL';
            }
        } else {
            $data['buyer_name'] = 'NULL';
        }

        if ($product != 'NULL') {
            $customer = $this->mlm->get_row('sample_type_name', 'mst_sample_types', ['sample_type_id' => $product]);
            if ($customer) {
                $data['product_name'] = $customer->sample_type_name;
            } else {
                $data['product_name'] = 'NULL';
            }
        } else {
            $data['product_name'] = 'NULL';
        }
        if ($applicant != 'NULL') {
            $where['tr.trf_applicant'] = $applicant;
        }
        if ($buyer != 'NULL') {
            $where['tr.trf_buyer'] = $buyer;
        }
        if ($divison != 'NULL') {
            $where['sr.division_id'] = $divison;
            $data['divison'] = $divison;
        } else {
            $data['divison'] = 'NULL';
        }
        if ($product != 'NULL') {
            $where['sr.sample_registration_sample_type_id'] = $product;
        }
        if ($search_gc != 'NULL') {
            $search_gc1 = trim(base64_decode($search_gc));
        }
        if ($search_trf != 'NULL') {
            $search_trf1 = trim(base64_decode($search_trf));
        }
        if ($start_date != 'NULL') {
            $where['sr.received_date >='] = ($start_date);
        }
        if ($end_date != 'NULL') {
            $where['sr.received_date <='] = ($end_date);
        }
        $total_row = $this->mlm->record_finding_list(NULL, NULL, $where, $search_gc1, $search_trf1, '1');

        $page = ($this->uri->segment(11)) ? $this->uri->segment(11) : 0;
        $config = $this->pagination($base_url, $total_row, 10, 11);

        $data["links"] = $config["links"];
        $data['list'] = $this->mlm->record_finding_list($config["per_page"], $page, $where, $search_gc1, $search_trf1);
        $start = ($data['list']) ? (int)$page + 1 : 0;
        $end = (($data['list']) ? count($data['list']) : 0) + (($page) ? $page : 0);
        $data['result_count'] = "Showing " . $start . " - " . $end . " of " . $total_row . " Results";
        $data['divisions'] = $this->mlm->get_default_division();
        $this->load_view('manage_lab/record_finding', $data);
    }

    public function open_record_finding($id, $sample_test_id, $branch_id)
    {
        $data['sample_reg_id'] = base64_decode($id);
        $data['sample_test_id'] = base64_decode($sample_test_id);
        $data['branch_id'] = base64_decode($branch_id);
        $data['details'] = $this->mlm->get_sample_gc($data);
        $data['part'] = $this->mlm->get_parts($data);
        $data['test'] = $this->mlm->get_test($data);
        // Added by CHANDAN --16-05-2022 
        $data['get_parameters'] = '';
        if (!empty($data['test']->test_id)) {
            $data['get_parameters'] = $this->mlm->parameter_details($data['test']->test_id);
        }
        // End....
        // Added by Saurabh on 05-10-2021 to get selected test 
        $data['sample_selected_test'] = $this->mlm->get_sample_selected_test($data['sample_reg_id']);
        //echo "<pre>"; print_r($data); die;
        $this->load_view('manage_lab/record_finding_page', $data);
    }

    public function add_lab_details()
    {
        $checkUser = $this->session->userdata('user_data');

        $data = $this->input->post();
        $details = array("lab_name" => $data['lab_name'], "lab_address" => $data['address'], "created_by" => $checkUser->uidnr_admin);
        $data = $this->mlm->insert_data('sub_contract_lab_details', $details);
        if ($data > 0) {
            echo json_encode(array("status" => TRUE));
        } else {
            echo json_encode(array("status" => FALSE));
        }
    }
    public function Lab_completion()
    {
        $id = $this->input->post();
        $lab_data['id'] = $id['sample_reg_id'];
        // $lab_data['sample_test_id'] = $id['sample_test_id'];
        $date = date("Y/m/d h:i:sa");

        $lab_data['data'] = array(
            'lab_completion_date_time' => $date,
            'status' => 'Send For Record Finding',
        );

        $status =  $this->mlm->lab_completion($lab_data);
        if ($status) {
            $old_status_query = $this->db->select('status')->from('sample_registration')->where('sample_reg_id', $id['sample_reg_id'])->get();
            $old_status = $old_status_query->row()->status;
            $logDetails = array(
                'module' => 'Samples',
                'old_status' => $old_status,
                'new_status' => '',
                'sample_reg_id' => $id['sample_reg_id'],
                'sample_assigned_lab_id' => /* $lab_id, */ '',
                'action_message' => 'Marked as Completed From Lab',
                'sample_job_id' => '',
                'report_id' => '',
                'report_status' => '',
                'test_ids' => '',
                'test_names' => '',
                'test_newstatus' => '',
                'test_oldStatus' => '',
                'test_assigned_to' => '',
                'source_module'    => 'Manage_lab',
                'operation'        => 'Lab_completion',
                'uidnr_admin'    => $this->session->userdata('user_data')->uidnr_admin,
                'log_activity_on' => date("Y-m-d H:i:s")
            );
            $this->mlm->save_user_log($logDetails);
            $msg = array(
                'status' => 1,
                'msg' => 'MarK as Completed From Lab'
            );
        } else {
            $msg = array(
                'status' => 1,
                'msg' => 'MarK as not Completed From Lab'
            );
        }
        echo json_encode($msg);
    }

    public function report_generation()
    {
        $data = $this->input->post();
        if ($data) {
            $getRFD = $this->mlm->check_sequence_no(null, $data['sample_registration_id'], $data['sequence_no']);
            if ($getRFD) {
                $msg = array(
                    'status' => 0,
                    'msg' => "Sequence No. should be Unique!"
                );
            } else {
                if (array_key_exists('non_nabl_table', $data)) {
                    $post['non_nabl_headings'] =  $data['non_nabl_table'];
                } else {
                    $post['non_nabl_headings'] = '';
                }
                if (array_key_exists('nabl_table', $data)) {
                    $post['nabl_headings'] =  $data['nabl_table'];
                } else {
                    $post['nabl_headings'] = '';
                }

                $post['created_by'] = $this->user;
                $post['sample_registration_id'] = $data['sample_registration_id'];
                $post['sample_test_id'] = $data['sample_test_id'];
                $post['test_result'] = $data['test_result'];
                if ($data['nabl_remark'] != '' && $data['nabl_remark'] != NULL) {
                    $post['nabl_remark'] = base64_encode(htmlentities($data['nabl_remark']));
                } else {
                    $post['nabl_remark'] = '';
                }
                if ($data['non_nabl_remark'] != '' && $data['non_nabl_remark'] != NULL) {
                    $post['non_nabl_remark'] = base64_encode(htmlentities($data['non_nabl_remark']));
                } else {
                    $post['non_nabl_remark'] = '';
                }
                if ($data['nabl_detail'] != '' && $data['nabl_detail'] != NULL) {
                    $post['nabl_detail'] = base64_encode(htmlentities($data['nabl_detail']));
                } else {
                    $post['nabl_detail'] = '';
                }
                if ($data['non_nabl_detail'] != '' && $data['non_nabl_detail'] != NULL) {
                    $post['non_nabl_detail'] = base64_encode(htmlentities($data['non_nabl_detail']));
                } else {
                    $post['non_nabl_detail'] = '';
                }
                $post['test_display_name'] = $data['test_display_name'];
                $post['test_display_method'] = $data['test_display_method'];
                $post['test_component'] = $data['test_component'];
                if (empty($data['test_name_type'])) {
                    unset($data['test_name_type']);
                } else {
                    $post['test_name_type'] = $data['test_name_type'];
                }
                if (empty($data['lab_id'])) {
                    unset($data['lab_id']);
                } else {
                    $post['lab_id'] = $data['lab_id'];
                }
                if (array_key_exists('nabl_table', $data) || !empty($post['nabl_detail']) || !empty($post['nabl_remark'])) {
                    $post['test_type'] = 'NABL';
                } else if (array_key_exists('non_nabl_table', $data) || !empty($post['non_nabl_detail']) || !empty($post['non_nabl_remark'])) {
                    $post['test_type'] = 'NON-NABL';
                } else {
                    $post['test_type'] = '';
                }
                $post['sequence_no'] = $data['sequence_no'];
                $post['result_entry'] = $data['result_entry'];
                $check_ids = $this->mlm->check_record_finding($post);
                if ($check_ids && $check_ids->record_finding_id) {
                    $result = $this->mlm->update_data('record_finding_details', $post, array('record_finding_id' => $check_ids->record_finding_id));
                    $status = $this->db->where('sample_test_id', $post['sample_test_id'])->update('sample_test', array('status' => 'Record Enter Done'));
                    $record_finding_id = $check_ids->record_finding_id;
                } else {
                    $result = $this->mlm->report_generation($post);
                    $record_finding_id = $result;
                }

                // Added by CHANDAN --16-05-2022
                if (!empty($record_finding_id) && isset($data['head_parameter_name']) && !empty($data['head_parameter_name'])) {

                    $para_heading_data = array(
                        'branch_id'             => $data['branch_id'],
                        'record_finding_id'     => $record_finding_id,
                        'sample_test_id'        => isset($data['head_sample_test_id']) ? $data['head_sample_test_id'] : NULL,
                        'sample_reg_id'         => isset($data['head_sample_reg_id']) ? $data['head_sample_reg_id'] : NULL,
                        'test_id'               => isset($data['head_test_id']) ? $data['head_test_id'] : NULL,
                        'clouse'                => isset($data['head_clouse']) ? $data['head_clouse'] : NULL,
                        'parameter_name'        => isset($data['head_parameter_name']) ? $data['head_parameter_name'] : NULL,
                        'category'              => isset($data['head_category']) ? $data['head_category'] : NULL,
                        'limitation'            => isset($data['head_limitation']) ? $data['head_limitation'] : NULL,
                        'requirement'           => isset($data['head_requirement']) ? $data['head_requirement'] : NULL,
                        'priority_order'        => isset($data['head_priority_order']) ? $data['head_priority_order'] : NULL,
                        'result_1'              => isset($data['head_result_1']) ? $data['head_result_1'] : NULL,
                        'result_2'              => isset($data['head_result_2']) ? $data['head_result_2'] : NULL,
                        'result_3'              => isset($data['head_result_3']) ? $data['head_result_3'] : NULL,
                        'result_4'              => isset($data['head_result_4']) ? $data['head_result_4'] : NULL,
                        'result_5'              => isset($data['head_result_5']) ? $data['head_result_5'] : NULL,
                        'notes'                 => isset($data['notes']) ? base64_encode(htmlentities($data['notes'])) : NULL,
                        'is_deleted'            => 0,
                        'created_by'            => $this->session->userdata('user_data')->uidnr_admin,
                        'created_on'            => date("Y-m-d H:i:s")
                    );
                    $this->db->insert('record_finding_parameters_heading', $para_heading_data);

                    for ($k = 0; $k < count($data['parameter_name']); $k++) {
                        $para_data[] = array(
                            'branch_id'         => $data['branch_id'],
                            'record_finding_id' => $record_finding_id,
                            'sample_test_id'    => isset($data['head_sample_test_id']) ? $data['head_sample_test_id'] : NULL,
                            'sample_reg_id'     => isset($data['head_sample_reg_id']) ? $data['head_sample_reg_id'] : NULL,
                            'test_id'           => isset($data['head_test_id']) ? $data['head_test_id'] : NULL,
                            'test_parameters_id' => isset($data['test_parameters_id'][$k]) ? $data['test_parameters_id'][$k] : NULL,
                            'clouse'            => isset($data['clouse'][$k]) ? $data['clouse'][$k] : NULL,
                            'parameter_name'    => isset($data['parameter_name'][$k]) ? $data['parameter_name'][$k] : NULL,
                            'category'          => isset($data['category'][$k]) ? $data['category'][$k] : NULL,
                            'limitation'        => isset($data['limitation'][$k]) ? $data['limitation'][$k] : NULL,
                            'requirement'       => isset($data['requirement'][$k]) ? $data['requirement'][$k] : NULL,
                            'priority_order'    => isset($data['priority_order'][$k]) ? $data['priority_order'][$k] : NULL,
                            'result_1'          => isset($data['result_1'][$k]) ? $data['result_1'][$k] : NULL,
                            'result_2'          => isset($data['result_2'][$k]) ? $data['result_2'][$k] : NULL,
                            'result_3'          => isset($data['result_3'][$k]) ? $data['result_3'][$k] : NULL,
                            'result_4'          => isset($data['result_4'][$k]) ? $data['result_4'][$k] : NULL,
                            'result_5'          => isset($data['result_5'][$k]) ? $data['result_5'][$k] : NULL,
                            'is_deleted'        => 0,
                            'created_by'        => $this->session->userdata('user_data')->uidnr_admin,
                            'created_on'        => date("Y-m-d H:i:s")
                        );
                    }
                    $this->db->insert_batch('record_finding_parameters_body', $para_data);
                }
                // End...

                $files = $_FILES;
                if (!empty($files['multiple_image']['name'][0]) && count($files['multiple_image']['name']) > 0) {
                    //if ($file) {
                    for ($i = 0; $i < count($files['multiple_image']['name']); $i++) {
                        // Added by Saurabh on 20-10-2021 to compress image
                        $file_temp = $this->compressImage($files['multiple_image']['tmp_name'][$i], LOCAL_PATH . 'thumb/' . $files['multiple_image']['name'][$i], 25);
                        $file_data = array(
                            'name'      => $files['multiple_image']['name'][$i],
                            'tmp_name'  => $file_temp
                        );
                        $file = $this->multiple_upload_image($file_data);
                        // Added by Saurabh on 20-10-2021 to compress image 
                        $images[] = array('image_path' => $file['aws_path'], 'record_finding_id ' => $result, 'compress_status' => 1);
                    }
                    $this->mlm->insert_multiple_data("report_generated_images", $images);
                    //}
                }
                if ($result) {
                    $msg = array('status' => 1, 'msg' => "Report data Save Successfully");
                    $this->session->set_flashdata('success', 'Report data Save Successfully');
                } else {
                    $msg = array('status' => 0, 'msg' => "Error While Saving Data");
                }
            }
        } else {
            $msg = array('status' => 0, 'msg' => "data Not found");
        }
        echo json_encode($msg);
    }

    public function mark_completed()
    {
        $id = $this->input->post();
        $status =  $this->mlm->mark_completion($id);

        if ($status) {
            $msg = array(
                'status' => 1,
                'msg' => 'MarK as Completed'
            );
        } else {
            $msg = array(
                'status' => 0,
                'msg' => 'MarK as not Completed'
            );
        }
        echo json_encode($msg);
    }

    public function edit_record_finding($record_finding_id, $sample_reg_id, $branch_id)
    {
        $data['record_finding_id'] = base64_decode($record_finding_id);
        $data['branch_id'] = base64_decode($branch_id);
        $data['sample_reg_id'] = base64_decode($sample_reg_id);
        $data['record_data'] = $this->mlm->edit_record_finding($data);
        $data['details'] = $this->mlm->get_sample_gc($data);
        $data['test'] = $this->mlm->get_test($data['record_data']);

        if (!empty($data['record_data']['non_nabl_headings'])) {
            $data['record_data']['non_nabl_headings'] = json_decode(stripslashes($data['record_data']['non_nabl_headings']));
        }
        if (!empty($data['record_data']['nabl_headings'])) {
            $data['record_data']['nabl_headings'] = json_decode(stripslashes($data['record_data']['nabl_headings']));
        }
        $record_finding_id =  $data['record_finding_id'];

        // Added by CHANDAN --16-05-2022
        $data['get_parameters'] = $data['parameters_heading'] = $data['parameters_body'] = NULL;

        if (!empty($record_finding_id) && !empty($data['test']->test_id)) {

            $data['parameters_heading'] = $this->mlm->get_row('*', 'record_finding_parameters_heading', ['record_finding_id' => $record_finding_id, 'is_deleted' => 0]);

            $data['parameters_body'] = $this->mlm->get_parameter_details($record_finding_id, $data['test']->test_id);

            if ((empty($data['parameters_heading']) || empty($data['parameters_body']))) {

                $data['get_parameters'] = $this->mlm->parameter_details($data['test']->test_id);
            }
        }
        // End....
        $data['images'] = $this->mlm->get_images($record_finding_id);

        $data['sample_selected_test'] = $this->mlm->get_sample_selected_test_with_rfid($data['sample_reg_id']);
        // echo "<pre>"; print_r($data); die;
        $this->load_view('manage_lab/edit_record_finding', $data);
    }

    public function update_record_finding()
    {
        $data = $this->input->post();
        if ($data) {
            $getRFD = $this->mlm->check_sequence_no($data['record_finding_id'], $data['sample_reg_id'], $data['sequence_no']);
            if ($getRFD) {
                $msg = array(
                    'status' => 0,
                    'msg' => "Sequence No. should be Unique!"
                );
            } else {
                if (array_key_exists('non_nabl_table', $data)) {
                    $post['non_nabl_headings'] =  $data['non_nabl_table'];
                } else {
                    $post['non_nabl_headings'] = '';
                }
                if (array_key_exists('nabl_table', $data)) {
                    $post['nabl_headings'] =  $data['nabl_table'];
                } else {
                    $post['nabl_headings'] = '';
                }
                $post['created_by'] = $this->user;
                $post['test_result'] = $data['test_result'];
                if ($data['nabl_remark'] != '' && $data['nabl_remark'] != NULL) {
                    $post['nabl_remark'] = base64_encode(htmlentities($data['nabl_remark']));
                } else {
                    $post['nabl_remark'] = '';
                }
                if ($data['non_nabl_remark'] != '' && $data['non_nabl_remark'] != NULL) {
                    $post['non_nabl_remark'] = base64_encode(htmlentities($data['non_nabl_remark']));
                } else {
                    $post['non_nabl_remark'] = '';
                }
                if ($data['nabl_detail'] != '' && $data['nabl_detail'] != NULL) {
                    $post['nabl_detail'] = base64_encode(htmlentities($data['nabl_detail']));
                } else {
                    $post['nabl_detail'] = '';
                }
                if ($data['non_nabl_detail'] != '' && $data['non_nabl_detail'] != NULL) {
                    $post['non_nabl_detail'] = base64_encode(htmlentities($data['non_nabl_detail']));
                } else {
                    $post['non_nabl_detail'] = '';
                }

                $post['test_display_name'] = $data['test_display_name'];
                $post['test_display_method'] = $data['test_display_method'];
                $post['test_component'] = $data['test_component'];
                if (empty($data['test_name_type'])) {
                    unset($data['test_name_type']);
                } else {
                    $post['test_name_type'] = $data['test_name_type'];
                }
                // $post['test_name_type'] = $data['test_name_type'];

                if (empty($data['lab_id'])) {
                    unset($data['lab_id']);
                } else {
                    $post['lab_id'] = $data['lab_id'];
                }
                if (array_key_exists('nabl_table', $data) || !empty($post['nabl_detail']) || !empty($post['nabl_remark'])) {
                    $post['test_type'] = 'NABL';
                } else if (array_key_exists('non_nabl_table', $data) || !empty($post['non_nabl_detail']) || !empty($post['non_nabl_remark'])) {
                    $post['test_type'] = 'NON-NABL';
                } else {
                    $post['test_type'] = '';
                }
                $post['sequence_no'] = $data['sequence_no'];
                $record_finding_id = $data['record_finding_id'];
                $post['result_entry'] = $data['result_entry'];
                $result = $this->mlm->update_data('record_finding_details', $post, array('record_finding_id' => $record_finding_id));

                // Added by CHANDAN --16-05-2022
                if (!empty($record_finding_id) && isset($data['head_parameter_name']) && !empty($data['head_parameter_name'])) {

                    $para_heading_data = array(
                        'is_deleted'            => 0,
                        'branch_id'             => $data['head_branch_id'],
                        'record_finding_id'     => $record_finding_id,
                        'sample_test_id'        => isset($data['head_sample_test_id']) ? $data['head_sample_test_id'] : NULL,
                        'sample_reg_id'         => isset($data['head_sample_reg_id']) ? $data['head_sample_reg_id'] : NULL,
                        'test_id'               => isset($data['head_test_id']) ? $data['head_test_id'] : NULL,
                        'clouse'                => isset($data['head_clouse']) ? $data['head_clouse'] : NULL,
                        'parameter_name'        => isset($data['head_parameter_name']) ? $data['head_parameter_name'] : NULL,
                        'category'              => isset($data['head_category']) ? $data['head_category'] : NULL,
                        'limitation'            => isset($data['head_limitation']) ? $data['head_limitation'] : NULL,
                        'requirement'           => isset($data['head_requirement']) ? $data['head_requirement'] : NULL,
                        'priority_order'        => isset($data['head_priority_order']) ? $data['head_priority_order'] : NULL,
                        'result_1'              => isset($data['head_result_1']) ? $data['head_result_1'] : NULL,
                        'result_2'              => isset($data['head_result_2']) ? $data['head_result_2'] : NULL,
                        'result_3'              => isset($data['head_result_3']) ? $data['head_result_3'] : NULL,
                        'result_4'              => isset($data['head_result_4']) ? $data['head_result_4'] : NULL,
                        'result_5'              => isset($data['head_result_5']) ? $data['head_result_5'] : NULL,
                        'notes'                 => isset($data['notes']) ? base64_encode(htmlentities($data['notes'])) : NULL
                    );
                    if (empty($data['para_head_id'])) {
                        $para_heading_data['created_by'] = $this->session->userdata('user_data')->uidnr_admin;
                        $para_heading_data['created_on'] = date("Y-m-d H:i:s");

                        $this->mlm->insert_data('record_finding_parameters_heading', $para_heading_data);
                    } else {
                        $para_heading_data['updated_by'] = $this->session->userdata('user_data')->uidnr_admin;
                        $para_heading_data['updated_on'] = date("Y-m-d H:i:s");

                        $this->mlm->update_data('record_finding_parameters_heading', $para_heading_data, ['id' => $data['para_head_id']]);
                    }

                    for ($k = 0; $k < count($data['parameter_name']); $k++) {
                        $para_data = array(
                            'is_deleted'        => 0,
                            'branch_id'         => $data['head_branch_id'],
                            'record_finding_id' => $record_finding_id,
                            'sample_test_id'    => isset($data['head_sample_test_id']) ? $data['head_sample_test_id'] : NULL,
                            'sample_reg_id'     => isset($data['head_sample_reg_id']) ? $data['head_sample_reg_id'] : NULL,
                            'test_id'           => isset($data['head_test_id']) ? $data['head_test_id'] : NULL,
                            'test_parameters_id' => isset($data['test_parameters_id'][$k]) ? $data['test_parameters_id'][$k] : NULL,
                            'clouse'            => isset($data['clouse'][$k]) ? $data['clouse'][$k] : NULL,
                            'parameter_name'    => isset($data['parameter_name'][$k]) ? $data['parameter_name'][$k] : NULL,
                            'category'          => isset($data['category'][$k]) ? $data['category'][$k] : NULL,
                            'limitation'        => isset($data['limitation'][$k]) ? $data['limitation'][$k] : NULL,
                            'requirement'       => isset($data['requirement'][$k]) ? $data['requirement'][$k] : NULL,
                            'priority_order'    => isset($data['priority_order'][$k]) ? $data['priority_order'][$k] : NULL,
                            'result_1'          => isset($data['result_1'][$k]) ? $data['result_1'][$k] : NULL,
                            'result_2'          => isset($data['result_2'][$k]) ? $data['result_2'][$k] : NULL,
                            'result_3'          => isset($data['result_3'][$k]) ? $data['result_3'][$k] : NULL,
                            'result_4'          => isset($data['result_4'][$k]) ? $data['result_4'][$k] : NULL,
                            'result_5'          => isset($data['result_5'][$k]) ? $data['result_5'][$k] : NULL
                        );

                        if (empty($data['para_body_id'][$k])) {
                            $para_data['created_by'] = $this->session->userdata('user_data')->uidnr_admin;
                            $para_data['created_on'] = date("Y-m-d H:i:s");

                            $this->mlm->insert_data('record_finding_parameters_body', $para_data);
                        } else {
                            $para_data['updated_by'] = $this->session->userdata('user_data')->uidnr_admin;
                            $para_data['updated_on'] = date("Y-m-d H:i:s");

                            $this->mlm->update_data('record_finding_parameters_body', $para_data, ['id' => $data['para_body_id'][$k]]);
                        }
                    }
                }
                // End...

                $files = $_FILES;
                if (!empty($files['multiple_image']['name'][0]) && count($files['multiple_image']['name']) > 0) {
                    for ($i = 0; $i < count($files['multiple_image']['name']); $i++) {
                        // Added by Saurabh on 20-10-2021 to compress image
                        $file_temp = $this->compressImage($files['multiple_image']['tmp_name'][$i], LOCAL_PATH . 'thumb/' . $files['multiple_image']['name'][$i], 25);
                        $file_data = array(
                            'name'      => $files['multiple_image']['name'][$i],
                            'tmp_name'  => $file_temp
                        );
                        $file = $this->multiple_upload_image($file_data);
                        // Added by Saurabh on 20-10-2021 to compress image 
                        $images = array(
                            'image_path' => $file['aws_path'],
                            'record_finding_id ' => $record_finding_id,

                        );
                        $data['images'] = $this->mlm->get_images($record_finding_id);
                        $upload_image = $this->mlm->insert_data("report_generated_images", $images);
                        if ($upload_image) {
                            $msg = array(
                                'status' => 1,
                                'msg' => "Report data Updated Successfully"
                            );
                        } else {
                            $msg = array(
                                'status' => 0,
                                'msg' => "Error While Saving Image"
                            );
                        }
                    }
                }

                if ($result) {
                    $old_status_query = $this->db->select('status')->from('sample_registration')->where('sample_reg_id', $data['sample_reg_id'])->get();
                    $old_status = $old_status_query->row()->status;
                    $logDetails = array(
                        'module' => 'Samples',
                        'old_status' => $old_status,
                        'new_status' => '',
                        'sample_reg_id' => $data['sample_reg_id'],
                        'sample_assigned_lab_id' => /* $lab_id, */ '',
                        'action_message' => 'Edited Record Finding',
                        'sample_job_id' => '',
                        'report_id' => '',
                        'report_status' => '',
                        'test_ids' => '',
                        'test_names' => '',
                        'test_newstatus' => '',
                        'test_oldStatus' => '',
                        'test_assigned_to' => '',
                        'source_module'    => 'Manage_lab',
                        'operation'        => 'edit_record_finding',
                        'uidnr_admin'    => $this->session->userdata('user_data')->uidnr_admin,
                        'log_activity_on' => date("Y-m-d H:i:s")
                    );
                    $this->mlm->save_user_log($logDetails);
                    $msg = array(
                        'status' => 1,
                        'msg' => "Report data Updated Successfully"
                    );
                    $this->session->set_flashdata('success', 'Report data Updated Successfully');
                } else {
                    $msg = array(
                        'status' => 0,
                        'msg' => "Error While Saving Data"
                    );
                }
            }
        } else {
            $msg = array(
                'status' => 0,
                'msg' => "data Not found"
            );
        }
        echo json_encode($msg);
    }

    public function report_listing($applicant = 'NULL', $buyer = 'NULL', $divison = 'NULL', $product = 'NULL', $search_gc = 'NULL', $search_trf = 'NULL', $start_date = 'NULL', $end_date = 'NULL')
    {
        $where = array();
        $search_trf1 = $search_gc1 = NULL;
        // echo $applicant . " " . $product . " " . $search . " " . $start_date . " " . $end_date . " " . $stauts;
        $base_url = "Manage_lab/report_listing";
        $base_url .= '/' . (($applicant != 'NULL') ? $applicant : 'NULL');
        $base_url .= '/' . (($buyer != 'NULL') ? $buyer : 'NULL');
        $base_url .= '/' . (($divison != 'NULL') ? $divison : 'NULL');
        $base_url .= '/' . (($product != 'NULL') ? $product : 'NULL');
        $base_url .= '/' . (($search_gc != 'NULL') ? ($search_gc) : 'NULL');
        $base_url .= '/' . (($search_trf != 'NULL') ? ($search_trf) : 'NULL');
        $base_url .= '/' . (($start_date != 'NULL') ? $start_date : 'NULL');
        $base_url .= '/' . (($end_date != 'NULL') ? $end_date : 'NULL');
        // $base_url .= '/' . (($stauts != 'NULL') ? ($stauts) : 'NULL');
        $data['applicant_id'] = ($applicant != 'NULL') ? $applicant : 'NULL';
        $data['buyer_id'] = ($buyer != 'NULL') ? $buyer : 'NULL';
        $data['product_id'] = ($product != 'NULL') ? $product : 'NULL';
        $data['search_gc'] = ($search_gc != 'NULL') ? base64_decode($search_gc) : 'NULL';
        $data['search_trf'] = ($search_trf != 'NULL') ? base64_decode($search_trf) : 'NULL';
        $data['start_date'] = ($start_date != 'NULL') ? $start_date : 'NULL';
        $data['end_date'] = ($end_date != 'NULL') ? $end_date : 'NULL';
        // $data['stauts'] = ($stauts != 'NULL') ? base64_decode($stauts) : 'NULL';
        if ($applicant != 'NULL') {
            $customer = $this->mlm->get_row('customer_name', 'cust_customers', ['customer_id' => $applicant]);
            if ($customer) {
                $data['applicant_name'] = $customer->customer_name;
            } else {
                $data['applicant_name'] = 'NULL';
            }
        } else {
            $data['applicant_name'] = 'NULL';
        }

        if ($buyer != 'NULL') {
            $customer = $this->mlm->get_row('customer_name', 'cust_customers', ['customer_id' => $buyer]);
            if ($customer) {
                $data['buyer_name'] = $customer->customer_name;
            } else {
                $data['buyer_name'] = 'NULL';
            }
        } else {
            $data['buyer_name'] = 'NULL';
        }

        if ($product != 'NULL') {
            $customer = $this->mlm->get_row('sample_type_name', 'mst_sample_types', ['sample_type_id' => $product]);
            if ($customer) {
                $data['product_name'] = $customer->sample_type_name;
            } else {
                $data['product_name'] = 'NULL';
            }
        } else {
            $data['product_name'] = 'NULL';
        }
        if ($applicant != 'NULL') {
            $where['tr.trf_applicant'] = $applicant;
        }
        if ($buyer != 'NULL') {
            $where['tr.trf_buyer'] = $buyer;
        }
        if ($divison != 'NULL') {
            $where['sr.division_id'] = $divison;
            $data['divison'] = $divison;
        } else {
            $data['divison'] = 'NULL';
        }
        if ($product != 'NULL') {
            $where['sr.sample_registration_sample_type_id'] = $product;
        }
        if ($search_gc != 'NULL') {
            $search_gc1 = trim(base64_decode($search_gc));
        }
        if ($search_trf != 'NULL') {
            $search_trf1 = trim(base64_decode($search_trf));
        }
        if ($start_date != 'NULL') {
            $where['sr.received_date >='] = ($start_date);
        }
        if ($end_date != 'NULL') {
            $where['sr.received_date <='] = ($end_date);
        }
        // if ($stauts!= 'NULL') {
        //     $where['sr.status'] = base64_decode($stauts);
        // }
        $total_row = $this->mlm->report_listing(NULL, NULL, $where,  $search_gc1, $search_trf1, '1');

        $page = ($this->uri->segment(11)) ? $this->uri->segment(11) : 0;
        $config = $this->pagination($base_url, $total_row, 10, 11);

        $data["links"] = $config["links"];

        $data['report_listing'] =  $this->mlm->report_listing($config["per_page"], $page, $where,  $search_gc1, $search_trf1);
        $data['divisions'] = $this->mlm->get_default_division();
        $data['sign_auth'] =  $this->mlm->get_report_approver();
        if ($total_row > 0) {
            $start = (int)$page + 1;
        } else {
            $start = 0;
        }
        $end = (($data['report_listing']) ? count($data['report_listing']) : 0) + (($page) ? $page : 0);
        $data['result_count'] = "Showing " . $start . " - " . $end . " of " . $total_row . " Results";
        $this->load_view('manage_lab/report_listing', $data);
    }

    public function Final_report_generate()
    {
        $checkUser = $this->session->userdata('user_data');

        $data = $this->input->post();

        $part_detail =  base64_encode(htmlentities($data['part_details']));
        $where = array('sample_reg_id' => $data['sample_reg_id']);
        $gc_no = $this->mlm->get_row('gc_no', 'sample_registration', $where);
        $report_data = array(
            'generated_date' => date("Y-m-d"),
            'generated_by' => $this->user,
            'sample_reg_id' => $data['sample_reg_id'],
            'background_process' => 'Completed',
            'status' => 'Report Generated',
            'report_num' => $gc_no->gc_no,
            'report_type' => 'System Report',
            'signing_authority' => $data['sign_auth'],
            'sign_authority_new' => $data['sign_auth1'],
            'report_format' => $data['report_format']
        );
        $generate_data = array(
            'part' => $data['part'],
            'part_details' => $part_detail
        );
        $report_id = $this->mlm->get_row('report_id', 'generated_reports', $where);
        $revise_flag = $this->mlm->get_row('revise_flag', 'sample_registration', $where);
        $additional_flag = $this->mlm->get_row('additional_flag', 'sample_registration', $where);
        if ($report_id->report_id && $revise_flag->revise_flag == 0 && $additional_flag->additional_flag == 0) {
            $report_where = array('report_id' => $report_id->report_id);
            $updat_generate_report =   $this->mlm->update_data('generated_reports', $report_data, $report_where);
            $generate_where = array('sample_reg_id' => $data['sample_reg_id']);
            if ($updat_generate_report) {
                $this->mlm->update_data('sample_registration', $generate_data, $generate_where);
            }
        } elseif ($report_id->report_id && $revise_flag->revise_flag > 0) {
            $revise_count =  $this->mlm->get_row('revise_count', 'sample_registration', array('sample_reg_id' => $data['sample_reg_id']));
            $revise_total = $revise_count->revise_count + 1;
            $report_new_name =  'Rev-' . $revise_total;
            $report_data = array(
                'generated_date' => date("Y-m-d"),
                'generated_by' => $this->user,
                'sample_reg_id' => $data['sample_reg_id'],
                'background_process' => 'Completed',
                'status' => 'Report Generated',
                'report_num' => $gc_no->gc_no . '-' . $report_new_name,
                'report_type' => 'System Report',
                'signing_authority' => $data['sign_auth'],
                'report_format' => $data['report_format'],
                'sign_authority_new' => $data['sign_auth1']
            );
            $generate_data = array(
                'part' => $data['part'],
                'part_details' => $part_detail
            );
            $this->mlm->update_data('sample_registration', array('revise_count' => $revise_total), array('sample_reg_id' => $data['sample_reg_id']));
            $update['reprot_generated_id'] = $this->mlm->report_generate_data($report_data);
            $updat_generate_report = $update['reprot_generated_id'];
            $generate_where = array('sample_reg_id' => $data['sample_reg_id']);
            if ($updat_generate_report) {
                $this->mlm->update_data('sample_registration', $generate_data, $generate_where);
            }
        }
        //  elseif ($report_id->report_id && $additional_flag->additional_flag > 0) {
        //     //    echo "3";die;
        //     $add_count =  $this->mlm->get_row('additional_count', 'sample_registration', array('sample_reg_id' => $data['sample_reg_id']));
        //     $add_total = $add_count->additional_count + 1;

        //     $report_new_no = 'Rev-' . $add_total;

        //     $report_data = array(
        //         'generated_date' => date("Y-m-d"),
        //         'generated_by' => $this->user,
        //         'sample_reg_id' => $data['sample_reg_id'],
        //         'background_process' => 'Completed',
        //         'status' => 'Report Generated',
        //         'report_num' => $gc_no->gc_no . '-' . $report_new_no,
        //         'report_type' => 'System Report',
        //         'signing_authority' => $data['sign_auth'],
        //         'report_format' => $data['report_format'],

        //         // 'sign_authority_new' => $data['sign_auth1']

        //     );
        //     $generate_data = array(
        //         'part' => $data['part'],
        //         'part_details' => $part_detail
        //     );
        //     $this->mlm->update_data('sample_registration', array('additional_count' => $add_total), array('sample_reg_id' => $data['sample_reg_id']));

        //     $update['reprot_generated_id'] = $this->mlm->report_generate_data($report_data);
        //     $updat_generate_report = $update['reprot_generated_id'];
        //     $generate_where = array('sample_reg_id' => $data['sample_reg_id']);
        //     if ($updat_generate_report) {
        //         $this->mlm->update_data('sample_Registration', $generate_data, $generate_where);
        //         // echo $this->db->last_Query();die;

        //     }
        // }
        else {
            $update['reprot_generated_id'] = $this->mlm->report_generate_data($report_data);
            // Added on 22-06-2021 to save part details while generating report by saurabh
            $generate_data = array(
                'part' => $data['part'],
                'part_details' => $part_detail
            );
            $generate_where = array('sample_reg_id' => $data['sample_reg_id']);
            $this->mlm->update_data('sample_registration', $generate_data, $generate_where);
            // Added on 22-06-2021 to save part details while generating report by saurabh
        }
        $update['report_data'] =  $this->mlm->Final_report_generate($data);
        $update['test_data'] =  $this->mlm->get_test_result($data);
        $approver_email = $this->mlm->get_approver_email($data['sign_auth']);
        if ($update) {
            $subject = "<p>Report generated for Sample #" . $gc_no->gc_no . '</p>';

            $message = '<table width="100%" border="0" cellspacing="5" cellpadding="5" style="border-collapse:collapse; font-family:Arial, Helvetica, sans-serif;font-size:12px;">
            <tr>
                <td colspan="2">
                    <br />
                    Dear Sir/Madam, 
                    <br />
                    <br />
                    Report is Generated for sample #<strong>' . $gc_no->gc_no . '</strong>, Kindly check and verify on LIMS(https://lims.basilrl.com)
                    <br />
                    <br />
                    <br />
                    <br />
                    <strong>Regards,
                    <br />
                    Basil Team
                    </strong><br />
                </td>
            </tr>
            </table>';
            if (!empty($approver_email->email)) {
               // $send_mail = send_to_report_approval($approver_email->email, NULL, NULL, $subject, $message);
                if ($send_mail) {
                    $this->session->set_flashdata('success', 'SUCCESSFULLY GENERATE');
                    redirect($_SERVER['HTTP_REFERER']);
                } else {
                    $this->session->set_flashdata('success', 'SUCCESSFULLY GENERATE');
                    redirect($_SERVER['HTTP_REFERER']);
                }
            } else {
                $this->session->set_flashdata('error', 'Error while saving data');
                redirect($_SERVER['HTTP_REFERER']);
            }
        }
    }

    public function preview_report()
    {
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '-1');

        $data = $this->input->get();
        $checkUser = $this->session->userdata('user_data');
        $update['test_component'] =  $this->mlm->pdf_test_component($data);
        $update['report_data'] =  $this->mlm->pdf_data_get($data);

        // Added by Saurabh on 21-10-2021 to check image compress status
        $sample_images =  $this->mlm->sample_final_images($data);
        $sam_images = [];
        if (!empty($sample_images)) {
            foreach ($sample_images as $imgs) {
                if ($imgs->compress_status == 1) {
                    $sam_images[] = $imgs;
                } else {
                    $file = $imgs->image_file_path;
                    $length = strlen($file);
                    $name = substr($file, 70, $length);
                    $file_temp = $this->compressImage($file, LOCAL_PATH . 'thumb/' . $name, 25);
                    $file_data = array(
                        'name'      => $name,
                        'tmp_name'  => $file_temp
                    );
                    $image = $this->multiple_upload_image($file_data);
                    $this->db->update('sample_photos', ['image_file_path' => $image['aws_path'], 'compress_status' => 1], ['image_id' => $imgs->image_id]);
                    $sam_images[] = $image['aws_path'];
                }
            }
        }
        $update['image_sample'] = $sam_images;
        // Added by Saurabh on 20-10-2021 to compress image

        // Added by Saurabh on 21-10-2021 to check image compress status
        $reference_images =  $this->mlm->get_reference_images($data);
        $ref_images = [];
        if (!empty($reference_images)) {
            foreach ($reference_images as $imgs) {
                if ($imgs->compress_status == 1) {
                    $ref_images[] = $imgs;
                } else {
                    $file = $imgs->image_file_path;
                    $length = strlen($file);
                    $name = substr($file, 70, $length);
                    $file_temp = $this->compressImage($file, LOCAL_PATH . 'thumb/' . $name, 25);
                    $file_data = array(
                        'name'      => $name,
                        'tmp_name'  => $file_temp
                    );
                    $image = $this->multiple_upload_image($file_data);
                    $this->db->update('report_reference_images', ['image_file_path' => $image['aws_path'], 'compress_status' => 1], ['report_ref_image_id' => $imgs->report_ref_image_id]);
                    $ref_images[] = $image['aws_path'];
                }
            }
        }
        $update['reference_sample'] = $ref_images;
        // Added by Saurabh on 21-10-2021 to check image compress status
        $application_data = $this->mlm->get_application_care($update);
        //echo "<pre>"; print_r($application_data); die;
        if ($application_data) {
            $appData = array();
            if (count($application_data) > 0) {
                foreach ($application_data as $k => $app) {
                    if (!empty($app->instruction_image)) {
                        $appData[$k]['instruction_image'] = $this->getS3Url($app->instruction_image);
                        $appData[$k]['instruction_name'] = $app->instruction_name;
                    }
                }
            }
            $update['application_data'] = $appData;
        } else {
            $update['application_data'] = null;
        }

        // $update['cps_data'] = $this->mlm->get_cps_data('*', $data['sample_reg_id']);
        // $update['nabl_record'] = $this->mlm->getNABLCpsResultData('record_finding_id,nabl_remark,nabl_detail,nabl_headings,test_result,test_display_name',$data['sample_reg_id']);

        // $update['non_nabl_record'] = $this->mlm->getNONNABLCpsResultData('record_finding_id,non_nabl_remark,non_nabl_detail,test_result,non_nabl_headings,test_display_name',$data['sample_reg_id']);
        $update['record_finding_data'] = $this->mlm->get_record_finding_data($data);

        // foreach ($update['cps_data'] as $key => $rfd_id) {
        //     $image  = $this->mlm->get_images($rfd_id['record_finding_id']);
        //     if ($image) {
        //         // Added by Saurabh on 21-10-2021 to check compress status
        //         foreach ($image as $img) {
        //             if ($img['compress_status'] == 1) {
        //                 $image_name[] = $img;
        //             } else {
        //                 $file = $img['image_path'];
        //                 $length = strlen($file);
        //                 $name = substr($file, 70, $length);
        //                 $file_temp = $this->compressImage($file, LOCAL_PATH . 'thumb/' . $name, 25);
        //                 $file_data = array(
        //                     'name'      => $name,
        //                     'tmp_name'  => $file_temp
        //                 );
        //                 // Added by Saurabh on 20-10-2021 to compress image
        //                 $image = $this->multiple_upload_image($file_data);
        //                 $this->db->update('report_generated_images', ['image_path' => $image['aws_path'], 'compress_status' => 1], ['images_id' => $img['images_id']]);
        //                 $image_name[] = $image['aws_path'];
        //             }
        //         }
        //         $update['cps_data'][$key]['images'] = $image_name;
        //     }
        // }
        if ($update['record_finding_data'] != '') {
            foreach ($update['record_finding_data'] as $key => $rfd_id) {

                // Added by CHANDAN --20-05-2022
                $update['record_finding_data'][$key]['parameters_heading'] = NULL;
                $update['record_finding_data'][$key]['parameters_body'] = NULL;
                if (!empty($rfd_id['record_finding_id'])) {
                    $update['record_finding_data'][$key]['parameters_heading'] = $this->mlm->get_row('*', 'record_finding_parameters_heading', ['record_finding_id' => $rfd_id['record_finding_id'], 'is_deleted' => 0]);

                    $update['record_finding_data'][$key]['dist_para_cat'] = $this->mlm->get_distinct_para_cat($rfd_id['record_finding_id']);

                    $update['record_finding_data'][$key]['parameters_body'] = $this->mlm->get_parameters_body($rfd_id['record_finding_id']);
                }
                // End....

                if (!empty($update['record_finding_data'][$key]['nabl_headings'])) {
                    $update['record_finding_data'][$key]['nabl_headings'] = json_decode(stripslashes($update['record_finding_data'][$key]['nabl_headings']));
                }
                $image  = $this->mlm->get_images($rfd_id['record_finding_id']);
                if ($image) {
                    $update['record_finding_data'][$key]['images'] = $image;
                }
            }
        } else {
            $update['record_finding_data'] = '';
        }
        if ($update['record_finding_data'] != '') {
            foreach ($update['record_finding_data'] as $key => $rfd_id) {
                if (!empty($update['record_finding_data'][$key]['non_nabl_headings'])) {
                    $update['record_finding_data'][$key]['non_nabl_headings'] = json_decode(stripslashes($update['record_finding_data'][$key]['non_nabl_headings']));
                }
                $image  = $this->mlm->get_images($rfd_id['record_finding_id']);
                if ($image) {
                    $update['record_finding_data'][$key]['images'] = $image;
                }
            }
        } else {
            $update['record_finding_data'] = '';
        }
        $update['sign_data1'] = NULL;
        $update['test_data'] =  $this->mlm->get_test_result($data);
        $update['template_type'] = 'preview';

        // echo "<pre>"; print_r($update); die;

        if ($update['report_data']->branch_name == 'Gurgaon') {
            if ($update['report_data']->report_format == 6 || $update['report_data']->division_name == 'Hradline') {
                $this->generate_pdf('manage_lab/ggnreport', $update);
            } elseif ($update['report_data']->report_format == 8 || $update['report_data']->division_name == 'Footwear') {
                $this->generate_pdf('manage_lab/ggnreport', $update);
                // $this->load_view('manage_lab/ggn_footwear_report', $update);
            } else {
                $this->generate_pdf('manage_lab/ggnreport', $update);
            }
        } elseif ($update['report_data']->branch_id == 2) {
            if ($update['report_data']->report_format == 4) {
                $this->generate_pdf('manage_lab/ggnreport', $update);
            } elseif ($update['report_data']->report_format == 3 || $update['report_data']->division_name == 'Textiles') {
                $this->generate_pdf('manage_lab/ggnreport', $update);
            } elseif ($update['report_data']->report_format == 2 || $update['report_data']->division_name == 'Analytical') {
                $this->generate_pdf('manage_lab/ggnreport', $update);
            } elseif ($update['report_data']->report_format == 1 || $update['report_data']->division_name == 'Toys') {
                $this->generate_pdf('manage_lab/ggnreport', $update);
            } elseif ($update['report_data']->report_format == 5 || $update['report_data']->division_name == 'Footwear') {
                $this->generate_pdf('manage_lab/ggnreport', $update);
            }
            // Added by Saurabh on 01-11-2021 for packaging division
            elseif ($update['report_data']->report_format == 5 || $update['report_data']->division_name == 'Packaging') {
                $this->generate_pdf('manage_lab/ggnreport', $update);
                // $this->load_view('manage_lab/packaging_uaereport', $update);
            } else {
                $this->generate_pdf('manage_lab/ggnreport', $update);
            }
        } elseif ($update['report_data']->branch_name == 'Dhaka') {
            $this->generate_pdf('manage_lab/ggnreport', $update);
        }
    }

    public function url_sign_get($signature_path_aws)
    {
        return str_replace('s3://', 'https://s3.ap-south-1.amazonaws.com/', $signature_path_aws);
    }

    public function pdf_demo()
    {
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '-1');

        $data = $this->input->get();
        $checkUser = $this->session->userdata('user_data');
        $update['test_component'] =  $this->mlm->pdf_test_component($data);

        $update['report_data'] =  $this->mlm->pdf_data_get($data);
        // Added by Saurabh on 21-10-2021 to check image compress status
        $sample_images =  $this->mlm->sample_final_images($data);

        $sam_images = array();
        if (!empty($sample_images)) {
            foreach ($sample_images as $imgs) {
                if ($imgs->compress_status == 1) {
                    $sam_images[] = $imgs;
                } else {
                    $file = $imgs->image_file_path;
                    $length = strlen($file);
                    $name = substr($file, 70, $length);
                    $file_temp = $this->compressImage($file, LOCAL_PATH . 'thumb/' . $name, 25);
                    $file_data = array(
                        'name'      => $name,
                        'tmp_name'  => $file_temp
                    );
                    $image = $this->multiple_upload_image($file_data);
                    $this->db->update('sample_photos', ['image_file_path' => $image['aws_path'], 'compress_status' => 1], ['image_id' => $imgs->image_id]);
                    $sam_images[] = $image['aws_path'];
                }
            }
        }
        $update['image_sample'] = $sam_images;
        // Added by Saurabh on 20-10-2021 to compress image
        // Added by Saurabh on 21-10-2021 to check image compress status
        $reference_images =  $this->mlm->get_reference_images($data);
        $ref_images = [];
        if (!empty($reference_images)) {
            foreach ($reference_images as $imgs) {
                if ($imgs->compress_status == 1) {
                    $ref_images[] = $imgs;
                } else {
                    $file = $imgs->image_file_path;
                    $length = strlen($file);
                    $name = substr($file, 70, $length);
                    $file_temp = $this->compressImage($file, LOCAL_PATH . 'thumb/' . $name, 25);
                    $file_data = array(
                        'name'      => $name,
                        'tmp_name'  => $file_temp
                    );
                    $image = $this->multiple_upload_image($file_data);
                    $this->db->update('report_reference_images', ['image_file_path' => $image['aws_path'], 'compress_status' => 1], ['report_ref_image_id' => $imgs->report_ref_image_id]);
                    $ref_images[] = $image['aws_path'];
                }
            }
        }
        $update['reference_sample'] = $ref_images;
        // Added by Saurabh on 21-10-2021 to check image compress status
        $application_data = $this->mlm->get_application_care($update);

        if ($application_data) {
            $appData = array();
            if (count($application_data) > 0) {
                foreach ($application_data as $k => $app) {
                    if (!empty($app->instruction_image)) {
                        $appData[$k]['instruction_image'] = $this->getS3Url($app->instruction_image);
                        $appData[$k]['instruction_name'] = $app->instruction_name;
                    }
                }
            }
            $update['application_data'] = $appData;
        } else {
            $update['application_data'] = null;
        }

        // $update['cps_data'] = $this->mlm->get_cps_data('*', $data['sample_reg_id']);
        $update['record_finding_data'] = $this->mlm->get_record_finding_data($data);

        // foreach ($update['cps_data'] as $key => $rfd_id) {
        //     $image  = $this->mlm->get_images($rfd_id['record_finding_id']);
        //     if ($image) {
        //         // Added by Saurabh on 21-10-2021 to check compress status
        //         $image_name = array();
        //         foreach ($image as $img) {
        //             if ($img['compress_status'] == 1) {
        //                 $image_name[] = $img;
        //             } else {
        //                 $file = $img['image_path'];
        //                 $length = strlen($file);
        //                 $name = substr($file, 70, $length);
        //                 $file_temp = $this->compressImage($file, LOCAL_PATH . 'thumb/' . $name, 25);
        //                 $file_data = array(
        //                     'name'      => $name,
        //                     'tmp_name'  => $file_temp
        //                 );
        //                 // Added by Saurabh on 20-10-2021 to compress image
        //                 $image = $this->multiple_upload_image($file_data);
        //                 $this->db->update('report_generated_images', ['image_path' => $image['aws_path'], 'compress_status' => 1], ['images_id' => $img['images_id']]);
        //                 $image_name[] = $image['aws_path'];
        //             }
        //         }
        //         $update['record_finding_data'][$key]['images'] = $image_name;
        //     }
        // }

        if ($update['record_finding_data'] != '') {
            foreach ($update['record_finding_data'] as $key => $rfd_id) {

                // Added by CHANDAN --20-05-2022
                $update['record_finding_data'][$key]['parameters_heading'] = NULL;
                $update['record_finding_data'][$key]['parameters_body'] = NULL;
                if (!empty($rfd_id['record_finding_id'])) {
                    $update['record_finding_data'][$key]['parameters_heading'] = $this->mlm->get_row('*', 'record_finding_parameters_heading', ['record_finding_id' => $rfd_id['record_finding_id'], 'is_deleted' => 0]);

                    $update['record_finding_data'][$key]['dist_para_cat'] = $this->mlm->get_distinct_para_cat($rfd_id['record_finding_id']);

                    $update['record_finding_data'][$key]['parameters_body'] = $this->mlm->get_parameters_body($rfd_id['record_finding_id']);
                }
                // End....

                if (!empty($update['record_finding_data'][$key]['nabl_headings'])) {
                    $update['record_finding_data'][$key]['nabl_headings'] = json_decode(stripslashes($update['record_finding_data'][$key]['nabl_headings']));
                }
                $image  = $this->mlm->get_images($rfd_id['record_finding_id']);
                if ($image) {
                    $update['record_finding_data'][$key]['images'] = $image;
                }
            }
        } else {
            $update['record_finding_data'] = '';
        }

        if ($update['record_finding_data'] != '') {
            foreach ($update['record_finding_data'] as $key => $rfd_id) {
                if (!empty($update['record_finding_data'][$key]['non_nabl_headings'])) {
                    $update['record_finding_data'][$key]['non_nabl_headings'] = json_decode(stripslashes($update['record_finding_data'][$key]['non_nabl_headings']));
                }
                $image  = $this->mlm->get_images($rfd_id['record_finding_id']);
                if ($image) {
                    // Added by Saurabh on 21-10-2021 to check compress status
                    $image_name1 = array();
                    foreach ($image as $img) {
                        if ($img['compress_status'] == 1) {
                            $image_name1[] = $img;
                        } else {
                            $file = $img['image_path'];
                            $length = strlen($file);
                            $name = substr($file, 70, $length);
                            $file_temp = $this->compressImage($file, LOCAL_PATH . 'thumb/' . $name, 25);
                            $file_data = array(
                                'name'      => $name,
                                'tmp_name'  => $file_temp
                            );
                            // Added by Saurabh on 20-10-2021 to compress image
                            $image = $this->multiple_upload_image($file_data);
                            $this->db->update('report_generated_images', ['image_path' => $image['aws_path'], 'compress_status' => 1], ['images_id' => $img['images_id']]);
                            $image_name1[] = $image['aws_path'];
                        }
                    }
                    $update['record_finding_data'][$key]['images'] = $image_name1;
                    // $update['record_finding_data'][$key]['images'] = $image;
                }
            }
        } else {
            $update['record_finding_data'] = '';
        }

        $sign1 = $this->mlm->get_row('signing_authority', 'generated_reports', ['report_id' => $update['report_data']->report_id]);

        $sign2 = $this->mlm->get_row('sign_authority_new', 'generated_reports', ['report_id' => $update['report_data']->report_id]);

        if ($sign1) {
            $signature1 = $this->mlm->get_row('sign_path', 'admin_signature', ['admin_id' => $sign1->signing_authority]);
        }
        if ($sign2) {
            $signature2 = $this->mlm->get_row('sign_path', 'admin_signature', ['admin_id' => $sign2->sign_authority_new]);
        }

        if ($signature1) {
            $update['signature1'] = $this->url_sign_get($signature1->sign_path);
        }
        if ($signature2) {
            $update['signature2'] = $this->url_sign_get($signature2->sign_path);
        }
        $update['sign_data1'] = $this->mlm->getsignvalues($sign1->signing_authority);
        $update['sign_data2'] = $this->mlm->getsignvalues($sign2->sign_authority_new);
        $update['test_data'] =  $this->mlm->get_test_result($data);

        // echo "<pre>"; print_r($update); die;

        if ($update['report_data']->branch_name == 'Gurgaon') {
            if ($update['report_data']->report_format == 6 || $update['report_data']->division_name == 'Hradline') {

                $this->generate_pdf('manage_lab/hggnreport', $update);
            } elseif ($update['report_data']->report_format == 8 || $update['report_data']->division_name == 'Footwear') {
                $this->generate_pdf('manage_lab/ggn_footwear_report', $update);
                // $this->load->view('manage_lab/ggn_footwear_report', $update);
            } else {
                $this->generate_pdf('manage_lab/ggnreport', $update);
            }
        } elseif ($update['report_data']->branch_id == 2) {
            if ($update['report_data']->report_format == 4) {
                $this->generate_pdf('manage_lab/landmark_uaereport', $update);
            } elseif ($update['report_data']->report_format == 3 || $update['report_data']->division_name == 'Textiles') {

                $this->generate_pdf('manage_lab/taxtile_uaereport', $update);
            } elseif ($update['report_data']->report_format == 2 || $update['report_data']->division_name == 'Analytical') {

                $this->generate_pdf('manage_lab/analytical_uaereport', $update);
            } elseif ($update['report_data']->report_format == 1 || $update['report_data']->division_name == 'Toys') {
                $this->generate_pdf('manage_lab/toys_uaereport', $update);
            } elseif ($update['report_data']->report_format == 5 || $update['report_data']->division_name == 'Footwear') {
                $this->generate_pdf('manage_lab/lf_uaereport', $update);
            }
            // Added by Saurabh on 01-11-2021 for packaging division
            elseif ($update['report_data']->report_format == 5 || $update['report_data']->division_name == 'Packaging') {
                $this->generate_pdf('manage_lab/packaging_uaereport', $update);
            } else {

                $this->generate_pdf('manage_lab/taxtile_uaereport', $update);
            }
        } elseif ($update['report_data']->branch_name == 'Dhaka') {

            $this->generate_pdf('manage_lab/bdreport', $update);
        }
    }

    public function approve_report()
    {
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '-1');

        $data = $this->input->post();
        $checkUser = $this->session->userdata('user_data');

        // Check multiple approver status, added by Saurabh on 15-02-2022
        $approver = $this->db->select('signing_authority, sign_authority_new')->where('report_id', $data['report_id'])->get('generated_reports')->row_array();

        $primary_approver = $approver['signing_authority'];
        $second_approver = $approver['sign_authority_new'];

        if ($checkUser->uidnr_admin == $primary_approver) {
            $this->mlm->update_data('generated_reports', ['primary_approver_status' => 2], array('report_id' => $data['report_id']));
        }

        if ($checkUser->uidnr_admin == $second_approver) {
            $this->mlm->update_data('generated_reports', ['secondary_approver_status' => 2], array('report_id' => $data['report_id']));
        }

        $approver_status = $this->db->select('primary_approver_status, secondary_approver_status')->where('report_id', $data['report_id'])->get('generated_reports')->row_array();

        $primary_approver_status = $approver_status['primary_approver_status'];
        $secondary_approver_status = $approver_status['secondary_approver_status'];

        if (($second_approver != 0) && (($primary_approver_status == 2) && ($secondary_approver_status == 2))) {
            $this->mlm->update_data('generated_reports', array('status' => 'Report Approved'), array('report_id' => $data['report_id']));
            $status = $this->mlm->update_data('sample_registration', array('status' => 'Report Approved'), array('sample_reg_id' => $data['sample_reg_id']));
        } else if ($second_approver == 0) {
            /* added by millan on 02-11-2021 */
            $this->mlm->update_data('generated_reports', array('status' => 'Report Approved'), array('report_id' => $data['report_id']));
            // updated by millan on 02-11-2021
            $status = $this->mlm->update_data('sample_registration', array('status' => 'Report Approved'), array('sample_reg_id' => $data['sample_reg_id']));
        }
        // End...

        $update['test_component'] = $this->mlm->pdf_test_component($data);
        // $update['cps_data'] = $this->mlm->get_cps_data('*', $data['sample_reg_id']);
        $update['report_data'] =  $this->mlm->pdf_data_get($data);
        // Added by Saurabh on 21-10-2021 to check image compress status
        $sample_images =  $this->mlm->sample_final_images($data);
        $sam_images = [];
        if (!empty($sample_images)) {
            foreach ($sample_images as $imgs) {
                if ($imgs->compress_status == 1) {
                    $sam_images[] = $imgs;
                } else {
                    $file = $imgs->image_file_path;
                    $length = strlen($file);
                    $name = substr($file, 70, $length);
                    $file_temp = $this->compressImage($file, LOCAL_PATH . 'thumb/' . $name, 25);
                    $file_data = array(
                        'name'      => $name,
                        'tmp_name'  => $file_temp
                    );
                    $image = $this->multiple_upload_image($file_data);
                    $this->db->update('sample_photos', ['image_file_path' => $image['aws_path'], 'compress_status' => 1], ['image_id' => $imgs->image_id]);
                    $sam_images[] = $image['aws_path'];
                }
            }
        }
        $update['image_sample'] = $sam_images;
        // Added by Saurabh on 20-10-2021 to compress image
        // Added by Saurabh on 21-10-2021 to check image compress status
        $reference_images =  $this->mlm->get_reference_images($data);
        $ref_images = array();
        if (!empty($reference_images)) {
            foreach ($reference_images as $imgs) {
                if ($imgs->compress_status == 1) {
                    $ref_images[] = $imgs;
                } else {
                    $file = $imgs->image_file_path;
                    $length = strlen($file);
                    $name = substr($file, 70, $length);
                    $file_temp = $this->compressImage($file, LOCAL_PATH . 'thumb/' . $name, 25);
                    $file_data = array(
                        'name'      => $name,
                        'tmp_name'  => $file_temp
                    );
                    $image = $this->multiple_upload_image($file_data);
                    $this->db->update('report_reference_images', ['image_file_path' => $image['aws_path'], 'compress_status' => 1], ['report_ref_image_id' => $imgs->report_ref_image_id]);
                    $ref_images[] = $image['aws_path'];
                }
            }
        }
        $update['reference_sample'] = $ref_images;
        // Added by Saurabh on 21-10-2021 to check image compress status
        $application_data = $this->mlm->get_application_care($update);
        if ($application_data) {
            $appData = array();
            if (count($application_data) > 0) {
                foreach ($application_data as $k => $app) {
                    if (!empty($app->instruction_image)) {

                        $appData[$k]['instruction_image'] = $this->getS3Url($app->instruction_image);
                        $appData[$k]['instruction_name'] = $app->instruction_name;
                    }
                }
            }
            $update['application_data'] = $appData;
        } else {
            $update['application_data'] = null;
        }

        $update['record_finding_data'] = $this->mlm->get_record_finding_data($data);

        // foreach ($update['cps_data'] as $key => $rfd_id) {
        //     $image  = $this->mlm->get_images($rfd_id['record_finding_id']);
        //     if ($image) {
        //         $update['cps_data'][$key]['images'] = $image;
        //     }
        // }
        if ($update['record_finding_data'] != '') {
            foreach ($update['record_finding_data'] as $key => $rfd_id) {

                // Added by CHANDAN --20-05-2022
                $update['record_finding_data'][$key]['parameters_heading'] = NULL;
                $update['record_finding_data'][$key]['parameters_body'] = NULL;
                if (!empty($rfd_id['record_finding_id'])) {
                    $update['record_finding_data'][$key]['parameters_heading'] = $this->mlm->get_row('*', 'record_finding_parameters_heading', ['record_finding_id' => $rfd_id['record_finding_id'], 'is_deleted' => 0]);

                    $update['record_finding_data'][$key]['dist_para_cat'] = $this->mlm->get_distinct_para_cat($rfd_id['record_finding_id']);

                    $update['record_finding_data'][$key]['parameters_body'] = $this->mlm->get_parameters_body($rfd_id['record_finding_id']);
                }
                // End....

                if (!empty($update['record_finding_data'][$key]['nabl_headings'])) {
                    $update['record_finding_data'][$key]['nabl_headings'] = json_decode(stripslashes($update['record_finding_data'][$key]['nabl_headings']));
                }
                $image1 = $this->mlm->get_images($rfd_id['record_finding_id']);
                if ($image1) {
                    $update['record_finding_data'][$key]['images'] = $image1;
                }
            }
        } else {
            $update['record_finding_data'] = '';
        }
        if ($update['record_finding_data'] != '') {
            foreach ($update['record_finding_data'] as $key => $rfd_id) {
                if (!empty($update['record_finding_data'][$key]['non_nabl_headings'])) {
                    $update['record_finding_data'][$key]['non_nabl_headings'] = json_decode(stripslashes($update['record_finding_data'][$key]['non_nabl_headings']));
                }
                $image2 = $this->mlm->get_images($rfd_id['record_finding_id']);
                if ($image2) {
                    $update['record_finding_data'][$key]['images'] = $image2;
                }
            }
        } else {
            $update['record_finding_data'] = '';
        }
        $record_finding_id = $update['report_data']->record_finding_id;
        $update['images'] = $this->mlm->get_images($record_finding_id);
        $sign1 = $this->mlm->get_row('signing_authority', 'generated_reports', ['report_id' => $update['report_data']->report_id]);

        $sign2 = $this->mlm->get_row('sign_authority_new', 'generated_reports', ['report_id' => $update['report_data']->report_id]);
        // echo $sign2;die;
        if ($sign1) {
            $signature1 = $this->mlm->get_row('sign_path', 'admin_signature', ['admin_id' => $sign1->signing_authority]);
        }
        if ($sign2) {
            $signature2 = $this->mlm->get_row('sign_path', 'admin_signature', ['admin_id' => $sign2->sign_authority_new]);
        }

        if ($signature1) {
            $update['signature1'] = $this->url_sign_get($signature1->sign_path);
        }
        if ($signature2) {
            $update['signature2'] = $this->url_sign_get($signature2->sign_path);
        }
        $update['sign_data1'] = $this->mlm->getsignvalues($sign1->signing_authority);
        $update['sign_data2'] = $this->mlm->getsignvalues($sign2->sign_authority_new);
        // print_r($update['sign_data2']);die;
        $update['test_data'] =  $this->mlm->get_test_result($data);

        $this->load->library('Ciqrcode');
        $params['data'] =  base_url('Render/download_pdf?report_id=' . base64_encode($data['report_id']) . '&sample_rg=' . base64_encode($update['report_data']->sample_reg_id));
        $params['level'] = 'H';
        $params['size'] = 1;
        $gc_no = $update['report_data']->gc_no;
        $params['savename'] = LOCAL_PATH . (($gc_no) ? $gc_no : rand(0000, 9999)) . '.png';
        $cer_po = $this->ciqrcode->generate($params); // genrate image
        // print_r($params);die;
        /* added by millan on 02-11-2021 */
        $qrcode = basename($params['savename']);

        $qrcode_upload = $this->uploadQRcode($qrcode);
        // Updated by Saurabh on 02-11-2021
        $update['qrcode'] = $qrcode_upload['aws_path'];
        $qr_update =  $this->mlm->update_data('generated_reports', array('qr_code_name' => $qrcode_upload['aws_path']), array('report_id' => $data['report_id']));
        /* added by millan on 02-11-2021 */

        if ($update['report_data']->branch_name == 'Gurgaon') {

            if ($update['report_data']->report_format == 6 || $update['report_data']->division_name == 'Hradline') {

                $pdf_body =  $this->generate_pdf('manage_lab/hggnreport', $update, 'aws_save');
            } elseif ($update['report_data']->report_format == 8 || $update['report_data']->division_name == 'Footwear') {

                $pdf_body =  $this->generate_pdf('manage_lab/ggn_footwear_report', $update, 'aws_save');
            } else {
                $pdf_body = $this->generate_pdf('manage_lab/ggnreport', $update, 'aws_save');
            }
        } elseif ($update['report_data']->branch_id == 2) {
            if ($update['report_data']->report_format == 4) {
                $pdf_body =  $this->generate_pdf('manage_lab/landmark_uaereport', $update, 'aws_save');
            } elseif ($update['report_data']->report_format == 3 || $update['report_data']->division_name == 'Textiles') {

                $pdf_body =   $this->generate_pdf('manage_lab/taxtile_uaereport', $update, 'aws_save');
            } elseif ($update['report_data']->report_format == 2 || $update['report_data']->division_name == 'Analytical') {

                $pdf_body = $this->generate_pdf('manage_lab/analytical_uaereport', $update, 'aws_save');
            } elseif ($update['report_data']->report_format == 1 || $update['report_data']->division_name == 'Toys') {
                $pdf_body = $this->generate_pdf('manage_lab/toys_uaereport', $update, 'aws_save');
            } elseif ($update['report_data']->report_format == 5 || $update['report_data']->division_name == 'Footwear') {
                $pdf_body = $this->generate_pdf('manage_lab/lf_uaereport', $update, 'aws_save');
            }
            // Added by Saurabh on 01-11-2021 for packaging division
            elseif ($update['report_data']->report_format == 5 || $update['report_data']->division_name == 'Packaging') {
                $this->generate_pdf('manage_lab/packaging_uaereport', $update);
            } else {
                $pdf_body =  $this->generate_pdf('manage_lab/taxtile_uaereport', $update, 'aws_save');
            }
        } elseif ($update['report_data']->branch_name == 'Dhaka') {

            $pdf_body =  $this->generate_pdf('manage_lab/bdreport', $update, 'aws_save');
        }

        if ($pdf_body) {
            $upload_path = $this->report_upload_aws($pdf_body, $gc_no . '.pdf');
            if ($upload_path) {
                // print_r($data);
                // $save_aws =  $this->mlm->update_data('generated_reports', array('manual_report_file' => $upload_path['aws_path'], 'status' => 'Report Approved'), array('report_id' => $data['report_id']));
                // echo $this->db->last_query();die;

                $save_aws =  $this->mlm->update_data('generated_reports', array('manual_report_file' => $upload_path['aws_path']), array('report_id' => $data['report_id']));

                if ($save_aws) {
                    unlink($params['savename']);

                    if ($status) {
                        $old_status_query = $this->db->select('status')->from('sample_registration')->where('sample_reg_id', $data['sample_reg_id'])->get();
                        $old_status = $old_status_query->row()->status;
                        $logDetails = array(
                            'module' => 'Samples',
                            'old_status' => $old_status,
                            'new_status' => 'Report Approved',
                            'sample_reg_id' => $data['sample_reg_id'],
                            'sample_assigned_lab_id' => /* $lab_id, */ '',
                            'action_message' => 'Report Approved',
                            'sample_job_id' => '',
                            'report_id' => '',
                            'report_status' => '',
                            'test_ids' => '',
                            'test_names' => '',
                            'test_newstatus' => '',
                            'test_oldStatus' => '',
                            'test_assigned_to' => '',
                            'source_module'    => 'Manage_lab',
                            'operation'        => 'approve_report',
                            'uidnr_admin'    => $this->session->userdata('user_data')->uidnr_admin,
                            'log_activity_on' => date("Y-m-d H:i:s")
                        );
                        // print_r($logDetails); die;
                        $this->mlm->save_user_log($logDetails);
                        $this->session->set_flashdata('success', 'Report Approved');
                        redirect($_SERVER['HTTP_REFERER']);
                    } else {
                        $this->session->set_flashdata('success', 'Report Approved');
                        redirect($_SERVER['HTTP_REFERER']);
                    }
                } else {
                    $this->session->set_flashdata('error', 'Report Not approve');
                    redirect($_SERVER['HTTP_REFERER']);
                }
            } else {
                $this->session->set_flashdata('error', 'AWS Path Not Found');
                redirect($_SERVER['HTTP_REFERER']);
            }
        } else {
            $this->session->set_flashdata('error', 'Pdf not generated');
            redirect($_SERVER['HTTP_REFERER']);
        }
    }

    public function regenerate_sample()
    {
        $id = $this->input->post();
        $sample_reg_id = $id['sample_reg_id'];
        $report_id = $id['report_id'];

        $data = $this->mlm->regenerate_sample($sample_reg_id, $report_id);
        if ($data) {
            $old_status_query = $this->db->select('status')->from('sample_registration')->where('sample_reg_id', $sample_reg_id)->get();
            $old_status = $old_status_query->row()->status;
            $logDetails = array(
                'module' => 'Samples',
                'old_status' => $old_status,
                'new_status' => 'Retest',
                'sample_reg_id' => $sample_reg_id,
                'sample_assigned_lab_id' => /* $lab_id, */ '',
                'action_message' => 'Report Regenerate',
                'sample_job_id' => '',
                'report_id' => '',
                'report_status' => '',
                'test_ids' => '',
                'test_names' => '',
                'test_newstatus' => '',
                'test_oldStatus' => '',
                'test_assigned_to' => '',
                'source_module'    => 'Manage_lab',
                'operation'        => 'regenerate_sample',
                'uidnr_admin'    => $this->session->userdata('user_data')->uidnr_admin,
                'log_activity_on' => date("Y-m-d H:i:s")
            );
            $this->mlm->save_user_log($logDetails);
            $msg = array(
                'status' => 1,
                'msg' => "Report Regenerate Successfully"
            );
            $this->session->set_flashdata('success', 'report Regenerate successfully');
        } else {
            $msg = array(
                'status' => 0,
                'msg' => "Error While Regenerating"
            );
        }
        echo json_encode($msg);
    }

    public function additional_test()
    {
        $id = $this->input->post();
        $sample_reg_id = $id['sample_reg_id'];
        $report_id = $id['report_id'];

        $data = $this->mlm->additional_test($sample_reg_id, $report_id);
        if ($data) {
            $old_status_query = $this->db->select('status')->from('sample_registration')->where('sample_reg_id', $sample_reg_id)->get();
            $old_status = $old_status_query->row()->status;
            $logDetails = array(
                'module' => 'Samples',
                'old_status' => $old_status,
                'new_status' => 'Sample send for Evaluation',
                'sample_reg_id' => $sample_reg_id,
                'sample_assigned_lab_id' => /* $lab_id, */ '',
                'action_message' => 'Report Regenerate',
                'sample_job_id' => '',
                'report_id' => '',
                'report_status' => '',
                'test_ids' => '',
                'test_names' => '',
                'test_newstatus' => '',
                'test_oldStatus' => '',
                'test_assigned_to' => '',
                'source_module'    => 'Manage_lab',
                'operation'        => 'additional_test',
                'uidnr_admin'    => $this->session->userdata('user_data')->uidnr_admin,
                'log_activity_on' => date("Y-m-d H:i:s")
            );
            // print_r($logDetails); die;
            $this->mlm->save_user_log($logDetails);
            $msg = array(
                'status' => 1,
                'msg' => "Sample send for Evaluation"
            );
            $this->session->set_flashdata('success', 'Sample send for Evaluation successfully');
        } else {
            $msg = array(
                'status' => 0,
                'msg' => "Error While sending"
            );
        }
        echo json_encode($msg);
    }

    public function check_file_upload($file_name)
    {
        if ($file_name['name'] != '' && $file_name['type'] == 'image/jpeg' || $file_name['type'] == 'image/png') {
            // Added by Saurabh on 20-10-2021 to compress image
            $file_temp = $this->compressImage($file_name['tmp_name'], LOCAL_PATH . 'thumb/' . $file_name['name'], 25);
            $file_data = array(
                'name'      => $file_name['name'],
                'tmp_name'  => $file_temp
            );
            // Added by Saurabh on 20-10-2021 to compress image
            $image = $this->multiple_upload_image($file_data);
            if (!empty($image)) {
                $img['image'] = $image['aws_path'];
                // Added by Saurabh on 20-10-2021 to compress image
                $thumb_name = $this->generate_image_thumbnail($file_data['name'], $file_data['tmp_name'], THUMB_PATH);
                // Added by Saurabh on 20-10-2021 to compress image
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

    // Added by Saurabh on 20-10-2021
    public function compressImage($source, $destination, $quality)
    {
        ini_set('memory_limit', '-1');
        // Get image info 
        $imgInfo = getimagesize($source);
        $mime = $imgInfo['mime'];
        // Create a new image from file 
        switch ($mime) {
            case 'image/jpeg':
                $image = imagecreatefromjpeg($source);
                break;
            case 'image/png':
                $image = imagecreatefrompng($source);
                break;
                // case 'image/gif': 
                //     $image = imagecreatefromgif($source); 
                //     break; 
            default:
                $image = imagecreatefromjpeg($source);
        }
        // Save image 
        imagejpeg($image, $destination, $quality);

        // Return compressed image 
        return $destination;
    }
    // Added by Saurabh on 20-10-2021

    public function upload_samples_images()
    {
        if ($this->input->post()) {
            for ($count = 0; $count < count($_FILES["sample_image"]["name"]); $count++) {
                $image['type'] = $_FILES["sample_image"]["type"][$count];
                $image['name'] = $_FILES["sample_image"]["name"][$count];
                $image['tmp_name'] = $_FILES["sample_image"]["tmp_name"][$count];
                $valid_file = $this->check_file_upload($image);
                // print_r($valid_file);die;
                // $mult[] = '';
                if ($valid_file) {
                    $data['image_file_path'] = $valid_file['image'];
                    $data['image_thumb_file_path'] = $valid_file['thumb'];
                    $data['sample_reg_id'] = $this->input->post('sample_reg_id');
                    $data['created_by'] = $this->admin_id();
                    $data['created_on'] = date('Y-m-d H:i:s');
                    $data['compress_status'] = 1;
                    $mult[] = $data;
                } else {
                    break;
                    echo json_encode(["message" => "Please select a JPEG image", "status" => 0]);
                    exit;
                }
            }
            $result = $this->mlm->insert_multiple_data("sample_photos", $mult);
            // echo $this->db->last_query();die;
            if ($result) {
                $old_status_query = $this->db->select('status')->from('sample_registration')->where('sample_reg_id', $this->input->post('sample_reg_id'))->get();
                $old_status = $old_status_query->row()->status;
                $logDetails = array(
                    'module' => 'Samples',
                    'old_status' => $old_status,
                    'new_status' => '',
                    'sample_reg_id' => $this->input->post('sample_reg_id'),
                    'sample_assigned_lab_id' => /* $lab_id, */ '',
                    'action_message' => 'Sample Images uploaded',
                    'sample_job_id' => '',
                    'report_id' => '',
                    'report_status' => '',
                    'test_ids' => '',
                    'test_names' => '',
                    'test_newstatus' => '',
                    'test_oldStatus' => '',
                    'test_assigned_to' => '',
                    'source_module'    => 'Manage_lab',
                    'operation'        => 'upload_samples_images',
                    'uidnr_admin'    => $this->session->userdata('user_data')->uidnr_admin,
                    'log_activity_on' => date("Y-m-d H:i:s")
                );
                $this->mlm->save_user_log($logDetails);
                echo json_encode(["message" => "Images saved successfully", "status" => 1]);
            } else {
                echo json_encode(["message" => "Something went wrong!.", "status" => 0]);
            }
        }
    }

    public function upload_refernce_images()
    {
        if ($this->input->post()) {
            for ($count = 0; $count < count($_FILES["reference_image"]["name"]); $count++) {
                $image['type'] = $_FILES["reference_image"]["type"][$count];
                $image['name'] = $_FILES["reference_image"]["name"][$count];
                $image['tmp_name'] = $_FILES["reference_image"]["tmp_name"][$count];
                $valid_file = $this->check_file_upload($image);
                // print_r($valid_file);die;
                // $mult[] = '';
                if ($valid_file) {
                    $data['image_file_path'] = $valid_file['image'];
                    $data['image_thumb_file_path'] = $valid_file['thumb'];
                    $data['sample_reg_id'] = $this->input->post('sample_reg_id');
                    $data['created_by'] = $this->admin_id();
                    $data['created_on'] = date('Y-m-d H:i:s');
                    $mult[] = $data;
                } else {
                    break;
                    echo json_encode(["message" => "Please select a JPEG image", "status" => 0]);
                    exit;
                }
            }
            $result = $this->mlm->insert_multiple_data("report_reference_images", $mult);
            // echo $this->db->last_query();die;
            if ($result) {
                $old_status_query = $this->db->select('status')->from('sample_registration')->where('sample_reg_id', $this->input->post('sample_reg_id'))->get();
                $old_status = $old_status_query->row()->status;
                $logDetails = array(
                    'module' => 'Samples',
                    'old_status' => $old_status,
                    'new_status' => '',
                    'sample_reg_id' => $this->input->post('sample_reg_id'),
                    'sample_assigned_lab_id' => /* $lab_id, */ '',
                    'action_message' => 'Reference Images uploaded',
                    'sample_job_id' => '',
                    'report_id' => '',
                    'report_status' => '',
                    'test_ids' => '',
                    'test_names' => '',
                    'test_newstatus' => '',
                    'test_oldStatus' => '',
                    'test_assigned_to' => '',
                    'source_module'    => 'Manage_lab',
                    'operation'        => 'upload_refernce_images',
                    'uidnr_admin'    => $this->session->userdata('user_data')->uidnr_admin,
                    'log_activity_on' => date("Y-m-d H:i:s")
                );
                $this->mlm->save_user_log($logDetails);
                echo json_encode(["message" => "Images saved successfully", "status" => 1]);
            } else {
                echo json_encode(["message" => "Something went wrong!.", "status" => 0]);
            }
        }
    }

    public function send_to_record_finding()
    {
        $id = $this->input->post();
        //  print_r($id);die;
        $res = $this->mlm->send_to_record_finding($id['sample_reg_id']);

        if ($res) {
            $old_status_query = $this->db->select('status')->from('sample_registration')->where('sample_reg_id', $this->input->post('sample_reg_id'))->get();
            $old_status = $old_status_query->row()->status;
            $logDetails = array(
                'module' => 'Samples',
                'old_status' => $old_status,
                'new_status' => '',
                'sample_reg_id' => $this->input->post('sample_reg_id'),
                'sample_assigned_lab_id' => /* $lab_id, */ '',
                'action_message' => 'sent for record finding',
                'sample_job_id' => '',
                'report_id' => '',
                'report_status' => '',
                'test_ids' => '',
                'test_names' => '',
                'test_newstatus' => '',
                'test_oldStatus' => '',
                'test_assigned_to' => '',
                'source_module'    => 'Manage_lab',
                'operation'        => 'send_to_record_finding',
                'uidnr_admin'    => $this->session->userdata('user_data')->uidnr_admin,
                'log_activity_on' => date("Y-m-d H:i:s")
            );
            $this->mlm->save_user_log($logDetails);
            echo json_encode(["message" => "Sample sent for record finding successfully", "status" => 1]);
        } else {
            echo json_encode(["message" => "Something went wrong!.", "status" => 0]);
        }
    }

    public function get_report_sample_images()
    {
        $sample_reg_id = $this->input->post('sample_reg_id');
        $data = $this->mlm->get_fields_by_id("sample_photos", 'image_id,image_file_path,comment,image_sequence', $sample_reg_id, "sample_reg_id");
        echo json_encode($data);
    }

    public function get_report_reference_image()
    {
        $sample_reg_id = $this->input->post('sample_reg_id');
        $data = $this->mlm->get_fields_by_id("report_reference_images", 'report_ref_image_id,image_file_path', $sample_reg_id, "sample_reg_id");
        echo json_encode($data);
    }

    public function delete_report_sample_image()
    {
        $image_id = $this->input->post('image_id');
        $delete = $this->mlm->remove_report_sample_image($image_id);
        if ($delete) {
            $old_status_query = $this->db->select('status')->from('sample_registration')->where('sample_reg_id', $this->input->post('sample_reg_id'))->get();
            $old_status = $old_status_query->row()->status;
            $logDetails = array(
                'module' => 'Samples',
                'old_status' => $old_status,
                'new_status' => '',
                'sample_reg_id' => $this->input->post('sample_reg_id'),
                'sample_assigned_lab_id' => /* $lab_id, */ '',
                'action_message' => 'Deleted sample images',
                'sample_job_id' => '',
                'report_id' => '',
                'report_status' => '',
                'test_ids' => '',
                'test_names' => '',
                'test_newstatus' => '',
                'test_oldStatus' => '',
                'test_assigned_to' => '',
                'source_module'    => 'Manage_lab',
                'operation'        => 'delete_report_sample_image',
                'uidnr_admin'    => $this->session->userdata('user_data')->uidnr_admin,
                'log_activity_on' => date("Y-m-d H:i:s")
            );
            $this->mlm->save_user_log($logDetails);
            echo json_encode(["message" => "Image deleted successfully.", "status" => 1]);
        } else {
            echo json_encode(["message" => "Something went wrong!.", "status" => 0]);
        }
    }

    public function delete_report_reference_image()
    {
        $image_id = $this->input->post('image_id');
        $delete = $this->mlm->remove_report_reference_image($image_id);
        if ($delete) {
            $old_status_query = $this->db->select('status')->from('sample_registration')->where('sample_reg_id', $this->input->post('sample_reg_id'))->get();
            $old_status = $old_status_query->row()->status;
            $logDetails = array(
                'module' => 'Samples',
                'old_status' => $old_status,
                'new_status' => '',
                'sample_reg_id' => $this->input->post('sample_reg_id'),
                'sample_assigned_lab_id' => /* $lab_id, */ '',
                'action_message' => 'Deleted reference images',
                'sample_job_id' => '',
                'report_id' => '',
                'report_status' => '',
                'test_ids' => '',
                'test_names' => '',
                'test_newstatus' => '',
                'test_oldStatus' => '',
                'test_assigned_to' => '',
                'source_module'    => 'Manage_lab',
                'operation'        => 'delete_report_reference_image',
                'uidnr_admin'    => $this->session->userdata('user_data')->uidnr_admin,
                'log_activity_on' => date("Y-m-d H:i:s")
            );
            $this->mlm->save_user_log($logDetails);
            echo json_encode(["message" => "Image deleted successfully.", "status" => 1]);
        } else {
            echo json_encode(["message" => "Something went wrong!.", "status" => 0]);
        }
    }

    public function save_comment()
    {
        $data = $this->input->post('image');
        foreach ($data as $key => $input) {
            $image_id = $input['image_id'];
            $comment = $input['comment'];
            $sequence = $input['sequence'];
            $inputs = array(
                'comment'    => $comment,
                'image_sequence' => $sequence
            );
            $update = $this->mlm->update_comment($inputs, $image_id);
        }
        if ($update) {
            echo json_encode(["message" => "Data saved successfully.", "status" => 1]);
        } else {
            echo json_encode(["message" => "Something went wrong!.", "status" => 0]);
        }
    }

    public function get_generate_report_data()
    {
        $ids =   $this->input->post();
        $data['report_data'] =  $this->mlm->get_generate_report_data($ids);
        $data['parts_data'] =  $this->mlm->get_parts_Details($ids);
        $data['report_format'] = $this->mlm->get_report_format($ids);
        // print_r($data1);die;
        if ($data) {
            $generate_data = array(
                'ulr_no_flag' => $data['report_data']->ulr_no_flag,
                'issuance_date' => $data['report_data']->issuance_date,
                'remark' => html_entity_decode($data['report_data']->remark),
                'manual_report_result' => $data['report_data']->manual_report_result,
                'manual_report_remark' => $data['report_data']->manual_report_remark,
                'sample_images_flag' => $data['report_data']->sample_images_flag,
                'name' => $data['report_data']->name,
                'parts' => $data['parts_data'],
                'report_format' => $data['report_format']
            );
            echo json_encode($generate_data);
        }
    }

    public function get_release_to_client_data()
    {
        $data = $this->input->post();
        $email = $this->mlm->get_release_to_client_data($data);

        echo json_encode($email);
    }

    public function Release_to_client()
    {
        $data = $this->input->post();
        // echo "<pre>";print_r($data);die;
        $file = $this->mlm->get_row('gr.manual_report_file', 'generated_reports gr', ['gr.report_id' => $data['report_id']]);
        //    print_r($file);die;
        if ($data['mail'] == 1) {
            $send_mail = send_mail_while_Release_to_Client($data['to'], NULL, $data['cc'], $data['bcc'], $data['email_body'], $data['subject'], $file->manual_report_file, $file->manual_report_file);
        }
        // if($send_mail){
        $done = $this->mlm->update_data('sample_registration sr', ['released_to_client' => '1', 'status' => 'Report Released To Client'], ['sr.sample_reg_id' => $data['sample_reg_id']]);
        $this->mlm->update_data('generated_reports', ['status' => 'Report Released To Client'], ['report_id' => $data['report_id']]);
        if ($done) {
            $old_status_query = $this->db->select('status')->from('sample_registration')->where('sample_reg_id', $this->input->post('sample_reg_id'))->get();
            $old_status = $old_status_query->row()->status;
            $logDetails = array(
                'module' => 'Samples',
                'old_status' => $old_status,
                'new_status' => 'Report released to client',
                'sample_reg_id' => $this->input->post('sample_reg_id'),
                'sample_assigned_lab_id' => /* $lab_id, */ '',
                'action_message' => 'Report released to client',
                'sample_job_id' => '',
                'report_id' => '',
                'report_status' => '',
                'test_ids' => '',
                'test_names' => '',
                'test_newstatus' => '',
                'test_oldStatus' => '',
                'test_assigned_to' => '',
                'source_module'    => 'Manage_lab',
                'operation'        => 'Release_to_client',
                'to_users'         => $data['to'],
                'cc_users'         => $data['cc'],
                'bcc_users'         => $data['bcc'],
                'uidnr_admin'    => $this->session->userdata('user_data')->uidnr_admin,
                'log_activity_on' => date("Y-m-d H:i:s")
            );
            $this->mlm->save_user_log($logDetails);
            $this->session->set_flashdata('success', 'Report Release to client successfully');
            redirect($_SERVER['HTTP_REFERER']);
        } else {
            $this->session->set_flashdata('error', 'Error while saving data');
            redirect($_SERVER['HTTP_REFERER']);
        }
        // }
        // else{
        //     $this->session->set_flashdata('error', 'Error while Sending Email');
        //     redirect($_SERVER['HTTP_REFERER']);

        // }
    }

    public function deleteimage()
    {
        $id = $this->input->post();
        // print_r($id['id']);die;
        $delete =  $this->mlm->delete_row('report_generated_images', ['images_id' => $id['id']]);
        if ($delete) {
            $msg = array(
                'status' => 1,
                'msg' => 'Image Delete Successfull'
            );
        } else {
            $msg = array(
                'status' => 0,
                'msg' => 'Something Went wrong'
            );
        }
        echo json_encode($msg);
    }

    // ajit code start 31-03-2021
    public function release_to_client_all_report()
    {
        if ($this->session->has_userdata('release_id_report')) {
            $sample_reg_ids = $this->session->userdata('release_id_report');

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
            $result =  $this->mlm->update_relase_to_report($sample_reg_ids);
        } else {
            $result = false;
        }

        if ($result) {
            if ($this->session->has_userdata('release_id_report')) {
                $this->session->unset_userdata('release_id_report');
            }
            if ($this->session->has_userdata('release_all_report')) {
                $this->session->set_userdata('release_all_report', 0);
            }
            $msg = array(
                'status' => '1',
                'msg' => 'Report Released to client successfully'
            );
            $this->session->set_flashdata('success', 'Report Released to client successfully');
        } else {
            $msg = array(
                'status' => '0',
                'msg' => 'Error in Releasing'
            );
            $this->session->set_flashdata('error', 'Error in Releasing');
        }

        echo json_encode($msg);
    }

    public function make_session_report()
    {
        $sample_reg_id = $this->input->post('sample_reg_id');
        $status = $this->input->post('status');
        $all = $this->input->post('chek_all');
        if ($sample_reg_id != null) {
            if ($status == 'true') {
                if ($this->session->has_userdata('release_id_report')) {
                    $release = $this->session->userdata('release_id_report');
                    $release[$sample_reg_id] = $sample_reg_id;
                } else {
                    $release = [$sample_reg_id => $sample_reg_id];
                }
            } else {
                if ($this->session->has_userdata('release_id_report')) {
                    $release = $this->session->userdata('release_id_report');
                    unset($release[$sample_reg_id]);
                } else {
                    $release = array();
                }
            }
        } else {
            if ($status == 'true') {
                $release = array();

                $ids = $this->mlm->get_result('sample_reg_id', 'sample_registration', ['status' => 'Report Approved', 'released_to_client' => '0']);

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
            $this->session->set_userdata('release_all_report', '1');
        } else {
            $this->session->set_userdata('release_all_report', '0');
        }
        $this->session->set_userdata('release_id_report', $release);
    }
    // end

    public function delete_file()
    {
        $this->delete_file_from_aws();
    }

    // Added by CHANDAN --13-05-2022
    public function delete_parameters()
    {
        $result = $this->mlm->update_data('record_finding_parameters_body', ['is_deleted' => 1], ['id' => $this->input->post('para_id')]);
        if ($result) {
            $log_details = array(
                'source_module'     => 'Manage_lab',
                'record_id'         => $this->input->post('para_id'),
                'created_on'         => date("Y-m-d h:i:s"),
                'created_by'         => $this->user,
                'action_taken'         => 'delete_parameter',
                'text'                 => 'Record has been deleted.'
            );
            $this->mlm->insert_data('user_log_history', $log_details);

            $response = array(
                'message'   => 'Record has been deleted.',
                'code'      => 1
            );
        } else {
            $response = array(
                'message'   => 'Something went wrong.',
                'code'      => 0
            );
        }
        echo json_encode($response);
    }

    public function getPartName()
    {
        echo json_encode($this->mlm->get_parts([
            'sample_reg_id' => $this->input->post('sample_reg_id'),
            'sample_test_id' => $this->input->post('sample_test_id')
        ]));
    }

    // Added by CHANDAN --06-07-2022
    public function revise_report_additional_test()
    {
        $data = array(
            'gr_revise_type'            => $this->input->post('action'),
            'gr_revise_reason'          => $this->input->post('reason'),
            'gr_revise_requester_id'    => $this->user,
            'gr_revise_flag'            => 1
        );
        $result = $this->mlm->update_data('generated_reports', $data, ['report_id' => $this->input->post('report_id')]);
        if ($result) {
            $getData = $this->mlm->get_row('sample_reg_id', 'generated_reports', ['report_id' => $this->input->post('report_id')]);
            if (!empty($getData->sample_reg_id)) {

                $updateData = $this->mlm->update_data('sample_registration', ['status' => 'Revise Report Approval'], ['sample_reg_id' => $getData->sample_reg_id]);

                if ($updateData) {
                    /*
                    $division_id = $this->session->userdata('user_data')->default_division_id;
                    $this->db->select('ad.admin_email');
                    $this->db->from('sample_registration sr');
                    $this->db->join('admin_users ad', 'sr.division_id = ad.default_division_id', 'inner');
                    $this->db->where(['sr.sample_reg_id' => $getData->sample_reg_id, 'sr.division_id' => $division_id, 'ad.id_admin_role' => 3]);
                    $getAdmin = $this->db->get();

                    $toEmail = array();
                    if ($getAdmin->num_rows() > 0) {
                        $adData = $getAdmin->result();
                        foreach ($adData as $key => $val) {
                            if (isset($val->admin_email) && !empty($val->admin_email)) {
                                array_push($toEmail, $val->admin_email);
                            }
                        }
                    }
                    */

                    $toEmail = array(
                        'developer.cps08@basilrl.com'
                    );

                    if (!empty($toEmail)) {
                        $sub = 'Request for ' . $this->input->post('action');
                        $msg = '<p>Dear Sir/Mam,</p>';
                        $msg .= '<p>' . $this->input->post('reason') . '</p>';
                        send_mail_while_Release_to_Client($toEmail, FROM, CCQUALITY, $bcc = NULL, $msg, $sub, $attachment_file = NULL, $attachment_path = NULL, $report = false);
                    }
                    $log_details = array(
                        'source_module'     => 'Manage_lab',
                        'record_id'         => $getData->sample_reg_id,
                        'created_on'        => date("Y-m-d h:i:s"),
                        'created_by'        => $this->user,
                        'action_taken'      => 'revise_report_additional_test',
                        'text'              => $this->input->post('action') . '!! with reason: ' . $this->input->post('reason')
                    );
                    $this->mlm->insert_data('user_log_history', $log_details);
                }
            }
            $response = array(
                'message'   => 'Request has been send for Approval.',
                'code'      => 1
            );
        } else {
            $response = array(
                'message'   => 'Something went wrong.',
                'code'      => 0
            );
        }
        echo json_encode($response);
    }
}
