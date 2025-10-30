<link rel="stylesheet" href="<?php echo base_url('assets/dataTables/css/dataTables.bootstrap4.min.css'); ?>">

<script src="<?php echo base_url('assets/dataTables/js/jquery.dataTables.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/dataTables/js/dataTables.bootstrap4.min.js'); ?>"></script>

<style>
  .table-responsive {
    overflow-x: hidden;
  }
</style>

<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>TEST PARAMETERS LIST</h1>
        </div>
        <div class="col-sm-6">
        </div>
      </div>

      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header bg-light">
              <div class="row mb-2">
              
                <div class="col-sm-2">
                  <input class="div_id" type="hidden" value="<?php echo ($div_id) ? $div_id : '' ?>" name="div_id">
                  <input class="form-control form-control-sm input-sm div_name" value="<?php echo ($div_name) ? $div_name->division_name : ''; ?>" autocomplete="off" name="div_name" type="text" placeholder="Select a Division ... ">
                  <ul class="list-group-item div_list" style="display:none"></ul>
                </div>
                <div class="col-sm-2">
                  <input class="lab_id" type="hidden" value="<?php echo ($lab_id) ? $lab_id : '' ?>" name="lab_id">
                  <input class="form-control form-control-sm  input-sm lab_name" value="<?php echo ($lab_name) ? $lab_name->lab_type_name : ''; ?>" autocomplete="off" name="lab_name" type="text" placeholder="Select a Lab Type... ">
                  <ul class="list-group-item lab_list" style="display:none"></ul>
                </div>
                <div class="col-sm-5">
                  <input value="<?php echo (($test_name != 'NULL') ? $test_name : ""); ?>" id="test_name" class="form-control form-control-sm" type="text" placeholder="Search by Test Name" aria-label="Search">
                </div>
                <div class="col-sm-5">
                  <input value="<?php echo (($test_parameter != 'NULL') ? $test_parameter : ""); ?>" id="test_parameter" class="form-control form-control-sm" type="text" placeholder="Search by Test Paramter " aria-label="Search">
                </div>
                <div class="col-sm-5">
                  <input value="<?php echo (($created_by != 'NULL') ? $created_by : ""); ?>" id="created_by" class="form-control form-control-sm" type="text" placeholder="Search by  Created by..." aria-label="Search">
                </div>
                <div class="col-sm-2">
                  <button onclick="searchfilter();" type="button" class="btn btn-sm btn-primary">SEARCH</button>
                  <a class="btn btn-sm btn-primary" href="<?php echo base_url('test_master'); ?>">CLEAR</a>
                </div>
              </div>
              <div class="row">
                <div class="col-sm-2">
                  <?php if (exist_val('Test_master/import_excel', $this->session->userdata('permission'))) { ?>
                    <!-- <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#import_test">
                      <img src="<?php echo base_url('assets/images/import_icon.jpg') ?>" alt="Import Test">Import Test
                      </button>
                      <?php } ?> -->
                </div>
                <!-- <div class="col-sm-2">
                      <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#import_sub_pera">
                      <img src="<?php echo base_url('assets/images/import_icon.jpg') ?>" alt="Import Sub Parameters">Import Sub Parameters
                      </button>
                </div>
                 -->
              </div>
            </div>

            <div class="table-responsive">
              <table id="emp_table" class="table table-hover table-sm">
                <thead>
                  <tr class="table-primary">
                    <?php
                    if ($div_id != "") {
                      $div_id = $div_id;
                    } else {
                      $div_id = "NULL";
                    }
                    if ($lab_id != "") {
                      $lab_id = $lab_id;
                    } else {
                      $lab_id = "NULL";
                    }
                    if ($test_name) {
                      $test_name = base64_encode($test_name);
                    } else {
                      $test_name = "NULL";
                    }
                    if ($test_parameter) {
                      $test_parameter = base64_encode($test_parameter);
                    } else {
                      $test_parameter = "NULL";
                    }
                    if ($created_by) {
                      $created_by = base64_encode($created_by);
                    } else {
                      $created_by = "NULL";
                    }
                    if ($order != "") {
                      $order = $order;
                    } else {
                      $order = "NULL";
                    }
                    ?>

                    <th scope="col"><a href="">SL NO.</a></th>
                    <th scope="col"><a href="<?php echo base_url('test_parameters/' . $div_id . '/' . $lab_id . '/' . $test_name . '/' . $test_parameter . '/' . $created_by. '/' . 'tp.clause/' . $order) ?>">Clause</a></th>
                    <th scope="col"><a href="<?php echo base_url('test_parameters/' . $div_id . '/' . $lab_id . '/' . $test_name . '/' . $test_parameter . '/' . $created_by. '/'  . 'tp.test_paramter/' . $order) ?>">TEST PARAMETER NAME</a></th>
                    <th scope="col"><a href="<?php echo base_url('test_parameters/' . $div_id . '/' . $lab_id . '/' . $test_name . '/' . $test_parameter . '/' . $created_by. '/' . 'tp.requirement/' . $order) ?>">Requirement</a></th>
                    <th scope="col"><a href="<?php echo base_url('test_parameters/' . $div_id . '/' . $lab_id . '/' . $test_name . '/' . $test_parameter . '/' . $created_by. '/'  . 'tp.parameter_limit/' . $order) ?>">Limitation</a></th>
                    <th scope="col"><a href="<?php echo base_url('test_parameters/' . $div_id . '/' . $lab_id . '/' . $test_name . '/' . $test_parameter . '/' . $created_by. '/' . 'tp.category/' . $order) ?>">Category</a></th>
                    <th scope="col"><a href="<?php echo base_url('test_parameters/' . $div_id . '/' . $lab_id . '/' . $test_name . '/' . $test_parameter . '/' . $created_by. '/'  . 'ts.test_name/' . $order) ?>">Test Name</a></th>
                    <th scope="col"><a href="<?php echo base_url('test_parameters/' . $div_id . '/' . $lab_id . '/' . $test_name . '/' . $test_parameter . '/' . $created_by. '/'  . 'tp.created_on/' . $order) ?>">CREATED ON</a></th>
                    <th scope="col"><a href="<?php echo base_url('test_parameters/' . $div_id . '/' . $lab_id . '/' . $test_name . '/' . $test_parameter . '/' . $created_by. '/'  . 'admin.admin_fname/' . $order) ?>">CREATED BY</a></th>
                    <th scope="col"><a href="">ACTION</a></th>
                  </tr>
                </thead>
                <tbody>
                  <?php (empty($this->uri->segment(10))) ? $sn = 1 : $sn = $this->uri->segment(10) + 1; ?>
                  <?php if ($test_list) : ?>
                    <?php foreach ($test_list as $item) : ?>

                      <tr>
                        <th><?= $sn; ?></th>
                        <td><?php echo isset($item->clause)?$item->clause:''; ?></td>
                        <td><?= $item->test_parameters_disp_name ?></td>
                        <td><?= $item->requirement ?></td>
                        <td><?= $item->parameter_limit ?></td>
                        <td><?= $item->category ?>
                        <td><?= $item->test_name ?>
                        
                        <td><?= $item->created_on ?>
                        <td><?= $item->created_by ?>
                       
                        <td></td>
                        <td>

                         
                        </td>
                      </tr>
                    <?php $sn++;
                    endforeach; ?>
                  <?php endif; ?>
                  <?php if ($test_list == NULL) : ?>
                    <tr>
                      <td>NO RECORD FOUND</td>
                    </tr>
                  <?php endif; ?>
                </tbody>
              </table>
            </div>
          </div>

          <!-- Pagination -->
          <div class="card-header">
            <span><?php echo $links ?></span>
            <?php if (count($test_list) > 0) : ?>
              <span><?php echo $result_count; ?></span>
            <?php endif; ?>
          </div>
          <!-- /.card-body -->
        </div>
        <!-- /.card -->
      </div>
    </div>
    <!-- /.row -->
