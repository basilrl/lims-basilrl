<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Test_protocols extends MY_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->check_session();
		$this->load->model('test_management_model/Test_protocols_model', 'test_protocols_model');
		$checkUser = $this->session->userdata('user_data');
		$this->user = $checkUser->uidnr_admin;
	}

	public function index($sample_id = NULL, $type_id = NULL, $search = NULL, $sortby = NULL, $order = NULL, $page_no = NULL)
	{
		$where = NULL;
		$base_url = 'test_protocols';

		if ($sample_id != '' && $sample_id != 'NULL') {
			$base_url .= '/' . $sample_id;
			$where['pto.protocol_sample_type_id'] = $sample_id;
			$data['sample_id'] = $sample_id;
			$data['sample_name'] = $this->test_protocols_model->get_row('sample_type_name', 'mst_sample_types', 'sample_type_id' . '=' . $where['pto.protocol_sample_type_id']);
		} else {
			$base_url .= '/NULL';
			$data['sample_id'] = NULL;
			$data['sample_name'] = NULL;
		}
		if ($type_id != '' && $type_id != 'NULL') {
			$base_url .= '/' . $type_id;
			$data['type_id'] = $type_id;
			switch ($data['type_id']) {
				case '1':
					$where['pto.protocol_type'] = "Global Standard";
					break;
				case '2':
					$where['pto.protocol_type'] = "Customer Specific";
					break;
				case '3':
					$where['pto.protocol_type'] = "BASIL";
					break;
			}
		} else {
			$base_url .= '/NULL';
			$data['type_id'] = NULL;
			$data['type_name'] = NULL;
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

		$total_row = $this->test_protocols_model->get_protocol_list(NULL, NULL, $search, NULL, NULL, $where, '1');
		$config = $this->pagination($base_url, $total_row, 10, 7);
		$data["links"] = $config["links"];
		$data['protocol_list'] = $this->test_protocols_model->get_protocol_list($config["per_page"], $config['page'], $search, $sortby, $order, $where);

		$start = (int)$page_no + 1;
		$end = (($data['protocol_list']) ? count($data['protocol_list']) : 0) + (($page_no) ? $page_no : 0);
		$data['result_count'] = "Showing " . $start . " - " . $end . " of " . $total_row . " Results";

		if ($order == NULL || $order == 'NULL') {
			$data['order'] = ($order) ? "DESC" : "ASC";
		} else {
			$data['order'] = ($order == "ASC") ? "DESC" : "ASC";
		}

		$this->load_view('test_management/test_protocols', $data);
	}

	public function add_protocol()
	{
		$country = NULL;
		$this->load_view('test_management/add_test_protocols', ['country' => $country]);
	}

	// insert protocol
	public function insert_protocol()
	{
		$setvalue = NULL;
		$data = $this->input->post();
		$setvalue['country'] = NULL;
		$setvalue['buyer']  = NULL;
		$country_ids = $this->input->post('protocol_country_id');
		$buyer_ids = $this->input->post('protocol_buyer_id');
		$setvalue = $this->getsetvaluedata($buyer_ids, $country_ids);
		$valid = $this->form_validation->run('add_test_protocol');
		$this->form_validation->set_error_delimiters('<div class="error" style="color:red">', '</div>');

		if ($valid == true) {
			$data = $this->unset_data($data);
			$data['protocol_price'] = '0';
			if ($_FILES['file'] && !empty($_FILES['file']['name'])) {
                $protocol_upload = $this->multiple_upload_image($_FILES['file']);
                if ($protocol_upload) {
                    $data['protocol_file'] = $protocol_upload['aws_path'];
                }
            }
			$result = $this->test_protocols_model->insert_protocols($data, $country_ids, $buyer_ids);
		} else {
			$result = false;
		}

		if ($result == true) {
			redirect('test_protocols');
			$this->session->set_flashdata('success', 'Test protocol added successfully');
		} else {
			$this->session->set_flashdata('error', 'Error in adding Test protocol');
			$this->load_view('test_management/add_test_protocols', $setvalue);
		}
	}

	// unsetting unusefull data 
	public function unset_data($data)
	{
		unset($data['sample_type_name']);
		unset($data['protocol_country_id']);
		unset($data['protocol_buyer_id']);
		return $data;
	}


	// edit protocol load page

	public function edit_protocol($protocol_id, $setvalue = NULL)
	{
		$where['pto.protocol_id'] = $protocol_id;
		$protocols = $this->test_protocols_model->get_protocol_list(NULL, NULL, NULL, NULL, NULL, $where, NULL);
		$protocols['item'] = $protocols[0];

		if ($setvalue == NULL) {

			$ids = $this->test_protocols_model->get_countryNbuyers($protocol_id);

			if ($ids) {

				if (count($ids['country']) > 0) {
					foreach ($ids['country'] as $key => $value) {
						$country_ids[$key] = $value->protocol_country_id;
					}
				} else {
					$country_ids = [];
				}
				if (count($ids['buyer'])) {
					foreach ($ids['buyer'] as $key => $value) {
						$buyer_ids[$key] = $value->protocol_buyer_id;
					}
				} else {

					$buyer_ids = [];
				}
			}


			$setvalue = $this->getsetvaluedata($buyer_ids, $country_ids);
		}

		$protocols['country'] = $setvalue['country'];
		$protocols['buyer'] = $setvalue['buyer'];

		$this->load_view('test_management/edit_test_protocols', $protocols);
	}


	//update protocol

	public function update_protocol($protocol_id)
	{

		$data = $this->input->post();
		$setvalue['country'] = NULL;
		$setvalue['buyer']  = NULL;
		$country_ids = $this->input->post('protocol_country_id');
		$buyer_ids = $this->input->post('protocol_buyer_id');
		$setvalue = $this->getsetvaluedata($buyer_ids, $country_ids);

		$valid = $this->form_validation->run('edit_test_protocol');
		$this->form_validation->set_error_delimiters('<div class="error" style="color:red">', '</div>');

		if ($valid == true) {
			$data = $this->unset_data($data);
			$data['protocol_price'] = '0';
			if ($_FILES['file'] && !empty($_FILES['file']['name'])) {
                $protocol_upload = $this->multiple_upload_image($_FILES['file']);
                if ($protocol_upload) {
                    $data['protocol_file'] = $protocol_upload['aws_path'];
                }
            }
			$result = $this->test_protocols_model->update_protocols($data, $country_ids, $buyer_ids, $protocol_id);
		} else {
			$result = false;
		}

		if ($result == true) {
			$this->user_log_update($protocol_id, 'PROTOCOL UPDATED WITH NAME ' . $data['protocol_name'], 'update_protocol');

			$this->session->set_flashdata('success', 'Test protocol Updated successfully');
			redirect('test_protocols');
		} else {
			$this->edit_protocol($protocol_id, $setvalue);
			$this->session->set_flashdata('error', 'Error in adding Test protocol');
		}
	}

	public function getsetvaluedata($buyer_ids, $country_ids, $setvalue = NULL)
	{

		if ($country_ids && count($country_ids) > 0) {
			$setvalue['country'] = $this->test_protocols_model->get_country($country_ids);
		} else {
			$setvalue['country'] = [];
		}

		if ($buyer_ids && count($buyer_ids) > 0) {
			foreach ($buyer_ids as $key => $value) {
				$buyers[$key] = $value;
			}
			$setvalue['buyer'] = $this->test_protocols_model->fetchBuyers(NULL, NULL, $buyers, NULL);
		} else {
			$setvalue['buyer'] = [];
		}

		return $setvalue;
	}


	public function uniq()
	{
		$data['id'] = $this->uri->segment('2');
		$data['name'] = $this->input->post('protocol_name');
		$check = $this->test_protocols_model->check_duplicate($data);

		if ($check) {
			return true;
		} else {
			$this->form_validation->set_message('uniq', 'Protocol name is already in use');
			return false;
		}
	}



	public function test_list()
	{
		$protocol_id = $this->input->post('protocol_id');
		$result = $this->test_protocols_model->getTestList($protocol_id);
		echo json_encode($result);
	}


	public function add_protocol_test()
	{
		$protocol_id = NULL;
		$data = $this->input->post();
		$protocol_id = $this->input->post('protocol_id');
		if ($data['protocol_test_id']) {
			$method =  $this->test_protocols_model->get_fields_by_id('tests', 'test_method', $data['protocol_test_id'], 'test_id');

			$TEST_NAME = $this->test_protocols_model->get_fields_by_id('tests', 'test_name', $data['protocol_test_id'], 'test_id');
			$TEST_NAME = $TEST_NAME[0]['test_name'];

			$data['protocol_test_method'] = $method[0]['test_method'];
			$result = $this->test_protocols_model->insert_data('protocol_tests', $data);
			$data = array();
			$data = $this->test_protocols_model->getTestList($protocol_id);
		} else {
			$result = false;
		}

		if ($result) {
			$log = $this->user_log_update($protocol_id, 'TEST ADDED WITH NAME ' . $TEST_NAME, 'add_protocol_test');
			if ($log) {
				$msg = array(
					'status' => 1,
					'msg' => 'Test Added Successfully'
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
				'msg' => 'Error in Adding Test'
			);
		}
		echo json_encode($msg);
	}


	public function save_test_protocols()
	{
		$id = NULL;
		$test_data = $this->input->post();

		if (count($test_data['row']) > 0 && count($test_data['row']) > 0) {
			$data = $test_data['row'];
			foreach ($data as $key => $value) {
				$id = $data[$key]['protocol_id'];
			}
			if ($id) {
				$result = $this->test_protocols_model->insert_test($data, $id);
				if ($result) {
					$result = true;
				} else {
					$result = false;
				}
			}
		}

		if ($result) {
			$log = $this->user_log_update($id, 'TEST UPDATED ', 'save_test_protocols');
			if ($log) {
				$msg = array(
					'status' => 1,
					'msg' => 'Test Saved Successfully'

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
				'msg' => 'Error in Adding Test',

			);
		}

		echo json_encode($msg);
	}


	public function get_price_list()
	{
		$protocol_id = $this->input->post('protocol_id');
		$result = $this->test_protocols_model->get_price_list($protocol_id);
		echo json_encode($result);
	}

	public function save_protocol_price()
	{
		$data = array();
		$data = $this->input->post();
		if ($data['row'] && count($data['row'])) {
			$sum = count($data['row']);
			for ($i = 0; $i < $sum; $i++) {
				$data['row'][$i]['type'] = 'Protocol';
				$data['row'][$i]['type_id'] = $data['protocol_id'];
				unset($data['row'][$i]['sl_number']);
				unset($data['row'][$i]['country_code']);
				$code = $data['row'][$i]['currency_code'];
				$where1['currency_code'] = $code;
				unset($data['row'][$i]['currency_code']);
				$id = $this->test_protocols_model->get_row('currency_id', 'mst_currency', $where1);
				$data['row'][$i]['currency_id'] = $id->currency_id;
				$where2['type_id'] = $data['protocol_id'];
				$where2['currency_id'] = $id->currency_id;
				$get = $this->test_protocols_model->get_row('pricelist_id', 'pricelist', $where2);

				if ($get->pricelist_id) {
					$where3['pricelist_id'] = $get->pricelist_id;
					$result = $this->test_protocols_model->update_data('pricelist', $data['row'][$i], $where3);
				} else {
					$result = $this->test_protocols_model->insert_data('pricelist', $data['row'][$i]);
				}
			}
		}

		if ($result) {
			$log = $this->user_log_update($data['protocol_id'], 'PRICE LIST UPDATED ', 'save_protocol_price');
			if ($log) {
				$msg = array(
					'status' => 1,
					'msg' => 'Test Updated Successfully'
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
		$data = $this->test_protocols_model->get_log_data($id);
		echo json_encode($data);
	}

	public function user_log_update($id, $text, $action)
	{
		$data = array();
		$data['source_module'] = 'Test_protocols';
		$data['record_id'] = $id;
		$data['created_on'] = date("Y-m-d h:i:s");
		$data['created_by'] = $this->user;
		$data['action_taken'] = $action;
		$data['text'] = $text;

		$result = $this->test_protocols_model->insert_data('user_log_history', $data);
		if ($result) {
			return true;
		} else {
			return false;
		}
	}

	// Added by CHANDAN --22-06-2022
	public function import_test_protocol()
	{
		ini_set('max_execution_time', 0);
		ini_set('memory_limit', '-1');

		$insertedRecord = 0;
		$import_protocol_id = $this->input->post('import_protocol_id');

		if (!empty($import_protocol_id) && isset($_FILES["import_test_file"]["name"])) {

			$file_data = fopen($_FILES["import_test_file"]["tmp_name"], 'r');
			fgetcsv($file_data);
			while (($row = fgetcsv($file_data)) !== false) {

				$test_name     	= trim($row[0]);
				$test_method 	= trim($row[1]);
				$division     	= trim($row[2]);
				$priority_order = trim($row[3]);
				$requirements   = trim($row[4]);

				if (!empty($test_name) && !empty($test_method) && !empty($division)) {

					// Get Protocol Data
					$protolInfo = $this->test_protocols_model->get_row('protocol_id, protocol_sample_type_id', 'protocols', ['protocol_id' => $import_protocol_id]);

					if (!empty($protolInfo) && isset($protolInfo->protocol_id)) {
						$protocol_id = $protolInfo->protocol_id;
						$protocol_sample_type_id = $protolInfo->protocol_sample_type_id;

						// Get Method Data....
						$methodInfo = $this->test_protocols_model->get_row('test_method_id', 'mst_test_methods', ['LOWER(test_method_name)' => strtolower($test_method)]);

						if (!empty($methodInfo) && isset($methodInfo->test_method_id)) {
							$method_id = $methodInfo->test_method_id;
						} else {
							$method_insert = array(
								'test_method_name'  => $test_method,
								'status' 			=> '1',
								'created_by'        => $this->user,
								'created_on'        => date("Y-m-d H:i:s")
							);
							$method_id = $this->test_protocols_model->insert_data('mst_test_methods', $method_insert);
							if (!empty($method_id)) {
								$log_details1 = array(
									'source_module'     => 'Test_method',
									'record_id'         => $method_id,
									'created_on' 		=> date("Y-m-d h:i:s"),
									'created_by' 		=> $this->user,
									'action_taken' 		=> 'import_test_protocol',
									'text' 				=> 'CSV Data Imported'
								);
								$this->test_protocols_model->insert_data('user_log_history', $log_details1);
							}
						}

						// Get Method Data....
						$divisionInfo = $this->test_protocols_model->get_row('division_id', 'mst_divisions', ['LOWER(division_name)' => strtolower($division)]);

						if (!empty($divisionInfo) && isset($divisionInfo->division_id)) {
							$division_id = $divisionInfo->division_id;
						} else {
							$division_insert = array(
								'division_name'  	=> $division,
								'status'			=> '1',
								'created_by'        => $this->user,
								'created_on'        => date("Y-m-d H:i:s")
							);
							$division_id = $this->test_protocols_model->insert_data('mst_divisions', $division_insert);
							if (!empty($division_id)) {
								$log_details2 = array(
									'source_module'     => 'Division',
									'record_id'         => $division_id,
									'created_on' 		=> date("Y-m-d h:i:s"),
									'created_by' 		=> $this->user,
									'action_taken' 		=> 'import_test_protocol',
									'text' 				=> 'CSV Data Imported'
								);
								$this->test_protocols_model->insert_data('user_log_history', $log_details2);
							}
						}

						// Get Test Data
						$testInfo = $this->test_protocols_model->get_row('test_id', 'tests', ['LOWER(test_name)' => strtolower($test_name), 'test_method_id' => $method_id]);

						if (!empty($testInfo) && isset($testInfo->test_id)) {
							$test_id = $testInfo->test_id;
						} else {
							$test_insert = array(
								'test_name' 			=> $test_name,
								'test_division_id'		=> $division_id,
								'test_method'			=> $test_method,
								'test_sample_type_id'	=> $protocol_sample_type_id,
								'test_status'        	=> 'Active',
								'test_method_id'		=> $method_id,
								'created_by'    		=> $this->user,
								'created_on'    		=> date("Y-m-d H:i:s")
							);
							$test_id = $this->test_protocols_model->insert_data('tests', $test_insert);
							if (!empty($test_id)) {
								$log_details3 = array(
									'source_module'     => 'Test_master',
									'record_id'         => $test_id,
									'created_on' 		=> date("Y-m-d h:i:s"),
									'created_by' 		=> $this->user,
									'action_taken' 		=> 'import_test_protocol',
									'text' 				=> 'CSV Data Imported'
								);
								$this->test_protocols_model->insert_data('user_log_history', $log_details3);
							}
						}

						// Get Protocol Test Data
						$protocolTestInfo = $this->test_protocols_model->get_row('protocol_tests_id', 'protocol_tests', ['protocol_id' => $protocol_id, 'protocol_test_id' => $test_id, 'LOWER(protocol_test_method)' => strtolower($test_method)]);

						if (empty($protocolTestInfo)) {
							$protocol_tests_insert = array(
								'protocol_id' 				=> $protocol_id,
								'protocol_test_id'			=> $test_id,
								'test_method_id'			=> $method_id,
								'protocol_test_method'		=> $test_method,
								'protocol_test_sort_order'	=> $priority_order,
								'protocol_requirements'    	=> $requirements
							);
							$protocol_insert = $this->test_protocols_model->insert_data('protocol_tests', $protocol_tests_insert);
							if (!empty($protocol_insert)) {
								$log_details4 = array(
									'source_module'     => 'Test_protocols',
									'record_id'         => $protocol_insert,
									'created_on' 		=> date("Y-m-d h:i:s"),
									'created_by' 		=> $this->user,
									'action_taken' 		=> 'import_test_protocol',
									'text' 				=> 'CSV Data Imported'
								);
								$this->test_protocols_model->insert_data('user_log_history', $log_details4);
								$insertedRecord++;
							}
						}
					}
				}
			}
			if (($insertedRecord > 0)) {
				echo json_encode(["message" => $insertedRecord . " Data imported!", "status" => 1]);
			} else {
				echo json_encode(["message" => "Something Went Wrong!.", "status" => 0]);
			}
		}
	}
	// End...
	 public function downloadPdf($id)
    {
        $path = $this->db->where('protocol_id',$id)->get('protocols')->row();
        if ($path) {
            if ($path->protocol_file) {
                $this->load->helper('download');
                $pdf_path    =   file_get_contents($path->protocol_file);
                $pdf_name    =   ($path->original_file_name != '')?(basename($path->original_file_name)):(basename($path->protocol_file));
                force_download($pdf_name, $pdf_path);
            } else {
                echo '<h1>NO RECORD FOUND</h1>';
            }
        } else {
            echo '<h1>“This pdf stands cancelled. Please do not transact based on this cancelled pdf. Geo Chem will not be liable for any issues, financial, legal or otherwise, based on using this cancelled pdf for any purpose.</h1>';
        }
    }
}
