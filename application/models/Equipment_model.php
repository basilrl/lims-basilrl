<?php

// Aws path for uploading the image path to s3 bucket on upload_main_image created by kamal singh on 6th of july 2022
use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;

// MODEL CLASS FOR APPLICATION CARE INSTRUCTION BY KAMAL ON 6TH JUNE 2022
class Equipment_model extends My_Model
{

    public function __construct()
    {
        $this->load->database('default');
        $this->load->library('session');
        // Call the Model constructor
        parent::__construct();
    }
    public function get_All_Equipment_List($search, $start, $end, $count = true, $sortby = null, $order = null)
    {
        if ($sortby != NULL || $order != NULL) {
            $this->db->order_by($sortby, $order);
        } else {
            $this->db->order_by('eqip_id', 'desc');
        }

        $this->db->select('*')
            ->from('eqip_equipments');

        ($search['eqip_name'] != 'NULL') ? $this->db->like('eqip_name', $search['eqip_name']) : '';
        ($search['eqip_ID_no'] != 'NULL') ? $this->db->like('eqip_ID_no', $search['eqip_ID_no']) : '';
        ($search['model'] != 'NULL') ? $this->db->like('model', $search['model']) : '';
        ($search['make'] != 'NULL') ? $this->db->like('make', $search['make']) : '';
        ($search['serial_no'] != 'NULL') ? $this->db->like('serial_no', $search['serial_no']) : '';
        ($search['calib_by'] != 'NULL') ? $this->db->like('calibrated_by', $search['calib_by']) : '';
        ($search['division'] != 'NULL') ? $this->db->like('division', $search['division']) : '';
        ($search['due_date'] != 'NULL') ? $this->db->like('calibration_due_date', $search['due_date']) : '';
        ($search['calib_date'] != 'NULL') ? $this->db->like('calibration_date', $search['calib_date']) : '';
        ($search['certi_no'] != 'NULL') ? $this->db->like('calibration_certificate_number', $search['certi_no']) : '';
        // ($search['start_date'] != 'NULL' && $search['end_date'] != 'NULL') ?
        //     $this->db->where('date(created_on) >=', $search['start_date'])->where('date(created_on) <=', $search['end_date'])
        //     : '';
        if (!$count) {
            $this->db->limit($start, $end);
        }
        $objQuery = $this->db->get();
        if ($count) {
            return $objQuery->num_rows();
        }

        if ($objQuery->num_rows() > 0) {
            return $objQuery->result_array();
        }
        return [];
    }
    // This is insert function for inserting the equipment data into the database on 22th of july 2022 by kamal
    public function insert_equip($arrData)
    {
        $this->db->insert('eqip_equipments', $arrData);
        if ($arrData['eqip_ID_no'] != null) {
            return true;
        } else {
            return false;
        }
    }

    public function get_row($col = '*', $table, $where)
    {
        $this->db->select($col)
            ->from($table)
            ->where($where);
        $result = $this->db->get();
        if ($result->num_rows() > 0) {
            return $result->num_rows();
        } else {
            return false;
        }
    }

    // This function is updating the data by kamal singh on 22th os july 2022
    public function update_eqip($editData, $id)
    {
        $this->db->where('eqip_id', $id);

        $obj = $this->db->update('eqip_equipments', $editData);
        if ($obj != null) {
            return true;
        } else {

            return false;
        }
    }

    // This method is getting id wise from database for equipment by kamal singh on 22th of july 2022
    public function get_id_wise_equipment($id)
    {
        $this->db->select('*');
        $this->db->from('eqip_equipments');
        $this->db->where('eqip_id', $id);
        $objQuery = $this->db->get();
        return $objQuery->row_array();
    }


    // fetching created person name for dropdown in the equipment by kamal 
    public function fetch_created_person()
    {
        $query = $this->db->select('CONCAT(ap.admin_fname, " ", ap.admin_lname) as created_by, ap.uidnr_admin')
            ->from('admin_profile ap')
            ->order_by('ap.admin_fname', 'asc')
            ->get();

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }
    
    // fetching the branch name list for the dropdown in equipment
    public function fetch_branch_name()
    {
        $query = $this->db->select('branch_name as branch , br.branch_id')
            ->from('mst_branches br')
            ->order_by('br.branch_name', 'asc')
            ->get();
        if ($query->num_rows() > 0) {

            return $query->result();
        } else {
            return false;
        }
    }
    // Fetching the test data for dropdown in the equipment
    public function fetch_test_name()
    {
        $query = $this->db->select('test_name as test , te.test_id')
            ->from('tests te')
            ->order_by('te.test_name', 'asc')
            ->get();
        if ($query->num_rows() > 0) {

            return $query->result();
        } else {
            return false;
        }
    }

