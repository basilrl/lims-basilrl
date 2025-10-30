<?php

require APPPATH . 'libraries/REST_Controller.php';

use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;

class ERP_Invoice extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('api/ERP_Invoice_Model', 'ERPI');
    }

    private function _set_invoice_pdf_file_name($gc_number = NULL)
    {
        $pdf_path = rand(10000, 99999) . "_invoice.pdf";
        if (!empty($gc_number)) {
            $gc_number = str_replace(",", "_", $gc_number);
            $pdf_path = $gc_number . "_" . rand(10000, 99999) . "_invoice.pdf";
        }
        return $pdf_path;
    }

    private function _uploadPDF($pdf_body, $file_name)
    {
        if (!empty($pdf_body) && !empty($file_name)) {

            require '../vendor/autoload.php';

            $keyName = 'invoice/' . $file_name;
            // Set Amazon S3 Credentials
            $s3 = S3Client::factory(
                array(
                    'credentials'   => array(
                        'key'       => SECRET_ACCESS_KEY,
                        'secret'    => SECRET_ACCESS_CODE
                    ),
                    'version'   => 'latest',
                    'region'    => 'ap-south-1',
                    'signature' => 'v4',
                )
            );
            try {
                // Put on S3
                $result = $s3->putObject(
                    array(
                        'Bucket'        => BUCKETNAME,
                        'Key'           => $keyName,
                        'Body'          => $pdf_body,
                        'Content=Type'  => 'application/pdf',
                        'ACL'           => 'public-read'
                    )
                );

                if ($result['ObjectURL']) {
                    return array('aws_path' => $result['ObjectURL']);
                } else {
                    return false;
                }
            } catch (S3Exception $e) {
                return $e->getMessage() . PHP_EOL;
            }
        }
    }

    public function uploadInvoicePDF_post()
    {
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '-1');

        $this->form_validation->set_rules('invoice_id', 'Invoice ID', 'required|trim');
        $this->form_validation->set_rules('invoice_pdf', 'Invoice PDF', 'required');

        if ($this->form_validation->run() === false) {
            $this->response(array(
                "status" => 0,
                "error" => validation_errors()
            ), 404);
        } else {

            $gcNo = $this->ERPI->fetch_gc_number($this->input->post('invoice_id'));

            $imageName = $this->_set_invoice_pdf_file_name((isset($gcNo->gc_no) && !empty($gcNo->gc_no)) ? $gcNo->gc_no : NULL);

            $tempFile = "temp.pdf";
            file_put_contents($tempFile, "");
            $data = base64_decode($this->input->post('invoice_pdf'));
            file_put_contents($tempFile, $data);

            $pdfPath = $this->_uploadPDF($data, $imageName);

            if (!empty($pdfPath['aws_path'])) {

                $status = $this->ERPI->uploadInvoicePDF($this->input->post('invoice_id'), $pdfPath['aws_path']);

                if ($status) {

                    $this->ERPI->update_data('invoice_details', ['modify_invoice_flag' => 3], ['invoice_id' => $this->input->post('invoice_id')]);

                    $this->response(array(
                        "status"    => 1,
                        "message"   => "TAX Invoice PDF has been uploaded!!",
                        "aws_path"  => $pdfPath['aws_path']
                    ), 200);
                } else {
                    $this->response(array(
                        "status"    => 0,
                        "message"   => "Failed to upload TAX Invoice PDF!",
                        "aws_path"  => ""
                    ), 404);
                }
            } else {
                $this->response(array(
                    "status"    => 0,
                    "message"   => $pdfPath,
                    "aws_path"  => ""
                ), 404);
            }
        }
    }
}
