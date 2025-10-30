<?php

use phpDocumentor\Reflection\DocBlock\Tags\Var_;

defined('BASEPATH') or exit('No direct script access allowed');

class Report_generated extends MY_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Report_generated_model', 'report');
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

		$base_url = 'Report_generated/index';

        if ($this->uri->segment('3') != NULL && $this->uri->segment('3') != 'NULL') {
            $report_number = $this->uri->segment('3');
			$data['report_number'] =  base64_decode($report_number);
			$base_url .= '/' . $report_number;
			$where['rp.report_num'] = base64_decode($report_number);

		} else {
			$base_url .= '/NULL';
			$data['report_number'] = NULL;
		}
        if ($this->uri->segment('4') != NULL && $this->uri->segment('4') != 'NULL') {
            $customer_id = $this->uri->segment('4');
			$data['customer_id'] =  base64_decode($customer_id);
            $customers= $this->report->get_row('customer_name','cust_customers','customer_id='.$data['customer_id']);
            if($customers && count($customers)>0){
                $data['customer_name'] = $customers->customer_name;
            }
			$base_url .= '/' . $customer_id;
			$where['trf.trf_applicant'] = base64_decode($customer_id);
		} else {
            $base_url .= '/NULL';
			$data['customer_id'] = NULL;
            $data['customer_name'] = NULL;
		}

        if ($this->uri->segment('5') != NULL && $this->uri->segment('5') != 'NULL') {
            $buyer_id = $this->uri->segment('5');
			$data['buyer_id'] =  base64_decode($buyer_id);
            $buyers= $this->report->get_row('customer_name','cust_customers','customer_id='.$data['buyer_id']);
            if($buyers && count($buyers)>0){
                $data['buyer_name'] = $buyers->customer_name;
            }
			$base_url .= '/' . $buyer_id;
			$where['trf.trf_buyer'] = base64_decode($buyer_id);
		} else {
            $base_url .= '/NULL';
			$data['buyer_id'] = NULL;
            $data['buyer_name'] = NULL;
		}

        if ($this->uri->segment('6') != NULL && $this->uri->segment('6') != 'NULL') {
            $agent_id = $this->uri->segment('6');
			$data['agent_id'] =  base64_decode($agent_id);
            $agents= $this->report->get_row('customer_name','cust_customers','customer_id='.$data['agent_id']);
            if($agents && count($agents)>0){
                $data['agent_name'] =  $agents->customer_name;
            }
			$base_url .= '/' . $agent_id;
			$where['trf.trf_agent'] = base64_decode($agent_id);
		} else {
            $base_url .= '/NULL';
			$data['agent_id'] = NULL;
            $data['agent_name'] = NULL;
		}

        if ($this->uri->segment('7') != NULL && $this->uri->segment('7') != 'NULL') {
            $thirdparty_id = $this->uri->segment('7');
			$data['thirdparty_id'] =  base64_decode($thirdparty_id);
            $thirdparty= $this->report->get_row('customer_name','cust_customers','customer_id='.$data['thirdparty_id']);
            if($thirdparty && count($thirdparty)>0){
                $data['thirdparty_name'] = $thirdparty->customer_name;
            }
			$base_url .= '/' . $thirdparty_id;
			$where['trf.trf_thirdparty'] = base64_decode($thirdparty_id);
		} else {
            $base_url .= '/NULL';
			$data['thirdparty_id'] = NULL;
            $data['thirdparty_name'] = NULL;
		}

    
        if ($this->uri->segment('8') != NULL && $this->uri->segment('8') != 'NULL') {
            $product_id = $this->uri->segment('8');
			$data['product_id'] =  base64_decode($product_id);
            $products= $this->report->get_row('sample_type_name','mst_sample_types','sample_type_id='.$data['product_id']);
            if($products && count($products)>0){
                $data['product_name'] =$products->sample_type_name;
            }
			$base_url .= '/' . $product_id;
			$where['sample.sample_type_id'] = base64_decode($product_id);
		} else {
            $base_url .= '/NULL';
			$data['product_id'] = NULL;
            $data['product_name'] = NULL;
		}

        if ($this->uri->segment('9') != NULL && $this->uri->segment('9') != 'NULL') {
            $country_id = $this->uri->segment('9');
			$data['country_id'] =  base64_decode($country_id);
            $countrys= $this->report->get_row('country_name','mst_country','country_id='.$data['country_id']);
            if($countrys && count($countrys)>0){
                $data['country_name'] =$countrys->country_name;
            }
			$base_url .= '/' . $country_id;
			$where['trf.trf_country_orgin'] = base64_decode($country_id);
		} else {
            $base_url .= '/NULL';
			$data['country_id'] = NULL;
            $data['country_name'] = NULL;
		}

        if ($this->uri->segment('10') != NULL && $this->uri->segment('10') != 'NULL') {
            $from_date = $this->uri->segment('10');
			$data['from_date'] =  base64_decode($from_date);
			$base_url .= '/' . $from_date;
			$start_date = base64_decode($from_date);
		} else {
            $base_url .= '/NULL';
			$data['from_date'] = NULL;
		}

        if ($this->uri->segment('11') != NULL && $this->uri->segment('11') != 'NULL') {
            $to_date = $this->uri->segment('11');
			$data['to_date'] =  base64_decode($to_date);
			$base_url .= '/' . $to_date;
			$end_date = base64_decode($to_date);
		} else {
            $base_url .= '/NULL';
			$data['to_date'] = NULL;
		}


        if ($this->uri->segment('12') != NULL && $this->uri->segment('12') != 'NULL') {
            $search = $this->uri->segment('12');
			$data['search'] =  base64_decode($search);
			$base_url .= '/' . $search;
			$search = base64_decode($search);
		} else {
            $base_url .= '/NULL';
			$data['search'] = NULL;
		}

        if ($this->uri->segment('13') != NULL && $this->uri->segment('13') != 'NULL') {
            $sortby = $this->uri->segment('13');
			$base_url .= '/' . $sortby;
            $sortby = base64_decode($sortby);
		} else {
			$base_url .= '/NULL';
			$sortby = NULL;
		}
		if ($this->uri->segment('14') != NULL && $this->uri->segment('14') != 'NULL') {
            $order = $this->uri->segment('14');
			$base_url .= '/' . $order;
		} else {
			$base_url .= '/NULL';
			$order = NULL;
		}

      
       
        
        $total_row = $this->report->get_report_list(NULL, NULL, $search, NULL, NULL, $where,$start_date,$end_date,true);
		$config = $this->pagination($base_url, $total_row, 5, 15);
        $data['report_list'] = $this->report->get_report_list($config["per_page"], $config['page'], $search, $sortby, $order, $where,$start_date,$end_date);
		$data["links"] = $config["links"];

        $this->report->get_report_list(NULL,NULL, $search, $sortby, $order, $where,$start_date,$end_date,true);
        $this->session->set_userdata('excel_query',$this->db->last_query());
       
        $page_no = $this->uri->segment('15');
		$start = (int)$page_no + 1;
		$end = (($data['report_list']) ? count($data['report_list']) : 0) + (($page_no) ? $page_no : 0);
		$data['result_count'] = "Showing " . $start . " - " . $end . " of " . $total_row . " Results";


		if ($order == NULL || $order == 'NULL') {
			$data['order'] = ($order) ? "DESC" : "ASC";
		} else {
			$data['order'] = ($order == "ASC") ? "DESC" : "ASC";
		}

		$this->load_view('report_generated/report_generated',$data);
	}


    public function get_auto_list_report_generated(){
        // pre_r($this->input->post());die;
        $search = $this->input->post('key');
        $where = (array)(json_decode(stripslashes($this->input->post('where'))));
        $like = $this->input->post('like');
		$select = $this->input->post('select');
        $table = $this->input->post('table');
        $result = $this->report->get_AutoList_report_generated($select,$table,$search,$like,$where);

        echo json_encode($result);
    }

   
    public function excel_export_generated_report(){
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
                    ->setTitle("Office 2007 XLS Report Document")
                    ->setSubject("Office 2007 XLS Report Document")
                    ->setDescription("Description for Report Document")
                    ->setKeywords("phpexcel office codeigniter php")
                    ->setCategory("Report details file");


                $objPHPExcel->setActiveSheetIndex(0);
                $objPHPExcel->getActiveSheet()->setCellValue('A1', "SL NO.");
                $objPHPExcel->getActiveSheet()->setCellValue('B1', "REPORT NUMBER");
                $objPHPExcel->getActiveSheet()->setCellValue('C1', "REPORT DATE");
                $objPHPExcel->getActiveSheet()->setCellValue('D1', "PRODUCT");
                $objPHPExcel->getActiveSheet()->setCellValue('E1', "CUSTOMER NAME");
                $objPHPExcel->getActiveSheet()->setCellValue('F1', "BUYER");
                $objPHPExcel->getActiveSheet()->setCellValue('G1', "AGENT");
                $objPHPExcel->getActiveSheet()->setCellValue('H1', "THIRD PARTY");
                $objPHPExcel->getActiveSheet()->setCellValue('I1', "STATUS");
                $objPHPExcel->getActiveSheet()->setCellValue('J1', "COUNTRY");
                $objPHPExcel->getActiveSheet()->setCellValue('K1', "CREATED BY");


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
            
                $i = 2;
                foreach ($data as $key => $value) {

                    $objPHPExcel->getActiveSheet()->setCellValue('A' . $i, ($i - 1));
                    $objPHPExcel->getActiveSheet()->setCellValue('B' . $i, ($value->report_num) ? $value->report_num : '');
                    $objPHPExcel->getActiveSheet()->setCellValue('C' . $i, ($value->report_date) ? $value->report_date : '');
                    $objPHPExcel->getActiveSheet()->setCellValue('D' . $i, ($value->product_name) ? $value->product_name : '');
                    $objPHPExcel->getActiveSheet()->setCellValue('E' . $i, ($value->customer_name) ? $value->customer_name : '');
                    $objPHPExcel->getActiveSheet()->setCellValue('F' . $i, ($value->buyer_name) ? $value->buyer_name : '');
                    $objPHPExcel->getActiveSheet()->setCellValue('G' . $i, ($value->agent_name) ? $value->agent_name : '');
                    $objPHPExcel->getActiveSheet()->setCellValue('H' . $i, ($value->thirdparty_name) ? $value->thirdparty_name : '');
                    $objPHPExcel->getActiveSheet()->setCellValue('I' . $i, ($value->status) ? $value->status : '');
                    $objPHPExcel->getActiveSheet()->setCellValue('J' . $i, ($value->country_name) ? $value->country_name : '');
                    $objPHPExcel->getActiveSheet()->setCellValue('K' . $i, ($value->created_by) ? $value->created_by : '');
                  
                    $i++;
                }

                $filename = 'Report_generated-' . date('Y-m-d-s') . ".xls";
                $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
                ob_end_clean();
                header('Content-type: application/vnd.ms-excel');
                header('Content-Disposition: attachment; filename=' . $filename);
                $objWriter->save('php://output');
            }
        }
    }
}
