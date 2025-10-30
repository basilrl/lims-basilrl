<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Test_parameters extends MY_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->check_session();
		$this->load->model('test_parameters_model');
		$checkUser = $this->session->userdata('user_data');
		$this->user = $checkUser->uidnr_admin;
	}

	public function index($div_id = NULL, $lab_id = NULL, $test_name = NULL,$test_parameter = NULL,$created_by = NULL, $sortby = NULL, $order = NULL, $page_no = NULL)
	{
		//$this->output->enable_profiler(true);
		$where = NULL;
		$base_url = 'test_parameters/index';

		if ($div_id!= '' && $div_id!= 'NULL') {
			$base_url .= '/' . $div_id;
			$data['div_id']=$where['ts.test_division_id'] = $div_id;
			$data['div_name'] = $this->test_master_model->get_row('division_name', 'mst_divisions', 'division_id' . '=' . $where['ts.test_division_id']);
		} else {
			$base_url .= '/NULL';
			$data['div_id'] = NULL;
			$data['div_name'] = NULL;
		}
		if ($lab_id!= '' && $lab_id!= 'NULL') {
			$base_url .= '/' . $lab_id;
			$data['lab_id'] =$where['ts.test_lab_type_id'] = $lab_id;
			$data['lab_name'] = $this->test_master_model->get_row('lab_type_name', 'mst_lab_type', 'lab_type_id' . '=' . $where['ts.test_lab_type_id']);
		} else {
			$base_url .= '/NULL';
			$data['lab_id'] = NULL;
			$data['lab_name'] = NULL;
		}

		if ($test_name!= NULL && $test_name!= 'NULL') {
			$data['test_name'] =  base64_decode($test_name);
			$base_url .= '/' . $test_name;
			$test_name = base64_decode($test_name);
		} else {
			$base_url .= '/NULL';
			$data['test_name'] = NULL;
			$test_name = NULL;
		}
		if ($test_parameter!= NULL && $test_parameter!= 'NULL') {
			$data['test_parameter'] =  base64_decode($test_parameter);
			$base_url .= '/' . $test_parameter;
			$test_parameter = base64_decode($test_parameter);
		} else {
			$base_url .= '/NULL';
			$data['test_parameter'] = NULL;
			$test_parameter = NULL;
		}
		if ($created_by!= NULL && $created_by!= 'NULL') {
			$data['created_by'] =  base64_decode($created_by);
			$base_url .= '/' . $created_by;
			$created_by = base64_decode($created_by);
		} else {
			$base_url .= '/NULL';
			$data['created_by'] = NULL;
			$created_by = NULL;
		}
		if ($sortby!= NULL && $sortby!= 'NULL') {
			$base_url .= '/' . $sortby;
		} else {
			$base_url .= '/NULL';
			$sortby = NULL;
		}
		if ($order!= NULL && $order!= 'NULL') {
			$base_url .= '/' . $order;
		} else {
			$base_url .= '/NULL';
			$order = NULL;
		}

		$total_row = $this->test_parameters_model->get_test_parameters_list(NULL, NULL, $test_name,$test_parameter,$created_by, NULL, NULL, $where, '1');
		$config = $this->pagination($base_url, $total_row, 10, 10);
		$data["links"] = $config["links"];
		$data['test_list'] = $this->test_parameters_model->get_test_parameters_list($config["per_page"], $config['page'], $test_name,$test_parameter,$created_by, $sortby, $order, $where);

		$start = (int)$page_no + 1;
		$end = (($data['test_list']) ? count($data['test_list']) : 0) + (($page_no) ? $page_no : 0);
		$data['result_count'] = "Showing " . ($start) . " - " . $end . " of " . $total_row . " Results";

		if ($order == NULL || $order == 'NULL') {
			$data['order'] = ($order) ? "DESC" : "ASC";
		} else {
			$data['order'] = ($order == "ASC") ? "DESC" : "ASC";
		}

		$this->load_view('test_parameters/index', $data);
	}

	public function removetestparameters(){

		$sql="select tp.test_parameters_id from test_parameters tp join tests ts on ts.test_id=tp.test_parameters_test_id where ts.test_name!=tp.test_parameters_name";
		$res=$this->db->query($sql);
		$data=$res->result();
		foreach($data as $row){
			echo $sql1="delete from test_parameters where test_parameters_id=".$row->test_parameters_id;
			echo "<br>";
			//exit;
			$this->db->query($sql1);
		}
	}


	
}
