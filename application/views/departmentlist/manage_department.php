<div class="content-wrapper">
    <section class="content-header">
        <!-- container fluid start -->
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12 text-center">
                    <div class="float-left mt-3">
                        <?php if (exist_val('DepartmentList/add_department', $this->session->userdata('permission'))) { ?>
                            <button type="button" class="btn btn-sm btn-primary add" data-bs-toggle="modal" data-bs-target=".add_desg" title="ADD DEPARTMENT"> <i class="fa fa-plus"></i> Add</button>
                        <?php } ?>
                    </div>
                    <h1 class="text-bold mt-3 mb-3">DEPARTMENTS</h1>
                </div>
            </div>
        </div>
        <div class="container-fluid jumbotron p-3">
        <div class="row">
                                        <?php if ($name_dept) {
                                            $dept_name = $name_dept;
                                        } else {
                                            $dept_name = "";
                                        } ?>
                                        <div class="col">
                                            <select name="dept_name" id="dept_name" class="form-control form-control-sm">
                                                <option value="">Select Department Name</option>
                                                <?php foreach ($dn_names as $dns) { ?>
                                                    <option value="<?php echo $dns->dept_name; ?>" <?php echo ($dept_name == $dns->dept_name) ? "selected" : ""; ?>> <?php echo $dns->dept_name; ?> </option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <?php if ($code_dept) {
                                            $dept_code = $code_dept;
                                        } else {
                                            $dept_code = "";
                                        } ?>
                                        <div class="col">
                                            <select name="dept_code" id="dept_code" class="form-control form-control-sm">
                                                <option value="">Select Department Code</option>
                                                <?php foreach ($dn_codes as $dcn) { ?>
                                                    <option value="<?php echo $dcn->dept_code; ?>" <?php echo ($dept_code == $dcn->dept_code) ? "selected" : ""; ?>> <?php echo $dcn->dept_code; ?> </option>
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
                                            <a class="btn btn-sm btn-danger ml-1" href="<?php echo base_url('DepartmentList'); ?>" title="Clear">
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
                                        if ($name_dept != "") {
                                            $name_dept = base64_encode($name_dept);
                                        } else {
                                            $name_dept = "NULL";
                                        }
                                        if ($code_dept != "") {
                                            $code_dept = base64_encode($code_dept);
                                        } else {
                                            $code_dept = "NULL";
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
                                        <th scope="col"><a href="<?php echo base_url('DepartmentList/index/' . $name_dept . '/' . $code_dept . '/' . $created_pesron . '/' .  $id_status . '/' . $search . '/' . 'msd.dept_name' . '/' . $order) ?>">DEPARTMENT NAME</a></th>
                                        <th scope="col"><a href="<?php echo base_url('DepartmentList/index/' . $name_dept . '/' . $code_dept . '/' . $created_pesron . '/' .  $id_status . '/' . $search . '/' . 'msd.dept_code' . '/' . $order) ?>">DEPARTMENT CODE</a></th>
                                        <th scope="col"><a href="<?php echo base_url('DepartmentList/index/' . $name_dept . '/' . $code_dept . '/' . $created_pesron . '/' .  $id_status . '/' . $search . '/' . 'ap.admin_fname' . '/' . $order) ?>">CREATED BY</a></th>
                                        <th scope="col"><a href="<?php echo base_url('DepartmentList/index/' . $name_dept . '/' . $code_dept . '/' . $created_pesron . '/' .  $id_status . '/' . $search . '/' . 'msd.created_on' . '/' . $order) ?>">CREATED ON</a></th>
                                        <?php if ((exist_val('DepartmentList/department_status', $this->session->userdata('permission')))) { ?>
                                            <th scope="col"><a href="<?php echo base_url('DepartmentList/index/' . $name_dept . '/' . $code_dept . '/' . $created_pesron . '/' .  $id_status . '/' . $search . '/' . 'msd.status' . '/' . $order) ?>">STATUS</a></th>
                                        <?php } ?>
                                        <?php if ((exist_val('DepartmentList/fetch_department_for_edit', $this->session->userdata('permission'))) || (exist_val('DepartmentList/get_department_log', $this->session->userdata('permission')))) { ?>
                                            <th scope="col">ACTION</th>
                                        <?php } ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $sn = $this->uri->segment('10') + 1;
                                    if ($depts_list) {
                                        foreach ($depts_list as $key => $value) { ?>
                                            <tr>
                                                <td><?php echo $sn; ?></td>
                                                <td><?php echo $value->dept_name ?></td>
                                                <td><?php echo $value->dept_code ?></td>
                                                <td><?php echo $value->admin_fname ?></td>
                                                <td><?php echo change_time($value->created_on, $this->session->userdata('timezone')); ?></td>
                                                <?php if ((exist_val('DepartmentList/department_status', $this->session->userdata('permission')))) { ?>
                                                    <?php if ($value->status == 1) { ?>
                                                        <td><span class="btn btn-sm btn-success status_chng" id="active" data-id="<?php echo $value->dept_id; ?>">Active</span></td>
                                                    <?php } else { ?>
                                                        <td><span class="btn btn-sm btn-danger status_chng" id="deactive" data-id="<?php echo $value->dept_id; ?>">Deactive</span></td>
                                                    <?php } ?>
                                                <?php } ?>
                                                <td>
                                                    <?php if ((exist_val('DepartmentList/fetch_department_for_edit', $this->session->userdata('permission')))) { ?>
                                                        <button type="button" class="btn btn-sm  edit_depart" title="Edit Department" data-bs-toggle="modal" data-bs-target="#dept_edit" data-id="<?php echo $value->dept_id ?>">
                                                            <i class="fa fa-edit" title="Edit Department"></i>
                                                        </button>
                                                    <?php } ?>

                                                    <?php if ((exist_val('DepartmentList/get_department_log', $this->session->userdata('permission')))) { ?>
                                                        <a href="javascript:void(0)" data-id="<?php echo $value->dept_id; ?>" class="btn log_view" data-bs-toggle="modal" data-bs-target="#exampleModal" title="Log View"><i class="fa fa-eye" title="View Log"></i></a>
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
                <?php if ($depts_list && count($depts_list) > 0) { ?>
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

<!-- ADD DEPARTMENT MODAL -->
<div class="modal fade add_desg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content modal-sm">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">ADD DEPARTMENT</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="javascript:void(0)" id="dept_add">
                <input type="hidden" class="txt_csrfname" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
                <div class="modal-body mx-3">
                    <div class="md-form mb-5">
                        <label data-error="wrong" data-success="right" for="department-name">Department Name</label>
                        <input required type="text" id="dept_name" name="dept_name" class="form-control validate">
                    </div>
                    <div class="md-form mb-5">
                        <label data-error="wrong" data-success="right" for="department-code">Department Code</label>
                        <input required type="text" id="dept_code" name="dept_code" class="form-control validate">
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

<!-- EDIT DEPARTMENT MODAL -->
<div class="modal fade" id="dept_edit" tabindex="-1" role="dialog" aria-labelledby="dept_editLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content modal-sm">
            <div class="modal-header">
                <h5 class="modal-title" id="dept_editLabel">EDIT DEPARTMENT DETAILS</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" id="edit_dept">
                <div class="modal-body">
                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                    <input type="hidden" id="dept_id" name="dept_id" value="">
                    <div class="form-group">
                        <label data-error="wrong" data-success="right" for="department-name">Department Name</label>
                        <input required type="text" id="dept_name_edit" name="dept_name" class="form-control validate">

                        <label data-error="wrong" data-success="right" for="department-code">Department Code</label>
                        <input required type="text" id="dept_code_edit" name="dept_code" class="form-control validate">
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

<!-- Department Log Modal Starts -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Department Log</h5>
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
                    <tbody id="department_log"></tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- Department Log Modal Ends -->

<script>
    function searchfilter() {
        var url = '<?php echo base_url("DepartmentList/index"); ?>';
        var name_dept = $('#dept_name').val();
        if (name_dept != "") {
            url = url + '/' + btoa(name_dept);
        } else {
            name_dept = "";
            url = url + '/NULL';
        }

        var code_dept = $('#dept_code').val();
        if (code_dept != "") {
            url = url + '/' + btoa(code_dept);
        } else {
            code_dept = "";
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

        $('#dept_add').on('submit', function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            $.ajax({
                url: "<?php echo base_url('DepartmentList/add_department') ?>",
                method: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    var result = $.parseJSON(response);
                    if (result.status > 0) {
                        $('.add_desg').modal('hide');
                        $("#dept_add").trigger('reset');
                        window.location.reload();
                    } else {
                        $.notify(result.msg, 'error');
                    }
                    if (result.error) {
                        var error = result.error;
                        $('.form_errors').remove();
                        $.each(error, function(i, v) {
                            $('#dept_add select[name="' + i + '"]').after('<span class="text-danger form_errors">' + v + '</span>');
                            $('#dept_add input[name="' + i + '"]').after('<span class="text-danger form_errors">' + v + '</span>');
                        });

                    } else {
                        $('.form_errors').remove();
                    }
                }
            });
            return false;
        });

        // Edit Department
        $('.edit_depart').on('click', function() {
            var dept_id = $(this).data('id');
            get_dept_edit_data(dept_id);
            $('#edit_dept #dept_id').val(dept_id);
        });

        function get_dept_edit_data(dept_id) {
            const _tokken = $('meta[name="_tokken"]').attr("value");
            $.ajax({
                url: "<?php echo base_url('DepartmentList/fetch_department_for_edit') ?>",
                method: "POST",
                data: {
                    dept_id: dept_id,
                    _tokken: _tokken
                },
                success: function(response) {
                    var data = $.parseJSON(response);
                    if (data) {
                        $('#dept_edit #dept_name_edit').val(data.dept_name);
                        $('#dept_edit #dept_code_edit').val(data.dept_code);
                    }
                }
            });
            return false;
        }

        // update department details
        $('#edit_dept').on('submit', function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            $.ajax({
                url: "<?php echo base_url('DepartmentList/update_department') ?>",
                method: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    var result = $.parseJSON(response);
                    if (result.status > 0) {
                        $('#dept_edit').modal('hide');
                        $("#edit_dept").trigger('reset');
                        window.location.reload();
                    } else {
                        $.notify(result.msg, 'error');
                    }
                    if (result.error) {
                        var error = result.error;
                        $('.form_errors').remove();
                        $.each(error, function(i, v) {
                            $('#edit_dept input[name="' + i + '"]').after('<span class="text-danger form_errors">' + v + '</span>');
                            $('#edit_dept select[name="' + i + '"]').after('<span class="text-danger form_errors">' + v + '</span>');
                        });

                    } else {
                        $('.form_errors').remove();
                    }
                }
            });
            return false;
        });
        // END

        // changing status of department
        $('.status_chng').click(function() {
            var dept_id = $(this).attr("data-id");
            var _tokken = $('meta[name="_tokken"]').attr('value');
            $.ajax({
                url: "<?php echo base_url(); ?>DepartmentList/department_status",
                data: {
                    "dept_id": dept_id,
                    "_tokken": _tokken
                },
                type: 'post',
                success: function(result) {
                    location.reload();
                }
            });
        });
        // END

        // Department Log
        $('.log_view').click(function(e) {
            e.preventDefault();
            $('#department_log').empty();
            var dept_id = $(this).data('id');
            const _tokken = $('meta[name="_tokken"]').attr("value");
            $.ajax({
                type: 'post',
                url: "<?php echo base_url('DepartmentList/get_department_log') ?>",
                data: {
                    _tokken: _tokken,
                    dept_id: dept_id
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
                            $('#department_log').append(value);
                            sn++;
                        });
                    } else {
                        var value = '';
                        value += '<tr>';
                        value += '<td colspan="5">';
                        value += "<h4> NO RECORD FOUND! </h4>";
                        value += "</td>";
                        value += "</tr>";
                        $('#department_log').append(value);
                    }
                }
            });
            // return false;
        });
        //ends
    });
</script>