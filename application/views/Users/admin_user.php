</style>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>USERS LIST</h1>
        </div>
        <div class="col-sm-6">
          <!--            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Simple Tables</li>
            </ol>-->
        </div>
      </div>


      <div class="row">
        <div class="col-12">
          <div class="card">




            <div class="card-header">

              <div class="row">
                <div class="col-sm-3">
                  <select name="" id="" class="crm_flag_filter form-control form-control-sm">
                    <option value="">CRM FLAG</option>
                    <option value="1" <?php echo ($crm_flag_id == '1') ? "selected" : "" ?>>YES</option>
                    <option value="0" <?php echo ($crm_flag_id == '0') ? "selected" : "" ?>>NO</option>
                  </select>
                </div>

                <div class="col-sm-3">
                  <select name="" id="" class="lab_analyst_filter form-control form-control-sm">
                    <option value="">LAB ANALYST FLAG</option>
                    <option value="1" <?php echo ($lab_analyst_id == '1') ? "selected" : "" ?>>YES</option>
                    <option value="0" <?php echo ($lab_analyst_id == '0') ? "selected" : "" ?>>NO</option>
                  </select>
                </div>

                <div class="col-sm-3">
                  <select name="" id="" class="division_filter form-control form-control-sm">

                  </select>
                </div>

                <div class="col-sm-3">
                  <select name="" id="" class="department_filter form-control form-control-sm">

                  </select>
                </div>
              </div>

              <br>

              <div class="row">
                <div class="col-sm-3">
                  <select name="" id="" class="branch_filter form-control form-control-sm">

                  </select>
                </div>

                <div class="col-sm-3">
                  <select name="" id="" class="username_filter form-control form-control-sm">

                  </select>
                </div>

                <div class="col-sm-3">
                  <select name="" id="" class="designation_filter form-control form-control-sm">

                  </select>
                </div>

                <div class="col-sm-3">
                  <select name="" id="" class="rol_filter form-control form-control-sm">

                  </select>
                </div>
              </div>
              <br>
              <?php if (exist_val('Users/add_new_user', $this->session->userdata('permission'))) { ?>


                <h3 class="card-title"><a href="<?php echo base_url('Users/add_new_user') ?>"><button class="btn btn-primary btn-sm">Add New</button></a></h3>
              <?php } ?>
              <div class="card-tools">
                <div class="input-group input-group-sm" style="width: 600px;">
                  <input type="text" name="table_search" class="form-control float-right" placeholder="Search" id="search" value="<?php echo ($search) ? $search : "" ?>">

                  <div class="input-group-append">
                    <button onclick="searchfilter();" type="button" class="btn btn-sm btn-default" title="Search">
                      <img src="<?php echo base_url('assets/images/search.png') ?>" alt="search">
                    </button>
                    <a class="btn btn-sm btn-default" href="<?php echo base_url('Users'); ?>" title="Clear">
                      <img src="<?php echo base_url('assets/images/drop.png') ?>" alt="Clear">
                    </a>
                  </div>
                </div>
              </div>
            </div>
            <!-- /.card-header -->
            <div class="table-responsive small p-2">
              <table class="table table-hover table-sm">
                <thead>
                  <tr>
                    <?php

                    if ($crm_flag_id) {
                      $crm_flag_id = base64_encode($crm_flag_id);
                    } else {
                      $crm_flag_id = "NULL";
                    }

                    if ($lab_analyst_id) {
                      $lab_analyst_id = base64_encode($lab_analyst_id);
                    } else {
                      $lab_analyst_id = "NULL";
                    }

                    if ($division_id) {
                      $division_id = base64_encode($division_id);
                    } else {
                      $division_id = "NULL";
                    }

                    if ($dept_id) {
                      $dept_id = base64_encode($dept_id);
                    } else {
                      $dept_id = "NULL";
                    }

                    if ($branch_id) {
                      $branch_id = base64_encode($branch_id);
                    } else {
                      $branch_id = "NULL";
                    }
                    if ($username) {
                      $username = base64_encode($username);
                    } else {
                      $username = "NULL";
                    }
                    if ($designation_id) {
                      $designation_id = base64_encode($designation_id);
                    } else {
                      $designation_id = "NULL";
                    }
                    if ($rol_id) {
                      $rol_id = base64_encode($rol_id);
                    } else {
                      $rol_id = "NULL";
                    }
                    if ($search) {
                      $search = base64_encode($search);
                    } else {
                      $search = "NULL";
                    }
                    // echo $search;die;
                    ?>

                    <th>Sl No.</th>

                    <th><a href="<?php echo base_url('Users/index/' . $crm_flag_id . '/' . $lab_analyst_id . '/' . $division_id . '/' . $dept_id . '/' . $branch_id . '/' . $username . '/' . $designation_id . '/' . $rol_id . '/' . $search . '/' . base64_encode("employee_no") . '/' . $order) ?>">Emp No</a></th>
                    
                    <th><a href="<?php echo base_url('Users/index/' . $crm_flag_id . '/' . $lab_analyst_id . '/' . $division_id . '/' . $dept_id . '/' . $branch_id . '/' . $username . '/' . $designation_id . '/' . $rol_id . '/' . $search . '/' . base64_encode("admin_name") . '/' . $order) ?>">Name</a></th>

                    <th><a href="<?php echo base_url('Users/index/' . $crm_flag_id . '/' . $lab_analyst_id . '/' . $division_id . '/' . $dept_id . '/' . $branch_id . '/' . $username . '/' . $designation_id . '/' . $rol_id . '/' . $search . '/' . base64_encode("u.admin_email") . '/' . $order) ?>">Email Id</a></th>

                    <th><a href="<?php echo base_url('Users/index/' . $crm_flag_id . '/' . $lab_analyst_id . '/' . $division_id . '/' . $dept_id . '/' . $branch_id . '/' . $username . '/' . $designation_id . '/' . $rol_id . '/' . $search . '/' . base64_encode("r.admin_role_name") . '/' . $order) ?>">Role</a></th>

                    <th><a href="<?php echo base_url('Users/index/' . $crm_flag_id . '/' . $lab_analyst_id . '/' . $division_id . '/' . $dept_id . '/' . $branch_id . '/' . $username . '/' . $designation_id . '/' . $rol_id . '/' . $search . '/' . base64_encode("ds.designation_name") . '/' . $order) ?>">Designation</a></th>



                    <th><a href="<?php echo base_url('Users/index/' . $crm_flag_id . '/' . $lab_analyst_id . '/' . $division_id . '/' . $dept_id . '/' . $branch_id . '/' . $username . '/' . $designation_id . '/' . $rol_id . '/' . $search . '/' . base64_encode("u.crm_flag") . '/' . $order) ?>">CRM Flag</a></th>

                    <th><a href="<?php echo base_url('Users/index/' . $crm_flag_id . '/' . $lab_analyst_id . '/' . $division_id . '/' . $dept_id . '/' . $branch_id . '/' . $username . '/' . $designation_id . '/' . $rol_id . '/' . $search . '/' . base64_encode("division_name") . '/' . $order) ?>">Default Division</a></th>

                    <th><a href="<?php echo base_url('Users/index/' . $crm_flag_id . '/' . $lab_analyst_id . '/' . $division_id . '/' . $dept_id . '/' . $branch_id . '/' . $username . '/' . $designation_id . '/' . $rol_id . '/' . $search . '/' . base64_encode("dept.dept_name") . '/' . $order) ?>">Department</a></th>

                    <th><a href="<?php echo base_url('Users/index/' . $crm_flag_id . '/' . $lab_analyst_id . '/' . $division_id . '/' . $dept_id . '/' . $branch_id . '/' . $username . '/' . $designation_id . '/' . $rol_id . '/' . $search . '/' . base64_encode("branch_name") . '/' . $order) ?>">Default Branch</a></th>




                    <?php if (exist_val('Users/Edit_user_submit', $this->session->userdata('permission'))) { ?>

                      <th>Action</th>
                    <?php } ?>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  if ($users) {
                    if (empty($this->uri->segment(14)))
                      $slno = 1;
                    else
                      $slno = $this->uri->segment(14) + 1;
                    //                      $slno = 1;
                    foreach ($users as $res) {

                  ?>

                      <tr>
                        <td><?php echo $slno; ?></td>
                        <td><?php echo $res['employee_no']; ?></td>
                        <td><?php echo $res['admin_name']; ?></td>
                        <td><?php echo $res['admin_email']; ?></td>
                        <td><?php echo $res['admin_role_name']; ?></td>
                        <td><?php echo $res['designation_name']; ?></td>

                        <td><?php echo ($res['crm_flag'] == '1') ? "YES" : "NO"; ?></td>
                        <td><?php echo $res['division_name']; ?></td>

                        <td><?php echo $res['dept_name']; ?></td>
                        <td><?php echo $res['branch_name']; ?></td>



                        <td>
                          <?php if (exist_val('Users/Edit_user_submit', $this->session->userdata('permission'))) { ?>
                            <a href="<?php echo base_url() . "Users/edit_admin_users/" . $res['uidnr_admin']; ?>" class="btn btn-sm btn-default"><img src="<?php echo base_url('assets/images/user_edit.png') ?>" title="EDIT USER"></a>
                          <?php } ?>
                          <?php
                          if (exist_val('Users/mark_user', $this->session->userdata('permission'))) {
                          ?>

                            <?php if ($res['lab_analyst'] == '0') { ?>
                              <a class="btn btn-sm" title="MARK LAB ANALYST" href="<?php echo base_url('Users/mark_user?uidnr_admin=' . $res['uidnr_admin']); ?>"><button type="button" class="btn btn-sm btn-default" title="MARK AS LAB ANALYST"><img src="<?php echo base_url('assets/images/assign_lab.png') ?>" alt="mark lab analyst"></button></a>

                            <?php } else { ?>

                              <a class="btn btn-sm" title="UN-MARK LAB ANALYST" href="<?php echo base_url('Users/mark_user?uidnr_admin=' . $res['uidnr_admin']); ?>"><button type="button" class="btn btn-sm btn-success" title="UN-MARK AS LAB ANALYST"><img src="<?php echo base_url('assets/images/assign_lab.png') ?>" alt="un-mark lab analyst"></button></a>

                            <?php } ?>

                          <?php
                          }
                          ?>

                          <?php
                          if (exist_val('Users/get_user_log_data', $this->session->userdata('permission'))) { ?>
                            <!-- added by Millan on 22-02-2021 -->
                            <button type="button" class="btn btn-sm log_view btn-default" data-bs-toggle="modal" data-bs-target=".user_log_windows" title="User Log" data-id="<?php echo $res['uidnr_admin']; ?>"><img src="<?php echo base_url('assets/images/log-view.png') ?>" alt="User Log"></button>
                          <?php
                          } ?>

                          <?php
                          if (exist_val('Users/upload_signature', $this->session->userdata('permission'))) { ?>

                            <button type="button" class="btn btn-sm btn-default upload_sign" data-bs-toggle="modal" data-bs-target="#upload_signature" title="Upload Signature" data-id="<?php echo $res['uidnr_admin']; ?>"><i class="fas fa-file-signature" alt='Upload signature'></i>
                            </button>
                          <?php
                          } ?>


                          <?php
                          if (exist_val('Users/mark_user', $this->session->userdata('permission'))) {
                          ?>

                            <?php if ($res['admin_active'] == 1) {         ?>

                              <a class="btn btn-sm" title="BLOCK" href="<?php echo base_url('Users/block_user?uidnr_admin=' . $res['uidnr_admin']); ?>"><button type="button" class="btn btn-sm btn-success"><i class="fas fa-ban"></i></button></a>

                            <?php } else { ?>

                              <a class="btn btn-sm" title="UN-BLOCK" href="<?php echo base_url('Users/block_user?uidnr_admin=' . $res['uidnr_admin']); ?>"><button type="button" class="btn btn-sm btn-danger"><i class="fas fa-ban"></i></button></a>

                            <?php } ?>

                          <?php
                          } ?>
                          
                        <?php
                          if (exist_val('Users/get_history', $this->session->userdata('permission'))) {
                          ?>
                          <button type="button" class="btn btn-sm btn-default login_history" data-id="<?php echo $res['uidnr_admin'] ?>" data-bs-toggle="modal" data-bs-target="#login_history_popup" title="Login user history"><i class="fas fa-history"></i></button>
                          <?php } ?>
                        </td>

                      </tr>
                  <?php $slno++;
                    }
                  } ?>
                </tbody>
              </table>
            </div>
            <div class="card-header">

              <?php if ($users && count($users) > 0) { ?>
                <span><?php echo $links ?></span>
                <span><?php echo $result_count; ?></span>
              <?php } else { ?>
                <h3>NO RECORD FOUND</h3>
              <?php } ?>

            </div>

          </div>
        </div><!-- /.container-fluid -->
        <!-- /.card-body -->
      </div>
      <!-- /.card -->
    </div>
