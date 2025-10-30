<!-- EDIT INSTRUCTION FOR APPLICATION CARE INSTRUCTION BY KAMAL ON 6TH JUNE 2022 -->

<div class="content-wrapper">
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Application Care Instruction</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>">Home</a></li>
            <li class="breadcrumb-item"><a href="<?php echo base_url('Care/index'); ?>">Care Instruction</a></li>
            <li class="breadcrumb-item active">Edit Instruction</li>
          </ol>
        </div>
      </div>
    </div>

    <div class="col-md-12">
          <div class="card card-primary">
            <div class="card-header">
              <h3 class="card-title">Application Care Instruction</h3>
            </div>

            <form action="" method="post" name="frmedit" enctype="multipart/form-data">

            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
            <input type="hidden" name="id" id="id" value="<?php echo $application_care_instruction['instruction_id']; ?>">  
            <div class="row">
         
    <div class="col-md-6">
                    <div class="form-group">
                      <label>Instruction Name :</label>
                      <input type="text" name="name" value="<?php echo $application_care_instruction['instruction_name']; ?>" required class="form-control" style="width: 100%;">
                
                      <?php echo form_error('name', '<div class="text-danger">', '</div>'); ?>
                    </div>
</div>
<div class="col-md-6">
                    <div class="form-group">
                      <label>Instruction Type :</label>
                      <input type="text" name="type" value="<?php echo $application_care_instruction['instruction_type']; ?>" required class="form-control" style="width: 100%;">
                      <?php echo form_error('type', '<div class="text-danger">', '</div>'); ?>
                    </div>
        </div>
    
        <div class="col-md-6">
                    <div class="form-group">
                      <label>Instruction Image :</label><br>
                      <img style="height: 100px; width:100px;" src="<?php echo getS3Url2($application_care_instruction['instruction_image']) ?>" alt="image">
                      <input type="file" name="image"  class="form-control" style="width: 100%;">
                      <?php echo form_error('image', '<div class="text-danger">', '</div>'); ?>
                    </div>
        </div>

      <div class="col-md-6">
                    <div class="form-group">
                      <label>Care Wording :</label>
                      <input type="text" name="wording" value="<?php echo $application_care_instruction['care_wording']; ?>" required class="form-control" style="width: 100%;">
                      <?php echo form_error('wording', '<div class="text-danger">', '</div>'); ?>
                    </div>
        </div>
        
          <div class="col-md-6">
                    <div class="form-group">
                      <label>Priority Order :</label>
                      <input type="text" name="order" value="<?php echo $application_care_instruction['priority_order']; ?>" required class="form-control" style="width: 100%;">
                      <?php echo form_error('order', '<div class="text-danger">', '</div>'); ?>
                    </div>
          </div>    
              </div>

                  <div class="form-group">
                           <center> <input type="submit" value="Update" name="btnEdit" class="btn btn-primary btn-lg">
                           <button  class="btn btn-danger btn-lg"><a style="color:white;" href="<?php echo base_url('Care/index'); ?>">Cancel</a></button>
                          </center>
                        </div>
             </form>
</div>
</div>
</div>