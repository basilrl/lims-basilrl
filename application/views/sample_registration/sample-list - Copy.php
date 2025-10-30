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

        <div class="row" style="display:none">
         <div class="col-md-12">
          <form action="">
          <div class="row">
            <div class="col-md-3">
              <div class="form-group">
                <input type="text" placeholder="TRF Reference Number" class="form-control" id="trf_reference_number">
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
              <select name="" class="select-box" class="form-control" id="customer_name">
                  <option selected value="">Select Customer</option>
                  <?php if(!empty($customer)){ foreach($customer as $value){ ?>
                    <option value="<?php echo $value['customer_id']; ?>"><?php echo $value['customer_name'] ?></option>
                  <?php } } ?>
                </select>
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <select name="" class="select-box" class="form-control" id="product">
                  <option selected value="">Select Product</option>
                  <?php if(!empty($products)){ foreach($products as $value){ ?>
                    <option value="<?php echo $value['sample_type_id']; ?>"><?php echo $value['sample_type_name'] ?></option>
                  <?php } } ?>
                </select>
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <input type="text" placeholder="Created On" class="form-control" id="created_on">
              </div>
            </div>
            <div class="col-md-3" style="display:none">
              <div class="form-group">
                <input type="text" placeholder="Created By" class="form-control" id="created_by">
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
                  <button type="button" id="search-trf" class="btn btn-primary">Search</button>
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
                <h3 class="card-title">Sample Registration</h3>
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
              <!-- /.card-header -->
              <input type="hidden" id="order" value="">
              <input type="hidden" id="column" value="">
              <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap table-sm" id="sample-list">
                  <thead>
                    <tr>
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
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody id="sample-listing">
                    <?php if(!empty($sample_registered)) { $page = $this->uri->segment(2); $sno = (($page?$page:'1')-1) * 10; foreach($sample_registered as $sample){?>
                    <tr>
                    <td><?php echo $sno += 1; ?></td>
                    <td><?php echo $sample['gc_no']; ?></td>
                    <td><img src="<?php echo $sample['barcode_path']; ?>"></td>
                    <td><?php echo $sample['ulr_no']; ?></td>
                    <td><?php echo $sample['sample_type_name']; ?></td>
                    <td><?php echo $sample['trf_ref_no']; ?></td>
                    <td><?php echo $sample['customer']?$sample['customer']:'N/A'; ?></td>
                    <td><?php echo $sample['buyer']?$sample['buyer']:'N/A'; ?></td>
                    <td><?php echo $sample['status']; ?></td>
                    <td><?php echo $sample['created_by']; ?></td>
                    <td><?php echo $sample['created_on']; ?></td>
                    <td><?php 
                      if($sample['status'] == "Sample Sent for Evaluation" || $sample['status'] == "Evaluation Completed"){  
                        echo "<a href='javascript:void(0)' data-bs-toggle='modal' data-bs-target='#sample_detail' id='show_detail' title='View Detail' data-one='".$sample['sample_reg_id']."'><img src='".base_url('assets/images/view_jobs_in_panel.png')."'></a>";


                        ?>
                              <span class="performa_span">
                              <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#generatePerformaInvoice" data-id="<?php echo $sample['sample_reg_id'] ?>" title="Generate Performa Invoice" class="generate_performa_btn"><img src="<?php echo base_url('assets/images/Generated invoice.png'); ?>"></a>
                              </span>

                            <?php 
                      } else {
                        echo "<a href='javascript:void(0)' title='Send for Sample Evaluation' id='sent_sample' data-one='".$sample['sample_reg_id']."'><img src='".base_url('assets/images/bullet_go.png')."'></a>";
                      }
                    ?>
		     <!-- added by millan on 15-01-2021 -->
                            <?php if( ($sample['status'] != "Sample Sent for Manual Reporting") && ($sample['status'] != "Sample Sent for Evaluation") ) { ?>
                              <a href="javascript:void(0)" class="manual_reporting_sample" title="Forward Sample for Manual Reporting" data-id="<?= $sample['sample_reg_id']; ?>"><img src="<?php echo base_url('assets/images/accept.png') ?>" alt="Forward Sample for Manual Reporting"></a>
                            <?php } if( ($sample['status'] == "Sample Sent for Manual Reporting" && $sample['status'] != "Sample Sent for Evaluation")) { ?>
                              <a href="javascript:void(0)" data-bs-toggle="modal" class="qrcode_download" data-bs-target="#download_qr_code" title="Download QR Code" data-id="<?= $sample['sample_reg_id']; ?>"><img src="<?php echo base_url('assets/images/qr-code-scan.png') ?>" alt="Download QR Code" style="height:20px;"></a>
                              <a href="javascript:void(0)" data-bs-toggle="modal" class="manualreportpdf_upload" data-bs-target="#manual_report_pdf_upload" title="Upload Maunal Report PDF" data-id="<?= $sample['sample_reg_id']; ?>"><img src="<?php echo base_url('assets/images/upload_fl.png') ?>" alt="Upload Maunal Report PDF"></a>
                            <?php } ?>
                            <!-- added by millan on 15-01-2021 -->
                            
                            <!-- added by millan on 19-01-2021 -->
                              <?php if( !empty($sample['manual_report_file']) && $sample['manual_report_file']!='' && $sample['status'] == "Sample Sent for Manual Reporting" ) { ?>
                                <a href="<?php echo base_url('SampleRegistration_Controller/download_pdf?sample_reg_id='.base64_encode($sample['sample_reg_id'])); ?>" id="download_manualpdfaws" title="Download Manual Report Pdf" data-id="<?= $sample['sample_reg_id']; ?>"><img src="<?php echo base_url('assets/images/downloadpdf.png') ?>" alt="Download Pdf From AWS" style="height:20px; width: 20px"></a>
                              <?php } ?>
                            <!-- added by millan on 19-01-2021 -->
                    <a href="javascript:void(0)" data-bs-toggle="modal" id="sample_image_upload" data-bs-target="#upload_image" title="Upload Images" data-one="<?=$sample['sample_reg_id'];?>"><img src="<?php echo base_url('assets/images/image.png')?>" alt="Upload Image" style="height:16px; width:16px;"></a>
                    <a href="javascript:void(0)" id="send_email" title="Resend Sample Acknowledgeent Mail" data-one="<?=$sample['sample_reg_id'];?>" data-two="sample_registration"><img src="<?php echo base_url('assets/images/send_email.png')?>" alt="Send Email"></a>
                    <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#worksheet" id="show_worksheet" title="Worksheet" data-one="<?=$sample['sample_reg_id'];?>"><img src="<?php echo base_url('assets/images/worksheet.png')?>" alt="Worksheet"></a>
                    <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#barcode" id="show_barcode" title="Show Barcode" data-one="<?=$sample['sample_reg_id'];?>"><img src="<?php echo base_url('assets/images/printer.png')?>" alt="Printer"></a>
                    </td>
                    </tr>
                    <?php } }?>
                  </tbody>
                </table>

              </div>

              <!-- Pagination -->
              <div class="card-header">
                <span><?php echo $pagination; ?></span>
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
      <form  method="post" enctype="multipart/form-data" id="upload_sample_image">
      <div class="modal-body">
      <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
      <input type="hidden" name="sample_reg_id" id="sample_reg_id">
        <div class="form-group">
            <input type="file" name="sample_image[]" multiple>
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
        <div class="modal-body">
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
    <div class="modal-dialog" role="document">
      <div class="modal-content">
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
            <tr><th>Client</th><td id="client"></td><th>Labs</th><td id="labs"></td></tr>
            <tr><th>Collected Time</th><td id="collect_time"></td><th>Recieved By</th><td id="create_by"></td></tr>
            <tr><th>Sample Recieved Time</th><td id="recieve_time"></td><th>Recieved Quantity</th><td id="qty_recieved"></td></tr>
            <tr><th>Test Specification</th><td id="test_specification"></td><th>Contact</th><td id="contact"></td></tr>
            <tr><th>Product</th><td id="trf_product"></td><td colspan="2"></td></tr>
            <tr><th>Quantity Description</th><td id="qty_desc"></td><th>Retain Sample period</th><td id="retain_sample"></td></tr>
            <tr><th>Sample Deadline</th><td id="sample_deadline"></td><th>Report Deadline</th><td id="report_deadline"></td></tr>
            <tr><th>Sample Description</th><td id="sample_desc"></td><td colspan="2"></td></tr>
            <tr><th>Barcode Number</th><td id="barcode"></td><th>Tat Date</th><td id="tat_date"></td></tr>
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
<!-- Modal for showing and downloading QR Code -->

