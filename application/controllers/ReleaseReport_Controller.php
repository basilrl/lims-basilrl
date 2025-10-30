<?php
defined('BASEPATH') or exit('No direct access allowed');

class ReleaseReport_Controller extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->check_session();
        $this->load->model('ReleaseReport_Model','rrm');
    }

    public function get_release_to_client_data()
    {
        $data = $this->input->post();
        $get_data = $this->rrm->get_release_to_client_data($data); // updated by millan on 01-07-2021
        $html = '<table width="100%" border="0" cellspacing="5" cellpadding="5" style="border-collapse:collapse; font-family:Arial, Helvetica, sans-serif;font-size:12px;">';
        $html .= '<tr><td colspan="2" style="background-color:#336699"><img src="'.base_url().'public/img/logo/geo-logo.png" height="53"/></td></tr>';
        $html .= '<tr>  <td colspan="2"><b>  Dear Sir/Madam,</b></td></tr>';

        $html .= '<tr>  <td colspan="2"> This is an auto-generated notification that your Report has been Released.</td></tr>';

        $html .= '<tr>  <td colspan="2">Please find the attached report below:</td></tr>';
        $html .= '<tr><td class="link">You Can also download the report from <a href="'.$get_data->report_file.'" target="_blank">here</a></td></tr>';
        $html .= '<tr><td colspan="2"> Thanks & Regards</td></tr>';
        
        
        $html .= '<tr><td colspan="2">GEO-CHEM</td></tr>';
        $html .= '<tr><td align="left" style="background-color:#D5E2F2">Geo Chem Consumer Products Services</td><td align="right" style="background-color:#D5E2F2">GLIMS - Online Lab Information System</td></tr>';
        $html .= '</table>';
        $get_data->message = $html;
        echo json_encode($get_data);
    }

    public function Release_to_client()
    {
        $data = $this->input->post();
        if( (!empty($data['type_report']) && $data['type_report'] =="Manual Report") && $data['mail'] == 1 ){
            $file = $this->rrm->get_row('gr.manual_report_file', 'generated_reports gr', ['gr.report_id' => $data['report_id']]);
            $send_mail = send_mail_while_Release_to_Client($data['to'], NULL, $data['cc'] , $data['bcc'], $data['email_body'], $data['subject'], $file->manual_report_file, $file->manual_report_file);
        }
        // if(!empty($data['report_pass']) && $data['mail'] == 1 ){
            // $get_password_report = $this->approve_report_password($data); // added by millan on 07-07-2021
            $file = $this->rrm->get_row('gr.manual_report_file', 'generated_reports gr', ['gr.report_id' => $data['report_id']]);
            $send_mail = send_mail_while_Release_to_Client($data['to'], NULL, $data['cc'] , $data['bcc'], $data['email_body'], $data['subject'], $file->manual_report_file, $file->manual_report_file); // updated by millan on 7 july 2021
        // }
        
        $done = $this->rrm->update_data('sample_registration sr', ['released_to_client' => '1'], ['sr.sample_reg_id' => $data['sample_reg_id']]);
        if ($done) {
            // UPDATE REPORT RELEASE TIME, ADDED BY SAURABH ON 15-11-2021
            $this->rrm->update_data('generated_reports',['report_release_time' => date('Y-m-d H:i:s')],['sample_reg_id' => $data['sample_reg_id']]);
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
            $this->rrm->save_user_log($logDetails);
            $this->session->set_flashdata('success', 'Report Release to client successfully');
            redirect('Manage_lab/report_listing');
        } else {
            $this->session->set_flashdata('error', 'Error while saving data');
            redirect('Manage_lab/report_listing');
        }
        // }
        // else{
        //     $this->session->set_flashdata('error', 'Error while Sending Email');
        //     redirect($_SERVER['HTTP_REFERER']);

        // }
    }

    /* added by millan on 06-07-2021 */
    // public function approve_report_password($data)
    // {
    //     $checkUser = $this->session->userdata('user_data');
    //     $update['test_component'] =  $this->mlm->pdf_test_component($data);
    //     $update['cps_data'] = $this->mlm->get_cps_data('*', $data['sample_reg_id']);
    //     $update['report_data'] =  $this->mlm->pdf_data_get($data);
    //     $sample_images =  $this->mlm->sample_final_images($data);
    //     $reference_images =  $this->mlm->get_reference_images($data);
    //     $update['reference_sample'] = $reference_images;
    //     $update['image_sample'] = $sample_images;
    //     $application_data = $this->mlm->get_application_care($update);
    //     if ($application_data) {
    //         $appData = array();
    //         if (count($application_data) > 0) {
    //             foreach ($application_data as $k => $app) {
    //                 if (!empty($app->instruction_image)) {

    //                     $appData[$k]['instruction_image'] = $this->getS3Url($app->instruction_image);
    //                     $appData[$k]['instruction_name'] = $app->instruction_name;
    //                 }
    //             }
    //         }
    //         $update['application_data'] = $appData;
    //     } else {
    //         $update['application_data'] = null;
    //     }

    //     $update['record_finding_data'] = $this->mlm->get_record_finding_data($data);

    //     foreach ($update['cps_data'] as $key => $rfd_id) {
    //         $image  = $this->mlm->get_images($rfd_id['record_finding_id']);
    //         if ($image) {
    //             $update['cps_data'][$key]['images'] = $image;
    //         }
    //     }
    //     if ($update['record_finding_data'] != '') {
    //         foreach ($update['record_finding_data'] as $key => $rfd_id) {
    //             if (!empty($update['record_finding_data'][$key]['nabl_headings'])) {
    //                 $update['record_finding_data'][$key]['nabl_headings'] = json_decode(stripslashes($update['record_finding_data'][$key]['nabl_headings']));
    //             }
    //             $image  = $this->mlm->get_images($rfd_id['record_finding_id']);
    //             if ($image) {
    //                 $update['record_finding_data'][$key]['images'] = $image;
    //             }
    //         }
    //     } else {
    //         $update['record_finding_data'] = '';
    //     }
    //     if ($update['record_finding_data'] != '') {
    //         foreach ($update['record_finding_data'] as $key => $rfd_id) {
    //             if (!empty($update['record_finding_data'][$key]['non_nabl_headings'])) {
    //                 $update['record_finding_data'][$key]['non_nabl_headings'] = json_decode(stripslashes($update['record_finding_data'][$key]['non_nabl_headings']));
    //             }
    //             $image  = $this->mlm->get_images($rfd_id['record_finding_id']);
    //             if ($image) {
    //                 $update['record_finding_data'][$key]['images'] = $image;
    //             }
    //         }
    //     } else {
    //         $update['record_finding_data'] = '';
    //     }
    //     $record_finding_id = $update['report_data']->record_finding_id;
    //     $update['images'] = $this->mlm->get_images($record_finding_id);
    //     $sign1 = $this->mlm->get_row('signing_authority', 'generated_reports', ['report_id' => $update['report_data']->report_id]);

    //     $sign2 = $this->mlm->get_row('sign_authority_new', 'generated_reports', ['report_id' => $update['report_data']->report_id]);
    //     if ($sign1) {
    //         $signature1 = $this->mlm->get_row('sign_path', 'admin_signature', ['admin_id' => $sign1->signing_authority]);
    //     }
    //     if ($sign2) {
    //         $signature2 = $this->mlm->get_row('sign_path', 'admin_signature', ['admin_id' => $sign2->sign_authority_new]);
    //     }

    //     if ($signature1) {
    //         $update['signature1'] = $this->url_sign_get($signature1->sign_path);
    //     }
    //     if ($signature2) {
    //         $update['signature2'] = $this->url_sign_get($signature2->sign_path);
    //     }
    //     $update['sign_data1'] = $this->mlm->getsignvalues($sign1->signing_authority);
    //     $update['sign_data2'] = $this->mlm->getsignvalues($sign2->sign_authority_new);
    //     $update['test_data'] =  $this->mlm->get_test_result($data);

    //     $this->load->library('Ciqrcode');
    //     $params['data'] =  base_url('Render/download_pdf?report_id=' . base64_encode($data['report_id']) . '&sample_rg=' . base64_encode($update['report_data']->sample_reg_id));
    //     $params['level'] = 'H';
    //     $params['size'] = 1;
    //     $gc_no = $update['report_data']->gc_no;
    //     $params['savename'] = QRCODE . (($gc_no) ? $gc_no : rand(0000, 9999)) . '.png';
    //     $cer_po = $this->ciqrcode->generate($params); // genrate image
    //     // print_r($params);die;
    //     $update['qrcode'] = $params['savename'];
    //     // echo '<pre>';print_r($data); die;
    //     if ($update['report_data']->branch_name == 'Gurgaon') {
    //         if ($update['report_data']->report_format == 6 || $update['report_data']->division_name == 'Hradline') {

    //             $pdf_body =  $this->generate_pdf_pass('manage_lab/hggnreport', $update, 'aws_save', trim($data['report_pass']));
    //         } elseif ($update['report_data']->report_format == 8 || $update['report_data']->division_name == 'Footwear') {

    //             $pdf_body =  $this->generate_pdf_pass('manage_lab/ggn_footwear_report', $update, 'aws_save', trim($data['report_pass']));
    //         } else {
    //             $pdf_body = $this->generate_pdf_pass('manage_lab/ggnreport', $update, 'aws_save' , trim($data['report_pass']));
    //         }
    //     } elseif ($update['report_data']->branch_id == 2) {
    //         if ($update['report_data']->report_format == 4) {
    //             $pdf_body =  $this->generate_pdf_pass('manage_lab/landmark_uaereport', $update, 'aws_save' , trim($data['report_pass']));
    //         } elseif ($update['report_data']->report_format == 3 || $update['report_data']->division_name == 'Textiles') {

    //             $pdf_body =   $this->generate_pdf_pass('manage_lab/taxtile_uaereport', $update, 'aws_save' , trim($data['report_pass']));
    //         } elseif ($update['report_data']->report_format == 2 || $update['report_data']->division_name == 'Analytical' ) {

    //             $pdf_body = $this->generate_pdf_pass('manage_lab/analytical_uaereport', $update, 'aws_save' , trim($data['report_pass']));
    //         } elseif ($update['report_data']->report_format == 1 || $update['report_data']->division_name == 'Toys') {
    //             $pdf_body = $this->generate_pdf_pass('manage_lab/toys_uaereport', $update, 'aws_save' , trim($data['report_pass']));
    //         } elseif ($update['report_data']->report_format == 5 || $update['report_data']->division_name == 'Footwear') {
    //             $pdf_body = $this->generate_pdf_pass('manage_lab/lf_uaereport', $update, 'aws_save' , trim($data['report_pass']));
    //         } else {
    //             $pdf_body =  $this->generate_pdf_pass('manage_lab/taxtile_uaereport', $update, 'aws_save' , trim($data['report_pass']));
    //         }
    //     } elseif ($update['report_data']->branch_name == 'Dhaka') {

    //         $pdf_body =  $this->generate_pdf_pass('manage_lab/bdreport', $update, 'aws_save', trim($data['report_pass']));
    //     }

    //     if ($pdf_body) {
    //         $upload_path = $this->report_upload_aws($pdf_body, $gc_no . '.pdf');
    //         if ($upload_path) {
    //             $save_aws =  $this->mlm->update_data('generated_reports', array('pass_pdf_path' => $upload_path['aws_path'], 'pdf_password' => trim($data['report_pass'])), array('report_id' => $data['report_id']));

    //             // echo $this->db->last_query();die;

    //             if ($save_aws) {
    //                 unlink($params['savename']);
    //                 $status = $this->mlm->update_data('sample_registration', array('status' => 'Report Approved'), array('sample_reg_id' => $data['sample_reg_id']));

    //                 if ($status) {
    //                     $old_status_query = $this->db->select('status')->from('sample_registration')->where('sample_reg_id', $data['sample_reg_id'])->get();
    //                     $old_status = $old_status_query->row()->status;
    //                     $logDetails = array(
    //                         'module' => 'Samples',
    //                         'old_status' => $old_status,
    //                         'new_status' => 'Report Approved',
    //                         'sample_reg_id' => $data['sample_reg_id'],
    //                         'sample_assigned_lab_id' => /* $lab_id, */ '',
    //                         'action_message' => 'Report Approved',
    //                         'sample_job_id' => '',
    //                         'report_id' => '',
    //                         'report_status' => '',
    //                         'test_ids' => '',
    //                         'test_names' => '',
    //                         'test_newstatus' => '',
    //                         'test_oldStatus' => '',
    //                         'test_assigned_to' => '',
    //                         'source_module'    => 'Manage_lab',
    //                         'operation'        => 'approve_report_password', 
    //                         'uidnr_admin'    => $this->session->userdata('user_data')->uidnr_admin,
    //                         'log_activity_on' => date("Y-m-d H:i:s")
    //                     );
    //                     $this->rrm->save_user_log($logDetails);
    //                     $this->session->set_flashdata('success', 'Report Approved');
    //                 } else {
    //                     $this->session->set_flashdata('error', 'Report Approved');
    //                     redirect($_SERVER['HTTP_REFERER']);
    //                 }
    //             } else {
    //                 $this->session->set_flashdata('error', 'Report Not approve');
    //                 redirect($_SERVER['HTTP_REFERER']);
    //             }
    //         } else {
    //             $this->session->set_flashdata('error', 'AWS Path Not Found');
    //             redirect($_SERVER['HTTP_REFERER']);
    //         }
    //     } else {
    //         $this->session->set_flashdata('error', 'Pdf not generated');
    //         redirect($_SERVER['HTTP_REFERER']);
    //     }
    // }
    /* added by millan on 06-07-2021 */

     /* added by millan on 01-07-2021 */
     public function generate_pdf_pass($view_file_name, $data, $type = 'view', $user_password, $file_name = 'document.pdf') {
        set_time_limit(0);
        // ini_set('max_execution_time', 0);
        $this->load->library('M_pdf');
        $this->m_pdf->pdf->charset_in = 'UTF-8';
        $this->m_pdf->pdf->setAutoBottomMargin = 'stretch';
        $this->m_pdf->pdf->setAutoTopMargin = 'stretch';
        $this->m_pdf->pdf->lang = 'en';
        $this->m_pdf->pdf->autoLangToFont = true;
        $this->m_pdf->pdf->showImageErrors = true;
        $html = $this->load->view($view_file_name, $data, true);
        
        $this->m_pdf->pdf->WriteHTML($html);
        $this->m_pdf->pdf->SetProtection(array('print') , $user_password); // added by millan on 01-07-2021
        $this->m_pdf->pdf->curlAllowUnsafeSslRequests = true;
        if ($type == 'aws_save') {
            $pdf_body = $this->m_pdf->pdf->Output($file_name, 'S');
            if ($pdf_body) {
                return $pdf_body;
            } else {
                return false;
            }
        } else {
            $this->m_pdf->pdf->Output($file_name, 'I');
            die;
        }
    }
    /* added by millan on 01-07-2021 */

    public function url_sign_get($signature_path_aws)
    {
        return str_replace('s3://', 'https://s3.ap-south-1.amazonaws.com/', $signature_path_aws);
    }
}

?>