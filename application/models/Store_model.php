<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Store_model extends MY_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public  function get_role_list($per_page, $page = 0, $search, $where, $count = NULL)
    {

        // SELECT store_id AS master_store_id, store_name AS master_store_name,DATE_FORMAT(stores.created_on,'%d-%m-%Y') as created_on, (SELECT branch_name FROM mst_branches WHERE branch_id = store_branch_id) AS store_branches,store_branch_id, (SELECT CONCAT(admin_fname,' ',admin_lname) FROM admin_profile WHERE uidnr_admin = store_store_keeper_id AND store_store_keeper_id!=0) AS store_keeper FROM stores where is_deleted = 0
        if ($per_page != NULL || $page != NULL) {
            $this->db->limit($per_page, $page);
        }
        $this->db->order_by('stores.store_id', 'DESC');
        if (!empty($where)) {
            $this->db->group_start();
            $this->db->where($where);
            $this->db->group_end();
        }
        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('stores.store_name', $search);
            $this->db->or_like('CONCAT(admin_profile.admin_fname," ",admin_profile.admin_lname)', $search);
            $this->db->or_like('mst_branches.branch_name', $search);
            $this->db->group_end();
        }
        $this->db->select('CONCAT(admin_profile.admin_fname," ",admin_profile.admin_lname) as store_keeper,stores.*,mst_branches.branch_name as store_branches');
        $this->db->join('mst_branches','mst_branches.branch_id = stores.store_branch_id','left');
        $this->db->join('admin_profile','admin_profile.uidnr_admin = stores.store_store_keeper_id','left');
        $this->db->group_start();
        $this->db->where('is_deleted',0);
        $this->db->group_end();
        $this->db->from('stores');
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
    public function branch_Store()
    {
        $checkUser = $this->session->userdata('user_data');
        $user_id = $checkUser->uidnr_admin;
        if ($user_id == 1) {
           $where = $this->get_result('user_branch_branch_id','user_branch');
        }else{
            $where = $this->get_result('user_branch_branch_id','user_branch',['user_branch_uidnr_admin'=>$user_id]);
        }
            $this->db->select('branch_id, branch_name');
            $this->db->order_by('branch_name', 'ASC');
            if ($where) {
                $this->db->where_in('branch_id',array_column($where,'user_branch_branch_id'));
            }
        $query = $this->db->get('mst_branches');
        if ($query->result())
            return $query->result();
        else
            return false;
    }

    public function store_keeper_store()
    {
        
            $where = $this->get_row('designation_id','mst_designations',['LOWER(designation_name)'=>strtolower('Store Keeper')]);
            if ($where) {
                 $where = $this->get_result('uidnr_admin','operator_profile',['admin_designation'=>$where->designation_id]);
            }
        $this->db->select("CONCAT(admin_fname,' ',admin_lname) AS store_keeper_name,uidnr_admin as store_keeper_id");
        if ($where) {
            $this->db->where_in('uidnr_admin',array_column($where,'uidnr_admin'));
        }
        $this->db->order_by('admin_fname', 'ASC');
        $query = $this->db->get('admin_profile');
        if ($query->result())
            return $query->result();
        else
            return false;
    }
   
}
