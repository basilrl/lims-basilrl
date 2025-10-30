<?php
$checkUser = $this->session->userdata('user_data');
$this->user = $checkUser->uidnr_admin;
$user = $this->user = $checkUser->uidnr_admin; ?>
<script src="<?php echo base_url(); ?>ckeditor/ckeditor.js"></script>
<div class="content-wrapper">
    <section class="content-header">
        <section class="content">
            <div class="container-fluid">
                <h3 class="text-center font-weight-bold text-primary">REPORT GENERATE LIST</h3>
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="row mb-3">
                                    <div class="col-sm-4">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <input type="text" id="search_gc" class="form-control form-control-sm" value="<?php echo ($search_gc != 'NULL') ? $search_gc : ''; ?>" placeholder="SEARCH BY BASIL REPORT NO...">
                                            </div>
                                            <div class="col-sm-6">
                                                <input type="text" id="search_trf" class="form-control form-control-sm" value="<?php echo ($search_trf != 'NULL') ? $search_trf : ''; ?>" placeholder="SEARCH BY TRF NO...">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <input type="text" class="form-control form-control-sm applicant_name" placeholder="ENTER APPLICANT NAME" value="<?php echo ($applicant_name != 'NULL') ? strtoupper($applicant_name) : '' ?>">
                                        <input type="hidden" class="applicant_id" id="applicant_id" value="<?php echo ($applicant_id != 'NULL') ? $applicant_id : ''; ?>">
                                        <ul class="list-group-item customer_list" style="display:none">
                                        </ul>
                                    </div>
                                    <div class="col-sm-3">
                                        <input type="text" id="" class="form-control form-control-sm product_type" placeholder="ENTER PRODUCT NAME" value="<?php echo ($product_name != 'NULL') ? strtoupper($product_name) : '' ?>">
                                        <input type="hidden" class="product_id" id="product_id" value="<?php echo ($product_id != 'NULL') ? $product_id : ''; ?>">
                                        <ul class="list-group-item cat_list" style="display:none">
                                        </ul>
                                    </div>

                                    <div class="col-sm-2 text-center">
                                        <a class="btn btn-primary btn-sm" onclick="filter_by()" href="javascript:void(0);">SUBMIT</a>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-2">
                                        <select class="form-control form-control-sm" name="" id="divison">
                                            <option value="">SELECT DIVISION</option>
                                            <?php foreach ($divisions as $key => $value) { ?>
                                                <option <?php echo ($divison > 0) ? (($divison == $value->division_id) ? 'SELECTED' : '') : '' ?> value="<?php echo $value->division_id; ?>"><?php echo $value->division_name; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="col-sm-3">
                                        <input type="text" class="form-control form-control-sm buyer_name_type" placeholder="ENTER BUYER NAME" value="<?php echo ($buyer_name != 'NULL') ? strtoupper($buyer_name) : '' ?>">
                                        <input type="hidden" class="buyer_id" id="buyer_id" value="<?php echo ($buyer_id != 'NULL') ? $buyer_id : ''; ?>">
                                        <ul class="list-group-item buyer_customer_list" style="display:none">
                                        </ul>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="row">
                                            <div class="col-sm-6 text-center">
                                                <input id="start_date" value="<?php echo ($start_date != 'NULL') ? $start_date : ''; ?>" placeholder="START DATE" type="text" onfocus="(this.type='date')" class="form-control form-control-sm">
                                            </div>
                                            <div class="col-sm-6 text-center">
                                                <input id="end_date" placeholder="END DATE" value="<?php echo ($end_date != 'NULL') ? $end_date : ''; ?>" type="text" onfocus="(this.type='date')" class="form-control form-control-sm">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-12 text-center"><label for="">DATE FILTER BY RECIEVED DATE</label></div>
                                        </div>
                                    </div>
                                    <div class="col-sm-2">

                                    </div>
                                    <div class="col-sm-2 text-center">
                                        <a class="btn btn-danger btn-sm" href="<?php echo base_url('Manage_lab/report_listing'); ?>">CLEAR</a>
                                    </div>
                                </div>
                                <!-- added by ajit 31-03-2021 -->
                                <div class="row">
                                    <?php if (exist_val('Manage_lab/release_to_client_all_report', $this->session->userdata('permission'))) { ?>
                                        <button type="button" class="btn btn-sm btn-success release_all_to_client_report" title="RELEASE ALL TO CLIENT">RELEASE</button>
                                    <?php  } ?>
                                </div>
                                <!-- end -->
                            </div>
                            <?php //echo "<pre>"; print_r($report_listing); 
                            ?>
                            <div class="card-body p-0">
                                <table class="table table-sm table-hovered text-center text-secondary table-bordered">
                                    <thead class="thead-light">
                                        <tr>

                                            <!-- added by ajit 31-03-2021 -->
                                            <?php if (exist_val('Manage_lab/release_to_client_all_report', $this->session->userdata('permission'))) { ?>
                                                <th>
                                                    <?php
                                                    if (!$this->session->has_userdata('release_all_report')) {
                                                        $this->session->set_userdata('release_all_report', 0);
                                                    }
                                                    ?>
                                                    Select All <input type="checkbox" class="check_all_release_report" value="0" <?php echo (($this->session->userdata('release_all_report') == '1') ? "checked" : "") ?>>

                                                </th>
                                            <?php } ?>


                                            <!-- end -->
                                            <th>S.No.</th>
                                            <th>Basil Report Number</th>
                                            <th>Report Number</th>
                                            <th>Client</th>
                                            <th>Sample Description</th>
                                            <th>Product</th>
                                            <th>TRF Reference No.</th>
                                            <th>Quantity</th>
                                            <th>Recieved Date</th>
                                            <th>Status</th>
                                            <th>Service Type</th>
                                            <th>Due Date</th>
                                            <?php if ((exist_val('Manage_lab/upload_samples_images', $this->session->userdata('permission'))) || (exist_val('Manage_lab/get_report_sample_images', $this->session->userdata('permission'))) || (exist_val('Manage_lab/send_to_record_finding', $this->session->userdata('permission'))) || (exist_val('Manage_lab/preview_report', $this->session->userdata('permission'))) || (exist_val('Manage_lab/pdf_demo', $this->session->userdata('permission'))) || (exist_val('Manage_lab/regenerate_sample', $this->session->userdata('permission'))) || (exist_val('Manage_lab/additional_test', $this->session->userdata('permission'))) || (exist_val('Manage_lab/Release_to_client', $this->session->userdata('permission'))) || (exist_val('Manage_lab/Final_report_generate', $this->session->userdata('permission')))) { ?>
                                                <!-- added by millan on 23-02-2021 -->
                                                <th>Action</th>
                                            <?php } ?>
                                        </tr>
                                    </thead>
                                    <tbody>


                                        <?php (empty($this->uri->segment(11))) ? $i = 1 : $i = $this->uri->segment(11) + 1; ?>

                                        <?php if ($report_listing || $report_listing != NULL) {
                                        ?> <?php foreach ($report_listing as $RS) : ?>


                                                <tr>
                                                    <!-- added by ajit 30-03-2021 -->

                                                    <?php if (exist_val('Manage_lab/release_to_client_all_report', $this->session->userdata('permission'))) { ?>
                                                        <td>
                                                            <?php if ($RS->status == "Report Approved" && $RS->released_to_client < 1) { ?>

                                                                <input <?php echo ((in_array($RS->sample_reg_id, (($this->session->has_userdata('release_id_report')) ? $this->session->userdata('release_id_report') : []))) ? 'checked' : "") ?> type="checkbox" value="<?php echo $RS->sample_reg_id ?>" class="realeased_to_check_report">

                                                            <?php } ?>
                                                        </td>
                                                    <?php }  ?>

                                                    <!-- end -->
                                                    <td><?php echo $i; ?></td>
                                                    <td><?php echo $RS->gc_no; ?></td>
                                                    <td><?php echo $RS->report_num; ?></td>
                                                    <td><?php echo $RS->client; ?></td>
                                                    <td><?php echo $RS->sample_desc; ?></td>
                                                    <td><?php echo $RS->product_name; ?></td>
                                                    <td><?php echo $RS->trf_ref_no; ?></td>
                                                    <td><?php echo $RS->qty_received; ?></td>
                                                    <td><?php echo change_time($RS->received_date, $this->session->userdata('timezone')); ?></td>
                                                    <?php if ($RS->total_record_finding == $RS->status_count) { ?>
                                                        <td><?php echo $RS->status; ?></td>
                                                    <?php } else { ?>
                                                        <td>Partially Completed</td>
                                                    <?php } ?>
                                                    <td><?php echo $RS->sample_service_type; ?></td>
                                                    <td><?php echo $RS->due_date; ?></td>
                                                    <td>

                                                        <?php if ($RS->trf_buyer != 0 && $RS->buyer_active == 'Active') {
                                                            if ($RS->status != 'Hold Sample') { ?>

                                                                <?php if (exist_val('Manage_lab/upload_samples_images', $this->session->userdata('permission'))) { ?>
                                                                    <!-- added by millan on 23-02-2021 -->
                                                                    <a id="samples_images_upload" href="javascript:void(0)" target="" data-id="<?php echo $RS->sample_reg_id; ?>" data-report_id="<?php echo $RS->report_id; ?>" data-bs-toggle="modal" data-bs-target="#upload_images"><img src="<?php echo base_url(); ?>assets/images/image.png" alt="" title="Upload Images" style="height:16px; width:16px;"></a>

                                                                    <a id="reference_image_upload" href="javascript:void(0)" target="" data-id="<?php echo $RS->sample_reg_id; ?>" data-report_id="<?php echo $RS->report_id; ?>" data-bs-toggle="modal" data-bs-target="#reference_images"><img src="<?php echo base_url(); ?>assets/images/image.png" alt="" title="Upload References Images" style="height:16px; width:16px;"></a>
                                                                <?php } ?>

                                                                <?php if (exist_val('Manage_lab/get_report_sample_images', $this->session->userdata('permission'))) { ?>
                                                                    <!-- added by millan on 23-02-2021 -->
                                                                    <a href="javascript:void(0)" data-bs-toggle="modal" id="report_sample_image" data-bs-target="#sample_images" title="View Sample Images" data-id="<?= $RS->sample_reg_id; ?>"><img src="<?php echo base_url('assets/images/view_sample.png') ?>" alt="View Images" style="height:16px; width:16px"></a>
                                                                    <a href="javascript:void(0)" data-bs-toggle="modal" id="report_reference_image" data-bs-target="#view_reference_images" title="View Reference Images" data-id="<?= $RS->sample_reg_id; ?>"><img src="<?php echo base_url('public/img/icon/viewdetail.png') ?>" alt="View Images" style="height:16px; width:16px"></a>
                                                                <?php } ?>

                                                                <?php if ($RS->status != 'Report Approved') { ?>
                                                                    <?php if (exist_val('Manage_lab/send_to_record_finding', $this->session->userdata('permission'))) { ?>
                                                                        <!-- added by millan on 23-02-2021 -->
                                                                        <a id="send_to_record_finding" href="javascript:void(0)" target="" data-id="<?php echo $RS->sample_reg_id; ?>" data-report_id="<?php echo $RS->report_id; ?>" data-bs-toggle="modal" data-bs-target="#record_finding_modal"><img src="<?php echo base_url(); ?>assets/images/arrow_rotate_anticlockwise.png" alt="" title="Send Sample to Record Finding"></a>
                                                                    <?php } ?>
                                                                <?php } ?>

                                                                <?php if ($RS->status == 'Report Generated') { ?>
                                                                    <?php if (exist_val('Manage_lab/preview_report', $this->session->userdata('permission'))) { ?>
                                                                        <!-- added by millan on 23-02-2021 -->
                                                                        <a class="preview_report" href="javascript:void(0)" target="" data-url="<?php echo base_url('Manage_lab/preview_report?sample_reg_id=' . $RS->sample_reg_id . '&report_id=' . $RS->report_id); ?>" data-id="<?php echo $RS->sample_reg_id; ?>" data-report_id="<?php echo $RS->report_id; ?>" data-bs-toggle="modal" data-bs-target="#report_preview"><img width="20px" src="<?php echo base_url(); ?>assets/images/icon/viewdocument.png" alt="" title="Preview Report"></a>
                                                                    <?php } ?>
                                                                <?php } ?>

                                                                <?php if ($RS->total_record_finding == $RS->status_count) { ?>

                                                                    <?php if ($RS->status == 'Report Generated' && ($RS->signing_authority == $user && $RS->primary_approver_status == 1)) { ?>
                                                                        <?php if (exist_val('Manage_lab/pdf_demo', $this->session->userdata('permission'))) { ?>
                                                                            <!-- added by millan on 23-02-2021 -->
                                                                            <a class="generate_report" href="javascript:void(0)" target="" data-url="<?php echo base_url('Manage_lab/pdf_demo?sample_reg_id=' . $RS->sample_reg_id . '&report_id=' . $RS->report_id); ?>" data-id="<?php echo $RS->sample_reg_id; ?>" data-report_id="<?php echo $RS->report_id; ?>" data-bs-toggle="modal" data-bs-target="#report_generation"><img src="<?php echo base_url(); ?>assets/images/right_arrow.png" alt="" title="Approve Report"></a>

                                                                        <?php }
                                                                    } elseif ($RS->status == 'Report Generated' && ($RS->sign_authority_new == $user && $RS->secondary_approver_status == 1)) { ?>
                                                                        <a class="generate_report" href="javascript:void(0)" target="" data-url="<?php echo base_url('Manage_lab/pdf_demo?sample_reg_id=' . $RS->sample_reg_id . '&report_id=' . $RS->report_id); ?>" data-id="<?php echo $RS->sample_reg_id; ?>" data-report_id="<?php echo $RS->report_id; ?>" data-bs-toggle="modal" data-bs-target="#report_generation"><img src="<?php echo base_url(); ?>assets/images/right_arrow.png" alt="" title="Approve Report"></a>

                                                                    <?php } elseif ($RS->status == 'Report Approved') { ?>
                                                                        <a href="<?php echo $RS->manual_report_file; ?>" download="" target="_blank"><img src="<?php echo base_url(); ?>assets/images/view-lab-report-pdf.png" alt="" title="View PDF"></a>
                                                                    <?php } ?>

                                                                    <?php if ($RS->status == 'Completed') { ?>
                                                                        <?php if (exist_val('Manage_lab/Final_report_generate', $this->session->userdata('permission'))) { ?>
                                                                            <!-- added by millan on 23-02-2021 -->
                                                                            <a class="final_report" href="javascript:void(0)" target="" data-id="<?php echo $RS->sample_reg_id; ?>" data-report_id="<?php echo $RS->report_id; ?>" data-branch_id="<?php echo $RS->sample_registration_branch_id; ?>" data-bs-toggle="modal" data-bs-target="#data_for_report_generate"><img src="<?php echo base_url(); ?>assets/images/generate_report.png" alt="" title="Report Generate"></a>
                                                                        <?php } ?>
                                                                    <?php } ?>

                                                                    <?php if ($RS->status == 'Report Generated' && ($RS->signing_authority == $user && $RS->primary_approver_status == 2)) { ?>
                                                                        <a href="<?php echo $RS->manual_report_file; ?>" download="" target="_blank"><img src="<?php echo base_url(); ?>assets/images/view-lab-report-pdf.png" alt="" title="View PDF"></a>
                                                                    <?php } ?>

                                                                    <?php if ($RS->status == 'Report Generated' && ($RS->sign_authority_new == $user && $RS->secondary_approver_status == 2)) { ?>
                                                                        <a href="<?php echo $RS->manual_report_file; ?>" download="" target="_blank"><img src="<?php echo base_url(); ?>assets/images/view-lab-report-pdf.png" alt="" title="View PDF"></a>
                                                                    <?php } ?>

                                                                    <!-- Modify by CHANDAN --07-07-2022 -->
                                                                    <?php if ($RS->status == 'Report Approved' && $RS->revise_count < 5) { ?>
                                                                        <?php if (exist_val('Manage_lab/regenerate_sample', $this->session->userdata('permission'))) { ?>
                                                                            <a href="javascript:void(0)" title="Revise Report" class="revise_report_additional_test_less_five" sample_reg_id="<?php echo $RS->sample_reg_id; ?>" report_id="<?php echo $RS->report_id; ?>" action="Revise Report"><img src="<?php echo base_url(); ?>assets/images/retest2nd.png"></a>
                                                                        <?php } ?>

                                                                        <?php if (exist_val('Manage_lab/additional_test', $this->session->userdata('permission'))) { ?>
                                                                            <a href="javascript:void(0)" title="Additional Test" class="revise_report_additional_test_less_five" sample_reg_id="<?php echo $RS->sample_reg_id; ?>" report_id="<?php echo $RS->report_id; ?>" action="Additional Test"><img src="<?php echo base_url(); ?>assets/images/Buyout.png"></a>
                                                                        <?php } ?>
                                                                    <?php } ?>

                                                                    <?php if ($RS->status == 'Report Approved' && $RS->revise_count >= 5) { ?>
                                                                        <?php if (exist_val('Manage_lab/regenerate_sample', $this->session->userdata('permission'))) { ?>
                                                                            <a href="javascript:void(0)" title="Revise Report" class="revise_report_additional_test" report_num="<?php echo $RS->gc_no; ?>" report_id="<?php echo $RS->report_id; ?>" action="Revise Report"><img src="<?php echo base_url(); ?>assets/images/retest2nd.png"></a>
                                                                        <?php } ?>

                                                                        <?php if (exist_val('Manage_lab/additional_test', $this->session->userdata('permission'))) { ?>
                                                                            <a href="javascript:void(0)" title="Additional Test" class="revise_report_additional_test" report_num="<?php echo $RS->gc_no; ?>" report_id="<?php echo $RS->report_id; ?>" action="Additional Test"><img src="<?php echo base_url(); ?>assets/images/Buyout.png"></a>
                                                                        <?php } ?>
                                                                    <?php } ?>
                                                                    <!-- End -->

                                                                    <?php if ($RS->status == 'Report Approved') { ?>

                                                                        <?php if (exist_val('Manage_lab/Release_to_client', $this->session->userdata('permission'))) { ?>
                                                                            <!-- added by millan on 23-02-2021 -->

                                                                            <?php if ($RS->released_to_client == "0") { ?>
                                                                                <a class="release_to_client" href="javascript:void(0)" target="" data-id="<?php echo $RS->sample_reg_id; ?>" data-report-id="<?php echo $RS->report_id; ?>" data-bs-toggle="modal" data-bs-target="#release_to_client"><img src="<?php echo base_url(); ?>assets/images/downarrow.png" alt="" title="Release to Client"></a>
                                                                            <?php } ?>
                                                                        <?php } ?>
                                                                    <?php } ?>
                                                                <?php } ?>
                                                            <?php }
                                                        } else { ?>

                                                            <?php if (exist_val('Manage_lab/upload_samples_images', $this->session->userdata('permission'))) { ?>
                                                                <!-- added by millan on 23-02-2021 -->
                                                                <a id="samples_images_upload" href="javascript:void(0)" target="" data-id="<?php echo $RS->sample_reg_id; ?>" data-report_id="<?php echo $RS->report_id; ?>" data-bs-toggle="modal" data-bs-target="#upload_images"><img src="<?php echo base_url(); ?>assets/images/image.png" alt="" title="Upload Images" style="height:16px; width:16px;"></a>

                                                                <a id="reference_image_upload" href="javascript:void(0)" target="" data-id="<?php echo $RS->sample_reg_id; ?>" data-report_id="<?php echo $RS->report_id; ?>" data-bs-toggle="modal" data-bs-target="#reference_images"><img src="<?php echo base_url(); ?>assets/images/image.png" alt="" title="Upload References Images" style="height:16px; width:16px;"></a>
                                                            <?php } ?>

                                                            <?php if (exist_val('Manage_lab/get_report_sample_images', $this->session->userdata('permission'))) { ?>
                                                                <!-- added by millan on 23-02-2021 -->
                                                                <a href="javascript:void(0)" data-bs-toggle="modal" id="report_sample_image" data-bs-target="#sample_images" title="View Sample Images" data-id="<?= $RS->sample_reg_id; ?>"><img src="<?php echo base_url('assets/images/view_sample.png') ?>" alt="View Images" style="height:16px; width:16px"></a>
                                                                <a href="javascript:void(0)" data-bs-toggle="modal" id="report_reference_image" data-bs-target="#view_reference_images" title="View Reference Images" data-id="<?= $RS->sample_reg_id; ?>"><img src="<?php echo base_url('public/img/icon/viewdetail.png') ?>" alt="View Images" style="height:16px; width:16px"></a>
                                                            <?php } ?>

                                                            <?php if ($RS->status != 'Report Approved') { ?>
                                                                <?php if (exist_val('Manage_lab/send_to_record_finding', $this->session->userdata('permission'))) { ?>
                                                                    <!-- added by millan on 23-02-2021 -->
                                                                    <a id="send_to_record_finding" href="javascript:void(0)" target="" data-id="<?php echo $RS->sample_reg_id; ?>" data-report_id="<?php echo $RS->report_id; ?>" data-bs-toggle="modal" data-bs-target="#record_finding_modal"><img src="<?php echo base_url(); ?>assets/images/arrow_rotate_anticlockwise.png" alt="" title="Send Sample to Record Finding"></a>
                                                                <?php } ?>
                                                            <?php } ?>

                                                            <?php if ($RS->status == 'Report Generated') { ?>
                                                                <?php if (exist_val('Manage_lab/preview_report', $this->session->userdata('permission'))) { ?>
                                                                    <!-- added by millan on 23-02-2021 -->
                                                                    <a class="preview_report" href="javascript:void(0)" target="" data-url="<?php echo base_url('Manage_lab/preview_report?sample_reg_id=' . $RS->sample_reg_id . '&report_id=' . $RS->report_id); ?>" data-id="<?php echo $RS->sample_reg_id; ?>" data-report_id="<?php echo $RS->report_id; ?>" data-bs-toggle="modal" data-bs-target="#report_preview"><img width="20px" src="<?php echo base_url(); ?>assets/images/icon/viewdocument.png" alt="" title="Preview Report"></a>
                                                                <?php } ?>
                                                            <?php } ?>

                                                            <?php if ($RS->total_record_finding == $RS->status_count) { ?>
                                                                <?php if ($RS->status == 'Report Generated' && ($RS->signing_authority == $user && $RS->primary_approver_status == 1)) { ?>
                                                                    <?php if (exist_val('Manage_lab/pdf_demo', $this->session->userdata('permission'))) { ?>
                                                                        <!-- added by millan on 23-02-2021 -->
                                                                        <a class="generate_report" href="javascript:void(0)" target="" data-url="<?php echo base_url('Manage_lab/pdf_demo?sample_reg_id=' . $RS->sample_reg_id . '&report_id=' . $RS->report_id); ?>" data-id="<?php echo $RS->sample_reg_id; ?>" data-report_id="<?php echo $RS->report_id; ?>" data-bs-toggle="modal" data-bs-target="#report_generation"><img src="<?php echo base_url(); ?>assets/images/right_arrow.png" alt="" title="Approve Report"></a>

                                                                    <?php }
                                                                } elseif ($RS->status == 'Report Generated' && ($RS->sign_authority_new == $user && $RS->secondary_approver_status == 1)) { ?>
                                                                    <a class="generate_report" href="javascript:void(0)" target="" data-url="<?php echo base_url('Manage_lab/pdf_demo?sample_reg_id=' . $RS->sample_reg_id . '&report_id=' . $RS->report_id); ?>" data-id="<?php echo $RS->sample_reg_id; ?>" data-report_id="<?php echo $RS->report_id; ?>" data-bs-toggle="modal" data-bs-target="#report_generation"><img src="<?php echo base_url(); ?>assets/images/right_arrow.png" alt="" title="Approve Report"></a>

                                                                <?php } elseif ($RS->status == 'Report Approved') { ?>
                                                                    <a href="<?php echo $RS->manual_report_file; ?>" download="" target="_blank"><img src="<?php echo base_url(); ?>assets/images/view-lab-report-pdf.png" alt="" title="View PDF"></a>
                                                                <?php } ?>
                                                                <?php if ($RS->status == 'Completed') { ?>
                                                                    <?php if (exist_val('Manage_lab/Final_report_generate', $this->session->userdata('permission'))) { ?>
                                                                        <!-- added by millan on 23-02-2021 -->
                                                                        <a class="final_report" href="javascript:void(0)" target="" data-id="<?php echo $RS->sample_reg_id; ?>" data-report_id="<?php echo $RS->report_id; ?>" data-branch_id="<?php echo $RS->sample_registration_branch_id; ?>" data-bs-toggle="modal" data-bs-target="#data_for_report_generate"><img src="<?php echo base_url(); ?>assets/images/generate_report.png" alt="" title="Report Generate"></a>
                                                                    <?php } ?>
                                                                <?php } ?>

                                                                <?php if ($RS->status == 'Report Generated' && ($RS->signing_authority == $user && $RS->primary_approver_status == 2)) { ?>
                                                                    <a href="<?php echo $RS->manual_report_file; ?>" download="" target="_blank"><img src="<?php echo base_url(); ?>assets/images/view-lab-report-pdf.png" alt="" title="View PDF"></a>
                                                                <?php } ?>

                                                                <?php if ($RS->status == 'Report Generated' && ($RS->sign_authority_new == $user && $RS->secondary_approver_status == 2)) { ?>
                                                                    <a href="<?php echo $RS->manual_report_file; ?>" download="" target="_blank"><img src="<?php echo base_url(); ?>assets/images/view-lab-report-pdf.png" alt="" title="View PDF"></a>
                                                                <?php } ?>

                                                                <?php if (exist_val('Manage_lab/regenerate_sample', $this->session->userdata('permission'))) { ?>
                                                                    <a href="javascript:void(0)" title="Revise Report" class="revise_report_additional_test" report_num="<?php echo $RS->gc_no; ?>" report_id="<?php echo $RS->report_id; ?>" action="Revise Report"><img src="<?php echo base_url(); ?>assets/images/retest2nd.png"></a>
                                                                <?php } ?>

                                                                <?php if (exist_val('Manage_lab/additional_test', $this->session->userdata('permission'))) { ?>
                                                                    <a href="javascript:void(0)" title="Additional Test" class="revise_report_additional_test" report_num="<?php echo $RS->gc_no; ?>" report_id="<?php echo $RS->report_id; ?>" action="Additional Test"><img src="<?php echo base_url(); ?>assets/images/Buyout.png"></a>
                                                                <?php } ?>

                                                                <?php if ($RS->status == 'Report Approved') { ?>
                                                                    <?php if (exist_val('Manage_lab/Release_to_client', $this->session->userdata('permission'))) { ?>
                                                                        <!-- added by millan on 23-02-2021 -->
                                                                        <?php if ($RS->released_to_client == "0") { ?>
                                                                            <a class="release_to_client" href="javascript:void(0)" target="" data-id="<?php echo $RS->sample_reg_id; ?>" data-report-id="<?php echo $RS->report_id; ?>" data-bs-toggle="modal" data-bs-target="#release_to_client"><img src="<?php echo base_url(); ?>assets/images/downarrow.png" alt="" title="Release to Client"></a>
                                                                        <?php } ?>
                                                                    <?php } ?>
                                                                <?php } ?>
                                                            <?php } ?>
                                                        <?php } ?>
                                                        <a href="javascript:void(0)" data-id="<?php echo $RS->sample_reg_id; ?>" class="log_view" data-bs-toggle='modal' data-bs-target='#exampleModal' class="btn btn-sm" title="Log View"><img src="<?php echo base_url('assets/images/log-view.png'); ?>" alt="Log view" width="20px"></a>
                                                    </td>
                                                </tr>
                                                <?php $i++;
                                            endforeach; ?><?php } else { ?>
                                                <tr>
                                                    <td colspan="14"><?php echo "NO RECORD FIND"; ?></td>
                                                </tr>
                                            <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="card-footer">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <?php echo $links; ?>
                                    </div>
                                    <div class="col-sm-6">
                                        <?php echo $result_count; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </section>
</div>

<!-- Modal to view sample images -->
<div class="modal fade" id="sample_images" tabindex="-1" role="dialog" aria-labelledby="sample_imagesLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content" style="margin: 0px auto;">
            <div class="modal-header">
                <h5 class="modal-title" id="sample_imagesLabel">Sample Images</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="<?php echo base_url('Manage_lab/save_comment') ?>" method="post" id="save_report_image_preference">
                <div class="modal-body">
                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Image</th>
                                <th>Comment</th>
                                <th>Image Priority</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="report_sample_image_view"></tbody>
                    </table>
                    <div></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="view_reference_images" tabindex="-1" role="dialog" aria-labelledby="reference_imagesLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content" style="margin: 0px auto;">
            <div class="modal-header">
                <h5 class="modal-title" id="reference_imagesLabel">Reference Images</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="javascript:void(0);" method="post" id="save_reference_image_preference">
                <div class="modal-body">
                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Image</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="report_reference_image_view"></tbody>
                    </table>
                    <div></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <!-- <button type="submit" class="btn btn-primary">Save Changes</button> -->
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Modal to view sample images ends here-->

<!-- Modal to upload sample images -->
<div class="modal fade" id="upload_images" tabindex="-1" role="dialog" aria-labelledby="upload_imageLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content modal-sm" style="margin: 0px auto;">
            <div class="modal-header" style="background-color:darkblue;color:white;">
                <h5 class="modal-title" id="upload_imageLabel">Upload Sample Image</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" enctype="multipart/form-data" id="upload_samples_images">
                <div class="modal-body">
                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                    <input type="hidden" name="sample_reg_id" id="sample_reg_id">
                    <div class="form-group">
                        <input type="file" name="sample_image[]" multiple>
                    </div>
                </div>
                <div class="modal-footer " style="background-color:darkblue;color:white;">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="reference_images" tabindex="-1" role="dialog" aria-labelledby="upload_imageLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content modal-sm" style="margin: 0px auto;">
            <div class="modal-header" style="background-color:darkblue;color:white;">
                <h5 class="modal-title" id="upload_imageLabel">Upload Reference Image</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" enctype="multipart/form-data" id="upload_refernce_images">
                <div class="modal-body">
                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                    <input type="hidden" name="sample_reg_id" id="ref_sample_reg_id">
                    <div class="form-group">
                        <input type="file" name="reference_image[]" multiple>
                    </div>
                </div>
                <div class="modal-footer " style="background-color:darkblue;color:white;">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Modal to upload sample images ends here-->

<!-- Modal to send record finding -->
<div class="modal fade" id="record_finding_modal" tabindex="-1" role="dialog" aria-labelledby="upload_imageLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content modal-sm" style="margin: 0px auto;">
            <div class="modal-header" style="background-color:darkblue;color:white;">
                <h5 class="modal-title" id="upload_imageLabel">CONFIRM :</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" enctype="multipart/form-data" id="record_finding">
                <div class="modal-body">
                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                    <input type="hidden" name="sample_reg_id" id="samples_reg_id">
                    <div class="form-group">
                        <p>Do you want to send this sample again to record finding ?</p>
                    </div>
                </div>
                <div class="modal-footer " style="background-color:darkblue;color:white;">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">OK</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Modal to send record finding ends here-->

<div class="modal fade" id="data_for_report_generate" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content modal-lg" style="margin: 0 auto;">
            <div class="modal-header bg-primary">
                <h5 class="modal-title text-white text-center" id="accepted_test_head">REPORT</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="text-white">&times;</span>
                </button>
            </div>
            <form name="final_report_generate" id="final_report_generate" method="post" action="<?php echo base_url(); ?>Manage_lab/Final_report_generate">
                <input type="hidden" name="sample_reg_id" value="" class="sample_reg_id">
                <input type="hidden" name="report_id" value="" class="report_id">
                <input type="hidden" name="branch_id" value="" class="branch_id">
                <div class="modal-body">
                    <div class="col-md-12">
                        <label for="">Ulr No Flag. :</label>
                        <select name="ulr_no_flag" id="ulr_no_flag" class="ulr_no_flag form-control">
                            <option value="">Select an option</option>
                            <option value="P">P</option>
                            <option value="F">F</option>
                        </select>
                        <label for="">Report Result :</label>
                        <select name="report_result" id="report_result" class="report_result form-control">
                            <option value="">Select an option</option>
                            <option value="1">PASS</option>
                            <option value="2">FAIL</option>
                            <option value="3">REFER RESULTS</option>
                        </select>
                        <label for="">Report Result Remark(if any)</label>
                        <textarea name="report_remark" id="report_remark" cols="20" rows="4" class=" form-control"></textarea>
                        <label for="">Issue Date</label>
                        <input type="date" name="issuance_date" id="issue_date" class="form-control" required> <br>
                        <label for="">Signing Authority :</label><br>
                        <select name="sign_auth" id="approver" class="form-control" required>
                            <option value="">Select...</option>
                            <?php foreach ($sign_auth as $sign) { ?>
                                <option value="<?php echo $sign->uidnr_admin ?>"><?php echo $sign->admin_fname . ' ' . $sign->admin_lname ?></option>
                            <?php } ?>
                        </select><br>
                        <label for="">Second Signing Authority(if any) :</label><br>
                        <select name="sign_auth1" id="approver1" class="form-control">
                            <option value="">Select...</option>
                            <?php foreach ($sign_auth as $sign) { ?>
                                <option value="<?php echo $sign->uidnr_admin ?>"><?php echo $sign->admin_fname ?></option>
                            <?php } ?>
                        </select><br>
                        <label for="">Remark</label>
                        <textarea name="remark" id="remark" cols="30" rows="10" class="ckeditor form-control"></textarea>
                        <label for="">Select Report Format</label>
                        <select name="report_format" id="report_format" class="report_format form-control" required>
                        </select>
                    </div>
                    <label for=""><b>Do You want this Parts Detail in Report or Not?</b></label>
                    <input type="radio" name="part" id="part" value="yes">Yes
                    <input type="radio" name="part" id="part" value="no" checked>No
                    <textarea name="part_details" id="part_details" cols="30" rows="10" class="ckeditor form-control"></textarea>
                    <div class="container-fluid">
                        <div class="row">
                            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-primary">
                    <a href="" target="_blank"> <button type="submit" class="btn btn-success submit">Generate</button></a>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="report_generation" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content modal-lg" style="margin: 0 auto;">
            <div class="modal-header">
                <h5 class="modal-title" id="accepted_test_head">GENERATE REPORT</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="text-white">&times;</span>
                </button>
            </div>
            <form name="report_generation" id="report_generation" method="post" action="<?php echo base_url(); ?>Manage_lab/approve_report">
                <input type="hidden" name="report_id" value="" class="report_id">
                <input type="hidden" name="sample_reg_id" value="" class="sample_reg_id">
                <div class="modal-body">

                    <div class="container-fluid">
                        <div class="report_html"></div>
                        <div class="row">
                            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <a href="" target="_blank"> <button type="submit" class="btn btn-success  submit">Approve</button></a>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="report_preview" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content modal-xl" style="margin: 0 auto;">
            <div class="modal-header">
                <h5 class="modal-title" id="accepted_test_head">PREVIEW REPORT</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="text-white">&times;</span>
                </button>
            </div>
            <form name="report_preview" id="report_preview" method="post" action="">
                <input type="hidden" name="report_id" value="" class="report_id">
                <input type="hidden" name="sample_reg_id" value="" class="sample_reg_id">
                <div class="modal-body">

                    <div class="container-fluid">
                        <div class="report_html"></div>
                        <div class="row">
                            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <!-- <a href="" target="_blank"> <button type="submit" class="btn btn-success  submit">Approve</button></a> -->
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" tabindex="-1" role="dialog" id="release_to_client" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content modal-md" style="margin: 0 auto;">
            <div class="modal-header" style="background-color:darkblue;color:white;height: 50px;">
                <h5 class="modal-title"><b>RELEASE TO CLIENT</b></h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" style="color:white;">&times;</span>
                </button>
            </div>
            <form method="post" name="release_to_client" action="<?php echo base_url('Manage_lab/Release_to_client'); ?>" id="release_to_client">
                <div class="modal-body">
                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                    <input type="hidden" value="" class="sample_reg_id" name="sample_reg_id">
                    <input type="hidden" value="" class="report_ids" name="report_id">
                    <input type="hidden" value="" class="report_pass" name="report_pass"> <!-- added by millan on 06-07-2021 -->
                    <div class="row">
                        <div class="col-md-12">
                            <label for="">To</label>
                            <input type="text" name="to" class="form-control email_to">
                            <label for="">CC</label>
                            <input type="test" name="cc" class="form-control">
                            <label for="">Bcc</label>
                            <input type="text" name="bcc" class="form-control">
                            <label for="">Subject</label>
                            <textarea name="subject" id="" cols="30" rows="2" class="form-control">Report Release To Client</textarea>
                            <label for="">Body</label>
                            <textarea name="email_body" id="" cols="30" rows="10" class="form-control ckeditor">
                                <table width="100%" border="0" cellspacing="5" cellpadding="5" style="border-collapse:collapse; font-family:Arial, Helvetica, sans-serif;font-size:12px;">
                                    <tr><td colspan="2" style="background-color:#336699"><img src=" <?php echo base_url(); ?>public/img/logo/geo-logo.png" height="53"/></td></tr>
                                    <tr>  <td colspan="2"><b>  Dear Sir/Madam,</b></td></tr>
                                    <tr>  <td colspan="2"> This is an auto-generated notification that your Report has been Released.</td></tr>
                                    <tr>  <td colspan="2">Please find the attached report below:</td></tr>
                                    <tr>
                                    <td colspan="2"> Thanks & Regards</td>
                                    </tr>
                                    <tr>
                                    <td colspan="2">GEO-CHEM</td>
                                    </tr>
                                    <tr>
                                    <td align="left" style="background-color:#D5E2F2">Geo Chem Consumer Products Services</td>
                                    <td align="right" style="background-color:#D5E2F2">GLIMS - Online Lab Information System</td>
                                    </tr>
                                </table>
                            </textarea>
                            <label for="">Do You Want To Send Mail Or Not?</label> <br>
                            <label for="">Yes</label>
                            <input type="radio" name="mail" id="mail" value="1">
                            <label for="">No</label>
                            <input type="radio" name="mail" id="mail" value="0">
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="background-color:darkblue;color:white;height: 50px;">
                    <button type="submit" data-id="" class="btn btn-success " style="margin-top: -10px;"><b>SEND</b></button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="margin-top: -10px;"><b>NO</b></button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- end -->

<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Sample log</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>SL.No.</th>
                            <th>Operation</th>
                            <th>Old Status</th>
                            <th>New Status</th>
                            <th>Performed By</th>
                            <th>Performed at</th>
                        </tr>
                    </thead>
                    <tbody id="sample_log"></tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Added by CHANDAN --06-07-2022 -->
<div class="modal fade" id="revise_report_additional_test_modal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Request for Basil Report No. - <b id="revise_report_additional_test_report_num"></b></h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body pb-5">
                <form method="post" id="revise_report_additional_test_form">
                    <input type="hidden" class="form-control" id="revise_report_additional_test_sample_reg_id" />
                    <input type="hidden" class="form-control" id="revise_report_additional_test_report_id" />
                    <input type="hidden" class="form-control" id="revise_report_additional_test_action" />
                    <div class="form-group">
                        <label for="revise_report_additional_test_reason">Reason:</label>
                        <textarea class="form-control" id="revise_report_additional_test_reason" rows="5" placeholder="Type reason..."></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="revise_report_additional_test_submit">Send Request</button>
            </div>
        </div>
    </div>
</div>
<!-- End -->

<script>
    $(document).ready(function() {
        CKEDITOR.replaceClass = 'ckeditor';
    });
</script>
<script>
    $('document').ready(function() {

        const _tokken = $('meta[name="_tokken"]').attr('value');

        $('.final_report').click(function() {
            var report_id = $(this).attr('data-report_id');
            var id = $(this).attr('data-id');
            var branch_id = $(this).attr('data-branch_id');
            $('.sample_reg_id').val(id);
            $('.branch_id').val(branch_id);
            $.ajax({
                url: "<?php echo base_url('Manage_lab/get_generate_report_data') ?>",
                method: "post",
                data: {
                    _tokken: _tokken,
                    sample_reg_id: id,
                    report_id: report_id,
                    branch_id: branch_id
                },
                success: function(data) {
                    var result = $.parseJSON(data);
                    var part = '<table align="center" cellspacing="0" border="1" style="border-collapse:collapse;font-size:12px!important;width:100%;"><thead><tr><th width="50%" style="vertical-align:bottom; white-space:nowrap;">TESTED COMPONENT</th><th width="50%" style="vertical-align:bottom; white-space:nowrap;">SAMPLE DESCRIPTION</th></tr></thead><tbody>';
                    $.each(result.parts, function(key, val) {
                        part += '<tr><td>' + val.part_name + '</td><td>' + val.parts_desc + '</td></tr>';
                    });
                    part += '</tbody></table>';
                    $('#sample_image_flag').val(result.sample_images_flag);
                    $('#ulr_no_flag').val(result.ulr_no_flag);
                    $('#report_result').val(result.manual_report_result);
                    $('#report_remark').val(result.manual_report_remark);
                    $('#issue_date').val(result.issuance_date);
                    $('#approver').val(result.name);
                    var format = '';
                    format += "<option value='" + 0 + "'>" + "default" + "</option>";
                    $.each(result.report_format, function(key, value) {
                        format += "<option value='" + value.format_id + "'>" + value.format_name + "</option>";
                    });
                    $('#report_format').append(format);
                    CKEDITOR.instances['remark'].setData(result.remark);
                    CKEDITOR.instances['part_details'].setData(part);
                }
            });
        });

        $('.generate_report').click(function() {
            var report_id = $(this).attr('data-report_id');
            var sample_reg_id = $(this).attr('data-id');
            var report = $(this).attr('data-url');
            $('.report_id').val(report_id);
            $('.sample_reg_id').val(sample_reg_id);
            $('.report_html').html('<iframe width="100%" height="400px;" src="' + report + '" frameborder="0" id="report"></iframe>');
        });

        $('.preview_report').click(function() {
            var report_id = $(this).attr('data-report_id');
            var sample_reg_id = $(this).attr('data-id');
            var report = $(this).attr('data-url');
            $('.report_id').val(report_id);
            $('.sample_reg_id').val(sample_reg_id);
            $('.report_html').html('<iframe width="100%" height="400px;" src="' + report + '" frameborder="0" id="report"></iframe>');
        });

        $(document).on('click', '.release_to_client', function() {
            // confirm('Are You Sure You Want to Release to client')
            var sample_reg_id = $(this).attr('data-id');
            var report_id = $(this).attr('data-report-id');
            $('.sample_reg_id').val(sample_reg_id);
            $('.report_ids').val(report_id);
            $.ajax({
                url: "<?php echo base_url('Manage_lab/get_release_to_client_data') ?>",
                method: "post",
                data: {
                    _tokken: _tokken,
                    sample_reg_id: sample_reg_id,
                },
                success: function(data) {
                    var result = $.parseJSON(data);
                    //var cust_name = result.customer_name.replace(/\s/g, "").substr(0,4); // added by millan on 06-july-2021
                    //var city = result.city.trim().slice(-2);   // added by millan on 13-july-2021
                    //var password = ('$' + cust_name + '@' + city).toUpperCase(); // added by millan on 06-july-2021
                    // console.log(password);
                    // var email = result.email.split("@"); // added by millan on 06-july-2021
                    if (result) {
                        $('.email_to').val(result.email);
                        //$('.report_pass').val(password); // added by millan on 06-july-2021
                    }
                }
            })
        });

        $(document).on('click', '#samples_images_upload', function() {
            var sample_reg_id = $(this).data('id');
            $('#sample_reg_id').val(sample_reg_id);
        });

        $(document).on('click', '#reference_image_upload', function() {
            var sample_reg_id = $(this).data('id');
            $('#ref_sample_reg_id').val(sample_reg_id);
        });
        // Ajax call to set sample_reg_id ends here

        // Ajax call to ulpload sample image

        $('#upload_samples_images').submit(function(e) {
            e.preventDefault();
            $.ajax({
                type: "post",
                processData: false,
                contentType: false,
                cache: false,
                async: false,
                url: '<?php echo base_url(); ?>Manage_lab/upload_samples_images',
                data: new FormData(this),
                success: function(result) {
                    let data = $.parseJSON(result);
                    if (data.status > 0) {
                        $.notify(data.message, "success");
                        $('#upload_images').modal('hide');
                        window.location.reload();
                    } else {
                        $.notify(data.message, "error");
                    }
                }
            });
        });

        $('#upload_refernce_images').submit(function(e) {
            e.preventDefault();
            $.ajax({
                type: "post",
                processData: false,
                contentType: false,
                cache: false,
                async: false,
                url: '<?php echo base_url(); ?>Manage_lab/upload_refernce_images',
                data: new FormData(this),
                success: function(result) {
                    let data = $.parseJSON(result);
                    if (data.status > 0) {
                        $.notify(data.message, "success");
                        $('#reference_images').modal('hide');
                        window.location.reload();
                    } else {
                        $.notify(data.message, "error");
                    }
                }
            });
        });

        $(document).on('click', '#send_to_record_finding', function() {
            var sample_reg_id = $(this).data('id');
            $('#samples_reg_id').val(sample_reg_id);
        });
        // Ajax call to set sample_reg_id ends here

        // Ajax call torecord finding
        $('#record_finding').submit(function(e) {
            e.preventDefault();
            $.ajax({
                type: "post",
                processData: false,
                contentType: false,
                cache: false,
                async: false,
                url: '<?php echo base_url(); ?>Manage_lab/send_to_record_finding',
                data: new FormData(this),
                success: function(result) {
                    let data = $.parseJSON(result);
                    if (data.status > 0) {
                        $.notify(data.message, "success");
                        $('#record_finding_modal').modal('hide');
                        window.location.reload();
                    } else {
                        $.notify(data.message, "error");
                    }
                }
            });
        });

        // Ajax call to get Sample images
        $(document).on('click', '#report_sample_image', function() {
            const _tokken = $('meta[name="_tokken"]').attr('value');
            $('#report_sample_image_view').empty();
            var sample_reg_id = $(this).data('id');
            $.ajax({
                type: 'post',
                url: '<?php echo base_url(); ?>Manage_lab/get_report_sample_images',
                data: {
                    _tokken: _tokken,
                    sample_reg_id: sample_reg_id
                },
                success: function(data) {
                    var images = JSON.parse(data);
                    image = '';
                    $.each(images, function(key, value) {
                        var comment = (value.comment) ? value.comment : "";
                        image += '<tr><input type="hidden" name="image[' + key + '][image_id]" value="' + value.image_id + '">';
                        image += '<td><img src="' + value.image_file_path + '" style="width:50px; height:50px"></td>';
                        image += '<td><input type="text" name="image[' + key + '][comment]" class="form-control form-control-sm" value="' + comment + '"></td>'
                        image += '<td><input type="number" name="image[' + key + '][sequence]" class="form-control form-control-sm"  value="' + value.image_sequence + '"></td>'
                        image += '<td><a href="javascript:void(0)" class="btn btn-danger delete-image" data-id="' + value.image_id + '" data-sample_id="' + sample_reg_id + '">X</a></td>'
                        image += '</tr>';
                    });
                    $('#report_sample_image_view').append(image);
                }
            })
        });

        $(document).on('click', '#report_reference_image', function() {
            const _tokken = $('meta[name="_tokken"]').attr('value');
            $('#report_reference_image_view').empty();
            var sample_reg_id = $(this).data('id');
            $.ajax({
                type: 'post',
                url: '<?php echo base_url(); ?>Manage_lab/get_report_reference_image',
                data: {
                    _tokken: _tokken,
                    sample_reg_id: sample_reg_id
                },
                success: function(data) {
                    var images = JSON.parse(data);
                    image = '';
                    $.each(images, function(key, value) {
                        var comment = (value.comment) ? value.comment : "";
                        image += '<tr><input type="hidden" name="image[' + key + '][image_id]" value="' + value.report_ref_image_id + '">';
                        image += '<td><img src="' + value.image_file_path + '" style="width:50px; height:50px"></td>';

                        image += '<td><a href="javascript:void(0)" class="btn btn-danger delete-ref_image" data-id="' + value.report_ref_image_id + '"  data-sample_id="' + sample_reg_id + '">X</a></td>'
                        image += '</tr>';
                    });
                    $('#report_reference_image_view').append(image);
                }
            })
        });
        // Ajax call to get sample images ends here 
    });
</script>

<script>
    function filter_by() {
        var base_url = '<?php echo base_url('Manage_lab/report_listing/'); ?>';
        var applicant = $('#applicant_id').val();
        var buyer_id = $('#buyer_id').val();
        var product = $('#product_id').val();
        var divison = $('#divison').val();
        var search_gc = $('#search_gc').val();
        var search_trf = $('#search_trf').val();
        var start_date = $('#start_date').val();
        var end_date = $('#end_date').val();
        // var status = $('#status').val();
        base_url += ((applicant) ? applicant : 'NULL');
        base_url += '/' + ((buyer_id) ? buyer_id : 'NULL');
        base_url += '/' + ((divison) ? divison : 'NULL');
        base_url += '/' + ((product) ? product : 'NULL');
        base_url += '/' + ((search_gc) ? btoa(search_gc) : 'NULL');
        base_url += '/' + ((search_trf) ? btoa(search_trf) : 'NULL');
        base_url += '/' + ((start_date) ? start_date : 'NULL');
        base_url += '/' + ((end_date) ? end_date : 'NULL');
        // base_url += '/' + ((status) ? btoa(status) : 'NULL');
        location.href = base_url;
    }
    var css = {
        position: "absolute",
        width: "95%",
        "font-size": "12px",
        "z-index": 999,
        "overflow-y": "auto",
        "overflow-x": "hidden",
        "max-height": "200px",
        cursor: "pointer",
    };
    var base_url = $("body").attr("data-url");
    getAutolist(
        "applicant_id",
        "applicant_name",
        "customer_list",
        "customer_li",
        "",
        "customer_name",
        "customer_id as id,customer_name as name",
        "cust_customers"
    );
    getAutolist(
        "buyer_id",
        "buyer_name",
        "buyer_customer_list",
        "customer_li",
        "",
        "customer_name",
        "customer_id as id,customer_name as name",
        "cust_customers"
    );
    getAutolist(
        "product_id",
        "product_type",
        "cat_list",
        "sample_li",
        "",
        "sample_type_name",
        "sample_type_id as id,sample_type_name as name",
        "mst_sample_types"
    );

    function getAutolist(hide_input, input, ul, li, where, like, select, table) {

        var base_url = $("body").attr("data-url");
        var hide_inputEvent = $("input." + hide_input);
        var inputEvent = $("input." + input);
        var ulEvent = $("ul." + ul);

        inputEvent.focusout(function() {
            ulEvent.fadeOut();
        });

        inputEvent.on("click keyup", function(e) {
            var me = $(this);
            var key = $(this).val();
            var _URL = base_url + "get_auto_list";
            const _tokken = $('meta[name="_tokken"]').attr("value");
            e.preventDefault();
            if (key) {
                $.ajax({
                    url: _URL,
                    method: "POST",
                    data: {
                        key: key,
                        where: where,
                        like: like,
                        select: select,
                        table: table,
                        _tokken: _tokken,
                    },

                    success: function(data) {
                        var html = $.parseJSON(data);
                        ulEvent.fadeIn();
                        ulEvent.css(css);
                        ulEvent.html("");
                        if (html) {
                            $.each(html, function(index, value) {
                                ulEvent.append(
                                    $(
                                        '<li class="list-group-item ' +
                                        li +
                                        '"' +
                                        "data-id=" +
                                        value.id +
                                        ">" +
                                        value.name +
                                        "</li>"
                                    )
                                );
                            });
                        } else {
                            ulEvent.append(
                                $(
                                    '<li class="list-group-item ' +
                                    li +
                                    '"' +
                                    'data-id="">NO REORD FOUND</li>'
                                )
                            );
                        }

                        var liEvent = $("li." + li);
                        liEvent.click(function() {
                            var id = $(this).attr("data-id");
                            var name = $(this).text();
                            inputEvent.val(name);
                            hide_inputEvent.val(id);
                            ulEvent.fadeOut();
                        });

                        // ****
                    },

                });

            } else {
                hide_inputEvent.val('');
            }
        });
    }
</script>

<!-- Ajax call to remove report image -->
<script>
    const _tokken = $('meta[name="_tokken"]').attr('value');
    $(document).on('click', '.delete-image', function() {
        var row_id = $(this);
        var image_id = row_id.data('id');
        var sample_reg_id = row_id.data('sample_id');
        if (confirm("Are you sure want to delete this image!")) {
            $.ajax({
                type: 'post',
                url: '<?php echo base_url("Manage_lab/delete_report_sample_image"); ?>',
                data: {
                    _tokken: _tokken,
                    image_id: image_id,
                    sample_reg_id: sample_reg_id
                },
                dataType: 'json',
                success: function(data) {
                    if (data.status > 0) {
                        // window.location.reload();
                        $('#sample_images').modal('hide');
                        $.notify(data.message, "success");
                    } else {
                        $.notify(data.message, "error");
                    }
                }
            });
        }
    });

    $(document).on('click', '.delete-ref_image', function() {
        var row_id = $(this);
        var sample_reg_id = row_id.data('sample_id');
        var image_id = row_id.data('id');
        if (confirm("Are you sure want to delete this image!")) {
            $.ajax({
                type: 'post',
                url: '<?php echo base_url("Manage_lab/delete_report_reference_image"); ?>',
                data: {
                    _tokken: _tokken,
                    image_id: image_id,
                    sample_reg_id: sample_reg_id
                },
                dataType: 'json',
                success: function(data) {
                    if (data.status > 0) {
                        // window.location.reload();
                        $('#view_reference_images').modal('hide');
                        $.notify(data.message, "success");
                    } else {
                        $.notify(data.message, "error");
                    }
                }
            });
        }
    });

    // Save comment and sequence
    $('#save_report_image_preference').submit(function(e) {
        e.preventDefault();
        var form = $(this);
        $.ajax({
            type: 'post',
            url: form.attr('action'),
            data: form.serialize(),
            dataType: 'json',
            success: function(data) {
                if (data.status > 0) {
                    $('#sample_images').modal('hide');
                    $.notify(data.message, "success");
                    window.location.reload();
                } else {
                    $.notify(data.message, "error");
                }
            }
        });
    });
    // Ajax call ends here

    // Added by CHANDAN --06-07-2022
    $(document).on('click', '.revise_report_additional_test_less_five', function() {
        let sample_reg_id = $(this).attr('sample_reg_id');
        let report_id = $(this).attr('report_id');
        let action = $(this).attr('action');
        if (sample_reg_id.length > 0 && report_id.length > 0 && action.length > 0) {
            if (action == 'Additional Test') {
                var check = confirm("Are you sure you want to send this sample For Additional Test?");
                var action_url = "<?php echo base_url('Manage_lab/additional_test'); ?>";
            } else {
                var check = confirm("Are you sure that you want to Regenerate Report?");
                var action_url = "<?php echo base_url('Manage_lab/regenerate_sample') ?>";
            }
            if (check) {
                $.ajax({
                    url: action_url,
                    method: 'POST',
                    data: {
                        _tokken: _tokken,
                        sample_reg_id: sample_reg_id,
                        report_id: report_id,
                        action: action
                    },
                    dataType: 'json',
                    success: function(data) {
                        if (data.status > 0) {
                            $.notify(data.msg, 'success');
                            window.location.reload();
                        } else {
                            $.notify(data.msg, 'error');
                        }
                    }
                });
            }
        }
    });

    $(document).on('click', '.revise_report_additional_test', function() {
        let report_id = $(this).attr('report_id');
        let report_num = $(this).attr('report_num');
        let action = $(this).attr('action');
        if (report_id.length > 0 && report_num.length > 0 && action.length > 0) {
            $('#revise_report_additional_test_modal').modal('show');
            $('#revise_report_additional_test_report_id').val(report_id);
            $('#revise_report_additional_test_report_num').text(report_num);
            $('#revise_report_additional_test_action').val(action);
            return false;
        }
    });

    $(document).on('click', '#revise_report_additional_test_submit', function() {
        let report_id = $('#revise_report_additional_test_report_id').val();
        let action = $('#revise_report_additional_test_action').val();
        let reason = $('#revise_report_additional_test_reason').val();
        if (report_id.length > 0 && action.length > 0 && reason.length > 0) {
            $.ajax({
                type: 'post',
                url: '<?php echo base_url("Manage_lab/revise_report_additional_test"); ?>',
                data: {
                    _tokken: _tokken,
                    report_id: report_id,
                    action: action,
                    reason: reason
                },
                beforeSend: function() {
                    $('body').append('<div id="pageloader" class="pageloader"></div>');
                    $('#revise_report_additional_test_submit').prop('disabled', true);
                },
                dataType: 'json',
                success: function(data) {
                    if (data.code == 1) {
                        $.notify(data.message, "success");
                        window.location.reload();
                    } else {
                        $('#pageloader').removeClass('pageloader');
                        $.notify(data.message, "error");
                    }
                }
            });
        } else {
            $("#revise_report_additional_test_reason").notify(
                "Reason is required!", {
                    position: "bottom center"
                }
            );
        }
    });
    // End...

    // ajit code start release to client
    $(document).ready(function() {
        // added by ajit 30-03-2021
        $('.check_all_release_report').on('change', function() {
            var check_element = $('.realeased_to_check_report');
            if (this.checked) {
                make_session(null, true, true);
                $.each(check_element, function(ind, pak) {
                    $(pak).attr('checked', true);
                });
            } else {
                make_session(null, false, false);
                $.each(check_element, function(ind, pak) {
                    $(pak).attr('checked', false);
                });
            }
        });


        $(document).on('change', '.realeased_to_check_report', function() {
            sample_reg_id = $(this).val();
            if (this.checked) {
                make_session(sample_reg_id, true, false);
            } else {
                make_session(sample_reg_id, false, false);
                $('.check_all_release_report').attr('checked', false);
            }
        });

        function make_session(sample_reg_id = null, status, chek_all) {
            const _tokken = $('meta[name="_tokken"]').attr('value');
            $.ajax({
                url: "<?php echo base_url('Manage_lab/make_session_report') ?>",
                method: "POST",
                data: {
                    sample_reg_id: sample_reg_id,
                    _tokken: _tokken,
                    status: status,
                    chek_all: chek_all
                }
            });
        }

        // release all code
        $('.release_all_to_client_report').on('click', function() {
            var check = $('.check_all_release_report').val();
            const _tokken = $('meta[name="_tokken"]').attr('value');
            $.ajax({
                url: "<?php echo base_url('Manage_lab/release_to_client_all_report') ?>",
                method: "POST",
                data: {
                    _tokken: _tokken
                },
                beforeSend: function() {
                    $('body').append('<div id="pageloader" class="pageloader"></div>');
                },
                success: function(result) {
                    var data = $.parseJSON(result);
                    if (data.status > 0) {
                        $.notify(data.msg, 'success');
                    } else {
                        $.notify(data.msg, 'error');
                    }
                    window.location.reload();
                }
            });
        });
    });
    // end
</script>

<!-- added by saurabh on 23-03-2021 -->
<script>
    $(document).ready(function() {
        const url = $('body').data('url');
        const _tokken = $('meta[name="_tokken"]').attr('value');
        // Ajax call to get log
        $('.log_view').click(function() {
            $('#sample_log').empty();
            var sample_id = $(this).data('id');
            $.ajax({
                type: 'post',
                url: url + 'SampleRegistration_Controller/get_sample_log',
                data: {
                    _tokken: _tokken,
                    sample_id: sample_id
                },
                success: function(result) {
                    var data = $.parseJSON(result);
                    var value = '';
                    sno = Number();
                    $.each(data, function(i, v) {
                        sno += 1;
                        var operation = v.operation;
                        var old_status = v.old_status;
                        var new_status = v.new_status;
                        var taken_by = v.taken_by;
                        var taken_at = new Date(v.taken_at + ' UTC');
                        value += '<tr>';
                        value += '<td>' + sno + '</td>';
                        value += '<td>' + operation + '</td>';
                        value += '<td>' + old_status + '</td>';
                        value += '<td>' + new_status + '</td>';
                        value += '<td>' + taken_by + '</td>';
                        value += '<td>' + taken_at.toLocaleString() + '</td>';
                        value += '</tr>';

                    });
                    $('#sample_log').append(value);
                }
            });
        });
        // ajax call to get log ends here
    });
</script>
<!-- added by saurabh on 23-03-2021 -->