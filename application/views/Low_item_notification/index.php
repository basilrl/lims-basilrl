<style>
    #application_name-error,
    #application_desc-error {
        color: red;
    }
</style>
<script src="<?php echo base_url(); ?>assets/js/Low_item_notification.js"></script>
<script src="<?php echo base_url('ckeditor/ckeditor.js'); ?>"></script>
<main class="main">
    <div class="container text-center"><br /><br />
        <h2><i class="fa fa-bars"></i> Low Stock Notification </h2>
    </div>


    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-3">
            </div>
            <div class="col-sm-9">
                <div class="row">
                    <div class="col-sm-6">
                        <input type="text" class="search form-control form-control-sm" placeholder="ENTER BY NAME" name="" id="">
                    </div>
                    <div class="col-sm-4">
                        <button class="btn btn-sm btn-primary search_listing">SEARCH</button>
                        <button class="btn btn-sm btn-danger clear_listing">CLEAR</button>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <div class=" mt-3 table-responsive small">
        <table class="table table-sm">
            <thead>
                <tr>
                    <th scope="col">SL NO</th>
                    <th>Item Name</th>
                    <th>Quantity Available</th>
                    <th>Quantity Required</th>
                    <th>Store</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="application_list"></tbody>
        </table>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-sm-4"></div>
            <div class="col-sm-4" id="application_pagination"></div>
            <div class="col-sm-4"></div>
        </div>
    </div>
</main>

<div class="modal fade" id="add_Store" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">LOG</h5><button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12" id="table_log"></div>
                </div>
            </div>
            <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">CLOSE</button><button type="submit" id="form-submit" class="btn btn-primary">ADD</button></div>
        </div>
    </div>
</div>

<!-- modal for add requiremnet details added by millan on 10-03-2021 -->
<div class="modal fade" id="add_ItemDetails" tabindex="-1" role="dialog" aria-labelledby="item_reqLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document" style="text-align: right">
        <div class="modal-content modal-lg" style="text-align: right">
            <div class="modal-header">
                <h5 class="modal-title" id="item_reqLabel">ADD REQUIREMENT DETAILS</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" id="item_ReqDetails" action="<?php echo base_url('Low_item_notification/add_requiremnetdetails'); ?>">
                <div class="modal-body">
                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                    <input type="hidden" name="item_id" id="item_id" value="">
                    <div class="form-group text-left">
                        <div class="row">
                            <div class="col-sm-6">
                                <label for="item_name"> <b> ITEM NAME: </b> </label>
                                <input type="text" name="item_name" class="form-control form-control-sm item_name">
                            </div>

                            <div class="col-sm-6">
                                <label for="category_name"> <b> CATEGORY: </b> </label>
                                <input type="text" name="category_name" class="form-control form-control-sm category_name">
                            </div>

                            <div class="col-sm-6">
                                <label for="min_quantity_required"> <b> MINIMUM QUANTITY REQUIRED: </b> </label>
                                <input type="number" name="min_quantity_required" class="form-control form-control-sm min_quantity_required" placeholder="Specify Minimum Quantity">
                            </div>

                            <div class="col-sm-6">
                                <label for="current_requirement"> <b> CURRENT REQUIREMENT: </b> </label>
                                <input type="number" name="current_requirement" class="form-control form-control-sm current_requirement" placeholder="Specify Current Requirement">
                            </div>

                            <div class="col-sm-12">
                                <label for="unit_id"> <b> UNIT: </b> </label>
                                <select name="unit_id" class="form-control form-control-sm unit_id">
                                    <option value="" selected disabled>Select Unit</option>
                                    <?php foreach ($units_list as $unt) { ?>
                                        <option value="<?php echo $unt->unit_id; ?>"> <?php echo $unt->unit; ?> </option>
                                    <?php } ?>
                                </select>

                                <label for="requirement_reason"> <b> REASON: </b> </label>
                                <textarea name="requirement_reason" rows="4" cols="10" class="form-control form-control-sm requirement_reason" placeholder="Specify Requirement Reason"></textarea>
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
<!-- modal for add requiremnet details ends -->

