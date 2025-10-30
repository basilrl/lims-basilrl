<?php
defined('BASEPATH') or exit('No Direct Access Allowed');

class Worksheet_Model extends MY_Model
{
    public function get_worksheet_details($id)
    {
        $this->db->select('sr.gc_number, sr.sample_desc, con.country_name as destination_country, sr.sample_seal, sr.container_type, sr.sample_quantity, sr.sample_weight, sr.sampling_status, sr.sample_entry_by, sr.remark, sr.specification, sr.temp_of_sample, sr.sample_condition, DATE_FORMAT(sr.created_on,"%d/%m/%Y") as created_on, DATE_FORMAT(sr.recieved_date,"%d/%m/%Y") as recieved_date, DATE_FORMAT(sr.recieved_date,"%h:%i %p") as recieved_time, DATE_FORMAT(sr.estimated_date,"%d/%m/%Y") as estimated_date, sr.specification, cat.category_name, prod.product_name, gen.report_num');
        $this->db->join('mst_category cat', 'sr.sample_category = cat.category_id', 'left');
        $this->db->join('mst_products prod', 'sr.product_id = prod.product_id', 'left');
        $this->db->join('food_mst_country con', 'con.country_id = sr.destination_country', 'left');
        $this->db->join('generated_reports gen', 'sr.sample_reg_id = gen.sample_reg_id', 'left');
        $this->db->where('sr.sample_reg_id', $id);
        $query = $this->db->get('sample_registration sr');
        return ($query->num_rows() > 0) ? $query->row_array() : [];
    }

    public function get_distinct_quotation_type($id)
    {
        $this->db->distinct();
        $this->db->select('quotation_type');
        $this->db->where('sample_test_sample_reg_id', $id);
        $this->db->order_by('quotation_type', 'asc');
        $this->db->where('is_deleted !=', '2');
        $query = $this->db->get('sample_test');
        return ($query->num_rows() > 0) ? $query->result_array() : [];
    }

    // Modify by CHANDAN ---- 10.02.2022
    public function get_worksheet_test_details($quotation_type, $sample_id, $dept_id)
    {
        if ($quotation_type == 1) {

            $this->db->select('tpd.tp_details_id, tp.test_parameters_name AS parameter_name, un.unit as unit_name, measure.uncertainty_name, tm.test_method_name');
            $this->db->join('test_parameter_details tpd', 'st.st_testing_parameter = tpd.test_parameters_id and st.parameter_method_id = tpd.method_id and tpd.is_deleted = 0 and tpd.status = 1', 'inner');
            $this->db->join('test_parameters tp', 'tpd.test_parameters_id = tp.test_parameters_id and tp.status = 1', 'inner');
            $this->db->join('units un', 'tpd.unit_id = un.unit_id', 'left');
            $this->db->join('test_methods tm', 'tpd.method_id = tm.test_method_id and tm.status = 1', 'left');
            $this->db->join('tbl_measure_uncertainty measure', 'tpd.uncertainty_id = measure.id and measure.status = 1', 'left');
            $this->db->where(['st.sample_test_sample_reg_id' => $sample_id, 'st.is_deleted !=' => '2']);
            if (!empty($dept_id)) {
                $this->db->where_in('tpd.department_id', explode(',', $dept_id));
            }
            $this->db->group_by('tp.test_parameters_id');
            $query = $this->db->get('sample_test st');
            return ($query->num_rows() > 0) ? $query->result_array() : [];
        }
        if ($quotation_type == 2) {

            $this->db->select('tgd.tgd_id, tp.test_parameters_name AS parameter_name, un.unit as unit_name, measure.uncertainty_name, tm.test_method_name, tg.test_name as group_spec_name');
            $this->db->join('test_group_details tgd', 'tgd.test_group_id = st.st_test_group', 'inner');
            $this->db->join('test_group tg', 'tg.test_id = tgd.test_group_id and and st.service_category = tg.tg_category_id and st.name_of_commodity = tg.tg_product_id', 'inner');
            $this->db->join('test_parameters tp', 'tgd.test_parameter_id = tp.test_parameters_id and tp.status = 1', 'inner');
            $this->db->join('test_parameter_details tpd', 'tpd.test_parameters_id = tp.test_parameters_id and tpd.is_deleted = 0 and tpd.status = 1', 'inner');
            $this->db->join('units un', 'tpd.unit_id = un.unit_id', 'left');
            $this->db->join('test_methods tm', 'tpd.method_id = tm.test_method_id and tm.status = 1', 'left');
            $this->db->join('tbl_measure_uncertainty measure', 'tpd.uncertainty_id = measure.id and measure.status = 1', 'left');
            $this->db->where(['st.sample_test_sample_reg_id' => $sample_id, 'st.is_deleted !=' => '2', 'tg.status' => 1, 'tgd.is_deleted' => 1]);
            if (!empty($dept_id)) {
                $this->db->where_in('tpd.department_id', explode(',', $dept_id));
            }
            $this->db->group_by('tp.test_parameters_id');
            $query = $this->db->get('sample_test st');
            return ($query->num_rows() > 0) ? $query->result_array() : [];
        }
        if ($quotation_type == 3) {

            $this->db->select('tp.test_parameters_id, tp.test_parameters_name AS parameter_name, un.unit as unit_name, measure.uncertainty_name, tm.test_method_name, spc.specification_name as group_spec_name');
            $this->db->join('tbl_specification spc', 'st.st_test_specification = spc.id and spc.status = 1 and st.service_category = spc.ts_category_id and st.name_of_commodity = spc.ts_product_id', 'inner');
            $this->db->join('tbl_specification_details tsd', 'tsd.tbl_specification_id = spc.id', 'inner');
            $this->db->join('test_parameters tp', 'tsd.test_parameter_id = tp.test_parameters_id and tp.status = 1', 'inner');
            $this->db->join('test_parameter_details tpd', 'tpd.test_parameters_id = tp.test_parameters_id and tpd.is_deleted = 0 and tpd.status = 1', 'inner');
            $this->db->join('units un', 'tpd.unit_id = un.unit_id', 'left');
            $this->db->join('test_methods tm', 'tpd.method_id = tm.test_method_id and tm.status = 1', 'left');
            $this->db->join('tbl_measure_uncertainty measure', 'tpd.uncertainty_id = measure.id and measure.status = 1', 'left');
            $this->db->where(['st.sample_test_sample_reg_id' => $sample_id, 'st.is_deleted !=' => '2', 'tsd.is_deleted' => 0]);
            if (!empty($dept_id)) {
                $this->db->where_in('tpd.department_id', explode(',', $dept_id));
            }
            $this->db->group_by('tp.test_parameters_id');
            $query = $this->db->get('sample_test st');
            return ($query->num_rows() > 0) ? $query->result_array() : [];
        }
    }

