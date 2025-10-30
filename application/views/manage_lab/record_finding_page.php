<script src="<?php echo base_url(); ?>ckeditor/ckeditor.js"></script>
<script src="<?php echo base_url(); ?>assets/dist/js/test_management.js"></script>
<!-- Added by Saurabh to import excel on 04-10-2021 -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.7.7/xlsx.core.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xls/0.7.4-a/xls.core.min.js"></script>
<!-- Added by Saurabh to csv import on 04-10-2021  -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-csv/0.71/jquery.csv-0.71.min.js"></script>

<div class="content-wrapper">
    <div class="container-fluid pl-5 pr-5">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h4 class="text-primary"><b>ADD RECORD FINDING</b></h4>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-right">
                    <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>">Home</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo base_url('Manage_lab/record_finding'); ?>">Record Finding List</a></li>
                </ol>
            </div>
        </div>

        <div class="card">
            <div class="card-header bg-info text-white">
                <h3 class="card-title">Sample Name : <?php echo $details->product_name;  ?> / Basil Report Number : <?php echo $details->gc_no ?></h3>
            </div>
            <div class="card-body bg-light">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="">Select Test Name To Complete Record Finding</label>
                            <select id="tests" class="form-control">
                                <option disabled="" selected>Select Test</option>
                                <?php if (!empty($sample_selected_test)) {
                                    foreach ($sample_selected_test as $tests) { ?>
                                        <option value="<?php echo $tests['sample_test_id']; ?>" <?php if ($test->test_id == $tests['test_id']) {
                                                                                                    echo "selected";
                                                                                                } ?>><?php echo $tests['test_name']; ?></option>
                                <?php }
                                } ?>
                            </select>
                        </div>
                    </div>
                </div>

                <form action="javascript:void(0);" method="post" enctype="multipart/form-data" name="report_generations" id="report_generation">
                    <div class="row">
                        <div class="col-md-12">
                            <input type="hidden" name="sample_registration_id" id="sample_registration_id" value="<?php echo $sample_reg_id; ?>">
                            <input type="hidden" name="sample_test_id" id="sample_test_id" value="<?php echo $sample_test_id; ?>">
                            <input type="hidden" name="branch_id" id="branch_id" value="<?php echo $branch_id; ?>">
                            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="">SUB-CONTRACTED TO:</label>
                                    <input class="sub_con_id form_data" type="hidden" value="<?php echo set_value('lab_details_id') ?>" name="lab_id">
                                    <input class="form-control input-sm sub_lab_name" value="<?php echo set_value('sub_lab_name') ?>" autocomplete="off" name="sub_lab_name" type="text" placeholder="Select a Lab ... ">
                                    <ul class="list-group-item sub_lab_list" style="display:none"></ul>

                                    <?php echo form_error('lab_details_id[]'); ?>
                                </div>
                                <div class="col-md-1">
                                    <a class="lab_details" href="#myModal" data-bs-toggle="modal"> <img style="margin-top: 30px;width:25px;" title="Add Lab Details" src="<?php echo base_url('/assets/images/add.png'); ?>"></a>
                                </div>
                                <div class="col-md-5">
                                    <label for="">Test Result</label>
                                    <select name="test_result" id="" class="form-control form_data" required>
                                        <option value="">Select and option</option>
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
                        <div class="col-md-12 mt-3">
                            <label for="">TEST DISPLAY NAME:</label>
                            <input type="text" name="test_display_name" class="form-control form_data" value="<?php echo $test->test_name; ?>" />
                        </div>
                        <div class="col-md-12 mt-3">
                            <label for="">TEST METHOD NAME:</label>
                            <input type="text" name="test_display_method" class="form-control form_data" value="<?php echo $test->test_method; ?>" />
                        </div>
                        <div class="col-md-12 mt-3 mb-3">
                            <label for="">TEST COMPONENT:</label>
                            <textarea cols='52' rows='3' name="test_component" class="form-control form_data"><?php echo $part->parts; ?></textarea>
                        </div>
                        <?php if ($branch_id == 2) { ?>
                            <div class="col-md-12">
                                <label for="">Select test type</label>
                                <select name="test_name_type" id="" class="form-control">
                                    <option value="">Select</option>
                                    <option value="#"># =The test is not applicable for the sample since there is no Metal present in the footwear.</option>
                                    <option value="##">##= The test is not applicable for the sample since there is no leather sole present in the footwear.</option>
                                    <option value=""> = The tests are under GAC Accreditation</option>
                                    <option value="¥">¥ = Test Subcontracted to ISO 17025 Accredited Lab</option>
                                </select>
                            </div>
                        <?php } ?>
                        <div class="col-md-12">
                            <label for="">Upload Image</label>
                            <input type="file" class="form-control form_data" id="multiple_image" name="multiple_image[]" multiple>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="">Choose Result Entry Option :</label>
                                <input type="radio" id="result_entry" checked name="result_entry" value="1" required> Import parameters
                                <input type="radio" id="result_entry" name="result_entry" value="2" required> Paste data in CKEditor
                            </div>
                        </div>

                        <div id="parameter_entry">
                            <!-- Added by CHANDAN --16-05-2022 -->
                            <div class="bg-info mt-3 p-3 text-center w-100">
                                <h4 class="text-white"><b>Please import your test parameter first to feed result into system, parameter feeding is one time job.</b></h4><br />
                                <button type="button" class="btn btn-warning" test_id="<?php echo $test->test_id; ?>" id="import_parameter">IMPORT PARAMETERS</button>
                            </div>
                            <?php if (!empty($get_parameters)) { ?>
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
                                                <th>
                                                    <input type="hidden" name="head_sample_test_id" value="<?php echo $sample_test_id; ?>" />
                                                    <input type="hidden" name="head_sample_reg_id" value="<?php echo $sample_reg_id; ?>" />
                                                    <input type="hidden" name="head_test_id" value="<?php echo $test->test_id; ?>" />
                                                    <input type="hidden" class="form-control form-control-sm" name="head_clouse" value="CLAUSE" required />
                                                    CLAUSE
                                                </th>
                                                <th>
                                                    <input type="hidden" class="form-control form-control-sm" name="head_parameter_name" value="PARAMETER NAME" required />
                                                    PARAMETER NAME
                                                </th>
                                                <th>
                                                    <input type="text" class="form-control form-control-sm" name="head_category" value="CATEGORY" required />
                                                </th>
                                                <th>
                                                    <input type="text" class="form-control form-control-sm" name="head_limitation" value="LIMITATION" required />
                                                </th>
                                                <th>
                                                    <input type="text" class="form-control form-control-sm" name="head_requirement" value="REQUIREMENT" required />
                                                </th>
                                                <th>
                                                    <input type="text" class="form-control form-control-sm" name="head_priority_order" value="PRIORITY ORDER" required />
                                                </th>
                                                <th>
                                                    <input type="text" class="form-control form-control-sm total_result_column_counter" name="head_result_1" value="RESULT" required />
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($get_parameters as $key1 => $val1) { ?>
                                                <tr id="delete_parameters_<?php echo $val1->test_parameters_id; ?>">
                                                    <td>
                                                        <button type="button" class="btn btn-default delete_parameters" del_id="<?php echo $val1->test_parameters_id; ?>">
                                                            <img src="<?php echo base_url('/assets/images/drop.png'); ?>">
                                                        </button>
                                                    </td>
                                                    <td>
                                                        <input type="hidden" name="test_parameters_id[]" value="<?php echo $val1->test_parameters_id; ?>" />
                                                        <input type="text" class="form-control form-control-sm" name="clouse[]" value="<?php echo $val1->clouse; ?>" />
                                                    </td>
                                                    <td>
                                                        <input type="hidden" class="form-control form-control-sm" name="parameter_name[]" value="<?php echo $val1->test_parameters_disp_name; ?>" readonly />
                                                        <?php echo $val1->test_parameters_disp_name; ?>
                                                    </td>
                                                    <td><input type="text" class="form-control form-control-sm" name="category[]" value="<?php echo $val1->category; ?>" /></td>
                                                    <td><input type="text" class="form-control form-control-sm" name="limitation[]" value="<?php echo $val1->parameter_limit; ?>" /></td>
                                                    <td><input type="text" class="form-control form-control-sm" name="requirement[]" value="<?php echo $val1->requirement; ?>" /></td>
                                                    <td><input type="text" class="form-control form-control-sm" name="priority_order[]" value="<?php echo $val1->priority_order; ?>" /></td>
                                                    <td><input type="text" class="form-control form-control-sm" name="result_1[]" value="" /></td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="col-md-12 mb-5">
                                    <label for="notes">NOTE:</label>
                                    <textarea cols='52' rows='3' name="notes" id="notes" class="form-control ckeditor"></textarea>
                                </div>
                            <?php } ?>
                            <!-- End -->
                        </div>

                        <div id="editor_entry" style="display: none;" class="col-md-12 m-2">
                            <h3 class="text-center text-dark">NABL DETAILS</h3>
                            <label for="">NABL TEST's & PARAMETERS:</label>
                            <textarea cols='52' rows='3' name="nabl_detail" id="nabl_details" class="form-control ckeditor "></textarea>
                            <label for="">NABL REMARKS:</label>
                            <textarea cols='52' rows='3' name="nabl_remark" id="nabl_remark" class="form-control ckeditor "></textarea>
                            <label for=""></label>
                            <!-- Added by Saurabh on 07-01-2021 -->
                            <div class="mt-3">
                                <label for="">Import data using excel file</label>
                                <input type="file" table_id="survey" name="dynamic_table_import" class="" id="nabl_excel_import">
                            </div>
                            <div class="mt-3">
                                <label for="">Import using csv file</label>
                                <input type="file" id="nabl_csv_upload">
                            </div>
                            <!-- Added by Saurabh on 07-01-2021 -->
                            <!-- <input type="file" table_id="survey" name="dynamic_table_import" class="dynamic_table_import" accept=".csv">
                            <h6 style="margin-bottom:2px" class="text-warning">* Only CSV File Import </h6> -->
                            <p style="margin-top: 10px">
                                <input type="button" value="add column" class="btn btn-success btn-sm addcol" table="nabl" />
                                <input type="button" value="delete column" class="btn btn-success btn-sm deletecol1" table="nabl" />
                                <input type="button" value="Add Row" class="btn btn-success btn-sm addrow" table="nabl">
                                <input type="button" value="Delete Row" class="btn btn-success btn-sm deleterowl" table="nabl">

                            </p>
                            <table id="nabl" border="1" width="100%">
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
                            </table>
                            <h3 class="text-center" style="line-height:0.7; margin-top:10px;">NON-NABL DETAILS</h3>
                            <label for="">NON-NABL TEST's & PARAMETERS:</label>
                            <textarea cols='52' rows='3' name="non_nabl_detail" id="non_nabl_details" class="form-control ckeditor "></textarea>
                            <?php echo form_error('type_category_id'); ?>
                            <label for="">NON-NABL REMARKS:</label>
                            <textarea cols='52' rows='3' name="non_nabl_remark" id="non_nabl_remark" class="form-control ckeditor "></textarea>
                            <?php echo form_error('type_category_id'); ?>
                            <!-- Added By Saurabh on 07-01-2021 -->
                            <div class="mt-3">
                                <label for="">Import data using excel file</label>
                                <input type="file" table_id="survey" name="dynamic_table_import" class="dynamic_table_import_excel" id="non_nabl_excel_import">
                            </div>
                            <div class="mt-3">
                                <label for="">Import using csv file</label>
                                <input type="file" id="non_nabl_csv_upload">
                            </div>
                            <!-- Added By Saurabh on 07-01-2021 -->
                            <!-- <input type="file" table_id="survey" name="dynamic_table_import" class="dynamic_table_import" accept=".csv">
                            <h6 style="margin-bottom:2px" class="text-warning">* Only CSV File Import </h6> -->
                            <p style="margin-top: 10px">
                                <input type="button" value="add column" class="btn btn-success btn-sm addcol" table="non_nabl" />
                                <input type="button" value="delete column" class="btn btn-success btn-sm deletecol1" table="non_nabl" />
                                <input type="button" value="Add Row" class="btn btn-success btn-sm addrow" table="non_nabl">
                                <input type="button" value="Delete Row" class="btn btn-success btn-sm deleterowl" table="non_nabl">
                            </p>
                            <table id="non_nabl" border="1" width="100%">
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
                            </table>
                        </div>
                        
                        <div class="col-sm-12">
                            <label for="">Test Sequence Number</label>
                            <input type="number" class="sequence_no form-control" name="sequence_no" min="1" value="" oninput="check(this)" required>
                        </div>

                        <div class="col-md-12 text-right p-2">
                            <a href="<?php echo base_url('Manage_lab/record_finding') ?>" class="btn btn-danger" type="submit">Back</a>
                            <button type="submit" class="btn btn-success submit" id="add_btn_submit">Submit</button>
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
                <button type="button" id="btnSave" onclick="save()" class="btn btn-primary">Save</button>
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
        $(document).on('submit', "#report_generation", function(e) {
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
            var formData = new FormData(this);
            if (nbl_table_check) {
                formData.append('nabl_table', JSON.stringify(nabl_table));
            }

            if (non_nabl_table_check) {
                formData.append('non_nabl_table', JSON.stringify(non_nabl_table));
            }

            $.ajax({
                url: "<?php echo base_url('Manage_lab/report_generation'); ?>",
                contentType: false,
                processData: false,
                type: 'post',
                data: formData,
                beforeSend: function() {
                    $('body').append('<div id="pageloader" class="pageloader"></div>');
                    $('#add_btn_submit').prop('disabled', true);
                },
                success: function(result) {
                    let data = $.parseJSON(result);
                    if (data.status > 0) {
                        $.notify(data.msg, 'success');
                        location.href = '<?php echo base_url('Manage_lab/record_finding'); ?>';
                    } else {
                        $.notify(data.msg, 'error');
                    }
                    $('#add_btn_submit').prop('disabled', false);
                    $('#pageloader').removeClass('pageloader');
                }
            });
        });

        // Added by CHANDAN--16-05-2022
        $(document).on('click', '.delete_parameters', function() {
            let id = $(this).attr('del_id');
            if (id.length > 0) {
                let cnf = confirm("Are you want to delete Parameter?");
                if (cnf == true) {
                    $('#delete_parameters_' + id).remove();
                }
            }
        });
        // End...
    });
