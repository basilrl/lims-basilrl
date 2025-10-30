$(document).ready(function() {
    const url = $('body').data('url');
    const _tokken = $('meta[name="_tokken"]').attr('value');

    // Ajax call to get proforma invoice details
    $(document).on('click', '#pro_inv_detail', function() {
        $('#invoiceItemStore').empty();
        var proforma_invoice_id = $(this).data('one');
        var sample_reg_id = $(this).data('two');
        $.ajax({
            type: 'post',
            url: url + 'proforma-invoice-details',
            data: { _tokken: _tokken, proforma_invoice_id: proforma_invoice_id, sample_reg_id: sample_reg_id, },
            dataType: 'json',
            success: function(data) {
                var invoiceItemStore = data.invoiceItemStore;
                var loadProformaInvoice = data.loadProformaInvoice;
                var sample_result_data = data.sample_result;
                var dynamic_test = data.dynamic_price;
                $('#pi_number').html(loadProformaInvoice.proforma_invoice_number);
                $('#proforma_invoice_id').val(loadProformaInvoice.proforma_invoice_id);
                $('#pi_email').html(loadProformaInvoice.proforma_client_email);
                $('#pi_date').html(loadProformaInvoice.proforma_invoice_date);
                $('#pi_customer').html(loadProformaInvoice.customer_name);
                $('#sample_detail_viewLabel').html("Sample -" + sample_result_data.gc_num);
                $('#client').html(sample_result_data.client);
                $('#labs').html(sample_result_data.conducted_lab);
                $('#collected_time').html(sample_result_data.dt_time);
                $('#recieved_by').html(sample_result_data.create_by);
                $('#sample_recieve_time').html(sample_result_data.sample_received_date);
                $('#recieved_quantity').html(sample_result_data.qty_rec);
                $('#test_specification').html(sample_result_data.test_specification);
                $('#barcode_number').html(sample_result_data.barcode_no);
                $('#sample_type').html(invoiceItemStore.sample_type);
                $('#sample_deadline').html(sample_result_data.dead_line);
                $('#report_deadline').html(sample_result_data.report_deadline);
                $('#sample_desc').html(sample_result_data.sample);
                $('#tat_date').html(invoiceItemStore.due_date);
                var test_price_count = invoiceItemStore.test_price_count;
                if (test_price_count > 0) {
                    $('#test_price_list').empty();
                    $('#download_pdf').css('display', 'block');
                    $('#template_data_new').css('display', 'block');
                    sno = 0;
                    for (index in dynamic_test) {
                        sno += 1;
                        record = "<tr>";
                        record += "<td>" + sno + "</td>";
                        record += "<td>" + dynamic_test[index].dynamic_heading + "</td>";
                        record += "<td>" + dynamic_test[index].dynamic_value + "</td>";
                        // record += "<td><a href='javascript:void(0)' title='Edit Test & Price' data-toggle='modal' data-target='#test_edit' id='test_edit' data-one='" + dynamic_test[index].inv_dyn_id + "'><img src='" + url + "assets/images/icon/edit.png'></a></td>";
                        record += "</tr>";
                        $('#test_price_list').append(record);
                    }
                } else {
                    $('#test_price_list').empty();
                    $('#download_pdf').css('display', 'none');
                    $('#template_data_new').css('display', 'none');
                    record = "<tr>";
                    record += "<td colspan='4'> No Data Available</td>";
                    record += "</tr>";
                    $('#test_price_list').append(record);
                }

                // $.each(invoiceItemStore, function(key, value) {
                tests = "<tr>";
                tests += "<td>" + 1 + "</td>";
                // tests += "<td>" + value.gc_no + "</td>";
                tests += "<td>" + invoiceItemStore.test_name + "</td>";
                tests += "<td>" + invoiceItemStore.sample_desc + "</td>";
                // tests += "<td><a href='javascript:void(0)' id='sample_details_view' data-toggle='modal' data-target='#sample_detail_view' data-one='" + invoiceItemStore.sample_reg_id + "' data-two='" + loadProformaInvoice.proforma_invoice_id + "'><img src='" + url + "assets/images/view_jobs_in_panel.png'></a></td>";
                tests += "</tr>";
                // });
                $('#invoiceItemStore').append(tests);

            }
        })
    });
    // Ajax call ends here

    // Ajax call to generate invoice
    $(document).on('click', '#generate-invoice', function() {
        var template_id = $('#invoice_template').val();
        var proforma_invoice_id = $('#invoice_id').val();
        $.ajax({
            type: 'post',
            url: url + 'report-view',
            data: { _tokken: _tokken, proforma_invoice_id: proforma_invoice_id, template_id: template_id },
            dataType: 'json',
            success: function(data) {

            }
        })
    });
    // Ajax call ends here

    // Ajax call to get Invoice Log
    $(document).on('click', '#invoice-log', function() {
        var proforma_invoice_id = $(this).data('one');
        $.ajax({
            type: 'post',
            url: url + 'invoice-log',
            data: { _tokken: _tokken, proforma_invoice_id: proforma_invoice_id },
            success: function(data) {
                $('#invoice_log').empty();
                var data = $.parseJSON(data);
                $.each(data, function(i, v) {
                    value = "<tr>";
                    value += "<td>" + v.taken_by + "</td>";
                    value += "<td>" + v.action_message + "</td>";
                    value += "<td>" + v.taken_at + "</td>";
                    value += "<td>" + v.new_status + "</td>";
                    value += "<td>" + v.old_status + "</td>";
                    value += "</tr>";
                    $('#invoice_log').append(value);
                });
            }
        })
    });
    // Ajax call ends here

    // Save test for invoice
    $('#save_test').submit(function(e) {
        e.preventDefault();
        $('body').append('<div class="pageloader"></div>');
        var form = $(this);
        $.ajax({
            type: 'post',
            url: form.attr('action'),
            data: form.serialize(),
            dataType: 'json',
            success: function(data) {
                $('.pageloader').remove();
                if (data.status > 0) {
                    $('#test').modal('hide');
                    $.notify(data.message, "success");
                    $('.calc_cal').css('text-align', 'center');
                    // .css('text-align','center');
                    window.location.reload();
                } else {
                    $.notify(data.message, "error");
                }
            }
        });
    });
    // Ajax call ends here
    var row_seq = 0;
    // Ajax call to set value and check whether it is quote or normal generated
    $(document).on('click', '#test_add', function() {
        $('body').append('<div class="pageloader"></div>');
        var proforma_invoice_id = $(this).data('one');
        var sample_reg_id = $(this).data('two');
        var trf_id = $(this).data('trf_id');
        var trf_quote_id = $(this).data('trf_quote_id');
        $('#sample_reg_id').val(sample_reg_id);
        $('#sm_proforma_invoice_id').val(proforma_invoice_id);
        $('#trf_id').val(trf_id);
        $('#trf_quote_id').val(trf_quote_id);
        $.ajax({
            type: 'post',
            url: url + 'check-trf-type',
            data: { _tokken: _tokken, sample_reg_id: sample_reg_id },
            success: function(data) {
                $('.pageloader').remove();
                if (test_data != "") {
                    let rowIndex1 = 0;
                    var test_data = $.parseJSON(data);
                    var final_amount = 0;
                    if ($.inArray('package', test_data)) {
                        var html = '<table id="myTable1" cell style="margin-top: 2pc; padding: 9px!important; ">\n\<tbody>';
                        $.each(test_data.package, function(key1, value) {
                            html += '<tr id="record1' + key1 + '">';
                            // html += '<td style="padding: 0px !important;"><input type="checkbox"></td>';
                            html += '<input type="hidden" name="test[' + row_seq + '][invoice_quote_type]" value="' + value.sample_test_quote_type + '">';
                            html += '<input type="hidden" name="test[' + row_seq + '][invoice_quote_id]" value="' + value.sample_test_quote_id + '">';
                            html += '<input type="hidden" name="test[' + row_seq + '][invoice_package_id]" value="' + value.sample_test_package_id + '">';
                            html += '<input type="hidden" name="test[' + row_seq + '][invoice_work_id]" value="' + value.sample_test_work_id + '">';
                            colIndex1++;
                            html += '<td style="padding: 0px !important;" > <textarea required class="form-control form-control-sm test_name' + key1 + '" name="test[' + row_seq + '][dynamic_heading]">' + value.test_name + '</textarea></td>';
                            colIndex1++;
                            html += '<td style="padding: 0px !important;" > <input  type="number" step=any required class="form-control form-control-sm calc_cal" name="test[' + row_seq + '][dynamic_value]" value="' + value.price + '"></td>';
                            colIndex1++;
                            html += '<td style="padding: 0px !important;" > <input  min="1" type="number" required class="form-control form-control-sm calc_qty" name="test[' + row_seq + '][quantity]" value="1"></td>';
                            colIndex1++;
                            html += '<td style="padding: 0px !important;" > <input min="0" max="100" type="number" required class="form-control form-control-sm calc_dis" step=any name="test[' + row_seq + '][discount]" value="0" ></td>';
                            colIndex1++;
                            html += '<td style="padding: 0px !important;" > <input  type="number" required class="form-control form-control-sm total_Row" step=any name="test[' + row_seq + '][applicable_charge]" value="' + value.price + '" data-amount="' + value.price + '" readonly></td>';
                            colIndex1++;
                            html += '<td class="removeClass_' + colIndex1 + '" style="padding: 0px !important;" ><a  style="margin-left:20px" class="delete_row_package" href="javascript:void(0);">X</a></td>';
                            colIndex1++;
                            final_amount = parseInt(final_amount) + parseInt(value.price);

                            html += '</tr >';


                            colIndex1 = 0;
                            row_seq++;
                        });
                        html += '</tbody>';
                        html += '</table>';
                        $('#package_details').html(html);
                    }
                    if ($.inArray('test_data', test_data)) {
                        var html = '<table id="myTable1" cell style="margin-top: 2pc; padding: 9px!important; ">\n\<tbody>';
                        $.each(test_data.test_data, function(key1, value) {
                            var price = (value.price > 0) ? value.price : 0;
                            var sample_test_quote_type = (value.sample_test_quote_type > 0) ? value.sample_test_quote_type : 0;
                            var sample_test_quote_id = (value.sample_test_quote_id > 0) ? value.sample_test_quote_id : 0;
                            var sample_test_work_id = (value.sample_test_work_id > 0) ? value.sample_test_work_id : 0;
                            html += '<tr id="record1' + key1 + '">';

                            // html += '<td style="padding: 0px !important;"><input type="checkbox"></td>';
                            colIndex1++;
                            html += '<input type="hidden" name="test[' + row_seq + '][invoice_quote_type]" value="' + sample_test_quote_type + '">';
                            html += '<input type="hidden" name="test[' + row_seq + '][invoice_quote_id]" value="' + sample_test_quote_id + '">';
                            html += '<input type="hidden" name="test[' + row_seq + '][invoice_package_id]" value="0">';
                            html += '<input type="hidden" name="test[' + row_seq + '][invoice_protocol_id]" value="0">';

                            html += '<input type="hidden" name="test[' + row_seq + '][invoice_work_id]" value="' + sample_test_work_id + '">';
                            html += '<td style="padding: 0px !important;" > <textarea required class="form-control form-control-sm test_name' + key1 + '" name="test[' + row_seq + '][dynamic_heading]">' + value.test_name + '</textarea></td>';
                            colIndex1++;

                            html += '<td style="padding: 0px !important;" > <input  type="number" step=any required class="form-control form-control-sm calc_cal" name="test[' + row_seq + '][dynamic_value]" value="' + price + '"></td>';
                            colIndex1++;
                            html += '<td style="padding: 0px !important;" > <input min="1"  type="number" required class="form-control form-control-sm calc_qty" name="test[' + row_seq + '][quantity]" value="1"></td>';
                            colIndex1++;
                            html += '<td style="padding: 0px !important;" > <input  type="number" required class="form-control form-control-sm calc_dis" step=any name="test[' + row_seq + '][discount]" value="0" min="0" max="100"></td>';
                            colIndex1++;
                            html += '<td style="padding: 0px !important;" > <input  type="number" required class="form-control form-control-sm total_Row" step=any name="test[' + row_seq + '][applicable_charge]" value="' + price + '" data-amount="' + value.price + '" readonly></td>';
                            html += '<td class="removeClass_' + colIndex1 + '" style="padding: 0px !important;" ><a  style="margin-left:20px" class="delete_row_pol" href="javascript:void(0);">X</a></td>';
                            colIndex1++;
                            colIndex1++;
                            final_amount = parseInt(final_amount) + parseInt(price);

                            html += '</tr >';


                            colIndex1 = 0;
                            row_seq++;
                        });
                        html += '</tbody>';
                        html += '</table>';
                        $('#test_table').html(html);
                    }
                    if ($.inArray('test_data.protocol', test_data)) {
                        var html = '<table id="myTable1" cell style="margin-top: 2pc; padding: 9px!important; ">\n\<tbody>';
                        $.each(test_data.protocol, function(key1, value) {
                            html += '<tr id="record1' + key1 + '">';
                            // html += '<td style="padding: 0px !important;"><input type="checkbox"></td>';
                            html += '<td class="removeClass_' + colIndex1 + '" style="padding: 0px !important;" >&nbsp;</td>';
                            colIndex1++;
                            html += '<input type="hidden" name="test[' + row_seq + '][invoice_quote_type]" value="' + value.sample_test_quote_type + '">';
                            html += '<input type="hidden" name="test[' + row_seq + '][invoice_quote_id]" value="' + value.sample_test_quote_id + '">';
                            html += '<input type="hidden" name="test[' + row_seq + '][invoice_protocol_id]" value="' + value.sample_test_protocol_id + '">';
                            html += '<input type="hidden" name="test[' + row_seq + '][invoice_package_id]" value="' + value.sample_test_package_id + '">';
                            html += '<input type="hidden" name="test[' + row_seq + '][invoice_work_id]" value="' + value.sample_test_work_id + '">';
                            html += '<td style="padding: 0px !important;" > <textarea required class="form-control form-control-sm test_name' + key1 + '" name="test[' + row_seq + '][dynamic_heading]">' + value.test_name + '</textarea></td>';
                            colIndex1++;
                            html += '<td style="padding: 0px !important;" > <input  type="number" step=any required class="form-control form-control-sm calc_cal" name="test[' + row_seq + '][dynamic_value]" value="' + value.price + '"></td>';
                            colIndex1++;
                            html += '<td style="padding: 0px !important;" > <input min="1" type="number" required class="form-control form-control-sm calc_qty" name="test[' + row_seq + '][quantity]" value="1"></td>';
                            colIndex1++;
                            html += '<td style="padding: 0px !important;" > <input  type="number" required class="form-control form-control-sm calc_dis" step=any name="test[' + row_seq + '][discount]" value="0" min="0"  max="100"></td>';
                            colIndex1++;
                            html += '<td style="padding: 0px !important;" > <input  type="number" required class="form-control form-control-sm total_Row" step=any name="test[' + row_seq + '][applicable_charge]" value="' + value.price + '" data-amount="' + value.price + '" readonly></td>';
                            colIndex1++;
                            html += '<td class="removeClass_' + colIndex1 + '" style="padding: 0px !important;" ><a  style="margin-left:20px" class="delete_row_pol" href="javascript:void(0);">X</a></td>';
                            colIndex1++;
                            final_amount = parseInt(final_amount) + parseInt(value.price);
                            html += '</tr >';
                            colIndex1 = 0;
                            row_seq++;
                        });
                        html += '</tbody>';
                        html += '</table>';
                        $('#protocol_details').html(html);
                    }
                    $('#total_amount').val(final_amount);

                } else {
                    // Show table
                    var html = '<table id="myTable1" cell style="margin-top: 2pc; padding: 9px!important; ">\n\<tbody>';
                    html += '<tr id="record1' + rowIndex1 + '">';
                    html += '<td style="padding: 0px !important;">#</td>';
                    colIndex1++;
                    html += '<td style="padding: 0px !important;" > <textarea required class="form-control form-control-sm" name="test[0][dynamic_heading]" value=""></textarea></td>';
                    colIndex1++;
                    html += '<td style="padding: 0px !important;" > <input  type="number" required class="form-control form-control-sm calc_cal" step=any name="test[0][dynamic_value]" value="0"></td>';
                    colIndex1++;
                    html += '<td style="padding: 0px !important;" > <input min="1" type="number" required class="form-control form-control-sm calc_qty" step=any name="test[0][quantity]" value="1"></td>';
                    colIndex1++;
                    html += '<td style="padding: 0px !important;" > <input  type="number" required class="form-control form-control-sm calc_dis" step=any name="test[0][discount]" value="0" min="0"  max="100"></td>';
                    colIndex1++;
                    html += '<td style="padding: 0px !important;" > <input  type="number" required class="form-control form-control-sm total_Row" name="test[0][applicable_charge]" step=any value="0" data-amount="0" readonly></td>';
                    colIndex1++;
                    html += '<td class="removeClass_' + colIndex1 + '" style="padding: 0px !important;" ></td>';
                    colIndex1++;
                    html += '</tr >';
                    record1++;
                    rowIndex1++;
                    colIndex1 = 0;
                    html += '<tr id="record1' + rowIndex1 + '">';
                    // html += '<td style="padding: 0px !important;"> <input type="checkbox"></td>';
                    html += '<td class="removeClass_' + colIndex1 + '" style="padding: 0px !important;" ><a style="margin-left:20px" class="delete_row_pol" href="javascript:void(0);">X</a>';
                    colIndex1++;
                    html += '<td style="padding: 0px !important;"><textarea required class="form-control form-control-sm" name="test[1][dynamic_heading]" value=""> </textarea>';
                    html += '</td>';
                    colIndex1++;
                    html += '<td style="padding: 0px !important;" > <input  type="number" required class="form-control form-control-sm calc_cal" step=any name="test[1][dynamic_value]" value="0"></td>';
                    colIndex1++;
                    html += '<td style="padding: 0px !important;" > <input min="1"  type="number" required class="form-control form-control-sm calc_qty" step=any name="test[1][quantity]" value="1"></td>';
                    colIndex1++;
                    html += '<td style="padding: 0px !important;" > <input  type="number" required class="form-control form-control-sm calc_dis" step=any name="test[1][discount]" value="0" min="0"  max="100"></td>';
                    colIndex1++;
                    html += '<td style="padding: 0px !important;" > <input  type="number" required class="form-control form-control-sm total_Row" name="test[1][applicable_charge]" step=any value="0" data-amount="0" readonly></td>';
                    colIndex1++;
                    colIndex1++;
                    html += '</td>';
                    html += '</tr>';
                    rowIndex1++;
                    colIndex1 = 0;
                    html += '</tbody>';
                    html += '</table>';
                    $('#test_table').html(html);
                    row_seq = 2;
                }
            }
        });
    });
    // Ajax call ends here
    let colIndex1 = 0;
    $(function() {
        $("#addRow").click(function() {
            var startingtableDataIndex1 = row_seq;
            var startingcolIndex1 = 0;
            var html = "";
            html += '<tr id="record1' + (startingtableDataIndex1) + '">';
          //  html += '<td style="padding: 0px !important;"> <input type="checkbox"></td>';
            startingcolIndex1++;
            html += '<input type="hidden" name="test[' + startingtableDataIndex1 + '][invoice_quote_type]" value="">';
            html += '<input type="hidden" name="test[' + startingtableDataIndex1 + '][invoice_quote_id]" value="">';
            html += '<input type="hidden" name="test[' + startingtableDataIndex1 + '][invoice_package_id]" value="0">';
            html += '<input type="hidden" name="test[' + startingtableDataIndex1 + '][invoice_protocol_id]" value="0">';
            html += '<input type="hidden" name="test[' + startingtableDataIndex1 + '][invoice_work_id]" value="">';
            html += '<td style="padding: 0px !important;" ><textarea required class="form-control form-control-sm test_name' + startingtableDataIndex1 + '" name="test[' + startingtableDataIndex1 + '][dynamic_heading]" value="" ></textarea></td>';
            startingcolIndex1++
            html += '<td  style="padding: 0px !important;" ><input type="text" required class="form-control form-control-sm row_change calc_cal test_rate' + startingtableDataIndex1 + '" step=any name="test[' + startingtableDataIndex1 + '][dynamic_value]" value="0"  /></td>';
            startingcolIndex1++;
            html += '<td  style="padding: 0px !important;" ><input min="1" type="text" required class="form-control form-control-sm row_change calc_qty test_qty' + startingtableDataIndex1 + '" step=any name="test[' + startingtableDataIndex1 + '][quantity]" value="1"  /></td>';
            startingcolIndex1++;
            html += '<td  style="padding: 0px !important;" ><input type="text" required class="form-control form-control-sm row_change calc_dis test_discount' + startingtableDataIndex1 + '" step=any name="test[' + startingtableDataIndex1 + '][discount]" value="0" min="0"  max="100"/></td>';
            startingcolIndex1++;
            html += '<td style="padding: 0px !important;" > <input  type="text" required class="form-control form-control-sm total_Row" name="test[' + startingtableDataIndex1 + '][applicable_charge]" step=any value="0" data-amount="0" readonly></td>';
            colIndex1++;
            html += '<td class="removeClass_' + colIndex1 + '" style="padding: 0px !important;" ><a  style="margin-left:20px" class="delete_row_pol" href="javascript:void(0);">X</a></td>';
            colIndex1++;
            html += '<input type="text" name="test[' + startingtableDataIndex1 + '][sample_registration_id]" value="1">';
            html += '</tr>';

            $('#test_table').append(html);
            colIndex1 = startingcolIndex1;
            row_seq = row_seq + 1;
        });
    });

    // Sign off invoice 
    $(document).on('click', '#sign_off', function() {
        var proforma_invoice_id = $(this).data('one');
        $('.proforma_invoice_id').val(proforma_invoice_id);
        $('#invoice_preview').html('<iframe width="100%" height="450px" src="' + url + 'get-proforma-invoice/' + proforma_invoice_id + '"></frame>')
    });
    // Sign off invoice ends here

    // Approve and reject proforma invoice
    $(document).on('click', '#approve_invoice', function() {
        var proforma_invoice_id = $('.proforma_invoice_id').val();
        var approval_status = $('input[name=proforma_approval]:checked').val();;
        $('body').append('<div class="pageloader"></div>');
        $.ajax({
            type: 'post',
            url: url + 'accept-proforma-invoice',
            data: { _tokken: _tokken, proforma_invoice_id: proforma_invoice_id, approval_status: approval_status },
            dataType: 'json',
            success: function(data) {
                $('.pageloader').remove();
                if (data.status > 0) {
                    $.notify(data.message, "success");
                    $('#signoff').modal('hide');
                    window.location.reload();
                } else {
                    $.notify(data.message, "error");
                }
            }
        });
    });

    $(document).on('click', '#reject_invoice', function() {
        var proforma_invoice_id = $('.proforma_invoice_id').val();
        $('body').append('<div class="pageloader"></div>');
        $.ajax({
            type: 'post',
            url: url + 'reject-proforma-invoice',
            data: { _tokken: _tokken, proforma_invoice_id: proforma_invoice_id },
            dataType: 'json',
            success: function(data) {
                $('.pageloader').remove();
                if (data.status > 0) {
                    $.notify(data.message, "success");
                    $('#signoff').modal('hide');
                    window.location.reload();
                } else {
                    $.notify(data.message, "error");
                }
            }
        });
    });
    // Approve and reject proforma invoice ends here

    // Set proforma invoice id for accept and reject proforma invoice
    $(document).on('click', '#accept_reject', function() {
        var proforma_invoice_id = $(this).data('one');
        $('.proforma_invoice_id').val(proforma_invoice_id);
    });
    // Set proforma invoice id for accept and reject proforma invoice ends here

    // Accept and reject proforma invoice ajax
    $(document).on('click', '#accept_invoice', function() {
        $('body').append('<div class="pageloader"></div>');
        var proforma_invoice_id = $('.proforma_invoice_id').val();
        var comment = $('.comment').val();
        $.ajax({
            type: 'post',
            url: url + 'Invoice_Controller/update_proforma_invoice_status',
            data: { _tokken: _tokken, proforma_invoice_id: proforma_invoice_id, comment: comment, status: 1 },
            dataType: 'json',
            success: function(data) {
                $('.pageloader').remove();
                if (data.status > 0) {
                    $('.comment').val('');
                    $('#accept_proforma').modal('hide');
                    $.notify(data.message, "success");
                    window.location.reload();
                } else {
                    $.notify(data.message, "error");
                }
            }
        })
    });

    $(document).on('click', '#reject', function() {
        $('body').append('<div class="pageloader"></div>');
        var proforma_invoice_id = $('.proforma_invoice_id').val();
        var comment = $('.comment').val();
        $.ajax({
            type: 'post',
            url: url + 'Invoice_Controller/update_proforma_invoice_status',
            data: { _tokken: _tokken, proforma_invoice_id: proforma_invoice_id, comment: comment, status: 2 },
            dataType: 'json',
            success: function(data) {
                $('.pageloader').remove();
                if (data.status > 0) {
                    $('.comment').val('');
                    $('#accept_proforma').modal('hide');
                    $.notify(data.message, "success");
                } else {
                    $.notify(data.message, "error");
                }
            }
        })
    });

});
