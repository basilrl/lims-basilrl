<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Basil_Sync_Customer extends MY_Controller
{
    private $basil="";
    public function __construct()
    {
        parent::__construct();

        $this->load->model('api/Basil_Sync_Customer_Model', 'ESCM');
        $this->load->helper('url');
        $this->basil=$this->load->database('basil',true);
    }
    
    public function syncBasilCustomer(){
        echo "<pre>";
        $this->basil->select('insp_customer_details.*, cn.country_name, c.city_name, insp_customertype.customer_type,insp_customer_acc_junction.customer_login_id')
                ->from('insp_customer_details')
                ->join('insp_country as cn', 'cn.country_id = insp_customer_details.country_id', 'left')
                ->join('insp_city as c', 'c.city_id=insp_customer_details.city_id', 'left')
                ->join('insp_customer_man_day_range as cmd', 'cmd.customer_id=insp_customer_details.customer_details_id', 'left')
                ->join('insp_customertype', 'insp_customertype.customertype_id = insp_customer_details.customertype_id', 'left')
                ->join('insp_customer_acc_junction', 'insp_customer_acc_junction.customer_id = insp_customer_details.customer_details_id', 'left');
      
     
        $query = $this->basil->get();
        
        $result=$query->result();
           echo "<pre>";
                print_r($result);
                //exit;
         //exit;
        foreach($result as $row){
       
//           
//             print_r($basilcontactdata);
//         exit;
            $where=array("basil_customer_details_id"=>$row->customer_details_id);
            $this->db->where($where);
            $res=$this->db->get('cust_customers');
                if($res->num_rows()==0){
                    if($row->customer_type=='Supplier'){
                        $row->customer_type="Thirdparty";
                    }
                $insertData=array(
                    "customer_name"=>$row->company_name,
                    "email"=>$row->email,
                    "customer_type"=>$row->customer_type,
                    "isactive"=>'Active',
                    "address"=>$row->address,
                    "customer_logo_filepath"=>$row->customer_logo,
                    "mobile"=>$row->phone_no,
                    "pan"=>$row->pan_no,
                    "basil_customer_details_id"=>$row->customer_details_id,
                );
               
                $this->db->insert('cust_customers',$insertData);
//                 echo "<pre>";
//                print_r($insertData);
//                exit;
                }
               //exit; 
            $sql="select * from cust_customers where basil_customer_details_id=".$row->customer_details_id;
            $res=$this->db->query($sql);
            $limsdata=$res->row();
//            print_r($row);
//            print_r($limsdata);
            $sql="select * from insp_customer_contact where cust_id=".$row->customer_details_id;
            $res=$this->basil->query($sql);
            $basilcontactdata=$res->result();
                //maping customer contacts to lims data
                foreach($basilcontactdata as $contactdata){
                $where=array("contacts_customer_id"=>$limsdata->customer_id,"contact_name"=>$contactdata->contact_name);
                $this->db->where($where);
                $res=$this->db->get('contacts');
                if($res->num_rows()==0){
                $insertData=array(
                    "contact_name"=>$contactdata->contact_name,
                    "email"=>$contactdata->email,
                    "customer_type"=>$row->customer_type,
                    "status"=>'1',
                    "mobile_no"=>$contactdata->mobile,
                    "contacts_customer_id"=>$limsdata->customer_id
                );
                $this->db->insert('contacts',$insertData);
                }     
                   
                }
                //contct maping end here 
                
                //setting customer relation into lims.
                
            
        }
        
     
        
        
        
       
    }
    
       public function getSupplier($acc_mng_id,$type)
        {
            $this->basil->where('customer_login_id', $acc_mng_id);
            $cc_query = $this->basil->get('insp_customer_acc_junction');
            if($cc_query->num_rows()>0){
            $result = $cc_query->result_array();
            foreach ($result as $row) {
                $customerId[] = $row['customer_id'];
            }
            $this->basil->select('customer_details_id, company_name');
            $this->basil->join('insp_customer_junction', 'buyer_supplier_factory_agent_id = customer_details_id');
            $this->basil->where('insp_customer_junction.customertype_id', $type);
            $this->basil->where_in('customer_id', $customerId);
            $this->basil->where('cust_status','1');
            $this->basil->order_by('company_name','asc');
            $query = $this->basil->get('insp_customer_details');
            if ($query->num_rows() > 0) {
                return $query->result();
            }
            }
            return false;
        }
        public function getLimsCustomerId($customer_details_id){
            $sql="select customer_id from cust_customers where basil_customer_details_id=".$customer_details_id;
            $res=$this->db->query($sql);
            if($res->num_rows()>0){
            $limsdata=$res->row();
            return $limsdata->customer_id;
            } else return false;
        }
       function synCustomerRelations(){
             echo "<pre>";
        $this->basil->select('insp_customer_details.*, cn.country_name, c.city_name, insp_customertype.customer_type,insp_customer_acc_junction.customer_login_id')
                ->from('insp_customer_details')
                ->join('insp_country as cn', 'cn.country_id = insp_customer_details.country_id', 'left')
                ->join('insp_city as c', 'c.city_id=insp_customer_details.city_id', 'left')
                ->join('insp_customer_man_day_range as cmd', 'cmd.customer_id=insp_customer_details.customer_details_id', 'left')
                ->join('insp_customertype', 'insp_customertype.customertype_id = insp_customer_details.customertype_id', 'left')
                ->join('insp_customer_acc_junction', 'insp_customer_acc_junction.customer_id = insp_customer_details.customer_details_id', 'left');
      
        $this->basil->where("insp_customer_details.customertype_id",1);
        $query = $this->basil->get();         
        $result=$query->result();
        
         //exit;
        foreach($result as $row){
           echo "buyer<br>";
            print_r($row);
           // print_r($limsdata);
            $buyerLimsCustomerId=$this->getLimsCustomerId($row->customer_details_id);
            $supplierList=$this->getSupplier($row->customer_login_id,4);
            echo "supplier<br>";
            print_r($supplierList);
            //exit;
            
            if($supplierList){
                foreach($supplierList as $supplier){
                    $supplierLimsCustomerId=$this->getLimsCustomerId($supplier->customer_details_id);
                    if($supplierLimsCustomerId){
                        $insertData=array(
                            'buyer_id'=>$buyerLimsCustomerId,
                            'thirdparty_id'=>$supplierLimsCustomerId
                        );
                        $this->db->insert("buyer_thirdparty",$insertData);
                    }
                }
            }
            $agentList=$this->getSupplier($row->customer_login_id,3);
            if($agentList){
                foreach($agentList as $supplier){
                    $supplierLimsCustomerId=$this->getLimsCustomerId($supplier->customer_details_id);
                    if($supplierLimsCustomerId){
                        $insertData=array(
                            'buyer_id'=>$buyerLimsCustomerId,
                            'agent_id'=>$supplierLimsCustomerId
                        );
                        $this->db->insert("buyer_agent",$insertData);
                    }
                }
            }
            $factoryList=$this->getSupplier($row->customer_login_id,2);
            if($factoryList){
                foreach($factoryList as $supplier){
                    $supplierLimsCustomerId=$this->getLimsCustomerId($supplier->customer_details_id);
                    if($supplierLimsCustomerId){
                    $insertData=array(
                        'buyer_id'=>$buyerLimsCustomerId,
                        'factory_id'=>$supplierLimsCustomerId
                    );
                    $this->db->insert("buyer_factory",$insertData);
                    }
                }
            }

      
                
            
        }
        
            
        }
}
