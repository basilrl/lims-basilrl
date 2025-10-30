<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Communication extends MY_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('customer_management_model/Communication_model');
        $this->check_session();
        $checkUser = $this->session->userdata('user_data');
		$this->user = $checkUser->uidnr_admin;
    }

    public function index()
    {

        $where = NULL;
		$search = NULL;
		$sortby = NULL;
		$order = NULL;
		$from_date = NULL;
		$to_date = NULL;

      
        $type_customer = $this->uri->segment('4');
        $id_customer = $this->uri->segment('5');
        $name_contact = $this->uri->segment('6');
        $connect_to = $this->uri->segment('7');
        $opportunity_name = $this->uri->segment('8');
        $created_by = $this->uri->segment('9');
        $from_date = $this->uri->segment('10');
		$to_date = $this->uri->segment('11');
        $search = $this->uri->segment('12');
        $sortby = $this->uri->segment('13');
        $order = $this->uri->segment('14');
        $page_no = $this->uri->segment('15');

        $base_url = 'customer_management/Communication/index';
        if ($type_customer != NULL  && $type_customer != 'NULL') {
            $data['type_customer'] = base64_decode($type_customer);
            $base_url  .= '/' . $type_customer;
            $where['comm.customer_type'] = base64_decode($type_customer);
        } else {
            $data['type_customer'] = 'NULL';
            $base_url  .= '/NULL';
        }
        if ($id_customer != NULL  && $id_customer != 'NULL') {
            $data['id_customer'] = base64_decode($id_customer);
            $base_url  .= '/' . $id_customer;
            $where['cust.customer_id'] = base64_decode($id_customer);
        } else {
            $data['id_customer'] = 'NULL';
            $base_url  .= '/NULL';
        }
        if ($name_contact != NULL  && $name_contact != 'NULL') {
            $data['name_contact'] = base64_decode($name_contact);
            $base_url  .= '/' . $name_contact;
            $where['contact.contact_id'] = base64_decode($name_contact);
        } else {
            $data['name_contact'] = 'NULL';
            $base_url  .= '/NULL';
        }
        if ($connect_to != NULL  && $connect_to != 'NULL') {
            $data['connect_to'] = base64_decode($connect_to);
            $base_url  .= '/' . $connect_to;
            $where['comm.connected_to'] = base64_decode($connect_to);
        } else {
            $data['connect_to'] = 'NULL';
            $base_url  .= '/NULL';
        }
        if ($opportunity_name != NULL  && $opportunity_name != 'NULL') {
            $data['opportunity_name'] = base64_decode($opportunity_name);
            $base_url  .= '/' . $opportunity_name;
            $where['opportunity.opportunity_id'] = base64_decode($opportunity_name);
        } else {
            $data['opportunity_name'] = 'NULL';
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
			$where["DATE(comm.created_on) >= '$from_date' "] = null;
		} else {
			$base_url .= '/NULL';
			$data['from_date'] = NULL;
		}

		if ($to_date != NULL && $to_date != 'NULL') {
			$data['to_date'] =  base64_decode($to_date);
			$to_date = base64_decode($to_date);
			$where["DATE(comm.created_on) <= '$to_date'"] = null;
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
        $total_row = $this->Communication_model->fetch_communication_list(NULL, NULL, $search, NULL, NULL, $where, '1');
        $config = $this->pagination($base_url, $total_row, 10, 15);
        $data["links"] = $config["links"];
        $data['result'] = $this->Communication_model->fetch_communication_list($config["per_page"], $config['page'], $search, $sortby, $order, $where);
        $this->Communication_model->fetch_communication_list(NULL, NULL, $search, $sortby, $order, $where, 1);
        $this->session->set_userdata('excel_data', $this->db->last_query());
        $data['cust_name'] = $this->Communication_model->fetch_cust_name();
        $data['contact_name'] = $this->Communication_model->fetch_contact_name();
        $data['created_by_name'] = $this->Communication_model->fetch_created_person();
        $data['comm_mediums'] = $this->Communication_model->fetch_comm_medium();
        $start = (int)$page_no + 1;
        $end = (($data['result']) ? count($data['result']) : 0) + (($page_no) ? $page_no : 0);
        $data['result_count'] = "Showing " . $start . " - " . $end . " of " . $total_row . " Results";
        if ($order == NULL || $order == 'NULL') {
            $data['order'] = ($order) ? "DESC" : "ASC";
        } else {
            $data['order'] = ($order == "ASC") ? "DESC" : "ASC";
        }
        $this->load_view('customer_management/manage_communication', $data);
    }

    public function communication_status()
    {
        if (!empty($this->input->post())) {
            $status = $this->Communication_model->update_communication_status($this->input->post());
            if ($status) {
                $this->session->set_flashdata('success', 'Contact Status Updated sucessfully');
                echo json_encode(array('msg' => 'upadte status'));
            } else {
                $this->session->set_flashdata('error', 'Error in Updating Contact Status');
                echo json_encode(array('msg' => 'not upadte status'));
            }
        }
    }

    public function extract_cust_name()
    {
        $fetch_data = $this->input->post();
        $fetch_type = $fetch_data['customer_type'];
        echo json_encode($this->Communication_model->extract_cust_name($fetch_type));
    }
    //Added by Prashant 01-10-2021-----------
    public function fetch_extract_cust_name()
    {
        $fetch_data = $this->input->post();
        $fetch_type = $fetch_data['customer_type'];
        $fetch_cust_id = $fetch_data['customer_id'];
        echo json_encode($this->Communication_model->fetch_extract_cust_name($fetch_type, $fetch_cust_id));
    }
    
    public function extract_cont_name(){
        $fetch_data = $this->input->post();
        $fetch_type = $fetch_data['customer_id'];
        echo json_encode($this->Communication_model->extract_cont_name($fetch_type));
    }
    //Added by Prashant 01-10-2021-----------
    public function fetch_extract_cont_name(){
        $fetch_data = $this->input->post();
        $fetch_cust_id = $fetch_data['cust_id'];
        $fetch_contact_id = $fetch_data['contact_id'];
        echo json_encode($this->Communication_model->fetch_extract_cont_name($fetch_cust_id, $fetch_contact_id));
    }

    public function add_comm_details(){
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
        $this->form_validation->set_rules('customer_type', 'Customer Type', 'required');
        $this->form_validation->set_rules('comm_communications_customer_id', 'Customer Name', 'required');
        $this->form_validation->set_rules('communication_mode', 'Communication Mode', 'required');
        $this->form_validation->set_rules('comm_communications_contact_id', 'Contact Name', 'required');
        $this->form_validation->set_rules('date_of_communication', 'Date of Communication', 'required');
        $this->form_validation->set_rules('follow_up_date', 'Follow Up Date', 'required');
        $this->form_validation->set_rules('medium', 'Medium', 'required');
        $this->form_validation->set_rules('connected_to', 'Connected to', 'required');
        $this->form_validation->set_rules('subject', 'Subject', 'required|trim');
        $this->form_validation->set_rules('note', 'note', 'required|trim');
        if ($this->form_validation->run() == false) {
            $data = array(
                'error' => $this->form_validation->error_array(),
                'status' => 0
            );
        } else {
            $checkUser = $this->session->userdata();
            $store_data = array();
            $fetch_data = $this->input->post();
            $store_data['customer_type'] = $fetch_data['customer_type'];
            $store_data['comm_communications_customer_id'] = $fetch_data['comm_communications_customer_id'];
            $store_data['communication_mode'] = $fetch_data['communication_mode'];
            $store_data['comm_communications_contact_id'] = $fetch_data['comm_communications_contact_id'];
            $store_data['date_of_communication'] = $fetch_data['date_of_communication'];
            $store_data['follow_up_date'] = $fetch_data['follow_up_date'];
            $store_data['medium'] = $fetch_data['medium'];
            $store_data['connected_to'] = $fetch_data['connected_to'];
            $store_data['comm_communications_opportunity_id'] = $fetch_data['opportunity'];
            $store_data['subject'] = $fetch_data['subject'];
            $store_data['note'] = $fetch_data['note'];
            $store_data['created_by'] = $checkUser['user_data']->uidnr_admin;
            $store_data['created_on'] = date("Y-m-d") . " " . date("h:i:s");
            if ($fetch_data['medium'] == "Others") {
                $store_data['other_medium'] = $fetch_data['other_medium'];
            } else {
                $store_data['other_medium'] = "";
            }
            if (!empty($fetch_data)) {
                $insert_data = $this->Communication_model->insert_data('comm_communications', $store_data);
                if ($insert_data) {
                    $log = $this->user_log_update_COMMUNICATION($insert_data,'CUMMUNICATION ADDED WITH SUBJECT '.$store_data['subject'],'ADD COMMUNICATION');
                    if($log){
                        $this->session->set_flashdata('success', 'Communication added Successfully');
                        $data = array(
                            'status' => 1,
                            'msg'=>'Communication added Successfully'
                        );
                    }
                    else{
                        $this->session->set_flashdata('error', 'error in generating log');
                        $data = array(
                            'status' => 0,
                            'msg'=>'error in generating log'
                        ); 
                    }
                    
                } else {
                    $this->session->set_flashdata('error', 'Error in adding Communication');
                    $data = array(
                        'status' => 0,
                        'msg'=>'Error in adding Communication'
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

    public function fetch_comm_details()
    {
        $id = $this->input->post();
        $comm_id = $id['communication_id'];
        $data = $this->Communication_model->fetch_comm_details($comm_id);
        echo json_encode($data);
    }
    public function fetch_comm_add_details()
    {
        $id = $this->input->post();
        $comm_id = $id['communication_id'];
        $data = $this->Communication_model->fetch_comm_add_details($comm_id);
        echo json_encode($data);
    }

    public function edit_communication_details(){
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
        $this->form_validation->set_rules('customer_type', 'Customer Type', 'required');
        $this->form_validation->set_rules('comm_communications_customer_id', 'Customer Name', 'required');
        $this->form_validation->set_rules('communication_mode', 'Communication Mode', 'required');
        $this->form_validation->set_rules('comm_communications_contact_id', 'Contact Name', 'required');
        $this->form_validation->set_rules('date_of_communication', 'Date of Communication', 'required');
        $this->form_validation->set_rules('follow_up_date', 'Follow Up Date', 'required');
        $this->form_validation->set_rules('medium', 'Medium', 'required');
        $this->form_validation->set_rules('connected_to', 'Connected to', 'required');
        $this->form_validation->set_rules('subject', 'Subject', 'required|trim');
        $this->form_validation->set_rules('note', 'note', 'required|trim');
        if ($this->form_validation->run() == false) {
            $data = array(
                'error' => $this->form_validation->error_array(),
                'status' => 0
            );
        } else {
            $checkUser = $this->session->userdata();
            $store_data = array();
            $fetch_data = $this->input->post();
            $store_data['customer_type'] = $fetch_data['customer_type'];
            $store_data['comm_communications_customer_id'] = $fetch_data['comm_communications_customer_id'];
            $store_data['communication_mode'] = $fetch_data['communication_mode'];
            $store_data['comm_communications_contact_id'] = $fetch_data['comm_communications_contact_id'];
            $store_data['date_of_communication'] = $fetch_data['date_of_communication'];
            $store_data['follow_up_date'] = $fetch_data['follow_up_date'];
            $store_data['medium'] = $fetch_data['medium'];
            $store_data['connected_to'] = $fetch_data['connected_to'];
            $store_data['comm_communications_opportunity_id'] = $fetch_data['opportunity'];
            $store_data['subject'] = $fetch_data['subject'];
            $store_data['note'] = $fetch_data['note'];
            $store_data['created_by'] = $checkUser['user_data']->uidnr_admin;
            $store_data['updated_on'] = date("Y-m-d") . " " . date("h:i:s");
            $where['communication_id'] = $fetch_data['communication_id'];
            if ($fetch_data['medium'] == "Others") {
                $store_data['other_medium'] = $fetch_data['other_medium'];
            } else {
                $store_data['other_medium'] = "";
            }
            if (!empty($fetch_data)) {
                $data_updated = $this->Communication_model->update_data('comm_communications', $store_data, $where);
                if ($data_updated) {

                    $log = $this->user_log_update_COMMUNICATION($fetch_data['communication_id'],'CUMMUNICATION UPDATED WITH SUBJECT '.$fetch_data['subject'],'EDIT COMMUNICATION');
                   if($log){
                        $this->session->set_flashdata('success', 'Communication Updated Successfully');
                        $data = array(
                            'status' => 1,
                            'msg'=>'Communication Updated Successfully'
                        );
                   }
                   else{
                        $this->session->set_flashdata('error', 'error in generating log');
                        $data = array(
                            'status' => 0,
                            'msg'=>'error in generating log'
                        );
                   }
                } else {
                    $this->session->set_flashdata('error', 'Error in Updating Communication');
                    $data = array(
                        'status' => 0
                    );
                }
            } else {
                $this->session->set_flashdata('error', 'Data Not Found !!');
                $data = array(
                    'status' => 0
                );
            }
        }
        echo json_encode($data);
    }

    public function communication_data(){
        $query = $this->session->userdata('excel_data');
        if ($query) {
            $data = $this->Communication_model->communication_data_export($query);
            if ($data && count($data) > 0) {
                $this->load->library('excel');
                $tmpfname = "example.xls";
                $excelReader = PHPExcel_IOFactory::createReaderForFile($tmpfname);
                $objPHPExcel = $excelReader->load($tmpfname);
                $objPHPExcel->getProperties()->setCreator("GEO-CHEM")
                    ->setLastModifiedBy("GEO-CHEM")
                    ->setTitle("Office 2007 XLS Communication Document")
                    ->setSubject("Office 2007 XLS Communication Document")
                    ->setDescription("Description for Communication Document")
                    ->setKeywords("phpexcel office codeigniter php")
                    ->setCategory("Communication Filter file");
                $objPHPExcel->setActiveSheetIndex(0);
                $objPHPExcel->getActiveSheet()->setCellValue('A2', "SL NO.");
                $objPHPExcel->getActiveSheet()->setCellValue('B2', "SUBJECT");
                $objPHPExcel->getActiveSheet()->setCellValue('C2', "NOTE");
                $objPHPExcel->getActiveSheet()->setCellValue('D2', "DATE OF COMMUNICATION");
                $objPHPExcel->getActiveSheet()->setCellValue('E2', "MEDIUM");
                $objPHPExcel->getActiveSheet()->setCellValue('F2', "CUSTOMER NAME");
                $objPHPExcel->getActiveSheet()->setCellValue('G2', "CUSTOMER TYPE");
                $objPHPExcel->getActiveSheet()->setCellValue('H2', "COMMUNICATION MODE");
                $objPHPExcel->getActiveSheet()->setCellValue('I2', "CONTACT NAME");
                $objPHPExcel->getActiveSheet()->setCellValue('J2', "FOLLOW UP DATE");
                $objPHPExcel->getActiveSheet()->setCellValue('K2', "CREATED BY");
                $objPHPExcel->getActiveSheet()->setCellValue('L2', "CREATED ON");

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

                $i = 3;
                foreach ($data as $key => $value) {
                    $objPHPExcel->getActiveSheet()->setCellValue('A' . $i, ($i - 2));
                    $objPHPExcel->getActiveSheet()->setCellValue('B' . $i, ($value->subject) ? $value->subject : '');
                    $objPHPExcel->getActiveSheet()->setCellValue('C' . $i, ($value->note) ? $value->note : '');
                    $objPHPExcel->getActiveSheet()->setCellValue('D' . $i, ($value->date_of_communication) ? $value->date_of_communication : '');
                    $objPHPExcel->getActiveSheet()->setCellValue('E' . $i, ($value->medium) ? $value->medium : '');
                    $objPHPExcel->getActiveSheet()->setCellValue('F' . $i, ($value->customer_name) ? $value->customer_name : '');
                    $objPHPExcel->getActiveSheet()->setCellValue('G' . $i, ($value->customer_type) ? $value->customer_type : '');
                    $objPHPExcel->getActiveSheet()->setCellValue('H' . $i, ($value->communication_mode) ? $value->communication_mode : '');
                    $objPHPExcel->getActiveSheet()->setCellValue('I' . $i, ($value->contact_name) ? $value->contact_name : '');
                    $objPHPExcel->getActiveSheet()->setCellValue('J' . $i, ($value->follow_up_date) ? $value->follow_up_date : '');
                    $objPHPExcel->getActiveSheet()->setCellValue('K' . $i, ($value->created_by) ? $value->created_by : '');
                    $objPHPExcel->getActiveSheet()->setCellValue('L' . $i, ($value->created_on) ? $value->created_on : '');
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
                $objPHPExcel->getActiveSheet()->setCellValue('A1', "GEO CHEM COMMUNICATION DETAILS");
                $objPHPExcel->getActiveSheet()->getStyle('A1:L1')->applyFromArray($stil);

                $filename = 'Comm_Details_Filter-' . date('Y-m-d-s') . ".xls";
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
		$communication_id = $this->input->post('communication_id');
		$data = $this->Communication_model->get_communication_log_COMMUNICATIONS($communication_id);
		echo json_encode($data);
	}

    public function user_log_update_COMMUNICATION($communication_id,$text,$action){
		$data = array();
		$data['source_module'] = 'Communication';
		$data['record_id'] = $communication_id;
		$data['created_on'] = date("Y-m-d h:i:s");
		$data['created_by'] = $this->user;
		$data['action_taken'] = $action;
		$data['text'] = $text;

		$result = $this->Communication_model->insert_data('user_log_history',$data);
		if($result){
			return true;
		}
		else{
			return false;
		}
    }
    public function opportunity_name(){
    $res = $this->Communication_model->fetch_opportunity_name();
    echo json_encode($res);

    }
    public function add_opportunity_name(){
        $res = $this->Communication_model->fetch_add_opportunity_name();
        echo json_encode($res);
    
        }
    
    public function fetch_opportunity_details(){
        $fetch_data = $this->input->post();
        $fetch_id = $fetch_data['communication_id'];
        if(!empty($fetch_data)){
            $data = $this->Communication_model->fetch_opportunity_details($fetch_id);
            if($data){
                echo json_encode($data);
            }
            else{
                echo json_encode(false);
            }
        }
    }
    public function add_fetch_communication_data(){
        $fetch_data = $this->input->post();
        $fetch_id = $fetch_data['communication_id'];
        if(!empty($fetch_data)){
            $data = $this->Communication_model->add_fetch_communication_data($fetch_id);
            if($data){
                echo json_encode($data);
            }
            else{
                echo json_encode(false);
            }
        }
    }
    public function add_communication(){
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
        $this->form_validation->set_rules('customer_type', 'Customer Type', 'required');
        $this->form_validation->set_rules('comm_communications_customer_id', 'Customer Name', 'required');
        $this->form_validation->set_rules('communication_mode', 'Communication Mode', 'required');
        $this->form_validation->set_rules('comm_communications_contact_id', 'Contact Name', 'required');
        $this->form_validation->set_rules('date_of_communication', 'Date of Communication', 'required');
        $this->form_validation->set_rules('follow_up_date', 'Follow Up Date', 'required');
        $this->form_validation->set_rules('medium', 'Medium', 'required');
        $this->form_validation->set_rules('connected_to', 'Connected to', 'required');
        $this->form_validation->set_rules('subject', 'Subject', 'required|trim');
        $this->form_validation->set_rules('note', 'note', 'required|trim');
        if ($this->form_validation->run() == false) {
            $data = array(
                'error' => $this->form_validation->error_array(),
                'status' => 0
            );
        } else {
            $checkUser = $this->session->userdata();
            $store_data = array();
            $fetch_data = $this->input->post();
            $store_data['parent_commu_id'] = $fetch_data['communication_id'];
            $store_data['customer_type'] = $fetch_data['customer_type'];
            $store_data['comm_communications_customer_id'] = $fetch_data['comm_communications_customer_id'];
            $store_data['communication_mode'] = $fetch_data['communication_mode'];
            $store_data['comm_communications_contact_id'] = $fetch_data['comm_communications_contact_id'];
            $store_data['date_of_communication'] = $fetch_data['date_of_communication'];
            $store_data['follow_up_date'] = $fetch_data['follow_up_date'];
            $store_data['medium'] = $fetch_data['medium'];
            $store_data['connected_to'] = $fetch_data['connected_to'];
            $store_data['comm_communications_opportunity_id'] = $fetch_data['opportunity'];
            $store_data['subject'] = $fetch_data['subject'];
            $store_data['note'] = $fetch_data['note'];
            $store_data['created_by'] = $checkUser['user_data']->uidnr_admin;
            $store_data['created_on'] = date("Y-m-d") . " " . date("h:i:s");
            if ($fetch_data['medium'] == "Others") {
                $store_data['other_medium'] = $fetch_data['other_medium'];
            } else {
                $store_data['other_medium'] = "";
            }
            
            if (!empty($fetch_data)) {
                $insert_data = $this->Communication_model->insert_data('comm_communications', $store_data);
                if ($insert_data) {
                    $log = $this->user_log_update_COMMUNICATION($insert_data,'CUMMUNICATION ADDED WITH SUBJECT '.$store_data['subject'],'ADD COMMUNICATION');
                    if($log){
                        $this->session->set_flashdata('success', 'Communication added Successfully');
                        $data = array(
                            'status' => 1,
                            'msg'=>'Communication added Successfully'
                        );
                    }
                    else{
                        $this->session->set_flashdata('error', 'error in generating log');
                        $data = array(
                            'status' => 0,
                            'msg'=>'error in generating log'
                        ); 
                    }
                    
                } else {
                    $this->session->set_flashdata('error', 'Error in adding Communication');
                    $data = array(
                        'status' => 0,
                        'msg'=>'Error in adding Communication'
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
}
