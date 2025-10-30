<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Manage_lab_model extends MY_Model
{
    function __construct()
    {
        parent::__construct();
    }
    public function accepted_sample_list($per_page = NULL, $page = 0, $where, $search_gc, $search_trf, $count = NULL)
    {
        $this->db->limit($per_page, $page);
        if (count($where) > 0) {
            $this->db->group_start();
            $this->db->where($where);
            $this->db->group_end();
        }
        if ($search_gc) {
            $this->db->group_start();
            $this->db->or_like('LOWER(sr.gc_no)', strtolower($search_gc));
            $this->db->group_end();
        }
        if ($search_trf) {
            $this->db->group_start();
            $this->db->or_like('LOWER(tr.trf_ref_no)', strtolower($search_trf));
            $this->db->group_end();
        }
        $this->db->select("buyer.isactive as buyer_active,cc.isactive as customer_active,sr.sample_reg_id,st.sample_test_sample_reg_id,(SELECT count(st.sample_test_id) FROM sample_test st 
        WHERE st.sample_test_sample_reg_id = sr.sample_reg_id) AS sample_test_ids,sr.sample_desc,sr.gc_no,CASE WHEN tr.trf_service_type ='Regular' AND  ( tr.service_days IS NULL OR service_days='') THEN CONCAT(tr.trf_service_type,' 3 Days')
        WHEN tr.trf_service_type ='Express' THEN CONCAT(tr.trf_service_type,' 2 Days')
        WHEN tr.trf_service_type ='Urgent'  THEN CONCAT(tr.trf_service_type,' 1 Days')
        WHEN tr.service_days IS NOT NULL OR tr.service_days!='' THEN CONCAT(tr.trf_service_type,' ',tr.service_days,'Days') END AS sample_service_type, cc.customer_name as client,tr.trf_ref_no,mst.sample_type_name as product_name,sr.received_date,sr.status as sample_reg_status,sr.due_date,sr.qty_received,(SELECT count(st1.sample_test_id) FROM sample_test st1 where (st1.status = 'In Progress' OR st1.status = 'Record Enter Done' OR st1.status = 'Mark As Completed By Lab') and st1.sample_test_sample_reg_id = sr.sample_reg_id) as test_status, trf_buyer");
        $this->db->join('trf_registration tr', 'tr.trf_id = sr.trf_registration_id', 'left');
        $this->db->join('cust_customers as cc', 'cc.customer_id = tr.trf_applicant', 'left');
        $this->db->join('cust_customers as buyer', 'buyer.customer_id = tr.trf_buyer', 'left');
        $this->db->join('mst_sample_types as mst', 'mst.sample_type_id = sr.sample_registration_sample_type_id', 'left');
        $this->db->join('sample_test st', 'st.sample_test_id = sr.sample_reg_id', 'left');
        //$this->db->group_start();
        $this->db->where_in('sr.status', ['Sample Accepted', 'Evaluation Completed']);
        // Commented by saurabh on 07-01-2022 bcoz list on coming
        // $this->db->where('cc.isactive', 'Active');
        // $this->db->where('buyer.isactive', 'Active');
        // Commented by saurabh on 07-01-2022


        // Remove flipkart sample, added by saurabh on 07-01-2022
        // $this->db->group_start();
        // $this->db->where_not_in('cc.customer_id',['634,633']);
        // $this->db->or_where_not_in('buyer.customer_id',['634,633']);
        // $this->db->group_end();
        // Remove flipkart sample, added by saurabh on 07-01-2022
        //$this->db->group_end();
        // $this->db->where_in('st.status', ['Sample Accepted', 'In Progress']);



        /**
         * Commented by saurabh on 23-05-2022, please uncomment before making live
         */
        // Added by saurabh on 01-02-2022 to show division wise list
        // $checkUser = $this->session->userdata('user_data');
        // $default_division = isset($checkUser->default_division_id) ? $checkUser->default_division_id : NULL;
        // $assigned_division = isset($checkUser->user_divisions) ? $checkUser->user_divisions : NULL;
        // $assigned_customer = isset($checkUser->assigned_customer) ? $checkUser->assigned_customer : NULL;

        // $this->db->group_start();
        // $this->db->where('tr.division', $default_division);
        // $this->db->or_where_in('tr.division', explode(',', $assigned_division));
        // if (!empty($assigned_customer)) {
        //     $this->db->or_where_in('tr.open_trf_customer_id', explode(',', $assigned_customer));
        // }
        // $this->db->group_end();
        // Added by saurabh on 01-02-2022 to show division wise list

        /**
         * Added by Saurabh on 28-06-2022 to show only proforma approved sample
         */
        $this->db->join('invoice_proforma', 'proforma_invoice_sample_reg_id = sr.sample_reg_id and (invoice_proforma_invoice_status_id = 14 or invoice_proforma_invoice_status_id = 4)','left');

        $this->db->order_by('sr.create_on', 'DESC');
        $this->db->from('sample_registration sr');

        $query = $this->db->get();
        // $checkUser = $this->session->userdata('user_data');
        // $user = $checkUser->uidnr_admin;
        // if($user == '56'){
        //     echo $this->db->last_query();die;
        // }
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

    // added by millan on 20-07-2021
    public function manual_report_listing($limit = NULL, $start = NULL, $where, $search_gc, $search_trf, $count = null)
    {
        $this->db->limit($limit, $start);
        if (count($where) > 0) {
            $this->db->group_start();
            $this->db->where($where);
            $this->db->group_end();
        }
        if ($search_gc) {
            $this->db->group_start();
            $this->db->like('sr.gc_no', ($search_gc), 'both');
            $this->db->group_end();
        }
        if ($search_trf) {
            $this->db->group_start();
            $this->db->like('tr.trf_ref_no', ($search_trf), 'both');
            $this->db->group_end();
        }
        // Commented by Saurabh on 17-03-2022
        // $where_in = ("(sr.status = 'Send For Record Finding' AND st.status = 'Completed' AND gr.report_type !='System Report' AND gr.report_type !='Automatic Report') OR sr.status = 'Report Generated' OR sr.status = 'Report Approved' OR sr.status = 'Completed' OR sr.status = 'Hold Sample' "); // updated by millan on 20-07-2021
        // Commented by Saurabh on 17-03-2022
        // $this->db->limit($limit, $start);
        $this->db->order_by('sr.lab_completion_date_time', 'DESC');
        // Commented by Saurabh on 17-03-2022
        // $this->db->group_start();
        // $this->db->where($where_in);
        // $this->db->group_end();
        // Commented by Saurabh on 17-03-2022
        // $this->db->where('st.status <>', 'Completed');

        $this->db->select("buyer.isactive as buyer_active, cc.isactive as customer_active, sr.ulr_no, gr.signing_authority, gr.sign_authority_new, sr.sample_desc, sr.gc_no, sr.sample_reg_id as sample_reg_id, st.sample_test_id as sample_test_id, 
        (CASE 
            WHEN tr.trf_service_type = 'Regular' AND (tr.service_days IS NULL OR service_days = '') THEN CONCAT(tr.trf_service_type,' 3 Days') 
            WHEN tr.trf_service_type = 'Express' THEN CONCAT(tr.trf_service_type,' 2 Days') 
            WHEN tr.trf_service_type = 'Urgent' THEN CONCAT(tr.trf_service_type,' 1 Days') 
            WHEN tr.service_days IS NOT NULL OR tr.service_days != '' THEN CONCAT(tr.trf_service_type,' ',tr.service_days,'Days') 
        END) AS sample_service_type, cc.customer_name as client, tr.trf_ref_no, mst.sample_type_name as product_name, sr.received_date, sr.status, st.status as sample_test_status, sr.lab_completion_date_time, DATE_FORMAT(due_date,'%d-%m-%Y') as due_date, sr.qty_received, ts.test_name, ts.test_method, sr.seal_no, gr.report_id, gr.manual_report_file, gr.report_num,(select COUNT(st.sample_test_sample_reg_id) from sample_test st where st.sample_test_sample_reg_id = sr.sample_reg_id) as total_record_finding, (select COUNT(str.sample_test_sample_reg_id) from sample_test str where (str.status = 'Completed' or str.status = 'Completed Sample') and (str.sample_test_sample_reg_id = sr.sample_reg_id)) as status_count, sr.sample_registration_branch_id, sr.released_to_client, trf_buyer, sr.revise_count, gr.report_type"); // updated by millan on 20-07-2021
        $this->db->from('sample_registration sr');
        $this->db->join('trf_registration tr', 'tr.trf_id = sr.trf_registration_id', 'inner');
        $this->db->join('sample_test st', 'st.sample_test_sample_reg_id = sr.sample_reg_id', 'inner');
        $this->db->join('tests ts', 'st.sample_test_test_id = ts.test_id', 'left');
        $this->db->join('cust_customers as cc', 'cc.customer_id = tr.trf_applicant', 'left');
        $this->db->join('cust_customers as buyer', 'buyer.customer_id = tr.trf_buyer', 'left');
        $this->db->join('mst_sample_types as mst', 'mst.sample_type_id = sr.sample_registration_sample_type_id', 'left');
        // $this->db->join('sample_photos as sp', 'sp.sample_reg_id = sr.sample_reg_id', 'left');
        $this->db->join('generated_reports as gr', 'gr.sample_reg_id = sr.sample_reg_id AND (gr.additional_report_flag <> 1 AND  gr.revise_report <> "1" )', 'left');
        $this->db->group_by('sr.sample_reg_id');
        $this->db->where('cc.isactive', 'Active');
        $this->db->where('gr.report_type', 'Manual Report'); // updated by millan on 20-07-2021
        $this->db->where('buyer.isactive', 'Active');

        // Added by saurabh on 01-02-2022 to show division wise list
        // $checkUser = $this->session->userdata('user_data');
        // $default_division = isset($checkUser->default_division_id) ? $checkUser->default_division_id : NULL;
        // $assigned_division = isset($checkUser->user_divisions) ? $checkUser->user_divisions : NULL;
        // $assigned_customer = isset($checkUser->assigned_customer) ? $checkUser->assigned_customer : NULL;

        // $this->db->group_start();
        // $this->db->where('tr.division', $default_division);
        // $this->db->or_where_in('tr.division', explode(',', $assigned_division));
        // if (!empty($assigned_customer)) {
        //     $this->db->or_where_in('tr.open_trf_customer_id', explode(',', $assigned_customer));
        // }
        // $this->db->group_end();
        // Added by saurabh on 01-02-2022 to show division wise list

        /**
         * Added by Saurabh on 28-06-2022 to show only proforma approved sample
         */
        $this->db->join('invoice_proforma', 'proforma_invoice_sample_reg_id = sr.sample_reg_id and (invoice_proforma_invoice_status_id = 14 or invoice_proforma_invoice_status_id = 4)');

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


    public function record_count()
    {
        // $this->db->select("sr.sample_id,");
        $this->db->join('trf_registration tr', 'tr.trf_id = sr.trf_registration_id', 'left');
        $this->db->join('cust_customers as cc', 'cc.customer_id = tr.trf_applicant', 'left');
        $this->db->join('mst_sample_types as mst', 'mst.sample_type_id = sr.sample_registration_sample_type_id', 'left');
        $this->db->join('sample_test st', 'st.sample_test_id = sr.sample_reg_id', 'left');
        $this->db->where('sr.status', 'Sample Accepted');
        $this->db->or_where('sr.status', 'Test as ReAssigned');
        $this->db->from('sample_registration sr');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->num_rows();
        } else {
            return false;
        }
    }
    public function check_record_finding($post)
    {
        $query = $this->db->select('rfd.record_finding_id')->where(['sample_registration_id ' => $post['sample_registration_id'], 'sample_test_id' => $post['sample_test_id']])->get('record_finding_details rfd');
        if ($query) {
            return $query->row();
        } else {
            return false;
        }
    }
    public function get_assign_head($sample_reg_id)
    {
        $this->db->select("sr.gc_no,mst.sample_type_name as product_name");
        $this->db->join('mst_sample_types as mst', 'mst.sample_type_id = sr.sample_registration_sample_type_id', 'left');
        $this->db->join('sample_test st', 'st.sample_test_id = sr.sample_reg_id', 'left');
        $this->db->where('sr.sample_reg_id', $sample_reg_id);
        $this->db->from('sample_registration sr');

        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->row_array();
        } else {
            return false;
        }
    }
    public function get_assign_test($sample_reg_id)
    {
        $this->db->select("st.sample_test_id,tests.test_name,tests.test_method,st.test_description,ml.lab_name,parts.part_name,ap.admin_fname,ap.admin_lname,tests.test_turn_around_time,st.assigned_to");
        $this->db->join('tests', 'st.sample_test_test_id = tests.test_id', 'left');
        $this->db->join('sample_registration sr', 'st.sample_test_sample_reg_id = sr.sample_reg_id', 'left');
        $this->db->join('mst_sample_types as mst', 'mst.sample_type_id = sr.sample_registration_sample_type_id', 'left');
        $this->db->join('mst_labs ml', 'st.sample_test_assigned_lab_id = ml.lab_id', 'left');
        $this->db->join('parts', 'parts.part_id=st.sample_part_id', 'left');
        $this->db->join('admin_profile ap', 'ap.uidnr_admin=st.assigned_to', 'left');
        $this->db->where('sr.sample_reg_id', $sample_reg_id);
        //  $this->db->where('st.checked','true');
        $this->db->from('sample_test st');
        $query = $this->db->get();
        //  echo $this->db->last_query();die;
        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return false;
        }
    }

    public function get_analysis()
    {
        $this->db->select('ap.uidnr_admin AS id, CONCAT(ap.admin_fname ," ", ap.admin_lname) as name,ar.admin_role_name')
            ->join('admin_users au', 'au.uidnr_admin = ap.uidnr_admin', 'left')->join('admin_role ar', 'ar.id_admin_role = au.id_admin_role', 'left')
            ->where_in('au.lab_analyst', 1)
            ->from('admin_profile as ap');
        $query = $this->db->get();
        // echo $this->db->last_query();die;
        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return false;
        }
    }




    public function SaveAssignTest($data)
    {
        // isset($data['assign_to'])
        $total = count($data['assign_to']);
        for ($i = 0; $i < $total; $i++) {
            $sample_id = $data['assign_to'][$i];
            $insert_data = array(
                'assigned_to' => $data['analysis'],
                'instruction_note' => $data['instruction_note'],
                'status' => 'In Progress'
            );
            $update =  $this->db->where('sample_test_id', $sample_id)->update('sample_test', $insert_data);
            // echo $this->db->last_query();die;
        }
        if ($update) {
            $old_status_query = $this->db->select('status')->from('sample_registration')->where('sample_reg_id', $data['sample_reg_id'])->get();
            $old_status = $old_status_query->row()->status;
            $logDetails = array(
                'old_status' => $old_status,
                'new_status' => '',
                'sample_reg_id' => $data['sample_reg_id'],
                'sample_assigned_lab_id' => /* $lab_id, */ '',
                'action_message' => 'Test assigned to analyst',
                'sample_job_id' => '',
                'report_id' => '',
                'report_status' => '',
                'test_ids' => '',
                'test_names' => '',
                'test_newstatus' => '',
                'test_oldStatus' => '',
                'test_assigned_to' => '',
                'source_module'    => 'Manage_lab',
                'operation'        => 'SaveAssignTest',
                'uidnr_admin'    => $this->session->userdata('user_data')->uidnr_admin,
                'log_activity_on' => date("Y-m-d H:i:s")
            );
            $this->save_user_log($logDetails);
            return true;
        } else {
            return false;
        }
    }

    public function record_finding_list($limit = NULL, $start = NULL, $where, $search_gc, $search_trf, $count = null)
    {
        if (count($where) > 0) {
            $this->db->group_start();
            $this->db->where($where);
            $this->db->group_end();
        }
        if ($search_gc) {
            $this->db->group_start();
            $this->db->like('sr.gc_no', ($search_gc), 'both');
            $this->db->group_end();
        }
        if ($search_trf) {
            $this->db->group_start();
            $this->db->like('tr.trf_ref_no', ($search_trf), 'both');
            $this->db->group_end();
        }
        $where_in = ("(sr.status = 'Send For Record Finding' AND st.status = 'Mark As Completed By Lab' OR st.status='Record Enter Done') OR (sr.status = 'Retest' and st.status = 'Retest') OR (sr.status = 'Completed' and st.status != 'Completed')");
        $this->db->limit($limit, $start);
        $this->db->order_by('sr.lab_completion_date_time', 'DESC');
        $this->db->group_start();
        $this->db->where($where_in);
        $this->db->group_end();
        // $this->db->where('st.status <>', 'Completed');
        $this->db->select("DISTINCT(rfd.record_finding_id) as record_finding_id,sr.sample_desc,sr.gc_no,sr.sample_reg_id as sample_reg_id,st.sample_test_id as sample_test_id,
	   CASE WHEN tr.trf_service_type ='Regular' AND  ( tr.service_days IS NULL OR service_days='') THEN CONCAT(tr.trf_service_type,' 3 Days')
       WHEN tr.trf_service_type ='Express' THEN CONCAT(tr.trf_service_type,' 2 Days')
       WHEN tr.trf_service_type ='Urgent'  THEN CONCAT(tr.trf_service_type,' 1 Days')
	   WHEN tr.service_days IS NOT NULL OR tr.service_days!='' THEN CONCAT(tr.trf_service_type,' ',tr.service_days,'Days') END AS sample_service_type, cc.customer_name as client, tr.trf_ref_no, mst.sample_type_name as product_name, sr.received_date, sr.status, st.status as sample_test_status, sr.lab_completion_date_time, due_date, sr.qty_received, ts.test_name, ts.test_method,sr.seal_no, sr.sample_registration_branch_id, buyer.isactive as buyer_active, cc.isactive as customer_active, trf_buyer, ts.test_id");
        $this->db->from('sample_registration sr');
        $this->db->join('sample_test st', 'st.sample_test_sample_reg_id = sr.sample_reg_id', 'left');
        $this->db->join('record_finding_details as rfd', 'rfd.sample_test_id = st.sample_test_id and rfd.sample_registration_id=st.sample_test_sample_reg_id', 'left');
        $this->db->join('trf_registration tr', 'tr.trf_id = sr.trf_registration_id', 'inner');
        $this->db->join('tests ts', 'st.sample_test_test_id = ts.test_id', 'left');
        $this->db->join('cust_customers as cc', 'cc.customer_id = tr.trf_applicant', 'left');
        $this->db->join('cust_customers as buyer', 'buyer.customer_id = tr.trf_buyer', 'left');
        $this->db->join('mst_sample_types as mst', 'mst.sample_type_id = sr.sample_registration_sample_type_id', 'left');
        $this->db->order_by('sr.create_on', 'desc');
        // Commented by saurabh on 07-01-2022 bcoz list on coming
        // $this->db->where('cc.isactive', 'Active');
        // $this->db->where('buyer.isactive', 'Active');
        // Commented by saurabh on 07-01-2022

        // Remove flipkart sample, added by saurabh on 07-01-2022
        //  $this->db->group_start();
        //  $this->db->where_not_in('cc.customer_id',['634,633']);
        //  $this->db->or_where_not_in('buyer.customer_id',['634,633']);
        //  $this->db->group_end();
        // Remove flipkart sample, added by saurabh on 07-01-2022

        /**
         * Commented by saurabh on 23-05-2022, please uncomment before making live
         */
        // Added by saurabh on 01-02-2022 to show division wise list
        // $checkUser = $this->session->userdata('user_data');
        // $default_division = isset($checkUser->default_division_id) ? $checkUser->default_division_id : NULL;
        // $assigned_division = isset($checkUser->user_divisions) ? $checkUser->user_divisions : NULL;
        // $assigned_customer = isset($checkUser->assigned_customer) ? $checkUser->assigned_customer : NULL;

        // $this->db->group_start();
        // $this->db->where('tr.division', $default_division);
        // $this->db->or_where_in('tr.division', explode(',', $assigned_division));
        // if (!empty($assigned_customer)) {
        //     $this->db->or_where_in('tr.open_trf_customer_id', explode(',', $assigned_customer));
        // }
        // $this->db->group_end();
        // Added by saurabh on 01-02-2022 to show division wise list

        /**
         * Added by Saurabh on 28-06-2022 to show only proforma approved sample
         */
        $this->db->join('invoice_proforma', 'proforma_invoice_sample_reg_id = sr.sample_reg_id and (invoice_proforma_invoice_status_id = 14 or invoice_proforma_invoice_status_id = 4)','left');

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

    public function get_count_record($limit = NULL, $start = NULL)
    {
        $where = ("sr.status='Sample Accepted' AND st.status='In Progress'");
        $this->db->select("buyer.isactive as buyer_active,cc.isactive as customer_active,sr.sample_desc,sr.gc_no,
	  CASE WHEN tr.trf_service_type ='Regular' AND  ( tr.service_days IS NULL OR service_days='') THEN CONCAT(tr.trf_service_type,' 3 Days')
       WHEN tr.trf_service_type ='Express' THEN CONCAT(tr.trf_service_type,' 2 Days')
       WHEN tr.trf_service_type ='Urgent'  THEN CONCAT(tr.trf_service_type,' 1 Days')
	   WHEN tr.service_days IS NOT NULL OR tr.service_days!='' THEN CONCAT(tr.trf_service_type,' ',tr.service_days,'Days') END AS sample_service_type,
	   cc.customer_name as client,tr.trf_ref_no,mst.sample_type_name as product_name,sr.received_date,sr.status,
	   sr.due_date,sr.qty_received,ts.test_name,ts.test_method,sr.seal_no");
        $this->db->join('trf_registration tr', 'tr.trf_id = sr.trf_registration_id', 'left');
        $this->db->join('sample_test st', 'st.sample_test_sample_reg_id = sr.sample_reg_id', 'left');
        $this->db->join('tests ts', 'st.sample_test_test_id = ts.test_id', 'left');
        $this->db->join('cust_customers as cc', 'cc.customer_id = tr.trf_applicant', 'left');
        $this->db->join('cust_customers as buyer', 'buyer.customer_id = tr.trf_buyer', 'left');
        $this->db->join('mst_sample_types as mst', 'mst.sample_type_id = sr.sample_registration_sample_type_id', 'left');
        $this->db->where($where);
        $this->db->where('cc.isactive', 'Active');
        $this->db->where('buyer.isactive', 'Active');
        $this->db->from('sample_registration sr');
        $this->db->order_by('sr.create_on', 'DESC');
        // $this->db->limit(10);  
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->num_rows();
        } else {
            return false;
        }
    }

    public function get_sample_gc($data)
    {
        $query =  $this->db->select("sr.gc_no,mst.sample_type_name as product_name")
            ->from('sample_registration sr')
            ->join('mst_sample_types as mst', 'mst.sample_type_id = sr.sample_registration_sample_type_id', 'left')
            ->where('sr.sample_reg_id', $data['sample_reg_id'])
            ->get();
        if ($query) {
            return $query->row();
        } else {
            return false;
        }
    }

    public function get_parts($data)
    {
        // Get parts ID from test
        $query = $this->db->select('sample_part_id')
            ->group_start()
            ->where('sample_test_sample_reg_id', $data['sample_reg_id'])
            ->where('sample_test_id', $data['sample_test_id'])
            ->group_end()
            ->get('sample_test');
        $parts_id = explode(',', $query->row_array()['sample_part_id']);
        $this->db->select('group_concat(concat(parts.part_name, " . ", parts.parts_desc) separator ", ") as parts');
        $this->db->where_in('part_id', $parts_id);
        $part_name_query = $this->db->get('parts');
        if ($part_name_query->num_rows() > 0) {
            return $part_name_query->row();
        }
        return false;
    }

    public function get_test($data)
    {
        $query = $this->db->select('test_name,test_method, test_id')
            ->join('tests', 'tests.test_id= st.sample_test_test_id', 'left')
            ->from('sample_test st')
            ->where('sample_test_id', $data['sample_test_id'])->get();
        if ($query) {
            return  $query->row();
        } else {
            return false;
        }
    }

    public function get_status($lab_data)
    {
        $query = $this->db->select('st.sample_test_id,st.status,st.sample_test_sample_reg_id')->from('sample_test as st')->join('sample_registration sr', 'sr.sample_reg_id = st.sample_test_sample_reg_id', 'left')->where('sample_reg_id', $lab_data['id'])->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return false;
        }
    }

    public function lab_completion($lab_data)
    {
        $result = $this->db->where('sample_reg_id', $lab_data['id'])->update('sample_registration', $lab_data['data']);
        if ($result) {
            $status_get = $this->db->select('st.status,st.sample_test_id')->from('sample_test st')->where('sample_test_sample_reg_id', $lab_data['id'])->where('st.status', 'In Progress')->get();

            if ($status_get) {
                $status_get1 = $status_get->result();
            }
            if ($status_get1) {
                $revise_data = $status_get1;
                foreach ($revise_data as $value) {
                    $update =  $this->db->where('sample_test_id', $value->sample_test_id)->update('sample_test', array('status' => "Mark As Completed By Lab"));
                }
                if ($update) {
                    return true;
                } else {
                    return false;
                }
            }
        } else {
            return false;
        }
    }

    public function report_generation($post)
    {
        $result = $this->db->insert('record_finding_details', $post);

        if ($result) {
            $last_insert_id = $this->db->insert_id();

            $status = $this->db->where('sample_test_id', $post['sample_test_id'])->update('sample_test', array('status' => 'Record Enter Done'));
            if ($status) {
                $old_status_query = $this->db->select('status')->from('sample_registration')->where('sample_reg_id', $post['sample_registration_id'])->get();
                $old_status = $old_status_query->row()->status;
                $logDetails = array(
                    'module' => 'Samples',
                    'old_status' => $old_status,
                    'new_status' => '',
                    'sample_reg_id' => $post['sample_registration_id'],
                    'sample_assigned_lab_id' => /* $lab_id, */ '',
                    'action_message' => 'Report data saved',
                    'sample_job_id' => '',
                    'report_id' => '',
                    'report_status' => '',
                    'test_ids' => '',
                    'test_names' => '',
                    'test_newstatus' => '',
                    'test_oldStatus' => '',
                    'test_assigned_to' => '',
                    'source_module'    => 'Manage_lab',
                    'operation'        => 'report_generation',
                    'uidnr_admin'    => $this->session->userdata('user_data')->uidnr_admin,
                    'log_activity_on' => date("Y-m-d H:i:s")
                );
                $this->save_user_log($logDetails);
                return $last_insert_id;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function mark_completion($id)
    {
        $status = $this->db->where('sample_test_id', $id['sample_test_id'])->update('sample_test', ['status' => 'Completed']);
        $status = $this->db->where('sample_reg_id', $id['sample_reg_id'])->update('sample_registration', ['status' => 'Completed']);
        if ($status) {
            $old_status_query = $this->db->select('status')->from('sample_registration')->where('sample_reg_id', $id['sample_reg_id'])->get();
            $old_status = $old_status_query->row()->status;
            $logDetails = array(
                'module' => 'Samples',
                'old_status' => $old_status,
                'new_status' => '',
                'sample_reg_id' => $id['sample_reg_id'],
                'sample_assigned_lab_id' => /* $lab_id, */ '',
                'action_message' => 'MarK as Completed',
                'sample_job_id' => '',
                'report_id' => '',
                'report_status' => '',
                'test_ids' => '',
                'test_names' => '',
                'test_newstatus' => '',
                'test_oldStatus' => '',
                'test_assigned_to' => '',
                'source_module'    => 'Manage_lab',
                'operation'        => 'mark_completion',
                'uidnr_admin'    => $this->session->userdata('user_data')->uidnr_admin,
                'log_activity_on' => date("Y-m-d H:i:s")
            );
            // print_r($logDetails); die;
            $this->save_user_log($logDetails);
            return true;
        } else {
            return false;
        }
    }

    public function edit_record_finding($data)
    {
        $query =  $this->db->select('record_finding_details.*,scld.lab_name')
            ->join('sub_contract_lab_details as scld', 'scld.lab_details_id=record_finding_details.lab_id', 'left')
            ->where('record_finding_details.record_finding_id', $data['record_finding_id'])
            ->where('record_finding_details.sample_registration_id', $data['sample_reg_id'])
            ->from('record_finding_details')->get();
        //   echo $this->db->last_query();die;
        if ($query) {

            return $query->row_array();
        } else {
            return false;
        }
    }
    public function update_record_finding($update_data)
    {
        $query =  $this->db->where('record_finding_id', $update_data['id'])->update('record_finding_details', $update_data['update_data']);
        if ($query) {
            return true;
        } else {
            return false;
        }
    }

    public function report_listing($limit = NULL, $start = NULL, $where, $search_gc, $search_trf, $count = null)
    {
        if (count($where) > 0) {
            $this->db->group_start();
            $this->db->where($where);
            $this->db->group_end();
        }
        if ($search_gc) {
            $this->db->group_start();
            $this->db->like('sr.gc_no', ($search_gc), 'both');
            $this->db->group_end();
        }
        if ($search_trf) {
            $this->db->group_start();
            $this->db->like('tr.trf_ref_no', ($search_trf), 'both');
            $this->db->group_end();
        }
        $where_in = ("(sr.status = 'Send For Record Finding' AND st.status = 'Completed') OR sr.status = 'Report Generated' OR sr.status = 'Report Approved' OR sr.status = 'Completed' OR sr.status = 'Hold Sample' ");
        // $this->db->limit($limit, $start);
        $this->db->order_by('sr.lab_completion_date_time', 'DESC');
        $this->db->group_start();
        $this->db->where($where_in);
        $this->db->group_end();
        // $this->db->where('st.status <>', 'Completed');
        $this->db->select("buyer.isactive as buyer_active, cc.isactive as customer_active, sr.ulr_no, gr.signing_authority, gr.sign_authority_new, sr.sample_desc, sr.gc_no, sr.sample_reg_id as sample_reg_id, st.sample_test_id as sample_test_id, 
        (CASE 
            WHEN tr.trf_service_type ='Regular' AND (tr.service_days IS NULL OR service_days = '') THEN CONCAT(tr.trf_service_type,' 3 Days') 
            WHEN tr.trf_service_type = 'Express' THEN CONCAT(tr.trf_service_type,' 2 Days') 
            WHEN tr.trf_service_type = 'Urgent'  THEN CONCAT(tr.trf_service_type,' 1 Days') 
            WHEN tr.service_days IS NOT NULL OR tr.service_days != '' THEN CONCAT(tr.trf_service_type,' ',tr.service_days,'Days') 
        END) AS sample_service_type, 
        cc.customer_name as client, tr.trf_ref_no, mst.sample_type_name as product_name, sr.received_date, sr.status, st.status as sample_test_status, sr.lab_completion_date_time, DATE_FORMAT(due_date,'%d-%m-%Y') as due_date, sr.qty_received, ts.test_name, ts.test_method, sr.seal_no, gr.report_id, gr.manual_report_file, gr.report_num, (select COUNT(st.sample_test_sample_reg_id) from sample_test st where st.sample_test_sample_reg_id = sr.sample_reg_id) as total_record_finding, (select COUNT(str.sample_test_sample_reg_id) from sample_test str where (str.status = 'Completed' or str.status = 'Completed Sample') and (str.sample_test_sample_reg_id = sr.sample_reg_id)) as status_count, sr.sample_registration_branch_id, sr.released_to_client, trf_buyer, sr.revise_count, primary_approver_status, secondary_approver_status, gr.gr_revise_flag");
        $this->db->from('sample_registration sr');
        $this->db->join('trf_registration tr', 'tr.trf_id = sr.trf_registration_id', 'inner');
        $this->db->join('sample_test st', 'st.sample_test_sample_reg_id = sr.sample_reg_id', 'inner');
        $this->db->join('tests ts', 'st.sample_test_test_id = ts.test_id', 'left');
        $this->db->join('cust_customers as cc', 'cc.customer_id = tr.trf_applicant', 'left');
        $this->db->join('cust_customers as buyer', 'buyer.customer_id = tr.trf_buyer', 'left');
        $this->db->join('mst_sample_types as mst', 'mst.sample_type_id = sr.sample_registration_sample_type_id', 'left');
        // $this->db->join('sample_photos as sp', 'sp.sample_reg_id = sr.sample_reg_id', 'left');
        $this->db->join('generated_reports as gr', 'gr.sample_reg_id = sr.sample_reg_id AND (gr.additional_report_flag <> 1 AND  gr.revise_report <> "1")', 'left');
        $this->db->group_by('sr.sample_reg_id');

        // Commented by saurabh on 07-01-2022 bcoz list on coming
        // $this->db->where('cc.isactive', 'Active');
        // $this->db->where('buyer.isactive', 'Active');
        // Commented by saurabh on 07-01-2022

        // Remove flipkart sample, added by saurabh on 07-01-2022
        //  $this->db->group_start();
        //  $this->db->where_not_in('cc.customer_id',['634,633']);
        //  $this->db->or_where_not_in('buyer.customer_id',['634,633']);
        //  $this->db->group_end();
        // Remove flipkart sample, added by saurabh on 07-01-2022

        /**
         * Commented by saurabh on 23-05-2022, please uncomment before making live
         */
        // Added by saurabh on 01-02-2022 to show division wise list
        // $checkUser = $this->session->userdata('user_data');
        // $default_division = isset($checkUser->default_division_id) ? $checkUser->default_division_id : NULL;
        // $assigned_division = isset($checkUser->user_divisions) ? $checkUser->user_divisions : NULL;
        // $assigned_customer = isset($checkUser->assigned_customer) ? $checkUser->assigned_customer : NULL;

        // $this->db->group_start();
        // $this->db->where('tr.division', $default_division);
        // $this->db->or_where_in('tr.division', explode(',', $assigned_division));
        // if (!empty($assigned_customer)) {
        //     $this->db->or_where_in('tr.open_trf_customer_id', explode(',', $assigned_customer));
        // }
        // $this->db->group_end();
        // Added by saurabh on 01-02-2022 to show division wise list

        /**
         * Added by Saurabh on 28-06-2022 to show only proforma approved sample
         */
        $this->db->join('invoice_proforma', 'proforma_invoice_sample_reg_id = sr.sample_reg_id and (invoice_proforma_invoice_status_id = 14 or invoice_proforma_invoice_status_id = 4)','left');
        $this->db->limit($limit, $start);

        $query = $this->db->get();

        // echo $this->db->last_query(); die;
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

    public function Final_report_generate($data)
    {
        $query = $this->db->where('sample_reg_id', $data['sample_reg_id'])->update('sample_registration', array('status' => 'Report Generated', 'issuance_date' => $data['issuance_date'], 'remark' => htmlentities($data['remark']), 'ulr_no_flag' => $data['ulr_no_flag'], 'manual_report_result' => $data['report_result'], 'manual_report_remark' => $data['report_remark']));
        if ($query) {
            return true;
        } else {
            return false;
        }
    }

    public function get_record_finding_data($data)
    {
        $query =  $this->db->select('rfd.*')
            ->from('record_finding_details rfd')
            ->where('rfd.sample_registration_id', $data['sample_reg_id'])
            ->order_by('rfd.sequence_no')
            ->get();
        // echo $this->db->last_query();die;
        if ($query) {
            return $query->result_array();
        } else {
            return false;
        }
    }

    public function getNABLCpsResultData($select, $sample_reg_id)
    {
        $this->db->where('sample_registration_id', $sample_reg_id);
        // $this->db->join('record_finding_details','record_finding_details.sample_registration_id = sample_reg_id','left');
        $this->db->group_start();
        $this->db->group_start();
        $this->db->where('nabl_detail !=', '');
        $this->db->or_where('nabl_remark !=', '');
        $this->db->group_end();
        $this->db->or_where('nabl_headings !=', '');

        $this->db->group_end();
        $query = $this->db->select($select)->get('record_finding_details rfd');
        //    echo $this->db->last_query(); exit;
        if ($query->num_rows()) {
            return $query->result_array();
        } else {
            return false;
        }
    }

    public function getNONNABLCpsResultData($select, $sample_reg_id)
    {
        $this->db->where('sample_registration_id', $sample_reg_id);
        $this->db->group_start();
        $this->db->group_start();
        $this->db->where('non_nabl_detail !=', '');
        $this->db->or_where('non_nabl_remark !=', '');
        $this->db->group_end();
        $this->db->or_where('non_nabl_headings !=', '');
        $this->db->group_end();
        $query = $this->db->select($select)->get('record_finding_details rfd');
        if ($query->num_rows()) {
            return $query->result_array();
        } else {
            return false;
        }
    }

    public function get_cps_data($select, $sample_reg_id)
    {
        $query = $this->db->select($select)->where('sample_registration_id', $sample_reg_id)->get('record_finding_details rfd');
        if ($query->num_rows()) {
            return $query->result_array();
        } else {
            return false;
        }
    }

    public function get_record_finding_id($id)
    {
        $query = $this->db->select('rfd.record_finding_id')
            ->from('record_finding_details rfd')
            ->where('rfd.sample_registration_id', $id)
            ->get();
        if ($query->num_rows() > 0) {

            return $query->result();
        } else {
            return false;
        }
    }

    public function get_application_care($update)
    {
        if (isset($update['report_data']->trf_id) && !empty($update['report_data']->trf_id)) {
            $query = $this->db->select('aci.instruction_image,if(tai.description!="",tai.description,aci.care_wording) as instruction_name')
                ->join('trf_registration tr', 'tr.trf_id = sr.trf_registration_id', 'left')
                ->join('trf_apc_instruction tai', 'tai.trf_id = tr.trf_id', 'left')
                ->join('application_care_instruction aci', 'tai.application_care_id = aci.instruction_id', 'left')
                ->where('tai.trf_id', $update['report_data']->trf_id)
                ->where('tai.image is NOT NULL', NULL, FALSE)
                ->order_by('tai.image_sequence', 'ASC')
                ->from('sample_registration sr')->get();
            return ($query->num_rows() > 0) ? $query->result() : false;
        }
        return false;
    }

    public function get_images($record_finding_id)
    {
        $query =  $this->db->select('rgi.image_path,rgi.images_id, compress_status')
            ->from('report_generated_images rgi')
            ->where('rgi.record_finding_id', $record_finding_id)
            ->get();
        if ($query) {
            return $query->result_array();
        } else {
            return false;
        }
    }

    public function get_test_result($data)
    {
        $query = $this->db->select('rfd.test_display_name,rfd.test_display_method,rfd.test_name_type,rfd.sample_test_id,ts.test_name as test_name,rfd.test_result,rfd.test_component,sr.sample_reg_id,ts.test_method,ts.test_id')
            ->from('sample_test st')
            ->join('tests ts', 'st.sample_test_test_id = ts.test_id')
            ->join('sample_registration sr', 'st.sample_test_sample_reg_id = sr.sample_reg_id')
            ->join('record_finding_details rfd', 'rfd.sample_test_id = st.sample_test_id')
            ->group_by('rfd.sample_test_id')
            ->where('rfd.sample_registration_id', $data['sample_reg_id'])
            ->order_by('rfd.sequence_no')
            ->get();
        if ($query->num_rows() > 0) {
            return  $query->result();
        } else {
            return false;
        }
    }

    public function pdf_data_get($data)
    {
        $status = $this->db->select('cc.customer_name as customer_name,cc.address,contacts.contact_name,sr.sample_desc,sr.report_template_type,sr.part,sr.part_details,sr.received_date,GROUP_CONCAT(DISTINCT(msc.country_name)) as destination,rfd.record_finding_id,rfd.test_result,rfd.test_component,rfd.test_display_name,ts.test_name,ts.test_method,sr.ulr_no,sr.gc_no,sr.ilac_logo,sr.report_template_type,gr.report_format,gr.generated_date,trb.customer_name as buyer_name,tra.customer_name as agent_name,tr.trf_id,tr.trf_end_use,trco.country_name as country_origin,sr.issuance_date,sr.remark,gr.report_num,tr.product_custom_fields,gr.report_id,sr.sample_images_flag,sr.ulr_no_flag,mb.branch_id,mb.branch_name,md.division_name,sr.sample_images_flag,sr.sample_reg_id, (select count(rfd1.test_component) from record_finding_details rfd1 where rfd1.sample_registration_id = sr.sample_reg_id) as test_total, (select count(rfd3.record_finding_id) from record_finding_details rfd3 where (rfd3.sample_registration_id = sr.sample_reg_id and rfd3.test_type = "NABL") ) as nabl_record, gr.signing_authority, gr.sign_authority_new, gr.primary_approver_status, gr.secondary_approver_status')
            ->join('trf_registration tr', 'tr.trf_id = sr.trf_registration_id', 'left')
            ->join('cust_customers trb', 'tr.trf_buyer = trb.customer_id AND trb.customer_type = "Buyer"', 'left')
            ->join('cust_customers tra', 'tr.trf_agent = tra.customer_id AND tra.customer_type = "Agent"', 'left')
            ->join('mst_country trco', 'tr.trf_country_orgin = trco.country_id', 'left')
            ->join('mst_divisions md', 'md.division_id = tr.division', 'left')
            ->join('cust_customers cc', 'tr.trf_applicant = cc.customer_id', 'left')
            ->join('contacts', 'contacts.contact_id = tr.trf_contact', 'left')
            ->join('mst_country msc', 'FIND_IN_SET(msc.country_id,tr.trf_country_destination) > 0', 'left', true)
            // ->join('mst_country msc', 'msc.country_id = tr.trf_country_destination', 'left')
            ->join('record_finding_details rfd', 'rfd.sample_registration_id = sr.sample_reg_id', 'left')
            ->join('sample_test st', 'st.sample_test_sample_reg_id = sr.sample_reg_id', 'left')
            ->join('mst_branches mb', 'mb.branch_id = sr.sample_registration_branch_id', 'left')
            ->join('tests ts', 'st.sample_test_test_id = ts.test_id', 'left')
            ->join('generated_reports as gr', 'gr.sample_reg_id = sr.sample_reg_id', 'left')
            ->group_by('sr.sample_reg_id')
            ->where('sr.sample_reg_id', $data['sample_reg_id'])
            ->where('gr.report_id', $data['report_id'])
            ->from('sample_registration sr')->get();
        if ($status) {
            return $status->row();
        } else {
            return false;
        }
    }

    public function pdf_test_component($data)
    {
        $this->db->select('count(rfd.test_component) as total');
        $this->db->from('record_finding_details rfd');
        $where = ['', 'NULL'];
        $this->db->where_in('rfd.test_component', $where);
        $this->db->where('rfd.sample_registration_id', $data['sample_reg_id']);
        $query =  $this->db->get();
        if ($query) {
            return $query->row();
        } else {
            return false;
        }
    }

    public function report_generate_data($report_data)
    {
        $query = $this->db->insert('generated_reports', $report_data);
        if ($query) {
            return $this->db->insert_id();
        } else {
            return false;
        }
    }

    public function regenerate_sample($sample_reg_id, $report_id)
    {
        $data = array('status' => 'Retest', 'revise_flag' => '1', 'released_to_client' => '0');
        $rep_data = array('revise_report' => '1');
        $update_sample = $this->db->where('sample_reg_id', $sample_reg_id)
            ->update('sample_registration', $data);
        $this->db->where('report_id', $report_id)
            ->update('generated_reports', $rep_data);
        if ($update_sample) {
            $data1 = array('status' => 'Retest');
            $update_test = $this->db->where('sample_test_sample_reg_id', $sample_reg_id)->update('sample_test', $data1);
            if ($update_test) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function additional_test($sample_reg_id, $report_id)
    {
        $data = array('status' => 'Sample Sent for Evaluation', 'revise_flag' => '1', 'released_to_client' => '0');
        $rep_data = array('additional_report_flag' => 1);
        $update_sample = $this->db->where('sample_reg_id', $sample_reg_id)
            ->update('sample_registration', $data);
        $this->db->where('report_id', $report_id)
            ->update('generated_reports', $rep_data);
        if ($update_sample) {
            $data1 = array('status' => 'Record Enter Done');
            $this->db->where('sample_test_sample_reg_id', $sample_reg_id)->update('sample_test', $data1);
            if ($update_sample) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function get_report_approver()
    {
        $query =  $this->db->select('ap.admin_fname,ap.admin_lname,au.uidnr_admin')
            ->from('admin_profile ap')
            ->join('admin_users au', 'au.uidnr_admin=ap.uidnr_admin')
            ->join('admin_role ar', 'ar.id_admin_role=au.id_admin_role')
            ->where_in('ap.ap_signing_auth', '1')
            ->get();
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    public function getsignvalues($data)
    {
        $query =  $this->db->select('CONCAT(ap.admin_fname," ",ap.admin_lname) as name ,au.uidnr_admin,designation_name as admin_role_name')
            ->from('admin_profile ap')
            ->join('admin_users au', 'au.uidnr_admin=ap.uidnr_admin')
            ->join('operator_profile ar', 'ar.uidnr_admin=ap.uidnr_admin')
            ->join('mst_designations', 'admin_designation=designation_id')
            ->where('au.uidnr_admin', $data)
            ->get();
        if ($query) {
            return $query->row();
        } else {
            return false;
        }
    }

    public function sample_final_images($data)
    {
        $query =  $this->db->select('sp.*')
            ->from('sample_registration sr')
            ->join('sample_photos sp', 'sp.sample_reg_id=sr.sample_reg_id')
            ->where('sr.sample_reg_id', $data['sample_reg_id'])
            ->order_by('image_sequence', 'asc')
            ->get();
        if ($query->num_rows()) {
            return $query->result();
        } else {
            return false;
        }
    }

    public function get_reference_images($data)
    {
        $query =  $this->db->select('rri.*')
            ->from('sample_registration sr')
            ->join('report_reference_images rri', 'rri.sample_reg_id=sr.sample_reg_id')
            ->where('sr.sample_reg_id', $data['sample_reg_id'])
            ->get();
        if ($query->num_rows()) {
            return $query->result();
        } else {
            return false;
        }
    }

    public function send_to_record_finding($id)
    {
        $update_sample = $this->db->where('sample_reg_id', $id)->update('sample_registration', array('status' => 'Send For Record Finding'));
        if ($update_sample) {
            $this->db->update('generated_reports', ['primary_approver_status' => 1, 'secondary_approver_status' => 1], ['sample_reg_id' => $id]);
            $data = array('status' => 'Record Enter Done');
            $update_test = $this->db->where('sample_test_sample_reg_id', $id)->update('sample_test', $data);
            if ($update_sample) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function remove_report_sample_image($image_id)
    {
        $delete = $this->db->delete('sample_photos', ['image_id' => $image_id]);
        if ($delete) {
            return true;
        } else {
            return false;
        }
    }
    public function remove_report_reference_image($image_id)
    {
        $delete = $this->db->delete('report_reference_images', ['report_ref_image_id' => $image_id]);
        if ($delete) {
            return true;
        } else {
            return false;
        }
    }

    public function update_comment($inputs, $image_id)
    {
        $update = $this->update_data("sample_photos", $inputs, ["image_id" => $image_id]);
        if ($update) {
            return true;
        }
        return false;
    }
    public function get_generate_report_data($ids)
    {
        $query =  $this->db->select('sr.ulr_no_flag,sr.issuance_date,sr.remark,sr.manual_report_result,sr.manual_report_remark,sr.sample_images_flag,gr.signing_authority,ap.admin_fname as name')
            ->from('sample_registration sr')
            ->where('sr.sample_reg_id', $ids['sample_reg_id'])
            ->join('generated_reports gr', 'gr.sample_reg_id = sr.sample_reg_id', 'left')->join('admin_profile ap', 'ap.uidnr_admin = gr.signing_authority', 'left')->get();
        if ($query) {
            return $query->row();
        } else {
            return false;
        }
    }


    public function get_parts_Details($ids)
    {
        $query = $this->db->select('parts.part_id, parts.parts_desc, parts.parts_sample_reg_id, parts.part_name')
            ->from('parts')
            ->where('parts_sample_reg_id', $ids['sample_reg_id'])
            ->get();
        if ($query) {
            return $query->result_array();
        } else {
            return false;
        }
    }

    public function get_approver_email($data)
    {
        $query = $this->db->select('ap.admin_email as email')
            ->from('admin_users ap')
            ->where('ap.uidnr_admin', $data)
            ->get();
        if ($query) {
            return $query->row();
        } else {
            return false;
        }
    }

    public function get_release_to_client_data($data)
    {
        $query = $this->db->select('sr.sample_customer_id')->from('sample_registration sr')->where('sr.sample_reg_id', $data['sample_reg_id'])->get();
        if ($query) {
            $customer_id = $query->row();
            $status = $this->db->select('cc.email')->from('cust_customers cc')->where('cc.customer_id', $customer_id->sample_customer_id)->get();
            //  echo $this->db->last_query();die;
            if ($status) {
                return $status->row();
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function get_report_format($ids)
    {
        $report_format = $this->db->select('rf.format_name,rf.format_id,rf.branch_id')->from('report_formats rf')->where('rf.branch_id', $ids['branch_id'])->get();
        if ($report_format) {
            return $report_format->result_array();
        } else {
            return false;
        }
    }

    // added by ajit 31-03-2021
    public function update_relase_to_report($where_in)
    {
        $this->db->where_in('sample_reg_id', $where_in);
        $result =  $this->db->update('sample_registration', ['released_to_client' => '1']);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }
    // end

    //  Added by Saurabh on 05-10-2021 to get sample selected test
    public function get_sample_selected_test($sample_reg_id)
    {
        $this->db->select('test_id, test_name, sample_test_id');
        $this->db->join('tests', 'test_id = sample_test_test_id');
        $this->db->where('sample_test_sample_reg_id', $sample_reg_id);
        $this->db->group_start();
        $this->db->where('sample_test.status', 'Mark As Completed By Lab');
        $this->db->or_where('sample_test.status', 'Record Enter Done');
        $this->db->group_end();
        $query = $this->db->get('sample_test');
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }

    //  Added by Saurabh on 05-10-2021 to get sample selected test
    public function get_sample_selected_test_with_rfid($sample_reg_id)
    {
        $this->db->select('test_id, test_name, record_finding_id, st.sample_test_id');
        $this->db->join('tests', 'test_id = sample_test_test_id');
        $this->db->join('record_finding_details rfd', 'sample_registration_id = sample_test_sample_reg_id and rfd.sample_test_id = st.sample_test_id');
        $this->db->where('sample_test_sample_reg_id', $sample_reg_id);
        $this->db->group_start();
        // $this->db->where('st.status !=', 'Mark As Completed By Lab');
        $this->db->where('st.status', 'Record Enter Done');
        $this->db->or_where('st.status', 'Retest');
        $this->db->or_where('st.status !=', 'Completed');
        $this->db->group_end();
        $query = $this->db->get('sample_test st');
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }

    // Added by CHANDAN --13-05-2022
    public function parameter_details($test_id)
    {
        $this->db->select('test_parameters_id, test_parameters_test_id, clouse, test_parameters_disp_name, category, parameter_limit, requirement, priority_order');
        $this->db->from('test_parameters');
        $this->db->where(['test_parameters_test_id' => $test_id, 'is_delete' => 0]);
        $this->db->order_by('test_parameters_id', 'desc');
        $result = $this->db->get();
        return ($result->num_rows() > 0) ? $result->result() : false;
    }

    public function get_parameters_body($record_finding_id)
    {
        $this->db->select('*');
        $this->db->from('record_finding_parameters_body');
        $this->db->where(['record_finding_id' => $record_finding_id, 'is_deleted' => 0]);
        $this->db->order_by('priority_order', 'asc');
        $result = $this->db->get();
        return ($result->num_rows() > 0) ? $result->result() : NULL;
    }

    public function get_parameter_details($record_finding_id, $test_id)
    {
        $this->db->select('
        (IFNULL(rfpb.id, "")) as id,
        (IFNULL(rfpb.test_parameters_id, tp.test_parameters_id)) as test_parameters_id,
        (IFNULL(rfpb.clouse, tp.clouse)) as clouse,
        (IFNULL(rfpb.parameter_name, tp.test_parameters_name)) as parameter_name,
        (IFNULL(rfpb.category, tp.category)) as category,
        (IFNULL(rfpb.limitation, tp.parameter_limit)) as limitation,
        (IFNULL(rfpb.requirement, tp.requirement)) as requirement,
        (IFNULL(rfpb.priority_order, tp.priority_order)) as priority_order,
        rfpb.result_1,
        rfpb.result_2,
        rfpb.result_3,
        rfpb.result_4,
        rfpb.result_5
        ');
        $this->db->from('test_parameters tp');
        $this->db->join('record_finding_parameters_body rfpb', 'tp.test_parameters_id = rfpb.test_parameters_id AND rfpb.is_deleted = 0 AND rfpb.record_finding_id = ' . $record_finding_id . '', 'left');
        $this->db->where(['tp.test_parameters_test_id' => $test_id, 'tp.is_delete' => 0]);
        $this->db->order_by('test_parameters_id', 'desc');
        $result = $this->db->get();
        return ($result->num_rows() > 0) ? $result->result() : NULL;
    }

    public function get_distinct_para_cat($record_finding_id)
    {
        $this->db->distinct();
        $this->db->select('test_id, category');
        $this->db->from('record_finding_parameters_body');
        $this->db->where(['record_finding_id' => $record_finding_id, 'is_deleted' => 0]);
        $this->db->order_by('priority_order', 'asc');
        $result = $this->db->get();
        return ($result->num_rows() > 0) ? $result->result() : NULL;
    }

    public function check_sequence_no($record_finding_id, $sample_reg_id, $sequence_no)
    {
        if (!empty($record_finding_id)) {
            $this->db->where(['sample_registration_id' => $sample_reg_id, 'sequence_no' => $sequence_no]);
            $this->db->where_not_in('record_finding_id', $record_finding_id);
        } else {
            $this->db->where(['sample_registration_id' => $sample_reg_id, 'sequence_no' => $sequence_no]);
        }
        $query = $this->db->get('record_finding_details');
        return ($query->num_rows() > 0) ? true : false;
    }
    // End....
}
