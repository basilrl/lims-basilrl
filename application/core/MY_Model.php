<?php  
/**
 * 
 */

use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;

class MY_Model extends CI_Model
{
	
	function __construct(){
		parent::__construct();
	}

	public function get_data_by_id_array($table, $column = '*', $id, $column_name)
	{
		if($id != NULL){
			$this->db->select($column);
		$query = $this->db->get_where($table, [$column_name => $id]);
		// echo $this->db->last_query(); die;
		if ($query->num_rows() > 0) {
			return $query->row_array();
		}
		return [];
		}
	}

	public function save_user_log($log_data){
		
		// Save log detail
		$module = $log_data['module'];

		// Switch case to check
		switch($module){
			// save jobs data
			case 'jobs' :
			$this->db->insert('jobs_activity_log',$log_data);
			break;

			case 'Samples' :
			$this->db->insert('sample_reg_activity_log',$log_data);
			break;

			case 'Laboratory' :
			$this->db->insert('sample_test_log',$log_data);
			break;

			case 'Lab Report' :
			$this->db->insert('report_log',$log_data);
			break;

			case 'Store' :
			$this->db->insert('store_log',$log_data);
			break;

			case 'Currency' :
			$this->db->insert('currency_activity_log',$log_data);
			break;

			case 'sales' :
			$this->db->insert('sales_activity_log',$log_data);
			break;

			case 'Holiday' :
			$this->db->insert('holiday_activity_log',$log_data);
			break;

			case 'Credit Note' :
			$this->db->insert('credit_note_log',$log_data);
			break;
			default:
			break;
		}
	}

	public function get_products(){
		$query = $this->db->select('sample_type_id,sample_type_name')
						  ->from('mst_sample_types')
						  ->order_by('sample_type_name','asc')
						  ->get();
						  
		if ($query->num_rows() > 0) {
				return $query->result_array();
			}	
			return [];
	}

	public function admin_id(){
        $checkUser = $this->session->userdata('user_data');
        return $checkUser->uidnr_admin;
    }

	public function fetch_all_data($table){
		$query = $this->db->get($table);
		if($query->num_rows() > 0){
			return $query->result_array();
		}
		return [];
	}

	public function get_data_by_id($table,$id,$column_name){
		$query = $this->db->get_where($table,[$column_name => $id]);
		// echo $this->db->last_query(); exit();
		if ($query->num_rows() > 0) {
			return $query->row();
		}
		return [];
	}

	public function get_fields_by_id($table,$columns,$id,$where){
		$query = $this->db->select($columns)
					  ->from($table)
					  ->where($where,$id)
					  ->get();
		if($query->num_rows() > 0){
			return $query->result_array();
		}
		return [];
	}

	public function get_fields($table,$columns){
		$query = $this->db->select($columns)
						  ->from($table)
						  ->get();
		if($query->num_rows() > 0){
			return $query->result_array();
		}
		return [];
	}

	public function fetch_country(){
        $this->db->select('country_id, country_name');
        $query = $this->db->get('mst_country');
        if ($query->result())
            return $query->result();
        else
            return false;
	}
	
	public function get_timezone(){
        $this->db->order_by('time_zone_label','ASC');
        $this->db->select('time_zone_id, time_zone_label');
        $query = $this->db->get('time_zone');
        if ($query->result())
            return $query->result();
        else
            return false;
	}
	
	public function fetch_roles(){
        $this->db->order_by('admin_role_name','ASC');
        $this->db->select('id_admin_role, admin_role_name');
        $query = $this->db->get('admin_role');
        if ($query->result())
            return $query->result();
        else
            return false;
	}
	
	public function fetch_crm(){
        $this->db->order_by('name','ASC');
        $this->db->where('crm_flag','1');
        $this->db->select("admin_profile.uidnr_admin, concat(admin_fname,'',admin_lname) as name");
         $this->db->from("admin_users");
          $this->db->join("admin_profile","admin_profile.uidnr_admin=admin_users.uidnr_admin","left");
        
        $query = $this->db->get();
        if ($query->result())
            return $query->result();
        else
            return false;
	}
	
