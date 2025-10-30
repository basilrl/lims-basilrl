<!-- modal for assigned_asset -->
<div class="modal fade" id="assigned_asset" tabindex="-1" role="dialog" aria-labelledby="assigned_assetLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="assigned_assetLabel">Assigned Asset</h5>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <table class="table">
          <thead>
            <tr>
              <th>SL.No.</th>
              <th>Operation</th>
              <th>Action</th>
              <th>Performed By</th>
              <th>Performed at</th>
            </tr>
          </thead>
          <tbody id="get_assigned_user"></tbody>
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<!-- end -->



<script>
     // assigned asset
     $('.assigned_asset').click(function(e) {
      e.preventDefault();
      $('#get_assigned_user').empty();
      var asset_id = $(this).data('id');
      const _tokken = $('meta[name="_tokken"]').attr("value");
      $.ajax({
        type: 'post',
        url: "<?php echo base_url('AssetManagement/assigned_asset') ?>",
        data: {
          _tokken: _tokken,
          asset_id: asset_id
        },
        success: function(data) {
          var data = $.parseJSON(data);
          if (data) {
            sn = 1;
            $.each(data, function(i, v) {
              var value = '' ;
              var taken_at = new Date(v.taken_at+ ' UTC');
              value += '<tr>';
              value += '<td>' + sn + '</td>';
              value += '<td>' + v.action_taken + '</td>';
              value += '<td>' + v.text + '</td>';
              value += '<td>' + v.taken_by + '</td>';
              value += '<td>' + taken_at.toLocaleString() + '</td>';
              value += '</tr>';
              $('#get_assigned_user').append(value);
              sn++;
            });
          } else {
            var value = '' ;
            value += '<tr>';
            value += '<td colspan="5">';
            value += "<h4> NO RECORD FOUND! </h4>";
            value += "</td>";
            value += "</tr>";
            $('#branch_log').append(value);
          }
        }
      });
      // return false;
    });
    //ends
</script>