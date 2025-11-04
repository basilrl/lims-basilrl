<?php

defined('BASEPATH') or exit('No direct script access allowed');

class SampleRegistration extends MY_Model
{

    function __construct()
    {
        parent::__construct();
    }

    public function get_product_details($id)
    {
        $this->db->select('product.sample_type_id as pid, product.sample_type_name as pname');
        $this->db->from('trf_registration as trf_reg');
        $this->db->join('mst_sample_types as product', 'product.sample_type_id = trf_reg.trf_product');
        $this->db->where('trf_reg.trf_id', $id);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->row();
        }
        return [];
    }

    public function get_selected_test($trf_id)
    {
        // $this->db->select('test_id, test_name, test_method');
        // $this->db->from('trf_test');
        // $this->db->join('tests', 'trf_test.trf_test_test_id = tests.test_id');
        // $this->db->where('trf_test_trf_id', $trf_id);
        // $query = $this->db->get();
        // if ($query->num_rows() > 0) {
        //     return $query->result_array();
        // }
        // return [];
        $this->db->select('ts.test_id, ts.test_name, tm.test_method_id, tm.test_method_name, tt.trf_test_id, tt.trf_test_quote_type, tt.trf_work_id, tt.trf_test_protocol_id, tt.trf_test_package_id, tt.trf_test_quote_id, rate_per_test');
        $this->db->join('tests ts', 'ts.test_id = tt.trf_test_test_id');
        $this->db->join('mst_test_methods tm', 'tm.test_method_id = ts.test_method_id');
        $this->db->where('trf_test_trf_id', $trf_id);
        $query = $this->db->get('trf_test tt');
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return [];
    }

    public function get_labs_by_branch($trf_id)
    {
        // Get TRF division
        $division_id = $this->get_fields_by_id('trf_registration', 'division', $trf_id, 'trf_id')[0]['division'];
        // $this->db->select('trf_test_test_id ');
        // $test_id = $this->db->get_where('trf_test',['trf_test_trf_id' => $trf_id]);
        // if($test_id->num_rows() > 0){
        //     $result = $test_id->result_array();
        //     foreach($result as $ids){
        //         $test_ids[] = $ids['trf_test_test_id'];
        //     }
        // } else {
        //     $test_ids = [];
        // }

        // $lab_query = $this->db->select('distinct(lab_id), lab_name')
        //                       ->from('mst_labs')
        //                       ->join('tests','mst_labs_lab_type_id  = test_lab_type_id ','left')
        //                       ->where('mst_labs_branch_id ',$branch)
        //                       ->where_in('test_id',$test_ids)
        //                       ->get();
        $lab_query = $this->db->select('lab_id, lab_name')
            ->join('mst_lab_type', 'lab_type_id = mst_labs_lab_type_id')
            ->join('mst_divisions', 'division_id = mst_lab_type_division_id')
            ->where('division_id', $division_id)
            ->get('mst_labs');
        if ($lab_query->num_rows() > 0) {
            return $lab_query->result_array();
        }
        return [];
    }

    public function get_test_spec($product_id)
    {
        $query1 = $this->db->select('test_standard_id, test_standard_name')
            ->from('mst_test_standards')
            ->where('status', '1')
            ->where('mst_test_standards_sample_type_id', $product_id)
            ->get_compiled_select();

        $query2 = $this->db->select(' 0 AS test_standard_id,"None" AS test_standard_name')
            ->from('mst_test_standards')
            ->get_compiled_select();

        $query = $this->db->query($query1 . ' UNION ' . $query2 . 'order by test_standard_name asc');
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return [];
    }

    public function save_sample_registration($record, $dynamic_fields, $test, $care_instruction)
    {
        $this->db->trans_begin();


        // Get division code
        $division_code_query = $this->db->select('division_code')
            ->from('trf_registration')
            ->join('mst_divisions', 'division_id=division')
            ->where('trf_id', $record['trf_registration_id'])
            ->get();
        if ($division_code_query->num_rows() > 0) {
            $division_code = $division_code_query->row()->division_code;
        }

        $branch_code_query = $this->db->select('branch_code')->get_where('mst_branches', ['branch_id' => $record['sample_registration_branch_id']]);
        if ($branch_code_query->num_rows() > 0) {
            $branch_code = $branch_code_query->row()->branch_code;
        }
        // Added by Saurabh on 10-02-2021 for missing GC number
        if (!empty($record['gc_number'])) {
            $rand = str_pad($record['gc_number'], 5, "0", STR_PAD_LEFT);
            $unique = 'BL-' . $division_code . '-' . date('y') . '-' . $rand;
            $record['gc_no'] = $unique;
        } /* Added by Saurabh on 10-02-2021 for missing GC number*/ else {
            if ($record['sample_registration_branch_id'] != 4) {
                // Get serial number
                $serial_no_query = $this->db->select_max('serial_no')
                    ->from('sample_number_confiq')
                    ->where('year(created_on)', date('Y'))
                    ->where('branch_id', $record['sample_registration_branch_id'])
                    ->where('division_id', $record['division_id'])
                    ->get()->row();

                $serial_number = $serial_no_query->serial_no + 1;
                // echo $serial_number; die;
                $confiq['branch_id'] = $record['sample_registration_branch_id'];
                $confiq['division_id'] = $record['division_id'];
                $confiq['serial_no'] = $serial_number;
                $confiq['created_on'] = date("Y-m-d H:i:s");
                $this->db->insert("sample_number_confiq", $confiq);
                // GC number
                $rand = str_pad($serial_number, 5, "0", STR_PAD_LEFT);
                $unique = 'BL-' . $division_code . '-' . date('y') . '-' . $rand;
                $record['gc_no'] = $unique;
            } else {
                // Get serial number 
                $date = numberOfDayPassed();
                $serial_no_query = $this->db->select_max('serial_no')
                    ->from('sample_number_confiq')
                    ->where('year(created_on)', date('Y'))
                    ->where('branch_id', $record['sample_registration_branch_id'])
                    ->where('division_id', $record['division_id'])
                    ->where('days_number', $date)
                    ->get()->row();
                // echo $this->db->last_query();
                $serial_number = $serial_no_query->serial_no + 1;
                // echo $serial_number; die;
                $confiq['branch_id'] = $record['sample_registration_branch_id'];
                $confiq['division_id'] = $record['division_id'];
                $confiq['serial_no'] = $serial_number;
                $confiq['created_on'] = date("Y-m-d H:i:s");
                $confiq['days_number'] = $date;
                $this->db->insert("sample_number_confiq", $confiq);
                // GC number
                $rand = str_pad($serial_number, 4, "0", STR_PAD_LEFT);
                $unique = 'BL-' . $division_code . '-' . date('y') . '-' . $rand;
                $record['gc_no'] = $unique;
            }
        }
        unset($record['gc_number']);
        // Generate ulr number/*-------------Check current year count - If count is zero truncate table 14-01-2022------------------*/
        $count = $this->db->select('count(ulr_id) as id_count')->where('current_year', date('Y'))->get('cps_ulr')->row_array();
        if ($count['id_count'] == 0) {
            $this->db->query('TRUNCATE cps_ulr');
        }
        $save_ulr = $this->db->insert('cps_ulr', ['created_date' => date('Y-m-d H:i:s'), 'current_year' => date('Y')]);
        $save_ulr = $this->db->insert('cps_ulr', ['created_date' => date('Y-m-d H:i:s')]);
        $last_id = $this->db->insert_id();
        $ulrid_number = str_pad($last_id, 8, "0", STR_PAD_LEFT);
        $ulr_no = 'TC6371' . date('y') . '0' . $ulrid_number;
        // Check ULR Number condition for Bangladesh and Dubai added by saurabh on 10-02-2021
        if ($record['sample_registration_branch_id'] == 1) {
            $record['ulr_no'] = $ulr_no;
        } else {
            $record['ulr_no'] = "";
        }
        // Check ULR Number condition for Bangladesh and Dubai added by saurabh on 10-02-2021

        // Generate barcode
        $filepath = base_url() . "assets/barcode/";
        @chmod($filepath, 0755);
        $localpath = "assets/barcode/";
        $text = $unique;
        $size = "30";
        $orientation = "horizontal";
        $code_type = "code128";
        $print = true;
        $sizefactor = "1";
        $text = str_replace('/', '-', $text);
        $image_data = barcode($filepath, $text, $size, $orientation, $code_type, $print, $sizefactor);
        $filename = $text . ".png";
        $file = $this->uploadBarcode($filename);
        $barcode_file = $file['aws_path'];

        $record['barcode_no'] = $filename;
        $record['barcode_path'] = $barcode_file;

        // Due date calculation

        $trf_detail = $this->db->select('trf_service_type,service_days,tat_date')
            ->from('trf_registration')
            ->where('trf_id', $record['trf_registration_id'])
            ->get();


        $receivedate = date("Y-m-d H:i:s");
        $trf_details = $trf_detail->row();
        if (!empty($trf_details->tat_date) && strtotime($trf_details->tat_date) != 0 && $trf_details->tat_date != '0000-00-00 00:00:00') {
            $due_date = date('d-M-Y', strtotime($trf_details->tat_date));
        } else {
            if ($trf_details->trf_service_type === 'Regular') {
                if ($trf_details->service_days > 1) {
                    $includingToday = $trf_details->service_days - 1;
                } else {
                    $includingToday = 2;
                }

                $days = ' ' . $includingToday . ' Days';
                $due_date = $this->calculateDueDate(date('Y-m-d H:i', strtotime($receivedate)), $includingToday);
            } elseif ($trf_details->trf_service_type === 'Express') {
                $includingToday = 1;
                $due_date = $this->calculateDueDate(date('Y-m-d H:i', strtotime($receivedate)), $includingToday);
            } else if ($trf_details->trf_service_type === 'Express3') {
                $includingToday = 2;
                $due_date = $this->calculateDueDate(date('Y-m-d H:i', strtotime($receivedate)), $includingToday);
            } else {
                $includingToday = 0;
                $due_date = $this->calculateDueDate(date('Y-m-d H:i', strtotime($receivedate)), $includingToday, true);
            }
        }

        $record['due_date'] = date("Y-m-d", strtotime($due_date));
        // Save sample registration
        if (!empty($dynamic_fields)) {
            $record['product_custom_fields'] = json_encode($dynamic_fields);
        } else {
            $record['product_custom_fields'] = null;
        }
        $save_sample = $this->db->insert('sample_registration', $record);
        $sample_reg_id = $this->db->insert_id();

        // delete application care provided instruction for the TRF
        $this->db->delete('trf_apc_instruction', ['trf_id' => $record['trf_registration_id']]);
        // Insert new application care provided instruction
        if (!empty($care_instruction)) {
            foreach ($care_instruction as $key => $care_instructions) {
                $care_instructions['created_by'] = $this->admin_id();
                $care_instructions['created_on'] = date('Y-m-d H:i:s');
                $care_instructions['trf_id'] = $record['trf_registration_id'];
                $data[$key] = $care_instructions;
            }
            // echo "<pre>"; print_r($data); die;
            $this->insert_multiple_data('trf_apc_instruction', $data);
        }

        // Update trf registration
        $trf_data['trf_status'] = "Sample Registered";
        $this->db->update('trf_registration', $trf_data, ['trf_id' => $record['trf_registration_id']]);

        // Check dynamic field
        // $field_id = $this->db->select('registration_fields_id')
        //                     ->from('registration_fields')
        //                     ->where('registration_fields_sample_type_id',$record['sample_registration_sample_type_id'])
        //                     ->where('status','0')
        //                     ->get();
        // if($field_id->num_rows() > 0){
        //     $field_id = $field_id->result_array();
        // }else {
        //     $field_id = [];
        // }

        // if(!empty($dynamic_fields)){
        //     $count = count($dynamic_fields);
        //     foreach ($field_id as $id) {
        // 		$dynamic_field['sample_registration_fields_reg_id'] = $sample_reg_id;
        // 		$dynamic_field['sample_registration_fields_type_id'] = $record['sample_registration_sample_type_id'];
        // 		$dynamic_field['sample_registration_fields_id'] = $id['registration_fields_id'];
        // 		$dynamic_field['sample_registration_fields_values'] = $dynamic_fields['trf_registrationfield_fields_'.$dynamic_field['sample_registration_fields_id']];
        // 		//Insert into trf registration fields
        // 		$status1 = $this->db->insert("sample_registration_fields", $dynamic_field);
        //         $regi_fields = $this->db->insert_id();
        //     }
        // }
        // Check trf type
        $trf_type = $this->get_data_by_id($table = "trf_registration", $id = $record['trf_registration_id'], $column_name = "trf_type");
        // Save tests
        if (!empty($test)) {
            $gridCount = count($test);
            $testIds = '';
            if ($gridCount > 0) {
                foreach ($test as $gridTest) {
                    $testIds .= $gridTest['trf_test_test_id'];
                    if ($gridCount > 1)
                        $testIds .= ',';
                }
            }
            $getLabids_query = $this->db->select('group_concat(Distinct (lab_id)) as lab_id')
                ->from('mst_labs lb')
                ->join('tests ts', 'ts.test_lab_type_id = lb.mst_labs_lab_type_id', 'left')
                ->join('mst_lab_type mlt', 'mlt.lab_type_id=ts.test_lab_type_id')
                ->where('lb.mst_labs_branch_id', $record['sample_registration_branch_id'])
                ->where_in('ts.test_id', $testIds)
                ->get();
            if ($getLabids_query->num_rows() > 0) {
                $getLab = $getLabids_query->row();
                $getlab_reg1['no_labs_assigned'] = array_unique(explode(",", $getLab->lab_id));
                $getlab_reg2['no_labs_assigned'] = implode(",", $getlab_reg1['no_labs_assigned']);
                $this->db->update('sample_registration', $getlab_reg2, ['sample_reg_id' => $sample_reg_id]);
            } else {
                $getLab = [];
            }

            // Get lab id for tests
            foreach ($test as $tests) {
                $labid_query = $this->db->select('group_concat(Distinct lab_id) as lab_id')
                    ->from('mst_labs lb')
                    ->join('tests ts', 'ts.test_lab_type_id = lb.mst_labs_lab_type_id', 'left')
                    ->join('mst_lab_type mlt', 'mlt.lab_type_id=ts.test_lab_type_id')
                    ->where('lb.mst_labs_branch_id', $record['sample_registration_branch_id'])
                    ->where('ts.test_id', $tests['trf_test_test_id'])
                    ->get();
                if ($labid_query->num_rows() > 0) {
                    $lab_id = $labid_query->row()->lab_id;
                } else {
                    $lab_id = "";
                }

                if ($trf_type == "TRF") {
                    // Columns to add query goes here
                } else {
                    // Columns to add query goes here
                }
                $test_data['sample_test_lab_id'] = $lab_id;
                $test_data['sample_test_test_id'] = $tests['trf_test_test_id'];
                $test_data['sample_test_sample_reg_id'] = $sample_reg_id;
                $test_data['sample_test_parameters'] = "";
                $test_data['sample_test_quote_type'] = $tests['sample_test_quote_type'];
                $test_data['sample_test_quote_id'] = $tests['sample_test_quote_id'];
                $test_data['sample_test_protocol_id'] = $tests['sample_test_protocol_id'];
                $test_data['sample_test_package_id'] = $tests['sample_test_package_id'];
                $test_data['sample_test_work_id'] = $tests['sample_test_work_id'];
                $test_data['rate_per_test'] = $tests['rate_per_test'];
                /* Columns to add
                    Add rate per test
                    Discount
                    applicable charge
                */
                $this->db->insert('sample_test', $test_data);
            }
        }

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return $result = array('success' => false);
        } else {
            $this->db->trans_commit();
            // echo $filename; die;
            return $result = array('success' => true, 'sample_reg_id' => $sample_reg_id);
        }
    }

    public function get_status()
    {
        $query = $this->db->select('distinct(status) as status')->get('sample_registration');
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return [];
    }
    public function get_registered_sample($start, $end, $trf, $customer_name, $product, $created_on, $ulr_no, $gc_number, $buyer, $status, $division, $style_no, $start_date, $end_date, $applicant,$labtype,$startdue,$enddue,$report_remark, $year, $month,$count = null)
    {
        // UPDATED BY SAURABH ON 15-11-2021,ADDED report_release_time, generated_date
        $this->db->select("buyer.isactive as buyer_active,customer.isactive as customer_active,tr.trf_id,tr.tat_date,sr.due_date,barcode_no, barcode_path, sr.sample_reg_id, sample_customer_id, gc_no, sample_registration_branch_id, sample_type_name, DATE_FORMAT(sr.create_on, '%d-%b-%Y') as created_on, customer.customer_name as customer, sample_desc, buyer.customer_name as buyer, admin_fname as created_by, tr.trf_ref_no, sr.ulr_no,sr.status, gr.manual_report_file as manual_report_file,gr.qr_code_name as qr_code_name,sr.comment,sr.released_to_client, (select count(proforma_invoice_id) from invoice_proforma where sr.sample_reg_id = proforma_invoice_sample_reg_id) as pi_count, additional_flag, trf_service_type, report_release_time, generated_date, applicant.customer_name as applicant_name, acknowledgement_mail_status,tr.product_custom_fields,sr.manual_report_remark,sr.manual_report_result,sr.marked_invoice,(CASE WHEN trf_service_type ='Regular' AND  ( service_days IS NULL OR service_days='') THEN CONCAT(trf_service_type,' 3 Days') WHEN trf_service_type ='Express' THEN CONCAT('Express',' 2 Days') WHEN trf_service_type ='Express3' THEN CONCAT('Express',' 3 Days') WHEN trf_service_type ='Same Day' THEN CONCAT('Same Day',' 0 Days') WHEN trf_service_type ='Urgent'  THEN CONCAT(trf_service_type,' 1 Days') WHEN service_days IS NOT NULL OR service_days!='' THEN CONCAT(trf_service_type,' ',service_days,' Days') END) AS trf_service_type ");
        $this->db->from('sample_registration sr');
        $this->db->join('trf_registration tr', 'sr.trf_registration_id = tr.trf_id');
        $this->db->join('mst_sample_types', 'sample_type_id = sr.sample_registration_sample_type_id');
        $this->db->join('cust_customers customer', 'customer.customer_id=tr.trf_applicant', 'left');
        $this->db->join('cust_customers buyer', 'buyer.customer_id=tr.trf_buyer', 'left');
        $this->db->join('cust_customers applicant', 'applicant.customer_id=tr.trf_applicant', 'left');
        $this->db->join('admin_profile', 'sr.create_by = uidnr_admin', 'left');
        $this->db->join('sample_hold_remark shr', 'shr.sample_reg_id=sr.sample_reg_id', 'left');
        $this->db->join('generated_reports as gr', 'gr.sample_reg_id = sr.sample_reg_id AND (gr.additional_report_flag <> 1 AND  gr.revise_report <> "1" )', 'left'); // added by millan on 19-01-2021
        // $this->db->where('buyer.isactive', 'Active');
        // $this->db->where('customer.isactive', 'Active');

        // Commented by Saurabh on 30-12-2021, as asked by sir

        // Added by Saurabh on 11-08-2021 to remove controllex division
        $this->db->where('tr.division !=', '34');
        // Added by Saurabh on 11-08-2021 to remove controllex division

        // if (exist_val('Branch/Wise', $this->session->userdata('permission'))) {
        //     $multibranch = $this->session->userdata('branch_ids');
        //     $this->db->group_start();
        //     $this->db->where(['sr.sample_registration_branch_id IN (' . $multibranch . ') ' => null]); //branch_id
        //     $this->db->group_end();
        // }

        // Added by saurabh on 01-02-2022 to show division wise list
        // $checkUser = $this->session->userdata('user_data');
        // $default_division = $checkUser->default_division_id;
        // $assigned_division = $checkUser->user_divisions;
        // $assigned_customer = $checkUser->assigned_customer;

        // $this->db->group_start();
        // $this->db->where('tr.division',$default_division);
        // $this->db->or_where_in('tr.division',explode(',',$assigned_division));
        // if(!empty($assigned_customer)){			
        //     $this->db->or_where_in('tr.open_trf_customer_id',explode(',',$assigned_customer));
        // }
        // $this->db->group_end();
        // Added by saurabh on 01-02-2022 to show division wise list

        if ($report_remark != "null" && $report_remark != "") {
            $this->db->where('sr.marked_invoice', $report_remark);
        }
        if ($customer_name != "null" && $customer_name != "") {
            $this->db->where('tr.open_trf_customer_id', $customer_name);
        }

        if ($applicant != "null" && $applicant != "") {
            $this->db->where('tr.trf_applicant', $applicant);
        }
        if ($division != "null" && $division != "") {
            $this->db->where('tr.division', $division);
        }
        if ($status != "null" && $status != "") {
            $this->db->where('sr.status', base64_decode($status));
        }
        if ($buyer != "null" && $buyer != "") {
            $this->db->where('tr.trf_buyer', $buyer);
        }
        if ($product != "null" && $product != "") {
            $this->db->where('sample_registration_sample_type_id', $product);
        }
        if ($created_on != "null" && $created_on != "") {
            $this->db->where('date(sr.create_on)', base64_decode($created_on));
        }

        // if ($style_no != "null" && $style_no != "") {
        //     $this->db->like('tr.style_number', base64_decode($style_no));
        // }

        if ($style_no != "null" && $style_no != "") {
            $newstyle = trim(strtolower(base64_decode($style_no)));
            $this->db->like('LOWER(tr.product_custom_fields)', $newstyle); // new change 
            // $this->db->like('LOWER(tr.product_custom_fields)', strtolower('["STYLE NO","%' .trim(strtolower(base64_decode($style_no))) . '"]')); // new change 
        }
        // dashboard
        // echo base64_decode($startdue);
        if ($startdue != "null" && !empty($startdue)) {
            $this->db->where('DATE(sr.due_date) <=', base64_decode($startdue));
            $this->db->where('sr.released_to_client', '0');
            $this->db->where('sr.status !=', 'Login Cancelled');
        }
        if ($enddue != "null" && !empty($enddue)) {
            $this->db->where('DATE(sr.due_date)', base64_decode($enddue));
            $this->db->where('sr.released_to_client', '0');
            $this->db->where('sr.status !=', 'Login Cancelled');
        }
        if ($labtype != "null" && $labtype != "") {
            $this->db->where('sr.sample_registered_to_lab_id', $labtype);
        }
        // end
        if (!empty($status) && $status != "null" && $status != "" && base64_decode($status) == 'Hold Sample') {
            if (!empty($start_date) && $start_date != "null") {
                $sdate = base64_decode($start_date);
                $edate = ($end_date != 'null') ? base64_decode($end_date) : date('Y-m-d');
                $this->db->group_start();
                $this->db->where(['date(shr.created_on) BETWEEN "' . $sdate . '" AND  "' . $edate . '" ' => null]);
                $this->db->group_end();
            }
        } else {
            if (!empty($start_date) && $start_date != "null") {
                $sdate = base64_decode($start_date);
                $edate = ($end_date != 'null') ? base64_decode($end_date) : date('Y-m-d');
                $this->db->group_start();
                $this->db->where(['date(sr.create_on) BETWEEN "' . $sdate . '" AND  "' . $edate . '" ' => null]);
                $this->db->group_end();
            }
        }

        if ($year != '' && $year != "null")
            $this->db->where('YEAR(sr.create_on)', $year);

        if ($month != '' && $month != "null")
            $this->db->where('Month(sr.create_on)', $month);

        if ($trf != "null" && $trf != "") {
            $this->db->like('tr.trf_ref_no', trim(base64_decode($trf)));
        }
        if ($ulr_no != "null" && $trf != "") {
            $this->db->like('sr.ulr_no', trim(base64_decode($ulr_no)));
        }

        if ($gc_number != "null" && $trf != "") {
            $this->db->like('sr.gc_no', trim(base64_decode($gc_number)));
        }
        if (!$count) {
            $this->db->limit($start, $end);
        }
        $this->db->order_by('sample_reg_id', 'desc');
        $this->db->group_by('sample_reg_id');
        $query = $this->db->get();
        //echo $this->db->last_query(); die;
        if ($count) {
            return $query->num_rows();
        } elseif ($query->num_rows() > 0) {
            $result = $query->result_array();
            foreach ($result as $value) {
                $sample_reg_id = $value['sample_reg_id'];
                // Get sample images count
                $count = $this->db->select('count(image_id) as count')->where('sample_reg_id', $sample_reg_id)->get('sample_photos');
                $value['image_count'] = $count->result()[0]->count;
                $samples[] = $value;
            }
            return $samples;
        } else {
            return [];
        }
    }

    public function update_status($status, $id)
    {
        $old_status_query = $this->db->select('status')->from('sample_registration')->where('sample_reg_id', $id)->get();
        $old_status = $old_status_query->row()->status;

        $query = $this->db->update('sample_registration', $status, ['sample_reg_id' => $id]);
        if ($query) {
            $logDetails = array(
                'module' => 'Samples',
                'old_status' => $old_status,
                'new_status' => $status['status'],
                'sample_reg_id' => $id,
                'sample_assigned_lab_id' => /* $lab_id, */ '',
                'action_message' => 'Sample Sent for Evaluation',
                'sample_job_id' => '',
                'report_id' => '',
                'report_status' => '',
                'test_ids' => '',
                'test_names' => '',
                'test_newstatus' => '',
                'test_oldStatus' => '',
                'test_assigned_to' => '',
                'source_module'    => 'sample_registration',
                'operation'        => 'sendFor_sample_evaluation',
                'uidnr_admin'    => $this->session->userdata('user_data')->uidnr_admin,
                'log_activity_on' => date("Y-m-d H:i:s")
            );
            $this->save_user_log($logDetails);
            return true;
        } else {
            return false;
        }
    }

    public function get_sample_detail($id)
    {
        $this->db->select('sample_registration_sample_type_id, sample_reg_id, sample_desc, seal_no, qty_received, unit as qty_unit, barcode_path as barcode, CASE WHEN (Select test_standard_name from mst_test_standards where test_standard_id = sr.sample_registration_test_standard_id) IS NULL THEN "None" ELSE (Select test_standard_name from mst_test_standards where test_standard_id = sr.sample_registration_test_standard_id) END AS  test_specification, customer_name as client, sample_registration_branch_id, GROUP_CONCAT(contact_name SEPARATOR " / ") as contact, CONCAT( admin_fname," ",admin_lname) as create_by, DATE_FORMAT(sr.collection_date,"%d-%b-%Y %H:%i") AS collection_time, gc_no, price_type, quantity_desc, DATE_FORMAT(sr.received_date,"%d-%b-%Y %H:%i") AS received_date, sr.status, CASE WHEN tat_date IS NOT NULL THEN DATE_FORMAT(tat_date,"%d-%b-%Y %H:%i") ELSE "" END AS tat_date, CASE WHEN sample_retain_period = 0 THEN "NA" ELSE CONCAT(sample_retain_period," Days") END AS sample_retain_period, mst.sample_type_name as sample_type_id');
        $this->db->from('sample_registration sr');
        $this->db->join('cust_customers', 'customer_id = sr.sample_customer_id', 'left');
        $this->db->join('trf_registration trf', 'trf_id = trf_registration_id');
        $this->db->join('contacts', 'contact_id = trf.trf_contact');
        $this->db->join('admin_profile', 'sr.create_by = uidnr_admin', 'left');
        $this->db->join('mst_sample_types mst', 'mst.sample_type_id = sr.sample_registration_sample_type_id');
        $this->db->join('units', 'qty_unit = unit_id', 'left');
        $this->db->where('sr.sample_reg_id', $id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $result = $query->row();
            // Get test ids
            $this->db->select('sample_test_test_id');
            $this->db->from('sample_test');
            $this->db->where('sample_test_sample_reg_id', $result->sample_reg_id);
            $test_query = $this->db->get();
            if ($test_query->num_rows() > 0) {
                $record = $test_query->result_array();
                foreach ($record as $test_id) {
                    $test[] = $test_id['sample_test_test_id'];
                }
            } else {
                $test = [];
            }
            // Ends here
            // Get labs
            $labs = $this->get_lab($result->sample_registration_branch_id, $test);
            // Ends here

            $result->conducted_lab = $labs;
            return $result;
        }
        return [];
    }

    public function sample_selected_test($id)
    {
        $trf_query = $this->db->select('trf_type')
            ->from('trf_registration tr')
            ->join('sample_registration sr', 'sr.trf_registration_id = tr.trf_id')
            ->where('sample_reg_id', $id)
            ->get();
        if ($trf_query->num_rows() > 0) {
            $result = $trf_query->row();
            if ($result->trf_type == "TRF") {
                $currency_det = $this->db->select('distinct(trf_id), quote_exchange_rate as exchange_rate, currency_decimal')
                    ->from('sample_registration')
                    ->join('trf_registration tr', 'tr.trf_id = trf_registration_id')
                    ->join('quotes qt', 'qt.quote_id = trf_quote_id')
                    ->join('mst_currency crr', 'crr.currency_id=quotes_currency_id')
                    ->where('sample_reg_id', $id)
                    ->get();
            } else {
                $currency_det = $this->db->select('DISTINCT(trf_id),open_trf_exchange_rate AS exchange_rate,currency_decimal')
                    ->from('sample_registration')
                    ->join('trf_registration tr', 'tr.trf_id = trf_registration_id')
                    ->join('mst_currency crr', 'crr.currency_id=open_trf_currency_id')
                    ->where('sample_reg_id', $id)
                    ->get();
            }
            if ($currency_det->num_rows() > 0) {
                $record = $currency_det->row();
                $currency_decimal = $record->currency_decimal;
                $exchange_rate = $record->exchange_rate;
            } else {
                $currency_decimal = 0;
                $exchange_rate = 0.00;
            }

            $final_query = $this->db->select('ts.test_id, st.sample_test_id, st.sample_part_id, st.sample_test_sample_reg_id, ts.test_name as test_name, st.test_description, method.test_method_name as test_method, ROUND(st.rate_per_test,' . $currency_decimal . ') as rate_per_test,ROUND(st.discount,' . $currency_decimal . ') as rate_per_test, ROUND(st.discount,' . $currency_decimal . ') as discount, st.applicable_charge')
                ->from('sample_test st')
                ->join('tests ts', 'ts.test_id = st.sample_test_test_id')
                ->join('mst_test_methods method', 'method.test_method_id = ts.test_method_id')
                ->where('st.sample_test_sample_reg_id', $id)
                ->get();

            if ($final_query->num_rows() > 0) {
                $final_result =  $final_query->result_array();
                foreach ($final_result as $value) {
                    $final = $value;
                    $sample_part_id = explode(",", $value['sample_part_id']);
                    $part_name_query = $this->db->select('GROUP_CONCAT(part_name) as part_name')
                        ->where_in('part_id', $sample_part_id)
                        ->get('parts');
                    if ($part_name_query->num_rows() > 0) {
                        $part_name = $part_name_query->row()->part_name;
                    } else {
                        $part_name = "";
                    }

                    $final['part_name'] = $part_name;
                    $final['exchange_rate'] = $exchange_rate;
                    $final['currency_decimal'] = $currency_decimal;
                    $final_data[] = $final;
                }
                return $final_data;
            }
        }
        return [];
    }


    public function add_test_for_sample($test_id, $sample_id, $test_method)
    {
        // Get exchange rate and decimal point
        $rate_query = $this->db->select('exchange_rate, currency_decimal')
            ->from('mst_currency')
            ->join('trf_registration tr', 'tr.open_trf_currency_id = currency_id')
            ->join('sample_registration sr', 'tr.trf_id = sr.trf_registration_id')
            ->where('sr.sample_reg_id', $sample_id)
            ->get();
        $rate_result = $rate_query->row();
        // Get applicable charge and discount
        $charge_query = $this->db->select('wt.discount,wt.applicable_charge')
            ->from('sample_registration sr')
            ->join('trf_registration tr', 'tr.trf_id=sr.trf_registration_id')
            ->join('quotes q', 'q.quote_id = tr.trf_quote_id')
            ->join('works w', 'w.work_job_type_id=q.quote_id')
            ->join('works_analysis_test wt', 'wt.work_id=w.work_id')
            ->where('work_test_id', $test_id)
            ->where('sr.sample_reg_id', $sample_id)
            ->get();
        if ($charge_query->num_rows() > 0) {
            $result = $charge_query->row_array();
            $discount = $result['discount'];
            $applicable_charge = number_format($result['applicable_charge'], $rate_result->currency_decimal);
        } else {
            $discount = '0.00';
            $applicable_charge = number_format('0', $rate_result->currency_decimal);
        }

        // Get test labs
        $get_branch = $this->db->select('sample_registration_branch_id')
            ->from('sample_registration')
            ->where('sample_reg_id', $sample_id)
            ->get();
        if ($get_branch->num_rows() > 0) {
            $branch_result = $get_branch->row();
            $branch_id = $branch_result->sample_registration_branch_id;
        }

        $get_labs = $this->db->select('group_concat(DISTINCT lab_id) as lab_id')
            ->from('mst_labs lb')
            ->join('tests ts', 'ts.test_lab_type_id = lb.mst_labs_lab_type_id', 'left')
            ->join('mst_lab_type mlt', 'mlt.lab_type_id=ts.test_lab_type_id')
            ->where('lb.mst_labs_branch_id', $branch_id)
            ->where('ts.test_id', $test_id)
            ->get();
        if ($get_labs->num_rows() > 0) {
            $lab_result = $get_labs->row();
            $lab_id = $lab_result->lab_id;
        }

        // Get Rate per test
        $test_rate_query = $this->db->select('price')
            ->from('pricelist p')
            ->join('tests ts', 'ts.test_id = p.pricelist_test_id')
            ->where('ts.test_id', $test_id)
            ->get();
        if ($test_rate_query->num_rows() > 0) {
            $test_rate_result = $test_rate_query->row();
            $rate_per_test = number_format($test_rate_result->price * $rate_result->exchange_rate, $rate_result->currency_decimal);
        } else {
            $rate_per_test = number_format(0, $rate_result->currency_decimal);
        }

        $record['sample_test_test_id'] = $test_id;
        $record['sample_test_sample_reg_id'] = $sample_id;
        $record['discount'] = $discount;
        $record['applicable_charge'] = $applicable_charge;
        $record['sample_test_lab_id'] = $lab_id;
        $record['rate_per_test'] = $rate_per_test;
        $record['sample_test_test_method_id'] = $test_method;
        $save = $this->db->insert('sample_test', $record);
        if ($save) {
            $old_status_query = $this->db->select('status')->from('sample_registration')->where('sample_reg_id', $sample_id)->get();
            $old_status = $old_status_query->row()->status;
            $logDetails = array(
                'module' => 'Samples',
                'old_status' => $old_status,
                'new_status' => '',
                'sample_reg_id' => $sample_id,
                'sample_assigned_lab_id' => '',
                'action_message' => 'Test added',
                'report_id' => '',
                'report_status' => '',
                'test_ids' => '',
                'test_names' => '',
                'test_newstatus' => '',
                'test_oldStatus' => '',
                'test_assigned_to' => '',
                'uidnr_admin'   => $this->admin_id(),
                'operation' => 'add_test_for_sample'
            );

            $this->save_user_log($logDetails);
            $this->session->set_flashdata('success', 'Test added');
            return true;
        } else {
            $this->session->set_flashdata('false', 'Something went wrong!');
        }
    }

    public function get_parts_by_sample($sample_id)
    {
        $query = $this->db->select('part_id,part_name,parts_desc,parts_sample_reg_id')
            ->from('parts')
            ->join('sample_registration sr', 'sr.sample_reg_id=parts.parts_sample_reg_id')
            ->where('sr.sample_reg_id', $sample_id)
            ->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return [];
    }

    public function save_sample_parts($record, $part_id)
    {
        if (empty($part_id)) {
            $check = $this->check_part($record['parts_sample_reg_id'], $record['part_name'], $part_id);
            if ($check == 0) {
                $save = $this->db->insert('parts', $record);
                if ($save) {
                    $old_status_query = $this->db->select('status')->from('sample_registration')->where('sample_reg_id', $record['parts_sample_reg_id'])->get();
                    $old_status = $old_status_query->row()->status;
                    $logDetails = array(
                        'module' => 'Samples',
                        'old_status' => $old_status,
                        'new_status' => '',
                        'sample_reg_id' => $record['parts_sample_reg_id'],
                        'sample_assigned_lab_id' => '',
                        'action_message' => 'Part added to sample',
                        'report_id' => '',
                        'report_status' => '',
                        'test_ids' => '',
                        'test_names' => '',
                        'test_newstatus' => '',
                        'test_oldStatus' => '',
                        'test_assigned_to' => '',
                        'uidnr_admin'   => $this->admin_id(),
                        'operation' => 'save_sample_parts'
                    );

                    $this->save_user_log($logDetails);
                    return $response = ['status' => true, 'comment' => 'Part added successfully'];
                } else {
                    return $response = ['status' => false, 'comment' => 'Something went wrong!.'];
                }
            } else {
                return $response = ['status' => false, 'comment' => 'This Part Name already exist.'];
            }
        } else {
            $check = $this->check_part($record['parts_sample_reg_id'], $record['part_name'], $part_id);
            if ($check == 0) {
                $save = $this->db->update('parts', $record, ['part_id' => $part_id]);
                // echo $this->db->last_query(); die;
                if ($save) {
                    $old_status_query = $this->db->select('status')->from('sample_registration')->where('sample_reg_id', $record['parts_sample_reg_id'])->get();
                    $old_status = $old_status_query->row()->status;
                    $logDetails = array(
                        'module' => 'Samples',
                        'old_status' => $old_status,
                        'new_status' => '',
                        'sample_reg_id' => $record['parts_sample_reg_id'],
                        'sample_assigned_lab_id' => '',
                        'action_message' => 'Part updated to sample',
                        'report_id' => '',
                        'report_status' => '',
                        'test_ids' => '',
                        'test_names' => '',
                        'test_newstatus' => '',
                        'test_oldStatus' => '',
                        'test_assigned_to' => '',
                        'uidnr_admin'   => $this->admin_id(),
                        'operation' => 'save_sample_parts'
                    );

                    $this->save_user_log($logDetails);
                    return $response = ['status' => true, 'comment' => 'Part updated successfully'];
                } else {
                    return $response = ['status' => false, 'comment' => 'Something went wrong!.'];
                }
            } else {
                return $response = ['status' => false, 'comment' => 'This Part Name already exist.'];
            }
        }
    }

    public function check_part($sample_reg_id, $part_name, $part_id)
    {
        $this->db->select('count(part_id) as count');
        $this->db->from('parts');
        $this->db->where('parts_sample_reg_id', $sample_reg_id);
        $this->db->where('part_name', $part_name);
        if (!empty($part_id)) {
            $this->db->where('part_id', $part_id);
        }
        $query = $this->db->get();
        return $query->row()->count;
    }

    public function update_sample_test($sample_test_id, $part_id)
    {

        // Check sample test status
        $status_query = $this->db->select('status')->where('sample_test_id', $sample_test_id)->get('sample_test');
        if ($status_query->num_rows() > 0) {
            $status_data = $status_query->row_array();
            if ($status_data['status'] == "Record Enter Done") {
                $status = 'Mark As Completed By Lab';
            } else {
                $status = $status_data['status'];
            }
        }
        $query = $this->db->update('sample_test', ['sample_part_id' => $part_id, 'status' => $status], ['sample_test_id' => $sample_test_id]);
        if ($query) {
            return true;
        }
        return false;
    }

    public function save_evaluation($input)
    {
        $sample_reg_id = $input['sample_reg_id'];
        $grid_details = json_decode($input['grid_details']);
        // $email = $input['stop_email'];
        $price_type = $input['price_type'];
        // Get customer ID
        $customer_id_query = $this->db->select('trf_applicant')
            ->from('trf_registration tr')
            ->join('sample_registration sr', 'sr.trf_registration_id=tr.trf_id')
            ->where('sample_reg_id', $sample_reg_id)
            ->get();
        if ($customer_id_query->num_rows() > 0) {
            $result = $customer_id_query->row();
            $customer_id = $result->trf_applicant;
        } else {
            $customer_id = "0";
        }
        // Get email template
        $temp_query = $this->db->get_where('sys_email_template', ['MailTypeId' => '38', 'customer_id' => $customer_id]);
        $temp_result = $temp_query->row();

        // update sample_registarion table
        $data['status'] = "Evaluation Completed";
        $data['price_type'] = $price_type == '1' ? 'Book Price' : 'Flat Price';
        $this->db->update('sample_registration', $data, ['sample_reg_id' => $sample_reg_id]);

        // Get sample_test
        $query = $this->db->select('sample_test_id')
            ->from('sample_test')
            ->where('sample_test_sample_reg_id', $sample_reg_id)
            ->get();
        $test_ids = $query->result_array();
        foreach ($test_ids as $test_id) {
            $test_id_array[] = $test_id['sample_test_id'];
        }

        // process selected test
        foreach ($grid_details as $sample_test_details) {
            $test_name = $sample_test_details->test_name;
            $test_id = $sample_test_details->test_id;
            $sample_test_sample_reg_id = $sample_test_details->sample_test_sample_reg_id;
            $test_description = $sample_test_details->test_description;
            $sample_test_id = $sample_test_details->sample_test_id;
            $sample_test_loop_array[] = $sample_test_id;
            if (!empty($sample_test_id)) {
                $update['test_description'] = $test_description;
                $update['rate_per_test'] = $sample_test_details->rate_per_test;
                $update['discount'] = $sample_test_details->discount;
                $update['applicable_charge'] = round($sample_test_details->applicable_charge);
                $this->db->update('sample_test', $update, ['sample_test_id' => $sample_test_id]);
            } else {
                // Get branch id
                $branch_query = $this->db->select('sample_registration_branch_id as branch_id')
                    ->from('sample_registration')
                    ->where('sample_reg_id', $sample_reg_id)
                    ->get();
                $branch_id = $branch_query->row()->branch_id;

                // Get lab id
                $lab_id = $this->get_lab($branch_id, $test_id);
                // Insert
                $insert['sample_test_test_id'] = $test_id;
                $insert['sample_test_sample_reg_id'] = $sample_test_sample_reg_id;
                $insert['test_description'] = $test_description;
                $insert['rate_per_test'] = $sample_test_details->rate_per_test;
                $insert['discount'] = $sample_test_details->discount;
                $insert['applicable_charge'] = round($sample_test_details->applicable_charge);
                $insert['sample_test_lab_id'] = $lab_id;
                $query = $this->db->insert('sample_test', $insert);
            }
        }
        $dif = array_diff($test_id_array, $sample_test_loop_array);
        $ids = implode(', ', $dif);
        if (!empty($ids)) {
            $query = $this->db->where_in('sample_test_id', $ids)
                ->delete('sample_test');
        }

        // Email sending process
        // $get_values = $this->db->select('trf_ref_no, gc_no, email AS customer_email, customer_name, email as applicant_email, customer_name as applicant_name, GROUP_CONCAT(email)  AS contacts_mail, GROUP_CONCAT(email) AS contacts_cc, GROUP_CONCAT(email) AS contacts_bcc')
        //                        ->from('sample_registration sr')
        //                        ->join('trf_registration tr','sr.trf_registration_id=tr.trf_id')
        //                        ->join('cust_customers cu','cu.customer_id=sr.sample_customer_id')
        //                        ->where('sample_reg_id',$sample_reg_id)
        //                        ->get();
        // if($get_values->num_rows() > 0){
        //     $values_result = $get_values->result_array();

        //     if(empty($temp_result)){
        //         $temp_query = $this->db->get_where('sys_email_template',['MailTypeId' => '38']);
        //         $temp_result = $temp_query->row();
        //     }

        //     $formdata = array('GCNO' => $values_result[0]['gc_no'], 'TRFREF' => $values_result[0]['trf_ref_no']);
        //     $get_tpl = $this->getContentFromTPL($temp_result->MailTemplateId, $formdata);

        //     if(!empty($email) && $email === 'true'){
        //         $to = $values_result[0]['customer_email'].",".$values_result[0]['applicant_email'];
        //         $cc = $values_result[0]['contacts_cc'];
        //         $bcc = $values_result[0]['contacts_bcc'];
        //         $subject = $get_tpl['subject'];
        //         $body = $get_tpl['content'];
        //         send_sample_report($to,$cc,$bcc,$subject,$body);
        //     }

        if ($query) {
            return true;
        } else {
            return false;
        }
        // }
    }

    public function get_lab($branch_id, $test_id)
    {
        $this->db->select('GROUP_CONCAT(DISTINCT  lab_name) AS conducted_lab');
        $this->db->from('mst_labs lb');
        $this->db->join('tests ts', 'ts.test_lab_type_id = lb.mst_labs_lab_type_id', 'left');
        $this->db->join('mst_lab_type mlt', 'mlt.lab_type_id = ts.test_lab_type_id');
        $this->db->where('lb.mst_labs_branch_id', $branch_id);
        $this->db->where_in('ts.test_id', $test_id);
        $lab_query = $this->db->get();
        if ($lab_query->num_rows() > 0) {
            $labs = $lab_query->row()->conducted_lab;
        } else {
            $labs = "";
        }
        return $labs;
    }

    public function add_images($data)
    {
        $query = $this->db->insert('sample_photos', $data);
        if ($query) {
            return true;
        } else {
            return false;
        }
    }

    public function send_email($sample_reg_id, $mail_module)
    {
        // Get crm users
        $crm_query = $this->db->select('sample_pickup_services, crm_user_id,trf_id')
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

        if ($mail_module == "sample_registration") {
            $customer_id_query = $this->db->select('trf_applicant')
                ->join('sample_registration sr', 'sr.trf_registration_id=tr.trf_id')
                ->where('sample_reg_id', $sample_reg_id)
                ->get('trf_registration tr');
            // if ($customer_id_query->num_rows() > 0) {
            //     $customer_id = $customer_id_query->row()->trf_applicant;
            //     $tpl_query = $this->db->get_where('sys_email_template', ['MailTypeId' => "37", 'customer_id' => $customer_id]);
            //     if ($tpl_query->num_rows() > 0) {
            //         $tpl = $tpl_query->result_array();
            //     } else {
            //         $tpl_query = $this->db->get_where('sys_email_template', ['MailTypeId' => "37"]);
            //         $tpl = $tpl_query->result_array()[0];
            //     }
            // }
            // Added alias for the sales person email id On 22-06-2021 by saurabh
            $det_mail_query = $this->db->query("SELECT sample_registration.qty_received as quantity, sample_registration_branch_id, admin_users.admin_email,admin_profile.admin_telephone,concat(admin_profile.admin_fname,' ',admin_profile.admin_lname) as user_name,contacts.contact_name,trf_ref_no,gc_no, (SELECT email FROM cust_customers WHERE customer_id=sample_customer_id) AS customer_email, (SELECT customer_name FROM cust_customers WHERE customer_id=sample_customer_id) AS customer_name, (SELECT email FROM cust_customers WHERE customer_id=trf_applicant) AS applicant_email, (SELECT customer_name FROM cust_customers WHERE customer_id=trf_applicant) AS applicant_name, (SELECT GROUP_CONCAT(email) FROM contacts WHERE FIND_IN_SET(contact_id,trf_contact)) AS contacts_mail, (SELECT    GROUP_CONCAT(email)  FROM contacts WHERE FIND_IN_SET(contact_id,trf_cc)) AS contacts_cc, (SELECT    GROUP_CONCAT(admin_email)  FROM admin_users WHERE FIND_IN_SET(uidnr_admin,crm_user_id)) AS crm_user_email, (SELECT GROUP_CONCAT(admin_email)  FROM admin_users WHERE admin_users.uidnr_admin=trf_registration.sales_person) AS sales_person_email, (SELECT  GROUP_CONCAT(email)  FROM contacts WHERE FIND_IN_SET(contact_id,trf_bcc)) AS contacts_bcc, trf_service_type AS  service_type,CASE WHEN trf_service_type ='Regular' AND (service_days IS NULL OR service_days='')
                 THEN CONCAT(trf_service_type,' 3 Days')
            WHEN trf_service_type ='Regular' AND service_days IS NOT  NULL
                 THEN CONCAT(trf_service_type,' ',service_days,' Days')
            ELSE trf_service_type END AS trf_service_type,service_days, (select GROUP_CONCAT(test_name) FROM sample_test INNER JOIN tests ON test_id=sample_test_test_id WHERE sample_test_sample_reg_id=sample_reg_id) AS test, (SELECT customer_name FROM cust_customers WHERE customer_id=trf_applicant) AS applicant, (SELECT customer_name FROM cust_customers WHERE customer_id=trf_buyer) AS buyer, trf_registration.trf_sample_desc as sample_desc,"
                . " date_format(sample_registration.received_date,'%d-%b-%Y') AS sample_reg_date,Date_Format(received_date,'%M %e %Y'),trf_applicant FROM trf_registration INNER JOIN  sample_registration ON trf_id=trf_registration_id INNER JOIN  admin_profile ON admin_profile.uidnr_admin=crm_user_id INNER JOIN  admin_users ON admin_users.uidnr_admin=crm_user_id INNER JOIN  contacts ON contact_id=trf_contact WHERE sample_reg_id = " . $sample_reg_id);
            $det_mail = $det_mail_query->result_array()[0];
            // echo "<pre>";print_r($det_mail); die;
            $qry_one_query = $this->db->query("SELECT registration_fields_name AS tag_name,registration_fields_type AS tag_type, sample_registration_fields_values AS tag_values
            FROM registration_fields INNER JOIN sample_registration_fields ON sample_registration_fields_id=registration_fields_id WHERE sample_registration_fields_reg_id={$sample_reg_id} AND registration_fields_type='Custom'");
            if ($qry_one_query->num_rows() > 0) {
                $reg_fields = $qry_one_query->result_array()[0];
            } else {
                $reg_fields = "";
            }
            $color = "";
            $Order = "";
            $Style = "";
            if ($reg_fields) {

                foreach ($reg_fields as $key => $value) {
                    if (stripos($value[0], 'Color') !== FALSE) {
                        $color = $value[2];
                    }
                    if (stripos($value[0], 'Order') !== FALSE) {
                        $Order = $value[2];
                    }
                    if (stripos($value[0], 'Style') !== FALSE) {
                        $Style = $value[2];
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

            $formdata = array(
                'GCNO' => isset($det_mail['gc_no']) ? $det_mail['gc_no'] : '&nbsp;', //changes on 21 jan 
                'branch_id' => isset($det_mail['sample_registration_branch_id']) ? $det_mail['sample_registration_branch_id'] : '&nbsp;', //changes on 21 jan 
                'STYLE' => isset($Style) ? $Style : '&nbsp;', //changes on 22 jan 
                'ATTENTION' => isset($contact_name) ? $contact_name : '&nbsp;',
                'REPORT_NO' => isset($det_mail['gc_no']) ? $det_mail['gc_no'] : '&nbsp;', //changes on 22 jan
                'SERVICE' => isset($det_mail['trf_service_type']) ? $det_mail['trf_service_type'] : '&nbsp;',
                'TO' => isset($det_mail['applicant']) ? $det_mail['applicant'] : '&nbsp;',
                'APPLICANT' => isset($det_mail['applicant']) ? $det_mail['applicant'] : '&nbsp;',
                'SAMPLE_DESC' => isset($det_mail['sample_desc']) ? $det_mail['sample_desc'] : '&nbsp;',
                'COLOR' => isset($color) ? $color : '&nbsp;',
                'PO_NO' => isset($Order) ? $Order : '&nbsp;',
                'STYLE_NO' => isset($Style) ? $Style : '&nbsp;',
                'BUYER' => isset($det_mail['buyer']) ? $det_mail['buyer'] : '&nbsp;',
                // 'QUANTITY' => isset($det_mail['quantity']) ? $det_mail['quantity'] : '&nbsp;',
                'REPORT_DATE' => isset($report_due_date) ? $report_due_date : '&nbsp;',
                'TEST' => isset($det_mail['test']) ? $det_mail['test'] : '&nbsp;',
                'SAMPLE_RECIEVE_DATE' => isset($det_mail['sample_reg_date']) ? $det_mail['sample_reg_date'] : '&nbsp;',
                'CUSTOMER_SERVICE_CONTACT' => isset($crm_data) ? $crm_data : '&nbsp;'
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
            // Setting email template
            $html = $this->load->view('template/sample_acknowledge_mail', $formdata, true);
            // $get_tpl = $this->getContentFromTPL($tpl['MailTemplateId'], $formdata);
            $msg = "Sample Registration mail resend success";
            $logDetail = array(
                'source_module' => "Sample_registration",
                'operation' => "send_acknowledgement_mail",
                'uidnr_admin' => $this->session->userdata('user_data')->uidnr_admin,
                'customer_id' => $det_mail['trf_applicant'],
                'log_activity_on' => date("Y-m-d H:i:s"),
                'action_message' => "Sample Registration mail Resend For Basil Report Number {$det_mail['gc_no']}"
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
        $subject = "Sample Acknowledgement " . $formdata['GCNO'] . "-" . $formdata['SAMPLE_DESC'] . "-" . $custom_values;

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
            $to = array($det_mail['contacts_mail']);
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

    public function send_emailold($sample_reg_id, $mail_module)
    {
        // Get crm users
        $crm_query = $this->db->select('sample_pickup_services, crm_user_id,trf_id')
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

        if ($mail_module == "sample_registration") {
            $customer_id_query = $this->db->select('trf_applicant')
                ->join('sample_registration sr', 'sr.trf_registration_id=tr.trf_id')
                ->where('sample_reg_id', $sample_reg_id)
                ->get('trf_registration tr');
            if ($customer_id_query->num_rows() > 0) {
                $customer_id = $customer_id_query->row()->trf_applicant;
                $tpl_query = $this->db->get_where('sys_email_template', ['MailTypeId' => "37", 'customer_id' => $customer_id]);
                if ($tpl_query->num_rows() > 0) {
                    $tpl = $tpl_query->result_array();
                } else {
                    $tpl_query = $this->db->get_where('sys_email_template', ['MailTypeId' => "37"]);
                    $tpl = $tpl_query->result_array()[0];
                }
            }
            $det_mail_query = $this->db->query("SELECT sample_registration.qty_received as quantity, sample_registration_branch_id, admin_users.admin_email,admin_profile.admin_telephone,concat(admin_profile.admin_fname,' ',admin_profile.admin_lname) as user_name,contacts.contact_name,trf_ref_no,gc_no, (SELECT email FROM cust_customers WHERE customer_id=sample_customer_id) AS customer_email, (SELECT customer_name FROM cust_customers WHERE customer_id=sample_customer_id) AS customer_name, (SELECT email FROM cust_customers WHERE customer_id=trf_applicant) AS applicant_email, (SELECT customer_name FROM cust_customers WHERE customer_id=trf_applicant) AS applicant_name, (SELECT GROUP_CONCAT(email) FROM contacts WHERE FIND_IN_SET(contact_id,trf_contact)) AS contacts_mail, (SELECT    GROUP_CONCAT(email)  FROM contacts WHERE FIND_IN_SET(contact_id,trf_cc)) AS contacts_cc, (SELECT  GROUP_CONCAT(email)  FROM contacts WHERE FIND_IN_SET(contact_id,trf_bcc)) AS contacts_bcc, trf_service_type AS  service_type,CASE WHEN trf_service_type ='Regular' AND (service_days IS NULL OR service_days='')
                 THEN CONCAT(trf_service_type,' 3 Days')
            WHEN trf_service_type ='Regular' AND service_days IS NOT  NULL
                 THEN CONCAT(trf_service_type,' ',service_days,' Days')
            ELSE trf_service_type END AS trf_service_type,service_days, (select GROUP_CONCAT(test_name) FROM sample_test INNER JOIN tests ON test_id=sample_test_test_id WHERE sample_test_sample_reg_id=sample_reg_id) AS test, (SELECT customer_name FROM cust_customers WHERE customer_id=trf_applicant) AS applicant, (SELECT customer_name FROM cust_customers WHERE customer_id=trf_buyer) AS buyer, trf_registration.trf_sample_desc as sample_desc,"
                . " date_format(sample_registration.received_date,'%d-%b-%Y') AS sample_reg_date,Date_Format(received_date,'%M %e %Y'),trf_applicant FROM trf_registration INNER JOIN  sample_registration ON trf_id=trf_registration_id INNER JOIN  admin_profile ON admin_profile.uidnr_admin=crm_user_id INNER JOIN  admin_users ON admin_users.uidnr_admin=crm_user_id INNER JOIN  contacts ON contact_id=trf_contact WHERE sample_reg_id = " . $sample_reg_id);
            $det_mail = $det_mail_query->result_array()[0];
            $qry_one_query = $this->db->query("SELECT registration_fields_name AS tag_name,registration_fields_type AS tag_type, sample_registration_fields_values AS tag_values
            FROM registration_fields INNER JOIN sample_registration_fields ON sample_registration_fields_id=registration_fields_id WHERE sample_registration_fields_reg_id={$sample_reg_id} AND registration_fields_type='Custom'");
            if ($qry_one_query->num_rows() > 0) {
                $reg_fields = $qry_one_query->result_array()[0];
            } else {
                $reg_fields = "";
            }
            $color = "";
            $Order = "";
            $Style = "";
            if ($reg_fields) {

                foreach ($reg_fields as $key => $value) {
                    if (stripos($value[0], 'Color') !== FALSE) {
                        $color = $value[2];
                    }
                    if (stripos($value[0], 'Order') !== FALSE) {
                        $Order = $value[2];
                    }
                    if (stripos($value[0], 'Style') !== FALSE) {
                        $Style = $value[2];
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

            $formdata = array(
                'GCNO' => isset($det_mail['gc_no']) ? $det_mail['gc_no'] : '&nbsp;', //changes on 21 jan 
                'branch_id' => isset($det_mail['sample_registration_branch_id']) ? $det_mail['sample_registration_branch_id'] : '&nbsp;', //changes on 21 jan 
                'STYLE' => isset($Style) ? $Style : '&nbsp;', //changes on 22 jan 
                'ATTENTION' => isset($contact_name) ? $contact_name : '&nbsp;',
                'REPORT_NO' => isset($det_mail['gc_no']) ? $det_mail['gc_no'] : '&nbsp;', //changes on 22 jan
                'SERVICE' => isset($det_mail['trf_service_type']) ? $det_mail['trf_service_type'] : '&nbsp;',
                'TO' => isset($det_mail['applicant']) ? $det_mail['applicant'] : '&nbsp;',
                'APPLICANT' => isset($det_mail['applicant']) ? $det_mail['applicant'] : '&nbsp;',
                'SAMPLE_DESC' => isset($det_mail['sample_desc']) ? $det_mail['sample_desc'] : '&nbsp;',
                'COLOR' => isset($color) ? $color : '&nbsp;',
                'PO_NO' => isset($Order) ? $Order : '&nbsp;',
                'STYLE_NO' => isset($Style) ? $Style : '&nbsp;',
                'BUYER' => isset($det_mail['buyer']) ? $det_mail['buyer'] : '&nbsp;',
                // 'QUANTITY' => isset($det_mail['quantity']) ? $det_mail['quantity'] : '&nbsp;',
                'REPORT_DATE' => isset($report_due_date) ? $report_due_date : '&nbsp;',
                'TEST' => isset($det_mail['test']) ? $det_mail['test'] : '&nbsp;',
                'SAMPLE_RECIEVE_DATE' => isset($det_mail['sample_reg_date']) ? $det_mail['sample_reg_date'] : '&nbsp;',
                'CUSTOMER_SERVICE_CONTACT' => isset($crm_data) ? $crm_data : '&nbsp;'
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
            // Setting email template
            $html = $this->load->view('template/sample_acknowledge_mail', $formdata, true);
            // $get_tpl = $this->getContentFromTPL($tpl['MailTemplateId'], $formdata);
            $msg = "Sample Registration mail resend success";
            $logDetail = array(
                'source_module' => "Sample_registration",
                'operation' => "send_acknowledgement_mail",
                'uidnr_admin' => $this->session->userdata('user_data')->uidnr_admin,
                'customer_id' => $det_mail['trf_applicant'],
                'log_activity_on' => date("Y-m-d H:i:s"),
                'action_message' => "Sample Registration mail Resend For Basil Report Number {$det_mail['gc_no']}"
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
        $subject = "Sample Acknowledgement " . $formdata['GCNO'] . "-" . $formdata['SAMPLE_DESC'] . "-" . $custom_values;

        // Added by Saurabh on 10-02-2021 for checking whom to send email
        if (INSTANCE_TYPE == "development") {
            $to = array(TO, "developer.cps02@basilrl.com");
            $cc = array(CC, "developer.cps@basilrl.com");
            $bcc = array('developer.cps01@basilrl.com');
        } else {
            $to = array($det_mail['contacts_mail'], $det_mail['customer_email']);
            $cc = array($det_mail['admin_email'], $det_mail['contacts_bcc']);
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



    public function get_worksheet_details($sample_reg_id, $lab_type_id)
    {
        // Get ulr no.
        $ulr_query = $this->db->select('ulr_no')
            ->where('sample_reg_id', $sample_reg_id)
            ->get('sample_registration');
        $data['ulr_no'] = ($ulr_query->num_rows() > 0) ? $ulr_query->row()->ulr_no : '';

        // Get sample branch id
        $branch_id_query = $this->db->select('sample_registration_branch_id')
            ->where('sample_reg_id', $sample_reg_id)
            ->get('sample_registration');
        $data['branch_id'] = $branch_id_query->row()->sample_registration_branch_id;

        // Get customer ID
        $customer_id_query = $this->db->select('trf_applicant')
            ->from('trf_registration tr')
            ->join('sample_registration sr', 'sr.trf_registration_id=tr.trf_id')
            ->where('sample_reg_id', $sample_reg_id)
            ->get();
        if ($customer_id_query->num_rows() > 0) {
            $result = $customer_id_query->row();
            $customer_id = $result->trf_applicant;
        } else {
            $customer_id = "0";
        }

        // new change
         $package = $this->db->where('sample_reg_id', $sample_reg_id)->select('package_name')->join('trf_registration tr', 'tr.trf_package_id= pac.package_id')->join('sample_registration sr', 'sr.trf_registration_id=tr.trf_id')->from('packages pac')->get();
           if($package->num_rows() == 0){
            $get_package = '';
           }else{
           $getPackage = $package->result_array();
           $get_package = $getPackage[0]['package_name'];
           } 
// new change
         $protocol = $this->db->where('sample_reg_id', $sample_reg_id)->select('protocol_name')->join('trf_registration tr', 'tr.trf_protocol_id= pac.protocol_id')->join('sample_registration sr', 'sr.trf_registration_id=tr.trf_id')->from('protocols pac')->get();
           if($protocol->num_rows() == 0){
            $get_protocol = '';
           }else{
           $getProtocol = $protocol->result_array();
           $get_protocol = $getProtocol[0]['package_name'];
           } 
 

        if ($data['branch_id'] == 4) {
            $val = "WHERE sample_test_sample_reg_id={$sample_reg_id} and lab_type_id = {$lab_type_id} ";
            $listQuery = "SELECT  ts.test_id,ts.test_name,ts.test_method,CONCAT(part_name,' - ',parts_desc) AS part_name,(SELECT sample_type_name FROM mst_sample_types WHERE sample_type_id = sr.sample_registration_sample_type_id) AS sample_type_id ,gc_no,barcode_path,ts.units, concat(admin_fname,' ',admin_lname) as assigned_to
            FROM sample_registration sr  
              INNER JOIN sample_test  st ON sr.sample_reg_id = st.sample_test_sample_reg_id 
              INNER JOIN tests   ts ON st.sample_test_test_id = ts.test_id  
              INNER JOIN mst_test_methods method ON method.test_method_id = ts.test_method_id
              INNER JOIN mst_lab_type on ts.test_lab_type_id = lab_type_id
               LEFT JOIN parts   ON st.sample_part_id = part_id  
               LEFT JOIN admin_profile on uidnr_admin = st.assigned_to                      
              {$val} ORDER BY test_name ASC,part_name ASC";
        } else {
            $val = "WHERE sample_test_sample_reg_id={$sample_reg_id} ";
            $listQuery = "SELECT  ts.test_id,ts.test_name,ts.test_method,CONCAT(part_name,' - ',parts_desc) AS part_name,(SELECT sample_type_name FROM mst_sample_types WHERE sample_type_id = sr.sample_registration_sample_type_id) AS sample_type_id ,gc_no,barcode_path,ts.units, method.test_method_name as test_method, lab_type_name
                   FROM sample_registration sr  
                     INNER JOIN sample_test  st ON sr.sample_reg_id = st.sample_test_sample_reg_id 
                     INNER JOIN tests   ts ON st.sample_test_test_id = ts.test_id  
                     INNER JOIN mst_test_methods method ON method.test_method_id = ts.test_method_id
                     INNER JOIN mst_lab_type on ts.test_lab_type_id = lab_type_id
                      LEFT JOIN parts   ON st.sample_part_id = part_id                        
                     {$val} ORDER BY test_name ASC,part_name ASC";
        }
        // echo $listQuery; die;
        $sample_details_query = $this->db->query($listQuery);
        $data['sample_details'] = $sample_details_query->result_array();
        $barcode_path = $sample_details_query->row();
        //        echo "<pre>";
        //        print_r($barcode_path);
        $barcode_path = $barcode_path->barcode_path;
        // 
        $reg_fields_qry = $this->db->query("SELECT registration_fields_name AS tag_name,registration_fields_type AS tag_type, sample_registration_fields_values AS tag_values
       FROM registration_fields INNER JOIN sample_registration_fields ON sample_registration_fields_id = registration_fields_id WHERE sample_registration_fields_reg_id={$sample_reg_id} AND registration_fields_type = 'Custom' AND registration_fields_name IN ('Style No.',  'Color') ");
        $data['reg_fields'] = $reg_fields_qry->result_array();

        // TRF Details
        $trf_detail_query = $this->db->query("SELECT treg.division,trf_id, sr.due_date,treg.tat_date,treg.trf_service_type AS service,(CASE WHEN trf_service_type ='Regular' AND  ( service_days IS NULL OR service_days='') THEN CONCAT(trf_service_type,' 3 Days') WHEN trf_service_type ='Express' THEN CONCAT('Express',' 2 Days') WHEN trf_service_type ='Express3' THEN CONCAT('Express',' 3 Days') WHEN trf_service_type ='Same Day' THEN CONCAT('Same Day',' 0 Days') WHEN trf_service_type ='Urgent'  THEN CONCAT(trf_service_type,' 1 Days') WHEN service_days IS NOT NULL OR service_days!='' THEN CONCAT(trf_service_type,' ',service_days,' Days') END) AS trf_service_type,treg.service_days, DATE_FORMAT(sr.received_date,'%d-%b-%Y %H:%i:%s') AS sample_received_date,sample_desc, division, trf_end_use AS end_use, (SELECT country_name FROM mst_country WHERE country_id = trf_country_orgin) AS contry_org, country_name AS country_dest, gc_no AS report_no, concat(admin_fname,admin_lname) as created_by, regulation_desc, regulation_image,treg.wash_care FROM trf_registration treg INNER JOIN  sample_registration sr ON  sr.trf_registration_id=treg.trf_id
        LEFT JOIN mst_country c ON c.country_id=trf_country_destination left join admin_profile on uidnr_admin = sr.create_by WHERE sr.sample_reg_id={$sample_reg_id}");
        // echo $this->db->last_query();
        $data['trf_detail'] = $trf_detail_query->row_array();

        // Buyer detail query
        $buyer_detail_query = $this->db->query("SELECT cust.customer_name FROM trf_registration trf 
        LEFT JOIN cust_customers cust ON cust.customer_id=trf.trf_buyer INNER JOIN sample_registration sr ON sr.trf_registration_id=trf.trf_id WHERE sr.sample_reg_id={$sample_reg_id} AND cust.customer_type = 'Buyer' ");
        $data['buyer_detail'] = $buyer_detail_query->row_array();

        // Get Application Care Provided Instruction
        $care_instruction_query = $this->db->select('instruction_name as instrunction_name,instruction_image')
            // $care_instruction_query = $this->db->select('GROUP_CONCAT(instruction_name) as instrunction_name,instruction_image')
            ->join('trf_apc_instruction', 'instruction_id = application_care_id')
            ->where('trf_id', $data['trf_detail']['trf_id'])
            ->order_by('image_sequence', 'asc')
            ->get('application_care_instruction');
        if ($care_instruction_query->num_rows() > 0) {
            $instruction = $care_instruction_query->result_array();
            $image = [];
            $name = [];
            foreach ($instruction as $care) {
                $images = getS3Url2($care['instruction_image']);
                $image[] = '<img src="' . $images . '">';
                // $image[] = getS3Url2($care['instruction_image']);
                $name[] = $care['instrunction_name'];
            }
            $img =  implode('  ', $image);
            $care_name =  implode(' , ', $name);
            if (!empty($care_name)) {
                $care_instruction = $img . '<br>' . $care_name;
            } else {
                $care_instruction = $img . '<br>' . $data['trf_detail']['wash_care']; // new wash care change
            }
        } else {
            $care_instruction = "";
        }
        $c_i = $care_instruction;
        // Get dynamic fields value
        $dynamic_value_query = $this->db->select('product_custom_fields')
            ->where('trf_id', $data['trf_detail']['trf_id'])
            ->get('trf_registration');
        if ($dynamic_value_query->num_rows() > 0) {
            $dynamic_value = $dynamic_value_query->row_array()['product_custom_fields'];
            $fields = json_decode($dynamic_value);
        } else {
            $fields = [];
        }
        // print_r($dynamic_value); die;
        $qry_one = $this->db->query("SELECT registration_fields_name AS tag_name,registration_fields_type AS tag_type,sample_registration_fields_values AS tag_values FROM registration_fields INNER JOIN sample_registration_fields ON sample_registration_fields_id=registration_fields_id WHERE sample_registration_fields_reg_id={$sample_reg_id} AND registration_fields_type='Custom' ");
        $data['reg_fields'] = $qry_one->result_array();

        $master_id_query = $this->db->query("SELECT registration_fields_master sample_registration_fields_values, registration_fields_name AS tag_name,registration_fields_type AS tag_type, '' AS tag_values FROM registration_fields INNER JOIN sample_registration_fields ON sample_registration_fields_id=registration_fields_id WHERE sample_registration_fields_reg_id = {$sample_reg_id} AND registration_fields_type='Master'");
        $data['master_id'] = $master_id_query->row();

        // Set data on worksheet view
        $output = '<html xmlns="http://www.w3.org/1999/xhtml">
        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
            <title>WORKSHEET</title>

        </head>
        <style type="text/css">
        .title-r{ font-weight:normal;}
        .title-r span{ font-weight:bold;}
        label{float:left;width:30%; font-weight:normal; text-align:right}
        .logo{text-align:left;float:left}
        .address h5{ border:none; padding:3px 0; font-weight:normal}
        .address{width:50%; float:right;}
        .ca-cust{ clear:both;}
        .big{font-size:16px; font-weight:bold;}
        .vbig{font-size:20px; font-weight:bold; text-transform:uppercase}
        .result-data{width:100%; margin: 0 auto;}
        .result-data span.det{float:left;width:60%; text-align:left;}
        .sep{float:left; width:10%;text-align:center;}
        .fullbordered td{border:1px solid #DDDDDD; }
        .fullbordered{border-collapse: collapse;}
        h2 {
        page-break-before: always;
        }


        </style>
        <body>

            <div style="padding:15px;"> ';
        $regulation_desc = $data['trf_detail']['regulation_desc'];
        $regulation_image = $data['trf_detail']['regulation_image'];

        if ($data['trf_detail']['tat_date'] != '' && $data['trf_detail']['tat_date'] != '0000-00-00 00:00:00') {
            $due_date = date('d-M-Y', strtotime($data['trf_detail']['tat_date']));
        } else {
            $due_date = date('d-M-Y', strtotime($data['trf_detail']['due_date']));
        }
        if ($data['trf_detail']['service'] == 'Express') {
            $style = 'style="color:red;font-size:18px;font-weight:600"';
        } else {
            $style = '';
        }
        $output .= '<table width="100%" border="0" cellspacing="0" cellpadding="0"> 
                <tr><td align="left" width="60%">
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img src="' . base_url() . 'assets/images/logo-login.png" alt="Worksheet Logo" style="max-width:80%;max-width:50%" />                 
                    </td><td colspan=2><table width="100%" border="0" cellpadding="0" cellspacing="2">
                    <tr><td class="big">Service:</td><td  ' . $style . '>' . $data['trf_detail']['trf_service_type'] . '</td><tr>
                    <tr><td class="big">Login Date:</td><td >' . $data['trf_detail']['sample_received_date'] . '</td><tr>
                    <tr><td  class="big">Registered By</td><td>:&nbsp;' . $data['trf_detail']['created_by'] . '</td><tr>
                    <tr><td class="big">Due Date:</td><td >' . $due_date . '</td><tr>    
                    </table>
                    </td>
                </tr>
                <tr>
                    <td colspan="2"  class="big">Report No:' . $data['trf_detail']['report_no'] . '</td>
                </tr>

                
                <tr>
                    <td colspan="2" align="center" class="big"><u>WORKSHEET</u></td>
                </tr><tr>
                    <td align="center" width="50%" style="border:none;" colspan=2>
                    <table width="100%" border="0" cellpadding="0" cellspacing="10">        
                    <tr><td width="50%">Sample Description</td><td>:&nbsp;' . $data['trf_detail']['sample_desc'] . '</td><tr> ';
        if (count($data['reg_fields']) > 0) {
            foreach ($data['reg_fields'] as $reg_field) {

                //$output.='<tr><td>' . $reg_field['tag_name'] . '&nbsp;</td><td>:&nbsp;' . $reg_field['tag_values'] . '</td><tr> ';
            }
        }
        $string = "";
        if (is_array($data['reg_fields']) && count($data['reg_fields']) > 0 or is_array($data['master_id']) && count($data['master_id']) > 0) {
            // $string.= " <table style='width:100%' cellspacing='0' cellpadding='1' border='0.5'>";
            if ($data['reg_fields']) {

                foreach ($data['reg_fields'] as $key => $value) {
                    if ($value['tag_values']) {
                        $string = "<tr>";
                        $string .= "<td><span style='font-size:12px'>" . $value['tag_name'] . "</span></td>" . "<td> <span style='font-size:12px'>: " . $value['tag_values'] . "</span></td>";
                        $string .= "</tr>";
                    }
                }
            }
            if ($data['master_id']) {
                foreach ($data['master_id'] as $key => $id) {
                    $master_query = $this->db->query("SELECT  master_table,selection_query,id_field FROM sys_master_cfg WHERE master_id = {$id[0]}");
                    $master = $master_query->row();
                    if ($id[1]) {
                        $master_value_query = $this->db->query("Select {$master[1]} from {$master[0]} where {$master[2]} = {$id[1]}");
                        $master_value = $master_value_query->row();
                        $id[4] = $master_value[1];
                        if ($id[4]) {
                            $string .= "<tr>";
                            $string .= "<td><span style='font-size:12px'>" . $id[2] . "</span></td>" . "<td><span style='font-size:12px'> : " . $id[4] . "</span></td>";
                            $string .= "</tr>";
                        }
                    }
                }
            }
            // $string.="</table>";
        }

        $output .= $string;
        $output .= '<tr><td>End Use</td><td>:&nbsp;' . $data['trf_detail']['end_use'] . '</td><tr>  
                    <tr><td>Country of Origin</td><td>:&nbsp;' . $data['trf_detail']['contry_org'] . '</td><tr>
                    <tr><td>Country of Destination</td><td>:&nbsp;' . $data['trf_detail']['country_dest'] . '</td><tr>
                    <tr><td>Barcode</td><td><img class="barcode" alr="" src="' . $barcode_path . '" /></td><tr>
                    <tr><td>Sample Received Date</td><td>:&nbsp;' . $data['trf_detail']['sample_received_date'] . '</td><tr>
                    <tr><td>Testing Period</td><td>:&nbsp;' . $data['trf_detail']['sample_received_date'] . ' to ' . $due_date . '</td><tr>';
        if ($data['trf_detail']['division'] != "4") {
            $output .= '<tr><td>Applicant Provided Care Instruction</td><td>:&nbsp;' . $c_i . '</td><tr>
                    <tr><td>Buyer Name</td><td>:&nbsp;' . $data['buyer_detail']['customer_name'] . '</td><tr>';
        }

        $output .=   '</table></td><tr>';
        if (!empty($fields)) {
            $output .= '<table width="100%" border="0" cellspacing="0" cellpadding="0"> 
            ';
            foreach ($fields as $key => $value) {
                $output .= '<tr><td align="left" width="50%">' . $value[0] . '</td><td>:&nbsp;' . $value[1] . '</td><tr>';
            }
            $output .= "</table></td></tr>";
        }
        $output .= '<tr><td align="center" width="50%" style="border:none;" colspan=2>';
        $output .= '<table width="100%" style="margin-bottom:20px !important" border="0" cellpadding="5" cellspacing="10" class = "fullbordered">';
        $slno = 1;
            // new change
        if (!empty($get_package)) {
            $output .= '<tr><td colspan="5" class="big" style="text-align:center;">'.$get_package.'</td></tr>';
        }
          // new change
        if (!empty($get_protocol)) {
            $output .= '<tr><td colspan="5" class="big" style="text-align:center;">'.$get_protocol.'</td></tr>';
        }
        $output .= '<tr><td class="big">SL.No</td><td class="big">Test Conducted</td><td class="big">Test Method</td><td class="big">Components</td><td class="big">Results</td><td class="big">Lab Type</td>';
        if ($data['branch_id'] == 2) {
            $output .= '<td class="big">Assigned To</td>';
        }
        $output .= '</tr>';
        foreach ($data['sample_details'] as $sample) {
            $output .= '<tr><td>' . $slno . '</td><td>' . $sample['test_name'] . '</td><td>' . $sample['test_method'] . '</td><td>' . $sample['part_name'] . '</td><td>Refer Results</td></td><td>' . $sample['lab_type_name'] . '</td>';
            if ($data['branch_id'] == 4) {
                if ($sample['assigned_to'] == "") {
                    $assigned_to = "Not Assigned";
                } else {
                    $assigned_to = $sample['assigned_to'];
                }
                $output .= '<td>' . $assigned_to . '</td>';
            }
            $output .= '</tr>';
            $slno++;
        }
        $output .= '</table>';


        $output .= '</td></tr></table>';

        $count = 1;
        $sl_no = 1;
        foreach ($data['sample_details'] as $sample) {

            $units = $sample['units'];
            $qry = $this->db->query("SELECT * FROM test_worksheet_report_format  "
                . " WHERE test_wrf_test_id={$sample['test_id']} and customer_id={$customer_id}");
            $data = $qry->result_array();
            if (count($data) < 2) {
                $qry = $this->db->query("SELECT * FROM test_worksheet_report_format  "
                    . " WHERE test_wrf_test_id={$sample['test_id']} and customer_id=0");
                if ($qry->num_rows() > 0) {
                    $data = $qry->result_array()[0];
                } else {
                    $data['worksheet_store_format'] = "";
                    $data['wrf_rows'] = "";
                    $data['wrf_columns'] = "";
                }
            }

            $json_data = json_decode(stripslashes($data['worksheet_store_format']));
            $rownum = $data['wrf_rows'];
            $colnum = $data['wrf_columns'];
            $unit_query = $this->db->query("SELECT uc_rep_unit_id FROM unit_conversion  WHERE uc_unit_id={$units}");

            if ($unit_query->num_rows() > 0) {
                $unit_details = $unit_query->result_array()[0];
            } else {
                $unit_details = [];
            }
            if (count($unit_details) > 0 && !empty($unit_details)) {
                $p = 0;
                foreach ($unit_details as $unit_det) {
                    if ($p === 0 && !empty($unit_det) && $unit_det != 0) {
                        $units = $unit_det;
                    } else if (!empty($unit_det) && $unit_det != 0) {
                        $units .= ',' . $unit_det;
                    }
                    $p++;
                }
            }
            if (!empty($json_data)) {
                foreach ($json_data as $item => $value) {
                    for ($i = 1; $i <= $colnum; $i++) {
                        $id = 'id' . $i;
                        if (!empty($json_data[$item]->$id)) {
                            $param_unit_query = $this->db->query("SELECT test_parameters_unit FROM test_parameters WHERE test_parameters_id={$json_data[$item]->$id}");
                            $result = $param_unit_query->result();
                            if (count($result) > 0) {
                                $u_d = $result[0]->test_parameters_unit;
                                if (!empty($u_d) && $u_d != 0) {
                                    $units .= ',';
                                    $units .= $u_d;
                                }
                            }
                        }
                    }
                }
            }
            $unit_list = '';
            $unit_qry = $this->db->query("SELECT unit  FROM units WHERE unit_id IN({$units})");
            // echo $this->db->last_query(); die;
            if ($unit_qry->num_rows() > 0) {
                $unit_det = $unit_qry->result_array()[0];

                foreach ($unit_det as $unit_values) {
                    $unit_list .= '&nbsp;(&nbsp;&nbsp;&nbsp;&nbsp;) &nbsp;&nbsp;' . $unit_values;
                }
            }
            if (!empty($regulation_desc)) {
                $output .= '<table width="100%" border="0" cellspacing="0" cellpadding="0"><tr><th>Regulation Description :</th><td>' . $regulation_desc . '</td></tr></table>';
            }

            if (!empty($regulation_image)) {
                $output .= '<table width="100%" border="0" cellspacing="0" cellpadding="0"><tr><th>Regulation Image : </th></tr><tr><td style="text-align:center !important"><img src=' . $regulation_image . ' width="100%" heigth="100%" style="text-align:center"></td></tr></table>';
            }

            $output .= '<table width="100%" border="0" cellspacing="0" cellpadding="0" > 
                <tr><td align="center" width="80%">                                
                    </td><td colspan=2><table width="100%" border="0" cellpadding="0" cellspacing="10">
                    <tr><td class="big">Product:</td><td  >' . $sample['sample_type_id'] . '</td><tr>
                    <tr><td class="big">Part:</td><td  >' . $sample['part_name'] . '</td><tr>    
                    </table>
                    </td>
                </tr>';


            $output .= '<tr>
                    <td align="center" width="50%" style="border:none;" colspan=2>
                    <table width="100%" border="0" cellpadding="0" cellspacing="10" style="border:.5px solid #dddddd">        
                    <tr><td><b>' . $sl_no . '. Test Name</b></td><td>' . $sample['test_name'] . '</td><td><b>Test Method</b></td><td>' . $sample['test_method'] . '</td><tr> 
                    <tr><td><b>&nbsp;&nbsp;&nbsp;&nbsp;  Unit</b></td><td colspan=3>' . $unit_list . '</td><tr>              
                    <!--<tr><td>Equipment</td><td><input type="text" style="width:250px;height:50" readonly/></td><td>Testing Note</td><td><input type="textarea" style="width:250px;height:100" readonly/></td><tr>
                    <tr><td>Uncertainity</td><td><input type="text" style="width:250px;height:50" readonly/></td><td>Test Method Deviation</td><td><input type="textarea" style="width:250px;height:100" readonly/></td></tr>-->
                    </table>';
            $output .= '<tr><td align="center" width="50%" style="border:none;" colspan=2>';
            error_reporting(E_ERROR | E_PARSE);
            $check = "indo";
            $outp = '';
            try {
                if (!empty($json_data)) {
                    $display = '<table width="100%" border="0" cellpadding="5" cellspacing="10" class = "fullbordered">';

                    foreach ($json_data as $item => $value) {
                        $display .= '<tr style="height:24px">';
                        $rowmerge = $json_data[$item]->merge;
                        $drag = $json_data[$item]->drag;
                        $new = '';

                        if (count($rowmerge) > 0 && !empty($rowmerge)) {
                            $list = 0;
                            $first = '';
                            foreach ($rowmerge as $key => $val) {
                                if ($val == 0) {
                                    $first = 1;
                                }
                                if ($val != '|') {
                                    if ($val + 1 == 2 && $first != '')
                                        $new[$list] = $first;

                                    $new[$list] = $val + 1;
                                }
                                if ($val == '|') {
                                    $list++;
                                }
                            }
                        }



                        for ($i = 1; $i <= $colnum; $i++) {
                            $style = "";
                            $j = $i;
                            $startcell = '';
                            $endcell = '';
                            $length = '';
                            if (count($new) > 0 && !empty($new)) {
                                foreach ($new as $key => $val) {
                                    if (in_array($i, $new[$key])) {
                                        $startcell = reset($new[$key]);
                                        $endcell = end($new[$key]);
                                        $length = count($new[$key]);
                                    }
                                }
                            }

                            if (count($drag) > 0 && !empty($drag)) {
                                if (!in_array($j - 1, $drag)) {
                                    $style = 'style="font-weight:bold"';
                                }
                            }
                            $index = 'index' . $i;
                            $id = 'id' . $i;
                            if ($i === $startcell) {
                                $display .= '<td ' . $style . ' colspan=' . $length . '>';
                            }
                            if ($i === $startcell || $i > $startcell && $i <= $endcell) {
                                $display .= ' ';
                            } else {
                                $display .= '<td ' . $style . '>';
                            }
                            /* Adding parameter */
                            if (!empty($json_data[$item]->$id)) {
                                $query = $this->db->query("SELECT test_parameters_id AS id, test_parameters_disp_name AS parameter, test_parameters_name as for_name, type,show_in_report,formula,mandatory, '' as `value`,field_type FROM test_parameters WHERE test_parameters_id={$json_data[$item]->$id}");

                                $parameter = $query->result_array()[0];
                            } else {
                                $parameter = '';
                            }
                            $param_val = '';
                            if (count($parameter) > 0 && !empty($parameter)) {
                                $parameter_id = $parameter['id'];
                                if ($parameter['type'] == 'Calculated') {
                                    //$param_val =   '<input type="text" style="width:100%;height:50" value="' . $parameter['formula'] . '" readonly/>';
                                    //$param_val =   '<input type="text" style="width:100%;height:50" value=" " readonly/>';                             
                                } else {
                                    if ($parameter['field_type'] == 'textfield') {
                                        //$param_val =  '<input type="text" style="width:100%;height:50" readonly/>';
                                    } else if ($parameter['field_type'] == 'lovcombo') {
                                        $parameterQuery = $this->db->query("SELECT test_parameter_field_dis_name FROM test_parameter_field_value where test_parameters_id = {$parameter_id}");
                                        $parameterValues = $parameterQuery->row();
                                        $param_val = $parameter['parameter'] . '(' . $parameter['for_name'] . ')';
                                        $param_val .= ' <table width="100%" border="0" cellpadding="0" cellspacing="0">';
                                        foreach ($parameterValues as $lovvalues) {
                                            $output .= '<tr><td><input type="checkbox"/></td><td align="left">' . $lovvalues . '</td></tr>';
                                        }
                                        $param_val .= '</table>';
                                    } else if ($parameter['field_type'] == 'combo') {
                                        $parameterQuery = $this->db->query("SELECT test_parameter_field_dis_name FROM test_parameter_field_value where test_parameters_id = {$parameter_id}");
                                        $parameterValues = $parameterQuery->row();
                                        $param_val = ' <table width="100%" border="0" cellpadding="0" cellspacing="0">';
                                        foreach ($parameterValues as $combovalues) {
                                            $param_val .= '<tr><td><input type="radio"/></td><td align="left">' . $combovalues . '</td></tr>';
                                        }
                                        $param_val .= '</table> ';
                                    } else if ($parameter['field_type'] == 'textarea') {
                                        $param_val = '<input type="textarea" style="width:100%;height:100;background-color:#f8f9f9;border:none;" readonly/> ';
                                    }
                                }
                            }
                            /**/

                            if (empty($json_data[$item]->$id) || $json_data[$item]->$id == '') {
                                $display .= $json_data[$item]->$index;
                            } else {
                                $display .= $param_val;
                            }

                            if ($i === $endcell) {
                                $display .= '</td>';
                            }
                            if ($i === $startcell || $i > $startcell && $i <= $endcell) {
                            } else {
                                $display .= '</td>';
                            }
                        }
                        $display .= '</tr>';
                    }
                    $display .= '</table></td></tr></table>';

                    $output .= $display;
                }
            } catch (Exception $e) {
                echo 'Message: ' . $e->getMessage();
            }
            if (!empty($regulation_desc)) {
                $output .= '<table width="100%" border="0" cellspacing="0" cellpadding="0"><tr><th>Regulation Description :</th><td>' . $regulation_desc . '</td></tr></table>';
            }

            if (!empty($regulation_image)) {
                $output .= '<table width="100%" border="0" cellspacing="0" cellpadding="0"><tr><th>Regulation Image : </th></tr><tr><td style="text-align:center !important"><img src=' . $regulation_image . ' width="100%" heigth="100%" style="text-align:center"></td></tr></table>';
            }
            if (count($data['sample_details']) != $count)
                //$output.='<h2></h2>';
                $count++;
            $sl_no++;
        }
        return $output;
    }

    // Generate performa invoice

    public function generate_performa_INVOICE($sample_reg_id, $data)
    {
        $this->db->trans_start();
        $this->db->select('proforma_invoice_id');
        $this->db->from('invoice_proforma');
        $this->db->where('proforma_invoice_sample_reg_id', $sample_reg_id);
        $p_id = $this->db->get()->result();
        if ($p_id && count($p_id) > 0) {
            $p_id = $p_id[0]->proforma_invoice_id;
        } else {
            $p_id = "";
        }
        $customer_id = $data[0]->sample_customer_id;
        $this->db->select('c.customer_id,c.city,c.po_box,location.location_name as location,provinces.province_name as province,country.country_name as country');
        $this->db->from('cust_customers c');
        $this->db->join('mst_locations location', 'location.location_id = c.cust_customers_location_id', 'left');
        $this->db->join('mst_provinces provinces', 'provinces.province_id = c.cust_customers_province_id', 'left');
        $this->db->join('mst_country country', 'country.country_id = c.cust_customers_country_id', 'left');
        $this->db->where('c.customer_id', $customer_id);
        $add = $this->db->get()->result();

        $address = $add[0]->location . "," . $add[0]->po_box . "  P O  ," . $add[0]->city . "," . $add[0]->province . "," . $add[0]->country;

        // Get TRF currency
        $currency_id = $this->get_data_by_id_array('trf_registration', 'open_trf_currency_id', $data[0]->trf_registration_id, 'trf_id');

        // Get Distinct type for pricing
        $distinct_type = $this->db->select('sample_test_quote_id,sample_test_protocol_id,sample_test_package_id')->where('sample_test_sample_reg_id', $sample_reg_id)->group_by(['sample_test_quote_id', 'sample_test_protocol_id', 'sample_test_package_id'])->get('sample_test')->row_array();
        // Get Price by distinct type
        $test_price = 0;
        $package_price = 0;
        $protocol_price = 0;
        $open_test_price = 0;
        $open_protocol_price = 0;
        $open_package_price = 0;
        // Price for the Open TRF Test
        if ($distinct_type['sample_test_quote_id'] == 0 && $distinct_type['sample_test_protocol_id'] == 0 && $distinct_type['sample_test_package_id'] == 0) {
            $this->db->select('SUM(price) as test_price');
            $this->db->from('tests');
            $this->db->join('sample_test st', 'st.sample_test_test_id=tests.test_id', 'inner');
            $this->db->join('pricelist', 'st.sample_test_test_id=pricelist_test_id', 'inner');
            $this->db->where('currency_id', $currency_id['open_trf_currency_id']);
            $this->db->group_start();
            $this->db->where('sample_test_quote_id', '0');
            $this->db->where('sample_test_protocol_id', '0');
            $this->db->where('sample_test_package_id', '0');
            $this->db->group_end();
            $this->db->where('sample_test_sample_reg_id', $sample_reg_id);
            $open_test_value = $this->db->get()->row_array();
            $open_test_price = $open_test_value['test_price'];
        }


        if ($distinct_type['sample_test_protocol_id'] > 0) {
            // Price for Open TRF Protocol
            $this->db->select('price as test_price');
            $this->db->join('pricelist', 'sample_test_protocol_id=type_id', 'inner');
            $this->db->where('currency_id', $currency_id['open_trf_currency_id']);
            $this->db->where('sample_test_sample_reg_id', $sample_reg_id);
            $this->db->group_by('sample_test_protocol_id');
            $open_protocol_value = $this->db->get('sample_test')->row_array();
            $open_protocol_price = $open_protocol_value['test_price'];
        }

        if ($distinct_type['sample_test_package_id'] > 0) {
            // Price for Open TRF Package
            $this->db->select('price as test_price');
            $this->db->join('pricelist', 'sample_test_package_id=type_id', 'inner');
            $this->db->where('currency_id', $currency_id['open_trf_currency_id']);
            $this->db->where('sample_test_sample_reg_id', $sample_reg_id);
            $open_package_value = $this->db->get('sample_test')->row_array();
            $open_package_price = $open_package_value['test_price'];
        }

        if ($distinct_type['sample_test_quote_id'] > 0) {
            $this->db->select('SUM(rate_per_test) as test_price');
            $this->db->where('sample_test_quote_type', 'Test');
            $this->db->where('sample_test_sample_reg_id', $sample_reg_id);
            $test_value = $this->db->get('sample_test')->row_array();
            $test_price = $test_value['test_price'];

            $this->db->select('rate_per_test as test_price');
            $this->db->where('sample_test_quote_type', 'Package');
            $this->db->where('sample_test_sample_reg_id', $sample_reg_id);
            $this->db->order_by('rate_per_test', 'desc');
            $this->db->limit(1);
            $package_value = $this->db->get('sample_test')->row_array();
            $package_price = $package_value['test_price'];


            $this->db->select('rate_per_test as test_price');
            $this->db->where('sample_test_quote_type', 'Protocol');
            $this->db->where('sample_test_sample_reg_id', $sample_reg_id);
            $this->db->order_by('rate_per_test', 'desc');
            $this->db->limit(1);
            $protocol_value = $this->db->get('sample_test')->row_array();
            $protocol_price = $protocol_value['test_price'];
        }
        $pi_created_by = $data['created_by'];        // added by millan on 25-06-2021

        $performa = array();
        $proforma['signoff_authority'] = "";
        $proforma['proforma_invoice_date'] = date("Y-m-d h:i:s");
        $proforma['invoice_proforma_customer_id'] = $customer_id;
        $proforma['invoice_proforma_invoice_status_id'] = 1;
        $proforma['proforma_invoice_sample_reg_id'] = $sample_reg_id;
        $proforma['proforma_invoice_job_type'] = 'Quote';
        $proforma['proforma_client_address'] = $address;
        $proforma['created_by'] = $pi_created_by;        // added by millan on 25-06-2021
        $proforma['total_amount'] = $test_price + $package_price + $protocol_price + $open_test_price + $open_protocol_price + $open_package_price;
        // pre_r($proforma);
        // die;
        if (empty($p_id)) {

            $this->db->insert('invoice_proforma', $proforma);
            $p_id = $this->db->insert_id();
            $division['division_id'] = $data[0]->division_id;
            $this->db->select('division_code');
            $this->db->from('mst_divisions');
            $this->db->where('division_id', $division['division_id']);
            $division_code = $this->db->get()->result();
            $division_code = $division_code[0]->division_code;
            $rand = str_pad($p_id, 5, "0", STR_PAD_LEFT);
            $this->db->select('branch_code');
            $this->db->from('mst_branches');
            $this->db->where('branch_id', $data[0]->sample_registration_branch_id);
            $branch_code = $this->db->get()->result();
            $branch_code = $branch_code[0]->branch_code;
            $pi_number['proforma_invoice_number'] = 'PI' . $branch_code . $division_code . date('Y') . '-' . $rand;
            // Added by saurabh on 28-01-2021
            // $this->db->update('sample_registration',['status' => 'Proforma Generated'],['sample_reg_id' => $sample_reg_id]);
            // Added by saurabh on 28-01-2021
        } else {
            $this->db->where('proforma_invoice_id', $p_id);
            $state = $this->db->update('invoice_proforma', $proforma);
            $this->db->select('proforma_invoice_number');
            $this->db->from('invoice_proforma');
            $this->db->where('proforma_invoice_sample_reg_id', $sample_reg_id);
            $proforma_invoice_number = $this->db->get()->result();
            $proforma_invoice_number = $proforma_invoice_number[0]->proforma_invoice_number;
            if (!empty($proforma_invoice_number)) {

                $keywords = preg_split("/-R./", $proforma_invoice_number);
                if (!empty($keywords[1])) {
                    preg_match('/-R.[0-9]{0,15}/', $proforma_invoice_number, $matches);
                    preg_match('/[0-9][0-9]/', $matches[0], $digits);
                    $number = ($digits[0] * 1) + 1;
                    $rand_rev = str_pad($number, 2, "0", STR_PAD_LEFT);
                    $rev_proforma_invoice_number = str_replace('-R.' . $keywords[1], '-R.' . $rand_rev, $proforma_invoice_number);
                } else {
                    $number = 1;
                    $rand_rev_number = str_pad($number, 2, "0", STR_PAD_LEFT);
                    $rev_proforma_invoice_number = $proforma_invoice_number . "-R." . $rand_rev_number;
                }
                $pi_number['proforma_invoice_number'] = $rev_proforma_invoice_number;
                // Added by saurabh on 28-01-2021
                // $this->db->update('sample_registration',['status' => 'Proforma Generated'],['sample_reg_id' => $sample_reg_id]);
                // Added by saurabh on 28-01-2021

            }
        }
        $sample_status3 = $data[0]->status;

        $logDetails = array(
            'module' => 'Samples',
            'old_status' => $sample_status3,
            'new_status' => 'Proforma Invoice Created',
            'sample_reg_id' => $sample_reg_id,
            'sample_assigned_lab_id' => '',
            'action_message' => 'Proforma Invoice Created',
            'report_id' => '',
            'report_status' => '',
            'test_ids' => '',
            'test_names' => '',
            'test_newstatus' => '',
            'test_oldStatus' => '',
            'test_assigned_to' => '',
            'uidnr_admin'   => $this->admin_id(),
            'operation' => 'generate_performa_INVOICE'
        );

        $this->save_user_log($logDetails);
        $this->db->where('proforma_invoice_id', $p_id);
        $status1 =  $this->db->update('invoice_proforma', $pi_number);
        $this->db->trans_complete();
        if ($status1) {
            return true;
        } else {
            return false;
        }
    }

    public function get_test_name($product_id)
    {
        $admin_currency_id = 1;
        $this->db->select("test_id, concat(test_name,'(',test_method,')') AS test_name, pricelist.price AS test_price");
        $this->db->from('tests');
        $this->db->join('test_sample_type stm', 'test_id = stm.test_sample_type_test_id');
        $this->db->join('pricelist', 'pricelist.pricelist_test_id=tests.test_id AND pricelist.currency_id=' . $admin_currency_id, 'left');
        $this->db->where('test_sample_type_sample_type_id', $product_id);
        $this->db->where('test_status', 'Active');
        $query = $this->db->get();
        // echo $this->db->last_query();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return [];
    }

    /* added by millan on 18-Jan-2021 */
    public function fetch_data_for_gc($cond)
    {
        $query = $this->db->select('sr.gc_no')
            ->from('sample_registration sr')
            ->where('sr.sample_reg_id', $cond)->get();
        if ($query->num_rows() > 0) {
            return $query->row();
        } else {
            return false;
        }
    }

    /* added by millan on 18-Jan-2021 */
    public function download_qr($table, $where = array())
    {
        $this->db->select('qr_code_name');
        $path = $this->db->get_where($table, $where = array());
        if ($path) {
            return $path->row();
        } else {
            return false;
        }
    }

    /* added by millan on 19-Jan-2021 */
    public function fetch_qr_code($cond)
    {
        $query = $this->db->select('gr.qr_code_name')
            ->from('generated_reports gr')
            ->where('gr.sample_reg_id', $cond)->get();
        if ($query->num_rows() > 0) {
            return $query->row();
        } else {
            return false;
        }
    }

    /* added by millan on 19-Jan-2021 for scan and download pdf */
    // public function download_pdf_aws($cond)
    // {
    //     $this->db->select('gr.manual_report_file');
    //     $this->db->from('generated_reports gr');
    //     $path = $this->db->where($cond)->get();
    //     echo $this->db->last_query();
    //     if ($path) {
    //         return $path->row();
    //     } else {
    //         return false;
    //     }
    // }

    public function download_pdf_aws($sample_reg_id, $report_id)
    {
        $this->db->select('gr.manual_report_file, gr.original_file_name');
        $this->db->from('generated_reports gr');
        $this->db->where('report_id', $report_id);
        $path = $this->db->where('sample_reg_id', $sample_reg_id)->get();
        if ($path) {
            return $path->row();
        } else {
            return false;
        }
    }
    /**-------------------clone sample------------------------------------- */
    public function get_sample_data($id)
    {
        $res = $this->db->select('*')->from('sample_registration')->where('sample_reg_id', $id)->get();

        if ($res->num_rows() > 0) {

            return $res->row();
        } else {
            return false;
        }
    }

    public function save_sample_clone_data($record)
    {
        $division_code_query = $this->db->select('division_code')
            ->from('trf_registration')
            ->join('mst_divisions', 'division_id=division')
            ->where('trf_id', $record['trf_registration_id'])
            ->get();
        if ($division_code_query->num_rows() > 0) {
            $division_code = $division_code_query->row()->division_code;
        }


        $branch_code_query = $this->db->select('branch_code')->get_where('mst_branches', ['branch_id' => $record['sample_registration_branch_id']]);
        if ($branch_code_query->num_rows() > 0) {
            $branch_code = $branch_code_query->row()->branch_code;
        }
        if ($record['sample_registration_branch_id'] != 4) {
            // Get serial number
            $serial_no_query = $this->db->select_max('serial_no')
                ->from('sample_number_confiq')
                ->where('year(created_on)', date('Y'))
                ->where('branch_id', $record['sample_registration_branch_id'])
                ->where('division_id', $record['division_id'])
                ->get()->row();

            $serial_number = $serial_no_query->serial_no + 1;
            // echo $serial_number; die;
            $confiq['branch_id'] = $record['sample_registration_branch_id'];
            $confiq['division_id'] = $record['division_id'];
            $confiq['serial_no'] = $serial_number;
            $confiq['created_on'] = date("Y-m-d H:i:s");
            $this->db->insert("sample_number_confiq", $confiq);
            // GC number
            $rand = str_pad($serial_number, 5, "0", STR_PAD_LEFT);
            $unique = 'BL-' . $division_code . '-' . date('y') . '-' . $rand;
            $record['gc_no'] = $unique;
        } else {
            // Get serial number 
            $date = numberOfDayPassed();
            $serial_no_query = $this->db->select_max('serial_no')
                ->from('sample_number_confiq')
                ->where('year(created_on)', date('Y'))
                ->where('branch_id', $record['sample_registration_branch_id'])
                ->where('division_id', $record['division_id'])
                ->where('days_number', $date)
                ->get()->row();
            // echo $this->db->last_query();
            $serial_number = $serial_no_query->serial_no + 1;
            // echo $serial_number; die;
            $confiq['branch_id'] = $record['sample_registration_branch_id'];
            $confiq['division_id'] = $record['division_id'];
            $confiq['serial_no'] = $serial_number;
            $confiq['created_on'] = date("Y-m-d H:i:s");
            $confiq['days_number'] = $date;
            $this->db->insert("sample_number_confiq", $confiq);
            // GC number
            $rand = str_pad($serial_number, 5, "0", STR_PAD_LEFT);
            $unique = 'BL-' . $division_code . '-' . date('y') . '-' . $rand;
            $record['gc_no'] = $unique;
        }


        // Generate ulr number
        $save_ulr = $this->db->insert('cps_ulr', ['created_date' => date('Y-m-d H:i:s')]);
        $last_id = $this->db->insert_id();
        $ulrid_number = str_pad($last_id, 8, "0", STR_PAD_LEFT);
        $ulr_no = 'TC6371' . date('y') . '0' . $ulrid_number;
        $record['ulr_no'] = $ulr_no;

        // Generate barcode
        $filepath = base_url() . "assets/barcode/";
        @chmod($filepath, 0755);
        $localpath = "assets/barcode/";
        $text = $unique;
        $size = "30";
        $orientation = "horizontal";
        $code_type = "code128";
        $print = true;
        $sizefactor = "1";
        $text = str_replace('/', '-', $text);
        $image_data = barcode($filepath, $text, $size, $orientation, $code_type, $print, $sizefactor);
        $filename = $text . ".png";
        $file = $this->uploadBarcode($filename);
        $barcode_file = $file['aws_path'];

        $record['barcode_no'] = $filename;
        $record['barcode_path'] = $barcode_file;
        $record['status'] = 'Registered';
        $save_sample = $this->db->insert('sample_registration', $record);
        $sample_reg_id = $this->db->insert_id();

        $trf_data['trf_status'] = "Sample Registered";
        $this->db->update('trf_registration', $trf_data, ['trf_id' => $record['trf_registration_id']]);

        if ($sample_reg_id) {
            return $sample_reg_id;
        } else {
            return false;
        }
    }

    public function get_sample_test($id)
    {

        $res = $this->db->select('*')->from('sample_test')->where('sample_test_sample_reg_id', $id)->get();

        if ($res->num_rows() > 0) {

            return $res->result();
        } else {
            return false;
        }
    }
    /**---------end----------clone sample------------------------------------- */

    // Added by Saurabh on 08-02-2021 for login cancel sample
    public function login_cancel_sample($sample_reg_id, $comment)
    {
        $update = $this->db->update('sample_registration', ['status' => 'Login Cancelled', 'comment' => $comment], ['sample_reg_id' => $sample_reg_id]);
        if ($update) {
            return true;
        }
        return false;
    }
    // Added by Saurabh on 08-02-2021 for login cancel sample

    // Added by saurabh to check GC Number on 10-02-2021
    public function check_gc_number($trf_id, $branch, $gc_no)
    {
        $division_code_query = $this->db->select('division_code')
            ->from('trf_registration')
            ->join('mst_divisions', 'division_id=division')
            ->where('trf_id', $trf_id)
            ->get();
        if ($division_code_query->num_rows() > 0) {
            $division_code = $division_code_query->row()->division_code;
        }

        $branch_code_query = $this->db->select('branch_code')->get_where('mst_branches', ['branch_id' => $branch]);
        if ($branch_code_query->num_rows() > 0) {
            $branch_code = $branch_code_query->row()->branch_code;
        }

        $rand = str_pad($gc_no, 5, "0", STR_PAD_LEFT);
        $unique = 'GC' . $branch_code . $division_code . date('y') . $rand;
        $check = $this->db->get_where('sample_registration', ['gc_no' => $unique]);
        if ($check->num_rows() > 0) {
            return ["status" => 1, "gc_number" => $unique];
        } else {
            return ["status" => 0, "gc_number" => $unique];
        }
    }
    // Added by saurabh to check GC Number on 10-02-2021

    // Added by Saurabh to get test lab name on 19-02-2021
    public function get_lab_name($sample_reg_id)
    {
        $query = $this->db->select('distinct(lab_type_id),lab_type_name')
            ->join('tests', 'test_id = sample_test_test_id')
            ->join('mst_lab_type', 'tests.test_lab_type_id = lab_type_id')
            ->where('sample_test_sample_reg_id', $sample_reg_id)
            ->get('sample_test');
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return [];
    }
    // Added by Saurabh to get test lab name on 19-02-2021

    public function delete_record_finding_data($record_id)
    {

        $query = $this->db->where('record_finding_id', $record_id)->delete('record_finding_details');
        if ($query) {
            return true;
        } else {
            return false;
        }
    }

    //    added by ajit 30-03-2021
    public function update_relase_to($where_in)
    {


        $this->db->where_in('sample_reg_id', $where_in);
        $result =  $this->db->update('sample_registration', ['released_to_client' => '1']);
        //   echo $this->db->last_query();die;
        if ($result) {
            return true;
        } else {
            return false;
        }
    }
    // end

    public function get_sample_log($sample_id)
    {
        $sample_query = $this->db->select('operation, log_activity_on as taken_at, old_status, new_status, concat(admin_fname," ",admin_lname) as taken_by')
            ->join('admin_profile', 'sample_reg_activity_log.uidnr_admin = admin_profile.uidnr_admin')
            ->where('sample_reg_id', $sample_id)
            // ->order_by('sample_reg_log_id   ', 'desc')
            ->get_compiled_select('sample_reg_activity_log');

        // Changed by Saurabh on 26-07-2021
        $trf_id_query = $this->db->select('trf_registration_id')->where('sample_reg_id', $sample_id)->get('sample_registration');
        $trf_id_result = $trf_id_query->row_array();
        $trf_id = $trf_id_result['trf_registration_id'];
        // Get trf log
        $trf_log_query = $this->db->select('operation, log_activity_on as taken_at, old_status, new_status, concat(admin_fname," ",admin_lname) as taken_by')
            ->join('admin_profile', 'jobs_activity_log.uidnr_admin = admin_profile.uidnr_admin')
            ->where('trf_id', $trf_id)
            // ->order_by('jobs_activity_log_id', 'desc')
            ->get_compiled_select('jobs_activity_log');
        // Changed by Saurabh on 26-07-2021

        $query = $this->db->query($sample_query . ' UNION ' . $trf_log_query . ' order by taken_at desc');
        if ($query->num_rows() > 0) {
            $sample_log = $query->result_array();
            // Changed by Saurabh on 26-07-2021
            // $trf_log = $trf_log_query->result_array();
            // $data = array_merge($sample_log,$trf_log);
            // return $data;
            // Changed by Saurabh on 26-07-2021
            return $sample_log;
        }
        return [];
    }

    // function to send status update to the customer
    public function get_customers_for_update()
    {
        $query = $this->db->select('email,customer_id')
            ->where('sample_week_report', '1')
            // ->where('isactive', 'Active')
            ->get('cust_customers');
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return [];
    }

    public function get_sample_status_report($customer_id)
    {
        $this->db->select('sample_reg_id, gc_no, trf_ref_no, status, client.customer_name as client, buyer.customer_name as buyer, tat_date, test_result');
        $this->db->join('trf_registration tr', 'sr.trf_registration_id = trf_id');
        $this->db->join('cust_customers client', 'client.customer_id = open_trf_customer_id');
        $this->db->join('cust_customers buyer', 'buyer.customer_id = trf_buyer', 'left');
        $this->db->join('record_finding_details rfd', 'rfd.sample_registration_id = sr.sample_reg_id', 'left');
        $this->db->where('released_to_client', '0');
        $this->db->where('tr.open_trf_customer_id', $customer_id);
        $query = $this->db->get('sample_registration sr');
        // echo $this->db->last_query(); die;
        if ($query->num_rows()) {
            return $query->result_array();
        }
        return [];
    }
    // function to send status update to the customer ends here

    public function get_revenue()
    {
        $revenue_by_year = $this->db->select('SUM(Invoice_Amount) as amount,YEAR(generated_date) as year')
            ->group_by('YEAR(manual_invoice.generated_date)')
            ->order_by('year', 'desc')
            ->get('manual_invoice');
        if ($revenue_by_year->num_rows() > 0) {
            $data['revenue_by_year'] = $revenue_by_year->result_array();
        }

        $revenue = $this->db->query('SELECT * from (SELECT SUM(Invoice_Amount) as amount,MONTHNAME(generated_date) as month,YEAR(generated_date) as year FROM `manual_invoice` GROUP BY YEAR(manual_invoice.generated_date),MONTH(manual_invoice.generated_date) ORDER BY month desc) table1 ORDER BY `table1`.`year` DESC');
        if ($revenue->num_rows() > 0) {
            $data['revenue'] = $revenue->result_array();
        }
        if (!empty($data)) {
            return $data;
        }
        return [];
    }

    public function get_customer()
    {
        $customers = $this->db->select('COUNT(customer_id) as customer_count,YEAR(created_on) as year')
            ->group_by('YEAR(created_on)')
            ->get('cust_customers');

        if ($customers->num_rows() > 0) {
            $data['customers'] = $customers->result_array();
        }
        if (!empty($data)) {
            return $data;
        }
        return [];
    }

    // Added by saurabh on 30-06-2021 to get partail/revise sample list
    public function get_partial_sample_list($start, $end, $trf, $customer_name, $product, $created_on, $ulr_no, $gc_number, $buyer, $status, $division, $style_no, $start_date, $end_date, $count = null)
    {
        $this->db->select('sr.sample_reg_id, sr.gc_no, barcode_path, ulr_no, sample_type_name, trf_ref_no, buyer.isactive as buyer_active, customer.isactive as customer_active, customer.customer_name as customer, DATE_FORMAT(sr.create_on, "%d-%b-%Y") as created_on, buyer.customer_name as buyer, gr.report_status as status, CONCAT(admin_fname," ",admin_lname) as created_by, sr.due_date, gr.qr_code_name, tr.tat_date, sr.comment, gr.report_id, manual_report_file, revise_report_num');
        $this->db->join('generated_reports gr', 'sr.sample_reg_id = gr.sample_reg_id');
        $this->db->join('mst_sample_types', 'sample_type_id = sr.sample_registration_sample_type_id');
        $this->db->join('trf_registration tr', 'sr.trf_registration_id = tr.trf_id');
        $this->db->join('cust_customers customer', 'customer.customer_id=tr.trf_applicant', 'left');
        $this->db->join('cust_customers buyer', 'buyer.customer_id=tr.trf_buyer', 'left');
        $this->db->join('admin_profile', 'sr.create_by = uidnr_admin', 'left');

        $this->db->group_start();
        $this->db->where('gr.revise_report_type', '1');
        $this->db->or_where('gr.revise_report_type', '2');
        $this->db->group_end();

        if (exist_val('Branch/Wise', $this->session->userdata('permission'))) {
            $multibranch = $this->session->userdata('branch_ids');
            $this->db->group_start();
            $this->db->where(['sr.sample_registration_branch_id IN (' . $multibranch . ') ' => null]); //branch_id
            $this->db->group_end();
        }

        if ($customer_name != "null" && $customer_name != "") {
            $this->db->where('tr.trf_applicant', $customer_name);
        }
        if ($division != "null" && $division != "") {
            $this->db->where('tr.division', $division);
        }
        if ($status != "null" && $status != "") {
            $this->db->where('sr.status', base64_decode($status));
        }
        if ($buyer != "null" && $buyer != "") {
            $this->db->where('tr.trf_buyer', $buyer);
        }
        if ($product != "null" && $product != "") {
            $this->db->where('sample_registration_sample_type_id', $product);
        }
        if ($created_on != "null" && $created_on != "") {
            $this->db->where('date(sr.create_on)', base64_decode($created_on));
        }

        if ($style_no != "null" && $style_no != "") {
            $this->db->like('tr.style_number', base64_decode($style_no));
        }

        if (!empty($start_date) && $start_date != "null") {
            $sdate = base64_decode($start_date);
            $edate = ($end_date != 'null') ? base64_decode($end_date) : date('Y-m-d');
            $this->db->group_start();
            $this->db->where(['date(sr.create_on) BETWEEN "' . $sdate . '" AND  "' . $edate . '" ' => null]);
            $this->db->group_end();
        }

        if ($trf != "null" && $trf != "") {
            $this->db->like('tr.trf_ref_no', trim(base64_decode($trf)));
        }
        if ($ulr_no != "null" && $trf != "") {
            $this->db->like('sr.ulr_no', trim(base64_decode($ulr_no)));
        }

        if ($gc_number != "null" && $trf != "") {
            $this->db->like('sr.gc_no', trim(base64_decode($gc_number)));
        }
        if (!$count) {
            $this->db->limit($start, $end);
        }
        $this->db->order_by('report_id', 'desc');
        $query = $this->db->get('sample_registration sr');
        // echo $this->db->last_query(); die;
        if ($count) {
            return $query->num_rows();
        }
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return [];
    }

    // Added bu saurabh on 30-06-2021 to get all report pdf
    public function get_all_report_pdf($sample_reg_id)
    {
        $this->db->select('sample_reg_id, report_id');
        $this->db->where('manual_report_file !=', '');
        $this->db->where('sample_reg_id', $sample_reg_id);
        $query = $this->db->get('generated_reports');
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return [];
    }

    /* added by millan for checking gc number availability on 30-06-2021 */
    public function fetch_gc_data($data)
    {
        $query = $this->db->select('gr.report_num, gr.sample_reg_id')
            ->from('generated_reports gr')
            ->where('gr.report_num', $data)
            ->get();
        if ($query->num_rows() > 0) {
            return $query->row();
        } else {
            return false;
        }
    }
    /* ends for checking gc number availability on 30-06-2021 */
    // Added by Saurabh on 02-07-2021 to get partial/Revise report type
    public function get_report_type($report_id)
    {
        $this->db->select('revise_report_type');
        $this->db->where('report_id', $report_id);
        $query = $this->db->get('generated_reports');
        if ($query->num_rows() > 0) {
            return $query->row_array();
        }
        return [];
    }
    // Added by Saurabh on 02-07-2021 to get partial/Revise report type
}
