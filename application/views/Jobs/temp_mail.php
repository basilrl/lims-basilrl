
</style>
  <!-- Content Wrapper. Contains page content -->
  <script src="<?php echo base_url('ckeditor/ckeditor.js');?>"></script>
  <script src="<?php echo base_url('assets/dist/js/jobs.js');?>"></script>
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>TEMPORARY REGISTRATIONS</h1>
          </div>
          <div class="col-sm-6">
<!--            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Simple Tables</li>
            </ol>-->
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>
        <div class="container">
        

            <hr>
            <h5>SEND TEMPORARY REGISTRATION</h5>
            <hr>
            <?php $data = $temp_list[0];?>
                
            <form action="<?php echo base_url('Temp_reg/temp_mail_send')?>" method="post">
            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash();?>">
               <input type="hidden" name="temp_id" value="<?php echo $data->temp_reg_id?>">
                   <div class="row">
                        <div class="col-sm-2">
                            <label class="form-control"for="to">To</label>
                        </div>
                        <div class="col-sm-10 ">
                            <input class="form-control" type="email" name="to_email" value="<?php echo $data->customer_email?>" id="">
                        </div>
                   </div>

                   <div class="row">
                        <div class="col-sm-2">
                            <label class="form-control"for="to">Cc</label>
                        </div>
                        <div class="col-sm-10 ">
                            <input class="form-control" type="email" value="<?php echo set_value('cc_email'); ?>" name="cc_email" value="" id="">
                        </div>
                   </div>

                   <div class="row">
                        <div class="col-sm-2">
                            <label class="form-control"for="to">Bcc</label>
                        </div>
                        <div class="col-sm-10 ">
                            <input class="form-control" type="email" value="<?php echo set_value('bcc_email'); ?>" name="bcc_email" value="" id="">
                        </div>
                   </div>

                   <div class="row">
                        <div class="col-sm-2">
                            <label class="form-control"for="to">Subject</label>
                        </div>
                        <div class="col-sm-10 ">
                            <input class="form-control" value="<?php echo set_value('subject'); ?>" type="text" name="subject" value="" id="">
                        </div>
                   </div>

             
                <div class="row">
                   
                    <div class="col-md-12">
                        <label for="">Compose Email</label>
                        <textarea class="ckeditor"  name="remarks"><?php echo $mail_body;?></textarea>
                    </div>
                </div>

                    <div class="row mt-2 text-right">
                        <div class="col-sm-12">
                        
                            <a href="<?php echo base_url('Temp_reg/index')?>" class="btn btn-primary" type="submit">Back</a>
                            <button class="btn btn-primary" type="submit">Send</button>
                        </div>
                    </div>
             </form>

        
        </div>

    </section>
    <!-- /.content -->
  </div>        
  <script>

     CKEDITOR.replace('remarks');

 </script> 