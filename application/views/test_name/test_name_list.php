<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Test Name</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Test Name</li>
                    </ol>
                </div>
            </div>


            <div class="col-md-2">
                <div class="form-group">
                    <select name="created_by" id="created_by" class="form-control form-control-sm">
                        <option value="">Select Created By</option>
                        <?php foreach ($created_by_name as $cr_name) { ?>
                            <option value="<?php echo $cr_name->uidnr_admin; ?>" <?php echo ($search['uidnr_admin'] == $cr_name->uidnr_admin) ? "selected" : ""; ?>> <?php echo $cr_name->created_by; ?> </option>
                        <?php } ?>
                    </select>
                </div>
            </div>

            <div class="col-md-2">
                <div class="form-group">
                    <input type="text" placeholder="Search by test name" class="form-control form-control-sm" id="test_name" value="<?php echo ($search['test_name'] != 'NULL') ? ($search['test_name']) : ''; ?>">
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <button type="button" class="btn btn-primary btn-sm" id="filter_data">Search</button>
                    <a href="<?php echo base_url('Test_name/index'); ?>" class="btn btn-danger btn-sm">Clear</a>
                </div>
            </div>
        </div>
    </section>
    <?php

    if ($search['uidnr_admin'] != 'NULL') {
        $created_by = base64_encode($search['uidnr_admin']);
    } else {
        $created_by  = 'NULL';
    }

    if ($search['test_name'] != 'NULL') {
        $test_name = base64_encode($search['test_name']);
    } else {
        $test_name  = 'NULL';
    }

    ?>


    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <!-- /.row -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">

                            <button type="button" class="btn btn-sm btn-primary add" data-bs-toggle="modal" data-bs-target=".add_testname" title="ADD NEW TEST NAME">ADD</button>

                            <!-- <div class="input-group input-group-sm">
                                <a href="<?php echo base_url('Test_name/add_testname'); ?>" class="btn btn-primary" style="float: right;">Add</a>
                            </div> -->
                        </div>
                        <!-- /.card-header -->
                        <input type="hidden" id="order" value="">
                        <input type="hidden" id="column" value="">
                        <div class="card-body small p-0">
                            <table class="table table-hover content-list">
                                <thead>
                                    <tr>
                                        <th>SL No.</th>
                                        <th scope="col"><a href="<?php echo base_url('Test_name/index/' . $created_by . '/' . $test_name . '/' .  'test_name' . '/' . $order) ?>">Test Name</a></th>
                                        <th scope="col"><a href="<?php echo base_url('Test_name/index/' . $created_by . '/' . $test_name . '/' .  'created_by' . '/' . $order) ?>">Created By</a></th>
                                        <th scope="col"><a href="<?php echo base_url('Test_name/index/' . $created_by . '/' . $test_name . '/' .  'created_on' . '/' . $order) ?>">Created On</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($testname)) {
                                        foreach ($testname as $key => $value) {
                                    ?>
                                            <tr>
                                                <td><?php echo $start;
                                                    $start++; ?></td>
                                                <td><?php echo $value['test_name']; ?></td>
                                                <td><?php echo $value['admin_fname']; ?></td>
                                                <td><?php echo $value['created_on']; ?></td>
                                                <td>

                                                     <button type="button" class="btn btn-sm btn-default editbtn" data-bs-toggle="modal" data-bs-target="#edit_testname"  data-id="<?php echo $value['test_id'] ?>">
                                                        <img src="<?php echo base_url('assets/images/mem_edit.png') ?>" alt="edit">
                                                    </button> 
                                                    <!-- <button type="button" class="btn btn-success editbtn" data-bs-toggle="modal" data-bs-target="#edit_testname" data-id="<?php echo $value['test_id'] ?>">Edit</button> -->

                                                    <!-- <a class=" btn btn-sm btn-primary"  data-bs-toggle="modal" data-bs-target=".edit_testname" href="<?php echo base_url('Test_name/edit_testname'); ?>"><i class="fas fa-edit"></i></a> -->
                                                    <a href="javascript:void(0)" data-bs-toggle="modal" class="log_view btn btn-sm btn-secondary" data-bs-target="#log_modal" title="View Log" data-id="<?php echo $value['test_id']; ?>"><i class="fa fa-eye" aria-hidden="true" title="View Log"></i></a>


                                                </td>
                                            </tr>
                                        <?php }
                                    } else { ?>
                                        <tr>
                                            <td colspan="10">No record found</td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                        <!-- Pagination -->
                        <div class="card-header">
                            <span id="pagination"><?php echo $pagination['links']; ?></span>
                            <span id="records"><?php echo $result_count; ?></span>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>

<!-- ADD Test name  MODAL -->
<div class="modal fade bd-example-modal-sm add_testname" id="modalLoginForm" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content modal-md">
            <div class="modal-header">
                <h5 class="modal-title" id="myLargeModalLabel">ADD TEST NAME</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="add_testname" action="javascript:void(0);">
                <div class="modal-body">
                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                    <div class="row p-2">

                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Test Name :</label>
                                <input type="text" name="test_name" placeholder="Enter test name" class="form-control form-control-sm" style="width: 100%;" value="">

                            </div>
                        </div>

                        <!-- <div class="row p-2">
                            <div class="col-sm-12">
                                <label for=""><b>TestName</b></label>
                                <input type="text" name="test_name" placeholder="Enter test_name" class="form-control form-control-sm" autocomplete="off">
                            </div>
                        </div> -->
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">CLOSE</button>
                    <button type="submit" class="btn btn-primary add_testname_button">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- END -->



<!-- EDIT TEST NAME  MODAL --> -->
<div class="modal fade" id="edit_testname" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content modal-lg" style="text-align: right">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit Test Name</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" id="testname_edit" action="<?php echo base_url('Test_name/update_testname'); ?>">
                <div class="modal-body">
                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">

                    <input type="hidden" id="test_id" name="test_id" class="test_id" value="">

                    <div class="form-group text-left">

                        <div class="row p-2">
                            <div class="col-sm-12">
                                <label for=""><b>TestName</b></label>
                                <input type="text" name="test_name" id="test_name" placeholder="Enter test name" class="form-control form-control-sm edit_testname" autocomplete="off">
                            </div>
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">update</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- END -->

<!-- modal to view test name log starts -->

<div class="modal fade" id="log_modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content modal-lg" style="margin:0 auto">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Test Name</h4>
                <input type="hidden" name="id" id="id_cls1">
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body contact_view">
                <table class="table">
                    <thead>
                        <tr>
                            <th>SL No.</th>
                            <th>Operation</th>
                            <th>Action Message</th>
                            <th>Taken At</th>
                            <th>Taken By</th>
                        </tr>
                    </thead>
                    <tbody id="details_log"></tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- modal to view test_name log ends -->

<!-- Ajax call to get log -->
<script>
    $(document).ready(function() {

        const url = $('body').data('url');
        const _tokken = $('meta[name="_tokken"]').attr('value');
        // // Ajax call to get log
        $('.log_view').click(function() {
            $('#details_log').empty();
            var test_id = $(this).data('id');
            $.ajax({
                type: 'post',
                url: url + 'Test_name/get_log_data',
                data: {
                    _tokken: _tokken,
                    test_id: test_id
                },
                success: function(data) {
                    var data = $.parseJSON(data);
                    sno = Number();
                    $.each(data, function(i, v) {
                        var value = '';
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
                        $('#details_log').append(value);
                    });

                }
            });
        });
        // // ajax call to get log ends here

        // Ajax call ends here

        // function to search created by, test name
        $('#filter_data').click(function() {
            var created_by = $('#created_by').val();

            var test_name = $('#test_name').val();
            var base_url = url + 'Test_name/index';

            if (created_by != "") {
                base_url = base_url + '/' + btoa(created_by);
            } else {
                base_url = base_url + '/' + 'NULL';
            }
            if (test_name != "") {
                base_url = base_url + '/' + btoa(test_name);
            } else {
                base_url = base_url + '/' + 'NULL';
            }


            location.href = base_url;
        });
    });
</script>

<script>
    $(document).ready(function() {
        $('.add').on('click', function() {
            $('#add_testname').on('submit', function(e) {
                e.preventDefault();
                var formData = new FormData(this);
                $.ajax({
                    url: "<?php echo base_url('Test_name/add_testname') ?>",
                    method: "POST",
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        var result = $.parseJSON(response);
                        if (result.status > 0) {
                            $('.add_testname').modal('hide');
                            $("#add_testname").trigger('reset');

                            window.location.reload();
                        } else {
                            $.notify(result.msg, 'error');
                        }
                        if (result.errors) {
                            var error = result.errors;
                            $('.form_errors').remove();
                            $.each(error, function(i, v) {
                                $('#add_testname select[name="' + i + '"]').after('<span class="text-danger form_errors">' + v + '</span>');
                            });

                        } else {
                            $('.form_errors').remove();
                        }
                    }
                });
                return false;
            });
        });

        //  EDIT  Testname
        $('.editbtn').on('click', function() {
            $('#edit_testname').modal('show');
            var test_id = $(this).data('id');
            //   alert(test_id); 
            fetch_testname_for_edit(test_id);

        });

        function fetch_testname_for_edit(test_id) {
            const _tokken = $('meta[name="_tokken"]').attr("value");
            $.ajax({
                url: "<?php echo base_url(); ?>Test_name/fetch_testname_for_edit",
                method: "POST",
                data: {
                    test_id: test_id,
                    _tokken: _tokken
                },
                success: function(response) {
                    var data = $.parseJSON(response);
                    //  console.log(data);
                    var test_name = data.test_name;

                    if (data) {

                        $('.edit_testname').val(data.test_name);

                        $('.test_id').val(test_id);

                        // $('edit_testname').modal('show')
                    }
                }
            });
            return false;
        }



        $('#testname_edit').on('submit', function(e) {
            e.preventDefault();
            var formData = new FormData(this);

            $.ajax({
                url: "<?php echo base_url('Test_name/update_testname') ?>",
                method: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    var result = $.parseJSON(response);
                    if (result.status > 0) {
                        $('#edit_testname').modal('hide');
                        $("#testname_edit").trigger('reset');
                        window.location.reload();
                    } else {
                        $.notify(result.msg, 'error');
                    }
                    if (result.errors) {
                        var error = result.errors;
                        $('.form_errors').remove();
                        $.each(error, function(i, v) {
                            $('#testname_edit select[name="' + i + '"]').after('<span class="text-danger form_errors">' + v + '</span>');
                            $('#testname_edit input[name="' + i + '"]').after('<span class="text-danger form_errors">' + v + '</span>');
                        });

                    } else {
                        $('.form_errors').remove();
                    }
                }
            });
            return false;
        });

        //  test_name log
        // $('.log_view').click(function(e) {
        //     e.preventDefault();
        //     $('#details_log').empty();
        //     var test_id = $(this).data('id');
        //     const _tokken = $('meta[name="_tokken"]').attr("value");
        //     $.ajax({
        //         type: 'post',
        //         url: "<?php echo base_url('Test_name/get_log_data') ?>",
        //         data: {
        //             _tokken: _tokken,
        //             test_id: test_id
        //         },
        //         success: function(data) {
        //             var data = $.parseJSON(data);
        //             if (data) {
        //                 sn = 1;
        //                 $.each(data, function(index, log) {
        //                     var value = '';
        //                     var taken_at = new Date(log.taken_at + ' UTC');
        //                     value += '<tr>';
        //                     value += '<td>' + sn + '</td>';
        //                     value += '<td>' + log.action_taken + '</td>';
        //                     value += '<td>' + log.text + '</td>';
        //                     value += '<td>' + log.taken_by + '</td>';
        //                     value += '<td>' + taken_at.toLocaleString() + '</td>';
        //                     value += '</tr>';
        //                     $('#details_log').append(value);
        //                     sn++;
        //                 });
        //             } else {
        //                 var value = '';
        //                 value += '<tr>';
        //                 value += '<td colspan="5">';
        //                 value += "<h4> NO RECORD FOUND! </h4>";
        //                 value += "</td>";
        //                 value += "</tr>";
        //                 $('#details_log').append(value);
        //             }
        //         }
        //     });
        //     // return false;
        // });
        // //ends




    });
</script>