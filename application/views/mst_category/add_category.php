
    <!-- Add view of mst category by kamal on 6th of june 2022; -->

  <div class="content-wrapper">
    <section class="content-header">
      <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Mst category</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">

          <!-- links for previous page for mst category listing by kamal on 6th of june 2022 -->

            <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>">Home</a></li>
            <li class="breadcrumb-item"><a href="<?php echo base_url('Mst_category/index'); ?>">Mst Category</a></li>
            <li class="breadcrumb-item active">Add Category</li>
          </ol>
        </div>
      </div>
    </div>

        <div class="col-md-12">
          <div class="card card-primary">
            <div class="card-header">
              <h3 class="card-title">Mst Category</h3>
            </div>

            <form action="" method="post" name="frmadd" >

            <!-- crsf token for login security on mst category listing by kamal on 6th of june 2022 -->

            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
            <div class="row">
         
          <div class="col-md-6">
                    <div class="form-group">
                      <label>Mst Category Name :</label>
                      <?php echo form_input(['class'=>'form-control','name'=>'name','value'=>set_value('name')]);?>
                      <!-- <input type="text" name="name"  required class="form-control" style="width: 100%;"> -->
        <!-- <center> <h2 style="color:green;"><?php echo $this->session->flashdata('message_name'); ?></h2> </center> -->
                      <?php echo form_error('name', '<div class="text-danger">', '</div>'); ?>
                    </div>
          </div>
          <div class="col-md-6">
                    <div class="form-group">
                      <label>Mst Category Code  :</label>
                      <?php echo form_input(['class'=>'form-control','name'=>'code','value'=>set_value('code')]);?>
                      <?php echo form_error('code', '<div class="text-danger">', '</div>'); ?>
                    </div>
        </div>
    
          <div class="form-group" style="position: relative; left:500px;">
                   <input type="submit" value="Submit" name="btnadd" class="btn btn-primary btn-lg">
                   <button  class="btn btn-danger btn-lg"><a style="color:white;"  href="<?php echo base_url('Mst_category/index'); ?>">Cancel</a></button>
                       
          </div>
        </form>
    </div>
    </div>
    </div>