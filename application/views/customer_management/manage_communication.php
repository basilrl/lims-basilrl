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
                <h2 class="text-info"><i class="fa fa-tasks"></i> Communication</h2>
            </div>
            <hr>
            <div class="container-fluid mt-4">
                <div class="row">
                    <?php if ($type_customer) {
                        $customer_type = $type_customer;
                    } else {
                        $customer_type = "";
                    } ?>
                    <div class="col-sm-2">
                        <select name="customer_type" id="customer_type" class="form-control form-control-sm">
                            <option value="">Select Customer Type</option>
                            <option value="Factory" <?php echo ($customer_type == 'Factory') ? "selected" : ""; ?>>Factory</option>
                            <option value="Buyer" <?php echo ($customer_type == 'Buyer') ? "selected" : ""; ?>>Buyer</option>
                            <option value="Agent" <?php echo ($customer_type == 'Agent') ? "selected" : ""; ?>>Agent</option>
                            <option value="Thirdparty" <?php echo ($customer_type == 'Thirdparty') ? "selected" : ""; ?>>Thirdparty</option>
                        </select>
                    </div>
                    <?php if ($id_customer) {
                        $customer_id = $id_customer;
                    } else {
                        $customer_id = 0;
                    } ?>
                    <div class="col-sm-2">
                        <select name="customer_id" id="customer_id" class="form-control form-control-sm">
                            <option value="">Select Customer Name</option>
                            <?php foreach ($cust_name as $name) { ?>
                                <option value="<?php echo $name->customer_id; ?>" <?php echo ($customer_id == $name->customer_id) ? "selected" : ""; ?>> <?php echo $name->customer_name; ?> </option>
                            <?php } ?>
                        </select>
                    </div>
                    <?php if ($name_contact) {
                        $contact_id = $name_contact;
                    } else {
                        $contact_id = 0;
                    } ?>
                    <div class="col-sm-2">
                        <select name="contact_name" id="contact_name" class="form-control form-control-sm">
                            <option value="">Select Contact Name</option>
                            <?php foreach ($contact_name as $cname) { ?>
                                <option value="<?php echo $cname->contact_id; ?>" <?php echo ($contact_id == $cname->contact_id) ? "selected" : ""; ?>> <?php echo $cname->contact_name; ?> </option>
                            <?php } ?>
                        </select>
                    </div>
                    <?php if ($connect_to) {
                        $connected_to = $connect_to;
                        
                    } else {
                        $connect_to = "";
                    } ?>
                    <div class="col-sm-2">
                        <select name="connected_to" id="connected_to" class="form-control form-control-sm">
                            <option value="">Select Connected To</option>
                            <option value="Lead" <?php echo ($connect_to == "Lead") ? "selected" : "" ?>>Lead</option>
                            <option value="Opportunity" <?php echo ($connect_to == "Opportunity") ? "selected" : "" ?>>Opportunity</option>
                        </select>
                    </div>
                    <?php if ($opportunity_name) {
                        $opportunity_id = $opportunity_name;
                    } else {
                        $opportunity_id = 0;
                    } ?>
                    <div class="col-sm-2">
                        <select name="opportunity" id="opportunity" class="form-control form-control-sm">
                            <option value="">Select Opportunity</option>
                        </select>
                    </div>
                    <?php if ($created_by) {
                        $uidnr_admin = $created_by;
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
                    <div class="col-sm-2">
                        <form class="form-inline" action="<?= base_url('communication/') ?>" method="POST">
                            <input type="hidden" class="txt_csrfname" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
                            <input name="search" class="form-control form-control-sm search_field" type="text" placeholder="Search" aria-label="Search" value="<?php echo ($search != NULL) ? $search : ''; ?>">
                        </form>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-1 mt-4 text-left">
                        <?php
                        if (exist_val('Communication/add_comm_details', $this->session->userdata('permission'))) {
                        ?>
                            <!-- added by Millan on 22-02-2021 -->
                            <a href="javascript:void(0)" data-bs-toggle="modal" class="add_communication_details btn btn-primary btn-rounded" data-bs-target="#communication_details" title="Add New Communication"> Add New</a>
                        <?php
                        }
                        ?>
                    </div>
                    <div class="col-sm-1 mt-4 ">
                        <?php
                        if (exist_val('Communication/communication_data', $this->session->userdata('permission'))) { ?>
                            <!-- added by Millan on 22-02-2021 -->
                            <a href="<?php echo base_url('customer_management/Communication/communication_data') ?>"><img src="<?php echo base_url('assets/images/excel-export.png') ?>" alt="Export to Excel" title="Export to Excel" width="30px"></a>
                            <!-- <a href="<?php // echo base_url('customer_management/Communication/communication_data'); 
                                            ?>" title="Export to Excel"> <img src="<?php echo base_url('assets/images/excel-export.png') ?>" alt="Export to Excel" height="50px" width="50px"> </a> -->
                        <?php
                        }
                        ?>
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




                    <div class="text-right mt-4 col-sm-2">
                        <button onclick="search()" class="btn btn-primary ml-3 btn-sm"> <i class="fa fa-search" aria-hidden="true"></i> Search </button>
                        <a href="<?php echo base_url('communication') ?>" class="btn btn-sm btn-success ml-3"> <i class="fa fa-eraser"></i> Clear </a>
                    </div>
                </div>
            </div> <br>
            <!-- table for listing  -->
            <div class="table-responsive table-sm">
                <table id="roleTable" class="display table" width="100%">
                    <thead>
                        <tr>

                            <?php
                            if ($search) {
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
                            <th scope="col"><a href="<?php echo base_url('customer_management/Communication/index' . '/' . $type_customer . '/' . $id_customer . '/' .
                                                            $name_contact . '/' . (($connect_to) ? $connect_to : 'NULL') . '/'. $opportunity_name.'/' . $created_by . '/' . $from_date . '/' . $to_date . '/' . $search . '/' . 'cust.customer_type' . '/' . $order) ?>">Customer Type</a></th>
                            <th scope="col"><a href="<?php echo base_url('customer_management/Communication/index' . '/' . $type_customer . '/' . $id_customer . '/' .
                                                            $name_contact . '/' . (($connect_to) ? $connect_to : 'NULL') . '/' .  $opportunity_name.'/'. $created_by . '/' . $from_date . '/' . $to_date . '/' . $search . '/' . 'cust.customer_name' . '/' . $order) ?>">Customer </a></th>
                            <th scope="col"><a href="<?php echo base_url('customer_management/Communication/index' . '/' . $type_customer . '/' . $id_customer . '/' .
                                                            $name_contact . '/' . (($connect_to) ? $connect_to : 'NULL') . '/' . $opportunity_name.'/'. $created_by . '/' . $from_date . '/' . $to_date . '/' . $search . '/' . 'contact.contact_name' . '/' . $order) ?>">Contact </a></th>
                            <th scope="col"><a href="<?php echo base_url('customer_management/Communication/index' . '/' . $type_customer . '/' . $id_customer . '/' .
                                                            $name_contact . '/' . (($connect_to) ? $connect_to : 'NULL') . '/' . $opportunity_name.'/'. $created_by . '/' . $from_date . '/' . $to_date . '/' . $search . '/' . 'comm.subject' . '/' . $order) ?>">Subject </a></th>
                            <th scope="col"><a href="<?php echo base_url('customer_management/Communication/index' . '/' . $type_customer . '/' . $id_customer . '/' .
                                                            $name_contact . '/' . (($connect_to) ? $connect_to : 'NULL') . '/' . $opportunity_name.'/'. $created_by . '/' . $from_date . '/' . $to_date . '/' . $search . '/' . 'comm.date_of_communication' . '/' . $order) ?>">Date of Comm </a></th>
                            <th scope="col"><a href="<?php echo base_url('customer_management/Communication/index' . '/' . $type_customer . '/' . $id_customer . '/' .
                                                            $name_contact . '/' . (($connect_to) ? $connect_to : 'NULL') . '/' . $opportunity_name.'/'. $created_by . '/' . $from_date . '/' . $to_date . '/' . $search . '/' . 'comm.communication_mode' . '/' . $order) ?>">Comm Mode</a></th>
                            <th scope="col"><a href="<?php echo base_url('customer_management/Communication/index' . '/' . $type_customer . '/' . $id_customer . '/' .
                                                            $name_contact . '/' . (($connect_to) ? $connect_to : 'NULL') . '/' . $opportunity_name.'/'. $created_by . '/' . $from_date . '/' . $to_date . '/' . $search . '/' . 'comm.medium' . '/' . $order) ?>">Comm Medium</a></th>
                            <th scope="col"><a href="<?php echo base_url('customer_management/Communication/index' . '/' . $type_customer . '/' . $id_customer . '/' .
                                                            $name_contact . '/' . (($connect_to) ? $connect_to : 'NULL') . '/' . $opportunity_name.'/'. $created_by . '/' . $from_date . '/' . $to_date . '/' . $search . '/' . 'comm.connected_to' . '/' . $order) ?>">Connected To</a></th>
                            <th scope="col"><a href="<?php echo base_url('customer_management/Communication/index' . '/' . $type_customer . '/' . $id_customer . '/' .
                                                            $name_contact . '/' . (($connect_to) ? $connect_to : 'NULL') . '/' . $opportunity_name.'/'. $created_by . '/' . $from_date . '/' . $to_date . '/' . $search . '/' . 'ap.admin_fname' . '/' . $order) ?>">Created By</a></th>
                            <th scope="col"><a href="<?php echo base_url('customer_management/Communication/index' . '/' . $type_customer . '/' . $id_customer . '/' .
                                                            $name_contact . '/' . (($connect_to) ? $connect_to : 'NULL') . '/' . $opportunity_name.'/'. $created_by . '/' . $from_date . '/' . $to_date . '/' . $search . '/' . 'comm.created_on' . '/' . $order) ?>">Created On</a></th>
                            <?php
                            if (exist_val('Communication/fetch_comm_details', $this->session->userdata('permission'))) {
                            ?>
                                <!-- added by Millan on 22-02-2021 -->
                                <th scope="col"> Action </th>
                            <?php
                            }
                            ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($result) {
                            if (empty($this->uri->segment(15)))
                                $i = 1;
                            else
                                $i = $this->uri->segment(15) + 1;
                            foreach ($result as $row) {
                        ?>
                                <tr>
                                    <td><?php echo $i; ?></td>
                                    <td><?php echo $row->customer_type; ?></td>
                                    <td><?php echo $row->customer_name; ?></td>
                                    <td><?php echo $row->contact_name; ?></td>
                                    <td><?php echo $row->subject; ?></td>
                                    <td><?php echo $row->date_of_communication; ?></td>
                                    <td><?php echo $row->communication_mode; ?></td>
                                    <td><?php echo $row->medium; ?></td>
                                    <td><?php echo $row->connected_to; ?></td>
                                    <td><?php echo $row->created_by; ?></td>
                                    <td><?php echo $row->created_on; ?></td>
                                    <td>
                                        <?php
                                        if (exist_val('Communication/fetch_comm_details', $this->session->userdata('permission'))) {
                                        ?>
                                            <!-- added by Millan on 22-02-2021 -->
                                            <a href="javascript:void(0)" data-bs-toggle="modal" class="edit_communication" data-bs-target="#communication_edit" title="Edit Communication" data-id="<?= $row->communication_id; ?>"><img src="<?php echo base_url('assets/images/mem_edit.png') ?>" alt="Edit Communication"></a>
                                        <?php
                                        }
                                        ?>
                                        <?php if (exist_val('Communication/add_fetch_communication_data', $this->session->userdata('permission'))) { ?>
                                            <!-- added by prashant on 06-10-2021---- -->
                                            <a href="javascript:void(0)" data-bs-toggle="modal" class="add_communication" data-bs-target="#communication_add" title="Add Communication" data-id="<?php echo $row->communication_id; ?>"><img src="<?php echo base_url('assets/images/add.png'); ?>" alt="Add Communication"></a>
                                        <?php } ?>

                                        <?php if (exist_val('Communication/fetch_opportunity_details', $this->session->userdata('permission'))) { ?>
                                            <!-- added by prashant on 06-10-2021---- -->
                                            <a href="javascript:void(0)" data-bs-toggle="modal" class="view_opportunity_details" data-bs-target="#opportunity_details_view" title="View Opportunity" data-id="<?php echo $row->communication_id; ?>"><img src="<?php echo base_url('assets/images/opportunity_view.png'); ?>" style="width:15px; height:15px" alt="View Communication"></a>
                                        <?php } ?>

                                        <?php
                                        if (exist_val('Communication/get_user_log_data', $this->session->userdata('permission'))) {
                                        ?>
                                            <!-- added by Millan on 01-04-2021 -->
                                            <button type="button" class="btn btn-sm user_log_btn_communication" data-bs-toggle="modal" data-bs-target=".user_log_windows" title="User Log" data-id="<?= $row->communication_id; ?>"><img src="<?php echo base_url('assets/images/log-view.png') ?>" alt="User Log"></button>
                                        <?php
                                        }
                                        ?>
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
                <?php if ($result && count($result) > 0) { ?>
                    <?php echo "<span class='text-dark font-weight-bold'>" . $result_count . "</span>"; ?>
                <?php } else { ?>
                    <?php echo "<h5 class='text-center font-weight-bold'> NO RECORD FOUND  </h5>"; ?>
                <?php } ?>
            </div>
        </div>
    </main>
</section>

<!-- modal for add new communication -->
<div class="modal fade" id="communication_details" tabindex="-1" role="dialog" aria-labelledby="comm_dataLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content modal-lg" style="text-align: right">
            <div class="modal-header">
                <h5 class="modal-title" id="comm_dataLabel">Add Communication Details</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" id="comm_fillData" action="<?php echo base_url('Communication/add_comm_details'); ?>">
                <div class="modal-body">
                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                    <div class="form-group text-left">
                        <div class="row">
                            <div class="col-sm-6">
                                <label for="customer_type" class="text-dark font-weight-bold"> Select Customer Type </label>
                                <select name="customer_type" class="form-control form-control-sm customer_type">
                                    <option value="">Select Customer Type</option>
                                    <option value="Factory">Factory</option>
                                    <option value="Buyer">Buyer</option>
                                    <option value="Agent">Agent</option>
                                    <option value="Thirdparty">Thirdparty</option>
                                </select>
                            </div>
                            <div class="col-sm-6">
                                <label for="comm_communications_customer_id">Customer Name:</label>
                                <select name="comm_communications_customer_id" class="form-control form-control-sm validate comm_communications_customer_id">
                                    <option class="text-dark" value="">Select Customer Name</option>
                                </select>
                            </div>
                            <div class="col-sm-6">
                                <label for="communication_mode">Mode:</label>
                                <select name="communication_mode" class="form-control form-control-sm validate communication_mode">
                                    <option value="" selected disabled>Select</option>
                                    <option value="Outgoing">Outgoing</option>
                                    <option value="Incoming">Incoming</option>
                                </select>
                            </div>
                            <div class="col-sm-6">
                                <label for="comm_communications_contact_id">Contact:</label>
                                <select name="comm_communications_contact_id" class="form-control form-control-sm validate comm_communications_contact_id">
                                    <option class="text-dark" value="">Select Contact</option>
                                </select>
                            </div>
                            <div class="col-sm-6">
                                <label for="date_of_communication">Date of Communication:</label>
                                <input type="date" name="date_of_communication" class="form-control form-control-sm date_of_communication" value="">
                            </div>
                            <div class="col-sm-6">
                                <label for="follow_up_date">Follow Up Date:</label>
                                <input type="date" name="follow_up_date" class="form-control form-control-sm follow_up_date" value="">
                            </div>
                            <div class="col-sm-6">
                                <label for="medium" class="text-dark font-weight-bold"> Select Communication Medium </label>
                                <select name="medium" class="form-control form-control-sm medium">
                                    <option value="">Select Communication Medium</option>
                                    <?php foreach ($comm_mediums as $cmed) { ?>
                                        <option value="<?php echo $cmed->medium; ?>"> <?php echo $cmed->medium; ?> </option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-sm-12 other_med" style="display: none">
                                <label for="other_medium">Others Medium:</label>
                                <input type="text" name="other_medium" class="form-control form-control-sm others_medium" value="">
                            </div>
                            <div class="col-sm-6">
                                <label for="connected_to">Connected To:</label>
                                <select name="connected_to" class="form-control form-control-sm validate add_connected_to">
                                    <option value="" selected disabled>Select</option>
                                    <option value="Lead">Lead</option>
                                    <option value="Opportunity">Opportunity</option>
                                </select>
                            </div>
                            <div class="col-sm-6">
                                <label for="connected_to">Opportunity:</label>
                                <select name="opportunity" class="form-control form-control-sm add_opportunity">
                                    <option value="">Select Opportunity</option>
                                    <option value=""></option>
                                </select>
                            </div>
                            <div class="col-sm-12">
                                <label for="subject">Subject:</label>
                                <input type="text" class="form-control form-control-sm" name="subject" value="" placeholder="Enter Subject">
                                <label for="note">Note:</label>
                                <textarea name="note" cols="30" rows="3" class="form-control form-control-sm note"></textarea>
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
<!-- modal for add new communication ends -->

<!-- modal for edit communication -->
<div class="modal fade" id="communication_edit" tabindex="-1" role="dialog" aria-labelledby="comm_editLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content modal-lg" style="margin:0 auto">
            <div class="modal-header">
                <h5 class="modal-title" id="comm_editLabel">Edit Communication Details</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" id="commData_edit" action="<?php echo base_url('Communication/edit_comm_details'); ?>">
                <div class="modal-body">
                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                    <input type="hidden" id="communication_id" name="communication_id" value="">
                    <div class="form-group text-left">
                        <div class="row">
                            <div class="col-sm-6">
                                <label for="customer_type" class="text-dark font-weight-bold"> Select Customer Type </label>
                                <select name="customer_type" class="form-control form-control-sm customer_type">
                                    <option value="">Select Customer Type</option>
                                    <option value="Factory">Factory</option>
                                    <option value="Buyer">Buyer</option>
                                    <option value="Agent">Agent</option>
                                    <option value="Thirdparty">Thirdparty</option>
                                </select>
                            </div>
                            <div class="col-sm-6">
                                <label for="comm_communications_customer_id">Customer Name:</label>
                                <select name="comm_communications_customer_id" class="form-control form-control-sm validate comm_communications_customer_id">
                                    <option class="text-dark" value="">Select Customer Name</option>
                                </select>
                            </div>
                            <div class="col-sm-6">
                                <label for="communication_mode">Mode:</label>
                                <select name="communication_mode" class="form-control form-control-sm validate communication_mode">
                                    <option value="" selected disabled>Select</option>
                                    <option value="Outgoing">Outgoing</option>
                                    <option value="Incoming">Incoming</option>
                                </select>
                            </div>
                            <div class="col-sm-6">
                                <label for="comm_communications_contact_id">Contact:</label>
                                <select name="comm_communications_contact_id" class="form-control form-control-sm validate comm_communications_contact_id">
                                    <option class="text-dark" value="">Select Contact</option>
                                </select>
                            </div>
                            <div class="col-sm-6">
                                <label for="date_of_communication">Date of Communication:</label>
                                <input type="date" name="date_of_communication" class="form-control form-control-sm date_of_communication" value="">
                            </div>
                            <div class="col-sm-6">
                                <label for="follow_up_date">Follow Up Date:</label>
                                <input type="date" name="follow_up_date" class="form-control form-control-sm follow_up_date" value="">
                            </div>
                            <div class="col-sm-6">
                                <label for="medium" class="text-dark font-weight-bold"> Select Communication Medium </label>
                                <select name="medium" class="form-control form-control-sm medium">
                                    <option value="">Select Communication Medium</option>
                                    <?php foreach ($comm_mediums as $cmed) { ?>
                                        <option value="<?php echo $cmed->medium; ?>"> <?php echo $cmed->medium; ?> </option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-sm-12 other_med" style="display: none">
                                <label for="other_med">Others Medium:</label>
                                <input type="text" name="other_med" class="form-control form-control-sm others_medium" value="">
                            </div>
                            <div class="col-sm-6">
                                <label for="connected_to">Connected To:</label>
                                <select name="connected_to" id="connected_to" class="form-control form-control-sm validate connected_to">
                                    <option value="" selected disabled>Select</option>
                                    <option value="Lead">Lead</option>
                                    <option value="Opportunity">Opportunity</option>
                                </select>
                            </div>
                            <div class="col-sm-6 opportunity">
                                <label for="opportunity">Opportunity:</label>
                                <select name="opportunity" id="opportunity" class="form-control form-control-sm add_opportunity">
                                    <option value="">Select Opportunity</option>
                                </select>
                            </div>
                            <div class="col-sm-12">
                                <label for="subject">Subject:</label>
                                <input type="text" class="form-control form-control-sm subject_edit" name="subject" value="" placeholder="Enter Subject">
                                <label for="note">Note:</label>
                                <textarea name="note" cols="30" rows="3" class="form-control form-control-sm note"></textarea>
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
<!-- modal for edit communication ends -->

<!-- modal for user log -->
<div class="modal user_log_windows" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content" style="max-height: 500px;">
            <div class="modal-header">
                <h5 class="modal-title"><b>COMMUNICATION LOG</b></h5>
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
                    <tbody id="communication_log"></tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- modal for view opportunity added by prashant on 12-10-2021 -->
<div class="modal fade" id="opportunity_details_view" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content modal-lg"  style="margin:0 auto">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">View Opportunity Details</h4>
                <input type="hidden" name="id" id="id_cls1">
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body opportunity_view">
                <div id="view_details"> </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- modal for view communication added by prashant on 27-09-2021 -->
<!-- modal for user log ends -->

<!-- modal for add new communication by prashant on 28-09-2021 -->
<div class="modal fade" id="communication_add" tabindex="-1" role="dialog" aria-labelledby="comm_dataLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content modal-lg" style="margin:0 auto">
            <div class="modal-header">
                <h5 class="modal-title" id="comm_dataLabel">Add Communication Details</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" id="comm_addData" action="<?php echo base_url('Communication/add_communication'); ?>">
                <div class="modal-body">
                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                    <input type="hidden" id="add_communication_id" name="communication_id" value="">
                    <div class="form-group text-left">
                        <div class="row">
                            <div class="col-sm-6">
                                <label for="customer_type" class="text-dark font-weight-bold"> Select Customer Type </label>
                                <select name="customer_type" class="form-control form-control-sm add_customer_type">
                                    <option value="">Select Customer Type</option>
                                </select>
                            </div>
                            <div class="col-sm-6">
                                <label for="comm_communications_customer_id">Customer Name:</label>
                                <select name="comm_communications_customer_id" class="form-control form-control-sm validate comm_communications_customer_id">
                                    <option class="text-dark" value="">Select Customer Name</option>
                                </select>
                            </div>
                            <div class="col-sm-6">
                                <label for="communication_mode">Mode:</label>
                                <select name="communication_mode" class="form-control form-control-sm validate add_communication_mode">
                                    <option value="" selected disabled>Select</option>
                                </select>
                            </div>
                            <div class="col-sm-6">
                                <label for="comm_communications_contact_id">Contact:</label>
                                <select name="comm_communications_contact_id" class="form-control form-control-sm validate comm_communications_contact_id">
                                    <option class="text-dark" value="">Select Contact</option>
                                </select>
                            </div>
                            <div class="col-sm-6">
                                <label for="date_of_communication">Date of Communication:</label>
                                <input type="date" name="date_of_communication" class="form-control form-control-sm add_date_of_communication" value="">
                            </div>
                            <div class="col-sm-6">
                                <label for="follow_up_date">Follow Up Date:</label>
                                <input type="date" name="follow_up_date" class="form-control form-control-sm add_follow_up_date" value="">
                            </div>
                            <div class="col-sm-6">
                                <label for="medium" class="text-dark font-weight-bold"> Select Communication Medium </label>
                                <select name="medium" class="form-control form-control-sm add_medium">
                                    <option value="">Select Communication Medium</option>
                                </select>
                            </div>
                            <div class="col-sm-12 other_med" style="display: none">
                                <label for="other_medium">Others Medium:</label>
                                <input type="text" name="other_medium" class="form-control form-control-sm add_others_medium" value="">
                            </div>
                            <div class="col-sm-6">
                                <label for="connected_to">Connected To:</label>
                                <select name="connected_to" class="form-control form-control-sm validate added_connected_to">
                                    <option value="" >Select</option>
                                </select>
                            </div>
                            <div class="col-sm-6">
                                <label for="connected_to">Opportunity:</label>
                                <select name="opportunity" class="form-control form-control-sm added_opportunity">
                                    <option value="">Select Opportunity</option>
                                    
                                </select>
                            </div>
                            <div class="col-sm-12">
                                <label for="subject">Subject:</label>
                                <input type="text" class="form-control form-control-sm add_subject" name="subject" value="" placeholder="Enter Subject" readonly>
                                <label for="note">Note:</label>
                                <textarea name="note" cols="30" rows="3" class="form-control form-control-sm add_note"></textarea>
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
<!-- modal for add new communication ends -->

<script type="text/javascript">
    function search() {
        var base_url = "<?php echo base_url('customer_management/Communication/index'); ?>"
        var customer_type = $('#customer_type').val();
        var customer_id = $('#customer_id').val();
        var contact_name = $('#contact_name').val();
        var connect_to = $('#connected_to').val();
        var opportunity = $('#opportunity').val();
        var created_by = $('#created_by').val();
        var search = $('.search_field').val();
        if (customer_type) {
            base_url = base_url + '/' + btoa(customer_type);
        } else {
            base_url = base_url + '/' + 'NULL';
        }
        if (customer_id) {
            base_url = base_url + '/' + btoa(customer_id);
        } else {
            base_url = base_url + '/' + 'NULL';
        }
        if (contact_name) {
            base_url = base_url + '/' + btoa(contact_name);
        } else {
            base_url = base_url + '/' + 'NULL';
        }
        if (connect_to) {
            base_url = base_url + '/' + btoa(connect_to);
        } else {
            base_url = base_url + '/' + 'NULL';
        }
        if (opportunity) {
            base_url = base_url + '/' + btoa(opportunity);
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
    $(document).ready(function() {

        $('.from_date').on('change', function() {
            if ($('.to_date').val() == "") {

                $.notify('Please select end date!', 'error');
            }
        })

        $(document).on("change", ".customer_type", function() {
            var cust_type = $(this).val();
            var _tokken = $('meta[name="_tokken"]').attr('value');
            $.ajax({
                url: "<?php echo base_url('customer_management/Communication/extract_cust_name'); ?>",
                data: {
                    customer_type: cust_type,
                    _tokken: _tokken
                },
                type: 'post',
                success: function(result) {
                    $('.comm_communications_customer_id').empty().append(' <option class="text-dark" value="">Select Customer Name</option>');
                    var data = $.parseJSON(result);
                    if (data) {
                        $.each(data, function(i, v) {
                            $('.comm_communications_customer_id').append(' <option class="text-dark" value="' + v.customer_id + '">' + v.customer_name + '</option>');
                        });
                    }
                }
            });
        });

        $(document).on("change", ".comm_communications_customer_id", function() {
            var customer_id = $(this).val();
            var _tokken = $('meta[name="_tokken"]').attr('value');
            $.ajax({
                url: "<?php echo base_url('customer_management/Communication/extract_cont_name'); ?>",
                data: {
                    customer_id: customer_id,
                    _tokken: _tokken
                },
                type: 'post',
                success: function(result) {
                    $('.comm_communications_contact_id').empty().append(' <option class="text-dark" value="">Select Contact</option>');
                    var data = $.parseJSON(result);
                    if (data) {
                        $.each(data, function(i, v) {
                            $('.comm_communications_contact_id').append(' <option class="text-dark" value="' + v.contact_id + '">' + v.contact_name + '</option>');
                        });
                    }
                }
            });
        });

        $(document).on('change', '.medium', function() {
            var comm_med = $(this).val();
            if (comm_med == "Others") {
                $('.other_med').show();
            } else {
                $('.other_med').hide();
            }
        });

        $(document).on('click', '.add_communication_details ', function() {
            var now = new Date();
            var day = ("0" + now.getDate()).slice(-2);
            var month = ("0" + (now.getMonth() + 1)).slice(-2);
            var today = now.getFullYear() + "-" + (month) + "-" + (day);
            $('.date_of_communication').val(today);
            $('.follow_up_date').val(today);
        });

        $(document).on("submit", "#comm_fillData", function(e) {
            e.preventDefault();
            var getdata = new FormData(this);
            $.ajax({
                url: "<?php echo base_url('customer_management/Communication/add_comm_details'); ?>",
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
                            $('#comm_fillData input[name=' + i + ']').after('<span class="text-danger form_errors">' + v + '</span>');
                            $('#comm_fillData select[name=' + i + ']').after('<span class="text-danger form_errors">' + v + '</span>');
                            $('#comm_fillData textarea[name=' + i + ']').after('<span class="text-danger form_errors">' + v + '</span>');
                        });
                    }
                }
            });
        });

        $(document).on('click', '.edit_communication', function() {
            var communication_id = $(this).data('id');
            var _tokken = $('meta[name="_tokken"]').attr('value');
            $.ajax({
                url: "<?php echo base_url('customer_management/Communication/fetch_comm_details'); ?>",
                type: "post",
                data: {
                    communication_id: communication_id,
                    _tokken: _tokken
                },
                success: function(result) {
                    var data = $.parseJSON(result);
                    cust_name(data.customer_type, data.comm_communications_customer_id);
                    contact_name(data.comm_communications_customer_id, data.comm_communications_contact_id);
                    $('#communication_edit #communication_id').val(data.communication_id);
                    // $('#communication_edit .customer_type').val(data.customer_type);
                    $('.customer_type option[value=' + data.customer_type + ']').attr('selected', 'selected');
                    $('#communication_edit .communication_mode').val(data.communication_mode);
                    $('#communication_edit .date_of_communication').val(data.date_of_communication);
                    $('#communication_edit .follow_up_date').val(data.follow_up_date);
                    $('#communication_edit .medium').val(data.medium);
                    // $('#communication_edit .add_opportunity').val(data.opportunity_name);
                    $('#communication_edit .connected_to').val(data.connected_to);
                    // $('#communication_edit .add_opportunity').val(data.comm_communications_opportunity_id);
                    // $('#communication_edit .add_opportunity').text(data.opportunity_name);
                    // $('.add_opportunity option[value=' + data.comm_communications_opportunity_id + ']').attr('selected', 'selected').text(data.opportunity_name);
                    $('.add_opportunity').append($('<option></option>').attr('value', data.comm_communications_opportunity_id).text(data.opportunity_name).attr('selected','selected'));
                    $('#communication_edit .subject_edit').val(data.subject);
                    $('#communication_edit .note').val(data.note);
                    $('#communication_edit .others_medium').val(data.other_med);
                }
            });
        });

        function cust_name(cust_type, cust_id) {
            var _tokken = $('meta[name="_tokken"]').attr('value');
            $.ajax({
                url: "<?php echo base_url('customer_management/Communication/extract_cust_name'); ?>",
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
                                    $('#communication_edit .comm_communications_customer_id').append('<option selected value="' + v.customer_id + '">' + v.customer_name + '</option>');
                                } else {
                                    $('#communication_edit .comm_communications_customer_id').append('<option value="' + v.customer_id + '">' + v.customer_name + '</option>');
                                }
                            } else {
                                $('#communication_edit .comm_communications_customer_id').append('<option value="' + v.customer_id + '">' + v.customer_name + '</option>');
                            }
                        });
                    }
                }
            })
        }

        function contact_name(cust_id, contact_id) {
            var _tokken = $('meta[name="_tokken"]').attr('value');
            $.ajax({
                url: "<?php echo base_url('customer_management/Communication/extract_cont_name'); ?>",
                type: 'post',
                data: {
                    customer_id: cust_id,
                    _tokken: _tokken
                },
                success: function(result) {
                    var data = $.parseJSON(result);
                    if (data) {
                        $.each(data, function(i, v) {
                            if (contact_id) {
                                if (contact_id == v.contact_id) {
                                    $('#communication_edit .comm_communications_contact_id').append('<option selected value="' + v.contact_id + '">' + v.contact_name + '</option>');
                                } else {
                                    $('#communication_edit .comm_communications_contact_id').append('<option value="' + v.contact_id + '">' + v.contact_name + '</option>');
                                }
                            } else {
                                $('#communication_edit .comm_communications_contact_id').append('<option value="' + v.contact_id + '">' + v.contact_name + '</option>');
                            }
                        });
                    }
                }
            })
        }
        $(document).on('submit', '#commData_edit', function(e) {
            e.preventDefault();
            var formdata = new FormData(this);
            $.ajax({
                url: "<?php echo base_url('customer_management/Communication/edit_communication_details'); ?>",
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
                            $('#commData_edit input[name=' + i + ']').after('<span class="text-danger form_errors">' + v + '</span>');
                            $('#commData_edit select[name=' + i + ']').after('<span class="text-danger form_errors">' + v + '</span>');
                            $('#commData_edit textarea[name=' + i + ']').after('<span class="text-danger form_errors">' + v + '</span>');
                        });
                    }
                }
            });
        });


        //Modal for Added contact details form by Prashant on 06-10-2021
        $(document).on('click', '.add_communication', function() {
            var communication_id = $(this).data('id');
            var _tokken = $('meta[name="_tokken"]').attr('value');
            $.ajax({
                url: "<?php echo base_url('customer_management/Communication/fetch_comm_add_details'); ?>",
                type: "post",
                data: {
                    communication_id: communication_id,
                    _tokken: _tokken
                },
                success: function(result) {
                    var data = $.parseJSON(result);
                    fetch_add_cust_name(data.customer_type, data.comm_communications_customer_id);
                    fetch_add_contact_name(data.comm_communications_customer_id, data.comm_communications_contact_id);
                    $('#communication_add #add_communication_id').val(data.communication_id);
                    $('.add_customer_type').append('<option selected value="' + data.customer_type + '">' + data.customer_type + '</option>');
                    $('.add_communication_mode').append('<option selected value="' + data.communication_mode + '">' + data.communication_mode + '</option>');
                    $('#communication_add .add_date_of_communication').val(data.date_of_communication);
                    $('#communication_add .add_follow_up_date').val(data.follow_up_date);
                    $('.add_medium').append('<option selected value="' + data.medium + '">' + data.medium + '</option>');
                    $('.added_connected_to').append('<option selected value="' + data.connected_to + '">' + data.connected_to + '</option>');
                    $('.added_opportunity').append($('<option></option>').attr('value', data.comm_communications_opportunity_id).text(data.opportunity_name).attr('selected','selected'));
                    $('#communication_add .add_subject').val(data.subject);
                    $('#communication_add .add_note').val(data.note);
                    $('#communication_add .add_others_medium').val(data.other_med);
                }
            });
        });

        function fetch_add_cust_name(cust_type, cust_id) {
            var _tokken = $('meta[name="_tokken"]').attr('value');
            $.ajax({
                url: "<?php echo base_url('customer_management/Communication/fetch_extract_cust_name'); ?>",
                type: 'post',
                data: {
                    customer_id: cust_id,
                    customer_type: cust_type,
                    _tokken: _tokken
                },
                success: function(result) {
                    var data = $.parseJSON(result);
                    if (data) {
                        $('#communication_add .comm_communications_customer_id').append('<option selected value="' + data.customer_id + '">' + data.customer_name + '</option>');
                    }
                }
            })
        }

        function fetch_add_contact_name(cust_id, contact_id) {
            var _tokken = $('meta[name="_tokken"]').attr('value');
            $.ajax({
                url: "<?php echo base_url('customer_management/Communication/fetch_extract_cont_name'); ?>",
                type: 'post',
                data: {
                    contact_id: contact_id,
                    cust_id: cust_id,
                    _tokken: _tokken
                },
                success: function(result) {
                    var data = $.parseJSON(result);
                    if (data){
                                $('#communication_add .comm_communications_contact_id').append('<option selected value="' + data.contact_id + '">' + data.contact_name + '</option>');
                             }
                }
            })
        }

        $(document).on("submit", "#comm_addData", function(e) {
            e.preventDefault();
            var getdata = new FormData(this);
            $.ajax({
                url: "<?php echo base_url('customer_management/Communication/add_communication'); ?>",
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
                            $('#comm_fillData input[name=' + i + ']').after('<span class="text-danger form_errors">' + v + '</span>');
                            $('#comm_fillData select[name=' + i + ']').after('<span class="text-danger form_errors">' + v + '</span>');
                            $('#comm_fillData textarea[name=' + i + ']').after('<span class="text-danger form_errors">' + v + '</span>');
                        });
                    }
                }
            });
        });
        // Added by prashant on 06-10-2021---
 });