<!-- Modal for uploading manual report added by millan on 15-01-2021 -->
<div class="modal fade" id="manual_report_pdf_upload" tabindex="-1" role="dialog" aria-labelledby="mrpLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content modal-sm" style="margin: 0px auto;">
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
            <div class="form-group">
              <input type="file" name="manual_report_pdf">
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
<!-- Modal for uploading manual report -->
  <!-- Part list modal ends here -->

  <script>
   $(document).ready(function(){


     $(document).on('click','.generate_performa_btn',function(){
        var id = $(this).attr('data-id');
        $('.sample_id').val(id) 
     })

      $(document).on('click','.generate_performa_invoice',function(){
        const _tokken = $('meta[name="_tokken"]').attr('value');
        var sample_reg_id = $('.sample_id').val(); 
        $.ajax({
          url:"<?php echo base_url('SampleRegistration_Controller/generate_performa_invoice')?>",
          method:"post",
          data:{

            _tokken:_tokken,
            sample_reg_id:sample_reg_id
          },
          success:function(data){
            var data = $.parseJSON(data);
            if(data.status>0){
              $('#generatePerformaInvoice').modal('hide');
              $('.performa_span').html("");
              $.notify(data.msg,'success');

            }
            else{
              $.notify(data.msg,'error');
            }
          }
        })
      })
   })
  </script>
  <!-- Change radio to checkbox for part name -->
  <script>
  $(document).ready(function(){
        $("input[type='button']").click(function(){
            var radioValue = $("input[name='gender']:checked").val();
            if(radioValue){
                alert("Your are a - " + radioValue);
            }
        });
    });
  </script>
