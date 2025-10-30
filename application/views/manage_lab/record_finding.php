<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-12">
          <h1 class="text-primary text-center font-weight-bold">RECORD FINDING LIST</h1>
        </div>
      </div>
      <div class="row">
        <div class="col-sm-12">
          <div class="card">
            <div class="card-header">
              <div class="row mb-3">
                <div class="col-sm-4">
                  <div class="row">
                    <div class="col-sm-6">
                      <input type="text" id="search_gc" class="form-control form-control-sm" value="<?php echo ($search_gc != 'NULL') ? $search_gc : ''; ?>" placeholder="SEARCH BY Basil Report NO...">
                    </div>
                    <div class="col-sm-6">
                      <input type="text" id="search_trf" class="form-control form-control-sm" value="<?php echo ($search_trf != 'NULL') ? $search_trf : ''; ?>" placeholder="SEARCH BY TRF NO...">
                    </div>
                  </div>
                </div>
                <div class="col-sm-3">
                  <input type="text" class="form-control form-control-sm applicant_name" placeholder="ENTER APPLICANT NAME" value="<?php echo ($applicant_name != 'NULL') ? strtoupper($applicant_name) : '' ?>">
                  <input type="hidden" class="applicant_id" id="applicant_id" value="<?php echo ($applicant_id != 'NULL') ? $applicant_id : ''; ?>">
                  <ul class="list-group-item customer_list" style="display:none">
                  </ul>
                </div>
                <div class="col-sm-3">
                  <input type="text" id="" class="form-control form-control-sm product_type" placeholder="ENTER PRODUCT NAME" value="<?php echo ($product_name != 'NULL') ? strtoupper($product_name) : '' ?>">
                  <input type="hidden" class="product_id" id="product_id" value="<?php echo ($product_id != 'NULL') ? $product_id : ''; ?>">
                  <ul class="list-group-item cat_list" style="display:none">
                  </ul>
                </div>

                <div class="col-sm-2 text-center">
                  <a class="btn btn-primary btn-sm" onclick="filter_by()" href="javascript:void(0);">SUBMIT</a>
                </div>
              </div>
              <div class="row">
                <div class="col-sm-2">
                  <select class="form-control form-control-sm" name="" id="divison">
                    <option value="">SELECT DIVISION</option>
                    <?php foreach ($divisions as $key => $value) { ?>
                      <option <?php echo ($divison > 0) ? (($divison == $value->division_id) ? 'SELECTED' : '') : '' ?> value="<?php echo $value->division_id; ?>"><?php echo $value->division_name; ?></option>
                    <?php } ?>
                  </select>
                </div>
                <div class="col-sm-3">
                  <input type="text" class="form-control form-control-sm buyer_name" placeholder="ENTER BUYER NAME" value="<?php echo ($buyer_name != 'NULL') ? strtoupper($buyer_name) : '' ?>">
                  <input type="hidden" class="buyer_id" id="buyer_id" value="<?php echo ($buyer_id != 'NULL') ? $buyer_id : ''; ?>">
                  <ul class="list-group-item buyer_customer_list" style="display:none">
                  </ul>
                </div>
                <div class="col-sm-3">
                  <div class="row">
                    <div class="col-sm-6 text-center">
                      <input id="start_date" value="<?php echo ($start_date != 'NULL') ? $start_date : ''; ?>" placeholder="START DATE" type="text" onfocus="(this.type='date')" class="form-control form-control-sm">
                    </div>
                    <div class="col-sm-6 text-center">
                      <input id="end_date" placeholder="END DATE" value="<?php echo ($end_date != 'NULL') ? $end_date : ''; ?>" type="text" onfocus="(this.type='date')" class="form-control form-control-sm">
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-sm-12 text-center"><label for="">DATE FILTER BY RECIEVED DATE</label></div>
                  </div>
                </div>
                <div class="col-sm-2">

                </div>
                <div class="col-sm-2 text-center">
                  <a class="btn btn-danger btn-sm" href="<?php echo base_url('Manage_lab/record_finding'); ?>">CLEAR</a>
                </div>
              </div>
            </div>
            <div class="card-body">
              <!-- <div class="row"> -->
              <div class="table-responsive">
                <div id="emp_table" class="table table-sm table-hover text-center text-secondary table-bordered">
                  <table class="table">
                    <thead class="thead-light">
                      <tr>
                        <?php
                        $search = NULL;
                        $order = NULL;
                        ?>
                        <th scope="col">SL NO.</a></th>
                        <th scope="col">BASIL REPORT NUMBER</a></th>
                        <th scope="col">CLIENT</a></th>
                        <th scope="col">SAMPLE DESCRIPTION</a></th>
                        <th scope="col">TEST NAME</a></th>
                        <th scope="col">TEST METHOD </a></th>
                        <th scope="col">QUANTITY </a></th>
                        <th scope="col">TRF REFERENCE NUMBER </a></th>
                        <th scope="col">SEAL NO</a></th>
                        <th scope="col">PART NAME</a></th>
                        <th scope="col">PART DESCRIPTION</a></th>
                        <th scope="col">RECEIVED DATE </a></th>
                        <th scope="col">STATUS </a></th>
                        <?php if ((exist_val('Manage_lab/open_record_finding', $this->session->userdata('permission'))) || (exist_val('Manage_lab/mark_completed', $this->session->userdata('permission'))) || (exist_val('Manage_lab/edit_record_finding', $this->session->userdata('permission')))) { ?>
                          <!-- added by millan on 23-02-2021 -->
                          <th scope="col">ACTION</a></th>
                        <?php } ?>
                      </tr>
                    </thead>
                    <tbody>
                      <?php (empty($this->uri->segment(11))) ? $sn = 1 : $sn = $this->uri->segment(11) + 1; ?>
                      <?php

                      if ($list) :
                        foreach ($list as $item) : ?>
                          <tr>
                            <th><?php echo $sn; ?></th>
                            <td><?php echo $item->gc_no ?></td>
                            <td><?php echo $item->client ?></td>
                            <td><?php echo $item->sample_desc ?></td>
                            <td><?php echo $item->test_name ?></td>
                            <td><?php echo $item->test_method ?></td>
                            <td><?php echo $item->qty_received ?></td>
                            <td><?php echo $item->trf_ref_no ?></td>
                            <td><?php echo $item->seal_no ?></td>
                            <td><?php echo getPartsOnRecordFinding('part_name', $item->sample_reg_id, $item->sample_test_id); ?></td>
                            <td><?php echo getPartsOnRecordFinding('parts_desc', $item->sample_reg_id, $item->sample_test_id); ?></td>
                            <td><?php echo change_time($item->lab_completion_date_time, $this->session->userdata('timezone')); ?></td>
                            <td><?php echo $item->sample_test_status ?></td>
                            <td>
                              <?php if ($item->trf_buyer != 0 && $item->buyer_active == 'Active') { ?>
                                <?php if (($item->sample_test_status == 'Record Enter Done' || $item->sample_test_status == 'Retest') && $item->record_finding_id != NULL) { ?>
                                  <!-- updated by saurabh on 07-12-2021 -->
                                  <?php if (exist_val('Manage_lab/mark_completed', $this->session->userdata('permission'))) { ?>
                                    <!-- added by millan on 23-02-2021 -->
                                    <a class="mark_completed" data-sample_reg_id="<?php echo $item->sample_reg_id; ?>" data-id="<?php echo $item->sample_test_id; ?>"><img src=<?php echo base_url('/assets/images/tick.png'); ?> title="Mark as Completed" style="cursor:pointer;"></a>
                                  <?php } ?>
                                  <?php if (exist_val('Manage_lab/edit_record_finding', $this->session->userdata('permission'))) { ?>
                                    <!-- added by millan on 23-02-2021 -->
                                    <a href="<?php echo base_url('/Manage_lab/edit_record_finding/' . base64_encode($item->record_finding_id) . '/' . base64_encode($item->sample_reg_id) . '/' . base64_encode($item->sample_registration_branch_id)); ?>"><img src=<?php echo base_url('/assets/images/edit.png'); ?> title="Edit"></a>
                                  <?php } ?>
                                <?php } else { ?>
                                  <?php if (exist_val('Manage_lab/open_record_finding', $this->session->userdata('permission'))) { ?>
                                    <!-- added by millan on 23-02-2021 -->
                                    <a href="<?php echo base_url('/Manage_lab/open_record_finding/' . base64_encode($item->sample_reg_id) . '/' . base64_encode($item->sample_test_id) . '/' . base64_encode($item->sample_registration_branch_id)); ?>"><img src=<?php echo base_url('/assets/images/save_search.png'); ?> title="Record Finding"></a>
                                  <?php } ?>
                                <?php }   ?>
                              <?php } else { ?>
                                <?php if (($item->sample_test_status == 'Record Enter Done' || $item->sample_test_status == 'Retest') && $item->record_finding_id != NULL) { ?>
                                  <!-- updated by saurabh on 07-12-2021 -->
                                  <?php if (exist_val('Manage_lab/mark_completed', $this->session->userdata('permission'))) { ?>
                                    <!-- added by millan on 23-02-2021 -->
                                    <a class="mark_completed" data-sample_reg_id="<?php echo $item->sample_reg_id; ?>" data-id="<?php echo $item->sample_test_id; ?>"><img src=<?php echo base_url('/assets/images/tick.png'); ?> title="Mark as Completed" style="cursor:pointer;"></a>
                                  <?php } ?>
                                  <?php if (exist_val('Manage_lab/edit_record_finding', $this->session->userdata('permission'))) { ?>
                                    <!-- added by millan on 23-02-2021 -->
                                    <a href="<?php echo base_url('/Manage_lab/edit_record_finding/' . base64_encode($item->record_finding_id) . '/' . base64_encode($item->sample_reg_id) . '/' . base64_encode($item->sample_registration_branch_id)); ?>"><img src=<?php echo base_url('/assets/images/edit.png'); ?> title="Edit"></a>
                                  <?php } ?>
                                <?php } else { ?>
                                  <?php if (exist_val('Manage_lab/open_record_finding', $this->session->userdata('permission'))) { ?>
                                    <!-- added by millan on 23-02-2021 -->
                                    <a href="<?php echo base_url('/Manage_lab/open_record_finding/' . base64_encode($item->sample_reg_id) . '/' . base64_encode($item->sample_test_id) . '/' . base64_encode($item->sample_registration_branch_id)); ?>"><img src=<?php echo base_url('/assets/images/save_search.png'); ?> title="Record Finding"></a>
                                  <?php } ?>
                                <?php } ?>
                              <?php } ?>
                              <a href="javascript:void(0)" data-id="<?php echo $item->sample_reg_id; ?>" class="log_view" data-bs-toggle='modal' data-bs-target='#exampleModal' class="btn btn-sm" title="Log View"><img src="<?php echo base_url('assets/images/log-view.png'); ?>" alt="Log view" width="20px"></a>
                            </td>
                          </tr>
                      <?php $sn++;
                        endforeach;
                      endif; ?>
                      <?php if ($list == NULL) : ?>
                        <tr>
                          <td colspan="14">NO RECORD FOUND</td>
                        </tr>
                      <?php endif; ?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
            <!-- Pagination -->
            <div class="card-footer">
              <div class="row">
                <div class="col-sm-6 "><span><?php echo $links ?></span></div>
                <div class="col-sm-6 text-right">
                  <span><?php echo $result_count; ?></span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
  </section>