	public function get_default_division(){
        $this->db->where('status','1');
        $this->db->order_by('division_name','ASC');
        $this->db->select('division_id, division_name');
        $query = $this->db->get('mst_divisions');
        if ($query->result())
            return $query->result();
        else
            return false;
              
	}
	
	public function fetch_department(){ 
        $this->db->where('status','1');
        $this->db->order_by('dept_name','ASC');
        $this->db->select('dept_id, dept_name');
        $query = $this->db->get('mst_departments');
        if ($query->result())
            return $query->result();
        else
            return false;
              
	}
	
	public function get_default_branch(){
        $this->db->where('status','1');
        $this->db->order_by('branch_name','ASC');
        $this->db->select('branch_id, branch_name');
        $query = $this->db->get('mst_branches');
        if ($query->result())
            return $query->result();
        else
            return false;
              
	}
	
	public function fetch_designation(){ 
        $this->db->where('status','1');
        $this->db->order_by('designation_name','ASC');
        $this->db->select('designation_id, designation_name');
        $query = $this->db->get('mst_designations');
        if ($query->result())
            return $query->result();
        else
            return false;
              
	}
	
	public function get_labs(){
		$query = $this->db->select('lab_name,lab_id')
						  ->from('mst_labs')
						  ->where('status','1')
						  ->get();
		if($query->num_rows() > 0){
			return $query->result();
		}
		return [];
	}

	public function get_columns_data($table,$columns,$status){
		$this->db->select($columns);
		$query = $this->db->get_where($table,['status' => $status]);

		if($query->num_rows() > 0){
			return $query->result_array();
		}
		return [];
	}

	public function check_fields($product_id){
		$query = $this->db->get_where('registration_fields',['registration_fields_sample_type_id' => $product_id, 'status' => '0']);
		if ($query->num_rows() > 0) {
			return $query->result_array();
		}
		return [];
	}

	public function get_dynamic_value($product_id,$field_id,$id){
		$query = $this->db->select('trf_registrationfield_fields_values')
						  ->from('trf_registrationfield')
						  ->where('trf_registrationfield_fields_type_id',$product_id)
						  ->where('trf_registrationfield_fields_id',$field_id)
						  ->where('trf_registrationfield_fields_reg_id',$id)
						  ->get();
		// echo "<pre>";
		// echo $this->db->last_query(); die;
		if ($query->num_rows() > 0) {
			return $query->row();
		}
		return [];
	}

	public function get_application_care_instruction(){
		$query = $this->db->select('instruction_id,instruction_name')
						  ->from('application_care_instruction')
						  ->order_by('priority_order','asc')
						  ->get();

		if ($query->num_rows() > 0) {
			return $query->result_array();
		}
		return [];
	}

	public function get_units(){
		$query = $this->db->select('unit_id, unit')
						  ->from('units')
						  ->order_by('unit','asc')
						  ->get();

		if($query->num_rows() > 0){
			return $query->result();
		}
		return [];
	}

	// public function uploadBarcode($file_name, $tempFilePath)
    // {
    //     require '../vendor/autoload.php';
    //     if (!empty($file_name)) { {
    //             $data = array();
    //             $keyName = 'assets/barcode/' . basename($file_name);
    //             // Set Amazon S3 Credentials
    //             $s3 = S3Client::factory(
    //                 array(
    //                     'credentials' => array(
    //                         'key' => SECRET_ACCESS_KEY,
    //                         'secret' => SECRET_ACCESS_CODE
    //                     ),
    //                     'version' => 'latest',
    //                     'region' => 'ap-south-1', //write your region name 
    //                     'signature' => 'v4',
    //                 )
    //             );

