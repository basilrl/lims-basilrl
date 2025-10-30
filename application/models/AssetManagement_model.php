<?php
defined('BASEPATH') or exit('No direct access allowed');

class AssetManagement_model extends MY_Model
{
    function __construct()
    {
        parent::__construct();
    }

    public function get_branch_list($limit = NULL, $start = NULL, $search = NULL, $sortby, $order, $where, $count = NULL)
    {
        if ($sortby != NULL || $order != NULL) {
            $this->db->order_by($sortby, $order);
        } else {
            $this->db->order_by('am.asset_id', 'desc');
        }
        if ($where) {
            $this->db->where($where);
        }
        if ($search != NULL && $search != 'NULL') {
            $search = trim($search);
            $this->db->group_start();
            $this->db->or_like('am.asset_name', $search);
            $this->db->like('am.asset_code', $search);
            $this->db->or_like('msc.country_name', $search);
             $this->db->or_like('ass.employee_name', $search);
             $this->db->or_like('am.assign_flag', $search);
            $this->db->or_like('msb.branch_name', $search);
            $this->db->or_like('ap.admin_fname', $search);
            $this->db->or_like('am.created_on', $search);
            $this->db->group_end();
        }
        $query = $this->db->select('am.asset_id,am.assign_flag, am.asset_code,am.asset_name,am.asset_make,am.asset_model, am.status,  msc.country_name,msb.branch_name, ass.employee_name ,ass.employee_id,ass.status as emp_status, taa.assigned_id,taa.assign_status,taa.tbl_assign_flag, msp.province_name,  ap.admin_fname, am.created_on')
            ->from('tbl_assets as am')
            ->join('admin_profile as ap', 'ap.uidnr_admin = am.created_by', 'left')
            ->join('mst_provinces msp', 'msp.province_id = am.state_id', 'left')
            ->join('mst_country msc', 'msc.country_id = am.country_id', 'left')
            ->join('mst_branches msb', 'msb.branch_id = am.branch_id', 'left')
            ->join('tbl_asset_assigned taa', '(taa.asset_id = am.asset_id and taa.assign_status="Assigned" and taa.tbl_assign_flag=1)', 'left')
            ->join('assets_user ass', '(ass.employee_id = taa.employee_id and taa.assign_status="Assigned")', 'left')
            ->limit($limit, $start)
            ->group_by('asset_id')
            ->get();

           //echo $this->db->last_query(); 
        if ($count) {
            return $query->num_rows();
        } else {
            if ($query->num_rows() > 0)
                return $query->result();
            else
                return false;
        }
    }


    public function get_user_list($limit = NULL, $start = NULL, $search = NULL, $sortby, $order, $where, $count = NULL)
    {
        if ($sortby != NULL || $order != NULL) {
            $this->db->order_by($sortby, $order);
        } else {
            $this->db->order_by('em.employee_id', 'desc');
        }
        if ($where) {
            $this->db->where($where);
        }
        if ($search != NULL && $search != 'NULL') {
            $search = trim($search);
            $this->db->group_start();
            $this->db->or_like('em.employee_name', $search);
            $this->db->like('em.employee_contact', $search);
            $this->db->or_like('msc.country_name', $search);
            $this->db->or_like('msd.division_name', $search);
            $this->db->or_like('ap.admin_fname', $search);
            $this->db->or_like('em.created_on', $search);
            $this->db->group_end();
        }
    $emp = $this->db->select('em.employee_id,em.asset_id,ass.assign_flag, em.employee_contact,em.employee_name,em.emp_id,em.employee_mail,em.division_id,em.employee_designation,em.status,msc.country_name,msd.division_name,ass.asset_id, taa.assigned_id,taa.assign_status, msp.province_name,  ap.admin_fname, em.created_on')
            ->from('assets_user as em')
            ->join('admin_profile as ap', 'ap.uidnr_admin = em.created_by', 'left')
            ->join('mst_provinces msp', 'msp.province_id = em.state_id', 'left')
            ->join('mst_country msc', 'msc.country_id = em.country_id', 'left')
            ->join('tbl_assets ass', 'ass.asset_id = em.asset_id', 'left')
            ->join('tbl_asset_assigned taa', 'taa.asset_id = em.asset_id', 'left')
            ->join('mst_divisions msd', 'msd.division_id = em.division_id', 'left')
            ->limit($limit, $start)
            ->group_by('employee_id')
            ->get();
//echo $this->db->last_query(); 
        if ($count) {
            return $emp->num_rows();
        } else {
            if ($emp->num_rows() > 0)
                return $emp->result();
            else
                return false;
        }
    }



    

