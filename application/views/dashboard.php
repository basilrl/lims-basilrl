<script src="<?= base_url('public/js/highcharts.js') ?>"></script>
<script src="<?= base_url('public/js/data.js') ?>"></script>
<script src="<?= base_url('public/js/drilldown.js') ?>"></script>
<script src="<?= base_url('public/js/exporting.js') ?>"></script>
<script src="<?= base_url('public/js/export-data.js') ?>"></script>
<script src="<?= base_url('public/js/accessibility.js') ?>"></script>
<script src="<?= base_url('assets/js/backlogs.js') ?>"></script>
<!-- UPDATE 17-05-2021 -->
<script src="<?= base_url('assets/js/invoice_not_upload.js') ?>"></script>
<!-- END -->




<style>
  .ui-autocomplete {
    max-height: 100px;
    overflow-y: auto;
    overflow-x: hidden;
  }

  * html .ui-autocomplete {
    height: 100px;
  }

  .fixed-height {
    padding: 1px;
    max-height: 200px;
    overflow: auto;
  }


  .highcharts-figure,
  .highcharts-data-table table {
    min-width: 310px;
    max-width: 800px;
    margin: 1em auto;
  }

  #container {
    /*height: 400px;*/
  }

  .highcharts-data-table table {
    font-family: Verdana, sans-serif;
    border-collapse: collapse;
    border: 1px solid #EBEBEB;
    /*margin: 10px auto;*/
    text-align: center;
    width: 100%;
    max-width: 500px;
  }

  .highcharts-data-table caption {
    padding: 1em 0;
    font-size: 1.2em;
    color: #555;
  }

  .highcharts-data-table th {
    font-weight: 600;
    padding: 0.5em;
  }

  .highcharts-data-table td,
  .highcharts-data-table th,
  .highcharts-data-table caption {
    padding: 0.5em;
  }

  .highcharts-data-table thead tr,
  .highcharts-data-table tr:nth-child(even) {
    background: #f8f8f8;
  }

  .highcharts-data-table tr:hover {
    background: #f1f7ff;
  }

  /*.wrapper {
    position: relative;
    padding: 10px;
    width: 100%;
}*/
  select.form-control-sm~.select2-container--default {
    font-size: 17px;
  }

  .select2-container .select2-selection--multiple .select2-selection__rendered {
    display: block;
    list-style: none;
    padding: 0;
  }

  .card {
    margin: 15px;

  }

  .hit {
    height: 188px;
  }

  h1 {
    font-weight: bold;
  }

  .bg_filter {
    background: #e7e7e6;
    padding-top: 6px;
  }
</style>

<!-- UPDATED 17=05=2021 -->
<div class="modal fade" id="view_invoice" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Invoice Details</h5>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="invoice_view_body">

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>

      </div>
    </div>
  </div>
</div>

<!-- NOT UPLOAD INVOICE -->

<div class="container-fluid">
  <div class="row">
    <div class="col-sm-12 text-center">
      <h2 class="mt-3 mb-3">
        DASHBOARD
      </h2>
    </div>
  </div>
</div>
<!-- END -->
<div class="container-fluid mb-4 bg_filter">
  <span id="error_message" style="color: red;"></span>
  <div class="row mt-4">
    <div class="col-sm-3">
      <select id="buyer" name="buyer" class="form-control">
        <option value="">Buyer's Name</option>
        <?php foreach ($buyer as $buyerdetails): ?>
          <option value="<?= $buyerdetails->customer_id ?>"><?= $buyerdetails->customer_name ?></option>
        <?php endforeach; ?>
      </select>
    </div>

    <div class="col-sm-3">
      <select id="agent" name="agent" class="form-control">
        <option value="">Applicant Name</option>
        <!-- <option value="">Agent Name</option> -->
        <?php foreach ($customer as $agentdetails): ?>
          <option value="<?= $agentdetails->customer_id ?>"><?= $agentdetails->customer_name ?></option>
        <?php endforeach; ?>
      </select>
    </div>

    <div class="col-md-3">
      <select id="customer" name="customer" class="form-control">
        <option value="">Customer's Name</option>
        <?php foreach ($customer as $customerdetails): ?>
          <option value="<?= $customerdetails->customer_id ?>"><?= $customerdetails->customer_name ?></option>
        <?php endforeach; ?>
      </select>
    </div>

       <div class="col-sm-3">
      <select id="labType" name="labType" class="form-control">
        <option value="">Lab Type</option>
        <?php foreach ($labType as $labs): ?>
          <option value="<?= $labs->lab_type_id ?>"><?= $labs->lab_type_name ?></option>
        <?php endforeach; ?>
      </select>
    </div>
  </div>
  <div class="row mt-3">
    <div class="col-md-3 ">
      <label>Sample Create Date From</label>
      <input id="start_date" name="start_date" type="date" class="form-control" placeholder="created date">
    </div>
    <div class="col-md-3">
      <label>Sample Create Date To</label>
      <input id="end_date" name="end_date" type="date" class="form-control">
    </div>
    <div class="col-md-3">
       <label></label>
      <select id="month" name="month" class="form-control">
        <option value="">Choose Month..</option>
      </select>
    </div>
    <div class="col-md-3">
      <label></label>
      <select id="year" name="year" class="form-control">
        <option value="">Choose Year..</option>
      </select>
    </div>


  </div>
  <div class="row mt-3">
       <div class="col-md-3">
      <select name="division_dropdown" class="form-control  division_dropdown" style="display:inline-block" placeholder="Divisions">
        <option value="">Division</option>
        <?php foreach ($division as $division): ?>
          <option value="<?= $division['division_id'] ?>"><?= $division['division_name'] ?></option>
        <?php endforeach; ?>
      </select>

    </div>
        <div class="col-md-2">
      <div class="form-group pt-0 mt-0">
        <button type="button" class="btn btn-secondary" id="filter"><i class="fa fa-search" aria-hidden="true"></i></button>
        <button type="button " class="btn btn-danger" id="reset"><i class="fa fa-retweet" aria-hidden="true"></i></button>
      </div>
    </div>
  </div>

</div>
<div class="container-fluid">
  <div class="row">
    <div class="col">
      <div class="card p-2 text-center hit">
        <h1 class="text" id="todayRegisteredSample"><?= $todayRegisteredSample ?></h1>
        <P>Registered samples (Today)</P>
        <a href="javascript:void(0)" class="text-success viewtodayRegisteredSample" target="_blank">View list</a>
      </div>
    </div>
    <div class="col">
      <div class="card p-2 hit text-center">
        <h1 class="text" id="totalRegisteredSample"><?= $totalRegisteredSample ?></h1>
        <p>Total Registered <br>(as on)</p>
        <a href="javascript:void(0)" class="text-success viewtotalRegisteredSample">View list</a>
      </div>
    </div>
    <div class="col">
      <div class="card hit p-2 text-center">
        <h1 id="todayHoldSample"><?= $todayHoldSample ?></h1>
        <p>Total Hold <br>(Today)</p>
        <a href="javascript:void(0)" class="text-success viewtodayHoldSample" target="_blank">View list</a>
      </div>
    </div>
    <div class="col">
      <div class="card hit p-2 text-center">
        <h1 id="totalHoldSample"><?= $totalHoldSample ?></h1>
        <p>Total Hold <br>(as on)</p>
        <a href="javascript:void(0)" class="text-success viewtotalHoldSample" target="_blank">View list</a>
      </div>
    </div>
    <div class="col">
      <div class="card hit p-2 text-center">
        <h1 class="text-danger" id="totalCancelled"><?= $totalCancelled ?></h1>
        <p>Total <br>Cancelled</p>
        <a href="javascript:void(0)" class="text-success viewtotalCancelled" target="_blank">View list</a>
      </div>
    </div>
    <div class="col">
      <div class="card hit p-2 text-center">
        <h1 class="text-" id="todayOverDue"><?= $todayOverDue ?></h1>
        Overdue <br>Reports(Today)</p>
        <a href="javascript:void(0)" class="text-success viewtodayOverDue" target="_blank">View list</a>
      </div>
    </div>
    <div class="col">
      <div class="card hit p-2 text-center">
        <h1 class="text-panding" id="totalOverDue"><?= $totalOverDue ?></h1>
        <p>Total Overdue Reports (as on)</p>
        <a href="javascript:void(0)" class="text-success viewtotalOverDue" target="_blank">View list</a>
      </div>
    </div>
    <div class="col">
      <div class="card hit p-2 text-center">
        <h1 class="text-panding" id="totalReport"><?= $totalReport ?></h1>
        <p>Total Reports Generated(as on)</p>
        <a href="javascript:void(0)" class="text-success viewtotalReport" target="_blank">View list</a>
      </div>
    </div>
  </div>