    //             try {
    //                 // Create temp file
    //                 // $tempFilePath = './assets/barcode/' . basename($file_name);
	// 				echo $type = filetype($file_name); die;
	// 				// Put on S3
    //                 $result = $s3->putObject(
    //                     array(
    //                         'Bucket' => BUCKETNAME,
    //                         'Key' => $keyName,
    //                         'Body' => fopen($tempFilePath.$file_name, 'r+'),
    //                         'ContentType' => $type,
    //                         'ACL' => 'public-read',
    //                         'SourceFile' => $tempFilePath,
    //                         'StorageClass' => 'STANDARD'
    //                     )
    //                 );

    //                 if ($result['ObjectURL']) {
	// 					@chmod($tempFilePath, 0777);
    //                     delete_files(BARCODE);
    //                     return array_merge($data, array('aws_path' => $result['ObjectURL']));
    //                 }
    //             } catch (S3Exception $e) {
    //                 echo $this->session->set_flashdata('error', $e->getMessage());
    //                 return false;
    //             } catch (Exception $e) {
    //                 echo $this->session->set_flashdata('error', $e->getMessage());
    //                 return false;
    //             }
    //             return $data;
    //         }
    //     } else {
    //         return false;
    //     }
	// }

	public function uploadBarcode($file_name)
    {
        require '../vendor/autoload.php';
        if (!empty($file_name)) { {
                $data = array();
                $keyName = 'assets/barcode/' . basename($file_name);
                // Set Amazon S3 Credentials
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

                try {
                    // Create temp file
                    $tempFilePath = './assets/barcode/' . basename($file_name);
                    $type = filetype($tempFilePath);
                    // Put on S3
                    $result = $s3->putObject(
                        array(
                            'Bucket' => BUCKETNAME,
                            'Key' => $keyName,
                            'Body' => fopen($tempFilePath, 'r+'),
                            'ContentType' => $type,
                            'ACL' => 'public-read',
                            'SourceFile' => $tempFilePath,
                            'StorageClass' => 'STANDARD'
                        )
                    );
					
                    if ($result['ObjectURL']) {
                        unlink($tempFilePath);
                        return array_merge($data, array('aws_path' => $result['ObjectURL']));
                    }
                } catch (S3Exception $e) {
					echo $this->session->set_flashdata('error', $e->getMessage());
                    return false;
                } catch (Exception $e) {
					echo $this->session->set_flashdata('error', $e->getMessage());
                    return false;
                }
                return $data;
            }
        } else {
            return false;
        }
    }
	
