<script>
    function inArray(value, arraylist) {
        var length = arraylist.length;
        for (var i = 0; i < length; i++) {
            if (arraylist[i] == value)
                return true;
        }
        return false;
    }
    $(document).ready(function() {
        $.validator.setDefaults({
            submitHandler: function() {
                alert("submitted!");
            }
        });

        $("body").on("click", ".accordion_head", function() {
            if ($('.accordion_body').is(':visible')) {
                $(".accordion_body").slideUp(300);
                $(".plusminus").text('+');
            }
            if ($(this).next(".accordion_body").is(':visible')) {
                $(this).next(".accordion_body").slideUp(300);
                $(this).children(".plusminus").text('+');
            } else {
                $(this).next(".accordion_body").slideDown(300);
                $(this).children(".plusminus").text('-');
            }
        });
    });
</script>

<section class="adjustheight">
    <main class="main">
        <div class="container-fluid">
            <div class="container text-center"><br />
                <h2 class="text-info mb-4"><i class="fa fa-tasks"></i> Opportunity </h2>
            </div>
            <div class="container-fluid">
                <div class="row">
                    <?php if ($type_customer) {
                        $opportunity_customer_type = $type_customer;
                    } else {
                        $opportunity_customer_type = "";
                    } ?>
                    <div class="col-sm-2">
                        <select name="opportunity_customer_type" id="opportunity_customer_type" class="form-control form-control-sm">
                            <option value="">Select Customer Type</option>
                            <option value="Factory" <?php echo ($opportunity_customer_type == 'Factory') ? "selected" : ""; ?>>Factory</option>
                            <option value="Buyer" <?php echo ($opportunity_customer_type == 'Buyer') ? "selected" : ""; ?>>Buyer</option>
                            <option value="Agent" <?php echo ($opportunity_customer_type == 'Agent') ? "selected" : ""; ?>>Agent</option>
                            <option value="Thirdparty" <?php echo ($opportunity_customer_type == 'Thirdparty') ? "selected" : ""; ?>>Thirdparty</option>
                        </select>
                    </div>
                    <?php if ($name_opportunity) {
                        $opportunity_name = $name_opportunity;
                    } else {
                        $opportunity_name = "";
                    } ?>
                    <div class="col-sm-2">
                        <select name="opportunity_name" id="opportunity_name" class="form-control form-control-sm">
                            <option value="">Select Opportunity Name</option>
                            <?php foreach ($opportunity_names as $opp_name) { ?>
                                <option value="<?php echo $opp_name->opportunity_name; ?>" <?php echo ($opportunity_name == $opp_name->opportunity_name) ? "selected" : ""; ?>> <?php echo $opp_name->opportunity_name; ?> </option>
                            <?php } ?>
                        </select>
                    </div>
                    <?php if ($types_opportunity) {
                        $types = $types_opportunity;
                    } else {
                        $types = "";
                    } ?>
                    <div class="col-sm-2">
                        <select name="types" id="types" class="form-control form-control-sm">
                            <option value="">Select Types</option>
                            <?php foreach ($opportunity_type as $opp_type) { ?>
                                <option value="<?php echo $opp_type->types; ?>" <?php echo ($types == $opp_type->types) ? "selected" : ""; ?>> <?php echo $opp_type->types; ?> </option>
                            <?php } ?>
                        </select>
                    </div>
                    <?php if ($status_opportunity) {
                        $opportunity_status = $status_opportunity;
                    } else {
                        $opportunity_status = "";
                    } ?>
                    <div class="col-sm-2">
                        <select name="opportunity_status" id="opportunity_status" class="form-control form-control-sm">
                            <option value="">Select Status</option>
                            <?php foreach ($opportunity_statuses as $opp_status) { ?>
                                <option value="<?php echo $opp_status->opportunity_status; ?>" <?php echo ($opportunity_status == $opp_status->opportunity_status) ? "selected" : ""; ?>> <?php echo $opp_status->opportunity_status; ?> </option>
                            <?php } ?>
                        </select>
                    </div>
                    <?php if ($created_by) {
                        $created_by = $created_by;
                    } else {
                        $created_by = "";
                    } ?>
                    <div class="col-sm-2">
                        <select name="created_by" id="created_by" class="form-control form-control-sm">
                            <option value="">Select Created By</option>
                            <?php foreach ($created_by_name as $cr_name) { ?>
                                <option value="<?php echo $cr_name->uidnr_admin; ?>" <?php echo ($created_by == $cr_name->uidnr_admin) ? "selected" : ""; ?>> <?php echo $cr_name->created_by; ?> </option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="col-sm-2 text-right">
                        <form class="form-inline" action="<?= base_url('opportunity/') ?>" method="POST">
                            <input type="hidden" class="txt_csrfname" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
                            <input name="search" class="form-control form-control-sm search_field" type="text" placeholder="Search" aria-label="Search" value="<?php echo ($search != NULL) ? $search : ''; ?>">
                        </form>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-1 mt-4 text-left">
                        <?php
                        if (exist_val('Opportunity/add_opp_details', $this->session->userdata('permission'))) {
                        ?>
                            <!-- added by Millan on 22-02-2021 -->
                            <a href="javascript:void(0)" data-bs-toggle="modal" class="add_opportunity_details btn btn-primary btn-rounded" data-bs-target="#opportunity_details" title="Add New Opportunuty"> Add New</a>
                        <?php
                        } ?>
                    </div>
                    <div class="col-sm-1 mt-4">
                        <?php
                        if (exist_val('Opportunity/opportunity_data', $this->session->userdata('permission'))) {
                        ?>
                            <!-- added by Millan on 22-02-2021 -->
                            <a href="<?php echo base_url('customer_management/Opportunity/opportunity_data'); ?>" title="Export to Excel"> <img src="<?php echo base_url('assets/images/excel-export.png') ?>" alt="Export to Excel" width="30px"> </a>
                        <?php
                        } ?>
                    </div>

                    <div class="col-sm-1  mt-4">
                        <label for="">START DATE</label>
                    </div>

                    <div class="col-sm-3  mt-4">
                        <input type="date" name="from_date" class="form-control form-control-sm from_date" placeholder="TO DATE" value="<?php echo ($from_date) ? $from_date : '' ?>">
                    </div>

                    <div class="col-sm-1  mt-4">
                        <label for="">END DATE</label>
                    </div>
                    <div class="col-sm-3  mt-4">
                        <input type="date" name="to_date" class="form-control form-control-sm to_date" placeholder="END DATE" value="<?php echo ($to_date) ? $to_date : '' ?>">
                    </div>


                    <div class="col-sm-2 text-right mt-4">
                        <button onclick="search()" class="btn btn-primary ml-3 btn-sm"> <i class="fa fa-search" aria-hidden="true"></i> Search </button>
                        <a href="<?php echo base_url('opportunity') ?>" class="btn btn-sm btn-success ml-3"> <i class="fa fa-eraser"></i> Clear </a>
                    </div>
                </div>
            </div> <br>
            <!-- table for listing  -->
            <div class="table-responsive table-sm">
                <table id="roleTable" class="display table" width="100%">
                    <thead>
                        <tr>

                            <?php if ($search) {
                                $search = $search;
                            } else {
                                $search = "NULL";
                            }


                            if ($from_date != "") {
                                $from_date = $from_date;
                            } else {
                                $from_date = "NULL";
                            }
                            if ($to_date != "") {
                                $to_date = $to_date;
                            } else {
                                $to_date = "NULL";
                            }
                            ?>
                            <th scope="col">SN</th>
                            <th scope="col"><a href="<?php echo base_url('customer_management/Opportunity/index' . '/' . $type_customer . '/' . $name_opportunity . '/' . $types_opportunity . '/' . $status_opportunity . '/' . $created_by . '/' . $from_date . '/' . $to_date . '/' .  $search . '/' . 'opp.opportunity_name' . '/' . $order) ?>">Opportunity Name</a></th>

                            <th scope="col"><a href="<?php echo base_url('customer_management/Opportunity/index' . '/' . $type_customer . '/' . $name_opportunity . '/' . $types_opportunity . '/' . $status_opportunity . '/' . $created_by . '/' . $from_date . '/' . $to_date . '/' .  $search . '/' . 'opp.opportunity_customer_type' . '/' . $order) ?>">Customer Type</a></th>

                            <th scope="col"><a href="<?php echo base_url('customer_management/Opportunity/index' . '/' . $type_customer . '/' . $name_opportunity . '/' . $types_opportunity . '/' . $status_opportunity . '/' . $created_by . '/' . $from_date . '/' . $to_date . '/' .  $search . '/' . 'opp.types' . '/' . $order) ?>">Types</a></th>

                            <th scope="col"><a href="<?php echo base_url('customer_management/Opportunity/index' . '/' . $type_customer . '/' . $name_opportunity . '/' . $types_opportunity . '/' . $status_opportunity . '/' . $created_by . '/' . $from_date . '/' . $to_date . '/' . $search . '/' . 'opp.opportunity_value' . '/' . $order) ?>">Value</a></th>

                            <th scope="col"><a href="<?php echo base_url('customer_management/Opportunity/index' . '/' . $type_customer . '/' . $name_opportunity . '/' . $types_opportunity . '/' . $status_opportunity . '/' . $created_by . '/' . $from_date . '/' . $to_date . '/' .  $search . '/' . 'opp.estimated_closure_date' . '/' . $order) ?>">Estimated Closing Date</a></th>

                            <th scop="col"><a href="<?php echo base_url('customer_management/Opportunity/index' . '/' . $type_customer . '/' . $name_opportunity . '/' . $types_opportunity . '/' . $status_opportunity . '/' . $created_by . '/' . $from_date . '/' . $to_date . '/' .  $search . '/' . 'opp.opportunity_status' . '/' . $order) ?>"> Status </a> </th>

                            <th scope="col"> <a href="<?php echo base_url('customer_management/Opportunity/index' . '/' . $type_customer . '/' . $name_opportunity . '/' . $types_opportunity . '/' . $status_opportunity . '/' . $created_by . '/' . $from_date . '/' . $to_date . '/' .  $search . '/' . 'ap.admin_fname' . '/' . $order) ?>"> Created By </a> </th>

                            <th scope="col"> <a href="<?php echo base_url('customer_management/Opportunity/index' . '/' . $type_customer . '/' . $name_opportunity . '/' . $types_opportunity . '/' . $status_opportunity . '/' . $created_by . '/' . $from_date . '/' . $to_date . '/' .  $search . '/' . 'opp.created_on' . '/' . $order) ?>"> Created On </a> </th>

                            <?php
                            if ((exist_val('Opportunity/fetch_opp_details', $this->session->userdata('permission'))) || (exist_val('Opportunity/won_mark_values_update', $this->session->userdata('permission'))) || (exist_val('Opportunity/loss_mark_update', $this->session->userdata('permission')))) { ?>
                                <!-- added by Millan on 22-02-2021 -->
                                <th scope="col"> Action </th>
                            <?php
                            } ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($result) {
                            if (empty($this->uri->segment(14)))
                                $i = 1;
                            else
                                $i = $this->uri->segment(14) + 1;
                            foreach ($result as $row) {
                        ?>
                                <tr>
                                    <td><?php echo $i; ?></td>
                                    <td title="<?php echo $row->opportunity_name; ?>"><?php echo $row->opportunity_name; ?></td>
                                    <td title="<?php echo $row->opportunity_customer_type; ?>"><?php echo $row->opportunity_customer_type; ?></td>
                                    <td title="<?php echo $row->types; ?>"><?php echo $row->types; ?></td>
                                    <td title="<?php echo $row->opportunity_value; ?>"><?php echo $row->opportunity_value; ?></td>
                                    <td title="<?php echo $row->estimated_closure_date; ?>"><?php echo $row->estimated_closure_date; ?></td>
                                    <td title="<?php echo $row->opportunity_status; ?>"><?php echo $row->opportunity_status; ?></td>
                                    <td title="<?php echo $row->created_by; ?>"><?php echo $row->created_by; ?></td>
                                    <td title="<?php echo $row->created_on; ?>"><?php echo $row->created_on; ?></td>
                                    <td>
                                        <?php
                                        if ((exist_val('Opportunity/fetch_opp_details', $this->session->userdata('permission')))) { ?>
                                            <!-- added by Millan on 22-02-2021 -->
                                            <?php if (($row->opportunity_status == 'Open') || ($row->opportunity_status == 'Won')) { ?>
                                                <a href="javascript:void(0)" data-bs-toggle="modal" class="edit_opportunity" data-bs-target="#opportunity_edit" title="Edit Opportunity" data-id="<?= $row->opportunity_id; ?>"><img src="<?php echo base_url('assets/images/mem_edit.png') ?>" alt="Edit Opportunity"></a>
                                        <?php }
                                        } ?>
                                        <?php
                                        if ((exist_val('Opportunity/won_mark_values_update', $this->session->userdata('permission')))) { ?>
                                            <!-- added by Millan on 22-02-2021 -->
                                            <!-- added by Prashant on 08-10-2021 -->
                                            <?php if (($row->opportunity_status == 'Open') || ($row->opportunity_status == 'Lost')) { ?>
                                                <a href="javascript:void(0)" data-bs-toggle="modal" class="won_mark" data-bs-target="#won_mark_fields" title="Mark as Won" data-id="<?= $row->opportunity_id; ?>"><img src="<?php echo base_url('assets/images/accept.png') ?>" alt="Mark as Won"></a>
                                        <?php }
                                        } ?>
                                        <?php
                                        if ((exist_val('Opportunity/loss_mark_update', $this->session->userdata('permission')))) { ?>
                                            <!-- added by Millan on 22-02-2021 -->
                                            <!-- added by Prashant on 08-10-2021 -->
                                            <?php if (($row->opportunity_status == 'Open') || ($row->opportunity_status == 'Won')) { ?>
                                                <a href="javascript:void(0)" data-bs-toggle="modal" class="loss_mark" data-bs-target="#loss_mark_field" title="Mark as Lost" data-id="<?= $row->opportunity_id; ?>"><img src="<?php echo base_url('assets/images/action_stop.gif') ?>" alt="Mark as Lost"></a>
                                        <?php }
                                        } ?>
                                        <?php if (exist_val('Opportunity/fetch_communication_details', $this->session->userdata('permission'))) { ?>
                                            <!-- added by Prashant on 08-10-2021 -->
                                            <a href="javascript:void(0)" data-bs-toggle="modal" class="view_communication_details" data-bs-target="#communication_details_view" title="View Communication" data-id="<?php echo $row->opportunity_id; ?>"><img src="<?php echo base_url('assets/images/communication-view.png'); ?>" style="width:15px; height:15px" alt="View Communication"></a>
                                        <?php } ?>
                                        <?php
                                        if (exist_val('Opportunity/get_user_log_data', $this->session->userdata('permission'))) { ?>
                                            <!-- added by Millan on 22-02-2021 -->
                                            <button type="button" class="btn btn-sm user_log_btn" data-bs-toggle="modal" data-bs-target=".user_log_windows" title="User Log" data-id="<?= $row->opportunity_id; ?>"><img src="<?php echo base_url('assets/images/log-view.png') ?>" alt="User Log"></button>
                                        <?php
                                        } ?>
                                    </td>
                            <?php $i++;
                            }
                        } ?>
                                </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="container-fluid">
            <div class="text-left">
                <?php echo $links ?>
                <?php if ($result && count($result) > 0) {
                    echo "<span class='text-dark font-weight-bold'>" . $result_count . "</span>";
                } else {
                    echo "<h5 class='text-center font-weight-bold'> NO RECORD FOUND  </h5>";
                } ?>
            </div>
        </div>
    </main>
</section>

<!-- modal for mark as won added by millan -->
<div class="modal fade" id="won_mark_fields" tabindex="-1" role="dialog" aria-labelledby="wmLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content modal-sm">
            <div class="modal-header">
                <h5 class="modal-title" id="wmLabel">Mark as Won</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" id="won_mark_value" action="<?php echo base_url('mark_won_update'); ?>">
                <div class="modal-body">
                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                    <input type="hidden" name="opportunity_id" id="opportunity_id_won" value="<?php echo $row->opportunity_id; ?>">
                    <div class="form-group">
                        <label for="closure_value">Closure Value:</label>
                        <input type="text" name="closure_value" class="form-control validate form-control-sm" placeholder="Enter Closure Value" value="">
                        <label for="closure_note">Reason:</label>
                        <input type="text" name="closure_note" class="form-control validate form-control-sm" placeholder="Please Provide Reason" value="">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary submit_details"> Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- modal for mark as won -->

<!-- modal for mark as loss added by millan -->
<div class="modal fade" id="loss_mark_field" tabindex="-1" role="dialog" aria-labelledby="lmLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content modal-sm">
            <div class="modal-header">
                <h5 class="modal-title" id="lmLabel">Mark as Loss</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" id="loss_mark_value" action="<?php echo base_url('mark_loss_update'); ?>">
                <div class="modal-body">
                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                    <input type="hidden" name="opportunity_id" id="opportunity_id_loss" value="<?php echo $row->opportunity_id; ?>">
                    <div class="form-group">
                        <label for="closure_note">Reason:</label>
                        <input type="text" name="closure_note" id="closure_note" class="form-control validate form-control-sm" placeholder="Please Provide Reason" value="">
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
<!-- modal for mark as loss -->

<!-- modal for add new opportunity -->
<div class="modal fade" id="opportunity_details" tabindex="-1" role="dialog" aria-labelledby="opp_dataLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content modal-lg">
            <div class="modal-header">
                <h5 class="modal-title" id="opp_dataLabel">Add Opportunity Details</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" id="opp_fillData" action="<?php echo base_url('Opportunity/add_opp_details'); ?>">
                <div class="modal-body">
                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                    <div class="form-group text-left">
                        <div class="row">
                            <div class="col-sm-6">
                                <label for="opportunity_customer_type" class="text-dark font-weight-bold"> Select Customer Type </label>
                                <select name="opportunity_customer_type" class="form-control form-control-sm opportunity_customer_type">
                                    <option value="">Select Customer Type</option>
                                    <option value="Factory">Factory</option>
                                    <option value="Buyer">Buyer</option>
                                    <option value="Agent">Agent</option>
                                    <option value="Thirdparty">Thirdparty</option>
                                </select>
                            </div>
                            <div class="col-sm-6">
                                <label for="cust_name">Customer Name:</label>
                                <select name="opportunity_customer_id" class="form-control form-control-sm validate opportunity_customer_id">
                                    <option class="text-dark" value="">Select Customer Name</option>
                                </select>
                            </div>
                            <div class="col-sm-6">
                                <label for="opportunity_name">Opportunity Name:</label>
                                <input type="text" name="opportunity_name" class="form-control form-control-sm opportunity_name" placeholder="Enter Opportunity Name" value="">
                            </div>
                            <div class="col-sm-6">
                                <label for="types">Type:</label>
                                <select name="types" class="form-control form-control-sm validate types">
                                    <option value="" selected disabled>Select</option>
                                    <option value="Testing">Testing</option>
                                    <option value="Analytical">Analytical</option>
                                    <option value="Operations">Operations</option>
                                    <option value="Calibration">Calibration</option>
                                    <option value="Manpower">Manpower</option>
                                    <option value="Materials">Materials</option>
                                    <option value="Inspections">Inspections</option>
                                    <option value="Training">Training</option>
                                </select>
                            </div>
                            <div class="col-sm-6">
                                <label for="opportunity_value">Opportunity Value:</label>
                                <input type="text" name="opportunity_value" class="form-control form-control-sm opportunity_value" placeholder="Enter Opportunity Value" value="">
                            </div>
                            <div class="col-sm-6">
                                <label for="opp_currency">Currency:</label>
                                <select name="currency_id" class="form-control form-control-sm opp_currency">
                                    <option value="">Select Currency</option>
                                    <?php foreach ($opp_currency as $curr) { ?>
                                        <option value="<?php echo $curr->currency_id; ?>"> <?php echo $curr->currency_name; ?> </option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-sm-6">
                                <label for="estimated_closure_date">Estd. Closure Date:</label>
                                <input type="date" name="estimated_closure_date" class="form-control form-control-sm estimated_closure_date" value="">
                            </div>
                            <div class="col-sm-6">
                                <label for="opportunity_contact_id">Contact:</label>
                                <select name="opportunity_contact_id" class="form-control form-control-sm validate opportunity_contact_id">
                                    <option class="text-dark" value="">Select Contact</option>
                                </select>
                            </div>
                            <div class="col-sm-6">
                                <label for="">Assigned To:</label>
                                <select name="op_assigned_to" id="" class="form-control form-control-sm op_assigned_to">
                                    <option class="text-dark" value="">Select Assigned To</option>
                                </select>
                            </div>
                            <!-- added by millan on 14-10-2021 -->
                            <div class="col-sm-6">
                                <label for="quote_ref_no">Quote Reference No:</label>
                                <input type="text" name="quote_ref_no" class="form-control form-control-sm quote_ref_no" placeholder="Enter Quote Reference No." value="">
                                <!-- <select name="quote_ref_no" class="form-control form-control-sm quote_ref_no">
                                    <option value="">Select Quote Reference No</option>
                                    <?php //  foreach ($opp_quote_no as $op_qn) { ?>
                                        <option value="<?php // echo $op_qn->quote_id; ?>"> <?php // echo $op_qn->reference_no; ?> </option>
                                    <?php // } ?>
                                </select> -->
                            </div>
                            <!-- added by millan on 14-10-2021 -->
                            <div class="col-sm-12">
                                <label for="description">Description:</label>
                                <textarea name="description" cols="100" rows="5" class="form-control form-control-sm description"></textarea>
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
<!-- modal for add new opportunity -->

<!-- modal for edit opportunity -->
<div class="modal fade" id="opportunity_edit" tabindex="-1" role="dialog" aria-labelledby="opp_editLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content modal-lg">
            <div class="modal-header">
                <h5 class="modal-title" id="opp_editLabel">Edit Opportunity</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" id="opp_editData" action="<?php echo base_url('Opportunity/edit_opp_details'); ?>">
                <div class="modal-body">
                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                    <input type="hidden" id="opportunity_id" name="opportunity_id" value="">
                    <div class="form-group text-left">
                        <div class="row">
                            <div class="col-sm-6">
                                <label for="opportunity_customer_type" class="text-dark font-weight-bold"> Select Customer Type </label>
                                <select name="opportunity_customer_type" class="form-control form-control-sm opportunity_customer_type_edit">
                                    <option value="">Select Customer Type</option>
                                    <option value="Factory">Factory</option>
                                    <option value="Buyer">Buyer</option>
                                    <option value="Agent">Agent</option>
                                    <option value="Thirdparty">Thirdparty</option>
                                </select>
                            </div>
                            <div class="col-sm-6">
                                <label for="cust_name">Customer Name:</label>
                                <select name="opportunity_customer_id" class="form-control form-control-sm validate opportunity_customer_id_edit">
                                    <option class="text-dark" value="">Select Customer Name</option>
                                </select>
                            </div>
                            <div class="col-sm-6">
                                <label for="opportunity_name">Opportunity Name:</label>
                                <input type="text" name="opportunity_name" class="form-control form-control-sm opportunity_name_edit" placeholder="Enter Opportunity Name" value="">
                            </div>
                            <div class="col-sm-6">
                                <label for="types">Type:</label>
                                <select name="types" class="form-control form-control-sm validate types_edit">
                                    <option value="" selected disabled>Select</option>
                                    <option value="Testing">Testing</option>
                                    <option value="Analytical">Analytical</option>
                                    <option value="Operations">Operations</option>
                                    <option value="Calibration">Calibration</option>
                                    <option value="Manpower">Manpower</option>
                                    <option value="Materials">Materials</option>
                                    <option value="Inspections">Inspections</option>
                                    <option value="Training">Training</option>
                                </select>
                            </div>
                            <div class="col-sm-6">
                                <label for="opportunity_value">Opportunity Value:</label>
                                <input type="text" name="opportunity_value" class="form-control form-control-sm opportunity_value_edit" placeholder="Enter Opportunity Value" value="">
                            </div>
                            <div class="col-sm-6">
                                <label for="opp_currency">Currency:</label>
                                <select name="currency_id" class="form-control form-control-sm opp_currency_edit">
                                    <option value="">Select Currency</option>
                                    <?php foreach ($opp_currency as $curr) { ?>
                                        <option value="<?php echo $curr->currency_id; ?>"> <?php echo $curr->currency_name; ?> </option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-sm-6">
                                <label for="estimated_closure_date">Estd. Closure Date:</label>
                                <input type="date" name="estimated_closure_date" class="form-control form-control-sm estimated_closure_date_edit" value="">
                            </div>
                            <div class="col-sm-6">
                                <label for="opportunity_contact_id">Contact:</label>
                                <select name="opportunity_contact_id" class="form-control form-control-sm validate opportunity_contact_id_edit">
                                    <option class="text-dark" value="">Select Contact</option>
                                </select>
                            </div>
                            <div class="col-sm-6">
                                <label for="">Assigned To:</label>
                                <select name="op_assigned_to" id="" class="form-control form-control-sm op_assigned_to">
                                    <option class="text-dark" value="">Select Assigned To</option>
                                </select>
                            </div>
                            <!-- added by millan on 14-10-2021 -->
                            <div class="col-sm-6">
                                <label for="quote_ref_no">Quote Reference No:</label>
                                <input type="text" name="quote_ref_no" class="form-control form-control-sm quote_ref_no_edit" placeholder="Enter Quoter Reference No" value="">
                                <!-- <select name="quote_ref_no" class="form-control form-control-sm quote_ref_no">
                                    <option value="">Select Quote Reference No</option>
                                    <?php // foreach ($opp_quote_no as $op_qn) { ?>
                                        <option value="<?php // echo $op_qn->quote_id; ?>"> <?php // echo $op_qn->reference_no; ?> </option>
                                    <?php // } ?>
                                </select> -->
                            </div>
                            <!-- added by millan on 14-10-2021 -->
                            <div class="col-sm-12">
                                <label for="description">Description:</label>
                                <textarea name="description" cols="100" rows="5" class="form-control form-control-sm description_edit"></textarea>
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
<!-- modal for edit opportunity -->

<!-- modal for view communication added by prashant on 08-10-2021 -->
<div class="modal fade" id="communication_details_view" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content modal-lg" style="min-width: 900px; margin:0 auto">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">View Communication Details</h4>
                <input type="hidden" name="id" id="id_cls1">
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body communication_view">
                <div id="view_details"> </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- modal for view communication added by prashant on 08-10-2021 -->

<!-- modal for user log -->
<div class="modal user_log_windows" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content modal-lg">
            <div class="modal-header">
                <h5 class="modal-title"><b>OPPORTUNITY LOG</b></h5>
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
                    <tbody id="opportunity_log"></tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- end -->

<script type="text/javascript">
    function search() {
        var base_url = "<?php echo base_url('customer_management/Opportunity/index'); ?>"
        var customer_type = $('#opportunity_customer_type').val();
        var opportunity_name = encodeURIComponent($('#opportunity_name').val());
        var types = $('#types').val();
        var opportunity_status = $('#opportunity_status').val();
        var created_by = $('#created_by').val();
        var search = $('.search_field').val();
        if (customer_type) {
            base_url = base_url + '/' + btoa(customer_type);
        } else {
            base_url = base_url + '/' + 'NULL';
        }
        if (opportunity_name) {
            base_url = base_url + '/' + btoa(opportunity_name);
        } else {
            base_url = base_url + '/' + 'NULL';
        }
        if (types) {
            base_url = base_url + '/' + btoa(types);
        } else {
            base_url = base_url + '/' + 'NULL';
        }
        if (opportunity_status) {
            base_url = base_url + '/' + btoa(opportunity_status);
        } else {
            base_url = base_url + '/' + 'NULL';
        }
        if (created_by) {
            base_url = base_url + '/' + btoa(created_by);
        } else {
            base_url = base_url + '/' + 'NULL';
        }

        from_date = $('.from_date').val();
        if (from_date != '') {
            base_url = base_url + '/' + btoa(from_date);
        } else {
            base_url = base_url + '/NULL';
        }
        to_date = $('.to_date').val();
        if (to_date != '') {
            base_url = base_url + '/' + btoa(to_date);
        } else {
            base_url = base_url + '/NULL';
        }
        if (search) {
            base_url = base_url + '/' + btoa(search);
        } else {
            base_url = base_url + '/' + 'NULL';
        }
        location.href = base_url;
    }
</script>

<script>
    $(function() {

        $('.from_date').on('change', function() {
            if ($('.to_date').val() == "") {

                $.notify('Please select end date!', 'error');
            }
        })

    });


    /* added by millan on 22-02-2021 */
    $(document).on('click', '.won_mark ', function() {
        var opportunity_id = $(this).data('id');
        var _tokken = $('meta[name="_tokken"]').attr('value');
        $.ajax({
            url: "<?php echo base_url('customer_management/Opportunity/fetch_opp_details'); ?>",
            type: "post",
            data: {
                opportunity_id: opportunity_id,
                _tokken: _tokken
            },
            success: function(result) {
                var data = $.parseJSON(result);
                $('#won_mark_fields #opportunity_id_won').val(data.opportunity_id);
            }
        });
        return false;
    });

    $(document).on('click', '.loss_mark ', function() {
        var opportunity_id = $(this).data('id');
        var _tokken = $('meta[name="_tokken"]').attr('value');
        $.ajax({
            url: "<?php echo base_url('customer_management/Opportunity/fetch_opp_details'); ?>",
            type: "post",
            data: {
                opportunity_id: opportunity_id,
                _tokken: _tokken
            },
            success: function(result) {
                var data = $.parseJSON(result);
                $('#loss_mark_field #opportunity_id_loss').val(data.opportunity_id);
            }
        });
        return false;
    });
    /* added by millan on 22-02-2021 */

    $(document).on("submit", "#won_mark_value", function(e) {
        e.preventDefault();
        var input_data = new FormData(this);
        $.ajax({
            url: "<?php echo base_url('mark_won_update'); ?>",
            data: input_data,
            type: 'post',
            processData: false,
            contentType: false,
            success: function(result) {
                $('.form_errors').remove();
                var data = $.parseJSON(result);
                if (data.status > 0) {
                    location.reload();
                } else {
                    $.each(data.error, function(i, v) {
                        $('#won_mark_value input[name=' + i + ']').after('<span class="text-danger form_errors">' + v + '</span>');
                    });
                }
            }
        });
    });

    $(document).on("submit", "#loss_mark_value", function(e) {
        e.preventDefault();
        var input_data = new FormData(this);
        $.ajax({
            url: "<?php echo base_url('mark_loss_update'); ?>",
            data: input_data,
            type: 'post',
            processData: false,
            contentType: false,
            success: function(result) {
                $('.form_errors').remove();
                var data = $.parseJSON(result);
                if (data.status > 0) {
                    location.reload();
                } else {
                    $.each(data.error, function(i, v) {
                        $('#loss_mark_value input[name=' + i + ']').after('<span class="form_errors text-danger">' + v + '</span>');
                    });
                }
            }
        });
    });
</script>

<script>
    $(document).ready(function() {
        var style = {
            'margin': '0 auto'
        };
        $('.modal-content').css(style);
        load_op_assigned_to();
        $(document).on("change", ".opportunity_customer_type", function() {
            var customer_type = $(this).val();
            get_customer_name(customer_type);
        });

        $(document).on("change", ".opportunity_customer_type_edit", function() {
            var customer_type = $(this).val();
            get_customer_name_edit(customer_type);
        });

        function get_customer_name(customer_type) {
            var _tokken = $('meta[name="_tokken"]').attr('value');
            $.ajax({
                url: "<?php echo base_url('customer_management/Opportunity/extract_cust_name'); ?>",
                data: {
                    customer_type: customer_type,
                    _tokken: _tokken
                },
                type: 'post',
                success: function(result) {
                    $('.opportunity_customer_id').empty().append(' <option class="text-dark" value="">Select Customer Name</option>');
                    var data = $.parseJSON(result);
                    if (data) {
                        $.each(data, function(i, v) {
                            $('.opportunity_customer_id').append(' <option class="text-dark" value="' + v.customer_id + '">' + v.customer_name + '</option>');
                        });
                    }
                }
            });
            return false;
        }

        function get_customer_name_edit(customer_type, customer_id = null) {
            var _tokken = $('meta[name="_tokken"]').attr('value');
            $.ajax({
                url: "<?php echo base_url('customer_management/Opportunity/extract_cust_name'); ?>",
                type: "post",
                data: {
                    customer_type: customer_type,
                    _tokken: _tokken
                },
                success: function(result) {
                    var data = $.parseJSON(result);
                    if (data) {
                        $.each(data, function(i, v) {
                            if (customer_id) {
                                if (customer_id == v.customer_id) {
                                    $('#opportunity_edit .opportunity_customer_id_edit').append('<option selected value="' + v.customer_id + '">' + v.customer_name + '</option>');
                                } else {
                                    $('#opportunity_edit .opportunity_customer_id_edit').append('<option value="' + v.customer_id + '">' + v.customer_name + '</option>');
                                }
                            } else {
                                $('#opportunity_edit .opportunity_customer_id_edit').append('<option value="' + v.customer_id + '">' + v.customer_name + '</option>');
                            }
                        });
                    }
                }
            })
            return false;
        }

        $(document).on("change", ".opportunity_customer_id", function() {
            var custmer_id = $(this).val();
            var _tokken = $('meta[name="_tokken"]').attr('value');
            $.ajax({
                url: "<?php echo base_url('customer_management/Opportunity/extract_cont_name'); ?>",
                data: {
                    custmer_id: custmer_id,
                    _tokken: _tokken
                },
                type: 'post',
                success: function(result) {
                    $('.opportunity_contact_id').empty().append(' <option class="text-dark" value="">Select Contact Name</option>');
                    var data = $.parseJSON(result);
                    if (data) {
                        $.each(data, function(i, v) {
                            $('.opportunity_contact_id').append(' <option class="text-dark" value="' + v.contact_id + '">' + v.contact_name + '</option>');
                        });
                    }
                }
            });
            return false;
        });

        function load_op_assigned_to() {
            $.ajax({
                url: "<?php echo base_url('customer_management/Opportunity/load_assign_to') ?>",
                method: "GET",
                success: function(data) {
                    var data = $.parseJSON(data);
                    $('.op_assigned_to').html("");
                    if (data) {
                        var option = "";
                        option = "<option value='' selected disabled>Select</option>";
                        $('.op_assigned_to').append(option);
                        $.each(data, function(index, value) {
                            option = "<option value='" + value.user_id + "'>" + value.user_name + "</option>";
                            $('.op_assigned_to').append(option);
                        })
                    }
                }
            })
            return false;
        }

        $(document).on("submit", "#opp_fillData", function(e) {
            e.preventDefault();
            var getdata = new FormData(this);
            $.ajax({
                url: "<?php echo base_url('customer_management/Opportunity/add_opp_details'); ?>",
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
                            $('#opp_fillData input[name=' + i + ']').after('<span class="text-danger form_errors">' + v + '</span>');
                            $('#opp_fillData select[name=' + i + ']').after('<span class="text-danger form_errors">' + v + '</span>');
                            $('#opp_fillData textarea[name=' + i + ']').after('<span class="text-danger form_errors">' + v + '</span>');
                        });
                    }
                }
            });
            return false;
        });

        $(document).on('click', '.add_opportunity_details', function() {
            var now = new Date();
            var day = ("0" + now.getDate()).slice(-2);
            var month = ("0" + (now.getMonth() + 1)).slice(-2);
            var today = now.getFullYear() + "-" + (month) + "-" + (day);
            $('.estimated_closure_date').val(today);
        });

        $(document).on('click', '.edit_opportunity', function() {
            var opportunity_id = $(this).data('id');
            var _tokken = $('meta[name="_tokken"]').attr('value');
            $.ajax({
                url: "<?php echo base_url('customer_management/Opportunity/fetch_opp_details'); ?>",
                type: "post",
                data: {
                    opportunity_id: opportunity_id,
                    _tokken: _tokken
                },
                success: function(result) {
                    var data = $.parseJSON(result);
                    get_customer_name_edit(data.opportunity_customer_type, data.opportunity_customer_id);
                    contact_name(data.opportunity_customer_id, data.opportunity_contact_id);
                    $('#opportunity_edit .opportunity_customer_type_edit').val(data.opportunity_customer_type);
                    $('#opportunity_edit #opportunity_id').val(data.opportunity_id);
                    $('#opportunity_edit .opportunity_name_edit').val(data.opportunity_name);
                    $('#opportunity_edit .types_edit').val(data.types);
                    $('#opportunity_edit .opportunity_value_edit').val(data.opportunity_value);
                    $('#opportunity_edit .opp_currency_edit').val(data.currency_id);
                    $('#opportunity_edit .estimated_closure_date_edit').val(data.estimated_closure_date);
                    $('#opportunity_edit .op_assigned_to').val(data.op_assigned_to);
                    $('#opportunity_edit .description_edit').val(data.description);
                    $('#opportunity_edit .quote_ref_no_edit').val(data.opp_quote_ref_no); // added by millan on 14-10-2021
                    // $('#opportunity_edit .quote_ref_no').html($('<option></option>').attr({'value': data.quote_id, selected:'selected'}).text(data.reference_no)); // added by millan on 14-10-2021
                }
            });
            return false;
        });

        function cust_name(cust_type, cust_id) {
            var _tokken = $('meta[name="_tokken"]').attr('value');
            $.ajax({
                url: "<?php echo base_url('customer_management/Opportunity/extract_cust_name'); ?>",
                type: 'post',
                data: {
                    customer_type: cust_type,
                    _tokken: _tokken
                },
                success: function(result) {
                    var data = $.parseJSON(result);
                    if (data) {
                        $.each(data, function(i, v) {
                            if (cust_id) {
                                if (cust_id == v.customer_id) {
                                    $('#opportunity_edit .opportunity_customer_id_edit').append('<option selected value="' + v.customer_id + '">' + v.customer_name + '</option>');
                                } else {
                                    $('#opportunity_edit .opportunity_customer_id_edit').append('<option value="' + v.customer_id + '">' + v.customer_name + '</option>');
                                }
                            } else {
                                $('#opportunity_edit .opportunity_customer_id_edit').append('<option value="' + v.customer_id + '">' + v.customer_name + '</option>');
                            }
                        });
                    }
                }
            })
            return false;
        }

        function contact_name(cust_id, cont_id) {
            var _tokken = $('meta[name="_tokken"]').attr('value');
            $.ajax({
                url: "<?php echo base_url('customer_management/Opportunity/extract_cont_name'); ?>",
                type: 'post',
                data: {
                    custmer_id: cust_id,
                    _tokken: _tokken
                },
                success: function(result) {
                    var data = $.parseJSON(result);
                    if (data) {
                        $.each(data, function(index, value) {
                            if (cont_id) {
                                if (cont_id == value.contact_id) {
                                    $('#opportunity_edit .opportunity_contact_id_edit').append('<option selected value="' + value.contact_id + '">' + value.contact_name + '</option>');
                                } else {
                                    $('#opportunity_edit .opportunity_contact_id_edit').append('<option value="' + value.contact_id + '">' + value.contact_name + '</option>');
                                }
                            } else {
                                $('#opportunity_edit .opportunity_contact_id_edit').append('<option value="' + value.contact_id + '">' + value.contact_name + '</option>');
                            }
                        });
                    }
                }
            })
            return false;
        }

        $(document).on("submit", "#opp_editData", function(e) {
            e.preventDefault();
            var getdata = new FormData(this);
            $.ajax({
                url: "<?php echo base_url('customer_management/Opportunity/edit_opp_details'); ?>",
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
                            $('#opp_editData input[name=' + i + ']').after('<span class="text-danger form_errors">' + v + '</span>');
                            $('#opp_editData select[name=' + i + ']').after('<span class="text-danger form_errors">' + v + '</span>');
                            $('#opp_editData textarea[name=' + i + ']').after('<span class="text-danger form_errors">' + v + '</span>');
                        });
                    }
                }
            });
            return false;
        });


        // modal for view communication by Prashant Rai on 08-10-2021 
        $(document).on("click", ".view_communication_details", function() {
            var opportunity_id = $(this).data('id');
            var _tokken = $('meta[name="_tokken"]').attr('value');
            $.ajax({
                url: "<?php echo base_url(); ?>customer_management/Opportunity/fetch_communication_details",
                data: {
                    "opportunity_id": opportunity_id,
                    _tokken: _tokken
                },
                type: 'post',
                success: function(result) {
                    var data = $.parseJSON(result);
                    if (data) {
                        var html = '<table class="table table-bordered table-stripped"><thead><tr><th>Customer Type</th><th>Customer</th><th>Contact</th><th> Subject</th><th> Date of Comm</th><th>Comm Mode</th><th>Comm Medium</th><th>Connected To</th><th>Created By</th><th>Created On </th></tr></thead><tbody>';
                        $.each(data, function(key, value) {
                            html += '<tr><td>' + value.customer_type + '</td><td>' + value.customer_name + '</td><td>' + value.contact_name + '</td><td>' + value.subject + '</td><td>' + value.date_of_communication + '</td><td>' + value.communication_mode + '</td><td>' + value.medium + '</td><td>' + value.connected_to + '</td><td>' + value.created_by + '</td><td>' + value.created_on + '</td></tr>';
                        });
                        html += '</tbody></table>'
                        $('.communication_view').empty().append(html);
                    } else {
                        var html = '<table class="table table-bordered table-stripped"><thead><tr><th>Customer Type</th><th>Customer</th><th>Contact</th><th> Subject</th><th> Date of Comm</th><th>Comm Mode</th><th>Comm Medium</th><th>Connected To</th><th>Created By</th><th>Created On </th></tr></thead><tbody>';
                        html += '<tr><th scope="col" colspan="7"><h5 class="text-center text-primary"> NO RECORD FOUND </h5></th>';
                        html += '</tbody></table>'
                        $('.communication_view').empty().append(html);
                    }
                }
            });
        });

        // });

        // function get_user_log_data(opportunity_id) {
        //     const _tokken = $('meta[name="_tokken"]').attr("value");
        //     $.ajax({
        //         url: "<?php echo base_url('customer_management/Opportunity/get_user_log_data') ?>",
        //         method: "POST",
        //         data: {
        //             opportunity_id: opportunity_id,
        //             _tokken: _tokken
        //         },
        //         success: function(response) {
        //             var data = $.parseJSON(response);
        //             $('.user_table tbody').html("");
        //             if (data) {
        //                 var serial = 1;
        //                 $.each(data, function(index, value) {
        //                     row = "<tr>";
        //                     row += "<td>" + serial + "</td>";
        //                     row += "<td>Insert By " + value.created_by + "</td>";
        //                     row += "<td>" + value.date + "</td>";
        //                     row += "</tr>";
        //                     $('.user_table tbody').append(row);
        //                     serial++;
        //                 });
        //             } else {
        //                 row = "<tr>";
        //                 row += "<td colspan=3>";
        //                 row += "<h6>NO RECORD FOUND!</h6>";
        //                 row += "</td>";
        //                 row += "</tr>";
        //                 $('.user_table tbody').append(row);
        //             }
        //         }
        //     });
        //     return false;
        // }
        // end
    });
