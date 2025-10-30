<style>
  form .error {
  color: #ff0000;
  margin-top:0;
  }

  </style>


  <div class="content-wrapper">
    <!-- Content Header (Page header) -->

    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>PRODUCTS</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?php echo base_url();; ?>">Home</a></li>
              <li class="breadcrumb-item"><a href="<?php echo base_url('products') ?>">Products</a></li>
              <li class="breadcrumb-item active">Add New</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>


    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <!-- left column -->
          <div class="col-md-12">
            <!-- general form elements -->
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Add New Product</h3>
              </div>



              <form action="<?php echo base_url('Test_management/Products/insert_product') ?>" method="post" enctype="multipart/form-data" name="add_product_form">
                <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">

                <div class="row px-3 p-3">

                  <div class=" col-md-6 px-3">
                    <label for="">PRODUCT CODE:</label>
                    <input class="form-control  input-sm" name="sample_types_code" value="<?php echo set_value('sample_types_code'); ?>" type="text">
                    <?php echo form_error('sample_types_code'); ?>
                  </div>

                  <div class="col-md-6 px-3">
                    <label for="">PRODUCT NAME:</label>
                    <input class="form-control  input-sm" name="sample_type_name" value="<?php echo set_value('sample_type_name'); ?>" type="text">
                    <?php echo form_error('sample_type_name'); ?>
                  </div>

                </div>

                <div class="row px-3 p-3">

                  <div class="col-md-6 px-3">
                    <label for="">CATEGORY NAME:</label>
                    <input class="sample_category_id" type="hidden" value="<?php echo set_value('type_category_id'); ?>" name="type_category_id">
                    <input class="form-control  input-sm sample_category_name" value="<?php echo set_value('cat_name'); ?>" autocomplete="off" name="cat_name" type="text" placeholder="Select a category ... ">
                    <ul class="list-group-item cat_list" style="display:none">
                    </ul>
                    <?php echo form_error('type_category_id'); ?>
                
                  </div>

                  <div class="col-md-6 px-3">
                    <label for="">RETAIN PERIOD:</label>
                    <input class="form-control  input-sm" value="<?php echo set_value('retain_period'); ?>" name="retain_period" type="number" placeholder="No of days">
                    <?php echo form_error('retain_period'); ?>
                   
                  </div>

                </div>

                <div class="row px-3 p-3">
                  <div class="col-md-6 px-3">
                    <label for="">MIN. SAMPLE QTY UNIT:</label>
                    <input type="hidden" value="<?php echo set_value('minimum_quantity_units'); ?>" name="minimum_quantity_units" class="unit_id">
                    <input class="form-control  input-sm unit" value="<?php echo set_value('unit_name'); ?>" name="unit_name" type="text" autocomplete="off" placeholder="Set Units..">
                    <ul class="list-group-item unit_list" style="display:none">
                    </ul>
                    <?php echo form_error('minimum_quantity_units'); ?>
            
                  </div>

                  <div class="col-md-6 px-3">
                    <label class="" for="">IMAGE UPLOAD (JPG|JPEG):</label>
                    <input type="file" class="form-control input-sm" value="<?php echo set_value('upload_image'); ?>" name="upload_image">
                    <div class="error" style="color:red">
                      <?php echo ($this->session->userdata('error_msz')) ? $this->session->userdata('error_msz') : '' ?>
                    </div>
                  </div>
                </div>

                <div class="row px-3 ">
                  <div class="col-md-6 px-3">
                    <label for="">STATUS:</label>
                    <select class="form-control  input-sm" name="status" id="">
                      <option value="" selected disabled>Select</option>
                      <option value="1" <?php echo set_select('status','1')?>>ACTIVE</option>
                      <option value="0" <?php echo set_select('status','0')?>>IN-ACTIVE</option>
                    </select>
                  </div>
                </div>

                <div class="row mt-2 text-right px-3">
                  <div class="col-sm-12 px-3">

                    <a href="<?php echo base_url('products') ?>" class="btn btn-primary" type="submit">Back</a>
                    <button class="btn btn-primary" type="submit">Submit</button>
                  </div>
                </div>


              </form>
            </div>

            <script src="<?php echo base_url('assets/dist/js/test_management.js'); ?>"></script>
            <script>
              
              // frond end validation 

              $(document).ready(function() {

                $("form[name='add_product_form']").validate({
                  rules: {
                    sample_types_code: "required",
                    sample_type_name: "required",
                    cat_name: "required",
                    retain_period: "required",
                    unit_name: "required",
                  
                    status: {
                      required: true
                    }
                  },
                  submitHandler: function(form) {
                    form.submit();
                  }
                });

              })
            </script>