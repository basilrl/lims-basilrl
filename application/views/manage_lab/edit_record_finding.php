<script src="<?php echo base_url(); ?>ckeditor/ckeditor.js"></script>
<script src="<?php echo base_url(); ?>assets/dist/js/test_management.js"></script>
<style>
    .delimage {
        border: 1px solid black;
        padding: 0px 6px;
        background: white;
        font-weight: bold;
        font-size: 16px;
    }
</style>

<div class="content-wrapper">
    <div class="container-fluid pl-5 pr-5">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h4 class="text-primary"><b>EDIT RECORD FINDING</b></h4>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-right">
                    <li class="breadcrumb-item"><a href="<?php echo base_url();; ?>">Home</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo base_url('Manage_lab/record_finding') ?>">Edit Record Finding List</a></li>
                </ol>
            </div>
        </div>

        <div class="card">
            <div class="card-header bg-info text-white">
                <h3 class="card-title">Sample Name : <?php echo $details->product_name;  ?> / Basil Report Number : <?php echo $details->gc_no ?></h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <input type="hidden" id="sample_registration_id" value="<?php echo $sample_reg_id; ?>">
                            <input type="hidden" id="branch_id" value="<?php echo $branch_id; ?>">
                            <label for="">Select Test Name To Complete Record Finding</label>
                            <select id="tests" class="form-control" readonly>
                                <option disabled="" selected>Select Test</option>
                                <?php if (!empty($sample_selected_test)) {
                                    foreach ($sample_selected_test as $tests) { ?>
                                        <option value="<?php echo $tests['sample_test_id']; ?>" data-rfd_id="<?php echo $tests['record_finding_id']; ?>" <?php if ($record_data['sample_test_id'] == $tests['sample_test_id']) {
                                                                                                                                                                echo "selected";
                                                                                                                                                            } ?>><?php echo $tests['test_name']; ?></option>
                                <?php }
                                } ?>
                            </select>
                        </div>
                    </div>
                </div>

                <form action="javascript:void(0);" enctype="multipart/form-data" name="edit_report_generation" id="edit_report_generation">
                    <div class="row">
                        <div class="col-md-12">
                            <input type="hidden" name="record_finding_id" class="record_finding_id" value="<?php echo $record_data['record_finding_id']; ?>">
                            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                            <div class="row">
                                <input type="hidden" name="sample_reg_id" value="<?php echo base64_decode($this->uri->segment(4)); ?>">
                                <div class="col-md-6">
                                    <label for="">SUB-CONTRACTED TO:</label>
                                    <input class="sub_con_id form_data" type="hidden" value="<?php echo set_value('lab_details_id'); ?>" name="lab_id">
                                    <input class="form-control input-sm sub_lab_name" value="<?php echo $record_data['lab_name']; ?>" autocomplete="off" name="sub_lab_name" type="text" placeholder="Select a Lab ... ">
                                    <ul class="list-group-item sub_lab_list" style="display:none">
                                    </ul>
                                    <?php echo form_error('lab_details_id[]'); ?>
                                </div>

                                <div class="col-md-1">
                                    <a class="lab_details " href="#myModal" data-bs-toggle="modal"> <img style="margin-top: 30px;width:25px;" title="Add Lab Details" src="<?php echo base_url('/assets/images/add.png'); ?>"></a>
                                </div>
                                <div class="col-md-5">
                                    <label for="">Test Result</label>
                                    <select name="test_result" id="" class="form-control form_data" required>
                                        <!-- <option value=" <?php if ($record_data['test_result'] == 'Pass') { ?> <?php echo $record_data['test_result']; ?> <?php } ?>" selected>Pass</option>
                                    <option value=" <?php if ($record_data['test_result'] == 'Fail') { ?> <?php echo $record_data['test_result']; ?> <?php } ?>" selected>Fail</option>
                                    <option value=" <?php if ($record_data['test_result'] == 'Refer Result') { ?> <?php echo $record_data['test_result']; ?> <?php } ?>" selected>Refer Result</option> -->
                                        <option value="<?php echo $record_data['test_result']; ?>"><?php echo $record_data['test_result']; ?></option>
                                        <option value="Pass">Pass</option>
                                        <option value="Fail">Fail</option>
                                        <option value="Refer Result">Refer Result</option>
                                        <option value="NA">NA</option>
                                        <option value="M">M</option>
                                        <option value="F">F</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <!-- updated by millan on 14-07-2021 -->
                        <div class="col-md-12 mt-3">
                            <label for="">TEST DISPLAY NAME:</label>
                            <input type="text" name="test_display_name" class="form-control form_data" value="<?php echo $record_data['test_display_name']; ?>" />
                        </div>
                        <div class="col-md-12 mt-3">
                            <label for="">TEST METHOD:</label>
                            <input type="text" name="test_display_method" class="form-control form_data" value="<?php echo $record_data['test_display_method']; ?>" />
                        </div>
                        <div class="col-md-12 mt-3 mb-3">
                            <label for="">TEST COMPONENT:</label>
                            <textarea cols='52' rows='3' name="test_component" class="form-control form_data"><?php echo $record_data['test_component']; ?></textarea>
                        </div>
                        <!-- updated by millan on 14-07-2021 -->

                        <?php if ($branch_id == 2) { ?>
                            <div class="col-md-12">
                                <label for="">Select test type</label>
                                <select name="test_name_type" id="" class="form-control">
                                    <option value="<?php echo $record_data['test_name_type']; ?>"><?php echo $record_data['test_name_type']; ?></option>
                                    <option value="#"># =The test is not applicable for the sample since there is no Metal present in the footwear.</option>
                                    <option value="##">##= The test is not applicable for the sample since there is no leather sole present in the footwear.</option>
                                    <option value=""> = The tests are under GAC Accreditation</option>
                                    <option value="¥">¥ = Test Subcontracted to ISO 17025 Accredited Lab</option>
                                </select>
                            </div>
                        <?php } ?>
                        <div class="col-md-12">
                            <label for="">Upload Image</label>
                            <input type="file" class="form-control form_data" id="multiple_image" name="multiple_image[]" multiple> <br>
                            <div class="row">
                                <?php foreach ($images as $key => $value) { ?>
                                    <div class="col-sm-4 images_del"> <img width="50%;" src="<?php echo $value['image_path']; ?>" alt="" width="10%;"><a class="delimage" type="text" readonly data-id="<?php echo $value['images_id']; ?>">X</a></div>
                                <?php } ?>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="">Choose Result Entry Option :</label>
                                <input type="radio" id="result_entry" name="result_entry" value="1" required <?php echo ($record_data['result_entry'] == 1)?'checked':'';?>> Import parameters
                                <input type="radio" id="result_entry" name="result_entry" value="2" required <?php echo ($record_data['result_entry'] == 2)?'checked':'';?>> Paste data in CKEditor
                            </div>
                        </div>

                        <div id="parameter_entry" style="display:<?php echo ($record_data['result_entry'] == 1)?'block':'none'; ?>">
                        <!-- Added by CHANDAN --16-05-2022 -->
                        <div class="bg-info mt-3 p-3 text-center w-100">
                            <h4 class="text-white"><b>Please import your test parameter first to feed result into system, parameter feeding is one time job.</b></h4><br />
                            <button type="button" class="btn btn-warning" test_id="<?php echo $test->test_id; ?>" id="import_parameter">IMPORT PARAMETERS</button>
                        </div>
                        <?php if (!empty($parameters_body) || !empty($get_parameters)) { ?>
                            <div class="col-md-12 mt-5">
                                <div class="row">
                                    <div class="col-md-9">
                                        <h4 class="text-dark">PARAMETER DETAILS</h4>
                                    </div>
                                    <div class="col-md-3 text-right">
                                        <button type="button" class="btn btn-sm btn-primary" id="add_result_column">ADD RESULT</button>
                                        <button type="button" class="btn btn-sm btn-danger ml-2" id="del_result_column">DELETE RESULT</button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 mt-2">
                                <table class="table small table-bordered" id="dynamic_test_parameters_table">
                                    <thead>
                                        <tr class="bg-info">
                                            <th>ACTION</th>
                                            <?php
                                            $para_head_id = (isset($parameters_heading->id) && !empty($parameters_heading->id)) ? $parameters_heading->id : '';

                                            $head_branch_id = $branch_id;
                                            $head_sample_test_id = $record_data['sample_test_id'];
                                            $head_sample_reg_id = $sample_reg_id;
                                            $head_test_id = $test->test_id;

                                            $head_clouse = (isset($parameters_heading->clouse) && !empty($parameters_heading->clouse)) ? $parameters_heading->clouse : 'CLAUSE';

                                            $head_parameter_name = (isset($parameters_heading->parameter_name) && !empty($parameters_heading->parameter_name)) ? $parameters_heading->parameter_name : 'PARAMETER NAME';

                                            $head_category = (isset($parameters_heading->category) && !empty($parameters_heading->category)) ? $parameters_heading->category : 'CATEGORY';

                                            $head_limitation = (isset($parameters_heading->limitation) && !empty($parameters_heading->limitation)) ? $parameters_heading->limitation : 'LIMITATION';

                                            $head_requirement = (isset($parameters_heading->requirement) && !empty($parameters_heading->requirement)) ? $parameters_heading->requirement : 'REQUIREMENT';

                                            $head_priority_order = (isset($parameters_heading->priority_order) && !empty($parameters_heading->priority_order)) ? $parameters_heading->priority_order : 'PRIORITY ORDER';

                                            $result_1 = (isset($parameters_heading->result_1) && !empty($parameters_heading->result_1)) ? $parameters_heading->result_1 : 'RESULT';
                                            ?>
                                            <th>
                                                <input type="hidden" name="para_head_id" value="<?php echo $para_head_id; ?>" />
                                                <input type="hidden" name="head_branch_id" value="<?php echo $head_branch_id; ?>" />
                                                <input type="hidden" name="head_sample_test_id" value="<?php echo $head_sample_test_id; ?>" />
                                                <input type="hidden" name="head_sample_reg_id" value="<?php echo $head_sample_reg_id; ?>" />
                                                <input type="hidden" name="head_test_id" value="<?php echo $head_test_id; ?>" />
                                                <input type="hidden" class="form-control form-control-sm" name="head_clouse" value="<?php echo $head_clouse; ?>" required />
                                                CLAUSE
                                            </th>
                                            <th>
                                                <input type="hidden" class="form-control form-control-sm" name="head_parameter_name" value="<?php echo $head_parameter_name; ?>" required readonly />
                                                PARAMETER NAME
                                            </th>
                                            <th>
                                                <input type="text" class="form-control form-control-sm" name="head_category" value="<?php echo $head_category; ?>" required />
                                            </th>
                                            <th>
                                                <input type="text" class="form-control form-control-sm" name="head_limitation" value="<?php echo $head_limitation; ?>" required />
                                            </th>
                                            <th>
                                                <input type="text" class="form-control form-control-sm" name="head_requirement" value="<?php echo $head_requirement; ?>" required />
                                            </th>
                                            <th>
                                                <input type="text" class="form-control form-control-sm" name="head_priority_order" value="<?php echo $head_priority_order; ?>" required />
                                            </th>
                                            <th>
                                                <input type="text" class="form-control form-control-sm total_result_column_counter" required name="head_result_1" value="<?php echo $result_1; ?>" />
                                            </th>
                                            <?php if (isset($parameters_heading->result_2) && !empty($parameters_heading->result_2)) { ?>
                                                <th><input type="text" class="form-control form-control-sm total_result_column_counter" required name="head_result_2" value="<?php echo $parameters_heading->result_2; ?>" /></th>
                                            <?php } ?>
                                            <?php if (isset($parameters_heading->result_3) && !empty($parameters_heading->result_3)) { ?>
                                                <th><input type="text" class="form-control form-control-sm total_result_column_counter" required name="head_result_3" value="<?php echo $parameters_heading->result_3; ?>" /></th>
                                            <?php } ?>
                                            <?php if (isset($parameters_heading->result_4) && !empty($parameters_heading->result_4)) { ?>
                                                <th><input type="text" class="form-control form-control-sm total_result_column_counter" required name="head_result_4" value="<?php echo $parameters_heading->result_4; ?>" /></th>
                                            <?php } ?>
                                            <?php if (isset($parameters_heading->result_5) && !empty($parameters_heading->result_5)) { ?>
                                                <th><input type="text" class="form-control form-control-sm total_result_column_counter" required name="head_result_5" value="<?php echo $parameters_heading->result_5; ?>" /></th>
                                            <?php } ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($parameters_body)) { ?>
                                            <?php foreach ($parameters_body as $key1 => $val1) { ?>
                                                <tr id="delete_parameters_<?php echo $val1->id; ?>">
                                                    <td>
                                                        <button type="button" class="btn btn-default delete_parameters" del_id="<?php echo $val1->id; ?>">
                                                            <img src="<?php echo base_url('/assets/images/drop.png'); ?>">
                                                        </button>
                                                    </td>
                                                    <td>
                                                        <input type="hidden" name="para_body_id[]" value="<?php echo $val1->id; ?>" />
                                                        <input type="hidden" name="test_parameters_id[]" value="<?php echo $val1->test_parameters_id; ?>" />
                                                        <input type="text" class="form-control form-control-sm" name="clouse[]" value="<?php echo $val1->clouse; ?>" />
                                                    </td>
                                                    <td>
                                                        <input type="hidden" class="form-control form-control-sm" name="parameter_name[]" value="<?php echo $val1->parameter_name; ?>" readonly />
                                                        <?php echo $val1->parameter_name; ?>
                                                    </td>
                                                    <td><input type="text" class="form-control form-control-sm" name="category[]" value="<?php echo $val1->category; ?>" /></td>
                                                    <td><input type="text" class="form-control form-control-sm" name="limitation[]" value="<?php echo $val1->limitation; ?>" /></td>
                                                    <td><input type="text" class="form-control form-control-sm" name="requirement[]" value="<?php echo $val1->requirement; ?>" /></td>
                                                    <td><input type="text" class="form-control form-control-sm" name="priority_order[]" value="<?php echo $val1->priority_order; ?>" /></td>
                                                    <td><input type="text" class="form-control form-control-sm" name="result_1[]" value="<?php echo $val1->result_1; ?>" /></td>
                                                    <?php if (isset($parameters_heading->result_2) && !empty($parameters_heading->result_2)) { ?>
                                                        <td><input type="text" class="form-control form-control-sm" name="result_2[]" value="<?php echo $val1->result_2; ?>" /></td>
                                                    <?php } ?>
                                                    <?php if (isset($parameters_heading->result_3) && !empty($parameters_heading->result_3)) { ?>
                                                        <td><input type="text" class="form-control form-control-sm" name="result_3[]" value="<?php echo $val1->result_3; ?>" /></td>
                                                    <?php } ?>
                                                    <?php if (isset($parameters_heading->result_4) && !empty($parameters_heading->result_4)) { ?>
                                                        <td><input type="text" class="form-control form-control-sm" name="result_4[]" value="<?php echo $val1->result_4; ?>" /></td>
                                                    <?php } ?>
                                                    <?php if (isset($parameters_heading->result_5) && !empty($parameters_heading->result_5)) { ?>
                                                        <td><input type="text" class="form-control form-control-sm" name="result_5[]" value="<?php echo $val1->result_5; ?>" /></td>
                                                    <?php } ?>
                                                </tr>
                                            <?php } ?>
                                        <?php } else { ?>
                                            <?php if (!empty($get_parameters)) { ?>
                                                <?php foreach ($get_parameters as $key1 => $val1) { ?>
                                                    <tr id="delete_parameters_<?php echo $val1->test_parameters_id; ?>">
                                                        <td>
                                                            <button type="button" class="btn btn-default delete_parameters" del_id="<?php echo $val1->test_parameters_id; ?>">
                                                                <img src="<?php echo base_url('/assets/images/drop.png'); ?>">
                                                            </button>
                                                        </td>
                                                        <td>
                                                            <input type="hidden" name="para_body_id[]" value="" />
                                                            <input type="hidden" name="test_parameters_id[]" value="<?php echo $val1->test_parameters_id; ?>" />
                                                            <input type="text" class="form-control form-control-sm" name="clouse[]" value="<?php echo $val1->clouse; ?>" />
                                                        </td>
                                                        <td><input type="text" class="form-control form-control-sm" name="parameter_name[]" value="<?php echo $val1->test_parameters_disp_name; ?>" readonly /></td>
                                                        <td><input type="text" class="form-control form-control-sm" name="category[]" value="<?php echo $val1->category; ?>" /></td>
                                                        <td><input type="text" class="form-control form-control-sm" name="limitation[]" value="<?php echo $val1->parameter_limit; ?>" /></td>
                                                        <td><input type="text" class="form-control form-control-sm" name="requirement[]" value="<?php echo $val1->requirement; ?>" /></td>
                                                        <td><input type="text" class="form-control form-control-sm" name="priority_order[]" value="<?php echo $val1->priority_order; ?>" /></td>
                                                        <td><input type="text" class="form-control form-control-sm" name="result_1[]" value="" /></td>
                                                    </tr>
                                                <?php } ?>
                                            <?php } ?>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>

                            <div class="col-md-12 mb-5">
                                <label for="notes">NOTE:</label>
                                <textarea cols='52' rows='3' name="notes" id="notes" class="form-control ckeditor"><?php echo isset($parameters_heading->notes) ? html_entity_decode(base64_decode($parameters_heading->notes)) : ''; ?></textarea>
                            </div>
                        <?php } ?>
                        <!-- End -->
                        </div>

                        <div id="editor_entry" class="col-md-12 m-2"  style="display:<?php echo ($record_data['result_entry'] == 2)?'block':'none'; ?>">
                            <h3 class="text-center text-dark">NABL DETAILS</h3>
                            <label for="">NABL TEST's & PARAMETERS:</label>
                            <textarea cols='52' rows='3' name="nabl_detail" id="nabl_detail" class="form-control ckeditor "><?php echo html_entity_decode(base64_decode($record_data['nabl_detail'])); ?></textarea>
                            <label for="">NABL REMARKS:</label>
                            <textarea cols='52' rows='3' name="nabl_remark" id="nabl_remark" class="form-control ckeditor "><?php echo html_entity_decode(base64_decode($record_data['nabl_remark'])); ?></textarea>
                            <label for=""></label>
                            <!-- <input type="file" table_id="survey" name="dynamic_table_import" class="dynamic_table_import" accept=".csv">
                            <h6 style="margin-bottom:2px" class="text-warning">* Only CSV File Import </h6> -->
                            <p style="margin-top: 10px">
                                <input type="button" value="add column" class="btn btn-success btn-sm addcol" table="nabl" />
                                <input type="button" value="delete column" class="btn btn-success btn-sm deletecol1" table="nabl" />
                                <input type="button" value="Add Row" class="btn btn-success btn-sm addrow" table="nabl">
                                <input type="button" value="Delete Row" class="btn btn-success btn-sm deleterowl" table="nabl">
                            </p>
                            <table id="nabl" border="1" width="100%">
                                <?php if ($record_data['nabl_headings']) { ?>
                                    <?php foreach ($record_data['nabl_headings'] as $key1 => $table_type) { ?>
                                        <?php if ($key1 == 'heading') { ?>
                                            <thead>
                                                <?php foreach ($table_type as $key2 => $row) { ?>
                                                    <?php if (array_key_exists('head1', $row)) { ?>
                                                        <tr>
                                                            <?php foreach ($row as $key3 => $coloum) { ?>
                                                                <?php if ($key3 == 'head1') { ?>
                                                                    <th><input type="checkbox" name="record"></th>
                                                                <?php } ?>
                                                                <th>
                                                                    <input class="form-control" type="text" name="<?php echo $key3; ?>" value="<?php echo $coloum; ?>">
                                                                </th>
                                                            <?php } ?>
                                                        <?php } ?>
                                                        </tr>
                                                    <?php  } ?>
                                            </thead>
                                        <?php } ?>
                                        <?php if ($key1 == 'body') { ?>
                                            <tbody>
                                                <?php foreach ($table_type as $key2 => $row) { ?>
                                                    <tr>
                                                        <?php foreach ($row as $key3 => $coloum) { ?>
                                                            <?php if ($key3 == 'value1') { ?>
                                                                <th><input type="checkbox" name="record"></th>

                                                            <?php } ?>
                                                            <td>
                                                                <input class="form-control" type="text" name="<?php echo $key3; ?>" value="<?php echo $coloum; ?>">
                                                            </td>
                                                        <?php } ?>
                                                    </tr>
                                                <?php  } ?>
                                            </tbody>
                                        <?php  } ?>
                                    <?php }
                                } else {
                                    ?>
                                    <thead>
                                        <tr>
                                            <th><input type="checkbox" name="record"></th>
                                            <th><input type="text" name="head1" value="" class="font-weight-bold form-control" /></th>
                                            <th><input type="text" name="head2" value="" class="font-weight-bold form-control" /></th>
                                            <th><input type="text" name="head3" value="" class="font-weight-bold form-control" /></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><input type="checkbox" name="record"></td>
                                            <td><input type="text" name="value1" class="form-control" /></td>
                                            <td><input type="text" name="value2" class="form-control" /></td>
                                            <td><input type="text" name="value3" class="form-control" /></td>
                                        </tr>
                                    </tbody>
                                <?php } ?>
                            </table>

                            <h3 class="text-center" style="line-height:0.7; margin-top:10px">NON-NABL DETAILS</h3>
                            <label for="">NON-NABL TEST's & PARAMETERS:</label>
                            <textarea cols='52' rows='3' name="non_nabl_detail" id="non_nabl_detail" class="form-control ckeditor "><?php echo html_entity_decode(base64_decode($record_data['non_nabl_detail'])); ?></textarea>
                            <?php echo form_error('type_category_id'); ?>
                            <label for="">NON-NABL REMARKS:</label>
                            <textarea cols='52' rows='3' name="non_nabl_remark" id="non_nabl_remark" class="form-control ckeditor "><?php echo html_entity_decode(base64_decode($record_data['non_nabl_remark'])); ?></textarea>
                            <?php echo form_error('type_category_id'); ?>
                            <p style="margin-top: 10px">
                                <input type="button" value="add column" class="btn btn-success btn-sm addcol" table="non_nabl" />
                                <input type="button" value="delete column" class="btn btn-success btn-sm deletecol1" table="non_nabl" />
                                <input type="button" value="Add Row" class="btn btn-success btn-sm addrow" table="non_nabl">
                                <input type="button" value="Delete Row" class="btn btn-success btn-sm deleterowl" table="non_nabl">
                            </p>
                            <table id="non_nabl" border="1" width="100%">

                                <?php if ($record_data['non_nabl_headings']) { ?>

                                    <?php foreach ($record_data['non_nabl_headings'] as $key1 => $table_type) { ?>
                                        <?php if ($key1 == 'heading') { ?>
                                            <thead>
                                                <?php foreach ($table_type as $key2 => $row) { ?>
                                                    <?php if (array_key_exists('head1', $row)) { ?>
                                                        <tr>
                                                            <?php foreach ($row as $key3 => $coloum) { ?>
                                                                <?php if ($key3 == 'head1') { ?>
                                                                    <th><input type="checkbox" name="record"></th>

                                                                <?php } ?>
                                                                <th>
                                                                    <input class="form-control" type="text" name="<?php echo $key3; ?>" value="<?php echo $coloum; ?>">
                                                                </th>
                                                            <?php } ?>
                                                        </tr>
                                                    <?php  } ?>
                                                <?php  } ?>
                                            </thead>
                                        <?php } ?>
                                        <?php if ($key1 == 'body') { ?>
                                            <tbody>
                                                <?php foreach ($table_type as $key2 => $row) { ?>
                                                    <tr>
                                                        <?php foreach ($row as $key3 => $coloum) { ?>
                                                            <?php if ($key3 == 'value1') { ?>
                                                                <td><input type="checkbox" name="record"></td>
                                                            <?php } ?>
                                                            <td>
                                                                <input class="form-control" type="text" name="<?php echo $key3; ?>" value="<?php echo $coloum; ?>">
                                                            </td>
                                                        <?php } ?>
                                                    </tr>
                                                <?php  } ?>
                                            </tbody>
                                        <?php  } ?>
                                    <?php } ?>
                                <?php } else {
                                ?>
                                    <thead>
                                        <tr>
                                            <th><input type="checkbox" name="record"></th>
                                            <th><input type="text" name="head1" value="" class="font-weight-bold form-control" /></th>
                                            <th><input type="text" name="head2" value="" class="font-weight-bold form-control" /></th>
                                            <th><input type="text" name="head3" value="" class="font-weight-bold form-control" /></th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><input type="checkbox" name="record"></td>
                                            <td><input type="text" name="value1" class="form-control" /></td>
                                            <td><input type="text" name="value2" class="form-control" /></td>
                                            <td><input type="text" name="value3" class="form-control" /></td>
                                        </tr>
                                    <?php } ?>
                                    </tbody>
                            </table>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <label for="">Sequence Number</label>
                                <input type="number" class="sequence_no form-control" name="sequence_no" min="0" oninput="check(this)" value="<?php echo $record_data['sequence_no']; ?>" required>
                            </div>
                        </div>
                        <div class="col-md-12 text-right p-2">
                            <a href="<?php echo base_url('Manage_lab/record_finding') ?>" class="btn btn-danger" type="submit">Back</a>
                            <button type="submit" class="btn btn-success submit" id="edit_btn_submit">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog" style="margin-left: 30%;width:50%!important;">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h3 class="modal-title ">LAB DETAILS</h3>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body form">
                <form action="#" method="post" id="form" enctype="multipart/form-data" class="form-horizontal">
                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                    <div class="form-body">
                        <div class="form-group">
                            <label class="control-label col-md-3">Lab Name</label>
                            <div class="col-md-12">
                                <input name="lab_name" required="required" placeholder="Lab Name" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Lab Address</label>
                            <div class="col-md-12">
                                <textarea name="address" placeholder="Address" class="form-control"></textarea>
                                <span class="help-block"></span>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" id="btnSave" onclick="save()" class="btn btn-primary">Update</button>
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>

