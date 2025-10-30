<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Manual_report extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->check_session();
        $this->load->model('SampleRegistration', 'sr');
    }

    /* added by millan on 18-Jan-2021 */
    public function upload_manual_report()
    {
        $post = $this->input->post();
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');
        $this->form_validation->set_rules('sample_reg_id', 'UNIQUE ID', 'trim|required');
        $this->form_validation->set_rules('alternate_report_number', 'Alternate Rpoert Number', 'trim|min_length[3]');
        $this->form_validation->set_rules('generated_date', 'Report Date', 'trim|required');
        $this->form_validation->set_rules('mr_result_ready_date', 'Report Ready Date', 'trim|required');
        $this->form_validation->set_rules('manual_report_result', 'Result status', 'trim|required|in_list[1,2,3]');
        $this->form_validation->set_rules('manual_report_file', 'Report Document', 'callback_file_upload_check');
        // $this->form_validation->set_rules('manual_report_worksheet', 'Report Worksheet', 'callback_file_upload_check');
        if ($post['manual_report_result'] == 3) {
            $this->form_validation->set_rules('manual_report_remark', 'Result status Remarks', 'trim|required|min_length[3]');
        }
        if ($this->form_validation->run() == true) {
            $report = $this->sr->get_row('report_id','generated_reports',['sample_reg_id' => $post['sample_reg_id']]);
            $sample_reg_id = $this->sr->get_row('sample_job_id,sample_reg_id','sample_registration',['sample_reg_id' => $post['sample_reg_id']]);
            $update_sample_registration['manual_report_result'] = $post['manual_report_result'];
            unset($post['manual_report_result']);
            //$report_worksheet_upload = $this->multiple_upload_image($_FILES['manual_report_worksheet']);
            if (array_key_exists('manual_report_remark', $post)) {
                $update_sample_registration['manual_report_remark'] = $post['manual_report_remark'];
                unset($post['manual_report_remark']);
            }
            if ($_FILES['manual_report_worksheet'] && !empty($_FILES['manual_report_worksheet']['name'])) {
                $report_worksheet_upload = $this->multiple_upload_image($_FILES['manual_report_worksheet']);
                if ($report_worksheet_upload) {
                    $post['manual_report_worksheet'] = $report_worksheet_upload['aws_path'];
                }
            } else {
                unset($post['manual_report_worksheet']);
            }
            $report_upload = $this->multiple_upload_image($_FILES['manual_report_file']);
            if ($report_upload) {
                $post['manual_report_file'] = $report_upload['aws_path'];
            } else {
                unset($post['manual_report_file']);
            }
            if (array_key_exists('manual_report_file', $post)) {
                $post['status']='Report Generated';
                $update = $this->sr->update_data('generated_reports', $post, ['sample_reg_id' => $post['sample_reg_id']]);
                if ($update) {
                    $update_sample_registration['status']='Report Generated';
                    $update = $this->sr->update_data('sample_registration', $update_sample_registration, ['sample_reg_id' => $post['sample_reg_id']]);
                    if ($update) {
                        $logDetails = array(
                            'module' => 'Samples',
                            'old_status' => '',
                            'new_status' => 'Report Generated',
                            'sample_reg_id' => $post['sample_reg_id'],
                            'sample_assigned_lab_id' => '',
                            'action_message' => 'Manual report file added.',
                            'sample_job_id' => $sample_reg_id->sample_job_id,
                            'report_id' => $report->report_id,
                            'report_status' => 'Report Generated',
                            'test_ids' => '',
                            'test_names' => '',
                            'test_newstatus' => '',
                            'test_oldStatus' => '',
                            'test_assigned_to' => ''
                        );
                        $this->sr->insert_data('sample_reg_activity_log',$logDetails);
                        $this->sr->insert_data('report_log',$logDetails);
                        $this->session->set_flashdata('success', 'SUCCESSFULLY UPLOAD PDF');
                        $msg = array('status' => 1,'msg' => 'SUCCESSFULLY UPLOAD PDF');
                    } else {
                        $msg = array('status' => 0,'msg' => 'SAMPLE REGESITRATION STATUS NOT UPDATED');
                    }
                } else {
                    $msg = array('status' => 0,'msg' => 'REPORT PDF NOT UPLOADED');
                }
            } else {
                $msg = array('status' => 0,'msg' => 'PLEASE TRY AGAIN SOMETHING WRONG WHILE UPLODING PDF');
            }
        } else {
            $msg = array(
                'status' => 0,
                'errors' => $this->form_validation->error_array(),
                'msg' => 'PLEASE VALID ENTER'
            );
        }
        echo json_encode($msg);
    }
    public function file_upload_check()
    {
        if (empty($_FILES['manual_report_file']['name']) && empty($_FILES['manual_report_worksheet']['name'])) {
            $this->form_validation->set_message('file_upload_check', 'REPORT File are Required');
            return FALSE;
        } else {
            return TRUE;
        }
    }
    /* added by millan on 18-Jan-2021 */
    public function get_gcNo()
    {
        $post =$this->input->post();
        echo json_encode($this->sr->get_row('gc_no','sample_registration',['sample_reg_id'=>$post['sample_reg_id']]));
    }


    /* added by saurabh on 30-June-2021 */
    public function upload_partial_report()
    {
        $post = $this->input->post();
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');
        $this->form_validation->set_rules('sample_reg_id', 'UNIQUE ID', 'trim|required');
        $this->form_validation->set_rules('alternate_report_number', 'Alternate Rpoert Number', 'trim|min_length[3]');
        $this->form_validation->set_rules('generated_date', 'Report Date', 'trim|required');
        $this->form_validation->set_rules('mr_result_ready_date', 'Report Ready Date', 'trim|required');
        $this->form_validation->set_rules('manual_report_result', 'Result status', 'trim|required|in_list[1,2,3]');
        $this->form_validation->set_rules('manual_report_file', 'Report Document', 'callback_file_upload_check');
        // $this->form_validation->set_rules('manual_report_worksheet', 'Report Worksheet', 'callback_file_upload_check');
        if ($post['manual_report_result'] == 3) {
            $this->form_validation->set_rules('manual_report_remark', 'Result status Remarks', 'trim|required|min_length[3]');
        }
        if ($this->form_validation->run() == true) {
            $report = $post['report_id'];
            $sample_reg_id = $this->sr->get_row('sample_job_id,sample_reg_id','sample_registration',['sample_reg_id' => $post['sample_reg_id']]);
            $update_sample_registration['manual_report_result'] = $post['manual_report_result'];
            unset($post['manual_report_result']);
            //$report_worksheet_upload = $this->multiple_upload_image($_FILES['manual_report_worksheet']);
            if (array_key_exists('manual_report_remark', $post)) {
                $update_sample_registration['manual_report_remark'] = $post['manual_report_remark'];
                unset($post['manual_report_remark']);
            }
            if ($_FILES['manual_report_worksheet'] && !empty($_FILES['manual_report_worksheet']['name'])) {
                $report_worksheet_upload = $this->multiple_upload_image($_FILES['manual_report_worksheet']);
                if ($report_worksheet_upload) {
                    $post['manual_report_worksheet'] = $report_worksheet_upload['aws_path'];
                }
            } else {
                unset($post['manual_report_worksheet']);
            }
            $report_upload = $this->multiple_upload_image($_FILES['manual_report_file']);
            if ($report_upload) {
                $post['manual_report_file'] = $report_upload['aws_path'];
            } else {
                unset($post['manual_report_file']);
            }
            if (array_key_exists('manual_report_file', $post)) {
                // get revise report type
                $report_type = $this->sr->get_report_type($report)['revise_report_type'];
                if($report_type == '1'){
                    $post['report_status'] = 'Revised Report Uploaded';
                } else {
                    $post['report_status'] = 'Partial Report Uploaded';
                }
                $post['status']='Report Generated';
                $update = $this->sr->update_data('generated_reports', $post, ['sample_reg_id' => $post['sample_reg_id'],'report_id' => $report]);
                if ($update) {
                    $update_sample_registration['status']='Report Generated';
                    $update = $this->sr->update_data('sample_registration', $update_sample_registration, ['sample_reg_id' => $post['sample_reg_id']]);
                    if ($update) {
                        $logDetails = array(
                            'module' => 'Samples',
                            'old_status' => '',
                            'new_status' => 'Report Generated',
                            'sample_reg_id' => $post['sample_reg_id'],
                            'sample_assigned_lab_id' => '',
                            'action_message' => 'Manual report file added.',
                            'sample_job_id' => $sample_reg_id->sample_job_id,
                            'report_id' => $report,
                            'report_status' => 'Report Generated',
                            'test_ids' => '',
                            'test_names' => '',
                            'test_newstatus' => '',
                            'test_oldStatus' => '',
                            'test_assigned_to' => ''
                        );
                        $this->sr->insert_data('sample_reg_activity_log',$logDetails);
                        $this->sr->insert_data('report_log',$logDetails);
                        $this->session->set_flashdata('success', 'SUCCESSFULLY UPLOAD PDF');
                        $msg = array('status' => 1,'msg' => 'SUCCESSFULLY UPLOAD PDF');
                    } else {
                        $msg = array('status' => 0,'msg' => 'SAMPLE REGESITRATION STATUS NOT UPDATED');
                    }
                } else {
                    $msg = array('status' => 0,'msg' => 'REPORT PDF NOT UPLOADED');
                }
            } else {
                $msg = array('status' => 0,'msg' => 'PLEASE TRY AGAIN SOMETHING WRONG WHILE UPLODING PDF');
            }
        } else {
            $msg = array(
                'status' => 0,
                'errors' => $this->form_validation->error_array(),
                'msg' => 'PLEASE VALID ENTER'
            );
        }
        echo json_encode($msg);
    }

}