</script>

<script>
    function save() {
        url = "<?php echo site_url('Manage_lab/add_lab_details'); ?>";
        // ajax adding data to database
        $.ajax({
            url: url,
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
                        newcell.childNodes[0].value = "";
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


        $('.dynamic_table_import_excel').change(function() {
            //Reference the non_nabl_excel_import element.
            var non_nabl_excel_import = $("#non_nabl_excel_import")[0];
            var regex = /^([a-zA-Z0-9\s_\\.\-:])+(.xlsx|.xls)$/;
            if (regex.test($("#non_nabl_excel_import").val().toLowerCase())) {
                var xlsxflag = false; /*Flag for checking whether excel is .xls format or .xlsx format*/
                if ($("#non_nabl_excel_import").val().toLowerCase().indexOf(".xlsx") > 0) {
                    xlsxflag = true;
                }
                /*Checks whether the browser supports HTML5*/
                if (typeof(FileReader) != "undefined") {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        var data = e.target.result;
                        /*Converts the excel data in to object*/
                        if (xlsxflag) {
                            var workbook = XLSX.read(data, {
                                type: 'binary'
                            });
                        } else {
                            var workbook = XLS.read(data, {
                                type: 'binary'
                            });
                        }
                        /*Gets all the sheetnames of excel in to a variable*/
                        var sheet_name_list = workbook.SheetNames;

                        sheet_name_list.forEach(function(y) {
                            /*Iterate through all sheets*/
                            /*Convert the cell value to Json*/
                            if (xlsxflag) {
                                var exceljson = XLSX.utils.sheet_to_json(workbook.Sheets[y]);
                            } else {
                                var exceljson = XLS.utils.sheet_to_row_object_array(workbook.Sheets[y]);
                            }
                            if (exceljson.length > 0) {
                                BindTable(exceljson, '#non_nabl');
                            }
                        });
                    }
                    if (xlsxflag) {
                        /*If excel file is .xlsx extension than creates a Array Buffer from excel*/
                        reader.readAsArrayBuffer($("#non_nabl_excel_import")[0].files[0]);
                    } else {
                        reader.readAsBinaryString($("#non_nabl_excel_import")[0].files[0]);
                    }
                } else {
                    alert("Sorry! Your browser does not support HTML5!");
                }
            } else {
                alert("Please upload a valid Excel file!");
            }
        });

        $('#nabl_excel_import').change(function() {
            //Reference the non_nabl_excel_import element.
            var non_nabl_excel_import = $("#nabl_excel_import")[0];
            var regex = /^([a-zA-Z0-9\s_\\.\-:])+(.xlsx|.xls)$/;
            if (regex.test($("#nabl_excel_import").val().toLowerCase())) {
                var xlsxflag = false; /*Flag for checking whether excel is .xls format or .xlsx format*/
                if ($("#nabl_excel_import").val().toLowerCase().indexOf(".xlsx") > 0) {
                    xlsxflag = true;
                }
                /*Checks whether the browser supports HTML5*/
                if (typeof(FileReader) != "undefined") {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        var data = e.target.result;
                        /*Converts the excel data in to object*/
                        if (xlsxflag) {
                            var workbook = XLSX.read(data, {
                                type: 'binary'
                            });
                        } else {
                            var workbook = XLS.read(data, {
                                type: 'binary'
                            });
                        }
                        /*Gets all the sheetnames of excel in to a variable*/
                        var sheet_name_list = workbook.SheetNames;

                        sheet_name_list.forEach(function(y) {
                            /*Iterate through all sheets*/
                            /*Convert the cell value to Json*/
                            if (xlsxflag) {
                                var exceljson = XLSX.utils.sheet_to_json(workbook.Sheets[y]);
                            } else {
                                var exceljson = XLS.utils.sheet_to_row_object_array(workbook.Sheets[y]);
                            }
                            if (exceljson.length > 0) {
                                BindTable(exceljson, '#nabl');
                            }
                        });
                    }
                    if (xlsxflag) {
                        /*If excel file is .xlsx extension than creates a Array Buffer from excel*/
                        reader.readAsArrayBuffer($("#nabl_excel_import")[0].files[0]);
                    } else {
                        reader.readAsBinaryString($("#nabl_excel_import")[0].files[0]);
                    }
                } else {
                    alert("Sorry! Your browser does not support HTML5!");
                }
            } else {
                alert("Please upload a valid Excel file!");
            }
        });

        function BindTable(jsondata, tableId) {
            /*Function used to convert the JSON array to Html Table*/
            var columns = BindTableHeader(jsondata, tableId); /*Gets all the column headings of Excel*/
            var row$ = '<tbody>';
            for (var i = 0; i < jsondata.length; i++) {
                row$ += '<tr>';
                row$ += '<td><input type="checkbox" name="record"></td>';
                var j = 1;
                for (var colIndex = 0; colIndex < columns.length; colIndex++) {
                    var cellValue = jsondata[i][columns[colIndex]];
                    if (cellValue == null)
                        cellValue = "";
                    row$ += '<td><input type="text" name="value' + j + '" class="form-control" value="' + cellValue + '"></td>';
                    j++;
                }
                row$ += '</tr>';
            }
            row$ += '</tbody>';
            $(tableId).append(row$);
        }

        function BindTableHeader(jsondata, tableId) {
            /*Function used to get all column names from JSON and bind the html table header*/
            var columnSet = [];
            var headerTr$ = '<thead><tr>';
            headerTr$ += '<th><input type="checkbox" name="record"></th>';
            for (var i = 0; i < jsondata.length; i++) {
                var rowHash = jsondata[i];
                var j = 1;
                for (var key in rowHash) {
                    if (rowHash.hasOwnProperty(key)) {
                        if ($.inArray(key, columnSet) == -1) {
                            /*Adding each unique column names to a variable array*/
                            columnSet.push(key);
                            headerTr$ += '<th><input type="text" name="head' + j + '" class="font-weight-bold form-control" value="' + key + '"></th>';
                        }
                    }
                    j++;
                }
            }
            headerTr$ += '</tr></thead>';
            $(tableId).html(headerTr$);
            return columnSet;
        }
    });
