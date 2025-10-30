<?php

defined('BASEPATH') or exit('No direct access allowed');

class Orders_model extends MY_Model
{

    function __construct()
    {
        parent::__construct();
    }


    
    public function get_order_list($per_page, $page = 0, $search, $where, $count = NULL){
       

        if ($per_page != NULL || $page != NULL) {
            $this->db->limit($per_page, $page);
        }
        $this->db->order_by('cps_order_item.order_id', 'DESC');

        if (!empty($where)) {
            $this->db->group_start();
            $this->db->where($where);
            $this->db->group_end();
        }
        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('cc.customer_name', $search);
            $this->db->or_like('cps_order_item.update_time', $search);
            $this->db->or_like('cps_order_item.ord_txn_id', $search);
            $this->db->or_like('cps_order_item.order_amount', $search);
            $this->db->or_like('co.email', $search);
            $this->db->or_like('co.contact_name', $search);
            $this->db->or_like('co.mobile_no', $search);
            $this->db->group_end();
        }
        
     
        $this->db->select('cps_order_item.order_id,cc.customer_name,cps_order_item.update_time,cps_order_item.ord_txn_id,cps_order_item.order_amount,co.email,co.contact_name,co.mobile_no');
        $this->db->from('cps_order_item');
        $this->db->join('cust_customers as cc','cps_order_item.customer_id=cc.customer_id','left');
        $this->db->join('contacts as co','cps_order_item.contact_id=co.contact_id','left');
        // $this->db->where('cps_order_item.contact_id',$this->session->userdata['CustomerDetails']->contact->contact_id);
        $this->db->order_by('cps_order_item.update_time','DESC');
       
        $result = $this->db->get();

        if ($count == '1') {
            return $result->num_rows();
        } else {
            if ($result->num_rows() > 0) {
                return $result->result();
            } else {
                return false;
            }
        }
    } 


    public function payslip($id)
    {
        $query = "SELECT it.customer_id,it.contact_id,it.order_amount,DATE(it.update_time) as Date,it.invoice_no,cc.customer_name,cc.po_box,cc.email,cc.address,cc.mobile,cc.pan,cc.city,cc.web,cc.gstin,coun.country_name,pay.name_on_card,pay.card_type,pay.card_num FROM `cps_order_item` as it LEFT JOIN cust_customers as cc on it.customer_id=cc.customer_id LEFT JOIN mst_country as coun on cc.cust_customers_country_id=coun.country_id LEFT JOIN cps_payment_detail as pay on it.order_id=pay.cps_order_item_id WHERE `order_id` = '$id' ";
        return $this->db->query($query)->result_array();
       
    }

    // public function getorderpro($id)
    // {
    //     $sql = "SELECT st.sample_type_name,st.sample_type_id,pro.order_product_id,pro.pro_qty,pro.remark,pro.manyColor,pro.colorNo
    //             FROM cps_order_product as pro LEFT JOIN mst_sample_types as st on 
    //             pro.product_id=st.sample_type_id WHERE pro.order_order_id = '$id' ";

       
    //     $data = $this->db->query($sql)->result_array();
       
    //     $sn = 0;
    //     foreach ($data as $pro) {
    //         $proid = $pro['order_product_id'];
    //         $test = "SELECT tst.test_name,tst.test_method,pl.price,mc.currency_code,cpstst.test_qty 
    //         FROM cps_order_test as cpstst LEFT JOIN tests as tst ON cpstst.test_id=tst.test_id
    //          LEFT JOIN pricelist as pl on cpstst.test_id=pl.pricelist_test_id 
    //          LEFT JOIN mst_currency as mc on pl.currency_id=mc.currency_id
    //          WHERE order_item_id = '$id' AND order_product_id = '$proid' AND tst.is_available_customerportal = '1' ";
    //         $data[$sn]['tests'] = $this->db->query($test)->result_array();
    //         $sn++;
    //     }
      

    //     return $data;
    // }

    public function getorderpro($id) {
        $sql = "SELECT st.sample_type_name,st.sample_type_id,pro.order_product_id,pro.pro_qty,pro.remark,pro.manyColor,pro.colorNo, lab.country_name as lab_location, pro_des.country_name as product_destination, test_standard_name, certificate_name
                FROM cps_order_product as pro LEFT JOIN mst_sample_types as st on 
                pro.product_id=st.sample_type_id LEFT JOIN mst_country lab on lab.country_id = lab_location_id  LEFT JOIN mst_country pro_des on pro_des.country_id = product_destination_id LEFT JOIN cps_test_standard on testing_standard_id = cps_test_standard.id left join cps_certificate on certificate_id = cps_certificate.id WHERE pro.order_order_id = '$id' ";


        $data = $this->db->query($sql)->result_array();
        
        $sn = 0;
        foreach ($data as $pro) {
            $proid = $pro['order_product_id'];
            $lab_location = $pro['lab_location'];
            $product_destination = $pro['product_destination'];
            $test = "SELECT tst.test_name,tst.test_method,pl.price,mc.currency_code,cpstst.test_order_count as test_qty
            FROM cps_order_test as cpstst LEFT JOIN tests as tst ON cpstst.test_id=tst.test_id
             LEFT JOIN pricelist as pl on cpstst.test_id=pl.pricelist_test_id 
             LEFT JOIN mst_currency as mc on pl.currency_id=mc.currency_id
             WHERE order_item_id = '$id' AND order_product_id = '$proid'  ";
            // echo $test = "SELECT * from cps_order_test where order_item_id = '$id'";
            $data[$sn]['tests'] = $this->db->query($test)->result_array();
            $sn++;
        }

        // echo "<pre>"; print_r($result); die;
        return $data;
    }
}