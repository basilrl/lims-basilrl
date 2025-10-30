<?php
defined('BASEPATH') or exit('No direct access allowed');

class ReleaseReport_Model extends MY_Model
{
    function __construct()
    {
        parent::__construct();
    }

    public function get_release_to_client_data($data)
    {
        $query =  $this->db->select('sr.sample_customer_id, manual_report_file')
                            ->join('generated_reports gr','gr.sample_reg_id = sr.sample_reg_id')
                            ->from('sample_registration sr')
                            ->where('sr.sample_reg_id', $data['sample_reg_id'])
                            ->get();
        if ($query) {
            $customer_id = $query->row();
            $status = $this->db->select('cc.email, cc.customer_name, cc.city')->from('cust_customers cc')->where('cc.customer_id', $customer_id->sample_customer_id)->get(); // updated by millan on 01-07-2021
            if ($status) {
                $data = $status->row();
                $data->report_file = $customer_id->manual_report_file;
                return $data;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
}


?>