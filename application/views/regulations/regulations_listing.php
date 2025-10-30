<script src="<?php echo base_url('ckeditor/ckeditor.js'); ?>"></script>
<div class="content-wrapper">
    <section class="content-header">
        <!-- container fluid start -->
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h1>REGULATIONS</h1>
                </div>
            </div>

            <hr>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-sm-6">

                                <?php if(exist_val('Regulations/edit_regulations',$this->session->userdata('permission'))){ ?>
                                    <button type="button" class="btn btn-sm btn-primary add" data-bs-toggle="modal" data-bs-target=".add_regulations" title="ADD NEW REGULATIONS">ADD</button>

                                    <?php }?>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-8">
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <select name="" id="" class="form-control form-control-sm regulation_country">
                                            </select>
                                        </div>

                                        <div class="col-sm-4">
                                            <select name="" id="" class="form-control form-control-sm regulation_category">       
                                            </select>
                                        </div>     
                            
                                   
                                        <div class="col-sm-4">
                                            <select name="" id="" class="form-control form-control-sm regulation_notified_body">
                                            </select>
                                        </div>
 
                                    </div>
                                
                                </div>

                                <div class="col-sm-3 text-right">
                                    <input value="<?php echo (($search != 'NULL') ? $search : ""); ?>" id="search" class="form-control form-control-sm" type="text" placeholder="Search" aria-label="Search">
                                </div>

                                <div class="col-sm-1">
                                    <button onclick="searchfilter();" type="button" class="btn btn-sm btn-default" title="Search">
                                        <img src="<?php echo base_url('assets/images/search.png')?>" alt="search" >
                                    </button>
                                    <a class="btn btn-sm btn-default" href="<?php echo base_url('Regulations'); ?>" title="Clear">
                                         <img src="<?php echo base_url('assets/images/drop.png')?>" alt="Clear" >
                                    </a>
                                </div>
                            </div>
                        </div>
                        <!-- end card header -->

                        <div class="table-responsive p-2">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <?php
                                        if ($search) {
                                            $search = base64_encode($search);
                                        } else {
                                            $search = "NULL";
                                        }
                                        if ($country_id != "") {
                                            $country_id = base64_encode($country_id);
                                        } else {
                                            $country_id = "NULL";
                                        }
                                        if ($category_id != "") {
                                            $category_id = base64_encode($category_id);
                                        } else {
                                            $category_id = "NULL";
                                        }
                                        if ($notified_body_id != "") {
                                            $notified_body_id = base64_encode($notified_body_id);
                                        } else {
                                            $notified_body_id = "NULL";
                                        } 
                                        if ($order != "") {
                                            $order = $order;
                                        } else {
                                            $order = "NULL";
                                        }
                                       
                                        ?>
                                        <th scope="col">S. NO.</th>
                                        <th scope="col"><a href="<?php echo base_url('Regulations/index/'.$country_id.'/'.$category_id.'/'.$notified_body_id.'/'.$search.'/'.'acc.title'.'/'.$order)?>">TITLE</a></th>
                                        <th scope="col"><a href="<?php echo base_url('Regulations/index/'.$country_id.'/'.$category_id.'/'.$notified_body_id.'/'.$search.'/'.'ct.country_name'.'/'.$order)?>">COUNTRY</a></th>
                                        <th scope="col"><a href="<?php echo base_url('Regulations/index/'.$country_id.'/'.$category_id.'/'.$notified_body_id.'/'.$search.'/'.'md.division_name'.'/'.$order)?>">CATEGORY</a></th>
                                        <th scope="col"><a href="<?php echo base_url('Regulations/index/'.$country_id.'/'.$category_id.'/'.$notified_body_id.'/'.$search.'/'.'nb.notified_body_name'.'/'.$order)?>">NOTIFIED BODY</a></th>
                                        <th scope="col"><a href="<?php echo base_url('Regulations/index/'.$country_id.'/'.$category_id.'/'.$notified_body_id.'/'.$search.'/'.'admin.admin_fname'.'/'.$order)?>">CREATED BY</a></th>
                                        <th scope="col"><a href="<?php echo base_url('Regulations/index/'.$country_id.'/'.$category_id.'/'.$notified_body_id.'/'.$search.'/'.'acc.notification_date'.'/'.$order)?>">NOTIFICATION DATE</a></th>
                                        <th scope="col"><a href="<?php echo base_url('Regulations/index/'.$country_id.'/'.$category_id.'/'.$notified_body_id.'/'.$search.'/'.'acc.created_on'.'/'.$order)?>">CREATED DATE</a></th>
                                        <th scope="col">ACTION</th>
                                    </tr>
                                </thead>

                                <tbody>

                                    <?php $sn = $this->uri->segment('9') + 1;
                                    if ($regulations_list) {
                                        //   $quotes_list = $quotes_list[0];
                                        foreach ($regulations_list as $key => $item) { ?>
                                            <tr>
                                                <td><?php echo $sn; ?></td>
                                                <td><?php echo $item->title ?></td>
                                                <td><?php echo $item->country_name ?></td>
                                                <td><?php echo $item->division_name ?></td>
                                                <td><?php echo $item->notified_body_name ?></td>
                                                <td><?php echo $item->admin_fname ?></td>
                                                <td><?php echo $item->notification_date ?></td>
                                                <td><?php echo $item->created_on ?></td>
                                                <td>
                                                <?php if(exist_val('Regulations/edit_regulations',$this->session->userdata('permission'))){ ?>
                                                    <button type="button" class="btn btn-sm btn-default edit_reg" title="Edit" data-bs-toggle="modal" data-bs-target=".edit_regulations" data-id="<?php echo $item->regulations_id?>">
                                                         <img src="<?php echo base_url('assets/images/mem_edit.png')?>" alt="edit" >
                                                    </button>
                                                <?php }?>

                                                <?php if(exist_val('Regulations/download_file_regulations',$this->session->userdata('permission'))){ ?>
                                                    <button type="button" class="btn btn-sm btn-default" title="Download Regulation File" data-id="<?php echo $item->regulations_id?>">
                                                         <a href="<?php echo base_url('Regulations/download_file_regulations/') . base64_encode($item->file_path);?>"><img src="<?php echo base_url('assets/images/select_download.png')?>" alt="Download Regulation File"></a>
                                                    </button>
                                                <?php }?>

                                                <?php if(exist_val('Regulations/add_changes',$this->session->userdata('permission'))){ ?>

                                                    <button type="button" class="btn btn-sm btn-default add_change_btn" title="Add Changes" data-id="<?php echo $item->regulations_id?>" data-bs-toggle="modal" data-bs-target=".add_changes_window">
                                                         <img src="<?php echo base_url('assets/images/edit_other_page.png')?>" alt="ADD CHANGES">
                                                    </button>
                                                    <?php }?>

                                                    <?php if(exist_val('Regulations/get_user_log_data',$this->session->userdata('permission'))){ ?>

                                                    <button type="button" class="btn btn-sm btn-default user_log_btn" title="NOTIFICATION HISTORY" data-id="<?php echo $item->regulations_id?>" data-bs-toggle="modal" data-bs-target=".user_log_windows">
                                                         <img src="<?php echo base_url('assets/images/news_notification.png')?>" >
                                                    </button>

                                                    <?php }?>


                                                    <?php 
                                                    if(exist_val('Regulations/get_log_details',$this->session->userdata('permission'))){ ?>

                                                    <button type="button" class="btn btn-sm btn-default log_view" title="USER LOG" data-id="<?php echo $item->regulations_id?>" data-bs-toggle="modal" data-bs-target="#lo_view_target">
                                                        <img src="<?php echo base_url('assets/images/log-view.png')?>" >
                                                    </button>

                                                    <?php
                                                 }?>
                                                </td>
                                            </tr>
                                    <?php $sn++;
                                        }
                                    } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- card end -->
                </div>
            </div>

            <!-- menu end -->

            <div class="card-header">

                <?php if ($regulations_list && count($regulations_list) > 0) { ?>
                    <span><?php echo $links ?></span>
                    <span><?php echo $result_count; ?></span>
                <?php } else { ?>
                    <h3>NO RECORD FOUND</h3>
                <?php } ?>

            </div>
        </div>
        <!-- container fluid end -->
    </section>
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


