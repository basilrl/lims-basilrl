<?php
defined('BASEPATH') or exit('No direct access allowed');

class Branch extends MY_Controller{

    function __construct(){
        parent::__construct();
        $this->check_session();
        $this->load->model('Branch_model','bm');
    }        

    public function index() {
        $where = NULL;
        $base_url = 'Branch/index';
        $name_branch = $this->uri->segment('3');
        $id_brn_code = $this->uri->segment('4');
        $id_country = $this->uri->segment('5');
        $id_state = $this->uri->segment('6'); 
        $created_pesron = $this->uri->segment('7');
        $search = $this->uri->segment('8');
        $sortby = $this->uri->segment('9');
        $order = $this->uri->segment('10');
        $page_no = $this->uri->segment('11');
        if ($name_branch != NULL  && $name_branch != 'NULL') {
            $data['name_branch'] = base64_decode($name_branch);
            $base_url  .= '/' . $name_branch;
            $where['msb.branch_name'] = base64_decode($name_branch); 
        } else {
            $data['name_branch'] = 'NULL';
            $base_url  .= '/NULL';
        }
        if ($id_brn_code != NULL  && $id_brn_code != 'NULL') {
            $data['id_brn_code'] = base64_decode($id_brn_code);
            $base_url  .= '/' . $id_brn_code;
            $where['msb.branch_code'] = base64_decode($id_brn_code); 
        } else {
            $data['id_brn_code'] = 'NULL';
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
        if ($id_state != NULL  && $id_state != 'NULL') {
            $data['id_state'] = base64_decode($id_state);
            $base_url  .= '/' . $id_state;
            $where['msp.province_id'] = base64_decode($id_state); 
        } else {
            $data['id_state'] = 'NULL';
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
        $total_row = $this->bm->get_branch_list(NULL, NULL, $search, NULL, NULL, $where, '1');
        $config = $this->pagination($base_url, $total_row,10,11);
        $data["links"] = $config["links"];
        $data['brn_list'] = $this->bm->get_branch_list($config["per_page"], $config['page'],$search,$sortby,$order, $where);
        $start = (int)$page_no + 1;
        $end = (($data['brn_list']) ? count($data['brn_list']) : 0) + (($page_no) ? $page_no : 0);
        $data['result_count'] = "Showing " . $start . " - " . $end . " of " . $total_row . " Results";
        $data['brn_names'] = $this->bm->fetch_branch_name(); 
        $data['brn_codes'] = $this->bm->fetch_branch_code(); 
        $data['countries'] = $this->bm->fetch_country();
        $data['states'] = $this->bm->fetch_state();
        $data['created_by_name'] = $this->bm->fetch_created_person();
        if ($order == NULL || $order == 'NULL') {
            $data['order'] = ($order) ? "DESC" : "ASC";
        } else {
            $data['order'] = ($order == "ASC") ? "DESC" : "ASC";
        }
        $this->load_view('branch/index', $data);
    }

    public function add_branch(){
        $data['country'] = $this->bm->fetch_all_data('mst_country');
        $data['currency'] = $this->bm->fetch_all_data('mst_currency');
        $data['division'] = $this->bm->fetch_all_data('mst_divisions');
        $this->form_validation->set_rules('branch_code', 'Branch Code', 'trim|required|is_unique[mst_branches.branch_code]');
        $this->form_validation->set_rules('branch_name', 'Branch Name', 'trim|min_length[2]|is_unique[mst_branches.branch_name]');
        $this->form_validation->set_rules('telephone', 'Telephone', 'trim|required');
        $this->form_validation->set_rules('country', 'Country Name', 'trim|required');
        $this->form_validation->set_rules('currency', 'Currency Name', 'trim');
        $this->form_validation->set_rules('division[]', 'Division Name', 'trim');
        $this->form_validation->set_rules('address', 'Address', 'trim');
        $this->form_validation->set_rules('state', 'State', 'trim');
        // echo '<pre>';
        // print_r($this->input->post());exit;
        if($this->form_validation->run() == true){
            $input_array = array(
                'branch_name'                 => $this->input->post('branch_name'),
                'branch_code'                 => $this->input->post('branch_code'),
                'branch_telephone'            => $this->input->post('telephone'),
                'mst_branches_country_id'     => $this->input->post('country'),
                'mst_state_id'                => $this->input->post('state'),
                'mst_branches_currency_id'    => $this->input->post('currency'),
                'branch_address'              => $this->input->post('address'),
                'status'                      => $this->input->post('status'),
                'created_by'                  => $this->session->userdata('user_data')->uidnr_admin,
                'created_on'                  => date('Y-m-d H:i:s')
            ); 
            $save = $this->bm->insert_data('mst_branches',$input_array);
            if($save){
                $branch_id = $save;
                $division = $this->input->post('division');
                foreach($division as $division_id){
                    $division_array = array(
                        'mst_branch_divisions_branch_id'    => $branch_id,
                        'mst_branch_divisions_division_id'  => $division_id,
                    );
                    $save = $this->bm->insert_data('mst_branch_divisions',$division_array);
                }

                $log_deatils = array(
                    'text'          => "Added Branch with name ".$this->input->post('branch_name'),
                    'created_by'    => $this->admin_id(),
                    'created_on'    => date('Y-m-d H:i:s'),
                    'record_id'     => $branch_id,
                    'source_module' => 'Branch',
                    'action_taken'  => 'add_branch'
                );

                $log = $this->bm->insert_data('user_log_history',$log_deatils);
                // echo $this->db->last_query(); die;
                if($log){
                    $this->session->set_flashdata('success','Branch Added successfully.');
                    $data = array(
                        'status' => 1,
                        'msg' => 'Branch Added Successfully'
                    );
                    return redirect('Branch/index');
                }
                else{
                    $this->session->set_flashdata('error', 'Error in Maintaining Branch Add Log');
                    $data = array(
                        'status' => 0,
                        'msg' => 'Error in Maintaining Branch Add Log'
                    );
                    return redirect('Branch/index');
                }
            } else{
                $this->session->set_flashdata('false','Error in Adding Branch.');
            }
        } 
        $this->load_view('branch/add_branch',$data);
    }

    public function edit_branch($id){
        $data['country'] = $this->bm->fetch_all_data('mst_country');
        $data['currency'] = $this->bm->fetch_all_data('mst_currency');
        $data['division'] = $this->bm->fetch_all_data('mst_divisions');
        $data['branch'] = $this->bm->get_data_by_id('mst_branches',base64_decode($id),'branch_id ');
        $state_id = $data['branch']->mst_state_id;
        $data['state'] = $this->bm->get_fields_by_id('mst_provinces','*',$state_id,'province_id');
        // echo "<pre>"; print_r($data); die;
        $division = [];
        $selected_division = $this->bm->get_fields_by_id('mst_branch_divisions','*',base64_decode($id),'mst_branch_divisions_branch_id ');
        foreach($selected_division as $sel_division){
            $divisions = $sel_division['mst_branch_divisions_division_id'];
            $division[] = $divisions;
        }
        $data['selected_division'] = $division;
        $this->form_validation->set_rules('branch_code', 'Branch Code', 'trim|required|callback_update_brn_code');
        $this->form_validation->set_rules('branch_name', 'Branch Name', 'trim|min_length[2]|callback_update_brn_name');
        $this->form_validation->set_rules('country', 'Country Name', 'trim|required');
        $this->form_validation->set_rules('currency', 'Currency Name', 'trim');
        $this->form_validation->set_rules('division[]', 'Division Name', 'trim');
        $this->form_validation->set_rules('address', 'Address', 'trim');
        $this->form_validation->set_rules('state', 'State', 'trim');
        if($this->form_validation->run() == true){
            $input_array = array(
                'branch_name'                 => $this->input->post('branch_name'),
                'branch_code'                 => $this->input->post('branch_code'),
                'branch_telephone'            => $this->input->post('telephone'),
                'mst_branches_country_id'     => $this->input->post('country'),
                'mst_state_id'                => $this->input->post('state'),
                'mst_branches_currency_id'    => $this->input->post('currency'),
                'branch_address'              => $this->input->post('address'),
                'status'                      => $this->input->post('status'),
                'created_by'                  => $this->session->userdata('user_data')->uidnr_admin,
                'updated_on'                  => date('Y-m-d H:i:s')
            ); 
            $save = $this->bm->update_data('mst_branches',$input_array,['branch_id' => base64_decode($id)]);
            if($save){
                $branch_id = base64_decode($id);
                $delete = $this->db->delete('mst_branch_divisions',array('mst_branch_divisions_branch_id' => base64_decode($id)) );
                $division = $this->input->post('division');
                foreach($division as $division_id){
                    $division_array = array(
                        'mst_branch_divisions_branch_id'    => $branch_id,
                        'mst_branch_divisions_division_id'  => $division_id,
                    );
                    $save = $this->bm->insert_data('mst_branch_divisions',$division_array);
                }
                $log_deatils = array(
                    'text'          => "Edited Branch with name ".$this->input->post('branch_name'),
                    'created_by'    => $this->admin_id(),
                    'created_on'    => date('Y-m-d H:i:s'),
                    'record_id'     => $branch_id,
                    'source_module' => 'Branch',
                    'action_taken'  => 'edit_branch'
                );

                $log= $this->bm->insert_data('user_log_history',$log_deatils);
                if($log){
                    $this->session->set_flashdata('success','Branch Updated successfully.');
                    $data = array(
                        'status' => 1,
                        'msg' => 'Branch Updated Successfully'
                    );
                    return redirect('Branch/index');    
                }
                else{
                    $this->session->set_flashdata('error','Branch Updated successfully.');
                    $data = array(
                        'status' => 0,
                        'msg' => 'Branch Updated Successfully'
                    );
                    return redirect('Branch/index');
                }
            } else{
                $this->session->set_flashdata('false','Error in Updating Branch.');
            }
        } 
        $this->load_view('branch/add_branch',$data);
    }

    public function get_location(){
        $state_id = $this->input->post('state');
        $location = $this->bm->get_fields_by_id('mst_locations','*',$state_id,'mst_locations_province_id');
        echo json_encode($location);
    }

    public function add_lab_details(){
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
        $this->form_validation->set_rules('lab_name', 'Lab Name', 'required|trim');
        $this->form_validation->set_rules('mst_labs_division_id', 'Division', 'required');
        $this->form_validation->set_rules('status', 'Status', 'required');
        if ($this->form_validation->run() == false) {
            $data = array(
                'error' => $this->form_validation->error_array(),
                'status' => 0
            );
        } else {
            $checkUser = $this->session->userdata();
            $store_data = array();
            $fetch_data = $this->input->post();
            $store_data['mst_labs_branch_id'] = $fetch_data['mst_labs_branch_id'];
            $store_data['lab_name'] = $fetch_data['lab_name'];
            $store_data['mst_labs_division_id'] = $fetch_data['mst_labs_division_id'];
            $store_data['status'] = $fetch_data['status'];
            $store_data['mst_labs_lab_type_id'] = $fetch_data['mst_labs_lab_type_id']; // added by millan on 01-Marc-2021 
            $store_data['created_by'] = $checkUser['user_data']->uidnr_admin;
            $store_data['created_on'] = date("Y-m-d h:i:s");
            if (!empty($fetch_data)) {
                $insert_data = $this->bm->insert_data('mst_labs', $store_data);
                if($insert_data){
                    $this->session->set_flashdata('success', 'Lab added Successfully');
                    $data = array(
                        'status' => 1
                    );
                }
                else {
                    $this->session->set_flashdata('error', 'Error in adding Lab');
                    $data = array(
                        'status' => 0
                    );
                }
            } else {
                $this->session->set_flashdata('error', 'Data Not Found !!');
                $data = array(
                    'status' => 0
                );
            }
        }
        echo json_encode($data);
    }

    public function get_province(){
        $country_id = $this->input->post('country');
        $state = $this->bm->get_fields_by_id('mst_provinces','*',$country_id,'mst_provinces_country_id');
        echo json_encode($state);
    }

    public function branch_status() {
        if (!empty($this->input->post())) {
            $checkUser = $this->session->userdata();
            $data_fetch = $this->bm->fetch_branch_for_edit($this->input->post('branch_id'));
            $branch_name = $data_fetch->branch_name;
            $status = $this->bm->update_branch_status($this->input->post());
            if ($status) {
                $log_deatils = array(
                    'text'          => "Updated Branch Status with Branch name ".$branch_name,
                    'created_by'    => $checkUser['user_data']->uidnr_admin,
                    'created_on'    => date('Y-m-d h:i:s'),
                    'record_id'     => $this->input->post('branch_id'),
                    'source_module' => 'Branch',
                    'action_taken'  => 'branch_status'
                );
                $log = $this->bm->insert_data('user_log_history',$log_deatils);
                if($log){
                    $this->session->set_flashdata('success', 'Branch Status Updated Successfully');
                    $data = array(
                        'status' => 1,
                        'msg' => 'Branch Status Updated Successfully'
                    );
                }
                else{
                    $this->session->set_flashdata('error', 'Error in Maintaining Branch Status Log');
                    $data = array(
                        'status' => 0,
                        'msg' => 'Error in Maintaining Branch Status Log'
                    );
                }
            } else {
                $this->session->set_flashdata('error', 'Error in Updating Branch Status');
                $data = array(
                    'status' => 0,
                    'msg' => 'Error in Updating Branch Status'
                );
            }
        }
        echo json_encode($data);
    }

    public function get_branch_log(){
		$branch_id = $this->input->post('branch_id');
		$data = $this->bm->get_branch_log($branch_id);
		echo json_encode($data);
	}

    // added by millan on 15-April-2021
    public function update_brn_code($field){
        $update_form = $this->input->post();
        $check_fileds = array();
        $check_fileds['LOWER(branch_code)'] = strtolower($update_form['branch_code']);
        $check_fileds['branch_id NOT IN ('.$update_form['branch_id'].')'] =  NULL;
        if(!empty($update_form) && !empty($update_form['branch_id']) ){
            $check_in = $this->bm->get_row('*', 'mst_branches' , $check_fileds);
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

    // added by millan on 15-April-2021
    public function update_brn_name($field){
        $update_form = $this->input->post();
        $check_fileds = array();
        $check_fileds['LOWER(branch_name)'] = strtolower($update_form['branch_name']);
        $check_fileds['branch_id NOT IN ('.$update_form['branch_id'].')'] =  NULL;
        if(!empty($update_form) && !empty($update_form['branch_id']) ){
            $check_in = $this->bm->get_row('*', 'mst_branches' , $check_fileds);
            if($check_in){
                $this->form_validation->set_message('update_brn_name', 'The {field} field can not be the same. "It Must be Unique!!"');
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
}    
?>