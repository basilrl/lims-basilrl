<?php
$checkUser = $this->session->userdata('user_data');
$home = base_url();
$list = base_url('customers');
$case = $this->uri->segment('1');
$op = $this->uri->segment('2');

if ($case == "add_customers" && empty($op)) {

  $title = "ADD NEW CUSTOMER";
  $active_title = 'Add New';
  $action = base_url('add_customers');
  $customer_name = set_value('customer_name');
  $customer_code = set_value('customer_code');

  $customer_type = "";
  $isactive = "";
  $credit = "";
  $regi_type = "";
  $cust_type = set_value('cust_type');

  $email = set_value('email');
  $tan_mendatory_flag = set_value('tan_mendatory_flag');
  $telephone = set_value('telephone');
  $mobile =   set_value('mobile');
  $address =  set_value('address');
  $city = set_value('city');
  $po_box = set_value('po_box');
  $cust_customers_country_id = set_value('cust_customers_country_id');
  $country_name = set_value('country_name');
  $cust_customers_province_id = set_value('cust_customers_province_id');
  $state_name = set_value('state_name');
  $cust_customers_location_id = set_value('cust_customers_location_id');
  $location_name = set_value('location_name');
  $web = set_value('web');
  $active =  set_select('isactive', '1', true);
  $inactive =  set_select('isactive', '0', true);
  $advance = set_select('credit', 'Advance', true);
  $days_30 = set_select('credit', '14 Days', true);
  $days_45 = set_select('credit', '30 Days', true);
  $days_45 = set_select('credit', '60 Days', true);
  $days_45 = set_select('credit', '90 Days', true);
  $pan = set_value('pan');
  $tan = set_value('tan');
  $gstin = set_value('gstin');
  $credit_limit = set_value('credit_limit');
  $retention_period = set_value('retention_period');
  $non_taxable = '';
  $button = "Submit";
  $branch = (set_value('trf_branch') ? set_value('trf_branch') : $checkUser->branch_id);
  // Added by CHANDAN --14-07-2022
  $balance = set_value('balance');
  $Registered = set_select('cust_type', 'Registered', true);
  $Unregistered = set_select('cust_type', 'Unregistered', true);
  $Export = set_select('cust_type', 'Export', true);
  $Deemed_Export = set_select('cust_type', 'Deemed Export', true);
  $Exempted = set_select('cust_type', 'Exempted', true);
  $SEZ_Development = set_select('cust_type', 'SEZ Development', true);
  $SEZ_Unit = set_select('cust_type', 'SEZ Unit', true);
  $SEZ_Unit = set_select('regi_type', 'GSTIN', true);
  $SEZ_Unit = set_select('regi_type', 'UID', true);
  $SEZ_Unit = set_select('regi_type', 'GID', true);
  $vat = set_value('vat');
  $gen = set_value('gen');
  $excise = set_value('excise');
  $cust_post = set_value('cust_post');
  $nav_customer_code='';
} else if ($case == "edit_customers") {

  $title = "UPDATE CUSTOMER";
  $active_title = 'UPDATE';
  $action = base_url('update_customers' . '/' . $op);
  $button = "Update";

  if ($data) {
    $customer_name = $data->customer_name;
    $customer_code = $data->customer_code;
    $tan_mendatory_flag = $data->tan_mendatory_flag;
    $email = $data->email;
    $telephone = $data->telephone;
    $mobile =   $data->mobile;
    $address = $data->address;
    $city = $data->city;
    $po_box = $data->po_box;
    $cust_customers_country_id = $data->cust_customers_country_id;
    $country_name = $data->country_name;
    $cust_customers_province_id = $data->cust_customers_province_id;
    $state_name = $data->state_name;
    $cust_customers_location_id = $data->cust_customers_location_id;
    $location_name = $data->location_name;
    $web = $data->web;
    $customer_type = $data->customer_type;
    $isactive = $data->isactive;
    $credit = $data->credit;
    $credit_limit = $data->credit_limit; //added by kapri on 28-07-2021
    $pan = $data->pan;
    $tan = $data->tan;
    $gstin =  $data->gstin;
    $retention_period = $data->retention_period;
    $non_taxable = $data->non_taxable;
    $nav_customer_code=$data->nav_customer_code;
    $branch = (set_value('trf_branch') ? set_value('trf_branch') :  $data->mst_branch_id);

    // Added by CHANDAN --14-07-2022
    $balance = $data->balance;
    $cust_type = $data->cust_type;
    $vat = $data->vat;
    $gen = $data->gen;
    $excise = $data->excise;
    $cust_post = $data->cust_post;
    $regi_type = $data->regi_type;
  }
} else {
  $customer_type = "";
  $isactive = "";
  $credit = "";
  $non_taxable = "";
  $tan_mendatory_flag = set_value('tan_mendatory_flag');
  $branch = (set_value('trf_branch') ? set_value('trf_branch') : $checkUser->branch_id);
  $regi_type = "";
  $cust_type = set_value('cust_type');;
  $credit_limit = set_value('credit_limit');
  $balance = "";
}
?>
<!--  -->



