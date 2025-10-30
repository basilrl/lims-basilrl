<?php

class Website_Operation_model extends MY_Model
{

    public function __construct()
    {
        parent::__construct();
    }
    public function get_operation_list($per_page, $page = 0, $search, $where, $count = NULL)
    {
        if ($per_page != NULL || $page != NULL) {
            $this->db->limit($per_page, $page);
        }
        $this->db->order_by('front_functions.function_id', 'DESC');
        if (!empty($where)) {
            $this->db->group_start();
            $this->db->where($where);
            $this->db->group_end();
        }
        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('front_functions.controller_name', $search);
            $this->db->or_like('front_functions.function_name', $search);
            $this->db->or_like('front_functions.alias', $search);
            $this->db->group_end();
        }
        $result = $this->db->get('front_functions');
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

    public function get_operation_log($operation_id)
    {
    $query = $this->db->select('action_taken, created_on as taken_at, text,concat(admin_fname," ",admin_lname) as taken_by')
                        ->join('admin_profile','user_log_history.created_by = admin_profile.uidnr_admin')
                        ->where('source_module','Website_Operation')
                        ->where('record_id',$operation_id)
                        ->order_by('id','desc')
                        ->get('user_log_history');
    if($query->num_rows() > 0){
        return $query->result_array();
    }
    return [];
    }
}
