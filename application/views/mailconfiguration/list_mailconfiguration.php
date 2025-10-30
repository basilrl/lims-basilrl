<?php
 if (empty($this->uri->segment(4))) {
            $i = 1;
        } else {
            $i = $this->uri->segment(4) + 1;
        }
  ?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Mail Configuration</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>">Home</a></li>
              <li class="breadcrumb-item active">Mail Configuration</li>
            </ol>
          </div>
        </div>

        <div class="row">
          <div class="col-md-12">
            <div class="row">

             
              <div class="col-md-3">
                <div class="form-group">
                  <input type="text" placeholder="Search by customer email" class="form-control" id="search" value="<?php echo $search; ?>">
                </div>
              </div>


              <div class="col-md-3">
                <div class="form-group">
                  <button type="button" class="btn btn-primary" id="data_filter" onclick="filters()">Search</button>
                  <a href="<?php echo base_url('mail_configuration/index'); ?>" class="btn btn-danger btn-sm">Clear</a>
                </div>
              </div>
            </div>
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
                <h3 class="card-title"><a href="<?php echo base_url('mail_configuration/add_mailconfiguration'); ?>" class="btn btn-primary" style="float: right;">Add</a></h3>
                <div class="card-tools">
                  <div class="input-group input-group-sm">
                    
                  </div>
                </div>
              </div>
              <!-- /.card-header -->
              <input type="hidden" id="order" value="">
              <input type="hidden" id="column" value="">
              <div class="card-body small p-0">
                <table class="table table-hover content-list">
                  <thead>
                    <tr>
                      <th>SL No.</th>
                      <th>Lab Location</th>
                      <th>Product Destination</th>
                      <th>Customer Email</th>
                      <th>Status</th>
                      <th>Created On</th>
                      <th>ACTION</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if (!empty($mail_configuration)) {
                      foreach ($mail_configuration as $key => $value) {
                        $delid = $value['mail_conf_id'];
                       ?>
                        <tr>
                          <td><?php echo $i++; ?></td>
                          <td><?php echo $value['lab_location']; ?></td>
                          <td><?php echo $value['product_destination']; ?></td>
                          <td><?php echo $value['c_email']; ?></td>
                          <td><?=($value['status'] == 0)?'Inactive':'Active';?></td>
                          <td><?php echo $value['admin_fname'] ?></td>

                          <td>
                            
                              <a href="<?php echo base_url('mail_configuration/edit_mailconfiguration/' . $value['mail_conf_id']); ?>" title="Edit Mail Configuration" ><img src="<?php echo base_url('assets/images/icon/edit.png'); ?>" alt="Edit" class="icon" style="width: 20px; height: 20px"></a>
                              

                              <!-- <a href='<?php echo base_url("mail_configuration/delete_mailconfiguration/?id=$delid"); ?>' title="Delete"><img src="<?php echo base_url('assets/images/del.png'); ?>" alt="Delete" class="icon"></a> -->
                           
                          </td>
                        </tr>
                      <?php }
                    } else { ?>
                      <tr>
                        <td colspan="10">No record found</td>
                      </tr>
                    <?php } ?>
                  </tbody>
                </table>

              </div>

              <!-- Pagination -->
              <div class="card-header">
                <span id="pagination"><?php echo $links; ?></span>
                <span id="records"><?php echo $result_count; ?></span>
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
  
  <script>
    
    function filters(){
        var url = '<?php echo base_url('mail_configuration/index');?>';
        var search = $('#search').val();
          if (search !== '') {
            url = url + '/' + encodeURI(search);
        } else {
            url = url + '/NULL';
        }
        window.location.href = url;
    }
  </script>