</div>

<script>
  function filter_by() {
    var base_url = '<?php echo base_url('Manage_lab/record_finding/'); ?>';
    var applicant = $('#applicant_id').val();
    var buyer_id = $('#buyer_id').val();
    var product = $('#product_id').val();
    var divison = $('#divison').val();
    var search_gc = $('#search_gc').val();
    var search_trf = $('#search_trf').val();
    var start_date = $('#start_date').val();
    var end_date = $('#end_date').val();
    // var status = $('#status').val();
    base_url += ((applicant) ? applicant : 'NULL');
    base_url += '/' + ((buyer_id) ? buyer_id : 'NULL');
    base_url += '/' + ((divison) ? divison : 'NULL');
    base_url += '/' + ((product) ? product : 'NULL');
    base_url += '/' + ((search_gc) ? btoa(search_gc) : 'NULL');
    base_url += '/' + ((search_trf) ? btoa(search_trf) : 'NULL');
    base_url += '/' + ((start_date) ? start_date : 'NULL');
    base_url += '/' + ((end_date) ? end_date : 'NULL');
    // base_url += '/' + ((status) ? btoa(status) : 'NULL');
    location.href = base_url;
  }
  var css = {
    position: "absolute",
    width: "95%",
    "font-size": "12px",
    "z-index": 999,
    "overflow-y": "auto",
    "overflow-x": "hidden",
    "max-height": "200px",
    cursor: "pointer",
  };
  var base_url = $("body").attr("data-url");
  getAutolist(
    "applicant_id",
    "applicant_name",
    "customer_list",
    "customer_li",
    "",
    "customer_name",
    "customer_id as id,customer_name as name",
    "cust_customers"
  );
  getAutolist(
    "buyer_id",
    "buyer_name",
    "buyer_customer_list",
    "customer_li",
    "",
    "customer_name",
    "customer_id as id,customer_name as name",
    "cust_customers"
  );
  getAutolist(
    "product_id",
    "product_type",
    "cat_list",
    "sample_li",
    "",
    "test_name",
    "test_id as id,test_name as name",
    "tests"
  );

  function getAutolist(hide_input, input, ul, li, where, like, select, table) {

    var base_url = $("body").attr("data-url");
    var hide_inputEvent = $("input." + hide_input);
    var inputEvent = $("input." + input);
    var ulEvent = $("ul." + ul);

    inputEvent.focusout(function() {
      ulEvent.fadeOut();
    });

    inputEvent.on("click keyup", function(e) {
      var me = $(this);
      var key = $(this).val();
      var _URL = base_url + "get_auto_list";
      const _tokken = $('meta[name="_tokken"]').attr("value");
      e.preventDefault();
      if (key) {
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
                  'data-id="">NO REORD FOUND</li>'
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
      } else {
        hide_inputEvent.val('');
      }

    });
  }
  const _tokken = $('meta[name="_tokken"]').attr('value');

  $('document').ready(function() {
    $('.mark_completed').on("click", function() {
      var check = confirm("Are you sure you want to Mark as Completed");
      if (check) {
        var id = $(this).attr('data-id');
        var sample_reg_id = $(this).attr('data-sample_reg_id');
        // alert(id);           
        $.ajax({
          url: "<?php echo base_url(); ?>Manage_lab/mark_completed",
          method: 'POST',
          data: {
            _tokken: _tokken,
            sample_test_id: id,
            sample_reg_id: sample_reg_id,
          },
          beforeSend: function() {
            $('body').append('<div id="pageloader" class="pageloader"></div>');
          },
          success: function(result) {
            if (result) {
              var data = $.parseJSON(result);
              if (data.status > 0) {
                $.notify(data.msg, 'success');
                window.location.reload();
              } else {
                $.notify(data.msg, 'error');
              }
              $('#pageloader').removeClass('pageloader');
            }
          }
        });
      }
    });
  })
