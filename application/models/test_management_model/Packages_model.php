<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Packages_model extends MY_Model {

	public function __construct(){
		parent::__construct();
		
    }

    public function get_packages_list($limit = NULL, $start = NULL, $search = NULL,$packageBuyer=NULL, $sortby=NULL, $order=NULL, $where,$count = NULL){
        $this->db->limit($limit, $start);
        // print_r($where);exit;
            if($sortby != NULL || $order != NULL) {
                $this->db->order_by($sortby, $order);
            }
            else
            {
                $this->db->order_by('pc.package_id','DESC');
            }
        
        if($where) 
        {
            $this->db->where($where);
        }
    
        if($search != NULL && $search!='NULL') {     
            $search =trim($search); 
            $this->db->group_start();
            $this->db->like('pc.package_name', $search);
            $this->db->or_like('sample.sample_type_name', $search);
            $this->db->group_end();
        }
            
        if($packageBuyer != NULL && $packageBuyer!='NULL') {     
            $packageBuyer =trim($packageBuyer); 
            $this->db->where('pc.buyer', $packageBuyer);
           
        }
        $result = $this->db->select("pc.*,sample.sample_type_name as product_name,cust.customer_name")
                                ->from("packages as pc")
                                ->join("mst_sample_types as sample","sample.sample_type_id=pc.packages_sample_type_id","left")
                                ->join("cust_customers as cust","cust.customer_id =pc.buyer","left")
                                ->get();
    
                                
                                if($count) 
                                {
                                    return $result->num_rows();
                                } 
                                else 
                                {
            
            if($result)
            {
                return $result->result();
            }
            else
            {
                return false;
            }
                        
        }
    }



   

// save test package
    public function saveTestpackages($data){
        $this->db->trans_begin();
        $this->db->insert('packages',$data);
        $id = $this->db->insert_id();
        $data = array();
		$data['source_module'] = 'Packages';
		$data['record_id'] = $id;
		$data['created_on'] = date("Y-m-d h:i:s");
		$data['created_by'] = $this->user;
		$data['action_taken'] = 'insert_packages';
		$data['text'] = 'PACKAGES ADDED WITH NAME '.$data['package_name'];

		$this->insert_data('user_log_history',$data);

        if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            return false;
        }
        else{
            $this->db->trans_commit();
            return true;
        }
    }


    // update packages

    public function update_packages($data,$package_id){
        $this->db->trans_begin();
        $this->db->where('package_id',$package_id);
        $this->db->update('packages',$data);


        if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            return false;
        }
        else{
            $this->db->trans_commit();
            return true;
        }
    }
    

    // 09-12-2020

    public function getTestList($package_id){
        $result=$this->db->select('tp.*,tp.test_package_id as id,test.test_name as test_name,test.test_method as test_method,tp.package_test_sort_order as order,tm.test_method_name as test_method')
                    ->where('test_package_packages_id',$package_id)
                    ->from('test_packages as tp')
                    ->join('tests as test','test.test_id= tp.test_package_test_id','left')
                    ->join("mst_test_methods tm", "tm.test_method_id = test.test_method_id", "left")
                    ->order_by('tp.package_test_sort_order','ASC')
                    // ->group_by('tp.test_package_test_id')
                    ->get();
                  //  echo $this->db->last_query();die;
        if($result->num_rows()>0){
            return $result->result();
        }
        else{
            return false;
        }
    }

    public function insert_test($data,$id){
        $this->db->trans_begin();
        $this->db->where('test_package_packages_id',$id)->delete('test_packages');
        $this->db->insert_batch('test_packages', $data);
        $this->db->trans_complete();

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			return false;
		} else {
			$this->db->trans_commit();
			return true;
		}
    }

    public function get_price_list($package_id){
        $this->db->select('distinct(mst_currency.currency_id),mst_currency.currency_code,mst_country.country_code as country_name,mst_country.country_code as country_code,if(pricelist.price is null,0,pricelist.price) as price');
        $this->db->from('mst_branches');
        $this->db->join("mst_currency","mst_currency.currency_id=mst_branches.mst_branches_currency_id AND mst_branches.status='1'","inner");
        $this->db->join("mst_country","mst_country.country_id=mst_branches.mst_branches_country_id  AND mst_currency.status='1'","inner");
        $this->db->join("pricelist","pricelist.currency_id=mst_currency.currency_id and pricelist.type_id ='".$package_id."' and pricelist.type='Package' ","left");
        $this->db->group_by('mst_currency.currency_id');
        $result = $this->db->get();
       
        if($result){
            return $result->result();
        }
        else{
            return false;
        }

    }

    public function checkDuplicate($data,$id=NULL)
	 {
		
		$this->db->select('package_name');
		$this->db->from('packages');
		$this->db->group_start();
		$this->db->like('package_name',trim($data['package_name']));
		$this->db->group_end();
		
		if($id!=NULL){
			$this->db->where_not_in('package_id',[$id]);
		}
		$result = $this->db->get();
		
		if($result->num_rows()==0){
            return true;
		}
		else{
            return false;
		}
    }
    
    public function delete_test($package_id){
        $this->db->where('test_package_packages_id',$package_id);
        $result = $this->db->delete('test_packages');

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
      $where['ul.source_module'] = 'Packages';
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