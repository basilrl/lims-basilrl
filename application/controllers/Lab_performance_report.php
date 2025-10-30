<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Lab_performance_report extends MY_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Lab_performance_report_model', 'LAB');
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
        
		$base_url = 'Lab_performance_report/index';
        
        
        
        if ($this->uri->segment('3') != NULL && $this->uri->segment('3') != 'NULL') {
            $lab_id = $this->uri->segment('3');
            
			$data['lab_id'] =  base64_decode($lab_id);
            $lab = $this->LAB->get_row("lab_name",'mst_labs','lab_id='.$data['lab_id']);
            
            if($lab && count($lab)>0){
                $data['lab'] =$lab->lab_name;
            }
			$base_url .= '/' . $lab_id;
			$where['lab.lab_id'] = base64_decode($lab_id);
		} else {
            $base_url .= '/NULL';
			$data['lab_id'] = NULL;
            $data['lab'] = NULL;
		}
        
        if ($this->uri->segment('4') != NULL && $this->uri->segment('4') != 'NULL') {
            $from_date = $this->uri->segment('4');
			$data['from_date'] =  base64_decode($from_date);
			$base_url .= '/' . $from_date;
            $from_date = base64_decode($from_date);
			$where["DATE(lab.created_on) >= '$from_date' "] = null;
		} else {
            $base_url .= '/NULL';
			$data['from_date'] = NULL;
		}
        
        if ($this->uri->segment('5') != NULL && $this->uri->segment('5') != 'NULL') {
            $to_date = $this->uri->segment('5');
			$data['to_date'] =  base64_decode($to_date);
			$base_url .= '/' . $to_date;
            $to_date = base64_decode($to_date);
			$where["(DATE(lab.created_on)) <= '$to_date'"] = null;
		} else {
            $base_url .= '/NULL';
			$data['to_date'] = NULL;
		}
        
        if ($this->uri->segment('6') != NULL && $this->uri->segment('6') != 'NULL') {
            $search = $this->uri->segment('6');
			$data['search'] =  base64_decode($search);
			$base_url .= '/' . $search;
			$search = base64_decode($search);
            
		} else {
            $base_url .= '/NULL';
			$data['search'] = NULL;
		}
        
        if ($this->uri->segment('7') != NULL && $this->uri->segment('7') != 'NULL') {
            $sortby = $this->uri->segment('7');
			$base_url .= '/' . $sortby;
            $sortby = base64_decode($sortby);
		} else {
            $base_url .= '/NULL';
			$sortby = NULL;
		}
		if ($this->uri->segment('8') != NULL && $this->uri->segment('8') != 'NULL') {
            $order = $this->uri->segment('8');
			$base_url .= '/' . $order;
		} else {
            $base_url .= '/NULL';
			$order = NULL;
		}
        
        
		$total_row = $this->LAB->get_lab_performance_list(NULL, NULL, $search, NULL, NULL, $where,$start_date,$end_date,true);
        
		$config = $this->pagination($base_url, $total_row, 10, 9);
        $data['lab_performance_list'] = $this->LAB->get_lab_performance_list($config["per_page"], $config['page'], $search, $sortby, $order, $where,$start_date,$end_date);
		$data["links"] = $config["links"];

        $this->LAB->get_lab_performance_list(NULL,NULL, $search, $sortby, $order, $where,$start_date,$end_date,true);
        $this->session->set_userdata('excel_query',$this->db->last_query());
       
        $page_no = $this->uri->segment('9');
		$start = (int)$page_no + 1;
		$end = (($data['lab_performance_list']) ? count($data['lab_performance_list']) : 0) + (($page_no) ? $page_no : 0);
		$data['result_count'] = "Showing " . $start . " - " . $end . " of " . $total_row . " Results";


		if ($order == NULL || $order == 'NULL') {
			$data['order'] = ($order) ? "DESC" : "ASC";
		} else {
			$data['order'] = ($order == "ASC") ? "DESC" : "ASC";
		}

		$this->load_view('lab_performance_report/lab_performance_report',$data);
	}


    public function get_auto_list_lab(){
        // pre_r($this->input->post());die;
        $search = $this->input->post('key');
        $where = (array)(json_decode(stripslashes($this->input->post('where'))));
        $like = $this->input->post('like');
		$select = $this->input->post('select');
        $table = $this->input->post('table');
        $result = $this->LAB->get_AutoList_lab($select,$table,$search,$like,$where);

        echo json_encode($result);
    }

   
    public function excel_export_lab(){
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
                    ->setTitle("Office 2007 XLS Lab Report Document")
                    ->setSubject("Office 2007 XLS Lab Report Document")
                    ->setDescription("Description for Lab Report Document")
                    ->setKeywords("phpexcel office codeigniter php")
                    ->setCategory("Invoice Lab details file");


                $objPHPExcel->setActiveSheetIndex(0);
                $objPHPExcel->getActiveSheet()->setCellValue('A1', "SL NO.");
                $objPHPExcel->getActiveSheet()->setCellValue('B1', "LAB");
                $objPHPExcel->getActiveSheet()->setCellValue('C1', "ASSIGNED TEST COUNT");
                $objPHPExcel->getActiveSheet()->setCellValue('D1', "COMPLETED TEST COUNT");
              
                $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
             
                $i = 2;
                foreach ($data as $key => $value) {

                    $objPHPExcel->getActiveSheet()->setCellValue('A' . $i, ($i - 1));
                    $objPHPExcel->getActiveSheet()->setCellValue('B' . $i, ($value->lab) ? $value->lab : '');
                    $objPHPExcel->getActiveSheet()->setCellValue('C' . $i, ($value->assigned_tests) ? $value->assigned_tests : '0');
                    $objPHPExcel->getActiveSheet()->setCellValue('D' . $i, ($value->completed_tests) ? $value->completed_tests : '0');
                   
                    $i++;
                }

                $filename = 'Lab_performance_report-' . date('Y-m-d-s') . ".xls";
                $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
                ob_end_clean();
                header('Content-type: application/vnd.ms-excel');
                header('Content-Disposition: attachment; filename=' . $filename);
                $objWriter->save('php://output');
            }
        }
    }
}
