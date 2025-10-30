<?php

defined('BASEPATH') or exit('No direct access allowed');

/**
 * 
 */

use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;

class MY_Controller extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->check_session();
        $this->checkEveryClickUser();
    }

    public function load_view($view_name, $data = null)
    {
        $this->load->view('includes/header');
        $this->load->view('includes/menu1');
        $this->load->view($view_name, $data);
        $this->load->view('includes/footer');
    }

    public function display_view($view_name, $data = null)
    {

        $this->load->view($view_name, $data);
    }

    public function check_session()
    {
        $checkUser = $this->session->userdata('user_data');
        if (empty($checkUser)) {
            redirect('Login');
        }
    }

    public function admin_id()
    {
        $checkUser = $this->session->userdata('user_data');
        return $checkUser->uidnr_admin;
    }

    // Added by Ajit

    // fetch buyers by search
    public function fetch_Buyers()
    {

        $search = $this->input->post('key');
        $result = $this->test_protocols_model->fetchBuyers($search);

        if ($result) {
            echo json_encode($result);
        } else {
            echo json_encode($result);
        }
    }

    public function pagination($base_url, $total_row, $per_page = 5, $uri = 3)
    {
        $config = array();
        $config["base_url"] = base_url($base_url);
        $config["total_rows"] = $total_row;
        $config["per_page"] = $per_page;
        $config["uri_segment"] = $uri;

        $config['full_tag_open'] = '<div class="pagging text-center"><nav><ul class="pagination">';
        $config['full_tag_close'] = '</ul></nav></div>';
        $config['num_tag_open'] = '<li class="page-item"><span class="page-link">';
        $config['num_tag_close'] = '</span></li>';
        $config['cur_tag_open'] = '<li class="page-item active"><span class="page-link">';
        $config['cur_tag_close'] = '<span class="sr-only">(current)</span></span></li>';
        $config['next_tag_open'] = '<li class="page-item"><span class="page-link">';
        $config['next_tag_close'] = '<span aria-hidden="true"></span></span></li>';
        $config['prev_tag_open'] = '<li class="page-item"><span class="page-link">';
        $config['prev_tag_close'] = '</span></li>';
        $config['first_tag_open'] = '<li class="page-item"><span class="page-link">';
        $config['first_tag_close'] = '</span></li>';
        $config['last_tag_open'] = '<li class="page-item"><span class="page-link">';
        $config['last_tag_close'] = '</span></li>';

        $this->pagination->initialize($config);

        $data["page"] = ($this->uri->segment($uri)) ? $this->uri->segment($uri) : 0;
        $data["per_page"] = $per_page;
        $data["links"] = $this->pagination->create_links();

        return $data;
    }

    public function get_auto_list()
    {
        // pre_r($this->input->post());die;
        $search = $this->input->post('key');
        $where = (array) (json_decode(stripslashes($this->input->post('where'))));
        $like = $this->input->post('like');
        $select = $this->input->post('select');
        $table = $this->input->post('table');
        $result = $this->products_model->get_AutoList($select, $table, $search, $like, $where);

        if ($result) {
            echo json_encode($result);
        } else {
            echo json_encode($result);
        }
    }
    public function get_auto_lists()
    {
        // pre_r($this->input->post());die;
        $search = $this->input->post('key');
        $where = (array) (json_decode(stripslashes($this->input->post('where'))));
        $like = $this->input->post('like');
        $select = $this->input->post('select');
        $table = $this->input->post('table');
         

        $result = $this->db->select('ts.test_id as id, ts.test_name, tm.test_method_name')->from('tests ts')->join('mst_test_methods tm', 'tm.test_method_id=ts.test_method_id','LEFT')->like('test_name', $search)->get();

       //  print_r($this->db->last_query());exit;

       
        if ($result) {
            echo json_encode($result->result());
        } else {
            echo json_encode($result);
        }
    }

    public function multiple_checkboxlist()
    {
        $search = $this->input->post('search_key');
        $where = $this->input->post('where');
        $like = $this->input->post('like');
        $select = $this->input->post('select');
        $table = $this->input->post('table');
        $result = $this->products_model->multiple_checkbox_list($select, $table, $search, $like, $where);
        if ($result) {
            echo json_encode($result);
        } else {
            echo json_encode($result);
        }
    }

    public function multiple_upload_image($file_name)
    {
        require '../vendor/autoload.php';
        $s3 = S3Client::factory(
            array(
                'credentials' => array(
                    'key' => SECRET_ACCESS_KEY,
                    'secret' => SECRET_ACCESS_CODE
                ),
                'version' => 'latest',
                'region' => 'ap-south-1', //write your region name 
                'signature' => 'v4',
            )
        );
        if (!empty($file_name)) {
            $data = array();
            $countfiles = is_array($file_name['name']);
            if ($countfiles) {
                for ($i = 0; $i < count($file_name['name']); $i++) {
                    $file_temp = $file_name['tmp_name'][$i];
                    $filter_image_name = sanitizeFileName($file_name['name'][$i]);
                    $path_parts = pathinfo($filter_image_name);
                    //filename
                    $fName = $path_parts['filename'];
                    //file extension
                    $fExtension = $path_parts['extension'];
                    $file_type = $fExtension;
                    $image = $fName . '-' . date('d-M') . '-' . rand(1000, 9999) . '.' . $fExtension;
                    $keyName = 'ci_lims/image/' . $image;

                    try {
                        $result = $s3->putObject(
                            array(
                                'Bucket' => BUCKETNAME,
                                'Key' => $keyName,
                                'Body' => fopen($file_temp, 'r+'),
                                'ContentType' => $file_type,
                                'ACL' => 'public-read',
                                'SourceFile' => $file_temp,
                                'StorageClass' => 'STANDARD'
                            )
                        );

                        if ($result['ObjectURL']) {
                            // unlink($filePath);
                            //$data= $result['ObjectURL'];
                            $data[$i] = $result['ObjectURL'];
                        }
                    } catch (S3Exception $e) {
                        echo $this->session->set_flashdata('error', $e->getMessage());
                        return false;
                    } catch (Exception $e) {
                        echo $this->session->set_flashdata('error', $e->getMessage());
                        return false;
                    }
                }
                return $data;
            } else {

                $file_temp = $file_name['tmp_name'];
                $filter_image_name = sanitizeFileName($file_name['name']);
                $path_parts = pathinfo($filter_image_name);
                //filename
                $fName = $path_parts['filename'];
                //file extension
                $fExtension = $path_parts['extension'];
                $file_type = $fExtension;
                $image = $fName . '-' . date('d-M') . '-' . rand(1000, 9999) . '.' . $fExtension;
                $keyName = 'ci_lims/image/' . $image;

                try {
                    $result = $s3->putObject(
                        array(
                            'Bucket' => BUCKETNAME,
                            'Key' => $keyName,
                            'Body' => fopen($file_temp, 'r+'),
                            'ContentType' => $file_type,
                            'ACL' => 'public-read',
                            'SourceFile' => $file_temp,
                            'StorageClass' => 'STANDARD'
                        )
                    );

                    if ($result['ObjectURL']) {
                        // unlink($filePath);
                        $data['aws_path'] = $result['ObjectURL'];
                        return $data;
                    }
                } catch (S3Exception $e) {
                    echo $this->session->set_flashdata('error', $e->getMessage());
                    return false;
                } catch (Exception $e) {
                    echo $this->session->set_flashdata('error', $e->getMessage());
                    return false;
                }
                return false;
            }
        }
    }

    public function upload_thumb_aws($thumb_path, $file_name)
    {
        require '../vendor/autoload.php';

        $keyName = 'ci_lims/image/' . $file_name;
        // Set Amazon S3 Credentials
        $s3 = S3Client::factory(
            array(
                'credentials' => array(
                    'key' => SECRET_ACCESS_KEY,
                    'secret' => SECRET_ACCESS_CODE
                ),
                'version' => 'latest',
                'region' => 'ap-south-1', //write your region name 
                'signature' => 'v4',
            )
        );
        try {
            // Put on S3
            $result = $s3->putObject(
                array(
                    'Bucket' => BUCKETNAME,
                    'Key' => $keyName,
                    'Body' => fopen($thumb_path, 'r+'),
                    'Content=Type' => filetype($thumb_path),
                    'ACL' => 'public-read'
                )
            );

            if ($result['ObjectURL']) {
                return array('aws_path' => $result['ObjectURL']);
            } else {
                return false;
            }
        } catch (S3Exception $e) {
            echo $e->getMessage() . PHP_EOL;
        }
    }



    function generate_image_thumbnail($filename, $source_image_path, $thumbnail_image_path)
    {

        list($source_image_width, $source_image_height, $source_image_type) = getimagesize($source_image_path);
        switch ($source_image_type) {
            case IMAGETYPE_GIF:
                $source_gd_image = imagecreatefromgif($source_image_path);
                break;
            case IMAGETYPE_JPEG:
                $source_gd_image = imagecreatefromjpeg($source_image_path);
                break;
            case IMAGETYPE_PNG:
                $source_gd_image = imagecreatefrompng($source_image_path);
                break;
        }
        if ($source_gd_image === false) {
            return 'error';
        }

        $source_aspect_ratio = $source_image_width / $source_image_height;
        $thumbnail_aspect_ratio = THUMB_WIDTH / THUMB_HEIGHT;
        if ($source_image_width <= THUMB_WIDTH && $source_image_height <= THUMB_HEIGHT) {
            $thumbnail_image_width = THUMB_WIDTH;
            $thumbnail_image_height = THUMB_HEIGHT;
        } elseif ($thumbnail_aspect_ratio > $source_aspect_ratio) {
            $thumbnail_image_width = (int) (THUMB_WIDTH * $source_aspect_ratio);
            $thumbnail_image_height = THUMB_HEIGHT;
        } else {
            $thumbnail_image_width = THUMB_WIDTH;
            $thumbnail_image_height = (int) (THUMB_HEIGHT * $source_aspect_ratio);
        }
        $thumbnail_gd_image = imagecreatetruecolor($thumbnail_image_width, $thumbnail_image_height);
        imagecopyresampled($thumbnail_gd_image, $source_gd_image, 0, 0, 0, 0, $thumbnail_image_width, $thumbnail_image_height, $source_image_width, $source_image_height);

        $img_disp = imagecreatetruecolor(THUMB_WIDTH, THUMB_WIDTH);
        $backcolor = imagecolorallocate($img_disp, 255, 255, 255);
        imagefill($img_disp, 0, 0, $backcolor);

        imagecopy($img_disp, $thumbnail_gd_image, (imagesx($img_disp) / 2) - (imagesx($thumbnail_gd_image) / 2), (imagesy($img_disp) / 2) - (imagesy($thumbnail_gd_image) / 2), 0, 0, imagesx($thumbnail_gd_image), imagesy($thumbnail_gd_image));

        $filename = 'thumb-' . pathinfo($filename, PATHINFO_FILENAME) . '.jpg';
        imagejpeg($img_disp, $thumbnail_image_path . $filename, 90);

        imagedestroy($source_gd_image);
        imagedestroy($thumbnail_gd_image);
        imagedestroy($img_disp);
        return $filename;
    }

    public function generate_pdf($view_file_name, $data, $type = 'view', $file_name = 'document.pdf')
    {
        set_time_limit(0);



        // ini_set('max_execution_time', 0);
        $this->load->library('M_pdf');
        $this->m_pdf->pdf->charset_in = 'UTF-8';
        $this->m_pdf->pdf->setAutoBottomMargin = 'stretch';
        $this->m_pdf->pdf->setAutoTopMargin = 'stretch';
        $this->m_pdf->pdf->lang = 'en';
        $this->m_pdf->pdf->autoLangToFont = true;
        $this->m_pdf->pdf->showImageErrors = true;
        $html = $this->load->view($view_file_name, $data, true);

        //$html = mb_convert_encoding($html, 'UTF-8', 'UTF-8');
        // exit;
        $this->m_pdf->pdf->WriteHTML($html);
        //$this->m_pdf->pdf->showImageErrors = true;
        $this->m_pdf->pdf->curlAllowUnsafeSslRequests = true;
        if ($type == 'aws_save') {
            $pdf_body = $this->m_pdf->pdf->Output($file_name, 'S');
            if ($pdf_body) {
                return $pdf_body;
            } else {
                return false;
            }
        } else {
            $this->m_pdf->pdf->Output($file_name, 'I');
            die;
        }
    }

    public function pdf($view_file, $data = NULL, $file_path = NULL)
    {
        require '../vendor/autoload.php';
        $mpdf = new \Mpdf\Mpdf();
        $mpdf = new Mpdf(
            ['tempDir' => '/tmp']
        );
        $html = $this->load->view($view_file, $data, true);
        $mpdf->WriteHTML($html);
        $file_name = "ProformaInvoice-" . $data['invoice_detail']->proforma_invoice_id . ".pdf";
        $mpdf->SetTitle($file_name);
        $final_path = $file_path . $file_name;
        if ($file_path) {

            $mpdf->Output($final_path, 'F');
        } else {
            # FOR VIEW
            $mpdf->Output($final_path, 'I');
        }
        $folder_structure = "assets/ProformaInvoice/";
        $upload = $this->uploadpdf($final_path, $folder_structure);

        if ($update_file) {
            return true;
        } else {
            return false;
        }
    }

    public function uploadpdf($file_name, $folder_sturcture)
    {
        require '../vendor/autoload.php';
        if (!empty($file_name)) {

            $keyName = $folder_sturcture . sanitizeFileName(basename($file_name));
            $path_parts = pathinfo($file_name);

            $file_type = $path_parts['extension'];;
            $s3 = S3Client::factory(
                array(
                    'credentials' => array(
                        'key' => SECRET_ACCESS_KEY,
                        'secret' => SECRET_ACCESS_CODE
                    ),
                    'version' => 'latest',
                    'region' => 'ap-south-1', //write your region name 
                    'signature' => 'v4',
                )
            );
            try {
                $result = $s3->putObject(
                    array(
                        'Bucket' => BUCKETNAME,
                        'Key' => $keyName,
                        'Body' => fopen($file_name, 'r+'),
                        'ContentType' => $file_type,
                        'ACL' => 'public-read',
                        'SourceFile' => $file_name,
                        'StorageClass' => 'STANDARD'
                    )
                );
                if ($result['ObjectURL']) {
                    $data['aws_path'] = $result['ObjectURL'];
                    return $data;
                }
            } catch (S3Exception $e) {
                echo $this->session->set_flashdata('error', $e->getMessage());
                return false;
            } catch (Exception $e) {
                echo $this->session->set_flashdata('error', $e->getMessage());
                return false;
            }
            return false;
        }
    }

    public function download_pdf()
    {
        $get = $this->uri->segment(3);
        $file = $this->invoice->get_data_by_id('invoice_proforma', $get, 'proforma_invoice_id');
        if ($file) {
            header("Content-type: application/x-file-to-save");
            header("Content-Disposition: attachment; filename=" . basename($file->file_path));
            ob_end_clean();
            readfile($file->file_path);
            die();
        } else {
            $this->session->set_flashdata('error', 'error');
            redirect($_SERVER['HTTP_REFERER']);
        }
    }

    public function getS3Url($path)
    {
        if (substr($path, 0, 5) == 's3://') {
            $lasturl = str_replace("s3://" . BUCKETNAME, "", $path);
            $s3Url = 'https://' . BUCKETNAME . '.s3.ap-south-1.amazonaws.com' . $lasturl;
            return $s3Url;
        } else {
            return $path;
        }
    }

    public function getS3Url1($path)
    {
        define('BUCKETNAME1', 'cpslims-prod');
        if (substr($path, 0, 5) == 's3://') {
            $lasturl = str_replace("s3://" . BUCKETNAME1, "", $path);
            $s3Url = 'https://' . BUCKETNAME1 . '.s3.ap-south-1.amazonaws.com' . $lasturl;
            return $s3Url;
        } else {
            return $path;
        }
    }

    public function report_upload_aws($pdf_body, $file_name)
    {
        require '../vendor/autoload.php';

        $keyName = 'ci_lims/image/' . $file_name;
        // Set Amazon S3 Credentials
        $s3 = S3Client::factory(
            array(
                'credentials' => array(
                    'key' => SECRET_ACCESS_KEY,
                    'secret' => SECRET_ACCESS_CODE
                ),
                'version' => 'latest',
                'region' => 'ap-south-1',
                'signature' => 'v4',
            )
        );
        try {
            // Put on S3
            $result = $s3->putObject(
                array(
                    'Bucket' => BUCKETNAME,
                    'Key' => $keyName,
                    'Body' => $pdf_body,
                    'Content=Type' => 'application/pdf',
                    'ACL' => 'public-read'
                )
            );

            if ($result['ObjectURL']) {
                return array('aws_path' => $result['ObjectURL']);
            } else {
                return false;
            }
        } catch (S3Exception $e) {
            echo $e->getMessage() . PHP_EOL;
        }
    }

    // added by millan on 18-Jan-2021
    public function uploadQRcode($file_name)
    {
        require '../vendor/autoload.php';
        if (!empty($file_name)) { {
                $data = array();
                // $keyName = $folder_sturcture . sanitizeFileName(basename($file_name));
                $keyName = 'assets/QRcode/' . basename($file_name);
                // Set Amazon S3 Credentials
                $s3 = S3Client::factory(
                    array(
                        'credentials' => array(
                            'key' => SECRET_ACCESS_KEY,
                            'secret' => SECRET_ACCESS_CODE
                        ),
                        'version' => 'latest',
                        'region' => 'ap-south-1', //write your region name 
                        'signature' => 'v4',
                    )
                );
                try {
                    // Create temp file
                    $tempFilePath = LOCAL_PATH . basename($file_name);
                    $type = filetype($tempFilePath);
                    // Put on S3
                    $result = $s3->putObject(
                        array(
                            'Bucket' => BUCKETNAME,
                            'Key' => $keyName,
                            'Body' => fopen($tempFilePath, 'r+'),
                            'ContentType' => $type,
                            'ACL' => 'public-read',
                            'SourceFile' => $tempFilePath,
                            'StorageClass' => 'STANDARD'
                        )
                    );
                    if ($result['ObjectURL']) {
                        // unlink($tempFilePath);
                        return array_merge($data, array('aws_path' => $result['ObjectURL']));
                    }
                } catch (S3Exception $e) {
                    echo $this->session->set_flashdata('error', $e->getMessage());
                    return false;
                } catch (Exception $e) {
                    echo $this->session->set_flashdata('error', $e->getMessage());
                    return false;
                }
                return $data;
            }
        } else {
            return false;
        }
    }

    public function permission($controller_with_function)
    {
        // if ($this->session->has_userdata('permission')) {
        //     if (!in_array($controller_with_function, $this->session->userdata('permission'))) {
        //         show_error('YOU HAVE NO PERMISSION TO ACCESS CONTACT ADMIN', '404', 'NO PERMISSION');
        //     }
        // }
    }

    public function permission_action($controller_with_function)
    {
        return exist_val($controller_with_function, $this->session->userdata('permission'));
    }

    public function upload_image_type_with_body($file_name, $body, $type)
    {
        require '../vendor/autoload.php';

        if (!empty($body) && !empty($type)) {
            $keyName = 'ci_lims/image/' . $file_name;
            // Set Amazon S3 Credentials
            $s3 = S3Client::factory(
                array(
                    'credentials' => array(
                        'key' => SECRET_ACCESS_KEY,
                        'secret' => SECRET_ACCESS_CODE
                    ),
                    'version' => 'latest',
                    'region' => 'ap-south-1', //write your region name 
                    'signature' => 'v4',
                )
            );
            try {
                // Put on S3
                $result = $s3->putObject(
                    array(
                        'Bucket' => BUCKETNAME,
                        'Key' => $keyName,
                        'Body' => $body,
                        'Content=Type' => $type,
                        'ACL' => 'public-read'
                    )
                );

                if ($result['ObjectURL']) {
                    return array('aws_path' => $result['ObjectURL']);
                } else {
                    return false;
                }
            } catch (S3Exception $e) {
                echo $e->getMessage() . PHP_EOL;
            }
        } else {
            # code...
        }
    }

    // added by millan on 06-05-2021
    public function upload_nl_img($file_name)
    {
        require '../vendor/autoload.php';
        $s3 = S3Client::factory(
            array(
                'credentials' => array(
                    'key' => SECRET_ACCESS_KEY,
                    'secret' => SECRET_ACCESS_CODE
                ),
                'version' => 'latest',
                'region' => 'ap-south-1', //write your region name
                'signature' => 'v4',
            )
        );
        if (!empty($file_name)) {
            $data = array();
            $countfiles = is_array($file_name['name']);
            if ($countfiles) {
                for ($i = 0; $i < count($file_name['name']); $i++) {
                    $file_temp = $file_name['tmp_name'][$i];
                    $filter_image_name = sanitizeFileName($file_name['name'][$i]);
                    $path_parts = pathinfo($filter_image_name);

                    //filename
                    $fName = $path_parts['filename'];
                    //file extension
                    $fExtension = $path_parts['extension'];
                    $file_type = $fExtension;
                    $image = $fName . '-' . date('d-M') . '-' . rand(1000, 9999) . '.' . $fExtension;
                    $keyName = 'ci_lims/newsletter/' . DATE('d_m_Y') . '/' . $image;

                    try {
                        $result = $s3->putObject(
                            array(
                                'Bucket' => BUCKETNAME,
                                'Key' => $keyName,
                                'Body' => fopen($file_temp, 'r+'),
                                'ContentType' => $file_type,
                                'ACL' => 'public-read',
                                'SourceFile' => $file_temp,
                                'StorageClass' => 'STANDARD'
                            )
                        );

                        if ($result['ObjectURL']) {
                            // unlink($filePath);
                            //$data= $result['ObjectURL'];
                            $data[$i] = $result['ObjectURL'];
                        }
                    } catch (S3Exception $e) {
                        echo $this->session->set_flashdata('error', $e->getMessage());
                        echo $e->getMessage();
                        exit;
                        return false;
                    } catch (Exception $e) {
                        echo $e->getMessage();
                        exit;
                        echo $this->session->set_flashdata('error', $e->getMessage());
                        return false;
                    }
                }
                return $data;
            } else {

                $file_temp = $file_name['tmp_name'];
                $filter_image_name = sanitizeFileName($file_name['name']);
                $path_parts = pathinfo($filter_image_name);
                //filename
                $fName = $path_parts['filename'];
                //file extension
                $fExtension = $path_parts['extension'];
                $file_type = $fExtension;
                $image = $fName . '-' . date('d-M') . '-' . rand(1000, 9999) . '.' . $fExtension;
                $keyName = 'ci_lims/newsletter/' . date('d_m_Y') . '/' . $image;

                try {
                    $result = $s3->putObject(
                        array(
                            'Bucket' => BUCKETNAME,
                            'Key' => $keyName,
                            'Body' => fopen($file_temp, 'r+'),
                            'ContentType' => $file_type,
                            'ACL' => 'public-read',
                            'SourceFile' => $file_temp,
                            'StorageClass' => 'STANDARD'
                        )
                    );

                    if ($result['ObjectURL']) {
                        // unlink($filePath);
                        $data['aws_path'] = $result['ObjectURL'];
                        return $data;
                    }
                } catch (S3Exception $e) {
                    echo $this->session->set_flashdata('error', $e->getMessage());
                    echo $e->getMessage();
                    exit;
                    return false;
                } catch (Exception $e) {
                    echo $this->session->set_flashdata('error', $e->getMessage());
                    echo $e->getMessage();
                    exit;
                    return false;
                }
                return false;
            }
        }
    }

    public function checkEveryClickUser()
    {
        $id = $this->admin_id();
        $this->db->where(['admin_active' => '0', 'uidnr_admin' => $id]);
        $get = $this->db->get('admin_users');
        if ($get->num_rows() > 0) {
            $this->session->unset_userdata('user_data');
            $this->session->sess_destroy();
            // $this->session->set_flashdata('success', 'Logout Successfully');
            redirect('Login');
        }
    }

    // added by kamal on 1 june 2022;
    public function upload_instruction_img($file_name)
    {
        require '../vendor/autoload.php';
        $s3 = S3Client::factory(
            array(
                'credentials' => array(
                    'key' => SECRET_ACCESS_KEY,
                    'secret' => SECRET_ACCESS_CODE
                ),
                'version' => 'latest',
                'region' => 'ap-south-1', //write your region name
                'signature' => 'v4',
            )
        );
        if (!empty($file_name)) {
            $data = array();
            $countfiles = is_array($file_name);

            if ($countfiles) {
                for ($i = 0; $i < count($file_name); $i++) {
                    $file_temp = $file_name['image']['tmp_name'];
                    $filter_image_name = sanitizeFileName($file_name['image']['name']);
                    $path_parts = pathinfo($filter_image_name);

                    $fName = $path_parts['filename'];

                    $fExtension = $path_parts['extension'];
                    $file_type = $fExtension;
                    $image = $fName . '-' . date('d-M') . '-' . rand(1000, 9999) . '.' . $fExtension;
                    $keyName = 'ci_lims/newsletter/' . DATE('d_m_Y') . '/' . $image;

                    try {
                        $result = $s3->putObject(
                            array(
                                'Bucket' => BUCKETNAME,
                                'Key' => $keyName,
                                'Body' => fopen($file_temp, 'r+'),
                                'ContentType' => $file_type,
                                'ACL' => 'public-read',
                                'SourceFile' => $file_temp,
                                'StorageClass' => 'STANDARD'
                            )
                        );

                        if ($result['ObjectURL']) {
                            // unlink($filePath);
                            $data[$i] = $result['ObjectURL'];
                        }
                    } catch (S3Exception $e) {
                        echo $this->session->set_flashdata('error', $e->getMessage());
                        echo $e->getMessage();
                        exit;
                        return false;
                    } catch (Exception $e) {
                        echo $e->getMessage();
                        exit;
                        echo $this->session->set_flashdata('error', $e->getMessage());
                        return false;
                    }
                }

                return $data;
            } else {

                $file_temp = $file_name['tmp_name'];
                $filter_image_name = sanitizeFileName($file_name['name']);
                $path_parts = pathinfo($filter_image_name);
                $fName = $path_parts['filename'];
                $fExtension = $path_parts['extension'];
                $file_type = $fExtension;
                $image = $fName . '-' . date('d-M') . '-' . rand(1000, 9999) . '.' . $fExtension;
                $keyName = 'ci_lims/newsletter/' . date('d_m_Y') . '/' . $image;

                try {
                    $result = $s3->putObject(
                        array(
                            'Bucket' => BUCKETNAME,
                            'Key' => $keyName,
                            'Body' => fopen($file_temp, 'r+'),
                            'ContentType' => $file_type,
                            'ACL' => 'public-read',
                            'SourceFile' => $file_temp,
                            'StorageClass' => 'STANDARD'
                        )
                    );

                    if ($result['ObjectURL']) {
                        unlink($filePath);
                        $data['aws_path'] = $result['ObjectURL'];
                        return $data;
                    }
                } catch (S3Exception $e) {
                    echo $this->session->set_flashdata('error', $e->getMessage());
                    echo $e->getMessage();
                    exit;
                    return false;
                } catch (Exception $e) {
                    echo $this->session->set_flashdata('error', $e->getMessage());
                    echo $e->getMessage();
                    exit;
                    return false;
                }
                return false;
            }
        }
    }

    // Added by Saurabh on 12-07-2021 to generate product image thumbnail
    function generate_product_image_thumbnail($filename, $source_image_path, $thumbnail_image_path)
    {

        list($source_image_width, $source_image_height, $source_image_type) = getimagesize($source_image_path);
        switch ($source_image_type) {
            case IMAGETYPE_GIF:
                $source_gd_image = imagecreatefromgif($source_image_path);
                break;
            case IMAGETYPE_JPEG:
                $source_gd_image = imagecreatefromjpeg($source_image_path);
                break;
            case IMAGETYPE_PNG:
                $source_gd_image = imagecreatefrompng($source_image_path);
                break;
        }
        if ($source_gd_image === false) {
            return 'error';
        }

        $source_aspect_ratio = $source_image_width / $source_image_height;
        $thumbnail_aspect_ratio = 250 / 250;
        if ($source_image_width <= 250 && $source_image_height <= 250) {
            $thumbnail_image_width = 250;
            $thumbnail_image_height = 250;
        } elseif ($thumbnail_aspect_ratio > $source_aspect_ratio) {
            $thumbnail_image_width = (int) (250 * $source_aspect_ratio);
            $thumbnail_image_height = 250;
        } else {
            $thumbnail_image_width = 250;
            $thumbnail_image_height = (int) (250 * $source_aspect_ratio);
        }
        $thumbnail_gd_image = imagecreatetruecolor($thumbnail_image_width, $thumbnail_image_height);
        imagecopyresampled($thumbnail_gd_image, $source_gd_image, 0, 0, 0, 0, $thumbnail_image_width, $thumbnail_image_height, $source_image_width, $source_image_height);

        $img_disp = imagecreatetruecolor(250, 250);
        $backcolor = imagecolorallocate($img_disp, 255, 255, 255);
        imagefill($img_disp, 0, 0, $backcolor);

        imagecopy($img_disp, $thumbnail_gd_image, (imagesx($img_disp) / 2) - (imagesx($thumbnail_gd_image) / 2), (imagesy($img_disp) / 2) - (imagesy($thumbnail_gd_image) / 2), 0, 0, imagesx($thumbnail_gd_image), imagesy($thumbnail_gd_image));

        $filename = 'thumb-' . pathinfo($filename, PATHINFO_FILENAME) . '.jpg';
        imagejpeg($img_disp, $thumbnail_image_path . $filename, 90);

        imagedestroy($source_gd_image);
        imagedestroy($thumbnail_gd_image);
        imagedestroy($img_disp);
        return $filename;
    }
    // Added by Saurabh on 12-07-2021 to generate product image thumbnail

    /* added by millan on 03-11-2021 */
    public function uploadQRcodeAuto($file_name)
    {
        require '../vendor/autoload.php';
        if (!empty($file_name)) { {
                $data = array();
                // $keyName = $folder_sturcture . sanitizeFileName(basename($file_name));
                $keyName = 'assets/QRcode/' . basename($file_name);
                // Set Amazon S3 Credentials
                $s3 = S3Client::factory(
                    array(
                        'credentials' => array(
                            'key' => SECRET_ACCESS_KEY,
                            'secret' => SECRET_ACCESS_CODE
                        ),
                        'version' => 'latest',
                        'region' => 'ap-south-1', //write your region name 
                        'signature' => 'v4',
                    )
                );
                try {
                    // Create temp file
                    $tempFilePath = QRCODE . basename($file_name);
                    $type = filetype($tempFilePath);
                    // Put on S3
                    $result = $s3->putObject(
                        array(
                            'Bucket' => BUCKETNAME,
                            'Key' => $keyName,
                            'Body' => fopen($tempFilePath, 'r+'),
                            'ContentType' => $type,
                            'ACL' => 'public-read',
                            'SourceFile' => $tempFilePath,
                            'StorageClass' => 'STANDARD'
                        )
                    );
                    if ($result['ObjectURL']) {
                        // unlink($tempFilePath);
                        return array_merge($data, array('aws_path' => $result['ObjectURL']));
                    }
                } catch (S3Exception $e) {
                    echo $this->session->set_flashdata('error', $e->getMessage());
                    return false;
                } catch (Exception $e) {
                    echo $this->session->set_flashdata('error', $e->getMessage());
                    return false;
                }
                return $data;
            }
        } else {
            return false;
        }
    }
    /* added by millan on 03-11-2021 */

    public function run_query()
    {
        //SELECT * from sample_test where sample_test_sample_reg_id=57729
        $query = $this->db->query("SELECT `test_name`, `rate_per_test` as `price`, `sample_test_quote_type`, `sample_test_quote_id`, `sample_test_protocol_id`, `sample_test_package_id`, `sample_test_work_id` FROM `sample_test` JOIN `tests` ON `test_id` = `sample_test_test_id` WHERE `sample_test_sample_reg_id` = '57729' AND `sample_test_quote_type` = 'Test'");
        echo "<pre>";
        print_r($query->result_array());
    }
}
