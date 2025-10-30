<div class="content-wrapper">
    <section class="content-header">
        <section class="content">
            <div class="container-fluid">
                <h3 class="text-center font-weight-bold text-primary">ACCEPTED SAMPLE</h3>
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="row mb-3">
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
                                        <select class="form-control form-control-sm" name="salesrep" id="status">
                                            <option value="">SELECT STATUS</option>
                                            <option value="Sample Accepted">Sample Accepted</option>
                                            <option value="Evaluation Completed">Evaluation Completed</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-3 text-center">
                                        <a class="btn btn-danger btn-sm" href="<?php echo base_url('Manage_lab/index'); ?>">CLEAR</a>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body p-0">
                                <table class="table table-sm table-hovered text-center text-secondary">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>S.No.</th>
                                            <th>GC No.</th>
                                            <th>Applicant</th>
                                            <th>Sample Description</th>
                                            <th>Product</th>
                                            <th>TRF Reference No.</th>
                                            <th>Quantity</th>
                                            <th>Recieved Date</th>
                                            <th>Status</th>
                                            <th>Service Type</th>
                                            <th>Due Date</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <?php (empty($this->uri->segment(9))) ? $i = 1 : $i = $this->uri->segment(9) + 1; ?>

                                    <tbody>
                                        <?php if ($accepted_sample || $accepted_sample != NULL) {
                                        ?> <?php foreach ($accepted_sample as $AS) : ?>

                                                <tr>
                                                    <td><?php echo $i; ?></td>
                                                    <td><?php echo $AS->gc_no; ?></td>
                                                    <td><?php echo $AS->client; ?></td>
                                                    <td><?php echo $AS->sample_desc; ?></td>
                                                    <td><?php echo $AS->product_name; ?></td>
                                                    <td><?php echo $AS->trf_ref_no; ?></td>
                                                    <td><?php echo $AS->qty_received; ?></td>
                                                    <td><?php echo $AS->received_date; ?></td>
                                                    <td><?php echo $AS->sample_reg_status; ?></td>
                                                    <td><?php echo $AS->sample_service_type; ?></td>
                                                    <td><?php echo $AS->due_date; ?></td>
                                                    <td>


                                                        <?php if (($AS->sample_test_ids ==  $AS->test_status)) { ?>
                                                            <!-- <a href="javascript:void(0)"><img src="<?php echo base_url(); ?>assets/images/arrow_redo.png" alt="" title="Re-assign Test"></a> -->
                                                            <a class="lab_completion" href="javascript:void(0)" data-lab_id="<?php echo $AS->sample_reg_id; ?>  "><img src="<?php echo base_url(); ?>assets/images/report_lab_completion.png" alt="" width="20%;" title="Mark as completed from lab"></a>


                                                        <?php } else { ?>


                                                            <a class="assign_test" href="javascript:void(0)" data-id="<?php echo $AS->sample_reg_id; ?>" data-bs-toggle="modal" data-bs-target="#sample_assign_test"><img src="<?php echo base_url(); ?>assets/images/arrow_right.png" alt="" title="Assign test"></a>
                                                        <?php } ?>



                                                    </td>
                                                </tr>

                                                <?php $i++;
                                            endforeach; ?><?php } else { ?>
                                                <tr>
                                                    <td> <?php echo "NO RECORD FIND";
                                                        } ?></td>
                                                </tr>

                                    </tbody>
                                </table>

                            </div>
                            <div class="card-footer">
                                <div class="row">
                                    <div class="col-sm-12"> <?php echo $links; ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </section>
</div>
<!-- sample assign test  -->
<div class="modal fade" id="sample_assign_test" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="accepted_test_head"></h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="text-white">&times;</span>
                </button>
            </div>
            <form method="POST" action="<?php echo base_url(); ?>Manage_lab/SaveAssignTest">

                <div class="modal-body">

                    <div class="container-fluid">
                        <div class="row">
                            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">

                            <div class="col-md-12">
                                <table style="width: 100%;" class="table table-hover table-sm text-center">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>SL No.</th>
                                            <th><input type="checkbox" name="assign_to" id="assign_to" class="form-control checkall"></th>
                                            <th>Test Name</th>
                                            <th>Test Method</th>
                                            <th>Test Duration</th>
                                            <th>Test Description</th>
                                            <th>Part</th>
                                            <th>Assign To</th>
                                            <th>Lab</th>
                                        </tr>
                                    </thead>
                                    <tbody class="showdata">

                                    </tbody>
                                </table>
                            </div>
                            <div class="col-md-12">
                                <label for="">Analyst</label>
                                <select name="analysis" id="analysis" class="form-control">

                                    <option value="">Select option</option>
                                </select>
                            </div>
                            <div class="col-md-12">
                                <label for="">Instruction Note</label>
                                <textarea name="instruction_note" id="" cols="10" rows="5" class="form-control"></textarea>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success  submit">Submit</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>

                </div>
            </form>
        </div>
    </div>

