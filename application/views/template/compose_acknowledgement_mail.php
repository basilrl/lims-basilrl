<script src="<?php echo base_url();?>/ckeditor/ckeditor.js"></script>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <script src="<?php echo base_url('assets/js/sample_registration.js') ?>"></script>
    <!-- Content Header (Page header) -->

    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Sample Registration </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>">Home</a></li>
                        <li class="breadcrumb-item"><a href="<?php echo base_url('open-trf-list'); ?>">Open TRF List</a></li>
                        <li class="breadcrumb-item active">Add Sample</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <?php echo validation_errors(); ?>
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <!-- left column -->
                <div class="col-md-12">
                    <!-- general form elements -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title"> Sample</h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form action="<?php echo base_url('SampleRegistration_Controller/send_acknowledgement_mail/'.$sample_reg_id)?>" method="post" id="send_acknowledgement_mail">
                            <div class="card-body">
                                <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">

                                <input type="hidden" value="" class="sample_reg_id" name="sample_reg_id">
                                <div class="row">
                                    <div class="col-md-12">
                                        <label for="">To</label>
                                        <input type="text" name="to" class="form-control email_to" value="<?php echo implode(",",$to);?>">
                                        <label for="">CC</label>
                                        <input type="text" name="cc" class="form-control" value="<?php echo implode(",",$cc);?>">
                                        <label for="">Bcc</label>
                                        <input type="text" name="bcc" class="form-control" value="<?php echo implode(",",$bcc);?>">
                                        <label for="">Subject</label>
                                        <textarea name="subject" id="" cols="30" rows="2" class="form-control"><?=$subject;?></textarea>
                                        <label for="">Body</label>
                                        <textarea name="message" id="" cols="30" rows="10" class="form-control ckeditor"><?=$template;?></textarea>
                                    </div>
                                </div>


                            </div>
                            
                    </div>
                        </div>
                        <!-- /.card-body -->

                        <div class="card-footer">
                        <button type="button" class="btn btn-danger" onclick="location.href='<?php echo base_url('sample-list')?>'">Cancel</button>
                            <button type="submit" class="btn btn-primary" style="float: right;">Submit</button>
                        </div>
                    </form>
                </div>
                <!-- /.card -->

            </div>
            <!--/.col (left) -->
        </div>
        <!-- /.row -->
</div><!-- /.container-fluid -->
</section>
<!-- /.content -->
</div>
<!-- /.content-wrapper -->
<script>
    $(document).ready(function() {
        CKEDITOR.replaceClass = 'ckeditor';
    });
</script>
<script>
    $(document).ready(function(){
        $('#send_acknowledgement_mail').submit(function(e){
        $('body').append('<div class="pageloader"></div>');
        e.preventDefault();
        var form = $(this);
        $.ajax({
            type: 'post',
            url: form.attr('action'),
            data: form.serialize(),
            dataType: 'json',
            success: function(data) {
                $('.pageloader').remove();
                if (data.status > 0) {
                    $.notify(data.message, "success");
                    window.location.replace('<?php echo base_url("sample-list")?>');
                } else {
                    $.notify(data.message, "error");
                }
            }
        });
    });
    });
</script>