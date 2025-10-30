<?php
defined('BASEPATH') or exit('No direct access allowed');

class Lab extends MY_Controller{
    function __construct(){
        parent::__construct();
        $this->load->model('Lab_model','lm');
        $this->check_session();
    }

    public function index() {
        $where = NULL;
        $base_url = 'Lab/index';
        $id_lab = $this->uri->segment('3');
        $id_lab_type = $this->uri->segment('4');
        $id_division = $this->uri->segment('5');
        $id_branch = $this->uri->segment('6');
        $created_pesron = $this->uri->segment('7');
        $id_status = $this->uri->segment('8');
        $search = $this->uri->segment('9');
        $sortby = $this->uri->segment('10');
        $order = $this->uri->segment('11');
        $page_no = $this->uri->segment('12'); 
        if ($id_lab != NULL  && $id_lab != 'NULL') {
            $data['id_lab'] = base64_decode($id_lab);
            $base_url  .= '/' . $id_lab;
            $where['msl.lab_id'] = base64_decode($id_lab); 
        } else {
            $data['id_lab'] = 'NULL';
            $base_url  .= '/NULL';
        }
        if ($id_lab_type != NULL  && $id_lab_type != 'NULL') {
            $data['id_lab_type'] = base64_decode($id_lab_type);
            $base_url  .= '/' . $id_lab_type;
            $where['mlt.lab_type_id'] = base64_decode($id_lab_type); 
        } else {
            $data['id_lab_type'] = 'NULL';
            $base_url  .= '/NULL';
        }
        if ($id_division != NULL  && $id_division != 'NULL') {
            $data['id_division'] = base64_decode($id_division);
            $base_url  .= '/' . $id_division;
            $where['msd.division_id'] = base64_decode($id_division); 
        } else {
            $data['id_division'] = 'NULL';
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
        if ($id_status != NULL  && $id_status != 'NULL') {
            $data['id_status'] = base64_decode($id_status);
            $base_url  .= '/' . $id_status;
            $where['msl.status'] = base64_decode($id_status); 
        } else {
            $data['id_status'] = 'NULL';
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
        $total_row = $this->lm->get_lab_list(NULL, NULL, $search, NULL, NULL, $where, '1');
        $config = $this->pagination($base_url, $total_row,10,12);
        $data["links"] = $config["links"];
        $data['labs_list'] = $this->lm->get_lab_list($config["per_page"], $config['page'], $search, $sortby, $order, $where);
        $start = (int)$page_no + 1;
        $end = (($data['labs_list']) ? count($data['labs_list']) : 0) + (($page_no) ? $page_no : 0);
        $data['result_count'] = "Showing " . $start . " - " . $end . " of " . $total_row . " Results";
        $data['lb_name'] = $this->lm->fetch_labs();
        $data['lab_types'] = $this->lm->fetch_lab_types();
        $data['divisions'] = $this->lm->fetch_divisions(); 
        $data['brn_names'] = $this->lm->fetch_branch_name(); 
        $data['created_by_name'] = $this->lm->fetch_created_person();
        if ($order == NULL || $order == 'NULL') {
            $data['order'] = ($order) ? "DESC" : "ASC";
        } else {
            $data['order'] = ($order == "ASC") ? "DESC" : "ASC";
        }
        $this->load_view('lab/index', $data);
    }

    public function add_lab(){
        $data['division'] = $this->lm->fetch_all_data('mst_divisions');
        $data['lab_types'] = $this->lm->fetch_lab_types();
        $data['brn_names'] = $this->lm->fetch_branch_name();
        $this->form_validation->set_rules('name', 'Lab Name', 'required|trim|is_unique[mst_labs.lab_name]'); 
        $this->form_validation->set_rules('division', 'Division', 'required');
        $this->form_validation->set_rules('lab_type_id', 'Lab Type', 'required');
        $this->form_validation->set_rules('branch_id', 'Branch Name', 'required');
        $this->form_validation->set_rules('status', 'Status', 'required');
        if($this->form_validation->run() == true){
            $input_array = array(
                'lab_name'              => $this->input->post('name'),
                'mst_labs_division_id'  => $this->input->post('division'),
                'mst_labs_lab_type_id'  => $this->input->post('lab_type_id'),
                'mst_labs_branch_id'    => $this->input->post('branch_id'),
                'status'                => $this->input->post('status'),
                'created_by'            => $this->session->userdata('user_data')->uidnr_admin,
                'created_on'            => date('Y-m-d H:i:s')
            );
            $save = $this->lm->insert_data('mst_labs',$input_array);
            if($save){
                $log_deatils = array(
                    'text'          => "Added Lab with name ".$this->input->post('name'),
                    'created_by'    => $this->session->userdata('user_data')->uidnr_admin,
                    'created_on'    => date('Y-m-d H:i:s'),
                    'record_id'     => $save,
                    'source_module' => 'Lab',
                    'action_taken'  => 'add_lab'
                );

                $log = $this->lm->insert_data('user_log_history',$log_deatils);
                if($log){
                    $this->session->set_flashdata('success','Lab Details Added successfully.');
                    return redirect('Lab/index');
                }
                else{
                    $this->session->set_flashdata('error','Error in Maintaining Add Lab Log');
                    return redirect('Lab/index');
                }
            } else{
                $this->session->set_flashdata('false','Error in Adding Lab Details');
            }
        }
        $this->load_view('lab/add_lab',$data);
    }

    public function edit_lab($id){
        $data['division'] = $this->lm->fetch_all_data('mst_divisions');
        $data['lab_types'] = $this->lm->fetch_lab_types();
        $data['brn_names'] = $this->lm->fetch_branch_name();
        $data['lab'] = $this->lm->get_data_by_id('mst_labs',base64_decode($id),'lab_id');
        $this->form_validation->set_rules('name', 'Lab Name', 'trim|required|callback_update_lname');
        $this->form_validation->set_rules('division', 'Division Id', 'required');
        $this->form_validation->set_rules('lab_type_id', 'Lab Type', 'required');
        $this->form_validation->set_rules('branch_id', 'Branch Name', 'required');
        $this->form_validation->set_rules('status', 'Status', 'required');
        if($this->form_validation->run() == true){
            $input_array = array(
                'lab_name'              => $this->input->post('name'),
                'mst_labs_division_id'  => $this->input->post('division'),
                'mst_labs_lab_type_id'  => $this->input->post('lab_type_id'),
                'mst_labs_branch_id'    => $this->input->post('branch_id'),
                'status'                => $this->input->post('status'),
                'created_by'            => $this->session->userdata('user_data')->uidnr_admin,
                'updated_on'            => date('Y-m-d H:i:s')
            );

            $update = $this->lm->update_data('mst_labs',$input_array,['lab_id' => base64_decode($id)]);
            if($update){
                $log_deatils = array(
                    'text'          => "Updated Lab with name ".$this->input->post('name'),
                    'created_by'    => $this->session->userdata('user_data')->uidnr_admin,
                    'created_on'    => date('Y-m-d H:i:s'),
                    'record_id'     => $data['lab']->lab_id,
                    'source_module' => 'Lab',
                    'action_taken'  => 'edit_lab'
                );
                $log = $this->lm->insert_data('user_log_history',$log_deatils);
                if($log){
                    $this->session->set_flashdata('success','Lab Details updated successfully.');
                    return redirect('Lab/index');
                }
                else{
                    $this->session->set_flashdata('error','Error in Maintaining Update Lab Log ');
                    return redirect('Lab/index');
                }
                
            } else{
                $this->session->set_flashdata('false','Something went wrong!.');
            }
        }
        $this->load_view('lab/add_lab',$data);
    }

    /* added by millan*/ 
    public function lab_status() {
        if (!empty($this->input->post())) {
            $checkUser = $this->session->userdata();
            $data_fetch = $this->lm->fetch_lab_for_edit($this->input->post());
            $lab_name  = $data_fetch->lab_name ;
            $status = $this->lm->update_lab_status($this->input->post());
            if ($status) {
                $log_deatils = array(
                    'text'          => "Updated Lab Name Status with name ".$lab_name,
                    'created_by'    => $checkUser['user_data']->uidnr_admin,
                    'created_on'    => date('Y-m-d h:i:s'),
                    'record_id'     => $this->input->post('lab_id'),
                    'source_module' => 'Lab',
                    'action_taken'  => 'lab_status'
                );
                $log = $this->lm->insert_data('user_log_history',$log_deatils);
                if($log){
                    $this->session->set_flashdata('success', 'Lab Status Updated Successfully');
                    $data = array(
                        'status' => 1,
                        'msg' => 'Lab Status Updated Successfully'
                    );
                }
                else{
                    $this->session->set_flashdata('error', 'Error in Maintaining Lab Status Log');
                    $data = array(
                        'status' => 0,
                        'msg' => 'Error in Maintaining Lab Status Log'
                    );
                }
            } else {
                $this->session->set_flashdata('error', 'Error in Updating Lab Status');
                $data = array(
                    'status' => 0,
                    'msg' => 'Error in Updating Lab Status'
                );
            }
        }
        echo json_encode($data);
    }

    // added by millan on 09-April-2021 
    public function update_lname($field){
        $update_form = $this->input->post();
        $check_fileds = array();
        $check_fileds['LOWER(lab_name)'] = $update_form['name'];
        $check_fileds['mst_labs_lab_type_id'] = $update_form['lab_type_id'];
        $check_fileds['mst_labs_branch_id'] = $update_form['branch_id'];
        $check_fileds['status'] = $update_form['status'];
        $check_fileds['lab_id NOT IN ('.$update_form['lab_id'].')'] =  NULL;
        if(!empty($update_form) && !empty($update_form['lab_id']) ){
            $check_in = $this->lm->get_row('*', 'mst_labs' , $check_fileds);
            if($check_in){
                $this->form_validation->set_message('update_lname', 'The {field} field can not be the same. "It Must be Unique!!"');
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

    // added by millan on 09-April-2021
    public function get_lab_log(){
		$lab_id = $this->input->post('lab_id');
		$data = $this->lm->get_lab_log($lab_id);
		echo json_encode($data);
	}
}
?>