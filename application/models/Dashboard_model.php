<?php

use Mpdf\Tag\Em;

defined('BASEPATH') or exit('No direct access allowed');

class Dashboard_model extends MY_Model
{

    function __construct()
    {
        parent::__construct();
    }

    public function customer_autosuggest($customer_name_string = "")
    {
        return $this->db->select('customer_id, customer_name')
            ->from('cust_customers')
            ->like('customer_name', $customer_name_string, 'after')
            ->limit(5, 0)
            ->order_by('customer_name', 'asc')
            ->get()
            ->result_array();
    }

    public function category_autosuggest($category_name_string = "")
    {

        return $this->db->select('division_id as category_id  , division_name AS category_name')
            ->from('mst_divisions')
            ->like('division_name', $category_name_string, 'after')
            ->limit(5, 0)
            ->order_by('division_name', 'asc')
            ->get()
            ->result_array();
    }

    public function sample_autosuggest($sample_name_string = "")
    {

        return $this->db->select('sample_type_id, sample_type_name')
            ->from('mst_sample_types')
            ->like('sample_type_name', $sample_name_string, 'after')
            ->limit(5, 0)
            ->order_by('sample_type_name', 'asc')
            ->get()
            ->result_array();
    }


    public function trfQuotesChart($filter)
    {

        return $this->db->select('qt.quote_status AS xval, COUNT(qt.quote_status) AS yval')
            ->from('quotes as qt')
            ->where($filter)
            ->join('cust_customers cus', 'cus.customer_id = qt.quotes_customer_id', 'left')
            ->group_by('qt.quote_status')
            ->get()
            ->result_array();
    }

