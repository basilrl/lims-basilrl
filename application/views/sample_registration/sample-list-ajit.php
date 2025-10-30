  <?php
  $trf_no = $this->uri->segment(3);
  if ($trf_no != "null") {
    $trf_no = base64_decode($trf_no);
  } else {
    $trf_no = "";
  }
  $customer_id = ($this->uri->segment(4)) ? $this->uri->segment(4) : '';
  $product_name = ($this->uri->segment(5)) ? $this->uri->segment(5) : '';
  $buyer_id = ($this->uri->segment(9)) ? $this->uri->segment(9) : '';
  $division_id = ($this->uri->segment(11)) ? $this->uri->segment(11) : '';
  $created_on = $this->uri->segment(6);
  if ($created_on != "null") {
    $created_on = base64_decode($created_on);
  } else {
    $created_on = "";
  }
  $status = $this->uri->segment(10);
  if ($status != "null") {
    $status = base64_decode($status);
  } else {
    $status = "";
  }
  $gc_number = $this->uri->segment(8);
  if ($gc_number != "null") {
    $gc_number = base64_decode($gc_number);
  } else {
    $gc_number = "";
  }

  $ulr_number = $this->uri->segment(7);
  if ($ulr_number != "null") {
    $ulr_number = base64_decode($ulr_number);
  } else {
    $ulr_number = "";
  }
  ?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <script src="<?php echo base_url('assets/js/sample_registration.js') ?>"></script>
    <script src="<?php echo base_url('webcam/webcam.min.js') ?>"></script>
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Sample Registration</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Sample Registration</li>
            </ol>
          </div>
        </div>

        <div class="row">
          <div class="col-md-12">
            <!-- <form action="<?php echo base_url('sample-list'); ?>" method="post" autocomplete="off"> -->
            <div class="row">
              <!-- <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>"> -->
              <div class="col-md-3">
                <div class="form-group">
                  <input type="text" placeholder="GC Number" name="gc_number" class="form-control form-control-sm" id="gc_number" value="<?= $gc_number; ?>">
                </div>
              </div>
              <div class="col-md-3">
                <input type="text" name="ulr_no" placeholder="ULR Number" class="form-control form-control-sm" id="ulr_no" value="<?= $ulr_number; ?>">
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <input type="text" placeholder="TRF Reference Number" class="form-control form-control-sm" id="trf_reference_number" name="trf_reference_number" value="<?= $trf_no; ?>">
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <select class="select-box" class="form-control form-control-sm" name="product" id="product">
                    <option selected value="">Select Product</option>
                    <?php if (!empty($products)) {
                      foreach ($products as $value) { ?>
                        <option value="<?php echo $value['sample_type_id']; ?>" <?php if ($product_name == $value['sample_type_id']) {
                                                                                  echo "selected";
                                                                                } ?>><?php echo $value['sample_type_name'] ?></option>
                    <?php }
                    } ?>
                  </select>
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <select class="select-box" class="form-control form-control-sm" name="customer_name" id="customer_name">
                    <option selected value="">Select Customer</option>
                    <?php if (!empty($customer)) {
                      foreach ($customer as $value) { ?>
                        <option value="<?php echo $value['customer_id']; ?>" <?php if ($customer_id == $value['customer_id']) {
                                                                                echo "selected";
                                                                              } ?>><?php echo $value['customer_name'] ?></option>
                    <?php }
                    } ?>
                  </select>
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <select class="select-box" class="form-control form-control-sm" name="buyer" id="buyer">
                    <option selected value="">Select Buyer</option>
                    <?php if (!empty($buyer)) {
                      foreach ($buyer as $value) { ?>
                        <option value="<?php echo $value['customer_id']; ?>" <?php if ($buyer_id == $value['customer_id']) {
                                                                                echo "selected";
                                                                              } ?>><?php echo $value['customer_name'] ?></option>
                    <?php }
                    } ?>
                  </select>
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <select class="select-box" class="form-control form-control-sm" name="division" id="division">
                    <option selected value="">Select Division</option>
                    <?php if (!empty($division)) {
                      foreach ($division as $value) { ?>
                        <option value="<?php echo $value['division_id']; ?>" <?php if ($division_id == $value['division_id']) {
                                                                                echo "selected";
                                                                              } ?>><?php echo $value['division_name'] ?></option>
                    <?php }
                    } ?>
                  </select>
                </div>
              </div>


              <div class="col-md-3">
                <div class="form-group">
                  <select class="select-box" class="form-control form-control-sm" name="status" id="status">
                    <option selected value="">Select Status</option>
                    <?php if (!empty($sample_status)) {
                      foreach ($sample_status as $value) { ?>
                        <option value="<?php echo $value['status']; ?>" <?php if ($status == $value['status']) {
                                                                          echo "selected";
                                                                        } ?>><?php echo $value['status'] ?></option>
                    <?php }
                    } ?>
                  </select>
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <input type="text" placeholder="Created On" class="form-control form-control-sm" id="created_on" name="created_on" value="<?php echo $created_on; ?>">
                </div>
              </div>


              <div class="col-md-3" style="display:none">
                <div class="form-group">
                  <select class="form-control select-box form-control-sm" name="trf_service_type" id="service_type">
                    <option selected="" disabled="">Select Service Type</option>
                    <option value="Regular">Regular(3 working days)</option>
                    <option value="Express">Express(2 working days)</option>
                    <option value="Express3">Express(3 working days)</option>
                    <option value="Urgent">Urgent(1 working days)</option>
                    <option value="2">Regular 2 days</option>
                    <option value="4">Regular 4 days</option>
                    <option value="5">Regular 5 days</option>
                    <option value="6">Regular 6 days</option>
                    <option value="7">Regular 7 days</option>
                    <option value="8">Regular 8 days</option>
                    <option value="9">Regular 9 days</option>
                    <option value="10">Regular 10 days</option>
                    <option value="12">Regular 12 days</option>
                    <option value="15">Regular 15 days</option>
                    <option value="20">Regular 20 days</option>
                    <option value="30">Regular 30 days</option>
                  </select>
                </div>
              </div>

              <div class="col-md-3">
                <div class="form-group">
                  <button type="button" class="btn btn-primary" id="filter">Search</button>
                  <button type="button" class="btn btn-danger" id="reset">Reset</button>
                </div>
              </div>



            </div>
            </form>
          </div>

        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <!-- /.row -->
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <div class="row">
                  <?php if (exist_val('SampleRegistration_Controller/release_to_client_all', $this->session->userdata('permission'))) { ?>
                    <button type="button" class="btn btn-sm btn-success release_all_to_client" title="RELEASE ALL TO CLIENT">RELEASE</button>
                  <?php } ?>
                </div>
                <div class="row">
                  <div class="col-sm-11">
                    <h3 class="card-title">Sample Registration</h3>
                  </div>


                  <div class="col-sm-1">
                    <?php if (exist_val('SampleRegistration_Controller/excel_export', $this->session->userdata('permission'))) { ?>
                      <a class="btn btn-sm btn-default" title="Excel Export" href="<?php echo base_url('SampleRegistration_Controller/excel_export') ?>"><img src="<?php echo base_url('assets/images/imp_excel.png') ?>" alt="export excel" height="30px" width="30px"></a>
                  </div>
                <?php } ?>
                </div>




                <div class="card-tools">
                  <div class="input-group input-group-sm">
                    <!-- <input type="text" name="table_search" class="form-control float-right" placeholder="Search">

                    <div class="input-group-append">
                      <button type="submit" class="btn btn-default"><i class="fas fa-search"></i></button>
                    </div> -->
                    <!-- <a href="<?php echo base_url('add-open-trf'); ?>" class="btn btn-primary" style="float: right;">Add</a> -->
                  </div>
                </div>
              </div>
              <?php //echo "<pre>"; print_r($sample_registered); die;
              ?>
              <!-- /.card-header -->
              <input type="hidden" id="order" value="">
              <input type="hidden" id="column" value="">
              <div class="card-body small p-0">
                <table class="table table-hover table-sm" id="sample-list">
                  <thead>
                    <tr>
                      <!-- added by ajit 30-03-2021 -->
                      <?php if (exist_val('SampleRegistration_Controller/release_to_client_all', $this->session->userdata('permission'))) { ?>
                        <th>
                          <?php
                          if (!$this->session->has_userdata('release_all')) {
                            $this->session->set_userdata('release_all', 0);
                          }
                          ?>
                          Select All <input type="checkbox" class="check_all_release" value="0" <?php echo (($this->session->userdata('release_all') == '1') ? "checked" : "") ?>>

                        </th>
                      <?php } ?>


                      <!-- end -->
                      <th>SL No.</th>
                      <th>GC No.</th>
                      <th>Barcode</th>
                      <th>ULR No.</th>
                      <th>Product</th>
                      <th>TRF Reference No.</th>
                      <th>Customer</th>
                      <th>Buyer</th>
                      <th>status</th>
                      <th>Created By</th>
                      <th>Created On</th>
                      <th>Due Date</th>
                      <th>Tat Date</th>
                      <th>Comment</th>
                      <?php if ((exist_val('SampleRegistrationEdit/edit_sample_reg', $this->session->userdata('permission'))) ||
                        (exist_val('SampleRegistration_Controller/view_details', $this->session->userdata('permission'))) ||
                        (exist_val('SampleRegistration_Controller/clone_sample', $this->session->userdata('permission'))) ||
                        (exist_val('SampleRegistration_Controller/generate_performa_invoice', $this->session->userdata('permission'))) ||
                        (exist_val('SampleRegistration_Controller/webcam_images', $this->session->userdata('permission'))) ||
                        (exist_val('SampleRegistration_Controller/sample_image_upload', $this->session->userdata('permission'))) ||
                        (exist_val('SampleRegistration_Controller/send_acknowledgement_mail', $this->session->userdata('permission'))) ||
                        (exist_val('SampleRegistration_Controller/show_barcode', $this->session->userdata('permission'))) ||
                        (exist_val('SampleRegistration_Controller/send_for_sample_evaluation', $this->session->userdata('permission'))) ||
                        (exist_val('SampleRegistration_Controller/forward_sample_for_manual_reporting', $this->session->userdata('permission'))) ||
                        (exist_val('SampleRegistration_Controller/download_QR', $this->session->userdata('permission'))) ||
                        (exist_val('SampleRegistration_Controller/upload_pdf_qr', $this->session->userdata('permission'))) ||
                        (exist_val('SampleRegistration_Controller/worksheet_dubai', $this->session->userdata('permission'))) ||
                        (exist_val('SampleRegistration_Controller/worksheet_all', $this->session->userdata('permission'))) ||
                        (exist_val('SampleRegistration_Controller/view_sample_images', $this->session->userdata('permission'))) ||
                        (exist_val('SampleRegistration_Controller/sample_login_cancel', $this->session->userdata('permission')))
                      ) { ?>
                        <!-- added by Millan on 24-02-2021 -->
                        <th>Action</th>
                      <?php } ?>
                    </tr>
                  </thead>
                  <tbody id="sample-listing">
                    <?php if (!empty($sample_registered)) {
                      $page = $this->uri->segment(2);
                      $sno = (($page ? $page : '1') - 1) * 10;
                      foreach ($sample_registered as $sample) { ?>
                        <tr>
                          <!-- added by ajit 30-03-2021 -->
                          <?php if (exist_val('SampleRegistration_Controller/release_to_client_all', $this->session->userdata('permission'))) { ?>

                            <?php if ($sample['status'] == "Report Generated" && $sample['released_to_client'] < 1) { ?>
                              <td>

                                <input <?php echo ((in_array($sample['sample_reg_id'], (($this->session->has_userdata('release_id')) ? $this->session->userdata('release_id') : []))) ? 'checked' : "") ?> type="checkbox" value="<?php echo $sample['sample_reg_id'] ?>" class="realeased_to_check">

                              </td>
                            <?php } ?>

                          <?php } ?>
                          <!-- end -->
                          <td><?php echo $sno += 1; ?></td>
                          <td><?php echo $sample['gc_no']; ?></td>
                          <td><img src="<?php echo $sample['barcode_path']; ?>"></td>
                          <td><?php echo $sample['ulr_no']; ?></td>
                          <td><?php echo $sample['sample_type_name']; ?></td>
                          <td><?php echo $sample['trf_ref_no']; ?></td>
                          <td><?php echo $sample['customer'] ? $sample['customer'] : 'N/A'; ?></td>
                          <td><?php echo $sample['buyer'] ? $sample['buyer'] : 'N/A'; ?></td>
                          <td><?php echo $sample['status']; ?></td>
                          <td><?php echo $sample['created_by']; ?></td>
                          <td><?php echo $sample['created_on']; ?></td>
                          <td><?php echo $sample['due_date']; ?></td>
                          <td><?php echo $sample['tat_date']; ?></td>
                          <td><?php echo $sample['comment']; ?></td>
                          <td>
                            <?php if (!empty($sample['buyer'])) { ?>
                              <?php if ($sample['buyer_active'] == 'Active' && $sample['customer_active'] = 'Active') { ?>
                                <?php if (exist_val('SampleRegistrationEdit/edit_sample_reg', $this->session->userdata('permission'))) { ?>
                                  <!-- added by Millan on 24-02-2021 -->
                                  <!--Added by kapri 18-02-21-->
                                  <?php if ($sample['status'] == "Registered") { ?>
                                    <a href="<?= base_url('SampleRegistrationEdit?sample_reg_id=' . base64_encode($sample['sample_reg_id'])); ?>"><img src="<?= base_url('/public/img/icon/edit.png'); ?>" style="width: 20px;" title="Edit Sample Registration"></a>
                                  <?php } ?>
                                  <!--end by kapri-->
                                <?php } ?>


                                <?php if (exist_val('SampleRegistration_Controller/view_details', $this->session->userdata('permission'))) { ?>
                                  <!-- added by Millan on 24-02-2021 -->
                                  <?php
                                  //if ($sample['status'] == "Sample Sent for Evaluation" || $sample['status'] == "Evaluation Completed" || $sample['status'] == "Proforma Generated") {
                                  echo "<a href='javascript:void(0)' data-bs-toggle='modal' data-bs-target='#sample_detail' id='show_detail' title='View Detail' data-one='" . $sample['sample_reg_id'] . "'><img src='" . base_url('assets/images/view_jobs_in_panel.png') . "'></a>";
                                  ?>
                                  <?php //} 
                                  ?>


                                <?php
                                }
                                if ($sample['status'] == "Registered") {
                                  if (exist_val('SampleRegistration_Controller/send_for_sample_evaluation', $this->session->userdata('permission'))) {
                                    echo "<a href='javascript:void(0)' title='Send for Sample Evaluation' id='sent_sample' data-one='" . $sample['sample_reg_id'] . "'><img src='" . base_url('assets/images/bullet_go.png') . "'></a>";
                                  }
                                }
                                ?>
                                <!-- sample registration clone -->
                                <?php if (exist_val('SampleRegistration_Controller/clone_sample', $this->session->userdata('permission'))) { ?>
                                  <!-- added by Millan on 24-02-2021 -->
                                  <a id="sample_clone" href="javascript:void(0)" target="" data-id="<?php echo $sample['sample_reg_id']; ?>" data-trf_id="<?php echo $sample['trf_id']; ?>" data-bs-toggle="modal"><img src="<?php echo base_url(); ?>assets/images/active_res.png" alt="" title="Clone Sample"></a>
                                <?php } ?>
                                <!-- end -->

                                <?php
                                if ($sample['status'] != "Proforma Generated") { ?>
                                  <span class="performa_span">
                                    <?php if (exist_val('SampleRegistration_Controller/generate_performa_invoice', $this->session->userdata('permission'))) { ?>
                                      <!-- added by Millan on 24-02-2021 -->
                                      <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#generatePerformaInvoice" data-id="<?php echo $sample['sample_reg_id'] ?>" title="Generate Performa Invoice" class="generate_performa_btn"><img src="<?php echo base_url('assets/images/Generated invoice.png'); ?>"></a>
                                    <?php } ?>
                                  </span>
                                <?php
                                } ?>
                                <?php if (exist_val('SampleRegistration_Controller/webcam_images', $this->session->userdata('permission'))) { ?>
                                  <!-- added by Millan on 24-02-2021 -->
                                  <a href="javascript:void(0)" data-bs-toggle="modal" id="sample_image_upload1" data-bs-target="#web_upload_image" title="Upload Images using WEBCAM" data-one="<?= $sample['sample_reg_id']; ?>"><img src="<?php echo base_url('assets/images/webcam.png') ?>" alt="Upload Image" style="height:16px; width:16px;"></a>
                                <?php } ?>
                                <?php if (exist_val('SampleRegistration_Controller/sample_image_upload', $this->session->userdata('permission'))) { ?>
                                  <!-- added by Millan on 24-02-2021 -->
                                  <a href="javascript:void(0)" data-bs-toggle="modal" id="sample_image_upload" data-bs-target="#upload_image" title="Upload Images" data-one="<?= $sample['sample_reg_id']; ?>"><img src="<?php echo base_url('assets/images/image.png') ?>" alt="Upload Image" style="height:16px; width:16px;"></a>
                                <?php } ?>
                                <?php if (exist_val('SampleRegistration_Controller/send_acknowledgement_mail', $this->session->userdata('permission'))) { ?>
                                  <!-- added by Millan on 24-02-2021 -->
                                  <a href="<?php echo base_url('SampleRegistration_Controller/send_acknowledgement_mail/' . $sample['sample_reg_id']) ?>" title="Resend Sample Acknowledgeent Mail" onclick="return confirm('Are you sure want to send acknowledgement mail again!.')"><img src="<?php echo base_url('assets/images/send_email.png') ?>" alt="Send Email"></a>
                                <?php } ?>
                                <!-- Added by Saurabh on 19-02-2021 -->
                                <?php if ($sample['sample_registration_branch_id'] == 4) { ?>
                                  <?php if (exist_val('SampleRegistration_Controller/worksheet_dubai', $this->session->userdata('permission'))) { ?>
                                    <!-- added by Millan on 24-02-2021 -->
                                    <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#worksheetlab" class="worksheet_lab" title="Worksheet" data-one="<?= $sample['sample_reg_id']; ?>"><img src="<?php echo base_url('assets/images/worksheet.png') ?>" alt="Worksheet"></a>
                                  <?php } ?>
                                <?php } else { ?>
                                  <?php if (exist_val('SampleRegistration_Controller/worksheet_all', $this->session->userdata('permission'))) { ?>
                                    <!-- added by Millan on 24-02-2021 -->
                                    <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#worksheet" id="show_worksheet" title="Worksheet" data-one="<?= $sample['sample_reg_id']; ?>"><img src="<?php echo base_url('assets/images/worksheet.png') ?>" alt="Worksheet"></a>
                                  <?php } ?>
                                <?php } ?>
                                <!-- Added by Saurabh on 19-02-2021 -->
                                <?php if (exist_val('SampleRegistration_Controller/send_acknowledgement_mail', $this->session->userdata('permission'))) { ?>
                                  <!-- added by Millan on 24-02-2021 -->
                                  <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#barcode" id="show_barcode" title="Show Barcode" data-one="<?= $sample['sample_reg_id']; ?>"><img src="<?php echo base_url('assets/images/printer.png') ?>" alt="Printer"></a>
                                <?php } ?>
                                <!-- added by millan on 15-01-2021 -->
                                <?php if (($sample['qr_code_name'] == "") && empty($sample['qr_code_name'])) { ?>
                                  <?php if (exist_val('SampleRegistration_Controller/forward_sample_for_manual_reporting', $this->session->userdata('permission'))) { ?>
                                    <!-- added by Millan on 24-02-2021 -->
                                    <a href="javascript:void(0)" class="btn btn-sm manual_reporting_sample" title="Forward Sample for Manual Reporting" onclick="return confirm('Do you want to forward sample for manual reporting')" data-id="<?= $sample['sample_reg_id']; ?>"><img src="<?php echo base_url('assets/images/accept.png') ?>" alt="Forward Sample for Manual Reporting"></a>
                                  <?php } ?>
                                <?php } else { ?>
                                  <?php if (!empty($sample['manual_report_file']) && $sample['manual_report_file'] != '') { ?>
                                    <?php if (exist_val('SampleRegistration_Controller/download_manual_report_pdf', $this->session->userdata('permission'))) { ?>
                                      <!-- added by Millan on 24-02-2021 -->
                                      <a class="btn btn-sm" href="<?php echo base_url('SampleRegistration_Controller/download_pdf?sample_reg_id=' . base64_encode($sample['sample_reg_id'])); ?>" id="download_manualpdfaws" title="Download Manual Report Pdf" data-id="<?= $sample['sample_reg_id']; ?>"><img src="<?php echo base_url('assets/images/downloadpdf.png') ?>" alt="Download Pdf From AWS" style="height:20px; width: 20px"></a>
                                    <?php } ?>
                                  <?php } else { ?>
                                    <?php if (exist_val('SampleRegistration_Controller/download_QR', $this->session->userdata('permission'))) { ?>
                                      <!-- added by Millan on 24-02-2021 -->
                                      <a class="btn btn-sm" download="" title="QRCODE DOWNLOAD" href="<?php echo base_url('SampleRegistration_Controller/download_QRCODE/' . base64_encode($sample['qr_code_name'])); ?>"><img src="<?php echo base_url('assets/images/qr-code-scan.png') ?>" alt="Download QR Code" width="22px"></a>
                                    <?php } ?>
                                    <!-- <a href="javascript:void(0)" data-bs-toggle="modal" class="qrcode_download" data-bs-target="#download_qr_code" title="Download QR Code" data-id="<?= $sample['sample_reg_id']; ?>"><img src="<?php echo base_url('assets/images/qr-code-scan.png') ?>" alt="Download QR Code" style="height:20px;"></a> -->
                                    <?php if (exist_val('SampleRegistration_Controller/upload_pdf_qr', $this->session->userdata('permission'))) { ?>
                                      <!-- added by Millan on 24-02-2021 -->
                                      <a href="javascript:void(0)" data-bs-toggle="modal" class="btn btn-sm manualreportpdf_upload" data-bs-target="#manual_report_pdf_upload" title="Upload Maunal Report PDF" data-id="<?= $sample['sample_reg_id']; ?>"><img src="<?php echo base_url('assets/images/upload_fl.png') ?>" alt="Upload Maunal Report PDF"></a>
                                    <?php } ?>
                                  <?php } ?>
                                <?php } ?>

                                <!-- Added by Saurabh on 28-01-2021 -->
                                <?php if ($sample['image_count'] > 0) { ?>
                                  <?php if (exist_val('SampleRegistration_Controller/view_sample_images', $this->session->userdata('permission'))) { ?>
                                    <!-- added by Millan on 24-02-2021 -->
                                    <a href="javascript:void(0)" data-bs-toggle="modal" id="sample_image" data-bs-target="#sample_images" title="View Sample Images" data-id="<?= $sample['sample_reg_id']; ?>"><img src="<?php echo base_url('public/img/icon/viewdetail.png') ?>" alt="View Images" style="height:16px; width:16px"></a>
                                  <?php } ?>
                                <?php } ?>
                                <!-- Added by Saurabh on 28-01-2021 -->

                                <!-- Added by Saurabh on 08-02-2021 -->
                                <?php if (exist_val('SampleRegistration_Controller/sample_login_cancel', $this->session->userdata('permission'))) { ?>
                                  <!-- added by Millan on 24-02-2021 -->
                                  <a href="javascript:void(0)" data-bs-toggle="modal" id="login_cancel" data-bs-target="#logincancel" title="Login Cancel Sample" data-id="<?= $sample['sample_reg_id']; ?>"><img src="<?php echo base_url('assets/images/cancel.png') ?>" alt="Login Cancel Sample" style="height:16px; width:16px"></a>
                                <?php } ?>
                                <!-- Added by Saurabh on 08-02-2021 -->
                                <a href="javascript:void(0)" data-id="<?php echo $sample['sample_reg_id']; ?>" class="log_view" data-bs-toggle='modal' data-bs-target='#exampleModal' class="btn btn-sm" title="Log View"><img src="<?php echo base_url('assets/images/log-view.png'); ?>" alt="Log view" width="20px"></a>
                                <a href="javascript:void(0)" data-bs-toggle="modal" class="reason_hold" data-bs-target="#hold_reason" title="Hold Reason" data-id="<?php echo $sample['sample_reg_id']; ?>"><img src="<?php echo base_url('assets/images/revise.png') ?>" alt="Hold Reason" style="height:16px; width:16px"></a>

                              <?php } else { ?>
                                <a href="javascript:void(0)" data-bs-toggle="modal" class="unhold_sample" data-bs-target="#unhold_sample" title="Unhold Sample" data-id="<?php echo $sample['sample_reg_id']; ?>"><img src="<?php echo base_url('assets/images/revise.png') ?>" alt="Unhold Sample" style="height:16px; width:16px"></a>

                              <?php }
                            } else { ?>
                              <?php if (exist_val('SampleRegistrationEdit/edit_sample_reg', $this->session->userdata('permission'))) { ?>
                                <!-- added by Millan on 24-02-2021 -->
                                <!--Added by kapri 18-02-21-->
                                <?php if ($sample['status'] == "Registered") { ?>
                                  <a href="<?= base_url('SampleRegistrationEdit?sample_reg_id=' . base64_encode($sample['sample_reg_id'])); ?>"><img src="<?= base_url('/public/img/icon/edit.png'); ?>" style="width: 20px;" title="Edit Sample Registration"></a>
                                <?php } ?>
                                <!--end by kapri-->
                              <?php } ?>


                              <?php if (exist_val('SampleRegistration_Controller/view_details', $this->session->userdata('permission'))) { ?>
                                <!-- added by Millan on 24-02-2021 -->
                                <?php
                                //if ($sample['status'] == "Sample Sent for Evaluation" || $sample['status'] == "Evaluation Completed" || $sample['status'] == "Proforma Generated") {
                                echo "<a href='javascript:void(0)' data-bs-toggle='modal' data-bs-target='#sample_detail' id='show_detail' title='View Detail' data-one='" . $sample['sample_reg_id'] . "'><img src='" . base_url('assets/images/view_jobs_in_panel.png') . "'></a>";
                                ?>
                                <?php //} 
                                ?>


                              <?php
                              }
                              if ($sample['status'] == "Registered") {
                                if (exist_val('SampleRegistration_Controller/send_for_sample_evaluation', $this->session->userdata('permission'))) {
                                  echo "<a href='javascript:void(0)' title='Send for Sample Evaluation' id='sent_sample' data-one='" . $sample['sample_reg_id'] . "'><img src='" . base_url('assets/images/bullet_go.png') . "'></a>";
                                }
                              }
                              ?>
                              <!-- sample registration clone -->
                              <?php if (exist_val('SampleRegistration_Controller/clone_sample', $this->session->userdata('permission'))) { ?>
                                <!-- added by Millan on 24-02-2021 -->
                                <a id="sample_clone" href="javascript:void(0)" target="" data-id="<?php echo $sample['sample_reg_id']; ?>" data-trf_id="<?php echo $sample['trf_id']; ?>" data-bs-toggle="modal"><img src="<?php echo base_url(); ?>assets/images/active_res.png" alt="" title="Clone Sample"></a>
                              <?php } ?>
                              <!-- end -->

                              <?php
                              if ($sample['status'] != "Proforma Generated") { ?>
                                <span class="performa_span">
                                  <?php if (exist_val('SampleRegistration_Controller/generate_performa_invoice', $this->session->userdata('permission'))) { ?>
                                    <!-- added by Millan on 24-02-2021 -->
                                    <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#generatePerformaInvoice" data-id="<?php echo $sample['sample_reg_id'] ?>" title="Generate Performa Invoice" class="generate_performa_btn"><img src="<?php echo base_url('assets/images/Generated invoice.png'); ?>"></a>
                                  <?php } ?>
                                </span>
                              <?php
                              } ?>
                              <?php if (exist_val('SampleRegistration_Controller/webcam_images', $this->session->userdata('permission'))) { ?>
                                <!-- added by Millan on 24-02-2021 -->
                                <a href="javascript:void(0)" data-bs-toggle="modal" id="sample_image_upload1" data-bs-target="#web_upload_image" title="Upload Images using WEBCAM" data-one="<?= $sample['sample_reg_id']; ?>"><img src="<?php echo base_url('assets/images/webcam.png') ?>" alt="Upload Image" style="height:16px; width:16px;"></a>
                              <?php } ?>
                              <?php if (exist_val('SampleRegistration_Controller/sample_image_upload', $this->session->userdata('permission'))) { ?>
                                <!-- added by Millan on 24-02-2021 -->
                                <a href="javascript:void(0)" data-bs-toggle="modal" id="sample_image_upload" data-bs-target="#upload_image" title="Upload Images" data-one="<?= $sample['sample_reg_id']; ?>"><img src="<?php echo base_url('assets/images/image.png') ?>" alt="Upload Image" style="height:16px; width:16px;"></a>
                              <?php } ?>
                              <?php if (exist_val('SampleRegistration_Controller/send_acknowledgement_mail', $this->session->userdata('permission'))) { ?>
                                <!-- added by Millan on 24-02-2021 -->
                                <a href="<?php echo base_url('SampleRegistration_Controller/send_acknowledgement_mail/' . $sample['sample_reg_id']) ?>" title="Resend Sample Acknowledgeent Mail" onclick="return confirm('Are you sure want to send acknowledgement mail again!.')"><img src="<?php echo base_url('assets/images/send_email.png') ?>" alt="Send Email"></a>
                              <?php } ?>
                              <!-- Added by Saurabh on 19-02-2021 -->
                              <?php if ($sample['sample_registration_branch_id'] == 4) { ?>
                                <?php if (exist_val('SampleRegistration_Controller/worksheet_dubai', $this->session->userdata('permission'))) { ?>
                                  <!-- added by Millan on 24-02-2021 -->
                                  <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#worksheetlab" class="worksheet_lab" title="Worksheet" data-one="<?= $sample['sample_reg_id']; ?>"><img src="<?php echo base_url('assets/images/worksheet.png') ?>" alt="Worksheet"></a>
                                <?php } ?>
                              <?php } else { ?>
                                <?php if (exist_val('SampleRegistration_Controller/worksheet_all', $this->session->userdata('permission'))) { ?>
                                  <!-- added by Millan on 24-02-2021 -->
                                  <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#worksheet" id="show_worksheet" title="Worksheet" data-one="<?= $sample['sample_reg_id']; ?>"><img src="<?php echo base_url('assets/images/worksheet.png') ?>" alt="Worksheet"></a>
                                <?php } ?>
                              <?php } ?>
                              <!-- Added by Saurabh on 19-02-2021 -->
                              <?php if (exist_val('SampleRegistration_Controller/send_acknowledgement_mail', $this->session->userdata('permission'))) { ?>
                                <!-- added by Millan on 24-02-2021 -->
                                <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#barcode" id="show_barcode" title="Show Barcode" data-one="<?= $sample['sample_reg_id']; ?>"><img src="<?php echo base_url('assets/images/printer.png') ?>" alt="Printer"></a>
                              <?php } ?>
                              <!-- added by millan on 15-01-2021 -->
                              <?php if (($sample['qr_code_name'] == "") && empty($sample['qr_code_name'])) { ?>
                                <?php if (exist_val('SampleRegistration_Controller/forward_sample_for_manual_reporting', $this->session->userdata('permission'))) { ?>
                                  <!-- added by Millan on 24-02-2021 -->
                                  <a href="javascript:void(0)" class="btn btn-sm manual_reporting_sample" title="Forward Sample for Manual Reporting" onclick="return confirm('Do you want to forward sample for manual reporting')" data-id="<?= $sample['sample_reg_id']; ?>"><img src="<?php echo base_url('assets/images/accept.png') ?>" alt="Forward Sample for Manual Reporting"></a>
                                <?php } ?>
                              <?php } else { ?>
                                <?php if (!empty($sample['manual_report_file']) && $sample['manual_report_file'] != '') { ?>
                                  <?php if (exist_val('SampleRegistration_Controller/download_manual_report_pdf', $this->session->userdata('permission'))) { ?>
                                    <!-- added by Millan on 24-02-2021 -->
                                    <a class="btn btn-sm" href="<?php echo base_url('SampleRegistration_Controller/download_pdf?sample_reg_id=' . base64_encode($sample['sample_reg_id'])); ?>" id="download_manualpdfaws" title="Download Manual Report Pdf" data-id="<?= $sample['sample_reg_id']; ?>"><img src="<?php echo base_url('assets/images/downloadpdf.png') ?>" alt="Download Pdf From AWS" style="height:20px; width: 20px"></a>
                                  <?php } ?>
                                <?php } else { ?>
                                  <?php if (exist_val('SampleRegistration_Controller/download_QR', $this->session->userdata('permission'))) { ?>
                                    <!-- added by Millan on 24-02-2021 -->
                                    <a class="btn btn-sm" download="" title="QRCODE DOWNLOAD" href="<?php echo base_url('SampleRegistration_Controller/download_QRCODE/' . base64_encode($sample['qr_code_name'])); ?>"><img src="<?php echo base_url('assets/images/qr-code-scan.png') ?>" alt="Download QR Code" width="22px"></a>
                                  <?php } ?>
                                  <!-- <a href="javascript:void(0)" data-bs-toggle="modal" class="qrcode_download" data-bs-target="#download_qr_code" title="Download QR Code" data-id="<?= $sample['sample_reg_id']; ?>"><img src="<?php echo base_url('assets/images/qr-code-scan.png') ?>" alt="Download QR Code" style="height:20px;"></a> -->
                                  <?php if (exist_val('SampleRegistration_Controller/upload_pdf_qr', $this->session->userdata('permission'))) { ?>
                                    <!-- added by Millan on 24-02-2021 -->
                                    <a href="javascript:void(0)" data-bs-toggle="modal" class="btn btn-sm manualreportpdf_upload" data-bs-target="#manual_report_pdf_upload" title="Upload Maunal Report PDF" data-id="<?= $sample['sample_reg_id']; ?>"><img src="<?php echo base_url('assets/images/upload_fl.png') ?>" alt="Upload Maunal Report PDF"></a>
                                  <?php } ?>
                                <?php } ?>
                              <?php } ?>

                              <!-- Added by Saurabh on 28-01-2021 -->
                              <?php if ($sample['image_count'] > 0) { ?>
                                <?php if (exist_val('SampleRegistration_Controller/view_sample_images', $this->session->userdata('permission'))) { ?>
                                  <!-- added by Millan on 24-02-2021 -->
                                  <a href="javascript:void(0)" data-bs-toggle="modal" id="sample_image" data-bs-target="#sample_images" title="View Sample Images" data-id="<?= $sample['sample_reg_id']; ?>"><img src="<?php echo base_url('public/img/icon/viewdetail.png') ?>" alt="View Images" style="height:16px; width:16px"></a>
                                <?php } ?>
                              <?php } ?>
                              <!-- Added by Saurabh on 28-01-2021 -->

                              <!-- Added by Saurabh on 08-02-2021 -->
                              <?php if (exist_val('SampleRegistration_Controller/sample_login_cancel', $this->session->userdata('permission'))) { ?>
                                <!-- added by Millan on 24-02-2021 -->
                                <a href="javascript:void(0)" data-bs-toggle="modal" id="login_cancel" data-bs-target="#logincancel" title="Login Cancel Sample" data-id="<?= $sample['sample_reg_id']; ?>"><img src="<?php echo base_url('assets/images/cancel.png') ?>" alt="Login Cancel Sample" style="height:16px; width:16px"></a>
                              <?php } ?>
                              <!-- Added by Saurabh on 08-02-2021 -->
                              <a href="javascript:void(0)" data-id="<?php echo $sample['sample_reg_id']; ?>" class="log_view" data-bs-toggle='modal' data-bs-target='#exampleModal' class="btn btn-sm" title="Log View"><img src="<?php echo base_url('assets/images/log-view.png'); ?>" alt="Log view" width="20px"></a>
                            <?php } ?>
                          </td>
                        </tr>
                    <?php }
                    } ?>
                  </tbody>
                </table>

              </div>

              <!-- Pagination -->
              <div class="card-header">
                <span id="sample-pagination"><?php echo $pagination; ?></span>
                <span><?php echo $result_count; ?></span>

              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <!-- Modal to Login Cancel Sample -->
  <div class="modal fade" id="logincancel" tabindex="-1" role="dialog" aria-labelledby="logincancelLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content modal-sm" style="margin: 0px auto;">
        <div class="modal-header">
          <h5 class="modal-title" id="logincancelLabel">Login Cancel Sample</h5>
          <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form action="<?php echo base_url("SampleRegistration_Controller/cancel_sample"); ?>" method="post" id="logincancel1">
          <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
          <input type="hidden" name="sample_reg_id" class="sample_reg_id">
          <div class="modal-body">
            <div class="form-group">
              <label for="">Comment</label>
              <textarea name="comment" id="" class="form-control form-control-sm"></textarea>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Save changes</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  <!-- Modal to Login Cancel Sample ends here-->

  <!-- Modal to view sample images -->
  <div class="modal fade" id="sample_images" tabindex="-1" role="dialog" aria-labelledby="sample_imagesLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content modal-lg" style="margin: 0px auto;">
        <div class="modal-header">
          <h5 class="modal-title" id="sample_imagesLabel">Sample Images</h5>
          <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div id="sample_image_view"></div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
  <!-- Modal to view sample images ends here-->

  <!-- Modal to upload sample images -->
  <div class="modal fade" id="upload_image" tabindex="-1" role="dialog" aria-labelledby="upload_imageLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content modal-sm" style="margin: 0px auto;">
        <div class="modal-header">
          <h5 class="modal-title" id="upload_imageLabel">Upload Sample Image</h5>
          <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form method="post" enctype="multipart/form-data" id="upload_sample_image">
          <div class="modal-body">
            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
            <input type="hidden" name="sample_reg_id" id="sample_reg_id">
            <div class="form-group">
              <input type="file" name="sample_image[]" multiple>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary" id="submit">Save changes</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  <!-- Modal to upload sample images ends here-->

  <!-- Modal to show worksheet data -->
  <div class="modal fade" id="worksheet" tabindex="-1" role="dialog" aria-labelledby="worksheetLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content modal-lg" style="margin: 0px auto;">
        <div class="modal-header">
          <h5 class="modal-title" id="worksheetLabel">Record Finding Details</h5>
          <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body" style="overflow-y:scroll; height:70vh;">
          <div id="worksheet_html"></div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary" id="print_worksheet">Print</button>
        </div>
      </div>
    </div>
  </div>
  <!-- Modal to show worksheet data ends here-->

  <!-- Modal to show barcode data -->
  <div class="modal fade" id="barcode" tabindex="-1" role="dialog" aria-labelledby="barcodeLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content modal-sm" style="margin: 0px auto;">
        <div class="modal-header">
          <h5 class="modal-title" id="barcodeLabel">Print Barcode</h5>
          <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div id="barcode_html"></div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary" id="print_barcode">Print</button>
        </div>
      </div>
    </div>
  </div>


  <div class="modal" tabindex="-1" role="dialog" id="generatePerformaInvoice">
    <div class="modal-dialog modal-sm" role="document">
      <div class="modal-content modal-sm" style="margin: 0 auto;">
        <div class="modal-header">
          <h5 class="modal-title">Confirm</h5>
          <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <input type="hidden" value="" class="sample_id">
          <p>
            Are you sure that you want to Generate Proforma Invoice ?.</p>
        </div>
        <div class="modal-footer">
          <button type="button" data-id="" class="btn btn-primary generate_performa_invoice">YES</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">NO</button>
        </div>
      </div>
    </div>
  </div>
  <!-- Modal to show barcode data ends here-->

  <!-- Sample Detail modal -->
  <div class="modal fade" id="sample_detail" tabindex="-1" role="dialog" aria-labelledby="sample_detailLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="sample_detailLabel">Sample Details - <span id='gc_no'></span></h5>
          <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <input type="hidden" id="sample_id">
          <table class="table table-hover text-nowrap">
            <tr>
              <th>Client</th>
              <td id="client"></td>
              <th>Labs</th>
              <td id="labs"></td>
            </tr>
            <tr>
              <th>Collected Time</th>
              <td id="collect_time"></td>
              <th>Recieved By</th>
              <td id="create_by"></td>
            </tr>
            <tr>
              <th>Sample Recieved Time</th>
              <td id="recieve_time"></td>
              <th>Recieved Quantity</th>
              <td id="qty_recieved"></td>
            </tr>
            <tr>
              <th>Test Specification</th>
              <td id="test_specification"></td>
              <th>Contact</th>
              <td id="contact"></td>
            </tr>
            <tr>
              <th>Product</th>
              <td id="trf_product"></td>
              <td colspan="2"></td>
            </tr>
            <tr>
              <th>Quantity Description</th>
              <td id="qty_desc"></td>
              <th>Retain Sample period</th>
              <td id="retain_sample"></td>
            </tr>
            <tr>
              <th>Sample Deadline</th>
              <td id="sample_deadline"></td>
              <th>Report Deadline</th>
              <td id="report_deadline"></td>
            </tr>
            <tr>
              <th>Sample Description</th>
              <td id="sample_desc"></td>
              <td colspan="2"></td>
            </tr>
            <tr>
              <th>Barcode Number</th>
              <td id="barcode_img"></td>
              <th>Tat Date</th>
              <td id="tat_date"></td>
            </tr>
          </table>
          <div class="row">
            <h4>Test Details</h4>
            <div class="col-md-1">
              <button class="btn btn-primary" id="product_test_list" data-bs-toggle='modal' data-bs-target='#product-list'>Add Test</button>
            </div>
            <div class="col-md-8">
              <div class="form-group">
                <label for="type">Type :</label>
                <input type="radio" name="price_type" id="book_price" value="1"> Book Price
                <input type="radio" name="price_type" id="flat_price" value="2"> Flat Price
                <input type="hidden" id="grid_details" value="">
              </div>
            </div>
            <div class="col-md-12" style="overflow-y: scroll;height: 150px;">
              <table class="table table-responsive">
                <thead>
                  <tr>
                    <th>SL NO.</th>
                    <th>Test Name</th>
                    <th>Test Method</th>
                    <th>Rate</th>
                    <th>Discount %</th>
                    <th>Applicable Charge</th>
                    <th>Test Description</th>
                    <th>Part</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody id="sample-test">
                </tbody>
              </table>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary" id="save-evaluation">Save changes</button>
        </div>
      </div>
    </div>
  </div>
  <!-- Sample detail modal ends here -->

  <!-- Part list modal -->
  <div class="modal fade" id="part-list" tabindex="-1" role="dialog" aria-labelledby="partListLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="partListLabel">Part List</h5>
          <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-12">
              <button id="add_part" class="btn btn-default">Add Part</button>
            </div>
          </div>
          <div class="row" id="part-add-form" style="display:none">
            <div class="col-md-4">
              <div class="form-group">
                <input type="text" id="part_name" class="form-control" placeholder="Part Name">
                <span class="text-danger" id="name_error"></span>
              </div>
            </div>

            <div class="col-md-4">
              <div class="form-group">
                <input type="text" id="part_desc" class="form-control" placeholder="Part Description">
                <span class="text-danger" id="desc_error"></span>
              </div>
            </div>
            <input type="hidden" id="part_sample_reg_id" value="">
            <input type="hidden" id="sample_test_id" value="">
            <input type="hidden" id="part_id" value="">
            <div class="col-md-4">
              <button class="btn btn-primary" id="submit_part">Save</button>
              <button class="btn btn-danger" id="cancel">Cancel</button>
            </div>
          </div>
          <span id="part-error" class="text-danger"></span>
          <div class="row" id="sample_part_type">
            <div class="col-md-12">
              <label for="">Sample Part Type : </label>
              <input type="radio" name="part_type" class="sample_part_type" value="0"> Single
              <input type="radio" name="part_type" class="sample_part_type" value="1"> Composite
            </div>
          </div>
          <table class="table">
            <thead>
              <tr>
                <td>Select</td>
                <td>Part Name</td>
                <td>Part Description</td>
                <td>Action</td>
              </tr>
            </thead>
            <tbody id="part-listing">
            </tbody>
          </table>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary" id="add-part-sample">Save changes</button>
        </div>
      </div>
    </div>
  </div>
  <!-- Part list modal ends here -->

  <!-- Modal for uploading manual report added by millan on 15-01-2021 -->
  <div class="modal fade" id="manual_report_pdf_upload" tabindex="-1" role="dialog" aria-labelledby="mrpLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg " role="document">
      <div class="modal-content" style="margin: 0px auto;">
        <div class="modal-header">
          <h5 class="modal-title" id="mrpLabel">Upload Manual Report PDF</h5>
          <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form method="post" enctype="multipart/form-data" id="upload_mrpdf">
          <div class="modal-body">
            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
            <input type="hidden" name="sample_reg_id" id="mrp_sample_reg_id" accept="application/pdf">
            <div class="row">
              <div class="col-sm-6">
                <h6>Report Number:</h6>
                <h3 id="GCNUMBER_upload"></h3>
              </div>
              <div class="col-sm-6">
                <h6>Alternate Report Number:</h6>
                <input type="text" name="alternate_report_number" class="form-control form control-sm">
              </div>
            </div>
            <div class="row">
              <div class="col-sm-3">
                <h6>Report Date:</h6>
                <input type="date" name="generated_date" value="<?php echo date('Y-m-d', time('now')) ?>" class="form-control form control-sm">
              </div>
              <div class="col-sm-3">
                <h6>Result Ready Date:</h6>
                <input type="date" name="mr_result_ready_date" value="<?php echo date('Y-m-d', time('now')) ?>" class="form-control form control-sm">
              </div>
              <div class="col-sm-6">
                <h6>Result:</h6>
                <select name="manual_report_result" class="form-control form-control-sm" id="result_upload_manual">
                  <option value="">Select Result</option>
                  <option value="1">Pass</option>
                  <option value="2">Fail</option>
                  <option value="3">Other</option>
                </select>
              </div>
            </div>
            <div class="row">
              <div class="col-sm-4">
                <div class="form-group">
                  <h6>Upload Worksheet Document:</h6>
                  <input type="file" class="form-control" name="manual_report_worksheet">
                </div>
              </div>
              <div class="col-sm-4">
                <div class="form-group">
                  <h6>Upload Report Document:</h6>
                  <input type="file" class="form-control" name="manual_report_file">
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary"> <i class="fa fa-cloud-upload" aria-hidden="true"></i> Upload Pdf</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  <!-- Modal for uploading manual report added by millan on 15-01-2021 ends -->

  <!-- Modal for showing and downloading QR Code added by millan on 19-Jan-2021 -->
  <div class="modal fade" id="download_qr_code" tabindex="-1" role="dialog" aria-labelledby="qrdLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content modal-sm" style="margin: 0px auto;">
        <div class="modal-header">
          <h5 class="modal-title" id="qrdLabel">Download QR Code</h5>
          <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="sample_reg_id" id="qrd_sample_reg_id" value="" accept="application/pdf">
          <div class="form-group set_qr text-center">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <a id="download_qr" download><button type="button" class="btn btn-primary"> <i class="fa fa-qrcode" aria-hidden="true"></i> Download QR</button> </a>
        </div>
      </div>
    </div>
  </div>
  <!-- Modal for showing and downloading QR Code added by millan on 15-01-2021 ends -->

  <!-- Modal to show lab name -->
  <div class="modal fade" id="worksheetlab" tabindex="-1" role="dialog" aria-labelledby="worksheet_labLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
      <div class="modal-content modal-sm" style="margin: 0px auto;">
        <div class="modal-header">
          <h5 class="modal-title" id="worksheet_labLabel">Lab Types</h5>
          <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form action="<?php echo base_url('SampleRegistration_Controller/record_finding_pdf') ?>" method="post" id="lab_worksheet">
          <div class="modal-body">
            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
            <input type="hidden" name="sample_reg_id" class="sample_reg_id">
            <div class="form-group">
              <label for="">Lab Name</label>
              <select name="lab_type_id" id="labs_name" class="form-control form-control-sm">
                <option selected="" disabled>Select Lab</option>
              </select>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Submit</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  <!-- Modal to show lab name ends here-->

  <script>
    $(document).ready(function() {

      // added by ajit 30-03-2021
      $('.check_all_release').on('change', function() {
        var check_element = $('.realeased_to_check');
        if (this.checked) {
          make_session(null, true, true);

          $.each(check_element, function(ind, pak) {

            $(pak).attr('checked', true);
          })
        } else {
          make_session(null, false, false);
          $.each(check_element, function(ind, pak) {

            $(pak).attr('checked', false);
          })
        }
      });


      $(document).on('change', '.realeased_to_check', function() {
        sample_reg_id = $(this).val();
        if (this.checked) {
          make_session(sample_reg_id, true, false);
        } else {
          make_session(sample_reg_id, false, false);
          $('.check_all_release').attr('checked', false);
        }
      })


      function make_session(sample_reg_id = null, status, chek_all) {
        const _tokken = $('meta[name="_tokken"]').attr('value');
        $.ajax({
          url: "<?php echo base_url('SampleRegistration_Controller/make_session') ?>",
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
      $('.release_all_to_client').on('click', function() {
        var check = $('.check_all_release').val();

        const _tokken = $('meta[name="_tokken"]').attr('value');
        $.ajax({
          url: "<?php echo base_url('SampleRegistration_Controller/release_to_client_all') ?>",
          method: "POST",
          data: {
            _tokken: _tokken
          },
          success: function(data) {
            var data = $.parseJSON(data);
            if (data.status > 0) {
              $.notify(data.msg, 'success');

            } else {
              $.notify(data.msg, 'error');
            }
            window.location.reload();
          }
        });


      })


      // end

      $(document).on('click', '.generate_performa_btn', function() {
        var id = $(this).attr('data-id');
        $('.sample_id').val(id)
      })

      $(document).on('click', '.generate_performa_invoice', function() {
        const _tokken = $('meta[name="_tokken"]').attr('value');
        var sample_reg_id = $('.sample_id').val();
        $.ajax({
          url: "<?php echo base_url('SampleRegistration_Controller/generate_performa_invoice') ?>",
          method: "post",
          data: {

            _tokken: _tokken,
            sample_reg_id: sample_reg_id
          },
          success: function(data) {
            var data = $.parseJSON(data);
            if (data.status > 0) {
              $('#generatePerformaInvoice').modal('hide');
              // $('.performa_span').html("");
              $.notify(data.msg, 'success');
              window.location.reload();
            } else {
              $.notify(data.msg, 'error');
            }
          }
        })
      })

      /**------------------------clone sample---------------- */
      $(document).on('click', '#sample_clone', function() {
        const _tokken = $('meta[name="_tokken"]').attr('value');
        if (confirm("Do you want to clone this Sample ? Please confirm?")) {
          var trf_id = $(this).data('trf_id');
          var sample_id = $(this).data('id');
          $.ajax({
            type: 'post',
            url: "<?php echo base_url('SampleRegistration_Controller/clone_sample'); ?>",
            data: {
              _tokken: _tokken,
              trf_id: trf_id,
              sample_id: sample_id
            },
            dataType: 'json',
            success: function(data) {
              // var data = $.parseJSON(result.status);
              if (data.status > 0) {

                window.location.reload();
                $.notify(data.msg, 'success');

              } else {
                $.notify(data.msg, 'error');
              }
            }
          })
        }
      });
      /**------------------end ----------clone sample---------------- */
    })
  </script>
  <!-- Change radio to checkbox for part name -->
  <script>
    const url = $('body').data('url');
    $(document).on('click', '#filter', function() {
      filter(0);
    });

    $(document).on('click', '#reset', function() {
      $('#trf_reference_number').val('');
      $('#customer_name').val('');
      $('#product').val('');
      $('#created_on').val('');
      $('#gc_number').val('');
      $('#ulr_no').val('');
      $('#buyer').val('');
      $('#status').val('');
      $('#division').val('');
      filter(0);

    });

    $('#sample-pagination').on('click', 'a', function(e) {
      e.preventDefault();
      var page = $(this).attr('data-ci-pagination-page');

      filter(page);
    });

    function filter(page) {
      var trf_number = $('#trf_reference_number').val();
      if (trf_number == "") {
        trf_number = null;
      } else {
        trf_number = btoa($('#trf_reference_number').val());
      }

      var customer_name = $('#customer_name').val();
      if (customer_name == "") {
        customer_name = null;
      } else {
        customer_name = $('#customer_name').val();
      }

      var product = $('#product').val();
      if (product == "") {
        product = null;
      } else {
        product = $('#product').val();
      }

      var created_on = $('#created_on').val();
      if (created_on == "") {
        created_on = null;
      } else {
        created_on = btoa($('#created_on').val());
      }

      var ulr_no = $('#ulr_no').val();
      if (ulr_no == "") {
        ulr_no = null;
      } else {
        ulr_no = btoa($('#ulr_no').val());
      }

      var gc_number = $('#gc_number').val();
      if (gc_number == "") {
        gc_number = null;
      } else {
        gc_number = btoa($('#gc_number').val());
      }

      var buyer = $('#buyer').val();
      if (buyer == "") {
        buyer = null;
      } else {
        buyer = $('#buyer').val();
      }

      var division = $('#division').val();
      if (division == "") {
        division = null;
      } else {
        division = $('#division').val();
      }

      var status = $('#status').val();
      if (status == "") {
        status = null;
      } else {
        status = btoa($('#status').val());
      }


      window.location.replace(url + 'sample-list/' + page + '/' + trf_number + '/' + customer_name + '/' + product + '/' + created_on + '/' + ulr_no + '/' + gc_number + '/' + buyer + '/' + status + '/' + division);
    }
  </script>
  <script>
    $(document).on('click', '#login_cancel', function() {
      var sample_reg_id = $(this).data('id');
      $('.sample_reg_id').val(sample_reg_id);
    });

    $('#logincancel1').submit(function(e) {
      e.preventDefault();
      $('body').append('<div class="pageloader"></div>');
      var form = $(this);
      $.ajax({
        type: 'post',
        url: form.attr('action'),
        data: $(this).serialize(),
        dataType: 'json',
        success: function(data) {
          $('.pageloader').remove();
          if (data.status > 0) {
            $('#test').modal('hide');
            $.notify(data.message, "success");
            window.location.reload();
          } else {
            $.notify(data.message, "error");
          }
        }
      });
    });
  </script>
  <script>
    const _tokken = $('meta[name="_tokken"]').attr('value');
    $('.worksheet_lab').click(function() {
      $('#labs_name').empty();
      var sample_reg_id = $(this).data('one');
      $('.sample_reg_id').val(sample_reg_id);
      $.ajax({
        type: 'post',
        url: url + 'SampleRegistration_Controller/get_test_labs',
        data: {
          _tokken: _tokken,
          sample_reg_id: sample_reg_id
        },
        success: function(data) {
          var data = $.parseJSON(data);
          $.each(data, function(key, value) {
            $('#labs_name').append($('<option></option>').attr('value', value.lab_type_id).text(value.lab_type_name));
          })
        }
      })
    });

    $('#lab_worksheet').submit(function(e) {
      $('#worksheet_html').empty();
      var self = $(this);
      e.preventDefault();
      $.ajax({
        type: "post",
        processData: false,
        contentType: false,
        cache: false,
        async: false,
        url: self.attr('action'),
        data: new FormData(this),
        success: function(data) {
          var data = $.parseJSON(data);
          $('#worksheetlab').modal('hide');
          $('#worksheet').modal('show');
          $('#worksheet_html').html(data);
        }
      });
    });
  </script>

  <?php include('webcam.php'); ?>