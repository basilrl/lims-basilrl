<?php
defined('BASEPATH') or exit('No direct script access allowed');
class To_do_list_model extends MY_Model
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


  public function get_to_do_list($per_page, $page = 0, $search, $where, $count = NULL,$sales_person){

    if(empty($page)){
      $page = '0';
    }
    

      $this->db->select("com.communication_id as id,cus.customer_id,com.subject as communication_name,com.follow_up_date as closing_date,cus.customer_name,'com'");
      $this->db->from('comm_communications com');
     
      if (!empty($search)) {
        $search = trim($search);
        $this->db->group_start();
        $this->db->like('com.subject', $search);
        $this->db->or_like('com.follow_up_date', $search);
        $this->db->or_like('cus.customer_name', $search);
        $this->db->group_end();
      }
      $this->db->join('cust_customers cus','cus.customer_id = com.comm_communications_customer_id','left');
      $this->db->where(['com.created_by'=>$sales_person,'DATE(com.follow_up_date)'=>date("Y-m-d")]);
      $query1 =  $this->db->get_compiled_select();


      // $this->db->select("opp.opportunity_id as id,cus.customer_id,opp.opportunity_name as opportunity_name,opp.estimated_closure_date as closing_date,cus.customer_name,'opp'");
      // $this->db->from('opportunity opp');
    
      // if (!empty($search)) {
      //   $search = trim($search);
      //   $this->db->group_start();
      //   $this->db->like('opp.opportunity_name', $search);
      //   $this->db->or_like('opp.estimated_closure_date', $search);
      //   $this->db->or_like('cus.customer_name', $search);
      //   $this->db->group_end();
      // }
      // $this->db->join('cust_customers cus','cus.customer_id = opp.opportunity_customer_id','left');
      // $this->db->where(['opp.created_by'=>$sales_person,'DATE(opp.estimated_closure_date)'=>date("Y-m-d")]);
      // $query2 =  $this->db->get_compiled_select();

 
     
      if ($count == '1') {
          $query = $this->db->query('SELECT COUNT(*) as no FROM ( '.$query1 .' ) CI_count_all_results '.$where);
          // echo $this->db->last_query();die;
          if($query->num_rows() > 0){
              $query = $query->row();
              return $query->no;
          }else{
              return 0;
          }
      } else {
          $query = $this->db->query('SELECT *  FROM ('.$query1 .') table1 '.$where.' LIMIT '.$page.','.$per_page);
          // echo $this->db->last_query();die;
          if ($query->num_rows() > 0) {
              return $query->result();
          } else {
              return false;
          }
      }
}


  public function get_customer(){
      $this->db->select('cus.customer_id as id,cus.customer_name as name');
      $this->db->from('cust_customers cus');
      $this->db->join('comm_communications com','cus.customer_id = com.comm_communications_customer_id','left');
      $this->db->where(['com.created_by'=>$this->user,'DATE(com.follow_up_date)'=>date("Y-m-d")]);
     
      $query1 =  $this->db->get_compiled_select();


      // $this->db->select('cus.customer_id as id,cus.customer_name as name');
      // $this->db->from('cust_customers cus');
      // $this->db->join('opportunity opp','cus.customer_id = opp.opportunity_customer_id','left');
      // $this->db->where(['opp.created_by'=>$this->user,'DATE(opp.estimated_closure_date)'=>date("Y-m-d")]);
     
      // $query2 =  $this->db->get_compiled_select();

      $result = $this->db->query('SELECT *  FROM ('.$query1. ') table1 GROUP BY id');
      // echo $this->db->last_query();die;
      if($result->num_rows()>0){
        return $result->result();
      }
      else{
        return false;
      }
  }

    public function fetch_com_data($id){
        $this->db->select('com.communication_id,com.subject,note,com.date_of_communication,com.medium,com.comm_communications_customer_id,com.customer_type,com.communication_mode,com.comm_communications_contact_id,com.connected_to,com.follow_up_date');
        $this->db->from('comm_communications com');
        $this->db->where('com.communication_id',$id);
        $result = $this->db->get();
        if($result->num_rows()>0){
          return $result->row();
        }
        else{
          return false;
        }
    }

  //   public function fetch_opp_data($id){
  //     $this->db->select('opp.opportunity_id,opp.opportunity_name,opp.opportunity_value,opp.description,opp.types,opp.estimated_closure_date,opp.opportunity_customer_type,opp.opportunity_status,opp.op_assigned_to,opp.currency_id, opp.opportunity_customer_id,opp.opportunity_contact_id');
  //     $this->db->from('opportunity opp');
  //     $this->db->where('opp.opportunity_id',$id);
  //     $result = $this->db->get();
  //     if($result->num_rows()>0){
  //       return $result->row();
  //     }
  //     else{
  //       return false;
  //     }
  // }

  public function get_asign_to($designation_id2, $designation_id1)
    {
        $this->db->select('admin_profile.uidnr_admin as user_id,CONCAT( admin_profile.admin_fname," ",admin_profile.admin_lname) as user_name,admin_users.admin_active as admin_active');
        $this->db->from('admin_profile');
        $this->db->join('operator_profile', 'operator_profile.uidnr_admin=admin_profile.uidnr_admin', 'inner');
        $this->db->join('admin_users', 'admin_users.uidnr_admin=admin_profile.uidnr_admin', 'inner');
        $this->db->where('admin_users.admin_active', '1');
        $this->db->where_in('operator_profile.admin_designation', [$designation_id2, $designation_id1]);
        $this->db->order_by('admin_profile.admin_fname', 'ASC');
        $result = $this->db->get();
        if ($result->num_rows() > 0) {
            return $result->result();
        } else {
            return false;
        }
    }
}