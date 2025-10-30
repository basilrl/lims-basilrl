<script src="<?php echo base_url('assets/js/test_request_form.js') ?>"></script>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Buyer Fields</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">Buyer Fields</li>
          </ol>
        </div>
      </div>
			<div class="row">
				<div class="col-md-12">
					<input type="text" id="searchBox" placeholder="Search customer..." class="form-control mb-3">
				</div>
			</div>
      <?php //echo "<pre>"; print_r($this->session->userdata());?>
      <!-- <div class="row">
        <div class="col-md-12">
          <form action="">
            <div class="row">
              <div class="col-md-3">
                <div class="form-group">
                  <input type="text" placeholder="TRF Reference Number" class="form-control" id="trf_reference_number">
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <select name="" class="select-box" class="form-control" id="customer_name">
                    <option selected value="">Select Customer</option>
                    <?php if (!empty($customer)) {
                      foreach ($customer as $value) { ?>
                        <option value="<?php echo $value->customer_id; ?>"><?php echo $value->customer_name ?></option>
                    <?php }
                    } ?>
                  </select>
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <select name="" class="select-box" class="form-control" id="applicant_name">
                    <option selected value="">Select Applicant</option>
                    <?php if (!empty($customer)) {
                      foreach ($customer as $value) { ?>
                        <option value="<?php echo $value->customer_id; ?>"><?php echo $value->customer_name ?></option>
                    <?php }
                    } ?>
                  </select>
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <select name="" class="select-box" class="form-control" id="product">
                    <option selected value="">Select Product</option>
                    <?php if (!empty($products)) {
                      foreach ($products as $value) { ?>
                        <option value="<?php echo $value['sample_type_id']; ?>"><?php echo $value['sample_type_name'] ?></option>
                    <?php }
                    } ?>
                  </select>
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <select name="" class="select-box" class="form-control" id="buyer">
                    <option selected value="">Select Buyer</option>
                    <?php if (!empty($buyer)) {
                      foreach ($buyer as $value) { ?>
                        <option value="<?php echo $value->customer_id; ?>"><?php echo $value->customer_name; ?></option>
                    <?php }
                    } ?>
                  </select>
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <select class="select-box" class="form-control form-control-sm" name="division" id="division">
                    <option selected value="">Select Division</option>
                    <?php if (!empty($division)) {
                      foreach ($division as $value) { ?>
                        <option value="<?php echo $value['division_id']; ?>"><?php echo $value['division_name'] ?></option>
                    <?php }
                    } ?>
                  </select>
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <select name="" class="select-box" class="form-control" id="status">
                    <option selected value="">Select Status</option>
                    <option value="New">New</option>
                    <option value="Sample Received">Sample Received</option>
                    <option value="Sample Registered">Sample Registered</option>
                  </select>
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <input type="text" placeholder="Created On" class="form-control" id="created_on">
                </div>
              </div>
              <div class="col-md-3" style="display:none">
                <div class="form-group">
                  <input type="text" placeholder="Created By" class="form-control" id="created_by">
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
                  <button type="button" id="search-trf" class="btn btn-primary">Search</button>
                  <button type="button" class="btn btn-danger" onclick="location.href='<?= base_url('open-trf-list'); ?>'">Clear</button>
                </div>
              </div>
            </div>
          </form>
        </div>

      </div> -->
    </div><!-- /.container-fluid -->
  </section>

  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      <!-- /.row -->
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <div class="row">
                <div class="col-sm-6">  
									<!-- Yogesh buyer custom filed - 20-06-2025 -->
                  <?php if (exist_val('Buyer/create', $this->session->userdata('permission'))) { ?>
                    <a href="<?php echo base_url('Buyer/create'); ?>" class="btn btn-primary btn-sm">Add New</a>
                  <?php } ?>
                </div>
								<div class="col-sm-6 d-flex justify-content-end mb-3">
									<label for="limitSelect">Records per page:</label>
										<select id="limitSelect" class="form-select w-auto d-inline-block mb-2">
											<option value="10">10</option>
											<option value="50" selected>50</option>
											<option value="100">100</option>
											<option value="200">200</option>
											<option value="500">500</option>
										</select>
								</div>
              </div>
            </div>
            <!-- /.card-header -->
            <input type="hidden" id="order" value="">
            <input type="hidden" id="column" value="">
            <div class="card-body small p-0">
              <table class="table table-hover table-sm" id="buyer-list">
                <thead>
                  <tr>
                    <th class="sorting" data-one="trf_id" style="cursor:pointer">SL No.</th>
                    <th>Buyer Name</th>
                    <th class="sorting" data-one="admin_fname" style="cursor:pointer">Created By</th>
                    <th class="sorting" data-one="trf_reg.create_on" style="cursor:pointer">Created On</th>
                    <th class="sorting" data-one="admin_fname" style="cursor:pointer">Status</th>
                   
                      <!-- added by Millan on 25-02-2021 -->
                      <th>Action</th>
                  </tr>
                </thead>
                <tbody id="buyer-list"></tbody>
              </table>
            </div>

            <!-- Pagination -->
						<div id="result_info" class="text-muted mb-2 text-end small"></div>
           	<div id="application_pagination" class="text-center mt-3"></div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->
        </div>
      </div>
      <!-- /.row -->
    </div><!-- /.container-fluid -->
  </section>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<!-- script -->
