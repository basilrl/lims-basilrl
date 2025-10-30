$(document).ready(function() {
    const url = $('body').data('url');
    const _tokken = $('meta[name="_tokken"]').attr('value');

    // Ajax to get customers name in open trf add form
    // $('#customer_type').change(function() {
    //     var type = $('#customer_type').val();
    //     $('#customer_name').empty();
    //     $('#customer_name').append($('<option></option>').attr({ disabled: 'disabled', selected: 'selected' }).text('Select Customer'));
    //     $.ajax({
    //         type: 'post',
    //         url: url + 'get-customer',
    //         data: { customer_type: type, _tokken: _tokken },
    //         success: function(data) {
    //             var customers = JSON.parse(data);
    //             $.each(customers, function(key, value) {
    //                 $('#customer_name').append($('<option></option>').attr('value', value.customer_id).text(value.customer_name));
    //             });
    //         }
    //     });
    // });
    // Ajax call end here

    // Ajax call to get buyer name
    // $('#applicant').change(function() {
    //     $('#buyer').empty();
    //     $('#buyer').append($('<option></option>').attr({ disabled: 'disabled', selected: 'selected' }).text('Select Buyer'));
    //     var applicant = $('#applicant').val();
    //     $.ajax({
    //         type: 'post',
    //         url: url + 'get-buyers',
    //         data: { applicant: applicant, _tokken: _tokken },
    //         success: function(data) {
    //             var buyers = JSON.parse(data);
    //             $.each(buyers, function(key, value) {
    //                 $('#buyer').append($('<option></option>').attr('value', value.customer_id).text(value.customer_name));
    //             });
    //         }
    //     });
    // });

    // Ajax call end here


    // Ajax call to get contact person name
    // $('#applicant').change(function() {
    //     $('#contact-person').empty();
    //     $('#contact-person').append($('<option></option>').attr('disabled', 'disabled').text('Select Contact Person'));
    //     var applicant = $('#applicant').val();
    //     $.ajax({
    //         type: 'post',
    //         url: url + 'get-contact-person',
    //         data: { applicant: applicant, _tokken: _tokken },
    //         success: function(data) {
    //             var contact_person = JSON.parse(data);
    //             $.each(contact_person, function(key, value) {
    //                 $('#contact-person').append($('<option></option>').attr('value', value.contact_id).text(value.contact_name));
    //             });
    //         }
    //     })
    // });
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
    });
    // Ajax call ends here

    // Ajax call to get invoice to contact person
    // $(".invoice-to").change(function() {
    //     $('#cp-invoice-to').empty();
    //     $('#cp-invoice-to').append($('<option></option>').attr('disabled', 'disabled').text('Contact Person(Invoice To)'));
    //     var invoice_to = $("input[type='radio']:checked").val();
    //     if (invoice_to == "Factory") {
    //         var user = $('#applicant').val();
    //     }
    //     if (invoice_to == "Buyer") {
    //         var user = $('#buyer').val();
    //     }
    //     if (invoice_to == "Agent") {
    //         var user = $('#agent');
    //     }
    //     if (invoice_to == "ThirdParty") {

    //         var user = $('#third-party').val();
    //     }
    //     $.ajax({
    //         type: 'post',
    //         url: url + 'invoice-contact',
    //         data: { user: user, _tokken: _tokken },
    //         success: function(data) {
    //             var contact_person = JSON.parse(data);
    //             $.each(contact_person, function(key, value) {
    //                 $('#cp-invoice-to').append($('<option></option>').attr('value', value.contact_id).text(value.contact_name));
    //             });
    //         }
    //     })

    // });

    // Ajax call ends here

    // Ajax call to get CC contacts
    // $('.cc').change(function() {
    //     $('#cc-contact').empty();
    //     var cc = $('.cc:checked').map(function() {
    //         return $(this).val();
    //     }).get();
    //     var user = "";
    //     // Check whether user has selected factory or not
    //     if (jQuery.inArray("Factory", cc) !== -1) {
    //         if (user === "") {
    //             user = $('#applicant').val();
    //         } else {
    //             user = user + ',' + $('#applicant').val();
    //         }
    //     }
    //     // Check whether user has selected buyer or not
    //     if (jQuery.inArray("Buyer", cc) !== -1) {
    //         if (user === "") {
    //             user = $('#buyer').val();
    //         } else {
    //             user = user + ',' + $('#buyer').val();
    //         }
    //     }
    //     // Check whether user has selected agent or not
    //     if (jQuery.inArray("Agent", cc) !== -1) {
    //         if (user === "") {
    //             user = $('#agent').val();
    //         } else {
    //             user = user + ',' + $('#agent').val();
    //         }
    //     }
    //     // Check whether user has selected third party or not
    //     if (jQuery.inArray("ThirdParty", cc) !== -1) {
    //         if (user === "") {
    //             user = $('#third-party').val();
    //         } else {
    //             user = user + ',' + $('#third-party').val();
    //         }
    //     }

    //     $.ajax({
    //         type: 'post',
    //         'url': url + 'cc-contact',
    //         data: { user: user, _tokken: _tokken },
    //         success: function(data) {
    //             var cc_contact = JSON.parse(data);
    //             $.each(cc_contact, function(key, value) {
    //                 $('#cc-contact').append($('<option></option>').attr('value', value.contact_id).text(value.contact_name));
    //             })
    //         }
    //     })
    // });
    // AJax call ends here

    // Ajax call to get BCC Contacts
    // $('.bcc').change(function() {
    //     $('#bcc-contact').empty();
    //         var bcc = $('.bcc:checked').map(function() {
    //             return $(this).val();
    //         }).get();
    //         var user = "";
    //         // Check whether user has selected factory or not
    //         if (jQuery.inArray("Factory", bcc) !== -1) {
    //             if (user === "") {
    //                 user = $('#applicant').val();
    //             } else {
    //                 user = user + ',' + $('#applicant').val();
    //             }
    //         }
    //         // Check whether user has selected buyer or not
    //         if (jQuery.inArray("Buyer", bcc) !== -1) {
    //             if (user === "") {
    //                 user = $('#buyer').val();
    //             } else {
    //                 user = user + ',' + $('#buyer').val();
    //             }
    //         }
    //         // Check whether user has selected agent or not
    //         if (jQuery.inArray("Agent", bcc) !== -1) {
    //             if (user === "") {
    //                 user = $('#agent').val();
    //             } else {
    //                 user = user + ',' + $('#agent').val();
    //             }
    //         }
    //         // Check whether user has selected third party or not
    //         if (jQuery.inArray("ThirdParty", bcc) !== -1) {
    //             if (user === "") {
    //                 user = $('#third-party').val();
    //             } else {
    //                 user = user + ',' + $('#third-party').val();
    //             }
    //         }

    //     $.ajax({
    //         type: 'post',
    //         'url': url + 'bcc-contact',
    //         data: { user: user, _tokken: _tokken },
    //         success: function(data) {
    //             var bcc_contact = JSON.parse(data);
    //             $.each(bcc_contact, function(key, value) {
    //                 $('#bcc-contact').append($('<option></option>').attr('value', value.contact_id).text(value.contact_name));
    //             })
    //         }
    //     })
    // });
    // Ajax call ends here

    // Ajax call to get test name
    // $('#product').change(function() {
    //     $('#test-name').empty();
    //     var product = $('#product').val();
    //     $.ajax({
    //         type: 'post',
    //         url: url + 'test-name',
    //         data: { product: product, _tokken: _tokken },
    //         success: function(data) {
    //             var test_name = JSON.parse(data);
    //             $.each(test_name, function(key, value) {
    //                 $('#test-name').append($('<option></option>').attr('value', value.test_id).text(value.test_name));
    //             })
    //         }
    //     })
    // });



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
                } else {
                    let row_id = 0;
                    let new_col = 0;
                    var new_row = "";
                    new_row += "<tr data-row='" + row_id + "'>";
                    new_row += "<td>";
                    new_row += "<input type='text' name='dynamic_field[" + row_id + "][" + new_col + "]' class='form-control form-control-sm'>";
                    new_row += "</td>";
                    new_col++;
                    new_row += "<td>";
                    new_row += "<input type='text' name='dynamic_field[" + row_id + "][" + new_col + "]' class='form-control form-control-sm'>";
                    new_row += "</td>";
                    new_row += "<td>";
                    new_row += "&nbsp;"
                    new_row += "</td>";
                    new_col = 0;
                    new_row += "</tr>";
                    $('#custom-fields').html(new_row);
                }
            }
        })
    });
    // Ajax call ends here

    // Ajax call to get open trf

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
});