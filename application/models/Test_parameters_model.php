<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Test_parameters_model extends MY_Model {

	public function __construct(){
		parent::__construct();
		
	}
	
	public function get_test_parameters_list($limit = NULL, $start = NULL, $test_name = NULL, $test_parameter = NULL, $created_by = NULL, $sortby, $order, $where, $count = NULL){
		$this->db->limit($limit, $start);
	   if($count==NULL){
		if ($sortby != NULL || $order= NULL) {
			$this->db->order_by($sortby, $order);
		}
		else {
			$this->db->order_by('tp.test_parameters_id', 'DESC');
		}
	   }
	   if ($where) {
		$this->db->where($where);
		}
		if ($test_name != NULL) {
			$test_name =trim($test_name); 
			$this->db->group_start();		
			$this->db->like('ts.test_name', $test_name,'after');
			$this->db->group_end();
		}
		if ($test_parameter != NULL) {
			$test_parameter =trim($test_parameter); 
			$this->db->group_start();		
			$this->db->like('tp.test_parameters_name', $test_parameter,'after');
			$this->db->group_end();
		}
		if ($created_by != NULL) {
			$created_by =trim($created_by); 
			$this->db->group_start();		
			$this->db->like('admin.admin_fname', $created_by);
			$this->db->group_end();
		}

		if ($where) {
			$this->db->where($where);
		}


		 $result = $this->db->select("tp.*,ts.test_name,DATE_FORMAT(tp.created_on, '%d-%b-%Y') as created_on,admin.admin_fname as created_by")
							->from("test_parameters as tp")
							->join("tests as ts","ts.test_id=tp.test_parameters_test_id")
							->join("mst_lab_type as lab","lab.lab_type_id=ts.test_lab_type_id","left")
							->join("mst_divisions as div","div.division_id=ts.test_division_id","left")
							->join("units as report","report.unit_id=ts.units","left")
							->join("units as min_qty","min_qty.unit_id=ts.minimum_quantity_units","left")
							->join("admin_profile as admin","admin.uidnr_admin=tp.created_by","left")
							->get();

							
							
		if ($count) {
			 return $result->num_rows();
					} 
		else {
			 if ($result){
				 return $result->result();
			}
			else{
				return false;
			}
					
			}

	 }
	 
	//  insert tests
	 public function insert_test($products,$filterdata,$sub_perameter,$price_listlog,$sub_contract){
		
		$this->db->trans_begin();
				$this->db->insert('tests',$filterdata);
				$test_id = $this->db->insert_id();
			
			$sub_perameter['test_parameters_test_id']=$price_listlog['pricelist_log_test_id']=$test_id;
			$this->db->insert('test_parameters',$sub_perameter);
			$this->db->insert('pricelist_log',$price_listlog);

			$data = array();
			$data['source_module'] = 'Test_master';
			$data['record_id'] = $test_id;
			$data['created_on'] = date("Y-m-d h:i:s");
			$data['created_by'] = $this->user;
			$data['action_taken'] = 'add_test';
			$data['text'] = 'Test added with name '.$filterdata['test_name'];

			$this->insert_data('user_log_history',$data);
			
			foreach($products as $value){
				$prod['test_sample_type_sample_type_id']=$value;
				$prod['test_sample_type_test_id'] = $test_id;
				$this->db->insert('test_sample_type',$prod);
			}

			if($sub_contract && count($sub_contract)>0){
				$sub_contract['test_id'] = $test_id;
				$this->db->insert('test_sub_contract_details', $sub_contract);
			  }

			if ($this->db->trans_status() === FALSE)
				{
						$this->db->trans_rollback();
						return false;
				}
			else
				{
						$this->db->trans_commit();
						return true;
				}
		}

// insert test end


// update test
		public function update_test($data,$products,$test_id,$sub_contract){
	
			$this->db->trans_begin();
			$this->db->where('test_id ',$test_id)
						->update('tests',$data);

			$this->db->where('test_sample_type_test_id',$test_id)
						->delete('test_sample_type');
						
			foreach($products as $key=> $value){
				$multi[$key] = ['test_sample_type_sample_type_id'=>$value,'test_sample_type_test_id'=>$test_id];
			}
			
			$this->db->insert_batch('test_sample_type',$multi);
			
			if($sub_contract && count($sub_contract)>0){
				$sub_contract['test_id'] = $test_id;
				$this->db->where('test_id',$test_id);
				$this->db->update('test_sub_contract_details', $sub_contract);
			}
			if ($this->db->trans_status() === FALSE)
				{
						$this->db->trans_rollback();
						return false;
				}
			else
				{
						$this->db->trans_commit();
						return true;
			}
		}
	
// update test end
public function check_edupli($data,$test_id=NULL){
			
	$this->db->select('test_name,test_method');
	$this->db->from('tests');
	$this->db->group_start();
	$this->db->where('LOWER(test_name)',strtolower($data['test_name']));
	$this->db->where('LOWER(test_method)',strtolower($data['test_method']));
	$this->db->group_end();
	if($test_id!=NULL){
		$this->db->where_not_in('test_id',[$test_id]);	
	}
	$result = $this->db->get();
	// echo $this->db->last_query();die;
	if($result->num_rows()==1){
		return true;
	}
	else{
		return false;
	}
}
		public function check_dupli($data,$test_id=NULL){
			
			$this->db->select('test_name,test_method');
			$this->db->from('tests');
			$this->db->group_start();
			$this->db->where('LOWER(test_name)',strtolower($data['test_name']));
			$this->db->where('LOWER(test_method)',strtolower($data['test_method']));
			$this->db->group_end();
			if($test_id!=NULL){
				$this->db->where_not_in('test_id',[$test_id]);	
			}
			$result = $this->db->get();
			// echo $this->db->last_query();die;
			if($result->num_rows()=="0"){
				return true;
			}
			else{
				return false;
			}
		}


		// get products by product ids array;

		public function get_products_of_test($product_ids){
			$this->db->select('sample_type_id as id,sample_type_name as name');
			$this->db->where_in('sample_type_id',$product_ids);
			$products=$this->db->get('mst_sample_types');

		if($products->num_rows()>0){
			return $products->result();
		}
		else{
			return false;
		}

		}
	
		

		public function getIDByName($select="*",$id,$like=NULL,$where=NULL,$table)
		{
			$this->db->group_start();
			$this->db->like($where,trim($like));
			$this->db->group_end();
			$this->db->select($select);
			$this->db->from($table);
			$id = $this->db->get();
			if($id->num_rows()>0){
				return $id->row();
			}
			else{
				return false;
			}
		}

		public function insert_import_test($data=NULL,$product=NULL){
			$test = NULL;
			$this->db->trans_begin();
			foreach($data as $key=>$value){
				$this->db->insert('tests',$value);
				$id = $this->db->insert_id();
				$this->db->where('test_sample_type_test_id',$id)
				->delete('test_sample_type');
				foreach($product as $key=>$value){
					$test['test_sample_type_sample_type_id']=$value;
					$test['test_sample_type_test_id']=$id;
					$this->db->insert('test_sample_type',$test);
				}
			}
			

			if ($this->db->trans_status() === FALSE)
				{
						$this->db->trans_rollback();
						return false;
				}
			else
				{
						$this->db->trans_commit();
						return true;
			}
		}


		public function get_price_lists_of_tests($test_id){
			$this->db->select('distinct(mst_currency.currency_id),mst_currency.currency_code,mst_country.country_code as country_name,mst_country.country_code as country_code,if(pricelist.price is null,0,pricelist.price) as price');
			$this->db->from('mst_branches');
			$this->db->join("mst_currency","mst_currency.currency_id=mst_branches.mst_branches_currency_id AND mst_branches.status='1'","inner");
			$this->db->join("mst_country","mst_country.country_id=mst_branches.mst_branches_country_id  AND mst_currency.status='1'","inner");
			$this->db->join("pricelist","pricelist.currency_id=mst_currency.currency_id and pricelist.pricelist_test_id ='".$test_id."' and pricelist.type='Test' ","left");
			$this->db->group_by('mst_currency.currency_id');
			$result = $this->db->get();
		   
			if($result){
				return $result->result();
			}
			else{
				return false;
			}
	
		}


		
		public function get_log_data($id)
		{
	  
		  $where = array();
		  $where['ul.source_module'] = 'Test_master';
		  $where['ul.record_id'] = $id;
	  
		  $this->db->select('ul.action_taken,ul.created_on as taken_at,ul.text, CONCAT(ap.admin_fname," ",ap.admin_lname) as taken_by');
		  $this->db->from('user_log_history ul');
		  $this->db->join('admin_profile ap','ul.created_by = ap.uidnr_admin','left');
		  $this->db->order_by('ul.id','DESC');
		  $this->db->where($where);
		  $result = $this->db->get();
		  // echo $this->db->last_query();die;
		  if($result->num_rows()>0){
			return $result->result();
		  }
		  else{
			return false;
		  }
		
	  
		}
}