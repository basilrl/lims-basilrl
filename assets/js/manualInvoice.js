$(document).ready(function () {
	var base_url = $("body").data("url");
	var _tokken = $('meta[name="_tokken"]').attr("value");
	$(document).on("click", ".manual_invoice", function () {
    $('.ManualInvoiceUpload').trigger('reset');
		var performa = $(this).data("performa");
		var sample = $(this).data("sample");
		$('#manualInvoice input[name="proforma_invoice_id"]').val(performa);
		$('#manualInvoice input[name="sample_reg_id"]').val(sample);
		$.ajax({
			url: base_url + "Manual_Invoice/get_invoice_manual",
			type: "POST",
			data: { _tokken: _tokken, sample_reg_id: sample },
			success: function (result) {
        $('.ManualInvoiceUpload .report_num').html('');
        var data = $.parseJSON(result);
				if (data) {
          $('.ManualInvoiceUpload .report_num').html(data.report_num);
					$.each(data, function (i, v) {
						if (v) {
							$('.ManualInvoiceUpload input[name="' + i + '"]').val(v);
							$('.ManualInvoiceUpload textarea[name="' + i + '"]').html(v);
							$('.ManualInvoiceUpload select[name="' + i + '"]').val(v);
						}
					});
				}
			},
			error: function (e) {
				console.log(e);
			},
		});
	});
	$(document).on("submit", ".ManualInvoiceUpload", function (e) {
		e.preventDefault();
		$.ajax({
			url: base_url + "Manual_Invoice/Upload_invoice",
			type: "POST",
			processData: false,
			contentType: false,
			data: new FormData(this),
			success: function (result) {
				var data = $.parseJSON(result);
				if (data.status > 0) {
					location.reload();
				} else {
					$.notify(data.msg, "error");
				}
				if (data.errors) {
					var error = data.errors;
					$(".upload_invoice").remove();
					$.each(error, function (i, v) {
						$('.ManualInvoiceUpload input[name="' + i + '"]').after(
							'<span class="text-danger upload_invoice">' + v + "</span>"
						);
						$('.ManualInvoiceUpload textarea[name="' + i + '"]').after(
							'<span class="text-danger upload_invoice">' + v + "</span>"
						);
						$('.ManualInvoiceUpload select[name="' + i + '"]').after(
							'<span class="text-danger upload_invoice">' + v + "</span>"
						);
					});
				} else {
					$(".upload_invoice").remove();
				}
			},
			error: function (e) {
				count_upload = 1;
				console.log(e);
			},
		});
	});
});
