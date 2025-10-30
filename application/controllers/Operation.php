<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Operation extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();   
        $this->load->model('Operation_model','OM'); 
        $this->permission('Operation/index');    
        $checkUser = $this->session->userdata('user_data');
        $this->user = $checkUser->uidnr_admin;
    }

    public function index()
    {
        $this->load_view('operation/index');
    }
    public function operation_listing($search,$page)
    {
        $where = NULL;
        if (!empty($search) && $search != 'NULL') {
            $search = base64_decode($search);
        } else {
            $search = NULL;
        }
        $per_page = 10;
        if ($page != 0) {
            $page = ($page - 1) * $per_page;
        }
        
        $total_row = $this->OM->get_operation_list(NULL, NULL, $search, $where, '1');
        $config['base_url'] = base_url('Operation/operation_listing');
        $config['use_page_numbers'] = TRUE;
        $config['total_rows'] = $total_row;
        $config['per_page'] = $per_page;
        $config['full_tag_open']    = '<div class="pagination text-center small"><nav><ul class="pagination">';
        $config['full_tag_close']   = '</ul></nav></div>';
        $config['num_tag_open']     = '<li class="page-item"><span class="page-link">';
        $config['num_tag_close']    = '</span></li>';
        $config['cur_tag_open']     = '<li class="page-item active"><span class="page-link">';
        $config['cur_tag_close']    = '<span class="sr-only">(current)</span></span></li>';
        $config['next_tag_open']    = '<li class="page-item"><span class="page-link">';
        $config['next_tag_close']  = '<span aria-hidden="true"></span></span></li>';
        $config['prev_tag_open']    = '<li class="page-item"><span class="page-link">';
        $config['prev_tag_close']  = '</span></li>';
        $config['first_tag_open']   = '<li class="page-item"><span class="page-link">';
        $config['first_tag_close'] = '</span></li>';
        $config['last_tag_open']    = '<li class="page-item"><span class="page-link">';
        $config['last_tag_close']  = '</span></li>';
        $this->pagination->initialize($config);
        $data['pagination'] =  $this->pagination->create_links();
        $result = $this->OM->get_operation_list($per_page, $page, $search, $where);
        if($total_row > 0){
        $start = (int)$page + 1;
        $end = (($result) ? count($result) : 0) + (($page) ? $page : 0);
        $data['result_count'] = "Showing " . $start . " - " . $end . " of " . $total_row . " Results";
        }
        
        $html = '';
        if ($result) {
            foreach ($result as $value) {
                $page++;
                $html .= '<tr>';
                $html .= '<th scope="col">' . $page . '</th>';
                $html .= '<td>' . $value->controller_name . '</td>';
                $html .= '<td>' . $value->function_name . '</td>';
                $html .= '<td>' . $value->alias . '</td>';
                $html .= '<td>';
                // if ($this->permission_action('Operation/edit')) {
                    $html .= '<a title="EDIT" href="javascript:void(0);" class="edit" data-toggle="modal" data-id="' . base64_encode($value->function_id) . '" data-target="#edit_operation"><img width="28px" src="' . base_url('public/img/icon/edit.png') . '" alt="BASIL" class="edit_application_data" ></a>';
                // }
                $html .= '&nbsp;&nbsp;<a href="javascript:void(0)" data-id="'.$value->function_id.'" class="log_view" data-toggle="modal" data-target="#exampleModal" class="btn btn-sm" title="Log View"><img src="'.base_url('assets/images/log-view.png').'" alt="Log view" width="20px"></a>';
                $html .= '</td>';
                $html .= '/<tr>';
            }
        } else {
            $html .= '<tr><td colspan="14"><h4>NO RECORD FOUND</h4></td></tr>';
        }
        $data['result'] = $html;
        echo json_encode($data);
    }
    public function add(){
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');
        $this->form_validation->set_rules('function_name', 'FUNCTION NAME', 'trim|required|min_length[3]');
        $this->form_validation->set_rules('controller_name', 'CONTROLLER NAME', 'trim|required|min_length[3]');
        $this->form_validation->set_rules('alias', 'ALIAS NAME', 'trim|required|min_length[3]');
        if ($this->form_validation->run() == TRUE) {
            $post = $this->input->post();
            $insert = $this->OM->insert_data('functions', $post);
            if ($insert) {
                $this->log_history(['action' => 'ADD OPERATION NAME ' . $post['alias'],'id' => $insert, 'action_taken' => 'add']);
                $msg = array(
                    'status' => 1,
                    'msg' => 'SUCCESSFULLY INSERT OPERATION'
                );
            } else {
                $msg = array(
                    'status' => 0,
                    'msg' => 'ERROR WHILE INSERT'
                );
            }
        } else {
            $msg = array(
                'status' => 0,
                'errors' => validation_errors(),
                'msg' => 'PLEASE ENTER VALID TEXT'
            );
        }
        echo json_encode($msg);
    }
    public function operation_get()
    {
        $post = $this->input->post();
        $post['id'] = base64_decode($post['id']);
        echo json_encode($this->OM->get_row('*', 'functions', ['function_id' => $post['id']]));
    }
    public function edit()
    {
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');
        $this->form_validation->set_rules('function_id', 'UNIQUE ID', 'trim|required');
        $this->form_validation->set_rules('function_name', 'FUNCTION NAME', 'trim|required|min_length[3]');
        $this->form_validation->set_rules('controller_name', 'CONTROLLER NAME', 'trim|required|min_length[3]');
        $this->form_validation->set_rules('alias', 'ALIAS NAME', 'trim|required|min_length[3]');
        if ($this->form_validation->run() == TRUE) {
            $post = $this->input->post();
            $get = $this->OM->get_row('alias','functions',['function_id'=>$post['function_id']]);
            $insert = $this->OM->update_data('functions', $post,['function_id'=>$post['function_id']]);
            if ($insert) {
                $this->log_history(['action' => 'EDIT OPERATION NAME CHANGE '. $get->alias . ' TO ' . $post['alias'],'id'=>$post['function_id'], 'action_taken' => 'edit']);
                $msg = array(
                    'status' => 1,
                    'msg' => 'SUCCESSFULLY EDIT OPERATION'
                );
            } else {
                $msg = array(
                    'status' => 0,
                    'msg' => 'ERROR WHILE INSERT'
                );
            }
        } else {
            $msg = array(
                'status' => 0,
                'errors' => validation_errors(),
                'msg' => 'PLEASE ENTER VALID TEXT'
            );
        }
        echo json_encode($msg);
    }
    public function log_history($reason)
    {
        $array = array(
            'text'=>$reason['action'],
            'record_id'=>$reason['id'],
            'source_module' => 'Operation',
            'action_taken' => $reason['action_taken'],
            'created_by'=> $this->user
        );
       $this->OM->insert_data('user_log_history', $array);
    }

    // Get operation log
	public function get_operation_log()
	{
		$operation_id = $this->input->post('operation_id');
		$data = $this->OM->get_operation_log($operation_id);
		echo json_encode($data);
	}
}
