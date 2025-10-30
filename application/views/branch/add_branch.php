<?php
// echo "<pre>"; print_r($branch); die;
if (empty($branch)) {
  $name = set_value('branch_name');
  $code = set_value('branch_code');
  $status = set_value('status');
  $telephone = set_value('telephone');
  $country_id = set_value('country');
  $state_id = set_value('state');
  $currency_id = set_value('currency');
  $address = set_value('address');
  $division_id = set_value('division');
} else {
  $branch_id = $branch->branch_id; // added by millan on 15-04-2021
  $name = $branch->branch_name;
  $code = $branch->branch_code;
  $status = $branch->status;
  $telephone = $branch->branch_telephone;
  $country_id = $branch->mst_branches_country_id;
  $state_id = $branch->mst_state_id;
  $currency_id = $branch->mst_branches_currency_id;
  $address = $branch->branch_address;
  $division_id = $selected_division;
}
?>
<script src="<?php echo base_url('assets/js/branch.js'); ?>"></script>
<div class="content-wrapper">
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Add Branch </h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>">Home</a></li>
            <li class="breadcrumb-item"><a href="<?php echo base_url('Branch/index'); ?>">Branch</a></li>
            <li class="breadcrumb-item active">Add Branch</li>
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
          <div class="card card-secondary">
            <div class="card-header">
              <h3 class="card-title"><i class="fa fa-plus"></i> Branch</h3>
            </div>
            <!-- /.card-header -->
            <?php //echo validation_errors(); ?>
            <form action="" method="post" autocomplete="off">
              <div class="card-body">
                <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                <?php if($this->uri->segment('2') =='edit_branch' ) { ?>  <!-- added by millan on 15-04-2021 -->
                  <input type="hidden" name="branch_id" id="branch_id" value="<?php echo $branch_id; ?>">  
                <?php } ?>
                <div class="row">

                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Branch Code :</label>
                      <input type="text" name="branch_code" class="form-control" style="width: 100%;" value="<?= $code; ?>">
                      <?php echo form_error('branch_code', '<div class="text-danger">', '</div>'); ?>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Branch Name :</label>
                      <input type="text" name="branch_name" class="form-control" style="width: 100%;" value="<?= $name; ?>">
                      <?php echo form_error('branch_name', '<div class="text-danger">', '</div>'); ?>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Phone :</label>
                      <input type="number" name="telephone" class="form-control" style="width: 100%;" value="<?= $telephone; ?>" min="0">
                      <?php echo form_error('telephone', '<div class="text-danger">', '</div>'); ?>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Country :</label>
                      <select name="country" id="country" class="form-control">
                        <option disabled selected>Select Country</option>
                        <?php if (!empty($country)) {
                          foreach ($country as $country_list) { ?>
                            <option value="<?= $country_list['country_id']; ?>" <?php if ($country_id == $country_list['country_id']) {
                                                                                  echo "selected";
                                                                                } ?>><?= $country_list['country_name']; ?></option>
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
                        <?php if (!empty($state_id)) {
                          foreach ($state as $state_list) { ?>
                            <option value="<?= $state_list['province_id']; ?>" <?php if ($state_id == $state_list['province_id']) {
                                                                                  echo "selected";
                                                                                } ?>><?= $state_list['province_name']; ?></option>
                        <?php }
                        } ?>
                      </select>
                      <?php echo form_error('state', '<div class="text-danger">', '</div>'); ?>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Currency :</label>
                      <select name="currency" id="currency" class="form-control">
                        <option disabled selected>Select Currency</option>
                        <?php if (!empty($currency)) {
                          foreach ($currency as $currency_list) { ?>
                            <option value="<?= $currency_list['currency_id']; ?>" <?php if ($currency_id == $currency_list['currency_id']) {
                                                                                    echo "selected";
                                                                                  } ?>><?= $currency_list['currency_name']; ?></option>
                        <?php }
                        } ?>
                      </select>
                      <?php echo form_error('currency', '<div class="text-danger">', '</div>'); ?>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Division :</label>
                      <select name="division[]" id="division" class="form-control" multiple value="">
                        <option disabled>Select Division</option>
                        <?php if (!empty($division)) {
                          foreach ($division as $division_list) { ?>
                            <option value="<?= $division_list['division_id']; ?>" <?php if (!empty($division_id)) {
                                                                                    if (in_array($division_list['division_id'], $division_id)) {
                                                                                      echo "selected";
                                                                                    }
                                                                                  } ?>><?= $division_list['division_name']; ?></option>
                        <?php }
                        } ?>
                      </select>
                      <?php echo form_error('division[]', '<div class="text-danger">', '</div>'); ?>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Address :</label>
                      <textarea name="address" class="form-control"><?= $address; ?></textarea>
                      <?php echo form_error('address', '<div class="text-danger">', '</div>'); ?>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Status :</label>
                      <select name="status" id="" class="form-control">
                        <option disabled selected>Select Status</option>
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


                </div>
              </div>
              <!-- /.card-body -->
              <div class="card-footer">
                <div style="margin-left: 43%;">
                  <a href="<?php echo base_url('Branch/index'); ?>"><button type="button" class="btn btn-danger">Cancel</button></a>
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


<script>
  $(document).ready(function() {
    $('#division').select2();
  });
</script>