<!-- ADD REGULATION MODAL -->

<div class="modal fade bd-example-modal-sm add_regulations" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">ADD REGULATIONS</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="add_regulation" action="javascript:void(0);" enctype="multipart/form-data">
                <div class="modal-body" style="">
                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">

                    <div class="row p-2">
                        <div class="col-sm-12">
                            <label for=""><b>TITLE</b></label>
                            <input type="text" name="title" placeholder="ENTER TITLE" class="form-control form-control-sm" autocomplete="off">
                        </div>
                    </div>

                    <div class="row p-2">
                        <div class="col-sm-6">
                            <label for=""><b>COUNTRY</b></label>
                            <select name="country_id" id="" class="form-control form-control-sm add_country">

                            </select>
                        </div>
                        <div class="col-sm-6">
                            <label for=""><b>CATEGORY</b></label>
                            <select name="division_id" id="" class="form-control form-control-sm add_category">

                            </select>
                        </div>
                    </div>

                    <div class="row p-2">
                        <div class="col-sm-6">
                            <label for=""><b>NOTIFIED BODY</b></label>
                            <select name="notified_body_id" id="" class="form-control form-control-sm add_notified_body">

                            </select>
                        </div>
                        <div class="col-sm-6">
                            <label for=""><b>NOTIFICATION DATE</b></label>
                            <input type="date" name="notification_date" id="" class="form-control form-control-sm" autocomplete="off">
                        </div>
                    </div>

                    <div class="row p-2">
                        <div class="col-sm-12">
                            <label for=""><b>TAT DESCRIPTION</b></label>
                            <textarea name="tat_description" placeholder="TYPE TAT DESCRIPTION" class="form-control form-control-sm" rows="5"></textarea>
                        </div>
                    </div>

                    <div class="row p-2">
                        <div class="col-sm-12">
                            <label for=""><b>UPLOAD FILE</b>(doc|docx|xls|xlsx|pdf)</label>
                            <input type="file" class="form-control form-control-sm" name="file_name" autocomplete="off">
                        </div>
                    </div>
                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">CLOSE</button>
                    <button type="submit" class="btn btn-primary add_regulation_button">ADD</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- END -->

