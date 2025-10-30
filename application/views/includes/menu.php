<?php
if ($this->session->userdata('user_data') !== FALSE) {
    $checkUser = $this->session->userdata('user_data');
//    echo '<pre>';
//    print_r($checkUser);die;
}
?> 
<!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="index3.html" class="nav-link">Home</a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="#" class="nav-link">Contact</a>
      </li>
    </ul>

    <!-- SEARCH FORM -->
    <form class="form-inline ml-3">
      <div class="input-group input-group-sm">
        <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
        <div class="input-group-append">
          <button class="btn btn-navbar" type="submit">
            <i class="fas fa-search"></i>
          </button>
        </div>
      </div>
    </form>
    
    <!--Barcode Scanner-->
    <ul class="navbar-nav">
      <li class="nav-item">
          <a data-bs-toggle="modal" data-bs-target="#view_barcode_modal" onclick="clear_barcode_div()" class="nav-link font-weight-bold" href="#" role="button">Barcode Scan</a>
      </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <!-- Messages Dropdown Menu -->
      <li class="nav-item dropdown">
        <a class="nav-link" data-bs-toggle="dropdown" href="#">
          <i class="far fa-comments"></i>
          <span class="badge badge-danger navbar-badge">3</span>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <a href="#" class="dropdown-item">
            <!-- Message Start -->
            <div class="media">
              <img src="<?php echo base_url('assets/dist/img/user1-128x128.jpg'); ?>" alt="User Avatar" class="img-size-50 mr-3 img-circle">
              <div class="media-body">
                <h3 class="dropdown-item-title">
                  Brad Diesel
                  <span class="float-right text-sm text-danger"><i class="fas fa-star"></i></span>
                </h3>
                <p class="text-sm">Call me whenever you can...</p>
                <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
              </div>
            </div>
            <!-- Message End -->
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <!-- Message Start -->
            <div class="media">
              <img src="<?php echo base_url('assets/dist/img/user8-128x128.jpg'); ?>" alt="User Avatar" class="img-size-50 img-circle mr-3">
              <div class="media-body">
                <h3 class="dropdown-item-title">
                  John Pierce
                  <span class="float-right text-sm text-muted"><i class="fas fa-star"></i></span>
                </h3>
                <p class="text-sm">I got your message bro</p>
                <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
              </div>
            </div>
            <!-- Message End -->
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <!-- Message Start -->
            <div class="media">
              <img src="<?php echo base_url('assets/dist/img/user3-128x128.jpg'); ?>" alt="User Avatar" class="img-size-50 img-circle mr-3">
              <div class="media-body">
                <h3 class="dropdown-item-title">
                  Nora Silvester
                  <span class="float-right text-sm text-warning"><i class="fas fa-star"></i></span>
                </h3>
                <p class="text-sm">The subject goes here</p>
                <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
              </div>
            </div>
            <!-- Message End -->
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item dropdown-footer">See All Messages</a>
        </div>
      </li>
      <!-- Notifications Dropdown Menu -->
      <li class="nav-item dropdown">
        <a class="nav-link" data-bs-toggle="dropdown" href="#">
          <i class="far fa-bell"></i>
          <span class="badge badge-warning navbar-badge">15</span>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <span class="dropdown-item dropdown-header">15 Notifications</span>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <i class="fas fa-envelope mr-2"></i> 4 new messages
            <span class="float-right text-muted text-sm">3 mins</span>
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <i class="fas fa-users mr-2"></i> 8 friend requests
            <span class="float-right text-muted text-sm">12 hours</span>
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <i class="fas fa-file mr-2"></i> 3 new reports
            <span class="float-right text-muted text-sm">2 days</span>
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item dropdown-footer">See All Notifications</a>
        </div>
      </li>
      <li class="nav-item dropdown">
        <a class="nav-link" data-bs-toggle="dropdown" href="#" >
            <i class="fas fa-sign-out-alt" ></i></a>
            <div class="dropdown-menu dropdown-menu-sm dropdown-menu-right">
               <div class="dropdown-divider"></div>
          <a href="<?php echo base_url('Login/logout'); ?>" class="dropdown-item">
            <i class="fas fa-sign-out-alt"></i>Logout
            
          </a> 
            </div>
          
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-light-primary elevation-4">
    <!-- Brand Logo -->
    <a href="<?php echo base_url('dashboard'); ?>" class="brand-link navbar-light">
      <img src="<?php echo base_url('assets/images/logo-login.png'); ?>" alt="AdminLTE Logo" class="brand-image"
           style="opacity: .8">
      <h3><span class="brand-text font-weight-bold" style="color:rgb(76, 83, 126)">LIMS</span></h3>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
          <div class="image" style="padding-top:2% !important;">
         <span class="dot"></span>
        </div>
        <div class="info">
          <a href="#" class="d-block"><?php echo $checkUser->username; ?></a>
        </div>
      </div>
      <?php $url = $this->uri->segment(1); ?>
      <!-- Sidebar Menu -->
         <!--Dasboard-->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class="nav-item has-treeview <?php if($url == "dashboard"){ echo "menu-open"; }?>">
            <a href="<?php echo base_url('dashboard'); ?>" class="nav-link <?php if($url == "dashboard"){ echo "active"; }?>">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>Dashboard</p>
            </a>
          </li>
          
           <!--setting-->
           <li class="nav-item has-treeview <?php if($url == "Users" || $url == "Country" || $url == "Currency"){ echo "menu-open"; }?>">
            <a href="#" class="nav-link <?php if($url == "Users"){ echo "active"; }?>">
             <i class="fas fa-cogs"></i>
              <p>Settings<i class="fas fa-angle-left right"></i></p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item <?php if($url == "Users"){ echo "menu-open"; }?>">
                <a href="#" class="nav-link <?php if($url == "Users"){ echo "active"; }?>">
                  <i class="fas fa-user-cog"></i>
                  <p>User Management <i class="fas fa-angle-left right"></i></p>
                </a>
                <ul class="nav nav-treeview">
                  <li class="nav-item">
                    <a href="<?php echo base_url('Users/index');?>" class="nav-link <?php if($url == "Users"){ echo "active"; }?>">
                      <i class="fas fa-users"></i>
                      <p>Admin Users</p>
                    </a>
                  </li>
                  
                </ul>
                
                <li class="nav-item">
                  <a href="<?php echo base_url('Country')?>" class="nav-link <?php if($url == 'Country'){ echo "active"; }?>">
                  <i class="far fa-circle nav-icon"></i>
                    <p>Country</p>
                  </a>
                </li>
                <li class="nav-item">
                <a href="<?php echo base_url('Currency')?>" class="nav-link <?php if($url == 'Currency'){ echo "active"; }?>">
                <i class="far fa-circle nav-icon"></i>
                  <p>Currency</p>
                </a>
              </li>

              <li class="nav-item">
                <a href="<?php echo base_url('Branch')?>" class="nav-link <?php if($url == 'Branch'){ echo "active"; }?>">
                <i class="far fa-circle nav-icon"></i>
                  <p>Branch</p>
                </a>
              </li>

              <li class="nav-item">
                <a href="<?php echo base_url('Lab')?>" class="nav-link <?php if($url == 'Lab'){ echo "active"; }?>">
                <i class="far fa-circle nav-icon"></i>
                  <p>Lab Type</p>
                </a>
              </li>

              <li class="nav-item">
                <a href="<?php echo base_url('Division')?>" class="nav-link <?php if($url == 'Division'){ echo "active"; }?>">
                <i class="far fa-circle nav-icon"></i>
                  <p>Divisions</p>
                </a>
              </li>
              </li>
             
            </ul>
          </li>

          <!-- sales -->

