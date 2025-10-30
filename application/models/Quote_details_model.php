<?php

// Model class of Quote Contact Details by kamal on 14th june 2022;

class Quote_details_model extends CI_Model {

public function __construct() {
    $this->load->database('default');
    $this->load->library('session');
    // Call the Model constructor
    parent::__construct();
}
    public function get_All_Details($search,$start,$end,$count=false,$sortby=null,$order=null)
    {
        if ($sortby != NULL|| $order != NULL) {
            $this->db->order_by($sortby, base64_decode($order));
        } else {
            $this->db->order_by('details_id', 'desc');
        }
        $this->db->select('qu.details_id,qu.status,qu.created_on,
        concat(admin.admin_fname,'.',admin.admin_lname) as created_by,ms.division_name as division')
        ->from('quote_contact_details as qu')
        ->join('admin_profile as admin', 'admin.uidnr_admin = qu.created_by', 'left')
        ->join('mst_divisions as ms', 'ms.division_id=qu.division', 'left');

        
        ($search['division'] != 'null'&&$search['division'] != 'NULL') ? $this->db->like('division',$search['division']):'';
        ($search['status'] != 'NULL'&&$search['status'] != 'null') ? $this->db->where('status=',$search['status']):'';
        ($search['created_by'] != 'NULL')?$this->db->like('created_by',$search['created_by']):'';
        ($search['start_date'] != 'NULL'&&$search['end_date'] != 'NULL')?
        $this->db->where('date(created_on) >=', $search['start_date'])->where('date(created_on) <=', $search['end_date']):'';
        if (!$count) {
            $this->db->limit($start, $end);
          }
        $objQuery=$this->db->get();
        // echo "<pre>";
        // echo $this->db->last_query(); die;
        if ($count) {
            return $objQuery->num_rows();
          }
         
        //    echo $objQuery->result_array(); die;

        if($objQuery->num_rows() >0)
        {
            return $objQuery->result_array();
        }
        return [];
    }


    public function fetch_division_name()
    {
    $query = $this->db->select('division_name as division , de.division_id')
    ->from('mst_divisions de')
    ->order_by('de.division_name','asc')
    ->get();
    if ($query->num_rows() > 0) {
        
        return $query->result();
    } else {
        return false;
    }
}


            public function insert_details($arrData)
            {   
              
                $this->db->insert('quote_contact_details', $arrData);
                if ($arrData['division']!=null) {
                    
                    return true;
                }
                 else {
                    return false;
                }
            }


            public function get_division_by_details($details_id)
            {

                $this->db->select('*');
                $this->db->from('quote_contact_details');
                $this->db->where('details_id', $details_id);
                $objQuery = $this->db->get();
                // echo $this->db->last_query(); die;
        
                return $objQuery->row_array();
            }

            public function update_details($editData,$details_id)
            {
                $this->db->where('details_id', $details_id);
            
                $obj=$this->db->update('quote_contact_details', $editData);
                // echo $this->db->last_query(); die;
                if ($obj!=null) {
                    return true;
                } else {
                  
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
