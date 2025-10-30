<?php

defined('BASEPATH') or exit('No direct access allowed');

class SEOMetaDesc extends MY_Controller{
    
      public function __construct() {
        parent::__construct();
        $this->check_session();
        $this->load->model('SeoMetaDesc_model', 'seo');
        $this->form_validation->set_error_delimiters('<div class="error" style="color:red">', '</div>');
    }
    
    public function index(){
      $base_url = 'SEOMetaDesc/index';
      $where = array();
       
       if ($this->uri->segment(3) != '' && $this->uri->segment(3) != 'NULL') {
            $data['search'] = $search = $this->uri->segment(3);
             $base_url  .= '/' . $this->uri->segment(3);
        } else {
            $data['search'] = $search = NULL;
            $base_url  .= '/NULL';
        } 
      
        $data['result_count'] =  $total_row = $this->seo->seo_meta_desc_list(NULL, NULL, $search, NULL, NULL, $where, '1');
        $config =  $this->pagination($base_url, $total_row, 10, 4);
        
        $data['links'] = $config['links'];
        $data['listing'] = $this->seo->seo_meta_desc_list($config["per_page"], $config['page'], $search, NULL, NULL, $where, NULL);

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
       $this->load_view('seo_meta_desc/seo_meta_desc', $data);  
    }
    
    public function getSeoContentAddForm(){
        echo json_encode($this->load->view('seo_meta_desc/seo_add_form', true));
    }
    
   public function  add_seo_meta_desc(){
     $seo_meta_data =  $this->input->post();
     
     $this->form_validation->set_rules('page_title', 'Page Title', 'required')
                            ->set_rules('keywords', 'Keywords', 'required')
                            ->set_rules('page_url', 'Page URL', 'required');
                                
            
             if(!$this->form_validation->run()){
                 $this->session->set_flashdata('error', 'somethig went wrong!');
                 redirect('SEOMetaDesc');  
             }else{
                 $insert_array = array(
                     'page_title' => $seo_meta_data['page_title'],
                     'keywords' => $seo_meta_data['keywords'],
                     'page_url' => $seo_meta_data['page_url'],
                     'description' => $seo_meta_data['description'],
                     'created_by' => $this->session->userdata('user_data')->uidnr_admin
                 );
                 $this->seo->add_cps_meta_content($insert_array);
                 $this->session->set_flashdata('success', 'Added Successfully!');
                 redirect('SEOMetaDesc');
             }
      
   }

   public function edit_seo(){
      $pid = $this->input->get('pid');
      $data['seo'] = $this->seo->getSeoMeta($pid);
      echo json_encode($this->load->view('seo_meta_desc/seo_edit_form', $data, true));
   }
   
   public function update_seo(){
       $seo_meta_data =  $this->input->post();
       $page_id =  $seo_meta_data['page_id'];
     $this->form_validation->set_rules('page_title', 'Page Title', 'required')
                            ->set_rules('keywords', 'Keywords', 'required')
                            ->set_rules('page_url', 'Page URL', 'required');
                
            if(!$this->form_validation->run()){
                 $this->session->set_flashdata('error', 'somethig went wrong!');
                 redirect('SEOMetaDesc');  
             }else{
                 $update_array = array(
                     'page_title' => $seo_meta_data['page_title'],
                     'keywords' => $seo_meta_data['keywords'],
                     'page_url' => $seo_meta_data['page_url'],
                     'description' => $seo_meta_data['description1'],
                   
                 );
                 $this->seo->update_cps_meta_content($update_array, $page_id);
                 $this->session->set_flashdata('success', 'Updated Successfully!');
                 redirect('SEOMetaDesc');
             }
      
   }
}