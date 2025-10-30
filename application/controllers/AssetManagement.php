<?php
defined('BASEPATH') or exit('No direct access allowed');

class AssetManagement extends MY_Controller{

    function __construct(){
        parent::__construct();
        $this->check_session();
        $this->load->model('AssetManagement_model','am');
    }        

    public function index() {
        $where = NULL;
        $base_url = 'AssetManagement/index';
        // echo $this->uri->segment('3'); die;
        $asset_name = $this->uri->segment('3');
        $asset_code = $this->uri->segment('4');
        $id_country = $this->uri->segment('5');
        $assigned_user = $this->uri->segment('6');
        $assign_flag = $this->uri->segment('7');
        $id_branch = $this->uri->segment('8'); 
        $created_pesron = $this->uri->segment('9');
        $search = $this->uri->segment('10');
        // echo $search; die;
        $sortby = $this->uri->segment('11');
        $order = $this->uri->segment('12');
        $page_no = $this->uri->segment('13');
 //print_r(base64_decode($assigned_user));

       
        if ($asset_name != NULL  && $asset_name != 'NULL') {
            $data['asset_name'] = base64_decode($asset_name);
            $base_url  .= '/' . $asset_name;
            $where['am.asset_name'] = base64_decode($asset_name); 
        } else {
            $data['asset_name'] = 'NULL';
            $base_url  .= '/NULL';
        }
        if ($asset_code != NULL  && $asset_code != 'NULL') {
            $data['asset_code'] = base64_decode($asset_code);
            $base_url  .= '/' . $asset_code;
            $where['am.asset_code'] = base64_decode($asset_code); 
        } else {
            $data['asset_code'] = 'NULL';
            $base_url  .= '/NULL';
        }
        if ($id_country != NULL  && $id_country != 'NULL') {
            $data['id_country'] = base64_decode($id_country);
            $base_url  .= '/' . $id_country;
            $where['msc.country_id'] = base64_decode($id_country); 
        } else {
            $data['id_country'] = 'NULL';
            $base_url  .= '/NULL';
        }
        if ($assigned_user != NULL  && $assigned_user != 'NULL') {
            $data['assigned_user'] = base64_decode($assigned_user);
            $base_url  .= '/' . $assigned_user;
            $where['ass.employee_id'] = base64_decode($assigned_user); 
        } else {
            $data['assigned_user'] = 'NULL';
            $base_url  .= '/NULL';
        }
        if ($assign_flag != NULL  && $assign_flag != 'NULL') {
            $data['assign_flag'] = base64_decode($assign_flag);
            $base_url  .= '/' . $assign_flag;
            $where['am.assign_flag'] = base64_decode($assign_flag); 
        } else {
            $data['assign_flag'] = 'NULL';
            $base_url  .= '/NULL';
        }
        if ($id_branch != NULL  && $id_branch != 'NULL') {
            $data['id_branch'] = base64_decode($id_branch);
            $base_url  .= '/' . $id_branch;
            $where['msb.branch_id'] = base64_decode($id_branch); 
        } else {
            $data['id_branch'] = 'NULL';
            $base_url  .= '/NULL';
        }
        if ($created_pesron != NULL  && $created_pesron != 'NULL') {
            $data['created_pesron'] = base64_decode($created_pesron);
            $base_url  .= '/' . $created_pesron;
            $where['ap.uidnr_admin'] = base64_decode($created_pesron); 
        } else {
            $data['created_pesron'] = 'NULL';
            $base_url  .= '/NULL';
        }
        if ($search != NULL && $search != 'NULL') {
            $data['search'] =  base64_decode($search);
            $base_url .= '/' . $search;
            $search = base64_decode($search);
        } else {
            $base_url .= '/NULL';
            $data['search'] = 'NULL';
            $search = NULL;
        }
        if ($sortby != NULL && $sortby != 'NULL') {
            $base_url .= '/' . $sortby;
        } else {
            $base_url .= '/NULL';
            $sortby = NULL;
        }
        if ($order != NULL && $order != 'NULL') {
            $base_url .= '/' . $order;
        } else {
            $base_url .= '/NULL';
            $order = NULL;
        }
        $total_row = $this->am->get_branch_list(NULL, NULL, $search, NULL, NULL, $where, '1');
        $config = $this->pagination($base_url, $total_row, 10, 13);
        $data["links"] = $config["links"];

        
        $data['brn_list'] = $this->am->get_branch_list($config["per_page"], $config['page'],$search,$sortby,$order, $where);
        $start = (int)$page_no + 1;
        $end = (($data['brn_list']) ? count($data['brn_list']) : 0) + (($page_no) ? $page_no : 0);
        $data['result_count'] = "Showing " . $start . " - " . $end . " of " . $total_row . " Results";
        $data['brn_names'] = $this->am->fetch_branch_name(); 
        $data['asset'] = $this->am->fetch_asset_code(); 
       $data['brn_divs'] = $this->am->fetch_brn_division(); 
        $data['countries'] = $this->am->fetch_country();
        $data['employee'] = $this->am->fetch_emp();
       
       $data['created_by_name'] = $this->am->fetch_created_person();
       $data['branch'] = $this->am->fetch_branch();

      //echo"<pre>";  print_r($data);die;
        if ($order == NULL || $order == 'NULL') {
            $data['order'] = ($order) ? "DESC" : "ASC";
        } else {
            $data['order'] = ($order == "ASC") ? "DESC" : "ASC";
        }
        $this->load_view('assetmanagement/index', $data);
    }

