<?php

use Mpdf\Tag\Em;

defined('BASEPATH') or exit('No direct script access allowed');

class Packages extends MY_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('test_management_model/Packages_model', 'packages_model');
		$this->load->library('excel');
		$this->check_session();
		$checkUser = $this->session->userdata('user_data');
		$this->user = $checkUser->uidnr_admin;
		$this->form_validation->set_error_delimiters('<div class="error" style="color:red">', '</div>');
	}

	public function index($p_id = NULL, $search = NULL,$packageBuyer=NULL, $sortby = NULL, $order = NULL)
	{
		$base_url = 'packages';
		$data['p_id'] = NULL;
		$data['sample_name'] = NULL;
		$data['search'] = NULL;
		$data['packageBuyer'] = NULL;
		$where = NULL;

		if ($p_id != NULL && $p_id != 'NULL') {
			$data['p_id'] = $where['pc.packages_sample_type_id'] = $p_id;
			$base_url .= '/' . $p_id;
			$data['sample_name'] = $this->packages_model->get_row('sample_type_name', 'mst_sample_types', 'sample_type_id' . '=' . $where['pc.packages_sample_type_id']);
		} else {
			$base_url .= '/NULL';
		}

		if ($search != NULL && $search != 'NULL') {
			$data['search'] = base64_decode($search);
			$base_url .= '/' . $search;
			$search = $data['search'];
		} else {
			$base_url .= '/NULL';
		}
		if ($packageBuyer != NULL && $packageBuyer != 'NULL') {
			$data['packageBuyer'] = base64_decode($packageBuyer);
			$base_url .= '/' . $packageBuyer;
			$packageBuyer = $data['packageBuyer'];
		} else {
			$base_url .= '/NULL';
			$data['packageBuyer'] = '';
		}
		if ($sortby != NULL && $sortby != 'NULL') {
			$sortby = $sortby;
			$base_url .= '/' . $sortby;
		} else {
			$sortby = NULL;
			$base_url .= '/NULL';
		}
		if ($order != NULL && $order != 'NULL') {
			$base_url .= '/' . $order;
		} else {
			$base_url .= '/NULL';
			$order = NULL;
		}
		// echo $sortby;echo $order;exit;

		$total_row = $this->packages_model->get_packages_list(NULL, NULL, $search,$packageBuyer, NULL, NULL, $where, '1');

		$config = $this->pagination($base_url, $total_row, 10, 7);
		$data["links"] = $config["links"];

		$data['packages_list'] = $this->packages_model->get_packages_list($config["per_page"], $config['page'], $search,$packageBuyer, $sortby, $order, $where);

		$start = (int)$this->uri->segment(7) + 1;
		$end = (($data['packages_list']) ? count($data['packages_list']) : 0) + (($this->uri->segment(7)) ? $this->uri->segment(7) : 0);
		$data['result_count'] = "Showing " . $start . " - " . $end . " of " . $total_row . " Results";

		if ($order == NULL || $order == 'NULL') {
			$data['order'] = ($order) ? "DESC" : "ASC";
		} else {
			$data['order'] = ($order == "ASC") ? "DESC" : "ASC";
		}
		$buyer_where = ['customer_type'=>'Buyer'];
		$data['buyers'] = $this->packages_model->get_result("customer_id,customer_name","cust_customers", $buyer_where); // new 23
		$this->load_view('test_management/packages', $data);
	}



	public function import_file()
	{
		$counter = 0;
		$path = $_FILES["file"]["tmp_name"];
		$object = PHPExcel_IOFactory::load($path);
		foreach ($object->getWorksheetIterator() as $worksheet) {
			$highestRow = $worksheet->getHighestRow();
			$highestColumn = $worksheet->getHighestColumn();
			for ($row = 2; $row <= $highestRow; $row++) {
				$package_name = $worksheet->getCellByColumnAndRow(0, $row)->getValue();
				$packages_sample_type_id = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
				$package_price = $worksheet->getCellByColumnAndRow(2, $row)->getValue();

				$getPackage = $this->packages_model->get_row('*', 'packages', ['LOWER(package_name)' => strtolower($package_name)]);
				if (empty($getPackage)) {
					$data = array(
						'package_name'  => $package_name,
						'packages_sample_type_id'   => $packages_sample_type_id,
						'package_price'   => $package_price,
						'created_on'    => date("Y-m-d H:i:s"),
						'created_by'  =>  $this->session->userdata('user_data')->uidnr_admin
					);
					$result = $this->packages_model->insert_data('packages', $data);
					if ($result) {
						$counter++;
					}
				}
			}
		}
		echo (!empty($counter)) ? json_encode($counter . 'Records imported!!') : json_encode('Something went wrong!');
	}


	function methodCheck($param)
	{
		$this->db->select("*");
		$this->db->from("packages");
		foreach ($param as $searchKey => $searchValue) {
			$this->db->where($searchKey, $searchValue);
		}
		$hasil = $this->db->get('')->result_array();
		if (isset($hasil)) {
			return false;
		} else {
			return true;
		}
	}




	public function open_packages($check, $package_id)
	{
		$buyer_where = ['customer_type'=>'Buyer'];
		$data['buyers'] = $this->packages_model->get_result("customer_id,customer_name","cust_customers", $buyer_where); // new 23


		if ($package_id != 'NULL') {
			$where['pc.package_id'] = $package_id;
			$data['data'] = $this->packages_model->get_packages_list(NULL, NULL, NULL, NULL, NULL,NULL, $where, NULL);
			$data['check'] = $check;
			$data['package_id']	= $package_id;
			$this->load_view('test_management/open_packages', ['item' => $data]);
		}
		if ($package_id == "NULL") {
			$data['data'] = '';
			$data['check'] = $check;
			$data['package_id']	= $package_id;
			$this->load_view('test_management/open_packages', ['item' => $data]);
		}
	
}



	public function insert_packages()
	{
		$data = $this->input->post();
		$valid = $this->form_validation->run('add_packages_validation');
		$data['created_by'] = $this->user;
		$data['created_on'] = date("Y-m-d");
		unset($data['sample_name']);
		if ($valid == true) {
			$result = $this->packages_model->saveTestpackages($data);
		} else {
			$result = false;
		}


		if ($result == true) {
			$this->session->set_flashdata('success', 'Package details added successfully');
			redirect('packages');
		} else {
			$this->open_packages('add', 'NULL');
			$this->session->set_flashdata('error', 'Please provide correct details');
		}
	}





	public function update_packages($package_id)
	{

		$data = $this->input->post();
		$valid = $this->form_validation->run('edit_packages_validation');
		$data['update_by'] = $this->user;
		$data['updated_on'] = date("Y-m-d");
		unset($data['sample_name']);
		if ($valid == true) {
			$result = $this->packages_model->update_packages($data, $package_id);
			$this->user_log_update($package_id, 'PACKAGES UPDATED WITH NAME ' . $data['package_name'], 'update_packages');
		} else {
			$result = false;
		}


		if ($result == true) {
			$this->session->set_flashdata('success', 'Package Details Updated Successfully.');
			redirect('packages');
		} else {
			$this->open_packages('update', $package_id);
			$this->session->set_flashdata('error', 'Plase provide correct details');
		}
	}




	public function test_list()
	{

		$package_id = $this->input->post('package_id');
		$result = $this->packages_model->getTestList($package_id);
		echo json_encode($result);
	}

	public function save_test_packages()
	{
		$test_data = $this->input->post();
		$package_id = $this->input->post('package_id');
		if (array_key_exists('row', $test_data)) {
			if ($test_data['row'] && count($test_data['row']) > 0) {
				$data = $test_data['row'];
				foreach ($data as $key => $value) {
					$data[$key]['test_package_division_id'] = '0';
					$data[$key]['test_package_lab_id'] = '0';
					$data[$key]['created_on'] = date("Y-m-d:H:s:i");
					$data[$key]['created_by'] = $this->user;
					$id = $data[$key]['test_package_packages_id'];
				}

				$result = $this->packages_model->insert_test($data, $id);
				$testdata = array();
				if ($result) {
					$testdata = $this->packages_model->getTestList($id);
					if ($testdata) {
						$LOG = $this->user_log_update($package_id, 'PACKAGE TESTS UPDATED AND SAVED', 'save_test_packages');
						if ($LOG) {
							$msg = array(
								'status' => 1,
								'msz' => 'Test Saved Successfully'
							);
						} else {
							$msg = array(
								'status' => 0,
								'msz' => 'error in generating log'
							);
						}
					} else {
						$msg = array(
							'status' => 0,
							'msz' => 'Error In Adding Test'
						);
					}
				} else {
					$msg = array(
						'status' => 0,
						'msz' => 'Test Not Added!'
					);
				}
			} else {
				$msg = array(
					'status' => 0,
					'msz' => 'Test Not Added!'
				);
			}
		} else {

			$result = $this->packages_model->delete_test($package_id);

			if ($result) {

				$LOG = $this->user_log_update($package_id, 'PACKAGE TESTS UPDATED AND SAVED', 'save_test_packages');
				if ($LOG) {
					$msg = array(
						'status' => 1,
						'msz' => 'All Test Record Deleted!'
					);
				} else {
					$msg = array(
						'status' => 0,
						'msz' => 'error in generating log'
					);
				}
			} else {
				$msg = array(
					'status' => 0,
					'msz' => 'Error while deleting test'
				);
			}
		}



		echo json_encode($msg);
	}

	public function add_testPackages()
	{
		$id = NULL;
		$package_id = $this->input->post('package_id');
		$test_data['test_package_packages_id'] = $this->input->post('test_package_packages_id');
		$id = $test_data['test_package_test_id'] = $this->input->post('test_package_test_id');
		if ($id) {
			$data = $this->packages_model->get_fields_by_id('tests', 'test_method,test_name', $id, 'test_id');
			$test_data['package_test_method'] = $data[0]['test_method'];
			$test_name = $data[0]['test_name'];
			$test_data['package_test_sort_order'] = $this->input->post('order');
			$test_data['test_package_division_id'] = '0';
			$test_data['test_package_lab_id'] = '0';
			$test_data['created_on'] = date("Y-m-d:H:s:i");
			$test_data['created_by'] = $this->user;
			$result = $this->packages_model->insert_data('test_packages', $test_data);
		} else {
			$result = false;
		}

		$data = array();
		$data = $this->packages_model->getTestList($test_data['test_package_packages_id']);

		if ($result) {
			$LOG = $this->user_log_update($package_id, 'PACKAGE TEST ADDED WITH NAME ' . $test_name, 'add_testPackages');
			if ($LOG) {
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
				'msg' => 'Test Error in Adding Test'
			);
		}

		echo json_encode($msg);
	}

	public function save_package_price()
	{
		$data = array();
		$data = $this->input->post();

		if ($data['row'] && count($data['row'])) {

			for ($i = 0; $i < count($data['row']); $i++) {

				$data['row'][$i]['type'] = 'Package';
				$data['row'][$i]['type_id'] = $data['price_package_id'];
				unset($data['row'][$i]['sl_number']);
				unset($data['row'][$i]['country_code']);
				$code = $data['row'][$i]['currency_code'];
				$where1['currency_code'] = $code;
				unset($data['row'][$i]['currency_code']);
				$id = $this->packages_model->get_row('currency_id', 'mst_currency', $where1);

				$data['row'][$i]['currency_id'] = $id->currency_id;
				$where2['type_id'] = $data['price_package_id'];
				$where2['currency_id'] = $id->currency_id;
				$get = $this->packages_model->get_row('pricelist_id', 'pricelist', $where2);

				if ($get->pricelist_id) {
					$where3['pricelist_id'] = $get->pricelist_id;
					$result = $this->packages_model->update_data('pricelist', $data['row'][$i], $where3);
				} else {
					$result = $this->packages_model->insert_data('pricelist', $data['row'][$i]);
				}
			}
		}

		if ($result) {
			$LOG = $this->user_log_update($data['price_package_id'], 'PRICE LIST UPDATED', 'save_package_price');
			if ($LOG) {
				$msg = array(
					'status' => 1,
					'msg' => 'Test Updated Successfully'
				);
			} else {
				$msg = array(
					'status' => 0,
					'msg' => 'Error In generating log'
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


	public function get_price_list()
	{
		$package_id = $this->input->post('package_id');
		$result = $this->packages_model->get_price_list($package_id);
		echo json_encode($result);
	}





















	public function uniq()
	{
		$id = $this->uri->segment('2');
		$data['package_name'] = $this->input->post('package_name');
		$check = $this->packages_model->checkDuplicate($data, $id);

		if ($check) {
			return true;
		} else {
			$this->form_validation->set_message('uniq', '{field} is already in use');
			return false;
		}
	}


	public function get_log_data()
	{
		$id = $this->input->post('id');
		$data = $this->packages_model->get_log_data($id);
		echo json_encode($data);
	}




	public function user_log_update($id, $text, $action)
	{
		$data = array();
		$data['source_module'] = 'Packages';
		$data['record_id'] = $id;
		$data['created_on'] = date("Y-m-d h:i:s");
		$data['created_by'] = $this->user;
		$data['action_taken'] = $action;
		$data['text'] = $text;

		$result = $this->packages_model->insert_data('user_log_history', $data);
		if ($result) {
			return true;
		} else {
			return false;
		}
	}
}