<style>
  form .error {
    color: #ff0000;
    margin-top: 0;
  }

  .table-responsive {
    overflow-x: hidden;
  }

  .modal-content {
    width: 100%;
    margin: 30px auto;
    /* position:relative; */
  }

  .country_list,
  .state_list {

    width: 94%;
    position: absolute;
    font-size: 12px;
    z-index: 1;
    overflow-y: auto;
    overflow-x: hidden;
    max-height: 150px;
    cursor: pointer;
    padding: 0;
    margin: 0 auto;

  }
</style>


<div class="content-wrapper">
  <!-- Content Header (Page header) -->

  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>CUSTOMER</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="<?php echo $home; ?>">Home</a></li>
            <li class="breadcrumb-item"><a href="<?php echo $list; ?>">CUSTOMERS</a></li>
            <li class="breadcrumb-item active"><?php echo $active_title; ?></li>
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
          <!-- general form elements -->
          <div class="card card-primary">
            <div class="card-header">
              <h3 class="card-title"><?php echo $title; ?></h3>
            </div>
            <form action="<?php echo $action; ?>" id="customer_form" method="post" enctype="multipart/form-data" name="customers_form">
              <div class="card-body">
                <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">

                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="">Customer Type<span class="text-danger">*</span></label>
                      <select name="customer_type" id="" value="<?php echo $customer_type ?>" class="form-control form-control-sm">
                        <option value="" selected disabled>Select</option>
                        <option value="Factory" <?php echo set_select('customer_type', 'Factory') ?> <?php echo ($customer_type == "Factory") ? "selected" : "" ?>>Factory</option>
                        <option value="Buyer" <?php echo set_select('customer_type', 'Buyer') ?> <?php echo ($customer_type == "Buyer") ? "selected" : "" ?>>Buyer</option>
                        <option value="Agent" <?php echo set_select('customer_type', 'Agent') ?> <?php echo ($customer_type == "Agent") ? "selected" : "" ?>>Agent</option>
                        <option value="Thirdparty" <?php echo set_select('customer_type', 'Thirdparty') ?><?php echo ($customer_type == "Thirdparty") ? "selected" : "" ?>>Thirdparty</option>
                      </select>
                      <?php echo form_error('customer_type'); ?>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="">Customer Name (Company Name)<span class="text-danger">*</span></label>
                      <input type="text" name="customer_name" class="form-control form-control-sm" placeholder="Customer Name" value="<?php echo $customer_name ?>" autocomplete="off">
                      <input type="hidden" name="customer_code" value="<?php echo $customer_code ?>">
                      <?php echo form_error('customer_name'); ?>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="">Customer Email<span class="text-danger">*</span></label>
                      <input type="email" name="email" class="form-control form-control-sm" placeholder="Email" value="<?php echo $email ?>" autocomplete="off">
                      <?php echo form_error('email'); ?>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="">Telephone</label>
                      <input type="number" name="telephone" class="form-control form-control-sm" placeholder="Telephone" value="<?php echo $telephone ?>" autocomplete="off">
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="">Mobile<span class="text-danger">*</span></label>
                      <input type="Number" name="mobile" class="form-control form-control-sm" placeholder="Mobile" value="<?php echo $mobile ?>" autocomplete="off">
                      <?php echo form_error('mobile'); ?>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Branch</label>
                      <select name="mst_branch_id" id="" class="form-control form-control-sm">
                        <option value="" selected disabled>Select</option>
                        <?php foreach ($branchs as $value) { ?>
                          <option value="<?php echo $value->branch_id; ?>" <?php echo (($branch == $value->branch_id) ? 'SELECTED' : ''); ?>><?php echo $value->branch_name; ?></option>
                        <?php } ?>
                      </select>
                    </div>
                  </div>

                  <div class="col-md-12">
                    <div class="form-group">
                      <label for="">Address<span class="text-danger">*</span></label>
                      <textarea name="address" class="form-control form-control-sm" rows="2" maxlength="150" placeholder="Address"><?php echo $address ?></textarea>
                      <span class="text-muted small">Only 150 characters allowed including spaces.</span>
                      <?php echo form_error('address'); ?>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="">City<span class="text-danger">*</span></label>
                      <input type="text" name="city" class="form-control form-control-sm" placeholder="City" value="<?php echo $city ?>" autocomplete="off">
                      <?php echo form_error('city'); ?>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group">
                      <label for=""><span class="text-danger">*</span>Pin Code</label>
                      <input type="text" name="po_box" class="form-control form-control-sm" placeholder="Pin Number" value="<?php echo $po_box ?>" autocomplete="off">
                      <?php echo form_error('po_box'); ?>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="">Country<span class="text-danger">*</span></label>
                      <input class="country_id" type="hidden" value="<?php echo $cust_customers_country_id ?>" name="cust_customers_country_id">
                      <input type="text" name="country_name" class="form-control form-control-sm country_name" value="<?php echo $country_name ?>" placeholder="Type country name...." autocomplete="off">
                      <?php echo form_error('cust_customers_country_id'); ?>
                      <ul class="list-group-item country_list" style="display:none"></ul>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="">State<span class="text-danger">*</span></label>
                      <input class="cust_customers_province_id" type="hidden" value="<?php echo $cust_customers_province_id ?>" name="cust_customers_province_id">
                      <input type="text" name="state_name" class="form-control form-control-sm state_name" placeholder="Type state name.." value="<?php echo $state_name ?>" autocomplete="off">
                      <ul class="list-group-item state_list" style="display:none">
                      </ul>
                      <?php echo form_error('cust_customers_province_id'); ?>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="">Area/Location</label>
                      <input class="cust_customers_location_id" type="hidden" value="<?php echo $cust_customers_location_id ?>" name="cust_customers_location_id">
                      <input type="text" name="location_name" class="form-control form-control-sm location_name" placeholder="Type Locations" value="<?php echo $location_name ?>" autocomplete="off">
                      <ul class="list-group-item location_list" style="display:none"></ul>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="">Website</label>
                      <input type="text" name="web" class="form-control form-control-sm" placeholder="Enter a website.." value="<?php echo $web ?>">
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="">Status<span class="text-danger">*</span></label>
                      <select name="isactive" id="" value="" class="form-control form-control-sm">
                        <option value="" selected disabled>Select</option>
                        <option value="Active" <?php echo set_select('isactive', 'Active') ?> <?php echo ($isactive == "Active") ? "selected" : "" ?>>Active</option>
                        <option value="Inactive" <?php echo set_select('isactive', 'Inactive') ?><?php echo ($isactive == "Inactive") ? "selected" : "" ?>>In-Active</option>
                        <option value="New" <?php echo set_select('isactive', 'New') ?> <?php echo ($isactive == "New") ? "selected" : "" ?>>New</option>
                        <option value="Hold For Payment" <?php echo set_select('isactive', 'Hold For Payment') ?><?php echo ($isactive == "Hold For Payment") ? "selected" : "" ?>>Hold For Payment</option>
                      </select>
                      <?php echo form_error('isactive'); ?>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="">Payement Term<span class="text-danger">*</span></label>
                      <select name="credit" id="credit" value="" class="form-control form-control-sm">
                        <option value="" selected disabled>Select</option>
                        <option value="Advance" <?php echo set_select('credit', 'Advance') ?> <?php echo ($credit == "Advance") ? "selected" : "" ?>>Advance</option>
                        <option value="10 Days" <?php echo set_select('credit', '14 Days') ?> <?php echo ($credit == "14 Days") ? "selected" : "" ?>>14 Days</option>
                        <option value="30 Days" <?php echo set_select('credit', '30 Days') ?> <?php echo ($credit == "30 Days") ? "selected" : "" ?>>30 Days</option>
                        <option value="45 Days" <?php echo set_select('credit', '60 Days') ?> <?php echo ($credit == "60 Days") ? "selected" : "" ?>>60 Days</option>
                        <option value="60 Days" <?php echo set_select('credit', '90 Days') ?> <?php echo ($credit == "90 Days") ? "selected" : "" ?>>90 Days</option>
                      </select>
                      <?php echo form_error('credit'); ?>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group">
                      <label for=""><span class="text-danger">*</span>PAN NO.</label>
                      <input type="text" name="pan" class="form-control form-control-sm pan" placeholder="Enter PAN NO." value="<?php echo $pan ?>" autocomplete="off">
                      <?php echo form_error('pan'); ?>

                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="">TAN NO. Mendatory</label>
                      <input type="radio" name="tan_mendatory_flag" value="yes" <?php if ($tan_mendatory_flag == "yes") {
                                                                        echo "checked";
                                                                      } ?>>YES
                      <input type="radio" name="tan_mendatory_flag" value="no" <?php if ($tan_mendatory_flag == "no") {
                                                                        echo "checked";
                                                                      } ?>>NO<br>
                      <label for="">TAN NO.</label>
                      <input type="text" name="tan" class="form-control form-control-sm tan" placeholder="Enter TAN NO." value="<?php echo $tan ?>" autocomplete="off">
                      <?php echo form_error('tan'); ?>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="">GST Customer Type</label>
                      <select name="cust_type" id="cust_type" value="0" class="form-control form-control-sm">
                        <option value="" selected disabled>Select</option>
                        <option value="Registered" <?php echo set_select('cust_type', 'Registered') ?> <?php echo ($cust_type == "Registered") ? "selected" : "" ?>>Registered</option>
                        <option value="Unregistered" <?php echo set_select('cust_type', 'Unregistered') ?><?php echo ($cust_type == "Unregistered") ? "selected" : "" ?>>Unregistered</option>
                        <option value="Export" <?php echo set_select('cust_type', 'Export') ?> <?php echo ($cust_type == "Export") ? "selected" : "" ?>>Export</option>
                        <option value="Deemed Export" <?php echo set_select('cust_type', 'Deemed Export') ?><?php echo ($cust_type == "Deemed Export") ? "selected" : "" ?>>Deemed Export</option>
                        <option value="Exempted" <?php echo set_select('cust_type', 'Exempted') ?><?php echo ($cust_type == "Exempted") ? "selected" : "" ?>>Exempted</option>
                        <option value="SEZ Development" <?php echo set_select('cust_type', 'SEZ Development') ?><?php echo ($cust_type == "SEZ Development") ? "selected" : "" ?>>SEZ Development</option>
                        <option value="SEZ Unit" <?php echo set_select('cust_type', 'SEZ Unit') ?><?php echo ($cust_type == "SEZ Unit") ? "selected" : "" ?>>SEZ Unit</option>
                      </select>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="">GSTIN</label>
                      <input type="text" name="gstin" class="form-control form-control-sm gstin" id="gst_in" placeholder="Enter GSTIN NO." value="<?php echo $gstin ?>" autocomplete="off">
                      <?php echo form_error('gstin'); ?>
                    </div>
                  </div>

                  <!-- <div class="col-md-6">
                    <div class="form-group">
                      <label for="">kamal</label>
                      <input type="text" name="kamal" class="form-control form-control-sm gstin" id="gst_in" placeholder="Enter GSTIN NO." value="<?php echo $gstin ?>" autocomplete="off">
                    </div>
                  </div> -->
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="">GST Registration Type</label>
                      <select name="regi_type" id="regi_type" class="form-control form-control-sm">
                        <option value="" selected disabled>Select</option>
                        <option value="GSTIN" <?php echo set_select('regi_type', 'GSTIN') ?> <?php echo ($regi_type == "GSTIN") ? "selected" : "" ?>>GSTIN</option>
                        <option value="UID" <?php echo set_select('regi_type', 'UID') ?> <?php echo ($regi_type == "UID") ? "selected" : "" ?>>UID</option>
                        <option value="GID" <?php echo set_select('regi_type', 'GID') ?> <?php echo ($regi_type == "GID") ? "selected" : "" ?>>GID</option>
                      </select>
                      <?php echo form_error('regi_type'); ?>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="">Retention period<span class="text-danger">*</span></label>
                      <input type="number" name="retention_period" class="form-control form-control-sm" placeholder="Enter Retention Period" value="<?php echo $retention_period ?>" autocomplete="off">
                      <?php echo form_error('retention_period'); ?>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Credit limit<span class="text-danger">*</span></label>
                      <input type="number" step="0.01" name="credit_limit" value="<?php echo $credit_limit; ?>" class="form-control form-control-sm credit_limit">
                      <span class="limit_error text-danger" style="display: none;">Credit limit can not be zero.</span>
                      <?php echo form_error('credit_limit'); ?>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="">Nav Customer Code</label>
                      <input type="text" name="nav_customer_code" class="form-control form-control-sm" placeholder="Enter Nav Customer Code" value="<?php echo isset($nav_customer_code)?$nav_customer_code:''; ?>" autocomplete="off">
                    </div>
                  </div>                                                   
                  <div class="col-sm-6">
                    <label for="">Gen. Bus. Posting Group</label>
                    <select class="form-control form-control-sm" name="gen" id="gen">
                      <option value="">No Selected</option>
                      <?php if (!empty($gen)) { ?>
                        <option value="<?php echo $gen_post['posting_group_id']; ?>" selected>
                          <?php echo $gen_post['posting_group_name']; ?></option>
                      <?php } ?>
                    </select>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="">VAT Bus. Posting Group</label>
                      <select class="form-control form-control-sm" name="vat" id="vat">
                        <option value="">No Selected</option>
                        <?php if (!empty($vat)) { ?>
                          <option value="<?php echo $vat_post['posting_group_id']; ?>" selected>
                            <?php echo $vat_post['posting_group_name']; ?></option>
                        <?php } ?>
                      </select>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="">Exise Bus. Posting Group</label>
                      <select class="form-control form-control-sm" name="excise" id="excise">
                        <option value="">No Selected</option>
                        <?php if (!empty($excise)) { ?>
                          <option value="<?php echo $excise_post['posting_group_id']; ?>" selected>
                            <?php echo $excise_post['posting_group_name']; ?></option>
                        <?php } ?>
                      </select>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="">Customer Posting Group</label>
                      <select class="form-control form-control-sm" name="cust_post" id="cust_post">
                        <option value="">No Selected</option>
                        <?php if (!empty($cust_post)) { ?>
                          <option value="<?php echo $custom_post['posting_group_id']; ?>" selected>
                            <?php echo $custom_post['posting_group_name']; ?></option>
                        <?php } ?>
                      </select>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="">Balance (LCY)</label>
                      <input type="text" name="balance" class="form-control form-control-sm" placeholder="Enter Balance (LCY)" value="<?php echo $balance; ?>" autocomplete="off">
                    </div>
                  </div>

                

                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="" class="mr-2">Non taxable</label>
                      <input type="checkbox" name="non_taxable" class="" value="1" <?php echo ($non_taxable == '1') ? "checked" : ""; ?>>
                    </div>
                  </div>
                </div>
              </div>
              <div class="card-footer">
                <a href="<?php echo $list; ?>" class="btn btn-primary">Back</a>
                <input type="submit" class="btn btn-primary" name="submitCustomer" style="float: right;" id="submit" value="<?php echo $button ?>">
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>