    public function add_asset_details(){

        $data['country'] = $this->am->fetch_all_data('mst_country');
        $data['division'] = $this->am->fetch_all_data('mst_divisions');
        $data['branch'] = $this->am->fetch_all_data('mst_branches');
        $data['employee'] = $this->am->fetch_all_data('assets_user');
     
    //  echo"<pre>";  print_r($data);
       $this->form_validation->set_rules('product_id', 'Serial no.', 'trim|required|is_unique[tbl_assets.product_id]');
       $this->form_validation->set_rules('asset_code', 'Asset Code', 'trim|required|is_unique[tbl_assets.asset_code]');
       $this->form_validation->set_rules('status', 'Status', 'required');
     
        if($this->form_validation->run() == true){
            $input_array = array(
                'asset_name'                 => $this->input->post('asset_name'),
                'asset_make'                 => $this->input->post('asset_make'),
                'asset_model'                 => $this->input->post('asset_model'),
                'product_id'                 => $this->input->post('product_id'),
                'asset_code'                 => $this->input->post('asset_code'),
                'country_id'                 => $this->input->post('country'),
                'status'                   => $this->input->post('status'),
                'branch_id'                 => $this->input->post('branch'),
                'created_by'                 => $this->session->userdata('user_data')->uidnr_admin,
                'created_on'                 => date('Y-m-d H:i:s')
            ); 
            $save = $this->am->insert_data('tbl_assets',$input_array);
          // echo  $this->db->last_query();die;
            if($save){
                $branch_id = $save;

                $log_deatils = array(
                    'text'          => "Added Asset with Details ".$this->input->post('asset_name'),
                    'created_by'    => $this->admin_id(),
                    'created_on'    => date('Y-m-d H:i:s'),
                    'record_id'     => $branch_id,
                    'source_module' => 'AssetManagement',
                    'action_taken'  => 'add_asset_details'
                );

                $log = $this->am->insert_data('user_log_history',$log_deatils);
                // echo $this->db->last_query(); die;
                if($log){
                    $this->session->set_flashdata('success','Asset Details Added successfully.');
                    $data = array(
                        'status' => 1,
                        'msg' => 'Asset Details Added Successfully'
                    );
                    return redirect('AssetManagement/index');
                }
                else{
                    $this->session->set_flashdata('error', 'Error in Maintaining Asset Details Add Log');
                    $data = array(
                        'status' => 0,
                        'msg' => 'Error in Maintaining Asset Details Add Log'
                    );
                    return redirect('AssetManagement/index');
                }
            } else{
                $this->session->set_flashdata('false','Error in Adding Asset Details.');
            }
        } 
        $this->load_view('assetmanagement/asset_form',$data);
    }

