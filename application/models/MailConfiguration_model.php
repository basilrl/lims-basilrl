<?php
defined('BASEPATH') or exit('No Direct access allowed');

class MailConfiguration_model extends MY_Model
{
    function __construct()
    {
        parent::__construct();
    }
    public function get_mailconfiguration($limit = 5, $start = 0, $search = NULL, $sortby, $order, $where, $count = NULL){
        $this->db->limit($limit, $start);
        if ($sortby != NULL || $order != NULL) {
            $this->db->order_by($sortby, $order);
        } else {
            $this->db->order_by('mail_conf_id', 'desc');
        }
        
        $this->db->where($where); 
        
        
        if ($search != NULL) {
            $this->db->like('c_email', $search);
        }



        $this->db->select('mail_conf_id, lab_location_id, product_destination_id, mail_configuration.status, c_email, DATE_FORMAT(mail_configuration.created_on,"%d-%m-%Y") as created_on, m1.country_name as lab_location, m2.country_name as product_destination, admin.admin_fname')
             ->from('mail_configuration')
             ->join('mst_country as m1', 'm1.country_id=mail_configuration.lab_location_id', 'inner')
             ->join('mst_country as m2', 'm2.country_id=mail_configuration.product_destination_id', 'inner')
             ->join('admin_profile  as admin', 'admin.uidnr_admin=mail_configuration.created_by ', 'inner');


             
         $query = $this->db->get();
         //echo $this->db->last_query(); die;
        if ($count === '1') {
            return $query->num_rows();
        } else {
            if ($query->result_array()) {
                return $query->result_array();
            } else {
                return false;
            }
        }
        
        
    }

    public function delete_mailconfiguration($id) {
        
        $id1 = $this->db->where('mail_conf_id', $id);
        $query = $this->db->delete('mail_configuration');
        return $query;
    }

    public function get_lab_location($id)
    {
        //echo $id;die;
        $this->db->select('country_id, country_code, country_name');
        if($id != NULL) {
            $this->db->where('country_id', $id);
        }
        
        $this->db->where('lab_location_flag', '1');
        $query = $this->db->get('mst_country');
        if($query->num_rows() > 0){
            return $query->result_array();
        }
        return [];
    }
    public function get_product_destination($id)
    {
        $this->db->select('country_id, country_code, country_name');
        if($id != NULL) {
            $this->db->where('country_id', $id);
        }
        $this->db->where('product_destination_flag', '1');
        $query = $this->db->get('mst_country');
        if($query->num_rows() > 0){
            return $query->result_array();
        }
        return [];
    }
    
}
?>