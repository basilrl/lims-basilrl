<style>
    /* .table-responsive {
        overflow-x: hidden;
    } */
</style>
<?php 
// print_r($list);die;
?>
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
                <div class="col-sm-3">
                  <input type="text" id="search" class="form-control form-control-sm" value="<?php echo ($search_url != 'NULL') ? $search_url : ''; ?>" placeholder="SEARCH BY GC/TRF NO...">
                </div>
                <div class="col-sm-3 text-center">
                  <a class="btn btn-primary btn-sm" onclick="filter_by()" href="javascript:void(0);">SUBMIT</a>
                </div>
              </div>
              <div class="row">
                <div class="col-sm-6">
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
                <div class="col-sm-3">
                  <!-- <select class="form-control form-control-sm" name="salesrep" id="status">
                                            <option value="">SELECT STATUS</option>
                                            <option value="Sample Accepted">Sample Accepted</option>
                                            <option value="Evaluation Completed">Evaluation Completed</option>
                                        </select> -->
                </div>
                <div class="col-sm-3 text-center">
                  <a class="btn btn-danger btn-sm" href="<?php echo base_url('Manage_lab/record_finding'); ?>">CLEAR</a>
                </div>
              </div>
            </div>
            <div class="card-body">
              <!-- <div class="row"> -->

              <div id="emp_table" class="table table-sm table-hover text-center text-secondary">
                <table class="table">
                  <thead class="thead-light">
                    <tr>
                      <?php
                      $search = NULL;
                      $order = NULL;
                      ?>
                      <th scope="col">SL NO.</a></th>
                      <th scope="col">GC NUMBER</a></th>
                      <th scope="col">CLIENT</a></th>
                      <th scope="col">SAMPLE DESCRIPTION</a></th>
                      <th scope="col">TEST NAME</a></th>
                      <th scope="col">TEST METHOD </a></th>

                      <th scope="col">QUANTITY </a></th>
                      <th scope="col">TRF REFERENCE NUMBER </a></th>
                      <th scope="col">SEAL NO</a></th>
                      <th scope="col">RECEIVED DATE </a></th>
                      <th scope="col">STATUS </a></th>

                      <th scope="col">ACTION</a></th>



                    </tr>
                  </thead>
                  <tbody>
                    <?php (empty($this->uri->segment(8))) ? $sn = 1 : $sn = $this->uri->segment(8) + 1; ?>
                    <?php

                    if ($list) :
                      foreach ($list as $item) : ?>

                        <tr>

                          <th><?= $sn; ?></th>
                          <td><?= $item->gc_no ?></td>
                          <td><?= $item->client ?></td>
                          <td><?= $item->sample_desc ?></td>
                          <td><?= $item->test_name ?></td>
                          <td><?= $item->test_method ?></td>


                          <td><?= $item->qty_received ?></td>
                          <td><?= $item->trf_ref_no ?></td>
                          <td><?= $item->seal_no ?></td>
                          <td><?= $item->lab_completion_date_time ?></td>
                          <td><?= $item->sample_test_status ?></td>
                          <td>

                            <?php if ($item->sample_test_status == 'Record Enter Done' || $item->sample_test_status == 'Retest') { ?>
                              <a class="mark_completed" data-sample_reg_id="<?php echo $item->sample_reg_id; ?>" data-id="<?php echo $item->sample_test_id; ?>"><img src=<?php echo base_url('/assets/images/tick.png'); ?> title="Mark as Completed" style="cursor:pointer;"></a>

                              <a href="<?php echo base_url('/Manage_lab/edit_record_finding/' . base64_encode($item->record_finding_id) . '/' . base64_encode($item->sample_reg_id)); ?>"><img src=<?php echo base_url('/assets/images/edit.png'); ?> title="Edit"></a>
                            <?php } else { ?>
                              <a href="<?php echo base_url('/Manage_lab/open_record_finding/' . base64_encode($item->sample_reg_id) . '/' . base64_encode($item->sample_test_id)); ?>"><img src=<?php echo base_url('/assets/images/save_search.png'); ?> title="Record Finding"></a>
                            <?php }   ?>

                          </td>
                        </tr>
                    <?php $sn++;
                      endforeach;
                    endif; ?>
                    <?php if ($list == NULL) : ?>
                      <tr>
                        <td>NO RECORD FOUND</td>
                      </tr>
                    <?php endif; ?>
                  </tbody>
                </table>
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
        var product = $('#product_id').val();
        var search = $('#search').val();
        var start_date = $('#start_date').val();
        var end_date = $('#end_date').val();
        // var status = $('#status').val();
        base_url += (applicant) ? applicant : 'NULL';
        base_url += '/' + ((product) ? product : 'NULL');
        base_url += '/' + ((search) ? btoa(search) : 'NULL');
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
            }else{
              hide_inputEvent.val('');
            }

        });
    }
  const _tokken = $('meta[name="_tokken"]').attr('value');

  $('document').ready(function(){
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
                        sample_reg_id: sample_reg_id,                    },
                    success: function(result) {
                      if(result){
                        var data = $.parseJSON(result);
                        if(data.status>0){
                          $.notify(data.msg,'success');
                          window.location.reload();
                        }
                        else{
                          $.notify(data.msg,'error');
                        }
                      }
                    }
                });
            }
        });
  })
</script>
