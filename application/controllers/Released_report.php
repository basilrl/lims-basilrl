<?php
class Released_report extends MY_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('Released_report_model', 'rrm');
        $this->check_session();
        $checkUser = $this->session->userdata('user_data');
        $this->user = $checkUser->uidnr_admin;
        $this->load->helper('common');

    }


    public function released_report_listing($applicant='NULL', $product='NULL', $search_url='NULL', $start_date='NULL', $end_date='NULL')
    {
        //$this->output->enable_profiler(true);
        $where = array();
        $search = NULL;
        // echo $applicant . " " . $product . " " . $search . " " . $start_date . " " . $end_date . " " . $stauts;
        $base_url = "Released_report/released_report_listing";
        $base_url .= '/' . (($applicant != 'NULL') ? $applicant : 'NULL');
        $base_url .= '/' . (($product != 'NULL') ? $product : 'NULL');
        $base_url .= '/' . (($search_url != 'NULL') ? ($search_url) : 'NULL');
        $base_url .= '/' . (($start_date != 'NULL') ? $start_date : 'NULL');
        $base_url .= '/' . (($end_date != 'NULL') ? $end_date : 'NULL');
        // $base_url .= '/' . (($stauts != 'NULL') ? ($stauts) : 'NULL');
        $data['applicant_id'] = ($applicant != 'NULL') ? $applicant : 'NULL';
        $data['product_id'] = ($product != 'NULL') ? $product : 'NULL';
        $data['search_url'] = ($search_url != 'NULL') ? base64_decode($search_url) : 'NULL';
        $data['start_date'] = ($start_date != 'NULL') ? $start_date : 'NULL';
        $data['end_date'] = ($end_date != 'NULL') ? $end_date : 'NULL';
        // $data['stauts'] = ($stauts != 'NULL') ? base64_decode($stauts) : 'NULL';
        if ($applicant!='NULL') {
          $customer = $this->rrm->get_row('customer_name','cust_customers',['customer_id'=>$applicant]);
          if ($customer) {
            $data['applicant_name'] = $customer->customer_name;
          } else {
            $data['applicant_name'] = 'NULL';
          }
        }else{
            $data['applicant_name'] = 'NULL';
        }
        if ($product!='NULL') {
            $customer = $this->rrm->get_row('sample_type_name','mst_sample_types',['sample_type_id'=>$product]);
            if ($customer) {
              $data['product_name'] = $customer->sample_type_name;
            } else {
              $data['product_name'] = 'NULL';
            }
          }else{
              $data['product_name'] = 'NULL';
          }
        if ($applicant!= 'NULL') {
             $where['tr.trf_applicant']= $applicant;
        }
        if ($product!= 'NULL') {
            $where['sr.sample_registration_sample_type_id']=$product;
        }
        if ($search_url!= 'NULL') {
            $search = base64_decode($search_url);
        }
        if ($start_date!= 'NULL') {
            $where['sr.received_date >=']= ($start_date);
        }
        if ($end_date!= 'NULL') {
            $where['sr.received_date <='] = ($end_date);
        }
        // if ($stauts!= 'NULL') {
        //     $where['sr.status'] = base64_decode($stauts);
        // }
        $total_row = $this->rrm->get_report_list(NULL, NULL,$where,$search, '1');

        $page = ($this->uri->segment(8)) ? $this->uri->segment(8) : 0;
        $config = $this->pagination($base_url, $total_row, 10, 8);

        $data["links"] = $config["links"];
        $data['report_listing'] =  $this->rrm->get_report_list($config["per_page"], $page,$where,$search);

         if($total_row > 0){
            $start = (int)$page + 1;
        } else {
            $start = 0;
        }
        $end = (($data['report_listing']) ? count($data['report_listing']) : 0) + (($page) ? $page : 0);
        $data['result_count'] = "Showing " . $start . " - " . $end . " of " . $total_row . " Results";
        // $data['sign_auth'] =  $this->rrm->get_report_approver();
        // $data['sign_auth'] =  $this->mlm->get_report_approver();

        $this->load_view('released_report/released_report_list', $data);
    }

    public function send_report_mail($sample_reg_id,$report_id)
    {
        if ($this->input->post()) {
            $to = $this->input->post('to');
            $cc = $this->input->post('cc');
            $bcc = $this->input->post('bcc');
            $subject = $this->input->post('subject');
            $message = $this->input->post('message');
            $from = FROM;
            // echo '<pre>';
            // print_r($this->input->post());die;
            $file = $this->rrm->get_row('gr.manual_report_file', 'generated_reports gr', ['gr.report_id' =>$report_id]);
            $mail = send_mail_while_Release_to_Client($to, $from, $cc, $bcc, $message, $subject,$file->manual_report_file, $file->manual_report_file);
            // $mail = send_mail_function($to, $from, $cc, $bcc, $message, $subject);
            if ($mail) {
                $report_post = array('report_mail_status' => 1,'status' =>'Report Released To Client');
                $this->rrm->update_data('sample_registration',$report_post,['sample_reg_id' => $sample_reg_id]);
                $this->rrm->update_data('generated_reports',['status' =>'Report Released To Client'],['report_id' => $report_id]);
                $old_status_query = $this->db->select('status')->from('sample_registration')->where('sample_reg_id', $sample_reg_id)->get();
                $old_status = $old_status_query->row()->status;
                $log_details = array(
                    'module'    => 'Lab',
                    'operation' => 'send_report_mail',
                    'source_module' => 'Release Report',
                    'sample_reg_id' => $sample_reg_id,
                    'uidnr_admin'   => $this->admin_id(),
                    'log_activity_on'   => date('Y-m-d H:i:s'),
                    'action_message'    => 'Sample report mail sent',
                    'to_users'          => $to,
                    'cc_users'          => $cc,
                    'bcc_users'         => $bcc,
                    'old_status'        => $old_status,
                    'new_status'        => 'Report Released To Client'
                );
                $this->rrm->save_user_log($log_details);
                echo json_encode(["message" => "Mail sent successfully", "status" => 1]);
            } else {
                echo json_encode(["message" => "Something went wrong!.", "status" => 0]);
            }
            exit;
        }
        $send_email = $this->rrm->send_email($sample_reg_id,$report_id, "release_report");
        $send_email['sample_reg_id'] = $sample_reg_id;
        $send_email['report_id'] = $report_id;
        // echo "<pre>"; print_r($send_email); die;
        $this->load_view('template/compose_report_mail', $send_email);
    }
}
