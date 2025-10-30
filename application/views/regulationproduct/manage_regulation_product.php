<div class="content-wrapper">
    <section class="content-header">
        <!-- container fluid start -->
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h1>REGULATION PRODUCT</h1>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-sm-6">
                                    <?php 
                                    if (exist_val('RegulationProduct/add_regpro', $this->session->userdata('permission'))) { ?>    <!-- added by millan on 19-02-2021 -->
                                        <a href="" class="btn btn-primary btn-rounded" data-bs-toggle="modal" data-bs-target="#modalLoginForm">ADD</a>
                                    <?php 
                                } ?>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-8">
                                    <div class="row">
                                        <?php if ($title_product) {
                                            $title = $title_product;
                                        } else {
                                            $title = 0;
                                        } ?>
                                        <div class="col-sm-4">
                                            <select name="title" id="title" class="form-control form-control-sm">
                                                <option value="">Select Title</option>
                                                <?php foreach ($pro_titles as $name) { ?>
                                                    <option value="<?php echo $name->reg_product_id; ?>" <?php echo ($title == $name->reg_product_id) ? "selected" : ""; ?>> <?php echo $name->reg_product_title; ?> </option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <?php if ($created_pesron) {
                                            $uidnr_admin = $created_pesron;
                                        } else {
                                            $uidnr_admin = "";
                                        } ?>
                                        <div class="col-sm-4">
                                            <select name="created_by" id="created_by" class="form-control form-control-sm">
                                                <option value="">Select Created By</option>
                                                <?php foreach ($created_by_name as $cr_name) { ?>
                                                    <option value="<?php echo $cr_name->uidnr_admin; ?>" <?php echo ($uidnr_admin == $cr_name->uidnr_admin) ? "selected" : ""; ?>> <?php echo $cr_name->created_by; ?> </option>
                                                <?php } ?>
                                            </select>
                                        </div>

                                        <div class="col-sm-4">
                                            <select name="" id="" class="form-control form-control-sm status">
                                            <option value="" >Status</option>
                                            <option value="1" <?php echo ($status=='1')? "selected":"";?>>Active</option>
                                            <option value="0" <?php echo ($status=='0')? "selected":"";?>>Deactive</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3 text-right">
                                    <input value="<?php echo (($search != 'NULL') ? $search : ""); ?>" id="search" class="form-control form-control-sm" type="text" placeholder="Search" aria-label="Search">
                                </div>
                                <div class="col-sm-1">
                                    <button onclick="searchfilter();" type="button" class="btn btn-sm btn-default" title="Search">
                                        <img src="<?php echo base_url('assets/images/search.png') ?>" alt="search">
                                    </button>
                                    <a class="btn btn-sm btn-default" href="<?php echo base_url('RegulationProduct'); ?>" title="Clear">
                                        <img src="<?php echo base_url('assets/images/drop.png') ?>" alt="Clear">
                                    </a>
                                </div>
                            </div>
                        </div>
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
                                        if ($title_product != "") {
                                            $title_product = base64_encode($title_product);
                                        } else {
                                            $title_product = "NULL";
                                        }
                                        if ($created_pesron != "") {
                                            $created_pesron = base64_encode($created_pesron);
                                        } else {
                                            $created_pesron = "NULL";
                                        }
                                        if ($order != "") {
                                            $order = $order;
                                        } else {
                                            $order = "NULL";
                                        }

                                        if ($status != "") {
                                            $status = $status;
                                        } else {
                                            $status = "NULL";
                                        }
                                        ?>
                                        
                                        <th scope="col">S. NO.</th>
                                        <th scope="col"><a href="<?php echo base_url('RegulationProduct/index/' . $title_product . '/' . $created_pesron . '/' . $status . '/'. $search . '/' . 'crp.reg_product_title' . '/' . $order) ?>">PRODUCT TITLE</a></th>
                                        <?php 
                                        if ( (exist_val('RegulationProduct/regpro_status', $this->session->userdata('permission'))) ) { ?>
                                            <th scope="col">STATUS</th>
                                        <?php
                                     } ?>
                                        
                                        <th scope="col"><a href="<?php echo base_url('RegulationProduct/index/' . $title_product . '/' . $created_pesron . '/' . $status . '/'. $search . '/' . 'ap.admin_fname' . '/' . $order) ?>">CREATED BY</a></th>
                                        <th scope="col"><a href="<?php echo base_url('RegulationProduct/index/' . $title_product . '/' . $created_pesron . '/' . $status . '/'. $search . '/' . 'crp.created_date' . '/' . $order) ?>">CREATED ON</a></th>
                                        <?php
                                         if ( (exist_val('RegulationProduct/fetch_regpro_for_edit', $this->session->userdata('permission'))) || (exist_val('RegulationProduct/get_user_log_data', $this->session->userdata('permission'))) || (exist_val('RegulationProduct/delete_regpro', $this->session->userdata('permission'))) ) { ?>
                                            <th scope="col">ACTION</th>
                                        <?php
                                     } ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $sn = $this->uri->segment('9') + 1;
                                    if ($regpro_list) {
                                        foreach ($regpro_list as $key => $value) { ?>
                                            <tr>
                                                <td><?php echo $sn; ?></td>
                                                <td><?php echo $value->reg_product_title ?></td>
                                                <?php
                                                 if ( (exist_val('RegulationProduct/regpro_status', $this->session->userdata('permission'))) ) { ?>
                                                    <?php if ($value->status == 1) { ?>
                                                        <td><span class="btn btn-success status_chng" id="active" data-id="<?php echo $value->reg_product_id; ?>">Active</span></td>
                                                    <?php } else { ?>
                                                        <td><span class="btn btn-danger status_chng" id="deactive" data-id="<?php echo $value->reg_product_id; ?>">Deactive</span></td>
                                                    <?php } ?>
                                                <?php 
                                            } ?>
                                                <td><?php echo $value->admin_fname ?></td>
                                                <td><?php echo change_time($value->created_date,$this->session->userdata('timezone')); ?></td>
                                                <td>
                                                    <?php
                                                     if (exist_val('RegulationProduct/fetch_regpro_for_edit', $this->session->userdata('permission'))) { ?>    <!-- added by millan on 19-02-2021 -->
                                                        <a href="#" class="btn btn-circle btn-sm editcls" data-bs-toggle="modal" data-bs-target="#EditRoleForm" data-id="<?php echo $value->reg_product_id; ?>">
                                                            <img src="<?php echo base_url('assets/images/mem_edit.png') ?>" alt="Edit Regulation Product" title="Edit Regulation Product">
                                                        </a>
                                                    <?php 
                                                } ?>    
                                                    
                                                    <?php 
                                                    if (exist_val('RegulationProduct/delete_regpro', $this->session->userdata('permission'))) { ?>    <!-- added by millan on 19-02-2021 -->
                                                        <a href="<?php echo base_url(); ?>RegulationProduct/delete_regpro?reg_product_id=<?php echo $value->reg_product_id; ?>" onclick="if (! confirm('Are you Sure Want To Delete Regulation Product ?')) { return false; }">
                                                            <button class="btn btn-danger btn-circle btn-sm "><i class="fa fa-times"></i></button>
                                                        </a>
                                                    <?php
                                                 } ?>

                                                
                                                     <?php 
                                                    if(exist_val('RegulationProduct/get_log_data',$this->session->userdata('permission'))){ ?> 
                                                    <a href="javascript:void(0)" data-id="<?php echo $value->reg_product_id ?>" class="log_view" data-bs-toggle='modal' data-bs-target='#lo_view_target' class="btn btn-sm" title="Log View"><img src="<?php echo base_url('assets/images/log-view.png'); ?>" alt="Log view" width="20px"></a>
                                                    <?php 
                                                }?>
                                                </td>
                                            </tr>
                                    <?php $sn++; } } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- card end -->
                </div>
            </div>

            <!-- menu end -->

            <div class="card-header">
                <?php if ($regpro_list && count($regpro_list) > 0) { ?>
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


<div class="modal fade" id="lo_view_target" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content" style="max-height: 500px;">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">MANAGE REGULATION PRODUCT LOG</h5>
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
            <tbody id="table_log"></tbody>
          </table>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
</div>


<!-- MODAL FOR ADD NEW REGULATION PRODUCT ADDED BY MILLAN -->
    <div class="modal fade" id="modalLoginForm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content modal-sm">
                <div class="modal-header text-center">
                    <h6 class="modal-title w-100 font-weight-bold text-info"><i class="fa fa-plus"></i> Add New Regulation Product</h6>
                    <button class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="regulationproduct_add" action="javascript:void(0);">
                    <input type="hidden" class="txt_csrfname" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
                    <div class="modal-body mx-3">
                        <div class="md-form mb-5">
                            <label data-error="wrong" data-success="right" for="reg_product_title">Product</label>
                            <input required type="text" id="reg_product_title" placeholder="Specify Product" name="reg_product_title" class="form-control validate">
                        </div>
                        <div class="md-form mb-5">
                            <label data-error="wrong" data-success="right" for="notified_body_id">Notification Body</label>
                            <select name="notified_body_id[]" id="notified_body_id" class="form-control" multiple>
                                <option value="">Select Notified Body</option>
                                <?php foreach ($notified_bodies as $nbs) { ?>
                                    <option value="<?php echo $nbs['notified_body_id']; ?>"><?php echo $nbs['notified_body_name']; ?></option>
                                <?php  } ?>
                            </select>
                        </div>
                        <div class="md-form mb-5">
                            <label data-error="wrong" data-success="right" for="crp-status">Status</label>
                            <select required class="form-control" id="status" name="status" aria-invalid="false" aria-required="true">
                                <option value="">Choose Status</option>
                                <option value="1">Active</option>
                                <option value="0">In-Active</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer d-flex justify-content-center">
                        <button class="btn btn-default">Add</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<!-- MODAL FOR ADD NEW REGULATION PRODUCT ENDS -->

<!-- MODAL FOR EDIT REGULATION PRODUCT ADDED BY MILLAN -->
    <div class="modal fade" id="EditRoleForm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content modal-sm">
                <div class="modal-header text-center">
                    <h6 class="modal-title w-100 font-weight-bold text-info"><i class="fa fa-edit"></i> Edit Regulation Product</h6>
                    <button class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="regulationproduct_update" action="javascript:void(0);">
                    <input type="hidden" class="txt_csrfname" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
                    <input type="hidden" name="reg_product_id" class="form-control validate" id="reg_product_id" value="" placeholder="Specify Product">
                    <div class="modal-body mx-3">
                        <div class="md-form mb-5">
                            <label data-error="wrong" data-success="right" for="reg_product_title">Product</label>
                            <input required type="text" id="reg_product_title_edit" name="reg_product_title" class="form-control validate">
                        </div>
                        <div class="md-form mb-5">
                            <label data-error="wrong" data-success="right" for="notified_body_id">Notification Body</label>
                            <select name="notified_body_id[]" id="notified_body_id_edit" class="form-control" multiple>
                                <option value="">Select Notified Body</option>
                                <?php foreach ($notified_bodies as $nbs) { ?>
                                    <option value="<?php echo $nbs['notified_body_id']; ?>"><?php echo $nbs['notified_body_name']; ?></option>
                                <?php  } ?>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer d-flex justify-content-center">
                        <button type="submit" class="btn btn-default">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<!-- MODAL FOR EDIT REGULATION PRODUCT ENDS -->

<script>
    $(document).ready(function() {
        $(".modal-content").css("margin","0 auto");
        $('#notified_body_id, #notified_body_id_edit').select2();
    });
</script>

<script>
    $(document).ready(function() {
        $('.status_chng').click(function() {
            var reg_product_id = $(this).attr("data-id");
            var _tokken = $('meta[name="_tokken"]').attr('value');
            $.ajax({
                url: "<?php echo base_url(); ?>RegulationProduct/regpro_status",
                data: {
                    "reg_product_id": reg_product_id,
                    "_tokken": _tokken
                },
                type: 'post',
                success: function(result) {
                    location.reload();
                }
            });
        });
    });

    $('#regulationproduct_add').on('submit', function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        $.ajax({
            url: "<?php echo base_url('RegulationProduct/add_regpro') ?>",
            method: "POST",
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                var result = $.parseJSON(response);
                if (result.status > 0) {
                    $('#modalLoginForm').modal('hide');
                    $("#regulationproduct_add").trigger('reset');
                    window.location.reload();
                } else {
                    $.notify(result.msg, 'error');
                }
                if (result.errors) {
                    var error = result.errors;
                    $('.form_errors').remove();
                    $.each(error, function(i, v) {
                        $('#regulationproduct_add select[name="' + i + '"]').after('<span class="text-danger form_errors">' + v + '</span>');
                        $('#regulationproduct_add input[name="' + i + '"]').after('<span class="text-danger form_errors">' + v + '</span>');
                        $('#regulationproduct_add textarea[name="' + i + '"]').after('<span class="text-danger form_errors">' + v + '</span>');
                    });

                } else {
                    $('.form_errors').remove();
                }
            }
        });
        return false;
    });

    $(document).on("click", ".editcls", function() {
        var reg_product_id = $(this).data('id');
        var _tokken = $('meta[name="_tokken"]').attr('value');
        $.ajax({
            url: "<?php echo base_url(); ?>RegulationProduct/fetch_regpro_for_edit",
            data: {
                "reg_product_id": reg_product_id,
                _tokken: _tokken
            },
            type: 'post',
            success: function(result) {
                var data = $.parseJSON(result);
                var notify_ids = data.notified_body_id;
                $('#EditRoleForm #reg_product_id').val(data.reg_product_id);
                $('#EditRoleForm #reg_product_title_edit').val(data.reg_product_title);
                items = notify_ids.split(',');
                $('#EditRoleForm #notified_body_id_edit').val(items);
                $('#notified_body_id_edit').trigger('change'); 
            }
        });
    });


   

    $('#regulationproduct_update').on('submit', function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        $.ajax({
            url: "<?php echo base_url('RegulationProduct/update_regpro') ?>",
            method: "POST",
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                var result = $.parseJSON(response);
                if (result.status > 0) {
                    $('#EditRoleForm').modal('hide');
                    $("#regulationproduct_update").trigger('reset');
                    window.location.reload();
                } else {
                    $.notify(result.msg, 'error');
                }
                if (result.errors) {
                    var error = result.errors;
                    $('.form_errors').remove();
                    $.each(error, function(i, v) {
                        $('#regulationproduct_update input[name="' + i + '"]').after('<span class="text-danger form_errors">' + v + '</span>');
                        $('#regulationproduct_update textarea[name="' + i + '"]').after('<span class="text-danger form_errors">' + v + '</span>');
                    });

                } else {
                    $('.form_errors').remove();
                }
            }
        });
        return false;
    });
