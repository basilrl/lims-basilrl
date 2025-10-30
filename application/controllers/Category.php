<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Category extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Category_model');
        // $this->permission('Role/index');
        $checkUser = $this->session->userdata('user_data');
        $this->user = $checkUser->uidnr_admin;
    }

    public function index()
    {
        $this->load_view('Category/index');
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
        $total_row = $this->Category_model->getCategory_list(NULL, NULL, $search, $where, '1');
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

        $result = $this->Category_model->getCategory_list($per_page, $page, $search, $where);
        $html = '';
        if ($result) {
            foreach ($result as $value) {
                $page++;
                $html .= '<tr>';
                $html .= '<th scope="col">' . $page . '</th>';
                $html .= '<td>' . $value->category_name . '</td>';
                $html .= '<td>' . $value->category_code . '</td>';
                $html .= '<td>' . $value->created_by . '</td>';
                $html .= '<td>' . date('d-m-Y',strtotime($value->updated_on)) . '</td>';
                $html .= '<td>';
                if ($this->permission_action('Category/edit')) {
                    $html .= '<a href="javascript:void(0);" title="EDIT" class="btn btn-sm edit_role" data-toggle="modal" data-id="' . base64_encode($value->category_id) . '" data-target="#add_Store"><img width="28px" src="' . base_url('public/img/icon/edit.png') . '" alt="BASIL" class="edit_application_data" ></a> ';
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
        if (!empty($post['category_id'])) {
            $this->form_validation->set_rules('category_id', 'UNIQUE ID', 'trim|required');
            $this->form_validation->set_rules('category_name', 'Category Name', 'trim|required|callback_check_Store');
        }else{
            $this->form_validation->set_rules('category_name', 'Category Name', 'trim|required|min_length[2]|is_unique[mst_category.category_name]');
        }
        $this->form_validation->set_rules('category_code', 'CODE', 'trim|required');
        if ($this->form_validation->run() == TRUE) {
            $msg = array();
            if (!empty($post['category_id'])) {
                $post['updated_by'] = $this->user;
                $post['updated_on'] = date("Y/m/d H:i:s");
                $insert = $this->Category_model->update_data('mst_category', $post,['category_id'=>$post['category_id']]);
                $msg['msg'] = 'Updated Category Details.';
            }else{
                unset($post['category_id']);
                $post['created_by'] = $this->user;
                $post['created_on'] = date("Y/m/d H:i:s");
                $insert = $this->Category_model->insert_data('mst_category', $post);
                if ($insert) {
                    $msg['msg']='Created New Category.';
                }
            }
            if ($insert) {
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
            $count = $this->Category_model->get_row('count(*) as count','mst_category',['LOWER(category_name)'=>strtolower($files['category_name']),'category_id NOT IN ('.$files['category_id'].')'=>null]);
            if ($count && $count->count > 0) {
                $this->form_validation->set_message('check_Store', 'Category name Already Exist');
                return false;
            }
            return true;
    }
    public function Store_management_get()
    {
        $post = $this->input->post();
        $post['id'] = base64_decode($post['id']);
        echo json_encode($this->Category_model->get_row('*', 'mst_category', ['category_id' => $post['id']]));
    }
    
    public function branch_Store()
    {
        echo json_encode($this->Category_model->branch_Store());
    }
    public function store_keeper_store()
    {
        echo json_encode($this->Category_model->store_keeper_store());
    }
    
}
