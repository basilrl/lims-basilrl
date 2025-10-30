<?php
defined('BASEPATH') or exit('No direct access allowed');

class Currency extends MY_Controller{
    function __construct(){
        parent::__construct();
        $this->load->model('Currency_model','cm');
        $this->check_session();
    }   
    
    public function index(){
        $where = NULL;
        $search = NULL;
        $sortby = NULL;
        $order = NULL;

        $base_url = 'Currency/index';
        if ($this->uri->segment('3') != NULL && $this->uri->segment('3') != 'NULL') {
            $code_currency = $this->uri->segment('3');
            $data['code_currency'] =  base64_decode($code_currency);
            $base_url .= '/' . $code_currency;
            $where['msc.currency_code'] = base64_decode($code_currency);
        } else {
            $base_url .= '/NULL';
            $data['code_currency'] = NULL;
        }

        if ($this->uri->segment('4') != NULL && $this->uri->segment('4') != 'NULL') {
            $name_currency = $this->uri->segment('4');
            $data['name_currency'] =  base64_decode($name_currency);
            $base_url .= '/' . $name_currency;
            $where['msc.currency_name'] = base64_decode($name_currency);
        } else {
            $base_url .= '/NULL';
            $data['name_currency'] = NULL;
        }

        if ($this->uri->segment('5') != NULL && $this->uri->segment('5') != 'NULL') {
            $basic_unit = $this->uri->segment('5');
            $data['basic_unit'] =  base64_decode($basic_unit);
            $base_url .= '/' . $basic_unit;
            $where['msc.currency_basic_unit'] = base64_decode($basic_unit);
        } else {
            $base_url .= '/NULL';
            $data['basic_unit'] = NULL;
        }

        if ($this->uri->segment('6') != NULL && $this->uri->segment('6') != 'NULL') {
            $fractional_unit = $this->uri->segment('6');
            $data['fractional_unit'] =  base64_decode($fractional_unit);
            $base_url .= '/' . $fractional_unit;
            $where['msc.currency_fractional_unit'] = base64_decode($fractional_unit);
        } else {
            $base_url .= '/NULL';
            $data['fractional_unit'] = NULL;
        }

        if ($this->uri->segment('7') != NULL && $this->uri->segment('7') != 'NULL') {
            $created_pesron = $this->uri->segment('7');
            $data['created_pesron'] =  base64_decode($created_pesron);
            $base_url .= '/' . $created_pesron;
            $where['ap.uidnr_admin'] = base64_decode($created_pesron);
        } else {
            $base_url .= '/NULL';
            $data['created_pesron'] = NULL;
        }

        if ($this->uri->segment('8') != NULL && $this->uri->segment('8') != 'NULL') {
            $id_status = $this->uri->segment('8');
            $data['id_status'] =  base64_decode($id_status);
            $base_url .= '/' . $id_status;
            $where['msc.status'] = base64_decode($id_status);
        } else {
            $base_url .= '/NULL';
            $data['id_status'] = "NULL";
        }

        if ($this->uri->segment('9') != NULL && $this->uri->segment('9') != 'NULL') {
            $search = $this->uri->segment('9');
            $data['search'] =  base64_decode($search);
            $base_url .= '/' . $search;
            $search = base64_decode($search);
        } else {
            $base_url .= '/NULL';
            $data['search'] = NULL;
        }

        if ($this->uri->segment('10') != NULL && $this->uri->segment('10') != 'NULL') {
            $sortby = $this->uri->segment('10');
            $base_url .= '/' . $sortby;
        } else {
            $base_url .= '/NULL';
        }

        if ($this->uri->segment('11') != NULL && $this->uri->segment('11') != 'NULL') {
            $order = $this->uri->segment('11');
            $base_url .= '/' . $order;
        } else {
            $base_url .= '/NULL';
        }

        $total_row = $this->cm->get_currency_list(NULL, NULL, $search, NULL, NULL, $where, '1');
        $config = $this->pagination($base_url, $total_row, 10, 11);
        $data["links"] = $config["links"];
        $data['cn_codes'] = $this->cm->fetch_currency_code();
        $data['cn_name'] = $this->cm->fetch_currency_name();
        $data['cn_basics'] = $this->cm->fetch_basic_unit();
        $data['cn_fracts'] = $this->cm->fetch_fractional_unit();
        $data['created_by_name'] = $this->cm->fetch_created_person();
        $data['crncy_list'] = $this->cm->get_currency_list($config["per_page"], $config['page'], $search, $sortby, $order, $where);
        $page_no = $this->uri->segment('11');
        $start = (int)$page_no + 1;
        $end = (($data['crncy_list']) ? count($data['crncy_list']) : 0) + (($page_no) ? $page_no : 0);
        $data['result_count'] = "Showing " . $start . " - " . $end . " of " . $total_row . " Results";
        if ($order == NULL || $order == 'NULL') {
            $data['order'] = ($order) ? "DESC" : "ASC";
        } else {
            $data['order'] = ($order == "ASC") ? "DESC" : "ASC";
        }
        $this->load_view('currency/manage_currency', $data);
    }

