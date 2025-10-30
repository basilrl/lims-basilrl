<?php

defined('BASEPATH') or exit('No direct access allowed');

class SampleRegistrationEdit extends MY_Controller{
   
    public function __construct() {
        parent::__construct();
        $this->check_session();
        $this->load->model('SampleRegEditModel', 'SREM')->model('SampleRegistration', 'sr');
        $this->form_validation->set_error_delimiters('<div class="error" style="color:red">', '</div>');
    }
    
    public function index(){
        $data['sample_reg_id'] = $sample_reg_id =  base64_decode($this->input->get('sample_reg_id'));
       
         $data['sample_data'] = $this->_getSampleReg($sample_reg_id);
         $data['trf_id'] =  $trf_id =  $data['sample_data']['trf_registration_id'];
        $data['application_care_instruction'] = $this->sr->get_application_care_instruction();
        
         $data['trf_data'] = $this->sr->get_fields_by_id("trf_registration", "trf_sample_desc, trf_buyer, product_custom_fields", $trf_id, "trf_id");
         
         $data['selected_application_care_instruction'] = $this->sr->get_fields_by_id('trf_apc_instruction', '*', $trf_id, 'trf_id');
         $data['product_details'] = $this->sr->get_product_details($trf_id);
          $product_id = $data['product_details']->pid;
         $data['test_specification'] = $this->sr->get_test_spec($product_id);
         // Commented by Saurabh on 18-08-2021, not to change test data
        //  $data['selected_test'] = $this->SREM->get_selected_test($sample_reg_id);
         $data['labs_id'] = $this->sr->get_labs_by_branch($trf_id);
         // Commented by Saurabh on 18-08-2021, not to change test data
        // $data['tests']  = $this->sr->get_test_name($product_id);
          $table = "mst_branches";
          $status = "1";
          $columns = "branch_id, branch_name";
        $data['branches'] = $this->sr->get_columns_data($table, $columns, $status);
        $data['units'] = $this->sr->get_units();
       
        
        
       $this->load_view('sample_registration/sample_reg_edit', $data);
    }

    private function _getSampleReg($sample_reg_id) {
      return $this->SREM->getSampleReg($sample_reg_id);
    }
    
