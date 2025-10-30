<script src="<?php echo base_url('ckeditor/ckeditor.js'); ?>"></script>

<div class="content-wrapper">
    <section class="content-header">
        <!-- container fluid start -->
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h1>NEWS FLASH</h1>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-sm-6">
                                    <?php if (exist_val('NewsFlash/add_newsflash', $this->session->userdata('permission'))) { ?>
                                        <!-- added by millan on 19-02-2021 -->
                                        <a href="" class="btn btn-primary btn-rounded" data-bs-toggle="modal" data-bs-target="#modalLoginForm">ADD</a>
                                    <?php } ?>

                                    <?php if (exist_val('NewsFlash/add_newsletter_dls', $this->session->userdata('permission'))) { ?>
                                        <!-- added by millan on 07-05-2021 -->
                                        <a href="" title="Add NewsLetter" class="btn btn-primary btn-rounded ml-4" data-bs-toggle="modal" data-bs-target="#newsletterform">ADD NEWSLETTER</a>
                                    <?php } ?>

                                    <?php if (exist_val('NewsFlash/get_nsl_data', $this->session->userdata('permission'))) { ?>
                                        <!-- added by millan on 07-05-2021 -->
                                        <a href="" title="View NewsLetter" class="btn btn-primary btn-rounded ml-4 nsl_mod" data-bs-toggle="modal" data-bs-target="#nwsltrview">VIEW NEWSLETTER</a>
                                    <?php } ?>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-8">
                                    <div class="row">
                                        <?php if ($title_news) {
                                            $title = $title_news;
                                        } else {
                                            $title = 0;
                                        } ?>
                                        <div class="col-sm-6">
                                            <select name="title" id="title" class="form-control form-control-sm">
                                                <option value="">Select Title</option>
                                                <?php foreach ($titles as $name) { ?>
                                                    <option value="<?php echo $name->news_id; ?>" <?php echo ($title == $name->news_id) ? "selected" : ""; ?>> <?php echo $name->title; ?> </option>
                                                <?php } ?>
                                            </select>
                                        </div>

                                        <div class="col-sm-2">
                                            <select name="" id="" class="form-control form-control-sm status">
                                                <option value="">Status</option>
                                                <option value="1" <?php echo ($status == '1') ? "selected" : ""; ?>>Active</option>
                                                <option value="0" <?php echo ($status == '0') ? "selected" : ""; ?>>Deactive</option>
                                            </select>
                                        </div>

                                        <?php if ($created_pesron) {
                                            $uidnr_admin = $created_pesron;
                                        } else {
                                            $uidnr_admin = "";
                                        } ?>
                                        <div class="col-sm-4">
                                            <select name="created_by" id="created_by" class="form-control form-control-sm">
                                                <option value="">Select Created By</option>
                                                <?php foreach ($created_by_name as $cr_name) { ?>
                                                    <option value="<?php echo $cr_name->uidnr_admin; ?>" <?php echo ($uidnr_admin == $cr_name->uidnr_admin) ? "selected" : ""; ?>> <?php echo $cr_name->created_by; ?> </option>
                                                <?php } ?>
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
                                    <a class="btn btn-sm btn-default" href="<?php echo base_url('NewsFlash'); ?>" title="Clear">
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
                                        if ($title_news != "") {
                                            $title_news = base64_encode($title_news);
                                        } else {
                                            $title_news = "NULL";
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
                                        <th scope="col"><a href="<?php echo base_url('NewsFlash/index/' . $title_news . '/' . $created_pesron . '/' . $status . '/' . $search . '/' . 'mnf.title' . '/' . $order) ?>">TITLE</a></th>
                                        <?php
                                        if ((exist_val('NewsFlash/newsflash_status', $this->session->userdata('permission')))) { ?>
                                            <th scope="col">STATUS</th>
                                        <?php
                                        } ?>

                                        <th scope="col"><a href="<?php echo base_url('NewsFlash/index/' . $title_news . '/' . $created_pesron . '/' . $status . '/' . $search . '/' . 'ap.admin_fname' . '/' . $order) ?>">CREATED BY</a></th>
                                        <th scope="col"><a href="<?php echo base_url('NewsFlash/index/' . $title_news . '/' . $created_pesron . '/' . $status . '/' . $search . '/' . 'mnf.created_date' . '/' . $order) ?>">CREATED ON</a></th>
                                        <?php
                                        if ((exist_val('NewsFlash/fetch_newsflash_for_edit', $this->session->userdata('permission'))) || (exist_val('RegulationConfiguration/get_user_log_data', $this->session->userdata('permission')))) { ?>
                                            <th scope="col">ACTION</th>
                                        <?php
                                        } ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $sn = $this->uri->segment('9') + 1;
                                    if ($newsflash_list) {
                                        foreach ($newsflash_list as $key => $value) {
                                           
                                            ?>
                                            <tr>
                                                <td><?php echo $sn; ?></td>
                                                <td><?php echo $value->title ?></td>
                                                <?php
                                                if ((exist_val('NewsFlash/newsflash_status', $this->session->userdata('permission')))) { ?>
                                                    <?php if ($value->status == 1) { ?>
                                                        <td><span class="btn btn-success status_chng" id="active" data-id="<?php echo $value->news_id; ?>">Active</span></td>
                                                    <?php } else { ?>
                                                        <td><span class="btn btn-danger status_chng" id="deactive" data-id="<?php echo $value->news_id; ?>">Deactive</span></td>
                                                    <?php } ?>
                                                <?php
                                                } ?>
                                                <td><?php echo $value->admin_fname ?></td>
                                                <td><?php echo change_time($value->created_date, $this->session->userdata('timezone')); ?></td>
                                                <td>
                                                    <?php
                                                    if (exist_val('NewsFlash/fetch_newsflash_for_edit', $this->session->userdata('permission'))) { ?>
                                                        <!-- added by millan on 19-02-2021 -->
                                                        <a href="#" class="btn btn-circle btn-sm editcls" data-bs-toggle="modal" data-bs-target="#EditRoleForm" data-id="<?php echo $value->news_id; ?>">
                                                            <img src="<?php echo base_url('assets/images/mem_edit.png') ?>" alt="Edit News Flash">
                                                        </a>
                                                    <?php
                                                    } ?>

                                                    <?php
                                                    if (exist_val('NewsFlash/delete_newsflash', $this->session->userdata('permission'))) { ?>
                                                        <!-- added by millan on 19-02-2021 -->
                                                        <a href="<?php echo base_url(); ?>NewsFlash/delete_newsflash?news_id=<?php echo $value->news_id; ?>" onclick="if (! confirm('Are you Sure Want To Delete News Flash ?')) { return false; }">
                                                            <button class="btn btn-danger btn-circle btn-sm "><i class="fa fa-times"></i></button>
                                                        </a>
                                                    <?php
                                                    } ?>


                                                    <?php
                                                    if (exist_val('NewsFlash/get_log_data', $this->session->userdata('permission'))) { ?>
                                                        <a href="javascript:void(0)" data-id="<?php echo $value->news_id; ?>" class="log_view" data-bs-toggle='modal' data-bs-target='#lo_view_target' class="btn btn-sm" title="Log View"><img src="<?php echo base_url('assets/images/log-view.png'); ?>" alt="Log view" width="20px"></a>
                                                    <?php
                                                    } ?>
                                                        
                                                        <?php if (!empty($value->aws_path)) { ?>
                                                                <a href="<?php echo $value->aws_path; ?>" target="_blank"><i class="fa fa-image" aria-hidden="true"></i></a>
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
                <?php if ($newsflash_list && count($newsflash_list) > 0) { ?>
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


<!-- bootstrap modal for add NEWSFLASH -->
<div class="modal fade" id="modalLoginForm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content modal-md">
            <div class="modal-header text-left">
                <h6 class="modal-title w-100 font-weight-bold">Add NewsFlash</h6>
                <button class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="newsflash_add" action="javascript:void(0);" enctype="multipart/form-data">
                <input type="hidden" class="txt_csrfname" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
                <div class="modal-body mx-3">
                    <div class="md-form mb-5">
                        <label data-error="wrong" data-success="right" for="title">Title</label>
                        <input type="text" id="title" name="title" class="form-control validate" placeholder="Mention Title">
                    </div>
                    <div class="md-form mb-5">
                        <label data-error="wrong" data-success="right" for="status">Status</label>
                        <select class="form-control" id="status" name="status" aria-invalid="false" aria-required="true">
                            <option value="">Choose Status</option>
                            <option value="1">Active</option>
                            <option value="0">In-Active</option>
                        </select>
                    </div>
                    
                    <div class="">
                        <label>Upload Image</label>
                        <input type="file" name="images" class="form-control form-control-sm">
                    </div>
                    
                    <div class="md-form mb-5">
                        <label data-error="wrong" data-success="right" for="content">Content</label>
                        <textarea class="form-control ckeditor-all content_add" name="content" placeholder="Enter Content..." rows="5" cols="50" id="content_add"></textarea>
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button class="btn btn-success">Add</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- bootstrap modal for add NEWSFLASH ENDS -->

<!-- bootstrap modal for EDIT NEWSFLASH -->
<div class="modal fade" id="EditRoleForm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content modal-md">
            <div class="modal-header text-left">
                <h6 class="modal-title w-100 font-weight-bold ">Edit NewsFlash Details</h6>
                <button class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="newsflash_update" action="<?php echo base_url(); ?>" method="post">
                <input type="hidden" class="txt_csrfname" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
                <input type="hidden" name="news_id" class="form-control validate" id="news_id" value="">
                <div class="modal-body mx-3">
                    <div class="md-form mb-5">
                        <label data-error="wrong" data-success="right" for="title">Title</label>
                        <input type="text" id="title_edit" name="title" class="form-control validate" placeholder="Mention Title">
                    </div>
                    <div class="md-form mb-5">
                        <label data-error="wrong" data-success="right" for="content">Content</label>
                        <textarea class="form-control ckeditor-all content_edit" name="content" placeholder="Enter Content..." rows="5" cols="50" id="content"></textarea>
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button type="submit" class="btn btn-success">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- bootstrap modal for EDIT NEWSFLASH ENDS -->

<!-- modal for add NEWSLETTER -->
<div class="modal fade" id="newsletterform" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content modal-md">
            <div class="modal-header text-left">
                <h6 class="modal-title w-100 font-weight-bold">Add NewsLetter</h6>
                <button class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="add_newsletter" action="javascript:void(0);">
                <input type="hidden" class="txt_csrfname" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <label data-error="wrong" data-success="right" for="title"> <span style="font-size:14px; font-weight: bold;"> Title </span> </label>
                            <input type="title" id="title" name="title" class="form-control validate" placeholder="Mention Title...">
                        </div>
                        <div class="col-sm-6">
                            <label data-error="wrong" data-success="right" for="subject"> <span style="font-size:14px; font-weight: bold;"> Subject </span> </label>
                            <input type="subject" id="subject" name="subject" class="form-control validate" placeholder="Mention Subject...">
                        </div>
                        <div class="col-sm-12">
                            <label data-error="wrong" data-success="right" for="content_desc"> <span style="font-size:14px; font-weight: bold;"> Content </span> </label>
                            <textarea class="form-control ckeditor-all content_desc" name="content_desc" placeholder="Enter Content..." rows="5" cols="50" id="content_desc"></textarea> <br>

                            <label data-error="wrong" data-success="right" for="nsl_img"> <span style="font-size:14px; font-weight: bold;"> NewsLetter Image </span> </label>
                            <input type="file" name="nsl_img[]" id="nsl_img" multiple accept="image/png, image/jpeg, image/jpg">
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
<!-- bootstrap modal for add NEWSLETTER ENDS -->

<!-- modal for view NEWSLETTER & send mail -->
<div class="modal fade" id="nwsltrview" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content modal-md">
            <div class="modal-header text-left">
                <h6 class="modal-title w-100 font-weight-bold">NEWSLETTER DETAILS</h6>
                <button class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="nsl_view" action="javascript:void(0);">
                <input type="hidden" class="txt_csrfname" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
                <input type="hidden" id="nsl_content_id" name="nsl_content_id" value="">
                <div class="modal-body mx-3">
                    <table class="table table-bordered table-sm">
                        <tbody id="dsp_con"></tbody>
                    </table>
                    <table class="table table-bordered table-sm">
                        <tbody id="mail_options"> </tbody>
                    </table>
                    <!-- <div class="row">
                        <div class="col-sm-12">
                            <input required type="radio" class="nsl_mail" name="mail" value="mtc">
                            <label for="mtc"> <span style="font-weight: bold; font-size:14px"> Mail to Client </span> </label>
                            <input type="radio" class="nsl_mail" name="mail" value="stf">
                            <label for="stf"> <span style="font-weight: bold; font-size:14px"> Mail to Staff </span> </label>
                        </div>
                    </div> -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" onclick="if (! confirm('Are you Sure Want To Send Mail ?')) { return false; }"> <i class="fa fa-envelope"></i> Send Mail</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- bootstrap modal for add NEWSLETTER & send mail ENDS -->

<script>
    $(document).ready(function() {
        var style = {
            "margin": "0 auto"
        };
        $('.modal-content').css(style);

        $('.status_chng').click(function() {
            var news_id = $(this).attr("data-id");
            var _tokken = $('meta[name="_tokken"]').attr('value');
            $.ajax({
                url: "<?php echo base_url(); ?>NewsFlash/newsflash_status",
                data: {
                    "news_id": news_id,
                    "_tokken": _tokken
                },
                type: 'post',
                success: function(result) {
                    location.reload();
                }
            });
        });
    });

    $('#newsflash_add').on('submit', function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        formData.append('content_add', CKEDITOR.instances['content_add'].getData());
        $.ajax({
            url: "<?php echo base_url('NewsFlash/add_newsflash') ?>",
            method: "POST",
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                var result = $.parseJSON(response);
                if (result.status > 0) {
                    $('#modalLoginForm').modal('hide');
                    $("#newsflash_add").trigger('reset');
                    window.location.reload();
                } else {
                    $.notify(result.msg, 'error');
                }
                if (result.errors) {
                    var error = result.errors;
                    $('.form_errors').remove();
                    $.each(error, function(i, v) {
                        $('#newsflash_add select[name="' + i + '"]').after('<span class="text-danger form_errors">' + v + '</span>');
                        $('#newsflash_add input[name="' + i + '"]').after('<span class="text-danger form_errors">' + v + '</span>');
                        $('#newsflash_add textarea[name="' + i + '"]').after('<span class="text-danger form_errors">' + v + '</span>');
                    });

                } else {
                    $('.form_errors').remove();
                }
            }
        });
        return false;
    });

    $(document).on("click", ".editcls", function() {
        var news_id = $(this).data('id');
        var _tokken = $('meta[name="_tokken"]').attr('value');
        $.ajax({
            url: "<?php echo base_url(); ?>NewsFlash/fetch_newsflash_for_edit",
            data: {
                "news_id": news_id,
                _tokken: _tokken
            },
            type: 'post',
            success: function(result) {
                var data = $.parseJSON(result);
                $('#EditRoleForm #news_id').val(data.news_id);
                $('#EditRoleForm #title_edit').val(data.title);
                // $('#content').html(data.content);
                CKEDITOR.instances['content'].setData(data.content);

            }
        });
    });

    $('#newsflash_update').on('submit', function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        formData.append('content', CKEDITOR.instances['content'].getData());
        $.ajax({
            url: "<?php echo base_url('NewsFlash/update_newsflash') ?>",
            method: "POST",
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                var result = $.parseJSON(response);
                if (result.status > 0) {
                    $('#EditRoleForm').modal('hide');
                    $("#newsflash_update").trigger('reset');
                    window.location.reload();
                } else {
                    $.notify(result.msg, 'error');
                }
                if (result.errors) {
                    var error = result.errors;
                    $('.form_errors').remove();
                    $.each(error, function(i, v) {
                        $('#newsflash_update input[name="' + i + '"]').after('<span class="text-danger form_errors">' + v + '</span>');
                        $('#newsflash_update textarea[name="' + i + '"]').after('<span class="text-danger form_errors">' + v + '</span>');
                    });

                } else {
                    $('.form_errors').remove();
                }
            }
        });
        return false;
    });

    // add newsletter details or  image added by millan on 07-05-2021
    $('#add_newsletter').on('submit', function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        formData.append('content_desc', CKEDITOR.instances['content_desc'].getData());
        $.ajax({
            url: "<?php echo base_url('NewsFlash/add_newsletter_dls') ?>",
            method: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                $('.form_errors').remove();
                var result = $.parseJSON(response);
                if (result.status > 0) {
                    $('#newsletterform').modal('hide');
                    $("#add_newsletter").trigger('reset');
                    window.location.reload();
                } else {
                    $.notify(result.msg, 'error');
                }
                if (result.errors) {
                    var error = result.errors;
                    $.each(error, function(i, v) {
                        $('#add_newsletter input[name="' + i + '"]').after('<span class="text-danger form_errors">' + v + '</span>');
                        $('#add_newsletter textarea[name="' + i + '"]').after('<span class="text-danger form_errors">' + v + '</span>');
                    });

                }
            }
        });
        // return false;
    });

    // showing newsletter content
    $('.nsl_mod').click(function(e) {
        e.preventDefault();
        const _tokken = $('meta[name="_tokken"]').attr("value");
        $.ajax({
            type: 'post',
            url: "<?php echo base_url('NewsFlash/get_nsl_data') ?>",
            data: {
                _tokken: _tokken
            },
            success: function(data) {
                $('#dsp_con').html('');
                var data = $.parseJSON(data);
                $('#nsl_view #nsl_content_id').val(data.nsl_content_id);
                if (data) {
                    img_path = data.image_path.split(",");
                    var value = '';
                    value += '';
                    if ((data.subject) != "") {
                        value += '<tr> <th> Subject </th> <td>' + data.subject + '</td> </tr>'
                    };
                    if ((data.content_desc) != "") {
                        value += '<tr> <th> Content </th> <td>' + data.content_desc + '</td> </tr>'
                    };
                    $.each(img_path, function(ind, val) {
                        if ((val) != "") {
                            value += '<tr> <th> View Image ' + (ind + 1) + ' </th> <td> <a href=" ' + val + ' " target="_blank" class="btn btn-success btn-rounded"> View </a> </td>  </tr>'
                        }
                    });
                    value += "";
                    value += '<tr> <th> <input required type="radio" class="nsl_mail" name="mail" value="mtc"> <label for="mtc"> <span style="font-weight: bold; font-size:14px"> Mail to Client </span> </label> </th> ';
                    value += '<th> <input type="radio" class="nsl_mail" name="mail" value="stf"> <label for="stf"> <span style="font-weight: bold; font-size:14px"> Mail to Staff </span> </label> </tr>';
                    $('#dsp_con').append(value);
                } else {
                    var value = '';
                    value += '<tr>';
                    value += '<td rowspan="4">';
                    value += "<h4> NO RECORD FOUND! </h4>";
                    value += "</td>";
                    value += "</tr>";
                    $('#dsp_con').append(value);
                }
            }
        });
    });
    //ends

    // mail to client 
    $('#nsl_view').submit(function(e) {
        var check = $('input[name="mail"]:checked').val();
        alert(check);
        if(check =="mtc"){
            var URL = "<?php echo base_url('NewsFlash/mail_data_client') ?>";
        }
        else{
            var URL = "<?php echo base_url('NewsFlash/send_newsletter_to_staff') ?>";
        }
        e.preventDefault();
        var formData = new FormData(this);
        $.ajax({
            url: URL,
            method: "post",
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                var result = $.parseJSON(response);
                $('#nwsltrview').modal('hide');
                window.location.reload();
            }
        })
    });
    //ends
</script>

<script>
    CKEDITOR.replaceAll('ckeditor-all');
</script>

<script>
    function searchfilter() {
        var url = '<?php echo base_url("NewsFlash/index"); ?>';
        var title_news = $('#title').val();
        if (title_news != "") {
            url = url + '/' + btoa(title_news);
        } else {
            title_news = "";
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
        const url = $('body').data('url');
        const _tokken = $('meta[name="_tokken"]').attr('value');
        // Ajax call to get log
        $('.log_view').click(function() {
            $('#table_log').empty();
            var id = $(this).data('id');
            $.ajax({
                type: 'post',
                url: url + 'NewsFlash/get_log_data',
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
                        var taken_at = new Date(v.taken_at + ' UTC');
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