</script>

<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Sample log</h5>
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
              <th>Old Status</th>
              <th>New Status</th>
              <th>Performed By</th>
              <th>Performed at</th>
            </tr>
          </thead>
          <tbody id="sample_log"></tbody>
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- added by saurabh on 23-03-2021 -->
<script>
  $(document).ready(function() {
    const url = $('body').data('url');
    const _tokken = $('meta[name="_tokken"]').attr('value');
    // Ajax call to get log
    $('.log_view').click(function() {
      $('#sample_log').empty();
      var sample_id = $(this).data('id');
      $.ajax({
        type: 'post',
        url: url + 'SampleRegistration_Controller/get_sample_log',
        data: {
          _tokken: _tokken,
          sample_id: sample_id
        },
        success: function(data) {
          var data = $.parseJSON(data);
          var value = '';
          sno = Number();
          $.each(data, function(i, v) {
            sno += 1;
            var operation = v.operation;
            var old_status = v.old_status;
            var new_status = v.new_status;
            var taken_by = v.taken_by;
            var taken_at = new Date(v.taken_at + ' UTC');
            value += '<tr>';
            value += '<td>' + sno + '</td>';
            value += '<td>' + operation + '</td>';
            value += '<td>' + old_status + '</td>';
            value += '<td>' + new_status + '</td>';
            value += '<td>' + taken_by + '</td>';
            value += '<td>' + taken_at.toLocaleString() + '</td>';
            value += '</tr>';

          });
          $('#sample_log').append(value);
        }
      });
    });
    // ajax call to get log ends here
  });
</script>
<!-- added by saurabh on 23-03-2021 -->