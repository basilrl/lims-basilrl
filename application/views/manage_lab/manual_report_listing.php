<?php
$checkUser = $this->session->userdata('user_data');
$this->user = $checkUser->uidnr_admin;
$user = $this->user = $checkUser->uidnr_admin; ?>
<script src="<?php echo base_url(); ?>ckeditor/ckeditor.js"></script>
<div class="content-wrapper">
    <section class="content-header">
        <section class="content">
            <div class="container-fluid">
                <h3 class="text-center font-weight-bold text-primary">MANUAL REPORT LIST</h3>
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="row mb-3">
                                    <div class="col-sm-4">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <input type="text" id="search_gc" class="form-control form-control-sm" value="<?php echo ($search_gc != 'NULL') ? $search_gc : ''; ?>" placeholder="SEARCH BY Basil Report NO...">
                                            </div>
                                            <div class="col-sm-6">
                                                <input type="text" id="search_trf" class="form-control form-control-sm" value="<?php echo ($search_trf != 'NULL') ? $search_trf : ''; ?>" placeholder="SEARCH BY TRF NO...">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <input type="text" class="form-control form-control-sm applicant_name" placeholder="ENTER APPLICANT NAME" value="<?php echo ($applicant_name != 'NULL') ? strtoupper($applicant_name) : '' ?>">
                                        <input type="hidden" class="applicant_id" id="applicant_id" value="<?php echo ($applicant_id != 'NULL') ? $applicant_id : ''; ?>">
                                        <ul class="list-group-item customer_list" style="display:none">
                                        </ul>
                                    </div>
                                    <div class="col-sm-3">
                                        <input type="text" id="" class="form-control form-control-sm product_type" placeholder="ENTER PRODUCT NAME" value="<?php echo ($product_name != 'NULL') ? strtoupper($product_name) : '' ?>">
                                        <input type="hidden" class="product_id" id="product_id" value="<?php echo ($product_id != 'NULL') ? $product_id : ''; ?>">
                                        <ul class="list-group-item cat_list" style="display:none">
                                        </ul>
                                    </div>

                                    <div class="col-sm-2 text-center">
                                        <a class="btn btn-primary btn-sm" onclick="filter_by()" href="javascript:void(0);">SUBMIT</a>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-2">
                                        <select class="form-control form-control-sm" name="" id="divison">
                                            <option value="">SELECT DIVISION</option>
                                            <?php foreach ($divisions as $key => $value) { ?>
                                                <option <?php echo ($divison > 0) ? (($divison == $value->division_id) ? 'SELECTED' : '') : '' ?> value="<?php echo $value->division_id; ?>"><?php echo $value->division_name; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="col-sm-3">
                                        <input type="text" class="form-control form-control-sm buyer_name_type" placeholder="ENTER BUYER NAME" value="<?php echo ($buyer_name != 'NULL') ? strtoupper($buyer_name) : '' ?>">
                                        <input type="hidden" class="buyer_id" id="buyer_id" value="<?php echo ($buyer_id != 'NULL') ? $buyer_id : ''; ?>">
                                        <ul class="list-group-item buyer_customer_list" style="display:none">
                                        </ul>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="row">
                                            <div class="col-sm-6 text-center">
                                                <input id="start_date" value="<?php echo ($start_date != 'NULL') ? $start_date : ''; ?>" placeholder="START DATE" type="text" onfocus="(this.type='date')" class="form-control form-control-sm">
                                            </div>
                                            <div class="col-sm-6 text-center">
                                                <input id="end_date" placeholder="END DATE" value="<?php echo ($end_date != 'NULL') ? $end_date : ''; ?>" type="text" onfocus="(this.type='date')" class="form-control form-control-sm">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-12 text-center"><label for="">DATE FILTER BY RECIEVED DATE</label></div>
                                        </div>
                                    </div>
                                    <div class="col-sm-2">

                                    </div>
                                    <div class="col-sm-2 text-center">
                                        <a class="btn btn-danger btn-sm" href="<?php echo base_url('Manage_lab/manual_report_listing'); ?>">CLEAR</a>
                                    </div>
                                </div>
                                <div class="row">
                                    <?php if (exist_val('Manage_lab/release_to_client_all_report', $this->session->userdata('permission'))) { ?>
                                        <button type="button" class="btn btn-sm btn-success release_all_to_client_report" title="RELEASE ALL TO CLIENT">RELEASE</button>
                                    <?php  } ?>
                                </div>
                            </div>
                            <div class="card-body p-0">
                                <table class="table table-sm table-hovered text-center text-secondary">
                                    <thead class="thead-light">
                                        <tr>
                                            <?php if (exist_val('Manage_lab/release_to_client_all_report', $this->session->userdata('permission'))) { ?>
                                                <th>
                                                    <?php
                                                    if (!$this->session->has_userdata('release_all_report')) {
                                                        $this->session->set_userdata('release_all_report', 0);
                                                    }
                                                    ?>
                                                    Select All <input type="checkbox" class="check_all_release_report" value="0" <?php echo (($this->session->userdata('release_all_report') == '1') ? "checked" : "") ?>>

                                                </th>
                                            <?php } ?>

                                            <th>S.No.</th>
                                            <th>Basil Report Number</th>
                                            <th>Report Number</th>
                                            <th>Client</th>
                                            <th>Sample Description</th>
                                            <th>Product</th>
                                            <th>TRF Reference No.</th>
                                            <th>Quantity</th>
                                            <th>Recieved Date</th>
                                            <th>Status</th>
                                            <th>Service Type</th>
                                            <th>Due Date</th>
                                            <?php if ((exist_val('Manage_lab/Release_to_client', $this->session->userdata('permission')))) { ?>
                                                <th>Action</th>
                                            <?php } ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php (empty($this->uri->segment(11))) ? $i = 1 : $i = $this->uri->segment(11) + 1; ?>
                                        <?php if ($report_listing || $report_listing != NULL) { ?> <?php foreach ($report_listing as $RS) : ?>
                                                <tr>
                                                    <?php if (exist_val('Manage_lab/release_to_client_all_report', $this->session->userdata('permission'))) { ?>
                                                        <td>
                                                            <?php if ($RS->status == "Report Approved" && $RS->released_to_client < 1) { ?>

                                                                <input <?php echo ((in_array($RS->sample_reg_id, (($this->session->has_userdata('release_id_report')) ? $this->session->userdata('release_id_report') : []))) ? 'checked' : "") ?> type="checkbox" value="<?php echo $RS->sample_reg_id ?>" class="realeased_to_check_report">
                                                            <?php } ?>
                                                        </td>
                                                    <?php }  ?>

                                                    <!-- end -->
                                                    <td><?php echo $i; ?></td>
                                                    <td><?php echo $RS->gc_no; ?></td>
                                                    <td><?php echo $RS->report_num; ?></td>
                                                    <td><?php echo $RS->client; ?></td>
                                                    <td><?php echo $RS->sample_desc; ?></td>
                                                    <td><?php echo $RS->product_name; ?></td>
                                                    <td><?php echo $RS->trf_ref_no; ?></td>
                                                    <td><?php echo $RS->qty_received; ?></td>
                                                    <td><?php echo change_time($RS->received_date, $this->session->userdata('timezone')); ?></td>
                                                    <td><?php echo $RS->status; ?></td>
                                                    <td><?php echo $RS->sample_service_type; ?></td>
                                                    <td><?php echo $RS->due_date; ?></td>
                                                    <td>
                                                        <?php if ($RS->trf_buyer != 0 && $RS->buyer_active == 'Active') { if ($RS->status != 'Hold Sample') { ?>
                                                            <?php if (exist_val('Manage_lab/Release_to_client', $this->session->userdata('permission'))) { ?>
                                                                <?php if ($RS->released_to_client == "0") { ?>
                                                                    <a class="release_to_client" href="javascript:void(0)" target="" data-id="<?php echo $RS->sample_reg_id; ?>" data-report-id="<?php echo $RS->report_id; ?>" data-bs-toggle="modal" data-bs-target="#release_to_client"><img src="<?php echo base_url(); ?>assets/images/downarrow.png" alt="" title="Release to Client"></a>
                                                                <?php } ?>
                                                            <?php } ?>
                                                            <?php } } else { ?>
                                                            <?php if (exist_val('Manage_lab/Release_to_client', $this->session->userdata('permission'))) { ?>
                                                                <?php if ($RS->released_to_client == "0") { ?>
                                                                    <a class="release_to_client" href="javascript:void(0)" target="" data-id="<?php echo $RS->sample_reg_id; ?>" data-report-id="<?php echo $RS->report_id; ?>" data-bs-toggle="modal" data-bs-target="#release_to_client"><img src="<?php echo base_url(); ?>assets/images/downarrow.png" alt="" title="Release to Client"></a>
                                                                <?php } ?>
                                                            <?php } ?>
                                                        <?php } ?>
                                                        <a href="javascript:void(0)" data-id="<?php echo $RS->sample_reg_id; ?>" class="log_view" data-bs-toggle='modal' data-bs-target='#exampleModal' class="btn btn-sm" title="Log View"><img src="<?php echo base_url('assets/images/log-view.png'); ?>" alt="Log view" width="20px"></a>
                                                    </td>
                                                </tr>
                                                <?php $i++; endforeach; ?><?php } else { ?>
                                                <tr>
                                                    <td> <?php echo "NO RECORD FIND"; } ?></td>
                                                </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="card-footer">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <?php echo $links; ?>
                                    </div>
                                    <div class="col-sm-6">
                                        <?php echo $result_count; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </section>
</div>

<div class="modal fade" tabindex="-1" role="dialog" id="release_to_client" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content modal-md" style="margin: 0 auto;">
            <div class="modal-header" style="background-color:darkblue;color:white;height: 50px;">
                <h5 class="modal-title"><b>RELEASE TO CLIENT</b></h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" style="color:white;">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="post" name="release_to_client" action="<?php echo base_url('Manage_lab/Release_to_client'); ?>" id="release_to_client">
                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                    <input type="hidden" value="" class="sample_reg_id" name="sample_reg_id">
                    <input type="hidden" value="" class="report_ids" name="report_id">
                    <input type="hidden" value="" class="type_report" name="type_report">
                    <div class="row">
                        <div class="col-md-12">
                            <label for="">To</label>
                            <input type="text" name="to" class="form-control email_to">
                            <label for="">CC</label>
                            <input type="test" name="cc" class="form-control">
                            <label for="">Bcc</label>
                            <input type="text" name="bcc" class="form-control">
                            <label for="">Subject</label>
                            <textarea name="subject" id="" cols="30" rows="2" class="form-control">Report Release To Client</textarea>
                            <label for="">Body</label>
                            <textarea name="email_body" id="" cols="30" rows="10" class="form-control ckeditor">
                                <table width="100%" border="0" cellspacing="5" cellpadding="5" style="border-collapse:collapse; font-family:Arial, Helvetica, sans-serif;font-size:12px;">
                                    <tr><td colspan="2" style="background-color:#336699"><img src=" <?php echo base_url(); ?>public/img/logo/geo-logo.png" height="53"/></td></tr>
                                    <tr> <td colspan="2"><b>  Dear Sir/Madam,</b></td></tr>

                                    <tr> <td colspan="2">This is an auto-generated notification that your Report has been Released</td></tr>

                                    <tr> <td colspan="2">Please find the attached report below:</td></tr>
                                    <tr> <td colspan="2"> Thanks & Regards</td> </tr>
                                    <tr> <td colspan="2">GEO-CHEM</td> </tr>
                                    <tr>
                                    <td align="left" style="background-color:#D5E2F2">Geo Chem Consumer Products Services</td>
                                    <td align="right" style="background-color:#D5E2F2">GLIMS - Online Lab Information System</td>
                                    </tr>
                                </table>
                            </textarea>
                            <label for="">Do You Want To Send Mail Or Not?</label> <br>
                            <label for="">Yes</label>
                            <input type="radio" name="mail" id="mail" value="1">
                            <label for="">No</label>
                            <input type="radio" name="mail" id="mail" value="0">
                        </div>
                    </div>


            </div>
            <div class="modal-footer" style="background-color:darkblue;color:white;height: 50px;">
                <button type="submit" data-id="" class="btn btn-success " style="margin-top: -10px;"><b>SEND</b></button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="margin-top: -10px;"><b>NO</b></button>
            </div>
            </form>
        </div>
    </div>
</div>
<!-- end -->


</div>
</div>
<script>
    $(document).ready(function() {
        CKEDITOR.replaceClass = 'ckeditor';
    });
</script>
<script>
    $('document').ready(function() {
        const _tokken = $('meta[name="_tokken"]').attr('value');

        $(document).on('click', '.release_to_client', function() {
            var sample_reg_id = $(this).attr('data-id');
            var report_id = $(this).attr('data-report-id');
            $('.sample_reg_id').val(sample_reg_id);
            $('.report_ids').val(report_id);
            $.ajax({
                url: "<?php echo base_url('Manage_lab/manual_release_to_client_data') ?>",
                method: "post",
                data: {
                    _tokken: _tokken,
                    sample_reg_id: sample_reg_id,
                },
                success: function(data) {
                    var result = $.parseJSON(data);
                    if (result) {
                        $('.email_to').val(result.email);
                        $('.type_report').val(result.report_type);
                    } 
                }
            })
        })

    });
</script>

<script>
    function filter_by() {
        var base_url = '<?php echo base_url('Manage_lab/manual_report_listing/'); ?>';
        var applicant = $('#applicant_id').val();
        var buyer_id = $('#buyer_id').val();
        var product = $('#product_id').val();
        var divison = $('#divison').val();
        var search_gc = $('#search_gc').val();
        var search_trf = $('#search_trf').val();
        var start_date = $('#start_date').val();
        var end_date = $('#end_date').val();
        // var status = $('#status').val();
        base_url += ((applicant) ? applicant : 'NULL');
        base_url += '/' + ((buyer_id) ? buyer_id : 'NULL');
        base_url += '/' + ((divison) ? divison : 'NULL');
        base_url += '/' + ((product) ? product : 'NULL');
        base_url += '/' + ((search_gc) ? btoa(search_gc) : 'NULL');
        base_url += '/' + ((search_trf) ? btoa(search_trf) : 'NULL');
        base_url += '/' + ((start_date) ? start_date : 'NULL');
        base_url += '/' + ((end_date) ? end_date : 'NULL');
        // base_url += '/' + ((status) ? btoa(status) : 'NULL');
        location.href = base_url;
    }
    var css = {
        position: "absolute",
        width: "95%",
        "font-size": "12px",
        "z-index": 999,
        "overflow-y": "auto",
        "overflow-x": "hidden",
        "max-height": "200px",
        cursor: "pointer",
    };
    var base_url = $("body").attr("data-url");
    getAutolist(
        "applicant_id",
        "applicant_name",
        "customer_list",
        "customer_li",
        "",
        "customer_name",
        "customer_id as id,customer_name as name",
        "cust_customers"
    );
    getAutolist(
        "buyer_id",
        "buyer_name",
        "buyer_customer_list",
        "customer_li",
        "",
        "customer_name",
        "customer_id as id,customer_name as name",
        "cust_customers"
    );
    getAutolist(
        "product_id",
        "product_type",
        "cat_list",
        "sample_li",
        "",
        "sample_type_name",
        "sample_type_id as id,sample_type_name as name",
        "mst_sample_types"
    );

    function getAutolist(hide_input, input, ul, li, where, like, select, table) {

        var base_url = $("body").attr("data-url");
        var hide_inputEvent = $("input." + hide_input);
        var inputEvent = $("input." + input);
        var ulEvent = $("ul." + ul);

        inputEvent.focusout(function() {
            ulEvent.fadeOut();
        });

        inputEvent.on("click keyup", function(e) {
            var me = $(this);
            var key = $(this).val();
            var _URL = base_url + "get_auto_list";
            const _tokken = $('meta[name="_tokken"]').attr("value");
            e.preventDefault();
            if (key) {
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
                                ulEvent.append(
                                    $(
                                        '<li class="list-group-item ' +
                                        li +
                                        '"' +
                                        "data-id=" +
                                        value.id +
                                        ">" +
                                        value.name +
                                        "</li>"
                                    )
                                );
                            });
                        } else {
                            ulEvent.append(
                                $(
                                    '<li class="list-group-item ' +
                                    li +
                                    '"' +
                                    'data-id="">NO REORD FOUND</li>'
                                )
                            );
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

            } else {
                hide_inputEvent.val('');
            }
        });
    }
