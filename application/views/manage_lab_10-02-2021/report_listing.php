<?php
$checkUser = $this->session->userdata('user_data');
$this->user = $checkUser->uidnr_admin;
$user = $this->user = $checkUser->uidnr_admin; ?>
<script src="<?php echo base_url(); ?>ckeditor/ckeditor.js"></script>
<div class="content-wrapper">
    <section class="content-header">
        <section class="content">
            <div class="container-fluid">
                <h3 class="text-center font-weight-bold text-primary">REPORT GENERATE LIST</h3>
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <input type="text"  class="form-control form-control-sm applicant_name" placeholder="ENTER APPLICANT NAME" value="<?php echo  ($applicant_name!='NULL')?strtoupper($applicant_name):'' ?>">
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
                                        <a class="btn btn-danger btn-sm" href="<?php echo base_url('Manage_lab/report_listing'); ?>">CLEAR</a>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body p-0">
                                <table class="table table-sm table-hovered text-center text-secondary">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>S.No.</th>
                                            <th>GC No.</th>
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
                                            <th>Action</th>
                                        </tr>
                                    </thead>
<tbody>


                                    <?php (empty($this->uri->segment(4))) ? $i = 1 : $i = $this->uri->segment(4) + 1; ?>

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
                                                <td><?php echo $RS->received_date; ?></td>
                                                <?php if($RS->total_record_finding == $RS->status_count) { ?>
                                                <td><?php echo $RS->status; ?></td>
                                                <?php }else {?>
                                                <td>Partially Completed</td>
                                                <?php }?>
                                                <td><?php echo $RS->sample_service_type; ?></td>
                                                <td><?php echo $RS->due_date; ?></td>
                                                <td>
                                                <?php if ($RS->status == 'Report Generated') { ?>
                                                        <a class="preview_report" href="javascript:void(0)" target="" data-url="<?php echo base_url('Manage_lab/preview_report?sample_reg_id=' . $RS->sample_reg_id . '&report_id=' . $RS->report_id); ?>" data-id="<?php echo $RS->sample_reg_id; ?>" data-report_id="<?php echo $RS->report_id; ?>" data-bs-toggle="modal" data-bs-target="#report_preview"><img width="20px" src="<?php echo base_url(); ?>assets/images/icon/viewdocument.png" alt="" title="Preview Report"></a>
<?php } ?>


