<?php 
defined('BASEPATH') or exit('No direct script access allowed');

class Buyer extends MY_Controller
{
	public function __construct()
    {
        parent::__construct();
		$this->load->model('TestRequestForm', 'trf');
        $this->load->model('Buyer_field_model', 'buyer');
        $this->check_session();
		$checkUser = $this->session->userdata('user_data');
		$this->user = $checkUser->uidnr_admin;
    }

	// index page
	public function index()

    {
		// $base_url = 'Buyer/index';
		// $config['base_url'] = base_url($base_url);
		//$this->pagination->initialize($config);
		// $config = $this->pagination($base_url, $total_row,10,11);
        $this->load_view('custom_fields/index');
    }

	// ajax
	public function fetch_All()
	{
		$base_url = 'Buyer/index';
		$limit = $this->input->get('limit') ?? 10;
		$page = $this->input->get('page') ?? 1;
		$search = $this->input->get('search') ?? '';

		$offset = ($page - 1) * $limit;
		$config = $this->pagination($base_url, $limit, $page, $search);
		$data = $this->buyer->fetchData($limit, $offset, $search,$config);
		$total = $this->buyer->countAllData($search);

		echo json_encode(['data' => $data,'total' => $total,'page' => $page,'limit' => $limit]);
	}

	// fetch buyer list
    public function fetch_customer_list()
    {
        $key = $this->input->get('key');
        $customer_type = $this->input->get('customer_type');
        $data = $this->trf->get_customer_applicant_name($customer_type, $key);
        echo json_encode($data);
    }

	//store
	public function create()
	{
		$this->load->library('form_validation');
		$this->load->model('buyer_field_model', 'buyer'); // correctly loaded

		$this->form_validation->set_rules('customer_type', 'Customer Type', 'required');
		$this->form_validation->set_rules('buyer_id', 'Applicant', 'required');
		$this->form_validation->set_rules('status', 'Status', 'required');

		$data['customer_type'] = $this->trf->get_factory_name();
		$data['applicant'] = [];
		
		if ($this->form_validation->run() == FALSE) {
			$this->load_view('custom_fields/add_field', $data);
			return;
		}

		$created_on = date('Y-m-d H:i:s');

		$checkUser = $this->session->userdata();
		$user = $this->session->userdata('user_data');
		$created_by = is_array($user) ? ($user['username'] ?? null) : ($user->username ?? null);

		// $checkUser = $this->session->userdata();
		// $created_by = $checkUser['user_data']->uidnr_admin ?? null;

		$buyer_id = $this->input->post('buyer_id');
		if ($this->buyer->check_buyer_exists($buyer_id)) {
			$this->session->set_flashdata('error', 'Buyer/Applicant already exists.');
			redirect('buyer/create');
			return;
		}
		#INSERT MAIN BUYER FIELD
		$buyer_field_data = [
			'buyer_id'       => $buyer_id,
			'customer_type'  => $this->input->post('customer_type'),
			'created_on'     => $created_on,
			'created_by'     => $created_by,
			'status'         => $this->input->post('status')
		];

		$buyer_field_id = $this->buyer->insert_buyer_field($buyer_field_data);

		# INSERT DYNAMIC CUSTOM FIELDS
		$names = $this->input->post('dynamic_field');
		
		$custom_fields = [];
		foreach ($names as $name) {
			if (is_array($name)) {
				$name = implode(', ', $name); 
			}

			$name = trim($name);

			if (!empty($name)) {
				$custom_fields[] = [
					'buyer_field_id'     => $buyer_field_id,
					'custom_field_name'  => $name,
					'created_at'         => $created_on
				];
			}
		}

		if (empty($custom_fields)) {
			$this->session->set_flashdata('error', 'At least one custom field is required.');
			redirect('buyer/create');
			return;
		}

		$inserted = $this->buyer->insert_custom_fields_batch($custom_fields);

		if (!$inserted) {
			$this->session->set_flashdata('error', 'Failed to save custom fields.');
			redirect('buyer/create');
			return;
		}

		// log_message('debug', print_r($custom_fields, true));
		$this->session->set_flashdata('success', 'Buyer field and custom fields saved successfully.');
		redirect('Buyer');
	}