</div><!-- /.container-fluid -->
</section>
<!-- /.content -->
</div>

<!-- Added by CHANDAN --09-04-2022 -->
<div class="modal fade" id="add_parameter_modal" tabindex="-1">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">TEST PARAMETER</h5>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form method="post" id="add_parameter_form" autocomplete="off">
        <div class="modal-body">
          <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
          <input type="hidden" name="test_parameters_id" id="test_parameters_id" />
          <input type="hidden" name="test_parameters_test_id" id="test_parameters_test_id" />
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="clouse">CLOUSE:</label>
                <input type="text" class="form-control" name="clouse" id="clouse" />
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="test_parameters_name">PARAMETER NAME:<span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="test_parameters_name" id="test_parameters_name" />
                <p id="error-test_parameters_name"></p>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="category">CATEGORY:</label>
                <input type="text" class="form-control" name="category" id="category" />
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="parameter_limit">LIMITATION:</label>
                <input type="text" class="form-control" name="parameter_limit" id="parameter_limit" />
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="requirement">REQUIREMENT:</label>
                <input type="text" class="form-control" name="requirement" id="requirement" />
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label for="priority_order">PRIORITY ORDER:<span class="text-danger">*</span></label>
                <input type="number" class="form-control" name="priority_order" id="priority_order" />
                <p id="error-priority_order"></p>
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label for="result_component_type">PARAMETER TYPE:<span class="text-danger">*</span></label>
                <select class="form-control" name="result_component_type" id="result_component_type">
                  <option value="">Select Parameter Type</option>
                  <option value="Parameter">Parameter</option>
                  <option value="Sub Parameter">Sub Parameter</option>
                  <option value="Step">Step</option>
                  <option value="Istomers">Istomers</option>
                  <option value="Solvent">Solvent</option>
                </select>
                <p id="error-result_component_type"></p>
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label for="test_parameters_unit">UNIT:</label>
                <select class="form-control" name="test_parameters_unit" id="test_parameters_unit">
                </select>
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label for="show_in_report">SHOW IN REPORT:<span class="text-danger">*</span></label>
                <select class="form-control" name="show_in_report" id="show_in_report">
                  <option value="Yes">Yes</option>
                  <option value="No">No</option>
                </select>
              </div>
            </div>
            <div class="col-md-2">
              <div class="form-group">
                <label for="mandatory">MANDATORY:<span class="text-danger">*</span></label>
                <select class="form-control" name="mandatory" id="mandatory">
                  <option value="Yes">Yes</option>
                  <option value="No">No</option>
                </select>
              </div>
            </div>
            <div class="col-md-2">
              <div class="form-group">
                <label for="min_values">MIN-VALUE:</label>
                <input type="text" class="form-control" name="min_values" id="min_values" />
              </div>
            </div>
            <div class="col-md-2">
              <div class="form-group">
                <label for="max_values">MAX-VALUE:</label>
                <input type="text" class="form-control" name="max_values" id="max_values" />
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">CLOSE</button>
          <button type="submit" class="btn btn-primary" id="add_parameter_submit">SUBMIT</button>
        </div>
      </form>
    </div>
  </div>
