<?php

defined('BASEPATH') or exit('No direct access allowed');

class Cron extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('Cron_model', 'cron');
    }

    public function index() {
            $this->sendTATReportBYDivision();
            $this->equipment_maintenance();
            $this->sample_status_cron();
            $this->customerInactive();
    }

    public function sendTATReportBYDivision() {
        // echo "erigufv";die;
        set_time_limit(0);
        $divisions = $this->cron->get_division();

        foreach ($divisions as $division) {
            $file = $this->cron->sendTATReportBYDivision($division['division_id'], $division['division_name']);
            //  echo $file;die;
            if (!empty($file)) {
                $message = " Dear Sir/Madam ,"
                        . " <br><br>Please find the attached sample details:- "
                        . "<br><br>";
                $CI = &get_instance();
                $CI->load->library('email');
                $config['protocol'] = PROTOCOL;
                $config['smtp_host'] = HOST;
                $config['smtp_user'] = USER;
                $config['smtp_pass'] = PASS;
                $config['smtp_port'] = PORT;
                $config['smtp_crypto'] = CRYPTO;
                $config['charset'] = 'utf-8';
                $config['newline'] = "\r\n";
                $config['mailtype'] = 'html';
                $CI->email->initialize($config);
                $CI->email->from(FROM, 'BASIL');
                if (INSTANCE_TYPE == "development") {
                    $CI->email->to('shankar.k@basilrl.com');
                } else {
                    if (BRANCH == "GURGAON") {
                        if ($division['division_name'] == 'Textiles') {
                            // $CI->email->to('developer.cps@basilrl.com', 'Deepa');
                            $CI->email->to('nitin.j@basilrl.com', 'Nitin Jangra');
                            $CI->email->cc('om.p@basilrl.com');
                            $CI->email->cc('manish.k@basilrl.com');
                        } elseif ($division['division_name'] == 'Analytical') {
                            // $CI->email->to('developer.cps01@basilrl.com', 'Deepa');
                            $CI->email->to('shobhit.shrivastav@basilrl.com', 'Shobhit shrivastav');
                            $CI->email->cc('om.p@basilrl.com');
                            $CI->email->cc('manish.k@basilrl.com');
                        } elseif ($division['division_name'] == 'Footwear') {
                            // $CI->email->to('developer.cps02@basilrl.com', 'Deepa');
                            $CI->email->to('ranjeet.t@basilrl.com', 'Ranjeet Thakur');
                            $CI->email->cc('anirudh.s@basilrl.com');
                            $CI->email->cc('om.p@basilrl.com');
                            $CI->email->cc('manish.k@basilrl.com');
                        } elseif ($division['division_name'] == 'Toys') {
                            // $CI->email->to('developer.cps03@basilrl.com', 'Deepa');
                            $CI->email->to('vinod.s@basilrl.com', 'Vinod Saini');
                            $CI->email->cc('bharat.s@basilrl.com');
                            $CI->email->cc('om.p@basilrl.com');
                            $CI->email->cc('manish.k@basilrl.com');
                        } elseif ($division['division_name'] == 'Hardlines') {
                            // $CI->email->to('developer.cps04@basilrl.com', 'Deepa');
                            $CI->email->to('deepak.k@basilrl.com', 'Deepak Kumar');
                            $CI->email->cc('bharat.s@basilrl.com');
                            $CI->email->cc('om.p@basilrl.com');
                            $CI->email->cc('manish.k@basilrl.com');
                        } elseif ($division['division_name'] == 'Electricals') {
                            // $CI->email->to('developer.cps05@basilrl.com', 'Deepa');
                            $CI->email->to('saurabh.s@basilrl.com', 'Saurabh Srivastav');
                            $CI->email->cc('om.p@basilrl.com');
                            $CI->email->cc('manish.k@basilrl.com');
                        } else {
                            $CI->email->to('shankar.k@basilrl.com', 'Shankar Kumar');
                        }
                    } //END GURGAON BRANCH
                    else if (BRANCH == "DUBAI") {
                        if ($division['division_name'] == 'Textiles') {

                            $CI->email->to('lab2.cpsdubai@basilrl.com');
                            $CI->email->cc('manish.k@basilrl.com');
                        } elseif ($division['division_name'] == 'Analytical') {

                            $CI->email->to('lab2.cpsdubai@basilrl.com');
                            $CI->email->cc('manish.k@basilrl.com');
                        } elseif ($division['division_name'] == 'Footwear') {

                            $CI->email->to('lab2.cpsdubai@basilrl.com');
                            $CI->email->cc('manish.k@basilrl.com');
                        } elseif ($division['division_name'] == 'Toys') {

                            $CI->email->to('lab2.cpsdubai@basilrl.com');
                            $CI->email->cc('manish.k@basilrl.com');
                        } elseif ($division['division_name'] == 'Hardlines') {

                            $CI->email->to('lab2.cpsdubai@basilrl.com');
                            $CI->email->cc('manish.k@basilrl.com');
                        } else {
                            $CI->email->to('shankar.k@basilrl.com', 'Shankar Kumar');
                        }
                    } //END DUBAI BRANCH
                    else {
                        if ($division['division_name'] == 'Textiles') {

                            $CI->email->to('pretesting.sl@basilrl.com');
                            $CI->email->cc('masum.m@basilrl.com');
                            $CI->email->cc('manish.k@basilrl.com');
                        } elseif ($division['division_name'] == 'Analytical') {

                            $CI->email->to('pretesting.sl@basilrl.com');
                            $CI->email->cc('masum.m@basilrl.com');
                            $CI->email->cc('manish.k@basilrl.com');
                        } elseif ($division['division_name'] == 'Footwear') {

                            $CI->email->to('pretesting.sl@basilrl.com');
                            $CI->email->cc('masum.m@basilrl.com');
                            $CI->email->cc('manish.k@basilrl.com');
                        } elseif ($division['division_name'] == 'Toys') {

                            $CI->email->to('pretesting.sl@basilrl.com');
                            $CI->email->cc('masum.m@basilrl.com');
                            $CI->email->cc('manish.k@basilrl.com');
                        } elseif ($division['division_name'] == 'Hardlines') {

                            $CI->email->to('pretesting.sl@basilrl.com');
                            $CI->email->cc('masum.m@basilrl.com');
                            $CI->email->cc('manish.k@basilrl.com');
                        } else {
                            $CI->email->to('shankar.k@basilrl.com', 'Shankar Kumar');
                        }
                    }
                }



                $CI->email->cc('shankar.k@basilrl.com', 'Shankar Kumar');
                $CI->email->subject($division['division_name'] . " - Sample Report TAT date on " . date('d-m-Y'));
                $CI->email->message($message);
                $CI->email->attach($file);
                $CI->email->send();
                $CI->email->clear(TRUE);
                unlink($file);
            }
        }
    }

    public function sendBackLogsTATReportBYDivision() {
        set_time_limit(0);
        $divisions = $this->cron->get_division();
        foreach ($divisions as $division) {
            // sleep(10);
            $file = null;
            $file = $this->cron->sendBackLogsTATReportBYDivision($division['division_id'], $division['division_name']);


            if (!empty($file)) {
                $message = " Dear Sir/Madam ,"
                        . " <br><br>Please check division by backlog list and please upload it and make backlog zero into the system."
                        . "<br><br>";

                $CI = &get_instance();
                $CI->load->library('email');
                $config['protocol'] = PROTOCOL;
                $config['smtp_host'] = HOST;
                $config['smtp_user'] = USER;
                $config['smtp_pass'] = PASS;
                $config['smtp_port'] = PORT;
                $config['newline'] = "\r\n";
                $config['smtp_crypto'] = CRYPTO;
                $config['charset'] = 'utf-8';
                $config['newline'] = "\r\n";
                $config['mailtype'] = 'html';
                $CI->email->initialize($config);
                $CI->email->from(FROM, 'BASIL');

                /*    if($division['division_name']=='Textiles'){

                  $CI->email->to('nitin.j@basilrl.com', 'Nitin Jangra');
                  $CI->email->cc('om.p@basilrl.com');
                  $CI->email->cc('manish.k@basilrl.com');
                  }
                  if($division['division_name']=='Analytical'){
                  $CI->email->to('shobhit.shrivastav@basilrl.com', 'Shobhit shrivastav');
                  $CI->email->cc('om.p@basilrl.com');
                  $CI->email->cc('manish.k@basilrl.com');
                  }
                  if($division['division_name']=='Footwear'){
                  $CI->email->to('ranjeet.t@basilrl.com', 'Ranjeet Thakur');
                  $CI->email->cc('anirudh.s@basilrl.com');
                  $CI->email->cc('om.p@basilrl.com');
                  $CI->email->cc('manish.k@basilrl.com');
                  }
                  if($division['division_name']=='Toys'){
                  $CI->email->to('vinod.s@basilrl.com', 'Vinod Saini');
                  $CI->email->cc('bharat.s@basilrl.com');
                  $CI->email->cc('om.p@basilrl.com');
                  $CI->email->cc('manish.k@basilrl.com');
                  }
                  if($division['division_name']=='Hardlines'){
                  $CI->email->to('deepak.k@basilrl.com', 'Deepak Kumar');
                  $CI->email->cc('bharat.s@basilrl.com');
                  $CI->email->cc('om.p@basilrl.com');
                  $CI->email->cc('manish.k@basilrl.com');
                  }
                  if($division['division_name']=='Electrical'){
                  $CI->email->to('saurabh.s@basilrl.com', 'Saurabh Srivastav');
                  $CI->email->cc('om.p@basilrl.com');
                  $CI->email->cc('manish.k@basilrl.com');
                  } */
                $CI->email->to('shankar.k@basilrl.com');

                // $CI->email->to('developer.cps@basilrl.com');

                $CI->email->subject($division['division_name'] . " - Backlog Sample List on " . date('d-m-Y'));
                $CI->email->message($message);
                $CI->email->attach($file);
                $CI->email->send();
                $CI->email->clear(TRUE);
            }
        }
    }

    public function equipment_maintenance() {
        $this->cron->equipment_maintenance();
    }

    public function sample_status_cron() {

        $this->cron->sample_status_cron();
    }

    public function customerInactive() {
        $this->cron->customerInactive();
    }

    public function updateStyleNumber(){
        $this->db->select('trf_id, style_number, product_custom_fields');
        $this->db->where('style_number', NULL);
        $this->db->or_where('style_number', '');
        $query = $this->db->get('trf_registration');
        if($query->num_rows() > 0){
            $result = $query->result_array();
            foreach($result as $r){
                foreach(json_decode($r['product_custom_fields']) as $c){
                    if(!empty($c[0]) && ($c[0] == 'Style No' || $c[0] == 'Style No.' || $c[0] == 'Style Number')){
                        echo '<pre>';echo $r['trf_id'].' '.$c[0].' '.$c[1];echo '</pre>';
                        $this->db->update('trf_registration',['style_number' => $c[1]],['trf_id' => $r['trf_id']]);
                        echo '<pre>';echo $this->db->last_query();echo '</pre>';
                    }
                }
            }
        }
    }

}

?>