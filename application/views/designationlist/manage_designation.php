<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12 text-center">
                    <div class="float-left mt-3">
                        <?php if (exist_val('DesignationList/add_designation', $this->session->userdata('permission'))) { ?>
                            <button type="button" class="btn btn-sm btn-primary add" data-bs-toggle="modal" data-bs-target=".add_desg" title="ADD DESIGNATION"> <i class="fa fa-plus"></i> Add</button>
                        <?php } ?>
                    </div>
                    <h1 class="text-bold mt-3 mb-3">DESIGNATIONS</h1>
                </div>
            </div>
        </div>
        <div class="container-fluid jumbotron p-3">
            <div class="row">
                
                    <?php if ($id_desg) {
                        $designation_id = $id_desg;
                    } else {
                        $designation_id = 0;
                    } ?>
                    <div class="col">
                        <select name="designation_id" id="designation_id" class="form-control form-control-sm">
                            <option value="">Select Designation</option>
                            <?php foreach ($dn_names as $dns) { ?>
                                <option value="<?php echo $dns->designation_id; ?>" <?php echo ($designation_id == $dns->designation_id) ? "selected" : ""; ?>> <?php echo $dns->designation_name; ?> </option>
                            <?php } ?>
                        </select>
                    </div>
                    <?php if ($id_report_to) {
                        $id_admin_role = $id_report_to;
                    } else {
                        $id_admin_role = 0;
                    } ?>
                    <div class="col">
                        <select name="id_admin_role" id="id_admin_role" class="form-control form-control-sm">
                            <option value="">Select Report To</option>
                            <?php foreach ($reports_to as $rst) { ?>
                                <option value="<?php echo $rst->id_admin_role; ?>" <?php echo ($id_admin_role == $rst->id_admin_role) ? "selected" : ""; ?>> <?php echo $rst->admin_role_name; ?> </option>
                            <?php } ?>
                        </select>
                    </div>
                    <?php if ($created_pesron) {
                        $uidnr_admin = $created_pesron;
                    } else {
                        $uidnr_admin = "";
                    } ?>
                    <div class="col">
                        <select name="created_by" id="created_by" class="form-control form-control-sm">
                            <option value="">Select Created By</option>
                            <?php foreach ($created_by_name as $cr_name) { ?>
                                <option value="<?php echo $cr_name->uidnr_admin; ?>" <?php echo ($uidnr_admin == $cr_name->uidnr_admin) ? "selected" : ""; ?>> <?php echo $cr_name->created_by; ?> </option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="col">
                        <select name="status" id="status" class="form-control form-control-sm">
                            <option value="">Select Status</option>
                            <option value="1" <?php echo (($id_status == "1") ? "selected" : ""); ?>> Active </option>
                            <option value="0" <?php echo (($id_status == "0") ? "selected" : ""); ?>> DeActive </option>
                        </select>
                    </div>
                    <div class="col">
                    <div class="input-group">
                    <input value="<?php echo (($search != 'NULL') ? $search : ""); ?>" id="search" class="form-control form-control-sm" type="text" placeholder="Search" aria-label="Search">
                    <div class="input-group-append">
                    <button onclick="searchfilter();" type="button" class="btn btn-sm btn-secondary" title="Search">
                      <i class="fa fa-search"></i>
                    </button>
                    <a class="btn btn-sm btn-danger ml-1" href="<?php echo base_url('DesignationList'); ?>" title="Clear">
                      <i class="fa fa-trash"></i>
                    </a>
                    </div>
                  </div></div>
            </div>

        </div>
        <!-- container fluid start -->
        <div class="container-fluid">

            <div class="row">
                <div class="col-12">
                    <div class="card">
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
                                        if ($id_desg != "") {
                                            $id_desg = base64_encode($id_desg);
                                        } else {
                                            $id_desg = "NULL";
                                        }
                                        if ($id_report_to != "") {
                                            $id_report_to = base64_encode($id_report_to);
                                        } else {
                                            $id_report_to = "NULL";
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
                                        <th scope="col">S.NO.</th>
                                        <th scope="col"><a href="<?php echo base_url('DesignationList/index/' . $id_desg . '/' . $id_report_to . '/' . $created_pesron . '/' .  $id_status . '/' . $search . '/' . 'msd.designation_name' . '/' . $order) ?>">DESIGNATION NAME</a></th>
                                        <th scope="col"><a href="<?php echo base_url('DesignationList/index/' . $id_desg . '/' . $id_report_to . '/' . $created_pesron . '/' .  $id_status . '/' . $search . '/' . 'ar.admin_role_name' . '/' . $order) ?>">REPORT TO</a></th>
                                        <th scope="col"><a href="<?php echo base_url('DesignationList/index/' . $id_desg . '/' . $id_report_to . '/' . $created_pesron . '/' .  $id_status . '/' . $search . '/' . 'ap.admin_fname' . '/' . $order) ?>">CREATED BY</a></th>
                                        <th scope="col"><a href="<?php echo base_url('DesignationList/index/' . $id_desg . '/' . $id_report_to . '/' . $created_pesron . '/' .  $id_status . '/' . $search . '/' . 'msd.created_on' . '/' . $order) ?>">CREATED ON</a></th>
                                        <?php if ((exist_val('DesignationList/designation_status', $this->session->userdata('permission')))) { ?>
                                            <th scope="col"><a href="<?php echo base_url('DesignationList/index/' . $id_desg . '/' . $id_report_to . '/' . $created_pesron . '/' .  $id_status . '/' . $search . '/' . 'msd.status' . '/' . $order) ?>">STATUS</a></th>
                                        <?php } ?>
                                        <?php if ((exist_val('DesignationList/fetch_designation_for_edit', $this->session->userdata('permission'))) || (exist_val('DesignationList/get_designation_log', $this->session->userdata('permission')))) { ?>
                                            <th scope="col">ACTION</th>
                                        <?php } ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $sn = $this->uri->segment('10') + 1;
                                    if ($desig_list) {
                                        foreach ($desig_list as $key => $value) { ?>
                                            <tr>
                                                <td><?php echo $sn; ?></td>
                                                <td><?php echo $value->designation_name ?></td>
                                                <td><?php echo $value->admin_role_name ?></td>
                                                <td><?php echo $value->admin_fname ?></td>
                                                <td><?php echo change_time($value->created_on, $this->session->userdata('timezone')); ?></td>
                                                <?php if ((exist_val('DesignationList/designation_status', $this->session->userdata('permission')))) { ?>
                                                    <?php if ($value->status == 1) { ?>
                                                        <td><span class="btn btn-sm btn-success status_chng" id="active" data-id="<?php echo $value->designation_id; ?>">Active</span></td>
                                                    <?php } else { ?>
                                                        <td><span class="btn btn-sm btn-danger status_chng" id="deactive" data-id="<?php echo $value->designation_id; ?>">Deactive</span></td>
                                                    <?php } ?>
                                                <?php } ?>
                                                <td>
                                                    <?php if ((exist_val('DesignationList/fetch_designation_for_edit', $this->session->userdata('permission')))) { ?>
                                                        <button type="button" class="btn btn-sm  edit_desg" title="Edit Designation" data-bs-toggle="modal" data-bs-target="#desig_edit" data-id="<?php echo $value->designation_id ?>">
                                                            <i class="fa fa-edit" title="Edit Designation"></i>
                                                        </button>
                                                    <?php } ?>

                                                    <?php if ((exist_val('DesignationList/get_designation_log', $this->session->userdata('permission')))) { ?>
                                                        <a href="javascript:void(0)" data-id="<?php echo $value->designation_id; ?>" class="btn log_view" data-bs-toggle="modal" data-bs-target="#exampleModal" title="Log View"><i class="fa fa-eye" title="View Log"></i></a>
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
                <?php if ($desig_list && count($desig_list) > 0) { ?>
                    <span><?php echo $links ?></span>
                    <span><?php echo $result_count; ?></span>
                <?php } else { ?>
                    <h3 class="text-center">NO RECORD FOUND</h3>
                <?php } ?>
            </div>
        </div>
        <!-- container fluid end -->
    </section>
</div>

<!-- ADD DESIGNATION MODAL -->
<div class="modal fade add_desg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content modal-sm">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">ADD DESIGNATION</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="javascript:void(0)" id="designation_add">
                <input type="hidden" class="txt_csrfname" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
                <div class="modal-body mx-3">
                    <div class="md-form mb-5">
                        <label data-error="wrong" data-success="right" for="designation-name">Designation Name</label>
                        <input required type="text" id="designation_name" name="designation_name" class="form-control validate">
                    </div>
                    <div class="md-form mb-5">
                        <label data-error="wrong" data-success="right" for="report_to">Report To</label>
                        <select name="report_to" id="report_to_id" class="form-control">
                            <option value="">Select Report To</option>
                            <?php foreach ($reports_to as $rst) { ?>
                                <option value="<?php echo $rst->id_admin_role; ?>"><?php echo $rst->admin_role_name; ?></option>
                            <?php  } ?>
                        </select>
                    </div>
                    <div class="md-form mb-5">
                        <label data-error="wrong" data-success="right" for="department-status">Status</label>
                        <select required="" class="form-control custom-select" id="status" name="status" aria-invalid="false" aria-required="true">
                            <option value="">Choose Status</option>
                            <option value="1">Active</option>
                            <option value="0">In-Active</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">CLOSE</button>
                    <button type="submit" class="btn btn-success">ADD</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- END -->

<!-- EDIT DESIGNATION MODAL -->
<div class="modal fade" id="desig_edit" tabindex="-1" role="dialog" aria-labelledby="desig_editLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content modal-sm">
            <div class="modal-header">
                <h5 class="modal-title" id="desig_editLabel">EDIT DESIGNATION DETAILS</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" id="edit_desig">
                <div class="modal-body">
                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                    <input type="hidden" id="designation_id" name="designation_id" value="">
                    <div class="form-group">
                        <label data-error="wrong" data-success="right" for="designation-name">Designation Name</label>
                        <input required type="text" id="designation_name_edit" name="designation_name" class="form-control validate">

                        <label data-error="wrong" data-success="right" for="report_to">Report To</label>
                        <select name="report_to" id="report_to_id_edit" class="form-control">
                            <option value="">Select Report To</option>
                            <?php foreach ($reports_to as $rst) { ?>
                                <option value="<?php echo $rst->id_admin_role; ?>"><?php echo $rst->admin_role_name; ?></option>
                            <?php  } ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary"> Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- END -->

<!-- Designation Log Modal Starts -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Designation Log</h5>
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
                    <tbody id="designation_log"></tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- Designation Log Modal Ends -->

<script>
    function searchfilter() {
        var url = '<?php echo base_url("DesignationList/index"); ?>';
        var id_desg = $('#designation_id').val();
        if (id_desg != "") {
            url = url + '/' + btoa(id_desg);
        } else {
            id_desg = "";
            url = url + '/NULL';
        }

        var id_cont_code = $('#id_admin_role').val();
        if (id_cont_code != "") {
            url = url + '/' + btoa(id_cont_code);
        } else {
            id_cont_code = "";
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

        $('#designation_add').on('submit', function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            $.ajax({
                url: "<?php echo base_url('DesignationList/add_designation') ?>",
                method: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    var result = $.parseJSON(response);
                    if (result.status > 0) {
                        $('.add_desg').modal('hide');
                        $("#designation_add").trigger('reset');
                        window.location.reload();
                    } else {
                        $.notify(result.msg, 'error');
                    }
                    if (result.error) {
                        var error = result.error;
                        $('.form_errors').remove();
                        $.each(error, function(i, v) {
                            $('#designation_add select[name="' + i + '"]').after('<span class="text-danger form_errors">' + v + '</span>');
                            $('#designation_add input[name="' + i + '"]').after('<span class="text-danger form_errors">' + v + '</span>');
                        });

                    } else {
                        $('.form_errors').remove();
                    }
                }
            });
            return false;
        });

        // Edit Designation
        $('.edit_desg').on('click', function() {
            var designation_id = $(this).data('id');
            get_desig_edit_data(designation_id);
            $('#edit_desig #designation_id').val(designation_id);
        });

        function get_desig_edit_data(designation_id) {
            const _tokken = $('meta[name="_tokken"]').attr("value");
            $.ajax({
                url: "<?php echo base_url('DesignationList/fetch_designation_for_edit') ?>",
                method: "POST",
                data: {
                    designation_id: designation_id,
                    _tokken: _tokken
                },
                success: function(response) {
                    var data = $.parseJSON(response);
                    if (data) {
                        $('#desig_edit #designation_name_edit').val(data.designation_name);
                        $('#desig_edit #report_to_id_edit option[value=' + data.report_to + ']').attr('selected', 'selected');
                    }
                }
            });
            return false;
        }

        // update designation details
        $('#edit_desig').on('submit', function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            $.ajax({
                url: "<?php echo base_url('DesignationList/update_designation') ?>",
                method: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    var result = $.parseJSON(response);
                    if (result.status > 0) {
                        $('#desig_edit').modal('hide');
                        $("#edit_desig").trigger('reset');
                        window.location.reload();
                    } else {
                        $.notify(result.msg, 'error');
                    }
                    if (result.error) {
                        var error = result.error;
                        $('.form_errors').remove();
                        $.each(error, function(i, v) {
                            $('#edit_desig input[name="' + i + '"]').after('<span class="text-danger form_errors">' + v + '</span>');
                            $('#edit_desig select[name="' + i + '"]').after('<span class="text-danger form_errors">' + v + '</span>');
                        });

                    } else {
                        $('.form_errors').remove();
                    }
                }
            });
            return false;
        });
        // END

        // changing status of designation
        $('.status_chng').click(function() {
            var designation_id = $(this).attr("data-id");
            var _tokken = $('meta[name="_tokken"]').attr('value');
            $.ajax({
                url: "<?php echo base_url(); ?>DesignationList/designation_status",
                data: {
                    "designation_id": designation_id,
                    "_tokken": _tokken
                },
                type: 'post',
                success: function(result) {
                    location.reload();
                }
            });
        });
        // END

        // Designation Log
        $('.log_view').click(function(e) {
            e.preventDefault();
            $('#designation_log').empty();
            var designation_id = $(this).data('id');
            const _tokken = $('meta[name="_tokken"]').attr("value");
            $.ajax({
                type: 'post',
                url: "<?php echo base_url('DesignationList/get_designation_log') ?>",
                data: {
                    _tokken: _tokken,
                    designation_id: designation_id
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
                            $('#designation_log').append(value);
                            sn++;
                        });
                    } else {
                        var value = '';
                        value += '<tr>';
                        value += '<td colspan="5">';
                        value += "<h4> NO RECORD FOUND! </h4>";
                        value += "</td>";
                        value += "</tr>";
                        $('#designation_log').append(value);
                    }
                }
            });
            // return false;
        });
        //ends
    });
</script>