</div>


</section>
<!-- /.content -->
</div>

<!-- user login history -->
<div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" id="login_history_popup">
  <div class="modal-dialog modal-lg ">
    <div class="modal-content modal-xl vertical-align-center " style="margin: 0 auto;max-height:500px">
      <div class="modal-header bg-primary">
        <h5 class="modal-title" id="">USER LOGIN HISTORY</h5>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
              <div class="row">
                  <div class="col-sm-4">
                  <select name="attempt_type" id="" class="attempt_type form-control form-control-sm">
                      <option value="">SELECT ATTEMPT TYPE</option>
                      <option value="1">SUCCESSFUL LOGIN</option>
                      <option value="0">UN-SUCCESSFUL LOGIN</option>
                  </select>
                  </div>

                  <div class="col-sm-6">
                      <input type="date" name="search_history" id="" class="form-control form-control-sm search_history" placeholder="Search by date...">
                  </div>

                  <div class="col-sm-2">
                      <button type="button" class="btn btn-sm btn-default submit_search"><i class="fas fa-search" title="Search"></i></button>
                      <button type="button" class="btn btn-sm btn-default clear_search"><i class="fas fa-undo-alt" title="Clear"></i></button>
                  </div>
              </div>

              <hr>
        <div class="table-responsive">
          <table class="table table-sm login_history_table">
            <thead>
              <tr>
                <th scope="col">SL NO.</th>
                <th scope="col">ATTEMPTS TYPE</th>
                <th scope="col"><a class="date_time_sort" style="color: blue;cursor:pointer" data-sort="DESC" title="Sort by date">DATE-TIME</a>
              </tr>
            </thead>
            <tbody>

            </tbody>

          </table>

        </div>
            <div class="row">
                <div class="col-sm-8">
                   TOTAL RECORDS : <h4 class='count' style="display:inline-block"></h4>
                </div>
                <div class="col-sm-4 pagination">
                    
                </div>
            </div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">CLOSE</button>
      </div>

    </div>
  </div>
