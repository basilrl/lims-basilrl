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
          <h1>TESTS LIST</h1>
        </div>
        <div class="col-sm-6">
        </div>
      </div>

      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header bg-light">
              <div class="row mb-2">
                <?php if (exist_val('Test_master/add_test', $this->session->userdata('permission'))) { ?>
                  <div class="col-sm-1">
                    <a class="btn btn-sm btn-info add" href="<?php echo base_url('add_test') ?>">Add New</a>
                  </div>
                <?php } ?>
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
                  <input value="<?php echo (($search != 'NULL') ? $search : ""); ?>" id="search" class="form-control form-control-sm" type="text" placeholder="Search by Test Name" aria-label="Search">
                </div>
                <div class="col-sm-2">
                  <button onclick="searchfilter();" type="button" class="btn btn-sm btn-primary">SEARCH</button>
                  <a class="btn btn-sm btn-primary" href="<?php echo base_url('test_master'); ?>">CLEAR</a>

                  <button onclick="export_all_tests();" type="button" class="btn btn-sm btn-primary float-right" title="Export all Tests">
                    <img src="<?php echo base_url('assets/images/imp_excel.png') ?>" alt="Export Excel">
                  </button>
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
                    if ($search) {
                      $search = base64_encode($search);
                    } else {
                      $search = "NULL";
                    }
                    if ($order != "") {
                      $order = $order;
                    } else {
                      $order = "NULL";
                    }
                    ?>

                    <th scope="col"><a href="">SL NO.</a></th>
                    <th scope="col"><a href="<?php echo base_url('test_master/' . $div_id . '/' . $lab_id . '/' . $search . '/' . 'ts.test_name/' . $order) ?>">TEST NAME</a></th>
                    <th scope="col"><a href="<?php echo base_url('test_master/' . $div_id . '/' . $lab_id . '/' . $search . '/' . 'div.division_name/' . $order) ?>">DIVISION</a></th>
                    <th scope="col"><a href="<?php echo base_url('test_master/' . $div_id . '/' . $lab_id . '/' . $search . '/' . 'lab.lab_type_name/' . $order) ?>">LAB TYPE</a></th>
                    <th scope="col"><a href="<?php echo base_url('test_master/' . $div_id . '/' . $lab_id . '/' . $search . '/' . 'ts.test_method/' . $order) ?>">TEST METHOD</a></th>
                    <th scope="col"><a href="<?php echo base_url('test_master/' . $div_id . '/' . $lab_id . '/' . $search . '/' . 'ts.method_type/' . $order) ?>">METHOD TYPE</a></th>
                    <th scope="col"><a href="<?php echo base_url('test_master/' . $div_id . '/' . $lab_id . '/' . $search . '/' . 'ts.created_on/' . $order) ?>">CREATED ON</a></th>
                    <th scope="col"><a href="<?php echo base_url('test_master/' . $div_id . '/' . $lab_id . '/' . $search . '/' . 'admin.admin_fname/' . $order) ?>">CREATED BY</a></th>
                    <th scope="col"><a href="<?php echo base_url('test_master/' . $div_id . '/' . $lab_id . '/' . $search . '/' . 'test_status/' . $order) ?>">STATUS</a></th>
                    <th scope="col"><a href="<?php echo base_url('test_master/' . $div_id . '/' . $lab_id . '/' . $search . '/' . 'ts.is_available_customerportal/' . $order) ?>">ONLINE SHOW</a></th>
                    <th scope="col"><a href="">ACTION</a></th>
                  </tr>
                </thead>
                <tbody>
                  <?php (empty($this->uri->segment(7))) ? $sn = 1 : $sn = $this->uri->segment(7) + 1; ?>
                  <?php if ($test_list) : ?>
                    <?php foreach ($test_list as $item) : ?>

                      <tr>
                        <th><?= $sn; ?></th>
                        <td><?= $item->test_name ?></td>
                        <td><?= $item->div_name ?></td>
                        <td><?= $item->lab_name ?></td>
                        <td><?= $item->test_method ?>
                        <td><?= ($item->method_type == 'IHTM') ? "IN HOUSE" : "SUB CONTRACT"; ?>
                        <td><?= $item->created_on ?>
                        <td><?= $item->created_by ?>
                        <td><?= $item->test_status ?>
                        <td><?= $item->online_show ?>
                        <td>

                          <?php if (exist_val('Test_master/edit_test', $this->session->userdata('permission'))) { ?>
                            <a href="<?php echo base_url('edit_test/' . $item->test_id) ?>" class="btn btn-sm btn-default" title="Edit Test"> <img src="<?php echo base_url('assets/images/mem_edit.png') ?>" alt="edit test"></a>
                            <button type="button" class="btn btn-sm btn-default test_price_test" data-id="<?php echo $item->test_id; ?>" data-bs-toggle="modal" data-bs-target="#testpriceModal" title="Test Price">
                              <img src="<?php echo base_url('assets/images/price.png') ?>">
                            </button>
                          <?php } ?>

                          <!-- Added by CHANDAN --09-04-2022 -->
                          <?php if (exist_val('Test_master/add_parameter', $this->session->userdata('permission'))) { ?>
                            <button type="button" class="btn btn-sm btn-default add_parameter" title="ADD PARAMETER" data-id="<?php echo $item->test_id ?>">
                              <img src="<?php echo base_url('assets/images/add.png') ?>">
                            </button>
                          <?php } ?>

                          <?php if (exist_val('Test_master/view_parameter', $this->session->userdata('permission'))) { ?>
                            <button type="button" class="btn btn-sm btn-default view_parameter" title="VIEW PARAMETER" data-id="<?php echo $item->test_id ?>">
                              <img src="<?php echo base_url('assets/images/view.png') ?>">
                            </button>
                          <?php } ?>

                          <?php if (exist_val('Test_master/import_parameter', $this->session->userdata('permission'))) { ?>
                            <button type="button" class="btn btn-sm btn-default import_parameter" title="IMPORT PARAMETER" data-id="<?php echo $item->test_id ?>">
                              <img src="<?php echo base_url('assets/images/page_excel.png') ?>">
                            </button>
                          <?php } ?>
                          <!-- End -->

                          <?php if (exist_val('Test_master/get_log_data', $this->session->userdata('permission'))) { ?>
                            <button type="button" class="btn btn-sm btn-default log_view" title="USER LOG" data-id="<?php echo $item->test_id ?>" data-bs-toggle="modal" data-bs-target="#lo_view_target">
                              <img src="<?php echo base_url('assets/images/log-view.png') ?>">
                            </button>
                          <?php } ?>
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
            <h5 class="text-dark"><b>Note: </b> "PARAMETER NAME" and "PRIORITY ORDER" are mandatory fields.</h5>
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
  $('.test_price_test').click(function() {
    var test_id = $(this).data('id');
    // alert(package_id);
    $('#test_id').attr('value', test_id);
  })
