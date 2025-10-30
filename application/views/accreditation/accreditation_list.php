<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Accreditation</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">Accreditation</li>
          </ol>
        </div>
      </div>


      <div class="row">
        <div class="col-md-12">
          <div class="row">
            <div class="col-sm-2">
              <select name="country_id" id="country_name" class="form-control form-control-sm">
                <option value="">Select Country</option>
                <?php foreach ($countries as $country) { ?>
                  <option value="<?php echo $country->country_name; ?>" <?php echo ($search['country_name'] == $country->country_name) ? "selected" : ""; ?>> <?php echo $country->country_name; ?> </option>
                <?php } ?>
              </select>
            </div>

            <div class="col-sm-2">
              <select name="branch_id" id="branch_name" class="form-control form-control-sm">
                <option value="">Select Branch</option>
                <?php foreach ($brn_names as $name) { ?>
                  <option value="<?php echo $name->branch_name; ?>" <?php echo ($search['branch_name'] == $name->branch_name) ? "selected" : ""; ?>> <?php echo $name->branch_name; ?> </option>
                <?php } ?>
              </select>
            </div>

            <div class="col-md-2">
              <div class="form-group">
                <input type="text" placeholder="Search by Certificate no" class="form-control form-control-sm" id="certificate_no" value="<?php echo ($search['certificate_no'] != 'NULL') ? ($search['certificate_no']) : ''; ?>">
              </div>
            </div>

            <div class="col-md-3">
              <div class="form-group">
                <button type="button" class="btn btn-primary btn-sm" id="filter_data">Search</button>
                <a href="<?php echo base_url('Accreditation/index'); ?>" class="btn btn-danger btn-sm">Clear</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>
  <?php
  //  echo $search['country_name'];die;
  if ($search['country_name'] != 'NULL') {
    $country_name = base64_encode($search['country_name']);
  } else {
    $country_name  = 'NULL';
  }
  if ($search['branch_name'] != 'NULL') {
    $branch_name = base64_encode($search['branch_name']);
  } else {
    $branch_name = 'NULL';
  }
  if ($search['certificate_no'] != 'NULL') {
    $certificate_no = base64_encode($search['certificate_no']);
  } else {
    $certificate_no  = 'NULL';
  }
  if ($order != "") {
    $order = $order;
  } else {
    $order = "NULL";
  }
  ?>

  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      <!-- /.row -->
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <div class="input-group input-group-sm">
                <a href="<?php echo base_url('Accreditation/add_accreditation'); ?>" class="btn btn-primary" style="float: right;">Add</a>
              </div>
            </div>
            <!-- /.card-header -->
            <input type="hidden" id="order" value="">
            <input type="hidden" id="column" value="">
            <div class="card-body small p-0">
              <table class="table table-sm table-hover content-list">
                <thead>
                  <tr>
                    <th>SL No.</th>
                    <th scope="col"><a href="<?php echo base_url('Accreditation/index/' . $country_name . '/' . $branch_name . '/' . $certificate_no . '/' . 'title' . '/' . $order) ?>">Title</a></th>
                    <th scope="col"><a href="<?php echo base_url('Accreditation/index/' . $country_name . '/' . $branch_name . '/' . $certificate_no . '/' . 'ct.country_name' . '/' . $order) ?>">Country</a></th>
                    <th scope="col"><a href="<?php echo base_url('Accreditation/index/' . $country_name . '/' . $branch_name . '/' . $certificate_no . '/' . 'mb.branch_id' . '/' . $order) ?>">Branch</a></th>
                    <th scope="col"><a href="<?php echo base_url('Accreditation/index/' . $country_name . '/' . $branch_name . '/' . $certificate_no . '/' . 'validity' . '/' . $order) ?>">Validity</a></th>
                    <th scope="col"><a href="<?php echo base_url('Accreditation/index/' . $country_name . '/' . $branch_name . '/' . $certificate_no . '/' . 'acc_standard' . '/' . $order) ?>">Accreditation Standard</a></th>
                    <th scope="col"><a href="<?php echo base_url('Accreditation/index/' . $country_name . '/' . $branch_name . '/' . $certificate_no . '/' . 'certificate_no' . '/' . $order) ?>">Certificate Number</a></th>
                    <th scope="col"><a href="<?php echo base_url('Accreditation/index/' . $country_name . '/' . $branch_name . '/' . $certificate_no . '/' . 'upload_filename' . '/' . $order) ?>">Accreditation Upload</a></th>
                    <th scope="col"><a href="<?php echo base_url('Accreditation/index/' . $country_name . '/' . $branch_name . '/' . $certificate_no . '/' . 'scope_filename' . '/' . $order) ?>">Scope Upload</a></th>
                    <th scope="col"><a href="<?php echo base_url('Accreditation/index/' . $country_name . '/' . $branch_name . '/' . $certificate_no . '/' . 'admin_fname' . '/' . $order) ?>">Created By</a></th>
                    <th scope="col"><a href="<?php echo base_url('Accreditation/index/' . $country_name . '/' . $branch_name . '/' . $certificate_no . '/' . 'uploaded_on' . '/' . $order) ?>">Created On</a></th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if (!empty($accreditation)) {
                    foreach ($accreditation as $key => $value) {
                  ?>
                      <tr>
                        <td><?php echo $start;
                            $start++; ?></td>
                        <td><?php echo $value['title']; ?></td>
                        <td><?php echo $value['country_name']; ?></td>
                        <td><?php echo $value['branch_name']; ?></td>
                        <td><?php echo $value['validity']; ?></td>
                        <td><?php echo $value['acc_standard']; ?></td>
                        <td><?php echo $value['certificate_no']; ?></td>
                        <td><?php echo $value['upload_filename']; ?></td>
                        <td><?php echo $value['scope_filename']; ?></td>
                        <td><?php echo $value['admin_fname']; ?></td>
                        <td><?php echo $value['uploaded_on']; ?></td>
                        <td>
                          <a class="view_log btn btn-sm btn-primary" href="<?php echo base_url('Accreditation/edit_accreditation/?acc_id=' . base64_encode($value['acc_id'])); ?>"> <i class="fas fa-edit"></i></a>
                          <a href="javascript:void(0)" data-bs-toggle="modal" class="log_view btn btn-sm btn-secondary" data-bs-target="#log_modal" title="View Log" data-id="<?php echo $value['acc_id']; ?>"><i class="fa fa-eye" aria-hidden="true" title="View Log"></i></a>
                          <a href="<?php echo base_url('Accreditation/download_file_accreditation/') . 'files/download/' . $value['acc_id']; ?>" class="btn btn-success btn-sm"><i class="fa fa-download"></i></a>


                        </td>
                      </tr>
                    <?php }
                  } else { ?>
                    <tr>
                      <td colspan="10">No record found</td>
                    </tr>
                  <?php } ?>
                </tbody>
              </table>
            </div>
            <!-- Pagination -->
            <div class="card-header">
              <span id="pagination"><?php echo $pagination['links']; ?></span>
              <span id="records"><?php echo $result_count; ?></span>
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

