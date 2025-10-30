
</style>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>UPDATE PACKAGE</h1>
          </div>
          <div class="col-sm-6">
<!--            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Simple Tables</li>
            </ol>-->
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>
    <div class="container">
            <?php $data=$item[0];?>

        <form action="<?php echo base_url('update_packages/'.$data->package_id)?>" method="post" enctype="multipart/form-data">
            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash();?>">

               <div class="row px-3 p-3">

                    <div class=" col-md-6 px-3">
                                <label for="">PACKAGE NAME:</label>
                                <input class="form-control  input-sm" name="package_name" value="<?php echo $data->package_name?>" type="text">
                                <?php echo form_error('package_name'); ?>
                        </div>
               </div>

               <div class="row px-3 p-3">
               <div class="col-md-6 px-3">
                                <label for="">PRODUCT:</label>
                                <input  class="sample_id" type="hidden" value="<?php echo $data->packages_sample_type_id?>" name="packages_sample_type_id">
                                <input class="form-control  input-sm sample_name" value="<?php echo $data->product_name?>" autocomplete="off" name="product_name" type="text" placeholder="Select Product">
                                <ul class="list-group-item sample_list" style="display:none">
                                </ul>
                                <?php echo form_error('packages_sample_type_id');?>
                                <?php echo form_error('product_name');?>
                         </div>

               </div>

                 
                
                <div class="row px-3 ">
                       <div class="col-md-6 px-3">
                            <label for="">STATUS:</label>
                            <select  class="form-control  input-sm" name="package_status" id="">
                                <option value="1" <?php echo ($data->package_status=='1')?'selected':'';?>>ACTIVE</option>
                                <option value="0" <?php echo ($data->package_status=='0')?'selected':'';?>>IN-ACTIVE</option>
                            </select>
                       </div>
                </div>
                    
                <div class="row mt-2 text-right px-3">
                        <div class="col-sm-12 px-3">
                        
                            <a href="<?php echo base_url('packages')?>" class="btn btn-primary" type="submit">Back</a>
                            <button class="btn btn-primary" type="submit">Update</button>
                        </div>
                    </div>    
            
                    
            </form>
        </div>
    
        <script src="<?php echo base_url('assets/dist/js/test_management.js'); ?>"></script>