</div>
<div class="container-fluid bg-light">
  <div class="row">

    <!-- Added by CHANDAN --- 27-09-2021 ---- -->
    <?php if (exist_val('Dashboard/view_customers_data', $this->session->userdata('permission'))) { ?>
      <div class="col-md-4 card">
        <figure class="highcharts-figure">

          <div id="cust_container" class="mt-2"></div>
          <p class="cust_highcharts-description"></p>
        </figure>
      </div>
    <?php } ?>

    <?php if (exist_val('Dashboard/view_opportunity_data', $this->session->userdata('permission'))) { ?>
      <div class="col-md-6">
        <div class="card">
          <figure class="highcharts-figure">

            <div id="opportunity_container" class="mt-2"></div>
            <p class="opportunity_highcharts-description"></p>
          </figure>
        </div>
      </div>
    <?php } ?>

    <?php if (exist_val('Dashboard/view_performa_data', $this->session->userdata('permission'))) { ?>
      <div class="col-md-4">
        <figure class="highcharts-figure">

          <div id="performa_container" class="mt-2"></div>
          <p class="performa_highcharts-description"></p>
        </figure>
      </div>
    <?php } ?>
    <!-- END -->

    <div class="col-md-4 ">
      <div class="card">
        <figure class="highcharts-figure">

          <div id="container" class="mt-2">

          </div>
          <p class="highcharts-description">
          </p>
        </figure>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card">
        <figure class="highcharts-figure">
          <div class="row mb-2">
            <div class="col-md-4">
              <input id="sample_fdate" type="hidden" name="fdate" class="form-control">
            </div>

          </div>
          <div id="sample_status_div"> </div>
          <p class="highcharts-description">

          </p>
        </figure>
      </div>
    </div>
    <!-- ajit start code  -->
    <?php if (exist_val('Backlogs_details/view_due_data', $this->session->userdata('permission'))) { ?>
      <div class="col-md-4">
        <div class="card">
          <figure class="highcharts-figure">
            <!-- <div class="row mt-2 mb-2">
            <div class="col-md-4">
              <div class="ui-widget">
                <label class="text-danger">at least 3 characters</label>
                <input id="report_customer_name" class="form-control" placeholder="Customer Name">
                <input id="report_customer_id" name="report_customer_id" type="hidden">
              </div>
            </div>

            <div class="col-md-4">
              <div class="ui-widget">
                <label class="text-danger">at least 3 characters</label>
                <input id="report_category_name" class="form-control" placeholder="Category Name">
                <input id="report_category_id" name="report_category_id" type="hidden">
              </div>
            </div>
            <div class="col-md-4">
              <div class="ui-widget">
                <label class="text-danger">at least 3 characters</label>
                <input id="report_sample_name" class="form-control" placeholder="Product Name">
                <input id="report_sample_id" name="report_sample_id" type="hidden">
              </div>
            </div>
          </div> -->
            <!-- <div class="row mb-2">

            <div class="col-md-4">
              <input id="report_fdate" type="date" name="report_fdate" class="form-control">
            </div>

            <div class="col-md-4">
              <input id="report_ldate" type="date" name="report_date" class="form-control">
            </div>

            <div class="col-md-1">
              <a href="#" onclick="get_report_chart()"> <i class="fa fa-search mt-3"></i></a>
            </div>

            <div class="col-md-1">
              <a href="#" onclick="get_report_chart(true)"> <i class="fa fa-sync-alt mt-3 text-danger"></i></a>
            </div>
          </div> -->
            <div id="report_status_div"> </div>
            <p class="highcharts-description">

            </p>
          </figure>
        </div>
      </div>



      <!-- ajit start code  end -->



    <?php } ?>
  </div>
</div>
<div class="container-fluid bg-white">
  <div class="row mt-4 ">
    <div class="col-md-12">
      <div class="card p-3">
        <div class="col-md-12">
          <h3 style="background-color:white;color:black;text-align:center">BACKLOGS DETAILS</h3>
        </div>
        <!-- <div class="row mt-2 mb-2">

          <div class="col-md-4">
            <input type="hidden" class="gc_hide" value="">
            <input type="text" class="serach_by_gc form-control form-control-sm" placeholder="Search by GC Number..." autocomplete="off">
            <ul class="list-group-item gc_backlog_list" style="display:none">
            </ul>
          </div>
          <div class="col-md-4">
            <select name="division_dropdown" class="form-control form-control-sm division_dropdown" style="display:inline-block" placeholder="Divisions">

            </select>

          </div>

          <div class="col-md-4" style="display: flex;">
            <button class="search_button btn btn-sm btn-default">Search</button>
            <button class="clear_button btn btn-sm btn-default">Clear</button>

            <?php if (exist_val('Backlogs_details/backlog_export', $this->session->userdata('permission'))) { ?>
              <a href="<?php echo base_url('Backlogs_details/backlog_export') ?>" class="export_button btn btn-sm btn-success" style="margin-left: 15px;">Export</a>

            <?php } ?>
          </div>


        </div> -->

        <div class="backlog_container" style="display:flex">

          <!-- <ul class="side_ul list-group list-group-sm" style="list-style-type: none; display:inline-block;flex:1;">

                  </ul> -->

          <div class="table-responsive">
            <table class="table table-sm backlog_data_table">
              <thead style="background: #fafafa;">
                <tr>
                  <th>SL No.</th>
                  <th>BASIL REPORT NUMBER</th>
                  <th>SAMPLE DESCRIPTION</th>
                  <th>DIVISION</th>
                  <th>DUE DATE</th>
                  <th>ACTION</th>
                </tr>
              </thead>

              <tbody style="font-size: 12px;">

              </tbody>
            </table>
          </div>


        </div>

        <div class="text-left" id="showing_result_div">
          Showing <span class="start"></span> to <span class="end"></span> of <span class="total"></span> Results
        </div>

        <div class="text-right">
          <button class="previous_data btn btn-sm btn-default" data-id='1' data-limit='5' data-offset='0'>Prev</button>
          <button class="next_data btn btn-sm btn-default" data-id='1' data-limit='5' data-offset='0'>Next</button>
        </div>
      </div>
    </div>
    <div class="col-md-12">

      <!-- UPDATE 17-05-2021 -->
      <?php if (exist_val('Invoice_not_upload/index', $this->session->userdata('permission'))) { ?>

        <div class="card pb-3">

          <div class="col-md-12">
            <h3 style="background-color:white;color:black;text-align:center">Invoice not Uploaded</h3>
          </div>



          <!-- <div class="row mt-3 ml-3">
        <div class="col-sm-4">
          <input type="hidden" class="cus_hide" value="">
          <input type="text" class="form-control form-control-sm search_by_cus" placeholder="Search By Customer ..." autocomplete="off" value="">
          <ul class="list-group-item cus_list" style="display:none">
          </ul>
        </div>


        <div class="col-sm-3">
          <input type="text" name="from_date" class="form-control form-control-sm from_date" placeholder="START DATE" value="" onfocus="(this.type='date')" onblur="(this.type='text')">

        </div>

        <div class="col-sm-4">
          <input type="text" name="to_date" class="form-control form-control-sm to_date" placeholder="END DATE" value="" onfocus="(this.type='date')" onblur="(this.type='text')">
        </div>
      </div>
      <div class="row mt-3 ml-3">

        <div class="col-sm-4">
          <select name="division_dropdown_invoice" class="form-control form-control-sm division_dropdown_invoice" style="display:inline-block" placeholder="Divisions" value="">

          </select>
        </div>
        <div class="col-sm-8">

          <div class="row">
            <div class="col-sm-8">
              <input type="hidden" class="invoice_hide" value="">
              <input type="text" class="form-control form-control-sm search_by_invoice" placeholder="Search By GC Number..." autocomplete="off" value="">
              <ul class="list-group-item invoice_no_list" style="display:none">
              </ul>
            </div>
            <div class="col-sm-2">
              <button class="btn btn-sm btn-default search_button_invoice" style="margin-left: -15px;"><i class="fas fa-search"></i></button><button class="btn btn-sm btn-default clear_button_invoice"><i class="fas fa-sync-alt"></i></i></button>
            </div>



            <div class="col-sm-2">
              <?php if (exist_val('Invoice_not_upload/invoice_export', $this->session->userdata('permission'))) { ?>
                <a href="<?php echo base_url('Invoice_not_upload/invoice_export') ?>" class="INVOICE_export_button btn btn-sm btn-success" title="Export"><i class="fas fa-file-excel"></i></a>
              <?php } ?>
            </div>
          </div>
        </div>

      </div> -->


          <div class="table-responsive">
            <table class="table table-sm">
              <thead style="background: #fafafa;">
                <th>SL.</th>
                <th>BASIL REPORT NUMBER.</th>
                <th>CUSTOMER NAME</th>
                <th>DIVISION</th>
                <th>STATUS</th>
                <th>ACTION</th>

              </thead>

              <tbody id="invoice_not_upload" style="font-size: 12px;">
              </tbody>
            </table>
          </div>

          <div class="row mt-3 ml-5 mr-5">
            <div class="col-sm-6">
              <div class="text-left" id="showing_result">
                Showing <span class="start_invoice"></span> to <span class="end_invoice"></span> of <span class="total_invoice"></span> Results
              </div>
            </div>
            <div class="col-sm-6">
              <div class="text-right">
                <button class="previous_data_invoice btn btn-sm btn-default" data-id='1' data-limit='5' data-offset='0'>Prev</button>
                <button class="next_data_invoice btn btn-sm btn-default" data-id='1' data-limit='5' data-offset='0'>Next</button>
              </div>
            </div>

          </div>

        </div>
      <?php } ?>

    </div>
  </div>
