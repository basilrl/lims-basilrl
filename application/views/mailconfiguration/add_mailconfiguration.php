
<?php
$country_id = '';
if(!empty($mail_configuration)) {
  $country_id = $mail_configuration->country_id;

}
//print_r($countries); die;
?>

<script src="<?php echo base_url('assets/js/jquery-1.12.4.min.js');?>"></script>
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
            <li class="breadcrumb-item"><a href="<?php echo base_url('mail_configuration/index'); ?>">Mail Configuration</a></li>
            <li class="breadcrumb-item active">Add Mail Configuration</li>
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
            <form action="<?php echo base_url('mail_configuration/save_mailconfiguration'); ?>" method="post" autocomplete="off">
              <div class="card-body">
                <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                <div class="row">

                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Lab Location :</label>
                      <select name="lab_location_id" class="form-control form-control-sm" id="lab_location" style="width: 100%;">
                        <option disabled selected="">Select Lab Location</option>
                        <?php foreach ($lablocation as $value) { ?>
                          <option value="<?php echo $value['country_id'] ?>">
                          
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
                          <option value="<?php echo $value['country_id'] ?>">
                          
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
                      <input type="email" name="c_email" class="form-control form-control-sm" style="width: 100%;">
                      <?php echo form_error('c_email', '<div class="text-danger">', '</div>'); ?>
                      
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Status :</label>
                      <select name="status" id="" class="form-control">
                        <option disabled selected>Select Status</option>
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                      </select>
                      <?php echo form_error('status', '<div class="text-danger">', '</div>'); ?>
                    </div>
                  </div>


                  
                  <!-- /.card-body -->
              <div class="card-footer">
                  <a href="<?php echo base_url('mail_configuration/index'); ?>"><button type="button" class="btn btn-danger">Cancel</button></a>
                  <button type="submit" name="submit" class="btn btn-primary">Save</button>
              </div>
          </div>
          </form>
        </div>
      </div>

    </div>
    <!-- /.col (right) -->
</div>
<!-- /.row -->
<!-- /.container-fluid -->
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
