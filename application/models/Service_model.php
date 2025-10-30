<?php
class Service_model extends MY_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    public function get_services_list($start,$end,$lab_location,$product_destination,$test_standard,$certificate,$count=false)
    {
        $this->db->select('lab.country_name as lab_location, pro_des.country_name as product_destination, certificate_name, (CASE WHEN tbl_services.status = 1 then "Active" WHEN tbl_services.status = 0 then "Inactive" END) as status, CONCAT(admin_fname," ",admin_lname) as created_by, test_standard_name, tbl_services.created_on, services_id');
        $this->db->join('mst_country lab','lab.country_id = lab_location_id');
        $this->db->join('mst_country as pro_des','pro_des.country_id = product_destination_id');
        $this->db->join('admin_profile','admin_profile.uidnr_admin = tbl_services.created_by','left');
        $this->db->join('cps_test_standard cts','cts.id = test_standard_id');
        $this->db->join('cps_certificate','certificate_id = cps_certificate.id','left');
        ($lab_location != 'NULL' && $lab_location !='')?$this->db->where('lab_location_id',base64_decode($lab_location)):'';
        ($product_destination != 'NULL' && $product_destination !='')?$this->db->where('product_destination_id',base64_decode($product_destination)):'';
        ($test_standard != 'NULL' && $test_standard !='')?$this->db->where('test_standard_id',base64_decode($test_standard)):'';
        ($certificate != 'NULL' && $certificate !='')?$this->db->where('certificate_id',base64_decode($certificate)):'';
        if (!$count) {
            $this->db->limit($start, $end);
        }
        $this->db->order_by('services_id','desc');
        $query = $this->db->get('tbl_services');
        // echo $this->db->last_query();
        if ($count) {
            return $query->num_rows();
        }
        if($query->num_rows() > 0){
            return $query->result_array();
        }
        return [];
    }

    public function get_lab_location($key)
    {
        $this->db->select('country_id as id, country_name as name, country_name as full_name');
        ($key != NULL)?$this->db->like('country_name',$key):'';
        $this->db->where('lab_location_flag','1');
        $query = $this->db->get('mst_country');
        if($query->num_rows() > 0){
            return $query->result_array();
        }
        return [];
    }

    public function get_product_destination($key)
    {
        $this->db->select('country_id as id, country_name as name, country_name as full_name');
        ($key != NULL)?$this->db->like('country_name',$key):'';
        $this->db->where('product_destination_flag','1');
        $query = $this->db->get('mst_country');
        if($query->num_rows() > 0){
            return $query->result_array();
        }
        return [];
    }

    public function get_test_standards($key)
    {
        $this->db->select('id as id, test_standard_name as name, test_standard_name as full_name');
        ($key !=NULL)?$this->db->like('test_standard_name',$key):'';
        $query = $this->db->get('cps_test_standard');
        if($query->num_rows() > 0){
            return $query->result_array();
        }
        return [];
    }

    public function get_certificate($key)
    {
       $this->db->select('id as id, certificate_name as name, certificate_name as full_name');
       $this->db->where('status','1');
       ($key != NULL)?$this->db->like('certificate_name',$key):'';
       $query = $this->db->get('cps_certificate');
       if($query->num_rows() > 0){
           return $query->result_array();
       }
       return [];
    }

    public function get_service_data($id)
    {
        $this->db->select('services_id, lab_location_id, lab.country_name as lab_location, product_destination_id, pro_des.country_name as product_destination, tbl_services.status, test_standard_id, test_standard_name, certificate_id, certificate_name');
        $this->db->join('mst_country lab','lab_location_id = lab.country_id');
        $this->db->join('mst_country pro_des','product_destination_id = pro_des.country_id');
        $this->db->join('cps_test_standard cts','cts.id = test_standard_id');
        $this->db->join('cps_certificate','cps_certificate.id = certificate_id','left');
        $this->db->where('services_id',$id);
        $query = $this->db->get('tbl_services');
        // echo $this->db->last_query(); die;
        if($query->num_rows() > 0){
            return $query->row_array();
        }
        return [];
    }

    public function get_services_log($services_id)
    {
    $query = $this->db->select('action_taken, created_on as taken_at, text,concat(admin_fname," ",admin_lname) as taken_by')
                        ->join('admin_profile','user_log_history.created_by = admin_profile.uidnr_admin')
                        ->where('source_module','Services_Controller')
                        ->where('record_id',$services_id)
                        ->order_by('id','desc')
                        ->get(' user_log_history');
    if($query->num_rows() > 0){
        return $query->result_array();
    }
    return [];
    }
    
}

?>