<!-- modal for edit requiremnet details added by millan on 10-03-2021 -->
<div class="modal fade" id="edit_ItemDetails" tabindex="-1" role="dialog" aria-labelledby="edititem_reqLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document" style="text-align: right">
        <div class="modal-content modal-lg" style="text-align: right">
            <div class="modal-header">
                <h5 class="modal-title" id="edititem_reqLabel">EDIT REQUIREMENT DETAILS</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" id="edititem_ReqDetails" action="<?php echo base_url('Low_item_notification/update_requirementdetails'); ?>">
                <div class="modal-body">
                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                    <input type="hidden" name="item_id" id="item_id" value="">
                    <input type="hidden" name="item_req_id" id="item_req_id" value="">
                    <div class="form-group text-left">
                        <div class="row">
                            <div class="col-sm-6">
                                <label for="item_name"> <b> ITEM NAME: </b> </label>
                                <input type="text" name="item_name" class="form-control form-control-sm item_name">
                            </div>

                            <div class="col-sm-6">
                                <label for="category_name"> <b> CATEGORY: </b> </label>
                                <input type="text" name="category_name" class="form-control form-control-sm category_name">
                            </div>

                            <div class="col-sm-6">
                                <label for="min_quantity_required"> <b> MINIMUM QUANTITY REQUIRED: </b> </label>
                                <input type="number" name="min_quantity_required" class="form-control form-control-sm min_quantity_required" placeholder="Specify Minimum Quantity">
                            </div>

                            <div class="col-sm-6">
                                <label for="current_requirement"> <b> CURRENT REQUIREMENT: </b> </label>
                                <input type="number" name="current_requirement" class="form-control form-control-sm current_requirement" placeholder="Specify Current Requirement">
                            </div>

                            <div class="col-sm-12">
                                <label for="unit_id"> <b> UNIT: </b> </label>
                                <select name="unit_id" class="form-control form-control-sm unit_id">
                                    <option value="" selected disabled>Select Unit</option>
                                    <?php foreach ($units_list as $unt) { ?>
                                        <option value="<?php echo $unt->unit_id; ?>"> <?php echo $unt->unit; ?> </option>
                                    <?php } ?>
                                </select>

                                <label for="requirement_reason"> <b> REASON: </b> </label>
                                <textarea name="requirement_reason" rows="4" cols="10" class="form-control form-control-sm requirement_reason" placeholder="Specify Requirement Reason"></textarea>
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
<!-- modal for edit requiremnet details ends -->

<!-- modal for showing cotent on iframe added by millan on 10-03-2021 -->
<div class="modal fade" id="report_lowItem" tabindex="-1" role="dialog" aria-labelledby="edititem_reqLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document" style="text-align: right">
        <div class="modal-content modal-lg" style="text-align: right">
            <div class="modal-header">
                <h5 class="modal-title" id="edititem_reqLabel">REQUIREMENT DETAILS</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" id="mail_approval_lab" action="javascript:void(0);">
                <input type="hidden" name="item_id" id="item_id" value="">
                <input type="hidden" name="item_req_id" id="item_req_id" value="">
                <input type="hidden" class="txt_csrfname" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
                <div class="modal-body get_report_data">
                </div>
                <div class="modal-footer btn_app_mm">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <!-- <?php // if( empty($result->lm_sign) && ($result->lab_manager_status == 'Accepted') ) { 
                            ?>
                        <button type="submit" class="btn btn-primary mail_approval_btn"> Send Mail to Lab Manager</button>
                    <?php // } else   { 
                    ?>
                        <button type="submit" class="btn btn-primary approval_lmgm"> Approved </button>
                    <?php // } 
                    ?> -->
                </div>
            </form>
        </div>
    </div>
</div>
<!-- modal for showing cotent on iframe requiremnet details ends -->

<!-- modal for approval from lab manager added by millan on 15-03-2021 -->
<div class="modal fade" id="lab_manager_approval" tabindex="-1" role="dialog" aria-labelledby="apLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document" style="margin: 0px auto;">
        <div class="modal-content modal-sm" style="margin: 0px auto;">
            <div class="modal-header">
                <h5 class="modal-title" id="apLabel">APPROVED FROM LAB MANAGER</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" id="approve_reason_lab" action="<?php echo base_url('Low_item_notification/lab_manager_approval'); ?>">
                <div class="modal-body">
                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                    <input type="hidden" name="item_id" id="item_id" value="">
                    <input type="hidden" name="item_req_id" id="item_req_id" value="">
                    <div class="form-group text-left">
                        <div class="row">
                            <label for="lab_manager_reason"> <b> REASON: </b> </label>
                            <textarea name="lab_manager_reason" rows="6" cols="10" class="form-control form-control-sm lab_manager_reason" placeholder="Specify Approval Reason"></textarea>
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
<!-- modal for approval from lab manager ends -->

