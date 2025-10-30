<?php
    error_reporting(1);
    $lab_location_id = ($mail_configuration) ? $mail_configuration->lab_location_id : '';
    $product_destination_id = ($mail_configuration) ? $mail_configuration->product_destination_id : '';
    $c_email = ($mail_configuration) ? $mail_configuration->c_email : '';
    $status = ($mail_configuration) ? $mail_configuration->status : '';
    $id = ($mail_configuration) ? $mail_configuration->mail_conf_id : '';
    
?>


<div class="content-wrapper">
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Mail Configuration</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>">Home</a></li>
            <li class="breadcrumb-item"><a href="<?php echo base_url('mail_configuration/index'); ?>">Mail_Configration</a></li>
            <li class="breadcrumb-item active">Edit Mail Configuration</li>
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
          <div class="card card-primary">
            <div class="card-header">
              <h3 class="card-title"> Mail Configuration</h3>
            </div>
            <!-- /.card-header -->
            <form action="<?php echo base_url('mail_configuration/update_mailconfiguration'); ?>" method="post" autocomplete="off">
              <div class="card-body">
                <input type="hidden" name="mailconfiguration_id" value="<?php echo $id; ?>">
                <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                <div class="row">

                 

                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Lab Location :</label>
                      <select name="lab_location_id" class="form-control form-control-sm" id="lab_location" style="width: 100%;">
                        <option disabled selected="">Select Lab Location</option>
                        <?php foreach ($lablocation as $value) { ?>
                          <option value="<?php echo $value['country_id'] ?>" <?php if ($value['country_id'] == $lab_location_id) { echo "selected";} ?>>
                          
                          <?php echo $value['country_name']; ?>
                          </option> 

                         <?php } ?>
                      </select>
                      <?php echo form_error('lab_location_id', '<div class="text-danger">', '</div>'); ?>
                      
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Product Destination :</label>
                      <select name="product_dest_id" class="form-control form-control-sm" id="product_destination" style="width: 100%;">
                        <option disabled selected="">Select Product Destination</option>
                        <?php foreach ($productdestination as $value) { ?>
                          <option value="<?php echo $value['country_id'] ?>" <?php if ($value['country_id'] == $product_destination_id) { echo "selected";} ?>>
                          
                          <?php echo $value['country_name']; ?>
                          </option> 

                         <?php } ?>
                      </select>
                      <?php echo form_error('product_dest_id', '<div class="text-danger">', '</div>'); ?>
                    </div>
                  </div>
                  
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Email :</label>
                      <input type="email" name="c_email" class="form-control form-control-sm" style="width: 100%;" value="<?php echo $c_email; ?>">
                      <?php echo form_error('c_email', '<div class="text-danger">', '</div>'); ?>
                      
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Status :</label>
                      
                      <select name="status" id="" class="form-control">
                        <option disabled selected="">Select Status</option>
                        <option value="1" <?php if ($status == "1") {
                                            echo "selected";
                                          } ?>>Active</option>
                        <option value="0" <?php if ($status == "0") {
                                            echo "selected";
                                          } ?>>Inactive</option>
                      </select> 
                      <?php echo form_error('status', '<div class="text-danger">', '</div>'); ?>
                    </div>
                  </div>

              <!-- /.card-body -->
              <div class="card-footer">
                  <a href="<?php echo base_url('mail_configuration/index'); ?>"><button type="button" class="btn btn-danger">Cancel</button></a>
                  <button type="submit" name="submit" class="btn btn-primary">Update</button>
              </div>
          </div>
          </form>
        </div>
      </div>

    </div>
    <!-- /.col (right) -->
</div>
<!-- /.row -->
</div><!-- /.container-fluid -->
</section>
</div>
<script>
  $(document).ready(function() {
    $('#lab_location').select2();
  });
</script>
<script>
  $(document).ready(function() {
    $('#product_destination').select2();
  });
</script>
