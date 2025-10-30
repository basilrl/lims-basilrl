<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Users extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->check_session();
        $this->load->model('User_model');
        $this->load->library('form_validation');
        $this->load->library('pagination');
    }
    public function index()
    {
        
        $where=NULL;
        $search = NULL;
        $sortby=NULL;
        $order=NULL;

		$base_url = 'Users/index';


        if ($this->uri->segment('3') != NULL && $this->uri->segment('3') != 'NULL') {
            $crm_flag_id = $this->uri->segment('3');
			$data['crm_flag_id'] =  base64_decode($crm_flag_id);
			$base_url .= '/' . $crm_flag_id;
			$where['u.crm_flag'] = base64_decode($crm_flag_id);

		} else {
			$base_url .= '/NULL';
			$data['crm_flag_id'] = NULL;
		}
        
        if ($this->uri->segment('4') != NULL && $this->uri->segment('4') != 'NULL') {
            $lab_analyst_id = $this->uri->segment('4');
			$data['lab_analyst_id'] =  base64_decode($lab_analyst_id);
			$base_url .= '/' . $lab_analyst_id;
			$where['u.lab_analyst'] = base64_decode($lab_analyst_id);
		} else {
            $base_url .= '/NULL';
			$data['lab_analyst_id'] = NULL;
		}
       
        if ($this->uri->segment('5') != NULL && $this->uri->segment('5') != 'NULL') {
            $division_id = $this->uri->segment('5');
			$data['division_id'] =  base64_decode($division_id);
			$base_url .= '/' . $division_id;
			$where['dv.division_id'] = base64_decode($division_id);
		} else {
            $base_url .= '/NULL';
			$data['division_id'] = NULL;
		}
    
        if ($this->uri->segment('6') != NULL && $this->uri->segment('6') != 'NULL') {
            $dept_id = $this->uri->segment('6');
			$data['dept_id'] =  base64_decode($dept_id);
			$base_url .= '/' . $dept_id;
			$where['dept.dept_id'] = base64_decode($dept_id);
		} else {
            $base_url .= '/NULL';
			$data['dept_id'] = NULL;
		}

        if ($this->uri->segment('7') != NULL && $this->uri->segment('7') != 'NULL') {
            $branch_id = $this->uri->segment('7');
			$data['branch_id'] =  base64_decode($branch_id);
			$base_url .= '/' . $branch_id;
			$where['branch.branch_id'] = base64_decode($branch_id);
		} else {
            $base_url .= '/NULL';
			$data['branch_id'] = NULL;
		}
        if ($this->uri->segment('8') != NULL && $this->uri->segment('8') != 'NULL') {
            $username = $this->uri->segment('8');
			$data['username'] =  base64_decode($username);
			$base_url .= '/' . $username;
			$where['p.uidnr_admin'] = base64_decode($username);
		} else {
            $base_url .= '/NULL';
			$data['username'] = NULL;
		}
        if ($this->uri->segment('9') != NULL && $this->uri->segment('9') != 'NULL') {
            $designation_id = $this->uri->segment('9');
			$data['designation_id'] =  base64_decode($designation_id);
			$base_url .= '/' . $designation_id;
			$where['ds.designation_id'] = base64_decode($designation_id);
		} else {
            $base_url .= '/NULL';
			$data['designation_id'] = NULL;
		}

        if ($this->uri->segment('10') != NULL && $this->uri->segment('10') != 'NULL') {
            $rol_id = $this->uri->segment('10');
			$data['rol_id'] =  base64_decode($rol_id);
			$base_url .= '/' . $rol_id;
			$where['r.id_admin_role'] = base64_decode($rol_id);
		} else {
            $base_url .= '/NULL';
			$data['rol_id'] = NULL;
		}

        if ($this->uri->segment('11') != NULL && $this->uri->segment('11') != 'NULL') {
            $search = $this->uri->segment('11');
			$data['search'] =  base64_decode($search);
			$base_url .= '/' . $search;
			$search = base64_decode($search);
		} else {
			$base_url .= '/NULL';
			$data['search'] = NULL;
		}

        if ($this->uri->segment('12') != NULL && $this->uri->segment('12') != 'NULL') {
            $sortby = $this->uri->segment('12');
			$base_url .= '/' . $sortby;
            $sortby = base64_decode($sortby);
		} else {
			$base_url .= '/NULL';
		}

        if ($this->uri->segment('13') != NULL && $this->uri->segment('13') != 'NULL') {
            $order = $this->uri->segment('13');
			$base_url .= '/' . base64_decode($order);
		} else {
			$base_url .= '/NULL';
		}

       
        $total_row = $this->User_model->getuserlist(NULL, NULL, $search, NULL, NULL, $where, '1');
		$config = $this->pagination($base_url, $total_row, 10, 14);

		$data["links"] = $config["links"];
        
		$data['users'] = $this->User_model->getuserlist($config["per_page"], $config['page'], $search, $sortby, $order, $where);
        $page_no = $this->uri->segment('14');
		$start = (int)$page_no + 1;
		$end = (($data['users']) ? count($data['users']) : 0) + (($page_no) ? $page_no : 0);
		$data['result_count'] = "Showing " . $start . " - " . $end . " of " . $total_row . " Results";


		if ($order == NULL && $order == 'NULL') {
			$data['order'] = ($order) ? "DESC" : "ASC";
		} else {
			$data['order'] = ($order == "ASC") ? "DESC" : "ASC";
		}
       
        $this->load_view('Users/admin_user', $data);
    }

    public function add_new_user()
    {
        $data['country'] = $this->User_model->fetch_country();
        $data['time_zone'] = $this->User_model->get_timezone();
        $data['roles'] = $this->User_model->fetch_roles();
        $data['crm_user'] = $this->User_model->fetch_crm();
        $data['default_division'] = $this->User_model->get_default_division();
        $data['department'] = $this->User_model->fetch_department();
        $data['default_branch'] = $this->User_model->get_default_branch();
        $data['designation'] = $this->User_model->fetch_designation();
        $data['labs'] = $this->User_model->get_labs();

      
        // save user data
        if ($this->input->post()) {

            $this->form_validation->set_rules('emp_no','Employee Number','required|is_unique[operator_profile.employee_no]');
			$this->form_validation->set_rules('first_name','First Name','required');
			$this->form_validation->set_rules('last_name','Last Name','required');
			$this->form_validation->set_rules('initial','Initial','required');
			$this->form_validation->set_rules('username','Username','required|is_unique[admin_users.admin_username]');
			$this->form_validation->set_rules('email','Email','required|is_unique[admin_users.admin_email]');
			$this->form_validation->set_rules('mob_no','Mobile Number','required');
			$this->form_validation->set_rules('time_zone','Time Zone','required');
			$this->form_validation->set_rules('role','Role','required');
			$this->form_validation->set_rules('crm_user_id','CRM Flag','required');
			$this->form_validation->set_rules('division[]','Division','required');
			$this->form_validation->set_rules('def_div','Default Division','required');
			$this->form_validation->set_rules('dept','Department','required');
			$this->form_validation->set_rules('branch[]','Branch','required');
			$this->form_validation->set_rules('def_branch','Default Branch','required');
			$this->form_validation->set_rules('labs[]','Labs','required');
			$this->form_validation->set_rules('designation','Designation','required');
			$this->form_validation->set_rules('address','Address','required');
            $this->form_validation->set_rules('sales_person', 'Sales Person Flag', 'required');
            if(!array_key_exists('sign_auth',$this->input->post())){
               $si = '0';
            }
            else{
                $si = $this->input->post('sign_auth');
            }

            if ($this->form_validation->run()) {
                $admin_users = array(
                    'admin_password'        => md5($this->input->post('password')),
                    'admin_email'           => $this->input->post('email'),
                    'crm_flag'              => $this->input->post('crm_user_id'),
                    'admin_username'        => $this->input->post('username'),
                    'default_division_id'   => $this->input->post('def_div'),
                    'id_admin_role'         => $this->input->post('role'),
                    'lab_analyst'           => $this->input->post('lab_analyst'),
                    'sales_person'          => $this->input->post('sales_person'),
                );
                
                $profile_image = array();
                $valid_file = $this->check_valid_file_upload($_FILES['profile_image']);
                if($valid_file){
                    $profile_image = array(
                        'profile_image' => $valid_file['image'],
                        'profile_image_thumb' => $valid_file['image'],
                    );
                } 
                // die;
                $admin_profile = array(
                    'admin_fname'       => $this->input->post('first_name'),
                    'admin_lname'       => $this->input->post('last_name'),
                    'admin_address'     => $this->input->post('address'),
                    'admin_telephone'   => $this->input->post('mob_no'),
                    'admin_initial'     => $this->input->post('initial'),
                    'time_zone_id'      => $this->input->post('time_zone'),
                    'ap_signing_auth'   => $si
                );
                // echo "<pre>"; print_r($admin_profile); die;
                
                $operator_profile = array(
                    'employee_no'       => $this->input->post('emp_no'),
                    'admin_designation'       => $this->input->post('designation'),
                    'dept_id'           => $this->input->post('dept'),
                    'default_branch_id' => $this->input->post('def_branch')
                );
              
                $division_data = array(
                    'user_division_div_id'  => implode(",", $this->input->post('division'))
                );
                
                $branch_data = $this->input->post('branch');
              
                $lab_data = $this->input->post('labs');
               
                $save = $this->User_model->save_user_data($admin_users, $admin_profile, $operator_profile, $division_data, $branch_data, $lab_data,$profile_image);

                if ($save['success']) {
                    $log_deatils = array(
                        'text'          => "Created User with name ".$this->input->post('first_name'),
                        'created_by'    => $this->admin_id(),
                        'created_on'    => date('Y-m-d H:i:s'),
                        'record_id'     => $save['admin_id'],
                        'source_module' => 'Users',
                        'action_taken'  => 'add_new_user'
                    );
    
                    $this->User_model->insert_data('user_log_history',$log_deatils);
                    $this->session->set_flashdata('success', 'User created successfully');
                    return redirect('users');
                } else {
                    $this->session->set_flashdata('error', 'error in adding users');
                }
            }
            else{
                $this->session->set_flashdata('error', 'fill all required fields');
            }
        }
        $this->load_view('Users/add_users', $data);
    }


    public function check_valid_file_upload($file_name)
	{
		if ($file_name['name'] != '' && ($file_name['type'] == 'image/jpeg' || $file_name['type'] == 'image/jpg' || $file_name['type'] == 'image/png')) {
			$image = $this->multiple_upload_image($file_name);
			if (!empty($image)) {
				$img['image'] = $image['aws_path'];
				$thumb_name = $this->generate_image_thumbnail($file_name['name'], $file_name['tmp_name'], THUMB_PATH);
				$thumb = $this->upload_thumb_aws(THUMB_PATH . $thumb_name, $thumb_name);
				$img['thumb'] = $thumb['aws_path'];
				$result = true;
			} else {
				$result = false;
			}
		} else {
			$result = false;
		}

		if ($result == false) {
			return false;
		} else {
			return $img;
		}
	}

    public function edit_admin_users($id)
    {
       
        $data['country'] = $this->User_model->fetch_country();
        $data['time_zone'] = $this->User_model->get_timezone();
        $data['roles'] = $this->User_model->fetch_roles();
        $data['crm_user'] = $this->User_model->fetch_crm();
        $data['default_division'] = $this->User_model->get_default_division();
        $data['department'] = $this->User_model->fetch_department();
        $data['default_branch'] = $this->User_model->get_default_branch();
        $data['designation'] = $this->User_model->fetch_designation();
        $data['labs'] = $this->User_model->get_labs();
        // Get Users details by id  
        $data['users'] = $this->User_model->get_user_by_id($id);
        $data['user_labs'] = $this->User_model->get_labs_by_user($id);
        $data['user_branches'] = $this->User_model->get_branch_by_user($id);
        $data['user_divisions'] = $this->User_model->get_devision_by_user($id);
        $this->load_view('Users/edit_users', $data);
    }

    public function Edit_user_submit()
    {
        $this->form_validation->set_rules('emp_no', 'Employee Number', 'required');
        $this->form_validation->set_rules('first_name', 'First Name', 'required');
        $this->form_validation->set_rules('last_name', 'Last Name', 'required');
        $this->form_validation->set_rules('initial', 'Initial', 'required');
        $this->form_validation->set_rules('username', 'Username', 'required');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
        $this->form_validation->set_rules('mob_no', 'Mobile Number', 'required');
        $this->form_validation->set_rules('time_zone', 'Time Zone', 'required');
        $this->form_validation->set_rules('role', 'Role', 'required');
        $this->form_validation->set_rules('crm_user_id', 'CRM Flag', 'required');
        $this->form_validation->set_rules('division[]', 'Division', 'required');
        $this->form_validation->set_rules('def_div', 'Default Division', 'required');
        $this->form_validation->set_rules('dept', 'Department', 'required');
        $this->form_validation->set_rules('branch[]', 'Branch', 'required');
        $this->form_validation->set_rules('def_branch', 'Default Branch', 'required');
        $this->form_validation->set_rules('labs[]', 'Labs', 'required');
        $this->form_validation->set_rules('designation', 'Designation', 'required');
        $this->form_validation->set_rules('address', 'Address', 'required');
        $this->form_validation->set_rules('sales_person', 'Sales Person Flag', 'required');
        if ($this->form_validation->run()) {
            $post = $this->input->post();
            $this->db->trans_begin();
            $admin_users = array(
                'admin_email'           => $post['email'],
                'crm_flag'              => $post['crm_user_id'],
                'admin_username'        => $post['username'],
                'default_division_id'   => $post['def_div'],
                'id_admin_role'         => $post['role'],
                'lab_analyst'           => $post['lab_analyst'],
                'sales_person'           => $post['sales_person']
            );
            if (!empty($post['password'])) {
                $admin_users['admin_password'] = md5($post['password']);
            }
            $this->User_model->update_data('admin_users', $admin_users, ['uidnr_admin' => $post['user_id']]);
            
            if(!empty($_FILES['profile_image'])){
                $valid_file = $this->check_valid_file_upload($_FILES['profile_image']);
                if($valid_file){
                $profile_image = array(
                    'profile_image' => $valid_file['image'],
                    'profile_image_thumb' => $valid_file['image'],
                );
                $this->User_model->update_data('admin_profile', $profile_image, ['uidnr_admin' => $post['user_id']]);
            }
            }
            

            $admin_profile = array(
                'admin_fname'       => $post['first_name'],
                'admin_lname'       => $post['last_name'],
                'admin_address'     => $post['address'],
                'admin_telephone'   => $post['mob_no'],
                'admin_initial'     => $post['initial'],
                'time_zone_id'      => $post['time_zone']
            );
            if (isset($post['sign_auth'])) {
                $admin_profile['ap_signing_auth']=$post['sign_auth'];
            } else {
                $admin_profile['ap_signing_auth']='0';
            }
            $this->User_model->update_data('admin_profile', $admin_profile, ['uidnr_admin' => $post['user_id']]);
            
            //$this->User_model->update_data('admin_profile', $admin_profile, ['uidnr_admin' => $post['user_id']]);
            // echo $this->db->last_query();
            $operator_profile = array(
                'employee_no'       => $post['emp_no'],
                'admin_designation' => $post['designation'],
                'dept_id'           => $post['dept'],
                'default_branch_id' => $post['def_branch']
            );
            $this->User_model->update_data('operator_profile', $operator_profile, ['uidnr_admin'=>$post['user_id']]);
            // echo $this->db->last_query();
            
            if ($post['division'] && count($post['division']) > 0) {
                $this->db->delete('user_divisions', ['user_division_uidnr_admin' => $post['user_id']]);
                // echo $this->db->last_query();
                $divison_multi = array();
                foreach ($post['division'] as $key => $value) {
                    $divison_multi[$key] = array(
                        'user_division_uidnr_admin'=> $post['user_id'],
                        'user_division_div_id'=> $value,
                    );
                }
                $this->User_model->insert_multiple_data('user_divisions',$divison_multi);
                // echo $this->db->last_query();
            }
            
            if ($post['labs'] && count($post['labs'])>0) {
                $this->db->delete('user_labs', ['user_labs_uidnr_admin' => $post['user_id']]);
                // echo $this->db->last_query();
                $labs_multi = array();
                foreach ($post['labs'] as $key => $labs) {
                    $labs_multi[$key]= array(
                        'user_labs_uidnr_admin'=>$post['user_id'],
                        'user_labs_lab_id'=>$labs
                    );
                }
                $this->User_model->insert_multiple_data('user_labs',$labs_multi);
                // echo $this->db->last_query();
            }
            
            if ($post['branch'] && count($post['branch'])>0) {
                $this->db->delete('user_branch', ['user_branch_uidnr_admin' =>$post['user_id']]);
                // echo $this->db->last_query();
                $branch_multi = array();
                foreach ($post['branch'] as $key => $branches) {
                    $branch_multi[$key]=array(
                        'user_branch_uidnr_admin'=>$post['user_id'],
                        'user_branch_branch_id'=>$branches
                    );
                }
                $this->User_model->insert_multiple_data('user_branch',$branch_multi);
                // echo $this->db->last_query();
            }
            // die;
            
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $msg = array(
                    'status' => 0,
                    'msg' => 'SOMETHING WRONG WHILE UPDATING USER DETAILS'
                );
            } else {
                $this->db->trans_commit();
                $log_deatils = array(
                    'text'          => "Updated User with name ".$this->input->post('first_name'),
                    'created_by'    => $this->admin_id(),
                    'created_on'    => date('Y-m-d H:i:s'),
                    'record_id'     => $post['user_id'],
                    'source_module' => 'Users',
                    'action_taken'  => 'Edit_user_submit'
                );

                $this->User_model->insert_data('user_log_history',$log_deatils);
                $this->session->set_flashdata('success', 'User updating successfully');
                $msg = array(
                    'status' => 1,
                    'msg' => 'User updated successfully'
                );


            }
        } else {
            $msg = array(
                'status' => 0,
                'errors' =>  $this->form_validation->error_array(),
                'msg' => 'PLEASE VALID ENTER'
            );
        }
        echo json_encode($msg);
    }
    public function emailCheck()
    {
        $post = $this->input->post();
        $user =  $this->User_model->get_row('count(*) as number','admin_users',['admin_email'=>$post['email'],'uidnr_admin not IN ('.$post['user_id'].')'=>null]);
        if ($user && $user->number > 0) {
            $this->form_validation->set_message('emailCheck', 'THIS EMAIL ALREADY EXIST!');
            return FALSE;
        }else{
            return TRUE;
        }
    }
    public function get_signature()
    {
        $data = $this->input->post();
        $sign = $this->User_model->get_row('sign_path', 'admin_signature', ['admin_id' => $data['admin_id']]);
        if ($sign) {
            // # code...
            // if (substr($sign->sign_path,0, 5) == 's3://') {
                $sign = $this->url_sign_get($sign->sign_path);
                // echo $sign;die;
            // } else           
        }
        echo json_encode($sign);
    }

    public function url_sign_get($signature_path_aws)
    {
        return str_replace('s3://','https://s3.ap-south-1.amazonaws.com/',$signature_path_aws);
    }

    public function upload_signature()
    {
      
        $id = $this->input->post();
        $data = $_FILES['sign_path'];

        if (!empty($data['name'])) {
            if($data['size']>30000){
                $msg = array(
                    'msg' => 'Signature size more than 30kb',
                    'status' => '0'
                );
            }
            else{

                $signature_path_aws =  $this->multiple_upload_image($data);
            
                if ($signature_path_aws) {
//                    $signature_path_aws = str_replace('https://s3.ap-south-1.amazonaws.com/','s3://',$signature_path_aws['aws_path']);
                    // print_r($signature_path_aws);die;
                    $signature_path_aws =$signature_path_aws['aws_path'];
                    $sign =  $this->User_model->get_row('sign_path', 'admin_signature', ['admin_id' => $id['admin_id']]);
                    if ($sign) {
                        $result = $this->User_model->update_data('admin_signature', ['sign_path' => $signature_path_aws], ['admin_id' => $id['admin_id']]);
                    } else {
                        $signature_data = array(
                            'admin_id' => $id['admin_id'],
                            'sign_path' => $signature_path_aws
                        );
                        $result =  $this->User_model->insert_data_signature('admin_signature', $signature_data);
                    }
                } else {
                    $result = false;
                }   
                // echo $result;die;
                if ($result) {
                    $log_deatils = array(
                        'text'          => "signature uploaded",
                        'created_by'    => $this->admin_id(),
                        'created_on'    => date('Y-m-d H:i:s'),
                        'record_id'     => $id['admin_id'],
                        'source_module' => 'Users',
                        'action_taken'  => 'upload_signature'
                    );
    
                   $result =  $this->User_model->insert_data('user_log_history',$log_deatils);
                    if($result){
                        $msg = array(
                            'msg' => 'Signature upload successfully',
                            'status' => '1'
                        );
                    }
                    else{
                        $msg = array(
                            'msg' => 'error in generating log',
                            'status' => '0'
                        );
                    }
                } else {
                    $msg = array(
                        'msg' => 'Error while Uploading signature',
                        'status' => '0'
                    );
                }
            }
           
        } else {
            $msg = array(
                'msg' => 'No File Selected',
                'status' => '0'
            );
        }
        echo json_encode($msg);
    }


    public function mark_user()
    {
        $get = $this->input->get();
        if (array_key_exists('uidnr_admin',$get) && $get['uidnr_admin'] && $get['uidnr_admin']>0) {
            $lab_analyst =  $this->User_model->get_row('lab_analyst', 'admin_users', ['uidnr_admin' => $get['uidnr_admin']]);
            $post = array();
                if ($lab_analyst && $lab_analyst->lab_analyst == '1') {
                    $post['lab_analyst']= '0';
                    $msg = 'REMOVE FROM LAB ANALYST';
                } else {
                    $post['lab_analyst']= '1';
                    $msg = 'MARK AS LAB ANALYST';
                }
            if (count($post)>0) {
               $update = $this->User_model->update_data('admin_users',$post, ['uidnr_admin' => $get['uidnr_admin']]);
               if ($update) {
                $log_deatils = array(
                    'text'          => "MARK USER UPDATED",
                    'created_by'    => $this->admin_id(),
                    'created_on'    => date('Y-m-d H:i:s'),
                    'record_id'     => $get['uidnr_admin'],
                    'source_module' => 'Users',
                    'action_taken'  => 'mark_user'
                );

               $result =  $this->User_model->insert_data('user_log_history',$log_deatils);
                if($result){
                    $this->session->set_flashdata('success', $msg);
                }
                else{
                    $this->session->set_flashdata('error', 'error in generating log'); 
                }
                  
               } else {
                $this->session->set_flashdata('error', 'SOMETHING WRONG WHILE UPDATE!');
               }
            } else {
                $this->session->set_flashdata('error', 'SOMETHING WRONG');
            }
        }else{
            $this->session->set_flashdata('error', 'RECORD NOT FOUND');
        }
        redirect($_SERVER['HTTP_REFERER']);
    }


    public function block_user()
    {
        $get = $this->input->get();
        if (array_key_exists('uidnr_admin',$get) && $get['uidnr_admin'] && $get['uidnr_admin']>0) {
            $admin_active =  $this->User_model->get_row('admin_active', 'admin_users', ['uidnr_admin' => $get['uidnr_admin']]);
            $post = array();
            
                if ($admin_active && $admin_active->admin_active == '1') {
                    $post['admin_active'] = '0';
                    $msg = 'UN-BLOCK USER';
                    $his['status']='0';
                } else {
                    $post['admin_active'] = '1';
                    $msg = 'BLOCK USER';
                    $his['status']='1';
                }
             
            
            if (count($post)>0) {
               $update = $this->User_model->update_data('admin_users',$post, ['uidnr_admin' => $get['uidnr_admin']]);
               $update2 = $this->User_model->update_data('login_user_history',$his, ['uidnr_admin' => $get['uidnr_admin']]);
               
               if ($update && $update2) {
                  

                   $log_deatils = array(
                    'text'          => "BLOCK STATUS UPDATED",
                    'created_by'    => $this->admin_id(),
                    'created_on'    => date('Y-m-d H:i:s'),
                    'record_id'     => $get['uidnr_admin'],
                    'source_module' => 'Users',
                    'action_taken'  => 'block_user'
                );

               $result =  $this->User_model->insert_data('user_log_history',$log_deatils);
                if($result){
                    $this->session->set_flashdata('success', $msg);
                }
                else{
                    $this->session->set_flashdata('error', 'error in generating log'); 
                }
               } else {
                $this->session->set_flashdata('error', 'SOMETHING WRONG WHILE UPDATE!');
               }
            } else {
                $this->session->set_flashdata('error', 'SOMETHING WRONG');
            }
        }else{
            $this->session->set_flashdata('error', 'RECORD NOT FOUND');
        }
        redirect($_SERVER['HTTP_REFERER']);
    }


    // Get user log
	public function get_user_log()
	{
		$user_id = $this->input->post('user_id');
		$data = $this->User_model->get_user_log($user_id);
		echo json_encode($data);
	}

    public function get_dropdown_by_ajax(){
        $select = $this->input->post('select');
        $from = $this->input->post('from');
        $where = $this->input->post('where');

        $data = $this->User_model->get_result($select,$from,$where);
        echo json_encode($data);
    }


    public function get_history(){
        $limit = $start = $search = $sortby = $order =$where=$count = NULL;
        $where = (array)(json_decode(stripslashes($this->input->post('where'))));
        
       if(array_key_exists('attempts_type',$where)){
           if($where['attempts_type']==""){
                unset($where['attempts_type']);
           }
       }

       if(array_key_exists('created_on',$where)){
        if(empty($where['created_on'])){
            unset($where['created_on']);
        }
        else{
            $where['date(created_on)'] = $where['created_on'];
            unset($where['created_on']);
        }
       }
        
    
        $limit = $this->input->post('limit');
        $start = $this->input->post('start');
        $search = $this->input->post('search');
        $sortby = $this->input->post('sort');
        $order = $this->input->post('order');

        $data['data'] = $this->User_model->get_login_history($limit, $start, $search , $sortby , $order, $where , $count );
        $data['total_c'] = $this->User_model->get_login_history(NULL, NULL, $search, NULL, NULL, $where, '1');
        echo json_encode($data);
    }

/*
 |-----------------------------------
 |ADDED ON 09-08-2021 BY KAPRI
 |-----------------------------------
 */
    public function uploadStampForm(){
      $user_id = $this->input->get('user_id');
      $data['userStamp'] = $this->db->select('ap.stamp_path, au.uidnr_admin')
                ->from('admin_profile as ap')
                ->join('admin_users as au', 'au.uidnr_admin = ap.uidnr_admin', 'left')
                ->where('au.uidnr_admin', $user_id)
                ->get()
                ->row_array();
        $stampdata = $this->load->view('Users/user_stamp_upload', $data, true);
        echo json_encode($stampdata);  
    }
    
    public function uploadStamp() {
        $user_id = $this->input->post('admin_id');
        $result = $this->multiple_upload_image($_FILES['stamp_path']);
        if (isset($result['aws_path']) && !empty($result['aws_path'])) {
            $this->db->update('admin_profile', ['stamp_path' => $result['aws_path']], ['uidnr_admin' => $user_id]);
            $this->session->set_flashdata('success', 'Stamp Uploaded Successfully!');
            redirect('users');
        }
        $this->session->set_flashdata('error', 'SOMETHING WENT WRONG!');
        redirect('users');
    }

    
   
}