	public function calculateDueDate($receivedate,$includingToday,$urgent=false){
        $time = date('H:i', strtotime($receivedate));
        //checking current time starat here
        if ($time > '15:00') {
            $receivedate = date('Y-m-d', strtotime($receivedate . ' + 1 days'));
        }

        $isDateHoliday_query = $this->db->select("count(*) as count")
                                  ->from('mst_holidays')
                                  ->where('holiday_date',date('Y-m-d', strtotime($receivedate)))
                                  ->get();
        $isDateHoliday = $isDateHoliday_query->result()[0]->count;
        if ($isDateHoliday)
        $receivedate = date('Y-m-d', strtotime($receivedate . " + 1 days"));
         //checking current time end here
        
        //checking urgent case of 8 hours time period
        if($urgent){
            $timeIntervalDate=strtotime(date('Y-m-d H:i:s', strtotime($receivedate . ' + 8 hours')));
            $shiftendTime=strtotime(date('Y-m-d')." 18:00:00");
    
            if($timeIntervalDate > $shiftendTime){
               $receivedate = date('d-M-Y', strtotime($receivedate . ' + 1 days'));
            } else {
                $receivedate = date('d-M-Y');
            }

            $isDateHoliday_query = $this->db->select("count(*) as count")
                                  ->from('mst_holidays')
                                  ->where('holiday_date',date('Y-m-d', strtotime($receivedate)))
                                  ->get();
             $isDateHoliday = $isDateHoliday_query->result()[0]->count;
             if ($isDateHoliday)
            $receivedate = date('Y-m-d', strtotime($receivedate . " + 1 days"));
        }
       
        //urgent case of 8 hourse end here.
        //chekcing saturday and sunday start here
	if(BRANCH=='GURGAON'){
			
			$daydate = date('D', strtotime($receivedate));

			if($daydate=='Sun'){
				$receivedate = date('Y-m-d', strtotime($receivedate . ' + 1 days'));
			}
	}
	if(BRANCH=='DUBAI'){
			$daydate = date('D', strtotime($receivedate));

			if($daydate=='Fri'){
				$receivedate = date('Y-m-d', strtotime($receivedate . ' + 1 days'));
			}
			$daydate = date('D', strtotime($receivedate));
			
			if($daydate=='Sat'){
				$receivedate = date('Y-m-d', strtotime($receivedate . ' + 1 days'));
			}
			
	}
	if(BRANCH=='BANGLADESH'){
			$daydate = date('D', strtotime($receivedate));

			if($daydate=='Fri'){
				$receivedate = date('Y-m-d', strtotime($receivedate . ' + 1 days'));
			}
			$daydate = date('D', strtotime($receivedate));
			
			if($daydate=='Sat'){
				$receivedate = date('Y-m-d', strtotime($receivedate . ' + 1 days'));
			}
	}
        
        $isDateHoliday_query = $this->db->select("count(*) as count")
                                  ->from('mst_holidays')
                                  ->where('holiday_date',date('Y-m-d', strtotime($receivedate)))
                                  ->get();
        $isDateHoliday = $isDateHoliday_query->result()[0]->count;

        if ($isDateHoliday)
        $receivedate = date('Y-m-d', strtotime($receivedate . " + 1 days"));
        //chekcing saturday and sunday end here.
    
        for($start=1;$start<=$includingToday;$start++){
            $receivedate = date('Y-m-d', strtotime($receivedate . ' + 1 days'));
			if(BRANCH=='GURGAON'){
			
				$daydate = date('D', strtotime($receivedate));
	
				if($daydate=='Sun'){
					$receivedate = date('Y-m-d', strtotime($receivedate . ' + 1 days'));
				}
		}
		if(BRANCH=='DUBAI'){
				$daydate = date('D', strtotime($receivedate));
	
				if($daydate=='Fri'){
					$receivedate = date('Y-m-d', strtotime($receivedate . ' + 1 days'));
				}
				$daydate = date('D', strtotime($receivedate));
				
				if($daydate=='Sat'){
					$receivedate = date('Y-m-d', strtotime($receivedate . ' + 1 days'));
				}
				
		}
		if(BRANCH=='BANGLADESH'){
				$daydate = date('D', strtotime($receivedate));
	
				if($daydate=='Fri'){
					$receivedate = date('Y-m-d', strtotime($receivedate . ' + 1 days'));
				}
				$daydate = date('D', strtotime($receivedate));
				
				if($daydate=='Sat'){
					$receivedate = date('Y-m-d', strtotime($receivedate . ' + 1 days'));
				}
		}
			

            $isDateHoliday_query = $this->db->select("count(*) as count")
                                  ->from('mst_holidays')
                                  ->where('holiday_date',date('Y-m-d', strtotime($receivedate)))
                                  ->get();
            $isDateHoliday = $isDateHoliday_query->result()[0]->count;
            if ($isDateHoliday)
            $receivedate = date('Y-m-d', strtotime($receivedate . " + 1 days"));
        }
        return $receivedate;
    }


	public function insert_data($table,$data=array()){
		$result = $this->db->insert($table,$data);
		if($result){
			return $this->db->insert_id();
		}
		else{
			return false;
		}
	}

	public function update_data($table,$data=array(),$where){
		$result =$this->db->where($where)
						->update($table,$data);
		// echo $this->db->last_query(); die;
		if($result){
			return true;
		}
		else{
			return false;
		}
	}

	public function get_row($col='*',$table,$where){
				$this->db->select($col)
							->from($table)
							->where($where);
							$result = $this->db->get();

		if($result->num_rows()>0){
			return $result->row();
		}
		else{
			return false;
		}
	}

	public function get_result($col='*',$table,$where=NULL){
				$this->db->select($col)
							->from($table);
						if($where!=NULL){
							$this->db->where($where);
						}
				$result=$this->db->get();
		
		if($result->num_rows()>0){
			return $result->result();
		}
		else{
			return false;
		}
	}

