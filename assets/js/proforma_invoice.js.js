$(document).ready(function() {
    const url = $('body').data('url');
    const _tokken = $('meta[name="_tokken"]').attr('value');

    // Ajax call to get proforma invoice details
    $(document).on('click', '#pro_inv_detail', function() {
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
                $('#product').html(sample_result_data.sample_type);
                $('#sample_deadline').html(sample_result_data.dead_line);
                $('#report_deadline').html(sample_result_data.report_deadline);
                $('#sample_desc').html(sample_result_data.sample);
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

                sno = 1;
                value = "<tr>";
                value += "<td>" + sno + "</td>";
                value += "<td>" + invoiceItemStore.gc_no + "</td>";
                value += "<td>" + invoiceItemStore.test_name + "</td>";
                value += "<td>" + invoiceItemStore.sample_desc + "</td>";
                // value += "<td><a href='javascript:void(0)' id='sample_details_view' data-toggle='modal' data-target='#sample_detail_view' data-one='" + invoiceItemStore.sample_reg_id + "' data-two='" + loadProformaInvoice.proforma_invoice_id + "'><img src='" + url + "assets/images/view_jobs_in_panel.png'></a></td>";
                value += "</tr>";
                $('#invoiceItemStore').html(value);
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
            dataType: 'JSON',
            success: function(data) {
                for (index in data) {
                    value = "<tr>";
                    value += "<td>" + data.user + "</td>";
                    value += "<td>" + data.action_message + "</td>";
                    value += "<td>" + data.log_activity_on + "</td>";
                    value += "<td>" + data.new_status + "</td>";
                    value += "<td>" + data.old_status + "</td>";
                    value += "</tr>";
                    $('#invoice_log').html(value);
                }
            }
        })
    });
    // Ajax call ends here

    // Ajax call to revise 
    // $(document).on('click', '#revise', function() {
    //     var proforma_invoice_id = $('#proforma_invoice_id').val();
    //     $.ajax({
    //         type: 'post',
    //         url: url + 'revise',
    //         data: { _tokken: _tokken, invoice_id: proforma_invoice_id },
    //         dataType: 'json',
    //         success: function(data) {
    //             $('#proforma_detail').modal('hide');
    //         }
    //     })
    // });
    // Ajax call ends here

    // Ajax call to proceed without approve
    // $(document).on('click', '#proceed_w_approve', function() {
    //     var proforma_invoice_id = $('#proforma_invoice_id').val();
    //     $.ajax({
    //         type: 'post',
    //         url: url + 'without-approve',
    //         data: { _tokken: _tokken, proforma_invoice_id: proforma_invoice_id },
    //         dataType: 'json',
    //         success: function(data) {
    //             $('#proforma_detail').modal('hide');
    //         }
    //     })
    // });
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
                    window.location.reload();
                } else {
                    $.notify(data.message, "error");
                }
            }
        });
    });
    // Ajax call ends here

    // Ajax call to set value and check whether it is quote or normal generated
    $(document).on('click', '#test_add', function() {
        $('body').append('<div class="pageloader"></div>');
        var proforma_invoice_id = $(this).data('one');
        var sample_reg_id = $(this).data('two');
        $('#sample_reg_id').val(sample_reg_id);
        $('#sm_proforma_invoice_id').val(proforma_invoice_id);
        $.ajax({
            type: 'post',
            url: url + 'check-trf-type',
            data: { _tokken: _tokken, sample_reg_id: sample_reg_id },
            success: function(data) {
                $('.pageloader').remove();
                if (test_data != "") {
                    let rowIndex1 = 0;
                    console.log(rowIndex1);
                    var test_data = $.parseJSON(data);
                    var final_amount = 0;
                    var html = '<table id="myTable1" cell style="margin-top: 2pc; padding: 9px!important; ">\n\<tbody>';
                    $.each(test_data, function(key, value) {
                        html += '<tr id="record1' + rowIndex1 + '">';
                        html += '<td style="padding: 0px !important;"><input type="checkbox"></td>';
                        colIndex1++;
                        html += '<td style="padding: 0px !important;" > <textarea required class="form-control form-control-sm test_name' + rowIndex1 + '" name="test[' + rowIndex1 + '][dynamic_heading]" data-id="' + rowIndex1 + '">' + value.test_name + '</textarea></td>';
                        colIndex1++;
                        html += '<td style="padding: 0px !important;" > <input  type="number" required class="form-control form-control-sm row_change test_rate' + rowIndex1 + '"" name="test[' + rowIndex1 + '][dynamic_value]" value="' + value.price + '" data-id="' + rowIndex1 + '"></td>';
                        colIndex1++;
                        html += '<td style="padding: 0px !important;" > <input  type="number" required class="form-control form-control-sm row_change test_qty' + rowIndex1 + '"" name="test[' + rowIndex1 + '][quantity]" value="1" data-id="' + rowIndex1 + '"></td>';
                        colIndex1++;
                        html += '<td style="padding: 0px !important;" > <input  type="number" step=any required class="form-control form-control-sm row_change test_discount' + rowIndex1 + '"" name="test[' + rowIndex1 + '][discount]" value="0" data-id="' + rowIndex1 + '"></td>';
                        colIndex1++;
                        html += '<td style="padding: 0px !important;" > <input  type="number" required class="form-control form-control-sm test_amt' + rowIndex1 + '"" name="test[' + rowIndex1 + '][applicable_charge]" value="' + value.price + '" data-id="' + rowIndex1 + '" readonly></td>';
                        colIndex1++;
                        html += '<td class="removeClass_' + colIndex1 + '" style="padding: 0px !important;" ><a  style="margin-left:20px" class="delete_row_pol" href="javascript:void(0);">X</a></td>';
                        colIndex1++;
                        html += '</tr >';

                        final_amount = parseInt(final_amount) + parseInt(value.price);
                        $('#total_amount').val(final_amount);
                        rowIndex1++;
                        colIndex1 = 0;
                    });
                    html += '</tbody>';
                    html += '</table>';
                    $('#test_table').html(html);
                } else {
                    // Show table
                    var html = '<table id="myTable1" cell style="margin-top: 2pc; padding: 9px!important; ">\n\<tbody>';
                    html += '<tr id="record1' + rowIndex1 + '">';
                    html += '<td style="padding: 0px !important;">#</td>';
                    colIndex1++;
                    html += '<td style="padding: 0px !important;" > <textarea required class="form-control form-control-sm test_name' + rowIndex1 + '" name="test[0][dynamic_heading]" value="" data-id="' + rowIndex1 + '"></textarea></td>';
                    colIndex1++;
                    html += '<td style="padding: 0px !important;" > <input  type="number" required class="form-control form-control-sm row_change test_rate' + rowIndex1 + '"" name="test[0][dynamic_value]" value="0" data-id="' + rowIndex1 + '"></td>';
                    colIndex1++;
                    html += '<td style="padding: 0px !important;" > <input  type="number" required class="form-control form-control-sm row_change test_qty' + rowIndex1 + '"" name="test[0][quantity]" value="1" data-id="' + rowIndex1 + '"></td>';
                    colIndex1++;
                    html += '<td style="padding: 0px !important;" > <input  type="number" step=any required class="form-control form-control-sm row_change test_discount' + rowIndex1 + '"" name="test[0][discount]" value="0" data-id="' + rowIndex1 + '"></td>';
                    colIndex1++;
                    html += '<td style="padding: 0px !important;" > <input  type="number" required class="form-control form-control-sm test_amt' + rowIndex1 + '"" name="test[0][applicable_charge]" value="0" data-id="' + rowIndex1 + '" readonly></td>';
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
                    html += '<td style="padding: 0px !important;" > <input  type="number" required class="form-control form-control-sm row_change test_rate' + rowIndex1 + '"" name="test[1][dynamic_value]" value="0" data-id="' + rowIndex1 + '"></td>';
                    colIndex1++;
                    html += '<td style="padding: 0px !important;" > <input  type="number" required class="form-control form-control-sm row_change test_qty' + rowIndex1 + '"" name="test[1][quantity]" value="1" data-id="' + rowIndex1 + '"></td>';
                    colIndex1++;
                    html += '<td style="padding: 0px !important;" > <input  type="number" step=any required class="form-control form-control-sm row_change test_discount' + rowIndex1 + '"" name="test[1][discount]" value="0" data-id="' + rowIndex1 + '"></td>';
                    colIndex1++;
                    html += '<td style="padding: 0px !important;" > <input  type="number" required class="form-control form-control-sm test_amt' + rowIndex1 + '"" name="test[1][applicable_charge]" value="0" data-id="' + rowIndex1 + '" readonly></td>';
                    colIndex1++;
                    html += '<td class="removeClass_' + colIndex1 + '" style="padding: 0px !important;" ><a style="margin-left:20px" class="delete_row_pol" href="javascript:void(0);">X</a>';
                    colIndex1++;
                    html += '</td>';
                    html += '</tr>';
                    rowIndex1++;
                    colIndex1 = 0;
                    html += '</tbody>';
                    html += '</table>';
                    $('#test_table').html(html);
                }
            }
        });
    });
    // Ajax call ends here

    // Calculate amount for the dynamic table
    function count_total_AMOUNT() {
        var count = $('#test_table tr').length;
        var total = 0;
        for (i = 0; i < count; i++) {
            var test_rate = $('input.test_rate' + i).val();
            if (test_rate == "NaN") {
                test_rate = 0;
            }
            var test_amt = $('input.test_amt' + i);
            var test_discount = $('input.test_discount' + i).val();
            var test_qty = $('input.test_qty' + i).val();
            var discount = (test_rate * test_qty) * ((test_discount) / 100);
            var applicable_charge = (test_rate * test_qty) - (discount);
            total += (parseFloat(applicable_charge)) ? parseFloat(applicable_charge) : 0;
            test_amt.val(applicable_charge);
            $('.total_value').val(total);
        }
    }

    // Remove row
    $('#test_table [type="button"]').on('click', function() {
        var count = $('#test_table tr').length;
        if (count > 1) {
            $('td input:checked').closest('tr').remove();
        } else {
            alert("Please keep atleast one record.");
        }

    });

    $(document).on('keyup', '.row_change', function() {
        count_total_AMOUNT();
    });

    $(document).ready(function() {
        bsCustomFileInput.init();
        $(document).on('click', '.delete_row_pol', function() {
            var count = $('#test_table tr').length;
            rowIndex1 = $('#test_table tr').length;
            if (count > 1) {
                $(this).parents('tr').remove();
                count--;
                count_total_AMOUNT();
            } else {
                alert("Please keep atleast one record.");
            }
        });
    });
    // Calculation ends here

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
        $('body').append('<div class="pageloader"></div>');
        $.ajax({
            type: 'post',
            url: url + 'accept-proforma-invoice',
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
    // Accept and reject proforma invoice ajax ends here
});