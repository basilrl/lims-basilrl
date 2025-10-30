<?php

defined('BASEPATH') or exit('No direct script access allowed');

class NewsFlash extends MY_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('NewsFlash_model');
        $this->check_session();
        $checkUser = $this->session->userdata('user_data');
        $this->user = $checkUser->uidnr_admin;
    }

    public function index()
    {
        $where = NULL;
        $search = NULL;
        $sortby = NULL;
        $order = NULL;

        $base_url = 'NewsFlash/index';

        if ($this->uri->segment('3') != NULL && $this->uri->segment('3') != 'NULL') {
            $title_news = $this->uri->segment('3');
            $data['title_news'] =  base64_decode($title_news);
            $base_url .= '/' . $title_news;
            $where['mnf.news_id'] = base64_decode($title_news);
        } else {
            $base_url .= '/NULL';
            $data['title_news'] = NULL;
        }

        if ($this->uri->segment('4') != NULL && $this->uri->segment('4') != 'NULL') {
            $created_pesron = $this->uri->segment('4');
            $data['created_pesron'] =  base64_decode($created_pesron);
            $base_url .= '/' . $created_pesron;
            $where['ap.uidnr_admin'] = base64_decode($created_pesron);
        } else {
            $base_url .= '/NULL';
            $data['created_pesron'] = NULL;
        }

        if ($this->uri->segment('5') != NULL && $this->uri->segment('5') != 'NULL') {
            $status = $this->uri->segment('5');
            $data['status'] =  base64_decode($status);
            $base_url .= '/' . $status;
            $where['mnf.status'] = base64_decode($status);
        } else {
            $base_url .= '/NULL';
            $data['status'] = NULL;
        }

        if ($this->uri->segment('6') != NULL && $this->uri->segment('6') != 'NULL') {
            $search = $this->uri->segment('6');
            $data['search'] =  base64_decode($search);
            $base_url .= '/' . $search;
            $search = base64_decode($search);
        } else {
            $base_url .= '/NULL';
            $data['search'] = NULL;
        }

        if ($this->uri->segment('7') != NULL && $this->uri->segment('7') != 'NULL') {
            $sortby = $this->uri->segment('7');
            $base_url .= '/' . $sortby;
        } else {
            $base_url .= '/NULL';
        }

        if ($this->uri->segment('8') != NULL && $this->uri->segment('8') != 'NULL') {
            $order = $this->uri->segment('8');
            $base_url .= '/' . $order;
        } else {
            $base_url .= '/NULL';
        }

        $total_row = $this->NewsFlash_model->fetch_newsflash_list(NULL, NULL, $search, NULL, NULL, $where, '1');
        $config = $this->pagination($base_url, $total_row, 10, 9);
        $data["links"] = $config["links"];
        $data['titles'] = $this->NewsFlash_model->fetch_titles();
        $data['created_by_name'] = $this->NewsFlash_model->fetch_created_person();
        $data['newsflash_list'] = $this->NewsFlash_model->fetch_newsflash_list($config["per_page"], $config['page'], $search, $sortby, $order, $where);
        $page_no = $this->uri->segment('9');
        $start = (int)$page_no + 1;
        $end = (($data['newsflash_list']) ? count($data['newsflash_list']) : 0) + (($page_no) ? $page_no : 0);
        $data['result_count'] = "Showing " . $start . " - " . $end . " of " . $total_row . " Results";
        if ($order == NULL || $order == 'NULL') {
            $data['order'] = ($order) ? "DESC" : "ASC";
        } else {
            $data['order'] = ($order == "ASC") ? "DESC" : "ASC";
        }
        $this->load_view('newsflash/manage_newsflash', $data);
    }

    public function add_newsflash()
    {
      
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
        $this->form_validation->set_rules('title', 'News Title', 'required|is_unique[manage_news_flash.title]');
        $this->form_validation->set_rules('status', 'Status', 'required');
        if ($this->form_validation->run() == false) {
            $data = array(
                'errors' => $this->form_validation->error_array(),
                'status' => 0,
                'msg' => 'fill all required fields'
            );
        } else {
            $checkUser = (array) $this->session->userdata('user_data');
            $fill_data = $this->input->post();

            if (!empty($fill_data)) {
//              
                                
             $aws_path =  (isset($_FILES['images']) && !empty($_FILES['images'])) ? $this->_upload_image($_FILES['images']) : ''; 
               
                $inserted_data = array(
                    'title' => $fill_data['title'],
                    'content' => htmlentities($fill_data['content_add']),
                    'status' => $fill_data['status'],
                    'created_by' => $checkUser['uidnr_admin'],
                    'created_date' => date("Y-m-d h:i:s"),
                    'aws_path' => $aws_path
                    );
                $status = $this->NewsFlash_model->insert_newsflash($inserted_data);
                if ($status) {
                    $log = $this->user_log_update($status, 'NEWS FLASH ADDED WITH TITLE ' . $fill_data['title'], 'ADD NEWS FLASH');

                    if ($log) {
                        $this->session->set_flashdata('success', 'News Flash added Sucessfully');
                        $data = array(
                            'status' => 1,
                            'msg' => 'News Flash added Sucessfully'
                        );
                    } else {
                        $data = array(
                            'status' => 0,
                            'msg' => 'error in generating log'
                        );
                    }
                } else {
                    $this->session->set_flashdata('error', 'Error in adding News Flash');
                    $data = array(
                        'status' => 0,
                        'msg' => 'Error in adding News Flash'
                    );
                }
            } else {
                $this->session->set_flashdata('error', 'Data Not Found ');
                $data = array(
                    'status' => 0,
                    'msg' => 'Data Not Found '
                );
            }
        }
        echo json_encode($data);
    }

    public function delete_newsflash()
    {
        if (!empty($this->input->get())) {
            $id = $this->input->get('news_id');

            $status = $this->NewsFlash_model->delete_newsflash($this->input->get());
            if ($status) {

                $log = $this->user_log_update($id, 'NEWS FLASH DELETED', 'DELETE NEWS FLASH');
                if ($log) {
                    $this->session->set_flashdata('success', 'News Flash Deleted sucessfully...');
                    redirect(base_url() . 'NewsFlash/');
                } else {
                    $this->session->set_flashdata('error', 'Error in deleting News Flash !!!');
                    redirect(base_url() . 'NewsFlash/');
                }
            } else {
                $this->session->set_flashdata('error', 'Error in deleting News Flash !!!');
                redirect(base_url() . 'NewsFlash/');
            }
        }
    }

    public function fetch_newsflash_for_edit()
    {
        if (!empty($this->input->post())) {
            $fetch_data = $this->NewsFlash_model->fetch_newsflash_for_edit($this->input->post());
            if ($fetch_data) {
                if (!empty($fetch_data->content)) {
                    $fetch_data->content = html_entity_decode($fetch_data->content);
                }
                echo json_encode($fetch_data);
            } else {
                echo false;
            }
        }
    }

    public function update_newsflash()
    {
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
        $this->form_validation->set_rules('title', 'News Title', 'required');
        if ($this->form_validation->run() == false) {
            $data = array(
                'error' => $this->form_validation->error_array(),
                'status' => 0,
                'msg' => 'fill all required fields'
            );
        } else {
            $store_data = array();
            $checkUser = $this->session->userdata();
            $fetch_data = $this->input->post();
            $store_data['title'] = $fetch_data['title'];
            $store_data['content'] = htmlentities($fetch_data['content']);
            $store_data['created_by'] = $checkUser['user_data']->uidnr_admin;
            $store_data['created_date'] = date("Y-m-d h:i:s");
            $where['news_id'] = $fetch_data['news_id'];
            if (!empty($fetch_data)) {
                $data_updated = $this->NewsFlash_model->update_data('manage_news_flash', $store_data, $where);
                if ($data_updated) {

                    $log = $this->user_log_update($where['news_id'], 'NEWS FLASH UPDATED WITH TITLE ' . $store_data['title'], 'UPDATE NEWS FLASH');
                    if ($log) {
                        $this->session->set_flashdata('success', 'News Flash Updated Successfully');
                        $data = array(
                            'status' => 1,
                            'msg' => 'News Flash Updated Successfully'
                        );
                    } else {
                        $data = array(
                            'status' => 0,
                            'msg' => 'error in generating log'
                        );
                    }
                } else {
                    $this->session->set_flashdata('error', 'Error in Updating News Flash');
                    $data = array(
                        'status' => 0,
                        'msg' => 'Error in Updating News Flash'
                    );
                }
            } else {
                $this->session->set_flashdata('error', 'Data Not Found !!');
                $data = array(
                    'status' => 0,
                    'msg' => 'Data Not Found !!'
                );
            }
        }
        echo json_encode($data);
    }

    public function newsflash_status()
    {
        if (!empty($this->input->post())) {
            $id = $this->input->post('news_id');

            $status = $this->NewsFlash_model->update_newsflash_status($this->input->post());
            if ($status) {

                $log = $this->user_log_update($id, 'NEWS FLASH STATUS UPDATED', 'NEWS FLASH STATUS');
                if ($log) {
                    $this->session->set_flashdata('success', 'News Flash Status Updated sucessfully');
                    echo json_encode(array('msg' => 'upadte status'));
                } else {
                    $this->session->set_flashdata('error', 'Error in generating log');
                    echo json_encode(array('msg' => 'not upadte status'));
                }
            } else {
                $this->session->set_flashdata('error', 'Error in Updating News Flash Status');
                echo json_encode(array('msg' => 'not upadte status'));
            }
        }
    }


    public function get_log_data()
    {
        $id = $this->input->post('id');
        $data = $this->NewsFlash_model->get_log_data($id);
        echo json_encode($data);
    }

    public function user_log_update($id, $text, $action)
    {
        $data = array();
        $data['source_module'] = 'NewsFlash';
        $data['record_id'] = $id;
        $data['created_on'] = date("Y-m-d h:i:s");
        $data['created_by'] = $this->user;
        $data['action_taken'] = $action;
        $data['text'] = $text;

        $result = $this->NewsFlash_model->insert_data('user_log_history', $data);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    // added by millan on 07-05-2021
    public function add_newsletter_dls()
    {

        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
        $this->form_validation->set_rules('subject', 'News Letter Subject', 'required|is_unique[newsletter_content.subject]');
        if ($this->form_validation->run() == false) {
            $data = array(
                'errors' => $this->form_validation->error_array(),
                'status' => 0,
                'msg' => 'Please Fill all Required Fields'
            );
        } else {
            $checkUser = (array) $this->session->userdata('user_data');
            $fill_data = $this->input->post();
            $store_data = array();
            $store_data['title'] = $fill_data['title'];
            $store_data['subject'] = $fill_data['subject'];
            $store_data['content_desc'] = html_entity_decode($fill_data['content_desc']);
            $store_data['created_by'] = $checkUser['uidnr_admin'];
            if (!empty($fill_data)) {
                $image_upload = array();
                $this->NewsFlash_model->update_data('newsletter_content', array('newsletter_status' => 0), '1 = 1');
                $add_data = $this->NewsFlash_model->insert_data('newsletter_content', $store_data);
                if ($add_data && (!empty($_FILES['nsl_img']) && $_FILES['nsl_img'] != '')) {
                    $insert_id = $this->db->insert_id();
                    $image_upload = $this->upload_nl_img($_FILES['nsl_img']);
                    if ($image_upload && count($image_upload) > 0) {
                        $store_data = array();
                        $data_added = false;
                        foreach ($image_upload as $key => $value) {
                            $store_data[$key]['ns_image_path'] = $value;
                            $store_data[$key]['newsltr_cont_id'] = $insert_id;
                        }
                        if (count($store_data) > 0) {
                            $data_added = $this->NewsFlash_model->insert_multiple_data('newsletter_image', $store_data);
                        }
                        if ($data_added) {
                            $this->session->set_flashdata('success', 'NewsLetter Added Succesfully');
                            $data = array(
                                'status' => 1,
                                'msg' => 'NewsLetter Added Succesfully'
                            );
                        } else {
                            $this->session->set_flashdata('error', 'Error in Inserting Image');
                            $data = array(
                                'status' => 0,
                                'msg' => 'Error in Inserting Image'
                            );
                        }
                    }
                } else {
                    $this->session->set_flashdata('error', 'Error in Inserting Data');
                    $data = array(
                        'status' => 0,
                        'msg' => 'Error in Inserting Data'
                    );
                }
            } else {
                $this->session->set_flashdata('error', 'Data Not Found ');
                $data = array(
                    'status' => 0,
                    'msg' => 'Data Not Found'
                );
            }
        }
        echo json_encode($data);
    }

    // added by millan on 07-05-2021
    public function get_nsl_data()
    {
        $data = $this->NewsFlash_model->fetch_nsl_data();
        echo json_encode($data);
    }

    // added by millan on 07-05-2021
    public function mail_data_client()
    {
        set_time_limit(0);
        ini_set('memory_limit', '-1');
        $get_data = $this->input->post();
        unset($get_data['mail']);
        if (!empty($get_data) && $get_data != "") {
            $fetch_data = $this->NewsFlash_model->fetch_ns_data($get_data);
            $message = "";
            $img_src = "";
            if (!empty($fetch_data) && $fetch_data != "") {
                $subject = $fetch_data->subject;
                $body = $fetch_data->content_desc;
                $image = explode(',', $fetch_data->image_path);
                foreach ($image as $value) {
                    $img_src = $value;
                    $message .= "<img src='" . $img_src . "' alt='GEO CHEM NEWSLETTER IMAGE' width='700px'>";
                }
                $result = $this->NewsFlash_model->get_customers_list();
                if (count($result) > 0) {
                    $subject = "BASIL Divison Weekly Newsletter Dated: " . date("Y-m-d");
                    foreach ($result as $key => $value) {
                        foreach ($value as $key1 => $value1) {
                            $to_user = 'no-reply@basilrl.com';
                            $bcc_user = $value1['email'];
                            $customer_id = $value1['customer_id'];
                            $msg = '<pre style="text-align:justify; font-size:16px; font-family:Calibri, sans-serif; font-weight:bold"> Dear Sir/Madam <br> We have started our weekly newsletter, please click on the bell icon to subscribe.</pre>';
                            $msg .=  '<div> ' . $body . '  </div>';
                            $msg .=  '<div> ' . $message . '  </div>';
                            $msg .= '<br><a href="' . base_url('NewsFlash/customer_subs?customer_id=' . base64_encode($customer_id) . '&mst_branch_id=' . base64_encode($value1['mst_branch_id']) . '&db=' . base64_encode($key)) . '" style="border: 1px solid lightgreen;"><p style="font-size:18px; font-family:Arial, Times New Roman, Calibiri; text-decoration:underline;"> Please subscribe us by clicking the bell icon..  
                            <img src="https://s3.ap-south-1.amazonaws.com/demo-portal-app.log/ci_lims/image/bell-20-Apr-2892.png" title="Subscribe NewsLetter" width="80px"> </p></a> ';
                            $this->send_newsletter_to_client('developer.cps06@basilrl.com', 'developer.cps04@basilrl.com', $msg, $subject); die;
                            $result = $this->NewsFlash_model->customer_newsletter_update($customer_id, $key);
                        }
                    }
                } else {
                    $result = false;
                }
            }
        } else {
            $result = false;
        }

        if($result==false){
            $this->session->set_flashdata('error', 'ID Not Found');
            $data = array(
                'status' => 0,
                'msg' => 'Data Not Found'
            );
        }
        else{
            $this->session->set_flashdata('success', 'Mail Send Successfully');
            $data = array(
                'status' => 1,
                'msg' => 'Mail Send Successfully'
            );
        }
    }

    // added by millan on 08-05-2021
    public function send_newsletter_to_client($to, $bcc = NULL, $msg = NULL, $sub = NULL, $cc = NULL)
    {
        $CI = &get_instance();
        $CI->load->library('email');

        $message = '<html><body>';
        $message = '<table width="100%" border="0" cellspacing="5" cellpadding="5" style="border-collapse:collapse; font-family:Arial, Helvetica, sans-serif;font-size:12px;">';
        $message .= '<div><tr>
                <td><img src="https://basilrl.com/public/media/logo.png" height="70"/></td></tr></div>
                ';
        $message .= '<tr><td colspan=2><br/>' . $msg . '</td></tr>';
        $message .= '<br/>
                <br/>
                <tr><td>Thanks and Regards,</td></tr>
                <tr><td>Geo Chem CPS Division</td></tr>';
        $message .= '</body></html>';
        if (count($to) > 1)
            $to_user = Implode(',', $to); // convert email array to string
        else
            $to_user = $to;

        if (count($bcc) > 1)
            $bcc_user = Implode(',', $bcc); // convert email array to string
        else
            $bcc_user = $bcc;
        if (count($cc) > 1)
            $cc_user = Implode(',', $cc); // convert email array to string
        else
            $cc_user = $cc;
        $config['protocol'] = PROTOCOL;
        $config['smtp_host'] = HOST;
        $config['smtp_user'] = USER;
        $config['smtp_pass'] = PASS;
        $config['smtp_port'] = PORT;
        $config['newline'] = "\r\n";
        $config['smtp_crypto'] = CRYPTO;
        $config['charset'] = 'utf-8';
        $config['mailtype'] = 'html';
        $CI->email->initialize($config);
        $CI->email->from(FROM, 'BASIL');
        $CI->email->to($to_user);
        $CI->email->bcc($bcc_user);
        $CI->email->subject($sub);
        $CI->email->message($message);
        $CI->email->cc($cc_user);
        $bool = $CI->email->send();
        $this->email->clear(TRUE);
        if ($bool) {
            return true;
        } else {
            print_r($CI->email->print_debugger());
            show_error($CI->email->print_debugger());
        }
    }

    // added by millan on 08-05-2021
    public function customer_subs()
    {
        $data_get = $this->input->get();
        $store_data['cust_id'] = base64_decode($data_get['customer_id']);
        $store_data['branch_id'] = base64_decode($data_get['mst_branch_id']);
        $db_name = base64_decode($data_get['db']);
        $store_data['subscribed_status'] = 1;
        if (($store_data['cust_id'] > 0)) {
            $data_inserted = $this->NewsFlash_model->insert_cust_subs_db('newsletter_subscription', $store_data, $db_name);
            if ($data_inserted) {
                echo '<h1 style="text-align:center; color:green; "> Thank You For Subscribing Us. <img src="' . base_url('newsletter/icon/subscribe.png') . '" height="256px" alt="Success">  </h1>';
            } else {
                echo '<h1 style="text-align:center"> Error <img src="' . base_url('newsletter/icon/error.png') . '" height="256px" alt="Error">  </h1>';
            }
        } else {
            echo '<h1 style="text-align:center"> Something Went Wrong <img src="' . base_url('newsletter/icon/blue-screen.png') . '" height="256px" alt="Blue Screen">  </h1>';
        }
    }

    // added by millan on 08-05-2021
    public function send_newsletter_to_staff()
    {
        set_time_limit(0);
        ini_set('memory_limit', '-1');
        $get_data = $this->input->post();
        unset($get_data['mail']);
        if(!empty($get_data) && $get_data!="" ){
            $fetch_data = $this->NewsFlash_model->fetch_ns_data($get_data);
            $message = "";
            $img_src = "";
            if (!empty($fetch_data) && $fetch_data != "") {
                $subject = $fetch_data->subject;
                $body = $fetch_data->content_desc;
                $image = explode(',', $fetch_data->image_path);
                foreach ($image as $value) {
                    $img_src = $value;
                    $message .= "<img src='" . $img_src . "' alt='GEO CHEM NEWSLETTER IMAGE' width='700px'>";
                }
                $office_staff = array(                    
                    'admin1@basilrl.com'
                );
                $office_staff = array_chunk($office_staff, 30);
                foreach ($office_staff as $key => $value) {
                    $cc_user = 'manish.k@basilrl.com';
                    $msg = '<pre style="text-align:justify; font-size:16px;"> Dear Sir/Madam <br> We have started our weekly newsletter.</pre>';
                    $msg .=  '<div> <img src=" ' . $img_src . '" alt="GEO CHEM NEWSLETTER IMAGE" >  </div>';
                    // $this->send_newsletter_to_client($value, NULL, $msg, $subject, $cc_user);
                    $this->send_newsletter_to_client('developer.cps06@basilrl.com', NULL, $msg, $subject, 'shankar.k@basilrl.com'); die;
                }
            } else{
                $result = false;
            }
        }else {
            $result = false;
        }

        if($result==false){
            $this->session->set_flashdata('error', 'ID Not Found');
            $data = array(
                'status' => 0,
                'msg' => 'Data Not Found'
            );
        }
        else{
            $this->session->set_flashdata('success', 'Mail Send Successfully to Staff Members');
            $data = array(
                'status' => 1,
                'msg' => 'Mail Send Successfully to Staff Members'
            );
        }
    }

    private function _upload_image($file) {
       $aws_data =  $this->multiple_upload_image($file);
       return !empty($aws_data['aws_path']) ? $aws_data['aws_path'] :'';
         
    }

}
