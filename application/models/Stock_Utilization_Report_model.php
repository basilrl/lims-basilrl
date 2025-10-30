<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Stock_Utilization_Report_model extends MY_Model
{

    public function __construct()
    {
        parent::__construct();
    }


    public  function get_list($per_page, $page = 0, $data, $count = NULL)
    {
        if ($per_page != NULL || $page != NULL) {
            $this->db->limit($per_page, $page);
        }
        if (!empty($where)) {
            $this->db->group_start();
            $this->db->where($where);
            $this->db->group_end();
        }
        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('admin_role.admin_role_name', $search);
            $this->db->group_end();
        }
        $this->db->select("a.stock_id,a.str_stock_item_id AS item,a.store_id AS store_id, DATE_FORMAT(a.stock_added_date,'%Y-%m-%d') AS consumption_dates,DATE_FORMAT(a.stock_added_date,'%d-%m-%Y') AS consumption_date, (SELECT SUM(item_quantity) FROM stock_info WHERE operation=2 AND str_stock_item_id=".$data['item_id']." AND store_id=".$data['store_id']." AND DATE_FORMAT(stock_added_date,'%Y-%m-%d')=consumption_dates) AS quantity, (SELECT current_stock FROM stock_info WHERE str_stock_item_id=".$data['item_id']." AND DATE_FORMAT(stock_added_date,'%Y-%m-%d')=consumption_dates AND store_id=".$data['store_id']." ORDER BY stock_added_date ASC LIMIT 1) AS opening_stock, (CASE a.operation WHEN 1 THEN (SELECT item_quantity+current_stock FROM stock_info WHERE str_stock_item_id=".$data['item_id']." AND DATE_FORMAT(stock_added_date,'%Y-%m-%d')=consumption_dates AND store_id=".$data['store_id']." ORDER BY stock_id DESC LIMIT 1) WHEN 2 THEN (SELECT current_stock FROM stock_info WHERE str_stock_item_id=".$data['item_id']." AND DATE_FORMAT(stock_added_date,'%Y-%m-%d')=consumption_dates AND store_id=".$data['store_id']." ORDER BY stock_id DESC LIMIT 1) WHEN 3 THEN (SELECT item_quantity+current_stock FROM stock_info WHERE str_stock_item_id=".$data['item_id']." AND DATE_FORMAT(stock_added_date,'%Y-%m-%d')=consumption_dates AND store_id=".$data['store_id']." ORDER BY stock_id DESC LIMIT 1) WHEN 4 THEN (SELECT current_stock-item_quantity FROM stock_info WHERE str_stock_item_id=".$data['item_id']." AND DATE_FORMAT(stock_added_date,'%Y-%m-%d')=consumption_dates AND store_id=".$data['store_id']." ORDER BY stock_id DESC LIMIT 1) ELSE 0 END) AS closing_stock, (SELECT unit FROM units u WHERE u.unit_id=stock_item_unit) AS unit");
        $this->db->from('stock_info a');
        $this->db->where(['a.str_stock_item_id'=>$data['item_id'],'a.store_id'=>$data['store_id'],'a.operation'=>'2']);
        $this->db->where(['DATE_FORMAT(stock_added_date,"%Y-%m-%d") >='=>$data['start_date'],'DATE_FORMAT(stock_added_date,"%Y-%m-%d") <='=>$data['end_date']]);
        $this->db->group_by('consumption_dates');
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
    public function fetch_controller()
    {
        $query = $this->db->distinct()->select('controller_name')->get('functions');
        if ($query->result())
            return $query->result();
        else
            return false;
    }

    public function fetch_functions($post)
    {
        $query = $this->db->distinct('function_name')->get_where('functions',$post);
        if ($query->result())
            return $query->result();
        else
            return false;
    }
    public function fetch_permission($post)
    {
        $data = array();
        $query = $this->db->select('function_id')->where($post)->get('set_permission');
        if ($query->row_array()) {
            $result = $query->row_array();
            $data = explode(',', $result['function_id']);
            return $data;
        } else
            return false;
    }
    public function save_permission($roleID, $functionID)
    {
        $data = array('role_id' => $roleID, 'function_id' => $functionID);
        $prev_perm_id = $this->db->select('permission_id')->where('role_id', $roleID)->get('set_permission')->row_array();
        if ($prev_perm_id) {
           $update = $this->db->update('set_permission', $data, ['permission_id'=>$prev_perm_id['permission_id']]);
            if ($update) {
                return true;
            } else {
                return false;
            }
        } else {
            $query = $this->db->insert('set_permission', $data);
            if ($query) {
                return true;
            } else {
                return false;
            }
        }
    }
}
