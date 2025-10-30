<?php
if ($this->uri->segment(5) == 'DESC')
  $order = 'ASC';
else if ($this->uri->segment(5) == 'ASC')
  $order = 'DESC';
?>
</style>
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
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title"></h3>

              <div class="card-tools">
                <div class="input-group input-group-sm" style="width: 150px;">
                  <input type="text" name="table_search" class="form-control float-right" placeholder="Search">

                  <div class="input-group-append">
                    <button type="submit" class="btn btn-default"><i class="fas fa-search"></i></button>
                  </div>
                </div>
              </div>
            </div>
            <?php
            // echo "<pre>";
            // print_r($performa_invoice);
            ?>
            <!-- /.card-header -->
            <div class="card-body table-responsive p-0">
              <table class="table table-hover text-nowrap table-sm">
                <thead>
                  <tr>
                    <th>Sl No.</th>
                    <th>GC No.</th>
                    <th>Proforma Invoice Number</th>
                    <th>Date of Proforma Invoice</th>
                    <th>Quote Ref Number</th>
                    <th>TRF Reference No.</th>
                    <th>Client</th>
                    <th>Buyer</th>
                    <th>Status</th>
                    <th>Action</th>

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
                        <td><?= $result['gc_no']; ?></td>
                        <td><?= $result['pro_invoice_number']; ?></td>
                        <td><?= $result['pro_invoice_date']; ?></td>
                        <td><?= $result['reference_number']; ?></td>
                        <td><?= $result['trf_ref_no']; ?></td>
                        <td><?= $result['client']; ?></td>
                        <td><?= $result['trf_buyer']; ?></td>
                        <td><?= $result['status']; ?></td>
                        <td><a href="javascript:void(0)" title="Edit" data-bs-toggle='modal' data-bs-target='#proforma_detail' id='pro_inv_detail' data-one="<?= $result['proforma_invoice_id']; ?>" data-two="<?= $result['sample_reg_id']; ?>"><img src="<?php echo base_url('assets/images/entity-enabled.png') ?>"></a>
                          <?php if(!empty($result['file_path'])) {?>
                            <a href="<?php echo base_url('Invoice_Controller/download_pdf/'.$result['proforma_invoice_id']); ?>" title="Download Invoice"><img src="<?php echo base_url('assets/images/download.gif') ?>" width="16px"></a>
                          <?php } ?>
                          &nbsp;&nbsp;<a href="javascript:void(0)" title="User Log" data-bs-toggle='modal' data-bs-target='#invoicelog' id='invoice-log' data-one="<?= $result['proforma_invoice_id']; ?>"><img src="<?php echo base_url('assets/images/log-view.png') ?>"></a>&nbsp;&nbsp;
                          <?php if ($result['test_price_count'] ==  0){?>
                          <a href="javascript:void(0)" title="Add Test & Price" data-bs-toggle='modal' data-bs-target='#test' id='test_add' data-one="<?= $result['proforma_invoice_id']; ?>" data-two="<?= $result['sample_reg_id']; ?>"><img src="<?php echo base_url('assets/images/add.png') ?>"></a>
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
                  <?php echo $pagination ?>
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

<!-- Proforma  Invoice Modal -->
<div class="modal fade" id="proforma_detail" tabindex="-1" role="dialog" aria-labelledby="proforma_detailLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content" style="width: 80%; margin-left: 14%;">
      <div class="modal-header">
        <h5 class="modal-title" id="proforma_detailLabel">Edit Proforma Invoice</h5>
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
                <td id="product"></td>
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
        <div class="row" style="margin-top:10px">
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
                    <th>GC Number</th>
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
        <button type="button" class="btn btn-sm btn-primary" id="download_pdf">Download PDF</button>
        <button type="button" class="btn btn-sm btn-primary" id="template_data_new" data-bs-toggle='modal' data-bs-target='#template'>Sign Off</button>
        <button type="button" class="btn btn-sm btn-primary" id="revise">Revise</button>
        <button type="button" class="btn btn-sm btn-primary" id="proceed_w_approve">Proceed without Approve</button>
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
              <input type="button" class="btn btn-danger" value="Delete Multiple Row">
            </div>

          </div>
          <table class="table">
            <thead>
              <tr>
                <th>#</th>
                <th>Test Name</th>
                <th>Rate Per Test</th>
                <th>Quantity</th>
                <th>Discount(in %)</th>
                <th>Applicable Charge</th>
                <th>Action</th>
              </tr>
            </thead>
            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
            <input type="hidden" id="sample_reg_id" name="sample_reg_id">
            <input type="hidden" id="sm_proforma_invoice_id" name="proforma_invoice_id">
            <tbody id="test_table">
            </tbody>
            <tfoot>
                  <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>TOTAL</td>
                    <td><input type="text" name="total_amount" value="0" class="total_value form-control form-control-sm" id="" readonly></td>
                    <td></td>
                  </tr>
          </tfoot>
          </table>
          <div class="row">
            <div class="col-md-12">
              <input type="radio" value="1" name="show_discount" required checked> Show Discount
              <input type="radio" value="0" name="show_discount" required> Don't Show Discount
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Generate Proforma Invoice</button>
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

<!-- Sample detail view load -->
<!-- <div class="modal fade" id="sample_detail_view" tabindex="-1" role="dialog" aria-labelledby="sample_detail_viewLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="sample_detail_viewLabel"></h5>
          <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
         <table class="table">
          <tbody>
          <tr><td>Client : </td><td id="client"></td><td>Labs : </td><td id="labs"></td></tr>
          <tr><td>Collected Time</td><td id="collected_time"></td><td>Recieved By : </td><td id="recieved_by"></td></tr>
          <tr><td>Sample Recieved Time :</td><td id="sample_recieve_time"></td><td>Recieved Quantity : </td><td id="recieved_quantity"></td></tr>
          <tr><td>Test Specification : </td><td id="test_specification"></td><td>Barcode Number : </td><td id="barcode_number"></td></tr>
          <tr><td>Product : </td><td id="product"></td><td colspan="2"></td></tr>
          <tr><td>Sample Deadline : </td><td id="sample_deadline"></td><td>Report Deadline : </td><td id="report_deadline"></td></tr>
          <tr><td>Sample Description : </td><td id="sample_desc"></td><td colspan="2"></td></tr>
          <tr><td>TAT Date : </td><td id="tat_date"></td></tr>
          </tbody>
         </table>
         <table style="margin-top:10px">
          <thead>
            <tr>
              <th>Test Name</th>
              <th>Test Method</th>
              <th>Test Parameter</th>
              <th>Rate (R) Per Test/ Sample</th>
              <th>Discount(%)</th>
              <th>Applicable Charge</th>
              <th>Service Type</th>
              <th>Parts</th>
              <th>Total Charge</th>
            </tr>
          </thead>
          <tbody id="analysis_test_result"></tbody>
         </table>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary">Save changes</button>
        </div>
      </div>
    </div>
  </div> -->
<!-- Sample detail view load ends here -->

<!-- Add dynamic columns to add test and price -->
<script>
  let rowIndex1 = $('#test_table tr').length;
  let colIndex1 = 0;
  var record1 = 1;
  $(function() {
    $("#addRow").click(function() {
      var startingtableDataIndex1 = rowIndex1 + 1;
      var startingcolIndex1 = 0;
      var html = "";
      html += '<tr id="record1' + startingtableDataIndex1 + '">';
      html += '<td style="padding: 0px !important;"> <input type="checkbox"></td>';
      startingcolIndex1++;
      html += '<td style="padding: 0px !important;" ><textarea required class="form-control form-control-sm test_name' + startingtableDataIndex1 + '" name="test[' + startingtableDataIndex1 + '][dynamic_heading]" value="" data-id="' + startingtableDataIndex1 + '"></textarea></td>';
      startingcolIndex1++
      html += '<td  style="padding: 0px !important;" ><input type="text" required class="form-control form-control-sm row_change test_rate' + startingtableDataIndex1 + '" name="test[' + startingtableDataIndex1 + '][dynamic_value]" value="0" data-id="' + startingtableDataIndex1 + '" /></td>';
      startingcolIndex1++;
      html += '<td  style="padding: 0px !important;" ><input type="text" required class="form-control form-control-sm row_change test_qty' + startingtableDataIndex1 + '" name="test[' + startingtableDataIndex1 + '][quantity]" value="0" data-id="' + startingtableDataIndex1 + '" /></td>';
      startingcolIndex1++;
      html += '<td  style="padding: 0px !important;" ><input type="text" required class="form-control form-control-sm row_change test_discount' + startingtableDataIndex1 + '" name="test[' + startingtableDataIndex1 + '][discount]" value="0" data-id="' + startingtableDataIndex1 + '" /></td>';
      startingcolIndex1++;
      html += '<td style="padding: 0px !important;" > <input  type="text" required class="form-control form-control-sm test_amt' + startingtableDataIndex1 + '" name="test[' + startingtableDataIndex1 + '][applicable_charge]" value="0" data-id="' + startingtableDataIndex1 + '" readonly></td>';
      colIndex1++;
      html += '<td class="removeClass_' + colIndex1 + '" style="padding: 0px !important;" ><a  style="margin-left:20px" class="delete_row_pol" href="javascript:void(0);">X</a></td>';
      colIndex1++;
      html += '<input type="text" name="test[' + startingtableDataIndex1 + '][sample_registration_id]" value="1">';
      html += '</tr>';
     
      $('#test_table').append(html);
      rowIndex1++;
      colIndex1 = startingcolIndex1;

    });

    // Show table

    var html = '<table id="myTable1" cell style="margin-top: 2pc; padding: 9px!important; ">\n\<tbody>';
    html += '<tr id="record1' + rowIndex1 + '">';
    html += '<td style="padding: 0px !important;">#</td>';
    colIndex1++;
    html += '<td style="padding: 0px !important;" > <textarea required class="form-control form-control-sm test_name' + rowIndex1 + '" name="test[0][dynamic_heading]" value="" data-id="' + rowIndex1 + '"></textarea></td>';
    colIndex1++;
    html += '<td style="padding: 0px !important;" > <input  type="text" required class="form-control form-control-sm row_change test_rate' + rowIndex1 + '"" name="test[0][dynamic_value]" value="0" data-id="' + rowIndex1 + '"></td>';
    colIndex1++;
    html += '<td style="padding: 0px !important;" > <input  type="text" required class="form-control form-control-sm row_change test_qty' + rowIndex1 + '"" name="test[0][quantity]" value="0" data-id="' + rowIndex1 + '"></td>';
    colIndex1++;
    html += '<td style="padding: 0px !important;" > <input  type="text" required class="form-control form-control-sm row_change test_discount' + rowIndex1 + '"" name="test[0][discount]" value="0" data-id="' + rowIndex1 + '"></td>';
    colIndex1++;
    html += '<td style="padding: 0px !important;" > <input  type="text" required class="form-control form-control-sm test_amt' + rowIndex1 + '"" name="test[0][applicable_charge]" value="0" data-id="' + rowIndex1 + '" readonly></td>';
    colIndex1++;
    html += '<td class="removeClass_' + colIndex1 + '" style="padding: 0px !important;" ></td>';
    colIndex1++;
    html += '</tr >';
    record1++;
    rowIndex1++;
    colIndex1 = 0;
    html += '<tr id="record1' + rowIndex1 + '">';
    html += '<td style="padding: 0px !important;"> <input type="checkbox"></td>';
    colIndex1++;
    html += '<td style="padding: 0px !important;"><textarea required class="form-control form-control-sm test_name' + rowIndex1 + '"" name="test[1][dynamic_heading]" value="" data-id="' + rowIndex1 + '"> </textarea>';
    html += '</td>';
    colIndex1++;
    html += '<td style="padding: 0px !important;" > <input  type="text" required class="form-control form-control-sm row_change test_rate' + rowIndex1 + '"" name="test[1][dynamic_value]" value="0" data-id="' + rowIndex1 + '"></td>';
    colIndex1++;
    html += '<td style="padding: 0px !important;" > <input  type="text" required class="form-control form-control-sm row_change test_qty' + rowIndex1 + '"" name="test[1][quantity]" value="0" data-id="' + rowIndex1 + '"></td>';
    colIndex1++;
    html += '<td style="padding: 0px !important;" > <input  type="text" required class="form-control form-control-sm row_change test_discount' + rowIndex1 + '"" name="test[1][discount]" value="0" data-id="' + rowIndex1 + '"></td>';
    colIndex1++;
    html += '<td style="padding: 0px !important;" > <input  type="text" required class="form-control form-control-sm test_amt' + rowIndex1 + '"" name="test[1][applicable_charge]" value="0" data-id="' + rowIndex1 + '" readonly></td>';
    colIndex1++;
    html += '<td class="removeClass_' + colIndex1 + '" style="padding: 0px !important;" ><a style="margin-left:20px" class="delete_row_pol" href="javascript:void(0);">X</a>';
    colIndex1++;
    html += '</td>';
    html += '</tr>';
    html += '</tbody>';
    html += '</table>';
    $('#test_table').html(html);

    // Remove row
    $('[type="button"]').on('click', function() {
      $('td input:checked').closest('tr').remove();
    });
    
    $(document).on('keyup', '.row_change', function() {
      count_total_AMOUNT();
    });
    function count_total_AMOUNT(){
    var count = $('#test_table tr').length;
    console.log(count);
      var total = 0;
      for(i=0;i<count;i++){
        var test_rate = $('input.test_rate' + i).val();
          var test_amt = $('input.test_amt' + i);
          var test_discount = $('input.test_discount'+i).val();
          var test_qty =$('input.test_qty'+i).val();
          var discount = (test_rate*test_qty)*((test_discount)/100);
          var applicable_charge = (test_rate*test_qty) - (discount);
          total += parseFloat(applicable_charge)
            test_amt.val(applicable_charge);
            $('.total_value').val(total);
      }
  }

  $(document).ready(function () {
    bsCustomFileInput.init();
    $(document).on('click','.delete_row_pol',function () {
      $(this).parents('tr').remove();
      rowIndex1--;
      count_total_AMOUNT();
    });
  });

  });
 
</script>
<!-- Add dynamic columns to add test and price ends here-->