<!-- Added by CHANDAN --19-05-2022 -->
<?php $this->load->view('manage_lab/record_finding_import_parameter'); ?>
<!-- End -->

<script>
    function check(input) {

        if (input.value == 0) {
            input.setCustomValidity('The number must not be zero.');
        } else {
            // input is fine -- reset the error message
            input.setCustomValidity('');
        }
    }
</script>
<script>
    // frond end validation 
    const _tokken = $('meta[name="_tokken"]').attr('value');

    $(document).ready(function() {
        $('.delimage').on('click', function() {
            var id = $(this).data('id');
            var self = $(this);
            $.ajax({
                url: "<?php echo base_url('Manage_lab/deleteimage'); ?>",
                type: 'post',
                data: {
                    id: id,
                    _tokken: _tokken
                },
                // console.log(data);
                success: function(result) {
                    // console.log(result);
                    var data = $.parseJSON(result);
                    if (data.status > 0) {
                        $.notify(data.msg, 'success');
                        self.parent('.images_del').remove();
                    } else {
                        $.notify(data.msg, 'error');
                    }
                }

            });
        });

        $("form[name='add_product_form']").validate({
            rules: {
                sample_types_code: "required",
                sample_type_name: "required",
                cat_name: "required",
                retain_period: "required",
                unit_name: "required",
                upload_image: "required",
                status: {
                    required: true
                }
            },
            submitHandler: function(form) {
                form.submit();
            }
        });

        // form submission
        $("#edit_report_generation").on('submit', function(e) {
            e.preventDefault();
            var nbl_table_check = true;
            var non_nabl_table_check = true;
            var nabl_table = {};
            nabl_table['heading'] = $('#nabl thead tr').map(function() {
                var obj = {};
                $(this).find('input[type=text]').each(function() {
                    if (!$(this).val() && this.name == 'head1') {
                        nbl_table_check = false;
                        return false;
                    } else {
                        obj[this.name] = $(this).val();
                    }
                });
                return obj;
            }).get();

            if (nbl_table_check) {
                nabl_table['body'] = $('#nabl tbody tr').map(function() {
                    var obj = {};
                    $(this).find('input[type=text]').each(function() {
                        if (!$(this).val() && this.name == 'value1') {
                            nbl_table_check = false;
                            return false;
                        } else {
                            obj[this.name] = $(this).val();
                        }
                    });
                    return obj;
                }).get();
            }
            var non_nabl_table = {};
            non_nabl_table['heading'] = $('#non_nabl thead tr').map(function() {
                var obj = {};
                $(this).find('input[type=text]').each(function() {
                    if (!$(this).val() && this.name == 'head1') {
                        non_nabl_table_check = false;
                        return false;
                    } else {
                        obj[this.name] = $(this).val();
                    }
                });
                return obj;
            }).get();

            if (non_nabl_table_check) {
                non_nabl_table['body'] = $('#non_nabl tbody tr').map(function() {
                    var obj = {};
                    $(this).find('input[type=text]').each(function() {
                        if (!$(this).val() && this.name == 'value1') {
                            non_nabl_table_check = false;
                            return false;
                        } else {
                            obj[this.name] = $(this).val();
                        }
                    });
                    return obj;
                }).get();
            }
            //             var form_data = $('.form_data').serialize();
            // console.log(form_data);
            var formData = new FormData(this);

            if (nbl_table_check) {
                formData.append('nabl_table', JSON.stringify(nabl_table));
            }

            if (non_nabl_table_check) {
                formData.append('non_nabl_table', JSON.stringify(non_nabl_table));
            }
            var nabl_remarks = CKEDITOR.instances['nabl_remark'].getData();
            var nabl_details = CKEDITOR.instances['nabl_detail'].getData();
            var non_nabl_remarks = CKEDITOR.instances['non_nabl_remark'].getData();
            var non_nabl_details = CKEDITOR.instances['non_nabl_detail'].getData();

            <?php if (!empty($parameters_body) || !empty($get_parameters)) { ?>
                var notes = CKEDITOR.instances['notes'].getData();
                formData.append('notes', notes);
            <?php } ?>

            formData.append('nabl_remark', nabl_remarks);
            formData.append('nabl_detail', nabl_details);
            formData.append('non_nabl_remark', non_nabl_remarks);
            formData.append('non_nabl_detail', non_nabl_details);

            $.ajax({
                url: "<?php echo base_url('Manage_lab/update_record_finding'); ?>",
                contentType: false,
                processData: false,
                type: 'post',
                data: formData,
                beforeSend: function() {
                    $('body').append('<div id="pageloader" class="pageloader"></div>');
                    $('#edit_btn_submit').prop('disabled', true);
                },
                success: function(result) {
                    let data = $.parseJSON(result);
                    if (data.status > 0) {
                        $.notify(data.msg, 'success');
                        location.href = '<?php echo base_url('Manage_lab/record_finding'); ?>';
                    } else {
                        $.notify(data.msg, 'error');
                    }
                    $('#edit_btn_submit').prop('disabled', false);
                    $('#pageloader').removeClass('pageloader');
                }
            });
        });

        // Added by CHANDAN--16-05-2022
        $(document).on('click', '.delete_parameters', function() {
            let para_id = $(this).attr('del_id');
            let cnf = confirm("Are you want to delete Parameter?");
            if (cnf == true) {
                if (para_id.length > 0) {
                    $.ajax({
                        url: '<?php echo base_url("Manage_lab/delete_parameters") ?>',
                        type: 'post',
                        data: {
                            para_id: para_id,
                            _tokken: _tokken
                        },
                        dataType: 'json',
                        success: function(data) {
                            if (data.code == 1) {
                                $('#delete_parameters_' + para_id).remove();
                            }
                            $.notify(data.message, {
                                position: 'top center',
                                className: (data.code == 1) ? 'success' : 'primary'
                            });
                        }
                    });

                } else {
                    $(this).closest("tr").remove();
                    return false;
                }
            }
        });
    });
