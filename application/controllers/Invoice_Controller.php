<?php
defined('BASEPATH') or exit('No direct access allowed');

class Invoice_Controller extends MY_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->check_session();
        $checkUser = $this->session->userdata('user_data');
        $this->user = $checkUser->uidnr_admin;
        $this->load->model('Invoice', 'invoice');
    }

    public function performa_invoice_list($page = 0, $trf = null, $customer_name = null, $product = null, $created_on = null, $ulr_no = null, $gc_number = null, $proforma_number = null, $buyer = null, $status = null, $division = null, $sales_person = NULL, $client_city = NULL, $proforma_amt = NULL, $profroma_made = NULL) // added by millan on 23-06-2021 updated on 29-06-2021
    {
        $per_page = "10";
        $page = $this->uri->segment(2);
        if ($page != 0) {
            $page = ($page - 1) * $per_page;
        } else {
            $page = 0;
        }
        $total_count_data = $this->invoice->performa_invoice($per_page, $page, $trf, $customer_name, $product, $created_on, $ulr_no, $gc_number, $proforma_number, $buyer, $status, $division, $sales_person, $client_city, $proforma_amt, $profroma_made, true); // added by millan on 23-06-2021
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
        $config['base_url'] = base_url() . "performa-invoice";
        $config['use_page_numbers'] = TRUE;
        $config['uri_segment'] = 2;
        $config['total_rows'] = $total_count_data['cnt'];
        $config['per_page'] = $per_page;
        $this->pagination->initialize($config);
        $data['pagination'] = $this->pagination->create_links();
        $data['performa_invoice'] = $this->invoice->performa_invoice($page, $per_page, $trf, $customer_name, $product, $created_on, $ulr_no, $gc_number, $proforma_number, $buyer, $status, $division, $sales_person, $client_city, $proforma_amt, $profroma_made); // added by millan on 23-06-2021
        $this->session->set_userdata('excel_data', $total_count_data['last_query']); // added by millan on 23-06-2021
        unset($data['performa_invoice']['last_query']); // added by millan on 23-06-2021
        if ($total_count_data['cnt'] > 0) {
            $start = (int)$page + 1;
        } else {
            $start = 0;
        }
        $end = (($data['performa_invoice']) ? count($data['performa_invoice']) : 0) + (($page) ? $page : 0);
        $data['result_count'] = "Showing " . $start . " - " . $end . " of " . $total_count_data['cnt'] . " Results";
        $data['customer'] = $this->invoice->get_fields("cust_customers", "customer_id,customer_name");
        $data['invoice_status'] = $this->invoice->get_fields("invoice_status", "invoice_status_id,invoice_status_name");
        // $data['division'] = $this->invoice->get_fields("mst_divisions", "division_id,division_name");
        // Changed by saurabh on 11-08-2021
        $data['division'] = $this->invoice->get_fields_by_id("mst_divisions", "*", '1', 'status');
        // Changed by saurabh on 11-08-2021
        $data['buyer'] = $this->invoice->get_fields_by_id("cust_customers", "customer_id,customer_name", "Buyer", "customer_type");
        $data['products'] = $this->invoice->get_products();
        $data['salepsn'] = $this->invoice->get_sales_person(); // added by millan for fetching sales_person list on 23-06-2021
        $data['clicity'] = $this->invoice->get_client_city(); // added by millan for fetching city list on 24-06-2021
        $data['prof_per'] = $this->invoice->get_proforma_created(); // added by millan for fetching proforma created by list on 24-06-2021
        $this->load_view('invoice/performa-invoice', $data);
    }

    public function proforma_invoice_details()
    {
        $proforma_invoice_id = $this->input->post('proforma_invoice_id');
        $sample_reg_id = $this->input->post('sample_reg_id');
        $data = $this->invoice->proforma_invoice_details($proforma_invoice_id, $sample_reg_id);
        $data['loadProformaInvoice'] = $this->invoice->proforma_details($proforma_invoice_id);
        echo json_encode($data);
    }

    public function sample_detail_load_view()
    {
        $sample_reg_id = $this->input->post('sample_reg_id');
        $proforma_invoice_id = $this->input->post('proforma_invoice_id');

        $data = $this->invoice->get_sample_result($sample_reg_id, $proforma_invoice_id);
        echo json_encode($data);
    }

    public function invoice_template()
    {
        $data = $this->invoice->invoice_template();
        echo json_encode($data);
    }

    public function report_view()
    {
        $proforma_invoice_id = $this->input->post('proforma_invoice_id');
        $template_id = $this->input->post('template_id');
        $data = $this->invoice->report_view($proforma_invoice_id, $template_id);
    }

    public function invoice_log()
    {
        $proforma_invoice_id = $this->input->post('proforma_invoice_id');
        $data = $this->invoice->invoice_log($proforma_invoice_id);
        echo json_encode($data);
    }

    public function revise()
    {
        $invoice_id = $this->input->post('proforma_invoice_id');
        // Get old status
        $query = $this->db->select('invoice_status_name')
            ->join('invoice_status', 'invoice_proforma_invoice_status_id = invoice_status_id')
            ->where('proforma_invoice_id', $invoice_id)
            ->get('invoice_proforma');
       if ($query->num_rows() > 0) {
            $query_data = $query->row_array();
            $old_status = $query_data['invoice_status_name'];
        } else {
            $old_status = '';
        }
        $revise_status = $this->input->post('revise_status');
        $save = $this->invoice->revise($invoice_id, $revise_status);
        if ($revise_status == 1) {
            $action_message = 'Proforma Invoice revised after proforma approve';
        } else {
            $action_message = 'Proforma Invoice revised';
        }
        if ($save) {
            $logdetails = array(
                'module'    => 'Invoice',
                'operation' => 'revise',
                'source_module' => 'Invoice_Controller',
                'uidnr_admin'   => $this->admin_id(),
                'log_activity_on'   => date('Y-m-d H:i:s'),
                'action_message'    => $action_message,
                'invoice_id'     => $invoice_id,
                'new_status'    => 'Revised',
                'old_status'    => $old_status
            );
            $this->db->insert('invoice_activity_log', $logdetails);
            echo json_encode(["message" => "Proforma Invoice Revised successfully.", "status" => 1]);
        } else {
            echo json_encode(["message" => "Something went wrong!.", "status" => 0]);
        }
    }

    public function without_approve()
    {
        $proforma_invoice_id = $this->input->post('proforma_invoice_id');
        $update = $this->invoice->without_approve($proforma_invoice_id);

        if ($update) {
            $this->session->set_flashdata('success', 'Data saved successfully');
            echo true;
        } else {
            $this->session->set_flashdata('false', 'Something went wrong!.');
            echo false;
        }
    }

    public function save_test()
    {
        $record = $this->input->post();
        $trf_quote_id = $record['trf_quote_id'];
        $dynamic_data = $record['test'];
        $invoice_id = $record['proforma_invoice_id'];
        $sample_reg_id = $record['sample_reg_id'];

        $zerovalidation = false;
        $data = array();
//   new surcharge
        if($record['surcharge'] > 100){
            echo json_encode(["message" => "Surcharge % cannot be more than 100", "status" => 0]);
            exit;
        }
// end 
        foreach ($dynamic_data as $key => $value) {
            // Check if parameter is of package
            if ($value['invoice_package_id'] > 0) {
                if ($key == 0) {
                    if ($value['applicable_charge'] <= 0) {
                        $zerovalidation = true;
                        break;
                    }
                }
                $value['sample_registration_id'] = $sample_reg_id;
                $value['invoice_id'] = $invoice_id;
                $value['created_by'] = $this->session->userdata('user_data')->uidnr_admin;
                $value['created_on'] = date('Y-m-d H:i:s');
                $data[$key] = $value;
            } elseif ($value['invoice_protocol_id'] > 0) {
                if ($key == 0) {
                    if ($value['applicable_charge'] <= 0) {
                        $zerovalidation = true;
                        break;
                    }
                }
                $value['sample_registration_id'] = $sample_reg_id;
                $value['invoice_id'] = $invoice_id;
                $value['created_by'] = $this->session->userdata('user_data')->uidnr_admin;
                $value['created_on'] = date('Y-m-d H:i:s');
                $data[$key] = $value;
            } elseif ($value['invoice_quote_type'] == "Test") {
                if ($value['applicable_charge'] <= 0) {
                    $zerovalidation = true;
                    break;
                } else {
                    $value['sample_registration_id'] = $sample_reg_id;
                    $value['invoice_id'] = $invoice_id;
                    $value['created_by'] = $this->session->userdata('user_data')->uidnr_admin;
                    $value['created_on'] = date('Y-m-d H:i:s');
                    $data[$key] = $value;
                }
            } elseif ($value['invoice_quote_type'] == "Package") {
                if ($key == 0) {
                if ($value['applicable_charge'] <= 0) {
                        $zerovalidation = true;
                        break;
                    }
                } 
                $value['sample_registration_id'] = $sample_reg_id;
                $value['invoice_id'] = $invoice_id;
                $value['created_by'] = $this->session->userdata('user_data')->uidnr_admin;
                $value['created_on'] = date('Y-m-d H:i:s');
                $data[$key] = $value;
            } elseif ($value['invoice_quote_type'] == "Protocol") {
                if ($key == 0) {
                    if ($value['applicable_charge'] <= 0) {
                        $zerovalidation = true;
                        break;
                    }
                } 
                $value['sample_registration_id'] = $sample_reg_id;
                $value['invoice_id'] = $invoice_id;
                $value['created_by'] = $this->session->userdata('user_data')->uidnr_admin;
                $value['created_on'] = date('Y-m-d H:i:s');
                $data[$key] = $value;
            } elseif (empty($value['invoice_quote_type'])) {
                if ($value['applicable_charge'] <= 0) {
                    $zerovalidation = true;
                    break;
                } else {
                    $value['sample_registration_id'] = $sample_reg_id;
                    $value['invoice_id'] = $invoice_id;
                    $value['created_by'] = $this->session->userdata('user_data')->uidnr_admin;
                    $value['created_on'] = date('Y-m-d H:i:s');
                    $data[$key] = $value;
                }
            }
        }
        if ($zerovalidation) {
            echo json_encode(["message" => "Rate Per Test can not be zero.", "status" => 0]);
            exit;
        } else {
            $save = $this->invoice->save_test($data);
        }

        if ($record['total_amount'] <= 0) {
            echo json_encode(["message" => "Total amount can not be zero.", "status" => 0]);
            exit;
        }
        $this->db->trans_start();
        $input_array = array(
            'total_amount'      => $record['total_amount'],
            'show_discount'     => $record['show_discount'],
            'invoice_remark'    => $record['invoice_remark'], //updated by saurabh on 05-08-2021
            'surcharge_percentage'    => $record['surcharge'], // new surcharge
            'surcharge_amount'    => $record['surchargeTotal'] // new surcharge
        );

        $update = $this->invoice->update_data('invoice_proforma', $input_array, ['proforma_invoice_id' => $invoice_id]);
        // $dynamic_data = $record['test'];
        // foreach ($dynamic_data as $key => $value) {
        //     $value['sample_registration_id'] = $sample_reg_id;
        //     $value['invoice_id'] = $invoice_id;
        //     $value['created_by'] = $this->session->userdata('user_data')->uidnr_admin;
        //     $value['created_on'] = date('Y-m-d H:i:s');
        //     $data[$key] = $value;
        // }

        // $save = $this->invoice->save_test($data);
        $this->db->trans_complete();
        if ($save) {
            $data['invoice_detail'] = $this->invoice->get_invoice_details($invoice_id);
            $data['test_details'] = [];
            $data['package_details'] = [];
            $data['protocol_details'] = [];
            $data['test_details'] = $this->invoice->get_test($invoice_id);
            $data['package_details'] = $this->invoice->get_package($invoice_id);
            $data['protocol_details'] = $this->invoice->get_protocol($invoice_id);
            // Get country id of the user
            $country_id = $this->invoice->get_country_id();
            $state = $data['invoice_detail']->state;
            $IGST = $this->invoice->get_fields_by_id("sys_configuration", "cfg_Value", "IGST", "cfg_Name")[0]['cfg_Value'];
            $SGST = $this->invoice->get_fields_by_id("sys_configuration", "cfg_Value", "SGST", "cfg_Name")[0]['cfg_Value'];
            $CGST = $this->invoice->get_fields_by_id("sys_configuration", "cfg_Value", "CGST", "cfg_Name")[0]['cfg_Value'];
            $UTGST = $this->invoice->get_fields_by_id("sys_configuration", "cfg_Value", "UTGST", "cfg_Name")[0]['cfg_Value'];
            $VAT = $this->invoice->get_fields_by_id("sys_configuration", "cfg_Value", "VAT", "cfg_Name")[0]['cfg_Value'];
            // Get dynamic fields value
            $dynamic_value_query = $this->db->select('product_custom_fields')
                ->where('trf_id', $data['invoice_detail']->trf_id)
                ->get('trf_registration');
            if ($dynamic_value_query->num_rows() > 0) {
                $dynamic_value = $dynamic_value_query->result_array()[0]['product_custom_fields'];
                $data['fields'] = json_decode($dynamic_value);
            } else {
                $data['fields'] = [];
            }
            $templateVars = [];
            $gst = 0;
            if ($data['invoice_detail']->total_amount > 0) {

                $data['invoice_detail']->total_amount;
                // new surcharge
                $data['invoice_detail']->amount = $data['invoice_detail']->total_amount;
                if($data['invoice_detail']->surcharge_amount == ''){
                    $data['invoice_detail']->total_amount = $data['invoice_detail']->total_amount;
                }else{
                    $data['invoice_detail']->total_amount = $data['invoice_detail']->surcharge_amount;
                }
                // end 

                if (($data['invoice_detail']->country_id == 1) && ($data['invoice_detail']->cust_type != 'SEZ Unit' && $data['invoice_detail']->cust_type != 'SEZ Development')) {
                    if ($this->invoice->gstCalculation($state, 'IGST', $data['invoice_detail']->total_amount, $IGST) > 0) {
                        $gst = $this->invoice->gstCalculation($state, 'IGST', $data['invoice_detail']->total_amount, $IGST);
                        $templateVars['GST'] = ('IGST @ ' . $IGST . '% ' . number_format($gst, $data['invoice_detail']->currency_decimal));
                    }

                    if ($this->invoice->gstCalculation($state, 'SGST', $data['invoice_detail']->total_amount, $SGST) > 0) {
                        $gst = $this->invoice->gstCalculation($state, 'SGST', $data['invoice_detail']->total_amount, $SGST);
                        $sgst = $gst;
                    }
                    if ($this->invoice->gstCalculation($state, 'CGST', $data['invoice_detail']->total_amount, $CGST) > 0) {
                        $gst += $this->invoice->gstCalculation($state, 'CGST', $data['invoice_detail']->total_amount, $CGST);
                        $templateVars['GST'] = 'SGST @ ' . $SGST . '% &nbsp; ' . number_format($this->invoice->gstCalculation($state, 'CGST', $data['invoice_detail']->total_amount, $SGST), $data['invoice_detail']->currency_decimal) . '<br/>' . 'CGST @ ' . $CGST . '%  &nbsp; ' . number_format($this->invoice->gstCalculation($state, 'CGST', $data['invoice_detail']->total_amount, $CGST), $data['invoice_detail']->currency_decimal);
                    }
                    if ($this->invoice->gstCalculation($state, 'UTGST', $data['invoice_detail']->total_amount, $UTGST)) {
                        $gst = $this->invoice->gstCalculation($state, 'UTGST', $data['invoice_detail']->total_amount, $UTGST);
                        $templateVars['GST'] = ('UTGST @ ' . $UTGST . '% ' . number_format($gst, $data['invoice_detail']->currency_decimal));
                    }
                } else {
                    if ($this->invoice->gstCalculation($state, 'VAT', $data['invoice_detail']->total_amount, $VAT) > 0) {
                        $gst = $this->invoice->gstCalculation($state, 'VAT', $data['invoice_detail']->total_amount, $VAT);
                        $templateVars['VAT'] = 'VAT @ ' . $VAT . '% &nbsp; ' . number_format($gst, $data['invoice_detail']->currency_decimal);
                    }
                }
            } else {
                echo json_encode(["message" => "Total amount can not be zero.", "status" => 0]);
                exit;
            }

            $data['sample_desc'] = $data['invoice_detail']->sample_desc;
            $data['invoice_detail']->gst = $templateVars;
            $data['invoice_detail']->price_with_gst = $data['invoice_detail']->total_amount + $gst;
            $this->invoice->update_data('invoice_proforma',['price_with_gst' => $gst],['proforma_invoice_id' => $invoice_id]);
            $data['invoice_detail']->authorized_signatory = 0;
            $data['invoice_detail']->final_amount = number_format(round($data['invoice_detail']->price_with_gst), $data['invoice_detail']->currency_decimal);
            $data['invoice_detail']->amount_in_word = numberToWords($data['invoice_detail']->final_amount, $data['invoice_detail']->currency_basic_unit, $data['invoice_detail']->currency_fractional_unit);
            $data['invoice_detail']->authorized_signatory = 0;
            $file_name = "ProformaInvoice-" . $data['invoice_detail']->proforma_invoice_id . ".pdf";
            $save_pdf =  $this->generate_pdf('template/invoice_pdf', $data, 'aws_save', $file_name);
            $save_pdf = $this->report_upload_aws($save_pdf, $file_name);
            $file_array = array(
                'file_path'     => $save_pdf['aws_path'],
            );
            $update_file = $this->invoice->update_data('invoice_proforma', $file_array, ['proforma_invoice_id' => $data['invoice_detail']->proforma_invoice_id]);
            $query = $this->db->select('invoice_status_name')
                ->join('invoice_status', 'invoice_proforma_invoice_status_id = invoice_status_id')
                ->where('proforma_invoice_id', $invoice_id)
                ->get('invoice_proforma');
            if ($query->num_rows() > 0) {
                $query_data = $query->row_array();
                $old_status = $query_data['invoice_status_name'];
            } else {
                $old_status = '';
            }
            $logdetails = array(
                'module'    => 'Invoice',
                'operation' => 'save_test',
                'source_module' => 'Invoice_Controller',
                'uidnr_admin'   => $this->admin_id(),
                'log_activity_on'   => date('Y-m-d H:i:s'),
                'action_message'    => 'Dynamic pricing done',
                'invoice_id'     => $invoice_id,
                'new_status'    => 'Test added for the proforma',
                'old_status'    => $old_status
            );
            $this->db->insert('invoice_activity_log', $logdetails);
            echo json_encode(["message" => "Data saved successfully.", "status" => 1]);
        } else {
            echo json_encode(["message" => "Something went wrong!.", "status" => 0]);
        }
    }

    // Not in use now
    public function signoff_invoice()
    {
        $id = $this->input->post('proforma_invoice_id');
        $data['invoice_detail'] = $this->invoice->get_invoice_details($id);
        $data['test_details'] = [];
        $data['package_details'] = [];
        $data['protocol_details'] = [];
        $data['test_details'] = $this->invoice->get_test($id);
        $data['package_details'] = $this->invoice->get_package($id);
        $data['protocol_details'] = $this->invoice->get_protocol($id);
        $data['invoice_detail']->total_amount;
        $country_id = $this->invoice->get_country_id();
        $state = $data['invoice_detail']->state;
        $IGST = $this->invoice->get_fields_by_id("sys_configuration", "cfg_Value", "IGST", "cfg_Name")[0]['cfg_Value'];
        $SGST = $this->invoice->get_fields_by_id("sys_configuration", "cfg_Value", "SGST", "cfg_Name")[0]['cfg_Value'];
        $CGST = $this->invoice->get_fields_by_id("sys_configuration", "cfg_Value", "CGST", "cfg_Name")[0]['cfg_Value'];
        $UTGST = $this->invoice->get_fields_by_id("sys_configuration", "cfg_Value", "UTGST", "cfg_Name")[0]['cfg_Value'];
        $VAT = $this->invoice->get_fields_by_id("sys_configuration", "cfg_Value", "VAT", "cfg_Name")[0]['cfg_Value'];
        // Get dynamic fields value
        $dynamic_value_query = $this->db->select('product_custom_fields')
            ->where('trf_id', $data['invoice_detail']->trf_id)
            ->get('trf_registration');
        // echo $this->db->last_query(); die;
        if ($dynamic_value_query->num_rows() > 0) {
            $dynamic_value = $dynamic_value_query->result_array()[0]['product_custom_fields'];
            $data['fields'] = json_decode($dynamic_value);
        } else {
            $data['fields'] = [];
        }
        $templateVars = [];
        if ($data['invoice_detail']->total_amount > 0) {
            if (($data['invoice_detail']->country_id == 1) && ($data['invoice_detail']->cust_type != 'SEZ Unit' && $data['invoice_detail']->cust_type != 'SEZ Development')) {
                if ($this->invoice->gstCalculation($state, 'IGST', $data['invoice_detail']->total_amount, $IGST) > 0) {
                    $gst = $this->invoice->gstCalculation($state, 'IGST', $data['invoice_detail']->total_amount, $IGST);
                    $templateVars['GST'] = ('IGST @ ' . $IGST . '% ' . number_format($gst, $data['invoice_detail']->currency_decimal));
                }

                if ($this->invoice->gstCalculation($state, 'SGST', $data['invoice_detail']->total_amount, $SGST) > 0) {
                    $gst = $this->invoice->gstCalculation($state, 'SGST', $data['invoice_detail']->total_amount, $SGST);
                    $sgst = $gst;
                }
                if ($this->invoice->gstCalculation($state, 'CGST', $data['invoice_detail']->total_amount, $CGST) > 0) {
                    $gst += $this->invoice->gstCalculation($state, 'CGST', $data['invoice_detail']->total_amount, $CGST);
                    $templateVars['GST'] = 'SGST @ ' . $SGST . '% &nbsp; ' . number_format($this->invoice->gstCalculation($state, 'CGST', $data['invoice_detail']->total_amount, $SGST), $data['invoice_detail']->currency_decimal) . '<br/>' . 'CGST @ ' . $CGST . '%  &nbsp; ' . number_format($this->invoice->gstCalculation($state, 'CGST', $data['invoice_detail']->total_amount, $CGST), $data['invoice_detail']->currency_decimal);
                }
                if ($this->invoice->gstCalculation($state, 'UTGST', $data['invoice_detail']->total_amount, $UTGST)) {
                    $gst = $this->invoice->gstCalculation($state, 'UTGST', $data['invoice_detail']->total_amount, $UTGST);
                    $templateVars['GST'] = ('UTGST @ ' . $UTGST . '% ' . number_format($gst, $data['invoice_detail']->currency_decimal));
                }
            } else {
                if ($this->invoice->gstCalculation($state, 'VAT', $data['invoice_detail']->total_amount, $VAT) > 0) {
                    $gst = $this->invoice->gstCalculation($state, 'VAT', $data['invoice_detail']->total_amount, $VAT);
                    $templateVars['VAT'] = 'VAT @ ' . $VAT . '% &nbsp; ' . number_format($gst, $data['invoice_detail']->currency_decimal);
                }
            }
        } else {
            echo json_encode(["message" => "Total amount can not be zero.", "status" => 0]);
            exit;
        }
        $data['sample_desc'] = $data['invoice_detail']->sample_desc;
        $data['invoice_detail']->gst = $templateVars;
        $data['invoice_detail']->price_with_gst = $data['invoice_detail']->total_amount + $gst;
        $this->invoice->update_data('invoice_proforma',['price_with_gst' => $gst],['proforma_invoice_id' => $id]);
        $data['invoice_detail']->authorized_signatory = 0;
        $data['invoice_detail']->final_amount = number_format(round($data['invoice_detail']->price_with_gst), $data['invoice_detail']->currency_decimal);
        $data['invoice_detail']->amount_in_word = numberToWords($data['invoice_detail']->final_amount, $data['invoice_detail']->currency_basic_unit, $data['invoice_detail']->currency_fractional_unit);
        // Get style no value 
        $product_id = $data['invoice_detail']->trf_product;
        $trf_id = $data['invoice_detail']->trf_id;
        // Check dynamic fields id
        // $dynamic_fields = $this->invoice->check_fields($product_id);
        // $values = [];
        // foreach($dynamic_fields as $d_fields){
        //     $field_id = $d_fields['registration_fields_id'];
        //     // Get dynamic field value
        //     $value = $this->invoice->get_dynamic_field_value($field_id,$product_id,$trf_id);
        //     $dy_field['value'] = $value['trf_registrationfield_fields_values'];
        //     $dy_field['name'] = $name = $d_fields['registration_fields_name'];
        //     $values[] = $dy_field;
        // } 
        // $custom_field_query = $this->invoice->get_fields_by_id("sample_registration","product_custom_fields",$data['invoice_detail']->proforma_invoice_sample_reg_id,"sample_reg_id");
        // if($custom_field_query->num_rows() > 0){
        //     $custom_field = $custom_field_query->result_array();
        //     $values = json_decode($custom_field);
        // } else {
        //     $values = "";
        // }
        // $data['fields'] = $values;
        $file_name = "ProformaInvoice-" . $data['invoice_detail']->proforma_invoice_id . ".pdf";
        $save_pdf =  $this->generate_pdf('template/invoice_pdf', $data, 'aws_save', $file_name);
        $save_pdf = $this->report_upload_aws($save_pdf, $file_name);
        $file_array = array(
            'file_path'     => $save_pdf['aws_path'],
        );
        $update_file = $this->invoice->update_data('invoice_proforma', $file_array, ['proforma_invoice_id' => $data['invoice_detail']->proforma_invoice_id]);
        if ($update_file) {
            $logdetails = array(
                'module'    => 'Invoice',
                'operation' => 'signoff_invoice',
                'source_module' => 'Invoice_Controller',
                'uidnr_admin'   => $this->admin_id(),
                'log_activity_on'   => date('Y-m-d H:i:s'),
                'action_message'    => 'Invoice signed off',
                'invoice_id'     => $data['invoice_detail']->proforma_invoice_id,
                'new_status'    => ''
            );
            $this->db->insert('invoice_activity_log', $logdetails);
            $this->session->set_flashdata('success', 'Proforma Invoice Generated');
            echo true;
        } else {
            $this->session->set_flashdata('false', 'Something went wrong!.');
            echo false;
        }
    }
    // Not in use now ends here

    public function check_trf_type()
    {
        $sample_reg_id = $this->input->post('sample_reg_id');
        $data = $this->invoice->check_trf_type($sample_reg_id);
        echo json_encode($data);
    }

    public function get_proforma_invoice($proforma_invoice_id)
    {
        $data['invoice_detail'] = $this->invoice->get_invoice_details($proforma_invoice_id);
        $data['test_details'] = [];
        $data['package_details'] = [];
        $data['protocol_details'] = [];
        $data['test_details'] = $this->invoice->get_test($proforma_invoice_id);
        $data['package_details'] = $this->invoice->get_package($proforma_invoice_id);
        $data['protocol_details'] = $this->invoice->get_protocol($proforma_invoice_id);
        // echo "<pre>";
        // print_r($data);
        // exit;

        $data['invoice_detail']->total_amount;
        // new surcharge
        $data['invoice_detail']->amount = $data['invoice_detail']->total_amount;
        if($data['invoice_detail']->surcharge_amount == ''){
            $data['invoice_detail']->total_amount = $data['invoice_detail']->total_amount;
        }else{
            $data['invoice_detail']->total_amount = $data['invoice_detail']->surcharge_amount;
        }

        $data['invoice_detail']->surcharge_percentage = $data['invoice_detail']->surcharge_percentage;
        $country_id = $this->invoice->get_country_id();
        $state = $data['invoice_detail']->state;
        $IGST = $this->invoice->get_fields_by_id("sys_configuration", "cfg_Value", "IGST", "cfg_Name")[0]['cfg_Value'];
        $SGST = $this->invoice->get_fields_by_id("sys_configuration", "cfg_Value", "SGST", "cfg_Name")[0]['cfg_Value'];
        $CGST = $this->invoice->get_fields_by_id("sys_configuration", "cfg_Value", "CGST", "cfg_Name")[0]['cfg_Value'];
        $UTGST = $this->invoice->get_fields_by_id("sys_configuration", "cfg_Value", "UTGST", "cfg_Name")[0]['cfg_Value'];
        $VAT = $this->invoice->get_fields_by_id("sys_configuration", "cfg_Value", "VAT", "cfg_Name")[0]['cfg_Value'];
        // Get dynamic fields value
        $dynamic_value_query = $this->db->select('product_custom_fields')
            ->where('trf_id', $data['invoice_detail']->trf_id)
            ->get('trf_registration');
        if ($dynamic_value_query->num_rows() > 0) {
            $dynamic_value = $dynamic_value_query->result_array()[0]['product_custom_fields'];
            $data['fields'] = json_decode($dynamic_value);
        } else {
            $data['fields'] = [];
        }

        $templateVars = [];
        if ($data['invoice_detail']->total_amount > 0) {
            if (($data['invoice_detail']->country_id == 1) && ($data['invoice_detail']->cust_type != 'SEZ Unit' && $data['invoice_detail']->cust_type != 'SEZ Development')) {
                if ($this->invoice->gstCalculation($state, 'IGST', $data['invoice_detail']->total_amount, $IGST) > 0) {
                    $gst = $this->invoice->gstCalculation($state, 'IGST', $data['invoice_detail']->total_amount, $IGST);
                    $templateVars['GST'] = ('IGST @ ' . $IGST . '% ' . number_format($gst, $data['invoice_detail']->currency_decimal));
                }

                if ($this->invoice->gstCalculation($state, 'SGST', $data['invoice_detail']->total_amount, $SGST) > 0) {
                    $gst = $this->invoice->gstCalculation($state, 'SGST', $data['invoice_detail']->total_amount, $SGST);
                    $sgst = $gst;
                }
                if ($this->invoice->gstCalculation($state, 'CGST', $data['invoice_detail']->total_amount, $CGST) > 0) {
                    $gst += $this->invoice->gstCalculation($state, 'CGST', $data['invoice_detail']->total_amount, $CGST);
                    $templateVars['GST'] = 'SGST @ ' . $SGST . '% &nbsp; ' . number_format($this->invoice->gstCalculation($state, 'CGST', $data['invoice_detail']->total_amount, $SGST), $data['invoice_detail']->currency_decimal) . '<br/>' . 'CGST @ ' . $CGST . '%  &nbsp; ' . number_format($this->invoice->gstCalculation($state, 'CGST', $data['invoice_detail']->total_amount, $CGST), $data['invoice_detail']->currency_decimal);
                }
                if ($this->invoice->gstCalculation($state, 'UTGST', $data['invoice_detail']->total_amount, $UTGST)) {
                    $gst = $this->invoice->gstCalculation($state, 'UTGST', $data['invoice_detail']->total_amount, $UTGST);
                    $templateVars['GST'] = ('UTGST @ ' . $UTGST . '% ' . number_format($gst, $data['invoice_detail']->currency_decimal));
                }
            } else {
                $gst = 0;
                $templateVars = [];
                // if ($this->invoice->gstCalculation($state, 'VAT', $data['invoice_detail']->total_amount, $VAT) > 0) {
                //     $gst = $this->invoice->gstCalculation($state, 'VAT', $data['invoice_detail']->total_amount, $VAT);
                //     $templateVars['VAT'] = 'VAT @ ' . $VAT . '% &nbsp; ' . number_format($gst, $data['invoice_detail']->currency_decimal);
                // }
            }
        }
        $data['sample_desc'] = $data['invoice_detail']->sample_desc;
        $data['invoice_detail']->gst = $templateVars;
        $data['invoice_detail']->price_with_gst = $data['invoice_detail']->total_amount + $gst;
        $this->invoice->update_data('invoice_proforma',['price_with_gst' => $gst],['proforma_invoice_id' => $proforma_invoice_id]);
        $data['invoice_detail']->authorized_signatory = 0;
        $data['invoice_detail']->final_amount = number_format(round($data['invoice_detail']->price_with_gst), $data['invoice_detail']->currency_decimal);
        $data['invoice_detail']->amount_in_word = numberToWords($data['invoice_detail']->final_amount, $data['invoice_detail']->currency_basic_unit, $data['invoice_detail']->currency_fractional_unit);
        // $data = $this->invoice->get_fields_by_id("invoice_proforma","file_path",$proforma_invoice_id,"proforma_invoice_id")[0]['file_path'];
        // Get distinct type of parameters, added by Saurabh on 31-05-2022
        // pre_r($data); die;
        $data['distinct_type'] = $this->db->select('invoice_quote_type, invoice_quote_id, invoice_protocol_id, invoice_package_id, invoice_work_id')->where('invoice_id', $proforma_invoice_id)->get('invoice_dynamic_details')->row_array();
        // echo '<pre>'; print_r($data); die;
        $invoice = $this->load->view('template/invoice_pdf', $data, true);
        $this->load->library('M_pdf');
        $this->m_pdf->pdf->charset_in = 'UTF-8';
        $this->m_pdf->curlAllowUnsafeSslRequests = true;
        $this->m_pdf->pdf->setAutoTopMargin = 'stretch';
        $this->m_pdf->pdf->setAutoBottomMargin = 'stretch';
        $this->m_pdf->pdf->WriteHTML($invoice);
        $this->m_pdf->pdf->Output('INVOICE.pdf', 'I');
    }

    public function accept_proforma_invoice()
    {
        $proforma_invoice_id = $this->input->post('proforma_invoice_id');
        $approval_status = $this->input->post('approval_status');
        if (!empty($approval_status)) {
            if ($approval_status == 1 || $approval_status == 3) {
                $this->db->trans_begin();
                $update = $this->invoice->update_data("invoice_proforma", $data = array("invoice_proforma_invoice_status_id" => 4), ["proforma_invoice_id" => $proforma_invoice_id]);
                $data['invoice_detail'] = $this->invoice->get_invoice_details($proforma_invoice_id);
                $data['test_details'] = [];
                $data['package_details'] = [];
                $data['protocol_details'] = [];
                $data['test_details'] = $this->invoice->get_test($proforma_invoice_id);
                $data['package_details'] = $this->invoice->get_package($proforma_invoice_id);
                $data['protocol_details'] = $this->invoice->get_protocol($proforma_invoice_id);
                $data['invoice_detail']->total_amount;
                // new surcharge
                $data['invoice_detail']->amount = $data['invoice_detail']->total_amount;
                if($data['invoice_detail']->surcharge_amount == ''){
                    $data['invoice_detail']->total_amount = $data['invoice_detail']->total_amount;
                }else{
                    $data['invoice_detail']->total_amount = $data['invoice_detail']->surcharge_amount;
                }
        
                $country_id = $this->invoice->get_country_id();
                $state = $data['invoice_detail']->state;
                $IGST = $this->invoice->get_fields_by_id("sys_configuration", "cfg_Value", "IGST", "cfg_Name")[0]['cfg_Value'];
                $SGST = $this->invoice->get_fields_by_id("sys_configuration", "cfg_Value", "SGST", "cfg_Name")[0]['cfg_Value'];
                $CGST = $this->invoice->get_fields_by_id("sys_configuration", "cfg_Value", "CGST", "cfg_Name")[0]['cfg_Value'];
                $UTGST = $this->invoice->get_fields_by_id("sys_configuration", "cfg_Value", "UTGST", "cfg_Name")[0]['cfg_Value'];
                $VAT = $this->invoice->get_fields_by_id("sys_configuration", "cfg_Value", "VAT", "cfg_Name")[0]['cfg_Value'];
                $templateVars = [];
                if ($data['invoice_detail']->total_amount > 0) {
                    if (($data['invoice_detail']->country_id == 1) && ($data['invoice_detail']->cust_type != 'SEZ Unit' && $data['invoice_detail']->cust_type != 'SEZ Development')) {
                        if ($this->invoice->gstCalculation($state, 'IGST', $data['invoice_detail']->total_amount, $IGST) > 0) {
                            $gst = $this->invoice->gstCalculation($state, 'IGST', $data['invoice_detail']->total_amount, $IGST);
                            $templateVars['GST'] = ('IGST @ ' . $IGST . '% ' . number_format($gst, $data['invoice_detail']->currency_decimal));
                        }

                        if ($this->invoice->gstCalculation($state, 'SGST', $data['invoice_detail']->total_amount, $SGST) > 0) {
                            $gst = $this->invoice->gstCalculation($state, 'SGST', $data['invoice_detail']->total_amount, $SGST);
                            $sgst = $gst;
                        }
                        if ($this->invoice->gstCalculation($state, 'CGST', $data['invoice_detail']->total_amount, $CGST) > 0) {
                            $gst += $this->invoice->gstCalculation($state, 'CGST', $data['invoice_detail']->total_amount, $CGST);
                            $templateVars['GST'] = 'SGST @ ' . $SGST . '% &nbsp; ' . number_format($this->invoice->gstCalculation($state, 'CGST', $data['invoice_detail']->total_amount, $SGST), $data['invoice_detail']->currency_decimal) . '<br/>' . 'CGST @ ' . $CGST . '%  &nbsp; ' . number_format($this->invoice->gstCalculation($state, 'CGST', $data['invoice_detail']->total_amount, $CGST), $data['invoice_detail']->currency_decimal);
                        }
                        if ($this->invoice->gstCalculation($state, 'UTGST', $data['invoice_detail']->total_amount, $UTGST)) {
                            $gst = $this->invoice->gstCalculation($state, 'UTGST', $data['invoice_detail']->total_amount, $UTGST);
                            $templateVars['GST'] = ('UTGST @ ' . $UTGST . '% ' . number_format($gst, $data['invoice_detail']->currency_decimal));
                        }
                    } else {
                        // if ($this->invoice->gstCalculation($state, 'VAT', $data['invoice_detail']->total_amount, $VAT) > 0) {
                        //     $gst = $this->invoice->gstCalculation($state, 'VAT', $data['invoice_detail']->total_amount, $VAT);
                        //     $templateVars['VAT'] = 'VAT @ ' . $VAT . '% &nbsp; ' . number_format($gst, $data['invoice_detail']->currency_decimal);
                        // }
                        $gst = 0;
                $templateVars = [];
                    }
                } else {
                    echo json_encode(["message" => "Total amount can not be zero.", "status" => 0]);
                    exit;
                }
                $data['sample_desc'] = $data['invoice_detail']->sample_desc;
                $data['invoice_detail']->gst = $templateVars;
                $data['invoice_detail']->price_with_gst = $data['invoice_detail']->total_amount + $gst;
                $this->invoice->update_data('invoice_proforma',['price_with_gst' => $gst],['proforma_invoice_id' => $proforma_invoice_id]);
                $data['invoice_detail']->authorized_signatory = 0;
                $data['invoice_detail']->final_amount = number_format(round($data['invoice_detail']->price_with_gst), $data['invoice_detail']->currency_decimal);
                $data['invoice_detail']->amount_in_word = numberToWords($data['invoice_detail']->final_amount, $data['invoice_detail']->currency_basic_unit, $data['invoice_detail']->currency_fractional_unit);
                $data['invoice_detail']->authorized_signatory = 1;
                $data['invoice_detail']->authorized_name = $this->session->userdata('user_data')->username;
                $data['invoice_detail']->authorized_designation = $this->session->userdata('user_data')->role_name;
                $data['invoice_detail']->authorized_signature = $this->getS3Url1($this->invoice->get_fields_by_id('admin_signature', 'sign_path', $this->admin_id(), "admin_id")[0]['sign_path']);
                $file_name = "ProformaInvoice-" . $data['invoice_detail']->proforma_invoice_id . ".pdf";
                $save_pdf =  $this->generate_pdf('template/invoice_pdf', $data, 'aws_save', $file_name);
                $save_pdf = $this->report_upload_aws($save_pdf, $file_name);
                $file_array = array(
                    'file_path'     => $save_pdf['aws_path'],
                );
                $update_file = $this->invoice->update_data('invoice_proforma', $file_array, ['proforma_invoice_id' => $data['invoice_detail']->proforma_invoice_id]);
                if ($this->db->trans_status() === FALSE) {
                    $this->db->trans_rollback();
                    echo json_encode(["message" => "Something went wrong!.", "status" => 0]);
                } else {
                    $this->db->trans_commit();
                    // Get old status
                    $query = $this->db->select('invoice_status_name')
                        ->join('invoice_status', 'invoice_proforma_invoice_status_id = invoice_status_id')
                        ->where('proforma_invoice_id', $proforma_invoice_id)
                        ->get('invoice_proforma');
                    if ($query->num_rows() > 0) {
                        $query_data = $query->row_array();
                        $old_status = $query_data['invoice_status_name'];
                    } else {
                        $old_status = '';
                    }
                    $logdetails = array(
                        'module'    => 'Invoice',
                        'operation' => 'accept_proforma_invoice',
                        'source_module' => 'Invoice_Controller',
                        'uidnr_admin'   => $this->admin_id(),
                        'log_activity_on'   => date('Y-m-d H:i:s'),
                        'action_message'    => 'Proforma Invoice Accepted',
                        'invoice_id'     => $data['invoice_detail']->proforma_invoice_id,
                        'new_status'    => 'Proforma Approved',
                        'old_status'    => $old_status
                    );
                    $this->db->insert('invoice_activity_log', $logdetails);
                    echo json_encode(["status" => 1, "message" => "Data saved successfuly"]);
                }
            } else {
                // Get contact person id from the TRF
                $this->db->select('email, gc_no, file_path');
                $this->db->join('sample_registration', 'proforma_invoice_sample_reg_id = sample_reg_id');
                $this->db->join('trf_registration', 'trf_id = trf_registration_id');
                $this->db->join('contacts', 'contact_id = trf_invoice_to_contact');
                $this->db->where('proforma_invoice_id', $proforma_invoice_id);
                $query = $this->db->get('invoice_proforma');
                if ($query->num_rows() > 0) {
                    $result = $query->row_array();
                }
                // Set content for email
                $subject = 'Approval of proforma invoice for sample number ' . $result['gc_no'];
                $email_body = '<table>';
                $email_body .= '<tr><td>Dear Sir/Madam,</td></tr>';
                $email_body .= '<tr><td>Kindly find Proforma Invoice attached for your sample registered ' . $result['gc_no'] . ',  please accept proforma invoice by clicking following link to process sample.</td></tr>';
                $email_body .= '<tr><td><a href="' . base_url('ProformaApproval/accept_proforma/'.base64_encode($proforma_invoice_id)) . '">Accept</a>|<a href="' . base_url('ProformaApproval/reject_proforma/'.base64_encode($proforma_invoice_id)) . '">Reject</a></td></tr>';
                $email_body .= '</table>';
                if (INSTANCE_TYPE == "development") {
                    $to = array("developer.cps01@basilrl.com");
                    $cc = array("developer.cps08@basilrl.com");
                    $bcc = array('developer.cps01@basilrl.com');
                } else {
                    $to = $result['email'];
                }
                $file_path = $result['file_path'];
                $result = send_proforma_mail($to, $from = NULL, $cc, $bcc, $email_body, $sub = "Proforma Invoice For Approval", $attachment_file = NULL, $file_path, $report = false);
                if ($result) {
                    // Get old status
                    $query = $this->db->select('invoice_status_name')
                        ->join('invoice_status', 'invoice_proforma_invoice_status_id = invoice_status_id')
                        ->where('proforma_invoice_id', $proforma_invoice_id)
                        ->get('invoice_proforma');
                    if ($query->num_rows() > 0) {
                        $query_data = $query->row_array();
                        $old_status = $query_data['invoice_status_name'];
                    } else {
                        $old_status = '';
                    }
                    $logdetails = array(
                        'module'    => 'Invoice',
                        'operation' => 'accept_proforma_invoice',
                        'source_module' => 'Invoice_Controller',
                        'uidnr_admin'   => $this->admin_id(),
                        'log_activity_on'   => date('Y-m-d H:i:s'),
                        'action_message'    => 'Proforma sent for approval to client',
                        'invoice_id'     => $proforma_invoice_id,
                        'new_status'    => 'Proforma Approved',
                        'old_status'    => $old_status
                    );
                    $this->db->insert('invoice_activity_log', $logdetails);
                    echo json_encode(['status' => 1, 'message' => 'Proforma sent for approval to client']);
                } else {
                    echo json_encode(["message" => "Something went wrong!.", "status" => 0]);
                }
            }
        } else {
            echo json_encode(['status' => 0, 'message' => 'Please select approval status']);
        }
    }


    public function reject_proforma_invoice()
    {
        $proforma_invoice_id = $this->input->post('proforma_invoice_id');
        $update = $this->invoice->update_data("invoice_proforma", $data = array("invoice_proforma_invoice_status_id" => 10), ["proforma_invoice_id" => $proforma_invoice_id]);
        if ($update) {
            // Get old status
            $query = $this->db->select('invoice_status_name')
                ->join('invoice_status', 'invoice_proforma_invoice_status_id = invoice_status_id')
                ->where('proforma_invoice_id', $proforma_invoice_id)
                ->get('invoice_proforma');
            if ($query->num_rows() > 0) {
                $query_data = $query->row_array();
                $old_status = $query_data['invoice_status_name'];
            } else {
                $old_status = '';
            }
            $logdetails = array(
                'module'    => 'Invoice',
                'operation' => 'reject_proforma_invoice',
                'source_module' => 'Invoice_Controller',
                'uidnr_admin'   => $this->admin_id(),
                'log_activity_on'   => date('Y-m-d H:i:s'),
                'action_message'    => 'Proforma Invoice Rejected',
                'invoice_id'     => $proforma_invoice_id,
                'new_status'    => 'Rejected',
                'old_status'    => $old_status
            );
            $this->db->insert('invoice_activity_log', $logdetails);
            echo json_encode(["status" => 1, "message" => "Data saved successfuly"]);
        } else {
            echo json_encode(["message" => "Something went wrong!.", "status" => 0]);
        }
    }

    public function update_proforma_invoice_status()
    {
        $proforma_invoice_id = $this->input->post('proforma_invoice_id');
        $comment = $this->input->post('comment');
        $status = $this->input->post('status');
        if ($status == 1) {
            $invoice_status = 14;
            $acc_status = "Accepted by client";
        } else {
            $invoice_status = 15;
            $acc_status = 'Rejected by client';
        }
        $update = $this->invoice->update_data("invoice_proforma", ["invoice_proforma_invoice_status_id" => $invoice_status, "comment" => $comment], ['proforma_invoice_id' => $proforma_invoice_id]);
        if ($update) {
            // Get old status
            $query = $this->db->select('invoice_status_name')
                ->join('invoice_status', 'invoice_proforma_invoice_status_id = invoice_status_id')
                ->where('proforma_invoice_id', $proforma_invoice_id)
                ->get('invoice_proforma');
            if ($query->num_rows() > 0) {
                $query_data = $query->row_array();
                $old_status = $query_data['invoice_status_name'];
            } else {
                $old_status = '';
            }
            $logdetails = array(
                'module'    => 'Invoice',
                'operation' => 'update_proforma_invoice_status',
                'source_module' => 'Invoice_Controller',
                'uidnr_admin'   => $this->admin_id(),
                'log_activity_on'   => date('Y-m-d H:i:s'),
                'action_message'    => 'Proforma Invoice Accepted',
                'invoice_id'     => $proforma_invoice_id,
                'new_status'    => $acc_status,
                'old_status'    => $old_status
            );
            $this->db->insert('invoice_activity_log', $logdetails);
            echo json_encode(["status" => 1, "message" => "Data saved successfuly"]);
        } else {
            echo json_encode(["message" => "Something went wrong!.", "status" => 0]);
        }
    }

    public function get_test_price()
    {
        $invoice_id = $this->input->post('proforma_invoice_id');
        $sample_reg_id = $this->input->post('sample_reg_id');
        $dynamic_price = $this->invoice->get_test_price($invoice_id, $sample_reg_id);
        $data = [];
        // foreach ($dynamic_price as $key => $test_price) {
        //     if ($test_price['invoice_quote_id'] > '0') {
        //         if($test_price['invoice_quote_type'] == 'Package'){
        //             $data['package'][$key] = $test_price;
        //         } elseif($test_price['invoice_quote_type'] == 'Protocol'){
        //             $data['protocol'][$key] = $test_price;
        //         } else {
        //             $data['test_data'][$key] = $test_price;
        //         }
        //     } elseif ($test_price['invoice_package_id'] > '0') {
        //         $data['package'][$key] = $test_price;
        //     } elseif ($test_price['invoice_protocol_id'] > '0') {
        //         $data['protocol'][$key] = $test_price;
        //     } else {
        //         $data['test_data'][$key] = $test_price;
        //     }
        // }
        $data['test_data'] = [];
        $data['package'] = [];
        $data['protocol'] = [];
        $data['test_data'] = $this->invoice->get_test($invoice_id);
        $data['package'] = $this->invoice->get_package($invoice_id);
        $data['protocol'] = $this->invoice->get_protocol($invoice_id);
        // Updated by saurabh on 05-08-2021
        $data['remark'] = $this->invoice->get_remark($invoice_id);
        echo json_encode($data);
    }

    public function update_test_price()
    {
        $record = $this->input->post();
        $checkUser = $this->session->userdata('user_data');
        $user = $checkUser->uidnr_admin;

        $invoice_id = $record['proforma_invoice_id'];
        $sample_reg_id = $record['sample_reg_id'];
        $trf_quote_id = $record['trf_quote_id'];
        $dynamic_data = $record['test'];
        $zerovalidation = false;
        $data = array();
        foreach ($dynamic_data as $key => $value) {
            // Check if parameter is of package
            if ($value['invoice_package_id'] > 0) {
                if ($key == 0) {
                    if ($value['applicable_charge'] <= 0) {
                        $zerovalidation = true;
                        break;
                    }
                }
                $value['sample_registration_id'] = $sample_reg_id;
                $value['invoice_id'] = $invoice_id;
                $value['created_by'] = $this->session->userdata('user_data')->uidnr_admin;
                $value['created_on'] = date('Y-m-d H:i:s');
                unset($value['invoice_dyn_id']);
                $data[$key] = $value;
            } elseif ($value['invoice_protocol_id'] > 0) {
                if ($key == 0) {
                    if ($value['applicable_charge'] <= 0) {
                        $zerovalidation = true;
                        break;
                    }
                }
                $value['sample_registration_id'] = $sample_reg_id;
                $value['invoice_id'] = $invoice_id;
                $value['created_by'] = $this->session->userdata('user_data')->uidnr_admin;
                $value['created_on'] = date('Y-m-d H:i:s');
                unset($value['invoice_dyn_id']);
                $data[$key] = $value;
            } elseif ($value['invoice_quote_type'] == "Test") {
                if ($value['applicable_charge'] <= 0) {
                    $zerovalidation = true;
                    break;
                } else {
                    $value['sample_registration_id'] = $sample_reg_id;
                    $value['invoice_id'] = $invoice_id;
                    $value['created_by'] = $this->session->userdata('user_data')->uidnr_admin;
                    $value['created_on'] = date('Y-m-d H:i:s');
                    unset($value['invoice_dyn_id']);
                    $data[$key] = $value;
                }
            } elseif ($value['invoice_quote_type'] == "Package") {
                if ($key == 0) {
                    if ($value['applicable_charge'] <= 0) {
                            $zerovalidation = true;
                            break;
                        }
                    } 
                    $value['sample_registration_id'] = $sample_reg_id;
                    $value['invoice_id'] = $invoice_id;
                    $value['created_by'] = $this->session->userdata('user_data')->uidnr_admin;
                    $value['created_on'] = date('Y-m-d H:i:s');
                    unset($value['invoice_dyn_id']);
                    $data[$key] = $value;
            } elseif ($value['invoice_quote_type'] == "Protocol") {
                if ($key == 0) {
                    if ($value['applicable_charge'] <= 0) {
                            $zerovalidation = true;
                            break;
                        }
                    } 
                    $value['sample_registration_id'] = $sample_reg_id;
                    $value['invoice_id'] = $invoice_id;
                    $value['created_by'] = $this->session->userdata('user_data')->uidnr_admin;
                    $value['created_on'] = date('Y-m-d H:i:s');
                    unset($value['invoice_dyn_id']);
                    $data[$key] = $value;
            } elseif (empty($value['invoice_quote_type'])) {
                if ($value['applicable_charge'] <= 0) {
                    $zerovalidation = true;
                    break;
                } else {
                    $value['sample_registration_id'] = $sample_reg_id;
                    $value['invoice_id'] = $invoice_id;
                    $value['created_by'] = $this->session->userdata('user_data')->uidnr_admin;
                    $value['created_on'] = date('Y-m-d H:i:s');
                    unset($value['invoice_dyn_id']);
                    $data[$key] = $value;
                }
            }
        }
        if ($zerovalidation) {
            echo json_encode(["message" => "Rate Per Test can not be zero.", "status" => 0]);
            exit;
        } else {
            // Delete old records
            $this->db->delete('invoice_dynamic_details', ['invoice_id' => $invoice_id, 'sample_registration_id' => $sample_reg_id]);
            $update = $this->invoice->update_test($data);
        }
        $this->db->trans_start();
        $input_array = array(
            'total_amount'      => $record['total_amount'],
            'show_discount'     => $record['show_discount'],
            'invoice_remark'    => $record['invoice_remark'], //updated by saurabh on 05-08-2021
            'surcharge_percentage'    => $record['surcharge'], // new surcharge
            'surcharge_amount'    => $record['surchargeTotal'] // new surcharge
        );
        $update = $this->invoice->update_data('invoice_proforma', $input_array, ['proforma_invoice_id' => $invoice_id]);
        $this->db->trans_complete();
        if ($update) {
            $data['invoice_detail'] = $this->invoice->get_invoice_details($invoice_id);
            $data['test_details'] = [];
            $data['package_details'] = [];
            $data['protocol_details'] = [];
            $data['test_details'] = $this->invoice->get_test($invoice_id);
            $data['package_details'] = $this->invoice->get_package($invoice_id);
            $data['protocol_details'] = $this->invoice->get_protocol($invoice_id);
            // Get country id of the user
            $country_id = $this->invoice->get_country_id();
            $state = $data['invoice_detail']->state;
            $IGST = $this->invoice->get_fields_by_id("sys_configuration", "cfg_Value", "IGST", "cfg_Name")[0]['cfg_Value'];
            $SGST = $this->invoice->get_fields_by_id("sys_configuration", "cfg_Value", "SGST", "cfg_Name")[0]['cfg_Value'];
            $CGST = $this->invoice->get_fields_by_id("sys_configuration", "cfg_Value", "CGST", "cfg_Name")[0]['cfg_Value'];
            $UTGST = $this->invoice->get_fields_by_id("sys_configuration", "cfg_Value", "UTGST", "cfg_Name")[0]['cfg_Value'];
            $VAT = $this->invoice->get_fields_by_id("sys_configuration", "cfg_Value", "VAT", "cfg_Name")[0]['cfg_Value'];
            // Get dynamic fields value
            $dynamic_value_query = $this->db->select('product_custom_fields')
                ->where('trf_id', $data['invoice_detail']->trf_id)
                ->get('trf_registration');
            if ($dynamic_value_query->num_rows() > 0) {
                $dynamic_value = $dynamic_value_query->result_array()[0]['product_custom_fields'];
                $data['fields'] = json_decode($dynamic_value);
            } else {
                $data['fields'] = [];
            }
            $templateVars = [];
            if ($data['invoice_detail']->total_amount > 0) {
                $data['invoice_detail']->total_amount;
                // new surcharge
                $data['invoice_detail']->amount = $data['invoice_detail']->total_amount;
                if($data['invoice_detail']->surcharge_amount == ''){
                    $data['invoice_detail']->total_amount = $data['invoice_detail']->total_amount;
                }else{
                    $data['invoice_detail']->total_amount = $data['invoice_detail']->surcharge_amount;
                }
                // end
                if (($data['invoice_detail']->country_id == 1) && ($data['invoice_detail']->cust_type != 'SEZ Unit' && $data['invoice_detail']->cust_type != 'SEZ Development')) {
                    if ($this->invoice->gstCalculation($state, 'IGST', $data['invoice_detail']->total_amount, $IGST) > 0) {
                        $gst = $this->invoice->gstCalculation($state, 'IGST', $data['invoice_detail']->total_amount, $IGST);
                        $templateVars['GST'] = ('IGST @ ' . $IGST . '% ' . number_format($gst, $data['invoice_detail']->currency_decimal));
                    }

                    if ($this->invoice->gstCalculation($state, 'SGST', $data['invoice_detail']->total_amount, $SGST) > 0) {
                        $gst = $this->invoice->gstCalculation($state, 'SGST', $data['invoice_detail']->total_amount, $SGST);
                        $sgst = $gst;
                    }
                    if ($this->invoice->gstCalculation($state, 'CGST', $data['invoice_detail']->total_amount, $CGST) > 0) {
                        $gst += $this->invoice->gstCalculation($state, 'CGST', $data['invoice_detail']->total_amount, $CGST);
                        $templateVars['GST'] = 'SGST @ ' . $SGST . '% &nbsp; ' . number_format($this->invoice->gstCalculation($state, 'CGST', $data['invoice_detail']->total_amount, $SGST), $data['invoice_detail']->currency_decimal) . '<br/>' . 'CGST @ ' . $CGST . '%  &nbsp; ' . number_format($this->invoice->gstCalculation($state, 'CGST', $data['invoice_detail']->total_amount, $CGST), $data['invoice_detail']->currency_decimal);
                    }
                    if ($this->invoice->gstCalculation($state, 'UTGST', $data['invoice_detail']->total_amount, $UTGST)) {
                        $gst = $this->invoice->gstCalculation($state, 'UTGST', $data['invoice_detail']->total_amount, $UTGST);
                        // $templateVars['GST'] = 'UTGST @ ' . $UTGST . '% &nbsp; ';
                        $templateVars['GST'] = ('UTGST @ ' . $UTGST . '% ' . number_format($gst, $data['invoice_detail']->currency_decimal));
                    }
                } else {
                    // if ($this->invoice->gstCalculation($state, 'VAT', $data['invoice_detail']->total_amount, $VAT) > 0) {
                    //     $gst = $this->invoice->gstCalculation($state, 'VAT', $data['invoice_detail']->total_amount, $VAT);
                    //     $templateVars['VAT'] = 'VAT @ ' . $VAT . '% &nbsp; ' . number_format($gst, $data['invoice_detail']->currency_decimal);
                    // }
                    $gst = 0;
                $templateVars = [];
                }
            } else {
                echo json_encode(["message" => "Total amount can not be zero - testing.", "status" => 0]);
                exit;
            }
            // echo "gst ".$gst; die;
            $data['sample_desc'] = $data['invoice_detail']->sample_desc;
            $data['invoice_detail']->gst = $templateVars;
            $data['invoice_detail']->price_with_gst = $data['invoice_detail']->total_amount + $gst;
            $this->invoice->update_data('invoice_proforma',['price_with_gst' => $gst],['proforma_invoice_id' => $invoice_id]);
            $data['invoice_detail']->authorized_signatory = 0;
            $data['invoice_detail']->final_amount = number_format(round($data['invoice_detail']->price_with_gst), $data['invoice_detail']->currency_decimal);
            $data['invoice_detail']->amount_in_word = numberToWords($data['invoice_detail']->final_amount, $data['invoice_detail']->currency_basic_unit, $data['invoice_detail']->currency_fractional_unit);
            $data['invoice_detail']->authorized_signatory = 0;
            $file_name = "ProformaInvoice-" . $data['invoice_detail']->proforma_invoice_id . ".pdf";
            $save_pdf =  $this->generate_pdf('template/invoice_pdf', $data, 'aws_save', $file_name);
            $save_pdf = $this->report_upload_aws($save_pdf, $file_name);
            $file_array = array(
                'file_path'     => $save_pdf['aws_path'],
            );
            $update_file = $this->invoice->update_data('invoice_proforma', $file_array, ['proforma_invoice_id' => $data['invoice_detail']->proforma_invoice_id]);
            // Get old status
            $query = $this->db->select('invoice_status_name')
                ->join('invoice_status', 'invoice_proforma_invoice_status_id = invoice_status_id')
                ->where('proforma_invoice_id', $invoice_id)
                ->get('invoice_proforma');
            if ($query->num_rows() > 0) {
                $query_data = $query->row_array();
                $old_status = $query_data['invoice_status_name'];
            } else {
                $old_status = '';
            }
            $logdetails = array(
                'module'    => 'Invoice',
                'operation' => 'update_test_price',
                'source_module' => 'Invoice_Controller',
                'uidnr_admin'   => $this->admin_id(),
                'log_activity_on'   => date('Y-m-d H:i:s'),
                'action_message'    => 'Dynamic pricing updated',
                'invoice_id'     => $invoice_id,
                'new_status'    => '',
                'old_status'    => $old_status
            );
            $this->db->insert('invoice_activity_log', $logdetails);

            echo json_encode(["message" => "Data saved successfully.", "status" => 1]);
        } else {
            echo json_encode(["message" => "Something went wrong!.", "status" => 0]);
        }
    }

    /* added by millan on 23-06-2021 for excel export */
    public function sales_person_data()
    {
        ini_set('memory_limit', '128567M');
        $query = $this->session->userdata('excel_data');

        if ($query) {
            $data = $this->db->query($query)->result();

            if ($data && count($data) > 0) {
                // $logdetails = array(
                //     'module'    => 'Invoice',
                //     'operation' => 'sales_person_data',
                //     'source_module' => 'Invoice_Controller',
                //     'uidnr_admin'   => $this->admin_id(),
                //     'log_activity_on'   => date('Y-m-d H:i:s'),
                //     'action_message'    => 'Export to Excel',
                //     // 'invoice_id'     => $invoice_id,
                //     // 'new_status'    => '',
                //     // 'old_status'    => $old_status
                // );
                // $this->db->insert('invoice_activity_log', $logdetails);
                $this->load->library('excel');
                $tmpfname = "example.xls";
                $excelReader = PHPExcel_IOFactory::createReaderForFile($tmpfname);
                $objPHPExcel = $excelReader->load($tmpfname);
                $objPHPExcel->getProperties()->setCreator("GEO-CHEM")
                    ->setLastModifiedBy("GEO-CHEM")
                    ->setTitle("Office 2007 XLS SALES PERSON Document")
                    ->setSubject("Office 2007 XLS SALES PERSON Document")
                    ->setDescription("Description for SALES PERSON Document")
                    ->setKeywords("phpexcel office codeigniter php")
                    ->setCategory("SALES PERSON Filter file");
                $objPHPExcel->setActiveSheetIndex(0);
                $objPHPExcel->getActiveSheet()->setCellValue('A2', "SL NO.");
                $objPHPExcel->getActiveSheet()->setCellValue('B2', "Basil Report NUMBER");
                $objPHPExcel->getActiveSheet()->setCellValue('C2', "PROFORMA INVOICE NUMBER");
                $objPHPExcel->getActiveSheet()->setCellValue('D2', "CUSTOMER NAME");
                $objPHPExcel->getActiveSheet()->setCellValue('E2', "CLIENT NAME");
                $objPHPExcel->getActiveSheet()->setCellValue('F2', "PROFORMA CREATED BY");
                $objPHPExcel->getActiveSheet()->setCellValue('G2', "PROFORMA CREATED ON");
                $objPHPExcel->getActiveSheet()->setCellValue('H2', "TOTAL AMOUNT");
                $objPHPExcel->getActiveSheet()->setCellValue('I2', "SURCHARGE PERCENTAGE");
                $objPHPExcel->getActiveSheet()->setCellValue('J2', "SURCHARGE AMOUNT");
                $objPHPExcel->getActiveSheet()->setCellValue('K2', "SALES PERSON NAME");
                // $objPHPExcel->getActiveSheet()->setCellValue('J2', "CRM USER");

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

                $i = 3;
                foreach ($data as $key => $value) {
                    $objPHPExcel->getActiveSheet()->setCellValue('A' . $i, ($i - 2));
                    $objPHPExcel->getActiveSheet()->setCellValue('B' . $i, ($value->gc_no) ? $value->gc_no : '');
                    $objPHPExcel->getActiveSheet()->setCellValue('C' . $i, ($value->pro_invoice_number) ? $value->pro_invoice_number : '');
                    $objPHPExcel->getActiveSheet()->setCellValue('D' . $i, ($value->customer_name) ? $value->customer_name : '');
                    $objPHPExcel->getActiveSheet()->setCellValue('E' . $i, ($value->client) ? $value->client : '');
                    $objPHPExcel->getActiveSheet()->setCellValue('F' . $i, ($value->proforma_created_by) ? $value->proforma_created_by : '');
                    $objPHPExcel->getActiveSheet()->setCellValue('G' . $i, ($value->pro_invoice_date) ? $value->pro_invoice_date : '');
                    $objPHPExcel->getActiveSheet()->setCellValue('H' . $i, ($value->total_amount) ? $value->total_amount : '');
                    $objPHPExcel->getActiveSheet()->setCellValue('I' . $i, ($value->surcharge_percentage) ? $value->surcharge_percentage : '');
                    $objPHPExcel->getActiveSheet()->setCellValue('J' . $i, ($value->surcharge_amount) ? $value->surcharge_amount : '');
                    $objPHPExcel->getActiveSheet()->setCellValue('K' . $i, ($value->sales_person_name) ? $value->sales_person_name : '');
                    // $objPHPExcel->getActiveSheet()->setCellValue('J' . $i, ($value->crm_person) ? $value->crm_person : '');
                    $i++;
                }

                // Set Font Color, Font Style and Font Alignment
                $stil = array(
                    'borders' => array(
                        'allborders' => array(
                            'style' => PHPExcel_Style_Border::BORDER_THIN,
                            'color' => array('rgb' => '000000')
                        )
                    ),
                    'alignment' => array(
                        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                    )
                );
                // Merge Cells
                $objPHPExcel->getActiveSheet()->mergeCells('A1:I1');
                $objPHPExcel->getActiveSheet()->setCellValue('A1', "GEO CHEM PROFORMA INVOICE DETAILS");
                $objPHPExcel->getActiveSheet()->getStyle('A1:I1')->applyFromArray($stil);

                $filename = 'pi_sales-' . date('Y-m-d-s') . ".xls";
                $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
                ob_end_clean();
                header('Content-type: application/vnd.ms-excel');
                header('Content-Disposition: attachment; filename=' . $filename);
                $objWriter->save('php://output');
            }
        }
    }
}
