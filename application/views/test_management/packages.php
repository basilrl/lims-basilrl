<link rel="stylesheet" href="<?php echo base_url('assets/dataTables/css/dataTables.bootstrap4.min.css'); ?>">

<script src="<?php echo base_url('assets/dataTables/js/jquery.dataTables.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/dataTables/js/dataTables.bootstrap4.min.js'); ?>"></script>

<style>
  .table-responsive {
    overflow-x: hidden;
  }

  .modal-content {
    width: 100%;
    margin: 30px auto;
    /* position:relative; */
  }

  /* .price_table{
  position:absolute;
} */
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

  <div class="container-fluid">
    <div class="row">
      <div class="col-sm-12">
        <h1>PACKAGES</h1>
      </div>
    </div>
    <!-- /.card-header -->

    <div class="row">
      <div class="col-sm-12">
        <div class="card">
          <div class="card-header">
            <div class="row">

              <div class="col-sm-2">
                <?php if (exist_val('Packages/open_packages', $this->session->userdata('permission'))) { ?>
                  <a class="btn btn-sm btn-primary add" href="<?php echo base_url('open_packages/add/NULL') ?>">Add New</a>
                <?php } ?>
              </div>
              <?php ?>
              <div class="col-sm-3">
                <input class="sample_id" type="hidden" val="<?php echo (($p_id != 'NULL') ? $p_id : ''); ?>" name="sample_id">
                <input class="form-control form-control-sm  input-sm sample_name" value="<?php echo ($sample_name) ? $sample_name->sample_type_name : ''; ?>" autocomplete="off" name="sample_name" type="text" placeholder="Select Product">
                <ul class="list-group-item sample_list" style="display:none">
                </ul>
              </div>
              <div class="col-sm-3">
               <select class="form-control form-control-sm" id="packageBuyer" name="packageBuyer">
                 <option value="">Choose Buyer</option>
                 <?php foreach ($buyers as $key => $value) { ?>
                        <option value="<?=$value->customer_id?>" <?php if($packageBuyer == $value->customer_id) { echo 'selected';}?>><?=$value->customer_name?></option>
                      <?php } ?>
               </select>
              </div>
              <div class="col-sm-2">
                <input value="<?php echo (($search != 'NULL') ? $search : ""); ?>" id="search" class="form-control form-control-sm" type="text" placeholder="Search" aria-label="Search">
              </div>

              <div class="col-sm-2">
                <button onclick="searchfilter();" type="button" class="btn btn-sm btn-primary">SEARCH</button>
                <a class="btn btn-sm btn-primary" href="<?php echo base_url('packages'); ?>">CLEAR</a>
              </div>

            </div>

            <div class="input-group input-group-md" style="width: 150px;">
              <div class="input-group-append">
              </div>
            </div>
          </div>


          <div class="row">
            <div class="col-sm-12">
              <div class="table-responsive">
                <table id="emp_table" class="table table-sm" style="margin: 0 20px;">
                  <thead>

                    <tr>
                      <?php
                      if ($search) {
                        $search = base64_encode($search);
                      } else {
                        $search = "NULL";
                      }
                      if ($p_id != "") {
                        $p_id = $p_id;
                      } else {
                        $p_id = "NULL";
                      }
                      if ($order != "") {
                        $order = $order;
                      } else {
                        $order = "NULL";
                      }

                      ?>


                      <!-- <?php echo base_url('products/' . $cat_id . '/' . $search . '/col_1' . '/' . $order); ?> -->

                      <th scope="col"><a href="">SL NO.</a></th>
                      <th scope="col"><a href="<?php echo base_url('packages/' . $p_id . '/' . $search . '/pc.package_name/' . $order) ?>">PACKAGE NAME</a></th>
                      <th scope="col">Buyer NAME</th>
                      <th scope="col"><a href="<?php echo base_url('packages/' . $p_id . '/' . $search . '/sample.sample_type_name/' . $order) ?>">PRODUCT </a></th>
                      <th scope="col"><a href="<?php echo base_url('packages/' . $p_id . '/' . $search . '/pc.package_status/' . $order) ?>">STATUS</a></th>
                      <th scope="col" colspan="2"><a href="">ACTION</a></th>


                    </tr>
                  </thead>
                  <tbody>
                    <?php (empty($this->uri->segment(7))) ? $sn = 1 : $sn = $this->uri->segment(7) + 1; ?>
                    <?php if ($packages_list) : ?>
                      <?php foreach ($packages_list as $item) : ?>

                        <tr>
                          <th><?= $sn; ?></th>
                          <td width="30%"><?= $item->package_name ?></td>
                          <td width="30%"><?= $item->customer_name ?></td>
                          <td><?= $item->product_name ?></td>
                          <td><?= ($item->package_status == '1') ? 'ACTIVE' : 'IN-ACTIVE' ?></td>
                          <td>
                            <?php if (exist_val('Packages/open_packages', $this->session->userdata('permission'))) { ?>
                              <a class="btn btn-sm btn-default" href="<?php echo base_url('open_packages/update/' . $item->package_id) ?>" title="Edit packages"><img src="<?php echo base_url('assets/images/mem_edit.png') ?>"></a>
                            <?php } ?>

                            <?php if (exist_val('Packages/test_list', $this->session->userdata('permission'))) { ?>
                              <button type="button" class="btn btn-sm btn-default add_test" data-id="<?php echo $item->package_id; ?>" data-bs-toggle="modal" data-bs-target="#tests" title="Manage Test">
                                <img src="<?php echo base_url('assets/images/manage_test.png') ?>">
                              </button>
                            <?php } ?>
                            <?php if (exist_val('Packages/get_price_list', $this->session->userdata('permission'))) { ?>
                              <button type="button" class="btn btn-sm btn-default test_price" data-id="<?php echo $item->package_id; ?>" data-bs-toggle="modal" data-bs-target="#priceModal" title="Price">
                                <img src="<?php echo base_url('assets/images/price.png') ?>">
                              </button>
                            <?php } ?>

                            <?php if (exist_val('Packages/import_file', $this->session->userdata('permission'))) { ?>
                              <button type="button" class="btn btn-sm btn-default import" data-id="<?php echo $item->package_id; ?>" data-bs-toggle="modal" data-bs-target="#import" title="import Package">
                                <img src="<?php echo base_url('assets/images/page_excel.png'); ?>" alt="Import Package" width="20px">
                              </button>
                            <?php } ?>
                            
                            <?php if (exist_val('Packages/get_log_data', $this->session->userdata('permission'))) { ?>
                              <a href="javascript:void(0)" data-id="<?php echo $item->package_id ?>" class="log_view" data-bs-toggle='modal' data-bs-target='#lo_view_target' class="btn btn-sm" title="Log View"><img src="<?php echo base_url('assets/images/log-view.png'); ?>" alt="Log view" width="20px"></a>
                            <?php } ?>
                          </td>
                        </tr>
                      <?php $sn++;
                      endforeach; ?>
                    <?php endif; ?>
                    <?php if ($packages_list == NULL) : ?>
                      <tr>
                        <td>NO RECORD FOUND</td>
                      </tr>
                    <?php endif; ?>
                  </tbody>
                </table>
              </div>
            </div>

          </div>


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

