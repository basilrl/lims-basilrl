<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Equipment extends MY_Controller
{

    // __constructor for Equipment
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Equipment_model', 'eq');
    }
    // INDEX FUNCTION FOR LISTING OF APPLICATION CAEE INSTRUCTION BY KAMAL  ON 6TH JUNE 2022
    public function index()
    {
        $where = NULL;
        $search = NULL;

        $base_url = 'Equipment/index';
        $order = ($this->uri->segment('13'));
        $sortby = $this->uri->segment('14');


        if ($this->uri->segment('3') != NULL && $this->uri->segment('3') != 'NULL') {
            $eqip_name = $this->uri->segment('3');
            $data['eqip_name'] =  $eqip_name;
            $base_url .= '/' . $eqip_name;
            $where['eqip_name'] = base64_decode($eqip_name);
        } else {
            $base_url .= '/NULL';
            $data['eqip_name'] = 'NULL';
            $where['eqip_name'] = 'NULL';
        }


        if ($this->uri->segment('4') != NULL && $this->uri->segment('4') != 'NULL') {
            $eqip_ID_no = $this->uri->segment('4');
            $data['eqip_ID_no'] =  $eqip_ID_no;
            $base_url .= '/' . $eqip_ID_no;
            $where['eqip_ID_no'] = base64_decode($eqip_ID_no);
        } else {
            $base_url .= '/NULL';
            $data['eqip_ID_no'] = 'NULL';
            $where['eqip_ID_no'] = 'NULL';
        }


        if ($this->uri->segment('5') != NULL && $this->uri->segment('5') != 'NULL') {
            $model = $this->uri->segment('5');
            $data['model'] =  $model;
            $base_url .= '/' . $model;
            $where['model'] = base64_decode($model);
        } else {
            $base_url .= '/NULL';
            $data['model'] = 'NULL';
            $where['model'] = 'NULL';
        }
        if ($this->uri->segment('6') != NULL && $this->uri->segment('6') != 'NULL') {
            $make = $this->uri->segment('6');
            $data['make'] =  $make;
            $base_url .= '/' . $make;
            $where['make'] = base64_decode($make);
        } else {
            $base_url .= '/NULL';
            $data['make'] = 'NULL';
            $where['make'] = 'NULL';
        }
        if ($this->uri->segment('7') != NULL && $this->uri->segment('7') != 'NULL') {
            $serial_no = $this->uri->segment('7');
            $data['serial_no'] =  $serial_no;
            $base_url .= '/' . $serial_no;
            $where['serial_no'] = base64_decode($serial_no);
        } else {
            $base_url .= '/NULL';
            $data['serial_no'] = 'NULL';
            $where['serial_no'] = 'NULL';
        }

        if ($this->uri->segment('8') != NULL && $this->uri->segment('8') != 'NULL') {
            $calib_by = $this->uri->segment('8');
            $data['calib_by'] =  $calib_by;
            $base_url .= '/' . $calib_by;
            $where['calib_by'] = base64_decode($calib_by);
        } else {
            $base_url .= '/NULL';
            $data['calib_by'] = 'NULL';
            $where['calib_by'] = 'NULL';
        }

        if ($this->uri->segment('9') != NULL && $this->uri->segment('9') != 'NULL') {
            $due_date = $this->uri->segment('9');
            $data['due_date'] =  $due_date;
            $base_url .= '/' . $due_date;
            $where['due_date'] = base64_decode($due_date);
        } else {
            $base_url .= '/NULL';
            $data['due_date'] = 'NULL';
            $where['due_date'] = 'NULL';
        }

        if ($this->uri->segment('10') != NULL && $this->uri->segment('10') != 'NULL') {
            $calib_date = $this->uri->segment('10');
            $data['calib_date'] =  $calib_date;
            $base_url .= '/' . $calib_date;
            $where['calib_date'] = base64_decode($calib_date);
        } else {
            $base_url .= '/NULL';
            $data['calib_date   '] = 'NULL';
            $where['calib_date'] = 'NULL';
        }

        if ($this->uri->segment('11') != NULL && $this->uri->segment('11') != 'NULL') {
            $division = $this->uri->segment('11');
            $data['division'] =  $division;
            $base_url .= '/' . $division;
            $where['division'] = base64_decode($division);
        } else {
            $base_url .= '/NULL';
            $data['division'] = 'NULL';
            $where['division'] = 'NULL';
        }


        if ($this->uri->segment('12') != NULL && $this->uri->segment('12') != 'NULL') {
            $certi_no = $this->uri->segment('12');
            $data['certi_no'] =  $certi_no;
            $base_url .= '/' . $certi_no;
            $where['certi_no'] = base64_decode($certi_no);
        } else {
            $base_url .= '/NULL';
            $data['certi_no'] = 'NULL';
            $where['certi_no'] = 'NULL';
        }

        if ($order != NULL && $order != 'NULL') {
            $base_url .= '/' . ($order);
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


        $total_row = $this->eq->get_All_Equipment_List($where, null, null, true, null, null);

        $config = $this->pagination($base_url, $total_row, 10, 15);
        $data['links'] = $config['links'];
        $data['eqip_equipments'] = $this->eq->get_All_Equipment_List($where, $config['per_page'], $config['page'], false, $sortby, base64_decode($order));
        $data['created_by_name'] = $this->eq->fetch_created_person();
        $data['div'] = $this->eq->fetch_division_name();
        $page_no = $this->uri->segment('15');
        $start = (int)$page_no + 1;
        $end = (($data['eqip_equipments']) ? count($data['eqip_equipments']) : 0) + (($page_no) ? $page_no : 0);
        $data['result_count'] = "Showing " . $start . " - " . $end . " of " . $total_row . " Results";

        if ($order == NULL || $order == 'NULL') {
            $data['order'] =    base64_decode($order) ? "DESC" : "ASC";
        } else {

            $data['order'] = (base64_decode($order) == "ASC") ? "DESC" : "ASC";
        }

        $this->load_view("Equipment/index", $data);
    }
    // add function in equipment listing adding data into listing create by kamal singh on 7th of july 2022

    public function add()
    {
        $arrData['created_by_name'] = $this->eq->fetch_created_person();
        $arrData['branch_name'] = $this->eq->fetch_branch_name();
        $arrData['test_name'] = $this->eq->fetch_test_name();
        $arrData['division_name'] = $this->eq->fetch_division_name();
        // $arrData['test_ids'] = explode(',', $arrData['eqip_equipments']['equip_test_id']);
        if ($this->input->post('btnadd')) {

            $this->form_validation->set_rules('name', 'Equipment Name', 'trim|required');
            $this->form_validation->set_rules('equip_id', 'Equipment Id', 'trim|required|is_unique[eqip_equipments.eqip_ID_no]');
            // $this->form_validation->set_rules('model', 'Model', 'trim|required');
            // $this->form_validation->set_rules('make', 'Make', 'trim|required');
            // $this->form_validation->set_rules('status', 'Status', 'trim|required');
            // $this->form_validation->set_rules('Last_calib_date', 'Last Calibration Date', 'trim|required');
            // $this->form_validation->set_rules('next_calib_date', 'Next Calibration Date', 'trim|required');
            // $this->form_validation->set_rules('Last_main_date', 'Last Calibration Date', 'trim|required');
            // $this->form_validation->set_rules('next_main_date', 'Last Calibration Date', 'trim|required');

            if ($this->form_validation->run() == true) {

                $Data['eqip_name'] = $this->input->post('name');
                $Data['eqip_ID_no'] = $this->input->post('equip_id');
                $Data['model'] = ($this->input->post('model') != '') ? ($this->input->post('model')) : '0';
                $Data['make'] = ($this->input->post('make') != '') ? ($this->input->post('make')) : '0';
                $Data['serial_no'] = ($this->input->post('serial') != '') ? ($this->input->post('serial')) : '0';
                $Data['created_by'] = $this->session->userdata('user_data')->uidnr_admin;
                $Data['created_on'] = date("Y-m-d h:i:s");

                $Data['eqip_usage'] = ($this->input->post('usage') != '') ? ($this->input->post('usage')) : '0';

                $Data['calibrated_by'] = ($this->input->post('calib_by') != '') ? ($this->input->post('calib_by')) : '0';
                // echo "<pre>";
                // print_r($this->input->post('certi_no')); die;
                $Data['calibration_certificate_number'] = ($this->input->post('certi_no') != '') ? ($this->input->post('certi_no')) : '0';
                $Data['calibration_date'] = ($this->input->post('calib_date') != '') ? ($this->input->post('calib_date')) : '0';
                $Data['calibration_due_date'] = ($this->input->post('due_date') != '') ? ($this->input->post('due_date')) : '0';

                // $Data['next_maintanance_date'] = $this->input->post('next_main_date');
                $Data['eqip_status'] = $this->input->post('status');
                // $Data['custodian_id'] = ($this->input->post('created_by') != 'NULL') ? ($this->input->post('created_by')) : '0';

                $Data['equip_branch_id'] = ($this->input->post('branch') != '') ? ($this->input->post('branch')) : '0';
                // $Data['next_calibration_period_type'] = ($this->input->post('calib_time') != '') ? ($this->input->post('calib_time')) : '0';
                // $Data['next_calibration_period'] = ($this->input->post('calib_period') != '') ? ($this->input->post('calib_period')) : '0';
                // $Data['next_maintanance_period_type'] = ($this->input->post('main_time') != '') ? ($this->input->post('main_time')) : '0';
                // $Data['next_maintanance_period'] = ($this->input->post('main_period') != '') ? ($this->input->post('main_period')) : '0';
                $Data['callibration_field'] = ($this->input->post('calib_field') != '') ? ($this->input->post('calib_field')) : '0';
                // $Data['maintanence_field'] = ($this->input->post('main_field') != '') ? ($this->input->post('main_field')) : '0';
                $Data['division'] = ($this->input->post('division') != '') ? ($this->input->post('division')) : '0';
                $fetch_data = $this->input->post();
                $test = implode(",", $fetch_data['test']);

                $Data['equip_test_id'] = ($test != '') ? $test : '0';
                $insert = $this->eq->insert_equip($Data);


                if ($insert) {
                    $this->session->set_flashdata('success', 'You are added Successfully');
                    $this->session->flashdata('success');
                    redirect("Equipment");
                } else {
                    $this->session->set_flashdata('error', 'Unable to load data TRY AGAIN!');
                    $this->session->flashdata('error');
                    redirect("Equipment");
                }
            } else {
            }
        }
        $this->load_view('Equipment/add', $arrData);
    }


    // Edit method of mst category by kamal on 6th of june 2022;

    public function edit($id)
    {
        $arrData['eqip_equipments'] = $this->eq->get_id_wise_equipment($id);
        $arrData['created_by_name'] = $this->eq->fetch_created_person();
        $arrData['branch_name'] = $this->eq->fetch_branch_name();
        $arrData['test_name'] = $this->eq->fetch_test_name();
        $arrData['division_name'] = $this->eq->fetch_division_name();
        $arrData['test_ids'] = explode(',', $arrData['eqip_equipments']['equip_test_id']);

        if ($this->input->post('btnedit')) {
            $this->form_validation->set_rules('name', 'Equipment Name', 'trim|required');
            $this->form_validation->set_rules('equip_id', 'Equipment Id', 'trim|required|callback_update_eqip_code');
            // $this->form_validation->set_rules('model', 'Model', 'trim|required');
            // $this->form_validation->set_rules('make', 'Make', 'trim|required');
            // $this->form_validation->set_rules('status', 'Status', 'trim|required');
            // $this->form_validation->set_rules('Last_calib_date', 'Last Calibration Date', 'trim|required');
            // $this->form_validation->set_rules('next_calib_date', 'Next Calibration Date', 'trim|required');
            // $this->form_validation->set_rules('Last_main_date', 'Last Calibration Date', 'trim|required');
            // $this->form_validation->set_rules('next_main_date', 'Last Calibration Date', 'trim|required');

            if ($this->form_validation->run() == true) {

                $editData['eqip_name'] = $this->input->post('name');
                $editData['eqip_ID_no'] = $this->input->post('equip_id');
                $editData['model'] = ($this->input->post('model') != '') ? ($this->input->post('model')) : '0';
                $editData['make'] = ($this->input->post('make') != '') ? ($this->input->post('make')) : '0';
                $editData['serial_no'] = ($this->input->post('serial') != '') ? ($this->input->post('serial')) : '0';
                $editData['updated_by'] = $this->session->userdata('user_data')->uidnr_admin;
                $editData['updated_on'] = date("Y-m-d h:i:s");
                $editData['eqip_usage'] = ($this->input->post('usage') != '') ? ($this->input->post('usage')) : '0';

                $Data['calibrated_by'] = ($this->input->post('calib_by') != '') ? ($this->input->post('calib_by')) : '0';
                $Data['calibration_certificate_number'] = ($this->input->post('certi_no') != '') ? ($this->input->post('certi_no')) : '0';
                $Data['calibration_date'] = ($this->input->post('calib_date') != '') ? ($this->input->post('calib_date')) : '0';
                $Data['calibration_due_date'] = ($this->input->post('due_date') != '') ? ($this->input->post('due_date')) : '0';

                // $editData['Last_calib_date'] = $this->input->post('Last_calib_date');
                // $editData['next_calib_date'] = $this->input->post('next_calib_date');
                // $editData['last_maintanance_date'] = $this->input->post('Last_main_date');
                // $editData['next_maintanance_date'] = $this->input->post('next_main_date');
                $editData['eqip_status'] = $this->input->post('status');
                // $editData['custodian_id'] = ($this->input->post('created_by') != 'NULL') ? ($this->input->post('created_by')) : '0';

                $editData['equip_branch_id'] = ($this->input->post('branch') != '') ? ($this->input->post('branch')) : '0';
                // $editData['next_calibration_period_type'] = ($this->input->post('calib_time') != '') ? ($this->input->post('calib_time')) : '0';
                // $editData['next_calibration_period'] = ($this->input->post('calib_period') != '') ? ($this->input->post('calib_period')) : '0';
                // $editData['next_maintanance_period_type'] = ($this->input->post('main_time') != '') ? ($this->input->post('main_time')) : '0';
                // $editData['next_maintanance_period'] = ($this->input->post('main_period') != '') ? ($this->input->post('main_period')) : '0';
                $editData['callibration_field'] = ($this->input->post('calib_field') != '') ? ($this->input->post('calib_field')) : '0';
                // $editData['maintanence_field'] = ($this->input->post('main_field') != '') ? ($this->input->post('main_field')) : '0';
                $editData['division'] = ($this->input->post('division') != '') ? ($this->input->post('division')) : '0';
                $fetch_data = $this->input->post();
                $test = implode(",", $fetch_data['test']);

                $editData['equip_test_id'] = ($test != '') ? $test : '0';


                $update = $this->eq->update_eqip($editData, $id);

                if ($update) {
                    $this->session->set_flashdata('success', 'Your Data is Updated Successfully');
                    $this->session->flashdata('success');
                    redirect("Equipment");
                } else {
                    $this->session->set_flashdata('error', 'unable to Update Data TRY AGAIN...');
                    $this->session->flashdata('error');
                    redirect("Equipment");
                }
            } else {
                $this->session->set_flashdata('warning', 'Duplicate Entry...');
                $this->session->flashdata('warning');
            }
        }
        $this->load_view('Equipment/edit', $arrData);
    }
    // This function is providing the validation to the edit form link is given in  the edit method by kamal singh on 22th of july 2022

    public function update_eqip_code()
    {
        $update_form = $this->input->post();
        $check_fileds = array();
        $check_fileds['LOWER(eqip_ID_no)'] = strtolower($update_form['equip_id']);
        $check_fileds['eqip_id NOT IN (' . $update_form['id'] . ')'] =  NULL;
        if (!empty($update_form) && !empty($update_form['id'])) {
            $check_in = $this->eq->get_row('*', 'eqip_equipments', $check_fileds);
            if ($check_in) {
                $this->form_validation->set_message('update_eqip_code', 'The {field} field can not be the same. "It Must be Unique!!"');
                return FALSE;
            } else {
                return TRUE;
            }
        } else {
            return false;
        }
    }

    // This function in the Quipment controller is working on ajax and sending data into database of maintANCE logs
    public function create_log()
    {


        $this->form_validation->set_rules('next_calib_log', ' Next Calibration Date', 'trim|required');
        $this->form_validation->set_rules('log_calib_period', 'Calibration Period', 'trim|required');
        $this->form_validation->set_rules('log_calib_time', 'Calibration Period Type', 'trim|required');
        $this->form_validation->set_rules('Last_calib_date_log', 'Last Calibration Date', 'trim|required');
        $this->form_validation->set_rules('Action_log', 'Calibration Action Date', 'trim|required');

        if ($this->form_validation->run() == true) {

            $next_date = $this->input->post('next_calib_log'); //next date
            $period = $this->input->post('log_calib_period'); //type of period
            $period_type = $this->input->post('log_calib_time'); //period
            $last_date = $this->input->post('Last_calib_date_log'); //last date
            $done_date = $this->input->post('Action_log'); //done date
            $note = ($this->input->post('note') != '') ? ($this->input->post('note')) : '0'; // note 
            $formal_Path = null;
            if (!empty($_FILES['image']['name'])) {
                $image_Name = $this->upload_instruction_img($_FILES);
                $formal_Path = send_Image_Database($image_Name[0]);
            }
            $eqip_id = $this->input->post('log_eqip_id'); //eqip id
            $data = array(
                'equip_id' => $eqip_id,
                'action_log' => "Calibration",
                'calibration_maintenance_last_date' => $last_date,
                'calibration_maintenance_done_date' => $done_date,
                'calibration_maintenance_period' => $period,
                'calibration_maintenance_period_type' => $period_type,
                'calibration_maintenance_next_date' => $next_date,
                'note' => $note,
                'attached_path' => $formal_Path != null ? $formal_Path : '0',
                'created_on' => date("Y-m-d h:i:s"),
                'created_by' => $this->session->userdata('user_data')->uidnr_admin,
            );
            $save = $this->db->insert('equipment_logs', $data);
            if ($save) {
                $this->session->set_flashdata('success', 'Added Sucessfully');
                $data = array(
                    'status' => 1,
                    'msg' => 'Log Added Successfully'
                );
                echo json_encode($data);
            } else {
                $this->session->set_flashdata('error', 'Unable To Add');
                $data = array(
                    'status' => 0,
                    'msg' => 'Error in Adding Log'
                );
                echo json_encode($data);
            }
        } else {
            $data = array(
                'status' => 3,
                'errors' => $this->form_validation->error_array(),
                'msg' => 'Please Fill All Required Fields'
            );
            echo json_encode($data);
        }
    }
    // This function in the Equipment controller is working on ajax and sending the data into database of calibration  logs 

    public function  create_main_log()
    {
        // form validation for the log of maintenance and calibration by kamal singh on 22th of july 2022
        $this->form_validation->set_rules('next_date_log', ' Next Maintenance Date', 'trim|required');
        $this->form_validation->set_rules('main_period_log', 'Maintenance Period', 'trim|required');
        $this->form_validation->set_rules('main_time_log', 'Maintenance Period Type', 'trim|required');
        $this->form_validation->set_rules('last_main_log', 'Last Maintenance Date', 'trim|required');
        $this->form_validation->set_rules('Action_main_log', 'Maintenance Action Date', 'trim|required');

        if ($this->form_validation->run() == true) {

            $next_date = $this->input->post('next_date_log'); //next date
            $period = $this->input->post('main_period_log'); //type of period
            $period_type = $this->input->post('main_time_log'); //period
            $last_date = $this->input->post('last_main_log'); //last date
            $done_date = $this->input->post('Action_main_log'); //done date
            $note = ($this->input->post('note2') != '') ? ($this->input->post('note2')) : '0'; // note 
            $formal_Path = null;

            if (!empty($_FILES['image_main']['name'])) {
                $image_Name = $this->eq->upload_Main_File($_FILES);
                $formal_Path = send_Image_Database($image_Name[0]);
            }
            $eqip_id = $this->input->post('main_eqip_id'); //eqip id
            $data = array(
                'equip_id' => $eqip_id,
                'action_log' => "Maintenance",
                'calibration_maintenance_last_date' => $last_date,
                'calibration_maintenance_done_date' => $done_date,
                'calibration_maintenance_period' => $period,
                'calibration_maintenance_period_type' => $period_type,
                'calibration_maintenance_next_date' => $next_date,
                'note' => $note,
                'attached_path' => $formal_Path != null ? $formal_Path : '0',
                'created_on' => date("Y-m-d h:i:s"),
                'created_by' => $this->session->userdata('user_data')->uidnr_admin,
            );
            $save = $this->db->insert('equipment_logs', $data);
            if ($save) {
                $this->session->set_flashdata('success', 'Added Sucessfully');
                $data = array(
                    'status' => 1,
                    'msg' => 'Log Added Successfully'
                );
                echo json_encode($data);
            } else {
                $this->session->set_flashdata('error', 'Unable To Add');
                $data = array(
                    'status' => 0,
                    'msg' => 'Error in Adding Log'
                );
                echo json_encode($data);
            }
        } else {
            $data = array(
                'status' => 3,
                'errors' => $this->form_validation->error_array(),
                'msg' => 'Please Fill All Required Fields'
            );
            echo json_encode($data);
        }
    }

    // get log function defined in ajax call for the ajax call for log view in index file in the equipment created by kamal singh 
    public function get_log_Data()
    {
        $id = $this->input->post('id');
        $data = $this->eq->id_wise_log($id);
        if (!empty($data))
            echo json_encode($data);
        else
            echo "";
    }
    // delete function defined in the ajax call for the delete function in index file created by kamal singh on 7th of july 
    public function delete_log()
    {
        $id = $this->input->post('log_id');
        $this->db->where('calibration_maintenance_id', $id)
            ->delete('equipment_logs');
        $this->session->set_flashdata('success', 'Log Deleted Successfully');
        $this->session->flashdata('success');
        echo "Log Deleted Successfully";
    }
}
