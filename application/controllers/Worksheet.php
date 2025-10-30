<?php
defined('BASEPATH') or exit('No Direct Access Allowed');

class Worksheet extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('Worksheet_Model', 'WM');
    }

    public function get_sample_worksheet_pdf()
    {
        $sample_id = base64_decode($this->input->get('sample_reg_id'));
        $format_id = base64_decode($this->input->get('format_id'));
        $dept_id = base64_decode($this->input->get('dept_id'));
        $performa_no = base64_decode($this->input->get('performa_no'));

        $data['sample_details'] = $this->WM->get_worksheet_details($sample_id);

        $quotation_type = $this->WM->get_distinct_quotation_type($sample_id);
        foreach ($quotation_type as $val) {
            $data['test_details'][$val['quotation_type']] = $this->WM->get_worksheet_test_details($val['quotation_type'], $sample_id, $dept_id);
        }

        $data['branch_address'] = $this->WM->get_row('branch_address', 'food_mst_branches', ['branch_id' => $this->session->userdata('branch_id')]);

        $fmt_data = $this->WM->get_row('*', 'tbl_worksheet_format', ['id' => $format_id]);
        if (!empty($fmt_data->view_name)) {

            if ($this->session->userdata('branch_id') == 2) {
                $perf_data = $this->WM->get_row('*', 'tbl_worksheet_performa_dept', ['id' => $performa_no, 'branch_id' => $this->session->userdata('branch_id')]);

                $data['performa_no']    = $perf_data->performa_no;
            } else {
                $data['performa_no']    = $fmt_data->performa_no;
            }
            $data['issue_no']       = $fmt_data->issue_no;
            $data['revision_no']    = $fmt_data->revision_no;
            $dept = (!empty($dept_id)) ? ', and Department ID: ' . $dept_id : '';

            $log_details = array(
                'source_module'     => 'Worksheet',
                'operation'         => 'get_sample_worksheet_pdf',
                'record_id'         => $sample_id,
                'uidnr_admin'       => $this->WM->admin_id(),
                'log_activity_on'   => date("Y-m-d H:i:s"),
                'action_message'    => 'View Worksheet: Format ID: ' . $format_id . $dept
            );
            $this->WM->save_user_log($log_details);

            //$this->load->view('template/worksheet_template/' . $this->session->userdata('branch_id') . '/' . $fmt_data->view_name, $data);
            $this->generate_pdf('template/worksheet_template/' . $this->session->userdata('branch_id') . '/' . $fmt_data->view_name, $data);
        }
    }

    // Modify by CHANDAN --- 10-02-2022
    public function get_sample_worksheet()
    {
        $sample_id = $this->input->post('sample_reg_id');
        $format_id = ($this->input->post('format_id')) ? $this->input->post('format_id') : 1;
        $performa_no = ($this->session->userdata('branch_id') == 2) ? $this->input->post('performa_no') : 0;
        $dept_id = ($this->input->post('department_id')) ? implode(",", $this->input->post('department_id')) : NULL;

        if (!empty($sample_id) && !empty($format_id)) {
            $get = '<iframe width="100%" height="650px!important" src="' . base_url() . 'Worksheet/get_sample_worksheet_pdf?sample_reg_id=' . base64_encode($sample_id) . '&format_id=' . base64_encode($format_id) . '&dept_id=' . base64_encode($dept_id) . '&performa_no=' . base64_encode($performa_no) . '"></iframe>';
            echo json_encode($get);
        }
    }

    // Modify by CHANDAN to get departments --- 10-02-2022
    public function get_worksheet_format()
    {
        $sample_reg_id = $this->input->post('sample_reg_id');
        if (!empty($sample_reg_id)) {
            $data['format'] = $this->WM->fetch_all_data('tbl_worksheet_format');

            $data['format'] = $this->WM->get_result('*', 'tbl_worksheet_format', ['branch_id' => $this->session->userdata('branch_id')]);

            $quotation_type = $this->WM->get_distinct_quotation_type($sample_reg_id);

            $dept_id = $departments = array();
            if ($this->session->userdata('branch_id') != 2) {
                foreach ($quotation_type as $val) {
                    $dept = $this->WM->get_worksheet_all_departments($sample_reg_id, $val['quotation_type']);
                    if (!empty($dept)) {
                        foreach ($dept as $key => $val) {
                            if (!in_array($val['dept_id'], $dept_id)) {
                                array_push($dept_id, $val['dept_id']);
                                array_push($departments, array('dept_id' => $val['dept_id'], 'dept_name' => $val['dept_name']));
                            }
                        }
                    }
                }
                $data['dept'] = $departments;
            } else {
                $data['dept'] = $this->WM->get_all_department_list();
            }
            echo json_encode($data);
        } else {
            echo "Sample Registration ID not defined";
            die;
        }
    }
}