    public function sampleStatusChart($filter)
    {


        $where = "(sr.status='Registered' or sr.status='Sample Accepted'
                or sr.status='Sample Rejected' or sr.status='Sample Completed' 
                or sr.status='Sample Sent for Evaluation' 
                or sr.status='Evaluation Completed' or sr.status='Ready to Start'
                or status='Sample Prepared' or sr.status='Sample Sent for Manual Reporting' 
                or sr.status='Login Cancelled' or sr.status='Report Generated')";
        $this->db->select('sr.status as xval, count(sr.status) as yval');
        $this->db->from('sample_registration as sr');
        $this->db->join('trf_registration as tr','tr.trf_id=sr.trf_registration_id');
        $this->db->where($where);
        $group = 'GROUP BY `sr`.`status`';
        $qry  =  $this->db->get_compiled_select();
        $result =  $this->db->query($qry . ' ' . $filter .' '.$group);
        // echo $this->db->last_query();die;
        return $result->result_array();
    }

    public function reportChart($filter)
    {
        $qry = "SELECT (CASE WHEN sr.manual_report_result=1 THEN 'Pass' 
         WHEN sr.manual_report_result=2 
         THEN 'Fail' WHEN sr.manual_report_result=3 
         THEN 'Refer Result' 
         ELSE 'Not Marked'
         END) AS xval,COUNT(`manual_report_result`) AS yval
         FROM sample_registration as sr
         inner join trf_registration trf on sr.trf_registration_id=trf.trf_id 
         WHERE (sr.status='Report Generated' OR sr.status='Report Approved' OR sr.status='Report Released To Client' OR sr.status = 'Completed') {$filter}
         GROUP BY sr.manual_report_result ";
        // $qry = "SELECT (CASE WHEN sr.manual_report_result=1 THEN 'Pass' 
        //  WHEN sr.manual_report_result=2 
        //  THEN 'Fail' WHEN sr.manual_report_result=3 
        //  THEN 'Refer Result' 
        //  ELSE 'Not Marked'
        //  END) AS xval,COUNT(`manual_report_result`) AS yval
        //  FROM sample_registration as sr
        //  inner join generated_reports as gr on gr.sample_reg_id=sr.sample_reg_id
        //  inner join trf_registration trf on sr.trf_registration_id=trf.trf_id 
        //  WHERE (gr.status='Report Generated' OR gr.status='Report Approved')
        //  AND (gr.report_type='Manual Report' ) {$filter}
        //  GROUP BY sr.manual_report_result ";

        //  $this->db->query($qry);
        //  echo $this->db->last_query();die;
        return $this->db->query($qry)->result_array();
    }


    // Added by CHANDAN --- 27-09-2021 ----
    public function get_admin_user($key)
    {
        $this->db->select('a.uidnr_admin as id, CONCAT(p.admin_fname," ",p.admin_lname) as name, CONCAT(p.admin_fname," ",p.admin_lname) as full_name');
        $this->db->join('admin_profile p', 'a.uidnr_admin = p.uidnr_admin', 'inner');
        $this->db->join('admin_role r', 'a.id_admin_role = r.id_admin_role', 'inner');
        ($key != null) ? $this->db->like('CONCAT(p.admin_fname," ",p.admin_lname)', $key) : '';
        $this->db->where_in('r.id_admin_role', [37, 33, 7]);
        $this->db->where(['a.admin_active' => '1']);
        $this->db->where('CONCAT(p.admin_fname," ",p.admin_lname) is NOT NULL', NULL, FALSE);
        $this->db->order_by('a.uidnr_admin', 'asc');
        $this->db->limit(30);
        $query = $this->db->get('admin_users a');
        return ($query->num_rows() > 0) ? $query->result_array() : [];
    }

    public function get_admin_customers($key)
    {
        $this->db->select('cust.customer_id as id, cust.customer_name as name, cust.customer_name as full_name');
        // $this->db->join('opportunity op', 'op.opportunity_customer_id = cust.customer_id', 'inner');
        ($key != null) ? $this->db->like('cust.customer_name', $key) : '';
        $this->db->where(['cust.isactive' => 'Active']);
        $this->db->order_by('cust.customer_name', 'asc');
        $this->db->limit(30);
        $query = $this->db->get('cust_customers cust');
        return ($query->num_rows() > 0) ? $query->result_array() : [];
    }

    public function get_customersChart($filter,$created_by)
    {
        // echo "<pre>"; print_r($created_by); die;
        $this->db->select('cust.customer_type AS xval, COUNT(cust.customer_type) AS yval, COUNT(CASE WHEN cust.isactive = "Active" then 1 ELSE NULL END) as "Active", COUNT(CASE WHEN cust.isactive = "Inactive" then 1 ELSE NULL END) as "Inactive"');
        if($created_by['cust.created_by'] != 'NULL'){
            $this->db->where_in('cust.created_by',$created_by['cust.created_by']);
        } 
        $this->db->where($filter);
        
        if (!exist_val('Dashboard/filter_by_user', $this->session->userdata('permission'))) {
            $this->db->where(['cust.created_by' => $this->session->userdata('user_data')->uidnr_admin]);
        }
        $this->db->where(['cust.customer_type !=' => ""]);
        $this->db->group_by('cust.customer_type');
        $query = $this->db->get('cust_customers cust');
        // echo $this->db->last_query(); die;
        return ($query->num_rows() > 0) ? $query->result_array() : [];
    }

    public function export_customer_data($filter,$created_by){
        $this->db->select("customer_name, province_name, location_name, city, country_name, telephone, email, customer_type, CONCAT(admin_fname,' ',admin_lname) as created_by, cust.created_on, address, credit, mobile, credit_limit");
        $this->db->join('mst_provinces','cust_customers_province_id = province_id','left');
        $this->db->join('mst_locations','cust_customers_location_id = location_id','left');
        $this->db->join('mst_country','cust_customers_country_id = country_id','left');
        $this->db->join('admin_profile','cust.created_by = uidnr_admin','left');
        ($filter != '')?$this->db->where($filter):'';
        if(!empty($created_by) && $created_by != 'NULL'){
            $this->db->where_in('cust.created_by',$created_by);
        }
        $query = $this->db->get('cust_customers cust');
        if($query->num_rows() > 0){
            return $query->result_array();
        }
        return [];
    }

    public function get_opportunityChart($filter, $type,$created_by,$customer)
    {
        if (!exist_val('Dashboard/filter_opportunity_by_user', $this->session->userdata('permission'))) {
            $this->db->select("YEAR(op.created_on) AS years, DATE_FORMAT(op.created_on, '%b') AS months, SUM(op.opportunity_value) AS opportunity_value");
            $this->db->where('op.opportunity_status','Won');
            $this->db->where('YEAR(op.created_on)','YEAR(CURDATE())');
            $this->db->where('op.created_by',$this->session->userdata('user_data')->uidnr_admin);
            $this->db->group_by('MONTH(op.closure_date),YEAR(op.closure_date)');
            $this->db->order_by("YEAR(op.closure_date), MONTH(op.closure_date)");
            $sql = $this->db->get('opportunity op');
            // $sql = "SELECT 
            // YEAR(op.created_on) AS years, 
            // DATE_FORMAT(op.created_on, '%b') AS months, 
            // SUM(op.opportunity_value) AS opportunity_value 
            // FROM opportunity op 
            // WHERE op.opportunity_status = 'Won' 
            // AND YEAR(op.created_on) = YEAR(CURDATE()) 
            // AND op.created_by =" . $this->session->userdata('user_data')->uidnr_admin . " 
            // GROUP BY MONTH(op.closure_date), YEAR(op.closure_date) 
            // ORDER BY YEAR(op.closure_date), MONTH(op.closure_date)".$query;
        } else {
            $colname = ($type == 'closure_date') ? 'op.closure_date' : 'op.created_on';
            $this->db->select("YEAR(op.created_on) AS years, DATE_FORMAT(op.created_on, '%b') AS months, SUM(op.opportunity_value) AS opportunity_value");
            if(!empty($created_by) && $created_by != 'NULL'){
                $this->db->where_in('op.created_by',$created_by);
            }
            if(!empty($customer) && $customer != 'NULL'){
                $this->db->where_in('op.opportunity_customer_id',$customer);
            }
            ($filter != '')?$this->db->where($filter):'';
            $this->db->group_by("MONTH($colname),YEAR($colname)");
            $this->db->order_by("YEAR($colname), MONTH($colname)");
            $sql = $this->db->get('opportunity op');
            
            // $sql = "SELECT 
            // YEAR($colname) AS years, 
            // DATE_FORMAT($colname, '%b') AS months, 
            // SUM(op.opportunity_value) AS opportunity_value 
            // FROM opportunity op 
            // WHERE op.created_by IS NOT NULL {$filter} 
            // GROUP BY MONTH($colname), YEAR($colname) 
            // ORDER BY YEAR($colname), MONTH($colname)";
        }
        // $query = $this->db->query($sql);
        // echo $this->db->last_query(); die;
        return ($sql->num_rows() > 0) ? $sql->result_array() : [];
    }

    public function export_opportunity_data($filter,$created_by,$customer)
    {
        $this->db->select('cust.*, CONCAT(adm.admin_fname," ",adm.admin_lname) as adm_created_by, op.*');
        $this->db->join('admin_profile adm', 'op.created_by = adm.uidnr_admin', 'left');
        $this->db->join('cust_customers cust', 'op.opportunity_customer_id = cust.customer_id', 'left');
        $this->db->where('op.created_by is NOT NULL', NULL, FALSE);
        ($filter != '')?$this->db->where($filter):'';
        if(!empty($created_by) && $created_by != 'NULL'){
            $this->db->where_in('op.created_by',$created_by);
        }
        if(!empty($customer) && $customer != 'NULL'){
            $this->db->where_in('op.opportunity_customer_id',$customer);
        }
        if (!exist_val('Dashboard/export_opportunity_data', $this->session->userdata('permission'))) {
            $this->db->where(['op.created_by' => $this->session->userdata('user_data')->uidnr_admin]);
        }
        $query = $this->db->get('opportunity op');
        // echo $this->db->last_query(); die;
        return ($query->num_rows() > 0) ? $query->result() : [];
    }

    public function get_performaChart($filter,$created_by, $customer)
    { 
        if (!exist_val('Dashboard/filter_performa_by_user', $this->session->userdata('permission'))) { 
            $this->db->select("YEAR(op.proforma_invoice_date) AS years, DATE_FORMAT(op.proforma_invoice_date, '%b') AS months, SUM(op.total_amount) AS total_amount ");
            $this->db->where('op.invoice_proforma_invoice_status_id',1);
            $this->db->where('YEAR(op.proforma_invoice_date)','YEAR(CURDATE())');
            $this->db->where('op.created_by',$this->session->userdata('user_data')->uidnr_admin);
            $this->db->group_by("MONTH(op.proforma_invoice_date), YEAR(op.proforma_invoice_date)");
            $this->db->order_by("YEAR(op.proforma_invoice_date), MONTH(op.proforma_invoice_date)");
            $query = $this->db->get('invoice_proforma op');

            // $sql = "SELECT 
            // YEAR(op.proforma_invoice_date) AS years, 
            // DATE_FORMAT(op.proforma_invoice_date, '%b') AS months, 
            // SUM(op.total_amount) AS total_amount 
            // FROM invoice_proforma op 
            // WHERE op.invoice_proforma_invoice_status_id = 1  
            // AND YEAR(op.proforma_invoice_date) = YEAR(CURDATE()) 
            // AND op.created_by =" . $this->session->userdata('user_data')->uidnr_admin . " 
            // GROUP BY MONTH(op.proforma_invoice_date), YEAR(op.proforma_invoice_date) 
            // ORDER BY YEAR(op.proforma_invoice_date), MONTH(op.proforma_invoice_date)";
        } else { 
            $this->db->select("YEAR(op.proforma_invoice_date) AS years, DATE_FORMAT(op.proforma_invoice_date, '%b') AS months,SUM(op.total_amount) AS total_amount");
            if(!empty($created_by) && $created_by != 'NULL'){
                $this->db->where_in('op.created_by',$created_by);
            }
            if(!empty($customer) && $customer != 'NULL'){
                $this->db->where_in('op.invoice_proforma_customer_id',$customer);
            }
            ($filter != '')?$this->db->where($filter):'';
            $this->db->group_by("MONTH(op.proforma_invoice_date), YEAR(op.proforma_invoice_date)");
            $this->db->order_by("YEAR(op.proforma_invoice_date), MONTH(op.proforma_invoice_date)");
            $query = $this->db->get('invoice_proforma op');

            // $sql = "SELECT 
            // YEAR(op.proforma_invoice_date) AS years, 
            // DATE_FORMAT(op.proforma_invoice_date, '%b') AS months, 
            // SUM(op.total_amount) AS total_amount 
            // FROM invoice_proforma op 
            // WHERE op.created_by IS NOT NULL {$filter} 
            // GROUP BY MONTH(op.proforma_invoice_date), YEAR(op.proforma_invoice_date) 
            // ORDER BY YEAR(op.proforma_invoice_date), MONTH(op.proforma_invoice_date)";
        }
        // $query = $this->db->query($sql);
        // echo $this->db->last_query(); die;
        return ($query->num_rows() > 0) ? $query->result_array() : [];
    }

    public function export_performa_data($filter,$created_by, $customer)
    {
        $this->db->select('cust.*, CONCAT(adm.admin_fname," ",adm.admin_lname) as adm_created_by, op.*, inv.invoice_status_name');
        $this->db->join('admin_profile adm', 'op.created_by = adm.uidnr_admin', 'left');
        $this->db->join('cust_customers cust', 'op.invoice_proforma_customer_id = cust.customer_id', 'left');
        $this->db->join('invoice_status inv', 'op.invoice_proforma_invoice_status_id = inv.invoice_status_id', 'left');
        $this->db->where('op.created_by is NOT NULL', NULL, FALSE);
        ($filter != '')?$this->db->where($filter):'';
        if(!empty($created_by) && $created_by != 'NULL'){
            $this->db->where_in('op.created_by',$created_by);
        }
        if(!empty($customer) && $customer != 'NULL'){
            $this->db->where_in('op.invoice_proforma_customer_id',$customer);
        }
        if (!exist_val('Dashboard/export_performa_data', $this->session->userdata('permission'))) {
            $this->db->where(['op.created_by' => $this->session->userdata('user_data')->uidnr_admin]);
        }
        $query = $this->db->get('invoice_proforma op');
        // echo $this->db->last_query(); die;
        return ($query->num_rows() > 0) ? $query->result() : [];
    }
    // END ----------------
}