    public function edit_asset($id){

      
         $data['country'] = $this->am->fetch_all_data('mst_country');
         $data['currency'] = $this->am->fetch_all_data('mst_currency');
         $data['division'] = $this->am->fetch_all_data('mst_divisions');
         $data['branch'] = $this->am->fetch_all_data('mst_branches');
        $data['fetch_data'] = $this->am->get_data_by_id('tbl_assets',base64_decode($id),'asset_id ');
         $state_id = $data['fetch_data']->state_id;
         $data['state'] = $this->am->get_fields_by_id('mst_provinces','*',$state_id,'province_id');
      
    //print_r($data);die;
    $this->form_validation->set_rules('product_id', 'Serial no.', 'callback_update_product_id');
        $this->form_validation->set_rules('asset_code', 'Asset Code', 'trim|required|callback_update_brn_code');
        if($this->form_validation->run() == true){
            $input_array = array(
                'asset_name'                 => $this->input->post('asset_name'),
                'asset_make'                 => $this->input->post('asset_make'),
                'asset_model'                 => $this->input->post('asset_model'),
                'product_id'                 => $this->input->post('product_id'),
                'asset_code'                 => $this->input->post('asset_code'),
                'country_id'                 => $this->input->post('country'),
                'status'                   => $this->input->post('status'),
                'branch_id'                   => $this->input->post('branch'),
                'created_by'                 => $this->session->userdata('user_data')->uidnr_admin
              

            ); 
            $asset_id = $this->am->update_data('tbl_assets',$input_array,['asset_id' => base64_decode($id)]);
           // echo  $this->db->last_query();die;
            if($asset_id){
                $branch_id = base64_decode($id);
                $devision_id = $this->input->post('division');
                    $division_array = array(
                        'mst_branch_divisions_branch_id'    => $branch_id,
                        'mst_branch_divisions_division_id'    => $devision_id
                    );
                    $asset_id = $this->am->insert_data('mst_branch_divisions',$division_array);
                    //echo  $this->db->last_query();die;


                $log_deatils = array(
                    'text'          => "Edited Asset with name ".$this->input->post('asset_name'),
                    'created_by'    => $this->admin_id(),
                    'created_on'    => date('Y-m-d H:i:s'),
                    'record_id'     => base64_decode($id),
                    'source_module' => 'AssetManagement',
                    'action_taken'  => 'edit_Asset'
                );

                $log= $this->am->insert_data('user_log_history',$log_deatils);
                //echo  $this->db->last_query();die;
                if($log){
                    $this->session->set_flashdata('success','Asset Updated successfully.');
                    $data = array(
                        'status' => 1,
                        'msg' => 'Asset Updated Successfully'
                    );
                    return redirect('AssetManagement/index');    
                }
                else{
                    $this->session->set_flashdata('error','Asset Updated successfully.');
                    $data = array(
                        'status' => 0,
                        'msg' => 'Asset Updated Successfully'
                    );
                    return redirect('AssetManagement/index');
                }
            } else{
                $this->session->set_flashdata('false','Error in Updating Asset.');
            }
        } 
        $this->load_view('assetmanagement/asset_form',$data);
    }

    public function get_location(){
        $state_id = $this->input->post('state');
        $location = $this->am->get_fields_by_id('mst_locations','*',$state_id,'mst_locations_province_id');
        echo json_encode($location);
    }

    public function get_province(){
        $country_id = $this->input->post('country');
        $state = $this->am->get_fields_by_id('mst_provinces','*',$country_id,'mst_provinces_country_id');
        echo json_encode($state);
    }

    public function asset_status() {
        if (!empty($this->input->post())) {
            $checkUser = $this->session->userdata();
            $data_fetch = $this->bm->fetch_asset_for_edit($this->input->post('branch_id'));
            $asset_name = $data_fetch->asset_name;
            $status = $this->bm->update_asset_status($this->input->post());
            if ($status) {
                $log_deatils = array(
                    'text'          => "Updated Asset Status with Asset name ".$asset_name,
                    'created_by'    => $checkUser['user_data']->uidnr_admin,
                    'created_on'    => date('Y-m-d h:i:s'),
                    'record_id'     => $this->input->post('branch_id'),
                    'source_module' => 'AssetManagement',
                    'action_taken'  => 'asset_status'
                );
                $log = $this->bm->insert_data('user_log_history',$log_deatils);
                if($log){
                    $this->session->set_flashdata('success', 'Asset Status Updated Successfully');
                    $data = array(
                        'status' => 1,
                        'msg' => 'Asset Status Updated Successfully'
                    );
                }
                else{
                    $this->session->set_flashdata('error', 'Error in Maintaining Asset Status Log');
                    $data = array(
                        'status' => 0,
                        'msg' => 'Error in Maintaining Asset Status Log'
                    );
                }
            } else {
                $this->session->set_flashdata('error', 'Error in Updating Asset Status');
                $data = array(
                    'status' => 0,
                    'msg' => 'Error in Updating Asset Status'
                );
            }
        }
        echo json_encode($data);
    }

    public function get_branch_log(){
		$asset_id = $this->input->post('asset_id');
		$data = $this->am->get_branch_log($asset_id);
		echo json_encode($data);
	}


    public function Assigned_history(){
		$asset_id = $this->input->post('asset_id');
		$data = $this->am->Assigned_history($asset_id);
		echo json_encode($data);
	}

