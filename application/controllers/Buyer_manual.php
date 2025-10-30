<?php
class Buyer_manual extends MY_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('Buyer_manual_model', 'bmm');
        $this->check_session();
        $checkUser = $this->session->userdata('user_data');
        $this->user = $checkUser->uidnr_admin;
    }
    public function index($name = 'NULL', $buyer = 'NULL', $title = 'NULL', $start_date = 'NULL', $end_date = 'NULL')
    {
        $where = array();
        $title1 = $buyer1 = NULL;
        $base_url = base_url() . "Buyer_manual/index";

        $base_url .= '/' . (($name != 'NULL') ? $name : 'NULL');
        $base_url .= '/' . (($buyer != 'NULL') ? ($buyer) : 'NULL');
        $base_url .= '/' . (($title != 'NULL') ? ($title) : 'NULL');
        $base_url .= '/' . (($start_date != 'NULL') ? $start_date : 'NULL');
        $base_url .= '/' . (($end_date != 'NULL') ? $end_date : 'NULL');


        $data['buyer'] = ($buyer != 'NULL') ? base64_decode($buyer) : 'NULL';
        $data['title'] = ($title != 'NULL') ? base64_decode($title) : 'NULL';
        $data['start_date'] = ($start_date != 'NULL') ? $start_date : 'NULL';
        $data['end_date'] = ($end_date != 'NULL') ? $end_date : 'NULL';


        if ($name != 'NULL') {
            $where['ap.uidnr_admin'] = $name;
            $data['name'] = $name;
        } else {
            $data['name'] = 'NULL';
        }

        if ($buyer != 'NULL') {
            $buyer1 = trim(base64_decode($buyer));
        }
        if ($title != 'NULL') {
            $title1 = trim(base64_decode($title));
        }
        if ($start_date != 'NULL') {
            $where['cbm.created_date >='] = ($start_date);
        }
        if ($end_date != 'NULL') {
            $where['cbm.created_date <='] = ($end_date);
        }
        //    echo $this->uri->segment(8);die;
        $this->load->library('pagination');
        $config = array();
        $config['full_tag_open']    = '<div class="pagging text-center"><nav><ul class="pagination">';
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
        $config["total_rows"] = $this->bmm->buyer_manual_list(NULL, NULL, $where, $buyer1, $title1, '1');
        $config["per_page"] = 10;
        $config["uri_segment"] = 8;
        $config["base_url"] = $base_url;
        $config1 = $this->pagination->initialize($config);
        $page = ($this->uri->segment(8)) ? $this->uri->segment(8) : 0;
        $data['accepted_sample'] = $this->bmm->buyer_manual_list($config1->per_page, $page, $where, $buyer1, $title1);
        $data['created_by'] = $this->bmm->created_by();
        // print_r($data['created_by']);die;
        $data["links"] = $this->pagination->create_links();
        $this->load_view('buyer_manual/index', $data);
    }

    public function open_buyer_manual()
    {
        //$this->output->enable_profiler(true);
        $data['buyer'] = $this->bmm->get_buyer();
       
        $this->load_view('buyer_manual/add_buyer_manual', $data);
    }

    public function add_buyer_manual()
    {
        $data = $this->input->post();
        // print_r($data);die;
        $post = array();
        $files = $_FILES;
        if (!empty($files['multiple_image']['name'][0]) && count($files['multiple_image']['name']) > 0) {
            $file = $this->multiple_upload_image($files['multiple_image']);
            if ($file) {
                $post['upload_filename'] = $files['multiple_image']['name'];
                if ($file) {
                    $post['upload_file_path'] = $file['aws_path'];
                } else {
                    $post['upload_file_path'] = '';
                }
            }
        }
        // print_r($data);die;
        
        if ($data) {


            $post['created_by'] = $this->user;

            $post['buyer_name'] = $data['buyer_name'];
            $post['title'] = $data['title'];
            $post['doc_version'] = $data['doc_version'];
            $post['mail_content'] = base64_encode(htmlentities($data['mail_content']));
            
        }
        $insert = $this->bmm->insert_data('cps_buyermanual', $post);
        if ($insert) {
            $log_deatils = array(
                'text'          => "Added buyer manual",
                'created_by'    => $this->admin_id(),
                'created_on'    => date('Y-m-d H:i:s'),
                'record_id'     => $insert,
                'source_module' => 'Buyer_manual',
                'action_taken'  => 'add_buyer_manual'
            );

            $this->bmm->insert_data('user_log_history',$log_deatils);
            $msg = array('status' => 1, 'msg' => "Report data Save Successfully");
            $this->session->set_flashdata('success', 'Buyer Manual Add Successfully');
            // redirect($_SERVER['HTTP_REFERER']);
        } else {
            $msg = array('status' => 0, 'msg' => "Error While Saving Data");
            $this->session->set_flashdata('error', 'Buyer Manual Not Added');
            // redirect($_SERVER['HTTP_REFERER']);
        }

        echo json_encode($msg);
    }






    public function edit_buyer_manual($buyer_manual_id)
    {
        $data['buyer_manual_id'] = base64_decode($buyer_manual_id);
        $data['buyer'] = $this->bmm->get_buyer();

        $data['buyer_manual'] = $this->bmm->edit_buyer_manual($data);
        $this->load_view('buyer_manual/edit_buyer_manual', $data);
    }
    public function update_buyer_manual()
    {
        $data = $this->input->post();
        $post = array();
        $files = $_FILES;       
        if (!empty($files['multiple_image']['name'][0]) && count($files['multiple_image']['name']) > 0) {
            $file = $this->multiple_upload_image($files['multiple_image']);
            if ($file) {
                $post['upload_filename'] = $files['multiple_image']['name'];
                if ($file) {
                    $post['upload_file_path'] = $file['aws_path'];
                } else {
                    $post['upload_file_path'] = '';
                }
            }
        }
        if ($data) {


            $post['created_by'] = $this->user;
            $post['buyer_name'] = $data['buyer_name'];
            $post['title'] = $data['title'];
            $post['doc_version'] = $data['doc_version'];
            $post['mail_content'] = base64_encode(htmlentities($data['mail_content']));
        }
        $update =  $this->bmm->update_data('cps_buyermanual', $post, array('buyer_manual_id' => $data['buyer_manual_id']));
        //    echo $this->db->last_Query();die;
        if ($update) {
            $log_deatils = array(
                'text'          => "Updated buyer manual",
                'created_by'    => $this->admin_id(),
                'created_on'    => date('Y-m-d H:i:s'),
                'record_id'     => $data['buyer_manual_id'],
                'source_module' => 'Buyer_manual',
                'action_taken'  => 'edit_buyer_manual'
            );

            $this->bmm->insert_data('user_log_history',$log_deatils);
            $msg = array('status' => 1, 'msg' => "Report data update Successfully");
            $this->session->set_flashdata('success', 'Buyer Manual Update Successfully');
            // redirect($_SERVER['HTTP_REFERER']);
        } else {
            $msg = array('status' => 0, 'msg' => "Error While Update Data");
            $this->session->set_flashdata('error', 'Buyer Manual Not Updated');
            // redirect($_SERVER['HTTP_REFERER']); 
        }
        echo json_encode($msg);
    }

    public function delete_buyer_manual($buyer_manual_id)
    {

        $id = base64_decode($buyer_manual_id);
        $delete = $this->bmm->delete_row('cps_buyermanual', ['buyer_manual_id' => $id]);
        // echo $this->db->last_query();die;
        if ($delete) {
            $log_deatils = array(
                'text'          => "Deleted buyer manual",
                'created_by'    => $this->admin_id(),
                'created_on'    => date('Y-m-d H:i:s'),
                'record_id'     => $buyer_manual_id,
                'source_module' => 'Buyer_manual',
                'action_taken'  => 'delete_buyer_manual'
            );

            $this->bmm->insert_data('user_log_history',$log_deatils);
            $this->session->set_flashdata('success', 'Buyer Manual Deleted Successfully');
            redirect($_SERVER['HTTP_REFERER']);
        } else {
            $this->session->set_flashdata('error', 'Buyer Manual Not Deleted');
            redirect($_SERVER['HTTP_REFERER']);
        }
    }
}
