<?php
defined('BASEPATH') or exit('No script access allowed');
require APPPATH . 'libraries/REST_Controller.php';
class ApiTestController extends REST_Controller{
    function __construct()
    {
        parent::__construct();
        $this->load->model('api/ApiTestModel','atm');
    }

    public function getTest_get($product_id, $keyword){

        $test = $this->atm->getTest($product_id, $keyword);
        if($test){
            $data['status'] = 1;
            $data['message'] = 'Test List';
            $data['tests'] = $test;
        } else {
            $data['status'] = 0;
            $data['message'] = 'No record found!.';
        }
        $this->set_response($data, REST_Controller::HTTP_OK);
    }

    public function getTestMethod_get($test_id, $keyword){
        $test_id = $this->uri->segment(4);
        $test_method = $this->atm->getTestMethod($test_id, $keyword);
        if($test_method){
            $data['status'] = 1;
            $data['message'] = 'Test Method';
            $data['test_method'] = $test_method;
        } else {
            $data['status'] = 0;
            $data['message'] = 'No record found!.';
        }
        $this->set_response($data, REST_Controller::HTTP_OK);
    }

    public function getAPCInstruction_get(){
        $application_care = $this->atm->getAPCInstruction();
        if($application_care){
            $data['status'] = 1;
            $data['message'] = 'Application Care Instruction';
            $data['application_care'] = $application_care;
        } else {
            $data['status'] = 0;
            $data['message'] = 'No record found!.';
        }
        $this->set_response($data, REST_Controller::HTTP_OK);
    }

    public function getAPCInstructionImage_get($application_care_id){
        $application_care_image = $this->atm->getAPCInstructionImage($application_care_id);
        if($application_care_image){
            $data['status'] = 1;
            $data['message'] = 'Application Care Instruction';
            $data['application_care_image'] = $application_care_image;
        } else {
            $data['status'] = 0;
            $data['message'] = 'No record found!.';
        }
        $this->set_response($data, REST_Controller::HTTP_OK);
    }
}
?>