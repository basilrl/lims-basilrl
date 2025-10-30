<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Test_master extends MY_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->check_session();
		$this->load->model('test_management_model/Test_master_model', 'test_master_model');
		$checkUser = $this->session->userdata('user_data');
		$this->user = $checkUser->uidnr_admin;
	}

	public function index($div_id = NULL, $lab_id = NULL, $search = NULL, $sortby = NULL, $order = NULL, $page_no = NULL)
	{
		$where = NULL;
		$base_url = 'test_master';

		if ($div_id != '' && $div_id != 'NULL') {
			$base_url .= '/' . $div_id;
			$data['div_id'] = $where['ts.test_division_id'] = $div_id;
			$data['div_name'] = $this->test_master_model->get_row('division_name', 'mst_divisions', 'division_id' . '=' . $where['ts.test_division_id']);
		} else {
			$base_url .= '/NULL';
			$data['div_id'] = NULL;
			$data['div_name'] = NULL;
		}
		if ($lab_id != '' && $lab_id != 'NULL') {
			$base_url .= '/' . $lab_id;
			$data['lab_id'] = $where['ts.test_lab_type_id'] = $lab_id;
			$data['lab_name'] = $this->test_master_model->get_row('lab_type_name', 'mst_lab_type', 'lab_type_id' . '=' . $where['ts.test_lab_type_id']);
		} else {
			$base_url .= '/NULL';
			$data['lab_id'] = NULL;
			$data['lab_name'] = NULL;
		}

		if ($search != NULL && $search != 'NULL') {
			$data['search'] =  base64_decode($search);
			$base_url .= '/' . $search;
			$search = base64_decode($search);
		} else {
			$base_url .= '/NULL';
			$data['search'] = NULL;
			$search = NULL;
		}

		if ($sortby != NULL && $sortby != 'NULL') {
			$base_url .= '/' . $sortby;
		} else {
			$base_url .= '/NULL';
			$sortby = NULL;
		}
		if ($order != NULL && $order != 'NULL') {
			$base_url .= '/' . $order;
		} else {
			$base_url .= '/NULL';
			$order = NULL;
		}

		$total_row = $this->test_master_model->get_test_list(NULL, NULL, $search, NULL, NULL, $where, '1');
		$config = $this->pagination($base_url, $total_row, 10, 7);
		$data["links"] = $config["links"];
		$data['test_list'] = $this->test_master_model->get_test_list($config["per_page"], $config['page'], $search, $sortby, $order, $where);

		$start = (int)$page_no + 1;
		$end = (($data['test_list']) ? count($data['test_list']) : 0) + (($page_no) ? $page_no : 0);
		$data['result_count'] = "Showing " . ($start) . " - " . $end . " of " . $total_row . " Results";

		if ($order == NULL || $order == 'NULL') {
			$data['order'] = ($order) ? "DESC" : "ASC";
		} else {
			$data['order'] = ($order == "ASC") ? "DESC" : "ASC";
		}

		$this->load_view('test_management/test_master', $data);
	}

	// add test page
	public function add_test()
	{
		$this->load_view('test_management/add_test', NULL);
	}


	// insert test
	public function insert_test()
	{
		$data = $this->input->post();
		$product_ids = $this->input->post('test_sample_type_id');

		// get products_array by product ids
		$products = $this->test_master_model->get_products_of_test($product_ids);
		if ($products) {
			foreach ($products as $key => $product) {
				$product_name[$product->id] = $product->name;
			}
		} else {
			$product_name = [];
		}

		// get service
		$arr = $this->input->post('test_service_type');
		if (empty($arr)) {
			$arr = [];
		}

		$sub_contract = array();
		if (array_key_exists('sub_contract', $data)) {

			$sub_contract = $data['sub_contract'];
			unset($data['sub_contract']);
			$sub_contract['created_by'] = $this->user;
			$sub_contract['created_on'] = date("Y-m-d h:i:s", time());
		}

		$this->form_validation->set_rules('test_division_id', 'Division', 'required');
		$this->form_validation->set_rules('test_lab_type_id', 'Lab type', 'required');
		$this->form_validation->set_rules('test_name', 'Test Name', 'required|trim|callback_uniq');
		$this->form_validation->set_rules('test_method_id', 'Test method', 'required|trim|callback_uniq');
		$this->form_validation->set_rules('test_sample_type_id[]', 'Product', 'required');
		$this->form_validation->set_rules('minimum_quantity', 'Min. sample Qty unit', 'required');
		$this->form_validation->set_rules('minimum_quantity_units', 'Set Unit', 'required');
		$this->form_validation->set_rules('units', 'Repport Unit', 'required');
		$this->form_validation->set_rules('test_service_type[]', 'Service Type', 'required');
		$this->form_validation->set_rules('is_available_customerportal', 'Online Show', 'required');
		$this->form_validation->set_rules('under_scope', 'Under Scope', 'required');


		$valid = $this->form_validation->run();
		$this->form_validation->set_error_delimiters('<div class="error" style="color:red">', '</div>');

		if ($valid == true) {
			$products = $data['test_sample_type_id'];
			$service = $data['test_service_type'];
			if (count($service) > 0) {
				$data['test_service_type'] = implode(",", $service);
			}
			$filterdata = $this->filter_data($data);
			// $sub_perameter = $this->crate_sub_perameter($data); // Commented by CHANDAN --10-05-2022
			$sub_perameter = array();
			$price_listlog = $this->pricelist_log();
			$result = $this->test_master_model->insert_test($products, $filterdata, $sub_perameter, $price_listlog, $sub_contract);
			// die;
			$this->session->set_flashdata('success', 'Test Added Successfully');
			redirect('test_master');
		} else {
			$this->session->set_flashdata('error', 'Error in Addind Test');
			$this->load_view('test_management/add_test', ['product' => $product_name, 'ser' => $arr]);
		}
	}

	// insert test end

	public function filter_data($data)
	{
		if ($data) {
			unset($data['div_name']);
			unset($data['lab_name']);
			unset($data['unit_name']);
			unset($data['report_unit_name']);
			unset($data['test_sample_type_id']);
			$data['created_by'] = $this->user;
			$data['created_on'] = date("Y-m-d h:i:s", time());
			return $data;
		} else {
			return false;
		}
	}

	public function crate_sub_perameter($data)
	{

		$sub['test_parameters_disp_name'] = $data['test_name'];
		$sub['test_parameters_name'] = $data['test_name'];
		$sub['type'] = 'Calculated';
		$sub['show_in_report'] = 'Yes';
		$sub['mandatory'] = 'Yes';
		$sub['formula'] = '';
		$sub['created_by'] = $this->user;
		$sub['created_on'] = date("Y-m-d h:i:s", time());

		return $sub;
	}



	public function pricelist_log()
	{

		$plog['updated_on'] = date("Y-m-d h:i:s", time());
		$plog['updated_by'] = $this->user;
		$plog['new_price'] = 0;
		$plog['pricelist_type'] = 'Test';
		$plog['old_price'] = 0;

		return $plog;
	}

	// edit test
	public function edit_test($test_id, $product_ids = NULL)
	{
		$products = null;
		$check = $this->uri->segment('1');
		if ($check == "edit_test" && $product_ids == NULL) {

			// get product_id by test id
			$prod['test_sample_type_test_id'] = $test_id;
			$product_ids = $this->test_master_model->get_result('test_sample_type_sample_type_id', 'test_sample_type', $prod);
			if ($product_ids) {
				foreach ($product_ids as $key => $value) {
					$product_ids[$key] = $value->test_sample_type_sample_type_id;
				}
				$products  = $this->test_master_model->get_products_of_test($product_ids);
			}
		} else {
			if ($product_ids != null) {
				$products  = $this->test_master_model->get_products_of_test($product_ids);
			}
		}


		// get products_array by product ids

		if ($products) {
			foreach ($products as $key => $product) {
				$product_name[$product->id] = $product->name;
			}
		} else {
			$product_name = [];
		}

		// get data by test id
		$where['ts.test_id'] = $test_id;
		$data = $this->test_master_model->get_test_details($test_id);

		// get sub_contract data by test_id
		$sub_contract = $this->test_master_model->get_row('sub_contract_lab_name,lab_address,test_price', 'test_sub_contract_details', 'test_id=' . $test_id);
		if ($sub_contract && count($sub_contract) > 0) {
			$sub_contract = $sub_contract;
		} else {
			$sub_contract = array();
		}
		$this->load_view('test_management/edit_test', ['item' => $data, 'product' => $product_name, 'sub_contract' => $sub_contract]);
	}

	public function update_test($test_id)
	{
		$this->form_validation->set_rules('test_division_id', 'Division', 'required');
		$this->form_validation->set_rules('test_lab_type_id', 'Lab type', 'required');
		$this->form_validation->set_rules('test_name', 'Test Name', 'required|trim|callback_uniq');
		$this->form_validation->set_rules('test_method_id', 'Test method', 'required|trim|callback_uniq');
		$this->form_validation->set_rules('test_sample_type_id[]', 'Product', 'required');
		$this->form_validation->set_rules('minimum_quantity', 'Min. sample Qty unit', 'required');
		$this->form_validation->set_rules('minimum_quantity_units', 'Set Unit', 'required');
		$this->form_validation->set_rules('units', 'Repport Unit', 'required');
		$this->form_validation->set_rules('test_service_type[]', 'Service Type', 'required');
		$this->form_validation->set_rules('is_available_customerportal', 'Online Show', 'required');
		$this->form_validation->set_rules('under_scope', 'Under Scope', 'required');

		$valid = $this->form_validation->run();

		$this->form_validation->set_error_delimiters('<div class="error" style="color:red">', '</div>');
		$data = $this->input->post();

		if (empty($this->input->post('test_sample_type_id'))) {
			$data['test_sample_type_id'] = NULL;
		}
		if (empty($this->input->post('test_service_type'))) {
			$data['test_service_type'] = NULL;
		}
		// check duplicate
		// first get into product after  unset($data['test_sample_type_id']);
		$products = $data['test_sample_type_id'];
		$service = $data['test_service_type'];

		$sub_contract = array();
		if (array_key_exists('sub_contract', $data)) {

			$sub_contract = $data['sub_contract'];
			unset($data['sub_contract']);
			$sub_contract['created_by'] = $this->user;
			$sub_contract['created_on'] = date("Y-m-d h:i:s", time());
		}
		if ($valid == true) {

			$data['created_on'] = $data['updated_on'] = date("Y-m-d h:i:s", time());

			$data['created_by'] = $data['updated_by'] = $this->user;
			if (count($service) > 0) {
				$data['test_service_type'] = implode(",", $service);
			}
			// unset
			unset($data['div_name']);
			unset($data['lab_name']);
			unset($data['unit_name']);
			unset($data['report_unit_name']);
			unset($data['test_sample_type_id']);

			$this->test_master_model->update_test($data, $products, $test_id, $sub_contract);
			$this->user_log_update($test_id, 'TEST UPDATED WITH TEST NAME ' . $data['test_name'], 'update_test');
			$this->session->set_flashdata('success', 'Test is Updated successfully');
			redirect('test_master');
		} else {
			$this->session->set_flashdata('error', 'Validation Error!. Please check required fields.');
			$this->edit_test($test_id, $products);
		}
	}





	public function import_excel()
	{
		$file = null;
		$data = null;
		$sample_ids = NULL;

		$file = $_FILES['excel_import_test'];

		if (!empty($file['name'])) {
			if ($file['type'] == "application/vnd.ms-excel" || $file['type'] == "text/csv") {
				$file  = fopen($file['tmp_name'], "r");
				$i = 0;
				while (($row = fgetcsv($file, 10000, ",")) !== FALSE) {
					if ($i > 0) {
						$dup['test_name'] = $row[3];
						$dup['test_method'] = $row[4];
						$duplicate = $this->test_master_model->check_dupli($dup, NULL);

						if ($duplicate == false) {
							continue;
						} else {
							$div_id = $this->test_master_model->getIDByName('division_id', $row[0], trim($row[0]), 'division_name', 'mst_divisions');
							$div_id = $div_id->division_id;

							$lab_id = $this->test_master_model->getIDByName('lab_type_id', $row[1], trim($row[1]), 'lab_type_name', 'mst_lab_type');
							$lab_id = $lab_id->lab_type_id;

							$product_name = explode(',', $row[2]);
							$sample_ids = array();

							foreach ($product_name as $key => $value) {
								$sample_id = $this->test_master_model->getIDByName('sample_type_id', $value, trim($value), 'sample_type_name', 'mst_sample_types');
								$sample_id = $sample_id->sample_type_id;
								array_push($sample_ids, $sample_id);
							}


							$min_unit = $this->test_master_model->getIDByName('unit_id', $row[6], trim($row[6]), 'unit', 'units');
							$min_unit = $min_unit->unit_id;

							$report_unit = $this->test_master_model->getIDByName('unit_id', $row[8], trim($row[8]), 'unit', 'units');
							$report_unit = $report_unit->unit_id;

							if ($div_id != NULL && $div_id > 0) {
								$div_id = $div_id;
							} else {
								$div_id = '0';
							}
							if ($lab_id != NULL && $lab_id > 0) {
								$lab_id = $lab_id;
							} else {
								$lab_id = '0';
							}
							if ($sample_ids && count($sample_ids) > 0) {
								$sample_ids = $sample_ids;
							} else {
								$sample_id = '0';
							}
							if ($min_unit != NULL && $min_unit > 0) {
								$min_unit = $min_unit;
							} else {
								$min_unit = '0';
							}
							if ($report_unit != NULL && $report_unit > 0) {
								$report_unit = $report_unit;
							} else {
								$report_unit = '0';
							}
							// $data[]="('".$row[0]."','".$row[1]."','".$cat_id."','".$row[3]."','0','0')";
							$data[] = array(
								"test_division_id" => $div_id,
								"test_lab_type_id" => $lab_id,
								"test_sample_type_id" => 0,
								"test_name" => $row[3],
								"test_method" => $row[4],
								"minimum_quantity" => $row[5],
								"minimum_quantity_units" => $min_unit,
								"test_price" => $row[7],
								"units" => $report_unit,
								"created_on" => date("Y-m-d h:i:s", time()),
								"created_by" => $this->user,
								"test_turn_around_time" => $row[9],


							);
						}
					}
					$i++;
				}

				if ($data && count($data) > 0) {
					if ($sample_ids && count($sample_ids) > 0) {
						$sql = $this->test_master_model->insert_import_test($data, $sample_ids);
					} else {
						$sql = $this->test_master_model->insert_import_test($data, NULL);
					}
				} else {
					$sql = false;
				}

				if ($sql) {
					$msg = array(
						'status' => 1,
						'msg' => 'File successfully uploaded'
					);
				} else {
					$msg = array(
						'status' => 0,
						'msg' => 'Error while importing'
					);
				}
			} else {
				$msg = array(
					'status' => 0,
					'msg' => 'Please choose a valid CSV'
				);
			}
		} else {
			$msg = array(
				'status' => 0,
				'msg' => 'Please choose a file'
			);
		}

		echo json_encode($msg);
	}


	public function uniq()
	{
		$id = $this->uri->segment('2');
		$data['test_name'] = $this->input->post('test_name');
		$data['test_method_id'] = $this->input->post('test_method_id');
		$check = $this->test_master_model->check_dupli($data, $id);

		if ($check) {
			return true;
		} else {
			$this->form_validation->set_message('uniq', '{field} is already in use');
			return false;
		}
	}


	public function get_price_list_test()
	{
		$test_id = $this->input->post('test_id');
		$result = $this->test_master_model->get_price_lists_of_tests($test_id);
		echo json_encode($result);
	}


	public function save_test_price()
	{
		$data = array();
		$data = $this->input->post();

		if ($data['row'] && count($data['row'])) {

			for ($i = 0; $i < count($data['row']); $i++) {

				$data['row'][$i]['type'] = 'Test';
				$data['row'][$i]['pricelist_test_id'] = $data['tests_test_id'];
				unset($data['row'][$i]['sl_number']);
				unset($data['row'][$i]['country_code']);
				$code = $data['row'][$i]['currency_code'];
				$where1['currency_code'] = $code;
				unset($data['row'][$i]['currency_code']);
				$id = $this->test_master_model->get_row('currency_id', 'mst_currency', $where1);

				$data['row'][$i]['currency_id'] = $id->currency_id;
				$where2['pricelist_test_id'] = $data['tests_test_id'];
				$where2['currency_id'] = $id->currency_id;
				$get = $this->test_master_model->get_row('pricelist_id', 'pricelist', $where2);

				if (!empty($get) && isset($get->pricelist_id)) {
					$where3['pricelist_id'] = $get->pricelist_id;
					$result = $this->test_master_model->update_data('pricelist', $data['row'][$i], $where3);
				} else {
					$result = $this->test_master_model->insert_data('pricelist', $data['row'][$i]);
				}
			}
		}

		if ($result) {
			$log = $this->user_log_update($data['tests_test_id'], 'Price list updated', 'save_test_price');
			if ($log) {
				$msg = array(
					'status' => 1,
					'msg' => 'Price Updated Successfully'
				);
			} else {
				$msg = array(
					'status' => 0,
					'msg' => 'error in generating log'
				);
			}
		} else {
			$msg = array(
				'status' => 0,
				'msg' => 'Error In Updating Price'
			);
		}

		echo json_encode($msg);
	}

	public function get_log_data()
	{
		$id = $this->input->post('id');
		$data = $this->test_master_model->get_log_data($id);
		echo json_encode($data);
	}

	public function user_log_update($id, $text, $action)
	{
		$data = array();
		$data['source_module'] = 'Test_master';
		$data['record_id'] = $id;
		$data['created_on'] = date("Y-m-d h:i:s");
		$data['created_by'] = $this->user;
		$data['action_taken'] = $action;
		$data['text'] = $text;

		$result = $this->test_master_model->insert_data('user_log_history', $data);
		if ($result) {
			return true;
		} else {
			return false;
		}
	}

	// Added by CHANDAN --09-04-2022
	public function fetch_units()
	{
		echo json_encode($this->test_master_model->get_result('unit_id, unit', 'units', NULL));
	}

	public function add_parameter()
	{
		$this->form_validation->set_rules('test_parameters_name', 'Parameter name', 'trim|required');
		$this->form_validation->set_rules('priority_order', 'Priority order', 'trim|required');
		$this->form_validation->set_rules('result_component_type', 'Parameter type', 'trim|required');
		$this->form_validation->set_error_delimiters('', '');

		if ($this->form_validation->run() == FALSE) {
			foreach ($this->input->post() as $key => $value) {
				$response['error'][$key] = form_error($key);
			}
		} else {
			$data = $this->input->post();
			$response = array(
				'message'   => 'Something went wrong.',
				'code'      => 0
			);

			$data['test_parameters_disp_name'] = $this->input->post('test_parameters_name');

			if (empty($data['test_parameters_id'])) {
				$parameter_info = $this->test_master_model->get_row('test_parameters_id', 'test_parameters', ['test_parameters_test_id' => $data['test_parameters_test_id'], 'LOWER(test_parameters_name)' => strtolower($data['test_parameters_name']), 'is_delete' => 0]);

				if (empty($parameter_info)) {
					$data['is_delete'] = 0;
					$data['created_on'] = date("Y-m-d h:i:s");
					$data['created_by'] = $this->user;
					$result = $this->test_master_model->insert_data('test_parameters', $data);
					if ($result) {
						$log_details = array(
							'source_module'     => 'Test_master',
							'record_id'         => $data['test_parameters_test_id'],
							'created_on' 		=> date("Y-m-d h:i:s"),
							'created_by' 		=> $this->user,
							'action_taken' 		=> 'add_parameter',
							'text' 				=> 'Record has been inserted.'
						);
						$this->test_master_model->insert_data('user_log_history', $log_details);

						$response = array(
							'message'   => 'Record has been inserted.',
							'code'      => 1
						);
					}
				} else {
					$response = array(
						'message'   => 'Parameter should be unique for same Test-ID.',
						'code'      => 0
					);
				}
			} else {
				$parameter_info = $this->db->select('test_parameters_id')->from('test_parameters')->where_not_in('test_parameters_id', $data['test_parameters_id'])->where(['test_parameters_test_id' => $data['test_parameters_test_id'], 'LOWER(test_parameters_name)' => strtolower($data['test_parameters_name']), 'is_delete' => 0])->count_all_results();

				if ($parameter_info < 1) {
					$data['updated_on'] = date("Y-m-d h:i:s");
					$data['updated_by'] = $this->user;
					$result = $this->test_master_model->update_data('test_parameters', $data, ['test_parameters_id' => $data['test_parameters_id']]);
					if ($result) {
						$log_details = array(
							'source_module'     => 'Test_master',
							'record_id'         => $data['test_parameters_test_id'],
							'created_on' 		=> date("Y-m-d h:i:s"),
							'created_by' 		=> $this->user,
							'action_taken' 		=> 'add_parameter',
							'text' 				=> 'Record has been updated.'
						);
						$this->test_master_model->insert_data('user_log_history', $log_details);

						$response = array(
							'message'   => 'Record has been updated.',
							'code'      => 1
						);
					}
				} else {
					$response = array(
						'message'   => 'Parameter should be unique for same Test-ID.',
						'code'      => 0
					);
				}
			}
		}
		echo json_encode($response);
	}

	public function edit_parameter()
	{
		echo json_encode($this->test_master_model->get_row('*', 'test_parameters', ['test_parameters_id' => $this->input->post('para_id')]));
	}

	public function view_parameter()
	{
		if (!empty($this->input->post('test_id'))) {
			$data = $this->test_master_model->view_parameter($this->input->post('test_id'));
			$html = '<table class="table small table-bordered" id="view_parameter_table"><thead><tr class="bg-info text-white"><th>SL</th><th>CLOUSE</th><th>PARAMETER</th><th>CATEGORY</th><th>PRIORITY ORDER</th><th>TYPE</th><th>MIN-VALUE</th><th>MAX-VALUE</th><th>CREATED BY</th><th>CREATED ON</th><th>ACTION</th></tr></thead><tbody>';
			$sl = 1;
			if (!empty($data)) {
				foreach ($data as $key => $val) {
					$html .= '<tr id="del_rows_' . $val->test_parameters_id . '"><td>' . $sl . '</td>';
					$html .= '<td>' . $val->clouse . '</td>';
					$html .= '<td>' . $val->test_parameters_name . '</td>';
					$html .= '<td>' . $val->category . '</td>';
					$html .= '<td>' . $val->priority_order . '</td>';
					$html .= '<td>' . $val->result_component_type . '</td>';
					$html .= '<td>' . $val->min_values . '</td>';
					$html .= '<td>' . $val->max_values . '</td>';
					$html .= '<td>' . $val->created_by . '</td>';
					$html .= '<td>' . $val->created_on . '</td>';
					$html .= '<td>';
					$html .= '<button type="button" class="btn btn-sm btn-default edit_parameter" title="EDIT PARAMETER" data-id="' . $val->test_parameters_id . '"><img src="' . base_url('assets/images/pen.png') . '"></button>';

					if (exist_val('Test_master/delete_parameter', $this->session->userdata('permission'))) {
						$html .= '<button type="button" class="btn btn-sm btn-default ml-1 delete_parameter" title="DELETE PARAMETER" data-id="' . $val->test_parameters_id . '"><img src="' . base_url('assets/images/cross.png') . '"></button>';
					}
					$html .= '</td></tr>';
					$sl++;
				}
				$html .= '</tbody></table>';
				$html .= '<script>$("#view_parameter_table").dataTable();</script>';
			} else {
				$html .= '<tr><td colspan="11" align="center">No Records.</td></tr>';
				$html .= '</tbody></table>';
			}
			echo $html;
		}
	}

	public function delete_parameter()
	{
		$result = $this->test_master_model->update_data('test_parameters', ['is_delete' => 1, 'updated_by' => $this->user], ['test_parameters_id' => $this->input->post('para_id')]);
		if ($result) {
			$log_details = array(
				'source_module'     => 'Test_master',
				'record_id'         => $this->input->post('para_id'),
				'created_on' 		=> date("Y-m-d h:i:s"),
				'created_by' 		=> $this->user,
				'action_taken' 		=> 'delete_parameter',
				'text' 				=> 'Record has been deleted.'
			);
			$this->test_master_model->insert_data('user_log_history', $log_details);

			$response = array(
				'message'   => 'Record has been deleted.',
				'code'      => 1
			);
		} else {
			$response = array(
				'message'   => 'Something went wrong.',
				'code'      => 0
			);
		}
		echo json_encode($response);
	}

	public function get_yes_no($value)
	{
		return (strtolower($value) == 'no') ? 'No' : 'Yes';
	}

	public function get_result_component_type($value)
	{
		if (empty($value)) {
			return 'Parameter';
		} else {
			if (strtolower($value) == 'sub parameter') {
				return 'Sub Parameter';
			} else if (strtolower($value) == 'step') {
				return 'Step';
			} else if (strtolower($value) == 'istomers') {
				return 'Istomers';
			} else if (strtolower($value) == 'solvent') {
				return 'Solvent';
			} else {
				return 'Parameter';
			}
		}
	}

	public function import_parameter()
	{
		ini_set('max_execution_time', 0);
		ini_set('memory_limit', '-1');

		$insertedRecord = $duplicateRecords = 0;
		$test_parameters_test_id = $this->input->post('import_test_id');

		if (!empty($test_parameters_test_id) && isset($_FILES["import_parameter_file"]["name"])) {

			$parameter_data = array();

			$file_data = fopen($_FILES["import_parameter_file"]["tmp_name"], 'r');
			fgetcsv($file_data);
			while (($row = fgetcsv($file_data)) !== false) {

				$clouse              	= trim($row[0]);
				$test_parameters_name 	= trim($row[1]);
				$category           	= trim($row[2]);
				$parameter_limit        = trim($row[3]);
				$requirement            = trim($row[4]);
				$priority_order         = trim($row[5]);
				$result_component_type  = $this->get_result_component_type(trim($row[6]));
				$show_in_report         = $this->get_yes_no(trim($row[7]));
				$mandatory              = $this->get_yes_no(trim($row[8]));
				$min_values            	= trim($row[9]);
				$max_values 			= trim($row[10]);

				if (!empty($test_parameters_name) && !empty($priority_order)) {

					$parameter_info = $this->test_master_model->get_row('test_parameters_id', 'test_parameters', ['test_parameters_test_id' => $test_parameters_test_id, 'LOWER(test_parameters_name)' => strtolower($test_parameters_name), 'is_delete' => 0]);

					if (empty($parameter_info)) {

						$parameter_data = array(
							'is_delete' 				=> 0,
							'created_on' 				=> date("Y-m-d h:i:s"),
							'created_by' 				=> $this->user,
							'test_parameters_test_id'	=> $test_parameters_test_id,
							'clouse' 					=> $clouse,
							'test_parameters_name' 		=> $test_parameters_name,
							'test_parameters_disp_name' => $test_parameters_name,
							'category' 					=> $category,
							'parameter_limit' 			=> $parameter_limit,
							'requirement' 				=> $requirement,
							'priority_order' 			=> $priority_order,
							'result_component_type'		=> $result_component_type,
							'show_in_report' 			=> $show_in_report,
							'mandatory' 				=> $mandatory,
							'min_values' 				=> $min_values,
							'max_values' 				=> $max_values
						);

						$result = $this->test_master_model->insert_data('test_parameters', $parameter_data);
						if ($result) {
							$log_details = array(
								'source_module'     => 'Test_master',
								'record_id'         => $test_parameters_test_id,
								'created_on' 		=> date("Y-m-d h:i:s"),
								'created_by' 		=> $this->user,
								'action_taken' 		=> 'import_parameter',
								'text' 				=> 'Record has been imported.'
							);
							$this->test_master_model->insert_data('user_log_history', $log_details);

							$insertedRecord++;
						}
					} else {
						$duplicateRecords++;
					}
				}
			}
			if (($insertedRecord > 0) || ($duplicateRecords > 0)) {
				echo json_encode(["message" => $insertedRecord . " Data imported! and " . $duplicateRecords . " Data Duplicate!", "status" => 1]);
			} else {
				echo json_encode(["message" => "Something Went Wrong!.", "status" => 0]);
			}
		}
	}

	public function export_all_tests($div_id = NULL, $lab_id = NULL, $search = NULL)
	{
		ini_set('max_execution_time', 0);
		ini_set('memory_limit', '-1');

		$where = array();
		if ($div_id != '' && $div_id != 'NULL') {
			$where['ts.test_division_id'] = $div_id;
		}
		if ($lab_id != '' && $lab_id != 'NULL') {
			$where['ts.test_lab_type_id'] = $lab_id;
		}
		if ($search != NULL && $search != 'NULL') {
			$srch['search'] =  base64_decode($search);
		} else {
			$srch['search'] = NULL;
		}

		$data = $this->test_master_model->export_all_tests($where, $srch);

		if (!empty($data)) {
			$this->load->library('excel');
			$tmpfname = "example.xls";
			$excelReader = PHPExcel_IOFactory::createReaderForFile($tmpfname);
			$objPHPExcel = $excelReader->load($tmpfname);
			$objPHPExcel->getProperties()->setCreator("GEO-CHEM")
				->setLastModifiedBy("GEO-CHEM")
				->setTitle("Office 2007 XLS Test Document")
				->setSubject("Office 2007 XLS Test Document")
				->setDescription("Description for Test Document")
				->setKeywords("phpexcel office codeigniter php")
				->setCategory("Test details file");

			$objPHPExcel->setActiveSheetIndex(0);
			$objPHPExcel->getActiveSheet()->setCellValue('A1', "SL NO.");
			$objPHPExcel->getActiveSheet()->setCellValue('B1', "TEST NAME");
			$objPHPExcel->getActiveSheet()->setCellValue('C1', "DIVISION");
			$objPHPExcel->getActiveSheet()->setCellValue('D1', "LAB TYPE");
			$objPHPExcel->getActiveSheet()->setCellValue('E1', "TEST METHOD");
			$objPHPExcel->getActiveSheet()->setCellValue('F1', "METHOD TYPE");
			$objPHPExcel->getActiveSheet()->setCellValue('G1', "CREATED ON");
			$objPHPExcel->getActiveSheet()->setCellValue('H1', "CREATED BY");
			$objPHPExcel->getActiveSheet()->setCellValue('I1', "STATUS");
			$objPHPExcel->getActiveSheet()->setCellValue('J1', "ONLINE SHOW");

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

			$i = 2;
			foreach ($data as $key => $value) {

				$method_type = ($value->method_type == 'IHTM') ? "IN HOUSE" : "SUB CONTRACT";

				$objPHPExcel->getActiveSheet()->setCellValue('A' . $i, ($i - 1));
				$objPHPExcel->getActiveSheet()->setCellValue('B' . $i, $value->test_name);
				$objPHPExcel->getActiveSheet()->setCellValue('C' . $i, $value->div_name);
				$objPHPExcel->getActiveSheet()->setCellValue('D' . $i, $value->lab_name);
				$objPHPExcel->getActiveSheet()->setCellValue('E' . $i, $value->test_method);
				$objPHPExcel->getActiveSheet()->setCellValue('F' . $i, $method_type);
				$objPHPExcel->getActiveSheet()->setCellValue('G' . $i, $value->created_on);
				$objPHPExcel->getActiveSheet()->setCellValue('H' . $i, $value->created_by);
				$objPHPExcel->getActiveSheet()->setCellValue('I' . $i, $value->test_status);
				$objPHPExcel->getActiveSheet()->setCellValue('J' . $i, $value->online_show);
				$i++;
			}
			$filename = 'Test_details-' . date('Y-m-d-s') . ".xls";
			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
			ob_end_clean();
			header('Content-type: application/vnd.ms-excel');
			header('Content-Disposition: attachment; filename=' . $filename);
			$objWriter->save('php://output');
			return redirect('test_master');
		} else {
			$this->session->set_flashdata('error', 'Something went wrong!.');
			return redirect('test_master');
		}
	}

	// Added by Saurabh on 02-12-2024 to get test method
	public function get_test_method()
	{
		$key = ($this->input->get('key'))?$this->input->get('key'):NULL;
		$data = $this->test_master_model->get_test_method($key);
		echo json_encode($data);
	}
}
