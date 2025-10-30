<?php
defined('BASEPATH') or exit('No Direct Access Allowed');

class SampleRegistration_model extends MY_Model
{
    function __construct()
    {
        parent::__construct();
    }

    public function get_data($id)
    {
        $query = $this->db->select('quotes.quote_id, quotes.customer_id, quotes.quotation_type, trf_id, trf.product_id, product_name, sample_desc, id_mark, seal, tested_for_spec, trf.category_id, category.category_name, letter_ref, letter_date, seal_no,temp_of_sample, container_type, sample_quantity, remark, remnant_sample, estimated_date,sample_entry_by, sample_reciept_time, exporting_country, country_name, food_cust_customers.customer_id, customer_name, concat(address," ",city) as address, quotation_type')
            ->join('mst_products mp', 'trf.product_id = mp.product_id', 'left')
            ->join('mst_category category', 'trf.category_id = category.category_id', 'left')
            ->join('food_mst_country', 'country_id = trf.exporting_country', 'left')
            ->join('food_cust_customers', 'customer_id = test_cer_company_id')
            ->join('quotes', 'trf.quote_id = quotes.quote_id')
            ->where('trf_id', $id)
            ->get('trf_registration trf');
        if ($query->num_rows() > 0) {
            $data = $query->row_array();
            return $data;
        }
        return [];
    }

    public function get_registered_sample($start, $end, $search, $sortby, $order, $count = false)
    {
        // pre_r($search); die;
        $this->db->select('sr.sample_reg_id,customer_name as client_name, category_name, product_name, trf_ref_no, DATE_FORMAT(sr.created_on,"%d-%m-%Y") as created_on, concat(admin_fname," ",admin_lname) as created_by, gr.manual_report_file,gr.qr_code_name, gc_number, ulr_number,sr.status,sr.proforma_generated,barcode_path,q.quote_id, q.branch_id, q.quote_ref_no, due_date, count(sc.sample_test_id) as test_count, count(alc.sample_test_id) as lab_assign_count, sr.samp_proforma_id'); // updated by millan on 09-02-2022
        $this->db->join('food_cust_customers', 'party_name = customer_id', 'left');
        $this->db->join('mst_category', 'mst_category.category_id = sr.sample_category', 'left');
        $this->db->join('mst_products', 'mst_products.product_id = sr.product_id', 'left');
        $this->db->join('admin_profile', 'uidnr_admin = sr.created_by', 'left');
        $this->db->join('trf_registration', 'trf_id = trf_reg_id', 'left');
        $this->db->join('quotes q', 'q.quote_id = trf_registration.quote_id', 'left');
        $this->db->join('generated_reports as gr', 'gr.sample_reg_id = sr.sample_reg_id AND (gr.additional_report_flag <> 1 AND  gr.revise_report <> "1" )', 'left');
        // Added by saurabh on 19-11-2021 to get test count
        $this->db->join('sample_test sc', 'sc.sample_test_sample_reg_id = sr.sample_reg_id');
        // Added by saurabh on 19-11-2021 to get test count
        // Added by saurabh on 19-11-2021 to get assign lab count
        $this->db->join('sample_test alc', 'alc.sample_test_sample_reg_id = sr.sample_reg_id and alc.sample_test_assigned_lab_id !=""', 'left');
        // Added by saurabh on 19-11-2021 to get assign lab count
        ($search['uidnr_admin'] != 'NULL') ? $this->db->like('sr.created_by', $search['uidnr_admin']) : '';
        ($search['gc_number'] != 'NULL') ? $this->db->like('gc_number', $search['gc_number']) : '';
        ($search['trf_ref_no'] != 'NULL') ? $this->db->like('trf_ref_no', $search['trf_ref_no']) : '';
        ($search['customer_id'] != 'NULL') ? $this->db->where('party_name', $search['customer_id']) : '';
        ($search['product_id'] != 'NULL') ? $this->db->like('sr.product_id', $search['product_id']) : '';
        ($search['status'] != 'NULL') ? $this->db->like('sr.status', $search['status']) : '';
        ($search['quote_ref_no'] != 'NULL') ? $this->db->like('q.quote_ref_no', $search['quote_ref_no']) : '';
        ($search['ulr_number'] != 'NULL') ? $this->db->like('ulr_number', $search['ulr_number']) : '';
        //Added by saurabh on 03-03-2022
        $branch_id = $this->session->userdata('branch_id');
        $this->db->where('q.branch_id', $branch_id);
        //Added by saurabh on 03-03-2022

        if ($search['start_dt'] != 'NULL' && $search['end_dt'] != 'NULL') {
            $this->db->where(['sr.created_on >=' => $search['start_dt'], 'sr.created_on <=' => $search['end_dt']]);
        }

        $this->db->group_by('sample_reg_id');
        (!$count) ? $this->db->limit($start, $end) : '';
        if ($sortby != 'NULL' || $order != 'NULL') {
            $this->db->order_by($sortby, $order);
        } else {
            $this->db->order_by('sample_reg_id', 'desc');
        }
        $query = $this->db->get('sample_registration sr');

        if ($query->num_rows() > 0) {
            return ($count) ? $query->num_rows() : $query->result_array();
        }
    }

    public function update_status($status, $id)
    {
        $old_status_query = $this->db->select('status')->from('sample_registration')->where('sample_reg_id', $id)->get();
        $old_status = $old_status_query->row();
        $old_status = $old_status_query->row()->status;
        $query = $this->db->update('sample_registration', $status, ['sample_reg_id' => $id]);
        if ($query) {
            return true;
        } else {
            return false;
        }
    }

    // public function fetch_data_for_gc($cond)
    // {
    //     $query = $this->db->select('sr.gc_number')
    //         ->from('sample_registration sr')
    //         ->where('sr.sample_reg_id', $cond)->get();
    //     if ($query->num_rows() > 0) {
    //         return $query->row_array();
    //     } else {
    //         return false;
    //     }
    // }

    /**
     * Updated by saurabh on 31-03-2022 to reset analysis number
     */
    public function fetch_data_for_gc($cond)
    {
        $query = $this->db->select('sr.gc_number')
            ->from('sample_registration sr')
            ->where('sr.sample_reg_id', $cond)->get();
        if ($query->num_rows() > 0) {
            return $query->row_array();
        } else {
            return false;
        }
    }
    /**
     * Updated by saurabh on 31-03-2022 to reset analysis number
     */

    public function download_qr($cond)
    {
        $this->db->select('gr.manual_report_file');
        $this->db->from('generated_reports gr');
        $path = $this->db->where('gr.sample_reg_id', $cond)->get();
        if ($path) {
            return $path->row();
        } else {
            return false;
        }
    }

    public function download_pdf_aws($cond)
    {
        $this->db->select('gr.manual_report_file');
        $this->db->from('generated_reports gr');
        $path = $this->db->where('gr.sample_reg_id', $cond)->get();
        if ($path) {
            return $path->row();
        } else {
            return false;
        }
    }

    public function send_for_evaluation($status, $id)
    {
        $old_status_query = $this->db->select('status')->from('sample_registration')->where('sample_reg_id', $id)->get();
        $old_status = $old_status_query->row()->status;

        $query = $this->db->update('sample_registration', $status, ['sample_reg_id' => $id]);
        if ($query) {
            $logDetails = array(
                'source_module' => 'SampleRegistration',
                'operation' => 'send_for_evaluation',
                'record_id' => $id,
                'old_status'  => $old_status,
                'new_status' => $status['status'],
                'uidnr_admin'    => $this->admin_id(),
                'log_activity_on' => date("Y-m-d H:i:s")
            );
            $this->save_user_log($logDetails);
            return true;
        } else {
            return false;
        }
    }

