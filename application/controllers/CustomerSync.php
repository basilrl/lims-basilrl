<?php
defined('BASEPATH') OR exit('No direct script access allowed');          

/**
 * Description of CustomerSync
 *
 * @author Abhishek
 */
class CustomerSync extends CI_Controller {
   
    public function __construct() {
        parent::__construct();
         $this->check_session();
    }
    
    
     private function check_session() {
        $checkUser = $this->session->userdata('user_data');
        if (empty($checkUser)) {
            redirect('Login');
        }
    }
    
    
    public function index(){

        $data['total_customers'] = $this->db->select('distinct(customer_name) as customer_name, '
                                      . ' customer_code, address, city, email,'
                                      . ' credit_limit, cust_customers_country_id, credit, telephone, gstin')
                                ->from('cust_customers')
                                ->group_by('customer_name')
                                ->get()
                                ->num_rows();
        $this->load->view('includes/header');
        $this->load->view('includes/menu1');
        $this->load->view("customerSync/syncCustomer", $data);
        $this->load->view('includes/footer');
    }
    
    public function customer_sync(){
         $limit =  $this->input->get('limit');
            
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '-1');
        $start_time = microtime(true);
        $customers =   $this->db->select('distinct(customer_name) as customer_name, customer_code, '
                . 'address, city, email,'
                . ' credit_limit, cust_customers_country_id, credit, telephone, gstin')
                                ->from('cust_customers')
                                ->group_by('customer_name')
                                ->limit(250, $limit)
                                ->get();
        
        
       
        $totalCount = count($customers->result_array());
        $found = 0;
        $notFound = 0;
            
        if(!empty($customers->result_array())){
            foreach ($customers->result_array() as $k=>$customer){
                
                $url = CUST_GET_API;
                $search_string = $customer['customer_name'];
                $q  = '%24filter=';
                $q1 = 'Name eq ' . "'$search_string'";
                $url .= $q . str_replace("+", "%20",urlencode($q1));
            
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    ERP_AUTH_KEY
//            'Username:nav.cps',
//            'password:9MwMK5VxqtbM2oB+fyELIzWyKrrw72ML2mqi1j7V5Sw='
                ));
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
                $result = curl_exec($ch);
                $r = json_decode($result);
                curl_close($ch);
          
                if (empty($r->value)) {
                    $this->_addCustomerOnBC365($customer);
                } elseif (isset($r->value) && !empty($r->value)) {
                    $this->db->update('cust_customers', ['nav_customer_code' => $r->value[0]->CustomerNo, 'accop_cust' => 1], ['customer_name' => $customer['customer_name']]);
                    if (empty($r->value[0]->LIMSCustomerId)) {
                        $customer['nav_customer_code'] = $r->value[0]->CustomerNo;
                        $this->_update_customer($customer);
                    }
                }

            }
//        echo "customer total = ". $totalCount. " found = ". $found. " not found = ". $notFound;
            $end_time = microtime(true);
            $execution_time = ($end_time - $start_time);
            echo " Execution time of script = " . ($execution_time/60) . " min"."<br>";
            echo "Customer Sync is completed for $limit - 250!";
        }
    }
    
    private function formatUri($queryString) {
        return str_replace(
                ['$', ' '],
                ['%24', '%20'],
                $queryString
        );
    }
   
    private function _addCustomerOnBC365($customer) {
         $address1 = "";
         $address2 = "";
         if(strlen($customer['address']) > 100){
           $address =  str_split($customer['address'], 100);
           $address1 = $address[0];
           $address2 = $address[1];
         }else{
            $address1 =  $customer['address'];
         }
        $data = array(
            "Line_No" => 0,
            "customerNo" => "",
            "LIMSCustomerId" => $customer['customer_code'], 
            "namE" => $customer['customer_name'], 
            "Name2" => "",
            "Address" => $address1,
            "Address2" => $address2,
            "countryCode" => $this->_setCountryCode($customer['cust_customers_country_id']),
            "City" => $customer['city'],  
            "State" => "",
            "PostCode" => "",
            "EMail" => $customer['email'],  
            "LocationCode" => "",
            "CreditLimit" => (double) $customer['credit_limit'],
            "CreditDays" => $this->_setCreditDays($customer['credit']), //credit
            "UserID" => "NAV.CPS",
            "TelNo" => $customer['telephone'],
             "VATRegistrationNo" => $customer['gstin']
        );

        // Convert the PHP array into a JSON format
        $payload = json_encode($data);
        $url = CUST_ADD_API;
        // Initialise new cURL session
        $ch = curl_init($url);

         curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
         curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        // Return result of POST request
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Get information about last transfer
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);

        // Use POST request
        curl_setopt($ch, CURLOPT_POST, true);

         // Set payload for POST request
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
 
        // Set HTTP Header for POST request 
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
             ERP_AUTH_KEY,
            'Content-Type: application/json',
            'Content-Length: ' . strlen($payload))
        );
        // Execute a cURL session
        $result = curl_exec($ch);
        $r = json_decode($result);
      
        if(isset($r->customerNo) && !empty($r->customerNo)){
          $this->db->update('cust_customers',  ['nav_customer_code' => $r->customerNo, 'accop_cust' => 1],  ['customer_name' => $customer['customer_name']]);   
        }
        // Close cURL session
        curl_close($ch);
    }
    
    private function _update_customer($customer) {
         $address1 = "";
         $address2 = "";
         if(strlen($customer['address']) > 100){
           $address =  str_split($customer['address'], 100);
           $address1 = $address[0];
           $address2 = $address[1];
         }else{
            $address1 =  $customer['address'];
         }
      $data = [
            "Line_No" => 0,
            "customerNo" => $customer['nav_customer_code'],
            "LIMSCustomerId" => $customer['customer_code'], 
            "namE" => $customer['customer_name'], 
            "Name2" => "",
            "Address" => $address1,
            "Address2" => $address2,
            "countryCode" => $this->_setCountryCode($customer['cust_customers_country_id']), 
            "City" => $customer['city'],  
            "State" => "",
            "PostCode" => "",
            "EMail" => $customer['email'],  
            "LocationCode" => "",
            "CreditLimit" => (double) $customer['credit_limit'],
            "CreditDays" => $this->_setCreditDays($customer['credit']), //credit $customer['credit'] ? : $this->_setCreditDays($customer['credit'])
            "UserID" => "NAV.CPS",
            "TelNo" => $customer['telephone'],
             "VATRegistrationNo" => $customer['gstin']
            ];     
            
        $payload = json_encode($data);
        $url = CUST_UPDATE_API;
            
        $ch = curl_init($url);

         curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
         curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
            
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
             ERP_AUTH_KEY,
            'Content-Type: application/json',
            'Content-Length: ' . strlen($payload))
        );
            
        $result = curl_exec($ch);
        curl_close($ch);  
    }

    private function _setCreditDays($creditDays) {
        $credit_days = "1";
        switch ($creditDays){
            case "30 Days" :
                $credit_days ="2";
                break;
            case "45 Days" :
                $credit_days ="3";
                break;
        }
        return $credit_days;
    }

    private function _setCountryCode($country_id) {
       $country_code = "";
       if(!empty($country_id)){
         $country = $this->db->get_where('mst_country', ['country_id' => $country_id])->row_array();   
         $country_code = $country['bc365_county_id'];
       }
     return $country_code;
    }
    

}
