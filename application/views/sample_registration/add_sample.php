x<?php  
$sample_customer_ids = $trf_data[0]['open_trf_customer_id'];
$sample_desc = $trf_data[0]['trf_sample_desc'];
$product_custom_fields = $trf_data[0]['product_custom_fields'];
$action = base_url()."add-sample/".$trf_id;
$branch_id = set_value('branch_id');
$test_standard_id = set_value('test_standard_id');
$branch_id = set_value('branch_name');
$qty = set_value('qty_received');
$unit = set_value('qty_unit');
$seal_no = set_value('seal_no');
$quantity_desc = set_value('quantity_desc');
$selected_lab = set_value('assign_sample_registered_to_lab_id');
if(!empty($this->input->post('care_instruction'))){
  $care_instruction = set_value('care_instruction');
} else {
  $care_instruction = [];
}
?>
<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
  <script src="<?php echo base_url('assets/js/sample_registration.js') ?>"></script>
    <!-- Content Header (Page header) -->

    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Sample Registration </h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?php echo base_url();?>">Home</a></li>
              <li class="breadcrumb-item"><a href="<?php echo base_url('open-trf-list');?>">Open TRF List</a></li>
              <li class="breadcrumb-item active">Add Sample</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>
  <?php //echo validation_errors();?>
    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <!-- left column -->
          <div class="col-md-12">
            <!-- general form elements -->
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title"> Sample</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form autocomplete="off" name="" role="form" method="post" action="<?php echo $action; ?>">
                <div class="card-body">
                  <div class="row">
                  <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                  <input type="hidden" name="sample_customer_id" value="<?php echo $sample_customer_ids; ?>">
                  <input type="hidden" id="trf_id" value="<?php echo $trf_id; ?>">

                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="exampleInputPassword1">Select Branch</label>
                        <select class="form-control select-box form-control-sm" id="branch_name" name="branch_name">
                          <option selected="" disabled="">Select branch</option>
                          <?php if (!empty($branches)) { foreach ($branches as $value) { ?>
                             <option value="<?php echo $value['branch_id']; ?>"         
                             <?php if ($branch_id == $value['branch_id']){ echo "selected"; } ?>><?php echo $value['branch_name']; ?></option> 
                          <?php } } ?>
                        </select>
                        <?php echo form_error('branch_name', '<div class="text-danger">', '</div>'); ?>
                      </div>
                    </div>

                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="exampleInputPassword1">Test Specification</label>
                        <select class="form-control select-box form-control-sm" id="test_standard_id" name="test_standard_id">
                          <option selected="" disabled="">Select Test Specification</option>
                          <?php if (!empty($branches)) { foreach ($test_specification as $value) { ?>
                             <option value="<?php echo $value['test_standard_id']; ?>"         
                             <?php if ($test_standard_id == $value['test_standard_id']){ echo "selected"; } ?>><?php echo $value['test_standard_name']; ?></option> 
                          <?php } } ?>
                        </select>
                        <?php echo form_error('branch_name', '<div class="text-danger">', '</div>'); ?>
                      </div>
                    </div>


                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="exampleInputEmail1">Collection Date</label>
                            <div class="input-group date" id="reservationdate" data-bs-target-input="nearest">
                                <input type="text" class="form-control datetimepicker form-control-sm" data-bs-target="#reservationdate" name="collection_date" value="">
                                <div class="input-group-append" data-bs-target="#reservationdate" data-bs-toggle="datetimepicker">
                                    <div class="input-group-text datetimepicker"><i class="fa fa-calendar"></i></div>
                                </div>
                            </div>
                            <?php echo form_error('collection_date', '<div class="text-danger">', '</div>'); ?>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="exampleInputEmail1">Recieved Date</label>
                            <div class="input-group date" id="reservationdate" data-bs-target-input="nearest">
                                <input type="text" class="form-control datetimepicker form-control-sm" data-bs-target="#reservationdate" name="received_date" value="<?php echo date('Y-m-d H:i:s'); ?>">
                                <div class="input-group-append" data-bs-target="#reservationdate" data-bs-toggle="datetimepicker">
                                    <div class="input-group-text datetimepicker"><i class="fa fa-calendar"></i></div>
                                </div>
                            </div>
                            <?php echo form_error('received_date', '<div class="text-danger">', '</div>'); ?>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="exampleInputEmail1">Seal No.</label>
                            <input type="text" class="form-control form-control-sm" name="seal_no" value="<?php echo $seal_no; ?>">
                            <?php echo form_error('seal_no', '<div class="text-danger">', '</div>'); ?>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group d-none">
                            <label for="exampleInputEmail1">Basil Report Number</label>
                            <input type="text" class="form-control form-control-sm" name="gc_number" id="gc_number" value="">
                        </div>
                    </div>

                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="exampleInputEmail1">Select Product</label>
                        <select class="form-control select-box form-control-sm" id="product" name="sample_registration_sample_type_id">
                          <option selected="" disabled="">Product</option>
                          <option value="<?php echo $product_details->pid; ?>" selected><?php echo $product_details->pname; ?></option>
                        </select>
                        <?php echo form_error('sample_registration_sample_type_id', '<div class="text-danger">', '</div>'); ?>
                      </div>                    
                    </div>

                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Qty. Recieved</label>
                                    <input type="number" min="0" class="form-control form-control-sm" data-bs-target="#reservationdate" name="qty_received" value="<?php echo $qty; ?>">
                                    <?php echo form_error('qty_received', '<div class="text-danger">', '</div>'); ?>
                                </div>
                            </div> 
                            <div class="col-md-6">
                                <label for="exampleInputEmail1">Unit</label>
                                <select class="form-control select-box form-control-sm" id="qty_unit" name="qty_unit">
                                    <option selected="" disabled=""> Select Unit</option>
                                    <?php foreach ($units as $value) { ?>
                                    <option value="<?php echo $value->unit_id; ?>" <?php if($unit == $value->unit_id){ echo "selected"; }?>><?php echo $value->unit; ?></option>
                                    <?php } ?>
                                </select>
                                <?php echo form_error('qty_unit', '<div class="text-danger">', '</div>'); ?>
                            </div>   
                        </div>             
                    </div>

                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="exampleInputEmail1">Sample Description</label>
                        <textarea class="form-control" name="sample_desc"><?php echo $sample_desc;?></textarea>
                        <?php echo form_error('sample_desc', '<div class="text-danger">', '</div>'); ?>
                      </div>                    
                    </div>

                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="exampleInputEmail1">Quanity Description</label>
                        <textarea class="form-control" name="quantity_desc"><?php echo $quantity_desc; ?></textarea>
                        <?php echo form_error('quantity_desc', '<div class="text-danger">', '</div>'); ?>
                      </div>                    
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="exampleInputEmail1">Primary Lab</label>
                            <select class="form-control select-box form-control-sm" id="labs" name="assign_sample_registered_to_lab_id">
                            <option disabled="">Select Lab</option>
                            <?php if(!empty($labs_id)){ foreach($labs_id as $lab) {?>
                            <option value="<?php echo $lab['lab_id']; ?>" <?php if($selected_lab == $lab['lab_id']){ echo "selected";}?>><?php echo $lab['lab_name']; ?></option>
                            <?php } }?>
                            </select>
                            <?php echo form_error('assign_sample_registered_to_lab_id', '<div class="text-danger">', '</div>'); ?>
                        </div>                    
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="exampleInputEmail1">Retain Sample Period</label>
                            <select class="form-control select-box form-control-sm" name="sample_retain_status">
                            <option disabled="">Retain Sample Period</option>
                            <option value="1">Yes</option>
                            <option value="0">No</option>
                            </select>
                            <?php echo form_error('sample_retain_status', '<div class="text-danger">', '</div>'); ?>
                        </div>                    
                    </div>

                    <!-- <div class="col-md-6">
                        <div class="form-group">
                            <label for="exampleInputEmail1">Application Provided Care Instruction</label>
                            <select class="form-control select-box form-control-sm" multiple="" name="care_instruction[]">
                            <option disabled="">Application Provided Care Instruction</option>
                            <?php foreach ($application_care_instruction as $value) { ?>
                            <option value="<?php echo $value['instruction_id'] ?>" <?php if(in_array($value['instruction_id'], $care_instruction)){ echo "selected"; }?>><?php echo $value['instruction_name'] ?></option>
                            <?php } ?>
                            </select>
                            <?php echo form_error('care_instruction[]', '<div class="text-danger">', '</div>'); ?>
                        </div>                    
                    </div> -->

                    <!-- <div class="col-md-6">
                        <div class="form-group">
                            <label for="exampleInputEmail1">Test Name (Test Methods)</label>
                            <select class="form-control select-box form-control-sm" multiple id="tests" name="griddata[]">
                            <option disabled="">Select Test</option>
                            <?php if(!empty($selected_test)) { foreach($tests as $new_test){ foreach($selected_test as $test_sel){?>
                                <option value="<?php echo $new_test['test_id']; ?>" <?php if($new_test['test_id'] == $test_sel['test_id']){echo "selected"; } ?>><?php echo $new_test['test_name']; ?></option>
                            <?php } } }?>
                            </select>
                            <?php echo form_error('griddata', '<div class="text-danger">', '</div>'); ?>
                        </div>                    
                    </div> -->

                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="">Do you want to send acknowledgement mail</label>
                        <select name="send_mail" class="form-control form-control-sm">
                          <option value="Yes">Yes</option>
                          <option value="No" selected>No</option>
                        </select>
                      </div>
                    </div>
                </div>
                <div class="row">
                  <div class="col-md-12">
                    <div class="card">
                      <div class="card-header">
                        <h3 class="card-title">Custom Fields</h3>
                        <div class="card-tools">
                          <div class="input-group input-group-sm">
                            <!-- <input type="text" name="table_search" class="form-control float-right" placeholder="Search">

                            <div class="input-group-append">
                              <button type="submit" class="btn btn-default"><i class="fas fa-search"></i></button>
                            </div> -->
                            <a href="javascript:void(0)" id="add_custom_field" class="btn btn-primary" style="float: right;">Add</a>
                          </div>
                        </div>
                      </div>
                      <div class="card-body table-responsive p-2">
                        <table id="custom-fields">
                          <?php if (!empty($product_custom_fields)) {
                            $custom_field = json_decode($product_custom_fields);
                            $i = 0;
                            foreach ($custom_field as $k => $value) {?>
                            <tr data-row=<?php echo $i; ?>>
                              <td><input type="text" name="dynamic_field[<?php echo $i; ?>][0]" class="form-control form-control-sm" value="<?php echo $value[0]; ?>"></td>
                              <td><input type="text" name="dynamic_field[<?php echo $i; ?>][1]" class="form-control form-control-sm" value="<?php echo $value[1]; ?>"></td>
                              <td><a href="javascript:void(0)" class="btn btn-danger remove_row">X</a></td>
                            </tr>
                         <?php $i++; } }?>
                        </table>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Added by Saurabh on 18-05-2022 to select test name and method -->
                <div class="row">
                  <div class="col-md-12">
                    <div class="card">
                      <div class="card-header">
                        <h3 class="card-title">Test and Methods</h3>
                        <div class="card-tools">
                          <div class="input-group input-group-sm">
                            <a href="javascript:void(0)" id="add_test_field" class="btn btn-primary" style="float: right;">Add</a>
                          </div>
                        </div>
                      </div>
                      <div class="card-body table-responsive p-2">
                          <table class="table" id="test_data">
                            <thead>
                              <tr>
                                <th>Test Name</th>
                                <th>Test Method</th>
                                <th>Action</th>
                              </tr>
                            </thead>
                            <input type="hidden" id="row_count" value="<?php echo (!empty($selected_test)) ? count($selected_test) : 0 ?>">
                            <tbody>
                              <?php if (!empty($selected_test)) {
                                foreach ($selected_test as $key => $test) { ?>
                                  <tr>
                                    <input type="hidden" name="test[<?php echo $key; ?>][sample_test_quote_type]" value="<?php echo $test['trf_test_quote_type']?>">
                                    <input type="hidden" name="test[<?php echo $key; ?>][sample_test_work_id]" value="<?php echo $test['trf_work_id']?>">
                                    <input type="hidden" name="test[<?php echo $key; ?>][sample_test_protocol_id]" value="<?php echo $test['trf_test_protocol_id']?>">
                                    <input type="hidden" name="test[<?php echo $key; ?>][sample_test_package_id]" value="<?php echo $test['trf_test_package_id']?>">
                                    <input type="hidden" name="test[<?php echo $key; ?>][sample_test_quote_id]" value="<?php echo $test['trf_test_quote_id']?>">
                                    <input type="hidden" name="test[<?php echo $key; ?>][rate_per_test]" value="<?php echo $test['rate_per_test']?>">
                                    <td>
                                      <select name="test[<?php echo $key; ?>][trf_test_test_id]" id="" class="form-control form-control-sm test<?php echo $key; ?>">
                                        <option value="<?php echo $test['test_id']; ?>"><?php echo $test['test_name']; ?></option>
                                      </select>
                                    </td>
                                    <td>
                                      <select name="test[<?php echo $key; ?>][trf_test_test_method_id]" id="" class="form-control form-control-sm method<?php echo $key; ?>">
                                        <option value="<?php echo $test['test_method_id']; ?>"><?php echo $test['test_method_name']; ?></option>
                                      </select>
                                    </td>
                                    <td><a href="javascript:void(0)" class="btn btn-sm btn-danger remove_test">X</a></td>
                                  </tr>
                              <?php }
                              } ?>
                            </tbody>
                          </table>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- Added by Saurabh on 18-05-2022 to select test name and method -->


                <div class="row">
                  <div class="col-md-12">
                    <div class="card">
                      <div class="card-header">
                        <h3 class="card-title">Application Provided Care Instruction</h3>
                        <div class="card-tools">
                          <div class="input-group input-group-sm">
                            <!-- <input type="text" name="table_search" class="form-control float-right" placeholder="Search">

                            <div class="input-group-append">
                              <button type="submit" class="btn btn-default"><i class="fas fa-search"></i></button>
                            </div> -->
                            <a href="javascript:void(0)" id="add_care_provided_field" class="btn btn-primary" style="float: right;">Add</a>
                          </div>
                        </div>
                      </div>
                      <div class="card-body table-responsive p-2">
                        <table id="care_provided" class="table">
                        <thead>
                          <tr>
                            <th>Application Provided Care Instruction</th>
                            <th>Image</th>
                            <th>Description</th>
                            <th>Image Preference</th>
                            <th>Action</th>
                          </tr>
                        </thead>
                          <tbody>
                          <?php if(!empty($selected_application_care_instruction)){ $i = 0; foreach($selected_application_care_instruction as $s_care_instruction){?>
                            <tr data-row=<?php echo $i;?>>
                              <td>
                                <select class="form-control form-control-sm care_provided" name="dynamic[<?=$i;?>][application_care_id]">
                                <option disabled="" selected>Application Provided Care Instruction</option>
                                <?php foreach ($application_care_instruction as $value) { ?>
                                <option value="<?php echo $value['instruction_id'] ?>" <?php if($value['instruction_id'] == $s_care_instruction['application_care_id']){ echo "selected"; }?>><?php echo $value['instruction_name']; ?></option>
                                <?php } ?>
                                </select>
                              </td>
                              <td class="care_image"><?php if(!empty($s_care_instruction['image'])){ ?><img src="<?php echo $s_care_instruction['image'];?>" alt="Application Care Provided Image"> <?php }?></td><input type="hidden" class="application_image" name="dynamic[<?php echo $i;?>][image]" value="<?php echo $s_care_instruction['image'];?>">
                              <td><textarea name="dynamic[<?=$i;?>][description]" class="form-control form-control-sm"><?php echo $s_care_instruction['description'];?></textarea></td>
                              <td><input type="text" class="form-control form-control-sm" name="dynamic[<?php echo $i;?>][image_sequence]" value="<?php echo $s_care_instruction['image_sequence']; ?>"></td>
                              <td><a href="javascript:void(0)" class="btn btn-danger remove_row">X</a></td>
                            </tr>
                          <?php $i++; } }?>
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                </div> 
                </div>
                <!-- /.card-body -->

                <div class="card-footer">
                  <button type="button" class="btn btn-danger" onclick="location.href='<?php echo base_url('open-trf-list')?>'">Cancel</button>
                  <button type="submit" class="btn btn-primary" style="float: right;" id="submit">Submit</button>
                </div>
              </form>
            </div>
            <!-- /.card -->

          </div>
          <!--/.col (left) -->
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <!-- Script to show dynamic fields -->
<script>
  let row_id = 0;
  let new_col = 0;
  
 <?php if (empty($product_custom_fields)) { ?>
    $(function() {
      var new_row = "";
      new_row += "<tr data-row='" + row_id + "'>";
      new_row += "<td>";
      new_row += "<input type='text' name='dynamic_field[" + row_id + "][" + new_col + "]' class='form-control form-control-sm'>";
      new_row += "</td>";
      new_col++;
      new_row += "<td>";
      new_row += "<input type='text' name='dynamic_field[" + row_id + "][" + new_col + "]' class='form-control form-control-sm'>";
      new_row += "</td>";
      new_row += "<td>";
      new_row += "&nbsp;"
      new_row += "</td>";
      new_col = 0;
      new_row += "</tr>";
      $('#custom-fields').append(new_row);
    });
  <?php } ?>

  $(document).on('click','#add_custom_field', function(){
    var last_row = $('#custom-fields tr:last').data('row');
    var row_id = last_row + 1;
    var  new_row = "";
    new_row += "<tr data-row='"+ row_id+"'>";
    new_row += "<td>";
    new_row += "<input type='text' name='dynamic_field["+row_id+"]["+new_col+"]' class='form-control form-control-sm'>";
    new_row += "</td>";
    new_col++;
    new_row += "<td>";
    new_row += "<input type='text' name='dynamic_field["+row_id+"]["+new_col+"]' class='form-control form-control-sm'>";
    new_row += "</td>";
    new_row += "<td>";
    new_row += "<a href='javascript:void(0)' class='btn btn-danger remove_row'>X</a>"
    new_row += "</td>";
    new_col = 0;
    new_row += "</tr>";
    $('#custom-fields').append(new_row);
  });

  $(document).ready(function () {
    bsCustomFileInput.init();
    $(document).on('click','.remove_row',function () {
      $(this).parents('tr').remove();
    });
  });
