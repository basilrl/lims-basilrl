<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sample_tracking extends MY_Controller {

	public function __construct(){
		parent::__construct();
		$this->check_session();
        $this->load->model('Sample_tracking_model', 'stm');
        set_time_limit(0);
        ini_set('memory_limit', '-1');
	}

    public function index(){
        $this->load_view('sample_tracking/sample_tracking',NULL);
    }


    public function get_auto_gc_list(){
        // pre_r($this->input->post());die;
        $search = $this->input->post('key');
        $where = (array)(json_decode(stripslashes($this->input->post('where'))));
        $like = $this->input->post('like');
		$select = $this->input->post('select');
        $table = $this->input->post('table');
        $result = $this->stm->get_auto_track_gc($select,$table,$search,$like,$where);

        echo json_encode($result);
    }


    public function get_ajax_data(){

        $where = (array)(json_decode(stripslashes($this->input->post('param'))));
        $controller_fn = $this->input->post('controller_fn');
        $data = $this->$controller_fn($where);
        echo json_encode($data);        
    }

    public function get_sample_details($where){
        $sample_status = $this->stm->sample_details($where);
        $sample_track = $this->stm->sample_tracking_status($where);
        $data['sample_status'] = $sample_status;
        $data['sample_tracking_status'] = $sample_track;
        return $this->return_fn($data);
    }

    public function return_fn($data){
        if($data && count($data)>0){
            return $data;
        }
        else{
            return false;
        }
    }
}