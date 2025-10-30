  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Country</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Country</li>
            </ol>
          </div>
        </div>

        <div class="row">
          <div class="col-md-12">
            <form action="<?php echo base_url('Country/index');?>" method="post" autocomplete="off">
            <div class="row">
            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
              <div class="col-md-3">
                <div class="form-group">
                  <input type="text" placeholder="Search By Name" class="form-control" name="country_name" value="<?php echo ($this->input->post('country_name'))?$this->input->post('country_name'):'';?>">
                </div>
              </div>
              <div class="col-md-3" style="display:none">
                <div class="form-group">
                  <input type="text" placeholder="Created By" class="form-control" id="created_by">
                </div>
              </div>
              
                <div class="col-md-3">
                  <div class="form-group">
                    <button type="submit" class="btn btn-primary">Search</button>
                    <button type="button" class="btn btn-danger" onclick="location.href='<?php echo base_url('Country/index'); ?>'">Clear</button>
                  </div>
                </div>
            </div>
            </form>
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
                <h3 class="card-title">Country List</h3>
                <div class="card-tools">
                  <div class="input-group input-group-sm">
                    <!-- <input type="text" name="table_search" class="form-control float-right" placeholder="Search">

                    <div class="input-group-append">
                      <button type="submit" class="btn btn-default"><i class="fas fa-search"></i></button>
                    </div> -->
                    <a href="<?php echo base_url('Country/add_country'); ?>" class="btn btn-primary" style="float: right;">Add</a>
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
                      <th>Country Name</th>
                      <th>Country Code</th>
                      <th>Status</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if(!empty($country)) { $page = $this->uri->segment(3); $sno = (($page?$page:'1')-1) * 10; foreach($country as $value){ ?>
                    <tr>
                        <td><?=$sno += 1;?></td>
                        <td><?=$value['country_name']?></td>
                        <td><?=$value['country_code']?></td>
                        <td><?=($value['status'] == 0)?'Inactive':'Active';?></td>
                        <td>
                        <a href="<?php echo base_url('Country/edit_country/'.base64_encode($value['country_id'])); ?>" class="btn btn-default btn-sm"><img src="<?php echo base_url('assets/images/icon/edit.png'); ?>" class="edit" alt="Edit"></a>
                        <a href="javascript:void(0)" data-id="<?php echo $value['country_id'];?>" class="log_view" data-bs-toggle='modal' data-bs-target='#exampleModal' class="btn btn-sm" title="Log View"><img src="<?php echo base_url('assets/images/log-view.png'); ?>" alt="Log view" width="20px"></a>
                        </td>
                    </tr>
                    <?php } }?>
                  </tbody>
                </table>

              </div>

              <!-- Pagination -->
              <div class="card-header">
                <span id="pagination"><?=$pagination;?></span>
                <span id="records"><?=$result_count;?></span>
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
          <h5 class="modal-title" id="exampleModalLabel">Country log</h5>
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
            <tbody id="country_log"></tbody>
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
        $('#country_log').empty();
        var country_id = $(this).data('id');
        $.ajax({
          type: 'post',
          url: url + 'country/get_country_log',
          data: {
            _tokken: _tokken,
            country_id: country_id
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
            $('#country_log').append(value);
          }
        });
      });
      // ajax call to get log ends here
    });
  </script>
  <!-- added by saurabh on 23-03-2021 -->