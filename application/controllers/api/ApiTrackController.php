<?php
defined('BASEPATH') or exit('No direct access allowed'); 
require APPPATH . 'libraries/REST_Controller.php';
class ApiTrackController extends REST_Controller{
    function __construct()
    {
        parent::__construct();
        $this->load->model('api/ApiTRFTrackModel','atm');
    }

    public function getTRFTrack_get($buyer_id, $keyword, $applicant, $product, $from_date, $to_date, $start, $end){
        $data['trf_count'] = $this->atm->getTRFTrack($buyer_id, $keyword, $applicant, $product, $from_date, $to_date, NULL, NULL, true);
        $data['trf'] = $this->atm->getTRFTrack($buyer_id, $keyword, $applicant, $product, $from_date, $to_date, $start, $end);
        if(!empty($data['trf'])){
            $data['status'] = 1;
            $data['message']  = "Track List";
           
         } else {
            $data['status'] = 0;
            $data['message'] = 'No record found!.';
         }
          $this->set_response($data, REST_Controller::HTTP_OK);
    }
}
?>