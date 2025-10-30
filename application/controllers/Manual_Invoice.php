<?php
defined('BASEPATH') or exit('No direct access allowed');

class Manual_Invoice extends MY_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->check_session();
        $this->load->model('ManualInvoice', 'IV');
        $checkUser = $this->session->userdata('user_data');
        $this->user = $checkUser->uidnr_admin;
    }
    public function Upload_invoice()
    {
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');
        $this->form_validation->set_rules('proforma_invoice_id', 'INVOICE ID', 'trim|required');
        $this->form_validation->set_rules('sample_reg_id', 'SAMPLE REGISTRATION ID', 'trim|required');
        $this->form_validation->set_rules('al_report_number', 'Alternate Invoice Number', 'trim');
        $this->form_validation->set_rules('Invoice_Amount', 'Invoice Amount', 'trim|required|numeric');
        $this->form_validation->set_rules('Payment_Status', 'Payment Status', 'trim|required|in_list[Paid,UnPaid]');
        $this->form_validation->set_rules('Payment_Date', 'Payment Date', 'trim');
        $this->form_validation->set_rules('generated_date', 'Report Date', 'trim|required');
        $this->form_validation->set_rules('Payment_Mode', 'Payment Mode', 'trim');
        $this->form_validation->set_rules('Remarks', 'Remarks', 'trim');
        $this->form_validation->set_rules('file', 'MANUAL INVOICE', 'trim|callback_file_check');
        if ($this->form_validation->run() == true) {
            $file = $_FILES['file'];
            $post = $this->input->post();
            $sample_reg_id = $post['sample_reg_id'];
            $res['invoice_proforma_invoice_status_id'] = 2;
            $pi_id = $this->IV->get_row('proforma_invoice_id', 'invoice_proforma', ['proforma_invoice_sample_reg_id' => $post['sample_reg_id']]);
            if ($pi_id && $pi_id->proforma_invoice_id > 0) {
                $state = $this->IV->update_data("invoice_proforma", $res, ['proforma_invoice_sample_reg_id' => $post['sample_reg_id']]);
            } else {
                $results = $this->IV->trf_details(["sr.sample_reg_id" => $sample_reg_id]);
                $address = $this->IV->get_row("c.customer_id,c.city,c.po_box,(SELECT location_name FROM mst_locations where location_id = c.cust_customers_location_id) as location,(SELECT province_name FROM mst_provinces WHERE province_id = c.cust_customers_province_id) as province,(SELECT country_name FROM mst_country WHERE country_id = c.cust_customers_country_id) as country", "cust_customers c", ["c.customer_id" => $results->trf_applicant]);
                $address = $address->location . "," . $address->po_box . "  P O  ," . $address->city . "," . $address->province . "," . $address->country;
                $est_value = $this->IV->test_sum(["st.sample_test_sample_reg_id" => $sample_reg_id]);
                $res['manually_generate'] = 1;
                $res['proforma_invoice_date'] = date("Y-m-d h:i:s");
                $res['created_by'] = $this->user;
                $res['invoice_proforma_customer_id'] = $results->trf_applicant;
                $res['proforma_invoice_sample_reg_id'] = $sample_reg_id;
                $res['proforma_invoice_job_type'] = 'Quote';
                $res['proforma_client_address'] = $address;
                if ($est_value->total > 0) {
                    $res['total_amount'] = $est_value->total;
                }
                $pi_id = $this->IV->insert_data("invoice_proforma", $res);
                $division_id = $this->IV->get_row("division_id", 'sample_registration', ["sample_reg_id" => $sample_reg_id]);
                $division_code = $this->IV->get_row("division_code", "mst_divisions", ["division_id" => $division_id->division_id]);
                $rand = str_pad($pi_id, 5, "0", STR_PAD_LEFT);
                $branch_code = $this->IV->branch_code(['sample_registration.sample_reg_id' => $sample_reg_id]);
                $pi_number['proforma_invoice_number'] = 'PI' . $branch_code . $division_code->division_code . date('Y') . '-' . $rand;
            }
            $gc_no = $this->IV->get_row("gc_no", "sample_registration", ["sample_reg_id" => $sample_reg_id]);
            $invoicesCnt = $this->IV->get_row("*", "Invoices", ["report_num" => $gc_no->gc_no]);
            if ($invoicesCnt) {
                $state = $invoicesCnt->invoiced_id;
                $id = $state;
            } else {
                $results = $this->IV->trf_details(["sr.sample_reg_id" => $sample_reg_id]);
                $data['report_num'] = $gc_no->gc_no;
                $proforma_invoice_id = $this->IV->get_row("proforma_invoice_id", "invoice_proforma", ["proforma_invoice_sample_reg_id" => $sample_reg_id]);

                $data['proforma_invoice_id'] = $proforma_invoice_id->proforma_invoice_id;
                $data['report_generated_by'] = $this->user;
                $data['generated_date '] = date("Y-m-d H:i:s");
                $state = $this->IV->insert_data("Invoices", $data);
                /* End for generate INVOICE Number */
                $id = $state;
                if ($state && $state > 0) {
                    $logDetail = array(
                        'source_module' => 'MANUAL INVOICE',
                        'operation' => 'generateManaualInvoice',
                        'uidnr_admin' => $this->user,
                        'customer_id' => $results->trf_applicant,
                        'log_activity_on' => date("Y-m-d H:i:s"),
                        'action_message' => "Generate Invoice With Invoice Number " . $gc_no->gc_no
                    );
                    $this->IV->insert_data("customer_activity_log", $logDetail);
                }
            }
            $data['status'] = 'Manual Invoice';
            if ($state && $state > 0) {
                $this->IV->update_data('Invoices', ['status' => 'Manual Invoice'], ['invoiced_id' => $state]);
                $sample_status3 = $this->IV->get_row("status", "sample_registration", ["sample_reg_id" => $sample_reg_id]);
            }
            $manual_invoice = $this->IV->get_row("*", "manual_invoice", ["LOWER(report_num)" => strtolower($gc_no->gc_no)]);
            $aws_path = $this->multiple_upload_image($file);
            if ($manual_invoice) {
                $data = array(
                    'al_report_number' => $post['al_report_number'],
                    'generated_date' => $post['generated_date'],
                    'uploadfilename' => basename($aws_path['aws_path']),
                    'uploadfilepath' => $aws_path['aws_path'],
                    'Payment_Status' => (($post['Payment_Status'])?$post['Payment_Status']:''),
                    'Payment_Mode' => $post['Payment_Mode'],
                    'Payment_Date' => (($post['Payment_Date'])?$post['Payment_Date']:''),
                    'Invoice_Amount' => $post['Invoice_Amount'],
                    'Remarks' => $post['Remarks']
                );
                $result = $this->IV->update_data("manual_invoice", $data, ['invoice_id' => $state]);
                if ($result) {
                    $this->session->set_flashdata('success', 'SUCCESSFULLY UPDATE MANUAL INVOICE.');
                    $msg = array('status' => 1, 'msg' => 'SUCCESSFULLY UPDATE MANUAL INVOICE');
                } else {
                    $msg = array('status' => 0, 'msg' => 'ERROR WHILE UPDATE MANUAL INVOICE');
                }

                $sample_status3 = $this->IV->get_row("status", "sample_registration ", ["sample_reg_id" => $sample_reg_id]);
                $logDetails = array(
                    'module' => 'Samples',
                    'old_status' => $sample_status3->status,
                    'new_status' => 'Manual Invoice Updated',
                    'sample_reg_id' => $sample_reg_id,
                    'sample_assigned_lab_id' => '',
                    'action_message' => 'Manual Invoice Updated',
                    'sample_job_id' => '',
                    'report_id' => '',
                    'report_status' => '',
                    'test_ids' => '',
                    'test_names' => '',
                    'test_newstatus' => '',
                    'test_oldStatus' => '',
                    'test_assigned_to' => ''
                );
                $this->IV->insert_data("sample_reg_activity_log", $logDetails);
            } else {
                $data = array(
                    'invoice_id' => $state,
                    'report_num' => $gc_no->gc_no,
                    'al_report_number' => $post['al_report_number'],
                    'generated_date' => $post['generated_date'],
                    'uploadfilename' => basename($aws_path['aws_path']),
                    'uploadfilepath' => $aws_path['aws_path'],
                    'Payment_Status' => $post['Payment_Status'],
                    'Payment_Mode' => $post['Payment_Mode'],
                    'Payment_Date' => $post['Payment_Date'],
                    'Invoice_Amount' => $post['Invoice_Amount'],
                    'Remarks' => $post['Remarks']
                );
                $result = $this->IV->insert_data("manual_invoice", $data);
                if ($result) {
                    $this->session->set_flashdata('success', 'SUCCESSFULLY INSERT MANUAL INVOICE.');
                    $msg = array('status' => 1, 'msg' => 'SUCCESSFULLY INSERT MANUAL INVOICE');
                } else {
                    $msg = array('status' => 0, 'msg' => 'ERROR WHILE INSERT MANUAL INVOICE');
                }

                $sample_status3 = $this->IV->get_row("status", "sample_registration ", ["sample_reg_id" => $sample_reg_id]);
                $logDetails = array(
                    'module' => 'Samples',
                    'old_status' => $sample_status3->status,
                    'new_status' => 'Manual Invoice Uploaded',
                    'sample_reg_id' => $sample_reg_id,
                    'sample_assigned_lab_id' => '',
                    'action_message' => 'Invoice uploaded Manually',
                    'sample_job_id' => /* $sample_status3['sample_job_id'], */ '',
                    'report_id' => '',
                    'report_status' => '',
                    'test_ids' => '',
                    'test_names' => '',
                    'test_newstatus' => '',
                    'test_oldStatus' => '',
                    'test_assigned_to' => ''
                );
                $this->IV->insert_data("sample_reg_activity_log", $logDetails);
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
    public function file_check($file)
    {
        $files = $_FILES;
        foreach ($files as $key => $value) {
            if (empty($value['name']) || count($value['name']) < 1) {
                $this->form_validation->set_message('file_check', 'THIS FILE REQUIRED PLEASE SELECT');
                return false;
            }
        }
        return true;
    }
    public function get_invoice_manual()
    {
        $post = $this->input->post();
        $gc_no = $this->IV->get_row("gc_no as report_num", "sample_registration", ["sample_reg_id" => $post['sample_reg_id']]);
        if ($gc_no) {
            $record = $this->IV->get_row("report_num,Invoice_Amount,DATE_FORMAT(Payment_Date, '%Y-%m-%d') as Payment_Date,Payment_Mode,Payment_Status,Remarks,al_report_number,DATE_FORMAT(generated_date, '%Y-%m-%d') as generated_date", "manual_invoice", ["LOWER(report_num)" => strtolower($gc_no->report_num)]);
            if ($record) {
                echo json_encode($record);
            } else {
                echo json_encode($gc_no);
            }
        } else {
            echo json_encode(FALSE);
        }
    }
    public function back_up($id)
    {
        if ($id == '8520') {
            set_time_limit(0);
            ini_set("memory_limit", -1);
            $this->load->dbutil();
            $prefs = array(
                'format'      => 'zip',
                'filename'    => 'my_db_backup.sql'
            );
            $backup = &$this->dbutil->backup($prefs);
            $db_name = 'backup-LIMS_CI-' . date("Y-m-d-H-i-s") . '.zip';
            $this->load->helper('file');
            $this->load->helper('download');
            force_download($db_name, $backup);
        } else {
            redirect($_SERVER['HTTP_REFERER']);
        }
    }

    public function back_upfiles($id)
    {
        if ($id == '8520') {
            set_time_limit(0);
            ini_set("memory_limit", -1);
            $this->load->library('zip');
            $this->zip->read_dir(FCPATH, FALSE);
            $this->zip->download('LIMS_CI_FOLDER' . date("Y-m-d-H-i-s") . '.zip');
        } else {
            redirect($_SERVER['HTTP_REFERER']);
        }
    }
}
