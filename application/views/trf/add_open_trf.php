<?php
$checkUser = $this->session->userdata('user_data');
if (!empty($trf)) {

  $id = $trf->trf_id;
  $action = base_url() . "edit-open-trf/" . $id;
  $customer_type = $trf->open_trf_customer_type;
  $temp_ref_id = $trf->temp_ref_id;
  $customer_id = $trf->open_trf_customer_id;
  if ($trf->service_days != "") {
    $service_type = $trf->service_days;
  } else {
    $service_type = $trf->trf_service_type;
  }
  $applicant_id = $trf->trf_applicant;
  $buyer_id = $trf->trf_buyer;
  $agent_id = $trf->trf_agent;
  $third_party_id = $trf->trf_thirdparty;
  // $contact_person_id = explode(",", $trf->trf_contact);
  if ($trf->trf_contact != '') {
    $contact_person_id = explode(",", $trf->trf_contact);
  } else {
    $contact_person_id = [];
  }
  $sample_ref = $trf->trf_sample_ref_id;
  $sample_descrition = $trf->trf_sample_desc;
  $invoice_to = $trf->trf_invoice_to;

  if ($trf->trf_invoice_to_contact != '') {
    $cp_invoice_to_id = explode(",", $trf->trf_invoice_to_contact);
  } else {
    $cp_invoice_to_id = [];
  }
  if ($trf->cc_type != '') {
    $cc_type = explode(",", $trf->cc_type);
  } else {
    $cc_type = [];
  }
  if ($trf->bcc_type != '') {
    $bcc_type = explode(",", $trf->bcc_type);
  } else {
    $bcc_type = [];
  }
  if ($trf->trf_cc != '') {
    $cc_id = explode(",", $trf->trf_cc);
  } else {
    $cc_id = [];
  }
  if ($trf->trf_bcc != '') {
    $bcc_id = explode(",", $trf->trf_bcc);
  } else {
    $bcc_id = [];
  }
  //  $bcc_type = explode(",", $trf->bcc_type);
  //  $cc_id = explode(",", $trf->trf_cc);
  //  $bcc_id = explode(",", $trf->trf_bcc);
  $no_of_sample = $trf->trf_no_of_sample;
  $client_ref_no = $trf->trf_client_ref_no;
  $destination_country = $trf->trf_country_destination;
  $origin_country = $trf->trf_country_orgin;
  $currency_id = $trf->open_trf_currency_id;
  $exchange_rate = $trf->open_trf_exchange_rate;
  $product = $trf->trf_product;
  $end_use = $trf->trf_end_use;
  if ($trf->sample_return_to != '') {
    $sample_return_to = explode(",", $trf->sample_return_to);
  } else {
    $sample_return_to = [];
  }
  if ($trf->reported_to != '') {
    $reported_to = explode(",", $trf->reported_to);
  } else {
    $reported_to = [];
  }
  // $reported_to = explode(",", $trf->reported_to);
  $division_id = $trf->division;
  $crm_user_id = explode(',', $trf->crm_user_id);
  // print_r($crm_user_id); die;
  $care_instruction = explode(",", $trf->care_instruction);
  $sample_pickup_services = $trf->sample_pickup_services;
  $tat_date = $trf->tat_date;
  $branch = (set_value('trf_branch') ? set_value('trf_branch') : $trf->trf_branch);
  $sales_person_id = $trf->sales_person;
  $regulation_desc = $trf->regulation_desc;
  $regulation_image = $trf->regulation_image;
  $wash_care = $trf->wash_care;
  $case = 'edit';
} else {
  $case = 'add';
  $action = base_url('add-open-trf');
  $temp_ref_id = set_value('temp_ref_id');
  $customer_type = set_value('trf_customer_type');
  $customer_id = set_value('open_trf_customer_id');
  $service_type = set_value('trf_service_type');
  $applicant_id = set_value('trf_applicant');
  $buyer_id = set_value('trf_buyer');
  $agent_id = set_value('trf_agent');
  $third_party_id = set_value('trf_thirdparty');
  if (set_value('trf_contact') != '') {
    $contact_person_id = set_value('trf_contact');
  } else {
    $contact_person_id = [];
  }
  $sample_ref = set_value('trf_sample_ref_id');
  $sample_descrition = set_value('trf_sample_desc');
  $invoice_to = set_value('invoice_to');

  if (set_value('trf_invoice_to_contact') != '') {
    $cp_invoice_to_id = set_value('trf_invoice_to_contact');
  } else {
    $cp_invoice_to_id = [];
  }

  //$cc_type = explode(",", set_value('cc_id'));

  if (set_value('cc_id') != '') {
    $cc_type = set_value('cc_id');
  } else {
    $cc_type = [];
  }

  //$bcc_type = explode(",", set_value('bcc_id'));

  if (set_value('bcc_id') != '') {
    $bcc_type = set_value('bcc_id');
  } else {
    $bcc_type = [];
  }

  // $cc_id = explode(",", set_value('trf_cc'));
  // print_r(set_value('trf_cc')); die;
  if (set_value('trf_cc') != '') {
    $cc_id = set_value('trf_cc');
  } else {
    $cc_id = [];
  }

  //$bcc_id =  explode(",", set_value('trf_bcc'));
  if (set_value('trf_bcc') != '') {
    $bcc_id = set_value('trf_bcc');
  } else {
    $bcc_id = [];
  }

  $no_of_sample = set_value('trf_no_of_sample');
  $client_ref_no = set_value('trf_client_ref_no');
  $destination_country = set_value('trf_country_destination');
  $origin_country = set_value('trf_country_orgin');
  $currency_id = set_value('open_trf_currency_id');
  $exchange_rate = set_value('open_trf_exchange_rate');
  $product = set_value('trf_product');
  $end_use = set_value('trf_end_use');
  //$sample_return_to = explode(",", set_value('sample_return_to'));
  if (set_value('sample_return_to') != '') {
    $sample_return_to = set_value('sample_return_to');
  } else {
    $sample_return_to = [];
  }

  //$reported_to = explode(",", set_value('reported_to'));
  if (set_value('reported_to') != '') {
    $reported_to = set_value('reported_to');
  } else {
    $reported_to = [];
  }
  $division_id = set_value('division');
  $crm_user_id[] = set_value('crm_user_id');
  //$care_instruction = explode(",", set_value('care_instruction'));

  if (set_value('care_instruction') != '') {
    $care_instruction = explode(",", set_value('care_instruction'));
  } else {
    $care_instruction = [];
  }

  $sample_pickup_services = set_value('sample_pickup_services');
  $tat_date = set_value('tat_date');
  $gridata = set_value('griddata');
  $branch = (set_value('trf_branch') ? set_value('trf_branch') : $checkUser->branch_id);
  $sales_person_id = '';
  $regulation_desc = '';
  $regulation_image = '';
}
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <script src="<?php echo base_url('assets/js/test_request_form.js') ?>"></script>
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Open TRF </h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>">Home</a></li>
            <li class="breadcrumb-item"><a href="<?php echo base_url('open-trf-list'); ?>">Open TRF List</a></li>
            <li class="breadcrumb-item active">Open TRF Form</li>
          </ol>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>

  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <!-- left column -->
        <div class="col-md-12">
          <!-- general form elements -->
          <div class="card card-primary">
            <div class="card-header">
              <h3 class="card-title"><?php if (empty($trf)) {
                                        echo "Add";
                                      } else {
                                        echo "Edit";
                                      } ?> Test Request Form</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <?php //echo validation_errors(); 
            ?>
            <form autocomplete="off" name="open-trf" id="open-trf" role="form" method="post" action="<?php echo $action; ?>" enctype="multipart/form-data">
              <div class="card-body">
                <div class="row">
                  <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                  <input type="hidden" class="process_type" value="<?php echo $case; ?>">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="exampleInputEmail1">Select Temporary Number</label>
                      <select class="form-control select-box form-control-sm" id="temp_ref_id" name="temp_ref_id">
                        <option selected="" disabled="">Select Temporary Number</option>
                        <?php if (!empty($temp_reg)) {
                          foreach ($temp_reg as $temp) { ?>
                            <option value="<?= $temp['temp_reg_id']; ?>" <?php if ($temp_ref_id == $temp['temp_reg_id']) {
                                                                            echo "selected";
                                                                          } ?>><?= $temp['temp_no']; ?></option>
                        <?php }
                        } ?>
                      </select>
                      <?php echo form_error('temp_ref_id', '<div class="text-danger">', '</div>'); ?>
                    </div>

                    <div class="form-group">
                      <label for="exampleInputEmail1">Select Customer Type</label>
                      <select class="form-control select-box form-control-sm" id="customer_type" name="trf_customer_type">
                        <option selected="" disabled="">Select Customer Type</option>
                        <option value="Factory" <?php if ($customer_type == "Factory") {
                                                  echo "selected";
                                                } ?>>Factory</option>
                        <option value="Buyer" <?php if ($customer_type == "Buyer") {
                                                echo "selected";
                                              } ?>>Buyer</option>
                      </select>
                      <?php echo form_error('trf_customer_type', '<div class="text-danger">', '</div>'); ?>
                    </div>

                    <div class="form-group">
                      <label for="exampleInputEmail1">Select Service Type</label>
                      <select class="form-control select-box form-control-sm" name="trf_service_type">
                        <option selected="" disabled="">Select Service Type</option>
                        <option value="Regular" <?php if ($service_type == "Regular") {
                                                  echo "selected";
                                                } ?>>Regular(3 working days)</option>
                        <option value="Express" <?php if ($service_type == "Express") {
                                                  echo "selected";
                                                } ?>>Express(2 working days)</option>
                        <option value="Express3" <?php if ($service_type == "Express3") {
                                                    echo "selected";
                                                  } ?>>Express(3 working days)</option>
                        <option value="Urgent" <?php if ($service_type == "Urgent") {
                                                  echo "selected";
                                                } ?>>Urgent(1 working days)</option>
                        <option value="2" <?php if ($service_type == "2") {
                                            echo "selected";
                                          } ?>>Regular 2 days</option>
                        <option value="4" <?php if ($service_type == "4") {
                                            echo "selected";
                                          } ?>>Regular 4 days</option>
                        <option value="5" <?php if ($service_type == "5") {
                                            echo "selected";
                                          } ?>>Regular 5 days</option>
                        <option value="6" <?php if ($service_type == "6") {
                                            echo "selected";
                                          } ?>>Regular 6 days</option>
                        <option value="7" <?php if ($service_type == "7") {
                                            echo "selected";
                                          } ?>>Regular 7 days</option>
                        <option value="8" <?php if ($service_type == "8") {
                                            echo "selected";
                                          } ?>>Regular 8 days</option>
                        <option value="9" <?php if ($service_type == "9") {
                                            echo "selected";
                                          } ?>>Regular 9 days</option>
                        <option value="10" <?php if ($service_type == "10") {
                                              echo "selected";
                                            } ?>>Regular 10 days</option>
                        <option value="12" <?php if ($service_type == "12") {
                                              echo "selected";
                                            } ?>>Regular 12 days</option>
                        <option value="15" <?php if ($service_type == "15") {
                                              echo "selected";
                                            } ?>>Regular 15 days</option>
                        <option value="20" <?php if ($service_type == "20") {
                                              echo "selected";
                                            } ?>>Regular 20 days</option>
                        <option value="30" <?php if ($service_type == "30") {
                                              echo "selected";
                                            } ?>>Regular 30 days</option>
                      </select>
                      <?php echo form_error('trf_service_type', '<div class="text-danger">', '</div>'); ?>
                    </div>

                    <div class="form-group">
                      <label for="exampleInputEmail1">Select Applicant</label>
                      <select class="form-control form-control-sm" id="applicant" name="trf_applicant">
                        <?php if (!empty($applicant_id)) {
                          foreach ($applicant as $value) { ?>
                            <option value="<?php echo $value['id'] ?>" <?php if ($applicant_id == $value['id']) {
                                                                          echo "selected";
                                                                        } ?>><?php echo $value['name'] ?></option>
                        <?php }
                        } ?>
                      </select>
                      <?php echo form_error('trf_applicant', '<div class="text-danger">', '</div>'); ?>
                    </div>

                    <div class="form-group">
                      <label for="exampleInputEmail1">Select Contact Person</label>
                      <select class="form-control form-control-sm" id="contact-person" multiple="" name="trf_contact[]">
                        <?php if (!empty($contact_person_id)) {
                          foreach ($contact_person as $value) { ?>
                            <option value="<?php echo $value['id'] ?>" <?php if (in_array($value['id'], $contact_person_id)) {
                                                                          echo "selected";
                                                                        } ?>><?php echo $value['name'] ?></option>
                        <?php }
                        } ?>
                      </select>
                      <?php echo form_error('trf_contact[]', '<div class="text-danger">', '</div>'); ?>
                    </div>

                    <div class="form-group">
                      <label for="exampleInputEmail1">Sample Reference ID</label>
                      <input type="text" class="form-control form-control-sm" placeholder="Sample Ref. ID" name="trf_sample_ref_id" value="<?php echo $sample_ref; ?>">
                      <?php echo form_error('trf_sample_ref_id', '<div class="text-danger">', '</div>'); ?>
                    </div>

                    <div class="form-group">
                      <label for="exampleInputEmail1">Invoice To</label>
                      <div class="row">
                        <div class="col-sm-3">
                          <!-- checkbox -->
                          <div class="custom-control custom-radio">
                            <input class="custom-control-input invoice-to" type="radio" id="invoiceto1" name="invoice_to" value="Factory" <?php if ($invoice_to == "Factory") {
                                                                                                                                            echo "checked";
                                                                                                                                          } ?>>
                            <label for="invoiceto1" class="custom-control-label">Factory</label>
                          </div>
                        </div>
                        <div class="col-sm-3">
                          <!-- checkbox -->
                          <div class="custom-control custom-radio">
                            <input class="custom-control-input invoice-to" type="radio" id="invoiceto2" name="invoice_to" value="Buyer" <?php if ($invoice_to == "Buyer") {
                                                                                                                                          echo "checked";
                                                                                                                                        } ?>>
                            <label for="invoiceto2" class="custom-control-label">Buyer</label>
                          </div>
                        </div>
                        <div class="col-sm-3">
                          <!-- checkbox -->
                          <div class="custom-control custom-radio">
                            <input class="custom-control-input invoice-to" type="radio" id="invoiceto3" name="invoice_to" value="Agent" <?php if ($invoice_to == "Agent") {
                                                                                                                                          echo "checked";
                                                                                                                                        } ?>>
                            <label for="invoiceto3" class="custom-control-label">Agent</label>
                          </div>
                        </div>
                        <div class="col-sm-3">
                          <!-- checkbox -->
                          <div class="custom-control custom-radio">
                            <input class="custom-control-input invoice-to" type="radio" id="invoiceto4" name="invoice_to" value="ThirdParty" <?php if ($invoice_to == "ThirdParty") {
                                                                                                                                                echo "checked";
                                                                                                                                              } ?>>
                            <label for="invoiceto4" class="custom-control-label">ThirdParty</label>
                          </div>
                        </div>
                      </div>
                      <?php echo form_error('invoice_to', '<div class="text-danger">', '</div>'); ?>
                    </div>

                    <div class="form-group">
                      <label for="exampleInputEmail1">Contact Person<small>(Invoice To:)</small></label>
                      <select class="form-control form-control-sm" multiple="" id="cp-invoice-to" name="trf_invoice_to_contact[]">
                        <?php if (!empty($cp_invoice_to_id)) {
                          foreach ($cp_invoice_to as $value) { ?>
                            <option value="<?php echo $value['id'] ?>" <?php if (in_array($value['id'], $cp_invoice_to_id)) {
                                                                          echo "selected";
                                                                        } ?>><?php echo $value['name'] ?></option>
                        <?php }
                        } ?>
                      </select>
                      <?php echo form_error('trf_invoice_to_contact[]', '<div class="text-danger">', '</div>'); ?>
                    </div>

                    <div class="form-group">
                      <label for="exampleInputEmail1">CC</label>
                      <div class="row">
                        <div class="col-sm-3">
                          <!-- checkbox -->
                          <div class="custom-control custom-checkbox">
                            <input class="custom-control-input cc" name="cc_id[]" type="checkbox" id="cp1" value="Factory" <?php if (in_array("Factory", $cc_type)) {
                                                                                                                              echo "checked";
                                                                                                                            } ?>>
                            <label for="cp1" class="custom-control-label">Factory</label>
                          </div>
                        </div>
                        <div class="col-sm-3">
                          <!-- checkbox -->
                          <div class="custom-control custom-checkbox">
                            <input class="custom-control-input cc" name="cc_id[]" type="checkbox" id="cp2" value="Buyer" <?php if (in_array("Buyer", $cc_type)) {
                                                                                                                            echo "checked";
                                                                                                                          } ?>>
                            <label for="cp2" class="custom-control-label">Buyer</label>
                          </div>
                        </div>
                        <div class="col-sm-3">
                          <!-- checkbox -->
                          <div class="custom-control custom-checkbox">
                            <input class="custom-control-input cc" name="cc_id[]" type="checkbox" id="cp3" value="Agent" <?php if (in_array("Agent", $cc_type)) {
                                                                                                                            echo "checked";
                                                                                                                          } ?>>
                            <label for="cp3" class="custom-control-label">Agent</label>
                          </div>
                        </div>
                        <div class="col-sm-3">
                          <!-- checkbox -->
                          <div class="custom-control custom-checkbox">
                            <input class="custom-control-input cc" name="cc_id[]" type="checkbox" id="cp4" value="ThirdParty" <?php if (in_array("ThirdParty", $cc_type)) {
                                                                                                                                echo "checked";
                                                                                                                              } ?>>
                            <label for="cp4" class="custom-control-label">ThirdParty</label>
                          </div>
                        </div>
                      </div>
                      <?php echo form_error('cc_id[]', '<div class="text-danger">', '</div>'); ?>
                    </div>

                    <div class="form-group">
                      <label for="exampleInputEmail1">Seelct CC Contact</label>
                      <select class="form-control form-control-sm" id="cc-contact" multiple="" name="trf_cc[]">
                        <?php if (!empty($cc_id)) {
                          foreach ($cc_contact as $value) { ?>
                            <option value="<?php echo $value['contact_id']; ?>" <?php if (in_array($value['contact_id'], $cc_id)) {
                                                                                  echo "selected";
                                                                                } ?>><?php echo $value['contact_name']; ?></option>
                        <?php }
                        } ?>
                      </select>
                      <?php echo form_error('trf_cc[]', '<div class="text-danger">', '</div>'); ?>
                    </div>

                    <div class="form-group">
                      <label for="exampleInputEmail1">Client Ref. No</label>
                      <input type="text" class="form-control form-control-sm" placeholder="Client Ref. Number" name="trf_client_ref_no" value="<?php echo $client_ref_no; ?>">
                      <?php echo form_error('trf_client_ref_no', '<div class="text-danger">', '</div>'); ?>
                    </div>

                    <div class="form-group">
                      <label for="">Test Protocol</label>
                      <select name="test_protocol" class="form-control form-control-sm" id="test_protocol">
                        <?php if (!empty($protocol)) { ?>
                          <option value="<?php echo $protocol['protocol_id'] ?>" selected><?php echo $protocol['protocol_name']; ?></option>
                        <?php } ?>
                      </select>
                    </div>

                    <div class="form-group">
                      <label for="">Test Package</label>
                      <select name="test_package" class="form-control form-control-sm" id="test_package">
                        <?php if (!empty($package)) { ?>
                          <option value="<?php echo $package['package_id'] ?>" selected><?php echo $package['package_name']; ?></option>
                        <?php } ?>
                      </select>
                    </div>

                    <div class="form-group">
                      <label for="exampleInputEmail1">Select Currency</label>
                      <select class="form-control select-box" id="country-currency" name="open_trf_currency_id">
                        <option selected="" disabled="">Country Currency</option>
                        <?php foreach ($currency as $value) { ?>
                          <option value="<?php echo $value['currency_id'] ?>" <?php if ($currency_id == $value['currency_id']) {
                                                                                echo "selected";
                                                                              } ?>><?php echo $value['currency_name'] ?></option>
                        <?php } ?>
                      </select>
                      <?php echo form_error('open_trf_currency_id', '<div class="text-danger">', '</div>'); ?>
                    </div>

                    <div class="form-group">
                      <label for="exampleInputEmail1">Exchange Rate</label>
                      <input type="text" class="form-control form-control-sm" id="exchange-rate" placeholder="Exchange Rate" readonly="" name="open_trf_exchange_rate" value="<?php echo $exchange_rate; ?>">
                      <?php echo form_error('open_trf_exchange_rate', '<div class="text-danger">', '</div>'); ?>
                    </div>

                    <div class="form-group">
                      <label for="exampleInputEmail1">Select Product</label>
                      <select class="form-control form-control-sm select-box" id="product" name="trf_product" disabled>
                        <option selected="" disabled="">Product</option>
                        <?php foreach ($products as $value) { ?>
                          <option value="<?php echo $value['sample_type_id'] ?>" <?php if ($product == $value['sample_type_id']) {
                                                                                    echo "selected";
                                                                                  } ?>><?php echo $value['sample_type_name'] ?></option>
                        <?php } ?>
                      </select>
                      <?php echo form_error('trf_product', '<div class="text-danger">', '</div>'); ?>
                      <div id="product_error">Please select currency first</div>
                    </div>

                    <div class="form-group">
                      <label for="exampleInputEmail1">Sample Return To</label>
                      <div class="row">
                        <div class="col-sm-3">
                          <!-- checkbox -->
                          <div class="custom-control custom-checkbox">
                            <input class="custom-control-input" type="checkbox" name="sample_return_to[]" id="srt1" value="Factory" <?php if (in_array("Factory", $sample_return_to)) {
                                                                                                                                      echo "checked";
                                                                                                                                    } ?>>
                            <label for="srt1" class="custom-control-label">Factory</label>
                          </div>
                        </div>
                        <div class="col-sm-3">
                          <!-- checkbox -->
                          <div class="custom-control custom-checkbox">
                            <input class="custom-control-input" type="checkbox" name="sample_return_to[]" id="srt2" value="Buyer" <?php if (in_array("Buyer", $sample_return_to)) {
                                                                                                                                    echo "checked";
                                                                                                                                  } ?>>
                            <label for="srt2" class="custom-control-label">Buyer</label>
                          </div>
                        </div>
                        <div class="col-sm-3">
                          <!-- checkbox -->
                          <div class="custom-control custom-checkbox">
                            <input class="custom-control-input" type="checkbox" name="sample_return_to[]" id="srt3" value="Agent" <?php if (in_array("Agent", $sample_return_to)) {
                                                                                                                                    echo "checked";
                                                                                                                                  } ?>>
                            <label for="srt3" class="custom-control-label">Agent</label>
                          </div>
                        </div>
                        <div class="col-sm-3">
                          <!-- checkbox -->
                          <div class="custom-control custom-checkbox">
                            <input class="custom-control-input" type="checkbox" name="sample_return_to[]" id="srt4" value="ThirdParty" <?php if (in_array("ThirdParty", $sample_return_to)) {
                                                                                                                                          echo "checked";
                                                                                                                                        } ?>>
                            <label for="srt4" class="custom-control-label">ThirdParty</label>
                          </div>
                        </div>
                      </div>
                      <?php echo form_error('sample_return_to[]', '<div class="text-danger">', '</div>'); ?>
                    </div>

                    <div class="form-group">
                      <label for="exampleInputEmail1">Select Division</label>
                      <select class="form-control select-box form-control-sm" name="division">
                        <option selected="" disabled="">Division</option>
                        <?php foreach ($division_list as $value) { ?>
                          <option value="<?php echo $value['division_id'] ?>" <?php if ($division_id == $value['division_id']) {
                                                                                echo "selected";
                                                                              } ?>><?php echo $value['division_name'] ?></option>
                        <?php } ?>
                      </select>
                      <?php echo form_error('division', '<div class="text-danger">', '</div>'); ?>
                    </div>

                    <div class="form-group">
                      <label for="exampleInputEmail1">TAT Date</label>
                      <div class="input-group date" id="reservationdate" data-bs-target-input="nearest">
                        <input type="text" class="form-control datepicker form-control-sm" data-bs-target="#reservationdate" name="tat_date" value="<?php echo $tat_date; ?>">
                        <div class="input-group-append" data-bs-target="#reservationdate" data-bs-toggle="datetimepicker">
                          <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                        </div>
                      </div>
                      <?php echo form_error('tat_date', '<div class="text-danger">', '</div>'); ?>
                    </div>

                    <div class="form-group">
                      <label for="exampleInputEmail1">CRM User List</label>
                      <select class="form-control select-box form-control-sm" multiple name="crm_user_id[]">
                        <?php foreach ($crm_user_list as $value) { ?>
                          <option value="<?php echo $value['uidnr_admin'] ?>" <?php if (in_array($value['uidnr_admin'], $crm_user_id)) {
                                                                                echo "selected";
                                                                              } ?>><?php echo $value['user_name'] ?></option>
                        <?php } ?>
                      </select>
                      <?php echo form_error('crm_user_id[]', '<div class="text-danger">', '</div>'); ?>
                    </div>

                    <div class="form-group">
                      <label for="exampleInputEmail1">Sample Pick Up Service</label>
                      <input type="text" class="form-control form-control-sm" placeholder="Sample Pick Up Service" name="sample_pickup_services" value="<?php echo $sample_pickup_services; ?>">
                      <?php echo form_error('sample_pickup_services', '<div class="text-danger">', '</div>'); ?>
                    </div>

                  </div>

                  <div class="col-md-6">
                    <div class="form-group d-none">
                      <label for="exampleInputPassword1">Select Customer</label>
                      <select class="form-control form-control-sm" id="customer_name" name="open_trf_customer_id">
                        <?php if (!empty($customer_id)) {
                          foreach ($customer_user_id as $value) { ?>
                            <option value="<?php echo $value['id']; ?>" <?php if ($customer_id == $value['id']) {
                                                                          echo "selected";
                                                                        } ?>><?php echo $value['name']; ?></option>
                        <?php }
                        } ?>
                      </select>
                      <?php echo form_error('open_trf_customer_id', '<div class="text-danger">', '</div>'); ?>
                    </div>

                    <div class="form-group">
                      <label for="exampleInputEmail1">Select Buyer Name</label>
                      <select class="form-control form-control-sm" id="buyer" name="trf_buyer">
                        <option selected="" disabled="">Select Buyer Name</option>
                        <?php if (!empty($buyer_id)) {
                          foreach ($buyers as $value) { ?>
                            <option value="<?php echo $value['id'] ?>" <?php if ($value['id'] == $buyer_id) {
                                                                          echo "selected";
                                                                        } ?>><?php echo $value['name'] ?></option>
                        <?php }
                        } ?>
                      </select>
                      <?php echo form_error('trf_buyer', '<div class="text-danger">', '</div>'); ?>
                    </div>

                    <div class="form-group">
                      <label for="exampleInputEmail1">Select Agent</label>
                      <select class="form-control form-control-sm" id="agent" name="trf_agent">
                        <option selected="">Select Agent</option>
                        <?php if (!empty($agent_id)) {
                          foreach ($agent as $value) { ?>
                            <option value="<?php echo $value['id'] ?>" <?php if ($agent_id == $value['id']) {
                                                                          echo "selected";
                                                                        } ?>><?php echo $value['name'] ?></option>
                        <?php }
                        } ?>
                      </select>
                      <?php echo form_error('trf_agent', '<div class="text-danger">', '</div>'); ?>
                    </div>

                    <div class="form-group">
                      <label for="exampleInputEmail1">Select Third Party</label>
                      <select class="form-control form-control-sm" id="third-party" name="trf_thirdparty">
                        <option selected="" disabled="">Select Third Party</option>
                        <?php if (!empty($third_party_id)) {
                          foreach ($third_party as $value) { ?>
                            <option value="<?php echo $value['id'] ?>" <?php if ($third_party_id == $value['id']) {
                                                                          echo "selected";
                                                                        } ?>><?php echo $value['name'] ?></option>
                        <?php }
                        } ?>
                      </select>
                      <?php echo form_error('trf_thirdparty', '<div class="text-danger">', '</div>'); ?>
                    </div>

                    <div class="form-group">
                      <label for="exampleInputEmail1">Sample Description</label>
                      <textarea class="form-control form-control-sm" placeholder="Enter Description" name="trf_sample_desc"><?php echo $sample_descrition; ?></textarea>
                      <?php echo form_error('trf_sample_desc', '<div class="text-danger">', '</div>'); ?>
                    </div>

                    <div class="form-group">
                      <label for="exampleInputEmail1">No. of Sample</label>
                      <input type="number" min="1" class="form-control form-control-sm" placeholder="Number Of Sample" name="trf_no_of_sample" value="<?php echo $no_of_sample; ?>">
                      <?php echo form_error('trf_no_of_sample', '<div class="text-danger">', '</div>'); ?>
                    </div>

                    <div class="form-group">
                      <label for="exampleInputEmail1">BCC</label>
                      <div class="row">
                        <div class="col-sm-3">
                          <!-- checkbox -->
                          <div class="custom-control custom-checkbox">
                            <input class="custom-control-input bcc" name="bcc_id[]" type="checkbox" id="bcc1" value="Factory" <?php if (in_array("Factory", $bcc_type)) {
                                                                                                                                echo "checked";
                                                                                                                              } ?>>
                            <label for="bcc1" class="custom-control-label">Factory</label>
                          </div>
                        </div>
                        <div class="col-sm-3">
                          <!-- checkbox -->
                          <div class="custom-control custom-checkbox">
                            <input class="custom-control-input bcc" name="bcc_id[]" type="checkbox" id="bcc2" value="Buyer" <?php if (in_array("Buyer", $bcc_type)) {
                                                                                                                              echo "checked";
                                                                                                                            } ?>>
                            <label for="bcc2" class="custom-control-label">Buyer</label>
                          </div>
                        </div>
                        <div class="col-sm-3">
                          <!-- checkbox -->
                          <div class="custom-control custom-checkbox">
                            <input class="custom-control-input bcc" name="bcc_id[]" type="checkbox" id="bcc3" value="Agent" <?php if (in_array("Agent", $bcc_type)) {
                                                                                                                              echo "checked";
                                                                                                                            } ?>>
                            <label for="bcc3" class="custom-control-label">Agent</label>
                          </div>
                        </div>
                        <div class="col-sm-3">
                          <!-- checkbox -->
                          <div class="custom-control custom-checkbox">
                            <input class="custom-control-input bcc" name="bcc_id[]" type="checkbox" id="bcc4" value="ThirdParty" <?php if (in_array("ThirdParty", $bcc_type)) {
                                                                                                                                    echo "checked";
                                                                                                                                  } ?>>
                            <label for="bcc4" class="custom-control-label">ThirdParty</label>
                          </div>
                        </div>
                      </div>
                      <?php echo form_error('bcc_id[]', '<div class="text-danger">', '</div>'); ?>
                    </div>

                    <div class="form-group">
                      <label for="exampleInputEmail1">Select BCC Contact</label>
                      <select class="form-control form-control-sm" id="bcc-contact" multiple="" name="trf_bcc[]">
                        <?php if (!empty($bcc_id)) {
                          foreach ($bcc_contact as $value) { ?>
                            <option value="<?php echo $value['id']; ?>" <?php if (in_array($value['id'], $bcc_id)) {
                                                                          echo "selected";
                                                                        } ?>><?php echo $value['name']; ?></option>
                        <?php }
                        } ?>
                      </select>
                      <?php echo form_error('trf_bcc[]', '<div class="text-danger">', '</div>'); ?>
                    </div>

                    <div class="form-group">
                      <label for="exampleInputEmail1">Country Of Destination</label>
                      <select class="form-control select-box form-control-sm" name="trf_country_destination">
                        <option selected="" disabled="">Country Of Destination</option>
                        <?php foreach ($country as $value) { ?>
                          <option value="<?php echo $value['country_id'] ?>" <?php if ($destination_country == $value['country_id']) {
                                                                                echo "selected";
                                                                              } ?>><?php echo $value['country_name'] ?></option>
                        <?php } ?>
                      </select>
                      <?php echo form_error('trf_country_destination', '<div class="text-danger">', '</div>'); ?>
                    </div>

                    <div class="form-group">
                      <label for="exampleInputEmail1">Country Of Origin</label>
                      <select class="form-control select-box form-control-sm" name="trf_country_orgin">
                        <option selected="" disabled="">Country Of Origin</option>
                        <?php foreach ($country as $value) { ?>
                          <option value="<?php echo $value['country_id'] ?>" <?php if ($origin_country == $value['country_id']) {
                                                                                echo "selected";
                                                                              } ?>><?php echo $value['country_name'] ?></option>
                        <?php } ?>
                      </select>
                      <?php echo form_error('trf_country_orgin', '<div class="text-danger">', '</div>'); ?>
                    </div>

                    <div class="form-group">
                      <label for="exampleInputEmail1">End Use</label>
                      <textarea class="form-control form-control-sm" placeholder="End Use" name="trf_end_use"><?php echo $end_use; ?></textarea>
                      <?php echo form_error('trf_end_use', '<div class="text-danger">', '</div>'); ?>
                    </div>

                    <div class="form-group">
                      <label for="exampleInputEmail1">Reported To</label>
                      <div class="row">
                        <div class="col-sm-3">
                          <!-- checkbox -->
                          <div class="custom-control custom-checkbox">
                            <input class="custom-control-input" type="checkbox" name="reported_to[]" id="rt1" value="Factory" <?php if (in_array("Factory", $reported_to)) {
                                                                                                                                echo "checked";
                                                                                                                              } ?>>
                            <label for="rt1" class="custom-control-label">Factory</label>
                          </div>
                        </div>
                        <div class="col-sm-3">
                          <!-- checkbox -->
                          <div class="custom-control custom-checkbox">
                            <input class="custom-control-input" type="checkbox" name="reported_to[]" id="rt2" value="Buyer" <?php if (in_array("Buyer", $reported_to)) {
                                                                                                                              echo "checked";
                                                                                                                            } ?>>
                            <label for="rt2" class="custom-control-label">Buyer</label>
                          </div>
                        </div>
                        <div class="col-sm-3">
                          <!-- checkbox -->
                          <div class="custom-control custom-checkbox">
                            <input class="custom-control-input" type="checkbox" name="reported_to[]" id="rt3" value="Agent" <?php if (in_array("Agent", $reported_to)) {
                                                                                                                              echo "checked";
                                                                                                                            } ?>>
                            <label for="rt3" class="custom-control-label">Agent</label>
                          </div>
                        </div>
                        <div class="col-sm-3">
                          <!-- checkbox -->
                          <div class="custom-control custom-checkbox">
                            <input class="custom-control-input" type="checkbox" name="reported_to[]" id="rt4" value="ThirdParty" <?php if (in_array("ThirdParty", $reported_to)) {
                                                                                                                                    echo "checked";
                                                                                                                                  } ?>>
                            <label for="rt4" class="custom-control-label">ThirdParty</label>
                          </div>
                        </div>
                      </div>
                      <?php echo form_error('reported_to[]', '<div class="text-danger">', '</div>'); ?>
                    </div>

                    <?php if (!empty($trf) && ($trf->trf_status != "Sample Registered")) { ?>
                      <div class="form-group d-none">
                        <label>Test Name and Price</label>
                        <select class="form-control form-control-sm" id="test-name" multiple="" name="griddata[]">
                          <option>Select Test</option>
                          <?php if (!empty($selected_test)) {
                            foreach ($test as $value) { ?>
                              <option value="<?php echo $value['id']; ?>" <?php if (in_array($value['id'], explode(',', $selected_test['id']))) {
                                                                            echo "selected";
                                                                          } ?>><?php echo $value['name']; ?></option>
                            <?php
                            }
                          } elseif (!empty($gridata)) {
                            foreach ($test as $value) { ?>
                              <option value="<?php echo $value['test_id']; ?>" <?php if (in_array($value['test_id'], $gridata)) {
                                                                                  echo "selected";
                                                                                } ?>><?php echo $value['test_name']; ?></option>
                          <?php }
                          } ?>
                        </select>
                        <?php echo form_error('griddata[]', '<div class="text-danger">', '</div>'); ?>
                      </div>
                    <?php } elseif (empty($trf)) { ?>
                      <div class="form-group d-none">
                        <label>Test Name and Price</label>
                        <select class="form-control form-control-sm" id="test-name" multiple="" name="griddata[]">
                          <option>Select Test</option>
                          <?php if (!empty($selected_test)) {
                            foreach ($test as $value) { ?>
                              <option value="<?php echo $value['id']; ?>" <?php if (in_array($value['id'], explode(',', $selected_test['id']))) {
                                                                            echo "selected";
                                                                          } ?>><?php echo $value['name']; ?></option>
                            <?php
                            }
                          } elseif (!empty($gridata)) {
                            foreach ($test as $value) { ?>
                              <option value="<?php echo $value['test_id']; ?>" <?php if (in_array($value['test_id'], $gridata)) {
                                                                                  echo "selected";
                                                                                } ?>><?php echo $value['test_name']; ?></option>
                          <?php }
                          } ?>
                        </select>
                        <?php echo form_error('griddata[]', '<div class="text-danger">', '</div>'); ?>
                      </div>
                    <?php } ?>

                    <div class="form-group">
                      <label>Branch</label>
                      <select name="trf_branch" id="" class="form-control form-control-sm">
                        <option value="" selected disabled>Select</option>
                        <?php foreach ($branchs as $value) { ?>
                          <option value="<?php echo $value->branch_id; ?>" <?php echo (($branch == $value->branch_id) ? 'SELECTED' : ''); ?>><?php echo $value->branch_name; ?></option>
                        <?php } ?>

                      </select>
                    </div>

                    <div class="form-group">
                      <label for="">Sales Person</label>
                      <select name="sales_person" id="sales_person" class="form-control form-control-sm">
                        <?php if (!empty($sales_person_id)) { ?>
                          <option value="<?php echo $sales_person['id']; ?>" <?php if ($sales_person_id == $sales_person['id']) {
                                                                                echo 'selected';
                                                                              } ?>><?php echo $sales_person['name']; ?></option>
                        <?php } ?>
                      </select>
                      <?php echo form_error('sales_person', '<div class="text-danger">', '</div>'); ?>
                    </div>
                    <!-- Added by saurabh on 02-08-2021 -->
                    <div class="form-group">
                      <label for="">Regulation Image</label>
                      <input type="file" name="regulation_image" class="form-control form-control-sm">
                      <?php if (!empty($regulation_image)) { ?>
                        <span><img style="margin:10px 0px" src="<?php echo $regulation_image; ?>" alt="Regulation Image" width="100px" height="100px"></span>
                      <?php } ?>
                    </div>

                    <div class="form-group">
                      <label for="">Regulation Description</label>
                      <textarea name="regulation_desc" class="form-control form-control-sm"><?php echo $regulation_desc; ?></textarea>
                    </div>
                    <!-- Added by saurabh on 02-08-2021 -->
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-12">
                    <div class="card">
                      <div class="card-header">
                        <h3 class="card-title">Custom Fields</h3>
                        <div class="card-tools">
                          <div class="input-group input-group-sm">
                            <!-- <input type="text" name="table_search" class="form-control float-right" placeholder="Search">

                            <div class="input-group-append">
                              <button type="submit" class="btn btn-default"><i class="fas fa-search"></i></button>
                            </div> -->
                            <a href="javascript:void(0)" id="add_custom_field" class="btn btn-primary" style="float: right;">Add</a>
                          </div>
                        </div>
                      </div>
                      <div class="card-body table-responsive p-2">
                        <table id="custom-fields">
                          <?php if (!empty($custom_field[0]) && is_array($custom_field)  &&  (count($custom_field) > 0)) {
                            foreach ($custom_field as $key => $value) {
                              echo $value;
                            }
                          } ?>
                        </table>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Added by Saurabh on 16-05-2022 to select test name and method -->
                <div class="row">
                  <div class="col-md-12">
                    <div class="card">
                      <div class="card-header">
                        <h3 class="card-title">Test and Methods</h3>
                        <div class="card-tools">
                          <div class="input-group input-group-sm">
                            <!-- <input type="text" name="table_search" class="form-control float-right" placeholder="Search">

                            <div class="input-group-append">
                              <button type="submit" class="btn btn-default"><i class="fas fa-search"></i></button>
                            </div> -->
                            <a href="javascript:void(0)" id="add_test_field" class="btn btn-primary" style="float: right;">Add</a>
                          </div>
                        </div>
                      </div>

                      <div class="card-body table-responsive p-2">
                        <?php if ($case == 'add') { ?>
                          <table class="table" id="test_data">
                            <thead>
                              <tr>
                                <th>Test Name</th>
                                <th>Test Method</th>
                                <th>Action</th>
                              </tr>
                            </thead>

                            <input type="hidden" id="row_count" value="<?php (!empty($selected_test)) ? count($selected_test) : 0 ?>">
                            <tbody>
                              <?php if (!empty($selected_test)) {
                                foreach ($selected_test as $key => $test) { ?>
                                  <tr>
                                    <td>
                                      <select name="test[<?php echo $key; ?>][trf_test_test_id]" id="" class="form-control form-control-sm test<?php echo $key; ?>">
                                        <option value="<?php echo $test['test_id']; ?>"><?php echo $test['test_name']; ?></option>
                                      </select>
                                    </td>
                                    <td>
                                      <select name="test[<?php echo $key; ?>][trf_test_test_method_id]" id="" class="form-control form-control-sm method<?php echo $key; ?>">
                                        <option value="<?php echo $test['test_method_id']; ?>"><?php echo $test['test_method_name']; ?></option>
                                      </select>
                                    </td>
                                    <td></td>
                                  </tr>
                              <?php }
                              } ?>
                            </tbody>
                          </table>
                        <?php } ?>
                        <?php if ($case == 'edit') { ?>
                          <table class="table" id="test_data">
                            <thead>
                              <tr>
                                <th>Test Name</th>
                                <th>Test Method</th>
                                <th>Action</th>
                              </tr>
                            </thead>
                            <tbody>
                              <?php if (!empty($selected_test)) {
                                foreach ($selected_test as $key => $test) { ?>
                                  <tr>
                                    <input type="hidden" name="test[<?php echo $key; ?>][trf_test_quote_type]" value="<?php echo $test['trf_test_quote_type']?>">
                                    <input type="hidden" name="test[<?php echo $key; ?>][trf_work_id]" value="<?php echo $test['trf_work_id']?>">
                                    <input type="hidden" name="test[<?php echo $key; ?>][trf_test_protocol_id]" value="<?php echo $test['trf_test_protocol_id']?>">
                                    <input type="hidden" name="test[<?php echo $key; ?>][trf_test_package_id]" value="<?php echo $test['trf_test_package_id']?>">
                                    <input type="hidden" name="test[<?php echo $key; ?>][trf_test_quote_id]" value="<?php echo $test['trf_test_quote_id']?>">
                                    <td>
                                      <select name="test[<?php echo $key; ?>][trf_test_test_id]" id="" class="form-control form-control-sm test<?php echo $key; ?>">
                                        <option value="<?php echo $test['test_id']; ?>"><?php echo $test['test_name']; ?></option>
                                      </select>
                                    </td>
                                    <td>
                                      <select name="test[<?php echo $key; ?>][trf_test_test_method_id]" id="" class="form-control form-control-sm method<?php echo $key; ?>">
                                        <option value="<?php echo $test['test_method_id']; ?>"><?php echo $test['test_method_name']; ?></option>
                                      </select>
                                    </td>
                                    <td><a href="javascript:void(0)" class="btn btn-sm btn-danger remove_test" data-trf_id="<?php echo $test['trf_test_trf_id']; ?>" data-trf_test_id="<?php echo $test['trf_test_id']; ?>">X</a></td>
                                  </tr>
                              <?php }
                              } ?>
                            </tbody>
                          </table>
                        <?php } ?>

                      </div>
                    </div>
                  </div>
                </div>
                <!-- Added by Saurabh on 16-05-2022 to select test name and method -->

                <div class="row">
                  <div class="col-md-12">
                    <div class="card">
                      <div class="card-header">
                        <h3 class="card-title">Application Provided Care Instruction</h3>
                        <div class="card-tools">
                          <div class="input-group input-group-sm">
                            <!-- <input type="text" name="table_search" class="form-control float-right" placeholder="Search">

                            <div class="input-group-append">
                              <button type="submit" class="btn btn-default"><i class="fas fa-search"></i></button>
                            </div> -->
                            <a href="javascript:void(0)" id="add_care_provided_field" class="btn btn-primary" style="float: right;">Add</a>
                          </div>
                        </div>
                      </div>
                      <div class="card-body table-responsive p-2">
                        <table id="care_provided" class="table">
                          <thead>
                            <tr>
                              <th>Application Provided Care Instruction</th>
                              <th>Image</th>
                              <th>Description</th>
                              <th>Image Preference</th>
                              <th>Action</th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php if (!empty($selected_application_care_instruction)) {
                              $i = 0;
                              foreach ($selected_application_care_instruction as $s_care_instruction) { ?>
                                <tr data-row=<?php echo $i; ?>>
                                  <td>
                                    <select class="form-control form-control-sm care_provided" name="dynamic[<?= $i; ?>][application_care_id]">
                                      <option disabled="" selected>Application Provided Care Instruction</option>
                                      <?php foreach ($application_care_instruction as $value) { ?>
                                        <option value="<?php echo $value['instruction_id'] ?>" <?php if ($value['instruction_id'] == $s_care_instruction['application_care_id']) {
                                                                                                  echo "selected";
                                                                                                } ?>><?php echo $value['instruction_name'] ?></option>
                                      <?php } ?>
                                    </select>
                                  </td>
                                  <td class="care_image"><?php if (!empty($s_care_instruction['image'])) { ?><img src="<?php echo $s_care_instruction['image']; ?>" alt="Application Care Provided Image"> <?php } ?></td><input type="hidden" class="application_image" name="dynamic[<?php echo $i; ?>][image]" value="<?php if (!empty($s_care_instruction['image'])) {
                                                                                                                                                                                                                                                                                                                            echo $s_care_instruction['image'];
                                                                                                                                                                                                                                                                                                                          } ?>">
                                  <td><textarea name="dynamic[<?= $i; ?>][description]" class="form-control form-control-sm"><?php echo $s_care_instruction['description']; ?></textarea></td>
                                  <td><input type="text" class="form-control form-control-sm" name="dynamic[<?php echo $i; ?>][image_sequence]" value="<?php echo $s_care_instruction['image_sequence']; ?>"></td>
                                  <td><a href="javascript:void(0)" class="btn btn-danger remove_row">X</a></td>
                                </tr>
                            <?php $i++;
                              }
                            } ?>
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-12">
                    <div class="card">

                      <div class="col-md-12 card-header">
                        <b> Wash Care Instructions</b>
                      </div>
                      <div class="row card-body">
                        <div class="col-md-12">
                          <textarea id="wash_care" name="wash_care" style="width:100%" class="form-control" placeholder="Enter Wash Care Instructions"><?= isset($wash_care) ? $wash_care : ''; ?></textarea>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <!-- /.card-body -->

              <div class="card-footer">
                <button type="button" class="btn btn-danger" onclick="location.href='<?php echo base_url('open-trf-list'); ?>'">Cancel</button>
                <button type="submit" class="btn btn-primary" style="float: right;" id="submit">Submit</button>
              </div>
            </form>
          </div>
          <!-- /.card -->

        </div>
        <!--/.col (left) -->
      </div>
      <!-- /.row -->
    </div><!-- /.container-fluid -->
  </section>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<script>
  $(document).on('change', '#country-currency', function() {
    //open bootsrap modal
    var currency = $('#country-currency').val();

    if (currency === "" || currency == null) {
      $("#product_error").css('display', 'block');
      $('#product').attr('disabled', true);
    } else {
      $('#product').prop('disabled', false);
      $("#product_error").css('display', 'none');
    }
  });

  $(function() {
    var currency = $('#country-currency').val();

    if (currency === "" || currency == null) {
      $("#product_error").css('display', 'block');
      $('#product').attr('disabled', true);
    } else {
      $('#product').prop('disabled', false);
      $("#product_error").css('display', 'none');
    }
  });