    public function add_currency(){
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
        $this->form_validation->set_rules('currency_code', 'Currency Code', 'required|trim|is_unique[mst_currency.currency_code]');
        $this->form_validation->set_rules('currency_name', 'Currency Name', 'required|trim|is_unique[mst_currency.currency_name]');
        $this->form_validation->set_rules('exchange_rate', 'Exchange Rate', 'required|trim|is_unique[mst_currency.currency_name]'); 
        $this->form_validation->set_rules('currency_basic_unit', 'Currency Basic Unit', 'required|trim|is_unique[mst_currency.currency_basic_unit]');
        $this->form_validation->set_rules('currency_fractional_unit', 'Currency Fractional Unit', 'required|trim|is_unique[mst_currency.currency_fractional_unit]');
        $this->form_validation->set_rules('currency_decimal', 'Currency Decimal Point', 'required');
        $this->form_validation->set_rules('status', 'Status', 'required');
        if ($this->form_validation->run() == false) {
            $data = array(
                'error' => $this->form_validation->error_array(),
                'status' => 0,
                'msg' => 'Please Fill All Required Fields'
            );
        } else {
            $checkUser = $this->session->userdata();
            $store_data = array();
            $fetch_data = $this->input->post();
            $store_data['currency_code']            = $fetch_data['currency_code'];
            $store_data['currency_name']            = $fetch_data['currency_name'];
            $store_data['exchange_rate']            = $fetch_data['exchange_rate'];
            $store_data['currency_basic_unit']      = $fetch_data['currency_basic_unit'];
            $store_data['currency_fractional_unit'] = $fetch_data['currency_fractional_unit'];
            $store_data['currency_decimal']         = $fetch_data['currency_decimal'];
            $store_data['status']                   = $fetch_data['status'];
            $store_data['created_by']               = $checkUser['user_data']->uidnr_admin;
            $store_data['created_on']               = date("Y-m-d h:i:s");
            if (!empty($fetch_data)) {
                $insert_data = $this->cm->insert_data('mst_currency', $store_data);
                if ($insert_data) {
                    $log_deatils = array(
                        'text'          => "Added Currency with Currency Name ".$store_data['currency_name'],
                        'created_by'    => $store_data['created_by'],
                        'created_on'    => $store_data['created_on'],
                        'record_id'     => $insert_data,
                        'source_module' => 'Currency',
                        'action_taken'  => 'add_currency'
                    );

                    $log = $this->cm->insert_data('user_log_history',$log_deatils);
                    if($log){
                        $this->session->set_flashdata('success', 'Currency Added Successfully');
                        $data = array(
                            'status' => 1,
                            'msg' => 'Currency Added Successfully'
                        );
                    }
                    else{
                        $this->session->set_flashdata('error', 'Error in Maintaining Currency Add Log ');
                        $data = array(
                            'status' => 0,
                            'msg' => 'Error in Maintaining Currency Add Log'
                        );
                    }
                    
                } else {
                    $this->session->set_flashdata('error', 'Error in adding Currency');
                    $data = array(
                        'status' => 0,
                        'msg' => 'Error in adding Currency'
                    );
                }
            } else {
                $this->session->set_flashdata('error', 'Data Not Found !!');
                $data = array(
                    'status' => 0,
                    'msg' => 'Data Not Found'
                );
            }
        }
        echo json_encode($data);
    }

    public function fetch_currency_for_edit(){
        $currency_id = $this->input->post('currency_id');
        $data = $this->cm->fetch_currency_for_edit($currency_id);
        echo json_encode($data);
    }

