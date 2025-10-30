<?php
defined('BASEPATH') or exit('No direct access allowed');

class Cron_model extends MY_Model{
    function __construct(){
        parent::__construct();
    }

    public function sendTATReportBYDivision($division_id,$division_name){
      
        
        $tatSQl = "SELECT * FROM ( SELECT trf.trf_ref_no AS trf_ref_no, 
                    sr.sample_desc,
                    (select division_name from mst_divisions WHERE division_id=trf.division) AS division,
                    (SELECT customer_name FROM cust_customers WHERE customer_id=trf.trf_applicant) AS client,                  
                    DATE_FORMAT( sr.received_date, '%d-%m-%Y %H:%i' ) AS received_date,
                         due_date, 
                sr.gc_no,sr.status,
                        mst.sample_type_name,sr.sample_registration_branch_id,
        
        (SELECT customer_name FROM cust_customers WHERE customer_id=trf.trf_buyer) AS buyer,
        DATE_FORMAT( gr.mr_result_ready_date, '%d-%m-%Y %H:%i' ) as report_generate_date,
        invp.proforma_invoice_number AS invoice_num,
        DATE_FORMAT(inv.generated_date, '%d-%m-%Y %H:%i' ) AS invoice_date,
        mr_remarks AS remarks,
        ap.admin_fname AS report_generated_by,
        gr.report_num AS report_number,sr.insufficient_remark,sr.receive_remark,trf.trf_service_type,(select group_concat(tst.test_name) from tests tst 
        join sample_test st  ON tst.test_id=st.sample_test_test_id where  st.sample_test_sample_reg_id = sr.sample_reg_id) as test_name
                        
                        FROM   sample_registration sr  
                        INNER JOIN  trf_registration trf ON trf.trf_id=sr.trf_registration_id 
                        LEFT JOIN mst_sample_types mst ON mst.sample_type_id = sr.sample_registration_sample_type_id 
                        left join generated_reports gr on gr.sample_reg_id=sr.sample_reg_id
                        left join admin_profile ap on ap.uidnr_admin=gr.report_generated_by
                        LEFT JOIN Invoices inv ON inv.report_num=gr.report_num
                        LEFT JOIN invoice_proforma invp ON invp.proforma_invoice_id=inv.proforma_invoice_id 	
                        
                         WHERE sr.released_to_client='0' and sr.division_id=".$division_id." and sr.status NOT IN('Login Cancelled') and due_date < curdate() and date(due_date) > '2020-01-01'
        ORDER BY received_date DESC) AS det  ORDER BY due_date DESC";
        
        $data = $this->db->query($tatSQl);
        $result = $data->result_array();
        
     
    
