<div class="modal fade" id="import_parameter_modal" tabindex="-1">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">IMPORT PARAMETERS</h5>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-7">
            <a href="<?php echo base_url('assets/sample_csv/SAMPLE_TEST_PARAMETER.csv'); ?>" download class="btn btn-info mb-4">Download ParameterSample Sheet</a>
            <h4 class="mt-3 text-primary">Instructions for Parameter Import:</h4>
            <table class="table table-bordered">
              <thead>
                <tr>
                  <th>PRIORITY ORDER</th>
                  <th>PARAMETER TYPE</th>
                  <th>SHOW IN REPORT</th>
                  <th>MANDATORY</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>Should be an integer.</td>
                  <td>Only allow (Parameter, Sub Parameter, Step, Istomers, Solvent).</td>
                  <td>Only allow (Yes, No).</td>
                  <td>Only allow (Yes, No).</td>
                </tr>
              </tbody>
            </table>
            <h5 class="text-dark"><b>Note: </b> "PARAMETER NAME" and "PRIORITY ORDER" are mandatory fields.</h5>
          </div>
          <div class="col-md-1"></div>
          <div class="col-md-4">
            <span id="import_parameter_msg" class="text-danger"></span>
            <form method="post" id="import_parameter_form" enctype="multipart/form-data">
              <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
              <input type="hidden" name="import_test_id" id="import_test_id" />
              <div class="form-group">
                <div class="input-group">
                  <div class="custom-file">
                    <input type="file" class="custom-file-input" name="import_parameter_file" id="import_parameter_file" accept=".csv" />
                    <label class="custom-file-label" for="import_parameter_file">Choose CSV file</label>
                  </div>
                </div>
              </div>
              <p class="text-center">
                <button type="submit" class="btn btn-primary" id="import_parameter_submit">IMPORT NOW</button>
              </p>
            </form>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">CLOSE</button>
      </div>
    </div>
  </div>
</div>

<script>
    $(document).on('click', '#import_parameter', function() {
        let test_id = $(this).attr('test_id');
        const _tokken = $('meta[name="_tokken"]').attr('value');
        if (test_id.length > 0) {
            $('#import_test_id').val(test_id);
            $('#import_parameter_modal').modal('show');
        }
    });

    $(document).on('submit', '#import_parameter_form', function(e) {
        e.preventDefault();
        const _tokken = $('meta[name="_tokken"]').attr('value');
        let import_test_id = $('#import_test_id').val();
        if (import_test_id.length > 0) {
            if ($.trim($('#import_parameter_file').val()).length < 1) {
                $('#import_parameter_msg').text('Please choose csv file.');
                return false;
            } else {
                let property = document.getElementById('import_parameter_file').files[0];
                let img_name = property.name;
                let extension = img_name.split('.').pop().toLowerCase();
                if ($.inArray(extension, ['csv']) == -1) {
                    $('#import_parameter_msg').text('Invalid file extension!');
                    return false;
                } else {
                    let form_data = new FormData(this);
                    $.ajax({
                        url: '<?php echo site_url("Test_management/Test_master/import_parameter"); ?>',
                        method: 'POST',
                        data: form_data,
                        beforeSend: function() {
                            $('#import_parameter_msg').html('<h6>Importing data, Please wait...</h6>');
                            $('#import_parameter_submit').prop('disabled', true);
                        },
                        dataType: 'json',
                        contentType: false,
                        cache: false,
                        processData: false,
                        success: function(data) {
                            $('#import_parameter_msg').html('');
                            $('#import_parameter_submit').prop('disabled', false);
                            if (data.status > 0) {
                                $('#import_test_id').val('');
                                $.notify(data.message, "success");
                                $('#import_parameter_modal').modal('hide');
                                location.reload();
                            } else {
                                $.notify(data.message, "error");
                            }
                        }
                    });
                }
            }
        }
    });
</script>