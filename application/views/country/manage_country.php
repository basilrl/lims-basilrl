<script src="<?php echo base_url('ckeditor/ckeditor.js'); ?>"></script>
<div class="content-wrapper">
    <section class="content-header">
        <!-- container fluid start -->
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12 text-center">
                    <div class="float-left mt-3">
                        <?php if (exist_val('Country/add_country', $this->session->userdata('permission'))) { ?> <button type="button" class="btn btn-sm btn-primary add" data-bs-toggle="modal" data-bs-target=".add_country" title="ADD NEW COUNTRY"> <i class="fa fa-plus"></i> Add</button>
                        <?php } ?>
                    </div>
                    <h1 class="text-bold mt-3 mb-3">COUNTRY</h1>
                </div>
            </div>
        </div>
        <div class="container-fluid jumbotron p-3">
        <div class="row">
                        <?php if ($id_cont) {
                            $country_id = $id_cont;
                        } else {
                            $country_id = 0;
                        } ?>
                        <div class="col-sm-2">
                            <select name="country_id" id="cont_id" class="form-control form-control-sm">
                                <option value="">Select Country</option>
                                <?php foreach ($countries as $cons) { ?>
                                    <option value="<?php echo $cons->country_id; ?>" <?php echo ($country_id == $cons->country_id) ? "selected" : ""; ?>> <?php echo $cons->country_name; ?> </option>
                                <?php } ?>
                            </select>
                        </div>
                        <?php if ($id_cont_code) {
                            $country_id = $id_cont_code;
                        } else {
                            $country_id = 0;
                        } ?>
                        <div class="col-sm-2">
                            <select name="country_code" id="cont_code_id" class="form-control form-control-sm">
                                <option value="">Select Country Code</option>
                                <?php foreach ($cont_codes as $cc) { ?>
                                    <option value="<?php echo $cc->country_id; ?>" <?php echo ($country_id == $cc->country_id) ? "selected" : ""; ?>> <?php echo $cc->country_code; ?> </option>
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
                    <a class="btn btn-sm btn-danger ml-1" href="<?php echo base_url('Country'); ?>" title="Clear">
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
                                        if ($id_cont != "") {
                                            $id_cont = base64_encode($id_cont);
                                        } else {
                                            $id_cont = "NULL";
                                        }
                                        if ($id_cont_code != "") {
                                            $id_cont_code = base64_encode($id_cont_code);
                                        } else {
                                            $id_cont_code = "NULL";
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
                                        <th scope="col"><a href="<?php echo base_url('Country/index/' . $id_cont . '/' . $id_cont_code . '/' . $created_pesron . '/' .  $id_status . '/' . $search . '/' . 'msc.country_name' . '/' . $order) ?>">COUNTRY NAME</a></th>
                                        <th scope="col"><a href="<?php echo base_url('Country/index/' . $id_cont . '/' . $id_cont_code . '/' . $created_pesron . '/' .  $id_status . '/' . $search . '/' . 'msc.country_code' . '/' . $order) ?>">COUNTRY CODE</a></th>
                                        <th scope="col"><a href="<?php echo base_url('Country/index/' . $id_cont . '/' . $id_cont_code . '/' . $created_pesron . '/' .  $id_status . '/' . $search . '/' . 'ap.admin_fname' . '/' . $order) ?>">CREATED BY</a></th>
                                        <th scope="col"><a href="<?php echo base_url('Country/index/' . $id_cont . '/' . $id_cont_code . '/' . $created_pesron . '/' .  $id_status . '/' . $search . '/' . 'msc.created_on' . '/' . $order) ?>">CREATED ON</a></th>
                                        <?php if ((exist_val('Country/cont_status', $this->session->userdata('permission')))) { ?>
                                            <th scope="col"><a href="<?php echo base_url('Country/index/' . $id_cont . '/' . $id_cont_code . '/' . $created_pesron . '/' .  $id_status . '/' . $search . '/' . 'msc.status' . '/' . $order) ?>">STATUS</a></th>
                                        <?php } ?>
                                        <?php if ((exist_val('Country/edit_country', $this->session->userdata('permission'))) || (exist_val('Country/get_country_log', $this->session->userdata('permission')))) { ?>
                                            <th scope="col">ACTION</th>
                                        <?php } ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $sn = $this->uri->segment('10') + 1;
                                    if ($countries_list) {
                                        foreach ($countries_list as $key => $value) { ?>
                                            <tr>
                                                <td><?php echo $sn; ?></td>
                                                <td><?php echo $value->country_name ?></td>
                                                <td><?php echo $value->country_code ?></td>
                                                <td><?php echo $value->admin_fname ?></td>
                                                <td><?php echo change_time($value->created_on, $this->session->userdata('timezone')); ?></td>
                                                <?php if ((exist_val('Country/cont_status', $this->session->userdata('permission')))) { ?>
                                                    <?php if ($value->status == 1) { ?>
                                                        <td><span class="btn btn-success btn-sm status_chng" id="active" data-id="<?php echo $value->country_id; ?>">Active</span></td>
                                                    <?php } else { ?>
                                                        <td><span class="btn btn-danger btn-sm status_chng" id="deactive" data-id="<?php echo $value->country_id; ?>">Deactive</span></td>
                                                    <?php } ?>
                                                <?php } ?>
                                                <td>
                                                    <?php if ((exist_val('Country/edit_country', $this->session->userdata('permission')))) { ?>
                                                        <button type="button" class="btn btn-sm  edit_cont" title="Edit Country" data-bs-toggle="modal" data-bs-target="#edit_country" data-id="<?php echo $value->country_id ?>">
                                                            <i class="fa fa-edit" title="Edit Country"></i>
                                                        </button>
                                                    <?php } ?>

                                                    <!-- added by saurabh for lab location flag on 29-06-2021 -->
                                                    <?php if ((exist_val('Country/lab_location', $this->session->userdata('permission')))) { ?>

                                                        <?php if ($value->lab_location_flag) { ?>

                                                            <a href="<?php echo base_url('Country/lab_location/' . base64_encode($value->country_id) . '/' . base64_encode($value->lab_location_flag)) ?>" class="btn btn-sm text-success" title="Un-Mark Lab Location Flag"> <i class="fa fa-flag" title="Test Country" ></i></a>

                                                        <?php } else { ?>

                                                            <a href="<?php echo base_url('Country/lab_location/' . base64_encode($value->country_id) . '/' . base64_encode($value->lab_location_flag)) ?>" class="btn btn-sm text-black-50" title="Mark Lab Location Flag"> <i class="fa fa-flag" title="Test Country"></i></a>

                                                        <?php } ?>

                                                    <?php } ?>
                                                    <!-- end -->


                                                    <!-- added by saurabh for product destination flag on 29-06-2021 -->
                                                    <?php if ((exist_val('Country/product_destination', $this->session->userdata('permission')))) { ?>

                                                        <?php if ($value->product_destination_flag) { ?>

                                                            <a href="<?php echo base_url('Country/product_destination/' . base64_encode($value->country_id) . '/' . base64_encode($value->product_destination_flag)) ?>" class="btn btn-sm text-success" title="Un-Mark Product Destination Flag"> <i class="fa fa-flag" title="Test Country"></i></a>

                                                        <?php } else { ?>

                                                            <a href="<?php echo base_url('Country/product_destination/' . base64_encode($value->country_id) . '/' . base64_encode($value->product_destination_flag)) ?>" class="btn btn-sm text-black-50" title="Mark Product Destination Flag"> <i class="fa fa-flag" title="Test Country"></i></a>

                                                        <?php } ?>

                                                    <?php } ?>
                                                    <!-- end -->

                                                    <!-- update by ajit 20-05-2021 -->
                                                    <?php if ((exist_val('Country/test_country', $this->session->userdata('permission')))) { ?>

                                                        <?php if ($value->test_country_flag) { ?>

                                                            <a href="<?php echo base_url('Country/test_country/' . base64_encode($value->country_id) . '/' . base64_encode($value->test_country_flag)) ?>" class="btn btn-sm text-success" title="Un-Mark Country"><i class="fa fa-flag" title="Test Country"></i></a>

                                                        <?php } else { ?>

                                                            <a href="<?php echo base_url('Country/test_country/' . base64_encode($value->country_id) . '/' . base64_encode($value->test_country_flag)) ?>" class="btn btn-sm" title="Mark Country"> <img src="<?php echo base_url('assets/images/flag-disable.png') ?>" alt="Test Country" width="20px"></a>

                                                        <?php } ?>

                                                    <?php } ?>
                                                    <!-- end -->

                                                    <?php if ((exist_val('Country/get_country_log', $this->session->userdata('permission')))) { ?>
                                                        <a href="javascript:void(0)" data-id="<?php echo $value->country_id; ?>" class="btn log_view" data-bs-toggle="modal" data-bs-target="#exampleModal" title="Log View"><i class="fa fa-eye" title="View Log"></i></a>
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
                <?php if ($countries_list && count($countries_list) > 0) { ?>
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


<!-- ADD COUNTRY MODAL -->
<div class="modal fade add_country" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content modal-sm">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">ADD COUNTRY</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="country_add" action="javascript:void(0);">
                <div class="modal-body">
                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                    <div class="row p-2">
                        <label for="country_name"><b>Country Name</b></label>
                        <input type="text" class="form-control form-control-sm country_name" name="country_name" placeholder="Specify Country Name">

                        <label for="country_code"><b>Country Code</b></label>
                        <input type="text" class="form-control form-control-sm country_code" name="country_code" placeholder="Specify Country Code">

                        <label for="status"><b>Status:</b></label>
                        <select name="status" class="form-control form-control-sm validate status">
                            <option value="" selected disabled>Select Status</option>
                            <option value="1">Active</option>
                            <option value="0">InActive</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">CLOSE</button>
                    <button type="submit" class="btn btn-primary">ADD</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- END -->

<!-- EDIT COUNTRY MODAL -->
<div class="modal fade" id="edit_country" tabindex="-1" role="dialog" aria-labelledby="cont_editLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content modal-sm">
            <div class="modal-header">
                <h5 class="modal-title" id="cont_editLabel">EDIT COUNTRY DETAILS</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" id="cont_edit" action="<?php echo base_url('Country/update_country'); ?>">
                <div class="modal-body">
                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                    <input type="hidden" id="country_id" name="country_id" value="">
                    <div class="form-group">
                        <label for="country_name"><b>Country Name</b></label>
                        <input type="text" class="form-control form-control-sm country_name" name="country_name" placeholder="Specify Country Name" value="">

                        <label for="country_code"><b>Country Code</b></label>
                        <input type="text" class="form-control form-control-sm country_code" name="country_code" placeholder="Specify Country Code" value="">
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

<!-- Country Log Modal Starts -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content" style="max-height:500px">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Country Log</h5>
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
<!-- Country Log Modal Ends -->

<script>
    function searchfilter() {
        var url = '<?php echo base_url("Country/index"); ?>';
        var id_cont = $('#cont_id').val();
        if (id_cont != "") {
            url = url + '/' + btoa(id_cont);
        } else {
            id_cont = "";
            url = url + '/NULL';
        }

        var id_cont_code = $('#cont_code_id').val();
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

        $('#country_add').on('submit', function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            $.ajax({
                url: "<?php echo base_url('Country/add_country') ?>",
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
                    if (result.error) {
                        var error = result.error;
                        $('.form_errors').remove();
                        $.each(error, function(i, v) {
                            $('#country_add select[name="' + i + '"]').after('<span class="text-danger form_errors">' + v + '</span>');
                            $('#country_add input[name="' + i + '"]').after('<span class="text-danger form_errors">' + v + '</span>');
                        });

                    } else {
                        $('.form_errors').remove();
                    }
                }
            });
            return false;
        });

        // EDIT COUNTRY
        $('.edit_cont').on('click', function() {
            var country_id = $(this).data('id');
            get_edit_country_data(country_id);
            $('#cont_edit #country_id').val(country_id);
        });

        function get_edit_country_data(country_id) {
            const _tokken = $('meta[name="_tokken"]').attr("value");
            $.ajax({
                url: "<?php echo base_url('Country/edit_country') ?>",
                method: "POST",
                data: {
                    country_id: country_id,
                    _tokken: _tokken
                },
                success: function(response) {
                    var data = $.parseJSON(response);
                    console.log(data);
                    if (data) {
                        $('#cont_edit .country_name').val(data.country_name);
                        $('#cont_edit .country_code').val(data.country_code);
                    }
                }
            });
            return false;
        }

        // update country details
        $('#cont_edit').on('submit', function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            $.ajax({
                url: "<?php echo base_url('Country/update_country') ?>",
                method: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    var result = $.parseJSON(response);
                    if (result.status > 0) {
                        $('#edit_country').modal('hide');
                        $("#cont_edit").trigger('reset');
                        window.location.reload();
                    } else {
                        $.notify(result.msg, 'error');
                    }
                    if (result.error) {
                        var error = result.error;
                        $('.form_errors').remove();
                        $.each(error, function(i, v) {
                            $('#cont_edit input[name="' + i + '"]').after('<span class="text-danger form_errors">' + v + '</span>');
                        });

                    } else {
                        $('.form_errors').remove();
                    }
                }
            });
            return false;
        });
        // END

        // changing status of country
        $('.status_chng').click(function() {
            var country_id = $(this).attr("data-id");
            var _tokken = $('meta[name="_tokken"]').attr('value');
            $.ajax({
                url: "<?php echo base_url(); ?>Country/cont_status",
                data: {
                    "country_id": country_id,
                    "_tokken": _tokken
                },
                type: 'post',
                success: function(result) {
                    location.reload();
                }
            });
        });
        // END

        // Country Log
        $('.log_view').click(function(e) {
            e.preventDefault();
            $('#country_log').empty();
            var country_id = $(this).data('id');
            const _tokken = $('meta[name="_tokken"]').attr("value");
            $.ajax({
                type: 'post',
                url: "<?php echo base_url('Country/get_country_log') ?>",
                data: {
                    _tokken: _tokken,
                    country_id: country_id
                },
                success: function(data) {
                    var data = $.parseJSON(data);
                    if (data) {
                        sn = 1;
                        $.each(data, function(index, log) {
                            var value = '';
                            var taken_at = new Date(log.taken_at + ' UTC');
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