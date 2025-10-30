<style>
    .list-group-item {
        padding: 5px;
        list-style: none;
    }

    .status_btn {
        width: 70px;
    }
</style>
<div class="content-wrapper">
    <section class="content-header">
    <div class="container-fluid">
        <div class="row">
          <div class="col-sm-12 text-center">
          <div class="float-left mt-3">
          <?php if (exist_val('Website_users/add_users', $this->session->userdata('permission'))) { ?>
            <button type="button" class="btn btn-sm btn-primary add" data-bs-toggle="modal" data-bs-target=".add_website_users_modal"> <i class="fa fa-plus"></i> Add</button>
                                          <?php } ?>
                                      </div>
            <h1 class="text-bold mt-3 mb-3">WEBSITE USERS</h1>
          </div>
        </div>
      </div>
      <div class="container-fluid jumbotron p-3">
      <div class="row">
                                <div class="col">
                                    <select name="" id="" class="customer_type form-control form-control-sm" value="">
                                        <option value="">SELECT CLIENT</option>
                                        <option value="Factory" <?php echo ($client_type == "Factory") ? "selected" : ""; ?>>Factory</option>
                                        <option value="Buyer" <?php echo ($client_type == "Buyer") ? "selected" : ""; ?>>Buyer</option>
                                        <option value="Agent" <?php echo ($client_type == "Agent") ? "selected" : ""; ?>>Agent</option>
                                        <option value="Thirdparty" <?php echo ($client_type == "Thirdparty") ? "selected" : ""; ?>>Third party</option>
                                    </select>
                                </div>
                                <div class="col">
                                    <input type="hidden" class="customer_name_website_hidden" value="<?php echo ($customer_id) ? ($customer_id) : ''; ?>">
                                    <input type="text" class="form-control form-control-sm customer_name_website" placeholder="Customer name" autocomplete="off" value="<?php echo ($customer_name) ? ($customer_name) : ""; ?>">
                                    <ul class="list-group-item customer_name_website_list" style="display:none">
                                    </ul>
                                </div>

                                <div class="col">
                                    <input type="hidden" class="contact_person_id" value="<?php echo ($contact_id) ? ($contact_id) : ''; ?>">
                                    <input type="text" class="form-control form-control-sm contact_person_name" placeholder="Contact person" autocomplete="off" value="<?php echo ($contact_name) ? ($contact_name) : ""; ?>">
                                    <ul class="list-group-item contact_person_list" style="display:none">
                                    </ul>
                                </div>

                                <div class="col">
                                    <select name="" id="" class="website_status form-control form-control-sm" value="">

                                        <option value="">SELECT STATUS</option>

                                        <option value="Inactive" <?php echo ($status == "Inactive") ? "selected" : ""; ?>>Inactive</option>
                                        <option value="Active" <?php echo ($status == "Active") ? "selected" : ""; ?>>Active</option>
                                    </select>
                                </div>



                                <div class="col">
                    <div class="input-group">
                    <input value="<?php echo (($search != 'NULL') ? $search : ""); ?>" id="search" class="form-control form-control-sm" type="text" placeholder="Search" aria-label="Search">
                    <div class="input-group-append">
                    <?php if (exist_val('Website_users/index', $this->session->userdata('permission'))) { ?>
                    <button  onclick="search_filter_record()" type="button" class="btn btn-sm btn-secondary" title="Search">
                      <i class="fa fa-search"></i>
                    </button>
                    <a class="btn btn-sm btn-danger ml-1" href="<?php echo base_url('Website_users') ?>" title="Clear">
                      <i class="fa fa-trash"></i>
                    </a>
                    <?php } ?>
                    </div>
                  </div></div>

                            </div>
      </div>
        <!-- container fluid start -->
        <div class="container-fluid">  
            <div class="row">
                <div class="col-12">
                    <div class="card">
                       <div class="table-responsive p-2">
                            <table class="table table-sm" style="font-size: small;">
                                <thead>
                                    <tr>

                                        <?php

                                        if ($client_type) {
                                            $client_type = base64_encode($client_type);
                                        } else {
                                            $client_type = "NULL";
                                        }

                                        if ($customer_id) {
                                            $customer_id = base64_encode($customer_id);
                                        } else {
                                            $customer_id = "NULL";
                                        }

                                        if ($contact_id) {
                                            $contact_id = base64_encode($contact_id);
                                        } else {
                                            $contact_id = "NULL";
                                        }

                                        if ($status) {
                                            $status = base64_encode($status);
                                        } else {
                                            $status = "NULL";
                                        }

                                        if ($search) {
                                            $search = base64_encode($search);
                                        } else {
                                            $search = "NULL";
                                        }
                                        ?>

                                        <th scope="col">S. NO.</th>
                                        <th scope="col"><a href="<?php echo base_url('Website_users/index/' . $client_type . '/' . $customer_id . '/' . $contact_id . '/' . $status . '/' . $search . '/' . base64_encode('users.customer_type') . '/' . $order) ?>">CUSTOMER TYPE</a></th>

                                        <th scope="col"><a href="<?php echo base_url('Website_users/index/' . $client_type . '/' . $customer_id . '/' . $contact_id . '/' . $status . '/' . $search . '/' . base64_encode('cust.customer_name') . '/' . $order) ?>"> CUSTOMER NAME</a></th>

                                        <th scope="col"><a href="<?php echo base_url('Website_users/index/' . $client_type . '/' . $customer_id . '/' . $contact_id . '/' . $status . '/' . $search . '/' . base64_encode('contact.contact_name') . '/' . $order) ?>">CONTACT PERSON</a></th>

                                        <th scope="col"><a href="<?php echo base_url('Website_users/index/' . $client_type . '/' . $customer_id . '/' . $contact_id . '/' . $status . '/' . $search . '/' . base64_encode('users.customer_login_username') . '/' . $order) ?>">USER NAME</a></th>

                                        <th scope="col"><a href=" <?php echo base_url('Website_users/index/' . $client_type . '/' . $customer_id . '/' . $contact_id . '/' . $status . '/' . $search . '/' . base64_encode('users.customer_login_status') . '/' . $order) ?>">STATUS</a></th>
                                        <th scope="col">ACTION</th>
                                    </tr>
                                </thead>

                                <tbody>

                                    <?php $sn = $this->uri->segment('10') + 1;
                                    if ($users_list) {

                                        foreach ($users_list as $key => $item) { ?>
                                            <tr>
                                                <td><?php echo $sn; ?></td>
                                                <td><?php echo $item->customer_type ?></td>
                                                <td><?php echo $item->customer_name ?></td>
                                                <td><?php echo $item->contact_name ?></td>
                                                <td><?php echo $item->customer_login_username ?></td>
                                                <td>

                                                    <?php if (exist_val('Website_users/update_status', $this->session->userdata('permission'))) { ?>
                                                        <?php
                                                        if ($item->customer_login_status == "Active") { ?>
                                                            <a href="<?php echo base_url('Website_users/update_status/' . $item->customer_login_id) ?>" class="btn btn-sm btn-success status_btn" title="Inactive"><?php echo $item->customer_login_status ?></a>

                                                        <?php  } else { ?>
                                                            <a href="<?php echo base_url('Website_users/update_status/' . $item->customer_login_id) ?>" class="btn btn-sm btn-danger status_btn" title="Active"><?php echo $item->customer_login_status ?></a>
                                                        <?php  } ?>
                                                    <?php  } ?>
                                                </td>

                                                <td>
                                                    <?php if (exist_val('Website_users/update_users', $this->session->userdata('permission'))) { ?>
                                                        <button type="button" class="btn btn-sm edit_button_of_modal" data-bs-toggle="modal" data-bs-target=".edit_website_users_modal" data-id="<?php echo $item->customer_login_id ?>">
                                                            <i class="fa fa-edit" alt="edit" title="Edit User"></i>
                                                        </button>
                                                    <?php } ?>
                                                    <?php if (exist_val('Website_users/delete_user', $this->session->userdata('permission'))) { ?>

                                                        <button type="button" class="btn btn-sm  delete_button text-danger" data-bs-toggle="modal" data-bs-target=".delete_website_users_modal" data-id="<?php echo $item->customer_login_id ?>">
                                                            <i class="fa fa-trash" alt="delete" title="Delete User"></i>
                                                        </button>
                                                    <?php } ?>
                                                    <?php if (exist_val('Website_users/save_permission', $this->session->userdata('permission'))) { ?>
                                                        <a class="btn btn-sm permission_click" data-id="<?php echo $item->customer_login_id ?>" data-bs-toggle='modal' data-bs-target='#permission_modal' href="javascript:void(0);"><i class="fa fa-user" aria-hidden="true" title="User Permission"></i> </a>
                                                    <?php } ?>
                                                    <?php
                                                    if (exist_val('Website_users/get_log_data', $this->session->userdata('permission'))) { ?>
                                                        <a href="javascript:void(0)" data-id="<?php echo $item->customer_login_id ?>" class="log_view btn btn-sm" data-bs-toggle='modal' data-bs-target='#lo_view_target' title="Log View"><i class="fa fa-eye text-black-50" title="View Logs"></i></a>
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

                        <hr>

                        <div class="card-header">
                            <?php if ($users_list && count($users_list) > 0) { ?>
                                <span><?php echo $links ?></span>
                                <span><?php echo $result_count; ?></span>
                            <?php } else { ?>
                                <h3>NO RECORD FOUND</h3>
                            <?php } ?>

                        </div>
                    </div>
                    <!-- card end -->
                </div>
            </div>

            <!-- menu end -->


        </div>
        <!-- container fluid end -->
    </section>
    <!-- add -->
    <div class="modal fade bd-example-modal-lg add_website_users_modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form id="add_website_user_form_id" name="add_website_form_name" action="javascript:void(0);">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Add New User</h5>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">

                        <input type="hidden" name="customer_login_id" class="customer_login_id_add" value="">
                        <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">


                        <div class="row p-2">
                            <div class="col-sm-6">
                                <label for="">Client :</label>
                                <select name="customer_type" class="form-control form-control-sm client_type" name="">
                                    <option value="" disabled selected>SELECT CLIENT</option>
                                    <option value="Factory">Factory</option>
                                    <option value="Buyer">Buyer</option>
                                    <option value="Agent">Agent</option>
                                    <option value="Thirdparty">Third party</option>
                                </select>

                            </div>

                            <div class="col-sm-6">
                                <label for="">Customer Name :</label>
                                <select name="contacts_customer_id" class="form-control form-control-sm customer_name_add">

                                </select>

                            </div>
                        </div>

                        <div class="row p-2">
                            <div class="col-sm-6">
                                <label for="">Contact Name :</label>
                                <select name="cl_contact_id" class="form-control form-control-sm contact_name_add">

                                </select>

                            </div>
                            <div class="col-sm-6">
                                <label for="">User Name :</label>
                                <input name="customer_login_username" class="form-control form-control-sm client_email" readonly style="background-color: #D3D3D3;" type="text">

                            </div>

                        </div>

                        <div class="row p-2">
                            <div class="col-sm-5">
                                <label for="" style="display: block;">Password :</label>
                                <input type="password" name="customer_login_password" id="add_pass" class="form-control form-control-sm password">


                            </div>
                            <div class="col-sm-1">
                                <label for="" style="display: block;">view</label>
                                <button type="button" class="btn btn-sm btn-info show_hide">SHOW</button>
                            </div>
                            <div class="col-sm-5">
                                <label for="">Confirm Password :</label>
                                <input type="password" name="confirm_password" id="add" class="form-control form-control-sm confirm_password">

                            </div>
                            <div class="col-sm-1 accept" style="display: none;">
                                <label for="" style="display: block;">check</label>
                                <img src="<?php echo base_url('assets/images/accept.png') ?>" alt="">
                            </div>

                        </div>

                        <div class="row p-2">
                            <div class="col-sm-6">
                                <label for="">Status :</label>
                                <select name="customer_login_status" class="form-control form-control-sm status">
                                    <option value="">SELECT</option>
                                    <option value="Active">Active</option>
                                    <option value="Inactive">Inactive</option>
                                </select>

                            </div>

                        </div>



                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary add_website_users_submit">ADD</button>
                    </div>
                </div>

            </form>
        </div>
    </div>

    <!-- edit -->
    <div class="modal fade bd-example-modal-lg edit_website_users_modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form id="edit_website_user_form_id" name="edit_website_form_name" action="javascript:void(0);">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Update User</h5>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">

                        <input type="hidden" name="customer_login_id" class="customer_login_id_edit" value="">
                        <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">


                        <div class="row p-2">
                            <div class="col-sm-6">
                                <label for="">Client :</label>
                                <select name="customer_type" class="form-control form-control-sm client_type" name="">
                                    <option value="" disabled selected>SELECT CLIENT</option>
                                    <option value="Factory">Factory</option>
                                    <option value="Buyer">Buyer</option>
                                    <option value="Agent">Agent</option>
                                    <option value="Thirdparty">Third party</option>
                                </select>

                            </div>

                            <div class="col-sm-6">
                                <label for="">Customer Name :</label>
                                <select name="contacts_customer_id" class="form-control form-control-sm customer_name_add">

                                </select>

                            </div>
                        </div>

                        <div class="row p-2">
                            <div class="col-sm-6">
                                <label for="">Contact Name :</label>
                                <select name="cl_contact_id" class="form-control form-control-sm contact_name_add">

                                </select>

                            </div>
                            <div class="col-sm-6">
                                <label for="">User Name :</label>
                                <input name="customer_login_username" class="form-control form-control-sm client_email" readonly style="background-color: #D3D3D3;" type="text">

                            </div>

                        </div>

                        <div class="row p-2">
                            <div class="col-sm-5">
                                <label for="" style="display: block;">Password :</label>
                                <input type="password" name="customer_login_password" id="edit_pass" class="form-control form-control-sm password">


                            </div>
                            <div class="col-sm-1">
                                <label for="" style="display: block;">view</label>
                                <button type="button" class="btn btn-sm btn-info show_hide">SHOW</button>
                            </div>
                            <div class="col-sm-5">
                                <label for="">Confirm Password :</label>
                                <input type="password" name="confirm_password" id="edit" class="form-control form-control-sm confirm_password">

                            </div>
                            <div class="col-sm-1 accept" style="display: none;">
                                <label for="" style="display: block;">check</label>
                                <img src="<?php echo base_url('assets/images/accept.png') ?>" alt="">
                            </div>

                        </div>

                        <div class="row p-2">
                            <div class="col-sm-6">
                                <label for="">Status :</label>
                                <select name="customer_login_status" class="form-control form-control-sm status">
                                    <option value="" selected disabled>SELECT</option>
                                    <option value="Active">Active</option>
                                    <option value="Inactive">Inactive</option>
                                </select>

                            </div>

                        </div>



                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary edit_website_users_submit">UPDATE</button>
                    </div>
                </div>

            </form>
        </div>
    </div>


    <!-- delete -->

    <!-- user log -->
    <div class="modal fade" id="lo_view_target" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content" style="max-height: 500px;">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">USER LOG</h5>
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

</div>



</div>

<div class="modal fade" id="permission_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">PERMISSION</h5><button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <form method="post" id="permission_save" class="permission_save" action="javscript:void(0);">
                <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                <input type="hidden" name="contact_id" class="contact_id" value="">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="row">
                                <div class="col-sm-12">
                                    <h4 class=""><input type="checkbox" class="check_all_permission"> <label for=""> Check All</label></h4>
                                </div>
                            </div>
                            <div class="row" id="permission_html">

                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">CLOSE</button><button type="submit" class="btn btn-primary">UPDATE</button></div>
            </form>
        </div>
    </div>
</div>

<script>
    function search_filter_record() {
        url = '<?php echo base_url("Website_users/index"); ?>';

        client_type = $('.customer_type').val();

        if (client_type != "") {
            url = url + '/' + btoa(client_type);
        } else {
            client_type = "";
            url = url + '/NULL';
        }


        if ($('.customer_name_website').val() == "") {
            $('.customer_name_website_hidden').val("");
        }
        customer_id = $('.customer_name_website_hidden').val();

        if (customer_id != "") {
            url = url + '/' + btoa(customer_id);
        } else {
            customer_id = "";
            url = url + '/NULL';
        }

        if ($('.contact_person_name').val() == "") {
            $('.contact_person_id').val("");
        }
        contact_id = $('.contact_person_id').val();

        if (contact_id != "") {
            url = url + '/' + btoa(contact_id);
        } else {
            contact_id = "";
            url = url + '/NULL';
        }

        status = $('.website_status').val();
        if (status != "") {
            url = url + '/' + btoa(status);
        } else {
            status = "";
            url = url + '/NULL';
        }


        search = $('#search').val();


        if (search != "") {
            url = url + '/' + btoa(search);
        } else {
            search = "";
            url = url + '/NULL';
        }

        window.location.href = url;
    }
</script>
<script>
    $(document).ready(function() {


        $('.customer_name_add').select2();
        $('.contact_name_add').select2();

        $('.edit_button_of_modal').on('click', function() {
            var edit_id = $(this).data("id");
            $('.customer_login_id_edit').attr('value', edit_id);
            get_website_uers_data(edit_id);
        })


        function get_website_uers_data(edit_id) {
            const _tokken = $('meta[name="_tokken"]').attr("value");
            $.ajax({
                url: "<?php echo base_url('Website_users/edit_user') ?>",
                method: "POST",
                data: {
                    id: edit_id,
                    _tokken: _tokken
                },
                success: function(response) {
                    var data = $.parseJSON(response);
                    if (data) {

                        $('.client_type option[value=' + data.customer_type + ']').attr('selected', 'selected');

                        $('.status option[value=' + data.customer_login_status + ']').attr('selected', 'selected');


                        var where_add1 = JSON.stringify({
                            customer_type: data.customer_type
                        });

                        Get_dropDropdown_by_Ajax('customer_name_add', 'SELECT CUSTOMER', 'customer_id as id,customer_name as name', 'cust_customers', where_add1, data.customer_id);

                        var where_cus_id1 = JSON.stringify({
                            contacts_customer_id: data.customer_id
                        });

                        Get_dropDropdown_by_Ajax('contact_name_add', 'SELECT CONTACT', 'contact_id as id,contact_name as name',
                            'contacts', where_cus_id1, data.contact_id);

                        $('.client_email').attr('value', data.customer_login_username);


                    }
                }
            });
            return false;
        }


        $('.password').on('keyup', function() {
            var str = $(this).val();
            var result = test_str(str);
            if (result) {
                $('.error').remove();
                $('.error_users').remove();
                var count = $(this).val().length;
                if (count == 8) {
                    $(this).after("<span class='error' style='color:green'>Good Password</span>");
                }
                if (count > 8) {
                    $(this).after("<span class='error' style='color:green'>Very Good Password</span>");
                }
                $(this).css("border", "1px solid green");
                $('.add_website_users_submit').attr('disabled', false);
            } else {
                $('.error').remove();
                $('.error_users').remove();
                $(this).css("border", "1px solid red");
                $(this).after("<span class='error' style='color:red'>Password must contain : Minimum 8 characters including at least 1 uppercase character, at least 1 lowercase character,at least 1 digit and at least 1 special character</span>");
                $('.add_website_users_submit').attr('disabled', true);
            }
        });


        $('.show_hide').on('click', function() {
            var type = $('.password').attr('type');
            if (type == "text") {
                $('.password').attr('type', 'password');
                $(this).html("SHOW");
            } else {
                $('.password').attr('type', 'text');
                $(this).html("HIDE");
            }
        })

        function test_str(str) {

            if (str.match(/[a-z]/g) && str.match(/[A-Z]/g) && str.match(/[0-9]/g) && str.match(/[^a-zA-Z\d]/g) && str.length >= 8) {
                return true;
            } else {
                return false;
            }

        }


        $('.confirm_password').on("keyup", function() {
            if ($(this).attr("id") == "add") {
                var password = $('#add_pass').val();
            } else {
                var password = $('#edit_pass').val();
            }

            var confirm_password = $(this).val();
            if (confirm_password != "") {
                if (password == confirm_password) {
                    $('.con_error').remove();
                    $('.error_users').remove();
                    $(this).after("<span class='con_error' style='color:green'>Password Matched!</span>");
                    $('.accept').css("display", "block");
                    $(this).css("border", "1px solid green");
                    $('.add_website_users_submit').attr('disabled', false);
                    $('.edit_website_users_submit').attr('disabled', false);
                } else {
                    $('.con_error').remove();
                    $('.error_users').remove();
                    $(this).after("<span class='con_error' style='color:red'>Password Not Matched!</span>");
                    $('.accept').css("display", "none");
                    $(this).css("border", "1px solid green");
                    $('.add_website_users_submit').attr('disabled', true);
                    $('.edit_website_users_submit').attr('disabled', true);
                }
            }
        })

        function Get_dropDropdown_by_Ajax(selectBoxClass, placeholder, tableColumn, table, where = null, selected_id = null) {
            var selectEvent = $('select.' + selectBoxClass);
            const _tokken = $('meta[name="_tokken"]').attr("value");
            $.ajax({
                url: "<?php echo base_url('Website_users/get_dropdown_by_ajax_website') ?>",
                method: "POST",
                data: {
                    select: tableColumn,
                    from: table,
                    where: where,
                    _tokken: _tokken
                },
                success: function(response) {
                    var data = $.parseJSON(response);
                    selectEvent.html("");
                    var option = "<option value='' selected>" + placeholder + "</option>";
                    selectEvent.append(option);

                    if (data) {
                        $.each(data, function(index, value) {
                            if (selected_id == value.id) {
                                var option = "<option value='" + value.id + "' selected>" + value.name + "</option>";
                            } else {
                                var option = "<option value='" + value.id + "' >" + value.name + "</option>";
                            }
                            selectEvent.append(option);
                        });
                    } else {
                        var option = "<option value='' >NO RECORD FOUND</option>";
                        selectEvent.append(option);
                    }

                }
            });
            return false;
        }

        // Get_dropDropdown_by_Ajax(selectBoxClass,placeholder,tableColumn,table,where=null,selected_id=null)

        $('.client_type').on('change', function() {
            where_type = $(this).val();
            var where_add = JSON.stringify({
                customer_type: where_type
            });

            Get_dropDropdown_by_Ajax('customer_name_add', 'SELECT CUSTOMER', 'customer_id as id,customer_name as name', 'cust_customers', where_add, null);
        });

        $('.customer_name_add').on('change', function() {
            where_cus = $(this).val();
            var where_cus_id = JSON.stringify({
                contacts_customer_id: where_cus
            });
            Get_dropDropdown_by_Ajax('contact_name_add', 'SELECT CONTACT', 'contact_id as id,contact_name as name', 'contacts', where_cus_id, null);

        });

        $('.contact_name_add').on('change', function() {
            var where_con = $(this).val();
            get_email(where_con);
        })

        function get_email(where_con) {

            const _tokken = $('meta[name="_tokken"]').attr("value");
            $.ajax({
                url: "<?php echo base_url('Website_users/get_email') ?>",
                method: "POST",
                data: {
                    _tokken: _tokken,
                    where_con: where_con
                },
                success: function(data) {
                    email = $.parseJSON(data);
                    if (email) {
                        $('.client_email').attr('value', email.email);
                    } else {
                        $('.client_email').attr('value', "");
                    }
                }
            });
            return false;
        }

        // add
        $('#edit_website_user_form_id').submit(function(e) {
            e.preventDefault();
            $.ajax({
                url: "<?php echo base_url('Website_users/update_users') ?>",
                method: "POST",
                data: $(this).serialize(),
                success: function(result) {
                    var msg = $.parseJSON(result);
                    if (msg.status > 0) {
                        // $.notify(msg.msg, 'success');
                        $('.edit_website_users_modal').modal("hide");
                        $("#edit_website_user_form_id").trigger('reset');
                        window.location.href = "<?php echo base_url('Website_users') ?>";
                    } else {
                        $.notify(msg.msg, 'error');
                    }
                    if (msg.errors) {
                        var error = msg.errors;
                        $('.error_users').remove();
                        $.each(error, function(i, v) {
                            $('#edit_website_user_form_id input[name="' + i + '"]').after('<span class="text-danger error_users">' + v + '</span>');
                            $('#edit_website_user_form_id select[name="' + i + '"]').after('<span class="text-danger error_users">' + v + '</span>');
                        });

                    } else {
                        $('.error_users').remove();
                    }
                }
            })
        });

        // delete
        $('.delete_button').on('click', function(e) {
            e.preventDefault();
            var delete_id = $(this).data("id");
            const _tokken = $('meta[name="_tokken"]').attr("value");
            if (confirm("are you sure!")) {
                $.ajax({
                    url: "<?php echo base_url('Website_users/delete_user') ?>",
                    method: "post",
                    data: {
                        id: delete_id,
                        _tokken: _tokken
                    },
                    success: function(response) {
                        var msg = $.parseJSON(response);
                        if (msg.status > 0) {
                            // $.notify(msg.msg, 'success');
                            window.location.reload();
                        } else {
                            $.notify(msg.msg, 'error');
                        }
                    }


                });
                return false;
            } else {
                return false;
            }

        })

        $('#add_website_user_form_id').submit(function(e) {
            e.preventDefault();
            $.ajax({
                url: "<?php echo base_url('Website_users/add_users') ?>",
                method: "POST",
                data: $(this).serialize(),
                success: function(result) {
                    var msg = $.parseJSON(result);
                    if (msg.status > 0) {
                        // $.notify(msg.msg, 'success');
                        $('.add_website_users_modal').modal("hide");
                        $("#add_website_user_form_id").trigger('reset');
                        window.location.href = "<?php echo base_url('Website_users') ?>";
                    } else {
                        $.notify(msg.msg, 'error');
                    }
                    if (msg.errors) {
                        var error = msg.errors;
                        $('.error_users').remove();
                        $.each(error, function(i, v) {
                            $('#add_website_user_form_id input[name="' + i + '"]').after('<span class="text-danger error_users">' + v + '</span>');
                            $('#add_website_user_form_id select[name="' + i + '"]').after('<span class="text-danger error_users">' + v + '</span>');
                        });

                    } else {
                        $('.error_users').remove();
                    }
                }
            })
        });

        // customer 
        getAutolist_website(
            'customer_name_website_hidden',
            'customer_name_website',
            'customer_name_website_list',
            'customer_name_website_li',
            '',
            'customer_name',
            'customer_id as id, customer_name as name',
            'cust_customers'
        );


        // contact person

        getAutolist_website(
            'contact_person_id',
            'contact_person_name',
            'contact_person_list',
            'contact_person_li',
            '',
            'contact_name',
            'contact_id as id, contact_name as name',
            'contacts'
        );




        var css = {
            "position": "absolute",
            "width": "93%",
            "height": "auto",
            "font-size": "10px",
            "z-index": 999,
            "overflow-y": "auto",
            "overflow-x": "hidden",
            "max-height": "200px",
            "cursor": "pointer",
        };

        function getAutolist_website(hide_input, input, ul, li, where, like, select, table) {

            var base_url = $("body").attr("data-url");
            var hide_inputEvent = $("input." + hide_input);
            var inputEvent = $("input." + input);
            var ulEvent = $("ul." + ul);

            inputEvent.focusout(function() {
                ulEvent.fadeOut();
            });

            inputEvent.on("keyup", function(e) {
                var me = $(this);
                var key = $(this).val();
                var _URL = "<?php echo base_url('Website_users/get_auto_list_website') ?>";
                const _tokken = $('meta[name="_tokken"]').attr("value");
                e.preventDefault();


                $.ajax({
                    url: _URL,
                    method: "POST",
                    data: {
                        key: key,
                        where: where,
                        like: like,
                        select: select,
                        table: table,
                        _tokken: _tokken,
                    },

                    success: function(data) {
                        var html = $.parseJSON(data);
                        ulEvent.fadeIn();
                        ulEvent.css(css);

                        ulEvent.html("");
                        if (html) {
                            $.each(html, function(index, value) {
                                ulEvent.append($('<li class="list-group-item ' + li + '"' + "data-id=" + value.id + ">" + value.name + "<li>"));
                            });
                        } else {
                            ulEvent.append($('<li class="list-group-item ' + li + '"' + 'data-id="not">NO REORD FOUND</li>'));
                        }

                        var liEvent = $("li." + li);
                        liEvent.click(function() {
                            var id = $(this).attr("data-id");
                            var name = $(this).text();
                            inputEvent.val(name);
                            hide_inputEvent.val(id);
                            ulEvent.fadeOut();
                        });

                        // ****
                    },

                });

                return false;

            });
        }
    })
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
                url: url + 'Website_users/get_log_data',
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
                        var taken_at = v.taken_at;
                        value += '<tr>';
                        value += '<td>' + sno + '</td>';
                        value += '<td>' + operation + '</td>';
                        value += '<td>' + action_message + '</td>';
                        value += '<td>' + taken_by + '</td>';
                        value += '<td>' + taken_at + '</td>';
                        value += '</tr>';

                    });
                    $('#table_log').append(value);
                }
            });
        });
        // ajax call to get log ends here
    });