    public function get_sample_details($sample_reg_id)
    {
        $this->db->select('gc_number, recieved_date, customer_name as party_name, sr.letter_date, mc.category_name as sample_category, mp.product_name, sr.sample_desc, specification, sr.sample_entry_by, sr.status,barcode_path,sr.sample_quantity, due_date');
        $this->db->join('food_cust_customers', 'customer_id = party_name');
        $this->db->join('trf_registration tr', 'trf_reg_id = trf_id');
        $this->db->join('quotes q', 'q.quote_id = tr.quote_id');
        $this->db->join('mst_category mc', 'mc.category_id = sample_category');
        $this->db->join('mst_products mp', 'mp.product_id = sr.product_id');
        $this->db->where('sr.sample_reg_id', $sample_reg_id);
        $details_query = $this->db->get('sample_registration sr');

        if ($details_query->num_rows() > 0) {
            $data['sample_details'] = $details_query->row_array();
        }

        $this->db->select('test_name as group_name, test_parameters_name as param_name, specification_name as spec_name, sample_part_id, sample_test_id, sample_test_sample_reg_id as sample_id');
        $this->db->join('test_group', 'test_id = st_test_group', 'left');
        $this->db->join('tbl_specification', 'tbl_specification.id = st_test_specification', 'left');
        $this->db->join('test_parameters', 'test_parameters.test_parameters_id = st_testing_parameter', 'left');
        $this->db->where('sample_test_sample_reg_id', $sample_reg_id);
        $this->db->where('is_deleted !=', '2');
        $test_query = $this->db->get('sample_test');

        if ($test_query->num_rows() > 0) {
            $tests_details = $test_query->result_array();
            foreach ($tests_details as $value) {
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
                $test[] = $final;
            }
            $data['tests_details'] = $test;
        }

        // $type = $data['sample_details']['quotation_type'];

        // $join = NULL;
        // $join2 = NULL;
        // $join3 = NULL;
        // if ($type == 1) {
        //     $column = 'test_parameters_id as id, test_parameters_name as name';
        //     $join = $this->db->join('test_parameters', 'test_parameters_id = st_testing_parameter');
        // }
        // if ($type == 2) {
        //     $column = 'test_parameters_id as id, GROUP_CONCAT(DISTINCT test_parameters_name) as name, test_id, test_name';
        //     $join = $this->db->join('test_group', 'test_id = st_test_group');
        //     $join2 = $this->db->join('test_parameters', 'FIND_IN_SET(test_parameters.test_parameters_id,tests.parameter_id) > 0', 'left', false);
        // }
        // if ($type == 3) {
        //     $column = 'test_parameters_id as id, GROUP_CONCAT(DISTINCT test_parameters_name) as name, test_id, GROUP_CONCAT(DISTINCT test_name) as test_name, tbl_specification.id as spec_id, specification_name';
        //     $join = $this->db->join('tbl_specification', 'tbl_specification.id = st_test_specification', 'left');
        //     $join2 = $this->db->join('test_parameters', 'FIND_IN_SET(test_parameters.test_parameters_id,tbl_specification.parameter_id) > 0', 'left', false);
        //     $join3 = $this->db->join('test_group', 'FIND_IN_SET(tests.test_id,tbl_specification.group_id) > 0', 'left', false);
        // }
        // ($join != 'NULL') ? $join : '';
        // ($join2 != 'NULL') ? $join2 : '';
        // ($join3 != 'NULL') ? $join3 : '';

        // $this->db->select('sample_test_sample_reg_id as sample_id, sample_test_id,sample_part_id, ' . $column);
        // $this->db->where('sample_test_sample_reg_id', $sample_reg_id);
        // $test_query = $this->db->get('sample_test');
        // if ($test_query->num_rows() > 0) {
        //     $tests_details = $test_query->result_array();
        //     foreach ($tests_details as $value) {
        //         $final = $value;
        //         $sample_part_id = explode(",", $value['sample_part_id']);
        //         $part_name_query = $this->db->select('GROUP_CONCAT(part_name) as part_name')
        //             ->where_in('part_id', $sample_part_id)
        //             ->get('parts');
        //         if ($part_name_query->num_rows() > 0) {
        //             $part_name = $part_name_query->row()->part_name;
        //         } else {
        //             $part_name = "";
        //         }

        //         $final['part_name'] = $part_name;
        //         $test[] = $final;
        //     }
        //     $data['tests_details'] = $test;
        // }
        return (!empty($data)) ? $data : [];
    }

    public function get_sample_log($sample_id)
    {
        $query = $this->db->select('operation, log_activity_on as taken_at, old_status, new_status, concat(admin_fname," ",admin_lname) as taken_by')
            ->join('admin_profile', 'user_log.uidnr_admin = admin_profile.uidnr_admin')
            ->where('record_id', $sample_id)
            ->where('source_module', 'SampleRegistration')
            ->order_by('id', 'desc')
            ->get('user_log');
        // echo $this->db->last_query(); die;
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return [];
    }

    public function get_category($key, $limit)
    {
        $this->db->select('category_id as id, category_name as name, category_name as full_name');
        ($key != null) ? $this->db->like('category_name', $key) : '';
        ($limit != null) ? $this->db->limit($limit) : '';
        $query = $this->db->get('mst_category');
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return [];
    }

    public function get_product_name($category_id, $key, $limit)
    {
        $this->db->select('product_id as id, product_name as name, product_name as full_name');
        $this->db->where('category_id', $category_id);
        ($key != null) ? $this->db->like('product_name', $key) : '';
        ($limit != null) ? $this->db->limit($limit) : '';
        $query = $this->db->get('mst_products');
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return [];
    }

    public function get_selected_customer($customer_id)
    {
        $this->db->select('customer_id as id, customer_name as name, customer_name as full_name');
        $this->db->where('customer_id', $customer_id);
        $query = $this->db->get('food_cust_customers');
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return [];
    }

    public function add_test_for_sample($test_id, $sample_id)
    {
        $data = ['sample_test_sample_reg_id' => $sample_id, 'sample_test_test_id' => $test_id];
        $query = $this->db->insert('sample_test', $data);
        if ($query) {
            return true;
        }
        return false;
    }

    public function sample_selected_test($sample_reg_id)
    {
        $this->db->select('quotation_type');
        $this->db->join('trf_registration tr', 'trf_reg_id = trf_id');
        $this->db->join('quotes q', 'q.quote_id = tr.quote_id');
        $this->db->where('sr.sample_reg_id', $sample_reg_id);
        $details_query = $this->db->get('sample_registration sr');

        if ($details_query->num_rows() > 0) {
            $sample_details = $details_query->row_array();
        }

        $type = $sample_details['quotation_type'];

        $join = NULL;
        $join2 = NULL;
        $join3 = NULL;
        if ($type == 1) {
            $column = 'test_parameters_id as id, test_parameters_name as name';
            $join = $this->db->join('test_parameters', 'test_parameters_id = st_testing_parameter');
        }
        if ($type == 2) {
            $column = 'test_parameters_id as id, GROUP_CONCAT(DISTINCT test_parameters_name) as name, test_id, test_name';
            $join = $this->db->join('test_group', 'test_id = st_test_group');
            $join2 = $this->db->join('test_parameters', 'FIND_IN_SET(test_parameters.test_parameters_id,test_group.parameter_id) > 0', 'left', false);
        }
        if ($type == 3) {
            $column = 'test_parameters_id as id, GROUP_CONCAT(DISTINCT test_parameters_name) as name, test_id, GROUP_CONCAT(DISTINCT test_name) as test_name, tbl_specification.id as spec_id, specification_name';
            $join = $this->db->join('tbl_specification', 'tbl_specification.id = st_test_specification', 'left');
            $join2 = $this->db->join('test_parameters', 'FIND_IN_SET(test_parameters.test_parameters_id,tbl_specification.parameter_id) > 0', 'left', false);
            $join3 = $this->db->join('test_group', 'FIND_IN_SET(test_group.test_id,tbl_specification.group_id) > 0', 'left', false);
        }
        ($join != 'NULL') ? $join : '';
        ($join2 != 'NULL') ? $join2 : '';
        ($join3 != 'NULL') ? $join3 : '';

        $this->db->select('sample_test_sample_reg_id as sample_id, sample_test_id,sample_part_id, ' . $column);
        $this->db->where('sample_test_sample_reg_id', $sample_reg_id);
        $test_query = $this->db->get('sample_test');
        if ($test_query->num_rows() > 0) {
            $tests_details = $test_query->result_array();
            foreach ($tests_details as $value) {
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
                $test[] = $final;
            }
            $data = $test;
        }
        return (!empty($data)) ? $data : [];
    }

    public function get_worksheet_details($id)
    {
        $this->db->select('sr.gc_number, sr.sample_desc, con.country_name as destination_country, sr.sample_seal, sr.container_type, sr.sample_quantity, sr.sample_weight, sr.sampling_status, sr.sample_entry_by, sr.remark, sr.specification, sr.temp_of_sample, DATE_FORMAT(sr.created_on,"%d/%m/%Y") as created_on, DATE_FORMAT(sr.recieved_date,"%d/%m/%Y") as recieved_date, DATE_FORMAT(sr.recieved_date,"%h:%i %p") as recieved_time, DATE_FORMAT(sr.estimated_date,"%d/%m/%Y") as estimated_date, cat.category_name, prod.product_name, gen.report_num');
        $this->db->join('mst_category cat', 'sr.sample_category = cat.category_id', 'left');
        $this->db->join('mst_products prod', 'sr.product_id = prod.product_id', 'left');
        $this->db->join('food_mst_country con', 'con.country_id = sr.destination_country', 'left');
        $this->db->join('generated_reports gen', 'sr.sample_reg_id = gen.sample_reg_id', 'left');
        $this->db->where('sr.sample_reg_id', $id);
        $query = $this->db->get('sample_registration sr');
        if ($query->num_rows() > 0) {
            return $query->row_array();
        }
        return [];
    }