	public function get_AutoList($col,$table,$search = NULL,$like,$where=NULL){
		
			$this->db->select($col)
							->from($table);
					if($where!=NULL){
							$this->db->where($where);
						}
					if($search!=NULL){
						$this->db->like($like,trim($search));
					}
					$this->db->order_by($like,'asc');
			$result = $this->db->get();

			// print_r($this->db->last_query());exit;
	
		if($result->num_rows()>0){
			return $result->result();
		}
		else{
			return false;
		}					
	}


	public function multiple_checkbox_list($col,$table,$search = NULL,$like,$where=NULL){
		$this->db->select($col)
						->from($table);
				if($where!=NULL){
						$this->db->where($where);
					}
				if($search!=NULL){
					$this->db->like($like,trim($search));
				}
				$this->db->order_by($like,'asc');
		$result = $this->db->get();

		// print_r($this->db->last_query());exit;

		if($result->num_rows()>0){
			return $result->result();
		}
		else{
			return false;
		}	

	}

	public function remove_data($table,$column,$where){
		$remove = $this->db->delete('sample_test',[$column => $where]);
		// echo $this->db->last_query(); die;
		if($remove){
			return true;
		}
		return false;
	}

	// Added by saurabh on 04-12-2020 to get email template
	function getContentFromTPL($tplId, $formData) {
        $query = $this->db->select('a.MailTemplateContents,a.MailSubject,b.TemplateVariables')
                          ->from('sys_email_template a')
                          ->join('sys_mail_type b','a.MailTypeId=b.MailTypeId','left')
						  ->where('a.MailTemplateId',$tplId)->get();
		
        $result = $query->result_array()[0];
        $values = array($result['MailTemplateContents'], $result['MailSubject'], $result['TemplateVariables']);
        list($content, $subject, $vars) = $values;
        $vars = json_decode($vars);
        $content = stripslashes($content);
        // build the vars
        $tplVars = array();
        $tplVals = array();
        foreach ($vars as $var) {
            if (isset($formData[$var[2]])) {
                $tplVars[] = "[" . $var[0] . "]";
                $tplVals[] = $formData[$var[2]];
            } elseif ($var[2] == 'MailSubject') {
                $temp = str_replace($tplVars, $tplVals, $subject);
                $tplVars[] = "[" . $var[0] . "]";
                $tplVals[] = $temp;
            } else {
                if (substr($var[2], 0, 2) == 'f:') {
                    $func = explode(',', substr($var[2], 2));
                    if (function_exists($func[0])) {
                        $tplVars[] = "[" . $var[0] . "]";
                        if (count($func) > 2) {
                            $tplVals[] = $func[0]($func, $formData);
                        } else {
                            $tplVals[] = $func[0]($func[1]);
                        }
                    }
                }
            }
		}
        return array('subject' => str_replace($tplVars, $tplVals, $subject), 'content' => str_replace($tplVars, $tplVals, $content));
    }
	

	// Added by Ajit

	public function fetchBuyers($search=NULL,$where=NULL,$where_in=NULL,$like=NULL){
		$this->db->select("customer.customer_id as buyer_id,customer.customer_name as buyer_name")
		->from("cust_customers as customer")
		->join("buyer_factory as factory",'factory.buyer_id=customer.customer_id','left')
		->join("buyer_agent as agent",'agent.buyer_id=customer.customer_id','left')
		->where(["customer.customer_type"=>"buyer","isactive"=>"Active"]);
		if($where_in!=NULL){
			$this->db->where_in('customer.customer_id',$where_in);
		}
		if($where!=NULL){
			$this->db->where($where);
		}
		if($search!=NULL){
			$this->db->like($like,trim($search));
		}
		
		$this->db->group_by('customer.customer_name');
		$result = $this->db->get();
		if($result->num_rows()>0){
			return $result->result();
		}
		else{
			return false;
		}	
	}


	// insert multiple data in one time

