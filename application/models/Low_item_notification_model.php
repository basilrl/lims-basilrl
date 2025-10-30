<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Low_item_notification_model extends MY_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public  function getCategory_list($per_page, $page = 0, $search, $where, $count = NULL){
        // SELECT store_id AS master_store_id, store_name AS master_store_name,DATE_FORMAT(stores.created_on,'%d-%m-%Y') as created_on, (SELECT branch_name FROM mst_branches WHERE branch_id = store_branch_id) AS store_branches,store_branch_id, (SELECT CONCAT(admin_fname,' ',admin_lname) FROM admin_profile WHERE uidnr_admin = store_store_keeper_id AND store_store_keeper_id!=0) AS store_keeper FROM stores where is_deleted = 0
        if ($per_page != NULL || $page != NULL) {
            $this->db->limit($per_page, $page);
        }
        $this->db->order_by('master_items.item_id', 'DESC');
        if (!empty($where)) {
            $this->db->group_start();
            $this->db->where($where);
            $this->db->group_end();
        }
        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('master_items.item_name', $search);
            // $this->db->or_like('units.unit', $search);
            $this->db->or_like('stores.store_name', $search);
            $this->db->group_end();
        }
        $this->db->group_start();
        $this->db->where(['current_stock.current_stock < master_items.min_quantity_required' => null]);
        $this->db->group_end();
        $this->db->select('stores.store_name,master_items.item_id,master_items.item_name, master_items.min_quantity_required AS min_quantity_required ,current_stock.current_stock as current_stock,a.unit as min_quantity_required_unit_name,b.unit as current_stock_unit_name, ir.item_req_id, ir.lab_manager_status, ir.lab_manager_id, ir.lab_manager_reason, ir.lmgm_approved_by, ir.lmgm_approved_on, ir.aws_path ');
        $this->db->join('mst_category', 'mst_category.category_id  = master_items .category_id', 'left');
        $this->db->join('units a', 'a.unit_id = master_items.unit', 'left');
        $this->db->join('current_stock', 'master_items.item_id = current_stock.item_id');
        $this->db->join('stores', 'stores.store_id =current_stock.store_id', 'left');
        $this->db->join('units b', 'b.unit_id = current_stock.current_stock_unit', 'left');
        $this->db->join('item_requirement ir', 'ir.item_id = master_items.item_id', 'left');
        $this->db->from('master_items');
        $result = $this->db->get();
        if ($count == '1') {
            return $result->num_rows();
        } else {
            if ($result->num_rows() > 0) {
                return $result->result();
            } else {
                return false;
            }
        }
    }

    public function store_userlog_dtlsview($id)
    {
        $this->db->select('store_log.store_log_id,store_log.action_message,store_log.log_activity_on,CONCAT( admin_profile.admin_fname," ", admin_profile.admin_lname ) as created_by');
        $this->db->join('admin_profile', 'admin_profile.uidnr_admin= store_log.uidnr_admin', 'left');
        $this->db->group_start();
        $this->db->where(['store_log.item_id' => $id]);
        $this->db->group_end();
        $this->db->order_by('store_log.store_log_id', 'DESC');
        $query = $this->db->get('store_log');
        // echo $this->db->last_query();exit;
        if ($query->result())
            return $query->result();
        else
            return false;
    }

    // added by millan on 10-03-2021
    public function fetch_units(){
        $query = $this->db->select('uns.unit_id, uns.unit')
            ->from('units as uns')
            ->get();
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    // added by millan on 10-03-2021
    public function fetch_item_details($item_id){
        $query = $this->db->select('mi.item_id, mi.item_name, mi.min_quantity_required, msc.category_name, un.unit, mi.unit')
                ->from('master_items mi')
                ->join('mst_category msc', 'msc.category_id = mi.category_id', 'left')
                ->join('units un', 'un.unit_id = mi.unit', 'left')
                ->where('mi.item_id', $item_id)
                ->get();
        if($query->num_rows() > 0){
            return $query->row();
        }
        else{
            return false;
        }
    }

    // added by millan on 10-03-2021
    public function fetch_item_requirement_details($item_req_id){
        $query = $this->db->select('im.*')
                ->from('item_requirement im')
                ->where('im.item_req_id',$item_req_id)
                ->get();
        if($query->num_rows() > 0){
            return $query->row();
        }
        else{
            return false;
        }
    }

    // added by millan on 10-03-2021
    public function fetch_data_for_report($item_req_id){
        $query = $this->db->select('ir.*, au.admin_email, ads.sign_path as lm_sign, un.unit, CONCAT(ap.admin_fname," ", ap.admin_lname) as created_by, CONCAT(ap.admin_fname," ", ap.admin_lname) as updated_by, msd.designation_name')
                ->from('item_requirement as ir')
                ->join('units un', 'un.unit_id = ir.unit_id', 'left')
                ->join('admin_profile ap', 'ap.uidnr_admin = ir.created_by', 'left')
                ->join('admin_users au','au.uidnr_admin = ir.lab_manager_id', 'left')
                ->join('admin_signature ads','ads.admin_id = ir.created_by', 'left')
                ->join('mst_designations msd','msd.designation_id = au.id_admin_role', 'left')
                ->where('ir.item_req_id', $item_req_id)
                ->get();
        if($query->num_rows() > 0){
            return $query->row();
        }
        else{
            return false;
        }
    } 

    // added by millan on 16-03-2021
    public function fetch_for_mail($item_req_id){
        $query = $this->db->select('im.*, str.store_name, au.admin_email, CONCAT(ap.admin_fname, " ", ap.admin_lname) as created_by, au.uidnr_admin')
                ->from('item_requirement im')
                ->join('current_stock cs', 'im.item_id = cs.item_id', 'left')
                ->join('stores str', 'str.store_id = cs.store_id', 'left')
                ->join('admin_users au', 'au.uidnr_admin = str.store_store_keeper_id', 'left')
                ->join('admin_profile ap', 'ap.uidnr_admin = im.created_by', 'left')
                ->where('im.item_req_id',$item_req_id)
                ->get();
        if($query->num_rows() > 0){
            return $query->row();
        }
        else{
            return false;
        }
    }

    // added by millan on 16-03-2021
    public function lm_status($item_req_id, $status, $approved_by){
        $query = $this->db->where('item_requirement.item_req_id', $item_req_id)
                ->update('item_requirement', array('lab_manager_status'=> $status, 'lab_manager_id' => $approved_by ));
        if($query){
            return true;
        }
        else{
            return false;
        }
    }

    // added by millan on 19-03-2021
    public function fetch_signature($item_req_id){
        $query = $this->db->select('ir.lab_manager_status, au.admin_email, ads.sign_path as lm_sign')
                ->from('item_requirement ir')
                ->join('admin_users au','au.uidnr_admin = ir.lab_manager_id', 'left')
                ->join('admin_signature ads','ads.admin_id = ir.lab_manager_id', 'left')
                ->where('ir.item_req_id', $item_req_id)
                ->get();
        if($query->num_rows() > 0){
            return $query->row();
        }
        else{
            return false;
        }
    }   

    // added by millan on 22-03-2021
    public function get_designation_gm(){
        $query = $this->db->select('au.uidnr_admin, au.admin_email, msd.designation_name')
                ->from('admin_users au')
                ->join('mst_designations msd', 'msd.designation_id = au.id_admin_role', 'left')
                ->get();
        if( $query->num_rows() > 0 ){
            return $query->row();
        }
        else{
            return false;
        }
    }

    // added by millan on 23-03-2021
    public function fetch_vendor($item_req_id){
        $query = $this->db->select('ir.item_name, msc.category_name, supp.vendor_name, supp.email' )
                ->from('item_requirement ir')
                ->join('master_items mii', 'mii.item_id = ir.item_id', 'left')
                ->join('mst_category msc', 'msc.category_id = mii.category_id', 'left')
                ->join('suppliers supp', 'supp.product_ids = mii.item_id', 'left')
                ->where('ir.item_req_id', $item_req_id)
                ->get('');
        if($query->num_rows() > 0){
            return $query->result();
        }
        else{
            return false;
        }
    }

    // added by millan on 002-april-2021
    public function fetch_attachment_path($item_req_id){
        $query = $this->db->select('ir.aws_path')
                ->from('item_requirement ir')
                ->where('ir.item_req_id', $item_req_id)
                ->get('');
        if($query->num_rows() > 0){
            return $query->row();
        }
        else{
            return false;
        }
    }
}