<script>
  function formatRepo(repo) {
    if (repo.loading) {
      return repo.text;
    }
    var $container = $(
      "<div class='select2-result-repository clearfix'>" +
      "<div class='select2-result-repository__title'></div>" +
      "</div>"
    );

    $container.find(".select2-result-repository__title").text(repo.name);
    return $container;
  }

  function formatRepoSelection(repo) {
    return repo.full_name || repo.text;
  }

  $(document).ready(function() {

    // Added by CHANDAN --14-07-2022
    // $('#gst_in').keyup(function() {
    //   let value = $(this).val();
    //   if (value.length > 0) {
    //     $('#cust_type').val('Registered');
    //     $('#regi_type').val('GSTIN');
    //   } else {
    //     $('#cust_type').val('');
    //     $('#regi_type').val('');
    //   }
    // });

    $('#gen').select2({
      allowClear: true,
      ajax: {
        url: '<?php echo base_url("customer_management/Customers/gen_posting_group") ?>',
        dataType: 'json',
        data: function(params) {
          return {
            key: params.term,
          };
        },
        processResults: function(response) {
          return {
            results: response
          };
        },
        cache: true
      },
      placeholder: 'Gen.Bus.Posting Group',
      minimumInputLength: 0,
      templateResult: formatRepo,
      templateSelection: formatRepoSelection
    });

    $('#vat').select2({
      allowClear: true,
      ajax: {
        url: '<?php echo base_url("customer_management/Customers/vat_posting_group") ?>',
        dataType: 'json',
        data: function(params) {
          return {
            key: params.term,
          };
        },
        processResults: function(response) {
          return {
            results: response
          };
        },
        cache: true
      },
      placeholder: 'VAT Bus. Posting Group',
      minimumInputLength: 0,
      templateResult: formatRepo,
      templateSelection: formatRepoSelection
    });

    $('#excise').select2({
      allowClear: true,
      ajax: {
        url: '<?php echo base_url("customer_management/Customers/excise_posting_group") ?>',
        dataType: 'json',
        data: function(params) {
          return {
            key: params.term,
          };
        },
        processResults: function(response) {
          return {
            results: response
          };
        },
        cache: true
      },
      placeholder: 'Excise Bus. Posting Group',
      minimumInputLength: 0,
      templateResult: formatRepo,
      templateSelection: formatRepoSelection
    });

    $('#cust_post').select2({
      allowClear: true,
      ajax: {
        url: '<?php echo base_url("customer_management/Customers/customer_posting_group") ?>',
        dataType: 'json',
        data: function(params) {
          return {
            key: params.term,
          };
        },
        processResults: function(response) {
          return {
            results: response
          };
        },
        cache: true
      },
      placeholder: 'Customer Posting Group',
      minimumInputLength: 0,
      templateResult: formatRepo,
      templateSelection: formatRepoSelection
    });
    // End....


    // function getAutolist(hide_input,input,ul,li,where,like,select,table)
    // country
    $('.country_name').focus(function(e) {
      e.preventDefault();
      getAutolist('country_id', 'country_name', 'country_list', 'country_li', 'status="1"', 'country_name', 'country_id as id,country_name as name', 'mst_country');
    });

    // state
    $('.state_name').focus(function(e) {

      e.preventDefault();
      var country_id = $('.country_id').val();

      var where_array = JSON.stringify({
        'status': '1',
        'mst_provinces_country_id': country_id
      });

      getAutolist('cust_customers_province_id', 'state_name', 'state_list', 'state_li', where_array, 'province_name', 'province_id as id,province_name as name', 'mst_provinces');
    });

    $('.location_name').focus(function(e) {

      e.preventDefault();
      var location_id = $('.cust_customers_province_id').val();

      var where_array = JSON.stringify({
        'status': '1',
        'mst_locations_province_id': location_id
      });

      getAutolist('cust_customers_location_id', 'location_name', 'location_list', 'location_li', where_array, 'location_name', 'location_id as id,location_name as name', 'mst_locations');
    });
  });

  var css = {
    "position": "absolute",
    "width": "95%",
    "font-size": "12px",
    "z-index": 999,
    "overflow-y": "auto",
    "overflow-x": "hidden",
    "max-height": "200px",
    "cursor": "pointer",
  };

  function getAutolist(hide_input, input, ul, li, where, like, select, table) {

    var base_url = $("body").attr("data-url");
    var hide_inputEvent = $("input." + hide_input);
    var inputEvent = $("input." + input);
    var ulEvent = $("ul." + ul);

    inputEvent.focusout(function() {
      ulEvent.fadeOut();
    });

    inputEvent.on("keyup", function(e) {
      var me = $(this);
      var key = $(this).val();
      var _URL = base_url + "get_auto_list";
      const _tokken = $('meta[name="_tokken"]').attr("value");
      e.preventDefault();

      $.ajax({
        url: _URL,
        method: "POST",
        data: {
          key: key,
          where: where,
          like: like,
          select: select,
          table: table,
          _tokken: _tokken,
        },
        success: function(data) {
          var html = $.parseJSON(data);
          ulEvent.fadeIn();
          ulEvent.css(css);
          ulEvent.html("");
          if (html) {
            $.each(html, function(index, value) {
              ulEvent.append(
                $(
                  '<li class="list-group-item ' +
                  li +
                  '"' +
                  "data-id=" +
                  value.id +
                  ">" +
                  value.name +
                  "</li>"
                )
              );
            });
          } else {
            ulEvent.append(
              $(
                '<li class="list-group-item ' +
                li +
                '"' +
                'data-id="not">NO REORD FOUND</li>'
              )
            );
          }

          var liEvent = $("li." + li);
          liEvent.click(function() {
            var id = $(this).attr("data-id");
            var name = $(this).text();
            inputEvent.val(name);
            hide_inputEvent.val(id);
            ulEvent.fadeOut();
          });

          // ****
        },

      });
      return false;
    });
  }
</script>

<script>
  // $(document).ready(function() {
  //   $('#credit').change(function() {
  //     var credit_days = $(this).val();
  //     if (credit_days == 'Advance') {
  //       $('.credit_limit').val(0);
  //       $('.credit_limit').attr('readonly', true);
  //     } else {
  //       $('.credit_limit').val(0);
  //       $('.credit_limit').attr('readonly', false);
  //     }
  //   });
  // });

  $(document).on('change', '#cust_type', function() {
    let unregist = $('#cust_type').val();

    // let newCust_type = '<?php echo $cust_type; ?>';

    // console.log(newCust_type);
    if (unregist == 'Unregistered') {
      $('#gst_in').attr('disabled', true);
    } else if (unregist != 'Unregistered') {
      $('#gst_in').removeAttr('disabled');
    }
    // if (newCust_type == 'Unregistered') {
    //   $('#gst_in').attr('disabled', true);
    // } else if (newCust_type != 'Unregistered'&&unregist == 'Unregistered') {
    //   $('#gst_in').removeAttr('disabled');
    // }
  });
</script>