</script>
<script>
    $(document).ready(function() {
        $(document).on('click', '.check_all_permission', function() {
            $('#permission_html input:checkbox').not(this).prop('checked', this.checked);
        });
    });
</script>
<script>
    $(document).ready(function() {
        var base = '<?php echo base_url("Website_users/"); ?>';
        let _tokken = $('meta[name="_tokken"]').attr("value");
        $(document).on('click', '.permission_click', function() {
            var id = $(this).data('id');
            $('#permission_save input[name="contact_id"]').val(id);
            $.ajax({
                async: true,
                url: base + 'fetch_permission',
                method: "POST",
                data: {
                    _tokken: _tokken,
                    contact_id: id
                },
                success: function(response) {
                    $('#permission_save').trigger('reset');
                    var permission_arr = $.parseJSON(response);
                    console.log(permission_arr);
                    if (permission_arr) {
                        $.each(permission_arr, function(i, v) {
                            $('#permission_save input[value="' + v + '"]').prop("checked", true);
                        });
                    }
                },
                errors: function(e) {
                    alert(e);
                }
            });
        });


        $(document).on('submit', '#permission_save', function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            $.ajax({
                async: true,
                url: base + 'save_permission',
                method: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    $(".role_edit_errors").html("");
                    var data = $.parseJSON(response);
                    if (data.status > 0) {
                        $('#permission_save').trigger('reset');
                        $.notify(data.msg, "success");
                        $('#permission_modal button[data-bs-dismiss="modal"]').click();
                    } else {
                        $.notify(data.msg, "error");
                    }
                },
                errors: function(e) {
                    alert(e);
                }
            });
        });

        $.ajax({
            async: true,
            url: base + 'fetch_list',
            method: "POST",
            data: {
                _tokken: _tokken
            },
            success: function(response) {
                var data = $.parseJSON(response);
                if (data) return $('#permission_html').html(data);

                return $('#permission_html').html('<h2 class="text-center">NO PERMISSION & ACTION AVAILABLE</h2>')
            },
            errors: function(e) {
                alert(e);
            }
        });
    });
</script>