</script>
<!-- Script to show application provided care instruction -->
<script>
  let row_index = 0;
    let col_index = 0;
    let row = "";

  $(document).on('click','#add_care_provided_field',function(){
    var last_row = $('#care_provided tbody tr:last').data('row');
    var row_index = last_row + 1;
    row = "";
    row += "<tr data-row="+row_index+">";
    row += "<td>";
    row += '<select class="form-control form-control-sm care_provided" name="dynamic['+row_index+'][application_care_id]">';
    row += '<option disabled="" selected>Application Provided Care Instruction</option>';
    <?php foreach ($application_care_instruction as $value) { ?>
      row += '<option value="<?php echo $value['instruction_id'] ?>" <?php if (in_array($value['instruction_id'], $care_instruction)) { echo "selected"; } ?>><?php echo $value['instruction_name'] ?></option>';
    <?php } ?>
    row += '</select>';
    row += "</td>";
    row += '<td class="care_image"></td><input type="hidden" class="application_image" name="dynamic['+row_index+'][image]">';
    row += '<td><textarea name="dynamic['+row_index+'][description]" class="form-control" placeholder="Enter Description"></textarea></td>';
    row += '<td><input type="text" name="dynamic['+row_index+'][image_sequence]" value="0" class="form-control form-control-sm"></td>';
    row += '<td><a href="javascript:void(0)" class="btn btn-danger remove_row">X</a></td>';
    row += '</tr>';
    row_index++;
    $('#care_provided tbody').append(row);
  });