    public function fetch_asset_code()
    {
        $query = $this->db->select('am.asset_code')
            ->from('tbl_assets am')
            ->order_by('am.asset_code', 'ASC')
            ->get();
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    public function fetch_emp_name()
    {
        $query = $this->db->select('em.employee_id, em.employee_name,em.employee_contact')
            ->from('assets_user as em')
            ->where('em.status', '1')
            ->order_by('em.employee_name', 'ASC')
            ->get();
           // echo $this->db->last_query();die;
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }
    public function  fetch_branch_name()
    {
        $query = $this->db->select('am.asset_id, am.asset_name,am.asset_code')
            ->from('tbl_assets am')
            ->where('am.status', '1')
            ->order_by('am.asset_name', 'ASC')
            ->get();
           // echo $this->db->last_query();die;
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }



    public function fetch_brn_division()
    {
        $query = $this->db->select('msd.division_id, msd.division_name')
            ->from('mst_divisions msd')
            ->where('msd.status', '1')
            ->order_by('msd.division_name', 'ASC')
            ->get();
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    public function fetch_created_person()
    {
        $query = $this->db->select('CONCAT(ap.admin_fname, " ", ap.admin_lname) as created_by, ap.uidnr_admin')
            ->from('admin_profile ap')
            ->order_by('ap.admin_fname', 'asc')
            ->get();
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    public function fetch_country()
    {
        $query = $this->db->select('msc.country_id, msc.country_name')
            ->from('mst_country msc')
            ->where('status', '1')
            ->order_by('msc.country_name', 'ASC')
            ->get();
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    public function fetch_state()
    {
        $query = $this->db->select('msb.branch_id, msb.branch_name')
            ->from('mst_branches msb')
            ->order_by('msb.branch_name', 'ASC')
            ->get();
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }


    public function fetch_emp()
    {
        $query = $this->db->select('emp.employee_id, emp.employee_name,emp.emp_id')
            ->from('assets_user emp')
            ->where('status=','1')
            ->order_by('emp.employee_name', 'ASC')
            ->get();
           // echo $this->db->last_query();
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }



   
    

    public function fetch_asset()
    {
        $query = $this->db->select('tas.asset_id, tas.asset_name,tas.asset_code')
            ->from('tbl_assets tas')
            ->where('assign_flag != ',3  )
            ->where('tas.status=',1  )
            ->order_by('tas.asset_name', 'ASC')
            ->get();
             $this->db->last_query();
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

  
    public function fetch_emp_id($asset_id){
        $query = $this->db->select('taa.employee_id')
            ->from('tbl_asset_assigned taa')
            ->where('tbl_assign_flag',1)
            ->where('asset_id',$asset_id)
           // ->order_by('tas.asset_name', 'ASC')
            ->get();
            // $this->db->last_query();
        if ($query->num_rows() > 0) {
            return $query->row();
        } else {
            return false;
        }
    }

    public function fetch_id($asset_id){
        $query = $this->db->select('taa.employee_id')
            ->from('tbl_asset_assigned taa')
            ->where('assigned_id',$asset_id)
            ->get();
            // $this->db->last_query();
        if ($query->num_rows() > 0) {
            return $query->row();
        } else {
            return false;
        }
    }

    public function update_branch_status($branch_id)
    {
        $query = $this->db->get_where('mst_branches', $branch_id);
        $row = $query->row();
        if ($row->status == '0')
            $post = array('status' => '1');
        else
            $post = array('status' => '0');
        $this->db->update('mst_branches', $post, $branch_id);
        if ($this->db->affected_rows() > 0)
            return $post;
        else
            return false;;
    }

    public function get_branch_log($asset_id)
    {
        $query = $this->db->select('action_taken, created_on as taken_at, text,concat(admin_fname," ",admin_lname) as taken_by')
            ->join('admin_profile', 'user_log_history.created_by = admin_profile.uidnr_admin')
            ->where('source_module', 'AssetManagement')
            ->where('record_id', $asset_id)
            ->order_by('id', 'desc')
            ->get(' user_log_history');
            //echo  $this->db->last_query();die;
        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return false;
        }
    }


    public function Assigned_history($asset_id)
    {
        $query = $this->db->select('assign_status as action_taken, 	added_on as taken_at,employee_name as text,concat(admin_fname," ",admin_lname) as taken_by')
            ->join('admin_profile', 'tbl_asset_assigned.added_by = admin_profile.uidnr_admin')
            ->join('assets_user', 'tbl_asset_assigned.employee_id = assets_user.employee_id')
            ->where('tbl_asset_assigned.asset_id', $asset_id)
            ->order_by('assigned_id', 'desc')
            ->get('tbl_asset_assigned');
           // echo  $this->db->last_query();die;
        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return false;
        }
    }

    public function Assigned_emp_history($employee_id)
    {
        $query = $this->db->select('assign_status as action_taken, 	added_on as taken_at,asset_name as asset_name,concat(admin_fname," ",admin_lname) as taken_by')
            ->join('admin_profile', 'tbl_asset_assigned.added_by = admin_profile.uidnr_admin')
            ->join('tbl_assets', 'tbl_asset_assigned.asset_id = tbl_assets.asset_id')
            ->where('tbl_asset_assigned.employee_id', $employee_id)
            ->order_by('assigned_id', 'desc')
            ->get('tbl_asset_assigned');
//echo  $this->db->last_query();die;
        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return false;
        }
    }


    public function get_assigned_user($asset_id)
    {
        $query = $this->db->select('employee_name,employee_mail,employee_designation,emp_id,employee_contact, created_on as taken_at, text,concat(admin_fname," ",admin_lname) as taken_by')
            ->join('admin_profile', 'user_log_history.created_by = admin_profile.uidnr_admin')
            ->where('asset_id', $asset_id)
            ->get('assets_user');
//echo  $this->db->last_query();die;
        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return false;
        }
    }


    public function get_emp_log($employee_id)
    {
        $query = $this->db->select('action_taken, created_on as taken_at, text,concat(admin_fname," ",admin_lname) as taken_by')
            ->join('admin_profile', 'user_log_history.created_by = admin_profile.uidnr_admin')
            ->where('source_module', 'AssetManagement')
            ->where('record_id', $employee_id)
            ->order_by('id', 'desc')
            ->get(' user_log_history');
           // echo  $this->db->last_query();die;
        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return false;
        }
    }

    public function fetch_branch_for_edit($data)
    {
        $query = $this->db->select('msb.*')
            ->from('mst_branches msb')
            ->get();
        if ($query) {
            return $query->row();
        } else {
            return false;
        }
    }


   public function fetch_branch(){
    $query = $this->db->select('mb.branch_id, mb.branch_name')
    ->from('mst_branches mb')
    ->order_by('mb.branch_name', 'ASC')
    ->get();
   // echo $this->db->last_query();
if ($query->num_rows() > 0) {
    return $query->result();
} else {
    return false;
}
   }



   public function fetch_assign_data($asset_id){
    $query = $this->db->select('aa.assigned_id, aa.assign_status,aa.asset_id')
    ->from('tbl_asset_assigned aa')
    ->where('asset_id', $asset_id)
    ->where('tbl_assign_flag',1)
    ->get();
   if ($query->num_rows() > 0) {
    return $query->row_array();
} else {
    return false;
}
   }


}



