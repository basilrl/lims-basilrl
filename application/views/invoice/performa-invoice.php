<?php
$trf_no = $this->uri->segment(3);
if ($trf_no != "null") {
  $trf_no = base64_decode($trf_no);
} else {
  $trf_no = "";
}
$customer_id = ($this->uri->segment(4)) ? $this->uri->segment(4) : '';
$product_name = ($this->uri->segment(5)) ? $this->uri->segment(5) : '';
$created_on = $this->uri->segment(6);
$buyer_id = ($this->uri->segment(10)) ? $this->uri->segment(10) : '';
$status = ($this->uri->segment(11)) ? $this->uri->segment(11) : '';
$division_id = ($this->uri->segment(12)) ? $this->uri->segment(12) : '';

if ($created_on != "null") {
  $created_on = base64_decode($created_on);
} else {
  $created_on = "";
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

$proforma_number = $this->uri->segment(9);
if ($proforma_number != "null") {
  $proforma_number = base64_decode($proforma_number);
} else {
  $proforma_number = "";
}
/* added by millan on 23-06-2021 */
$sales_person = ($this->uri->segment(13)) ? $this->uri->segment(13) : '';
if ($sales_person != "null") {
  $sales_person = base64_decode($sales_person);
} else {
  $sales_person = 0;
}
/*ends */

/* added by millan on 24-06-2021 */
$client_city = ($this->uri->segment(14)) ? $this->uri->segment(14) : '';
if ($client_city != "null") {
  $client_city = base64_decode($client_city);
} else {
  $client_city = 0;
}
/*ends */

/* added by millan on 29-06-2021 */
$proforma_amt = $this->uri->segment(15);
if ($proforma_amt != "null") {
  $proforma_amt = base64_decode($proforma_amt);
} else {
  $proforma_amt = "";
}

$profroma_made = ($this->uri->segment(16)) ? $this->uri->segment(16) : '';
if ($profroma_made != "null") {
  $profroma_made = base64_decode($profroma_made);
} else {
  $profroma_made = 0;
}
/*ends */

?>
<?php
if ($this->uri->segment(5) == 'DESC')
  $order = 'ASC';
else if ($this->uri->segment(5) == 'ASC')
  $order = 'DESC';
?>
</style>
<script src="<?php echo base_url('assets/js/proforma_invoice.js') ?>"></script>
<script src="<?php echo base_url('assets/js/manualInvoice.js'); ?>"></script>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>PROFORMA INVOICE</h1>
        </div>
        <div class="col-sm-6">
          <!--            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Simple Tables</li>
            </ol>-->
        </div>
      </div>

      <div class="row">
        <div class="col-md-12">
          <!-- <form action="<?php echo base_url('sample-list'); ?>" method="post" autocomplete="off"> -->
          <div class="row">
            <!-- <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>"> -->
            <div class="col-md-3">
              <div class="form-group">
                <!-- updated by millan on 29-06-2021 for GC Number filter -->
                <input type="text" placeholder="Basil Report Number" name="gc_number" class="form-control form-control-sm" id="gc_number" value="<?php echo $gc_number; ?>" style="height: 38px;">
              </div> <!-- updated by millan on 29-06-2021 for GC Number filter -->
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <!-- updated by millan on 29-06-2021 for Proforma Invoice Number filter -->
                <input type="text" placeholder="Proforma Invoice Number" class="form-control form-control-sm" id="proforma_number" name="proforma_number" value="<?php echo $proforma_number; ?>" style="height: 38px;"> <!-- updated by millan on 29-06-2021 for Proforma Invoice Number filter -->
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <!-- updated by millan on 29-06-2021 for TRF Reference Number filter -->
                <input type="text" placeholder="TRF Reference Number" class="form-control form-control-sm" id="trf_reference_number" name="trf_reference_number" value="<?php echo $trf_no; ?>" style="height: 38px;"> <!-- updated by millan on 29-06-2021 for TRF Reference Number filter -->
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
                <select class="select-box" class="form-control form-control-sm" name="status" id="status">
                  <option selected value="">Select Status</option>
                  <?php if (!empty($invoice_status)) {
                    foreach ($invoice_status as $value) { ?>
                      <option value="<?php echo $value['invoice_status_id']; ?>" <?php if ($status == $value['invoice_status_id']) {
                                                                                    echo "selected";
                                                                                  } ?>><?php echo $value['invoice_status_name'] ?></option>
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
                <input type="text" placeholder="Created On" class="form-control form-control-sm" id="created_on" name="created_on" value="<?php echo $created_on; ?>" style="height: 38px;">
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

            <!-- updated by millan on 29-06-2021 for ulr number filter -->
            <div class="col-md-3 mt-1">
              <input type="text" name="ulr_no" placeholder="ULR Number" class="form-control form-control-sm" id="ulr_no" value="<?php echo $ulr_number; ?>" style="height: 38px;">
            </div>
            <!-- updated by millan on 29-06-2021 for ulr number filter -->

            <!-- added by millan on 23-06-2021 for sales_person filter -->
            <div class="col-md-3">
              <div class="form-group">
                <select class="select-box" class="form-control form-control-sm" name="sales_person" id="sales_person">
                  <option selected value="">Select Sales Person</option>
                  <?php if (!empty($salepsn)) {
                    foreach ($salepsn as $value) { ?>
                      <option value="<?php echo $value['uidnr_admin']; ?>" <?php if ($sales_person == $value['uidnr_admin']) {
                                                                              echo "selected";
                                                                            } ?>><?php echo $value['name'] ?></option>
                  <?php }
                  } ?>
                </select>
              </div>
            </div>

            <!-- added by millan on 24-06-2021 for client city filter -->
            <div class="col-md-3">
              <div class="form-group">
                <select class="select-box" class="form-control form-control-sm" name="client_city" id="client_city">
                  <option selected value="">Select City</option>
                  <?php if (!empty($clicity)) {
                    foreach ($clicity as $value) { ?>
                      <option value="<?php echo $value['location_id']; ?>" <?php if ($client_city == $value['location_id']) {
                                                                              echo "selected";
                                                                            } ?>><?php echo $value['location_name'] ?></option>
                  <?php }
                  } ?>
                </select>
              </div>
            </div>
            <!-- ends -->

            <!-- added by millan on 29-06-2021 for proforma amount filter -->
            <div class="col-md-3">
              <div class="form-group">
                <input type="text" placeholder="PROFORMA AMOUNT" name="proforma_amt" class="form-control form-control-sm" id="proforma_amt" value="<?php echo $proforma_amt; ?>" style="height: 38px;">
              </div>
            </div>
            <!-- ends -->

            <!-- added by millan on 29-06-2021 for proforma created by -->
            <div class="col-md-3">
              <div class="form-group">
                <select class="select-box" class="form-control form-control-sm" name="profroma_made" id="profroma_made">
                  <option selected value="">Select Created By</option>
                  <?php if (!empty($prof_per)) {
                    foreach ($prof_per as $value) { ?>
                      <option value="<?php echo $value['uidnr_admin']; ?>" <?php if ($profroma_made == $value['uidnr_admin']) {
                                                                              echo "selected";
                                                                            } ?>><?php echo $value['name'] ?></option>
                  <?php }
                  } ?>
                </select>
              </div>
            </div>
            <!-- ends -->

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

      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Proforma Invoice</h3>
              <!-- added by millan 23-06-2021 for excel export -->
              <span class="ml-5"><a href="<?php echo base_url('Invoice_Controller/sales_person_data'); ?>" title="Export to Excel"> <img src="<?php echo base_url('assets/images/excel-export.png') ?>" alt="Export to Excel" width="30px"> </a> </span>
              <!-- ends -->
            </div>
            <?php //echo "<pre>"; print_r($performa_invoice); 
            ?>
            <!-- /.card-header -->
            <div class="card-body small p-0">
              <table class="table table-hover table-sm">
                <thead>
                  <tr>
                    <th>Sl No.</th>
                    <th>Basil Report No.</th>
                    <th>Proforma Invoice Number</th>
                    <th>Date of Proforma Invoice</th>
                    <th>Quote Ref Number</th>
                    <th>TRF Reference No.</th>
                    <th>Client</th>
                    <th>Buyer</th>
                    <th>Status</th>
                    <!-- added by millan on 29-06-2021 -->
                    <th>Amount</th>
                    <th>Sales Person</th>
                    <th>ERP Status</th>
                    <th>Created By</th>
                    <!-- added by millan on 29-06-2021 -->
                    <th>Comment</th>
                    <?php if (exist_val('Invoice_Controller/view_invoice_details', $this->session->userdata('permission')) || exist_val('Invoice_Controller/download_pdf', $this->session->userdata('permission')) || exist_val('Invoice_Controller/save_test', $this->session->userdata('permission')) || exist_val('Manual_Invoice/Upload_invoice', $this->session->userdata('permission')) || exist_val('Invoice_Controller/accept_reject_proforma_client', $this->session->userdata('permission')) || exist_val('Invoice_Controller/sign_off', $this->session->userdata('permission')) || exist_val('Invoice_Controller/edit_test_price', $this->session->userdata('permission')) || exist_val('Invoice_Controller/revise', $this->session->userdata('permission')) || exist_val('Invoice_Controller/get_invoice_log', $this->session->userdata('permission'))) { ?>
                      <th>Action</th>
                    <?php } ?>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  if ($performa_invoice) {
                    if (empty($this->uri->segment(2))) {
                      $slno = 1;
                    } else {
                      $count = $this->uri->segment(2) - 1;
                      $slno = 10 * $count + 1;
                    }
                    foreach ($performa_invoice as $result) { ?>
                      <tr>
                        <td><?php echo $slno; ?></td>
                        <td><?php echo $result['gc_no']; ?></td>
                        <td><?php echo $result['pro_invoice_number']; ?></td>
                        <td><?php echo $result['pro_invoice_date']; ?></td>
                        <td><?php echo $result['reference_number']; ?></td>
                        <td><?php echo $result['trf_ref_no']; ?></td>
                        <td><?php echo $result['client']; ?></td>
                        <td><?php echo $result['trf_buyer']; ?></td>
                        <td><?php echo $result['status']; ?></td>
                        <!-- added by millan on 29-06-2021 -->
                        <td><?php echo $result['surcharge_amount'] != '' ? number_format(round($result['surcharge_amount'] + $result['price_with_gst'])) : number_format(round($result['total_amount'] + $result['price_with_gst'])); ?></td>
                        <td><?php echo $result['sales_person_name']; ?></td>
                        <td><?php echo $result['tax_status']; ?></td>
                        <td><?php echo $result['proforma_created_by']; ?></td>
                        <!-- added by millan on 29-06-2021 -->
                        <td><?php echo $result['comment']; ?></td>
                        <td>
                          <?php if ($result['sr_status'] != 'Hold Sample') { ?>
                            <?php if (exist_val('Invoice_Controller/view_invoice_details', $this->session->userdata('permission'))) { ?>
                              <!-- added by Millan on 19-02-2021 -->
                              <a href="javascript:void(0)" title="Proforma Details" data-bs-toggle='modal' data-bs-target='#proforma_detail' id='pro_inv_detail' data-one="<?php echo $result['proforma_invoice_id']; ?>" data-two="<?php echo $result['sample_reg_id']; ?>"><img src="<?php echo base_url('assets/images/entity-enabled.png') ?>"></a>
                            <?php } ?>
                            <?php if ($result['tax_status'] != "TAX INVOICE UPDATED") { ?>
                              <?php if (exist_val('Invoice_Controller/download_pdf', $this->session->userdata('permission'))) { ?>
                                <!-- added by Millan on 19-02-2021 -->
                                <?php if (!empty($result['file_path'])) { ?>
                                  <a href="<?php echo base_url('Invoice_Controller/download_pdf/' . $result['proforma_invoice_id']); ?>" title="Download Invoice"><img src="<?php echo base_url('assets/images/download.gif') ?>" width="16px"></a>
                                <?php } ?>
                              <?php } ?>
                              &nbsp;&nbsp;

                              <?php if (exist_val('Invoice_Controller/save_test', $this->session->userdata('permission'))) { ?>
                                <!-- added by Millan on 19-02-2021 -->
                                <?php if ($result['test_price_count'] == 0) { ?>
                                  <a href="javascript:void(0)" title="Add Test & Price" data-bs-toggle='modal' data-bs-target='#test' id='test_add' data-one="<?php echo $result['proforma_invoice_id']; ?>" data-two="<?php echo $result['sample_reg_id']; ?>" data-trf_id="<?php echo $result['trf_id']; ?>" data-trf_quote_id="<?php echo $result['trf_quote_id']; ?>"><img src="<?php echo base_url('assets/images/add.png') ?>"></a>
                                <?php } ?>
                              <?php } ?>

                              <?php //if ($result['status'] == "Proforma Approved") { 
                              ?>
                              <?php if (exist_val('Manual_Invoice/Upload_invoice', $this->session->userdata('permission'))) { ?>
                                <!-- added by Millan on 19-02-2021 -->
                                <a class="manual_invoice" href="javascript:void(0);" title="MANUAL INVOICE UPLOAD" data-bs-toggle='modal' data-bs-target='#manualInvoice' data-performa="<?php echo $result['proforma_invoice_id']; ?>" data-sample="<?php echo $result['sample_reg_id']; ?>"><img src="<?php echo base_url('public/img/icon/manual_invoice.png'); ?>" alt=""></a>
                              <?php } ?>
                              <?php if (exist_val('Invoice_Controller/accept_reject_proforma_client', $this->session->userdata('permission'))) { ?>
                                <!-- added by Millan on 19-02-2021 -->
                                <a href="javascript:void(0)" title="Accept & Reject Proforma Invoice" data-bs-toggle="modal" data-bs-target="#accept_proforma" data-one="<?php echo $result['proforma_invoice_id']; ?>" id="accept_reject"><img src="<?php echo base_url('assets/images/icon/approval.png') ?>" height="16px;" width="16px"></a>
                              <?php } ?>
                              <?php //} 
                              ?>

                              <?php if (exist_val('Invoice_Controller/sign_off', $this->session->userdata('permission'))) { ?>
                                <!-- added by Millan on 19-02-2021 -->
                                <?php if (!empty($result['file_path']) && $result['status'] == "Proforma Generated" || $result['status'] == 'revise') { ?>
                                  <a href="javascript:void(0)" title="Sign Off Invoice" data-bs-toggle='modal' data-bs-target='#signoff' id='sign_off' data-one="<?php echo $result['proforma_invoice_id']; ?>"><img src="<?php echo base_url('assets/images/sign_off.png') ?>"></a>
                                <?php } ?>
                              <?php } ?>

                              <?php if (exist_val('Invoice_Controller/edit_test_price', $this->session->userdata('permission'))) { ?>
                                <!-- added by Millan on 19-02-2021 -->
                                <?php if ($result['status'] == "revise") { ?>
                                  <a href="javascript:void(0)" title="Edit Test & Price" data-bs-toggle='modal' data-bs-target='#price' id='test_edit' data-one="<?php echo $result['proforma_invoice_id']; ?>" data-two="<?php echo $result['sample_reg_id']; ?>" data-trf_id="<?php echo $result['trf_id']; ?>" data-trf_quote_id="<?php echo $result['trf_quote_id']; ?>"><img src="<?php echo base_url('assets/images/icon/edit1.png') ?>" height="16px" width="16px"></a>
                                <?php } ?>
                              <?php } ?>

                              <?php if ($result['status'] == "Proforma Approved") {
                                $revise = 1;
                              } else {
                                $revise = 2;
                              }
                              $keywords = preg_split("/-R./", $result['pro_invoice_number']);
                              ?>

                              <?php if (exist_val('Invoice_Controller/revise', $this->session->userdata('permission'))) { ?>
                                <!-- added by Millan on 19-02-2021 -->
                                <?php if ($result['test_price_count'] > 0 && $result['pi_revise_count'] < 3) { ?>
                                  <a href="javascript:void(0)" title="Revise Proforma Invoice" data-one="<?php echo $result['proforma_invoice_id']; ?>" data-two="<?php echo $revise; ?>" class="revise"><img src="<?php echo base_url('assets/images/arrow_rotate_anticlockwise.png') ?>" height="16px;" width="16px"></a>
                              <?php }
                              } ?>
                            <?php } ?>
                          <?php } ?>
                          <?php if (exist_val('Invoice_Controller/get_invoice_log', $this->session->userdata('permission'))) { ?>
                            <!-- added by Millan on 19-02-2021 -->
                            &nbsp;&nbsp;<a href="javascript:void(0)" title="Invoice Log" data-bs-toggle='modal' data-bs-target='#invoicelog' id='invoice-log' data-one="<?php echo $result['proforma_invoice_id']; ?>"><img src="<?php echo base_url('assets/images/log-view.png') ?>"></a>
                          <?php } ?>
                        </td>

                      </tr>
                  <?php $slno++;
                    }
                  } ?>
                </tbody>
              </table>
            </div>
            <div class="container-fluid">
              <div class="row">
                <div class="col-md-6">
                  <?php echo $result_count; ?>
                </div>
                <div class="col-md-6 card-header">
                  <span id="proforma-pagination"><?php echo $pagination ?></span>
                </div>
              </div>
            </div>
          </div><!-- /.container-fluid -->
          <!-- /.card-body -->
        </div>
        <!-- /.card -->
      </div>
    </div>


  </section>
  <!-- /.content -->
</div>

<!-- Modal boxes -->

<!-- Manual Invoice Upload -->
<div class="modal fade" id="manualInvoice" tabindex="-1" role="dialog" aria-labelledby="invoice_logLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content" style="width: 60%; margin-left: 20%;">
      <form action="javascript:void(0);" class="ManualInvoiceUpload" enctype="multipart/form-data" method="post">
        <div class="modal-header">
          <h5 class="modal-title" id="invoice_logLabel">MANUAL INVOICE UPLOAD</h5>
          <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
          <input type="hidden" value="" name="proforma_invoice_id" class="">
          <input type="hidden" value="" name="sample_reg_id" class="">
          <div class="container-fluid">
            <div class="row">
              <div class="col-sm-6">
                <label for="">
                  <h6>INVOICE NUMBER:</h6>
                </label>
                <h5><span class="report_num"></span></h5>
              </div>
              <div class="col-sm-6">
                <label for="">
                  <h6>Alternate Invoice Number:</h6>
                </label>
                <input type="text" name="al_report_number" class="form-control form-control-sm">
              </div>
            </div>
            <div class="row">
              <div class="col-sm-6">
                <label for="">
                  <h6>Invoice Amount:</h6>
                </label>
                <input type="text" name="Invoice_Amount" class="form-control form-control-sm">
              </div>
              <div class="col-sm-6">
                <label for="">
                  <h6>Report Date:</h6>
                </label>
                <input type="date" name="generated_date" value="<?php echo date('Y-m-d') ?>" class="form-control form-control-sm">
              </div>
            </div>
            <div class="row">
              <div class="col-sm-6">
                <label for="">
                  <h6>Select Amount Status:</h6>
                </label>
                <select name="Payment_Status" id="" class="form-control form-control-sm">
                  <option value="">SELECT PAYMENT STATUS</option>
                  <option value="Paid">Paid</option>
                  <option value="UnPaid">UnPaid</option>
                </select>
              </div>
              <div class="col-sm-6">
                <label for="">
                  <h6>Payment Date:</h6>
                </label>
                <input type="date" name="Payment_Date" class="form-control form-control-sm">
              </div>
            </div>
            <div class="row">
              <div class="col-sm-6">
                <label for="">
                  <h6>Mode Of Payment:</h6>
                </label>
                <input type="text" name="Payment_Mode" class="form-control form-control-sm">
              </div>
              <div class="col-sm-6">
                <label for="">
                  <h6>Upload Manual Invoice:</h6>
                </label>
                <input name="file" type="file" class="form-control">
              </div>
            </div>
            <div class="row">
              <div class="col-sm-12">
                <label for="">
                  <h6>Remarks:</h6>
                </label>
                <textarea class="form-control form-control-sm" name="Remarks" id="" cols="30" rows="10"></textarea>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">SUBMIT</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Accept and reject proforma invoice modal -->
<div class="modal fade" id="accept_proforma" tabindex="-1" role="dialog" aria-labelledby="accept_proformaLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content modal-sm" style="margin: 0 auto;">
      <div class="modal-header">
        <h5 class="modal-title" id="accept_proformaLabel">Accept or Reject Proforma Invoice</h5>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <input type="hidden" class="proforma_invoice_id">
        <div class="form-group">
          <label for="">Comment</label>
          <textarea class="form-control form-control-sm comment"></textarea>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-danger" id="reject">Reject</button>
        <button type="button" class="btn btn-primary" id="accept_invoice">Accept</button>
      </div>
    </div>
  </div>
</div>
<!-- Accept and reject proforma invoice modal ends here -->

<!-- Invoice Sign off modal -->
<div class="modal fade" id="signoff" tabindex="-1" role="dialog" aria-labelledby="sign_offLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="sign_offLabel">Invoice Preview</h5>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <input type="hidden" class="proforma_invoice_id">
        <div id="invoice_preview"></div>
        <div class="row">
          <div class="col-md-12">
            <label for="">Proforma Approval :</label>
            <input type="radio" name="proforma_approval" class="proforma_approval" value="1"> Not Applicable
            <input type="radio" name="proforma_approval" class="proforma_approval" value="2"> Sent email to client for approval
            <input type="radio" name="proforma_approval" class="proforma_approval" value="3"> Already have approval
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-danger" id="reject_invoice">Reject</button>
        <button type="button" class="btn btn-primary" id="approve_invoice">Approve</button>
      </div>
    </div>
  </div>
</div>
<!-- Invoice Sign off modal ends here -->

<!-- Proforma  Invoice Modal -->
<div class="modal fade" id="proforma_detail" tabindex="-1" role="dialog" aria-labelledby="proforma_detailLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content" style="width: 80%; margin-left: 14%;">
      <div class="modal-header">
        <h5 class="modal-title" id="proforma_detailLabel">Proforma Invoice Details</h5>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
          <table class="table table-responsive">
            <tbody>
              <tr>
                <td>Performa Invoice Number : </td>
                <td id="pi_number"></td>
                <td>Email : </td>
                <td id="pi_email"></td>
              </tr>
              <tr>
                <td>Performa Invoice Date : </td>
                <td id="pi_date"></td>
                <td>Customer : </td>
                <td id="pi_customer"></td>
              </tr>
              <tr>
                <td>Client : </td>
                <td id="client"></td>
                <td>Labs : </td>
                <td id="labs"></td>
              </tr>
              <tr>
                <td>Collected Time</td>
                <td id="collected_time"></td>
                <td>Recieved By : </td>
                <td id="recieved_by"></td>
              </tr>
              <tr>
                <td>Sample Recieved Time :</td>
                <td id="sample_recieve_time"></td>
                <td>Recieved Quantity : </td>
                <td id="recieved_quantity"></td>
              </tr>
              <tr>
                <td>Test Specification : </td>
                <td id="test_specification"></td>
                <td>Barcode Number : </td>
                <td id="barcode_number"></td>
              </tr>
              <tr>
                <td>Product : </td>
                <td id="sample_type"></td>
                <td colspan="2"></td>
              </tr>
              <tr>
                <td>Sample Deadline : </td>
                <td id="sample_deadline"></td>
                <td>Report Deadline : </td>
                <td id="report_deadline"></td>
              </tr>
              <tr>
                <td>Sample Description : </td>
                <td id="sample_desc"></td>
                <td colspan="2"></td>
              </tr>
              <tr>
                <td>TAT Date : </td>
                <td id="tat_date"></td>
              </tr>
            </tbody>
          </table>
        </div>
        <div class="row" style="margin-top:10px">
          <div class="col-md-12">
            <h4>Dynamic Price</h4>
          </div>
          <hr>
          <div class="row">
            <table class="table">
              <thead>
                <tr>
                  <th>SL No.</th>
                  <th>Test Name</th>
                  <th>Test Price</th>
                  <!-- <th>Action</th> -->
                </tr>
              </thead>
              <tbody id="test_price_list"></tbody>
            </table>
          </div>
        </div>
        <div class="row" style="margin-top:10px; display:none">
          <div class="col-md-12">
            <h4>Item Details</h4>
          </div>
          <hr>
          <input type="hidden" id="proforma_invoice_id">
          <div class="row">
            <div class="col-md-12">
              <table class="table">
                <thead>
                  <tr>
                    <th>SL No.</th>
                    <!-- <th>GC Number</th> -->
                    <th>Product</th>
                    <th>Sample Description</th>
                    <!-- <th>Action</th> -->
                  </tr>
                </thead>
                <tbody id="invoiceItemStore"></tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <!-- <button type="button" class="btn btn-sm btn-primary" id="download_pdf">Download PDF</button> -->
        <!-- <button type="button" class="btn btn-sm btn-primary" id="template_data_new" data-bs-toggle='modal' data-bs-target='#template'>Sign Off</button> -->
        <!-- <button type="button" class="btn btn-sm btn-primary">Revise</button> -->
        <!-- <button type="button" class="btn btn-sm btn-primary" id="proceed_w_approve">Proceed without Approve</button> -->
        <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<!-- Proforma  Invoice Modal ends here -->

<!-- Add test and price modal -->
<div class="modal fade" id="test" tabindex="-1" role="dialog" aria-labelledby="testLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="testLabel">Add Test & Price</h5>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="<?php echo base_url('save-test'); ?>" method="post" id="save_test">
        <div class="modal-body">
          <div class="row">
            <div class="col-md-12">
              <input id="addRow" type="button" class="btn btn-primary" value="Add Row">
              <!-- <input type="button" class="btn btn-danger del_row" value="Delete Multiple Row"> -->
            </div>

          </div>
          <table class="table">
            <thead>
              <tr>
                <th>Selected Test</th>
                <th>Rate Per Test</th>
                <th>Quantity</th>
                <th>Discount(in %)</th>
                <th>Applicable Charge</th>
                <th>Action</th>
              </tr>
            </thead>
          </table>
          <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
          <input type="hidden" id="sample_reg_id" name="sample_reg_id">
          <input type="hidden" id="sm_proforma_invoice_id" name="proforma_invoice_id">
          <input type="hidden" id="trf_id" name="trf_id">
          <input type="hidden" id="trf_quote_id" name="trf_quote_id">
          <b>
            <p>Test Details :</p>
          </b>
          <table class="table">
            <tbody id="test_table">
            </tbody>
          </table>
          <b>
            <p>Package Details :</p>
          </b>
          <table class="table">
            <tbody id="package_details">
            </tbody>
          </table>
          <b>
            <p>Protocol Details</p>
          </b>
          <table class="table">
            <tbody id="protocol_details">
            </tbody>
          </table>
          <table class="table">
            <tfoot>
              <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td>TOTAL</td>
                <td><input type="text" name="total_amount" value="0" class="total_value form-control form-control-sm" id="total_amount" readonly></td>
                <td></td>
              </tr>
              <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td>Surcharge Percentage(%)</td>
                <td>
                  <small id="warning" style="color:red; display:none;">Max value is 100!</small>
                  <input type="text" name="surcharge" value="0" class="surcharge form-control form-control-sm" id="surcharge" maxlength="5" pattern="\d{1,5}" inputmode="numeric">
                </td>
                <td></td>
              </tr>
              <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td>Total Surcharge</td>
                <td><input type="text" name="surchargeTotal" value="0" class="surchargeTotal form-control form-control-sm" id="surchargeTotal" readonly></td>
                <td></td>
              </tr>
            </tfoot>
          </table>
          <div class="row">
            <div class="col-md-12">
              <input type="radio" value="1" name="show_discount" required> Show Discount
              <input type="radio" value="0" name="show_discount" required checked> Don't Show Discount
            </div>
          </div>
          <!-- Added by Saurabh on 05-08-2021 -->
          <div class="row">
            <div class="col-md-12">
              <div class="form-gorup">
                <label for="Remark">Remark (If any)</label>
                <textarea name="invoice_remark" class="form-control form-comtrol-sm"></textarea>
              </div>
            </div>
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
<!--Modal ends here  -->

<!--Edit test and price modal -->
<div class="modal fade" id="price" tabindex="-1" role="dialog" aria-labelledby="testLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="testLabel">Edit Test & Price</h5>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="<?php echo base_url('Invoice_Controller/update_test_price'); ?>" method="post" id="update_test">
        <div class="modal-body">
          <div class="row">
            <div class="col-md-12">
              <input id="addRow_edit" type="button" class="btn btn-primary" value="Add Row">
              <!-- <input type="button" class="btn btn-danger del_row_edit" value="Delete Multiple Row"> -->
            </div>

          </div>
          <table class="table">
            <thead>
              <tr>
                <th>Test Name</th>
                <th>Rate Per Test</th>
                <th>Quantity</th>
                <th>Discount(in %)</th>
                <th>Applicable Charge</th>
                <th>Action</th>
              </tr>
            </thead>
          </table>
          <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
          <input type="hidden" class="sample_reg_id" name="sample_reg_id">
          <input type="hidden" class="sm_proforma_invoice_id" name="proforma_invoice_id">
          <input type="hidden" class="trf_id" name="trf_id">
          <input type="hidden" class="trf_quote_id" name="trf_quote_id">
          <b>
            <p>Test Details :</p>
          </b>
          <table class="table">
            <tbody id="test_table_price">
            </tbody>
          </table>
          <b>
            <p>Package Details :</p>
          </b>
          <table class="table">
            <tbody id="package_details_price">
            </tbody>
          </table>
          <b>
            <p>Protocol Details</p>
          </b>
          <table class="table">
            <tbody id="protocol_details_price">
            </tbody>
          </table>
          <table class="table">
            <tfoot>
              <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td>TOTAL</td>
                <td><input type="text" name="total_amount" value="0" class="total_value form-control form-control-sm" id="total_amount" readonly></td>
                <td></td>
              </tr>
              <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td>Surcharge Percentage(%)</td>
                <td>
                  <small id="warning1" style="color:red; display:none;">Max value is 100!</small>
                  <input type="text" name="surcharge" value="0" class="surcharge form-control form-control-sm" id="surcharge1" maxlength="5" pattern="\d{1,5}" inputmode="numeric">
                </td>
                <td></td>
              </tr>
              <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td>Total Surcharge</td>
                <td><input type="text" name="surchargeTotal" value="0" class="surchargeTotal form-control form-control-sm" id="surchargeTotal1" readonly></td>
                <td></td>
              </tr>
            </tfoot>
          </table>
          </table>
          <div class="row">
            <div class="col-md-12">
              <input type="radio" value="1" name="show_discount" required> Show Discount
              <input type="radio" value="0" name="show_discount" required checked> Don't Show Discount
            </div>
          </div>
          <!-- Added by Saurabh on 05-08-2021 -->
          <div class="row">
            <div class="col-md-12">
              <div class="form-gorup">
                <label for="Remark">Remark (If any)</label>
                <textarea name="invoice_remark" class="form-control form-comtrol-sm invoice_remark"></textarea>
              </div>
            </div>
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
<!--Modal ends here  -->

<!-- Invoice log Details -->
<div class="modal fade" id="invoicelog" tabindex="-1" role="dialog" aria-labelledby="invoice_logLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content" style="width: 60%; margin-left: 20%;">
      <div class="modal-header">
        <h5 class="modal-title" id="invoice_logLabel">Sample History</h5>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <table class="table table-responsive">
          <thead>
            <tr>
              <th>Name</th>
              <th>Action</th>
              <th>Date</th>
              <th>Current Status</th>
              <th>Previous Status</th>
            </tr>
          </thead>
          <tbody id="invoice_log"></tbody>

        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<!-- Invoice log details ends here -->

<!-- Template modal -->
<div class="modal fade" id="template" tabindex="-1" role="dialog" aria-labelledby="templateLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content" style="width: 50%;margin-left: 27%;">
      <div class="modal-header">
        <h5 class="modal-title" id="templateLabel">Invoice</h5>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
          <input type="hidden" id="invoice_id">
          <div class="col-md-12">
            <label for="">Select Template</label>
            <select id="invoice_template" class="form-control select-box">
              <option disabled selected>Select Template</option>
            </select>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="generate-invoice">Generate Invoice</button>
      </div>
    </div>
  </div>
</div>
<!-- Template modal ends here -->

<script>
  const url = $('body').data('url');
  $(document).on('click', '#filter', function() {
    filter(0);
  });
  // new surcharge
  $(document).ready(function() {
    $('#surcharge').on('keyup', function() {
      const input = $('#surcharge').val();
      if (input.includes('-')) {
      $(this).val(0);
    }
      if (input > 100) {
        $('#warning').css('display', 'block');
        return false;
      } else {
        $('#warning').css('display', 'none');
        let surcharge = $('.surcharge').val();
        let total = $('.total_value').val();
        if (!isNaN(surcharge) && !isNaN(surcharge) && total !== 0) {
          if (surcharge == "") surcharge = 0;
          var result = (surcharge / 100) * total;
          var totalsum = parseFloat(result) + parseFloat(total);
          $('.surchargeTotal').val(totalsum.toFixed(2));
        }
      }
    })
    $('#surcharge1').on('keyup', function() {
      const input = $('#surcharge1').val();
      if (input.includes('-')) {
      $(this).val(0);
    }
      if (input > 100) {
        $('#warning1').css('display', 'block');
        return false;
      } else {
        $('#warning1').css('display', 'none');
        let surcharge = $('#surcharge1').val();
        let total = $('#total_amount').val();
        if (surcharge == "") surcharge = 0;
        let result = (parseFloat(surcharge) / 100) * parseFloat(total);
        let totalsum = parseFloat(result) + parseFloat(total);
        $('#surchargeTotal1').val(totalsum.toFixed(2));
        if(surcharge == 0){
          $('.surcharge').val('');
        }else{
          $('.surcharge').val(surcharge);
        }
        

      }
    })
  })
  // end
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
    $('#proforma_number').val('');
    $('#sales_person').val(''); // added by millan on 23-06-2021
    $('#client_city').val(''); // added by millan on 24-06-2021
    $('#proforma_amt').val(''); // added by millan on 29-06-2021 
    $('#profroma_made').val(''); // added by millan on 29-06-2021
    filter(0);

  });

  $('#proforma-pagination').on('click', 'a', function(e) {
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

    var proforma_number = $('#proforma_number').val();
    if (proforma_number == "") {
      proforma_number = null;
    } else {
      proforma_number = btoa($('#proforma_number').val());
    }

    var buyer = $('#buyer').val();
    if (buyer == "") {
      buyer = null;
    } else {
      buyer = $('#buyer').val();
    }

    var status = $('#status').val();
    if (status == "") {
      status = null;
    } else {
      status = $('#status').val();
    }

    var division = $('#division').val();
    if (division == "") {
      division = null;
    } else {
      division = $('#division').val();
    }

    /* added by millan on 23-06-2021 */
    var sales_person = $('#sales_person').val();
    if (sales_person == "") {
      sales_person = null;
    } else {
      sales_person = btoa($('#sales_person').val());
    }
    /* added by millan on 23-06-2021 */

    /* added by millan on 24-06-2021 */
    var client_city = $('#client_city').val();
    if (client_city == "") {
      client_city = null;
    } else {
      client_city = btoa($('#client_city').val());
    }
    /* added by millan on 24-06-2021 */

    /* added by millan on 29-06-2021 */
    var proforma_amt = $('#proforma_amt').val();
    if (proforma_amt == "") {
      proforma_amt = null;
    } else {
      proforma_amt = btoa($('#proforma_amt').val());
    }

    var profroma_made = $('#profroma_made').val();
    if (profroma_made == "") {
      profroma_made = null;
    } else {
      profroma_made = btoa($('#profroma_made').val());
    }
    /* added by millan on 29-06-2021 */

    window.location.replace(url + 'performa-invoice/' + page + '/' + trf_number + '/' + customer_name + '/' + product + '/' + created_on + '/' + ulr_no + '/' + gc_number + '/' + proforma_number + '/' + buyer + '/' + status + '/' + division + '/' + sales_person + '/' + client_city + '/' + proforma_amt + '/' + profroma_made); // updated by millan on 24-06-2021
  }
</script>

<script>
  const _tokken = $('meta[name="_tokken"]').attr('value');
  $(document).ready(function() {
    $('.revise').click(function() {
      var proforma_invoice_id = $(this).data('one');
      var revise_status = $(this).data('two');
      if (confirm("Are you sure want to revise this proforma invoice")) {
        $.ajax({
          type: 'post',
          url: '<?php echo base_url("revise"); ?>',
          data: {
            _tokken: _tokken,
            proforma_invoice_id: proforma_invoice_id,
            revise_status: revise_status
          },
          dataType: 'json',
          success: function(data) {
            if (data.status > 0) {
              $.notify(data.message, "success");
              window.location.reload();
            } else {
              $.notify(data.message, "error");
            }
          }
        })
      }
    });
  });
  var key_test = 0;
  // Ajax call to set value and check whether it is quote or normal generated
  let colIndex1 = 0;
  // .Changed by saurabh on 05-08-2021 for remark
  $(document).on('click', '#test_edit', function() {
    var proforma_invoice_id = $(this).data('one');
    var sample_reg_id = $(this).data('two');
    var trf_id = $(this).data('trf_id');
    var trf_quote_id = $(this).data('trf_quote_id');
    key_test = 0;
    $('.sample_reg_id').val(sample_reg_id);
    $('.sm_proforma_invoice_id').val(proforma_invoice_id);
    $('.trf_id').val(trf_id);
    $('.trf_quote_id').val(trf_quote_id);

    $.ajax({
      type: 'post',
      url: '<?php echo base_url("Invoice_Controller/get_test_price"); ?>',
      data: {
        _tokken: _tokken,
        sample_reg_id: sample_reg_id,
        proforma_invoice_id: proforma_invoice_id
      },
      success: function(data) {
        var test_data = $.parseJSON(data);
        if (test_data != "") {
          var final_amount = 0;
          if ($.inArray('test_data', test_data)) {
            var html = '<table id="myTable1" cell style="margin-top: 2pc; padding: 9px!important; ">\n\<tbody>';
            $.each(test_data.test_data, function(key, value) {
              var invoice_quote_type = (value.invoice_quote_type != '') ? value.invoice_quote_type : '';
              var invoice_quote_id = (value.invoice_quote_id > 0) ? value.invoice_quote_id : '';
              var invoice_protocol_id = (value.invoice_protocol_id > 0) ? value.invoice_protocol_id : '';
              var invoice_package_id = (value.invoice_package_id > 0) ? value.invoice_package_id : '';
              var invoice_work_id = (value.invoice_work_id > 0) ? value.invoice_work_id : '';
              var applicable_charge = (value.applicable_charge > 0) ? value.applicable_charge : 0;
              html += '<tr id="record1' + key + '">';
              // html += '<td style="padding: 0px !important;"><input type="checkbox" value="' + value.inv_dyn_id + '"></td>';
              html += '<input type="hidden" name="test[' + key_test + '][invoice_quote_type]" value="' + invoice_quote_type + '">';
              html += '<input type="hidden" name="test[' + key_test + '][invoice_quote_id]" value="' + invoice_quote_id + '">';
              html += '<input type="hidden" name="test[' + key_test + '][invoice_protocol_id]" value="' + invoice_protocol_id + '">';
              html += '<input type="hidden" name="test[' + key_test + '][invoice_package_id]" value="' + invoice_package_id + '">';
              html += '<input type="hidden" name="test[' + key_test + '][invoice_work_id]" value="' + invoice_work_id + '">';
              colIndex1++;
              html += '<input type="hidden" name="test[' + key_test + '][invoice_dyn_id]" value="' + value.inv_dyn_id + '" class="inv_dyn_id">';
              html += '<td style="padding: 0px !important;" > <textarea required class="form-control form-control-sm" name="test[' + key_test + '][dynamic_heading]">' + value.dynamic_heading + '</textarea></td>';
              colIndex1++;
              html += '<td style="padding: 0px !important;" > <input  type="number" required class="form-control form-control-sm calc_cal test_rate' + key + '"" step=any name="test[' + key_test + '][dynamic_value]" value="' + value.dynamic_value + '"></td>';
              colIndex1++;
              html += '<td style="padding: 0px !important;" > <input min="1" type="number" required class="form-control form-control-sm calc_qty test_qty' + key + '"" step=any name="test[' + key_test + '][quantity]" value="' + value.quantity + '"></td>';
              colIndex1++;
              html += '<td style="padding: 0px !important;" > <input  type="number" step=any required class="form-control form-control-sm calc_dis test_discount' + key + '"" step=any name="test[' + key_test + '][discount]" value="' + value.discount + '" min="0"  max="100"></td>';
              colIndex1++;
              html += '<td style="padding: 0px !important;" > <input  type="number" required class="form-control form-control-sm total_Row" name="test[' + key_test + '][applicable_charge]" step=any value="' + applicable_charge + '" data-amount="' + applicable_charge + '"  readonly></td>';
              colIndex1++;
              html += '<td class="removeClass_' + colIndex1 + '" style="padding: 0px !important;" ><a  style="margin-left:20px" class="edit_delete_row_pol" href="javascript:void(0);" data-id="' + value.inv_dyn_id + '">X</a></td>';
              colIndex1++;
              html += '</tr >';
              console.log('price' + applicable_charge);
              final_amount = parseFloat(final_amount) + parseFloat(applicable_charge);
              $('.total_value').val(final_amount.toFixed(2));
              colIndex1 = 0;
              key_test++;
            });
            html += '</tbody>';
            html += '</table>';
            $('#test_table_price').html(html);
          }
          if ($.inArray('package', test_data)) {
            var html = '<table id="myTable1" cell style="margin-top: 2pc; padding: 9px!important; ">\n\<tbody>';
            $.each(test_data.package, function(key, value) {
              var invoice_quote_type = (value.invoice_quote_type != "") ? value.invoice_quote_type : '';
              var invoice_quote_id = (value.invoice_quote_id > 0) ? value.invoice_quote_id : '';
              var invoice_protocol_id = (value.invoice_protocol_id > 0) ? value.invoice_protocol_id : '';
              var invoice_package_id = (value.invoice_package_id > 0) ? value.invoice_package_id : '';
              var invoice_work_id = (value.invoice_work_id > 0) ? value.invoice_work_id : '';
              var applicable_charge = (value.applicable_charge > 0) ? value.applicable_charge : 0;
              html += '<tr id="record1' + key + '">';
              // html += '<td style="padding: 0px !important;"><input type="checkbox" value="' + value.inv_dyn_id + '"></td>';
              html += '<input type="hidden" name="test[' + key_test + '][invoice_quote_type]" value="' + invoice_quote_type + '">';
              html += '<input type="hidden" name="test[' + key_test + '][invoice_quote_id]" value="' + invoice_quote_id + '">';
              html += '<input type="hidden" name="test[' + key_test + '][invoice_protocol_id]" value="' + invoice_protocol_id + '">';
              html += '<input type="hidden" name="test[' + key_test + '][invoice_package_id]" value="' + invoice_package_id + '">';
              html += '<input type="hidden" name="test[' + key_test + '][invoice_work_id]" value="' + invoice_work_id + '">';
              colIndex1++;
              html += '<input type="hidden" name="test[' + key_test + '][invoice_dyn_id]" value="' + value.inv_dyn_id + '" class="inv_dyn_id">';
              html += '<td style="padding: 0px !important;" > <textarea required class="form-control form-control-sm" name="test[' + key_test + '][dynamic_heading]">' + value.dynamic_heading + '</textarea></td>';
              colIndex1++;
              if (key == 0) {
                html += '<td style="padding: 0px !important;" > <input  type="number" required class="form-control form-control-sm calc_cal test_rate' + key + '"" step=any name="test[' + key_test + '][dynamic_value]" value="' + value.dynamic_value + '"></td>';
                colIndex1++;
                html += '<td style="padding: 0px !important;" > <input min="1" type="number" required class="form-control form-control-sm calc_qty test_qty' + key + '"" step=any name="test[' + key_test + '][quantity]" value="' + value.quantity + '"></td>';
                colIndex1++;
                html += '<td style="padding: 0px !important;" > <input  type="number" step=any required class="form-control form-control-sm calc_dis test_discount' + key + '"" step=any name="test[' + key_test + '][discount]" value="' + value.discount + '" min="0"  max="100"></td>';
                colIndex1++;
                html += '<td style="padding: 0px !important;" > <input  type="number" required class="form-control form-control-sm total_Row" name="test[' + key_test + '][applicable_charge]" step=any value="' + applicable_charge + '" data-amount="' + applicable_charge + '"  readonly></td>';
                colIndex1++;
                html += '<td class="removeClass_' + colIndex1 + '" style="padding: 0px !important;" ><a  style="margin-left:20px" class="edit_delete_row_package" href="javascript:void(0);" data-id="' + value.inv_dyn_id + '">X</a></td>';
                colIndex1++;
              }
              html += '</tr >';
              final_amount = parseFloat(final_amount) + parseFloat(applicable_charge);

              colIndex1 = 0;
              key_test++;
            });
            html += '</tbody>';
            html += '</table>';
            $('#package_details_price').html(html);
          }
          if ($.inArray('protocol', test_data)) {
            var html = '<table id="myTable1" cell style="margin-top: 2pc; padding: 9px!important; ">\n\<tbody>';
            $.each(test_data.protocol, function(key, value) {
              var invoice_quote_type = (value.invoice_quote_type != "") ? value.invoice_quote_type : '';
              var invoice_quote_id = (value.invoice_quote_id > 0) ? value.invoice_quote_id : '';
              var invoice_protocol_id = (value.invoice_protocol_id > 0) ? value.invoice_protocol_id : '';
              var invoice_package_id = (value.invoice_package_id > 0) ? value.invoice_package_id : '';
              var invoice_work_id = (value.invoice_work_id > 0) ? value.invoice_work_id : '';
              var applicable_charge = (value.applicable_charge > 0) ? value.applicable_charge : 0;
              html += '<tr id="record1' + key + '">';
              // html += '<td style="padding: 0px !important;"><input type="checkbox" value="' + value.inv_dyn_id + '"></td>';
              html += '<input type="hidden" name="test[' + key_test + '][invoice_quote_type]" value="' + invoice_quote_type + '">';
              html += '<input type="hidden" name="test[' + key_test + '][invoice_quote_id]" value="' + invoice_quote_id + '">';
              html += '<input type="hidden" name="test[' + key_test + '][invoice_protocol_id]" value="' + invoice_protocol_id + '">';
              html += '<input type="hidden" name="test[' + key_test + '][invoice_package_id]" value="' + invoice_package_id + '">';
              html += '<input type="hidden" name="test[' + key_test + '][invoice_work_id]" value="' + invoice_work_id + '">';
              colIndex1++;
              html += '<input type="hidden" name="test[' + key_test + '][invoice_dyn_id]" value="' + value.inv_dyn_id + '" class="inv_dyn_id">';
              html += '<td style="padding: 0px !important;" > <textarea required class="form-control form-control-sm" name="test[' + key_test + '][dynamic_heading]">' + value.dynamic_heading + '</textarea></td>';
              colIndex1++;
              if (key == 0) {
                html += '<td style="padding: 0px !important;" > <input  type="number" required class="form-control form-control-sm calc_cal test_rate' + key + '"" step=any name="test[' + key_test + '][dynamic_value]" value="' + value.dynamic_value + '"></td>';
                colIndex1++;
                html += '<td style="padding: 0px !important;" > <input min="1" type="number" required class="form-control form-control-sm calc_qty test_qty' + key + '"" step=any name="test[' + key_test + '][quantity]" value="' + value.quantity + '"></td>';
                colIndex1++;
                html += '<td style="padding: 0px !important;" > <input  type="number" step=any required class="form-control form-control-sm calc_dis test_discount' + key + '"" step=any name="test[' + key_test + '][discount]" value="' + value.discount + '" min="0"  max="100"></td>';
                colIndex1++;
                html += '<td style="padding: 0px !important;" > <input  type="number" required class="form-control form-control-sm total_Row" name="test[' + key_test + '][applicable_charge]" step=any value="' + applicable_charge + '" data-amount="' + applicable_charge + '"  readonly></td>';
                colIndex1++;
                html += '<td class="removeClass_' + colIndex1 + '" style="padding: 0px !important;" ><a  style="margin-left:20px" class="edit_delete_row_pol" href="javascript:void(0);" data-id="' + value.inv_dyn_id + '">X</a></td>';
                colIndex1++;
              }
              html += '</tr >';
              final_amount = parseFloat(final_amount) + parseFloat(applicable_charge);

              colIndex1 = 0;
              key_test++;
            });
            html += '</tbody>';
            html += '</table>';
            $('#protocol_details_price').html(html);
          }
          $('.total_value').val(final_amount.toFixed(2));
          $('.invoice_remark').html(test_data.remark.invoice_remark);
          $('.surcharge').val(test_data.remark.surcharge_percentage); // new surcharge
          $('.surchargeTotal').val(test_data.remark.surcharge_amount); // new surcharge
        }
      }
    });
  });
  let colIndex2 = 0;
  var record1 = 1;
  $(function() {
    $("#addRow_edit").click(function() {
      var startingtableDataIndex2 = key_test;
      var startingcolIndex1 = 0;
      var html = "";
      html += '<tr id="record1' + startingtableDataIndex2 + '">';
      // html += '<td style="padding: 0px !important;"> <input type="checkbox"></td>';
      startingcolIndex1++;
      html += '<input type="hidden" name="test[' + startingtableDataIndex2 + '][invoice_quote_type]" value="">';
      html += '<input type="hidden" name="test[' + startingtableDataIndex2 + '][invoice_quote_id]" value="">';
      html += '<input type="hidden" name="test[' + startingtableDataIndex2 + '][invoice_package_id]" value="0">';
      html += '<input type="hidden" name="test[' + startingtableDataIndex2 + '][invoice_protocol_id]" value="0">';

      html += '<input type="hidden" name="test[' + startingtableDataIndex2 + '][invoice_work_id]" value="">';
      html += '<td style="padding: 0px !important;" ><textarea required class="form-control form-control-sm test_name' + startingtableDataIndex2 + '" name="test[' + startingtableDataIndex2 + '][dynamic_heading]" value=""></textarea></td>';
      startingcolIndex1++
      html += '<td  style="padding: 0px !important;" ><input type="text" step=any required class="form-control form-control-sm calc_cal test_rate' + startingtableDataIndex2 + '" name="test[' + startingtableDataIndex2 + '][dynamic_value]" value="0" /></td>';
      startingcolIndex1++;
      html += '<td  style="padding: 0px !important;" ><input min="1" type="text" step=any required class="form-control form-control-sm calc_qty test_qty' + startingtableDataIndex2 + '" name="test[' + startingtableDataIndex2 + '][quantity]" value="1" /></td>';
      startingcolIndex1++;
      html += '<td  style="padding: 0px !important;" ><input type="text" step=any required class="form-control form-control-sm calc_dis test_discount' + startingtableDataIndex2 + '" name="test[' + startingtableDataIndex2 + '][discount]" value="0" min="0"  max="100"/></td>';
      startingcolIndex1++;
      html += '<td style="padding: 0px !important;" > <input  type="text" step=any required class="form-control form-control-sm total_Row" name="test[' + startingtableDataIndex2 + '][applicable_charge]" value="0" data-amount="0" readonly></td>';
      colIndex2++;
      html += '<td class="removeClass_' + colIndex2 + '" style="padding: 0px !important;" ><a  style="margin-left:20px" class="edit_delete_row_pol" href="javascript:void(0);">X</a></td>';
      colIndex2++;
      html += '<input type="text" name="test[' + startingtableDataIndex2 + '][sample_registration_id]" value="1">';
      html += '</tr>';
      $('#test_table_price').append(html);
      colIndex1 = startingcolIndex1;
      key_test++;
    });
  });

  $('#update_test').submit(function(e) {
    e.preventDefault();
    var form = $(this);
    $('body').append('<div class="pageloader"></div>');
    $.ajax({
      type: 'post',
      url: form.attr('action'),
      data: form.serialize(),
      dataType: 'json',
      success: function(data) {
        if (data.status > 0) {
          $('.pageloader').remove();
          $('#price').modal('hide');
          $.notify(data.message, "success");
          window.location.reload();
        } else {
          $('.pageloader').remove();
          $.notify(data.message, "error");
        }
      }
    });
  });
</script>
<script>
  let rowIndex2 = $('#test_table_price tr').length;
  colIndex2 = 0;
  var record1 = 1;
  // $(function() {
  //   $("#addRow_edit").click(function() {
  //     var startingtableDataIndex2 = key_test;
  //     var startingcolIndex1 = 0;
  //     var html = "";
  //     html += '<tr id="record1' + startingtableDataIndex2 + '">';
  //     html += '<td style="padding: 0px !important;"> <input type="checkbox"></td>';
  //     startingcolIndex1++;
  //     html += '<td style="padding: 0px !important;" ><textarea required class="form-control form-control-sm test_name' + startingtableDataIndex2 + '" name="test[' + startingtableDataIndex2 + '][dynamic_heading]" value=""></textarea></td>';
  //     startingcolIndex1++
  //     html += '<td  style="padding: 0px !important;" ><input type="text" required class="form-control form-control-sm calc_cal test_rate' + startingtableDataIndex2 + '" name="test[' + startingtableDataIndex2 + '][dynamic_value]" value="0" /></td>';
  //     startingcolIndex1++;
  //     html += '<td  style="padding: 0px !important;" ><input type="text" required class="form-control form-control-sm calc_qty test_qty' + startingtableDataIndex2 + '" name="test[' + startingtableDataIndex2 + '][quantity]" value="1" /></td>';
  //     startingcolIndex1++;
  //     html += '<td  style="padding: 0px !important;" ><input type="text" step=any required class="form-control form-control-sm calc_dis test_discount' + startingtableDataIndex2 + '" name="test[' + startingtableDataIndex2 + '][discount]" value="0" /></td>';
  //     startingcolIndex1++;
  //     html += '<td style="padding: 0px !important;" > <input  type="text" required class="form-control form-control-sm total_Row" name="test[' + startingtableDataIndex2 + '][applicable_charge]" value="0" data-amount="0" readonly></td>';
  //     colIndex2++;
  //     html += '<td class="removeClass_' + colIndex2 + '" style="padding: 0px !important;" ><a  style="margin-left:20px" class="edit_delete_row_pol" href="javascript:void(0);">X</a></td>';
  //     colIndex2++;
  //     html += '<input type="text" name="test[' + startingtableDataIndex2 + '][sample_registration_id]" value="1">';
  //     html += '</tr>';
  //     $('#test_table_price').append(html);
  //     colIndex1 = startingcolIndex1;
  //     key_test = key_test+1;
  //   });
  // });

  // $(document).ready(function() {
     $(document).on('keyup', '.calc_cal', function() {
    var self = $(this);
    var number = self.val();
    var number_qty = self.parent().next().children().val();
    var number_discount = self.parent().next().next().children().val();
    var show = self.parent().next().next().next().children();
// new surcharge
    let lengthCount = number.length;
    if (number.includes('-')) {
      $(this).val(0);
    }
    if (number.includes('.')) {
      console.log(number);
      let newnum = number.split('.')[1].length;
      let oldnum = number.split('.')[0];
      if (newnum > 2) {
      let pointData = number.split('.')[1];
        var maxLength = pointData.slice(0, 2);
        var val =  oldnum +'.'+ maxLength ;
          $(this).val(val);
      
      }
    }else{
      if(lengthCount > 5){
      $(this).val(number.slice(0, 5));
    }
    }
   
    // end
    var final_amount = (parseFloat(number * number_qty)) - (parseFloat(((number * number_qty) * number_discount)) / 100);

    show.attr('value', final_amount.toFixed(2));
    show.attr('data-amount', (parseFloat(number * number_qty)) - (parseFloat(((number * number_qty) * number_discount)) / 100));
    cal_total_amt();
  });
  $(document).on('keyup', '.calc_qty', function() {
    var self = $(this);
    var number = self.parent().prev().children().val();
    var number_qty = self.val();
    var number_discount = self.parent().next().children().val();
    var show = self.parent().next().next().children();
    // new surcharge
    let lengthCount = number_qty.length;
    // if(number_qty == 0){
    //   $(this).val();
    //   return false;
    // }
    if (number_qty.includes('-')) {
      $(this).val(1);
      return false;
    }
    if (number_qty.includes('.')) {
      $(this).val(1);
      return false;
    } else{
      if(lengthCount > 3){
      $(this).val(number_qty.slice(0,3));
    }
    } 
    // if (number_qty > 100) {
    //   alert('more than 100 quantity not allowed');
    //   return false;
    // }
    // end
    var final_amount = (parseFloat(number * number_qty)) - (parseFloat(((number * number_qty) * number_discount)) / 100);
    show.attr('value', final_amount.toFixed(2));
    show.attr('data-amount', (parseFloat(number * number_qty)) - (parseFloat(((number * number_qty) * number_discount)) / 100));
    cal_total_amt();
  });

  $(document).on('keyup', '.calc_dis', function() {
    var self = $(this);
    var number = self.parent().prev().prev().children().val();
    var number_qty = self.parent().prev().children().val();
    var number_discount = self.val();
    var show = self.parent().next().children();
    // new surcharge
    if (number_discount > 100) {
      alert('more than 100% discount not allowed');
      return false;
    }
    if (number_discount.includes('-')) {
      $(this).val(0);
    }
    if (number_discount.includes('.')) {
      console.log(number_discount);
      
      let newnum = number_discount.split('.')[1].length;
      let oldnum = number_discount.split('.')[0].length;
      console.log(newnum);
      
      if (newnum > 1) {
        if(oldnum > 1){
          var maxLength = 5;
        }else{
          var maxLength = 4;
        }
        
        var val = number_discount;

          $(this).val(val.slice(0, maxLength));
      
      }
    }


    // end
    var final_amount = (parseFloat(number * number_qty)) - (parseFloat(((number * number_qty) * number_discount)) / 100);
    show.attr('value', final_amount.toFixed(2));
    show.attr('data-amount', (parseFloat(number * number_qty)) - (parseFloat(((number * number_qty) * number_discount)) / 100));
    cal_total_amt();
  });

     function cal_total_amt() {
    cal_total_amt
    var input = $('.total_Row');
    var total = 0;
    let amount = 0;
    input.map(function() {
      amount = $(this).attr('data-amount');
      if (amount == 'null' || amount == null) {
        amount = 0;
      } else {
        amount = amount;
      }
      total += parseFloat(amount);
      // total += parseFloat($(this).attr('data-amount'));
    });
    $('.total_value').attr('value', total.toFixed(2));
    $('.total_value').val(total.toFixed(2));
    // new surcharge
    let surchargepercent = $('.surcharge').val();
    let surchargeamount = $('.surchargeTotal').val();
    if (surchargepercent != '' && surchargeamount != '') {
      var result = (surchargepercent / 100) * total.toFixed(2);
      var totalsum = parseFloat(result) + parseFloat(total.toFixed(2));
      $('.surchargeTotal').val(totalsum.toFixed(2));
    }
    // end
  }

    $(document).on('click', '.edit_delete_row_pol', function() {
      var count = $('#test_table_price tr').length;
      var packagecount = $('#package_details_price tr').length; // new 
      if (packagecount >= 1 || count > 1) {
        $(this).parents('tr').remove();
        // count--;
        var i = 0;
        $('#test_table_price tr').map(function() {
          // console.log(this);
          $(this).attr('id', 'record1' + i);
          i++;
        });
        cal_total_amt();
      } else {
        alert("Please keep atleast one record.");
      }
    });
    // new
    $(document).on('click', '.edit_delete_row_package', function() {
      var count = $('#test_table_price tr').length;
      var packagecount = $('#package_details_price tr').length; // new 
      if (count >= 1 || packagecount > 1) {
        $(this).parents('tr').remove();
        // count--;
        var i = 0;
        $('#test_table_price tr').map(function() {
          // console.log(this);
          $(this).attr('id', 'record1' + i);
          i++;
        });
        cal_total_amt();
      } else {
        alert("Please keep atleast one record.");
      }
    });

    $(document).on('click', '.delete_row_pol', function() {
      var count = $('#test_table tr').length;
      var packagecount = $('#package_details tr').length; // new 
      rowIndex1 = $('#test_table tr').length;
      if (packagecount >= 1 || count > 1) {
        $(this).parents('tr').remove();
        var i = 0;
        $('#test_table tr').map(function() {
          $(this).attr('id', 'record1' + i);
          i++;
        });
        cal_total_amt();
      } else {
        alert("Please keep atleast one record.");
      }
    });
    $(document).on('click', '.delete_row_package', function() {
      var count = $('#test_table tr').length;
      var packagecount = $('#package_details tr').length; // new 
      rowIndex1 = $('#test_table tr').length;
      if (count >= 1 || packagecount > 1 ) {
        $(this).parents('tr').remove();
        var i = 0;
        $('#test_table tr').map(function() {
          $(this).attr('id', 'record1' + i);
          i++;
        });
        cal_total_amt();
      } else {
        alert("Please keep atleast one record.");
      }
    });

    $('.del_row').on('click', function() {
      var count = $('#test_table tr').length;
      if (count > 1) {
        $('td input:checked').closest('tr').remove();
        var i = 0;
        cal_total_amt();
        $('#test_table tr').map(function() {
          $(this).attr('id', 'record1' + i);
          i++;
        });
      } else {
        alert("Please keep atleast one record.");
      }

    });

    $('.del_row_edit').on('click', function() {
      var count = $('#test_table_price tr').length;
      if (count > 1) {
        $('td input:checked').closest('tr').remove();
        var i = 0;
        cal_total_amt();
        $('#test_table tr').map(function() {
          $(this).attr('id', 'record1' + i);
          i++;
        });
      } else {
        alert("Please keep atleast one record.");
      }

    });

  // });
</script>