</script>


<script type="text/javascript">
    $(function() {
        $("#non_nabl_csv_upload").change(function() {
            var regex = /^([a-zA-Z0-9\s_\\.\-:])+(.csv|.txt)$/;
            if (regex.test($("#non_nabl_csv_upload").val().toLowerCase())) {
                if (typeof(FileReader) != "undefined") {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        var csvData = event.target.result;
                        var rows = $.csv.toArrays(csvData);
                        var table = $("#non_nabl");
                        var header = rows[0];
                        var table = '<thead><tr>';
                        table += '<th><input type="checkbox" name="record"></th>';
                        var j = 1;
                        $.each(header, function(i, v) {
                            table += '<th><input type="text" name="head' + j + '" value="' + v + '" class="font-weight-bold form-control"></th>';
                            j++;
                        });
                        table += '</tr></thead>';
                        table += '<tbody>';
                        for (var i = 1; i < rows.length; i++) {
                            var j = 1;
                            table += '<tr>';
                            table += '<td><input type="checkbox" name="record"></td>';
                            $.each(rows[i], function(i, v) {
                                table += '<td><input type="text" name="value' + j + '" class="form-control" value="' + v + '"></td>';
                                j++;
                            });
                            table += '</tr>';
                        }
                        table += '</tbody>';
                        $('#non_nabl').html(table);
                    }
                    reader.readAsText($("#non_nabl_csv_upload")[0].files[0]);
                } else {
                    alert("This browser does not support HTML5.");
                }
            } else {
                alert("Please upload a valid CSV file.");
            }
        });

        $("#nabl_csv_upload").change(function() {
            var regex = /^([a-zA-Z0-9\s_\\.\-:])+(.csv|.txt)$/;
            if (regex.test($("#nabl_csv_upload").val().toLowerCase())) {
                if (typeof(FileReader) != "undefined") {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        var csvData = event.target.result;
                        var rows = $.csv.toArrays(csvData);
                        var table = $("#nabl");
                        var header = rows[0];
                        var table = '<thead><tr>';
                        table += '<th><input type="checkbox" name="record"></th>';
                        var j = 1;
                        $.each(header, function(i, v) {
                            table += '<th><input type="text" name="head' + j + '" value="' + v + '" class="font-weight-bold form-control"></th>';
                            j++;
                        });
                        table += '</tr></thead>';
                        table += '<tbody>';
                        for (var i = 1; i < rows.length; i++) {
                            var j = 1;
                            table += '<tr>';
                            table += '<td><input type="checkbox" name="record"></td>';
                            $.each(rows[i], function(i, v) {
                                table += '<td><input type="text" name="value' + j + '" class="form-control" value="' + v + '"></td>';
                                j++;
                            });
                            table += '</tr>';
                        }
                        table += '</tbody>';
                        $('#nabl').html(table);
                    }
                    reader.readAsText($("#nabl_csv_upload")[0].files[0]);
                } else {
                    alert("This browser does not support HTML5.");
                }
            } else {
                alert("Please upload a valid CSV file.");
            }
        });

        // Added by Saurabh on 05-10-2021
        $('#tests').change(function() {
            let sample_test_id = $(this).val();
            let sample_reg_id = $('#sample_registration_id').val();
            let branch_id = $('#branch_id').val();
            if (sample_test_id.length > 0 && sample_reg_id.length > 0 && branch_id.length > 0) {
                window.location.replace('<?php echo base_url('Manage_lab/open_record_finding/') ?>' + btoa(sample_reg_id) + '/' + btoa(sample_test_id) + '/' + btoa(branch_id));
            } else {
                alert('Test-ID | Sample-Reg-ID | Branch-ID is mandatory!');
            }
        });
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