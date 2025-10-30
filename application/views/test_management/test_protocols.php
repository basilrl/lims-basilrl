<style>
  .table-responsive {
    overflow-x: hidden;
  }

  .modal-content {
    width: 100%;
    margin: 30px auto;
    /* position:relative; */
  }

  .test_list {

    width: 100%;
    /* position: absolute; */
    font-size: 12px;
    z-index: 1;
    overflow-y: auto;
    overflow-x: hidden;
    max-height: 150px;
    cursor: pointer;
    padding: 0;
    margin: 0 auto;


  }

  .test_table tr th:nth-child(2),
  .test_table tr td:nth-child(2) {
    width: 70%;
  }
</style>

<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>TEST PROTOCOLS LIST</h1>
        </div>
        <div class="col-sm-6">
          <!--            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Simple Tables</li>
            </ol>-->
        </div>
      </div>
      <!-- /.card-header -->

      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <div class="row">
                <div class="col-sm-2">
                  <?php if (exist_val('Test_protocols/add_protocol', $this->session->userdata('permission'))) { ?>
                    <a class="btn btn-sm btn-primary add" href="<?php echo base_url('add_protocol') ?>">Add New</a>
                  <?php } ?>
                </div>

                <div class="col-sm-3">
                  <input class="sample_id" type="hidden" value="<?php echo ($sample_id) ? $sample_id : '' ?>" name="sample_id">
                  <input class="form-control form-control-sm  input-sm sample_name" value="<?php echo ($sample_name) ? $sample_name->sample_type_name : ''; ?>" autocomplete="off" name="sample_name" type="text" placeholder="Select Product">
                  <ul class="list-group-item sample_list" style="display:none">
                  </ul>
                </div>
                <div class="col-sm-2">

                  <select name="" class="form-control form-control-sm type_id">
                    <?php echo ($type_id) ? $type_id : ""; ?>
                    <option value="">Select Protocol Type</option>
                    <option value="1" <?php echo ($type_id == '1') ? "selected" : ""; ?>>Global Standard</option>
                    <option value="2" <?php echo ($type_id == '2') ? "selected" : ""; ?>>Customer Specific</option>
                    <option value="3" <?php echo ($type_id == '3') ? "selected" : ""; ?>>BASIL</option>
                  </select>
                </div>

                <div class="col-sm-2">
                  <input value="<?php echo (($search != 'NULL') ? $search : ""); ?>" id="search" class="form-control form-control-sm" type="text" placeholder="Search" aria-label="Search">
                </div>

                <div class="col-sm-3">
                  <button onclick="searchfilter();" type="button" class="btn btn-sm btn-primary">SEARCH</button>
                  <a class="btn btn-sm btn-primary" href="<?php echo base_url('test_protocols'); ?>">CLEAR</a>
                </div>
              </div>

              <div class="input-group input-group-md" style="width: 150px;">
                <div class="input-group-append">
                </div>
              </div>
            </div>

            <div class="table-responsive">
              <table id="emp_table" class="table-sm table table-hover text-nowrap">
                <thead>
                  <tr>
                    <?php
                    if ($search) {
                      $search = base64_encode($search);
                    } else {
                      $search = "NULL";
                    }
                    if ($sample_id != "") {
                      $sample_id = $sample_id;
                    } else {
                      $sample_id = "NULL";
                    }
                    if ($type_id != "") {
                      $type_id = $type_id;
                    } else {
                      $type_id = "NULL";
                    }
                    if ($order != "") {
                      $order = $order;
                    } else {
                      $order = "NULL";
                    }
                    ?>

                    <th scope="col"><a href="">SL NO.</a></th>
                    <th scope="col"><a href="<?php echo base_url('test_protocols/' . $sample_id . '/' . $type_id . '/' . $search . '/' . 'pto.protocol_name/' . $order) ?>">PROTOCOL NAME</a></th>
                    <th scope="col"><a href="<?php echo base_url('test_protocols/' . $sample_id . '/' . $type_id . '/' . $search . '/' . 'sample.sample_type_name/' . $order) ?>">PRODUCT</a></th>
                    <th scope="col"><a href="<?php echo base_url('test_protocols/' . $sample_id . '/' . $type_id . '/' . $search . '/' . 'pto.protocol_reference/' . $order) ?>">PROTOCOL REFERENCE</a></th>
                    <th scope="col"><a href="<?php echo base_url('test_protocols/' . $sample_id . '/' . $type_id . '/' . $search . '/' . 'pto.protocol_type/' . $order) ?>">PROTOCOL TYPE</a>
                    <th scope="col"><a href="">ACTION</a>

                  </tr>
                </thead>
                <tbody>
                  <?php (empty($this->uri->segment(7))) ? $sn = 1 : $sn = $this->uri->segment(7) + 1; ?>
                  <?php if ($protocol_list) : ?>
                    <?php foreach ($protocol_list as $item) : ?>

                      <tr>
                        <th><?= $sn; ?></th>
                        <td><?= $item->protocol_name ?></td>
                        <td><?= $item->sample_name ?></td>
                        <td><?= $item->protocol_reference ?></td>
                        <td><?= $item->protocol_type ?>
                        <td>
                          <a href="<?=base_url('downloadPdf/'. $item->protocol_id)?>" target="_blank"><img src="<?php echo base_url('assets/images/downloadpdf.png') ?>" title="Download File" alt="Download file" style="height:20px; width: 20px"></a>
                          
                          <?php if (exist_val('Test_protocols/edit_protocol', $this->session->userdata('permission'))) { ?>
                            <a class='btn btn-default btn-sm' href="<?php echo base_url('edit_protocol/' . $item->protocol_id) ?>"><img src="<?php echo base_url('assets/images/mem_edit.png') ?>" title="Edit Protocol"></a>
                          <?php } ?>

                          <?php if (exist_val('Test_protocols/test_list', $this->session->userdata('permission'))) { ?>
                            <button type="button" class="btn btn-sm btn-default add_test" data-id="<?php echo $item->protocol_id; ?>" data-bs-toggle="modal" data-bs-target="#tests">
                              <img src="<?php echo base_url('assets/images/manage_test.png') ?>" title="Manage Test">
                            </button>
                          <?php } ?>

                          <!-- Added by CHANDAN --22-06-2022 -->
                          <?php if (exist_val('Test_protocols/import_test_protocol', $this->session->userdata('permission'))) { ?>
                            <button type="button" class="btn btn-sm btn-default import_test_protocol" title="IMPORT TEST" data-id="<?php echo $item->protocol_id; ?>">
                              <img src="<?php echo base_url('assets/images/page_excel.png') ?>">
                            </button>
                          <?php } ?>
                          <!-- End -->

                          <?php if (exist_val('Test_protocols/get_price_list', $this->session->userdata('permission'))) { ?>
                            <button type="button" class="btn btn-sm btn-default test_price" data-id="<?php echo $item->protocol_id; ?>" data-bs-toggle="modal" data-bs-target="#priceModal" title="Price">
                              <img src="<?php echo base_url('assets/images/price.png') ?>">
                            </button>
                          <?php } ?>

                          <?php
                          if (exist_val('Test_protocols/get_log_data', $this->session->userdata('permission'))) { ?>
                            <a href="javascript:void(0)" data-id="<?php echo $item->protocol_id ?>" class="log_view" data-bs-toggle='modal' data-bs-target='#lo_view_target' class="btn btn-sm" title="Log View"><img src="<?php echo base_url('assets/images/log-view.png'); ?>" alt="Log view" width="20px"></a>
                          <?php
                          } ?>
                        </td>
                      </tr>
                    <?php $sn++;
                    endforeach; ?>
                  <?php endif; ?>
                  <?php if ($protocol_list == NULL) : ?>
                    <tr>
                      <td>NO RECORD FOUND</td>
                    </tr>
                  <?php endif; ?>
                </tbody>
              </table>

            </div>

            <!-- Pagination -->
            <div class="card-header">
              <span><?php echo $links ?></span>
              <span><?php echo $result_count; ?></span>
            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->
        </div>
      </div>
      <!-- /.row -->
    </div><!-- /.container-fluid -->
  </section>
  <!-- /.content -->
