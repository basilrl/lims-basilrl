<?php  
//$product_custom_fields = $sample_data['product_custom_fields'];
$product_custom_fields = $trf_data[0]['product_custom_fields'];
?>
<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
        <script src="<?php echo base_url(); ?>public/js/validate.js"></script>
  <script src="<?= base_url('assets/js/sample_registration.js') ?>"></script>
    <!-- Content Header (Page header) -->

    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Edit Sample Registration </h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?php echo base_url();?>">Home</a></li>
              <li class="breadcrumb-item"><a href="<?= base_url('open-trf-list');?>">Open TRF List</a></li>
              <li class="breadcrumb-item active">Add Sample</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>
  <?php echo validation_errors();?>
    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <!-- left column -->
          <div class="col-md-12">
            <!-- general form elements -->
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Edit Sample</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
             
              <form id="sample_reg_form" autocomplete="off"  method="post" action="<?=  base_url('SampleRegistrationEdit/edit_sample_reg');?>">
                <div class="card-body">
                  <div class="row">
                  <input type="hidden" name="<?= $this->security->get_csrf_token_name();?>" value="<?= $this->security->get_csrf_hash(); ?>">
                  <input type="hidden" name="sample_customer_id" value="<?= isset($sample_customer_ids) ? $sample_customer_ids :''; ?>">
                  <input type="hidden" name="sample_reg_id" id="sample_reg_id" value="<?= isset($sample_data['sample_reg_id']) ? $sample_data['sample_reg_id'] : ''; ?>">
                  <input type="hidden" name="trf_reg_id" id="trf_id" value="<?= isset($sample_data['trf_registration_id']) ? $sample_data['trf_registration_id'] : ''; ?>">

                    <div class="col-md-6">
                      <div class="form-group">
                        <label>Select Branch</label>
                        <select class="form-control select-box form-control-sm" id="branch_name" name="branch_name" required>
                          <option selected="" disabled="">Select branch</option>
                          <?php  if (!empty($branches)) { 
                              foreach ($branches as $value) { ?>
                             <option value="<?= $value['branch_id']; ?>"         
                             <?php if ($sample_data['sample_registration_branch_id'] == $value['branch_id']){ echo "selected"; } ?>><?php echo $value['branch_name']; ?></option> 
                          <?php } } ?>
                        </select>
                        <?= form_error('branch_name', '<div class="text-danger">', '</div>'); ?>
                      </div>
                    </div>

                    <div class="col-md-6">
                      <div class="form-group">
                        <label>Test Specification</label>
                        <select class="form-control select-box form-control-sm" id="test_standard_id" name="test_standard_id" required>
                            <option selected value=''>Select Test Specification</option>
                          <?php if (!empty($test_specification)) { foreach ($test_specification as $value) { ?>
                             <option value="<?php echo $value['test_standard_id']; ?>"         
                             <?php if ($sample_data['sample_registration_test_standard_id'] == $value['test_standard_id']){ echo "selected"; } ?>><?php echo $value['test_standard_name']; ?></option> 
                          <?php } } ?>
                        </select>
                        <?php echo form_error('branch_name', '<div class="text-danger">', '</div>'); ?>
                      </div>
                    </div>


                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Collection Date</label>
                            <div class="input-group date" id="reservationdate" data-bs-target-input="nearest">
                                <input type="text" class="form-control form-control-sm" 
                                       data-bs-target="#reservationdate" name="collection_date" 
                                       value="<?= isset($sample_data['collection_date']) ? $sample_data['collection_date']:'';?>" readonly>
                                <div class="input-group-append" data-bs-target="#reservationdate" data-bs-toggle="datetimepicker">
                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                </div>
                            </div>
                            <?php echo form_error('collection_date', '<div class="text-danger">', '</div>'); ?>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Recieved Date</label>
                            <div class="input-group date" id="reservationdate" data-bs-target-input="nearest">
                                <input type="text" class="form-control form-control-sm" 
                                       data-bs-target="#reservationdate" name="received_date" 
                                       value="<?= isset($sample_data['received_date']) ? $sample_data['received_date']:''; ?>" readonly>
                                <div class="input-group-append" data-bs-target="#reservationdate" data-bs-toggle="datetimepicker">
                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                </div>
                            </div>
                            <?php echo form_error('received_date', '<div class="text-danger">', '</div>'); ?>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Seal No.</label>
                            <input class="form-control form-control-sm" name="seal_no" value="<?= isset($sample_data['seal_no']) ? $sample_data['seal_no'] : ''; ?>">
                            <?php echo form_error('seal_no', '<div class="text-danger">', '</div>'); ?>
                        </div>
                    </div>



                    <div class="col-md-6">
                      <div class="form-group">
                        <label>Select Product</label>
                        <select class="form-control select-box form-control-sm" id="product" name="sample_registration_sample_type_id">
                          <option selected="" disabled="">Product</option>
                          <option value="<?= $product_details->pid; ?>" selected><?= $product_details->pname; ?></option>
                        </select>
                        <?php echo form_error('sample_registration_sample_type_id', '<div class="text-danger">', '</div>'); ?>
                      </div>                    
                    </div>

                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Qty. Recieved</label>
                                    <input type="number" min="0" class="form-control form-control-sm" data-bs-target="#reservationdate" name="qty_received" 
                                           value="<?= isset($sample_data['qty_received']) ? $sample_data['qty_received'] : ''; ?>">
                                    <?php echo form_error('qty_received', '<div class="text-danger">', '</div>'); ?>
                                </div>
                            </div> 
                            <div class="col-md-6">
                                <label>Unit</label>
                                <select class="form-control select-box form-control-sm" id="qty_unit" name="qty_unit" required>
                                    <option value="" selected> Select Unit</option>
                                    <?php foreach ($units as $value) { ?>
                                    <option value="<?= $value->unit_id; ?>" <?php if($sample_data['qty_unit'] == $value->unit_id){ echo "selected"; }?>><?php echo $value->unit; ?></option>
                                    <?php } ?>
                                </select>
                                <?= form_error('qty_unit', '<div class="text-danger">', '</div>'); ?>
                            </div>   
                        </div>             
                    </div>

                    <div class="col-md-6">
                      <div class="form-group">
                        <label>Sample Description</label>
                        <textarea class="form-control" name="sample_desc" required><?= $sample_data['sample_desc'];?></textarea>
                        <?php echo form_error('sample_desc', '<div class="text-danger">', '</div>'); ?>
                      </div>                    
                    </div>

                    <div class="col-md-6">
                      <div class="form-group">
                        <label>Quanity Description</label>
                        <textarea class="form-control" name="quantity_desc" required><?= $sample_data['quantity_desc']; ?></textarea>
                        <?php echo form_error('quantity_desc', '<div class="text-danger">', '</div>'); ?>
                      </div>                    
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Primary Lab</label>
                            <select class="form-control select-box form-control-sm" id="labs" 
                                    name="assign_sample_registered_to_lab_id" required>
                                <option value="">Select Lab</option>
                            <?php if(!empty($labs_id)){ foreach($labs_id as $lab) {?>
                            <option value="<?php echo $lab['lab_id']; ?>" 
                                <?php if($sample_data['sample_registered_to_lab_id'] == $lab['lab_id']){ echo "selected";}?>><?php echo $lab['lab_name']; ?></option>
                            <?php } }?>
                            </select>
                            <?php echo form_error('assign_sample_registered_to_lab_id', '<div class="text-danger">', '</div>'); ?>
                        </div>                    
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Retain Sample Period</label>
                            <select class="form-control select-box form-control-sm" name="sample_retain_status">
                            <option disabled="">Retain Sample Period</option>
                            <option value="1" <?php if($sample_data['sample_retain_status'] == 1){ echo "selected";} ?>>Yes</option>
                            <option value="0" <?php if($sample_data['sample_retain_status'] == 0){ echo "selected";} ?>>No</option>
                            </select>
                            <?php echo form_error('sample_retain_status', '<div class="text-danger">', '</div>'); ?>
                        </div>                    
                    </div>

                    <!-- Commented by Saurabh on 18-08-2021, not to change test data -->

                    <!-- <div class="col-md-6">
                        <div class="form-group">
                            <label>Test Name (Test Methods)</label>
                            <select class="form-control select-box form-control-sm" multiple id="tests" name="griddata[]" required>
                                <option value="">Select Test</option>
                            <?php if(!empty($selected_test)) {
                                foreach($tests as $new_test){ 
                                foreach($selected_test as $test_sel){?>
                                <option value="<?php echo $new_test['test_id']; ?>" 
                                    <?php if($new_test['test_id'] == $test_sel['test_id']){echo "selected"; } ?>>
                                        <?php echo $new_test['test_name']; ?>
                                </option>
                            <?php } } }?>
                            </select>
                            <?php echo form_error('griddata', '<div class="text-danger">', '</div>'); ?>
                        </div>                    
                    </div> -->
                </div>
                <div class="row">
                  <div class="col-md-12">
                    <div class="card">
                      <div class="card-header">
                        <h3 class="card-title">Custom Fields</h3>
                        <div class="card-tools">
                          <div class="input-group input-group-sm">
                            
                            <a href="javascript:void(0)" onclick="AddRow('custom-fields', 'custom_fields_row')"  id="add_custom_field" class="btn btn-primary" style="float: right;">Add</a>
                          </div>
                        </div>
                      </div>
                      <div class="card-body table-responsive p-2">
                        <table id="custom-fields">
                            
                            
                            
                          <?php if (!empty($product_custom_fields)) {
                            $custom_field = json_decode($product_custom_fields);?>
                            <tr id="custom_fields_row" hidden>
                                <td><input name="dynamic_fields[value1][]" class="form-control form-control-sm"></td>
                                <td>  
                                    <input name="dynamic_fields[value2][]" class="form-control form-control-sm">
                                
                                </td>
                                <td>
                                    <button type="button" class="btn btn-danger" onclick="delRow(this, 'custom-fields')">
                                              <i class="fa fa-trash-alt text-white"></i>
                                    </button>
                                </td>
                            </tr>
                           <?php foreach ($custom_field as $k => $cols) {?>
                            <tr>
                                <td>
                                    <input name="dynamic_fields[value1][]" class="form-control form-control-sm" value="<?= $cols[0]; ?>">
                                </td>
                                 <td>  
                                    <input name="dynamic_fields[value2][]" class="form-control form-control-sm" value="<?= $cols[1]; ?>">
                                  </td>
                                        <td>
                                            <button type="button" class="btn btn-danger" onclick="delRow(this, 'custom-fields')">
                                                <i class="fa fa-trash-alt text-white"></i>
                                            </button>
                                        </td>
                                    </tr>
                               <?php }?>
                                  <?php }else{?>
                                    <tr id="custom_fields_row" hidden>
                                <td><input name="dynamic_fields[value1][]" class="form-control form-control-sm"></td>
                                <td>  
                                    <input name="dynamic_fields[value2][]" class="form-control form-control-sm">
                                
                                </td>
                                <td>
                                    <button type="button" class="btn btn-danger" onclick="delRow(this, 'custom-fields')">
                                              <i class="fa fa-trash-alt text-white"></i>
                                    </button>
                                </td>
                            </tr>
                                    
                                    <tr>
                                <td><input name="dynamic_fields[value1][]" class="form-control form-control-sm"></td>
                                <td>  
                                    <input name="dynamic_fields[value2][]" class="form-control form-control-sm">
                                
                                </td>
                                <td>
                                    <button type="button" class="btn btn-danger" onclick="delRow(this, 'custom-fields')">
                                              <i class="fa fa-trash-alt text-white"></i>
                                    </button>
                                </td>
                            </tr>
                                  <?php }?>
                            
                            
                        </table>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-12">
                    <div class="card">
                      <div class="card-header">
                        <h3 class="card-title">Application Provided Care Instruction</h3>
                        <div class="card-tools">
                          <div class="input-group input-group-sm">
                          
                            <a href="javascript:void(0)" onclick="AddRow('care_provided', 'care_provided_row')" id="add_care_provided_field" class="btn btn-primary" style="float: right;">Add</a>
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
                          <?php if(!empty($selected_application_care_instruction)){?>
                                 <tr id="care_provided_row" hidden>
                                <td>
                                 <select class="form-control form-control-sm care_provided" 
                                         name="dynamic[application_care_id][]">
                                     <option value="" selected>Application Provided Care Instruction</option>
                                <?php 
                                foreach ($application_care_instruction as $value) { ?>
                                <option value="<?= $value['instruction_id'] ?>">
                                        <?= $value['instruction_name']; ?>
                                </option>
                                <?php } ?>
                                </select>
                                </td>
                                
                                <td class="care_image">
                                <?php if(!empty($s_care_instruction['image'])){ ?>
                                    <img src="" alt="Application Care Provided Image">
                                <?php }?>
                                </td>
                                
                              <input type="hidden" class="application_image" name="dynamic[image][]">
                              <td>
                                  <textarea name="dynamic[description][]" class="form-control form-control-sm">
                                    
                                  </textarea>
                              </td>
                              <td>
                                <input type="text" class="form-control form-control-sm" 
                                       name="dynamic[image_sequence][]">
                              </td>
                             
                                <td>
                                    <button type="button" class="btn btn-danger" onclick="delRow(this, 'care_provided')">
                                      <i class="fa fa-trash-alt text-white"></i>
                                    </button>
                                </td>
                            </tr>
                            
                              <?php $i = 0; foreach($selected_application_care_instruction as $s_care_instruction){?>
                            <tr>
                              <td>
                                <select class="form-control form-control-sm care_provided" name="dynamic[application_care_id][]">
                                <option disabled="" selected>Application Provided Care Instruction</option>
                                <?php foreach ($application_care_instruction as $value) { ?>
                                <option value="<?= $value['instruction_id'] ?>" 
                                    <?php if($value['instruction_id'] == $s_care_instruction['application_care_id']){ echo "selected"; }?>><?= $value['instruction_name']; ?></option>
                                <?php } ?>
                                </select>
                              </td>
                              
                              <td class="care_image">
                                  <?php if(!empty($s_care_instruction['image'])){ ?>
                                  <img src="<?= $s_care_instruction['image'];?>" alt="Application Care Provided Image"> <?php }?>
                              </td>
                            <input type="hidden" class="application_image" name="dynamic[image][]" value="<?= $s_care_instruction['image'];?>">
                            
                              <td>
                                  <textarea name="dynamic[description][]" class="form-control form-control-sm"><?php echo $s_care_instruction['description'];?>
                                  </textarea>
                              </td>
                              <td>
                                  <input type="text" class="form-control form-control-sm" 
                                         name="dynamic[image_sequence][]" value="<?= $s_care_instruction['image_sequence']; ?>"></td>
                              <td>
                                  <button type="button" class="btn btn-danger" onclick="delRow(this, 'care_provided')">
                                      <i class="fa fa-trash-alt text-white"></i>
                                    </button>
                              </td>
                            </tr>
                          <?php $i++; } 
                          
                              }else{?>
                            <tr id="care_provided_row" hidden>
                                <td>
                                 <select class="form-control form-control-sm care_provided" 
                                         name="dynamic[application_care_id][]">
                                     <option value="" selected>Application Provided Care Instruction</option>
                                <?php 
                                foreach ($application_care_instruction as $value) { ?>
                                <option value="<?= $value['instruction_id'] ?>">
                                        <?= $value['instruction_name']; ?>
                                </option>
                                <?php } ?>
                                </select>
                                </td>
                                
                                <td class="care_image">
                                <?php if(!empty($s_care_instruction['image'])){ ?>
                                    <img src="" alt="Application Care Provided Image">
                                <?php }?>
                                </td>
                                
                              <input type="hidden" class="application_image" name="dynamic[image][]">
                              <td>
                                  <textarea name="dynamic[description][]" class="form-control form-control-sm">
                                    
                                  </textarea>
                              </td>
                              <td>
                                <input type="text" class="form-control form-control-sm" 
                                       name="dynamic[image_sequence][]">
                              </td>
                             
                                <td>
                                    <button type="button" class="btn btn-danger" onclick="delRow(this, 'care_provided')">
                                      <i class="fa fa-trash-alt text-white"></i>
                                    </button>
                                </td>
                            </tr>
                            
                            <tr>
                                <td>
                                 <select class="form-control form-control-sm care_provided" 
                                         name="dynamic[application_care_id][]">
                                     <option value="">Application Provided Care Instruction</option>
                                <?php foreach ($application_care_instruction as $value) { ?>
                                <option value="<?= $value['instruction_id'] ?>">
                                        <?= $value['instruction_name']; ?>
                                </option>
                                <?php } ?>
                                </select>
                                </td>
                                
                                <td class="care_image">
                                <?php if(!empty($s_care_instruction['image'])){ ?>
                                    <img src="" alt="Application Care Provided Image">
                                <?php }?>
                                </td>
                                
                              <input type="hidden" class="application_image" name="dynamic[image][]">
                              <td>
                                  <textarea name="dynamic[description][]" class="form-control form-control-sm">
                                    
                                  </textarea>
                              </td>
                              <td>
                                <input type="text" class="form-control form-control-sm" 
                                       name="dynamic[image_sequence][]">
                              </td>
                             
                                <td>
                                    <button type="button" class="btn btn-danger" onclick="delRow(this, 'care_provided')">
                                      <i class="fa fa-trash-alt text-white"></i>
                                    </button>
                                </td>
                            </tr>
                              <?php }?>
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                </div> 
                </div>
                <!-- /.card-body -->

                <div class="card-footer">
                  <button type="button" class="btn btn-danger" onclick="location.href='<?php echo base_url('sample-list')?>'">Cancel</button>
                  <button type="submit" class="btn btn-primary" style="float: right;" id="submit">Submit</button>
                </div>
              </form>
            </div>
            <!-- /.card -->

          </div>
          <!--/.col (left) -->
        </div>
       
      </div>
    </section>
   
  </div>
  
  
  <script>
      $('#sample_reg_form').validate({});
      
     function AddRow(tableId, rowId){
      var table = document.getElementById(tableId);
      var _data = document.getElementById(rowId).innerHTML;
      var row = table.insertRow();
      row.innerHTML = _data;
 }
 
 function delRow(currElement, tableId) {
     var parentRowIndex = currElement.parentNode.parentNode.rowIndex;
     document.getElementById(tableId).deleteRow(parentRowIndex);
}    
   </script>


