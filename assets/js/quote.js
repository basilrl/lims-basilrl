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
            url: url + 'Quote_trf/get_customer_name',
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
            url: url + 'Quote_trf/get_buyer_name',
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
            url: url + 'Quote_trf/get_contact_person_name',
            data: { applicant: applicant, _tokken: _tokken },
            success: function(data) {
                var contact_person = JSON.parse(data);
                $.each(contact_person, function(key, value) {
                    $('#contact-person').append($('<option></option>').attr('value', value.contact_id).text(value.contact_name));
                });
            }
        })
    });
    // Ajax call end here


    // Ajax call to get exchange currency
    $('#country-currency').change(function() {
        $('#exchange-rate').val('');
        var currency = $('#country-currency').val();
        $.ajax({
            type: 'post',
            url: url + 'Quote_trf/get_exchange_rate',
            data: { country_currency: currency, _tokken: _tokken },
            success: function(data) {
                var exchange_rate = JSON.parse(data);
                $.each(exchange_rate, function(key, value) {
                    $('#exchange-rate').val(value.exchange_rate);
                });
            }
        })
    });
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
            url: url + 'Quote_trf/get_contact_name',
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
            'url': url + 'Quote_trf/get_contact_name',
            data: { user: user, _tokken: _tokken },
            success: function(data) {
                var cc_contact = JSON.parse(data);
                $.each(cc_contact, function(key, value) {
                    $('#cc-contact').append($('<option></option>').attr('value', value.contact_id).text(value.contact_name));
                })
            }
        })
    });
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
            'url': url + 'Quote_trf/get_contact_name',
            data: { user: user, _tokken: _tokken },
            success: function(data) {
                var bcc_contact = JSON.parse(data);
                $.each(bcc_contact, function(key, value) {
                    $('#bcc-contact').append($('<option></option>').attr('value', value.contact_id).text(value.contact_name));
                })
            }
        })
    });
    // Ajax call ends here

    // Ajax call to get test name
    $('#product').change(function() {
        $('#test-name').empty();
        var product = $('#product').val();
        $.ajax({
            type: 'post',
            url: url + 'Quote_trf/get_customer_name',
            data: { product: product, _tokken: _tokken },
            success: function(data) {
                var test_name = JSON.parse(data);
                $.each(test_name, function(key, value) {
                    $('#test-name').append($('<option></option>').attr('value', value.test_id).text(value.test_name));
                })
            }
        })
    });
    // Ajax call ends here

    // Ajax call to check custom fields
    $('#product').change(function() {
        var product_id = $('#product').val();
        $.ajax({
            type: 'post',
            url: url + 'Quote_trf/get_custom_fields',
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
    });
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
            var change_status = "Quote_trf/send_sample_received/" + id;
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
                url: url + 'Quote_trf/clone_trf',
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

    // Get image for application provided care instruction
    $(document).on('change', '.care_provided', function() {
        var self = $(this);
        var care_provided = $(this).val();
        $.ajax({
            type: 'post',
            url: url + 'Quote_trf/get_care_instruction_image',
            data: { _tokken: _tokken, care_provided: care_provided },
            success: function(data) {
                image_path = JSON.parse(data);
                self.parent().siblings('.application_image').val(image_path);
                self.parent().siblings('.care_image').html('<img src="' + image_path + '">');
            }
        });
    });
    //   Ends here 
});