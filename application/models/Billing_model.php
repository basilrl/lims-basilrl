<?php

use phpDocumentor\Reflection\Types\Null_;

defined('BASEPATH') or exit('No direct script access allowed');

class Billing_model extends MY_Model
{
    public function get_status()
    {
        $query = $this->db->select('distinct(status) as status')->get('sample_registration');
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return [];
    }

    public function get_invoices($id)
    {
        $this->db->select("ip.proforma_invoice_id, concat(ap.admin_fname, '', ap.admin_lname) as created_by");
        $this->db->from('invoice_proforma` as `ip');
        $this->db->join(' Invoices as ic', 'ip.proforma_invoice_id  = ic.`proforma_invoice_id`', 'left');
        $this->db->join(' admin_profile  as ap', 'ap.uidnr_admin   = ic.`generated_by`', 'left');
        $this->db->where('ip.proforma_invoice_id', $id);
        $query = $this->db->get();
        $result = $query->row_array();
        return $result;
        // echo $this->db->last_query(); die;
    }

    public function get_billing_list($start, $end, $trf, $customer_name, $product, $created_on,  $gc_number, $buyer, $status, $pi,  $start_date, $end_date, $due_date, $count = null)
    {
        $this->db->select("sr.ulr_no, sr.sample_desc, sr.gc_no, sr.sample_reg_id as sample_reg_id, 
        (CASE WHEN tr.trf_service_type ='Regular' AND ( tr.service_days IS NULL OR service_days='')
         THEN CONCAT(tr.trf_service_type,' 3 Days') 
        WHEN tr.trf_service_type ='Express' THEN CONCAT(tr.trf_service_type,' 2 Days') 
        WHEN tr.trf_service_type ='Urgent'  THEN CONCAT(tr.trf_service_type,' 1 Days') 
        WHEN tr.service_days IS NOT NULL OR tr.service_days!='' THEN CONCAT(tr.trf_service_type,' ',tr.service_days,'Days') END) AS sample_service_type, cc.customer_name as client,tr.trf_ref_no,mst.sample_type_name as product_name,sr.received_date,sr.status, due_date, sr.qty_received, gr.report_id, gr.manual_report_file, gr.report_num, gr.manual_report_worksheet, gr.report_type, sr.revise_count, ip.proforma_invoice_number, ip.file_path, ip.proforma_invoice_id, ic.invoiced_id, cc.customer_id,ip.is_invoice_generated, ic.tax_status, invd.invoice_id, ic.invoice_pdf_path");
        $this->db->from('invoice_proforma ip');
        $this->db->join('sample_registration sr', 'ip.proforma_invoice_sample_reg_id  = sr.sample_reg_id', 'inner');
        $this->db->join('trf_registration tr', 'tr.trf_id = sr.trf_registration_id', 'inner');
        $this->db->join('contacts cont', 'cont.contact_id = tr.trf_invoice_to_contact', 'inner');
        $this->db->join('cust_customers cc', 'cc.customer_id = cont.contacts_customer_id', 'inner');

        $this->db->join('mst_sample_types mst', 'mst.sample_type_id = sr.sample_registration_sample_type_id', 'left');
        $this->db->join('generated_reports gr', 'gr.sample_reg_id = sr.sample_reg_id', 'left');
        $this->db->join('invoice_details invd', 'ip.proforma_invoice_id = invd.proforma_id', 'left');
        $this->db->join('Invoices ic', 'invd.invoice_id = ic.invoiced_id', 'left');
        $this->db->where_in('ip.invoice_proforma_invoice_status_id', ['4', '14']);

        // $this->db->from('sample_registration sr');
        // $this->db->join('trf_registration tr', 'tr.trf_id = sr.trf_registration_id', 'left');
        // $this->db->join('cust_customers as cc', 'cc.customer_id = tr.trf_applicant', 'left');
        // $this->db->join('mst_sample_types as mst', 'mst.sample_type_id = sr.sample_registration_sample_type_id', 'left');
        // $this->db->join('invoice_proforma as ip', 'ip.proforma_invoice_sample_reg_id  = sr.`sample_reg_id`', 'left');
        // $this->db->join('generated_reports as gr', 'gr.sample_reg_id = sr.sample_reg_id', 'left');
        // $this->db->join('Invoices as ic', 'ic.proforma_invoice_id  = ip.proforma_invoice_id', 'left');
        // $this->db->where('ip.invoice_proforma_invoice_status_id', '4');

        if (exist_val('Branch/Wise', $this->session->userdata('permission'))) {
            $multibranch = $this->session->userdata('branch_ids');
            $this->db->group_start();
            $this->db->where(['sr.sample_registration_branch_id IN (' . $multibranch . ') ' => null]); //branch_id
            $this->db->group_end();
        }

        if ($customer_name != "null" && $customer_name != "") {
            $this->db->where('tr.open_trf_customer_id', $customer_name);
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

        if ($pi != "null" && $pi != "") {
            $this->db->like('ip.proforma_invoice_number', base64_decode($pi));
        }

        if (!empty($start_date) && $start_date != "null") {
            $sdate = base64_decode($start_date);
            $edate = ($end_date != 'null') ? base64_decode($end_date) : date('Y-m-d');
            $this->db->group_start();
            $this->db->where(['date(sr.create_on) BETWEEN "' . $sdate . '" AND  "' . $edate . '" ' => null]);
            $this->db->group_end();
        }

        if ($due_date != "null" && $due_date != "") {
            $this->db->where('sr.due_date', trim(base64_decode($due_date)));
        }

        if ($trf != "null" && $trf != "") {
            $this->db->like('tr.trf_ref_no', trim(base64_decode($trf)));
        }

        if ($gc_number != "null" && $gc_number != "") {
            $this->db->like('sr.gc_no', trim(base64_decode($gc_number)));
        }
        if (!$count) {
            $this->db->limit($start, $end);
        }
        // $this->db->order_by('sr.sample_reg_id', 'desc');
        // $this->db->group_by('sr.sample_reg_id');

        $this->db->order_by('ip.proforma_invoice_id', 'desc');
        $this->db->group_by('sr.sample_reg_id');

        $query = $this->db->get();
        // echo $this->db->last_query(); die;
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

    public function get_billing_log($sample_id)
    {

        $sample_query = $this->db->select('operation, log_activity_on as taken_at, old_status, new_status, concat(admin_fname," ",admin_lname) as taken_by')
            ->join('admin_profile', 'sample_reg_activity_log.uidnr_admin = admin_profile.uidnr_admin')
            ->where('sample_reg_id', $sample_id)
            // ->order_by('sample_reg_log_id   ', 'desc')
            ->get_compiled_select('sample_reg_activity_log');


        $invoice_id_query = $this->db->select('proforma_invoice_id')->where('proforma_invoice_sample_reg_id', $sample_id)->get('invoice_proforma');
        $invoice_id_result = $invoice_id_query->row_array();
        $invoice_id = $invoice_id_result['proforma_invoice_id'];


        // Get invoice log
        $invoice_log_query = $this->db->select('operation, log_activity_on as taken_at, old_status, new_status, concat(admin_fname," ",admin_lname) as taken_by')
            ->join('admin_profile', '	invoice_activity_log.uidnr_admin = admin_profile.uidnr_admin')
            ->where('invoice_id', $invoice_id)
            // ->order_by('jobs_activity_log_id', 'desc')
            ->get_compiled_select('invoice_activity_log');

        $query = $this->db->query($sample_query . ' UNION ' . $invoice_log_query . ' order by taken_at desc');

        if ($query->num_rows() > 0) {
            $sample_log = $query->result_array();
            return $sample_log;
        }
        return [];
    }

    public function download_proforma($proforma_invoice_id)
    {
        $this->db->select('file_path');
        $this->db->from('invoice_proforma');
        $path = $this->db->where('proforma_invoice_id', $proforma_invoice_id)->get();
        if ($path) {
            return $path->row();
        } else {

            return false;
        }
    }

    public function download_report_pdf($sample_reg_id, $report_id)
    {
        $this->db->select('gr.manual_report_file, gr.original_file_name');
        $this->db->from('generated_reports gr');
        //$this->db->where('report_id', $report_id);
        $path = $this->db->where('sample_reg_id', $sample_reg_id)->get();
        if ($path) {
            return $path->row();
        } else {
            return false;
        }
    }

    // Added by CHANDAN --01-08-2022
    public function sync_invoice_details_table()
    {
        $this->db->select('sr.sample_reg_id, sr.sample_customer_id as customer_id, pi.proforma_invoice_id, inv.invoiced_id, idd.dynamic_heading, idd.dynamic_value, idd.quantity, idd.discount, idd.applicable_charge, idd.invoice_quote_type, mi.uploadfilepath');
        $this->db->from('invoice_dynamic_details idd');
        $this->db->join('invoice_proforma pi', 'idd.invoice_id = pi.proforma_invoice_id', 'inner');
        $this->db->join('sample_registration sr', 'pi.proforma_invoice_sample_reg_id = sr.sample_reg_id', 'inner');
        $this->db->join('Invoices inv', 'pi.proforma_invoice_id = inv.proforma_invoice_id', 'inner');
        $this->db->join('manual_invoice mi', 'inv.invoiced_id = mi.invoice_id', 'left');
        $query = $this->db->get();
        return ($query->num_rows() > 0) ? $query->result() : false;
    }

    public function fetch_all_gc_number($customer_id)
    {
        $this->db->select('pi.proforma_invoice_id, sr.sample_reg_id, sr.gc_no');
        $this->db->from('invoice_proforma pi');
        $this->db->join('sample_registration sr', 'pi.proforma_invoice_sample_reg_id = sr.sample_reg_id', 'inner');
        $this->db->join('trf_registration trf', 'trf.trf_id = sr.trf_registration_id', 'inner');
        // $this->db->join('Invoices inv', 'pi.proforma_invoice_id != inv.proforma_invoice_id', 'inner');
        //$this->db->where(['pi.is_invoice_generated' => 0]);
        $this->db->group_start();
        $this->db->or_where('sr.sample_customer_id', $customer_id);
        $this->db->or_where('trf.trf_applicant', $customer_id);
        $this->db->or_where('trf.trf_buyer', $customer_id);
        $this->db->or_where('trf.trf_thirdparty', $customer_id);
        $this->db->group_end();

        $query = $this->db->get();
        //$this->db->last_query(); exit;
        return ($query->num_rows() > 0) ? $query->result() : false;
    }

    public function fetch_gc_number($proforma_invoice_id)
    {
        $this->db->select('pi.proforma_invoice_id, sr.sample_reg_id, sr.gc_no');
        $this->db->from('invoice_proforma pi');
        $this->db->join('sample_registration sr', 'pi.proforma_invoice_sample_reg_id = sr.sample_reg_id', 'inner');
        $this->db->where('pi.proforma_invoice_id', $proforma_invoice_id);
        $query = $this->db->get();
        return ($query->num_rows() > 0) ? $query->row() : false;
    }

    public function get_open_trf($proforma_invoice_id)
    {
        $this->db->where('invoice_id', $proforma_invoice_id);
        $this->db->where('invoice_quote_type', 0);
        $this->db->where('invoice_quote_id', 0);
        $this->db->where('invoice_protocol_id', 0);
        $this->db->where('invoice_package_id', 0);
        $query = $this->db->get('invoice_dynamic_details');
        // echo $this->db->last_query(); die;
        return ($query->num_rows() > 0) ? $query->result_array() : [];
    }

    public function get_test($proforma_invoice_id)
    {
        $this->db->where('invoice_id', $proforma_invoice_id);
        $this->db->group_start();
        $this->db->where('invoice_quote_type !=', 'Package');
        $this->db->where('invoice_quote_type !=', 'Protocol');
        $this->db->group_end();
        $this->db->group_start();
        $this->db->where('invoice_quote_id >','0');
        $this->db->where('invoice_protocol_id',0);
        $this->db->where('invoice_package_id',0);
        $this->db->group_end();
        $query = $this->db->get('invoice_dynamic_details');
        return ($query->num_rows() > 0) ? $query->result_array() : [];
    }

    public function get_package($proforma_invoice_id)
    {
        $this->db->where('invoice_id', $proforma_invoice_id);
        $this->db->where('invoice_package_id >', 0);
        $query = $this->db->get('invoice_dynamic_details');
        // echo $this->db->last_query(); die;
        return ($query->num_rows() > 0) ? $query->result_array() : [];
    }

    public function get_protocol($proforma_invoice_id)
    {
        $this->db->where('invoice_id', $proforma_invoice_id);
        $this->db->where('invoice_protocol_id >', 0);
        $query = $this->db->get('invoice_dynamic_details');
        return ($query->num_rows() > 0) ? $query->result_array() : [];
    }

    public function fetch_distinct_gc_no($cust_id, $inv_id)
    {
        $this->db->select('GROUP_CONCAT(distinct(sr.gc_no)) as gc_no, cu.nav_customer_code');
        $this->db->from('invoice_details invd');
        $this->db->join('sample_registration sr', 'invd.sample_reg_id = sr.sample_reg_id', 'inner');
        $this->db->join('cust_customers cu', 'invd.customer_id = cu.customer_id', 'inner');
        $this->db->where(['invd.customer_id' => $cust_id, 'invd.invoice_id' => $inv_id, 'invd.sample_reg_id >' => 0, 'invd.modify_invoice_flag <' => 3]);
        $query = $this->db->get();
        return ($query->num_rows() > 0) ? $query->row() : NULL;
    }

    public function get_invoice_details_data($cust_id, $inv_id)
    {
        $this->db->select('*');
        $this->db->where(['customer_id' => $cust_id, 'invoice_id' => $inv_id, 'modify_invoice_flag <' => 3]);
        $query = $this->db->get('invoice_details');
        return ($query->num_rows() > 0) ? $query->result() : NULL;
    }
    public function get_NewData($cust_id)
    {
        $this->db->select("trf.division,mst.erpdivision_code,trf.sales_person,trf.trf_applicant , concat(ap.admin_fname, '', ap.admin_lname) as sales_person_name");
        $this->db->where('trf.trf_applicant',$cust_id);
        $this->db->from('trf_registration as trf')
        ->join('admin_profile as ap','ap.uidnr_admin=trf.sales_person','left')
        ->join('mst_divisions as mst','mst.division_id=trf.division', 'left');
        $query = $this->db->get();
        return ($query->num_rows() > 0) ? $query->row() : NULL;
    }
}