</script>

<!-- Ajax call to remove report image -->
<script>
    const _tokken = $('meta[name="_tokken"]').attr('value');

    // ajit code start release to client
    $(document).ready(function() {
        // added by ajit 30-03-2021
        $('.check_all_release_report').on('change', function() {
            var check_element = $('.realeased_to_check_report');
            if (this.checked) {
                make_session(null, true, true);

                $.each(check_element, function(ind, pak) {

                    $(pak).attr('checked', true);
                })
            } else {
                make_session(null, false, false);
                $.each(check_element, function(ind, pak) {

                    $(pak).attr('checked', false);
                })
            }
        });

        $(document).on('change', '.realeased_to_check_report', function() {
            sample_reg_id = $(this).val();
            if (this.checked) {
                make_session(sample_reg_id, true, false);
            } else {
                make_session(sample_reg_id, false, false);
                $('.check_all_release_report').attr('checked', false);
            }
        })


        function make_session(sample_reg_id = null, status, chek_all) {
            const _tokken = $('meta[name="_tokken"]').attr('value');
            $.ajax({
                url: "<?php echo base_url('Manage_lab/make_session_report') ?>",
                method: "POST",
                data: {
                    sample_reg_id: sample_reg_id,
                    _tokken: _tokken,
                    status: status,
                    chek_all: chek_all
                }
            });
        }


        // release all code
        $('.release_all_to_client_report').on('click', function() {
            var check = $('.check_all_release_report').val();

            const _tokken = $('meta[name="_tokken"]').attr('value');
            $.ajax({
                url: "<?php echo base_url('Manage_lab/release_to_client_all_report') ?>",
                method: "POST",
                data: {
                    _tokken: _tokken
                },
                success: function(data) {
                    var data = $.parseJSON(data);
                    if (data.status > 0) {
                        $.notify(data.msg, 'success');

                    } else {
                        $.notify(data.msg, 'error');
                    }
                    window.location.reload();
                }
            });


        })


        // end
    })
    // end