    public  function edit_sample_reg(){
      $data['sample_data'] =  $sample_edit_data = $this->input->post(); 

       $this->form_validation->set_rules('sample_desc', 'Sample Description', 'required')
                             ->set_rules('branch_name', 'Branch', 'required')
                             ->set_rules('sample_registration_sample_type_id', 'Product', 'required')
                             ->set_rules('qty_received', 'Qty Received', 'required')
                             ->set_rules('sample_desc', 'Sample Description', 'required')
                             ->set_rules('quantity_desc', 'Quantity Description', 'required');
                             // Commented by Saurabh on 18-08-2021, not to change test data
                            //  ->set_rules('griddata[]', 'Test', 'required');
         if(!$this->form_validation->run()){
             
           $data['sample_data'] = $sample_edit_data;
           $data['sample_data']['product_custom_fields'] = json_encode($this->_set_product_custom_fields($sample_edit_data['dynamic_fields']));
           $data['trf_id'] =  $trf_id =  $sample_edit_data['trf_reg_id'];
           $data['application_care_instruction'] = $this->sr->get_application_care_instruction();
              $acp_data =[];
           if(!empty($sample_edit_data['dynamic'])){
                 
                    foreach($sample_edit_data['dynamic']['application_care_id'] as $key => $care_instructions){

                        if(!empty($sample_edit_data['dynamic']['application_care_id'][$key])){
                            
                           $acp_data[] = array(
                               'image' => $sample_edit_data['dynamic']['image'][$key],
                               'description' => $sample_edit_data['dynamic']['description'][$key],
                               'image_sequence' => $sample_edit_data['dynamic']['image_sequence'][$key],
                               'application_care_id' => $care_instructions,
                               
			       'created_on' => date('Y-m-d H:i:s'),
			       'trf_id' => $sample_edit_data['trf_reg_id']
                           ); 
                        }
			}
                      
		}
           
           $data['selected_application_care_instruction'] =   $acp_data;
           $data['product_details'] = $this->sr->get_product_details($trf_id);
           $product_id = $data['product_details']->pid;
         $data['test_specification'] = $this->sr->get_test_spec($product_id);
         // Commented by Saurabh on 18-08-2021, not to change test data
        //  $data['selected_test'] = $sample_edit_data['griddata'];
         $data['labs_id'] = $this->sr->get_labs_by_branch($trf_id);
         $data['tests']  = $this->sr->get_test_name($product_id);
          $table = "mst_branches";
          $status = "1";
          $columns = "branch_id, branch_name";
        $data['branches'] = $this->sr->get_columns_data($table, $columns, $status);
        $data['units'] = $this->sr->get_units();
           
            
           $this->load_view('sample_registration/sample_reg_edit', $data);   
         }else{
          $pro_custom_fields = $this->_set_product_custom_fields($sample_edit_data['dynamic_fields']);
          $update_data  = array(
              'sample_registration_branch_id' => $sample_edit_data['branch_name'],
              'sample_registration_test_standard_id' => $sample_edit_data['test_standard_id'],
             
              'seal_no' => $sample_edit_data['seal_no'],
              'sample_registration_sample_type_id' => $sample_edit_data['sample_registration_sample_type_id'],
              'sample_desc' => $sample_edit_data['sample_desc'],
              'qty_received' => $sample_edit_data['qty_received'],
              'qty_unit' => $sample_edit_data['qty_unit'],
              'quantity_desc' => $sample_edit_data['quantity_desc'],
              'sample_registered_to_lab_id' => $sample_edit_data['assign_sample_registered_to_lab_id'],
              'sample_retain_status' => $sample_edit_data['sample_retain_status'],//
              'create_by' => $this->session->userdata('user_data')->uidnr_admin,
              'product_custom_fields' => json_encode($pro_custom_fields)
          );
           $this->SREM->update_sample_reg($sample_edit_data['sample_reg_id'], $update_data);
           $old_status_query = $this->db->select('status')->from('sample_registration')->where('sample_reg_id',$sample_edit_data['sample_reg_id'])->get();
           $old_status = $old_status_query->row()->status;
           $logDetails = array('module' => 'Samples',
                'old_status' => $old_status,
                'new_status' => '',
                'sample_reg_id' => $sample_edit_data['sample_reg_id'],
                'sample_assigned_lab_id' => /* $lab_id, */ '',
                'action_message' => 'Sample Edited',
                'sample_job_id' => '',
                'report_id' => '',
                'report_status' => '',
                'test_ids' => '',
                'test_names' => '',
                'test_newstatus' => '',
                'test_oldStatus' => '',
                'test_assigned_to' => '',
                'source_module' => 'sample_registration',
                'operation'   => 'edit_sample_reg',
                'uidnr_admin' => $this->session->userdata('user_data')->uidnr_admin,
                'log_activity_on'=> date("Y-m-d H:i:s")
            );
            $this->sr->save_user_log($logDetails);

           $this->_update_apc_details($sample_edit_data);
           // Commented by Saurabh on 18-08-2021, not to change test data
        //    $this->_update_test_details($sample_edit_data);
           $this->_update_trf_details($sample_edit_data);
           $this->session->set_flashdata('success', 'Sample Edited Succussfully!');
           redirect('sample-list');
        }
           
      
    }
    
     public function _update_apc_details($sample_edit_data) {
                $data =[];
                $this->db->delete('trf_apc_instruction', ['trf_id' => $sample_edit_data['trf_reg_id']]);
		if(!empty($sample_edit_data['dynamic'])){
                   
                    foreach($sample_edit_data['dynamic']['application_care_id'] as $key => $care_instructions){

                        if(!empty($sample_edit_data['dynamic']['application_care_id'][$key])){
                            
                           $data[] = array(
                               'image' => $sample_edit_data['dynamic']['image'][$key],
                               'description' => $sample_edit_data['dynamic']['description'][$key],
                               'image_sequence' => $sample_edit_data['dynamic']['image_sequence'][$key],
                               'application_care_id' => $care_instructions,
                               'created_by' => $this->session->userdata('user_data')->uidnr_admin,
			       'created_on' => date('Y-m-d H:i:s'),
			       'trf_id' => $sample_edit_data['trf_reg_id']
                           ); 
                        }
			}
                      if(!empty($data)){
                        $this->SREM->insert_multiple_data('trf_apc_instruction', $data);   
                      }
		}
                
                return true;
    }
    