<script>
// $(document).ready(function () {
// 	const baseUrl = "<?= base_url(); ?>";

// 	$.ajax({
// 		url: baseUrl + 'Buyer/fetch_All',
// 		type: 'GET',
// 		dataType: 'json',
// 		success: function (response) {
// 			let result = response.data || []; // use 'data' key returned from PHP
// 			let sno = 0;
// 			load_data(result, sno);
// 		},
// 		error: function () {
// 			alert('Failed to fetch data from server.');
// 		}
// 	});

// 	function load_data(result, sno) {
// 		sno = Number(sno);
// 		$('#buyer-list tbody').empty();

// 		if (result && result.length > 0) {
// 			result.forEach(function (item, index) {
// 				let buyer_field_id = item.buyer_field_id || '';
// 				let customer_name = item.customer_name || '';
// 				let created_on = item.created_on || '';
// 				let created_by = item.created_by || 'N/A';

// 				// console.log(item.custom_field_id);
// 				sno += 1;

// 				let row = "<tr>";
// 				row += "<td>" + sno + "</td>";
// 				row += "<td>" + customer_name + "</td>";
// 				row += "<td>" + created_on + "</td>";
// 				row += "<td>" + created_by + "</td>";
// 				row += `<td>
// 							<a href="${baseUrl}Buyer/edit_buyer/${buyer_field_id}" title="EDIT" class="btn btn-sm edit_role" style="margin-left:-21px">
// 								<img width="18px" src="https://uat.lims.basilrl.com/public/img/icon/edit.png" alt="BASIL" class="edit_application_data">
// 							</a>
// 							<a href="<?= base_url('Buyer/delete/') ?>${buyer_field_id}" title="Delete" class="btn btn-sm delete">
// 								<img width="18px" src="	https://uat.lims.basilrl.com/assets/images/cancel.png" alt="BASIL" class="edit_application_data" style="margin-left:-21px">
// 							</a>
// 						</td>`;
// 				row += "</tr>";


// 				$('#buyer-list tbody').append(row);
// 			});
// 		} else {
// 			let noData = "<tr><td colspan='5'>No record found</td></tr>";
// 			$('#buyer-list tbody').append(noData);
// 		}
// 	}
// });

