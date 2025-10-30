<?php
defined('BASEPATH') or exit('No direct access allowed');

class SampleRegEditModel extends MY_Model{
    
    public function __construct() {
        parent::__construct();
       
    }
    
    public function getSampleReg($sampleRegId){
     return   $this->db->select('*')
                ->from('sample_registration')
                ->where('sample_reg_id', $sampleRegId)
                ->get()
                ->row_array();
    }
    
    public function update_sample_reg($sample_reg_id, $update_data){
       return $this->db->update('sample_registration', $update_data, ['sample_reg_id' => $sample_reg_id]); 
    }
    
    public function get_selected_test($trf_id){
        $this->db->select('test_id, test_name, test_method');
        $this->db->from('sample_test');
        $this->db->join('tests','sample_test.sample_test_test_id = tests.test_id');
        $this->db->where('sample_test_sample_reg_id', $trf_id);
        $query = $this->db->get();
        if($query->num_rows() > 0){
            return $query->result_array();
        }
        return [];
    }
}