	public function insert_multiple_data($table,$data=array()){
		$result = 	$this->db->insert_batch($table,$data);
		if($result){
			return true;
		}
		else{
			return false;
		}
	}

	// added by millan on 30-01-2021
	public function get_count($table, $condition = NULL, $value = NULL)
    {

        $this->db->select('COUNT(*) as count');
        $this->db->from($table);
        $this->db->where_in($condition, $value);
        $query = $this->db->get()->row_array();
        // print_r($query);die;
        // echo $this->db->last_query();die;
        return $query['count'];
    }
	public function gstCalculation($state, $gst_type, $amount, $percentage, $country_code = false) {
		if (!$country_code)
		$country_code = 'IND';
		$state=trim($state);
		$union_territory = $this->get_fields_by_id("sys_configuration","cfg_Value","UNION_TERRITORY","cfg_Name")[0]['cfg_Value'];
		$UAE_VAT_PLACES = $this->get_fields_by_id("sys_configuration","cfg_Value","UAE_VAT_PLACES","cfg_Name")[0]['cfg_Value'];
		$ZERO_RATED_TAX = $this->get_fields_by_id("sys_configuration","cfg_Value","ZERO_RATED_TAX","cfg_Name")[0]['cfg_Value'];
		$territtory = explode(",", $union_territory);
		$uae_place = explode(",", $UAE_VAT_PLACES);
	   
		if ($country_code == 'IND') {
			if (strtolower($state) == 'haryana') {
				if ($gst_type == 'SGST')
					$return_gst = 'SGST';
				else
					$return_gst = 'CGST';
			}else if (in_array($state, $territtory)) {
				$return_gst = 'UTGST';
			} else {
				$return_gst = 'IGST';
			}
		} else if ($country_code == 'UAE' || $country_code == 'AE') {
			 
			if (in_array($state, $uae_place)) {
				$return_gst = 'VAT';
			} else {
				$percentage = $ZERO_RATED_TAX;
				$return_gst = 'VAT';
			}
		} else if ($country_code == 'BAD') {
			$return_gst = 'VAT';
		} 
		//echo $gst_type."##".$return_gst;
		if ($gst_type == $return_gst) {         
			return round($amount * $percentage / 100);
		} else
			return 0;
	}
        public function delete_row($table,$where=array()){
		$delete = $this->db->delete($table,$where);
		if($delete){
			return true;
		}
		return false;
	}

