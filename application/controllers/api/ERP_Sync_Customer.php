<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ERP_Sync_Customer extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('api/ERP_Sync_Customer_Model', 'ESCM');
        $this->load->helper('url');
    }

    public function update_nav_customer_code($nav_customer_code, $column_name, $value)
    {
        if (!empty($nav_customer_code) && !empty($column_name) && !empty($value)) {

            $custData = $this->ESCM->check_customers_data($column_name, $value);
            // echo "<pre>";
            // print_r($custData);
            // echo "<br>";
            // echo $nav_customer_code."<br>";

            if (isset($custData->customer_id) && !empty($custData->customer_id)) {

               $result = $this->ESCM->update_data('cust_customers', ['nav_customer_code' => $nav_customer_code], ['customer_id' => $custData->customer_id]);

                return ($result) ? false : true;
            } else {
                return true;
            }
        }
    }

    public function sync_nav_customer_code()
    {
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '-1');

        $url = ERP_GET_ALL_CUSTOMER_URL;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $url);
        $result = curl_exec($ch);
        curl_close($ch);
        $data = json_decode($result);

        $data1 = str_replace("<m:", "<", $data->Data->m_StringValue);
        $data2 = str_replace("</m:", "</", $data1);
        $data3 = str_replace("<d:", "<", $data2);
        $data4 = str_replace("</d:", "</", $data3);

        $xml_string = simplexml_load_string($data4);

        $totalUpdatedData = 0;

        foreach ($xml_string->entry as $key1 => $val1) {
            foreach ($val1->content as $key2 => $val2) {
                foreach ($val2->properties as $key3 => $val3) {

                    $nav_customer_code = (!empty(trim($val3->No))) ? trim($val3->No) : NULL;
                    $customer_name = (!empty(trim($val3->Name))) ? trim($val3->Name) : NULL;
                    $gstin = (!empty(trim($val3->GST_Registration_No))) ? trim($val3->GST_Registration_No) : NULL;
                    $tan = (!empty(trim($val3->TAN_No))) ? trim($val3->TAN_No) : NULL;
                    $pan = (!empty(trim($val3->P_A_N_No))) ? trim($val3->P_A_N_No) : NULL;

                    echo 'CODE: ---> ' . $nav_customer_code . '<br/>';
                    echo 'NAME: ---> ' . $customer_name . '<br/>';
                    // echo 'GST: ---> ' . $gstin . '<br/>';
                    // echo 'TAN: ---> ' . $tan . '<br/>';

                    // echo 'No: ---> ' . $val3->No . '<br/>';
                    // echo 'Payment_Terms_Code: ---> ' . $val3->Payment_Terms_Code . '<br/>';
                    // echo 'Invoice_Disc_Code: ---> ' . $val3->Invoice_Disc_Code . '<br/>';
                    // echo 'Country_Region_Code: ---> ' . $val3->Country_Region_Code . '<br/>';
                    // echo 'Address: ---> ' . $val3->Address . '<br/>';
                    // echo 'Address_2: ---> ' . $val3->Address_2 . '<br/>';
                    // echo 'City: ---> ' . $val3->City . '<br/>';
                    // echo 'Phone_No: ---> ' . $val3->Phone_No . '<br/>';
                    // echo 'No: ---> ' . $val3->No . '<br/>';

                    // echo 'PAN: ---> ' . $pan . '<br/>-------------------------------<br/><br/>';



                    if (!empty($nav_customer_code)) {

                        $isUpdate = true;

                        // Update "nav_customer_code" by CUSTOMER_NAME...
                        if ($isUpdate && !empty($customer_name)) {

                            $isUpdate = $this->update_nav_customer_code($nav_customer_code, 'customer_name', $customer_name);
                            if ($isUpdate == false) {
                                $totalUpdatedData++;
                            }
                        }

                        // Update "nav_customer_code" by GST_NO...
                        if ($isUpdate && !empty($gstin)) {

                            $isUpdate = $this->update_nav_customer_code($nav_customer_code, 'gstin', $gstin);
                            if ($isUpdate == false) {
                                $totalUpdatedData++;
                            }
                        }

                        // Update "nav_customer_code" by TAN_NO...
                        if ($isUpdate && !empty($tan)) {

                            $isUpdate = $this->update_nav_customer_code($nav_customer_code, 'tan', $tan);
                            if ($isUpdate == false) {
                                $totalUpdatedData++;
                            }
                        }

                        // Update "nav_customer_code" by PAN_NO...
                        if ($isUpdate && !empty($pan)) {

                            $isUpdate = $this->update_nav_customer_code($nav_customer_code, 'pan', $pan);
                            if ($isUpdate == false) {
                                $totalUpdatedData++;
                            }
                        }
                    } 
                }
            }
        }

        $totalRemainingData = $this->ESCM->check_not_updated_customers();

        echo "------------------------ CUSTOMER'S DATA ---------------------<br/>";
        echo "TOTAL UPDATE : " . $totalUpdatedData . "<br/><br/>";
        echo "REMAINING DATA : " . $totalRemainingData . "<br/><br/>";
        exit();
    }
}