<!-- modal for discarded from lab manager added by millan on 15-03-2021 -->
<div class="modal fade" id="lab_manager_rejection" tabindex="-1" role="dialog" aria-labelledby="apLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document" style="margin: 0px auto;">
        <div class="modal-content modal-sm" style="margin: 0px auto;">
            <div class="modal-header">
                <h5 class="modal-title" id="apLabel">REJECTED FROM LAB MANAGER</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" id="reject_reason_lab" action="<?php echo base_url('Low_item_notification/lab_manager_rejection'); ?>">
                <div class="modal-body">
                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                    <input type="hidden" name="item_id" id="item_id" value="">
                    <input type="hidden" name="item_req_id" id="item_req_id" value="">
                    <div class="form-group text-left">
                        <div class="row">
                            <label for="lab_manager_reason"> <b> REASON: </b> </label>
                            <textarea name="lab_manager_reason" rows="6" cols="10" class="form-control form-control-sm lab_manager_reason" placeholder="Specify Approval Reason"></textarea>
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
<!-- modal for discarded from lab manager ends -->

<!-- modal for select vendors added by millan on 15-03-2021 -->
<div class="modal fade" id="vendor_mail" tabindex="-1" role="dialog" aria-labelledby="vmLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document" style="margin: 0px auto;">
        <div class="modal-content modal-lg" style="margin: 0px auto;">
            <div class="modal-header">
                <h5 class="modal-title" id="vmLabel">VENDOR DETAILS</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" id="ml_vend_dls" action="javascript:void(0)">
                <div class="modal-body">
                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                    <input type="hidden" name="item_req_id" id="item_req_id" value="">
                    <div class="form-group text-left">
                        <div class="row view_vn_dls">
                            <div class="col-sm-12">
                                <select name="email_name[]" class="form-control-sm email_name" multiple>
                                    <option value="" disabled> Select Vendors </option>
                                    <option value=""> </option>
                                </select>
                                <input type="text" class="form-control form-control-sm mt-4" placeholder="ENTER To E-MAIL ID" name="to_email" value="" id="to_email">

                                <input type="text" class="form-control form-control-sm mt-4" placeholder="ENTER CC E-MAIL ID" name="cc_email" value="" id="cc_email">

                                <input type="text" class="form-control form-control-sm mt-4 mb-4" placeholder="ENTER Subject" name="subject_email" value="" id="subject_email">

                                <textarea name="message_body" rows="4" cols="10" class="form-control form-control-sm message_body" placeholder="Specify Message Content"></textarea>
                                <script>
                                    CKEDITOR.replace('message_body');
                                </script>
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
<!-- modal for select vendor ends -->

