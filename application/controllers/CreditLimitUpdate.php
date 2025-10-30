<?php

defined('BASEPATH') or exit('No direct access allowed');
/**
 * Description of CreditLimitUpdate
 *
 * @author Abhishek
 */
class CreditLimitUpdate extends MY_Controller{
    
    public function __construct() {
        parent::__construct();
    }
    
    public function index() {

        $file = $_FILES['documents']['tmp_name'];
        $handle = fopen($file, 'r');

        $row = 1;

        while (($filesop = fgetcsv($handle)) !== false) {

            if ($row == 1) {
                $row++;
                continue;
            }

            $customer_name = trim($filesop[2]);
            $credit_limit = trim($filesop[8]);
            
            $customer = $this->_customerDeatils($customer_name, $credit_limit);
            if ($customer && !empty($customer) && !empty($customer['nav_customer_code'])) {
                $this->_update_customer($customer, $credit_limit);
            }
            $row++;
        }


        echo "Credit Limit Update Completed!";
    }

    public function index1() {
        $this->load->library('excel');
        $path = $_FILES["documents"]["tmp_name"];
        $object = PHPExcel_IOFactory::load($path);

        $sheet = $object->getActiveSheet();
        $highestRow = $sheet->getHighestRow();

        for ($row = 2; $row < $highestRow; $row++) {
            $credit_limit = trim($sheet->getCell('I' . $row)->getValue());
            $customer_name = trim($sheet->getCell('C' . $row)->getValue());
            $customer = $this->_customerDeatils($customer_name, $credit_limit);
            if($customer && !empty($customer) && !empty($customer['nav_customer_code'])){
                $this->_update_customer($customer, $credit_limit);
            }
        }
        echo "Credit Limit Update Completed!";
    }

    private function _update_customer($customer, $credit_limit) {
      $data = [
            "Line_No" => 0,
            "customerNo" => $customer['nav_customer_code'],
            "LIMSCustomerId" => $customer['customer_code'], 
            "namE" => $customer['customer_name'], 
            "Name2" => "",
            "Address" => $customer['address'],
            "Address2" => "",
            "countryCode" => $this->_setCountryCode($customer['cust_customers_country_id']), 
            "City" => $customer['city'],  
            "State" => "",
            "PostCode" => "",
            "EMail" => $customer['email'],  
            "LocationCode" => "",
            "CreditLimit" => (double) $credit_limit,
            "CreditDays" => $this->_setCreditDays($customer['credit']), 
            "UserID" => "NAV.CPS",
            "TelNo" => $customer['telephone']
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
            
        curl_exec($ch);
        curl_close($ch);  
    }

    private function _customerDeatils($customer_name, $credit_limit) {
        $customer = $this->db->get_where('cust_customers', ['customer_name' => $customer_name])->row_array();
         if(!empty($customer)){
             $this->db->update('cust_customers', ['credit_limit' => $credit_limit], ['customer_name' => $customer_name]);
               return $customer;
         }
        return false;
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