</div>

<div class="modal fade" id="import_test_modal" tabindex="-1">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">IMPORT TEST</h5>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-7">
            <a href="<?php echo base_url('assets/sample_csv/SAMPLE_TESTS.csv'); ?>" download class="btn btn-info mb-4">Download Test Sample Sheet</a>
          </div>
          <div class="col-md-1"></div>
          <div class="col-md-4">
            <span id="import_test_msg" class="text-danger"></span>
            <form method="post" id="import_test_form" enctype="multipart/form-data">
              <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
              <input type="hidden" name="import_protocol_id" id="import_protocol_id" />
              <div class="form-group">
                <div class="input-group">
                  <div class="custom-file">
                    <input type="file" class="custom-file-input" name="import_test_file" id="import_test_file" accept=".csv" />
                    <label class="custom-file-label" for="import_test_file">Choose CSV file</label>
                  </div>
                </div>
              </div>
              <p class="text-center">
                <button type="submit" class="btn btn-primary" id="import_test_submit">IMPORT NOW</button>
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
  $(document).on('click', '.import_test_protocol', function() {
    let protocol_id = $(this).attr('data-id');
    const _tokken = $('meta[name="_tokken"]').attr('value');
    if (protocol_id.length > 0) {
      $('#import_protocol_id').val(protocol_id);
      $('#import_test_modal').modal('show');
    }
  });

  $(document).on('submit', '#import_test_form', function(e) {
    e.preventDefault();
    const _tokken = $('meta[name="_tokken"]').attr('value');
    let import_protocol_id = $('#import_protocol_id').val();
    if (import_protocol_id.length > 0) {
      if ($.trim($('#import_test_file').val()).length < 1) {
        $('#import_test_msg').text('Please choose csv file.');
        return false;
      } else {
        let property = document.getElementById('import_test_file').files[0];
        let img_name = property.name;
        let extension = img_name.split('.').pop().toLowerCase();
        if ($.inArray(extension, ['csv']) == -1) {
          $('#import_test_msg').text('Invalid file extension!');
          return false;
        } else {
          let form_data = new FormData(this);
          $.ajax({
            url: '<?php echo site_url("Test_management/Test_protocols/import_test_protocol"); ?>',
            method: 'POST',
            data: form_data,
            beforeSend: function() {
              $('#import_test_msg').html('<h6>Importing data, Please wait...</h6>');
              $('#import_test_submit').prop('disabled', true);
            },
            dataType: 'json',
            contentType: false,
            cache: false,
            processData: false,
            success: function(data) {
              $('#import_test_msg').html('');
              $('#import_test_submit').prop('disabled', false);
              if (data.status > 0) {
                $('#import_protocol_id').val('');
                $.notify(data.message, "success");
                $('#import_test_modal').modal('hide');
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
<!-- End -->


<div class="modal fade" id="lo_view_target" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content" style="max-height: 500px;">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">USER LOG</h5>
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
          <tbody id="table_log"></tbody>
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- test modal -->

<div class="modal fade " id="tests" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Test Lists</h5>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="container-fluid">
          <div class="row">
            <?php if (exist_val('Test_protocols/add_protocol_test', $this->session->userdata('permission'))) { ?>

              <button class="btn btn-sm btn-primary" id="addTest">ADD</button>
            <?php } ?>
          </div>
          <form action="" method="post" id="testForm">
            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">


            <div class="row">
              <div class="table-responsive">

                <table class="table table-sm test_table">
                  <thead>
                    <tr>
                      <th>SL No.</th>
                      <th>Test Name</th>
                      <th>Test Method</th>
                      <th>Action</th>
                    </tr>

                  </thead>

                  <tbody>

                  </tbody>

                </table>

              </div>
            </div>
          </form>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">CLOSE</button>
        <button type="button" class="btn btn-primary add_btn" data-id="" style="display:none;">ADD</button>
        <button type="button" class="btn btn-primary save_Testbtn" data-id="" style="display:inline-block">SAVE</button>
      </div>
    </div>
  </div>
</div>


<!-- price list modal -->



<div class="modal fade " id="priceModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Price Lists</h5>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">

        <!-- <div class="row">
          <button class="btn btn-sm btn-primary" id="addPrice">ADD</button>
        </div> -->
        <form action="" method="post" id="priceForm">
          <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
          <input type="hidden" value="" id="price_package_id" class="price_package_id" name="protocol_id">

          <div class="table-responsive">
            <div class="container">
              <div class="row">
                <div class="col-md-12">

                  <table class="table table-sm price_table ">
                    <thead>
                      <tr>
                        <th>SL No.</th>
                        <th>Country code</th>
                        <th>Currency code</th>
                        <th>Price</th>
                      </tr>
                    </thead>
                    <tbody>


                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>

        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">CLOSE</button>
        <button type="button" class="btn btn-primary savePrice " data-id="" style="display:inline-block">SAVE</button>
      </div>
    </div>
  </div>
</div>

<!-- price list modal end -->
<script src="<?php echo base_url('assets/dist/js/test_management.js'); ?>"></script>
<script>
  $('.test_price').click(function() {
    var protocol_id = $(this).data('id');
    // alert(package_id);
    ($('#price_package_id').val(protocol_id));
  })
</script>


<script>
  function searchfilter() {

    var url = '<?php echo base_url("test_protocols"); ?>';

    var sample_id = $('.sample_id').val();
    var type_id = $('.type_id').val();
    var search = $('#search').val();

    if (sample_id != '') {
      url = url + '/' + sample_id;
    } else {
      url = url + '/NULL';
    }
    if (type_id != '') {
      url = url + '/' + type_id;
    } else {
      url = url + '/NULL';
    }
    if (search != '') {
      url = url + '/' + btoa(search);
    } else {
      url = url + '/NULL';
    }

    window.location.href = url;

  }
</script>
<script>
  $(document).ready(function() {



    // function getAutolist(hide_input,input,ul,li,where,like,select,table)

    function getAutolist(hide_input, input, ul, li, where, like, select, table) {

      var hide_inputEvent = $("input." + hide_input);
      var inputEvent = $(input);
      var ulEvent = $("ul." + ul);

      inputEvent.focusout(function() {
        ulEvent.fadeOut();
      })

      inputEvent.on('keyup', function() {
        var key = $(this).val();
        var _URL = "<?php echo base_url('get_auto_list'); ?>";
        const _tokken = $('meta[name="_tokken"]').attr('value');
        $.ajax({
          url: _URL,
          method: 'POST',
          data: {
            key: key,
            where: where,
            like: like,
            select: select,
            table: table,
            _tokken: _tokken
          },

          success: function(data) {
            var html = $.parseJSON(data);
            ulEvent.fadeIn();

            ulEvent.parent().css("positon", "relative");
            ulEvent.html('');
            if (html) {
              $.each(html, function(index, value) {
                ulEvent.append($('<li class="list-group-item ' + li + '"' + 'data-id=' + value.id + '>' + value.name + '</li>'));
              })
            } else {
              ulEvent.append($('<li class="list-group-item ' + li + '"' + 'data-id="">NO RECORD FOUND</li>'));
            }

            var liEvent = $("li." + li);
            liEvent.click(function() {
              var id = $(this).attr('data-id');
              var name = $(this).text();
              inputEvent.val(name);
              hide_inputEvent.val(id);
              ulEvent.fadeOut();

            })



            // ****
          }
        })
      })

    }
    $(document).on('click', '.test_name', function() {
      getAutolist('test_id', this, 'test_list', 'test_li', '', 'test_name', 'test_id as id,CONCAT(test_name,"(",test_method,")") as name', 'tests');
    });





  })

  $(document).ready(function() {
    $('#addTest').click(function() {
      var test_package_test_id = $('.test_id').val();
      if (test_package_test_id != "") {
        var sl = $('.test_table tbody').children("tr").length;
        var delIcon = '<?php echo base_url('assets/images/delete.png') ?>';
        var dumy_tr = '<tr><td class="sl_no">' + (sl + 1) + '</td><td><div class="row "><div class="col-sm-12"><input class="test_id" type="hidden" value="" name=""><input class="form-control input-sm test_name" value="" autocomplete="off" name="test_name" type="text" placeholder="Type Test Name..."></div></div><div class="row"><div class="col-sm-12"><ul class="list-group-item test_list" style="display:none"></ul></div></div></td><td><a type="button" class="deleteTest"><img src="' + delIcon + '" title="Delete test" height="20px" width="20px"></i></a></td><td><input type="hidden" name="row[' + (sl + 1) + '][test_package_test_id]" value=""><input type="hidden" name="row[' + (sl + 1) + '][test_package_id]" value=""><input type="hidden" name="row[' + (sl + 1) + '][test_package_packages_id]" value=""><input type="hidden" name="row[' + (sl + 1) + '][package_test_method]" value=""><input type="hidden" class="sort_order" name="row[' + (sl + 1) + '][package_test_sort_order]" value="' + (sl + 1) + '"></td></tr>';
        $('.test_table tbody').append(dumy_tr);
        $('.add_btn').css("display", "inline-block");
        $('.save_Testbtn').css("display", "none");
      } else {
        alert('Please Select Atleast One Test');
      }
    })

    $(document).on('click', '.deleteTest', function() {
      $(this).parents("tr").remove();
      $('.add_btn').css("display", "none");
      $('.save_Testbtn').css("display", "inline-block");
      serial_reset();
    })

    function serial_reset() {
      var sl = 1;
      var tr = $('.sl_no');
      var sort_order = $('.sort_order');
      $.each(tr, function(i, v) {
        tr[i].innerHTML = sl;
        sort_order[i].value = sl;
        sl++;
      })
    }




    var sl = 1;
    $('.add_test').click(function() {
      var protocol_id = $(this).attr("data-id")
      $('.add_btn').data('id', protocol_id);
      const _tokken = $('meta[name="_tokken"]').attr('value');
      getTestlists(protocol_id, _tokken);
    })

    var sl = 1;

    function getTestlists(protocol_id, _tokken) {
      var sl_no = 1;
      $.ajax({
        url: "<?php echo base_url('Test_management/Test_protocols/test_list') ?>",
        method: "POST",
        data: {
          protocol_id: protocol_id,
          _tokken: _tokken
        },
        success: function(data) {

          var html = $.parseJSON(data);
          // console.log(html);
          $('.test_table tbody').html("");
          var delIcon = '<?php echo base_url('assets/images/delete.png') ?>';
          var upIcon = '<?php echo base_url('assets/images/arrow_up.png') ?>'
          var downIcon = '<?php echo base_url('assets/images/arrow_down.png') ?>'
          $.each(html, function(index, value) {
            $('.test_table tbody').append($("<tr><td class='sl_no'>" + sl_no + "</td><td>" + value.test_name + "</td><td>" + value.test_method + "</td><td><a type='button' class='deleteTest'><img src='" + delIcon + "' title='Delete test' height='20px' width='20px'></i></a></button><button type='button' class='up btn btn-sm'><img src='" + upIcon + "' title='Up order'></button><button type='button' class='down btn btn-sm'><img src='" + downIcon + "' title='Down order'></button></td><td><input type='hidden' name='row[" + sl + "][protocol_test_id]' value='" + value.protocol_test_id + "'><input type='hidden' name='row[" + sl + "][protocol_tests_id]' value='" + value.protocol_tests_id + "'><input type='hidden' name='row[" + sl + "][protocol_id]' value='" + protocol_id + "'><input type='hidden' name='row[" + sl + "][protocol_test_method]' value='" + value.test_method + "'><input type='hidden' class='sort_order' name='row[" + sl + "][protocol_test_sort_order]' value='" + sl_no + "'></td></tr>"));
            sl++;
            sl_no++;
            // order_set();
          })

        }
      })
    }

    $('.save_Testbtn').click(function() {
      const _tokken = $('meta[name="_tokken"]').attr('value');
      $.ajax({
        url: "<?php echo base_url('Test_management/Test_protocols/save_test_protocols') ?>",
        method: "post",
        data: $('#testForm').serialize(),
        success: function(data) {
          var html = $.parseJSON(data);
          if (html.status > 0) {
            $.notify(html.msg, 'success');
            $('#tests').modal('hide');
          } else {
            $.notify(html.msg, 'error');
          }
        }
      })
    })

    $(document).on('click', '.up,.down', function() {
      var $element = this;
      var row = $($element).parents("tr:first");

      if ($(this).is('.up')) {
        row.insertBefore(row.prev());
        serial_reset();
        //  order_set();
      } else {
        row.insertAfter(row.next());
        serial_reset();
        // order_set();

      }
    })



    $('.add_btn').click(function() {
      var protocol_id = $(this).data('id');
      var protocol_test_id = $('.test_id').val();
      var sl = $('.test_table tbody').children("tr").length;
      var order = sl + 1;
      const _tokken = $('meta[name="_tokken"]').attr('value');
      $.ajax({
        url: '<?php echo base_url("Test_management/Test_protocols/add_protocol_test") ?>',
        method: 'post',
        data: {
          protocol_id: protocol_id,
          protocol_test_id: protocol_test_id,
          protocol_test_sort_order: order,
          _tokken: _tokken
        },
        success: function(data) {
          var msg = $.parseJSON(data);
          if (msg.status > 0) {
            getTestlists(protocol_id, _tokken);
            $.notify(msg.msg, 'success');
            $('.add_btn').css("display", "none");
            $('.save_Testbtn').css("display", "inline-block");
            // $('#tests').modal('hide');
          } else {
            $.notify(msg.msg, 'error');
          }
        }

      })

    })


    $('.savePrice').on('click', function() {
      const _tokken = $('meta[name="_tokken"]').attr('value');
      $.ajax({
        url: '<?php echo base_url("Test_management/Test_protocols/save_protocol_price") ?>',
        method: 'post',
        data: $('#priceForm').serialize(),
        success: function(data) {
          var html = $.parseJSON(data);
          if (html.status > 0) {
            $.notify(html.msg, 'success');
            $('#priceModal').modal('hide');
          } else {
            $.notify(html.msg, 'error');
          }
        }
      })
    })


    $('.test_price').on('click', function() {
      var protocol_id = $(this).attr('data-id');
      const _tokken = $('meta[name="_tokken"]').attr('value');
      $.ajax({
        url: '<?php echo base_url("Test_management/Test_protocols/get_price_list") ?>',
        method: 'post',
        data: {
          protocol_id: protocol_id,
          _tokken: _tokken
        },
        success: function(data) {
          var html = $.parseJSON(data);
          $('.price_table tbody').html("");
          var w = 1;
          if (html) {
            $.each(html, function(index, value) {

              $('.price_table tbody').append($('<tr><td><input type="text" class="form-control form-control-sm" name="row[' + index + '][sl_number]" value="' + w + '" readonly></td><td><input type="text" class="form-control form-control-sm" name="row[' + index + '][country_code]" value="' + value.country_code + '" readonly></td><td><input type="text" class="form-control form-control-sm" name="row[' + index + '][currency_code]" value="' + value.currency_code + '" readonly></td><td><input type="text" class="form-control form-control-sm" name="row[' + index + '][price]" value="' + value.price + '"></td></tr>'));
              w++;
            })
          }
        }

      })
    })

  })
</script>
<script>
  $(document).ready(function() {
    const url = $('body').data('url');
    const _tokken = $('meta[name="_tokken"]').attr('value');
    // Ajax call to get log
    $('.log_view').click(function() {
      $('#table_log').empty();
      var id = $(this).data('id');
      $.ajax({
        type: 'post',
        url: url + 'Test_management/Test_protocols/get_log_data',
        data: {
          _tokken: _tokken,
          id: id
        },
        success: function(data) {
          var data = $.parseJSON(data);
          var value = '';
          sno = Number();
          $.each(data, function(i, v) {
            sno += 1;
            var operation = v.action_taken;
            var action_message = v.text;
            var taken_by = v.taken_by;
            var taken_at = v.taken_at;
            value += '<tr>';
            value += '<td>' + sno + '</td>';
            value += '<td>' + operation + '</td>';
            value += '<td>' + action_message + '</td>';
            value += '<td>' + taken_by + '</td>';
            value += '<td>' + taken_at + '</td>';
            value += '</tr>';

          });
          $('#table_log').append(value);
        }
      });
    });
    // ajax call to get log ends here
  });
</script>