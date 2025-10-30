<?php
$trf_no = $this->uri->segment(4);
if ($trf_no != "null") {
    $trf_no = base64_decode($trf_no);
} else {
    $trf_no = "";
}
$customer_id = ($this->uri->segment(5)) ? $this->uri->segment(5) : '';
$product_name = ($this->uri->segment(6)) ? $this->uri->segment(6) : '';
$buyer_id = ($this->uri->segment(9)) ? $this->uri->segment(9) : '';
$status = ($this->uri->segment(10)) ? base64_decode($this->uri->segment(10)) : '';
$created_on = $this->uri->segment(7);
if ($created_on != "null") {
    $created_on = base64_decode($created_on);
} else {
    $created_on = "";
}

$pi = $this->uri->segment(11);
if ($pi != "null") {
    $pi = base64_decode($pi);
} else {
    $pi = "";
}
$start_date = $this->uri->segment(12);
if ($start_date != "null") {
    $start_date = base64_decode($start_date);
} else {
    $start_date = "";
}

$end_date = $this->uri->segment(13);
if ($end_date != "null") {
    $end_date = base64_decode($end_date);
} else {
    $end_date = "";
}

$due_date = $this->uri->segment(14);
if ($due_date != "null") {
    $due_date = base64_decode($due_date);
} else {
    $due_date = "";
}


$gc_number = $this->uri->segment(8);
if ($gc_number != "null") {
    $gc_number = base64_decode($gc_number);
} else {
    $gc_number = "";
}

