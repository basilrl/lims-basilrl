<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Stock_Register extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Stock_Register_model', 'SUR');
        $checkUser = $this->session->userdata('user_data');
        $this->user = $checkUser->uidnr_admin;
    }

    public function index()
    {
        $data['store'] = $this->SUR->get_result('store_id,store_name', 'stores', ['is_deleted' => 0, 'main_store' => 1]);
        $data['unit'] = $this->SUR->get_result('unit_id, unit', 'units');
        $this->load_view('Stock_Register/index', $data);
    }

    public function listing($store_id, $item_id, $per_page = 10, $page = 0)
    {
        $where = array();
        if ($store_id != 'NULL') {
            $where['a.store_id'] = $store_id;
        }
        if ($item_id != 'NULL') {
            $where['a.item_id'] = $item_id;
        }
        if ($page != 0) {
            $page = ($page - 1) * $per_page;
        }
        $total_row = $this->SUR->get_list(NULL, NULL, $where, '1');
        $config['base_url'] = base_url('Stock_Register/listing');
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
                $html .= '<td>' . $value->item_name . '</td>';
                $html .= '<td>' . $value->item_store_name . '</td>';
                $html .= '<td>' . $value->available_qty . '</td>';
                $html .= '<td>' . $value->min_quantity_required . '</td>';
                $html .= '<td>' . $value->unit . '</td>';
                $html .= '<td>';
                if ($this->permission_action('Stock_Register/addStockConsumption')) {
                $html .= '<a href="javascript:void(0);" title="Stock Consumption" class="btn btn-sm stock_con" data-toggle="modal" data-store_id="' . base64_encode($value->store_id) . '" data-id="' . base64_encode($value->item_id) . '" data-target="#stock_cons"><img width="28px" src="' . base_url('assets/images/Consume-stock.png') . '" alt="BASIL" class="edit_application_data" ></a>';
                }
                if ($this->permission_action('Stock_Register/initiateStockTransfer')) {
                $html .= '<a href="javascript:void(0);" title="Transfer Item to Store" class="btn btn-sm transfer_stock_con" data-toggle="modal" data-store_id="' . base64_encode($value->store_id) . '" data-id="' . base64_encode($value->item_id) . '" data-target="#trnasfer_stock_cons"><img width="28px" src="' . base_url('assets/images/icon/stock_send.png') . '" alt="BASIL" class="edit_application_data" ></a>';
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
    public function item()
    {
        $post = $this->input->post();
        $concat =  $this->SUR->get_row('GROUP_CONCAT(DISTINCT item_id) as no', 'current_stock', ['store_id' => $post['id']]);
        if ($concat && !empty($concat->no)) {
            $result =  $this->SUR->get_result('item_id, item_name', 'master_items', ['item_id IN (' . $concat->no . ') ' => null]);
        } else {
            $result = false;
        }
        echo json_encode($result);
    }
    public function addStockConsumption()
    {
        $post = $this->input->post();
        $this->form_validation->set_rules('item_id', 'UNIQUE ID', 'trim|required');
        $this->form_validation->set_rules('store_id', 'UNIQUE ID', 'trim|required');
        $this->form_validation->set_rules('stock_consumption_quantity', 'QTY', 'trim|required|is_natural_no_zero');
        $this->form_validation->set_rules('stock_consumption_unit', 'UNIT', 'trim|required');
        $this->form_validation->set_rules('stock_consumption_notes', 'NOTE', 'trim|required');
        if ($this->form_validation->run() == true) {
            $post['item_id'] = base64_decode($post['item_id']);
            $post['store_id'] = base64_decode($post['store_id']);
            $this->db->trans_begin();
            $prev_stock =  $this->SUR->get_row('item_id,current_stock', 'current_stock', ['item_id' => $post['item_id'], 'store_id' => $post['store_id']]);
            $item_unit = $this->SUR->get_row('unit', 'master_items', ['item_id' => $post['item_id']]);
            if ($prev_stock && ($prev_stock->current_stock) > 0) {
                $prev_stock = $prev_stock->current_stock;
            } else {
                $prev_stock = 0;
            }
            $moving_quantity = 0;
            if ($item_unit && !empty($item_unit->unit)) {
                $converting_units = $this->SUR->get_row('uc_formula', 'unit_conversion', ['uc_rep_unit_id' => $item_unit->unit, 'uc_unit_id' => $post['stock_consumption_unit']]);
                if ($converting_units && $converting_units->uc_formula) {
                    $mov = str_ireplace("x", $post['stock_consumption_quantity'], $converting_units->uc_formula);
                    $moving_quantity += eval('return ' . $mov . ';');
                }
            }
            if ($prev_stock < $moving_quantity) {
                $msg = array(
                    'status' => 0,
                    'message' => 'Entered quantity is not available in the stock.'
                );
            } else {
                $current_stock = array(
                    'item_id' => $post['item_id'],
                    //'current_stock' => $prev_stock - $data['stock_consumption_quantity'],
                    'current_stock' => $prev_stock - $moving_quantity,
                    'last_updated' => date("Y/m/d H:i:s")
                );

                $transaction_id = rand();

                $stock_info = array(
                    'str_stock_item_id' => $post['item_id'],
                    'store_id' => $post['store_id'],
                    'item_quantity' => $post['stock_consumption_quantity'],
                    'stock_item_unit' => $post['stock_consumption_unit'],
                    /*  'intent_number' => $post['stock_consumption_intent_num'], */
                    'notes' => $post['stock_consumption_notes'],
                    'stock_added_by' =>   $this->user,
                    'operation' => 2,
                    'current_stock' => $prev_stock,
                    //'current_balance' => $prev_stock - $post['stock_consumption_quantity'],
                    //'current_balance' => $prev_stock - $moving_quantity,
                    'transaction_id' => $transaction_id
                );
                $this->SUR->update_data("current_stock", $current_stock, ['item_id' => $post['item_id'], 'store_id' => $post['store_id']]);
                $this->SUR->insert_data("stock_info", $stock_info);
                $msg = array(
                    'status' => 1,
                    'message' => 'Stock consumption details saved successfully.'
                );
            }
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
            } else {
                $this->db->trans_commit();
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
    public function initiateStockTransfer()
    {
        $post = $this->input->post();
        $this->form_validation->set_rules('item_id', 'UNIQUE ID', 'trim|required');
        $this->form_validation->set_rules('store_id', 'UNIQUE ID', 'trim|required');
        $this->form_validation->set_rules('item_transfer_store_id', 'STORE', 'trim|required|is_natural_no_zero');
        $this->form_validation->set_rules('item_transfer_quantity', 'QTY', 'trim|required');
        $this->form_validation->set_rules('transfer_item_unit', 'UNIT', 'trim|required');
        $this->form_validation->set_rules('stock_transfer_notes', 'NOTE', 'trim|required');
        if ($this->form_validation->run() == true) {
            $post['item_id'] = base64_decode($post['item_id']);
            $post['store_id'] = base64_decode($post['store_id']);
            $this->db->trans_begin();
            $prev_stock =  $this->SUR->get_row('item_id,current_stock', 'current_stock', ['item_id' => $post['item_id'], 'store_id' => $post['store_id']]);
            $item_unit = $this->SUR->get_row('unit', 'master_items', ['item_id' => $post['item_id']]);
            if ($prev_stock && ($prev_stock->current_stock) > 0) {
                $prev_stock = $prev_stock->current_stock;
            } else {
                $prev_stock = 0;
            }
            $moving_quantity = 0;
            if ($item_unit && !empty($item_unit->unit)) {
                $converting_units = $this->SUR->get_row('uc_formula', 'unit_conversion', ['uc_rep_unit_id' => $item_unit->unit, 'uc_unit_id' => $post['transfer_item_unit']]);
                if ($converting_units && $converting_units->uc_formula) {
                    $mov = str_ireplace("x", $post['stock_consumption_quantity'], $converting_units->uc_formula);
                    $moving_quantity += eval('return ' . $mov . ';');
                }
            }
            $transaction_id = rand();
            $stock_info_out = array(
                'str_stock_item_id' => $post['item_id'],
                'store_id' => $post['store_id'],
                'item_quantity' => $post['item_transfer_quantity'],
                'stock_item_unit' => $post['transfer_item_unit'],
                'notes' => $post['stock_transfer_notes'],
                'stock_added_by' => $this->user,
                'operation' => 4,
                'current_stock' => $prev_stock,
                /*             * Unit Conversion* */
                //'current_balance' => $prev_stock - $post['item_transfer_quantity'],
                // 'current_balance' => $prev_stock - $moving_quantity,
                'transaction_id' => $transaction_id,
                'transaction_status' => 'Initiated'
            );

            $cur_stock =  $this->SUR->get_row('current_stock', 'current_stock', ['item_id' => $post['item_id'], 'store_id' => $post['item_transfer_store_id']]);
            if ($cur_stock && ($cur_stock->current_stock) > 0) {
                $cur_stock = $cur_stock->current_stock;
            } else {
                $cur_stock = 0;
            }

            $stock_info_in = array(
                'str_stock_item_id' => $post['item_id'],
                'store_id' => $post['item_transfer_store_id'],
                'item_quantity' => $post['item_transfer_quantity'],
                'stock_item_unit' => $post['transfer_item_unit'],
                'notes' => $post['stock_transfer_notes'],
                'stock_added_by' =>  $this->user,
                'operation' => 3,
                'current_stock' => $cur_stock,
                'transaction_id' => $transaction_id,
                /*             * *Unit Conversion* */
                //'current_balance' => $prev_stock - $data['item_transfer_quantity'],
                //'current_balance' => $prev_stock - $moving_quantity,
                'transaction_status' => 'Initiated'
            );
            $stock_transfer_ledger = array(
                'transfer_ledger_transaction_id' => $transaction_id,
                'transfer_from_store_id' => $post['store_id'],
                'transfer_to_store_id' => $post['item_transfer_store_id'],
                'quantity_transfered' => $post['item_transfer_quantity'],
                'transfer_ledger_unit' => $post['transfer_item_unit'],
                'status' => 'initiated',
                'transfer_ledger_item_id' => $post['item_id']
            );

            /* update stock master */
            $current_stock = array(
                /*             * *****Unit Conversion*** */
                // 'current_stock' => $prev_stock - $data['item_transfer_quantity'],
                'current_stock' => $prev_stock - $moving_quantity,
                'current_stock_unit' => $post['transfer_item_unit'],
                'last_updated' => date("Y/m/d H:i:s")
            );


            $this->SUR->insert_data("stock_info", $stock_info_out);
            $this->SUR->insert_data("stock_info", $stock_info_in);
            $this->SUR->insert_data("transfer_ledger", $stock_transfer_ledger);
            $this->SUR->update_data("current_stock", $current_stock, ['item_id' => $post['item_id'], 'store_id' => $post['store_id']]);

            $from_store =  $this->SUR->get_row('store_name', 'stores', ['store_id' => $post['store_id']]);
            if ($from_store) {
                $from_store = $from_store->store_name;
            }
            $logDetails = array(
                'module' => 'Store',
                'is_deleted' => '',
                'store_id' => $post['item_transfer_store_id'],
                'lab_id' => '', 'item_id' => $post['item_id'],
                'action_message' => 'Stock transferred ' . $post['item_transfer_quantity'] . ' from ' . $from_store . ' ',
                'operation' => 'initiateStockTransfer',
                'uidnr_admin' => $this->user,
                'log_activity_on' => date("Y-m-d H:i:s")
            );

            $this->SUR->insert_data("store_log", $logDetails);

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $msg = ["status" => 0, "message" => "STOCK DETAILS ADD"];
            } else {
                $this->db->trans_commit();
                $msg = ["status" => 1, "message" => "Stock Transferred Successfully."];
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
}