    public function update_currency(){
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
        $this->form_validation->set_rules('currency_name', 'Currency Name', 'required|trim|callback_update_crncyname');
        $this->form_validation->set_rules('currency_code', 'Currency Code', 'required|trim|callback_update_crncycode');
        $this->form_validation->set_rules('exchange_rate', 'Exchnage Rate', 'required|trim');
        $this->form_validation->set_rules('currency_basic_unit', 'Currency Basic Unit', 'required|trim|callback_update_crncybunit');
        $this->form_validation->set_rules('currency_fractional_unit', 'Currency Fractional Unit', 'required|trim|callback_update_crncyfunit');
        $this->form_validation->set_rules('currency_decimal', 'Currency Decimal', 'required|trim');
        if ($this->form_validation->run() == false) {
            $data = array(
                'error' => $this->form_validation->error_array(),
                'status' => 0,
                'msg' => 'Please Fill All Required Fields'
            );
        } else {
            $store_data = array();
            $checkUser = $this->session->userdata();
            $fetch_data = $this->input->post();
            $store_data['currency_name']                = $fetch_data['currency_name'];
            $store_data['currency_code']                = $fetch_data['currency_code'];
            $store_data['exchange_rate']                = $fetch_data['exchange_rate'];
            $store_data['currency_basic_unit']          = $fetch_data['currency_basic_unit'];
            $store_data['currency_fractional_unit']     = $fetch_data['currency_fractional_unit'];
            $store_data['currency_decimal']             = $fetch_data['currency_decimal'];
            $store_data['updated_by']                   = $checkUser['user_data']->uidnr_admin;
            $store_data['updated_on']                   = date("Y-m-d h:i:s");
            $where['currency_id']                       = $fetch_data['currency_id'];
            if (!empty($fetch_data)) {
                $data_updated = $this->cm->update_data('mst_currency', $store_data, $where);
                if ($data_updated) {
                    $log_deatils = array(
                        'text'          => "Updated Currency with Currency Name ".$store_data['currency_name'],
                        'created_by'    => $store_data['updated_by'],
                        'created_on'    => $store_data['updated_on'],
                        'record_id'     => $where['currency_id'],
                        'source_module' => 'Currency',
                        'action_taken'  => 'update_currency'
                    );
    
                    $log = $this->cm->insert_data('user_log_history',$log_deatils);
                    if($log){
                        $this->session->set_flashdata('success', 'Currency Updated Successfully');
                        $data = array(
                            'status' => 1,
                            'msg' => 'Currency Updated Successfully'
                        );
                    }
                    else{
                        $this->session->set_flashdata('error', 'Error in Maintaining Update Currency Log');
                        $data = array(
                            'status' => 0,
                            'msg' => 'Error in Maintaining Update Currency Log'
                        );
                    }
                } else {
                    $this->session->set_flashdata('error', 'Error in Updating Currency');
                    $data = array(
                        'status' => 0,
                        'msg' => 'Error in Updating Currency Details'
                    );
                }
            } else {
                $this->session->set_flashdata('error', 'Data Not Found !!');
                $data = array(
                    'status' => 0,
                    'msg' => 'Data Not Found !!'
                );
            }
        }
        echo json_encode($data);
    }

    public function currency_status() {
        if (!empty($this->input->post())) {
            $checkUser = $this->session->userdata();
            $data_fetch = $this->cm->fetch_currency_for_edit($this->input->post('currency_id'));
            $currency_name = $data_fetch->currency_name;
            $status = $this->cm->update_currency_status($this->input->post());
            if ($status) {
                $log_deatils = array(
                    'text'          => "Updated Currency Status with Currency name ".$currency_name,
                    'created_by'    => $checkUser['user_data']->uidnr_admin,
                    'created_on'    => date('Y-m-d h:i:s'),
                    'record_id'     => $this->input->post('currency_id'),
                    'source_module' => 'Currency',
                    'action_taken'  => 'currency_status'
                );
                $log = $this->cm->insert_data('user_log_history',$log_deatils);
                if($log){
                    $this->session->set_flashdata('success', 'Currency Status Updated Successfully');
                    $data = array(
                        'status' => 1,
                        'msg' => 'Currency Status Updated Successfully'
                    );
                }
                else{
                    $this->session->set_flashdata('error', 'Error in Maintaining Currency Status Log');
                    $data = array(
                        'status' => 0,
                        'msg' => 'Error in Maintaining Currency Status Log'
                    );
                }
            } else {
                $this->session->set_flashdata('error', 'Error in Updating Currency Status');
                $data = array(
                    'status' => 0,
                    'msg' => 'Error in Updating Currency Status'
                );
            }
        }
        echo json_encode($data);
    }

