$(document).ready(function () {
	var base_url = $("body").attr("data-url");
	var _tokken = $('meta[name="_tokken"]').attr("value");

	var pageno = 0;
	loadPagination(pageno);
	$("#application_pagination").on("click", "a", function (e) {
		e.preventDefault();
		pageno = $(this).attr("data-ci-pagination-page");
		loadPagination(pageno);
	});

	function loadPagination(pagno) {
		var per_page = $('#per_page').val();
		var search = $(".search").val() ? btoa($(".search").val()) : "NULL";
		$.ajax({
			url: base_url + "Invoice_import/listing/"+per_page+"/" + search + "/" + pagno,
			type: "get",
			dataType: "json",
			success: function (response) {
				$("#application_pagination").html(response.pagination);
				createTable(response.result);
			},
		});
	}

	$(document).on("change", "#per_page", function () {
		loadPagination(pageno);
	});
	function createTable(result) {
		$("#application_list").empty();
		$("#application_list").html(result);
	}
	$(document).on("click", ".search_listing", function () {
		loadPagination(0);
	});
	$(document).on("click", ".clear_listing", function () {
		$(".search").val("");
		loadPagination(0);
	});
	$(document).on("click", ".send_mail", function () {
		var id = $(this).data('id');
		$.ajax({
			url: base_url + 'Invoice_import/invoice_details_fetch_mail',
			method: 'POST',
			data: {
				_tokken: _tokken,
				id: id
			},
			success: function (data) {
				$('#emails_set').empty();
				var data = $.parseJSON(data);
				CKEDITOR.instances['text'].setData(data.html);
					if (data.subject) {
						$('.send_mail_perticular input[name=subject]').val(data.subject+'  - Outstanding Payment Status');
					}
				if (data.email) {
					var email = data.email;
					$.each(email, function (i, v) {
						$('#emails_set').append('<div class="col-sm-3"><div class="form-check-inline"><label class="form-check-label email_set_check"><input type="checkbox" class="form-check-input" value="' + v + '">' + v + ' </label></div></div>');
					});
				} else {
					$('#emails_set').html('<div class="col-sm-12"><h2>NO EMAIL FOUND</h2></div>');
				}
			},
			error: function (e) {
				console.log(e);
			}
		});
	});
	$(document).on("submit", ".send_mail_perticular", function () {
		$('body').append('<div class="pageloader"></div>');
		var self = $(this);
		var form_data = new FormData(this);
		$.ajax({
			url: base_url + 'Invoice_import/send_mail',
			method: 'POST',
			data: form_data,
			processData: false,
			contentType: false,
			success: function (data) {
				$('.pageloader').remove();
				$(".mail_error").remove();
				var data = $.parseJSON(data);
				if (data.status > 0) {
					self.trigger('reset');
					$('#send_mail').modal('hide');
					$.notify(data.msg, "success");
				}else{
					$.notify(data.msg, "error");
				}
				if (data.errors) {
					$.each(data.errors, function (i, v) {
						$('#send_mail input[name=' + i + ']').after('<span class="text-danger mail_error">' +v +"</span>");
						$('#send_mail textarea[name=' + i + ']').after('<span class="text-danger mail_error">' +v +"</span>");
					  });
				}
			},
			error: function (e) {
				$('.pageloader').remove();
				console.log(e);
			}
		});
	});

	$(document).on("click", ".send_mail", function () {
		var id = $(this).data('id');
		$('.send_mail_perticular input[name=id]').val(id);
	});
	$(document).on("change", ".send_mail_perticular input:checkbox", function (e) {
		var chkd = $('input:checkbox:checked');
		var vals = chkd.map(function () {
				return this.value;
			}).get().join(', ');
		if (vals) {
			$('.send_mail_perticular input[name=email]').val(vals);
		}else{
			$('.send_mail_perticular input[name=email]').val('');
		}

	});
	$(document).on("click", ".edit_role", function () {
		var id = $(this).data('id');
		$.ajax({
			url: base_url + 'Invoice_import/invoice_details_fetch',
			method: 'POST',
			data: {
				_tokken: _tokken,
				id: id
			},
			success: function (data) {
				var data = $.parseJSON(data);
				$('#set_invoice_Details').empty().html(data);
			},
			error: function (e) {
				console.log(e);
			}
		});
	});
	var add_role = 1;
	var details = {};
	$(document).on("submit", ".add_submit", function (e) {
		e.preventDefault();
		$('body').append('<div class="pageloader"></div>');
		excel_data();
		$('#add_application').modal('hide');
	});
	$(document).on("submit", ".add_invoice_submit", function (e) {
		e.preventDefault();
		$('body').append('<div class="pageloader"></div>');
		excel_data1();
		$('#email_flag').modal('hide');
	});


	function excel_data() {
		//Reference the FileUpload element.
		var fileUpload = $("#excelfile")[0];
		//Validate whether File is valid Excel file.
		var regex = /^([a-zA-Z0-9\s_\\.\-():])+(.xls|.xlsx)$/;
		if (regex.test(fileUpload.value.toLowerCase())) {
			if (typeof (FileReader) != "undefined") {
				var reader = new FileReader();

				//For Browsers other than IE.
				if (reader.readAsBinaryString) {
					reader.onload = function (e) {
						ProcessExcel(e.target.result);
					};
					reader.readAsBinaryString(fileUpload.files[0]);
				} else {
					//For IE Browser.
					reader.onload = function (e) {
						var data = "";
						var bytes = new Uint8Array(e.target.result);
						for (var i = 0; i < bytes.byteLength; i++) {
							data += String.fromCharCode(bytes[i]);
						}
						ProcessExcel(data);
					};
					reader.readAsArrayBuffer(fileUpload.files[0]);
				}
			} else {
				alert("This browser does not support HTML5.");
			}
		} else {
			alert("Please upload a valid Excel file.");
		}
	}

	function ProcessExcel(data) {
		//Read the Excel File data.
		var workbook = XLSX.read(data, {
			type: 'binary'
		});

		//Fetch the name of First Sheet.
		var firstSheet = workbook.SheetNames[0];
		//Read all rows from First Sheet into an JSON array.
		var excelRows = XLSX.utils.sheet_to_row_object_array(workbook.Sheets[firstSheet]);
		var html = '';
		// console.log(excelRows.length);
		if (excelRows.length > 0) {
			$.ajax({
				url: base_url + 'Invoice_import/import_details',
				method: 'POST',
				data: {
					_tokken: _tokken,
					excel: JSON.stringify(excelRows)
				},
				success: function (data) {
					$('.pageloader').remove();
					var data = $.parseJSON(data);
					if (data.status > 0) {
						$('#test').modal('hide');
						$.notify(data.msg, "success");
						loadPagination(pageno);
					} else {
						$.notify(data.msg, "error");
					}
				},
				error: function (e) {
					console.log(e);
				}
			});
		} else {
			$('.pageloader').remove();
			console.log(e);
		}
	};

	function excel_data1() {
		//Reference the FileUpload element.
		var fileUpload = $("#invoice_details")[0];
		//Validate whether File is valid Excel file.
		var regex = /^([a-zA-Z0-9\s_\\.\-():])+(.xls|.xlsx)$/;
		if (regex.test(fileUpload.value.toLowerCase())) {
			if (typeof (FileReader) != "undefined") {
				var reader = new FileReader();

				//For Browsers other than IE.
				if (reader.readAsBinaryString) {
					reader.onload = function (e) {
						ProcessExcel1(e.target.result);
					};
					reader.readAsBinaryString(fileUpload.files[0]);
				} else {
					//For IE Browser.
					reader.onload = function (e) {
						var data = "";
						var bytes = new Uint8Array(e.target.result);
						for (var i = 0; i < bytes.byteLength; i++) {
							data += String.fromCharCode(bytes[i]);
						}
						ProcessExcel1(data);
					};
					reader.readAsArrayBuffer(fileUpload.files[0]);
				}
			} else {
				alert("This browser does not support HTML5.");
			}
		} else {
			alert("Please upload a valid Excel file.");
		}
	}

	function ProcessExcel1(data) {
		//Read the Excel File data.
		var workbook = XLSX.read(data, {
			type: 'binary'
		});

		//Fetch the name of First Sheet.
		var firstSheet = workbook.SheetNames[0];
		//Read all rows from First Sheet into an JSON array.
		var excelRows = XLSX.utils.sheet_to_row_object_array(workbook.Sheets[firstSheet]);
		var html = '';
		console.log(JSON.stringify(excelRows));
		if (excelRows.length > 0) {
			$.ajax({
				url: base_url + 'Invoice_import/invoice_details',
				method: 'POST',
				data: {
					_tokken: _tokken,
					excel: JSON.stringify(excelRows)
				},
				success: function (data) {
					$('.pageloader').remove();
					var data = $.parseJSON(data);
					if (data.status > 0) {
						$('#test').modal('hide');
						$.notify(data.msg, "success");
						loadPagination(pageno);
					} else {
						$.notify(data.msg, "error");
					}
				},
				error: function (e) {
					console.log(e);
				}
			});
		} else {
			$('.pageloader').remove();
			console.log(e);
		}
	};
});
