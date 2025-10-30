<?php

use setasign\Fpdi\PdfParser\Filter\Flate;

defined('BASEPATH') or exit('No direct script access allowed');

class Login extends CI_Controller
{

    public function __construct(Type $var = null)
    {
        parent::__construct();
        $this->load->model('user_model');
    }

    public function index()
    {

        $checkUser = $this->session->userdata('user_data');
        if (!empty($checkUser)) {
            redirect('Dashboard');
        } else {
            $this->form_validation->set_rules('email', 'Email', 'required');
            $this->form_validation->set_rules('password', 'Password', 'required');
            $this->form_validation->set_rules('captcha', 'captcha', 'trim|required|callback_captcha_word'); // added by millan

            if ($this->form_validation->run() == FALSE) {
                $data['image'] = $this->captcha();        // added by millan
                $this->load->view('login', $data);
            } else {

                $post = $this->input->post();
                if ($post['captcha']) {                 // added by millan
                    unset($post['captcha']);
                }
                $clean = $this->security->xss_clean($post);

                $userInfo = $this->user_model->checkLogin($clean);
                // AJIT 
                $user_id = $this->user_model->get_row('uidnr_admin,admin_email,admin_active', 'admin_users', ['admin_username' => $post['email']]);


                if ($user_id && count($user_id) > 0) {
                    if ($user_id->admin_active) {
                        if (!$userInfo) {

                            $user_counts = $this->user_model->get_row("COUNT(*) as user_count", "login_user_history", ["uidnr_admin" => $user_id->uidnr_admin, "status" => '0', 'date(created_on)' => date("Y-m-d"), 'attempts_type' => '0']);

                            if ($user_counts->user_count) {

                                if ($user_counts->user_count > 2) {

                                    $inactive['admin_active'] = '0';
                                    $block = $this->user_model->update_data('admin_users', $inactive, ['uidnr_admin' => $user_id->uidnr_admin]);
                                    if ($block) {

                                        $this->session->set_flashdata('flash_message', 'you are blocked due to more times incorrect login');

                                        $msg = 'Dear ' . $post['email'] . '<br>';
                                        $msg .= 'you have attempted 3 times with wrong password</br>';
                                        $msg .= 'and hence your password is blocked</br>';
                                        $msg .= 'provide us reason of it and contact admin to reset your password</br></br>';
                                        $msg .= 'List of unsuccessful attempts with time</br></br>';
                                        $msg .= '<table border="1">';
                                        $msg .= '<tr><td><b>SL No.<b></td><td><b>Date time<b></td></tr>';

                                        $list =  $this->user_model->get_result("(created_on)", "login_user_history", ["uidnr_admin" => $user_id->uidnr_admin, "status" => '0', 'date(created_on)' => date("Y-m-d"), 'attempts_type' => '0']);

                                        if ($list && count($list) > 0) {
                                            foreach ($list as $key => $value) {
                                                if ($key < 3) {
                                                    $msg .=  '<tr><td>(' . ($key + 1) . ')</td><td>' . $value->created_on . '</td><tr>';
                                                }
                                            }
                                        }

                                        $msg .=  '</table>';
                                        send_mail_function($user_id->admin_email, NULL, CC, $msg, 'USER BLOCKED!', NULL, NULL, false);

                                        $this->session->unset_userdata('timeout_list');
                                    } else {
                                        $this->session->set_flashdata('flash_message', 'something went wrong!');
                                    }
                                } else {
                                    $this->session->set_flashdata('flash_message', 'The login was unsuccessful');
                                    $this->session->set_userdata('timeout', $this->session->userdata('timeout') + 1);
                                    $time = $this->session->userdata('timeout_list');
                                    $time[$this->session->userdata('timeout')] = date('d-m-Y : h:i:sa');
                                    $this->session->set_userdata('timeout_list', $time);
                                    $this->user_model->insert_data('login_user_history', ['uidnr_admin' => $user_id->uidnr_admin, 'attempts_type' => '0', 'status' => '0']);
                                }
                            } else {
                                $this->session->set_flashdata('flash_message', 'The login was unsuccessful');
                                $this->session->set_userdata('timeout', 1);
                                $time[1] = date('d-m-Y : h:i:sa');
                                $this->session->set_userdata('timeout_list', $time);
                                $this->user_model->insert_data('login_user_history', ['uidnr_admin' => $user_id->uidnr_admin, 'attempts_type' => '0', 'status' => '0']);
                            }
                            redirect('login');
                            // AJIT END

                        } else {
                            $this->session->set_flashdata('success', 'Login Successfully');
                            $this->user_model->insert_data('login_user_history', ['uidnr_admin' => $user_id->uidnr_admin, 'attempts_type' => '1', 'status' => '1']);
                            $this->session->set_flashdata('saved_timezone', '1');
                            $this->session->set_userdata('user_data', $userInfo);
                            $this->session->unset_userdata('captcha'); // added by millan
                            $this->session->unset_userdata('timeout');
                            $this->session->unset_userdata('timeout_list');
                            redirect('Dashboard');
                        }
                    } else {
                        $this->session->set_flashdata('flash_message', 'YOU ARE BLOCKED DUE TO MORE WRONG ATTEMPTS CHECK YOUR EMAIL!');
                        $data['image'] = $this->captcha();
                        $this->load->view('login', $data);
                    }
                } else {
                    $this->session->set_flashdata('flash_message', 'username is incorrect!');
                    $data['image'] = $this->captcha();
                    $this->load->view('login', $data);
                }
            }
        }
    }

    public function captcha_word($word)
    {
        $word_session = $this->session->userdata('captcha');
        if ($word_session === $word) {
            return TRUE;
        } else {
            $this->form_validation->set_message('captcha_word', 'The {field} field can not be Match');
            return FALSE;
        }
    }

    public function captcha()
    {
        $this->load->helper('captcha');
        $filePath = base_url() . 'assets/uploads/captcha/';
        $files = glob(LOCAL_PATH . 'captcha/*'); // get all file names
        foreach ($files as $file) { // iterate files
            if (is_file($file))
                unlink($file); // delete file
        }
        $vals = array(
            'img_path'      => './assets/uploads/captcha/',
            'img_url'       => $filePath,
            'img_width'     => '200',
            'img_height'    => 30,
            'expiration'    => 7200,
            'word_length'   => 4,
            'font_size'     => 42,
            'pool'          => '123456789ABCDEF',
            'colors'        => array(
                'background' => array(255, 255, 255), 'border' => array(155, 155, 155), 'text' => array(0, 0, 0),
                'grid' => array(255, 200, 40)
            )
        );

        $cap = create_captcha($vals);
        $this->session->set_userdata('captcha', $cap['word']);

        if ($this->input->get()) {
            echo $cap['image'];   # code...
        } else {
            return $cap['image'];
        }
    }

    public function logout()
    {
        $this->session->unset_userdata('user_data');
        $this->session->sess_destroy();
        $this->session->set_flashdata('success', 'Logout Successfully');
        redirect('Login');
    }

    public function save_time_zone()
    {
        $post = $this->input->post();
        $timezone_offset_minutes = $post['time'];  // $_GET['timezone_offset_minutes']
        // Convert minutes to seconds
        $timezone_name = timezone_name_from_abbr("", $timezone_offset_minutes * 60, false);
        $this->session->set_userdata('timezone', $timezone_name);
    }
}
