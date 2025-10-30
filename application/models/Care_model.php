<?php

// MODEL CLASS FOR APPLICATION CARE INSTRUCTION BY KAMAL ON 6TH JUNE 2022
class Care_Model extends CI_Model {

    public function __construct() {
        $this->load->database('default');
        $this->load->library('session');
        // Call the Model constructor
        parent::__construct();
    }

    public function get_all_instruction_detail($search,$start,$end,$count=false,$sortby=null,$order=null) {

        if ($sortby != NULL|| $order != NULL) {
            $this->db->order_by($sortby, $order);
            
        } else {
            $this->db->order_by('instruction_id', 'desc');
        }

        $this->db->select('instruction_id,instruction_name,instruction_type,instruction_image,
        care_wording,created_on,priority_order, concat(admin.admin_fname,'.',admin.admin_lname) as created_by')
        ->from('application_care_instruction as app')
        ->join('admin_profile as admin', 'admin.uidnr_admin = app.created_by', 'left');
       
        
        ($search['instruction_name'] != 'NULL')?$this->db->like('instruction_name',$search['instruction_name']):'';
    ($search['instruction_type'] != 'NULL')?$this->db->like('instruction_type',$search['instruction_type']):'';
    ($search['care_wording'] != 'NULL')?$this->db->like('care_wording',$search['care_wording']):'';
    ($search['created_by'] != 'NULL')?$this->db->like('created_by',$search['created_by']):'';
    ($search['start_date'] != 'NULL'&&$search['end_date'] != 'NULL')?
    $this->db->where('date(created_on) >=', $search['start_date'])->where('date(created_on) <=', $search['end_date'])
    :'';
      
    if (!$count) {
        $this->db->limit($start, $end);
      }
      $objQuery = $this->db->get();
    //   echo $count; die;
      if ($count) {
    //   echo $this->db->last_query(); die;

        return $objQuery->num_rows();
      }
      if($objQuery->num_rows() > 0){
        return $objQuery->result_array();
      }
      return [];
    }
    public function getTotalRows($search1=null,$search2=null,$search3=null)
    {
        if($search1!=null)
        {
         $search1=trim($search1);
 
            $this->db->like("instruction_name",$search1);
        }
        if($search2!=null)
        {
         $search2=trim($search2);
            $this->db->like("instruction_type",$search2);
        }
        if($search3!=null)
        {
            $search3=trim($search3);
            $this->db->like("care_wording",$search3);
        }
        $q=$this->db->get('application_care_instruction');
        return $q->num_rows();
        
    }
   
    public function get_list($limit,$start)
    {
        $query=$this->db->select('*')->from('application_care_instruction')->
        limit($limit,$start)->get();
        return $query->num_rows();
    }



    public function insert($arrData) {
        $this->db->insert('application_care_instruction', $arrData);
        if ($arrData['instruction_name']!=null) {
            
            return true;
        }
         else {
            return false;
        }
    }



    public function update($editData, $id) {
        // echo "<pre>";
        // print_r($editData); die;
        $this->db->where('instruction_id', $id);
        $obj=$this->db->update('application_care_instruction', $editData);
        if ($obj!=null) {
            return true;
        } else {
            return false;
        //     
        }
    }

    public function get_id_wise_instruction($id) {
        $this->db->select('*');
        $this->db->from('application_care_instruction');
        $this->db->where('instruction_id', $id);
        $objQuery = $this->db->get();

        return $objQuery->row_array();
    }
    // public function get_Image($id)
    // {
    //     $this->db->select('instruction_image')
    //     ->from('application_care_instruction')
    //     ->where('instruction_id',$id);
    //     $object=$this->db->get();
    //     return $object->row_array()['instruction_image'];
    // }
    
    public function get_row($col='*',$table,$where){
        $this->db->select($col)
                    ->from($table)
                    ->where($where);
                    $result = $this->db->get();
        if($result->num_rows()>0){
            return $result->num_rows(); 
        }
        else{
            return false;
        }
        }

        public function fetch_created_person()
    {
        $query = $this->db->select('CONCAT(ap.admin_fname, " ", ap.admin_lname) as created_by, ap.uidnr_admin')
            ->from('admin_profile ap')
            ->order_by('ap.admin_fname','asc')
            ->get();
            
        if ($query->num_rows() > 0) {
            // echo "<pre>";
            // print_r($query->result()); die;
            return $query->result();
        } else {
            return false;
        }
    }
}
?>