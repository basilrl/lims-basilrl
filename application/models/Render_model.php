<?php

class Render_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function download_pdf($where)
    {
        $this->db->select('gr.manual_report_file');
      
        $path = $this->db->get_where('generated_reports as gr',$where);
        if ($path) {
            return $path->row();
        }
        else{
            return false;
        }
    }

    /* added by millan on 19-Jan-2021 for scan and download pdf */
    public function download_qr($cond)
    {
        $this->db->select('gr.manual_report_file');
        $this->db->from('generated_reports gr');
        $path = $this->db->where('gr.sample_reg_id',$cond)->get();
        if ($path) {
            return $path->row();
        }
        else{
            return false;
        }
    }

    // Added by Saurabh on 02-07-2021 to get Partial/Revise PDF
    public function download_report($cond){
        // Get report id 
        $this->db->select('gr.manual_report_file');
        $this->db->where('revise_report_num',$cond);
        $query = $this->db->get('generated_reports gr');
        echo $this->db->last_query(); 
        if($query->num_rows()  > 0){
            return $query->row();
        }
        return false;
    }
}
