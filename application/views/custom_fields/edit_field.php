<?php
   $checkUser = $this->session->userdata('user_data');
   if (!empty($trf)) {
   
     $id = $trf->trf_id;
     $customer_type = $trf->open_trf_customer_type;
     $customer_id = $trf->open_trf_customer_id;
     /*if ($trf->service_days != "") {
       $service_type = $trf->service_days;
     } else {
       $service_type = $trf->trf_service_type;
     }
     */
   //   $service_type = $trf->service_type_id;
   //   $applicant_id = $trf->trf_applicant;
   //   $buyer_id = $trf->trf_buyer;
   //   $agent_id = $trf->trf_agent;
   //   $third_party_id = $trf->trf_thirdparty;
   //   // $contact_person_id = explode(",", $trf->trf_contact);
   //   if ($trf->trf_contact != '') {
   //     $contact_person_id = explode(",", $trf->trf_contact);
   //   } else {
   //     $contact_person_id = [];
   //   }
   //   $sample_ref = $trf->trf_sample_ref_id;
   //   $sample_descrition = $trf->trf_sample_desc;
   //   $invoice_to = $trf->trf_invoice_to;
   
   //   if ($trf->trf_invoice_to_contact != '') {
   //     $cp_invoice_to_id = explode(",", $trf->trf_invoice_to_contact);
   //   } else {
   //     $cp_invoice_to_id = [];
   //   }
   //   if ($trf->cc_type != '') {
   //     $cc_type = explode(",", $trf->cc_type);
   //   } else {
   //     $cc_type = [];
   //   }
   //   if ($trf->bcc_type != '') {
   //     $bcc_type = explode(",", $trf->bcc_type);
   //   } else {
   //     $bcc_type = [];
   //   }
   //   if ($trf->trf_cc != '') {
   //     $cc_id = explode(",", $trf->trf_cc);
   //   } else {
   //     $cc_id = [];
   //   }
   //   if ($trf->trf_bcc != '') {
   //     $bcc_id = explode(",", $trf->trf_bcc);
   //   } else {
   //     $bcc_id = [];
   //   }
   //   //  $bcc_type = explode(",", $trf->bcc_type);
   //   //  $cc_id = explode(",", $trf->trf_cc);
   //   //  $bcc_id = explode(",", $trf->trf_bcc);
   //   $no_of_sample = $trf->trf_no_of_sample;
   //   $client_ref_no = $trf->trf_client_ref_no;
   //   $destination_country = $trf->trf_country_destination;
   //   $origin_country = $trf->trf_country_orgin;
   //   $currency_id = $trf->open_trf_currency_id;
   //   $exchange_rate = $trf->open_trf_exchange_rate;
   //   $product = $trf->trf_product;
   //   $end_use = $trf->trf_end_use;
   //   if ($trf->sample_return_to != '') {
   //     $sample_return_to = explode(",", $trf->sample_return_to);
   //   } else {
   //     $sample_return_to = [];
   //   }
   //   if ($trf->reported_to != '') {
   //     $reported_to = explode(",", $trf->reported_to);
   //   } else {
   //     $reported_to = [];
   //   }
   //   // $reported_to = explode(",", $trf->reported_to);
   //   $division_id = $trf->division;
   //   $crm_user_id = explode(',', $trf->crm_user_id);
   //   // print_r($crm_user_id); die;
   //   $care_instruction = explode(",", $trf->care_instruction);
   //   $sample_pickup_services = $trf->sample_pickup_services;
   //   $tat_date = $trf->tat_date;
   //   $branch = (set_value('trf_branch') ? set_value('trf_branch') : $trf->trf_branch);
   //   $sales_person_id = $trf->sales_person;
   //   $regulation_desc = $trf->regulation_desc;
   //   $regulation_image = $trf->regulation_image;
   //   $case = 'edit';
   // } else {
   //   $case = 'add';
   // $action = base_url('add-open-trf');
   //   $temp_ref_id = set_value('temp_ref_id');
   //   $customer_type = set_value('trf_customer_type');
   //   $customer_id = set_value('open_trf_customer_id');
   //   $service_type = set_value('trf_service_type');
   //   $applicant_id = set_value('trf_applicant');
   //   $buyer_id = set_value('trf_buyer');
   //   $agent_id = set_value('trf_agent');
   //   $third_party_id = set_value('trf_thirdparty');
   //   if (set_value('trf_contact') != '') {
   //     $contact_person_id = set_value('trf_contact');
   //   } else {
   //     $contact_person_id = [];
   //   }
   //   $sample_ref = set_value('trf_sample_ref_id');
   //   $sample_descrition = set_value('trf_sample_desc');
   //   $invoice_to = set_value('invoice_to');
   
   //   if (set_value('trf_invoice_to_contact') != '') {
   //     $cp_invoice_to_id = set_value('trf_invoice_to_contact');
   //   } else {
   //     $cp_invoice_to_id = [];
   //   }
   
   //   //$cc_type = explode(",", set_value('cc_id'));
   
   //   if (set_value('cc_id') != '') {
   //     $cc_type = set_value('cc_id');
   //   } else {
   //     $cc_type = [];
   //   }
   
   //   //$bcc_type = explode(",", set_value('bcc_id'));
   
   //   if (set_value('bcc_id') != '') {
   //     $bcc_type = set_value('bcc_id');
   //   } else {
   //     $bcc_type = [];
   //   }
   
   //   // $cc_id = explode(",", set_value('trf_cc'));
   //   // print_r(set_value('trf_cc')); die;
   //   if (set_value('trf_cc') != '') {
   //     $cc_id = set_value('trf_cc');
   //   } else {
   //     $cc_id = [];
   //   }
   
   //   //$bcc_id =  explode(",", set_value('trf_bcc'));
   //   if (set_value('trf_bcc') != '') {
   //     $bcc_id = set_value('trf_bcc');
   //   } else {
   //     $bcc_id = [];
   //   }
   
   //   $no_of_sample = set_value('trf_no_of_sample');
   //   $client_ref_no = set_value('trf_client_ref_no');
   //   $destination_country = set_value('trf_country_destination');
   //   $origin_country = set_value('trf_country_orgin');
   //   $currency_id = set_value('open_trf_currency_id');
   //   $exchange_rate = set_value('open_trf_exchange_rate');
   //   $product = set_value('trf_product');
   //   $end_use = set_value('trf_end_use');
   //   //$sample_return_to = explode(",", set_value('sample_return_to'));
   //   if (set_value('sample_return_to') != '') {
   //     $sample_return_to = set_value('sample_return_to');
   //   } else {
   //     $sample_return_to = [];
   //   }
   
   //   //$reported_to = explode(",", set_value('reported_to'));
   //   if (set_value('reported_to') != '') {
   //     $reported_to = set_value('reported_to');
   //   } else {
   //     $reported_to = [];
   //   }
   //   $division_id = set_value('division');
   //   $crm_user_id[] = set_value('crm_user_id');
   //   //$care_instruction = explode(",", set_value('care_instruction'));
   
   //   if (set_value('care_instruction') != '') {
   //     $care_instruction = explode(",", set_value('care_instruction'));
   //   } else {
   //     $care_instruction = [];
   //   }
   
   //   $sample_pickup_services = set_value('sample_pickup_services');
   //   $tat_date = set_value('tat_date');
   //   $gridata = set_value('griddata');
   //   $branch = (set_value('trf_branch') ? set_value('trf_branch') : $checkUser->branch_id);
   //   $sales_person_id = '';
   //   $regulation_desc = '';
   //   $regulation_image = '';
   }
   ?>
