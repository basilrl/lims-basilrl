<?php
defined('BASEPATH') OR exit('No direct script access allowed');

    class Temp_model extends MY_model{

        public function __construct(){
            parent::__construct();
        }


        public function getcustomer($search){

          $result = $this->db->select("customer_id,customer_name")
                            ->from('cust_customers')
                            ->like('customer_name',$search)
                            ->where_in('customer_type',['Factory','Agent'])
                            ->where('isactive','Active')
                            
                            ->order_by('customer_name','asc')
                            ->get();
                        
          if($result){
              return $result->result();
          }
          else{
              return false;
          }

        }

        public function getBuyers($key,$search){
           
            $sql1 = $this->db->select("customer.customer_id,customer.customer_name")
                                ->from("cust_customers as customer")
                                ->join("buyer_factory as buyer",'buyer.buyer_id=customer.customer_id','left')
                                ->group_start()
                                ->like('customer.customer_name',$search)
                                ->group_end()
                                ->where(["customer.customer_type"=>"buyer","isactive"=>"Active","factory_id"=>$key])
                                ->get_compiled_select();

             $sql2 = $this->db->select("customer.customer_id,customer.customer_name")
                                ->from("cust_customers as customer")
                                ->join("buyer_agent as buyer",'buyer.buyer_id=customer.customer_id','left')
                                ->group_start()
                                ->like('customer.customer_name',$search)
                                ->group_end()
                                ->where(["customer.customer_type"=>"buyer","isactive"=>"Active","agent_id"=>$key])
                                ->get_compiled_select();

            $result = $this->db->query($sql1 . ' UNION ' . $sql2);

               if($result){
                    return $result->result();
               }
               else{
                   return false;
               }
                
        }


        public function getcontacts($key,$search){

            $result = $this->db->select("contacts.contact_id,contacts.contact_name,contacts.email")
                                ->from("contacts")
                                ->join("cust_customers as customer","customer.customer_id=contacts.contacts_customer_id",'left')
                                ->group_start()
                                ->like('contacts.contact_name',$search)
                                ->group_end()
                                ->where("contacts.status",'1')
                                ->where_in("customer.customer_id",$key)
                                ->get();
                      
            if($result){
                return $result->result();
            }
            else{
                return false;
            }
        }

        public function getCountries($search){
        
        
            $sql1 = $this->db->select("'0' as country_id,'none' as country_name,'' as  country_code")
                            ->from("mst_country")
                            ->group_start()
                            ->like("mst_country.country_name",$search)
                            ->group_end()
                            ->get_compiled_select();

         $sql2 = $this->db->select("mst_country.country_id,mst_country.country_name,mst_country.country_code")
                            ->from("mst_country")
                            ->group_start()
                            ->like("mst_country.country_name",$search)
                            ->group_end()
                            ->Where("status",'1')
                            ->get_compiled_select();
                            
                            $result = $this->db->query($sql1 . " UNION " . $sql2);
                            // echo $this->db->last_query();exit;
            // print_r($result);exit;

            if($result){
                return $result->result();
            }
            else{
                return false;
            }

        }

        public function crm_user_list($search){

           

            $result = $this->db->select("admin_users.uidnr_admin,concat(admin_profile.admin_fname,' ',admin_profile.admin_lname) as user_name")
                                ->from("admin_users")
                                ->join("admin_profile","admin_users.uidnr_admin=admin_profile.uidnr_admin",'left')
                                ->group_start()
                                ->like("admin_profile.admin_fname",$search)
                                ->group_end()
                                ->where("admin_users.crm_flag",'1')
                                ->get();

                // print_r($this->db->last_query());exit;

            if($result){
                return $result->result();
            }
            else{
                return false;
            }
     
         }
         public function insert_temp_reg($data){
           $query =   $this->db->insert('temporary_registration',$data);
           if($query){
               $temp_reg_id = $this->db->insert_id();
               $temp_no = 'GC/TEMP/'.str_pad($temp_reg_id,6,"0",STR_PAD_LEFT);

               $update =  $this->db->where('temp_reg_id', $temp_reg_id)->update('temporary_registration',['temp_no'=>$temp_no]);
               if($update){
                   $log = array(
                       'temp_reg_id'=>$temp_reg_id,
                       'title'=>'Record Inserted',
                       'created_by'=>$data['created_by']
                   );
                   $this->db->insert('temporary_reg_log',$log);
                   return $temp_no;
                }
                else{
                    return false;
               }
               
           }
           else{
               return false;
           }
           
         }
         
         public function update_temp_reg($data,$temp_reg_id,$log){
             $result = $this->db->where('temp_reg_id',$temp_reg_id)
             ->update('temporary_registration',$data);
             
                // print_r($this->db->last_query());die;

            if($result){
                $this->db->insert('temporary_reg_log',$log);
                return true;
            }
            else{
                return false;
            }
         }

         public function getTempdetails($temp_reg_id){

            $result = $this->db->select("tr.*,CONCAT(cus.customer_name,'(',REPLACE(REPLACE(REPLACE(cus.address,'\n',''),'\t',''),' ',''),')') AS customer_name,buy.customer_name as buyer,con.contact_name,c_mst.country_name as c_origin,d_mst.country_name as d_origin,concat(admin_profile.admin_fname,' ',admin_profile.admin_lname) as user_name, admin_profile.admin_telephone as mobile, admin_users.admin_email as email")
                                ->from("temporary_registration as tr")
                                ->join("cust_customers as cus","cus.customer_id=tr.customer_id",'left')
                                ->join("cust_customers as buy","buy.customer_id=tr.buyer_id",'left')
                                ->join("contacts as con","con.contact_id=tr.temp_contact",'left')
                                ->join("mst_country as c_mst","tr.temp_country_orgin=c_mst.country_id",'left')
                                ->join("mst_country as d_mst","tr.temp_country_destination=d_mst.country_id",'left')
                                ->join("admin_profile","tr.tempcrm_user_id=admin_profile.uidnr_admin",'left')
                                ->join("admin_users","tr.tempcrm_user_id=admin_users.uidnr_admin",'left')
                                ->where("tr.temp_reg_id",$temp_reg_id)
                                ->order_by("created_on","desc")
                                ->get();

                // print_r($this->db->last_query());exit;
            if($result){
                                 
                return $result->result();

            }
            else{
                return false;
            }

         }

         public function getTempDataList($limit = NULL, $start = NULL, $search = NULL, $sortby, $order, $where, $count = NULL){
            $this->db->limit($limit, $start);

           if($count==NULL){
            if ($sortby != NULL || $order= NULL) {
                $this->db->order_by($sortby, $order);
            }
            else {
                $this->db->order_by('tr.temp_reg_id', 'DESC');
            }
           }
           if ($where) {
            $this->db->where($where);
            }

            if ($search != NULL) {
                $search =trim($search); 
                $this->db->group_start();
                $this->db->like('tr.temp_no', $search);
                $this->db->or_like('cus.customer_name', $search);
                $this->db->or_like('tr.customer_email',$search);
                $this->db->or_like('tr.reference_no',$search);
                $this->db->or_like('tr.sample_receiving_date',$search);
                $this->db->or_like('tr.report_date',$search);
                $this->db->or_like('tr.service',$search);
                $this->db->or_like('tr.style_no',$search);
                $this->db->or_like('tr.po_no',$search);
                $this->db->or_like('tr.colour',$search);
                $this->db->or_like('buy.customer_name',$search);
                $this->db->or_like('con.contact_name',$search);
                $this->db->or_like('con.email',$search);
                $this->db->or_like('c_mst.country_name',$search);
                $this->db->or_like('d_mst.country_name',$search);
                $this->db->group_end();
            }
            if ($where) {
                $this->db->where($where);
            }

             $result = $this->db->select("tr.*,cus.customer_name,buy.customer_name as buyer,con.contact_name,con.email,c_mst.country_name as c_origin,d_mst.country_name as d_origin,concat(admin_profile.admin_fname,' ',admin_profile.admin_lname) as user_name")
                                ->from("temporary_registration as tr")
                                ->join("cust_customers as cus","cus.customer_id=tr.customer_id",'left')
                                ->join("cust_customers as buy","buy.customer_id=tr.buyer_id",'left')
                                ->join("contacts as con","con.contact_id=tr.temp_contact",'left')
                                ->join("mst_country as c_mst","tr.temp_country_orgin=c_mst.country_id",'left')
                                ->join("mst_country as d_mst","tr.temp_country_destination=d_mst.country_id",'left')
                                ->join("admin_profile","tr.tempcrm_user_id=admin_profile.uidnr_admin",'left')
                                ->get();

                            // print_r($this->db->last_query());exit;

            if ($count) {
                 return $result->num_rows();
                        } 
            else {
                 if ($result){
                     return $result->result();
                }
                else
                        return false;
                }

         }


         public function get_templist(){
             $result = $this->db->select('temp_no')
                                ->from('temporary_registration')
                                ->get();
        
            if($result){
                return $result->result();
            }
            else{
                return false;
            }
         }
         public function get_custby_id($customer_id){
            $result = $this->db->select('customer_id,customer_name')
                               ->from('cust_customers')
                               ->where('customer_id',$customer_id)
                               ->get();
       
           if($result){
               return $result->row();
           }
           else{
               return false;
           }
        }

        public function get_buyerby_id($buyer_id){


            $sql1 = $this->db->select('customer.customer_id,customer.customer_name"')
                                ->from('cust_customers as customer')
                                ->join("buyer_factory as buyer",'buyer.buyer_id=customer.customer_id','left')
                                ->where(["customer.customer_type"=>"buyer","isactive"=>"Active","factory_id"=>$buyer_id])
                                ->get_compiled_select();

            $sql2 = $this->db->select('customer.customer_id,customer.customer_name"')
                                ->from('cust_customers as customer')
                                ->join("buyer_agent as buyer",'buyer.buyer_id=customer.customer_id','left')
                                ->where(["customer.customer_type"=>"buyer","isactive"=>"Active","agent_id"=>$buyer_id])
                                ->get_compiled_select();

            $result = $this->db->query($sql1 . ' UNION ' . $sql2);
                                
            if($result){
                return $result->row();
            }
            else{
                return false;
            }
        }

        public function get_ref(){
            $result = $this->db->select('tr.reference_no')
                                ->from('temporary_registration tr')
                                ->group_by('tr.reference_no')
                                ->get();
            if($result){
                return $result->result();
            }
            else{
                return false;
            }
        }

       public function mail_log($log){
         
           $this->db->insert('temp_mail_log',$log);
       }
       

      
    }
   