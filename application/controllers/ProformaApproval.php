<?php 
defined('BASEPATH') or exit('No direct access allowed');

class ProformaApproval extends CI_Controller
{
    function __construct(){
        parent::__construct();
    }

    public function accept_proforma($proforma_invoice_id)
    {
        $proforma_invoice_id = base64_decode($proforma_invoice_id);
        $update = $this->db->update('invoice_proforma',['invoice_proforma_invoice_status_id' => 14],['proforma_invoice_id' => $proforma_invoice_id]);
        echo "Proforma Invoice approved successfully";
    }

    public function reject_proforma($proforma_invoice_id)
    {
        $proforma_invoice_id = base64_decode($proforma_invoice_id);
        $update = $this->db->update('invoice_proforma',['invoice_proforma_invoice_status_id' => 15],['proforma_invoice_id' => $proforma_invoice_id]);
        echo "Proforma Invoice rejected successfully";
    }
}

?>