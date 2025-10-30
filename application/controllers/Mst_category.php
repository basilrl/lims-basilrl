<?php

    // Controller class of mst category by kamal on 6th of june 2022
    
defined('BASEPATH') OR exit('No direct script access allowed');

class Mst_category extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Mst_category_model');
    }
    // index method of mst category on 6th of june 2022 by kamal 

    public function index()
    {
        $where = NULL;
        $search = NULL;

        $base_url = 'mst_category/index';
        $order = base64_decode($this->uri->segment('8'));
        // print_r($order); die;
        $sortby=$this->uri->segment('9');

        if ($this->uri->segment('3') != NULL && $this->uri->segment('3') != 'NULL') {
            $category_name = $this->uri->segment('3');
            $data['category_name'] =  $category_name;
            $base_url .= '/' . $category_name;
            $where['category_name'] = base64_decode($category_name);
        } else {
            $base_url .= '/NULL';
            $data['category_name'] = 'NULL';
            $where['category_name'] = 'NULL';
        }


        if ($this->uri->segment('4') != NULL && $this->uri->segment('4') != 'NULL') {
            $category_code = $this->uri->segment('4');
            $data['category_code'] =  $category_code;
            $base_url .= '/' . $category_code;
            $where['category_code'] = base64_decode($category_code);
        } else {
            $base_url .= '/NULL';
            $data['category_code'] = 'NULL';
            $where['category_code'] = 'NULL';
        }
        if ($this->uri->segment('5') != NULL && $this->uri->segment('5') != 'NULL') {
            $created_by = $this->uri->segment('5');
            $data['created_by'] =  $created_by;
            $base_url .= '/' . $created_by;
            $where['created_by'] = base64_decode($created_by);
        } else {
            $base_url .= '/NULL';
            $data['created_by'] = 'NULL';
            $where['created_by'] = 'NULL';
        }

        if ($this->uri->segment('6') != NULL && $this->uri->segment('6') != 'NULL') {
            $start_date = $this->uri->segment('6');
            $data['created_by'] =  $start_date;
            $base_url .= '/' . $start_date;
            $where['start_date'] = base64_decode($start_date);
            // echo "<pre>";
            // print_r( $where['start_date']); die;
        } else {
            $base_url .= '/NULL';
            $data['start_date'] = 'NULL';
            $where['start_date'] = 'NULL';
        }

        if ($this->uri->segment('7') != NULL && $this->uri->segment('7') != 'NULL') {
            $end_date = $this->uri->segment('7');
            $data['end_date'] =  $end_date;
            $base_url .= '/' . $end_date;
            $where['end_date'] = base64_decode($end_date);
        } else {
            $base_url .= '/NULL';
            $data['end_date'] = 'NULL';
            $where['end_date'] = 'NULL';
        }
        if ($order != NULL && $order != 'NULL') {
            $base_url .= '/' . $order;
        } else {
            $base_url .= '/NULL';
            $order = NULL;
            // echo "kamal"; die;
        }
        if ($sortby != NULL && $sortby != 'NULL') {
            $base_url .= '/' . $sortby;
        } else {
            $base_url .= '/NULL';
            $sortby = NULL;
        }
        // if()
            $total_row = $this->Mst_category_model->get_all_category_detail($where,null,null,true,null,null);
            
            // loading pagination from Mycontroller in core folder by kamal on 6th june 2022

            $config = $this->pagination($base_url, $total_row, 10, 10);
            $data["links"] = $config["links"];
            // echo $order; echo $sortby; die; 
            $data['mst_category'] = $this->Mst_category_model->get_all_category_detail($where,$config["per_page"], $config['page'],false,$sortby,$order);
            $data['created_by_name'] = $this->Mst_category_model->fetch_created_person();
            // echo "<pre>";_
            // echo $created_by_name; 
            // die;
            // loading view using mycontroller in core folder by kamal on 6th of june 2022

            // Result count by kamal on 9th of june 2022

            $page_no=$this->uri->segment('10');
            $start = (int)$page_no + 1;
            $end = (($data['mst_category']) ? count($data['mst_category']) : 0) + (($page_no) ? $page_no : 0);
            $data['result_count'] = "Showing " . $start . " - " . $end . " of " . $total_row . " Results";
            

            if ($order == NULL || $order == 'NULL') {
                $data['order'] = ($order) ? "DESC" : "ASC";
                // echo "if working";  print_r($data['order']);die;
            } else {
                $data['order'] = ($order == "ASC") ? "DESC" : "ASC";
                // echo "else working"; print_r($data['order']);die;s
            }
            // echo "<pre>";
            // print_r(base64_decode($order)); die;
            $this->load_view('mst_category/index', $data);
     
        
    }   

    // Add method of mst caategory by kamal on 6th of june 2022

    public function add() {
        if ($this->input->post('btnadd')) 
        {

            $this->form_validation->set_rules('name', 'Category Name', 'trim|required|is_unique[mst_category.category_name]');
            $this->form_validation->set_rules('code', 'Category Code', 'trim|required|is_unique[mst_category.category_code]');


        if($this->form_validation->run() == true)
            { 
                // echo "true";
                $arrData['category_name'] = $this->input->post('name'); 
                $arrData['category_code'] = $this->input->post('code');
                $arrData['created_by']=$this->session->userdata('user_data')->uidnr_admin;
                $arrData['created_on']= date("Y-m-d h:i:sa");
                $insert = $this->Mst_category_model->insert($arrData);
                
                if ($insert) {
                    $this->session->set_flashdata('success', 'You are added Successfully');
                    $this->session->flashdata('success');
                    redirect("Mst_category");
                }
                else
                {
                    $this->session->set_flashdata('error', 'Unable to load data TRY AGAIN!');
                    $this->session->flashdata('error');
                    redirect("Mst_category");
                }
            } 
            else
            {
                    $this->load_view('mst_category/add_category', null);
            }

        }
        $this->load_view('mst_category/add_category', null);
    }


    // Edit method of mst category by kamal on 6th of june 2022;

    public function edit($id) {
        $arrData['mst_category'] = $this->Mst_category_model->get_id_wise_category($id);
        if ($this->input->post('btnEdit')) {
            $this->form_validation->set_rules('name', 'Category Name', 'trim|required|callback_update_cat_name');
            $this->form_validation->set_rules('code', 'Category Code', 'trim|required|callback_update_cat_code');

            if($this->form_validation->run() == true)
            {

            $editData['category_name'] = $this->input->post('name');
            $editData['category_code'] = $this->input->post('code');
           
            $editData['created_by']=$this->session->userdata('user_data')->uidnr_admin;
            $editData['updated_on']=date("Y-m-d h:i:sa");
            $update = $this->Mst_category_model->update($editData, $id);
       
            if ($update) {
                $this->session->set_flashdata('success', 'Your Data is Updated Successfully');
                $this->session->flashdata('success');
                redirect("mst_category");
                }
            else{
                $this->session->set_flashdata('error', 'unable to Update Data TRY AGAIN...');
                $this->session->flashdata('error');
                redirect("Mst_category");
                }
                
            }
            else
            {
                $this->session->set_flashdata('warning', 'Duplicate Entry...');
                $this->session->flashdata('warning');
            }
        }
        $this->load_view('mst_category/edit_category', $arrData);
    }

    public function update_cat_name()
    {
        // echo "working main"; die;
        $update_form = $this->input->post();
        $check_fileds = array();
        $check_fileds['LOWER(category_name)'] = strtolower($update_form['name']);
        $check_fileds['category_id NOT IN ('.$update_form['id'].')'] =  NULL;
        if(!empty($update_form) && !empty($update_form['id']) ){
            // echo "working main1"; die;
            $check_in = $this->Mst_category_model->get_row('*', 'mst_category' , $check_fileds);
            if($check_in){
                // echo "working main"; die;
                $this->form_validation->set_message('update_cat_name', 'The {field} field can not be the same. "It Must be Unique!!"');
                return FALSE;
            }
            else
            {
                return TRUE;
            }
        }
        else{
            // echo "working main"; die;
            return false;
        }
    }
    public function update_cat_code()
    {
        $update_form = $this->input->post();
        $check_fileds = array();
        $check_fileds['LOWER(category_code)'] = strtolower($update_form['code']);
        $check_fileds['category_id NOT IN ('.$update_form['id'].')'] =  NULL;
        if(!empty($update_form) && !empty($update_form['id']) ){
            $check_in = $this->Mst_category_model->get_row('*', 'mst_category' , $check_fileds);
            if($check_in){
                // echo "working main code"; die;
                $this->form_validation->set_message('update_cat_code', 'The {field} field can not be the same. "It Must be Unique!!"');
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
}


