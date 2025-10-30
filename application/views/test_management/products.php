<style>
  .table-responsive {
    overflow-x: hidden;
  }


  .test_list{

width: 100%;
/* position: absolute; */
font-size: 12px;
z-index: 1;
overflow-y: auto;
overflow-x: hidden;
max-height: 150px;
cursor: pointer;
padding:0;
margin:0 auto;


}
.test_table tr th:nth-child(2),.test_table tr td:nth-child(2){
width:70%;
}
</style>

<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>PRODUCTS LIST</h1>
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

                <div class="col-sm-1">
                <?php if(exist_val('Products/add_product',$this->session->userdata('permission'))){ ?>
                  <a class="btn btn-sm btn-primary add" href="<?php echo base_url('add_product') ?>">Add New</a>
                  <?php } ?>
                </div>
                <!-- <div class="col-sm-2"> -->
                <?php if(exist_val('Products/import_excel',$this->session->userdata('permission'))){ ?>
                <!-- <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                <img src="<?php echo base_url('assets/images/import_icon.jpg')?>" alt="Import Products">Import Product
                </button> -->
                <?php } ?>
                <!-- </div> -->

                <div class="col-sm-3">
                  <input class="sample_category_id" type="hidden" value="<?php echo ($cat_id) ? $cat_id : '' ?>" name="sample_category_id">
                  <input class="form-control form-control-sm input-sm  sample_category_name" value="<?php echo ($cat_name) ? $cat_name->sample_category_name : '' ?>" name="sample_category_name" autocomplete="off" type="text" placeholder="Select a category ... ">
                  <ul class="list-group-item cat_list" style="display:none">
                  </ul>
                </div>

                <div class="col-sm-3">
                  <input value="<?php echo (($search != 'NULL') ? $search : ""); ?>" id="search" class="form-control form-control-sm" type="text" placeholder="Search" aria-label="Search">
                </div>

                <div class="col-md-2">
                  <select name="ecom_available" class="form-control form-control-sm" id="ecom_available">
                    <option disabled="" selected>Choose option</option>
                    <option value="1" <?php if($available_to_ecommerce == '1'){ echo 'selected'; }?>>Listed Products</option>
                    <option value="0" <?php if($available_to_ecommerce == '0'){ echo 'selected'; }?>>Un-Listed Products</option>
                  </select>
                </div>

                <div class="col-sm-3">
                  <button onclick="searchfilter();" type="button" class="btn btn-sm btn-primary">SEARCH</button>
                  <a class="btn btn-sm btn-primary" href="<?php echo base_url('products'); ?>">CLEAR</a>
                </div>

              </div>

              <div class="input-group input-group-md" style="width: 150px;">
                <div class="input-group-append">
                </div>
              </div>
            </div>

            <div id="emp_table" class="table-responsive">
              <table class="table table-sm">
                <thead>
                  <tr>
                    <?php
                    if ($search) {
                      $search = base64_encode($search);
                    } else {
                      $search = "NULL";
                    }
                    if ($cat_id != "") {
                      $cat_id = $cat_id;
                    } else {
                      $cat_id = "NULL";
                    }
                    if ($order != "") {
                      $order = $order;
                    } else {
                      $order = "NULL";
                    }
                    if ($available_to_ecommerce != "") {
                      $available_to_ecommerce = base64_encode($available_to_ecommerce);
                    } else {
                      $available_to_ecommerce = "NULL";
                    }

                    ?>
                    <!-- <?php echo base_url('products/' . $cat_id . '/' . $search . '/col_1' . '/' . $order); ?> -->

                    <th scope="col"><a href="">SL NO.</a></th>
                    <th scope="col"><a href="<?php echo base_url('products/' . $cat_id . '/' . $search .'/' . $available_to_ecommerce . '/st.sample_type_name/' . $order); ?>">PRODUCT</a></th>
                    <th scope="col"><a href="<?php echo base_url('products/' . $cat_id . '/' . $search .'/' . $available_to_ecommerce . '/cat.sample_category_name/' . $order); ?>">PRODUCT CATEGORY</a></th>
                    <th scope="col"><a href="<?php echo base_url('products/' . $cat_id . '/' . $search .'/' . $available_to_ecommerce . '/st.sample_types_code/' . $order); ?>">PRODUCT CODE</a></th>
                    <th scope="col"><a href="<?php echo base_url('products/' . $cat_id . '/' . $search .'/' . $available_to_ecommerce . '/st.retain_period/' . $order); ?>">RETAIN PERIOD</a></th>
                    <th scope="col"><a href="<?php echo base_url('products/' . $cat_id . '/' . $search .'/' . $available_to_ecommerce . '/st.status/' . $order); ?>">STATUS</a></th>
                    <th scope="col"><a href="">E-commerce Availibility</a></th>
                    <th scope="col"><a href="">IMAGE</a></th>
                    <th scope="col"><a href="">ACTION</a></th>


                  </tr>
                </thead>
                <tbody>
                  <?php (empty($this->uri->segment(7))) ? $sn = 1 : $sn = $this->uri->segment(7) + 1; ?>
                  <?php if ($product_list) : ?>
                    <?php foreach ($product_list as $item) : ?>

                      <tr>
                        <th><?= $sn; ?></th>
                        <td><?= $item->sample_type_name ?></td>
                        <td><?= $item->cat_name ?></td>
                        <td><?= $item->sample_types_code ?></td>
                        <td><?= $item->retain_period ?></td>
                        <td><?= ($item->status == '1') ? 'ACTIVE' : 'IN-ACTIVE' ?></td>
                        <td><?= ($item->available_to_ecommerce == '1') ? 'Listed' : 'Not Listed' ?></td>
                        <td style="max-width: 100px; overflow: hidden;text-overflow: ellipsis;white-space: nowrap;">
                            <img src="<?= $item->upload_image; ?>" style="width:100px;height:100px;"  />   
                        </td>
                        
                        <td>
                          <?php if($item->available_to_ecommerce == 0){?>
                            <a href="<?php echo base_url('list_product/' . $item->sample_type_id) ?>" title="List product into e-commerce"><img src="<?php echo base_url('assets/images/accept.png')?>"></a>
                            <?php } ?>
                            <?php if($item->available_to_ecommerce == 1){?>
                            <a href="<?php echo base_url('unlist_product/' . $item->sample_type_id) ?>" title="Un-List product into e-commerce"> <img src="<?php echo base_url('assets/images/action_stop.gif')?>"></a>
                            <?php } ?>
                        <?php if(exist_val('Products/edit_product',$this->session->userdata('permission'))){ ?>
                          
                          <a href="<?php echo base_url('edit_product/' . $item->sample_type_id) ?>"><img src="<?php echo base_url('assets/images/mem_edit.png')?>" alt="Edit" title="Edit Product" > </a>
                          <?php }?>

                          <?php if(exist_val('Products/add_test_products',$this->session->userdata('permission'))){ ?>
                          <a data-id="<?php echo $item->sample_type_id?>" data-bs-toggle="modal" data-bs-target="#tests" style="cursor:pointer" class="add_test"><img src="<?php echo base_url('assets/images/manage_test.png')?>" alt="Manage Test" title="Manage Test" ></a>
                         <?php }?> 



                         <?php 
                            if(exist_val('Products/get_log_data',$this->session->userdata('permission'))){ ?>
                            <button type="button" class="btn btn-sm btn-default log_view" title="USER LOG" data-id="<?php echo $item->sample_type_id?>" data-bs-toggle="modal" data-bs-target="#lo_view_target">
                             <img src="<?php echo base_url('assets/images/log-view.png')?>" >
                             </button>

                           <?php }?>
                        </td>
                      </tr>
                    <?php $sn++;
                    endforeach; ?>
                  <?php endif; ?>
                  <?php if ($product_list == NULL) : ?>
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