<div id="flash-message-area"></div>


<div class="content-wrapper">
   <script src="<?php //echo base_url('assets/js/test_request_form.js') ?>"></script>
   <section class="content-header">
      <div class="container-fluid">
         <div class="row mb-2">
            <div class="col-sm-6">
               <h1>Edit Buyer Fields </h1>
            </div>
            <div class="col-sm-6">
               <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>">Home</a></li>
                  <li class="breadcrumb-item"><a href="<?php echo base_url('Buyer'); ?>">Buyer Fields List</a></li>
                  <li class="breadcrumb-item active">Edit Buyer Fields</li>
               </ol>
            </div>
         </div>
      </div>
      <!-- /.container-fluid -->
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
                     <h3 class="card-title">Edit Buyer Fields</h3>
                  </div>
                  <!-- /.card-header -->
                  <!-- form start -->
                  <form autocomplete="off" name="" id="" role="" method="post" action="<?= base_url('Buyer/update_buyer') ?>" enctype="multipart/form-data">
                     <div class="card-body">
                        <div class="row">
                           <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                           <input type="hidden" name="buyer_field_id"			 value="<?= $buyer_field['buyer_field_id'];?>">
                           <div class="col-md-4">
                              <div class="form-group">
                                <label for="customer_type">Select Customer Type</label>
											<select name="customer_type" class="form-control" required>
												<option value="" disabled <?= set_select('customer_type', '', (!isset($buyer_field['customer_type']) ? true : false)) ?>>Select Customer Type</option>
												<option value="Factory" <?= (isset($buyer_field['customer_type']) && $buyer_field['customer_type'] == 'Factory') ? 'selected' : '' ?>>Factory</option>
												<option value="Buyer" <?= (isset($buyer_field['customer_type']) && $buyer_field['customer_type'] == 'Buyer') ? 'selected' : '' ?>>Buyer</option>
											</select>
											<?= form_error('customer_type', '<div class="text-danger">', '</div>'); ?>
										</div>
                           </div>
									<div class="col-md-4">
										<div class="form-group">
											<label for="exampleInputEmail1">Select Applicant</label>
											<select class="form-control form-control-sm" id="applicant" name="buyer_id">
													<?php if (!empty($applicant)) {
														foreach ($applicant as $value) { ?>
														<option value="<?= $value['id'] ?>" <?= ($applicant_id == $value['id']) ? 'selected' : '' ?>>
															<?= $value['name'] ?>
														</option>
													<?php }
													} ?>
											</select>
											<?= form_error('trf_applicant', '<div class="text-danger">', '</div>'); ?>
										</div>
									</div>
									<div class="col-md-4">
                              <div class="form-group">
                                 <label>Status</label>
                                 <select class="form-control form-control-sm" name="status" required style="height: 38px !important;">
												<option value="" disabled <?= !isset($buyer_field['status']) ? 'selected' : '' ?>>Select Status</option>
												<option value="0" <?= (isset($buyer_field['status']) && $buyer_field['status'] == '0') ? 'selected' : '' ?>>Active</option>
												<option value="1" <?= (isset($buyer_field['status']) && $buyer_field['status'] == '1') ? 'selected' : '' ?>>Inactive</option>
											</select>
                                 <?= form_error('status', '<div class="text-danger">', '</div>') ?>
                              </div>
                           </div>
                        </div>
                        <div class="row">
                           <div class="col-md-12">
                              <div class="card">
                                 <div class="card-header">
                                    <h3 class="card-title">Custom Fields</h3>
                                    <div class="card-tools">
                                       <div class="input-group input-group-sm">
                                          <a href="javascript:void(0)" id="add_custom_field" class="btn btn-primary" style="float: right;">Add</a>
                                       </div>
                                    </div>
                                 </div>
                                 <div class="card-body table-responsive p-2">
                                    <table id="custom-fields">
                                       <?php if (!empty($custom_fields)) : ?>
                                       <?php foreach ($custom_fields as $field) : ?>
                                       <tr>
                                          <td>
                                          	<input type="hidden" name="custom_field_id[]" value="<?= $field['custom_field_id'] ?>">
                                             <input type="text" name="custom_fields[<?= $field['custom_field_id'] ?>]" value="<?= htmlspecialchars($field['custom_field_name']) ?>" class="form-control" />
														</td>
														<td>
															<a href="javascript:void(0);" class="btn btn-danger remove-field" data-id="<?= $field['custom_field_id'] ?>">Remove</a>
														</td>
                                       </tr>
                                       <?php endforeach; ?>
                                       <?php endif; ?>
                                       <?php 
                                          if (!empty($custom_field[0]) && is_array($custom_field)  &&  (count($custom_field) > 0)) {
                                            foreach ($custom_field as $key => $value) {
                                              echo $value;
                                            }
                                          } ?>
                                    </table>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                     <!-- /.card-body -->
                     <div class="card-footer">
                        <button type="button" class="btn btn-danger" onclick="location.href='<?php echo base_url('Buyer'); ?>'">Cancel</button>
                        <button type="submit" class="btn btn-primary" style="float: right;" id="btn_submit">Submit</button>
                     </div>
                  </form>
               </div>
               <!-- /.card -->
            </div>
            <!--/.col (left) -->
         </div>
         <!-- /.row -->
      </div>
      <!-- /.container-fluid -->
   </section>
   <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<!-- Script to show dynamic fields start-->