<?php if($RS->total_record_finding == $RS->status_count) { ?>
                                                    <?php if ($RS->status == 'Report Generated' && ($RS->signing_authority == $user || $RS->sign_authority_new == $user)) { ?>
                                                        <a class="generate_report" href="javascript:void(0)" target="" data-url="<?php echo base_url('Manage_lab/pdf_demo?sample_reg_id=' . $RS->sample_reg_id . '&report_id=' . $RS->report_id); ?>" data-id="<?php echo $RS->sample_reg_id; ?>" data-report_id="<?php echo $RS->report_id; ?>" data-bs-toggle="modal" data-bs-target="#report_generation"><img src="<?php echo base_url(); ?>assets/images/right_arrow.png" alt="" title="Approve Report"></a>

                                                    <?php } elseif ($RS->status == 'Report Approved') { ?>

                                                        <a href="<?php echo $RS->manual_report_file; ?>" download="" target="_blank"><img src="<?php echo base_url(); ?>assets/images/view-lab-report-pdf.png" alt="" title="view pdf"></a>

                                                    <?php } else { ?>
                                                        <a class="final_report" href="javascript:void(0)" target="" data-id="<?php echo $RS->sample_reg_id; ?>" data-report_id="<?php echo $RS->report_id; ?>" data-bs-toggle="modal" data-bs-target="#data_for_report_generate"><img src="<?php echo base_url(); ?>assets/images/generate_report.png" alt="" title="Report Generate"></a>
                                                    <?php } ?>
                                                    <?php if ($RS->status == 'Report Approved') { ?>
                                                        <a class="regenerate_btn" href="javascript:void(0)" target="" data-id="<?php echo $RS->sample_reg_id; ?>" data-reports-id="<?php echo $RS->report_id; ?>" data-bs-toggle="modal" data-bs-target="#regenerate"><img src="<?php echo base_url(); ?>assets/images/retest2nd.png" alt="" title="Revise Report"></a>
                                                    <?php } ?>
                                                    <?php if ($RS->status == 'Report Approved') { ?>
                                                        <a class="additional" href="javascript:void(0)" target="" data-id="<?php echo $RS->sample_reg_id; ?>" data-report-id="<?php echo $RS->report_id; ?>" data-bs-toggle="modal" data-bs-target="#additional_test"><img src="<?php echo base_url(); ?>assets/images/Buyout.png" alt="" title="Additional Test"></a>
                                                    <?php } ?>
                                                    <?php if ($RS->status != 'Report Approved') { ?>
                                                        <a id="samples_images_upload" href="javascript:void(0)" target="" data-id="<?php echo $RS->sample_reg_id; ?>" data-report_id="<?php echo $RS->report_id; ?>" data-bs-toggle="modal" data-bs-target="#upload_images"><img src="<?php echo base_url(); ?>assets/images/image.png" alt="" title="Upload Images" style="height:16px; width:16px;"></a>
                                                    <?php } ?>
                                                  
                                                    <?php if ($RS->image_id) { ?>
                                                        <a href="javascript:void(0)" data-bs-toggle="modal" id="report_sample_image" data-bs-target="#sample_images" title="View Sample Images" data-id="<?= $RS->sample_reg_id; ?>"><img src="<?php echo base_url('public/img/icon/viewdetail.png') ?>" alt="View Images" style="height:16px; width:16px"></a>
                                                    <?php } ?>
                                                    <a id="send_to_record_finding" href="javascript:void(0)" target="" data-id="<?php echo $RS->sample_reg_id; ?>" data-report_id="<?php echo $RS->report_id; ?>" data-bs-toggle="modal" data-bs-target="#record_finding_modal"><img src="<?php echo base_url(); ?>assets/images/arrow_rotate_anticlockwise.png" alt="" title="Send Sample to Record Finding"></a>
                                                    <?php } ?>
                                                         <a id="send_to_record_finding" href="javascript:void(0)" target="" data-id="<?php echo $RS->sample_reg_id; ?>" data-report_id="<?php echo $RS->report_id; ?>" data-bs-toggle="modal" data-bs-target="#record_finding_modal"><img src="<?php echo base_url(); ?>assets/images/arrow_rotate_anticlockwise.png" alt="" title="Send Sample to Record Finding"></a>

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

<!-- Modal to view sample images -->
<div class="modal fade" id="sample_images" tabindex="-1" role="dialog" aria-labelledby="sample_imagesLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content" style="margin: 0px auto;">
            <div class="modal-header">
                <h5 class="modal-title" id="sample_imagesLabel">Sample Images</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="<?php echo base_url('Manage_lab/save_comment')?>" method="post" id="save_report_image_preference">
            <div class="modal-body">
            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                <table class="table">
                 <thead>
                  <tr>
                   <th>Image</th>
                   <th>Comment</th>
                   <th>Image Priority</th>
                   <th>Action</th>
                  </tr>
                 </thead>
                 <tbody id="report_sample_image_view"></tbody>
                </table>
                <div></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save Changes</button>
            </div>
            </form>
        </div>
    </div>
</div>
<!-- Modal to view sample images ends here-->
<!-- Modal to upload sample images -->
<div class="modal fade" id="upload_images" tabindex="-1" role="dialog" aria-labelledby="upload_imageLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content modal-sm" style="margin: 0px auto;">
            <div class="modal-header" style="background-color:darkblue;color:white;">
                <h5 class="modal-title" id="upload_imageLabel">Upload Sample Image</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" enctype="multipart/form-data" id="upload_samples_images">
                <div class="modal-body">
                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                    <input type="hidden" name="sample_reg_id" id="sample_reg_id">
                    <div class="form-group">
                        <input type="file" name="sample_image[]" multiple>
                    </div>
                </div>
                <div class="modal-footer " style="background-color:darkblue;color:white;">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Modal to upload sample images ends here-->