</div>

<!-- /.content -->

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
        <div class="row">
          <?php if (exist_val('Packages/add_testPackages', $this->session->userdata('permission'))) { ?>
            <button class="btn btn-sm btn-primary" id="addTest">ADD</button>
          <?php } ?>
        </div>
        <span id="test_list_loader"></span>
        <form action="" method="post" id="testForm">
          <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
          <input type="hidden" name="package_id" value="" class="hidden_package_id">
          <table class="table table-sm test_table" id="test_table_datatable">
            <thead>
              <tr>
                <th>SL No.</th>
                <th>Test Name</th>
                <th>Action</th>
                <th></th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>
        </form>
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
          <input type="hidden" value="hello" id="price_package_id" class="price_package_id" name="price_package_id">

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

<!-- import modal  -->
<div class="modal fade " id="import" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">import file</h5>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <!-- Form -->
      <form method="post" id="import_form" enctype="multipart/form-data">
        <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
        <p><label>Select Excel File</label>
          <input type="file" name="file" id="file" required accept=".xls, .xlsx" />
        </p>
        <br />
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">CLOSE</button>
        <button type="submit" class="btn btn-primary " style="display:inline-block">SAVE</button>

      </form>


    </div>
  </div>
</div>




<script src="<?php echo base_url('assets/dist/js/test_management.js'); ?>"></script>

<script>
  $('.test_price').click(function() {
    var package_id = $(this).data('id');
    // alert(package_id);
    ($('#price_package_id').val(package_id));
  })
</script>

