<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Test_protocols_model extends MY_Model
{

	public function __construct()
	{
		parent::__construct();
	}

	public function get_protocol_list($limit = NULL, $start = NULL, $search = NULL, $sortby, $order, $where, $count = NULL)
	{
		$this->db->limit($limit, $start);

		if ($count == NULL) {
			if ($sortby != NULL || $order = NULL) {
				$this->db->order_by($sortby, $order);
			} else {
				$this->db->order_by('pto.protocol_id', 'DESC');
			}
		}
		if ($where) {
			$this->db->where($where);
		}

		if ($search != NULL) {

			$search = trim($search);
			$this->db->group_start();
			$this->db->like('pto.protocol_name', $search);
			$this->db->or_like('pto.protocol_reference', $search);
			$this->db->or_like('pto.protocol_type', $search);
			$this->db->or_like('pto.protocol_price', $search);
			$this->db->or_like('sample.sample_type_name', $search);
			$this->db->group_end();
		}


		$result = $this->db->select("pto.*,sample.sample_type_name as sample_name")
			->from("protocols as pto")
			->join("mst_sample_types as sample", "sample.sample_type_id=pto.protocol_sample_type_id", "left")
			->get();



		if ($count) {
			return $result->num_rows();
		} else {
			if ($result) {
				return $result->result();
			} else {
				return false;
			}
		}
	}


	//  insert protocols

	public function insert_protocols($data,$country_ids,$buyer_ids)
	{
		$this->db->trans_begin();

		$this->db->insert('protocols', $data);
		$id = $this->db->insert_id();
		$this->db->where('protocol_id', $id)->delete('protocol_country');
		$this->db->where('protocol_id', $id)->delete('protocol_buyers');

		$LOG = array();
		$LOG['source_module'] = 'Test_protocols';
		$LOG['record_id'] = $id;
		$LOG['created_on'] = date("Y-m-d h:i:s");
		$LOG['created_by'] = $this->user;
		$LOG['action_taken'] = 'add_protocol';
		$LOG['text'] = 'PROTOCOL ADDED WITH NAME '.$data['protocol_name'];

		$this->insert_data('user_log_history',$LOG);

		foreach ($country_ids as $key => $value) {
			$multi[$key] = ['protocol_country_id' => $value, 'protocol_id' => $id];
		}
		$this->db->insert_batch('protocol_country', $multi);

		foreach ($buyer_ids as $key => $value) {
			$multi_by[$key] = ['protocol_buyer_id' => $value, 'protocol_id' => $id];
		}
		$this->db->insert_batch('protocol_buyers', $multi_by);
		$this->db->trans_complete();
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			return false;
		} else {
			$this->db->trans_commit();
			return true;
		}
	}


	public function get_country($country_ids)
	{
		$result = $this->db->select('country_id,country_name')
			->where_in('country_id', $country_ids)
			->get('mst_country');
		if ($result->num_rows() > 0) {
			return $result->result();
		} else {
			return false;
		}
	}

	public function update_protocols($data,$country_ids,$buyer_ids,$protocol_id)
	{
		$this->db->trans_begin();
		$this->db->where('protocol_id', $protocol_id);
		$this->db->update('protocols', $data);

		$this->db->where('protocol_id',$protocol_id)->delete('protocol_buyers');
		$this->db->where('protocol_id',$protocol_id)->delete('protocol_country');
	
		foreach ($country_ids as $key => $value) {
			$multi[$key] = ['protocol_country_id' => $value, 'protocol_id' => $protocol_id];
		}
		
		$this->db->insert_batch('protocol_country', $multi);
	
		foreach ($buyer_ids as $key => $value) {
			$multi_by[$key] = ['protocol_buyer_id' => $value, 'protocol_id' => $protocol_id];
		}
		$this->db->insert_batch('protocol_buyers', $multi_by);
	

		$this->db->trans_complete();
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			return false;
		} else {
			$this->db->trans_commit();
			return true;
		}
	}

	public function get_countryNbuyers($protocol_id,$setvalue=NULL)
	{
		$this->db->trans_begin();
		$this->db->select('protocol_country_id')
			->from('protocol_country')
			->where('protocol_id', $protocol_id);
		$result1 = $this->db->get();
		if ($result1->num_rows() > 0) {
			$setvalue['country'] = $result1->result();
		}else{
			$setvalue['country'] =[];
		}


		$this->db->select('protocol_buyer_id')
			->from('protocol_buyers')
			->where('protocol_id', $protocol_id);
		$result2 = $this->db->get();
		if ($result2->num_rows() > 0) {
			$setvalue['buyer'] = $result2->result();
		}else{
			$setvalue['buyer']=[];
		}
		
		$this->db->trans_complete();
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			return false;
		} else {
			$this->db->trans_commit();
			return $setvalue;
		}
	}

	public function check_duplicate($data){
		
		$this->db->select('protocol_name');
		$this->db->from('protocols');
		$this->db->group_start();
		$this->db->like('protocol_name',trim($data['name']));
		$this->db->group_end();
	
		if($data['id']!=NULL){
			$this->db->where_not_in('protocol_id',[$data['id']]);
		}
		$check = $this->db->get();

	//  print_r($this->db->last_query());exit;
		if($check->num_rows()==0){
			return true;
		}
		else{
			return false;
		}

	}


	public function getTestList($protocol_id){
        $result=$this->db->select('pt.*,pt.protocol_id as id,test.test_name as test_name,test.test_method as test_method,pt.protocol_test_sort_order as order')
                    ->where('pt.protocol_id',$protocol_id)
                    ->from('protocol_tests pt')
                    ->join('tests as test','test.test_id= pt.protocol_test_id','left')
                    ->order_by('pt.protocol_test_sort_order','ASC')
                    ->get();
        if($result->num_rows()>0){
            return $result->result();
        }
        else{	
            return false;
        }
    }

    public function insert_test($data,$id){
        $this->db->trans_begin();
        $this->db->where('protocol_id',$id)->delete('protocol_tests');
        $this->db->insert_batch('protocol_tests', $data);
        $this->db->trans_complete();

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			return false;
		} else {
			$this->db->trans_commit();
			return true;
		}
    }

    public function get_price_list($protocol_id){
        $this->db->select('distinct(mst_currency.currency_id),mst_currency.currency_code,mst_country.country_code as country_name,mst_country.country_code as country_code,if(pricelist.price is null,0,pricelist.price) as price');
        $this->db->from('mst_branches');
        $this->db->join("mst_currency","mst_currency.currency_id=mst_branches.mst_branches_currency_id AND mst_branches.status='1'","inner");
        $this->db->join("mst_country","mst_country.country_id=mst_branches.mst_branches_country_id  AND mst_currency.status='1'","inner");
        $this->db->join("pricelist","pricelist.currency_id=mst_currency.currency_id and pricelist.type_id ='".$protocol_id."' and pricelist.type='Protocol' ","left");
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
      $where['ul.source_module'] = 'Test_protocols';
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

