<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends MY_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('testrequestform','job');
	}
	
	public function index()
	{
		// echo base_url(); die;
		$this->load_view('dashboard');
	}

	public function add_open_trf(){
		$data['customer'] = $this->job->get_customer();
		$this->load_view('add_open_trf',$data);
	}

	public function open_trf_list(){
		$this->load_view('open_trf_list');
	}
}
