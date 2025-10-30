<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Stock_Utilization_Report extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Stock_Utilization_Report_model', 'SUR');
        $checkUser = $this->session->userdata('user_data');
        $this->user = $checkUser->uidnr_admin;
    }

    public function index()
    {
        $data['store'] = $this->SUR->get_result('store_id,store_name', 'stores', ['is_deleted' => 0, 'main_store' => 1]);
        $this->load_view('Stock_Utilization_Report/index', $data);
    }

    public function listing($store_id,$item_id,$start_date,$end_date,$page = 0)
    {
        $where = array();
        if ($store_id != 'NULL') {
           $where['store_id']=$store_id;
        }
        if ($item_id != 'NULL') {
           $where['item_id']=$item_id;
        }
        if ($start_date != 'NULL') {
           $where['start_date']=$start_date;
        }
        if ($end_date != 'NULL') {
           $where['end_date']=$end_date;
        }
        $per_page = 10;
        if ($page != 0) {
            $page = ($page - 1) * $per_page;
        }
        $total_row = $this->SUR->get_list(NULL, NULL, $where, '1');
        $config['base_url'] = base_url('Stock_Utilization_Report/listing');
        $config['use_page_numbers'] = TRUE;
        $config['total_rows'] = $total_row;
        $config['per_page'] = $per_page;
        $config['full_tag_open']    = '<div class="pagination text-center small"><nav><ul class="pagination">';
        $config['full_tag_close']   = '</ul></nav></div>';
        $config['num_tag_open']     = '<li class="page-item"><span class="page-link">';
        $config['num_tag_close']    = '</span></li>';
        $config['cur_tag_open']     = '<li class="page-item active"><span class="page-link">';
        $config['cur_tag_close']    = '<span class="sr-only">(current)</span></span></li>';
        $config['next_tag_open']    = '<li class="page-item"><span class="page-link">';
        $config['next_tag_close']  = '<span aria-hidden="true"></span></span></li>';
        $config['prev_tag_open']    = '<li class="page-item"><span class="page-link">';
        $config['prev_tag_close']  = '</span></li>';
        $config['first_tag_open']   = '<li class="page-item"><span class="page-link">';
        $config['first_tag_close'] = '</span></li>';
        $config['last_tag_open']    = '<li class="page-item"><span class="page-link">';
        $config['last_tag_close']  = '</span></li>';
        $this->pagination->initialize($config);
        $data['pagination'] =  $this->pagination->create_links();
        $result = $this->SUR->get_list($per_page, $page, $where);
        $html = '';
        if ($result) {
            foreach ($result as $value) {
                $page++;
                $html .= '<tr>';
                $html .= '<th scope="col">' . $page . '</th>';
                $html .= '<td>' . $value->opening_stock . '</td>';
                $html .= '<td>' . !empty($value->consumption_date)?date('d-m-Y',strtotime($value->consumption_date)):'' . '</td>';
                $html .= '<td>' . $value->quantity . '</td>';
                $html .= '<td>' . $value->unit . '</td>';
                $html .= '<td>' . $value->closing_stock . '</td>';
                $html .= '/<tr>';
            }
        } else {
            $html .= '<tr><td colspan="14"><h4>NO RECORD FOUND</h4></td></tr>';
        }
        $data['result'] = $html;
        echo json_encode($data);
    }
    public function item()
    {
        $post = $this->input->post();
        $concat =  $this->SUR->get_row('GROUP_CONCAT(DISTINCT item_id) as no', 'current_stock', ['store_id' => $post['id']]);
        if ($concat && !empty($concat->no)) {
            $result =  $this->SUR->get_result('item_id, item_name', 'master_items', ['item_id IN ('.$concat->no.') ' => null]);   
        }else{
            $result = false;
        }
        echo json_encode($result);
    }
}
