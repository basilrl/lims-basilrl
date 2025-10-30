<?php

$home = base_url();
$list = base_url('packages');

$case = $this->uri->segment('1');
$op = $this->uri->segment('2');

if ($case=="insert_packages"){

          $title = "ADD NEW PACKAGE";  
          $active_title = 'Add New';
          $action = base_url('insert_packages'); 
          $set_package_name = set_value('package_name');
          $set_packages_sample_type_id=set_value('packages_sample_type_id');
          $sample_name = set_value('sample_name');
          $active =   set_select('package_status','1',true);
          $inactive =  set_select('package_status','0',true);
          $buyer =  set_select('buyer');
          $button = "Submit";
}

elseif($case =="open_packages" && $op=="update"){
          $data = $item['data'][0];
          $package_id = $item['package_id'];
          $title = "UPDATE PACKAGE";  
          $active_title = 'Update';
          $action = base_url('update_packages/').$package_id; 
          $set_package_name = $data->package_name;
          $set_packages_sample_type_id=$data->packages_sample_type_id;
          $sample_name =  $data->product_name;
          $active =   ($data->package_status)=="1"?"selected":"";
          $inactive =  ($data->package_status)=="0"?"selected":""; 
          $buyer =  ($data->buyer) ;
          $button = "Update";

}

elseif($case =="update_packages"){

          $data = $item['data'][0];
          $package_id = $item['package_id'];
          $title = "UPDATE PACKAGE";  
          $active_title = 'Update';
          $action = base_url('update_packages/').$package_id; 
          $set_package_name = set_value('package_name');
          $set_packages_sample_type_id=set_value('packages_sample_type_id');
          $sample_name = set_value('sample_name');
          $active =   set_select('package_status','1',true);
          $inactive =  set_select('package_status','0',true);
          $buyer =  set_select('buyer');
          $button = "Update";
}
else{
  $title = "ADD NEW PACKAGE";  
  $active_title = 'Add New';
  $action = base_url('insert_packages'); 
  $set_package_name = "";
  $set_packages_sample_type_id="";
  $sample_name = "";
  $active =  "";
  $inactive =  "";
  $buyer =  "";
  $button = "Submit";
}
$buyers = $item['buyers'];
?> 




<style>
form .error {
  color: #ff0000;
  margin-top:0;
}

</style>


 <div class="content-wrapper">
    <!-- Content Header (Page header) -->

    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>PACKAGES</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?php echo $home;?>">Home</a></li>
              <li class="breadcrumb-item"><a href="<?php echo $list;?>">Packages</a></li>
              <li class="breadcrumb-item active"><?php echo $active_title;?></li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>


    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <!-- left column -->
          <div class="col-md-12">
            <!-- general form elements -->
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title"><?php echo $title;?></h3>
              </div>
            

        <form action="<?php echo $action;?>" method="post" enctype="multipart/form-data" name="package_form">
        <div class="card-body">
            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash();?>">

               <div class="row px-3 p-3">

                    <div class=" col-md-12 px-3">
                                <label for="">PACKAGE NAME:</label>
                                <input class="form-control  input-sm" name="package_name" value="<?php echo $set_package_name;?>" type="text">
                                <?php echo form_error('package_name'); ?>
                        </div>
               </div>

               <div class="row px-3 p-3">
               <div class="col-md-12 px-3">
                                <label for="">PRODUCT:</label>
                                <input  class="sample_id" type="hidden" value="<?php echo $set_packages_sample_type_id;?>" name="packages_sample_type_id">
                                <input class="form-control  input-sm sample_name" value="<?php echo $sample_name;?>" autocomplete="off" name="sample_name" type="text" placeholder="Select Product">
                                <ul class="list-group-item sample_list" style="display:none">
                                </ul>
                                <?php echo form_error('packages_sample_type_id');?>
                                <?php echo form_error('sample_name');?>
                         </div>

               </div>

                 
                
                <div class="row px-3 p-3">
                       <div class="col-md-12 px-3">
                            <label for="">STATUS:</label>
                            <select  class="form-control  input-sm" name="package_status" id="">
                                <option value="" selected disabled>Select</option>
                                <option value="1" <?php echo $active;?>>ACTIVE</option>
                                <option value="0" <?php echo $inactive;?>>IN-ACTIVE</option>
                            </select>
                       </div>
                </div>

                   <!-- new buyer changes -->
                   <div class="row px-3 p-3">
                  <div class="col-md-12 px-3">
                    <label for="">BUYER:</label>
                    <select class="form-control  input-sm" name="buyer" id="buyer">
                      <option value="" selected disabled>Select Buyer ...</option>
                      <?php foreach ($buyers as $key => $value) { ?>
                        <option value="<?=$value->customer_id?>" <?php if($buyer == $value->customer_id) { echo 'selected';}?>><?=$value->customer_name?></option>
                      <?php } ?>
                    </select>
                    <?php echo form_error('buyer'); ?>
                  </div>
                </div>
                    
                <br>
                <br>
                    <div class="card-footer">
                    <a href="<?php echo $list;?>" class="btn btn-primary" type="submit">Back</a>
                  <button type="submit" class="btn btn-primary" style="float: right;" id="submit" ><?php echo $button;?></button>
                </div>
              </form>
            </div>
            <!-- /.card -->

          </div>
          <!--/.col (left) -->
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <script src="<?php echo base_url('assets/dist/js/test_management.js'); ?>"></script>
<script>
  $(document).ready(function(){
    $('#buyer').select2();
        $("form[name='package_form']").validate({      
          rules: {
            package_name: "required",
            sample_name: "required",
            package_status:{
              required: true
            }
          },
          messages: {
            package_name: "This is required field please fill it",
            sample_name: "This is required field please fill it",
            package_status:{
              messages: "Plase select status"
            }
        },
          submitHandler: function(form) {    
            form.submit(); 
          }
       });

   })
 
</script>
  