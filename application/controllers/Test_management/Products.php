<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Products extends MY_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('test_management_model/Products_model', 'products_model');
		$this->check_session();
		$checkUser = $this->session->userdata('user_data');
		$this->user = $checkUser->uidnr_admin;
	}

	public function index($cat_id = NULL, $search = NULL, $ecom_available = NULL, $sortby = NULL, $order = NULL,$page_no=NULL)
	{
               // $this->output->enable_profiler(true);
		$where = NULL;
		$base_url = 'products';
		// print_r($this->uri->segment('5'));exit;
		if ($cat_id != NULL && $cat_id != 'NULL') {
			$base_url .= '/' . $cat_id;
			$data['cat_id'] = $where['cat.sample_category_id'] = $cat_id;
			$data['cat_name'] = $this->products_model->get_row('sample_category_name', 'mst_sample_category', 'sample_category_id' . '=' . $data['cat_id']);
		} else {
			$base_url .= '/NULL';
			$data['cat_id'] = NULL;
			$data['cat_name'] = NULL;
		}
		if ($search != NULL && $search != 'NULL') {
			$data['search'] =  base64_decode($search);
			$base_url .= '/' . $search;
			$search = base64_decode($search);
		} else {
			$base_url .= '/NULL';
			$data['search'] = NULL;
			$search=NULL;
		}
		if ($ecom_available != NULL && $ecom_available != 'NULL') {
			$data['available_to_ecommerce'] =  $where['available_to_ecommerce'] = base64_decode($ecom_available);
			$base_url .= '/' . $ecom_available;
			$search = base64_decode($search);
		} else {
			$base_url .= '/NULL';
			$data['available_to_ecommerce'] = NULL;
			$search=NULL;
		}
		if ($sortby != NULL && $sortby != 'NULL') {
			$base_url .= '/' . $sortby;
		} else {
			$base_url .= '/NULL';
			$sortby =NULL;
		}
		if ($order != NULL && $order != 'NULL') {
			$base_url .= '/' . $order;
			
		} else {
			$base_url .= '/NULL';
			$order =NULL;
		}

		$total_row = $this->products_model->get_prod_List(NULL, NULL, $search, NULL, NULL, $where, '1');

		$config = $this->pagination($base_url, $total_row, 10, 7);
		$data["links"] = $config["links"];
		$data['product_list'] = $this->products_model->get_prod_List($config["per_page"], $config['page'], $search, $sortby, $order, $where);

		$start = (int)$page_no + 1;
		$end = (($data['product_list']) ? count($data['product_list']) : 0) + (($page_no) ? $page_no : 0);
		$data['result_count'] = "Showing " . $start . " - " . $end . " of " . $total_row . " Results";

		if ($order == NULL || $order == 'NULL') {
			$data['order'] = ($order) ? "DESC" : "ASC";
		} else {
			$data['order'] = ($order == "ASC") ? "DESC" : "ASC";
		}

		$this->load_view('test_management/products', $data);
	}

	public function add_product()
	{
		$this->load_view('test_management/add_product', NULL);
	}


	public function insert_product()
	{
		$valid = $this->form_validation->run('add_product_validation');
		$this->form_validation->set_error_delimiters('<div class="error" style="color:red">', '</div>');
		$data = $this->input->post();
		$log['created_by'] = $data['created_by'] = $this->user;
		$data['minimum_quantity'] = '0';
		unset($data['cat_name']);
		unset($data['unit_name']);

		if ($valid == true) {

			$valid_file = $this->check_valid_file_upload($_FILES['upload_image']);
			if ($valid_file) {
				$data['upload_image'] = $valid_file['image'];
				$data['thumb_image'] = $valid_file['thumb'];
				$log['title'] = 'Product Inserted';
				$result = $this->products_model->insert_product_data($data, $log);
			} else {
				$result = false;
				$this->session->set_flashdata('error_msz', 'Please select a JPG image.');
			}
		} else {
			$result = false;
		}

		if ($result == false) {
			$this->session->set_flashdata('error', 'Error in Adding Product');
			$this->load_view('test_management/add_product', NULL);
		} else {

			
			$this->session->set_flashdata('success', 'Product Successfully Added');
			redirect('products');
		}
	}
        public function list_product($sample_type_id)
	{       
		$data=array('available_to_ecommerce'=>1);
                $this->db->where('sample_type_id',$sample_type_id);
                $this->db->update('mst_sample_types',$data);
                $this->load->library('user_agent');
                redirect($_SERVER['HTTP_REFERER']);
	}
        public function unlist_product($sample_type_id)
	{       
		$data=array('available_to_ecommerce'=>0);
                $this->db->where('sample_type_id',$sample_type_id);
                $this->db->update('mst_sample_types',$data);
                $this->load->library('user_agent');
                redirect($_SERVER['HTTP_REFERER']);
	}
	public function edit_product($id)
	{
		$data = $this->products_model->get_sample_type($id);
		$this->load_view('test_management/edit_product', ['item' => $data]);
	}

	public function update_product($id)
	{
		$data = $this->products_model->get_sample_type($id);
		$update_data  = $this->input->post();
		$log['created_by'] = $data['updated_by'] = $update_data['updated_by'] = $this->user;
		$log['title'] = 'Product Details Updated';
		$log['sample_type_id'] = $id;
		$data['updated_on'] = $update_data['updated_on'] = date("Y-m-d h:i:s", time());
		$data['minimum_quantity'] = $update_data['minimum_quantity'] = '0';
		$this->form_validation->set_error_delimiters('<div class="error" style="color:red">', '</div>');
		$valid = $this->form_validation->run('edit_product_validation');
		$file = $_FILES['upload_image'];

		if ($valid == TRUE && $file['name'] == '') {
			$update_data['upload_image'] = $data['upload_image'];
			$update_data['thumb_image'] = $data['thumb_image'];
			unset($update_data['cat_name']);
			unset($update_data['unit_name']);
			$result = $this->products_model->update_data('mst_sample_types', $update_data, 'sample_type_id' . '=' . $id);
			$log_result = $this->products_model->insert_data('mst_sample_types_log', $log);
			} else if ($valid == TRUE && $file['name'] != '') {
			if ($file['type'] != 'image/jpeg') {
				$this->session->set_flashdata('error_msz', 'Image Type is not JPG|JPEG');
				$result = FALSE;
			} else {
				$upload = $this->multiple_upload_image($file);
				// $thumb_name = $this->generate_image_thumbnail($file['name'], $file['tmp_name'], THUMB_PATH);
				// Added by Saurabh on 12-07-2021 to generate product image thumbnail
				$thumb_name = $this->generate_product_image_thumbnail($file['name'], $file['tmp_name'], THUMB_PATH);
				// Added by Saurabh on 12-07-2021 to generate product image thumbnail
				$upload_thumb = $this->upload_thumb_aws(THUMB_PATH . $thumb_name, $thumb_name);
				$update_data['upload_image'] = $upload['aws_path'];
				$update_data['thumb_image'] = $upload_thumb['aws_path'];
				unset($update_data['cat_name']);
				unset($update_data['unit_name']);
				$result = $this->products_model->update_data('mst_sample_types', $update_data, 'sample_type_id' . '=' . $id);
			
				$log_result = $this->products_model->insert_data('mst_sample_types_log', $log);
			}
		} else {
			$this->session->set_flashdata('error', 'Error in Updating Details');
			$result = FALSE;
		}

		if ($result == TRUE && $log_result == TRUE) {

			 $this->user_log_update($id,'UPDATE PRODUCT WITH NAME '.$update_data['sample_type_name'],'update_product');
			$this->session->set_flashdata('success', 'Product Successfully Updated');
			redirect('products');
			
		}
		if ($result == FALSE) {
			$this->load_view('test_management/edit_product', ['item' => $data]);
		}
	}

	public function check_valid_file_upload($file_name)
	{
		if ($file_name['name'] != '' && $file_name['type'] == 'image/jpeg') {
			$image = $this->multiple_upload_image($file_name);
			if (!empty($image)) {
				$img['image'] = $image['aws_path'];
				// $thumb_name = $this->generate_image_thumbnail($file_name['name'], $file_name['tmp_name'], THUMB_PATH);
				// Added by Saurabh on 12-07-2021 to generate product image thumbnail
				$thumb_name = $this->generate_product_image_thumbnail($file_name['name'], $file_name['tmp_name'], THUMB_PATH);
				// Added by Saurabh on 12-07-2021 to generate product image thumbnail
				$thumb = $this->upload_thumb_aws(THUMB_PATH . $thumb_name, $thumb_name);
				$img['thumb'] = $thumb['aws_path'];
				$result = true;
			} else {
				$result = false;
			}
		} else {
			$result = false;
		}

		if ($result == false) {
			return false;
		} else {
			return $img;
		}
	}

	

	public function import_excel(){

		$data = null;
		$file = null;
		$file = $_FILES['excel_import'];

		if(!empty($file['name'])){
			if($file['type']=="text/csv"){
				$file  = fopen($file['tmp_name'] , "r");
			}
			else{
				$msg = array(
					'status'=>0,
					'msg'=>'File type is not csv'
				);
			}
			
			$i =0;
			while (($row = fgetcsv($file, 10000, ",")) !== FALSE){
				if($i>0){
							$dup['sample_types_code'] = $row[0];
							$dup['sample_type_name'] = $row[1];
							$duplicate = $this->products_model->checkDuplicate($dup,NULL);
						if($duplicate==false){
							continue;
						}
						else{
							$id= $this->products_model->get_catid($row[2]);
							$cat_id = $id->sample_category_id;
							if($cat_id!=NULL && $cat_id>0){
							$cat_id =$cat_id;  
							}
							else{
								$cat_id='0';	
							}
	
						// $data[]="('".$row[0]."','".$row[1]."','".$cat_id."','".$row[3]."','0','0')";
							$data[] = array(
							"sample_types_code"=>$row[0],
							"sample_type_name"=>$row[1],
							"type_category_id"=>$cat_id,
							"retain_period"=>$row[3],
							"minimum_quantity"=>'0',
							"minimum_quantity_units"=>'0'
						);
					}
				}
				$i++;
			
			}
			if($data && count($data)>0){
	
				$sql = $this->products_model->insert_multiple_data('mst_sample_types',$data);
			}
			else{
				$sql = false;
			}
			if($sql){
				$this->session->set_flashdata('success', 'Product Successfully Updated');
				$msg = array(
					'status'=>1
				);
				
			}
			else{
				$msg = array(
					'status'=>0,
					'msg'=>'Error in import products or duplicate entry '
				);
			}
		}
		else{
			$msg = array(
				'status'=>0,
				'msg'=>'Please select a file'
			);
		}
		echo json_encode($msg);
	}

	public function uniq()
	{
		$id = $this->uri->segment('2');
		$data['sample_types_code'] = $this->input->post('sample_types_code');
		$data['sample_type_name'] = $this->input->post('sample_type_name');
		$check = $this->products_model->checkDuplicate($data,$id);
		
		if($check){	
			return true;
		}
		else{
			$this->form_validation->set_message('uniq', '{field} is already in use');
			return false;
		}
	}


	public function get_test_list_for_product(){
		$search = $this->input->post('search');
		if($search){
			$result = $this->products_model->get_test_list($search);
		}
		else{
			$result = false;
		}
       

		echo json_encode($result); 
	}
	

	public function test_list(){
		$product_id = $this->input->post('product_id');
		$result = $this->products_model->getTestList($product_id);
		echo json_encode($result);
	}
	
	public function add_test_products(){
		$data = $this->input->post();

		$test_name = $this->products_model->get_row('test_name','tests',['test_id'=>$data['test_sample_type_test_id']]);
		if($test_name && count($test_name)>0){
			$test_name = $test_name->test_name;
		}
	
		if($data && count($data)>0){
			$result = $this->products_model->insert_test_product($data);	
			if($result){
				$log = $this->user_log_update($data['product_id'],'TEST ADDED TO PRODUCT WITH NAME '.$test_name,'add_test_products');

				if($log){
					$msg =array(
						'status'=>1,
						'msg'=>'Test Added Successfully'
					);
				}
				else{
					$msg =array(
						'status'=>0,
						'msg'=>'Error in generating log'
					);
				}
				
			}
			else{
				$msg =array(
					'status'=>0,
					'msg'=>'Error in test adding or maybe duplicate'
				);
			}
		}
		else{
			$msg =array(
				'status'=>0,
				'msg'=>'Error in test adding'
			);
		}

		echo json_encode($msg);
		
	}

	public function save_test_products(){
		$data['row'] = null;
		$product_id = null;
		$result = null;
		$data = $this->input->post();
		if($data && count($data)){
			$product_id = $data['product_id'];
			unset($data['product_id']);
		}
		
	
		if(array_key_exists('row',$data)){
			$testData = $data['row'];
			if($product_id){
		
				$result = $this->products_model->update_test_product($testData,$product_id);
				if($result){

					$log = $this->user_log_update($product_id,'TEST UPDATED AND SAVED','save_test_products');
					if($log){
						$msg = array(
							'status'=>1,
							'msg'=>'Test Saved Successfully'
						);
					}
					
				}
				else{
					$msg = array(
						'status'=>0,
						'msg'=>'Error in Saving Test'
					);
				}
				
			}
		}
		else
		{
			$result = $this->products_model->delete_test($product_id);
			
			if($result){

				$log = $this->user_log_update($product_id,'TEST UPDATED AND SAVED','save_test_products');
					if($log){
						$msg = array(
							'status'=>1,
							'msg'=>'Test Saved Successfully'
						);
					}
			}
			else{
				$msg = array(
					'status'=>0,
					'msg'=>'Error while deleting test'
				);
			}
		}


		echo json_encode($msg);
	}


	
    public function get_log_data()
	{
		$id = $this->input->post('id');
		$data = $this->products_model->get_log_data($id);
		echo json_encode($data);
	}
	
	public function user_log_update($id,$text,$action){
		$data = array();
		$data['source_module'] = 'Products';
		$data['record_id'] = $id;
		$data['created_on'] = date("Y-m-d h:i:s");
		$data['created_by'] = $this->user;
		$data['action_taken'] = $action;
		$data['text'] = $text;

		$result = $this->products_model->insert_data('user_log_history',$data);
		if($result){
			return true;
		}
		else{
			return false;
		}

}

}
