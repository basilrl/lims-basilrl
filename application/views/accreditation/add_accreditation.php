<?php
if (empty($accreditation)) {
    $title = set_value('title');
    $country_id = set_value('country_id');
    $branch_id = set_value('branch_id');
    $validity = set_value('validity');
    $acc_standard = set_value('acc_standard');
    $certificate_no = set_value('certificate_no');
    $upload_filename = set_value('upload_filename');
    $scope_filename = set_value('scope_filename');
} else {

    $title = $accreditation->title;
    $country_id = $accreditation->country_id;
    $branch_id = $accreditation->branch_id;
    $validity = $accreditation->validity;
    $acc_standard = $accreditation->acc_standard;
    $certificate_no = $accreditation->certificate_no;
    $upload_filename = $accreditation->upload_filename;
    $scope_filename = $accreditation->scope_filename;
}
?>

<!-- <script src="<?php echo base_url('assets/js/accreditation.js'); ?>"></script>  -->
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Accreditation</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>">Home</a></li>
                        <li class="breadcrumb-item"><a href="<?php echo base_url('Accreditation/index'); ?>"> Accreditation</a></li>
                        <li class="breadcrumb-item active"> Accreditation</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>


    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <!-- left column  -->
                <div class="col-md-12">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Accreditation</h3>
                        </div>
                        <!-- /.card-header  -->
                        <form action="" enctype="multipart/form-data" method="post" autocomplete="off">
                            <div class="card-body">
                                <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                                <div class="row">
                                    <input type="hidden" id="case" value="add">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Title :</label>
                                            <input type="text" name="title" placeholder="Enter title" class="form-control form-control-sm" style="width: 100%;" value="<?php echo $title ?>">
                                            <?php echo form_error('title', '<div class="text-danger">', '</div>'); ?>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Country :</label>
                                            <select name="country_id" id="country_name" class="form-control form-control-sm">
                                                <option value="">Select Country</option>
                                                <?php foreach ($country_name as $value) { ?>
                                                    <option value="<?php echo $value->country_id; ?>" <?php echo ($country_id === $value->country_id) ? 'selected' : ''; ?>>
                                                        <?php echo $value->country_name; ?>
                                                    </option>
                                                <?php } ?>

                                            </select>
                                            <?php echo form_error('country_id', '<div class="text-danger">', '</div>'); ?>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Branch Name:</label>
                                            <select name="branch_id" id="branch_name" class="form-control form-control-sm">
                                                <option value="">Select Branch</option>
                                                <?php foreach ($branch_name as $name) { ?>
                                                    <option value="<?php echo $name->branch_id; ?>" <?php echo ($branch_id == $name->branch_id) ? "selected" : ""; ?>> <?php echo $name->branch_name; ?> </option>
                                                <?php } ?>
                                            </select>
                                            <?php echo form_error('branch_id', '<div class="text-danger">', '</div>'); ?>
                                        </div>
                                    </div>



                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Validity:</label>
                                            <input type="date" name="validity" class="form-control form-control-sm" style="width: 100%;" value="<?php echo $validity ?>">
                                            <?php echo form_error('validity', '<div class="text-danger">', '</div>'); ?>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Accreditation standard :</label>
                                            <input type="text" name="acc_standard" class="form-control form-control-sm" style="width: 100%;" value="<?php echo $acc_standard ?>">
                                            <?php echo form_error('acc_standard', '<div class="text-danger">', '</div>'); ?>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Certificate Number :</label>
                                            <input type="text" name="certificate_no" class="form-control form-control-sm" style="width: 100%;" value="<?php echo $certificate_no ?>">
                                            <?php echo form_error('certificate_no', '<div class="text-danger">', '</div>'); ?>
                                        </div>
                                    </div>


                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="">UPLOAD FILE(doc|docx|xls|xlsx|pdf)</label>
                                            <input type="file" class="form-control form-control-sm" name="upload_filename" autocomplete="off">
                                            <?php echo form_error('upload_filename', '<div class="text-danger">', '</div>'); ?>
                                        </div>
                                    </div>



                                    <div class="col-sm-6">
                                        <div class="" form-group>
                                            <label for="">Scope File(doc|docx|xls|xlsx|pdf)</label>
                                            <input type="file" class="form-control form-control-sm" name="scope_filename" autocomplete="off">
                                            <?php echo form_error('scope_filename', '<div class="text-danger">', '</div>'); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /.card-body  -->
                            <div class="card-footer">
                                <div style="margin-left: 10px;">
                                    <a href="<?php echo base_url('Accreditation/index'); ?>"><button type="button" class="btn btn-danger">Cancel</button></a>
                                    <button type="submit" name="submit" class="btn btn-primary">Save</button>
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