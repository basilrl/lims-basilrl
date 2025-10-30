  <?php
  $trf_no = $this->uri->segment(4);
  if ($trf_no != "null") {
    $trf_no = base64_decode($trf_no);
  } else {
    $trf_no = "";
  }
  $customer_id = ($this->uri->segment(5)) ? $this->uri->segment(5) : '';
  $product_name = ($this->uri->segment(6)) ? $this->uri->segment(6) : '';
  $buyer_id = ($this->uri->segment(10)) ? $this->uri->segment(10) : '';
  $division_id = ($this->uri->segment(12)) ? $this->uri->segment(12) : '';
  $created_on = $this->uri->segment(7);
  if ($created_on != "null") {
    $created_on = base64_decode($created_on);
  } else {
    $created_on = "";
  }
  $style_no = $this->uri->segment(13);
  if ($style_no != "null") {
    $style_no = base64_decode($style_no);
  } else {
    $style_no = "";
  }

  $start_date = $this->uri->segment(14);
  if ($start_date != "null") {
    $start_date = base64_decode($start_date);
  } else {
    $start_date = "";
  }

  $end_date = $this->uri->segment(15);
  if ($end_date != "null") {
    $end_date = base64_decode($end_date);
  } else {
    $end_date = "";
  }

  $status = $this->uri->segment(11);
  if ($status != "null") {
    $status = base64_decode($status);
  } else {
    $status = "";
  }
  $gc_number = $this->uri->segment(9);
  if ($gc_number != "null") {
    $gc_number = base64_decode($gc_number);
  } else {
    $gc_number = "";
  }

  $ulr_number = $this->uri->segment(8);
  if ($ulr_number != "null") {
    $ulr_number = base64_decode($ulr_number);
  } else {
    $ulr_number = "";
  }
  ?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
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
                  <input type="text" placeholder="Basil Report Number" name="gc_number" class="form-control form-control-sm" id="gc_number" value="<?php echo $gc_number; ?>">
                </div>
              </div>
              <div class="col-md-3">
                <input type="text" name="ulr_no" placeholder="ULR Number" class="form-control form-control-sm" id="ulr_no" value="<?php echo $ulr_number; ?>">
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <input type="text" placeholder="TRF Reference Number" class="form-control form-control-sm" id="trf_reference_number" name="trf_reference_number" value="<?php echo $trf_no; ?>">
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

            </div>

            <div class="row">

              <div class="col-md-3">
                <div class="form-group">
                  <select class="select-box" class="form-control form-control-sm" name="customer_name" id="customer_name">
                    <option selected value="">Select Customer</option>
                    <?php if (!empty($customer)) { ?>
                      <?php foreach ($customer as $value) { ?>
                        <option value="<?php echo $value->customer_id; ?>" <?php if ($customer_id == $value->customer_id) {
                                                                              echo "selected";
                                                                            } ?>><?php echo $value->customer_name; ?></option>
                      <?php } ?>
                    <?php } ?>
                  </select>
                </div>
              </div>

              <div class="col-md-3">
                <div class="form-group">
                  <select class="select-box" class="form-control form-control-sm" name="buyer" id="buyer">
                    <option selected value="">Select Buyer</option>
                    <?php if (!empty($buyer)) { ?>
                      <?php foreach ($buyer as $value) { ?>
                        <option value="<?php echo $value->customer_id; ?>" <?php if ($buyer_id == $value->customer_id) {
                                                                              echo "selected";
                                                                            } ?>><?php echo $value->customer_name; ?></option>
                      <?php } ?>
                    <?php } ?>
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

            </div>

            <div class="row">


              <div class="col-md-3">
                <div class="form-group">
                  <input type="text" placeholder="Created On" class="form-control form-control-sm" id="created_on" name="created_on" value="<?php echo $created_on; ?>">
                </div>
              </div>

              <div class="col-md-3">
                <div class="form-group">
                  <input type="text" placeholder="Style Number" class="form-control form-control-sm" id="style_no" name="style_no" value="<?php echo $style_no; ?>">
                </div>
              </div>

              <div class="col-md-3">
                <div class="form-group">
                  <input type="text" placeholder="Start Date" class="form-control form-control-sm datepicker" id="start_date" name="start_date" value="<?php echo $start_date; ?>">
                </div>
              </div>

              <div class="col-md-3">
                <div class="form-group">
                  <input type="text" placeholder="End Date" class="form-control form-control-sm datepicker" id="end_date" name="end_date" value="<?php echo $end_date; ?>">
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


          </div>
          <!-- </form> -->
        </div>

      </div>
      <!-- Main content -->
      <section class="content">
        <div class="container-fluid">
          <!-- /.row -->
          <div class="row">
            <div class="col-12">
              <div class="card">
                <div class="card-header">
                  <div class="row">
                    <div class="col-sm-11">
                      <h3 class="card-title">Sample Registration</h3>
                    </div>

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
                        <th>SL No.</th>
                        <th>Basil Report No.</th>
                        <th>Revise/Partial Basil Report No.</th>
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
                        <?php if ((exist_val('SampleRegistration_Controller/show_barcode', $this->session->userdata('permission'))) ||
                          (exist_val('SampleRegistration_Controller/forward_sample_for_manual_reporting', $this->session->userdata('permission')))
                          ) { ?>
                          <!-- added by Millan on 24-02-2021 -->
                          <th>Action</th>
                        <?php } ?>
                      </tr>
                    </thead>
                    <tbody id="sample-listing">
                      <?php if (!empty($partial_report)) {
                        $page = $this->uri->segment(3);
                        $sno = (($page ? $page : '1') - 1) * 10;
                        foreach ($partial_report as $sample) { ?>
                          <tr>
                            <td><?php echo $sno += 1; ?></td>
                            <td><?php echo $sample['gc_no']; ?></td>
                            <td><?php echo $sample['revise_report_num']; ?></td>
                            <td><img src="<?php echo $sample['barcode_path']; ?>"></td>
                            <td><?php echo $sample['ulr_no']; ?></td>
                            <td><?php echo $sample['sample_type_name']; ?></td>
                            <td><?php echo $sample['trf_ref_no']; ?></td>
                            <td><?php echo $sample['customer'] ? $sample['customer'] : 'N/A'; ?></td>
                            <td><?php echo $sample['buyer'] ? $sample['buyer'] : 'N/A'; ?></td>
                            <td><?php echo $sample['status']; ?></td>
                            <td><?php echo $sample['created_by']; ?></td>
                            <td><?php echo change_time($sample['created_on'], $this->session->userdata('timezone')); ?></td>
                            <td><?php echo date('Y-m-d', strtotime($sample['due_date'])); ?></td>
                            <td><?php echo $sample['tat_date']; ?></td>
                            <td><?php echo $sample['comment']; ?></td>
                            <td>
                              <?php if ($sample['status'] != "Login Cancelled") { ?>
                                <?php if (!empty($sample['buyer'])) { ?>
                                  <?php if ($sample['buyer_active'] == 'Active' && $sample['customer_active'] = 'Active' && $sample['status'] != "Hold Sample") { ?>

                                    <!-- Added by Saurabh on 19-02-2021 -->
                                   
                                    <!-- added by millan on 15-01-2021 -->
                                    
                                      <?php if (!empty($sample['manual_report_file']) && $sample['manual_report_file'] != '') { ?>
                                        <?php if (exist_val('SampleRegistration_Controller/download_manual_report_pdf', $this->session->userdata('permission'))) { ?>
                                          <!-- added by Millan on 24-02-2021 -->
                                          <a class="btn btn-sm" href="<?php echo base_url('SampleRegistration_Controller/download_pdf?sample_reg_id=' . base64_encode($sample['sample_reg_id']).'&report_id='.base64_encode($sample['report_id'])); ?>" id="download_manualpdfaws" title="Download Manual Report Pdf" data-id="<?php echo $sample['sample_reg_id']; ?>"><img src="<?php echo base_url('assets/images/downloadpdf.png') ?>" alt="Download Pdf From AWS" style="height:20px; width: 20px"></a>
                                        <?php } ?>
                                      <?php } else { ?>
                                        <?php if (exist_val('SampleRegistration_Controller/download_QR', $this->session->userdata('permission'))) { ?>
                                          <!-- added by Millan on 24-02-2021 -->
                                          <a class="btn btn-sm" download="" title="QRCODE DOWNLOAD" href="<?php echo base_url('SampleRegistration_Controller/download_QRCODE/' . base64_encode($sample['qr_code_name'])); ?>"><img src="<?php echo base_url('assets/images/qr-code-scan.png') ?>" alt="Download QR Code" width="22px"></a>
                                        <?php } ?>
                                        <!-- <a href="javascript:void(0)" data-bs-toggle="modal" class="qrcode_download" data-bs-target="#download_qr_code" title="Download QR Code" data-id="<?php echo $sample['sample_reg_id']; ?>"><img src="<?php echo base_url('assets/images/qr-code-scan.png') ?>" alt="Download QR Code" style="height:20px;"></a> -->
                                        <?php if (exist_val('SampleRegistration_Controller/upload_pdf_qr', $this->session->userdata('permission'))) { ?>
                                          <!-- added by Millan on 24-02-2021 -->
                                          <a href="javascript:void(0)" data-bs-toggle="modal" class="btn btn-sm manualreportpdf_upload" data-bs-target="#manual_report_pdf_upload" title="Upload Maunal Report PDF" data-id="<?php echo $sample['sample_reg_id']; ?>" data-report_id="<?php echo $sample['report_id']; ?>"><img src="<?php echo base_url('assets/images/upload_fl.png') ?>" alt="Upload Maunal Report PDF"></a>
                                        <?php } ?>
                                      <?php } ?>

                                    <!-- Added by Saurabh on 08-02-2021 -->
                                    
                                  <?php } ?>
                                  
                                <?php } else { ?>
                                 
                              
                                    <?php if (!empty($sample['manual_report_file']) && $sample['manual_report_file'] != '') { ?>
                                      <?php if (exist_val('SampleRegistration_Controller/download_manual_report_pdf', $this->session->userdata('permission'))) { ?>
                                        <!-- added by Millan on 24-02-2021 -->
                                        <a class="btn btn-sm" href="<?php echo base_url('SampleRegistration_Controller/download_pdf?sample_reg_id=' . base64_encode($sample['sample_reg_id'])); ?>" id="download_manualpdfaws" title="Download Manual Report Pdf" data-id="<?php echo $sample['sample_reg_id']; ?>"><img src="<?php echo base_url('assets/images/downloadpdf.png') ?>" alt="Download Pdf From AWS" style="height:20px; width: 20px"></a>
                                      <?php } ?>
                                    <?php } else { ?>
                                      <?php if (exist_val('SampleRegistration_Controller/download_QR', $this->session->userdata('permission'))) { ?>
                                        <!-- added by Millan on 24-02-2021 -->
                                        <a class="btn btn-sm" download="" title="QRCODE DOWNLOAD" href="<?php echo base_url('SampleRegistration_Controller/download_QRCODE/' . base64_encode($sample['qr_code_name'])); ?>"><img src="<?php echo base_url('assets/images/qr-code-scan.png') ?>" alt="Download QR Code" width="22px"></a>
                                      <?php } ?>
                                      <!-- <a href="javascript:void(0)" data-bs-toggle="modal" class="qrcode_download" data-bs-target="#download_qr_code" title="Download QR Code" data-id="<?php echo $sample['sample_reg_id']; ?>"><img src="<?php echo base_url('assets/images/qr-code-scan.png') ?>" alt="Download QR Code" style="height:20px;"></a> -->
                                      <?php if (exist_val('SampleRegistration_Controller/upload_pdf_qr', $this->session->userdata('permission'))) { ?>
                                        <!-- added by Millan on 24-02-2021 -->
                                        <a href="javascript:void(0)" data-bs-toggle="modal" class="btn btn-sm manualreportpdf_upload" data-bs-target="#manual_report_pdf_upload" title="Upload Maunal Report PDF" data-id="<?php echo $sample['sample_reg_id']; ?>" data-report_id="<?php echo $sample['report_id']; ?>"><img src="<?php echo base_url('assets/images/upload_fl.png') ?>" alt="Upload Maunal Report PDF"></a>
                                      <?php } ?>
                                    <?php } ?>
                                    
                                <?php } ?>
                              <?php } ?>
                            </td>
                          </tr>
                      <?php }
                      } else {
                        echo "<tr><td colspan='15'>No record found</td></tr>";
                      } ?>
                    </tbody>
                  </table>

                </div>

                <!-- Pagination -->
                <div class="card-header">
                  <span id="sample-pagination"><?php //echo $pagination; ?></span>
                  <span><?php //echo $result_count; ?></span>

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
        <div class="modal-body" style="overflow-y: scroll; height:500px">
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
              <div class="add_test_btn"></div>
            </div>
            <div class="col-md-8">
              <div class="form-group">
                <label for="type">Type :</label>
                <input type="radio" name="price_type" id="book_price" value="1"> Book Price
                <input type="radio" name="price_type" id="flat_price" value="2"> Flat Price
                <input type="hidden" id="grid_details" value="">
              </div>
            </div>
            <div class="col-md-12">
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
          <span class="save_evaluation"></span>

        </div>
      </div>
    </div>
  </div>
  <!-- Sample detail modal ends here -->

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
            <input type="hidden" name="report_id" id="mrp_report_id" accept="application/pdf">
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


  <script>
  $(document).ready(function(){
    const url = $('body').data('url');
    const _tokken = $('meta[name="_tokken"]').attr('value');

         // set sample_reg_id using ajax for uploading manual report added by millan on 15-Jan-2021
    $(document).on('click', '.manualreportpdf_upload', function() {
        var sample_reg_id = $(this).data('id');
        $('#mrp_sample_reg_id').val(sample_reg_id);
        $.ajax({
            url: url + 'Manual_report/get_gcNo',
            type: 'post',
            data: { _tokken: _tokken, sample_reg_id: sample_reg_id },
            success: function(data) {
                var data = $.parseJSON(data);
                $('#GCNUMBER_upload').html('NOT FOUND');
                if (data) {
                    $('#GCNUMBER_upload').html(data.gc_no);
                }
            },
            error: function(e) {
                console.log(e);
            }
        });
    });
       // set sample_reg_id using ajax for uploading manual report added by millan on 15-Jan-2021
       $(document).on('click', '.manualreportpdf_upload', function() {
        var sample_reg_id = $(this).data('id');
        var report_id = $(this).data('report_id');
        $('#mrp_sample_reg_id').val(sample_reg_id);
        $('#mrp_report_id').val(report_id);
        $.ajax({
            url: url + 'Manual_report/get_gcNo',
            type: 'post',
            data: { _tokken: _tokken, sample_reg_id: sample_reg_id },
            success: function(data) {
                var data = $.parseJSON(data);
                $('#GCNUMBER_upload').html('NOT FOUND');
                if (data) {
                    $('#GCNUMBER_upload').html(data.gc_no);
                }
            },
            error: function(e) {
                console.log(e);
            }
        });
    });

    // upload manual report pdf function call ajax added by millan on 15-Jan-2021
    $('#upload_mrpdf').submit(function(e) {
        e.preventDefault();
        $.ajax({
            type: "post",
            processData: false,
            contentType: false,
            cache: false,
            async: false,
            url: url + 'Manual_report/upload_partial_report',
            data: new FormData(this),
            success: function(data) {
                var data = $.parseJSON(data);
                if (data.status > 0) {
                    $.notify(data.msg, 'success');
                    window.location.reload();
                } else {
                    $.notify(data.msg, 'error');
                }
                if (data.errors) {
                    $('.error_mr_upload').remove();
                    $.each(data.errors, function(i, v) {
                        $('#upload_mrpdf input[name="' + i + '"]').after('<span class="error_mr_upload text-danger">' + v + '</span>');
                        $('#upload_mrpdf select[name="' + i + '"]').after('<span class="error_mr_upload text-danger">' + v + '</span>');
                        $('#upload_mrpdf textarea[name="' + i + '"]').after('<span class="text-danger error_mr_upload">' + v + '</span>');
                    });
                } else {
                    $('.error_mr_upload').remove();
                }
            }
        });
    });

    $(document).on('change', '#result_upload_manual', function() {
        var id = $(this).val();
        var self = $(this);
        if (id == 3) {
            self.after('<br><h6 class="other">Other Remark</h6><textarea class="other form-control form-control-sm" name="manual_report_remark"></textarea>');
        } else {
            self.siblings('.other').remove();
        }
    });

    // displaying and downloading qrcode modal using ajax added by millan on 19-Jan-2021
    $(document).on('click', '.qrcode_download', function() {
        var sample_reg_id = $(this).data('id');
        $('#qrd_sample_reg_id').val(sample_reg_id);
        $.ajax({
            type: 'post',
            url: url + 'show-qr-code',
            data: { sample_reg_id: sample_reg_id, _tokken: _tokken },
            dataType: 'json',
            success: function(data) {
                if (data.qr_path) {
                    $('.set_qr').html('<img src="' + data.qr_path + '">');
                    $('#download_qr').attr('href', data.qr_path);
                } else {
                    $('.set_qr').html('NOT FOUND');
                    $('#download_qr').hide();
                }
            }
        });
    });
    // Code ends here
  });
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
      $('#style_no').val('');
      $('#start_date').val('');
      $('#end_date').val('');
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

      var style_no = $('#style_no').val();
      if (style_no == "") {
        style_no = null;
      } else {
        style_no = btoa($('#style_no').val());
      }

      var start_date = $('#start_date').val();
      if (start_date == "") {
        start_date = null;
      } else {
        start_date = btoa($('#start_date').val());
      }

      var end_date = $('#end_date').val();
      if (end_date == "") {
        end_date = null;
      } else {
        end_date = btoa($('#end_date').val());
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


      window.location.replace(url + 'SampleRegistration_Controller/partial_revise_sample/' + page + '/' + trf_number + '/' + customer_name + '/' + product + '/' + created_on + '/' + ulr_no + '/' + gc_number + '/' + buyer + '/' + status + '/' + division + '/' + style_no + '/' + start_date + '/' + end_date);
    }
  </script>

  

