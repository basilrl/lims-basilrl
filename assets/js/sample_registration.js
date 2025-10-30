$(document).ready(function() {
    const url = $('body').data('url');
    const _tokken = $('meta[name="_tokken"]').attr('value');

    // Ajax call to get labs for sample registration
    $('#branch_name').change(function() {
        $('#labs').empty();
        var trf_id = $('#trf_id').val();
        $.ajax({
            type: 'post',
            url: url + 'get-labs',
            data: { _tokken: _tokken, trf_id: trf_id },
            success: function(data) {
                var lab = JSON.parse(data);
                $.each(lab, function(key, value) {
                    $('#labs').append($('<option></option>').attr('value', value.lab_id).text(value.lab_name));
                })
            }
        });
    });

    // Ajax call ends here

    $(document).on('click', '#sent_sample', function() {
        if (confirm("Do you want to send sample to sample evaluation, please confirm?")) {
            var sample_id = $(this).data('one');
            $.ajax({
                type: 'post',
                url: url + 'send-sample-for-evaluation',
                data: { _tokken: _tokken, sample_id: sample_id },
                dataType: 'json',
                success: function(data) {
                    if (data) {
                        window.location.reload();
                    }
                }
            })
        }
    });

    $(document).on('click', '#show_detail', function() {
        $('#sample-test').empty();
        var sample_id = $(this).data('one');
        $.ajax({
            type: 'post',
            url: url + 'sample-details',
            data: { _tokken: _tokken, sample_id: sample_id },
            dataType: 'JSON',
            success: function(data) {
                var sample_data = data.sample_detail;
                var test_data = data.test_detail;
                var price_type = sample_data.price_type;
                if (price_type == "Book Price") {
                    $('#book_price').attr('checked', true);
                    $('#flat_price').attr('disabled', true);
                }
                if (price_type == "Flat Price") {
                    $('#book_price').attr('disabled', true);
                    $('#flat_price').attr('checked', true);
                }
                if (sample_data.status == "Sample Sent for Evaluation") {
                    $('.add_test_btn').html("<button class='btn btn-primary' id='product_test_list' data-bs-toggle='modal' data-bs-target='#product-list'>Add Test</button>");
                    $('.save_evaluation').html('<button type="button" class="btn btn-primary" id="save-evaluation">Save changes</button>');
                } else {
                    $('.add_test_btn').html('');
                    $('.save_evaluation').html('');
                }
                sample_status = sample_data.status;
                $('#sample_id').val(sample_id);
                $('#gc_no').html(sample_data.gc_no);
                $('#client').html(sample_data.client);
                $('#labs').html(sample_data.conducted_lab);
                $('#collect_time').html(sample_data.collection_time);
                $('#create_by').html(sample_data.create_by);
                $('#recieve_time').html(sample_data.received_date);
                $('#qty_recieved').html(sample_data.qty_received.concat(" ", sample_data.qty_unit));
                $('#test_specification').html(sample_data.test_specification);
                $('#contact').html(sample_data.contact);
                $('#trf_product').html(sample_data.sample_type_id);
                $('#qty_desc').html(sample_data.quantity_desc);
                $('#retain_sample').html(sample_data.sample_retain_period);
                $('#sample_desc').html(sample_data.sample_desc);
                $('#barcode_img').html('<img src="' + sample_data.barcode + '">');
                $('#tat_date').html(sample_data.tat_date);
                var product_id = sample_data.sample_registration_sample_type_id;
                $('#product_test_list').attr('data-one', product_id);
                show_sample_test(test_data, sample_id, sample_status)

            }
        })
    });

    // Get propduct test list
    $(document).on('click', '#product_test_list', function() {
        $('#product_test').empty();
        var product_id = $(this).data('one');
        $.ajax({
            type: 'post',
            url: url + 'test-name',
            data: { _tokken: _tokken, product: product_id },
            dataType: 'json',
            success: function(data) {
                $.each(data, function(key, value) {
                    $('#product_test').append($('<option></option>').attr('value', value.id).text(value.name));
                })
            }
        })
    });
    // List ends here

    // Add test in the test table
    $(document).on('click', '#add-test', function() {
        var test_id = $('#product_test').val();
        var sample_id = $('#sample_id').val();
        var test_method = $('#test_method').val();
        $('#product-list').modal('hide');
        $.ajax({
            type: 'post',
            url: url + 'add-test',
            data: { test_id: test_id, sample_id: sample_id, _tokken: _tokken, test_method: test_method },
            dataType: 'JSON',
            success: function(data) {
                $('#sample_detail').modal('show'); // new
                show_sample_test(data.test_detail, sample_id, sample_status);
            }
        })
    })

    // Show test in the sample details
    function show_sample_test(result, sample_id, sample_status) {
        $('#sample-test').empty();
        var grid_details = "";
        var test_data = result;
        grid_details = JSON.stringify(test_data);
        $('#grid_details').val(grid_details);
        sno = 0;
        for (index in test_data) {
            sno += 1;
            var test_id = test_data[index].test_id;
            var sample_test_id = test_data[index].sample_test_id;
            var discount = test_data[index].discount;
            var applicable_charge = test_data[index].applicable_charge;
            var rate = test_data[index].rate_per_test;
            var test_method = test_data[index].test_method;
            var test_name = test_data[index].test_name;
            var test_desc = test_data[index].test_description;
            var part_name = (test_data[index].part_name) ? test_data[index].part_name : '';
            result = '<tr>';
            result += "<td>" + sno + "</td>";;
            result += "<td>" + test_name + "</td>";
            result += "<td>" + test_method + "</td>";
            result += "<td>" + rate + "</td>";
            result += "<td>" + discount + "</td>";
            result += "<td>" + applicable_charge + "</td>";
            result += "<td>" + test_desc + "</td>";
            result += "<td>" + part_name + "</td>";
            result += "<td>";
            if (sample_status == 'Sample Sent for Evaluation') {
                result += "<a href='javascript:void(0)'id='duplicate-test' data-status='" + sample_status + "' data-one='" + test_id + "' data-two='" + sample_id + "'><img src='" + url + "assets/images/add.png'></a>" + ' ' + "<a href='javascript:void(0)'id='remove-test' data-status='" + sample_status + "' data-one='" + sample_test_id + "' data-two='" + sample_id + "'><img src='" + url + "assets/images/delete.png'></a>" + ' ' + "<a href='javascript:void(0)' id='add-part' data-status='" + sample_status + "' data-one='" + sample_test_id + "' data-two='" + sample_id + "' data-toggle='modal' data-target='#part-list'><img src='" + url + "assets/images/part_icon.png'></a>";
            }
            result += "</td>";
            result += "</tr>";
            $('#sample-test').append(result);
        }
    }

    // Duplicate test in sample detail
    $(document).on('click', '#duplicate-test', function() {
        var test_id = $(this).data('one');
        var sample_id = $(this).data('two');
        var sample_status = $(this).data('status');
        $.ajax({
            type: 'post',
            url: url + 'add-test',
            data: { _tokken: _tokken, test_id: test_id, sample_id: sample_id },
            dataType: 'JSON',
            success: function(data) {
                show_sample_test(data.test_detail, sample_id, sample_status);
            }
        })
    });

    // Remove test from the list
    $(document).on('click', '#remove-test', function() {
        var sample_test_id = $(this).data('one');
        var sample_id = $(this).data('two');
        var length = $('#sample-test tr').length;
        var sample_status = $(this).data('status');
        if (length == 1) {
            alert('Please keep atleast one record.');
        } else {
            $.ajax({
                type: 'post',
                url: url + 'remove-test',
                data: { _tokken: _tokken, sample_test_id: sample_test_id, sample_id: sample_id },
                dataType: 'JSON',
                success: function(data) {
                    show_sample_test(data.test_detail, sample_id, sample_status);
                }
            })
        }

    });

    // Show part add form row
    $(document).on('click', '#add_part', function() {
        $('#part-add-form').css('display', 'block');
    });
    // Hide add part row on cancel
    $(document).on('click', '#cancel', function() {
        $('#part-add-form').css('display', 'none');
    });
    // Show part list modal 
    $(document).on('click', '#add-part', function() {
        var sample_id = $(this).data('two');
        var test_id = $(this).data('one');
        $('#sample_test_id').val(test_id);
        $('#part_sample_reg_id').val(sample_id);
    });

    // Save part
    $(document).on('click', '#submit_part', function() {
        name_error = true;
        desc_error = true;
        var part_id = $('#part_id').val();
        var sample_reg_id = $('#part_sample_reg_id').val();
        var part_name = $('#part_name').val();
        var part_desc = $('#part_desc').val();
        if (part_name == "") {
            $('#name_error').html('Name is required');
            name_error = false;
        }
        if (part_desc == "") {
            $('#desc_error').html('Description is required');
            desc_error = false;
        }
        if (name_error && desc_error) {
            $.ajax({
                type: 'post',
                url: url + 'save-part',
                data: { _tokken: _tokken, part_id: part_id, part_name: part_name, part_desc: part_desc, sample_id: sample_reg_id },
                dataType: 'JSON',
                success: function(data) {
                    part_name = $('#part_name').val('');
                    part_desc = $('#part_desc').val('');
                    $('#part-add-form').css('display', 'none');
                    show_part_list(data);
                }
            })
        }

    });
    // Select sample part type
    $(document).on("change", ".sample_part_type", function() {
        var sample_id = $('#part_sample_reg_id').val();
        $.ajax({
            type: 'post',
            url: url + 'get-parts',
            data: { _tokken: _tokken, sample_id: sample_id },
            dataType: 'JSON',
            success: function(data) {
                show_part_list(data);
            }
        });
    });
    // Sample select part type ends here

    // Show part listing
    function show_part_list(data) {
        var sample_part_type = $("input[name='part_type']:checked").val();
        $('#part-listing').empty();
        sno = 0;
        for (index in data) {
            sno += 1;
            var part_id = data[index].part_id;
            var part_name = data[index].part_name;
            var part_desc = data[index].parts_desc;
            var sample_id = data[index].parts_sample_reg_id;
            value = "<tr>";
            if (sample_part_type == 0) {
                value += "<td><input type='radio' name='sample_part_id' value='" + part_id + "'></td>";
            } else {
                value += "<td><input type='checkbox' name='sample_part_id[]' value='" + part_id + "'></td>";
            }

            value += "<td>" + part_name + "</td>";
            value += "<td>" + part_desc + "</td>";
            value += "<td><a href='javascript:void(0)' title='Edit Part' id='edit-part' data-one='" + part_id + "'><img src='" + url + "public/img/icon/edit.png' class='edit'></a><a href='javascript:void(0)' title='Delete Part' id='delete-part' data-one='" + part_id + "' data-two='" + sample_id + "'><img src='" + url + "assets/images/del.png' class='edit'></a></td>";
            value += "</tr>";
            $('#part-listing').append(value);
        }
    }

    // Edit part data
    $(document).on('click', '#edit-part', function() {
        var part_id = $(this).data('one');
        $.ajax({
            type: 'post',
            url: url + 'part-details',
            data: { _tokken: _tokken, part_id: part_id },
            dataType: 'JSON',
            success: function(data) {
                for (index in data) {
                    var sample_id = data[index].parts_sample_reg_id;
                    var part_name = data[index].part_name;
                    var part_desc = data[index].parts_desc;
                    $('#part_id').val(part_id);
                    $('#sample_reg_id').val(sample_id);
                    $('#part_name').val(part_name);
                    $('#part_desc').val(part_desc);
                    $('#part-add-form').css('display', 'block');
                }
            }
        });
    });

    // Delete part data
    $(document).on('click', '#delete-part', function() {
        var part_id = $(this).data('one');
        var sample_id = $(this).data('two');
        if (confirm('Are you sure want to delete this part?')) {
            $.ajax({
                type: 'post',
                url: url + 'SampleRegistration_Controller/delete_part',
                data: { _tokken: _tokken, part_id: part_id, sample_id: sample_id },
                dataType: 'JSON',
                success: function(data) {
                    show_part_list(data);
                }
            });
        }

    });

    // Add part to sample test
    $(document).on('click', '#add-part-sample', function() {
        var sample_part_type = $("input[name='part_type']:checked").val();
        var part_id = [];
        if (sample_part_type == 0) {
            part_id = $("input[name='sample_part_id']:checked").val();
        } else {
            $(':checkbox:checked').each(function(i) {
                part_id[i] = $(this).val();
            });
            // var part_id = $("input[name='sample_part_id[]']:checked").val();
        }
        var sample_test_id = $('#sample_test_id').val();
        var sample_reg_id = $('#part_sample_reg_id').val();
        if (part_id != "" || part_id != "undefined") {
            $.ajax({
                type: 'post',
                url: url + 'insert-part',
                data: { _tokken: _tokken, part_id: part_id, sample_test_id: sample_test_id, sample_reg_id: sample_reg_id },
                dataType: 'JSON',
                success: function(data) {
                    $('#part-list').modal('hide');
                    show_sample_test(data.test_detail, sample_reg_id, data.sample_status);
                }
            });
        } else {
            $('#part-error').html('Select Atleast one');
        }
    });
    // Add part to sample test ends here

    $(document).on('click', '#save-evaluation', function() {
        var test_data = $('#grid_details').val();
        var sample_reg_id = $('#sample_id').val();
        var price_type = $("input[name='price_type']:checked").val();
        $.ajax({
            type: 'post',
            url: url + 'save-evaluation',
            data: { _tokken: _tokken, grid_details: test_data, sample_reg_id: sample_reg_id, price_type: price_type },
            dataType: 'JSON',
            success: function(data) {
                if (data.status > 0) {
                    $.notify(data.message, "success");
                    $('#sample_detail').modal('hide');
                } else {
                    $.notify(data.message, "error");
                }
                window.location.reload();
            }
        })
    });
    // Ajax call ends here

    // Get image for application provided care instruction
    $(document).on('change', '.care_provided', function() {
        var self = $(this);
        var care_provided = $(this).val();
        $.ajax({
            type: 'post',
            url: url + 'get-care-provided-image',
            data: { _tokken: _tokken, care_provided: care_provided },
            success: function(data) {
                image_path = JSON.parse(data);
                self.parent().siblings('.application_image').val(image_path);
                self.parent().siblings('.care_image').html('<img src="' + image_path + '">');
            }
        });
    });
    //   Ends here 

    // Ajax call to ulpload sample image
    $('#upload_sample_image').submit(function(e) {
        $('#submit').html('Wait...');
        $('#submit').attr('disabled', 'disabled');
        var self = $(this);
        e.preventDefault();
        $.ajax({
            type: "post",
            processData: false,
            contentType: false,
            cache: false,
            async: false,
            url: url + 'upload-sample-image',
            data: new FormData(this),
            success: function(data) {
                $('.errors_images').remove();
                var data = $.parseJSON(data);
                if (data.status > 0) {
                    self.trigger('reset');
                    $.notify(data.message, "success");
                    $('#upload_image').modal('hide');
                    window.location.reload();
                } else {
                    $.notify(data.message, "error");
                    $('#submit').html('Save Changes');
                    $('#submit').attr('disabled', false);
                }
                if (data.error) {
                    $.each(data.error, function(i, v) {
                        $('#upload_sample_image input[name="' + i + '"]').after('<span class="text-danger errors_images">' + v + '</span>');
                    });
                }
            }
        });
    });
    // Ajax call to ulpload sample image ends here

    // Resend acknowledgement mail
    $(document).on('click', '#send_email', function() {
        var sample_reg_id = $(this).data('one');
        var mail_type = $(this).data('two');
        $.notify("Mail is being sent, Please wait.", "success");
        $.ajax({
            type: 'post',
            url: url + 'send-email',
            data: { _tokken: _tokken, sample_reg_id: sample_reg_id, mail_type: mail_type },
            success: function(data) {
                var data = $.parseJSON(data);
                if (data.status > 0) {
                    $.notify(data.message, "success");
                    $('#upload_image').modal('hide');
                } else {
                    $.notify(data.message, "error");
                }
            }
        })
    });
    // Resend acknowledgement mail ends here

    // Ajax call to Show worksheet
    $(document).on('click', '#show_worksheet', function() {
        $('#worksheet_html').empty();
        var sample_reg_id = $(this).data('one');
        $.ajax({
            type: 'post',
            url: url + 'record-finding-pdf',
            data: { _tokken: _tokken, sample_reg_id: sample_reg_id },
            dataType: 'json',
            success: function(data) {
                $('#worksheet_html').html(data);
            }
        })
    });
    // Ajax call ends here

    // Ajax call to generate invoice
    $(document).on('click', '#generate_invoice', function() {
        var proforma_invoice_id = $(this).data('one');
        if (confirm('Do you want to generate performa invoice!')) {
            $.notify('Proforma invoice being generated', 'success');
            $.ajax({
                type: 'post',
                url: url + 'sign-off',
                data: { _tokken: _tokken, proforma_invoice_id: proforma_invoice_id },
                dataType: 'json',
                success: function(data) {
                    window.location.reload();
                }
            })
        }
    });
    // Ajax call ends here

    // Ajax call to set sample_reg_id
    $(document).on('click', '#sample_image_upload', function() {
        var sample_reg_id = $(this).data('one');
        $('#sample_reg_id').val(sample_reg_id);
    });
    // Ajax call to set sample_reg_id ends here

    // Print worksheet
    function print_worksheet() {
        var divToPrint = document.getElementById("worksheet_html");
        newWin = window.open("");
        newWin.document.write(divToPrint.outerHTML);
        newWin.print();
        newWin.close();
    }

    $(document).on('click', '#print_worksheet', function() {
        print_worksheet();
    });
    // print worksheet ends here

    // Print barcode ajax call
    $(document).on('click', '#show_barcode', function() {
        var sample_reg_id = $(this).data('one');
        $.ajax({
            type: 'post',
            url: url + 'get-barcode',
            data: { _tokken: _tokken, sample_reg_id: sample_reg_id },
            success: function(data) {
                barcode = JSON.parse(data);
                $('#barcode_html').html('<img id="print_barcode_download" src="' + barcode.barcode_path + '">')
            }
        });
    });

    function print_barcode() {
        var divToPrint = document.getElementById("barcode_html");
        newWin = window.open("");
        newWin.document.write(divToPrint.outerHTML);
        newWin.print();
        newWin.close();
    }

    function PrintImage() {

        var src = $('#print_barcode_download').attr('src');

        var newWindow = window.open('', '', 'width=100, height=100'),
            document = newWindow.document.open(),
            pageContent =
            '<!DOCTYPE html>' +
            '<html>' +
            '<head>' +
            '<meta charset="utf-8" />' +
            '<title>Inventory</title>' +
            '<style type="text/css">body {-webkit-print-color-adjust: exact; font-family: Arial; }</style>' +
            '</head>' +
            '<body><div><div style="width:33.33%; float:left;"><img src="' + src + '"/></body></html>';
        document.write(pageContent);
        document.close();
        newWindow.moveTo(0, 0);
        newWindow.resizeTo(screen.width, screen.height);
        setTimeout(function() {
            newWindow.print();
            newWindow.close();
        }, 250);

        /* OLD CODE */
        // var w = window.open('', '_new'); //create new window
        // var image = document.createElement('img'); //create img element
        // image.src = document.getElementById('print_barcode_download').src; //set the src
        // w.document.body.appendChild(image); //append it to the body
        // w.print(); //invoke browser's print function
        // w.close(); //close window
    }

    $(document).on('click', '#print_barcode', function() {
        // print_barcode();
        PrintImage();
    });

    // Print barcode ajax call ends here 

    // Forward Sample for Manual Reporting added by millan on 15-Jan-2021
    $(document).on('click', '.manual_reporting_sample', function() {
        var sample_reg_id = $(this).data('id');
        if (confirm('Do you want to forward sample for manual reporting')) {
            $.ajax({
                type: 'post',
                url: url + 'SampleRegistration_Controller/send_sample_for_manual_report',
                data: { sample_reg_id: sample_reg_id, _tokken: _tokken },
                dataType: 'json',
                success: function(data) {
                    if (data) {
                        window.location.reload();
                    }
                }
            })
        }
    });

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

    // upload manual report pdf function call ajax added by millan on 15-Jan-2021
    $('#upload_mrpdf').submit(function(e) {
        e.preventDefault();
        $.ajax({
            type: "post",
            processData: false,
            contentType: false,
            cache: false,
            async: false,
            url: url + 'upload-manual-report-pdf',
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

    // Ajax call to get Sample images
    $(document).on('click', '#sample_image', function() {
        $('#sample_image_view').empty();
        var sample_reg_id = $(this).data('id');
        $.ajax({
            type: 'post',
            url: url + 'get-sample-images',
            data: { _tokken: _tokken, sample_reg_id: sample_reg_id },
            success: function(data) {
                var images = JSON.parse(data);
                var image = '<table class="table">';
                $.each(images, function(key, value) {
                    image += '<tr>';
                    image += '<td><img src="' + value.image_file_path + '" style="width:50%; height:auto"></td>';
                    image += '</tr>';
                });
                image += '</table>';
                $('#sample_image_view').append(image);
            }
        })
    });
    // Ajax call to get sample images ends here

    // Ajax call to get sample retain period 
    $('.sample_retain_status').change(function() {
        var sample_retain_status = $('.sample_retain_status').val();
        if (sample_retain_status == 1) {
            $('#retain_sample_input').css('display', 'block');
        }
    });
    // Ajax call to get sample retain period ends here

    // Check GC number duplicacy
    $(document).on('blur', '#gc_number', function() {
        var branch = $('#branch_name').val();
        var gc_no = $('#gc_number').val();
        var trf_id = $('#trf_id').val();
        $.ajax({
            type: 'post',
            url: url + 'SampleRegistration_Controller/check_gc_number',
            data: { _tokken: _tokken, branch: branch, gc_no: gc_no, trf_id: trf_id },
            success: function(data) {
                var data = $.parseJSON(data);
                if (data.status > 0) {
                    $.notify(data.message, 'success');
                    $('#submit').attr('disabled', false);
                } else {
                    $.notify(data.message, 'error');
                    $('#submit').attr('disabled', 'disabled');
                }
            }
        });
    });


    // Check GC number duplicacy ends here
});