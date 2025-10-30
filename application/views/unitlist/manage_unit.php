<div class="content-wrapper">
    <section class="content-header">
        <!-- container fluid start -->
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12 text-center">
                    <div class="float-left mt-3">
                        <?php if (exist_val('UnitsList/add_unit', $this->session->userdata('permission'))) { ?>
                            <a href="" class="btn btn-sm btn-primary btn-rounded" data-bs-toggle="modal" data-bs-target="#modalLoginForm"> <i class="fa fa-plus"></i> Add</a>
                        <?php } ?>
                    </div>
                    <h1 class="text-bold mt-3 mb-3">UNITS</h1>
                </div>
            </div>
        </div>
        <div class="container-fluid jumbotron p-3">
            <div class="row">
                <?php if ($id_unit) {
                    $unit_id = $id_unit;
                } else {
                    $unit_id = 0;
                } ?>
                <div class="col-sm-3">
                    <select name="unit_id" id="unit_id" class="form-control form-control-sm">
                        <option value="">Select Unit</option>
                        <?php foreach ($units_nm as $uname) { ?>
                            <option value="<?php echo $uname->unit_id; ?>" <?php echo ($unit_id == $uname->unit_id) ? "selected" : ""; ?>> <?php echo $uname->unit; ?> </option>
                        <?php } ?>
                    </select>
                </div>
                <?php if ($type_un) {
                    $un_types = $type_un;
                } else {
                    $un_types = "";
                } ?>
                <div class="col-sm-3">
                    <select name="un_types" id="un_types" class="form-control form-control-sm">
                        <option value="">Select Unit Type</option>
                        <option value="Report" <?php echo ($un_types == 'Report') ? "selected" : ""; ?>> Report </option>
                        <option value="Consumable" <?php echo ($un_types == 'Consumable') ? "selected" : ""; ?>> Consumable </option>
                    </select>
                </div>
                <?php if ($created_pesron) {
                    $uidnr_admin = $created_pesron;
                } else {
                    $uidnr_admin = "";
                } ?>
                <div class="col-sm-3">
                    <select name="created_by" id="created_by" class="form-control form-control-sm">
                        <option value="">Select Created By</option>
                        <?php foreach ($created_by_name as $cr_name) { ?>
                            <option value="<?php echo $cr_name->uidnr_admin; ?>" <?php echo ($uidnr_admin == $cr_name->uidnr_admin) ? "selected" : ""; ?>> <?php echo $cr_name->created_by; ?> </option>
                        <?php } ?>
                    </select>
                </div>
                <div class="col-sm-3">
                    <div class="input-group">
                        <input value="<?php echo (($search != 'NULL') ? $search : ""); ?>" id="search" class="form-control form-control-sm" type="text" placeholder="Search" aria-label="Search">
                        <div class="input-group-append">
                            <button onclick="searchfilter();" type="button" class="btn btn-sm btn-secondary" title="Search">
                                <i class="fa fa-search"></i>
                            </button>
                            <a class="btn btn-sm btn-danger ml-1" href="<?php echo base_url('UnitsList'); ?>" title="Clear">
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
                                        if ($id_unit != "") {
                                            $id_unit = base64_encode($id_unit);
                                        } else {
                                            $id_unit = "NULL";
                                        }
                                        if ($type_un != "") {
                                            $type_un = base64_encode($type_un);
                                        } else {
                                            $type_un = "NULL";
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
                                        <th scope="col"><a href="<?php echo base_url('UnitsList/index/' . $id_unit . '/' . $type_un . '/' . $created_pesron . '/' . $search . '/' . 'un.unit' . '/' . $order) ?>">UNIT</a></th>
                                        <th scope="col"><a href="<?php echo base_url('UnitsList/index/' . $id_unit . '/' . $type_un . '/' . $created_pesron . '/' . $search . '/' . 'un.unit_type' . '/' . $order) ?>">UNIT TYPE</a></th>
                                        <th scope="col"><a href="<?php echo base_url('UnitsList/index/' . $id_unit . '/' . $type_un . '/' . $created_pesron . '/' . $search . '/' . 'ap.admin_fname' . '/' . $order) ?>">CREATED BY</a></th>
                                        <th scope="col"><a href="<?php echo base_url('UnitsList/index/' . $id_unit . '/' . $type_un . '/' . $created_pesron . '/' . $search . '/' . 'un.created_on' . '/' . $order) ?>">CREATED ON</a></th>
                                        <?php if ((exist_val('UnitsList/fetch_unit_for_edit', $this->session->userdata('permission'))) || (exist_val('UnitsList/delete_unit', $this->session->userdata('permission'))) || (exist_val('UnitsList/get_unit_log', $this->session->userdata('permission')))) { ?>
                                            <!-- added by Millan on 19-02-2021 -->
                                            <th scope="col">Action</th>
                                        <?php } ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $sn = $this->uri->segment('9') + 1;
                                    if ($un_listing) {
                                        foreach ($un_listing as $key => $value) { ?>
                                            <tr>
                                                <td><?php echo $sn; ?></td>
                                                <td><?php echo (($value->unit) ? $value->unit : 'N/A') ?></td>
                                                <td><?php echo (($value->unit_type) ? $value->unit_type : 'N/A') ?></td>
                                                <td><?php echo (($value->admin_fname) ? $value->admin_fname : 'N/A') ?></td>
                                                <td><?php echo (($value->created_on) ? change_time($value->created_on, $this->session->userdata('timezone')) : 'N/A') ?></td>
                                                <td>
                                                    <?php if (exist_val('UnitsList/fetch_unit_for_edit', $this->session->userdata('permission'))) { ?>
                                                        <!-- added by Millan on 19-02-2021 -->
                                                        <a href="javascript:void(0)" class="btn editcls" data-bs-toggle="modal" data-bs-target="#EditRoleForm" data-id="<?php echo $value->unit_id; ?>"><i class="fa fa-edit" title="Edit Unit"></i></a>
                                                    <?php } ?>

                                                    <?php if (exist_val('UnitsList/delete_unit', $this->session->userdata('permission'))) { ?>
                                                        <!-- added by Millan on 19-02-2021 -->
                                                        <a href="<?php echo base_url(); ?>UnitsList/delete_unit?unit_id=<?php echo $value->unit_id; ?>" onclick="if (! confirm('Are you Sure Want To Delete Unit ?')) { return false; }" class="text-danger">
                                                            <i class="fa fa-times" title="Delete Unit"></i>
                                                        </a>
                                                    <?php } ?>

                                                    <?php if (exist_val('UnitsList/get_unit_log', $this->session->userdata('permission'))) { ?>
                                                        <a href="javascript:void(0)" data-id="<?php echo $value->unit_id; ?>" class="btn log_view ml-2" data-bs-toggle="modal" data-bs-target="#exampleModal" title="Log View"><i class="fa fa-eye" title="Log view"></i></a>
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
                <?php if ($un_listing && count($un_listing) > 0) { ?>
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


<!-- MODAL FOR ADD NEW UNIT STARTS -->
<div class="modal fade" id="modalLoginForm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content modal-sm">
            <div class="modal-header text-center">
                <h6 class="modal-title w-100 font-weight-bold">Add New Unit</h6>
                <button class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="javascript:void(0)" id="addunit_modal">
                <input type="hidden" class="txt_csrfname" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
                <input type="hidden" name="unit_id" class="form-control validate" id="unit_id" value="">
                <div class="modal-body mx-3">
                    <div class="md-form mb-5">
                        <label data-error="wrong" data-success="right" for="unit-name">Unit Name</label>
                        <input type="text" id="unit_add" name="unit" class="form-control validate">
                    </div>
                    <div class="md-form mb-5">
                        <label data-error="wrong" data-success="right" for="unit-type">Unit Type</label>
                        <select class="form-control custom-select" id="unit_type" name="unit_type" aria-invalid="false" aria-required="true">
                            <option value="">Choose Unit Type</option>
                            <option value="Report">Report</option>
                            <option value="Consumable">Consumable</option>
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
<!-- MODAL FOR ADD NEW UNIT ENDS -->

<!-- MODAL FOR EDIT UNIT STARTS -->
<div class="modal fade" id="EditRoleForm" tabindex="-1" role="dialog" aria-labelledby="myModalLabelEdit" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content modal-sm">
            <div class="modal-header text-center">
                <h6 class="modal-title w-100 font-weight-bold">Edit Unit</h6>
                <button class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="javascript:void(0)" id="updateunit_modal">
                <input type="hidden" class="txt_csrfname" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
                <input type="hidden" name="unit_id" class="form-control validate" id="unit_id" value="">
                <div class="modal-body mx-3">
                    <div class="md-form mb-5">
                        <label data-error="wrong" data-success="right" for="unit-name">Unit Name</label>
                        <input type="text" id="unit_name_edit" name="unit" class="form-control validate">
                    </div>
                    <div class="md-form mb-5">
                        <label data-error="wrong" data-success="right" for="unit-type">Unit Type</label>
                        <select class="form-control custom-select" id="unit_type_edit" name="unit_type" aria-invalid="false" aria-required="true">
                            <option value="">Choose Unit Type</option>
                            <option value="Report">Report</option>
                            <option value="Consumable">Consumable</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">CLOSE</button>
                    <button type="submit" class="btn btn-success">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- MODAL FOR EDIT UNITS ENDS -->

<!-- Unit Log Modal Starts -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Unit log</h5>
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
                    <tbody id="unit_log"></tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- Unit Log Modal Ends -->


<script>
    var style = {
        'margin': '0 auto'
    };
    $('.modal-content').css(style);

    function searchfilter() {
        var url = '<?php echo base_url("UnitsList/index"); ?>';
        var id_unit = $('#unit_id').val();
        if (id_unit != "") {
            url = url + '/' + btoa(id_unit);
        } else {
            id_unit = "";
            url = url + '/NULL';
        }

        var type_un = $('#un_types').val();
        if (type_un != "") {
            url = url + '/' + btoa(type_un);
        } else {
            type_un = "";
            url = url + '/NULL';
        }

        var created_pesron = $('#created_by').val();
        if (created_pesron != "") {
            url = url + '/' + btoa(created_pesron);
        } else {
            created_pesron = "";
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

        // add unit
        $('#addunit_modal').on('submit', function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            $.ajax({
                url: "<?php echo base_url('UnitsList/add_unit') ?>",
                method: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    var result = $.parseJSON(response);
                    if (result.status > 0) {
                        $('#modalLoginForm').modal('hide');
                        $("#addunit_modal").trigger('reset');
                        window.location.reload();
                    } else {
                        $.notify(result.msg, 'error');
                    }
                    if (result.errors) {
                        var error = result.errors;
                        $('.form_errors').remove();
                        $.each(error, function(i, v) {
                            $('#addunit_modal select[name="' + i + '"]').after('<span class="text-danger form_errors">' + v + '</span>');
                            $('#addunit_modal input[name="' + i + '"]').after('<span class="text-danger form_errors">' + v + '</span>');
                        });

                    } else {
                        $('.form_errors').remove();
                    }
                }
            });
            return false;
        });

        // fetch unit list data 
        $('.editcls').on('click', function() {
            var unit_id = $(this).data('id');
            get_unit_data(unit_id);
            $('#updateunit_modal #unit_id').val(unit_id);
        });

        function get_unit_data(unit_id) {
            const _tokken = $('meta[name="_tokken"]').attr("value");
            $.ajax({
                url: "<?php echo base_url('UnitsList/fetch_unit_for_edit') ?>",
                method: "POST",
                data: {
                    unit_id: unit_id,
                    _tokken: _tokken
                },
                success: function(response) {
                    var data = $.parseJSON(response);
                    if (data) {
                        $('#updateunit_modal #unit_name_edit').val(data.unit);
                        $('#updateunit_modal #unit_type_edit option[value=' + data.unit_type + ']').attr('selected', 'selected');
                    }
                }
            });
            return false;
        }
        // ends

        // update unit 
        $('#updateunit_modal').on('submit', function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            $.ajax({
                url: "<?php echo base_url('UnitsList/update_unit') ?>",
                method: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    var result = $.parseJSON(response);
                    if (result.status > 0) {
                        $('#EditRoleForm').modal('hide');
                        $("#updateunit_modal").trigger('reset');
                        window.location.reload();
                    } else {
                        $.notify(result.msg, 'error');
                    }
                    if (result.errors) {
                        var error = result.errors;
                        $('.form_errors').remove();
                        $.each(error, function(i, v) {
                            $('#updateunit_modal input[name="' + i + '"]').after('<span class="text-danger form_errors">' + v + '</span>');
                            $('#updateunit_modal select[name="' + i + '"]').after('<span class="text-danger form_errors">' + v + '</span>');
                        });

                    } else {
                        $('.form_errors').remove();
                    }
                }
            });
            return false;
        });
        // ends

        // Unit Log
        $('.log_view').click(function(e) {
            e.preventDefault();
            $('#unit_log').empty();
            var unit_id = $(this).data('id');
            const _tokken = $('meta[name="_tokken"]').attr("value");
            $.ajax({
                type: 'post',
                url: "<?php echo base_url('UnitsList/get_unit_log') ?>",
                data: {
                    _tokken: _tokken,
                    unit_id: unit_id
                },
                success: function(data) {
                    var data = $.parseJSON(data);
                    if (data) {
                        sno = 1;
                        $.each(data, function(i, v) {
                            var value = '';
                            value += '<tr>';
                            value += '<td>' + sno + '</td>';
                            value += '<td>' + v.action_taken + '</td>';
                            value += '<td>' + v.text + '</td>';
                            value += '<td>' + v.taken_by + '</td>';
                            value += '<td>' + v.taken_at.toLocaleString() + '</td>';
                            value += '</tr>';
                            $('#unit_log').append(value);
                            sno++;
                        });
                    } else {
                        var value = '';
                        value += '<tr>';
                        value += '<td colspan="5">';
                        value += "<h4> NO RECORD FOUND! </h4>";
                        value += "</td>";
                        value += "</tr>";
                        $('#unit_log').append(value);
                    }
                }
            });
            // return false;
        });
        //ends

    });
</script>