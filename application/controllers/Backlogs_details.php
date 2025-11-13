<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Backlogs_details extends MY_Controller {

	public function __construct(){
		parent::__construct();
		$this->check_session();
        $this->load->model('Backlogs_model', 'bcm');
        set_time_limit(0);
        ini_set('memory_limit', '-1');
	}

    public function view_due_data($grid = false){

        // $data=array();
        // $data['back_log_data'] = $data['today_log'] =  $data['upcoming_log'] = '';
        $data = array();
        $data['back_log_data'] = array(); 
        $data['today_log'] = array();   
        $data['upcoming_log'] = array();
        $startBackLogDate='2019-01-01';
        $today=date("Y-m-d");
        $nextDay=date("Y-m-d",strtotime($today." 1 day"));
        $divisions = $this->bcm->get_result("*","mst_divisions",['status'=>'1']);
        if($divisions && count($divisions)>0){
            foreach($divisions as $key=>$value){
                $where = array(
                    "sr.released_to_client"=>"0",
                     "sr.division_id"=>$value->division_id,
                     "DATE(sr.due_date) >= '$startBackLogDate'"=>NULL,
                     "DATE(sr.due_date) <= '$today'"=>NULL
                    );
                    $back_log_data = $this->bcm->get_due_data($where);
               
                if($back_log_data){
                    $data['back_log_data'][$value->division_name] = $back_log_data;
                }

                $where1 = array(
                    "sr.released_to_client"=>"0",
                     "sr.division_id"=>$value->division_id,
                     "DATE(sr.due_date)" => $today
                  );
                
                $today_log = $this->bcm->get_due_data($where1);

                if($today_log){
                    $data['today_log'] = $today_log;
                }
                
                $where2 = array(
                    "sr.released_to_client"=>"0",
                     "sr.division_id"=>$value->division_id,
                     "DATE(sr.due_date)" => $nextDay
                  );
                
                $upcoming_log = $this->bcm->get_due_data($where2);
                if($upcoming_log){
                    $data['upcoming_log'] = $upcoming_log;
                }
              
            }
           
        }
      
        if($grid){
            echo json_encode($data);
        }
        else{
            
            $this->load->view('includes/due_slider.php',$data);
        }

    }

    public function get_due_dates_gc_no(){
      
        $limit = $this->input->post('limit');
        $offset = $this->input->post('offset');
        $startBackLogDate='2019-01-01';
        $today=date("Y-m-d");
      
        
        $where = array(
            "sr.released_to_client"=>"0",
             "DATE(sr.due_date) >= '$startBackLogDate'"=>NULL,
             "DATE(sr.due_date) <= '$today'"=>NULL
            );
        $back_log_data = $this->bcm->get_due_data_onlY_gc($where,$limit,$offset);
       echo json_encode($back_log_data);
    }

    public function get_sample_details_due(){
        $sample_reg_id = $this->input->post('sample_reg_id');
        $data = $this->bcm->due_gc_sample_details($sample_reg_id);

        if(!array_key_exists('sample_desc',$data)){
            $data->sample_desc = '-';
        }
        if(!array_key_exists('seal_no',$data)){
            $data->seal_no = '-';
        }
        if(!array_key_exists('qty_received',$data)){
            $data->qty_received = '-';
        }
        if(!array_key_exists('unit_name',$data)){
            $data->unit_name = '-';
        }
        if(!array_key_exists('barcode',$data)){
            $data->barcode = '-';
        }
        if(!array_key_exists('test_specification',$data)){
            $data->test_specification = '-';
        }
        if(!array_key_exists('client',$data)){
            $data->client = '-';
        }
        if(!array_key_exists('branch_name',$data)){
            $data->branch_name = '-';
        }
        if(!array_key_exists('contact',$data)){
            $data->contact = '-';
        }
        if(!array_key_exists('create_by',$data)){
            $data->create_by = '-';
        }
        if(!array_key_exists('collection_time',$data)){
            $data->collection_time = '-';
        }
        if(!array_key_exists('price_type',$data)){
            $data->price_type = '-';
        }

        if(!array_key_exists('quantity_desc',$data)){
            $data->quantity_desc = '-';
        }

        if(!array_key_exists('status',$data)){
            $data->status = '-';
        }
        if(!array_key_exists('tat_date',$data) || $data->tat_date == 'null' || $data->tat_date == 'NULL' || $data->tat_date ==""){
            $data->tat_date = '-';
        }
        if(!array_key_exists('sample_retain_period',$data)){
            $data->sample_retain_period = '-';
        }
        if(!array_key_exists('sample_type_name',$data)){
            $data->sample_type_name = '-';
        }
        if(!array_key_exists('due_date',$data)){
            $data->due_date = '-';
        }

        echo json_encode($data);
    }


    public function get_division_wise_backlog()
    {
        $startBackLogDate = '2019-01-01';
        $today = date("Y-m-d");
        $division_id = $this->input->post('division_dropdown');
        $start_date = $this->input->post('start_date');
        $end_date = $this->input->post('end_date');
        $buyer = $this->input->post('buyer');
        $agent = $this->input->post('agent');
        $labType = $this->input->post('labType');
        $customer = $this->input->post('customer');
        $offset = $this->input->post('offset');
        $limit = $this->input->post('limit');
        // $sample_reg_id = $this->input->post('sample_reg_id');
        $year = $this->input->post('year');
        $month = $this->input->post('month');
        //    report review
        $report_reviewer = $this->input->post('report_reviewer');
        if ($report_reviewer != "" && $report_reviewer != null) {
            $where['sr.report_reviewer_id'] = $report_reviewer;
        }
        if ($year != "" && $month != "") {
            $where["YEAR(sr.create_on)"] = $year;
            $where["MONTH(sr.create_on)"] = $month;
        }
        if ($start_date != "" && $start_date != null && $end_date != "" && $end_date != null ) {
            $where["DATE(sr.create_on) >= '$start_date'"] = NULL;
            $where["DATE(sr.create_on) <= '$end_date'"] = NULL;
        }
        if ($buyer != "" && $buyer != null) {
            $where['tr.trf_buyer'] = $buyer;
        }
        if ($agent != "" && $agent != null) {
            $where['tr.trf_applicant'] = $agent;
        }
        if ($labType != "" && $labType != null) {
            $where['sr.sample_registered_to_lab_id'] = $labType;
        }
        if ($customer != "" && $customer != null) {
            $where['sr.sample_customer_id'] = $customer;
        }
        if ($division_id != "" && $division_id != null) {
            $where['tr.division'] = $division_id;
            // $where['sr.division_id'] = $division_id;
        }
        $where["sr.released_to_client"] = "0";
        $where["DATE(sr.due_date) >= '$startBackLogDate'"] = NULL;
        $where["DATE(sr.due_date) <= '$today'"] = NULL;

        $back_log_data['data'] = $this->bcm->get_due_data($where, $limit, $offset);
        $count = $this->bcm->get_due_data($where, $limit = NULL, $offset = NULL);

        if ($count && count($count) > 0) {
            $back_log_data['count'] = count($count);
        }
        // $this->session->set_userdata('excel_query',$this->db->last_query());

        $this->session->set_userdata('excel_query_where',$where);
        echo json_encode($back_log_data);
    }

    
    public function get_auto_list_gc(){
        // pre_r($this->input->post());die;
        $search = $this->input->post('key');
        $where = (array)(json_decode(stripslashes($this->input->post('where'))));
        $like = $this->input->post('like');
		$select = $this->input->post('select');
        $table = $this->input->post('table');
        $result = $this->bcm->get_auto_list_GC($select,$table,$search,$like,$where);

        echo json_encode($result);
    }

    public function backlog_export(){
        $where = array();
        $where = $this->session->userdata('excel_query_where');
       
        if ($where) {
            $data = $this->bcm->excel_export_backlog($where);
        
            if ($data && count($data) > 0) {
                $this->load->library('excel');
                $tmpfname = "example.xls";

                $excelReader = PHPExcel_IOFactory::createReaderForFile($tmpfname);
                $objPHPExcel = $excelReader->load($tmpfname);

                $objPHPExcel->getProperties()->setCreator("GEO-CHEM")
                    ->setLastModifiedBy("GEO-CHEM")
                    ->setTitle("Office 2007 XLS Backlog Details")
                    ->setSubject("Office 2007 XLS Backlog Details")
                    ->setDescription("Description for Backlog Details")
                    ->setKeywords("phpexcel office codeigniter php")
                    ->setCategory("Backlog Details file");


                $objPHPExcel->setActiveSheetIndex(0);
                $objPHPExcel->getActiveSheet()->setCellValue('A1', "SL NO.");
                $objPHPExcel->getActiveSheet()->setCellValue('B1', "DIVISION");
                $objPHPExcel->getActiveSheet()->setCellValue('C1', "CLIENT");
                $objPHPExcel->getActiveSheet()->setCellValue('D1', "BUYER NAME");
                $objPHPExcel->getActiveSheet()->setCellValue('E1', "PRODUCT");
                $objPHPExcel->getActiveSheet()->setCellValue('F1', "TRF NUMBER");
                $objPHPExcel->getActiveSheet()->setCellValue('G1', "RECEIVED DATE");
                $objPHPExcel->getActiveSheet()->setCellValue('H1', "STATUS");
                $objPHPExcel->getActiveSheet()->setCellValue('I1', "INSUFFICIENT REMARKS");
                $objPHPExcel->getActiveSheet()->setCellValue('J1', "SERVICE TYPE");
                $objPHPExcel->getActiveSheet()->setCellValue('K1', "DUE DATE");
                $objPHPExcel->getActiveSheet()->setCellValue('L1', "SAMPLE DESC");
                $objPHPExcel->getActiveSheet()->setCellValue('M1', "REPORT NUMBER");
                $objPHPExcel->getActiveSheet()->setCellValue('N1', "REPORT RELEASE DATE");
                $objPHPExcel->getActiveSheet()->setCellValue('O1', "INVOICE DATE");
                $objPHPExcel->getActiveSheet()->setCellValue('P1', "REPORT RELEASED BY");
                $objPHPExcel->getActiveSheet()->setCellValue('Q1', "REMARKS");
               
                
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
                $objPHPExcel->getActiveSheet()->getColumnDimension('N')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('O')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('P')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setAutoSize(true);
               
                $i = 2;
                foreach ($data as $key => $value) {

                    $objPHPExcel->getActiveSheet()->setCellValue('A' . $i, ($i - 1));
                    $objPHPExcel->getActiveSheet()->setCellValue('B' . $i, ($value->division_name) ? $value->division_name : '');
                    $objPHPExcel->getActiveSheet()->setCellValue('C' . $i, ($value->client) ? $value->client : '');
                    $objPHPExcel->getActiveSheet()->setCellValue('D' . $i, ($value->buyer_name) ? $value->buyer_name : '');
                    $objPHPExcel->getActiveSheet()->setCellValue('E' . $i, ($value->product) ? $value->product : '');
                    $objPHPExcel->getActiveSheet()->setCellValue('F' . $i, ($value->trf_no) ? $value->trf_no : '');
                    $objPHPExcel->getActiveSheet()->setCellValue('G' . $i, ($value->received_date) ? $value->received_date : '');
                    $objPHPExcel->getActiveSheet()->setCellValue('H' . $i, ($value->status) ? $value->status : '');
                    $objPHPExcel->getActiveSheet()->setCellValue('I' . $i, ($value->insufficient_remark) ? $value->insufficient_remark : '');
                    $objPHPExcel->getActiveSheet()->setCellValue('J' . $i, ($value->service_type) ? $value->service_type : '');
                    $objPHPExcel->getActiveSheet()->setCellValue('K' . $i, ($value->due_date) ? $value->due_date : '');
                    $objPHPExcel->getActiveSheet()->setCellValue('L' . $i, ($value->sample_desc) ? $value->sample_desc : '');
                    $objPHPExcel->getActiveSheet()->setCellValue('M' . $i, ($value->report_num) ? $value->report_num : '');
                    $objPHPExcel->getActiveSheet()->setCellValue('N' . $i, ($value->report_release_date) ? $value->report_release_date : '');
                    $objPHPExcel->getActiveSheet()->setCellValue('O' . $i, ($value->invoice_date) ? $value->invoice_date : '');
                    $objPHPExcel->getActiveSheet()->setCellValue('P' . $i, ($value->report_released_by) ? $value->report_released_by : '');
                    $objPHPExcel->getActiveSheet()->setCellValue('Q' . $i, ($value->remark) ? $value->remark : '');
 
                    $i++;
                }

                $filename = 'Tat_details-' . date('Y-m-d-s') . ".xls";
                $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
                ob_end_clean();
                header('Content-type: application/vnd.ms-excel');
                header('Content-Disposition: attachment; filename=' . $filename);
                $objWriter->save('php://output');
            }
        }
    
    }

    public function get_divisions_due(){
        $data = $this->bcm->get_result("division_id,division_name","mst_divisions",['status'=>'1']);
        echo json_encode($data);
    }

   
}
      
