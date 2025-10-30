<script src="<?php echo base_url('assets/js/test_request_form.js') ?>"></script>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Test Request Form</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">Open TRF</li>
          </ol>
        </div>
      </div>
      <?php //echo "<pre>"; print_r($this->session->userdata());?>
      <div class="row">
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

      </div>
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
                  <?php if (exist_val('Quote_trf/add_quote_trf', $this->session->userdata('permission'))) { ?>
                    <!-- added by Millan on 25-02-2021 -->
                    <a href="<?php echo base_url('Quote_trf/index'); ?>" class="btn btn-primary btn-sm">Quote Trf</a>
                  <?php } ?>
                  <?php if (exist_val('TestRequestForm_Controller/add_open_trf', $this->session->userdata('permission'))) { ?>
                    <!-- added by Millan on 25-02-2021 -->
                    <a href="<?php echo base_url('add-open-trf'); ?>" class="btn btn-primary btn-sm">Open Trf</a>
                  <?php } ?>
                </div>
              </div>
            </div>
            <!-- /.card-header -->
            <input type="hidden" id="order" value="">
            <input type="hidden" id="column" value="">
            <div class="card-body small p-0">
              <table class="table table-hover table-sm" id="open-trf-list">
                <thead>
                  <tr>
                    <th class="sorting" data-one="trf_id" style="cursor:pointer">SL No.</th>
                    <th>TRF Service Type</th>
                    <th class="sorting" data-one="sample_type_name" style="cursor:pointer">Product</th>
                    <th class="sorting" data-one="trf_sample_ref_id" style="cursor:pointer">Sample Reference Id</th>
                    <th class="sorting" data-one="trf_ref_no" style="cursor:pointer">TRF Reference No.</th>
                    <th class="sorting" data-one="trf_regitration_type" style="cursor:pointer">TRF Type</th>
                    <th class="sorting" data-one="customer_name" style="cursor:pointer">Client</th>
                    <th class="sorting" data-one="contact_name" style="cursor:pointer">Contact</th>
                    <th class="sorting" data-one="trf_reg.create_on" style="cursor:pointer">Created On</th>
                    <th class="sorting" data-one="admin_fname" style="cursor:pointer">Created By</th>
                    <th class="sorting" data-one="trf_buyer" style="cursor:pointer">Buyer</th>
                    <th class="sorting" data-one="trf_applicant" style="cursor:pointer">Applicant</th>
                    <th class="sorting" data-one="tat_date" style="cursor:pointer">TAT Date</th>
                    <th class="sorting" data-one="trf_status" style="cursor:pointer">TRF Status</th>
                    <?php if ((exist_val('TestRequestForm_Controller/send_sample_received', $this->session->userdata('permission'))) || (exist_val('TestRequestForm_Controller/add_sample', $this->session->userdata('permission'))) || (exist_val('TestRequestForm_Controller/edit_open_trf', $this->session->userdata('permission'))) || (exist_val('TestRequestForm_Controller/clone_trf', $this->session->userdata('permission')))) { ?>
                      <!-- added by Millan on 25-02-2021 -->
                      <th>Action</th>
                    <?php } ?>
                  </tr>
                </thead>
                <tbody id="open-trf"></tbody>
              </table>

            </div>

            <!-- Pagination -->
            <div class="card-header">
              <span id="open-trf-pagination"></span>
              <span id="open-trf-records"></span>
            </div>
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

