<?php
$CI = &get_instance();
// $CI->load->library('library_name');
// $CI->library_name->My_Function();
$checkUser = $this->session->userdata('user_data');
$this->user = $checkUser->uidnr_admin;
$user = $this->user = $checkUser->uidnr_admin; ?>
<!-- <script src="<?php echo base_url(); ?>ckeditor/ckeditor.js"></script> -->
<div class="content-wrapper">
    <section class="content-header">
        <section class="content">
            <div class="container-fluid">
                <h3 class="text-center font-weight-bold text-primary">RELEASED REPORT LIST</h3>
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <input type="text" class="form-control form-control-sm applicant_name" placeholder="ENTER CLIENT NAME" value="<?php echo ($applicant_name != 'NULL') ? strtoupper($applicant_name) : '' ?>">
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
                                    <div class="col-sm-3">
                                        <input type="text" id="search" class="form-control form-control-sm" value="<?php echo ($search_url != 'NULL') ? $search_url : ''; ?>" placeholder="SEARCH BY GC/TRF NO...">
                                    </div>
                                    <div class="col-sm-3 text-center">
                                        <a class="btn btn-primary btn-sm" onclick="filter_by()" href="javascript:void(0);">SUBMIT</a>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
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
                                    <div class="col-sm-3">
                                        <!-- <select class="form-control form-control-sm" name="salesrep" id="status">
                                            <option value="">SELECT STATUS</option>
                                            <option value="Sample Accepted">Sample Accepted</option>
                                            <option value="Evaluation Completed">Evaluation Completed</option>
                                        </select> -->
                                    </div>
                                    <div class="col-sm-3 text-center">
                                        <a class="btn btn-danger btn-sm" href="<?php echo base_url('released_report/released_report_listing'); ?>">CLEAR</a>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body p-0">
                                <table class="table table-sm table-hovered text-center text-secondary">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>S.No.</th>
                                            <th>Basil Report No.</th>
                                            <th>Report Number</th>
                                            <th>Client</th>
                                            <th>Sample Description</th>
                                            <th>Product</th>
                                            <th>TRF Reference No.</th>
                                            <th>Quantity</th>
                                            <th>Recieved Date</th>
                                            <th>Status</th>
                                            <th>Report Type</th>
                                            <th>Service Type</th>
                                            <th>Due Date</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>


                                        <?php (empty($this->uri->segment(4))) ? $i = 1 : $i = $this->uri->segment(8) + 1; ?>

                                        <?php if ($report_listing || $report_listing != NULL) {
                                        ?> <?php foreach ($report_listing as $RS) : ?>
                                                <tr>
                                                    <td><?php echo $i; ?></td>
                                                    <td><?php echo $RS->gc_no; ?></td>
                                                    <td><?php echo $RS->report_num; ?></td>
                                                    <td><?php echo $RS->client; ?></td>
                                                    <td><?php echo $RS->sample_desc; ?></td>
                                                    <td><?php echo $RS->product_name; ?></td>
                                                    <td><?php echo $RS->trf_ref_no; ?></td>
                                                    <td><?php echo $RS->qty_received; ?></td>
                                                    <td><?php echo change_time($RS->received_date,$this->session->userdata('timezone')); ?></td>
                                                    <td><?php echo $RS->report_status; ?></td>
                                                    <td><?php echo $RS->report_type; ?></td>
                                                    <td><?php echo $RS->sample_service_type; ?></td>
                                                    <td><?php echo $RS->due_date; ?></td>
                                                    <td>
                                                    <?php if (exist_val('Released_report/report_view', $this->session->userdata('permission'))) { ?> <!-- added by Millan on 24-02-2021 -->    
                                                        <?php if ($RS->manual_report_file != '') { ?>
                                                            <?php if (substr($RS->manual_report_file, 0, 5) == 's3://') { ?>
                                                            <?php $path =  getS3Urlpath($RS->manual_report_file);
                                                            } else { ?>
                                                            <?php $path = $RS->manual_report_file;
                                                            } ?>
                                                            <a href="<?php echo $path; ?>" download="" target="_blank"><img src="<?php echo base_url(); ?>assets/images/view-lab-report-pdf.png" alt="" title="View Report"></a>
                                                        <?php } ?>
                                                    <?php } ?>
                                                    <?php if (exist_val('Released_report/view_worksheet', $this->session->userdata('permission'))) { ?> <!-- added by Millan on 24-02-2021 -->    
                                                        <?php if ($RS->manual_report_worksheet != '') { ?>
                                                            <?php if (substr($RS->manual_report_worksheet, 0, 5) == 's3://') { ?>
                                                            <?php $worksheet_path =  getS3Urlpath($RS->manual_report_worksheet);
                                                            } else { ?>
                                                            <?php $worksheet_path = $RS->manual_report_worksheet;
                                                            } ?>
                                                            <a href="<?php echo $worksheet_path; ?>" download="" target="_blank"><img src="<?php echo base_url(); ?>assets/images/worksheet.png" alt="" title="View Worksheet"></a>
                                                        <?php } ?>
                                                    <?php } ?>

                                                        <?php if($RS->revise_count < 3){ if ($RS->status == 'Report Approved' || $RS->status == 'Sample Sent for Manual Reporting' || $RS->status == 'Report Approved') { ?>
                                                            <?php if (exist_val('Manage_lab/regenerate_sample', $this->session->userdata('permission'))) { ?> <!-- added by Millan on 24-02-2021 -->    
                                                                <a class="regenerate_btn" href="javascript:void(0)" target="" data-id="<?php echo $RS->sample_reg_id; ?>" data-reports-id="<?php echo $RS->report_id; ?>" data-bs-toggle="modal" data-bs-target="#regenerate"><img src="<?php echo base_url(); ?>assets/images/retest2nd.png" alt="" title="Revise Report"></a>
                                                            <?php } ?>
                                                            <?php if (exist_val('Manage_lab/additional_test', $this->session->userdata('permission'))) { ?> <!-- added by Millan on 24-02-2021 --> 
                                                                <a class="additional" href="javascript:void(0)" target="" data-id="<?php echo $RS->sample_reg_id; ?>" data-report-id="<?php echo $RS->report_id; ?>" data-bs-toggle="modal" data-bs-target="#additional_test"><img src="<?php echo base_url(); ?>assets/images/Buyout.png" alt="" title="Additional Test"></a>
                                                            <?php } ?>
                                                        <?php } }?>
                                                        
                                                        <a href="javascript:void(0)" data-id="<?php echo $RS->sample_reg_id;?>" class="log_view" data-bs-toggle='modal' data-bs-target='#exampleModal' class="btn btn-sm" title="Log View"><img src="<?php echo base_url('assets/images/log-view.png'); ?>" alt="Log view" width="20px"></a>
                                                        <?php if (exist_val('Released_report/send_report_mail', $this->session->userdata('permission'))) { 
                                    if($RS->report_status != 'Report Released To Client'){
                                    ?>
                                      <a href="<?php echo base_url('Released_report/send_report_mail/' . $RS->sample_reg_id.'/'.$RS->report_id) ?>" title="Sample Report Mail" onclick="return confirm('Are you sure want to send Report mail !.')"><img src="<?php echo base_url('assets/images/send_email.png') ?>" alt="Send Email"></a>
                                    <?php }  } ?>
                                                    </td>
                                                </tr>
                                                <?php $i++;
                                            endforeach; ?><?php } else { ?>
                                                <tr>
                                                    <td> <?php echo "NO RECORD FOUND";
                                                        } ?></td>
                                                </tr>

                                    </tbody>
                                </table>
                            </div>
                            <div class="card-header">
              <!-- <span id="sample-pagination"><?php echo $pagination; ?></span> -->
              <span><?php echo $result_count; ?></span>

            </div>
                            <div class="card-footer">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <?php echo $links; ?>
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



