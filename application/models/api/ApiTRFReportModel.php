<?php
defined('BASEPATH') or exit('No direct access allowed');
class ApiTRFReportModel extends CI_Model{
    function __construct()
    {
        parent::__construct();
    }

    public function getTRFReport($buyer_id, $keyword, $applicant, $product, $from_date, $to_date, $limit, $start, $count=false){
        $buyer_query = $this->db->select('customer_id')->where('basil_customer_details_id', $buyer_id)->get('cust_customers')->row_array();
        $this->db->select('trf_id, trf_ref_no, customer_name, sample_type_name, trf_sample_ref_id, tr.create_on, tat_date, service_type_name, sr.status as trf_status, manual_report_file,sr.sample_reg_id,sr.gc_no');
        $this->db->join('sample_registration sr','trf_id = trf_registration_id');
        $this->db->join('generated_reports gr','sr.sample_reg_id = gr.sample_reg_id');
        $this->db->join('cust_customers','customer_id = trf_applicant');
        $this->db->join('mst_sample_types','sample_type_id = trf_product');
        $this->db->join('service_type','service_type_id = trf_service_type');
        $this->db->where('trf_buyer',$buyer_query['customer_id']);
        // $this->db->where('sr.released_to_client',1);
        $this->db->where('sr.status','Report Generated');
        $this->db->order_by('sr.sample_reg_id', 'desc');
        $this->db->group_by('sr.sample_reg_id');
        ($keyword != 'NULL')?$this->db->like('trf_ref_no', trim($keyword)):'';
		($applicant != 'NULL')?$this->db->where('trf_applicant', $applicant):'';
		($product != 'NULL')?$this->db->where('trf_product', $product):'';
		if ($from_date != 'NULL' && $to_date != 'NULL') {
			$this->db->where('date(tr.create_on) >=', $from_date);
			$this->db->where('date(tr.create_on) <=', $to_date);
		} elseif ($from_date != 'NULL') {
			$this->db->where('date(tr.create_on)', $from_date);
		} elseif ($to_date != 'NULL') {
			$this->db->where('date(tr.create_on)', $to_date);
		}
        if(!$count){
			$this->db->limit($limit, $start);
		}
        $query = $this->db->get('trf_registration tr');
        // echo $this->db->last_query(); die;
        if($count){
			return $query->num_rows();
		}
        if($query->num_rows() > 0){
            return $query->result_array();
        }
        return false;
    }
}
?>