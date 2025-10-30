<?php
$dept_sel_id = ($this->uri->segment('3') != NULL) ? base64_decode($this->uri->segment('3')) : '';
$status = ($this->uri->segment('4') != NULL) ? base64_decode($this->uri->segment('4')) : '';
$job_titles = ($this->uri->segment('5') != 'NULL') ? base64_decode($this->uri->segment('5')) : '';
?>


<div class="row">
    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">

    <div class="col-md-3">
      <div class="form-group"> 
        <label>Department name:</label>
        <select name="job_diss" id="job_diss" class="form-control-sm select-box">
          <option disabled="" selected>Department name</option>
          <?php if (!empty($dept_name)) { foreach ($dept_name as $dept) { ?>
            <option value="<?php echo $dept['id']; ?>" <?php if ($dept_sel_id == $dept['id']) { echo 'selected'; } ?>><?php echo $dept['name']; ?></option>
          <?php }} ?>
        </select>
      </div>
    </div>

    <div class="col-md-3">
      <div class="form-group">
        <label>Select status:</label>
        <select name="job_statuss" id="job_statuss" class="form-control form-control-sm select-box">
          <option disabled="" selected>Select status </option>
          <option value="1" <?php if($status == '1'){ echo "selected";}?>>Active</option>
          <option value="0" <?php if($status == '0'){ echo "selected";}?>>Inactive</option>

        </select>
      </div>
    </div>

    <div class="col-md-3">
      <div class="form-group">
        <label>Job Title:</label>
        <input type="text" name="job_titles" id="job_titles" class="form-control form-control-sm" value="<?php echo $job_titles; ?>" placeholder="job_title" style="height:38px">
      </div>
    </div>

    <div class="col-md-3 mt-4">
      <button type="button" class="btn btn-primary" id="search">Search</button>
      <button type="button" class="btn btn-danger" onclick="location.href='<?php echo base_url('Job_Controller/index'); ?>'">Clear</button>
    </div>
    <!-- </form> -->
</div>
<section class="content">
  <div class="container-fluid">
    <!-- /.row -->
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Job Discription List</h3>
            <div class="card-tools">
              <div class="input-group input-group-sm">
           
                <a href="<?php echo base_url('Job_Controller/load_add_form'); ?>" class="btn btn-primary" style="float: right;">Add</a>
                <?php
                // } 
                ?>
              </div>
            </div>
          </div>
          <!-- /.card-header -->
          <input type="hidden" id="order" value="">
          <input type="hidden" id="column" value="">
          <div class="card-body table-responsive p-0">
            <table class="table table-hover text-nowrap table-sm">
              <thead>
                <tr>
                  <th>S No.</th>
                   <th><a href="<?php echo base_url('Job_Controller/index').'/'.$depart_id.'/'.$job_status.'/'.$job_title.'/depart_id/'.$order;?>">Department Name</a></th>

                  <th><a href="<?php echo base_url('Job_Controller/index').'/'.$depart_id.'/'.$job_status.'/'.$job_title.'/job_discription/'.$order;?>">Job Description</a></th>

                  <th><a href="<?php echo base_url('Job_Controller/index').'/'.$depart_id.'/'.$job_status.'/'.$job_title.'/job_title/'.$order;?>">Job Title</a></th>

                  <th><a href="<?php echo base_url('Job_Controller/index').'/'.$depart_id.'/'.$job_status.'/'.$job_title.'/job_posted/'.$order;?>">Job Posted Date</a></th>
                  <th><a href="<?php echo base_url('Job_Controller/index').'/'.$depart_id.'/'.$job_status.'/'.$job_title.'/job_status/'.$order;?>">Job Status</a></th>

                  <th><a href="<?php echo base_url('Job_Controller/index').'/'.$depart_id.'/'.$job_status.'/'.$job_title.'/created_by/'.$order;?>">Created By</a></th>

                 
                  <th>Action</th>
                  <?php
                  //} 
                  ?>
                </tr>
              </thead>

              <tbody>
                <?php if (!empty($services)) {
                  $page = $this->uri->segment('8');
                  $sno = (($page ? $page : '1') - 1) * 10;
                  foreach ($services as $value) { ?>
                    <tr>
                      <td><?php echo $sno += 1; ?></td>
                      <td><?php echo !empty($value->dept_name) ? $value->dept_name : ""; ?></td>
                      <td><?php echo !empty($value->job_discription) ? html_entity_decode($value->job_discription) : ""; ?></td>

                      <td><?php echo !empty($value->job_title) ? $value->job_title : ""; ?></td>
                      <td><?php echo !empty($value->job_posted) ? date("d/m/Y h:i:s",strtotime($value->job_posted)) : ""; ?></td>
                     
                      <td>
                        <?php if (($value->job_status == 1 && !empty($value->job_status))) { ?>
                          <button class="btn btn-success user_status" uid="<?php echo $value->id; ?>" ustatus="<?php echo $value->job_status ?>">Active</button>
                        <?php } else { ?>
                          <button class="btn btn-danger user_status" uid="<?php echo $value->id; ?>" ustatus="<?php echo $value->job_status ?>">Inactive</button>
                        <?php } ?>

                      </td>
                      <td><?php echo !empty($value->created_by) ? $value->created_by : ''; ?></td>

                      <td>
                        <?php  
                        {
                        ?>
                          <a href="<?php echo base_url('Job_Controller/edit_job/' . $value->id); ?>" class="btn btn-sm"><img src="<?php echo base_url('assets/images/icon/edit.png'); ?>" class="edit" alt="Edit"></a>
                        <?php } ?>
                      </td>
                    </tr>
                  <?php } $sno++;
                } else { ?>
                  <tr>
                    <td colspan="9">No record found!</td>
                  </tr>
                <?php } ?>
              </tbody>
            </table>

          </div>

          <!-- Pagination -->
          <div class="card-header">
             <span id="pagination"><?php echo ($links) ? $links : ''; ?></span>
                <span id="records"><?php echo ($result_count) ? $result_count : ''; ?></span> 
          </div>
          <!-- /.card-body -->
        </div>
        <!-- /.card -->
      </div>
    </div>
    <!-- /.row -->
  </div><!-- /.container-fluid -->