<script>
   let row_id = 0;
   let new_col = 0;
   
   $(document).on('click', '#add_custom_field', function () {
     let last_row = $('#custom-fields tr:last').data('row');
     if (typeof last_row === 'undefined' || isNaN(last_row)) {
       last_row = 0;
     }
   
     row_id = last_row + 1;
   
     let new_row = "";
     new_row += "<tr data-row='" + row_id + "'>";
     new_row += "<td>";
     new_row += "<input type='text' name='dynamic_field[" + row_id + "][" + new_col + "]' class='form-control form-control-sm' placeholder='Enter field name'>";
     new_row += "</td>";
     new_row += "<td>";
     new_row += "<a href='javascript:void(0)' class='btn btn-danger remove_row'>X</a>";
     new_row += "</td>";
     new_row += "</tr>";
   
     $('#custom-fields').append(new_row);
   });
   
   <?php if (empty($custom_field)) { ?>
     $(function () {
       let new_row = "";
       new_row += "<tr data-row='" + row_id + "'>";
       new_row += "<td>";
       new_row += "<input type='text' name='dynamic_field[" + row_id + "][" + new_col + "]' class='form-control form-control-sm' placeholder='Enter field name'>";
       new_row += "</td>";
       new_row += "<td>&nbsp;</td>";
       new_row += "</tr>";
       $('#custom-fields').append(new_row);
     });
   <?php } ?>
   
   $(document).ready(function () {
     if (typeof bsCustomFileInput !== 'undefined') {
       bsCustomFileInput.init();
     }
   
     $(document).on('click', '.remove_row', function () {
       $(this).closest('tr').remove();
     });
   });
