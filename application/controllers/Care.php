<!-- APPLICATION CARE INSTRUCTION CONTROLLER BY KAMAL  ON 6TH JUNE 2022 --> 

<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Care extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Care_model');  
        // $sn=66;  
    }
    // INDEX FUNCTION FOR LISTING OF APPLICATION CAEE INSTRUCTION BY KAMAL  ON 6TH JUNE 2022
	public function index()
	{
        $where = NULL;
        $search = NULL;

        $base_url = 'Care/index';
        $order = ($this->uri->segment('9'));
        $sortby=$this->uri->segment('10');

        if ($this->uri->segment('3') != NULL && $this->uri->segment('3') != 'NULL') {
            $instruction_name = $this->uri->segment('3');
            $data['instruction_name'] =  $instruction_name;
            $base_url .= '/' . $instruction_name;
            $where['instruction_name'] = base64_decode($instruction_name);
        } else {
            $base_url .= '/NULL';
            $data['instruction_name'] = 'NULL';
            $where['instruction_name'] = 'NULL';
        }


        if ($this->uri->segment('4') != NULL && $this->uri->segment('4') != 'NULL') {
            $instruction_type = $this->uri->segment('4');
            $data['instruction_type'] =  $instruction_type;
            $base_url .= '/' . $instruction_type;
            $where['instruction_type'] = base64_decode($instruction_type);
        } else {
            $base_url .= '/NULL';
            $data['instruction_type'] = 'NULL';
            $where['instruction_type'] = 'NULL';
        }


        if ($this->uri->segment('5') != NULL && $this->uri->segment('5') != 'NULL') {
            $care_wording = $this->uri->segment('5');
            $data['care_wording'] =  $care_wording;
            $base_url .= '/' . $care_wording;
            $where['care_wording'] = base64_decode($care_wording);
        } else {
            $base_url .= '/NULL';
            $data['care_wording'] = 'NULL';
            $where['care_wording'] = 'NULL';
        }
        if ($this->uri->segment('6') != NULL && $this->uri->segment('6') != 'NULL') {
            $created_by = $this->uri->segment('6');
            $data['created_by'] =  $created_by;
            $base_url .= '/' . $created_by;
            $where['created_by'] = base64_decode($created_by);
        } else {
            $base_url .= '/NULL';
            $data['created_by'] = 'NULL';
            $where['created_by'] = 'NULL';
        }

        if ($this->uri->segment('7') != NULL && $this->uri->segment('7') != 'NULL') {
            $start_date = $this->uri->segment('7');
            $data['start_date'] =  $start_date;
            $base_url .= '/' . $start_date;
            $where['start_date'] = base64_decode($start_date);
        } else {
            $base_url .= '/NULL';
            $data['start_date'] = 'NULL';
            $where['start_date'] = 'NULL';
        }

        if ($this->uri->segment('8') != NULL && $this->uri->segment('8') != 'NULL') {
            $end_date = $this->uri->segment('8');
            $data['end_date'] =  $end_date;
            $base_url .= '/' . $end_date;
            $where['end_date'] = base64_decode($end_date);
        } else {
            $base_url .= '/NULL';
            $data['end_date'] = 'NULL';
            $where['end_date'] = 'NULL';
        }


        if ($order != NULL && $order != 'NULL') {
            $base_url .= '/' . ( $order);
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

            $total_row = $this->Care_model->get_all_instruction_detail($where,null,null,true,null,null);
            // print_r(($order)); die;
            $config = $this->pagination($base_url, $total_row, 10, 11);
            $data["links"] = $config["links"];
        
            $data['application_care_instruction'] = $this->Care_model->get_all_instruction_detail($where,$config["per_page"], $config['page'],false,$sortby,base64_decode( $order));
            
            
            $data['created_by_name'] = $this->Care_model->fetch_created_person();
            $page_no=$this->uri->segment('11');
            $start = (int)$page_no + 1;
            $end = (($data['application_care_instruction']) ? count($data['application_care_instruction']) : 0) + (($page_no) ? $page_no : 0);
            $data['result_count'] = "Showing " . $start . " - " . $end . " of " . $total_row . " Results";
            if ($order == NULL || $order == 'NULL') {
                $data['order'] =base64_decode( $order) ? "DESC" : "ASC"; 
            } else {
            // print_r(base64_decode( $order));

                $data['order'] = (base64_decode( $order) == "ASC") ? "DESC" : "ASC";
            }
            // if(base64_decode($order)=='ASC')
            // {
            //     $data['order']="DESC"
            // }
            // else if()
            // else
            // {
            //     $data['order']="ASC";
            // }
            $this->load_view('Care/index', $data);
     
	}


    // ADD FUNCTION FOR APPLICATION CARE INSTRUCTION BY KAMAL  ON 6TH JUNE 2022
    public function add() {
        if ($this->input->post('btnadd')) {
            
            $this->form_validation->set_rules('name1', 'Instruction Name', 'trim|is_unique[application_care_instruction.instruction_name]');

            if($this->form_validation->run() == true){


                $arrData['instruction_name'] = $this->input->post('name1');
            $arrData['instruction_type'] = $this->input->post('type');
            $arrData['care_wording'] = $this->input->post('wording');
            $image_Name=$this->upload_instruction_img($_FILES);
            
            $formal_Path=send_Image_Database($image_Name[0]);
            $arrData['instruction_image']=$formal_Path;
            $arrData['created_by'] = $this->session->userdata('user_data')->uidnr_admin;
            $arrData['priority_order'] = $this->input->post('order');
            $arrData['created_on']=date("Y-m-d h:i:sa");

            $insert = $this->Care_model->insert($arrData);
           
            if ($insert) {
                $this->session->set_flashdata('success', 'You are added Successfully');
                $this->session->flashdata('success');
                redirect("care");
            }
            else
                {
                $this->session->set_flashdata('message_name', 'Unable to load data TRY AGAIN!');
                $this->session->flashdata('error');
                redirect("care");
                }
            }
            else
            {
                // $this->load_view('care/Add_care', null);
            }
        }
        $this->load_view('Care/Add_care', null);
    }

    // EDIT FUNCTION FOR APPLICATION CARE INSTRUCTION BY KAMAL  ON 6TH JUNE 2022
    public function edit($id) {
        $arrData['application_care_instruction'] = $this->Care_model->get_id_wise_instruction($id);
        if ($this->input->post('btnEdit')) {
           
            $this->form_validation->set_rules('name', 'Instruction Name', 'trim|callback_update_inst_name');

            if($this->form_validation->run() == true)
            {
            $editData['instruction_name'] = $this->input->post('name');
            $editData['instruction_type'] = $this->input->post('type');
            $editData['care_wording'] = $this->input->post('wording');
            if(!empty($_FILES['image']['name']))
            {  
                $image_Name=$this->upload_instruction_img($_FILES);
                $formal_Path=send_Image_Database($image_Name[0]);
                $editData['instruction_image']=$formal_Path;
            }
            $editData['created_on']=date("Y-m-d h:i:sa");
            $editData['created_by'] = $this->session->userdata('user_data')->uidnr_admin;
            $editData['priority_order'] = $this->input->post('order');
           
            $update = $this->Care_model->update($editData, $id);
            
            if ($update) {
                $this->session->set_flashdata('success', 'YOur Data is Updated Successfully');
                $this->session->flashdata('success');
                redirect("care");
            }
            else{
                $this->session->set_flashdata('error', 'unable to Update Data TRY AGAIN...');
                $this->session->flashdata('error');
                redirect("care");
            }
        }
        else
        {
            $this->session->set_flashdata('warning', 'Duplicate Entry...');
            $this->session->flashdata('warning');
        }
        }
        
        $this->load_view('care/Edit_care', $arrData);
    }
    // Call back method for edit by kamal
    public function update_inst_name()
    {
        $update_form = $this->input->post();
        $check_fileds = array();
        $check_fileds['LOWER(instruction_name)'] = strtolower($update_form['name']);
        $check_fileds['instruction_id NOT IN ('.$update_form['id'].')'] =  NULL;
        if(!empty($update_form) && !empty($update_form['id']) ){
            $check_in = $this->Care_model->get_row('*', 'application_care_instruction' , $check_fileds);
            if($check_in){
                $this->form_validation->set_message('update_inst_name', 'The {field} field cannot be the same. "It Must be Unique!!"');
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