<!-- EDIT REGULATION MODAL -->

<div class="modal fade bd-example-modal-sm edit_regulations" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">UPDATE REGULATIONS</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="edit_regulation" action="javascript:void(0);" enctype="multipart/form-data">
                <div class="modal-body" style="">
                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">

                    <input type="hidden" value="" name="regulations_id" class="regulations_id">

                    <div class="row p-2">
                        <div class="col-sm-12">
                            <label for=""><b>TITLE</b></label>
                            <input type="text" name="title" placeholder="ENTER TITLE" class="form-control form-control-sm edit_title" autocomplete="off">
                        </div>
                    </div>

                    <div class="row p-2">
                        <div class="col-sm-6">
                            <label for=""><b>COUNTRY</b></label>
                            <select name="country_id" id="" class="form-control form-control-sm edit_country">

                            </select>
                        </div>
                        <div class="col-sm-6">
                            <label for=""><b>CATEGORY</b></label>
                            <select name="division_id" id="" class="form-control form-control-sm edit_category">

                            </select>
                        </div>
                    </div>

                    <div class="row p-2">
                        <div class="col-sm-6">
                            <label for=""><b>NOTIFIED BODY</b></label>
                            <select name="notified_body_id" id="" class="form-control form-control-sm edit_notified_body">

                            </select>
                        </div>
                        <div class="col-sm-6">
                            <label for=""><b>NOTIFICATION DATE</b></label>
                            <input type="date" name="notification_date" id="" class="form-control form-control-sm notification_date" autocomplete="off">
                        </div>
                    </div>

                    <div class="row p-2">
                        <div class="col-sm-12">
                            <label for=""><b>TAT DESCRIPTION</b></label>
                            <textarea name="tat_description" placeholder="TYPE TAT DESCRIPTION" class="form-control form-control-sm tat" rows="5"></textarea>
                        </div>
                    </div>

                    <div class="row p-2">
                        <div class="col-sm-12">
                            
                            <label for=""><b>UPLOAD FILE</b>(doc|docx|xls|xlsx|pdf)</label>
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span>LAST UPLOADED FILE NAME</span>
                            <span><input type="text" readonly value="" class="file_name"></span>
                            <input type="file" class="form-control form-control-sm" name="file_name" autocomplete="off">
                        </div>
                    </div>
                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">CLOSE</button>
                    <button type="submit" class="btn btn-primary update_regulation_button">UPDATE</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- END -->


