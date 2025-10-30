<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Opportunity extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('customer_management_model/Opportunity_model');
        $this->check_session();
        $checkUser = $this->session->userdata('user_data');
		$this->user = $checkUser->uidnr_admin;
    }

    public function index() {

        $where = NULL;
		$search = NULL;
		$sortby = NULL;
		$order = NULL;
		$from_date = NULL;
		$to_date = NULL;

        $type_customer = $this->uri->segment('4'); 
        $name_opportunity = $this->uri->segment('5'); 
        $types_opportunity = $this->uri->segment('6'); 
        $status_opportunity = $this->uri->segment('7'); 
        $created_by = $this->uri->segment('8'); 
        $from_date = $this->uri->segment('9');
		$to_date = $this->uri->segment('10');
        $search = $this->uri->segment('11'); 
        $sortby = $this->uri->segment('12'); 
        $order = $this->uri->segment('13'); 
        $page_no = $this->uri->segment('14'); 

        $base_url = 'customer_management/Opportunity/index';
        
        if ($type_customer != NULL  && $type_customer != 'NULL') {
            $data['type_customer'] = base64_decode($type_customer);
            $base_url  .= '/' . $type_customer;
            $where['opp.opportunity_customer_type'] = base64_decode($type_customer); 
        } else {
            $data['type_customer'] = 'NULL';
            $base_url  .= '/NULL';
        }
        if ($name_opportunity != NULL  && $name_opportunity != 'NULL') {
            $data['name_opportunity'] = base64_decode($name_opportunity);
            $base_url  .= '/' . $name_opportunity;
            $where['opp.opportunity_name'] = base64_decode($name_opportunity); 
        } else {
            $data['name_opportunity'] = 'NULL';
            $base_url  .= '/NULL';
        }
        if ($types_opportunity != NULL  && $types_opportunity != 'NULL') {
            $data['types_opportunity'] = base64_decode($types_opportunity);
            $base_url  .= '/' . $types_opportunity;
            $where['opp.types'] = base64_decode($types_opportunity); 
        } else {
            $data['types_opportunity'] = 'NULL';
            $base_url  .= '/NULL';
        }
        if ($status_opportunity != NULL  && $status_opportunity != 'NULL') {
            $data['status_opportunity'] = base64_decode($status_opportunity);
            $base_url  .= '/' . $status_opportunity;
            $where['opp.opportunity_status'] = base64_decode($status_opportunity); 
        } else {
            $data['status_opportunity'] = 'NULL';
            $base_url  .= '/NULL';
        }
        if ($created_by != NULL  && $created_by != 'NULL') {
            $data['created_by'] = base64_decode($created_by);
            $base_url  .= '/' . $created_by;
            $where['ap.uidnr_admin'] = base64_decode($created_by); 
        } else {
            $data['created_by'] = 'NULL';
            $base_url  .= '/NULL';
        }

        if ($from_date != NULL && $from_date != 'NULL') {
			$data['from_date'] =  base64_decode($from_date);
			$from_date = base64_decode($from_date);
			$where["DATE(opp.created_on) >= '$from_date' "] = null;
		} else {
			$base_url .= '/NULL';
			$data['from_date'] = NULL;
		}

		if ($to_date != NULL && $to_date != 'NULL') {
			$data['to_date'] =  base64_decode($to_date);
			$to_date = base64_decode($to_date);
			$where["DATE(opp.created_on) <= '$to_date'"] = null;
		} else {
			$base_url .= '/NULL';
			$data['to_date'] = NULL;
		}

        if ($search != NULL && $search != 'NULL') {
            $data['search'] =  base64_decode($search);
            $base_url .= '/' . $search;
            $search = base64_decode($search);
        } else {
            $base_url .= '/NULL';
            $data['search'] = '';
            $search = NULL;
        }
        if ($sortby != NULL && $sortby != 'NULL') {
            $base_url .= '/' . $sortby;
        } else {
            $base_url .= '/NULL';
            $sortby = NULL;
        }
        if ($order != NULL && $order != 'NULL') {
            $base_url .= '/' . $order;
        } else {
            $base_url .= '/NULL';
            $order = NULL;
        }
        $total_row = $this->Opportunity_model->fetch_opportunity_list(NULL, NULL, $search, NULL, NULL, $where, '1');
        $config = $this->pagination($base_url, $total_row,10,14);
        $data["links"] = $config["links"];
        $data['result'] = $this->Opportunity_model->fetch_opportunity_list($config["per_page"], $config['page'],$search,$sortby,$order, $where);
        $this->Opportunity_model->fetch_opportunity_list(NULL, NULL,$search,$sortby,$order, $where,1);
        $this->session->set_userdata('excel_data', $this->db->last_query());
        $start = (int)$page_no + 1;
        $end = (($data['result']) ? count($data['result']) : 0) + (($page_no) ? $page_no : 0);
        $data['result_count'] = "Showing " . $start . " - " . $end . " of " . $total_row . " Results";
        $data['opportunity_names'] = $this->Opportunity_model->fetch_opportunity_name();
        $data['created_by_name'] = $this->Opportunity_model->fetch_created_person();
        $data['opportunity_type'] = $this->Opportunity_model->fetch_opportunity_types();
        $data['opportunity_statuses'] = $this->Opportunity_model->fetch_opportunity_status();
        $data['opp_currency'] = $this->Opportunity_model->fetch_currency();
        $data['opp_quote_no'] = $this->Opportunity_model->fetch_quote_reference_data(); // added by millan on 14-10-2021
        if ($order == NULL || $order == 'NULL') {
            $data['order'] = ($order) ? "DESC" : "ASC";
        } else {
            $data['order'] = ($order == "ASC") ? "DESC" : "ASC";
        }
        $this->load_view('customer_management/manage_opportunity', $data);
    }

    public function load_add_opportunity(){
        $checkUser = $this->session->userdata('user_data');
        if (empty($checkUser->admin_email)) {
            redirect('default_controller');
        }
        $this->load_view('customer_management/add_opportunity_form');
    }

    public function won_mark_values_update(){
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
        $this->form_validation->set_rules('closure_value', 'Closure Value', 'required|trim');
        $this->form_validation->set_rules('closure_note', 'Closure Note', 'required|trim');
        if ($this->form_validation->run() == false) 
        {
            $data = array(
              'error' => $this->form_validation->error_array(),
              'status' => 0
            );
        }
        else{
            $input_data = $this->input->post();
            $checkUser = $this->session->userdata();
            if( !empty($input_data) ){
                $update_data['updated_by'] = $checkUser['user_data']->uidnr_admin;
                $update_data['updated_on'] = date('y-m-d h:i:s');
                $update_data['opportunity_id'] = $input_data['opportunity_id'];
                $update_data['closure_note'] = $input_data['closure_note'];
                $update_data['closure_value'] = $input_data['closure_value'];
                $update_values = $this->Opportunity_model->update_won_mark_values($update_data);
                if($update_values){

                   $log =  $this->user_log_update_OPPOTUNITY($update_data['opportunity_id'],'MARK AS WON OPPORTUNITY WITH REASON '.$update_data['closure_note'],'WON MARK VALUES UPDATE');
                   if($log){
                    $this->session->set_flashdata('success', 'Mark as Won Opportunity Closure Updated successfully');
                    $data = array(
                        'status' => 1,
                        'msg'=>'Mark as Won Opportunity Closure Updated successfully'
                    );
                   }
                   else{
                    $this->session->set_flashdata('success', 'error in generating log');
                    $data = array(
                        'status' => 0,
                        'msg'=>'error in generating log'
                    );
                   }
                   
                }
                else{
                    $this->session->set_flashdata('error', 'Error in Updating Mark as Won Opportunity Closure');
                    $data = array(
                        'status' => 0,
                        'msg'=>'Error in Updating Mark as Won Opportunity Closure'
                    );
                }
            }
            else{
                $this->session->set_flashdata('error', 'Record Not Found');
                $data = array(
                    'status' => 0,
                    'msg'=>'Record Not Found'
                );
            }
        }
        echo json_encode($data);
    }

    public function loss_mark_update(){
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
        $this->form_validation->set_rules('closure_note', 'Closure Note', 'required|trim');
        if ($this->form_validation->run() == false) 
        {
            $data = array(
              'error' => $this->form_validation->error_array(),
              'status' => 0
            );
           
        }
        else{
            $input_data = $this->input->post();
            $checkUser = $this->session->userdata();
            if( !empty($input_data) ){
                $update_data['updated_by'] = $checkUser['user_data']->uidnr_admin;
                $update_data['updated_on'] = date('y-m-d h:i:s');
                $update_data['opportunity_id'] = $input_data['opportunity_id'];
                $update_data['closure_note'] = $input_data['closure_note'];
                $update_values = $this->Opportunity_model->update_loss_mark($update_data);
                if($update_values){
                    $log =  $this->user_log_update_OPPOTUNITY($update_data['opportunity_id'],'MARK AS LOST OPPORTUNITY WITH REASON '.$update_data['closure_note'],'LOS MARK UPDATE');
                    if($log){
                        $this->session->set_flashdata('success', 'Mark as Lost Opportunity Closure Updated successfully');
                        $data = array(
                            'status' => 1,
                            'msg'=>'Mark as Lost Opportunity Closure Updated successfully'
                        );
                    }
                    else{
                        $this->session->set_flashdata('success', 'error in generating log');
                        $data = array(
                            'status' => 0,
                            'msg'=>'error in generating log'
                        );
                    }
                    
                }
                else{
                    $this->session->set_flashdata('error', 'Error in Updating Mark as Lost Opportunity Closure');
                    $data = array(
                        'status' => 0,
                        'msg'=>'Error in Updating Mark as Lost Opportunity Closure'
                    );
                }
            }
            else{
                $this->session->set_flashdata('error', 'Record Not Found');
                $data = array(
                    'status' => 0,
                    'msg'=>'Record Not Found'
                );
            }
        }
        echo json_encode($data);
    }
    
    public function extract_cust_name(){
        $fetch_data = $this->input->post();
        $fetch_type = $fetch_data['customer_type'];
        echo json_encode($this->Opportunity_model->extract_cust_name($fetch_type, $customer_id=NULL));
    }

    public function extract_cont_name(){
        $fetch_data = $this->input->post();
        $fetch_type = $fetch_data['custmer_id'];
        echo json_encode($this->Opportunity_model->extract_cont_name($fetch_type));
    }
    
    public function load_assign_to(){
		$designation_id2 = SALES_EXECUTIVE;
		$designation_id1 = SALES_MANAGER;
		$result = $this->Opportunity_model->op_assign_to($designation_id2,$designation_id1);
		echo json_encode($result);
	}

    public function add_opp_details()
    {
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
        $this->form_validation->set_rules('opportunity_customer_type', 'Customer Type', 'required|trim');
        $this->form_validation->set_rules('opportunity_customer_id', 'Customer Name', 'required');
        $this->form_validation->set_rules('opportunity_name', 'Opportunity Name', 'required|trim|is_unique[opportunity.opportunity_name]');
        $this->form_validation->set_rules('types', 'Types', 'required');
        $this->form_validation->set_rules('opportunity_value', 'Opportunity Value', 'required|trim');
        $this->form_validation->set_rules('currency_id', 'Currency', 'required');
        $this->form_validation->set_rules('estimated_closure_date', 'Estimated Closure Date', 'required');
        $this->form_validation->set_rules('description', 'Description', 'required|min_length[20]|trim');
        $this->form_validation->set_rules('opportunity_contact_id', 'Contact', 'required');
        $this->form_validation->set_rules('op_assigned_to', 'Assigned To', 'required');
        $this->form_validation->set_rules('quote_ref_no', 'Quote Reference No', 'required|trim|is_unique[opportunity.opp_quote_ref_no]|min_length[5]'); //added by millan on 14-10-2021
        if ($this->form_validation->run() == false) {
            $data = array(
                'error' => $this->form_validation->error_array(),
                'status' => 0
            );
        } else {
            $checkUser = $this->session->userdata();
            $store_data = array();
            $fetch_data = $this->input->post();
            $store_data['opportunity_customer_type'] = $fetch_data['opportunity_customer_type'];
            $store_data['opportunity_customer_id'] = $fetch_data['opportunity_customer_id'];
            $store_data['opportunity_name'] = $fetch_data['opportunity_name'];
            $store_data['types'] = $fetch_data['types'];
            $store_data['opportunity_value'] = $fetch_data['opportunity_value'];
            $store_data['currency_id'] = $fetch_data['currency_id'];
            $store_data['estimated_closure_date'] = $fetch_data['estimated_closure_date'];
            $store_data['description'] = $fetch_data['description'];
            $store_data['opportunity_contact_id'] = $fetch_data['opportunity_contact_id'];
            $store_data['op_assigned_to'] = $fetch_data['op_assigned_to'];
            $store_data['opp_quote_ref_no'] = $fetch_data['quote_ref_no']; // added by millan on 14-10-2021
            $store_data['created_by'] = $checkUser['user_data']->uidnr_admin;
            $store_data['created_on'] = date("Y-m-d") . " " . date("h:i:s");
            if (!empty($fetch_data)) {
                $insert_data = $this->Opportunity_model->insert_data('opportunity', $store_data);
                if($insert_data){
                    $log =  $this->user_log_update_OPPOTUNITY($insert_data,'OPPORTUNITY ADDED WITH NAME '.$store_data['opportunity_name'],'ADD OPPORTUNITY DETAILS');
                    if($log){
                        $this->session->set_flashdata('success', 'Opportunity added Successfully');
                        $data = array(
                            'status' => 1,
                            'msg'=>'Opportunity added Successfully'
                        );
                    }
                    else{
                        $this->session->set_flashdata('success', 'error in generating log');
                        $data = array(
                            'status' => 0,
                            'msg'=>'error in generating log'
                        );
                    }
                    
                    
                }
                else {
                    $this->session->set_flashdata('error', 'Error in adding Opportunity');
                    $data = array(
                        'status' => 0,
                        'msg'=>'Error in adding Opportunity'
                    );
                }
            } else {
                $this->session->set_flashdata('error', 'Data Not Found !!');
                $data = array(
                    'status' => 0,
                    'msg'=>'Data Not Found !!'
                );
            }
        }
        echo json_encode($data);
    }

    public function fetch_opp_details(){
        $id = $this->input->post();
        $opp_id = $id['opportunity_id'];
        $data = $this->Opportunity_model->fetch_opp_details($opp_id);
        echo json_encode($data);
    }

    public function edit_opp_details(){
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
        $this->form_validation->set_rules('opportunity_customer_type', 'Customer Type', 'required');
        $this->form_validation->set_rules('opportunity_customer_id', 'Customer Name', 'required');
        $this->form_validation->set_rules('opportunity_name', 'Opportunity Name', 'required');
        $this->form_validation->set_rules('types', 'Types', 'required');
        $this->form_validation->set_rules('opportunity_value', 'Opportunity Value', 'required');
        $this->form_validation->set_rules('currency_id', 'Currency', 'required');
        $this->form_validation->set_rules('estimated_closure_date', 'Estimated Closure Date', 'required');
        $this->form_validation->set_rules('description', 'Description', 'required|min_length[20]|trim');
        $this->form_validation->set_rules('opportunity_contact_id', 'Contact', 'required');
        $this->form_validation->set_rules('op_assigned_to', 'Assigned To', 'required');
        $this->form_validation->set_rules('quote_ref_no', 'Quote Reference No', 'required|trim|min_length[5]|callback_uniq_ref'); //added by millan on 14-10-2021
        if ($this->form_validation->run() == false) {
            $data = array(
                'error' => $this->form_validation->error_array(),
                'status' => 0
            );
        } else {
            $checkUser = $this->session->userdata();
            $data_get = $this->input->post();
            $update_data = array();
            $update_data['opportunity_customer_type'] = $data_get['opportunity_customer_type'];
            $update_data['opportunity_customer_id'] = $data_get['opportunity_customer_id'];
            $update_data['opportunity_name'] = $data_get['opportunity_name'];
            $update_data['types'] = $data_get['types'];
            $update_data['opportunity_value'] = $data_get['opportunity_value'];
            $update_data['currency_id'] = $data_get['currency_id'];
            $update_data['estimated_closure_date'] = $data_get['estimated_closure_date'];
            $update_data['opportunity_contact_id'] = $data_get['opportunity_contact_id'];
            $update_data['op_assigned_to'] = $data_get['op_assigned_to'];
            $update_data['description'] = $data_get['description'];
            $update_data['opp_quote_ref_no'] = $data_get['quote_ref_no']; // added by millan on 14-10-2021
            $update_data['updated_by'] = $checkUser['user_data']->uidnr_admin;
            $update_data['updated_on'] = date('y-m-d h:i:s');
            $where['opportunity_id'] = $data_get['opportunity_id'];
            if(!empty($data_get)){
                $data_updated = $this->Opportunity_model->update_data('opportunity', $update_data, $where);
                if($data_updated){

                    $log =  $this->user_log_update_OPPOTUNITY($where['opportunity_id'],'OPPORTUNITY UPDATED WITH NAME '.$update_data['opportunity_name'],'EDIT OPPORTUNITY DETAILS');
                    if($log){
                        $this->session->set_flashdata('success', 'Opportunity Updated Successfully');
                        $data = array(
                            'status' => 1,
                            'msg'=>'Opportunity Updated Successfully'
                        );
                    }
                    else{
                        $this->session->set_flashdata('success', 'error in generating log');
                        $data = array(
                            'status' => 0,
                            'msg'=>'error in generating log'
                        );
                    }
                   
                }
                else{
                    $this->session->set_flashdata('error', 'Error in Updating Opportunity');
                    $data = array(
                        'status' => 0,
                        'msg'=>'Error in Updating Opportunity'
                    );
                }
            }
            else{
                $this->session->set_flashdata('error', 'Data Not Found');
                $data = array(
                    'status' => 0,
                    'msg'=>'Data Not Found'
                );
            }
        }
        echo json_encode($data);
    }

    public function opportunity_data(){
        $query = $this->session->userdata('excel_data');
        if ($query) {
            $data = $this->Opportunity_model->opportunity_data_export($query);
            if ($data && count($data) > 0) {
                $this->load->library('excel');
                $tmpfname = "example.xls";
                $excelReader = PHPExcel_IOFactory::createReaderForFile($tmpfname);
                $objPHPExcel = $excelReader->load($tmpfname);
                $objPHPExcel->getProperties()->setCreator("GEO-CHEM")
                    ->setLastModifiedBy("GEO-CHEM")
                    ->setTitle("Office 2007 XLS Opportunity Document")
                    ->setSubject("Office 2007 XLS Opportunity Document")
                    ->setDescription("Description for Opportunity Document")
                    ->setKeywords("phpexcel office codeigniter php")
                    ->setCategory("Opportunity Filter file");
                $objPHPExcel->setActiveSheetIndex(0);
                $objPHPExcel->getActiveSheet()->setCellValue('A2', "SL NO.");
                $objPHPExcel->getActiveSheet()->setCellValue('B2', "OPPORTUNITY NAME");
                $objPHPExcel->getActiveSheet()->setCellValue('C2', "OPPORTUNITY VALUE");
                $objPHPExcel->getActiveSheet()->setCellValue('D2', "DESCRIPTION");
                $objPHPExcel->getActiveSheet()->setCellValue('E2', "TYPES");
                $objPHPExcel->getActiveSheet()->setCellValue('F2', "ESTIMATED CLOSURE DATE");
                $objPHPExcel->getActiveSheet()->setCellValue('G2', "CUSTOMER TYPE");
                $objPHPExcel->getActiveSheet()->setCellValue('H2', "CUSTOMER NAME");
                $objPHPExcel->getActiveSheet()->setCellValue('I2', "CONTACT NAME");
                $objPHPExcel->getActiveSheet()->setCellValue('J2', "CURRENCY");
                $objPHPExcel->getActiveSheet()->setCellValue('K2', "OPPORTUNITY STATUS");
                $objPHPExcel->getActiveSheet()->setCellValue('L2', "CREATED BY");
                $objPHPExcel->getActiveSheet()->setCellValue('M2', "CREATED ON");

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
                $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);

                $i = 3;
                foreach ($data as $key => $value) {
                    $objPHPExcel->getActiveSheet()->setCellValue('A' . $i, ($i - 2));
                    $objPHPExcel->getActiveSheet()->setCellValue('B' . $i, ($value->opportunity_name) ? $value->opportunity_name : '');
                    $objPHPExcel->getActiveSheet()->setCellValue('C' . $i, ($value->opportunity_value) ? $value->opportunity_value : '');
                    $objPHPExcel->getActiveSheet()->setCellValue('D' . $i, ($value->description) ? $value->description : '');
                    $objPHPExcel->getActiveSheet()->setCellValue('E' . $i, ($value->types) ? $value->types : '');
                    $objPHPExcel->getActiveSheet()->setCellValue('F' . $i, ($value->estimated_closure_date) ? $value->estimated_closure_date : '');
                    $objPHPExcel->getActiveSheet()->setCellValue('G' . $i, ($value->opportunity_customer_type) ? $value->opportunity_customer_type : '');
                    $objPHPExcel->getActiveSheet()->setCellValue('H' . $i, ($value->customer_name) ? $value->customer_name : '');
                    $objPHPExcel->getActiveSheet()->setCellValue('I' . $i, ($value->contact_name) ? $value->contact_name : '');
                    $objPHPExcel->getActiveSheet()->setCellValue('J' . $i, ($value->currency_name) ? $value->currency_name : '');
                    $objPHPExcel->getActiveSheet()->setCellValue('K' . $i, ($value->opportunity_status) ? $value->opportunity_status : '');
                    $objPHPExcel->getActiveSheet()->setCellValue('L' . $i, ($value->created_by) ? $value->created_by : '');
                    $objPHPExcel->getActiveSheet()->setCellValue('M' . $i, ($value->created_on) ? $value->created_on : '');
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
                $objPHPExcel->getActiveSheet()->mergeCells('A1:L1');
                $objPHPExcel->getActiveSheet()->setCellValue('A1', "GEO CHEM OPPORTUNITY DETAILS");
                $objPHPExcel->getActiveSheet()->getStyle('A1:L1')->applyFromArray($stil);

                $filename = 'Opp_Details_Filter-' . date('Y-m-d-s') . ".xls";
                $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
                ob_end_clean();
                header('Content-type: application/vnd.ms-excel');
                header('Content-Disposition: attachment; filename=' . $filename);
                $objWriter->save('php://output');
            }
        }
    }

    public function get_user_log_data()
	{
		$opportunity_id = $this->input->post('opportunity_id');
		$data = $this->Opportunity_model->get_OPPOTUNITY_log($opportunity_id);
		echo json_encode($data);
	}

    public function user_log_update_OPPOTUNITY($opportunity_id,$text,$action){
		$data = array();
		$data['source_module'] = 'Opportunity';
		$data['record_id'] = $opportunity_id;
		$data['created_on'] = date("Y-m-d h:i:s");
		$data['created_by'] = $this->user;
		$data['action_taken'] = $action;
		$data['text'] = $text;

		$result = $this->Opportunity_model->insert_data('user_log_history',$data);
		if($result){
			return true;
		}
		else{
			return false;
		}
    }

    // added by prashant on 08-10-2021
    public function fetch_communication_details(){
        $fetch_data = $this->input->post();
        $fetch_id = $fetch_data['opportunity_id'];
        if(!empty($fetch_data)){
            $data = $this->Opportunity_model->fetch_communication_details($fetch_id);
            if($data){
                echo json_encode($data);
            }
            else{
                echo json_encode(false);
            }
        }
    }

    // added by millan on 14-10-2021
    public function get_quote_reference()
    {
        $key = ($this->input->get('key')) ? $this->input->get('key') : null;
        $data = $this->Opportunity_model->get_quote_reference($key);
        echo json_encode($data);
    }

    // added by millan on 14-10-2021
    public function uniq_ref($field){
        $update_form = $this->input->post();
        $check_fileds = array();
        $check_fileds['LOWER(opp_quote_ref_no)'] = strtolower($update_form['quote_ref_no']);
        $check_fileds['LOWER(opportunity_name) '] = strtolower($update_form['opportunity_name']);
        $check_fileds['opportunity_customer_id NOT IN ('.$update_form['opportunity_customer_id'].')'] =  NULL;
        $check_fileds['opportunity_id NOT IN ('.$update_form['opportunity_id'].')'] =  NULL;
        if(!empty($update_form) && !empty($update_form['opportunity_id']) ){
            $check_in = $this->Opportunity_model->get_row('*', 'opportunity' , $check_fileds);
            if($check_in){
                $this->form_validation->set_message('uniq_ref', 'The {field} can not be the same. "It Must be Unique!!"');
                return FALSE;
            }
            else
            {
                return TRUE;
            }
        }
        else{
            return false;
        }
    }
}