        $currentDate = date('d-M-Y');
        //$time = date('H:i', strtotime('+5 hours +30 minutes')); 
        $time = date('H:i');
        $csvArray=array();
        $header = array(
                            'Basil Report Number', 
                            'Division', 
                            'Client', 
                            'Buyer Name', 
                            'Product', 
                            'TRF No', 
                            'Received Date', 
                            'Status', 
                            'Insufficient Remarks', 
                            'Service Type', 
                            'Due Date', 
                            'Sample Desc', 
                            'Report number', 
                            'Report release date', 
                            'Invoice number', 
                            'Invoice date',
                            'Report released by', 
                            'Remarks',
                            'Test Names'
                          );
        foreach ($result AS $key => $row) {
            $branch_id = $row['sample_registration_branch_id'];
            $due_date = date('Y-m-d', strtotime($row['due_date']));
            //if ($date <= $currentDate && $time == '18:20') {
                
                $send = 'Yes';
                $sample_desc = preg_replace("/(\r?\n){2,}/", " ", $row['sample_desc']);
                $sample_desc = trim($sample_desc);
                $sample_desc = trim(str_replace(',', ' ', $sample_desc));
                $sample_desc = preg_replace("/[\n\r]/", " ", $sample_desc);
        
                if (!empty($row['gc_no']))
                    $csvArray[] = array(
                                    $row['gc_no'], 
                                    $row['division'], 
                                    $row['client'], 
                                    $row['buyer'], 
                                    $row['sample_type_name'], 
                                    $row['trf_ref_no'], 
                                    $row['received_date'], 
                                    $row['status'], 
                                    $row['insufficient_remark'], 
                                    $row['trf_service_type'], 
                                    $due_date, 
                                    $sample_desc, 
                                    $row['report_number'], 
                                    $row['report_generate_date'], 
                                    $row['invoice_num'], 
                                    $row['invoice_date'], 
                                    $row['report_generated_by'], 
                                    $row['remarks'],
                                    $row['test_name']
                        );
            //}
        }
        if($result > 0){
            $this->load->helper('file');
    $la = glob(LOCAL_PATH .'CSV/*');
    foreach ($la as $key => $value) {
        if (is_dir($value)) {
            delete_files($value . '/', true);
            rmdir($value); // Delete the folder
        }
    }
        $filename = $division_name.'-Sample_report_due_date-' . date('d-m-Y') . '.csv';
            $file_path = LOCAL_PATH .'CSV/'. $filename;
            $file = fopen($file_path, 'wb');
            fputcsv($file, $header);
            foreach ($csvArray as $fields) {
                fputcsv($file, $fields);
            }
            fclose($file);
            return $file_path;
        
        }else{
            return false;
        }
       
        
        
        
        }


        public function sendBackLogsTATReportBYDivision($division_id,$division_name){
            $tatSQl = "SELECT * FROM ( SELECT trf.trf_ref_no AS trf_ref_no, 
            sr.sample_desc,
            (select division_name from mst_divisions WHERE division_id=trf.division) AS division,
            (SELECT customer_name FROM cust_customers WHERE customer_id=trf.trf_applicant) AS client,                  
            DATE_FORMAT( sr.received_date, '%d-%m-%Y %H:%i' ) AS received_date,
                 due_date, 
		sr.gc_no,sr.status,
                mst.sample_type_name,sr.sample_registration_branch_id,

(SELECT customer_name FROM cust_customers WHERE customer_id=trf.trf_buyer) AS buyer,
DATE_FORMAT( gr.mr_result_ready_date, '%d-%m-%Y %H:%i' ) as report_generate_date,
invp.proforma_invoice_number AS invoice_num,
DATE_FORMAT(inv.generated_date, '%d-%m-%Y %H:%i' ) AS invoice_date,
mr_remarks AS remarks,
ap.admin_fname AS report_generated_by,
gr.report_num AS report_number,sr.insufficient_remark,sr.receive_remark,trf.trf_service_type
                
                FROM   sample_registration sr  
                INNER JOIN  trf_registration trf ON trf.trf_id=sr.trf_registration_id 
                LEFT JOIN mst_sample_types mst ON mst.sample_type_id = sr.sample_registration_sample_type_id 
                left join generated_reports gr on gr.sample_reg_id=sr.sample_reg_id
                left join admin_profile ap on ap.uidnr_admin=gr.report_generated_by
                LEFT JOIN Invoices inv ON inv.report_num=gr.report_num
                LEFT JOIN invoice_proforma invp ON invp.proforma_invoice_id=inv.proforma_invoice_id 	
                
                 WHERE sr.released_to_client='0' and sr.division_id=".$division_id." and sr.status NOT IN('Login Cancelled') and due_date < curdate() and date(due_date) > '2019-01-01'
ORDER BY received_date DESC) AS det  ORDER BY due_date DESC";

$data = $this->db->query($tatSQl);
$result = $data->result_array();

// $sample_branch = $result[0]['sample_registration_branch_id'];


$currentDate = date('d-M-Y');
//$time = date('H:i', strtotime('+5 hours +30 minutes')); 
$time = date('H:i');
$csvArray=array();
$header = array(
                    'Basil Report Number', 
                    'Division', 
                    'Client', 
                    'Buyer Name', 
                    'Product', 
                    'TRF No', 
                    'Received Date', 
                    'Status', 
                    'Insufficient Remarks', 
                    'Service Type', 
                    'Due Date', 
                    'Sample Desc', 
                    'Report number', 
                    'Report release date', 
                    'Invoice number', 
                    'Invoice date',
                    'Report released by', 
                    'Remarks'
                  );
