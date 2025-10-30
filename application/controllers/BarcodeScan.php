<?php

class BarcodeScan extends MY_Controller{
    
    public function __construct() {
        parent::__construct();
        $this->check_session();
        $this->load->model('BarcodeModel', 'bs');
        
    }
    
    public function Barcode_Details(){
      $barcode_details =  $this->input->get();
      $barcode_code= $barcode_details['barcode'];
      $sample_time = $barcode_details['in_out'];
      $data = $this->bs->get_barcodeDetails($barcode_code, $sample_time);
//      echo "<pre>";print_r($data);die;
      if($data){
           echo json_encode($this->load->view('barcode_scan/barcode_scan_view', ['data' => $data], TRUE));
       }else{
           $data = "";
          echo json_encode($data); 
       }
     
     }
}