?>
<script src="<?php echo base_url('assets/js/sample_registration.js') ?>"></script>
<script src="<?php echo base_url('webcam/webcam.min.js') ?>"></script>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Billing List</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Billing List</li>
                    </ol>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <!-- <form action="<?php echo base_url('billing-list'); ?>" method="post" autocomplete="off"> -->
                    <div class="row">
                        <!-- <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>"> -->
                        <div class="col-md-3">
                            <div class="form-group">
                                <input type="text" placeholder="Basil Report Number" name="gc_number" class="form-control form-control-sm" id="gc_number" value="<?php echo $gc_number; ?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <input type="text" placeholder="TRF Reference Number" class="form-control form-control-sm" id="trf_reference_number" name="trf_reference_number" value="<?php echo $trf_no; ?>">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <input type="text" placeholder="Proforma Invoice Number" class="form-control form-control-sm" id="pi" name="pi" value="<?php echo $pi; ?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <select class="select-box  form-control form-control-sm" name="product" id="product">
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
                                <select class="select-box form-control form-control-sm" name="customer_name" id="customer_name">
                                    <option selected value="">Select Applicant</option>
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
                                <select class="select-box form-control form-control-sm" name="buyer" id="buyer">
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
                                <select class="select-box form-control form-control-sm" name="status" id="status">
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

                        <div class="col-md-3">
                            <div class="form-group">
                                <input type="text" placeholder="Due Date" class="form-control form-control-sm datepicker" id="due_date" name="due_date" value="<?php echo $due_date; ?>">
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
                                <button type="button" class="btn btn-primary btn-sm" id="filter">Search</button>
                                <button type="button" class="btn btn-danger btn-sm" id="reset">Reset</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- </form> -->
            </div>
        </div>
        <div class="card">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-sm table-hover text-center table-bordered mb-0">
                        <thead>
                            <tr class="table-info">
                                <th>S.No.</th>
                                <th>Basil Report Number</th>
                                <th>proforma invoice number</th>
                                <th>Client</th>
                                <th>Sample Description</th>
                                <th>Product</th>
                                <th>TRF Reference No.</th>
                                <th>Quantity</th>
                                <th>Recieved Date</th>
                                <th>Status</th>
                                <th>TAX Status</th>
                                <th>Due Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($billing_list)) {
                                $page = $this->uri->segment(3);
                                $sno = (($page ? $page : '1') - 1) * 10;
                                foreach ($billing_list as $RS) { ?>
                                    <tr>
                                        <td><?php echo $sno += 1; ?></td>
                                        <td><?php echo $RS['gc_no']; ?></td>
                                        <td><?php echo $RS['proforma_invoice_number']; ?></td>
                                        <td><?php echo $RS['client']; ?></td>
                                        <td><?php echo $RS['sample_desc']; ?></td>
                                        <td><?php echo $RS['product_name']; ?></td>
                                        <td><?php echo $RS['trf_ref_no']; ?></td>
                                        <td><?php echo $RS['qty_received']; ?></td>
                                        <td><?php echo change_time($RS['received_date'], $this->session->userdata('timezone')); ?></td>
                                        <td><?php echo $RS['status']; ?></td>
                                        <td><?php echo $RS['tax_status']; ?> <?php echo $RS['invoice_id']; ?></td>
                                        <td><?php echo $RS['due_date']; ?></td>
                                        <td>
                                            <a href="javascript:void(0)" data-id="<?php echo $RS['sample_reg_id']; ?>" class="log_view" data-bs-toggle='modal' data-bs-target='#exampleModal' class="btn btn-sm" title="Log View"><img src="<?php echo base_url('assets/images/log-view.png'); ?>" alt="Log view" width="20px"></a>

                                            <?php if ($RS['status'] == 'Report Approved') { ?>
                                                <a href="javascript:void(0)" data-id="<?php echo  $RS['sample_reg_id']; ?>" data-report_id="<?php echo  $RS['report_id']; ?>" class="download_report" data-bs-toggle='modal' data-bs-target='#download_report' class="btn btn-sm" title="Download report"><img src="<?php echo base_url('assets/images/downloadpdf.png'); ?>" alt="Download report" width="20px"></a>
                                            <?php } ?>

                                            <?php if (!empty($RS['file_path'])) { ?>
                                                <a href="<?php echo base_url('Billing_Controller/download_proforma?pro_id=' . $RS['proforma_invoice_id']); ?>" title="Download Proforma Invoice"><img src="<?php echo base_url('assets/images/download.gif') ?>" width="16px"></a>
                                            <?php } ?>

                                            <?php if (empty($RS['invoiced_id'])) { ?>
                                                <a href="<?php echo base_url('Billing_Controller/Upload_invoice/pro_id/' . $RS['proforma_invoice_id']); ?>" title="MANUAL INVOICE UPLOAD">
                                                    <img src="<?php echo base_url('public/img/icon/manual_invoice.png'); ?>" alt=""></a>
                                            <?php  } ?>

                                            <!-- Added by CHANDAN --30-06-2022 -->
                                            <?php if($RS['tax_status'] != 'TAX INVOICE UPDATED'){ ?>
                                            <button type="button" class="btn btn-sm btn-default generate_invoice" cust_id="<?php echo $RS['customer_id']; ?>" client="<?php echo $RS['client']; ?>" title="GENERATE INVOICE">
                                                <img height="15" src="<?php echo base_url('public/img/icon/arrow_right.png'); ?>">
                                            </button>
                                            <?php }?>
                                            <?php if (($RS['tax_status'] != 'TAX INVOICE UPDATED') && !empty($RS['invoice_id'])) { ?>
                                                <button type="button" class="btn btn-sm btn-default sent_on_erp" cust_id="<?php echo $RS['customer_id']; ?>" inv_id="<?php echo $RS['invoice_id']; ?>" title="SENT ON ERP">
                                                    <img height="15" src="<?php echo base_url('public/img/icon/upload-report1.png'); ?>">
                                                </button>
                                            <?php } ?>

                                            <?php if (($RS['tax_status'] == 'TAX INVOICE UPDATED') && !empty($RS['invoice_pdf_path'])) { ?>
                                                <a href="<?php echo $RS['invoice_pdf_path']; ?>" class="btn btn-sm btn-default" title="Download Invoice">
                                                    <img height="15" src="<?php echo base_url('assets/images/downloadpdf.png'); ?>">
                                                </a>
                                            <?php } ?>
                                            <!-- End -->
                                        </td>
                                    </tr>
                                <?php }
                            } else { ?>
                                <tr>
                                    <td><?php echo "NO RECORD FOUND"; ?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer">
                <div class="row">
                    <div class="col-md-6">
                        <?php echo $result_count; ?>
                    </div>
                    <div class="col-md-6">
                        <span id="sample-pagination"><?php echo $pagination; ?></span>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

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

<!-- // added by harsh rastogi on 31/05/2022 -->

<div class="modal fade" id="download_report" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">download report</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>S.No.</th>
                            <th>Report N.O</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="report_download"></tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="gi_modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Invoice Generated for: <b id="gi_customer_name"></b></h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="gi_form_error" style="min-height: 130px;">
                <form method="post" id="gi_form">
                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                    <input type="hidden" name="customer_id" id="gi_cust_id" />
                    <span id="gi_fetch_gc_number"></span>
                    <div class="text-center mt-2 d-none" id="gi_fetch_gc_number_loader">
                        <span class="spinner-border text-primary" role="status"></span>
                    </div>
                    <div id="gi_fetch_gc_number_tables"></div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-success" id="gi_submit">Generate</button>
            </div>
        </div>
    </div>
</div>

<script>
    const url = $('body').data('url');

    $(document).on('click', '#filter', function() {
        filter(0);
    });

    $(document).on('click', '#reset', function() {
        $('#trf_reference_number').val('');
        $('#customer_name').val('');
        //$('#applicant_name').val('');
        $('#product').val('');
        $('#created_on').val('');
        $('#gc_number').val('');
        $('#buyer').val('');
        $('#status').val('');
        $('#pi').val('');
        $('#start_date').val('');
        $('#end_date').val('');
        $('#due_date').val('');
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

        var pi = $('#pi').val();
        if (pi == "") {
            pi = null;
        } else {
            pi = btoa($('#pi').val());
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

        var due_date = $('#due_date').val();
        if (due_date == "") {
            due_date = null;
        } else {
            due_date = btoa($('#due_date').val());
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

        var status = $('#status').val();
        if (status == "") {
            status = null;
        } else {
            status = btoa($('#status').val());
        }
        window.location.replace(url + 'Billing_Controller/index/' + page + '/' + trf_number + '/' + customer_name + '/' + product + '/' + created_on + '/' + gc_number + '/' + buyer + '/' + status + '/' + pi + '/' + start_date + '/' + end_date + '/' + due_date);
    }

    // Added by CHANDAN --30-06-2022
    function numVal(value) {
        if (isNaN(value)) {
            return 0;
        } else {
            return (value) ? value : 0;
        }
    }

    $(document).on('click', '.generate_invoice', function() {
        let cust_id = $(this).attr('cust_id');
        if (cust_id.length > 0) {
            $('#gi_cust_id').val(cust_id);
            $('#gi_customer_name').text($(this).attr('client'));
            $.ajax({
                url: '<?php echo base_url("Billing_Controller/fetch_all_gc_number"); ?>',
                method: "POST",
                beforeSend: function() {
                    $('#gi_modal').modal('show');
                    $('#gi_fetch_gc_number_tables').html('');
                    $('#gi_fetch_gc_number').html('<h4 class="text-center">Loading data...</h4>').show();
                },
                data: $('#gi_form').serialize(),
                dataType: 'json',
                success: function(data) {
                    let html = '';
                    if (data.length > 0) {
                        html = '<div class="form-group"><label for="gi_proforma_invoice_id">Select Analysis Number</label><select multiple class="form-control" id="gi_proforma_invoice_id" name="gi_proforma_invoice_id[]">';
                        $.each(data, function(key, val) {
                            html += '<option value="' + val.proforma_invoice_id + '">' + val.gc_no + '</option>';
                        });
                        html += '</select></div>';
                    } else {
                        html = '<p class="text-center">No Records found!</p>';
                    }
                    $('#gi_fetch_gc_number').html(html);
                    $('#gi_proforma_invoice_id').select2();
                }
            });
        } else {
            alert('Client ID is mandatory!')
        }
    });

    $(document).on('change', '#gi_proforma_invoice_id', function() {
        let proforma_invoice_id = $(this).val();
        if (proforma_invoice_id.length > 0) {
            $.ajax({
                url: '<?php echo base_url("Billing_Controller/fetch_parameters_details"); ?>',
                method: "POST",
                beforeSend: function() {
                    $('#gi_fetch_gc_number_tables').html('');
                    $('#gi_fetch_gc_number_loader').removeClass('d-none');
                },
                data: {
                    proforma_invoice_id: proforma_invoice_id,
                    _tokken: $('meta[name="_tokken"]').attr("value")
                },
                dataType: 'html',
                success: function(data) {
                    $('#gi_fetch_gc_number_tables').html(data);
                    $('#gi_fetch_gc_number_loader').addClass('d-none');
                }
            });
        }
    });

    $(document).on('click', '#add_btn_parameters', function() {
        let sl_no_counter = parseInt($('.sl_no_counter').length) + 1;

        let html = '<tr class="sl_no_counter" id="delRow_' + sl_no_counter + '">';
        html += '<input type="hidden" class="form-control form-control-sm" name="sample_reg_id[]" value="" />';
        html += '<input type="hidden" class="form-control form-control-sm" name="proforma_id[]" value="" />';
        html += '<input type="hidden" class="form-control form-control-sm" name="invoice_type[]" value="" />';
        html += '<td><input type="text" class="form-control form-control-sm isEmptyParameter" name="parameter_name[]" required /></td>';
        html += '<td><input type="text" class="form-control form-control-sm text-center calPrice" name="rate[]" id="test_rate_GC_' + sl_no_counter + '" /></td>';
        html += '<td><input type="text" class="form-control form-control-sm text-center calPrice" name="quantity[]" id="test_qty_GC_' + sl_no_counter + '" /></td>';
        html += '<td><input type="text" class="form-control form-control-sm text-center calPrice" name="discount[]" id="test_dis_GC_' + sl_no_counter + '" /></td>';
        html += '<td><input type="text" class="form-control form-control-sm text-center" name="price[]" id="test_price_GC_' + sl_no_counter + '" readonly /></td>';
        html += '<td class="text-center"><button type="button" class="btn btn-sm btn-danger delRow" del_id="' + sl_no_counter + '">X</button></td>';
        html += '</tr>';

        $('#add_row_parameters').append(html);
    });

    $(document).on('click', '.delRow', function() {
        let id = $(this).attr('del_id');
        if (id.length > 0) {
            $('#delRow_' + id).remove();
        }
    });

    $(document).on('keyup', '.calPrice', function() {
        let name = $(this).attr('id').split('_')['0'];
        let field = $(this).attr('id').split('_')['1'];
        let gc_no = $(this).attr('id').split('_')['2'];
        let ids = $(this).attr('id').split('_')['3'];
        if (name.length > 0 && field.length > 0 && gc_no.length > 0) {
            let rate = numVal($('#' + name + '_rate_' + gc_no + '_' + ids).val());
            let qty = numVal($('#' + name + '_qty_' + gc_no + '_' + ids).val());
            let dis = numVal($('#' + name + '_dis_' + gc_no + '_' + ids).val());
            let total = numVal(parseFloat(rate) * parseFloat(qty));
            let total_price = numVal(parseFloat(total) - (parseFloat(total) * (parseFloat(dis) / 100)));
            $('#' + name + '_price_' + gc_no + '_' + ids).val(numVal(parseFloat(total_price).toFixed(2)));
        }
    });

    $(document).on('click', '#gi_submit', function() {
        var isValid = false;
        let cust_id = $('#gi_cust_id').val();
        let gi_proforma = $.trim($('#gi_proforma_invoice_id').val());
        let isEmptyParameter = parseInt($(".isEmptyParameter").length);

        if (isEmptyParameter == 0) {
            $('#gi_form_error').notify('At least one Parameter is required!', {
                position: 'top center'
            });
            return false;
        }
        if (gi_proforma.length == 0) {
            $('#gi_form_error').notify('At least one Analysis Number is required!', {
                position: 'top center'
            });
            return false;
        }
        $(".isEmptyParameter").each(function() {
            if (!$(this).val()) {
                isValid = false;
                $('#gi_form_error').notify('All Parameters fields are mandatory!', {
                    position: 'top center'
                });
                return false;
            } else {
                isValid = true;
            }
        });
        if (isValid && (cust_id.length > 0)) {
            $.ajax({
                url: '<?php echo base_url("Billing_Controller/process_generate_invoice"); ?>',
                method: "POST",
                beforeSend: function() {
                    $('body').append('<div id="pageloader" class="pageloader"></div>');
                },
                data: $('#gi_form').serialize(),
                dataType: 'json',
                success: function(data) {
                    $('#pageloader').removeClass('pageloader');
                    if (data.code == 1) {
                        $.notify(data.message, "success");
                        window.location.reload();
                    } else {
                        $.notify(data.message, "error");
                    }
                }
            });
        }
    });

    $(document).on('click', '.sent_on_erp', function() {
        let cust_id = $(this).attr('cust_id');
        let inv_id = $(this).attr('inv_id');
        if (cust_id.length > 0 && inv_id.length > 0) {
            let cnf = confirm("Are you want to Send on ERP?");
            if (cnf == true) {
                $.ajax({
                    url: '<?php echo base_url("Billing_Controller/sent_on_erp"); ?>',
                    method: "POST",
                    beforeSend: function() {
                        $('body').append('<div id="pageloader" class="pageloader"></div>');
                    },
                    data: {
                        _tokken: $('meta[name="_tokken"]').attr("value"),
                        cust_id: cust_id,
                        inv_id: inv_id
                    },
                    dataType: 'json',
                    success: function(data) {
                        $('#pageloader').removeClass('pageloader');
                        if (data.code == 1) {
                            $.notify(data.message, "success");
                            window.location.reload();
                        } else {
                            $.notify(data.message, "error");
                        }
                    }
                });
            }
        } else {
            alert('Customer ID and Invoice ID is manadatory!');
        }
    });
    // End...
</script>

<script>
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

<!-- added by harsh on 01-06-2022 -->
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
                url: url + 'Billing_Controller/get_billing_log',
                data: {
                    _tokken: _tokken,
                    sample_id: sample_id
                },
                success: function(data) {
                    var data = $.parseJSON(data);
                    var value = '';
                    sno = Number();
                    $.each(data, function(i, v) {
                        sno += 1;
                        var operation = v.operation;
                        var old_status = v.old_status;
                        var new_status = v.new_status;
                        var taken_by = v.taken_by;
                        var taken_at = v.taken_at;
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



    $(document).ready(function() {
        const url = $('body').data('url');
        const _tokken = $('meta[name="_tokken"]').attr('value');
        // Ajax call to download report
        $('.download_report').click(function() {
            $('#report_download').empty();
            var sample_id = $(this).data('id');
            $.ajax({
                type: 'post',
                url: url + 'Billing_Controller/download_report',
                data: {
                    _tokken: _tokken,
                    sample_id: sample_id

                },
                success: function(data) {
                    var data = $.parseJSON(data);
                    var value = '';
                    sno = Number();
                    $.each(data, function(i, v) {
                        sno += 1;
                        var report_num = v.report_num;
                        var manual_report_file = v.manual_report_file;
                        var sample_reg_id = v.sample_reg_id;
                        var report_id = v.report_id;
                        value += '<tr>';
                        value += '<td>' + sno + '</td>';
                        value += '<td>' + report_num + '</td>';
                        value += '<td><a href=' + url + 'Billing_Controller/download_report_pdf/?sample_id=' + sample_id + '&report_id=' + report_id + ' target="_blank">Download</a></td>';
                        value += '</tr>';

                    });


                    $('#report_download').append(value);
                }
            });
        });
        // ajax call to download report ends here
    });
</script>
<!-- added by saurabh on 23-03-2021 -->