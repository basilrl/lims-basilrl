<?php

// Model class of mst category by kamal on 6th june 2022;

class Mst_category_model extends CI_Model {

public function __construct() {
    $this->load->database('default');
    $this->load->library('session');
    // Call the Model constructor
    parent::__construct();
}

                // Get list from data base method by kamal on 6th of june 2022

            public function get_all_category_detail($search,$start,$end,$count=false,$sortby=null,$order=null)
            {
                // echo "kamal";
            //    echo $sortby; 
                if ($sortby != NULL|| $order != NULL) {
                    $this->db->order_by($sortby, $order);
                    // echo "kamal"; die;
                } else {
                    $this->db->order_by('category_id', 'desc');
                }
                
                 $this->db->select('category_id ,category_name,category_code,created_on,
                  concat(admin.admin_fname,'.',admin.admin_lname) as created_by')
                 ->from('mst_category as mst')
                 ->join('admin_profile as admin', 'admin.uidnr_admin = mst.created_by', 'left');
                 
                
                 ($search['category_name'] != 'NULL')?$this->db->like('category_name',$search['category_name']):'';
                 ($search['category_code'] != 'NULL')?$this->db->like('category_code',$search['category_code']):'';
                 ($search['created_by'] != 'NULL')?$this->db->like('created_by',$search['created_by']):'';
                 ($search['start_date'] != 'NULL'&&$search['end_date'] != 'NULL')?
                 $this->db->where('date(created_on) >=', $search['start_date'])->where('date(created_on) <=', $search['end_date'])
                 :'';
                 
                if (!$count) {
                    $this->db->limit($start, $end);
                  }
                  $objQuery = $this->db->get(); 
            //    echo $this->db->last_query(); die;
                  if ($count) {
                    return $objQuery->num_rows();
                  }
                  if($objQuery->num_rows() > 0){
                    return $objQuery->result_array();
                  }
                  
                  return [];
                }
            
            

            // Add data into database by kamal on 6th june 2022

            public function insert($arrData) {
                $this->db->insert('mst_category', $arrData);
                if ($arrData['category_name']!=null) {
                    
                    return true;
                }
                 else {
                    return false;
                }
            }


            // update data for mst category by kamal on 6th of june  2022

            public function update($editData, $id) {
                $this->db->where('category_id', $id);
                
                $obj=$this->db->update('mst_category', $editData);
                if ($obj!=null) {
                    return true;
               
                } else {
                  
                    return false;
                }
            }

            // Id wise category detail in mst category by kamal on 6th of june 2022

            public function get_id_wise_category($id) {
                $this->db->select('*');
                $this->db->from('mst_category');
                $this->db->where('category_id', $id);
                $objQuery = $this->db->get();
        
                return $objQuery->row_array();
            }

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
    // added by kamal on 8th of june 2022
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