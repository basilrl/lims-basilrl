<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Approve_Revise_Report extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('Approve_Revise_Report_Model', 'ARR');
        $this->load->helper('url');
    }

    public function index()
    {
        $this->load_view('approve_revise_report/index', null);
    }

    function fetch_records()
    {
        $fetch_data = $this->ARR->fetch_records($this->input->post());
        $data = array();
        $sl = $this->input->post('start') + 1;
        if ($fetch_data) {
            foreach ($fetch_data as $key => $row) {

                $btnApprove = $btnReject = $btnLog = '';

                if (exist_val("Approve_Revise_Report/report_approve", $this->session->userdata("permission"))) {
                    $btnApprove = '<button type="button" report_num="' . $row->report_num . '" report_id="' . $row->report_id . '" action="Approve" class="btn btn-primary btn-sm report_approve_reject">Approve</button>';
                }
                if (exist_val("Approve_Revise_Report/report_reject", $this->session->userdata("permission"))) {
                    $btnReject = '<button type="button" report_num="' . $row->report_num . '" report_id="' . $row->report_id . '" action="Reject" class="btn btn-danger btn-sm report_approve_reject m-1">Reject</button>';
                }
                if (exist_val("Approve_Revise_Report/report_log", $this->session->userdata("permission"))) {
                    $btnLog = '<button type="button" sample_reg_id="' . $row->sample_reg_id . '" class="btn btn-info btn-sm report_log m-1" title="Logs"><i class="fa fa-bolt"></i></button>';
                }
                $badge = ($row->gr_revise_type == 'Additional Test') ? 'badge-primary' : 'badge-success';
                $sub_array = array();
                $sub_array[] = $sl;
                $sub_array[] = $row->report_num;
                $sub_array[] = '<span class="badge badge-pill ' . $badge . '">' . $row->gr_revise_type . '</span>';
                $sub_array[] = $row->user_name;
                $sub_array[] = $row->gr_revise_reason;
                $sub_array[] =  $btnApprove . $btnReject . $btnLog;
                $data[] = $sub_array;
                $sl++;
            }
        }

        echo json_encode([
            "draw"              => intval($this->input->post('draw')),
            "recordsTotal"      => $this->ARR->get_all_data('generated_reports'),
            "recordsFiltered"   => $this->ARR->get_filtered_data($this->input->post()),
            "data"              => $data
        ]);
    }

    public function report_log()
    {
        echo json_encode($this->ARR->report_log($this->input->post('record_id')));
    }

    public function report_approve_reject()
    {
        $action = $this->input->post('action');
        $getData = $this->ARR->get_row('sample_reg_id, gr_revise_type, gr_revise_requester_id, report_num', 'generated_reports', ['report_id' => $this->input->post('report_id')]);

        if (!empty($getData->sample_reg_id) && !empty($getData->gr_revise_type)) {

            $sampleData = $this->ARR->get_row('gc_no', 'sample_registration', ['sample_reg_id' => $getData->sample_reg_id]);
            $getGC = (isset($sampleData->gc_no) && !empty($sampleData->gc_no)) ? $sampleData->gc_no : '';

            $this->db->select('admin_users.admin_email, CONCAT(admin_profile.admin_fname, ,admin_profile.admin_lname) as username');
            $this->db->from('admin_users');
            $this->db->join('admin_profile', 'admin_profile.uidnr_admin = admin_users.uidnr_admin', 'left');
            $this->db->where('admin_users.uidnr_admin', $getData->gr_revise_requester_id);
            $getUserData = $this->db->get();

            if ($getUserData->num_rows() > 0) {
                $getUser = $getUserData->row();
                $username = '<p>Dear Mr. ' . $getUser->username . '</p>';
                $adminEmail = $getUser->admin_email;
            } else {
                $username = '<p>Dear Sir/Madam,</p>';
                $adminEmail = '';
            }

            if ($action == 'Reject') {
                $data = array(
                    'gr_revise_approver_reason' => $this->input->post('reason'),
                    'gr_revise_approver_id'     => $this->session->userdata('user_data')->uidnr_admin,
                    'gr_revise_flag'            => 0
                );
                if ($getData->gr_revise_type == 'Revise Report') {
                    $data['revise_report'] = '0';
                } else {
                    $data['additional_report_flag'] = 0;
                }
                $result = $this->ARR->update_data('generated_reports', $data, ['report_id' => $this->input->post('report_id')]);
                if ($result) {
                    $log_details = array(
                        'source_module'     => 'Manage_lab',
                        'record_id'         => $getData->sample_reg_id,
                        'created_on'        => date("Y-m-d h:i:s"),
                        'created_by'        => $this->session->userdata('user_data')->uidnr_admin,
                        'action_taken'      => 'report_approve_reject',
                        'text'              => 'Rejected!! with reason: ' . $this->input->post('reason')
                    );
                    $this->ARR->insert_data('user_log_history', $log_details);

                    $done = $this->ARR->update_data('sample_registration', ['status' => 'Report Approved'], ['sample_reg_id' => $getData->sample_reg_id]);

                    if ($done && !empty($adminEmail)) {
                        $sub = 'REJECTED REQUEST REVISION FOR SAMPLE NUMBER -' . $getGC . ' - REQUEST FOR REVISION MORE THAN 5 TIMES';
                        $msg = $username;
                        $msg .= '<p>Revision has been declined, please consult with your Lab Manager.</p>';
                        $msg .= '<p><b>Reason for rejection:</b> ' . $this->input->post('reason') . '</p>';
                        $msg .= '<br/><h4>Regards,</h4><h4>Basil Team</h4>';
                        send_mail_while_Release_to_Client($adminEmail, FROM, CC, $bcc = NULL, $msg, $sub, $attachment_file = NULL, $attachment_path = NULL, $report = false);
                    }
                    $response = array(
                        'message'   => 'Request has been Rejected.',
                        'code'      => 1
                    );
                } else {
                    $response = array(
                        'message'   => 'Something went wrong.',
                        'code'      => 0
                    );
                }
            } else {
                if ($getData->gr_revise_type == 'Revise Report') {

                    $data = $this->ARR->regenerate_sample($getData->sample_reg_id, $this->input->post('report_id'));
                    if ($data) {
                        $old_status_query = $this->db->select('status')
                            ->from('sample_registration')
                            ->where('sample_reg_id', $getData->sample_reg_id)
                            ->get();

                        $old_status = $old_status_query->row()->status;
                        $logDetails = array(
                            'module'                    => 'Samples',
                            'old_status'                => $old_status,
                            'new_status'                => 'Retest',
                            'sample_reg_id'             => $getData->sample_reg_id,
                            'sample_assigned_lab_id'    => '',
                            'action_message'            => 'Report Regenerate',
                            'sample_job_id'             => '',
                            'report_id'                 => '',
                            'report_status'             => '',
                            'test_ids'                  => '',
                            'test_names'                => '',
                            'test_newstatus'            => '',
                            'test_oldStatus'            => '',
                            'test_assigned_to'          => '',
                            'source_module'             => 'Manage_lab',
                            'operation'                 => 'regenerate_sample',
                            'uidnr_admin'               => $this->session->userdata('user_data')->uidnr_admin,
                            'log_activity_on'           => date("Y-m-d H:i:s")
                        );
                        $this->ARR->save_user_log($logDetails);

                        $log_details = array(
                            'source_module'     => 'Manage_lab',
                            'record_id'         => $getData->sample_reg_id,
                            'created_on'        => date("Y-m-d h:i:s"),
                            'created_by'        => $this->session->userdata('user_data')->uidnr_admin,
                            'action_taken'      => 'report_approve_reject',
                            'text'              => 'Revise Report Approved!! with reason: ' . $this->input->post('reason')
                        );
                        $this->ARR->insert_data('user_log_history', $log_details);

                        $done = $this->ARR->update_data('generated_reports', ['gr_revise_flag' => 0], ['report_id' => $this->input->post('report_id')]);

                        if ($done && !empty($adminEmail)) {
                            $sub = 'ACCEPTED REQUEST REVISION FOR SAMPLE NUMBER -' . $getGC . ' - REQUEST FOR REVISION MORE THAN 5 TIMES';
                            $msg = $username;
                            $msg .= '<p>Revision has been accepted, please process further.</p>';
                            $msg .= '<p><b>Reason for accept:</b> ' . $this->input->post('reason') . '</p>';
                            $msg .= '<br/><h4>Regards,</h4><h4>Basil Team</h4>';
                            send_mail_while_Release_to_Client($adminEmail, FROM, CC, $bcc = NULL, $msg, $sub, $attachment_file = NULL, $attachment_path = NULL, $report = false);
                        }
                        $response = array(
                            'message'   => 'Report Regenerate Successfully',
                            'code'      => 1
                        );
                    } else {
                        $response = array(
                            'message'   => 'Error While Regenerating data.',
                            'code'      => 0
                        );
                    }
                } else {
                    $data = $this->ARR->additional_test($getData->sample_reg_id, $this->input->post('report_id'));
                    if ($data) {
                        $old_status_query = $this->db->select('status')
                            ->from('sample_registration')
                            ->where('sample_reg_id', $getData->sample_reg_id)
                            ->get();

                        $old_status = $old_status_query->row()->status;
                        $logDetails = array(
                            'module'                    => 'Samples',
                            'old_status'                => $old_status,
                            'new_status'                => 'Sample send for Evaluation',
                            'sample_reg_id'             => $getData->sample_reg_id,
                            'sample_assigned_lab_id'    => '',
                            'action_message'            => 'Report Regenerate',
                            'sample_job_id'             => '',
                            'report_id'                 => '',
                            'report_status'             => '',
                            'test_ids'                  => '',
                            'test_names'                => '',
                            'test_newstatus'            => '',
                            'test_oldStatus'            => '',
                            'test_assigned_to'          => '',
                            'source_module'             => 'Manage_lab',
                            'operation'                 => 'additional_test',
                            'uidnr_admin'               => $this->session->userdata('user_data')->uidnr_admin,
                            'log_activity_on'           => date("Y-m-d H:i:s")
                        );
                        $this->ARR->save_user_log($logDetails);

                        $log_details = array(
                            'source_module'     => 'Manage_lab',
                            'record_id'         => $getData->sample_reg_id,
                            'created_on'        => date("Y-m-d h:i:s"),
                            'created_by'        => $this->session->userdata('user_data')->uidnr_admin,
                            'action_taken'      => 'report_approve_reject',
                            'text'              => 'Additional Test Approved!! with reason: ' . $this->input->post('reason')
                        );
                        $this->ARR->insert_data('user_log_history', $log_details);

                        $done = $this->ARR->update_data('generated_reports', ['gr_revise_flag' => 0], ['report_id' => $this->input->post('report_id')]);

                        if ($done && !empty($adminEmail)) {
                            $sub = 'ACCEPTED REQUEST REVISION FOR SAMPLE NUMBER -' . $getGC . ' - REQUEST FOR REVISION MORE THAN 5 TIMES';
                            $msg = $username;
                            $msg .= '<p>Revision has been accepted, please process further.</p>';
                            $msg .= '<p><b>Reason for accept:</b> ' . $this->input->post('reason') . '</p>';
                            $msg .= '<br/><h4>Regards,</h4><h4>Basil Team</h4>';
                            send_mail_while_Release_to_Client($adminEmail, FROM, CC, $bcc = NULL, $msg, $sub, $attachment_file = NULL, $attachment_path = NULL, $report = false);
                        }
                        $response = array(
                            'message'   => 'Sample send for Evaluation.',
                            'code'      => 1
                        );
                    } else {
                        $response = array(
                            'message'   => 'Error While sending data.',
                            'code'      => 0
                        );
                    }
                }
            }
        } else {
            $response = array(
                'message'   => 'Something went wrong.',
                'code'      => 0
            );
        }
        echo json_encode($response);
    }
}
