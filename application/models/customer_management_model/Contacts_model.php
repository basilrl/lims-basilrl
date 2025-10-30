<?php

class Contacts_model extends MY_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function fetch_contacts_list($limit = NULL, $start = NULL, $search = NULL, $sortby, $order, $where, $count = NULL)
    {
        if ($sortby != NULL || $order != NULL) {
            $this->db->order_by($sortby, $order);
        } else {
            $this->db->order_by('contact.contact_id', 'desc');
        }
        if ($where) {
            $this->db->where($where);
        }
        if ($search != NULL && $search != 'NULL') {
            $search = trim($search);
            $this->db->group_start();
            $this->db->like('contact.contact_name', $search);
            $this->db->or_like('contact.customer_type', $search);
            $this->db->or_like('cust.customer_name', $search);
            $this->db->or_like('ap.admin_fname', $search);
            $this->db->or_like('contact.type', $search);
            $this->db->or_like('contact.email', $search);
            $this->db->or_like('contact.mobile_no', $search);
            $this->db->group_end();
        }
        if(exist_val('Branch/Wise',$this->session->userdata('permission'))){
			$multibranch = $this->session->userdata('branch_ids');
            $this->db->group_start();
			$this->db->where(['cust.mst_branch_id IN ('.$multibranch.') '=> null]); //branch_id
            $this->db->group_end();
		}
        $query = $this->db->select('contact.customer_type, cust.customer_name, contact.contact_id, contact.contact_name, contact.email, contact.mobile_no, contact.type, contact.status, CONCAT(ap.admin_fname, " ", ap.admin_lname) as created_by, contact.created_on, contact.contact_id, ap.uidnr_admin')
            ->from('contacts as contact')
            ->join('admin_profile as ap', 'ap.uidnr_admin = contact.created_by', 'left')
            ->join('cust_customers as cust', 'cust.customer_id = contact.contacts_customer_id', 'left')
            ->limit($limit, $start)
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

    public function update_contact_status($contact_id)
    {
        $query = $this->db->get_where('contacts', ['contact_id'=>$contact_id]);
        $row = $query->row();

        if ($row->status == '0'){
            $post = array('status' => '1');
        }
        else{
            $post = array('status' => '0');
        }
          
         $this->db->where('contact_id',$contact_id);
         $result = $this->db->update('contacts',$post);
   
        if ($result){
            return true;
        }
          else{
              return false;
          }


    }

    public function fetch_cust_name(){
        $this->db->select('cust.customer_name')->from('cust_customers as cust');
        if(exist_val('Branch/Wise',$this->session->userdata('permission'))){
			$multibranch = $this->session->userdata('branch_ids');
                $this->db->group_start();
			    $this->db->where(['cust.mst_branch_id IN ('.$multibranch.') '=> null]); //branch_id
                $this->db->group_end();
		}
                    $query = $this->db->get();
        if($query->num_rows() > 0){
            return $query->result();
        } else {
            return false;
        }
    }

    public function fetch_contact_name(){
        $this->db->select('contact.contact_name')->from('contacts as contact')->join('cust_customers as cust', 'cust.customer_id = contact.contacts_customer_id', 'left');
        if(exist_val('Branch/Wise',$this->session->userdata('permission'))){
			$multibranch = $this->session->userdata('branch_ids');
            $this->db->group_start();
			$this->db->where(['cust.mst_branch_id IN ('.$multibranch.') '=> null]); //branch_id
             $this->db->group_end();
		}
        $query = $this->db->get();
        if($query->num_rows() > 0){
            return $query->result();
        } else {
            return false;
        }
    }

    public function fetch_created_person()
    {
        $query = $this->db->select('CONCAT(ap.admin_fname, " ", ap.admin_lname) as created_by, ap.uidnr_admin')
            ->from('admin_profile as ap')
            ->get();
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    public function fetch_country()
    {
        $query = $this->db->select('country_id, country_code, country_name')
            ->from('mst_country')
            ->where('status', '1')
            ->get();
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    public function extract_cust_name($fetch_type, $customer_id = NULL)
    {
        $this->db->select('cust.customer_name, cust.customer_id')
            ->from('cust_customers cust')
            ->where('cust.isactive', 'Active')
            ->where('cust.customer_type', $fetch_type);
            if($customer_id!=NULL && $customer_id !=''){
                $this->db->where('cust.customer_id',$customer_id);
            }
            if(exist_val('Branch/Wise',$this->session->userdata('permission'))){
                $multibranch = $this->session->userdata('branch_ids');
                  $this->db->group_start();
                $this->db->where(['cust.mst_branch_id IN ('.$multibranch.') '=> null]); //branch_id
                $this->db->group_end();
            }
        $query = $this->db->get();       
        if($query->num_rows() > 0){
            return $query->result();
        } else {
            return false;
        }
    }

    public function extract_state($fetch_id)
    {
        $query = $this->db->select('state.province_name, state.province_id')
            ->from('mst_provinces as state')
            ->where('state.mst_provinces_country_id', $fetch_id)
            ->get();
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    public function fetch_cont_details($contact_id)
    {
        $query = $this->db->select('contacts.*')
            ->from('contacts')
            ->where('contacts.contact_id', $contact_id)
            ->get();
        if ($query->num_rows() > 0) {
            return $query->row();
        } else {
            return false;
        }
    }

    public function get_contact_log_CONTACT($contact_id)
  {

    $where = array();
    $where['ul.source_module'] = 'Contacts';
    $where['ul.record_id'] = $contact_id;

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
