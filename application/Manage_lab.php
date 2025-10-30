<?php
class Manage_lab extends MY_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('Manage_lab_model', 'mlm');
        $this->check_session();
        $checkUser = $this->session->userdata('user_data');
        $this->user = $checkUser->uidnr_admin;
    }
    public function index($applicant = 'NULL', $buyer = 'NULL', $divison = 'NULL', $product = 'NULL', $search_gc = 'NULL', $search_trf = 'NULL', $start_date = 'NULL', $end_date = 'NULL', $stauts = 'NULL')
    {
        $where = array();
        $search_trf1 = $search_gc1 = NULL;
        // echo $applicant . " " . $product . " " . $search . " " . $start_date . " " . $end_date . " " . $stauts;
        $base_url = base_url() . "Manage_lab/index";
        $base_url .= '/' . (($applicant != 'NULL') ? $applicant : 'NULL');
        $base_url .= '/' . (($buyer != 'NULL') ? $buyer : 'NULL');
        $base_url .= '/' . (($divison != 'NULL') ? $divison : 'NULL');
        $base_url .= '/' . (($product != 'NULL') ? $product : 'NULL');
        $base_url .= '/' . (($search_gc != 'NULL') ? ($search_gc) : 'NULL');
        $base_url .= '/' . (($search_trf != 'NULL') ? ($search_trf) : 'NULL');
        $base_url .= '/' . (($start_date != 'NULL') ? $start_date : 'NULL');
        $base_url .= '/' . (($end_date != 'NULL') ? $end_date : 'NULL');
        $base_url .= '/' . (($stauts != 'NULL') ? ($stauts) : 'NULL');
        $data['applicant_id'] = ($applicant != 'NULL') ? $applicant : 'NULL';
        $data['buyer_id'] = ($buyer != 'NULL') ? $buyer : 'NULL';
        $data['product_id'] = ($product != 'NULL') ? $product : 'NULL';
        $data['search_gc'] = ($search_gc != 'NULL') ? base64_decode($search_gc) : 'NULL';
        $data['search_trf'] = ($search_trf != 'NULL') ? base64_decode($search_trf) : 'NULL';
        $data['start_date'] = ($start_date != 'NULL') ? $start_date : 'NULL';
        $data['end_date'] = ($end_date != 'NULL') ? $end_date : 'NULL';
        $data['stauts'] = ($stauts != 'NULL') ? base64_decode($stauts) : 'NULL';
        if ($applicant != 'NULL') {
            $customer = $this->mlm->get_row('customer_name', 'cust_customers', ['customer_id' => $applicant]);
            if ($customer) {
                $data['applicant_name'] = $customer->customer_name;
            } else {
                $data['applicant_name'] = 'NULL';
            }
        } else {
            $data['applicant_name'] = 'NULL';
        }

        if ($buyer != 'NULL') {
            $customer = $this->mlm->get_row('customer_name', 'cust_customers', ['customer_id' => $buyer]);
            if ($customer) {
                $data['buyer_name'] = $customer->customer_name;
            } else {
                $data['buyer_name'] = 'NULL';
            }
        } else {
            $data['buyer_name'] = 'NULL';
        }

        if ($product != 'NULL') {
            $customer = $this->mlm->get_row('sample_type_name', 'mst_sample_types', ['sample_type_id' => $product]);
            if ($customer) {
                $data['product_name'] = $customer->sample_type_name;
            } else {
                $data['product_name'] = 'NULL';
            }
        } else {
            $data['product_name'] = 'NULL';
        }
        if ($applicant != 'NULL') {
            $where['tr.trf_applicant'] = $applicant;
        }
        if ($buyer != 'NULL') {
            $where['tr.trf_buyer'] = $buyer;
        }
        if ($divison != 'NULL') {
            $where['sr.division_id'] = $divison;
            $data['divison'] = $divison;
        } else {
            $data['divison'] = 'NULL';
        }
        if ($product != 'NULL') {
            $where['sr.sample_registration_sample_type_id'] = $product;
        }
        if ($search_gc != 'NULL') {
            $search_gc1 = trim(base64_decode($search_gc));
        }
        if ($search_trf != 'NULL') {
            $search_trf1 = trim(base64_decode($search_trf));
        }
        if ($start_date != 'NULL') {
            $where['sr.received_date >='] = ($start_date);
        }
        if ($end_date != 'NULL') {
            $where['sr.received_date <='] = ($end_date);
        }
        if ($stauts != 'NULL') {
            $where['sr.status'] = base64_decode($stauts);
        }
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
        $config["total_rows"] = $this->mlm->accepted_sample_list(NULL, NULL, $where, $search_gc1, $search_trf1, '1');
        $config["per_page"] = 10;
        $config["uri_segment"] = 12;
        $config["base_url"] = $base_url;
        $config1 = $this->pagination->initialize($config);
        $page = ($this->uri->segment(12)) ? $this->uri->segment(12) : 0;
        $data['accepted_sample'] = $this->mlm->accepted_sample_list($config1->per_page, $page, $where, $search_gc1, $search_trf1);
        $data['divisions'] = $this->mlm->get_default_division();
        // print_r($data['divisions']);die;
        $data["links"] = $this->pagination->create_links();
        $this->load_view('manage_lab/accepted_sample', $data);
    }
    public function Assign_test()
    {
        $sample_reg_id = $this->input->post('sample_reg_id');
        $result['head'] =  $this->mlm->get_assign_head($sample_reg_id);
        $result['test'] =  $this->mlm->get_assign_test($sample_reg_id);
        $result['analysis'] = $this->mlm->get_analysis();
        echo json_encode($result);
    }
    public function SaveAssignTest()
    {
        $data = $this->input->post();
        // print_r($data);die;
        $result = $this->mlm->SaveAssignTest($data);
        if ($result) {
            $this->session->set_flashdata('success', 'Task Assigned Successfully');
            redirect($_SERVER['HTTP_REFERER']);
        } else {
            $this->session->set_flashdata('false', 'Something went wrong!.');
        }


        // print_r($data);die;
    }


    public function record_finding($applicant = 'NULL', $buyer = 'NULL', $divison = 'NULL', $product = 'NULL', $search_gc = 'NULL', $search_trf = 'NULL', $start_date = 'NULL', $end_date = 'NULL')
    {
        $where = array();
        $search_trf1 = $search_gc1 = NULL;
        $base_url = "Manage_lab/record_finding";
        $base_url .= '/' . (($applicant != 'NULL') ? $applicant : 'NULL');
        $base_url .= '/' . (($buyer != 'NULL') ? $buyer : 'NULL');
        $base_url .= '/' . (($divison != 'NULL') ? $divison : 'NULL');
        $base_url .= '/' . (($product != 'NULL') ? $product : 'NULL');
        $base_url .= '/' . (($search_gc != 'NULL') ? ($search_gc) : 'NULL');
        $base_url .= '/' . (($search_trf != 'NULL') ? ($search_trf) : 'NULL');
        $base_url .= '/' . (($start_date != 'NULL') ? $start_date : 'NULL');
        $base_url .= '/' . (($end_date != 'NULL') ? $end_date : 'NULL');
        // $base_url .= '/' . (($stauts != 'NULL') ? ($stauts) : 'NULL');
        $data['applicant_id'] = ($applicant != 'NULL') ? $applicant : 'NULL';
        $data['buyer_id'] = ($buyer != 'NULL') ? $buyer : 'NULL';
        $data['product_id'] = ($product != 'NULL') ? $product : 'NULL';
        $data['search_gc'] = ($search_gc != 'NULL') ? base64_decode($search_gc) : 'NULL';
        $data['search_trf'] = ($search_trf != 'NULL') ? base64_decode($search_trf) : 'NULL';
        $data['start_date'] = ($start_date != 'NULL') ? $start_date : 'NULL';
        $data['end_date'] = ($end_date != 'NULL') ? $end_date : 'NULL';
        // $data['stauts'] = ($stauts != 'NULL') ? base64_decode($stauts) : 'NULL';
        if ($applicant != 'NULL') {
            $customer = $this->mlm->get_row('customer_name', 'cust_customers', ['customer_id' => $applicant]);
            if ($customer) {
                $data['applicant_name'] = $customer->customer_name;
            } else {
                $data['applicant_name'] = 'NULL';
            }
        } else {
            $data['applicant_name'] = 'NULL';
        }

        if ($buyer != 'NULL') {
            $customer = $this->mlm->get_row('customer_name', 'cust_customers', ['customer_id' => $buyer]);
            if ($customer) {
                $data['buyer_name'] = $customer->customer_name;
            } else {
                $data['buyer_name'] = 'NULL';
            }
        } else {
            $data['buyer_name'] = 'NULL';
        }

        if ($product != 'NULL') {
            $customer = $this->mlm->get_row('sample_type_name', 'mst_sample_types', ['sample_type_id' => $product]);
            if ($customer) {
                $data['product_name'] = $customer->sample_type_name;
            } else {
                $data['product_name'] = 'NULL';
            }
        } else {
            $data['product_name'] = 'NULL';
        }
        if ($applicant != 'NULL') {
            $where['tr.trf_applicant'] = $applicant;
        }
        if ($buyer != 'NULL') {
            $where['tr.trf_buyer'] = $buyer;
        }
        if ($divison != 'NULL') {
            $where['sr.division_id'] = $divison;
            $data['divison'] = $divison;
        } else {
            $data['divison'] = 'NULL';
        }
        if ($product != 'NULL') {
            $where['sr.sample_registration_sample_type_id'] = $product;
        }
        if ($search_gc != 'NULL') {
            $search_gc1 = trim(base64_decode($search_gc));
        }
        if ($search_trf != 'NULL') {
            $search_trf1 = trim(base64_decode($search_trf));
        }
        if ($start_date != 'NULL') {
            $where['sr.received_date >='] = ($start_date);
        }
        if ($end_date != 'NULL') {
            $where['sr.received_date <='] = ($end_date);
        }
        $total_row = $this->mlm->record_finding_list(NULL, NULL, $where, $search_gc1, $search_trf1, '1');

        $page = ($this->uri->segment(11)) ? $this->uri->segment(11) : 0;
        $config = $this->pagination($base_url, $total_row, 10, 11);

        $data["links"] = $config["links"];
        $data['list'] = $this->mlm->record_finding_list($config["per_page"], $page, $where, $search_gc1, $search_trf1);
        $start = ($data['list']) ? (int)$page + 1 : 0;
        $end = (($data['list']) ? count($data['list']) : 0) + (($page) ? $page : 0);
        $data['result_count'] = "Showing " . $start . " - " . $end . " of " . $total_row . " Results";
        $data['divisions'] = $this->mlm->get_default_division();

        $this->load_view('manage_lab/record_finding', $data);
    }

    public function open_record_finding($id, $sample_test_id,$branch_id)
    {
        $data['sample_reg_id'] = base64_decode($id);
        $data['sample_test_id'] = base64_decode($sample_test_id);
        $data['branch_id'] = base64_decode($branch_id);
// print_r($data['branch_id']);die;
        $data['details'] = $this->mlm->get_sample_gc($data);
        $data['part'] = $this->mlm->get_parts($data);
        $data['test'] = $this->mlm->get_test($data);
        //         echo "<pre>";
        // print_r($data);die;
        $this->load_view('manage_lab/record_finding_page', $data);
    }

    public function add_lab_details()
    {
        $checkUser = $this->session->userdata('user_data');

        $data = $this->input->post();
        $details = array("lab_name" => $data['lab_name'], "lab_address" => $data['address'], "created_by" => $checkUser->uidnr_admin);
        $data = $this->mlm->insert_data('sub_contract_lab_details', $details);
        // print_r($data);die;
        if ($data > 0) {
            //
            echo json_encode(array("status" => TRUE));
        } else {
            echo json_encode(array("status" => FALSE));
        }
    }
    public function Lab_completion()
    {
        $id = $this->input->post();
        $lab_data['id'] = $id['sample_reg_id'];
        // $lab_data['sample_test_id'] = $id['sample_test_id'];
        $date = date("Y/m/d h:i:sa");

        $lab_data['data'] = array(
            'lab_completion_date_time' => $date,
            'status' => 'Send For Record Finding',
        );

        $status =  $this->mlm->lab_completion($lab_data);
        if ($status) {
            $msg = array(
                'status' => 1,
                'msg' => 'MarK as Completed From Lab'
            );
        } else {
            $msg = array(
                'status' => 0,
                'msg' => 'MarK as not Completed From Lab'
            );
        }
        echo json_encode($msg);
    }

    public function report_generation()
    {
        $data = $this->input->post();
       
        // print_r($data);die;
        if ($data) {
            if (array_key_exists('non_nabl_table', $data)) {
                $post['non_nabl_headings'] =  $data['non_nabl_table'];
            } else {
                $post['non_nabl_headings']='';
            }
            if (array_key_exists('nabl_table', $data)) {
                $post['nabl_headings'] =  $data['nabl_table'];
            } else {
                $post['nabl_headings']='';
            }
            
            $post['created_by'] = $this->user;
            $post['sample_registration_id'] = $data['sample_registration_id'];
            $post['sample_test_id'] = $data['sample_test_id'];
            $post['test_result'] = $data['test_result'];
            if($data['nabl_remark']!='' && $data['nabl_remark']!=NULL){
            $post['nabl_remark'] = base64_encode(htmlentities($data['nabl_remark']));
            } else {
               $post['nabl_remark']=''; 
            }
             if($data['non_nabl_remark']!='' && $data['non_nabl_remark']!=NULL){
            $post['non_nabl_remark'] = base64_encode(htmlentities($data['non_nabl_remark']));
            } else {
               $post['non_nabl_remark']=''; 
            }
             if($data['nabl_detail']!='' && $data['nabl_detail']!=NULL){
            $post['nabl_detail'] = base64_encode(htmlentities($data['nabl_detail']));
            } else {
               $post['nabl_detail']=''; 
            }
              if($data['non_nabl_detail']!='' && $data['non_nabl_detail']!=NULL){
            $post['non_nabl_detail'] = base64_encode(htmlentities($data['non_nabl_detail']));
            } else {
               $post['non_nabl_detail']=''; 
            }
            $post['test_display_name'] = $data['test_display_name'];
            $post['test_display_method'] = $data['test_display_method'];
            $post['test_component'] = $data['test_component'];
            if (empty($data['test_name_type'])) {
                unset($data['test_name_type']);
            }
            else{
                $post['test_name_type'] = $data['test_name_type'];
            }            if (empty($data['lab_id'])) {
                unset($data['lab_id']);
            }
            else{
                $post['lab_id'] = $data['lab_id'];
            }
            if(array_key_exists('nabl_table', $data) || !empty($post['nabl_detail']) || !empty($post['nabl_remark'])){
                $post['test_type'] = 'NABL';
            }
            else if(array_key_exists('non_nabl_table', $data) || !empty($post['non_nabl_detail']) || !empty($post['non_nabl_remark'])){
                $post['test_type'] = 'NON-NABL';
            }
            else{
                $post['test_type'] = '';
            }
            $post['sequence_no'] = $data['sequence_no'];
            // print_r($post);die;
            $check_ids =  $this->mlm->check_record_finding($post);
            //    print_r($check_ids);die;
            if ($check_ids && $check_ids->record_finding_id) {
                $result =  $this->mlm->update_data('record_finding_details', $post, array('record_finding_id' => $check_ids->record_finding_id));
            } else {

                $result = $this->mlm->report_generation($post);
            }
            // echo $this->db->last_query();die;
            $files = $_FILES;
            // print_r(count($files['multiple_image']['name']));die;
            if (!empty($files['multiple_image']['name'][0]) && count($files['multiple_image']['name']) > 0) {
                // echo 'hello';
                $file = $this->multiple_upload_image($files['multiple_image']);
                if ($file) {
                    for ($i = 0; $i < count($file); $i++) {
                        $images[] = array('image_path' => $file[$i], 'record_finding_id ' => $result);
                    }
                    $this->mlm->insert_multiple_data("report_generated_images", $images);
                    // echo $this->db->last_query();die;
                }
            }
            // echo $this->db->last_query();die;
            if ($result) {
                $msg = array('status' => 1, 'msg' => "Report data Save Successfully");
                $this->session->set_flashdata('success', 'report data save successfuly');
            } else {
                $msg = array('status' => 0, 'msg' => "Error While Saving Data");
            }
        } else {
            $msg = array('status' => 0, 'msg' => "data Not found");
        }
        echo json_encode($msg);
    }
    public function mark_completed()
    {
        $id = $this->input->post();
        // print_r($id);die;
        $status =  $this->mlm->mark_completion($id);

        if ($status) {
            $msg = array(
                'status' => 1,
                'msg' => 'MarK as Completed'
            );
        } else {
            $msg = array(
                'status' => 0,
                'msg' => 'MarK as not Completed'
            );
        }
        echo json_encode($msg);
    }


    public function edit_record_finding($record_finding_id, $sample_reg_id,$branch_id)
    {
        $data['record_finding_id'] = base64_decode($record_finding_id);
        $data['branch_id'] = base64_decode($branch_id);
// echo $data['branch_id'];die;
        $data['sample_reg_id'] = base64_decode($sample_reg_id);
        $data['record_data'] = $this->mlm->edit_record_finding($data);
        if (!empty($data['record_data']['non_nabl_headings'])) {
            $data['record_data']['non_nabl_headings'] = json_decode(stripslashes($data['record_data']['non_nabl_headings']));
        }
        if (!empty($data['record_data']['nabl_headings'])) {
            $data['record_data']['nabl_headings'] = json_decode(stripslashes($data['record_data']['nabl_headings']));
        }

        $record_finding_id =  $data['record_finding_id'];
        $data['images'] = $this->mlm->get_images($record_finding_id);
        // print_r($data['images']);die;
        $this->load_view('manage_lab/edit_record_finding', $data);
    }
    public function update_record_finding()
    {
        $data = $this->input->post();
        if ($data) {
            if (array_key_exists('non_nabl_table', $data)) {
                $post['non_nabl_headings'] =  $data['non_nabl_table'];
            } else {
                $post['non_nabl_headings']='';
            }
            if (array_key_exists('nabl_table', $data)) {
                $post['nabl_headings'] =  $data['nabl_table'];
            } else {
                $post['nabl_headings']='';
            }
            $post['created_by'] = $this->user;
            $post['test_result'] = $data['test_result'];
            if($data['nabl_remark']!='' && $data['nabl_remark']!=NULL){
            $post['nabl_remark'] = base64_encode(htmlentities($data['nabl_remark']));
            } else {
               $post['nabl_remark']=''; 
            }
             if($data['non_nabl_remark']!='' && $data['non_nabl_remark']!=NULL){
            $post['non_nabl_remark'] = base64_encode(htmlentities($data['non_nabl_remark']));
            } else {
               $post['non_nabl_remark']=''; 
            }
             if($data['nabl_detail']!='' && $data['nabl_detail']!=NULL){
            $post['nabl_detail'] = base64_encode(htmlentities($data['nabl_detail']));
            } else {
               $post['nabl_detail']=''; 
            }
              if($data['non_nabl_detail']!='' && $data['non_nabl_detail']!=NULL){
            $post['non_nabl_detail'] = base64_encode(htmlentities($data['non_nabl_detail']));
            } else {
               $post['non_nabl_detail']=''; 
            }
           
            
            $post['test_display_name'] = $data['test_display_name'];
            $post['test_display_method'] = $data['test_display_method'];
            $post['test_component'] = $data['test_component'];
            if (empty($data['test_name_type'])) {
                unset($data['test_name_type']);
            }
            else{
                $post['test_name_type'] = $data['test_name_type'];
            }
            // $post['test_name_type'] = $data['test_name_type'];

            if (empty($data['lab_id'])) {
                unset($data['lab_id']);
            }
            else{
                $post['lab_id'] = $data['lab_id'];
            }
            if(array_key_exists('nabl_table', $data) || !empty($post['nabl_detail']) || !empty($post['nabl_remark'])){
                $post['test_type'] = 'NABL';
            }
            else if(array_key_exists('non_nabl_table', $data) || !empty($post['non_nabl_detail']) || !empty($post['non_nabl_remark'])){
                $post['test_type'] = 'NON-NABL';
            }
            else{
                $post['test_type'] = '';
            }
            $post['sequence_no'] = $data['sequence_no'];

            $record_finding_id = $data['record_finding_id'];
            //echo "<br>";
            $result = $this->mlm->update_data('record_finding_details', $post, array('record_finding_id' => $record_finding_id));

            $files = $_FILES;
            // print_r($files);die;
            if (!empty($files['multiple_image']['name'][0]) && count($files['multiple_image']['name']) > 0) {

                $file = $this->multiple_upload_image($files['multiple_image']);
                for ($i = 0; $i < count($file); $i++) {
                    $images = array(
                        'image_path' => $file[$i],
                        'record_finding_id ' => $record_finding_id,

                    );
                    $data['images'] = $this->mlm->get_images($record_finding_id);
                    $upload_image = $this->mlm->insert_data("report_generated_images", $images);
 if ($upload_image) {
                        $msg = array(
                            'status' => 1,
                            'msg' => "Report data Updated Successfully"
                        );
                    } else {
                        $msg = array(
                            'status' => 0,
                            'msg' => "Error While Saving Image"
                        );
                    }
                }
            }

            if ($result) {

                $msg = array(
                    'status' => 1,
                    'msg' => "Report data Updated Successfully"
                );

                $this->session->set_flashdata('success', 'report data Updated successfuly');
            } else {

                $msg = array(
                    'status' => 0,
                    'msg' => "Error While Saving Data"
                );
            }
        } else {
            $msg = array(
                'status' => 0,
                'msg' => "data Not found"
            );
        }
        echo json_encode($msg);
    }

    public function report_listing($applicant = 'NULL', $buyer = 'NULL', $divison = 'NULL', $product = 'NULL', $search_gc = 'NULL', $search_trf = 'NULL', $start_date = 'NULL', $end_date = 'NULL')
    {
        $where = array();
        $search_trf1 = $search_gc1 = NULL;
        // echo $applicant . " " . $product . " " . $search . " " . $start_date . " " . $end_date . " " . $stauts;
        $base_url = "Manage_lab/report_listing";
        $base_url .= '/' . (($applicant != 'NULL') ? $applicant : 'NULL');
        $base_url .= '/' . (($buyer != 'NULL') ? $buyer : 'NULL');
        $base_url .= '/' . (($divison != 'NULL') ? $divison : 'NULL');
        $base_url .= '/' . (($product != 'NULL') ? $product : 'NULL');
        $base_url .= '/' . (($search_gc != 'NULL') ? ($search_gc) : 'NULL');
        $base_url .= '/' . (($search_trf != 'NULL') ? ($search_trf) : 'NULL');
        $base_url .= '/' . (($start_date != 'NULL') ? $start_date : 'NULL');
        $base_url .= '/' . (($end_date != 'NULL') ? $end_date : 'NULL');
        // $base_url .= '/' . (($stauts != 'NULL') ? ($stauts) : 'NULL');
        $data['applicant_id'] = ($applicant != 'NULL') ? $applicant : 'NULL';
        $data['buyer_id'] = ($buyer != 'NULL') ? $buyer : 'NULL';
        $data['product_id'] = ($product != 'NULL') ? $product : 'NULL';
        $data['search_gc'] = ($search_gc != 'NULL') ? base64_decode($search_gc) : 'NULL';
        $data['search_trf'] = ($search_trf != 'NULL') ? base64_decode($search_trf) : 'NULL';
        $data['start_date'] = ($start_date != 'NULL') ? $start_date : 'NULL';
        $data['end_date'] = ($end_date != 'NULL') ? $end_date : 'NULL';
        // $data['stauts'] = ($stauts != 'NULL') ? base64_decode($stauts) : 'NULL';
        if ($applicant != 'NULL') {
            $customer = $this->mlm->get_row('customer_name', 'cust_customers', ['customer_id' => $applicant]);
            if ($customer) {
                $data['applicant_name'] = $customer->customer_name;
            } else {
                $data['applicant_name'] = 'NULL';
            }
        } else {
            $data['applicant_name'] = 'NULL';
        }

        if ($buyer != 'NULL') {
            $customer = $this->mlm->get_row('customer_name', 'cust_customers', ['customer_id' => $buyer]);
            if ($customer) {
                $data['buyer_name'] = $customer->customer_name;
            } else {
                $data['buyer_name'] = 'NULL';
            }
        } else {
            $data['buyer_name'] = 'NULL';
        }

        if ($product != 'NULL') {
            $customer = $this->mlm->get_row('sample_type_name', 'mst_sample_types', ['sample_type_id' => $product]);
            if ($customer) {
                $data['product_name'] = $customer->sample_type_name;
            } else {
                $data['product_name'] = 'NULL';
            }
        } else {
            $data['product_name'] = 'NULL';
        }
        if ($applicant != 'NULL') {
            $where['tr.trf_applicant'] = $applicant;
        }
        if ($buyer != 'NULL') {
            $where['tr.trf_buyer'] = $buyer;
        }
        if ($divison != 'NULL') {
            $where['sr.division_id'] = $divison;
            $data['divison'] = $divison;
        } else {
            $data['divison'] = 'NULL';
        }
        if ($product != 'NULL') {
            $where['sr.sample_registration_sample_type_id'] = $product;
        }
        if ($search_gc != 'NULL') {
            $search_gc1 = trim(base64_decode($search_gc));
        }
        if ($search_trf != 'NULL') {
            $search_trf1 = trim(base64_decode($search_trf));
        }
        if ($start_date != 'NULL') {
            $where['sr.received_date >='] = ($start_date);
        }
        if ($end_date != 'NULL') {
            $where['sr.received_date <='] = ($end_date);
        }
        // if ($stauts!= 'NULL') {
        //     $where['sr.status'] = base64_decode($stauts);
        // }
        $total_row = $this->mlm->report_listing(NULL, NULL, $where,  $search_gc1, $search_trf1, '1');

        $page = ($this->uri->segment(11)) ? $this->uri->segment(11) : 0;
        $config = $this->pagination($base_url, $total_row, 10, 11);

        $data["links"] = $config["links"];

        $data['report_listing'] =  $this->mlm->report_listing($config["per_page"], $page, $where,  $search_gc1, $search_trf1);
        $data['divisions'] = $this->mlm->get_default_division();
        $data['sign_auth'] =  $this->mlm->get_report_approver();
        // $data['sign_auth'] =  $this->mlm->get_report_approver();

        $this->load_view('manage_lab/report_listing', $data);
    }



    public function Final_report_generate()
    {
        $checkUser = $this->session->userdata('user_data');

        $data = $this->input->post();
        // print_r($data);die;
        // $update['cps_data'] = $this->mlm->get_cps_data($data['sample_reg_id']);
        // $update['application_data'] =  $this->mlm->get_application_care($data);
      $part_detail =  base64_encode(htmlentities($data['part_details']));
        $where = array('sample_reg_id' => $data['sample_reg_id']);
        $gc_no = $this->mlm->get_row('gc_no', 'sample_registration', $where);
        $report_data = array(
            'generated_date' => date("Y-m-d"),
            'generated_by' => $this->user,
            'sample_reg_id' => $data['sample_reg_id'],
            'background_process' => 'Completed',
            'status' => 'Report Generated',
            'report_num' => $gc_no->gc_no,
            'report_type' => 'System Report',
            'signing_authority' => $data['sign_auth'],
            'report_format'=>$data['report_format'],
           
            // 'sign_authority_new' => $data['sign_auth1']

        );
        $generate_data = array(
            'part'=>$data['part'],
            'part_details'=>$part_detail
        );
        // print_r($report_data);die;
        $report_id = $this->mlm->get_row('report_id', 'generated_reports', $where);
        $revise_flag = $this->mlm->get_row('revise_flag', 'sample_registration', $where);
        $additional_flag = $this->mlm->get_row('additional_flag', 'sample_registration', $where);
// print_r($additional_flag);die;
        if ($report_id->report_id && $revise_flag->revise_flag == 0 && $additional_flag->additional_flag == 0 ) {
        //    echo "1";die;
            $report_where = array('report_id' => $report_id->report_id);
           
            $updat_generate_report =   $this->mlm->update_data('generated_reports', $report_data, $report_where);
            $generate_where = array('sample_reg_id' => $data['sample_reg_id']);
            if($updat_generate_report){
                $this->mlm->update_data('sample_Registration', $generate_data, $generate_where); 
                // echo $this->db->last_Query();die;
            }
        } elseif ($report_id->report_id && $revise_flag->revise_flag > 0 ) {
        //    echo "2";die;
            $revise_count =  $this->mlm->get_row('revise_count', 'sample_registration', array('sample_reg_id' => $data['sample_reg_id']));
            $revise_total = $revise_count->revise_count + 1;


            $report_new_name =  'Rev-'.$revise_total;
         
            $report_data = array(
                'generated_date' => date("Y-m-d"),
                'generated_by' => $this->user,
                'sample_reg_id' => $data['sample_reg_id'],
                'background_process' => 'Completed',
                'status' => 'Report Generated',
                'report_num' => $gc_no->gc_no . '-' . $report_new_name,
                'report_type' => 'System Report',
                'signing_authority' => $data['sign_auth'],
                'report_format'=>$data['report_format'],
                
                // 'sign_authority_new' => $data['sign_auth1']

            );
            $generate_data = array(
                'part'=>$data['part'],
                'part_details'=>$part_detail
            );
            $this->mlm->update_data('sample_registration', array('revise_count' => $revise_total), array('sample_reg_id' => $data['sample_reg_id']));
         

            $update['reprot_generated_id'] = $this->mlm->report_generate_data($report_data);
            $updat_generate_report = $update['reprot_generated_id'];
            $generate_where = array('sample_reg_id' => $data['sample_reg_id']);
            if($updat_generate_report){
                $this->mlm->update_data('sample_Registration', $generate_data, $generate_where); 
                // echo $this->db->last_Query();die;

            }
        } elseif ($report_id->report_id && $additional_flag->additional_flag > 0) {
        //    echo "3";die;
            $add_count =  $this->mlm->get_row('additional_count', 'sample_registration', array('sample_reg_id' => $data['sample_reg_id']));
            $add_total = $add_count->additional_count + 1;

            $report_new_no = 'Rev-'.$add_total;
            
            $report_data = array(
                'generated_date' => date("Y-m-d"),
                'generated_by' => $this->user,
                'sample_reg_id' => $data['sample_reg_id'],
                'background_process' => 'Completed',
                'status' => 'Report Generated',
                'report_num' => $gc_no->gc_no . '-' . $report_new_no,
                'report_type' => 'System Report',
                'signing_authority' => $data['sign_auth'],
                'report_format'=>$data['report_format'],
                
                // 'sign_authority_new' => $data['sign_auth1']

            );
            $generate_data = array(
                'part'=>$data['part'],
                'part_details'=>$part_detail
            );
            $this->mlm->update_data('sample_registration', array('additional_count' => $add_total), array('sample_reg_id' => $data['sample_reg_id']));
            
            $update['reprot_generated_id'] = $this->mlm->report_generate_data($report_data);
            $updat_generate_report = $update['reprot_generated_id'];
            $generate_where = array('sample_reg_id' => $data['sample_reg_id']);
            if($updat_generate_report){
                $this->mlm->update_data('sample_Registration', $generate_data, $generate_where); 
                // echo $this->db->last_Query();die;

            }
        } else {
            $update['reprot_generated_id'] = $this->mlm->report_generate_data($report_data);
        }
        $update['report_data'] =  $this->mlm->Final_report_generate($data);
        $update['test_data'] =  $this->mlm->get_test_result($data);
        $approver_email = $this->mlm->get_approver_email($data['sign_auth']);
        if ($update) {
            $subject = "Sample[" . $gc_no->gc_no . "] for approval ";

            $message = '<table width="100%" border="0" cellspacing="5" cellpadding="5" style="border-collapse:collapse; font-family:Arial, Helvetica, sans-serif;font-size:12px;"><tr><td bgcolor="#336699"><img src=" ' . base_url() . '/public/img/lims-logo-worksheet.png" height="53"/></td><td align="right" bgcolor="#336699"><img src="[SITE_PATH]/resources/logo/logo-receipt.gif" height="53"/></td>
        </tr>
        <tr>
        <td colspan="2"><br />
        Dear Sir,
        
        <br />
        <br />
        Report is Generated with GC No:<strong>' . $gc_no->gc_no . '</strong> <br />
        Please Login to <a href="https://geochemcpslims.com">geochemcpslims.com</a> to approve.<br />
        <br />
        <br />
        <br />
        
        <br />
        <strong>Thanks & Regards <br />
        ' . $checkUser->username . '</strong><br /></td>
        </tr>
        <tr>
        <td align="left" bgcolor="#D5E2F2">Geo Chem Consumer Products Services</td>
        <td align="right" bgcolor="#D5E2F2">GLIMS - Online Lab Information System</td>
        </tr>
        </table>';

            // $send_mail = send_to_report_approval($approver_email->email, NULL, NULL, $subject, $message);



            if ($approver_email) {
                $this->session->set_flashdata('success', 'SUCCESSFULLY GENERATE');
                redirect($_SERVER['HTTP_REFERER']);
            } else {
                $this->session->set_flashdata('error', 'Error while sending Mail');
                redirect($_SERVER['HTTP_REFERER']);
            }
        } else {
            $this->session->set_flashdata('error', 'Error while saving data');
            redirect($_SERVER['HTTP_REFERER']);
        }
    }
    public function preview_report(){
        $data = $this->input->get();
        // print_r($data);die;
        $checkUser = $this->session->userdata('user_data');
        $update['test_component'] =  $this->mlm->pdf_test_component($data);
        // print_r($update['test_component']);die;
        $update['report_data'] =  $this->mlm->pdf_data_get($data);
    //    echo '<pre>'; print_r($update['report_data']);die;
        $sample_images =  $this->mlm->sample_final_images($data);
        $update['image_sample'] = $sample_images;
        $reference_images =  $this->mlm->get_reference_images($data);
        $update['reference_sample'] = $reference_images;
        // print_r($update['reference_sample']);die;
        $application_data= $this->mlm->get_application_care($update);
        if ($application_data) {
            $appData = array();
            if (count($application_data) > 0) {
                foreach ($application_data as $k => $app) {
                    if(!empty($app->instruction_image)){
                    $appData[$k]['instruction_image'] = $this->getS3Url($app->instruction_image);
                    $appData[$k]['instruction_name'] = $app->instruction_name;
                }
            }
            }
            $update['application_data'] = $appData;
        } else {
            $update['application_data'] = null;
        }
      
        $update['cps_data'] = $this->mlm->get_cps_data('*',$data['sample_reg_id']);
        // $update['nabl_record'] = $this->mlm->getNABLCpsResultData('record_finding_id,nabl_remark,nabl_detail,nabl_headings,test_result,test_display_name',$data['sample_reg_id']);
      
        // $update['non_nabl_record'] = $this->mlm->getNONNABLCpsResultData('record_finding_id,non_nabl_remark,non_nabl_detail,test_result,non_nabl_headings,test_display_name',$data['sample_reg_id']);
        $update['record_finding_data'] = $this->mlm->get_record_finding_data($data);
        
        foreach ($update['cps_data'] as $key => $rfd_id) {
            $image  = $this->mlm->get_images($rfd_id['record_finding_id']);
            if ($image) {
                $update['cps_data'][$key]['images'] = $image;
            }
        }
        if($update['record_finding_data']!=''){
        foreach ($update['record_finding_data'] as $key => $rfd_id) {
            if (!empty($update['record_finding_data'][$key]['nabl_headings'])) {
                $update['record_finding_data'][$key]['nabl_headings'] = json_decode(stripslashes($update['record_finding_data'][$key]['nabl_headings']));
            }
            $image  = $this->mlm->get_images($rfd_id['record_finding_id']);
            if ($image) {
                $update['record_finding_data'][$key]['images'] = $image;
            }
        }
        } else {
            $update['record_finding_data']='';
        }
         if($update['record_finding_data']!=''){
        foreach ($update['record_finding_data'] as $key => $rfd_id) {
            if (!empty($update['record_finding_data'][$key]['non_nabl_headings'])) {
                $update['record_finding_data'][$key]['non_nabl_headings'] = json_decode(stripslashes($update['record_finding_data'][$key]['non_nabl_headings']));
            }
            $image  = $this->mlm->get_images($rfd_id['record_finding_id']);
            if ($image) {
                $update['record_finding_data'][$key]['images'] = $image;
            }
         }
         } else {
             $update['record_finding_data']='';
         }
         $update['sign_data1'] = Null;
        $update['test_data'] =  $this->mlm->get_test_result($data);
// echo $update['report_data']->branch_name;
// echo $update['report_data']->report_format;
// echo $update['report_data']->division_name;
        //  $this->load->view('manage_lab/bdreport', $update);
        if ($update['report_data']->branch_name == 'Gurgaon' ) {


            if($update['report_data']->report_format == 1 || $update['report_data']->division_name == 'Hradline'){

            $this->generate_pdf('manage_lab/hggnreport', $update);
        }
        else{
            $this->generate_pdf('manage_lab/ggnreport', $update);
        }
        }
        elseif ($update['report_data']->branch_id == 2) {
            if($update['report_data']->report_format == 3){
                $this->generate_pdf('manage_lab/landmark_uaereport', $update);
            }
            elseif($update['report_data']->report_format == 7 || $update['report_data']->division_name == 'Textiles'){

                $this->generate_pdf('manage_lab/taxtile_uaereport', $update);
            }
            elseif($update['report_data']->report_format == 4 || $update['report_data']->division_name == 'Analytical'){

                $this->generate_pdf('manage_lab/analytical_uaereport', $update);
            }
            elseif($update['report_data']->report_format == 5 || $update['report_data']->division_name == 'Toys'){
                $this->generate_pdf('manage_lab/toys_uaereport', $update);
            }
            elseif($update['report_data']->report_format == 6 || $update['report_data']->division_name == 'Footwear'){
                $this->generate_pdf('manage_lab/lf_uaereport', $update);
            }
            
             else{
                 $this->generate_pdf('manage_lab/taxtile_uaereport', $update);
             }
        }
       
        elseif ($update['report_data']->branch_name == 'Dhaka') {

            $this->generate_pdf('manage_lab/bdreport', $update);
        }
    }
    public function url_sign_get($signature_path_aws)
    {
        return str_replace('s3://','https://s3.ap-south-1.amazonaws.com/',$signature_path_aws);
    }
    public function pdf_demo()
    {
        $data = $this->input->get();
        $checkUser = $this->session->userdata('user_data');
        $update['test_component'] =  $this->mlm->pdf_test_component($data);

        $update['report_data'] =  $this->mlm->pdf_data_get($data);
        $sample_images =  $this->mlm->sample_final_images($data);
        $reference_images =  $this->mlm->get_reference_images($data);
        $update['reference_sample'] = $reference_images;
        $update['image_sample'] = $sample_images;
        $application_data= $this->mlm->get_application_care($update);
       
        if ($application_data) {
            $appData = array();
            if (count($application_data) > 0) {
                foreach ($application_data as $k => $app) {
                    if(!empty($app->instruction_image)){
                    $appData[$k]['instruction_image'] = $this->getS3Url($app->instruction_image);
                    $appData[$k]['instruction_name'] = $app->instruction_name;
                }
            }
            }
            $update['application_data'] = $appData;
        } else {
            $update['application_data'] = null;
        }
     
        $update['cps_data'] = $this->mlm->get_cps_data('*',$data['sample_reg_id']);
        $update['record_finding_data'] = $this->mlm->get_record_finding_data($data);

        
        foreach ($update['cps_data'] as $key => $rfd_id) {
            $image  = $this->mlm->get_images($rfd_id['record_finding_id']);
            if ($image) {
                $update['cps_data'][$key]['images'] = $image;
            }
        }
        if($update['record_finding_data']!=''){
            foreach ($update['record_finding_data'] as $key => $rfd_id) {
                if (!empty($update['record_finding_data'][$key]['nabl_headings'])) {
                    $update['record_finding_data'][$key]['nabl_headings'] = json_decode(stripslashes($update['record_finding_data'][$key]['nabl_headings']));
                }
                $image  = $this->mlm->get_images($rfd_id['record_finding_id']);
                if ($image) {
                    $update['record_finding_data'][$key]['images'] = $image;
                }
            }
            } else {
                $update['record_finding_data']='';
            }
             if($update['record_finding_data']!=''){
            foreach ($update['record_finding_data'] as $key => $rfd_id) {
                if (!empty($update['record_finding_data'][$key]['non_nabl_headings'])) {
                    $update['record_finding_data'][$key]['non_nabl_headings'] = json_decode(stripslashes($update['record_finding_data'][$key]['non_nabl_headings']));
                }
                $image  = $this->mlm->get_images($rfd_id['record_finding_id']);
                if ($image) {
                    $update['record_finding_data'][$key]['images'] = $image;
                }
             }
             } else {
                 $update['record_finding_data']='';
             }
       
        $sign1 = $this->mlm->get_row('signing_authority', 'generated_reports', ['report_id' => $update['report_data']->report_id]);

        $sign2 = $this->mlm->get_row('sign_authority_new', 'generated_reports', ['report_id' => $update['report_data']->report_id]);

        if ($sign1) {
            $signature1 = $this->mlm->get_row('sign_path', 'admin_signature', ['admin_id' => $sign1->signing_authority]);
        }
        if ($sign2) {
            $signature2 = $this->mlm->get_row('sign_path', 'admin_signature', ['admin_id' => $sign2->sign_authority_new]);
        }

        if ($signature1) {
            $update['signature1'] = $this->url_sign_get($signature1->sign_path);
        }
        if ($signature2) {
            $update['signature2'] = $this->url_sign_get($signature2->sign_path);
        }
        $update['sign_data1'] = $this->mlm->getsignvalues($sign1->signing_authority);
        $update['sign_data2'] = $this->mlm->getsignvalues($sign2->sign_authority_new);
        $update['test_data'] =  $this->mlm->get_test_result($data);

        if ($update['report_data']->branch_name == 'Gurgaon' ) {

            $this->generate_pdf('manage_lab/ggnreport', $update);
        }
        elseif ($update['report_data']->branch_id == 2) {
            if($update['report_data']->report_format == 4){
                $this->generate_pdf('manage_lab/landmark_uaereport', $update);
            }
            elseif($update['report_data']->report_format == 3 || $update['report_data']->division_name == 'Textiles'){

                $this->generate_pdf('manage_lab/taxtile_uaereport', $update);
            }
            elseif($update['report_data']->report_format == 2 || $update['report_data']->division_name == 'Analytical'){

                $this->generate_pdf('manage_lab/analytical_uaereport', $update);
            }
            elseif($update['report_data']->report_format == 1 || $update['report_data']->division_name == 'Toys'){
                $this->generate_pdf('manage_lab/toys_uaereport', $update);
            }
            elseif($update['report_data']->report_format == 5 || $update['report_data']->division_name == 'Footwear'){
                $this->generate_pdf('manage_lab/lf_uaereport', $update);
            }
             else{

                 $this->generate_pdf('manage_lab/taxtile_uaereport', $update);
             }

        }
       
        elseif ($update['report_data']->branch_name == 'Dhaka') {

            $this->generate_pdf('manage_lab/bdreport', $update);
        }
    }
    public function approve_report()
    {
        $data = $this->input->post();
        $checkUser = $this->session->userdata('user_data');
        $update['test_component'] =  $this->mlm->pdf_test_component($data);

        $update['cps_data'] = $this->mlm->get_cps_data('*',$data['sample_reg_id']);
        $update['report_data'] =  $this->mlm->pdf_data_get($data);
         $sample_images =  $this->mlm->sample_final_images($data);
         $reference_images =  $this->mlm->get_reference_images($data);
         $update['reference_sample'] = $reference_images;
        $update['image_sample'] = $sample_images;
        $application_data= $this->mlm->get_application_care($update);
        if ($application_data) {
            $appData = array();
            if (count($application_data) > 0) {
                foreach ($application_data as $k => $app) {
                    if(!empty($app->instruction_image)){
                       
                    $appData[$k]['instruction_image'] = $this->getS3Url($app->instruction_image);
                    $appData[$k]['instruction_name'] = $app->instruction_name;
                }
            }
            
            }
            $update['application_data'] = $appData;
        } else {
            $update['application_data'] = null;
        }
       
        $update['record_finding_data'] = $this->mlm->get_record_finding_data($data);

       foreach ($update['cps_data'] as $key => $rfd_id) {
            $image  = $this->mlm->get_images($rfd_id['record_finding_id']);
            if ($image) {
                $update['cps_data'][$key]['images'] = $image;
            }
        }
        if($update['record_finding_data']!=''){
            foreach ($update['record_finding_data'] as $key => $rfd_id) {
                if (!empty($update['record_finding_data'][$key]['nabl_headings'])) {
                    $update['record_finding_data'][$key]['nabl_headings'] = json_decode(stripslashes($update['record_finding_data'][$key]['nabl_headings']));
                }
                $image  = $this->mlm->get_images($rfd_id['record_finding_id']);
                if ($image) {
                    $update['record_finding_data'][$key]['images'] = $image;
                }
            }
            } else {
                $update['record_finding_data']='';
            }
             if($update['record_finding_data']!=''){
            foreach ($update['record_finding_data'] as $key => $rfd_id) {
                if (!empty($update['record_finding_data'][$key]['non_nabl_headings'])) {
                    $update['record_finding_data'][$key]['non_nabl_headings'] = json_decode(stripslashes($update['record_finding_data'][$key]['non_nabl_headings']));
                }
                $image  = $this->mlm->get_images($rfd_id['record_finding_id']);
                if ($image) {
                    $update['record_finding_data'][$key]['images'] = $image;
                }
             }
             } else {
                 $update['record_finding_data']='';
             }
        $record_finding_id = $update['report_data']->record_finding_id;
        $update['images'] = $this->mlm->get_images($record_finding_id);
        $sign1 = $this->mlm->get_row('signing_authority', 'generated_reports', ['report_id' => $update['report_data']->report_id]);

        $sign2 = $this->mlm->get_row('sign_authority_new', 'generated_reports', ['report_id' => $update['report_data']->report_id]);
        // echo $sign2;die;
        if ($sign1) {
            $signature1 = $this->mlm->get_row('sign_path', 'admin_signature', ['admin_id' => $sign1->signing_authority]);
        }
        if ($sign2) {
            $signature2 = $this->mlm->get_row('sign_path', 'admin_signature', ['admin_id' => $sign2->sign_authority_new]);
        }

        if ($signature1) {
            $update['signature1'] = $this->url_sign_get($signature1->sign_path);
        }
        if ($signature2) {
            $update['signature2'] = $this->url_sign_get($signature2->sign_path);
        }
        $update['sign_data1'] = $this->mlm->getsignvalues($sign1->signing_authority);
        $update['sign_data2'] = $this->mlm->getsignvalues($sign2->sign_authority_new);
        // print_r($update['sign_data2']);die;
        $update['test_data'] =  $this->mlm->get_test_result($data);
      
        $this->load->library('Ciqrcode');
        $params['data'] =  base_url('Render/download_pdf?report_id=' . base64_encode($data['report_id']) . '&sample_rg=' . base64_encode($update['report_data']->sample_reg_id));
        $params['level'] = 'H';
        $params['size'] = 1;
        $gc_no = $update['report_data']->gc_no;
        $params['savename'] = QRCODE . (($gc_no) ? $gc_no : rand(0000, 9999)) . '.png';
        $cer_po = $this->ciqrcode->generate($params); // genrate image
        // print_r($params);die;
        $update['qrcode'] = $params['savename'];

        if ($update['report_data']->branch_name == 'Gurgaon' ) {

            $pdf_body =  $this->generate_pdf('manage_lab/ggnreport', $update,'aws_save');
        }
        elseif ($update['report_data']->branch_id == 2) {
            if($update['report_data']->report_format == 4){
                $pdf_body =  $this->generate_pdf('manage_lab/landmark_uaereport', $update,'aws_save');
            }
            elseif($update['report_data']->report_format == 3 || $update['report_data']->division_name == 'Textiles'){

                $pdf_body = $this->generate_pdf('manage_lab/taxtile_uaereport', $update,'aws_save');
            }
            elseif($update['report_data']->report_format == 2 || $update['report_data']->division_name == 'Analytical'){

                $pdf_body =  $this->generate_pdf('manage_lab/analytical_uaereport', $update,'aws_save');
            }
            elseif($update['report_data']->report_format == 1 || $update['report_data']->division_name == 'Toys'){
                $pdf_body = $this->generate_pdf('manage_lab/toys_uaereport', $update,'aws_save');
            }
            elseif($update['report_data']->report_format == 5 || $update['report_data']->division_name == 'Footwear'){
                $pdf_body = $this->generate_pdf('manage_lab/lf_uaereport', $update,'aws_save');
            }
             else{

                $pdf_body =  $this->generate_pdf('manage_lab/taxtile_uaereport', $update,'aws_save');
             }

        }
       
        elseif($update['report_data']->branch_name == 'Dhaka') {

            $pdf_body =  $this->generate_pdf('manage_lab/bdreport', $update,'aws_save');
        }
        if ($pdf_body) {
            $upload_path = $this->report_upload_aws($pdf_body, $gc_no . '-' . rand(0, 999) . $data['reprot_id'] . '.pdf');
            if ($upload_path) {
                // print_r($data);
                $save_aws =  $this->mlm->update_data('generated_reports', array('manual_report_file' => $upload_path['aws_path'], 'status' => 'Report Approved'), array('report_id' => $data['report_id']));
                // echo $this->db->last_query();die;

                if ($save_aws) {
                    unlink($params['savename']);
                    $status = $this->mlm->update_data('sample_registration', array('status' => 'Report Approved'), array('sample_reg_id' => $data['sample_reg_id']));
                    //    echo $this->db->last_query();die;

                    if ($status) {
                        $this->session->set_flashdata('success', 'Report Approved');
                        redirect($_SERVER['HTTP_REFERER']);
                    } else {
                        $this->session->set_flashdata('error', 'Report Approved');
                        redirect($_SERVER['HTTP_REFERER']);
                    }
                } else {
                    $this->session->set_flashdata('error', 'Report Not approve');
                    redirect($_SERVER['HTTP_REFERER']);
                }
            } else {
                $this->session->set_flashdata('error', 'AWS Path Not Found');
                redirect($_SERVER['HTTP_REFERER']);
            }
        } else {
            $this->session->set_flashdata('error', 'Pdf not generated');
            redirect($_SERVER['HTTP_REFERER']);
        }
    }


    public function regenerate_sample()
    {
        $id = $this->input->post();
        $sample_reg_id = $id['sample_reg_id'];
        $report_id = $id['report_id'];

        $data = $this->mlm->regenerate_sample($sample_reg_id, $report_id);
        if ($data) {

            $msg = array(
                'status' => 1,
                'msg' => "Report Regenerate Successfully"
            );

            $this->session->set_flashdata('success', 'report Regenerate successfully');
        } else {

            $msg = array(
                'status' => 0,
                'msg' => "Error While Regenerating"
            );
        
    } 
        echo json_encode($msg);
    }

    public function additional_test()
    {
        $id = $this->input->post();
        $sample_reg_id = $id['sample_reg_id'];
        $report_id = $id['report_id'];

        $data = $this->mlm->additional_test($sample_reg_id, $report_id);
        if ($data) {

            $msg = array(
                'status' => 1,
                'msg' => "Sample send for Evaluation"
            );

            $this->session->set_flashdata('success', 'Sample send for Evaluation successfully');
        } else {

            $msg = array(
                'status' => 0,
                'msg' => "Error While sending"
            );
        
    } 
        echo json_encode($msg);
    }
    public function check_file_upload($file_name)
    {
        if ($file_name['name'] != '' && $file_name['type'] == 'image/jpeg' || $file_name['type'] == 'image/png') {
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
    public function upload_samples_images()
    {
        if ($this->input->post()) {
            for ($count = 0; $count < count($_FILES["sample_image"]["name"]); $count++) {
                $image['type'] = $_FILES["sample_image"]["type"][$count];
                $image['name'] = $_FILES["sample_image"]["name"][$count];
                $image['tmp_name'] = $_FILES["sample_image"]["tmp_name"][$count];
                $valid_file = $this->check_file_upload($image);
                // print_r($valid_file);die;
                // $mult[] = '';
                if ($valid_file) {
                    $data['image_file_path'] = $valid_file['image'];
                    $data['image_thumb_file_path'] = $valid_file['thumb'];
                    $data['sample_reg_id'] = $this->input->post('sample_reg_id');
                    $data['created_by'] = $this->admin_id();
                    $data['created_on'] = date('Y-m-d H:i:s');
                    $mult[] = $data;
                } else {
                         break;
                    echo json_encode(["message" => "Please select a JPEG image", "status" => 0]);
                    exit;
                }
            }
            $result = $this->mlm->insert_multiple_data("sample_photos",$mult);
            // echo $this->db->last_query();die;
            if ($result) {
                echo json_encode(["message" => "Images saved successfully", "status" => 1]);
            } else {
                echo json_encode(["message" => "Something went wrong!.", "status" => 0]);
            }
        }
    }
    public function upload_refernce_images()
    {
        if ($this->input->post()) {
            for ($count = 0; $count < count($_FILES["reference_image"]["name"]); $count++) {
                $image['type'] = $_FILES["reference_image"]["type"][$count];
                $image['name'] = $_FILES["reference_image"]["name"][$count];
                $image['tmp_name'] = $_FILES["reference_image"]["tmp_name"][$count];
                $valid_file = $this->check_file_upload($image);
                // print_r($valid_file);die;
                // $mult[] = '';
                if ($valid_file) {
                    $data['image_file_path'] = $valid_file['image'];
                    $data['image_thumb_file_path'] = $valid_file['thumb'];
                    $data['sample_reg_id'] = $this->input->post('sample_reg_id');
                    $data['created_by'] = $this->admin_id();
                    $data['created_on'] = date('Y-m-d H:i:s');
                    $mult[] = $data;
                } else {
                         break;
                    echo json_encode(["message" => "Please select a JPEG image", "status" => 0]);
                    exit;
                }
            }
            $result = $this->mlm->insert_multiple_data("report_reference_images",$mult);
            // echo $this->db->last_query();die;
            if ($result) {
                echo json_encode(["message" => "Images saved successfully", "status" => 1]);
            } else {
                echo json_encode(["message" => "Something went wrong!.", "status" => 0]);
            }
        }
    }

           
    public function send_to_record_finding(){
      $id = $this->input->post();
    //  print_r($id);die;
      $res = $this->mlm->send_to_record_finding($id['sample_reg_id']);

      if ($res) {   
        echo json_encode(["message" => "Sample sent for record finding successfully", "status" => 1]);
    } else {
        echo json_encode(["message" => "Something went wrong!.", "status" => 0]);
    }
    }

    public function get_report_sample_images(){
        $sample_reg_id = $this->input->post('sample_reg_id');
        $data = $this->mlm->get_fields_by_id("sample_photos",'image_id,image_file_path,comment,image_sequence',$sample_reg_id,"sample_reg_id");
        echo json_encode($data);
    }
    public function get_report_reference_image(){
        $sample_reg_id = $this->input->post('sample_reg_id');
        $data = $this->mlm->get_fields_by_id("report_reference_images",'report_ref_image_id,image_file_path',$sample_reg_id,"sample_reg_id");
        
        echo json_encode($data);
    }

    public function delete_report_sample_image(){
        $image_id = $this->input->post('image_id');
        $delete = $this->mlm->remove_report_sample_image($image_id);
        if($delete){
            echo json_encode(["message" => "Image deleted successfully.", "status" => 1]);
        } else {
            echo json_encode(["message" => "Something went wrong!.", "status" => 0]);
        }
    }
    public function delete_report_reference_image(){
        $image_id = $this->input->post('image_id');
        $delete = $this->mlm->remove_report_reference_image($image_id);
        if($delete){
            echo json_encode(["message" => "Image deleted successfully.", "status" => 1]);
        } else {
            echo json_encode(["message" => "Something went wrong!.", "status" => 0]);
        }
    }

    public function save_comment(){
        $data = $this->input->post('image');
        foreach($data as $key => $input){
           $image_id = $input['image_id'];
           $comment = $input['comment'];
           $sequence = $input['sequence'];
           $inputs = array(
               'comment'    => $comment,
               'image_sequence' => $sequence
           );
           $update = $this->mlm->update_comment($inputs,$image_id);
        }
        if($update){
            echo json_encode(["message" => "Data saved successfully.", "status" => 1]);
        } else {
            echo json_encode(["message" => "Something went wrong!.", "status" => 0]);
        }
    }
	 public function get_generate_report_data()
    {
        $ids =   $this->input->post();
        $data['report_data'] =  $this->mlm->get_generate_report_data($ids);
        $data['parts_data'] =  $this->mlm->get_parts_Details($ids);
        $data['report_format'] = $this->mlm->get_report_format($ids);
        // print_r($data1);die;
        if ($data) {
            $generate_data = array(
                'ulr_no_flag' => $data['report_data']->ulr_no_flag,
                'issuance_date' => $data['report_data']->issuance_date,
                'remark' => html_entity_decode($data['report_data']->remark),
                'manual_report_result' => $data['report_data']->manual_report_result,
                'manual_report_remark' => $data['report_data']->manual_report_remark,
                'sample_images_flag' => $data['report_data']->sample_images_flag,
                'name' => $data['report_data']->name,
                'parts'=>$data['parts_data'],
                'report_format'=>$data['report_format']
            );

            echo json_encode($generate_data);
        }
    }


    public function get_release_to_client_data(){
        $data = $this->input->post();
       $email = $this->mlm->get_release_to_client_data($data);
      
           echo json_encode($email);
       
       
    }
    public function Release_to_client(){
        $data = $this->input->post();
        // print_r($data);die;
       $file = $this->mlm->get_row('gr.manual_report_file','generated_reports gr',['gr.report_id'=> $data['report_id']]);
    //    print_r($file);die;
    if($data['mail'] == 1){
        $send_mail = send_mail_while_Release_to_Client($data['to'],NULL, $data['cc'], $data['email_body'], $data['subject'] ,$file->manual_report_file,$file->manual_report_file);
    }
        // if($send_mail){
           $done = $this->mlm->update_data('sample_registration sr',['released_to_client'=>'1'],['sr.sample_reg_id'=>$data['sample_reg_id']]);
           if($done){
            $this->session->set_flashdata('success', 'Report Release to client successfully');
            redirect($_SERVER['HTTP_REFERER']);

        }
        else{
            $this->session->set_flashdata('error', 'Error while saving data');
            redirect($_SERVER['HTTP_REFERER']);

        }
        // }
        // else{
        //     $this->session->set_flashdata('error', 'Error while Sending Email');
        //     redirect($_SERVER['HTTP_REFERER']);

        // }
    }


    public function deleteimage(){
        $id = $this->input->post();
        // print_r($id['id']);die;
      $delete =  $this->mlm->delete_row('report_generated_images',['images_id'=>$id['id']]);
      if($delete){
          $msg = array(
              'status'=>1,
              'msg'=>'Image Delete Successfull'
          );
      }
      else{
        $msg = array(
            'status'=>0,
            'msg'=>'Something Went wrong'
        );
      }
       echo json_encode($msg);
    }

   
}