</div>

<script>
  $(function() {

    attempt_type = $('.attempt_type');
    btn = $('.login_history');
    dte = $('.search_history');


    $('.date_time_sort').click(function(){
      id = btn.attr("data-id");
      
      var obj = { uidnr_admin: id, attempts_type : attempt_type.val(), created_on : dte.val() };
        where = JSON.stringify(obj);
        order = $(this).attr("data-sort");
        if(order == "DESC"){
          order = "ASC";
          $(this).attr("data-sort",order);
          get_history(5,0,null,"created_on",order,where,null);
        }
        else{
          order = "DESC";
          $(this).attr("data-sort",order);
          get_history(5,0,null,"created_on",order,where,null);
        }
       
      
    })

    $(document).on('click','.next',function(){
      limit = $(this).data("limit");
      offset = $(this).data("offset");
      id = btn.attr("data-id");
      var obj = { uidnr_admin: id, attempts_type : attempt_type.val(), created_on : dte.val() };
        where = JSON.stringify(obj)
       get_history(limit,offset,null,null,null,where,null);
    
    })

    $(document).on('click','.back',function(){
      limit = $(this).data("limit");
      offset = $(this).data("offset");
      id = btn.attr("data-id");
      var obj = { uidnr_admin: id, attempts_type : attempt_type.val(), created_on : dte.val() };
        where = JSON.stringify(obj)
      get_history(limit,offset,null,null,null,where,null);
      
    })

    $('.submit_search').click(function(){

        id = btn.attr("data-id");
        var obj = { uidnr_admin: id, attempts_type : attempt_type.val(), created_on : dte.val() };
        where = JSON.stringify(obj)
      get_history(5,0,null,null,null,where,null);
    })

   $('.clear_search').click(function(){
      id = btn.attr("data-id");
      var obj = { uidnr_admin: id };
      where = JSON.stringify(obj)
      get_history(5,0,null,null,null,where,null);
      dte.val("");
      attempt_type.val("");
   })

    btn.click(function() {
      id = $(this).attr("data-id");
      var obj = { uidnr_admin: id };
      where = JSON.stringify(obj)
      get_history(5,0,null,null,null,where,null);
    });

    get_history = (limit=null,start=null,search=null,sort=null,order=null,where=null,count=null) => {
      URL = "<?php echo base_url('Users/get_history') ?>";
      const _tokken = $('meta[name="_tokken"]').attr("value");
      $.post(URL, {
          where:where,
          search :search,
          sort:sort,
          order:order,
          limit:limit,
          start:start,
          count:count,
        _tokken: _tokken
      }, (res) => {
        if (res) {
          data = $.parseJSON(res);
          tbody = $('.login_history_table tbody');
          tbody.empty();
          if (data.data) {
            sn= 1;
            sL = (start+1);
            $.each(data.data, function(i, v) {


              if (v.attempts_type == '1') {
                ty = "SUCCESSFUL LOGIN";
              } else {
                ty = "UN-SUCCESSFUL LOGIN";
              }
              tr = '<tr>';
              tr += "<td>" + sL + "</td>";
              tr += "<td>" + ty + "</td>";
              tr += "<td>" + v.created_on + "</td>";
              tr += "</tr>";
              tbody.append(tr);
              sL++;
              sn++;

            })
  

          }
          else{
            tbody.append("<td>NO RECORD FOUND</td>");
            sn=0;
          }
        
          if(data.total_c >5){
            $('.pagination').empty();
            if(sn > 5){
                button = '<button type="button" class="back btn btn-sm bg-primary" data-limit="'+limit+'" data-offset="'+(start-limit)+'">Back</button>|<button type="button" class="next btn btn-sm bg-primary" data-limit="'+limit+'" data-offset="'+(limit+start)+'">Next</button>';
                 
            }
            else{
              button = '<button type="button" class="back btn btn-sm bg-primary" data-limit="'+limit+'" data-offset="'+(start-limit)+'">Back</button>|';
            }
            $('.pagination').append(button);
          }
          else{
            $('.pagination').empty();
          }
          $('.count').html(data.total_c);
        }
      

      });
    }
  });