</script>

<script>
  $(document).ready(function() {

  });
</script>

<!-- Modal to show error -->
<div class="modal fade" id="currency_alert" tabindex="-1" role="dialog" aria-labelledby="currency_alertTitle" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content" style="width: 20%;margin-left: 27%;">
      <div class="modal-header">
        <h5 class="modal-title" id="currency_alertTitle">Alert</h5>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        Select currency first
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<!-- Script to show dynamic fields -->
<script>
  let row_id = 0;
  let new_col = 0;
  $(document).on('click', '#add_custom_field', function() {
    var last_row = $('#custom-fields tr:last').data('row');
    var row_id = last_row + 1;
    var new_row = "";
    new_row += "<tr data-row='" + row_id + "'>";
    new_row += "<td>";
    new_row += "<input type='text' name='dynamic_field[" + row_id + "][" + new_col + "]' class='form-control form-control-sm'>";
    new_row += "</td>";
    new_col++;
    new_row += "<td>";
    new_row += "<input type='text' name='dynamic_field[" + row_id + "][" + new_col + "]' class='form-control form-control-sm'>";
    new_row += "</td>";
    new_row += "<td>";
    new_row += "<a href='javascript:void(0)' class='btn btn-danger remove_row'>X</a>"
    new_row += "</td>";
    new_col = 0;
    new_row += "</tr>";
    $('#custom-fields').append(new_row);
  });
  <?php if (empty($custom_field[0]) && (count($custom_field[0]) == 0)) { ?>
    $(function() {
      var new_row = "";
      new_row += "<tr data-row='" + row_id + "'>";
      new_row += "<td>";
      new_row += "<input type='text' name='dynamic_field[" + row_id + "][" + new_col + "]' class='form-control form-control-sm'>";
      new_row += "</td>";
      new_col++;
      new_row += "<td>";
      new_row += "<input type='text' name='dynamic_field[" + row_id + "][" + new_col + "]' class='form-control form-control-sm'>";
      new_row += "</td>";
      new_row += "<td>";
      new_row += "&nbsp;"
      new_row += "</td>";
      new_col = 0;
      new_row += "</tr>";
      $('#custom-fields').append(new_row);
    });
  <?php } ?>
  $(document).ready(function() {
    bsCustomFileInput.init();
    $(document).on('click', '.remove_row', function() {
      $(this).parents('tr').remove();
    });
  });
