<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Regulations_model extends MY_Model
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

  public function get_regulations_list($limit = NULL, $start = NULL, $search = NULL, $sortby = NULL, $order = NULL, $where = NULL, $count = NULL)
  {

    if ($sortby != NULL || $order != NULL) {
      $this->db->order_by($sortby, $order);
    } else {
      $this->db->order_by('acc.regulations_id', 'DESC');
    }

    if ($where) {
      $this->db->where($where);
    }


    if ($search != NULL && $search != 'NULL') {
      $search = trim($search);
      $this->db->group_start();
      $this->db->like('crn.notification_description', $search);
      $this->db->or_like('crn.notification_title', $search);
      $this->db->or_like('acc.notification_date', $search);
      $this->db->or_like('title', $search);
      $this->db->or_like('md.division_name', $search);
      $this->db->or_like('country_name', $search);
      $this->db->or_like('notified_body_name', $search);
      $this->db->or_like('admin.admin_fname', $search);
      $this->db->or_like('acc.created_on', $search);
      $this->db->or_like('ctat.notification_content', $search);
      $this->db->group_end();
    }

    $this->db->select('acc.regulations_id,crn.notification_description,crn.notification_id,
    crn.notification_title,acc.notification_date,acc.file_path,acc.title,
    md.division_name,ct.country_name,nb.notified_body_name,
    admin.admin_fname,acc.created_on,ctat.notification_content');
    $this->db->from('manage_regulations as acc');
    $this->db->join('cps_regulation_notification as crn', 'crn.regulation_id=acc.regulations_id and crn.status=1','left');
    $this->db->join('admin_profile as admin', 'admin.uidnr_admin=acc.created_by', 'left');
    $this->db->join('mst_country ct','ct.country_id=acc.country_id','left');
    $this->db->join('mst_divisions md','md.division_id=acc.division_id','left');
    $this->db->join('notified_body nb','nb.notified_body_id=acc.notified_body_id','left');
    $this->db->join('cps_regulations_tat_notifications as ctat','acc.regulations_id=ctat.regulation_id','left');
    $this->db->group_by('acc.regulations_id');
    $this->db->limit($limit, $start);
    $result = $this->db->get();
  
    if ($result->num_rows() > 0) {
      if ($count != NULL) {
        return $result->num_rows();
      } else {
        return $result->result();
      }
    } else {
      return false;
    }
  }

  public function get_regulation_data($regulation_id){
      $this->db->select('reg.title,reg.country_id,reg.division_id,reg.notified_body_id,reg.notification_date,reg.tat_description,reg.file_name');
      $this->db->from('manage_regulations reg');
      $this->db->where('reg.regulations_id',$regulation_id);
      $result = $this->db->get();
      if($result->num_rows()>0){
          return $result->row();
      }
      else{
          return false;
      }
  }

  public function insert_data_regulations($data){
      $this->db->insert('manage_regulations',$data);
      $regulation_id = $this->db->insert_id();

      if($regulation_id){
          $notification = array();
          $notification['notification_title'] = $data['title'];
          $notification['notification_content'] = $data['tat_description'];
          $notification['created_by'] = $data['created_by'];
          $notification['regulation_id'] = $regulation_id;

          $LOG = array();
          $LOG['source_module'] = 'Regulations';
          $LOG['record_id'] = $regulation_id;
          $LOG['created_on'] = date("Y-m-d h:i:s");
          $LOG['created_by'] = $this->user;
          $LOG['action_taken'] = 'INSERT DATA REGULATIONS';
          $LOG['text'] = 'ADD REGULATION WITH TITLE '.$data['title'];
          $this->insert_data('user_log_history',$LOG);

         $result =  $this->db->insert('cps_regulations_tat_notifications',$notification);

         if($result){
             return true;
         }
         else{
             return false;
         }
      }
      else{
          return false;
      }
  }

  public function update_data_regulations($data,$regulation_id){
        $this->db->where('regulations_id',$regulation_id);
        $result = $this->db->update('manage_regulations',$data);

        if($result){
            $notification = array();
          $notification['notification_title'] = $data['title'];
          $notification['notification_content'] = $data['tat_description'];
          $notification['created_by'] = $data['created_by'];
          $notification['regulation_id'] = $regulation_id;

          $this->db->where('regulation_id',$regulation_id);

          $LOG = array();
          $LOG['source_module'] = 'Regulations';
          $LOG['record_id'] = $regulation_id;
          $LOG['created_on'] = date("Y-m-d h:i:s");
          $LOG['created_by'] = $this->user;
          $LOG['action_taken'] = 'INSERT DATA REGULATIONS';
          $LOG['text'] = 'ADD REGULATION WITH TITLE '.$data['title'];
          $this->insert_data('user_log_history',$LOG);

          $result =  $this->db->update('cps_regulations_tat_notifications',$notification);

          

          if($result){
            return true;
          }
        else{
            return false;
        }

    }
    else{
        return false;
    }

  }

  public function get_user_logData($regulation_id){
      $this->db->select('CONCAT(ap.admin_fname," ", ap.admin_lname) as created_by,crn.created_date as date,notification_title as title');
      $this->db->from('cps_regulation_notification as crn');
      $this->db->join('admin_profile as ap','ap.uidnr_admin=crn.created_by','left');
      $this->db->where('crn.regulation_id',$regulation_id);
      $this->db->order_by('crn.notification_id','DESC');
      $result = $this->db->get();

      if($result->num_rows()>0){
          return $result->result();
      }
      else{
          return false;
      }

  }

  // public function get_regulation_log($regulation_id)
  //   {
  //   $query = $this->db->select('action_taken, created_on as taken_at, text,concat(admin_fname," ",admin_lname) as taken_by')
  //                       ->join('admin_profile','user_log_history.created_by = admin_profile.uidnr_admin')
  //                       ->where('source_module','regulation')
  //                       ->where('record_id',$regulation_id)
  //                       ->order_by('id','desc')
  //                       ->get(' user_log_history');
  //   if($query->num_rows() > 0){
  //       return $query->result_array();
  //   }
  //   return [];
  //   }

 
    
    public function get_log_data($id)
    {
     
      $where = array();
      $where['ul.source_module'] = 'Regulations';
      $where['ul.record_id'] = $id;
  
      $this->db->select('ul.action_taken,ul.created_on as taken_at,ul.text, CONCAT(ap.admin_fname," ",ap.admin_lname) as taken_by');
      $this->db->from('user_log_history ul');
      $this->db->join('admin_profile ap','ul.created_by = ap.uidnr_admin','left');
      $this->db->order_by('ul.id','DESC');
      $this->db->where($where);
      $result = $this->db->get();
      // echo $this->db->last_query();die;
      if($result->num_rows()>0){
        return $result->result();
      }
      else{
        return false;
      }
    
  
    }
}