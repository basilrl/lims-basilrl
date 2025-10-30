<!-- Edit view of mst category by kamal on 6th of june 2022; -->
<div class="content-wrapper">
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Mst Category</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">

            <!-- links to redirect on previous page on mst category listing by kamal on 6th of june 2022 -->

            <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>">Home</a></li>
            <li class="breadcrumb-item"><a href="<?php echo base_url('Mst_category/index'); ?>">Mst Category</a></li>
            <li class="breadcrumb-item active">Edit Instruction</li>
          </ol>
        </div>
      </div>
    </div>

    <div class="col-md-12">
          <div class="card card-primary">
            <div class="card-header">
              <h3 class="card-title">Mst Category</h3>
            </div>

            <form  method="post" name="frmedit">

              <!-- Crsf token for login security on mst category by kamal on 6th of june 2022 -->

            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
            <input type="hidden" name="id" id="id" value="<?php echo $mst_category['category_id']; ?>">  
            <div class="row">
         
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Category Name :</label>
                      <input type="text" name="name" value="<?php echo $mst_category['category_name']; ?>"  class="form-control" style="width: 100%;">
                
                      <?php echo form_error('name', '<div class="text-danger">', '</div>'); ?>
                    </div>
                  </div>
                <div class="col-md-6">
                    <div class="form-group">
                      <label>Category Code :</label>
                      <input type="text" name="code" value="<?php echo $mst_category['category_code']; ?>"  class="form-control" style="width: 100%;">
                      <?php echo form_error('code', '<div class="text-danger">', '</div>'); ?>
                    </div>
                </div>

                  <div class="form-group" style="position: relative; left:500px;">
                           <center> <input type="submit" value="Update" name="btnEdit" class="btn btn-primary btn-lg">
                           <button  class="btn btn-danger btn-lg"><a style="color:white;" href="<?php echo base_url('Mst_category/index'); ?>">Cancel</a></button>
                          </center>
                        </div>
            </form>
      </div>
      </div>
    </div>