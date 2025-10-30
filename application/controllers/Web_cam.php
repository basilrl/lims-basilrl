<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Web_cam extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->check_session();
        $this->load->model('SampleRegistration', 'sr');
    }

    public function index()
    {
        $this->form_validation->set_rules('web_image[]', 'Sample Images', 'trim|required');
        if ($this->form_validation->run()) {
            $post = $this->input->post();
            $sample_images = array();
            foreach ($post['web_image'] as $key => $value) {
                $image_parts = explode(";base64,", $value);
                $image_type_aux = explode(":", $image_parts[0]);
                $image_type = $image_type_aux[1];
                $image_body = base64_decode($image_parts[1]);
                $file_name = 'sample_image' . rand(0, 9999) . '-' . date('Y') . '.jpeg';
                $aws_path = $this->upload_image_type_with_body($file_name, $image_body, $image_type);
                if ($aws_path) {
                    $data = array();
                    $data['image_file_path'] = $aws_path['aws_path'];
                    $data['image_thumb_file_path'] = $aws_path['aws_path'];
                    $data['sample_reg_id'] = $post['sample_reg_id'];
                    $data['created_by'] = $this->admin_id();
                    $data['created_on'] = date('Y-m-d H:i:s');
                    $sample_images[] = $data;
                }
            }
            if (count($sample_images) > 0) {
                $result = $this->sr->insert_multiple_data("sample_photos", $sample_images);
                if ($result) {
                    $this->session->set_flashdata('success', 'Images saved successfully');
                    echo json_encode(["message" => "Images saved successfully", "status" => 1]);
                } else {
                    echo json_encode(["message" => "Something went wrong!. While submit ", "status" => 0]);
                }
            } else {
                echo json_encode(["message" => "IMAGE WRONG SUBMIT", "status" => 0]);
            }
        } else {
            echo json_encode(["message" => "Something Wrong", "error" => $this->form_validation->error_array(), "status" => 0]);
        }
    }
}
