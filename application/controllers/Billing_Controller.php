<?php

class Billing_Controller extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Billing_model', 'bm');
		$this->check_session();
		$checkUser = $this->session->userdata('user_data');
		$this->user = $checkUser->uidnr_admin;
		$this->form_validation->set_error_delimiters('<div class="error" style="color:red">', '</div>');
	}

	public function index($page = 0, $trf = null, $customer_name = null, $product = null, $created_on = null,  $gc_number = null, $buyer = null,  $status = null, $pi = null,  $start_date = null, $end_date = null, $due_date = null)
	{
		$checkUser = $this->session->userdata('user_data');
		$cust_where = NULL;
		$buyer_where = ['customer_type' => 'Buyer'];

		if (exist_val('Branch/Wise', $this->session->userdata('permission'))) {
			$multibranch = $this->session->userdata('branch_ids');
			$buyer_where['mst_branch_id IN (' . $multibranch . ') '] = null;
			$cust_where['mst_branch_id IN (' . $multibranch . ') '] = null;
		}
		$data['customer'] = $this->bm->get_result("customer_id,CONCAT(customer_name,' (Address - ',address,')') as customer_name", "cust_customers", $cust_where);
		$data['buyer'] = $this->bm->get_result("customer_id,customer_name", "cust_customers", $buyer_where);
		$data['products'] = $this->bm->get_products();
		$data['sample_status'] = $this->bm->get_status();
		$data['division'] = $this->bm->get_fields("mst_divisions", "division_id,division_name");
		$per_page = "10";
		$page = $this->uri->segment(3);
		if ($page != 0) {
			$page = ($page - 1) * $per_page;
		} else {
			$page = 0;
		}

		$total_count = $this->bm->get_billing_list($per_page, $page, $trf, $customer_name, $product, $created_on, $gc_number, $buyer, $status, $pi,  $start_date, $end_date, $due_date, $count = true);
		$config['full_tag_open']    = '<div class="pagging text-center"><nav><ul class="pagination">';
		$config['full_tag_close']   = '</ul></nav></div>';
		$config['num_tag_open']     = '<li class="page-item"><span class="page-link">';
		$config['num_tag_close']    = '</span></li>';
		$config['cur_tag_open']     = '<li class="page-item active"><span class="page-link">';
		$config['cur_tag_close']    = '<span class="sr-only">(current)</span></span></li>';
		$config['next_tag_open']    = '<li class="page-item"><span class="page-link">';
		$config['next_tag_close']  = '<span aria-hidden="true"></span></span></li>';
		$config['prev_tag_open']    = '<li class="page-item"><span class="page-link">';
		$config['prev_tag_close']  = '</span></li>';
		$config['first_tag_open']   = '<li class="page-item"><span class="page-link">';
		$config['first_tag_close'] = '</span></li>';
		$config['last_tag_open']    = '<li class="page-item"><span class="page-link">';
		$config['last_tag_close']  = '</span></li>';
		$config['base_url'] = base_url() . "Billing_Controller/index";
		$config['use_page_numbers'] = TRUE;
		$config['uri_segment'] = 3;
		$config['total_rows'] = $total_count;
		$config['per_page'] = $per_page;
		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();
		$data['billing_list'] = $this->bm->get_billing_list($per_page, $page, $trf,  $customer_name, $product, $created_on,  $gc_number, $buyer, $status,  $pi,   $start_date, $end_date, $due_date);

		// HARSH CODE 30-05-2022
		$this->bm->get_billing_list(NULL, NULL, $trf, $customer_name, $product, $created_on, $gc_number, $buyer, $status, $pi, $start_date, $end_date, $due_date, $count = true);
		$this->session->set_userdata('excel_query', $this->db->last_query());
		// END

		if ($total_count > 0) {
			$start = (int)$page + 1;
		} else {
			$start = 0;
		}
		$end = (($data['billing_list']) ? count($data['billing_list']) : 0) + (($page) ? $page : 0);
		$data['result_count'] = "Showing " . $start . " - " . $end . " of " . $total_count . " Results";
		//echo "<pre>"; print_r($data['billing_list']); die;

		$this->load_view('billing_list', $data);
	}

	// Get sample log
	public function get_billing_log()
	{
		$sample_id = $this->input->post('sample_id');
		$data = $this->bm->get_billing_log($sample_id);
		echo json_encode($data);
	}

	public function download_report()
	{
		$sample_reg_id = $this->input->post('sample_id');
		$this->db->select('report_num , manual_report_file, sample_reg_id,report_id');
		$this->db->from("generated_reports");
		$this->db->where('sample_reg_id', $sample_reg_id);
		$query = $this->db->get();
		//print_r($this->db->last_query());die;
		echo json_encode($query->result_array());
	}

	public function download_proforma()
	{
		$proforma_invoice_id = $this->input->get('pro_id');

		$path = $this->bm->download_proforma($proforma_invoice_id);
		if ($path) {
			if ($path->file_path) {
				$this->load->helper('download');
				$pdf_path    =   file_get_contents($path->file_path);
				$pdf_name    =   (!empty($path->original_file_name)) ? (basename($path->original_file_name)) : (basename($path->file_path));

				$logdetails = array(
					'module'    => 'invoice',
					'operation' => 'download proforma',
					'source_module' => 'Billing_Controller',
					'uidnr_admin'   => $this->admin_id(),
					'log_activity_on'   => date('Y-m-d H:i:s'),
					'invoice_id'     => $proforma_invoice_id
				);
				$this->db->insert('invoice_activity_log', $logdetails);
				force_download($pdf_name, $pdf_path);
			} else {
				echo '<h1>NO RECORD FOUND</h1>';
			}
		} else {
			echo '<h1>“This pdf stands cancelled. Please do not transact based on this cancelled pdf. Geo Chem will not be liable for any issues, financial, legal or otherwise, based on using this cancelled pdf for any purpose.</h1>';
		}
	}

	public function download_report_pdf()
	{
		$post = $this->input->get();
		//print_r($post);die;
		$sample_reg_id = $post['sample_id'];
		$report_id = $post['report_id'];
		$path = $this->bm->download_report_pdf($sample_reg_id, $report_id);
		if ($path) {
			if ($path->manual_report_file) {
				$this->load->helper('download');
				$pdf_path    =   file_get_contents($path->manual_report_file);
				$pdf_name    =   ($path->original_file_name != '') ? (basename($path->original_file_name)) : (basename($path->manual_report_file));

				$logdetails = array(
					'module'    => 'Samples',
					'operation' => 'download report pdf',
					'source_module' => 'Billing_Controller',
					'uidnr_admin'   => $this->admin_id(),
					'log_activity_on'   => date('Y-m-d H:i:s'),
					'sample_reg_id'     => $sample_reg_id
				);
				$this->db->insert('sample_reg_activity_log', $logdetails);
				force_download($pdf_name, $pdf_path);
			} else {
				echo '<h1>NO RECORD FOUND</h1>';
			}
		} else {
			echo '<h1>“This pdf stands cancelled. Please do not transact based on this cancelled pdf. Geo Chem will not be liable for any issues, financial, legal or otherwise, based on using this cancelled pdf for any purpose.</h1>';
		}
	}

	public function Upload_invoice()
	{
		$id = $this->uri->segment(4);
		$get = $this->bm->get_invoices($id);
		//print_r($get);die;
		$data = array(
			'proforma_invoice_id' =>  $get['proforma_invoice_id'],
			'generated_by' =>  $get['created_by'],
		);
		$result = $this->db->insert('Invoices', $data);

		if ($result) {
			$this->session->set_flashdata('success', 'SUCCESSFULLY INSERT MANUAL INVOICE.');
			$msg = array('status' => 1, 'msg' => 'SUCCESSFULLY INSERT MANUAL INVOICE');
			redirect('Billing_Controller');
		} else {
			$msg = array('status' => 0, 'msg' => 'ERROR WHILE INSERT MANUAL INVOICE');
			redirect('Billing_Controller');
		}
		//echo $this->db->last_query(); die;
	}

	// Added by CHANDAN --01-08-2022
	public function sync_invoice_details_table()
	{
		set_time_limit(0);
		ini_set("memory_limit", -1);

		$data = $this->bm->sync_invoice_details_table(); 
		
		if ($data) {
			foreach ($data as $key => $val) {
				if (!empty($val->proforma_invoice_id)) {
					$inData = array(
						'customer_id' 			=> $val->customer_id,
						'proforma_id' 			=> $val->proforma_invoice_id,
						'invoice_id' 			=> $val->invoiced_id,
						'sample_reg_id' 		=> $val->sample_reg_id,
						'modify_invoice_flag'	=> 3,
						'invoice_type' 			=> (!empty($val->invoice_quote_type)) ? $val->invoice_quote_type : 0,
						'parameter_name' 		=> $val->dynamic_heading,
						'rate' 					=> (!empty($val->dynamic_value)) ? $val->dynamic_value : 0,
						'quantity' 				=> (!empty($val->quantity)) ? $val->quantity : 0,
						'discount' 				=> (!empty($val->discount)) ? $val->discount : 0,
						'price' 				=> (!empty($val->applicable_charge)) ? $val->applicable_charge : 0,
						'created_by' 			=> $this->session->userdata('user_data')->uidnr_admin,
						'created_on'			=> date('Y-m-d H:i:s')
					);
					$run = $this->bm->insert_data('invoice_details', $inData);
					
					if ($run && !empty($val->uploadfilepath)) {
						$this->bm->update_data('invoice_proforma', ['is_invoice_generated' => 1], ['proforma_invoice_id' => $val->proforma_invoice_id]);

						$invUpdate = array(
							'tax_status' 		=> 'TAX INVOICE UPDATED',
							'invoice_type' 		=> 'Manual',
							'invoice_pdf_path' 	=> (!empty($val->uploadfilepath)) ? $val->uploadfilepath : NULL, 
						);
						$this->bm->update_data('Invoices', $invUpdate, ['invoiced_id' => $val->invoiced_id]);
					}
				}
			}
			echo 'Done...';
		}
	}

	public function fetch_all_gc_number()
	{
		echo json_encode($this->bm->fetch_all_gc_number($this->input->post('customer_id')));
	}

	public function fetch_parameters_details()
	{
		set_time_limit(0);
		ini_set("memory_limit", -1);

		$proforma = $this->input->post('proforma_invoice_id');
		if (count($proforma) > 0) {
			$html1 = '<div class="card shadow-none mb-3">
				<div class="card-header text-center bg-info">
					<button type="button" class="btn btn-primary btn-sm" id="add_btn_parameters">ADD PARAMETERS</button>
				</div>
				<div class="card-body bg-light">
					<table class="table table-sm table-bordered mb-3" id="add_row_parameters">
						<thead>
							<tr class="table-primary">
								<th width="65%">PARAMETERS</th>
								<th width="10%" class="text-center">RATE</th>
								<th width="5%" class="text-center">QTY</th>
								<th width="5%" class="text-center">DISCOUNT</th>
								<th width="10%" class="text-center">PRICE</th>
								<th width="5%" class="text-center">DELETE</th>
							</tr>
						</thead>
						<tbody class="bg-white">
						</tbody>
					</table>
				</div>
			</div>';

			foreach ($proforma as $key => $val) {
				$opentrf 	= $this->bm->get_open_trf($val);
				$test 		= $this->bm->get_test($val);
				$package 	= $this->bm->get_package($val);
				$protocol 	= $this->bm->get_protocol($val);
				$gcNumber 	= $this->bm->fetch_gc_number($val);
				$gc_no					= (isset($gcNumber->gc_no) && !empty($gcNumber->gc_no)) ? $gcNumber->gc_no : NULL;
				$sample_reg_id 			= (isset($gcNumber->sample_reg_id) && !empty($gcNumber->sample_reg_id)) ? $gcNumber->sample_reg_id : NULL;
				$proforma_invoice_id 	= (isset($gcNumber->proforma_invoice_id) && !empty($gcNumber->proforma_invoice_id)) ? $gcNumber->proforma_invoice_id : NULL;

				if (!empty($gc_no) && !empty($sample_reg_id) && !empty($proforma_invoice_id)) {

					$html1 .= '<div class="card shadow-none">
  						<div class="card-header text-center bg-info"><b>' . $gc_no . '</b></div>
  						<div class="card-body bg-light">';

					if (!empty($opentrf)) {
						$html1 .= '<table class="table table-sm table-bordered mb-3">
						<thead>
							<tr class="table-primary">
								<th width="5%" class="text-center">SL</th>
								<th width="65%">PARAMETERS (OPEN TRF)</th>
								<th width="10%" class="text-center">RATE</th>
								<th width="5%" class="text-center">QTY</th>
								<th width="5%" class="text-center">DISCOUNT</th>
								<th width="10%" class="text-center">PRICE</th>
							</tr>
						</thead>
						<tbody class="bg-white">';
						foreach ($opentrf as $key0 => $val0) {
							$html1 .= '<tr>
								<input type="hidden" class="form-control form-control-sm" name="sample_reg_id[]" value="' . $sample_reg_id . '" />
								<input type="hidden" class="form-control form-control-sm" name="proforma_id[]" value="' . $proforma_invoice_id . '" />
								<input type="hidden" class="form-control form-control-sm" name="invoice_type[]" value="" />';
							$html1 .= '<td class="text-center">' . ($key0 + 1) . '</td>';
							$html1 .= '<td>
								<input type="text" class="form-control form-control-sm isEmptyParameter" name="parameter_name[]" value="' . $val0["dynamic_heading"] . '" />
							</td>';
							$html1 .= '<td>
								<input type="text" class="form-control form-control-sm text-center calPrice" name="rate[]" id="opentrf_rate_' . $gc_no . '_' . $key0 . '" value="' . round($val0["dynamic_value"], 2) . '" />
							</td>';
							$html1 .= '<td>
								<input type="text" class="form-control form-control-sm text-center calPrice" name="quantity[]" id="opentrf_qty_' . $gc_no . '_' . $key0 . '" value="' . $val0["quantity"] . '" />
							</td>';
							$html1 .= '<td>
								<input type="text" class="form-control form-control-sm text-center calPrice" name="discount[]" id="opentrf_dis_' . $gc_no . '_' . $key0 . '" value="' . $val0["discount"] . '" />
							</td>';
							$html1 .= '<td>
								<input type="text" class="form-control form-control-sm text-center" name="price[]" id="opentrf_price_' . $gc_no . '_' . $key0 . '" value="' . round($val0["applicable_charge"], 2) . '" readonly />
							</td>';
							$html1 .= '</tr>';
						}
						$html1 .= '</tbody></table>';
					}

					if (!empty($test)) {
						$html1 .= '<table class="table table-sm table-bordered mb-3">
						<thead>
							<tr class="table-primary">
								<th width="5%" class="text-center">SL</th>
								<th width="65%">PARAMETERS (TEST)</th>
								<th width="10%" class="text-center">RATE</th>
								<th width="5%" class="text-center">QTY</th>
								<th width="5%" class="text-center">DISCOUNT</th>
								<th width="10%" class="text-center">PRICE</th>
							</tr>
						</thead>
						<tbody class="bg-white">';
						foreach ($test as $key1 => $val1) {
							$html1 .= '<tr>
								<input type="hidden" class="form-control form-control-sm" name="sample_reg_id[]" value="' . $sample_reg_id . '" />
								<input type="hidden" class="form-control form-control-sm" name="proforma_id[]" value="' . $proforma_invoice_id . '" />
								<input type="hidden" class="form-control form-control-sm" name="invoice_type[]" value="Test" />';
							$html1 .= '<td class="text-center">' . ($key1 + 1) . '</td>';
							$html1 .= '<td>
								<input type="text" class="form-control form-control-sm isEmptyParameter" name="parameter_name[]" value="' . $val1["dynamic_heading"] . '" />
							</td>';
							$html1 .= '<td>
								<input type="text" class="form-control form-control-sm text-center calPrice" name="rate[]" id="test_rate_' . $gc_no . '_' . $key1 . '" value="' . round($val1["dynamic_value"], 2) . '" />
							</td>';
							$html1 .= '<td>
								<input type="text" class="form-control form-control-sm text-center calPrice" name="quantity[]" id="test_qty_' . $gc_no . '_' . $key1 . '" value="' . $val1["quantity"] . '" />
							</td>';
							$html1 .= '<td>
								<input type="text" class="form-control form-control-sm text-center calPrice" name="discount[]" id="test_dis_' . $gc_no . '_' . $key1 . '" value="' . $val1["discount"] . '" />
							</td>';
							$html1 .= '<td>
								<input type="text" class="form-control form-control-sm text-center" name="price[]" id="test_price_' . $gc_no . '_' . $key1 . '" value="' . round($val1["applicable_charge"], 2) . '" readonly />
							</td>';
							$html1 .= '</tr>';
						}
						$html1 .= '</tbody></table>';
					}

					if (!empty($package)) {
						$html1 .= '<table class="table table-sm table-bordered mb-3">
						<thead>
							<tr class="table-primary">
								<th width="5%" class="text-center">SL</th>
								<th width="65%">PARAMETERS (PACKAGE)</th>
								<th width="10%" class="text-center">RATE</th>
								<th width="5%" class="text-center">QTY</th>
								<th width="5%" class="text-center">DISCOUNT</th>
								<th width="10%" class="text-center">PRICE</th>
							</tr>
						</thead>
						<tbody class="bg-white">';
						foreach ($package as $key2 => $val2) {
							$html1 .= '<tr>
								<input type="hidden" class="form-control form-control-sm" name="sample_reg_id[]" value="' . $sample_reg_id . '" />
								<input type="hidden" class="form-control form-control-sm" name="proforma_id[]" value="' . $proforma_invoice_id . '" />
								<input type="hidden" class="form-control form-control-sm" name="invoice_type[]" value="Package" />';
							$html1 .= '<td  class="text-center">' . ($key2 + 1) . '</td>';
							$html1 .= '<td><input type="text" class="form-control form-control-sm isEmptyParameter" name="parameter_name[]" value="' . $val2["dynamic_heading"] . '" /></td>';
							$html1 .= '<td>
								<input type="text" class="form-control form-control-sm text-center calPrice" name="rate[]" id="package_rate_' . $gc_no . '_' . $key2 . '" value="' . round($val2["dynamic_value"], 2) . '" />
							</td>';
							$html1 .= '<td>
								<input type="text" class="form-control form-control-sm text-center calPrice" name="quantity[]" id="package_qty_' . $gc_no . '_' . $key2 . '" value="' . $val2["quantity"] . '" />
							</td>';
							$html1 .= '<td>
								<input type="text" class="form-control form-control-sm text-center calPrice" name="discount[]" id="package_dis_' . $gc_no . '_' . $key2 . '" value="' . $val2["discount"] . '" />
							</td>';
							$html1 .= '<td>
								<input type="text" class="form-control form-control-sm text-center" name="price[]" id="package_price_' . $gc_no . '_' . $key2 . '" value="' . round($val2["applicable_charge"], 2) . '" readonly />
							</td>';
							$html1 .= '</tr>';
						}
						$html1 .= '</tbody></table>';
					}

					if (!empty($protocol)) {
						$html1 .= '<table class="table table-sm table-bordered">
						<thead>
							<tr class="table-primary">
								<th width="5%" class="text-center">SL</th>
								<th width="65%">PARAMETERS (PROTOCOL)</th>
								<th width="10%" class="text-center">RATE</th>
								<th width="5%" class="text-center">QTY</th>
								<th width="5%" class="text-center">DISCOUNT</th>
								<th width="10%" class="text-center">PRICE</th>
							</tr>
						</thead>
						<tbody class="bg-white">';
						foreach ($protocol as $key3 => $val3) {
							$html1 .= '<tr>
								<input type="hidden" class="form-control form-control-sm" name="sample_reg_id[]" value="' . $sample_reg_id . '" />
								<input type="hidden" class="form-control form-control-sm" name="proforma_id[]" value="' . $proforma_invoice_id . '" />
								<input type="hidden" class="form-control form-control-sm" name="invoice_type[]" value="Protocol" />';
							$html1 .= '<td class="text-center">' . ($key3 + 1) . '</td>';
							$html1 .= '<td>
								<input type="text" class="form-control form-control-sm isEmptyParameter" name="parameter_name[]" value="' . $val3["dynamic_heading"] . '" />
							</td>';
							$html1 .= '<td>
								<input type="text" class="form-control form-control-sm text-center calPrice" name="rate[]" id="protocol_rate_' . $gc_no . '_' . $key3 . '" value="' . round($val3["dynamic_value"], 2) . '" />
							</td>';
							$html1 .= '<td>
								<input type="text" class="form-control form-control-sm text-center calPrice" name="quantity[]" id="protocol_qty_' . $gc_no . '_' . $key3 . '" value="' . $val3["quantity"] . '" />
							</td>';
							$html1 .= '<td>
								<input type="text" class="form-control form-control-sm text-center calPrice" name="discount[]" id="protocol_dis_' . $gc_no . '_' . $key3 . '" value="' . $val3["discount"] . '" />
							</td>';
							$html1 .= '<td>
								<input type="text" class="form-control form-control-sm text-center" name="price[]" id="protocol_price_' . $gc_no . '_' . $key3 . '" value="' . round($val3["applicable_charge"], 2) . '" readonly />
							</td>';
							$html1 .= '</tr>';
						}
						$html1 .= '</tbody></table>';
					}
					$html1 .= '</div></div>';
				}
			}
			echo $html1;
		}
	}

	function cleanInvoiceData($sample_reg_id){

		$sample_arr_data=array_unique($sample_reg_id);
		foreach($sample_arr_data as $reg_id){
				$this->db->select('distinct(invoice_id)')->from('invoice_details');
				$this->db->where('sample_reg_id',$reg_id);
				$res=$this->db->get();
				//echo $this->db->last_query(); exit;
				if($res->num_rows()>0){
				$result=$res->result_array();
				// echo "<pre>";
				// print_r($result);
				// //exit;
					foreach($result as $row){
						$sql="delete from Invoices where invoiced_id=".$row['invoice_id'];
						$this->db->query($sql);
					}
				
				}
				$sql="delete from invoice_details where sample_reg_id=".$reg_id;
				$this->db->query($sql);
				
		}

	}

	public function process_generate_invoice()
	{
		set_time_limit(0);
		ini_set("memory_limit", -1);

		$post = $this->input->post();
		// echo "<pre>";
		// print_r($post);
		$customer_id = $post['customer_id'];

		if (!empty($customer_id) && !empty($post['sample_reg_id'])) {

			$insertedData = 0;
			$this->cleanInvoiceData($post['sample_reg_id']);


			$invoice_type_flag = (count(array_filter(array_unique($post['sample_reg_id']))) > 1) ? 'Multiple' : 'Single';

			$invoicesData = array(
				'generated_date' 		=> date('Y-m-d H:i:s'),
				'proforma_invoice_id'	=> 0,
				'status' 				=> 'Report Generated',
				'report_generated_by'	=> $this->session->userdata('user_data')->uidnr_admin,
				'invoice_customer_id'	=> $customer_id,
				'invoice_type_flag'		=> $invoice_type_flag,
				'tax_status'			=> 'DRAFT'
			);

			$invoiced_id = $this->bm->insert_data('Invoices', $invoicesData);

			if (!empty($invoiced_id)) {

				for ($i = 0; $i < count($post['sample_reg_id']); $i++) {

					if (!empty($post['parameter_name'][$i])) {
						$dyData = array(
							'customer_id' 		=> $customer_id,
							'proforma_id' 		=> $post['proforma_id'][$i],
							'invoice_id' 		=> $invoiced_id,
							'sample_reg_id' 	=> $post['sample_reg_id'][$i],
							'modify_invoice_flag' => 1,
							'invoice_type' 		=> $post['invoice_type'][$i],
							'parameter_name' 	=> $post['parameter_name'][$i],
							'rate' 				=> $post['rate'][$i],
							'quantity' 			=> $post['quantity'][$i],
							'discount' 			=> $post['discount'][$i],
							'price' 			=> $post['price'][$i],
							'created_by' 		=> $this->session->userdata('user_data')->uidnr_admin,
							'created_on' 		=> date('Y-m-d H:i:s')
						);
						// echo "<pre>";
						// print_r($dyData);
						$run = $this->bm->insert_data('invoice_details', $dyData);
					
						if ($run) {
							$insertedData++;

							$this->bm->update_data('invoice_proforma', ['is_invoice_generated' => 1], ['proforma_invoice_id' => $post['proforma_id'][$i]]);
						}
					}
				}
				//exit;
				$response = array(
					'message'   => $insertedData . ' Records Inserted!!',
					'code'      => 1
				);
			} else {
				$response = array(
					'message'   => 'Something went wrong!',
					'code'      => 0
				);
			}
		} else {
			$response = array(
				'message'   => 'Customer is missing!',
				'code'      => 0
			);
		}
		echo json_encode($response);
	}
   function sanitizeTestName($parameter_name){

	$parameter_name=str_replace("(","-",$parameter_name);
	$parameter_name=str_replace(")","-",$parameter_name);
	return $parameter_name;
   }
	public function sent_on_erp()
	{
		$cust_id = $this->input->post('cust_id');
		$inv_id = $this->input->post('inv_id');

		if (!empty($cust_id) && !empty($inv_id)) {

			$gc_no = $this->bm->fetch_distinct_gc_no($cust_id, $inv_id);
			// echo "<pre>";
			// print_r($gc_no);
			// exit;
			// added by kamal on 22th sep 2022
			$newData=$this->bm->get_NewData($cust_id);
			// echo "<pre>"; print_r($newData); die;
			$gcNumber = (isset($gc_no->gc_no) && !empty($gc_no->gc_no)) ? $gc_no->gc_no : "";
			$nav_customer_code = (isset($gc_no->nav_customer_code) && !empty($gc_no->nav_customer_code)) ? $gc_no->nav_customer_code : NULL;

			if (!empty($nav_customer_code)) {

				$ModifyInv = $this->bm->get_row('tax_status,erp_invoice_no', 'Invoices', ['invoiced_id' => $inv_id]);
				// echo "<pre>";
				// print_r($ModifyInv);
				// exit;
				if(isset($ModifyInv->erp_invoice_no) && $ModifyInv->erp_invoice_no != NULL){
					$ModifyInvoice =  true ;
				} else {
					$ModifyInvoice =  false;
				}
				$data = $this->bm->get_invoice_details_data($cust_id, $inv_id);

				$dynamicData = array();
				foreach ($data as $key => $val) {

					$parameter_name=$this->sanitizeTestName($val->parameter_name);

					if(strlen($parameter_name)>50){
						$parameter_name1=substr($parameter_name,0,50);
						$parameter_name2=substr($parameter_name,50,strlen($parameter_name));
					} else {
						$parameter_name1=$parameter_name;
						$parameter_name2="";
					}

					$newArr = array(
						"ItemDescription" 	=> trim($parameter_name1),
						"Description 2" 	=> $parameter_name2,
						"Qty" 				=> $val->quantity,
						"Price" 			=> $val->rate,
						"WorkDoneBy"	=> "GGN",
						"DiscountinP"	=> $val->discount,
					);
					array_push($dynamicData, $newArr);
				}
				
				if (!empty($dynamicData)) {
					$postData = array(
						"PHPPrimaryNo" 		=> $inv_id,
						"LocationCode" 		=> "MUM",
						'InvoiceNo'  =>		isset($ModifyInv->erp_invoice_no)?$ModifyInv->erp_invoice_no:'',
						"CustomerNo" 		=> $nav_customer_code,
						"CertificateNo" 	=> $gcNumber,
						"CertificateDate" 	=> date("Y-m-d"),
						"ModifyInvoice" 	=> $ModifyInvoice,
						"DivisionCode "	=> (!empty($newData->erpdivision_code)?$newData->erpdivision_code:""),
						"SalesInoviceLine"	=> $dynamicData 
					);
					// $post_data['DivisionCode']=$newData->erpdivision_code;
					// $post_data['WorkDoneBy']=$newData->sales_person_name;

					// echo "<pre>";
					// print_r($postData); die;	
					$post_data = json_encode($postData);
					
					$url = ERP_SALES_INVOICE_URL;

					$ch = curl_init($url);
					curl_setopt($ch, CURLOPT_URL, $url);
					curl_setopt($ch, CURLOPT_POST, true);
					curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
					// curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
					// curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
					curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
					curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
					curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
					$resp = json_decode(curl_exec($ch));
					$resNew = curl_exec($ch);
					$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
					curl_close($ch);
					
					//echo "<pre>";
					///print_r($post_data);				
					// print_r($dynamicData);				
					// print_r($resp); 
					//exit;

					if ($resp->Status && $resp->Status_code == 'SUCCESS') {

						
						$this->bm->update_data('invoice_details', ['modify_invoice_flag' => 2], ['invoice_id' => $inv_id]);

						$this->bm->update_data('Invoices', ['tax_status' => 'SENT ON ERP','erp_invoice_no'=>$resp->InvoiceNo], ['invoiced_id' => $inv_id]);

						$response = array(
							'message'   => 'Data has been sent on SENT ON ERP!!',
							'code'      => 1
						);
					} else {
					
						send_mail_function("developer.cps04@basilrl.com", NULL, "shankar.k@basilrl.com","status: ".$httpCode."<br>".$resNew, "Gurgaon Lims ERP API ISSUE for Basil Report NO.".$gcNumber."And Invoice Id:".$inv_id, NULL, NULL);
						$response = array(
							'message'   => $resp->Messsage,
							'code'      => 0
						);
					}
				}
			} else {
				$response = array(
					'message'   => 'GC_NO or NAV_CUSTOMER_CODE is missing!',
					'code'      => 0
				);
			}
		} else {
			$response = array(
				'message'   => 'CUSTOMER_ID or INVOICE_ID is missing!',
				'code'      => 0
			);
		}
		echo json_encode($response);
	}
}