</div>
<!-- END -->
<script>
  $('.viewtodayRegisteredSample').on('click', function() {
    let url = '<?= base_url('SampleRegistration_Controller/sample_register_listing') ?>';

    var today = new Date();
    var formattedDate = today.getFullYear() + '-' +
      String(today.getMonth() + 1).padStart(2, '0') + '-' +
      String(today.getDate()).padStart(2, '0');
    let start_date = btoa(formattedDate);

    let end_date = btoa(formattedDate);

    let buyer = $('#buyer').val();
    if (buyer == "") {
      buyer = null;
    } else {
      buyer = ($('#buyer').val());
    }
    let agent = $('#agent').val();
    if (agent == "") {
      agent = null;
    } else {
      agent = ($('#agent').val());
    }
    let labType = $('#labType').val();
    if (labType == "") {
      labType = null;
    } else {
      labType = btoa($('#labType').val());
    }
    let customer = $('#customer').val();
    if (customer == "") {
      customer = null;
    } else {
      customer = ($('#customer').val());
    }
    let division_dropdown = $('.division_dropdown').val();
    if (division_dropdown == "") {
      division_dropdown = null;
    } else {
      division_dropdown = ($('.division_dropdown').val());
    }

    // new
    let year = $('#year').val();
    if (year == "") {
      year = null;
    } else {
      year = ($('#year').val());
    }
    let month = $('#month').val();
    if (month == "") {
      month = null;
    } else {
      month = ($('#month').val());
    }
    // let status = btoa('Registered');
    let status = null;

    let newUrl = url + '/' + 0 + '/' + null + '/' + customer + '/' + null + '/' + null + '/' + null + '/' + null + '/' + buyer + '/' + status + '/' + division_dropdown + '/' + null + '/' + start_date + '/' + end_date + '/' + agent + '/' + null + '/' + null + '/' + null + '/' + null + '/' + year + '/' + month;
    // window.location.href = url + '/' + 0 + '/' + null + '/' + customer + '/' + null + '/' + null + '/' + null + '/' + null + '/' + buyer + '/' + status + '/' + division_dropdown + '/' + null + '/' + start_date + '/' + end_date + '/' + agent;

    window.open(newUrl, '_blank');
  })
  $('.viewtotalRegisteredSample').on('click', function() {
    let url = '<?= base_url('SampleRegistration_Controller/sample_register_listing') ?>';

    let start_date = $('#start_date').val();
    if (start_date == "") {
      start_date = null;
    } else {
      start_date = btoa($('#start_date').val());
    }
    let end_date = $('#end_date').val();
    if (end_date == "") {
      end_date = null;
    } else {
      end_date = btoa($('#end_date').val());
    }
    let buyer = $('#buyer').val();
    if (buyer == "") {
      buyer = null;
    } else {
      buyer = ($('#buyer').val());
    }
    let agent = $('#agent').val();
    if (agent == "") {
      agent = null;
    } else {
      agent = ($('#agent').val());
    }
    let labType = $('#labType').val();
    if (labType == "") {
      labType = null;
    } else {
      labType = ($('#labType').val());
    }
    let customer = $('#customer').val();
    if (customer == "") {
      customer = null;
    } else {
      customer = ($('#customer').val());
    }
    let division_dropdown = $('.division_dropdown').val();
    if (division_dropdown == "") {
      division_dropdown = null;
    } else {
      division_dropdown = ($('.division_dropdown').val());
    }
      // new
    let year = $('#year').val();
    if (year == "") {
      year = null;
    } else {
      year = ($('#year').val());
    }
    let month = $('#month').val();
    if (month == "") {
      month = null;
    } else {
      month = ($('#month').val());
    }
    // let status = btoa('Registered');;
    let status = null;
    console.log(buyer);

    let newUrl = url + '/' + 0 + '/' + null + '/' + customer + '/' + null + '/' + null + '/' + null + '/' + null + '/' + buyer + '/' + status + '/' + division_dropdown + '/' + null + '/' + start_date + '/' + end_date + '/' + agent + '/' + null + '/' + null + '/' + null + '/' + null + '/' + year + '/' + month;
    window.open(newUrl, '_blank');
    // window.location.href = url + '/' + 0 + '/' + null + '/' + customer + '/' + null + '/' + null + '/' + null + '/' + null + '/' + buyer + '/' + status + '/' + division_dropdown + '/' + null + '/' + start_date + '/' + end_date + '/' + agent;
  })
  $('.viewtodayHoldSample').on('click', function() {
    let url = '<?= base_url('SampleRegistration_Controller/sample_register_listing') ?>';

    var today = new Date();
    var formattedDate = today.getFullYear() + '-' +
      String(today.getMonth() + 1).padStart(2, '0') + '-' +
      String(today.getDate()).padStart(2, '0');
    let start_date = btoa(formattedDate);

    let end_date = btoa(formattedDate);

    let buyer = $('#buyer').val();
    if (buyer == "") {
      buyer = null;
    } else {
      buyer = ($('#buyer').val());
    }
    let agent = $('#agent').val();
    if (agent == "") {
      agent = null;
    } else {
      agent = ($('#agent').val());
    }
    let labType = $('#labType').val();
    if (labType == "") {
      labType = null;
    } else {
      labType = ($('#labType').val());
    }
    let customer = $('#customer').val();
    if (customer == "") {
      customer = null;
    } else {
      customer = ($('#customer').val());
    }
    let division_dropdown = $('.division_dropdown').val();
    if (division_dropdown == "") {
      division_dropdown = null;
    } else {
      division_dropdown = ($('.division_dropdown').val());
    }
      // new
    let year = $('#year').val();
    if (year == "") {
      year = null;
    } else {
      year = ($('#year').val());
    }
    let month = $('#month').val();
    if (month == "") {
      month = null;
    } else {
      month = ($('#month').val());
    }
    let status = btoa('Hold Sample');

    let newUrl = url + '/' + 0 + '/' + null + '/' + customer + '/' + null + '/' + null + '/' + null + '/' + null + '/' + buyer + '/' + status + '/' + division_dropdown + '/' + null + '/' + start_date + '/' + end_date + '/' + agent + '/' + null + '/' + null + '/' + null + '/' + null + '/' + year + '/' + month;
    window.open(newUrl, '_blank');
    // window.location.href = url + '/' + 0 + '/' + null + '/' + customer + '/' + null + '/' + null + '/' + null + '/' + null + '/' + buyer + '/' + status + '/' + division_dropdown + '/' + null + '/' + start_date + '/' + end_date + '/' + agent;
  })
  $('.viewtotalHoldSample').on('click', function() {
    let url = '<?= base_url('SampleRegistration_Controller/sample_register_listing') ?>';

    let start_date = $('#start_date').val();
    if (start_date == "") {
      start_date = null;
    } else {
      start_date = btoa($('#start_date').val());
    }
    let end_date = $('#end_date').val();
    if (end_date == "") {
      end_date = null;
    } else {
      end_date = btoa($('#end_date').val());
    }

    let buyer = $('#buyer').val();
    if (buyer == "") {
      buyer = null;
    } else {
      buyer = ($('#buyer').val());
    }
    let agent = $('#agent').val();
    if (agent == "") {
      agent = null;
    } else {
      agent = ($('#agent').val());
    }
    let labType = $('#labType').val();
    if (labType == "") {
      labType = null;
    } else {
      labType = ($('#labType').val());
    }
    let customer = $('#customer').val();
    if (customer == "") {
      customer = null;
    } else {
      customer = ($('#customer').val());
    }
    let division_dropdown = $('.division_dropdown').val();
    if (division_dropdown == "") {
      division_dropdown = null;
    } else {
      division_dropdown = ($('.division_dropdown').val());
    }

      // new
    let year = $('#year').val();
    if (year == "") {
      year = null;
    } else {
      year = ($('#year').val());
    }
    let month = $('#month').val();
    if (month == "") {
      month = null;
    } else {
      month = ($('#month').val());
    }
    let status = btoa('Hold Sample');

    let newUrl = url + '/' + 0 + '/' + null + '/' + customer + '/' + null + '/' + null + '/' + null + '/' + null + '/' + buyer + '/' + status + '/' + division_dropdown + '/' + null + '/' + start_date + '/' + end_date + '/' + agent + '/' + null + '/' + null + '/' + null + '/' + null + '/' + year + '/' + month;
    window.open(newUrl, '_blank');
    // window.location.href = url + '/' + 0 + '/' + null + '/' + customer + '/' + null + '/' + null + '/' + null + '/' + null + '/' + buyer + '/' + status + '/' + division_dropdown + '/' + null + '/' + start_date + '/' + end_date + '/' + agent;
  })
  $('.viewtotalCancelled').on('click', function() {
    let url = '<?= base_url('SampleRegistration_Controller/sample_register_listing') ?>';

    let start_date = $('#start_date').val();
    if (start_date == "") {
      start_date = null;
    } else {
      start_date = btoa($('#start_date').val());
    }
    let end_date = $('#end_date').val();
    if (end_date == "") {
      end_date = null;
    } else {
      end_date = btoa($('#end_date').val());
    }

    let buyer = $('#buyer').val();
    if (buyer == "") {
      buyer = null;
    } else {
      buyer = ($('#buyer').val());
    }
    let agent = $('#agent').val();
    if (agent == "") {
      agent = null;
    } else {
      agent = ($('#agent').val());
    }
    let labType = $('#labType').val();
    if (labType == "") {
      labType = null;
    } else {
      labType = ($('#labType').val());
    }
    let customer = $('#customer').val();
    if (customer == "") {
      customer = null;
    } else {
      customer = ($('#customer').val());
    }
    let division_dropdown = $('.division_dropdown').val();
    if (division_dropdown == "") {
      division_dropdown = null;
    } else {
      division_dropdown = ($('.division_dropdown').val());
    }

      // new
    let year = $('#year').val();
    if (year == "") {
      year = null;
    } else {
      year = ($('#year').val());
    }
    let month = $('#month').val();
    if (month == "") {
      month = null;
    } else {
      month = ($('#month').val());
    }
    let status = btoa('Login Cancelled');

    let newUrl = url + '/' + 0 + '/' + null + '/' + customer + '/' + null + '/' + null + '/' + null + '/' + null + '/' + buyer + '/' + status + '/' + division_dropdown + '/' + null + '/' + start_date + '/' + end_date + '/' + agent + '/' + null + '/' + null + '/' + null + '/' + null + '/' + year + '/' + month;
    window.open(newUrl, '_blank');
    // window.location.href = url + '/' + 0 + '/' + null + '/' + customer + '/' + null + '/' + null + '/' + null + '/' + null + '/' + buyer + '/' + status + '/' + division_dropdown + '/' + null + '/' + start_date + '/' + end_date + '/' + agent;
  })
  //  new
  $('.viewtotalReport').on('click', function() {
    let url = '<?= base_url('SampleRegistration_Controller/sample_register_listing') ?>';

    let start_date = $('#start_date').val();
    if (start_date == "") {
      start_date = null;
    } else {
      start_date = btoa($('#start_date').val());
    }
    let end_date = $('#end_date').val();
    if (end_date == "") {
      end_date = null;
    } else {
      end_date = btoa($('#end_date').val());
    }

    let buyer = $('#buyer').val();
    if (buyer == "") {
      buyer = null;
    } else {
      buyer = ($('#buyer').val());
    }
    let agent = $('#agent').val();
    if (agent == "") {
      agent = null;
    } else {
      agent = ($('#agent').val());
    }
    let labType = $('#labType').val();
    if (labType == "") {
      labType = null;
    } else {
      labType = ($('#labType').val());
    }
    let customer = $('#customer').val();
    if (customer == "") {
      customer = null;
    } else {
      customer = ($('#customer').val());
    }
    let division_dropdown = $('.division_dropdown').val();
    if (division_dropdown == "") {
      division_dropdown = null;
    } else {
      division_dropdown = ($('.division_dropdown').val());
    }
      // new
    let year = $('#year').val();
    if (year == "") {
      year = null;
    } else {
      year = ($('#year').val());
    }
    let month = $('#month').val();
    if (month == "") {
      month = null;
    } else {
      month = ($('#month').val());
    }
    // let status = 'Report Generated';
    let status = btoa('Report Generated');
    var today = new Date();
    var formattedDate = today.getFullYear() + '-' +
      String(today.getMonth() + 1).padStart(2, '0') + '-' +
      String(today.getDate()).padStart(2, '0');
    let startdue = btoa(formattedDate);

    let newUrl = url + '/' + 0 + '/' + null + '/' + customer + '/' + null + '/' + null + '/' + null + '/' + null + '/' + buyer + '/' + status + '/' + division_dropdown + '/' + null + '/' + start_date + '/' + end_date + '/' + agent + '/' + null + '/' + startdue + '/' + null + '/' + null + '/' + year + '/' + month;
    window.open(newUrl, '_blank');
    // window.location.href = url + '/' + 0 + '/' + null + '/' + customer + '/' + null + '/' + null + '/' + null + '/' + null + '/' + buyer + '/' + status + '/' + division_dropdown + '/' + null + '/' + start_date + '/' + end_date + '/' + agent;
  })
  $('.viewtotalOverDue').on('click', function() {
    let url = '<?= base_url('SampleRegistration_Controller/sample_register_listing') ?>';

    let start_date = $('#start_date').val();
    if (start_date == "") {
      start_date = null;
    } else {
      start_date = btoa($('#start_date').val());
    }
    let end_date = $('#end_date').val();
    if (end_date == "") {
      end_date = null;
    } else {
      end_date = btoa($('#end_date').val());
    }

    let buyer = $('#buyer').val();
    if (buyer == "") {
      buyer = null;
    } else {
      buyer = ($('#buyer').val());
    }
    let agent = $('#agent').val();
    if (agent == "") {
      agent = null;
    } else {
      agent = ($('#agent').val());
    }
    let labType = $('#labType').val();
    if (labType == "") {
      labType = null;
    } else {
      labType = ($('#labType').val());
    }
    let customer = $('#customer').val();
    if (customer == "") {
      customer = null;
    } else {
      customer = ($('#customer').val());
    }
    let division_dropdown = $('.division_dropdown').val();
    if (division_dropdown == "") {
      division_dropdown = null;
    } else {
      division_dropdown = ($('.division_dropdown').val());
    }
       // new
    let year = $('#year').val();
    if (year == "") {
      year = null;
    } else {
      year = ($('#year').val());
    }
    let month = $('#month').val();
    if (month == "") {
      month = null;
    } else {
      month = ($('#month').val());
    }
    let status = null;
    // let status = btoa('Login Cancelled');
    var today = new Date();
    var formattedDate = today.getFullYear() + '-' +
      String(today.getMonth() + 1).padStart(2, '0') + '-' +
      String(today.getDate()).padStart(2, '0');
    let startdue = btoa(formattedDate);

    let newUrl = url + '/' + 0 + '/' + null + '/' + customer + '/' + null + '/' + null + '/' + null + '/' + null + '/' + buyer + '/' + status + '/' + division_dropdown + '/' + null + '/' + start_date + '/' + end_date + '/' + agent + '/' + null + '/' + startdue + '/' + null + '/' + null + '/' + year + '/' + month;
    window.open(newUrl, '_blank');
    // window.location.href = url + '/' + 0 + '/' + null + '/' + customer + '/' + null + '/' + null + '/' + null + '/' + null + '/' + buyer + '/' + status + '/' + division_dropdown + '/' + null + '/' + start_date + '/' + end_date + '/' + agent;
  })
  $('.viewtodayOverDue').on('click', function() {
    let url = '<?= base_url('SampleRegistration_Controller/sample_register_listing') ?>';

    var today = new Date();
    var formattedDate = today.getFullYear() + '-' +
      String(today.getMonth() + 1).padStart(2, '0') + '-' +
      String(today.getDate()).padStart(2, '0');
    let start_date = btoa(formattedDate);

    let end_date = btoa(formattedDate);

    let buyer = $('#buyer').val();
    if (buyer == "") {
      buyer = null;
    } else {
      buyer = ($('#buyer').val());
    }
    let agent = $('#agent').val();
    if (agent == "") {
      agent = null;
    } else {
      agent = ($('#agent').val());
    }
    let labType = $('#labType').val();
    if (labType == "") {
      labType = null;
    } else {
      labType = ($('#labType').val());
    }
    let customer = $('#customer').val();
    if (customer == "") {
      customer = null;
    } else {
      customer = ($('#customer').val());
    }
    let division_dropdown = $('.division_dropdown').val();
    if (division_dropdown == "") {
      division_dropdown = null;
    } else {
      division_dropdown = ($('.division_dropdown').val());
    }
       // new
    let year = $('#year').val();
    if (year == "") {
      year = null;
    } else {
      year = ($('#year').val());
    }
    let month = $('#month').val();
    if (month == "") {
      month = null;
    } else {
      month = ($('#month').val());
    }
    let status = null;
    // let status = btoa('Login Cancelled');
    var today = new Date();
    var formattedDate = today.getFullYear() + '-' +
      String(today.getMonth() + 1).padStart(2, '0') + '-' +
      String(today.getDate()).padStart(2, '0');
    let enddue = btoa(formattedDate);

    let newUrl = url + '/' + 0 + '/' + null + '/' + customer + '/' + null + '/' + null + '/' + null + '/' + null + '/' + buyer + '/' + status + '/' + division_dropdown + '/' + null + '/' + null + '/' + null + '/' + agent + '/' + null + '/' + null + '/' + enddue + '/' + null + '/' + year + '/' + month;
    window.open(newUrl, '_blank');
    // window.location.href = url + '/' + 0 + '/' + null + '/' + customer + '/' + null + '/' + null + '/' + null + '/' + null + '/' + buyer + '/' + status + '/' + division_dropdown + '/' + null + '/' + start_date + '/' + end_date + '/' + agent;
  })


  $(document).ready(function() {
    if ($('#todayHoldSample').html() == 0) {
      $('.viewtodayHoldSample').css('display', 'none');
    }
    if ($('#todayRegisteredSample').html() == 0) {
      $('.viewtodayRegisteredSample').css('display', 'none');
    }
    if ($('#totalRegisteredSample').html() == 0) {
      $('.viewtotalRegisteredSample').css('display', 'none');
    }
    if ($('#totalHoldSample').html() == 0) {
      $('.viewtotalHoldSample').css('display', 'none');
    }
    if ($('#totalCancelled').html() == 0) {
      $('.viewtotalCancelled').css('display', 'none');
    }
    if ($('#todayOverDue').html() == 0) {
      $('.viewtodayOverDue').css('display', 'none');
    }
    if ($('#totalOverDue').html() == 0) {
      $('.viewtotalOverDue').css('display', 'none');
    }
    // new
    if ($('#totalReport').html() == 0) {
      $('.viewtotalReport').css('display', 'none');
    }
  })
  $('#reset').on('click', function() {
    window.location.reload();
  });
  $('#filter').on('click', function() {
    const _tokken = $('meta[name="_tokken"]').attr('value');
    let start_date = $('#start_date').val();
    let end_date = $('#end_date').val();
    let buyer = $('#buyer option:selected').val();
    let agent = $('#agent option:selected').val();
    let labType = $('#labType option:selected').val();
    let division_dropdown = $('.division_dropdown option:selected').val();
    let year = $('#year option:selected').val(); // new
    let month = $('#month option:selected').val(); //new
    let customer = $('#customer option:selected').val();
// new
    if(year != '' && month == ''){
      $('#error_message').text('Month cannot be blank while choosing year. Please select month !!!');
        setTimeout(function() {
          $('#error_message').fadeOut();
        }, 5000);
        e.preventDefault(); 
    }
    if(year == '' && month != ''){
      $('#error_message').text('Year cannot be blank while choosing month. Please select year !!!');
        setTimeout(function() {
          $('#error_message').fadeOut();
        }, 5000);
        e.preventDefault(); 
    }
    // end
    if (start_date !== '' && end_date !== '') {
      var fromDate = new Date(start_date);
      var toDate = new Date(end_date);
      if (fromDate > toDate) {
        $('#error_message').text('From Date cannot be greater than To Date.');
        setTimeout(function() {
          $('#error_message').fadeOut();
        }, 3000);
        e.preventDefault(); // Prevent form submission or further action
      }
    }
    $('body').append('<div class="pageloader"></div>');
    $.ajax({
      type: 'post',
      url: "<?php echo base_url('Dashboard/getDashboardFilteredData'); ?>",
      data: {
        _tokken: _tokken,
        start_date: start_date,
        end_date: end_date,
        buyer: buyer,
        agent: agent,
        labType: labType,
        division_dropdown: division_dropdown,
        customer: customer,
        sampleyear: year, // new
        month: month, // new
      },
      success: function(result) {
        $('.pageloader').remove();
        var ddata = $.parseJSON(result);
        if (ddata.status == 1) {
          console.log(ddata.data);

          $('#todayHoldSample').html(ddata.data.todayHoldSample);
          $('#todayOverDue').html(ddata.data.todayOverDue);
          $('#todayRegisteredSample').html(ddata.data.todayRegisteredSample);
          $('#totalCancelled ').html(ddata.data.totalCancelled);
          $('#totalHoldSample').html(ddata.data.totalHoldSample);
          $('#totalOverDue').html(ddata.data.totalOverDue);
          $('#totalRegisteredSample').html(ddata.data.totalRegisteredSample);
          $('#totalReport').html(ddata.data.totalReport); // new

          // new
          if (ddata.data.totalReport == 0) {
            $('.viewtotalReport').css('display', 'none');
          } else {
            $('.viewtotalReport').css('display', 'block');
          }

          if (ddata.data.todayHoldSample == 0) {
            $('.viewtodayHoldSample').css('display', 'none');
          } else {
            $('.viewtodayHoldSample').css('display', 'block');
          }

          if (ddata.data.todayRegisteredSample == 0) {
            $('.viewtodayRegisteredSample').css('display', 'none');
          } else {
            $('.viewtodayRegisteredSample').css('display', 'block');
          }

          if (ddata.data.totalRegisteredSample == 0) {
            $('.viewtotalRegisteredSample').css('display', 'none');
          } else {
            $('.viewtotalRegisteredSample').css('display', 'block');
          }

          if (ddata.data.totalHoldSample == 0) {
            $('.viewtotalHoldSample').css('display', 'none');
          } else {
            $('.viewtotalHoldSample').css('display', 'block');
          }

          if (ddata.data.totalCancelled == 0) {
            $('.viewtotalCancelled').css('display', 'none');
          } else {
            $('.viewtotalCancelled').css('display', 'block');
          }
          if (ddata.data.todayOverDue == 0) {
            $('.viewtodayOverDue').css('display', 'none');
          } else {
            $('.viewtodayOverDue').css('display', 'block');
          }

          if (ddata.data.totalOverDue == 0) {
            $('.viewtotalOverDue').css('display', 'none');
          } else {
            $('.viewtotalOverDue').css('display', 'block');
          }
          get_quote_chart();
          get_sample_chart();
          get_report_chart();
          get_backlogs_by_division(null, 5, 0, null);
          get_not_upload_invoice(null, 5, 0, null, null, null);
        }
      }
    })
  })

  window.onload = function() {
    get_quote_chart();
    get_sample_chart();
    get_report_chart();
  };
  $(document).ready(function() {
    $('#customer').select2();
    $('#buyer').select2();
    $('.division_dropdown').select2();
    $('#agent').select2();
  })

  function get_quote_chart(reset = false) {
    if (reset == true) {
      var period = [];
      $('#start_date').val("");
      $('#end_date').val("");
      $('#year').val("");
      $('#month').val("");
      // $('#labType').val("");
      // $('.division_dropdown').val("");
      $('#customer').val("");
    } else {
      var period = [];
      period[0] = $('#start_date').val();
      period[1] = $('#end_date').val();
      // period[2] = $('#buyer').val();
      // period[3] = $('#agent').val();
      // period[4] = $('#labType').val();
      // period[5] = $('.division_dropdown').val();
      period[2] = $('#customer').val();
      period[3] = $('#year').val();
      period[4] = $('#month').val();
    }



    $.ajax({
      datatype: "json",

      url: "<?php echo base_url(); ?>Dashboard/get_trfQuotesChart",
      data: {
        quote_chart_filter: period
      },

      success: function(result) {

        var ddata = $.parseJSON(result);

        quote_x_val = ddata.map(function(val, i) {
          return val.xval;
        });

        quote_y_val = ddata.map(function(val, i) {
          return parseInt(val.yval);
        });

        Highcharts.chart('container', {
          chart: {
            type: 'column'
          },
          title: {
            text: 'Quote Status'
          },
          xAxis: {
            categories: quote_x_val
          },
          yAxis: {
            title: {
              text: 'Total Records'
            }
          },

          legend: {
            reversed: true
          },
          plotOptions: {
            series: {
              stacking: 'normal'
            }
          },
          series: [{
            name: 'Quote Status',
            data: quote_y_val
          }]
        });
      }

    });
  }


  function get_sample_chart(reset = false) {
    if (reset) {
      var sample = [];
      $('#start_date').val("");
      $('#end_date').val("");
      $('#buyer').val("");
      $('#agent').val("");
      $('#labType').val("");
      $('.division_dropdown').val("");
      $('#customer').val("");
      $('#year').val("");
      $('#month').val("");
    } else {
      var sample = [];
      sample[0] = $('#start_date').val();
      sample[1] = $('#end_date').val();
      sample[2] = $('#buyer').val();
      sample[3] = $('#agent').val();
      sample[4] = $('#labType').val();
      sample[5] = $('.division_dropdown').val();
      sample[6] = $('#customer').val();
      sample[7] = $('#year').val();
      sample[8] = $('#month').val();
    }
    console.log(sample);


    $.ajax({
      datatype: "json",

      url: "<?php echo base_url('Dashboard/get_sampleStatusChart'); ?>",
      data: {
        sample_chart_filter: sample
      },

      success: function(result) {

        var ddata = $.parseJSON(result);
        console.log(ddata);


        quote_x_val = ddata.map(function(val, i) {
          return val.xval;
        });

        quote_y_val = ddata.map(function(val, i) {
          return parseInt(val.yval);
        });
        Highcharts.chart('sample_status_div', {
          chart: {
            type: 'column'
          },
          title: {
            text: 'Sample Status'
          },
          xAxis: {
            categories: quote_x_val
          },
          yAxis: {
            title: {
              text: 'Total Records'
            }
          },

          legend: {
            reversed: true
          },
          plotOptions: {
            series: {
              stacking: 'normal'
            }
          },
          series: [{
            name: 'Sample Status',
            data: quote_y_val
          }]
        });
      }

    });
  }


  function get_report_chart(reset = false) {
    if (reset) {
      var report = [];
      $('#start_date').val("");
      $('#end_date').val("");
      $('#buyer').val("");
      $('#agent').val("");
      $('#labType').val("");
      $('.division_dropdown').val("");
      $('#customer').val("");
      $('#year').val("");
      $('#month').val("");
    } else {
      var report = [];
      report[0] = $('#start_date').val();
      report[1] = $('#end_date').val();
      report[2] = $('#buyer').val();
      report[3] = $('#agent').val();
      report[4] = $('#labType').val();
      report[5] = $('.division_dropdown').val();
      report[6] = $('#customer').val();
      report[7] = $('#year').val();
      report[8] = $('#month').val();
    }
    console.log(report);

    $.ajax({
      datatype: "json",

      url: "<?php echo base_url('Dashboard/get_reportChart'); ?>",
      data: {
        report_chart_filter: report
      },

      success: function(result) {

        var ddata = $.parseJSON(result);


        quote_x_val = ddata.map(function(val, i) {
          return val.xval;
        });

        quote_y_val = ddata.map(function(val, i) {
          return parseInt(val.yval);
        });
        Highcharts.chart('report_status_div', {
          chart: {
            type: 'column'
          },
          title: {
            text: 'Report Status'
          },
          xAxis: {
            categories: quote_x_val
          },
          yAxis: {
            title: {
              text: 'Total Records'
            }
          },

          legend: {
            reversed: true
          },
          plotOptions: {
            series: {
              stacking: 'normal'
            }
          },
          series: [{
            name: 'Report Status',
            data: quote_y_val
          }]
        });
      }

    });
  }

  $('#tags2').keyup(function() {
    var query = $(this).val();
    var csrfHash = '<?php echo $this->security->get_csrf_hash(); ?>';
    if (query !== '' && query.length >= 2) {
      $.ajax({
        url: "<?php echo base_url('Dashboard/get_customer_autosuggest') ?>",
        method: "GET",
        data: {
          "csrf_test_name": csrfHash,
          query: query
        },
        dataType: "json",
        success: function(data) {
          customer_name_arr = [];
          customer_id_arr = [];
          $.each(data, function(i, customer) {
            customer_id_arr[customer.customer_name] = customer.customer_id;
            customer_name_arr[i] = customer.customer_name;

          });

          $("#tags2").autocomplete({
            source: customer_name_arr,
            select: function(event, ui) {

              $('#quote_customer_id').val(customer_id_arr[ui.item.value]);
            }
          });

          $("#tags2").autocomplete("widget").addClass('fixed-height');

        }
      });
    }
  });

  //sample-->

  $('#category_name').keyup(function() {
    var query = $(this).val();
    var csrfHash = '<?php echo $this->security->get_csrf_hash(); ?>';
    if (query !== '' && query.length >= 2) {
      $.ajax({
        url: "<?php echo base_url('Dashboard/get_category_autosuggest') ?>",
        method: "GET",
        data: {
          "csrf_test_name": csrfHash,
          query: query
        },
        dataType: "json",
        success: function(data) {
          category_name_arr = [];
          category_id_arr = [];
          $.each(data, function(i, category) {
            category_id_arr[category.category_name] = category.category_id;
            category_name_arr[i] = category.category_name;

          });

          $("#category_name").autocomplete({
            source: category_name_arr,
            select: function(event, ui) {
              $('#sample_category_id').val(category_id_arr[ui.item.value]);
            }
          });

          $("#category_name").autocomplete("widget").addClass('fixed-height');

        }
      });
    }
  });

  $('#sample_status_customer_name').keyup(function() {
    var query = $(this).val();
    var csrfHash = '<?php echo $this->security->get_csrf_hash(); ?>';
    if (query !== '' && query.length >= 2) {
      $.ajax({
        url: "<?php echo base_url('Dashboard/get_customer_autosuggest') ?>",
        method: "GET",
        data: {
          "csrf_test_name": csrfHash,
          query: query
        },
        dataType: "json",
        success: function(data) {
          customer_name_arr = [];
          customer_id_arr = [];
          $.each(data, function(i, customer) {
            customer_id_arr[customer.customer_name] = customer.customer_id;
            customer_name_arr[i] = customer.customer_name;

          });

          $("#sample_status_customer_name").autocomplete({
            source: customer_name_arr,
            select: function(event, ui) {

              $('#sample_status_customer_id').val(customer_id_arr[ui.item.value]);
            }
          });

          $("#sample_status_customer_name").autocomplete("widget").addClass('fixed-height');

        }
      });
    }
  });

  $('#sample_name').keyup(function() {
    var query = $(this).val();
    var csrfHash = '<?php echo $this->security->get_csrf_hash(); ?>';
    if (query !== '' && query.length >= 2) {
      $.ajax({
        url: "<?php echo base_url('Dashboard/get_sample_autosuggest') ?>",
        method: "GET",
        data: {
          "csrf_test_name": csrfHash,
          query: query
        },
        dataType: "json",
        success: function(data) {
          customer_name_arr = [];
          customer_id_arr = [];
          $.each(data, function(i, sample) {
            customer_id_arr[sample.sample_type_name] = sample.sample_type_id;
            customer_name_arr[i] = sample.sample_type_name;

          });

          $("#sample_name").autocomplete({
            source: customer_name_arr,
            select: function(event, ui) {

              $('#sample_id').val(customer_id_arr[ui.item.value]);
            }
          });

          $("#sample_name").autocomplete("widget").addClass('fixed-height');

        }
      });
    }
  });

  //reports

  $('#report_category_name').keyup(function() {
    var query = $(this).val();
    var csrfHash = '<?php echo $this->security->get_csrf_hash(); ?>';
    if (query !== '' && query.length >= 2) {
      $.ajax({
        url: "<?php echo base_url('Dashboard/get_category_autosuggest') ?>",
        method: "GET",
        data: {
          "csrf_test_name": csrfHash,
          query: query
        },
        dataType: "json",
        success: function(data) {
          category_name_arr = [];
          category_id_arr = [];
          $.each(data, function(i, category) {
            category_id_arr[category.category_name] = category.category_id;
            category_name_arr[i] = category.category_name;

          });

          $("#report_category_name").autocomplete({
            source: category_name_arr,
            select: function(event, ui) {
              $('#report_category_id').val(category_id_arr[ui.item.value]);
            }
          });

          $("#report_category_name").autocomplete("widget").addClass('fixed-height');

        }
      });
    }
  });

  $('#report_customer_name').keyup(function() {
    var query = $(this).val();
    var csrfHash = '<?php echo $this->security->get_csrf_hash(); ?>';
    if (query !== '' && query.length >= 2) {
      $.ajax({
        url: "<?php echo base_url('Dashboard/get_customer_autosuggest') ?>",
        method: "GET",
        data: {
          "csrf_test_name": csrfHash,
          query: query
        },
        dataType: "json",
        success: function(data) {
          customer_name_arr = [];
          customer_id_arr = [];
          $.each(data, function(i, customer) {
            customer_id_arr[customer.customer_name] = customer.customer_id;
            customer_name_arr[i] = customer.customer_name;

          });

          $("#report_customer_name").autocomplete({
            source: customer_name_arr,
            select: function(event, ui) {

              $('#report_customer_id').val(customer_id_arr[ui.item.value]);
            }
          });

          $("#report_customer_name").autocomplete("widget").addClass('fixed-height');

        }
      });
    }
  });

  $('#report_sample_name').keyup(function() {
    var query = $(this).val();
    var csrfHash = '<?php echo $this->security->get_csrf_hash(); ?>';
    if (query !== '' && query.length >= 2) {
      $.ajax({
        url: "<?php echo base_url('Dashboard/get_sample_autosuggest') ?>",
        method: "GET",
        data: {
          "csrf_test_name": csrfHash,
          query: query
        },
        dataType: "json",
        success: function(data) {
          customer_name_arr = [];
          customer_id_arr = [];
          $.each(data, function(i, sample) {
            customer_id_arr[sample.sample_type_name] = sample.sample_type_id;
            customer_name_arr[i] = sample.sample_type_name;

          });

          $("#report_sample_name").autocomplete({
            source: customer_name_arr,
            select: function(event, ui) {

              $('#report_sample_id').val(customer_id_arr[ui.item.value]);
            }
          });

          $("#report_sample_name").autocomplete("widget").addClass('fixed-height');

        }
      });
    }
  });
