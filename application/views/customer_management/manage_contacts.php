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

        $('.status').click(function() {
            var contact_id = $(this).attr("data-id");
            var _tokken = $('meta[name="_tokken"]').attr('value');
            $.ajax({
                url: "<?php echo base_url('customer_management/Contacts/contact_status'); ?>",
                data: {
                    contact_id: contact_id,
                    _tokken: _tokken
                },
                type: 'post',
                success: function(result) {
                    var result = $.parseJSON(result);
                    if(result.status>0){
                        
                        window.location.reload();
                        $.notify(result.msg,'success');
                    }
                    else{
                        $.notify(result.msg,'error');
                    }
                }
            });
        });

    });
</script>

<section class="adjustheight">
    <main class="main">
        <div class="container-fluid">
            <div class="container text-center"><br />
                <h2 class="text-info"><i class="fa fa-tasks"></i> Contacts</h2>
            </div>
            <hr>
            <div class="container-fluid mt-4">
                <div class="row">
                    <div class="col-sm-2">
                        <?php if ($customer_type) {
                            $customer_type = $customer_type;
                        } else {
                            $customer_type = "";
                        } ?>
                        <select name="customer_type" id="customer_type" class="form-control form-control-sm" value="">
                            <option value="">Select Customer Type</option>
                            <option value="Factory" <?php echo ($customer_type == 'Factory') ? "selected" : ""; ?> >Factory</option>
                            <option value="Buyer" <?php echo ($customer_type == 'Buyer') ? "selected" : ""; ?> >Buyer</option>
                            <option value="Agent" <?php echo ($customer_type == 'Agent') ? "selected" : ""; ?> >Agent</option>
                            <option value="Thirdparty" <?php echo ($customer_type == 'Thirdparty') ? "selected" : ""; ?> >Thirdparty</option>
                        </select>
                    </div>
                    <div class="col-sm-2">
                        <?php if ($name_customer) {
                            $customer_name = $name_customer;
                        } else {
                            $name_customer = "";
                        } ?>
                        <select name="customer_name" id="customer_name" class="form-control form-control-sm">
                            <option value="">Select Customer Name</option>
                            <?php foreach ($cust_name as $name) { ?>
                                <option value="<?php echo $name->customer_name; ?>" <?php echo ($name_customer == $name->customer_name) ? "selected" : ""; ?>> <?php echo $name->customer_name; ?> </option>
                            <?php } ?>
                        </select>
                    </div>
                    <?php if ($name_contact) {
                        $contact_name = $name_contact;
                    } else {
                        $contact_name = "";
                    } ?>
                    <div class="col-sm-2">
                        <select name="contact_name" id="contact_name" class="form-control form-control-sm">
                            <option value="">Select Contact Name</option>
                            <?php foreach ($contacts_name as $cname) { ?>
                                <option value="<?php echo $cname->contact_name; ?>" <?php echo ($contact_name == $cname->contact_name) ? "selected" : ""; ?>> <?php echo $cname->contact_name; ?> </option>
                            <?php } ?>
                        </select>
                    </div>
                    <?php if ($type_contact) {
                        $type = $type_contact;
                    } else {
                        $type = "";
                    } ?>
                    <div class="col-sm-2">
                        <select name="type" id="type" class="form-control form-control-sm">
                            <option value="">Select Type</option>
                            <option value="None">None</option>
                            <option value="Technical">Technical</option>
                            <option value="Report">Report</option>
                            <option value="Invoice">Invoice</option>
                            <option value="Payment follow-up">Payment follow-up</option>
                            <option value="Alternative">Alternative</option>
                            <option value="Contract">Contract</option>
                            <option value="Invoice follow-up">Invoice follow-up</option>
                            <option value="site/sampling">site/sampling</option>
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
                    <div class="col-sm-2 text-right">
                        <select name="" id="" class="status_search form-control form-control-sm">
                                <option value="" disabled selected>Select status</option>
                                <option value="1" <?php echo ($status=='1')?'selected':""?>>Active</option>
                                <option value="0" <?php echo ($status=='0')?'selected':""?>>Deactive</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-2 mt-4 text-left">
                        <?php
                         if (exist_val('Contacts/add_contact', $this->session->userdata('permission'))) { 
                             ?>
                            <!-- added by Millan on 22-02-2021 -->
                            <a href="javascript:void(0)" data-bs-toggle="modal" class="add_contact_details btn btn-primary btn-rounded" data-bs-target="#contact_details" title="Add New Contact"> Add New</a>
                        <?php 
                    } 
                    ?>
                    </div>
                    <div class="col-sm-8  mt-4 ">
                    <form class="form-inline" action="<?= base_url('contact/') ?>" method="POST">
                            <input type="hidden" class="txt_csrfname" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
                            <input name="search" class="form-control form-control-sm search_field" type="text" placeholder="Search" aria-label="Search" style="width:480px" value="<?php echo ($search!=NULL)?$search:'';?>">
                        </form>
                    </div>
                    <div class=" col-sm-2 text-right mt-4">
                        <button onclick="search()" class="btn btn-primary ml-3 btn-sm"> <i class="fa fa-search" aria-hidden="true"></i> Search </button>
                        <a href="<?php echo base_url('contact') ?>" class="btn btn-sm btn-success ml-3"> <i class="fa fa-eraser"></i> Clear </a>
                    </div>
                </div>
            </div> <br>
            <!-- table for listing  -->
            <div class="table table-sm table-hovered">
                <table id="roleTable" class="display table" width="100%">
                    <thead>
                        <tr>
                        <?php 
                            if($customer_type){
                                $customer_type = $customer_type;
                            }
                            else{
                                $customer_type = "NULL";
                            }
                            if($name_customer){
                                $name_customer =$name_customer;
                            }
                            else{
                                $name_customer = "NULL";
                            }
                            if($created_by){
                                $created_by =$created_by;
                            }
                            else{
                                $created_by = "NULL";
                            }
                            if($search){
                                $search =$search;
                            }
                            else{
                                $search = "NULL";
                            }
                        
                            if($status){
                                $status =$status;
                            }
                            else{
                                $status = "NULL";
                            }
                        
                        
                        ?>
                            <th scope="col">SN</th>
                            <th scope="col"><a href="<?php echo base_url('customer_management/Contacts/index' . '/' . $customer_type . '/' . $name_customer . '/' .
                                                            $name_contact . '/' . (($type_contact) ? $type_contact : 'NULL') . '/' . $created_by . '/' . $search . '/' .$status.'/'. 'cust.customer_type' . '/' . $order) ?>">Customer Type</a></th>
                            <th scope="col"><a href="<?php echo base_url('customer_management/Contacts/index' . '/' . $customer_type . '/' . $name_customer . '/' .
                                                            $name_contact . '/' . (($type_contact) ? $type_contact : 'NULL') . '/' . $created_by . '/' . $search .  '/' .$status.'/'. 'cust.customer_name' . '/' . $order) ?>">Customer Name</a></th>
                            <th scope="col"><a href="<?php echo base_url('customer_management/Contacts/index' . '/' . $customer_type . '/' . $name_customer . '/' .
                                                            $name_contact . '/' . (($type_contact) ? $type_contact : 'NULL') . '/' . $created_by . '/' . $search .  '/' .$status.'/' . 'contact.contact_name' . '/' . $order) ?>">Contact Name</a></th>
                            <th scope="col"><a href="<?php echo base_url('customer_management/Contacts/index' . '/' . $customer_type . '/' . $name_customer . '/' .
                                                            $name_contact . '/' . (($type_contact) ? $type_contact : 'NULL') . '/' . $created_by . '/' . $search .  '/' .$status.'/' . 'contact.email' . '/' . $order) ?>">Email</a></th>
                            <th scope="col"><a href="<?php echo base_url('customer_management/Contacts/index' . '/' . $customer_type . '/' . $name_customer . '/' .
                                                            $name_contact . '/' . (($type_contact) ? $type_contact : 'NULL') . '/' . $created_by . '/' . $search .  '/' .$status.'/' . 'contact.mobile_no' . '/' . $order) ?>">Mobile No</a></th>
                            <th scope="col"><a href="<?php echo base_url('customer_management/Contacts/index' . '/' . $customer_type . '/' . $name_customer . '/' .
                                                            $name_contact . '/' . (($type_contact) ? $type_contact : 'NULL') . '/' . $created_by . '/' . $search .  '/' .$status.'/' . 'contact.type' . '/' . $order) ?>">Type</a></th>
                            <?php 
                            if (exist_val('Contacts/contact_status', $this->session->userdata('permission'))) {
                                 ?>
                                <!-- added by Millan on 22-02-2021 -->
                                <th scop="col"> Status </th>
                            <?php 
                        }
                         ?>
                            <th scope="col"><a href="<?php echo base_url('customer_management/Contacts/index' . '/' . $customer_type . '/' . $name_customer . '/' .
                                                            $name_contact . '/' . (($type_contact) ? $type_contact : 'NULL') . '/' . $created_by . '/' . $search . '/' .$status.'/' . 'ap.admin_fname' . '/' . $order) ?>">Created By</a></th>
                            <th scope="col"><a href="<?php echo base_url('customer_management/Contacts/index' . '/' . $customer_type . '/' . $name_customer . '/' .
                                                            $name_contact . '/' . (($type_contact) ? $type_contact : 'NULL') . '/' . $created_by . '/' . $search . '/' .$status.'/' . 'contact.created_on' . '/' . $order) ?>">Created On</a></th>
                     
                            <?php 
                            if (exist_val('Contacts/fetch_contact_data', $this->session->userdata('permission'))) {
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
                            if (empty($this->uri->segment(13)))
                                $i = 1;
                            else
                                $i = $this->uri->segment(13) + 1;
                            foreach ($result as $row) {
                        ?>
                                <tr>
                                    <td><?php echo $i; ?></td>
                                    <td><?php echo $row->customer_type; ?></td>
                                    <td><?php echo $row->customer_name; ?></td>
                                    <td><?php echo $row->contact_name; ?></td>
                                    <td><?php echo $row->email; ?></td>
                                    <td><?php echo $row->mobile_no; ?></td>
                                    <td><?php echo $row->type; ?></td>
                                    <?php 
                                    if (exist_val('Contacts/contact_status', $this->session->userdata('permission'))) { 
                                        
                                        ?>
                                        <!-- added by Millan on 22-02-2021 -->
                                        <?php if ($row->status == 1) { ?>
                                            <td><span class="btn btn-success status" id="active" data-id="<?php echo $row->contact_id; ?>">Active</span></td>
                                        <?php } else { ?>
                                            <td><span class="btn btn-danger status" id="deactive" data-id="<?php echo $row->contact_id; ?>">Deactive</span></td>
                                        <?php } ?>
                                    <?php
                                
                                }
                                 ?>
                                    <td><?php echo $row->created_by; ?></td>
                                    <td><?php echo change_time($row->created_on,$this->session->userdata('timezone')); ?></td>
                                    <td>
                                        <?php
                                         if (exist_val('Contacts/fetch_contact_data', $this->session->userdata('permission'))) {
                                             
                                             ?>
                                            <!-- added by Millan on 22-02-2021 -->
                                            <a href="javascript:void(0)" data-bs-toggle="modal" class="update_contact_details" data-bs-target="#contact_details_update" title="Edit Contact" data-id="<?= $row->contact_id; ?>"><img src="<?php echo base_url('assets/images/mem_edit.png') ?>" alt="Edit Contact"></a>
                                        <?php 
                                    }
                                     ?>

                                        <?php 
                                        if (exist_val('contact/get_contact_log', $this->session->userdata('permission'))) { 
                                            ?>
                                            <!-- added by Millan on 22-02-2021 -->
                                            <button type="button" class="btn btn-sm user_log_btn_contact" data-bs-toggle="modal" data-bs-target=".user_log_windows" title="User Log" data-id="<?= $row->contact_id; ?>"><img src="<?php echo base_url('assets/images/log-view.png') ?>" alt="User Log"></button>
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

<!-- modal for add new contact -->
<div class="modal fade" id="contact_details" tabindex="-1" role="dialog" aria-labelledby="cont_dataLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content modal-lg">
            <div class="modal-header">
                <h5 class="modal-title" id="cont_dataLabel">Contact Details</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" id="cont_fillData" action="<?php echo base_url('customer_management/Contacts/add_update_contact'); ?>">
                <div class="modal-body">
                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                    <div class="form-group text-left">
                        <div class="row">
                            <div class="col-sm-6">
                                <label for="contact_salutation">Contact Salutation:</label>
                                <input type="text" name="contact_salutation" class="form-control form-control-sm contact_salutation" value="">

                                <label for="customer_type" class="text-dark font-weight-bold">Select Customer Type: </label>
                                <select name="customer_type" class="form-control form-control-sm customer_type">
                                    <option value="">Select Customer Type</option>
                                    <option value="Factory">Factory</option>
                                    <option value="Buyer">Buyer</option>
                                    <option value="Agent">Agent</option>
                                    <option value="Thirdparty">Thirdparty</option>
                                </select>

                                <label for="contacts_designation_id">Designation:</label>
                                <input type="text" name="contacts_designation_id" class="form-control form-control-sm contacts_designation_id" value="">

                                <label for="telephone"><span class="text-danger">*</span>Telephone:</label>
                                <input type="number" name="telephone" class="form-control form-control-sm telephone" value="">

                                <label for="type"><span class="text-danger">*</span>Type:</label>
                                <select name="type" class="form-control form-control-sm type">
                                    <option value="">Select Type</option>
                                    <option value="None">None</option>
                                    <option value="Technical">Technical</option>
                                    <option value="Report">Report</option>
                                    <option value="Invoice">Invoice</option>
                                    <option value="Payment follow-up">Payment follow-up</option>
                                    <option value="Alternative">Alternative</option>
                                    <option value="Contract">Contract</option>
                                    <option value="Invoice follow-up">Invoice follow-up</option>
                                    <option value="site/sampling">site/sampling</option>
                                </select>

                                <label for="country_id">Country:</label>
                                <select name="country_id" class="form-control form-control-sm country_id">
                                    <option value="">Country</option>
                                    <?php foreach ($countries as $con) { ?>
                                        <option value="<?php echo $con->country_id; ?>"> <?php echo $con->country_name; ?> </option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-sm-6">
                                <label for="contact_name"><span class="text-danger">*</span>Contact Name:</label>
                                <input type="text" name="contact_name" class="form-control form-control-sm contact_name" value="">

                                <label for="contacts_customer_id"> Customer Name </label>
                                <select name="contacts_customer_id" class="form-control form-control-sm contact_customer_id">
                                    <option value="" class="text-dark"> Customer Name:</option>
                                </select>

                                <label for="email"><span class="text-danger">*</span>Email:</label>
                                <input type="email" name="email" class="form-control form-control-sm email" value="">

                                <label for="mobile_no">Mobile No:</label>
                                <input type="number" name="mobile_no" class="form-control form-control-sm mobile_no" value="">

                                <label for="status">Status:</label>
                                <select name="status" class="form-control form-control-sm status1">
                                    <option value="" selected disabled>Select Status</option>
                                    <option value="1">Active</option>
                                    <option value="0">InActive</option>
                                </select>

                                <label for="state_id"> State </label>
                                <select name="state_id" class="form-control form-control-sm state_id_get">
                                    <option value="" class="text-dark"> State:</option>
                                </select>
                            </div>

                            <div class="col-sm-12">
                                <label for="note">Note</label>
                                <textarea class="form-control form-control-sm note" rows="5" cols="100" name="note"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary update"> Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- modal for add new contact -->

<!-- modal for update contact -->
<div class="modal fade" id="contact_details_update" tabindex="-1" role="dialog" aria-labelledby="cont_dataLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content modal-lg">
            <div class="modal-header">
                <h5 class="modal-title" id="cont_dataLabel">Contact Details</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" id="cont_updateData" action="<?php echo base_url('customer_management/Contacts/update_contact'); ?>">
                <div class="modal-body">
                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                    <input type="hidden" id="contact_id" name="contact_id" value="">
                    <div class="form-group text-left">
                        <div class="row">
                            <div class="col-sm-6">
                                <label for="contact_salutation">Contact Salutation:</label>
                                <input type="text" name="contact_salutation" class="form-control form-control-sm contact_salutation_edit" value="">

                                <label for="customer_type" class="text-dark font-weight-bold">Select Customer Type: </label>
                                <select name="customer_type" class="form-control form-control-sm customer_type">
                                    <option value="">Select Customer Type</option>
                                    <option value="Factory">Factory</option>
                                    <option value="Buyer">Buyer</option>
                                    <option value="Agent">Agent</option>
                                    <option value="Thirdparty">Thirdparty</option>
                                </select>

                                <label for="contacts_designation_id">Designation:</label>
                                <input type="text" name="contacts_designation_id" class="form-control form-control-sm contacts_designation_id_edit" value="">

                                <label for="telephone"><span class="text-danger">*</span>Telephone:</label>
                                <input type="number" name="telephone" class="form-control form-control-sm telephone_edit" value="">

                                <label for="type"><span class="text-danger">*</span>Type:</label>
                                <select name="type" class="form-control form-control-sm type_edit">
                                    <option value="">Select Type</option>
                                    <option value="None">None</option>
                                    <option value="Technical">Technical</option>
                                    <option value="Report">Report</option>
                                    <option value="Invoice">Invoice</option>
                                    <option value="Payment follow-up">Payment follow-up</option>
                                    <option value="Alternative">Alternative</option>
                                    <option value="Contract">Contract</option>
                                    <option value="Invoice follow-up">Invoice follow-up</option>
                                    <option value="site/sampling">site/sampling</option>
                                </select>

                                <label for="country_id">Country:</label>
                                <select name="country_id" class="form-control form-control-sm country_id_edit">
                                    <option value="">Country</option>
                                    <?php foreach ($countries as $con) { ?>
                                        <option value="<?php echo $con->country_id; ?>"> <?php echo $con->country_name; ?> </option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-sm-6">
                                <label for="contact_name"><span class="text-danger">*</span>Contact Name:</label>
                                <input type="text" name="contact_name" class="form-control form-control-sm contact_name_edit" value="">

                                <label for="contacts_customer_id"> Customer Name </label>
                                <select name="contacts_customer_id" class="form-control form-control-sm contact_customer_id">
                                    <option value="" class="text-dark"> Customer Name:</option>
                                </select>

                                <label for="email"><span class="text-danger">*</span>Email:</label>
                                <input type="email" name="email" class="form-control form-control-sm email_edit" value="">

                                <label for="mobile_no">Mobile No:</label>
                                <input type="number" name="mobile_no" class="form-control form-control-sm mobile_no_edit" value="">

                                <label for="status">Status:</label>
                                <select name="status" class="form-control form-control-sm status_edit">
                                    <option value="" selected disabled>Select Status</option>
                                    <option value="1">Active</option>
                                    <option value="0">InActive</option>
                                </select>

                                <label for="state_id"> State </label>
                                <select name="state_id" class="form-control form-control-sm state_id_get_edit">
                                    <option value="" class="text-dark"> State:</option>
                                </select>
                            </div>

                            <div class="col-sm-12">
                                <label for="note">Note</label>
                                <textarea class="form-control form-control-sm note_edit" rows="5" cols="100" name="note"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary update"> Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- modal for update contact -->

<!-- modal for user log -->
<div class="modal user_log_windows" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content" style="max-height: 500px;">
            <div class="modal-header">
                <h5 class="modal-title"><b>CONTACTS LOG</b></h5>
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
            <tbody id="contact_log"></tbody>
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
        var base_url = "<?php echo base_url('customer_management/Contacts/index'); ?>"
        var customer_type = $('#customer_type').val();
        var customer_name = $('#customer_name').val();
        var contact_name = $('#contact_name').val();
        var type = $('#type').val();
        var created_by = $('#created_by').val();
        var search = $('.search_field').val();

        var status = $('.status_search').val();
        if (customer_type) {
            base_url = base_url + '/' + btoa(customer_type);
        } else {
            base_url = base_url + '/' + 'NULL';
        }
        if (customer_name) {
            base_url = base_url + '/' + btoa(customer_name);
        } else {
            base_url = base_url + '/' + 'NULL';
        }
        if (contact_name) {
            base_url = base_url + '/' + btoa(contact_name);
        } else {
            base_url = base_url + '/' + 'NULL';
        }
        if (type) {
            base_url = base_url + '/' + btoa(type);
        } else {
            base_url = base_url + '/' + 'NULL';
        }
        if (created_by) {
            base_url = base_url + '/' + btoa(created_by);
        } else {
            base_url = base_url + '/' + 'NULL';
        }
        if (search) {
            base_url = base_url + '/' + btoa(search);
        } else {
         
            base_url = base_url + '/' + 'NULL';
        }
        if(status){
            base_url = base_url + '/' + btoa(status);
        }
        else{
            base_url = base_url + '/' + 'NULL';
        }
        location.href = base_url;
    }
</script>

<script>
    $(document).ready(function() {

        var style = {
            'margin': '0 auto'};
        $('.modal-content').css(style);

        $(document).on('change', '.customer_type', function() {
            var customer_type = $(this).val();
            get_customer_name(customer_type);
        });

        $(document).on('change', '.customer_type_edit', function() {
            var customer_type = $(this).val();
            get_customer_name_edit(customer_type, customer_id);
        });

        function get_customer_name(customer_type) {
            var _tokken = $('meta[name="_tokken"]').attr('value');
            $.ajax({
                url: "<?php echo base_url('customer_management/Contacts/extract_cust_name'); ?>",
                data: {
                    customer_type: customer_type,
                    _tokken: _tokken
                },
                type: 'post',
                success: function(result) {
                    $('.contact_customer_id').empty().append(' <option class="text-dark" value="">Select Customer Name</option>');
                    var data = $.parseJSON(result);
                    if (data) {
                        $.each(data, function(i, v) {
                            $('.contact_customer_id').append(' <option class="text-dark" value="' + v.customer_id + '">' + v.customer_name + '</option>');
                        });
                    }
                }
            });
            return false;
        }

        $(document).on('change', '.country_id', function() {
            var country_id = $(this).val();
            get_state_name(country_id);
        });

        $(document).on('change', '.country_id_edit', function() {
            var country_id = $(this).val();
            get_state_name_edit(country_id);
        });

        function get_state_name(country_id) {
            var _tokken = $('meta[name="_tokken"]').attr('value');
            $.ajax({
                url: "<?php echo base_url('customer_management/Contacts/extract_state'); ?>",
                data: {
                    country_id: country_id,
                    _tokken: _tokken
                },
                type: 'post',
                success: function(result) {
                    $('.state_id_get').empty().append(' <option class="text-dark" value="">Select State</option>');
                    var data = $.parseJSON(result);
                    if (data) {
                        $.each(data, function(i, v) {
                            $('.state_id_get').append(' <option class="text-dark" value="' + v.province_id + '">' + v.province_name + '</option>');
                        });
                    }
                    else{
                        $('.state_id_get').append(' <option class="text-dark" value="">NO RECORD FOUND</option>');
                    }
                }
            });
            return false;
        }

        $(document).on('submit', '#cont_fillData', function(e) {
            e.preventDefault();
            var fill_data = new FormData(this);
            $.ajax({
                url: "<?php echo base_url('customer_management/Contacts/add_contact'); ?>",
                type: "post",
                data: $(this).serialize(),
                success: function(result) {
                    $('.form_errors').remove();
                    var data = $.parseJSON(result);
                    if (data.status > 0) {
                        $.notify(data.msg,'success');
                        location.reload();
                    }
                    else{
                        $.notify(data.msg,'error');
                    }
                    if (data.error) {
                        $.each(data.error, function(i, v) {
                            $('#cont_fillData input[name=' + i + ']').after('<span class="text-danger form_errors">' + v + '</span>');
                            $('#cont_fillData select[name=' + i + ']').after('<span class="text-danger form_errors">' + v + '</span>');
                            $('#cont_fillData textarea[name=' + i + ']').after('<span class="text-danger form_errors">' + v + '</span>');
                        });
                    }
                }
            });
            return false;
        });

        $(document).on('click', '.update_contact_details', function() {
            var contact_id = $(this).data('id');
            var _tokken = $('meta[name="_tokken"]').attr('value');
            $.ajax({
                url: "<?php echo base_url('customer_management/Contacts/fetch_contact_data'); ?>",
                type: "post",
                data: {
                    contact_id: contact_id,
                    _tokken: _tokken
                },
                success: function(result) {
                    var data = $.parseJSON(result);
                    get_customer_name_edit(data.customer_type, data.contacts_customer_id);
                    get_state_name_edit(data.country_id, data.state_id);
                    $('#contact_details_update #contact_id').val(data.contact_id);
                    $('#contact_details_update .contact_salutation_edit').val(data.contact_salutation);
                    $('#contact_details_update .contact_name_edit').val(data.contact_name);
                    // $('#contact_details_update .customer_type').val(data.customer_type);
                    $('.customer_type option[value=' + data.customer_type + ']').attr('selected', 'selected');
                    $('#contact_details_update .contacts_designation_id_edit').val(data.contacts_designation_id);
                    $('#contact_details_update .email_edit').val(data.email);
                    $('#contact_details_update .telephone_edit').val(data.telephone);
                    $('#contact_details_update .mobile_no_edit').val(data.mobile_no);
                    $('#contact_details_update .type_edit').val(data.type);
                    $('#contact_details_update .status_edit').val(data.status);
                    $('#contact_details_update .country_id_edit').val(data.country_id);
                    $('#contact_details_update .note_edit').val(data.note);
                }
            })
            return false;
        });

        function get_customer_name_edit(customer_type, customer_id) {
            var _tokken = $('meta[name="_tokken"]').attr('value');
            $.ajax({
                url: "<?php echo base_url('customer_management/Contacts/extract_cust_name'); ?>",
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
                                    $('#cont_updateData .contact_customer_id').append('<option selected value="' + v.customer_id + '">' + v.customer_name + '</option>');
                                } else {
                                    $('#cont_updateData .contact_customer_id').append('<option value="' + v.customer_id + '">' + v.customer_name + '</option>');
                                }
                            } else {
                                $('#cont_updateData .contact_customer_id').append('<option value="' + v.customer_id + '">' + v.customer_name + '</option>');
                            }
                        });
                    }
                }
            })
        }

        function get_state_name_edit(country_id, state_id) {
            var _tokken = $('meta[name="_tokken"]').attr('value');
            $.ajax({
                url: "<?php echo base_url('customer_management/Contacts/extract_state'); ?>",
                type: "post",
                data: {
                    country_id: country_id,
                    _tokken: _tokken
                },
                success: function(result) {
                    $('#cont_updateData .state_id_get_edit').empty().append('<option> Select State </option>');
                    var data = $.parseJSON(result);
                    if (data) {
                        $.each(data, function(i, v) {
                            if (state_id) {
                                if (state_id == v.province_id) {
                                    $('#cont_updateData .state_id_get_edit').append('<option selected value="' + v.province_id + '">' + v.province_name + '</option>');
                                } else {
                                    $('#cont_updateData .state_id_get_edit').append('<option value="' + v.province_id + '">' + v.province_name + '</option>');
                                }
                            } else {
                                $('#cont_updateData .state_id_get_edit').append('<option value="' + v.province_id + '">' + v.province_name + '</option>');
                            }
                        });
                    }
                    else{
                        $('#cont_updateData .state_id_get_edit').append('<option value="" selected>NO RECORD FOUND</option>');
                    }
                }
            })
        }

        $(document).on('submit', '#cont_updateData', function(e) {
            e.preventDefault();
            var fill_data = new FormData(this);
            var contact_id = $('#contact_id').val();
            $.ajax({
                url: "<?php echo base_url('customer_management/Contacts/update_contact'); ?>",
                type: "post",
                data: $(this).serialize(),
                success: function(result) {
                    console.log(result);
                    $('.form_errors').remove();
                    var data = $.parseJSON(result);
                    if (data.status > 0) {
                        $.notify(data.msg,'success');
                        location.reload();
                    }
                    else{
                        $.notify(data.msg,'error');
                    }
                    if (data.error) {
                        $.each(data.error, function(i, v) {
                            $('#cont_updateData input[name=' + i + ']').after('<span class="text-danger form_errors">' + v + '</span>');
                            $('#cont_updateData select[name=' + i + ']').after('<span class="text-danger form_errors">' + v + '</span>');
                            $('#cont_updateData textarea[name=' + i + ']').after('<span class="text-danger form_errors">' + v + '</span>');
                        });
                    }
                }
            });
            return false;
        });

        // user log detail fetching
        // $('.user_log_btn').on('click', function() {
        //     var contact_id = $(this).data('id');
        //     get_user_log_data(contact_id);

        // });

        // function get_user_log_data(contact_id) {
        //     const _tokken = $('meta[name="_tokken"]').attr("value");
        //     $.ajax({
        //         url: "<?php echo base_url('customer_management/Contacts/get_user_log_data') ?>",
        //         method: "POST",
        //         data: {
        //             contact_id: contact_id,
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
      $('.user_log_btn_contact').click(function() {
        $('#contact_log').empty();
        var contact_id = $(this).data('id');
        $.ajax({
          type: 'post',
          url: url + 'customer_management/Contacts/get_contact_log_CONTACT',
          data: {
            _tokken: _tokken,
            contact_id: contact_id
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
            $('#contact_log').append(value);
          }
        });
      });
      // ajax call to get log ends here
    });
    
  </script>