<!-- Modal to send record finding -->
<div class="modal fade" id="record_finding_modal" tabindex="-1" role="dialog" aria-labelledby="upload_imageLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content modal-sm" style="margin: 0px auto;">
            <div class="modal-header" style="background-color:darkblue;color:white;">
                <h5 class="modal-title" id="upload_imageLabel">CONFIRM :</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" enctype="multipart/form-data" id="record_finding">
                <div class="modal-body">
                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                    <input type="hidden" name="sample_reg_id" id="samples_reg_id">
                    <div class="form-group">
                        <p>Do you want to send this sample again to record finding ?</p>
                    </div>
                </div>
                <div class="modal-footer " style="background-color:darkblue;color:white;">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">OK</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Modal to send record finding ends here-->

<div class="modal fade" id="data_for_report_generate" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content modal-lg" style="margin: 0 auto;">
            <div class="modal-header bg-primary">
                <h5 class="modal-title text-white text-center" id="accepted_test_head">REPORT</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="text-white">&times;</span>
                </button>
            </div>
            <form name="final_report_generate" id="final_report_generate" method="post" action="<?php echo base_url(); ?>manage_lab/Final_report_generate">
                <input type="hidden" name="sample_reg_id" value="" class="sample_reg_id">
                <input type="hidden" name="report_id" value="" class="report_id">
                <div class="modal-body">
                    <div class="col-md-12">
                        <label for="">Do you want to show sample images in report?</label>
                        <select name="sample_image_flag" id="sample_image_flag" class="form-control" value="1" required>
                        <option value="">Choose one</option>
                        <option value="1" selected="selected">Yes</option>
                        <option value="0">No</option>
                        </select> <br>
                        <!-- <input type="radio" id="sample_image_flag" name="sample_image_flag" value="1" checked="checked">
                        <label for="Yes" >Yes</label>
                        <input type="radio" id="sample_image_flag" name="sample_image_flag" value="0">
                        <label for="No">No</label> <br> -->
                        <!-- <label for="">Report Template</label>
                        <select name="report_template_type" id="" class="report_template_type form-control">
                            <option value="">Select an option</option>
                            <option value="nabl_logo">Report template With NABL logo</option>
                            <option value="non_nabl_logo">Report template Without NABL logo</option>
                        </select> -->
                        <label for="">Ulr No Flag. :</label>
                        <select name="ulr_no_flag" id="ulr_no_flag" class="ulr_no_flag form-control">
                            <option value="">Select an option</option>
                            <option value="P">P</option>
                            <option value="F">F</option>
                        </select>
                        <label for="">Report Result :</label>
                        <select name="report_result" id="report_result" class="report_result form-control">
                            <option value="">Select an option</option>
                            <option value="1">PASS</option>
                            <option value="2">FAIL</option>
                            <option value="3">REFER RESULTS</option>
                        </select>
                        <label for="">Report Result Remark(if any)</label>
                        <textarea name="report_remark" id="report_remark" cols="20" rows="4" class=" form-control"></textarea>


                        <label for="">Issue Date</label>
                        <input type="date" name="issuance_date" id="issue_date" class="form-control"> <br>

                        <label for="">Signing Authority :</label><br>
                        <select name="sign_auth" id="approver" class="form-control" required>
                            <option value="">Select...</option>
                            <?php foreach ($sign_auth as $sign) { ?>
                                <option value="<?php echo $sign->uidnr_admin ?>"><?php echo $sign->admin_fname.' '.$sign->admin_lname ?></option>
                            <?php } ?>
                        </select><br>
<!-- 
                        <label for="">Second Signing Authority(if any) :</label><br>
                        <select name="sign_auth1" id="approver1" class="form-control">
                            <option value="">Select...</option>
                            <?php foreach ($sign_auth as $sign) { ?>
                                <option value="<?php echo $sign->uidnr_admin ?>"><?php echo $sign->admin_fname ?></option>
                            <?php } ?>
                        </select><br> -->





                        <label for="">Remark</label>
                        <textarea name="remark" id="remark" cols="30" rows="10" class="ckeditor form-control"></textarea>
                    </div>
                    <div class="container-fluid">
                        <div class="row">
                            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">



                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-primary">
                    <a href="" target="_blank"> <button type="submit" class="btn btn-success  submit">Generate</button></a>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>

                </div>
            </form>
        </div>
    </div>

