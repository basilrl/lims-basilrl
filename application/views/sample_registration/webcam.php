<script src="<?php echo base_url('webcam/webcam.min.js') ?>"></script>
<script>
Webcam.set({
      width: 320,
      height: 240,
      image_format: 'jpeg',
      jpeg_quality: 90,
    });
 function setup() {
      Webcam.reset();
      Webcam.attach('#my_camera');
    }

    function take_snapshot() {
      // freeze camera so user can preview pic
      Webcam.freeze();

      // swap button sets
      document.getElementById('pre_take_buttons').style.display = 'none';
      document.getElementById('post_take_buttons').style.display = '';
    }

    function cancel_preview() {
      // cancel preview freeze and return to live camera feed
      Webcam.unfreeze();

      // swap buttons back
      document.getElementById('pre_take_buttons').style.display = '';
      document.getElementById('post_take_buttons').style.display = 'none';
    }
    var image = 1;

    function save_photo() {

      Webcam.snap(function(data_uri) {
        // display results in page

        $('#results').append('<div class="col-sm-3 mt-3"><div class="row"><div class="col-sm-12"><input type="hidden" name="web_image[]" value="' + data_uri + '"><img style="width:150px;" src="' + data_uri + '"></div></div><div class="row mt-2"><div class="col-sm-12 text-center"><a href="javascript:void(0);" class="btn btn-sm btn-danger delete_web_image">IMAGE ' + image + '</a></div></div></div>')

        image++;
        // swap buttons back
        document.getElementById('pre_take_buttons').style.display = '';
        document.getElementById('post_take_buttons').style.display = 'none';
      });
    }
    $(document).on('click', '.delete_web_image', function() {
      $(this).parents('.col-sm-3').remove();
    });
  </script>
<script>
    $(document).ready(function() {

      $(document).on('click','.webcam_id',function(){
        var id = $(this).data('one');
        $('#web_upload_image #sample_reg_id').val(id)
      })



      $('#web_upload_image_form').submit(function(e) {
        $('#web_upload_image_form #submit').html('Wait...');
        $('#web_upload_image_form #submit').attr('disabled', 'disabled');
        var self = $(this);
        e.preventDefault();
        $.ajax({
          type: "post",
          processData: false,
          contentType: false,
          cache: false,
          async: false,
          url: url + 'Web_cam/index',
          data: new FormData(this),
          success: function(data) {
            $('.errors_images').remove();
            var data = $.parseJSON(data);
            if (data.status > 0) {
              self.trigger('reset');
              $.notify(data.message, "success");
              $('#web_upload_image').modal('hide');
              window.location.reload();
            } else {
              $.notify(data.message, "error");
              $('#web_upload_image_form #submit').html('Save Changes');
              $('#web_upload_image_form #submit').attr('disabled', false);
            }
            if (data.error) {
              $.each(data.error, function(i, v) {
                $('#upload_sample_image input[name="' + i + '"]').after('<span class="text-danger errors_images">' + v + '</span>');
              });
            }
          }
        });
      });
    });
  </script>

  <!-- Modal to upload sample images -->
  <div class="modal fade" id="web_upload_image" tabindex="-1" role="dialog" aria-labelledby="upload_imageLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content" style="margin: 0px auto;">
        <div class="modal-header">
          <h5 class="modal-title" id="upload_imageLabel">Upload Sample Image</h5>
          <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form method="post" enctype="multipart/form-data" id="web_upload_image_form">
          <div class="modal-body">
            <div class="row">
              <div class="col-sm-6">
                <div class="row mb-4">
                  <div class="col-sm-12 text-center"><input type="button" value="Access Camera" onClick="setup();"></div>
                </div>
                <div class="row">
                  <div class="col-sm-12">
                    <div class="row">
                      <div class="col-sm-12">
                        <div id="my_camera"></div>
                      </div>
                    </div>
                    <div class="row mt-4">
                      <div class="col-sm-12">
                        <div id="pre_take_buttons">
                          <input type=button value="Take Snapshot" onClick="take_snapshot()">
                        </div>
                        <div id="post_take_buttons" style="display:none">
                          <input type=button value="&lt; Take Another" onClick="cancel_preview()">
                          <input type=button value="Save Photo &gt;" onClick="save_photo()" style="font-weight:bold;">
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-sm-6">
                <div>Your captured image's will appear here...</div>
                <div class="row" id="results">

                </div>
              </div>
            </div>
            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
            <input type="hidden" name="sample_reg_id" id="sample_reg_id">
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary" id="submit">Save changes</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  <!-- END WEBCAM IMAGE UPLOAD -->
