
    <!-- Add view of mst category by kamal on 6th of june 2022; -->
    <script src="<?php echo base_url(); ?>ckeditor/ckeditor.js"></script>
    <div class="content-wrapper">
    <section class="content-header">
      <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Quote Contact Details</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">

          <!-- links for previous page for mst category listing by kamal on 6th of june 2022 -->

            <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>">Home</a></li>
            <li class="breadcrumb-item"><a href="<?php echo base_url('Quote_details/index'); ?>">Quote Contact Details</a></li>
            <li class="breadcrumb-item active">Add Detail</li>
          </ol>
        </div>
      </div>
    </div>

        <div class="col-md-12">
          <div class="card card-primary">
            <div class="card-header">
              <h3 class="card-title">Add Quote Contact Details</h3>
            </div>

         
            <form action="" method="post" name="frmadd" >

            <!-- crsf token for login security on mst category listing by kamal on 6th of june 2022 -->

            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
            <div class="row">
         
            <div class="col-md-5" style="margin-left:6px;">
                <label>Division:</label>
                 <select name="division" id="division" class="form-control ">
                   <option disabled selected>Select Division...</option>
                 
                   <?php if (isset($division_name)) {
                     foreach ($division_name as $de) { ?>
                         <option  value="<?php echo $de->division_id; ?>" > <?php echo $de->division; ?> </option>
                      <?php } }?>
                     
                    </select>
                    <?php echo form_error('division', '<div class="text-danger">', '</div>'); ?>
                    <br><br>
                    

                  <!-- </div>    -->
                  </div>
                  
                  <div class="col-md-5 mt-4">
                  <select name="status" class="form-control">
                          <option  disabled selected>select</option>
                        
                          
                          <option value="active" >Active</option>
                          <option value="inactive" >Inactive</option>
                      </select>
                      <?php echo form_error('status', '<div class="text-danger">', '</div>'); ?>
                  </div>

                   <div class="col-md-12">
                        <div class="form-group">
                        <label>Contact Person Detail :</label>
                        <?php echo 
                          form_textarea(['class'=>'form-control','name'=>'detail', 'id'=>'detail','value'=>set_value('detail')]);?>
                        <?php
                           echo form_error('detail', '<div class="text-danger">', '</div>');?>
                        </div>
            </div>
                  
          <div class="form-group" style="position: relative; left:500px;">
                   <input type="submit" value="Submit" name="btnadd" class="btn btn-primary btn-lg">
                   <button  class="btn btn-danger btn-lg"><a style="color:white;"  href="<?php echo base_url('Quote_details/index'); ?>">Cancel</a></button>
                       
          </div>
          <!-- <div class="row"> -->
             
          <!-- </div> -->
        </form>
    </div>
    </div>
    </div>
<script>
  $(document).ready(function(){
    CKEDITOR.replace('detail');
  })
</script>