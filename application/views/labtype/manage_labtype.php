<div class="content-wrapper">
    <section class="content-header">
        <!-- container fluid start -->
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12 text-center">
                    <div class="float-left mt-3">
                        <?php if (exist_val('LabType/add_labtype', $this->session->userdata('permission'))) { ?>
                            <a href="" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modalLoginForm1" > <i class=" fa fa-plus"></i> Add</a>
                        <?php } ?>
                    </div>
                    <h1 class="text-bold mt-3 mb-3">LAB TYPES </h1>
                </div>
            </div>
        </div>
        <div class="container-fluid jumbotron p-3">
          <div class="row">
         
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
                                <?php foreach ($divisions as $divs) { ?>
                                    <option value="<?php echo $divs['division_id']; ?>" <?php echo ($division_id == $divs['division_id']) ? "selected" : ""; ?>> <?php echo $divs['division_name']; ?> </option>
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

                        <div class="col-sm-3">
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
                                    <a class="btn btn-sm btn-danger ml-1" href="<?php echo base_url('LabType'); ?>" title="Clear">
                                        <i class="fa fa-trash"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
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
                                        if ($id_lab_type != "") {
                                            $id_lab_type = base64_encode($id_lab_type);
                                        } else {
                                            $id_lab_type = "NULL";
                                        }
                                        if ($id_division != "") {
                                            $id_division = base64_encode($id_division);
                                        } else {
                                            $id_division = "NULL";
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
                                        <th scope="col"><a href="<?php echo base_url('LabType/index/' . $id_lab_type . '/' . $id_division . '/' . $created_pesron . '/' .  $id_status . '/' .  $search . '/' . 'mlt.lab_type_name' . '/' . $order) ?>">LAB TYPE NAME</a></th>
                                        <th scope="col"><a href="<?php echo base_url('LabType/index/' . $id_lab_type . '/' . $id_division . '/' . $created_pesron . '/' .  $id_status . '/' . $search . '/' . 'msd.division_name' . '/' . $order) ?>">DIVISION NAME</a></th>
                                        <th scope="col"><a href="<?php echo base_url('LabType/index/' . $id_lab_type . '/' . $id_division . '/' . $created_pesron . '/' .  $id_status  . '/' . $search . '/' . 'ap.admin_fname' . '/' . $order) ?>">CREATED BY</a></th>
                                        <th scope="col"><a href="<?php echo base_url('LabType/index/' . $id_lab_type . '/' . $id_division . '/' . $created_pesron . '/' .  $id_status  . '/' . $search . '/' . 'mlt.created_on' . '/' . $order) ?>">CREATED ON</a></th>
                                        <?php if ((exist_val('LabType/labtype_status', $this->session->userdata('permission')))) { ?>
                                            <th scope="col"><a href="<?php echo base_url('LabType/index/' . $id_lab_type . '/' . $id_division . '/' . $created_pesron . '/' .  $id_status  . '/' . $search . '/' . 'mlt.status' . '/' . $order) ?>">Status</a></th>
                                        <?php } ?>
                                        <?php if ((exist_val('LabType/fetch_labtype_for_edit', $this->session->userdata('permission'))) || (exist_val('LabType/get_user_log_data', $this->session->userdata('permission')))) { ?>
                                            <th scope="col">ACTION</th>
                                        <?php } ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $sn = $this->uri->segment('10') + 1;
                                    if ($labtype_list) {
                                        foreach ($labtype_list as $key => $value) { ?>
                                            <tr>
                                                <td><?php echo $sn; ?></td>
                                                <td><?php echo $value->lab_type_name ?></td>
                                                <td><?php echo $value->division_name ?></td>
                                                <td><?php echo $value->admin_fname ?></td>
                                                <td><?php echo change_time($value->created_on, $this->session->userdata('timezone')); ?></td>
                                                <?php if ((exist_val('LabType/labtype_status', $this->session->userdata('permission')))) { ?>
                                                    <?php if ($value->status == 1) { ?>
                                                        <td><span class="btn btn-success btn-sm status_chng" id="active" data-id="<?php echo $value->lab_type_id; ?>">Active</span></td>
                                                    <?php } else { ?>
                                                        <td><span class="btn btn-danger btn-sm status_chng" id="deactive" data-id="<?php echo $value->lab_type_id; ?>">Deactive</span></td>
                                                    <?php } ?>
                                                <?php } ?>
                                                <td>
                                                    <?php if ((exist_val('LabType/fetch_labtype_for_edit', $this->session->userdata('permission')))) { ?>
                                                        <a href="#" class="btn editcls" data-bs-toggle="modal" data-bs-target="#EditRoleForm" data-id="<?php echo $value->lab_type_id; ?>"> <i class="fa fa-edit" title="Edit Lab Type"> </i></a>
                                                    <?php } ?>

                                                    <?php if (exist_val('LabType/get_lab_type_log', $this->session->userdata('permission'))) { ?>
                                                        <a href="javascript:void(0)" data-id="<?php echo $value->lab_type_id; ?>" class="btn log_view" data-bs-toggle="modal" data-bs-target="#exampleModal" title="Log View"><i class="fa fa-eye" title="Log view"></i></a>
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
                <?php if ($labtype_list && count($labtype_list) > 0) { ?>
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

<!-- user log windows -->
<div class="modal user_log_windows" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content" W>
            <div class="modal-header">
                <h5 class="modal-title"><b>NOTIFICATION HISTORY</b></h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="table-responsive">

                            <table class="table table-sm p-2 user_table">
                                <thead>
                                    <tr>
                                        <th scope="col">S. NO.</th>
                                        <th scope="col">ACTION MESSAGE</th>
                                        <th scope="col">DATE-TIME</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>

                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- end -->

<!-- modal for add new labtype starts -->
<div class="modal fade" id="modalLoginForm1" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content modal-sm">
            <div class="modal-header text-center">
                <h6 class="modal-title w-100 font-weight-bold">Add New Lab Type</h6>
                <button class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="javascript:void(0)" id="labtype_add">
                <input type="hidden" class="txt_csrfname" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
                <div class="modal-body mx-3">
                    <div class="md-form mb-5">
                        <label data-error="wrong" data-success="right" for="lab_type_name">Lab Type Name</label>
                        <input type="text" id="lab_type_name" name="lab_type_name" class="form-control validate">
                    </div>
                    <div class="md-form mb-5">
                        <label data-error="wrong" data-success="right" for="lab_type_division">Division</label>
                        <select name="mst_lab_type_division_id" id="mst_lab_type_division_id" class="form-control">
                            <option value="">Select Division</option>
                            <?php foreach ($divisions as $divs) { ?>
                                <option value="<?php echo $divs['division_id']; ?>"><?php echo $divs['division_name']; ?></option>
                            <?php  } ?>
                        </select>
                    </div>
                    <div class="md-form mb-5">
                        <label data-error="wrong" data-success="right" for="labtype-status">Status</label>
                        <select class="form-control custom-select" id="status" name="status" aria-invalid="false" aria-required="true">
                            <option value="">Choose Status</option>
                            <option value="1">Active</option>
                            <option value="0">In-Active</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button class="btn btn-success">Add</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- modal for add new labtype ends -->

<!-- modal for update lab type starts -->
<div class="modal fade" id="EditRoleForm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content modal-sm">
            <div class="modal-header text-center">
                <h6 class="modal-title w-100 font-weight-bold">Edit Lab Type</h6>
                <button class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="javascript:void(0)" id="labtype_update">
                <input type="hidden" class="txt_csrfname" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
                <input type="hidden" name="lab_type_id" class="form-control validate" id="lab_type_id" value="">
                <div class="modal-body mx-3">
                    <div class="md-form mb-5">
                        <label data-error="wrong" data-success="right" for="lab_type_name">Lab Type Name</label>
                        <input required type="text" id="lab_type_name_edit" name="lab_type_name" class="form-control validate">
                    </div>
                    <div class="md-form mb-5">
                        <label data-error="wrong" data-success="right" for="division-code">Division</label>
                        <select name="mst_lab_type_division_id" id="mst_lab_type_division_id_edit" class="form-control">
                            <option value="">Select Division</option>
                            <?php foreach ($divisions as $divs) { ?>
                                <option value="<?php echo $divs['division_id']; ?>"><?php echo $divs['division_name']; ?></option>
                            <?php  } ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button type="submit" class="btn btn-success">Update</button>
                </div>
            </form>
        </div>
    </div>
</div> <br />
<!-- modal for update lab type ends -->

<!-- Lab Type Log Modal Starts -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Lab Type Log</h5>
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
                    <tbody id="labtype_log"></tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- Lab Type Log Modal Ends -->

<script>
    var style = {
        'margin': '0 auto'
    };
    $('.modal-content').css(style);

    function searchfilter() {
        var url = '<?php echo base_url("LabType/index"); ?>';
        var id_lab_type = $('#lab_type_id').val();
        if (id_lab_type != "") {
            url = url + '/' + btoa(id_lab_type);
        } else {
            id_lab_type = "";
            url = url + '/NULL';
        }

        var id_division = $('#division_id').val();
        if (id_division != "") {
            url = url + '/' + btoa(id_division);
        } else {
            id_division = "";
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

        // add lab type
        $('#labtype_add').on('submit', function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            $.ajax({
                url: "<?php echo base_url('LabType/add_labtype') ?>",
                method: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    var result = $.parseJSON(response);
                    if (result.status > 0) {
                        $('#modalLoginForm').modal('hide');
                        $("#labtype_add").trigger('reset');
                        window.location.reload();
                    } else {
                        $.notify(result.msg, 'error');
                    }
                    if (result.errors) {
                        var error = result.errors;
                        $('.form_errors').remove();
                        $.each(error, function(i, v) {
                            $('#labtype_add select[name="' + i + '"]').after('<span class="text-danger form_errors">' + v + '</span>');
                            $('#labtype_add input[name="' + i + '"]').after('<span class="text-danger form_errors">' + v + '</span>');
                        });

                    } else {
                        $('.form_errors').remove();
                    }
                }
            });
            return false;
        });

        // fetch lab type data 
        $('.editcls').on('click', function() {
            var lab_type_id = $(this).data('id');
            get_labtype_data(lab_type_id);
            $('#labtype_update #lab_type_id').val(lab_type_id);
        });

        function get_labtype_data(lab_type_id) {
            const _tokken = $('meta[name="_tokken"]').attr("value");
            $.ajax({
                url: "<?php echo base_url('LabType/fetch_labtype_for_edit') ?>",
                method: "POST",
                data: {
                    lab_type_id: lab_type_id,
                    _tokken: _tokken
                },
                success: function(response) {
                    var data = $.parseJSON(response);
                    if (data) {
                        $('#labtype_update #lab_type_name_edit').val(data.lab_type_name);
                        $('#labtype_update #mst_lab_type_division_id_edit option[value=' + data.mst_lab_type_division_id + ']').attr('selected', 'selected');
                    }
                }
            });
            return false;
        }
        // ends

        // update lab type 
        $('#labtype_update').on('submit', function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            $.ajax({
                url: "<?php echo base_url('LabType/update_labtype') ?>",
                method: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    var result = $.parseJSON(response);
                    if (result.status > 0) {
                        $('#EditRoleForm').modal('hide');
                        $("#labtype_update").trigger('reset');
                        window.location.reload();
                    } else {
                        $.notify(result.msg, 'error');
                    }
                    if (result.errors) {
                        var error = result.errors;
                        $('.form_errors').remove();
                        $.each(error, function(i, v) {
                            $('#labtype_update input[name="' + i + '"]').after('<span class="text-danger form_errors">' + v + '</span>');
                            $('#labtype_update select[name="' + i + '"]').after('<span class="text-danger form_errors">' + v + '</span>');
                        });

                    } else {
                        $('.form_errors').remove();
                    }
                }
            });
            return false;
        });
        // ends

        // change status of lab type
        $('.status_chng').click(function() {
            var lab_type_id = $(this).attr("data-id");
            var _tokken = $('meta[name="_tokken"]').attr('value');
            $.ajax({
                url: "<?php echo base_url(); ?>LabType/labtype_status",
                data: {
                    "lab_type_id": lab_type_id,
                    "_tokken": _tokken
                },
                type: 'post',
                success: function(result) {
                    location.reload();
                }
            });
        });

        // user log detail fetching
        $('.user_log_btn').on('click', function() {
            var lab_type_id = $(this).data('id');
            get_user_log_data(lab_type_id);

        });

        function get_user_log_data(lab_type_id) {
            const _tokken = $('meta[name="_tokken"]').attr("value");
            $.ajax({
                url: "<?php echo base_url('LabType/get_user_log_data') ?>",
                method: "POST",
                data: {
                    lab_type_id: lab_type_id,
                    _tokken: _tokken
                },
                success: function(response) {
                    var data = $.parseJSON(response);
                    $('.user_table tbody').html("");
                    if (data) {
                        var serial = 1;
                        $.each(data, function(index, value) {
                            row = "<tr>";
                            row += "<td>" + serial + "</td>";
                            row += "<td>Insert By " + value.created_by + "</td>";
                            row += "<td>" + value.date + "</td>";
                            row += "</tr>";
                            $('.user_table tbody').append(row);
                            serial++;
                        });
                    } else {
                        row = "<tr>";
                        row += "<td colspan=3>";
                        row += "<h6>NO RECORD FOUND!</h6>";
                        row += "</td>";
                        row += "</tr>";
                        $('.user_table tbody').append(row);
                    }
                }
            });
            return false;
        }
        // end

        // Lab Type Log
        $('.log_view').click(function(e) {
            e.preventDefault();
            $('#labtype_log').empty();
            var lab_type_id = $(this).data('id');
            const _tokken = $('meta[name="_tokken"]').attr("value");
            $.ajax({
                type: 'post',
                url: "<?php echo base_url('LabType/get_lab_type_log') ?>",
                data: {
                    _tokken: _tokken,
                    lab_type_id: lab_type_id
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
                            $('#labtype_log').append(value);
                            sn++;
                        });
                    } else {
                        var value = '';
                        value += '<tr>';
                        value += '<td colspan="5">';
                        value += "<h4> NO RECORD FOUND! </h4>";
                        value += "</td>";
                        value += "</tr>";
                        $('#labtype_log').append(value);
                    }
                }
            });
            // return false;
        });
        //ends
    });
</script>