</div>
<div class="modal fade" id="report_generation" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content modal-lg" style="margin: 0 auto;">
            <div class="modal-header">
                <h5 class="modal-title" id="accepted_test_head">GENERATE REPORT</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="text-white">&times;</span>
                </button>
            </div>
            <form name="report_generation" id="report_generation" method="post" action="<?php echo base_url(); ?>manage_lab/approve_report">
                <input type="hidden" name="report_id" value="" class="report_id">
                <input type="hidden" name="sample_reg_id" value="" class="sample_reg_id">
                <div class="modal-body">

                    <div class="container-fluid">
                        <div class="report_html"></div>
                        <div class="row">
                            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">



                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <a href="" target="_blank"> <button type="submit" class="btn btn-success  submit">Approve</button></a>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>

                </div>
            </form>
        </div>
    </div>

</div>

<div class="modal fade" id="report_preview" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content modal-xl" style="margin: 0 auto;">
            <div class="modal-header">
                <h5 class="modal-title" id="accepted_test_head">PREVIEW REPORT</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="text-white">&times;</span>
                </button>
            </div>
            <form name="report_preview" id="report_preview" method="post" action="">
                <input type="hidden" name="report_id" value="" class="report_id">
                <input type="hidden" name="sample_reg_id" value="" class="sample_reg_id">
                <div class="modal-body">

                    <div class="container-fluid">
                        <div class="report_html"></div>
                        <div class="row">
                            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">



                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <!-- <a href="" target="_blank"> <button type="submit" class="btn btn-success  submit">Approve</button></a> -->
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>

                </div>
            </form>
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
        $('.final_report').click(function() {
            var report_id = $(this).attr('data-report_id');
            var id = $(this).attr('data-id');
            $('.sample_reg_id').val(id);
            //alert(report_id);
            $.ajax({
                url: "<?php echo base_url('Manage_lab/get_generate_report_data') ?>",
                method: "post",
                data: {
                    _tokken: _tokken,
                    sample_reg_id: id,
                    report_id: report_id
                },
                success: function(data) {
                    var result = $.parseJSON(data);  
                    console.log(result);                 
                    $('#sample_image_flag').val(result.sample_images_flag);
                    $('#ulr_no_flag').val(result.ulr_no_flag);
                    $('#report_result').val(result.manual_report_result);
                    $('#report_remark').val(result.manual_report_remark);
                    $('#issue_date').val(result.issuance_date);
                    $('#approver').val(result.name);
                    CKEDITOR.instances['remark'].setData(result.remark);
                }
            });
        });
        $('.generate_report').click(function() {
            var report_id = $(this).attr('data-report_id');
            var sample_reg_id = $(this).attr('data-id');
            var report = $(this).attr('data-url');
            $('.report_id').val(report_id);
            $('.sample_reg_id').val(sample_reg_id);
            $('.report_html').html('<iframe width="100%" height="400px;" src="' + report + '" frameborder="0" id="report"></iframe>');
            // return false;
        });


        $('.preview_report').click(function() {
            var report_id = $(this).attr('data-report_id');
            var sample_reg_id = $(this).attr('data-id');

            var report = $(this).attr('data-url');
            $('.report_id').val(report_id);
            $('.sample_reg_id').val(sample_reg_id);

            $('.report_html').html('<iframe width="100%" height="400px;" src="' + report + '" frameborder="0" id="report"></iframe>');
            // return false;
            
        });

        $(document).on('click', '.regenerate_btn', function() {
            var sample_reg_id = $(this).attr('data-id');
            var report_id = $(this).attr('data-reports-id');
           // alert(report_id)
            $('.sample_reg_id').val(sample_reg_id),
                $('.report_ids').val(report_id)
               
        })

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
        $(document).on('click', '#samples_images_upload', function() {
            var sample_reg_id = $(this).data('id');
            $('#sample_reg_id').val(sample_reg_id);
        });
        // Ajax call to set sample_reg_id ends here

        // Ajax call to ulpload sample image

        $('#upload_samples_images').submit(function(e) {
            e.preventDefault();
            $.ajax({
                type: "post",
                processData: false,
                contentType: false,
                cache: false,
                async: false,
                url: '<?php echo base_url(); ?>Manage_lab/upload_samples_images',
                data: new FormData(this),
                success: function(data) {
                    var data = $.parseJSON(data);
                    if (data.status > 0) {
                        $.notify(data.message, "success");
                        $('#upload_images').modal('hide');
                        window.location.reload();
                    } else {
                        $.notify(data.message, "error");

                    }
                    // window.location.reload();
                }
            });
        });



        $(document).on('click', '#send_to_record_finding', function() {
            var sample_reg_id = $(this).data('id');
            // alert(sample_reg_id);
            $('#samples_reg_id').val(sample_reg_id);
        });
        // Ajax call to set sample_reg_id ends here

        // Ajax call torecord finding

        $('#record_finding').submit(function(e) {
            e.preventDefault();

            $.ajax({
                type: "post",
                processData: false,
                contentType: false,
                cache: false,
                async: false,
                // dataType: "json",
                url: '<?php echo base_url(); ?>Manage_lab/send_to_record_finding',
                data: new FormData(this),
                success: function(data) {
                    // alert(data);
                    var data = $.parseJSON(data);
                    // alert(data);
                    if (data.status > 0) {
                        $.notify(data.message, "success");
                        $('#record_finding_modal').modal('hide');
                        window.location.reload();
                    } else {
                        $.notify(data.message, "error");

                    }
                    // window.location.reload();record_finding
                }
            });
        });
        // Ajax call to get Sample images
        $(document).on('click', '#report_sample_image', function() {
            const _tokken = $('meta[name="_tokken"]').attr('value');
            $('#report_sample_image_view').empty();
            var sample_reg_id = $(this).data('id');
            $.ajax({
                type: 'post',
                url: '<?php echo base_url(); ?>Manage_lab/get_report_sample_images',
                data: {
                    _tokken: _tokken,
                    sample_reg_id: sample_reg_id
                },
                success: function(data) {
                    var images = JSON.parse(data);
                    image = '';
                    $.each(images, function(key, value) {
                        var comment = (value.comment)?value.comment:"";
                        image += '<tr><input type="hidden" name="image['+key+'][image_id]" value="'+value.image_id+'">';
                        image += '<td><img src="' + value.image_file_path + '" style="width:50px; height:50px"></td>';
                        image += '<td><input type="text" name="image['+key+'][comment]" class="form-control form-control-sm" value="'+comment+'"></td>'
                        image += '<td><input type="number" name="image['+key+'][sequence]" class="form-control form-control-sm"  value="'+value.image_sequence+'"></td>'
                        image += '<td><a href="javascript:void(0)" class="btn btn-danger delete-image" data-id="'+value.image_id+'">X</a></td>'
                        image += '</tr>';
                    });
                    $('#report_sample_image_view').append(image);
                }
            })
        });
        // Ajax call to get sample images ends here 
    });
