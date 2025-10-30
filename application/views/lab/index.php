  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <section class="content-header">
      <!-- container fluid start -->
      <div class="container-fluid">
        <div class="row">
          <div class="col-sm-12 text-center">
          <div class="float-left mt-3">
          <?php if (exist_val('Lab/add_lab', $this->session->userdata('permission'))) { ?>
            <a href="<?php echo base_url('Lab/add_lab'); ?>" class="btn btn-sm btn-primary "> <i class="fa fa-plus"></i> Add</a>
                                          <?php } ?>
                                      </div>
            <h1 class="text-bold mt-3 mb-3">LABS</h1>
          </div>
        </div>
      </div>
      <div class="container-fluid jumbotron p-3">
      <div class="row">
                  <?php if ($id_lab) {
                    $lab_id = $id_lab;
                  } else {
                    $lab_id = 0;
                  } ?>
                  <div class="col-sm-2">
                    <select name="lab_id" id="lab_id" class="form-control form-control-sm">
                      <option value="">Select Lab</option>
                      <?php foreach ($lb_name as $name) { ?>
                        <option value="<?php echo $name->lab_id; ?>" <?php echo ($lab_id == $name->lab_id) ? "selected" : ""; ?>> <?php echo $name->lab_name; ?> </option>
                      <?php } ?>
                    </select>
                  </div>
                  <?php if ($id_lab_type) {
                    $lab_type_id = $id_lab_type;
                  } else {
                    $lab_type_id = 0;
                  } ?>
                  <div class="col-sm-2">
                    <select name="lab_type_id" id="lab_type_id" class="form-control form-control-sm">
                      <option value="">Select Lab Type</option>
                      <?php foreach ($lab_types as $ltype) { ?>
                        <option value="<?php echo $ltype->lab_type_id; ?>" <?php echo ($lab_type_id == $ltype->lab_type_id) ? "selected" : ""; ?>> <?php echo $ltype->lab_type_name; ?> </option>
                      <?php } ?>
                    </select>
                  </div>
                  <?php if ($id_division) {
                    $division_id = $id_division;
                  } else {
                    $division_id = 0;
                  } ?>
                  <div class="col-sm-2">
                    <select name="division_id" id="division_id" class="form-control form-control-sm">
                      <option value="">Select Division</option>
                      <?php foreach ($divisions as $divns) { ?>
                        <option value="<?php echo $divns->division_id; ?>" <?php echo ($division_id == $divns->division_id) ? "selected" : ""; ?>> <?php echo $divns->division_name; ?> </option>
                      <?php } ?>
                    </select>
                  </div>
                  <?php if ($id_branch) {
                    $branch_id = $id_branch;
                  } else {
                    $branch_id = 0;
                  } ?>
                  <div class="col-sm-2">
                    <select name="branch_id" id="branch_id" class="form-control form-control-sm">
                      <option value="">Select Branch</option>
                      <?php foreach ($brn_names as $brnch) { ?>
                        <option value="<?php echo $brnch->branch_id; ?>" <?php echo ($branch_id == $brnch->branch_id) ? "selected" : ""; ?>> <?php echo $brnch->branch_name; ?> </option>
                      <?php } ?>
                    </select>
                  </div>
                  <?php if ($created_pesron) {
                    $uidnr_admin = $created_pesron;
                  } else {
                    $uidnr_admin = "";
                  } ?>
                  <!-- <div class="col-sm-2">
                    <select name="created_by" id="created_by" class="form-control form-control-sm">
                      <option value="">Select Created By</option>
                      <?php foreach ($created_by_name as $cr_name) { ?>
                        <option value="<?php echo $cr_name->uidnr_admin; ?>" <?php echo ($uidnr_admin == $cr_name->uidnr_admin) ? "selected" : ""; ?>> <?php echo $cr_name->created_by; ?> </option>
                      <?php } ?>
                    </select>
                  </div> -->
                  <div class="col-sm-2">
                    <select name="status" id="status" class="form-control form-control-sm">
                      <option value="">Select Status</option>
                      <option value="1" <?php echo (($id_status == "1") ? "selected" : "") ; ?> > Active </option>
                      <option value="0" <?php echo (($id_status == "0") ? "selected" : "") ; ?> > DeActive </option>
                    </select>
                  </div> 
                 
                  <div class="col-sm-2">
                    <div class="input-group">
                    <input value="<?php echo (($search != 'NULL') ? $search : ""); ?>" id="search" class="form-control form-control-sm" type="text" placeholder="Search" aria-label="Search">
                    <div class="input-group-append">
                    <button onclick="searchfilter();" type="button" class="btn btn-sm btn-secondary" title="Search">
                      <i class="fa fa-search"></i>
                    </button>
                    <a class="btn btn-sm btn-danger ml-1" href="<?php echo base_url('Lab'); ?>" title="Clear">
                      <i class="fa fa-trash"></i>
                    </a>
                    </div>
                  </div></div>
                 
                </div>
      </div>
      <div class="container-fluid">
     
        <div class="row">
          <div class="col-12">
            <div class="card">
              
              <!-- end card header -->

              <div class="table-responsive p-2">
                <table class="table table-sm">
                  <thead>
                    <tr>
                      <?php
                      if ($search) {
                        $search = base64_encode($search);
                      } else {
                        $search = "NULL";
                      }

                      if ($id_lab != "NULL") {
                        $id_lab = base64_encode($id_lab);
                      } else {
                        $id_lab = "NULL";
                      }
                      if ($id_lab_type != "NULL") {
                        $id_lab_type = base64_encode($id_lab_type);
                      } else {
                        $id_lab_type = "NULL";
                      }
                      if ($id_division != "NULL") {
                        $id_division = base64_encode($id_division);
                      } else {
                        $id_division = "NULL";
                      }
                      if ($id_branch != "NULL") {
                        $id_branch = base64_encode($id_branch);
                      } else {
                        $id_branch = "NULL";
                      }
                      if ($created_pesron != "NULL") {
                        $created_pesron = base64_encode($created_pesron);
                      } else {
                        $created_pesron = "NULL";
                      }
                      if ($order != "") {
                        $order = $order;
                      } else {
                        $order = "NULL";
                      }
                      ?>
                      <th scope="col">S. NO.</th>
                      <th scope="col"><a href="<?php echo base_url('Lab/index/' . $id_lab . '/' . $id_lab_type . '/' . $id_division . '/' . $id_branch . '/' . $created_pesron  . '/' .  $id_status .'/' . $search . '/' . 'msl.lab_name' . '/' . $order) ?>">LAB NAME</a></th>
                      <th scope="col"><a href="<?php echo base_url('Lab/index/' . $id_lab . '/' . $id_lab_type . '/' . $id_division .  '/' . $id_branch . '/' . $created_pesron . '/' .  $id_status .'/' . $search . '/' . 'mlt.lab_type_name' . '/' . $order) ?>">LAB TYPE</a></th>
                      <th scope="col"><a href="<?php echo base_url('Lab/index/' . $id_lab . '/' . $id_lab_type . '/' . $id_division . '/' . $id_branch . '/' . $created_pesron . '/' .  $id_status .'/' . $search . '/' . 'msd.division_name' . '/' . $order) ?>">DIVISION</a></th>
                      <th scope="col"><a href="<?php echo base_url('Lab/index/' . $id_lab . '/' . $id_lab_type . '/' . $id_division . '/' . $id_branch . '/' . $created_pesron . '/' .  $id_status .'/' . $search . '/' . 'msb.branch_name' . '/' . $order) ?>">BRANCH</a></th>
                      <?php if ((exist_val('Lab/lab_status', $this->session->userdata('permission')))) { ?>
                        <th scope="col"><a href="<?php echo base_url('Lab/index/' . $id_lab . '/' . $id_lab_type . '/' . $id_division . '/' . $id_branch . '/' . $created_pesron . '/' .  $id_status .'/' . $search . '/' . 'msl.status' . '/' . $order) ?>">STATUS</a></th>
                      <?php } ?>
                      <th scope="col"><a href="<?php echo base_url('Lab/index/' . $id_lab . '/' . $id_lab_type . '/' . $id_division . '/' . $id_branch . '/' . $created_pesron . '/' .  $id_status . '/' . $search . '/' . 'ap.admin_fname' . '/' . $order) ?>">CREATED BY</a></th>
                      <th scope="col"><a href="<?php echo base_url('Lab/index/' . $id_lab . '/' . $id_lab_type . '/' . $id_division . '/' . $id_branch . '/' . $created_pesron . '/' .  $id_status . '/' . $search . '/' . 'msl.created_on' . '/' . $order) ?>">CREATED ON</a></th>
                      <?php if ((exist_val('Lab/edit_lab', $this->session->userdata('permission'))) || (exist_val('Lab/get_lab_log', $this->session->userdata('permission')))) { ?>
                        <th scope="col">ACTION</th>
                      <?php } ?>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    if ($labs_list) {
                      if (empty($this->uri->segment(12)))
                        $i = 1;
                      else
                        $i = $this->uri->segment(12) + 1;
                      foreach ($labs_list as $value) {
                    ?>
                        <tr>
                          <td><?php echo $i; ?></td>
                          <td><?php echo $value->lab_name ?></td>
                          <td><?php echo ($value->lab_type_name) ? $value->lab_type_name : 'N/A';  ?></td>
                          <td><?php echo ($value->division_name) ? $value->division_name : 'N/A'; ?></td>
                          <td><?php echo ($value->branch_name) ? $value->branch_name : 'N/A'; ?></td>
                          <?php if ((exist_val('Lab/lab_status', $this->session->userdata('permission')))) { ?>
                            <?php if ($value->status == 1) { ?>
                              <td><span class="btn btn-sm btn-success status_chng" id="active" data-id="<?php echo $value->lab_id; ?>">Active</span></td>
                            <?php } else { ?>
                              <td><span class="btn btn-sm btn-danger status_chng" id="deactive" data-id="<?php echo $value->lab_id; ?>">Deactive</span></td>
                            <?php } ?>
                          <?php } ?>
                          <td><?php echo $value->admin_fname ?></td>
                          <td><?php echo change_time($value->created_on,$this->session->userdata('timezone')); ?></td>
                          <td>
                            <?php if ((exist_val('Lab/edit_lab', $this->session->userdata('permission')))) { ?>
                              <a href="<?php echo base_url('Lab/edit_lab/' . base64_encode($value->lab_id)); ?>" class="btn btn-sm"><i class="fa fa-edit" title="edit"></i></a>
                            <?php } ?>

                            <?php if ((exist_val('Lab/get_lab_log', $this->session->userdata('permission')))) { ?>
                              <a href="javascript:void(0)" data-id="<?php echo $value->lab_id; ?>" class="btn log_view" data-bs-toggle="modal" data-bs-target="#exampleModal" title="Log View"><i class="fa fa-eye" title="view log"></i></i></a>
                            <?php } ?>
                          </td>
                      <?php $i++;
                      }
                    } ?>
                        </tr>
                  </tbody>

                </table>
              </div>
            </div>
            <!-- card end -->
          </div>
        </div>

        <!-- menu end -->

        <div class="card-header">
          <?php if ($labs_list && count($labs_list) > 0) { ?>
            <span><?php echo $links ?></span>
            <span><?php echo $result_count; ?></span>
          <?php } else { ?>
            <h3>NO RECORD FOUND</h3>
          <?php } ?>
        </div>
      </div>
      <!-- container fluid end -->
    </section>
  </div>
  <!-- /.content-wrapper -->

  <!-- Lab Log Modal Starts -->
  <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Lab Log</h5>
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
            <tbody id="lab_log"></tbody>
          </table>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
  <!-- Lab Log Modal Ends -->

  <script>
  function searchfilter() {
      var base_url = "<?php echo base_url('Lab/index'); ?>"
      var lab_id = $('#lab_id').val();
      var lab_type_id = $('#lab_type_id').val();
      var division_id = $('#division_id').val();
      var branch_id = $('#branch_id').val();
      var created_by = $('#created_by').val();
      var id_status = $('#status').val();
      var search = $('#search').val();
      if (lab_id) {
        base_url = base_url + '/' + btoa(lab_id);
      } else {
        base_url = base_url + '/' + 'NULL';
      }
      if (lab_type_id) {
        base_url = base_url + '/' + btoa(lab_type_id);
      } else {
        base_url = base_url + '/' + 'NULL';
      }
      if (division_id) {
        base_url = base_url + '/' + btoa(division_id);
      } else {
        base_url = base_url + '/' + 'NULL';
      }
      if (branch_id) {
        base_url = base_url + '/' + btoa(branch_id);
      } else {
        base_url = base_url + '/' + 'NULL';
      }
      if (created_by) {
        base_url = base_url + '/' + btoa(created_by);
      } else {
        base_url = base_url + '/' + 'NULL';
      }

      if (id_status) {
        base_url = base_url + '/' + btoa(id_status);
      } else {
          id_status = "";
          base_url = base_url + '/NULL';
      }

      if (search) {
        base_url = base_url + '/' + btoa(search);
      } else {
        base_url = base_url + '/' + 'NULL';
      }
      location.href = base_url;
    }
    $(document).ready(function() {

      var style = {
        "margin": "0 auto"
      };
      $('.modal-content').css(style);

      // status change
      $('.status_chng').click(function() {
        var lab_id = $(this).attr("data-id");
        var _tokken = $('meta[name="_tokken"]').attr('value');
        $.ajax({
          url: "<?php echo base_url('Lab/lab_status'); ?>",
          data: {
            "lab_id": lab_id,
            "_tokken": _tokken
          },
          type: 'post',
          success: function(result) {
            location.reload();
          }
        });
      });
      // end

      // Lab Type Log
      $('.log_view').click(function(e) {
        e.preventDefault();
        $('#lab_log').empty();
        var lab_id = $(this).data('id');
        const _tokken = $('meta[name="_tokken"]').attr("value");
        $.ajax({
          type: 'post',
          url: "<?php echo base_url('Lab/get_lab_log') ?>",
          data: {
            _tokken: _tokken,
            lab_id: lab_id
          },
          success: function(data) {
            var data = $.parseJSON(data);
            if (data) {
              sn = 1;
              $.each(data, function(index, log) {
                var taken_at = new Date(log.taken_at+ ' UTC');
                var value = '';
                value += '<tr>';
                value += '<td>' + sn + '</td>';
                value += '<td>' + log.action_taken + '</td>';
                value += '<td>' + log.text + '</td>';
                value += '<td>' + log.taken_by + '</td>';
                value += '<td>' + taken_at.toLocaleString() + '</td>';
                value += '</tr>';
                $('#lab_log').append(value);
                sn++;
              });
            } else {
              var value = '';
              value += '<tr>';
              value += '<td colspan="5">';
              value += "<h4> NO RECORD FOUND! </h4>";
              value += "</td>";
              value += "</tr>";
              $('#lab_log').append(value);
            }
          }
        });
        // return false;
      });
      //ends


    });
  </script>