    public function Assigned_emp_history(){
		$employee_id = $this->input->post('employee_id');
		$data = $this->am->Assigned_emp_history($employee_id);
		echo json_encode($data);
	}


    public function get_assigned_user(){
		$asset_id = $this->input->post('employee_id');
		$data = $this->am->get_assigned_user($asset_id);
		echo json_encode($data);
	}

    public function get_emp_log(){
		$employee_id = $this->input->post('employee_id');
		$data = $this->am->get_emp_log($employee_id);
		echo json_encode($data);
    }

    public function update_brn_code($field){
        $update_form = $this->input->post();
        $check_fileds = array();
        $check_fileds['LOWER(asset_code)'] = strtolower($update_form['asset_code']);
        $check_fileds['asset_id NOT IN ('.$update_form['asset_id'].')'] =  NULL;
        if(!empty($update_form) && !empty($update_form['asset_id']) ){
            $check_in = $this->am->get_row('*', 'tbl_assets' , $check_fileds);
            if($check_in){
                $this->form_validation->set_message('update_brn_code', 'The {field} field can not be the same. "It Must be Unique!!"');
                return FALSE;
            }
            else
            {
                return TRUE;
            }
        }
        else{
            return false;
        }
    }

    public function update_product_id($field){
        $update_form = $this->input->post();
        $check_fileds = array();
        $check_fileds['LOWER(product_id)'] = strtolower($update_form['product_id']);
        $check_fileds['asset_id NOT IN ('.$update_form['asset_id'].')'] =  NULL;
        if(!empty($update_form) && !empty($update_form['asset_id']) ){
            $check_in = $this->am->get_row('*', 'tbl_assets' , $check_fileds);
            if($check_in){
                $this->form_validation->set_message('update_product_id', 'The {field} field can not be the same. "It Must be Unique!!"');
                return FALSE;
            }
            else
            {
                return TRUE;
            }
        }
        else{
            return false;
        }
    }


   
    


    

public function add_employee(){
$id=$this->uri->segment('4');
$data['asset_id']= $id;
// echo"<pre>";  print_r($data);die;
        $data['country'] = $this->am->fetch_all_data('mst_country');
        $data['division'] = $this->am->fetch_all_data('mst_divisions');
        $data['branch'] = $this->am->fetch_all_data('mst_branches');
       
    //  echo"<pre>";  print_r($data);
       $this->form_validation->set_rules('employee_name', 'employee Name', 'trim|required');
       $this->form_validation->set_rules('employee_contact', 'Employee Contact', 'trim|is_unique[assets_user.employee_contact]');
       $this->form_validation->set_rules('status', 'Status', 'required');
       $this->form_validation->set_rules('emp_id', 'Employee ID', 'is_unique[assets_user.emp_id]');
       $this->form_validation->set_rules('employee_mail', 'Employee E-mail', 'is_unique[assets_user.employee_mail]');
       
     
        if($this->form_validation->run() == true){
            $input_array = array(
                'employee_name'                 => $this->input->post('employee_name'),
                'employee_contact'                 => $this->input->post('employee_contact'),
                'emp_id'                 => $this->input->post('emp_id'),
                'employee_designation'                 => $this->input->post('employee_designation'),
                'issue_date'                 => $this->input->post('issue_date'),
                'employee_mail'                 => $this->input->post('employee_mail'),
                'division_id'                 => $this->input->post('division'),
                'asset_id'                 => $this->input->post('asset_id'),
                'country_id'                 => $this->input->post('country'),
                'status'                   => $this->input->post('status'),
                'state_id'                 => $this->input->post('state'),
                'address'                 => $this->input->post('address'),
                'created_by'                 => $this->session->userdata('user_data')->uidnr_admin,
                'created_on'                 => date('Y-m-d H:i:s')
            ); 
            $save = $this->am->insert_data('assets_user',$input_array);
           //echo  $this->db->last_query();die;
if($save){
    $branch_id = $save;
           $log_deatils = array(
            'text'          => "Added Employee with Details ".$this->input->post('employee_name'),
            'created_by'    => $this->admin_id(),
            'created_on'    => date('Y-m-d H:i:s'),
            'record_id'     => $branch_id,
            'source_module' => 'AssetManagement',
            'action_taken'  => 'add_employee_details'
        );

        $log = $this->am->insert_data('user_log_history',$log_deatils);
        // echo $this->db->last_query(); die;
        if($log){
            $this->session->set_flashdata('success','Employee Details Added successfully.');
            $data = array(
                'status' => 1,
                'msg' => 'Employee Details Added Successfully'
            );
            return redirect('AssetManagement/assets_userlist');
        }
        else{
            $this->session->set_flashdata('error', 'Error in Maintaining Asset Details Add Log');
            $data = array(
                'status' => 0,
                'msg' => 'Error in Maintaining Asset Details Add Log'
            );
            return redirect('AssetManagement/assets_userlist');
        }
    } else{
        $this->session->set_flashdata('false','Error in Adding Employee Details.');
    }
} 

    $this->load_view('assetmanagement/emp_form',$data);
}


public function assets_userlist(){

    $where = NULL;
    $base_url = 'AssetManagement/assets_userlist';
    $employee_name = $this->uri->segment('3');
    $employee_contact = $this->uri->segment('4');
    $country_id = $this->uri->segment('5');
    $status = $this->uri->segment('6');
    $division_id = $this->uri->segment('7'); 
    $created_pesron = $this->uri->segment('8');
    $search = $this->uri->segment('9');
    $sortby = $this->uri->segment('10');
    $order = $this->uri->segment('11');
    $page_no = $this->uri->segment('12');
// print_r(base64_decode($status));

   
    if ($employee_name != NULL  && $employee_name != 'NULL') {
        $data['employee_name'] = base64_decode($employee_name);
        $base_url  .= '/' . $employee_name;
        $where['em.employee_name'] = base64_decode($employee_name); 
    } else {
        $data['employee_name'] = 'NULL';
        $base_url  .= '/NULL';
    }
    if ($employee_contact != NULL  && $employee_contact != 'NULL') {
        $data['employee_contact'] = base64_decode($employee_contact);
        $base_url  .= '/' . $employee_contact;
        $where['em.employee_contact'] = base64_decode($employee_contact); 
    } else {
        $data['employee_contact'] = 'NULL';
        $base_url  .= '/NULL';
    }
    if ($country_id != NULL  && $country_id != 'NULL') {
        $data['country_id'] = base64_decode($country_id);
        $base_url  .= '/' . $country_id;
        $where['msc.country_id'] = base64_decode($country_id); 
    } else {
        $data['country_id'] = 'NULL';
        $base_url  .= '/NULL';
    }

    if ($status != NULL  && $status != 'NULL') {
        $data['status'] = base64_decode($status);
        $base_url  .= '/' . $status;
        $where['em.status'] = base64_decode($status); 
    } else {
        $data['status'] = 'NULL';
        $base_url  .= '/NULL';
    }
    
    if ($division_id != NULL  && $division_id != 'NULL') {
        $data['division_id'] = base64_decode($division_id);
        $base_url  .= '/' . $division_id;
        $where['msd.division_id'] = base64_decode($division_id); 
    } else {
        $data['division_id'] = 'NULL';
        $base_url  .= '/NULL';
    }
    if ($created_pesron != NULL  && $created_pesron != 'NULL') {
        $data['created_pesron'] = base64_decode($created_pesron);
        $base_url  .= '/' . $created_pesron;
        $where['ap.uidnr_admin'] = base64_decode($created_pesron); 
    } else {
        $data['created_pesron'] = 'NULL';
        $base_url  .= '/NULL';
    }
    if ($search != NULL && $search != 'NULL') {
        $data['search'] =  base64_decode($search);
        $base_url .= '/' . $search;
        $search = base64_decode($search);
    } else {
        $base_url .= '/NULL';
        $data['search'] = 'NULL';
        $search = NULL;
    }
    if ($sortby != NULL && $sortby != 'NULL') {
        $base_url .= '/' . $sortby;
    } else {
        $base_url .= '/NULL';
        $sortby = NULL;
    }
    if ($order != NULL && $order != 'NULL') {
        $base_url .= '/' . $order;
    } else {
        $base_url .= '/NULL';
        $order = NULL;
    }
    $total_row = $this->am->get_user_list(NULL, NULL, $search, NULL, NULL, $where, '1');
    $config = $this->pagination($base_url, $total_row,10,11);
    $data["links"] = $config["links"];
    $data['emp_list'] = $this->am->get_user_list($config["per_page"], $config['page'],$search,$sortby,$order, $where);
    $start = (int)$page_no + 1;
    $end = (($data['emp_list']) ? count($data['emp_list']) : 0) + (($page_no) ? $page_no : 0);
    $data['result_count'] = "Showing " . $start . " - " . $end . " of " . $total_row . " Results";
    $data['brn_names'] = $this->am->fetch_emp_name(); 
    $data['asset'] = $this->am->fetch_asset();
   $data['brn_divs'] = $this->am->fetch_brn_division(); 
    $data['countries'] = $this->am->fetch_country();
   $data['created_by_name'] = $this->am->fetch_created_person();


  //echo"<pre>";  print_r($data);die;
    if ($order == NULL || $order == 'NULL') {
        $data['order'] = ($order) ? "DESC" : "ASC";
    } else {
        $data['order'] = ($order == "ASC") ? "DESC" : "ASC";
    }




    $this->load_view('assetmanagement/emp_list',$data);
}















public function edit_employee($id){
    //  $id=$this->uri->segment('3');
    //  $data['asset_id']= $id;

    $data['country'] = $this->am->fetch_all_data('mst_country');
    $data['division'] = $this->am->fetch_all_data('mst_divisions');
   $data['emp_data'] = $this->am->get_data_by_id('assets_user',base64_decode($id),'employee_id ');
    $state_id = $data['emp_data']->state_id;
    $data['state'] = $this->am->get_fields_by_id('mst_provinces','*',$state_id,'province_id');
 
//print_r($data);die;

   $this->form_validation->set_rules('employee_name', 'employee name', 'required');
   $this->form_validation->set_rules('employee_contact', 'Employee Contact', 'trim|callback_update_employee_contact');
   $this->form_validation->set_rules('status', 'Status', 'required');
    $this->form_validation->set_rules('emp_id', 'Employee ID', 'callback_update_employee_emp_id');
    $this->form_validation->set_rules('employee_mail', 'Employee E-mail', 'callback_update_employee_mail');
   if($this->form_validation->run() == true){
       $input_array = array(
        'employee_name'                 => $this->input->post('employee_name'),
        'employee_contact'                 => $this->input->post('employee_contact'),
        'emp_id'                 => $this->input->post('emp_id'),
        'employee_designation'                 => $this->input->post('employee_designation'),
        'issue_date'                 => $this->input->post('issue_date'),
        'employee_mail'                 => $this->input->post('employee_mail'),
        'division_id'                 => $this->input->post('division'),
        //'asset_id'                 => $this->input->post('asset_id'),
        'country_id'                 => $this->input->post('country'),
        'status'                   => $this->input->post('status'),
        'state_id'                 => $this->input->post('state'),
        'address'                 => $this->input->post('address'),
        'created_by'                 => $this->session->userdata('user_data')->uidnr_admin,
        'created_on'                 => date('Y-m-d H:i:s')
         

       ); 
       $employee_id = $this->am->update_data('assets_user',$input_array,['employee_id' => base64_decode($id)]);
      // echo  $this->db->last_query();die;
       if($employee_id){
           $branch_id = base64_decode($id);
        //    $devision_id = $this->input->post('division');
        //        $division_array = array(
        //            'mst_branch_divisions_branch_id'    => $branch_id,
        //            'mst_branch_divisions_division_id'    => $devision_id
        //        );
        //        $asset_id = $this->am->insert_data('mst_branch_divisions',$division_array);
               //echo  $this->db->last_query();die;


           $log_deatils = array(
               'text'          => "Edited Asset with name ".$this->input->post('employee_name'),
               'created_by'    => $this->admin_id(),
               'created_on'    => date('Y-m-d H:i:s'),
               'record_id'     => base64_decode($id),
               'source_module' => 'AssetManagement',
               'action_taken'  => 'edit_Employee'
           );

           $log= $this->am->insert_data('user_log_history',$log_deatils);
           //echo  $this->db->last_query();die;
           if($log){
               $this->session->set_flashdata('success','Employee Updated successfully.');
               $data = array(
                   'status' => 1,
                   'msg' => 'Employee Updated Successfully'
               );
               return redirect('AssetManagement/assets_userlist');    
           }
           else{
               $this->session->set_flashdata('error','Employee Updated successfully.');
               $data = array(
                   'status' => 0,
                   'msg' => 'Employee Updated Successfully'
               );
               return redirect('AssetManagement/assets_userlist');
           }
       } else{
           $this->session->set_flashdata('false','Error in Updating Employee.');
       }
   } 
   $this->load_view('assetmanagement/emp_form',$data);

}




public function update_employee_contact($field){
    $update_form = $this->input->post();
    $check_fileds = array();
    $check_fileds['LOWER(employee_contact)'] = strtolower($update_form['employee_contact']);
    $check_fileds['employee_id NOT IN ('.$update_form['employee_id'].')'] =  NULL;
    if(!empty($update_form) && !empty($update_form['employee_id']) ){
        $check_in = $this->am->get_row('*', 'assets_user' , $check_fileds);
        if($check_in){
            $this->form_validation->set_message('update_employee_contact', 'The {field} field can not be the same. "It Must be Unique!!"');
            return FALSE;
        }
        else
        {
            return TRUE;
        }
    }
    else{
        return false;
    }
}


public function update_employee_emp_id($field){
    $update_form = $this->input->post();
    $check_fileds = array();
    $check_fileds['LOWER(emp_id)'] = strtolower($update_form['emp_id']);
    $check_fileds['employee_id NOT IN ('.$update_form['employee_id'].')'] =  NULL;
    if(!empty($update_form) && !empty($update_form['employee_id']) ){
        $check_in = $this->am->get_row('*', 'assets_user' , $check_fileds);
        if($check_in){
            $this->form_validation->set_message('update_employee_emp_id', 'The {field} field can not be the same. "It Must be Unique!!"');
            return FALSE;
        }
        else
        {
            return TRUE;
        }
    }
    else{
        return false;
    }
}
public function update_employee_mail($field){
    $update_form = $this->input->post();
    $check_fileds = array();
    $check_fileds['LOWER(employee_mail)'] = strtolower($update_form['employee_mail']);
    $check_fileds['employee_id NOT IN ('.$update_form['employee_id'].')'] =  NULL;
    if(!empty($update_form) && !empty($update_form['employee_id']) ){
        $check_in = $this->am->get_row('*', 'assets_user' , $check_fileds);
        if($check_in){
            $this->form_validation->set_message('update_employee_mail', 'The {field} field can not be the same. "It Must be Unique!!"');
            return FALSE;
        }
        else
        {
            return TRUE;
        }
    }
    else{
        return false;
    }
}









public function assigned_asset(){    
    $asset_id=$this->input->post('asset_id');
$post=$this->input->post('assigned_id');

    $data['employee'] = $this->am->fetch_all_data('assets_user');
    $employee=$this->input->post('employee');
        $data=array(
            'tbl_assign_flag' =>0
        );
        $update_asset_status=   $this->am->update_data('tbl_asset_assigned',$data,['asset_id' => $asset_id]); 
 
    $status="Assigned";
    $input_array = array(
        'asset_id'                 => $this->input->post('asset_id'),
        'employee_id'                 => $this->input->post('employee'),
        'added_by'                 => $this->session->userdata('user_data')->uidnr_admin,
        'added_on'                 => date('Y-m-d H:i:s'),
        'assign_status' =>$status,
        'tbl_assign_flag'=>1.
    ); 
        $save = $this->am->insert_data('tbl_asset_assigned',$input_array);
        $this->am->update_data('tbl_assets',['assign_flag'=>3],['asset_id'=>$this->input->post('asset_id')]);
      if($save){
       
        $this->session->set_flashdata('success','Asset Assigned successfully.');
        $data = array(
            'status' => 1,
            'msg' => 'Asset Assigned Successfully'
        );
        echo json_encode($data);
    }
    else{
        $this->session->set_flashdata('error', 'Error in Maintaining Asset Details Add Log');
        $data = array(
            'status' => 0,
            'msg' => 'Error in Maintaining Asset Details Add Log'
        );
        echo json_encode($data);
    }
    
 //   echo  $this->db->last_query();die;
    }


//reassign 
public function reassigned_asset(){
    $asset_id=$this->input->post('asset_id');
    $post=$this->input->post('assigned_id');
    $empl_id = $this->am->fetch_id($post);
  //  echo  $this->db->last_query();die;
    $status="Recieved back";
$data=array(
    'asset_id'                 => $this->input->post('asset_id'),
    'employee_id'                 =>$empl_id->employee_id,
    'added_by'                 => $this->session->userdata('user_data')->uidnr_admin,
    'added_on'                 => date('Y-m-d H:i:s'),
    'assign_status' =>$status
    // 'tbl_assign_flag'=>0

);
$this->am->insert_data('tbl_asset_assigned',$data);
//echo  $this->db->last_query();die;
$update_asset_status=   $this->am->update_data('tbl_asset_assigned',['tbl_assign_flag'=>0],['asset_id' => $asset_id]); 
// echo  $this->db->last_query();die;
    $status="Assigned";
    $input_array = array(
        'asset_id'                 => $this->input->post('asset_id'),
        'employee_id'                 => $this->input->post('employee'),
        'added_by'                 => $this->session->userdata('user_data')->uidnr_admin,
        'added_on'                 => date('Y-m-d H:i:s'),
        'assign_status' =>$status,
        'tbl_assign_flag'=>1.
    ); 
        $save = $this->am->insert_data('tbl_asset_assigned',$input_array);

        $this->am->update_data('tbl_assets',['assign_flag'=>3],['asset_id'=>$this->input->post('asset_id')]);
        if($save){
         
          $this->session->set_flashdata('success','Asset Re-Assigned successfully.');
          $data = array(
              'status' => 1,
              'msg' => 'Asset Re-Assigned Successfully'
          );
          echo json_encode($data);
      }
      else{
          $this->session->set_flashdata('error', 'Error in Maintaining Asset Details Add Log');
          $data = array(
              'status' => 0,
              'msg' => 'Error in Maintaining Asset Details Add Log'
          );
          echo json_encode($data);
      }

}









