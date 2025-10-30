  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <section class="content-header">
      <!-- container fluid start -->
      <div class="container-fluid">
        <div class="row">
          <div class="col-sm-12 text-center">
          <div class="float-left mt-3">
                    <?php if (exist_val('Branch/add_branch', $this->session->userdata('permission'))) { ?>
                      <a href="<?php echo base_url('Branch/add_branch'); ?>" class="btn btn-primary btn-sm"> <i class="fa fa-plus"></i> Add</a>
                    <?php } ?>
                  </div>
            <h1 class="text-bold mt-3 mb-3">BRANCHES</h1>
          </div>
        </div>
      </div>
      <div class="container-fluid jumbotron p-3">
      
               
               <div class="row">
                
                <?php if ($name_branch) {
                  $branch_name = $name_branch;
                } else {
                  $branch_name = 0;
                } ?>
                <div class="col-sm-2">
                  <select name="branch_name" id="branch_name" class="form-control form-control-sm">
                    <option value="">Select Branch</option>
                    <?php foreach ($brn_names as $name) { ?>
                      <option value="<?php echo $name->branch_name; ?>" <?php echo ($branch_name == $name->branch_name) ? "selected" : ""; ?>> <?php echo $name->branch_name; ?> </option>
                    <?php } ?>
                  </select>
                </div>
                <?php if ($id_brn_code) {
                  $branch_code = $id_brn_code;
                } else {
                  $branch_code = 0;
                } ?>
                <div class="col-sm-2">
                  <select name="branch_code" id="branch_code" class="form-control form-control-sm">
                    <option value="">Select Branch Code</option>
                    <?php foreach ($brn_codes as $brcodes) { ?>
                      <option value="<?php echo $brcodes->branch_code; ?>" <?php echo ($branch_code == $brcodes->branch_code) ? "selected" : ""; ?>> <?php echo $brcodes->branch_code; ?> </option>
                    <?php } ?>
                  </select>
                </div>
                <?php if ($id_country) {
                  $country_id = $id_country;
                } else {
                  $country_id = 0;
                } ?>
                <div class="col-sm-2">
                  <select name="country_id" id="country_id" class="form-control form-control-sm">
                    <option value="">Select Country</option>
                    <?php foreach ($countries as $contry) { ?>
                      <option value="<?php echo $contry->country_id; ?>" <?php echo ($country_id == $contry->country_id) ? "selected" : ""; ?>> <?php echo $contry->country_name; ?> </option>
                    <?php } ?>
                  </select>
                </div>
                <?php if ($id_state) {
                  $state_id = $id_state;
                } else {
                  $state_id = 0;
                } ?>
                <div class="col-sm-2">
                  <select name="state_id" id="state_id" class="form-control form-control-sm">
                    <option value="">Select State</option>
                    <?php foreach ($states as $stes) { ?>
                      <option value="<?php echo $stes->province_id; ?>" <?php echo ($state_id == $stes->province_id) ? "selected" : ""; ?>> <?php echo $stes->province_name; ?> </option>
                    <?php } ?>
                  </select>
                </div>
                <?php if ($created_pesron) {
                  $uidnr_admin = $created_pesron;
                } else {
                  $uidnr_admin = "";
                } ?>
                <div class="col-sm-2">
                  <select name="created_by" id="created_by" class="form-control form-control-sm">
                    <option value="">Select Created By</option>
                    <?php foreach ($created_by_name as $cr_name) { ?>
                      <option value="<?php echo $cr_name->uidnr_admin; ?>" <?php echo ($uidnr_admin == $cr_name->uidnr_admin) ? "selected" : ""; ?>> <?php echo $cr_name->created_by; ?> </option>
                    <?php } ?>
                  </select>
                </div>
                <div class="col-sm-2">
                  <div class="input-group">
                  <input value="<?php echo (($search != 'NULL') ? $search : ""); ?>" id="search" class="form-control form-control-sm" type="text" placeholder="Search" aria-label="Search">
                  <div class="input-group-append">
                  <button onclick="searchfilter();" type="button" class="btn btn-sm btn-secondary" title="Search">
                    <i class="fa fa-search"></i>
                  </button>
                  <a class="btn btn-sm btn-danger ml-1" href="<?php echo base_url('Branch'); ?>" title="Clear">
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
              <?php //echo "<pre>"; print_r($this->session->userdata('permission'));?>
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
                      if ($name_branch != "NULL") {
                        $name_branch = base64_encode($name_branch);
                      } else {
                        $name_branch = "NULL";
                      }
                      if ($id_brn_code != "NULL") {
                        $id_brn_code = base64_encode($id_brn_code);
                      } else {
                        $id_brn_code = "NULL";
                      }
                      if ($id_country != "NULL") {
                        $id_country = base64_encode($id_country);
                      } else {
                        $id_country = "NULL";
                      }
                      if ($id_state != "NULL") {
                        $id_state = base64_encode($id_state);
                      } else {
                        $id_state = "NULL";
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
                      <th scope="col"><a href="<?php echo base_url('Branch/index/' . $name_branch . '/' . $id_brn_code . '/' . $id_country . '/' . $id_state . '/' . $created_pesron . '/' . $search . '/' . 'msb.branch_name' . '/' . $order) ?>">BRANCH NAME</a></th>
                      <th scope="col"><a href="<?php echo base_url('Branch/index/' . $name_branch . '/' . $id_brn_code . '/' . $id_country .  '/' . $id_state . '/' . $created_pesron . '/' . $search . '/' . 'msb.branch_code' . '/' . $order) ?>">BRANCH CODE</a></th>
                      <th scope="col"><a href="<?php echo base_url('Branch/index/' . $name_branch . '/' . $id_brn_code . '/' . $id_country . '/' . $id_state . '/' . $created_pesron . '/' . $search . '/' . 'msc.country_name' . '/' . $order) ?>">COUNTRY</a></th>
                      <th scope="col"><a href="<?php echo base_url('Branch/index/' . $name_branch . '/' . $id_brn_code . '/' . $id_country . '/' . $id_state . '/' . $created_pesron . '/' . $search . '/' . 'msp.province_name' . '/' . $order) ?>">STATE</a></th>
                      <th scope="col"><a href="<?php echo base_url('Branch/index/' . $name_branch . '/' . $id_brn_code . '/' . $id_country . '/' . $id_state . '/' . $created_pesron . '/' . $search . '/' . 'msb.branch_telephone' . '/' . $order) ?>">TELEPHONE</a></th>
                      <?php if ((exist_val('Branch/branch_status', $this->session->userdata('permission')))) { ?>
                        <th scope="col">STATUS</th>
                      <?php } ?>
                      <th scope="col"><a href="<?php echo base_url('Branch/index/' . $name_branch . '/' . $id_brn_code . '/' . $id_country . '/' . $id_state . '/' . $created_pesron . '/' . $search . '/' . 'ap.admin_fname' . '/' . $order) ?>">CREATED BY</a></th>
                      <th scope="col"><a href="<?php echo base_url('Branch/index/' . $name_branch . '/' . $id_brn_code . '/' . $id_country . '/' . $id_state . '/' . $created_pesron . '/' . $search . '/' . 'msb.created_on' . '/' . $order) ?>">CREATED ON</a></th>
                      <?php if ((exist_val('Branch/edit_branch', $this->session->userdata('permission'))) || (exist_val('Branch/get_branch_log', $this->session->userdata('permission')))) { ?>
                        <th scope="col">ACTION</th>
                      <?php } ?>
                    </tr>
                  </thead>
                  <tbody>
                    <?php $sn = $this->uri->segment('11') + 1;
                    if ($brn_list) {
                      foreach ($brn_list as $key => $value) { ?>
                        <tr>
                          <td><?php echo $sn; ?></td>
                          <td><?php echo $value->branch_name ?></td>
                          <td><?php echo $value->branch_code ?></td>
                          <td><?php echo $value->country_name ?></td>
                          <td><?php echo $value->province_name ?></td>
                          <td><?php echo $value->branch_telephone ?></td>
                          <?php if ((exist_val('Branch/branch_status', $this->session->userdata('permission')))) { ?>
                            <?php if ($value->status == 1) { ?>
                              <td><span class="btn btn-success btn-sm status_chng" id="active" data-id="<?php echo $value->branch_id; ?>">Active</span></td>
                            <?php } else { ?>
                              <td><span class="btn btn-danger btn-sm status_chng" id="deactive" data-id="<?php echo $value->branch_id; ?>">Deactive</span></td>
                            <?php } ?>
                          <?php } ?>
                          <td><?php echo $value->admin_fname ?></td>
                          <td><?php echo change_time($value->created_on,$this->session->userdata('timezone')) ?></td>
                          <td>
                            <?php if ((exist_val('Branch/edit_branch', $this->session->userdata('permission')))) { ?>
                              <a href="<?php echo base_url('Branch/edit_branch/' . base64_encode($value->branch_id)); ?>" class="btn btn-sm text"><i class="fa fa-edit" title="edit"></i></a>
                            <?php } ?>

                            <?php if ((exist_val('Branch/get_branch_log', $this->session->userdata('permission')))) { ?>
                              <a href="javascript:void(0)" data-id="<?php echo $value->branch_id; ?>" class="log_view btn btn-sm text-secondary" data-bs-toggle='modal' data-bs-target='#exampleModal'  title="Log View"><i class="fa fa-eye"></i></a>
                            <?php } ?>
                          </td>
                        </tr>
                    <?php $sn++;
                      }
                    } ?>
                  </tbody>
                </table>
              </div>
            </div>
            <!-- card end -->
          </div>
        </div>

        <!-- menu end -->

        <div class="card-header">
          <?php if ($brn_list && count($brn_list) > 0) { ?>
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

  <!-- modal for user log -->
  <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Branch log</h5>
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
            <tbody id="branch_log"></tbody>
          </table>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
  <!-- end -->

  <script type="text/javascript">
    function searchfilter() {
      var base_url = "<?php echo base_url('Branch/index'); ?>"
      var branch_name = $('#branch_name').val();
      var branch_code = $('#branch_code').val();
      var country_id = $('#country_id').val();
      var state_id = $('#state_id').val();
      var created_by = $('#created_by').val();
      var search = $('#search').val();
      if (branch_name) {
        base_url = base_url + '/' + btoa(branch_name);
      } else {
        base_url = base_url + '/' + 'NULL';
      }
      if (branch_code) {
        base_url = base_url + '/' + btoa(branch_code);
      } else {
        base_url = base_url + '/' + 'NULL';
      }
      if (country_id) {
        base_url = base_url + '/' + btoa(country_id);
      } else {
        base_url = base_url + '/' + 'NULL';
      }
      if (state_id) {
        base_url = base_url + '/' + btoa(state_id);
      } else {
        base_url = base_url + '/' + 'NULL';
      }
      if (created_by) {
        base_url = base_url + '/' + btoa(created_by);
      } else {
        base_url = base_url + '/' + 'NULL';
      }
      if (search) {
        base_url = base_url + '/' + btoa(search);
      } else {
        base_url = base_url + '/' + 'NULL';
      }
      location.href = base_url;
    }
  </script>

  <script>
    $(document).ready(function() {

      var style = {
        "margin": "0 auto"
      };
      $('.modal-content').css(style);

      // status change
      $('.status_chng').click(function() {
        var branch_id = $(this).attr("data-id");
        var _tokken = $('meta[name="_tokken"]').attr('value');
        $.ajax({
          url: "<?php echo base_url('Branch/branch_status'); ?>",
          data: {
            "branch_id": branch_id,
            "_tokken": _tokken
          },
          type: 'post',
          success: function(result) {
            location.reload();
          }
        });
      });
      // end

      // Branch Log
      $('.log_view').click(function(e) {
        e.preventDefault();
        $('#branch_log').empty();
        var branch_id = $(this).data('id');
        const _tokken = $('meta[name="_tokken"]').attr("value");
        $.ajax({
          type: 'post',
          url: "<?php echo base_url('Branch/get_branch_log') ?>",
          data: {
            _tokken: _tokken,
            branch_id: branch_id
          },
          success: function(data) {
            var data = $.parseJSON(data);
            if (data) {
              sn = 1;
              $.each(data, function(i, v) {
                var value = '' ;
                var taken_at = new Date(v.taken_at+ ' UTC');
                value += '<tr>';
                value += '<td>' + sn + '</td>';
                value += '<td>' + v.action_taken + '</td>';
                value += '<td>' + v.text + '</td>';
                value += '<td>' + v.taken_by + '</td>';
                value += '<td>' + taken_at.toLocaleString() + '</td>';
                value += '</tr>';
                $('#branch_log').append(value);
                sn++;
              });
            } else {
              var value = '' ;
              value += '<tr>';
              value += '<td colspan="5">';
              value += "<h4> NO RECORD FOUND! </h4>";
              value += "</td>";
              value += "</tr>";
              $('#branch_log').append(value);
            }
          }
        });
        // return false;
      });
      //ends

    });
  </script>