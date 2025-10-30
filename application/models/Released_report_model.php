<?php 

defined('BASEPATH') OR exit('No direct script access allowed');

class Released_report_model extends MY_Model{

    function __construct(){
        parent::__construct();
    }

    public function get_report_list($limit = NULL, $start = NULL,$where,$search,$count = null)
    {
        $this->db->limit($limit, $start);
        if (count($where)>0) {
            $this->db->group_start();
            $this->db->where($where);
            $this->db->group_end();
        }
        if ($search) {
            $this->db->group_start();
            $this->db->like('LOWER(sr.gc_no)',strtolower($search));
            $this->db->or_like('LOWER(tr.trf_ref_no)',strtolower($search));
            $this->db->group_end();
        }
      //  $where_in = ("(gr.status='Report Approved' OR gr.status = 'Report Generated') AND (sr.status='Report Approved' OR sr.status = 'Report Generated')");
        // $this->db->limit($limit, $start);
        $where_in = ("(gr.manual_report_file !='') OR (sr.released_to_client = '1')" );
        $this->db->order_by('gr.report_id', 'DESC');
        $this->db->group_start();
        $this->db->where($where_in);
        $this->db->group_end();
        // $this->db->where('st.status <>', 'Completed');
        $this->db->select("sr.ulr_no,sr.sample_desc,sr.gc_no,sr.sample_reg_id as sample_reg_id,
	   CASE WHEN tr.trf_service_type ='Regular' AND  ( tr.service_days IS NULL OR service_days='') THEN CONCAT(tr.trf_service_type,' 3 Days')
       WHEN tr.trf_service_type ='Express' THEN CONCAT(tr.trf_service_type,' 2 Days')
       WHEN tr.trf_service_type ='Urgent'  THEN CONCAT(tr.trf_service_type,' 1 Days')
	   WHEN tr.service_days IS NOT NULL OR tr.service_days!='' THEN CONCAT(tr.trf_service_type,' ',tr.service_days,'Days') END AS sample_service_type,
	   cc.customer_name as client,tr.trf_ref_no,mst.sample_type_name as product_name,sr.received_date,sr.status,
	   due_date,sr.qty_received,gr.report_id,gr.manual_report_file,gr.report_num,gr.manual_report_worksheet,gr.report_type, revise_count,gr.status as report_status");
        $this->db->from('sample_registration sr');
        $this->db->join('trf_registration tr', 'tr.trf_id = sr.trf_registration_id', 'inner');
        $this->db->join('cust_customers as cc', 'cc.customer_id = tr.trf_applicant', 'left');
        $this->db->join('mst_sample_types as mst', 'mst.sample_type_id = sr.sample_registration_sample_type_id', 'left');
        $this->db->join('generated_reports as gr', 'gr.sample_reg_id = sr.sample_reg_id','inner');
        $this->db->group_by('sr.sample_reg_id');
        $query = $this->db->get();
        // print_r($this->db->last_query());die;
        if ($count) {
            return $query->num_rows();
        } else {
            if ($query->num_rows() > 0) {
                return $query->result();
            } else {
                return false;
            }
        }
        
    }

    public function send_email($sample_reg_id,$report_id, $mail_module)
    {
        // Get crm users
        $crm_query = $this->db->select('sample_pickup_services, crm_user_id,trf_id,trf_registration.product_custom_fields,manual_report_result')
            ->join('trf_registration', 'trf_id=trf_registration_id')
            ->where('sample_reg_id', $sample_reg_id)
            ->get('sample_registration');

        if ($crm_query->num_rows() > 0) {
            $result = $crm_query->result_array();
            $crm_user_list = explode(',', $result[0]['crm_user_id']);
            $trf_id = $result[0]['trf_id'];
        } else {
            $crm_user_list = '';
        }

        $crm_user_data = array();
        $crm_data = '';

        if (count($crm_user_list) > 0) {
            for ($i = 0; $i < count($crm_user_list); $i++) {
                $crm_user_data_query = $this->db->select('admin_users.admin_email,admin_profile.admin_telephone,concat(admin_profile.admin_fname," ",admin_profile.admin_lname) as user_name')
                    ->join('admin_profile', 'admin_profile.uidnr_admin=admin_users.uidnr_admin')
                    ->where('admin_users.uidnr_admin', $crm_user_list[$i])
                    ->get('admin_users');
                $crm_user_data = $crm_user_data_query->result_array()[0];
                $crm_data .= isset($crm_user_data['user_name']) ? "<b><span lang=EN-GB>" . $crm_user_data['user_name'] . "</span><br/></b></p><p class=MsoNormal style=line-height:105%><span lang=EN-GB>E-Mail: </span><br/><span lang=EN-GB>" . $crm_user_data['admin_email'] . "</span></a></p><p class=MsoNormal><span lang=EN-GB>Phone: " . $crm_user_data['admin_telephone'] . "</span></p>" : '&nbsp;';
                $crm_mail_list[0] = $crm_user_data['admin_email'];
            }
        }

        if ($mail_module == "release_report") {
            $customer_id_query = $this->db->select('trf_applicant')
                ->join('sample_registration sr', 'sr.trf_registration_id=tr.trf_id')
                ->where('sample_reg_id', $sample_reg_id)
                ->get('trf_registration tr');
            if ($customer_id_query->num_rows() > 0) {
                $customer_id = $customer_id_query->row()->trf_applicant;
                // $tpl_query = $this->db->get_where('sys_email_template', ['MailTypeId' => "37", 'customer_id' => $customer_id]);
                // if ($tpl_query->num_rows() > 0) {
                //     $tpl = $tpl_query->result_array();
                // } else {
                //     $tpl_query = $this->db->get_where('sys_email_template', ['MailTypeId' => "37"]);
                //     $tpl = $tpl_query->result_array()[0];
                // }
            }
            // Added alias for the sales person email id On 22-06-2021 by saurabh
            $det_mail_query = $this->db->query("SELECT sample_registration.qty_received as quantity, sample_registration_branch_id, admin_users.admin_email,admin_profile.admin_telephone,concat(admin_profile.admin_fname,' ',admin_profile.admin_lname) as user_name,contacts.contact_name,trf_ref_no,gc_no, (SELECT email FROM cust_customers WHERE customer_id=sample_customer_id) AS customer_email, (SELECT customer_name FROM cust_customers WHERE customer_id=sample_customer_id) AS customer_name, (SELECT email FROM cust_customers WHERE customer_id=trf_applicant) AS applicant_email, (SELECT customer_name FROM cust_customers WHERE customer_id=trf_applicant) AS applicant_name, (SELECT GROUP_CONCAT(email) FROM contacts WHERE FIND_IN_SET(contact_id,trf_contact)) AS contacts_mail, (SELECT    GROUP_CONCAT(email)  FROM contacts WHERE FIND_IN_SET(contact_id,trf_cc)) AS contacts_cc, (SELECT    GROUP_CONCAT(admin_email)  FROM admin_users WHERE FIND_IN_SET(uidnr_admin,crm_user_id)) AS crm_user_email, (SELECT GROUP_CONCAT(admin_email)  FROM admin_users WHERE admin_users.uidnr_admin=trf_registration.sales_person) AS sales_person_email, (SELECT  GROUP_CONCAT(email)  FROM contacts WHERE FIND_IN_SET(contact_id,trf_bcc)) AS contacts_bcc, trf_service_type AS  service_type,CASE WHEN trf_service_type ='Regular' AND (service_days IS NULL OR service_days='')
                 THEN CONCAT(trf_service_type,' 3 Days')
            WHEN trf_service_type ='Regular' AND service_days IS NOT  NULL
                 THEN CONCAT(trf_service_type,' ',service_days,' Days')
            ELSE trf_service_type END AS trf_service_type,service_days, (select GROUP_CONCAT(test_name) FROM sample_test INNER JOIN tests ON test_id=sample_test_test_id WHERE sample_test_sample_reg_id=sample_reg_id) AS test, (SELECT customer_name FROM cust_customers WHERE customer_id=trf_applicant) AS applicant, (SELECT customer_name FROM cust_customers WHERE customer_id=trf_buyer) AS buyer, trf_registration.trf_sample_desc as sample_desc,"
                . " date_format(sample_registration.received_date,'%d-%b-%Y') AS sample_reg_date,Date_Format(received_date,'%M %e %Y'),trf_buyer,trf_applicant,trf_registration.trf_end_use FROM trf_registration INNER JOIN  sample_registration ON trf_id=trf_registration_id INNER JOIN  admin_profile ON admin_profile.uidnr_admin=crm_user_id INNER JOIN  admin_users ON admin_users.uidnr_admin=crm_user_id INNER JOIN  contacts ON contact_id=trf_contact WHERE sample_reg_id = " . $sample_reg_id);
              //  echo $this->db->last_query();die;
              if($det_mail_query->num_rows() > 0){
                $det_mail = $det_mail_query->result_array()[0];
              }
           
        // echo "<pre>";print_r($det_mail); die;
           
            $color = "";
            $Order = "";
            $Style = "";
            $Category = "";
            if ($result) {
                $custom = json_decode($result[0]['product_custom_fields']);
                
                
                foreach ($custom as $key => $value) {
                    if (stripos($value[0], 'Color') !== FALSE) {
                        $color = $value[1];
                    }
                    if (stripos($value[0], 'Order') !== FALSE) {
                        $Order = $value[1];
                    }
                    if (stripos($value[0], 'Style') !== FALSE) {
                        $Style = $value[1];
                    }
                    if (stripos($value[0], 'Category') !== FALSE) {
                        $Category = $value[1];
                    }
                }
            }
           
            $qry_one_query = $this->db->query("SELECT Date_Format(due_date,'%M %e %Y') as due_date from sample_registration where sample_reg_id={$sample_reg_id} ");

            if (is_array($det_mail['contact_name']) && count($det_mail['contact_name']) > 1) {
                $contact_name = implode('/', $det_mail['contact_name']);
            } else {
                $contact_name = $det_mail['contact_name'];
            }

            $report_due_date = $qry_one_query->result_array()[0]['due_date'];

            if ($det_mail['customer_name'] == 'Not Provided') {
                $det_mail['customer_name'] = '';
            }
            if ($det_mail['buyer'] == 'Not Provided') {
                $det_mail['buyer'] = '';
            }

            $CountryQuery = $this->db->where('trf_id', $trf_id)->select('origin.country_name as country_origin,destination.country_name as country_destination')
            ->from('trf_registration')
            ->join('mst_country origin', 'origin.country_id = trf_registration.trf_country_orgin','LEFT')
            ->join('mst_country destination', 'destination.country_id = trf_registration.trf_country_destination','LEFT')->get();
   
            if($CountryQuery->num_rows() >0){
                $country = $CountryQuery->result_array()[0];
                $origin = $country['country_origin'];
                $destination = $country['country_destination'];
            }

            if($result[0]['manual_report_result'] == 1){
                $reportResult = 'PASS';
            } elseif($result[0]['manual_report_result'] == 2){
                $reportResult = 'FAIL';
            }else{
                $reportResult = 'OTHER';
            }

            $formdata = array(
                'GCNO' => isset($det_mail['gc_no']) ? $det_mail['gc_no'] : '&nbsp;', //changes on 21 jan 
                'branch_id' => isset($det_mail['sample_registration_branch_id']) ? $det_mail['sample_registration_branch_id'] : '&nbsp;', //changes on 21 jan 
                'STYLE' => isset($Style) ? $Style : '&nbsp;', //changes on 22 jan 
                'ATTENTION' => isset($contact_name) ? $contact_name : '&nbsp;',
                'REPORT_NO' => isset($det_mail['gc_no']) ? $det_mail['gc_no'] : '&nbsp;', //changes on 22 jan
                'SERVICE' => isset($det_mail['trf_service_type']) ? $det_mail['trf_service_type'] : '&nbsp;',
                'TO' => isset($det_mail['applicant_name']) ? $det_mail['applicant_name'] : '&nbsp;',
                'APPLICANT' => isset($det_mail['applicant_name']) ? $det_mail['applicant_name'] : '&nbsp;',
                'SAMPLE_DESC' => isset($det_mail['sample_desc']) ? $det_mail['sample_desc'] : '&nbsp;',
                'COLOR' => isset($color) ? $color : '&nbsp;',
                'ARTICLE' => isset($article_no) ? $article_no : '&nbsp;',
                'END_USE' => isset($trf_end_use) ? $trf_end_use : '&nbsp;',
                'PO_NO' => isset($Order) ? $Order : '&nbsp;',
                'STYLE_NO' => isset($Style) ? $Style : '&nbsp;',
                'CATEGORY' => isset($Category) ? $Category : '&nbsp;',
                'BUYER' => isset($det_mail['buyer']) ? $det_mail['buyer'] : '&nbsp;',
                // 'QUANTITY' => isset($det_mail['quantity']) ? $det_mail['quantity'] : '&nbsp;',
                'REPORT_DATE' => isset($report_due_date) ? $report_due_date : '&nbsp;',
                'TEST' => isset($det_mail['test']) ? $det_mail['test'] : '&nbsp;',
                'SAMPLE_RECIEVE_DATE' => isset($det_mail['sample_reg_date']) ? $det_mail['sample_reg_date'] : '&nbsp;',
                'CUSTOMER_SERVICE_CONTACT' => isset($crm_data) ? $crm_data : '&nbsp;',
                'ORIGIN' => isset($origin) ? $origin : '&nbsp;',
                'DESTINATION' => isset($destination) ? $destination : '&nbsp;',
                'REPORTSTATUS' => isset($reportResult) ? $reportResult : '&nbsp;',
            );
            // Get custom fields value
            $custom_field_query = $this->db->select('product_custom_fields')
                ->where('trf_id', $trf_id)
                ->get('trf_registration');
            if ($custom_field_query->num_rows() > 0) {
                $formdata['custom_field'] = json_decode($custom_field_query->result()[0]->product_custom_fields);
            } else {
                $formdata['custom_field'] = "";
            }

            $gen_report = $this->db->where('report_id',$report_id)->select('report_num,status,manual_report_file')->get('generated_reports')->row_array();
            // Setting email template
            $html = $this->load->view('template/report_mail_send', $formdata, true);
            // $get_tpl = $this->getContentFromTPL($tpl['MailTemplateId'], $formdata);
            $msg = "Report Release mail resend success";
            $logDetail = array(
                'source_module' => "Release_Report",
                'operation' => "send_report_mail",
                'uidnr_admin' => $this->session->userdata('user_data')->uidnr_admin,
                'customer_id' => $det_mail['trf_applicant'],
                'log_activity_on' => date("Y-m-d H:i:s"),
                'action_message' => "Release Report mail send For GC NO {$det_mail['gc_no']}"
            );
            $this->db->insert("customer_activity_log", $logDetail);
        } else {
        }
        $custom_values = "";
        if (!empty($formdata['custom_field'])) {
            foreach ($formdata['custom_field'] as $value) {
                $customs[] = $value[0] . "-" . $value[1];
            }
            $custom_values = implode(",", $customs);
        }
        // echo '<pre>';
        // print_r($formdata);die;
        if($det_mail['trf_buyer'] == 363){
            $subject = $formdata['REPORTSTATUS'].'_'. $formdata['CATEGORY'] . "_".$formdata['APPLICANT'] ."_" . $formdata['COLOR'] . "_" . $gen_report['report_num'];
        }else{
            $subject = $formdata['REPORTSTATUS']. "_" . $gen_report['report_num'].'_'. $formdata['SAMPLE_DESC'] . "_".$formdata['STYLE_NO'] ."_" . $formdata['COLOR'] ;
        }
        

        // Added by Saurabh on 10-02-2021 for checking whom to send email
        if (INSTANCE_TYPE == "development") {
            $to = array(TO, "developer.cps02@basilrl.com");
            $cc = array(CC, "developer.cps@basilrl.com");
            $bcc = array('developer.cps01@basilrl.com');
        } else {
            /**
             * Updated By Saurabh on 12-04-2022
             */
            // $to = array($det_mail['contacts_mail'], $det_mail['customer_email'], $det_mail['sales_person_email']);
            $to = array($det_mail['contacts_mail'], $det_mail['sales_person_email']);
            $cc = array($det_mail['crm_user_email'], $det_mail['contacts_bcc']);
            $bcc = array($det_mail['contacts_bcc']);
        }



        return ['to' => $to, 'cc' => $cc, 'bcc' => $bcc, 'template' => $html, 'subject' => $subject];


        // // Added by Saurabh on 10-02-2021 for checking whom to send email
        // $from = FROM;

        // // echo $html; die;

        // $sub = $subject;
        // $msg = $html; 
        // $mail = send_mail_function($to, $from, $cc, $msg, $sub);
        // if($mail){
        //     return true;
        // } else {
        //    return false;
        // }
    }
}

