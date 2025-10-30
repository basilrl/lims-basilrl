<?php //print_r($employee);  
?>
<script src="<?php echo base_url('ckeditor') ?>/ckeditor.js"></script>


<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <section class="content-header">
    <!-- container fluid start -->
    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-6">
          <h1>ASSETS</h1>
        </div>
      </div>
      <hr>
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <div class="row">
                <div class="col-sm-6">
                  <?php // if (exist_val('AssetManagement/add_asset_details', $this->session->userdata('permission'))) { 
                  ?>
                  <a href="<?php echo base_url('AssetManagement/add_asset_details'); ?>" class="btn btn-primary">Add</a>
                  <?php // } 
                  ?>
                </div>
              </div>
              <hr>
              <div class="row">
                <?php if ($asset_name) {
                  $asset_name = $asset_name;
                } else {
                  $asset_name = 0;
                } ?>
                <div class="col-sm-2">
                  <select name="asset_name" id="asset_name" class="form-control form-control-sm">
                    <option value="">Select Asset NAME</option>
                    <?php foreach ($brn_names as $name) { ?>
                      <option value="<?php echo $name->asset_name; ?>" <?php echo ($asset_name == $name->asset_name) ? "selected" : ""; ?>> <?php echo $name->asset_name; ?> </option>
                    <?php } ?>
                  </select>
                </div>


                <?php if ($asset_code) {
                  $asset_code = $asset_code;
                } else {
                  $asset_code = 0;
                } ?>
                <div class="col-sm-2">
                  <select name="asset_code" id="asset_code" class="form-control form-control-sm">
                    <option value="">Select ASSETS CODE</option>
                    <?php foreach ($asset as $brcodes) { ?>
                      <option value="<?php echo $brcodes->asset_code; ?>" <?php echo ($asset_code == $brcodes->asset_code) ? "selected" : ""; ?>> <?php echo $brcodes->asset_code; ?> </option>
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


                <?php if ($assigned_user) {
                  $assigned_user = $assigned_user;
                } else {
                  $assigned_user = 0;
                } ?>
                <div class="col-sm-2">
                  <select name="assigned_user" id="assigned_user" class="form-control form-control-sm">
                    <option value="">Select ASSIGNED user</option>
                    <?php foreach ($employee as $emp_name) { ?>
                      <option value="<?php echo $emp_name->employee_id; ?>" <?php echo ($assigned_user == $emp_name->employee_id) ? "selected" : ""; ?>> <?php echo $emp_name->employee_name; ?> </option>
                    <?php } ?>
                  </select>
                </div>



                <?php if ($assign_flag) {
                  $assign_flag = $assign_flag;
                } else {
                  $assign_flag = 0;
                }?>
                <div class="col-sm-2">
                  <select name="assign_flag" id="assign_flag" class="form-control form-control-sm">
                    <option value="">Select Assigned Status</option>
                    <option value="1" <?php if($assign_flag == 1){echo "selected"; }?>>Free</option>
                    <option value="2" <?php if($assign_flag == 2){echo "selected"; }?>>Not Assigned</option>
                    <option value="3" <?php if($assign_flag == 3){echo "selected"; }?>>Assigned</option>
                    
                  </select>
                </div>

                <?php if ($id_branch) {
                  $branch_id = $id_branch;
                } else {
                  $branch_id = 0;
                } ?>
                <div class="col-sm-2">
                  <select name="branch_id" id="branch_id" class="form-control form-control-sm">
                    <option value="">Select branch</option>
                    <?php foreach ($branch as $bb) { ?>
                      <option value="<?php echo $bb->branch_id; ?>" <?php echo ($branch_id == $bb->branch_id) ? "selected" : ""; ?>> <?php echo $bb->branch_name; ?> </option>
                    <?php } ?>
                  </select>
                </div>
                <br>
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

                <hr>
                <div class="col-sm-12 text-right">
                  <button onclick="searchfilter();" type="button" class="btn btn-sm" title="Search">
                    <img src="<?php echo base_url('assets/images/search.png') ?>" alt="search">
                  </button>
                  <a class="btn btn-sm" href="<?php echo base_url('AssetManagement'); ?>" title="Clear">
                    <img src="<?php echo base_url('assets/images/drop.png') ?>" alt="Clear">
                  </a>
                </div>
              </div>
            </div>
            <!-- end card header -->
            <?php //echo "<pre>"; print_r($this->session->userdata('permission'));
            ?>
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
                    if ($asset_name != "NULL") {
                      $asset_name = base64_encode($asset_name);
                    } else {
                      $asset_name = "NULL";
                    }
                    if ($asset_code != "NULL") {
                      $asset_code = base64_encode($asset_code);
                    } else {
                      $asset_code = "NULL";
                    }
                    if ($id_country != "NULL") {
                      $id_country = base64_encode($id_country);
                    } else {
                      $id_country = "NULL";
                    }
                    if ($assigned_user != "NULL") {
                      $assigned_user = base64_encode($assigned_user);
                    } else {
                      $assigned_user = "NULL";
                    }
                    if ($assign_flag != "NULL") {
                      $assign_flag = base64_encode($assign_flag);
                    } else {
                      $assign_flag = "NULL";
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
                    <th scope="col"><a href="<?php echo base_url('AssetManagement/index/' . $asset_name . '/' . $asset_code . '/' . $id_country . '/' . $assigned_user .'/' . $assign_flag .  '/' . $id_branch . '/' . $created_pesron . '/' . $search . '/' . 'am.asset_name' . '/' . $order) ?>">Asset NAME</a></th>
                    <th scope="col"><a href="<?php echo base_url('AssetManagement/index/' . $asset_name . '/' . $asset_code . '/' . $id_country . '/' . $assigned_user .'/' . $assign_flag .  '/' . $id_branch . '/' . $created_pesron . '/' . $search . '/' . 'am.asset_code' . '/' . $order) ?>">ASSETS CODE</a></th>

                    <th scope="col"><a href="<?php echo base_url('AssetManagement/index/' . $asset_name . '/' . $asset_code . '/' . $id_country . '/' . $assigned_user .'/' . $assign_flag .  '/' . $id_branch . '/' . $created_pesron . '/' . $search . '/' . 'am.asset_make' . '/' . $order) ?>">ASSETS MAKE</a></th>





                    <th scope="col"><a href="<?php echo base_url('AssetManagement/index/' . $asset_name . '/' . $asset_code . '/' . $id_country . '/' . $assigned_user .'/' . $assign_flag .'/' . $id_branch . '/' . $created_pesron . '/' . $search . '/' . 'msc.country_name' . '/' . $order) ?>">COUNTRY</a></th>




                    <th scope="col"><a href="<?php echo base_url('AssetManagement/index/' . $asset_name . '/' . $asset_code . '/' . $id_country . '/' . $assigned_user .'/' . $assign_flag .'/' . $id_branch . '/' . $created_pesron . '/' . $search . '/' . 'msb.branch_name' . '/' . $order) ?>">Branch</a></th>



                    <th scope="col"><a href="<?php echo base_url('AssetManagement/index/' . $asset_name . '/' . $asset_code . '/' . $id_country . '/' . $assigned_user .'/' . $assign_flag .'/' . $id_branch . '/' . $created_pesron . '/' . $search . '/' . 'am.asset_model' . '/' . $order) ?>">Asset Model</a></th>

                    <th scope="col"><a href="<?php echo base_url('AssetManagement/index/' . $asset_name . '/' . $asset_code . '/' . $id_country . '/' . $assigned_user .'/' . $assign_flag .'/' . $id_branch . '/' . $created_pesron . '/' . $search . '/' . 'ass.employee_name' . '/' . $order) ?>">ASSIGNED USERS</a></th>
                    <th scope="col">ASSIGNED STATUS</th>
                    <th scope="col"><a href="<?php echo base_url('AssetManagement/index/' . $asset_name . '/' . $asset_code . '/' . $id_country . '/' . $assigned_user .'/' . $assign_flag . '/' . $id_branch . '/' . $created_pesron . '/' . $search . '/' . 'ap.admin_fname' . '/' . $order) ?>">CREATED BY</a></th>
                    <th scope="col"><a href="<?php echo base_url('AssetManagement/index/' . $asset_name . '/' . $asset_code . '/' . $id_country . '/' . $assigned_user .'/' . $assign_flag . '/' . $id_branch . '/' . $created_pesron . '/' . $search . '/' . 'am.created_on' . '/' . $order) ?>">CREATED ON</a></th>


                    <th scope="col">ASSETS STATUS</th>

                    <th scope="col">ACTION</th>

                  </tr>
                </thead>
                <tbody>
                  <?php $sn = $this->uri->segment('13') + 1;
                  if ($brn_list) {
                    foreach ($brn_list as $key => $value) { ?>
                      <tr>
                        <td><?php echo $sn; ?></td>
                        <td><?php echo $value->asset_name ?></td>
                        <td><?php echo $value->asset_code ?></td>
                        <td><?php echo $value->asset_make ?></td>
                        <td><?php echo $value->country_name ?></td>
                        <td><?php echo $value->branch_name ?></td>
                        <td><?php echo $value->asset_model ?></td>



                        <?php // print_r($value->flag);  
                        ?>
                 

                 <?php //if ($value->flag == 1) { ?>
                        <td><?php if ($value->tbl_assign_flag == 1) {  echo $value->employee_name; } ?></td>
                        <?php //}  ?>



                        <?php //if ((exist_val('Branch/branch_status', $this->session->userdata('permission')))) { 
                        ?>
                        
                        <?php //} 
                        ?>


                          <?php if ($value->assign_flag == 1) { ?>
                            <td><span class="btn btn-success status_chng" id="active" data-id="<?php echo $value->asset_id; ?>">Free</span></td>
                          <?php } else if ($value->assign_flag == '2') { ?>
                            <td><span class="btn btn-danger status_chng" id="deactive" data-id="<?php echo $value->asset_id; ?>">Not Assigned</span></td>
                          <?php  } else if ($value->assign_flag == '3') { ?>
                            <td><span class="btn btn-success status_chng" id="active" data-id="<?php echo $value->asset_id; ?>">Assigned</span></td>
                            <?php } ?>



                        <td><?php echo $value->admin_fname ?></td>
                        <td><?php echo change_time($value->created_on, $this->session->userdata('timezone')) ?></td>

                        <?php if ((exist_val('Branch/branch_status', $this->session->userdata('permission')))) { ?>
                          <?php if ($value->status == 1) { ?>
                            <td><span class="btn status_chng" id="active" data-id="<?php echo $value->asset_id; ?>">active</span></td>
                          <?php } else { ?>
                            <td><span class="btn status_chng" id="deactive" data-id="<?php echo $value->asset_id; ?>">inactive</span></td>
                          <?php  } ?>
                        <?php } ?>


                        <td>
                          <?php //if ((exist_val('Branch/edit_asset', $this->session->userdata('permission')))) { 
                          ?>
                          <a href="<?php echo base_url('AssetManagement/edit_asset/' . base64_encode($value->asset_id)); ?>" class="btn btn-sm"><img src="<?php echo base_url('assets/images/mem_edit.png'); ?>" title="Edit" class="edit" alt="Edit" width="20px"></a>
                          <?php //} 
                          ?>

                          <?php // if ((exist_val('Branch/get_branch_log', $this->session->userdata('permission')))) { 
                          ?>
                          <a href="javascript:void(0)" data-id="<?php echo $value->asset_id; ?>" class="log_view" data-bs-toggle='modal' data-target='#exampleModal' class="btn btn-sm" title="Log View"><img src="<?php echo base_url('assets/images/log-view.png'); ?>" alt="Log view"></a>
                          <?php //} 
                          ?>

                          <?php // if ((exist_val('Branch/get_branch_log', $this->session->userdata('permission')))) { 
                          ?>
                          <a href="javascript:void(0)" data-id="<?php echo $value->asset_id; ?>" class="Assigned_history" data-bs-toggle='modal' data-target='#Assigned_history' class="btn btn-sm" title="Assigned history"><img src="<?php echo base_url('assets/images/application_view_detail.png'); ?>" alt="Assigned_history"></a>
                          <?php //} 
                          ?>

                            <?php if($value->status==1) {  ?>
                          <?php if ($value->assign_flag != 3) { ?>
                            <a href="javascript:void(0)" data-id="<?php echo $value->asset_id; ?>" class="assigned_asset" data-bs-toggle='modal' data-target='#assigned_asset' class="btn btn-sm" title="Assigned Asset"><img src="<?php echo base_url('assets/images/ApproveReport.png'); ?>" alt="Assigned Asset"></a>
                          <?php  } ?>


                          <?php if ($value->assign_flag != 1 && $value->assign_flag != 2 ) { ?>
                            <a href="javascript:void(0)" data-id="<?php echo $value->asset_id; ?>" class="reassigned_asset" data-bs-toggle='modal' data-target='#reassigned_asset' class="btn btn-sm" title="Re assigned">
                              <img src="<?php echo base_url('assets/images/resume_report.png'); ?>" alt=""></a>

                          <?php  } ?>

                          <?php if ($value->assign_flag != 1 && $value->assign_flag != 2 ) { ?>
                            <a href="javascript:void(0)" data-id="<?php echo $value->asset_id; ?>" class="free_asset" data-bs-toggle='modal' data-target='#free_asset' class="btn btn-sm" title="Free assigned">
                              <img src="<?php echo base_url('assets/images/reset.png'); ?>" alt=""></a>

                          <?php  } ?>
                          <?php }  ?>
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
        <h5 class="modal-title" id="exampleModalLabel">Asset Management log</h5>
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

<!-- modal for assigned history -->
<div class="modal fade" id="Assigned_history" tabindex="-1" role="dialog" aria-labelledby="Assigned_historyLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="Assigned_historyLabel">Assigned history</h5>
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
              <th>Employee Name</th>
              <th>Performed By</th>
              <th>Added AT</th>
            </tr>
          </thead>
          <tbody id="assign_log"></tbody>
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<!-- end -->






<!-- modal for free assigned -->
<div class="modal fade" id="free_asset" tabindex="-1" role="dialog" aria-labelledby="free_assetLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="free_assetLabel">Assigned history</h5>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="" id="freeForm" method="POST">
          <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
          <input type="hidden" name="asset_id" class="asset_id">
          <div class="col-sm-6">

            <label>Reason for:</label>
            <textarea  name="remark" id="remark" class="form-control form-control-sm" rows="5" cols="70">

      </textarea>
      <?php echo form_error('remark', '<div class="text-danger">', '</div>'); ?>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label>Status :</label>
              <select name="status" id="status" class="form-control">
                <option disabled selected>Select Status</option>
                <option value="1">Active</option>
                    <option value="2">Resign</option>
                    <option value="3">transfer</option>
              </select>
              <?php echo form_error('status', '<div class="text-danger">', '</div>'); ?>
            </div>
          </div>
          <input type="submit" id="freeasset" class="btn btn-primary" value="FREE Assets">
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<!-- end -->

<!-- modal for assigned_asset -->
<div class="modal fade" id="assigned_asset" tabindex="-1" role="dialog" aria-labelledby="assigned_assetLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="assigned_assetLabel">Assigned Asset</h5>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="" id="myForm" method="post">
          <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
          <input type="hidden" name="asset_id" class="asset_id">
          <input type="hidden" name="assigned_id" class="assigned_id">

          <div class="col-md-6">
            <label>Employee Name :</label>
            <select name="employee" id="employee" class="form-control">
              <option value="" disabled selected>Select Employee Name</option>
              <?php if (!empty($employee)) {
                foreach ($employee as $employee_list) { ?>
                  <option value="<?php echo $employee_list->employee_id; ?>" <?php if (!empty($employee_id) && $employee_id == $employee_list->employee_id) {
                                                                                echo "selected";
                                                                              } ?>><?php echo $employee_list->employee_name; ?>(<?php echo $employee_list->emp_id; ?>)
                  </option>
              <?php }
              } ?>
            </select>
            <?php echo form_error('employee', '<div class="text-danger">', '</div>'); ?>
          </div>


          <br>
          <input type="submit" id="submitbutton" class="btn btn-primary" value="Assigned">

        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>






<!-- modal for reassigned_asset -->
<div class="modal fade" id="reassigned_asset" tabindex="-1" role="dialog" aria-labelledby="reassigned_assetLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="reassigned_assetLabel">Reassigned Asset</h5>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="" id="myForm1" method="post">
          <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
          <input type="hidden" name="asset_id" class="asset_id">
          <input type="hidden" name="assigned_id" class="assigned_id">

          <div class="col-md-6">
            <label>Employee Name :</label>
            <select name="employee" id="employee" class="form-control">
              <option value="" disabled selected>Select Employee Name</option>
              <?php if (!empty($employee)) {
                foreach ($employee as $employee_list) { ?>
                  <option value="<?php echo $employee_list->employee_id; ?>" <?php if (!empty($employee_id) && $employee_id == $employee_list->employee_id) {
                                                                                echo "selected";
                                                                              } ?>><?php echo $employee_list->employee_name; ?>(<?php echo $employee_list->emp_id; ?>)
                  </option>
              <?php }
              } ?>
            </select>
            <?php echo form_error('employee', '<div class="text-danger">', '</div>'); ?>
          </div>


          <br>
          <input type="submit" id="submit" class="btn btn-primary" value="Re-Assigned">

        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>















<script>
  $(".assigned_asset").click(function() {
    var asset_id = $(this).data('id');
    $(".asset_id").val(asset_id);

  });
  $(".reassigned_asset").click(function() {
    var asset_id = $(this).data('id');
    $(".asset_id").val(asset_id);

  });

  $(".free_asset").click(function() {
    var asset_id = $(this).data('id');
    $(".asset_id").val(asset_id);

  });

  $(".assigned_asset").click(function() {
    event.preventDefault();
    var asset_id = $(this).data('id');
    const _tokken = $('meta[name="_tokken"]').attr('value');
    $.ajax({
      type: "POST",
      url: "<?php echo base_url('AssetManagement/fetch_assign_data'); ?>",
      dataType: "json",
      data: {
        _tokken: _tokken,
        asset_id: asset_id
      },
      success: function(data) {
        $(".assigned_id").val(data.assigned_id);
      }
    });
  });
  $(".reassigned_asset").click(function() {
    event.preventDefault();
    var asset_id = $(this).data('id');
    const _tokken = $('meta[name="_tokken"]').attr('value');
    $.ajax({
      type: "POST",
      url: "<?php echo base_url('AssetManagement/fetch_assign_data'); ?>",
      dataType: "json",
      data: {
        _tokken: _tokken,
        asset_id: asset_id
      },
      success: function(data) {
        $(".assigned_id").val(data.assigned_id);
      }
    });
  });


  $("#freeasset").click(function() {
    event.preventDefault();
    for (instance in CKEDITOR.instances) {
      CKEDITOR.instances[instance].updateElement();
    }

    $.ajax({
      url: "<?php echo base_url('AssetManagement/free_asset'); ?>",
      type: 'POST',
      dataType: 'json',
      data: $("#freeForm").serialize(),
      success: function(data) {
                 if (data.status == 1) {
                  window.location.reload();
                 } else if (data.status == 0) {
                  //  console.log(result);
                   $.notify(data.msg, 'error');
                 }
                 if (data.status == 3) {
                  //  console.log(result);
                   $.notify(data.msg, 'error');
                 }
      },
      error: function() {
                 alert('Error');
               }
    });
  });





  $("#submitbutton").click(function() {
    event.preventDefault();
    var form = $(this);
    $.ajax({
      url: "<?php echo base_url('AssetManagement/assigned_asset'); ?>",
      type: 'POST',
      dataType: 'json',
      data: $("#myForm").serialize(),
      success: function(data) {
        if (data.status = '1') {
          window.location.reload();
        }
      },
      error: function() {
        alert("Fail");
      }
    });
  });


  //reassign code
  $("#submit").click(function() {
    event.preventDefault();
    $.ajax({
      url: "<?php echo base_url('AssetManagement/reassigned_asset'); ?>",
      type: 'POST',
      dataType: 'json',
      data: $("#myForm1").serialize(),
      success: function(data) {
        if (data.status = '1') {
          window.location.reload();
        }
      },
      error: function() {
        alert("Fail");
      }
    });
  });
</script>



<script type="text/javascript">
  function searchfilter() {
    var base_url = "<?php echo base_url('AssetManagement/index'); ?>"
    var asset_name = $('#asset_name').val();
    var asset_code = $('#asset_code').val();
    var country_id = $('#country_id').val();
    var assigned_user = $('#assigned_user').val();
    var assign_flag = $('#assign_flag').val();
    var branch_id = $('#branch_id').val();
    var created_by = $('#created_by').val();
    var search = $('#search').val();
    if (asset_name) {
      base_url = base_url + '/' + btoa(asset_name);
    } else {
      base_url = base_url + '/' + 'NULL';
    }
    if (asset_code) {
      base_url = base_url + '/' + btoa(asset_code);
    } else {
      base_url = base_url + '/' + 'NULL';
    }
    if (country_id) {
      base_url = base_url + '/' + btoa(country_id);
    } else {
      base_url = base_url + '/' + 'NULL';
    }
    if (assigned_user) {
      base_url = base_url + '/' + btoa(assigned_user);
    } else {
      base_url = base_url + '/' + 'NULL';
    }
     if (assign_flag) {
      base_url = base_url + '/' + btoa(assign_flag);
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
      var asset_id = $(this).attr("data-id");
      var _tokken = $('meta[name="_tokken"]').attr('value');
      $.ajax({
        url: "<?php echo base_url('AssetManagement/asset_status'); ?>",
        data: {
          "asset_id": asset_id,
          "_tokken": _tokken
        },
        type: 'post',
        success: function(result) {
          location.reload();
        }
      });
    });
    // end

    // Asset Management Log
    $('.log_view').click(function(e) {
      e.preventDefault();
      $('#branch_log').empty();
      var asset_id = $(this).data('id');
      const _tokken = $('meta[name="_tokken"]').attr("value");
      $.ajax({
        type: 'post',
        url: "<?php echo base_url('AssetManagement/get_branch_log') ?>",
        data: {
          _tokken: _tokken,
          asset_id: asset_id
        },
        success: function(data) {
          var data = $.parseJSON(data);
          if (data) {
            sn = 1;
            $.each(data, function(i, v) {
              var value = '';
              var taken_at = new Date(v.taken_at + ' UTC');
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
            var value = '';
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

    // Asset history Log
    $('.Assigned_history').click(function(e) {
      e.preventDefault();
      $('#assign_log').empty();
      var asset_id = $(this).data('id');
      const _tokken = $('meta[name="_tokken"]').attr("value");
      $.ajax({
        type: 'post',
        url: "<?php echo base_url('AssetManagement/Assigned_history') ?>",
        data: {
          _tokken: _tokken,
          asset_id: asset_id
        },
        success: function(data) {
          var data = $.parseJSON(data);
          if (data) {
            sn = 1;
            $.each(data, function(i, v) {
              var value = '';
              var taken_at = new Date(v.taken_at + ' UTC');
              value += '<tr>';
              value += '<td>' + sn + '</td>';
              value += '<td>' + v.action_taken + '</td>';
              value += '<td>' + v.text + '</td>';
              value += '<td>' + v.taken_by + '</td>';
              value += '<td>' + taken_at.toLocaleString() + '</td>';
              value += '</tr>';
              $('#assign_log').append(value);
              sn++;
            });
          } else {
            var value = '';
            value += '<tr>';
            value += '<td colspan="5">';
            value += "<h4> NO RECORD FOUND! </h4>";
            value += "</td>";
            value += "</tr>";
            $('#assign_log').append(value);
          }
        }
      });
      // return false;
    });
    //ends



  });
  CKEDITOR.replace('remark');
</script>