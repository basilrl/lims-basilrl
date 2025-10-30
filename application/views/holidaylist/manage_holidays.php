<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12 text-center">
                    <div class="float-left mt-3">
                        <?php if (exist_val('HolidayList/add_holiday', $this->session->userdata('permission'))) { ?>
                            <button type="button" title="ADD NEW HOLIDAY" class="btn btn-sm btn-primary btn-rounded" data-bs-toggle="modal" data-bs-target="#modalLoginForm"><i class="fa fa-plus"></i> Add</button>
                        <?php } ?>
                    </div>
                    <h1 class="text-bold mt-3 mb-3">HOLIDAYS</h1>
                </div>
            </div>
        </div>
        <div class="container-fluid jumbotron p-3">
            <div class="row">
                <div class="col-sm-3">
                    <select name="holiday_date" id="month_val" class="form-control form-control-sm">
                        <?php echo ($mon_no) ? $mon_no : ""; ?>
                        <option value="">Select Month</option>
                        <option value="1" <?php echo ($mon_no == 1) ? "selected" : ""; ?>>JANUARY</option>
                        <option value="2" <?php echo ($mon_no == 2) ? "selected" : ""; ?>>FEBRUARY</option>
                        <option value="3" <?php echo ($mon_no == 3) ? "selected" : ""; ?>>MARCH</option>
                        <option value="4" <?php echo ($mon_no == 4) ? "selected" : ""; ?>>APRIL</option>
                        <option value="5" <?php echo ($mon_no == 5) ? "selected" : ""; ?>>MAY</option>
                        <option value="6" <?php echo ($mon_no == 6) ? "selected" : ""; ?>>JUNE</option>
                        <option value="7" <?php echo ($mon_no == 7) ? "selected" : ""; ?>>JULY</option>
                        <option value="8" <?php echo ($mon_no == 8) ? "selected" : ""; ?>>AUGUST</option>
                        <option value="9" <?php echo ($mon_no == 9) ? "selected" : ""; ?>>SEPTEMBER</option>
                        <option value="10" <?php echo ($mon_no == 10) ? "selected" : ""; ?>>OCTOBER</option>
                        <option value="11" <?php echo ($mon_no == 11) ? "selected" : ""; ?>>NOVEMBER</option>
                        <option value="12" <?php echo ($mon_no == 12) ? "selected" : ""; ?>>DECEMBER</option>
                    </select>
                </div>
                <div class="col-sm-3">
                    <select name="holiday_date" id="year_val" class="form-control form-control-sm">
                        <?php echo ($year_no) ? $year_no : ""; ?>
                        <option value="">Select Year</option>
                        <option value="2018" <?php echo ($year_no == 2018) ? "selected" : ""; ?>>2018</option>
                        <option value="2019" <?php echo ($year_no == 2019) ? "selected" : ""; ?>>2019</option>
                        <option value="2020" <?php echo ($year_no == 2020) ? "selected" : ""; ?>>2020</option>
                        <option value="2021" <?php echo ($year_no == 2021) ? "selected" : ""; ?>>2021</option>
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
                        <a class="btn btn-sm btn-danger ml-1" href="<?php echo base_url('HolidayList'); ?>" title="Clear">
                            <i class="fa fa-trash"></i>
                        </a>
                    </div>
                </div>
            </div>

        </div></div>

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
                                        if ($mon_no != "") {
                                            $mon_no = base64_encode($mon_no);
                                        } else {
                                            $mon_no = "NULL";
                                        }
                                        if ($year_no != "") {
                                            $year_no = base64_encode($year_no);
                                        } else {
                                            $year_no = "NULL";
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
                                        <th scope="col"><a href="<?php echo base_url('HolidayList/index/' . $mon_no . '/' . $year_no . '/' . $created_pesron . '/' . $search . '/' . 'msp.holiday_date' . '/' . $order) ?>">HOLIDAY DATE</a></th>
                                        <th scope="col"><a href="<?php echo base_url('HolidayList/index/' . $mon_no . '/' . $year_no . '/' . $created_pesron . '/' . $search . '/' . 'msp.holiday_reason' . '/' . $order) ?>">HOLIDAY REASON</a></th>
                                        <th scope="col"><a href="<?php echo base_url('HolidayList/index/' . $mon_no . '/' . $year_no . '/' . $created_pesron . '/' . $search . '/' . 'ap.admin_fname' . '/' . $order) ?>">CREATED BY</a></th>
                                        <th scope="col"><a href="<?php echo base_url('HolidayList/index/' . $mon_no . '/' . $year_no . '/' . $created_pesron . '/' . $search . '/' . 'msp.created_on' . '/' . $order) ?>">CREATED ON</a></th>
                                        <?php if ((exist_val('HolidayList/fetch_holiday_for_edit', $this->session->userdata('permission'))) || (exist_val('HolidayList/get_user_log_data', $this->session->userdata('permission'))) || (exist_val('HolidayList/delete_holiday', $this->session->userdata('permission')))) { ?>
                                            <th scope="col">ACTION</th>
                                        <?php } ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $sn = $this->uri->segment('9') + 1;
                                    if ($holilist) {
                                        foreach ($holilist as $key => $value) { ?>
                                            <tr>
                                                <td><?php echo $sn; ?></td>
                                                <td><?php echo $value->holiday_date ?></td>
                                                <td><?php echo $value->holiday_reason ?></td>
                                                <td><?php echo $value->admin_fname ?></td>
                                                <td><?php echo change_time($value->created_on, $this->session->userdata('timezone')); ?></td>
                                                <td>
                                                    <?php if (exist_val('HolidayList/fetch_holiday_for_edit', $this->session->userdata('permission'))) { ?> 
                                                        <a href="#" class="btn btn-sm editcls" data-bs-toggle="modal" data-bs-target="#EditRoleForm" data-id="<?php echo $value->holiday_id; ?>"><i class="fa fa-edit" title="Edit Holiday"></i></a>
                                                    <?php } ?>

                                                    <?php if (exist_val('HolidayList/delete_holiday', $this->session->userdata('permission'))) { ?> 
                                                        <a href="<?php echo base_url(); ?>HolidayList/delete_holiday?holiday_id=<?php echo $value->holiday_id; ?>" onclick="if (! confirm('Are you Sure Want To Delete Holiday ?')) { return false; }">
                                                            <i class="fa fa-times text-danger" title="Delete Holiday"></i>
                                                        </a>
                                                    <?php } ?>

                                                    <?php if (exist_val('HolidayList/get_holiday_log', $this->session->userdata('permission'))) { ?>
                                                        <a href="javascript:void(0)" data-id="<?php echo $value->holiday_id; ?>" class="btn log_view" data-bs-toggle="modal" data-bs-target="#exampleModal" title="Log View"><i class="fa fa-eye" title="Log view"></i></a>
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
                <?php if ($holilist && count($holilist) > 0) { ?>
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

<!-- MODAL FOR ADDD NEW HOLIDAY -->
<div class="modal fade" id="modalLoginForm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content modal-sm" style="margin:0 auto;">
            <div class="modal-header">
                <h6 class="modal-title w-100 font-weight-bold"></i> ADD HOLIDAY</h6>
                <button class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="<?php echo base_url(); ?>HolidayList/add_holiday" id="insert_holiday" method="post">
                <input type="hidden" class="txt_csrfname" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
                <div class="modal-body mx-3">
                    <div class="md-form mb-5">
                        <label data-error="wrong" data-success="right" for="holiday-date">Holiday Date</label>
                        <input required type="date" id="holiday_date" name="holiday_date" class="form-control validate">
                    </div>
                    <div class="md-form mb-5">
                        <label data-error="wrong" data-success="right" for="holiday-reason">Holiday Reason</label>
                        <input required type="text" id="holiday_reason" name="holiday_reason" class="form-control validate">
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button class="btn btn-success">Add</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- MODAL FOR ADD HOLIDAY ENDS -->

<!-- MODAL FOR EDIT HOLIDAY -->
<div class="modal fade" id="EditRoleForm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content modal-sm" style="margin:0 auto;">
            <div class="modal-header">
                <h6 class="modal-title w-100 font-weight-bold text-info">EDIT HOLIDAY DETAILS</h6>
                <button class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="<?php echo base_url(); ?>HolidayList/update_holiday" method="post" id="holi_edit">
                <input type="hidden" class="txt_csrfname" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
                <input type="hidden" name="holiday_id" class="form-control validate" id="holiday_id" value="">
                <div class="modal-body mx-3">
                    <div class="md-form mb-5">
                        <label data-error="wrong" data-success="right" for="holiday-date">Holiday Date</label>
                        <input required type="date" id="holiday_date_edit" name="holiday_date" class="form-control validate">
                    </div>
                    <div class="md-form mb-5">
                        <label data-error="wrong" data-success="right" for="holiday-reason">Holiday Reason</label>
                        <input required type="text" id="holiday_reason_edit" name="holiday_reason" class="form-control validate">
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button type="submit" class="btn btn-success">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- MODAL FOR EDIT HOLIDAY ENDS -->

<!-- user log windows -->
<div class="modal user_log_windows" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content" style="margin: 0 auto">
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
                                        <th scope="col">UPDATED BY</th>
                                        <th scope="col">UPDATED ON</th>
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

<!-- Holiday Log Modal Starts -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Holiday log</h5>
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
                    <tbody id="holiday_log"></tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- Holiday Log Modal Ends -->

<script>
    $(document).ready(function() {

        var style = {
            'margin': '0 auto'
        };
        $('.modal-content').css(style);

        // ADD HOLIDAY
        $('#insert_holiday').on('submit', function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            $.ajax({
                url: "<?php echo base_url('HolidayList/add_holiday') ?>",
                method: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    var result = $.parseJSON(response);
                    if (result.status > 0) {
                        $('.add_country').modal('hide');
                        $("#country_add").trigger('reset');
                        window.location.reload();
                    } else {
                        $.notify(result.msg, 'error');
                    }
                    if (result.errors) {
                        var error = result.errors;
                        $('.form_errors').remove();
                        $.each(error, function(i, v) {
                            $('#insert_holiday input[name="' + i + '"]').after('<span class="text-danger form_errors">' + v + '</span>');
                        });

                    } else {
                        $('.form_errors').remove();
                    }
                }
            });
            return false;
        });

        // EDIT HOLIDAY
        $('.editcls').on('click', function() {
            var holiday_id = $(this).data('id');
            get_edit_holiday_data(holiday_id);
            $('#holi_edit #holiday_id').val(holiday_id);
        });

        function get_edit_holiday_data(holiday_id) {
            const _tokken = $('meta[name="_tokken"]').attr("value");
            $.ajax({
                url: "<?php echo base_url('HolidayList/edit_holiday') ?>",
                method: "POST",
                data: {
                    holiday_id: holiday_id,
                    _tokken: _tokken
                },
                success: function(response) {
                    var data = $.parseJSON(response);
                    if (data) {
                        $('#holi_edit #holiday_date_edit').val(data.holiday_date);
                        $('#holi_edit #holiday_reason_edit').val(data.holiday_reason);
                    }
                }
            });
            return false;
        }

        // update holiday details
        $('#holi_edit').on('submit', function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            $.ajax({
                url: "<?php echo base_url('HolidayList/update_holiday') ?>",
                method: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    var result = $.parseJSON(response);
                    if (result.status > 0) {
                        $('#EditRoleForm').modal('hide');
                        $("#holi_edit").trigger('reset');
                        window.location.reload();
                    } else {
                        $.notify(result.msg, 'error');
                    }
                    if (result.errors) {
                        var error = result.errors;
                        $('.form_errors').remove();
                        $.each(error, function(i, v) {
                            $('#holi_edit input[name="' + i + '"]').after('<span class="text-danger form_errors">' + v + '</span>');
                        });

                    } else {
                        $('.form_errors').remove();
                    }
                }
            });
            return false;
        });
        // END

        // user log details
        $('.user_log_btn').on('click', function() {
            var holiday_id = $(this).data('id');
            get_user_log_data(holiday_id);

        });

        function get_user_log_data(holiday_id) {
            const _tokken = $('meta[name="_tokken"]').attr("value");
            $.ajax({
                url: "<?php echo base_url('HolidayList/get_user_log_data') ?>",
                method: "POST",
                data: {
                    holiday_id: holiday_id,
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
                            row += "<td>" + value.updated_by + "</td>";
                            row += "<td>" + value.updated_on + "</td>";
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

        // Holiday Log
        $('.log_view').click(function(e) {
            e.preventDefault();
            $('#holiday_log').empty();
            var holiday_id = $(this).data('id');
            const _tokken = $('meta[name="_tokken"]').attr("value");
            $.ajax({
                type: 'post',
                url: "<?php echo base_url('HolidayList/get_holiday_log') ?>",
                data: {
                    _tokken: _tokken,
                    holiday_id: holiday_id
                },
                success: function(data) {
                    var data = $.parseJSON(data);
                    var value = '';
                    sno = Number();
                    if (data) {
                        $.each(data, function(i, v) {
                            sno += 1;
                            var operation = v.action_taken;
                            var action_message = v.text;
                            var taken_by = v.taken_by;
                            var taken_at = new Date(v.taken_at + ' UTC');
                            value += '<tr>';
                            value += '<td>' + sno + '</td>';
                            value += '<td>' + operation + '</td>';
                            value += '<td>' + action_message + '</td>';
                            value += '<td>' + taken_by + '</td>';
                            value += '<td>' + taken_at.toLocaleString() + '</td>';
                            value += '</tr>';
                            $('#holiday_log').append(value);
                            sno++;
                        });
                    } else {
                        value += '<tr>';
                        value += '<td colspan="5">';
                        value += "<h4> NO RECORD FOUND! </h4>";
                        value += "</td>";
                        value += "</tr>";
                        $('#holiday_log').append(value);
                    }
                }
            });
            // return false;
        });
        //ends
    });
</script>

<script>
    function searchfilter() {
        var url = '<?php echo base_url("HolidayList/index"); ?>';
        var mon_no = $('#month_val').val();
        if (mon_no != "") {
            url = url + '/' + btoa(mon_no);
        } else {
            mon_no = "";
            url = url + '/NULL';
        }

        var year_no = $('#year_val').val();
        if (year_no != "") {
            url = url + '/' + btoa(year_no);
        } else {
            year_no = "";
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
</script>