<script>
    $(document).ready(function() {
        $(document).on('click', '.item_req_modal', function() {
            var item_id = $(this).data('id');
            var _tokken = $('meta[name="_tokken"]').attr('value');
            $.ajax({
                url: "<?php echo base_url('Low_item_notification/fetch_item_details'); ?>",
                type: "post",
                data: {
                    item_id: item_id,
                    _tokken: _tokken
                },
                success: function(result) {
                    var data = $.parseJSON(result);
                    $('#item_ReqDetails #item_id').attr('value', data.item_id);
                    $('#item_ReqDetails .item_name').attr('value', data.item_name);
                    $('#item_ReqDetails .item_name').attr('readonly', true);
                    $('#item_ReqDetails .category_name').attr('value', data.category_name);
                    $('#item_ReqDetails .category_name').attr('readonly', true);
                    $('#item_ReqDetails .min_quantity_required').attr('value', data.min_quantity_required);
                    $('#item_ReqDetails .unit_id option[value=' + data.unit + ']').attr('selected', true);
                }
            });
            return false;
        });

        $(document).on("submit", "#item_ReqDetails", function(e) {
            e.preventDefault();
            var getdata = new FormData(this);
            $.ajax({
                url: "<?php echo base_url('Low_item_notification/add_requiremnetdetails'); ?>",
                data: $(this).serialize(),
                type: 'post',
                success: function(result) {
                    $('.form_errors').remove();
                    var data = $.parseJSON(result);
                    if (data.status > 0) {
                        location.reload();
                    }
                    if (data.error) {
                        $.each(data.error, function(i, v) {
                            $('#item_ReqDetails input[name=' + i + ']').after('<span class="text-danger form_errors">' + v + '</span>');
                            $('#item_ReqDetails textarea[name=' + i + ']').after('<span class="text-danger form_errors">' + v + '</span>');
                            $('#item_ReqDetails select[name=' + i + ']').after('<span class="text-danger form_errors">' + v + '</span>');
                        });
                    }
                }
            });
            return false;
        });

        $(document).on('click', '.edit_item_req_modal', function() {
            var item_req_id = $(this).data('id');
            var _tokken = $('meta[name="_tokken"]').attr('value');
            $.ajax({
                url: "<?php echo base_url('Low_item_notification/fetch_item_requirement_details'); ?>",
                type: "post",
                data: {
                    item_req_id: item_req_id,
                    _tokken: _tokken
                },
                success: function(result) {
                    var data = $.parseJSON(result);
                    $('#edititem_ReqDetails #item_id').attr('value', data.item_id);
                    $('#edititem_ReqDetails #item_req_id').attr('value', data.item_req_id);
                    $('#edititem_ReqDetails .item_name').attr('value', data.item_name);
                    $('#edititem_ReqDetails .item_name').attr('readonly', true);
                    $('#edititem_ReqDetails .category_name').attr('value', data.category_name);
                    $('#edititem_ReqDetails .category_name').attr('readonly', true);
                    $('#edititem_ReqDetails .min_quantity_required').attr('value', data.min_quantity_required);
                    $('#edititem_ReqDetails .unit_id option[value=' + data.unit_id + ']').attr('selected', true);
                    $('#edititem_ReqDetails .current_requirement').attr('value', data.current_requirement);
                    $('#edititem_ReqDetails .requirement_reason').val(data.requirement_reason);
                }
            });
        });

        $(document).on("submit", "#edititem_ReqDetails", function(e) {
            e.preventDefault();
            var getdata = new FormData(this);
            $.ajax({
                url: "<?php echo base_url('Low_item_notification/update_requiremnetdetails'); ?>",
                data: $(this).serialize(),
                type: 'post',
                success: function(result) {
                    $('.form_errors').remove();
                    var data = $.parseJSON(result);
                    if (data.status > 0) {
                        location.reload();
                    }
                    if (data.error) {
                        $.each(data.error, function(i, v) {
                            $('#edititem_ReqDetails input[name=' + i + ']').after('<span class="text-danger form_errors">' + v + '</span>');
                            $('#edititem_ReqDetails textarea[name=' + i + ']').after('<span class="text-danger form_errors">' + v + '</span>');
                            $('#edititem_ReqDetails select[name=' + i + ']').after('<span class="text-danger form_errors">' + v + '</span>');
                        });
                    }
                }
            });
            return false;
        });

        $(document).on('click', '.ger_report', function() {
            var item_req_id = $(this).data('id');
            var _tokken = $('meta[name="_tokken"]').attr('value');
            $.ajax({
                url: "<?php echo base_url('Low_item_notification/approver_mail'); ?>",
                type: 'post',
                data: {
                    item_req_id: item_req_id,
                    _tokken: _tokken
                },
                success: function(result) {
                    var data = $.parseJSON(result);
                    $('#mail_approval_lab #item_req_id').val(atob(item_req_id));
                    if (data) {
                        $('.get_report_data').html(data.html);
                        $('.btn_app_mm').html(data.btn);
                    }
                }
            })
        });

        $(document).on('submit', '#mail_approval_lab', function(e) {
            var item_req_id = $(this).data('id');
            var _tokken = $('meta[name="_tokken"]').attr('value');
            var formData = new FormData(this);
            e.preventDefault();
            $.ajax({
                url: "<?php echo base_url('Low_item_notification/approve_on_mail'); ?>",
                type: "post",
                data: formData,
                processData: false,
                contentType: false,
                success: function(result) {
                    var data = $.parseJSON(result);
                    if (data.status > 0) {
                        $('#report_lowItem').modal('hide');
                        $.notify('Mail Sent Successfully', 'success');
                    }
                }
            });
        });

        $(document).on('click', '.reason_lab_accepted', function() {
            var item_req_id = $(this).data('id');
            var _tokken = $('meta[name="_tokken"]').attr('value');
            $.ajax({
                url: "<?php echo base_url('Low_item_notification/fetch_item_requirement_details'); ?>",
                type: "post",
                data: {
                    item_req_id: item_req_id,
                    _tokken: _tokken
                },
                success: function(result) {
                    var data = $.parseJSON(result);
                    $('#approve_reason_lab #item_id').attr('value', data.item_id);
                    $('#approve_reason_lab #item_req_id').attr('value', data.item_req_id);
                }
            });
        });

        $(document).on("submit", "#approve_reason_lab", function(e) {
            e.preventDefault();
            var getdata = new FormData(this);
            $.ajax({
                url: "<?php echo base_url('Low_item_notification/lm_approval'); ?>",
                data: $(this).serialize(),
                type: 'post',
                success: function(result) {
                    $('.form_errors').remove();
                    var data = $.parseJSON(result);
                    if (data.status > 0) {
                        location.reload();
                    }
                    if (data.error) {
                        $.each(data.error, function(i, v) {
                            $('#approve_reason_lab textarea[name=' + i + ']').after('<span class="text-danger form_errors">' + v + '</span>');
                        });
                    }
                }
            });
            return false;
        });

        $(document).on('click', '.reason_lab_rejected', function() {
            var item_req_id = $(this).data('id');
            var _tokken = $('meta[name="_tokken"]').attr('value');
            $.ajax({
                url: "<?php echo base_url('Low_item_notification/fetch_item_requirement_details'); ?>",
                type: "post",
                data: {
                    item_req_id: item_req_id,
                    _tokken: _tokken
                },
                success: function(result) {
                    var data = $.parseJSON(result);
                    $('#reject_reason_lab #item_id').attr('value', data.item_id);
                    $('#reject_reason_lab #item_req_id').attr('value', data.item_req_id);
                }
            });
        });

        $(document).on("submit", "#reject_reason_lab", function(e) {
            e.preventDefault();
            var getdata = new FormData(this);
            $.ajax({
                url: "<?php echo base_url('Low_item_notification/lm_rejection'); ?>",
                data: $(this).serialize(),
                type: 'post',
                success: function(result) {
                    $('.form_errors').remove();
                    var data = $.parseJSON(result);
                    if (data.status > 0) {
                        location.reload();
                    }
                    if (data.error) {
                        $.each(data.error, function(i, v) {
                            $('#reject_reason_lab textarea[name=' + i + ']').after('<span class="text-danger form_errors">' + v + '</span>');
                        });
                    }
                }
            });
            return false;
        });

        $(document).on('click', '.approval_lmgm', function(e) {
            e.preventDefault();
            var item_req_id = $(this).data('id');
            var _tokken = $('meta[name="_tokken"]').attr('value');
            $.ajax({
                url: "<?php echo base_url('Low_item_notification/approve_pathlmgm'); ?>",
                type: "post",
                data: {
                    item_req_id: item_req_id,
                    _tokken: _tokken
                },
                success: function(result) {
                    var data = $.parseJSON(result);
                    $('#mail_approval_lab #item_req_id').attr('value', data.item_req_id);
                }
            });
        });

        $(document).on('click', '.mail_vendor', function(e) {
            e.preventDefault();
            var item_req_id = $(this).data('id');
            var _tokken = $('meta[name="_tokken"]').attr('value');
            $('#ml_vend_dls #item_req_id').attr('value', item_req_id);
            $.ajax({
                url: "<?php echo base_url('Low_item_notification/fetch_vend_details') ?>",
                type: "post",
                data: {
                    item_req_id: item_req_id,
                    _tokken: _tokken
                },
                success: function(result) {
                    var data = $.parseJSON(result);
                    $('.view_vn_dls .email_name').empty();
                    if (data) {
                        var html = '';
                        $.each(data.result, function(key, value) {
                            html += '<option value=" ' + value.email + ' "> ' + value.vendor_name + '  </option>';
                        });
                        $('.view_vn_dls .email_name').append(html);
                    } else {
                        $('.view_vn_dls .email_name').append('<option> NO RECORD FOUND </option>');
                    }
                }
            })
        });
    });
    $(document).on('change', '.email_name', function() {
        $('#to_email').val($(this).val());
    });

    $(document).on("submit", "#ml_vend_dls", function(e) {
        e.preventDefault();
        var getdata = new FormData(this);
        $.ajax({
            url: "<?php echo base_url('Low_item_notification/vendor_mail'); ?>",
            data: $(this).serialize(),
            type: 'post',
            success: function(result) {
                $('.form_errors').remove();
                var data = $.parseJSON(result);
                if (data.status > 0) {
                    location.reload();
                }
                // if (data.error) {
                //     $.each(data.error, function(i, v) {
                //         // $('#approve_reason_lab textarea[name=' + i + ']').after('<span class="text-danger form_errors">' + v + '</span>');
                //     });
                // }
            }
        });
        return false;
    });
</script>

<script>
    $(document).ready(function() {
        $('.view_vn_dls .email_name').select2({
            placeholder: 'Select Vendors'
        });
    });
</script>