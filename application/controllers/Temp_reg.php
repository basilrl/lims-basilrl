<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Temp_reg extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->check_session();
        $this->load->model('Temp_model');
    }

    public function index(){

        $where = NULL;
        $data['search'] = $search = NULL;
        $base_url = 'Temp_reg/index/';
        $data['customer']=NULL;
        $data['buyer']=NULL;
        // if ($this->uri->segment('3')!='' && $this->uri->segment('3') != 'NULL') {
        //     $base_url .=$where['tr.temp_no']= base64_decode($this->uri->segment('3'));
           
        // } else {
        //     $base_url .= '/NULL';
        // }
        if($this->uri->segment('3')!='' && $this->uri->segment('3')!='NULL'){
            $base_url .= $where['cus.customer_id']= $this->uri->segment('3'); 
            $data['customer']=$where['cus.customer_id'];
            $data['customer_name'] = $this->get_customerbyId($where['cus.customer_id']);
        }
        else{
            $base_url .= '/NULL';
            $data['customer']='NULL';
            $data['customer_name'] =NULL;
        }
        if($this->uri->segment('4')!='' && $this->uri->segment('4')!='NULL'){
            $base_url .= $where['tr.buyer_id']= $this->uri->segment('4');
            $data['buyer']=$where['tr.buyer_id'];
            $data['buyer_name'] = $this->get_customerbyId($where['tr.buyer_id']);
        
        }  
        else{
            $base_url .= '/NULL';
            $data['buyer']='NULL';
            $data['buyer_name']=NULL;
        }
        // if($this->uri->segment('6')!='' && $this->uri->segment('6')!='NULL'){
        //     $base_url .= $where['tr.reference_no']= $this->uri->segment('6');  
        // }
        
        // else{
        //     $base_url .= '/NULL';
        // }
        if ($this->uri->segment('5')!=NULL && $this->uri->segment('5') != 'NULL') {
           $data['search'] = $search = base64_decode($this->uri->segment('5'));
           $base_url .= '/'.base64_encode($data['search']);
           
            
        }else {
            $base_url .= '/NULL';
            $data['search'] = 'NULL';
        }
        if ($this->uri->segment('6')!=NULL && $this->uri->segment('6') != 'NULL') {
            $sortby = $this->uri->segment('6');
            $base_url .= '/'.$sortby;
             
         }else {
             $base_url .= '/NULL';
             $sortby =NULL;
         }
         if ($this->uri->segment('7')!=NULL && $this->uri->segment('7') != 'NULL') {
            $order = $this->uri->segment('7');
            $base_url .= '/'.$order;
             
         }else {
             $base_url .= '/NULL';
             $order ='NULL';
         }
        
        $total_row = $this->Temp_model->getTempDataList(NULL,NULL,$search,NULL,NULL,$where,'1');
        
        $config = $this->pagination($base_url, $total_row,10,8);;
        $data["links"] = $config["links"];
        $data['temp_list'] = $this->Temp_model->getTempDataList($config["per_page"], $config['page'], $search, $sortby,$order,$where);

        $start = (int)$this->uri->segment(8) + 1;
        $end = (($data['temp_list'])?count($data['temp_list']):0) + (($this->uri->segment(8)) ? $this->uri->segment(8) : 0);
        $data['result_count'] = "Showing " . $start . " - " . $end . " of " . $total_row . " Results";

        if ($order == NULL || $order =='NULL') {
            $data['order'] = ($order) ? "DESC" : "ASC";
        } else {
            $data['order'] = ($order == "ASC") ? "DESC" : "ASC";
        }

        // if ($order = "ASC") {
        //     $data['order'] = "DESC";
        // } if($order = "DESC") {
        //     $data['order'] = "ASC";
        // }


        $this->load_view('Jobs/temp_list',$data);
       
    }


    public function addtemp_page(){
        $this->load_view('Jobs/add_temp',NULL);
    }

    public function add_temp(){
    

        $data = $this->input->post();
        $checkUser = $this->session->userdata('user_data');
        $data['remarks'] = htmlentities($data['remarks_temp_add']);
        $data['temp_no'] = '';
        $data['created_by'] = $checkUser->uidnr_admin;
        $data['trf_gen_status'] = '0';
        $data['status'] = '1';
        unset($data['cust_name']);
        unset($data['buyer_name']);
        unset($data['contact_name']);
        unset($data['country_of_origin']);
        unset($data['country_of_destination']);
        unset($data['crm_user_list']);
        
        
         
      if($this->form_validation->run('temp_submit')==FALSE){
         $this->form_validation->set_error_delimiters('<div class="error" style="color:red">', '</div>');
             $this->load_view('Jobs/add_temp',NULL);
      }
      else{
            unset($data['remarks_temp_add']);
            $insert = $this->Temp_model->insert_temp_reg($data);
            if($insert){
               
                $this->session->set_flashdata('success', 'Temporary Registration No.'.$insert .'Generated');
                redirect('Temp_reg/index');
            }
            else{
                $this->session->set_flashdata('error', 'Error in Submitting details');
                $this->load_view('Jobs/add_temp',NULL);
            }

           
      }
    }  
    
    public function edit_temp($temp_reg_id){
        $data = $this->Temp_model->getTempdetails($temp_reg_id);

        if($data){
            $this->load_view('Jobs/edit_temp',['temp_list'=>$data]);
        }
        else{
            redirect('Temp_reg/index');
        }
        
    }

    public function update_temp($temp_reg_id){

        $data = $this->input->post();
        $checkUser = $this->session->userdata('user_data');
        $data['remarks'] = htmlentities($data['remarks_temp_edit']);
        unset($data['cust_name']);
        unset($data['buyer_name']);
        unset($data['contact_name']);
        unset($data['country_of_origin']);
        unset($data['country_of_destination']);
        unset($data['crm_user_list']);
       
      
        $log = array(
            'temp_reg_id'=>$temp_reg_id,
            'title'=>'Record Updated',
            'created_by'=>$checkUser->uidnr_admin
        );

      if($this->form_validation->run('temp_submit')==FALSE){
         $this->form_validation->set_error_delimiters('<div class="error" style="color:red">', '</div>');
         $data = $this->Temp_model->getTempdetails($temp_reg_id);
             $this->load_view('Jobs/edit_temp',['temp_list'=>$data]);
      }
      else{
            unset($data['remarks_temp_edit']);
            $update = $this->Temp_model->update_temp_reg($data,$temp_reg_id,$log);
            if($update){
                $this->session->set_flashdata('success', 'Temporary Registration Updated');
                redirect('Temp_reg/index');
            }
            else{
                $this->session->set_flashdata('error', 'Error in Updating details');
                $this->load_view('Jobs/edit_temp');
            }

           
      }
    }  
    


    public function getcustomers(){
        $search = $this->input->post('search');
        $customers = $this->Temp_model->getcustomer($search);
    
        if($customers){
            echo json_encode($customers);
        }
       
    }

    public function getbuyer(){
        $key =$this->input->post('key');
        $search = $this->input->post('search');
        $buyers = $this->Temp_model->getBuyers($key,$search);
        if($buyers){
            echo json_encode($buyers);
        }
    }
    public function getContacts(){
        $key =$this->input->post('key');
        $search = $this->input->post('search');
        $contacts = $this->Temp_model->getcontacts($key,$search);
        
        if($contacts){
            echo json_encode($contacts);
        }
    }

    public function getCountry(){
        $search =$this->input->post('search');
        $countries = $this->Temp_model->getCountries($search);
        if($countries){
            echo json_encode($countries);
        }
    }

    public function crm_list(){
        $search = $this->input->post('search');
        $crm = $this->Temp_model->crm_user_list($search);
        if($crm){
            echo json_encode($crm);
        }
    }

    public function send_temp($temp_reg_id){
        $data = $this->Temp_model->getTempdetails($temp_reg_id);
        $body = $this->compose_email_body($data);
        if($data){
            $this->load_view('Jobs/temp_mail',['temp_list'=>$data,'mail_body'=>$body]);
        }
        else{
            redirect('Temp_reg/index');
        }

    }

    public function temp_mail_send(){
        $checkUser = $this->session->userdata('user_data');
        $mail =$this->input->post();
      
        $to =$mail['to_email'];
        $cc_email = $mail['cc_email'];
        $bcc_email = $mail['bcc_email'];
        $compose = $mail['remarks'];
        $subject = $mail['subject'];

        $log = array(
            'temp_reg_id'=>$mail['temp_id'],
            'created_by'=>$checkUser->uidnr_admin,
            'receiver_email'=>$to,
            'cc_person'=>$cc_email,
            'bcc_person'=>$bcc_email
        );
      
        if($this->form_validation->run('send_mail')==FALSE){
            $this->form_validation->set_error_delimiters('<div class="error" style="color:red">', '</div>');
                $this->load_view('Jobs/send_temp',NULL);
         }
         else{
    
                $msg = send_mail_function($to,'',$cc_email,$compose,$subject);

               if($msg){
                   $this->session->set_flashdata('success', 'Temporary Registration Sent');
                   $this->Temp_model->mail_log($log);
                   redirect('Temp_reg/index');
               }
               else{
                   $this->session->set_flashdata('error', 'Error in Sending details');
                   $this->load_view('Jobs/send_temp',NULL);
               }
   
              
         }
    }

    public function compose_email_body($data){
        $mail_body = $data[0];
        $test_req = html_entity_decode($mail_body->remarks);

        if($mail_body->mobile){
            $mobile = $mail_body->mobile;
        }
        else{
            $mobile= '91 124 6250 500';
        }
        $body = '';
        $body.='<table width="100%" border="0" cellspacing="5" cellpadding="5" style="border-collapse:collapse; font-family:Arial, Helvetica, sans-serif;font-size:12px;"><tr><td bgcolor="#336699"><img src="[SITE_PATH]/resources/images/default/inner-logo-glims.png" height="53"/></td><td align="right" bgcolor="#336699"><img src="[SITE_PATH]/resources/logo/logo-receipt.gif" height="53"/></td></tr><tr><td colspan="2"><br /> Dear Sir/Madam,<br /><br /> We have processing your sample with temporarily details, please share TRF details to provide you Basil Report number.<br /> Here are details: <br /><br /><br /></td></tr><tr ><td colspan="2"><table border="2"><tr><td >TO</td><td>'.$mail_body->customer_name.'</td></tr><tr><td>BUYER</td><td>'.$mail_body->buyer.'</td></tr><tr><td>ATTENTION</td><td>'. $mail_body->contact_name.'</td></tr><tr><td >REPORT NO.</td><td>'.$mail_body->temp_no.'</td></tr><tr><td >SAMPLE(S) RECEIVED DATE</td><td>'.$mail_body->sample_receiving_date.'</td></tr><tr><td >REPORT DATE</td><td>'.$mail_body->report_date.'</td></tr><tr><td >SERVICE</td><td>'.$mail_body->service.'</td></tr><tr><td >SAMPLE DESCRIPTION</td><td>'.$mail_body->sample_desc.'</td></tr><tr><td >STYLE NO.</td><td>'.$mail_body->style_no.'</td></tr><tr><td >PO NO.</td><td>'.$mail_body->po_no.'</td></tr><tr><td >COLOUR</td><td>'.$mail_body->colour.'</td></tr><tr><td >TEST(S) REQUESTED</td><td>'.$test_req.'</td></tr><tr><td >CUSTOMER SERVICE CONTACT</td><td><br><b>'.$mail_body->user_name.'</b><br><br>E-Mail: <a href="mailto: '.$mail_body->email.'">'.$mail_body->email.'</a><br>Phone: '.$mobile.'<br></td></tr><tr><td colspan="2"><br><b>NOTE:- </b><br><br>Due to Covid 19 , we all are facing huge challenges. We at BASIL are working on staggered and from home to make sure your reports doesnâ€™t get delayed. If there is any delay please bear with us and do write us back to following escalating person :-<br><br>Textile :- Vivek Patil (<a href = "mailto: vivek.p@basilrl.com">vivek.p@basilrl.com</a>)<br><br>Leather & Footwear â€“ Anirudh Sharma (<a href="mailto: anirudh.s@basilrl.com">anirudh.s@basilrl.com</a> )<br><br>Toys ,Hardline , Furniture and home and beauty â€“ Om Prakash (<a href="mailto: om.p@basilrl.com">om.p@basilrl.com</a>)<br><br>Inspection- Satish Gupta (<a href="mailto: satish.gupta@basilrl.com">satish.gupta@basilrl.com</a>)<br><br>Electrical- saurabh Srivastav (<a href="mailto: Saurabh.s@basilrl.com">Saurabh.s@basilrl.com</a> )<br><br>Please review the above information and advice immediately if any amendment is required. For any queries, please feel free to contact our Customer Service Representative.</td></tr></table></td></tr><tr><td colspan="2"><br/><br/><br/><br/><br/><br/> Thanks and Regards,<br/> Basil Team<br/><br/> <br /> If you need further support, please contact the GLIMS Administrator. <br /> <strong>GLIMS Administrator</strong><br /> BASIL <br /></td></tr><tr><td align="left" bgcolor="#D5E2F2">Geo Chem Consumer Products Services</td><td align="right" bgcolor="#D5E2F2">GLIMS - Online Lab Information System</td></tr></table>';

        return $body;


    }


    public function open_worksheet($temp_reg_id){
        $file['data'] = $this->Temp_model->getTempdetails($temp_reg_id);
        // echo "<pre>";
        // print_r($file);exit;
        // $html = $this->load->view('Jobs/tempRecord',$file,true);
        $rand_no = 'coc_list'.rand(1000, 9999);
        $file_name = $rand_no . '.pdf';
        $this->generate_pdf('Jobs/tempRecord', $file, 'view',$file_name);
      
    //     $mpdf = new \Mpdf\Mpdf();
    //    $mpdf->charset_in = 'UTF-8';
       
    //    $mpdf->setAutoTopMargin = 'stretch';
    // //    $mpdf->lang = 'ar';
    //   // $mpdf->SetWatermarkText('DRAFT');
    //   // $mpdf->showWatermarkText = true;
    //    $mpdf->autoLangToFont = true;
    //    $mpdf->WriteHTML($html);
    //    $rand_no = 'coc_list'.rand(1000, 9999);
    //    $mpdf->Output($rand_no . '.pdf', 'I');
        
    }

    // public function get_temp_list(){

    //     $data = $this->Temp_model->get_templist(); 
    //     if($data){
    //         echo json_encode($data);
    //     }
    // }

    // public function get_cust_list(){

    //     $data = $this->Temp_model->get_custlist();
    //     if($data){
    //         echo json_encode($data);
    //     }
    // }
   
    // public function get_buyer(){
    //     $data = $this->Temp_model->get_buyers();
    //     if($data){
    //         echo json_encode($data);
    //     }
    // }
    // public function get_reference(){
    //     $data = $this->Temp_model->get_ref();
    //     if($data){
    //         echo json_encode($data);
    //     }
    // }

    public function col_hide(){
        $post = $this->input->post();
        $this->session->set_userdata('col_'.$post['colno'],$post['checked']);
    }
    public function get_customerbyId($customer_id){
        return $this->Temp_model->get_custby_id($customer_id);
    }
    public function get_buyerbyId($buyer_id){
        return $this->Temp_model->get_buyerby_id($buyer_id);
    }

    // Get division log
	public function get_temp_reg_log()
	{
		$temp_reg_id = $this->input->post('temp_reg_id');
		$data = $this->Temp_model->get_temp_reg_log($temp_reg_id);
		echo json_encode($data);
	}


}
