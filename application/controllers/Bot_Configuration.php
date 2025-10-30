<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Bot_Configuration extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('Bot_Configuration_Model', 'BCM');
        $this->load->helper('url');
    }

    public function index()
    {
        $this->load_view('bot_configuration/bot_configuration', null);
    }

    function fetch_records()
    {
        $fetch_data = $this->BCM->fetch_records($this->input->post());
        $data = array();
        $sl = $this->input->post('start') + 1;
        foreach ($fetch_data as $key => $row) {
            $status = ($row->status == 1) ? 'checked' : '';
            $title = ($row->status == 1) ? 'Active' : 'In-Active';
            $btnStatus = $btnUpdate = $btnDelete = $btnLog = '';
            if (exist_val("Bot_Configuration/bot_configuration_status", $this->session->userdata("permission"))) {
                $btnStatus = '<div class="custom-control custom-switch">
                    <input type="checkbox" class="custom-control-input bot_configuration_status" id="bot_configuration_status_' . $row->id . '" ' . $status . '>
                    <label class="custom-control-label" title="' . $title . '" id="bot_configuration_status_title_' . $row->id . '" for="bot_configuration_status_' . $row->id . '"></label>
                </div>';
            }
            if (exist_val("Bot_Configuration/bot_configuration_add_edit", $this->session->userdata("permission"))) {
                $btnUpdate = '<button type="button" data-id="' . $row->id . '" class="btn btn-primary btn-sm bot_configuration_edit" title="Update"><i class="fa fa-edit"></i></button>';
            }
            if (exist_val("Bot_Configuration/bot_configuration_delete", $this->session->userdata("permission"))) {
                $btnDelete = '<button type="button" data-id="' . $row->id . '" class="btn btn-danger btn-sm bot_configuration_delete ml-2" title="Delete"><i class="fa fa-trash"></i></button>';
            }
            if (exist_val("Bot_Configuration/bot_configuration_log", $this->session->userdata("permission"))) {
                $btnLog = '<button type="button" data-id="' . $row->id . '" class="btn btn-info btn-sm bot_configuration_log ml-2" title="Logs"><i class="fa fa-bolt"></i></button>';
            }
            $sub_array = array();
            $sub_array[] = $sl;
            $url = base_url() . 'Bot_Configuration/add_faq?id=' . base64_encode($row->id);
            $sub_array[] = '<a href="' . $url . '">' . $row->intents . '</a> <span title="Total ' . $row->question . ' Questions" class="badge badge-pill badge-info">' . $row->question . '</span> <span title="Total ' . $row->answers . ' Answers" class="badge badge-pill badge-success">' . $row->answers . '</span>';
            $sub_array[] = $row->user_name;
            $sub_array[] = $row->date_time;
            $sub_array[] = $btnStatus;
            $sub_array[] =  $btnUpdate . $btnDelete . $btnLog;
            $data[] = $sub_array;
            $sl++;
        }

        echo json_encode([
            "draw"              => intval($this->input->post('draw')),
            "recordsTotal"      => $this->BCM->get_all_data('rasa_faq_intents'),
            "recordsFiltered"   => $this->BCM->get_filtered_data($this->input->post()),
            "data"              => $data
        ]);
    }

    public function fetch_edit_bot_configuration()
    {
        echo json_encode($this->BCM->get_row('*', 'rasa_faq_intents', ['id' => $this->input->post('id')]));
    }

    public function bot_configuration_status()
    {
        $result = $this->BCM->update_data('rasa_faq_intents', ['status' => $this->input->post('status')], ['id' => $this->input->post('id')]);
        $this->BCM->update_data('rasa_faq_questions', ['status' => $this->input->post('status')], ['intents_id' => $this->input->post('id')]);
        $this->BCM->update_data('rasa_faq_answers', ['status' => $this->input->post('status')], ['intents_id' => $this->input->post('id')]);
        if ($result) {
            $act = ($this->input->post('status') == 1) ? 'Active' : 'In-Active';
            $log_details = array(
                'source_module'     => 'Bot_Configuration',
                'operation'         => 'bot_configuration_status',
                'admin_id'          => $this->session->userdata('user_data')->uidnr_admin,
                'record_id'         => $this->input->post('id'),
                'action_message'    => 'Status changed to ' . $act
            );
            $this->BCM->insert_data('rasa_faq_log', $log_details);

            $response = array(
                'message'   => 'Status has been changed.',
                'code'      => 1
            );
        } else {
            $response = array(
                'message'   => 'Something went wrong.',
                'code'      => 0
            );
        }
        echo json_encode($response);
    }

    public function bot_configuration_delete()
    {
        $result = $this->BCM->update_data('rasa_faq_intents', ['is_deleted' => 1], ['id' => $this->input->post('id')]);
        $this->BCM->update_data('rasa_faq_questions', ['is_deleted' => 1], ['intents_id' => $this->input->post('id')]);
        $this->BCM->update_data('rasa_faq_answers', ['is_deleted' => 1], ['intents_id' => $this->input->post('id')]);
        if ($result) {
            $log_details = array(
                'source_module'     => 'Bot_Configuration',
                'operation'         => 'bot_configuration_delete',
                'admin_id'          => $this->session->userdata('user_data')->uidnr_admin,
                'record_id'         => $this->input->post('id'),
                'action_message'    => 'Deleted bot configuration.'
            );
            $this->BCM->insert_data('rasa_faq_log', $log_details);

            $response = array(
                'message'   => 'Record has been deleted.',
                'code'      => 1
            );
        } else {
            $response = array(
                'message'   => 'Something went wrong.',
                'code'      => 0
            );
        }
        echo json_encode($response);
    }

    public function bot_configuration_log()
    {
        echo json_encode($this->BCM->bot_configuration_log($this->input->post('record_id')));
    }

    public function bot_configuration_add_edit()
    {
        $this->form_validation->set_rules('intents', 'Intent Name', 'trim|required|min_length[5]|max_length[50]|regex_match[/^[a-z_]+$/]|is_unique[rasa_faq_intents.intents]');
        $this->form_validation->set_error_delimiters('', '');

        if ($this->form_validation->run() == FALSE) {
            foreach ($this->input->post() as $key => $value) {
                $response['error'][$key] = form_error($key);
            }
        } else {
            $id = $this->input->post('id');
            $response = array(
                'message'   => 'Something went wrong.',
                'code'      => 0
            );

            $data['intents'] = trim($this->input->post('intents'), '_');

            if (empty($id)) {
                $data['is_deleted'] = 0;
                $data['status'] = 1;
                $data['created_on'] = date("Y-m-d h:i:s");
                $data['created_by'] = $this->session->userdata('user_data')->uidnr_admin;
                $result = $this->BCM->insert_data('rasa_faq_intents', $data);
                if ($result) {
                    $log_details = array(
                        'source_module'     => 'Bot_Configuration',
                        'operation'         => 'bot_configuration_add_edit',
                        'admin_id'          => $this->session->userdata('user_data')->uidnr_admin,
                        'record_id'         => $result,
                        'action_message'    => 'Added new Intents.'
                    );
                    $this->BCM->insert_data('rasa_faq_log', $log_details);

                    $response = array(
                        'message'   => 'Record has been inserted.',
                        'code'      => 1
                    );
                }
            } else {
                $data['updated_on'] = date("Y-m-d h:i:s");
                $data['updated_by'] = $this->session->userdata('user_data')->uidnr_admin;
                $result = $this->BCM->update_data('rasa_faq_intents', $data, ['id' => $id]);
                if ($result) {

                    $log_details = array(
                        'source_module'     => 'Bot_Configuration',
                        'operation'         => 'bot_configuration_add_edit',
                        'section'           => 'Intents',
                        'admin_id'          => $this->session->userdata('user_data')->uidnr_admin,
                        'record_id'         => $id,
                        'action_message'    => 'Updated Intents.'
                    );
                    $this->BCM->insert_data('rasa_faq_log', $log_details);

                    $response = array(
                        'message'   => 'Record has been updated.',
                        'code'      => 1
                    );
                }
            }
        }
        echo json_encode($response);
    }

    // ========================================== QUESTIONS/ANSWERS =====================================
    public function add_faq()
    {
        $id = base64_decode($this->input->get('id'));
        if (!empty($id)) {
            $faq['intents'] = $this->BCM->get_row('id, intents', 'rasa_faq_intents', ['id' => $id]);
            $faq['questions'] = $this->BCM->fetch_faq_questions($id);
            $faq['answers'] = $this->BCM->fetch_faq_answers($id);
            $this->load_view('bot_configuration/add_faq', $faq);
        } else {
            redirect('Bot_Configuration');
        }
    }

    public function save_faq()
    {
        $post = $this->input->post();
        // Questions section....
        for ($i = 0; $i < count($post['questions_id']); $i++) {

            $quest = trim(str_replace(':', '', $post['questions'][$i]));
            if (!empty($quest)) {
                if (empty($post['questions_id'][$i])) {
                    $question1 = array(
                        'is_deleted'    => 0,
                        'status'        => 1,
                        'intents_id'    => $post['intents_id'],
                        'questions'     => $quest,
                        'created_by'    => $this->session->userdata('user_data')->uidnr_admin,
                        'created_on'    => date("Y-m-d h:i:s")
                    );
                    $quest_is = $this->BCM->insert_data('rasa_faq_questions', $question1);
                    if ($quest_is) {
                        $log_details1 = array(
                            'source_module'     => 'Bot_Configuration',
                            'operation'         => 'save_faq',
                            'admin_id'          => $this->session->userdata('user_data')->uidnr_admin,
                            'record_id'         => $post['intents_id'],
                            'action_message'    => 'Questions added ID: ' . $quest_is
                        );
                        $this->BCM->insert_data('rasa_faq_log', $log_details1);
                    }
                } else {
                    $question2 = array(
                        'questions'     => $quest,
                        'updated_by'    => $this->session->userdata('user_data')->uidnr_admin,
                        'updated_on'    => date("Y-m-d h:i:s")
                    );
                    $result1 = $this->BCM->update_data('rasa_faq_questions', $question2, ['id' => $post['questions_id'][$i]]);
                    /*if ($result1) {
                    $log_details2 = array(
                        'source_module'     => 'Bot_Configuration',
                        'operation'         => 'save_faq',
                        'admin_id'          => $this->session->userdata('user_data')->uidnr_admin,
                        'record_id'         => $post['intents_id'],
                        'action_message'    => 'Questions updated ID: ' . $post['questions_id'][$i]
                    );
                    $this->BCM->insert_data('rasa_faq_log', $log_details2);
                }*/
                }
            }
        }
        // Anserwers section....
        for ($i = 0; $i < count($post['answers_id']); $i++) {

            //$answ = trim(str_replace(':', '', $post['answers'][$i]));
            $answ = trim($post['answers'][$i]);

            if (!empty($answ)) {
                if (empty($post['answers_id'][$i])) {
                    $answer1 = array(
                        'is_deleted'    => 0,
                        'status'        => 1,
                        'intents_id'    => $post['intents_id'],
                        'answers'       => $answ,
                        'created_by'    => $this->session->userdata('user_data')->uidnr_admin,
                        'created_on'    => date("Y-m-d h:i:s")
                    );
                    $ans_id = $this->BCM->insert_data('rasa_faq_answers', $answer1);
                    if ($ans_id) {
                        $log_details3 = array(
                            'source_module'     => 'Bot_Configuration',
                            'operation'         => 'save_faq',
                            'admin_id'          => $this->session->userdata('user_data')->uidnr_admin,
                            'record_id'         => $post['intents_id'],
                            'action_message'    => 'Answers added ID: ' . $ans_id
                        );
                        $this->BCM->insert_data('rasa_faq_log', $log_details3);
                    }
                } else {
                    $answer2 = array(
                        'answers'       => $answ,
                        'updated_by'    => $this->session->userdata('user_data')->uidnr_admin,
                        'updated_on'    => date("Y-m-d h:i:s")
                    );
                    $result2 = $this->BCM->update_data('rasa_faq_answers', $answer2, ['id' => $post['answers_id'][$i]]);
                    /*if ($result2) {
                    $log_details4 = array(
                        'source_module'     => 'Bot_Configuration',
                        'operation'         => 'save_faq',
                        'admin_id'          => $this->session->userdata('user_data')->uidnr_admin,
                        'record_id'         => $post['intents_id'],
                        'action_message'    => 'Answers updated ID: ' . $post['answers_id'][$i]
                    );
                    $this->BCM->insert_data('rasa_faq_log', $log_details4);
                }*/
                }
            }
        }
        $response = array(
            'message'   => 'Record has been saved!!',
            'code'      => 1
        );
        echo json_encode($response);
    }

    public function delete_questions_answers()
    {
        $table = ($this->input->post('action') == 'questions') ? 'rasa_faq_questions' : 'rasa_faq_answers';
        $result = $this->BCM->update_data($table, ['is_deleted' => 1, 'updated_by' => $this->session->userdata('user_data')->uidnr_admin], ['id' => $this->input->post('id')]);
        if ($result) {
            $log_details = array(
                'source_module'     => 'Bot_Configuration',
                'operation'         => 'delete_questions_answers',
                'admin_id'          => $this->session->userdata('user_data')->uidnr_admin,
                'record_id'         => $this->input->post('intent_id'),
                'action_message'    => 'Deleted ' . $this->input->post('action') . ', ID: ' . $this->input->post('id')
            );
            $this->BCM->insert_data('rasa_faq_log', $log_details);

            $response = array(
                'message'   => 'Record has been deleted.',
                'code'      => 1
            );
        } else {
            $response = array(
                'message'   => 'Something went wrong.',
                'code'      => 0
            );
        }
        echo json_encode($response);
    }

    // ========================================== ALL CONVERSATIONS =====================================
    public function conversation()
    {
        $this->load_view('bot_configuration/all_conversation', null);
    }

    function fetch_conversation()
    {
        $fetch_data = $this->BCM->fetch_conversation($this->input->post());
        $data = array();
        $sl = $this->input->post('start') + 1;
        foreach ($fetch_data as $key => $row) {
            $btnDetails = '';
            if (exist_val("Bot_Configuration/conversation_details", $this->session->userdata("permission"))) {
                $btnDetails = '<button type="button" data-id="' . $row->sender_id . '" class="btn btn-primary btn-sm conversation_details" title="Details"><i class="fa fa-eye"></i></button>';
            }
            $sub_array = array();
            $sub_array[] = $sl;
            $sub_array[] = $row->sender_id;
            $sub_array[] = $btnDetails;
            $data[] = $sub_array;
            $sl++;
        }
        echo json_encode([
            "draw"              => intval($this->input->post('draw')),
            "recordsTotal"      => $this->BCM->get_all_data('events'),
            "recordsFiltered"   => $this->BCM->get_filtered_data_conversation($this->input->post()),
            "data"              => $data
        ]);
    }

    // ============================ CONVERSATION DETAILS =====================================

    public function conversation_details()
    {
        $post = $this->security->xss_clean($this->input->post());
        if (!empty($post)) {
            $fetch_data = $this->BCM->conversation_details($post);
            // $fetch_data = $this->BCM->fetch_logs($post, 'Brand');
            $data = array();
            $slno = $post['start'] + 1;
            foreach ($fetch_data as $key => $val) {
                $message = json_decode($val->data);
                $user = ($val->type_name == 'bot') ? '<span class="badge badge-pill badge-danger" title="BOT"><i class="fa fa-user-secret" aria-hidden="true"></i></span>' : '<span class="badge badge-pill badge-primary" title="USER"><i class="fa fa-user" aria-hidden="true"></i></span>';
                $sub_array = array();
                $sub_array[] = $slno;
                $sub_array[] = $user;
                $sub_array[] = $message->text;
                $sub_array[] = date('d/m/Y H:i:s A', $message->timestamp);
                $data[] = $sub_array;
                $slno++;
            }
            echo json_encode([
                "draw"              => intval($this->security->xss_clean($this->input->post('draw'))),
                "recordsTotal"      => $this->BCM->get_all_conversation_data($post['sender_id']),
                "recordsFiltered"   => $this->BCM->get_filtered_conversation($post),
                "data"              => $data
            ]);
        }

        /*
        $data = $this->BCM->conversation_details($this->input->post('sender_id'));
        $html = '<table class="table table-sm table-striped" id="conversations-table">
                        <thead>
                            <tr class="bg-primary text-light">
                                <th>SL</th>
                                <th>NAME</th>
                                <th>MESSAGE</th>
                                <th>TIMING</th>
                                <th>INTENT</th>
                                <th>ACTION</th>
                            </tr>
                        </thead>
                        <tbody>';
        if (!empty($data)) {
            $i = 1;
            foreach ($data as $key => $val) {
                $message = json_decode($val->data);
                $html .= '<tr><td>' . $i . '</td>';
                $html .= '<td>' . $val->type_name . '</td>';
                $html .= '<td>' . $message->text . '</td>';
                $html .= '<td>' . date('d/m/Y H:i:s', $message->timestamp) . '</td>';
                $html .= '<td>' . $val->intent_name . '</td>';
                $html .= '<td>' . $val->action_name . '</td></tr>';
                $i++;
            }
        }
        $html .= '</tbody></table>';
        $html .= '<script>$("#conversations-table").dataTable();</script>';
        echo $html;
        */
    }

    // ========================================== TRAINING PART =========================================
    /*
    public function training()
    {
        if ($this->input->post('action') == 'auth') {

            set_time_limit(0);
            ini_set("memory_limit", -1);

            $this->load->helper('file');

            $intents = $this->BCM->fetch_faq_intents();
            if (!empty($intents)) {

                // =========================== NLU | FAQ ===============================

                $file = FCPATH . 'rasa_cps_2.8.3/data/nlu/faq.yml';
                $faqFile = fopen($file, "w") or die("Unable to open file!");

                fwrite($faqFile, "version: \"2.0\"\n\n");

                fwrite($faqFile, "nlu:\n\n");

                foreach ($intents as $key => $val) {
                    if (!empty($val->id) && !empty($val->intents)) {
                        fwrite($faqFile, "- intent: faq/" . $val->intents);
                        fwrite($faqFile, "\n  examples: |\n");
                        $questions = $this->BCM->fetch_faq_questions($val->id);
                        foreach ($questions as $k => $v) {
                            if (!empty($v) && !empty($v->questions)) {
                                fwrite($faqFile, "    - " . $v->questions . "\n");
                            }
                        }
                        fwrite($faqFile, "\n");
                    }
                }
                fclose($faqFile);

                // ============================== DOMAIN =============================

                $file2 = FCPATH . 'rasa_cps_2.8.3/domain.yml';
                $domainFile = fopen($file2, "w") or die("Unable to open file!");

                fwrite($domainFile, "version: \"2.0\"\n\n");

                fwrite($domainFile, "session_config:");
                fwrite($domainFile, "\n  session_expiration_time: 60");
                fwrite($domainFile, "\n  carry_over_slots_to_new_session: true");

                fwrite($domainFile, "\n\nintents:");
                fwrite($domainFile, "\n  - greet");
                fwrite($domainFile, "\n  - goodbye");
                fwrite($domainFile, "\n  - affirm");
                fwrite($domainFile, "\n  - deny");
                fwrite($domainFile, "\n  - outofscope");
                fwrite($domainFile, "\n  - faq");
                fwrite($domainFile, "\n  - chitchat");

                fwrite($domainFile, "\n\nslots:");
                fwrite($domainFile, "\n  isAuth:");
                fwrite($domainFile, "\n    type: bool");
                fwrite($domainFile, "\n    influence_conversation: false");
                fwrite($domainFile, "\n  clientName:");
                fwrite($domainFile, "\n    type: text");
                fwrite($domainFile, "\n    influence_conversation: false");
                fwrite($domainFile, "\n  clientOrganization:");
                fwrite($domainFile, "\n    type: text");
                fwrite($domainFile, "\n    influence_conversation: false");
                fwrite($domainFile, "\n  clientEmail:");
                fwrite($domainFile, "\n    type: text");
                fwrite($domainFile, "\n    influence_conversation: false");
                fwrite($domainFile, "\n  clientPhone:");
                fwrite($domainFile, "\n    type: text");
                fwrite($domainFile, "\n    influence_conversation: false");
                fwrite($domainFile, "\n  isName:");
                fwrite($domainFile, "\n    type: bool");
                fwrite($domainFile, "\n    influence_conversation: false");
                fwrite($domainFile, "\n  isOrganization:");
                fwrite($domainFile, "\n    type: bool");
                fwrite($domainFile, "\n    influence_conversation: false");
                fwrite($domainFile, "\n  isEmail:");
                fwrite($domainFile, "\n    type: bool");
                fwrite($domainFile, "\n    influence_conversation: false");
                fwrite($domainFile, "\n  isPhone:");
                fwrite($domainFile, "\n    type: bool");
                fwrite($domainFile, "\n    influence_conversation: false");

                fwrite($domainFile, "\n\nactions:");
                fwrite($domainFile, "\n  - validate_clientAuthForm");
                fwrite($domainFile, "\n  - action_ask_clientName");
                fwrite($domainFile, "\n  - action_ask_clientOrganization");
                fwrite($domainFile, "\n  - action_ask_clientEmail");
                fwrite($domainFile, "\n  - action_ask_clientPhone");
                fwrite($domainFile, "\n  - action_clientAuthFormSubmit");

                fwrite($domainFile, "\n\nforms:");
                fwrite($domainFile, "\n  clientAuthForm:");
                fwrite($domainFile, "\n    required_slots:");
                fwrite($domainFile, "\n      clientName:");
                fwrite($domainFile, "\n      - type: from_text");
                fwrite($domainFile, "\n      clientOrganization:");
                fwrite($domainFile, "\n      - type: from_text");
                fwrite($domainFile, "\n      clientEmail:");
                fwrite($domainFile, "\n      - type: from_text");
                fwrite($domainFile, "\n      clientPhone:");
                fwrite($domainFile, "\n      - type: from_text");

                fwrite($domainFile, "\n\nresponses:");
                fwrite($domainFile, "\n  utter_goodbye:");
                fwrite($domainFile, "\n  - text: Bye");

                fwrite($domainFile, "\n\n  utter_chitchat/mood_unhappy:");
                fwrite($domainFile, "\n  - text: Here is something to cheer you up");

                fwrite($domainFile, "\n\n  utter_chitchat/mood_great:");
                fwrite($domainFile, "\n  - text: Great, carry on!");

                fwrite($domainFile, "\n\n  utter_outofscope:");
                fwrite($domainFile, "\n  - text: Sorry, I wasn't able to understand. Could you please rephrase it?");

                foreach ($intents as $key => $val) {
                    if (!empty($val->id) && !empty($val->intents)) {
                        fwrite($domainFile, "\n\n  utter_faq/" . $val->intents . ":");
                        $answers = $this->BCM->fetch_faq_answers($val->id);
                        foreach ($answers as $k => $v) {
                            if (!empty($v) && !empty($v->answers)) {
                                fwrite($domainFile, "\n  - text: " . '"' . $v->answers . '"');
                            }
                        }
                    }
                }
                fclose($domainFile);

                $response = array(
                    'message'   => 'YML files has been generated!!',
                    'code'      => 1
                );
            } else {
                $response = array(
                    'message'   => 'Something went wrong!',
                    'code'      => 0
                );
            }
            echo json_encode($response);
        }
    }
    */

    // =============================== MAIL OUT_OF_SCOPE QUESTIONS ==================================
    /*
    public function send_email_attachment($pdf)
    {
        ini_set('memory_limit', '1024M');

        require_once(APPPATH . 'third_party/PHPMailer/PHPMailerAutoload.php');

        $mail = new PHPMailer(true);

        try {
            //Server settings
            $mail->SMTPDebug = false;
            $mail->isSMTP();
            $mail->Host       = HOST;
            $mail->SMTPAuth   = true;
            $mail->Username   = USER;
            $mail->Password   = PASS;
            $mail->SMTPSecure = 'tls';
            $mail->Port       = PORT;
            //Recipients
            $mail->setFrom('no-reply@basilrl.com', 'BASIL');
            $mail->addAddress('developer.cps04@basilrl.com');
            $mail->addCC('developer.cps04@basilrl.com');

            $mail->addStringAttachment($pdf, "Questions-" . date('d-m-Y') . ".pdf");

            // Content
            $mail->isHTML(true);
            $mail->Subject = 'Un-answered question list - ' . date('d-m-Y');
            $message = '<p>Dear Sir,</p>';
            $message .= '<p>Kindly find details of un -answered questions as bellow</p>';
            $message .= '<br/><br/>';
            $message .= '<p>Regards,</p>';
            $message .= '<p>Basil Team</p>';
            $mail->Body    = $message;

            $mail->send();
            return 'success';
        } catch (Exception $e) {
            return "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }

    public function mail_outofscope()
    {
        set_time_limit(0);
        ini_set("memory_limit", -1);

        $this->db->truncate('rasa_temp_questions');

        $fetch_data = $this->BCM->get_result('id, sender_id, data', 'events', ['type_name' => 'user', 'intent_name' => 'outofscope', 'is_selected' => 0]);
        foreach ($fetch_data as $key => $val) {
            $data = json_decode($val->data);
            if (isset($data->text) && str_word_count($data->text) > 2 && !filter_var($data->text, FILTER_VALIDATE_EMAIL)) {
                $quest = array(
                    'sender_id'     => $fetch_data[$key]->sender_id,
                    'questions'     => $data->text
                );
                $save = $this->BCM->insert_data('rasa_temp_questions', $quest);
                if ($save) {
                    $this->BCM->update_data('events', ['is_selected' => 1], ['id' => $fetch_data[$key]->id]);
                }
            }
        }

        $fetch_quest = $this->BCM->get_result('id, sender_id, questions', 'rasa_temp_questions', NULL);

        if (!empty($fetch_quest)) {

            $html = '<table><tr><th>SL</th><th>QUESTIONS</th></tr><tbody>';
            $slno = 1;
            foreach ($fetch_quest as $key => $val) {
                $html .= '<tr><td>' . $slno . '</td><td>' . $val->questions . '</td></tr>';
                $slno++;
            }
            $html .= '</tbody></table>';

            ob_start();
            $this->load->library('M_pdf');
            $this->m_pdf->pdf->charset_in = 'UTF-8';
            $this->m_pdf->pdf->setAutoTopMargin = 'stretch';
            $this->m_pdf->pdf->lang = 'ar';
            $this->m_pdf->pdf->autoLangToFont = true;
            $this->m_pdf->pdf->autoPageBreak = false;
            $this->m_pdf->pdf->WriteHTML($html);
            ob_end_clean();
            $content = $this->m_pdf->pdf->Output('', 'S');

            $this->send_email_attachment($content);
        }
    }
    */
}
