
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <section class="content-header">
    <!-- container fluid start -->
    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-6">
          <h1>ASSETS USERS</h1>
        </div>
      </div>
      <hr>
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <div class="row">
                <div class="col-sm-6">
                  <?php // if (exist_val('AssetManagement/add_asset_details', $this->session->userdata('permission'))) { ?>
                    <a href="<?php echo base_url('AssetManagement/add_employee'); ?>" class="btn btn-primary">Add</a>
                  <?php // } ?>
                </div>
              </div>
              <hr>
              <div class="row">
                <?php if ($employee_name) {
                  $employee_name = $employee_name;
                } else {
                  $employee_name = 0;
                } ?>
                <div class="col-sm-2">
                  <select name="employee_name" id="employee_name" class="form-control form-control-sm">
                    <option value="">Select Employee NAME</option>
                    <?php foreach ($brn_names as $name) { ?>
                      <option value="<?php echo $name->employee_name; ?>" <?php echo ($employee_name == $name->employee_name) ? "selected" : ""; ?>> <?php echo $name->employee_name; ?> </option>
                    <?php } ?>
                  </select>
                </div>
<?php //print_r($name->employee_name); ?>

                <?php if ($employee_contact) {
                  $employee_contact = $employee_contact;
                } else {
                  $employee_contact = 0;
                } ?>
         <div class="col-sm-2">
                  <select name="employee_contact" id="employee_contact" class="form-control form-control-sm">
                    <option value="">Select employee contact</option>
                    <?php foreach ($brn_names as $brcodes) { ?>
                      <option value="<?php echo $brcodes->employee_contact; ?>" <?php echo ($employee_contact == $brcodes->employee_contact) ? "selected" : ""; ?>> <?php echo $brcodes->employee_contact; ?> </option>
                    <?php } ?>
                  </select>
                </div>
                <?php if ($country_id) {
                  $country_id = $country_id;
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


                <?php if ($status) {
                  $status = $status;
                } else {
                  $status = 0;
                } ?>
                <div class="col-sm-2">
                  <select name="status" id="status" class="form-control form-control-sm">
                    <option value="" disabled selected>Select status</option>
                    <option value="1" <?php if($status == 1){echo "selected"; }?>>Active</option>
                    <option value="2" <?php if($status == 2){echo "selected"; }?>>Resign</option>
                    <option value="3" <?php if($status == 3){echo "selected"; }?>>transfer</option>
                    
                  </select>
                </div>


                <?php if ($division_id) {
                  $division_id = $division_id;
                } else {
                  $division_id = 0;
                } ?>
                <div class="col-sm-2">
                  <select name="division_id" id="division_id" class="form-control form-control-sm">
                    <option value="">Select Division</option>
                    <?php foreach ($brn_divs as $bb) { ?>
                      <option value="<?php echo $bb->division_id; ?>" <?php echo ($division_id == $bb->division_id) ? "selected" : ""; ?>> <?php echo $bb->division_name; ?> </option>
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


               
                
                <hr>
                <div class="col-sm-12 text-right">
                  <button onclick="searchfilter();" type="button" class="btn btn-sm" title="Search">
                    <img src="<?php echo base_url('assets/images/search.png') ?>" alt="search">
                  </button>
                  <a class="btn btn-sm" href="<?php echo base_url('AssetManagement/assets_userlist'); ?>" title="Clear">
                    <img src="<?php echo base_url('assets/images/drop.png') ?>" alt="Clear">
                  </a>
                </div>
              </div>
            </div>
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
                    if ($employee_name != "NULL") {
                      $employee_name = base64_encode($employee_name);
                    } else {
                      $employee_name = "NULL";
                    }
                    if ($employee_contact != "NULL") {
                      $employee_contact = base64_encode($employee_contact);
                    } else {
                      $employee_contact = "NULL";
                    }
                    if ($country_id != "NULL") {
                      $country_id = base64_encode($country_id);
                    } else {
                      $country_id = "NULL";
                    }

                    if ($status != "NULL") {
                      $status = base64_encode($status);
                    } else {
                      $status = "NULL";
                    }
                    if ($division_id != "NULL") {
                      $division_id = base64_encode($division_id);
                    } else {
                      $division_id = "NULL";
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
                    <th scope="col"><a href="<?php echo base_url('AssetManagement/assets_userlist/' . $employee_name . '/' . $employee_contact . '/' . $country_id .'/' . $status . '/' . $division_id . '/' . $created_pesron . '/' . $search . '/' . 'em.employee_name' . '/' . $order) ?>">Employee NAME</a></th>
                    <th scope="col"><a href="<?php echo base_url('AssetManagement/assets_userlist/' . $employee_name . '/' . $employee_contact . '/' . $country_id . '/' . $status . '/' . $division_id . '/' . $created_pesron . '/' . $search . '/' . 'em.employee_contact' . '/' . $order) ?>">Employee Contact</a></th>

                    <th scope="col"><a href="<?php echo base_url('AssetManagement/assets_userlist/' . $employee_name . '/' . $employee_contact . '/' . $country_id . '/' . $status . '/' . $division_id . '/' . $created_pesron . '/' . $search . '/' . 'em.employee_mail' . '/' . $order) ?>">Employee Email</a></th>





                    <th scope="col"><a href="<?php echo base_url('AssetManagement/assets_userlist/' . $employee_name . '/' . $employee_contact . '/' . $country_id . '/' . $status .'/' . $division_id . '/' . $created_pesron . '/' . $search . '/' . 'msc.country_name' . '/' . $order) ?>">COUNTRY</a></th>




                    <th scope="col"><a href="<?php echo base_url('AssetManagement/assets_userlist/' . $employee_name . '/' . $employee_contact . '/' . $country_id .'/' . $status . '/' . $division_id . '/' . $created_pesron . '/' . $search . '/' . 'msd.division_name' . '/' . $order) ?>">Devision</a></th>



                    <th scope="col"><a href="<?php echo base_url('AssetManagement/assets_userlist/' . $employee_name . '/' . $employee_contact . '/' . $country_id .'/' . $status . '/' . $division_id . '/' . $created_pesron . '/' . $search . '/' . 'em.employee_designation' . '/' . $order) ?>">Employee Designation</a></th>
                    <?php //if ((exist_val('AssetManagement/asset_status', $this->session->userdata('permission')))) { ?>
                      <th scope="col">STATUS</th>
                    <?php //} ?>
                    <th scope="col"><a href="<?php echo base_url('AssetManagement/assets_userlist/' . $employee_name . '/' . $employee_contact . '/' . $country_id .'/' . $status . '/' . $division_id . '/' . $created_pesron . '/' . $search . '/' . 'ap.admin_fname' . '/' . $order) ?>">CREATED BY</a></th>
                    <th scope="col"><a href="<?php echo base_url('AssetManagement/assets_userlist/' . $employee_name . '/' . $employee_contact . '/' . $country_id .'/' . $status . '/' . $division_id . '/' . $created_pesron . '/' . $search . '/' . 'em.created_on' . '/' . $order) ?>">CREATED ON</a></th>
                    <?php //if ((exist_val('AssetManagement/edit_branch', $this->session->userdata('permission'))) || (exist_val('AssetManagement/get_emp_log', $this->session->userdata('permission')))) { ?>
                      <th scope="col">ACTION</th>
                    <?php //} ?>
                  </tr>
                </thead>
                <tbody>
                  <?php $sn = $this->uri->segment('11') + 1;
                  if ($emp_list) {
                    foreach ($emp_list as $key => $value) { ?>
                      <tr>
                        <td><?php echo $sn; ?></td>
                        <td><?php echo $value->employee_name ?></td>
                        <td><?php echo $value->employee_contact ?></td>
                        <td><?php echo $value->employee_mail ?></td>
                        <td><?php echo $value->country_name ?></td>
                        <td><?php echo $value->division_name ?></td>
                        <td><?php echo $value->employee_designation ?></td>
                      
            


                        <?php //print_r($value->assign_flag == 0);  ?>
                        <?php if ((exist_val('Branch/branch_status', $this->session->userdata('permission')))) { ?>
                          <?php if ($value->status == '1') { ?>
                            <td><span class="btn btn-success status_chng" id="active" data-id="<?php echo $value->employee_id; ?>">active</span></td>
                          <?php } else if ($value->status == '2') { ?>
                            <td><span class="btn btn-danger status_chng" id="deactive" data-id="<?php echo $value->employee_id; ?>">Resign</span></td>
                          <?php  } else if ($value->status == '3') { ?>
                            <td><span class="btn btn-danger status_chng" id="deactive" data-id="<?php echo $value->employee_id; ?>">transfer</span></td>
                            <?php } ?>
                        <?php } ?> 
                        <td><?php echo $value->admin_fname ?></td>
                        <td><?php echo change_time($value->created_on,$this->session->userdata('timezone')) ?></td>
                        <td>
                          <?php //if ((exist_val('Branch/edit_asset', $this->session->userdata('permission')))) { ?>
                            <a href="<?php echo base_url('AssetManagement/edit_employee/' . base64_encode($value->employee_id)); ?>" class="btn btn-sm"><img src="<?php echo base_url('assets/images/edit_employer.png'); ?>" title="Edit" class="edit" alt="Edit" width="20px"></a>
                          <?php //} ?>

                          <?php // if ((exist_val('Branch/get_emp_log', $this->session->userdata('permission')))) { ?>
                            <a href="javascript:void(0)" data-id="<?php echo $value->employee_id; ?>" class="log_view" data-bs-toggle='modal' data-target='#exampleModal' class="btn btn-sm" title="Log View"><img src="<?php echo base_url('assets/images/log-view.png'); ?>" alt="Log view"></a>
                          <?php //} ?>

                          <?php // if ((exist_val('Branch/get_branch_log', $this->session->userdata('permission')))) { ?>
                            <a href="javascript:void(0)" data-id="<?php echo $value->employee_id; ?>" class="Assigned_history" data-bs-toggle='modal' data-target='#Assigned_history' class="btn btn-sm" title="Assigned history"><img src="<?php echo base_url('assets/images/application_view_detail.png'); ?>" alt="Assigned_history"></a>
                          <?php //} ?>

                          <?php if($value->status==1) {  ?>
                            <a href="javascript:void(0)" data-id="<?php echo $value->employee_id; ?>" class="assigned_asset" data-bs-toggle='modal' data-target='#assigned_asset' class="btn btn-sm" title="Assigned Asset"><img src="<?php echo base_url('assets/images/ApproveReport.png'); ?>" alt="Assigned Asset"></a>
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
        <?php if ($emp_list && count($emp_list) > 0) { ?>
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
              <th>Assets Name</th>
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
        <input type="hidden" name="employee_id" class="employee_id" >

                <div class="col-md-6">
                  <label>asset Name :</label>
                    <select name="asset" id="asset" class="form-control">
                      <option value="" disabled selected>Select asset Name</option>
                      <?php if (!empty($asset)) {
                        foreach ($asset as $asset_list) { ?>
                          <option value="<?php echo $asset_list->asset_id; ?>" <?php if (!empty($asset_id) && $asset_id == $asset_list->asset_id) { echo "selected"; } ?>><?php echo $asset_list->asset_name; ?>(<?php echo $asset_list->asset_code; ?>)
                          </option>
                      <?php }
                      } ?>
                    </select>
                    <?php echo form_error('asset', '<div class="text-danger">', '</div>'); ?>
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

<script>

$(".assigned_asset").click(function(){
var employee_id =$(this).data('id');
$(".employee_id").val(employee_id);

});


    $("#submitbutton").click(function() {
    event.preventDefault();
    var form = $(this);
  $.ajax({
      url: "<?php echo base_url('AssetManagement/assigned_emp'); ?>",
      type: 'POST',
      dataType:'json',
      data: $("#myForm").serialize(),
      success: function(data) {
        if(data.status='1'){
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
    var base_url = "<?php echo base_url('AssetManagement/assets_userlist'); ?>"
    var employee_name = $('#employee_name').val();
    var employee_contact = $('#employee_contact').val();
    var country_id = $('#country_id').val();
    var status = $('#status').val();
    var division_id = $('#division_id').val();
    var created_by = $('#created_by').val();
    var search = $('#search').val();
    if (employee_name) {
      base_url = base_url + '/' + btoa(employee_name);
    } else {
      base_url = base_url + '/' + 'NULL';
    }
    if (employee_contact) {
      base_url = base_url + '/' + btoa(employee_contact);
    } else {
      base_url = base_url + '/' + 'NULL';
    }
    if (country_id) {
      base_url = base_url + '/' + btoa(country_id);
    } else {
      base_url = base_url + '/' + 'NULL';
    }
    if (status) {
      base_url = base_url + '/' + btoa(status);
    } else {
      base_url = base_url + '/' + 'NULL';
    }
    if (division_id) {
      base_url = base_url + '/' + btoa(division_id);
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
      var employee_id = $(this).attr("data-id");
      var _tokken = $('meta[name="_tokken"]').attr('value');
      $.ajax({
        url: "<?php echo base_url('AssetManagement/asset_status'); ?>",
        data: {
          "employee_id":employee_id,
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
      var employee_id = $(this).data('id');
      const _tokken = $('meta[name="_tokken"]').attr("value");
      $.ajax({
        type: 'post',
        url: "<?php echo base_url('AssetManagement/get_emp_log') ?>",
        data: {
          _tokken: _tokken,
          employee_id: employee_id
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




    // Asset history Log
    $('.Assigned_history').click(function(e) {
      e.preventDefault();
      $('#assign_log').empty();
      var employee_id = $(this).data('id');
      const _tokken = $('meta[name="_tokken"]').attr("value");
      $.ajax({
        type: 'post',
        url: "<?php echo base_url('AssetManagement/Assigned_emp_history') ?>",
        data: {
          _tokken: _tokken,
          employee_id: employee_id
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
              value += '<td>' + v.asset_name + '</td>';
              value += '<td>' + v.taken_by + '</td>';
              value += '<td>' + taken_at.toLocaleString() + '</td>';
              value += '</tr>';
              $('#assign_log').append(value);
              sn++;
            });
          } else {
            var value = '' ;
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
</script>