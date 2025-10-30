<?php

class BarcodeModel extends MY_Model{
    
    public function __construct() {
        parent::__construct();
    }
    
    public function get_barcodeDetails($barcode_code, $sample_time){
        $data = [];
        $details = " select * from sample_registration where gc_no='$barcode_code'";
        $sample_reg = $this->db->query($details)->row_array();

        $reg_id = $sample_reg['sample_reg_id'];
     
        $user_id = $this->session->userdata('user_data')->uidnr_admin;
        
        $sample_scan="select divi.division_name as default_division_name from admin_users as ad "
                . "inner join mst_divisions as divi on ad.default_division_id=divi.division_id where ad.uidnr_admin='$user_id'";
        $samplelog = $this->db->query($sample_scan)->row_array();
   
        $default_division_name = $samplelog['default_division_name'];
        if ($sample_time == 'OUT') {
            $scanned_text = 'Sample Scan Out From ';
        } else {
            $scanned_text = 'Sample Scan In ';
        }
        $scanned_result = $scanned_text . $default_division_name;
         ///
        $sample_reg_id = $sample_reg['sample_reg_id'];
        
        $sample_status = $this->db->query("SELECT status FROM sample_registration WHERE sample_reg_id=" . $sample_reg_id)->row_array();

        $data['status'] = 'Sample Sent for Evaluation';
        $status = $this->perform("sample_registration", $data, 'update', "sample_reg_id =$sample_reg_id");
        if ($status) {
            $logDetails = array('module' => 'Samples',
                'old_status' => $sample_status['status'],
                'new_status' => $data['status'],
                'sample_reg_id' => $sample_reg_id,
                'sample_assigned_lab_id' => /* $lab_id, */ '',
                'action_message' => $scanned_result,
                'sample_job_id' => '',
                'report_id' => '',
                'report_status' => '',
                'test_ids' => '',
                'test_names' => '',
                'test_newstatus' => '',
                'test_oldStatus' => '',
                'test_assigned_to' => '',
            );
            $this->save_user_log($logDetails);
        }

$trf_type = $this->db->query("SELECT trf_type FROM trf_registration
                                INNER JOIN sample_registration sr ON sr.trf_registration_id=trf_id    
                                WHERE sample_reg_id = " . $reg_id)->row_array();

if ($reg_id > 0) {
    if ($trf_type['trf_type'] == 'Open TRF') {
       $data = $this->db->query("SELECT DATE_FORMAT(collection_date,'%d-%M-%Y') AS collection_date,
                        DATE_FORMAT(received_date,'%d-%M-%Y') AS received_date,
                        sample_source_type, sample_name,sample_desc,seal_no,qty_received,quantity_desc,
                        barcode_no,barcode_path,sample_retain_period,                    
                        (SELECT sample_type_name FROM mst_sample_types 
                        WHERE sample_type_id = sample_registration_sample_type_id) AS 
                        sample_registration_sample_type_id,qty_unit,            
                        CASE   
                        WHEN sr.sample_source_type = 'Vessel' THEN (SELECT vessel_rig_name FROM vessel_rigs 
                        WHERE vessel_rig_id = sr.vessel_transformer_id)
                        WHEN sr.sample_source_type = 'Rig' THEN (SELECT transformer_number FROM transformer_engine 
                        WHERE transformer_id = sr.vessel_transformer_id)
                        ELSE 'None'  
                        END AS vessel_transformer_id,
                        (SELECT container_name AS TEXT FROM mst_container_type 
                        WHERE container_type_id = sr.container_type_id) AS container_type_id,
                        CASE   
                        WHEN sr.sample_registration_test_standard_id > 0 THEN (SELECT test_standard_name FROM mst_test_standards 
                        WHERE test_standard_id = sr.sample_registration_test_standard_id)
                        ELSE 'None'  
                        END AS  test_standard,
                        (SELECT branch_name FROM mst_branches WHERE branch_id = sr.sample_registration_branch_id) AS  branch,
                        trf_ref_no,gc_no,
                        (SELECT customer_name FROM cust_customers WHERE customer_id=trf_applicant) AS applicant,
                    /*(SELECT contact_name FROM contacts WHERE contact_id=trf_contact)*/ (select GROUP_CONCAT(contact_name SEPARATOR ' / ') from contacts where FIND_IN_SET(contact_id,trf_contact)) AS applicant_contact,
                    (SELECT customer_name FROM cust_customers WHERE customer_id=CASE WHEN trf_invoice_to='Factory' THEN trf_applicant WHEN trf_invoice_to='Buyer' THEN trf_buyer ELSE trf_agent END) AS invoice_name,
                    (SELECT contact_name FROM contacts WHERE contact_id=trf_invoice_to_contact) AS invoice_contact,
                    (SELECT customer_name FROM cust_customers WHERE customer_id=open_trf_customer_id) AS quote_cust,'Test' AS product_type
                        FROM sample_registration sr 
                        INNER JOIN sample_test st ON st.sample_test_sample_reg_id = sr.sample_reg_id  
                        INNER JOIN trf_registration trf_reg ON trf_reg.trf_id=sr.trf_registration_id"
                . " WHERE sample_reg_id = " . $reg_id)->row_array();  
    } else {
        $data = $this->db->query("SELECT DATE_FORMAT(collection_date,'%d-%M-%Y') AS collection_date,
                        DATE_FORMAT(received_date,'%d-%M-%Y') AS received_date,
                        sample_source_type, sample_name,sample_desc,seal_no,qty_received,quantity_desc,
                        barcode_no,barcode_path,sample_retain_period,                    
                        (SELECT sample_type_name FROM mst_sample_types 
                        WHERE sample_type_id = sample_registration_sample_type_id) AS 
                        sample_registration_sample_type_id,qty_unit,            
                        CASE   
                        WHEN sr.sample_source_type = 'Vessel' THEN (SELECT vessel_rig_name FROM vessel_rigs 
                        WHERE vessel_rig_id = sr.vessel_transformer_id)
                        WHEN sr.sample_source_type = 'Rig' THEN (SELECT transformer_number FROM transformer_engine 
                        WHERE transformer_id = sr.vessel_transformer_id)
                        ELSE 'None'  
                        END AS vessel_transformer_id,
                        (SELECT container_name AS TEXT FROM mst_container_type 
                        WHERE container_type_id = sr.container_type_id) AS container_type_id,
                        CASE   
                        WHEN sr.sample_registration_test_standard_id > 0 THEN (SELECT test_standard_name FROM mst_test_standards 
                        WHERE test_standard_id = sr.sample_registration_test_standard_id)
                        ELSE 'None'  
                        END AS  test_standard,
                        (SELECT branch_name FROM mst_branches WHERE branch_id = sr.sample_registration_branch_id) AS  branch,
                        trf_ref_no,gc_no,
                        (SELECT customer_name FROM cust_customers WHERE customer_id=trf_applicant) AS applicant,
                    /*(SELECT contact_name FROM contacts WHERE contact_id=trf_contact)*/ (select GROUP_CONCAT(contact_name SEPARATOR ' / ') from contacts where FIND_IN_SET(contact_id,trf_contact)) AS applicant_contact,
                    (SELECT customer_name FROM cust_customers WHERE customer_id=CASE WHEN trf_invoice_to='Factory' THEN trf_applicant WHEN trf_invoice_to='Buyer' THEN trf_buyer ELSE trf_agent END) AS invoice_name,
                    (SELECT contact_name FROM contacts WHERE contact_id=trf_invoice_to_contact) AS invoice_contact,
                    (SELECT customer_name FROM cust_customers WHERE customer_id=quotes_customer_id) AS quote_cust,wk.product_type
                        FROM sample_registration sr 
                        INNER JOIN sample_test st ON st.sample_test_sample_reg_id = sr.sample_reg_id  
                        INNER JOIN trf_registration trf_reg ON trf_reg.trf_id=sr.trf_registration_id
                        INNER JOIN quotes qt ON qt.quote_id=trf_quote_id
                        INNER JOIN works wk ON wk.work_id=trf_reg.work_id"
                . " WHERE sample_reg_id = " . $reg_id)->row_array();
    }

    $test_data = $this->db->query("SELECT st.sample_test_test_id as sample_test_test_id, 
                ts.test_name as sample_test_test_name,ts.test_method as sample_test_test_method
                FROM sample_test st , tests ts 
                where st.checked ='true' and ts.test_id = st.sample_test_test_id and sample_test_sample_reg_id = {$reg_id}
                ORDER BY ts.test_name ASC ")->result_array();

    //$job_work_id = $db->getItemFromDB("select work_id from job_transactions where job_id = {$job_id}", true);


    $custome_fields =  $this->db->query("SELECT rf.registration_fields_name,
                            srf.sample_registration_fields_values AS field_values
                            FROM registration_fields rf
                            INNER JOIN sample_registration_fields srf 
                            ON rf.registration_fields_id = srf.sample_registration_fields_id 
                            AND rf.registration_fields_sample_type_id = srf.sample_registration_fields_type_id
                            WHERE srf.sample_registration_fields_reg_id = {$reg_id}
                            AND rf.registration_fields_type = 'Custom' AND rf.status='0'")->result_array();
}

$notMentioned = "-  N A  -";

/*----sample log---*/
$details = " select sample_reg_log_id , (SELECT CONCAT( admin_profile.admin_fname, ' ', admin_profile.admin_lname )  from admin_profile  where admin_profile.uidnr_admin= sample_reg_activity_log.uidnr_admin )as user,"
        . "  log_activity_on,old_status,new_status,"
        . "  case when test_assigned_to>0 then 
	CONCAT((select UPPER(test_name) from tests where test_id=test_ids ),' ',action_message,' by ',(SELECT CONCAT( admin_profile.admin_fname, ' ', admin_profile.admin_lname )  from admin_profile  where admin_profile.uidnr_admin= sample_reg_activity_log.test_assigned_to))
	else action_message
	end AS action_message "
        . " from sample_reg_activity_log "
        . " where sample_reg_id ='{$sample_reg_id}' order by sample_reg_log_id desc";

 $samplelog = $this->db->query($details)->result_array();
 $details2 = " select dv.division_name,jobs_activity_log_id , (SELECT CONCAT( admin_profile.admin_fname, ' ', admin_profile.admin_lname )  from admin_profile  where admin_profile.uidnr_admin= jobs_activity_log.uidnr_admin )as user,"
        . "   log_activity_on,old_status,new_status,"
        . "  action_message "
        . " from sample_registration "
        . " inner join jobs_activity_log on trf_id=sample_registration.trf_registration_id "
        . " left join admin_users u on u.uidnr_admin=jobs_activity_log.uidnr_admin "
        . " left join mst_divisions dv on dv.division_id=u.default_division_id "
        . " where sample_reg_id ='{$sample_reg_id}' order by jobs_activity_log_id desc";
  $samplelog1 =  $this->db->query($details)->result_array($details2); 


$samplelog = array_merge($samplelog, $samplelog1);


// if (!empty($test_data['test_data'])) {
//                    foreach ($test_data['test_data'] as $row) {
//                      $row['sample_test_test_name']; 
//                       $row['sample_test_test_method']; 
//
//                            $saved_parameters = $this->db->query("SELECT GROUP_CONCAT(parameter_id) "
//                                    . "FROM work_analysis_test_parameters WHERE  work_analysis_test_id = {$row['sample_test_test_id']}")->row_array();
//
//                            if (!empty($saved_parameters['parameter_id'])) {
//                                $saved_parameters = str_replace(';', ',', $saved_parameters['parameter_id']);
//                                $params_con = " AND test_parameters_id IN ({$saved_parameters['parameter_id']}) ";
//
//
//                                $sub_params = $this->db->query("SELECT GROUP_CONCAT(test_parameters_disp_name)"
//                                        . " FROM test_parameters WHERE test_parameters_test_id = {$row['sample_test_test_id']}"
//                                        . " AND show_in_report ='Yes' {$params_con} ORDER BY test_parameters_disp_name ASC")->row_array();
//                              
//                            }
//                        }
//                    }



     return [
             'data' => $data, 'custome_fields' => $custome_fields, 'test_data' => $test_data, 
             'samplelog' => $samplelog, 'trf_type' => $trf_type
             ];
    }

    public function perform($table, $data, $action = 'insert', $parameters = '') {
      reset($data);
    
        if ($action == 'insert' or $action == 'replace') {
            $query = strtoupper($action) . ' INTO ' . $table . ' (' . join(', ', array_keys($data)) . ') VALUES (';
            reset($data);
            foreach ($data as $value) {
                if (strpos($value, 'func:') !== false) {
                    $query .= substr($value, 5) . ', ';
                } else {
                    switch ((string) $value) {
                        case 'now()' :
                            $query .= 'NOW(), ';
                            break;
                        case 'null' :
                            $query .= 'NULL, ';
                            break;
                        default :
                            $query .= '\'' . $value . '\', ';
                            break;
                    }
                }
            }
            $query = substr($query, 0, -2) . ')';

            return $query;
        } elseif ($action == 'update') {
            $query = 'UPDATE ' . $table . ' SET ';
            foreach ($data as $columns => $value) {
                if (strpos($value, 'func:') !== false) {
                    $query .= $columns . ' = ' . substr($value, 5) . ', ';
                } else {
                    switch ((string) $value) {
                        case 'now()' :
                            $query .= $columns . ' = NOW(), ';
                            break;
                        case 'null' :
                            $query .= $columns . ' = NULL, ';
                            break;
                        case '++' :
                            $query .= $columns . ' = ' . $columns . ' + 1, ';
                            break;
                        default :
                            $query .= $columns . ' = \'' . $value . '\', ';

                            break;
                    }
                }
            }
       
            $query = substr($query, 0, -2);
            if ($parameters !== '')
                $query .= ' WHERE ' . $parameters;

            return $query;
        }   
    }
    
     function input($string) {
        return mysqli_real_escape_string($this->db->link, $string);
    }
    
     function query($query, $logQuery = true) {
        $result = mysqli_query($this->db->link, $query) or $this->error($query, mysqli_errno($this->db->link), mysqli_error($this->db->link));
        return $result;
    }
    
   
}