</script>



<script>
    function save() {
        // ajax adding data to database
        $.ajax({
            url: "<?php echo site_url('Manage_lab/add_lab_details'); ?>",
            type: "POST",
            data: $('#form').serialize(),
            dataType: "JSON",
            success: function(data) {
                if (data.status)
                    console.log(data.status);
                $('#myModal').modal('hide');
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert('Error while adding data');
            }
        });
    }
</script>


<script>
    $(document).ready(function() {
        CKEDITOR.replaceClass = 'ckeditor';
    });
</script>


<script>
    $(document).ready(function() {

        $(".addcol").on('click', function() {
            addColumn($(this).attr('table'));
        });
        $(".deletecol1").on('click', function() {
            deleteColumn($(this).attr('table'));
        });
        $(".addrow").on('click', function() {
            addRow($(this).attr('table'));
        });
        $(".deleterowl").on('click', function() {
            deleteRow($(this).attr('table'));
        });

        function addColumn(tblId) {
            var tblHeadObj = document.getElementById(tblId).tHead;
            for (var h = 0; h < tblHeadObj.rows.length; h++) {
                var newTH = document.createElement('th');
                tblHeadObj.rows[h].appendChild(newTH);
                newTH.innerHTML = '<input type="text" class="form-control font-weight-bold" name="head' + (tblHeadObj.rows[h].cells.length - 1) + '">';
            }

            var tblBodyObj = document.getElementById(tblId).tBodies[0];
            for (var i = 0; i < tblBodyObj.rows.length; i++) {
                var newCell = tblBodyObj.rows[i].insertCell(-1);
                newCell.innerHTML = '<input type="text" class="form-control" name="value' + (tblBodyObj.rows[i].cells.length - 1) + '">';
            }
        }

        function deleteColumn(tblId) {
            var allRows = document.getElementById(tblId).rows;
            for (var i = 0; i < allRows.length; i++) {
                if (allRows[i].cells.length > 1) {
                    allRows[i].deleteCell(-1);
                }
            }
        }

        function addRow(tableID) {
            var table = document.getElementById(tableID);
            var rowCount = table.rows.length;
            var row = table.insertRow(rowCount);
            var colCount = table.rows[0].cells.length;
            for (var i = 0; i < colCount; i++) {
                var newcell = row.insertCell(i);
                newcell.innerHTML = table.rows[1].cells[i].innerHTML;
                switch (newcell.childNodes[0].type) {
                    case "text":
                        newcell.childNodes[0].value = " ";
                        break;
                    case "checkbox":
                        newcell.childNodes[0].checked = false;
                        break;
                    case "select-one":
                        newcell.childNodes[0].selectedIndex = 0;
                        break;
                }
            }
        }

        function deleteRow(tableID) {
            try {
                var table = document.getElementById(tableID);
                var rowCount = table.rows.length;
                for (var i = 1; i < rowCount; i++) {
                    var row = table.rows[i];
                    var chkbox = row.cells[0].childNodes[0];
                    if (true == chkbox.checked) {
                        if (rowCount <= 2) {
                            alert("Cannot delete all the rows.");
                            break;
                        }
                        table.deleteRow(i);
                        rowCount--;
                        i--;
                    }
                }
            } catch (e) {
                alert(e);
            }
        }
    });
</script>
<script>
    $(document).on('change','#result_entry',function(){
        var result_entry = $("input[name='result_entry']:checked").val();
        if(result_entry == 1){
            $('#parameter_entry').css('display','block');
            $('#editor_entry').css('display','none');
        } else {
            $('#editor_entry').css('display','block');
            $('#parameter_entry').css('display','none');
        }
    });
</script>