</div>

<div class="modal fade" id="view_parameter_modal" tabindex="-1">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">VIEW PARAMETERS</h5>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div id="view_parameter_html"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">CLOSE</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="import_parameter_modal" tabindex="-1">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">IMPORT PARAMETERS</h5>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-7">
            <a href="<?php echo base_url('assets/sample_csv/SAMPLE_TEST_PARAMETER.csv'); ?>" download class="btn btn-info mb-4">Download ParameterSample Sheet</a>
            <h4 class="mt-3 text-primary">Instructions for Parameter Import:</h4>
            <table class="table table-bordered">
              <thead>
                <tr>
                  <th>PRIORITY ORDER</th>
                  <th>PARAMETER TYPE</th>
                  <th>SHOW IN REPORT</th>
                  <th>MANDATORY</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>Should be an integer.</td>
                  <td>Only allow (Parameter, Sub Parameter, Step, Istomers, Solvent).</td>
                  <td>Only allow (Yes, No).</td>
                  <td>Only allow (Yes, No).</td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="col-md-1"></div>
          <div class="col-md-4">
            <span id="import_parameter_msg" class="text-danger"></span>
            <form method="post" id="import_parameter_form" enctype="multipart/form-data">
              <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
              <input type="hidden" name="import_test_id" id="import_test_id" />
              <div class="form-group">
                <div class="input-group">
                  <div class="custom-file">
                    <input type="file" class="custom-file-input" name="import_parameter_file" id="import_parameter_file" accept=".csv" />
                    <label class="custom-file-label" for="import_parameter_file">Choose CSV file</label>
                  </div>
                </div>
              </div>
              <p class="text-center">
                <button type="submit" class="btn btn-primary" id="import_parameter_submit">IMPORT NOW</button>
              </p>
            </form>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">CLOSE</button>
      </div>
    </div>
  </div>
