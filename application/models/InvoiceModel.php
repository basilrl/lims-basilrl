<?php


/**
 * Description of InvoiceModel
 *
 * @author Abhishek
 */
class InvoiceModel extends MY_Model{
  
    public function allInvoices($limit = NULL, $start = NULL, $search = NULL, $sortby, $order, $where, $count = NULL){
        
        $this->db->limit($limit, $start);
       
        if ($sortby != NULL || $order != NULL) {
            $this->db->order_by($sortby, $order);
        } else {
            $this->db->order_by('inv.invoiced_id ', 'desc');
        }
        $this->db->where($where);
        
        if ($search != NULL) {
//             $this->db->or_like('inv.report_num', $search);
            
        }
        //sr.sample_reg_id,
//        $this->db->select('inv.*,  '
//                . '(select customer_name from cust_customers where customer_id = trf.trf_applicant) AS client, '
//                . '(select customer_name from cust_customers where customer_id = trf.trf_buyer)  AS trf_buyer, '
//                . 'concat(ap.admin_fname, " ", ap.admin_lname) as created_by,'
//                . ' trf.trf_ref_no')
//                ->from('Invoices as inv')
//                ->join('invoice_proforma_junction as ipj', 'ipj.invoice_id = inv.invoiced_id ', 'left')
//                ->join('invoice_proforma as ip', 'ip.proforma_invoice_id = ipj.pro_invoice_id ', 'left')
//                ->join('sample_registration as sr', 'sr.sample_reg_id = ip.proforma_invoice_sample_reg_id', 'left')
//                ->join('trf_registration as trf','trf.trf_id = sr.trf_registration_id', 'left')
//                ->join('admin_profile as ap', 'ap.uidnr_admin = inv.generated_by', 'left')
//                ->group_by('inv.invoiced_id');
        $this->db->select('inv.*, '
                . '(select customer_name from cust_customers where customer_id = inv.invoice_customer_id) AS client, '
                . '(select customer_name from cust_customers where customer_id = trf.trf_buyer)  AS trf_buyer, '
                . 'concat(ap.admin_fname, " ", ap.admin_lname) as created_by,'
                . ' trf.trf_ref_no')
                ->from('Invoices as inv')
                ->join('invoice_proforma_junction as ipj', 'ipj.invoice_id = inv.invoiced_id ', 'left')
                ->join('invoice_proforma as ip', 'ip.proforma_invoice_id = ipj.pro_invoice_id ', 'left')
                ->join('sample_registration as sr', 'sr.sample_reg_id = ip.proforma_invoice_sample_reg_id', 'left')
                ->join('trf_registration as trf','trf.trf_id = sr.trf_registration_id', 'left')
                ->join('admin_profile as ap', 'ap.uidnr_admin = inv.generated_by', 'left')
                ->group_by('inv.invoiced_id');
                
        $query = $this->db->get();
        // echo "<pre>"; print_r($query->result_array()); die;
        //  echo $this->db->last_query(); die;
        if ($count === '1') {
            return $query->num_rows();
        } else {
            if ($query->result_array()) {             
                return $query->result_array();
            } else {
                return false;
            }
        }
    }

    public function dynamic_test_price_details($invoice_id){
         $dynamic_data = $this->db->select('idp.*, inv.total_amount, inv.tax_percentage, '
                 . 'inv.final_amount_after_tax, '
                 . 'inv.tax_currency, mst_currency.currency_code, inv.vat_prod_posting_group')
                 ->join('Invoices as inv', 'inv.invoiced_id = idp.invoice_id', 'left')
                 ->join('mst_currency', 'mst_currency.currency_id = inv.tax_currency', 'left')
                 ->get_where('invoice_dynamic_prices as idp',['invoice_id' => $invoice_id]);
        if($dynamic_data->num_rows() > 0){
            return $dynamic_data->result_array();
        }else{
            return false;
        }
    }
    
