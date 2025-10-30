<?php
$CI =& get_instance();
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
                <h3 class="text-center font-weight-bold text-primary">RELEASED INVOICE LIST</h3>
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <input type="text"  class="form-control form-control-sm applicant_name" placeholder="ENTER CLIENT NAME" value="<?php echo  ($applicant_name!='NULL')?strtoupper($applicant_name):'' ?>">
                                        <input type="hidden" class="applicant_id" id="applicant_id" value="<?php echo ($applicant_id!='NULL')?$applicant_id:''; ?>">
                                        <ul class="list-group-item customer_list" style="display:none">
                                        </ul>
                                    </div>
                                    <div class="col-sm-3">
                                        <input type="text" id="" class="form-control form-control-sm product_type" placeholder="ENTER PRODUCT NAME" value="<?php echo  ($product_name!='NULL')?strtoupper($product_name):'' ?>">
                                        <input type="hidden" class="product_id" id="product_id" value="<?php echo ($product_id!='NULL')?$product_id:''; ?>">
                                        <ul class="list-group-item cat_list" style="display:none">
                                        </ul>
                                    </div>
                                    <div class="col-sm-3">
                                        <input type="text" id="search" class="form-control form-control-sm" value="<?php echo ($search_url!='NULL')?$search_url:''; ?>" placeholder="SEARCH BY GC/TRF NO...">
                                    </div>
                                    <div class="col-sm-3 text-center">
                                        <a class="btn btn-primary btn-sm" onclick="filter_by()" href="javascript:void(0);">SUBMIT</a>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="row">
                                            <div class="col-sm-6 text-center">
                                                <input id="start_date" value="<?php echo ($start_date!='NULL')?$start_date:''; ?>" placeholder="START DATE" type="text" onfocus="(this.type='date')" class="form-control form-control-sm">
                                            </div>
                                            <div class="col-sm-6 text-center">
                                                <input id="end_date" placeholder="END DATE" value="<?php echo ($end_date!='NULL')?$end_date:''; ?>" type="text" onfocus="(this.type='date')" class="form-control form-control-sm">
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
                                        <a class="btn btn-danger btn-sm" href="<?php echo base_url('Released_invoice/released_invoice_list'); ?>">CLEAR</a>
                                    </div>
                                    <?php if (exist_val('Released_invoice/export_excel', $this->session->userdata('permission'))) { ?>
                                <a class="btn btn-sm btn-default" title="Excel Export" href="<?php echo base_url('Released_invoice/export_excel') ?>"><img src="<?php echo base_url('assets/images/imp_excel.png') ?>" alt="export excel" height="30px" width="30px"></a>
                            <?php } ?>
                                </div>
                            </div>
                            <div class="row">
                            
                            </div>
                            
                            <div class="card-body p-0">
                                <table class="table table-sm table-hovered text-center text-secondary">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>S.No.</th>
                                            <th>Basil Report No.</th>
                                            <th>Invoice Number</th>
                                            <th>Client</th>
                                            <th>Product</th>
                                            <th>TRF Reference No.</th>
                                            <!-- <th>Quantity</th> -->
                                            <th>Received Date</th>
                                            <th>Status</th>
                                            <!-- <th>Invoice Type</th> -->
                                            <!-- <th>Service Type</th> -->
                                            <th>Due Date</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
<tbody>


                                    <?php (empty($this->uri->segment(8))) ? $i = 1 : $i =  $this->uri->segment(8) + 1; ?>

                                    <?php if ($report_listing || $report_listing != NULL) {
                                    ?> <?php foreach ($report_listing as $RS) : ?>
                                            <tr>
                                                <td><?php echo $i; ?></td>
                                                <td><?php echo $RS->gc_no; ?></td>
                                                <td><?php echo $RS->report_num; ?></td>
                                                <td><?php echo $RS->client; ?></td>
                                                <!-- <td><?php echo $RS->sample_desc; ?></td> -->
                                                <td><?php echo $RS->product_name; ?></td>
                                                <td><?php echo $RS->trf_ref_no; ?></td>
                                                <!-- <td><?php echo $RS->qty_received; ?></td> -->
                                                <td><?php echo change_time($RS->received_date,$this->session->userdata('timezone')); ?></td>
                                                <td><?php echo $RS->status; ?></td>
                                                <!-- <td><?php echo $RS->report_type; ?></td> -->
                                                <!-- <td><?php echo $RS->sample_service_type; ?></td> -->
                                                <td><?php echo $RS->due_date; ?></td>
                                                <td>
                                                
                                            <?php if($RS->uploadfilepath != ''){?>
                                                <?php if(substr($RS->uploadfilepath, 0, 5) == 's3://') { ?>
                                                  <?php  $path =  getS3Urlpath($RS->uploadfilepath); }else{?>
                                                  <?php $path = $RS->uploadfilepath; }?>
                                                <a href="<?php echo $path; ?>" download="" target="_blank"><img src="<?php echo base_url(); ?>assets/images/view-lab-report-pdf.png" alt="" title="View Report"></a>
<?php } ?>


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
                                    <div class="card-header">
                                    <span><?php echo $result_count; ?></span>
                                    </div>
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


<script>
     function filter_by() {
        var base_url = '<?php echo base_url('Released_invoice/released_invoice_list/'); ?>';
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

            }else{
                hide_inputEvent.val('');
            }
        });
    }
</script>

