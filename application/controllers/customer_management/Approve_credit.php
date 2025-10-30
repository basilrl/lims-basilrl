<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Approve_credit extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('customer_management_model/Customers_model', 'acm');
    }
    public function approve_credit_limit()
    {
        $cid = $this->uri->segment(4);
        $approver_id = $this->uri->segment(6);
        $id = base64_decode($cid);
        // Check approve status
        $query = $this->db->select('credit_status')->where('customer_id', $id)->get('cust_customers');
        $query_result = $query->row_array();
        // print_r($query_result); die;
        if ($query_result['credit_status'] == 1) {
            $response['response'] = ['status' => 1, 'message' => 'Credit limit is already approved.'];
            $this->load->view('customer_management/approve_credit', $response);
        } else {
            $data = ['credit_status' =>  '1', 'credit_approved_by' => base64_decode($approver_id)];
            $this->db->where('customer_id', $id);
            $res = $this->db->update('cust_customers', $data);
            if ($res) {
                $data = array();
                $data['source_module'] = 'Customers';
                $data['record_id'] = $id;
                $data['created_on'] = date("Y-m-d h:i:s");
                $data['created_by'] = base64_decode($approver_id);
                $data['action_taken'] = 'approve_credit_limit';
                $data['text'] = 'Approved customer limit';
                $log = $this->acm->insert_data('user_log_history', $data);
                $response['response'] = ['status' => 1, 'message' => 'Credit limit is already approved.'];
                $this->load->view('customer_management/approve_credit', $response);
            }
        }
    }
    public function reject_credit_limit()
    {
        $cid = $this->uri->segment(4);
        $id = base64_decode($cid);
        $approver_id = $this->uri->segment(6);
        // Check approve status
        $query = $this->db->select('credit_status')->where('customer_id', $id)->get('cust_customers');
        $query_result = $query->row_array();
        if ($query_result['credit_status'] == 1) {
            $response['response'] = ['status' => 1, 'message' => 'Credit limit is already rejected.'];
            $this->load->view('customer_management/approve_credit', $response);
        } else {
            $data = ['credit_status' =>  '2', 'credit_approved_by' => base64_decode($approver_id)];
            $this->db->where('customer_id', $id);
            $res = $this->db->update('cust_customers', $data);
            if ($res) {
                // Update Log
                $data = array();
                $data['source_module'] = 'Customers';
                $data['record_id'] = $id;
                $data['created_on'] = date("Y-m-d h:i:s");
                $data['created_by'] = base64_decode($approver_id);
                $data['action_taken'] = 'reject_credit_limit';
                $data['text'] = 'Rejected customer limit';
                $log = $this->acm->insert_data('user_log_history', $data);

                $this->db->select('cc.customer_id, cc.created_by, au.uidnr_admin, au.admin_email, cc.customer_name, cc.credit_limit, credit');
                $this->db->from('cust_customers as cc');
                $this->db->join('admin_users as au', 'cc.created_by = au.uidnr_admin');
                $this->db->where('cc.customer_id', $id);
                $query = $this->db->get();
                $result = $query->row();
                $admin_email = $result->admin_email;
                $customer_name = $result->customer_name;
                $credit_limit = $result->credit_limit;
                $credit = $result->credit;
                if ($admin_email !== '') {
                    $html = '<table width="100%" border="0" cellspacing="5" cellpadding="5" style="border-collapse:collapse; font-family:Arial, Helvetica, sans-serif;font-size:12px;">';
                    $html .= '<tr><td colspan="2" style="background-color:#336699"><img src=" <?php echo base_url(); ?>public/img/logo/geo-logo.png" height="53"/></td></tr>';
                    $html .= "<tr><td>Dear Sir,</td></tr>";
                    $html .= "<tr><td>The customer name is " . $customer_name . ". His credit limit is " . $credit_limit . " and his credit days is " . $credit . " is rejected.</td></tr>";
                    $html .= "<tr><td>Regards,</td></tr>";
                    $html .= "<tr><td>Gurgaon Lims</td></tr>";
                    $html .= "<tr>";
                    $html .= '<td align="left" style="background-color:#D5E2F2">Geo Chem Consumer Products Services</td><td align="right" style="background-color:#D5E2F2">GLIMS - Online Lab Information System</td></tr>';
                    $html .= "</table>";
                    $subject = "Reject for credit limit and credit days";
                    send_to_report_approval($admin_email, NULL, NULL, $subject, $html);
                }
                $response['response'] = ['status' => 1, 'message' => 'Credit limit is rejected.'];
                $this->load->view('customer_management/approve_credit',$response);
            }
        }
    }
}
