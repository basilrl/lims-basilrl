<?php

class User_model extends MY_Model
{

    public $status;
    public $roles;

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
        $this->status = $this->config->item('status');
        $this->roles = $this->config->item('roles');
    }

    public function insertUser($d)
    {
        $string = array(
            'first_name' => $d['firstname'],
            'last_name' => $d['lastname'],
            'email' => $d['email'],
            'role' => $this->roles[0],
            'password' => '',
            'last_login' => ''
        );

        $q = $this->db->insert_string('users', $string);
        $this->db->query($q);
        return $this->db->insert_id();
    }
    public function get_count_users($table)
    {
        $this->db->select('COUNT(*) as count');
        $this->db->from($table);
        $query = $this->db->get()->row_array();
        return $query['count'];
    }
    public function isDuplicate($email)
    {
        $this->db->get_where('users', array('email' => $email), 1);
        return $this->db->affected_rows() > 0 ? TRUE : FALSE;
    }

    public function insertToken($user_id)
    {
        $token = substr(sha1(rand()), 0, 30);
        $date = date('Y-m-d');

        $string = array(
            'token' => $token,
            'user_id' => $user_id,
            'created' => $date
        );
        $query = $this->db->insert_string('tokens', $string);
        $this->db->query($query);
        return $token . $user_id;
    }

    public function isTokenValid($token)
    {
        $tkn = substr($token, 0, 30);
        $uid = substr($token, 30);

        $q = $this->db->get_where('tokens', array(
            'tokens.token' => $tkn,
            'tokens.user_id' => $uid
        ), 1);

        if ($this->db->affected_rows() > 0) {
            $row = $q->row();

            $created = $row->created;
            $createdTS = strtotime($created);
            $today = date('Y-m-d');
            $todayTS = strtotime($today);

            if ($createdTS != $todayTS) {
                return false;
            }

            $user_info = $this->getUserInfo($row->user_id);
            return $user_info;
        } else {
            return false;
        }
    }

    public function getUserInfo($id)
    {
        $q = $this->db->get_where('users', array('id' => $id), 1);
        if ($this->db->affected_rows() > 0) {
            $row = $q->row();
            return $row;
        } else {
            error_log('no user found getUserInfo(' . $id . ')');
            return false;
        }
    }

    public function updateUserInfo($post)
    {
        $data = array(
            'password' => $post['password'],
            'last_login' => date('Y-m-d h:i:s A'),
            'status' => $this->status[1]
        );
        $this->db->where('id', $post['user_id']);
        $this->db->update('users', $data);
        $success = $this->db->affected_rows();

        if (!$success) {
            error_log('Unable to updateUserInfo(' . $post['user_id'] . ')');
            return false;
        }

        $user_info = $this->getUserInfo($post['user_id']);
        return $user_info;
    }

    public function checkLogin($post)
    {
        $where['admin_users.admin_username'] = $post['email'];
        $where['admin_users.admin_password'] = md5($post['password']);
        $where['admin_users.admin_active'] = '1';
      //  $post['roles.status'] = '1';

        $query = $this->db->select('admin_users.*,CONCAT(admin_profile.admin_fname, ,admin_profile.admin_lname) as username ,admin_role.id_admin_role,admin_role.admin_role_name as role_name,operator_profile.default_branch_id as branch_id')
            ->from('admin_users')
            ->join('admin_role', 'admin_users.id_admin_role = admin_role.id_admin_role')
            ->join('admin_profile','admin_profile.uidnr_admin=admin_users.uidnr_admin')
            ->join('operator_profile','operator_profile.uidnr_admin=admin_users.uidnr_admin')
            ->where($where)
            ->get();
            // echo $this->db->last_query();die;

        if ($query->num_rows() == 1) {

            $userInfo = $query->row();
            $this->updateLoginTime($userInfo->uidnr_admin);
            $this->permission($userInfo->id_admin_role);
            $branch = $this->get_row('GROUP_CONCAT(user_branch_branch_id) as ids','user_branch',['user_branch_uidnr_admin'=>$userInfo->uidnr_admin]);
            if ($branch) {
                $this->session->set_userdata('branch_ids',$branch->ids);
            }
        } else {
            error_log('Unsuccessful login attempt(' . $post['email'] . ')');
            return false;
        }

        unset($userInfo->password);
        return $userInfo;
    }

    public function updateLoginTime($id)
    {
       // $this->db->where('id', $id);
        $this->db->insert('admin_user_login_history', array('ulh_logintime' => date('Y-m-d h:i:s A'),
            'UserId' => $id));
        return;
    }
    public function permission($role_id)
    {
        $this->db->select('(CONCAT(functions.controller_name,"/",functions.function_name)) as func');
        $this->db->join('functions','FIND_IN_SET(functions.function_id,set_permission.function_id) <> 0', 'left', false);
        $this->db->where('set_permission.role_id',$role_id);
        $result = $this->db->get('set_permission');
        if ($result->num_rows() > 0) {            
            $result = $result->result();
            $dummy = array();
            foreach ($result as $key => $value) {
                $dummy[]=$value->func;
            }
        }else{
            $dummy = array();
        }
        $this->session->set_userdata('permission',$dummy);
        
    }

    public function getUserInfoByEmail($email)
    {
        $q = $this->db->get_where('users', array('email' => $email), 1);
        if ($this->db->affected_rows() > 0) {
            $row = $q->row();
            return $row;
        } else {
            error_log('no user found getUserInfo(' . $email . ')');
            return false;
        }
    }

    public function updatePassword($post)
    {
        $this->db->where('id', $post['user_id']);
        $this->db->update('users', array('password' => $post['password']));
        $success = $this->db->affected_rows();

        if (!$success) {
            error_log('Unable to updatePassword(' . $post['user_id'] . ')');
            return false;
        }
        return true;
    }
    public function getuserlistCount($search = NULL, $where = array(),$name,$email)
    {
    //    echo $name;
    //    print_r($email);
        if ($where && count($where) > 0) {
            // $this->db->group_start();
            $this->db->where($where);
            // $this->db->group_end();
        }
       
        if ($name != 'NULL' || $email != 'NULL') {
            
            $this->db->like('u.admin_username', $name);
        
                $this->db->or_like('u.admin_email', $email);

       
     
    }
        $query = $this->db->select('u.*,as.sign_path')
            ->from('admin_users u ')
            ->join('admin_role r ', 'u.id_admin_role=r.id_admin_role ','left')
            ->join('admin_profile p' , 'u.uidnr_admin = p.uidnr_admin ', 'left')
            ->join('operator_profile op' , 'op.uidnr_admin=u.uidnr_admin','left')
            ->join('mst_designations ds' , 'op.admin_designation = ds.designation_id', 'left')
            ->join('mst_divisions dv' , 'dv.division_id=u.default_division_id ','left')
            ->join('admin_signature as' , 'as.admin_id=u.uidnr_admin ','left')
            
            ->get();
            // echo $this->db->last_query();die;
            

        if ($query)
            return $query->num_rows();
        else
            return false;
    }
    public function getuserlist($limit = NULL, $start = NULL, $search = NULL, $sortby = NULL, $order = NULL, $where = NULL, $count = NULL)
    {
        
        if ($sortby != NULL || $order != NULL) {
            $this->db->order_by($sortby, $order);
        } else {
            $this->db->order_by('u.uidnr_admin', 'DESC');
        }

        if ($where) {
            $this->db->where($where);
        }

        if ($search != NULL && $search != 'NULL') {
            $search = trim($search);
            $this->db->group_start();
            $this->db->like('op.employee_no', $search);
            $this->db->or_like("CONCAT( p.admin_fname,' ',p.admin_lname)", $search);
            $this->db->or_like('u.admin_email', $search);
            $this->db->or_like('r.admin_role_name', $search);
            $this->db->or_like('ds.designation_name', $search);
            $this->db->or_like('u.crm_flag', $search);
            $this->db->or_like('dept.dept_name', $search);
            $this->db->group_end();
        }



        $result = $this->db->select("u.uidnr_admin,u.default_division_id,dv.division_name as default_division_name,
                CONCAT(p.admin_fname,' ',p.admin_lname) AS admin_name,
                u.admin_username, u.admin_email, u.admin_active,r.admin_role_name,
                IF(u.admin_active<>'0', 'disable_user', 'enable_user') AS status_icon,'enabled' AS editAction,
                p.admin_telephone,op.employee_no,
                ds.designation_name,p.ap_signing_auth as signing_authority,as.sign_path,p.ap_signing_auth,
                u.lab_analyst,u.crm_flag,dept.dept_id,dept.dept_name,u.admin_active,dv.division_name,
                branch.branch_name, 
                p.stamp_path")  /*ADDED ON 09-08-2021 BY KAPRI*/
            ->from('admin_users u ')
            ->join('admin_role r ', 'u.id_admin_role=r.id_admin_role ','left')
            ->join('admin_profile p' , 'u.uidnr_admin = p.uidnr_admin ', 'left')
            ->join('operator_profile op' , 'op.uidnr_admin=u.uidnr_admin','left')
            ->join('mst_designations ds' , 'op.admin_designation = ds.designation_id', 'left')
            ->join('mst_divisions dv' , 'dv.division_id=u.default_division_id ','left')
            ->join('admin_signature as' , 'as.admin_id=u.uidnr_admin ','left')
            ->join('mst_departments dept','dept.dept_id = op.dept_id','left')
            ->join('mst_branches branch','branch.branch_id = op.default_branch_id','left')
            ->limit($limit, $start)
        
            ->get();
    
            if ($result->num_rows() > 0) {
                if ($count != NULL) {
                    return $result->num_rows();
                } else {
                    return $result->result_array();
                }
            } else {
                return false;
            }
    }

    public function save_user_data($admin_users,$admin_profile,$operator_profile,$division_data,$branch_data,$lab_data,$profile_image){
        $abc = array_merge($admin_profile,$profile_image);
        // echo "<pre>"; print_r($abc); die;
        $this->db->trans_begin();
        // Save admin user table data
    
        $save_admin_user = $this->db->insert('admin_users',$admin_users);
       
        if($save_admin_user){
            $admin_id = $this->db->insert_id(); 
            
            if($admin_id){
                // Save admin profile table data
                $abc['uidnr_admin'] = $admin_id;
                $save_admin_profile = $this->db->insert('admin_profile',$abc);
                if($save_admin_profile){
                      
                     // Save operator profile table data
                    $operator_profile['uidnr_admin'] = $admin_id;
                    $this->db->insert('operator_profile',$operator_profile);
                }


                
                // Save user division table data
                foreach($division_data as $key => $division){
                    $division_data['user_division_uidnr_admin'] = $admin_id;
                    $division_data['user_division_div_id'] = $division;
                    $this->db->insert('user_divisions',$division_data);
                }
                

                // Save user lab table data
                foreach($lab_data as $key => $labs){
                    $labs_data['user_labs_uidnr_admin'] = $admin_id;
                    $labs_data['user_labs_lab_id'] = $labs;
                    $this->db->insert('user_labs',$labs_data);
                }

                // Save brach table data
                foreach($branch_data as $key => $branches){
                    $branches_data['user_branch_uidnr_admin'] = $admin_id;
                    $branches_data['user_branch_branch_id'] = $branches;
                    $this->db->insert('user_branch',$branches_data);
                }
            }


        }
        

        // Commit the process
        if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            return $result = array('success' => false);
        } else{
            $this->db->trans_commit();
            return $result = array('success' => true,'admin_id' => $admin_id);
        }
    }


    public function deleteUser($get)
    {
        $this->db->where('id', $get['User_id']);
        $query = $this->db->delete('users');
        return $query;
    }

    public function fetch_user_for_edit($post)
    {
        $query = $this->db->get_where('users', $post);
        if ($query) {
            $result = $query->row();
        
            // print_r($result);die;
            return $result;
        }
    }

    public function fetch_User_type()
    {
        $this->db->select('role_id,name');
        $query = $this->db->get('roles');
        if ($query->result())
            return $query->result();
        else
            return false;
    }

    public function updateUser($post)
    {
        if (isset($post['state_id']) || isset($post['state'])) {
            if ($post['state_id']) {
                $post['state_id'] = $post['state_id'];
            } else {
                $new_state = array('state_name' => $post['state'], 'country_id' => $post['country_id']);
                $this->db->insert('mst_state', $new_state);
                $post['state_id'] = $this->db->insert_id();
            }
            unset($post['state']);
        }
       
        $this->db->where('id', $post['id']);
        $status = $this->db->update('users', $post);
// print_r($this->db->last_query());die;
        if ($status) {
            return true;
        }
    }

    public function update_user_status($user_id)
    {
        $q = $this->db->get_where('users', $user_id);
        $row = $q->row();
        if ($row->status == '0')
            $post = array('status' => '1');
        else
            $post = array('status' => '0');
        $this->db->update('users', $post, $user_id);
        if ($this->db->affected_rows() > 0)
            return $post;
        else
            return false;;
    }

    /*----------------------------------- added by millan on 04-03-2020 ------------------------------------------*/
    public function upload_sign($post, $where)
    {
        $status = $this->db->update('users', $post, $where);
        //      echo $this->db->last_query();die;
        if ($status) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /*-------------------------------------- added by millan on 12-03-2020 ---------------------------------------*/
    public function fetch_sign($post)
    {
        $attachments = $this->db->query("select signature_path,concat(first_name,' ',last_name) as user_name from users where id={$post['id']}");
        $data = $attachments->row_array();
        if ($data)
            return $data;
        else
            return false;
    }


   
    // changes by jyoti
    public function change_password($id, $post)
    {
        // $data = $this->input->post('password');
        $this->db->where('id', $id);
        $query =   $this->db->update('users', $post);
        if ($query) {
            return $query;
        } else {
            return false;
        }
    }
    public function getRoles()
    {
        $roles =  $this->db->get('roles');
        if ($roles) {
            return $roles->result();
        } else {
            return false;
        }
    }
    public function fetch_states(){
      $query =  $this->db->get('mst_state');
        if($query->result())
        return $query->result();
        else
        return false;
    }
    public function fetch_branch(){
      $query =  $this->db->get('mst_branch');
        if($query->result())
        return $query->result();
        else
        return false;
    }

    public function get_user_by_id($id){
        $this->db->select('*');
        $this->db->join('admin_profile','admin_profile.uidnr_admin = admin_users.uidnr_admin','left');
        $this->db->join('operator_profile','operator_profile.uidnr_admin = admin_users.uidnr_admin','left');
        $this->db->join('user_divisions','user_divisions.user_division_uidnr_admin = admin_users.uidnr_admin','left');
        $query = $this->db->get_where('admin_users',['admin_users.uidnr_admin' => $id]);
        if($query->num_rows() > 0){
            return $query->row();
        } else{
            return [];
        }
    }

    public function get_labs_by_user($id){
        $this->db->select('user_labs_lab_id'); 
        $query = $this->db->get_where('user_labs',['user_labs_uidnr_admin' => $id]);
        if($query->num_rows() > 0){
            return $query->result_array();
        }
        return [];
    }

    public function get_branch_by_user($id){
        $this->db->select('user_branch_branch_id');
        $query = $this->db->get_where('user_branch',['user_branch_uidnr_admin' => $id]);
        if($query->num_rows() > 0){
            return $query->result_array();
        }
        return [];
    }

    public function get_devision_by_user($id){
        $this->db->select('user_division_div_id');
        $query = $this->db->get_where('user_divisions',['user_division_uidnr_admin' => $id]);
        if($query->num_rows() > 0){
            return $query->result_array();
        }
        return [];
    }

    public function update_user_data($id,$admin_users,$admin_profile,$operator_profile,$division_data,$branch_data,$lab_data){
      
        
        $this->db->trans_begin();
        // Check user email 
        $check_email = $this->db->get_where('admin_users',['admin_email' => $admin_users['admin_email']]);
        if($check_email->num_rows() == 1){
            // Update admin users table data
            $update_admin_users = $this->db->update('admin_users',$admin_users,['uidnr_admin' => $id]);
            // echo $this->db->last_query(); die;
            
            // Update user profile table data
            $update_admin_profile = $this->db->update('admin_profile',$admin_profile,['uidnr_admin' => $id]);
            
            // update operator profile table data
            $update_operator_profile = $this->db->update('operator_profile',$operator_profile,['uidnr_admin' => $id]);

            // update division table data
            $delete_user_division = $this->db->delete('user_divisions',['user_division_uidnr_admin' => $id]);
            foreach($division_data as $key => $divisions){
                $divisions_data['user_division_uidnr_admin'] = $id;
                $divisions_data['user_division_div_id'] = $divisions;
                $save_division_data = $this->db->insert('user_divisions',$divisions_data);
            }

            // update branch table data
            $delete_user_branch = $this->db->delete('user_branch',['user_branch_uidnr_admin' => $id]);
            foreach($branch_data as $key => $branches){
                $branches_data['user_branch_uidnr_admin'] = $id;
                $branches_data['user_branch_branch_id'] = $branches;
                $save_branch_data = $this->db->insert('user_branch',$branches_data);
            }

            // update lab table data
            $delete_user_lab = $this->db->delete('user_labs',['user_labs_uidnr_admin' => $id]);
            foreach($lab_data as $key => $labs){
                $labs_data['user_labs_uidnr_admin'] = $id;
                $labs_data['user_labs_lab_id'] = $labs;
                $save_lab_data = $this->db->insert('user_labs',$labs_data);
            }
           
            // Commit the process
            if ($this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
                return $result = array('success' => false, "message" => "Something went wrong!.");
            } else{
                $this->db->trans_commit();
                return $result = array('success' => true,  "message" => "User updated successfully.");
            }
        } else {
            return $status = array("success" => false, "message" => "Email already exist.");
        }
        
    }
public function insert_data_signature($table,$data){
    $result = $this->db->insert($table,$data);
		if($result){
			return true;
		}
		else{
			return false;
		}
}

public function get_user_log($user_id)
    {
    $query = $this->db->select('action_taken, created_on as taken_at, text,concat(admin_fname," ",admin_lname) as taken_by')
                        ->join('admin_profile','user_log_history.created_by = admin_profile.uidnr_admin')
                        ->where('source_module','Users')
                        ->where('record_id',$user_id)
                        ->order_by('id','desc')
                        ->get(' user_log_history');
    if($query->num_rows() > 0){
        return $query->result_array();
    }
    return [];
    }



    public function get_login_history($limit = NULL, $start = NULL, $search = NULL, $sortby = NULL, $order = NULL, $where = NULL, $count = NULL){

                 
        if ($sortby != NULL || $order != NULL) {
            $this->db->order_by($sortby, $order);
        } else {
            $this->db->order_by('created_on', 'DESC');
        }

        if ($where) {
            $this->db->where($where);
        }

        if ($search != NULL && $search != 'NULL') {
            $search = trim($search);
            $this->db->group_start();
            $this->db->group_end();
        }

        $result = $this->db->select("*")
            ->from('login_user_history')
            ->limit($limit, $start)
            ->get();

        if ($result->num_rows() > 0) {
            if ($count != NULL) {
                return $result->num_rows();
            } else {
                return $result->result_array();
            }
        } else {
            return false;
        }
    }
}
