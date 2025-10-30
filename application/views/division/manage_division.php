<div class="content-wrapper">
    <section class="content-header">
        <!-- container fluid start -->
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12 text-center">
                    <div class="float-left mt-3">
                        <?php if (exist_val('Division/add_division', $this->session->userdata('permission'))) { ?>
                            <button type="button" class="btn btn-sm btn-primary add" data-bs-toggle="modal" data-bs-target=".add_divs" title="ADD DIVISION"> <i class="fa fa-plus"></i> Add</button>
                        <?php } ?>
                    </div>
                    <h1 class="text-bold mt-3 mb-3">DIVISION</h1>
                </div>
            </div>
        </div>
        <div class="container-fluid jumbotron p-3">   
            <div class="row">
                        <?php if ($name_divs) {
                            $division_name = $name_divs;
                        } else {
                            $division_name = "";
                        } ?>
                        <div class="col">
                            <select name="division_name" id="division_name" class="form-control form-control-sm">
                                <option value="">Select Division Name</option>
                                <?php foreach ($dn_names as $dns) { ?>
                                    <option value="<?php echo $dns->division_name; ?>" <?php echo ($division_name == $dns->division_name) ? "selected" : ""; ?>> <?php echo $dns->division_name; ?> </option>
                                <?php } ?>
                            </select>
                        </div>
                        <?php if ($code_divs) {
                            $division_code = $code_divs;
                        } else {
                            $division_code = "";
                        } ?>
                        <div class="col">
                            <select name="division_code" id="division_code" class="form-control form-control-sm">
                                <option value="">Select Division Code</option>
                                <?php foreach ($dn_codes as $dcn) { ?>
                                    <option value="<?php echo $dcn->division_code; ?>" <?php echo ($division_code == $dcn->division_code) ? "selected" : ""; ?>> <?php echo $dcn->division_code; ?> </option>
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
                    <a class="btn btn-sm btn-danger ml-1" href="<?php echo base_url('Division'); ?>" title="Clear">
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
                                        if ($name_divs != "") {
                                            $name_divs = base64_encode($name_divs);
                                        } else {
                                            $name_divs = "NULL";
                                        }
                                        if ($code_divs != "") {
                                            $code_divs = base64_encode($code_divs);
                                        } else {
                                            $code_divs = "NULL";
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
                                        <th scope="col"><a href="<?php echo base_url('Division/index/' . $name_divs . '/' . $code_divs . '/' . $created_pesron . '/' .  $id_status . '/' . $search . '/' . 'msd.division_name' . '/' . $order) ?>">DIVISION NAME</a></th>
                                        <th scope="col"><a href="<?php echo base_url('Division/index/' . $name_divs . '/' . $code_divs . '/' . $created_pesron . '/' .  $id_status . '/' . $search . '/' . 'msd.division_code' . '/' . $order) ?>">DIVISION CODE</a></th>
                                        <th scope="col"><a href="<?php echo base_url('Division/index/' . $name_divs . '/' . $code_divs . '/' . $created_pesron . '/' .  $id_status . '/' . $search . '/' . 'msd.erpdivision_code' . '/' . $order) ?>">ERP DIVISION CODE</a></th>
                                        <th scope="col"><a href="<?php echo base_url('Division/index/' . $name_divs . '/' . $code_divs . '/' . $created_pesron . '/' .  $id_status . '/' . $search . '/' . 'ap.admin_fname' . '/' . $order) ?>">CREATED BY</a></th>
                                        <th scope="col"><a href="<?php echo base_url('Division/index/' . $name_divs . '/' . $code_divs . '/' . $created_pesron . '/' .  $id_status . '/' . $search . '/' . 'msd.created_on' . '/' . $order) ?>">CREATED ON</a></th>
                                        <?php if ((exist_val('Division/division_status', $this->session->userdata('permission')))) { ?>
                                            <th scope="col"><a href="<?php echo base_url('Division/index/' . $name_divs . '/' . $code_divs . '/' . $created_pesron . '/' .  $id_status . '/' . $search . '/' . 'msd.status' . '/' . $order) ?>">STATUS</a></th>
                                        <?php } ?>
                                        <?php if ((exist_val('Division/fetch_division_for_edit', $this->session->userdata('permission'))) || (exist_val('Division/get_division_log', $this->session->userdata('permission')))) { ?>
                                            <th scope="col">ACTION</th>
                                        <?php } ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $sn = $this->uri->segment('10') + 1;
                                    if ($divs_list) {
                                        foreach ($divs_list as $key => $value) { ?>
                                            <tr>
                                                <td><?php echo $sn; ?></td>
                                                <td><?php echo $value->division_name ?></td>
                                                <td><?php echo $value->division_code ?></td>
                                                <td><?php echo $value->erpdivision_code ?></td>
                                                <td><?php echo $value->admin_fname ?></td>
                                                <td><?php echo change_time($value->created_on, $this->session->userdata('timezone')); ?></td>
                                                <?php if ((exist_val('Division/division_status', $this->session->userdata('permission')))) { ?>
                                                    <?php if ($value->status == 1) { ?>
                                                        <td><span class="btn btn-sm btn-success status_chng" id="active" data-id="<?php echo $value->division_id; ?>">Active</span></td>
                                                    <?php } else { ?>
                                                        <td><span class="btn btn-sm btn-danger status_chng" id="deactive" data-id="<?php echo $value->division_id; ?>">Deactive</span></td>
                                                    <?php } ?>
                                                <?php } ?>
                                                <td>
                                                    <?php if ((exist_val('Division/fetch_division_for_edit', $this->session->userdata('permission')))) { ?>
                                                        <button type="button" class="btn btn-sm  edit_divsn" title="Edit Division" data-bs-toggle="modal" data-bs-target="#divs_edit" data-id="<?php echo $value->division_id ?>">
                                                            <i class="fa fa-edit" title="Edit Division"></i>
                                                        </button>
                                                    <?php } ?>

                                                    <?php if ((exist_val('Division/get_division_log', $this->session->userdata('permission')))) { ?>
                                                        <a href="javascript:void(0)" data-id="<?php echo $value->division_id; ?>" class="btn log_view" data-bs-toggle="modal" data-bs-target="#exampleModal" title="Log View"><i class="fa fa-eye" title="View Log"></
                                                    </a>
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
                <?php if ($divs_list && count($divs_list) > 0) { ?>
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

<!-- ADD DIVISION MODAL -->
<div class="modal fade add_divs" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content modal-lg">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">ADD DIVISION</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="javascript:void(0)" id="divs_add">
                <input type="hidden" class="txt_csrfname" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
                <div class="modal-body mx-3">
                    <div class="md-form mb-5">
                        <label data-error="wrong" data-success="right" for="division-name">Division Name</label>
                        <input type="text" id="division_name" name="division_name" class="form-control validate">
                    </div>
                    <div class="md-form mb-5">
                        <label data-error="wrong" data-success="right" for="division-code">Division Code</label>
                        <input type="text" id="division_code" name="division_code" class="form-control validate">
                    </div>
                    <div class="md-form mb-5">
                        <label data-error="wrong" data-success="right" for="erpdivision-code">ERP Division Code :</label>
                        <input type="text" name="erpdivision_code" class="form-control validate" style="width: 100%;">
                    </div>
                    <div class="md-form mb-5">
                        <label data-error="wrong" data-success="right" for="division-status">Status</label>
                        <select class="form-control custom-select" id="status" name="status" aria-invalid="false" aria-="true">
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

<!-- EDIT DIVISION MODAL -->
<div class="modal fade" id="divs_edit" tabindex="-1" role="dialog" aria-labelledby="divs_editLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content modal-lg">
            <div class="modal-header">
                <h5 class="modal-title" id="divs_editLabel">EDIT DIVISION DETAILS</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" id="edit_divs">
                <div class="modal-body">
                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                    <input type="hidden" id="division_id" name="division_id" value="">
                    <input type="hidden" id="division_type" name="division_type" value="">
                    <div class="form-group">
                        <label data-error="wrong" data-success="right" for="division-name">Division Name</label>
                        <input type="text" id="division_name_edit" name="division_name" class="form-control validate">

                        <label data-error="wrong" data-success="right" for="division-code">Division Code</label>
                        <input type="text" id="division_code_edit" name="division_code" class="form-control validate">
                        <label data-error="wrong" data-success="right" for="erpdivision_code_edit">ERP Division Code :</label>
                        <input type="text" id="erpdivision_code_edit" name="erpdivision_code" class="form-control validate" style="width: 100%;">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success"> Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- END -->

<!-- Division Log Modal Starts -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content modal-lg">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Division Log</h5>
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
                    <tbody id="division_log"></tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- Division Log Modal Ends -->

<script>
    function searchfilter() {
        var url = '<?php echo base_url("Division/index"); ?>';
        var name_divs = $('#division_name').val();
        if (name_divs != "") {
            url = url + '/' + btoa(name_divs);
        } else {
            name_divs = "";
            url = url + '/NULL';
        }

        var code_divs = $('#division_code').val();
        if (code_divs != "") {
            url = url + '/' + btoa(code_divs);
        } else {
            code_divs = "";
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

        $('#divs_add').on('submit', function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            $.ajax({
                url: "<?php echo base_url('Division/add_division') ?>",
                method: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    var result = $.parseJSON(response);
                    if (result.status > 0) {
                        $('.add_divs').modal('hide');
                        $("#divs_add").trigger('reset');
                        window.location.reload();
                    } else {
                        $.notify(result.msg, 'error');
                    }
                    if (result.error) {
                        var error = result.error;
                        $('.form_errors').remove();
                        $.each(error, function(i, v) {
                            $('#divs_add select[name="' + i + '"]').after('<span class="text-danger form_errors">' + v + '</span>');
                            $('#divs_add input[name="' + i + '"]').after('<span class="text-danger form_errors">' + v + '</span>');
                        });

                    } else {
                        $('.form_errors').remove();
                    }
                }
            });
            return false;
        });

        // Edit Division
        $('.edit_divsn').on('click', function() {
            var division_id = $(this).data('id');
            get_divs_edit_data(division_id);
            $('#edit_divs #division_id').val(division_id);
        });

        function get_divs_edit_data(division_id) {
            const _tokken = $('meta[name="_tokken"]').attr("value");
            $.ajax({
                url: "<?php echo base_url('Division/fetch_division_for_edit') ?>",
                method: "POST",
                data: {
                    division_id: division_id,
                    _tokken: _tokken
                },
                success: function(response) {
                    var data = $.parseJSON(response);
                    if (data) {
                        $('#divs_edit #division_name_edit').val(data.division_name);
                        $('#divs_edit #division_code_edit').val(data.division_code);
                        $('#divs_edit #erpdivision_code_edit').val(data.erpdivision_code);
                        $('#divs_edit #division_type').val(data.division_type);
                    }
                }
            });
            return false;
        }

        // update division details
        $('#edit_divs').on('submit', function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            $.ajax({
                url: "<?php echo base_url('Division/update_division') ?>",
                method: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    var result = $.parseJSON(response);
                    if (result.status > 0) {
                        $('#divs_edit').modal('hide');
                        $("#edit_divs").trigger('reset');
                        window.location.reload();
                    } else {
                        $.notify(result.msg, 'error');
                    }
                    if (result.error) {
                        var error = result.error;
                        $('.form_errors').remove();
                        $.each(error, function(i, v) {
                            $('#edit_divs input[name="' + i + '"]').after('<span class="text-danger form_errors">' + v + '</span>');
                            $('#edit_divs select[name="' + i + '"]').after('<span class="text-danger form_errors">' + v + '</span>');
                        });

                    } else {
                        $('.form_errors').remove();
                    }
                }
            });
            return false;
        });
        // END

        // changing status of division
        $('.status_chng').click(function() {
            var division_id = $(this).attr("data-id");
            var _tokken = $('meta[name="_tokken"]').attr('value');
            $.ajax({
                url: "<?php echo base_url(); ?>Division/division_status",
                data: {
                    "division_id": division_id,
                    "_tokken": _tokken
                },
                type: 'post',
                success: function(result) {
                    location.reload();
                }
            });
        });
        // END

        // Division Log
        $('.log_view').click(function(e) {
            e.preventDefault();
            $('#division_log').empty();
            var division_id = $(this).data('id');
            const _tokken = $('meta[name="_tokken"]').attr("value");
            $.ajax({
                type: 'post',
                url: "<?php echo base_url('Division/get_division_log') ?>",
                data: {
                    _tokken: _tokken,
                    division_id: division_id
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
                            $('#division_log').append(value);
                            sn++;
                        });
                    } else {
                        var value = '';
                        value += '<tr>';
                        value += '<td colspan="5">';
                        value += "<h4> NO RECORD FOUND! </h4>";
                        value += "</td>";
                        value += "</tr>";
                        $('#division_log').append(value);
                    }
                }
            });
            // return false;
        });
        //ends
    });
</script>