<!-- EDIT REGULATION MODAL -->

<div class="modal fade bd-example-modal-sm add_changes_window" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">ADD CHANGES</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="add_changes_form" action="javascript:void(0);" enctype="multipart/form-data">
                <div class="modal-body" style="">
                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">

                    <input type="hidden" name='regulation_id' value="" class="regulation_id">

                    <div class="row p-2">
                        <div class="col-sm-12">
                            <label for="">TITLE</label>
                            <input type="text" autocomplete="off" placeholder="Enter Notification title" name='notification_title' class="form-control form-control-sm">
                        </div>
                    </div>
                    
                    <div class="row p-2">
                    <div class="col-sm-12">
                            <label for="">NOTIFICATION DESCRIPTION</label>
                            <textarea class="ckeditor" name="notification_description" class="form-control form-control-sm"></textarea>
                        </div>
                    </div>   
                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">CLOSE</button>
                    <button type="submit" class="btn btn-primary add_change_submit">ADD</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- END -->


<!-- user log windows -->

<div class="modal user_log_windows" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content" style="">
      <div class="modal-header">
        <h5 class="modal-title"><b>NOTIFICATION HISTORY</b></h5>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
            <div class="row">
                    <div class="col-sm-12">
                    <div class="table-responsive">
                        
                            <table class="table table-sm p-2 user_table">
                            <thead>
                                <tr>
                                    <th scope="col">S. NO.</th>
                                    <th scope="col">NOTIFICATION TITLE</th>
                                    <th scope="col">ACTION MESSAGE</th>
                                    <th scope="col">DATE-TIME</th>
                                </tr>
                            </thead>
                            <tbody>
                            
                            </tbody>
                            
                            </table>
                    </div>
                 </div>
            </div>
            </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<!-- end -->
<script>
function searchfilter() {

        var url = '<?php echo base_url("Regulations/index"); ?>';

        
        var country_id = $('.regulation_country').val();
        
        if (country_id!="") {
            url = url + '/' + btoa(country_id);
        } else {
            country_id = "";
            url = url + '/NULL';
        }
       
        var category_id = $('.regulation_category').val();
        if (category_id!="") {
            url = url + '/' + btoa(category_id);
        } else {
            category_id = "";
            url = url + '/NULL';
        }

        var notified_body_id = $('.regulation_notified_body').val();
        if (notified_body_id!="") {
            url = url + '/' + btoa(notified_body_id);
        } else {
            notified_body_id = "";
            url = url + '/NULL';
        }
     
        var search = $('#search').val();
        if (search != '') {
            url = url + '/' + btoa(search);
        } else {
            url = url + '/NULL';
        }

        window.location.href = url;

    }

</script>

