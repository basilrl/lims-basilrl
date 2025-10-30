<script src="<?php echo base_url('ckeditor/ckeditor.js'); ?>"></script>
<div class="content-wrapper">
    <section class="content-header">
        <!-- container fluid start -->
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h1>REGULATION CONFIGURATIONS</h1>
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
                                    if (exist_val('RegulationConfiguration/add_regulationconfig', $this->session->userdata('permission'))) { ?>
                                        <button type="button" class="btn btn-sm btn-primary add" data-bs-toggle="modal" data-bs-target=".add_regulationconfig" title="ADD NEW REGULATION CONFIGURATION">ADD</button>
                                    <?php
                                 } ?>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-8">
                                    <div class="row">
                                        <?php if ($id_customer) {
                                            $customer_id = $id_customer;
                                        } else {
                                            $customer_id = 0;
                                        } ?>
                                        <div class="col-sm-3">
                                            <select name="customer_id" id="customer_id" class="form-control form-control-sm select-box">
                                                <option value="">Select Customer Name</option>
                                                <?php foreach ($cust_name as $name) { ?>
                                                    <option value="<?php echo $name->customer_id; ?>" <?php echo ($customer_id == $name->customer_id) ? "selected" : ""; ?>> <?php echo $name->customer_name; ?> </option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <?php if ($id_contact) {
                                            $contact_id = $id_contact;
                                        } else {
                                            $contact_id = 0;
                                        } ?>
                                        <div class="col-sm-3">
                                            <select name="contact_name" id="contact_id" class="form-control form-control-sm select-box">
                                                <option value="">Select Contact Name</option>
                                                <?php foreach ($contact_name as $cname) { ?>
                                                    <option value="<?php echo $cname->contact_id; ?>" <?php echo ($contact_id == $cname->contact_id) ? "selected" : ""; ?>> <?php echo $cname->contact_name; ?> </option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <?php if ($created_pesron) {
                                            $uidnr_admin = $created_pesron;
                                        } else {
                                            $uidnr_admin = "";
                                        } ?>
                                        <div class="col-sm-3">
                                            <select name="created_by" id="created_by" class="form-control form-control-sm select-box">
                                                <option value="">Select Created By</option>
                                                <?php foreach ($created_by_name as $cr_name) { ?>
                                                    <option value="<?php echo $cr_name->uidnr_admin; ?>" <?php echo ($uidnr_admin == $cr_name->uidnr_admin) ? "selected" : ""; ?>> <?php echo $cr_name->created_by; ?> </option>
                                                <?php } ?>
                                            </select>
                                        </div>

                                        <div class="col-sm-3">
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
                                    <a class="btn btn-sm btn-default" href="<?php echo base_url('RegulationConfiguration'); ?>" title="Clear">
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
                                        if ($id_customer != "") {
                                            $id_customer = base64_encode($id_customer);
                                        } else {
                                            $id_customer = "NULL";
                                        }
                                        if ($id_contact != "") {
                                            $id_contact = base64_encode($id_contact);
                                        } else {
                                            $id_contact = "NULL";
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
                                        <th scope="col"><a href="<?php echo base_url('RegulationConfiguration/index/' . $id_customer . '/' . $id_contact . '/' . $created_pesron . '/' . $status .'/' . $search . '/' . 'cust.customer_name' . '/' . $order) ?>">CLIENT NAME</a></th>
                                        <th scope="col"><a href="<?php echo base_url('RegulationConfiguration/index/' . $id_customer . '/' . $id_contact . '/' . $created_pesron . '/' . $status .'/' . $search . '/' . 'cont.contact_name' . '/' . $order) ?>">CONTACT NAME</a></th>
                                        <th scope="col"><a href="<?php echo base_url('RegulationConfiguration/index/' . $id_customer . '/' . $id_contact . '/' . $created_pesron . '/' . $status .'/' . $search . '/' . 'ap.admin_fname' . '/' . $order) ?>">CREATED BY</a></th>
                                        <th scope="col"><a href="<?php echo base_url('RegulationConfiguration/index/' . $id_customer . '/' . $id_contact . '/' . $created_pesron . '/' . $status .'/' . $search . '/' . 'crc.created_date' . '/' . $order) ?>">CREATED DATE</a></th>
                                        <?php
                                         if ( (exist_val('RegulationConfiguration/regconfig_status', $this->session->userdata('permission'))) ) { ?>
                                            <th scope="col">STATUS</th>
                                        <?php
                                     } ?>
                                        <?php 
                                        if ( (exist_val('RegulationConfiguration/edit_regulationconfiguration', $this->session->userdata('permission'))) || (exist_val('RegulationConfiguration/get_user_log_data', $this->session->userdata('permission'))) ) { ?>
                                            <th scope="col">ACTION</th>
                                        <?php
                                     } ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $sn = $this->uri->segment('10') + 1;
                                    if ($regulationconfig_list) {
                                        foreach ($regulationconfig_list as $key => $value) { ?>
                                            <tr>
                                                <td><?php echo $sn; ?></td>
                                                <td><?php echo $value->customer_name ?></td>
                                                <td><?php echo $value->contact_name ?></td>
                                                <td><?php echo $value->admin_fname ?></td>
                                                <td><?php echo change_time($value->created_date,$this->session->userdata('timezone')) ?></td>
                                                <?php 
                                                if ( (exist_val('RegulationConfiguration/regconfig_status', $this->session->userdata('permission'))) ) { ?>
                                                    <?php if ($value->status == 1) { ?>
                                                        <td><span class="btn btn-success status_chng" id="active" data-id="<?php echo $value->reg_conf_id; ?>">Active</span></td>
                                                    <?php } else { ?>
                                                        <td><span class="btn btn-danger status_chng" id="deactive" data-id="<?php echo $value->reg_conf_id; ?>">Deactive</span></td>
                                                    <?php } ?>
                                                <?php
                                             } ?>
                                                <td>
                                                    <?php 
                                                    if ( (exist_val('RegulationConfiguration/edit_regulationconfiguration', $this->session->userdata('permission'))) ) { ?>
                                                        <button type="button" class="btn btn-sm btn-default edit_reg_config" title="Edit" data-bs-toggle="modal" data-bs-target="#edit_regulationconfiguration" data-id="<?php echo $value->reg_conf_id ?>">
                                                            <img src="<?php echo base_url('assets/images/mem_edit.png') ?>" alt="Edit Regulation Configuration">
                                                        </button>
                                                    <?php 
                                                } ?>
                                                    
                                                    <?php
                                                     if ( (exist_val('RegulationConfiguration/get_log_data', $this->session->userdata('permission'))) ) { ?>
                                                        <a href="javascript:void(0)" data-id="<?php echo  $value->reg_conf_id?>" class="log_view" data-bs-toggle='modal' data-bs-target='#lo_view_target' class="btn btn-sm" title="Log View"><img src="<?php echo base_url('assets/images/log-view.png'); ?>" alt="Log view" width="20px"></a>
                                                    <?php 
                                                } ?>
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
                <?php if ($regulationconfig_list && count($regulationconfig_list) > 0) { ?>
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


<!-- ADD REGULATION CONFIGURATION MODAL -->
<div class="modal fade bd-example-modal-sm add_regulationconfig" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content modal-md">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">ADD REGULATION CONFIGURATION</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="add_regulationconfig" action="javascript:void(0);">
                <div class="modal-body">
                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                    <div class="row p-2">
                        <div class="col-sm-6">
                            <label for=""><b>Customer Name</b></label>
                            <select name="customer_id" class="form-control form-control-sm validate customer_id">
                                <option value="">Select Customer Name</option>
                                <?php foreach ($cust_name as $name) { ?>
                                    <option value="<?php echo $name->customer_id; ?>"> <?php echo $name->customer_name; ?> </option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-sm-6">
                            <label for=""><b>Contact Name</b></label>
                            <select name="contact_id" class="form-control form-control-sm validate contact_id">
                                <option value="">Select Contact Name</option>
                            </select>
                        </div>
                    </div>
                    <div class="row p-2">
                        <div class="col-sm-6">
                            <label for=""><b>Country</b></label>
                            <select name="country_id[]" class="form-control form-control-sm validate country_id" multiple>
                                <option value="" disabled>Select Country</option>
                                <?php foreach ($countries as $con) { ?>
                                    <option value="<?php echo $con->country_id; ?>"> <?php echo $con->country_name; ?> </option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-sm-6">
                            <label for=""><b>Notification Body</b></label>
                            <select name="notified_body_id[]" class="form-control form-control-sm validate notified_body_id" multiple>
                                <option value="" disabled>Select Notified Body</option>
                                <?php foreach ($noti_bodies as $nb) { ?>
                                    <option value="<?php echo $nb->notified_body_id; ?>"> <?php echo $nb->notified_body_name; ?> </option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-sm-6">
                            <label for=""><b>Select Category</b></label>
                            <select name="division_id[]" class="form-control form-control-sm validate division_id" multiple>
                                <option value="" disabled>Select Category</option>
                                <?php foreach ($samp_cat as $sam) { ?>
                                    <option value="<?php echo $sam->division_id; ?>"> <?php echo $sam->division_name; ?> </option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-sm-6">
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

<!-- EDIT REGULATION CONFIGURATION MODAL -->
<div class="modal fade" id="edit_regulationconfiguration" tabindex="-1" role="dialog" aria-labelledby="regconfig_editLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content modal-lg" style="text-align: right">
            <div class="modal-header">
                <h5 class="modal-title" id="regconfig_editLabel">Edit Regulation Configuration Details</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" id="regconfig_edit" action="<?php echo base_url('RegulationConfiguration/update_regulationconfiguration'); ?>">
                <div class="modal-body">
                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                    <input type="hidden" id="reg_conf_id" name="reg_conf_id" value="">
                    <div class="form-group text-left">
                        <div class="row p-2">
                            <div class="col-sm-6">
                                <label for=""><b>Customer Name</b></label>
                                <select name="customer_id" class="form-control form-control-sm validate customer_id">
                                    <option value="">Select Customer Name</option>
                                    <?php foreach ($cust_name as $name) { ?>
                                        <option value="<?php echo $name->customer_id; ?>"> <?php echo $name->customer_name; ?> </option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-sm-6">
                                <label for=""><b>Contact Name</b></label>
                                <select name="contact_id" class="form-control form-control-sm validate contact_id">
                                    <option value="">Select Contact Name</option>
                                </select>
                            </div>
                        </div>
                        <div class="row p-2">
                            <div class="col-sm-6">
                                <label for=""><b>Country</b></label>
                                <select name="country_id[]" class="form-control form-control-sm validate country_id" multiple>
                                    <option value="" disabled>Select Country</option>
                                    <?php foreach ($countries as $con) { ?>
                                        <option value="<?php echo $con->country_id; ?>"> <?php echo $con->country_name; ?> </option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-sm-6">
                                <label for=""><b>Notification Body</b></label>
                                <select name="notified_body_id[]" class="form-control form-control-sm validate notified_body_id" multiple>
                                    <option value="" disabled>Select Notified Body</option>
                                    <?php foreach ($noti_bodies as $nb) { ?>
                                        <option value="<?php echo $nb->notified_body_id; ?>"> <?php echo $nb->notified_body_name; ?> </option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-sm-6">
                                <label for=""><b>Select Category</b></label>
                                <select name="division_id[]" class="form-control form-control-sm validate division_id" multiple>
                                    <option value="" disabled>Select Category</option>
                                    <?php foreach ($samp_cat as $sam) { ?>
                                        <option value="<?php echo $sam->division_id; ?>"> <?php echo $sam->division_name; ?> </option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-sm-6">
                                <label for=""><b>Status</b></label>
                                <select name="status" class="form-control form-control-sm validate status">
                                    <option value="" selected disabled>Select Status</option>
                                    <option value="1">Active</option>
                                    <option value="0">InActive</option>
                                </select>
                            </div>
                        </div>
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

<div class="modal fade" id="lo_view_target" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content" style="max-height: 500px;">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">NEWS FLASH LOG</h5>
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

<!-- user log windows -->
<div class="modal user_log_windows" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content" style="">
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

<script>
    function searchfilter() {
        var url = '<?php echo base_url("RegulationConfiguration/index"); ?>';
        var id_customer = $('#customer_id').val();
        if (id_customer != "") {
            url = url + '/' + btoa(id_customer);
        } else {
            id_customer = "";
            url = url + '/NULL';
        }

        var id_contact = $('#contact_id').val();
        if (id_contact != "") {
            url = url + '/' + btoa(id_contact);
        } else {
            id_contact = "";
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
        $('#add_regulationconfig').on('submit', function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            $.ajax({
                url: "<?php echo base_url('RegulationConfiguration/add_regulationconfig') ?>",
                method: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    var result = $.parseJSON(response);
                    if (result.status > 0) {
                        $('.add_regulationconfig').modal('hide');
                        $("#add_regulationconfig").trigger('reset');
                        window.location.reload();
                    } else {
                        $.notify(result.msg, 'error');
                    }
                    if (result.errors) {
                        var error = result.errors;
                        $('.form_errors').remove();
                        $.each(error, function(i, v) {
                            $('#add_regulationconfig select[name="' + i + '"]').after('<span class="text-danger form_errors">' + v + '</span>');
                        });

                    } else {
                        $('.form_errors').remove();
                    }
                }
            });
            return false;
        });

        $(document).on("change", ".customer_id", function() {
            var cust_id = $(this).val();
            var _tokken = $('meta[name="_tokken"]').attr('value');
            $.ajax({
                url: "<?php echo base_url('RegulationConfiguration/extract_cont_name'); ?>",
                data: {
                    contacts_customer_id: cust_id,
                    _tokken: _tokken
                },
                type: 'post',
                success: function(result) {
                    $('.contact_id').empty().append(' <option class="text-dark" value="">Select Contact Name</option>');
                    var data = $.parseJSON(result);
                    if (data) {
                        $.each(data, function(i, v) {
                            $('.contact_id').append(' <option class="text-dark" value="' + v.contact_id + '">' + v.contact_name + '</option>');
                        });
                    }
                }
            });
        });

        // EDIT REGULATION CONFIGURATION
        $('.edit_reg_config').on('click', function() {
            var reg_conf_id = $(this).data('id');
            get_edit_regulationconfig_data(reg_conf_id);
            $('#regconfig_edit #reg_conf_id').val(reg_conf_id);
        });

        function get_edit_regulationconfig_data(reg_conf_id) {
            const _tokken = $('meta[name="_tokken"]').attr("value");
            $.ajax({
                url: "<?php echo base_url('RegulationConfiguration/edit_regulationconfiguration') ?>",
                method: "POST",
                data: {
                    reg_conf_id: reg_conf_id,
                    _tokken: _tokken
                },
                success: function(response) {
                    var data = $.parseJSON(response);
                    
                    var countries = data.country_id;
                    var divisions = data.division_id;
                    var notify_bodies = data.notified_body_id;
                    if (data) {
                        $('#regconfig_edit .customer_id option[value=' + data.customer_id + ']').attr('selected', 'selected');
                        contact_name(data.customer_id, data.contact_id);
                        $('#regconfig_edit .country_id').val(countries);
                        $('#regconfig_edit .country_id').select2().trigger('change');
                        $('#regconfig_edit .division_id').val(divisions);
                        $('#regconfig_edit .division_id').select2().trigger('change');
                        $('#regconfig_edit .notified_body_id').val(notify_bodies);
                        $('#regconfig_edit .notified_body_id').select2().trigger('change');
                        $('#regconfig_edit .status option[value=' + data.status + ']').attr('selected', 'selected');
                    }
                }
            });
            return false;
        }

        function contact_name(customer_id, contact_id) {
            var _tokken = $('meta[name="_tokken"]').attr('value');
            $.ajax({
                url: "<?php echo base_url('RegulationConfiguration/extract_cont_name'); ?>",
                type: 'post',
                data: {
                    contacts_customer_id: customer_id,
                    _tokken: _tokken
                },
                success: function(result) {
                    var data = $.parseJSON(result);
                    if (data) {
                        $.each(data, function(i, v) {
                            if (contact_id) {
                                if (contact_id == v.contact_id) {
                                    $('#regconfig_edit .contact_id').append('<option selected value="' + v.contact_id + '">' + v.contact_name + '</option>');
                                } else {
                                    $('#regconfig_edit .contact_id').append('<option value="' + v.contact_id + '">' + v.contact_name + '</option>');
                                }
                            } else {
                                $('#regconfig_edit .contact_id').append('<option value="' + v.contact_id + '">' + v.contact_name + '</option>');
                            }
                        });
                    }
                }
            })
        }

        $('#regconfig_edit').on('submit', function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            $.ajax({
                url: "<?php echo base_url('RegulationConfiguration/update_regulationconfiguration') ?>",
                method: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    var result = $.parseJSON(response);
                    if (result.status > 0) {
                        $('#edit_regulationconfiguration').modal('hide');
                        $("#regconfig_edit").trigger('reset');
                        window.location.reload();
                    } else {
                        $.notify(result.msg, 'error');
                    }
                    if (result.errors) {
                        var error = result.errors;
                        $('.form_errors').remove();
                        $.each(error, function(i, v) {
                            $('#regconfig_edit select[name="' + i + '"]').after('<span class="text-danger form_errors">' + v + '</span>');
                        });

                    } else {
                        $('.form_errors').remove();
                    }
                }
            });
            return false;
        });
        // END


        // user log detail fetching
        $('.user_log_btn').on('click', function() {
            var reg_conf_id = $(this).data('id');
            get_user_log_data(reg_conf_id);

        });

        function get_user_log_data(reg_conf_id) {
            const _tokken = $('meta[name="_tokken"]').attr("value");
            $.ajax({
                url: "<?php echo base_url('RegulationConfiguration/get_user_log_data') ?>",
                method: "POST",
                data: {
                    reg_conf_id: reg_conf_id,
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

        $('.status_chng').click(function() {
            var reg_conf_id = $(this).attr("data-id");
            var _tokken = $('meta[name="_tokken"]').attr('value');
            $.ajax({
                url: "<?php echo base_url(); ?>RegulationConfiguration/regconfig_status",
                data: {
                    "reg_conf_id": reg_conf_id,
                    "_tokken": _tokken
                },
                type: 'post',
                success: function(result) {
                    location.reload();
                }
            });
        });
        // END
    });
</script>

<script>
    $(document).ready(function() {
        $('#add_regulationconfig .country_id, .notified_body_id, .division_id').select2();
        $('#edit_regulationconfiguration .country_id, .notified_body_id, .division_id').select2();
    });
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
          url: url + 'RegulationConfiguration/get_log_data',
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