  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Divisions</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Division</li>
            </ol>
          </div>
        </div>

        <div class="row">
          <div class="col-md-12">
            <form action="<?php echo base_url('Division/index');?>" method="post" autocomplete="off">
            <div class="row">
            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
              <div class="col-md-3">
                <div class="form-group">
                  <input type="text" placeholder="Search By Name" class="form-control" name="division_name" value="<?php echo ($this->input->post('division_name'))?$this->input->post('division_name'):'';?>">
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
                    <button type="button" class="btn btn-danger" onclick="location.href='<?php echo base_url('Division/index'); ?>'">Clear</button>
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
                <h3 class="card-title">Division List</h3>
                <div class="card-tools">
                  <div class="input-group input-group-sm">
                    <!-- <input type="text" name="table_search" class="form-control float-right" placeholder="Search">

                    <div class="input-group-append">
                      <button type="submit" class="btn btn-default"><i class="fas fa-search"></i></button>
                    </div> -->
                    <a href="<?php echo base_url('Division/add_division'); ?>" class="btn btn-primary" style="float: right;">Add</a>
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
                      <th>Division Name</th>
                      <th>Division Code</th>
                      <th>Status</th>
                      <th>Created By</th>
                      <th>Created On</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if(!empty($division)) { $page = $this->uri->segment(3); $sno = (($page?$page:'1')-1) * 10; foreach($division as $value){ ?>
                    <tr>
                        <td><?=$sno += 1;?></td>
                        <td><?=$value['division_name']?></td>
                        <td><?=$value['division_code']?></td>
                        <td><?=($value['status'] == 0)?'Inactive':'Active';?></td>
                        <td><?=$value['created_by']?$value['created_by']:'N/A';?></td>
                        <td><?=$value['created_on']?$value['created_on']:'N/A';?></td>
                        <td><a href="<?php echo base_url('Division/edit_division/'.base64_encode($value['division_id'])); ?>" class="btn btn-sm btn-default"><img src="<?php echo base_url('assets/images/icon/edit.png'); ?>" class="edit" alt="Edit"></a></td>
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