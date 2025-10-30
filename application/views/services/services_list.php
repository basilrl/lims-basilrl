  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Services</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Services</li>
            </ol>
          </div>
        </div>

        <div class="row">
          <div class="col-md-12">
            <!-- <form action="<?php echo base_url('Services_Controller/index');?>" method="post" autocomplete="off"> -->
            <div class="row">
            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
              
              <div class="col-md-3">
                <div class="form-group">
                  <select name="" id="lab_location" class="form-control form-control-sm select-box">
                    <option disabled="" selected>Select Lab Location</option>
                    <?php if(!empty($lab_location)){ foreach($lab_location as $lab){?>
                    <option value="<?php echo $lab['id']; ?>"><?php echo $lab['name']; ?></option>
                    <?php } }?>
                  </select>
                </div>
              </div>

              <div class="col-md-3">
                <div class="form-group">
                  <select name="" id="product_destination" class="form-control form-control-sm select-box">
                    <option disabled="" selected>Select Product Destination</option>
                    <?php if(!empty($product_destination)){ foreach($product_destination as $pro_des){?>
                    <option value="<?php echo $pro_des['id']; ?>"><?php echo $pro_des['name']; ?></option>
                    <?php } }?>
                  </select>
                </div>
              </div>

              <div class="col-md-3">
                <div class="form-group">
                  <select name="" id="test_standard" class="form-control form-control-sm select-box">
                    <option disabled="" selected>Select Test Standard</option>
                    <?php if(!empty($test_standard)){ foreach($test_standard as $ts){?>
                    <option value="<?php echo $ts['id']; ?>"><?php echo $ts['name']; ?></option>
                    <?php } }?>
                  </select>
                </div>
              </div>

              <div class="col-md-3">
                <div class="form-group">
                  <select name="" id="certificate" class="form-control form-control-sm select-box">
                    <option disabled="" selected>Select Certificate</option>
                    <?php if(!empty($certificate)){ foreach($certificate as $cer){?>
                    <option value="<?php echo $cer['id']; ?>"><?php echo $cer['name']; ?></option>
                    <?php } }?>
                  </select>
                </div>
              </div>
              
              
                <div class="col-md-3">
                  <div class="form-group">
                    <button type="button" class="btn btn-primary" id="search">Search</button>
                    <button type="button" class="btn btn-danger" onclick="location.href='<?php echo base_url('Services_Controller/index'); ?>'">Clear</button>
                  </div>
                </div>
            </div>
            <!-- </form> -->
          </div>
          
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <!-- /.row -->
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Services List</h3>
                <div class="card-tools">
                  <div class="input-group input-group-sm">
                    <!-- <input type="text" name="table_search" class="form-control float-right" placeholder="Search">

                    <div class="input-group-append">
                      <button type="submit" class="btn btn-default"><i class="fas fa-search"></i></button>
                    </div> -->
                    <?php if (exist_val('Services_Controller/add_services', $this->session->userdata('permission'))) { ?>
                    <a href="<?php echo base_url('Services_Controller/add_services'); ?>" class="btn btn-primary" style="float: right;">Add</a>
                    <?php } ?>
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
                      <th>SL No.</th>
                      <th>Lab Location</th>
                      <th>Product Destination</th>
                      <th>Test Standard</th>
                      <th>Certification</th>
                      <th>Status</th>
                      <th>Created By</th>
                      <th>Created On</th>
                      <?php if (exist_val('Services_Controller/edit_services', $this->session->userdata('permission'))) { ?>
                      <th>Action</th>
                      <?php } ?>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if(!empty($services)) {$sno = 0; foreach($services as $value){ ?>
                    <tr>
                        <td><?php echo $sno += 1;?></td>
                        <td><?php echo $value['lab_location']?></td>
                        <td><?php echo $value['product_destination']?></td>
                        <td><?php echo $value['test_standard_name']?></td>
                        <td><?php echo $value['certificate_name'];?></td>
                        <td><?php echo $value['status'];?></td>
                        <td><?php echo $value['created_by']?$value['created_by']:'N/A';?></td>
                        <td><?php echo $value['created_on']?$value['created_on']:'N/A';?></td>
                        <td>
                        <?php if (exist_val('Services_Controller/edit_services', $this->session->userdata('permission'))) { ?>
                        <a href="<?php echo base_url('Services_Controller/edit_services/'.$value['services_id']); ?>" class="btn btn-sm btn-default"><img src="<?php echo base_url('assets/images/icon/edit.png'); ?>" class="edit" alt="Edit"></a>
                        <?php } ?>
                        <a href="javascript:void(0)" data-id="<?php echo $value['services_id']; ?>" class="log_view" data-bs-toggle='modal' data-bs-target='#exampleModal' class="btn btn-sm" title="Log View"><img src="<?php echo base_url('assets/images/log-view.png'); ?>" alt="Log view" width="20px"></a>
                        </td>
                    </tr>
                    <?php } } else {?>
                    <tr><td colspan="9">No record found!</td></tr>
                    <?php } ?>
                  </tbody>
                </table>

              </div>

              <!-- Pagination -->
              <div class="card-header">
                <span id="pagination"><?php //echo ($pagination)?$pagination:'';?></span>
                <span id="records"><?php //echo ($result_count)?$result_count:'';?></span>
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
  <!-- /.content-wrapper -->
  <!-- Modal to show log -->
  <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Services log</h5>
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
            <tbody id="services_log"></tbody>
          </table>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

  <!-- added by saurabh on 23-03-2021 -->
  <script>
    $(document).ready(function() {
      const url = $('body').data('url');
      const _tokken = $('meta[name="_tokken"]').attr('value');
      // Ajax call to get log
      $('.log_view').click(function() {
        $('#services_log').empty();
        var services_id = $(this).data('id');
        $.ajax({
          type: 'post',
          url: url + 'Services_Controller/get_services_log',
          data: {
            _tokken: _tokken,
            services_id: services_id
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
            $('#services_log').append(value);
          }
        });
      });
      // ajax call to get log ends here
    });
  </script>
  <!-- added by saurabh on 23-03-2021 -->

  <!-- Added by saurabh to filter data -->
  <script>
  $(document).ready(function(){
    const url = $('body').data('url');

    $(document).on('click', '#search', function() {
      filter(0);
    });

    $('#pagination').on('click', 'a', function(e) {
      e.preventDefault();
      var page = $(this).attr('data-ci-pagination-page');
      filter(page);
    });

    function filter(page) {
      var lab_location = $('#lab_location').val();
      var product_destination = $('#product_destination').val();
      var test_standard = $('#test_standard').val();
      var certificate = $('#certificate').val();
    
      if(lab_location == null){
        lab_location = 'NULL';
      } else {
        lab_location = btoa(lab_location);
      }

      if(product_destination == null){
        product_destination = 'NULL';
      } else {
        product_destination = btoa(product_destination);
      }

      if(test_standard == null){
        test_standard = 'NULL';
      } else {
        test_standard = btoa(test_standard);
      }

      if(certificate == null){
        certificate = 'NULL';
      } else {
        certificate = btoa(certificate);
      }

      window.location.replace(url + 'Services_Controller/index/' + page + '/' + lab_location + '/' + product_destination + '/' + test_standard + '/' + certificate);
    }

    $(document).on('click', '#reset', function() {
      $('#lab_location').val('');
      $('#product_destination').val('');
      $('#test_standard').val('');
      $('#certificate').val('');
      filter(0)
    });
  });
  
  </script>
  <!-- Added by saurabh to filter data -->