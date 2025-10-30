<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Item extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Item_model');
        // $this->permission('Role/index');
        $checkUser = $this->session->userdata('user_data');
        $this->user = $checkUser->uidnr_admin;
    }

    public function index()
    {
        $data['store'] = $this->Item_model->get_result('store_id,store_name', 'stores', ['is_deleted' => 0, 'main_store' => 1]);
        $data['unit'] = $this->Item_model->get_result('unit_id, unit', 'units');
        $this->load_view('Item/index', $data);
    }

    public function store_listing($search, $page = 0)
    {
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
        $total_row = $this->Item_model->getCategory_list(NULL, NULL, $search, $where, '1');
        $config['base_url'] = base_url('Role/role_listing');
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

        $result = $this->Item_model->getCategory_list($per_page, $page, $search, $where);
        $html = '';
        if ($result) {
            foreach ($result as $value) {
                $page++;
                $html .= '<tr>';
                $html .= '<th scope="col">' . $page . '</th>';
                $html .= '<td>' . $value->item_name . '</td>';
                $html .= '<td>' . $value->category_name . '</td>';
                $html .= '<td>' . $value->unit_name . '</td>';
                $html .= '<td>' . $value->min_quantity_required . '</td>';
                $html .= '<td>';

                if ($this->permission_action('Item/edit')) {
                $html .= '<a href="javascript:void(0);" title="EDIT" class="btn btn-sm edit_role" data-toggle="modal" data-id="' . base64_encode($value->item_id) . '" data-target="#add_Store"><img width="28px" src="' . base_url('public/img/icon/edit.png') . '" alt="BASIL" class="edit_application_data" ></a> ';
                }
                if ($this->permission_action('Item/addStock')) {
                    $html .= '<a title="ADD STOCK DETAILS" href="javascript:void(0);" class="btn btn-sm add_Stock_form_open" data-toggle="modal" data-id="' . base64_encode($value->item_id) . '" data-target="#role_permission"><img width="28px" src="' . base_url('public/img/icon/add_Stock.png') . '" alt="BASIL" class="edit_application_data" ></a>';
                }
                if ($this->permission_action('Item/Log')) {
                    $html .= '<a title="USER LOG" href="javascript:void(0);" class="btn btn-sm log" data-toggle="modal" data-id="' . base64_encode($value->item_id) . '" data-target="#user_log"><img width="28px" src="' . base_url('public/img/icon/logs.png') . '" alt="BASIL" class="edit_application_data" ></a>';
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
    public function add()
    {
        $cat = $this->Item_model->category();
        $uni = $this->Item_model->unit();
        $post = $this->input->post();
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');
        if (!empty($post['item_id'])) {
            $this->form_validation->set_rules('item_id', 'UNIQUE ID', 'trim|required');
            $this->form_validation->set_rules('item_name', 'Category Name', 'trim|required|callback_check_Store');
        } else {
            $this->form_validation->set_rules('item_name', 'Category Name', 'trim|required|min_length[2]|is_unique[master_items.item_name]');
        }
        $this->form_validation->set_rules('category_id', 'Category Name', 'trim|required|is_natural_no_zero|in_list[' . implode(',', array_column($cat, 'category_id')) . ']');
        $this->form_validation->set_rules('unit', 'Unit', 'trim|required|is_natural_no_zero|in_list[' . implode(',', array_column($uni, 'unit_id')) . ']');
        if ($this->form_validation->run() == TRUE) {
            if (!array_key_exists('critical_item', $post)) {
                $post['critical_item'] = 0;
            }
            $msg = array();
            if (!empty($post['item_id'])) {
                $post['item_id'] = base64_decode($post['item_id']);
                $insert = $this->Item_model->update_data('master_items', $post, ['item_id' => $post['item_id']]);
                $msg['msg'] = 'Updated Item Details.';
            } else {
                unset($post['item_id']);
                $insert = $this->Item_model->insert_data('master_items', $post);
                if ($insert) {
                    $msg['msg'] = 'Created New Item.';
                }
            }
            if ($insert) {
                $msg['status'] = 1;
            } else {
                $msg = array(
                    'status' => 0,
                    'msg' => 'ERROR WHILE SEND'
                );
            }
        } else {
            $msg = array(
                'status' => 0,
                'errors' => $this->form_validation->error_array(),
                'msg' => 'PLEASE ENTER VALID TEXT'
            );
        }
        echo json_encode($msg);
    }
    public function check_Store($file)
    {
        $files = $this->input->post();
        $files['item_id']=  base64_decode($files['item_id']);
        $count = $this->Item_model->get_row('count(*) as count', 'master_items', ['LOWER(item_name)' => strtolower($files['item_name']), 'item_id NOT IN (' .$files['item_id'] . ')' => null]);
        if ($count && $count->count > 0) {
            $this->form_validation->set_message('check_Store', 'Item name Already Exist');
            return false;
        }
        return true;
    }
    public function master_items_get()
    {
        $post = $this->input->post();
        $post['id'] = base64_decode($post['id']);
        echo json_encode($this->Item_model->get_row('*', 'master_items', ['item_id' => $post['id']]));
    }

    public function category()
    {
        echo json_encode($this->Item_model->category());
    }
    public function unit()
    {
        echo json_encode($this->Item_model->unit());
    }
    public function addStock()
    {
        $store = $this->Item_model->get_result('store_id,store_name', 'stores', ['is_deleted' => 0, 'main_store' => 1]);
        $unit = $this->Item_model->get_result('unit_id, unit', 'units');
        $post = $this->input->post();
        $this->form_validation->set_rules('item_id', 'UNIQUE ID', 'trim|required');
        $this->form_validation->set_rules('po_num', 'PO NUMBER', 'trim|required|min_length[3]');
        $this->form_validation->set_rules('item_store_id', 'STORE', 'trim|required|in_list[' . implode(',', array_column($store, 'store_id')) . ']');
        $this->form_validation->set_rules('item_quantity', 'QUANTITY', 'trim|required');
        $this->form_validation->set_rules('stock_item_unit', 'UNIT', 'trim|required|in_list[' . implode(',', array_column($unit, 'unit_id')) . ']');
        $this->form_validation->set_rules('po_date', 'PO DATE', 'trim|required');
        if ($this->form_validation->run() == true) {
            $post['item_id'] = base64_decode($post['item_id']);
            $this->db->trans_begin();
            $prev_stock = $this->Item_model->get_row('item_id,current_stock', 'current_stock', ['item_id' => $post['item_id'], 'store_id' => $post['item_store_id']]);
            $prev_stock = ($prev_stock && $prev_stock->current_stock > 0) ? $prev_stock->current_stock : 0;
            $moving_quantity = 0;
            $item_unit = $this->Item_model->get_row('unit', 'master_items', ['item_id' => $post['item_id']]);
            if ($item_unit && !empty($item_unit->unit)) {
                $converting_units = $this->Item_model->get_row('uc_formula', 'unit_conversion', ['uc_rep_unit_id' => $item_unit->unit, 'uc_unit_id' => $post['stock_item_unit']]);
                if ($converting_units && !empty($converting_units->uc_formula)) {
                    $mov = str_ireplace("x", $post['item_quantity'], $converting_units->uc_formula);
                    $moving_quantity += eval('return ' . $mov . ';');
                }
            }
            $save_data = array(
                'str_stock_item_id' => $post['item_id'],
                'store_id' => $post['item_store_id'],
                'item_quantity' => $post['item_quantity'],
                'stock_item_unit' => $post['stock_item_unit'],
                'po_number' => $post['po_num'],
                'po_date' => date('Y-m-d', strtotime(str_replace('/', '-', $post['po_date']))),
                'purchase_value' => $post['purchase_value'],
                'invoice_number' => $post['invoice_num'],
                'invoice_date' => date('Y-m-d', strtotime(str_replace('/', '-', $post['invoice_date']))),
                'intent_number' => $post['intent_num'],
                'notes' => $post['stock_notes'],
                'stock_added_by' => $this->user,
                'operation' => 1, //purchase
                'current_stock' => $prev_stock,
                'transaction_id' => rand()
            );

            $this->Item_model->insert_data('stock_info', $save_data);

            $current_stock = array('item_id' => $post['item_id'], 'current_stock' => $prev_stock + $moving_quantity, 'current_stock_unit' => $post['stock_item_unit'], 'last_updated' => date("Y/m/d H:i:s"), 'store_id' => $post['item_store_id']);

            $stockAvailable = $this->Item_model->get_row('count(*) as cnt', 'current_stock', ['item_id' => $post['item_id']]);

            if ($stockAvailable && $stockAvailable->cnt > 0)
                $this->Item_model->update_data("current_stock", $current_stock, ['item_id' => $post['item_id'], 'store_id' => $post['item_store_id']]);
            else {
                $this->Item_model->insert_data("current_stock", $current_stock);
            }
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $msg = ["message" => "Something went wrong!.", "status" => 0];
            } else {
                $this->db->trans_commit();
                $logDetails = array(
                    'module' => 'Store', 'is_deleted' => '',
                    'store_id' => $post['item_store_id'], 'lab_id' => '', 'item_id' => $post['item_id'],
                    'action_message' => 'Stock added',
                    'operation' => 'addStock',
                    'uidnr_admin' => $this->user,
                    'log_activity_on' => date("Y-m-d H:i:s")
                );
                $this->Item_model->insert_data("store_log", $logDetails);
                $msg = ["status" => 1, "message" => "STOCK DETAILS ADD"];
            }
        } else {
            $msg = array(
                'status' => 0,
                'errors' => $this->form_validation->error_array(),

                'message' => 'PLEASE VALID ENTER'

            );
        }
        echo json_encode($msg);
    }

    public function store_userlog_dtlsview()
    {
        $post = $this->input->post();
        $post['id'] = base64_decode($post['id']);
        $result = $this->Item_model->get_result('store_log_id , (SELECT CONCAT( admin_profile.admin_fname, " ", admin_profile.admin_lname ) from admin_profile where admin_profile.uidnr_admin= store_log.uidnr_admin )as user, action_message ,DATE_FORMAT(log_activity_on, "%d-%b-%Y %H:%i:%s") AS log_activity_on,module', 'store_log', ['item_id' => $post['id']]);
        $html = '<table class="table table-hover"><thead><tr><th scope="col">Sn.</th><th scope="col">USER</th><th scope="col">ACTIVITY ON</th><th scope="col">MODULE</th><th>ACTION</th></tr></thead><tbody>';
        if ($result) {
            foreach ($result as $key => $value) {
                $html .= '<tr><th scope="row">'.($key+1).'</th><td>'.$value->user.'</td><td>'.$value->log_activity_on.'</td><td>'.$value->module.'</td><td>'.$value->action_message.'</td></tr>';
            }
        } else {
            $html .= '<tr><th colspan="5" >NO RECORD FOUND</th></tr>';
        }
        $html .= '</tbody></table>';
        echo json_encode($html);
    }

}
