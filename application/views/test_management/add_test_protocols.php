<style>
  form .error {
    color: #ff0000;
    margin-top: 0;
  }
</style>


<div class="content-wrapper">
  <!-- Content Header (Page header) -->

  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>TEST PROTOCOLS</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="<?php echo base_url();; ?>">Home</a></li>
            <li class="breadcrumb-item"><a href="<?php echo base_url('test_protocols') ?>">Test Protocols</a></li>
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
              <h3 class="card-title">Add New Protocol</h3>
            </div>


            <form action="<?php echo base_url('insert_protocol') ?>" method="post" enctype="multipart/form-data" name="add_test_protocol" class="add_test_protocol">
              <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">

              <div class="row px-3 p-3">
                <div class=" col-md-6 px-3">
                  <label for="">PROTOCOL NAME:</label>
                  <input class="form-control  input-sm" name="protocol_name" value="<?php echo set_value('protocol_name'); ?>" type="text">
                  <?php echo form_error('protocol_name'); ?>
                </div>

                <div class="col-md-6 px-3">
                  <label for="">PRODUCT:</label>
                  <input class="sample_type_id" type="hidden" value="<?php echo set_value('protocol_sample_type_id') ?>" name="protocol_sample_type_id">
                  <input class="form-control  input-sm sample_type_name" name="sample_type_name" value="<?php echo set_value('sample_type_name'); ?>" type="text" autocomplete="off" placeholder="Type product name....">
                  <?php echo form_error('protocol_sample_type_id'); ?>
                  <ul class="list-group-item sample_list" style="display:none">
                  </ul>
                </div>

              </div>

              <div class="row px-3 p-3">

                <div class=" col-md-6 px-3">
                  <label for="">PROTOCOL REFERENCE:</label>
                  <input class="form-control  input-sm" name="protocol_reference" value="<?php echo set_value('protocol_reference'); ?>" type="text">
                  <?php echo form_error('protocol_reference'); ?>
                </div>

                <div class="col-md-6 px-3">
                  <label for="">PROTOCOL TYPE:</label>
                  <select name="protocol_type" class="form-control  protocol_type">
                    <option value="" disabled selected>Select Protocol Type</option>
                    <option value="Global Standard" <?php echo set_select('protocol_type', 'Global Standard') ?>>Global Standard</option>
                    <option value="Customer Specific" <?php echo set_select('protocol_type', 'Customer Specific') ?>>Customer Specific</option>
                    <option value="BASIL" <?php echo set_select('protocol_type', 'BASIL') ?>>BASIL</option>
                  </select>
                  <?php echo form_error('protocol_type'); ?>
                </div>

              </div>

              <div class="row px-3 p-3">

                <div class="col-md-12 px-3">
                  <label for="">APPLICABLE TO:</label>
                  <input type="hidden" name="" id="">
                  <select id="ji" class="list-group-item protocol_country_id" name="protocol_country_id[]" style="" multiple="true" value="">
                    <?php if ($country) : ?>
                      <?php foreach ($country as $i => $v) : ?>
                        <option value="<?php echo $v->country_id ?>" selected><?php echo $v->country_name ?></option>
                      <?php endforeach; ?>
                    <?php endif; ?>

                  </select>
                  <?php echo form_error('protocol_country_id[]'); ?>
                </div>
              </div>
              <div class="row px-3 p-3">
                <div class="col-md-12 px-3">
                  <label for="">MAPPED TO:</label>
                  <input type="hidden" value="" class="pc_name">
                  <select class="list-group-item protocol_buyer_id required" name="protocol_buyer_id[]" style="" multiple="true" value="">
                    <?php if ($buyer) : ?>
                      <?php foreach ($buyer as $i => $v) : ?>
                        <option value="<?php echo $v->buyer_id ?>" selected><?php echo $v->buyer_name ?></option>
                      <?php endforeach; ?>
                    <?php endif; ?>


                  </select>
                  <?php echo form_error('protocol_buyer_id[]'); ?>
                </div>


              </div>

              <div class="row px-3 p-3">
                <div class="col-md-12 px-3">
                  <label for="">Upload File:</label>
                  <input type="file" name="file" id="file">
                </div>
                
              </div>


              <div class="row mt-2 text-right px-3">
                <div class="col-sm-12 px-3">

                  <a href="<?php echo base_url('test_protocols') ?>" class="btn btn-primary" type="submit">Back</a>
                  <button class="btn btn-primary" type="submit" class="add_test_submit">Submit</button>
                </div>
              </div>


            </form>
          </div>

          <script src="<?php echo base_url('assets/dist/js/test_management.js'); ?>"></script>
          <script>
            // frond end validation 

           
            $(document).ready(function() {
              $("form[name='add_test_protocol']").validate({
                rules: {
                  protocol_name: "required",
                  sample_type_name: "required",
                  protocol_reference: "required",
                  protocol_type: "required",
                },
                messages: {
                  protocol_name: "This is required field please fill it",
                  sample_type_name: "This is required field please fill it",
                  protocol_reference: "This is required field please fill it",
                  protocol_type: "This is required field please fill it",

                },
                submitHandler: function(form) {
                  form.submit();
                }
              });
            })
          </script>