foreach ($result AS $key => $row) {
    $branch_id = $row['sample_registration_branch_id'];
    $due_date = date('Y-m-d', strtotime($row['due_date']));
    //if ($date <= $currentDate && $time == '18:20') {
        
        $send = 'Yes';
        $sample_desc = preg_replace("/(\r?\n){2,}/", " ", $row['sample_desc']);
        $sample_desc = trim($sample_desc);
        $sample_desc = trim(str_replace(',', ' ', $sample_desc));
        $sample_desc = preg_replace("/[\n\r]/", " ", $sample_desc);

        if (!empty($row['gc_no']))
            $csvArray[] = array(
                            $row['gc_no'], 
                            $row['division'], 
                            $row['client'], 
                            $row['buyer'], 
                            $row['sample_type_name'], 
                            $row['trf_ref_no'], 
                            $row['received_date'], 
                            $row['status'], 
                            $row['insufficient_remark'], 
                            $row['trf_service_type'], 
                            $due_date, 
                            $sample_desc, 
                            $row['report_number'], 
                            $row['report_generate_date'], 
                            $row['invoice_num'], 
                            $row['invoice_date'], 
                            $row['report_generated_by'], 
                            $row['remarks']
                );
    //}
}
    if($result > 0){
    $this->load->helper('file');
    $la = glob(LOCAL_PATH .'CSV/*');
    foreach ($la as $key => $value) {
        if (is_dir($value)) {
            delete_files($value . '/', true);
            rmdir($value); // Delete the folder
        }
    }
    $filename = $division_name.'-GGN-backlog-list-' . date('d-m-Y') . '.csv';
        $file_path = LOCAL_PATH .'CSV/'. $filename;
        $file = fopen($file_path, 'wb');
        fputcsv($file, $header);
        foreach ($csvArray as $fields) {
            fputcsv($file, $fields);
        }
        fclose($file);
        return $file_path;
    
    }else{
        return false;
    }
        }

    public function get_division(){
        $where = "division_type='1' AND status = '1'";
         $res = $this->db->select('*')->from('mst_divisions')->where($where)->get();
         if($res->num_rows()){
            return $res->result_array();
         }else{
             return false;
         }
    
        }

        public function equipment_maintenance(){
            // $db->query("UPDATE sample_registration SET sample_retain_status = '6'
            // WHERE sample_retain_expirydate < NOW()");
            $date = date('Y-m-d H:i:s');
            $update = $this->db->where('sample_retain_expirydate <',$date)->update('sample_registration',array('sample_retain_status' => '6'));
            // print_r($this->db->last_query());die;

$qry_maintanace = "SELECT  eqip_id,eqip_name,eqip_ID_no,CONCAT(admin_fname,' ', admin_lname) AS custodian_name,admin_email,
            date_format(next_maintanance_date,'%d-%b-%Y') AS date_with,mail_send_type 
            FROM eqip_equipments
            INNER JOIN admin_profile ON custodian_id=uidnr_admin
            INNER JOIN admin_users ON admin_users.uidnr_admin=admin_profile.uidnr_admin
            WHERE NOW() 
            BETWEEN DATE_SUB(next_maintanance_date, INTERVAL 30 
            DAY )
            AND next_maintanance_date AND mail_send_type IN('Default', 'Calibration')";
$qry_calbration = "SELECT eqip_id,eqip_name,eqip_ID_no,CONCAT(admin_fname,' ', admin_lname) AS custodian_name,admin_email,
            date_format(next_calib_date,'%d-%b-%Y') AS date_with,mail_send_type
            FROM eqip_equipments
            INNER JOIN admin_profile ON custodian_id=uidnr_admin
            INNER JOIN admin_users ON admin_users.uidnr_admin=admin_profile.uidnr_admin
            WHERE NOW( ) 
            BETWEEN DATE_SUB(next_calib_date, INTERVAL 30 
            DAY )
            AND next_calib_date AND mail_send_type IN('Default', 'Manitanance')";

$maintanace = $this->db->query($qry_maintanace);
$calbration = $this->db->query($qry_calbration);

$maintanace_result = $maintanace->result_array();
$calbration_result = $calbration->result_array();
//  echo '<pre>';print_r($calbration_result);die;
$tpl_qry = $this->db->query('SELECT * FROM sys_email_template WHERE MailTemplateId = "41" ');
$tpl = $tpl_qry->row();

if (!empty($maintanace_result)) {
    
    foreach ($maintanace_result AS $details) {
        if ($details['mail_send_type'] != 'Default') {
            $this->db->query("UPDATE eqip_equipments SET mail_send_type = 'All'
        WHERE eqip_id = {$details['eqip_id']}");
        } else {
            $this->db->query("UPDATE eqip_equipments SET mail_send_type = 'Manitanance'
        WHERE eqip_id = {$details['eqip_id']}");
        }
        $formdata = array('SITE_PATH' => base_url(), 'CUSTODIAN' => $details['custodian_name'], 'EQUIPMENT_NAME' => $details['eqip_name'],
            'EQUIPMENT_ID' => $details['eqip_ID_no'],
            'DATE_LABEL' => 'Next Maintanace Date', 'DATE_WITH' => $details['date_with']);
        $get_tpl = getContentFromTPL($tpl['MailTemplateId'], $formdata);
        $CI = &get_instance();
        $CI->load->library('email');
        $config['protocol'] = PROTOCOL;
        $config['smtp_host'] = HOST;
        $config['smtp_user'] = USER;
        $config['smtp_pass'] = PASS;
        $config['smtp_port'] = PORT;
        $config['newline'] = "\r\n";
        $config['smtp_crypto'] = CRYPTO;
        $config['charset'] = 'utf-8';
        $config['newline'] = "\r\n";
        $config['mailtype'] = 'html';
        $CI->email->initialize($config);
        $CI->email->from(FROM, 'BASIL');
        if(INSTANCE_TYPE == "development"){
           
            $CI->email->to('shankar.k@basilrl.com', 'Shankar Kumar');
        }else{
          
        $CI->email->to($details['admin_email'], $details['custodian_name']);
       
       
        }
        // $CI->email->cc(CC_MAIL_EQUIPMENT);
        $CI->email->subject($get_tpl['subject']);
        $CI->email->message($get_tpl['content']);
        // $mail->AltBody = $get_tpl['subject'];
        $CI->email->send();
    }
}
if (!empty($calbration_result)) {
    foreach ($calbration_result AS $details) {
        if ($details['mail_send_type'] != 'Default') {
            $this->db->query("UPDATE eqip_equipments SET mail_send_type = 'All'
        WHERE eqip_id = {$details['eqip_id']}");
        } else {
            $this->db->query("UPDATE eqip_equipments SET mail_send_type = 'Calibration'
            WHERE eqip_id = {$details['eqip_id']}");
        }
        $formdata = array('SITE_PATH' => base_url(), 'CUSTODIAN' => $details['custodian_name'], 'EQUIPMENT_NAME' => $details['eqip_name'],
            'EQUIPMENT_ID' => $details['eqip_ID_no'],
            'DATE_LABEL' => 'Next Calibration Date', 'DATE_WITH' => $details['date_with']);
        $get_tpl = getContentFromTPL($tpl['MailTemplateId'], $formdata);
        $CI = &get_instance();
        $CI->load->library('email');
        $config['protocol'] = PROTOCOL;
        $config['smtp_host'] = HOST;
        $config['smtp_user'] = USER;
        $config['smtp_pass'] = PASS;
        $config['smtp_port'] = PORT;
        $config['newline'] = "\r\n";
        $config['smtp_crypto'] = CRYPTO;
        $config['charset'] = 'utf-8';
        $config['newline'] = "\r\n";
        $config['mailtype'] = 'html';
        $CI->email->initialize($config);
        $CI->email->from(FROM, 'BASIL');
        if(INSTANCE_TYPE == "development"){
           
            $CI->email->to('shankar.k@basilrl.com', 'Shankar Kumar');
        }else{
          
        $CI->email->to($details['admin_email'], $details['custodian_name']);
       
       
        }
      
        $CI->email->subject($get_tpl['subject']);
        $CI->email->message($get_tpl['content']);
        // $mail->AltBody = $get_tpl['subject'];
        $CI->email->send();
    }
}

$customer_login_det1 = $this->db->query('SELECT customer_login_id,DATE_ADD(last_login_date, INTERVAL 30 DAY ) AS last_login_date  FROM customer_login');
$customer_login_det = $customer_login_det1->result_array();
// echo '<pre>';
// print_r($customer_login_det);die;
foreach ($customer_login_det AS $login_det) {
    $expire = $login_det['last_login_date'];
    $currentDate = date("Y-m-d H:i:s");

    if ($expire < $currentDate && $expire != null) {
        $this->db->query("UPDATE customer_login SET customer_login_status = 'Inactive'
        WHERE customer_login_id = {$login_det['customer_login_id']}");
    }
}
        }


        public function sample_status_cron(){
          
        $sql = "select sample_status_request.* from trf_registration join sample_status_request on sample_status_request.trf_id=trf_registration.trf_id where sample_status_request.approved_on < DATE_SUB(CURDATE(), INTERVAL 1 DAY) AND sample_status_request.acceptance_status='Accepted'";

         $data = $this->db->query($sql);
         $results = $data->result_array();
         if(!empty($results)){
         foreach($results as $value){
            $this->db->where('trf_id',$value['trf_id'])->update('sample_status_request',array('acceptance_status' => 'In-Active'));
         }
        }

        }
        public function customerInactive(){
        $customer = $this->db->query('SELECT customer_login_id,DATE_ADD(last_login_date, INTERVAL 30 DAY ) AS last_login_date  FROM customer_login');
      
        $customer_login_det =  $customer->result_array();
        
        foreach ($customer_login_det AS $login_det) {
            $expire = $login_det['last_login_date'];
            $currentDate = date("Y-m-d H:i:s");
    
            if ($expire < $currentDate && $expire != null) {
                $this->db->query("UPDATE customer_login SET customer_login_status = 'Inactive'
                WHERE customer_login_id = {$login_det['customer_login_id']}");
            }
        }
    }
}

?>