$(document).ready(function () {
	const baseUrl = "<?= base_url(); ?>";
	
	let currentPage = 1;
	let limit = parseInt($('#limitSelect').val()) || 50;

	loadBuyers();

	function loadBuyers(search = '', page = 1) {
		$.ajax({
			url: `${baseUrl}Buyer/fetch_All`,
			type: 'GET',
			data: { search: search, page: page, limit: limit },
			dataType: 'json',
			success: function (response) {
				renderTable(response.data);
				renderPagination(response.total, response.page);
			},
			error: function () {
				alert('Failed to fetch data');
			}
		});
	}

	function renderTable(data) {
		let tbody = $('#buyer-list tbody');
		tbody.empty();

		let sno = (currentPage - 1) * limit;

		if (data.length > 0) {
			$.each(data, function (i, item) {
				let status = item.status;

				// Assign status badge based on value
				if (item.status == 0) {
					statusBadge = "<span class='badge badge-success'>Active</span>";
				} else if (item.status == 1) {
					statusBadge = "<span class='badge badge-warning'>Inactive</span>";
				} else if (item.status == 2) {
					statusBadge = "<span class='badge badge-danger'>Deleted</span>";
				} else {
					statusBadge = "<span class='badge badge-secondary'>Unknown</span>";
				}
				tbody.append(`
					<tr>
						<td>${++sno}</td>
						<td>${item.customer_name}</td>
						<td>${item.created_by}</td>
						<td>${item.created_on}</td>
						<td>${statusBadge}</td>
						<td>
							<a href="${baseUrl}Buyer/edit_buyer/${item.buyer_field_id}" title="EDIT" class="btn btn-sm edit_role" style="margin-left:-21px">
								<img width="18px" src="https://lims.basilrl.com/public/img/icon/edit.png" alt="BASIL" class="edit_application_data">
							</a>
							<a href="${baseUrl}Buyer/delete/${item.buyer_field_id}" title="Delete" class="btn btn-sm delete">
								<img width="18px" src="https://lims.basilrl.com/assets/images/cancel.png" alt="BASIL" class="edit_application_data" style="margin-left:-21px">
							</a>
						</td>
					</tr>
				`);
			});
		} else {
			tbody.append(`<tr><td colspan="5" class="text-center">No records found</td></tr>`);
		}
	}

	function renderPagination(total, currentPage) {
		let totalPages = Math.ceil(total / limit);
		let html = '';

		if (totalPages <= 1) {
			$('#application_pagination').html('');
			return;
		}

		html += `<ul class="pagination justify-content-center">`;

		// First and Previous
		if (currentPage > 1) {
			html += `<li class="page-item"><a class="page-link" href="#" data-page="1">&laquo;</a></li>`;
			html += `<li class="page-item"><a class="page-link" href="#" data-page="${currentPage - 1}">&lt;</a></li>`;
		}

		let range = 2;
		let start = Math.max(1, currentPage - range);
		let end = Math.min(totalPages, currentPage + range);

		if (start > 1) {
			html += `<li class="page-item"><a class="page-link" href="#" data-page="1">1</a></li>`;
			if (start > 2) {
				html += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
			}
		}

		for (let i = start; i <= end; i++) {
			html += `<li class="page-item ${i === currentPage ? 'active' : ''}">
						<a class="page-link" href="#" data-page="${i}">${i}</a>
					</li>`;
		}

		if (end < totalPages) {
			if (end < totalPages - 1) {
				html += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
			}
			html += `<li class="page-item"><a class="page-link" href="#" data-page="${totalPages}">${totalPages}</a></li>`;
		}

		// Next and Last
		if (currentPage < totalPages) {
			html += `<li class="page-item"><a class="page-link" href="#" data-page="${currentPage + 1}">&gt;</a></li>`;
			html += `<li class="page-item"><a class="page-link" href="#" data-page="${totalPages}">&raquo;</a></li>`;
		}

		html += `</ul>`;

		$('#application_pagination').html(html);
	}

	// Pagination click
	$(document).on('click', '.page-link', function (e) {
		e.preventDefault();
		const selectedPage = parseInt($(this).data('page'));
		if (!isNaN(selectedPage)) {
			currentPage = selectedPage;
			loadBuyers($('#searchBox').val(), currentPage);
		}
	});

	// Search handler
	$('#searchBox').on('keyup', function () {
		const searchVal = $(this).val();
		currentPage = 1;
		loadBuyers(searchVal, currentPage);
	});

	// Limit dropdown change
	$('#limitSelect').on('change', function () {
		limit = parseInt($(this).val());
		currentPage = 1;
		loadBuyers($('#searchBox').val(), currentPage);
	});
});
</script>
