<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Webinar extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->check_session();
        $this->load->model('Webinar_model','WM');
    }

    public function index()
    {
        $this->load_view('Webinar/index');
    }
    public function listing($search, $page = 0)
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
        $total_row = $this->WM->get_list(NULL, NULL, $search, $where, '1');
        $config['base_url'] = base_url('Webinar/listing');
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

        $result = $this->WM->get_list($per_page, $page, $search, $where);
        $html = '';
        if ($result) {
            foreach ($result as $value) {
                $page++;
                $html .= '<tr>';
                $html .= '<th scope="col">' . $page . '</th>';
                $html .= '<td>' . $value->title . '</td>';
                $html .= '<td>' . $value->host_name . '</td>';
                $html .= '<td>' . $value->profile . '</td>';
                $html .= '<td>' . $value->date . '</td>';
                $html .= '<td>' . $value->start_time . '</td>';
                $html .= '<td>' . $value->end_time . '</td>';
                $html .= '<td>' . $value->desc . '</td>';
                $html .= '<td>' . (($value->status > 0) ? 'ACTIVE' : 'IN-ACTIVE') . '</td>';
                $html .= '<td>' . $value->created_by . '</td>';
                $html .= '<td>' . date('d-M-Y',strtotime($value->created_on)) . '</td>';
                $html .= '<td>';
                if ($this->permission_action('Webinar/edit')) {
                    $html .= '<a href="javascript:void(0);" title="EDIT" class="btn btn-sm edit_web" data-toggle="modal" data-id="' . base64_encode($value->id) . '" data-target="#edit_application"><img width="28px" src="' . base_url('public/img/icon/edit.png') . '" alt="BASIL" class="edit_application_data" ></a> ';
                }
                if ($this->permission_action('Webinar/link_show')) {
                    $html .= '<a target="_blank" href="'.$value->link.'" class="btn btn-sm" ><img alt="BASIL LINK" src="'.base_url('assets/images/icon/link.png').'" /></a> ';
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
        $this->form_validation->set_rules('host_name', 'HOST NAME', 'trim|required|min_length[1]');
        $this->form_validation->set_rules('profile', 'PROFILE NAME', 'trim|required|min_length[1]');
        $this->form_validation->set_rules('link', 'LINK', 'trim|required|min_length[3]|is_unique[webinar.link]');
        $this->form_validation->set_rules('title', 'TITLE', 'trim|required|min_length[3]|is_unique[webinar.title]');
        $this->form_validation->set_rules('date', 'DATE', 'trim|required');
        $this->form_validation->set_rules('start_time', 'START TIME', 'trim|required');
        $this->form_validation->set_rules('end_time', 'END TIME', 'trim|required');
        $this->form_validation->set_rules('desc', 'DESCRIPTION', 'trim|required|min_length[3]');
        if ($this->form_validation->run() == TRUE) {
            $checkUser = $this->session->userdata('user_data');
            $post = $this->input->post();
            $post['created_by'] = $checkUser->uidnr_admin;
            $insert = $this->WM->insert_data('webinar',$post);
            if ($insert) {
                $msg = array(
                    'status' => 1,
                    'msg' => 'SUCCESSFULLY INSERT NEW MEETING'
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
                'errors' => $this->form_validation->error_array(),
                'msg' => 'PLEASE ENTER VALID TEXT'
            );
        }
        echo json_encode($msg);
    }
    public function edit()
    {
        $this->form_validation->set_rules('host_name', 'HOST NAME', 'trim|required|min_length[1]');
        $this->form_validation->set_rules('profile', 'PROFILE NAME', 'trim|required|min_length[1]');
        $this->form_validation->set_rules('link', 'LINK', 'trim|required|min_length[3]');
        $this->form_validation->set_rules('title', 'TITLE', 'trim|required|min_length[3]');
        $this->form_validation->set_rules('date', 'DATE', 'trim|required');
        $this->form_validation->set_rules('start_time', 'START TIME', 'trim|required');
        $this->form_validation->set_rules('end_time', 'END TIME', 'trim|required');
        $this->form_validation->set_rules('desc', 'DESCRIPTION', 'trim|required|min_length[3]');
        if ($this->form_validation->run() == TRUE) {
            $post = $this->input->post();
           
                $insert = $this->WM->update_data('webinar', $post, ['id' => $post['id']]);
                if ($insert) {
                    $msg = array(
                        'status' => 1,
                        'msg' => 'SUCCESSFULLY UPDATE MEETING'
                    );
                } else {
                    $msg = array(
                        'status' => 0,
                        'msg' => 'ERROR WHILE UPDATE'
                    );
                }
           
        } else {
            $msg = array(
                'status' => 0,
                'errors' => $this->form_validation->error_array(),
                'msg' => 'PLEASE ENTER VALID TEXT'
            );
        }
        echo json_encode($msg);
    }
    public function get_edit()
    {
        $id = $this->input->post('id');
        echo json_encode($this->WM->get_row('*','webinar',['id'=>base64_decode($id)]));
    }
}
