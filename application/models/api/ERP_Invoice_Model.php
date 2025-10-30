<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ERP_Invoice_Model extends MY_Model
{
    public function fetch_gc_number($inv_id)
    {
        $this->db->select('GROUP_CONCAT(distinct(sr.gc_no)) as gc_no');
        $this->db->from('invoice_details invd');
        $this->db->join('sample_registration sr', 'invd.sample_reg_id = sr.sample_reg_id', 'inner');
        $this->db->where(['invd.invoice_id' => $inv_id, 'invd.sample_reg_id >' => 0, 'invd.modify_invoice_flag <' => 2]);
        $query = $this->db->get();
        return ($query->num_rows() > 0) ? $query->row() : NULL;
    }

    public function uploadInvoicePDF($inv_id, $pdfPath)
    {
        
        $data=array(
        'invoice_pdf_path' => $pdfPath, 
        'tax_status' => 'TAX INVOICE UPDATED', 
        'invoice_type' => 'ERP',
        'process_status_time'=> date('Y-m-d H:i:s'));
        $this->db->where('invoiced_id', $inv_id);
        $result = $this->db->update('Invoices', $data);
        //echo $this->db->last_query(); exit;
        return ($result) ? true : false;
    }
}