</script>

<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Sample log</h5>
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
                            <th>Old Status</th>
                            <th>New Status</th>
                            <th>Performed By</th>
                            <th>Performed at</th>
                        </tr>
                    </thead>
                    <tbody id="sample_log"></tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- added by saurabh on 23-03-2021 -->
<script>
    $(document).ready(function() {
        const url = $('body').data('url');
        const _tokken = $('meta[name="_tokken"]').attr('value');
        // Ajax call to get log
        $('.log_view').click(function() {
            $('#sample_log').empty();
            var sample_id = $(this).data('id');
            $.ajax({
                type: 'post',
                url: url + 'SampleRegistration_Controller/get_sample_log',
                data: {
                    _tokken: _tokken,
                    sample_id: sample_id
                },
                success: function(data) {
                    var data = $.parseJSON(data);
                    var value = '';
                    sno = Number();
                    $.each(data, function(i, v) {
                        sno += 1;
                        var operation = v.operation;
                        var old_status = v.old_status;
                        var new_status = v.new_status;
                        var taken_by = v.taken_by;
                        var taken_at = new Date(v.taken_at + ' UTC');
                        value += '<tr>';
                        value += '<td>' + sno + '</td>';
                        value += '<td>' + operation + '</td>';
                        value += '<td>' + old_status + '</td>';
                        value += '<td>' + new_status + '</td>';
                        value += '<td>' + taken_by + '</td>';
                        value += '<td>' + taken_at.toLocaleString() + '</td>';
                        value += '</tr>';

                    });
                    $('#sample_log').append(value);
                }
            });
        });
        // ajax call to get log ends here
    });
</script>
<!-- added by saurabh on 23-03-2021 -->