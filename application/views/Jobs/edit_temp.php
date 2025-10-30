
</style>
  <!-- Content Wrapper. Contains page content -->
  <script src="<?php echo base_url('ckeditor/ckeditor.js');?>"></script>
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
            <h5>UPDATE TEMPORARY REGISTRATION</h5>
            <hr>
            <?php $data = $temp_list[0];
            ?>

            <form action="<?php echo base_url('Temp_reg/update_temp/'.$data->temp_reg_id)?>" method="post" autocomplete="off">
            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash();?>">
                <div class="row">
                    <div class="col-sm-6">
                        <label for="">CUSTOMER NAME:</label>
                        <input type="hidden" value="<?php echo $data->customer_id?>" name="customer_id" class="customer_id">
                        <input class="form-control  input-sm cust_name" name="cust_name" value="<?php echo $data->customer_name; ?>" type="text">
                        <?php echo form_error('cust_name'); ?>
                        <ul class="list-group-item drop_list">
                        </ul>
                    </div>

                    <div class="col-sm-6"> 
                         <label for="">NO OF SAMPLES:</label>
                        <input class="form-control  input-sm" name="no_of_samples" value="<?php echo $data->no_of_samples?>" type="number">
                   
                    </div>
                    
                </div>

                <div class="row">
                    <div class="col-sm-6">
                        <label for="">CUSTOMER EMAIL:</label>
                        <input class="form-control  input-sm cust_email" value="<?php echo $data->customer_email; ?>" name="customer_email" type="email">
                        <?php echo form_error('customer_email'); ?>
                        
                    </div>

                    <div class="col-sm-3"> 
                         <label for="">SAMPLE RECEIVING DATE:</label>
                        <input class="form-control  input-sm" value="<?php echo $data->sample_receiving_date; ?>" name="sample_receiving_date" type="date">
                       
                    </div>
                    <div class="col-sm-3"> 
                         <label for="">REPORT DATE:</label>
                        <input class="form-control  input-sm" value="<?php echo $data->report_date ?>" name="report_date"  type="date">
                       
                    </div>
                    
                </div>

                <div class="row">
                    <div class="col-sm-6">
                        <label for="">BUYER NAME:</label>
                        <input type="hidden" value="<?php echo $data->buyer_id; ?>" name="buyer_id" class="buyer_id">
                        <input class="form-control  input-sm buyer_name" name="buyer_name" value="<?php echo $data->buyer ?>" type="text">
                        <?php echo form_error('buyer_name'); ?>
                        <ul class="list-group-item buyer_list">
                        </ul>
                    </div>
                    <div class="col-sm-6"> 
                         <label for="">COLOR:</label>
                        <input class="form-control  input-sm" name="colour" value="<?php echo $data->colour ?>" type="text">
                        <?php echo form_error('colour'); ?>
                    </div>
                    
                </div>

                <div class="row">
                    <div class="col-sm-6">
                        <label for="">CONTACT PERSON:</label>
                        <input type="hidden" value="<?php echo $data->temp_contact ?>" name="temp_contact" class="contact_id">
                        <input class="form-control  input-sm contact_name" value="<?php echo $data->contact_name ?>" name="contact_name" type="text">
                        <?php echo form_error('contact_name'); ?>
                        <ul class="list-group-item con_list">
                        </ul>
                    </div>

                    <div class="col-sm-6"> 
                         <label for="">SERVICE:</label>
                        <input class="form-control  input-sm" name="service" value="<?php echo $data->service; ?>" type="text">
                        <?php echo form_error('service'); ?>
                    </div>
                    
                </div>

                <div class="row">
                    <div class="col-sm-6">
                        <label for="">CONTACT PERSON EMAIL:</label>
                        <input class="form-control  input-sm temp_contact_email" value="<?php echo $data->email ?>" name="temp_contact_email" type="email">
                        <?php echo form_error('temp_contact_email'); ?>
                    </div>

                    <div class="col-sm-6"> 
                         <label for="">STYLE NUMBER:</label>
                        <input class="form-control  input-sm" name="style_no" value="<?php echo $data->style_no ?>" type="text">
                        <?php echo form_error('style_no'); ?>
                    </div>
                    
                </div>

                <div class="row">
                    <div class="col-sm-6">
                        <label for="">REFERENCE NO.:</label>
                        <input class="form-control  input-sm" type="text" value="<?php echo $data->reference_no ?>" name="reference_no">
                        <?php echo form_error('reference_no'); ?>
                    </div>

                    <div class="col-sm-6"> 
                         <label for="">P.O NUMBER:</label>
                        <input class="form-control  input-sm" name="po_no" value="<?php echo $data->po_no; ?>" type="text">
                        <?php echo form_error('po_no'); ?>
                    </div>
                    
                </div>

                <div class="row">
                    <div class="col-sm-12">
                        <label for="">SAMPLE DESCRIPTION:</label>
                        <textarea  class="form-control" name="sample_desc"  id="" cols="10" rows="2"><?php echo $data->sample_desc ?></textarea>
                        <?php echo form_error('sample_desc'); ?>
                    </div>

                   
                </div>
                    
                    <div class="row">
                        <div class="col-sm-6">
                            <label for="">COUNTRY OF ORIGIN:</label>
                            <input type="hidden" value="<?php echo $data->temp_country_orgin ?>" name="temp_country_orgin" class="country_origin">
                             <input class="form-control  input-sm country_of_origin" name="country_of_origin" value="<?php echo $data->c_origin ?>"  type="text">
                             
                            <ul class="list-group-item origin_list">
                            </ul>
                        </div>

                        <div class="col-sm-6"> 
                            <label for="">COUNTRY OF DESTINATION:</label>
                            <input type="hidden" value="<?php echo $data->temp_country_destination ?>" name="temp_country_destination" class="country_dest">
                             <input class="form-control  input-sm country_of_destination" name="country_of_destination" value="<?php echo $data->d_origin ?>" type="text">
                            <ul class="list-group-item desti_list">
                            </ul>
                        </div>
                    
                </div>
                

                    <div class="row">
                        <div class="col-sm-6">
                            <label for="">END USE:</label>
                            <input class="form-control  input-sm" value="<?php echo $data->end_use; ?>" name="end_use" type="text">
                        </div>

                        <div class="col-sm-6"> 
                            <label for="">CRM USER LIST:</label>
                            <input type="hidden" value="<?php echo $data->tempcrm_user_id ?>" name="tempcrm_user_id" class="crm_user">
                             <input class="form-control  input-sm crm_user_list" name="crm_user_list" type="text" value="<?php echo $data->user_name ?>" >
                            <ul class="list-group-item crm_list">
                            </ul>
                        </div>
                    
                     </div>
                        <div class="row">
                        <div class="col-md-12">
                        <label for="remarks">Remark</label>
                        <textarea class="ckeditor" class="remarks_temp_edit" name="remarks"><?php echo $data->remarks ?></textarea>
                        </div>
                     </div>
                                   
            
                    <div class="row mt-2 text-right">
                        <div class="col-sm-12">
                        
                            <a href="<?php echo base_url('Temp_reg/index')?>" class="btn btn-primary" type="submit">Back</a>
                            <button class="btn btn-primary" type="submit">Update</button>
                        </div>
                    </div>
            </form>

        
        </div>

    </section>
    <!-- /.content -->
  </div>        
  <script>

    //  CKEDITOR.replace('edit_temp_remarks');

 </script> 