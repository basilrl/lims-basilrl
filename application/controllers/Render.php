<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Render extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Render_model');  
    }
        
    public function download_pdf()  // updated by millan on 13 july 2020
    {
        $where = array();
        // print_r($this->input->get());die;
       $get = $this->input->get();
        // print_r($where);exit;
        $path = $this->Render_model->download_pdf(['report_id'=> base64_decode($get['report_id'])]);
        if($path){
            if (!empty($path->manual_report_file)){
                $this->load->helper('download');
                $pth    =   file_get_contents($path->manual_report_file);
                $nme    =   basename($path->manual_report_file);
                force_download($nme, $pth); 
            }
            else{
                echo '<h1>NO RECORD FOUND</h1>';
            }
       }else{
        echo '<h1>“NO RECORD FOUND"</h1>';
        }
    }

    /* added by millan on 19-Jan-2021 for scan and download pdf */
    public function download_qr()
    {
        $get = $this->input->get();
        $where = base64_decode($get['sample_reg_id']);
        $path = $this->Render_model->download_qr($where);
        if ($path) {
            if ($path->manual_report_file) {
                $this->load->helper('download');
                $pdf_path    =   file_get_contents($path->manual_report_file);
                $pdf_name    =   basename($path->manual_report_file);
                force_download($pdf_name, $pdf_path);
            } else {
                echo '<h1>NO RECORD FOUND</h1>';
            }
        } else {
            echo '<h1>“This pdf stands cancelled. Please do not transact based on this cancelled pdf. Geo Chem will not be liable for any issues, financial, legal or otherwise, based on using this cancelled pdf for any purpose.</h1>';
        }
    }

    // Added by saurabh on 02-07-2021 to get report file PDF(Partial/Revise)
    public function download_report()
    {
        $get = $this->input->get();
        $where = base64_decode($get['report_number']);
        $path = $this->Render_model->download_report($where);
        if ($path) {
            if ($path->manual_report_file) {
                $this->load->helper('download');
                $pdf_path    =   file_get_contents($path->manual_report_file);
                $pdf_name    =   basename($path->manual_report_file);
                force_download($pdf_name, $pdf_path);
            } else {
                echo '<h1>NO RECORD FOUND</h1>';
            }
        } else {
            echo '<h1>“This pdf stands cancelled. Please do not transact based on this cancelled pdf. Geo Chem will not be liable for any issues, financial, legal or otherwise, based on using this cancelled pdf for any purpose.</h1>';
        }
    }

   

  
}
