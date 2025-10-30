<?php
defined('BASEPATH') or exit('No direct access allowed');

class ManualInvoice extends MY_Model
{

    function __construct()
    {
        parent::__construct();
    }
    public function test_sum($where)
    {
        $this->db->select('SUM(test_price) as total');
        $this->db->join('sample_test st', 'st.sample_test_test_id=tests.test_id');
        $result = $this->db->get_where('tests', $where);
        if ($result->num_rows() > 0) {
            return $result->row();
        } else {
            return false;
        }
    }
    public function trf_details($where)
    {
        $this->db->select('*');
        $this->db->join('trf_registration tr', 'tr.trf_id=sr.trf_registration_id');
        $result = $this->db->get_where('sample_registration sr', $where);
        if ($result->num_rows() > 0) {
            return $result->row();
        } else {
            return false;
        }
    }
    public function branch_code($where)
    {
        $this->db->select('branch_code');
        $this->db->join('mst_branches br', 'br.branch_id=sample_registration.sample_registration_branch_id');
        $result = $this->db->get_where('sample_registration', $where);
        if ($result->num_rows() > 0) {
            return $result->row();
        } else {
            return false;
        }
    }
}