<li class="nav-item has-treeview">
            <a href="#" class="nav-link">
             <i class="fas fa-cogs"></i>
              <p>
               Sales
                <i class="fas fa-angle-left right"></i> 
              </p>
            </a>
            <ul class="nav nav-treeview">
                <li class="nav-item">
                <a href="#" class="nav-link">
                  <i class="fas fa-user-cog"></i>
                  <p>Customer Management<i class="fas fa-angle-left right"></i></p>
                </a>
              <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="<?php echo base_url('customers')?>" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Customers</p>
                </a>
              </li>
              <!-- <li class="nav-item">
                  <a href="<?php echo base_url('Users/index');?>" class="nav-link">
                  <i class="fas fa-users"></i>
                  <p>Admin Users</p>
                </a>
              </li> -->
              </ul>
             </li>
            </ul>
          </li>
<!--  -->

<li class="nav-item has-treeview <?php if($url == "Manage_lab" || $url == "Manage_lab/record_finding"){ echo "menu-open"; }?>">
            <a href="#" class="nav-link <?php if($url == "Manage_lab" || $url == "Manage_lab/record_finding" ){ echo "active"; }?>">
             <i class="nav-icon fas fa-tasks"></i>
              <p>
               Labs
                <i class="fas fa-angle-left right"></i>
              
              </p>
            </a>
            <ul class="nav nav-treeview">
                <li class="nav-item <?php if($url == "Manage_lab" || $url == "Manage_lab/record_finding"){ echo "menu-open"; }?>">
                <a href="#" class="nav-link <?php if($url == "Manage_lab" || $url == "Manage_lab/record_finding" ){ echo "active"; }?>">
                  <i class="fas fa-copy"></i>
                  <p>Manage Laboratory<i class="fas fa-angle-left right"></i></p>
                </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="<?php echo base_url('Manage_lab'); ?>" class="nav-link <?php if($url == "Manage_lab" ){ echo "active"; }?>">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Accepted Sample</p>
                  </a>
                </li>
               
                <li class="nav-item">
                  <a href="<?php echo base_url('Manage_lab/record_finding')?>" class="nav-link <?php if($url == "Manage_lab/record_finding"){ echo "active"; }?>">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Record Finding</p>
                  </a>
                </li>            
                <li class="nav-item">
                  <a href="<?php echo base_url('Manage_lab/report_listing')?>" class="nav-link <?php if($url == "Manage_lab/report_listing"){ echo "active"; }?>">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Report</p>
                  </a>
                </li>            
              </ul>

            </ul>

            
          </li>

          <!--Jobs-->
          <li class="nav-item has-treeview <?php if($url == "open-trf-list" || $url == "Temp_reg" || $url == "add-open-trf" || $url == "TestRequestForm_Controller" || $url == "sample-list"){ echo "menu-open"; }?>">
            <a href="#" class="nav-link <?php if($url == "open-trf-list" || $url == "Temp_reg" || $url == "add-open-trf" || $url == "TestRequestForm_Controller" || $url == "sample-list"){ echo "active"; }?>">
             <i class="nav-icon fas fa-tasks"></i>
              <p>
               Jobs
                <i class="fas fa-angle-left right"></i>
              
              </p>
            </a>
            <ul class="nav nav-treeview">
                <li class="nav-item <?php if($url == "open-trf-list" || $url == "Temp_reg" || $url == "add-open-trf"){ echo "menu-open"; }?>">
                <a href="#" class="nav-link <?php if($url == "open-trf-list" || $url == "Temp_reg" || $url == "add-open-trf"){ echo "active"; }?>">
                  <i class="fas fa-copy"></i>
                  <p>Test Request Form <i class="fas fa-angle-left right"></i></p>
                </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="<?php echo base_url('open-trf-list'); ?>" class="nav-link <?php if($url == "open-trf-list" || $url == "add-open-trf"){ echo "active"; }?>">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Manage TRF</p>
                  </a>
                </li>
                <!-- <li class="nav-item">
                <a href="<?php echo base_url('TestRequestForm_Controller/sample_received_view'); ?>" class="nav-link <?php if($url == "TestRequestForm_Controller"){ echo "active"; }?>">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Sample Received</p>
                </a>
                </li>   -->
                <li class="nav-item">
                  <a href="<?php echo base_url('Temp_reg/index')?>" class="nav-link <?php if($url == "Temp_reg"){ echo "active"; }?>">
                    <i class="far fa-circle nav-icon"></i>
                    <p>
                      Temporary Registration
                    </p>
                  </a>
                </li>            
              </ul>

              <li class="nav-item">
              <a href="<?php echo base_url('sample-list'); ?>" class="nav-link <?php if($url == "sample-list"){ echo "active"; }?>">
                <i class="far fa-circle nav-icon"></i>
                <p>Sample Registration</p>
              </a>
            </li>
            </ul>

            
          </li>

          <li class="nav-item has-treeview <?php if($url == 'products'){ echo "menu-open"; }?>">
            <a href="#" class="nav-link <?php if($url == 'products' || $url == 'test_master' || $url == 'test_protocols'){ echo "active"; }?>">
            <i class="fas fa-tasks"></i>
              <p>
               Test Management
                <i class="fas fa-angle-left right"></i> 
              </p>
            </a>
            <ul class="nav nav-treeview">
                <li class="nav-item">
                <a href="<?php echo base_url('products')?>" class="nav-link <?php if($url == 'products' || $url == "add_product" || $url == "edit_product"){ echo "active"; }?>">
                <i class="far fa-circle nav-icon"></i>
                  <p>Products</p>
                </a>
              </li>

              <li class="nav-item">
                <a href="<?php echo base_url('test_master')?>" class="nav-link <?php if($url == 'test_master' || $url == "add_test"){ echo "active"; }?>">
                <i class="far fa-circle nav-icon"></i>
                  <p>Test Master</p>
                </a>
              </li>

              <li class="nav-item">
                <a href="<?php echo base_url('test_protocols')?>" class="nav-link <?php if($url == 'test_protocols'){ echo "active"; }?>">
                <i class="far fa-circle nav-icon"></i>
                  <p>Test Protocols</p>
                </a>
              </li>

              <li class="nav-item">
                <a href="<?php echo base_url('packages')?>" class="nav-link <?php if($url == 'packages'){ echo "active"; }?>">
                <i class="far fa-circle nav-icon"></i>
                  <p>Packages</p>
                </a>
              </li>
            </ul>
          </li>

          <li class="nav-item has-treeview <?php if($url == 'performa-invoice'){ echo "menu-open"; }?>">
            <a href="#" class="nav-link <?php if($url == 'performa-invoice'){ echo "active"; }?>">
            <i class="fas fa-tasks"></i>
              <p>
               Invoice
                <i class="fas fa-angle-left right"></i> 
              </p>
            </a>
            <ul class="nav nav-treeview">
                <li class="nav-item">
                <a href="<?php echo base_url('performa-invoice')?>" class="nav-link <?php if($url == 'performa-invoice'){ echo "active"; }?>">
                <i class="far fa-circle nav-icon"></i>
                  <p>Proforma Invoice</p>
                </a>
              </li>

            </ul>
          </li>

         
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>
  <style>
.dot {
  height: 10px;
  width: 10px;
  background-color: green;
  border-radius: 50%;
  display: inline-block;
}
</style>