</script>

<!-- end -->

<div class="modal fade" id="upload_signature" tabindex="-1" role="dialog" aria-labelledby="upload_signature" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content modal-sm" style="margin: 0px auto;">
      <div class="modal-header">
        <h5 class="modal-title" id="upload_imageLabel">UPLOAD SIGNATURE</h5>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form method="post" enctype="multipart/form-data" name="upload_signature" id="form_upload_signature">
        <div class="modal-body">
          <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
          <input type="hidden" name="admin_id" id="admin_id" value="">

          <div class="col-row">
            <div class="col-md-12 show_signature">

            </div>
            <div class="col-md-12">
              <label for="">Upload signature</label>
              <input type="file" name="sign_path" id="signature" value="">
            </div>
          </div>
        </div>
        <div class="modal-footer ">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">submit</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
  const _tokken = $('meta[name="_tokken"]').attr('value');
  $(document).ready(function() {
    $('#form_upload_signature').submit(function(e) {

      e.preventDefault();
      var self = $(this);
      var formData = new FormData(this);
      $.ajax({
        url: '<?php echo base_url(); ?>Users/upload_signature',
        contentType: false,
        processData: false,
        type: "post",
        async: true,
        data: formData,
        success: function(data) {
          var result = $.parseJSON(data);
          if (result.status > 0) {
            $.notify(result.msg, "success");
            $('#upload_signature').modal('hide');
            self.trigger('reset');
          } else {
            $.notify(result.msg, "error");
          }
        }
      });
      e.stopImmediatePropagation();
      return false;
    });

    $('.upload_sign').on('click', function() {
      var admin_id = $(this).data('id');

      $('#admin_id').val(admin_id);
      $.ajax({
        url: "<?php echo base_url('Users/get_signature') ?>",
        method: "post",
        data: {
          _tokken: _tokken,
          admin_id: admin_id,
        },
        success: function(data) {
          var result = $.parseJSON(data);
          if (result) {

            $('.show_signature').html('<img src="' + result + '" alt="">');
          } else {
            $('.show_signature').html('<h1>No Signature Found</h1>');

          }
        }
      });



    });
  });

  function Search() {

    var base_url = '<?php echo base_url('Users/index/'); ?>';
    var name = $('#name').val();
    var email = $('#email').val();
    var role = $('#role_id').val();
    var desig = $('#desig_id').val();
    // var end_date = $('#end_date').val();
    // var status = $('#status').val();
    base_url += (name) ? name : 'NULL';
    base_url += '/' + ((email) ? email : 'NULL');
    base_url += '/' + ((role) ? role : 'NULL');
    base_url += '/' + ((desig) ? desig : 'NULL');
    // base_url += '/' + ((end_date) ? end_date : 'NULL');
    // base_url += '/' + ((status) ? btoa(status) : 'NULL');
    location.href = base_url;
  }

  var css = {
    position: "absolute",
    width: "95%",
    "font-size": "12px",
    "z-index": 999,
    "overflow-y": "auto",
    "overflow-x": "hidden",
    "max-height": "200px",
    cursor: "pointer",
  };
  var base_url = $("body").attr("data-url");
  getAutolist(
    "role_id",
    "role_type",
    "role_list",
    "role_li",
    "",
    "admin_role_name",
    "id_admin_role as id,admin_role_name as name",
    "admin_role"
  );
  getAutolist(
    "desig_id",
    "designation_type",
    "desig_list",
    "desig_li",
    "",
    "designation_name",
    "designation_id as id,designation_name as name",
    "mst_designations"
  );

  function getAutolist(hide_input, input, ul, li, where, like, select, table) {

    var base_url = $("body").attr("data-url");
    var hide_inputEvent = $("input." + hide_input);
    var inputEvent = $("input." + input);
    var ulEvent = $("ul." + ul);

    inputEvent.focusout(function() {
      ulEvent.fadeOut();
    });

    inputEvent.on("click keyup", function(e) {
      var me = $(this);
      var key = $(this).val();
      var _URL = base_url + "get_auto_list";
      const _tokken = $('meta[name="_tokken"]').attr("value");
      e.preventDefault();
      if (key) {
        $.ajax({
          url: _URL,
          method: "POST",
          data: {
            key: key,
            where: where,
            like: like,
            select: select,
            table: table,
            _tokken: _tokken,
          },

          success: function(data) {
            var html = $.parseJSON(data);
            ulEvent.fadeIn();
            ulEvent.css(css);
            ulEvent.html("");
            if (html) {
              $.each(html, function(index, value) {
                ulEvent.append(
                  $(
                    '<li class="list-group-item ' +
                    li +
                    '"' +
                    "data-id=" +
                    value.id +
                    ">" +
                    value.name +
                    "</li>"
                  )
                );
              });
            } else {
              ulEvent.append(
                $(
                  '<li class="list-group-item ' +
                  li +
                  '"' +
                  'data-id="">NO RECORD FOUND</li>'
                )
              );
            }

            var liEvent = $("li." + li);
            liEvent.click(function() {
              var id = $(this).attr("data-id");
              var name = $(this).text();
              inputEvent.val(name);
              hide_inputEvent.val(id);
              ulEvent.fadeOut();
            });

            // ****
          },

        });

      } else {
        hide_inputEvent.val('');
      }
    });
  }