</script>
<script>
     function filter_by() {
        var base_url = '<?php echo base_url('Manage_lab/report_listing/'); ?>';
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
<!-- Ajax call to remove report image -->
<script>
const _tokken = $('meta[name="_tokken"]').attr('value');
$(document).on('click','.delete-image',function(){
    var row_id = $(this);
    var image_id = row_id.data('id');
    if(confirm("Are you sure want to delete this image!")){
        $.ajax({
        type: 'post',
        url: '<?php echo base_url("Manage_lab/delete_report_sample_image");?>',
        data: {_tokken:_tokken,image_id:image_id},
        dataType: 'json',
        success:function(data){
            if (data.status > 0) {
                // window.location.reload();
                $('#sample_images').modal('hide');
                $.notify(data.message, "success");
            } else {
                $.notify(data.message, "error");
            }
        }
    });
    }
});

// Save comment and sequence
$('#save_report_image_preference').submit(function(e) {
        e.preventDefault();
        var form = $(this);
        $.ajax({
            type: 'post',
            url: form.attr('action'),
            data: form.serialize(),
            dataType: 'json',
            success: function(data) {
                if (data.status > 0) {
                    $('#sample_images').modal('hide');
                    $.notify(data.message, "success");
                    window.location.reload();
                } else {
                    $.notify(data.message, "error");
                }
            }
        });
    });
    // Ajax call ends here
</script>
