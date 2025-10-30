<?php

    // Controller class of Quote Contact Details by kamal on 14th of june 2022
    
defined('BASEPATH') OR exit('No direct script access allowed');

class Quote_details extends MY_Controller 
{
    function __construct() {
        parent::__construct();
        $this->load->model('Quote_details_model','eq');

    }
    public function index()
    {
        $where = NULL;
        $search = NULL;
        $base_url="Quote_details/index";

        $order =$this->uri->segment('8');
        $sortby=$this->uri->segment('9');

        if ($this->uri->segment('3') != NULL && $this->uri->segment('3') != 'NULL') {
            $division = $this->uri->segment('3');
            $arrData['division'] =  $division;
            $base_url .= '/' . $division;
            $where['division'] = base64_decode($division);
        } else {
            $base_url .= '/NULL';
            $arrData['division'] = 'NULL';
            $where['division'] = 'NULL';
        }


        if ($this->uri->segment('4') != NULL && $this->uri->segment('4') != 'NULL') {
            $status = $this->uri->segment('4');
            $arrData['status'] =  $status;
            $base_url .= '/' . $status;
            $where['status'] = base64_decode($status);
        } else {
            $base_url .= '/NULL';
            $arrData['status'] = 'NULL';
            $where['status'] = 'NULL';
        }
        // echo $where['status']; die;
        if ($this->uri->segment('5') != NULL && $this->uri->segment('5') != 'NULL') {
            $created_by = $this->uri->segment('5');
            $arrData['created_by'] =  $created_by;
            $base_url .= '/' . $created_by;
            $where['created_by'] = base64_decode($created_by);
        } else {
            $base_url .= '/NULL';
            $arrData['created_by'] = 'NULL';
            $where['created_by'] = 'NULL';
        }

        if ($this->uri->segment('6') != NULL && $this->uri->segment('6') != 'NULL') {
            $start_date = $this->uri->segment('6');
            $arrData['start_date'] =  $start_date;
            $base_url .= '/' . $start_date;
            $where['start_date'] = base64_decode($start_date);
            // echo "<pre>";
            // print_r( $where['start_date']); die;
        } else {
            $base_url .= '/NULL';
            $arrData['start_date'] = 'NULL';
            $where['start_date'] = 'NULL';
        }

        if ($this->uri->segment('7') != NULL && $this->uri->segment('7') != 'NULL') {
            $end_date = $this->uri->segment('7');
            $arrData['end_date'] =  $end_date;
            $base_url .= '/' . $end_date;
            $where['end_date'] = base64_decode($end_date);
        } else {
            $base_url .= '/NULL';
            $arrData['end_date'] = 'NULL';
            $where['end_date'] = 'NULL';
        }


        if ($order != NULL && $order != 'NULL') {
            $base_url .= '/' . $order;
        } else {
            $base_url .= '/NULL';
            $order = NULL;
        }
        if ($sortby != NULL && $sortby != 'NULL') {
            $base_url .= '/' . $sortby;
        } else {
            $base_url .= '/NULL';
            $sortby = NULL;
        }


        $arrData['division_name']=$this->eq->fetch_division_name();
        $arrData['created_by_name'] = $this->eq->fetch_created_person();
        $total_row = $this->eq->get_All_Details($where,null,null,true,null,null);
            // $total_row=8;
            // loading pagination from Mycontroller in core folder by kamal on 6th june 2022

            $config = $this->pagination($base_url, $total_row, 10, 10);
            $arrData["links"] = $config["links"];


        $arrData['Quote_contact_details']=$this->eq->get_All_Details($where,$config['per_page'],$config['page'],false,$sortby,$order);
        $page_no=$this->uri->segment('10');
        $start = (int)$page_no + 1;
        $end = (($arrData['Quote_contact_details']) ? count($arrData['Quote_contact_details']) : 0) + (($page_no) ? $page_no : 0);
        
        $arrData['result_count'] = "Showing " . $start . " - " . $end . " of " . $total_row . " Results";
        
        if ($order == NULL || $order == 'NULL') {
            $arrData['order'] =    base64_decode( $order) ? "DESC" : "ASC"; 
        } else {

            $arrData['order'] = (base64_decode( $order) == "ASC") ? "DESC" : "ASC";
        }

        $this->load_view("quote_details/index",$arrData);
    }

    public function add()
    {
        $Data['division_name']=$this->eq->fetch_division_name();

        if ($this->input->post('btnadd')) 
        {
            $this->form_validation->set_rules('division', 'Division', 'trim|required');
            $this->form_validation->set_rules('detail', 'Contact Person Detail', 'trim|required');
            $this->form_validation->set_rules('status', 'Status', 'trim|required');

            if($this->form_validation->run() == true)
            { 
                $arrData['division'] = $this->input->post('division');
                $arrData['contact_person_details'] = $this->input->post('detail');
                $arrData['status'] = $this->input->post('status');
                $arrData['created_by']=$this->session->userdata('user_data')->uidnr_admin;
                $arrData['created_on']= date("Y-m-d h:i:s");
                $insert = $this->eq->insert_details($arrData);

                if ($insert) {
                    $this->session->set_flashdata('success', 'You are added Successfully');
                    $this->session->flashdata('success');
                    redirect("Quote_details");
                }
                else
                {
                    $this->session->set_flashdata('error', 'Unable to load data TRY AGAIN!');
                    $this->session->flashdata('error');
                    redirect("Quote_details");
                }
            } 
            else
            {
                    // $this->load_view('Quote_details/add', null);
            }

        }
        $this->load_view('quote_details/add', $Data);
    }


    public function edit($details_id)
    {
        $Data['division_name']=$this->eq->fetch_division_name();
        $Data['quote_contact_details'] = $this->eq->get_division_by_details($details_id);
        // echo "<pre>";
        // print_r($Data['quote_contact_details']); die;
        if ($this->input->post('btnedit')) {
            
            $this->form_validation->set_rules('division', 'Division', 'trim|required');
            $this->form_validation->set_rules('detail', 'Contact Person Detail', 'trim|required');
            $this->form_validation->set_rules('status', 'Status', 'trim|required');

            if($this->form_validation->run() == true)
            {
                $editData['division'] = $this->input->post('division');
                $editData['contact_person_details'] =   $this->input->post('detail');
                $editData['status'] = $this->input->post('status');

                $update=$this->eq->update_details($editData,$details_id);
                if ($update) {
                    $this->session->set_flashdata('success', 'Your Data is Updated Successfully');
                    $this->session->flashdata('success');
                    redirect("Quote_details");
                    }
                else{
                    $this->session->set_flashdata('error', 'unable to Update Data TRY AGAIN...');
                    $this->session->flashdata('error');
                    redirect("Quote_details");
                    }

            }
            else
            {
            }

        }
        $this->load_view("quote_details/edit",$Data);

    }

}
?>