<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Products_model extends MY_Model {

	public function __construct(){
		parent::__construct();
		
	}

	public function get_prod_List($limit = NULL, $start = NULL, $search = NULL, $sortby, $order, $where, $count = NULL){
		$this->db->limit($limit, $start);

	   if($count==NULL){
		if ($sortby != NULL || $order= NULL) {
			$this->db->order_by($sortby, $order);
		}
		else {
			$this->db->order_by('st.sample_type_id', 'DESC');
		}
	   }
	   if ($where) {
		$this->db->where($where);
		}

		if ($search != NULL) {
			
			$search =trim($search); 
			$this->db->group_start();
			//$this->db->like('st.sample_types_code', $search);
			$this->db->or_like('st.sample_type_name', $search);
			//$this->db->or_like('cat.sample_category_name',$search);
			//$this->db->or_like('u.unit',$search);
			//$this->db->or_like('st.retain_period',$search);
			$this->db->group_end();
		}
		

		 $result = $this->db->select("st.*,cat.sample_category_name as cat_name,u.unit")
							->from("mst_sample_types as st")
							->join("mst_sample_category as cat","cat.sample_category_id=st.type_category_id",'left')
							->join("units as u","u.unit_id=st.minimum_quantity_units","left")
							->get();

						// print_r($this->db->last_query());exit;

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

	 public function get_sample_type($id){

		 $result = $this->db->select("st.*,cat.sample_category_name as cat_name,u.unit")
							->from("mst_sample_types as st")
							->join("mst_sample_category as cat","cat.sample_category_id=st.type_category_id",'left')
							->join("units as u","u.unit_id=st.minimum_quantity_units","left")
							->where("sample_type_id",$id)
							->get();

			 if ($result->num_rows()>0){
				 return $result->row_array();
			}
			else{
					return false;
			}
	 }


	 public function insert_product_data($data,$log){
		$this->db->trans_begin();
			$this->db->insert('mst_sample_types',$data);
			$id = $this->db->insert_id();
			$log['sample_type_id']=$id;
			$this->db->insert('mst_sample_types_log',$log);

				$ulog = array();
				$ulog['source_module'] = 'Products';
				$ulog['record_id'] = $id;
				$ulog['created_on'] = date("Y-m-d h:i:s");
				$ulog['created_by'] = $this->user;
				$ulog['action_taken'] = 'insert_product';
				$ulog['text'] = 'ADD PRODUCT WITH NAME '.$data['sample_type_name'];
				$this->insert_data('user_log_history',$ulog);

// print_r($this->db->last_query());exit;
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
	

	 public function get_catid($cat_name){
		 $this->db->group_start();
		 $this->db->like('sample_category_name',trim($cat_name));
		 $this->db->group_end();
		 $this->db->select('sample_category_id');
		 $this->db->from('mst_sample_category');
		$cat_id = $this->db->get();
		if($cat_id->num_rows()>0){
			return $cat_id->row();
		}
		else{
			return false;
		}
	 }


	 public function checkDuplicate($data,$id=NULL)
	 {
		
		$this->db->select('sample_types_code,sample_type_name');
		$this->db->from('mst_sample_types');
		$this->db->group_start();
		$this->db->like('sample_types_code',trim($data['sample_types_code']));
		$this->db->or_like('sample_type_name',trim($data['sample_type_name']));
		$this->db->group_end();
		
		if($id!=NULL){
			$this->db->where_not_in('sample_type_id',[$id]);
		}
		$result = $this->db->get();

	
		if($result->num_rows()=="0"){
			return true;
		}
		else{
			return false;
		}
}


	public function get_test_list($search){
			$this->db->select('tt.test_id as test_id,tt.test_name as test_name,tt.test_method as test_method');
			$this->db->from('tests tt');
			$this->db->join('test_sample_type product','product.test_sample_type_test_id=tt.test_id','left');
			$this->db->where('tt.test_status','Active');
			$this->db->group_start();
			$this->db->like('tt.test_name',$search);
			$this->db->group_end();
			$this->db->group_by('product.test_sample_type_test_id');
			$result = $this->db->get();
			if($result->num_rows()>0){
				return $result->result();
			}
			else{
				return false;
			}
	}

	public function getTestList($product_id){
		$this->db->select('smt.test_sample_type_test_id as test_id,tt.test_name as test_name,tt.test_method as test_method');
		$this->db->from('test_sample_type as smt');
		$this->db->join('tests tt','tt.test_id=smt.test_sample_type_test_id','inner');
		$this->db->where('tt.test_status','Active');
		$this->db->where('smt.test_sample_type_sample_type_id',$product_id);
		$result = $this->db->get();
		// echo $this->db->last_query();die;
			if($result->num_rows()>0){
				return $result->result();
			}
			else{
				return false;
			}
	}

	public function insert_test_product($data){
		unset($data['product_id']);
		$result = $this->db->insert('test_sample_type',$data);
		if($result){
			return true;
		}
		else{
			return false;
		}

	}

	public function update_test_product($data,$product_id){

		$this->db->trans_begin();
        $this->db->where('test_sample_type_sample_type_id',$product_id)->delete('test_sample_type');
        $this->db->insert_batch('test_sample_type', $data);

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

	public function delete_test($product_id){
        $this->db->where('test_sample_type_sample_type_id',$product_id);
        $result = $this->db->delete('test_sample_type');

        if($result){
            return true;
        }
        else{
            return false;
        }
        
    }

	public function get_log_data($id)
    {
     
      $where = array();
      $where['ul.source_module'] = 'Products';
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