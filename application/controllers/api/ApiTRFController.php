<?php
defined('BASEPATH') or exit('No script access allowed');
require APPPATH . 'libraries/REST_Controller.php';
class ApiTRFController extends REST_Controller{
    function __construct()
    {
        parent::__construct();
        $this->load->model('api/ApiTRFModel','atm');
    }

    public function getCustomer_get($buyer_id){

        // $customerId = $this->uri->segment(4);
        $cust = $this->atm->getCustomer($buyer_id);
        if($cust){
            $data['status'] = 1;
            $data['message'] = 'Customer List';
            $data['customers'] = $cust;
        } else {
            $data['status'] = 0;
            $data['message'] = 'No record found!.';
        }
        $this->set_response($data, REST_Controller::HTTP_OK);
    }

    public function getServiceType_get(){

        $service = $this->atm->getServiceType();
        if($service){
            $data['status'] = 1;
            $data['message'] = 'Service type';
            $data['service_type'] = $service;
        } else {
            $data['status'] = 0;
            $data['message'] = 'No record found!.';
        }
        $this->set_response($data, REST_Controller::HTTP_OK);
    }

    public function getAgent_get()
    {
        $customerId = $this->uri->segment(4);
        $agent = $this->atm->getAgent($customerId);
        if($agent){
            $data['status'] = 1;
            $data['message'] = 'agent Lists';
            $data['agent'] = $agent;
        } else {
            $data['status'] = 0;
            $data['message'] = 'No record found!.';
        }
        $this->set_response($data, REST_Controller::HTTP_OK);
    }

    public function getSupplier_get()
    {
        $customerId = $this->uri->segment(4);
        $supplier = $this->atm->getSupplier($customerId);
        if($supplier){
            $data['status'] = 1;
            $data['message'] = 'supplier Lists';
            $data['supplier'] = $supplier;
        } else {
            $data['status'] = 0;
            $data['message'] = 'No record found!.';
        }
        $this->set_response($data, REST_Controller::HTTP_OK);
    }

    public function getContactPerson_get($applicant_id){

        $cont = $this->atm->getContactPerson($applicant_id);
        if($cont){
            $data['status'] = 1;
            $data['message'] = 'Contact Persons';
            $data['contact'] = $cont;
        } else {
            $data['status'] = 0;
            $data['message'] = 'No record found!.';
        }
        $this->set_response($data, REST_Controller::HTTP_OK);
    }

    public function getDestCountry_get(){

        $country = $this->atm->getDestCountry();
        if($country){
            $data['status'] = 1;
            $data['message'] = 'country';
            $data['country'] = $country;
        } else {
            $data['status'] = 0;
            $data['message'] = 'No record found!.';
        }
        $this->set_response($data, REST_Controller::HTTP_OK);
    }

    public function getCurrency_get(){

        $currency = $this->atm->getCurrency();
        if($currency){
            $data['status'] = 1;
            $data['message'] = 'currency';
            $data['currency'] = $currency;
        } else {
            $data['status'] = 0;
            $data['message'] = 'No record found!.';
        }
        $this->set_response($data, REST_Controller::HTTP_OK);
    }

    public function getDivision_get(){

        $division = $this->atm->getDivision();
        if($division){
            $data['status'] = 1;
            $data['message'] = 'division';
            $data['divisions'] = $division;
        } else {
            $data['status'] = 0;
            $data['message'] = 'No record found!.';
        }
        $this->set_response($data, REST_Controller::HTTP_OK);
    }

    public function getProduct_get(){

        $product = $this->atm->getProduct();
        if($product){
            $data['status'] = 1;
            $data['message'] = 'product';
            $data['products'] = $product;
        } else {
            $data['status'] = 0;
            $data['message'] = 'No record found!.';
        }
        $this->set_response($data, REST_Controller::HTTP_OK);
    }

