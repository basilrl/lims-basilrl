<?php
defined('BASEPATH') or exit('No direct script access allowed');

class To_do_list extends MY_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('To_do_list_model', 'TDL');
		$this->check_session();
		$checkUser = $this->session->userdata('user_data');
		$this->user = $checkUser->uidnr_admin;
		$this->form_validation->set_error_delimiters('<div class="error" style="color:red">', '</div>');
    
	}

	public function index(){
		$this->load_view('to_do_list/to_do_list.php');
	}

	public function to_do_listing($search=NULL,$type=NULL,$customer_id=NULL,$page=0)
	{
		$where ="";
		if((!empty($type) && $type!='NULL') && (!empty($customer_id) && $customer_id!='NULL')){
			$where = 'WHERE com="'.$type.'" and customer_id='.$customer_id;;
		} elseif (!empty($type) && $type!='NULL'){
			$where = 'WHERE com="'.$type.'" ';
		} elseif (!empty($customer_id) && $customer_id!='NULL'){
			$where = 'WHERE customer_id='.$customer_id;
		}
	
		
        if (!empty($search) && $search != 'NULL') {
            $search = base64_decode($search);
        } else {
            $search = NULL;
        }
        $per_page = 5;
        if ($page != 0) {
            $page = ($page - 1) * $per_page;
        }
        $total_row = $this->TDL->get_to_do_list(NULL, NULL, $search, $where, '1',$this->user);
		
		$config = array();
        $config['base_url'] = base_url('To_do_list/index');
        $config['use_page_numbers'] = TRUE;
        $config['total_rows'] = $total_row;
        $config['per_page'] = $per_page;
		$config["uri_segment"] = '6';
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
		$data["page"] = ($this->uri->segment('6')) ? $this->uri->segment('6') : 0;
        $this->pagination->initialize($config);

		
        $data['links'] =  $this->pagination->create_links();
        $result = $this->TDL->get_to_do_list($per_page, $page, $search,$where,NULL,$this->user);

		$start = (int)$page + 1;
        $end = (($result) ? count($result) : 0) + (($page) ? $page : 0);
        $data['result_count'] = "Showing " . $start . " - " . $end . " of " . $total_row . " Results";
        $html = '';
	
        if ($result) {
            foreach ($result as $value) {
                $page++;
                $html .= '<tr>';
                $html .= '<th scope="col">' . $page . '</th>';
                $html .= '<td>' . $value->communication_name . '</td>';
                $html .= '<td>' . $value->customer_name . '</td>';
				$html .= '<td>' . $value->closing_date . '</td>';
			

					$target = '#add_communication';
				
				
			if(exist_val('To_do_list/insert_new_communication',$this->session->userdata('permission')) && exist_val('To_do_list/insert_new_opportunity',$this->session->userdata('permission'))){ 

				$html .= '<td><button type="button" class="btn btn-sm btn-primary add_opp_com" data-toggle="modal" data-target='.$target.' data-type='.$value->com.' data-id='.$value->id.' title="ADD CHANGE"><span> <i class="fa fa-plus"> </i></span></button></td>';
			}
                $html .= '</tr>';
            }
        } else {
            $html .= '<tr><td colspan="6"><h4>NO RECORD FOUND</h4></td></tr>';
        }
        $data['result'] = $html;
        echo json_encode($data);
    }


	public function get_customer(){
		$data = $this->TDL->get_customer();
		echo json_encode($data);
	}

	public function fetch_details(){
		$type = $this->input->post('type');
		$id = $this->input->post('id');
		if($type=='com'){
			$data = $this->TDL->fetch_com_data($id);
		}
		if($type=='opp'){
			$data = $this->TDL->fetch_opp_data($id);
		}
		echo json_encode($data);
	}

	public function fetch_customer_by_type(){
		$customer_type = $this->input->post('customer_type');
		$data = $this->TDL->get_result('customer_id as id,customer_name as name','cust_customers',['customer_type'=>$customer_type]);
		echo json_encode($data);
	}
  
	public function fetch_contact_by_customer(){
		$customer_id = $this->input->post('customer_id');
		$data = $this->TDL->get_result('contact_id as id,contact_name as name','contacts',['contacts_customer_id'=>$customer_id]);
		echo json_encode($data);
	}

	public function get_ajax(){
		$col = $this->input->post('select');
		$table = $this->input->post('table');
		$data = $this->TDL->get_result($col,$table);
		echo json_encode($data);
	}

	public function get_asign_to(){
		$designation_id2 = SALES_EXECUTIVE;
		$designation_id1 = SALES_MANAGER;
		$result = $this->TDL->get_asign_to($designation_id2,$designation_id1);
		echo json_encode($result);
	}


	public function insert_new_communication(){
		$post = $this->input->post();
		$this->form_validation->set_rules('comm_communications_contact_id','contact','required');
		$this->form_validation->set_rules('medium','Medium','required');
		$this->form_validation->set_rules('note','Note','required|trim');

		$data = (array)$this->TDL->get_row('*','comm_communications',['communication_id'=>$post['communication_id']]);

		$data['communication_mode'] = $post['communication_mode'];
		$data['comm_communications_contact_id'] = $post['comm_communications_contact_id'];
		$data['date_of_communication'] = $post['date_of_communication'];
		$data['follow_up_date'] = $post['follow_up_date'];
		$data['medium'] = $post['medium'];
		$data['connected_to'] = $post['connected_to'];
		$data['note'] =$post['note'];
		$data['created_by'] = $this->user;
		$data['created_on'] = date("Y-m-d") . " " . date("h:i:s");
		unset($data['communication_id']);
		if($this->form_validation->run()){

			$result = $this->TDL->insert_data('comm_communications',$data);
			
			if($result){

				$log = array();
				$log['source_module'] = 'Communication';
				$log['record_id'] = $result;
				$log['created_on'] = date("Y-m-d h:i:s");
				$log['created_by'] = $this->user;
				$log['action_taken'] = 'insert_new_communication';
				$log['text'] = 'COMMUNICATION ADDED WITH SUBJECT '.$data['subject'];
				$this->TDL->insert_data('user_log_history',$log);

				$msg = array(
					'status'=>1,
					'msg'=>'NEW COMMUNICATION ADDED SUCCESSFULLY'
				);
				$this->session->set_flashdata('success', 'NEW COMMUNICATION ADDED SUCCESSFULLY');
			}
			else{
				$msg = array(
					'status'=>0,
					'msg'=>'SOMETHING WENT WRONG!'
				);
			}
		}
		else{
			$msg = array(
				'status'=>0,
				'msg'=>'FILL ALL REQUIRED FIELDS!',
				'errors'=>$this->form_validation->error_array()
			);
		}
		echo json_encode($msg);
	}

	// public function insert_new_opportunity(){
	// 	$post = $this->input->post();
	// 	$this->form_validation->set_rules('opportunity_value','Value','required');
	// 	$this->form_validation->set_rules('currency_id','Currency','required');
	// 	$this->form_validation->set_rules('opportunity_contact_id','Contact','required');
	// 	$this->form_validation->set_rules('opportunity_contact_id','Contact','required');
	// 	$this->form_validation->set_rules('op_assigned_to', 'Assigned To', 'required');
	// 	$this->form_validation->set_rules('description', 'Description', 'required|min_length[20]|trim');

	// 	$data = (array)$this->TDL->get_row('*','opportunity',['opportunity_id'=>$post['opportunity_id']]);

	// 	$data['types'] = $post['types'];
	// 	$data['opportunity_value'] = $post['opportunity_value'];
	// 	$data['currency_id'] = $post['currency_id'];
	// 	$data['estimated_closure_date'] = $post['estimated_closure_date'];
	// 	$data['opportunity_contact_id'] = $post['opportunity_contact_id'];
	// 	$data['op_assigned_to'] = $post['op_assigned_to'];
	// 	$data['description'] =$post['description'];
	// 	$data['created_by'] = $this->user;
	// 	$data['created_on'] = date("Y-m-d") . " " . date("h:i:s");
	// 	unset($data['opportunity_id']);
	// 	if($this->form_validation->run()){

	// 		$result = $this->TDL->insert_data('opportunity',$data);
			
	// 		if($result){
	// 			$log = array();
	// 			$log['source_module'] = 'Opportunity';
	// 			$log['record_id'] = $result;
	// 			$log['created_on'] = date("Y-m-d h:i:s");
	// 			$log['created_by'] = $this->user;
	// 			$log['action_taken'] = 'insert_new_opportunity';
	// 			$log['text'] = 'OPPORTUNITY ADDED WITH NAME '.$data['opportunity_name'];
	// 			$this->TDL->insert_data('user_log_history',$log);

	// 			$msg = array(
	// 				'status'=>1,
	// 				'msg'=>'NEW OPPORTUNITY ADDED SUCCESSFULLY'
	// 			);
	// 			$this->session->set_flashdata('success', 'NEW OPPORTUNITY ADDED SUCCESSFULLY');
	// 		}
	// 		else{
	// 			$msg = array(
	// 				'status'=>0,
	// 				'msg'=>'SOMETHING WENT WRONG!'
	// 			);
	// 		}
	// 	}
	// 	else{
	// 		$msg = array(
	// 			'status'=>0,
	// 			'msg'=>'FILL ALL REQUIRED FIELDS!',
	// 			'errors'=>$this->form_validation->error_array()
	// 		);
	// 	}
	// 	echo json_encode($msg);
	// }
}