    // added by millan on 09-April-2021
    public function update_crncycode($field){
        $update_form = $this->input->post();
        $check_fileds = array();
        $check_fileds['LOWER(currency_name)'] = strtolower($update_form['currency_name']);
        $check_fileds['LOWER(currency_code)'] = strtolower($update_form['currency_code']);
        $check_fileds['LOWER(currency_basic_unit)'] = strtolower($update_form['currency_basic_unit']);
        $check_fileds['LOWER(currency_fractional_unit)'] = strtolower($update_form['currency_fractional_unit']);
        $check_fileds['currency_id NOT IN ('.$update_form['currency_id'].')'] =  NULL;
        if(!empty($update_form) && !empty($update_form['currency_id']) ){
            $check_in = $this->cm->get_row('*', 'mst_currency' , $check_fileds);
            if($check_in){
                $this->form_validation->set_message('update_crncyname', 'The {field} field can not be the same. "It Must be Unique!!"');
                return FALSE;
            }
            else
            {
                return TRUE;
            }
        }
        else{
            return false;
        }
    }

    // added by millan on 09-April-2021
    public function update_crncyname($field){
        $update_form = $this->input->post();
        $check_fileds = array();
        $check_fileds['LOWER(currency_name)'] = strtolower($update_form['currency_name']);
        $check_fileds['LOWER(currency_code)'] = strtolower($update_form['currency_code']);
        $check_fileds['LOWER(currency_basic_unit)'] = strtolower($update_form['currency_basic_unit']);
        $check_fileds['LOWER(currency_fractional_unit)'] = strtolower($update_form['currency_fractional_unit']);
        $check_fileds['currency_id NOT IN ('.$update_form['currency_id'].')'] =  NULL;
        if(!empty($update_form) && !empty($update_form['currency_id']) ){
            $check_in = $this->cm->get_row('*', 'mst_currency' , $check_fileds);
            if($check_in){
                $this->form_validation->set_message('update_crncyname', 'The {field} field can not be the same. "It Must be Unique!!"');
                return FALSE;
            }
            else
            {
                return TRUE;
            }
        }
        else{
            return false;
        }
    }

    // added by millan on 09-April-2021
    public function update_crncybunit($field){
        $update_form = $this->input->post();
        $check_fileds = array();
        $check_fileds['LOWER(currency_name)'] = strtolower($update_form['currency_name']);
        $check_fileds['LOWER(currency_code)'] = strtolower($update_form['currency_code']);
        $check_fileds['LOWER(currency_basic_unit)'] = strtolower($update_form['currency_basic_unit']);
        $check_fileds['LOWER(currency_fractional_unit)'] = strtolower($update_form['currency_fractional_unit']);
        $check_fileds['currency_id NOT IN ('.$update_form['currency_id'].')'] =  NULL;
        if(!empty($update_form) && !empty($update_form['currency_id']) ){
            $check_in = $this->cm->get_row('*', 'mst_currency' , $check_fileds);
            if($check_in){
                $this->form_validation->set_message('update_crncyname', 'The {field} field can not be the same. "It Must be Unique!!"');
                return FALSE;
            }
            else
            {
                return TRUE;
            }
        }
        else{
            return false;
        }
    }

    // added by millan on 09-April-2021
    public function update_crncyfunit($field){
        $update_form = $this->input->post();
        $check_fileds = array();
        $check_fileds['LOWER(currency_name)'] = strtolower($update_form['currency_name']);
        $check_fileds['LOWER(currency_code)'] = strtolower($update_form['currency_code']);
        $check_fileds['LOWER(currency_basic_unit)'] = strtolower($update_form['currency_basic_unit']);
        $check_fileds['LOWER(currency_fractional_unit)'] = strtolower($update_form['currency_fractional_unit']);
        $check_fileds['currency_id NOT IN ('.$update_form['currency_id'].')'] =  NULL;
        if(!empty($update_form) && !empty($update_form['currency_id']) ){
            $check_in = $this->cm->get_row('*', 'mst_currency' , $check_fileds);
            if($check_in){
                $this->form_validation->set_message('update_crncyname', 'The {field} field can not be the same. "It Must be Unique!!"');
                return FALSE;
            }
            else
            {
                return TRUE;
            }
        }
        else{
            return false;
        }
    }

    // added by millan on 09-April-2021
    public function get_currency_log(){
		$currency_id = $this->input->post('currency_id');
		$data = $this->cm->get_currency_log($currency_id);
		echo json_encode($data);
	}