</script>

<div class="modal fade bd-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" id="follow_up_modal">
  <div class="modal-dialog modal-sm">
    <div class="modal-content" style="width: 500px;margin:0 auto;border:2px solid yellow">
      <div class="modal-body">
        <div class="row">
          <div class="col-sm-9">
            Dear <?php $checkUser = $this->session->userdata('user_data');
                  echo  $checkUser->username; ?> ! <br> Today you have <b><span class="count" style="display: inline-block;"></span></b> follow up!
          </div>

          <div class="col-sm-3">
            <i class="fas fa-comment-dots" style="font-size: 50px;"></i>
          </div>
        </div>
        <hr>
        <div class="row">
          <div class="col-sm-12">
            <a href="<?php echo base_url('To_do_list') ?>" class="btn btn-warning btn-sm">Check!</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<script>
  // Added by CHANDAN --- 27-09-2021 ----
  function formatRepo(repo) {
    if (repo.loading) {
      return repo.text;
    }
    var container = $(
      "<div class='select2-result-repository clearfix'>" +
      "<div class='select2-result-repository__title'></div>" +
      "</div>"
    );

    container.find(".select2-result-repository__title").text(repo.name);
    return container;
  }

  function formatRepoSelection(repo) {
    return repo.full_name || repo.text;
  }

  function get_cust_chart(reset = false) {
    let period = [];
    if (reset == true) {
      $('#cust_period').val("");
      $('#srch_admin_user').val('').trigger("change");
      // $('#srch_admin_user').val("");
      $('#cust_start_date').val("");
      $('#cust_end_date').val("");
    } else {
      var user = '';
      if ($('#srch_admin_user').val() == '') {
        user = 'NULL';
      } else {
        user = $('#srch_admin_user').val();
      }
      period[0] = $('#cust_period').val();
      period[1] = user;
      period[2] = $('#cust_start_date').val();
      period[3] = $('#cust_end_date').val();
    }
    // console.log(period);
    // return false;
    $.ajax({
      url: "<?php echo base_url('Dashboard/get_customersChart'); ?>",
      data: {
        cust_chart_filter: period
      },
      dataType: 'json',
      success: function(data) {

        let chart = Highcharts.chart('cust_container', {
          chart: {
            type: 'column'
          },
          title: {
            text: 'Total Customer Status'
          },
          colors: ['#33E5FF', '#33FF95', '#FF5B33'],
          xAxis: {
            categories: data.map(function(val, i) {
              return val.xval;
            }),
            labels: {
              x: -10
            }
          },

          yAxis: {
            min: 0,
            allowDecimals: false,
            title: {
              text: 'Total Customers'
            }
          },

          series: [{
            name: 'Total',
            data: data.map(function(val, i) {
              return parseInt(val.yval);
            })
          }, {
            name: 'Active',
            data: data.map(function(val, i) {
              return parseInt(val.Active);
            })
          }, {
            name: 'In-Active',
            data: data.map(function(val, i) {
              return parseInt(val.Inactive);
            })
          }]
        });
        // let set_title = $('#srch_admin_user').select2('data');
        // chart.setTitle({
        //   text: (set_title[0].full_name == undefined || set_title[0].full_name == null) ? 'Total Customer Status' : set_title[0].full_name + "'s Customer Status"
        // });
      }
    });
  }

  function numVal(value) {
    if (isNaN(value)) {
      return 0;
    } else {
      return (value) ? parseFloat(value) : 0;
    }
  }

  function get_opportunity_chart(reset = false) {
    let period = [];
    if (reset == true) {
      $('#opportunity_period').val("");
      $('#srch_opportunity_admin_user').val('').trigger("change");
      // $('#srch_opportunity_admin_user').val("");
      $('#srch_opportunity_cust_user').val('').trigger("change");
      // $('#srch_opportunity_cust_user').val("");
      $('#opportunity_date_type').val("");
      $('#opportunity_start_date').val("");
      $('#opportunity_end_date').val("");
      $('#opportunity_opportunity_status').val("");
    } else {
      var user = '';
      var customer = '';
      if ($('#srch_opportunity_admin_user').val() == '') {
        user = 'NULL';
      } else {
        user = $('#srch_opportunity_admin_user').val();
      }
      if ($('#srch_opportunity_cust_user').val() == '') {
        customer = 'NULL';
      } else {
        customer = $('#srch_opportunity_cust_user').val();
      }
      period[0] = $('#opportunity_period').val();
      period[1] = user;
      period[2] = customer;
      period[3] = $('#opportunity_date_type').val();
      period[4] = $('#opportunity_start_date').val();
      period[5] = $('#opportunity_end_date').val();
      period[6] = $('#opportunity_opportunity_status').val();
    }
    // console.log(period);
    // return false;
    $.ajax({
      url: "<?php echo base_url('Dashboard/get_opportunityChart'); ?>",
      data: {
        opportunity_chart_filter: period
      },
      dataType: 'json',
      success: function(data1) {

        quote_x_val = data1.map(function(val, i) {
          return val.months + '-' + val.years;
        });

        quote_y_val = data1.map(function(val, i) {
          return parseFloat(val.opportunity_value);
        });

        let chart2 = Highcharts.chart('opportunity_container', {
          chart: {
            type: 'column'
          },
          title: {
            text: 'Opportunity'
          },
          xAxis: {
            categories: quote_x_val
          },
          yAxis: {
            title: {
              text: 'Total Opportunity'
            }
          },

          legend: {
            reversed: true
          },
          plotOptions: {
            series: {
              stacking: 'normal'
            }
          },
          series: [{
            name: 'Opportunity',
            data: quote_y_val
          }]
        });
        // let set_title = $('#srch_opportunity_admin_user').select2('data');
        // if (set_title.length > 0) {
        //   chart2.setTitle({
        //     text: (set_title[0].full_name == undefined || set_title[0].full_name == null) ? 'Total Opportunity' : set_title[0].full_name + "'s Opportunity"
        //   });
        // }
      }
    });
  }

  function get_performa_chart(reset = false) {
    let period = [];
    if (reset == true) {
      $('#performa_period').val("");
      // $('#srch_performa_admin_user').val("");
      $('#srch_performa_admin_user').val('').trigger("change");
      $('#srch_performa_cust_user').val('').trigger("change");
      // $('#srch_performa_cust_user').val("");
      $('#performa_start_date').val("");
      $('#performa_end_date').val("");
      $('#performa_performa_status').val("");
    } else {
      var user = '';
      var customer = '';
      if ($('#srch_performa_admin_user').val() == '') {
        user = 'NULL';
      } else {
        user = $('#srch_performa_admin_user').val();
      }
      if ($('#srch_performa_cust_user').val() == '') {
        customer = 'NULL';
      } else {
        customer = $('#srch_performa_cust_user').val();
      }
      period[0] = $('#performa_period').val();
      period[1] = user;
      period[2] = customer;
      period[3] = $('#performa_start_date').val();
      period[4] = $('#performa_end_date').val();
      period[5] = $('#performa_performa_status').val();
    }

    $.ajax({
      url: "<?php echo base_url('Dashboard/get_performaChart'); ?>",
      data: {
        performa_chart_filter: period
      },
      dataType: 'json',
      success: function(data2) {

        quote_x_val = data2.map(function(val, i) {
          return val.months + '-' + val.years;
        });

        quote_y_val = data2.map(function(val, i) {
          return parseFloat(val.total_amount);
        });

        let chart2 = Highcharts.chart('performa_container', {
          chart: {
            type: 'column'
          },
          title: {
            text: 'Revenue'
          },
          xAxis: {
            categories: quote_x_val
          },
          yAxis: {
            title: {
              text: 'Total Revenue'
            }
          },

          legend: {
            reversed: true
          },
          plotOptions: {
            series: {
              stacking: 'normal'
            }
          },
          series: [{
            name: 'Revenue',
            data: quote_y_val
          }]
        });
        // let set_title = $('#srch_performa_admin_user').select2('data');
        // if (set_title.length > 0) {
        //   chart2.setTitle({
        //     text: (set_title[0].full_name == undefined || set_title[0].full_name == null) ? 'Total Revenue' : set_title[0].full_name + "'s Revenue"
        //   });
        // }
      }
    });
  }
  // End -----------

  $(document).ready(function() {
    $('.toast').toast('show');
    $.get({
      url: "<?php echo base_url('Dashboard/get_to_list_count') ?>",
      dataType: "json",
      success: function(res) {
        if (res) {
          if (res > 0) {
            $('.count').html(res);
            $('#follow_up_modal').modal('show');
          }
        }
      }
    });

    // Added by CHANDAN --- 27-09-2021 ----

    get_cust_chart();

    get_opportunity_chart();

    get_performa_chart();

    $('#srch_admin_user, #srch_opportunity_admin_user, #srch_performa_admin_user').select2({
      allowClear: true,
      multiple: true,
      ajax: {
        url: "<?php echo base_url('Dashboard/get_admin_user') ?>",
        dataType: 'json',
        data: function(params) {
          return {
            key: params.term, // search term
          };
        },
        processResults: function(response) {
          return {
            results: response
          };
        },
        cache: true
      },
      placeholder: 'Search by user',
      minimumInputLength: 0,
      templateResult: formatRepo,
      templateSelection: formatRepoSelection
    });

    $('#srch_opportunity_cust_user, #srch_performa_cust_user').select2({
      allowClear: true,
      multiple: true,
      ajax: {
        url: "<?php echo base_url('Dashboard/get_admin_customers') ?>",
        dataType: 'json',
        data: function(params) {
          return {
            key: params.term, // search term
          };
        },
        processResults: function(response) {
          return {
            results: response
          };
        },
        cache: true
      },
      placeholder: 'Search by customer',
      minimumInputLength: 0,
      templateResult: formatRepo,
      templateSelection: formatRepoSelection
    });
    // END ----------------

  })

  function get_backlogs_by_division(division_id = '', limit = 5, offset = 0, sample_reg_id = null) {
    const url = $('body').data('url');
    const _tokken = $('meta[name="_tokken"]').attr('value');
    $.ajax({
      url: url + 'Backlogs_details/get_division_wise_backlog',
      method: "post",
      data: {
        _tokken: _tokken,
        division_id: division_id,
        offset: offset,
        sample_reg_id: sample_reg_id,
        limit: limit,
        start_date: $('#start_date').val(),
        end_date: $('#end_date').val(),
        buyer: $('#buyer').val(),
        agent: $('#agent').val(),
        labType: $('#labType').val(),
        division_dropdown: $('.division_dropdown').val(),
        customer: $('#customer').val(),
        year: $('#year').val(),
        month: $('#month').val(),
      },
      success: function(data) {
        var backlogs_data = $.parseJSON(data);

        $('.backlog_data_table tbody').html("");
        if (backlogs_data.data) {

          var sn = offset + 1;
          $.each(backlogs_data.data, function(index1, back) {

            var tr = "<tr>";
            tr += "<td>" + sn + "</td>";
            tr += "<td>" + back.gc_no + "</td>";
            tr += "<td style='width:200px'>" + back.sample_desc + "</td>";
            tr += "<td>" + back.division_name + "</td>";
            tr += "<td>" + back.due_date + "</td>";
            tr += "<td><button type='button' class='btn btn-sm btn-info active_gc' data-id='" + back.sample_reg_id + "' data-toggle='modal' data-target='#view_gc_backlog'>VIEW</button></td>";
            tr += "</tr>";
            $('.backlog_data_table tbody').append(tr);
            sn++;
          });

          if (backlogs_data.count <= (offset + limit)) {
            $('.next_data').attr('disabled', true);
            $('.previous_data ').attr('disabled', false);
          } else {
            $('.next_data').attr('disabled', false);
            $('.previous_data ').attr('disabled', false);
          }
          $('#showing_result_div').css("display", "block");
          $('.start').html("");
          $('.start').html(offset + 1);
          $('.end').html("");
          $('.end').html(offset + limit);
          $('.total').html("")
          $('.total').html(backlogs_data.count);



        } else {
          var tr = "<tr>";
          tr += "<td colspan='5'>NO MORE RECORDS!</td>";
          tr += "</tr>";
          $('.backlog_data_table tbody').append(tr);
          $('#showing_result_div').css("display", "none");
          $('.start').attr('disabled', true);
          $('.end').attr('disabled', true);
        }

      }
    });
    return false;
  }

  function get_not_upload_invoice(div_id_invoice, limit_invoice, offset_invoice, search, cust_id, from, to) {
    const url = $('body').data('url');
    const _tokken = $('meta[name="_tokken"]').attr('value');

    $.ajax({
      url: url + 'Invoice_not_upload/get_not_upload_invoice',
      method: "post",
      data: {
        _tokken: _tokken,
        division_id: div_id_invoice,
        offset: offset_invoice,
        search: search,
        limit: limit_invoice,
        start_date: $('#start_date').val(),
        end_date: $('#end_date').val(),
        buyer: $('#buyer').val(),
        agent: $('#agent').val(),
        labType: $('#labType').val(),
        division_dropdown: $('.division_dropdown').val(),
        customer: $('#customer').val(),
        year: $('#year').val(),
        month: $('#month').val(),
      },
      success: function(data) {
        var invoice_data = $.parseJSON(data);

        $('#invoice_not_upload').html("");
        if (invoice_data.invoice_data) {

          var sn_no = offset_invoice + 1;
          $.each(invoice_data.invoice_data, function(ind, invoice) {

            var tr = "<tr>";
            tr += "<td>" + sn_no + "</td>";
            tr += "<td>" + invoice.gc_no + "</td>";
            tr += "<td>" + invoice.customer_name + "</td>";
            tr += "<td>" + invoice.division_name + "</td>";
            tr += "<td>" + invoice.status + "</td>";
            tr += "<td><button type='button' class='btn btn-sm btn-info active_invoice' data-id='" + invoice.sample_reg_id + "' data-toggle='modal' data-target='#view_invoice' title='View Details'><i class='fas fa-eye'></i></button></td>";
            tr += "</tr>";
            $('#invoice_not_upload').append(tr);
            sn_no++;
          });


          $('.start_invoice').html("");
          $('.start_invoice').html(offset_invoice + 1);
          $('.end_invoice').html("");
          if (invoice_data.count < limit_invoice) {
            $('.end_invoice').html(invoice_data.count);
            $('.next_data_invoice').attr('disabled', true);
            $('.previous_data_invoice').attr('disabled', true);
          } else {
            $('.end_invoice').html(offset_invoice + limit_invoice);
            $('.next_data_invoice').attr('disabled', false);
            $('.previous_data_invoice').attr('disabled', false);
          }

          $('.total_invoice').html("");
          $('.total_invoice').html(invoice_data.count);
          $('#showing_result').css("display", "block");


        } else {
          var tr = "<tr>";
          tr += "<td colspan='5'>NO MORE RECORDS!</td>";
          tr += "</tr>";
          $('#invoice_not_upload').append(tr);
          $('#showing_result').css("display", "none");
          $('.next_data_invoice').attr('disabled', false);
          $('.previous_data_invoice').attr('disabled', false);
        }

      }
    });
    return false;
  }

  document.addEventListener("DOMContentLoaded", function() {
    const today = new Date().toISOString().split("T")[0];
    document.getElementById("end_date").setAttribute("max", today);
  });
  document.addEventListener("DOMContentLoaded", function() {
    const today = new Date().toISOString().split("T")[0];
    document.getElementById("start_date").setAttribute("max", today);
  });

  const months = ['January','February','March','April','May','June','July','August','September','October','November','December'];

  var now = new Date();
  var currentYear = now.getFullYear();
  var currentMonth = now.getMonth() + 1;
  var day = now.getDate();

  $(function () {
    for(let i= 2025;i<= currentYear; i++){
      $('#year').append('<option value="'+i+'" >' +i+'</option>');
    }
    for(let i = 0 ;i < currentMonth; i++){
      $('#month').append('<option value="'+(i + 1)+'" >' +months[i]+'</option>');
    }
  }) 
</script>