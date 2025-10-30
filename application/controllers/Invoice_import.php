<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Invoice_import extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Invoice_import_model', 'invoice_import_model');
        $this->permission('Invoice_import/index');
        $checkUser = $this->session->userdata('user_data');
        $this->user = $checkUser->uidnr_admin;
    }

    public function index()
    {
        $this->load_view('Invoice_import/index');
    }

    public function listing($per_page,$search, $page = 0)
    {
        $where = NULL;
        if (!empty($search) && $search != 'NULL') {
            $search = base64_decode($search);
        } else {
            $search = NULL;
        }
        if ($page != 0) {
            $page = ($page - 1) * $per_page;
        }
        $total_row = $this->invoice_import_model->listing(NULL, NULL, $search, $where, '1');
        $config['base_url'] = base_url('Invoice_import/listing');
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

        $result = $this->invoice_import_model->listing($per_page, $page, $search, $where);
        $html = '';
        if ($result) {
            foreach ($result as $value) {
                $page++;
                $html .= '<tr>';
                $html .= '<th scope="col">' . $page . '</th>';
                $html .= '<td>' . $value->customer_id . '</td>';
                $html .= '<td>' . $value->customer_name_excel . '</td>';
                $html .= '<td>' . $value->balance . '</td>';
                $html .= '<td>' . $value->less_60days . '</td>';
                $html .= '<td>' . $value->less_90days . '</td>';
                $html .= '<td>' . $value->less_120days . '</td>';
                $html .= '<td>' . $value->less_180days . '</td>';
                $html .= '<td>' . $value->greater_180days . '</td>';
                $html .= '<td>' . $value->created_by . '</td>';
                $html .= '<td>' . (($value->status > 0) ? 'ACTIVE' : 'IN-ACTIVE') . '</td>';
                $html .= '<td>' . date('Y-m-d', strtotime($value->created_on)) . '</td>';
                $html .= '<td>';
                if ($this->permission_action('Invoice_import/details')) {
                $html .= '<a href="javascript:void(0);" title="DETAILS" class="btn btn-sm edit_role" data-toggle="modal" data-id="' . base64_encode($value->id) . '" data-target="#invoice_details_models"><img width="28px" src="' . base_url('assets/images/icon/view-report.png') . '" alt="BASIL" class="edit_application_data" ></a> ';
                }
                if ($this->permission_action('Invoice_import/send_mail')) {
                $html .= '<a title="SEND MAIL" href="javascript:void(0);" class="btn btn-sm send_mail" data-toggle="modal" data-id="' . base64_encode($value->id) . '" data-target="#send_mail"><img width="28px" src="' . base_url('assets/images/send_mail.png') . '" alt="BASIL" class="edit_application_data" ></a>';
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

    public function import_details()
    {
        $post = $this->input->post();
        set_time_limit(0);
        $data = (array)json_decode($post['excel']);
        if (count($data) > 0) {
            $insert = array();
            foreach ($data as $key => $value) {
                $value = (array)$value;
                if ($value['_No']) {
                    $get = $this->invoice_import_model->get_result('customer_id as id, accop_code as acobe', 'cust_customers', ['LOWER(customer_name)' => strtolower($value['_Name'])]);
                    if ($get) {
                        foreach ($get as $key => &$customer) {
                            if (!empty($customer->id)) {
                                if (!empty($customer->acobe)) {
                                    $this->invoice_import_model->update_data('cust_customers', ['accop_code' => $value['_No']], ['customer_id IN (' . $customer->id . ') ' => null]);
                                }
                            }
                        }
                    }
                }
                $insert[] = array(
                    'balance' => $value['balance'],
                    'customer_id' => $value['_No'],
                    'customer_name_excel' => $value['_Name'],
                    'less_60days' => $value['less_60days'],
                    'less_90days' => $value['less_90days'],
                    'less_120days' => $value['less_120days'],
                    'less_180days' => $value['less_180days'],
                    'greater_180days' => $value['greater_180days'],
                    'created_by' => $this->user
                );
            }
            if (count($insert) > 0) {
                $this->db->truncate('payment_due');
                $insert =  $this->invoice_import_model->insert_multiple_data('payment_due', $insert);
                if ($insert) {
                    $msg = ['status' => 1, 'msg' => 'SUCCESSFULLY INSERT'];
                } else {
                    $msg = ['status' => 0, 'msg' => 'ERROR WHILE RECORD SUBMIT'];
                }
            } else {
                $msg = ['status' => 0, 'msg' => 'NO RECORD FOUND'];
            }
        } else {
            $msg = ['status' => 0, 'msg' => 'NO RECORD FOUND'];
        }

        echo json_encode($msg);
    }
    public function invoice_details()
    {
        $post = $this->input->post();
        set_time_limit(0);
        $data = (array)json_decode($post['excel']);
        $total = count($data);
        if ($total > 0) {
            $this->db->truncate('invoice_due_details');
            $data = array_chunk($data,550);
            foreach ($data as $key => &$value) {
                foreach ($value as $key_row => $value_row) {
                    $insert_batch = (array)$value_row;
                    $insert_batch['created_by'] = $this->user;
                    $insert[] =  $this->invoice_import_model->invoice_insert('invoice_due_details',$insert_batch);
                }
            }
            if (array_sum($insert) == $total) {
                $msg = ['status' => 1, 'msg' => 'SUCCESSFULLY INVOICE DETAILS INSERT'];
            } else {
                $msg = ['status' => 0, 'msg' => 'ERROR WHILE RECORD SUBMIT'];
            }
        } else {
            $msg = ['status' => 0, 'msg' => 'NO RECORD'];
        }
        echo json_encode($msg);
    }
    public function invoice_details_fetch()
    {
        $post = $this->input->post();
        $id = base64_decode($post['id']);
        $result = $this->invoice_import_model->invoice_details_fetch(['payment_due.id' => $id]);
        $html = '<table class="table table-striped table-hover small" ><thead><tr><td>Sn.</td><td>Branch Code</td><td>Division</td><td>Posting Date</td><td>Document Type</td><td>Document No</td><td>Commodity</td><td>Customer code</td><td>Vessel</td><td>Name</td><td>Original Amount</td><td>Remaining Amount</td><td>Remaining Amt. (LCY)</td><td>TDS Rec Amt</td><td>Contact</td><td>Certificate Name</td><td>Certificate Date</td></tr></thead><tbody>';
        if ($result) {
            foreach ($result as $key => $value) {
                $html .= '<tr><td>' . ($key + 1) . '</td><td>' . $value->branch . '</td><td>' . $value->division . '</td><td>' . ((!empty($value->posting_date))? date('d-m-Y',strtotime($value->posting_date)):'') . '</td><td>' . $value->doc_type . '</td><td>' . $value->doc_no . '</td><td>' . $value->commodity . '</td><td>' . $value->customer_code . '</td><td>' . $value->vessel . '</td><td>' . $value->name . '</td><td>' . $value->org_amount . '</td><td>' . $value->rem_amount . '</td><td>' . $value->rem_amount_lcy . '</td><td>' . $value->tds_rec_amt . '</td><td>' . $value->contact . '</td><td>' . $value->certificate_name . '</td><td>' .((!empty($value->certificate_date)) ? date('m-d-Y',strtotime($value->certificate_date)):'') . '</td></tr>';
            }
        } else {
            $html .= '<tr><td colspan="17" align="center">NO RECORD FOUND</td></tr>';
        }
        $html .= '</tbody></table>';
        echo json_encode($html);
    }
    public function invoice_details_fetch_mail()
    {
        $post = $this->input->post();
        $id = base64_decode($post['id']);
        $data = $this->invoice_import_model->get_row('*','payment_due',['id'=>$id]);
        $result = $this->invoice_import_model->invoice_details_fetch(['payment_due.id' => $id]);
        $emails = $this->invoice_import_model->customer_contacts_email(['payment_due.id' => $id]);
        $email = false;
        if ($emails) {
            foreach ($emails as $key => $value) {
                if (!empty($value->email)) {
                    $email[] = $value->email;
                }
            }
        }

        $html = '<p style="text-align:start">Dear Sir,</p><p style="text-align:start"><span style="font-size:small"><span style="color:#222222"><span style="font-family:Arial,Helvetica,sans-serif"><span style="background-color:#ffffff"><span style="background-color:#fafafa"><span style="color:black">Greetings from Geo- Chem Laboratory !</span></span><br /><br /><span style="color:black"><span style="background-color:#fafafa">Kindly note that our record indicates that you have an outstanding balance of&nbsp;<strong>Rs.</strong></span><strong>&nbsp;'.$data->balance.'</strong><span style="background-color:#fafafa">&nbsp;We have yet to receive this payment. Please find attached the ledger account along with our bank detail for your easy reference, request you to please arrange to make the payment as soon as possible. Also request you please share the payment remittance details once payment transfer/remittance in our ICICI bank account to track the payment.</span></span></span></span></span></span></p><p style="text-align:start">Thank you for your cooperation regarding in this matter.&nbsp;In case of any further information in this regard, please feel free to contact us.</p><p style="text-align:start"><span style="font-size:small"><span style="color:#222222"><span style="font-family:Arial,Helvetica,sans-serif"><span style="background-color:#ffffff">&nbsp;</span></span></span></span></p><table cellspacing="0" style="border-collapse:collapse; width:2348px"><tbody><tr><td style="background-color:silver; border-bottom:1px solid black; border-left:1px solid black; border-right:1px solid black; border-top:1px solid black; height:19px; vertical-align:bottom; white-space:nowrap; width:86px"><span style="font-size:15px"><strong><span style="font-family:Calibri,sans-serif">Branch Code</span></strong></span></td><td style="background-color:silver; border-bottom:1px solid black; border-left:none; border-right:1px solid black; border-top:1px solid black; vertical-align:bottom; white-space:nowrap; width:96px"><span style="font-size:15px"><strong><span style="font-family:Calibri,sans-serif">Division Code</span></strong></span></td><td style="background-color:silver; border-bottom:1px solid black; border-left:none; border-right:1px solid black; border-top:1px solid black; vertical-align:bottom; white-space:nowrap; width:87px"><span style="font-size:15px"><strong><span style="font-family:Calibri,sans-serif">Posting Date</span></strong></span></td><td style="background-color:silver; border-bottom:1px solid black; border-left:none; border-right:1px solid black; border-top:1px solid black; vertical-align:bottom; white-space:nowrap; width:108px"><span style="font-size:15px"><strong><span style="font-family:Calibri,sans-serif">Document Type</span></strong></span></td><td style="background-color:silver; border-bottom:1px solid black; border-left:none; border-right:1px solid black; border-top:1px solid black; vertical-align:bottom; white-space:nowrap; width:140px"><span style="font-size:15px"><strong><span style="font-family:Calibri,sans-serif">Document No.</span></strong></span></td><td style="background-color:silver; border-bottom:1px solid black; border-left:none; border-right:1px solid black; border-top:1px solid black; vertical-align:bottom; white-space:nowrap; width:113px"><span style="font-size:15px"><strong><span style="font-family:Calibri,sans-serif">Commodity</span></strong></span></td><td style="background-color:silver; border-bottom:1px solid black; border-left:none; border-right:1px solid black; border-top:1px solid black; vertical-align:bottom; white-space:nowrap; width:96px"><span style="font-size:15px"><strong><span style="font-family:Calibri,sans-serif">Customer No.</span></strong></span></td><td style="background-color:silver; border-bottom:1px solid black; border-left:none; border-right:1px solid black; border-top:1px solid black; vertical-align:bottom; white-space:nowrap; width:262px"><span style="font-size:15px"><strong><span style="font-family:Calibri,sans-serif">Vessel</span></strong></span></td><td style="background-color:silver; border-bottom:1px solid black; border-left:none; border-right:1px solid black; border-top:1px solid black; vertical-align:bottom; white-space:nowrap; width:208px"><span style="font-size:15px"><strong><span style="font-family:Calibri,sans-serif">name</span></strong></span></td><td style="background-color:silver; border-bottom:1px solid black; border-left:none; border-right:1px solid black; border-top:1px solid black; vertical-align:bottom; white-space:nowrap; width:113px"><span style="font-size:15px"><strong><span style="font-family:Calibri,sans-serif">Original Amount</span></strong></span></td><td style="background-color:silver; border-bottom:1px solid black; border-left:none; border-right:1px solid black; border-top:1px solid black; vertical-align:bottom; white-space:nowrap; width:132px"><span style="font-size:15px"><strong><span style="font-family:Calibri,sans-serif">Remaining Amount</span></strong></span></td><td style="background-color:silver; border-bottom:1px solid black; border-left:none; border-right:1px solid black; border-top:1px solid black; vertical-align:bottom; white-space:nowrap; width:147px"><span style="font-size:15px"><strong><span style="font-family:Calibri,sans-serif">Remaining Amt. (LCY)</span></strong></span></td><td style="background-color:silver; border-bottom:1px solid black; border-left:none; border-right:1px solid black; border-top:1px solid black; vertical-align:bottom; white-space:nowrap; width:86px"><span style="font-size:15px"><strong><span style="font-family:Calibri,sans-serif">TDS Rec Amt</span></strong></span></td><td style="background-color:silver; border-bottom:1px solid black; border-left:none; border-right:1px solid black; border-top:1px solid black; vertical-align:bottom; white-space:nowrap; width:324px"><span style="font-size:15px"><strong><span style="font-family:Calibri,sans-serif">Contact</span></strong></span></td><td style="background-color:silver; border-bottom:1px solid black; border-left:none; border-right:1px solid black; border-top:1px solid black; vertical-align:bottom; white-space:nowrap; width:240px"><span style="font-size:15px"><strong><span style="font-family:Calibri,sans-serif">Certificate Name</span></strong></span></td><td style="background-color:silver; border-bottom:1px solid black; border-left:none; border-right:1px solid black; border-top:1px solid black; vertical-align:bottom; white-space:nowrap; width:108px"><span style="font-size:15px"><strong><span style="font-family:Calibri,sans-serif">Certificate Date</span></strong></span></td></tr>';
        if ($result) {
            foreach ($result as $key => $value) {
                $html .= '  <tr><td style="border-bottom:1px solid black; border-left:1px solid black; border-right:1px solid black; border-top:none; height:19px; vertical-align:bottom; white-space:nowrap"><span style="font-size:15px"><span style="font-family:Calibri,sans-serif">' . $value->branch . '</span></span></td><td style="border-bottom:1px solid black; border-left:none; border-right:1px solid black; border-top:none; vertical-align:bottom; white-space:nowrap"><span style="font-size:15px"><span style="font-family:Calibri,sans-serif">' . $value->division . '</span></span></td><td style="border-bottom:1px solid black; border-left:none; border-right:1px solid black; border-top:none; vertical-align:bottom; white-space:nowrap"><span style="font-size:15px"><span style="font-family:Calibri,sans-serif">' . ((!empty($value->posting_date))? date('d-m-Y',strtotime($value->posting_date)):'') . '</span></span></td><td style="border-bottom:1px solid black; border-left:none; border-right:1px solid black; border-top:none; vertical-align:bottom; white-space:nowrap"><span style="font-size:15px"><span style="font-family:Calibri,sans-serif">' . $value->doc_type . '</span></span></td><td style="border-bottom:1px solid black; border-left:none; border-right:1px solid black; border-top:none; vertical-align:bottom; white-space:nowrap"><span style="font-size:15px"><span style="font-family:Calibri,sans-serif">' . $value->doc_no . '</span></span></td><td style="border-bottom:1px solid black; border-left:none; border-right:1px solid black; border-top:none; vertical-align:bottom; white-space:nowrap"><span style="font-size:15px"><span style="font-family:Calibri,sans-serif">' . $value->commodity . '</span></span></td><td style="border-bottom:1px solid black; border-left:none; border-right:1px solid black; border-top:none; vertical-align:bottom; white-space:nowrap"><span style="font-size:15px"><span style="font-family:Calibri,sans-serif">' . $value->customer_code . '</span></span></td><td style="border-bottom:1px solid black; border-left:none; border-right:1px solid black; border-top:none; vertical-align:bottom; white-space:nowrap"><span style="font-size:15px"><span style="font-family:Calibri,sans-serif">' . $value->vessel . '</span></span></td><td style="border-bottom:1px solid black; border-left:none; border-right:1px solid black; border-top:none; vertical-align:bottom; white-space:nowrap"><span style="font-size:15px"><span style="font-family:Calibri,sans-serif">' . $value->name . '</span></span></td><td style="border-bottom:1px solid black; border-left:none; border-right:1px solid black; border-top:none; vertical-align:bottom; white-space:nowrap"><span style="font-size:15px"><span style="font-family:Calibri,sans-serif">' . $value->org_amount . '</span></span></td><td style="border-bottom:1px solid black; border-left:none; border-right:1px solid black; border-top:none; vertical-align:bottom; white-space:nowrap"><span style="font-size:15px"><span style="font-family:Calibri,sans-serif">' . $value->rem_amount . '</span></span></td><td style="border-bottom:1px solid black; border-left:none; border-right:1px solid black; border-top:none; vertical-align:bottom; white-space:nowrap"><span style="font-size:15px"><span style="font-family:Calibri,sans-serif">' . $value->rem_amount_lcy . '</span></span></td><td style="border-bottom:1px solid black; border-left:none; border-right:1px solid black; border-top:none; vertical-align:bottom; white-space:nowrap"><span style="font-size:15px"><span style="font-family:Calibri,sans-serif">' . $value->tds_rec_amt . '</span></span></td><td style="border-bottom:1px solid black; border-left:none; border-right:1px solid black; border-top:none; vertical-align:bottom; white-space:nowrap"><span style="font-size:15px"><span style="font-family:Calibri,sans-serif">' . $value->contact . '</span></span></td><td style="border-bottom:1px solid black; border-left:none; border-right:1px solid black; border-top:none; vertical-align:bottom; white-space:nowrap"><span style="font-size:15px"><span style="color:black"><span style="font-family:Calibri,sans-serif">' . $value->certificate_name . '</span></span></span></td><td style="border-bottom:1px solid black; border-left:none; border-right:1px solid black; border-top:none; vertical-align:bottom; white-space:nowrap"><span style="font-size:15px"><span style="color:black"><span style="font-family:Calibri,sans-serif">' . ((!empty($value->certificate_date)) ? date('m-d-Y',strtotime($value->certificate_date)):'') . '</span></span></span></td></tr>';
            }
        } 
        $html .= '</tbody></table><p style="text-align:start"><span style="font-size:small"><span style="color:#222222"><span style="font-family:Arial,Helvetica,sans-serif"><span style="background-color:#ffffff"><span style="background-color:#fafafa"><span style="color:black">If this amount or any amount from above mentioned details has already been paid and sent, please disregard this mail &amp; forward us the details of payment made by you. Otherwise you are requested to please make the payment at the earliest &amp; forward us the details.</span></span></span></span></span></span></p><p style="text-align:start"><span style="font-size:small"><span style="color:#222222"><span style="font-family:Arial,Helvetica,sans-serif"><span style="background-color:#ffffff"><span style="background-color:#fafafa"><span style="color:black">Also find attached the bank details for making the payment for your easy reference.</span></span></span></span></span></span></p><p style="text-align:start"><span style="font-size:small"><span style="color:#222222"><span style="font-family:Arial,Helvetica,sans-serif"><span style="background-color:#ffffff"><span style="background-color:#fafafa"><span style="color:black">Thank you for your cooperation regarding in this matter. We sincerely hope we can continue doing business together in the future.&nbsp;&nbsp;</span></span></span></span></span></span></p><p style="text-align:start"><br /><span style="font-size:small"><span style="color:#222222"><span style="font-family:Arial,Helvetica,sans-serif"><span style="background-color:#ffffff"><strong><span style="color:#002060">Thanks and Regards,&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></strong></span></span></span></span></p><p style="text-align:start"><span style="font-size:small"><span style="color:#222222"><span style="font-family:Arial,Helvetica,sans-serif"><span style="background-color:#ffffff"><strong><span style="color:gray">Rajesh Kumar Singh</span></strong></span></span></span></span></p><p style="text-align:start"><span style="font-size:small"><span style="color:#222222"><span style="font-family:Arial,Helvetica,sans-serif"><span style="background-color:#ffffff"><span style="color:gray">Sr. Credit Control Executive&nbsp;</span><span style="font-size:9.5pt"><span style="background-color:white"><span style="font-family:Arial,sans-serif"><span style="color:gray">| CPS Division&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></span></span></span></span></span></span></span></p><p style="text-align:start"><span style="font-size:small"><span style="color:#222222"><span style="font-family:Arial,Helvetica,sans-serif"><span style="background-color:#ffffff"><span style="color:#1f497d"></span></span></span></span></span></p><p style="text-align:start"><span style="font-size:small"><span style="color:#222222"><span style="font-family:Arial,Helvetica,sans-serif"><span style="background-color:#ffffff"><strong><span style="color:#002060">GEO-CHEM LABORATORIES PVT. LTD.</span></strong></span></span></span></span></p><p style="text-align:start"><span style="font-size:small"><span style="color:#222222"><span style="font-family:Arial,Helvetica,sans-serif"><span style="background-color:#ffffff"><span style="color:gray">Telephone&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; :&nbsp; 0124 6250500 / 523</span></span></span></span></span></p><p style="text-align:start"><span style="font-size:small"><span style="color:#222222"><span style="font-family:Arial,Helvetica,sans-serif"><span style="background-color:#ffffff"><span style="color:gray">Mobile&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; :&nbsp;&nbsp; 09711450411</span></span></span></span></span></p><p style="text-align:start"><span style="font-size:small"><span style="color:#222222"><span style="font-family:Arial,Helvetica,sans-serif"><span style="background-color:#ffffff"><span style="color:gray">Website&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; :&nbsp;&nbsp;</span><span style="color:#1f497d"><a href="https://basilrl.com/" style="color:#1155cc" target="_blank">https://basilrl.com</a></span></span></span></span></span></p><p style="text-align:start"><span style="font-size:small"><span style="color:#222222"><span style="font-family:Arial,Helvetica,sans-serif"><span style="background-color:#ffffff"><span style="color:gray">Address&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; :&nbsp; 306, Udyog Vihar, phase II, Gurgaon, India &ndash; 122016</span></span></span></span></span></p>';
        echo json_encode(['html' => $html, 'email' => $email,'subject'=>$data->customer_name_excel]);
    }
    public function send_mail()
    {
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');
        $this->form_validation->set_rules('id', 'UNIQUE ID', 'trim|required');
        $this->form_validation->set_rules('email', 'EMAIL', 'trim|required|valid_emails');
        $this->form_validation->set_rules('email_cc', 'EMAIL', 'trim|valid_emails');
        $this->form_validation->set_rules('subject', 'SUBJECT', 'trim|required');
        $this->form_validation->set_rules('text', 'CONTENT', 'trim|required');
        if ($this->form_validation->run() == TRUE) {
            $post = $this->input->post();
            $user = $this->session->userdata('user_data');
            if (INSTANCE_TYPE == 'development') {
                $mail = $this->mail_send('developer.cps04@basilrl.com',$post['subject'],'developer.cps04@basilrl.com',$post['text'],'shankar.k@basilrl.com','https://cpslims-prod.s3.ap-south-1.amazonaws.com/ci_lims/image/Bank_Details_of_ICICI-26-Apr-3685.pdf');
            } else {
                $mail = $this->mail_send($post['email'],$post['subject'],$post['email_cc'],$post['text'],'creditcontrol.cps@basilrl.com','https://cpslims-prod.s3.ap-south-1.amazonaws.com/ci_lims/image/Bank_Details_of_ICICI-26-Apr-3685.pdf');
            }
            
            if ($mail) {
                $msg = ['status' => 1, 'msg' => 'SUCCESSFULLY SEND MAIL TO '.$post['email']];
            } else {
                $msg = ['status' => 0, 'msg' => 'ERROR WHILE SEND MAIL TO '.$post['email']];
            }
        } else {
            $msg = array(
                'status' => 0,
                'errors' => $this->form_validation->error_array(),
                'msg' => 'PLEASE ENTER VALID TEXT'
            );
        }
        echo json_encode($msg);
    }
    public function mail_send($to, $subject, $cc, $body, $from,$url = null)
    {

        $this->load->library('email');
        $config['protocol'] = PROTOCOL;
        $config['smtp_host'] = HOST;
        $config['smtp_user'] = USER;
        $config['smtp_pass'] = PASS;
        $config['smtp_port'] = PORT;
        $config['newline'] = "\r\n";
        $config['smtp_crypto'] = CRYPTO;
        $config['charset'] = 'utf-8';
        $config['newline'] = "\r\n";
        $config['mailtype'] = 'html';
        $this->email->initialize($config);
        $this->email->from($from);
        $this->email->to($to);
        if (!empty($cc)) {
            $this->email->cc($cc);
        }
        $this->email->subject($subject);
        $this->email->message($body);
        if (!empty($url)){
            $this->email->attach($url);
        }
        $bool = $this->email->send();
        if ($bool) {
            $data= array(
                'to'=>$to,
                'cc'=>$cc,
                'subject'=>$subject,
                'text'=>$body,
                'created_by'=>$this->user,
                'from'=>$from,
            );
            $this->invoice_import_model->log('mail_log_invoice_details',$data);
            return true;
        } else {
            show_error($this->email->print_debugger());
        }
    }
}
