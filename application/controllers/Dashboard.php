<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->check_session();
        $this->load->model('Dashboard_model', 'dm');
        $this->load->model('To_do_list_model', 'TDL');
        $this->load->model('SampleRegistration', 'sr');
        $checkUser = $this->session->userdata('user_data');
        $this->user = $checkUser->uidnr_admin;

        set_time_limit(0);
        ini_set('memory_limit', '-1');
    }

    public function index()
    {
        $cust_where = NULL;
        $buyer_where = ['customer_type' => 'Buyer'];
        $agent_where = ['customer_type' => 'Applicant'];
        $lab_where = ['status' => '1'];
        $diviion_where = ['status' => '1'];
        $data['customer'] = $this->sr->get_result("customer_id,CONCAT(customer_name,' (Address - ',address,')') as customer_name", "cust_customers", $cust_where);
        $data['buyer'] = $this->sr->get_result("customer_id,customer_name", "cust_customers", $buyer_where);
        $data['agent'] = $this->sr->get_result("customer_id,customer_name", "cust_customers", $agent_where);
        $data['products'] = $this->sr->get_products();
        $data['sample_status'] = $this->sr->get_status();
        $data['division'] = $this->sr->get_fields("mst_divisions", "division_id,division_name",$diviion_where);
        $data['labType'] = $this->sr->get_result("lab_type_id,lab_type_name", "mst_lab_type", $lab_where);
        $data['report_reviewer'] = $this->sr->get_reviewer(); // report reviewer

        $data['todayRegisteredSample'] = $this->sr->getDashboardSamplesData(['DATE(sample_registration.create_on)' => date("Y-m-d")]);
        $data['totalRegisteredSample'] = $this->sr->getDashboardSamplesData(NULL);
        $data['totalHoldSample'] = $this->sr->getDashboardSamplesData(['status'=>'Hold Sample']);
        $data['todayHoldSample'] = $this->sr->getDashboardSamplesData(['status'=>'Hold Sample','DATE(shr.created_on)' => date("Y-m-d")]);
        $data['totalCancelled'] = $this->sr->getDashboardSamplesData(['status'=>'Login Cancelled']);
        $data['todayOverDue'] = $this->sr->getDashboardSamplesData(['DATE(due_date)' => date("Y-m-d"),'released_to_client' => '0','status !=' => 'Login Cancelled']);
        $data['totalOverDue'] = $this->sr->getDashboardSamplesData(['DATE(due_date) <=' => date("Y-m-d"),'released_to_client' => '0','status !=' => 'Login Cancelled']);
        $data['totalReport'] = $this->sr->getDashboardSamplesData(['status' => 'Report Generated']); // new
        // echo '<pre>';
        // print_r($data);die;
        $this->load_view('dashboard', $data);
    }


    public function getDashboardFilteredData(){
        $res = $this->input->post();
        $data['todayRegisteredSample'] = $this->sr->getDashboardFilteredData(['DATE(sr.create_on)' => date("Y-m-d")],$res,1);
        $data['totalRegisteredSample'] = $this->sr->getDashboardFilteredData(NULL,$res,0);
        $data['totalHoldSample'] = $this->sr->getDashboardFilteredData(['sr.status'=>'Hold Sample'],$res,0);
        $data['todayHoldSample'] = $this->sr->getDashboardFilteredData(['sr.status'=>'Hold Sample','DATE(shr.created_on)' => date("Y-m-d")],$res,1);
        $data['totalCancelled'] = $this->sr->getDashboardFilteredData(['sr.status'=>'Login Cancelled'],$res,0);
        $data['todayOverDue'] = $this->sr->getDashboardFilteredData(['DATE(sr.due_date)' => date("Y-m-d"),'sr.released_to_client' => '0','sr.status !=' => 'Login Cancelled'],$res,1);
        $data['totalOverDue'] = $this->sr->getDashboardFilteredData(['DATE(sr.due_date) <=' => date("Y-m-d"),'sr.released_to_client' => '0','sr.status !=' => 'Login Cancelled'],$res,0);
        $data['totalReport'] = $this->sr->getDashboardFilteredData(['status' => 'Report Generated'],$res,0); // new
      
        echo json_encode(['status'=>'1','data'=>$data]);
    }

    public function get_trfQuotesChart()
    {
        $filter = array();

        if ($this->input->get('quote_chart_filter')) {
            
            $quote_chart_filter = $this->input->get('quote_chart_filter');
            if ($quote_chart_filter[0] != "" && $quote_chart_filter[0] != NULL  && $quote_chart_filter[1] != "") {
                // $filter .= " AND qt.created_on >= '{$quote_chart_filter[0]}' AND qt.created_on <= '{$quote_chart_filter[1]}'";
                $filter["DATE(qt.created_on) >= '$quote_chart_filter[0]'"] = NULL;
                $filter["DATE(qt.created_on) <= '$quote_chart_filter[1]'"] = NULL;
            }
            if ($quote_chart_filter[2] != "" && $quote_chart_filter[2] > 0) {
                // $filter .= " AND qt.quotes_customer_id =" . $quote_chart_filter[6];
                $filter['qt.quotes_customer_id'] = $quote_chart_filter[2];
            }
              if ($quote_chart_filter[3] != "" && $quote_chart_filter[4] != "") {
                $filter['YEAR(qt.created_on)'] = $quote_chart_filter[3];
                $filter['MONTH(qt.created_on)'] = $quote_chart_filter[4];
            }
        }
      
        echo json_encode($this->dm->trfQuotesChart($filter));
    }

    public function get_sampleStatusChart()
    {
        $filter = " ";

        if ($this->input->get('sample_chart_filter')) {
            $quote_chart_filter = $this->input->get('sample_chart_filter');
            if ($quote_chart_filter[0] != "" && $quote_chart_filter[0] != NULL  && $quote_chart_filter[1] != "") {
                $filter .= " AND DATE(sr.create_on) >= '{$quote_chart_filter[0]}' AND DATE(sr.create_on) <= '{$quote_chart_filter[1]}'";
            }
            if ($quote_chart_filter[2] != "" && $quote_chart_filter[2] > 0) {
                $filter .= " AND tr.trf_buyer =" . $quote_chart_filter[2];
            }
            if ($quote_chart_filter[3] != "" && $quote_chart_filter[3] > 0) {
                $filter .= " AND tr.trf_applicant =" . $quote_chart_filter[3];
            }
            if ($quote_chart_filter[4] != "" && $quote_chart_filter[4] > 0) {

                $filter .= " AND sr.sample_registered_to_lab_id =" . $quote_chart_filter[4];
            }
            if ($quote_chart_filter[5] != "" && $quote_chart_filter[5] > 0) {

                $filter .= " AND tr.division =" . $quote_chart_filter[5];
            }
            if ($quote_chart_filter[6] != "" && $quote_chart_filter[6] > 0) {
                $filter .= " AND sr.sample_customer_id =" . $quote_chart_filter[6];
            }
             if ($quote_chart_filter[7] != "" && $quote_chart_filter[8] != "") {
                $filter .= " AND YEAR(sr.create_on) =" . $quote_chart_filter[7];
                $filter .= " AND MONTH(sr.create_on) =" . $quote_chart_filter[8];
                
            }
             if ($quote_chart_filter[9] != "" && $quote_chart_filter[9] > 0) {
                $filter .= " AND sr.report_reviewer_id =" . $quote_chart_filter[9];
            }// report reviewer
            
        }
        // echo '<pre>';
        // print_r($filter);die;
        echo json_encode($this->dm->sampleStatusChart($filter));
    }

    public function get_reportChart()
    {
        $filter = " ";

        if ($this->input->get('report_chart_filter')) {
            $quote_chart_filter = $this->input->get('report_chart_filter');
            if ($quote_chart_filter[0] != "" && $quote_chart_filter[0] != NULL  && $quote_chart_filter[1] != "") {
                $filter .= " AND DATE(sr.create_on) >= '{$quote_chart_filter[0]}' AND DATE(sr.create_on) <= '{$quote_chart_filter[1]}'";
            }
            if ($quote_chart_filter[2] != "" && $quote_chart_filter[2] > 0) {
                $filter .= " AND trf.trf_buyer =" . $quote_chart_filter[2];
            }
            if ($quote_chart_filter[3] != "" && $quote_chart_filter[3] > 0) {
                $filter .= " AND trf.trf_applicant =" . $quote_chart_filter[3];
            }
            if ($quote_chart_filter[4] != "" && $quote_chart_filter[4] > 0) {

                $filter .= " AND sr.sample_registered_to_lab_id =" . $quote_chart_filter[4];
            }
            if ($quote_chart_filter[5] != "" && $quote_chart_filter[5] > 0) {

                $filter .= " AND trf.division =" . $quote_chart_filter[5];
            }
            if ($quote_chart_filter[6] != "" && $quote_chart_filter[6] > 0) {
                $filter .= " AND sr.sample_customer_id =" . $quote_chart_filter[6];
            }
             if ($quote_chart_filter[7] != "" && $quote_chart_filter[8] != "") {
                 $filter .= " AND YEAR(sr.create_on) =" . $quote_chart_filter[7];
                $filter .= " AND MONTH(sr.create_on) =" . $quote_chart_filter[8];
            }
             // report reviewer
            if ($quote_chart_filter[9] != "" && $quote_chart_filter[9] > 0) {
                $filter .= " AND sr.report_reviewer_id =" . $quote_chart_filter[9];
            }
        }
        echo json_encode($this->dm->reportChart($filter));
    }

    public function get_customer_autosuggest()
    {
        $customer_string = $this->input->get('query');
        if ($customer_string != "") {
            echo json_encode($this->dm->customer_autosuggest($customer_string));
        } else {
            echo false;
        }
    }

    public function get_category_autosuggest()
    {
        $category_string = $this->input->get('query');
        if ($category_string != "") {
            echo json_encode($this->dm->category_autosuggest($category_string));
        } else {
            echo false;
        }
    }

    public function get_sample_autosuggest()
    {
        $sample_string = $this->input->get('query');
        if ($sample_string != "") {
            echo json_encode($this->dm->sample_autosuggest($sample_string));
        } else {
            echo false;
        }
    }



    public function get_to_list_count()
    {
        $count = $this->TDL->get_to_do_list(NULL, NULL, NULL, NULL, '1', $this->user);
        echo json_encode($count);
    }

    // END

    public function get_admin_user()
    {
        $key = ($this->input->get('key')) ? $this->input->get('key') : null;
        echo json_encode($this->dm->get_admin_user($key));
    }

    public function get_admin_customers()
    {
        $key = ($this->input->get('key')) ? $this->input->get('key') : null;
        echo json_encode($this->dm->get_admin_customers($key));
    }

    public function get_customersChart()
    {
        $filter = array();
        $created_by['cust.created_by'] = NULL;
        // echo "<pre>"; print_r($this->input->get()); die;
        if ($this->input->get('cust_chart_filter')) {
            $cust_chart_filter = $this->input->get('cust_chart_filter');

            if ($cust_chart_filter[0] != "" && $cust_chart_filter[0] != NULL) {
                $since = date("Y-m-d", strtotime("-" . $cust_chart_filter[0] . " days"));
                $filter['cust.created_on >='] = $since;
            }

            if (!empty($cust_chart_filter[1]) && $cust_chart_filter[1] != NULL) {
                $created_by['cust.created_by'] = $cust_chart_filter[1];
            } else {
                $created_by['cust.created_by'] = NULL;
            }

            if ($cust_chart_filter[2] != "" && $cust_chart_filter[2] != NULL  && $cust_chart_filter[3] != "") {

                $filter['cust.created_on >='] = $cust_chart_filter[2];
                $filter['cust.created_on <='] = $cust_chart_filter[3];
            }
        }
        echo json_encode($this->dm->get_customersChart($filter,$created_by));
    }

    public function export_customer_data(){
        $period = $this->input->post('cust_period');
        $created_by = $this->input->post('admin_users');
        $start_date = $this->input->post('cust_start_date');
        $end_date = $this->input->post('cust_end_date');
        $filter = '';
        if(!empty($period)){
            $since = date("Y-m-d", strtotime("-" . $period . " days"));
            $filter['cust.created_on >='] = $since;
        }

        if(!empty($start_date) && $end_date != ''){
            $filter['cust.created_on >='] = $start_date;
            $filter['cust.created_on <='] = $end_date;
        }

        $data = $this->dm->export_customer_data($filter,$created_by);
        if (!empty($data)) {
            $this->load->library('excel');
            $tmpfname = "example.xls";

            $excelReader = PHPExcel_IOFactory::createReaderForFile($tmpfname);
            $objPHPExcel = $excelReader->load($tmpfname);

            $objPHPExcel->getProperties()->setCreator("GEO-CHEM")
                ->setLastModifiedBy("GEO-CHEM")
                ->setTitle("Office 2007 XLS Customer Details")
                ->setSubject("Office 2007 XLS Customer Details")
                ->setDescription("Description for Customer Details")
                ->setKeywords("phpexcel office codeigniter php")
                ->setCategory("Customer Details file");

                $objPHPExcel->setActiveSheetIndex(0);
                $objPHPExcel->getActiveSheet()->setCellValue('A1', "SL NO.");
                $objPHPExcel->getActiveSheet()->setCellValue('B1', "Customer Name");
                $objPHPExcel->getActiveSheet()->setCellValue('C1', "State Name");
                $objPHPExcel->getActiveSheet()->setCellValue('D1', "Location Name");
                $objPHPExcel->getActiveSheet()->setCellValue('E1', "City Name");
                $objPHPExcel->getActiveSheet()->setCellValue('F1', "Country Name");
                $objPHPExcel->getActiveSheet()->setCellValue('G1', "Telephone");
                $objPHPExcel->getActiveSheet()->setCellValue('H1', "Email Address");
                $objPHPExcel->getActiveSheet()->setCellValue('I1', "Customer Type");
                $objPHPExcel->getActiveSheet()->setCellValue('J1', "Created By");
                $objPHPExcel->getActiveSheet()->setCellValue('K1', "Created On");
                $objPHPExcel->getActiveSheet()->setCellValue('L1', "Address");
                $objPHPExcel->getActiveSheet()->setCellValue('M1', "Credit Days");
                $objPHPExcel->getActiveSheet()->setCellValue('N1', "Mobile Number");
                $objPHPExcel->getActiveSheet()->setCellValue('O1', "Credit Limit");

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

                $i = 2;
            foreach ($data as $key => $value) {

                $objPHPExcel->getActiveSheet()->setCellValue('A' . $i, ($i - 1));
                $objPHPExcel->getActiveSheet()->setCellValue('B' . $i, ($value['customer_name']) ? $value['customer_name'] : '');
                $objPHPExcel->getActiveSheet()->setCellValue('C' . $i, ($value['province_name']) ? $value['province_name'] : '');
                $objPHPExcel->getActiveSheet()->setCellValue('D' . $i, ($value['location_name']) ? $value['location_name'] : '');
                $objPHPExcel->getActiveSheet()->setCellValue('E' . $i, ($value['city']) ? $value['city'] : '');
                $objPHPExcel->getActiveSheet()->setCellValue('F' . $i, ($value['country_name']) ? $value['country_name'] : '');
                $objPHPExcel->getActiveSheet()->setCellValue('G' . $i, ($value['telephone']) ? $value['telephone'] : '');
                $objPHPExcel->getActiveSheet()->setCellValue('H' . $i, ($value['email']) ? $value['email'] : '');
                $objPHPExcel->getActiveSheet()->setCellValue('I' . $i, ($value['customer_type']) ? $value['customer_type'] : '');
                $objPHPExcel->getActiveSheet()->setCellValue('J' . $i, ($value['created_by']) ? $value['created_by'] : '');
                $objPHPExcel->getActiveSheet()->setCellValue('K' . $i, ($value['created_on']) ? $value['created_on'] : '');
                $objPHPExcel->getActiveSheet()->setCellValue('L' . $i, ($value['address']) ? $value['address'] : '');
                $objPHPExcel->getActiveSheet()->setCellValue('M' . $i, ($value['credit']) ? $value['credit'] : '');
                $objPHPExcel->getActiveSheet()->setCellValue('N' . $i, ($value['mobile']) ? $value['mobile'] : '');
                $objPHPExcel->getActiveSheet()->setCellValue('O' . $i, ($value['credit_limit']) ? $value['credit_limit'] : '');
                $i++;
            }
            $filename = 'Customer_details-' . date('Y-m-d-s') . ".xls";
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
            ob_end_clean();
            header('Content-type: application/vnd.ms-excel');
            header('Content-Disposition: attachment; filename=' . $filename);
            $objWriter->save('php://output');
        }
    }

    public function get_opportunityChart()
    {
        $filter = "";
        $created_by = [];
        $customer = [];
        $opportunity_chart_filter = $this->input->get('opportunity_chart_filter');
        // echo "<pre>"; print_r($opportunity_chart_filter); die;

        if (!empty($opportunity_chart_filter[0]) || !empty($opportunity_chart_filter[1]) || !empty($opportunity_chart_filter[2]) || (!empty($opportunity_chart_filter[4]) && !empty($opportunity_chart_filter[5])) || !empty($opportunity_chart_filter[6])) {

            if (!empty($opportunity_chart_filter[0])) {
                $since = date("Y-m-d", strtotime("-" . $opportunity_chart_filter[0] . " days"));

                if (isset($opportunity_chart_filter[3]) && $opportunity_chart_filter[3] == 'closure_date') {
                    $filter .= "op.closure_date >= '" . $since . "' and ";
                } else {
                    $filter .= "op.created_on >= '" . $since . "' and ";
                }
            }
            if (!empty($opportunity_chart_filter[1]) && $opportunity_chart_filter[1] != 'NULL') {
                $created_by = $opportunity_chart_filter[1];
            }
            if (!empty($opportunity_chart_filter[2])) {

                $customer = $opportunity_chart_filter[2];
            }
            if (!empty($opportunity_chart_filter[4]) && !empty($opportunity_chart_filter[5])) {

                if (isset($opportunity_chart_filter[3]) && $opportunity_chart_filter[3] == 'closure_date') {
                    $filter .= "op.closure_date >= '{$opportunity_chart_filter[4]}' AND op.closure_date <= '{$opportunity_chart_filter[5]}'";
                } else {
                    $filter .= " op.created_on >= '{$opportunity_chart_filter[4]}' AND op.created_on <= '{$opportunity_chart_filter[5]}'";
                }
            }
            if (!empty($opportunity_chart_filter[6])) {
                $filter .= "op.opportunity_status ='" . $opportunity_chart_filter[6]."'";
            }
        } else {
            if (isset($opportunity_chart_filter[3]) && $opportunity_chart_filter[3] == 'closure_date') {
                $filter .= " op.opportunity_status = 'Won' AND YEAR(op.closure_date) = YEAR(CURDATE())";
            } else {
                $filter .= " op.opportunity_status = 'Won' AND YEAR(op.created_on) = YEAR(CURDATE())";
            }
        }
        // echo "<pre>"; print_r($filter); die;
        echo json_encode($this->dm->get_opportunityChart($filter, $opportunity_chart_filter[3],$created_by,$customer));
    }

    public function export_opportunity_data()
    {
        $filter = array();
        $created_by = [];
        $customer = [];

        $opportunity_period = $this->input->post('opportunity_period');
        $srch_opportunity_admin_user = $this->input->post('srch_opportunity_admin_user');
        $srch_opportunity_cust_user = $this->input->post('srch_opportunity_cust_user');
        $opportunity_date_type = $this->input->post('opportunity_date_type');
        $opportunity_start_date = $this->input->post('opportunity_start_date');
        $opportunity_end_date = $this->input->post('opportunity_end_date');
        $opportunity_opportunity_status = $this->input->post('opportunity_opportunity_status');

        if (!empty($opportunity_period) || !empty($srch_opportunity_admin_user) || !empty($srch_opportunity_cust_user) || (!empty($opportunity_start_date) && !empty($opportunity_end_date)) || !empty($opportunity_opportunity_status)) {

            if (!empty($opportunity_period)) {
                $since = date("Y-m-d", strtotime("-" . $opportunity_period . " days"));

                if ($opportunity_date_type == 'closure_date') {
                    $filter['op.closure_date >='] = $since;
                } else {
                    $filter['op.created_on >='] = $since;
                }
            }
            if (!empty($srch_opportunity_admin_user)) {

                $created_by = $srch_opportunity_admin_user;
            }
            if (!empty($srch_opportunity_cust_user)) {

                $customer = $srch_opportunity_cust_user;
            }
            if (!empty($opportunity_start_date) && !empty($opportunity_end_date)) {

                if ($opportunity_date_type == 'closure_date') {
                    $filter['op.closure_date >='] = $opportunity_start_date;
                    $filter['op.closure_date <='] = $opportunity_end_date;
                } else {
                    $filter['op.created_on >='] = $opportunity_start_date;
                    $filter['op.created_on <='] = $opportunity_end_date;
                }
            }
            if (!empty($opportunity_opportunity_status)) {

                $filter['op.opportunity_status'] = $opportunity_opportunity_status;
            }
        } else {
            $filter['op.opportunity_status'] = 'Won';
            if ($opportunity_date_type == 'closure_date') {
                $filter['YEAR(op.closure_date)'] = date("Y");
            } else {
                $filter['YEAR(op.created_on)'] = date("Y");
            }
        }

        $data = $this->dm->export_opportunity_data($filter,$created_by, $customer);

        if (!empty($data)) {
            $this->load->library('excel');
            $tmpfname = "example.xls";

            $excelReader = PHPExcel_IOFactory::createReaderForFile($tmpfname);
            $objPHPExcel = $excelReader->load($tmpfname);

            $objPHPExcel->getProperties()->setCreator("GEO-CHEM")
                ->setLastModifiedBy("GEO-CHEM")
                ->setTitle("Office 2007 XLS Opportunity Details")
                ->setSubject("Office 2007 XLS Opportunity Details")
                ->setDescription("Description for Opportunity Details")
                ->setKeywords("phpexcel office codeigniter php")
                ->setCategory("Opportunity Details file");

            $objPHPExcel->setActiveSheetIndex(0);
            $objPHPExcel->getActiveSheet()->setCellValue('A1', "SL NO.");
            $objPHPExcel->getActiveSheet()->setCellValue('B1', "CUSTOMER CODE");
            $objPHPExcel->getActiveSheet()->setCellValue('C1', "CUSTOMER NAME");
            $objPHPExcel->getActiveSheet()->setCellValue('D1', "CITY");
            $objPHPExcel->getActiveSheet()->setCellValue('E1', "TELEPHONE");
            $objPHPExcel->getActiveSheet()->setCellValue('F1', "FAX");
            $objPHPExcel->getActiveSheet()->setCellValue('G1', "EMAIL");
            $objPHPExcel->getActiveSheet()->setCellValue('H1', "STATUS");
            $objPHPExcel->getActiveSheet()->setCellValue('I1', "CUSTOMER TYPE");
            $objPHPExcel->getActiveSheet()->setCellValue('J1', "CREATED ON");
            $objPHPExcel->getActiveSheet()->setCellValue('K1', "UPDATED ON");
            $objPHPExcel->getActiveSheet()->setCellValue('L1', "CUSTOMER'S ADDRESS");
            $objPHPExcel->getActiveSheet()->setCellValue('M1', "CREDIT");
            $objPHPExcel->getActiveSheet()->setCellValue('N1', "MOBILE");
            $objPHPExcel->getActiveSheet()->setCellValue('O1', "PAN");
            $objPHPExcel->getActiveSheet()->setCellValue('P1', "GSTIN");
            $objPHPExcel->getActiveSheet()->setCellValue('Q1', "CREATED BY");
            $objPHPExcel->getActiveSheet()->setCellValue('R1', "OPPORTUNITY ID");
            $objPHPExcel->getActiveSheet()->setCellValue('S1', "OPPORTUNITY NAME");
            $objPHPExcel->getActiveSheet()->setCellValue('T1', "OPPORTUNITY VALUE");
            $objPHPExcel->getActiveSheet()->setCellValue('U1', "DESCRIPTION");
            $objPHPExcel->getActiveSheet()->setCellValue('V1', "TYPES");
            $objPHPExcel->getActiveSheet()->setCellValue('W1', "ESTIMATED CLOSURE DATE");
            $objPHPExcel->getActiveSheet()->setCellValue('X1', "OPPORTUNITY STATUS");
            $objPHPExcel->getActiveSheet()->setCellValue('Y1', "CLOSURE NOTE");
            $objPHPExcel->getActiveSheet()->setCellValue('Z1', "CLOSURE VALUE");

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
            $objPHPExcel->getActiveSheet()->getColumnDimension('R')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('S')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('T')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('U')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('V')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('W')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('X')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('Y')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('Z')->setAutoSize(true);

            $i = 2;
            foreach ($data as $key => $value) {

                $objPHPExcel->getActiveSheet()->setCellValue('A' . $i, ($i - 1));
                $objPHPExcel->getActiveSheet()->setCellValue('B' . $i, ($value->customer_code) ? $value->customer_code : '');
                $objPHPExcel->getActiveSheet()->setCellValue('C' . $i, ($value->customer_name) ? $value->customer_name : '');
                $objPHPExcel->getActiveSheet()->setCellValue('D' . $i, ($value->city) ? $value->city : '');
                $objPHPExcel->getActiveSheet()->setCellValue('E' . $i, ($value->telephone) ? $value->telephone : '');
                $objPHPExcel->getActiveSheet()->setCellValue('F' . $i, ($value->fax) ? $value->fax : '');
                $objPHPExcel->getActiveSheet()->setCellValue('G' . $i, ($value->email) ? $value->email : '');
                $objPHPExcel->getActiveSheet()->setCellValue('H' . $i, ($value->isactive) ? $value->isactive : '');
                $objPHPExcel->getActiveSheet()->setCellValue('I' . $i, ($value->customer_type) ? $value->customer_type : '');
                $objPHPExcel->getActiveSheet()->setCellValue('J' . $i, ($value->created_on) ? $value->created_on : '');
                $objPHPExcel->getActiveSheet()->setCellValue('K' . $i, ($value->updated_on) ? $value->updated_on : '');
                $objPHPExcel->getActiveSheet()->setCellValue('L' . $i, ($value->address) ? $value->address : '');
                $objPHPExcel->getActiveSheet()->setCellValue('M' . $i, ($value->credit) ? $value->credit : '');
                $objPHPExcel->getActiveSheet()->setCellValue('N' . $i, ($value->mobile) ? $value->mobile : '');
                $objPHPExcel->getActiveSheet()->setCellValue('O' . $i, ($value->pan) ? $value->pan : '');
                $objPHPExcel->getActiveSheet()->setCellValue('P' . $i, ($value->gstin) ? $value->gstin : '');
                $objPHPExcel->getActiveSheet()->setCellValue('Q' . $i, ($value->adm_created_by) ? $value->adm_created_by : '');
                $objPHPExcel->getActiveSheet()->setCellValue('R' . $i, ($value->opportunity_id) ? $value->opportunity_id : '');
                $objPHPExcel->getActiveSheet()->setCellValue('S' . $i, ($value->opportunity_name) ? $value->opportunity_name : '');
                $objPHPExcel->getActiveSheet()->setCellValue('T' . $i, ($value->opportunity_value) ? $value->opportunity_value : '');
                $objPHPExcel->getActiveSheet()->setCellValue('U' . $i, ($value->description) ? $value->description : '');
                $objPHPExcel->getActiveSheet()->setCellValue('V' . $i, ($value->types) ? $value->types : '');
                $objPHPExcel->getActiveSheet()->setCellValue('W' . $i, ($value->estimated_closure_date) ? $value->estimated_closure_date : '');
                $objPHPExcel->getActiveSheet()->setCellValue('X' . $i, ($value->opportunity_status) ? $value->opportunity_status : '');
                $objPHPExcel->getActiveSheet()->setCellValue('Y' . $i, ($value->closure_note) ? $value->closure_note : '');
                $objPHPExcel->getActiveSheet()->setCellValue('Z' . $i, ($value->closure_value) ? $value->closure_value : '');
                $i++;
            }

            $filename = 'Opportunity_details-' . date('Y-m-d-s') . ".xls";
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
            ob_end_clean();
            header('Content-type: application/vnd.ms-excel');
            header('Content-Disposition: attachment; filename=' . $filename);
            $objWriter->save('php://output');
        }
    }

    public function get_performaChart()
    {
        $filter = "";
        $performa_chart_filter = $this->input->get('performa_chart_filter');
        $created_by = [];
        $customer = [];
        if (!empty($performa_chart_filter[0]) || !empty($performa_chart_filter[1]) || !empty($performa_chart_filter[2]) || (!empty($performa_chart_filter[3]) && !empty($performa_chart_filter[4])) || !empty($performa_chart_filter[5])) {

            if (!empty($performa_chart_filter[0])) {
                $since = date("Y-m-d", strtotime("-" . $performa_chart_filter[0] . " days"));

                $filter .= "op.proforma_invoice_date >= '" . $since . "'";
            }
            if (!empty($performa_chart_filter[1])) {

                $created_by = $performa_chart_filter[1];
            }
            if (!empty($performa_chart_filter[2])) {

                $customer =  $performa_chart_filter[2];
            }
            if (!empty($performa_chart_filter[3]) && !empty($performa_chart_filter[4])) {

                $filter .= "op.proforma_invoice_date >= '{$performa_chart_filter[3]}' AND op.proforma_invoice_date <= '{$performa_chart_filter[4]}'";
            }
            if (!empty($performa_chart_filter[5])) {

                $filter .= "op.invoice_proforma_invoice_status_id =" . $performa_chart_filter[5];
            }
        } else {
            $filter .= "YEAR(op.proforma_invoice_date) = YEAR(CURDATE())";
        }
        echo json_encode($this->dm->get_performaChart($filter,$created_by, $customer));
    }

    public function export_performa_data()
    {
        $filter = array();
        $created_by = [];
        $customer = [];

        $performa_period = $this->input->post('performa_period');
        $srch_performa_admin_user = $this->input->post('srch_performa_admin_user');
        $srch_performa_cust_user = $this->input->post('srch_performa_cust_user');
        $performa_start_date = $this->input->post('performa_start_date');
        $performa_end_date = $this->input->post('performa_end_date');
        $performa_performa_status = $this->input->post('performa_performa_status');

        if (!empty($performa_period) || !empty($srch_performa_admin_user) || !empty($srch_performa_cust_user) || (!empty($performa_start_date) && !empty($performa_end_date)) || !empty($performa_performa_status)) {

            if (!empty($performa_period)) {
                $since = date("Y-m-d", strtotime("-" . $performa_period . " days"));

                $filter['op.proforma_invoice_date >='] = $since;
            }
            if (!empty($srch_performa_admin_user)) {

                $created_by = $srch_performa_admin_user;
            }
            if (!empty($srch_performa_cust_user)) {

                $customer = $srch_performa_cust_user;
            }
            if (!empty($performa_start_date) && !empty($performa_end_date)) {

                $filter['op.proforma_invoice_date >='] = $performa_start_date;
                $filter['op.proforma_invoice_date <='] = $performa_end_date;
            }
            if (!empty($performa_performa_status)) {

                $filter['op.invoice_proforma_invoice_status_id'] = $performa_performa_status;
            }
        } else {
            $filter['op.invoice_proforma_invoice_status_id'] = 1;
            $filter['YEAR(op.proforma_invoice_date)'] = date("Y");
        }

        $data = $this->dm->export_performa_data($filter, $created_by, $customer);

        if (!empty($data)) {
            $this->load->library('excel');
            $tmpfname = "example.xls";

            $excelReader = PHPExcel_IOFactory::createReaderForFile($tmpfname);
            $objPHPExcel = $excelReader->load($tmpfname);

            $objPHPExcel->getProperties()->setCreator("GEO-CHEM")
                ->setLastModifiedBy("GEO-CHEM")
                ->setTitle("Office 2007 XLS Performa Details")
                ->setSubject("Office 2007 XLS Performa Details")
                ->setDescription("Description for Performa Details")
                ->setKeywords("phpexcel office codeigniter php")
                ->setCategory("Performa Details file");

            $objPHPExcel->setActiveSheetIndex(0);
            $objPHPExcel->getActiveSheet()->setCellValue('A1', "SL NO.");
            $objPHPExcel->getActiveSheet()->setCellValue('B1', "CUSTOMER CODE");
            $objPHPExcel->getActiveSheet()->setCellValue('C1', "CUSTOMER NAME");
            $objPHPExcel->getActiveSheet()->setCellValue('D1', "CITY");
            $objPHPExcel->getActiveSheet()->setCellValue('E1', "TELEPHONE");
            $objPHPExcel->getActiveSheet()->setCellValue('F1', "FAX");
            $objPHPExcel->getActiveSheet()->setCellValue('G1', "EMAIL");
            $objPHPExcel->getActiveSheet()->setCellValue('H1', "STATUS");
            $objPHPExcel->getActiveSheet()->setCellValue('I1', "CUSTOMER TYPE");
            $objPHPExcel->getActiveSheet()->setCellValue('J1', "CREATED ON");
            $objPHPExcel->getActiveSheet()->setCellValue('K1', "UPDATED ON");
            $objPHPExcel->getActiveSheet()->setCellValue('L1', "CUSTOMER'S ADDRESS");
            $objPHPExcel->getActiveSheet()->setCellValue('M1', "CREDIT");
            $objPHPExcel->getActiveSheet()->setCellValue('N1', "MOBILE");
            $objPHPExcel->getActiveSheet()->setCellValue('O1', "PAN");
            $objPHPExcel->getActiveSheet()->setCellValue('P1', "GSTIN");
            $objPHPExcel->getActiveSheet()->setCellValue('Q1', "CREATED BY");
            $objPHPExcel->getActiveSheet()->setCellValue('R1', "Performa ID");
            $objPHPExcel->getActiveSheet()->setCellValue('S1', "DISCOUNT");
            $objPHPExcel->getActiveSheet()->setCellValue('T1', "INVOICE DATE");
            $objPHPExcel->getActiveSheet()->setCellValue('U1', "TOTAL AMOUNT");
            $objPHPExcel->getActiveSheet()->setCellValue('V1', "INVOICE STATUS");
            $objPHPExcel->getActiveSheet()->setCellValue('W1', "SAMPLE REG. ID");
            $objPHPExcel->getActiveSheet()->setCellValue('X1', "INVOICE NUMBER");
            $objPHPExcel->getActiveSheet()->setCellValue('Y1', "CLIENT ADDRESS");
            $objPHPExcel->getActiveSheet()->setCellValue('Z1', "CLIENT CONTACT");

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
            $objPHPExcel->getActiveSheet()->getColumnDimension('R')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('S')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('T')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('U')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('V')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('W')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('X')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('Y')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('Z')->setAutoSize(true);

            $i = 2;
            foreach ($data as $key => $value) {

                $objPHPExcel->getActiveSheet()->setCellValue('A' . $i, ($i - 1));
                $objPHPExcel->getActiveSheet()->setCellValue('B' . $i, ($value->customer_code) ? $value->customer_code : '');
                $objPHPExcel->getActiveSheet()->setCellValue('C' . $i, ($value->customer_name) ? $value->customer_name : '');
                $objPHPExcel->getActiveSheet()->setCellValue('D' . $i, ($value->city) ? $value->city : '');
                $objPHPExcel->getActiveSheet()->setCellValue('E' . $i, ($value->telephone) ? $value->telephone : '');
                $objPHPExcel->getActiveSheet()->setCellValue('F' . $i, ($value->fax) ? $value->fax : '');
                $objPHPExcel->getActiveSheet()->setCellValue('G' . $i, ($value->email) ? $value->email : '');
                $objPHPExcel->getActiveSheet()->setCellValue('H' . $i, ($value->isactive) ? $value->isactive : '');
                $objPHPExcel->getActiveSheet()->setCellValue('I' . $i, ($value->customer_type) ? $value->customer_type : '');
                $objPHPExcel->getActiveSheet()->setCellValue('J' . $i, ($value->created_on) ? $value->created_on : '');
                $objPHPExcel->getActiveSheet()->setCellValue('K' . $i, ($value->updated_on) ? $value->updated_on : '');
                $objPHPExcel->getActiveSheet()->setCellValue('L' . $i, ($value->address) ? $value->address : '');
                $objPHPExcel->getActiveSheet()->setCellValue('M' . $i, ($value->credit) ? $value->credit : '');
                $objPHPExcel->getActiveSheet()->setCellValue('N' . $i, ($value->mobile) ? $value->mobile : '');
                $objPHPExcel->getActiveSheet()->setCellValue('O' . $i, ($value->pan) ? $value->pan : '');
                $objPHPExcel->getActiveSheet()->setCellValue('P' . $i, ($value->gstin) ? $value->gstin : '');
                $objPHPExcel->getActiveSheet()->setCellValue('Q' . $i, ($value->adm_created_by) ? $value->adm_created_by : '');
                $objPHPExcel->getActiveSheet()->setCellValue('R' . $i, ($value->proforma_invoice_id) ? $value->proforma_invoice_id : '');
                $objPHPExcel->getActiveSheet()->setCellValue('S' . $i, ($value->proforma_discount) ? $value->proforma_discount : '');
                $objPHPExcel->getActiveSheet()->setCellValue('T' . $i, ($value->proforma_invoice_date) ? $value->proforma_invoice_date : '');
                $objPHPExcel->getActiveSheet()->setCellValue('U' . $i, ($value->total_amount) ? $value->total_amount : '');
                $objPHPExcel->getActiveSheet()->setCellValue('V' . $i, ($value->invoice_status_name) ? $value->invoice_status_name : '');
                $objPHPExcel->getActiveSheet()->setCellValue('W' . $i, ($value->proforma_invoice_sample_reg_id) ? $value->proforma_invoice_sample_reg_id : '');
                $objPHPExcel->getActiveSheet()->setCellValue('X' . $i, ($value->proforma_invoice_number) ? $value->proforma_invoice_number : '');
                $objPHPExcel->getActiveSheet()->setCellValue('Y' . $i, ($value->proforma_client_address) ? $value->proforma_client_address : '');
                $objPHPExcel->getActiveSheet()->setCellValue('Z' . $i, ($value->pro_client_contact) ? $value->pro_client_contact : '');
                $i++;
            }

            $filename = 'performa_details-' . date('Y-m-d-s') . ".xls";
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
            ob_end_clean();
            header('Content-type: application/vnd.ms-excel');
            header('Content-Disposition: attachment; filename=' . $filename);
            $objWriter->save('php://output');
        }
    }
    // END ----------------
}