    public function addTRF_post(){
         
        $post=$this->input->raw_input_stream;
        $post_data = json_decode($post);
        // echo '<pre>'; print_r($post_data); die;
        $customer_id		= $post_data->trf_applicant;
        $service 			= $post_data->trf_service_type;
        $applicant 			= $post_data->trf_applicant;
        $buyer 				= (array_key_exists('trf_buyer',$post_data) && $post_data->trf_buyer) ? $post_data->trf_buyer : 0;
        if($buyer > 0){
            $buyer_query = $this->db->select('customer_id')->where('basil_customer_details_id', $buyer)->get('cust_customers')->row_array();
            $buyer_id = $buyer_query['customer_id'];
        } else {
            $buyer_id = 0;
        }
        $agent 				= ($post_data->trf_agent == "Select Agent") ? 0 : $post_data->trf_agent;
        //$trf_contact		= $post_data->trf_contact;
        $trf_contact = (array_key_exists('trf_contact',$post_data) && $post_data->trf_contact)?$post_data->trf_contact:0;
        // if ($trf_contact > 0) {
        //         $trf_contact = implode(",", $post_data->trf_contact);
        // } else
        //         $trf_contact = '';

        $trf_thirdparty 	= $post_data->trf_thirdparty;
        $trf_sample_ref_id	= $post_data->trf_sample_ref_id;
        $trf_sample_desc	= $post_data->trf_sample_desc;
        $invoice_to			= $post_data->invoice_to;
        $invtocontant = (array_key_exists('trf_invoice_to_contact',$post_data) && $post_data->trf_invoice_to_contact)?$post_data->trf_invoice_to_contact:0;
        if ($invtocontant > 0) {
                $trf_invoice_to_contact = implode(",", $post_data->trf_invoice_to_contact);
        } else
                $trf_invoice_to_contact = '';
        $trf_no_of_sample	= $post_data->trf_no_of_sample;
        // $cc_id				= $post_data->cc_id;
        // $trf_cc 			= $post_data->trf_cc;
        // $bcc_id				= $post_data->bcc_id;
        // $trf_bcc			= $post_data->trf_bcc;
        $trf_client_ref_no	= $post_data->trf_client_ref_no;
        $trf_country_destination = $post_data->trf_country_destination;
        $trf_country_orgin	= $post_data->trf_country_orgin;
        $open_trf_currency_id = $post_data->open_trf_currency_id;
        $open_trf_exchange_rate = (array_key_exists('open_trf_exchange_rate',$post_data) && $post_data->open_trf_exchange_rate)?$post_data->open_trf_exchange_rate:0;
        $trf_product 		= $post_data->trf_product;
        $trf_end_use		= $post_data->trf_end_use;
        $tat_date			= (array_key_exists('tat_date',$post_data) && $post_data->tat_date)?$post_data->tat_date:NULL;
        $division			= $post_data->division;
        $reported_to		= $post_data->reported_to;	 	
        // $crm_user_id		= $post_data->crm_user_id;
        $sample_return_to	= $post_data->sample_return_to;
        $care_instruction 	= $post_data->dynamic;
        $sample_pickup_services = $post_data->sample_pickup_services;
        $dynamic			= $post_data->dynamic_field?$post_data->dynamic_field:''; 
        // $trf_branch			= ($post_data->trf_branch)?$post_data->trf_branch:0; 
        // $sales_person	    = $post_data->sales_person;
        // Check test name
        
        $test = $post_data->test;
        $record = array(
                'trf_applicant'			            => $applicant,
                'trf_contact'			            => (is_array($trf_contact)) ? implode(",", $trf_contact) : $trf_contact,
                'trf_sample_ref_id'		            => $trf_sample_ref_id,
                'trf_invoice_to'		            => (is_array($invoice_to)) ? implode(",", $invoice_to) : $invoice_to,
                'trf_product'			            => $trf_product,
                'work_id'				            => 0,
                'trf_buyer'				            => $buyer_id,
                'trf_agent'				            => (isset($agent) && !empty($agent)) ? $agent : 0,
                'trf_sample_desc'		            => $trf_sample_desc,
                'trf_no_of_sample'		            => $trf_no_of_sample,
                'trf_country_destination'           => $trf_country_destination,
                'trf_end_use'			            => $trf_end_use,
                'trf_quote_id'			            => 0,
                'trf_client_ref_no'		            => $trf_client_ref_no,
                'quote_customer_id'		            => 0,
                'reported_to'			            => (is_array($reported_to)) ? implode(",", $reported_to) : $reported_to,
                'sample_return_to'		            => (is_array($sample_return_to)) ? implode(",", $sample_return_to) : $sample_return_to,
                'open_trf_currency_id'	            => $open_trf_currency_id,
                'open_trf_customer_id'	            => $customer_id,
                'open_trf_customer_type'            => 'Factory',
                'open_trf_exchange_rate'            => $open_trf_exchange_rate,
                'trf_thirdparty'		            => (isset($trf_thirdparty) && !empty($trf_thirdparty)) ? $trf_thirdparty : 0,
                'trf_country_orgin'		            => $trf_country_orgin,
                'tat_date'				            => (!empty($tat_date))?$tat_date:NULL,
                'trf_type'				            => "Open TRF",
                'division'				            => $division,
                'create_on'				            => date('Y-m-d H:i'),
                'updated_by'			            => 1,
                'sample_pickup_services'            => $sample_pickup_services,
                'clone_trf_id'			            => 0,    
                'trf_service_type'                  => $service
            );
            //    if ($service >= 2 && $service <= 30) {
            //     $record['service_days'] = $service;
            //     $record['trf_service_type'] = "Regular";
            // } else {
            //     $record['service_days'] = '';
            //     $record['trf_service_type'] = $service;
            // }

            // save application care provided instruction
            foreach ($care_instruction as $care) {
                $care_instructions[] = $care;
            }
            if (!empty($dynamic)) {
                foreach ($dynamic as $key => $dynamic_values) {
                    if (!empty($dynamic_values)) {
                        if($dynamic_values[0] == 'Style No.'){
                            $record['style_number'] = $dynamic_values[1];
                        }
                        $dyn_values[] = $dynamic_values;
                    } else {
                        $dyn_values = [];
                    }
                }
            } else {
                $dyn_values = [];
            }
        
        $save = $this->atm->save_open_trf($record, $dyn_values, $test, $care_instructions);
    
       if($save){
        $data['status'] = 1;
        $data['message']  = "TRF with " . $save['unique_id'] . " is created";
       
     } else {
        $data['status'] = 0;
        $data['message'] = 'No record found!.';
     }
      $this->set_response($data, REST_Controller::HTTP_OK);
    }

