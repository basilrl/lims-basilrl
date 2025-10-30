<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Low_item_notification extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Low_item_notification_model');
        // $this->permission('Role/index');
        $checkUser = $this->session->userdata('user_data');
        $this->user = $checkUser->uidnr_admin;
    }

    public function index()
    {
        $data['units_list'] =  $this->Low_item_notification_model->fetch_units(); // added by millan on 10-03-2021
        $this->load_view('Low_item_notification/index', $data);
    }

    public function store_listing($search, $page = 0)
    {
        $where = NULL;
        if (!empty($search) && $search != 'NULL') {
            $search = base64_decode($search);
        } else {
            $search = NULL;
        }
        $per_page = 10;
        if ($page != 0) {
            $page = ($page - 1) * $per_page;
        }
        $total_row = $this->Low_item_notification_model->getCategory_list(NULL, NULL, $search, $where, '1');
        $config['base_url'] = base_url('Role/role_listing');
        $config['use_page_numbers'] = TRUE;
        $config['total_rows'] = $total_row;
        $config['per_page'] = $per_page;
        $config['full_tag_open']    = '<div class="pagination text-center small"><nav><ul class="pagination">';
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
        $this->pagination->initialize($config);

        $data['pagination'] =  $this->pagination->create_links();

        $result = $this->Low_item_notification_model->getCategory_list($per_page, $page, $search, $where);
        $html = '';
        if ($result) {
            foreach ($result as $value) {
                $page++;
                $html .= '<tr>';
                $html .= '<th scope="col">' . $page . '</th>';
                $html .= '<td>' . $value->item_name . '</td>';
                $html .= '<td>' . $value->current_stock . ' ' . $value->current_stock_unit_name . '</td>'; // updated by millan on 22-03-2021
                $html .= '<td>' . $value->min_quantity_required . ' ' . $value->min_quantity_required_unit_name . '</td>'; // updated by millan on 22-03-2021
                $html .= '<td>' . $value->store_name . '</td>';
                $html .= '<td>';
                if ($this->permission_action('Low_item_notification/log')) {
                    $html .= '<a href="javascript:void(0);" title="User Log" class="btn btn-sm edit_role" data-toggle="modal" data-id="' . base64_encode($value->item_id) . '" data-target="#add_Store"><img src="' . base_url('assets/images/log-view.png') . '" alt="BASIL" class="edit_application_data" ></a> ';

                    // added by millan on 10-03-2021
                    if (empty($value->aws_path)) {
                        $html .= '<a href="javascript:void(0);" title="Add Item Requirement Details" class="btn btn-sm item_req_modal" data-toggle="modal" 
                        data-id="' . base64_encode($value->item_id) . '" data-target="#add_ItemDetails"><img src="' . base_url('assets/images/add.png') . '" alt="Add Item Requirement Details"></a> ';
                    }

                    // added by millan on 10-03-2021
                    if ($value->item_req_id > 0 && empty($value->aws_path)) {
                        $html .= '<a href="javascript:void(0);" title="Edit Item Requirement Details" class="btn btn-sm edit_item_req_modal" data-toggle="modal" 
                        data-id="' . base64_encode($value->item_req_id) . '" data-target="#edit_ItemDetails"><img src="' . base_url('assets/images/edit_first_page.png') . '" alt="Edit Item Requirement Details"></a> ';
                    }

                    // added by millan on 10-03-2021
                    if ($value->item_req_id > 0 && $value->lab_manager_id > 0 && empty($value->aws_path)) {
                        $html .= '<a href="javascript:void(0);" title="Send Report to Lab Manager" data-target="#report_lowItem" data-toggle="modal" class="btn btn-sm ger_report" data-id="' . base64_encode($value->item_req_id) . '" data-req="' . base64_encode($value->item_req_id) . '"><img src="' . base_url('assets/images/Report_icon.gif') . '" alt="Send Report to Lab Manager"></a> ';
                    }

                    // added by millan on 15-03-2021
                    if (($value->item_req_id > 0 && empty($value->lab_manager_reason) && empty($value->lab_manager_status))) {
                        $html .= '<a href="javascript:void(0);" title="Approved from Lab Manager" data-target="#lab_manager_approval" data-toggle="modal" class="btn btn-sm reason_lab_accepted" data-id="' . base64_encode($value->item_req_id) . '"><img src="' . base_url('assets/images/accept.png') . '" alt="Approved from Lab Manager"></a> ';

                        $html .= '<a href="javascript:void(0);" title="Rejected from Lab Manager" data-target="#lab_manager_rejection" data-toggle="modal" class="btn btn-sm reason_lab_rejected" data-id="' . base64_encode($value->item_req_id) . '"><img src="' . base_url('assets/images/action_stop.gif') . '" alt="Rejected from Lab Manager"></a> ';
                    }

                    // added by millan on 23-03-2021
                    if (($value->item_req_id > 0 && !empty($value->aws_path) && $value->aws_path != '')) {
                        $html .= '<a href=' . $value->aws_path . ' title="View Approved Report" class="btn btn-sm view_rp_ap" data-id="' . base64_encode($value->item_req_id) . '"><img src="' . base_url('assets/images/view-report.png') . '" alt="View Approved Report" width="20px"></a> ';

                        $html .= '<a href="javascript:void(0);" title="Send Mail To Vendor" data-target="#vendor_mail" data-toggle="modal" class="btn btn-sm mail_vendor" data-id="' . base64_encode($value->item_req_id) . '"><img src="' . base_url('assets/images/email.png') . '" alt="Send Mail To Vendor"></a> ';
                    }
                }
                $html .= '</td>';
                $html .= '/<tr>';
            }
        } else {
            $html .= '<tr><td colspan="14"><h4>NO RECORD FOUND</h4></td></tr>';
        }
        $data['result'] = $html;
        echo json_encode($data);
    }


    public function store_userlog_dtlsview()
    {
        $post = $this->input->post();
        $post['id'] = base64_decode($post['id']);
        $data = $this->Low_item_notification_model->store_userlog_dtlsview($post['id']);
        $html = '<table class="table table-sm table-hover"><thead><tr><th>USER</th><th>ACTIVITY ON</th><th>ACTION</th></tr></thead><tbody>';
        if ($data) {
            foreach ($data as $key => $value) {
                $html .= '<tr>';
                $html .= '<td>' . $value->created_by . '</td>';
                $html .= '<td>' . (($value->log_activity_on) ? date('d-m-Y', strtotime($value->log_activity_on)) : '') . '</td>';
                $html .= '<td>' . $value->action_message . '</td>';
                $html .= '</tr>';
            }
        } else {
            $html .= '<tr><td colspan="4">NO RECORD FOUND</td></tr>';
        }
        $html .= '</tbody></table>';
        echo json_encode($html);
    }

    // added by millan on 10-03-2021
    public function fetch_item_details()
    {
        $id = $this->input->post();
        $item_id = base64_decode($id['item_id']);
        $data = $this->Low_item_notification_model->fetch_item_details($item_id);
        echo json_encode($data);
    }

    // added by millan on 10-03-2021
    public function add_requiremnetdetails()
    {
        $this->form_validation->set_error_delimiters('<span class = "text-dangeer">', '</span>');
        $this->form_validation->set_rules('item_name', 'Item Name', 'required');
        $this->form_validation->set_rules('category_name', 'Category Name', 'required');
        $this->form_validation->set_rules('min_quantity_required', 'Minimum Quantity Required', 'required|trim');
        $this->form_validation->set_rules('current_requirement', 'Current Requiremnet', 'required|trim');
        $this->form_validation->set_rules('unit_id', 'Unit of Item', 'required');
        $this->form_validation->set_rules('requirement_reason', 'Requirement Reason', 'required|trim');
        if ($this->form_validation->run() == false) {
            $data = array(
                'error' => $this->form_validation->error_array(),
                'status' => 0
            );
        } else {
            $checkUser = $this->session->userdata();
            $store_data = array();
            $fetch_data = $this->input->post();
            $store_data['item_id'] = $fetch_data['item_id'];
            $store_data['item_name'] = $fetch_data['item_name'];
            $store_data['category_name'] = $fetch_data['category_name'];
            $store_data['min_quantity_required'] = $fetch_data['min_quantity_required'];
            $store_data['current_requirement'] = $fetch_data['current_requirement'];
            $store_data['unit_id'] = $fetch_data['unit_id'];
            $store_data['requirement_reason'] = $fetch_data['requirement_reason'];
            $store_data['created_by'] = $checkUser['user_data']->uidnr_admin;
            $store_data['created_on'] = date("Y-m-d h:i:s");
            if (!empty($fetch_data)) {
                $data_insert = $this->Low_item_notification_model->insert_data('item_requirement', $store_data);
                if ($data_insert) {
                    $this->session->set_flashdata('success', 'Item Requirement Stored Successfully');
                    $data = array(
                        'status' => 1
                    );
                } else {
                    $this->session->set_flashdata('error', 'Error in Storing Item Requirement');
                    $data = array(
                        'status' => 0
                    );
                }
            } else {
                $this->session->set_flashdata('error', 'Data Not Found !!');
                $data = array(
                    'status' => 0
                );
            }
            echo json_encode($data);
        }
    }

    // added by millan on 10-03-2021
    public function fetch_item_requirement_details()
    {
        $id = $this->input->post();
        $item_req_id = base64_decode($id['item_req_id']);
        $data = $this->Low_item_notification_model->fetch_item_requirement_details($item_req_id);
        echo json_encode($data);
    }

    // added by millan on 10-03-2021
    public function update_requiremnetdetails()
    {
        $this->form_validation->set_error_delimiters('<span class = "text-dangeer">', '</span>');
        $this->form_validation->set_rules('item_name', 'Item Name', 'required');
        $this->form_validation->set_rules('category_name', 'Category Name', 'required');
        $this->form_validation->set_rules('min_quantity_required', 'Minimum Quantity Required', 'required|trim');
        $this->form_validation->set_rules('current_requirement', 'Current Requiremnet', 'required|trim');
        $this->form_validation->set_rules('unit_id', 'Unit of Item', 'required');
        $this->form_validation->set_rules('requirement_reason', 'Requirement Reason', 'required|trim');
        if ($this->form_validation->run() == false) {
            $data = array(
                'error' => $this->form_validation->error_array(),
                'status' => 0
            );
        } else {
            $checkUser = $this->session->userdata();
            $data_get = $this->input->post();
            $update_data = array();
            $update_data['item_id'] = $data_get['item_id'];
            $update_data['item_name'] = $data_get['item_name'];
            $update_data['category_name'] = $data_get['category_name'];
            $update_data['min_quantity_required'] = $data_get['min_quantity_required'];
            $update_data['current_requirement'] = $data_get['current_requirement'];
            $update_data['unit_id'] = $data_get['unit_id'];
            $update_data['requirement_reason'] = $data_get['requirement_reason'];
            $update_data['updated_by'] = $checkUser['user_data']->uidnr_admin;
            $update_data['updated_on'] = date('y-m-d h:i:s');
            $where['item_req_id'] = $data_get['item_req_id'];
            if (!empty($data_get)) {
                $data_updated = $this->Low_item_notification_model->update_data('item_requirement', $update_data, $where);
                if ($data_updated) {
                    $this->session->set_flashdata('success', 'Item Requirement Updated Successfully');
                    $data = array(
                        'status' => 1
                    );
                } else {
                    $this->session->set_flashdata('error', 'Error in Updating Item Requirement');
                    $data = array(
                        'status' => 0
                    );
                }
            } else {
                $this->session->set_flashdata('error', 'Data Not Found');
                $data = array(
                    'status' => 0
                );
            }
        }
        echo json_encode($data);
    }

    // added by millan on 10-03-2021
    public function generate_report()
    {
        $get_id = $this->input->get();
        $item_req_id = base64_decode($get_id['item_req_id']);
        if ($item_req_id || !empty($item_req_id)) {
            $data['result'] = $this->Low_item_notification_model->fetch_data_for_report($item_req_id);
            $data['lbmn_sign'] = $this->getS3Url($data['result']->lm_sign);
            $data['gm_sign'] = $this->Low_item_notification_model->get_row('sign_path', 'admin_signature', ['admin_id' => 45]);
            $data['gm_signature'] =  $this->getS3Url($data['gm_sign']->sign_path);
            $data['gm_approver'] = $this->Low_item_notification_model->get_row('CONCAT(admin_fname, " ",admin_lname) gm_admin', 'admin_profile', ['uidnr_admin' => 45]);
            $data['gm_desg'] = $this->Low_item_notification_model->get_designation_gm();
            if ($data) {
                $this->generate_pdf('Low_item_notification/item_requirement_report', $data);
            } else {
                echo "NO RECORD FOUND";
            }
        } else {
            echo "ID NOT FOUND";
        }
    }

    // added by millan on 10-03-2021
    public function approver_mail()
    {
        $get_id = $this->input->post();
        $item_req_id = base64_decode($get_id['item_req_id']);
        $data['result'] = $this->Low_item_notification_model->fetch_signature($item_req_id);
        $gm_sign = $this->Low_item_notification_model->get_row('sign_path', 'admin_signature', ['admin_id' => 45]);
        if (!empty($gm_sign) && count($gm_sign) > 0) {
            $data['gm_sign'] = $gm_sign->sign_path;
        } else {
            $data['gm_sign'] = "";
        }
        $btn = '<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>';
        if ($item_req_id && !empty($item_req_id)) {
            $get =  '<div class="row">
                        <div class="col-sm-12" style="min-height:70vh">
                            <iframe width="100%" height="100%" src="' . base_url() . 'Low_item_notification/generate_report?item_req_id=' . base64_encode($item_req_id) . '"></iframe>
                        </div>
                    </div>';
            if (!empty($data['result']->lm_sign) && $data['result']->lm_sign != '') {
                $get .=    '<div class="row"> <div class="col-sm-6">
                        <img src="' . $this->getS3Url($data['result']->lm_sign) . '" alt="LAB MANAGER SIGNATURE" width="150px" style="text-align:left !important;">
                    </div>
                    <div class="col-md-6">
                        <img src="' . $this->getS3Url($data['gm_sign']) . '" alt="GENERAL MANAER SIGNATURE" width="150px" style="text-align:right;">
                    </div> </div>';
            }
            if (empty($data['result']->lm_sign) && ($data['result']->lab_manager_status == "Accepted")) {
                $btn .= '<button type="submit" class="btn btn-primary mail_approval_btn"> Send Mail to Lab Manager</button>';
            } else {
                $btn .= '<button type="submit" class="btn btn-primary approval_lmgm" data-id="' . $item_req_id . '" > Approved </button>';
            }
        } else {
            $get = '<h1> NO RECORD FOUND </h1>';
        }
        echo json_encode(['html' => $get, 'btn' => $btn]);
    }

    // added by millan on 15-03-2021
    public function lm_approval()
    {
        $this->form_validation->set_error_delimiters('<span class = "text-dangeer">', '</span>');
        $this->form_validation->set_rules('lab_manager_reason', 'Reason', 'required|trim');
        if ($this->form_validation->run() == false) {
            $data = array(
                'error' => $this->form_validation->error_array(),
                'status' => 0
            );
        } else {
            $checkUser = $this->session->userdata();
            $data_get = $this->input->post();
            $update_data = array();
            $update_data['item_id'] = $data_get['item_id'];
            $update_data['lab_manager_reason'] = $data_get['lab_manager_reason'];
            $update_data['updated_by'] = $checkUser['user_data']->uidnr_admin;
            $update_data['updated_on'] = date('y-m-d h:i:s');
            $update_data['lab_manager_status'] = 'Accepted';
            $where['item_req_id'] = $data_get['item_req_id'];
            if (!empty($data_get)) {
                $data_updated = $this->Low_item_notification_model->update_data('item_requirement', $update_data, $where);
                if ($data_updated) {
                    $this->session->set_flashdata('success', 'Approved From Lab Manager');
                    $data = array(
                        'status' => 1
                    );
                } else {
                    $this->session->set_flashdata('error', 'Error in Approval From Lab Manager');
                    $data = array(
                        'status' => 0
                    );
                }
            } else {
                $this->session->set_flashdata('error', 'Data Not Found');
                $data = array(
                    'status' => 0
                );
            }
        }
        echo json_encode($data);
    }

    // added by millan on 15-03-2021
    public function lm_rejection()
    {
        $this->form_validation->set_error_delimiters('<span class = "text-dangeer">', '</span>');
        $this->form_validation->set_rules('lab_manager_reason', 'Reason', 'required|trim');
        if ($this->form_validation->run() == false) {
            $data = array(
                'error' => $this->form_validation->error_array(),
                'status' => 0
            );
        } else {
            $checkUser = $this->session->userdata();
            $data_get = $this->input->post();
            $update_data = array();
            $update_data['item_id'] = $data_get['item_id'];
            $update_data['lab_manager_reason'] = $data_get['lab_manager_reason'];
            $update_data['updated_by'] = $checkUser['user_data']->uidnr_admin;
            $update_data['updated_on'] = date('y-m-d h:i:s');
            $update_data['lab_manager_status'] = 'Rejected';
            $where['item_req_id'] = $data_get['item_req_id'];
            if (!empty($data_get)) {
                $data_updated = $this->Low_item_notification_model->update_data('item_requirement', $update_data, $where);
                if ($data_updated) {
                    $this->session->set_flashdata('success', 'Rejected From Lab Manager');
                    $data = array(
                        'status' => 1
                    );
                } else {
                    $this->session->set_flashdata('error', 'Error in Approval From Lab Manager');
                    $data = array(
                        'status' => 0
                    );
                }
            } else {
                $this->session->set_flashdata('error', 'Data Not Found');
                $data = array(
                    'status' => 0
                );
            }
        }
        echo json_encode($data);
    }

    // added by millan on 15-03-2021
    public function approve_on_mail()
    {
        $checkUser = $this->session->userdata();
        $get_id = $this->input->post();
        $item_req_id = $get_id['item_req_id'];
        $sender =  $checkUser['user_data']->admin_email;
        $subject = 'LOW ITEM REMINDER MAIL';
        if ($item_req_id || !empty($item_req_id)) {
            $fetch_data = $this->Low_item_notification_model->fetch_for_mail($item_req_id);
            if ($fetch_data && !empty($fetch_data)) {
                // $receiver = $fetch_data->admin_email;
                $receiver = 'developer.cps06@basilrl.com';
                $cc = 'developer.cps04@basilrl.com';
                $item_name = $fetch_data->item_name;
                $category = $fetch_data->category_name;
                $minimum = $fetch_data->min_quantity_required;
                $current = $fetch_data->current_requirement;
                $raiser = $fetch_data->created_by;
                $reason = $fetch_data->requirement_reason;
                $approver_id = $fetch_data->uidnr_admin;
                $message = 'This is a reminder mail for low stock of ' . $item_name . ' which comes under '
                    . $category . ' category. Minimum Quantity Required for ' . $item_name . ' is ' . $minimum . ' and the current demand needed is '
                    . $current . ' The Demand Raised By ' . $raiser . ' for ' . $reason . '<br> <br> 
                <a href="' . base_url('Low_item_notification/approval_rejection?item_req_id=' . base64_encode($item_req_id) . '&lab_manager_status=' . base64_encode('Accepted')) . '&approver_id=' . base64_encode($approver_id) . ' " style="border: 1px solid lightgreen;"> 
                <img src="' . base_url('assets/images/accept.svg') . '" title="Accept" width="30px"> </a> 
                <a href="' . base_url('Low_item_notification/approval_rejection?item_req_id=' . base64_encode($item_req_id) . '&lab_manager_status=' . base64_encode('Rejected')) . '&approver_id=' . base64_encode($approver_id) . ' " style="border: 1px solid red; margin-left:30px;"> 
                <img src="' . base_url('assets/images/cancel.svg') . '" title="Cancel" width="30px"> </a>  ';
                if ($receiver && !empty($receiver)) {
                    send_mail_function($receiver, $sender, $cc, $message, $subject);
                    $this->session->set_flashdata('success', 'Mail Sent Successfully to Lab Manager');
                    $data = array(
                        'status' => 1
                    );
                } else {
                    $this->session->set_flashdata('error', 'Error in Sending Mail');
                    $data = array(
                        'status' => 0
                    );
                }
            } else {
                $this->session->set_flashdata('error', 'Data Not Found');
                $data = array(
                    'status' => 0
                );
            }
        } else {
            $this->session->set_flashdata('error', 'ID Not Found');
            $data = array('status' => 0);
        }
        echo json_encode($data);
    }

    // added by millan on 16-03-2021
    public function approval_rejection()
    {
        $get_data = $this->input->get();
        $item_req_id = base64_decode($get_data['item_req_id']);
        $status = base64_decode($get_data['lab_manager_status']);
        $approved_by = base64_decode($get_data['approver_id']);
        if (($item_req_id && !empty($item_req_id)) && !empty($status) && ($approved_by) > 0 && !empty($approved_by)) {
            $get_status = $this->Low_item_notification_model->lm_status($item_req_id, $status, $approved_by);
            $success = '<h1 class="text-center text-success">This Request is ' . strtoupper($status) . ' </h1>';
            if ($get_status) {
                echo $success;
            } else {
                echo '<h1> Something Went Wrong. Please Contact admin </h1>';
            }
        } else {
            echo '<h1> Data Not Found </h1>';
        }
    }

    // added by millan on 19/03/2021
    public function approve_pathlmgm()
    {
        $checkUser = $this->session->userdata();
        $data_get = $this->input->post();
        $str_data = array();
        $str_data['lmgm_approved_by'] = 45;
        $str_data['lmgm_approved_on'] = date('y-m-d h:i:s');
        $where['item_req_id'] = $data_get['item_req_id'];
        $item_req_id = $data_get['item_req_id'];
        if (!empty($data_get)) {
            $appr_lmgm = $this->Low_item_notification_model->update_data('item_requirement', $str_data, $where);
            if ($appr_lmgm) {
                $data['result'] = $this->Low_item_notification_model->fetch_data_for_report($item_req_id);
                $data['lbmn_sign'] = $this->getS3Url($data['result']->lm_sign);
                $data['gm_sign'] = $this->Low_item_notification_model->get_row('sign_path', 'admin_signature', ['admin_id' => 45]);
                $data['gm_signature'] =  $this->getS3Url($data['gm_sign']->sign_path);
                $data['gm_approver'] = $this->Low_item_notification_model->get_row('CONCAT(admin_fname, " ",admin_lname) gm_admin', 'admin_profile', ['uidnr_admin' => 45]);
                $data['gm_desg'] = $this->Low_item_notification_model->get_designation_gm();
                $report_data = $this->generate_pdf('Low_item_notification/item_requirement_report', $data, 'aws_save');
                $fileName = sanitizeFileName($data['result']->item_name . '-' . $data['result']->category_name . '-' . $data['result']->lmgm_approved_on) . '.pdf';
                if ($report_data) {
                    $aws_data = $this->upload_data_aws($report_data, date('Y-m'), $fileName);
                    if ($aws_data) {
                        $stat_upd['aws_path'] = $aws_data['aws_path'];
                        $stat_upd['file_name'] = $aws_data['file_name'];
                        $stat_upd['aws_folder'] = $aws_data['aws_folder'];
                        $stat_upd['aws_uploaded_by'] = $checkUser['user_data']->uidnr_admin;
                        $stat_upd['aws_uploaded_on'] = date('y-m-d h:i:s');
                        $this->Low_item_notification_model->update_data('item_requirement', $stat_upd, $where);
                        $this->session->set_flashdata('success', 'REPORT Saved Successfully on AWS');
                        $data = array(
                            'status' => 1
                        );
                    } else {
                        $this->session->set_flashdata('error', 'Report Data on AWS Not Saved');
                        $data = array(
                            'status' => 0
                        );
                    }
                } else {
                    $this->session->set_flashdata('error', 'Data In PDF Not Saved Successfully');
                    $data = array(
                        'status' => 0
                    );
                }
            } else {
                $this->session->set_flashdata('error', 'Not Approved From LM & GM');
                $data = array(
                    'status' => 0
                );
            }
        } else {
            $this->session->set_flashdata('error', 'Data Not Found');
            $data = array(
                'status' => 0
            );
        }
        echo json_encode($data);
    }

    // added by millan on 23/03/2021
    public function fetch_vend_details()
    {
        $data_get = $this->input->post();
        $item_req_id = base64_decode($data_get['item_req_id']);
        if ($item_req_id) {
            $data['result'] = $this->Low_item_notification_model->fetch_vendor($item_req_id);
        } else {
            $this->session->set_flashdata('error', 'Data Not Found');
            $data = array(
                'status' => 0
            );
        }
        echo json_encode($data);
    }

    public function vendor_mail(){
        set_time_limit(0);
        $checkUser = $this->session->userdata();
        $compose = $this->input->post();
        $item_req_id = base64_decode($compose['item_req_id']);
        if (!empty($item_req_id) && $item_req_id) {
            $receiver = $checkUser['user_data']->admin_email;
            $to_users = $compose['to_email'];
            $cc_user = $compose['cc_email'];
            $subject = $compose['subject_email'];
            $message_content = html_entity_decode($compose['message_body']);
            $user_mail = explode(',', $to_users);
            $find_attachment = $this->Low_item_notification_model->fetch_attachment_path($item_req_id);
            foreach ($user_mail as $to_mail) {
                $mail = send_mail_function($to_mail, $receiver, $cc_user, $message_content, $subject, NULL, (($find_attachment->aws_path) ? $find_attachment->aws_path : NULL) );
                if ($mail) {
                    $data_add['item_req_id'] = $item_req_id;
                    $data_add['to_user'] = $to_mail;
                    $data_add['cc_user'] = $cc_user;
                    $data_add['attachment_path'] = ( ($find_attachment->aws_path) ? $find_attachment->aws_path : NULL );
                    $data_add['mail_by'] = $checkUser['user_data']->uidnr_admin;
                    $data_add['mail_time'] = date("Y-m-d h:i:s");
                    $this->Low_item_notification_model->insert_data('vendor_mail_log', $data_add);
                }
                $data_insert[] = $mail; 
            }

            if ($data_insert && count($data_insert) == array_sum($data_insert)) {   
                $data_updated = $this->Low_item_notification_model->update_data('item_requirement', array('vendor_mail_status' => 1), array('item_req_id' => $item_req_id));
                if ($data_updated) {
                    $this->session->set_flashdata('success', 'Vendor Mail Status Updated Successfully');
                    $data = array('status' => 1);
                } else {
                    $this->session->set_flashdata('error', 'Error in Updating Vendor Mail Status');
                    $data = array('status' => 0);
                }
            } else {
                $this->session->set_flashdata('error', 'Error in Updating Log Details');
                $data = array('status' => 0);
            }
        } else {
            $this->session->set_flashdata('error', 'Item Id Not Found');
            $data = array('status' => 0);
        }
        echo json_encode($data);
    }
}
