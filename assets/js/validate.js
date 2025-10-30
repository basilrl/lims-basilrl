$(function() {
    // Validate open trf form
    const url = $('body').data('url');
    var vaild = $("form[name='open-trf']").validate({
        // Specify rules for validation
        rules: {
            trf_customer_type: "required",
            open_trf_customer_id: "required",
            trf_service_type: "required",
            trf_applicant: "required",
            // trf_buyer: "required",
            trf_contact: "required",
            trf_sample_desc: "required",
            invoice_to: "required",
            trf_invoice_to_contact: "required",
            // trf_country_destination: "required",
            open_trf_currency_id: "required",
            open_trf_exchange_rate: "required",
            // trf_country_orgin: "required",
            trf_product: "required",
            // trf_end_use: "required",
            crm_user_id: "required",
            division: "required",
            trf_no_of_sample: "required",
            "test[]": "required"
        },
        // Specify validation error message
        messages: {
            trf_customer_type: "Please select Customer Type",
            open_trf_customer_id: "Please select Customer",
            trf_service_type: "Select Service",
            trf_applicant: "Select Applicant",
            // trf_buyer: "Seelct Buyer",
            trf_contact: "Select contact person",
            trf_sample_desc: "Enter sample description",
            invoice_to: "Select Invoice to",
            trf_invoice_to_contact: "Select Invoice to contact",
            open_trf_currency_id: "Select Currency",
            open_trf_exchange_rate: "Select Currency First",
            // trf_country_orgin: "Select Origin Country",
            trf_product: "Select Product",
            // trf_end_use: "Enter End Use",
            crm_user_id: "CRM User is required",
            division: "Select Division",
            trf_no_of_sample: "Enter No. of sample",
            "test[]": "Select Tests"
        }
    });
    // Updated by Saurabh on 02-08-2021
    $('#open-trf').submit(function(e) {
        e.preventDefault();
        if (vaild) {
            $('#submit').html('Wait...');
            $('#submit').attr('disabled', 'disabled');
            $('body').append('<div class="pageloader"></div>');
            var form_data = new FormData(this);

            $.ajax({
                type: 'post',
                url: $(this).attr('action'),
                processData: false,
                contentType: false,
                cache: false,
                async: false,
                data: new FormData(this),
                success: function(data) {
                    var data = $.parseJSON(data);
                    $('.pageloader').remove();
                    $('.errors_msg').remove();
                    if (data.status > 0) {
                        $.notify(data.message, "success");
                        window.setTimeout(function() {
                            window.location.replace(url + 'open-trf-list');
                        }, 1000);
                    } else {
                        $.notify(data.message, "error");
                        $('#submit').html('Save');
                        $('#submit').attr('disabled', false);
                    }
                    if (data.error) {
                        $.each(data.error, function(i, v) {
                            $.notify(v, "error");
                            $('#open-trf input[name="' + i + '"]').after('<span class="text-danger errors_msg">' + v + '</span>');
                            $('#open-trf select[name="' + i + '"]').after('<span class="text-danger errors_msg">' + v + '</span>');
                            $('#submit').html('Save');
                            $('#submit').attr('disabled', false);
                        });
                    }
                }
            });
        }
    });
});