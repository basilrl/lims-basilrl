<?php

defined('BASEPATH') or exit('No direct access allowed');

class CVG_Storage extends MY_Controller{
    
      public function __construct() {
        parent::__construct();
        $this->check_session();
       
        $this->load->model('CVG_Storage_model', 'cvg');
        $this->form_validation->set_error_delimiters('<div class="error" style="color:red">', '</div>');
        $checkUser = $this->session->userdata('user_data');
		$this->user = $checkUser->uidnr_admin;
    }
    
    public function index(){
        
      $base_url = 'CVG_Storage/index';
      $where = array();
       
       if ($this->uri->segment(3) != '' && $this->uri->segment(3) != 'NULL') {
            $data['search'] = $search = $this->uri->segment(3);
             $base_url  .= '/' . $this->uri->segment(3);
        } else {
            $data['search'] = $search = NULL;
            $base_url  .= '/NULL';
        } 
      
        $data['result_count'] =  $total_row = $this->cvg->cvg_storage_list(NULL, NULL, $search, NULL, NULL, $where, '1');
        $config =  $this->pagination($base_url, $total_row, 10, 4);
        
        $data['links'] = $config['links'];
        $data['country'] = $this->cvg->fetch_country();
        $data['accredited_by'] = $this->cvg->get_accredited_by();
        $data['listing'] = $this->cvg->cvg_storage_list($config["per_page"], $config['page'], $search, NULL, NULL, $where, NULL);

        if ($order = NULL) {
            $data['order'] = $order = NULL ? "DESC" : "ASC";
        } else {
            $data['order'] = $order = "ASC" ? "DESC" : "ASC";
        }

        if ($order = "ASC") {
            $data['order'] = "DESC";
        } else {
            $data['order'] = "ASC";
        }
       $this->load_view('cvg/cvg_storage', $data);  
    }
    
 
    
   public function  add_cvg_storage(){
     $seo_meta_data =  $this->input->post();
     
     $this->form_validation->set_rules('title', 'Title', 'required')
                            ->set_rules('country', 'country', 'required')
                            ->set_rules('accredited_by', 'Accredited By', 'required');
                                
            
             if(!$this->form_validation->run()){
                 $this->session->set_flashdata('error', 'somethig went wrong!');
                 redirect('CVG_Storage');  
             }else{

               $aws_data = $this->multiple_upload_image($_FILES['document']);
              
                 $insert_array = array(
                     'title' => $seo_meta_data['title'],
                     'country_id' => $seo_meta_data['country'],
                     'accredited_by' => $seo_meta_data['accredited_by'],
                     'file_name' => $aws_data['aws_path'],
                     'file_path' => $aws_data['aws_path'],
                     'create_by' => $this->session->userdata('user_data')->uidnr_admin
                 );
                 $result = $this->cvg->add_cvg_storage($insert_array);
                 if($result){
                    $this->session->set_flashdata('success', 'Added Successfully!');
                    redirect('CVG_Storage');
                 }
                 else{
                    $this->session->set_flashdata('error', 'error in adding!');
                 }
                
             }
      
   }

   public function edit_cvg(){
      $cs_id = $this->input->get('cs_id');
      $data['cvg'] = $this->cvg->getCvgStorage($cs_id);
      $data['country'] = $this->cvg->fetch_country();
      $data['accredited_by'] = $this->cvg->get_accredited_by();
      echo json_encode($this->load->view('cvg/cvg_edit_form', $data, true));
   }
   
   public function update_cvg(){
       $seo_meta_data =  $this->input->post();
       $cvg_storage_id =  $seo_meta_data['cvg_storage_id'];
    $this->form_validation->set_rules('title', 'Title', 'required')
                            ->set_rules('country', 'country', 'required')
                            ->set_rules('accredited_by', 'Accredited By', 'required');
                
            if(!$this->form_validation->run()){
                 $this->session->set_flashdata('error', 'somethig went wrong!');
                 redirect('CVG_Storage');  
             }else{
                 if(!empty($_FILES['document']['name'])){
                    $aws_data = $this->multiple_upload_image($_FILES['document']);
                    $update_array = array(
                        'title' => $seo_meta_data['title'],
                        'country_id' => $seo_meta_data['country'],
                        'accredited_by' => $seo_meta_data['accredited_by'],
                        'file_name' => $aws_data['aws_path'],
                        'file_path' => $aws_data['aws_path'],
                        'create_by' => $this->session->userdata('user_data')->uidnr_admin
                      
                    );
                 }else{
                    $update_array = array(
                        'title' => $seo_meta_data['title'],
                        'country_id' => $seo_meta_data['country'],
                        'accredited_by' => $seo_meta_data['accredited_by'],
                        'create_by' => $this->session->userdata('user_data')->uidnr_admin
                      
                    );
                 }   
                $this->cvg->update_cvg_storage($update_array, $cvg_storage_id);
                 
                 $this->session->set_flashdata('success', 'Updated Successfully!');
                 redirect('CVG_Storage');
             }
      
   }
   


   public function download_cvg_file($path)
   {
     
       $path = base64_decode($path);
       $this->load->helper('download');
       $pdf_path    =   file_get_contents($path);
       $pdf_name    =   basename($path);
       force_download($pdf_name, $pdf_path);
   }

   public function delete_cvg_storage(){
      $cvg_storage_id = base64_decode($this->input->get('cs_id'));
      if(!empty($cvg_storage_id)){
        $result =   $this->db->where('cvg_storage_id', $cvg_storage_id)
                  ->delete('cvg_storage');
        if($result){
            $data = array();
            $data['source_module'] = 'CVG_Storage';
            $data['record_id'] = $cvg_storage_id;
            $data['created_on'] = date("Y-m-d h:i:s");
            $data['created_by'] = $this->user;
            $data['action_taken'] = 'delete_cvg_storage';
            $data['text'] = 'cvg storage deleted';
            $result = $this->cvg->insert_data('user_log_history',$data);
            $this->session->set_flashdata('success', 'Deleted Successfully!');
                 redirect('CVG_Storage'); 
        }
        else{
            $this->session->set_flashdata('error', 'Error in Deleting!');
            redirect('CVG_Storage'); 
        }    
         
      }
      redirect('CVG_Storage'); 
   }


   public function get_log_data()
   {
       $id = $this->input->post('id');
       $data = $this->cvg->get_log_data(base64_decode($id));
       echo json_encode($data);
   }


   

}