</script>
<script>
$(document).ready(function(){
  $('#submit').click(function(){
    $('body').append('<div class="pageloader"></div>');
  });

  $(document).on('click', '.remove_test', function(e) {
      var count = $('#test_data tbody tr').length;
      var self = $(this);
      e.preventDefault();
      $('body').append('<div class="pageloader"></div>');
      if (count < 2) {
        $.notify('Please keep atleast one record!.', "error");
        $('.pageloader').remove();
      } else {
        self.parents('tr').remove();
        $('.pageloader').remove();
        $.notify('Test Removed Successfully.', "success");
      }
    });

    $(document).on('click', '#add_test_field', function() {
      key_test = $('#row_count').val();
      key_test = parseInt(key_test) + 1;
      var html = '';
      html += '<tr>';
      html += '<input type="hidden" name="test[' + key_test + '][sample_test_quote_type]" value="">';
      html += '<input type="hidden" name="test[' + key_test + '][sample_test_work_id]" value="">';
      html += '<input type="hidden" name="test[' + key_test + '][sample_test_protocol_id]" value="">';
      html += '<input type="hidden" name="test[' + key_test + '][sample_test_package_id]" value="">';
      html += '<input type="hidden" name="test[' + key_test + '][sample_test_quote_id]" value="">';
      html += '<input type="hidden" name="test[' + key_test + '][rate_per_test]" value="">';
      html += '<td><select name="test[' + key_test + '][trf_test_test_id]" class="form-control form-control-sm test' + key_test + '"></select></td>';
      html += '<td><select name="test[' + key_test + '][trf_test_test_method_id]" class="form-control form-control-sm method' + key_test + '"></select></td>';
      html += '<td><a href="javascrip:void(0)" class="btn btn-sm btn-danger remove_test" data-trf_test_id="">X</a></td>';
      html += '</tr>';
      $('#test_data tbody').append(html);
      $('.test' + key_test).select2({
        allowClear: true,
        ajax: {
          url: "<?php echo base_url('test-name'); ?>",
          dataType: 'json',
          data: function(params) {
            return {
              key: params.term, // search term
              product: $('#product').val(),
            };
          },
          processResults: function(response) {

            return {
              results: response
            };
          },
          cache: true
        },
        placeholder: 'Select Test Name',
        minimumInputLength: 0,
        templateResult: formatRepo,
        templateSelection: formatRepoSelection
      });
      $('.method' + key_test).select2({
        allowClear: true,
        ajax: {
          url: "<?php echo base_url('TestRequestForm_Controller/get_test_method'); ?>",
          dataType: 'json',
          data: function(params) {
            return {
              key: params.term, // search term
              test_id: $(this).parent().prev().children().val(),
            };
          },
          processResults: function(response) {

            return {
              results: response
            };
          },
          cache: true
        },
        placeholder: 'Select Test Method',
        minimumInputLength: 0,
        templateResult: formatRepo,
        templateSelection: formatRepoSelection
      });
      key_test = key_test + 1;
      $('#row_count').val(key_test);
    });

    function formatRepo(repo) {
      if (repo.loading) {
        return repo.text;
      }
      var $container = $(
        "<div class='select2-result-repository clearfix'>" +
        "<div class='select2-result-repository__title'></div>" +
        "</div>"
      );

      $container.find(".select2-result-repository__title").text(repo.name);
      return $container;
    }

    function formatRepoSelection(repo) {
      return repo.full_name || repo.text;
    }
});
</script>