</div>
</div>
<!-- report regenerate confirm modal -->
<div class="modal fade" tabindex="-1" role="dialog" id="regenerate" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content modal-sm" style="margin: 0 auto;margin-top:15%;">
            <div class="modal-header" style="background-color:darkblue;color:white;height: 50px;">
                <h5 class="modal-title"><b>CONFIRM</b></h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" style="color:white;">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" value="" class="sample_reg_id">
                <input type="hidden" value="" class="report_ids">
                <p>
                    Are you sure that you want to Regenerate Report ?</p>
            </div>
            <div class="modal-footer" style="background-color:darkblue;color:white;height: 50px;">
                <button type="button" data-id="" class="btn btn-success re_generate" style="margin-top: -10px;"><b>YES</b></button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="margin-top: -10px;"><b>NO</b></button>
            </div>
        </div>
    </div>
</div>

<script>
    $('document').ready(function() {
        const _tokken = $('meta[name="_tokken"]').attr('value');
        $(document).on('click', '.re_generate', function() {
            var sample_reg_id = $('.sample_reg_id').val();
            var report_id = $('.report_ids').val();
            $.ajax({
                url: "<?php echo base_url('Manage_lab/regenerate_sample') ?>",
                method: "post",
                data: {
                    _tokken: _tokken,
                    sample_reg_id: sample_reg_id,
                    report_id: report_id
                },
                success: function(data) {
                    // console.log(data);
                    var result = $.parseJSON(data);
                    if (result.status > 0) {
                        $('#regenerate').modal('hide');
                        window.location.reload();
                        $.notify(result.msg, 'success');

                    } else {
                        $.notify(result.msg, 'error');
                    }
                }
            })
        })

        $('.additional').on("click", function() {
            check = confirm("Are you sure you want to send this sample For Additional Test");
            if (check) {

                var sample_reg_id = $(this).attr('data-id');
                var report_id = $(this).attr('data-report-id');
                $.ajax({
                    url: "<?php echo base_url(); ?>Manage_lab/additional_test",
                    method: 'POST',
                    data: {
                        _tokken: _tokken,
                        sample_reg_id: sample_reg_id,
                        report_id: report_id
                    },
                    success: function(data) {
                        console.log(data);
                        var result = $.parseJSON(data);
                        if (result.status > 0) {
                            $.notify(result.msg, 'success');
                            window.location.reload();
                        } else {
                            $.notify(result.msg, 'error');
                        }

                    }
                });
            }
        });
    });

    function filter_by() {
        var base_url = '<?php echo base_url('Released_report/released_report_listing/'); ?>';
        var applicant = $('#applicant_id').val();
        var product = $('#product_id').val();
        var search = $('#search').val();
        var start_date = $('#start_date').val();
        var end_date = $('#end_date').val();
        // var status = $('#status').val();
        base_url += (applicant) ? applicant : 'NULL';
        base_url += '/' + ((product) ? product : 'NULL');
        base_url += '/' + ((search) ? btoa(search) : 'NULL');
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
              var taken_at = new Date(v.taken_at+ ' UTC');
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
