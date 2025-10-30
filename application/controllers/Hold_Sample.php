<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Hold_Sample extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->check_session();
        $this->load->model('SampleRegistration', 'sr');
        $this->load->model('Hold_sample_model', 'hs');
    }



    public function Hold_sample()
    {
        $checkUser = $this->session->userdata('user_data');

        $data = $this->input->post();

        // print_r($data);die;

        $previous_status = $this->hs->get_Row('status', 'sample_registration', ['sample_reg_id' => $data['sample_reg_id']]);
        $hold_sample_data = array(
            'hold_remark' => $data['remark'],
            'hold_reason' => $data['hold_Reason'],
            'sample_reg_id' => $data['sample_reg_id'],
            'previous_Status' => $previous_status->status,
            'done_by'=>$checkUser->uidnr_admin

        );
        // print_r($hold_sample_data);die;
        $insert =  $this->hs->insert_data('sample_hold_remark', $hold_sample_data);
        // echo $this->db->last_Query();die;
        if ($insert) {
            $update_hold_sample_data = $this->hs->update_data('sample_registration', ['status' => 'Hold Sample'], ['sample_reg_id' => $data['sample_reg_id']]);

            if ($update_hold_sample_data) {
                $msg = array(
                    'status' => 1,
                    'msg' => 'Sample has been Hold'
                );
            } else {
                $msg = array(
                    'status' => 0,
                    'msg' => 'Sample Not Hold'
                );
            }
        } else {
            $msg = array(
                'status' => 0,
                'msg' => 'Sample Not Hold'
            );
        }
        echo json_encode($msg);
    }


    public function get_reason()
    {
        $data = $this->input->post();
        $reason = $this->hs->get_reason($data);
        // print_r($reason);die;
        if ($reason) {
            $html = '<table border="1" style="border-collapse:collapsed;width:100%;"><tr><th>Hold Remark</th><th>Hold Reason</th><th>Created BY</th><th>Date</th></tr>';
            foreach($reason as $re){
               $html.='<tr><td>'.$re['hold_Reason'].'</td><td>'.$re['hold_reason'].'</td><td>'.$re['created_by'].'</td><td>'.$re['created_on'].'</td></tr>';
            }
            $html .='</table>';
        } else {
            $html ='No record found!.';
        }
        echo json_encode($html);
    }
    public function get_unhold_reason()
    {
        $data = $this->input->post();
        // print_r($data);die;
        $reason = $this->hs->get_unhold_reason($data);
        // print_r($reason);die;
        if ($reason) {
            $html = '<table border="1" style="border-collapse:collapsed;width:100%;"><tr><th>UnHold Reason</th><th>Created BY</th><th>Date</th></tr>';
            foreach($reason as $re){
               $html.='<tr><td>'.$re['unhold_reason'].'</td><td>'.$re['created_by'].'</td><td>'.$re['created_on'].'</td></tr>';
            }
            $html .='</table>';
        } else {
            $html ='No record found!.';
        }
       
        echo json_encode($html);
    }
    public function UnHold_sample()
    {
        $checkUser = $this->session->userdata('user_data');


        $data = $this->input->post();
        // print_r($data);die;
        $previous_Status =  $this->hs->get_row('previous_Status,id', 'sample_hold_remark', ['sample_reg_id' => $data['sample_reg_id']]);
        $umhold_sample_data = array(
            'unhold_reason' => $data['unhold_reason'],
            'sample_reg_id' => $data['sample_reg_id'],
            'done_by'=>$checkUser->uidnr_admin
        );
        $update_unhold_reason = $this->hs->insert_data('sample_hold_remark', $umhold_sample_data);
        // echo $this->db->last_query();die;
        if ($update_unhold_reason) {
            $update_unhold_sample_data = $this->hs->update_data('sample_registration', ['status' => $previous_Status->previous_Status], ['sample_reg_id' => $data['sample_reg_id']]);
            if ($update_unhold_sample_data) {
                $msg = array(
                    'status' => 1,
                    'msg' => 'Sample has been UnHold'
                );
            } else {
                $msg = array(
                    'status' => 0,
                    'msg' => 'Sample Not unHold'
                );
            }
        } else {
            $msg = array(
                'status' => 0,
                'msg' => 'Sample Not unHold'
            );
        }
        echo json_encode($msg);
    }

    public function get_hold_status()
    {
        $hold_status = $this->hs->get_hold_status();
        if ($hold_status) {
            echo json_encode($hold_status);
        }
    }
}
