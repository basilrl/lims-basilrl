<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Manage_lab_model extends MY_Model
{

    function __construct()
    {
        parent::__construct();
    }
    public function accepted_sample_list($per_page = NULL, $page = 0,$where,$search,$count = NULL)
    {
        $this->db->limit($per_page, $page);
        if (count($where)>0) {
            $this->db->group_start();
            $this->db->where($where);
            $this->db->group_end();
        }
        if ($search) {
            $this->db->group_start();
            $this->db->like('LOWER(sr.gc_no)',strtolower($search));
            $this->db->or_like('LOWER(tr.trf_ref_no)',strtolower($search));
            $this->db->group_end();
        }
        $this->db->select("sr.sample_reg_id,st.sample_test_sample_reg_id,(SELECT count(st.sample_test_id) FROM sample_test st 
        WHERE st.sample_test_sample_reg_id = sr.sample_reg_id) AS sample_test_ids,sr.sample_desc,sr.gc_no,CASE WHEN tr.trf_service_type ='Regular' AND  ( tr.service_days IS NULL OR service_days='') THEN CONCAT(tr.trf_service_type,' 3 Days')
        WHEN tr.trf_service_type ='Express' THEN CONCAT(tr.trf_service_type,' 2 Days')
        WHEN tr.trf_service_type ='Urgent'  THEN CONCAT(tr.trf_service_type,' 1 Days')
        WHEN tr.service_days IS NOT NULL OR tr.service_days!='' THEN CONCAT(tr.trf_service_type,' ',tr.service_days,'Days') END AS sample_service_type, cc.customer_name as client,tr.trf_ref_no,mst.sample_type_name as product_name,sr.received_date,sr.status as sample_reg_status,sr.due_date,sr.qty_received,(SELECT count(st.sample_test_id) FROM sample_test st where st.status = 'In Progress' and st.sample_test_sample_reg_id = sr.sample_reg_id) as test_status");
        $this->db->join('trf_registration tr', 'tr.trf_id = sr.trf_registration_id', 'left');
        $this->db->join('cust_customers as cc', 'cc.customer_id = tr.trf_applicant', 'left');
        $this->db->join('mst_sample_types as mst', 'mst.sample_type_id = sr.sample_registration_sample_type_id', 'left');
        $this->db->join('sample_test st', 'st.sample_test_id = sr.sample_reg_id', 'left');
        //$this->db->group_start();
        //$this->db->where_in('sr.status', ['Sample Accepted', 'Evaluation Completed']);
        //$this->db->group_end();
        // $this->db->where_in('st.status', ['Sample Accepted', 'In Progress']);
        $this->db->order_by('sr.create_on', 'DESC');
        $this->db->from('sample_registration sr');

        $query = $this->db->get();
        if ($count) {
           return $query->num_rows();
        }else{
            if ($query->num_rows() > 0) {
                return $query->result();
            } else {
                return false;
            }
        }
    }


    public function record_count()
    {
        // $this->db->select("sr.sample_id,");
        $this->db->join('trf_registration tr', 'tr.trf_id = sr.trf_registration_id', 'left');
        $this->db->join('cust_customers as cc', 'cc.customer_id = tr.trf_applicant', 'left');
        $this->db->join('mst_sample_types as mst', 'mst.sample_type_id = sr.            sample_registration_sample_type_id', 'left');
        $this->db->join('sample_test st', 'st.sample_test_id = sr.sample_reg_id', 'left');
        $this->db->where('sr.status', 'Sample Accepted');
        $this->db->or_where('sr.status', 'Test as ReAssigned');
        $this->db->from('sample_registration sr');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->num_rows();
        } else {
            return false;
        }
    }
    public function check_record_finding($post){
       $query = $this->db->select('rfd.record_finding_id')->where(['sample_registration_id '=> $post['sample_registration_id'],'sample_test_id'=>$post['sample_test_id']])->get('record_finding_details rfd');
    
       if($query){
           return $query->row();
       }
       else{
           return false;
       }
    }
    public function get_assign_head($sample_reg_id)
    {
        $this->db->select("sr.gc_no,mst.sample_type_name as product_name");
        $this->db->join('mst_sample_types as mst', 'mst.sample_type_id = sr.            sample_registration_sample_type_id', 'left');
        $this->db->join('sample_test st', 'st.sample_test_id = sr.sample_reg_id', 'left');
        $this->db->where('sr.sample_reg_id', $sample_reg_id);
        $this->db->from('sample_registration sr');

        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->row_array();
        } else {
            return false;
        }
    }
    public function get_assign_test($sample_reg_id)
    {

        $this->db->select("st.sample_test_id,tests.test_name,tests.test_method,st.test_description,ml.lab_name,parts.part_name,ap.admin_fname,ap.admin_lname,tests.test_turn_around_time,st.assigned_to");

        $this->db->join('tests', 'st.sample_test_test_id = tests.test_id', 'left');
        $this->db->join('sample_registration sr', 'st.sample_test_sample_reg_id = sr.sample_reg_id', 'left');
        $this->db->join('mst_sample_types as mst', 'mst.sample_type_id = sr.            sample_registration_sample_type_id', 'left');
        $this->db->join('mst_labs ml', 'st.sample_test_assigned_lab_id = ml.lab_id', 'left');
        $this->db->join('parts', 'parts.part_id=st.sample_part_id', 'left');
        $this->db->join('admin_profile ap', 'ap.uidnr_admin=st.assigned_to', 'left');
        $this->db->where('sr.sample_reg_id', $sample_reg_id);
        //  $this->db->where('st.checked','true');
        $this->db->from('sample_test st');
        $query = $this->db->get();
        //  echo $this->db->last_query();die;
        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return false;
        }
    }



    public function get_analysis()
    {


        $this->db->select('ap.uidnr_admin AS id, CONCAT(ap.admin_fname ," ", ap.admin_lname) as name,ar.admin_role_name')->join('admin_users au','au.uidnr_admin = ap.uidnr_admin','left')->join('admin_role ar','ar.id_admin_role = au.id_admin_role','left')
        ->where_in('ar.admin_role_name',['Lab Manager','Lab Supervisor','Lab','Technician','Quality Analyst','Quality Assurance'])
            ->from('admin_profile as ap');
        $query = $this->db->get();
        // echo $this->db->last_query();die;
        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return false;
        }
    }



    public function SaveAssignTest($data)
    {
        // isset($data['assign_to'])
        $total = count($data['assign_to']);
        for ($i = 0; $i < $total; $i++) {
            $sample_id = $data['assign_to'][$i];
            $insert_data = array(
                'assigned_to' => $data['analysis'],
                'instruction_note' => $data['instruction_note'],
                'status' => 'In Progress'
            );
            $update =  $this->db->where('sample_test_id', $sample_id)->update('sample_test', $insert_data);
            // echo $this->db->last_query();die;
        }
        if ($update) {
            return true;
        } else {
            return false;
        }
    }


    public function record_finding_list($limit = NULL, $start = NULL,$where,$search,$count = null)
    {
        if (count($where)>0) {
            $this->db->group_start();
            $this->db->where($where);
            $this->db->group_end();
        }
        if ($search) {
            $this->db->group_start();
            $this->db->like('LOWER(sr.gc_no)',strtolower($search));
            $this->db->or_like('LOWER(tr.trf_ref_no)',strtolower($search));
            $this->db->group_end();
        }
        $where_in = ("(sr.status = 'Send For Record Finding' AND st.status = 'Mark As Completed By Lab' OR st.status='Record Enter Done') OR (sr.status = 'Retest' and st.status = 'Retest') OR (sr.status = 'Completed' and st.status != 'Completed')");
        $this->db->limit($limit, $start);
        $this->db->order_by('sr.lab_completion_date_time', 'DESC');
        $this->db->group_start();
        $this->db->where($where_in);
        $this->db->group_end();
        // $this->db->where('st.status <>', 'Completed');
        $this->db->select("DISTINCT(rfd.record_finding_id) as record_finding_id,sr.sample_desc,sr.gc_no,sr.sample_reg_id as sample_reg_id,st.sample_test_id as sample_test_id,
	   CASE WHEN tr.trf_service_type ='Regular' AND  ( tr.service_days IS NULL OR service_days='') THEN CONCAT(tr.trf_service_type,' 3 Days')
       WHEN tr.trf_service_type ='Express' THEN CONCAT(tr.trf_service_type,' 2 Days')
       WHEN tr.trf_service_type ='Urgent'  THEN CONCAT(tr.trf_service_type,' 1 Days')
	   WHEN tr.service_days IS NOT NULL OR tr.service_days!='' THEN CONCAT(tr.trf_service_type,' ',tr.service_days,'Days') END AS sample_service_type,
	   cc.customer_name as client,tr.trf_ref_no,mst.sample_type_name as product_name,sr.received_date,sr.status,
	   st.status as sample_test_status,sr.lab_completion_date_time,due_date,sr.qty_received,ts.test_name,ts.test_method,sr.seal_no");
        $this->db->from('sample_registration sr');
        $this->db->join('sample_test st', 'st.sample_test_sample_reg_id = sr.sample_reg_id', 'left');
        $this->db->join('record_finding_details as rfd', 'rfd.sample_test_id = st.sample_test_id','left');
        $this->db->join('trf_registration tr', 'tr.trf_id = sr.trf_registration_id', 'inner');
        $this->db->join('tests ts', 'st.sample_test_test_id = ts.test_id', 'left');
        $this->db->join('cust_customers as cc', 'cc.customer_id = tr.trf_applicant', 'left');
        $this->db->join('mst_sample_types as mst', 'mst.sample_type_id = sr.sample_registration_sample_type_id', 'left');
        $query = $this->db->get();
        // print_r($this->db->last_query());die;
        if ($count) {
           return $query->num_rows();
        } else {
            if ($query->num_rows() > 0) {
                return $query->result();
            } else {
                return false;
            }
        }
        
      
    }

    public function get_count_record($limit = NULL, $start = NULL)
    {

        $where = ("sr.status='Sample Accepted' AND st.status='In Progress'");
        $this->db->select("sr.sample_desc,sr.gc_no,
	  CASE WHEN tr.trf_service_type ='Regular' AND  ( tr.service_days IS NULL OR service_days='') THEN CONCAT(tr.trf_service_type,' 3 Days')
       WHEN tr.trf_service_type ='Express' THEN CONCAT(tr.trf_service_type,' 2 Days')
       WHEN tr.trf_service_type ='Urgent'  THEN CONCAT(tr.trf_service_type,' 1 Days')
	   WHEN tr.service_days IS NOT NULL OR tr.service_days!='' THEN CONCAT(tr.trf_service_type,' ',tr.service_days,'Days') END AS sample_service_type,
	   cc.customer_name as client,tr.trf_ref_no,mst.sample_type_name as product_name,sr.received_date,sr.status,
	   sr.due_date,sr.qty_received,ts.test_name,ts.test_method,sr.seal_no");
        $this->db->join('trf_registration tr', 'tr.trf_id = sr.trf_registration_id', 'left');
        $this->db->join('sample_test st', 'st.sample_test_sample_reg_id = sr.sample_reg_id', 'left');
        $this->db->join('tests ts', 'st.sample_test_test_id = ts.test_id', 'left');
        $this->db->join('cust_customers as cc', 'cc.customer_id = tr.trf_applicant', 'left');
        $this->db->join('mst_sample_types as mst', 'mst.sample_type_id = sr.sample_registration_sample_type_id', 'left');
        $this->db->where($where);
        $this->db->from('sample_registration sr');
        $this->db->order_by('sr.create_on', 'DESC');
        // $this->db->limit(10);  
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->num_rows();
        } else {
            return false;
        }
    }


    // public function get_record_data($data)
    // {

    //     $this->db->where('sr.sample_reg_id', $data['sample_reg_id']);
    //     $this->db->where('st.sample_test_id', $data['sample_test_id']);
    //     $this->db->select("sr.sample_desc,sr.gc_no,sr.sample_reg_id,
    //    CASE WHEN tr.trf_service_type ='Regular' AND (tr.service_days = NULL OR service_days='') THEN CONCAT(tr.trf_service_type,' 3 Days') WHEN tr.trf_service_type ='Express' THEN CONCAT(tr.trf_service_type,' 2 Days') WHEN tr.trf_service_type ='Urgent'  THEN CONCAT(tr.trf_service_type,' 1 Days')
    //    WHEN tr.service_days IS NOT NULL OR tr.service_days!='' THEN CONCAT(tr.trf_service_type,' ',tr.service_days,'Days') END AS sample_service_type,cc.customer_name as client,tr.trf_ref_no,mst.sample_type_name as product_name,sr.received_date,sr.status,
    //    sr.due_date,sr.qty_received,ts.test_name,ts.test_method,sr.seal_no");
    //     $this->db->from('sample_registration sr');
    //     $this->db->join('trf_registration tr', 'tr.trf_id = sr.trf_registration_id', 'left');
    //     $this->db->join('sample_test st', 'st.sample_test_sample_reg_id = sr.sample_reg_id', 'left');
    //     $this->db->join('tests ts', 'st.sample_test_test_id = ts.test_id', 'left');
    //     $this->db->join('cust_customers as cc', 'cc.customer_id = tr.trf_applicant', 'left');
    //     $this->db->join('mst_sample_types as mst', 'mst.sample_type_id = sr.sample_registration_sample_type_id', 'left');
    //     $query = $this->db->get();
    //     // print_r($this->db->last_query());die;
    //     if ($query->num_rows() > 0) {
    //         return $query->row();
    //     } else {
    //         return false;
    //     }
    // }
    public function get_sample_gc($data){
      $query =  $this->db->select("sr.gc_no,mst.sample_type_name as product_name")
        ->from('sample_registration sr')
        ->join('mst_sample_types as mst', 'mst.sample_type_id = sr.sample_registration_sample_type_id', 'left')
        ->where('sr.sample_reg_id',$data['sample_reg_id'])
        ->get();
        if ($query) {
           return $query->row();
        } else {
            return false;
        }
}

    public function get_parts($data)
    {
        // print_r($data);die;
        $query =  $this->db->select('group_concat(parts.part_name) as parts')
        ->join('sample_test st','st.sample_part_id = parts.part_id','left')
            ->from('parts')->where('st.sample_test_id', $data['sample_test_id'])->get();
            // echo $this->db->last_query();die;
        if ($query) {
           return $query->row();
        } else {
            return false;
        }
    }
    public function get_test($data)
    {
        $query = $this->db->select('test_name')
            ->join('tests', 'tests.test_id= st.sample_test_test_id', 'left')
            ->from('sample_test st')
            ->where('sample_test_id', $data['sample_test_id'])->get();
        if ($query) {
          return  $query->row();
        } else {
            return false;
        }
    }


    public function get_status($lab_data)
    {
        $query = $this->db->select('st.sample_test_id,st.status,st.sample_test_sample_reg_id')->from('sample_test as st')->join('sample_registration sr', 'sr.sample_reg_id = st.sample_test_sample_reg_id', 'left')->where('sample_reg_id', $lab_data['id'])->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return false;
        }
    }
    public function lab_completion($lab_data)
    {
        $result =  $this->db->where('sample_reg_id', $lab_data['id'])->update('sample_registration', $lab_data['data']);
        // print_r($this->db->last_query());

        if ($result) {
            $status = $this->db->where('sample_test_sample_reg_id', $lab_data['id'])->update('sample_test', array('status' => "Mark As Completed By Lab"));
            if ($status) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }

        // print_r($this->db->last_query());die;
    }


    public function report_generation($post)
    {
        $result = $this->db->insert('record_finding_details', $post);
        // echo $this->db->last_query();die;

        if ($result) {
            $last_insert_id = $this->db->insert_id();

            $status = $this->db->where('sample_test_id', $post['sample_test_id'])->update('sample_test', array('status' => 'Record Enter Done'));
            if ($status) {
                return $last_insert_id;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
    public function mark_completion($id)
    {
        $status = $this->db->where('sample_test_id', $id['sample_test_id'])->update('sample_test', array('status' => 'Completed'));
        $status = $this->db->where('sample_reg_id', $id['sample_reg_id'])->update('sample_registration', array('status' => 'Completed'));
        if ($status) {
            return true;
        } else {
            return false;
        }
    }
    public function edit_record_finding($data)
    {
        // print_r($data);die;
        $query =  $this->db->select('record_finding_details.*,scld.lab_name')
            ->join('sub_contract_lab_details as scld', 'scld.lab_details_id=record_finding_details.lab_id', 'left')
            ->where('record_finding_details.record_finding_id', $data['record_finding_id'])
            ->where('record_finding_details.sample_registration_id', $data['sample_reg_id'])
            ->from('record_finding_details')->get();
        //   echo $this->db->last_query();die;
        if ($query) {
            return $query->row_array();
        } else {
            return false;
        }
    }
    public function update_record_finding($update_data)
    {
        // print_r($data);die;
        $query =  $this->db->where('record_finding_id', $update_data['id'])->update('record_finding_details', $update_data['update_data']);
        if ($query) {
            return true;
        } else {
            return false;
        }
    }

    public function report_listing($limit = NULL, $start = NULL,$where,$search,$count = null)
    {
        $this->db->limit($limit, $start);
        if (count($where)>0) {
            $this->db->group_start();
            $this->db->where($where);
            $this->db->group_end();
        }
        if ($search) {
            $this->db->group_start();
            $this->db->like('LOWER(sr.gc_no)',strtolower($search));
            $this->db->or_like('LOWER(tr.trf_ref_no)',strtolower($search));
            $this->db->group_end();
        }
        $where_in = ("(sr.status = 'Send For Record Finding' AND st.status = 'Completed') OR sr.status = 'Report Generated' OR sr.status = 'Report Approved' OR sr.status = 'Completed' ");
        // $this->db->limit($limit, $start);
        $this->db->order_by('sr.lab_completion_date_time', 'DESC');
        $this->db->group_start();
        $this->db->where($where_in);
        $this->db->group_end();
        // $this->db->where('st.status <>', 'Completed');
        $this->db->select("sr.ulr_no,gr.signing_authority,gr.sign_authority_new,sr.sample_desc,sr.gc_no,sr.sample_reg_id as sample_reg_id,st.sample_test_id as sample_test_id,
	   CASE WHEN tr.trf_service_type ='Regular' AND  ( tr.service_days IS NULL OR service_days='') THEN CONCAT(tr.trf_service_type,' 3 Days')
       WHEN tr.trf_service_type ='Express' THEN CONCAT(tr.trf_service_type,' 2 Days')
       WHEN tr.trf_service_type ='Urgent'  THEN CONCAT(tr.trf_service_type,' 1 Days')
	   WHEN tr.service_days IS NOT NULL OR tr.service_days!='' THEN CONCAT(tr.trf_service_type,' ',tr.service_days,'Days') END AS sample_service_type,
	   cc.customer_name as client,tr.trf_ref_no,mst.sample_type_name as product_name,sr.received_date,sr.status,
	   st.status as sample_test_status,sr.lab_completion_date_time,due_date,sr.qty_received,ts.test_name,ts.test_method,sr.seal_no,gr.report_id,gr.manual_report_file,gr.report_num,sp.image_id, COUNT(st.sample_test_sample_reg_id) as total_record_finding , (select COUNT(str.sample_test_sample_reg_id) from sample_test str where (str.status = 'Completed'  or str.status = 'Completed Sample') and (str.sample_test_sample_reg_id = sr.sample_reg_id)) as status_count");
        $this->db->from('sample_registration sr');
        $this->db->join('trf_registration tr', 'tr.trf_id = sr.trf_registration_id', 'inner');
        $this->db->join('sample_test st', 'st.sample_test_sample_reg_id = sr.sample_reg_id', 'inner');
        $this->db->join('tests ts', 'st.sample_test_test_id = ts.test_id', 'left');
        $this->db->join('cust_customers as cc', 'cc.customer_id = tr.trf_applicant', 'left');
        $this->db->join('mst_sample_types as mst', 'mst.sample_type_id = sr.sample_registration_sample_type_id', 'left');
        $this->db->join('sample_photos as sp', 'sp.sample_reg_id = sr.sample_reg_id', 'left');
        $this->db->join('generated_reports as gr', 'gr.sample_reg_id = sr.sample_reg_id AND (gr.additional_report_flag <> 1 AND  gr.revise_report <> "1" )', 'left');
        $this->db->group_by('sr.sample_reg_id');
        $query = $this->db->get();
        // print_r($this->db->last_query());die;
        if ($count) {
            return $query->num_rows();
        } else {
            if ($query->num_rows() > 0) {
                return $query->result();
            } else {
                return false;
            }
        }
        
    }


   public function Final_report_generate($data)
    {

        $query = $this->db->where('sample_reg_id', $data['sample_reg_id'])->update('sample_registration', array('status' => 'Report Generated', 'issuance_date' => $data['issuance_date'], 'remark' => htmlentities($data['remark']),'ulr_no_flag' => $data['ulr_no_flag'],'manual_report_result' => $data['report_result'],'manual_report_remark' => $data['report_remark'],'sample_images_flag' => $data['sample_image_flag']));
        // print_r($this->db->last_query());die;
        // if ($query) {
        //     $status = $this->db->select('cc.customer_name as customer_name,cc.address,contacts.contact_name,sr.sample_desc,sr.report_template_type,sr.received_date,msc.country_name,rfd.record_finding_id,rfd.test_result,sr.remark,rfd.test_component,rfd.test_display_name,ts.test_name,sr.ulr_no,sr.gc_no,sr.ilac_logo,sr.report_template_type,gr.generated_date,cc.customer_name as buyer_name,cc.customer_name as agent_name,tr.trf_end_use,trco.country_name as country_origin,ts.test_method,sr.issuance_date,gr.report_num,tr.trf_id,tr.product_custom_fields')
        //         ->join('trf_registration tr', 'tr.trf_id = sr.trf_registration_id', 'left')
        //         ->join('cust_customers trb', 'tr.trf_buyer = trb.customer_id AND trb.customer_type = "Buyer"', 'left')
        //         ->join('cust_customers tra', 'tr.trf_agent = tra.customer_id AND tra.customer_type = "Agent"', 'left')
        //         ->join('mst_country trco', 'tr.trf_country_orgin = trco.country_id', 'left')
        //         ->join('cust_customers cc', 'tr.trf_applicant = cc.customer_id', 'left')
        //         ->join('contacts', 'contacts.contact_id = cc.customer_id', 'left')
        //         ->join('mst_country msc', 'msc.country_id = tr.trf_country_destination', 'left')
        //         ->join('record_finding_details rfd', 'rfd.sample_registration_id = sr.sample_reg_id', 'left')
        //         ->join('sample_test st', 'st.sample_test_sample_reg_id = sr.sample_reg_id', 'left')
        //         ->join('generated_reports as gr', 'gr.sample_reg_id = sr.sample_reg_id', 'left')

        //         ->join('tests ts', 'st.sample_test_test_id = ts.test_id', 'left')
        //         ->join('report_generated_images rgi', 'rgi.record_finding_id = rfd.record_finding_id', 'left')

        //         ->where('sr.sample_reg_id', $data['sample_reg_id'])
        //         ->from('sample_registration sr')->get();
        //     // echo $this->db->last_query();die;
            if ($query) {
                return true;
            } else {
                return false;
            }
       
    }
//      public function getNABLCpsResultData($select,$sample_reg_id)
//     {
//             $this->db->group_start();
//             $this->db->where('nabl_headings !=','');
//            // $this->db->where('nabl_values !=','');
//             $this->db->group_end();
           
//             $this->db->or_where('nabl_detail !=', '');
//             $this->db->or_where('nabl_remark !=', '');
            
       
//         $query = $this->db->select($select)->where('sample_registration_id', $sample_reg_id)->get('record_finding_details rfd');
//         //echo $this->db->last_query(); exit;
//         if ($query->num_rows()) {
//             return $query->result_array();
//         } else {
//             return false;
//         }
//     }
//      public function getNONNABLCpsResultData($select,$sample_reg_id)
//   {   
//             $this->db->group_start();
//             $this->db->where('non_nabl_headings !=','');
//            // $this->db->where('non_nabl_values !=','');
//             $this->db->group_end();
             
//             $this->db->or_where('non_nabl_detail !=', '');
//             $this->db->or_where('non_nabl_remark !=', '');
            
         
//         $query = $this->db->select($select)->where('sample_registration_id', $sample_reg_id)->get('record_finding_details rfd');
//         if ($query->num_rows()) {
//             return $query->result_array();
//         } else {
//             return false;
//         }
//     }


 public function getNABLCpsResultData($select,$sample_reg_id)
    {
            $this->db->where('sample_registration_id', $sample_reg_id);
            $this->db->group_start();
             $this->db->group_start();
            $this->db->where('nabl_detail !=', '');
            $this->db->or_where('nabl_remark !=', '');
             $this->db->group_end();
            $this->db->or_where('nabl_headings !=','');          
           
            
             $this->db->group_end();
        $query = $this->db->select($select)->get('record_finding_details rfd');
       //echo $this->db->last_query(); exit;
        if ($query->num_rows()) {
            return $query->result_array();
        } else {
            return false;
        }
    }
     public function getNONNABLCpsResultData($select,$sample_reg_id)
  {   
           
              $this->db->where('sample_registration_id', $sample_reg_id);
            $this->db->group_start();
             $this->db->group_start();
            $this->db->where('non_nabl_detail !=', '');
            $this->db->or_where('non_nabl_remark !=', '');
             $this->db->group_end();
            $this->db->or_where('non_nabl_headings !=','');          
           
            
             $this->db->group_end();
            
         
        $query = $this->db->select($select)->get('record_finding_details rfd');
        if ($query->num_rows()) {
            return $query->result_array();
        } else {
            return false;
        }
    }
    public function get_cps_data($select,$sample_reg_id)
    {
        $query = $this->db->select($select)->where('sample_registration_id', $sample_reg_id)->get('record_finding_details rfd');
        if ($query->num_rows()) {
            return $query->result_array();
        } else {
            return false;
        }
    }
    public function get_record_finding_id($id)
    {
        $query = $this->db->select('rfd.record_finding_id')
            ->from('record_finding_details rfd')
            ->where('rfd.sample_registration_id', $id)
            ->get();
        if ($query->num_rows() > 0) {

            return $query->result();
        } else {
            return false;
        }
    }
    public function get_application_care($update){
       $query = $this->db->select('aci.instruction_image,if(tai.description!="",tai.description,aci.care_wording) as instruction_name')
        ->join('trf_registration tr', 'tr.trf_id = sr.trf_registration_id', 'left')
        ->join('trf_apc_instruction tai', 'tai.trf_id = tr.trf_id', 'left')
        ->join('application_care_instruction aci', 'tai.application_care_id = aci.instruction_id', 'left')
        ->where('tai.trf_id', $update['report_data']->trf_id)
        ->where('tai.image is NOT NULL', NULL, FALSE)
        ->order_by('tai.image_sequence','ASC')
        ->from('sample_registration sr')->get();
        // echo $this->db->last_query();die;
       if($query->num_rows()>0){
           return $query->result();
       }
       else{
           return false;
       }
    }
    public function get_images($record_finding_id)
    {

        $query =  $this->db->select('image_path')
            ->from('report_generated_images rgi')
            ->where('rgi.record_finding_id', $record_finding_id)
            ->get();
        // echo $this->db->last_query();die;
        if ($query) {
            return $query->result_array();
        } else {
            return false;
        }
    }

    public function get_test_result($data)
    {
        $query =   $this->db->select('distinct(rfd.test_display_name),ts.test_name,rfd.sample_test_id,ts.test_name as test_name,rfd.test_result,rfd.test_component,sr.sample_reg_id,ts.test_method')
            ->join('tests ts', 'st.sample_test_test_id = ts.test_id')
            ->join('sample_registration sr', 'st.sample_test_sample_reg_id = sr.sample_reg_id')

            ->join('record_finding_details rfd', 'rfd.sample_registration_id = sr.sample_reg_id')
            // ->join('record_finding_details rfd', 'rfd.sample_test_id = st.sample_test_id')
            ->from('sample_test st')
            ->group_by('rfd.sample_test_id')
            ->where('rfd.sample_registration_id', $data['sample_reg_id'])
            ->get();
        // echo $this->db->last_query();die;
        if ($query->num_rows() > 0) {
            return  $query->result();
        } else {
            return false;
        }
    }

    public function pdf_data_get($data)
    {
        $status = $this->db->select('cc.customer_name as customer_name,cc.address,contacts.contact_name,sr.sample_desc,sr.report_template_type,sr.received_date,msc.country_name as destination,rfd.record_finding_id,rfd.test_result,rfd.test_component,rfd.test_display_name,ts.test_name,ts.test_method,sr.ulr_no,sr.gc_no,sr.ilac_logo,sr.report_template_type,gr.generated_date,trb.customer_name as buyer_name,tra.customer_name as agent_name,tr.trf_id,tr.trf_end_use,trco.country_name as country_origin,sr.issuance_date,sr.remark,gr.report_num,tr.product_custom_fields,gr.report_id,sr.sample_images_flag,sr.ulr_no_flag,mb.branch_name,sr.sample_images_flag')
            ->join('trf_registration tr', 'tr.trf_id = sr.trf_registration_id', 'left')
            ->join('cust_customers trb', 'tr.trf_buyer = trb.customer_id AND trb.customer_type = "Buyer"', 'left')
            ->join('cust_customers tra', 'tr.trf_agent = tra.customer_id AND tra.customer_type = "Agent"', 'left')
            ->join('mst_country trco', 'tr.trf_country_orgin = trco.country_id', 'left')

            ->join('cust_customers cc', 'tr.trf_applicant = cc.customer_id', 'left')
            ->join('contacts', 'contacts.contact_id = tr.trf_contact', 'left')
            ->join('mst_country msc', 'msc.country_id = tr.trf_country_destination', 'left')
            ->join('record_finding_details rfd', 'rfd.sample_registration_id = sr.sample_reg_id', 'left')
            ->join('sample_test st', 'st.sample_test_sample_reg_id = sr.sample_reg_id', 'left')
            ->join('mst_branches mb', 'mb.branch_id = sr.sample_registration_branch_id', 'left')
            ->join('tests ts', 'st.sample_test_test_id = ts.test_id', 'left')
            ->join('generated_reports as gr', 'gr.sample_reg_id = sr.sample_reg_id', 'left')
            ->group_by('sr.sample_reg_id')
            ->where('sr.sample_reg_id', $data['sample_reg_id'])
            ->where('gr.report_id', $data['report_id'])
            ->from('sample_registration sr')->get();
        // echo $this->db->last_query();die;
        if ($status) {
            return $status->row();
        } else {
            return false;
        }
    }
    public function report_generate_data($report_data)
    {
        $query = $this->db->insert('generated_reports', $report_data);
        if ($query) {
            return $this->db->insert_id();
        } else {
            return false;
        }
    }

    public function regenerate_sample($sample_reg_id, $report_id)
    {
        // echo $sample_reg_id;die;
        $data = array('status' => 'Retest', 'revise_flag' => '1');
        $rep_data = array('revise_report' => '1');
        $update_sample = $this->db->where('sample_reg_id', $sample_reg_id)
            ->update('sample_registration', $data);
        $update_report = $this->db->where('report_id', $report_id)
            ->update('generated_reports', $rep_data);  

        if ($update_sample) {
            $data = array('status' => 'Retest');
            $update_test = $this->db->where('sample_test_sample_reg_id', $sample_reg_id)
                ->update('sample_test', $data);

            if ($update_sample) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
        // print_r($this->db->last_query());die;
    }
    public function additional_test($sample_reg_id, $report_id)
    {
        // echo $sample_reg_id;die;
        $data = array('status' => 'Sample Sent for Evaluation', 'additional_flag' => '1');
        $rep_data = array('additional_report_flag' => '1');
        $update_sample = $this->db->where('sample_reg_id', $sample_reg_id)
            ->update('sample_registration', $data);
        $update_report = $this->db->where('report_id', $report_id)
            ->update('generated_reports', $rep_data);  

        if ($update_sample) {
            $data = array('status' => 'New');
            $update_test = $this->db->where('sample_test_sample_reg_id', $sample_reg_id)
                ->update('sample_test', $data);

            if ($update_sample) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
        // print_r($this->db->last_query());die;
    }
    public function get_report_approver(){
     $query =  $this->db->select('ap.admin_fname,ap.admin_lname,au.uidnr_admin')
                  ->from('admin_profile ap')
                  ->join('admin_users au','au.uidnr_admin=ap.uidnr_admin')
                  ->join('admin_role ar','ar.id_admin_role=au.id_admin_role')
                  ->where_in('ap.ap_signing_auth','1')
                  ->get();
// echo $this->db->last_query();die;
                  if($query->num_rows()>0){
                      return $query->result();
                  }else{
                      return false;
                  }

    }
    public function getsignvalues($data){
        $query =  $this->db->select('CONCAT(ap.admin_fname,"",ap.admin_lname) as name ,au.uidnr_admin,ar.admin_role_name')
        ->from('admin_profile ap')
        ->join('admin_users au','au.uidnr_admin=ap.uidnr_admin')
        ->join('admin_role ar','ar.id_admin_role=au.id_admin_role')
        ->where('au.uidnr_admin',$data)
        ->get();
       if($query){
           return $query->row();
       }
       else{
           return false;
       }
    }
    public function sample_final_images($data){
        $query =  $this->db->select('sp.*')
        ->from('sample_registration sr')
        ->join('sample_photos sp','sp.sample_reg_id=sr.sample_reg_id')
        ->where('sr.sample_reg_id',$data['sample_reg_id'])
                ->order_by('image_sequence','asc')
        ->get();
// echo $this->db->last_query();die;
if ($query->num_rows()) {
    return $query->result();
} else {
    return false;
}
    }

    public function send_to_record_finding($id){
  
 $update_sample = $this->db->where('sample_reg_id', $id)->update('sample_registration', array('status' => 'Send For Record Finding'));
//  echo ($this->db->last_query());die;
 if ($update_sample) {
    //  echo "dkjhd vmdf nvd";die;
     $data = array('status' => 'Record Enter Done');
     $update_test = $this->db->where('sample_test_sample_reg_id', $id)
         ->update('sample_test', $data);

     if ($update_sample) {
         return true;
     } else {
         return false;
     }
 } else {
     return false;
 }
    }

    public function remove_report_sample_image($image_id){
        $delete = $this->db->delete('sample_photos',['image_id' => $image_id]);
        if($delete){
            return true;
        } else {
            return false;
        }
    }

    public function update_comment($inputs,$image_id){
        $update = $this->update_data("sample_photos",$inputs,["image_id" => $image_id]);
        if($update){
            return true;
        }
        return false;
    }
    public function get_generate_report_data($ids){
      $query =  $this->db->select('sr.ulr_no_flag,sr.issuance_date,sr.remark,sr.manual_report_result,sr.manual_report_remark,sr.sample_images_flag,gr.signing_authority,ap.admin_fname as name')->from('sample_registration sr')->where('sr.sample_reg_id',$ids['sample_reg_id'])->join('generated_reports gr','gr.sample_reg_id = sr.sample_reg_id','left')->join('admin_profile ap','ap.uidnr_admin = gr.signing_authority','left')->get();
      if($query){
           return $query->row();
      }
      else{
          return false;
      }
    }

    public function get_approver_email($data)
    {
        $query = $this->db->select('ap.admin_email as email')
            ->from('admin_users ap')
            ->where('ap.uidnr_admin', $data)
            ->get();
        if ($query) {
            return $query->row();
        } else {
            return false;
        }
    }
}