    public function getTRF_get($buyer_id, $keyword, $applicant, $product, $from_date, $to_date, $start, $end){
        $data['trf_count'] = $this->atm->getTRF($buyer_id, $keyword, $applicant, $product, $from_date, $to_date, NULL, NULL, true);
        $data['trf'] = $this->atm->getTRF($buyer_id, $keyword, $applicant, $product, $from_date, $to_date, $start, $end);
        if(!empty($data['trf'])){
            $data['status'] = 1;
            $data['message']  = "TRF List";
           
         } else {
            $data['status'] = 0;
            $data['message'] = 'No record found!.';
         }
          $this->set_response($data, REST_Controller::HTTP_OK);
    }

    public function getTRFDetails_get($trf_id){
        $data['trf_details'] = $this->atm->getTRFDetails($trf_id);
        if(!empty($data['trf_details'])){
            $data['test_data'] = $this->atm->getTrfTest($trf_id);
            $data['apc_instruction'] = $this->atm->getTrfAPCInstruction($trf_id);
            $data['status'] = 1;
            $data['message']  = "TRF Details";
           
         } else {
            $data['status'] = 0;
            $data['message'] = 'No record found!.';
         }
          $this->set_response($data, REST_Controller::HTTP_OK);
    }

    public function editTRF_get($trf_id){
        $data['trf_details'] = $this->atm->getTRFDetails($trf_id);
        if(!empty($data['trf_details'])){
            $data['test_data'] = $this->atm->getTrfTest($trf_id);
            $data['apc_instruction'] = $this->atm->getTrfAPCInstruction($trf_id);
            $data['status'] = 1;
            $data['message']  = "Edit TRF Details";
           
        } else {
            $data['status'] = 0;
            $data['message'] = 'No record found!.';
        }
        $this->set_response($data, REST_Controller::HTTP_OK);
    }

    public function deleteTrfTest_get($trf_test_id){
        $delete = $this->db->update('trf_test',['is_deleted' => 1],['trf_test_id' => $trf_test_id]);
        if($delete){
            $data['status'] = 1;
            $data['message']  = "Test Removed";
           
         } else {
            $data['status'] = 0;
            $data['message'] = 'Something went wrong!.';
         }
          $this->set_response($data, REST_Controller::HTTP_OK);
    }
    