	public function run_query(){
		$query = $this->db->query("SELECT cust.gstin as newgstinnumber, invoice_remark, sr.sample_desc, ip.proforma_invoice_number AS proforma_invoice_number, trf_invoice_to, proforma_invoice_sample_reg_id, ip.show_discount, total_amount, trf_product, ip.tax_percentage, ip.gst_amount, proforma_invoice_id, trf_id, cust.customer_name AS customer,cust.customer_code AS applicant_code, trf.trf_ref_no AS reference_no, (select GROUP_CONCAT(reference_no) AS reference_no FROM quotes WHERE FIND_IN_SET(quote_id,trf.trf_quote_id)) AS quote_ref_no,cust.address AS customer_address, CASE WHEN service_days!='' THEN CONCAT(trf_service_type,' ',service_days,' ',' Days') ELSE trf_service_type END AS service_type,country_name AS country,province_name AS state,location_name AS location,cust.city,cust.po_box, /*CONCAT( c.contact_salutation, '. ', c.contact_name)*/ (select GROUP_CONCAT(contact_name SEPARATOR ' / ') from contacts where FIND_IN_SET(contact_id,trf.trf_invoice_to_contact)) AS customer_contact,c.telephone AS contact_telephone,c.mobile_no AS contact_mobile_no,c.email AS contact_email, (SELECT currency_name FROM mst_currency WHERE currency_id=open_trf_currency_id) AS quotes_currency, (SELECT currency_code FROM mst_currency WHERE currency_id=open_trf_currency_id) AS quote_currency_code, (SELECT currency_basic_unit FROM mst_currency WHERE currency_id = ip.tax_currency ) AS currency_basic_unit, (SELECT currency_decimal FROM mst_currency WHERE currency_id=open_trf_currency_id) AS currency_decimal, (SELECT currency_code FROM mst_currency WHERE currency_id=open_trf_currency_id) AS currency_code, (SELECT currency_fractional_unit FROM mst_currency WHERE currency_id=open_trf_currency_id) AS currency_fractional_unit, (SELECT sample_type_name FROM mst_sample_types WHERE sample_type_id=sample_registration_sample_type_id) as sample_name, CASE WHEN trf_invoice_to='Factory' THEN (SELECT province_name FROM mst_provinces INNER JOIN cust_customers cs ON cs.cust_customers_province_id=province_id WHERE customer_id=trf_applicant) WHEN trf_invoice_to='Buyer' THEN (SELECT province_name FROM mst_provinces INNER JOIN cust_customers cs ON cs.cust_customers_province_id=province_id WHERE customer_id=trf_buyer) ELSE (SELECT province_name FROM mst_provinces INNER JOIN cust_customers cs ON cs.cust_customers_province_id=province_id WHERE customer_id=trf_agent) END AS state1, '' AS 'GST','' AS 'total_amount_with_gst','' AS 'VAT', (SELECT customer_name FROM cust_customers WHERE customer_id=trf.trf_buyer) AS buyer, (SELECT customer_name FROM cust_customers WHERE customer_id=trf.trf_agent) AS agent, (SELECT gstin FROM cust_customers WHERE customer_id=trf.trf_buyer) AS gstin,DATE_FORMAT( sr.received_date, '%d-%m-%Y %H:%i' ) AS proforma_invoice_date,(select division_name from mst_divisions where trf.division = mst_divisions.division_id) as division,/*DATE_FORMAT(DATE_ADD(sr.received_date, INTERVAL CASE WHEN trf_service_type='Regular' THEN 3 WHEN trf_service_type='Express' THEN 2 WHEN trf_service_type='Urgent' THEN 1 END DAY), '%d-%m-%Y' ) AS due_date*/ tat_date, due_date,service_days AS sr_days,gc_no AS test_report_no,(SELECT country_name FROM mst_country WHERE country_id=trf_country_orgin) AS trf_country_of_orgin FROM invoice_proforma ip INNER JOIN sample_registration sr ON sr.sample_reg_id=ip.proforma_invoice_sample_reg_id INNER JOIN trf_registration trf ON trf.trf_id=sr.trf_registration_id LEFT JOIN quotes qt ON qt.quote_id =(SELECT quote_id FROM quotes WHERE quote_id IN (trf.trf_quote_id) LIMIT 1 ) INNER JOIN cust_customers cust ON cust.customer_id=trf.trf_applicant INNER JOIN mst_country mctry ON mctry.country_id=cust.cust_customers_country_id LEFT JOIN mst_provinces mpro ON mpro.province_id=cust.cust_customers_province_id LEFT JOIN mst_locations mloc ON mloc.location_id=cust.cust_customers_location_id INNER JOIN contacts c ON c.contact_id = trf.trf_contact WHERE proforma_invoice_id='31891'");
		echo "<pre>"; print_r($query->result_array());
	}


	// public function getDashboardSamplesData($where){
    //      if($where != NULL){
    //         $this->db->where($where);
	// 	 }
	// 	$sql = $this->db->select('count(DISTINCT sample_registration.sample_reg_id) as count')->from('sample_registration')->join('sample_hold_remark shr','shr.sample_reg_id=sample_registration.sample_reg_id','left')->get();
    //     $res = $sql->result_array()[0];
	// 	return $res['count'];
	// }

	// public function getDashboardFilteredData($where,$res,$num){
    //      if($where != NULL){
    //         $this->db->where($where);
	// 	 }
    //      //echo '<pre>';print_r($num);die;
	// 	 if($res['buyer'] != '')
	// 	  $this->db->where('tr.trf_buyer',$res['buyer']);

	// 	 if($res['agent'] != '')
	// 	  $this->db->where('tr.trf_applicant',$res['agent']);

