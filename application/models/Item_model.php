<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Item_model extends MY_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public  function getCategory_list($per_page, $page = 0, $search, $where, $count = NULL)
    {

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
            $this->db->like('mst_category.category_name', $search);
            $this->db->or_like('CONCAT(admin_profile.admin_fname," ",admin_profile.admin_lname)', $search);
            $this->db->or_like('units.unit', $search);
            $this->db->or_like('master_items.item_name', $search);
            $this->db->group_end();
        }
        $this->db->select('item_id,item_name,units.unit,units.unit as unit_name,mst_category.category_name  as category_name,master_items.min_quantity_required');
        // $this->db->join('admin_profile','admin_profile.uidnr_admin = mst_category.created_by','left');
        $this->db->join('mst_category', 'mst_category.category_id  = master_items .category_id', 'left');
        $this->db->join('units', 'units.unit_id = master_items.unit', 'left');
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
    public function category()
    {
        $this->db->select('category_id,category_name');
        $this->db->order_by('category_name', 'ASC');
        $query = $this->db->get('mst_category');
        if ($query->result())
            return $query->result();
        else
            return false;
    }

    public function unit()
    {
        $this->db->select('unit_id, unit');
        $this->db->order_by('unit_id', 'DESC');
        $query = $this->db->get('units');
        if ($query->result())
            return $query->result();
        else
            return false;
    }
}
