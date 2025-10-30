<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Monthly_sales_report extends MY_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Monthly_sales_report_model', 'SL');
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

		$base_url = 'Monthly_sales_report/index';

       
        if ($this->uri->segment('3') != NULL && $this->uri->segment('3') != 'NULL') {
            $gc_number = $this->uri->segment('3');
			$data['gc_number'] =  base64_decode($gc_number);
			$base_url .= '/' . $gc_number;
			$where['sr.gc_no'] = base64_decode($gc_number);

		} else {
			$base_url .= '/NULL';
			$data['gc_number'] = NULL;
		}
        
        if ($this->uri->segment('4') != NULL && $this->uri->segment('4') != 'NULL') {
            $customer_id = $this->uri->segment('4');
			$data['customer_id'] =  base64_decode($customer_id);
            $customers= $this->SL->get_row('customer_name','cust_customers','customer_id='.$data['customer_id']);
            if($customers && count($customers)>0){
                $data['customer_name'] =$customers->customer_name;
            }
			$base_url .= '/' . $customer_id;
			$where['sr.sample_customer_id'] = base64_decode($customer_id);
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
        

		$total_row = $this->SL->get_monthly_sales_list(NULL, NULL, $search, NULL, NULL, $where,$start_date,$end_date,true);
      
		$config = $this->pagination($base_url, $total_row, 10, 10);
        $data['monthly_sales_report_list'] = $this->SL->get_monthly_sales_list($config["per_page"], $config['page'], $search, $sortby, $order, $where,$start_date,$end_date);
		$data["links"] = $config["links"];

        $this->SL->get_monthly_sales_list(NULL,NULL, $search, $sortby, $order, $where,$start_date,$end_date,true);
        $this->session->set_userdata('excel_query',$this->db->last_query());
       
        $page_no = $this->uri->segment('10');
		$start = (int)$page_no + 1;
		$end = (($data['monthly_sales_report_list']) ? count($data['monthly_sales_report_list']) : 0) + (($page_no) ? $page_no : 0);
		$data['result_count'] = "Showing " . $start . " - " . $end . " of " . $total_row . " Results";


		if ($order == NULL || $order == 'NULL') {
			$data['order'] = ($order) ? "DESC" : "ASC";
		} else {
			$data['order'] = ($order == "ASC") ? "DESC" : "ASC";
		}

		$this->load_view('monthly_sales_report/monthly_sales_report',$data);
	}


    public function get_auto_list_sales(){
        // pre_r($this->input->post());die;
        $search = $this->input->post('key');
        $where = (array)(json_decode(stripslashes($this->input->post('where'))));
        $like = $this->input->post('like');
		$select = $this->input->post('select');
        $table = $this->input->post('table');
        $result = $this->SL->get_AutoList_sales($select,$table,$search,$like,$where);

        echo json_encode($result);
    }

   
    public function excel_export_sales(){
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
                    ->setTitle("Office 2007 XLS Sales Report Document")
                    ->setSubject("Office 2007 XLS Sales Report Document")
                    ->setDescription("Description for Sales Report Document")
                    ->setKeywords("phpexcel office codeigniter php")
                    ->setCategory("Invoice Sales details file");


                $objPHPExcel->setActiveSheetIndex(0);
                $objPHPExcel->getActiveSheet()->setCellValue('A1', "SL NO.");
                $objPHPExcel->getActiveSheet()->setCellValue('B1', "Basil Report NUMBER");
                $objPHPExcel->getActiveSheet()->setCellValue('C1', "CUSTOMER");
                $objPHPExcel->getActiveSheet()->setCellValue('D1', "CREATED DATE");
                $objPHPExcel->getActiveSheet()->setCellValue('E1', "ESTIMATE VALUE");
                $objPHPExcel->getActiveSheet()->setCellValue('F1', "STATUS");


                $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
             
                $i = 2;
                foreach ($data as $key => $value) {

                    $objPHPExcel->getActiveSheet()->setCellValue('A' . $i, ($i - 1));
                    $objPHPExcel->getActiveSheet()->setCellValue('B' . $i, ($value->gc_no) ? $value->gc_no : '');
                    $objPHPExcel->getActiveSheet()->setCellValue('C' . $i, ($value->customer_name) ? $value->customer_name : '');
                    $objPHPExcel->getActiveSheet()->setCellValue('D' . $i, ($value->create_on) ? $value->create_on : '');
                    $objPHPExcel->getActiveSheet()->setCellValue('E' . $i, ($value->estimate_value) ? $value->estimate_value : '');
                    $objPHPExcel->getActiveSheet()->setCellValue('F' . $i, ($value->status) ? $value->status : '');
                   
                    $i++;
                }

                $filename = 'monthly_sales_report-' . date('Y-m-d-s') . ".xls";
                $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
                ob_end_clean();
                header('Content-type: application/vnd.ms-excel');
                header('Content-Disposition: attachment; filename=' . $filename);
                $objWriter->save('php://output');
            }
        }
    }
}