	// 	 if($res['labType'] != '')
	// 	  $this->db->where('sr.sample_registered_to_lab_id',$res['labType']);

	// 	 if($res['division_dropdown'] != '')
	// 	  $this->db->where('sr.division_id',$res['division_dropdown']);

	// 	 if($res['customer'] != '')
	// 	  $this->db->where('tr.trf_applicant',$res['customer']);
    //     if($num == 0){
	// 		if ($res['start_date'] != '' && $res['end_date'] != '') {
	// 			$this->db->where('date(sr.create_on) >=', $res['start_date']);
	// 			$this->db->where('date(sr.create_on) <=', $res['end_date']);
	// 		} elseif ($res['start_date'] != '') {
	// 			$this->db->where('date(sr.create_on)', $res['start_date']);
	// 		} elseif ($res['end_date']  != '') {
	// 			$this->db->where('date(sr.create_on)', $res['end_date'] );
	// 		}
	// 	}
		

	// 	$sql = $this->db->select('count(*) as count')->from('sample_registration sr')->join('trf_registration tr','tr.trf_id=sr.trf_registration_id')->join('sample_hold_remark shr','shr.sample_reg_id=sr.sample_reg_id','left')->get();

	//    // echo $this->db->last_query();echo '<br/>';
    //     $res = $sql->result_array()[0];
	// 	return $res['count'];
	// }

	public function getDashboardSamplesData($where)
	{
		if ($where != NULL) {
			$this->db->where($where);
		}
		$sql = $this->db->select('count(DISTINCT sample_registration.sample_reg_id) as count')->from('sample_registration')->join('sample_hold_remark shr', 'shr.sample_reg_id=sample_registration.sample_reg_id', 'left')->get();
		$res = $sql->result_array()[0];
		return $res['count'];
	}

	public function getDashboardFilteredData($where, $res, $num)
	{
		if ($where != NULL) {
			$this->db->where($where);
		}
		//echo '<pre>';print_r($num);die;
		if ($res['buyer'] != '')
			$this->db->where('tr.trf_buyer', $res['buyer']);

		if ($res['agent'] != '')
			$this->db->where('tr.trf_applicant', $res['agent']);

		if ($res['labType'] != '')
			$this->db->where('sr.sample_registered_to_lab_id', $res['labType']);

		if ($res['division_dropdown'] != '')
			$this->db->where('sr.division_id', $res['division_dropdown']);

		if ($res['customer'] != '')
			$this->db->where('tr.trf_applicant', $res['customer']);
		if ($num == 0) {
			if ($res['start_date'] != '' && $res['end_date'] != '') {
				$this->db->where('date(sr.create_on) >=', $res['start_date']);
				$this->db->where('date(sr.create_on) <=', $res['end_date']);
			} elseif ($res['start_date'] != '') {
				$this->db->where('date(sr.create_on)', $res['start_date']);
			} elseif ($res['end_date']  != '') {
				$this->db->where('date(sr.create_on)', $res['end_date']);
			}
			// new
			if (isset($where['sr.status']) && $where['sr.status'] == 'Hold Sample') {
				if ($res['sampleyear'] != '')
					$this->db->where('YEAR(shr.created_on)', $res['sampleyear']);

				if ($res['month'] != '')
					$this->db->where('Month(shr.created_on)', $res['month']);
			} else {
				if ($res['sampleyear'] != '')
					$this->db->where('YEAR(sr.create_on)', $res['sampleyear']);

				if ($res['month'] != '')
					$this->db->where('Month(sr.create_on)', $res['month']);
			}
		}


		$sql = $this->db->select('(count(DISTINCT sr.sample_reg_id) )as count')->from('sample_registration sr')->join('trf_registration tr', 'tr.trf_id=sr.trf_registration_id')->join('sample_hold_remark shr', 'shr.sample_reg_id=sr.sample_reg_id', 'left')->get();

		//    echo $this->db->last_query();echo '<br/>';die;
		$res = $sql->result_array()[0];
		return $res['count'];
	}
}
?>
