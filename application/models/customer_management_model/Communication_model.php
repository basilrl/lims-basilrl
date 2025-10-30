<?php

class Communication_model extends MY_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function fetch_communication_list($limit = NULL, $start = NULL, $search = NULL, $sortby, $order, $where, $count = NULL)
    {
        $this->db->limit($limit, $start);
        if ($sortby != NULL || $order != NULL) {
            $this->db->order_by($sortby, $order);
        } else {
            $this->db->order_by('comm.communication_id', 'desc');
        }
        if ($where) {
            $this->db->where($where);
        }
        if ($search != NULL) {
            $search = trim($search);
            $this->db->group_start();
            $this->db->like('contact.contact_name', $search);
            $this->db->or_like('comm.customer_type', $search);
            $this->db->or_like('cust.customer_name', $search);
            $this->db->or_like('comm.connected_to', $search);
            $this->db->or_like('opportunity.opportunity_name', $search);
            $this->db->or_like('ap.admin_fname', $search);
            $this->db->or_like('comm.created_on', $search);
            $this->db->or_like('comm.communication_mode', $search);
            $this->db->or_like('comm.subject', $search);
            $this->db->or_like('comm.medium', $search);
            $this->db->or_like('CONCAT(ap.admin_fname,"", ap.admin_lname)',$search);
            $this->db->group_end();
        }
        $query = $this->db->select('comm.customer_type, cust.customer_name, comm.communication_id, comm.comm_communications_contact_id, CONCAT(ap.admin_fname, " ", ap.admin_lname) as contact, comm.subject, comm.date_of_communication, comm.communication_mode, comm.medium, comm.connected_to, CONCAT(ap.admin_fname, " ", ap.admin_lname) as created_by, comm.created_on, contact.contact_name, opportunity.opportunity_name, comm.note, comm.follow_up_date')
            ->from('comm_communications as comm')
            ->join('admin_profile as ap', 'ap.uidnr_admin = comm.created_by', 'left')
            ->join('cust_customers as cust', 'cust.customer_id = comm.comm_communications_customer_id', 'left')
            ->join('contacts as contact', 'contact.contact_id = comm.comm_communications_contact_id', 'left')
            ->join('opportunity', 'opportunity.opportunity_id = comm.comm_communications_opportunity_id', 'left')
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

    public function update_communication_status($communication_id)
    {
        $query = $this->db->get_where('comm_communications', $communication_id);
        $row = $query->row();
        if ($row->status == '0')
            $post = array('status' => '1');
        else
            $post = array('status' => '0');
        $this->db->update('comm_communications', $post, $communication_id);
        if ($this->db->affected_rows() > 0)
            return $post;
        else
            return false;;
    }

    public function fetch_cust_name()
    {
        $query = $this->db->select('cust.customer_name, cust.customer_id')
            ->from('cust_customers as cust')
            ->get();
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    public function fetch_contact_name()
    {
        $query = $this->db->select('contact.contact_id, contact.contact_name')
            ->from('contacts as contact')
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
    public function fetch_opportunity_name()
    {
        $query = $this->db->select('opportunity_id, opportunity_name, opportunity_name as full_name')
            ->from('opportunity')
            ->order_by('opportunity_name','ASC')
            ->get();
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }
    public function fetch_add_opportunity_name()
    {
        $query = $this->db->select('opportunity_id, opportunity_name, opportunity_name as full_name')
            ->from('opportunity')
            ->order_by('opportunity_name','ASC')
            ->get();
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    public function fetch_comm_medium()
    {
        $query = $this->db->select('medium')
        ->distinct('medium')
        ->from('comm_communications')
        ->get();
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    public function extract_cust_name($fetch_type){
        $query = $this->db->select('cust.customer_name, cust.customer_id')
            ->from('cust_customers as cust')
            ->where('cust.customer_type', $fetch_type)
            ->where('cust.isactive', 'Active')
            ->get();
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }
    //Added by Prashant 01-10-2021-----------
    public function fetch_extract_cust_name($fetch_type, $fetch_cust_id){
        $query = $this->db->select('cust.customer_name, cust.customer_id')
            ->from('cust_customers as cust')
            ->where('cust.customer_type', $fetch_type)
            ->where('cust.customer_id', $fetch_cust_id)
            ->where('cust.isactive', 'Active')
            ->get();
            
        if ($query->num_rows() > 0) {
            return $query->row();
        } else {
            return false;
        }
    }

    public function extract_cont_name($fetch_type){
        $query = $this->db->select('cont.contact_name, cont.contact_id')
            ->from('contacts as cont')
            ->where('cont.contacts_customer_id', $fetch_type)
            ->get();
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }
    //Added by Prashant 01-10-2021-----------
    public function fetch_extract_cont_name($fetch_cust_id, $fetch_contact_id){
        $query = $this->db->select('cont.contact_name, cont.contact_id')
            ->from('contacts as cont')
            ->where('cont.contacts_customer_id', $fetch_cust_id)
            ->where('cont.contact_id', $fetch_contact_id)
            ->get();
        if ($query->num_rows() > 0) {
            return $query->row();
        } else {
            return false;
        }
    }

    /* added by prashant on 27-09-2021 */
    public function fetch_comm_details($comm_id){
        $query = $this->db->select('comm.* , opp.opportunity_name, opp.opportunity_name as full_name')
            ->from('comm_communications as comm')
            ->join('opportunity as opp', 'opp.opportunity_id = comm.comm_communications_opportunity_id', 'left')
            ->where('comm.communication_id', $comm_id)
            ->get();
        if ($query->num_rows() > 0) {
            return $query->row();
        } else {
            return false;
        }
    }
    
    public function fetch_comm_add_details($comm_id){
        $query = $this->db->select('comm.* , opp.opportunity_name, opp.opportunity_name as full_name')
            ->from('comm_communications as comm')
            ->join('opportunity as opp', 'opp.opportunity_id = comm.comm_communications_opportunity_id', 'left')
            ->where('comm.communication_id', $comm_id)
            ->get();
        if ($query->num_rows() > 0) {
            return $query->row();
        } else {
            return false;
        }
    }
    /* added by prashant on 27-09-2021 */

    public function communication_data_export($query){
        $res = $this->db->query($query)->result();
        if($res && count($res) > 0){
            return $res;
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

    public function get_communication_log_COMMUNICATIONS($communication_id)
    {
  
      $where = array();
      $where['ul.source_module'] = 'Communication';
      $where['ul.record_id'] = $communication_id;
  
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

    //added by prashant on 12-10-2021
    public function fetch_opportunity_details($fetch_id){
        $query = $this->db->select('opp.opportunity_id, opp.opportunity_name, opp.description, opp.types, opp.opportunity_customer_type,
        opp.opportunity_value, opp.estimated_closure_date, cust.customer_name as customer_name, cont.contact_name as contact_name, opp.opportunity_status,CONCAT(ap.admin_fname, " ", ap.admin_lname) as created_by, 
        opp.created_on, ap.uidnr_admin, ap.admin_fname')
                ->from('opportunity as opp')
                ->join('admin_profile as ap', 'ap.uidnr_admin = opp.created_by', 'left')
                ->join('cust_customers as cust', 'cust.customer_id = opp.opportunity_customer_id', 'left')
                ->join('contacts as cont', 'cont.contact_id = opp.opportunity_contact_id', 'left')
                ->where('opp.opportunity_id' , $fetch_id)
                ->get();
            //echo $this->db->last_query(); die;    
            if($query->num_rows() > 0){
            return $query->result_array();
        }
        else{
            return false;
        }
    }
    public function add_fetch_communication_data($fetch_id){
        $query = $this->db->select('comm.customer_type, cust.customer_name, comm.communication_id, comm.comm_communications_contact_id, CONCAT(ap.admin_fname, " ", ap.admin_lname) as contact, comm.subject, comm.date_of_communication, comm.communication_mode, comm.medium, comm.connected_to, CONCAT(ap.admin_fname, " ", ap.admin_lname) as created_by, comm.created_on, contact.contact_name, opportunity.opportunity_name, comm.note, comm.follow_up_date')
                ->from('comm_communications as comm')
                ->join('admin_profile as ap', 'ap.uidnr_admin = comm.created_by', 'left')
                ->join('cust_customers as cust', 'cust.customer_id = comm.comm_communications_customer_id', 'left')
                ->join('contacts as contact', 'contact.contact_id = comm.comm_communications_contact_id', 'left')
                ->join('opportunity', 'opportunity.opportunity_id = comm.comm_communications_opportunity_id', 'left')
                ->where('comm.communication_id' , $fetch_id)
                ->get();
        if($query->num_rows() > 0){
            return $query->result_array();
        }
        else{
            return false;
        }
    }
}