    // Fetching the division name from the division table form database for dropdwon in equipmemt
    public function fetch_division_name()
    {
        $query = $this->db->select('division_name as division , de.division_id')
            ->from('mst_divisions de')
            ->order_by('de.division_name', 'asc')
            ->get();
        // echo $this->db->last_query(); die;
        if ($query->num_rows() > 0) {

            return $query->result();
        } else {
            return false;
        }
    }

    // This function is working on upload attachfile for maintenance log created by kamal singh on 6th of july 2022
    public function upload_Main_File($file_name)
    {
        require '../vendor/autoload.php';
        $s3 = S3Client::factory(
            array(
                'credentials' => array(
                    'key' => SECRET_ACCESS_KEY,
                    'secret' => SECRET_ACCESS_CODE
                ),
                'version' => 'latest',
                'region' => 'ap-south-1', //write your region name
                'signature' => 'v4',
            )
        );
        if (!empty($file_name)) {
            $data = array();
            $countfiles = is_array($file_name);

            if ($countfiles) {
                for ($i = 0; $i < count($file_name); $i++) {
                    $file_temp = $file_name['image_main']['tmp_name'];
                    $filter_image_name = sanitizeFileName($file_name['image_main']['name']);
                    $path_parts = pathinfo($filter_image_name);

                    //filename
                    $fName = $path_parts['filename'];
                    //file extension    

                    $fExtension = $path_parts['extension'];
                    $file_type = $fExtension;
                    $image = $fName . '-' . date('d-M') . '-' . rand(1000, 9999) . '.' . $fExtension;
                    $keyName = 'ci_lims/newsletter/' . DATE('d_m_Y') . '/' . $image;

                    try {
                        $result = $s3->putObject(
                            array(
                                'Bucket' => BUCKETNAME,
                                'Key' => $keyName,
                                'Body' => fopen($file_temp, 'r+'),
                                'ContentType' => $file_type,
                                'ACL' => 'public-read',
                                'SourceFile' => $file_temp,
                                'StorageClass' => 'STANDARD'
                            )
                        );

                        if ($result['ObjectURL']) {
                            // unlink($filePath);
                            //$data= $result['ObjectURL'];
                            $data[$i] = $result['ObjectURL'];
                        }
                    } catch (S3Exception $e) {
                        echo $this->session->set_flashdata('error', $e->getMessage());
                        echo $e->getMessage();
                        exit;
                        return false;
                    } catch (Exception $e) {
                        echo $e->getMessage();
                        exit;
                        echo $this->session->set_flashdata('error', $e->getMessage());
                        return false;
                    }
                }

                return $data;
            } else {

                $file_temp = $file_name['tmp_name'];
                $filter_image_name = sanitizeFileName($file_name['name']);
                $path_parts = pathinfo($filter_image_name);
                //filename
                $fName = $path_parts['filename'];
                //file extension
                $fExtension = $path_parts['extension'];
                $file_type = $fExtension;
                $image = $fName . '-' . date('d-M') . '-' . rand(1000, 9999) . '.' . $fExtension;
                $keyName = 'ci_lims/newsletter/' . date('d_m_Y') . '/' . $image;

                try {
                    $result = $s3->putObject(
                        array(
                            'Bucket' => BUCKETNAME,
                            'Key' => $keyName,
                            'Body' => fopen($file_temp, 'r+'),
                            'ContentType' => $file_type,
                            'ACL' => 'public-read',
                            'SourceFile' => $file_temp,
                            'StorageClass' => 'STANDARD'
                        )
                    );

                    if ($result['ObjectURL']) {
                        // unlink($filePath);
                        $data['aws_path'] = $result['ObjectURL'];
                        return $data;
                    }
                } catch (S3Exception $e) {
                    echo $this->session->set_flashdata('error', $e->getMessage());
                    echo $e->getMessage();
                    exit;
                    return false;
                } catch (Exception $e) {
                    echo $this->session->set_flashdata('error', $e->getMessage());
                    echo $e->getMessage();
                    exit;
                    return false;
                }
                return false;
            }
        }
    }
    // This function is getting the log data from the equipment_logs table from the database created  by kamal 
    public function id_wise_log($id)
    {
        $this->db->select('eq.calibration_maintenance_id as log_id,eq.action_log as action,eq.calibration_maintenance_last_date as last_date,eq.calibration_maintenance_done_date as action_date,
        CONCAT(eq.calibration_maintenance_period ," ",eq.calibration_maintenance_period_type) as period,
        eq.calibration_maintenance_next_date as next_date');
        $this->db->from('equipment_logs as eq');
        $this->db->where('equip_id', $id)->order_by('created_on', 'desc');
        $objQuery = $this->db->get();
        return $objQuery->result_array();
    }
}
