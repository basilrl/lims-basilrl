<?php
class TaxRules extends MY_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('TaxRules_model', 'trm');
        $this->check_session();
        $checkUser = $this->session->userdata('user_data');
        $this->user = $checkUser->uidnr_admin;
    }
    public function index($search_lbl = 'NULL',$page_no = NULL )
    {
        $where = array();
        $base_url = base_url() . "TaxRules/index";
        $search_lbl1 = NULL;
        $base_url .= '/' . (($search_lbl != 'NULL') ? ($search_lbl) : 'NULL');
        $data['search_lbl'] = ($search_lbl != 'NULL') ? base64_decode($search_lbl) : 'NULL';
        if ($search_lbl != 'NULL' && !empty($search_lbl)) {
            $search_lbl1 = trim(base64_decode($search_lbl));
        }
        $this->load->library('pagination');
        $config = array();
        $config['full_tag_open']    = '<div class="pagging text-center"><nav><ul class="pagination">';
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
        $config["total_rows"] = $this->trm->taxrules_list(NULL, NULL, $where, $search_lbl1, '1');
        $config["per_page"] = 10;
        $config["uri_segment"] = 3;
        $config["base_url"] = $base_url;
        $config1 = $this->pagination->initialize($config);
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $data['tax_rl_listing'] = $this->trm->taxrules_list($config1->per_page, $page, $where, $search_lbl1);
        $start = (int)$page_no + 1;
        $end = (($data['tax_rl_listing']) ? count($data['tax_rl_listing']) : 0) + (($page_no) ? $page_no : 0);
        $data['result_count'] = "Showing " . $start . " - " . $end . " of " . $config["total_rows"] . " Results";
        $data["links"] = $this->pagination->create_links();
        $this->load_view('manage_taxrules/tr_listing', $data);
    }
}
