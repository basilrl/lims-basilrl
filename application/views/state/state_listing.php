<script src="<?php echo base_url('ckeditor/ckeditor.js'); ?>"></script>
<div class="content-wrapper">
    <section class="content-header">
        <!-- container fluid start -->
        <div class="container-fluid">
        <div class="row">
          <div class="col-sm-12 text-center">
          <div class="float-left mt-3">
          <?php if (exist_val('State/add_state', $this->session->userdata('permission'))) { ?>
            <button type="button" class="btn btn-sm btn-primary add float-right" data-bs-toggle="modal" data-bs-target=".add_state" title="ADD NEW STATE"> <i class="fa fa-plus"></i> Add</button>
                                          <?php } ?>
                                      </div>
            <h1 class="text-bold mt-3 mb-3">STATES</h1>
          </div>
        </div>
      </div>
      <div class="container-fluid jumbotron p-3">
      <div class="row">
                        <div class="col-md-2">
                            <?php
                            if ($id_country) {
                                $country_id = $id_country;
                            } else {
                                $country_id = 0;
                            }
                            ?>
                            <select name="country_id" id="country_id" class="form-control form-control-sm">
                                <option value="">Select Country</option>
                                <?php foreach ($countries as $coun) { ?>
                                    <option value="<?php echo $coun->country_id; ?>" <?php echo ($country_id == $coun->country_id) ? "selected" : ""; ?>> <?php echo $coun->country_name; ?> </option>
                                <?php } ?>
                            </select>
                        </div>
                        <?php
                        if ($id_state) {
                            $province_id = $id_state;
                        } else {
                            $province_id = 0;
                        }
                        ?>
                        <div class="col-md-2 ">
                            <select name="province_id" id="province_id" class="form-control form-control-sm">
                                <option value="">Select State</option>
                                <?php foreach ($states as $stat) { ?>
                                    <option value="<?php echo $stat->province_id; ?>" <?php echo ($province_id == $stat->province_id) ? "selected" : ""; ?>> <?php echo $stat->province_name; ?> </option>
                                <?php } ?>
                            </select>
                        </div>
                        <?php
                        if ($created_pesron) {
                            $uidnr_admin = $created_pesron;
                        } else {
                            $uidnr_admin = "";
                        }
                        ?>
                        <div class="col-md-2">
                            <select name="created_by" id="created_by" class="form-control form-control-sm">
                                <option value="">Select Created By</option>
                                <?php foreach ($created_by_name as $cr_name) { ?>
                                    <option value="<?php echo $cr_name->uidnr_admin; ?>" <?php echo ($uidnr_admin == $cr_name->uidnr_admin) ? "selected" : ""; ?>> <?php echo $cr_name->created_by; ?> </option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-md-3 ">
                            <select name="status" id="status" class="form-control form-control-sm">
                                <option value="">Select Status</option>
                                <option value="1" <?php echo (($id_status == "1") ? "selected" : ""); ?>> Active </option>
                                <option value="0" <?php echo (($id_status == "0") ? "selected" : ""); ?>> DeActive </option>
                            </select>
                        </div>
                        <div class="col-sm-3">
                    <div class="input-group">
                    <input value="<?php echo (($search != 'NULL') ? $search : ""); ?>" id="search" class="form-control form-control-sm" type="text" placeholder="Search" aria-label="Search">
                    <div class="input-group-append">
                    <button onclick="searchfilter();" type="button" class="btn btn-sm btn-secondary" title="Search">
                      <i class="fa fa-search"></i>
                    </button>
                    <a class="btn btn-sm btn-danger ml-1" href="<?php echo base_url('State'); ?>" title="Clear">
                      <i class="fa fa-trash"></i>
                    </a>
                    </div>
                  </div></div>
                       

                       
                    </div>
      </div>
        <div class="container-fluid">
            <div class="card">
                
                   
                    <div class="table-responsive p-2">
                        <table class=" table table-sm">
                            <thead>
                                <tr class="">
                                    <?php
                                    if ($search) {
                                        $search = base64_encode($search);
                                    } else {
                                        $search = "NULL";
                                    }
                                    if ($id_country  != "") {
                                        $id_country = base64_encode($id_country);
                                    } else {
                                        $id_country = "NULL";
                                    }
                                    if ($id_state != "") {
                                        $id_state = base64_encode($id_state);
                                    } else {
                                        $id_state = "NULL";
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
                                    ?>
                                    <th scope="col">S. NO.</th>
                                    <th scope="col"><a href="<?php echo base_url('State/index/' . $id_country . '/' . $id_state . '/' . $created_pesron . '/' .  $id_status . '/' . $search . '/' . 'msc.country_name' . '/' . $order) ?>">COUNTRY </a></th>
                                    <th scope="col"><a href="<?php echo base_url('State/index/' . $id_country . '/' . $id_state . '/' . $created_pesron . '/' .  $id_status . '/' . $search . '/' . 'msp.province_name' . '/' . $order) ?>">STATE</a></th>
                                    <th>STATE CODE</th>
                                    <th scope="col"><a href="<?php echo base_url('State/index/' . $id_country . '/' . $id_state . '/' . $created_pesron . '/' .  $id_status . '/' . $search . '/' . 'ap.admin_fname' . '/' . $order) ?>">CREATED BY</a></th>
                                    <th scope="col"><a href="<?php echo base_url('State/index/' . $id_country . '/' . $id_state . '/' . $created_pesron . '/' .  $id_status . '/' . $search . '/' . 'msp.created_on' . '/' . $order) ?>">CREATED ON</a></th>
                                    <?php if ((exist_val('State/state_status', $this->session->userdata('permission')))) { ?>
                                        <th scope="col"><a href="<?php echo base_url('State/index/' . $id_country . '/' . $id_state . '/' . $created_pesron . '/' .  $id_status . '/' . $search . '/' . 'msp.status' . '/' . $order) ?>">STATUS</a></th>
                                    <?php } ?>
                                    <?php if ((exist_val('State/edit_state', $this->session->userdata('permission'))) || (exist_val('State/get_user_log_data', $this->session->userdata('permission')))) { ?>
                                        <th scope="col">ACTION</th>
                                    <?php } ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $sn = $this->uri->segment('10') + 1;
                                if ($state_list) {
                                    foreach ($state_list as $key => $value) { ?>
                                        <tr>
                                            <td><?php echo $sn; ?></td>
                                            <td><?php echo $value->country_name; ?></td>
                                            <td><?php echo $value->province_name; ?></td>
                                            <td><?php echo $value->state_code; ?></td>
                                            <td><?php echo strtoupper($value->created_by); ?></td>
                                            <td><?php echo change_time($value->created_on, $this->session->userdata('timezone')); ?></td>
                                            <?php if ((exist_val('State/state_status', $this->session->userdata('permission')))) { ?>
                                                <?php if ($value->status == 1) { ?>
                                                    <td><span class="btn btn-sm btn-success status_chng" id="active" data-id="<?php echo $value->province_id; ?>">Active</span></td>
                                                <?php } else { ?>
                                                    <td><span class="btn btn-sm btn-danger status_chng" id="deactive" data-id="<?php echo $value->province_id; ?>">Deactive</span></td>
                                                <?php } ?>
                                            <?php } ?>
                                            <td>
                                                <?php if ((exist_val('State/edit_state', $this->session->userdata('permission')))) { ?>
                                                    <button type="button" class="btn btn-sm edit_state_data " title="Edit State" data-bs-toggle="modal" data-bs-target="#edit_state" data-id="<?php echo $value->province_id ?>">
                                                        <i class="fa fa-edit" title="Edit State"></i>
                                                    </button>
                                                <?php } ?>

                                                <?php if ((exist_val('State/get_state_log', $this->session->userdata('permission')))) { ?>
                                                    <a href="javascript:void(0)" data-id="<?php echo $value->province_id; ?>" class="btn log_view btn-sm" data-bs-toggle="modal" data-bs-target="#exampleModal" title="Log View"><i class="fa fa-eye" title="View Log"></i></a>
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
                <div class="">
                    <div class="row">
                        <?php if ($state_list && count($state_list) > 0) { ?>
                            <div class="col-md-6"><?php echo $result_count; ?></div>
                            <div class="col-md-6"><?php echo $links ?></div>
                        <?php } else { ?>
                            <h5>NO RECORD FOUND</h5>
                        <?php } ?>
                    </div>
                </div>
            
        </div>
    </section>
</div>


<!-- ADD STATE MODAL -->
<div class="modal fade add_state" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">ADD STATE</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="state_add_form" action="javascript:void(0);">
                <div class="modal-body">
                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                    <div class="row">
                        <div class="col-md-3">
                            <label for=""><b>COUNTRY</b></label>
                            <select name="mst_provinces_country_id" class="form-control form-control-sm validate mst_provinces_country_id">
                                <option value="">Select Country</option>
                                <?php foreach ($countries as $cont) { ?>
                                    <option value="<?php echo $cont->country_id; ?>"> <?php echo $cont->country_name; ?> </option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for=""><b>STATE</b></label>
                            <input type="text" class="form-control form-control-sm state_id" name="province_name" placeholder="Enter State Name">
                        </div>
                        <div class="col-md-3">
                            <label for=""><b>STATE CODE</b></label>
                            <input type="text" class="form-control form-control-sm" name="state_code" placeholder="Enter State Code">
                        </div>
                        <div class="col-md-3">
                            <label for="status"><b>Status:</b></label>
                            <select name="status" class="form-control form-control-sm validate status">
                                <option value="" selected disabled>Select Status</option>
                                <option value="1">Active</option>
                                <option value="0">InActive</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">CLOSE</button>
                    <button type="submit" class="btn btn-primary add_regulation_button">ADD</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- END -->

<!-- EDIT STATE MODAL -->
<div class="modal fade" id="edit_state" tabindex="-1" role="dialog" aria-labelledby="state_editLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content" style="text-align: right">
            <div class="modal-header">
                <h5 class="modal-title" id="state_editLabel">EDIT STATE DETAILS</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" id="update_state_form" action="<?php echo base_url('State/update_state'); ?>">
                <div class="modal-body">
                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                    <input type="hidden" id="province_id" name="province_id" value="">
                    <div class="row p-2">
                        <div class="col-md-4 form-group">
                            <label for=""><b>COUNTRY</b></label>
                            <select name="mst_provinces_country_id" class="form-control form-control-sm validate mst_provinces_country_id_edit">
                                <option value="">Select Country</option>
                                <?php foreach ($countries as $cont) { ?>
                                    <option value="<?php echo $cont->country_id; ?>"> <?php echo $cont->country_name; ?> </option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-md-4 form-group">
                            <label for=""><b>STATE CODE</b></label>
                            <input type="text" class="form-control form-control-sm state_code" name="state_code" placeholder="Enter State Code">
                        </div>
                        <div class="col-md-4 form-group">
                            <label for=""><b>STATE</b></label>
                            <input type="text" class="form-control form-control-sm state_id_edit" name="province_name" placeholder="Enter State Name">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- END -->

<!-- State Log Modal Starts -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">State Log</h5>
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
                    <tbody id="country_log"></tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- State Log Modal Ends -->

<script>
    function searchfilter() {
        var url = '<?php echo base_url("State/index"); ?>';
        var id_country = $('#country_id').val();
        if (id_country != "") {
            url = url + '/' + btoa(id_country);
        } else {
            id_country = "";
            url = url + '/NULL';
        }

        var id_state = $('#province_id').val();
        if (id_state != "") {
            url = url + '/' + btoa(id_state);
        } else {
            id_state = "";
            url = url + '/NULL';
        }

        var created_pesron = $('#created_by').val();
        if (created_pesron != "") {
            url = url + '/' + btoa(created_pesron);
        } else {
            created_pesron = "";
            url = url + '/NULL';
        }

        var id_status = $('#status').val();
        if (id_status != "") {
            url = url + '/' + btoa(id_status);
        } else {
            id_status = "";
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

    $(document).ready(function() {

        var style = {
            'margin': '0 auto'
        };
        $('.modal-content').css(style);

        // ADD STATE
        $('#state_add_form').on('submit', function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            $.ajax({
                url: "<?php echo base_url('State/add_state') ?>",
                method: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    var result = $.parseJSON(response);
                    if (result.status > 0) {
                        $('.add_state').modal('hide');
                        $("#state_add_form").trigger('reset');
                        window.location.reload();
                    } else {
                        $.notify(result.msg, 'error');
                    }
                    if (result.errors) {
                        var error = result.errors;
                        $('.form_errors').remove();
                        $.each(error, function(i, v) {
                            $('#state_add_form select[name="' + i + '"]').after('<span class="text-danger form_errors">' + v + '</span>');
                            $('#state_add_form input[name="' + i + '"]').after('<span class="text-danger form_errors">' + v + '</span>');
                        });

                    } else {
                        $('.form_errors').remove();
                    }
                }
            });
            return false;
        });

        // EDIT STATE
        $('.edit_state_data').on('click', function() {
            var province_id = $(this).data('id');
            get_edit_state_data(province_id);
            $('#update_state_form #province_id').val(province_id);
        });

        function get_edit_state_data(province_id) {
            const _tokken = $('meta[name="_tokken"]').attr("value");
            $.ajax({
                url: "<?php echo base_url('State/edit_state') ?>",
                method: "POST",
                data: {
                    province_id: province_id,
                    _tokken: _tokken
                },
                success: function(response) {
                    var data = $.parseJSON(response);
                    if (data) {
                        $('#update_state_form .mst_provinces_country_id_edit option[value=' + data.mst_provinces_country_id + ']').attr('selected', 'selected');
                        $('#update_state_form .state_code').val(data.state_code);
                        $('#update_state_form .state_id_edit').val(data.province_name);
                    }
                }
            });
            return false;
        }

        $('#update_state_form').on('submit', function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            $.ajax({
                url: "<?php echo base_url('State/update_state') ?>",
                method: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    var result = $.parseJSON(response);
                    if (result.status > 0) {
                        $('#edit_state').modal('hide');
                        $("#update_state_form").trigger('reset');
                        window.location.reload();
                    } else {
                        $.notify(result.msg, 'error');
                    }
                    if (result.errors) {
                        var error = result.errors;
                        $('.form_errors').remove();
                        $.each(error, function(i, v) {
                            $('#update_state_form select[name="' + i + '"]').after('<span class="text-danger form_errors">' + v + '</span>');
                            $('#update_state_form input[name="' + i + '"]').after('<span class="text-danger form_errors">' + v + '</span>');
                        });

                    } else {
                        $('.form_errors').remove();
                    }
                }
            });
            return false;
        });
        // END

        // STATE STATUS
        $('.status_chng').click(function() {
            var province_id = $(this).attr("data-id");
            var _tokken = $('meta[name="_tokken"]').attr('value');
            $.ajax({
                url: "<?php echo base_url(); ?>State/state_status",
                data: {
                    "province_id": province_id,
                    "_tokken": _tokken
                },
                type: 'post',
                success: function(result) {
                    location.reload();
                }
            });
        });
        // STATE STATUS END

        // State Log
        $('.log_view').click(function(e) {
            e.preventDefault();
            $('#country_log').empty();
            var province_id = $(this).data('id');
            const _tokken = $('meta[name="_tokken"]').attr("value");
            $.ajax({
                type: 'post',
                url: "<?php echo base_url('State/get_state_log') ?>",
                data: {
                    _tokken: _tokken,
                    province_id: province_id
                },
                success: function(data) {
                    var data = $.parseJSON(data);
                    if (data) {
                        sn = 1;
                        $.each(data, function(index, log) {
                            var taken_at = new Date(log.taken_at + ' UTC');
                            var value = '';
                            value += '<tr>';
                            value += '<td>' + sn + '</td>';
                            value += '<td>' + log.action_taken + '</td>';
                            value += '<td>' + log.text + '</td>';
                            value += '<td>' + log.taken_by + '</td>';
                            value += '<td>' + taken_at.toLocaleString() + '</td>';
                            value += '</tr>';
                            $('#country_log').append(value);
                            sn++;
                        });
                    } else {
                        var value = '';
                        value += '<tr>';
                        value += '<td colspan="5">';
                        value += "<h4> NO RECORD FOUND! </h4>";
                        value += "</td>";
                        value += "</tr>";
                        $('#country_log').append(value);
                    }
                }
            });
            // return false;
        });
        //ends
    });
</script>