<!-- modal to view accreditation log starts -->
<div class="modal fade" id="log_modal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content modal-lg" style="margin:0 auto">
      <div class="modal-header">
        <h4 class="modal-title" id="myModalLabel">Accreditation Log</h4>
        <input type="hidden" name="id" id="id_cls1">
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-body contact_view">
        <table class="table">
          <thead>
            <tr>
              <th>SL No.</th>
              <th>Operation</th>
              <th>Action Message</th>
              <th>Taken At</th>
              <th>Taken By</th>
            </tr>
          </thead>
          <tbody id="accreditation_log"></tbody>
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<!-- modal to view accreditation log ends -->

<!-- Ajax call to get log -->
<script>
  $(document).ready(function() {

    const url = $('body').data('url');
    const _tokken = $('meta[name="_tokken"]').attr('value');
    // Ajax call to get log
    $('.log_view').click(function() {
      $('#accreditation_log').empty();
      var acc_id = $(this).data('id');
      $.ajax({
        type: 'post',
        url: url + 'Accreditation/get_log_data',
        data: {
          _tokken: _tokken,
          acc_id: acc_id
        },
        success: function(data) {
          var data = $.parseJSON(data);
          var value = '';
          sno = Number();
          $.each(data, function(i, v) {
            sno += 1;
            var operation = v.action_taken;
            var action_message = v.text;
            var taken_by = v.taken_by;
            var taken_at = v.taken_at;
            value += '<tr>';
            value += '<td>' + sno + '</td>';
            value += '<td>' + operation + '</td>';
            value += '<td>' + action_message + '</td>';
            value += '<td>' + taken_by + '</td>';
            value += '<td>' + taken_at + '</td>';
            value += '</tr>';
            $('#accreditation_log').append(value);
          });

        }
      });
    });
    // ajax call to get log ends here

    // Ajax call ends here

    // function to search country, branch, certificate
    $('#filter_data').click(function() {
      var country_name = $('#country_name').val();
      var branch_name = $('#branch_name').val();
      var certificate_no = $('#certificate_no').val();
      var base_url = url + 'Accreditation/index';

      if (country_name != "") {
        base_url = base_url + '/' + btoa(country_name);
      } else {
        base_url = base_url + '/' + 'NULL';
      }

      if (branch_name != "") {
        base_url = base_url + '/' + btoa(branch_name);
      } else {
        base_url = base_url + '/' + 'NULL';
      }
      if (certificate_no != "") {
        base_url = base_url + '/' + btoa(certificate_no);
      } else {
        base_url = base_url + '/' + 'NULL';
      }
      location.href = base_url;
    });
  });
</script>