</div>
</div>
</div>



<script>
    $('document').ready(function() {

        <?php if ($stauts != 'NULL') { ?>
            $('#status').val('<?php echo $stauts; ?>');
        <?php } ?>
        $('.lab_completion').on("click", function() {
            check = confirm("Are you sure you want to send this sample as completed in record finding");
            if (check) {

                var id = $(this).attr('data-lab_id');
                $.ajax({
                    url: "<?php echo base_url(); ?>Manage_lab/Lab_completion",
                    method: 'POST',
                    data: {
                        _tokken: _tokken,
                        sample_reg_id: id,
                    },
                    success: function(result) {
                        var data = $.parseJSON(result);
                        if (data.status > 0) {
                            $.notify(data.msg, 'success');
                            window.location.reload();
                        } else {
                            $.notify(data.msg, 'error');
                        }

                    }
                });
            }
        });


        const _tokken = $('meta[name="_tokken"]').attr('value');
        $('.assign_test').click(function() {

            var id = $(this).attr('data-id');
            $.ajax({
                url: "<?php echo base_url(); ?>Manage_lab/Assign_test",
                method: 'POST',
                data: {
                    _tokken: _tokken,
                    sample_reg_id: id
                },
                success: function(result) {
                    var data = $.parseJSON(result);
                    var head = data.head;
                    var heading = 'Sample -' + head.product_name + '-' + head.gc_no;
                    $("#accepted_test_head").html(heading);
                    var test = data.test;
                    var test_record = test.length;
                    var html = '';
                    for (i = 0; i < test_record; i++) {
                        html += '<tr align="center">' +
                            '<td>' + (i + 1) + '</td>' +
                            '<td>';
                        console.log(test[i].assigned_to);
                        if (test[i].assigned_to) {
                            html += '<input type="checkbox"  name="assign_to[]" class="assign_to" id="assign_to" checked readonly disabled value=" ' + test[i].sample_test_id + '">' + '</td>';

                        } else {
                            html += '<input type="checkbox" name="assign_to[]" class="assign_to checked" id="assign_to" value="' + test[i].sample_test_id + '">' + '</td>';

                        }
                        html += '<td>' + test[i].test_name + '</td>' +
                            '<td>' + test[i].test_method + '</td>' +

                            '<td>' + test[i].test_turn_around_time + '</td>' +
                            '<td>' + test[i].test_description + '</td>';
                                
                             if(test[i].part_name){
                                html +=  '<td>' + test[i].part_name +'</td>';
                            }
                            else{
                                html +=  '<td>' + '' + '</td>';
                            }
                            if(test[i].admin_fname){
                                html +=  '<td>' + test[i].admin_fname + ' ' + test[i].admin_lname + '</td>';
                            }
                            else{
                                html +=  '<td>' + '' + '</td>';
                            } 
                           
                            if(test[i].lab_name){
                                html +=  '<td>' + test[i].lab_name +'</td>';
                            }
                            else{
                                html +=  '<td>' + '' + '</td>';
                            } 

                            '</tr>';
                    }
                    $(".showdata").html(html);

                    var rowCount = $('.showdata tr').length;
                    // console.log(rowCount);
                    var a = [];
                    $("table .showdata > tr").each(function () {
  if ($(this).find(".assign_to").is(":checked")) {
  var checked_count = $(this).find('td').eq(1).text();
 a.push(checked_count);
var count = a.length;
  }
  if(rowCount == count){
    $('.submit').attr('disabled','disabled');
  }
  else{
    $('.submit').removeAttr('disabled');
  }

});

                    var analysis = data.analysis;
                    if (analysis) {
                        $(analysis).each(function() {
                            var option = $('<option />');
                            option.attr('value', this.id).text(this.name);
                            $('#analysis').append(option);
                        });
                    } else {
                        $('#analysis').html('<option value="">Analysis not available</option>');
                    }
                }


            });
        });
        $(".checkall").click(function() {
           var count = $('input:checkbox').not(this).prop('checked', this.checked);
        });
       
    });
   
        function filter_by() {
            var base_url = '<?php echo base_url('Manage_lab/index/'); ?>';
            var applicant = $('#applicant_id').val();
            var product = $('#product_id').val();
            var search = $('#search').val();
            var start_date = $('#start_date').val();
            var end_date = $('#end_date').val();
            var status = $('#status').val();
            base_url += (applicant) ? applicant : 'NULL';
            base_url += '/' + ((product) ? product : 'NULL');
            base_url += '/' + ((search) ? btoa(search) : 'NULL');
            base_url += '/' + ((start_date) ? start_date : 'NULL');
            base_url += '/' + ((end_date) ? end_date : 'NULL');
            base_url += '/' + ((status) ? btoa(status) : 'NULL');
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
