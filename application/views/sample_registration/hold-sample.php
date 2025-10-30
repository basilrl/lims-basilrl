<!-- modal for hold sample -->
<div class="modal fade" id="hold_sample" tabindex="-1" role="dialog" aria-labelledby="qrdLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content modal-md" style="margin: 0px auto;width:700px;">
      <div class="modal-header">
        <h5 class="modal-title" id="qrdLabel">Sample Hold</h5>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="javascript:void(0);">
        <div class="modal-body">
          <input type="hidden" name="sample_reg_id" id="hold_sample_reg_id" class="hold_sample_reg_id" value="">
          <label for="">Remark</label>

          <select name="hold_remark" id="" class="hold_remark form-control" required>
            <option value="">Select any option</option>
          </select>
          <label for="">Reason</label>
          <textarea name="hold_reason" id="" cols="30" rows="10" class="hold_reason form-control"></textarea>
          <div class="form-group set_qr text-center">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-success hold_sample_submit">Submit</button>
        </div>
      </form>
    </div>
  </div>
</div>
<!-- end -->
<!-- modal for Unhold sample -->
<div class="modal fade" id="unhold_sample" tabindex="-1" role="dialog" aria-labelledby="qrdLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content modal-md" style="margin: 0px auto;width:700px;">
      <div class="modal-header">
        <h5 class="modal-title" id="qrdLabel">UNHOLD SAMPLE</h5>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="javascript:void(0);" class="unhold_sample_submit">
        <div class="modal-body">
          <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
          <input type="hidden" name="sample_reg_id" id="unhold_sample_reg_id" class="unhold_sample_reg_id" value="">
          <label for="">Unhold Reason</label>
          <textarea name="unhold_reason" id="" cols="30" rows="10" class="unhold_reason form-control"></textarea>
          <div class="form-group set_qr text-center">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-success ">Unhold</button>
        </div>
      </form>
    </div>
  </div>
</div>
<!-- end -->
<!-- modal for Hold Reason -->
<div class="modal fade" id="hold_reason" tabindex="-1" role="dialog" aria-labelledby="qrdLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content modal-md"style="width: 600px;margin:0 auto;">
      <div class="modal-header">
        <h5 class="modal-title" id="qrdLabel">Hold Reason</h5>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="javascript:void(0);">
        <div class="modal-body">

          <div class="hold_remark"></div>
          <!-- <div class="holdreason"></div> -->

          <div class="form-group set_qr text-center">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
      </form>
    </div>
  </div>
</div>
<!-- end -->
<!-- modal for unHold Reason -->
<div class="modal fade" id="reason_unhold" tabindex="-1" role="dialog" aria-labelledby="qrdLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content modal-md" style="width: 600px;margin:0 auto;">
      <div class="modal-header">
        <h5 class="modal-title" id="qrdLabel">UnHold Reason</h5>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="javascript:void(0);">
        <div class="modal-body">

          <div class="unhold_reasons"></div>

          <div class="form-group set_qr text-center">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
      </form>
    </div>
  </div>
</div>
<!-- end -->

<script>
  $(document).ready(function() {
    $(document).on('click', '.unhold_sample', function() {
      const _tokken = $('meta[name="_tokken"]').attr('value');

      var id = $(this).attr('data-id');
      $('.unhold_sample_reg_id').val(id);

    });
    $(document).on('click', '.reason_hold', function() {
      const _tokken = $('meta[name="_tokken"]').attr('value');

      var sample_reg_id = $(this).attr('data-id');
      $.ajax({
        url: "<?php echo base_url('Hold_Sample/get_reason') ?>",
        method: "post",
        data: {
          _tokken: _tokken,
          sample_reg_id: sample_reg_id,
        },
        success: function(data) {
          var data = $.parseJSON(data);
          $('.hold_remark').html(data);
        


        }
      })

    });
    $(document).on('click', '.reason_unhold', function() {
      const _tokken = $('meta[name="_tokken"]').attr('value');

      var sample_reg_id = $(this).attr('data-id');
      $.ajax({
        url: "<?php echo base_url('Hold_Sample/get_unhold_reason') ?>",
        method: "post",
        data: {
          _tokken: _tokken,
          sample_reg_id: sample_reg_id,
        },
        success: function(data) {
          var data = $.parseJSON(data);
          $('.unhold_reasons').html(data);

        }
      })

    });
    $(document).on('submit', '.unhold_sample_submit', function(e) {
      e.preventDefault();
      var sample_reg_id = $('.unhold_sample_reg_id').val();
      var unhold_reason = $('.unhold_reason').val();
      const _tokken = $('meta[name="_tokken"]').attr('value');

      $.ajax({
        url: "<?php echo base_url('Hold_Sample/UnHold_sample') ?>",
        method: "post",
        data: {
          _tokken: _tokken,
          sample_reg_id: sample_reg_id,
          unhold_reason: unhold_reason
        },
        success: function(data) {
          var data = $.parseJSON(data);
          if (data.status > 0) {
            $('#unhold_sample').modal('hide');
            // $('.performa_span').html("");
            $.notify(data.msg, 'success');
            window.location.reload();
          } else {
            $.notify(data.msg, 'error');
          }
        }
      })
    });
    $(document).on('click', '.hold_sample', function() {
      var id = $(this).attr('data-id');
      $('#hold_sample_reg_id').val(id);
      $.ajax({
        url: "<?php echo base_url('Hold_Sample/get_hold_status') ?>",
        success: function(data) {
          var data = $.parseJSON(data);

          $.each(data, function(key, value) {
            $(".hold_remark").append("<option value=" + value.status_id + ">" + value.hold_Reason + "</option>");
          });


        }
      })
    });
    $(document).on('click', '.hold_sample_submit', function() {
      const _tokken = $('meta[name="_tokken"]').attr('value');
      var sample_reg_id = $('.hold_sample_reg_id').val();
      var remark = $('.hold_remark').val();
      var hold_Reason = $('.hold_reason').val();
      $.ajax({
        url: "<?php echo base_url('Hold_Sample/Hold_sample') ?>",
        method: "post",
        data: {

          _tokken: _tokken,
          sample_reg_id: sample_reg_id,
          remark: remark,
          hold_Reason: hold_Reason
        },
        success: function(data) {
          var data = $.parseJSON(data);
          if (data.status > 0) {
            $('#hold_sample').modal('hide');
            // $('.performa_span').html("");
            $.notify(data.msg, 'success');
            window.location.reload();
          } else {
            $.notify(data.msg, 'error');
          }
        }
      })
    })
  });
</script>