    public function invoice_payment_details($invoice_id){
       $dynamic_data = $this->db->select('ip.*')               
                  ->get_where('invoice_payment as ip',['invoice_id' => $invoice_id]);
        if($dynamic_data->num_rows() > 0){
            return $dynamic_data->result_array();
        }else{
            return false;
        } 
    }
    
    
    public function invoice_details($id){
       
//        $this->db->select('ip.invoice_proforma_customer_id')
//                ->from('Invoices as inv')
//                ->join('invoice_proforma_junction as ipj', 'ipj.invoice_id  = inv.invoiced_id', 'left')
//                ->join('invoice_proforma as ip', 'ip.proforma_invoice_id  = ipj.pro_invoice_id', 'left')
//                ->where('inv.invoiced_id', $id)
//                ->group_by('inv.invoiced_id');
        $this->db->select('inv.invoice_customer_id')
                ->from('Invoices as inv')
                ->where('inv.invoiced_id', $id);
        $customerQuery = $this->db->get();

        if ($customerQuery->num_rows() > 0) {
            $customer_id = $customerQuery->row_array()['invoice_customer_id'];
        }


        $query = $this->db->query("SELECT                                          
                                          inv.inspection_qty,
                                          inv.inspection_date_bl,
                                          inv.vessel_name, 
                                          inv.sample_rec_date,
                                          inv.product,
                                          inv.supply_date,
                                          inv.certificate_report_no,
                                          inv.contract_no,
                                          inv.lpo_no,
                                          inv.total_amount, 
                                          inv.nomination_contact,
                                          inv.customer_email,
                                          inv.contact_person_name,
                                          inv.job_ref_no,
                                          inv.style_no,
                                          inv.quotes_ref_no,                                          
                                          inv.tax_percentage,                                          
                                          inv.tax_amount,
                                          inv.vat_prod_posting_group,
                                          inv.proforma_invoice_id,
                                          cust.nav_customer_code,                                            
                                          cust.address AS customer_address,                                          
                                          country_name AS country,
                                          country_code,
                                          province_name AS state,
                                          location_name AS location,
                                          cust.city,
                                          cust.po_box, 
                             (SELECT currency_code 
                                   FROM mst_currency 
                                   WHERE mst_currency.currency_id = inv.tax_currency
                              ) AS tax_currency
                   FROM Invoices as inv   
                   INNER JOIN invoice_proforma_junction as ipj ON ipj.invoice_id  = inv.invoiced_id 
                   INNER JOIN invoice_proforma as ip ON ip.proforma_invoice_id  = ipj.pro_invoice_id   
                   
                   INNER JOIN cust_customers cust ON cust.customer_id = $customer_id
                   INNER JOIN mst_country  mctry ON mctry.country_id=cust.cust_customers_country_id 
                   LEFT JOIN mst_provinces  mpro ON mpro.province_id = cust.cust_customers_province_id  
                   LEFT JOIN mst_locations  mloc ON mloc.location_id = cust.cust_customers_location_id  
                   WHERE inv.invoiced_id = $id ");

        // echo $this->db->last_query(); die;
        if($query->num_rows() > 0){
            return $query->result()[0];
        }
        return [];
        
    }
    
    public function invoice_details1($id){
       
        $this->db->select('trf_id, trf_invoice_to') 
                ->from('Invoices as inv')
                ->join('invoice_proforma as ip', 'ip.proforma_invoice_id  = inv.proforma_invoice_id', 'left')
                ->join('sample_registration','proforma_invoice_sample_reg_id = sample_reg_id')
                ->join('trf_registration','trf_id = trf_registration_id') 
                ->where('invoiced_id', $id);
        $trf_query = $this->db->get();
        
        if($trf_query->num_rows() > 0){
            $invoice_to_data = $trf_query->row_array();
            $customer_type = $invoice_to_data['trf_invoice_to'];
            if($customer_type == 'Factory'){
                $customer_id = 'trf.trf_applicant';
            } elseif($customer_type == 'Buyer') {
                $customer_id = 'trf.trf_buyer';
            } elseif($customer_type == 'Agent') {
                $customer_id = 'trf.trf_agent';
            } else {
                $customer_id = 'trf.trf_thirdparty';
            }
        }
  
         
        $query = $this->db->query("SELECT cust.gstin as newgstinnumber,
                                          sr.sample_desc,
                                          ip.proforma_invoice_number AS proforma_invoice_number,
                                          trf_invoice_to, 
                                          proforma_invoice_sample_reg_id, 
                                          ip.show_discount, 
                                          inv.inspection_qty,
                                          inv.inspection_date_bl,
                                          inv.vessel_name, 
                                          inv.sample_rec_date,
                                          inv.product,
                                          inv.supply_date,
                                          inv.certificate_report_no,
                                          inv.contract_no,
                                          inv.lpo_no,
                                          inv.total_amount, 
                                          inv.nomination_contact,
                                          inv.customer_email,
                                          inv.contact_person_name,
                                          inv.job_ref_no,
                                          inv.style_no,
                                          inv.quotes_ref_no,                                          
                                          inv.tax_percentage,                                          
                                          inv.tax_amount,
                                          inv.vat_prod_posting_group,
                                          trf_product, 
                                          inv.proforma_invoice_id,
                                          trf_id, 
                                          cust.customer_name AS customer,
                                          cust.customer_code AS applicant_code,
                                          cust.nav_customer_code, 
                                          trf.trf_ref_no AS reference_no,
                                          (select GROUP_CONCAT(reference_no) AS reference_no 
                                               FROM quotes 
                                              WHERE  FIND_IN_SET(quote_id,trf.trf_quote_id)
                                           ) AS quote_ref_no,
                                          cust.address AS customer_address, 
                                          CASE 
                                            WHEN service_days!=''
                                            THEN CONCAT(trf_service_type,' ',service_days,' ',' Days')
                                            ELSE  trf_service_type 
                                          END AS service_type,
                                          country_name AS country,
                                          country_code,
                                          province_name AS state,
                                          location_name AS location,
                                          cust.city,
                                          cust.po_box,
                                         (select GROUP_CONCAT(contact_name SEPARATOR ' / ') 
                                            From contacts 
                                           where FIND_IN_SET(contact_id,trf.trf_invoice_to_contact)
                                           ) AS customer_contact,
                                         c.telephone AS contact_telephone,
                                         c.mobile_no AS contact_mobile_no,
                                         c.email AS contact_email, 
                                         CASE 
                                          WHEN trf_type='Open TRF' 
                                          THEN (SELECT currency_name 
                                                   FROM mst_currency 
                                                  WHERE  currency_id=open_trf_currency_id
                                                  ) 
                                          ELSE (SELECT currency_name 
                                                   FROM mst_currency 
                                                   WHERE  currency_id=quotes_currency_id
                                                   ) END AS quotes_currency, 
                                          CASE 
                                           WHEN trf_type='Open TRF' 
                                           THEN (SELECT currency_code 
                                                   FROM mst_currency 
                                                   WHERE  currency_id=open_trf_currency_id
                                                   ) 
                                           ELSE (SELECT currency_code 
                                                    FROM mst_currency 
                                                    WHERE  currency_id=quotes_currency_id
                                                    ) 
                                          END AS quote_currency_code, 
                                          CASE 
                                           WHEN trf_type='Open TRF' 
                                           THEN (SELECT currency_basic_unit FROM mst_currency WHERE  currency_id=open_trf_currency_id) 
                                           ELSE  (SELECT currency_basic_unit FROM mst_currency WHERE  currency_id=quotes_currency_id) 
                                          END AS currency_basic_unit, 
                                          CASE
                                           WHEN trf_type='Open TRF' 
                                           THEN (SELECT currency_decimal FROM mst_currency WHERE  currency_id=open_trf_currency_id) 
                                           ELSE  (SELECT currency_decimal FROM mst_currency WHERE  currency_id=quotes_currency_id)
                                          END AS  currency_decimal, 
                                          CASE 
                                           WHEN trf_type='Open TRF' 
                                           THEN (SELECT currency_code FROM mst_currency WHERE  currency_id=open_trf_currency_id) 
                                           ELSE  (SELECT currency_code FROM mst_currency WHERE  currency_id=quotes_currency_id) 
                                          END AS  currency_code,
                                          CASE 
                                           WHEN trf_type='Open TRF' 
                                           THEN (SELECT currency_fractional_unit FROM mst_currency WHERE  currency_id=open_trf_currency_id) 
                                           ELSE (SELECT currency_fractional_unit FROM mst_currency WHERE  currency_id=quotes_currency_id) 
                                          END AS currency_fractional_unit,
                                          (SELECT sample_type_name
                                              FROM mst_sample_types 
                                              WHERE  sample_type_id=sample_registration_sample_type_id
                                              ) as sample_name,
                                          CASE 
                                           WHEN trf_invoice_to='Factory'
                                           THEN (SELECT province_name 
                                                   FROM mst_provinces 
                                                     INNER JOIN cust_customers cs ON cs.cust_customers_province_id=province_id 
                                                  WHERE customer_id=trf_applicant
                                                  )
                                           WHEN trf_invoice_to='Buyer' 
                                           THEN (SELECT province_name
                                                    FROM mst_provinces 
                                                     INNER JOIN cust_customers cs ON cs.cust_customers_province_id=province_id 
                                                    WHERE customer_id=trf_buyer
                                                    ) 
                                           ELSE (SELECT province_name 
                                                   FROM mst_provinces 
                                                   INNER JOIN cust_customers cs ON cs.cust_customers_province_id=province_id 
                                                  WHERE customer_id=trf_agent
                                                  )
                                           END AS state1, 
                                           '' AS 'GST',
                                           '' AS 'total_amount_with_gst',
                                           '' AS 'VAT', 
                                           (SELECT customer_name 
                                              FROM cust_customers 
                                              WHERE customer_id=trf.trf_buyer
                                              ) AS buyer, 
                                           (SELECT customer_name 
                                              FROM cust_customers
                                                WHERE customer_id=trf.trf_agent
                                                ) AS agent,
                                           (SELECT gstin FROM cust_customers 
                                                 WHERE customer_id=trf.trf_buyer
                                            ) AS gstin,
                                            DATE_FORMAT( sr.received_date, '%d-%m-%Y %H:%i' ) AS proforma_invoice_date,
                                            (select division_name 
                                               From mst_divisions 
                                               where trf.division = mst_divisions.division_id
                                               ) as division,
                                              tat_date, 
                                              due_date,
                                              service_days AS sr_days,
                                              gc_no AS test_report_no,
                            (SELECT country_name 
                                FROM mst_country 
                              WHERE country_id=trf_country_orgin
                             ) AS trf_country_of_orgin,
                             (SELECT currency_code 
                                   FROM mst_currency 
                                   WHERE mst_currency.currency_id = inv.tax_currency
                              ) AS tax_currency
                   FROM invoice_proforma as ip  
                   INNER JOIN Invoices as inv
                      ON ip.proforma_invoice_id  = inv.proforma_invoice_id  
                   INNER JOIN sample_registration sr 
                      ON sr.sample_reg_id=ip.proforma_invoice_sample_reg_id  
                   INNER JOIN  trf_registration trf 
                      ON trf.trf_id=sr.trf_registration_id 
                   LEFT JOIN quotes qt
                      ON qt.quote_id =(SELECT quote_id FROM quotes 
                                           WHERE  quote_id IN (trf.trf_quote_id) LIMIT 1 )
                   INNER JOIN cust_customers cust
                      ON cust.customer_id = $customer_id "
                . "INNER JOIN mst_country  mctry"
                . "   ON mctry.country_id=cust.cust_customers_country_id"
                . " LEFT JOIN mst_provinces  mpro "
                . "   ON mpro.province_id = cust.cust_customers_province_id "
                . "LEFT JOIN mst_locations  mloc "
                . "   ON mloc.location_id = cust.cust_customers_location_id "
                . "INNER JOIN contacts c "
                . "   ON c.contact_id = trf.trf_contact  "
                . "WHERE inv.invoiced_id='$id'");

        // echo $this->db->last_query(); die;
        if($query->num_rows() > 0){
            return $query->result()[0];
        }
        return [];
        // changed trf.trf_applicant to trf.trf_invoice_to_contact for Name change in proforma Invoice
    }
    
    
     public function get_invoice_details($id){
        
        $this->db->select('trf_id,trf_invoice_to');
        $this->db->join('sample_registration','proforma_invoice_sample_reg_id = sample_reg_id');
        $this->db->join('trf_registration','trf_id = trf_registration_id');
        $this->db->where('proforma_invoice_id', $id);
        $trf_query = $this->db->get('invoice_proforma');
        
        if($trf_query->num_rows() > 0){
            $invoice_to_data = $trf_query->row_array();
            $customer_type = $invoice_to_data['trf_invoice_to'];
            if($customer_type == 'Factory'){
                $customer_id = 'trf.trf_applicant';
            } elseif($customer_type == 'Buyer') {
                $customer_id = 'trf.trf_buyer';
            } elseif($customer_type == 'Agent') {
                $customer_id = 'trf.trf_agent';
            } else {
                $customer_id = 'trf.trf_thirdparty';
            }
        }
         
        $query = $this->db->query("SELECT 
                      gr.report_num,
                    cust.gstin as newgstinnumber, sr.sample_desc,
                    ip.proforma_invoice_number AS proforma_invoice_number, 
                    trf_invoice_to, proforma_invoice_sample_reg_id, 
                    ip.show_discount, total_amount, 
                    trf_product, proforma_invoice_id, trf_id, cust.customer_name AS customer,
            cust.customer_code AS applicant_code, cust.nav_customer_code, trf.trf_ref_no AS reference_no,(select GROUP_CONCAT(reference_no) AS reference_no FROM quotes WHERE  FIND_IN_SET(quote_id,trf.trf_quote_id)) AS quote_ref_no,cust.address AS customer_address,  CASE WHEN service_days!='' THEN CONCAT(trf_service_type,' ',service_days,' ',' Days') ELSE  trf_service_type END AS service_type,country_name AS country,province_name AS state,location_name AS location,cust.city,cust.po_box, /*CONCAT( c.contact_salutation, '. ', c.contact_name)*/ (select GROUP_CONCAT(contact_name SEPARATOR ' / ') from contacts where FIND_IN_SET(contact_id,trf.trf_invoice_to_contact)) AS customer_contact,c.telephone AS contact_telephone,c.mobile_no AS contact_mobile_no,c.email AS contact_email, CASE WHEN trf_type='Open TRF' THEN (SELECT currency_name FROM mst_currency WHERE  currency_id=open_trf_currency_id) ELSE (SELECT currency_name FROM mst_currency WHERE  currency_id=quotes_currency_id) END AS quotes_currency, CASE WHEN trf_type='Open TRF' THEN (SELECT currency_code FROM mst_currency WHERE  currency_id=open_trf_currency_id) ELSE (SELECT currency_code FROM mst_currency WHERE  currency_id=quotes_currency_id) END AS quote_currency_code, CASE WHEN trf_type='Open TRF' THEN (SELECT currency_basic_unit FROM mst_currency WHERE  currency_id=open_trf_currency_id) ELSE  (SELECT currency_basic_unit FROM mst_currency WHERE  currency_id=quotes_currency_id) END AS currency_basic_unit, CASE WHEN trf_type='Open TRF' THEN (SELECT currency_decimal FROM mst_currency WHERE  currency_id=open_trf_currency_id) ELSE  (SELECT currency_decimal FROM mst_currency WHERE  currency_id=quotes_currency_id) END AS  currency_decimal, CASE WHEN trf_type='Open TRF' THEN (SELECT currency_code FROM mst_currency WHERE  currency_id=open_trf_currency_id) ELSE  (SELECT currency_code FROM mst_currency WHERE  currency_id=quotes_currency_id) END AS  currency_code, CASE WHEN trf_type='Open TRF' THEN (SELECT currency_fractional_unit FROM mst_currency WHERE  currency_id=open_trf_currency_id) ELSE (SELECT currency_fractional_unit FROM mst_currency WHERE  currency_id=quotes_currency_id) END AS currency_fractional_unit,
            (SELECT sample_type_name FROM mst_sample_types  
            WHERE  sample_type_id=sample_registration_sample_type_id)
            as sample_name, CASE WHEN trf_invoice_to='Factory' THEN     
                    (SELECT province_name FROM mst_provinces INNER JOIN cust_customers cs ON cs.cust_customers_province_id=province_id WHERE customer_id=trf_applicant)
                   WHEN trf_invoice_to='Buyer' THEN 
                   (SELECT province_name FROM mst_provinces INNER JOIN cust_customers cs ON cs.cust_customers_province_id=province_id WHERE customer_id=trf_buyer) 
                   ELSE (SELECT province_name FROM mst_provinces 
                   INNER JOIN cust_customers cs ON cs.cust_customers_province_id=province_id 
                   WHERE customer_id=trf_agent)
                    END AS state1, '' AS 'GST','' AS 'total_amount_with_gst','' AS 'VAT', (SELECT customer_name FROM cust_customers WHERE customer_id=trf.trf_buyer) AS buyer, (SELECT customer_name FROM cust_customers WHERE customer_id=trf.trf_agent) AS agent, (SELECT gstin FROM cust_customers WHERE customer_id=trf.trf_buyer) AS gstin,DATE_FORMAT( sr.received_date, '%d-%m-%Y %H:%i' ) AS proforma_invoice_date,(select division_name from mst_divisions where trf.division = mst_divisions.division_id) as division,/*DATE_FORMAT(DATE_ADD(sr.received_date, INTERVAL 
                CASE WHEN trf_service_type='Regular' THEN 3
                WHEN trf_service_type='Express' THEN 2
                WHEN trf_service_type='Urgent' THEN 1 END                
                DAY), '%d-%m-%Y' ) AS due_date*/ tat_date, due_date,service_days AS sr_days,
                gc_no AS test_report_no,
                (SELECT country_name FROM mst_country WHERE country_id=trf_country_orgin) AS trf_country_of_orgin 
                   FROM invoice_proforma ip  INNER JOIN sample_registration sr 
                   ON sr.sample_reg_id=ip.proforma_invoice_sample_reg_id  
                   INNER JOIN  trf_registration trf ON trf.trf_id=sr.trf_registration_id 
                   LEFT JOIN quotes qt ON qt.quote_id =(SELECT quote_id FROM quotes 
                   WHERE  quote_id IN (trf.trf_quote_id) LIMIT 1 )
                   INNER JOIN cust_customers cust ON cust.customer_id=$customer_id "
                . "INNER JOIN mst_country  mctry ON mctry.country_id=cust.cust_customers_country_id"
                . " LEFT JOIN mst_provinces  mpro ON mpro.province_id=cust.cust_customers_province_id "
                . "LEFT JOIN mst_locations  mloc ON mloc.location_id=cust.cust_customers_location_id "
                . "left join generated_reports as gr ON gr.sample_reg_id = sr.sample_reg_id "
                . "INNER JOIN contacts c ON c.contact_id = trf.trf_contact   "
                . "WHERE proforma_invoice_id='$id'");

        if($query->num_rows() > 0){
            return $query->row_array();
        }
        return [];
        
    }
    
    public function get_gc_nums($proforma_inv_id) {
        return
          $this->db->select('ip.proforma_invoice_number, ip.proforma_invoice_id')
                        ->from('invoice_proforma as ip')
                        ->join('sample_registration sr', 'sr.sample_reg_id = ip.proforma_invoice_sample_reg_id')
                        ->where('ip.invoice_proforma_invoice_status_id !=', 16)
                        ->where('ip.invoice_proforma_invoice_status_id', 4)
                        ->where('ip.invoice_proforma_customer_id in(SELECT invoice_proforma_customer_id
                          from invoice_proforma WHERE proforma_invoice_id  =  ' . $proforma_inv_id . ')', null, false)
                        ->where('sr.division_id in(SELECT division_id
                          from sample_registration inner join invoice_proforma 
                          on sample_registration.sample_reg_id = invoice_proforma.proforma_invoice_sample_reg_id WHERE proforma_invoice_id  =  ' . $proforma_inv_id . ')', null, false)
                        ->get()
                        ->result_array();
    }
    
    public function clients_details($invoice_id){
        
     $clientsQuery =   $this->db->select('inv.invoice_customer_id, trf_registration.trf_applicant,'
                . ' trf_registration.trf_buyer, trf_registration.trf_agent')
                ->from('Invoices as inv')
                ->join('invoice_proforma_junction as ipj', 'ipj.invoice_id  = inv.invoiced_id', 'left')
                ->join('invoice_proforma as ip', 'ip.proforma_invoice_id  = ipj.pro_invoice_id', 'left')
                ->join('sample_registration','proforma_invoice_sample_reg_id = sample_reg_id')
                ->join('trf_registration','trf_id = trf_registration_id')
                ->where('inv.invoiced_id', $invoice_id)
                ->group_by('inv.invoiced_id')
                ->get()
                ->row_array();
     
       $clientIds = array(
            $clientsQuery['invoice_customer_id'],
            $clientsQuery['trf_applicant'],
            $clientsQuery['trf_buyer'],
            $clientsQuery['trf_agent']
        );
       
       $customer_ids = array_filter(array_unique($clientIds));
       
       return $this->db->select('customer_id, customer_name')
                       ->from('cust_customers')
                       ->where_in('customer_id', $customer_ids)
                       ->get()
                       ->result_array();
    }
    
    public function clients_proforma_details($proforma_invoice_id){
        
     $clientsQuery =   $this->db->select('ip.invoice_proforma_customer_id, trf_registration.trf_applicant,'
                . ' trf_registration.trf_buyer, trf_registration.trf_agent')
                ->from('invoice_proforma as ip')          
                ->join('sample_registration','ip.proforma_invoice_sample_reg_id = sample_reg_id')
                ->join('trf_registration','trf_id = trf_registration_id')
                ->where('ip.proforma_invoice_id', $proforma_invoice_id)
                ->get()
                ->row_array();
     
       $clientIds = array(
            $clientsQuery['invoice_proforma_customer_id'],
            $clientsQuery['trf_applicant'],
            $clientsQuery['trf_buyer'],
            $clientsQuery['trf_agent']
        );
       
       $customer_ids = array_filter(array_unique($clientIds));
       
       return $this->db->select('customer_id, customer_name')
                       ->from('cust_customers')
                       ->where_in('customer_id', $customer_ids)
                       ->get()
                       ->result_array();
    }

}
