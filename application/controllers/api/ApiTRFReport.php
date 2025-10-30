<?php 
defined('BASEPATH') or exit('No direct script access allowed');
require APPPATH . 'libraries/REST_Controller.php';
class ApiTRFReport extends REST_Controller{
    function __construct()
    {
        parent::__construct();
        $this->load->model('api/ApiTRFReportModel','atrm');
    }

    public function getTRFReport_get($buyer_id, $keyword, $applicant, $product, $from_date, $to_date, $start, $end){
        if ($keyword != NULL && $keyword != 'NULL')  $keyword = base64_decode($keyword);
        $data['trf_count'] = $this->atrm->getTRFReport($buyer_id, $keyword, $applicant, $product, $from_date, $to_date, NULL, NULL, true);
        $trf_report = $this->atrm->getTRFReport($buyer_id, $keyword, $applicant, $product, $from_date, $to_date, $start, $end);
        if($trf_report){
            $data['status'] = 1;
            $data['message'] = 'TRF Report List';
            $data['trf_report'] = $trf_report;
        } else {
            $data['status'] = 0;
            $data['message'] = 'No record found!.';
        }
        $this->set_response($data, REST_Controller::HTTP_OK);
    }

    public function getSampleReport_get($sampl_reg_id){
        $query = $this->db->select('manual_report_file, report_num')->where('sample_reg_id', $sampl_reg_id)->get('generated_reports');
        $report_file_data = $query->row_array();
        if($query->num_rows() > 0){
            $data['status'] = 1;
            $data['message'] = 'Report File';
            $data['report_file'] = $report_file_data;
        } else {
            $data['status'] = 0;
            $data['message'] = 'No record found!.';
        }
        $this->set_response($data, REST_Controller::HTTP_OK);
    }
}
?>