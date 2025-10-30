<!-- Navbar -->
<nav class="main-header navbar navbar-expand-md navbar-light navbar-white">
  <div class="container-fluid">
    <a href="<?= base_url('dashboard') ?>" class="navbar-brand">
      <img src="<?php echo base_url('assets/images/logo-login.png'); ?>" alt="AdminLTE Logo" class="brand-image" style="opacity: .8">
      <span class="brand-text font-weight-light"></span>
    </a>

    <button class="navbar-toggler order-1" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse order-3" id="navbarCollapse">
      <!-- Left navbar links -->
      <ul class="navbar-nav">
        <li class="nav-item">
          <a href="<?= base_url('dashboard') ?>" class="nav-link">Dashboard</a>
        </li>

        <?php if (exist_val_multiple(['Branch/index', 'Country/index', 'Currency/index', 'Lab/index', 'Division/index', 'DepartmentList/index', 'DesignationList/index', 'UnitsList/index', 'HolidayList/index', 'Users/index', 'Role/index', 'Operation/index', 'NewsFlash/index', 'RegulationProduct/index', 'Regulations/index'], $this->session->userdata('permission'))) { ?>
          <li class="nav-item dropdown">
            <a id="dropdownSubMenu1" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link dropdown-toggle">Setting</a>
            <ul aria-labelledby="dropdownSubMenu1" class="dropdown-menu border-0 shadow">
              <?php if (exist_val_multiple(['Branch/index', 'Country/index', 'Currency/index', 'Lab/index', 'Division/index', 'DepartmentList/index', 'DesignationList/index', 'UnitsList/index', 'HolidayList/index'], $this->session->userdata('permission'))) { ?>
                <li class="dropdown-submenu dropdown-hover">
                  <a id="dropdownSubMenu2" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="dropdown-item dropdown-toggle">Master</a>
                  <ul aria-labelledby="dropdownSubMenu2" class="dropdown-menu border-0 shadow">
                    <?php if (exist_val('Branch/index', $this->session->userdata('permission'))) { ?>
                      <li>
                        <a tabindex="-1" href="<?php echo base_url('Branch/index'); ?>" class="dropdown-item">Branch</a>
                      </li>
                    <?php } ?>
                    <?php if (exist_val('Lab/index', $this->session->userdata('permission'))) { ?>
                      <li>
                        <a tabindex="-1" href="<?php echo base_url('Lab/index'); ?>" class="dropdown-item">Labs</a>
                      </li>
                    <?php } ?>
                    <?php if (exist_val('LabType/index', $this->session->userdata('permission'))) { ?>
                      <li>
                        <a tabindex="-1" href="<?php echo base_url('LabType/index'); ?>" class="dropdown-item">Labs Type</a>
                      </li> <!-- added by millan on 02-03-2021 -->
                    <?php } ?>
                    <?php if (exist_val('Country/index', $this->session->userdata('permission'))) { ?>
                      <li>
                        <a tabindex="-1" href="<?php echo base_url('Country/index'); ?>" class="dropdown-item">Country</a>
                      </li>
                    <?php } ?>
                    <?php if (exist_val('State/index', $this->session->userdata('permission'))) { ?>
                      <li>
                        <a tabindex="-1" href="<?php echo base_url('State/index'); ?>" class="dropdown-item">State</a>
                      </li>
                    <?php } ?>
                    <!-- added by millan on 05-03-2021 -->
                    <?php if (exist_val('Currency/index', $this->session->userdata('permission'))) { ?>
                      <li>
                        <a tabindex="-1" href="<?php echo base_url('Currency/index'); ?>" class="dropdown-item">Currency</a>
                      </li>
                    <?php } ?>

                    <?php if (exist_val('Division/index', $this->session->userdata('permission'))) { ?>
                      <li>
                        <a tabindex="-1" href="<?php echo base_url('Division/index'); ?>" class="dropdown-item">Division</a>
                      </li>
                    <?php } ?>
                    <?php if (exist_val('DepartmentList/index', $this->session->userdata('permission'))) { ?>
                      <!-- added by millan on 30-01-2021 -->
                      <li>
                        <a tabindex="-1" href="<?php echo base_url('DepartmentList/index'); ?>" class="dropdown-item">Department</a>
                      </li>
                    <?php } ?>
                    <?php if (exist_val('DesignationList/index', $this->session->userdata('permission'))) { ?>
                      <li>
                        <a tabindex="-1" href="<?php echo base_url('DesignationList/index'); ?>" class="dropdown-item">Designation</a>
                      </li>
                    <?php } ?>
                    <?php if (exist_val('UnitsList/index', $this->session->userdata('permission'))) { ?>
                      <li>
                        <a tabindex="-1" href="<?php echo base_url('UnitsList/index'); ?>" class="dropdown-item">Units</a>
                      </li>
                    <?php } ?>
                    <?php if (exist_val('HolidayList/index', $this->session->userdata('permission'))) { ?>
                      <li>
                        <a tabindex="-1" href="<?php echo base_url('HolidayList/index'); ?>" class="dropdown-item">Holidays</a>
                      </li>
                    <?php } ?>
                    <!-- added by millan on 30-01-2021 -->

                    <li>
                      <a tabindex="-1" href="<?php echo base_url('Care/index'); ?>" class="dropdown-item">Application Care</a>
                    </li>

                  </ul>
                </li>
              <?php } ?>
              <?php if ((exist_val_multiple(['Users/index', 'Role/index', 'Operation/index'], $this->session->userdata('permission')))) { ?>
                <li class="dropdown-divider"></li>
                <!-- Level two dropdown-->
                <li class="dropdown-submenu dropdown-hover">
                  <a id="dropdownSubMenu2" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="dropdown-item dropdown-toggle">User Management</a>
                  <ul aria-labelledby="dropdownSubMenu2" class="dropdown-menu border-0 shadow">
                    <?php if (exist_val('Users/index', $this->session->userdata('permission'))) { ?>
                      <li>
                        <a tabindex="-1" href="<?php echo base_url('Users/index'); ?>" class="dropdown-item">LIMS Users</a>
                      </li>
                    <?php } ?>
                    <?php if (exist_val('Role/index', $this->session->userdata('permission'))) { ?>
                      <li>
                        <a tabindex="-1" href="<?php echo base_url('Role/index'); ?>" class="dropdown-item">Role</a>
                      </li>
                    <?php } ?>
                    <?php if (exist_val('Operation/index', $this->session->userdata('permission'))) { ?>
                      <li>
                        <a tabindex="-1" href="<?php echo base_url('Operation/index'); ?>" class="dropdown-item">LIMS Operation</a>
                      </li>
                    <?php } ?>
                    <?php if (exist_val('Website_Operation/index', $this->session->userdata('permission'))) { ?>
                      <li>
                        <a tabindex="-1" href="<?php echo base_url('Website_Operation/index'); ?>" class="dropdown-item">Website Operation</a>
                      </li>
                    <?php } ?>

                    <?php if (exist_val('Website_users/index', $this->session->userdata('permission'))) { ?>
                      <li>
                        <a tabindex="-1" href="<?php echo base_url('Website_users/index'); ?>" class="dropdown-item">Customer Portal Users</a>
                      </li>
                    <?php } ?>
                  </ul>
                </li>
                <!-- End Level two -->
              <?php } ?>
              <?php if ((exist_val_multiple(['NewsFlash/index', 'RegulationProduct/index', 'Regulations/index'], $this->session->userdata('permission')))) { ?>
                <!-- Level 3 Drop Down added by millan on 31-01-2021 -->
                <li class="dropdown-divider"></li>
                <li class="dropdown-submenu dropdown-hover">
                  <a id="dropdownSubMenu2" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="dropdown-item dropdown-toggle">Credentials</a>
                  <ul aria-labelledby="dropdownSubMenu2" class="dropdown-menu border-0 shadow">
                    <?php if (exist_val('NewsFlash/index', $this->session->userdata('permission'))) { ?>
                      <li>
                        <a tabindex="-1" href="<?php echo base_url('NewsFlash/index'); ?>" class="dropdown-item">Manage News Flash</a>
                      </li>
                    <?php } ?>
                    <?php if (exist_val('RegulationProduct/index', $this->session->userdata('permission'))) { ?>
                      <li>
                        <a tabindex="-1" href="<?php echo base_url('RegulationProduct/index'); ?>" class="dropdown-item">Manage Regulations</a>
                      </li>
                    <?php } ?>
                    <?php if (exist_val('Regulations/index', $this->session->userdata('permission'))) { ?>
                      <li>
                        <a tabindex="-1" href="<?php echo base_url('Regulations/index'); ?>" class="dropdown-item">Regulations</a>
                      </li>
                    <?php } ?>
                    <?php if (exist_val('RegulationConfiguration/index', $this->session->userdata('permission'))) { ?>
                      <li>
                        <a tabindex="-1" href="<?php echo base_url('RegulationConfiguration/index'); ?>" class="dropdown-item">Regulation Configuration</a>
                      </li>
                    <?php } ?>
                    <!-- added by millan on 03-Mar-2021 -->
                    <?php if (exist_val('Accreditation/index', $this->session->userdata('permission'))) { ?>
                      <li>
                        <a tabindex="-1" href="<?php echo base_url('Accreditation/index'); ?>" class="dropdown-item">Accreditation</a>
                      </li>
                    <?php } ?>
                    <!-- <li>
                      <a tabindex="-1" href="<?php echo base_url('Test_name/index'); ?>" class="dropdown-item">Test Name</a>
                    </li>
                    <li>
                      <a tabindex="-1" href="<?php echo base_url('Test_method/index'); ?>" class="dropdown-item">Test Method</a>
                    </li> -->
                  </ul>
                </li>
              <?php } ?>

              <!-- Level 3 Drop Down Ends added by millan on 31-01-2021 -->
              <li class="dropdown-divider"></li>
              <?php  //if (exist_val('CVG_Storage/index', $this->session->userdata('permission'))) { ?>
                <a href="<?php echo base_url('Buyer'); ?>" aria-haspopup="true" aria-expanded="false" class="nav-link">Buyer Custom Field</a>
              <?php // } ?>
              <li class="dropdown-divider"></li>
              <!-- Level four dropdown -->
              <?php if (exist_val('CVG_Storage/index', $this->session->userdata('permission'))) { ?>
                <!-- added by millan on 04-03-2021 -->
                <a href="<?php echo base_url('CVG_Storage'); ?>" aria-haspopup="true" aria-expanded="false" class="nav-link">CVG Storage</a>
              <?php } ?>
              <!-- Level four dropdown ends -->

              <li class="dropdown-divider"></li>
              <!-- Level five dropdown -->
              <?php if (exist_val('TaxRules/list_tax_rule', $this->session->userdata('permission'))) { ?>
                <li>
                  <a tabindex="-1" href="<?php echo base_url('TaxRules/index'); ?>" class="dropdown-item">Tax Rules</a>
                </li>
              <?php } ?>
              <!-- added by millan on 03-Mar-2021 -->

              <li class="dropdown-divider"></li>
              <!-- Level five dropdown -->
              <?php if (exist_val('Webinar/index', $this->session->userdata('permission'))) { ?>
                <li>
                  <a tabindex="-1" href="<?php echo base_url('Webinar/index'); ?>" class="dropdown-item">Webinar</a>
                </li>
              <?php } ?>
              <!-- added by millan on 03-Mar-2021 -->

              <!-- Added by Saurabh on 28-06-2021 for services -->
              <li class="dropdown-divider"></li>
              <?php if (exist_val('Services_Controller/index', $this->session->userdata('permission'))) { ?>
                <li>
                  <a tabindex="-1" href="<?php echo base_url('Services_Controller/index'); ?>" class="dropdown-item">Services</a>
                </li>
              <?php } ?>
              <!-- Added by Saurabh on 28-06-2021  for services-->
              <li class="dropdown-divider"></li>
              <li>
                <a tabindex="-1" href="<?php echo base_url('Job_Controller/index'); ?>" class="dropdown-item">Job Description</a>
              </li>
              <!-- Added by CHANDAN ---- 24/01/2022 -->
              <?php if (exist_val('Bot_Configuration/index', $this->session->userdata('permission'))) { ?>
                <li class="dropdown-divider"></li>
                <li>
                  <a tabindex="-1" href="<?php echo base_url('Bot_Configuration/index'); ?>" class="dropdown-item">Bot Configuration</a>
                </li>
              <?php } ?>
              <?php //if (exist_val_multiple(['AssetManagement/index'], $this->session->userdata('permission'))) { 
              ?>
              <li class="dropdown-divider"></li>
              <li class="dropdown-submenu dropdown-hover">
                <a id="dropdownSubMenu2" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="dropdown-item dropdown-toggle">Asset Management</a>
                <ul aria-labelledby="dropdownSubMenu2" class="dropdown-menu border-0 shadow">
                  <li><a href="<?php echo  base_url('AssetManagement/index'); ?>" class="dropdown-item">Assets</a></li>
                  <li><a href="<?php echo base_url('AssetManagement/assets_userlist'); ?>" class="dropdown-item">Assets User </a></li>

                </ul>
              </li>
              <?php //} 
              ?>
              <!-- End CHANDAN -->
              <!-- Level five dropdown ends -->
            </ul>
          </li>
        <?php } ?>
           
           <?php if (exist_val_multiple(['Products/index', 'Test_master/index', 'Test_protocols/index', 'Packages/index', 'Test_method/index'], $this->session->userdata('permission'))) { ?>
          <li class="nav-item dropdown">
            <a id="dropdownSubMenu1" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link dropdown-toggle">Configuration</a>
            <ul aria-labelledby="dropdownSubMenu1" class="dropdown-menu border-0 shadow">
                <?php if (exist_val('Products/index', $this->session->userdata('permission'))) { ?>
                      <li>
                        <a tabindex="-1" href="<?php echo base_url('products'); ?>" class="dropdown-item">Products</a>
                      </li>
                    <?php } ?>
              <li class="dropdown-divider"></li>
              <?php if (exist_val('Test_master/index', $this->session->userdata('permission'))) { ?>
                      <li>
                        <a tabindex="-1" href="<?php echo base_url('test_master') ?>" class="dropdown-item">Test Master</a>
                      </li>
                    <?php } ?><li class="dropdown-divider"></li>
                    <?php //if (exist_val('Test_method/index', $this->session->userdata('permission'))) {
                        {?>
                      <li>
                        <a tabindex="-1" href="<?php echo base_url('Test_method') ?>" class="dropdown-item">Test Method</a>
                      </li>
                    <?php } ?><li class="dropdown-divider"></li>
                    <?php if (exist_val('Test_protocols/index', $this->session->userdata('permission'))) { ?>
                      <li>
                        <a tabindex="-1" href="<?php echo base_url('test_protocols') ?>" class="dropdown-item">Test Protocols</a>
                      </li>
                    <?php } ?><li class="dropdown-divider"></li>
                    <?php if (exist_val('Packages/index', $this->session->userdata('permission'))) { ?>
                      <li>
                        <a tabindex="-1" href="<?php echo base_url('packages') ?>" class="dropdown-item">Packages</a>
                      </li>
                    <?php } ?><li class="dropdown-divider"></li>
                    <?php if (exist_val('Equipment/index', $this->session->userdata('permission'))) { ?>
                      <li>
                        <a tabindex="-1" href="<?php echo base_url('Equipment') ?>" class="dropdown-item">Equipments</a>
                      </li>
                    <?php } ?><li class="dropdown-divider"></li>
            </ul>
          </li>
        <?php } ?>
          
        <?php if ((exist_val_multiple(['customers/index', 'quotes/index', 'contact/index', 'Communication/index', 'opportunity/index'], $this->session->userdata('permission')))) { ?>
          <li class="nav-item dropdown">
            <a id="dropdownSubMenu1" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link dropdown-toggle">Sales</a>
            <ul aria-labelledby="dropdownSubMenu1" class="dropdown-menu border-0 shadow">
              <!-- Level two dropdown-->
              <?php if ((exist_val_multiple(['customers/index', 'contact/index', 'Communication/index', 'opportunity/index'], $this->session->userdata('permission')))) { ?>
                <li class="dropdown-submenu dropdown-hover">
                  <a id="dropdownSubMenu2" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="dropdown-item dropdown-toggle">Customer Management</a>
                  <ul aria-labelledby="dropdownSubMenu2" class="dropdown-menu border-0 shadow">
                    <?php if (exist_val('customers/index', $this->session->userdata('permission'))) { ?>
                      <li>
                        <a tabindex="-1" href="<?php echo base_url('customers'); ?>" class="dropdown-item">Customers</a>
                      </li>
                    <?php } ?>
                    <?php if (exist_val('contact/index', $this->session->userdata('permission'))) { ?>
                      <!-- added by millan on 01-Feb-2021 -->
                      <a tabindex="-1" href="<?php echo base_url('contact'); ?>" class="dropdown-item"> Contacts </a>
                    <?php } ?>
                    <?php if (exist_val('Communication/index', $this->session->userdata('permission'))) { ?>
                      <a tabindex="-1" href="<?php echo base_url('communication'); ?>" class="dropdown-item"> Communication </a>
                    <?php } ?>
                    <?php if (exist_val('opportunity/index', $this->session->userdata('permission'))) { ?>
                      <a tabindex="-1" href="<?php echo base_url('opportunity'); ?>" class="dropdown-item"> Opportunity </a>
                    <?php } ?>
                    <!-- added by millan on 01-Feb-2021 -->
                  </ul>
                </li>
              <?php } ?>
              <!-- End Level two -->
              <?php if (exist_val('quotes/index', $this->session->userdata('permission'))) { ?>
                <li>
                  <a tabindex="-1" href="<?php echo base_url('quotes'); ?>" class="dropdown-item">Quotes</a>
                </li>
              <?php } ?>

              <?php if (exist_val('To_do_list/index', $this->session->userdata('permission')) && exist_val('To_do_list/to_do_listing', $this->session->userdata('permission'))) { ?>
                <li>
                  <a tabindex="-1" href="<?php echo base_url('To_do_list'); ?>" class="dropdown-item">To do list</a>
                </li>
              <?php } ?>


              <?php if (exist_val('Orders/index', $this->session->userdata('permission')) && exist_val('Orders/order_list', $this->session->userdata('permission'))) { ?>
                <li>
                  <a tabindex="-1" href="<?php echo base_url('Orders'); ?>" class="dropdown-item">E-commerce Order List</a>
                </li>
              <?php } ?>
              <li>
                <a tabindex="-1" href="<?php echo base_url('Quote_details/index'); ?>" class="dropdown-item">Quote Details</a>

              </li>
            </ul>
          </li>
        <?php } ?>
         <?php if ((exist_val_multiple([ 'TestRequestForm_Controller/index', 'Temp_reg/index'], $this->session->userdata('permission')))) { ?>
          <li class="nav-item dropdown">
            <a id="dropdownSubMenu1" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link dropdown-toggle">TRF</a>
            <ul aria-labelledby="dropdownSubMenu1" class="dropdown-menu border-0 shadow">
           <?php if (exist_val('TestRequestForm_Controller/index', $this->session->userdata('permission'))) { ?>
                      <li>
                        <a tabindex="-1" href="<?php echo base_url('open-trf-list'); ?>" class="dropdown-item">Manage TRF</a>
                      </li>
                    <?php } ?>
                      <li class="dropdown-divider"></li>
                <?php if (exist_val('Temp_reg/index', $this->session->userdata('permission'))) { ?>
                      <li>
                        <a tabindex="-1" href="<?php echo base_url('Temp_reg/index'); ?>" class="dropdown-item">Temporary Registration</a>
                      </li>
                    <?php } ?>
              <li class="dropdown-divider"></li>
          
            </ul>
          </li>
        <?php } ?>
          
          
        <?php if ((exist_val_multiple(['SampleRegistration_Controller/index', 'TestRequestForm_Controller/index', 'Temp_reg/index'], $this->session->userdata('permission')))) { ?>
          <li class="nav-item dropdown">
            <a id="dropdownSubMenu1" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link dropdown-toggle">Sample</a>
            <ul aria-labelledby="dropdownSubMenu1" class="dropdown-menu border-0 shadow">
              <?php if (exist_val('SampleRegistration_Controller/index', $this->session->userdata('permission'))) { ?>
                <li><a href="<?php echo base_url('SampleRegistration_Controller/sample_register_listing'); ?>" class="dropdown-item">Sample Registration </a></li>
                <li class="dropdown-divider"></li>
                <li><a href="<?php echo base_url('SampleRegistration_Controller/partial_revise_sample'); ?>" class="dropdown-item">Partial/Revise Sample</a></li>
              <?php } ?>
              <li class="dropdown-divider"></li>
            
            </ul>
          </li>
        <?php } ?>
       
          
        <?php if (exist_val_multiple(['Manage_lab/index', 'Manage_lab/record_finding', 'Manage_lab/report_listing', 'Released_report/released_report_listing', 'Approve_Revise_Report/index'], $this->session->userdata('permission'))) { ?>
          <li class="nav-item dropdown">
            <a id="dropdownSubMenu1" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link dropdown-toggle">Lab</a>
            <ul aria-labelledby="dropdownSubMenu1" class="dropdown-menu border-0 shadow">
                  <?php if (exist_val('Manage_lab/index', $this->session->userdata('permission'))) { ?>
                      <li>
                        <a tabindex="-1" href="<?php echo base_url('Manage_lab'); ?>" class="dropdown-item">Accepted Sample</a>
                      </li>
                    <?php } ?>
                    <?php if (exist_val('Manage_lab/record_finding', $this->session->userdata('permission'))) { ?>
                      <li>
                        <a tabindex="-1" href="<?php echo base_url('Manage_lab/record_finding') ?>" class="dropdown-item">Record Finding</a>
                      </li>
                    <?php } ?>
                    <?php if (exist_val('Manage_lab/report_listing', $this->session->userdata('permission'))) { ?>
                      <li>
                        <a tabindex="-1" href="<?php echo base_url('Manage_lab/report_listing') ?>" class="dropdown-item">Generate Report</a>
                      </li>
                    <?php } ?>
                    <!-- added by millan on 20-07-2021 -->
                    <?php if (exist_val('Manage_lab/manual_report_listing', $this->session->userdata('permission'))) { ?>
                      <li>
                        <a tabindex="-1" href="<?php echo base_url('Manage_lab/manual_report_listing') ?>" class="dropdown-item">Manual Reports</a>
                      </li>
                    <?php } ?>
                    <!-- added by millan on 20-07-2021 -->
                    <?php if (exist_val('Released_report/released_report_listing', $this->session->userdata('permission'))) { ?>
                      <li>
                        <a tabindex="-1" href="<?php echo base_url('Released_report/released_report_listing') ?>" class="dropdown-item">Released Reports</a>
                      </li>
                    <?php } ?>
                    <!-- Added by CHANDAN --07-07-2022 -->
                    <?php if (exist_val('Approve_Revise_Report/index', $this->session->userdata('permission'))) { ?>
                      <li>
                        <a tabindex="-1" href="<?php echo base_url('Approve_Revise_Report/index') ?>" class="dropdown-item">Approve Revise Report</a>
                      </li>
                    <?php } ?>
            
            </ul>
          </li>


        <?php } ?>

        <!-- MIS -->
        <?php /*if (exist_val_multiple(['Invoice_register/index', 'invoice_register/invoice_register_list'], $this->session->userdata('permission'))) { ?>
          <li class="nav-item dropdown">
            <a id="dropdownSubMenu1" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link dropdown-toggle">MIS Report</a>
            <ul aria-labelledby="dropdownSubMenu1" class="dropdown-menu border-0 shadow">
              <li><a href="<?php echo base_url('Invoice_register'); ?>" class="dropdown-item">Invoice Register</a></li>

              <?php if (exist_val_multiple(['TRF_register/index', 'trf_register/trf_register_list'], $this->session->userdata('permission'))) { ?>
                <li><a href="<?php echo base_url('TRF_register'); ?>" class="dropdown-item">TRF Register</a></li>
              <?php } ?>

              <?php if (exist_val_multiple(['Monthly_sales_report/index', 'monthly_sales_report/monthly_sales_report'], $this->session->userdata('permission'))) { ?>

                <li><a href="<?php echo base_url('Monthly_sales_report'); ?>" class="dropdown-item">Monthly Sales Report</a></li>
              <?php } ?>

              <?php if (exist_val_multiple(['Analyst_performance_report/index', 'analyst_performance_report/analyst_performance_report_list'], $this->session->userdata('permission'))) { ?>

                <li><a href="<?php echo base_url('Analyst_performance_report'); ?>" class="dropdown-item">Analyst Performance Report</a></li>
              <?php } ?>


              <?php if (exist_val_multiple(['Lab_performance_report/index', 'lab_performance_report/lab_performance_report'], $this->session->userdata('permission'))) { ?>

                <li><a href="<?php echo base_url('Lab_performance_report'); ?>" class="dropdown-item">Lab Performance Report</a></li>
              <?php } ?>

              <?php if (exist_val_multiple(['Report_generated/index', 'report_generated/report_generated'], $this->session->userdata('permission'))) { ?>
                <li><a href="<?php echo base_url('Report_generated'); ?>" class="dropdown-item">Report Generated</a></li>
              <?php } ?>
            </ul>

          </li>
        <?php } */ ?>
        <!-- END MIS -->


        <?php if (exist_val_multiple(['Invoice_Controller/performa_invoice_list', 'Released_invoice/released_invoice_list', 'Approve_Revise_Report/index'], $this->session->userdata('permission'))) { ?>
          <li class="nav-item dropdown">
            <a id="dropdownSubMenu1" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link dropdown-toggle">Invoice</a>
            <ul aria-labelledby="dropdownSubMenu1" class="dropdown-menu border-0 shadow">
              <?php if (exist_val('Invoice_Controller/performa_invoice_list', $this->session->userdata('permission'))) { ?>
                <li><a href="<?php echo base_url('performa-invoice'); ?>" class="dropdown-item">Performa Invoice </a></li>
              <?php } ?>
              <?php if (exist_val('Released_invoice/released_invoice_list', $this->session->userdata('permission'))) { ?>
                <li><a href="<?php echo base_url('Released_invoice/released_invoice_list'); ?>" class="dropdown-item">Released Invoice </a></li>
              <?php } ?>
              <!--23-07-2021-->
              <li><a href="<?php echo base_url('invoices'); ?>" class="dropdown-item">Invoices </a></li>
              <?php if (exist_val_multiple(['Billing_Controller/index'], $this->session->userdata('permission'))) { ?>
                <li>
                  <a href="<?= base_url('Billing_Controller') ?>" class="dropdown-item">Billing Interface</a>
                </li>
              <?php } ?>
            </ul>
          </li>
        <?php } ?>

        <?php /* if (exist_val('Invoice_import/index', $this->session->userdata('permission'))) { ?>
          <li class="nav-item dropdown">
            <a id="dropdownSubMenu1" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link dropdown-toggle">Client Info</a>
            <ul aria-labelledby="dropdownSubMenu1" class="dropdown-menu border-0 shadow">
              <li><a href="<?php echo base_url('Invoice_import/index'); ?>" class="dropdown-item"> INVOICE IMPORT </a></li>
            </ul>
          </li>
        <?php } */ ?>
        <?php if (exist_val_multiple(['Low_item_notification/index', 'Store_management/index', 'Category/index', 'Item/index'], $this->session->userdata('permission'))) { ?>
          <li class="nav-item dropdown">
            <a id="dropdownSubMenu1" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link dropdown-toggle">Store</a>
            <ul aria-labelledby="dropdownSubMenu1" class="dropdown-menu border-0 shadow">
              <?php if (exist_val('Low_item_notification/index', $this->session->userdata('permission'))) { ?>
                <li><a href="<?php echo base_url('Low_item_notification'); ?>" class="dropdown-item">Low_item_notification</a></li>
              <?php } ?>
              <?php if (exist_val('Store_management/index', $this->session->userdata('permission'))) { ?>
                <li><a href="<?php echo base_url('Store_management'); ?>" class="dropdown-item">Store_management</a></li>
              <?php } ?>
              <?php if (exist_val('Category/index', $this->session->userdata('permission'))) { ?>
                <li><a href="<?php echo base_url('Category'); ?>" class="dropdown-item">Store Category</a></li>
              <?php } ?>
              <?php if (exist_val('Item/index', $this->session->userdata('permission'))) { ?>
                <li><a href="<?php echo base_url('Item'); ?>" class="dropdown-item">Item</a></li>
              <?php } ?>
            </ul>
          </li>
        <?php } ?>

        <!-- sample tracking start -->
        <?php if (exist_val_multiple(['Sample_tracking/index'], $this->session->userdata('permission'))) { ?>
          <li class="nav-item dropdown">
            <a id="dropdownSubMenu1" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link dropdown-toggle">Sample Track</a>
            <ul aria-labelledby="dropdownSubMenu1" class="dropdown-menu border-0 shadow">
              <?php if (exist_val('Sample_tracking/index', $this->session->userdata('permission'))) { ?>
                <li><a href="<?php echo base_url('Sample_tracking'); ?>" class="dropdown-item">Sample Tracking</a></li>
              <?php } ?>
            </ul>
          </li>
        <?php } ?>
        <!-- sample tracking end -->

        <?php if (exist_val_multiple(['Buyer_manual/index'], $this->session->userdata('permission'))) { ?>
          <li class="nav-item">
            <a href="<?= base_url('Buyer_manual') ?>" class="nav-link">Buyer Manual</a>
          </li>
        <?php } ?>


        <!-- SEARCH FORM -->
        <!-- <form class="form-inline ml-0 ml-md-3">
          <div class="input-group input-group-sm">
            <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
            <div class="input-group-append">
              <button class="btn btn-navbar" type="submit">
                <i class="fas fa-search"></i>
              </button>
            </div>
          </div>
        </form> -->

        <!--Barcode Scanner-->
        <ul class="navbar-nav">
          <li class="nav-item">
            <a data-bs-toggle="modal" data-bs-target="#view_barcode_modal" onclick="clear_barcode_div()" class="nav-link font-weight-bold" href="#" role="button">Barcode Scan</a>
          </li>
        </ul>
    </div>


    <!-- Right navbar links -->
    <ul class="order-1 order-md-3 navbar-nav navbar-no-expand ml-auto">
      <!-- Messages Dropdown Menu -->
      <li class="nav-item dropdown d-none">
        <a class="nav-link" data-bs-toggle="dropdown" href="#">
          <i class="fas fa-comments"></i>
          <span class="badge badge-danger navbar-badge">3</span>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <a href="#" class="dropdown-item">
            <!-- Message Start -->
            <div class="media">
              <img src="<?php echo base_url('assets/dist/img/user3-128x128.jpg'); ?>" alt="User Avatar" class="img-size-50 mr-3 img-circle">
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
              <img src="<?php echo base_url('assets/dist/img/user3-128x128.jpg'); ?>" alt="User Avatar" class="img-size-50 img-circle mr-3">
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
      <li class="nav-item dropdown d-none">
        <a class="nav-link" data-bs-toggle="dropdown" href="#">
          <i class="far fa-bell"></i>
          <span class="badge badge-warning navbar-badge">15</span>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <span class="dropdown-header">15 Notifications</span>
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
      <li class="nav-item">
        <div class="dropdown-menu-right">
          <a href="#" class="dropdown-item">
              <?php $checkUser = $this->session->userdata('user_data');

              echo  $checkUser->username; ?>
          </a>
        </div>
      </li>
      <li class="nav-item dropdown">
        <a class="nav-link" data-bs-toggle="dropdown" href="#"><i class="fas fa-sign-out-alt"></i></a>
        <div class="dropdown-menu dropdown-menu-sm dropdown-menu-right">
          <a href="<?php echo base_url('Login/logout'); ?>" class="dropdown-item">
            <i class="fas fa-sign-out-alt"></i> Logout
          </a>
        </div>
    </ul>
  </div>
</nav>
<!-- <div id="demo" class="carousel slide" data-ride="carousel">

  <div class="container carousel-inner no-padding">
    <div class="carousel-item active">
      <div class="col-xs-3 col-sm-12 col-md-12 ">
        Please get your country connected with ERP - Deadline will be 15th May 2022.
      </div>

    </div>
    <div class="carousel-item">
      <div class="col-xs-3 col-sm-12 col-md-12">
        Proforma Invoice generation will be must from 1st May 2022 for all countries and without generating it you are not allowedd to move sample further.
      </div>

    </div>
  </div>

 

  </a>
</div> -->
<style>
  .carousel-inner {
    position: relative;
    width: 88%;
    overflow: hidden;
    z-index: 99;
    font-size: 14px;
    color: #ffd905 !important;
    text-align: center;
  }
</style>
<!-- /.navbar -->