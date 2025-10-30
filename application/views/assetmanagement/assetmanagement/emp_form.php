<?php
if (empty($emp_data)) {
    $employee_name = set_value('employee_name');
    $employee_id = set_value('employee_id');
    $employee_contact = set_value('employee_contact');
    $emp_id = set_value('emp_id');
    $employee_designation	 = set_value('employee_designation');
    $issue_date = set_value('issue_date');
    $employee_mail = set_value('employee_mail');
    $division_id = set_value('division');
    $country_id = set_value('country');
    $state_id = set_value('state_id');
    $address = set_value('address');
    $country_id = set_value('country');
    $status = set_value('status');
  }else{
    $employee_name = $emp_data->employee_name; 
    $employee_contact = $emp_data->employee_contact; 
    $employee_id = $emp_data->employee_id; 
    $emp_id = $emp_data->emp_id; 
    $employee_designation = $emp_data->employee_designation; 
    $issue_date = $emp_data->issue_date; 
    $employee_mail = $emp_data->employee_mail; 
    $division_id = $emp_data->division_id; 
    $country_id = $emp_data->country_id; 
    $state_id = $emp_data->state_id; 
    $address = $emp_data->address; 
    $status = $emp_data->status; 
  }

 // echo"<pre>";  print_r($employee_id);

?>


<div class="content-wrapper">
  <section class="content-header">
    <div class="container">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Employee Details </h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>">Home</a></li>
            <li class="breadcrumb-item"><a href="<?php echo base_url('AssetManagement/assets_userlist'); ?>">Employee Details</a></li>
            <li class="breadcrumb-item active">Add Employee</li>
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
              <h3 class="card-title"> employee Details</h3>
            </div>

            <form action="" method="post" id="myForm2" autocomplete="off">
              <div class="card-body">
                <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
               
                  <input type="hidden" name="employee_id" id="employee_id" value="<?php echo $employee_id; ?>">
               

                <div class="row">

                  <div class="col-md-6">
                  <label>Employee Name:</label>
                    <input type="text" name="employee_name" class="form-control" style="width: 100%;" value="<?php echo $employee_name; ?>">
                    <?php echo form_error('employee_name', '<div class="text-danger">', '</div>'); ?>

                    <label>Employee ID:</label>
                    <input type="text" name="emp_id" class="form-control" style="width: 100%;" value="<?php echo $emp_id; ?>">
                    <?php echo form_error('emp_id', '<div class="text-danger">', '</div>'); ?>


                    <label>Employee Contact No:</label>
                    <input type="number" name="employee_contact" class="form-control" style="width: 100%;" value="<?php echo $employee_contact; ?>" min="0">
                    <?php echo form_error('employee_contact', '<div class="text-danger">', '</div>'); ?>
                  </div>

                  <div class="col-md-6">
                  <label>Employee Designation:</label>
                    <input type="text" name="employee_designation" class="form-control" style="width: 100%;" value="<?php  echo $employee_designation; ?>" min="0">
                    <?php echo form_error('employee_designation', '<div class="text-danger">', '</div>'); ?>


                    <label>Issue Date:</label>
                    <input type="date" name="issue_date" class="form-control" style="width: 100%;" value="<?php echo $issue_date; ?>">
                    <?php echo form_error('issue_date', '<div class="text-danger">', '</div>'); ?>
                  </div>

                  <div class="col-md-6">
                  <label>Employee Email:</label>
                    <input type="email" name="employee_mail" class="form-control" style="width: 100%;" value="<?php echo $employee_mail; ?>">
                    <?php echo form_error('employee_mail', '<div class="text-danger">', '</div>'); ?>
                  </div>

                  <div class="col-md-6">
                   <label>Address :</label>
                      <textarea name="address" class="form-control"><?php echo $address; ?></textarea>
                      <?php echo form_error('address', '<div class="text-danger">', '</div>'); ?>
                  </div>

                  <div class="col-md-6">
                  <label>Division :</label>
                    <select name="division" id="division" class="form-control">
                      <option value="" disabled selected>Select Division</option>
                      <?php if (!empty($division)) {
                        foreach ($division as $division_list) { ?>
                          <option value="<?php echo $division_list['division_id']; ?>" <?php if (!empty($division_id) && $division_id == $division_list['division_id']) { echo "selected"; } ?>><?php echo $division_list['division_name']; ?>
                          </option>
                      <?php }
                      } ?>
                    </select>
                    <?php echo form_error('division', '<div class="text-danger">', '</div>'); ?>
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
                    <label>State :</label>
                      <select name="state" id="state" class="form-control">
                        <option disabled selected>Select State</option>
                        <?php if (!empty($state)) {
                          foreach ($state as $state_list) { ?>
                            <option value="<?php echo $state_list['province_id']; ?>" <?php if (!empty($state) && $state == $state_list['province_id'])
 {
                                                                                        echo "selected";
                                                                                      } ?>><?php echo $state_list['province_name']; ?></option>
                        <?php }
                        } ?>
                      </select>
                      <?php echo form_error('state', '<div class="text-danger">', '</div>'); ?>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Status :</label>
                      <select name="status" id="" class="form-control">
                        <option disabled selected>Select Status</option>
                        <option value="1" <?php if ($status == "1") {
                                            echo "selected";
                                          } ?>>Joined</option>
                        <option value="2" <?php if ($status == "2") {
                                            echo "selected";
                                          } ?>>Resign</option>

<option value="3" <?php if ($status == "3") {
                                            echo "selected";
                                          } ?>>Transfer</option>
                      </select>
                      <?php echo form_error('status', '<div class="text-danger">', '</div>'); ?>
                    </div>
                  </div>

                </div>
              </div>
              <!-- /.card-body -->
              <div class="card-footer">
                <div style="margin-left: 43%;">
                  <a href="<?php echo base_url('AssetManagement/assets_userlist'); ?>"><button type="button" class="btn btn-danger">Cancel</button></a>
                  <button type="submit"  id="submitbutton" class="btn btn-primary">Save</button>
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