</script>
<!-- Script to show dynamic fields end-->
<script type="text/javascript">
   $(document).ready(function () {
   // When customer type changes, reset customer_name
   $('#customer_type').on('change', function () {
     $('#customer_name').val('').trigger("change");
   });
   
   $('#customer_name').select2({
   allowClear: true,
   ajax: {
   transport: function (params, success, failure) {
   	let customerType = $('#customer_type').val();
   
   	if (!customerType) {
   	// If dropdown not selected, don't send AJAX
   	failure(); 
   	return;
   	}
   
   	$('#pageloader').addClass('pageloader'); // show loader
   
   	$.ajax({
   	url: "<?php echo base_url('get-customer'); ?>",
   	data: {
   		key: params.data.term,
   		customer_type: customerType
   	},
   	dataType: 'json',
   	success: function (data) {
   		$('#pageloader').removeClass('pageloader'); // hide loader
   		success(data);
   	},
   	error: function (xhr) {
   		$('#pageloader').removeClass('pageloader'); // hide loader on error
   		failure(xhr);
   	}
   	});
   },
   processResults: function (response) {
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
   
         
     $('#applicant').select2({
       allowClear: true,
       ajax: {
         url: "<?php echo base_url('buyer/fetch_customer_list'); ?>",
         dataType: 'json',
         data: function(params) {
           return {
             key: params.term, // search term
             customer_type: $('#customer_type').val(),
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
   
   // Template functions
   function formatRepo(repo) {
     if (repo.loading) return repo.text;
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
   });
   
</script>
<script>
$(function () {
    let csrfName = '<?= $this->security->get_csrf_token_name(); ?>';
    let csrfHash = '<?= $this->security->get_csrf_hash(); ?>';

    $.ajaxSetup({
        data: {
            [csrfName]: csrfHash
        }
    });

    $('#custom-fields').on('click', '.remove-field', function () {
        let button = $(this);
        let customFieldId = button.data('id');

        if (confirm("Are you sure you want to delete this field?")) {
            $.ajax({
                url: '<?= base_url("buyer/deletefield") ?>',
                type: 'POST',
                data: {
                    id: customFieldId,
                    [csrfName]: csrfHash
                },
                success: function (res) {
                    let response = JSON.parse(res);
                    if (response.status === 'success') {
                        // $('#row_' + customFieldId).remove();
								window.location.reload();
                    }
                    // ✅ Show Bootstrap alert
                    $('#flash-message-area').html(response.html);
                },
                error: function () {
                    $('#flash-message-area').html('<div class="alert alert-danger">Something went wrong.</div>');
                }
            });
        }
    });
});



</script>
