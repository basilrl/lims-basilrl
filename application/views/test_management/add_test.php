<style>
  form .error {
    color: #ff0000;
    margin-top: 0;
  }
</style>


<div class="content-wrapper">
  <!-- Content Header (Page header) -->

  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>TEST MASTER</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>">Home</a></li>
            <li class="breadcrumb-item"><a href="<?php echo base_url('test_master'); ?>">Test Master</a></li>
            <li class="breadcrumb-item active">Add New</li>
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
          <!-- general form elements -->
          <div class="card card-primary">
            <div class="card-header">
              <h3 class="card-title">Add New Test</h3>
              
            </div>
            <form action="<?php echo base_url('Test_management/Test_master/insert_test') ?>" method="post" enctype="multipart/form-data" id="add_test_form" name="add_test_form" autocomplete="off">
              <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">

              <div class="row p-3">

                <div class="col-md-6 px-3">
                  <label for="">DIVISION:</label>
                  <input class="div_id" type="hidden" value="<?php echo set_value('test_division_id') ?>" name="test_division_id">
                  <input class="form-control  input-sm div_name" value="<?php echo set_value('div_name') ?>" autocomplete="off" name="div_name" type="text" placeholder="Select a Division ... ">
                  <ul class="list-group-item div_list" style="display:none">
                  </ul>
                  <?php echo form_error('test_division_id'); ?>
                </div>


                <div class="col-md-6 px-3">
                  <label for="">LAB TYPE:</label>
                  <input class="lab_id" type="hidden" value="<?php echo set_value('test_lab_type_id') ?>" name="test_lab_type_id">
                  <input class="form-control input-sm lab_name" value="<?php echo set_value('lab_name') ?>" autocomplete="off" name="lab_name" type="text" placeholder="Select a Lab Type... ">
                  <ul class="list-group-item lab_list" style="display:none">
                  </ul>
                  <?php echo form_error('test_lab_type_id'); ?>
                </div>

              </div>

              <div class="row p-3">

                <div class="col-md-6 px-3">
                  <label for="">TEST NAME:</label>
                  <input class="form-control  input-sm" value="<?php echo set_value('test_name'); ?>" name="test_name" type="text" placeholder="">
                  <?php echo form_error('test_name'); ?>
                </div>

                <div class=" col-md-6 px-3">
                  <label for="">TEST METHOD:</label>
                  <!-- <input class="form-control  input-sm" value="<?php echo set_value('test_method'); ?>" name="test_method" type="text" placeholder=""> -->
                  <select name="test_method_id" id="test_method" class="form-control form-control-sm"></select>
                  <?php echo form_error('test_method'); ?>
                </div>

              </div>

              <div class="row p-3">

                <div class="col-md-12 px-3">
                  <label for="">PRODUCT:</label>

                  <select class="list-group-item type_list required" name="test_sample_type_id[]" style="" multiple="true" value="">
                    <?php if ($product) : ?>
                      <?php foreach ($product as $key => $value) : ?>
                        <option value="<?php echo $key ?>" selected><?php echo $value ?></option>
                      <?php endforeach; ?>
                    <?php endif; ?>

                  </select>
                  <?php echo form_error('test_sample_type_id[]'); ?>

                </div>
              </div>

              <div class="row p-3">

                <div class="col-md-6 px-3">
                  <label for="">MIN. SAMPLE QTY UNIT:</label>
                  <input class="form-control  input-sm" name="minimum_quantity" value="<?php echo set_value('minimum_quantity'); ?>" type="number">
                  <?php echo form_error('minimum_quantity'); ?>
                </div>


                <div class=" col-md-6 px-3">
                  <label for="">SET UNITS:</label>
                  <input type="hidden" value="<?php echo set_value('minimum_quantity_units'); ?>" name="minimum_quantity_units" class="unit_id">
                  <input class="form-control  input-sm unit" value="<?php echo set_value('unit_name'); ?>" name="unit_name" type="text" autocomplete="off" placeholder="Set Units..">
                  <ul class="list-group-item unit_list" style="display:none">
                  </ul>
                  <?php echo form_error('minimum_quantity_units'); ?>
                </div>
              </div>

              <div class="row  p-3">

                <div class="col-md-6 px-3">
                  <label for="">REPORT UNIT:</label>
                  <input type="hidden" value="<?php echo set_value('units'); ?>" name="units" class="roport_unit">
                  <input class="form-control  input-sm report_unit_name" value="<?php echo set_value('report_unit_name'); ?>" name="report_unit_name" type="text" autocomplete="off" placeholder="Set Repport Unit...">
                  <ul class="list-group-item report_unit_list" style="display:none">
                  </ul>
                  <?php echo form_error('units'); ?>
                </div>

                <div class="col-md-6 px-3">
                  <label for="">SERVICE TYPE:</label>
                  <select class="form-control service required" name="test_service_type[]" multiple="true" value="">

                    <option class="" data-id="1" value="Regular" <?php echo set_select('test_service_type[]', 'Regular') ?>>Regular</option>
                    <option class="" data-id="2" value="Express" <?php echo set_select('test_service_type[]', 'Express') ?>>Express</option>
                    <option class="" data-id="3" value="Urgent" <?php echo set_select('test_service_type[]', 'Urgent') ?>>Urgent</option>
                  </select>
                  <?php echo form_error('test_service_type[]'); ?>


                </div>

              </div>

              <div class="row p-3">
                <div class="col-md-6 px-3">
                  <label for="">STATUS:</label>
                  <select class="form-control  input-sm" name="test_status">
                    <option value="Active" <?php echo set_select('test_status', 'Active') ?>>ACTIVE</option>
                    <option value="Inactive" <?php echo set_select('test_status', 'Inactive') ?>>IN-ACTIVE</option>
                  </select>
                </div>


                <div class=" col-md-6 px-3">
                  <label for="">TEST DURATION:</label>
                  <input class="form-control  input-sm test_turn_around_time" value="<?php echo set_value('test_turn_around_time'); ?>" name="test_turn_around_time" type="time" placeholder="HH:MM...">
                  <?php echo form_error('test_turn_around_time'); ?>
                </div>

              </div>


              <div class="row p-3">
                <div class="col-md-6 px-3">
                  <label for="">Online Available Test:</label>
                  <select class="form-control  input-sm" name="is_available_customerportal">
                    <option value="#" selected disabled>Select</option>
                    <option value="1" <?php echo set_select('is_available_customerportal', 1) ?>>YES</option>
                    <option value="0" <?php echo set_select('is_available_customerportal', 0) ?>>NO</option>
                  </select>
                  <?php echo form_error('is_available_customerportal'); ?>
                </div>

                <div class="col-md-6 px-3">
                  <label for="">METHOD TYPE</label>
                  <select class="form-control  input-sm units_extra_method" name="method_type" value="">
                    <option value="IHTM" <?php echo set_select('method_type', 'IHTM') ?>>IN HOUSE</option>
                    <option value="SUB_CONTRACT" <?php echo set_select('method_type', 'SUB_CONTRACT') ?>>SUB CONTRACT</option>
                  </select>

                </div>

              </div>

              <div class="row p-3">
              <div class="col-md-6 px-3">
                  <label for="">Under Scope</label>
                  <select class="form-control  input-sm" name="under_scope">
                    <option value="Yes" <?php echo set_select('under_scope', 'Yes') ?>>Yes</option>
                    <option value="No" <?php echo set_select('under_scope', 'No') ?>>No</option>
                  </select>
                  <?php echo form_error('under_scope'); ?>
                </div>
              </div>

              <div class="row mt-2 text-right p-3">
                <div class="col-sm-12 px-3">

                  <a href="<?php echo base_url('test_master') ?>" class="btn btn-primary" type="submit">Back</a>
                  <button class="btn btn-primary add_test_submit" type="submit">Submit</button>
                </div>
              </div>


            </form>
          </div>
          <script src="<?php echo base_url('assets/dist/js/test_management.js'); ?>"></script>

          <script>
            // frond end validation 

            $(document).ready(function() {


              $('.units_extra_method').on('change', function() {
                var method_type = $(this).val();
                if (method_type == 'SUB_CONTRACT') {
                  sub_con_detail = "";
                  sub_con_detail += "<div class='row'>";
                  sub_con_detail += "<div class='col-sm-4'>";
                  sub_con_detail += "<lable>SUB-CONTRACT LAB NAME</label>";
                  sub_con_detail += "<input type='text' class='form-control form-control-sm sub_con_lab' name='sub_contract[sub_contract_lab_name]' />";
                  sub_con_detail += "</div>";

                  sub_con_detail += "<div class='col-sm-4'>";
                  sub_con_detail += "<lable>ADDRESS</label>";
                  sub_con_detail += "<textarea type='text' class='form-control form-control-sm lab_address' name='sub_contract[lab_address]' ></textarea>";
                  sub_con_detail += "</div>";

                  sub_con_detail += "<div class='col-sm-4'>";
                  sub_con_detail += "<lable>PRICE</label>";
                  sub_con_detail += "<input type='number' class='form-control form-control-sm test_price' name='sub_contract[test_price]' placeholder='ENTER PRICE...'>";
                  sub_con_detail += "</div>";
                  sub_con_detail += "</div>";
                  $('.sub_contract_details').html("");
                  $('.sub_contract_details').html(sub_con_detail);
                } else {
                  $('.sub_contract_details').html("");
                }

              })

              $("form[name='add_test_form']").validate({
                rules: {
                  div_name: "required",
                  lab_name: "required",
                  test_name: "required",
                  test_method: "required",
                  test_sample_type_id: "required",
                  minimum_quantity: "required",
                  unit_name: "required",
                  report_unit_name: "required",
                  test_service_type: "required",
                  test_status: "required",
                  is_available_customerportal: "required"
                },
                submitHandler: function(form) {
                  form.submit();
                }
              });

              $('#test_method').select2({
                allowClear: true,
                ajax: {
                  url: "<?php echo base_url('Test_management/Test_master/get_test_method'); ?>",
                  dataType: 'json',
                  data: function(params) {
                    return {
                      key: params.term, // search term
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

            })
          </script>