</div>
<!-- End -->

<div class="modal fade" id="lo_view_target" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content" style="max-height: 500px;">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">USER LOG</h5>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <table class="table">
          <thead>
            <tr>
              <th>SL.No.</th>
              <th>Operation</th>
              <th>Action</th>
              <th>Performed By</th>
              <th>Performed at</th>
            </tr>
          </thead>
          <tbody id="table_log"></tbody>
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- import test -->
<div class="modal fade" id="import_test" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Import Test</h5>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form method="post" enctype="multipart/form-data" id="importTest">
        <div class="modal-body">

          <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">

          <input class="primary form-control" type="file" name="excel_import_test">
          <br>
          <div class="col-sm-12">
            Please ensure excel file you are going to upload complies with all business rules (unique values, minimum length etc)?<a href='<?php echo base_url("public/file/Analytical_Test.xlsx") ?>' target="_blank">Click Here</a> for sample format

          </div>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary ">Submit</button>
        </div>
      </form>
    </div>
  </div>
</div>


<!-- import sub parameters
<div class="modal fade" id="import_sub_pera" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Import Sub Parameters</h5>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form method="post" enctype="multipart/form-data" id="importSub">
                  <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">

                  <input class="primary form-control" type="file" name="import_sub_parameter" >
                  <br>
                  <div class="col-sm-12">
                  Please ensure excel file you are going to upload complies with all business rules (unique values, minimum length etc)?<a href='<?php echo base_url("public/file/Analytical_Test_Parameter.xlsx") ?>' target="_blank">Click Here</a> for sample format
                  
                  </div>
          
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary import_excel">Submit</button>
      </div>
      </form>
    </div>
  </div>
</div> -->

<div class="modal fade " id="testpriceModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Price Lists</h5>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="" method="post" id="testPriceFOrm">
        <div class="modal-body">

          <!-- <div class="row">
          <button class="btn btn-sm btn-primary" id="addPrice">ADD</button>
        </div> -->

          <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
          <input type="hidden" value="" id="test_id" class="test_id" name="tests_test_id">

          <div class="table-responsive">
            <div class="container">
              <div class="row">
                <div class="col-md-12">

                  <table class="table table-sm test_price_table ">
                    <thead>
                      <tr>
                        <th>SL No.</th>
                        <th>Country code</th>
                        <th>Currency code</th>
                        <th>Price</th>
                      </tr>
                    </thead>
                    <tbody>


                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>


        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">CLOSE</button>
          <button type="submit" class="btn btn-primary savePriceBTN" data-id="" style="display:inline-block">SAVE</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script src="<?php echo base_url('assets/dist/js/test_management.js'); ?>"></script>


<script>
  function searchfilter() {

    let url = '<?php echo base_url("test_parameters/index"); ?>';

    let div_id = $('.div_id').val();
    let lab_id = $('.lab_id').val();
    let test_name = $('#test_name').val();
    let test_parameter = $('#test_parameter').val();
    let created_by = $('#created_by').val();

    if (div_id != '') {
      url = url + '/' + div_id;
    } else {
      url = url + '/NULL';
    }
    if (lab_id != '') {
      url = url + '/' + lab_id;
    } else {
      url = url + '/NULL';
    }
    if (test_name != '') {
      url = url + '/' + btoa(test_name);
    } else {
      url = url + '/NULL';
    }
    if (test_parameter != '') {
      url = url + '/' + btoa(test_parameter);
    } else {
      url = url + '/NULL';
    }
    if (created_by != '') {
      url = url + '/' + btoa(created_by);
    } else {
      url = url + '/NULL';
    }

    window.location.href = url;
  }
</script>

<script>

</script>