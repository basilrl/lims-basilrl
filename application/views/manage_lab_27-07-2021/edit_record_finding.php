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

    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>EDIT RECORD FINDING</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?php echo base_url();; ?>">Home</a></li>
                        <li class="breadcrumb-item"><a href="<?php echo base_url('record_finding') ?>">Edit Record Finding List</a></li>

                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <section>





        <div class="container-fluid" style="width: 90%;">


            <form action="javascript:void(0);" enctype="multipart/form-data" name="edit_report_generation" id="edit_report_generation">
                <div class="row">
                    <div class="col-md-12">
                        <input type="hidden" name="record_finding_id" class="record_finding_id" value="<?php echo $record_data['record_finding_id']; ?>">


                        <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                        <div class="row">
                            <input type="hidden" name="sample_reg_id" value="<?php echo base64_decode($this->uri->segment(4));?>">
                            <div class="col-md-6">
                                <label for="">SUB-CONTRACTED TO:</label>

                                <input class="sub_con_id form_data" type="hidden" value="<?php echo set_value('lab_details_id') ?>" name="lab_id">
                                <input class="form-control  input-sm sub_lab_name" value="<?php echo $record_data['lab_name']; ?>" autocomplete="off" name="sub_lab_name" type="text" placeholder="Select a Lab ... ">
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
                    <div class="col-md-12">
                        <label for="">TEST DISPLAY NAME:</label>
                        <textarea cols='52' rows='3' name="test_display_name" class="form-control form_data"><?php echo $record_data['test_display_name']; ?></textarea>

                    </div>
                    <div class="col-md-12">
                        <label for="">TEST DISPLAY NAME:</label>
                        <textarea cols='52' rows='3' name="test_display_method" class="form-control form_data"><?php echo $record_data['test_display_method']; ?></textarea>

                    </div>
                    <div class="col-md-12">
                        <label for="">TEST COMPONENT:</label>
                        <textarea cols='52' rows='3' name="test_component" class="form-control form_data"><?php echo $record_data['test_component']; ?></textarea>


                    </div>
                    <?php if($branch_id == 2){?>
                    <div class="col-md-12">
                    <label for="">Select test type</label>
                    <select name="test_name_type" id="" class="form-control">
                    <option value="<?php echo $record_data['test_name_type']; ?>"><?php echo $record_data['test_name_type']; ?></option>
                    <option value="#"># =The test is not applicable for the sample since there is no Metal present in the footwear.</option>
                    <option value="##">##= The test is not applicable for the sample since there is no leather sole present in the footwear.</option>
                    <option value=""> = The tests are under GAC Accreditation</option>
                    <option value="¥">¥  = Test Subcontracted to ISO 17025 Accredited Lab</option>
                    </select>
                    </div>
                    <?php }?>
                    <div class="col-md-12">
                        <label for="">Upload Image</label>
                        <input type="file" class="form-control form_data" id="multiple_image" name="multiple_image[]" multiple> <br>
                        <div class="row">
                            <?php foreach ($images as $key => $value) { ?>
                                <div class="col-sm-4 images_del"> <img width="50%;" src="<?php echo $value['image_path']; ?>" alt="" width="10%;"><a class="delimage" type="text" readonly data-id="<?php echo $value['images_id']; ?>">X</a></div>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="col-md-12 m-2" style="border: 1px solid lightgrey;padding:10px;">
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
                    </div>
                    <div class="col-md-12 m-2" style="border: 1px solid lightgrey;padding:10px;">
                        <h3 class="text-center" style="line-height:0.7;">NON-NABL DETAILS</h3>
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
                                <?php //echo'<pre>';print_r($record_data['non_nabl_headings']);exit; 
                                ?>
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
                        <button type="submit" class="btn btn-success submit" type="submit">Submit</button>
                    </div>
            </form>

        </div>
    </section>
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
</div><!-- /.modal -->
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
                // console.log(data);
                success: function(result) {
                    // console.log(result);
                    var data = $.parseJSON(result);
                    if (data.status > 0) {
                        $.notify(data.msg, 'success');
                        location.href = '<?php echo base_url('Manage_lab/record_finding'); ?>';
                    } else {
                        $.notify(data.msg, 'error');
                    }
                }

            });
        });
    })
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