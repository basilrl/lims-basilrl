<?php

class Opportunity_model extends MY_Model
{

    public function __construct()
    {
        parent::__construct();
        $checkUser = $this->session->userdata('user_data');
        $this->user = $checkUser->uidnr_admin;
        $this->userdata=$checkUser;
    }

    public function fetch_opportunity_list($limit = NULL, $start = NULL, $search = NULL, $sortby, $order, $where, $count = NULL)
    {
        $this->db->limit($limit, $start);
        if ($sortby != NULL || $order != NULL) {
            $this->db->order_by($sortby, $order);
        } else {
            $this->db->order_by('opp.opportunity_id', 'desc');
        }
        if ($where) {
            $this->db->where($where);
        }
        if ($search != NULL) {
            $search = trim($search);
            $this->db->group_start();
            $this->db->like('opp.opportunity_name', $search);
            $this->db->or_like('opp.opportunity_customer_type', $search);
            $this->db->or_like('opp.opportunity_value', $search);
            $this->db->or_like('opp.estimated_closure_date', $search);
            $this->db->or_like('opp.opportunity_status', $search);
            $this->db->or_like('ap.uidnr_admin', $search);
            $this->db->or_like('opp.created_on', $search);
            $this->db->or_like('opp.types', $search);
            $this->db->or_like('CONCAT(ap.admin_fname,"", ap.admin_lname)',$search);
            $this->db->group_end();
        }
        if($this->userdata->role_name!='National Sale Head' && $this->userdata->role_name!='Super Admin')
        {
            $this->db->where('opp.created_by',$this->user);
        }
        
        $query = $this->db->select('opp.opportunity_id, opp.opportunity_name, opp.opportunity_value, opp.description, opp.types, opp.opportunity_customer_type,
         opp.opportunity_value, opp.estimated_closure_date, cust.customer_name as customer_name, cont.contact_name as contact_name, opp.opportunity_status,CONCAT(ap.admin_fname, " ", ap.admin_lname) as created_by, 
         opp.created_on, ap.uidnr_admin, ap.admin_fname, curncy.currency_name as currency_name')
            ->from('opportunity as opp')
            ->join('admin_profile as ap', 'ap.uidnr_admin = opp.created_by', 'left')
            ->join('cust_customers cust', 'cust.customer_id = opp.opportunity_customer_id', 'left')
            ->join('contacts cont', 'cont.contact_id = opp.opportunity_contact_id', 'left')
            ->join('mst_currency as curncy', 'curncy.currency_id = opp.currency_id', 'left')
            ->get();
        if ($count) {
            return $query->num_rows();
        } else {
            if ($query->num_rows() > 0)
                return $query->result();
            else
                return false;
        }
    }

    public function update_opportunity_status($opportunity_id)
    {
        $query = $this->db->get_where('contacts', $opportunity_id);
        $row = $query->row();
        if ($row->status == '0')
            $post = array('status' => '1');
        else
            $post = array('status' => '0');
        $this->db->update('contacts', $post, $opportunity_id);
        if ($this->db->affected_rows() > 0)
            return $post;
        else
            return false;;
    }

    public function fetch_opportunity_name()
    {
        $query = $this->db->select('opportunity_name')
            ->from('opportunity')
            ->get();
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    public function fetch_opportunity_types()
    {
        $query = $this->db->select('distinct(opp.types)')
            ->from('opportunity as opp')
            ->get();
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    public function fetch_opportunity_status()
    {
        $query = $this->db->select('distinct(opp.opportunity_status)')
            ->from('opportunity as opp')
            ->get();
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    public function fetch_created_person()
    {
        $query = $this->db->select('CONCAT(ap.admin_fname," ", ap.admin_lname) as created_by, ap.uidnr_admin')
            ->from('admin_profile as ap')
            ->order_by('ap.admin_fname','ASC')
            ->get();
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    public function update_won_mark_values($update_data)
    {
        $query = $this->db->where('opportunity_id', $update_data['opportunity_id'])
            ->update('opportunity', array('closure_value' => $update_data['closure_value'], 'closure_note' => $update_data['closure_note'], 'updated_on' => $update_data['updated_on'], 'updated_by' => $update_data['updated_by'],  'opportunity_status' => 'Won'));
        if ($query) {
            return true;
        } else {
            return false;
        }
    }

    public function update_loss_mark($update_data)
    {
        $query = $this->db->where('opportunity_id', $update_data['opportunity_id'])
            ->update('opportunity', array('closure_note' => $update_data['closure_note'], 'updated_on' => $update_data['updated_on'], 'updated_by' => $update_data['updated_by'], 'opportunity_status' => 'Lost'));
        if ($query) {
            return true;
        } else {
            return false;
        }
    }

    public function fetch_currency()
    {
        $query = $this->db->select('msc.currency_id, msc.currency_code, msc.currency_name, msc.exchange_rate')
            ->from('mst_currency as msc')
            ->get();
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    public function extract_cust_name($fetch_type, $customer_id=NULL)
    {
        $this->db->select('customer_name, customer_id, customer_type')
            ->from('cust_customers')
            ->where('customer_type', $fetch_type)
            ->where('isactive', 'Active');
            if($customer_id!=NULL && $customer_id !=''){
                $this->db->where('cust.customer_id',$customer_id);
            }
        $query = $this->db->get();       
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    public function op_assign_to($designation_id2, $designation_id1)
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

    public function extract_cont_name($fetch_type){
        $query = $this->db->select('cont.contact_name, cont.contact_id')
                ->from('contacts as cont')
                ->where('cont.contacts_customer_id', $fetch_type)
                ->get();
        if($query->num_rows() > 0){
            return $query->result();
        }
        else{
            return false;
        }   
    }

    public function fetch_opp_details($opp_id){
        $query = $this->db->select('opp.opportunity_id, opp.opportunity_name, opp.opportunity_customer_type, opp.types, opp.opportunity_value, opp.estimated_closure_date, opp.opportunity_status, CONCAT(ap.admin_fname, " ", ap.admin_lname) as created_by, opp.created_on, opp.op_assigned_to, opp.description, opp.currency_id, opp.opportunity_customer_id, opp.opportunity_contact_id, opp.opp_quote_ref_no') // updated by millan on 14-10-2021
                ->from('opportunity opp') 
                ->join('admin_profile as ap', 'ap.uidnr_admin = opp.created_by', 'left') 
                // ->join('quotes qu', 'qu.quote_id = opp.opp_quote_id', 'inner') // added by millan on 14-10-2021 opp.opp_quote_id, qu.reference_no, qu.quote_id
                ->where('opp.opportunity_id', $opp_id)
                ->get();
        if($query->num_rows() > 0){
            return $query->row();
        }
        else{
            return false;
        }
    }

    public function get_user_logData($communication_id){
        $this->db->select('CONCAT(ap.admin_fname," ", ap.admin_lname) as created_by, comm.created_on as date');
        $this->db->from('comm_communications comm');
        $this->db->join('admin_profile as ap', 'ap.uidnr_admin = comm.created_by', 'left');
        $this->db->where('comm.communication_id', $communication_id);
        $this->db->order_by('comm.communication_id', 'DESC');
        $result = $this->db->get();
        if ($result->num_rows() > 0) {
            return $result->result();
        } else {
            return false;
        }
    }

    public function get_OPPOTUNITY_log($opportunity_id)
    {
  
      $where = array();
      $where['ul.source_module'] = 'Opportunity';
      $where['ul.record_id'] = $opportunity_id;
  
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
    
    // added by prashant on 08-10-2021
    public function fetch_communication_details($fetch_id){
        $query = $this->db->select('cc.communication_id, cc.subject, cc.date_of_communication, cc.medium, cc.customer_type, cc.communication_mode, cc.connected_to,  cust.customer_name as customer_name, cont.contact_name as contact_name, CONCAT(ap.admin_fname, " ", ap.admin_lname) as created_by, 
        cc.created_on')
                ->from('comm_communications as cc')
                ->join('admin_profile as ap', 'ap.uidnr_admin = cc.created_by', 'left')
                ->join('cust_customers as cust', 'cust.customer_id = cc.comm_communications_customer_id', 'left')
                ->join('contacts as cont', 'cont.contact_id = cc.comm_communications_contact_id', 'left')
                ->where('cc.comm_communications_opportunity_id' , $fetch_id)
                ->get();
                //echo $this->db->last_query(); die;
            if($query->num_rows() > 0){
            return $query->result_array();
        }
        else{
            return false;
        }
    }

    // added by millan on 14-10-2021
    public function fetch_quote_reference_data(){
        $query = $this->db->select('qu.quote_id, qu.reference_no')
            ->from('quotes qu')
            ->get();
        if($query->num_rows() > 0){
            return $query->result();
        }else{
            return false;
        }
    }

    public function get_quote_reference($key){
        $this->db->select('qu.quote_id id, qu.reference_no full_name, qu.reference_no name');
        ($key!=null) ? $this->db->like('qu.reference_no',$key):'';
        $this->db->order_by('qu.quote_id','desc');
        $this->db->limit(30);
        $query = $this->db->get('quotes qu');
        if($query->num_rows() > 0){
            return $query->result();
        }else{
            return [];
        }
    }
    // ends by millan on 14-10-2021
}