</script>
<!-- Script to show application provided care instruction -->
<script>
  let row_index = 0;
  let col_index = 0;
  let row = "";
  <?php if (empty($selected_application_care_instruction)) { ?>
    $(function() {
      row += "<tr data-row=" + row_index + ">";
      row += "<td>";
      row += '<select class="form-control form-control-sm care_provided" name="dynamic[0][application_care_id]">';
      row += '<option disabled="" selected>Application Provided Care Instruction</option>';
      <?php foreach ($application_care_instruction as $value) { ?>
        row += '<option value="<?php echo $value['instruction_id'] ?>" <?php if (in_array($value['instruction_id'], $care_instruction)) {
                                                                          echo "selected";
                                                                        } ?>><?php echo $value['instruction_name'] ?></option>';
      <?php } ?>
      row += '</select>';
      row += "</td>";
      row += '<td class="care_image"></td><input type="hidden" class="application_image" name="dynamic[0][image]">';
      row += '<td><textarea name="dynamic[0][description]" class="form-control" placeholder="Enter Description"></textarea></td>';
      row += '<td><input type="text" name="dynamic[0][image_sequence]" value="0" class="form-control form-control-sm"></td>';
      row += '<td></td>';
      row += '</tr>';
      row_index++;
      $('#care_provided tbody').append(row);
    });
  <?php } ?>
  $(document).on('click', '#add_care_provided_field', function() {
    var last_row = $('#care_provided tbody tr:last').data('row');
    var row_index = last_row + 1;
    row = "";
    row += "<tr data-row=" + row_index + ">";
    row += "<td>";
    row += '<select class="form-control form-control-sm care_provided" name="dynamic[' + row_index + '][application_care_id]">';
    row += '<option disabled="" selected>Application Provided Care Instruction</option>';
    <?php foreach ($application_care_instruction as $value) { ?>
      row += '<option value="<?php echo $value['instruction_id'] ?>" <?php if (in_array($value['instruction_id'], $care_instruction)) {
                                                                        echo "selected";
                                                                      } ?>><?php echo $value['instruction_name'] ?></option>';
    <?php } ?>
    row += '</select>';
    row += "</td>";
    row += '<td class="care_image"></td><input type="hidden" class="application_image" name="dynamic[' + row_index + '][image]">';
    row += '<td><textarea name="dynamic[' + row_index + '][description]" class="form-control" placeholder="Enter Description"></textarea></td>';
    row += '<td><input type="text" name="dynamic[' + row_index + '][image_sequence]" value="0" class="form-control form-control-sm"></td>';
    row += '<td><a href="javascript:void(0)" class="btn btn-danger remove_row">X</a></td>';
    row += '</tr>';
    row_index++;
    $('#care_provided tbody').append(row);
  });
