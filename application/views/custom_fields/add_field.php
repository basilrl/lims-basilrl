<?php
   $checkUser = $this->session->userdata('user_data');
   if (!empty($trf)) {
   
     $id = $trf->trf_id;
     $customer_type = $trf->open_trf_customer_type;
     $customer_id = $trf->open_trf_customer_id;
   }
   ?>
<div class="content-wrapper">
   <script src="<?php echo base_url('assets/js/test_request_form.js') ?>"></script>
   <section class="content-header">
      <div class="container-fluid">
         <div class="row mb-2">
            <div class="col-sm-6">
               <h1>Buyer Fields </h1>
            </div>
            <div class="col-sm-6">
               <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>">Home</a></li>
                  <li class="breadcrumb-item"><a href="<?php echo base_url('Buyer'); ?>">Buyer Fields List</a></li>
                  <li class="breadcrumb-item active">Buyer Fields</li>
               </ol>
            </div>
         </div>
      </div>
   </section>
   <!-- /.container-fluid -->
   <!-- Main content -->
   <section class="content">
      <div class="container-fluid">
         <div class="row">
            <!-- left column -->
            <div class="col-md-12">
               <!-- general form elements -->
               <div class="card card-primary">
                  <div class="card-header">
                     <h3 class="card-title"> Buyer Fields</h3>
                  </div>
                  <!-- /.card-header -->
                  <!-- form start -->
                  <form autocomplete="off" method="post" action="<?= base_url('Buyer/create') ?>" enctype="multipart/form-data">
                     <div class="card-body">
                        <div class="row">
                           <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                           <input type="hidden" class="process_type" value="">
                           <div class="col-md-4">
                              <div class="form-group">
                                 <label for="exampleInputEmail1">Select Customer Type</label>
                                 	<select class="form-control select-box form-control-sm" id="customer_type" name="customer_type" required>
													<option disabled <?= set_value('customer_type') == '' ? 'selected' : '' ?>>Select Customer Type</option>
													<option value="Factory" <?= set_value('customer_type') == 'Factory' ? 'selected' : '' ?>>Factory</option>
													<option value="Buyer" <?= set_value('customer_type') == 'Buyer' ? 'selected' : '' ?>>Buyer</option>
												</select>
                                 <?= form_error('customer_type', '<div class="text-danger">', '</div>') ?>
                              </div>
                           </div>
                           <div class="col-md-4">
                              <div class="form-group">
                                 <label for="exampleInputEmail1">Select Applicant</label>
                                 <select class="form-control form-control-sm" id="applicant" name="buyer_id">
                                    <?php if (!empty($applicant_id)) {
                                       foreach ($applicant as $value) { ?>
                                    <option value="<?php echo $value['id'] ?>" 
                                       <?php if ($applicant_id == $value['id']) { echo "selected";}?>><?php echo $value['name'] ?></option>
                                    <?php }
                                       } ?>
                                 </select>
                                 <?= form_error('buyer_id', '<div class="text-danger">', '</div>') ?>
                              </div>
                           </div>
                           <div class="col-md-4">
                              <div class="form-group">
                                 <label for="exampleInputEmail12">Status</label>
                                 <select class="form-control form-control-sm" style="height: 38px !important;" id="" name="status">
                                    <option value="" disabled selected>Select Status</option>
                                    <option value="0" <?= set_select('status', '0') ?>>Active</option>
                                    <option value="1" <?= set_select('status', '1') ?>>Inactive</option>
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
                                       <?php 
                                          if (!empty($custom_field[0]) && is_array($custom_field)  &&  (count($custom_field) > 0)) {
                                          	foreach ($custom_field as $key => $value) {
                                          		echo $value;
                                          	}
                                          } ?>
                                    </table>
                                    <?= form_error('custom_field', '<div class="text-danger">', '</div>') ?>
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
     new_row += "<input type='text' id='input_box' name='dynamic_field[" + row_id + "][" + new_col + "]' class='form-control form-control-sm' placeholder='Enter field name'>";
     new_row += "<div class='text-danger field-error'></div>";
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
     new_row += "<input type='text' id='input_box' name='dynamic_field[" + row_id + "][" + new_col + "]' class='form-control form-control-sm' placeholder='Enter field name'>";
     new_row += "<div class='text-danger field-error'></div>";
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
   
   // Validate dynamic fields before form submit
   $('form').on('submit', function (e) {
   	let hasError = false;
   
   	$('input[name^="dynamic_field"]').each(function () {
   		const val = $(this).val().trim();
   		if (val === '') {
   			$(this).next('.field-error').text('This field is required');
   			hasError = true;
   		} else {
   			$(this).next('.field-error').text('');
   		}
   	});
   
   	if (hasError) {
   		e.preventDefault(); // Prevent form submission
   	}
   });
   
   });
   $(document).on('input', 'input[name^="dynamic_field"]', function () {
   	$(this).next('.field-error').text('');
   });
</script>
<!-- Script to show dynamic fields end-->
<script type="text/javascript">
	$(document).ready(function () {
		
		// Reset applicant dropdown on customer type change
		$('#customer_type').on('change', function () {
			$('#applicant').val(null).trigger('change');
			$('#applicant').prop('disabled', false);
		});

		const old_customer_type = '<?= set_value('customer_type') ?>';
		const old_applicant_id = '<?= set_value('buyer_id') ?>';

		// Initialize Select2 on applicant dropdown
		$('#applicant').select2({
			allowClear: true,
			placeholder: 'Search for an applicant',
			minimumInputLength: 0,
			ajax: {
				url: "<?= base_url('buyer/fetch_customer_list'); ?>",
				dataType: 'json',
				delay: 250,
				data: function (params) {
					return {
						key: params.term,
						customer_type: $('#customer_type').val()
					};
				},
				processResults: function (response) {
					return {
						results: response
					};
				},
				cache: true
			},
			templateResult: formatRepo,
			templateSelection: formatRepoSelection
		});

		// Select2 formatting functions
		function formatRepo(repo) {
			if (repo.loading) return repo.text;
			return `${repo.name}`;
		}

		function formatRepoSelection(repo) {
			return repo.name || repo.text;
		}

		// ✅ Restore old selected applicant if set
		if (old_customer_type && old_applicant_id) {
			$.ajax({
				url: "<?= base_url('buyer/fetch_customer_list'); ?>",
				dataType: 'json',
				data: {
					key: '',
					customer_type: old_customer_type
				},
				success: function (data) {
					const match = data.find(obj => obj.id == old_applicant_id);
					if (match) {
						const option = new Option(match.name, match.id, true, true);
						$('#applicant').append(option).trigger('change');
					}
				}
			});
		}
	});

</script>
<script>
   $(document).ready(function () {
   
     $(document).on('input change', 'input, select', function () {
       $(this).siblings('.text-danger').text('');
     });
   
     $(document).on('input', 'input[name="dynamic_field[]"]', function () {
       $(this).next('.field-error').text('');
     });
   
   });
</script>
