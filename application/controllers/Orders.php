<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Orders extends MY_Controller {

	public function __construct(){
		parent::__construct();
		$this->check_session();
        $this->load->model('Orders_model', 'OM');
	}


    public function index(){
        $this->load_view('orders_list/index');
    }

    public function order_list($search,$page){
       
        $where = NULL;
        if (!empty($search) && $search != 'NULL') {
            $search = base64_decode($search);
        } else {
            $search = NULL;
        }
        $per_page = 10;
        if ($page != 0) {
            $page = ($page - 1) * $per_page;
        }
        $total_row = $this->OM->get_order_list(NULL, NULL, $search, $where, '1');
        $config['base_url'] = base_url('Orders/order_list');
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
        $result = $this->OM->get_order_list($per_page, $page, $search, $where);
        $html = '';
        if ($result) {
            foreach ($result as $value) {
                $page++;
                $html .= '<tr>';
                $html .= '<th scope="col">' . $page . '</th>';
                $html .= '<td>' . $value->customer_name . '</td>';
                $html .= '<td>' . $value->contact_name . '</td>';
                $html .= '<td>' . $value->email . '</td>';
                $html .= '<td>' . $value->mobile_no . '</td>';
                $html .= '<td>' . $value->ord_txn_id . '</td>';
                $html .= '<td>' . $value->update_time . '</td>';

                $html .= '<td>';
                if ($this->permission_action('Orders/Payslip_Pdf')) {
                    $html .= '<a  target="_blank" class="btn btn-primary" href="'. base_url("Orders/Payslip_Pdf") . '/' . $value->order_id .'" title="Download Pdf"><i class="fas fa-file-pdf"></i></a>';
                }
                $html .= '</td>';
                $html .= '/<tr>';
            }
        } else {
            $html .= '<tr><td colspan="14"><h4>NO RECORD FOUND</h4></td></tr>';
        }
        $data['result'] = $html;
        echo json_encode($data);

        
    }

    public function Payslip_Pdf(){

        $this->load->library('M_pdf');
		$this->m_pdf->pdf->charset_in = 'UTF-8';
		$this->m_pdf->pdf->setAutoTopMargin = 'stretch';
		$this->m_pdf->pdf->lang = 'ar';
		$this->m_pdf->pdf->autoLangToFont = true;
        $data = [];
        $id = $this->uri->segment(3);
        $data['customer'] = current($this->OM->payslip($id));
        $data['products'] = $this->OM->getorderpro($id);
        // $amount =  $data['customer']['order_amount'];
        // $data['customer']['order_amount_in_word'] = $this->getIndianCurrency($amount);

        $this->load->view('orders_list/Bill', $data);

        $html = $this->output->get_output();
        $this->m_pdf->pdf->WriteHTML($html);
        $this->m_pdf->pdf->Output(rand(). '.pdf', 'I');
    }

   
}
      