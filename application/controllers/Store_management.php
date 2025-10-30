<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Store_management extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Store_model');
        // $this->permission('Role/index');
        $checkUser = $this->session->userdata('user_data');
        $this->user = $checkUser->uidnr_admin;
    }

    public function index()
    {
        $this->load_view('Store/index');
    }

    public function store_listing($search, $page = 0)
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
        $total_row = $this->Store_model->get_role_list(NULL, NULL, $search, $where, '1');
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

        $result = $this->Store_model->get_role_list($per_page, $page, $search, $where);
        $html = '';
        if ($result) {
            foreach ($result as $value) {
                $page++;
                $html .= '<tr>';
                $html .= '<th scope="col">' . $page . '</th>';
                $html .= '<td>' . $value->store_name . '</td>';
                $html .= '<td>' . $value->store_branches . '</td>';
                $html .= '<td>' . $value->store_keeper . '</td>';
                $html .= '<td>' . date('d-m-Y',strtotime($value->created_on)) . '</td>';
                $html .= '<td>';
                if ($this->permission_action('Store_management/edit')) {
                    $html .= '<a href="javascript:void(0);" title="EDIT" class="btn btn-sm edit_role" data-toggle="modal" data-id="' . base64_encode($value->store_id) . '" data-target="#add_Store"><img width="28px" src="' . base_url('public/img/icon/edit.png') . '" alt="BASIL" class="edit_application_data" ></a> ';
                }
                if ($this->permission_action('Store_management/deleteStore')) {
                    $html .= '<a title="DELETE STORE" href="'.base_url('Store_management/deleteStore/'.base64_encode($value->store_id)).'" class="btn btn-sm" ><img src="' . base_url('assets/images/del.png') . '" alt="BASIL" ></a>';
                }
                if ($this->permission_action('Store_management/log')) {
                    $html .= '<a title="USER LOG" href="javascript:void(0);" class="btn btn-sm log" data-toggle="modal" data-id="' . base64_encode($value->store_id) . '" data-target="#user_log"><img width="28px" src="' . base_url('public/img/icon/logs.png') . '" alt="BASIL" ></a>';
                }
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
        $post = $this->input->post();
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');
        if (!empty($post['store_id']) && $post['store_id']>0) {
            $this->form_validation->set_rules('store_id', 'UNIQUE ID', 'trim|required');
            $this->form_validation->set_rules('store_name', 'Store Name', 'trim|required|callback_check_Store');
        }else{
            $this->form_validation->set_rules('store_name', 'Store Name', 'trim|required|min_length[2]|is_unique[stores.store_name]');
        }
        $this->form_validation->set_rules('store_branch_id', 'Branch', 'trim|required');
        $this->form_validation->set_rules('store_store_keeper_id', 'Store Keeper', 'trim|required');
        if ($this->form_validation->run() == TRUE) {
            $logDetails = array('module' => 'Store','is_deleted' => '','store_id' => $post['store_id'],'lab_id' => '',);
            $msg = array();
            if (!array_key_exists('low_stock_notif_req',$post)) {
                $post['low_stock_notif_req'] = 0;
            }
            if (!array_key_exists('main_store',$post)) {
                $post['main_store'] = 0;
            }
            if (!empty($post['store_id'])) {
               $insert = $this->Store_model->update_data('stores', $post,['store_id'=>$post['store_id']]);
               $logDetails['store_id']=$post['store_id'];
               $logDetails['action_message'] = $msg['msg'] = 'Updated Store Details.';
            }else{
                unset($post['store_id']);
                $post['created_by'] = $this->user;
                $post['created_on'] = date("Y/m/d H:i:s");
                $insert = $this->Store_model->insert_data('stores', $post);
                if ($insert) {
                    $logDetails['store_id'] = $insert;
                    $logDetails['action_message'] = $msg['msg']='Created New Store.';
                }
            }
            if ($insert) {
                $this->Store_model->insert_data('store_log', $logDetails);
                $msg['status'] = 1;
            } else {
                $msg = array(
                    'status' => 0,
                    'msg' => 'ERROR WHILE SEND'
                );
            }
        } else {
            $msg = array(
                'status' => 0,
                'errors' =>$this->form_validation->error_array(),
                'msg' => 'PLEASE ENTER VALID TEXT'
            );
        }
        echo json_encode($msg);
    }
    public function check_Store($file)
    {
            $files = $this->input->post();
            $count = $this->Store_model->get_row('count(*) as count','stores',['LOWER(store_name)'=>strtolower($files['store_name']),'store_id NOT IN ('.$files['store_id'].')'=>null]);
            if ($count && $count->count > 0) {
                $this->form_validation->set_message('check_Store', 'Store Name Already Exist');
                return false;
            }
            return true;
    }
    public function Store_management_get()
    {
        $post = $this->input->post();
        $post['id'] = base64_decode($post['id']);
        echo json_encode($this->Store_model->get_row('*', 'stores', ['store_id' => $post['id']]));
    }
    
    public function branch_Store()
    {
        echo json_encode($this->Store_model->branch_Store());
    }
    public function store_keeper_store()
    {
        echo json_encode($this->Store_model->store_keeper_store());
    }
    public function store_userlog_dtlsview()
    {
        $post = $this->input->post();
        $post['id'] = base64_decode($post['id']);
        $result = $this->Store_model->get_result('store_log_id , (SELECT CONCAT( admin_profile.admin_fname, " ", admin_profile.admin_lname ) from admin_profile where admin_profile.uidnr_admin= store_log.uidnr_admin )as user, action_message ,DATE_FORMAT(log_activity_on, "%d-%b-%Y %H:%i:%s") AS log_activity_on,module', 'store_log', ['store_id' => $post['id']]);
        $html = '<table class="table table-hover"><thead><tr><th scope="col">Sn.</th><th scope="col">USER</th><th scope="col">ACTIVITY ON</th><th scope="col">MODULE</th><th>ACTION</th></tr></thead><tbody>';
        if ($result) {
            foreach ($result as $key => $value) {
                $html .= '<tr><th scope="row">'.($key+1).'</th><td>'.$value->user.'</td><td>'.$value->log_activity_on.'</td><td>'.$value->module.'</td><td>'.$value->action_message.'</td></tr>';
            }
        } else {
            $html .= '<tr><th colspan="5" >NO RECORD FOUND</th></tr>';
        }
        $html .= '</tbody></table>';
        echo json_encode($html);
    }
    public function deleteStore($id)
    {
        $id = base64_decode($id);
        $count = $this->Store_model->get_row('COUNT(*) AS number', 'current_stock', ['store_id' => $id,'current_stock >'=>0]);
        if ($count && $count->number > 0) {
            $this->session->set_flashdata('error','Store has items in inventory left. Please transfer them to other stores and try again.');
            redirect($_SERVER['HTTP_REFERER']);
        } else {
            $count = $this->Store_model->update_data('stores', array('is_deleted' => 1), ['store_id' => $id]);
            $logDetails = array(
                'module' => 'Store', 'is_deleted' => 1,
                'store_id' => $id, 'lab_id' => '',
                'action_message' => 'Store Deleted',
                'operation' => 'deleteStore',
                'uidnr_admin' => $this->user,
                'log_activity_on' => date("Y-m-d H:i:s")
            );
            $this->Store_model->insert_data("store_log", $logDetails);
            $this->session->set_flashdata('success','Store deleted successfully.');
            redirect($_SERVER['HTTP_REFERER']);
        }
        
    }
}