	// Load form
	public function edit_buyer($buyer_field_id)
	{
		$data['buyer_field'] = $this->buyer->get_buyer_field_by_id($buyer_field_id);

		if (!$data['buyer_field']) {
			$this->session->set_flashdata('error', 'Record not found.');
			redirect('Buyer');
		}

		$data['custom_fields'] = $this->buyer->get_custom_fields($buyer_field_id);
		$data['status'] = ['1' => 'Active', '0' => 'Inactive'];
		$customer_type = $data['buyer_field']['customer_type'];
		$customer_id = $data['buyer_field']['buyer_id']; // '363'
		$data['applicant_id'] = $customer_id;
		// $data['applicant'] = $this->buyer->selected_applicant_name($customer_id);
		$data['applicant'] = $this->buyer->get_all_applicants();
		// echo "<pre>";print_r($data);die();
		$this->load_view('custom_fields/edit_field', $data);
	}
	public function update_buyer()
	{
		$buyer_field_id = $this->input->post('buyer_field_id');
		$buyer_id       = $this->input->post('buyer_id');
		$status         = $this->input->post('status');
		$customer_type  = $this->input->post('customer_type');

		$user = $this->session->userdata('user_data');
		$updated_by = is_array($user) ? ($user['username'] ?? null) : ($user->username ?? null);

		// $updated_by = 'admin'; // or use session username
		$updated_on = date('Y-m-d H:i:s');

		// === Update buyer_fields table ===
		$buyer_field_data = [
			'buyer_id'      => $buyer_id,
			'status'        => $status,
			'customer_type' => $customer_type,
			'created_on'    => $updated_on,
			'created_by'    => $updated_by
		];
		$this->buyer->update_buyer_field($buyer_field_id, $buyer_field_data);

		$custom_ids = $this->input->post('custom_field_id'); 
		$custom_fields_assoc = $this->input->post('custom_fields'); 

		if (!empty($custom_ids) && is_array($custom_ids)) {
			foreach ($custom_ids as $custom_id) {
				if (isset($custom_fields_assoc[$custom_id])) {
					$name = trim($custom_fields_assoc[$custom_id]);

					if (!empty($custom_id) && !empty($name)) {
						$this->buyer->update_custom_field($custom_id, [
							'custom_field_name' => $name,
							'updated_at'        => date('Y-m-d H:i:s')
						]);
					}
				} else {
					log_message('error', "Missing custom field name for ID: $custom_id");
				}
			}
		}

		# INSERT DYNAMIC CUSTOM FIELDS
		$names = $this->input->post('dynamic_field');
		$custom_fields = [];
		foreach ($names as $name) {
			if (is_array($name)) {
				$name = implode(', ', $name); 
			}

			$name = trim($name);

			if (!empty($name)) {
				$custom_fields[] = [
					'buyer_field_id'     => $buyer_field_id,
					'custom_field_name'  => $name,
					'created_at'         => $created_on
				];
			}
		}

		if(!empty($custom_fields)){
			$this->buyer->insert_custom_fields_batch($custom_fields);
		}

		$this->session->set_flashdata('success', 'Buyer updated successfully.');
		redirect('Buyer');
	}

	public function delete($buyer_field_id)
	{
		$update_data = ['status' => '2'];
		$result = $this->buyer->SoftDeleteupdate($buyer_field_id, $update_data);

		// echo "<pre>";print_r($result);
		// die();
		if ($result) {
			$this->session->set_flashdata('success', 'Buyer Deleted successfully.');
		} else {
			$this->session->set_flashdata('error', 'Failed to delete buyer.');
		}

		redirect('Buyer');
	}

	public function deletefield()
	{
		$id = $this->input->post('id');

		if (!empty($id) && is_numeric($id)) {
			$this->load->model('buyer_field_model', 'buyer');
			$deleted = $this->buyer->delete_single_custom_field($id);

			if ($deleted) {
				$message = '<div class="alert alert-success">Buyer field deleted successfully.</div>';
				echo json_encode(['status' => 'success', 'html' => $message]);
			} else {
				$message = '<div class="alert alert-danger">Failed to delete the field.</div>';
				echo json_encode(['status' => 'error', 'html' => $message]);
			}
		} else {
			$message = '<div class="alert alert-danger">Invalid field ID.</div>';
			echo json_encode(['status' => 'error', 'html' => $message]);
		}
	}






}