<!-- Modal to show TRF logs -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">TRF log</h5>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <table class="table">
          <thead>
            <tr>
              <th>SL.No.</th>
              <th>Operation</th>
              <th>Action</th>
              <th>Performed By</th>
              <th>Performed at</th>
            </tr>
          </thead>
          <tbody id="trf_log"></tbody>
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<!-- Modal to show TRF logs ends here -->
<script>
  $(document).ready(function() {
    const url = $('body').data('url');
    const _tokken = $('meta[name="_tokken"]').attr('value');

    // View Log
    $(document).on('click','.log_view',function() {
      var trf_id = $(this).data('id');
      $('#trf_log').empty();
      $.ajax({
        type: 'post',
        url: url + 'TestRequestForm_Controller/get_log',
        data: {
          _tokken: _tokken,
          trf_id: trf_id
        },
        success: function(data) {
          var data = $.parseJSON(data);
          var value = '';
          sl = Number();
          $.each(data, function(i, v) {
            sl += 1;
            var operation = v.operation;
            var action_message = v.action_message;
            var taken_by = v.taken_by;
            var taken_at = new Date(v.log_activity_on+ ' UTC');
            value += '<tr>';
            value += '<td>' + sl + '</td>';
            value += '<td>' + operation + '</td>';
            value += '<td>' + action_message + '</td>';
            value += '<td>' + taken_by + '</td>';
            value += '<td>' + taken_at.toLocaleString() + '</td>';
            value += '</tr>';
            
          });
          $('#trf_log').append(value);
        }
      });
    });

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

      var applicant_name = $('#applicant_name').val();
      if (applicant_name == null || applicant_name === "") {
        applicant_name = null;
      } else {
        applicant_name = $('#applicant_name').val();
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
      $('#open-trf-records').empty();
      $.ajax({
        url: url + 'TestRequestForm_Controller/open_trf_record/' + page + '/' + trf_reference_number + '/' + customer_name + '/' + product + '/' + created_on + '/' + created_by + '/' + service_type + '/' + column + '/' + order + '/' + buyer + '/' + status + '/' + division+ '/' + applicant_name,
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
      if (result) {
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
        var applicant_name = result[index].applicant_name;
        var updated_by = result[index].updated_by;
        var sample_status = result[index].sample_status;
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
        data += "<td>" + applicant_name + "</td>";
        if (tat_date == "00-00-0000" || tat_date == null) {
          data += "<td>N/A</td>";
        } else {
          data += "<td>" + tat_date + "</td>";
        }
        data += "<td>" + trf_status +"</td>";
        data += '<td>';
        if (trf_status == "New") {
          <?php if (exist_val('TestRequestForm_Controller/send_sample_received', $this->session->userdata('permission'))) { ?> // added by Millan on 25-02-2021
            data += "<a href='" + change_status + "' title='Recieve Sample'><img src = " + url + '/assets/images/Laboratory--Accepted-Sample.png' + "></a>&nbsp;";
          <?php } ?>
        }
        if (trf_status == "Sample Received") {
          <?php if (exist_val('TestRequestForm_Controller/add_sample', $this->session->userdata('permission'))) { ?> // added by Millan on 25-02-2021
            data += "<a href='" + add_sample + "' title='Register Sample'><img src = " + url + '/assets/images/add_sample.png' + "></a>&nbsp;";
          <?php } ?>
        }
        // Condition removed by Saurabh on 18-08-2021 
        // if ((sample_status != 'Queued to Lab') && (sample_status != 'Test ReAssigned') && (sample_status != 'Partial Completed') && (sample_status != 'In Progress') && (sample_status != 'Test ReAssigned') && (sample_status != 'Completed') && (sample_status != 'Report Generated') && (sample_status != 'Report Sent for Approval') && (sample_status != 'Report Rejected') && (sample_status != 'Sample Sent to Reporting') && (sample_status != 'Sample Sent for Manual Reporting') &&(sample_status != 'Evaluation Completed') && (sample_status != 'Send For Record Finding') && (sample_status != 'Report Approved') && (sample_status != 'Hold Sample')) {
        // Condition removed by Saurabh on 18-08-2021 
        <?php if (exist_val('TestRequestForm_Controller/edit_open_trf', $this->session->userdata('permission'))) { ?> // added by Millan on 25-02-2021
          data += "<a href='" + link + "'  title='Edit TRF'><img src = " + url + '/assets/images/edit_jobs.png' + "></a>&nbsp;";
        <?php } ?>
        // } 
        /**-----------------------clone trf---------------- */
        <?php if (exist_val('TestRequestForm_Controller/edit_open_trf', $this->session->userdata('permission'))) { ?> // added by Millan on 25-02-2021
          data += "<a data-id=" + id + " id='clone_trf' title='Clone TRF'><img src = " + url + '/assets/images/active_res.png' + "></a>&nbsp;";
        <?php } ?>
        /**------------------end ----------clone trf---------------- */
        /**-----------------------View trf log---------------- */
        <?php if (exist_val('TestRequestForm_Controller/edit_open_trf', $this->session->userdata('permission'))) { ?> // added by Millan on 25-02-2021
          data += "<a data-id=" + id + " class='log_view' title='View Log' data-bs-toggle='modal' data-bs-target='#exampleModal'><img src = " + url + '/assets/images/log-view.png' + "></a>";
        <?php } ?>
        /**------------------end ----------View trf log---------------- */
        data += '</td>';


        data += "</tr>";
        $('#open-trf-list tbody').append(data);
      }
    } else {
      data = "<tr>";
      data += "<td colspan='14'>No record found</td>";
      data += "</td>";
      $('#open-trf-list tbody').append(data);
    }
    

    }


  });
</script>