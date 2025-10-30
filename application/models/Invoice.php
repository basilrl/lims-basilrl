<?php
defined('BASEPATH') or exit('No direct access allowed');

class Invoice extends MY_Model
{

    function __construct()
    {
        parent::__construct();
    }

    public function performa_invoice($per_page, $start, $trf, $customer_name, $product, $created_on, $ulr_no, $gc_number, $proforma_number, $buyer, $status, $division, $sales_person, $client_city, $proforma_amt, $profroma_made, $count = false)
    {
        $checkUser = $this->session->userdata('user_data');
        $this->db->select('invoice_proforma.proforma_invoice_id,Invoices.tax_status, invoice_proforma_irt_no,DATE_FORMAT( proforma_invoice_date, "%d-%b-%Y" ) AS pro_invoice_date, '
            . ' po_number, proforma_invoice_number AS pro_invoice_number, contact_name as contact, '
            . ' pro_client_contact as contact_id, invoice_status_name AS status, '
            . ' (select customer_name from cust_customers where customer_id=trf.trf_applicant) AS client,'
            . ' proforma_invoice_sample_reg_id, wcr_status, assigned_labs, invoice_proforma_customer_id as customer_id, gc_no, '
            . ' trf.trf_product as job_description_id, trf.trf_ref_no  as trf_ref_no, trf.trf_quote_id as job_type_id, proforma_invoice_job_type as type, '
            . ' trf.trf_quote_id as type_id, (select GROUP_CONCAT(reference_no) AS reference_no from quotes where '
            . ' FIND_IN_SET(quote_id,trf.trf_quote_id)) as reference_number, client.customer_name as trf_client, '
            . ' (select customer_name from cust_customers where customer_id=trf.trf_buyer)  AS trf_buyer, proforma_invoice_sample_reg_id as sample_reg_id,file_path,file_name,invoice_proforma.comment, sample_registration.status as sr_status, invoice_proforma.revise_count as pi_revise_count, invoice_proforma.created_by, concat(admin_profile.admin_fname, " ", admin_profile.admin_lname) as proforma_created_by, invoice_proforma.total_amount, concat(admn.admin_fname, " ", admn.admin_lname) as sales_person_name, cust_customers.customer_name, trf_id, trf_quote_id, price_with_gst,invoice_proforma.surcharge_percentage,invoice_proforma.surcharge_amount'); // new surcharge
        $this->db->from('invoice_proforma');
        $this->db->join('purchase_orders', 'invoice_proforma.po_reference_id = purchase_orders.po_id', 'left');
        $this->db->join('contacts', 'contact_id = pro_client_contact', 'left');
        $this->db->join('sample_registration', 'sample_reg_id = proforma_invoice_sample_reg_id', 'left');
        $this->db->join('trf_registration trf', 'trf_id = trf_registration_id', 'left');
        $this->db->join('cust_customers', 'cust_customers.customer_id = invoice_proforma_customer_id', 'left');
        $this->db->join('cust_customers as client', 'client.customer_id = trf.trf_applicant', 'left');
        $this->db->join('cust_customers as buyer', 'buyer.customer_id = trf.trf_buyer', 'left');
        $this->db->join('quotes', 'trf.trf_quote_id = quote_id', 'left');
        $this->db->join('invoice_status', 'invoice_status.invoice_status_id = invoice_proforma_invoice_status_id', 'left');
        $this->db->join('admin_profile', 'admin_profile.uidnr_admin = invoice_proforma.created_by', 'left'); // added by millan on 23-06-2021
        // $this->db->join('admin_users au','au.uidnr_admin = admin_profile.uidnr_admin','left'); // added by millan on 23-06-2021
        $this->db->join('admin_users', 'FIND_IN_SET(admin_users.uidnr_admin,crm_user_id) > 0', 'left', true);
        $this->db->join('mst_locations msl', 'msl.location_id = cust_customers.cust_customers_location_id', 'left');  // added by millan on 24-06-2021
        $this->db->join('admin_profile admn', 'admn.uidnr_admin = trf.sales_person', 'left'); // added by millan on 24-06-2021
        $this->db->join('invoice_proforma_junction ipj','ipj.pro_invoice_id = invoice_proforma.proforma_invoice_id','left'); 
        $this->db->join('Invoices','Invoices.invoiced_id = ipj.invoice_id','left'); //added by kamal on 20 sep 2022/
        // $this->db->join('admin_profile aap', 'FIND_IN_SET(aap.uidnr_admin,crm_user_id) > 0','left',true); // added by millan on 24-06-2021
        /*(select GROUP_CONCAT(DISTINCT CONCAT(aap.admin_fname, " ",aap.admin_lname) ) from admin_profile aap left join trf_registration trfr on FIND_IN_SET(aap.uidnr_admin, crm_user_id ) > 0 )AS crm_person*/

        // $this->db->join('trf_registration trfr','trfr.crm_user_id = admin_users.uidnr_admin','left'); // added by millan on 23-06-2021
        // $this->db->where('invoice_type','proforma');
        // $this->db->where('invoice_status_id','1');
        // $this->db->where('invoice_proforma_irt_no', '');
        // $this->db->where('admin_users.crm_flag','1');// added by millan on 23-06-2021

        // Added by Saurabh on 11-08-2021 to remove controllex division
        $this->db->where('trf.division !=', '34');
        // Added by Saurabh on 11-08-2021 to remove controllex division

        // Added by Saurabh on 04-05-2022 to show proforma only to sales person and other roles array
        $role_id_array[] = ['46', '48', '37', '33', '7'];
        if (in_array($checkUser->id_admin_role, $role_id_array)) {
            $this->db->where('trf.sales_person', $checkUser->id_admin_role);
        }


        if ($customer_name != "null" && $customer_name != "") {
            $this->db->where('trf.trf_applicant', $customer_name);
        }
        if ($division != "null" && $division != "") {
            $this->db->where('trf.division', $division);
        }
        if ($status != "null" && $status != "") {
            $this->db->where('invoice_proforma_invoice_status_id', $status);
        }
        if ($buyer != "null" && $buyer != "") {
            $this->db->where('trf.trf_buyer', $buyer);
        }
        if ($proforma_number != "null" && $proforma_number != "") {
            $this->db->like('proforma_invoice_number', base64_decode($proforma_number));
        }
        if ($product != "null" && $product != "") {
            $this->db->where('sample_registration_sample_type_id', $product);
        }
        if ($created_on != "null" && $created_on != "") {
            $this->db->where('date(proforma_invoice_date)', base64_decode($created_on));
        }
        if ($trf != "null" && $trf != "") {
            $this->db->like('trf.trf_ref_no', base64_decode($trf));
        }
        if ($ulr_no != "null" && $trf != "") {
            $this->db->where('sample_registration.ulr_no', base64_decode($ulr_no));
        }
        /* added by millan on 23-06-2021 */
        if ($sales_person != "null" && $sales_person != "") {
            $this->db->where('trf.sales_person', base64_decode($sales_person));
        }
        /* added by millan on 23-06-2021 */

        /* added by millan on 24-06-2021 */
        if ($client_city != "null" && $client_city != "") {
            $this->db->where('msl.location_id', base64_decode($client_city));
        }
        /* added by millan on 24-06-2021 */

        /* added by millan on 29-06-2021 */
        if ($proforma_amt != "null" && $proforma_amt != "") {
            $this->db->where('invoice_proforma.total_amount', base64_decode($proforma_amt));
        }
        /* added by millan on 29-06-2021 */

        /* added by millan on 29-06-2021 */
        if ($profroma_made != "null" && $profroma_made != "") {
            $this->db->where('admin_profile.uidnr_admin', base64_decode($profroma_made));
        }
        /* added by millan on 29-06-2021 */

        if ($gc_number != "null" && $trf != "") {
            $this->db->like('sample_registration.gc_no', base64_decode($gc_number));
        }
        $this->db->order_by('proforma_invoice_id', 'desc');
        if (!$count) {
        }


        if ($count) {
            $query = $this->db->get();
            // echo "<pre>"; print_r($this->db->last_query()); die;
            $data['last_query'] = $this->db->last_query(); // added by millan on 23-06-2021
            $data['cnt'] = $query->num_rows();
            return $data;
        } else {
            $this->db->limit($start, $per_page);
            $query = $this->db->get();
        }

        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            foreach ($result as $invoice) {
                $invoice_id = $invoice['proforma_invoice_id'];
                $count_query = $this->db->where('invoice_id', $invoice_id)
                    ->get('invoice_dynamic_details');
                $invoice['test_price_count'] = $count_query->num_rows();
                $data[] = $invoice;
            }
            return $data;
        }
        return [];
    }

    public function proforma_invoice_details($proforma_invoice_id, $sample_reg_id)
    {
        $this->db->select('sample_test_id, sample_test_sample_reg_id, a.sample_reg_id, sample_test.status as test_status, a.sample_registration_sample_type_id as sample_type_id, a.sample_registered_to_lab_id, a.sample_name, a.gc_no, c.test_name, count(*) as quantity, tst.test_price as rate, a.sample_desc, (SELECT sample_type_name FROM mst_sample_types WHERE sample_type_id = (SELECT sample_registration_sample_type_id FROM sample_registration WHERE sample_reg_id = sample_test_sample_reg_id )) AS sample_type, proforma_invoice_id, file_path, DATE_FORMAT(a.due_date,"%Y - %m - %d") as due_date');
        $this->db->from('invoice_proforma');
        $this->db->join('sample_registration as a', 'a.sample_reg_id = proforma_invoice_sample_reg_id');
        $this->db->join('sample_test', 'sample_reg_id = sample_test_sample_reg_id');
        $this->db->join('sample_registration as b', 'b.sample_reg_id = sample_test_sample_reg_id');
        $this->db->join('tests c', 'test_id = sample_test_test_id');
        $this->db->join('tests tst', 'tst.test_id = sample_test_test_id');
        $this->db->where('proforma_invoice_id', $proforma_invoice_id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $data['invoiceItemStore'] = $query->result_array()[0];
            $invoice_id = $data['invoiceItemStore']['proforma_invoice_id'];
            $count_query = $this->db->where('invoice_id', $invoice_id)
                ->get('invoice_dynamic_details');
            $data['invoiceItemStore']['test_price_count'] = $count_query->num_rows();
        }

        // Lab name
        $branch_id = $this->get_fields_by_id($table = "sample_registration", $columns = "sample_registration_branch_id", $id = $sample_reg_id, $where = "sample_reg_id")[0]['sample_registration_branch_id'];
        $test_id_data = $this->get_fields_by_id($table = "sample_test", $columns = "sample_test_test_id", $id = $sample_reg_id, $where = "sample_test_sample_reg_id");
        $test_id = "";
        foreach ($test_id_data as $test_ids) {
            if (empty($test_id)) {
                $test_id = $test_ids['sample_test_test_id'];
            } else {
                $test_id = $test_id . "," . $test_ids['sample_test_test_id'];
            }
        }
        $lab_query = $this->db->select('GROUP_CONCAT(DISTINCT  lab_name) AS conducted_lab')
            ->from('mst_labs lb')
            ->join('tests ts', 'ts.test_lab_type_id = lb.mst_labs_lab_type_id', 'LEFT')
            ->join('mst_lab_type mlt', 'mlt.lab_type_id = ts.test_lab_type_id')
            ->where('lb.mst_labs_branch_id', $branch_id)
            ->where_in('ts.test_id', $test_id)
            ->get();
        if ($lab_query->num_rows() > 0) {
            $lab_data = $lab_query->result_array()[0];
        }
        $conductedlab_name = '';
        if ($lab_data['conducted_lab'] != "") {
            $conductedlab_name = "'" . $lab_data['conducted_lab'] . "'" . ' AS conducted_lab,';
        }
        $report_dealine_con = '1:0';
        // Deadline calculation
        $deadline_query = $this->db->select('MAX(DATE_FORMAT(deadline,"%d-%M-%Y %H:%i")) AS dead_line,DATE_ADD( MAX(deadline), INTERVAL "{$report_dealine_con}" HOUR_MINUTE ) AS report_deadline')
            ->from("(SELECT   sr.sample_reg_id,ts.test_id, DATE_ADD( sr.create_on, INTERVAL ts.test_turn_around_time HOUR_MINUTE ) AS deadline FROM sample_registration sr INNER JOIN sample_test st ON st.sample_test_sample_reg_id = sr.sample_reg_id INNER JOIN tests ts ON ts.test_id = st.sample_test_test_id WHERE sample_reg_id ={$sample_reg_id}) AS det")
            ->get();
        if ($deadline_query->num_rows() > 0) {
            $deadline_data = $deadline_query->result_array()[0];
        }
        $deadline_time = '';
        if ($deadline_data['dead_line'] != "") {
            $deadline_time = "'" . $deadline_data['dead_line'] . "'" . ' AS dead_line,';
            $report_deadline_time = "'" . date("d-M-Y", strtotime($deadline_data['report_deadline'])) . "'" . ' AS report_deadline,';
        }

        // Dynamic price data
        $dynamic_price_query = $this->db->get_where('invoice_dynamic_details', ['invoice_id' => $proforma_invoice_id]);
        if ($dynamic_price_query->num_rows() > 0) {
            $data['dynamic_price'] = $dynamic_price_query->result_array();
        } else {
            $data['dynamic_price'] = [];
        }
        // Sample result query
        $sample_result_query = $this->db->select("sr.sample_reg_id, sr.gc_no as gc_num, sr.sample_desc AS sample, {$conductedlab_name}{$deadline_time}{$report_deadline_time} DATE_FORMAT(sr.received_date,'%d-%b-%Y %H:%i') AS sample_received_date, sample_type_name AS sample_type, customer_name as client, container_name as container, sampling_apparatus_name as apparatus, concat(ROUND(qty_received,2),' ',unit) as qty_rec, CONCAT(admin_profile.admin_fname,' ',admin_profile.admin_lname) as signoff_by,  DATE_FORMAT(sr.collection_date,'%d-%b-%Y %H:%i') AS dt_time, CONCAT( ap.admin_fname,' ',ap.admin_lname) as create_by,GROUP_CONCAT(DATE_FORMAT(st.result_ready_date,'%d-%b-%Y')) as result_ready_date, barcode_no, sample_name, DATE_FORMAT(gr.generated_date,'%d-%b-%Y %H:%i') as report_generated, DATE_FORMAT(ra.process_status_time,'%d-%b-%Y %H:%i') as report_approved, (CASE WHEN (Select test_standard_name from mst_test_standards where test_standard_id = sample_registration_test_standard_id) IS NULL THEN 'None' ELSE (Select test_standard_name from mst_test_standards where test_standard_id = sample_registration_test_standard_id) END) AS test_specification, sample_remarks as completed_remark, price_type, (SELECT (CASE WHEN tat_date IS NOT NULL THEN DATE_FORMAT(tat_date,'%d-%b-%Y %H:%i') ELSE '' END) AS tat_date FROM trf_registration WHERE trf_id=trf_registration_id) AS tat_date, (SELECT GROUP_CONCAT(division_name) FROM mst_divisions WHERE division_id IN (SELECT DISTINCT mst_branch_divisions_division_id FROM mst_branch_divisions WHERE mst_branch_divisions_branch_id = 1)) AS division")
            ->from('sample_registration sr')
            ->join('mst_sample_types', 'sample_type_id = sr.sample_registration_sample_type_id')
            ->join('cust_customers', 'customer_id = sr.sample_customer_id', 'left')
            ->join('mst_container_type mct', 'mct.container_type_id=sr.container_type_id', 'left')
            ->join('mst_sampling_apparatus', 'sampling_apparatus_id=sr.sample_registration_sampling_apparatus_id', 'left')
            ->join('admin_profile', 'admin_profile.uidnr_admin = sr.signoff_retest_user', 'left')
            ->join('admin_profile ap', 'sr.create_by = ap.uidnr_admin', 'left')
            ->join('sample_test st', 'sample_test_sample_reg_id = sr.sample_reg_id', 'left')
            ->join('generated_reports gr', 'gr.sample_reg_id= sr.sample_reg_id', 'left')
            ->join('generated_reports ra', 'ra.sample_reg_id= sr.sample_reg_id', 'left')
            ->join('report_sample_remarks', 'remarks_sample_reg_id = sr.sample_reg_id', 'left')
            ->join('units', 'qty_unit = unit_id', 'left')
            ->where('sr.sample_reg_id', $sample_reg_id)
            ->get();
        if ($sample_result_query->num_rows() > 0) {
            $sample_result_data = $sample_result_query->result_array();
            $data['sample_result'] = $sample_result_data[0];
        }
        if (!empty($data)) {
            return $data;
        }
        return [];
    }

    public function proforma_details($proforma_invoice_id)
    {
        $this->db->select('p.proforma_invoice_id,p.proforma_invoice_number,DATE_FORMAT(p.proforma_invoice_date,"%d-%M-%Y") as proforma_invoice_date,cs.customer_id as invoice_proforma_customer_id,cs.customer_name,p.invoice_subject,cs.telephone as proforma_invoice_telephone,cs.fax as proforma_client_fax,cs.email as proforma_client_email, invoice_status_name as invoice_proforma_invoice_status_id');
        $this->db->from('invoice_proforma p');
        $this->db->join('cust_customers cs', 'cs.customer_id = p.invoice_proforma_customer_id', 'left');
        $this->db->join('invoice_status', 'invoice_status_id = p.invoice_proforma_invoice_status_id');
        $this->db->where('p.proforma_invoice_id', $proforma_invoice_id);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->result_array()[0];
        }
        return [];
    }

    public function get_sample_result($sample_reg_id, $proforma_invoice_id)
    {
        $trf_type_query = $this->get_fields_by_id($table = "trf_registration", $columns = "trf_type", $id = $sample_reg_id, $where = "trf_id");
        $trf_type = $trf_type_query[0]['trf_type'];

        // Currency Decimal
        if (!empty($proforma_invoice_id)) {
            if ($trf_type == "TRF") {
                $currency_details_query = $this->db->select('currency_decimal,currency_code')
                    ->from('invoice_proforma')
                    ->join('sample_registration sr', 'sr.sample_reg_id=proforma_invoice_sample_reg_id')
                    ->join('trf_registration trf', 'trf.trf_id=sr.trf_registration_id')
                    ->join('quotes', 'quote_id=trf.trf_quote_id')
                    ->join('mst_currency mst_cr', 'mst_cr.currency_id=quotes_currency_id')
                    ->where('proforma_invoice_id', $proforma_invoice_id)
                    ->get();
            } else {
                $currency_details_query = $this->db->select('currency_decimal,currency_code')
                    ->from('trf_registration')
                    ->join('sample_registration', 'trf_id=trf_registration_id')
                    ->join('mst_currency mst_cr', 'mst_cr.currency_id=open_trf_currency_id')
                    ->where('sample_reg_id', $sample_reg_id)
                    ->get();
            }
            if ($currency_details_query->num_rows() > 0) {
                $currency_details_result = $currency_details_query->result_array();
                $currency_decimal = $currency_details_result[0]['currency_decimal'];
                $currency_code = $currency_details_result[0]['currency_code'];
            }
        } else {
            $currency_decimal = 3;
            $currency_code = '';
        }

        // Analysis test query
        if ($trf_type == "TRF") {
            $this->db->select("sample_test_id, test_name,rate_per_test, product_type, round(total_cost) AS total_cost,discount, round(CASE WHEN sevice_type ='Urgent' THEN (applicable_charge*2 ) WHEN sevice_type ='Express' THEN (applicable_charge+applicable_charge/2) ELSE applicable_charge END) AS applicable_charge,sevice_type,part_name,test_method");
            $this->db->from("(select sample_test_id ,CONCAT((SELECT test_name FROM tests where tests.test_id =sample_test.sample_test_test_id),'(',test_description,')') as test_name ,
            TRUNCATE(rate_per_test,3) as rate_per_test,'Test' AS product_type,(SELECT SUM(applicable_charge) FROM  sample_test WHERE sample_test_sample_reg_id=sample_reg_id) AS total_cost           ,
            sample_test.discount,sample_test.applicable_charge, CASE WHEN FIND_IN_SET(trf_service_type, test_service_type ) > 0 THEN trf_service_type WHEN FIND_IN_SET('Express', test_service_type ) > 0 THEN 'Express' ELSE 'Regular' END AS sevice_type,part_name,test_method,CASE WHEN tat_date IS NOT NULL THEN DATE_FORMAT(tat_date,'%d-%b-%Y %H:%i') ELSE '' END AS tat_date from sample_test
            inner join sample_registration on sample_reg_id = sample_test_sample_reg_id 
            inner join trf_registration on trf_id=trf_registration_id
            LEFT JOIN parts ON parts.part_id=sample_test.sample_part_id
            INNER JOIN tests t ON t.test_id=sample_test.sample_test_test_id          
            where sample_reg_id ='{$sample_reg_id}') as det");
            $analysis_test_query = $this->db->get();
        } else {
            $this->db->select("sample_test_id, test_name,rate_per_test, product_type, round(total_cost) AS total_cost,discount, round(CASE WHEN sevice_type ='Urgent' THEN (applicable_charge*2 ) WHEN sevice_type ='Express' THEN (applicable_charge+applicable_charge/2) ELSE applicable_charge END) AS applicable_charge,sevice_type,part_name,test_method");
            $this->db->from("(select sample_test_id ,CONCAT((SELECT test_name FROM tests where tests.test_id =sample_test.sample_test_test_id),'(',test_description,')') as test_name ,
            TRUNCATE(rate_per_test,3) as rate_per_test,'Test' AS product_type,(SELECT SUM(applicable_charge) FROM  sample_test WHERE sample_test_sample_reg_id=sample_reg_id) AS total_cost           ,
            sample_test.discount,sample_test.applicable_charge, CASE WHEN FIND_IN_SET(trf_service_type, test_service_type ) > 0 THEN trf_service_type WHEN FIND_IN_SET('Express', test_service_type ) > 0 THEN 'Express' ELSE 'Regular' END AS sevice_type,part_name,test_method,CASE WHEN tat_date IS NOT NULL THEN DATE_FORMAT(tat_date,'%d-%b-%Y %H:%i') ELSE '' END AS tat_date from sample_test
            inner join sample_registration on sample_reg_id = sample_test_sample_reg_id 
            inner join trf_registration on trf_id=trf_registration_id
            LEFT JOIN parts ON parts.part_id=sample_test.sample_part_id
            INNER JOIN tests t ON t.test_id=sample_test.sample_test_test_id          
            where sample_reg_id ='{$sample_reg_id}') as det");
            $analysis_test_query = $this->db->get();
        }
        if ($analysis_test_query->num_rows() > 0) {
            $analysis_test_result = $analysis_test_query->result_array();
        }
        // Analysis test pacage query
        $analysis_test_pkg_qry = $this->db->select('sample_test_id ,CONCAT(test_name,"(",test_description,")") as test_name, TRUNCATE(test_price,3) as rate_per_test, test_method, wk.product_type,round(CASE WHEN trf_service_type="Urgent" THEN (wk.total_cost -(wk.total_cost*wk.discount/100))*2 WHEN trf_service_type="Express" THEN (wk.total_cost -(wk.total_cost*wk.discount/100))+((wk.total_cost -(wk.total_cost*wk.discount/100))/2) ELSE wk.total_cost -(wk.total_cost*wk.discount/100) END) AS   total_cost, part_name')
            ->from('sample_test')
            ->join('sample_registration', 'sample_reg_id = sample_test_sample_reg_id')
            ->join('trf_registration', 'trf_id=trf_registration_id')
            ->join('tests t', 't.test_id=sample_test.sample_test_test_id ')
            ->join('works wk', 'wk.work_id=sample_registration.work_id', 'left')
            ->join('parts', 'parts.part_id=sample_test.sample_part_id', 'left')
            ->where('sample_reg_id', $sample_reg_id)
            ->where('product_type !=', 'Test')
            ->get();
        if ($analysis_test_pkg_qry->num_rows() > 0) {
            $analysis_test_pkg_result = $analysis_test_pkg_qry->result_array()[0];
        } else {
            $analysis_test_pkg_result = "";
        }
        // Lab name
        $branch_id = $this->get_fields_by_id($table = "sample_registration", $columns = "sample_registration_branch_id", $id = $sample_reg_id, $where = "sample_reg_id")[0]['sample_registration_branch_id'];
        $test_id_data = $this->get_fields_by_id($table = "sample_test", $columns = "sample_test_test_id", $id = $sample_reg_id, $where = "sample_test_sample_reg_id");
        $test_id = "";
        foreach ($test_id_data as $test_ids) {
            if (empty($test_id)) {
                $test_id = $test_ids['sample_test_test_id'];
            } else {
                $test_id = $test_id . "," . $test_ids['sample_test_test_id'];
            }
        }
        $lab_query = $this->db->select('GROUP_CONCAT(DISTINCT  lab_name) AS conducted_lab')
            ->from('mst_labs lb')
            ->join('tests ts', 'ts.test_lab_type_id = lb.mst_labs_lab_type_id', 'LEFT')
            ->join('mst_lab_type mlt', 'mlt.lab_type_id = ts.test_lab_type_id')
            ->where('lb.mst_labs_branch_id', $branch_id)
            ->where_in('ts.test_id', $test_id)
            ->get();
        if ($lab_query->num_rows() > 0) {
            $lab_data = $lab_query->result_array()[0];
        }
        $conductedlab_name = '';
        if ($lab_data['conducted_lab'] != "") {
            $conductedlab_name = "'" . $lab_data['conducted_lab'] . "'" . ' AS conducted_lab,';
        }
        $report_dealine_con = '1:0';
        // Deadline calculation
        $deadline_query = $this->db->select('MAX(DATE_FORMAT(deadline,"%d-%M-%Y %H:%i")) AS dead_line,DATE_ADD( MAX(deadline), INTERVAL "{$report_dealine_con}" HOUR_MINUTE ) AS report_deadline')
            ->from("(SELECT   sr.sample_reg_id,ts.test_id, DATE_ADD( sr.create_on, INTERVAL ts.test_turn_around_time HOUR_MINUTE ) AS deadline FROM sample_registration sr INNER JOIN sample_test st ON st.sample_test_sample_reg_id = sr.sample_reg_id INNER JOIN tests ts ON ts.test_id = st.sample_test_test_id WHERE sample_reg_id ={$sample_reg_id}) AS det")
            ->get();
        if ($deadline_query->num_rows() > 0) {
            $deadline_data = $deadline_query->result_array()[0];
        }
        $deadline_time = '';
        if ($deadline_data['dead_line'] != "") {
            $deadline_time = "'" . $deadline_data['dead_line'] . "'" . ' AS dead_line,';
            $report_deadline_time = "'" . date("d-M-Y", strtotime($deadline_data['report_deadline'])) . "'" . ' AS report_deadline,';
        }
        // Sample result query
        $sample_result_query = $this->db->select("sr.sample_reg_id, sr.gc_no as gc_num, sr.sample_desc AS sample, {$conductedlab_name}{$deadline_time}{$report_deadline_time} DATE_FORMAT(sr.received_date,'%d-%b-%Y %H:%i') AS sample_received_date, sample_type_name AS sample_type, customer_name as client, container_name as container, sampling_apparatus_name as apparatus, concat(ROUND(qty_received,2),' ',qty_unit) as qty_rec, CONCAT(admin_profile.admin_fname,' ',admin_profile.admin_lname) as signoff_by,  DATE_FORMAT(sr.collection_date,'%d-%b-%Y %H:%i') AS dt_time, CONCAT( ap.admin_fname,' ',ap.admin_lname) as create_by,GROUP_CONCAT(DATE_FORMAT(st.result_ready_date,'%d-%b-%Y')) as result_ready_date, barcode_no, sample_name, DATE_FORMAT(gr.generated_date,'%d-%b-%Y %H:%i') as report_generated, DATE_FORMAT(ra.process_status_time,'%d-%b-%Y %H:%i') as report_approved, (CASE WHEN (Select test_standard_name from mst_test_standards where test_standard_id = sample_registration_test_standard_id) IS NULL THEN 'None' ELSE (Select test_standard_name from mst_test_standards where test_standard_id = sample_registration_test_standard_id) END) AS test_specification, sample_remarks as completed_remark, price_type, (SELECT (CASE WHEN tat_date IS NOT NULL THEN DATE_FORMAT(tat_date,'%d-%b-%Y %H:%i') ELSE '' END) AS tat_date FROM trf_registration WHERE trf_id=trf_registration_id) AS tat_date, (SELECT GROUP_CONCAT(division_name) FROM mst_divisions WHERE division_id IN (SELECT DISTINCT mst_branch_divisions_division_id FROM mst_branch_divisions WHERE mst_branch_divisions_branch_id = 1)) AS division")
            ->from('sample_registration sr')
            ->join('mst_sample_types', 'sample_type_id = sr.sample_registration_sample_type_id')
            ->join('cust_customers', 'customer_id = sr.sample_customer_id')
            ->join('mst_container_type mct', 'mct.container_type_id=sr.container_type_id', 'left')
            ->join('mst_sampling_apparatus', 'sampling_apparatus_id=sr.sample_registration_sampling_apparatus_id', 'left')
            ->join('admin_profile', 'admin_profile.uidnr_admin = sr.signoff_retest_user', 'left')
            ->join('admin_profile ap', 'sr.create_by = ap.uidnr_admin', 'left')
            ->join('sample_test st', 'sample_test_sample_reg_id = sr.sample_reg_id', 'left')
            ->join('generated_reports gr', 'gr.sample_reg_id= sr.sample_reg_id', 'left')
            ->join('generated_reports ra', 'ra.sample_reg_id= sr.sample_reg_id', 'left')
            ->join('report_sample_remarks', 'remarks_sample_reg_id = sr.sample_reg_id', 'left')
            ->where('sr.sample_reg_id', $sample_reg_id)
            ->get();
        if ($sample_result_query->num_rows() > 0) {
            $sample_result_data = $sample_result_query->result_array();
            $data['sample_result'] = $sample_result_data[0];
        }

        // Price type query
        $price_type = $data['sample_result']['price_type'];

        if (!empty($analysis_test_pkg_result)) {
            if ($price_type == "Book Price") {
                echo 1;
                die;
                unset($analysis_test_pkg_result);
                $analysis_test_query = $this->db->select('sample_test_id, test_name, rate_per_test, product_type, round(total_cost) AS total_cost, discount, round(CASE WHEN sevice_type ="Urgent" THEN (applicable_charge*2 )
                WHEN sevice_type = "Express" THEN (applicable_charge+applicable_charge/2)
                ELSE applicable_charge END) AS applicable_charge, sevice_type, test_method')
                    ->from("(select sample_test_id , CONCAT((SELECT test_name FROM tests where tests.test_id =sample_test.sample_test_test_id),'(',test_description,')') as test_name , TRUNCATE(rate_per_test,3) as rate_per_test, wk.product_type, (SELECT SUM(applicable_charge) FROM  sample_test WHERE sample_test_sample_reg_id=sample_reg_id) AS total_cost, sample_test.discount,sample_test.applicable_charge, CASE WHEN FIND_IN_SET(trf_service_type, test_service_type ) > 0 THEN trf_service_type WHEN FIND_IN_SET('Express', test_service_type ) > 0 THEN 'Express' ELSE 'Regular' END AS sevice_type,test_method
                 from sample_test inner join sample_registration on sample_reg_id = sample_test_sample_reg_id inner join trf_registration on trf_id=trf_registration_id INNER JOIN tests t ON t.test_id=sample_test.sample_test_test_id LEFT JOIN works wk ON wk.work_id=sample_registration.work_id where sample_reg_id ='{$sample_reg_id}' AND product_type!='Test')det")
                    ->get();
                echo $this->db->last_query();
                die;
                if ($analysis_test_query->num_rows() > 0) {
                    $analysis_test_result = $analysis_test_query->result_array()[0];
                }
            }
        }
        return ['sample_result' => $data['sample_result'], 'analysis_test_result' => $analysis_test_result];
    }

    public function invoice_template()
    {
        $query = $this->db->select('mapping_id AS  template_id,mapping_template_name AS template_name')
            ->from('report_template_mapping_invoice')
            ->where('section_type', 'ProformaInvoice')
            ->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return [];
    }

    public function report_view($proforma_invoice_id, $template_id)
    {
        $sample_reg_id = $this->invoice->get_fields_by_id($table = "invoice_proforma", $columns = "proforma_invoice_sample_reg_id", $id = $proforma_invoice_id, $where = "proforma_invoice_id")[0]['proforma_invoice_sample_reg_id'];

        $sample_status = $this->invoice->get_fields_by_id($table = "sample_registration", $columns = "status", $id = $sample_reg_id, $where = "sample_reg_id")[0]['status'];

        // Save user log
        $logDetails = array(
            'module' => 'Samples',
            'old_status' => $sample_status,
            'new_status' => 'signoff',
            'sample_reg_id' => $sample_reg_id,
            'sample_assigned_lab_id' => '',
            'action_message' => 'Proforma Invoice signoff',
            'sample_job_id' => /* $sample_status3['sample_job_id'], */ '',
            'report_id' => '',
            'report_status' => '',
            'test_ids' => '',
            'test_names' => '',
            'test_newstatus' => '',
            'test_oldStatus' => '',
            'test_assigned_to' => ''
        );

        $this->invoice->save_user_log($logDetails);

        //Show pdf

        // $update = $this->db->update('invoice_proforma',array('file_name' => $file),['proforma_invoice_id =' . $proforma_invoice_id]);

    }

    public function invoice_log($proforma_invoice_id)
    {
        $query = $this->db->select('action_message, log_activity_on as taken_at, concat(admin_fname," ",admin_lname) as taken_by, old_status, new_status')
            ->join('admin_profile', 'invoice_activity_log.uidnr_admin = admin_profile.uidnr_admin')
            ->where('invoice_id', $proforma_invoice_id)
            ->order_by('invoice_activity_log_id', 'desc')
            ->get('invoice_activity_log');
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }

    public function revise($invoice_id, $revise_status)
    {
        // Get proforma_invoice_sample_reg_id
        $proforma_invoice_sample_reg_id = $this->get_fields_by_id("invoice_proforma", "proforma_invoice_sample_reg_id", $invoice_id, "proforma_invoice_id")[0]['proforma_invoice_sample_reg_id'];
        // Get revise count
        $revise_count_query = $this->db->select('revise_count')
            ->where('proforma_invoice_id', $invoice_id)
            ->get('invoice_proforma');
        if ($revise_count_query->num_rows() > 0) {
            $revise_count_data = $revise_count_query->row_array();
            $revise_count = $revise_count_data['revise_count'];
        } else {
            $revise_count = 0;
        }
        // Update invoice_proforma status
        $this->db->select('proforma_invoice_number');
        $this->db->from('invoice_proforma');
        $this->db->where('proforma_invoice_sample_reg_id', $proforma_invoice_sample_reg_id);
        $proforma_invoice_number = $this->db->get()->result();
        $proforma_invoice_number = $proforma_invoice_number[0]->proforma_invoice_number;
        if (!empty($proforma_invoice_number) && $revise_status == 1) {
            $new_revise_count = $revise_count + 1;
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
            $pi_number = $rev_proforma_invoice_number;
        } else {
            $new_revise_count = $revise_count;
            $pi_number = $proforma_invoice_number;
        }
        $update = $this->db->update('invoice_proforma', ['invoice_proforma_invoice_status_id' => 12, 'proforma_invoice_number' => $pi_number, 'revise_count' => $new_revise_count], ['proforma_invoice_id' => $invoice_id]);

        // Get file_name and proforma_invoice_number
        // $query = $this->db->select('proforma_invoice_number, file_name')
        //                   ->from('invoice_proforma')
        //                   ->where('proforma_invoice_id',$invoice_id)
        //                   ->get();
        // if($query->num_rows() > 0){
        //     $details = $query->result_array()[0];
        // } else {
        //     $details = [];
        // }

        // File path query


        // $revise_data['revise_invoice'] = $invoice_id;
        // $revise_data['created_by'] = $this->session->userdata('user_data')->uidnr_admin;
        // $revise_data['created_on'] = date("Y-m-d H:i:s");
        // // Insert into revise_invoice
        // $insert = $this->db->insert('revise_invoice',$revise_data);

        // $this->db->trans_begin();
        // Update sample_registration status
        // $data['status'] = 'Sample Sent for Evaluation';
        // $update = $this->db->update('sample_registration',$data, ['sample_reg_id' => $proforma_invoice_sample_reg_id]);

        // Update sample_test
        // $sample_test_update = $this->db->update('sample_test',['status' => NULL,'assigned_to'=> NULL],['sample_test_sample_reg_id' => $proforma_invoice_sample_reg_id]);

        // Get generated_report id
        // $report_id = $this->get_fields_by_id($table = "generated_reports", $columns = "report_id", $id = $proforma_invoice_sample_reg_id, $where = "sample_reg_id");

        // if(!empty($report_id)){
        //     $this->db->update('generated_reports',['status' => 'Report Generated', 'revise_report' => 1, 'background_process' => 'New'],['sample_reg_id' => $invoice_id]);
        // }

        // Get Invoice status name
        // $invoice_status_name_query = $this->db->select('invoice_status_name')
        //                                       ->from('invoice_proforma')
        //                                       ->join('invoice_status','invoice_proforma_invoice_status_id=invoice_status_id')
        //                                       ->where('proforma_invoice_id',$invoice_id)
        //                                       ->get();
        // if($invoice_status_name_query->num_rows() > 0){
        //     $invoice_status_name = $invoice_status_name_query->result_array()[0];
        // } else {
        //     $invoice_status_name = [];
        // }

        // Get sample status
        // $sample_status = $this->get_fields_by_id($table = "sample_registration", $columns = "status", $id =$proforma_invoice_sample_reg_id, $where = "sample_reg_id")[0]['status'];

        // // Save user log
        // $logDetails = array('module' => 'Samples',
        //     'old_status' => $sample_status,
        //     'new_status' => 'Revised',
        //     'sample_reg_id' => $proforma_invoice_sample_reg_id,
        //     'sample_assigned_lab_id' => '',
        //     'action_message' => 'Proforma Invoice Revised',
        //     'sample_job_id' => /* $sample_status3['sample_job_id'], */ '',
        //     'report_id' => '',
        //     'report_status' => '',
        //     'test_ids' => '',
        //     'test_names' => '',
        //     'test_newstatus' => '',
        //     'test_oldStatus' => '',
        //     'test_assigned_to' => ''
        // );

        // $this->save_user_log($logDetails);
        // Commit the process
        if ($update) {
            // $this->db->trans_rollback();
            return true;
        } else {
            // $this->db->trans_commit();
            return false;
        }
    }

    public function without_approve($proforma_invoice_id)
    {
        // Get sample_reg_id
        $sample_reg_id = $this->get_fields_by_id($table = "invoice_proforma", $columns = "proforma_invoice_sample_reg_id", $id = $proforma_invoice_id, $where = "proforma_invoice_id")[0]['proforma_invoice_sample_reg_id'];

        $data['status'] = 'Sample Accepted';
        $update = $this->db->update('sample_registration', $data, ['sample_reg_id' => $sample_reg_id]);
        if ($update) {
            return true;
        } else {
            return false;
        }
    }

    public function save_test($record)
    {
        // $insert = $this->db->insert_batch('invoice_dynamic_details', $record);
        // echo $this->db->last_query(); die;
        foreach ($record as $test) {
            $insert = $this->db->insert('invoice_dynamic_details', $test);
        }
        if ($insert) {
            return true;
        } else {
            return false;
        }
    }

    public function get_invoice_details($id)
    {
        // Get TRF id 
        $this->db->select('trf_id,trf_invoice_to');
        $this->db->join('sample_registration', 'proforma_invoice_sample_reg_id = sample_reg_id');
        $this->db->join('trf_registration', 'trf_id = trf_registration_id');
        $this->db->where('proforma_invoice_id', $id);
        $trf_query = $this->db->get('invoice_proforma');
        if ($trf_query->num_rows() > 0) {
            $invoice_to_data = $trf_query->row_array();
            $customer_type = $invoice_to_data['trf_invoice_to'];
            if ($customer_type == 'Factory') {
                $customer_id = 'trf.trf_applicant';
            } elseif ($customer_type == 'Buyer') {
                $customer_id = 'trf.trf_buyer';
            } elseif ($customer_type == 'Agent') {
                $customer_id = 'trf.trf_agent';
            } else {
                $customer_id = 'trf.trf_thirdparty';
            }
        }
        // print_r($invoice_to_data); die;
        // Updated by Saurabh on 05-08-2021
        // $query = $this->db->query("SELECT quotes_currency_id,trf_type,cust.gstin as newgstinnumber,invoice_remark, sr.sample_desc,ip.proforma_invoice_number AS proforma_invoice_number, trf_invoice_to, proforma_invoice_sample_reg_id, ip.show_discount, total_amount, trf_product, proforma_invoice_id, trf_id, cust.customer_name AS customer,cust.customer_code AS applicant_code,trf.trf_ref_no AS reference_no,(select GROUP_CONCAT(reference_no) AS reference_no FROM quotes WHERE  FIND_IN_SET(quote_id,trf.trf_quote_id)) AS quote_ref_no,cust.address AS customer_address,  CASE WHEN service_days!='' THEN CONCAT(trf_service_type,' ',service_days,' ',' Days') ELSE  trf_service_type END AS service_type,country_name AS country,province_name AS state,location_name AS location,cust.city,cust.po_box, /*CONCAT( c.contact_salutation, '. ', c.contact_name)*/ (select GROUP_CONCAT(contact_name SEPARATOR ' / ') from contacts where FIND_IN_SET(contact_id,trf.trf_invoice_to_contact)) AS customer_contact,c.telephone AS contact_telephone,c.mobile_no AS contact_mobile_no,c.email AS contact_email, CASE WHEN trf_type='Open TRF' THEN (SELECT currency_name FROM mst_currency WHERE  currency_id=open_trf_currency_id) ELSE (SELECT currency_name FROM mst_currency WHERE  currency_id=quotes_currency_id) END AS quotes_currency, CASE WHEN trf_type='Open TRF' THEN (SELECT currency_code FROM mst_currency WHERE  currency_id=open_trf_currency_id) ELSE (SELECT currency_code FROM mst_currency WHERE  currency_id=quotes_currency_id) END AS quote_currency_code, CASE WHEN trf_type='Open TRF' THEN (SELECT currency_basic_unit FROM mst_currency WHERE  currency_id=open_trf_currency_id) ELSE  (SELECT currency_basic_unit FROM mst_currency WHERE  currency_id=quotes_currency_id) END AS currency_basic_unit, CASE WHEN trf_type='Open TRF' THEN (SELECT currency_decimal FROM mst_currency WHERE  currency_id=open_trf_currency_id) ELSE  (SELECT currency_decimal FROM mst_currency WHERE  currency_id=quotes_currency_id) END AS  currency_decimal, CASE WHEN trf_type='Open TRF' THEN (SELECT currency_code FROM mst_currency WHERE  currency_id=open_trf_currency_id) ELSE  (SELECT currency_code FROM mst_currency WHERE  currency_id=quotes_currency_id) END AS  currency_code, CASE WHEN trf_type='Open TRF' THEN (SELECT currency_fractional_unit FROM mst_currency WHERE  currency_id=open_trf_currency_id) ELSE (SELECT currency_fractional_unit FROM mst_currency WHERE  currency_id=quotes_currency_id) END AS currency_fractional_unit, (SELECT sample_type_name FROM mst_sample_types  WHERE  sample_type_id=sample_registration_sample_type_id) as sample_name, CASE WHEN trf_invoice_to='Factory' THEN     
        //             (SELECT province_name FROM mst_provinces INNER JOIN cust_customers cs ON cs.cust_customers_province_id=province_id WHERE customer_id=trf_applicant)
        //            WHEN trf_invoice_to='Buyer' THEN 
        //            (SELECT province_name FROM mst_provinces INNER JOIN cust_customers cs ON cs.cust_customers_province_id=province_id WHERE customer_id=trf_buyer) 
        //            ELSE (SELECT province_name FROM mst_provinces INNER JOIN cust_customers cs ON cs.cust_customers_province_id=province_id WHERE customer_id=trf_agent)
        //             END AS state1, '' AS 'GST','' AS 'total_amount_with_gst','' AS 'VAT', (SELECT customer_name FROM cust_customers WHERE customer_id=trf.trf_buyer) AS buyer, (SELECT customer_name FROM cust_customers WHERE customer_id=trf.trf_agent) AS agent, (SELECT gstin FROM cust_customers WHERE customer_id=trf.trf_buyer) AS gstin,DATE_FORMAT( sr.received_date, '%d-%m-%Y %H:%i' ) AS proforma_invoice_date,(select division_name from mst_divisions where trf.division = mst_divisions.division_id) as division,/*DATE_FORMAT(DATE_ADD(sr.received_date, INTERVAL 
        //         CASE WHEN trf_service_type='Regular' THEN 3
        //         WHEN trf_service_type='Express' THEN 2
        //         WHEN trf_service_type='Urgent' THEN 1 END                
        //         DAY), '%d-%m-%Y' ) AS due_date*/ tat_date, due_date,service_days AS sr_days,gc_no AS test_report_no,(SELECT country_name FROM mst_country WHERE country_id=trf_country_orgin) AS trf_country_of_orgin  FROM invoice_proforma ip  INNER JOIN sample_registration sr ON sr.sample_reg_id=ip.proforma_invoice_sample_reg_id  INNER JOIN  trf_registration trf ON trf.trf_id=sr.trf_registration_id  LEFT JOIN quotes qt ON qt.quote_id =(SELECT quote_id FROM quotes WHERE  quote_id IN (trf.trf_quote_id) LIMIT 1 ) INNER JOIN cust_customers cust ON cust.customer_id=$customer_id INNER JOIN mst_country  mctry ON mctry.country_id=cust.cust_customers_country_id LEFT JOIN mst_provinces  mpro ON mpro.province_id=cust.cust_customers_province_id LEFT JOIN mst_locations  mloc ON mloc.location_id=cust.cust_customers_location_id INNER JOIN contacts c ON c.contact_id = trf.trf_contact   WHERE proforma_invoice_id='$id'");

        // new surcharge
        $query = $this->db->query("SELECT cust.cust_type,cust.gstin as newgstinnumber,invoice_remark, sr.sample_desc,ip.proforma_invoice_number AS proforma_invoice_number, trf_invoice_to, proforma_invoice_sample_reg_id, ip.show_discount, total_amount,surcharge_percentage,surcharge_amount, trf_product, proforma_invoice_id, trf_id, cust.customer_name AS customer,cust.customer_code AS applicant_code,trf.trf_ref_no AS reference_no,(select GROUP_CONCAT(reference_no) AS reference_no FROM quotes WHERE  FIND_IN_SET(quote_id,trf.trf_quote_id)) AS quote_ref_no,cust.address AS customer_address,  CASE WHEN service_days!='' THEN CONCAT(trf_service_type,' ',service_days,' ',' Days') ELSE  trf_service_type END AS service_type,country_name AS country, mctry.country_id, province_name AS state,location_name AS location,cust.city,cust.po_box, /*CONCAT( c.contact_salutation, '. ', c.contact_name)*/ (select GROUP_CONCAT(contact_name SEPARATOR ' / ') from contacts where FIND_IN_SET(contact_id,trf.trf_invoice_to_contact)) AS customer_contact,c.telephone AS contact_telephone,c.mobile_no AS contact_mobile_no,c.email AS contact_email, (SELECT currency_name FROM mst_currency WHERE  currency_id=open_trf_currency_id) AS quotes_currency, (SELECT currency_code FROM mst_currency WHERE  currency_id=open_trf_currency_id) AS quote_currency_code,(SELECT currency_basic_unit FROM mst_currency WHERE  currency_id=open_trf_currency_id) AS currency_basic_unit, (SELECT currency_decimal FROM mst_currency WHERE  currency_id=open_trf_currency_id) AS  currency_decimal, (SELECT currency_code FROM mst_currency WHERE  currency_id=open_trf_currency_id) AS  currency_code, (SELECT currency_fractional_unit FROM mst_currency WHERE  currency_id=open_trf_currency_id) AS currency_fractional_unit, (SELECT sample_type_name FROM mst_sample_types  WHERE  sample_type_id=sample_registration_sample_type_id) as sample_name, CASE WHEN trf_invoice_to='Factory' THEN     
                    (SELECT province_name FROM mst_provinces LEFT JOIN cust_customers cs ON cs.cust_customers_province_id=province_id WHERE customer_id=trf_applicant)
                   WHEN trf_invoice_to='Buyer' THEN 
                   (SELECT province_name FROM mst_provinces LEFT JOIN cust_customers cs ON cs.cust_customers_province_id=province_id WHERE customer_id=trf_buyer) 
                   WHEN trf_invoice_to='Agent' THEN  (SELECT province_name FROM mst_provinces LEFT JOIN cust_customers cs ON cs.cust_customers_province_id=province_id WHERE customer_id=trf_agent)
                   ELSE (SELECT province_name FROM mst_provinces LEFT JOIN cust_customers cs ON cs.cust_customers_province_id=province_id WHERE customer_id=trf_thirdparty)
                    END AS state1, '' AS 'GST','' AS 'total_amount_with_gst','' AS 'VAT', (SELECT customer_name FROM cust_customers WHERE customer_id=trf.trf_buyer) AS buyer, (SELECT customer_name FROM cust_customers WHERE customer_id=trf.trf_agent) AS agent, (SELECT gstin FROM cust_customers WHERE customer_id=trf.trf_buyer) AS gstin,DATE_FORMAT( sr.received_date, '%d-%m-%Y %H:%i' ) AS proforma_invoice_date,(select division_name from mst_divisions where trf.division = mst_divisions.division_id) as division,/*DATE_FORMAT(DATE_ADD(sr.received_date, INTERVAL 
                CASE WHEN trf_service_type='Regular' THEN 3
                WHEN trf_service_type='Express' THEN 2
                WHEN trf_service_type='Urgent' THEN 1 END                
                DAY), '%d-%m-%Y' ) AS due_date*/ tat_date, due_date,service_days AS sr_days,gc_no AS test_report_no,(SELECT country_name FROM mst_country WHERE country_id=trf_country_orgin) AS trf_country_of_orgin  FROM invoice_proforma ip  LEFT JOIN sample_registration sr ON sr.sample_reg_id=ip.proforma_invoice_sample_reg_id  LEFT JOIN  trf_registration trf ON trf.trf_id=sr.trf_registration_id  LEFT JOIN quotes qt ON qt.quote_id =(SELECT quote_id FROM quotes WHERE  quote_id IN (trf.trf_quote_id) LIMIT 1 ) LEFT JOIN cust_customers cust ON cust.customer_id=$customer_id LEFT JOIN mst_country  mctry ON mctry.country_id=cust.cust_customers_country_id LEFT JOIN mst_provinces  mpro ON mpro.province_id=cust.cust_customers_province_id LEFT JOIN mst_locations  mloc ON mloc.location_id=cust.cust_customers_location_id LEFT JOIN contacts c ON c.contact_id = trf.trf_contact   WHERE proforma_invoice_id='$id'");
        $checkUser = $this->session->userdata('user_data');
        $user = $checkUser->uidnr_admin;
        // if($user == 56){
        //     echo $this->db->last_query(); die;
        // }
        if ($query->num_rows() > 0) {
            return $query->result()[0];
        }
        return [];
        // changed trf.trf_applicant to trf.trf_invoice_to_contact for Name change in proforma Invoice
    }

    public function get_dynamic_field_value($field_id, $product_id, $trf_id)
    {
        $query = $this->db->select('trf_registrationfield_fields_values')
            ->where('trf_registrationfield_fields_type_id', $product_id)
            ->where('trf_registrationfield_fields_id', $field_id)
            ->where('trf_registrationfield_fields_reg_id', $trf_id)
            ->get('trf_registrationfield');
        // echo $this->db->last_query(); die;
        if ($query->num_rows() > 0) {
            return $query->result_array()[0];
        }
        return [];
    }

    public function check_trf_type_old($sample_reg_id)
    {
        // Get TRF ID
        $trf_id = $this->db->select('trf_registration_id as trf_id')->where('sample_reg_id', $sample_reg_id)->get('sample_registration')->row()->trf_id;
        // Get currency id to get test price
        $currency_id_query = $this->db->select('open_trf_currency_id')
            ->where('trf_id', $trf_id)
            ->get('trf_registration');

        if ($currency_id_query->num_rows() > 0) {
            $currency_id = $currency_id_query->row()->open_trf_currency_id;
        } else {
            $currency_id = 0;
        }

        // Get quotation id
        $quotation_id_query = $this->db->select('trf_quote_id')->where('trf_id', $trf_id)->get('trf_registration');
        if ($quotation_id_query->num_rows() > 0) {
            $quotation_id_data = $quotation_id_query->row();
            if ($quotation_id_data->trf_quote_id != "") {
                $quotation_id = "";
            } else {
                $quotation_id = "";
            }
        } else {
            $quotation_id = "";
        }
        if (empty($quotation_id)) {
            $test_data_query = $this->db->select('test_id, test_name')
                ->join('tests', 'test_id = sample_test_test_id')
                ->where('sample_test_sample_reg_id', $sample_reg_id)
                ->get('sample_test');
            if ($test_data_query->num_rows() > 0) {
                $data = $test_data_query->result_array();
                foreach ($data as $key => $tests) {
                    // Get price for the tests
                    $test_id = $tests['test_id'];
                    $test_price_query = $this->db->select('price')
                        ->where('pricelist_test_id', $test_id)
                        ->where('currency_id', $currency_id)
                        ->get('pricelist');
                    if ($test_price_query->num_rows() > 0) {
                        $test_price = $test_price_query->row()->price;
                    } else {
                        $test_price = 0;
                    }
                    $data[$key]['price'] = $test_price;
                }
            }
        } else {
            $analysis_test_query = $this->db->select('wk.total_cost, (wk.total_cost -(wk.total_cost * wk.discount / 100)) AS original_cost, protocol_name, GROUP_CONCAT(test_name)')
                ->join('works_analysis_test wat', 'wat.work_id = wk.work_id')
                ->join('tests ts', 'ts.test_id = wat.work_test_id', 'left')
                ->join('protocols pc', 'pc.protocol_id = wk.product_type_id')
                ->where('work_job_type', 'Quote')
                ->where('wk.product_type', 'Test')
                ->where('work_job_type_id', $quotation_id)
                ->get('works wk');
            if ($analysis_test_query->num_rows() > 0) {
                $data = $analysis_test_query->result_array();
            }

            $analysis_package_query = $this->db->select('wk.total_cost, (wk.total_cost -(wk.total_cost * wk.discount / 100)) AS original_cost, protocol_name, GROUP_CONCAT(test_name)')
                ->join('works_analysis_test wat', 'wat.work_id = wk.work_id')
                ->join('tests ts', 'ts.test_id = wat.work_test_id', 'left')
                ->join('protocols pc', 'pc.protocol_id = wk.product_type_id')
                ->where('work_job_type', 'Quote')
                ->where('wk.product_type', 'Package')
                ->where('work_job_type_id', $quotation_id)
                ->get('works wk');
            if ($analysis_package_query->num_rows() > 0) {
                $data = $analysis_package_query->result_array();
            }

            $analysis_protocol_query = $this->db->select('wk.total_cost, (wk.total_cost -(wk.total_cost * wk.discount / 100)) AS original_cost, protocol_name, GROUP_CONCAT(test_name)')
                ->join('works_analysis_test wat', 'wat.work_id = wk.work_id')
                ->join('tests ts', 'ts.test_id = wat.work_test_id', 'left')
                ->join('protocols pc', 'pc.protocol_id = wk.product_type_id')
                ->where('work_job_type', 'Quote')
                ->where('wk.product_type', 'Protocol')
                ->where('work_job_type_id', $quotation_id)
                ->get('works wk');
            if ($analysis_protocol_query->num_rows() > 0) {
                $data = $analysis_protocol_query->result_array();
            }
        }
        return $data;
    }



    public function check_trf_type($sample_reg_id)
    {
        // Get TRF ID
        $trf_id = $this->db->select('trf_registration_id as trf_id')->where('sample_reg_id', $sample_reg_id)->get('sample_registration')->row()->trf_id;
        // Get currency id to get test price
        $currency_id_query = $this->db->select('open_trf_currency_id')
            ->where('trf_id', $trf_id)
            ->get('trf_registration');

        if ($currency_id_query->num_rows() > 0) {
            $currency_id = $currency_id_query->row()->open_trf_currency_id;
        } else {
            $currency_id = 0;
        }

        // Check type of the test
        $test_type = $this->db->select('sample_test_quote_type, sample_test_quote_id, sample_test_protocol_id, sample_test_package_id, sample_test_work_id')->where('sample_test_sample_reg_id', $sample_reg_id)->distinct()->get('sample_test');
        if ($test_type->num_rows() > 0) {
            $test_type_data = $test_type->result_array();
            $result = [];
            foreach ($test_type_data as $test_data) {
                if (($test_data['sample_test_quote_id'] > 0)) {
                    if ($test_data['sample_test_quote_type'] == "Package") {
                        $this->db->select('package_name as test_name, sample_test_quote_type, sample_test_quote_id, sample_test_protocol_id, sample_test_package_id, sample_test_work_id, sample_test.rate_per_test as price');
                        // $this->db->join('works', 'work_job_type_id = sample_test_quote_id');
                        $this->db->join('packages','package_id = sample_test_package_id');	
                        $this->db->join('tests', 'sample_test_test_id = test_id');
                        $this->db->where('sample_test_sample_reg_id', $sample_reg_id);
                        $this->db->where('sample_test_quote_type', 'Package');
                        $this->db->group_by('sample_test_sample_reg_id');
                        $query = $this->db->get('sample_test');
                        // echo $this->db->last_query(); die;
                        $result['package'] = $query->result_array();
                    }
                    if ($test_data['sample_test_quote_type'] == "Protocol") {
                        $this->db->select('sample_test.rate_per_test as price, sample_test_quote_type, sample_test_quote_id, sample_test_protocol_id, sample_test_package_id, sample_test_work_id, protocol_name as test_name');
                        // $this->db->join('works', 'work_job_type_id = sample_test_quote_id');
                        $this->db->join('tests', 'sample_test_test_id = test_id');
                        $this->db->join('protocols','protocol_id = sample_test_protocol_id');
                        $this->db->where('sample_test_sample_reg_id', $sample_reg_id);
                        $this->db->where('sample_test_quote_type','Protocol');
                        $this->db->group_by('sample_test_sample_reg_id');
                        $query = $this->db->get('sample_test');
                        
                        $result['protocol'] = $query->result_array();
                    }
                    if ($test_data['sample_test_quote_type'] == "Test") {
                        // $this->db->select('test_name, works_analysis_test.applicable_charge as price, sample_test_quote_type, sample_test_quote_id, sample_test_protocol_id, sample_test_package_id, sample_test_work_id');
                        // $this->db->join('works_analysis_test', 'sample_test_test_id = work_test_id');
                        // $this->db->join('works', 'works_analysis_test.work_id = works.work_id');
                        // $this->db->join('tests', 'work_test_id = test_id');
                        // $this->db->where('sample_test_quote_type', 'Test');
                        // $this->db->where('sample_test_sample_reg_id', $sample_reg_id);
                        // $this->db->where('work_job_type_id', $test_data['sample_test_quote_id']);
                        // $query = $this->db->get('sample_test');
                        $this->db->select('test_name, rate_per_test as price, sample_test_quote_type, sample_test_quote_id, sample_test_protocol_id, sample_test_package_id, sample_test_work_id');
                        $this->db->join('tests', 'test_id = sample_test_test_id');
                        $this->db->where('sample_test_sample_reg_id', $sample_reg_id);
                        $this->db->where('sample_test_quote_type','Test');
                        $query = $this->db->get('sample_test');
                        // echo $this->db->last_query(); die;
                        $result['test_data'] = $query->result_array();
                    }
                } elseif ($test_data['sample_test_package_id'] > 0) {
                    $this->db->select('package_name as test_name, pricelist.price as price, sample_test_quote_type, sample_test_quote_id, sample_test_protocol_id, sample_test_package_id, sample_test_work_id');
                    $this->db->join('tests ts', 'ts.test_id=sample_test_test_id', 'left');
                    $this->db->join('pricelist price', 'price.pricelist_test_id=ts.test_id AND price.currency_id=' . $currency_id, 'left');
                    $this->db->join('pricelist', 'pricelist.type_id=sample_test_package_id AND pricelist.type="Package" AND pricelist.currency_id=' . $currency_id, 'left');
                    $this->db->join('packages','package_id = sample_test_package_id');	
                    $this->db->where('sample_test_sample_reg_id', $sample_reg_id);
                    $this->db->group_by('sample_test_sample_reg_id');
                    $query = $this->db->get('sample_test');
                    $result['package'] = $query->result_array();
                } elseif ($test_data['sample_test_protocol_id'] > 0) {
                    $this->db->select('protocol_name as test_name, pricelist.price as price, sample_test_quote_type, sample_test_quote_id, sample_test_protocol_id, sample_test_package_id, sample_test_work_id');
                    $this->db->join('tests ts', 'ts.test_id=sample_test_test_id', 'left');
                    $this->db->join('protocols','protocol_id = sample_test_protocol_id');
                    $this->db->join('pricelist price', 'price.pricelist_test_id=ts.test_id AND price.currency_id=' . $currency_id, 'left');
                    $this->db->join('pricelist', 'pricelist.type_id=sample_test_protocol_id AND pricelist.type="Protocol" AND pricelist.currency_id=' . $currency_id, 'left');
                    $this->db->where('sample_test_sample_reg_id', $sample_reg_id);
                    $this->db->group_by('sample_test_sample_reg_id');
                    $query = $this->db->get('sample_test');
                    $result['protocol'] = $query->result_array();
                } else {
                    $test_data_query = $this->db->select('test_name, price, sample_test_quote_type, sample_test_quote_id, sample_test_protocol_id, sample_test_package_id, sample_test_work_id')
                        ->join('tests', 'test_id = sample_test_test_id')
                        ->join('pricelist', 'pricelist_test_id = test_id and currency_id = '.$currency_id, 'left')
                        ->where('sample_test_sample_reg_id', $sample_reg_id)
                        ->group_by('test_id')
                        ->get('sample_test');
                    // echo $this->db->last_query(); die;
                    if ($test_data_query->num_rows() > 0) {
                        $result['test_data'] = $test_data_query->result_array();
                    }
                }
            }
            return $result;
        }
    }

    public function get_country_id()
    {
        $query = $this->db->select('mst_branches_country_id')
            ->join('mst_branches', 'default_branch_id = branch_id')
            ->where('operator_profile.uidnr_admin', $this->admin_id())
            ->get('operator_profile');
        if ($query->num_rows() > 0) {
            return $query->result_array()[0]['mst_branches_country_id'];
        }
        return [];
    }

    public function get_test_price($invoice_id, $sample_reg_id)
    {
        $query = $this->db->select('dynamic_heading, dynamic_value,quantity,discount,applicable_charge,inv_dyn_id,
        invoice_quote_type, invoice_quote_id, invoice_protocol_id, invoice_package_id, invoice_work_id')
            ->where('invoice_id', $invoice_id)
            ->where('sample_registration_id', $sample_reg_id)
            ->order_by('inv_dyn_id', 'asc')
            ->get('invoice_dynamic_details');
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return [];
    }

    // Added by Saurabh on 05-08-2021 to get remark
    public function get_remark($invoice_id)
    {
        $this->db->select('invoice_remark,surcharge_percentage,surcharge_amount');// new surcharge
        $this->db->where('proforma_invoice_id', $invoice_id);
        $query = $this->db->get('invoice_proforma');
        if ($query->num_rows() > 0) {
            return $query->row_array();
        }
        return [];
    }

    public function update_test($record)
    {
        foreach ($record as $test) {
            $insert = $this->db->insert('invoice_dynamic_details', $test);
        }
        if ($insert) {
            return true;
        } else {
            return false;
        }
    }

    /* added by millan on 23-06-2021 for sales person*/
    public function get_sales_person()
    {
        $query = $this->db->select('concat(ap.admin_fname, " ", ap.admin_lname) as name, au.uidnr_admin')
            ->from('admin_profile ap')
            ->join('admin_users au', 'au.uidnr_admin = ap.uidnr_admin', 'left')
            ->where('au.sales_person', 1)
            ->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return false;
        }
    }

    /* added by millan on 24-06-2021 for client city*/
    public function get_client_city()
    {
        $query = $this->db->select('msl.location_id, msl.location_name')
            ->from('cust_customers cust')
            ->join('mst_locations msl', 'msl.location_id = cust.cust_customers_location_id', 'left')
            ->where('cust.isactive', 'Active')
            ->where('msl.location_name IS NOT NULL')
            ->order_by('msl.location_name', 'ASC')
            ->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return false;
        }
    }

    /* added by millan on 29-06-2021 for proforma amount */
    public function get_proforma_created()
    {
        $query = $this->db->select('concat(ap.admin_fname, " ", ap.admin_lname) as name, ap.uidnr_admin')
            ->from('admin_profile ap')
            ->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return false;
        }
    }
    /* added by millan on 29-06-2021 for proforma created by */

    public function get_test($proforma_invoice_id)
    {
        $this->db->where('invoice_id', $proforma_invoice_id);
        $this->db->group_start();
        $this->db->where('invoice_quote_type !=', 'Package');
        $this->db->where('invoice_quote_type !=', 'Protocol');
        $this->db->group_end();
        $this->db->group_start();
        $this->db->where('invoice_protocol_id',0);
        $this->db->where('invoice_package_id',0);
        $this->db->group_end();
        $query = $this->db->get('invoice_dynamic_details');
        //echo $this->db->last_query(); exit;
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return [];
    }

    public function get_package($proforma_invoice_id)
    {
        $this->db->where('invoice_id', $proforma_invoice_id);
        $this->db->group_start();
        $this->db->where('invoice_quote_type', 'Package');
        $this->db->or_where('invoice_package_id >',0);
        $this->db->group_end();
        $this->db->order_by('applicable_charge','desc');
        $query = $this->db->get('invoice_dynamic_details');
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return [];
    }

    public function get_protocol($proforma_invoice_id)
    {
        $this->db->where('invoice_id', $proforma_invoice_id);
        $this->db->group_start();
        $this->db->where('invoice_quote_type', 'Protocol');
        $this->db->or_where('invoice_protocol_id >',0);
        $this->db->group_end();
        $this->db->order_by('applicable_charge','desc');
        $query = $this->db->get('invoice_dynamic_details');
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return [];
    }
}
