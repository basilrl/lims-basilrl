<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Invoice_not_upload extends MY_Controller {

	public function __construct(){
		parent::__construct();
		$this->check_session();
        $this->load->model('Invoice_not_upload_model', 'inv_model');
        set_time_limit(0);
        ini_set('memory_limit', '-1');
	}

    public function get_not_upload_invoice(){
       
        $division_id = $this->input->post("division_dropdown");
        $offset = $this->input->post("offset");
        $search = $this->input->post("search");
        $limit = $this->input->post("limit");
        $cust_id = $this->input->post("customer");
        $to = $this->input->post("end_date");
        $from = $this->input->post("start_date");
        $buyer = $this->input->post("buyer");
        $agent = $this->input->post("agent");
        $labType = $this->input->post("labType");
        $where = array();
        $year = $this->input->post('year');
        $month = $this->input->post('month');

        if ($year != "" && $month != "") {
            $where["YEAR(sr.create_on)"] = $year;
            $where["MONTH(sr.create_on)"] = $month;
        }
        if (!empty($division_id)) {
            $where['div.division_id'] = $division_id;
        }
        if(!empty($cust_id)){
            $where['cus.customer_id'] = $cust_id;
        }
        if(!empty($buyer)){
            $where['trf.trf_buyer'] = $buyer;
        }
        if(!empty($agent)){
            $where['trf.trf_applicant'] = $agent;
        }
        if(!empty($labType)){
            $where['sr.sample_registered_to_lab_id'] = $labType;
        }
        if(!empty($from)){

            $where["DATE(sr.create_on) >= '$from'"] = null;
        }
        if(!empty($to)){
    
            $where["DATE(sr.create_on) <= '$to'"] = null;
        }
// print_r($where);die;
        $data['invoice_data'] = $this->inv_model->get_not_upload_invoice($where,$offset,$search,$limit);

        $count = $this->inv_model->get_not_upload_invoice($where,NULL,$search,NULL);
        $this->session->set_userdata('excel_query_invoice',$this->db->last_query());
        
        if($count && count($count)>0){
            $data['count'] = count($count);
        }
       
       
        echo json_encode($data);
    }

    
    public function get_auto_list_invoice(){
        // pre_r($this->input->post());die;
        $search = $this->input->post('key');
        $where = (array)(json_decode(stripslashes($this->input->post('where'))));
        $like = $this->input->post('like');
		$select = $this->input->post('select');
        $table = $this->input->post('table');
        $result = $this->inv_model->get_auto_list_invoice($select,$table,$search,$like,$where);

        echo json_encode($result);
    }

    public function invoice_export(){
       
       
        $data = $this->db->query($this->session->userdata('excel_query_invoice'))->result();

            if ($data && count($data) > 0) {
                $this->load->library('excel');
                $tmpfname = "example.xls";

                $excelReader = PHPExcel_IOFactory::createReaderForFile($tmpfname);
                $objPHPExcel = $excelReader->load($tmpfname);

                $objPHPExcel->getProperties()->setCreator("GEO-CHEM")
                    ->setLastModifiedBy("GEO-CHEM")
                    ->setTitle("Office 2007 XLS Invoice not uploaded")
                    ->setSubject("Office 2007 XLS Invoice not uploaded")
                    ->setDescription("Description for Invoice not uploaded")
                    ->setKeywords("phpexcel office codeigniter php")
                    ->setCategory("Invoice not uploaded file");


                $objPHPExcel->setActiveSheetIndex(0);
                $objPHPExcel->getActiveSheet()->setCellValue('A1', "SL NO.");
                $objPHPExcel->getActiveSheet()->setCellValue('B1', "Basil Report NUMBER");
                $objPHPExcel->getActiveSheet()->setCellValue('C1', "CUSTOMER NAME");
                $objPHPExcel->getActiveSheet()->setCellValue('D1', "DIVISION");
                $objPHPExcel->getActiveSheet()->setCellValue('E1', "COLLECTION DATE");
                $objPHPExcel->getActiveSheet()->setCellValue('F1', "RECEIVED DATE");
                $objPHPExcel->getActiveSheet()->setCellValue('G1', "SAMPLE DESCRIPTION");
                $objPHPExcel->getActiveSheet()->setCellValue('H1', "QTY RECEIVED");
                $objPHPExcel->getActiveSheet()->setCellValue('I1', "CREATED ON");
                $objPHPExcel->getActiveSheet()->setCellValue('J1', "CREATED BY");
                $objPHPExcel->getActiveSheet()->setCellValue('K1', "DUE DATE");

                $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);

                $i = 2;
                foreach ($data as $key => $value) {

                    $objPHPExcel->getActiveSheet()->setCellValue('A' . $i, ($i - 1));
                    $objPHPExcel->getActiveSheet()->setCellValue('B' . $i, ($value->gc_no) ? $value->gc_no : '');
                    $objPHPExcel->getActiveSheet()->setCellValue('C' . $i, ($value->customer_name) ? $value->customer_name : '');
                    $objPHPExcel->getActiveSheet()->setCellValue('D' . $i, ($value->division_name) ? $value->division_name : '');
                    $objPHPExcel->getActiveSheet()->setCellValue('E' . $i, ($value->collection_date) ? $value->collection_date : '');
                    $objPHPExcel->getActiveSheet()->setCellValue('F' . $i, ($value->received_date) ? $value->received_date : '');
                    $objPHPExcel->getActiveSheet()->setCellValue('G' . $i, ($value->sample_desc) ? $value->sample_desc : '');
                    $objPHPExcel->getActiveSheet()->setCellValue('H' . $i, ($value->qty_received) ? $value->qty_received : '');
                    $objPHPExcel->getActiveSheet()->setCellValue('I' . $i, ($value->create_on) ? $value->create_on : '');
                    $objPHPExcel->getActiveSheet()->setCellValue('J' . $i, ($value->create_by) ? $value->create_by : '');
                    $objPHPExcel->getActiveSheet()->setCellValue('K' . $i, ($value->due_date) ? $value->due_date : '');
                  
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

    public function get_divisions(){
        $data = $this->inv_model->get_result("division_id,division_name","mst_divisions",['status'=>'1']);
        echo json_encode($data);
    }

    public function get_details_particular(){
        $manual_invoice_id = $this->input->post('manual_invoice_id');
        $data = $this->inv_model->get_particular_details($manual_invoice_id);
        echo json_encode($data);
    }

   
}
      
