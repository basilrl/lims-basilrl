<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class RegulationProduct extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('RegulationProduct_model');
        $this->check_session();
        $checkUser = $this->session->userdata('user_data');
		$this->user = $checkUser->uidnr_admin;
    }

    public function index(){
        $where = NULL;
        $search = NULL;
        $sortby = NULL;
        $order = NULL;

        $base_url = 'RegulationProduct/index';
        
        if ($this->uri->segment('3') != NULL && $this->uri->segment('3') != 'NULL') {
            $title_product = $this->uri->segment('3');
            $data['title_product'] =  base64_decode($title_product);
            $base_url .= '/' . $title_product;
            $where['crp.reg_product_id'] = base64_decode($title_product);
        } else {
            $base_url .= '/NULL';
            $data['title_product'] = NULL;
        }

        if ($this->uri->segment('4') != NULL && $this->uri->segment('4') != 'NULL') {
            $created_pesron = $this->uri->segment('4');
            $data['created_pesron'] =  base64_decode($created_pesron);
            $base_url .= '/' . $created_pesron;
            $where['ap.uidnr_admin'] = base64_decode($created_pesron);
        } else {
            $base_url .= '/NULL';
            $data['created_pesron'] = NULL;
        }

        if ($this->uri->segment('5') != NULL && $this->uri->segment('5') != 'NULL') {
            $status = $this->uri->segment('5');
            $data['status'] =  base64_decode($status);
            $base_url .= '/' . $status;
            $where['crp.status'] = base64_decode($status);
        } else {
            $base_url .= '/NULL';
            $data['status'] = NULL;
        }

        if ($this->uri->segment('6') != NULL && $this->uri->segment('6') != 'NULL') {
            $search = $this->uri->segment('6');
            $data['search'] =  base64_decode($search);
            $base_url .= '/' . $search;
            $search = base64_decode($search);
        } else {
            $base_url .= '/NULL';
            $data['search'] = NULL;
        }

        if ($this->uri->segment('7') != NULL && $this->uri->segment('7') != 'NULL') {
            $sortby = $this->uri->segment('7');
            $base_url .= '/' . $sortby;
        } else {
            $base_url .= '/NULL';
        }

        if ($this->uri->segment('8') != NULL && $this->uri->segment('8') != 'NULL') {
            $order = $this->uri->segment('8');
            $base_url .= '/' . $order;
        } else {
            $base_url .= '/NULL';
        }

        $total_row = $this->RegulationProduct_model->fetch_regpro_list(NULL, NULL, $search, NULL, NULL, $where, '1');
        $config = $this->pagination($base_url, $total_row, 10, 9);
        $data["links"] = $config["links"];
        $data['pro_titles'] = $this->RegulationProduct_model->fetch_pro_title();
        $data['created_by_name'] = $this->RegulationProduct_model->fetch_created_person();
        $data['notified_bodies'] = $this->RegulationProduct_model->fetch_notified_body();
        $data['regpro_list'] = $this->RegulationProduct_model->fetch_regpro_list($config["per_page"], $config['page'], $search, $sortby, $order, $where);
        $page_no = $this->uri->segment('9');
        $start = (int)$page_no + 1;
        $end = (($data['regpro_list']) ? count($data['regpro_list']) : 0) + (($page_no) ? $page_no : 0);
        $data['result_count'] = "Showing " . $start . " - " . $end . " of " . $total_row . " Results";
        if ($order == NULL || $order == 'NULL') {
            $data['order'] = ($order) ? "DESC" : "ASC";
        } else {
            $data['order'] = ($order == "ASC") ? "DESC" : "ASC";
        }
        $this->load_view('regulationproduct/manage_regulation_product', $data);
    }

    public function add_regpro() {
        $this->form_validation->set_rules('reg_product_title', 'Porduct title', 'required|is_unique[cps_regulations_products.reg_product_title]');

        if ($this->form_validation->run() == false){
            $data = array(
                'status'=>0,
                'msg'=>'fill all required fields',
                'errors' => $this->form_validation->error_array()
            );
        }
        else{
            $checkUser = (array) $this->session->userdata('user_data');
            $fill_data = $this->input->post();
            $notify_body = implode("," , $fill_data['notified_body_id']);
            if (!empty($fill_data)) {
                $inserted_data = array('reg_product_title' => $fill_data['reg_product_title'], 'notified_body_id' => $notify_body, 'status' => $fill_data['status'], 'created_by' => $checkUser['uidnr_admin'], 'created_date' =>date('y-m-d'));
                $status = $this->RegulationProduct_model->insert_regpro($inserted_data);
                if ($status) {
                    
                    $log= $this->user_log_update($status,'REGULATION PRODUCT ADDED WITH TITLE '.$fill_data['reg_product_title'],'add_regpro');
                    if($log){
                        $this->session->set_flashdata('success', 'Regulation Product added successfully..');
                        $data = array(
                            'status' => 1,
                            'msg'=>'Regulation Product added successfully..'
                        );
                    }
                    else{
                        $data = array(
                            'status' => 0,
                            'msg'=>'error in generating log'
                        );
                    }
                    
                } else {
                   

                    $data = array(
                        'status'=>0,
                        'msg'=>'Error in adding Regulation Product !!!'
                    );
                   
                }
            }
        }
        
        echo json_encode($data);
    }

    public function delete_regpro() {
        if (!empty($this->input->get())) {
            $id = $this->input->get('reg_product_id');

            $status = $this->RegulationProduct_model->delete_regpro($this->input->get());
            if ($status) {
                    $log = $this->user_log_update($id,'REGULATION PRODUCT DELETED','delete_regpro');
                    if($log){
                        $this->session->set_flashdata('success', 'Regulation Product Deleted successfully...');
                        redirect(base_url() . 'RegulationProduct/');
                } else {
                    $this->session->set_flashdata('error', 'Error in deleting Regulation Product !!!');
                    redirect(base_url() . 'RegulationProduct/');
                }
               
                
            } else {
                $this->session->set_flashdata('error', 'Error in deleting Regulation Product !!!');
                redirect(base_url() . 'RegulationProduct/');
            }
        }
    }

    public function fetch_regpro_for_edit() {
        if (!empty($this->input->post())) {
            $fetch_data = $this->RegulationProduct_model->fetch_regpro_for_edit($this->input->post());
            if ($fetch_data) {
                if(!empty($fetch_data->notified_body_id)){
                    (strpos($fetch_data->notified_body_id, ","))?explode(',',$fetch_data->notified_body_id):$fetch_data->notified_body_id;
                }
                echo json_encode($fetch_data);
            } else {
                echo false;
            }
        }
    }

    public function update_regpro() {
        if (!empty($this->input->post())) {
            $data = $this->input->post();

            $status = $this->RegulationProduct_model->update_regpro($this->input->post());
            if ($status) {

                $log= $this->user_log_update($data['reg_product_id'],'REGULATION PRODUCT UPDATED WITH TITLE '.$data['reg_product_title'],'update_regpro');
               
                if($log){
                    $this->session->set_flashdata('success', 'Regulation Product Updated Successfully..');
                    $data = array(
                        'status' => 1,
                        'msg'=>'Regulation Product Updated Successfully..'
                    );
                }
                else{
                    $data = array(
                        'status' => 0,
                        'msg'=>'error in generating log'
                    );
                }

               
               
            } else {
                $this->session->set_flashdata('error', 'Error in updating Regulation Product !!!');
                $data = array(
                    'status'=>0,
                    'msg'=>'Error in updating Regulation Product !!!'
                    
                );
            }
        }
        echo json_encode($data);
    }

    public function regpro_status() {
        if (!empty($this->input->post())) {
            $id = $this->input->post('reg_product_id');
            $status = $this->RegulationProduct_model->update_regpro_status($this->input->post());
            if ($status) {

                $log= $this->user_log_update($id,'REGULATION PRODUCT STATUS UPDATED','regpro_status');
                if($log){
                    $this->session->set_flashdata('success', 'Regulation Product Status Updated sucessfully');
                    echo json_encode(array('msg' => 'upadte status'));
                }
                else{
                    $this->session->set_flashdata('error', 'Error in generating log');
                    echo json_encode(array('msg' => 'not upadte status'));
                }
               
            } else {
                $this->session->set_flashdata('error', 'Error in Updating Regulation Product Status');
                echo json_encode(array('msg' => 'not upadte status'));
            }
        }
    }

    public function get_log_data()
	{
		$id = $this->input->post('id');
		$data = $this->RegulationProduct_model->get_log_data($id);
		echo json_encode($data);
	}


	public function user_log_update($id,$text,$action){
		$data = array();
		$data['source_module'] = 'RegulationProduct';
		$data['record_id'] = $id;
		$data['created_on'] = date("Y-m-d h:i:s");
		$data['created_by'] = $this->user;
		$data['action_taken'] = $action;
		$data['text'] = $text;

		$result = $this->RegulationProduct_model->insert_data('user_log_history',$data);
		if($result){
			return true;
		}
		else{
			return false;
		}

}


}