    public function fetch_assign_data(){
		$asset_id = $this->input->post('asset_id');
		$data = $this->am->fetch_assign_data($asset_id);
		echo json_encode($data);
	}








public function assigned_emp(){
    $id=$this->input->post('employee_id');
    $asset_id=$this->input->post('asset_id');
    $data['asset'] = $this->am->fetch_asset();

    $asset=$this->input->post('asset');
    $this->am->update_data('tbl_asset_assigned',['tbl_assign_flag'=>0],['asset_id' => $asset_id]); 
     $status="Assigned";
   $input_array = array(
       'asset_id'                 => $this->input->post('asset'),
       'employee_id'                 => $this->input->post('employee_id'),
       'added_by'                 => $this->session->userdata('user_data')->uidnr_admin,
       'added_on'                 => date('Y-m-d H:i:s'),
       'assign_status' =>$status,
       'tbl_assign_flag'        => 1
   ); 
   $save = $this->am->insert_data('tbl_asset_assigned',$input_array);
   $this->am->update_data('tbl_assets',['assign_flag'=>3],['asset_id'=>$this->input->post('asset')]);
  // echo  $this->db->last_query();die;
       if($save){
        $this->session->set_flashdata('success','ASSETS Assigned successfully.');
        $data = array(
            'status' => 1,
            'msg' => 'ASSETS Assigned Successfully'
        );
        echo json_encode($data);
    }
    else{
        $this->session->set_flashdata('error', 'Error in Maintaining Asset Details Add Log');
        $data = array(
            'status' => 0,
            'msg' => 'Error in Maintaining Asset Details Add Log'
        );
        echo json_encode($data);
    }

}





public function free_asset(){
    $asset_id=$this->input->post('asset_id');
    $employe_info = $this->am->fetch_emp_id($asset_id);
    $employee=$this->input->post('employee');
   $this->form_validation->set_rules('remark', 'Remark', 'required');
   $this->form_validation->set_rules('status', 'Status', 'required');
  
   if ($this->form_validation->run() == true) {
    
    $input_array = array(
        'asset_id'                 => $this->input->post('asset_id'),
        'employee_id'                 => $employe_info->employee_id,
        'added_by'                 => $this->session->userdata('user_data')->uidnr_admin,
        'added_on'                 => date('Y-m-d H:i:s'),
        'remark'                 => $this->input->post('remark'),
        'assign_status' =>   'Recieved back'
        
    ); 
    $save = $this->am->insert_data('tbl_asset_assigned',$input_array);
   // $p_id = $this->db->insert_id();
    $query= $this->db->get_where('tbl_asset_assigned', array('asset_id' => $asset_id), 1, 'desc');
    $id=$query->row_array();

   $this->am->update_data('tbl_asset_assigned',['tbl_assign_flag'=>0],['asset_id'=>$id['asset_id']]);
    $this->am->update_data('assets_user',['status'=>$this->input->post('status')],['employee_id'=>$employe_info->employee_id]);
    $this->am->update_data('tbl_assets',['assign_flag'=>2],['asset_id'=>$this->input->post('asset_id')]);
    if($save){
        $this->session->set_flashdata('success','ASSET FREE successfully.');
        $data = array(
            'status' => 1,
            'msg' => 'ASSETS FREE Successfully'
        );
        echo json_encode($data);
    }
    else{
        $this->session->set_flashdata('error', 'Error in FREE Asset');
        $data = array(
            'status' => 0,
            'msg' => 'Error in FREE Asset'
        );
        echo json_encode($data);
    }
    }else {
        $data = array(
            'status' => 3,
            'errors' => $this->form_validation->error_array(),
            'msg' => 'Please Fill All Required Fields'
        );
        echo json_encode($data);
    }
   
}



}