<script>
  function searchfilter() {
    var url = '<?php echo base_url("packages"); ?>';
    var p_id = $('.sample_id').val();
    var search = $('#search').val();
    var packageBuyer = $('#packageBuyer').val();

    if (p_id != '') {
      url = url + '/' + p_id;
    } else {
      url = url + '/NULL';
    }

    if (search != '') {
      url = url + '/' + btoa(search);
    } else {
      url = url + '/NULL';
    }
    if (packageBuyer != '') {
      url = url + '/' + btoa(packageBuyer);
    } else {
      url = url + '/NULL';
    }
    window.location.href = url;
  }
</script>

<script>
  $(document).ready(function() {
    $('#packageBuyer').select2();
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
        var _URL = "<?php echo base_url('get_auto_lists'); ?>";
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

            // ulEvent.parent().css("positon","relative");
            ulEvent.html('');
            if (html) {
              $.each(html, function(index, value) {
              console.log(value);
              
                ulEvent.append('<li class="list-group-item ' + li + '"' + 'data-id=' + value.id + '>' + value.test_name + '(' + value.test_method_name + ')'  + '</li>');
              })
            } else {
              ulEvent.append('<li class="list-group-item ' + li + '"' + 'data-id="">NO RECORD FOUND</li>');
            }

            var liEvent = $("li." + li);
            liEvent.click(function() {
              var id = $(this).attr('data-id');
              var name = $(this).text();
              inputEvent.val(name);
              hide_inputEvent.val(id);
              ulEvent.fadeOut();
            });
          }
        });
      });
    }

 
    $(document).on('click', '.test_name', function() {
      getAutolist('test_id', this, 'test_list', 'test_li', '', 'test_name', '*', 'tests');
    });
  });

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
    });

    $(document).on('click', '.deleteTest', function() {
      $(this).parents("tr").remove();
      $('.add_btn').css("display", "none");
      $('.save_Testbtn').css("display", "inline-block");
      serial_reset();
    });

    function serial_reset() {
      var sl = 1;
      var tr = $('.sl_no');
      var sort_order = $('.sort_order');
      $.each(tr, function(i, v) {
        tr[i].innerHTML = sl;
        sort_order[i].value = sl;
        sl++;
      });
    }

    var sl = 1;
    $('.add_test').click(function() {
      var package_id = $(this).attr("data-id")
      $('.add_btn').data('id', package_id);
      $('.hidden_package_id').attr('value', package_id);
      const _tokken = $('meta[name="_tokken"]').attr('value');

      getTestlists(package_id, _tokken);
    });

    var sl = 1;

    function getTestlists(package_id, _tokken) {
      var sl_no = 1;
      $.ajax({
        url: "<?php echo base_url('Test_management/Packages/test_list') ?>",
        method: "POST",
        data: {
          package_id: package_id,
          _tokken: _tokken
        },
        beforeSend: function() {
          $('#test_list_loader').html('<h4 class="mt-5 text-center">Please wait...</h4>');
          if ($.fn.DataTable.isDataTable('#test_table_datatable')) {
            $('#test_table_datatable').DataTable().destroy();
          }
        },
        success: function(data) {
          var html = $.parseJSON(data);
          console.log(typeof(html));
          $('#test_list_loader').html("");
          $('.test_table tbody').html("");
          var delIcon = '<?php echo base_url('assets/images/delete.png') ?>';
          var upIcon = '<?php echo base_url('assets/images/arrow_up.png') ?>';
          var downIcon = '<?php echo base_url('assets/images/arrow_down.png') ?>';
          if (html) {
            $.each(html, function(index, value) {
              $('.test_table tbody').append("<tr><td class='sl_no'>" + sl_no + "</td><td>" + value.test_name + " (" + value.test_method + ")</td><td><a type='button' class='deleteTest'><img src='" + delIcon + "' title='Delete test' height='20px' width='20px'></i></a></button><button type='button' class='up btn btn-sm'><img src='" + upIcon + "' title='Up order'></button><button type='button' class='down btn btn-sm'><img src='" + downIcon + "' title='Down order'></button></td><td><input type='hidden' name='row[" + sl + "][test_package_test_id]' value='" + value.test_package_test_id + "'><input type='hidden' name='row[" + sl + "][test_package_id]' value='" + value.test_package_id + "'><input type='hidden' name='row[" + sl + "][test_package_packages_id]' value='" + value.test_package_packages_id + "'><input type='hidden' name='row[" + sl + "][package_test_method]' value='" + value.package_test_method + "'><input type='hidden' class='sort_order' name='row[" + sl + "][package_test_sort_order]' value='" + sl_no + "'></td></tr>");
              sl++;
              sl_no++;
              // order_set();
            });
          }
          $('#test_table_datatable').DataTable({
            "destroy": true,
            "paging": false,
            "lengthChange": false,
            "columnDefs": [{
              "targets": [2, 3],
              "orderable": false,
            }, ],
          });
        }
      });
    }

    $('.save_Testbtn').click(function() {
      const _tokken = $('meta[name="_tokken"]').attr('value');
      $.ajax({
        url: "<?php echo base_url('Test_management/Packages/save_test_packages') ?>",
        method: "post",
        data: $('#testForm').serialize(),
        success: function(data) {
          var msg = $.parseJSON(data);
          if (msg.status > 0) {
            $.notify(msg.msz, 'success');
            $('#tests').modal('hide');
          } else {
            $.notify(msg.msz, 'error');
          }
        }
      });
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
    });

    $('.add_btn').click(function() {
      var test_package_packages_id = $(this).data('id');
      var test_package_test_id = $('.test_id').val();
      var package_id = $('.hidden_package_id').val();
      var sl = $('.test_table tbody').children("tr").length;
      var order = sl + 1;
      const _tokken = $('meta[name="_tokken"]').attr('value');
      $.ajax({
        url: '<?php echo base_url("Test_management/Packages/add_testPackages") ?>',
        method: 'post',
        data: {
          test_package_packages_id: test_package_packages_id,
          test_package_test_id: test_package_test_id,
          order: order,
          package_id: package_id,
          _tokken: _tokken
        },
        success: function(data) {
          var msg = $.parseJSON(data);
          if (msg.status > 0) {
            getTestlists(test_package_packages_id, _tokken);
            $.notify(msg.msg, 'success');
            $('.add_btn').css("display", "none");
            $('.save_Testbtn').css("display", "inline-block");
            // $('#tests').modal('hide');
          } else {
            $.notify(msg.msg, 'error');
          }
        }
      });
    });

    $('.savePrice').on('click', function() {
      const _tokken = $('meta[name="_tokken"]').attr('value');
      $.ajax({
        url: '<?php echo base_url("Test_management/Packages/save_package_price") ?>',
        method: 'post',
        data: $('#priceForm').serialize(),
        success: function(data) {
          var msg = $.parseJSON(data);
          if (msg.status > 0) {
            $.notify(msg.msg, 'success');
            $('#priceModal').modal('hide');
          } else {
            $.notify(msg.msg, 'error');
          }
        }
      });
    });


    $(document).ready(function() {
      $('#import_form').on('submit', function(event) {
        event.preventDefault();
        const _tokken = $('meta[name="_tokken"]').attr('value');
        $.ajax({
          url: '<?php echo base_url("Test_management/Packages/import_file") ?>',
          method: "POST",
          data: new FormData(this),
          contentType: false,
          cache: false,
          processData: false,
          dataType: 'json',
          success: function(data) {
            // console.log(data);
            $.notify(data, 'error');
            location.reload();
          }
        })
      });

    });






    $('.test_price').on('click', function() {
      var package_id = $(this).attr('data-id');
      const _tokken = $('meta[name="_tokken"]').attr('value');
      $.ajax({
        url: '<?php echo base_url("Test_management/Packages/get_price_list") ?>',
        method: 'post',
        data: {
          package_id: package_id,
          _tokken: _tokken
        },
        success: function(data) {
          var html = $.parseJSON(data);
          $('.price_table tbody').html("");
          var w = 1;
          if (html) {
            $.each(html, function(index, value) {
              $('.price_table tbody').append('<tr><td><input type="text" class="form-control form-control-sm" name="row[' + index + '][sl_number]" value="' + w + '" readonly></td><td><input type="text" class="form-control form-control-sm" name="row[' + index + '][country_code]" value="' + value.country_code + '" readonly></td><td><input type="text" class="form-control form-control-sm" name="row[' + index + '][currency_code]" value="' + value.currency_code + '" readonly></td><td><input type="text" class="form-control form-control-sm" name="row[' + index + '][price]" value="' + value.price + '"></td></tr>');
              w++;
            });
          }
        }
      });
    });
  });
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
        url: url + 'Test_management/Packages/get_log_data',
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