</script>
<script>
    $(document).ready(function() {
        const url = $('body').data('url');
        const _tokken = $('meta[name="_tokken"]').attr('value');
        // Ajax call to get log
        $('.user_log_btn').click(function() {
            $('#opportunity_log').empty();
            var opportunity_id = $(this).data('id');
            $.ajax({
                type: 'post',
                url: url + 'customer_management/Opportunity/get_user_log_data',
                data: {
                    _tokken: _tokken,
                    opportunity_id: opportunity_id
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
                        var taken_at = v.taken_at;
                        value += '<tr>';
                        value += '<td>' + sno + '</td>';
                        value += '<td>' + operation + '</td>';
                        value += '<td>' + action_message + '</td>';
                        value += '<td>' + taken_by + '</td>';
                        value += '<td>' + taken_at + '</td>';
                        value += '</tr>';

                    });
                    $('#opportunity_log').append(value);
                }
            });
        });
        // ajax call to get log ends here

        /* added by millan on 14-10-2021 */
        // function formatRepo(repo) {
        //     if (repo.loading) {
        //         return repo.text;
        //     }
        //     var $container = $(
        //         "<div class='select2-result-repository clearfix'>" +
        //         "<div class='select2-result-repository__title'></div>" +
        //         "</div>"
        //     );

        //     $container.find(".select2-result-repository__title").text(repo.name);
        //     return $container;
        // }

        // function formatRepoSelection(repo) {
        //     return repo.full_name || repo.text;
        // }

        // Get Quote Ref No start here
        // $('.quote_ref_no').select2({
        //     allowClear: true,
        //     ajax: {
        //         url: "<?php // echo base_url('customer_management/Opportunity/get_quote_reference') ?>",
        //         dataType: 'json',
        //         data: function(params) {
        //             return {
        //                 key: params.term, // search reference no
        //             };
        //         },
        //         processResults: function(response) {

        //             return {
        //                 results: response
        //             };
        //         },
        //         cache: true
        //     },
        //     placeholder: 'Search by Quote Ref No',
        //     minimumInputLength: 0,
        //     templateResult: formatRepo,
        //     templateSelection: formatRepoSelection
        // });
        /* added by millan on 14-10-2021 ends */ 
    });
</script>