    private function _update_test_details($sample_edit_data){
            if(!empty($sample_edit_data['griddata'])){
                
                $this->db->where('trf_test_trf_id', $sample_edit_data['trf_reg_id'])->delete('trf_test');
                
                $this->db->where('sample_test_sample_reg_id', $sample_edit_data['sample_reg_id'])->delete('sample_test');
                
                $test = $sample_edit_data['griddata'];
                $getLabids_query = $this->db->select('group_concat(Distinct (lab_id)) as lab_id')
                                   ->from('mst_labs lb')
                                   ->join('tests ts','ts.test_lab_type_id = lb.mst_labs_lab_type_id','left')
                                   ->join('mst_lab_type mlt','mlt.lab_type_id=ts.test_lab_type_id')
                                   ->where('lb.mst_labs_branch_id', $sample_edit_data['branch_name'])
                                   ->where_in('ts.test_id', $test)
                                   ->get();
                
                if($getLabids_query->num_rows() > 0){
                    $getLab = $getLabids_query->row();
                    $getlab_reg1['no_labs_assigned'] = array_unique(explode(",", $getLab->lab_id));
                    $getlab_reg2['no_labs_assigned'] = implode(",", $getlab_reg1['no_labs_assigned']);
                    $this->db->update('sample_registration', $getlab_reg2, ['sample_reg_id' => $sample_edit_data['sample_reg_id']]);
                } else {
                    $getLab = [];
                }

            // Get lab id for tests
            foreach($test as $tests){
                $labid_query = $this->db->select('group_concat(Distinct lab_id) as lab_id')
                                        ->from('mst_labs lb')
                                        ->join('tests ts','ts.test_lab_type_id = lb.mst_labs_lab_type_id','left')
                                        ->join('mst_lab_type mlt','mlt.lab_type_id=ts.test_lab_type_id')
                                        ->where('lb.mst_labs_branch_id', $sample_edit_data['branch_name'])
                                        ->where('ts.test_id', $tests)
                                        ->get();
                if($labid_query->num_rows() > 0){
                    $lab_id = $labid_query->row()->lab_id;
                } else {
                    $lab_id = "";
                }

                $test_data['sample_test_lab_id'] = $lab_id;
                $test_data['sample_test_test_id'] = $tests;
                $test_data['sample_test_sample_reg_id'] = $sample_edit_data['sample_reg_id'];
                $test_data['sample_test_parameters'] = "";
               
                
                $test_trf_data['trf_test_test_id'] = $tests;
                $test_trf_data['trf_test_trf_id'] = $sample_edit_data['trf_reg_id'];
                
               
                $this->db->insert('sample_test', $test_data);
                $this->db->insert('trf_test', $test_trf_data);
            }
        }
    }

    private function _set_product_custom_fields($pro_custom_fields) {
        if(!empty($pro_custom_fields)){
            $custom_field_arr = [];
          foreach($pro_custom_fields['value1'] as $k=>$pcf){
              if(!empty($pcf)){
                $custom_field_arr[] = array($pcf, $pro_custom_fields['value2'][$k]);    
              }
           } 
         return $custom_field_arr; 
        }
        return [];
    }

    private function _update_trf_details($sample_edit_data) {
        $pro_custom =  $this->_set_product_custom_fields($sample_edit_data['dynamic_fields']);
       $update_pro_custom_fields = array('product_custom_fields' => json_encode($pro_custom), 'trf_sample_desc' => $sample_edit_data['sample_desc']);
       $this->db->update('trf_registration', $update_pro_custom_fields, ['trf_id' => $sample_edit_data['trf_reg_id']]);
    }

}
    
