<?php
// echo"<pre>";
// print_r($fetch_data);
if (empty($fetch_data)) {
  $asset_name = set_value('asset_name');
  $asset_id = set_value('asset_id');
  $asset_make = set_value('asset_make');
  $asset_model = set_value('asset_model');
  $product_id = set_value('product_id');
  $asset_code = set_value('asset_code');
  $branch_id = set_value('branch');
  $country_id = set_value('country');
 $status = set_value('status');
}else{
  $asset_name = $fetch_data->asset_name; 
  $asset_id = $fetch_data->asset_id; 
  $asset_make = $fetch_data->asset_make; 
  $product_id = $fetch_data->product_id; 
  $asset_code = $fetch_data->asset_code; 
  $asset_model = $fetch_data->asset_model; 
  $branch_id = $fetch_data->branch_id;
   $country_id = $fetch_data->country_id;
   $status = $fetch_data->status;
}


//print_r($status);
?>


<div class="content-wrapper">
  <section class="content-header">
    <div class="container">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Asset Details </h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>">Home</a></li>
            <li class="breadcrumb-item"><a href="<?php echo base_url('AssetManagement/index'); ?>">Asset Details</a></li>
            <li class="breadcrumb-item active">Add Asset</li>
          </ol>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>

  <section class="content">
    <div class="container">
      <div class="row">
        <!-- left column -->
        <div class="col-md-12">
          <div class="card card-primary">
            <div class="card-header">
              <h3 class="card-title"> Asset Details</h3>
            </div>

            <form action="" method="post" autocomplete="off">
              <div class="card-body">
                <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                <?php //if ($this->uri->segment('2') == 'edit_asset') { ?>
                  <input type="hidden" name="asset_id" id="asset_id" value="<?php echo $asset_id; ?>">
                <?php //} ?>

                <div class="row">

                  <div class="col-md-6">
                    <label>Asset Name:</label>
                    <input type="text" name="asset_name" class="form-control" style="width: 100%;" value="<?php echo $asset_name; ?>">
                    <?php echo form_error('asset_name', '<div class="text-danger">', '</div>'); ?>

                    <label>Asset Make:</label>
                    <input type="text" name="asset_make" class="form-control" style="width: 100%;" value="<?php echo $asset_make; ?>">
                    <?php echo form_error('asset_make', '<div class="text-danger">', '</div>'); ?>
                  </div>

                  <div class="col-md-6">
                    <label>Serial no:</label>
                    <input type="text" name="product_id" class="form-control" style="width: 100%;" value="<?php echo $product_id; ?>">
                    <?php echo form_error('product_id', '<div class="text-danger">', '</div>'); ?>


                    <label>Asset Code:</label>
                    <input type="text" name="asset_code" class="form-control" style="width: 100%;" value="<?php echo $asset_code; ?>">
                    <?php echo form_error('asset_code', '<div class="text-danger">', '</div>'); ?>
                  </div>

                  <div class="col-md-6">
                    <label>Asset Model:</label>
                    <input type="text" name="asset_model" class="form-control" style="width: 100%;" value="<?php echo $asset_model; ?>">
                    <?php echo form_error('asset_model', '<div class="text-danger">', '</div>'); ?>
                  </div>

                  <div class="col-md-6">
                    <label>branch :</label>
                    <select name="branch" id="branch" class="form-control">
                      <option value="" disabled selected>Select branch</option>
                      <?php if (!empty($branch)) {
                        foreach ($branch as $branch_list) { ?>
                          <option value="<?php echo $branch_list['branch_id']; ?>" <?php if (!empty($branch_id) && $branch_id == $branch_list['branch_id']) {
                                                                                      echo "selected";
                                                                                    } ?>><?php echo $branch_list['branch_name']; ?>
                          </option>
                      <?php }
                      } ?>
                    </select>
                    <?php echo form_error('branch', '<div class="text-danger">', '</div>'); ?>
                  </div>
               

                  <div class="col-md-6">
                    <div class="form-group">
                      <label>country :</label>
                      <select name="country" id="country" class="form-control">
                        <option value="" disabled selected>Select country</option>
                        <?php if (!empty($country)) {
                          foreach ($country as $country_list) { ?>
                            <option value="<?php echo $country_list['country_id']; ?>" <?php if (!empty($country_id) && $country_id == $country_list['country_id']) {
                                                                                          echo "selected";
                                                                                        } ?>><?php echo $country_list['country_name']; ?>
                            </option>
                        <?php }
                        } ?>
                      </select>
                      <?php echo form_error('country', '<div class="text-danger">', '</div>'); ?>
                    </div>
                  </div>

             
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Status :</label>
                      <select name="status" id="" class="form-control">
                        <option disabled selected>Select Status</option>
                        <option value="1" <?php if ($status == "1") {
                                            echo "selected";
                                          } ?>>active</option>
                        <option value="2" <?php if ($status == "2") {
                                            echo "selected";
                                          } ?>>inactive</option>
                      </select>
                      <?php echo form_error('status', '<div class="text-danger">', '</div>'); ?>
                    </div>
                  </div>

                </div>
              </div>
              <!-- /.card-body -->
              <div class="card-footer">
                <div style="margin-left: 43%;">
                  <a href="<?php echo base_url('AssetManagement/index'); ?>"><button type="button" class="btn btn-danger">Cancel</button></a>
                  <button type="submit" class="btn btn-primary">Save</button>
                </div>
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
</div>

<script>
  $(document).ready(function() {
    const url = $('body').data('url');
    const _tokken = $('meta[name="_tokken"]').attr('value');
    // Ajax call to get state/provice
    $(document).on('change', '#country', function() {
      $('#state').empty();
      var country = $('#country').val();
      $.ajax({
        type: 'post',
        url: url + 'AssetManagement/get_province',
        data: {
          _tokken: _tokken,
          country: country
        },
        success: function(data) {
          var state = $.parseJSON(data);
          $('#state').append($('<option></option>').attr('disables', 'selected').text('Select State'));
          if (state != '') {
            $.each(state, function(key, value) {
              $('#state').append($('<option></option>').attr('value', value.province_id).text(value.province_name));
            });
          } else {
            $('#state').append('<option value="" >NO RECORD FOUND</option>');
          }
        }
      });
    });
    // Ajax call ends here

    // Ajax call to get City
    $(document).on('change', '#state', function() {
      $('#location').empty();
      var state = $('#state').val();
      $.ajax({
        type: 'post',
        url: url + 'AssetManagement/get_location',
        data: {
          _tokken: _tokken,
          state: state
        },
        success: function(data) {
          var location = JSON.parse(data);
          $('#location').append($('<option></option>').attr('disables', 'selected').text('Select Location'));
          $.each(location, function(key, value) {
            $('#location').append($('<option></option>').attr('value', value.location_id).text(value.location_name));
          });
        }
      });
    });
    // Ajax call ends here
  });
</script>