</script>

<script>
    function searchfilter() {
        var url = '<?php echo base_url("RegulationProduct/index"); ?>';
        var title_product = $('#title').val();
        if (title_product != "") {
            url = url + '/' + btoa(title_product);
        } else {
            title_product = "";
            url = url + '/NULL';
        }

        var created_pesron = $('#created_by').val();
        if (created_pesron != "") {
            url = url + '/' + btoa(created_pesron);
        } else {
            created_pesron = "";
            url = url + '/NULL';
        }

        var status = $('.status').val();
        if (status != "") {
            url = url + '/' + btoa(status);
        } else {
            status = "";
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

<script>
    $(document).ready(function() {
      const url = $('body').data('url');
      const _tokken = $('meta[name="_tokken"]').attr('value');
      // Ajax call to get log
      $('.log_view').click(function() {
        $('#table_log').empty();
        var id = $(this).data('id');
        $.ajax({
          type: 'post',
          url: url + 'RegulationProduct/get_log_data',
          data: {
            _tokken: _tokken,
            id: id
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
              var taken_at = new Date(v.taken_at+ ' UTC');
              value += '<tr>';
              value += '<td>' + sno + '</td>';
              value += '<td>' + operation + '</td>';
              value += '<td>' + action_message + '</td>';
              value += '<td>' + taken_by + '</td>';
              value += '<td>' + taken_at.toLocaleString() + '</td>';
              value += '</tr>';

            });
            $('#table_log').append(value);
          }
        });
      });
      // ajax call to get log ends here
    });
  </script>