<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Import Products</h5>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form method="post" enctype="multipart/form-data" id="importExcel">
                  <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">

                  <input class="primary form-control" type="file" name="excel_import" >
                  <br>
                  <div class="col-sm-12">
                  Please ensure excel file you are going to upload complies with all business rules (unique values, minimum length etc)?<a href='<?php echo base_url("public/file/Product.xlsx")?>' target="_blank">Click Here</a> for sample format
                  
                  </div>
          
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary import_excel">Submit</button>
      </div>
      </form>
    </div>
  </div>
</div>




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
          <button class="btn btn-sm btn-primary" id="addTest">ADD</button>
        </div>
        <form action="" method="post" id="testForm">
            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
              <input type="hidden" name="product_id" value="" class="hidden_package_id">
            <div class="row">
              <div class="table-responsive">

                <table class="table table-sm test_table">
                  <thead>
                  <tr>
                  <th>SL No.</th>
                    <th>Test Name</th>
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


<!-- common method -->
<script src="<?php echo base_url('assets/dist/js/test_management.js'); ?>"></script>
<script>
  $(document).ready(function() {

  
// function getAutolist(hide_input,input,ul,li,where,like,select,table)

function getAutolist(hide_input, input, ul, li) {

  var hide_inputEvent = $("input." + hide_input);
  var inputEvent = $(input);
  var ulEvent = $("ul." + ul);

  inputEvent.focusout(function() {
    ulEvent.fadeOut();
  })

  inputEvent.on('keyup', function() {
    var key = $(this).val();
    var _URL = "<?php echo base_url('Test_management/Products/get_test_list_for_product'); ?>";
    const _tokken = $('meta[name="_tokken"]').attr('value');
    $.ajax({
      url: _URL,
      method: 'POST',
      data: {
        search: key,
        _tokken: _tokken
      },

      success: function(data) {
        var html = $.parseJSON(data);
        ulEvent.fadeIn();
       
        // ulEvent.parent().css("positon","relative");
        ulEvent.html('');
        if (html) {
          $.each(html, function(index, value) {
            ulEvent.append($('<li class="list-group-item ' + li + '"' + 'data-id=' + value.test_id+ '><b>Test name </b>: ' + value.test_name + ' <b>Test method</b> :'+value.test_method+'</li>'));
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

      }
    })
  })

}
  $(document).on('click','.test_name',function(){
    getAutolist('test_id', this, 'test_list', 'test_li');
  });


})


$(document).ready(function() {
    $('#addTest').click(function() {
      var test_package_test_id = $('.test_id').val();
      if(test_package_test_id!=""){
        var sl =  $('.test_table tbody').children("tr").length; 
        var delIcon = '<?php echo base_url('assets/images/delete.png')?>';
        var dumy_tr = '<tr><td class="sl_no">'+(sl+1)+'</td><td><div class="row "><div class="col-sm-12"><input class="test_id" type="hidden" value="" name=""><input class="form-control input-sm test_name" value="" autocomplete="off" name="test_name" type="text" placeholder="Type Test Name..."></div></div><div class="row"><div class="col-sm-12"><ul class="list-group-item test_list" style="display:none"></ul></div></div></td><td><a type="button" class="deleteTest"><img src="'+delIcon+'" title="Delete test" height="20px" width="20px"></i></a></td></tr>';
        $('.test_table tbody').append(dumy_tr);
        $('.add_btn').css("display","inline-block");
        $('.save_Testbtn').css("display","none");
      }
      else{
        alert('Please Select Atleast One Test');
      }
  
    })

    $(document).on('click', '.deleteTest', function() {
      $(this).parents("tr").remove();
      $('.add_btn').css("display","none");
      $('.save_Testbtn').css("display","inline-block");
      serial_reset();
      
    })

    function serial_reset(){
      var sl = 1;
      var tr = $('.sl_no');
      var sort_order = $('.sort_order');
      $.each(tr,function(i,v){
        tr[i].innerHTML=sl;
        sort_order[i].value= sl;
        sl++;
      })
    }


    var sl = 1;
    $('.add_test').click(function(){
      var product_id = $(this).attr("data-id")
      $('.add_btn').data('id',product_id);
      $('.hidden_package_id').attr('value',product_id);
      const _tokken = $('meta[name="_tokken"]').attr('value'); 
      getTestlists(product_id,_tokken);
    })

    var sl=0;
   function getTestlists(product_id,_tokken){
    var sl_no = 1;
      $.ajax({
        url:"<?php echo base_url('Test_management/Products/test_list')?>",
        method:"POST",
        data:{
          product_id:product_id,
          _tokken:_tokken
        },
        success:function(data){

          var html = $.parseJSON(data);
          $('.test_table tbody').html("");
          var delIcon = '<?php echo base_url('assets/images/delete.png')?>';
          var upIcon = '<?php echo base_url('assets/images/arrow_up.png')?>'
          var downIcon = '<?php echo base_url('assets/images/arrow_down.png')?>'
          $.each(html,function(index,value){
            
              ht = "<tr>";
              ht += "<td class='sl_no'>"+sl_no+"</td>";
              ht += "<td>"+value.test_name+"</td>";
              ht += "<td><a type='button' class='deleteTest' data-id='"+value.test_id+"'><img src='"+delIcon+"' title='Delete test' height='20px' width='20px'></a></td>";
              ht += "<td><button type='button' class='up btn btn-sm'><img src='"+upIcon+"' title='Up order'></button><button type='button' class='down btn btn-sm'><img src='"+downIcon+"' title='Down order'></button></td>";
              ht += "<td><input type='hidden' name='row["+sl+"][test_sample_type_test_id]' value='"+value.test_id+"'><input type='hidden' name='row["+sl+"][test_sample_type_sample_type_id]' value='"+product_id+"'><input type='hidden' class='sort_order' name='row["+sl+"][priority_order]' value='"+sl_no+"'></td>";
              ht +="</tr>";
             
            $('.test_table tbody').append($(ht));
            sl++;
            sl_no++;
            // order_set();
          })
         
        },
      
          
        
      })
    }

    $('.save_Testbtn').click(function(){

      const _tokken = $('meta[name="_tokken"]').attr('value');
      $.ajax({
        url:"<?php echo base_url('Test_management/Products/save_test_products')?>",
        method:"post",
        data:$('#testForm').serialize(),
           success:function(data){
             var msg = $.parseJSON(data);
             if(msg.status>0){
              $.notify(msg.msg,'success');
               $('#tests').modal('hide');
             }
             else{
              $.notify(msg.msg,'error');
             }
        }
      })
    })

    $(document).on('click','.up,.down', function(){
      var $element = this;
         var row = $($element).parents("tr:first");
         
         if($(this).is('.up')){
             row.insertBefore(row.prev());
             serial_reset();
            //  order_set();
         }
         
         else{
              row.insertAfter(row.next());
              serial_reset();
              // order_set();
            
         }
    })


    $('.add_btn').click(function(){
      product_id = $('.hidden_package_id').val();
     
       var test_sample_type_sample_type_id = $(this).data('id');
       var test_sample_type_test_id = $('.test_id').val();
       var sl =  $('.test_table tbody').children("tr").length;
      var order = sl+1;
       const _tokken = $('meta[name="_tokken"]').attr('value');
       $.ajax({
         url:'<?php echo base_url("Test_management/Products/add_test_products")?>',
         method:'post',
         data:{
          test_sample_type_sample_type_id:test_sample_type_sample_type_id,
          test_sample_type_test_id:test_sample_type_test_id,
          priority_order:order,
          product_id:product_id,
          _tokken: _tokken
         },
         success:function(data){
          var msg = $.parseJSON(data);

          if(msg.status>0){
            getTestlists(test_sample_type_sample_type_id,_tokken);

            $.notify(msg.msg,'success');
            $('.add_btn').css("display","none");
            $('.save_Testbtn').css("display","inline-block");
  
          }
          else
          {
            $.notify(msg.msg,'error');
          }
       
          
         }

       })
      
    })

    // import excel ajax
    $(document).on('submit','#importExcel',function(event){
      event.preventDefault('#importExcel');
      var formData = new FormData(this);
    
      $.ajax({
        url:"<?php echo base_url('Test_management/Products/import_excel')?>",
        method:"POST",
        data:formData,
        processData: false,
        contentType: false,
        success:function(data){
          var result = $.parseJSON(data);
          if(result.status>0){
            location.reload();
          }
          else{
            $.notify(result.msg,'error');
          }
        }
      })
     
    })

})
    
</script>


<script>
  function searchfilter() {

    var url = '<?php echo base_url("products"); ?>';

    var cat_id = $('.sample_category_id').val();
    var search = $('#search').val();
    var ecom_available = $('#ecom_available').val();

    if (cat_id != '') {
      url = url + '/' + cat_id;
    } else {
      url = url + '/NULL';
    }

    if (search != '') {
      url = url + '/' + btoa(search);
    } else {
      url = url + '/NULL';
    }

    if (ecom_available != null) {
      url = url + '/' + btoa(ecom_available);
    } else {
      url = url + '/NULL';
    }
    // console.log(url);
    window.location.href = url;

  }
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
            url: url + 'Test_management/Products/get_log_data',
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