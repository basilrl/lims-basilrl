$(document).ready(function() {
    const url = $('body').data('url');
    const _tokken = $('meta[name="_tokken"]').attr('value');

    // Ajax to get customers name in open trf add form
    $('#customer_type').change(function() {
        var type = $('#customer_type').val();
        $('#customer_name').empty();
        $('#customer_name').append($('<option></option>').attr({ disabled: 'disabled', selected: 'selected' }).text('Select Customer'));
        $.ajax({
            type: 'post',
            url: url + 'get-customer',
            data: { customer_type: type, _tokken: _tokken },
            success: function(data) {
                var customers = JSON.parse(data);
                $.each(customers, function(key, value) {
                    $('#customer_name').append($('<option></option>').attr('value', value.customer_id).text(value.customer_name));
                });
            }
        });
    });
    // Ajax call end here

    // Ajax call to get buyer name
    $('#applicant').change(function() {
        $('#buyer').empty();
        $('#buyer').append($('<option></option>').attr({ disabled: 'disabled', selected: 'selected' }).text('Select Buyer'));
        var applicant = $('#applicant').val();
        $.ajax({
            type: 'post',
            url: url + 'get-buyers',
            data: { applicant: applicant, _tokken: _tokken },
            success: function(data) {
                var buyers = JSON.parse(data);
                $.each(buyers, function(key, value) {
                    $('#buyer').append($('<option></option>').attr('value', value.customer_id).text(value.customer_name));
                });
            }
        });
    });

    // Ajax call end here


    // Ajax call to get contact person name
    $('#applicant').change(function() {
            $('#contact-person').empty();
            $('#contact-person').append($('<option></option>').attr('disabled', 'disabled').text('Select Contact Person'));
            var applicant = $('#applicant').val();
            $.ajax({
                type: 'post',
                url: url + 'get-contact-person',
                data: { applicant: applicant, _tokken: _tokken },
                success: function(data) {
                    var contact_person = JSON.parse(data);
                    $.each(contact_person, function(key, value) {
                        $('#contact-person').append($('<option></option>').attr('value', value.contact_id).text(value.contact_name));
                    });
                }
            })
        })
        // Ajax call end here


    // Ajax call to get exchange currency
    $('#country-currency').change(function() {
            $('#exchange-rate').val('');
            var currency = $('#country-currency').val();
            $.ajax({
                type: 'post',
                url: url + 'get-exchange-rate',
                data: { country_currency: currency, _tokken: _tokken },
                success: function(data) {
                    var exchange_rate = JSON.parse(data);
                    $.each(exchange_rate, function(key, value) {
                        $('#exchange-rate').val(value.exchange_rate);
                    });
                }
            })
        })
        // Ajax call ends here

    // Ajax call to get invoice to contact person
    $(".invoice-to").change(function() {
        $('#cp-invoice-to').empty();
        $('#cp-invoice-to').append($('<option></option>').attr('disabled', 'disabled').text('Contact Person(Invoice To)'));
        var invoice_to = $("input[type='radio']:checked").val();
        if (invoice_to == "Factory") {
            var user = $('#applicant').val();
        }
        if (invoice_to == "Buyer") {
            var user = $('#buyer').val();
        }
        if (invoice_to == "Agent") {
            var user = $('#agent');
        }
        if (invoice_to == "ThirdParty") {

            var user = $('#third-party').val();
        }
        $.ajax({
            type: 'post',
            url: url + 'invoice-contact',
            data: { user: user, _tokken: _tokken },
            success: function(data) {
                var contact_person = JSON.parse(data);
                $.each(contact_person, function(key, value) {
                    $('#cp-invoice-to').append($('<option></option>').attr('value', value.contact_id).text(value.contact_name));
                });
            }
        })

    });

    // Ajax call ends here

    // Ajax call to get CC contacts
    $('.cc').change(function() {
            $('#cc-contact').empty();
            var cc = $('.cc:checked').map(function() {
                return $(this).val();
            }).get();
            var user = "";
            // Check whether user has selected factory or not
            if (jQuery.inArray("Factory", cc) !== -1) {
                if (user === "") {
                    user = $('#applicant').val();
                } else {
                    user = user + ',' + $('#applicant').val();
                }
            }
            // Check whether user has selected buyer or not
            if (jQuery.inArray("Buyer", cc) !== -1) {
                if (user === "") {
                    user = $('#buyer').val();
                } else {
                    user = user + ',' + $('#buyer').val();
                }
            }
            // Check whether user has selected agent or not
            if (jQuery.inArray("Agent", cc) !== -1) {
                if (user === "") {
                    user = $('#agent').val();
                } else {
                    user = user + ',' + $('#agent').val();
                }
            }
            // Check whether user has selected third party or not
            if (jQuery.inArray("ThirdParty", cc) !== -1) {
                if (user === "") {
                    user = $('#third-party').val();
                } else {
                    user = user + ',' + $('#third-party').val();
                }
            }

            $.ajax({
                type: 'post',
                'url': url + 'cc-contact',
                data: { user: user, _tokken: _tokken },
                success: function(data) {
                    var cc_contact = JSON.parse(data);
                    $.each(cc_contact, function(key, value) {
                        $('#cc-contact').append($('<option></option>').attr('value', value.contact_id).text(value.contact_name));
                    })
                }
            })
        })
        // AJax call ends here

    // Ajax call to get BCC Contacts
    $('.bcc').change(function() {
            $('#bcc-contact').empty();
            var bcc = $('.bcc:checked').map(function() {
                return $(this).val();
            }).get();
            var user = "";
            // Check whether user has selected factory or not
            if (jQuery.inArray("Factory", bcc) !== -1) {
                if (user === "") {
                    user = $('#applicant').val();
                } else {
                    user = user + ',' + $('#applicant').val();
                }
            }
            // Check whether user has selected buyer or not
            if (jQuery.inArray("Buyer", bcc) !== -1) {
                if (user === "") {
                    user = $('#buyer').val();
                } else {
                    user = user + ',' + $('#buyer').val();
                }
            }
            // Check whether user has selected agent or not
            if (jQuery.inArray("Agent", bcc) !== -1) {
                if (user === "") {
                    user = $('#agent').val();
                } else {
                    user = user + ',' + $('#agent').val();
                }
            }
            // Check whether user has selected third party or not
            if (jQuery.inArray("ThirdParty", bcc) !== -1) {
                if (user === "") {
                    user = $('#third-party').val();
                } else {
                    user = user + ',' + $('#third-party').val();
                }
            }

            $.ajax({
                type: 'post',
                'url': url + 'bcc-contact',
                data: { user: user, _tokken: _tokken },
                success: function(data) {
                    var bcc_contact = JSON.parse(data);
                    $.each(bcc_contact, function(key, value) {
                        $('#bcc-contact').append($('<option></option>').attr('value', value.contact_id).text(value.contact_name));
                    })
                }
            })
        })
        // Ajax call ends here

    // Ajax call to get test name
    $('#product').change(function() {
            $('#test-name').empty();
            var product = $('#product').val();
            $.ajax({
                type: 'post',
                url: url + 'test-name',
                data: { product: product, _tokken: _tokken },
                success: function(data) {
                    var test_name = JSON.parse(data);
                    $.each(test_name, function(key, value) {
                        $('#test-name').append($('<option></option>').attr('value', value.test_id).text(value.test_name));
                    })
                }
            })
        })
        // Ajax call ends here

    // Ajax call to check custom fields
    $('#product').change(function() {
            var product_id = $('#product').val();
            $.ajax({
                type: 'post',
                url: url + 'check-custom-fields',
                data: { product_id: product_id, _tokken: _tokken },
                success: function(data) {
                    var fields = $.parseJSON(data);
                    if (fields != "") {
                        $('#custom-fields').html('');
                        $.each(fields, function(key, value) {
                            $('#custom-fields').append(value);
                        })
                    }
                }
            })
        })
        // Ajax call ends here

    // Ajax call to get open trf
    $('#open-trf-pagination').on('click', 'a', function(e) {
        e.preventDefault();
        var page = $(this).attr('data-ci-pagination-page');
        open_trf_pagination(page);
    });

    $(document).on('click', '#search-trf', function() {
        open_trf_pagination(0);
    });

    $(document).on('click', '.sorting', function() {
        var page = $(this).attr('data-ci-pagination-page');
        var column = $(this).data('one');
        if (column == "") {
            column = null;
        } else {
            column = $(this).data('one');
        }
        $('#column').val(column);
        var order = $('#order').val();
        if (order == "") {
            order = null;
        } else {
            order = $('#order').val();
        }
        $('#order').val(order);
        open_trf_pagination(0);
    });

    open_trf_pagination(0);

    function open_trf_pagination(page) {
        var trf_reference_number = $('#trf_reference_number').val();
        if (trf_reference_number == "") {
            trf_reference_number = null;
        } else {
            trf_reference_number = btoa($('#trf_reference_number').val());
        }
        var column = $('#column').val();
        if (column == null || column === "") {
            column = null;
        } else {
            column = $('#column').val();
        }
        var customer_name = $('#customer_name').val();
        if (customer_name == null || customer_name === "") {
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
        var created_by = $('#created_by').val();
        if (created_by == null || created_by === "") {
            created_by = null;
        } else {
            created_by = $('#created_by').val();
        }
        var status = $('#status').val();
        if (status == null || status === "") {
            status = null;
        } else {
            status = btoa($('#status').val());
        }
        var buyer = $('#buyer').val();
        if (buyer == null || buyer === "") {
            buyer = null;
        } else {
            buyer = $('#buyer').val();
        }
        var service_type = $('#service_type').val();
        if (service_type == null) {
            service_type = null;
        } else {
            service_type = $('#service_type').val();
        }
        var order = $('#order').val();
        if (order == "") {
            order = null;
        } else {
            if (order == "desc") {
                order = "asc";
            } else {
                order = "desc";
            }
        }
        var division = $('#division').val();
        if (division == null || division === "") {
            division = null;
        } else {
            division = $('#division').val();
        }
        $.ajax({
            url: url + 'open-trf-records/' + page + '/' + trf_reference_number + '/' + customer_name + '/' + product + '/' + created_on + '/' + created_by + '/' + service_type + '/' + column + '/' + order + '/' + buyer + '/' + status + '/' + division,
            type: 'get',
            dataType: 'json',
            success: function(response) {
                $('#open-trf-pagination').html(response.pagination);
                $('#open-trf-records').html(response.result_count);
                $('#order').val(response.order);
                load_open_trf(response.result, response.row);
            }
        });
    }

    function load_open_trf(result, sno) {
        sno = Number(sno);
        $('#open-trf-list tbody').empty();
        for (index in result) {
            var id = result[index].trf_id;
            var sample_ref_id = result[index].trf_sample_ref_id;
            var service_type = result[index].trf_service_type;
            var sample_name = result[index].sample_type_name;
            var trf_ref_no = result[index].trf_ref_no;
            var trf_status = result[index].trf_status;
            var reference_no = result[index].reference_no;
            var trf_registration_type = result[index].trf_regitration_type;
            var client = result[index].client;
            var tat_date = result[index].tat_date;
            var contact = result[index].contact;
            var created_on = result[index].create_on;
            var buyer = result[index].buyer;
            var updated_by = result[index].updated_by;
            var link = "edit-open-trf/" + id;
            var change_status = "TestRequestForm_Controller/send_sample_received/" + id;
            var add_sample = "add-sample/" + id;

            sno += 1;
            var data = "<tr>";
            data += "<td>" + sno + "</td>";
            data += "<td>" + service_type + "</td>";
            data += "<td>" + sample_name + "</td>";
            data += "<td>" + sample_ref_id + "</td>";
            data += "<td>" + trf_ref_no + "</td>";
            data += "<td>" + trf_registration_type + "</td>";
            data += "<td>" + client + "</td>";
            data += "<td>" + contact + "</td>";
            data += "<td>" + created_on + "</td>";
            data += "<td>" + updated_by + "</td>";
            data += "<td>" + buyer + "</td>";
            if (tat_date == "0000-00-00 00:00:00" || tat_date == null) {
                data += "<td>N/A</td>";
            } else {
                data += "<td>" + tat_date + "</td>";
            }
            data += "<td>" + trf_status + "</td>";
            if (trf_status == "New") {
                data += "<td><a href='" + change_status + "' title='Recieve Sample'><img src = " + url + '/assets/images/Laboratory--Accepted-Sample.png' + "></a> <a href='" + link + "'  title='Edit TRF'><img src = " + url + '/assets/images/edit_jobs.png' + "></a></td>";
            } else if (trf_status == "Sample Registered") {
                data += "<td><a href='" + link + "'  title='Edit TRF'><img src = " + url + '/assets/images/edit_jobs.png' + "></a></td>";
            } else {
                data += "<td><a href='" + add_sample + "' title='Register Sample'><img src = " + url + '/assets/images/add_sample.png' + "></a> <a href='" + link + "'  title='Edit TRF'><img src = " + url + '/assets/images/edit_jobs.png' + "></a></td>";
            }
            /**-----------------------clone trf---------------- */
            data += "<td><a data-id=" + id + " id='clone_trf' title='Clone TRF'><img src = " + url + '/assets/images/active_res.png' + "></a> </td>";
            /**------------------end ----------clone trf---------------- */
            data += "</tr>";
            $('#open-trf-list tbody').append(data);
        }
    }
    // Ajax call ends here
    /**------------------------clone trf---------------- */
    $(document).on('click', '#clone_trf', function() {
        if (confirm("Do you want to clone this TRF ? Please confirm?")) {
            var trf_id = $(this).data('id');
            $.ajax({
                type: 'post',
                url: url + 'TestRequestForm_Controller/clone_trf',
                data: { _tokken: _tokken, trf_id: trf_id },
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
    /**------------------end ----------clone trf---------------- */


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
                show_sample_test(test_data, sample_id)

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
                    $('#product_test').append($('<option></option>').attr('value', value.test_id).text(value.test_name + '(' + value.test_price + ')'));
                })
            }
        })
    });
    // List ends here

    // Add test in the test table
    $(document).on('click', '#add-test', function() {
        var test_id = $('#product_test').val();
        var sample_id = $('#sample_id').val();
        $('#product-list').modal('hide');
        $.ajax({
            type: 'post',
            url: url + 'add-test',
            data: { test_id: test_id, sample_id: sample_id, _tokken: _tokken },
            dataType: 'JSON',
            success: function(data) {
                show_sample_test(data.test_detail, sample_id);
            }
        })
    })

    // Show test in the sample details
    function show_sample_test(result, sample_id) {
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
            result += "<td><a href='javascript:void(0)' id='duplicate-test' data-one='" + test_id + "' data-two='" + sample_id + "'><img src='" + url + "assets/images/add.png'></a>" + ' ' + "<a href='javascript:void(0)' id='remove-test' data-one='" + sample_test_id + "' data-two='" + sample_id + "'><img src='" + url + "assets/images/delete.png'></a>" + ' ' + "<a href='javascript:void(0)' id='add-part'  data-one='" + sample_test_id + "' data-two='" + sample_id + "' data-toggle='modal' data-target='#part-list'><img src='" + url + "assets/images/part_icon.png'></a></td>";
            result += "</tr>";
            $('#sample-test').append(result);
        }
    }

    // Duplicate test in sample detail
    $(document).on('click', '#duplicate-test', function() {
        var test_id = $(this).data('one');
        var sample_id = $(this).data('two');
        $.ajax({
            type: 'post',
            url: url + 'add-test',
            data: { _tokken: _tokken, test_id: test_id, sample_id: sample_id },
            dataType: 'JSON',
            success: function(data) {
                show_sample_test(data.test_detail, sample_id);
            }
        })
    });

    // Remove test from the list
    $(document).on('click', '#remove-test', function() {
        var sample_test_id = $(this).data('one');
        var sample_id = $(this).data('two');
        var length = $('#sample-test tr').length;
        if (length == 1) {
            alert('Please keep atleast one record.');
        } else {
            $.ajax({
                type: 'post',
                url: url + 'remove-test',
                data: { _tokken: _tokken, sample_test_id: sample_test_id, sample_id: sample_id },
                dataType: 'JSON',
                success: function(data) {
                    show_sample_test(data.test_detail, sample_id);
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
            value += "<td><a href='javascript:void(0)' title='Edit Part' id='edit-part' data-one='" + part_id + "'>Edit</a></td>";
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
                    show_sample_test(data.test_detail, sample_reg_id);
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
        })
        // Ajax call ends here

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

    // Ajax call to show sample details
    // $(document).on('click', '#sample_details_view', function() {
    //     var sample_reg_id = $(this).data('one');
    //     var proforma_invoice_id = $(this).data('two');
    //     $.ajax({
    //         type: 'post',
    //         url: url + 'sample-detail-view',
    //         data: { _tokken: _tokken, sample_reg_id: sample_reg_id, proforma_invoice_id: proforma_invoice_id },
    //         dataType: 'json',
    //         success: function(data) {
    //             var sample_result_data = data.sample_result;
    //             var analysis_test_result = data.analysis_test_result;
    //             $('#sample_detail_viewLabel').html("Sample -" + sample_result_data.gc_num);
    //             $('#client').html(sample_result_data.client);
    //             $('#labs').html(sample_result_data.conducted_lab);
    //             $('#collected_time').html(sample_result_data.dt_time);
    //             $('#recieved_by').html(sample_result_data.create_by);
    //             $('#sample_recieve_time').html(sample_result_data.sample_received_date);
    //             $('#recieved_quantity').html(sample_result_data.qty_rec);
    //             $('#test_specification').html(sample_result_data.test_specification);
    //             $('#barcode_number').html(sample_result_data.barcode_no);
    //             $('#product').html(sample_result_data.sample_type);
    //             $('#sample_deadline').html(sample_result_data.dead_line);
    //             $('#report_deadline').html(sample_result_data.report_deadline);
    //             $('#sample_desc').html(sample_result_data.sample);
    //             for (index in analysis_test_result) {
    //                 var value = "<tr>";
    //                 value = "<td>" + analysis_test_result.test_name + "<td>";
    //                 value += "<td>" + analysis_test_result.test_method + "</td>";
    //                 value += "<td>" + analysis_test_result.test_method + "</td>";
    //                 value += "</tr>";
    //             }
    //         }
    //     })
    // });
    // Ajax call ends here


    // Ajax call to show template
    $(document).on('click', '#template_data_new', function() {
        if ($('#proforma_detail').is(':visible')) {
            $('#proforma_detail').modal('hide');
        }
        var proforma_invoice_id = $(this).data('one') ? $(this).data('one') : $('#proforma_invoice_id').val();
        $('#invoice_id').val(proforma_invoice_id);
        $.ajax({
            type: 'post',
            url: url + 'load-template',
            data: { _tokken: _tokken },
            dataType: 'json',
            success: function(data) {
                $('#invoice_template').empty();
                $.each(data, function(key, value) {
                    $('#invoice_template').append($('<option></option>').attr('value', value.template_id).text(value.template_name));
                });
            }
        });
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
    $(document).on('click', '#proceed_w_approve', function() {
        var proforma_invoice_id = $('#proforma_invoice_id').val();
        $.ajax({
            type: 'post',
            url: url + 'without-approve',
            data: { _tokken: _tokken, proforma_invoice_id: proforma_invoice_id },
            dataType: 'json',
            success: function(data) {
                $('#proforma_detail').modal('hide');
            }
        })
    });
    // Ajax call ends here

    // Save test for invoice
    $('#save_test').submit(function(e) {
        e.preventDefault();
        var form = $(this);
        $.ajax({
            type: 'post',
            url: form.attr('action'),
            data: form.serialize(),
            dataType: 'json',
            success: function(data) {
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
        var proforma_invoice_id = $(this).data('one');
        var sample_reg_id = $(this).data('two');
        $('#sample_reg_id').val(sample_reg_id);
        $('#sm_proforma_invoice_id').val(proforma_invoice_id);
        $.ajax({
            type: 'post',
            url: url + 'check-trf-type',
            data: { _tokken: _tokken, sample_reg_id: sample_reg_id },
            success: function(data) {
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
                        html += '<td style="padding: 0px !important;" > <input  type="number" required class="form-control form-control-sm row_change test_discount' + rowIndex1 + '"" name="test[' + rowIndex1 + '][discount]" value="0" data-id="' + rowIndex1 + '"></td>';
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
                    html += '<td style="padding: 0px !important;" > <input  type="number" required class="form-control form-control-sm row_change test_discount' + rowIndex1 + '"" name="test[0][discount]" value="0" data-id="' + rowIndex1 + '"></td>';
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
                    html += '<td style="padding: 0px !important;" > <input  type="number" required class="form-control form-control-sm row_change test_discount' + rowIndex1 + '"" name="test[1][discount]" value="0" data-id="' + rowIndex1 + '"></td>';
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

    // Ajax call to ulpload sample image
    $('#upload_sample_image').submit(function(e) {
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
                } else {
                    $.notify(data.message, "error");
                }
                if (data.error) {
                    $.each(data.error, function(i, v) {
                        $('#upload_sample_image input[name="' + i + '"]').after('<span class="text-danger errors_images">' + v + '</span>');
                    });
                }

                // window.location.reload();
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
                $('#barcode_html').html('<img src="' + barcode.barcode_path + '">')
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

    $(document).on('click', '#print_barcode', function() {
        print_barcode();
    });

    // Print barcode ajax call ends here

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

    // Forward Sample for Manual Reporting added by millan on 15-Jan-2021
    $(document).on('click', '.manual_reporting_sample', function() {
        var sample_reg_id = $(this).data('id');
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
        // alert(sample_reg_id);
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
        $.ajax({
            type: 'post',
            url: url + 'accept-proforma-invoice',
            data: { _tokken: _tokken, proforma_invoice_id: proforma_invoice_id },
            dataType: 'json',
            success: function(data) {
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
        $.ajax({
            type: 'post',
            url: url + 'reject-proforma-invoice',
            data: { _tokken: _tokken, proforma_invoice_id: proforma_invoice_id },
            dataType: 'json',
            success: function(data) {
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

    // Set proforma invoice id for accept and reject proforma invoice
    $(document).on('click', '#accept_reject', function() {
        var proforma_invoice_id = $(this).data('one');
        $('.proforma_invoice_id').val(proforma_invoice_id);
    });
    // Set proforma invoice id for accept and reject proforma invoice ends here

    // Accept and reject proforma invoice ajax
    $(document).on('click', '#accept_invoice', function() {
        var proforma_invoice_id = $('.proforma_invoice_id').val();
        var comment = $('.comment').val();
        $.ajax({
            type: 'post',
            url: url + 'Invoice_Controller/update_proforma_invoice_status',
            data: { _tokken: _tokken, proforma_invoice_id: proforma_invoice_id, comment: comment, status: 1 },
            dataType: 'json',
            success: function(data) {
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
        var proforma_invoice_id = $('.proforma_invoice_id').val();
        var comment = $('.comment').val();
        $.ajax({
            type: 'post',
            url: url + 'Invoice_Controller/update_proforma_invoice_status',
            data: { _tokken: _tokken, proforma_invoice_id: proforma_invoice_id, comment: comment, status: 2 },
            dataType: 'json',
            success: function(data) {
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
