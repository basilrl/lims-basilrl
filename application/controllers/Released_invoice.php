<?php
class Released_invoice extends MY_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('Released_invoice_model', 'rim');
        $this->check_session();
        $checkUser = $this->session->userdata('user_data');
        $this->user = $checkUser->uidnr_admin;
        $this->load->helper('common');

    }


    public function released_invoice_list($applicant='NULL', $product='NULL', $search_url='NULL', $start_date='NULL', $end_date='NULL')
    {
        // echo "rkfbekjr";die;
        $where = array();
        $search = NULL;
        // echo $applicant . " " . $product . " " . $search . " " . $start_date . " " . $end_date . " " . $stauts;
        $base_url = "Released_invoice/released_invoice_list";
        $base_url .= '/' . (($applicant != 'NULL') ? $applicant : 'NULL');
        $base_url .= '/' . (($product != 'NULL') ? $product : 'NULL');
        $base_url .= '/' . (($search_url != 'NULL') ? ($search_url) : 'NULL');
        $base_url .= '/' . (($start_date != 'NULL') ? $start_date : 'NULL');
        $base_url .= '/' . (($end_date != 'NULL') ? $end_date : 'NULL');
        // $base_url .= '/' . (($stauts != 'NULL') ? ($stauts) : 'NULL');
        $data['applicant_id'] = ($applicant != 'NULL') ? $applicant : 'NULL';
        $data['product_id'] = ($product != 'NULL') ? $product : 'NULL';
        $data['search_url'] = ($search_url != 'NULL') ? base64_decode($search_url) : 'NULL';
        $data['start_date'] = ($start_date != 'NULL') ? $start_date : 'NULL';
        $data['end_date'] = ($end_date != 'NULL') ? $end_date : 'NULL';
        // $data['stauts'] = ($stauts != 'NULL') ? base64_decode($stauts) : 'NULL';
        if ($applicant!='NULL') {
          $customer = $this->rim->get_row('customer_name','cust_customers',['customer_id'=>$applicant]);
          if ($customer) {
            $data['applicant_name'] = $customer->customer_name;
          } else {
            $data['applicant_name'] = 'NULL';
          }
        }else{
            $data['applicant_name'] = 'NULL';
        }
        if ($product!='NULL') {
            $customer = $this->rim->get_row('sample_type_name','mst_sample_types',['sample_type_id'=>$product]);
            if ($customer) {
              $data['product_name'] = $customer->sample_type_name;
            } else {
              $data['product_name'] = 'NULL';
            }
          }else{
              $data['product_name'] = 'NULL';
          }
        if ($applicant!= 'NULL') {
             $where['tr.trf_applicant']= $applicant;
        }
        if ($product!= 'NULL') {
            $where['sr.sample_registration_sample_type_id']=$product;
        }
        if ($search_url!= 'NULL') {
            $search = base64_decode($search_url);
        }
        if ($start_date!= 'NULL') {
            $where['sr.received_date >=']= ($start_date);
        }
        if ($end_date!= 'NULL') {
            $where['sr.received_date <='] = ($end_date);
        }
        // if ($stauts!= 'NULL') {
        //     $where['sr.status'] = base64_decode($stauts);
        // }
        $total_row = $this->rim->get_invoice_list(NULL, NULL,$where,$search, '1');

        $page = ($this->uri->segment(8)) ? $this->uri->segment(8) : 0;
        $config = $this->pagination($base_url, $total_row, 10, 8);

        $data["links"] = $config["links"];
        $data['report_listing'] =  $this->rim->get_invoice_list($config["per_page"], $page,$where,$search);

        if($total_row > 0){
            $start = (int)$page + 1;
        } else {
            $start = 0;
        }
        $end = (($data['report_listing']) ? count($data['report_listing']) : 0) + (($page) ? $page : 0);
        $data['result_count'] = "Showing " . $start . " - " . $end . " of " . $total_row . " Results";
        $this->session->set_userdata('released_invoice_export',$this->db->last_query());
        // $data['sign_auth'] =  $this->rrm->get_report_approver();
        // $data['sign_auth'] =  $this->mlm->get_report_approver();

        $this->load_view('released_invoice/released_invoice', $data);
    }

    public function export_excel()
    {
      $query = $this->session->userdata('released_invoice_export');
        $result = $this->db->query($query);
        // echo $this->db->last_query(); die;
        if ($result->num_rows() > 0) {
            $result = $result->result();
            $this->load->library('excel');
            $tmpfname = "example.xls";

            $excelReader = PHPExcel_IOFactory::createReaderForFile($tmpfname);
            $objPHPExcel = $excelReader->load($tmpfname);

            $objPHPExcel->getProperties()->setCreator("GEO-CHEM")
                ->setLastModifiedBy("GEO-CHEM")
                ->setTitle("Office 2007 XLS REVENUE Document")
                ->setSubject("Office 2007 XLS REVENUE Document")
                ->setDescription("Description for REVENUE Document")
                ->setKeywords("phpexcel office codeigniter php")
                ->setCategory("REVENUE details file");


            $objPHPExcel->setActiveSheetIndex(0);
            $objPHPExcel->getActiveSheet()->setCellValue('A1', "ULR Number");
            $objPHPExcel->getActiveSheet()->setCellValue('B1', "Basil Report NUMBER");
            $objPHPExcel->getActiveSheet()->setCellValue('C1', "Sample Description");
            $objPHPExcel->getActiveSheet()->setCellValue('D1', "Sample Service Type");
            $objPHPExcel->getActiveSheet()->setCellValue('E1', "Client");
            $objPHPExcel->getActiveSheet()->setCellValue('F1', "TRF Ref No.");
            $objPHPExcel->getActiveSheet()->setCellValue('G1', "Product");
            $objPHPExcel->getActiveSheet()->setCellValue('H1', "Recieved Date");
            $objPHPExcel->getActiveSheet()->setCellValue('I1', "Status");
            $objPHPExcel->getActiveSheet()->setCellValue('J1', "Due Date");
            $objPHPExcel->getActiveSheet()->setCellValue('K1', "Quantity Recieved");
            $objPHPExcel->getActiveSheet()->setCellValue('L1', "Report Number");




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

            $i = 2;
            foreach ($result as $key => &$value) {
                $objPHPExcel->getActiveSheet()->setCellValue('A' . $i, (($value->ulr_no) ? $value->ulr_no : ''));
                $objPHPExcel->getActiveSheet()->setCellValue('B' . $i, ($value->gc_no) ? $value->gc_no : '');
                $objPHPExcel->getActiveSheet()->setCellValue('C' . $i, ($value->sample_desc) ? $value->sample_desc : '');
                $objPHPExcel->getActiveSheet()->setCellValue('D' . $i, ($value->sample_service_type) ? $value->sample_service_type : '');
                $objPHPExcel->getActiveSheet()->setCellValue('E' . $i, ($value->client) ? $value->client : '');
                $objPHPExcel->getActiveSheet()->setCellValue('F' . $i, ($value->trf_ref_no) ? $value->trf_ref_no : '');
                $objPHPExcel->getActiveSheet()->setCellValue('G' . $i, ($value->product_name) ? $value->product_name : '');
                $objPHPExcel->getActiveSheet()->setCellValue('H' . $i, ($value->received_date) ? $value->received_date : '');
                $objPHPExcel->getActiveSheet()->setCellValue('I' . $i, ($value->status) ? $value->status : '');
                $objPHPExcel->getActiveSheet()->setCellValue('J' . $i, ($value->due_date) ? $value->due_date : '');
                $objPHPExcel->getActiveSheet()->setCellValue('K' . $i, ($value->qty_received) ? $value->qty_received : '');
                $objPHPExcel->getActiveSheet()->setCellValue('K' . $i, ($value->report_num) ? $value->report_num : '');
                $i++;
            }

            $filename = 'Released-invoice-' . date('Y-m-d-s') . ".xls";
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
            ob_end_clean();
            header('Content-type: application/vnd.ms-excel');
            header('Content-Disposition: attachment; filename=' . $filename);
            $objWriter->save('php://output');
        }
    }
}
