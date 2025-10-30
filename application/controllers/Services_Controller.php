<?php
class Services_Controller extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->check_session();
        $this->load->model('Service_model','sm');
    }   

    public function index($page = 0,$lab_location = NULL,$product_destionation=NULL,$test_standard=NULL,$certificate=NULL)
    {
        $per_page = "10";
        if ($page != 0) {
            $page = ($page - 1) * $per_page;
        } else {
            $page = 0;
        }
        $total_count = $this->sm->get_services_list($per_page,$page,$lab_location,$product_destionation,$test_standard,$certificate,true);
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
        $config['base_url'] = base_url() . "Services_Controller/index";
        $config['use_page_numbers'] = TRUE;
        $config['uri_segment'] = 3;
        $config['total_rows'] = $total_count;
        $config['per_page'] = $per_page;
        $this->pagination->initialize($config);
        $data['pagination'] = $this->pagination->create_links();
        $data['lab_location'] = $this->sm->get_lab_location(NULL); 
        $data['product_destination'] = $this->sm->get_product_destination(NULL); 
        $data['certificate'] = $this->sm->get_certificate(NULL);
        $data['test_standard'] = $this->sm->get_test_standards(NULL);
        $data['services'] = $this->sm->get_services_list($per_page,$page,$lab_location,$product_destionation,$test_standard,$certificate);
        // echo "<pre>"; print_r($data); die;
        $this->load_view('services/services_list',$data);
    }

    public function add_services()
    {
        if($this->input->post()){
            $this->form_validation->set_rules('lab_location','Lab Location','required');
            $this->form_validation->set_rules('product_destination','Product Destination','required');
            $this->form_validation->set_rules('test_standards','Test standards','required');
            $this->form_validation->set_rules('status','Status','required');

            if($this->form_validation->run()){
                $test_standards = $this->input->post('test_standards');
                // foreach($test_standards as $ts){
                    $data_array = array(
                        'lab_location_id'           => $this->input->post('lab_location'),
                        'product_destination_id'    => $this->input->post('product_destination'),
                        'created_on'                => date('Y-m-d H:i:s'),
                        'created_by'                => $this->admin_id(),
                        'test_standard_id'          => $test_standards,
                        'status'                    => $this->input->post('status'),
                        'certificate_id'            => $this->input->post('certification')
                    );

                    $save = $this->sm->insert_data('tbl_services',$data_array);
                // }
                if($save){
                    $log_deatils = array(
                        'text'          => "Added services",
                        'created_by'    => $this->admin_id(),
                        'created_on'    => date('Y-m-d H:i:s'),
                        'record_id'     => $save,
                        'source_module' => 'Services_Controller',
                        'action_taken'  => 'add_services'
                    );
    
                    $log = $this->sm->insert_data('user_log_history',$log_deatils);
                    echo json_encode(['status' => 1, 'message' => 'Data saved successfully.']);
                exit;
                } else {
                    echo json_encode(['status' => 0, 'message' => 'Something went wrong!.']);
                exit;
                }
            } else {
                echo json_encode(['status' => 0, 'message' => 'Something went wrong!.','error' => $this->form_validation->error_array()]);
                exit;
            }
        }
        $this->load_view('services/add_services');
    }

    public function edit_services($id)
    {
        $data['service'] = $this->sm->get_service_data($id);
        if($this->input->post()){
            $this->form_validation->set_rules('lab_location','Lab Location','required');
            $this->form_validation->set_rules('product_destination','Product Destination','required');
            $this->form_validation->set_rules('test_standards','Test standards','required');
            $this->form_validation->set_rules('status','Status','required');

            if($this->form_validation->run()){
                $test_standards = $this->input->post('test_standards');
                // foreach($test_standards as $ts){
                    $data_array = array(
                        'lab_location_id'           => $this->input->post('lab_location'),
                        'product_destination_id'    => $this->input->post('product_destination'),
                        'created_on'                => date('Y-m-d H:i:s'),
                        'created_by'                => $this->admin_id(),
                        'test_standard_id'          => $test_standards,
                        'status'                    => $this->input->post('status'),
                        'certificate_id'            => $this->input->post('certification')
                    );

                    $save = $this->sm->update_data('tbl_services',$data_array,['services_id' => $id]);
                // }
                if($save){
                    $log_deatils = array(
                        'text'          => "Updated services",
                        'created_by'    => $this->admin_id(),
                        'created_on'    => date('Y-m-d H:i:s'),
                        'record_id'     => $id,
                        'source_module' => 'Services_Controller',
                        'action_taken'  => 'edit_services'
                    );
    
                    $log = $this->sm->insert_data('user_log_history',$log_deatils);
                    echo json_encode(['status' => 1, 'message' => 'Data updated successfully.']);
                exit;
                } else {
                    echo json_encode(['status' => 0, 'message' => 'Something went wrong!.']);
                exit;
                }
            } else {
                echo json_encode(['status' => 0, 'message' => 'Something went wrong!.','error' => $this->form_validation->error_array()]);
                exit;
            }
        }
        $this->load_view('services/add_services',$data);
    }

    public function get_lab_location()
    {
        $key = ($this->input->get('key'))?$this->input->get('key'):NULL;
        $data = $this->sm->get_lab_location($key);
        echo json_encode($data);
    }

    public function get_product_destination()
    {
        $key = ($this->input->get('key'))?$this->input->get('key'):NULL;
        $data = $this->sm->get_product_destination($key);
        echo json_encode($data);
    }

    public function get_test_standard()
    {
        $key = ($this->input->get('key'))?$this->input->get('key'):NULL;
        $data = $this->sm->get_test_standards($key);
        echo json_encode($data);
    }

    public function get_certificate()
    {
        $key = ($this->input->get('key'))?$this->input->get('key'):NULL;
        $data = $this->sm->get_certificate($key);
        echo json_encode($data);
    }
    
    public function get_services_log()
	{
		$services_id = $this->input->post('services_id');
		$data = $this->sm->get_services_log($services_id);
		echo json_encode($data);
	}
}

?>