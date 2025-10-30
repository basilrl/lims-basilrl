<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Role extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Role_model');
        // $this->permission('Role/index');
        $checkUser = $this->session->userdata('user_data');
        $this->user = $checkUser->uidnr_admin;
    }

    public function index()
    {
        $this->load_view('role/index');
    }

    public function role_listing($search, $page = 0)
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
        $total_row = $this->Role_model->get_role_list(NULL, NULL, $search, $where, '1');
        $config['base_url'] = base_url('Role/role_listing');
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

        $result = $this->Role_model->get_role_list($per_page, $page, $search, $where);
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
                $html .= '<td>' . $value->admin_role_name . '</td>';
                $html .= '<td>' . (($value->status > 0) ? 'ACTIVE' : 'IN-ACTIVE') . '</td>';
                $html .= '<td>';
                // if ($this->permission_action('Role/edit')) {
                $html .= '<a href="javascript:void(0);" title="UPLOAD DOCUMENTS" class="btn btn-sm edit_role" data-bs-toggle="modal" data-id="' . base64_encode($value->id_admin_role) . '" data-bs-target="#edit_application"><img width="28px" src="' . base_url('public/img/icon/edit.png') . '" alt="BASIL" class="edit_application_data" ></a> ';
                // }
                // if ($this->permission_action('Role/save_permission')) {
                $html .= '<a title="PERMISSION" href="javascript:void(0);" class="btn btn-sm permission" data-bs-toggle="modal" data-id="' . base64_encode($value->id_admin_role) . '" data-bs-target="#role_permission"><img width="28px" src="' . base_url('public/img/icon/permission_role.png') . '" alt="BASIL" class="edit_application_data" ></a>';

                $html .= '<a href="javascript:void(0)" data-id="'.$value->id_admin_role.'" class="log_view" data-bs-toggle="modal" data-bs-target="#exampleModal" class="btn btn-sm" title="Log View"><img src="'.base_url('assets/images/log-view.png').'" alt="Log view" width="20px"></a>';
                // }
                $html .= '</td>';
                $html .= '/<tr>';
            }
        } else {
            $html .= '<tr><td colspan="14"><h4>NO RECORD FOUND</h4></td></tr>';
        }
        $data['result'] = $html;
        echo json_encode($data);
    }
    public function add()
    {
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');
        $this->form_validation->set_rules('admin_role_name', 'ROLE NAME', 'trim|required|min_length[3]|is_unique[admin_role.admin_role_name]');
        if ($this->form_validation->run() == TRUE) {
            $post = $this->input->post();
            $insert = $this->Role_model->insert_data('admin_role', $post);
            if ($insert) {
                $this->log_history(['action' => ' ADD ROLE NAME ' . $post['admin_role_name'],'id' => $insert,'source_module' => 'Role', 'action_taken' => 'Added Role']);
                $msg = array(
                    'status' => 1,
                    'msg' => 'SUCCESSFULLY INSERT ROLE'
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
    public function role_get()
    {
        $post = $this->input->post();
        $post['id'] = base64_decode($post['id']);
        echo json_encode($this->Role_model->get_row('*', 'admin_role', ['id_admin_role' => $post['id']]));
    }
    public function edit()
    {
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');
        $this->form_validation->set_rules('id_admin_role', 'UNIQUE', 'trim|required');
        $this->form_validation->set_rules('admin_role_name', 'ROLE NAME', 'trim|required|min_length[3]');
        if ($this->form_validation->run() == TRUE) {
            $post = $this->input->post();
            $role = $this->Role_model->get_row('*', 'admin_role', ['id_admin_role' => $post['id_admin_role']]);
            if ($role) {
                $insert = $this->Role_model->update_data('admin_role', $post, ['id_admin_role' => $post['id_admin_role']]);
                if ($insert) {
                    $this->log_history(['action' => 'ROLE NAME CHANGE ' . $role->admin_role_name . ' TO ' . $post['admin_role_name'],'id' => $post['id_admin_role'], 'source_module' => 'Role', 'action_taken' => 'Edited Role']);
                    $msg = array(
                        'status' => 1,
                        'msg' => 'SUCCESSFULLY INSERT ROLE'
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
                    'msg' => 'NO RECORD FOUND WHILE UPDATE'
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
    public function fetch_functions($controller)
    {
        return $this->Role_model->fetch_functions($controller);
    }
    public function fetch_controller()
    {
        return $this->Role_model->fetch_controller();
    }
    public function fetch_permission()
    {
        $post = $this->input->post();
        $post['role_id'] = base64_decode($post['role_id']);
        $functions = $this->Role_model->fetch_permission($post);
        if ($functions)
            echo json_encode($functions);
        else
            echo false;
    }
    public function save_permission()
    {
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');
        $this->form_validation->set_rules('role_id', 'UNIQUE ID', 'trim|required');
        if ($this->form_validation->run() == TRUE) {
            $roleID = base64_decode($this->input->post('role_id'));
            $functionID = $this->input->post('function_id');
            if (count($functionID) > 1) {
                $functionID = implode(',', $functionID);
            } else {
                $functionID = current($functionID);
            }
            $functions = $this->Role_model->save_permission($roleID, $functionID);
            if ($functions) {
                $this->log_history(['action' => 'Updated permission for Role id :-'.$roleID,'id' => $roleID, 'source_module' => 'Role', 'action_taken' => 'Edited Role']);
                $msg = array(
                    'status' => 1,
                    'msg' => 'PERMISSION SET SUCCESSFULLY'
                );
            } else {
                $msg = array(
                    'status' => 0,
                    'msg' => 'PERMISSION NOT SET'
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
    public function fetch_list()
    {
        $controller = $this->fetch_controller();
        if ($controller) {
            $html = '<div class="col-4"><div class="list-group" id="list-tab" role="tablist" style=" max-height:60vh; overflow-y:scroll;">';
            $functions_html = '<div class="col-8"><div class="tab-content" id="nav-tabContent">';
            foreach ($controller as $key => $value) {
                $functions = $this->fetch_functions(['controller_name' => $value->controller_name]);
                $functions_html .= '<div class="tab-pane fade" id="list-' . $value->controller_name . '" role="tabpanel" aria-labelledby="list-home-list"><div class="row">';
                $html .= '<a class="list-group-item list-group-item-action" id="list-' . $value->controller_name . '-list" data-bs-toggle="list" href="#list-' . $value->controller_name . '" role="tab" aria-selected="true">' . $value->controller_name . "</a>";
                foreach ($functions as $key1 => $value1) {
                    $functions_html .=  '<div class="col-sm-4"><div class="form-check form-check-inline"><input class="form-check-input" type="checkbox" name="function_id[]" value="' . $value1->function_id . '"><label class="form-check-label" for="inlineCheckbox1">' . $value1->alias . "</label></div></div>";
                }
                $functions_html .= '</div></div>';
            }
            $html .= ' </div></div>';
            $functions_html .= '</div></div>';
            $controller = $html . $functions_html;
        } else {
            $controller = false;
        }
        echo json_encode($controller);
    }
    public function log_history($reason)
    {
        $array = array(
            'text'=>$reason['action'],
            'record_id'=>$reason['id'],
            'source_module'  => $reason['source_module'],
            'action_taken'  => $reason['action_taken'],
            'created_by'=> $this->user
        );
       $this->Role_model->insert_data('user_log_history', $array);
    //    echo $this->db->last_query(); die;
    }

    // Get division log
	public function get_role_log()
	{
		$role_id = $this->input->post('role_id');
		$data = $this->Role_model->get_role_log($role_id);
		echo json_encode($data);
	}
}