    public function get_distinct_quotation_type($id)
    {
        $this->db->distinct();
        $this->db->select('quotation_type');
        $this->db->where('sample_test_sample_reg_id', $id);
        $this->db->order_by('quotation_type', 'asc');
        $this->db->where('is_deleted !=', '2');
        $query = $this->db->get('sample_test');
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return [];
    }

    // public function get_worksheet_test_details($quotation_type, $sample_id)
    // {
    //     if ($quotation_type == 1) {

    //         $this->db->select('tp.test_parameters_name AS parameter_name, un.unit as unit_name, measure.uncertainty_name');
    //         $this->db->join('units un', 'tp.test_parameters_unit = un.unit_id', 'left');
    //         $this->db->join('tbl_measure_uncertainty measure', 'tp.uncertainty_id = measure.id', 'left');
    //         $this->db->join('sample_test st', 'tp.test_parameters_id = st.st_testing_parameter', 'inner');
    //         $this->db->where(['st.sample_test_sample_reg_id' => $sample_id, 'st.is_deleted' => 0]);
    //         $query = $this->db->get('test_parameters tp');
    //         return ($query->num_rows() > 0) ? $query->result_array() : [];
    //     }
    //     if ($quotation_type == 2) {

    //         $this->db->select('tp.test_parameters_name AS parameter_name, un.unit as unit_name, measure.uncertainty_name');
    //         $this->db->join('units un', 'tp.test_parameters_unit = un.unit_id', 'left');
    //         $this->db->join('tbl_measure_uncertainty measure', 'tp.uncertainty_id = measure.id', 'left');
    //         $this->db->join('test_group te', 'FIND_IN_SET(tp.test_parameters_id, te.parameter_id) > 0', 'inner', false);
    //         $this->db->join('sample_test st', 'te.test_id = st.st_test_group', 'inner');
    //         $this->db->where(['st.sample_test_sample_reg_id' => $sample_id, 'st.is_deleted' => 0]);
    //         $query = $this->db->get('test_parameters tp');
    //         return ($query->num_rows() > 0) ? $query->result_array() : [];
    //     }
    //     if ($quotation_type == 3) {

    //         $this->db->select('tp.test_parameters_id, tp.test_parameters_name AS parameter_name, un.unit as unit_name, measure.uncertainty_name, spc1.specification_name as group_spec_name');
    //         $this->db->join('units un', 'tp.test_parameters_unit = un.unit_id', 'left');
    //         $this->db->join('tbl_measure_uncertainty measure', 'tp.uncertainty_id = measure.id', 'left');
    //         $this->db->join('tbl_specification spc1', 'FIND_IN_SET(tp.test_parameters_id, spc1.parameter_id) > 0', 'inner', false);
    //         $this->db->join('sample_test st', 'spc1.id = st.st_test_specification', 'inner');
    //         $this->db->where(['st.sample_test_sample_reg_id' => $sample_id, 'st.is_deleted' => 0]);
    //         $query1 = $this->db->get_compiled_select('test_parameters tp', FALSE);
    //         $this->db->reset_query();

    //         $this->db->select('tp1.test_parameters_id, tp1.test_parameters_name AS parameter_name, un.unit as unit_name, measure.uncertainty_name, spc.specification_name as group_spec_name');
    //         $this->db->join('units un', 'tp1.test_parameters_unit = un.unit_id', 'left');
    //         $this->db->join('tbl_measure_uncertainty measure', 'tp1.uncertainty_id = measure.id', 'left');
    //         $this->db->join('test_group te', 'FIND_IN_SET(tp1.test_parameters_id, te.parameter_id) > 0', 'inner', false);
    //         $this->db->join('tbl_specification spc', 'FIND_IN_SET(te.test_id, spc.group_id) > 0', 'inner', false);
    //         $this->db->join('sample_test st', 'spc.id = st.st_test_specification', 'inner');
    //         $this->db->where(['st.sample_test_sample_reg_id' => $sample_id, 'st.is_deleted' => 0]);
    //         $query2 = $this->db->get_compiled_select('test_parameters tp1', FALSE);
    //         $this->db->reset_query();

    //         $query = $this->db->query("$query1 UNION ALL $query2");
    //         return ($query->num_rows() > 0) ? $query->result_array() : [];
    //     }
    // }

    public function get_worksheet_test_details($quotation_type, $sample_id)
    {
        if ($quotation_type == 1) {

            $this->db->select('tpd.tp_details_id, tp.test_parameters_name AS parameter_name, un.unit as unit_name, measure.uncertainty_name');
            $this->db->join('test_parameter_details tpd', 'st.st_testing_parameter = tpd.test_parameters_id and st.parameter_method_id = tpd.method_id and st.service_category = tpd.category_id and st.name_of_commodity = tpd.product_id', 'inner');
            $this->db->join('test_parameters tp', 'tpd.test_parameters_id = tp.test_parameters_id', 'inner');
            $this->db->join('units un', 'tpd.unit_id = un.unit_id', 'left');
            $this->db->join('tbl_measure_uncertainty measure', 'tpd.uncertainty_id = measure.id', 'left');
            $this->db->where(['st.sample_test_sample_reg_id' => $sample_id, 'st.is_deleted !=' => '2']);
            $this->db->where(['tpd.is_deleted' => 0, 'tpd.status' => 1]);
            $this->db->group_by('tp.test_parameters_id');
            $query = $this->db->get('sample_test st');
            return ($query->num_rows() > 0) ? $query->result_array() : [];
        }
        if ($quotation_type == 2) {

            $this->db->select('tgd.tgd_id, tp.test_parameters_name AS parameter_name, "" as unit_name, "" as uncertainty_name, tg.test_name as group_spec_name');
            $this->db->join('test_group_details tgd', 'st.st_test_group = tgd.test_group_id', 'inner');
            $this->db->join('test_group tg', 'tgd.test_group_id = tg.test_id and st.service_category = tg.tg_category_id and st.name_of_commodity = tg.tg_product_id', 'inner');
            $this->db->join('test_parameters tp', 'tgd.test_parameter_id = tp.test_parameters_id', 'inner');
            $this->db->where(['st.sample_test_sample_reg_id' => $sample_id, 'st.is_deleted !=' => '2']);
            $this->db->group_by('tp.test_parameters_id');
            $query = $this->db->get('sample_test st');
            return ($query->num_rows() > 0) ? $query->result_array() : [];
        }
        if ($quotation_type == 3) {

            $this->db->select('tp.test_parameters_id, tp.test_parameters_name AS parameter_name, "" as unit_name, "" as uncertainty_name, spc.specification_name as group_spec_name');
            $this->db->join('tbl_specification spc', 'st.st_test_specification = spc.id and st.service_category = spc.ts_category_id and st.name_of_commodity = spc.ts_product_id', 'inner');
            $this->db->join('tbl_specification_details tsd', 'tsd.tbl_specification_id = spc.id', 'inner');
            $this->db->join('test_parameters tp', 'tsd.test_parameter_id = tp.test_parameters_id', 'inner');
            $this->db->where(['st.sample_test_sample_reg_id' => $sample_id, 'st.is_deleted !=' => '2']);
            $this->db->group_by('tp.test_parameters_id');
            $query = $this->db->get('sample_test st');
            return ($query->num_rows() > 0) ? $query->result_array() : [];
        }
    }