</script>

<script>
    $(document).ready(function() {
        const url = $('body').data('url');
        const _tokken = $('meta[name="_tokken"]').attr('value');
        // Ajax call to get log
        $('.user_log_btn_communication').click(function() {
            $('#communication_log').empty();
            var communication_id = $(this).data('id');
            $.ajax({
                type: 'post',
                url: url + 'customer_management/Communication/get_user_log_data',
                data: {
                    _tokken: _tokken,
                    communication_id: communication_id
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
                    $('#communication_log').append(value);
                }
            });
        });
        // ajax call to get log ends here
        //Added by prashant on 24-09-2021------------

        $(document).on('change', '#connected_to', function() {
            $('#opportunity').select2();
            $('#opportunity').empty();
            var connected_to = $('#connected_to').val();
            if (connected_to === 'Opportunity') {
                $('body').append('<div class="pageloader"></div>');
                $.ajax({
                    type: 'GET',
                    url: url + 'customer_management/Communication/opportunity_name',
                    dataType: 'json',
                    success: function(data) {
                        $('.pageloader').remove();
                        $('#opportunity').append($('<option></option>').attr('disables', 'selected').text('Select opportunity'));
                        $.each(data, function(key, value) {
                            $('#opportunity').append($('<option></option>').attr('value', value.opportunity_id).text(value.opportunity_name));
                        });
                    }
                });
            } else {
                $('#opportunity').append($('<option></option>').attr('disables', 'selected').text(''));
            }
        });

        // modal for view opportunity by Prashant Rai on 12-10-2021 
      $(document).on("click", ".view_opportunity_details", function() {
            var communication_id = $(this).data('id');
            var _tokken = $('meta[name="_tokken"]').attr('value');
            $.ajax({
                url: "<?php echo base_url(); ?>customer_management/Communication/fetch_opportunity_details",
                data: {
                    "communication_id": communication_id,
                    _tokken: _tokken
                },
                type: 'post',
                success: function(result) {
                    var data = $.parseJSON(result);
                    if (data) {
                        var html = '<table class="table table-bordered table-stripped"><thead><tr><th>Opportunity Name</th><th>Customer Type</th><th>Types</th><th>Value</th><th> Estimated Closing Date</th><th> Status</th><th>Created By</th><th>Created On </th></tr></thead><tbody>';
                        $.each(data, function(key, value) {
                            html += '<tr><td>' + value.opportunity_name + '</td><td>' + value.opportunity_customer_type + '</td><td>' + value.types + '</td><td>' + value.opportunity_value + '</td><td>' + value.estimated_closure_date + '</td><td>' + value.opportunity_status + '</td><td>' + value.created_by + '</td><td>' + value.created_on + '</td></tr>';
                        });
                        html += '</tbody></table>'
                        $('.opportunity_view').empty().append(html);
                    } else {
                        var html = '<table class="table table-bordered table-stripped"><thead><tr><th>Opportunity Name</th><th>Customer Type</th><th>Types</th><th>Value</th><th> Estimated Closing Date</th><th> Status</th><th>Created By</th><th>Created On </th></tr></thead><tbody>';
                        html += '<tr><th scope="col" colspan="7"><h5 class="text-center text-primary"> NO RECORD FOUND </h5></th>';
                        html += '</tbody></table>'
                        $('.opportunity_view').empty().append(html);
                    }
                }
            });
        });

        //Added by Prashant on 27-09-2021-------------
        $(document).on('change', '.add_connected_to', function() {
            $('.add_opportunity').select2();
            $('.add_opportunity').empty();
            var connected_to = $('.add_connected_to').val();
            if (connected_to === 'Opportunity') {
                $('body').append('<div class="pageloader"></div>');
                $.ajax({
                    type: 'GET',
                    url: url + 'customer_management/Communication/add_opportunity_name',
                    dataType: 'json',
                    success: function(data) {
                        $('.pageloader').remove();
                        $('.add_opportunity').append($('<option></option>').attr('disables', 'selected').text('Select opportunity'));
                        $.each(data, function(key, value) {
                            $('.add_opportunity').append($('<option></option>').attr('value', value.opportunity_id).text(value.opportunity_name));
                        });
                    }
                });
            } else {
                $('.add_opportunity').append($('<option></option>').attr('disables', 'selected').text(''));
            }
        });
});
</script>