</script>

<!-- Modal to show log -->
<div class="modal fade user_log_windows" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">User log</h5>
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
          <tbody id="user_log"></tbody>
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- added by saurabh on 23-03-2021 -->
<script>
  $(document).ready(function() {



    const url = $('body').data('url');
    const _tokken = $('meta[name="_tokken"]').attr('value');
    // Ajax call to get log
    $('.log_view').click(function() {
      $('#user_log').empty();
      var user_id = $(this).data('id');
      $.ajax({
        type: 'post',
        url: url + 'Users/get_user_log',
        data: {
          _tokken: _tokken,
          user_id: user_id
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

          });
          $('#user_log').append(value);
        }
      });
    });
    // ajax call to get log ends here
  });
</script>
<!-- added by saurabh on 23-03-2021 -->

<script>
  function searchfilter() {

    var url = '<?php echo base_url("Users/index"); ?>';

    var crm_flag_id = $('.crm_flag_filter').val();

    if (crm_flag_id != "") {
      url = url + '/' + btoa(crm_flag_id);
    } else {
      crm_flag_id = "";
      url = url + '/NULL';
    }

    var lab_analyst_id = $('.lab_analyst_filter').val();
    if (lab_analyst_id != "") {
      url = url + '/' + btoa(lab_analyst_id);
    } else {
      lab_analyst_id = "";
      url = url + '/NULL';
    }

    var division_id = $('.division_filter').val();

    if (division_id != "") {
      url = url + '/' + btoa(division_id);
    } else {
      division_id = "";
      url = url + '/NULL';
    }

    var dept_id = $('.department_filter').val();
    if (dept_id != "") {
      url = url + '/' + btoa(dept_id);
    } else {
      dept_id = "";
      url = url + '/NULL';
    }

    var branch_id = $('.branch_filter').val();
    if (branch_id != "") {
      url = url + '/' + btoa(branch_id);
    } else {
      branch_id = "";
      url = url + '/NULL';
    }

    var username = $('.username_filter').val();
    if (username != "") {
      url = url + '/' + btoa(username);
    } else {
      username = "";
      url = url + '/NULL';
    }

    var designation_id = $('.designation_filter').val();
    if (designation_id != "") {
      url = url + '/' + btoa(designation_id);
    } else {
      designation_id = "";
      url = url + '/NULL';
    }

    var rol_id = $('.rol_filter').val();
    if (rol_id != "") {
      url = url + '/' + btoa(rol_id);
    } else {
      rol_id = "";
      url = url + '/NULL';
    }

    var search = $('#search').val();
    if (search != '') {
      url = url + '/' + btoa(search);
    } else {
      url = url + '/NULL';
    }

    window.location.href = url;

  }
