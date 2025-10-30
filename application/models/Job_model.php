<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Job_model extends MY_Model
{
  public function __construct()
  {
    parent::__construct();
    $this->db->trans_start();
  }

  public function __destruct()
  {
    $this->db->trans_complete();
  }
  
  public function get_services_list($start,$end,$search,$sortby,$order, $count=false)
  {
    $this->db->select('job_discription.depart_id as depart_id, job_discription.job_posted,job_discription.job_discription as job_discription, job_discription.job_title,job_discription.job_status, (CASE WHEN job_discription.job_status = 1 then "Active" WHEN job_discription.job_status = 0 then "Inactive" END) as status,CONCAT(admin_profile.admin_fname," ",admin_profile.admin_lname) as created_by,dept_name,id');
    $this->db->join('mst_departments','mst_departments.dept_id = depart_id');
    $this->db->join('admin_profile','admin_profile.uidnr_admin = job_discription.created_by','left');
    ($search['depart_id'] != 'NULL')?$this->db->where('depart_id',$search['depart_id']):'';
    ($search['job_status'] != 'NULL')?$this->db->where('job_status',$search['job_status']):'';
    ($search['job_title'] != 'NULL')?$this->db->like('job_title',$search['job_title']):'';
  
    if (!$count) {
      $this->db->limit($start, $end);
    }
    if($sortby != NULL){
      $this->db->order_by($sortby,$order);
    } else {
      $this->db->order_by('id','desc');
    }
    $query = $this->db->get('job_discription');
    if ($count) {
      return $query->num_rows();
    }
    if($query->num_rows() > 0){
      return $query->result();
    }
    return [];
  }


  function saverecords($job_dis, $job_title, $job_posted, $job_status, $job_depart)
  {
    $query = "insert into job_discription(job_discription,job_title,job_posted,job_status,depart_id) values('$job_dis','$job_title','$job_posted','$job_status','$job_depart')";
    $this->db->query($query);
  }

  function get_data()
  {
    $query = $this->db->query("select Jd.*,Md.dept_name,(CASE When jd.job_status='1' Then 'Active' Else 'Inactive' END)As Status from job_discription Jd inner join mst_departments MD on Jd.depart_id=MD.dept_id");
    return $query->result();
  }


  function displayrecordsById($id)
  {
    $query = $this->db->query("select * from job_discription where id='" . $id . "'");
    return $query->result();
  }

  function deleterecords($id)
  {
    $this->db->query("delete  from job_discription where id='" . $id . "'");
  }

  function updaterecords($job_dis, $job_title, $job_posted, $job_status, $job_depart, $id)
  {
    // $query=$this->db->query("update job_discription SET job_discription='$job_dis',
    // job_title='$job_title',job_status='$job_status',depart_id='$job_depart',
    // job_posted='$job_posted' where id='".$id."'");


    $d = $this->db->query("update job_discription SET job_discription='$job_dis',
  job_title='$job_title',job_status='$job_status',depart_id='$job_depart',
  job_posted='$job_posted' where id='" . $id . "'");
  
    $data = $d->row_array();
    return $data;
  }

  public function get_departments()
  {
    $this->db->select('dept_id as id, dept_name as name, dept_name as full_name');
    $this->db->where('mst_departments.status','1');
    $query = $this->db->get('mst_departments');
    if ($query->num_rows() > 0) {
      return $query->result_array();
    }
    return [];
  }

  public function get_product_destination($key)
    {
        $this->db->select('country_id as id, country_name as name, country_name as full_name');
        ($key != NULL)?$this->db->like('country_name',$key):'';
        $this->db->where('mst_country.status','1');
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
}
