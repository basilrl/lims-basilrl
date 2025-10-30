<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Invoice_register extends MY_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Invoice_register_model', 'IN');
		$this->check_session();
		$checkUser = $this->session->userdata('user_data');
		$this->user = $checkUser->uidnr_admin;
		$this->form_validation->set_error_delimiters('<div class="error" style="color:red">', '</div>');
        set_time_limit(0);
        ini_set('memory_limit', '-1');
	}

	public function index()
	{
        $where=NULL;
        $search = NULL;
        $sortby=NULL;
        $order=NULL;
        $start_date = NULL;
        $end_date = NULL;

		$base_url = 'Invoice_register/index';

       
        if ($this->uri->segment('3') != NULL && $this->uri->segment('3') != 'NULL') {
            $invoice_number = $this->uri->segment('3');
			$data['invoice_number'] =  base64_decode($invoice_number);
			$base_url .= '/' . $invoice_number;
			$where['invoice.proforma_invoice_number'] = base64_decode($invoice_number);

		} else {
			$base_url .= '/NULL';
			$data['invoice_number'] = NULL;
		}
        
        if ($this->uri->segment('4') != NULL && $this->uri->segment('4') != 'NULL') {
            $customer_id = $this->uri->segment('4');
			$data['customer_id'] =  base64_decode($customer_id);
            $customers= $this->IN->get_row('customer_name','cust_customers','customer_id='.$data['customer_id']);
            if($customers && count($customers)>0){
                $data['customer_name'] =$customers->customer_name;
            }
			$base_url .= '/' . $customer_id;
			$where['invoice.invoice_proforma_customer_id'] = base64_decode($customer_id);
		} else {
            $base_url .= '/NULL';
			$data['customer_id'] = NULL;
            $data['customer_name'] = NULL;
		}
       
        if ($this->uri->segment('5') != NULL && $this->uri->segment('5') != 'NULL') {
            $from_date = $this->uri->segment('5');
			$data['from_date'] =  base64_decode($from_date);
			$base_url .= '/' . $from_date;
			$start_date = base64_decode($from_date);
		} else {
            $base_url .= '/NULL';
			$data['from_date'] = NULL;
		}

        if ($this->uri->segment('6') != NULL && $this->uri->segment('6') != 'NULL') {
            $to_date = $this->uri->segment('6');
			$data['to_date'] =  base64_decode($to_date);
			$base_url .= '/' . $to_date;
			$end_date = base64_decode($to_date);
		} else {
            $base_url .= '/NULL';
			$data['to_date'] = NULL;
		}

        if ($this->uri->segment('7') != NULL && $this->uri->segment('7') != 'NULL') {
            $search = $this->uri->segment('7');
			$data['search'] =  base64_decode($search);
			$base_url .= '/' . $search;
			$search = base64_decode($search);
		} else {
            $base_url .= '/NULL';
			$data['search'] = NULL;
		}

        if ($this->uri->segment('8') != NULL && $this->uri->segment('8') != 'NULL') {
            $sortby = $this->uri->segment('8');
			$base_url .= '/' . $sortby;
            $sortby = base64_decode($sortby);
		} else {
			$base_url .= '/NULL';
			$sortby = NULL;
		}
		if ($this->uri->segment('9') != NULL && $this->uri->segment('9') != 'NULL') {
            $order = $this->uri->segment('9');
			$base_url .= '/' . $order;
		} else {
			$base_url .= '/NULL';
			$order = NULL;
		}
        

		$total_row = $this->IN->get_invoice_register_list(NULL, NULL, $search, NULL, NULL, $where,$start_date,$end_date,true);
		$config = $this->pagination($base_url, $total_row, 10, 10);
        $data['invoice_register_list'] = $this->IN->get_invoice_register_list($config["per_page"], $config['page'], $search, $sortby, $order, $where,$start_date,$end_date);
		$data["links"] = $config["links"];

        $this->IN->get_invoice_register_list(NULL,NULL, $search, $sortby, $order, $where,$start_date,$end_date,true);
        $this->session->set_userdata('excel_query',$this->db->last_query());
       
        $page_no = $this->uri->segment('10');
		$start = (int)$page_no + 1;
		$end = (($data['invoice_register_list']) ? count($data['invoice_register_list']) : 0) + (($page_no) ? $page_no : 0);
		$data['result_count'] = "Showing " . $start . " - " . $end . " of " . $total_row . " Results";


		if ($order == NULL || $order == 'NULL') {
			$data['order'] = ($order) ? "DESC" : "ASC";
		} else {
			$data['order'] = ($order == "ASC") ? "DESC" : "ASC";
		}

		$this->load_view('invoice_register/invoice_register_list',$data);
	}


    public function get_auto_list_invoice(){
        // pre_r($this->input->post());die;
        $search = $this->input->post('key');
        $where = (array)(json_decode(stripslashes($this->input->post('where'))));
        $like = $this->input->post('like');
		$select = $this->input->post('select');
        $table = $this->input->post('table');
        $result = $this->IN->get_AutoList_report($select,$table,$search,$like,$where);

        echo json_encode($result);
    }

   
    public function excel_export_reports(){
        $query = $this->session->userdata('excel_query');

        if ($query) {
            $data = $this->db->query($query)->result();

            if ($data && count($data) > 0) {
                $this->load->library('excel');
                $tmpfname = "example.xls";

                $excelReader = PHPExcel_IOFactory::createReaderForFile($tmpfname);
                $objPHPExcel = $excelReader->load($tmpfname);

                $objPHPExcel->getProperties()->setCreator("GEO-CHEM")
                    ->setLastModifiedBy("GEO-CHEM")
                    ->setTitle("Office 2007 XLS Invoice Report Document")
                    ->setSubject("Office 2007 XLS Invoice Report Document")
                    ->setDescription("Description for Invoice Report Document")
                    ->setKeywords("phpexcel office codeigniter php")
                    ->setCategory("Invoice Report details file");


                $objPHPExcel->setActiveSheetIndex(0);
                $objPHPExcel->getActiveSheet()->setCellValue('A1', "SL NO.");
                $objPHPExcel->getActiveSheet()->setCellValue('B1', "INVOICE NUMBER");
                $objPHPExcel->getActiveSheet()->setCellValue('C1', "INVOICE DATE");
                $objPHPExcel->getActiveSheet()->setCellValue('D1', "CUSTOMER NAME");
                $objPHPExcel->getActiveSheet()->setCellValue('E1', "TOTAL AMOUNT");
                $objPHPExcel->getActiveSheet()->setCellValue('F1', "CURRENCY");
                $objPHPExcel->getActiveSheet()->setCellValue('G1', "CUSTOMER TYPE");
                $objPHPExcel->getActiveSheet()->setCellValue('H1', "JOB TYPE");
                $objPHPExcel->getActiveSheet()->setCellValue('I1', "CREATED BY");

                $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
               



                $i = 2;
                foreach ($data as $key => $value) {

                    $objPHPExcel->getActiveSheet()->setCellValue('A' . $i, ($i - 1));
                    $objPHPExcel->getActiveSheet()->setCellValue('B' . $i, ($value->proforma_invoice_number) ? $value->proforma_invoice_number : '');
                    $objPHPExcel->getActiveSheet()->setCellValue('C' . $i, ($value->proforma_invoice_date) ? $value->proforma_invoice_date : '');
                    $objPHPExcel->getActiveSheet()->setCellValue('D' . $i, ($value->customer_name) ? $value->customer_name : '');
                    $objPHPExcel->getActiveSheet()->setCellValue('E' . $i, ($value->total_amount) ? $value->total_amount : '');
                    $objPHPExcel->getActiveSheet()->setCellValue('F' . $i, ($value->currency_name) ? $value->currency_name : '');
                    $objPHPExcel->getActiveSheet()->setCellValue('G' . $i, ($value->customer_type) ? $value->customer_type : '');
                    $objPHPExcel->getActiveSheet()->setCellValue('H' . $i, ($value->proforma_invoice_job_type) ? $value->proforma_invoice_job_type : '');
                    $objPHPExcel->getActiveSheet()->setCellValue('I' . $i, ($value->created_by) ? $value->created_by : '');
                  
                    $i++;
                }

                $filename = 'invoice_register-' . date('Y-m-d-s') . ".xls";
                $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
                ob_end_clean();
                header('Content-type: application/vnd.ms-excel');
                header('Content-Disposition: attachment; filename=' . $filename);
                $objWriter->save('php://output');
            }
        }
    }
}