    public function deleteAPC_get($apc_id){
        $delete = $this->db->update('trf_apc_instruction',['is_deleted' => 1],['apc_instruction_id' => $apc_id]);
        if($delete){
            $data['status'] = 1;
            $data['message']  = "APC Removed";
           
         } else {
            $data['status'] = 0;
            $data['message'] = 'Something went wrong!.';
         }
          $this->set_response($data, REST_Controller::HTTP_OK);
    }

    
    public function updateTRF_post(){
         
        $post=$this->input->raw_input_stream;
        $post_data = json_decode($post);
        // echo '<pre>'; print_r($post_data); die;
        $customer_id		= $post_data->trf_applicant;
        $service 			= $post_data->trf_service_type;
        $applicant 			= $post_data->trf_applicant;
        $buyer 				= (array_key_exists('trf_buyer',$post_data) && $post_data->trf_buyer) ? $post_data->trf_buyer : 0;
        if($buyer > 0){
            $buyer_query = $this->db->select('customer_id')->where('basil_customer_details_id', $buyer)->get('cust_customers')->row_array();
            $buyer_id = $buyer_query['customer_id'];
        } else {
            $buyer_id = 0;
        }
        $agent 				= ($post_data->trf_agent == "Select Agent") ? 0 : $post_data->trf_agent;
        //$trf_contact		= $post_data->trf_contact;
        $trf_contact = (array_key_exists('trf_contact',$post_data) && $post_data->trf_contact)?$post_data->trf_contact:0;
        // if ($trf_contact > 0) {
        //         $trf_contact = implode(",", $post_data->trf_contact);
        // } else
        //         $trf_contact = '';

        $trf_thirdparty 	= $post_data->trf_thirdparty;
        $trf_sample_ref_id	= $post_data->trf_sample_ref_id;
        $trf_sample_desc	= $post_data->trf_sample_desc;
        $invoice_to			= $post_data->invoice_to;
        $invtocontant = (array_key_exists('trf_invoice_to_contact',$post_data) && $post_data->trf_invoice_to_contact)?$post_data->trf_invoice_to_contact:0;
        if ($invtocontant > 0) {
                $trf_invoice_to_contact = implode(",", $post_data->trf_invoice_to_contact);
        } else
                $trf_invoice_to_contact = '';
        $trf_no_of_sample	= $post_data->trf_no_of_sample;
        // $cc_id				= $post_data->cc_id;
        // $trf_cc 			= $post_data->trf_cc;
        // $bcc_id				= $post_data->bcc_id;
        // $trf_bcc			= $post_data->trf_bcc;
        $trf_client_ref_no	= $post_data->trf_client_ref_no;
        $trf_country_destination = $post_data->trf_country_destination;
        $trf_country_orgin	= $post_data->trf_country_orgin;
        $open_trf_currency_id = $post_data->open_trf_currency_id;
        $open_trf_exchange_rate = (array_key_exists('open_trf_exchange_rate',$post_data) && $post_data->open_trf_exchange_rate)?$post_data->open_trf_exchange_rate:0;
        $trf_product 		= $post_data->trf_product;
        $trf_end_use		= $post_data->trf_end_use;
        $tat_date			= (array_key_exists('tat_date',$post_data) && $post_data->tat_date)?$post_data->tat_date:NULL;
        $division			= $post_data->division;
        $reported_to		= $post_data->reported_to;	 	
        // $crm_user_id		= $post_data->crm_user_id;
        $sample_return_to	= $post_data->sample_return_to;
        $care_instruction 	= $post_data->dynamic;
        $sample_pickup_services = $post_data->sample_pickup_services;
        $dynamic			= $post_data->dynamic_field?$post_data->dynamic_field:''; 
        // $trf_branch			= ($post_data->trf_branch)?$post_data->trf_branch:0; 
        // $sales_person	    = $post_data->sales_person;
        // Check test name
        
        $test = $post_data->test;
        $trf_id = $post_data->trf_id; 
        $record = array(
                'trf_applicant'			            => $applicant,
                'trf_contact'			            => (is_array($trf_contact)) ? implode(",", $trf_contact) : $trf_contact,
                'trf_sample_ref_id'		            => $trf_sample_ref_id,
                'trf_invoice_to'		            => (is_array($invoice_to)) ? implode(",", $invoice_to) : $invoice_to,
                'trf_product'			            => $trf_product,
                'work_id'				            => 0,
                'trf_buyer'				            => $buyer_id,
                'trf_agent'				            => (isset($agent) && !empty($agent)) ? $agent : 0,
                'trf_sample_desc'		            => $trf_sample_desc,
                'trf_no_of_sample'		            => $trf_no_of_sample,
                'trf_country_destination'           => $trf_country_destination,
                'trf_end_use'			            => $trf_end_use,
                'trf_quote_id'			            => 0,
                'trf_client_ref_no'		            => $trf_client_ref_no,
                'quote_customer_id'		            => 0,
                'reported_to'			            => (is_array($reported_to)) ? implode(",", $reported_to) : $reported_to,
                'sample_return_to'		            => (is_array($sample_return_to)) ? implode(",", $sample_return_to) : $sample_return_to,
                'open_trf_currency_id'	            => $open_trf_currency_id,
                'open_trf_customer_id'	            => $customer_id,
                'open_trf_customer_type'            => 'Factory',
                'open_trf_exchange_rate'            => $open_trf_exchange_rate,
                'trf_thirdparty'		            => (isset($trf_thirdparty) && !empty($trf_thirdparty)) ? $trf_thirdparty : 0,
                'trf_country_orgin'		            => $trf_country_orgin,
                'tat_date'				            => (!empty($tat_date))?$tat_date:NULL,
                'trf_type'				            => "Open TRF",
                'division'				            => $division,
                'create_on'				            => date('Y-m-d H:i'),
                'updated_by'			            => 1,
                'sample_pickup_services'            => $sample_pickup_services,
                'clone_trf_id'			            => 0,    
                'trf_service_type'                  => $service
            );
            //    if ($service >= 2 && $service <= 30) {
            //     $record['service_days'] = $service;
            //     $record['trf_service_type'] = "Regular";
            // } else {
            //     $record['service_days'] = '';
            //     $record['trf_service_type'] = $service;
            // }

            // save application care provided instruction
            foreach ($care_instruction as $care) {
                $care_instructions[] = $care;
            }
            if (!empty($dynamic)) {
                foreach ($dynamic as $key => $dynamic_values) {
                    if (!empty($dynamic_values)) {
                        if($dynamic_values[0] == 'Style No.'){
                            $record['style_number'] = $dynamic_values[1];
                        }
                        $dyn_values[] = $dynamic_values;
                    } else {
                        $dyn_values = [];
                    }
                }
            } else {
                $dyn_values = [];
            }
        
        $save = $this->atm->update_open_trf($trf_id, $record, $dyn_values, $test, $care_instructions);
    
       if($save){
        $data['status'] = 1;
        $data['message']  = "TRF is updated";
       
     } else {
        $data['status'] = 0;
        $data['message'] = 'No record found!.';
     }
      $this->set_response($data, REST_Controller::HTTP_OK);
    }

    public function getSelectedCustomer_get($applicant){
        $data['applicant'] = $this->atm->getSelectedCustomer($applicant);
        if(!empty($data)){
            $data['status'] = 1;
            $data['message'] = 'Applicant List';
        } else {
            $data['status'] = 0;
            $data['message'] = 'No record found!.';
        }
        $this->set_response($data, REST_Controller::HTTP_OK);
    }

    public function getSelectedProduct_get($applicant){
        $data['products'] = $this->atm->getSelectedProduct($applicant);
        if(!empty($data)){
            $data['status'] = 1;
            $data['message'] = 'Product List';
        } else {
            $data['status'] = 0;
            $data['message'] = 'No record found!.';
        }
        $this->set_response($data, REST_Controller::HTTP_OK);
    }
    public function getdashboarddata_get($basil_customer_details_id){
        $data['dashboarddata'] = $this->atm->dashboarddata($basil_customer_details_id);
        if(!empty($data['dashboarddata'])){
            
            $data['status'] = 1;
            $data['message']  = "TRF Details";
           
         } else {
            $data['status'] = 0;
            $data['message'] = 'No record found!.';
         }
          $this->set_response($data, REST_Controller::HTTP_OK);
    }
}
