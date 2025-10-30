<?php
if (empty($lab)) {
  $division_id = set_value('division');
  $name = set_value('name');
  $status = set_value('status');
  $lab_type_id = set_value('lab_types');
  $branch_id = set_value('brn_names');
} else {
  $division_id = $lab->mst_labs_division_id;
  $name = $lab->lab_name;
  $status = $lab->status;
  $lab_type_id = $lab->mst_labs_lab_type_id;
  $branch_id = $lab->mst_labs_branch_id;
}
?>

<div class="content-wrapper">
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Lab </h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>">Home</a></li>
            <li class="breadcrumb-item"><a href="<?php echo base_url('Lab'); ?>">Lab</a></li>
            <li class="breadcrumb-item active">Add Lab</li>
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
          <div class="card card-secondary mb-5">
            <div class="card-header">
              <h3 class="card-title">Lab</h3>
            </div>
            <!-- /.card-header -->
            <form action="" method="post" autocomplete="off">
              <div class="card-body">
                <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                <?php if ($this->uri->segment('3') && $this->uri->segment('2') == 'edit_lab') { ?>
                  <!-- added by millan on 09-04-2021 -->
                  <input type="hidden" name="lab_id" value="<?php echo base64_decode($this->uri->segment('3')); ?>">
                <?php } ?>
                <!-- added by millan on 09-04-2021 -->
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Lab Type Name :</label>
                      <input type="text" name="name" class="form-control" style="width: 100%;" value="<?= $name; ?>">
                      <?php echo form_error('name', '<div class="text-danger">', '</div>'); ?>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Division:</label>
                      <select name="division" id="division" class="form-control">
                        <option disabled selected>Select Division</option>
                        <?php if (!empty($division)) {
                          foreach ($division as $division_list) { ?>
                            <option value="<?= $division_list['division_id']; ?>" <?php if ($division_id == $division_list['division_id']) {
                                                                                  echo "selected";
                                                                                } ?>><?= $division_list['division_name']; ?></option>
                        <?php }
                        } ?>
                      </select>
                      <?php echo form_error('division', '<div class="text-danger">', '</div>'); ?>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Lab Type:</label>
                      <select name="lab_type_id" id="lab_type_id" class="form-control">
                        <option disabled selected>Select Lab Type</option>
                        <?php if (!empty($lab_types)) {
                          foreach ($lab_types as $lb_list) { ?>
                            <option value="<?= $lb_list->lab_type_id; ?>" <?php if ($lab_type_id == $lb_list->lab_type_id) {
                                                                            echo "selected";
                                                                          } ?>><?= $lb_list->lab_type_name; ?></option>
                        <?php }
                        } ?>
                      </select>
                      <?php echo form_error('lab_type_id', '<div class="text-danger">', '</div>'); ?>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Branch:</label>
                      <select name="branch_id" id="branch_id" class="form-control">
                        <option disabled selected>Select Branch</option>
                        <?php if (!empty($brn_names)) {
                          foreach ($brn_names as $brn_list) { ?>
                            <option value="<?= $brn_list->branch_id; ?>" <?php if ($branch_id == $brn_list->branch_id) {
                                                                          echo "selected";
                                                                        } ?>><?= $brn_list->branch_name; ?></option>
                        <?php }
                        } ?>
                      </select>
                      <?php echo form_error('lab_type_id', '<div class="text-danger">', '</div>'); ?>
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
                  <a href="<?php echo base_url('Lab/index'); ?>"><button type="button" class="btn btn-danger">Cancel</button></a>
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