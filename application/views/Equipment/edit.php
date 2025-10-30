<!-- Add view of Equipment by kamal on 20th of june 2022; -->

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css">

<script src="<?php echo base_url('ckeditor') ?>/ckeditor.js"></script>
<div class="content-wrapper">
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Equipments</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">

            <!-- links for previous page for Equipment listing by kamal on 20th of june 2022 -->

            <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>">Home</a></li>
            <li class="breadcrumb-item"><a href="<?php echo base_url('Equipment/index'); ?>">Equipments</a></li>
            <li class="breadcrumb-item active">Edit Equipment</li>
          </ol>
        </div>
      </div>
    </div>

    <div class="col-md-12">
      <div class="card card-primary">
        <div class="card-header">
          <h3 class="card-title">Equipments</h3>
        </div>

        <form action="" method="post" name="frmEdit">

          <!-- crsf token for login security on Equipment listing by kamal on 20th of june 2022 -->

          <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
          <input type="hidden" name="id" id="id" value="<?php echo $eqip_equipments['eqip_id']; ?>">  

          <div class="row">

            <div class="col-md-4">
              <div class="form-group">
                <label>Equipment Name :</label>
                <?php echo form_input(['class' => 'form-control', 'name' => 'name', 'placeholder' => 'Equipment Name...', 'value' => $eqip_equipments['eqip_name']]); ?>
                <?php echo form_error('name', '<div class="text-danger">', '</div>'); ?>
              </div>
            </div>

            <div class="col-md-4">
              <div class="form-group">
                <label>Equipment Id No:</label>
                <?php echo form_input(['class' => 'form-control', 'name' => 'equip_id', 'placeholder' => 'Equipment ID...', 'value' => $eqip_equipments['eqip_ID_no']]); ?>
                <?php echo form_error('equip_id', '<div class="text-danger">', '</div>'); ?>
              </div>
            </div>


            <div class="col-md-4">
              <div class="form-group">
                <label>Calibrated By:</label>
                <?php echo form_input(['class' => 'form-control', 'name' => 'calib_by', 'placeholder' => 'Calibrated By...', 'value' => $eqip_equipments['calibrated_by']]); ?>
                <?php echo form_error('calib_by', '<div class="text-danger">', '</div>'); ?>
              </div>
            </div>
            <!-- <div class="col-md-4 ">
              <?php $old_cus = set_value('created_by'); ?>
              <label>Custodian:</label>
              <select name="created_by" id="created_by" class="form-control ">
                <option >Select Custodian...</option>
                <?php foreach ($created_by_name as $cr_name) { ?>
                  <option <?php if ($eqip_equipments['custodian_id'] == $cr_name->uidnr_admin) {
                            echo "selected";
                          } ?> value="<?php echo $cr_name->uidnr_admin; ?>"> <?php echo $cr_name->created_by; ?> </option>
                <?php } ?>
              </select>
            </div> -->
          </div>

          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label>Model:</label>
                <?php echo form_input(['class' => 'form-control', 'name' => 'model', 'placeholder' => 'Model...', 'value' => $eqip_equipments['model']]); ?>
                <?php echo form_error('model', '<div class="text-danger">', '</div>'); ?>
              </div>
            </div>


            <div class="col-md-4">
              <div class="form-group">
                <label>Make:</label>
                <?php echo form_input(['class' => 'form-control', 'name' => 'make', 'placeholder' => 'Make...', 'value' => $eqip_equipments['make']]); ?>
                <?php echo form_error('make', '<div class="text-danger">', '</div>'); ?>
              </div>
            </div>


            <div class="col-md-4">
              <div class="form-group">
                <label>Serial No:</label>
                <?php echo form_input(['class' => 'form-control', 'name' => 'serial', 'placeholder' => 'Serial No...', 'value' => $eqip_equipments['serial_no']]); ?>
                <?php echo form_error('serial', '<div class="text-danger">', '</div>'); ?>
              </div>
            </div>
          </div>

          <div class="row">

            <div class="col-md-4 ">
              <div class="form-group">
                <label for="">Status</label>
                <select name="status" class="form-control">
                  <option>select</option>
                  <option value="Working" <?php if ($eqip_equipments['eqip_status'] =='Working') {
                                            echo "selected";
                                          } ?>>Working</option>
                   <option value="Retired" <?php if ($eqip_equipments['eqip_status'] =='Retired') {
                                            echo "selected";
                                          } ?>>Retired</option>
                  <option value="Damaged" <?php if ($eqip_equipments['eqip_status'] =='Damaged') {
                                            echo "selected";
                                          } ?>>Damaged</option>
                 
              </select>
              </div>
            </div>
            <div class="col-md-4 ">
              <label>Branch:</label>
              <select name="branch" id="branch" class="form-control ">
                <option value="" disabled selected>Select Branch...</option>
                <?php foreach ($branch_name as $br) { ?>
                  <option <?php if ($eqip_equipments['equip_branch_id'] == $br->branch_id) {
                            echo "selected";
                          } ?> value="<?php echo $br->branch_id; ?>"> <?php echo $br->branch; ?> </option>
                <?php } ?>
              </select>
            </div>
            <div class="col-md-4 " >
              <label>Division:</label>
              <select name="division" id="division" class="form-control ">
                <option>Select Division...</option>
                <?php foreach ($division_name as $de) { ?>
                  <option <?php if($eqip_equipments['division']==$de->division_id){echo "selected";}?> value="<?php echo $de->division_id; ?>"> <?php echo $de->division; ?> </option>
                <?php } ?>
              </select>
            </div>


            <!-- <div class="col-md-4">
              <div class="form-group">
                <label>Last Calibration Date:</label>
                <?php echo form_input(['class' => 'form-control', 'name' => 'Last_calib_date', 'type' => 'date', 'value' => $eqip_equipments['last_calib_date']]); ?>
                <?php echo form_error('Last_calib_date', '<div class="text-danger">', '</div>'); ?>
              </div>
            </div>

            <div class="col-md-4">
              <div class="form-group">
                <label>Last Maintenance Date:</label>
                <?php echo form_input(['class' => 'form-control', 'name' => 'Last_main_date', 'type' => 'date', 'value' => $eqip_equipments['last_maintanance_date']]); ?>
                <?php echo form_error('Last_main_date', '<div class="text-danger">', '</div>'); ?>
              </div>
            </div> -->
          </div>

          <div class="row">
            
                      </div>
          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label>Calibration Due Date:</label>
                <?php echo form_input(['class' => 'form-control', 'name' => 'due_date', 'type' => 'date', 'value' => $eqip_equipments['calibration_due_date']]); ?>
                <?php echo form_error('due_date', '<div class="text-danger">', '</div>'); ?>
              </div>
            </div>

            <div class="col-md-4">
              <div class="form-group">
                <label>Calibration Date:</label>
                <?php echo form_input(['class' => 'form-control', 'name' => 'calib_date', 'type' => 'date', 'value' => $eqip_equipments['calibration_date']]); ?>
                <?php echo form_error('calib_date', '<div class="text-danger">', '</div>'); ?>
              </div>
            </div>
            

            <div class="col-md-4">
              <div class="form-group">
                <label>Certificate No:</label>
                <?php echo form_input(['class' => 'form-control', 'name' => 'certi_no', 'placeholder' => 'Calibration Certificate No...', 'value' => $eqip_equipments['calibration_certificate_number']]); ?>
                <?php echo form_error('certi_no', '<div class="text-danger">', '</div>'); ?>
              </div>
            </div>

          </div>
          <div class="row">
            <div class="col-md-5" style='position: relative; left:40px'>

              <label>Test:</label>
              <select name="test[]" id="test" class="form-control multiple_select " multiple>
              <?php if ($test_ids) : ?>
                <option>Select Test...</option>
                <?php foreach ($test_name as $te) { ?>
                  <option  value="<?php echo $te->test_id; ?>" <?php if(in_array($te->test_id,$test_ids)) echo "selected";?> > <?php echo $te->test; ?> </option>
                <?php } ?>
                <?php endif?>
              </select>
            </div>

            <!-- </div> -->
            <!-- <div class="row"> -->
          
          </div>

          <div class="row">

            <div class="col-md-12">
              <div class="form-group ">
                <label>Calibration Field:</label>
                <?php echo
                form_textarea(['rows' => '10', 'cols' => '10', 'id' => 'calib_field', 'class' => 'form-control ', 'name' => 'calib_field', 'placeholder' => 'Calibration Field...', 'value' => $eqip_equipments['callibration_field']]);
                ?>
                  <?php echo form_error('calib_field', '<div class="text-danger">', '</div>'); ?>
              </div>
            </div>

            
            <div class="col-md-12">
              <div class="form-group">
                <?php $old_usage=set_value('usage')?>
                <label>Usage:</label>
                <?php echo
              form_textarea(['rows' => '5', 'cols' => '10', 'class' => 'form-control', 'name' => 'usage', 'id' => 'usage_id', 'placeholder' => 'Usage...', 'value' => $eqip_equipments['eqip_usage']]);
                ?>
                <?php echo form_error('usage', '<div class="text-danger">', '</div>'); ?>
              </div>
            </div>

          </div>

          <div class="col-md-5" style="position: relative; top:15px; left:600px;">
            <div class="form-group">
              <input type="submit" value="Update" name="btnedit" class="btn btn-primary btn-lg">
              <button class="btn btn-danger btn-lg"><a style="color:white;" href="<?php echo base_url('Equipment/index'); ?>">Cancel</a></button>

            </div>
          </div>
      </div><br>


      </form>
    </div>
</div>
</div>
<script>
  CKEDITOR.replace('calib_field');
  CKEDITOR.replace('main_field');
  CKEDITOR.config.enterMode = CKEDITOR.ENTER_BR;
  
  CKEDITOR.replace('usage_id');
  
</script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
<script>
  $(".multiple_select").select2({
    // maximumSelectionLength:10
  })
</script>
