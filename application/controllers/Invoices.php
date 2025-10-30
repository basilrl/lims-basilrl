<?php
 

/**
 * Description of Invoice
 *
 * @author Abhishek
 */
class Invoices extends MY_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->model('InvoiceModel', 'INVM');
        $this->load->model('Invoice', 'invoice');
        $this->load->model('TestRequestForm','trf');
    }
    
    public function index(){ 
        $search = null;
        $where  = array();
        $base_url = "Invoices/index";
        
        if ($this->uri->segment(3) > 0  && $this->uri->segment(3) != NULL) {
            $data['invoice_type'] = $where['inv.invoice_type']  = $this->uri->segment(3); 
            $base_url  .= '/' . $this->uri->segment(3);
        } else {
            $data['invoice_type'] = '';
            $base_url  .= '/0';
        }
        if ($this->uri->segment(4) > 0 && $this->uri->segment(4) != NULL) {
            $data['customer_id'] = $where['inv.invoice_customer_id'] = $this->uri->segment(4); 
            $base_url  .= '/' . $this->uri->segment(4);
        } else {
            $data['customer_id'] = '';
            $base_url  .= '/0';
        }
        
        if ($this->uri->segment(5) > 0 && $this->uri->segment(5) != NULL) {
            $data['invoice_generatedBy'] = $where['inv.generated_by'] = $this->uri->segment(5); 
            $base_url  .= '/' . $this->uri->segment(5);
        } else {
            $data['invoice_generatedBy'] = '';
            $base_url  .= '/0';
        }
        
        $data['customer'] = $this->invoice->get_fields("cust_customers", "customer_id,customer_name");
        $data['users'] = $this->invoice->get_proforma_created();
        $data['result_count'] = $total_row = $this->INVM->allInvoices(NULL, NULL, NULL, NULL, NULL, $where, '1');
        $config =  $this->pagination($base_url, $total_row, 10, 6);
        
        $data['pagination'] = $config['links'];
        $data['invoice_list'] = $this->INVM->allInvoices($config["per_page"], $config['page'], $search, NULL, NULL, $where, NULL);
          
        $this->load_view('invoice/invoices', $data);
    }

    public function generateInvoice() {
  
        $performa_ids = $this->input->post('proforma_invoice_ids');
        $invoice_post_data = $this->input->post();
        $sample = $this->db->select('group_concat(sr.gc_no) as gc_no, sum(ip.total_amount) as total_amount,'
                . ' sum(ip.gst_amount) as gst_amount, sum(ip.proforma_discount) as proforma_discount,'
                . ' max(ip.tax_percentage) as tax_percentage, sum(ip.final_amount_after_tax) as final_amount_after_tax,'
                . ' ip.tax_currency, ip.invoice_proforma_customer_id')
                ->from('invoice_proforma  as ip')
                ->join('sample_registration as sr', 'ip.proforma_invoice_sample_reg_id = sr.sample_reg_id', 'left')
                ->where_in('ip.proforma_invoice_id', $performa_ids)
                ->get();
        
        $dynamic_details =  $this->_getDynamicTestDetails($performa_ids);
        
  
        if ($sample->num_rows() > 0) {  
           $this->db->trans_start();
            $sample_data = $sample->row_array();
            
            $tax_amount = $sample_data['total_amount'] * $sample_data['tax_percentage'] /100;
            $final_amount =$sample_data['total_amount'] + $tax_amount;
            $data = array(
                'report_num' => $sample_data['gc_no'],
//                'proforma_invoice_id' => $performa_id,
                'invoice_customer_id'     => $sample_data['invoice_proforma_customer_id'],
                'generated_date' => date('Y-m-d H:i:s'),
                'status' => 'Draft',
                'background_process' => 'Completed',
                'page_count' => 1,
                'invoice_type' => '2',
                'total_amount' => $sample_data['total_amount'],
                'gst_amount' => $tax_amount,
                'proforma_discount' => $sample_data['proforma_discount'],
                'tax_percentage' => $sample_data['tax_percentage'],
                'tax_currency' => $sample_data['tax_currency'],
                'final_amount_after_tax' => $final_amount,
                'generated_by' => $this->admin_id(),                 
                "inspection_qty" => $invoice_post_data['inspection_qty'],
                "inspection_date_bl" => $invoice_post_data['inspection_date_bl'],
                "vessel_name" => $invoice_post_data['vessel_name'],
                "sample_rec_date" => $invoice_post_data['sample_rec_date'],
                "product" => $invoice_post_data['product'],
                "supply_date" => $invoice_post_data['supply_date'],
                "certificate_report_no" => $invoice_post_data['certificate_report_no'],
                "contract_no" => $invoice_post_data['contract_no'],
                "lpo_no" => $invoice_post_data['lpo_no'],
                //13-10-2021                   
                'nomination_contact' => $invoice_post_data['nomination_contact'],
                'customer_email' => $invoice_post_data['customer_email'],
                'contact_person_name' => $invoice_post_data['contact_person_name'],
                'job_ref_no' => $invoice_post_data['job_ref_no'],
                'style_no' => $invoice_post_data['style_no'],
                'quotes_ref_no' => $invoice_post_data['quotes_ref_no'],
                'vat_prod_posting_group' => ($sample_data['tax_percentage'] == 5) ? 'REDUCED' : 'ZERO',
                'tax_amount' => $sample_data['gst_amount']
            );
           
            $this->db->insert('Invoices', $data);
            $invoice_id = $this->db->insert_id();
            if ($invoice_id) {
                
                foreach ($performa_ids as $pid) {
                    $inv_pro_junc[] = array(
                        'invoice_id' => $invoice_id,
                        'pro_invoice_id' => $pid,
                        'created_by' => $this->admin_id()
                    );
                }

                $this->db->insert_batch('invoice_proforma_junction', $inv_pro_junc);
                $this->db->set('invoice_proforma_invoice_status_id', 16)
                         ->where_in('proforma_invoice_id', $performa_ids)
                         ->update('invoice_proforma');
                  if(!empty($dynamic_details)){
                     $invoice_dynamic_data = $this->_setDynamicTestDetails($invoice_id, $dynamic_details);
                     $this->db->insert_batch('invoice_dynamic_prices', $invoice_dynamic_data);
                  }
                $this->db->trans_complete();
                 
                 $this->session->set_flashdata('success', 'invoice generated successfully!');
                redirect('performa-invoice');
             
            }
            $this->session->set_flashdata('error', 'somthing went wrong!');
            redirect('performa-invoice');
        }
    }
    
    public function dynamicTestForm(){
        $data['invoice_id'] = $invoice_id = $this->input->get('invoice_id');
//        $data['sample_reg_id'] = $this->input->get('sample_reg_id');
        $data['currency'] = $this->trf->get_currency(); 
        $data['dynamic_tests'] = $this->INVM->dynamic_test_price_details($invoice_id);
        $data['invoice_details'] = $this->INVM->invoice_details($invoice_id);  //ADDED ON 11-10-2021 
        
        $data = $this->load->view('invoice/invoice_test_price_addUpdate', $data, true);
        echo json_encode($data);    
    }
   
    public function sendToBC365(){
       $invoice_id = $this->input->post('invoice_id');
        if (!IS_ERP_INTEGRATED) {
            echo json_encode(['error' => array('message' => "Not Allowed!")]);
        } else {
            $dynamic_tests = $this->INVM->dynamic_test_price_details($invoice_id);
//        $proforma_invoice_id = $this->db->select('proforma_invoice_id')
//                ->get_where('Invoices', ['invoiced_id' => $invoice_id])
//                ->row_array();
//        $invoice_detail = $this->invoice->get_invoice_details_for_bc365($proforma_invoice_id['proforma_invoice_id']);
            $invoice_detail = $this->INVM->invoice_details($invoice_id);

            $tests = is_array($dynamic_tests) ? count($dynamic_tests) : 0;

            if ($tests > 0) {
            $j = 1;
            for ($api = $tests, $k = 0; $k < $tests; $api++, $k++) {
                $invoice_data = array(
                    "limsInvoiceID" => $invoice_id,//$invoice_id,
                    "lineNo" => $j++,
                    "customerID" => $invoice_detail->nav_customer_code, // $invoice_detail['nav_customer_code']
                    "locationID" => "",
                    "itemType" => 0,
                    "ProductID" => $dynamic_tests[$k]['inv_dyn_id'],
                    "Name" => $dynamic_tests[$k]['dynamic_heading'],
                    "quantity" => (int) $dynamic_tests[$k]['quantity'],
                    "unitType" => "PCS",
                    "unitPrice" => (double) $dynamic_tests[$k]['dynamic_value'],
                    "discount_percentage" => (double) $dynamic_tests[$k]['discount'],
                    "applicable_charges" => (double) $dynamic_tests[$k]['applicable_charge'],
                    "total_price" => (double) $invoice_detail->total_amount,
                    "gd1" => "",
                    "gd2" => "",
                    "Invoice_Date" => date("Y-m-d"),
                        "InspectionQty" => (int) $invoice_detail->inspection_qty,
                        "Inspection_Date_BL" => $invoice_detail->inspection_date_bl,
                        "VesselName" => $invoice_detail->vessel_name,
                        "SampleRecDate" => $invoice_detail->sample_rec_date,
                        "Product" => $invoice_detail->product,
                        "DateOfSupply" => $invoice_detail->supply_date,
                        "Certificate_Report_No" => $invoice_detail->certificate_report_no,
                        "ContractNo" => $invoice_detail->contract_no,
                        "LPO_No" => $invoice_detail->lpo_no,
                        "GEMQuoteNo" => !empty($invoice_detail->quotes_ref_no) ? $invoice_detail->quotes_ref_no : "N/A",
                        "Approval" => 0,
                        "CurrencyCode" => $invoice_detail->tax_currency, //tax_currency currency_code
                        "user_id" => $this->admin_id(),
                        "auth_by" => $this->admin_id(),
                    //ADDED ON 12-10-2021
                        'Nomination_Contact' => $invoice_detail->nomination_contact,
                        'Customer_Email_Address' => $invoice_detail->customer_email,
                        'Contact_Person_Name' => $invoice_detail->contact_person_name,
                        'Job_Ref_No' => $invoice_detail->job_ref_no,
                        'Style_No' => $invoice_detail->style_no,
                        'VAT_Base_Amount' => 0,//(double) $invoice_detail->tax_amount
                        'VATProdPosting_Group' => $invoice_detail->vat_prod_posting_group,
                        'VATPer' => 0, //(int) $invoice_detail->tax_percentage
                    );
              
                $status = $this->_addInvoice($invoice_data);
            }
            
            if ($status['status'] == 201) {
                $this->db->update('Invoices', ['isUpdatedOnBC365' => 1, 'prepared_by' => $this->admin_id()], ['invoiced_id' => $invoice_id]);
                $this->_maintain_log($invoice_id, "Sent Invoice Details To Business Central");
                echo true;
            }else{
                echo $status['response_data']; 
            }
        }
        echo false;


        // applicant_code total_amount
        
// 1. get invoice details 2. hit API
}
     
   }
   
   
    public function approveInvoice(){
       $invoice_id = $this->input->post('invoice_id');
        if (!IS_ERP_INTEGRATED) {
            echo json_encode(['error' => array('message' => "Not Allowed!")]);
        } else {
            $dynamic_tests = $this->INVM->dynamic_test_price_details($invoice_id);
            $invoice_detail = $this->INVM->invoice_details($invoice_id);

            $tests = is_array($dynamic_tests) ? count($dynamic_tests) : 0;

            if ($tests > 0) {
            $j = 1;
//            $last_key = end(array_keys($dynamic_tests));

            for ($api = $tests, $k = 0; $k < $tests; $api++, $k++) {
                $invoice_data = array(
                    "limsInvoiceID" => $invoice_id,
                    "lineNo" => $j++,
                    "customerID" => $invoice_detail->nav_customer_code, 
                    "locationID" => "",
                    "itemType" => 0,
                    "ProductID" => $dynamic_tests[$k]['inv_dyn_id'],
                    "Name" => $dynamic_tests[$k]['dynamic_heading'],
                    "quantity" => (int) $dynamic_tests[$k]['quantity'],
                    "unitType" => "PCS",
                    "unitPrice" => (double) $dynamic_tests[$k]['dynamic_value'],
                    "discount_percentage" => (double) $dynamic_tests[$k]['discount'],
                    "applicable_charges" => (double) $dynamic_tests[$k]['applicable_charge'],
                    "total_price" => (double) $invoice_detail->total_amount,
                    "gd1" => "",
                    "gd2" => "",
                    "Invoice_Date" => date("Y-m-d"),
                       "InspectionQty" => (int) $invoice_detail->inspection_qty,
                        "Inspection_Date_BL" => $invoice_detail->inspection_date_bl,
                        "VesselName" => $invoice_detail->vessel_name,
                        "SampleRecDate" => $invoice_detail->sample_rec_date,
                        "Product" => $invoice_detail->product,
                        "DateOfSupply" => $invoice_detail->supply_date,
                        "Certificate_Report_No" => $invoice_detail->certificate_report_no,
                        "ContractNo" => $invoice_detail->contract_no,
                        "LPO_No" => $invoice_detail->lpo_no,
                        "GEMQuoteNo" => $invoice_detail->quotes_ref_no,
                        "Approval" => ($tests == $j-1) ? 1 : 0,
                        "CurrencyCode" => $invoice_detail->tax_currency, //tax_currency
                        "user_id" => $this->admin_id(),
                        "auth_by" => $this->admin_id(),
                     //ADDED ON 12-10-2021
                        'Nomination_Contact' => $invoice_detail->nomination_contact,
                        'Customer_Email_Address' => $invoice_detail->customer_email,
                        'Contact_Person_Name' => $invoice_detail->contact_person_name,
                        'Job_Ref_No' => $invoice_detail->job_ref_no,
                        'Style_No' => $invoice_detail->style_no,
                        'VAT_Base_Amount' => 0,  //(double) $invoice_detail->tax_amount
                        'VATProdPosting_Group' => $invoice_detail->vat_prod_posting_group,
                        'VATPer' => 0,  //(int) $invoice_detail->tax_percentage
                    );

                $status = $this->_addInvoice($invoice_data);
            }
          
            if ($status['status'] == 201) {
                $this->db->update('Invoices', ['isApproved' => 1, 'approved_by' => $this->admin_id()], ['invoiced_id' => $invoice_id]);
                $this->_maintain_log($invoice_id, "Invoice Approved");
                echo true;
            }else{
                echo $status['response_data']; 
            }
        }
        echo false;

}
     
   }
   
    public function invoicePaymentDetails(){
        $data['invoice_id'] = $invoice_id = $this->input->get('invoice_id');
        
        $data['invoice_payment'] = $this->INVM->invoice_payment_details($invoice_id);
        if($data['invoice_payment']){
           $paymentdata = $this->load->view('invoice/invoice_payment_details', $data, true);
        }else{
            $paymentdata = "<span class='text-center text-danger'><b>Payment Not Marked Yet!</b></span>";
        }
        echo json_encode($paymentdata);    
    }
   
    public function invoiceTestDetails(){
        $data['invoice_id'] = $invoice_id = $this->input->get('invoice_id');
        
        $data['dynamic_tests'] = $this->INVM->dynamic_test_price_details($invoice_id);
        if($data['dynamic_tests']){
           $testdata = $this->load->view('invoice/invoice_test_details', $data, true);
        }else{
            $testdata = "<span class='text-center text-danger'><b>Not Found!</b></span>";
        }
        echo json_encode($testdata);    
    }
   
   
    public function saveDynamicTestPrices(){

        $data = $this->input->post();
        // 1. save total_amount on invoice table
        // 2. save dynamic values on invoice_dynamic_details table
        // 3. calculate gst & save in invoices
        
        $tax_amount = $data['total_amount'] * $data['tax_percentage'] /100;
        $invoiceTestData = array('total_amount' => $data['total_amount'],
            'tax_percentage' => $data['tax_percentage'],
            'final_amount_after_tax' => $data['final_amount_after_tax'],
            'tax_currency' => $data['tax_currency'],
            'gst_amount' => $tax_amount,
            //ADDED ON 11-10-2021
            'inspection_qty' => $data['inspection_qty'],
            'inspection_date_bl' => $data['inspection_date_bl'],
            'vessel_name' => $data['vessel_name'],
            'sample_rec_date' => $data['sample_rec_date'],
            'product' => $data['product'],
            'supply_date' => $data['supply_date'],
            'certificate_report_no' => $data['certificate_report_no'],
            'contract_no' => $data['contract_no'],
            'lpo_no' => $data['lpo_no'],  
            
            'nomination_contact' => $data['nomination_contact'],         
            'customer_email' => $data['customer_email'],         
            'contact_person_name' => $data['contact_person_name'],         
            'job_ref_no' => $data['job_ref_no'],         
            'style_no' => $data['style_no'],         
            'quotes_ref_no' => $data['quotes_ref_no'],
            'tax_amount' => $tax_amount,
            'vat_prod_posting_group' => ($data['tax_percentage'] == 5) ? 'REDUCED' : 'ZERO'
        );
      
        $this->db->trans_start();
        $this->db->update('Invoices', $invoiceTestData, ['invoiced_id' => $data['invoice_id']]);         
        $this->_saveDynamicTestData($data['test'], $data['invoice_id']); //, $data['sample_reg_id']
        $this->db->trans_complete();
        redirect('invoices');
    }
    
    private function _getDynamicTestDetails($performa_id) {      
        $dynamic_data = $this->db->select('*')
                                ->from('invoice_dynamic_details')
                                ->where_in('invoice_id', $performa_id)
                                ->where('is_deleted', 0)
                                ->get();
        if($dynamic_data->num_rows() > 0){
            return $dynamic_data->result_array();
        }else{
            return false;
        }
    }
    
    private function _setDynamicTestDetails($invoice_id, $dynamicData) {
        foreach ($dynamicData as $dynamicDetail) {
            $data[] = array(
                'invoice_id' => $invoice_id,
//                'sample_registration_id' => $sample_reg_id,
                'dynamic_heading' => $dynamicDetail['dynamic_heading'],
                'dynamic_value' => $dynamicDetail['dynamic_value'],
                'quantity' => $dynamicDetail['quantity'],
                'discount' => $dynamicDetail['discount'],
                'applicable_charge' => $dynamicDetail['applicable_charge'],
                'created_by' => $this->admin_id(),
            );
        }
        return $data;
    }

    private function _saveDynamicTestData($tests, $invoice_id) { //, $sample_reg_id

        $this->db->where('invoice_id', $invoice_id)->delete('invoice_dynamic_prices');
       
        if (!empty($tests)) {
            foreach ($tests['dynamic_heading'] as $k => $val) {
                if (!empty($val)) {
                    $testprice_data[] = array(
                        'invoice_id' => $invoice_id,
//                        'sample_registration_id' => $sample_reg_id,
                        'dynamic_heading' => $val,
                        'dynamic_value' => $tests['dynamic_value'][$k],
                        'quantity' => $tests['quantity'][$k],
                        'discount' => $tests['discount'][$k],
                        'applicable_charge' => $tests['applicable_charge'][$k],
                        'created_by' => $this->admin_id(),
                    );
                }
            }
            
            isset($testprice_data) && !empty($testprice_data) 
            ? $this->db->insert_batch('invoice_dynamic_prices', $testprice_data) 
                    : '';
        }
    }

    private function _calculateGST($invoice_id) {
//        $proforma_invoice_id = $this->db->select('proforma_invoice_id')
//                                        ->get_where('Invoices', ['invoiced_id' => $invoice_id])
//                                        ->row_array();
//           $data['invoice_detail'] = $this->invoice->get_invoice_details($proforma_invoice_id['proforma_invoice_id']);
           $data['invoice_detail'] = $this->INVM->invoice_details($invoice_id);

//               $data['test_details'] = $this->invoice->get_fields_by_id('invoice_dynamic_prices', '*', $invoice_id, 'invoice_id');
            // Get country id of the user
            $country_id = $this->invoice->get_country_id();
            $state = $data['invoice_detail']->state;
            $IGST = $this->invoice->get_fields_by_id("sys_configuration", "cfg_Value", "IGST", "cfg_Name")[0]['cfg_Value'];
            $SGST = $this->invoice->get_fields_by_id("sys_configuration", "cfg_Value", "SGST", "cfg_Name")[0]['cfg_Value'];
            $CGST = $this->invoice->get_fields_by_id("sys_configuration", "cfg_Value", "CGST", "cfg_Name")[0]['cfg_Value'];
            $UTGST = $this->invoice->get_fields_by_id("sys_configuration", "cfg_Value", "UTGST", "cfg_Name")[0]['cfg_Value'];
            $VAT = $this->invoice->get_fields_by_id("sys_configuration", "cfg_Value", "VAT", "cfg_Name")[0]['cfg_Value'];

// Get dynamic fields value
           
            $templateVars = [];
            if ($data['invoice_detail']->total_amount > 0) {
            if ($country_id == 1) {
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
                    $templateVars['GST'] = 'UTGST @ ' . $UTGST . '% &nbsp; ';
                }
            } else {
                if ($this->invoice->gstCalculation($state, 'VAT', $data['invoice_detail']->total_amount, $VAT) > 0) {
                    $gst = $this->invoice->gstCalculation($state, 'VAT', $data['invoice_detail']->total_amount, $VAT);  //, $data['invoice_detail']->country_code
                    $templateVars['VAT'] = 'VAT @ ' . $VAT . '% &nbsp; ' . number_format($gst, $data['invoice_detail']->currency_decimal);
                }
            }

            $this->invoice->update_data('Invoices', ['gst_amount' => $gst], ['invoiced_id' => $invoice_id]);
        } else {
            $this->invoice->update_data('Invoices', ['gst_amount' => 0], ['invoiced_id' => $invoice_id]);
        }
    }

    private function _addInvoice($invoice_detail) {
     if(IS_ERP_INTEGRATED){
         $payload = json_encode($invoice_detail);
        $url = INVOICE_ADD_API;
        // Initialise new cURL session
        $ch = curl_init($url);

         curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
         curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($ch, CURLINFO_HEADER_OUT, true);

        curl_setopt($ch, CURLOPT_POST, true);

        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
 
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
             ERP_AUTH_KEY,
            'Content-Type: application/json',
            'Content-Length: ' . strlen($payload))
        );
        
        $result = curl_exec($ch);
 
        $status = curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
        curl_close($ch);  
        return ['status' => $status, 'response_data' => $result];   
        }
        
    return ['status' => 403, 'response_data' => "Integration is not done for this"];
    }
    
    public function previewPdf() {
        $invoice_id = $this->input->get('invoice_id');
        $base64String = $this->_previewPDF((string) $invoice_id);
        if (isset($base64String['response_data']->PDFbase64String) && !empty($base64String['response_data']->PDFbase64String)) {
           
            $decoded = base64_decode($base64String['response_data']->PDFbase64String);
            $file = 'invoice_preview.pdf';
            file_put_contents($file, $decoded);

 
            if (file_exists($file)) {
                header('Content-Description: File Transfer');
                header('Content-Type: application/octet-stream');
                header('Content-Disposition: attachment; filename="' . basename($file) . '"');
                header('Expires: 0');
                header('Cache-Control: must-revalidate');
                header('Pragma: public');
                header('Content-Length: ' . filesize($file));
                readfile($file);
                exit;
            }
            redirect('invoices');
        }
    }
    
    private function _previewPDF($invoice_id) {
     if(IS_ERP_INTEGRATED){
        $invoice_detail =  array(
             'PreAssignedNo' => $invoice_id
         );
         
        $payload = json_encode($invoice_detail);
        $url = INVOICE_GET_PDF_API;
        // Initialise new cURL session
        $ch = curl_init($url);

         curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
         curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($ch, CURLINFO_HEADER_OUT, true);

        curl_setopt($ch, CURLOPT_POST, true);

        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
 
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
             ERP_AUTH_KEY,
            'Content-Type: application/json',
            'Content-Length: ' . strlen($payload))
        );
        
        $result = curl_exec($ch);
 
        $status = curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
        curl_close($ch);  
        return ['status' => $status, 'response_data' => json_decode($result)];   
        }
        
    return ['status' => 403, 'response_data' => "PDF NOT FOUND"];
    }
   
    public function invoiceDetailsForm(){
        $data['proforma_invoice_id'] = $invoice_id = $this->input->get('proforma_invoice_id');
        $data['sample_reg_id'] = $this->input->get('sample_reg_id');
        $data['invoice_detail'] = $this->INVM->get_invoice_details($invoice_id);  
        $data['gc_nums'] = $this->INVM->get_gc_nums($invoice_id); 
        $data = $this->load->view('invoice/invoice_details_form', $data, true);
        echo json_encode($data);    
    }
   
    public function changeClientForm(){
        $data['invoice_id'] = $invoice_id = $this->input->get('invoice_id');        
        $data['client_detail'] = $this->INVM->clients_details($invoice_id);
        $data = $this->load->view('invoice/change_client', $data, true);
        echo json_encode($data);    
    }
    
    public function updateClient(){
      $client_id =  $this->input->post('invoice_client_id');
      $invoice_id =  $this->input->post('invoice_id');
      $result = $this->db->update('Invoices', ['invoice_customer_id' => $client_id], ['invoiced_id' => $invoice_id]);
      if($result){
          $this->_maintain_log($invoice_id, 'Client Updated');
           $this->session->set_flashdata('success', 'Client Updated Successfully!');
           redirect('invoices'); 
      }else{
           $this->session->set_flashdata('error', 'Something Went Wrong!');
          redirect('invoices');
      }
      
    }
    
    public function changeProformaClientForm(){
        $data['invoice_id'] = $proforma_invoice_id = $this->input->get('proforma_invoice_id');        
        $data['client_detail'] = $this->INVM->clients_proforma_details($proforma_invoice_id);
        $data = $this->load->view('invoice/proforma_change_client', $data, true);
        echo json_encode($data);    
    }
    
    public function updateProformaClient(){
      $client_id =  $this->input->post('invoice_client_id');
      $invoice_id =  $this->input->post('proforma_invoice_id');
      $result = $this->db->update('invoice_proforma', ['invoice_proforma_customer_id' => $client_id], ['proforma_invoice_id' => $invoice_id]);
      if($result){         
           $this->session->set_flashdata('success', 'Client Updated Successfully!');
           redirect('performa-invoice'); 
      }else{
           $this->session->set_flashdata('error', 'Something Went Wrong!');
          redirect('performa-invoice');
      }
      
    }

    private function _maintain_log($invoice_id, $msg) {
        $log = array(
            'status' => $msg,
            'user_id' => $this->admin_id(),
            'invoice_id' => $invoice_id,
            'invoice_id' => $invoice_id,
        );
        $this->db->insert('invoice_log', $log);
    }

 

}