    public function get_test_certificate($id)
    {
        $this->db->select('customer_name, concat(cust.address," ",cust.city) as address, tr.letter_date, tr.letter_ref, sr.recieved_date,category_name, product_name,sr.id_mark, tested_for_spec');
        $this->db->join('trf_registration tr', 'sr.trf_reg_id = tr.trf_id');
        $this->db->join('food_cust_customers cust', 'customer_id = test_cer_company_id');
        $this->db->join('mst_category mc', 'mc.category_id = tr.category_id');
        $this->db->join('mst_products mp', 'mp.product_id = tr.product_id');
        $this->db->where('sample_reg_id', $id);
        $query = $this->db->get('sample_registration sr');
        if ($query->num_rows() > 0) {
            return $query->row_array();
        }
        return [];
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
                        'source_module'    => 'SampleRegistration',
                        'operation'        => 'save_sample_parts',
                        'record_id'     => $record['parts_sample_reg_id'],
                        'uidnr_admin'    => $this->sr->admin_id(),
                        'log_activity_on' => date("Y-m-d H:i:s"),
                        'action_message'    => 'Part added to sample'
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
                if ($save) {
                    $old_status_query = $this->db->select('status')->from('sample_registration')->where('sample_reg_id', $record['parts_sample_reg_id'])->get();
                    $old_status = $old_status_query->row()->status;
                    $logDetails = array(
                        'source_module'    => 'SampleRegistration',
                        'operation'        => 'save_sample_parts',
                        'record_id'     => $record['parts_sample_reg_id'],
                        'uidnr_admin'    => $this->sr->admin_id(),
                        'log_activity_on' => date("Y-m-d H:i:s"),
                        'action_message'    => 'Part updated to sample'
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

    public function get_company($key, $limit)
    {
        $this->db->select('customer_id as id, customer_name as name, customer_name as full_name');
        ($key != null) ? $this->db->like('customer_name', $key) : '';
        ($limit != null) ? $this->db->limit($limit) : '';
        $query = $this->db->get('food_cust_customers');
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return [];
    }

    /**-------------------clone sample------------------------------------- */
    public function get_sample_data($sample_id)
    {
        $res = $this->db->select('*')->from('sample_registration')->where('sample_reg_id', $sample_id)->get();

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
            $unique = 'BA' . $branch_code . $division_code . date('y') . $rand;
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
            $unique = 'BA' . $branch_code . $division_code . date('y') . $date . $rand;
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


    // Added by Saurabh on 15-07-2021 to get labs
    public function get_labs_for_transfer($key, $branch_id)
    {
        $this->db->select('branch_id as id, branch_name as name, branch_name as full_name');
        ($key != NULL) ? $this->db->like('branch_name', $key) : '';
        $this->db->where_not_in('branch_id', $branch_id);
        $query = $this->db->get('food_mst_branches');
        // echo $this->db->last_query(); die;
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return [];
    }
    // Added by Saurabh on 15-07-2021 to get labs

    // Added by Saurabh on 15-07-2021 to get Quote fields data
    public function get_quote_fields($quote_id)
    {
        // Get quotation type
        $this->db->select('quotation_type');
        $this->db->where('quote_id', $quote_id);
        $query = $this->db->get('quotes');
        if ($query->num_rows() > 0) {
            $data['quotation'] = $query->row_array();
            // Get the data according to quotation type
            if ($data['quotation']['quotation_type'] == 1) {
                $this->db->select('test_parameters_name, test_parameters_id, quote_field_id');
                $this->db->join('test_parameters', 'testing_parameter = test_parameters_id');
                $this->db->where('quote_id', $quote_id);
                $this->db->where('is_deleted !=', '2');
                $tp_query = $this->db->get('quotes_fields');
                if ($tp_query->num_rows() > 0) {
                    $data['testing_parameters'] = $tp_query->result_array();
                } else {
                    $data['testing_parameters'] = [];
                }
            }
            if ($data['quotation']['quotation_type'] == 2) {
                $this->db->select('test_id, test_name, quote_field_id');
                $this->db->join('test_group', 'test_id = test_group');
                $this->db->where('quote_id', $quote_id);
                $this->db->where('is_deleted !=', '2');
                $tp_query = $this->db->get('quotes_fields');
                if ($tp_query->num_rows() > 0) {
                    $data['test_group'] = $tp_query->result_array();
                } else {
                    $data['test_group'] = [];
                }
            }
            if ($data['quotation']['quotation_type'] == 3) {
                $this->db->select('id, specification_name, quote_field_id');
                $this->db->join('tbl_specification', 'id = test_specification');
                $this->db->where('quote_id', $quote_id);
                $this->db->where('is_deleted !=', '2');
                $sp_query = $this->db->get('quotes_fields');
                if ($sp_query->num_rows() > 0) {
                    $data['specification'] = $sp_query->result_array();
                } else {
                    $data['specification'] = [];
                }
            }
        }
        return $data;
    }

    // Added by Saurabh on 15-07-2021 to get Quote fields data
    public function get_quote_field_by_id($param_id)
    {
        $this->db->select('tat_date, sampling_quantity, service_category, st_testing_parameter as testing_parameter, remark, charges, no_sample, discount_type, discount, tax_value, tax_amount, total_cost, name_of_commodity, quotation_type, st_test_specification as test_specification, st_test_group as test_group, total_cost, type_of_service');
        $this->db->where_in('sample_test_id', $param_id);
        $this->db->where('is_deleted !=', '2');
        $query = $this->db->get('sample_test');
        return $query->result_array();
    }

    // Added by Saurabh on 20-07-2021 to get sample type
    public function get_sample_types($category_id, $key)
    {
        $this->db->select('sample_type_id as id, sample_type_name as name, sample_type_name as full_name');
        $this->db->where('category_id', $category_id);
        ($key != NULL) ? $this->db->like('sample_type_name', $key) : '';
        $query = $this->db->get('sample_type');
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return [];
    }

    // Added by Saurabh on 20-07-2021 to get sample category
    public function get_sample_category($category_id, $sample_type_id, $key)
    {
        $this->db->select('sample_category_id as id, sample_category_name as name, sample_category_name as full_name');
        $this->db->where('category_id', $category_id);
        $this->db->where('sample_type_id', $sample_type_id);
        ($key != NULL) ? $this->db->like('sample_category_name', $key) : '';
        $query = $this->db->get('sample_category');
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return [];
    }


    // Added by Saurabh on 26-07-2021 to get sample input fields
    public function get_sample_inputs($category_id)
    {
        $this->db->select('sample_fields_id, sample_fields_name');
        $this->db->where('product_category_id', $category_id);
        $query = $this->db->get('sample_fields');
        // echo $this->db->last_query();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return [];
    }
    // Added by Saurabh on 26-07-2021 to get sample input fields

    // Added by Saurabh on 28-07-2021 to get tests from quotation
    public function get_testing_parameter($quotation_id, $quotation_type)
    {
        if ($quotation_type == 1) {
            $this->db->select('test_parameters_id as id, test_parameters_name as name');
            $this->db->join('test_parameters', 'test_parameters_id = testing_parameter');
        } elseif ($quotation_type == 2) {
            $this->db->select('test_id as id, test_name as name');
            $this->db->join('test_group', 'test_id = test_group');
        } elseif ($quotation_type == 3) {
            $this->db->select('id, specification_name as name');
            $this->db->join('tbl_specification', 'tbl_specification.id = test_specification', 'left');
        }
        $this->db->where('quote_id', $quotation_id);
        $this->db->where('is_deleted !=', '2');
        $query = $this->db->get('quotes_fields');
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return [];
    }
    // Added by Saurabh on 28-07-2021 to get tests from quotation


    public function get_selected_category($quote_id)
    {
        $this->db->select('DISTINCT(mst_category.category_id) as category_id, category_name');
        $this->db->join('quotes_fields', 'service_category = mst_category.category_id');
        $this->db->where('quote_id', $quote_id);
        $this->db->where('is_deleted !=', '2');
        $query = $this->db->get('mst_category');
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return [];
    }

    public function get_sample_selected_category($category_id)
    {
        $this->db->select('category_id, category_name');
        $this->db->where('category_id', $category_id);
        $query = $this->db->get('mst_category');
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return [];
    }

    public function get_selected_product($category_id, $key, $quotation_id)
    {
        $this->db->select('DISTINCT(product_id) as id, product_name as name, product_name as full_name');
        $this->db->join('mst_products', 'name_of_commodity = product_id');
        $this->db->where('quote_id', $quotation_id);
        $this->db->where('service_category', $category_id);
        $this->db->where('is_deleted !=', '2');
        ($key != NULL) ? $this->db->like($key) : '';
        $query = $this->db->get('quotes_fields');
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return [];
    }

    public function get_sample_selected_product($product_id)
    {
        $this->db->select('product_id as id, product_name as full_name');
        $this->db->where('product_id', $product_id);
        $query = $this->db->get('mst_products');
        if ($query->num_rows() > 0) {
            return $query->row_array();
        }
        return [];
    }

    public function remove_sample_field($field_id)
    {
        $this->db->where_in('sdv_id', $field_id);
        $query = $this->db->update('sample_dynamic_value', ['status' => 0]);
        if ($query) {
            return true;
        }
        return false;
    }

    public function get_sample_selected_fields($sample_reg_id)
    {
        $this->db->where('status', 1);
        $this->db->where('sample_reg_id', $sample_reg_id);
        $query = $this->db->get('sample_dynamic_value');
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return [];
    }

    /* updated by millan on 23-09-2021 */
    public function get_new_parameter($quote_id, $selected_param)
    {
        $this->db->select('quote_field_id, sampling_quantity, tat_date, mst_category.category_id, mst_category.category_name, mst_products.product_id, mst_products.product_name, quote_field_id, remark, charges, no_sample, discount, discount_type, tax_value, total_cost, type_of_service, tax_amount, quotation_type, test_parameters_id, test_parameters_name, test_id, test_name, tbl_specification.id as spec_id, tbl_specification.specification_name, quotation_type, test_methods.test_method_name, parameter_method_id');
        $this->db->join('tbl_specification', 'tbl_specification.id = test_specification', 'left');
        $this->db->join('test_parameters', 'testing_parameter = test_parameters_id', 'left');
        $this->db->join('test_group', 'test_id = test_group', 'left');
        $this->db->join('mst_category', 'mst_category.category_id = service_category', 'left');
        $this->db->join('mst_products', 'mst_products.product_id = name_of_commodity', 'left');
        $this->db->join('test_methods', 'test_methods.test_method_id = quotes_fields.parameter_method_id', 'left');
        $this->db->where('quote_id', $quote_id);
        $this->db->where_not_in('quote_field_id', explode(',', $selected_param));
        $this->db->where('is_deleted !=', '2');
        $this->db->group_by('quotes_fields.quote_field_id');
        $query = $this->db->get('quotes_fields');
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return [];
    }
    /* updated by millan on 23-09-2021 */

    /* updated by millan on 23-09-2021 */
    public function get_trf_parameters($id)
    {
        $this->db->select('quote_field_id, sampling_quantity, tat_date, mst_category.category_id, category_name, mst_products.product_id, mst_products.product_name, remark, charges, no_sample, discount, discount_type, tax_value, total_cost, type_of_service, tax_amount, quotation_type, test_parameters_id, test_parameters_name, test_id, test_name, tbl_specification.id as spec_id, tbl_specification.specification_name, trf_test.parameter_method_id, test_methods.test_method_name'); //added by millan on 23-09-2021
        $this->db->join('tbl_specification', 'tbl_specification.id = test_specification', 'left');
        $this->db->join('test_parameters', 'testing_parameter = test_parameters_id', 'left');
        $this->db->join('test_group', 'test_id = test_group', 'left');
        $this->db->join('mst_category', 'mst_category.category_id = trf_test.service_category', 'left');
        $this->db->join('mst_products', 'mst_products.product_id = trf_test.name_of_commodity', 'left');
        $this->db->join('test_methods', 'test_methods.test_method_id = trf_test.parameter_method_id', 'left'); //added by millan on 23-09-2021
        $this->db->where('trf_test_trf_id', $id);
        $this->db->where('is_deleted !=', '2');
        // $this->db->group_by('sample_test.sample_test_id');
        $query = $this->db->get('trf_test');
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return [];
    }
    /* updated by millan on 23-09-2021 */

    /* updated by millan on 23-09-2021 */
    public function get_sample_parameters($id)
    {
        $this->db->select('quote_field_id, sampling_quantity, tat_date, mst_category.category_id, mst_category.category_name, mst_products.product_id, mst_products.product_name, remark, charges, no_sample, discount, discount_type, tax_value, total_cost, type_of_service, tax_amount, quotation_type, test_parameters_id, test_parameters_name, test_id, test_name, tbl_specification.id as spec_id, tbl_specification.specification_name, sample_test_id, sample_test.parameter_method_id, test_methods.test_method_name'); //added by millan on 23-09-2021
        $this->db->join('tbl_specification', 'tbl_specification.id = st_test_specification', 'left');
        $this->db->join('test_parameters', 'st_testing_parameter = test_parameters_id', 'left');
        $this->db->join('test_group', 'test_id = st_test_group', 'left');
        $this->db->join('mst_category', 'mst_category.category_id = service_category', 'left');
        $this->db->join('mst_products', 'mst_products.product_id = name_of_commodity', 'left');
        $this->db->join('test_methods', 'test_methods.test_method_id = sample_test.parameter_method_id', 'left'); //added by millan on 23-09-2021
        $this->db->where('sample_test_sample_reg_id', $id);
        $this->db->where('is_deleted !=', '2');
        // $this->db->group_by('sample_test.sample_test_id');
        $query = $this->db->get('sample_test');
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return [];
    }
    /* updated by millan on 23-09-2021 */

    public function delete_parameters($ids)
    {
        $this->db->where_in('sample_test_id', $ids);
        $query = $this->db->update('sample_test', ['is_deleted' => '2']);
        if ($query) {
            return true;
        }
        return false;
    }

    /* Added by Saurabh on 30-08-2021 to get trf data */
    public function get_sample_selected_test($sample_reg_id)
    {
        $this->db->select('tat_date, sampling_quantity, service_category, st_testing_parameter as testing_parameter, remark, charges, no_sample, discount_type, discount, tax_value, tax_amount, total_cost, name_of_commodity, quotation_type, st_test_specification as test_specification, st_test_group as test_group, total_cost, type_of_service');
        $query = $this->db->get_where('sample_test', ['sample_test_sample_reg_id' => $sample_reg_id, 'is_deleted !=' => '2']);
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return [];
    }
    /* Added by Saurabh on 30-08-2021 to get trf data */

    /* Added by Saurabh on 30-08-2021 */
    public function get_sample_selected_parameters($sample_reg_id)
    {
        $this->db->select('quote_field_id, sample_test_id, quotation_type, test_parameters_id, test_parameters_name, test_id, test_name, tbl_specification.id as spec_id, tbl_specification.specification_name');
        $this->db->join('tbl_specification', 'tbl_specification.id = st_test_specification', 'left');
        $this->db->join('test_parameters', 'st_testing_parameter = test_parameters_id', 'left');
        $this->db->join('test_group', 'test_id = st_test_group', 'left');
        $this->db->where('sample_test_sample_reg_id', $sample_reg_id);
        $this->db->where('is_deleted !=', '2');
        // $this->db->group_by('sample_test.sample_test_sample_reg_id');
        $query = $this->db->get('sample_test');
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return [];
    }
    /* Added by Saurabh on 30-08-2021 */


    // CHANDAN ---- 31-08-2021------------
    public function generate_performa_invoice($sample_reg_id)
    {
        $sample_data_query = $this->db->select('sr.*, quo.currency_id, trf.quote_id, trf.division_id, quo.branch_id')
            ->join('trf_registration trf', 'trf.trf_id = sr.trf_reg_id', 'inner')
            ->join('quotes quo', 'quo.quote_id = trf.quote_id', 'inner')
            ->where('sr.sample_reg_id', $sample_reg_id)
            ->get('sample_registration sr');
        $sample_data = ($sample_data_query->num_rows() > 0) ? $sample_data_query->row_array() : NULL;
        // echo "<pre>"; print_r($sample_data); die;
        if (!empty($sample_data)) {
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
            $customer_id = $sample_data['party_name'];
            $this->db->select('c.customer_id,c.city,c.po_box,location.location_name as location,provinces.province_name as province,country.country_name as country');
            $this->db->from('food_cust_customers c');
            $this->db->join('food_mst_locations location', 'location.location_id = c.cust_customers_location_id', 'left');
            $this->db->join('food_mst_provinces provinces', 'provinces.province_id = c.cust_customers_province_id', 'left');
            $this->db->join('food_mst_country country', 'country.country_id = c.cust_customers_country_id', 'left');
            $this->db->where('c.customer_id', $customer_id);
            $add = $this->db->get();
            if ($add->num_rows() > 0) {
                $row = $add->row();
                $address = $row->location . "," . $row->po_box . "  P O  ," . $row->city . "," . $row->province . "," . $row->country;
            } else {
                $address = '';
            }
            $this->db->select('SUM(test_price) as test_price');
            $this->db->from('test_group');
            $this->db->join('sample_test st', 'st.sample_test_id=test_group.test_id', 'inner');
            $this->db->where('sample_test_sample_reg_id', $sample_reg_id);
            $test_value = $this->db->get();
            $test_value = ($test_value->num_rows() > 0) ? $test_value->result() : 0;

            $proforma['type'] = 1;
            $proforma['quote_id'] = $sample_data['quote_id'];
            $proforma['currency_id'] = $sample_data['currency_id'];
            $proforma['signoff_authority'] = "";
            $proforma['proforma_invoice_date'] = date("Y-m-d h:i:s");
            $proforma['invoice_proforma_customer_id'] = $customer_id;
            $proforma['invoice_proforma_invoice_status_id'] = 1;
            $proforma['proforma_invoice_sample_reg_id'] = $sample_reg_id;
            $proforma['proforma_invoice_job_type'] = 'Quote';
            $proforma['proforma_client_address'] = $address;
            $proforma['created_by'] = $this->admin_id();
            $proforma['total_amount'] = ($test_value[0]->test_price > 0) ? $test_value[0]->test_price : 0;

            if (empty($p_id)) {

                $this->db->insert('invoice_proforma', $proforma);
                $p_id = $this->db->insert_id();

                $this->db->select('division_code');
                $this->db->from('food_mst_divisions');
                $this->db->where('division_id', $sample_data['division_id']);
                $division_code = $this->db->get();
                $division_code = ($division_code->num_rows() > 0) ? $division_code->result() : NULL;
                $division_code = (isset($division_code[0]->division_code)) ? $division_code[0]->division_code : '';

                $rand = str_pad($p_id, 5, "0", STR_PAD_LEFT);

                $this->db->select('branch_code');
                $this->db->from('food_mst_branches');
                $this->db->where('branch_id', $sample_data['branch_id']);
                $branch_code = $this->db->get();
                $branch_code = ($branch_code->num_rows() > 0) ? $branch_code->result() : NULL;
                $branch_code = (isset($branch_code[0]->branch_code)) ? $branch_code[0]->branch_code : '';

                $pi_number['proforma_invoice_number'] = 'PI' . $division_code . $branch_code . date('Y') . '-' . $rand;
            } else {
                $this->db->where('proforma_invoice_id', $p_id);
                $this->db->update('invoice_proforma', $proforma);

                $this->db->select('proforma_invoice_number');
                $this->db->from('invoice_proforma');
                $this->db->where('proforma_invoice_sample_reg_id', $sample_reg_id);
                $proforma_invoice_number = $this->db->get();

                $proforma_invoice_number = ($proforma_invoice_number->num_rows() > 0) ? $proforma_invoice_number->result() : NULL;
                $proforma_invoice_number = (isset($proforma_invoice_number[0]->proforma_invoice_number)) ? $proforma_invoice_number[0]->proforma_invoice_number : '';

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
            $this->db->where('proforma_invoice_id', $p_id);
            $status1 = $this->db->update('invoice_proforma', $pi_number);
            $status1 = $this->db->update('sample_registration', ['proforma_generated' => 1], ['sample_reg_id' => $sample_reg_id]);
            $this->db->trans_complete();
            return ($status1) ? true : false;
        }
    }

    public function get_sample_selected_test_ids($sample_reg_id)
    {
        $this->db->select('quote_field_id as tsf_fields_id');
        $query = $this->db->get_where('sample_test', ['sample_test_sample_reg_id' => $sample_reg_id, 'is_deleted !=' => '2']);
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return [];
    }

    public function get_quote_field_ids($param_id)
    {
        $this->db->select('quote_field_id as tsf_fields_id');
        $this->db->where_in('sample_test_id', $param_id);
        $this->db->where('is_deleted !=', '2');
        $query = $this->db->get('sample_test');
        return $query->result_array();
    }
    // Added by Saurabh on 19-11-2021 to get quotation type
    public function get_quotes($samp_id)
    {
        $query = $this->db->select('GROUP_CONCAT(DISTINCT st.quotation_type SEPARATOR "$#") quotation_type')
            ->from('sample_test st')
            ->group_by('st.sample_test_sample_reg_id')
            ->having('st.sample_test_sample_reg_id', $samp_id)
            ->where('st.is_deleted !=', '2')
            ->get();
        if ($query->num_rows() > 0) {
            return $query->row_array();
        } else {
            return false;
        }
    }

    // Added by Saurabh on 19-11-2021 to get test name
    public function get_test_name($sample_reg_id, $quotation_type)
    {
        $data = array();
        $quote_type = explode("$#", $quotation_type);
        foreach ($quote_type as $value) {
            if ($value === '1') {
                $qu = $this->db->select('tpd.tp_details_id, tp.test_parameters_name tpgs_name, st.sample_test_id, lab_name, tp.test_parameters_id, st.name_of_commodity, st.service_category, CONCAT(tpd.unique_number, "-", tms.test_method_name) as un_mhd_name, tpn.proforma_no, tpd.method_id') // added by millan on 21-12-2021
                    ->from('sample_test st')
                    ->join('sample_registration sr', 'sr.sample_reg_id = st.sample_test_sample_reg_id', 'left') // Added by millan on 22-12-2021
                    ->join('test_parameters tp', 'tp.test_parameters_id = st.st_testing_parameter', 'left') // Added by millan on 14-02-2022
                    ->join('test_parameter_details tpd', 'tpd.tp_details_id = st.sample_test_tp_unique_id', 'left')
                    ->join('food_mst_labs', 'st.sample_test_assigned_lab_id = lab_id', 'left') // Added by saurabh on 25-11-2021
                    ->join('template_proforma_no tpn', 'tpn.tpn_id = sr.samp_proforma_id', 'left') // Added by millan on 22-12-2021
                    ->join('test_methods tms', 'tms.test_method_id = tpd.method_id', 'left') // Added by millan on 22-12-2021
                    ->where('st.sample_test_sample_reg_id', $sample_reg_id)
                    ->group_by('tp.test_parameters_id')
                    ->where('st.is_deleted !=', '2')
                    ->where(['tpd.is_deleted' => 0, 'tpd.status' => 1])
                    ->get();
                // echo $this->db->last_query(); die;
                if ($qu->num_rows() > 0) {
                    $data['tst_pms_data'] = $qu->result_array();
                } else {
                    $data['tst_pms_data'] = array();
                }
            }

            if ($value === '2') {
                $q3 = $this->db->select('tp.test_parameters_name tpgs_name, st.sample_test_id, lab_name, st.name_of_commodity, st.service_category, tpd.tp_details_id test_parameters_id, CONCAT(tpd.unique_number, "-", tms.test_method_name) as un_mhd_name, tpn.proforma_no') // added by millan on 21-12-2021
                    ->from('sample_test st')
                    ->join('food_mst_labs', 'st.sample_test_assigned_lab_id = lab_id', 'left') // Added by saurabh on 25-11-2021
                    ->join('test_group_details tgd', 'tgd.test_group_id = st.st_test_group', 'left') // Added by millan on 22-12-2021
                    ->join('sample_registration sr', 'sr.sample_reg_id = st.sample_test_sample_reg_id', 'left') // Added by millan on 22-12-2021
                    ->join('test_parameter_details tpd', 'tpd.test_parameters_id = tgd.test_parameter_id', 'left') // Added by millan on 22-12-2021
                    ->join('test_parameters tp', 'tp.test_parameters_id = tpd.test_parameters_id', 'inner') // Added by millan on 22-12-2021
                    ->join('template_proforma_no tpn', 'tpn.tpn_id = sr.samp_proforma_id', 'left') // Added by millan on 22-12-2021
                    ->join('test_methods tms', 'tms.test_method_id = tpd.method_id', 'left') // Added by millan on 22-12-2021
                    ->where('st.sample_test_sample_reg_id', $sample_reg_id)
                    ->group_by('tp.test_parameters_id')
                    ->where('st.is_deleted !=', '2')
                    ->where(['tpd.is_deleted' => 0, 'tpd.status' => 1])
                    ->get();
                if ($q3->num_rows() > 0) {
                    $data['tes_grp_data'] = $q3->result_array();
                } else {
                    $data['tes_grp_data'] = array();
                }
            }

            if ($value === '3') {
                $q4 = $this->db->select('tp.test_parameters_name tpgs_name, st.sample_test_id, lab_name, st.name_of_commodity, st.service_category, tp.tp_details_id test_parameters_id, CONCAT(tpd.unique_number, "-", tms.test_method_name) as un_mhd_name, tpn.proforma_no') // added by millan on 21-12-2021')
                    ->from('sample_test st')
                    ->join('food_mst_labs', 'st.sample_test_assigned_lab_id = lab_id', 'left') // Added by saurabh on 25-11-2021
                    ->join('tbl_specification_details tsd', 'tsd.tbl_specification_id = st.st_test_specification', 'left')
                    ->join('test_parameter_details tpd', 'tpd.test_parameters_id = tsd.test_parameter_id', 'left') // Added by millan on 22-12-2021
                    ->join('test_parameters tp', 'tp.test_parameters_id = tpd.test_parameters_id', 'inner') // Added by millan on 22-12-2021
                    ->join('sample_registration sr', 'sr.sample_reg_id = st.sample_test_sample_reg_id', 'left') // Added by millan on 22-12-2021
                    ->join('template_proforma_no tpn', 'tpn.tpn_id = sr.samp_proforma_id', 'left') // Added by millan on 22-12-2021
                    ->join('test_methods tms', 'tms.test_method_id = tpd.method_id', 'left') // Added by millan on 22-12-2021
                    ->where('st.sample_test_sample_reg_id', $sample_reg_id)
                    ->where(['tpd.is_deleted' => 0, 'tpd.status' => 1])
                    ->group_by('tp.test_parameters_id')
                    ->where('st.is_deleted !=', '2')
                    ->get();
                if ($q4->num_rows() > 0) {
                    $data['tst_spc_data'] = $q4->result_array();
                } else {
                    $data['tst_spc_data'] = array();
                }
            }
        }
        return $data;
    }

    // Added by Saurabh on 19-11-2021 to get Labs
    public function get_labs_by_key($key)
    {
        $this->db->select('lab_id as id, lab_name as name, lab_name as full_name');
        ($key != 'NULL') ? $this->db->like('lab_name', $key) : '';
        $this->db->limit(30);
        $query = $this->db->get('food_mst_labs');
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return [];
    }

    // Added by saurabh on 25-11-2021 to get exporter list by category
    public function get_exporter_list($category_id, $key)
    {
        $this->db->select('exporter_id as id, exporter_name as name, exporter_name as full_name');
        $this->db->where('category_id', $category_id);
        $this->db->where('tbl_exporters.status', '1');
        ($key != 'NULL') ? $this->db->like('exporter_name', $key) : '';
        $this->db->limit(30);
        $query = $this->db->get('tbl_exporters');
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return [];
    }

    // Added by saurabh on 25-11-2021 to get exporter name
    public function get_exporter_name($exporter_id)
    {
        $this->db->select('exporter_id, exporter_name');
        $this->db->where('exporter_id', $exporter_id);
        $query = $this->db->get('tbl_exporters');
        if ($query->num_rows() > 0) {
            return $query->row_array();
        }
        return [];
    }

    // Added by saurabh on 25-11-2021 to get quotation details
    public function get_quote_details($quote_id)
    {
        $this->db->select('service_days, service_type');
        $this->db->where('quote_id', $quote_id);
        $query = $this->db->get('quotes');
        if ($query->num_rows() > 0) {
            return $query->row_array();
        }
        return [];
    }

    // Added by saurabh on 25-11-2021 to get data for email
    public function get_data_for_email($sample_reg_id)
    {
        $this->db->select('sr.recieved_date, sr.gc_number, fcc.customer_name, sr.party_address, sr.letter_ref, sr.sample_desc, sr.id_mark, sr.estimated_date');
        $this->db->join('food_cust_customers fcc', 'party_name = fcc.customer_id');
        $this->db->where('sample_reg_id', $sample_reg_id);
        $query = $this->db->get('sample_registration sr');
        if ($query->num_rows() > 0) {
            return $query->row_array();
        }
        return [];
    }

    public function export_sample_status($search)
    {
        $this->db->select('sr.sample_reg_id, sr.created_on, sr.recieved_date, sr.gc_number, fcc.customer_name, sr.party_address, sr.letter_ref, sr.letter_date, msc.category_name as category_name, sr.sample_desc, sr.specification, sr.id_mark, sr.sample_seal, sr.container_type, sr.temp_of_sample, sr.sample_quantity, sr.remnant_sample, sr.remark, sr.sample_condition, fmc.country_name, sr.estimated_date, sr.sample_entry_by, sr.sample_reciept_time, te.exporter_name');
        $this->db->join('trf_registration trf', 'trf.trf_id = sr.trf_reg_id', 'inner');     // Added by CHANDAN --19-04-2022
        $this->db->join('quotes qt', 'qt.quote_id = trf.quote_id', 'inner');                // Added by CHANDAN --19-04-2022
        $this->db->join('food_cust_customers fcc', 'fcc.customer_id = qt.customer_id');
        $this->db->join('mst_category msc', 'msc.category_id = sr.sample_category');
        $this->db->join('food_mst_country fmc', 'fmc.country_id = sr.exporting_country', 'left');
        $this->db->join('tbl_exporters te', 'sr.exporter_id = te.exporter_id', 'left');
        ($search['uidnr_admin'] != 'NULL') ? $this->db->like('sr.created_by', $search['uidnr_admin']) : '';
        ($search['gc_number'] != 'NULL') ? $this->db->like('sr.gc_number', $search['gc_number']) : '';
        ($search['trf_ref_no'] != 'NULL') ? $this->db->like('trf.trf_ref_no', $search['trf_ref_no']) : '';
        ($search['customer_id'] != 'NULL') ? $this->db->where('sr.party_name', $search['customer_id']) : '';
        ($search['product_id'] != 'NULL') ? $this->db->like('sr.product_id', $search['product_id']) : '';
        ($search['status'] != 'NULL') ? $this->db->like('sr.status', $search['status']) : '';
        ($search['quote_ref_no'] != 'NULL') ? $this->db->like('qt.quote_ref_no', $search['quote_ref_no']) : '';
        ($search['ulr_number'] != 'NULL') ? $this->db->like('sr.ulr_number', $search['ulr_number']) : '';
        if (!empty($this->session->userdata('branch_id'))) {
            $this->db->where('qt.branch_id', $this->session->userdata('branch_id'));        // Added by CHANDAN --19-04-2022
        }
        if ($search['start_dt'] != 'NULL' && $search['end_dt'] != 'NULL') {
            $this->db->where(['sr.created_on >=' => $search['start_dt'], 'sr.created_on <=' => $search['end_dt']]);
        }
        $query = $this->db->get('sample_registration sr');
        return ($query->num_rows() > 0) ? $query->result_array() : [];
    }

    public function sample_selected_test_and_dept($sample_reg_id)
    {
        $this->db->select('GROUP_CONCAT(DISTINCT(CASE WHEN st.quotation_type = 1 THEN CONCAT(tp.test_parameters_name," - ",tpmd.dept_name) WHEN quotation_type = 2 THEN CONCAT(tgpn.test_parameters_name," - ",tgmd.dept_name) WHEN quotation_type = 3 THEN CONCAT(tstp.test_parameters_name," - ",tsmd.dept_name) END)) as test_parameter');
        // For the test parameter
        $this->db->join('test_parameters tp', 'st.st_testing_parameter = tp.test_parameters_id', 'left');
        $this->db->join('test_parameter_details tpd', 'tpd.test_parameters_id = tp.test_parameters_id and tpd.is_deleted = 0 and tpd.status = 1', 'left');
        $this->db->join('mst_departments tpmd', 'tpd.department_id = tpmd.dept_id', 'left');

        // For the test group
        $this->db->join('test_group_details tgd', 'tgd.test_group_id = st.st_test_group', 'left');
        $this->db->join('test_parameter_details tgpd', 'tgpd.test_parameters_id = tgd.test_parameter_id and tgpd.is_deleted = 0 and tgpd.status = 1', 'left');
        $this->db->join('test_parameters tgpn', 'tgpn.test_parameters_id = tgpd.test_parameters_id', 'left');
        $this->db->join('mst_departments tgmd', 'tgpd.department_id = tgmd.dept_id', 'left');

        // For the test specification
        $this->db->join('tbl_specification ts', 'st_test_specification = ts.id', 'left');
        $this->db->join('tbl_specification_details tsd', 'tsd.tbl_specification_id = ts.id', 'left');
        $this->db->join('test_parameter_details tstpd', 'tsd.test_parameter_id = tstpd.test_parameters_id and tstpd.is_deleted = 0 and tstpd.status = 1', 'left');
        $this->db->join('test_parameters tstp', 'tstpd.test_parameters_id = tstp.test_parameters_id', 'left');
        $this->db->join('mst_departments tsmd', 'tsmd.dept_id = tstpd.department_id', 'left');

        $this->db->distinct('tp.test_parameters_id');
        $this->db->distinct('tgpn.test_parameters_id');
        $this->db->distinct('tstp.test_parameters_id');
        $this->db->where('sample_test_sample_reg_id', $sample_reg_id);
        $query = $this->db->get('sample_test st');
        if ($query->num_rows() > 0) {
            return $query->row_array();
        }
        return [];
    }

    public function test_required_for_sample($sample_reg_id)
    {
        $this->db->select('GROUP_CONCAT(DISTINCT(CASE WHEN st.quotation_type = 1 THEN tp.test_parameters_name WHEN quotation_type = 2 THEN tgpn.test_parameters_name WHEN quotation_type = 3 THEN tstp.test_parameters_name END)) as test_parameter');
        // For the test parameter
        $this->db->join('test_parameters tp', 'st.st_testing_parameter = tp.test_parameters_id', 'left');
        $this->db->join('test_parameter_details tpd', 'tpd.test_parameters_id = tp.test_parameters_id and tpd.is_deleted = 0 and tpd.status = 1', 'left');

        // For the test group
        $this->db->join('test_group_details tgd', 'tgd.test_group_id = st.st_test_group', 'left');
        $this->db->join('test_parameter_details tgpd', 'tgpd.test_parameters_id = tgd.test_parameter_id and tgpd.is_deleted = 0 and tgpd.status = 1', 'left');
        $this->db->join('test_parameters tgpn', 'tgpn.test_parameters_id = tgpd.test_parameters_id', 'left');

        // For the test specification
        $this->db->join('tbl_specification ts', 'st_test_specification = ts.id', 'left');
        $this->db->join('tbl_specification_details tsd', 'tsd.tbl_specification_id = ts.id', 'left');
        $this->db->join('test_parameter_details tstpd', 'tsd.test_parameter_id = tstpd.test_parameters_id and tstpd.is_deleted = 0 and tstpd.status = 1', 'left');
        $this->db->join('test_parameters tstp', 'tstpd.test_parameters_id = tstp.test_parameters_id', 'left');

        $this->db->distinct('tp.test_parameters_id');
        $this->db->distinct('tgpn.test_parameters_id');
        $this->db->distinct('tstp.test_parameters_id');
        $this->db->where('sample_test_sample_reg_id', $sample_reg_id);
        $query = $this->db->get('sample_test st');
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            return $result['test_parameter'];
        }
        return [];
    }

    public function get_customer_email($sample_reg_id)
    {
        $this->db->select('commu_email, email');
        $this->db->join('food_cust_customers', 'customer_id = party_name');
        $this->db->where('sample_reg_id', $sample_reg_id);
        $query = $this->db->get('sample_registration');
        if ($query->num_rows() > 0) {
            return $query->row_array();
        }
        return [];
    }

    // added by millan on 20-12-2021
    public function get_unique_number($param_id, $pro_id, $cat_id, $key)
    {
        $this->db->select('CONCAT(tpd.unique_number, "-", tms.test_method_name) as name,CONCAT(tpd.unique_number, "-", tms.test_method_name) as full_name, tpd.tp_details_id as id');
        $this->db->group_start();
        ($key != 'NULL') ? $this->db->like('unique_number', $key) : '';
        ($key != 'NULL') ? $this->db->or_like('test_method_name', $key) : '';
        $this->db->group_end();
        $this->db->join('test_methods tms', 'tms.test_method_id = tpd.method_id', 'inner');
        $this->db->where('tpd.test_parameters_id', $param_id);
        $this->db->where('tpd.product_id', $pro_id);
        $this->db->where('tpd.category_id', $cat_id);
        $this->db->where(['tpd.is_deleted' => 0, 'tpd.status' => 1]);
        $query = $this->db->get('test_parameter_details tpd');
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }
    //ends

    // added by millan on 22-12-2021
    public function get_proforma_no($key)
    {
        $this->db->select('tpn.tpn_id as id, tpn.proforma_no as name, tpn.proforma_no as full_name');
        ($key != 'NULL') ? $this->db->like('proforma_no', $key) : '';
        $query = $this->db->get('template_proforma_no tpn');
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }
    //ends

    // added by millan on 10-02-2022
    public function get_dyn_fill_vals($proforma_id, $category_id, $product_id, $sample_reg_id)
    {
        $query = $this->db->select('profroma_dynamic_values.*')
            ->where('proforma_id', $proforma_id)
            ->where('category_id', $category_id)
            ->where('product_id', $product_id)
            ->where('sample_reg_id', $sample_reg_id)
            ->get('profroma_dynamic_values');
        if ($query->num_rows() > 0) {
            return $query->row();
        } else {
            return false;
        }
    }

    // added by millan on 18-02-2022
    public function get_selected_test_method($sample_reg_id)
    {
        $query = $this->db->select('tms.test_method_name, tms.test_method_id')
            ->join('test_methods tms', 'tms.test_method_id = st.parameter_method_id', 'left')
            ->where('st.sample_test_sample_reg_id', $sample_reg_id)
            ->get('sample_test st');
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }
    // ends

    // Added by CHANDAN --15-06-2022
    public function fetch_distinct_department()
    {
        $this->db->distinct();
        $this->db->select('dp.dept_id, dp.dept_name');
        $this->db->join('test_parameter_details tpd', 'dp.dept_id = tpd.department_id AND tpd.status = 1 AND tpd.is_deleted = 0', 'inner');
        $this->db->where(['dp.status' => '1', 'tpd.branch_id' => $this->session->userdata('branch_id')]);
        $this->db->order_by('dp.dept_name');
        $query = $this->db->get('mst_departments dp');
        return ($query->num_rows() > 0) ? $query->result() : false;
    }

    public function fetch_department_wise_counter($dept_id, $sample_reg_id)
    {
        $query = $this->db->distinct()
            ->select('quotation_type')
            ->where(['sample_test_sample_reg_id' => $sample_reg_id, 'is_deleted !=' => '2'])
            ->order_by('quotation_type', 'asc')
            ->get('sample_test');
        $quotation_type = ($query->num_rows() > 0) ? $query->row()->quotation_type : NULL;

        if (!empty($quotation_type) && !empty($dept_id)) {

            if ($quotation_type == 1) {

                $this->db->select('COUNT(tp.test_parameters_name) AS total_dept');
                $this->db->join('test_parameter_details tpd', 'st.st_testing_parameter = tpd.test_parameters_id and st.parameter_method_id = tpd.method_id and tpd.is_deleted = 0 and tpd.status = 1', 'inner');
                $this->db->join('test_parameters tp', 'tpd.test_parameters_id = tp.test_parameters_id and tp.status = 1', 'inner');
                $this->db->where(['st.sample_test_sample_reg_id' => $sample_reg_id, 'st.is_deleted !=' => '2']);
                $this->db->where('tpd.department_id', $dept_id);
                $this->db->group_by('tp.test_parameters_id');
                $query1 = $this->db->get('sample_test st');
                return ($query1->num_rows() > 0) ? $query1->row()->total_dept : 0;
            }
            if ($quotation_type == 2) {

                $this->db->select('COUNT(tp.test_parameters_name) AS total_dept');
                $this->db->join('test_group_details tgd', 'tgd.test_group_id = st.st_test_group', 'inner');
                $this->db->join('test_group tg', 'tg.test_id = tgd.test_group_id and and st.service_category = tg.tg_category_id and st.name_of_commodity = tg.tg_product_id', 'inner');
                $this->db->join('test_parameters tp', 'tgd.test_parameter_id = tp.test_parameters_id and tp.status = 1', 'inner');
                $this->db->join('test_parameter_details tpd', 'tpd.test_parameters_id = tp.test_parameters_id and tpd.is_deleted = 0 and tpd.status = 1', 'inner');
                $this->db->where(['st.sample_test_sample_reg_id' => $sample_reg_id, 'st.is_deleted !=' => '2', 'tg.status' => 1, 'tgd.is_deleted' => 1]);
                $this->db->where('tpd.department_id', $dept_id);
                $this->db->group_by('tp.test_parameters_id');
                $query1 = $this->db->get('sample_test st');
                return ($query1->num_rows() > 0) ? $query1->row()->total_dept : 0;
            }
            if ($quotation_type == 3) {

                $this->db->select('COUNT(tp.test_parameters_name) AS total_dept');
                $this->db->join('tbl_specification spc', 'st.st_test_specification = spc.id and spc.status = 1 and st.service_category = spc.ts_category_id and st.name_of_commodity = spc.ts_product_id', 'inner');
                $this->db->join('tbl_specification_details tsd', 'tsd.tbl_specification_id = spc.id', 'inner');
                $this->db->join('test_parameters tp', 'tsd.test_parameter_id = tp.test_parameters_id and tp.status = 1', 'inner');
                $this->db->join('test_parameter_details tpd', 'tpd.test_parameters_id = tp.test_parameters_id and tpd.is_deleted = 0 and tpd.status = 1', 'inner');
                $this->db->where(['st.sample_test_sample_reg_id' => $sample_reg_id, 'st.is_deleted !=' => '2', 'tsd.is_deleted' => 0]);
                $this->db->where('tpd.department_id', $dept_id);
                $this->db->group_by('tp.test_parameters_id');
                $query1 = $this->db->get('sample_test st');
                return ($query1->num_rows() > 0) ? $query1->row()->total_dept : 0;
            }
        } else {
            return 0;
        }
    }

    // Added by CHANDAN --19-04-2022
    public function fetch_customer_group_flag($key)
    {
        $this->db->select('branch_id as id, branch_name as name, branch_name as full_name');
        ($key != null) ? $this->db->like('branch_name', $key) : '';
        $this->db->where('status', '1');
        $this->db->where_not_in('branch_id', $this->session->userdata('branch_id'));
        $this->db->order_by('branch_name', 'asc');
        $this->db->limit(30);
        $query = $this->db->get('food_mst_branches');
        return ($query->num_rows() > 0) ? $query->result_array() : [];
    }

    public function fetch_client_data($key)
    {
        $this->db->select('customer_id as id, customer_name as name, customer_name as full_name');
        ($key != null) ? $this->db->like('customer_name', $key) : '';
        $this->db->limit(30);
        $query = $this->db->get('food_cust_customers');
        return ($query->num_rows() > 0) ? $query->result_array() : [];
    }

    public function fetch_product_data($key)
    {
        $this->db->select('product_id as id, product_name as name, product_name as full_name');
        $this->db->where('status', 1);
        ($key != null) ? $this->db->like('product_name', $key) : '';
        $this->db->limit(30);
        $query = $this->db->get('mst_products');
        return ($query->num_rows() > 0) ? $query->result_array() : [];
    }

    public function fetch_admin_data($key)
    {
        $this->db->select('uidnr_admin as id, CONCAT(admin_fname," ",admin_lname) as name, CONCAT(admin_fname," ",admin_lname) as full_name');
        ($key != null) ? $this->db->like('CONCAT(admin_fname," ",admin_lname)', $key) : '';
        $this->db->limit(30);
        $query = $this->db->get('admin_profile');
        return ($query->num_rows() > 0) ? $query->result_array() : [];
    }
    // End
}