    // Added by CHANDAN ---- 10-02-2022
    public function get_worksheet_all_departments($sample_id, $quotation_type)
    {
        if ($quotation_type == 1) {
            $this->db->distinct();
            $this->db->select('dept.dept_id, dept.dept_name');
            $this->db->join('test_parameter_details tpd', 'st.st_testing_parameter = tpd.test_parameters_id and st.parameter_method_id = tpd.method_id and tpd.is_deleted = 0 and tpd.status = 1', 'inner');
            $this->db->join('test_parameters tp', 'tp.test_parameters_id = tpd.test_parameters_id', 'inner');
            $this->db->join('mst_departments dept', 'dept.dept_id = tpd.department_id', 'inner');
            $this->db->where(['st.sample_test_sample_reg_id' => $sample_id, 'st.is_deleted !=' => '2', 'dept.status' => '1']);
            $this->db->group_by('tp.test_parameters_id');
            $query = $this->db->get('sample_test st');
            return ($query->num_rows() > 0) ? $query->result_array() : [];
        }
        if ($quotation_type == 2) {
            $this->db->distinct();
            $this->db->select('dept.dept_id, dept.dept_name');
            $this->db->join('test_group_details tgd', 'tgd.test_group_id = st.st_test_group', 'inner');
            $this->db->join('test_group tg', 'tgd.test_group_id = tg.test_id and st.service_category = tg.tg_category_id and st.name_of_commodity = tg.tg_product_id', 'inner');
            $this->db->join('test_parameters tp', 'tp.test_parameters_id = tgd.test_parameter_id', 'inner');
            $this->db->join('test_parameter_details tpd', 'tpd.test_parameters_id = tp.test_parameters_id and tpd.is_deleted = 0 and tpd.status = 1', 'inner');
            $this->db->join('mst_departments dept', 'dept.dept_id = tpd.department_id', 'inner');
            $this->db->where(['st.sample_test_sample_reg_id' => $sample_id, 'st.is_deleted !=' => '2', 'dept.status' => '1', 'tg.status' => 1, 'tgd.is_deleted' => 1]);
            $this->db->group_by('tp.test_parameters_id');
            $query = $this->db->get('sample_test st');
            return ($query->num_rows() > 0) ? $query->result_array() : [];
        }
        if ($quotation_type == 3) {
            $this->db->distinct();
            $this->db->select('dept.dept_id, dept.dept_name');
            $this->db->join('tbl_specification spc', 'st.st_test_specification = spc.id and spc.status = 1 and st.service_category = spc.ts_category_id and st.name_of_commodity = spc.ts_product_id', 'inner');
            $this->db->join('tbl_specification_details tsd', 'tsd.tbl_specification_id = spc.id', 'inner');
            $this->db->join('test_parameters tp', 'tsd.test_parameter_id = tp.test_parameters_id', 'inner');
            $this->db->join('test_parameter_details tpd', 'tpd.test_parameters_id = tp.test_parameters_id and tpd.is_deleted = 0 and tpd.status = 1', 'inner');
            $this->db->join('mst_departments dept', 'dept.dept_id = tpd.department_id', 'inner');
            $this->db->where(['st.sample_test_sample_reg_id' => $sample_id, 'st.is_deleted !=' => '2', 'dept.status' => '1', 'tsd.is_deleted' => 0]);
            $this->db->group_by('tp.test_parameters_id');
            $query = $this->db->get('sample_test st');
            return ($query->num_rows() > 0) ? $query->result_array() : [];
        }
    }

    public function get_all_department_list()
    {
        $this->db->distinct();
        $this->db->select('dept.dept_id, dept.dept_name');
        $this->db->join('test_parameter_details tpd', 'tpd.department_id = dept.dept_id', 'inner');
        $this->db->where(['dept.status' => '1', 'tpd.is_deleted' => 0, 'tpd.status' => 1, 'tpd.branch_id' => $this->session->userdata('branch_id')]);
        $query = $this->db->get('mst_departments dept');
        return ($query->num_rows() > 0) ? $query->result_array() : [];
    }
}