</script>
<script type="text/javascript">
  $(document).ready(function() {
    // Populate select2 on edit, added by Saurabh on 17-05-2022
    <?php if (!empty($selected_test)) {
      foreach ($selected_test as $key => $value) { ?>
        $('.test<?php echo $key; ?>').select2({
          allowClear: true,
          ajax: {
            url: "<?php echo base_url('test-name'); ?>",
            dataType: 'json',
            data: function(params) {
              return {
                key: params.term, // search term
                product: $('#product').val(),
              };
            },
            processResults: function(response) {

              return {
                results: response
              };
            },
            cache: true
          },
          placeholder: 'Select Test Name',
          minimumInputLength: 0,
          templateResult: formatRepo,
          templateSelection: formatRepoSelection
        });
        $('.method<?php echo $key; ?>').select2({
          allowClear: true,
          ajax: {
            url: "<?php echo base_url('TestRequestForm_Controller/get_test_method'); ?>",
            dataType: 'json',
            data: function(params) {
              return {
                key: params.term, // search term
                test_id: $(this).parent().prev().children().val(),
              };
            },
            processResults: function(response) {

              return {
                results: response
              };
            },
            cache: true
          },
          placeholder: 'Select Test Method',
          minimumInputLength: 0,
          templateResult: formatRepo,
          templateSelection: formatRepoSelection
        });
    <?php }
    } ?>
    // Get Test name
    $("#product").change(function() {
      $('#test-name').val('').trigger("change");
    });
    $('#test-name').select2({
      allowClear: true,
      multiple: true,
      ajax: {
        url: "<?php echo base_url('test-name'); ?>",
        dataType: 'json',
        data: function(params) {
          return {
            key: params.term, // search term
            product: $('#product').val() // search term
          };
        },
        processResults: function(response) {

          return {
            results: response
          };
        },
        cache: true
      },
      placeholder: 'Search for a Test Name',
      minimumInputLength: 0,
      templateResult: formatRepo,
      templateSelection: formatRepoSelection
    });
    // Get Test name ends here

    // Get sales person name
    $('#sales_person').select2({
      allowClear: true,
      ajax: {
        url: "<?php echo base_url('TestRequestForm_Controller/get_sales_person'); ?>",
        dataType: 'json',
        data: function(params) {
          return {
            key: params.term, // search term
            product: $('#product').val() // search term
          };
        },
        processResults: function(response) {

          return {
            results: response
          };
        },
        cache: true
      },
      placeholder: 'Search for a Sales Person',
      minimumInputLength: 0,
      templateResult: formatRepo,
      templateSelection: formatRepoSelection
    });
    // Get sales person name

    // Get Customer name
    $('#customer_type').on('change', function() {
      $('#customer_name').val('').trigger("change");
    });

    $('#customer_name').select2({
      allowClear: true,
      ajax: {
        url: "<?php echo base_url('get-customer'); ?>",
        dataType: 'json',
        data: function(params) {
          return {
            key: params.term, // search term
            customer_type: $('#customer_type').val() // search term
          };
        },
        processResults: function(response) {

          return {
            results: response
          };
        },
        cache: true
      },
      placeholder: 'Search for a Customer Name',
      minimumInputLength: 0,
      templateResult: formatRepo,
      templateSelection: formatRepoSelection
    });
    // Get Customer name ends here

    // Get Buyer name
    $('#buyer').select2({
      allowClear: true,
      ajax: {
        url: "<?php echo base_url('get-buyers'); ?>",
        dataType: 'json',
        data: function(params) {
          return {
            key: params.term, // search term
            applicant: $('#applicant').val() // search term
          };
        },
        processResults: function(response) {

          return {
            results: response
          };
        },
        cache: true
      },
      placeholder: 'Search for a Buyer Name',
      minimumInputLength: 0,
      templateResult: formatRepo,
      templateSelection: formatRepoSelection
    });
    // Get Buyer name ends here

    // Get Contact Person name
    $('#contact-person').select2({
      allowClear: true,
      ajax: {
        url: "<?php echo base_url('get-contact-person'); ?>",
        dataType: 'json',
        data: function(params) {
          return {
            key: params.term, // search term
            applicant: $('#applicant').val() // search term
          };
        },
        processResults: function(response) {

          return {
            results: response
          };
        },
        cache: true
      },
      placeholder: 'Search for a Contact Person Name',
      minimumInputLength: 0,
      templateResult: formatRepo,
      templateSelection: formatRepoSelection
    });
    // Get Contact Person name

    // Get agent name
    $('#agent').select2({
      allowClear: true,
      ajax: {
        url: "<?php echo base_url('TestRequestForm_Controller/get_agent_name'); ?>",
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
      placeholder: 'Search for a agent',
      minimumInputLength: 0,
      templateResult: formatRepo,
      templateSelection: formatRepoSelection
    });
    // Get agent name

    // Get third party name
    $('#third-party').select2({
      allowClear: true,
      ajax: {
        url: "<?php echo base_url('TestRequestForm_Controller/get_third_party'); ?>",
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
      placeholder: 'Search for a third party',
      minimumInputLength: 0,
      templateResult: formatRepo,
      templateSelection: formatRepoSelection
    });
    // Get third party name

    // Get Test Protocol added by saurabh on 13-10-2021
    <?php if (!empty($trf) && $trf->trf_status != 'Sample Registered') { ?>
      $('#test_protocol').select2({
        allowClear: true,
        ajax: {
          url: "<?php echo base_url('TestRequestForm_Controller/get_test_protocol'); ?>",
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
        placeholder: 'Search test protocol',
        minimumInputLength: 0,
        templateResult: formatRepo,
        templateSelection: formatRepoSelection
      });
    <?php } elseif (empty($trf)) { ?>
      $('#test_protocol').select2({
        allowClear: true,
        ajax: {
          url: "<?php echo base_url('TestRequestForm_Controller/get_test_protocol'); ?>",
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
        placeholder: 'Search test protocol',
        minimumInputLength: 0,
        templateResult: formatRepo,
        templateSelection: formatRepoSelection
      });
    <?php } else { ?>
      $('#test_protocol').select2({
        placeholder: 'Search test protocol',
      });
    <?php } ?>
    // Get Test Protocol

    // Get test protocols product added by saurabh on 13-10-2021
    const _tokken = $('meta[name="_tokken"]').attr('value');
    $('#test_protocol').change(function() {
      var protocol_id = $(this).val();
      $.ajax({
        type: "post",
        url: "<?php echo base_url('TestRequestForm_Controller/get_protocol_product_test'); ?>",
        data: {
          _tokken: _tokken,
          protocol_id: protocol_id
        },
        success: function(data) {
          var data = $.parseJSON(data);
          var product = data.product;
          $('#product').html($('<option></option>').attr({
            'value': product.sample_type_id,
            selected: 'selected'
          }).text(product.sample_type_name));

          var test = data.tests;
          // $('#test-name').html('');
          // $.each(test, function(i, v) {
          //   $('#test-name').append($('<option></option>').attr({
          //     'value': v.id,
          //     selected: 'selected'
          //   }).text(v.name));
          // });
          var html = '';
          $.each(test, function(i, v) {
            $('#test_data tbody').empty();
              html += '<tr>';
              html += '<input type="hidden" name="test[' + i + '][trf_test_protocol_id]" value="' + v.protocol_id + '">';
             
              html += '<td><select name="test[' + i + '][trf_test_test_id]" class="form-control">';
              html += '<option value="' + v.test_id + '" selected>' + v.test_name + '</option>';
              html += '</select></td>';
              html += '<td><select name="test[' + i + '][trf_test_test_method_id]" class="form-control">';
              html += '<option value="' + v.test_method_id + '" selected>' + v.test_method_name + '</option>';
              html += '</select></td>';
              html += '<td><a href="javascrip:void(0)" class="btn btn-sm btn-danger remove_test">X</a></td>';
              html += '</tr>';
          });
          $('#test_data tbody').append(html);
        }
      })
    });
    // Get product added by saurabh on 13-10-2021

    // Get Test package added by saurabh on 13-10-2021
    $('#test_package').select2({
      allowClear: true,
      ajax: {
        url: "<?php echo base_url('TestRequestForm_Controller/get_test_package'); ?>",
        dataType: 'json',
        data: function(params) {
          return {
            key: params.term, // search term
            buyer:$('#buyer option:selected').val(), // new 23
          };
        },
        processResults: function(response) {

          return {
            results: response
          };
        },
        cache: true
      },
      placeholder: 'Search test package',
      minimumInputLength: 0,
      templateResult: formatRepo,
      templateSelection: formatRepoSelection
    });
    // Get Test package

    // Get test protocols product added by saurabh on 13-10-2021
    $('#test_package').change(function() {
      var package_id = $(this).val();
      $.ajax({
        type: "post",
        url: "<?php echo base_url('TestRequestForm_Controller/get_package_product_test'); ?>",
        data: {
          _tokken: _tokken,
          package_id: package_id
        },
        success: function(data) {
          var data = $.parseJSON(data);
          var product = data.product;
          $('#product').html($('<option></option>').attr({
            'value': product.sample_type_id,
            selected: 'selected'
          }).text(product.sample_type_name));

          var test = data.tests;
          // $('#test-name').html('');
          // $.each(test, function(i, v) {
          //   $('#test-name').append($('<option></option>').attr({
          //     'value': v.id,
          //     selected: 'selected'
          //   }).text(v.name));
          // });
          var html = '';
          $.each(test, function(i, v) {
            $('#test_data tbody').empty();
              html += '<tr>';
              html += '<input type="hidden" name="test[' + i + '][trf_test_package_id]" value="' + v.test_package_packages_id + '">';
             
              html += '<td><select name="test[' + i + '][trf_test_test_id]" class="form-control">';
              html += '<option value="' + v.test_id + '" selected>' + v.test_name + '</option>';
              html += '</select></td>';
              html += '<td><select name="test[' + i + '][trf_test_test_method_id]" class="form-control">';
              html += '<option value="' + v.test_method_id + '" selected>' + v.test_method_name + '</option>';
              html += '</select></td>';
              html += '<td><a href="javascrip:void(0)" class="btn btn-sm btn-danger remove_test">X</a></td>';
              html += '</tr>';
          });
          $('#test_data tbody').append(html);
        }
      })
    });
    // Get product added by saurabh on 13-10-2021

    // Get Contact Person name
    $(".invoice-to").change(function() {
      $('#cp-invoice-to').val('').trigger("change");
    });

    $('#cp-invoice-to').select2({
      allowClear: true,
      ajax: {
        url: "<?php echo base_url('invoice-contact'); ?>",
        dataType: 'json',
        data: function(params) {
          var invoice_to = $("input[type='radio']:checked").val();
          if (invoice_to == "Factory") {
            var invoice_to_user = $('#applicant').val();
          }
          if (invoice_to == "Buyer") {
            var invoice_to_user = $('#buyer').val();
          }
          if (invoice_to == "Agent") {
            var invoice_to_user = $('#agent');
          }
          if (invoice_to == "ThirdParty") {

            var invoice_to_user = $('#third-party').val();
          }
          return {
            key: params.term, // search term
            user: invoice_to_user // search term
          };
        },
        processResults: function(response) {

          return {
            results: response
          };
        },
        cache: true
      },
      placeholder: 'Search for a contact',
      minimumInputLength: 0,
      templateResult: formatRepo,
      templateSelection: formatRepoSelection
    });
    // Get Contact Person name

    // Get cc contact person name
    $('#cc-contact').select2({
      allowClear: true,
      ajax: {
        url: "<?php echo base_url('cc-contact'); ?>",
        dataType: 'json',
        data: function(params) {
          var cc = $('.cc:checked').map(function() {
            return $(this).val();
          }).get();
          var user = "";
          // Check whether user has selected factory or not
          if (jQuery.inArray("Factory", cc) !== -1) {
            if (user === "") {
              user = $('#applicant').val();
            } else {
              user = user + ',' + $('#applicant').val();
            }
          }
          // Check whether user has selected buyer or not
          if (jQuery.inArray("Buyer", cc) !== -1) {
            if (user === "") {
              user = $('#buyer').val();
            } else {
              user = user + ',' + $('#buyer').val();
            }
          }
          // Check whether user has selected agent or not
          if (jQuery.inArray("Agent", cc) !== -1) {
            if (user === "") {
              user = $('#agent').val();
            } else {
              user = user + ',' + $('#agent').val();
            }
          }
          // Check whether user has selected third party or not
          if (jQuery.inArray("ThirdParty", cc) !== -1) {
            if (user === "") {
              user = $('#third-party').val();
            } else {
              user = user + ',' + $('#third-party').val();
            }
          }
          return {
            key: params.term, // search term
            user: user // search term
          };
        },
        processResults: function(response) {

          return {
            results: response
          };
        },
        cache: true
      },
      placeholder: 'Search for a contact',
      minimumInputLength: 0,
      templateResult: formatRepo,
      templateSelection: formatRepoSelection
    });
    // Get cc contact person name

    // Get bcc contact person name
    $('#bcc-contact').select2({
      allowClear: true,
      ajax: {
        url: "<?php echo base_url('bcc-contact'); ?>",
        dataType: 'json',
        data: function(params) {
          // Check whether user has selected factory or not
          var bcc = $('.bcc:checked').map(function() {
            return $(this).val();
          }).get();
          var user = "";
          // Check whether user has selected factory or not
          if (jQuery.inArray("Factory", bcc) !== -1) {
            if (user === "") {
              user = $('#applicant').val();
            } else {
              user = user + ',' + $('#applicant').val();
            }
          }
          // Check whether user has selected buyer or not
          if (jQuery.inArray("Buyer", bcc) !== -1) {
            if (user === "") {
              user = $('#buyer').val();
            } else {
              user = user + ',' + $('#buyer').val();
            }
          }
          // Check whether user has selected agent or not
          if (jQuery.inArray("Agent", bcc) !== -1) {
            if (user === "") {
              user = $('#agent').val();
            } else {
              user = user + ',' + $('#agent').val();
            }
          }
          // Check whether user has selected third party or not
          if (jQuery.inArray("ThirdParty", bcc) !== -1) {
            if (user === "") {
              user = $('#third-party').val();
            } else {
              user = user + ',' + $('#third-party').val();
            }
          }
          return {
            key: params.term, // search term
            user: user // search term
          };
        },
        processResults: function(response) {

          return {
            results: response
          };
        },
        cache: true
      },
      placeholder: 'Search for a contact',
      minimumInputLength: 0,
      templateResult: formatRepo,
      templateSelection: formatRepoSelection
    });
    // Get bcc contact person name

    // Get bcc contact person name
    $('#applicant').select2({
      allowClear: true,
      ajax: {
        url: "<?php echo base_url('TestRequestForm_Controller/get_applicant'); ?>",
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
      placeholder: 'Search for an applicant',
      minimumInputLength: 0,
      templateResult: formatRepo,
      templateSelection: formatRepoSelection
    });
    // Get bcc contact person name

    $(function() {
      var html = '';
      var process_type = $('.process_type').val();
      let key_test = 0;
      if (process_type == 'add') {
        html += '<tr>';
        html += '<td><select name="test[' + key_test + '][trf_test_test_id]" class="form-control form-control-sm test' + key_test + '"></select></td>';
        html += '<td><select name="test[' + key_test + '][trf_test_test_method_id]" class="form-control form-control-sm method' + key_test + '"></select></td>';
        html += '<td></td>';
        html += '</tr>';

        $('#test_data tbody').html(html);
        $('.test' + key_test).select2({
          allowClear: true,
          ajax: {
            url: "<?php echo base_url('test-name'); ?>",
            dataType: 'json',
            data: function(params) {
              return {
                key: params.term, // search term
                product: $('#product').val(),
              };
            },
            processResults: function(response) {

              return {
                results: response
              };
            },
            cache: true
          },
          placeholder: 'Select Test Name',
          minimumInputLength: 0,
          templateResult: formatRepo,
          templateSelection: formatRepoSelection
        });
        $('.method' + key_test).select2({
          allowClear: true,
          ajax: {
            url: "<?php echo base_url('TestRequestForm_Controller/get_test_method'); ?>",
            dataType: 'json',
            data: function(params) {
              return {
                key: params.term, // search term
                test_id: $(this).parent().prev().children().val(),
              };
            },
            processResults: function(response) {

              return {
                results: response
              };
            },
            cache: true
          },
          placeholder: 'Select Test Method',
          minimumInputLength: 0,
          templateResult: formatRepo,
          templateSelection: formatRepoSelection
        });
        key_test = 1;
        $('#row_count').val(key_test);
      }
    });

    $(document).on('click', '#add_test_field', function() {
      key_test = $('#row_count').val();
      key_test = parseInt(key_test) + 1;
      var html = '';
      html += '<tr>';
      html += '<td><select name="test[' + key_test + '][trf_test_test_id]" class="form-control form-control-sm test' + key_test + '"></select></td>';
      html += '<td><select name="test[' + key_test + '][trf_test_test_method_id]" class="form-control form-control-sm method' + key_test + '"></select></td>';
      html += '<td><a href="javascrip:void(0)" class="btn btn-sm btn-danger remove_test" data-trf_test_id="">X</a></td>';
      html += '</tr>';
      $('#test_data tbody').append(html);
      $('.test' + key_test).select2({
        allowClear: true,
        ajax: {
          url: "<?php echo base_url('test-name'); ?>",
          dataType: 'json',
          data: function(params) {
            return {
              key: params.term, // search term
              product: $('#product').val(),
            };
          },
          processResults: function(response) {

            return {
              results: response
            };
          },
          cache: true
        },
        placeholder: 'Select Test Name',
        minimumInputLength: 0,
        templateResult: formatRepo,
        templateSelection: formatRepoSelection
      });
      $('.method' + key_test).select2({
        allowClear: true,
        ajax: {
          url: "<?php echo base_url('TestRequestForm_Controller/get_test_method'); ?>",
          dataType: 'json',
          data: function(params) {
            return {
              key: params.term, // search term
              test_id: $(this).parent().prev().children().val(),
            };
          },
          processResults: function(response) {

            return {
              results: response
            };
          },
          cache: true
        },
        placeholder: 'Select Test Method',
        minimumInputLength: 0,
        templateResult: formatRepo,
        templateSelection: formatRepoSelection
      });
      key_test = key_test + 1;
      $('#row_count').val(key_test);
    });

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

    $(document).on('click', '.remove_test', function(e) {
      var count = $('#test_data tbody tr').length;
      var trf_test_id = $(this).data('trf_test_id');
      var trf_id = $(this).data('trf_id');
      var self = $(this);
      e.preventDefault();
      $('body').append('<div class="pageloader"></div>');
      if (count < 2) {
        $.notify('Please keep atleast one record!.', "error");
        $('.pageloader').remove();
      } else {
        if (trf_test_id != '') {
          if (confirm('Are you sure to delete this test')) {
            $.ajax({
              type: 'post',
              url: "<?php echo base_url('TestRequestForm_Controller/delete_trf_test'); ?>",
              data: {
                _tokken: _tokken,
                trf_test_id: trf_test_id,
                trf_id: trf_id
              },
              success: function(data) {
                var data = $.parseJSON(data);
                $('.pageloader').remove();
                if (data.status > 0) {
                  self.parents('tr').remove();
                  $.notify(data.message, "success");
                } else {
                  $.notify(data.message, "error");
                }
              }
            })
          }
        } else {
          self.parents('tr').remove();
          $('.pageloader').remove();
          $.notify('Test Removed Successfully.', "success");
        }
      }
    });

  });

  $('#buyer').on('change', function() {
    const _tokken = $('meta[name="_tokken"]').attr('value');
    let buyer = $('#buyer').val();
    $.ajax({
      type: 'post',
      url: "<?php echo base_url('TestRequestForm_Controller/getBuyerCustomFields'); ?>",
      data: {
        _tokken: _tokken,
        buyer: buyer
      },
      success: function(res) {
        var result = $.parseJSON(res);
        // buyer custom fields
        if (result.data != 0) {
          var new_row = "";
          $('#custom-fields tr').remove();
          $.each(result.data, function(index, value) {
            let new_col = 0;
            let new_col1 = 1;
            new_row += "<tr data-row='" + index + "'>";
            new_row += "<td>";
            new_row += "<input type='text' name='dynamic_field[" + index + "][" + new_col + "]' class='form-control form-control-sm' value= " + value.custom_field_name + ">";
            new_row += "</td>";
            new_col++;
            new_row += "<td>";
            new_row += "<input type='text' name='dynamic_field[" + index + "][" + new_col1 + "]' class='form-control form-control-sm'>";
            new_row += "</td>";
            new_row += "<td>";
            new_row += "&nbsp;"
            new_row += "</td>";
            new_col = 0;
            new_row += "</tr>";

          })
          $('#custom-fields').append(new_row);
        }

      }



    })
  })
</script>
