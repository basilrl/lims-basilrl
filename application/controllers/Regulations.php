<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Regulations extends MY_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Regulations_model', 'RM');
		$this->check_session();
		$checkUser = $this->session->userdata('user_data');
		$this->user = $checkUser->uidnr_admin;
		$this->form_validation->set_error_delimiters('<div class="error" style="color:red">', '</div>');
	}

	public function index()
	{
        $where=NULL;
        $search = NULL;
        $sortby=NULL;
        $order=NULL;

		$base_url = 'Regulations/index';


        if ($this->uri->segment('3') != NULL && $this->uri->segment('3') != 'NULL') {
            $country_id = $this->uri->segment('3');
			$data['country_id'] =  base64_decode($country_id);
			$base_url .= '/' . $country_id;
			$where['ct.country_id'] = base64_decode($country_id);

		} else {
			$base_url .= '/NULL';
			$data['country_id'] = NULL;
		}
        
        if ($this->uri->segment('4') != NULL && $this->uri->segment('4') != 'NULL') {
            $category_id = $this->uri->segment('4');
			$data['category_id'] =  base64_decode($category_id);
			$base_url .= '/' . $category_id;
			$where['md.division_id'] = base64_decode($category_id);
		} else {
            $base_url .= '/NULL';
			$data['category_id'] = NULL;
		}
       
        if ($this->uri->segment('5') != NULL && $this->uri->segment('5') != 'NULL') {
            $notified_body_id = $this->uri->segment('5');
			$data['notified_body_id'] =  base64_decode($notified_body_id);
			$base_url .= '/' . $notified_body_id;
			$where['nb.notified_body_id'] = base64_decode($notified_body_id);
		} else {
            $base_url .= '/NULL';
			$data['notified_body_id'] = NULL;
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
			$base_url .= '/' . base64_decode($sortby);
		} else {
			$base_url .= '/NULL';
		}

        if ($this->uri->segment('8') != NULL && $this->uri->segment('8') != 'NULL') {
            $order = $this->uri->segment('8');
			$base_url .= '/' . base64_decode($order);
		} else {
			$base_url .= '/NULL';
		}


		$total_row = $this->RM->get_regulations_list(NULL, NULL, $search, NULL, NULL, $where, '1');
		$config = $this->pagination($base_url, $total_row, 10, 9);

		$data["links"] = $config["links"];
        
		$data['regulations_list'] = $this->RM->get_regulations_list($config["per_page"], $config['page'], $search, $sortby, $order, $where);
        $page_no = $this->uri->segment('9');
		$start = (int)$page_no + 1;
		$end = (($data['regulations_list']) ? count($data['regulations_list']) : 0) + (($page_no) ? $page_no : 0);
		$data['result_count'] = "Showing " . $start . " - " . $end . " of " . $total_row . " Results";


		if ($order == NULL || $order == 'NULL') {
			$data['order'] = ($order) ? "DESC" : "ASC";
		} else {
			$data['order'] = ($order == "ASC") ? "DESC" : "ASC";
		}

		$this->load_view('regulations/regulations_listing', $data);
	}


    public function get_dropdown_by_ajax(){
        $select = $this->input->post('select');
        $from = $this->input->post('from');
        $where = $this->input->post('where');

        $data = $this->RM->get_result($select,$from,$where);
        echo json_encode($data);
    }

    public function add_regulations(){
        $data = $this->input->post();
        $this->form_validation->set_rules('title','Title','required');
        $this->form_validation->set_rules('country_id','Country','required');
        $this->form_validation->set_rules('division_id','Category','required');
        $this->form_validation->set_rules('notified_body_id','Notified body','required');
        $this->form_validation->set_rules('notification_date','Notification date','required');
        $this->form_validation->set_rules('tat_description','Tat description','required');
    
        if($this->form_validation->run()==TRUE){
            if($_FILES['file_name']['name']!=""){
                $data['file_name'] = $_FILES['file_name']['name'];
                $file = $this->multiple_upload_image($_FILES['file_name']);
                if($file){
                    $data['file_path'] = $file['aws_path'];
                    $data['created_by'] = $this->user;
                    $result = $this->RM->insert_data_regulations($data);
                    if($result){

    
                        $msg = array(
                            'status'=>1,
                            'msg'=>'REGULATION ADDED SUCCESSFULLY'
                        );
                        $this->session->set_flashdata('success','REGULATION ADDED SUCCESSFULLY');
                    } 
                    else{
                        $msg = array(
                            'status'=>0,
                            'msg'=>'ERROR IN ADDING REGULATION'
                        );
                    }   
                }
                else{
                    $msg = array(
                        'status'=>0,
                        'msg'=>'ERROR IN UPLOADING FILE'
                    );
               }
            }
            else{
                $msg = array(
                    'status'=>0,
                    'msg'=>'PLEASE UPLOAD FILE'
                );
            }
            
        }
        else{
            $msg = array(
				'status' => 0,
				'msg' => 'Please fill all required details.',
				'errors' => $this->form_validation->error_array()
			);
        }
       
        
        echo json_encode($msg);
        
        
    }

    public function update_regulations(){
        $data = $this->input->post();
        $regulation_id = $data['regulations_id'];
        $this->form_validation->set_rules('title','Title','required');
        $this->form_validation->set_rules('country_id','Country','required');
        $this->form_validation->set_rules('division_id','Category','required');
        $this->form_validation->set_rules('notified_body_id','Notified body','required');
        $this->form_validation->set_rules('notification_date','Notification date','required');
        $this->form_validation->set_rules('tat_description','Tat description','required');
        $data['created_by'] = $this->user;

        if($this->form_validation->run()==TRUE){
            if($_FILES['file_name']['name']!=""){
                $data['file_name'] = $_FILES['file_name']['name'];
                $file = $this->multiple_upload_image($_FILES['file_name']);
                if($file){
                    $data['file_path'] = $file['aws_path'];
                }
            }
            $result = $this->RM->update_data_regulations($data,$regulation_id);
            if($result){
                $msg = array(
                    'status'=>1,
                    'msg'=>'REGULATION UPDATED SUCCESSFULLY'
                );
                $this->session->set_flashdata('success','REGULATION UPDATED SUCCESSFULLY');
            } 
            else{
                $msg = array(
                    'status'=>0,
                    'msg'=>'ERROR IN UPDATING REGULATION'
                );
            } 
        }
        else{
            $msg = array(
				'status' => 0,
				'msg' => 'Please fill all required details.',
				'errors' => $this->form_validation->error_array()
			);
        }
       
        
        echo json_encode($msg);
        
        
    }
    


    public function edit_regulations(){
        $regulation_id = $this->input->post('regulation_id');
        $data = $this->RM->get_regulation_data($regulation_id);
        echo json_encode($data);
    }

    public function download_file_regulations($path)
	{
      
		$path = base64_decode($path);
		$this->load->helper('download');
		$pdf_path    =   file_get_contents($path);
		$pdf_name    =   basename($path);
		force_download($pdf_name, $pdf_path);
	}

    public function get_user_log_data(){
        $regulation_id = $this->input->post('regulation_id');
        $data = $this->RM->get_user_logData($regulation_id);
        echo json_encode($data);
    }

    public function add_changes(){
        $this->form_validation->set_rules('notification_title','title','required');
        $this->form_validation->set_rules('notification_description','description','required');
       
        if($this->form_validation->run()==TRUE){
            $data = array();
            $data['notification_description'] = html_entity_decode($this->input->post('notification_description'));
            $data['notification_title'] = $this->input->post('notification_title');
            $data['regulation_id']=$this->input->post('regulation_id');
            $data['status']='1';
            $data['created_by'] = $this->user;
            $data['created_date'] = date("Y-m-d H:i:s");
            $result = $this->RM->insert_data('cps_regulation_notification',$data);
            if($result){
                $msg = array(
                    'status'=>1,
                    'msg'=>'NOTIFICATION ADDED SUCCESSFULLY'
                );
                $this->session->set_flashdata('success','NOTIFICATION ADDED SUCCESSFULLY');
            }
            else{
                $msg = array(
                    'status'=>0,
                    'msg'=>'ERROR IN ADDING'
                );
            }
        }
        else{
            $msg = array(
				'status' => 0,
				'msg' => 'Please fill all required details.',
				'errors' => $this->form_validation->error_array()
			);
        }

        echo json_encode($msg);
    }

    public function get_log_data()
	{
		$id = $this->input->post('id');
		$data = $this->RM->get_log_data($id);
		echo json_encode($data);
	}


	public function user_log_update($id,$text,$action){
		$data = array();
		$data['source_module'] = 'Regulations';
		$data['record_id'] = $id;
		$data['created_on'] = date("Y-m-d h:i:s");
		$data['created_by'] = $this->user;
		$data['action_taken'] = $action;
		$data['text'] = $text;

		$result = $this->RM->insert_data('user_log_history',$data);
		if($result){
			return true;
		}
		else{
			return false;
		}

}

}