</script>

<script>
  function searchfilter() {

    let url = '<?php echo base_url("test_master"); ?>';

    let div_id = $('.div_id').val();
    let lab_id = $('.lab_id').val();
    let search = $('#search').val();

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
    if (search != '') {
      url = url + '/' + btoa(search);
    } else {
      url = url + '/NULL';
    }

    window.location.href = url;
  }

  function export_all_tests() {

    let url = '<?php echo base_url("Test_management/Test_master/export_all_tests"); ?>';

    let div_id = $('.div_id').val();
    let lab_id = $('.lab_id').val();
    let search = $('#search').val();

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
    if (search != '') {
      url = url + '/' + btoa(search);
    } else {
      url = url + '/NULL';
    }

    window.location.href = url;
  }
</script>

<script>
  // Added by CHANDAN --09-04-2022
  function fetch_units() {
    $.ajax({
      url: '<?php echo base_url("Test_management/Test_master/fetch_units") ?>',
      async: false,
      global: false,
      dataType: 'json',
      success: function(data) {
        $('#test_parameters_unit').find('option').remove();
        $('#test_parameters_unit').append($('<option value=""></option>').text('Select Unit'));
        $.each(data, function(x, y) {
          $('#test_parameters_unit').append($('<option></option>').attr('value', y.unit_id).text(y.unit));
        });
        $('#add_parameter_modal').modal('show');
      }
    });
  }

  $(document).ready(function() {

    // Added by CHANDAN --09-04-2022
    $(document).on('click', '.add_parameter', function() {
      let test_id = $(this).attr('data-id');
      if (test_id.length > 0) {
        $('#test_parameters_id, #clouse, #test_parameters_name, #category, #parameter_limit, #requirement, #priority_order, #result_component_type, #test_parameters_unit, #min_values, #max_values').val('');
        $('#test_parameters_test_id').val(test_id);
        fetch_units();
      }
    });

    $(document).on('click', '.edit_parameter', function() {
      let para_id = $(this).attr('data-id');
      const _tokken = $('meta[name="_tokken"]').attr('value');
      if (para_id.length > 0) {
        $.ajax({
          url: '<?php echo base_url("Test_management/Test_master/edit_parameter") ?>',
          type: 'post',
          data: {
            para_id: para_id,
            _tokken: _tokken
          },
          beforeSend: function() {
            $('#view_parameter_modal').modal('hide');
            fetch_units();
          },
          dataType: 'json',
          success: function(data) {
            $('#test_parameters_id').val(para_id);
            $('#test_parameters_test_id').val(data.test_parameters_test_id);
            $('#clouse').val(data.clouse);
            $('#test_parameters_name').val(data.test_parameters_name);
            $('#category').val(data.category);
            $('#parameter_limit').val(data.parameter_limit);
            $('#requirement').val(data.requirement);
            $('#priority_order').val(data.priority_order);
            $('#result_component_type').val(data.result_component_type);
            $('#test_parameters_unit').val(data.test_parameters_unit);
            $('#show_in_report').val(data.show_in_report);
            $('#mandatory').val(data.mandatory);
            $('#min_values').val(data.min_values);
            $('#max_values').val(data.max_values);
          }
        });
      }
    });

    $(document).on('click', '.import_parameter', function() {
      let test_id = $(this).attr('data-id');
      const _tokken = $('meta[name="_tokken"]').attr('value');
      if (test_id.length > 0) {
        $('#import_test_id').val(test_id);
        $('#import_parameter_modal').modal('show');
      }
    });

    $(document).on('click', '.view_parameter', function() {
      let test_id = $(this).attr('data-id');
      const _tokken = $('meta[name="_tokken"]').attr('value');
      if (test_id.length > 0) {
        $.ajax({
          url: '<?php echo base_url("Test_management/Test_master/view_parameter") ?>',
          type: 'post',
          data: {
            test_id: test_id,
            _tokken: _tokken
          },
          beforeSend: function() {
            $('#view_parameter_modal').modal('show');
            $('#view_parameter_html').html('<h4 class="text-center">Please wait...</h4>');
          },
          dataType: 'html',
          success: function(data) {
            $('#view_parameter_html').html(data);
          }
        });
      }
    });

    $(document).on('click', '.delete_parameter', function() {
      let para_id = $(this).attr('data-id');
      const _tokken = $('meta[name="_tokken"]').attr('value');
      if (para_id.length > 0) {
        let cnf = confirm("Are you want to delete Parameter?");
        if (cnf == true) {
          $.ajax({
            url: '<?php echo base_url("Test_management/Test_master/delete_parameter") ?>',
            type: 'post',
            data: {
              para_id: para_id,
              _tokken: _tokken
            },
            dataType: 'json',
            success: function(data) {
              if (data.code == 1) {
                $('#del_rows_' + para_id).remove();
              }
              $.notify(data.message, {
                position: 'top center',
                className: (data.code == 1) ? 'success' : 'primary'
              });
            }
          });
        }
      }
    });

    $('#add_parameter_form').on('submit', function(e) {
      e.preventDefault();
      $.ajax({
        url: '<?php echo base_url("Test_management/Test_master/add_parameter") ?>',
        type: 'post',
        data: $('#add_parameter_form').serialize(),
        beforeSend: function() {
          $('#add_parameter_submit').prop('disabled', true);
        },
        dataType: 'json',
        success: function(data) {
          $('#add_parameter_submit').prop('disabled', false);
          if (data.message) {
            $.notify(data.message, {
              position: 'top center',
              className: (data.code == 1) ? 'success' : 'primary'
            });
          }
          if (data.code == 1) {
            $('#add_parameter_form')[0].reset();
            $('#test_parameters_test_id').val('');
            $('#add_parameter_modal').modal('hide');
          } else {
            if (data.error) {
              $.each(data.error, function(key, value) {
                $('#error-' + key).notify(value, {
                  position: 'bottom center'
                });
              });
            }
          }
        }
      });
    });

    $(document).on('submit', '#import_parameter_form', function(e) {
      e.preventDefault();
      const _tokken = $('meta[name="_tokken"]').attr('value');
      let import_test_id = $('#import_test_id').val();
      if (import_test_id.length > 0) {
        if ($.trim($('#import_parameter_file').val()).length < 1) {
          $('#import_parameter_msg').text('Please choose csv file.');
          return false;
        } else {
          let property = document.getElementById('import_parameter_file').files[0];
          let img_name = property.name;
          let extension = img_name.split('.').pop().toLowerCase();
          if ($.inArray(extension, ['csv']) == -1) {
            $('#import_parameter_msg').text('Invalid file extension!');
            return false;
          } else {
            let form_data = new FormData(this);
            $.ajax({
              url: '<?php echo site_url("Test_management/Test_master/import_parameter"); ?>',
              method: 'POST',
              data: form_data,
              beforeSend: function() {
                $('#import_parameter_msg').html('<h6>Importing data, Please wait...</h6>');
                $('#import_parameter_submit').prop('disabled', true);
              },
              dataType: 'json',
              contentType: false,
              cache: false,
              processData: false,
              success: function(data) {
                $('#import_parameter_msg').html('');
                $('#import_parameter_submit').prop('disabled', false);
                if (data.status > 0) {
                  $('#import_test_id').val('');
                  $.notify(data.message, "success");
                  $('#import_parameter_modal').modal('hide');
                } else {
                  $.notify(data.message, "error");
                }
              }
            });
          }
        }
      }
    });
    // End....

    $('.test_price_test').on('click', function() {
      var test_id = $(this).attr('data-id');
      const _tokken = $('meta[name="_tokken"]').attr('value');
      $.ajax({
        url: '<?php echo base_url("Test_management/Test_master/get_price_list_test") ?>',
        method: 'post',
        data: {
          test_id: test_id,
          _tokken: _tokken
        },
        success: function(data) {
          var html = $.parseJSON(data);
          $('.test_price_table tbody').html("");
          var w = 1;
          if (html) {
            $.each(html, function(index, value) {

              $('.test_price_table tbody').append($('<tr><td><input type="text" class="form-control form-control-sm" name="row[' + index + '][sl_number]" value="' + w + '" readonly></td><td><input type="text" class="form-control form-control-sm" name="row[' + index + '][country_code]" value="' + value.country_code + '" readonly></td><td><input type="text" class="form-control form-control-sm" name="row[' + index + '][currency_code]" value="' + value.currency_code + '" readonly></td><td><input type="text" class="form-control form-control-sm" name="row[' + index + '][price]" value="' + value.price + '"></td></tr>'));
              w++;
            })
          }
        }

      })
    });



    $('#testPriceFOrm').submit(function(e) {
      e.preventDefault();
      $.ajax({
        url: "<?php echo base_url('Test_management/Test_master/save_test_price') ?>",
        method: "POST",
        data: $(this).serialize(),
        success: function(response) {
          var data = $.parseJSON(response);
          if (data.status > 0) {
            $.notify(data.msg, 'success');
            $('#testpriceModal').modal('hide');
          } else {
            $.notify(data.msg, 'error');
          }
        }
      });
      return false;
    });




    // import test
    $(document).on('submit', '#importTest', function(event) {
      event.preventDefault('#importTest');
      var formData = new FormData(this);
      $.ajax({
        url: "<?php echo base_url('Test_management/Test_master/import_excel') ?>",
        method: "POST",
        data: formData,
        processData: false,
        contentType: false,
        success: function(data) {
          var result = $.parseJSON(data);
          if (result.status > 0) {
            location.reload();
          } else {
            $.notify(result.msg, 'error');
          }
        }
      })

    })

    // import sub parameters
    $(document).on('submit', '#importSub', function(event) {
      event.preventDefault('#importSub');
      var formData = new FormData(this);
      $.ajax({
        url: "<?php echo base_url('Test_management/Test_master/import_subpara') ?>",
        method: "POST",
        data: formData,
        processData: false,
        contentType: false,
        success: function(data) {
          var result = $.parseJSON(data);
          if (result.status > 0) {
            location.reload();
          } else {
            $.notify(result.msg, 'error');
          }
        }
      })

    })
  });
</script>


<script>
  $(document).ready(function() {
    const url = $('body').data('url');
    const _tokken = $('meta[name="_tokken"]').attr('value');
    // Ajax call to get log
    $('.log_view').click(function() {
      $('#table_log').empty();
      var id = $(this).data('id');
      $.ajax({
        type: 'post',
        url: url + 'Test_management/Test_master/get_log_data',
        data: {
          _tokken: _tokken,
          id: id
        },
        success: function(data) {
          var data = $.parseJSON(data);
          var value = '';
          sno = Number();
          $.each(data, function(i, v) {
            sno += 1;
            var operation = v.action_taken;
            var action_message = v.text;
            var taken_by = v.taken_by;
            var taken_at = v.taken_at;
            value += '<tr>';
            value += '<td>' + sno + '</td>';
            value += '<td>' + operation + '</td>';
            value += '<td>' + action_message + '</td>';
            value += '<td>' + taken_by + '</td>';
            value += '<td>' + taken_at + '</td>';
            value += '</tr>';

          });
          $('#table_log').append(value);
        }
      });
    });
    // ajax call to get log ends here
  });
</script>