</script>

</script>
<script>
  $(document).ready(function() {

    function Get_dropDropdown_by_Ajax(selectBoxClass, placeholder, tableColumn, table, where = null, selected_id = null) {
      var selectEvent = $('select.' + selectBoxClass);
      const _tokken = $('meta[name="_tokken"]').attr("value");
      $.ajax({
        url: "<?php echo base_url('Users/get_dropdown_by_ajax') ?>",
        method: "POST",
        data: {
          select: tableColumn,
          from: table,
          where: where,
          _tokken: _tokken
        },
        success: function(response) {
          var data = $.parseJSON(response);
          selectEvent.html("");
          var option = "<option value='' selected>" + placeholder + "</option>";
          selectEvent.append(option);

          if (data) {
            $.each(data, function(index, value) {
              if (selected_id == value.id) {
                var option = "<option value='" + value.id + "' selected>" + value.name + "</option>";
              } else {
                var option = "<option value='" + value.id + "' >" + value.name + "</option>";
              }
              selectEvent.append(option);
            });
          } else {
            var option = "<option value='' >NO RECORD FOUND</option>";
            selectEvent.append(option);
          }

        }
      });
      return false;
    }


    var division_id = "<?php echo base64_decode($division_id); ?>";

    Get_dropDropdown_by_Ajax('division_filter', 'DIVISION', 'division_id as id,division_name as name', 'mst_divisions', '', division_id);

    var dept_id = "<?php echo base64_decode($dept_id); ?>";
    Get_dropDropdown_by_Ajax('department_filter', 'DEPARTMENT', 'dept_id as id,dept_name as name', 'mst_departments', '', dept_id);

    var branch_id = "<?php echo base64_decode($branch_id); ?>";
    Get_dropDropdown_by_Ajax('branch_filter', 'BRANCH', 'branch_id as id,branch_name as name', 'mst_branches', '', branch_id);

    var username = "<?php echo base64_decode($username); ?>";
    Get_dropDropdown_by_Ajax('username_filter', 'NAME', 'uidnr_admin as id,CONCAT(admin_fname," ",admin_lname) as name', 'admin_profile', '', username);

    var designation_id = "<?php echo base64_decode($designation_id); ?>";
    Get_dropDropdown_by_Ajax('designation_filter', 'DESIGNATION', 'designation_id as id,designation_name as name', 'mst_designations', '', designation_id);

    var rol_id = "<?php echo base64_decode($rol_id); ?>";
    Get_dropDropdown_by_Ajax('rol_filter', 'ROLE', 'id_admin_role as id,admin_role_name as name', 'admin_role', '', rol_id);



  });
</script>