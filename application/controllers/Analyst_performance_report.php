<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Analyst_performance_report extends MY_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Analyst_performance_report_model', 'AP');
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

		$base_url = 'Analyst_performance_report/index';

      
        
        if ($this->uri->segment('3') != NULL && $this->uri->segment('3') != 'NULL') {
            $analyst_id = $this->uri->segment('3');
          
			$data['analyst_id'] =  base64_decode($analyst_id);
            $analyst = $this->AP->get_row("concat(admin_fname,' ',admin_lname) as analyst",'admin_profile','uidnr_admin='.$data['analyst_id']);

            if($analyst && count($analyst)>0){
                $data['analyst'] =$analyst->analyst;
            }
			$base_url .= '/' . $analyst_id;
			$where['ap.uidnr_admin'] = base64_decode($analyst_id);
		} else {
            $base_url .= '/NULL';
			$data['analyst_id'] = NULL;
            $data['analyst'] = NULL;
		}
       
        if ($this->uri->segment('4') != NULL && $this->uri->segment('4') != 'NULL') {
            $from_date = $this->uri->segment('4');
			$data['from_date'] =  base64_decode($from_date);
			$base_url .= '/' . $from_date;
            $from_date = base64_decode($from_date);
			$where["DATE(st.assigned_time) >= '$from_date' "] = null;
		} else {
            $base_url .= '/NULL';
			$data['from_date'] = NULL;
		}

        if ($this->uri->segment('5') != NULL && $this->uri->segment('5') != 'NULL') {
            $to_date = $this->uri->segment('5');
			$data['to_date'] =  base64_decode($to_date);
			$base_url .= '/' . $to_date;
            $to_date = base64_decode($to_date);
			$where["(DATE(st.assigned_time)) <= '$to_date'"] = null;
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
        

		$total_row = $this->AP->get_analyst_performance_list(NULL, NULL, $search, NULL, NULL, $where,$start_date,$end_date,true);

		$config = $this->pagination($base_url, $total_row, 10, 9);
        $data['analyst_performance_list'] = $this->AP->get_analyst_performance_list($config["per_page"], $config['page'], $search, $sortby, $order, $where,$start_date,$end_date);
		$data["links"] = $config["links"];

        $this->AP->get_analyst_performance_list(NULL,NULL, $search, $sortby, $order, $where,$start_date,$end_date,true);
        $this->session->set_userdata('excel_query',$this->db->last_query());
       
        $page_no = $this->uri->segment('9');
		$start = (int)$page_no + 1;
		$end = (($data['analyst_performance_list']) ? count($data['analyst_performance_list']) : 0) + (($page_no) ? $page_no : 0);
		$data['result_count'] = "Showing " . $start . " - " . $end . " of " . $total_row . " Results";


		if ($order == NULL || $order == 'NULL') {
			$data['order'] = ($order) ? "DESC" : "ASC";
		} else {
			$data['order'] = ($order == "ASC") ? "DESC" : "ASC";
		}

		$this->load_view('analyst_performance_report/analyst_performance_report_list',$data);
	}


    public function get_auto_list_analyst(){
        // pre_r($this->input->post());die;
        $search = $this->input->post('key');
        $where = (array)(json_decode(stripslashes($this->input->post('where'))));
        $like = $this->input->post('like');
		$select = $this->input->post('select');
        $table = $this->input->post('table');
        $result = $this->AP->get_AutoList_analyst($select,$table,$search,$like,$where);

        echo json_encode($result);
    }

   
    public function excel_export_analyst(){
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
                    ->setTitle("Office 2007 XLS Analyst Report Document")
                    ->setSubject("Office 2007 XLS Analyst Report Document")
                    ->setDescription("Description for Analyst Report Document")
                    ->setKeywords("phpexcel office codeigniter php")
                    ->setCategory("Invoice Analyst details file");


                $objPHPExcel->setActiveSheetIndex(0);
                $objPHPExcel->getActiveSheet()->setCellValue('A1', "SL NO.");
                $objPHPExcel->getActiveSheet()->setCellValue('B1', "ANALYST");
                $objPHPExcel->getActiveSheet()->setCellValue('C1', "ASSIGNED TEST COUNT");
                $objPHPExcel->getActiveSheet()->setCellValue('D1', "COMPLETED TEST COUNT");
              
                $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
             
                $i = 2;
                foreach ($data as $key => $value) {

                    $objPHPExcel->getActiveSheet()->setCellValue('A' . $i, ($i - 1));
                    $objPHPExcel->getActiveSheet()->setCellValue('B' . $i, ($value->analyst) ? $value->analyst : '');
                    $objPHPExcel->getActiveSheet()->setCellValue('C' . $i, ($value->assigned_tests) ? $value->assigned_tests : '0');
                    $objPHPExcel->getActiveSheet()->setCellValue('D' . $i, ($value->completed_tests) ? $value->completed_tests : '0');
                   
                    $i++;
                }

                $filename = 'Analyst_performance_report-' . date('Y-m-d-s') . ".xls";
                $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
                ob_end_clean();
                header('Content-type: application/vnd.ms-excel');
                header('Content-Disposition: attachment; filename=' . $filename);
                $objWriter->save('php://output');
            }
        }
    }
}
