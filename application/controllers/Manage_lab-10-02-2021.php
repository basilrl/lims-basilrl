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
    public function index($applicant='NULL', $product='NULL', $search_url='NULL', $start_date='NULL', $end_date='NULL', $stauts='NULL')
    {
        $where = array();
        $search = NULL;
        // echo $applicant . " " . $product . " " . $search . " " . $start_date . " " . $end_date . " " . $stauts;
        $base_url = base_url() . "Manage_lab/index";
        $base_url .= '/' . (($applicant != 'NULL') ? $applicant : 'NULL');
        $base_url .= '/' . (($product != 'NULL') ? $product : 'NULL');
        $base_url .= '/' . (($search_url != 'NULL') ? ($search_url) : 'NULL');
        $base_url .= '/' . (($start_date != 'NULL') ? $start_date : 'NULL');
        $base_url .= '/' . (($end_date != 'NULL') ? $end_date : 'NULL');
        $base_url .= '/' . (($stauts != 'NULL') ? ($stauts) : 'NULL');
        $data['applicant_id'] = ($applicant != 'NULL') ? $applicant : 'NULL';
        $data['product_id'] = ($product != 'NULL') ? $product : 'NULL';
        $data['search_url'] = ($search_url != 'NULL') ? base64_decode($search_url) : 'NULL';
        $data['start_date'] = ($start_date != 'NULL') ? $start_date : 'NULL';
        $data['end_date'] = ($end_date != 'NULL') ? $end_date : 'NULL';
        $data['stauts'] = ($stauts != 'NULL') ? base64_decode($stauts) : 'NULL';
        if ($applicant!='NULL') {
          $customer = $this->mlm->get_row('customer_name','cust_customers',['customer_id'=>$applicant]);
          if ($customer) {
            $data['applicant_name'] = $customer->customer_name;
          } else {
            $data['applicant_name'] = 'NULL';
          }
        }else{
            $data['applicant_name'] = 'NULL';
        }
        if ($product!='NULL') {
            $customer = $this->mlm->get_row('sample_type_name','mst_sample_types',['sample_type_id'=>$product]);
            if ($customer) {
              $data['product_name'] = $customer->sample_type_name;
            } else {
              $data['product_name'] = 'NULL';
            }
          }else{
              $data['product_name'] = 'NULL';
          }
        if ($applicant!= 'NULL') {
             $where['tr.trf_applicant']= $applicant;
        }
        if ($product!= 'NULL') {
            $where['sr.sample_registration_sample_type_id']=$product;
        }
        if ($search_url!= 'NULL') {
            $search = base64_decode($search_url);
        }
        if ($start_date!= 'NULL') {
            $where['sr.received_date >=']= ($start_date);
        }
        if ($end_date!= 'NULL') {
            $where['sr.received_date <='] = ($end_date);
        }
        if ($stauts!= 'NULL') {
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
        $config["total_rows"] = $this->mlm->accepted_sample_list(NULL, NULL,$where,$search, '1');
        $config["per_page"] = 10;
        $config["uri_segment"] = 9;
        $config["base_url"] = $base_url;
        $config1 = $this->pagination->initialize($config);
        $page = ($this->uri->segment(9)) ? $this->uri->segment(9) : 0;
        $data['accepted_sample'] = $this->mlm->accepted_sample_list($config1->per_page, $page,$where,$search);
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
            redirect('Manage_lab/index');
        } else {
            $this->session->set_flashdata('false', 'Something went wrong!.');
        }


        // print_r($data);die;
    }


    public function record_finding($applicant='NULL', $product='NULL', $search_url='NULL', $start_date='NULL', $end_date='NULL')
    {
        $where = array();
        $search = NULL;
        $base_url = "Manage_lab/record_finding";
        $base_url .= '/' . (($applicant != 'NULL') ? $applicant : 'NULL');
        $base_url .= '/' . (($product != 'NULL') ? $product : 'NULL');
        $base_url .= '/' . (($search_url != 'NULL') ? ($search_url) : 'NULL');
        $base_url .= '/' . (($start_date != 'NULL') ? $start_date : 'NULL');
        $base_url .= '/' . (($end_date != 'NULL') ? $end_date : 'NULL');
        // $base_url .= '/' . (($stauts != 'NULL') ? ($stauts) : 'NULL');
        $data['applicant_id'] = ($applicant != 'NULL') ? $applicant : 'NULL';
        $data['product_id'] = ($product != 'NULL') ? $product : 'NULL';
        $data['search_url'] = ($search_url != 'NULL') ? trim(base64_decode($search_url)) : 'NULL';
        $data['start_date'] = ($start_date != 'NULL') ? $start_date : 'NULL';
        $data['end_date'] = ($end_date != 'NULL') ? $end_date : 'NULL';
        // $data['stauts'] = ($stauts != 'NULL') ? base64_decode($stauts) : 'NULL';
        if ($applicant!='NULL') {
          $customer = $this->mlm->get_row('customer_name','cust_customers',['customer_id'=>$applicant]);
          if ($customer) {
            $data['applicant_name'] = $customer->customer_name;
          } else {
            $data['applicant_name'] = 'NULL';
          }
        }else{
            $data['applicant_name'] = 'NULL';
        }
        if ($product!='NULL') {
            $customer = $this->mlm->get_row('test_name','tests',['test_id'=>$product]);
            if ($customer) {
              $data['product_name'] = trim($customer->test_name);
            } else {
              $data['product_name'] = 'NULL';
            }
          }else{
              $data['product_name'] = 'NULL';
          }
        if ($applicant!= 'NULL') {
             $where['tr.trf_applicant']= $applicant;
        }
        if ($product!= 'NULL') {
            $where['st.sample_test_test_id']=$product;
        }
        if ($search_url!= 'NULL') {
            $search = base64_decode($search_url);
        }
        if ($start_date!= 'NULL') {
            $where['sr.received_date >=']= ($start_date);
        }
        if ($end_date!= 'NULL') {
            $where['sr.received_date <='] = ($end_date);
        }
        $total_row = $this->mlm->record_finding_list(NULL, NULL, $where,$search,'1');

        $page = ($this->uri->segment(8)) ? $this->uri->segment(8) : 0;
        $config = $this->pagination($base_url, $total_row, 10, 8);

        $data["links"] = $config["links"];
        // print_r($data);exit;
        $data['list'] = $this->mlm->record_finding_list($config["per_page"], $page,$where,$search);
        $start = ($data['list']) ? (int)$page + 1 : 0;
        $end = (($data['list']) ? count($data['list']) : 0) + (($page) ? $page : 0);
        $data['result_count'] = "Showing " . $start . " - " . $end . " of " . $total_row . " Results";

        $this->load_view('manage_lab/record_finding', $data);
    }

    public function open_record_finding($id, $sample_test_id)
    {
        $data['sample_reg_id'] = base64_decode($id);
        $data['sample_test_id'] = base64_decode($sample_test_id);

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
            $post['test_component'] = $data['test_component'];
            if (empty($data['lab_id'])) {
                unset($data['lab_id']);
            }
            else{
                $post['lab_id'] = $data['lab_id'];
            }
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


    public function edit_record_finding($record_finding_id, $sample_reg_id)
    {
        $data['record_finding_id'] = base64_decode($record_finding_id);

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
       
                    
//            echo "<pre>";
//            print_r($data);
//             print_r($_FILES);
//            exit;
            
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
            $post['test_component'] = $data['test_component'];
            if (empty($data['lab_id'])) {
                unset($data['lab_id']);
            }
            else{
                $post['lab_id'] = $data['lab_id'];
            }
              
            $record_finding_id = $data['record_finding_id'];
            //echo "<br>";
            $result = $this->mlm->update_data('record_finding_details', $post, array('record_finding_id' => $record_finding_id));
//            echo $result;
//            echo "<pre>";
//            print_r($post);
//            exit;
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
//                    if ($data['images']) {
//
//                        $upload_image = $this->mlm->update_data("report_generated_images", $images, array('record_finding_id' => $data['record_finding_id']));
//                    } else {
//                        $upload_image = $this->mlm->insert_data("report_generated_images", $images);
//                        
//                    }


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

    public function report_listing($applicant='NULL', $product='NULL', $search_url='NULL', $start_date='NULL', $end_date='NULL')
    {
        $where = array();
        $search = NULL;
        // echo $applicant . " " . $product . " " . $search . " " . $start_date . " " . $end_date . " " . $stauts;
        $base_url = "Manage_lab/report_listing";
        $base_url .= '/' . (($applicant != 'NULL') ? $applicant : 'NULL');
        $base_url .= '/' . (($product != 'NULL') ? $product : 'NULL');
        $base_url .= '/' . (($search_url != 'NULL') ? ($search_url) : 'NULL');
        $base_url .= '/' . (($start_date != 'NULL') ? $start_date : 'NULL');
        $base_url .= '/' . (($end_date != 'NULL') ? $end_date : 'NULL');
        // $base_url .= '/' . (($stauts != 'NULL') ? ($stauts) : 'NULL');
        $data['applicant_id'] = ($applicant != 'NULL') ? $applicant : 'NULL';
        $data['product_id'] = ($product != 'NULL') ? $product : 'NULL';
        $data['search_url'] = ($search_url != 'NULL') ? base64_decode($search_url) : 'NULL';
        $data['start_date'] = ($start_date != 'NULL') ? $start_date : 'NULL';
        $data['end_date'] = ($end_date != 'NULL') ? $end_date : 'NULL';
        // $data['stauts'] = ($stauts != 'NULL') ? base64_decode($stauts) : 'NULL';
        if ($applicant!='NULL') {
          $customer = $this->mlm->get_row('customer_name','cust_customers',['customer_id'=>$applicant]);
          if ($customer) {
            $data['applicant_name'] = $customer->customer_name;
          } else {
            $data['applicant_name'] = 'NULL';
          }
        }else{
            $data['applicant_name'] = 'NULL';
        }
        if ($product!='NULL') {
            $customer = $this->mlm->get_row('sample_type_name','mst_sample_types',['sample_type_id'=>$product]);
            if ($customer) {
              $data['product_name'] = $customer->sample_type_name;
            } else {
              $data['product_name'] = 'NULL';
            }
          }else{
              $data['product_name'] = 'NULL';
          }
        if ($applicant!= 'NULL') {
             $where['tr.trf_applicant']= $applicant;
        }
        if ($product!= 'NULL') {
            $where['sr.sample_registration_sample_type_id']=$product;
        }
        if ($search_url!= 'NULL') {
            $search = base64_decode($search_url);
        }
        if ($start_date!= 'NULL') {
            $where['sr.received_date >=']= ($start_date);
        }
        if ($end_date!= 'NULL') {
            $where['sr.received_date <='] = ($end_date);
        }
        // if ($stauts!= 'NULL') {
        //     $where['sr.status'] = base64_decode($stauts);
        // }
        $total_row = $this->mlm->report_listing(NULL, NULL,$where,$search, '1');

        $page = ($this->uri->segment(8)) ? $this->uri->segment(8) : 0;
        $config = $this->pagination($base_url, $total_row, 10, 8);

        $data["links"] = $config["links"];
        $data['report_listing'] =  $this->mlm->report_listing($config["per_page"], $page,$where,$search);
    //   echo '<pre>';  print_r($data['report_listing']);die;
        $data['sign_auth'] =  $this->mlm->get_report_approver();
        // $data['sign_auth'] =  $this->mlm->get_report_approver();

        $this->load_view('manage_lab/report_listing', $data);
    }



    public function Final_report_generate()
    {
        $checkUser = $this->session->userdata('user_data');

        $data = $this->input->post();
        // $update['cps_data'] = $this->mlm->get_cps_data($data['sample_reg_id']);
        // $update['application_data'] =  $this->mlm->get_application_care($data);

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
            // 'sign_authority_new' => $data['sign_auth1']

        );
        // print_r($report_data);die;
        $report_id = $this->mlm->get_row('report_id', 'generated_reports', $where);
        $revise_flag = $this->mlm->get_row('revise_flag', 'sample_registration', $where);
        $additional_flag = $this->mlm->get_row('additional_flag', 'sample_registration', $where);

        if ($report_id->report_id && $revise_flag->revise_flag == 0) {
            $report_where = array('report_id' => $report_id->report_id);
            $updat_generate_report =   $this->mlm->update_data('generated_reports', $report_data, $report_where);
        } elseif ($report_id->report_id && $revise_flag->revise_flag > 0) {
            // echo 'revise';
            $revise_count =  $this->mlm->get_row('revise_count', 'sample_registration', array('sample_reg_id' => $data['sample_reg_id']));
            $revise_total = $revise_count->revise_count + 1;

            $revise_array = array(
                1 => 'A',
                2 => 'B',
                3 => 'C',
                4 => 'D',
                5 => 'E',
                6 => 'F',
                7 => 'G',
                8 => 'H',
                9 => 'I',
                10 => 'J',
                11 => 'K',
                12 => 'L',
                13 => 'M',
                14 => 'N',
                15 => 'O'
            );
            $report_new_name =  $revise_array[$revise_total];
            // echo "revise";
            // echo $report_new_name;die;
            $report_data = array(
                'generated_date' => date("Y-m-d"),
                'generated_by' => $this->user,
                'sample_reg_id' => $data['sample_reg_id'],
                'background_process' => 'Completed',
                'status' => 'Report Generated',
                'report_num' => $gc_no->gc_no . '-' . $report_new_name,
                'report_type' => 'System Report',
                'signing_authority' => $data['sign_auth'],
                // 'sign_authority_new' => $data['sign_auth1']

            );
            // echo '<pre>';
            // print_r($report_data);die;
            $this->mlm->update_data('sample_registration', array('revise_count' => $revise_total), array('sample_reg_id' => $data['sample_reg_id']));
            // echo $this->db->last_query();die;    
            // print_r($report_data);die;

            $update['reprot_generated_id'] = $this->mlm->report_generate_data($report_data);
            $updat_generate_report = $update['reprot_generated_id'];
        } elseif ($report_id->report_id && $additional_flag->additional_flag > 0) {
            // echo 'revise';
            $add_count =  $this->mlm->get_row('additional_count', 'sample_registration', array('sample_reg_id' => $data['sample_reg_id']));
            $add_total = $add_count->additional_count + 1;

            $report_new_no =  $add_total;
            // echo "revise";
            // echo $report_new_name;die;
            $report_data = array(
                'generated_date' => date("Y-m-d"),
                'generated_by' => $this->user,
                'sample_reg_id' => $data['sample_reg_id'],
                'background_process' => 'Completed',
                'status' => 'Report Generated',
                'report_num' => $gc_no->gc_no . '-' . $report_new_no,
                'report_type' => 'System Report',
                'signing_authority' => $data['sign_auth'],
                // 'sign_authority_new' => $data['sign_auth1']

            );
            // echo '<pre>';
            // print_r($report_data);die;
            $this->mlm->update_data('sample_registration', array('additional_count' => $add_total), array('sample_reg_id' => $data['sample_reg_id']));
            // echo $this->db->last_query();die;    
            // print_r($report_data);die;

            $update['reprot_generated_id'] = $this->mlm->report_generate_data($report_data);
            $updat_generate_report = $update['reprot_generated_id'];
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
        Please Login to <a href="https://lims.basilrl.com">lims.basilrl.com</a> to approve.<br />
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

            $send_mail = send_to_report_approval($approver_email->email, NULL, NULL, $subject, $message);



            if ($send_mail) {
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
        $checkUser = $this->session->userdata('user_data');
        $update['report_data'] =  $this->mlm->pdf_data_get($data);
        $sample_images =  $this->mlm->sample_final_images($data);
        //print_r($sample_images);
        
        $update['image_sample'] = $sample_images;
    
        $application_data= $this->mlm->get_application_care($update);
        if ($application_data) {
            $appData = array();
            if (count($application_data) > 0) {
                foreach ($application_data as $k => $app) {
                    $appData[$k]['instruction_image'] = $this->getS3Url($app->instruction_image);
                    $appData[$k]['instruction_name'] = $app->instruction_name;
                }
            }
            $update['application_data'] = $appData;
        } else {
            $update['application_data'] = null;
        }
      
        $update['cps_data'] = $this->mlm->get_cps_data('*',$data['sample_reg_id']);
        $update['nabl_record'] = $this->mlm->getNABLCpsResultData('record_finding_id,nabl_remark,nabl_detail,nabl_headings',$data['sample_reg_id']);
      
        $update['non_nabl_record'] = $this->mlm->getNONNABLCpsResultData('record_finding_id,non_nabl_remark,non_nabl_detail,non_nabl_headings',$data['sample_reg_id']);
//               echo "<pre>";
//        print_r($update);
//        exit;
        
        foreach ($update['cps_data'] as $key => $rfd_id) {
            $image  = $this->mlm->get_images($rfd_id['record_finding_id']);
            if ($image) {
                $update['cps_data'][$key]['images'] = $image;
            }
        }
        if($update['nabl_record']!=''){
        foreach ($update['nabl_record'] as $key => $rfd_id) {
            if (!empty($update['nabl_record'][$key]['nabl_headings'])) {
                $update['nabl_record'][$key]['nabl_headings'] = json_decode(stripslashes($update['nabl_record'][$key]['nabl_headings']));
            }
            $image  = $this->mlm->get_images($rfd_id['record_finding_id']);
            if ($image) {
                $update['nabl_record'][$key]['images'] = $image;
            }
        }
        } else {
            $update['nabl_record']='';
        }
         if($update['non_nabl_record']!=''){
        foreach ($update['non_nabl_record'] as $key => $rfd_id) {
            if (!empty($update['non_nabl_record'][$key]['non_nabl_headings'])) {
                $update['non_nabl_record'][$key]['non_nabl_headings'] = json_decode(stripslashes($update['non_nabl_record'][$key]['non_nabl_headings']));
            }
            $image  = $this->mlm->get_images($rfd_id['record_finding_id']);
            if ($image) {
                $update['non_nabl_record'][$key]['images'] = $image;
            }
         }
         } else {
             $update['non_nabl_record']='';
         }
         $update['sign_data1'] = Null;

        // $sign1 = $this->mlm->get_row('signing_authority', 'generated_reports', ['report_id' => $update['report_data']->report_id]);

        // $sign2 = $this->mlm->get_row('sign_authority_new', 'generated_reports', ['report_id' => $update['report_data']->report_id]);

        // if ($sign1) {
        //     $signature1 = $this->mlm->get_row('sign_path', 'admin_signature', ['admin_id' => $sign1->signing_authority]);
        // }
        // if ($sign2) {
        //     $signature2 = $this->mlm->get_row('sign_path', 'admin_signature', ['admin_id' => $sign2->sign_authority_new]);
        // }

        // if ($signature1) {
        //     $update['signature1'] = $this->getS3Url($signature1->sign_path);
        // }
        // if ($signature2) {
        //     $update['signature2'] = $this->getS3Url($signature2->sign_path);
        // }
        // $update['sign_data1'] = $this->mlm->getsignvalues($sign1->signing_authority);
        // $update['sign_data2'] = $this->mlm->getsignvalues($sign2->sign_authority_new);
        $update['test_data'] =  $this->mlm->get_test_result($data);

        if ($update['report_data']->branch_name == 'Gurgaon') {

            $this->generate_pdf('manage_lab/ggnreport', $update);
        }
        if ($update['report_data']->branch_name == 'Dubai') {

            $this->generate_pdf('manage_lab/uaereport', $update);
        }
        if ($update['report_data']->branch_name == 'Dhaka') {

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
        $update['report_data'] =  $this->mlm->pdf_data_get($data);
        $sample_images =  $this->mlm->sample_final_images($data);
        //print_r($sample_images);
        
        $update['image_sample'] = $sample_images;
        $application_data= $this->mlm->get_application_care($update);
        if ($application_data) {
            $appData = array();
            if (count($application_data) > 0) {
                foreach ($application_data as $k => $app) {
                    $appData[$k]['instruction_image'] = $this->getS3Url($app->instruction_image);
                    $appData[$k]['instruction_name'] = $app->instruction_name;
                }
            }
            $update['application_data'] = $appData;
        } else {
            $update['application_data'] = null;
        }
     
        $update['cps_data'] = $this->mlm->get_cps_data('*',$data['sample_reg_id']);
        $update['nabl_record'] = $this->mlm->getNABLCpsResultData('record_finding_id,nabl_remark,nabl_detail,nabl_headings',$data['sample_reg_id']);
        $update['non_nabl_record'] = $this->mlm->getNONNABLCpsResultData('record_finding_id,non_nabl_remark,non_nabl_detail,non_nabl_headings',$data['sample_reg_id']);
//               echo "<pre>";
//        print_r($update);
//        exit;
        
        foreach ($update['cps_data'] as $key => $rfd_id) {
            $image  = $this->mlm->get_images($rfd_id['record_finding_id']);
            if ($image) {
                $update['cps_data'][$key]['images'] = $image;
            }
        }
        if($update['nabl_record']!=''){
        foreach ($update['nabl_record'] as $key => $rfd_id) {
            if (!empty($update['nabl_record'][$key]['nabl_headings'])) {
                $update['nabl_record'][$key]['nabl_headings'] = json_decode(stripslashes($update['nabl_record'][$key]['nabl_headings']));
            }
            $image  = $this->mlm->get_images($rfd_id['record_finding_id']);
            if ($image) {
                $update['nabl_record'][$key]['images'] = $image;
            }
        }
        } else {
            $update['nabl_record']='';
        }
         if($update['non_nabl_record']!=''){
        foreach ($update['non_nabl_record'] as $key => $rfd_id) {
            if (!empty($update['non_nabl_record'][$key]['non_nabl_headings'])) {
                $update['non_nabl_record'][$key]['non_nabl_headings'] = json_decode(stripslashes($update['non_nabl_record'][$key]['non_nabl_headings']));
            }
            $image  = $this->mlm->get_images($rfd_id['record_finding_id']);
            if ($image) {
                $update['non_nabl_record'][$key]['images'] = $image;
            }
         }
         } else {
             $update['non_nabl_record']='';
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

        if ($update['report_data']->branch_name == 'Gurgaon') {

            $this->generate_pdf('manage_lab/ggnreport', $update);
        }
        if ($update['report_data']->branch_name == 'Dubai') {

            $this->generate_pdf('manage_lab/uaereport', $update);
        }
        if ($update['report_data']->branch_name == 'Dhaka') {

            $this->generate_pdf('manage_lab/bdreport', $update);
        }
    }
    public function approve_report()
    {
        $data = $this->input->post();
        $checkUser = $this->session->userdata('user_data');
        $update['cps_data'] = $this->mlm->get_cps_data('*',$data['sample_reg_id']);
        $update['report_data'] =  $this->mlm->pdf_data_get($data);
         $sample_images =  $this->mlm->sample_final_images($data);
        //print_r($sample_images);
        
        $update['image_sample'] = $sample_images;
        $application_data= $this->mlm->get_application_care($update);
        if ($application_data) {
            $appData = array();
            if (count($application_data) > 0) {
                foreach ($application_data as $k => $app) {
                    $appData[$k]['instruction_image'] = $this->getS3Url($app->instruction_image);
                    $appData[$k]['instruction_name'] = $app->instruction_name;
                }
            }
            $update['application_data'] = $appData;
        } else {
            $update['application_data'] = null;
        }
       
       $update['nabl_record'] = $this->mlm->getNABLCpsResultData('record_finding_id,nabl_remark,nabl_detail,nabl_headings',$data['sample_reg_id']);
       $update['non_nabl_record'] = $this->mlm->getNONNABLCpsResultData('record_finding_id,non_nabl_remark,non_nabl_detail,non_nabl_headings',$data['sample_reg_id']);
       foreach ($update['cps_data'] as $key => $rfd_id) {
            $image  = $this->mlm->get_images($rfd_id['record_finding_id']);
            if ($image) {
                $update['cps_data'][$key]['images'] = $image;
            }
        }
        if($update['nabl_record']!=''){
        foreach ($update['nabl_record'] as $key => $rfd_id) {
            if (!empty($update['nabl_record'][$key]['nabl_headings'])) {
                $update['nabl_record'][$key]['nabl_headings'] = json_decode(stripslashes($update['nabl_record'][$key]['nabl_headings']));
            }
            $image  = $this->mlm->get_images($rfd_id['record_finding_id']);
            if ($image) {
                $update['nabl_record'][$key]['images'] = $image;
            }
        }
        } else {
            $update['nabl_record']='';
        }
         if($update['non_nabl_record']!=''){
        foreach ($update['non_nabl_record'] as $key => $rfd_id) {
            if (!empty($update['non_nabl_record'][$key]['non_nabl_headings'])) {
                $update['non_nabl_record'][$key]['non_nabl_headings'] = json_decode(stripslashes($update['non_nabl_record'][$key]['non_nabl_headings']));
            }
            $image  = $this->mlm->get_images($rfd_id['record_finding_id']);
            if ($image) {
                $update['non_nabl_record'][$key]['images'] = $image;
            }
         }
         } else {
             $update['non_nabl_record']='';
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
//        echo "<pre>";
//        print_r($update);
//        exit;
        $this->load->library('Ciqrcode');
        $params['data'] =  base_url('Render/download_pdf?report_id=' . base64_encode($data['report_id']) . '&sample_rg=' . base64_encode($update['report_data']->sample_registration_id));
        $params['level'] = 'H';
        $params['size'] = 1;
        $gc_no = $update['report_data']->gc_no;
        $params['savename'] = QRCODE . (($gc_no) ? $gc_no : rand(0000, 9999)) . '.png';
        $cer_po = $this->ciqrcode->generate($params); // genrate image
        // print_r($params);die;
        $update['qrcode'] = $params['savename'];

        if ($update['report_data']->branch_name == 'Gurgaon') {

            $pdf_body =  $this->generate_pdf('manage_lab/ggnreport', $update, 'aws_save');
        }
        if ($update['report_data']->branch_name == 'Dubai') {

            $pdf_body =  $this->generate_pdf('manage_lab/uaereport', $update, 'aws_save');
        }
        if ($update['report_data']->branch_name == 'Dhaka') {

            $pdf_body =  $this->generate_pdf('manage_lab/bdreport', $update, 'aws_save');
        }
        if ($pdf_body) {
            $upload_path = $this->report_upload_aws($pdf_body, $gc_no . '-' . rand(0, 999) . $data['reprot_id'] . '.pdf');
            if ($upload_path) {
                // print_r($data);
                $save_aws =  $this->mlm->update_data('generated_reports', array('manual_report_file' => $upload_path['aws_path'], 'status' => 'Report Approved'), array('report_id' => $data['report_id']));
                // echo $this->db->last_query();

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

    public function delete_report_sample_image(){
        $image_id = $this->input->post('image_id');
        $delete = $this->mlm->remove_report_sample_image($image_id);
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
        $data =  $this->mlm->get_generate_report_data($ids);
        if ($data) {
            $generate_data = array(
                'ulr_no_flag' => $data->ulr_no_flag,
                'issuance_date' => $data->issuance_date,
                'remark' => html_entity_decode($data->remark),
                'manual_report_result' => $data->manual_report_result,
                'manual_report_remark' => $data->manual_report_remark,
                'sample_images_flag' => $data->sample_images_flag,
                'name' => $data->name,
            );

            echo json_encode($generate_data);
        }
    }
}