</section>
<div class="modal modal-danger fade" id="modal_popup">
  <div class="modal-dialog modal-sm">
    <form action="<?php echo base_url() . 'Job_Controller/user_status_changed' ?>" method="post" id="test">
      <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
      <div class="modal-content modal-sm" style="margin:0 auto">
        <div class="modal-header">
          <h6>Are you sure, want to change job status?</h6>
          <input type="hidden" name="id" id="user_id" value="">
          <input type="hidden" name="status" id="user_status" value="">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger pull-left" data-bs-dismiss="modal">No</button>
          <button type="submit" name="submit" class="btn btn-success">Yes</button>
        </div>
      </div>
    </form>
  </div>
</div>

<script type="text/javascript">
  $(document).on('click', '.user_status', function() {

    var id = $(this).attr('uid'); //get attribute value in variable
    var status = $(this).attr('ustatus'); //get attribute value in variable

    $('#user_id').val(id); //pass attribute value in ID
    $('#user_status').val(status); //pass attribute value in ID

    $('#modal_popup').modal({
      backdrop: 'static',
      keyboard: true,
      show: true
    }); //show modal popup

  });
</script>
<script>
  $(function() {
    $("#posted_date_to").datepicker();
    $("#posted_date_from").datepicker();
  });
</script>

<script>
  $(document).ready(function() {
    const url = $('body').data('url');

    $(document).on('click', '#search', function() {
      filter();
    });

    // $('#pagination').on('click', 'a', function(e) {
    //   e.preventDefault();
    //   var page = $(this).attr('data-ci-pagination-page');
    //   filter(page);
    // });

    function filter() {
      var job_dis = $('#job_diss').val(); //department name

      var job_status = $('#job_statuss').val();;
      var job_title = $('#job_titles').val();


      // alert(posted_date_from);


      if (job_dis == null) {
        job_dis = 'NULL';
      } else {
        job_dis = btoa(job_dis);
      }

      if (job_status == null) {
        job_status = 'NULL';
      } else {
        job_status = btoa(job_status);
      }


      if (job_title == '') {
        job_title = 'NULL';
      } else {
        job_title = btoa(job_title);
      }

      window.location.replace(url + 'Job_Controller/index/' + job_dis + '/' + job_status + '/' + job_title);
    }


    $(document).on('click', '#reset', function() {
      $('#job_dis').val('');
      $('#job_status').val('');
      $('#job_title').val('');
      filter(0)
    });
  });
</script>
<script>
  CKEDITER.replace('job_dis') 
</script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css">
<link rel="stylesheet" href="/resources/demos/style.css">
<script src="https://code.jquery.com/jquery-3.6.0.js"></script>
<script src="https://code.jquery.com/ui/1.13.1/jquery-ui.js"></script>
<script src="//cdn.ckeditor.com/4.17.1/standard/ckeditor.js"></script>