    // added by millan on 13-04-2021
    public function fetch_currency_exchange_details(){
        $where['primary_curr_id'] = $this->input->post('currency_id');
        $where['ex_curr_id'] = $this->input->post('ex_curr_id');
        $data = $this->cm->fetch_currency_exchange_details($where);
        echo json_encode($data);
    }

    // added by millan on 13-04-2021
    public function add_update_exch_currency(){
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
        $this->form_validation->set_rules('ex_curr_id', 'Select Currency', 'required');
        if ($this->form_validation->run() == false) {
            $data = array(
                'error' => $this->form_validation->error_array(),
                'status' => 0,
                'msg' => 'Please Fill All Required Fields'
            );
        }else{
            $checkUser = $this->session->userdata();
            $store_data = array();
            $fetch_data = $this->input->post();
            $store_data['primary_curr_id'] =  $fetch_data['currency_id'];
            $store_data['ex_curr_id'] =  $fetch_data['ex_curr_id'];
            $store_data['ex_rate'] = $fetch_data['ex_rate']; 
            if (!empty($fetch_data['currency_ex_id'])) {
                $store_data['updated_by'] = $checkUser['user_data']->uidnr_admin;
                $store_data['updated_on'] = date("Y-m-d h:i:s");
                $update_data = $this->cm->update_data('currency_exchage', $store_data, array('primary_curr_id' => $fetch_data['currency_id'], 'ex_curr_id' => $fetch_data['ex_curr_id']));
                if($update_data){
                    $log_deatils = array(
                        'text'          => "Updated Currency Exchange with Currency ID ".$store_data['primary_curr_id'],
                        'created_by'    => $store_data['updated_by'],
                        'created_on'    => $store_data['updated_on'],
                        'record_id'     => $this->input->post('currency_ex_id'),
                        'source_module' => 'Currency',
                        'action_taken'  => 'add_update_exch_currency'
                    );
                    $log = $this->cm->insert_data('user_log_history',$log_deatils);
                    if($log){
                        $this->session->set_flashdata('success', 'Currency Exchange Updated Successfully');
                        $data = array(
                            'status' => 1,
                            'msg' => 'Currency Exchange Updated Successfully'
                        );
                    }
                    else{
                        $data = array(
                            'status' => 1,
                            'msg' => 'Error in Maintaining Currency Exchange Log'
                        );
                    }
                }
            }
            else{
                $store_data['created_by'] = $checkUser['user_data']->uidnr_admin;
                $store_data['created_on'] = date("Y-m-d h:i:s");
                $data_insert = $this->cm->insert_data('currency_exchage', $store_data);
                $log_deatils = array(
                    'text'          => "Updated Currency Exchange with Currency ID ".$data_insert,
                    'created_by'    => $store_data['created_by'],
                    'created_on'    => $store_data['created_on'],
                    'record_id'     => $data_insert,
                    'source_module' => 'Currency',
                    'action_taken'  => 'add_update_exch_currency'
                );
                $log = $this->cm->insert_data('user_log_history',$log_deatils);
                if($log){
                    $this->session->set_flashdata('success', 'Currency Add Updated Successfully');
                    $data = array(
                        'status' => 1,
                        'msg' => 'Currency Add Updated Successfully'
                    );
                }
                else{
                    $data = array(
                        'status' => 1,
                        'msg' => 'Error in Maintaining Currency Add Log'
                    );
                }
            } 
        }
        echo json_encode($data);
    }


    public function store_method()
    {
        $query = $this->db->select('DISTINCT(test_method)')->get('tests')->result_array();
        
        foreach($query as $data){
            $method_array = array(
                'test_method_name'      => $data['test_method'],
                'created_by'            => $this->admin_id(),
                'created_on'            => date('Y-m-d H:i:s')
            );
            $methods_array[] = $method_array;
        }
        $this->db->insert_batch('mst_test_methods',$methods_array);
        echo "<pre>"; echo $this->db->last_query();
        
    }

    public function update_method_id()
    {
        $query = $this->db->query('SELECT * FROM `tests` WHERE test_method_id IS NULL')->result_array();
        foreach($query as $data)
        {
            $method = $this->db->select('test_method_id, test_method_name')->where('test_method_name',$data['test_method'])->get('mst_test_methods')->row_array();
            $this->db->update('tests',['test_method_id' => $method['test_method_id']],['test_id' => $data['test_id']]);
            echo "<pre>";echo $this->db->last_query();
        }
    }
}    
?>