<?php

require APPPATH . 'libraries/REST_Controller.php';

class Customers extends REST_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model(array('api/Customers_model'));
    }
    public  function index_post()
    {
        $data = json_decode(file_get_contents("php://input"));

         $customer_data =   json_decode($this->input->raw_input_stream, true);
       //  echo "<pre>"; print_r($customer_data);die;
        $cust_type = $customer_data['cust_type'];
        $customer_name = $customer_data['customer_name'];
        $city = $customer_data['city'];
        $cust_customers_country_id = $customer_data['cust_customers_country_id'];
        $email = $customer_data['email'];
        $credit = $customer_data['credit'];
        $mobile = $customer_data['mobile'];
        $credit_limit = $customer_data['credit_limit'];
        $gstin = $customer_data['gstin'];
        $nav_customer_code = $customer_data['nav_customer_code'];

        $_POST = $customer_data;
      
           
        $this->form_validation->set_rules('cust_type', 'Customer Type', 'required');
         $this->form_validation->set_rules('customer_name', 'customer name', 'required');
        $this->form_validation->set_rules('city', 'City', 'required');
        $this->form_validation->set_rules('cust_customers_country_id', 'Customer country', 'required');
         $this->form_validation->set_rules('email', 'email', 'trim|required|valid_email|is_unique[cust_customers.email]');
        $this->form_validation->set_rules('credit', 'credit', 'required');
        $this->form_validation->set_rules('mobile', 'mobile', 'required');
        $this->form_validation->set_rules('credit_limit', 'credit limit', 'required');
        $this->form_validation->set_rules('gstin', 'G.S.T No', 'required|is_unique[cust_customers.gstin]');
        $this->form_validation->set_rules('nav_customer_code', 'nav customer code', 'required');
        if ($this->form_validation->run()===false) {
            $this->response(array(
                "status" => 0,
                "error" => validation_errors()
              ), 404);
        } else {


                $Customers = array(
                    'cust_type' => $cust_type,
                    'customer_name' => $customer_name,
                    'email' => $email,
                    'mobile' => $mobile,
                    'cust_customers_country_id' => $cust_customers_country_id,
                    'city' => $city,
                    'credit' => $credit,
                    'credit_limit' => $credit_limit,
                    'gstin' => $gstin,
                    'nav_customer_code' => $nav_customer_code
                );
               
                if ($this->Customers_model->insert_Customers($Customers)) {
                    $this->response(array(
                        "status" => 1,
                        "message" => "Customers has been created"
                    ), 200);
                } else {

                    $this->response(array(
                        "status" => 0,
                        "message" => "failed to create"
                    ), 404);
                }
            
            
        }
    }
    public function index_put()
    {
      $data= json_decode(file_get_contents("php://input"));

      if(isset($data->nav_customer_code) && isset($data->cust_type) && isset($data->customer_name) && isset($data->email) && isset($data->mobile) && isset($data->cust_customers_country_id) && isset($data->city)  && isset($data->credit)  && isset($data->credit_limit) && isset($data->gstin) ){
$nav_customer_code = $data->nav_customer_code;
$customer_info = array(
                    'cust_type' => $data->cust_type,
                    'customer_name' => $data->customer_name,
                    'email' => $data->email,
                    'mobile' => $data->mobile,
                    'cust_customers_country_id' => $data->cust_customers_country_id,
                    'city' => $data->city,
                    'credit' => $data->credit,
                    'credit_limit' => $data->credit_limit,
                    'gstin' => $data->gstin
);
if($this->Customers_model->update_Customers($nav_customer_code,$customer_info)){
    $this->response(array(
        "status" => 1,
        "message" => "Customers has been Updated"
    ), 200);
} else {
     $this->response(array(
        "status" => 0,
        "message" => "failed to Update"
    ), 404);
}

  }else{
          $this->response(array(
              "status" =>0,
              "message" => "All fields are needed"
          ),404);
      }
    }


 


    public  function index_get($customer_name= null ,$nav_customer_code = null,$customer_id=0,$gstin=0)
    {
        $Customers = $this->Customers_model->get_Customers($customer_name, $nav_customer_code, $customer_id, $gstin);
        if (count($Customers) > 0) {
            $this->response(array(
                "status" => 1,
                "message" => "Customers found",
                "data" => $Customers
            ), 200);
        } else {
            $this->response(array(
                "status" => 0,
                "message" => " NO Customers found",
                "data" => $Customers
            ), 404);
        }
    }
}
