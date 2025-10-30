  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Sample Received List</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Sample Received</li>
            </ol>
          </div>
        </div>

        <div class="row">
        <div class="col-md-12">
          <form action="">
          <div class="row">
            <div class="col-md-3">
              <div class="form-group">
                <input type="text" placeholder="TRF Reference Number" class="form-control" id="trf_reference_number">
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
              <select name="" class="select-box" class="form-control" id="customer_name">
                  <option selected value="">Select Customer</option>
                  <?php if(!empty($customer)){ foreach($customer as $value){ ?>
                    <option value="<?php echo $value['customer_id']; ?>"><?php echo $value['customer_name'] ?></option>
                  <?php } } ?>
                </select>
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <select name="" class="select-box" class="form-control" id="product">
                  <option selected value="">Select Product</option>
                  <?php if(!empty($products)){ foreach($products as $value){ ?>
                    <option value="<?php echo $value['sample_type_id']; ?>"><?php echo $value['sample_type_name'] ?></option>
                  <?php } } ?>
                </select>
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <input type="text" placeholder="Created On" class="form-control" id="created_on">
              </div>
            </div>
            <div class="col-md-3" style="display:none">
              <div class="form-group">
                <input type="text" placeholder="Created By" class="form-control" id="created_by">
              </div>
            </div>
            <div class="col-md-3" style="display:none">
              <div class="form-group">
              <select class="form-control select-box form-control-sm" name="trf_service_type" id="service_type">
                <option selected="" disabled="">Select Service Type</option>
                <option value="Regular">Regular(3 working days)</option>
                <option value="Express">Express(2 working days)</option>
                <option value="Express3">Express(3 working days)</option>
                <option value="Urgent">Urgent(1 working days)</option>
                <option value="2">Regular 2 days</option>
                <option value="4">Regular 4 days</option>
                <option value="5">Regular 5 days</option>
                <option value="6">Regular 6 days</option>
                <option value="7">Regular 7 days</option>
                <option value="8">Regular 8 days</option>
                <option value="9">Regular 9 days</option>
                <option value="10">Regular 10 days</option>
                <option value="12">Regular 12 days</option>
                <option value="15">Regular 15 days</option>
                <option value="20">Regular 20 days</option>
                <option value="30">Regular 30 days</option>
              </select>
              </div>
            </div>
              <div class="col-md-3">
                <div class="form-group">
                  <button type="button" id="search-trf" class="btn btn-primary">Search</button>
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
                <h3 class="card-title">Sample Received</h3>
                <div class="card-tools">
                  <div class="input-group input-group-sm">
                    <!-- <input type="text" name="table_search" class="form-control float-right" placeholder="Search">

                    <div class="input-group-append">
                      <button type="submit" class="btn btn-default"><i class="fas fa-search"></i></button>
                    </div> -->
                    <!-- <a href="<?php echo base_url('add-open-trf'); ?>" class="btn btn-primary" style="float: right;">Add</a> -->
                  </div>
                </div>
              </div>
              <!-- /.card-header -->
              <input type="hidden" id="order" value="">
              <input type="hidden" id="column" value="">
              <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap" id="sample-received-list">
                  <thead>
                    <tr>
                      <th class="sorting" data-one="trf_id" style="cursor:pointer">SL No.</th>
                      <th>TRF Service Type</th>
                      <th class="sorting" data-one="sample_type_name" style="cursor:pointer">Product</th>
                      <th class="sorting" data-one="trf_sample_ref_id" style="cursor:pointer">Sample Reference Id</th>
                      <th class="sorting" data-one="trf_ref_no" style="cursor:pointer">TRF Reference No.</th>
                      <th class="sorting" data-one="trf_regitration_type" style="cursor:pointer">TRF Type</th>
                      <th class="sorting" data-one="customer_name" style="cursor:pointer">Client</th>
                      <th class="sorting" data-one="contact_name" style="cursor:pointer">Contact</th>
                      <th class="sorting" data-one="trf_reg.create_on" style="cursor:pointer">Created On</th>
                      <th class="sorting" data-one="admin_fname" style="cursor:pointer">Created By</th>
                      <th class="sorting" data-one="trf_status" style="cursor:pointer">TRF Status</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody id="sample-received" >
                  
                  
                  </tbody>
                </table>

              </div>

              <!-- Pagination -->
              <div class="card-header">
                    <span id="sample-received-pagination"></span>
                    <span id="sample-received-records"></span>
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