<script>

        $(document).ready(function(){
            
            function Get_dropDropdown_by_Ajax(selectBoxClass,placeholder,tableColumn,table,where=null,selected_id=null){
                var selectEvent = $('select.'+ selectBoxClass);
                const _tokken = $('meta[name="_tokken"]').attr("value");
                $.ajax({
                    url:"<?php echo base_url('Regulations/get_dropdown_by_ajax')?>",
                    method:"POST",
                    data:{
                        select:tableColumn,
                        from:table,
                        where:where,
                        _tokken:_tokken
                    },
                    success:function(response){
                        var data = $.parseJSON(response);
                        selectEvent.html("");
                        var option = "<option value='' selected>"+placeholder+"</option>";
                        selectEvent.append(option);

                        if(data){
                            $.each(data,function(index,value){
                            if(selected_id==value.id){
                                var option = "<option value='"+value.id+"' selected>"+value.name+"</option>";
                            }
                            else{
                                var option = "<option value='"+value.id+"' >"+value.name+"</option>";
                            }
                            selectEvent.append(option);
                         });
                        }
                        else{
                            var option = "<option value='' >NO RECORD FOUND</option>";
                            selectEvent.append(option);
                        }
                        
                    }
                });
                return false;
            }

            // country dropdown
            var country_id = "<?php echo base64_decode($country_id);?>";
            
            Get_dropDropdown_by_Ajax('regulation_country','SELECT COUNTRY','country_id as id,country_name as name','mst_country','',country_id);

            // division dropdown
            var category_id = "<?php echo base64_decode($category_id);?>";
            Get_dropDropdown_by_Ajax('regulation_category','SELECT CATEGORY','division_id as id,division_name as name','mst_divisions','',category_id);

            // notified body dropdown
            var notified_body_id = "<?php echo base64_decode($notified_body_id);?>";
            Get_dropDropdown_by_Ajax('regulation_notified_body','SELECT NOTIFIED BODY','notified_body_id as id,notified_body_name as name','notified_body','',notified_body_id);



            // ADD REGULATIONS

            $('.add').on('click',function(){

                Get_dropDropdown_by_Ajax('add_country','SELECT COUNTRY','country_id as id,country_name as name','mst_country','',null);

                Get_dropDropdown_by_Ajax('add_category','SELECT CATEGORY','division_id as id,division_name as name','mst_divisions','',null);

                Get_dropDropdown_by_Ajax('add_notified_body','SELECT NOTIFIED BODY','notified_body_id as id,notified_body_name as name','notified_body','',null);
            });

            
            $('#add_regulation').on('submit',function(e){
                e.preventDefault();
                var formData = new FormData(this);
            
                $.ajax({
                    url:"<?php echo base_url('Regulations/add_regulations')?>",
                    method:"POST",
                    data:formData,
                    contentType: false,
                    processData: false,
                    success:function(response){
                        var result = $.parseJSON(response);
                        if(result.status>0){
                            $('.add_regulations').modal('hide');
                            $("#add_regulation").trigger('reset');
                            window.location.reload();
                        }
                        else{
                            $.notify(result.msg, 'error');
                        }
                        if (result.errors) {
                        var error = result.errors;
                        $('.regulation_add').remove();
                        $.each(error, function(i, v) {
                            $('#add_regulation input[name="' + i + '"]').after('<span class="text-danger regulation_add">' + v + '</span>');
                            $('#add_regulation select[name="' + i + '"]').after('<span class="text-danger regulation_add">' + v + '</span>');
                            $('#add_regulation textarea[name="' + i + '"]').after('<span class="text-danger regulation_add">' + v + '</span>');
                        });

                    } else {
                        $('.regulation_add').remove();
                    }
                    }
                });
                return false;
            })
            
            // END


            // EDIT REGULATION
            $('.edit_reg').on('click',function(){

                var regulation_id = $(this).data('id');
                get_edit_regulation_data(regulation_id);
               
            });

            function get_edit_regulation_data(regulation_id){
                const _tokken = $('meta[name="_tokken"]').attr("value");
                $.ajax({
                    url:"<?php echo base_url('Regulations/edit_regulations')?>",
                    method:"POST",
                    data:{
                        regulation_id:regulation_id,
                        _tokken:_tokken
                    },
                    success:function(response){
                        var data = $.parseJSON(response);
                        if(data){
                            Get_dropDropdown_by_Ajax('edit_country','SELECT COUNTRY','country_id as id,country_name as name','mst_country','',data.country_id);

                            Get_dropDropdown_by_Ajax('edit_category','SELECT CATEGORY','division_id as id,division_name as name','mst_divisions','',data.division_id);

                            Get_dropDropdown_by_Ajax('edit_notified_body','SELECT NOTIFIED BODY','notified_body_id as id,notified_body_name as name','notified_body','',data.notified_body_id);
    
                            $('.edit_title').val(data.title);
                            $('.notification_date').val(data.notification_date);
                            $('.tat').html(data.tat_description);
                            $('.regulations_id').val(regulation_id);
                            $('.file_name').val(data.file_name);
                        }
                    }
                });
                return false;
            }

            $('#edit_regulation').on('submit',function(e){
                e.preventDefault();
                var formData = new FormData(this);
            
                $.ajax({
                    url:"<?php echo base_url('Regulations/update_regulations')?>",
                    method:"POST",
                    data:formData,
                    contentType: false,
                    processData: false,
                    success:function(response){
                        var result = $.parseJSON(response);
                        if(result.status>0){
                            $('.edit_regulations').modal('hide');
                            $("#edit_regulation").trigger('reset');
                            window.location.reload();
                        }
                        else{
                            $.notify(result.msg, 'error');
                        }
                        if (result.errors) {
                        var error = result.errors;
                        $('.regulation_edit').remove();
                        $.each(error, function(i, v) {
                            $('#edit_regulation input[name="' + i + '"]').after('<span class="text-danger regulation_edit">' + v + '</span>');
                            $('#edit_regulation select[name="' + i + '"]').after('<span class="text-danger regulation_edit">' + v + '</span>');
                            $('#edit_regulation textarea[name="' + i + '"]').after('<span class="text-danger regulation_edit">' + v + '</span>');
                        });

                    } else {
                        $('.regulation_edit').remove();
                    }
                    }
                });
                return false;
            })
            // END



            // user log detail fetching
                $('.user_log_btn').on('click',function(){
                    var regulation_id = $(this).data('id');
                    get_user_log_data(regulation_id);
                    
                });

            function get_user_log_data(regulation_id){
                const _tokken = $('meta[name="_tokken"]').attr("value");
                $.ajax({
                    url:"<?php echo base_url('Regulations/get_user_log_data')?>",
                    method:"POST",
                    data:{
                        regulation_id:regulation_id,
                        _tokken:_tokken
                    },
                    success:function(response){
                        var data = $.parseJSON(response);
                        $('.user_table tbody').html("");
                        if(data){
                            var serial = 1;
                            $.each(data,function(index,value){
                                row = "<tr>";
                                row+= "<td>"+serial+"</td>";
                                row+= "<td>"+value.title+"</td>";
                                row+= "<td>Insert By "+value.created_by+"</td>";
                                row+= "<td>"+value.date+"</td>";
                                row+= "</tr>";
                                $('.user_table tbody').append(row);
                                serial++;
                            });
                        }
                        else{
                            row = "<tr>";
                            row+= "<td colspan=3>";
                            row+= "<h6>NO RECORD FOUND!</h6>";
                            row+= "</td>";
                            row+= "</tr>";
                            $('.user_table tbody').append(row);
                        }
                    }
                });
                return false;
            }
            // end


            // add changes 
                    $('.add_change_btn').on('click',function(){
                        var regulation_id = $(this).data('id');
                        $('.regulation_id').val(regulation_id);
                    })
               
                $('#add_changes_form').on('submit',function(e){
                    e.preventDefault();
                var formData = new FormData(this);
                formData.append('notification_description', CKEDITOR.instances['notification_description'].getData());
                $.ajax({
                    url:"<?php echo base_url('Regulations/add_changes')?>",
                    method:"POST",
                    data:formData,
                    contentType: false,
                    processData: false,
                    success:function(response){
                        var result = $.parseJSON(response);
                        if(result.status>0){
                            $('.add_changes_window').modal('hide');
                            $("#add_changes_form").trigger('reset');
                            $('.ckeditor').html("");
                            window.location.reload();
                        }
                        else{
                            $.notify(result.msg, 'error');
                        }
                        if (result.errors) {
                        var error = result.errors;
                        $('.add_change_add').remove();
                        $.each(error, function(i, v) {
                            $('#add_changes_form input[name="' + i + '"]').after('<span class="text-danger add_change_add">' + v + '</span>');
                            $('#add_changes_form textarea[name="' + i + '"]').after('<span class="text-danger add_change_add">' + v + '</span>');
                        });

                    } else {
                        $('.add_change_add').remove();
                    }
                    }
                });
                return false;
                })
            // end
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
            url: url + 'Regulations/get_log_data',
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
                $('#table_log').append(value);
                });
                
            }
            });
        });
        // ajax call to get log ends here
    });
</script>


