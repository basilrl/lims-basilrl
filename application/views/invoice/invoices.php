 <script src="<?php echo base_url('public/js/sweetalert.min.js'); ?>"></script>



 <script>
     function filters(sortColumn = '', cOrder = '') {
         var url = "<?php echo base_url('invoices/index'); ?>";
         var invoice_type_id = $('#invoice_type_filter').val();
         if (invoice_type_id !== '') {
             url = url + '/' + invoice_type_id;
         } else {
             url = url + '/0';
         }
         var customer_name_filter = $('#customer_name_filter').val();
         if (customer_name_filter !== '') {
             url = url + '/' + customer_name_filter;
         } else {
             url = url + '/0';
         }
         var invoice_created_by = $('#invoice_created_by').val();
         if (invoice_created_by !== '') {
             url = url + '/' + invoice_created_by;
         } else {
             url = url + '/0';
         }


         //        if (sortColumn !== '') {
         //            url = url + '/' + sortColumn;
         //        } else {            
         //            url = url + '<?php echo (empty($this->uri->segment(6))) ? '/NULL' : "/" . $this->uri->segment(6); ?>';
         //        }
         //        if (cOrder !== '') {
         //            url = url + '/' + cOrder;
         //        } else {
         //            url = url + '<?php echo (empty($this->uri->segment(7))) ? '/NULL' :  "/" . $this->uri->segment(7); ?>';
         //        }
         window.location.href = url;
     }
     (function() {
         var age = document.getElementById('search');
         age.addEventListener('keypress', function(event) {
             if (event.keyCode === 13) {
                 filterUsers();
             }
         });
     }());
 </script>
 <!-- Content Wrapper. Contains page content -->
 <div class="content-wrapper">
     <!-- Content Header (Page header) -->
     <section class="content-header">
         <div class="container-fluid">
             <div class="row mb-2">
                 <div class="col-sm-6">
                     <h1>INVOICE</h1>
                 </div>

             </div>

             <!--filter starts-->
             <div class="row">
                 <div class="col-sm-2">
                     <select class="form-control form-control-sm" id="invoice_type_filter">
                         <option value="">Invoice Type</option>
                         <option value="1" <?php if ($invoice_type == 1) {
                                                echo "selected";
                                            } ?>>Manual</option>
                         <option value="2" <?php if ($invoice_type == 2) {
                                                echo "selected";
                                            } ?>>Automatic</option>
                         <option value="3" <?php if ($invoice_type == 3) {
                                                echo "selected";
                                            } ?>>BC365 Generated</option>
                     </select>
                 </div>

                 <div class="col-md-3">
                     <select class="select-box" class="form-control form-control-sm" id="customer_name_filter">
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

                 <div class="col-sm-3">
                     <select class="select-box" class="form-control form-control-sm" id="invoice_created_by">
                         <option selected value="">Created By</option>
                         <?php
                            foreach ($users as $value) { ?>

                             <option value="<?php echo $value['uidnr_admin']; ?>" <?php if ($invoice_generatedBy == $value['uidnr_admin']) {
                                                                                        echo "selected";
                                                                                    } ?>><?php echo $value['name']; ?></option>
                         <?php  } ?>

                     </select>
                 </div>

                 <div class="col-sm-2">
                     <div class="input-group">


                         <button onclick="filters()" type="button" class="btn btn-secondary btn-sm">Search</button>
                         <a href="<?php echo base_url('invoices'); ?>" class="btn btn-sm btn-danger">Clear All</a>
                     </div>
                 </div>
             </div>
             <br>

             <!--End filters-->
             <div class="row">
                 <div class="col-12">

                     <div class="card">
                         <div class="card-header">
                             <h3 class="card-title">Invoice</h3>


                         </div>

                         <!-- /.card-header -->
                         <div class="card-body small p-0">
                             <table class="table table-hover table-sm">
                                 <thead>
                                     <tr>
                                         <th>Sl No.</th>
                                         <th>Basil Report No.</th>

                                         <th>Date of Invoice</th>

                                         <th>TRF Reference No.</th>
                                         <th>Client</th>
                                         <th>Buyer</th>
                                         <th>Invoice Type</th>
                                         <th>Taxable Value</th>
                                         <th>Total GST</th>
                                         <th>Subtotal</th>
                                        <th>Status</th>
                                         <th>Created By</th>

                                         <?php if (exist_val('Invoice_Controller/view_invoice_details', $this->session->userdata('permission')) || exist_val('Invoice_Controller/download_pdf', $this->session->userdata('permission')) || exist_val('Invoice_Controller/save_test', $this->session->userdata('permission')) || exist_val('Manual_Invoice/Upload_invoice', $this->session->userdata('permission')) || exist_val('Invoice_Controller/accept_reject_proforma_client', $this->session->userdata('permission')) || exist_val('Invoice_Controller/sign_off', $this->session->userdata('permission')) || exist_val('Invoice_Controller/edit_test_price', $this->session->userdata('permission')) || exist_val('Invoice_Controller/revise', $this->session->userdata('permission')) || exist_val('Invoice_Controller/get_invoice_log', $this->session->userdata('permission'))) { ?>
                                             <th>Action</th>
                                         <?php } ?>
                                     </tr>
                                 </thead>
                                 <tbody>
                                     <?php
                                        if (isset($invoice_list) && !empty($invoice_list)) {
                                            if (empty($this->uri->segment(6))) {
                                                $slno = 1;
                                            } else {
                                                $slno = $this->uri->segment(6) + 1;
                                            }

                                            foreach ($invoice_list as $result) { ?>
                                             <tr>
                                                 <td><?php echo $slno++; ?></td>
                                                 <td title="<?php echo $result['report_num']; ?>"><?php echo strlen($result['report_num']) < 28
                                                                                                        ? $result['report_num']
                                                                                                        : substr($result['report_num'], 0, 26) . "..."; ?></td>

                                                 <td><?php echo $result['generated_date']; ?></td>

                                                 <td><?php echo $result['trf_ref_no']; ?></td>
                                                 <td><?php echo $result['client']; ?> </td>
                                                 <td><?php echo $result['trf_buyer']; ?> </td>
                                                 <td><?php
                                                        if ($result['invoice_type'] == "1") {
                                                            echo "Manual";
                                                        } elseif ($result['invoice_type'] == "2") {
                                                            echo "Automatic";
                                                        } elseif ($result['invoice_type'] == "3") {
                                                            echo "BC365 Generated";
                                                        }
                                                        ?></td>

                                                 <td><?php echo (is_numeric($result['total_amount'])) ? round($result['total_amount'], 2) : '0'; ?></td>
                                                 <td><?php echo is_numeric($result['gst_amount']) ? round($result['gst_amount'], 2) : '0'; ?></td>
                                                 <td><?php echo (is_numeric($result['gst_amount']) && is_numeric($result['total_amount']))
                                                            ? round($result['total_amount'] + $result['gst_amount'], 2) : '0'; ?></td> <!-- ($result['total_amount'] + $result['gst_amount'] )-->
                                                <td><?php echo $result['tax_status'];?></td>
                                                 <td><?php echo $result['created_by']; ?></td>

                                                 <td>
                                                     <?php if ($result['invoice_type'] == 2) { ?>
                                                         <?php if ($result['isApproved'] == 0) { ?>
                                                             <!--//, "<?php echo $result['sample_reg_id']; ?>"-->
                                                             <a href="javascript:void(0)" title="Add/Update Test & Price" onclick='testPrice("<?php echo $result['invoiced_id']; ?>")' data-bs-toggle='modal' data-bs-target='#test' id='test_add'>
                                                                 <img src="<?php echo base_url('assets/images/add.png') ?>">
                                                             </a>

                                                             <a href="javascript:void(0)" title="Send To BC365" onclick="sendDetailsToBC365('<?php echo $result['invoiced_id']; ?>')">
                                                                 <img width="15px;" src="<?php echo base_url('public/img/icon/add_Stock.png'); ?>">
                                                             </a>
                                                         <?php } ?>


                                                     <?php } ?>


                                                     <a class="btn btn-sm" title="View Test Details" data-bs-toggle="modal" data-bs-target="#view_test" onclick="testDetails('<?php echo $result['invoiced_id']; ?>')">
                                                         <img width="20px;" src="<?php echo base_url('public/img/view.png') ?>" alt="view test">
                                                     </a>

                                                     <?php if (!empty($result['invoice_pdf_path'])) { ?>
                                                         <a href="<?php echo $result['invoice_pdf_path']; ?>" download="" target="_blank">
                                                             <img src="<?php echo base_url(); ?>assets/images/view-lab-report-pdf.png" alt="" title="View Invoice"></a>
                                                     <?php } ?>


                                                     <?php // if($result['isUpdatedOnBC365'] == 1){
                                                        ?>
                                                     <!--                            <a href="javascript:void(0)" title="Send For Approval" 
                               onclick='sendForApproval("<?php echo $result['invoiced_id']; ?>", "<?php echo $result['sample_reg_id']; ?>")'
                                           data-bs-toggle='modal' data-bs-target='#sendForApproval' id='sendForApproval'>
                                           <img src="<?php echo base_url('assets/images/send_email.png'); ?>">
                            </a>-->

                                                     <?php // }
                                                        ?>


                                                     <?php if ($result['isApproved'] == 0) { ?>
                                                         <!--                              <a href="<?php echo base_url('invoices/previewPdf?invoice_id=' . $result['invoiced_id']); ?>"
                                 class="btn btn-sm" title="Preview PDF">
                                <img width="20px;" src="<?php echo base_url('public/img/icon/testing-upload.png') ?>" 
                                     alt="view pdf">
                             </a>
                            -->
                                                         <?php // if (!exist_val('Invoices/approveInvoice', $this->session->userdata('permission'))) { 
                                                            ?>
                                                        <?php if($result['tax_status']!="TAX INVOICE UPDATED"){?>
                                                         <a class="btn btn-sm" title="Change Client" data-bs-toggle="modal" data-bs-target="#changeClientModal" onclick="change_client_name('<?php echo $result['invoiced_id']; ?>')">
                                                             <img width="20px;" src="<?php echo base_url('public/img/icon/exchange.png') ?>" alt="Change Client">
                                                         </a>

                                                         <a class="btn btn-sm" title="approve invoice" onclick="approve_invoice('<?php echo $result['invoiced_id']; ?>')">
                                                             <img width="20px;" src="<?php echo base_url('public/img/icon/approval.png') ?>" alt="approve invoice">
                                                         </a>
                                                        <?php }?>

                                                     <?php // }
                                                        }
                                                        ?>

                                                     <?php if ($result['isApproved'] == 1) { ?>


                                                         <a class="btn btn-sm" title="View Payment Details" data-bs-toggle="modal" data-bs-target="#view_payment" onclick="paymentDetails('<?php echo $result['invoiced_id']; ?>')">
                                                             <img src="<?php echo base_url('assets/images/view.png') ?>" alt="view payment">
                                                         </a>
                                                     <?php } ?>

                                                 </td>

                                             </tr>
                                     <?php

                                            }
                                        }
                                        ?>
                                 </tbody>
                             </table>
                         </div>
                         <div class="container-fluid">
                             <div class="row">
                                 <div class="col-md-6">
                                     <b><?php echo isset($result_count) ? "Total : " . $result_count : ''; ?></b>
                                 </div>
                                 <div class="col-md-6 card-header">
                                     <span id="proforma-pagination"><?php echo isset($pagination) ? $pagination : '' ?></span>
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



 <!-- Change Client  modal -->
 <div class="modal fade" id="changeClientModal" tabindex="-1" role="dialog" aria-labelledby="testLabel" aria-hidden="true">
     <div class="modal-dialog modal-sm" role="document">
         <div class="modal-content">
             <div class="modal-header">
                 <h5 class="modal-title text-info" id="testLabel">Change Client</h5>
                 <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                     <span aria-hidden="true">&times;</span>
                 </button>
             </div>
             <div id="change_client_div"></div>
             <div class="modal-footer">

             </div>

         </div>
     </div>
 </div>
 <!--Modal ends here  -->

 <!-- Add test and price modal -->
 <div class="modal fade" id="test" tabindex="-1" role="dialog" aria-labelledby="testLabel" aria-hidden="true">
     <div class="modal-dialog" role="document">
         <div class="modal-content">
             <div class="modal-header">
                 <h5 class="modal-title text-info" id="testLabel">Add/Update Test & Price</h5>
                 <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                     <span aria-hidden="true">&times;</span>
                 </button>
             </div>
             <div id="test_price_div"></div>
             <div class="modal-footer">

             </div>

         </div>
     </div>
 </div>
 <!--Modal ends here  -->

 <!-- invoice payment and price modal 29-07-2021-->
 <div class="modal fade" id="view_payment" tabindex="-1" role="dialog" aria-hidden="true">
     <div class="modal-dialog modal-lg">
         <div class="modal-content">
             <div class="modal-header">
                 <h5 class="modal-title text-info" id="myModalLabel">Payment Details</h5>
                 <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                     <span aria-hidden="true" class="text-danger">&times;</span>
                 </button>
             </div>
             <div class="modal-body" id="payment_details_div"></div>
             <div class="modal-footer">
                 <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
             </div>

         </div>
     </div>
 </div>
 <!--Modal ends here  -->

 <!-- invoice test details modal 06-08-2021-->
 <div class="modal fade" id="view_test" tabindex="-1" role="dialog" aria-hidden="true">
     <div class="modal-dialog modal-lg">
         <div class="modal-content">
             <div class="modal-header">
                 <h5 class="modal-title text-info" id="myModalLabel">Test Details</h5>
                 <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                     <span aria-hidden="true" class="text-danger">&times;</span>
                 </button>
             </div>
             <div class="modal-body" id="test_details_div"></div>
             <div class="modal-footer">
                 <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
             </div>

         </div>
     </div>
 </div>
 <!--Modal ends here  -->

 <script>
     function approve_invoice(invoice_id) {
         var csrf_hash = '<?= $this->security->get_csrf_hash(); ?>';
         swal({
                 title: "Are you sure?",
                 text: "Do you want to approve to invoice?",
                 icon: "warning",
                 buttons: true,
                 dangerMode: true
             })
             .then((release) => {
                 if (release) {
                     $('body').append('<div class="pageloader"></div>');

                     $.ajax({
                         type: "POST",
                         url: "<?php echo base_url('Invoices/approveInvoice'); ?>",
                         data: {
                             invoice_id: invoice_id,
                             _tokken: csrf_hash
                         },
                         success: function(data) {
                             var res = $.parseJSON(data);

                             if (res.error) {
                                 $('.pageloader').remove();
                                 swal(res.error.message, "Something Went Wrong!", "error").then(() => {
                                     location.reload();
                                 });
                             } else {
                                 $('.pageloader').remove();
                                 swal("Invoice Approved!", "success").then(() => {
                                     location.reload();
                                 });

                             }
                         }
                     })
                 } else {

                 }
             });
     }

     function testDetails(invoice_id) {
         $('#test_details_div').empty();
         if (invoice_id !== '') {
             $.ajax({
                 url: "<?php echo base_url('Invoices/invoiceTestDetails'); ?>",
                 data: {
                     invoice_id: invoice_id
                 },
                 method: 'GET',
                 success: function(response) {
                     var data = $.parseJSON(response);
                     $('#test_details_div').empty().append(data);
                 }
             });
         }
     }

     function paymentDetails(invoice_id) {
         $('#payment_details_div').empty();
         if (invoice_id !== '') {
             $.ajax({
                 url: "<?php echo base_url('Invoices/invoicePaymentDetails'); ?>",
                 data: {
                     invoice_id: invoice_id
                 },
                 method: 'GET',
                 success: function(response) {
                     var data = $.parseJSON(response);
                     $('#payment_details_div').empty().append(data);
                 }
             });
         }
     }

     function sendDetailsToBC365(invoice_id) {
         var csrf_hash = '<?= $this->security->get_csrf_hash(); ?>';
         swal({
                 title: "Are you sure?",
                 text: "Do you want to send to business central?",
                 icon: "warning",
                 buttons: true,
                 dangerMode: true
             })
             .then((release) => {
                 if (release) {
                     $('body').append('<div class="pageloader"></div>');
                     //             var  report_data= {invoice_id : invoice_id};

                     $.ajax({
                         type: "post",
                         url: "<?php echo base_url('Invoices/sendToBC365'); ?>",
                         data: {
                             invoice_id: invoice_id,
                             _tokken: csrf_hash
                         },
                         success: function(data) {
                             var res = $.parseJSON(data);
                             console.log(res);
                             if (res.error) {
                                 $('.pageloader').remove();
                                 swal(res.error.message, "Something Went Wrong!", "error").then(() => {
                                     location.reload();
                                 });
                             } else {
                                 $('.pageloader').remove();
                                 swal("Details Updated!", "success").then(() => {
                                     location.reload();
                                 });

                             }
                         }
                     })
                 } else {

                 }
             });
     }


     //23-07-2021
     //    , sample_reg_id
     function testPrice(invoice_id) {
         $('#test_price_div').empty();
         if (invoice_id !== '') {
             $.ajax({
                 url: "<?php echo base_url('Invoices/dynamicTestForm'); ?>",
                 data: {
                     "invoice_id": invoice_id
                 },
                 method: 'GET',
                 success: function(response) {
                     var data = $.parseJSON(response);
                     $('#test_price_div').empty().append(data);
                 }
             });
         }
     }

     function change_client_name(invoice_id) {
         $('#change_client_div').empty();
         if (invoice_id !== '') {
             $.ajax({
                 url: "<?php echo base_url('Invoices/changeClientForm'); ?>",
                 data: {
                     "invoice_id": invoice_id
                 },
                 method: 'GET',
                 success: function(response) {
                     var data = $.parseJSON(response);
                     $('#change_client_div').empty().append(data);
                 }
             });
         }
     }
 </script>