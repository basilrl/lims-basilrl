<?php
if (empty($data)) {
    $action = base_url() . 'Job_Controller/savedata';
    $job_status = 1;
    $depart_id = '';
    $job_discription = '';
    $job_title = '';
    $job_posted = '';
    $created_by = '';
} else {
    $dta = (array)$data[0];
    $action = base_url() . 'Job_Controller/edit_job/' . $dta['id'];
    $depart_id = $dta['depart_id'];
    $job_discription = $dta['job_discription'];
    $job_title = $dta['job_title'];
    $job_posted = $dta['job_posted'];
    $job_status = $dta['job_status'];
    $created_by = $dta['created_by'];
}
?>

<script src="<?php echo base_url('ckeditor/ckeditor.js'); ?>"></script>
<script>
    $(document).ready(function() {
        CKEDITOR.replace('job_dis');
    });
</script>

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Jobs Discription</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>">Home</a></li>
                        <li class="breadcrumb-item"><a href="<?php echo base_url('Services_Controller'); ?>">job</a></li>
                        <li class="breadcrumb-item active">Add Jobs</li>
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
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title"> Job</h3>
                        </div>
                        <!-- /.card-header -->

                        <form action="<?php echo $action; ?>" method="post" autocomplete="off" id="services_form">
                            <div class="card-body">
                                <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Job Title:</label>
                                            <input type="text" name="job_title" id="job_title" class="form-control" value="<?php echo $job_title; ?>" placeholder="Job Title">
                                            <?php echo form_error('job_title', '<div class="text-danger">', '</div>'); ?>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Job Posted Date :</label>
                                            <input type="text" name="job_posted" id="job_posted" class="form-control" placeholder="Job Posted Date" value="<?php if(!empty($job_posted)){echo date("Y-m-d",strtotime($job_posted)); } else { echo date('Y-m-d');}?>" >
                                            <?php echo form_error('job_posted', '<div class="text-danger">', '</div>'); ?>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Job Status :</label>
                                            <select name="job_status" id="job_status" class="form-control" style="width: 100%;">
                                                <option disabled selected>Select Status</option>
                                                <option value="1" <?php echo !empty($job_status && $job_status == '1') ? "selected" : "";  ?> > Active </option>
                                                <option value="0" <?php echo !empty($job_status && $job_status == '0') ? "selected" : "";  ?> > InActive </option>
                                            </select>
                                            <?php echo form_error('job_status', '<div class="text-danger">', '</div>'); ?>

                                        </div>
                                    </div>
                        
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Department Name:</label>
                                            <select name="job_depart" id="job_depart" class="form-control" style="width: 100%;">
                                                <option disabled selected>Select Department</option>
                                                <?php if (!empty($dept_name)) { foreach ($dept_name as $dept) { ?>
                                                    <option value="<?php echo $dept['id']; ?>" <?php if ($depart_id == $dept['id']) { echo 'selected'; } ?>><?php echo $dept['name']; ?></option>
                                                <?php }
                                                } ?>
                                            </select>
                                            <?php echo form_error('job_depart', '<div class="text-danger">', '</div>'); ?>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Job Description:</label>
                                            <textarea name="job_dis" id="job_dis" class="form-control" placeholder="Job Description" style="width:14px; hieght:20px;"> <?php echo html_entity_decode($job_discription); ?>  </textarea>
                                            <?php echo form_error('job_dis', '<div class="text-danger">', '</div>'); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /.card-body -->
                            <div class="card-footer">
                                <div style="text-align:right">
                                    <a href="<?php echo base_url('Job_Controller/index'); ?>"><button type="button" class="btn btn-danger">Cancel</button></a>
                                    <button type="submit" class="btn btn-primary" id="submit" value="Submit">Submit </button>
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
<script>
    var dateToday = new Date();
    $(function() {
      $("#job_posted").datepicker({
        minDate: dateToday,
        dateFormat: 'yy-mm-dd'
      });
    });
  </script>