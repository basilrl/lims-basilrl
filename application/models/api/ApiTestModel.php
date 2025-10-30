<?php 
class ApiTestModel extends CI_Model{
    function __construct()
    {
        parent::__construct();
    }

    public function getTest($product_id, $keyword){
        $this->db->select("test_id as id, test_name AS name,test_name AS full_name");
		$this->db->from('tests');
		$this->db->join('test_sample_type stm', 'test_id = stm.test_sample_type_test_id');
		$this->db->group_start();
		$this->db->where(['test_sample_type_sample_type_id' => $product_id, 'test_status' => 'Active']);
		$this->db->group_end();
		($keyword != 'NULL') ? $this->db->like("tests.test_name", $keyword) : '';
		$this->db->order_by('name', 'ASC');
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->result_array();
		}
        return false;
    }

    

    public function getTestMethod($test_id, $keyword){
        $this->db->select('tm.test_method_id as id, test_method_name as name, test_method_name as full_name');
        $this->db->join('mst_test_methods tm', 'tm.test_method_id = ts.test_method_id');
        $this->db->where('ts.test_id', $test_id);
        ($keyword != 'NULL') ? $this->db->like("test_method_name", $keyword) : '';
        $query = $this->db->get('tests ts');
        if($query->num_rows() > 0){
            return $query->result_array();
        }
        return false;
    }


    public function getAPCInstruction(){
        $this->db->select('instruction_id as id, instruction_name as name, instruction_name as full_name');
        $query = $this->db->get('application_care_instruction');
        if($query->num_rows() > 0){
            return $query->result_array();
        }
        return false;
    }

    public function getAPCInstructionImage($application_care_id){
        $this->db->select('instruction_image, care_wording, priority_order');
        $this->db->where('instruction_id',$application_care_id);
        $query = $this->db->get('application_care_instruction');
        if($query->num_rows() > 0){
            return $query->row_array();
        }
        return false;
    }
}
?>