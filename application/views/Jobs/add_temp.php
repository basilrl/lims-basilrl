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
        </div>
        <!-- /.container-fluid -->
    </section>
    <section class="content">
        <div class="container-fluid">
        <div class="card card-primary">
            <div class="card-header">
            <h5>ADD NEW REGISTRATION</h5>
            </div>
            <form action="<?php echo base_url('Temp_reg/add_temp')?>" method="post" autocomplete="off">
            <div class="card-body">
                <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash();?>">
                <div class="row">
                    <div class="col-sm-6">
                        <label for="">CUSTOMER NAME:</label>
                        <input type="hidden" value="<?php echo set_value('customer_id')?>" name="customer_id" class="customer_id">
                        <input class="form-control  input-sm cust_name" name="cust_name" value="<?php echo set_value('cust_name'); ?>" type="text">
                        <?php echo form_error( 'cust_name'); ?>
                        <ul class="list-group-item drop_list">
                        </ul>
                    </div>

                    <div class="col-sm-6">
                        <label for="">NO OF SAMPLES:</label>
                        <input class="form-control  input-sm" name="no_of_samples" type="number" value="<?php echo set_value('no_of_samples')?>">
                    </div>

                </div>

                <div class="row">
                    <div class="col-sm-6">
                        <label for="">CUSTOMER EMAIL:</label>
                        <input class="form-control  input-sm cust_email" value="<?php echo set_value('customer_email'); ?>" name="customer_email" type="email">
                        <?php echo form_error( 'customer_email'); ?>

                    </div>

                    <div class="col-sm-3">
                        <label for="">SAMPLE RECEIVING DATE:</label>
                        <input class="form-control  input-sm" name="sample_receiving_date" type="date" value="<?php echo set_value('sample_receiving_date')?>">

                    </div>
                    <div class="col-sm-3">
                        <label for="">REPORT DATE:</label>
                        <input class="form-control  input-sm" name="report_date" type="date" value="<?php echo set_value('report_date')?>">

                    </div>

                </div>

                <div class="row">
                    <div class="col-sm-6">
                        <label for="">BUYER NAME:</label>
                        <input type="hidden" value="<?php echo set_value('buyer_id'); ?>" name="buyer_id" class="buyer_id">
                        <input class="form-control  input-sm buyer_name" name="buyer_name" value="<?php echo set_value('buyer_name'); ?>" type="text">
                        <?php echo form_error( 'buyer_name'); ?>
                        <ul class="list-group-item buyer_list">
                        </ul>
                    </div>
                    <div class="col-sm-6">
                        <label for="">COLOR:</label>
                        <input class="form-control  input-sm" name="colour" value="<?php echo set_value('colour'); ?>" type="text">
                        <?php echo form_error( 'colour'); ?>
                    </div>

                </div>

                <div class="row">
                    <div class="col-sm-6">
                        <label for="">CONTACT PERSON:</label>
                        <input type="hidden" value="<?php echo set_value('temp_contact'); ?>" name="temp_contact" class="contact_id">
                        <input class="form-control  input-sm contact_name" value="<?php echo set_value('contact_name'); ?>" name="contact_name" type="text">
                        <?php echo form_error( 'contact_name'); ?>
                        <ul class="list-group-item con_list">
                        </ul>
                    </div>

                    <div class="col-sm-6">
                        <label for="">SERVICE:</label>
                        <input class="form-control  input-sm" name="service" value="<?php echo set_value('service'); ?>" type="text">
                        <?php echo form_error( 'service'); ?>
                    </div>

                </div>

                <div class="row">
                    <div class="col-sm-6">
                        <label for="">CONTACT PERSON EMAIL:</label>
                        <input class="form-control  input-sm contact_email" value="<?php echo set_value('temp_contact_email'); ?>" name="temp_contact_email" type="email">
                        <?php echo form_error( 'temp_contact_email'); ?>
                    </div>

                    <div class="col-sm-6">
                        <label for="">STYLE NUMBER:</label>
                        <input class="form-control  input-sm" name="style_no" value="<?php echo set_value('style_no'); ?>" type="text">
                        <?php echo form_error( 'style_no'); ?>
                    </div>

                </div>

                <div class="row">
                    <div class="col-sm-6">
                        <label for="">REFERENCE NO.:</label>
                        <input class="form-control  input-sm" type="text" value="<?php echo set_value('reference_no'); ?>" name="reference_no">
                        <?php echo form_error( 'reference_no'); ?>
                    </div>

                    <div class="col-sm-6">
                        <label for="">P.O NUMBER:</label>
                        <input class="form-control  input-sm" name="po_no" value="<?php echo set_value('po_no'); ?>" type="text">
                        <?php echo form_error( 'po_no'); ?>
                    </div>

                </div>

                <div class="row">
                    <div class="col-sm-12">
                        <label for="">SAMPLE DESCRIPTION :</label>
                        <textarea class="form-control" name="sample_desc"  id="" cols="10" rows="2"><?php echo set_value('sample_desc'); ?></textarea>
                        <?php echo form_error( 'sample_desc'); ?>
                    </div>


                </div>

                <div class="row">
                    <div class="col-sm-6">
                        <label for="">COUNTRY OF ORIGIN:</label>
                        <input type="hidden" value="<?php echo set_value('temp_country_orgin'); ?>" name="temp_country_orgin" class="country_origin">
                        <input class="form-control  input-sm country_of_origin" name="country_of_origin" type="text" value="<?php echo set_value('country_of_origin')?>">

                        <ul class="list-group-item origin_list">
                        </ul>
                    </div>

                    <div class="col-sm-6">
                        <label for="">COUNTRY OF DESTINATION:</label>
                        <input type="hidden" value="<?php echo set_value('temp_country_destination'); ?>" name="temp_country_destination" class="country_dest">
                        <input class="form-control  input-sm country_of_destination" name="country_of_destination" type="text" value="<?php echo set_value('country_of_destination')?>">
                        <ul class="list-group-item desti_list">
                        </ul>
                    </div>

                </div>


                <div class="row">
                    <div class="col-sm-6">
                        <label for="">END USE:</label>
                        <input class="form-control  input-sm" name="end_use" type="text" value="<?php echo set_value('end_use')?>">
                    </div>

                    <div class="col-sm-6">
                        <label for="">CRM USER LIST:</label>
                        <input type="hidden" value="<?php echo set_value('tempcrm_user_id')?>" name="tempcrm_user_id" class="crm_user">
                        <input class="form-control  input-sm crm_user_list" name="crm_user_list" type="text" value="<?php echo set_value('crm_user_list')?>">
                        <ul class="list-group-item crm_list">
                        </ul>
                    </div>

                </div>
                <div class="row">
                    <div class="col-md-12">
                        <label for="remarks">Remark</label>
                        <textarea class="ckeditor" name="remarks_temp_add" class="remarks_temp_add"><?php echo set_value('remarks_temp_add')?></textarea>
                    </div>
                </div>


                <div class="row mt-2 text-right">
                    <div class="col-sm-12">

                        <a href="<?php echo base_url('Temp_reg/index')?>" class="btn btn-primary" type="submit">Back</a>
                        <button class="btn btn-primary" type="submit">Submit</button>
                    </div>
                </div>
                </div>
            </form>


        </div>

    </section>
    <!-- /.content -->
</div>
<script>
    // CKEDITOR.replace('remarks_temp');
</script>