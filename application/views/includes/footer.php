
  
  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->

  <!--barcode modal-->
  <div class="modal fade" id="view_barcode_modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-header bg-primary">
          <h4 class="modal-title text-white" id="myModalLabel">Barcode Scan Details</h4>
          <button type="button" class="close text-danger" data-bs-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-sm-4">
              <label>Sample IN/OUT :</label>
              <select name="barcode_scan_type" id="in_out" class="form-control">
                <option value="">select...</option>
                <option value="IN">IN</option>
                <option value="OUT">OUT</option>
              </select>
            </div>
          </div>

          <div class="row">
            <div class="col-sm-4">
              <label>Sample Barcode :</label>
              <input autocomplete="off" class="form-control" id="theBarcode">
            </div>
          </div>
        </div>
        <!--<iframe  src="" frameborder="0" height="100%" width="100%">-->
        <div class="modal-body" id="barcode_code_div"> </div>
        <!--</iframe>-->
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
  <!--barcode modal ends here-->



  <!-- Product add modal -->
  <div class="modal fade" id="product-list" tabindex="-1" role="dialog" aria-labelledby="productListLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="productListLabel">Add product</h5>
          <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-3">Select Test</div>
            <div class="col-md-9">
              <div class="form-group">
                <select name="gridata[]" id="product_test" class="form-control select-box">
                  <option disabled="" selected>Select Test</option>
                </select>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary" id="add-test">Add</button>
        </div>
      </div>
    </div>
  </div>
  <!-- Product add modal ends here -->


  <!-- Due status footer start  BACKLOG AJIT -->

  <style>
    @keyframes blinker {
      50% {
        opacity: 0;
      }

    }
    @media (min-width: 768px){
body:not(.sidebar-mini-md) .content-wrapper, body:not(.sidebar-mini-md) .main-footer, body:not(.sidebar-mini-md) .main-header {
    transition: margin-left .3s ease-in-out;
    margin-left:0px;
}
}
    .main-footer {
      margin-bottom: 30px;
      background: #636426;
      color: #fff;
      text-align: center;
    }
  </style>
   <?php if (exist_val('Backlogs_details/get_due_dates_gc_no', $this->session->userdata('permission'))) { ?>
    <div class="due_status_div container-fluid text-left" style="position: fixed;left: 0;bottom: 0;width: 100%;background-color:#DCDCDC;color: black;text-align: center; height:46px;z-index:999; padding-top:8px; padding-bottom:8px">           
        <div class="row" style="width: 100%;">
          <div class="coll"><i class='fas fa-angle-double-left prev_record ml-2' type="button" title="Back" data-offset="0"></i></div>
          <div class="col due_gc text-center mt-1"></div>
          <div class="coll"><i class='fas fa-angle-double-right next_record' type="button" title="Next" data-offset="0"></i></div>
          <div class="coll">
            <button type="button" class="btn btn-sm btn-success ml-2" style="cursor:pointer;" data-bs-toggle="modal" data-bs-target="#view_backlog_details">VIEW ALL</button>
          </div>      
      </div>     
    </div>
  <?php } ?>
  <!-- <?php if (exist_val('Backlogs_details/get_due_dates_gc_no', $this->session->userdata('permission'))) { ?>

    <div class="due_status_div container-fluid text-left" style="position: fixed;left: 0;bottom: 0;width: 100%;background-color:#DCDCDC;color: black;text-align: center; height:46px;z-index:999;padding-top:8px; padding-bottom:8px">

      <button type="button" class="btn btn-sm btn-info" style="cursor:pointer;" data-bs-toggle="modal" data-bs-target="#view_backlog_details">VIEW ALL</button>
      <div style="position: absolute; left: 100px;bottom: 0;width: 100%;">
       
        <div class="row" style="width: 100%;">
          <div class="col-sm-1"><i class='fas fa-angle-double-left prev_record' type="button" title="Back" data-offset="0"></i></div>
          <div class="col-sm-10 due_gc"></div>
          <div class="col-sm-1"><i class='fas fa-angle-double-right next_record' type="button" title="Next" data-offset="5"></i></div>
        </div>


      </div>

    </div>
  <?php } ?> -->

  <div class="modal fade" id="view_backlog_details" tabindex="-1" role="dialog" aria-labelledby="productListLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content" style="height: 100%;width: 900px;margin: 0 auto">
        <div class="modal-header" style="background: #80821f">
          <h5 class="modal-title text-white" id="">TAT LIST</h5>
          <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">

          <iframe class="" src="<?php echo base_url('Backlogs_details/view_due_data') ?>" frameborder="0" width="100%" height="460px"></iframe>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>


  <div class="modal fade" id="view_gc_backlog" tabindex="-1" role="dialog" aria-labelledby="productListLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content" style="height: 100%;">
        <div class="modal-header">
          <h5 class="modal-title" id="">TAT DETAILS</h5>
          <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="table-responsive">
            <h4 class="due_date_blink" style="animation: blinker 1s linear infinite;color:red"></h4>
            <table class="table table-sm sample_details_due">
              <tbody>
              </tbody>
            </table>
          </div>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

  <script>
    $(document).ready(function() {

      const limit = 5;
      
      
     
      $('.due_gc').html("");
      var offset = 0;
      getAuto_gc(limit, offset);

      $('.next_record').on('click', function() {
        var offset = $(this).attr("data-offset");
        new_offset = parseInt(offset) + parseInt(limit);
        $(this).attr("data-offset",new_offset);
        $('.prev_record').attr("data-offset",(new_offset-limit));
        $(".due_gc").hide('slide', {direction: 'left'}, 500);
        getAuto_gc(limit, new_offset);
        $(".due_gc").show('slide', {direction: 'right'}, 500);
  
      });

      $('.prev_record').on('click', function() {
        var offset = $(this).attr("data-offset");
       
        if(offset >= limit){
          new_offset = parseInt(offset) - parseInt(limit);
          $(this).attr("data-offset",new_offset);
          $('.next_record').attr("data-offset",offset);
          $(".due_gc").hide('slide', {direction: 'right'}, 500);
          getAuto_gc(limit, new_offset);
         $(".due_gc").show('slide', {direction: 'left'}, 500);
        }
        
      });

      function getAuto_gc(limit, offset) {

        const _tokken = $('meta[name="_tokken"]').attr("value");
        $.ajax({
          url: "<?php echo base_url('Backlogs_details/get_due_dates_gc_no') ?>",
          method: "POST",
          data: {
            limit: limit,
            offset: offset,
            _tokken: _tokken
          },
          success: function(data) {
            var gc = $.parseJSON(data);
          
            $('.due_gc').html("");
            
            $.each(gc, function(index, value) {
              var gc_no = "<a type='button' style='cursor:pointer;margin-right:10px;color:red' data-bs-toggle='modal' data-bs-target='#view_gc_backlog' class='active_gc' data-id='" + value.sample_reg_id + "'>[" + value.gc_no + "  " + value.due_date + "]</a>";
              $('.due_gc').append(gc_no);

            });
           
          }

        });
        return false;
        // setTimeout(getAuto_gc(limit,offset+limit),5000);
      }


      $(document).on('click', '.active_gc', function() {
        var sample_reg_id = $(this).data("id");
        const _tokken = $('meta[name="_tokken"]').attr("value");
        $.ajax({
          url: "<?php echo base_url('Backlogs_details/get_sample_details_due') ?>",
          method: "POST",
          data: {
            _tokken: _tokken,
            sample_reg_id: sample_reg_id
          },
          success: function(data) {
            var data = $.parseJSON(data);
            $('.sample_details_due tbody').html("");
            $('.due_date_blink').html("");
            var tag = "Due date : " + data.due_date + " !";
            $('.due_date_blink').append(tag);
            var tbody = "<tr>";
            tbody += "<th>Basil Report Number</th>";
            tbody += "<td>" + data.gc_no + "</td>";
            tbody += "<th>Seal Number</th>";
            tbody += "<td>" + data.seal_no + "</td>";
            tbody += "</tr>";

            tbody += "<tr>";
            tbody += "<th>Client</th>";
            tbody += "<td>" + data.client + "</td>";
            tbody += "<th>Collection Time</th>";
            tbody += "<td>" + data.collection_time + "</td>";
            tbody += "</tr>";


            tbody += "<tr>";
            tbody += "<th>Recieved By</th>";
            tbody += "<td>" + data.create_by + "</td>";
            tbody += "<th>Sample Recieved Time</th>";
            tbody += "<td>" + data.received_date + "</td>";
            tbody += "</tr>";

            tbody += "<tr>";
            tbody += "<th>Recieved Quantity</th>";
            tbody += "<td>" + data.qty_received + " " + data.unit_name + "</td>";
            tbody += "<th>Test Specification</th>";
            tbody += "<td>" + data.test_specification + "</td>";
            tbody += "</tr>";

            tbody += "<tr>";
            tbody += "<th>Contact</th>";
            tbody += "<td>" + data.contact + "</td>";
            tbody += "<th>Product</th>";
            tbody += "<td>" + data.sample_type_name + "</td>";
            tbody += "</tr>";

            tbody += "<tr>";
            tbody += "<th>Quantity Description</th>";
            tbody += "<td>" + data.quantity_desc + "</td>";
            tbody += "<th>Retain Sample period</th>";
            tbody += "<td>" + data.sample_retain_period + "</td>";
            tbody += "</tr>";

            tbody += "<tr>";
            tbody += "<th>Sample Description</th>";
            tbody += "<td>" + data.sample_desc + "</td>";
            tbody += "<th>Barcode</th>";
            tbody += "<td><img src='" + data.barcode + "'></td>";
            tbody += "</tr>";

            tbody += "<tr>";
            tbody += "<th>Tat Date</th>";
            tbody += "<td>" + data.tat_date + "</td>";
            tbody += "<th>Sample status</th>";
            tbody += "<td>" + data.status + "</td>";
            tbody += "</tr>";



            $('.sample_details_due tbody').append(tbody);
            $('#view_gc_backlog').modal('show'); // dashboard
          }
        });
      })
    })
  </script>
 

  <!-- Main Footer -->
  <footer class="main-footer">
    Copyright &copy; 2023 Basil Research laboratory private limited. 
    
    
    All rights reserved.
    
  </footer>
  </div>

  <!-- REQUIRED SCRIPTS -->

  <!-- Bootstrap -->
  <!-- <script src="<?php echo base_url('assets/dist/js/test_management.js'); ?>"></script> -->
  <script src="<?php echo base_url('assets/plugins/bootstrap/js/bootstrap.bundle.min.js'); ?>"></script>
  <!-- jquery-validation -->
  <script src="<?php echo base_url('assets/plugins/jquery-validation/jquery.validate.min.js'); ?>"></script>
  <script src="<?php echo base_url('assets/plugins/jquery-validation/additional-methods.min.js'); ?>"></script>
  <!-- overlayScrollbars -->
  <script src="<?php echo base_url('assets/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js'); ?>"></script>
  <!-- AdminLTE App -->
  <script src="<?php echo base_url('assets/dist/js/adminlte.js'); ?>"></script>

  <!-- OPTIONAL SCRIPTS -->
  <script src="<?php echo base_url('assets/dist/js/demo.js'); ?>"></script>
  <!-- Date Picker JS -->
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <!-- PAGE PLUGINS -->
  <!-- jQuery Mapael -->
  <script src="<?php echo base_url('assets/plugins/jquery-mousewheel/jquery.mousewheel.js'); ?>"></script>
  <script src="<?php echo base_url('assets/plugins/raphael/raphael.min.js'); ?>"></script>
  <script src="<?php echo base_url('assets/plugins/jquery-mapael/jquery.mapael.min.js'); ?>"></script>
  <script src="<?php echo base_url('assets/plugins/jquery-mapael/maps/usa_states.min.js') ?>"></script>
  <!-- ChartJS -->
  <script src="<?php echo base_url('assets/plugins/chart.js/Chart.min.js'); ?>"></script>
  <!-- bs-custom-file-input -->
  <script src="<?php echo base_url('assets/plugins/bs-custom-file-input/bs-custom-file-input.min.js'); ?>"></script>
  <!-- PAGE SCRIPTS -->
  <!-- <script src="<?php echo base_url('assets/dist/js/pages/dashboard2.js'); ?>"></script> -->
  <!-- Date time picker -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.full.min.js"></script>
  <!-- Select2 Dropdown JS -->
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
  <!-- Form validation js -->
  <script src="<?php echo base_url('assets/js/validate.js'); ?>"></script>
  <!-- Ajax Call -->
  <!-- <script type="text/javascript" src="<?php echo base_url('assets/js/ajax.js'); ?>"></script> -->

  <!-- Notifier -->
  <script type="text/javascript" src="<?php echo base_url('assets/js/notify.min.js'); ?>"></script>
  <!-- Jobs -->
  <script src="<?php echo base_url('assets/js/jobs.js'); ?>"></script>
  <script type="text/javascript">
    $(document).ready(function() {
      $.notify.defaults({
        position: 'top right',
        style: 'bootstrap'
      });
      <?php if ($this->session->flashdata('success')) { ?>
        $.notify("<?php echo $this->session->flashdata('success'); ?>", "success");
      <?php }
      if ($this->session->flashdata('error')) { ?>
        $.notify("<?php echo $this->session->flashdata('error'); ?>", "error");
      <?php }
      if ($this->session->flashdata('warning')) { ?>
        $.notify("<?php echo $this->session->flashdata('warning'); ?>", "warn");
      <?php }
      if ($this->session->flashdata('info')) { ?>
        $.notify("<?php echo $this->session->flashdata('info'); ?>", "info");
      <?php } ?>
    });
  </script>
  <script type="text/javascript">
    $(document).ready(function() {
      $('.select-box').select2();
    });
    $(function() {
      $('.datetimepicker').datetimepicker({
        format: 'Y-m-d H:i',
      });
    });
  </script>

  <script>
    var dateToday = new Date();
    $(function() {
      $(".datepicker").datepicker({
        minDate: dateToday,
        dateFormat: 'yy-mm-dd'
      });
    });
  </script>
  <script>
    $(function() {
      $("#created_on").datepicker({
        dateFormat: 'yy-mm-dd'
      });
    });
  </script>

  <!--by Kapri 15-01-21-->
  <script>
    function clear_barcode_div() {
      $('#barcode_code_div').empty();
      $('#in_out').val("");
      $('#theBarcode').val("");
    }

    $('#theBarcode').keyup(function(e) {
      $('#barcode_code_div').empty();
      barcode = $(this);
      in_out = $('#in_out').val();

      if ((e.keyCode === 13) && (barcode.val().length > 10)) {
        sendBarcode(barcode.val(), in_out);
      }

    });

    function sendBarcode(b, in_out) {
      $('#barcode_code_div').empty();
      $.ajax({
        url: '<?= base_url('BarcodeScan/Barcode_Details'); ?>',
        type: "get",
        dataType: "JSON",
        data: {

          'barcode': b,
          in_out: in_out
        },
        success: function(result) {
          $('#barcode_code_div').empty().append(result);
        }
      });
    }
  </script>

  </body>

  </html>
