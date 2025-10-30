<?php
defined('BASEPATH') or exit('No Direct access allowed');

class Mail_configuration extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('MailConfiguration_model','mc');

    }

    public function index()
    {

        $base_url = 'mail_configuration/index';
        $where = array();
        $page_no = $this->uri->segment('4');
        

        if ($this->uri->segment(3) != '' && $this->uri->segment(3) != 'NULL') {
            $data['search'] = $search = urldecode($this->uri->segment(3));
            $base_url .= '/' . $this->uri->segment(3);
        } else {
            $data['search'] = $search = NULL;
            $base_url .= '/NULL';
        }

        
        $total_row = $this->mc->get_mailconfiguration(NULL, NULL, $search, NULL, NULL, $where, '1');
        $config = $this->pagination($base_url, $total_row, 10, 4);

        $data['links'] = $config['links'];
        $data['mail_configuration'] = $this->mc->get_mailconfiguration($config["per_page"], $config['page'], $search, NULL, NULL, $where, NULL);

        $start = (int)$page_no + 1;
        $end = (($data['mail_configuration']) ? count($data['mail_configuration']) : 0) + (($page_no) ? $page_no : 0);
        $data['result_count'] = "Showing " . $start . " - " . $end . " of " . $total_row . " Results";

        $this->load_view('mailconfiguration/list_mailconfiguration',$data);
        
    }

    public function add_mailconfiguration()
    {
        $data['mail_configuration'] = '';
        $data['lablocation'] = $this->mc->get_lab_location(null);
        $data['productdestination'] = $this->mc->get_product_destination(null);
        
        $this->load_view('mailconfiguration/add_mailconfiguration',$data);
    }

    public function save_mailconfiguration()
    {

        $this->form_validation->set_rules('lab_location_id', 'Lab Location', 'required');
        $this->form_validation->set_rules('product_dest_id', 'Product Destination', 'required');
        $this->form_validation->set_rules('c_email', 'Cuctomer Email', 'required');
        $this->form_validation->set_rules('status', 'Status', 'required');
        
       
        if($this->form_validation->run() == true){
            $data_array = array(
                'lab_location_id'    =>  $this->input->post('lab_location_id'),
                'product_destination_id'    =>  $this->input->post('product_dest_id'),
                'c_email'                 => $this->input->post('c_email'),
                'status'                      => $this->input->post('status'),
                'created_by'                  => $this->session->userdata('user_data')->uidnr_admin,
                'created_on'             => date('Y-m-d H:i:s')
            );
           
            $save = $this->mc->insert_data('mail_configuration',$data_array);
            if($save){
                 $this->session->set_flashdata('success', 'Data saved Successfully!');                   
                 redirect('mail_configuration/index');
            } else {
                $this->session->set_flashdata('error', 'Something went wrong!.');

                 redirect('mail_configuration/add_mailconfiguration');
            }
        }else{
                $this->session->set_flashdata('false','Something went wrong!.');
                $data['lablocation'] = $this->mc->get_lab_location(null);
                $data['productdestination'] = $this->mc->get_product_destination(null);
                $this->load_view('mailconfiguration/add_mailconfiguration');
            } 
        
    }

    public function edit_mailconfiguration($id=null)
    {
        $data['mail_configuration'] = $this->mc->get_data_by_id('mail_configuration',$id,'mail_conf_id');
        $data['lablocation'] = $this->mc->get_lab_location(null);
        $data['productdestination'] = $this->mc->get_product_destination(null);
         $this->load_view('mailconfiguration/edit_mailconfiguration',$data);
    }

    
    public function update_mailconfiguration($id=null)
    {

        $this->form_validation->set_rules('lab_location_id', 'Lab Location', 'required');
        $this->form_validation->set_rules('product_dest_id', 'Product Destination', 'required');
        $this->form_validation->set_rules('c_email', 'Cuctomer Email', 'required');
        $this->form_validation->set_rules('status', 'Status', 'required');

        
        if($this->form_validation->run()){
            $data_array = array(
                'lab_location_id'    =>  $this->input->post('lab_location_id'),
                'product_destination_id'    =>  $this->input->post('product_dest_id'),
                'c_email'                 => $this->input->post('c_email'),
                'status'                      => $this->input->post('status'),
                'created_by'                  => $this->session->userdata('user_data')->uidnr_admin,
                'created_on'             => date('Y-m-d H:i:s')
                
                
            );
            $id=$this->input->post('mailconfiguration_id');
            $update = $this->mc->update_data('mail_configuration',$data_array,['mail_conf_id' => $id]);
            if($update){

                 $this->session->set_flashdata('success', 'Data Updated Successfully!');

                 redirect('mail_configuration');
            } else {
               redirect('mail_configuration/edit_mailconfiguration');
            } 
        } else {

           $data['lablocation'] = $this->mc->get_lab_location(null);
           $data['productdestination'] = $this->mc->get_product_destination(null);            
           $this->load_view('mailconfiguration/edit_mailconfiguration');
        }
    }

      
    public function delete_mailconfiguration()
    {

        $id = $this->input->get('id');
        $delete = $this->mc->delete_mailconfiguration($id);
        if ($delete) {
            
                 redirect('mail_configuration');
            
        } 
    }

    
}
?>