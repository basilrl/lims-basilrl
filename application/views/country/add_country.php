<?php
if(empty($country)){
    $name = set_value('country_name');
    $code = set_value('country_code');
    $status = set_value('status');
    } else{
        $name = $country->country_name;
        $code = $country->country_code;
        $status = $country->status;
    }
?>

<div class="content-wrapper">
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Country </h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="<?php echo base_url();?>">Home</a></li>
            <li class="breadcrumb-item"><a href="<?php echo base_url('Counrty');?>">Country</a></li>
            <li class="breadcrumb-item active">Add Counrty</li>
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
      <div class="card card-primary">
        <div class="card-header">
          <h3 class="card-title"> Country</h3>
        </div>
        <!-- /.card-header -->
        <form action="" method="post" autocomplete="off">
        <div class="card-body">
        <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
          <div class="row">

          <div class="col-md-6">
                <div class="form-group">
                <label>Country Code :</label>
                <input type="text" name="country_code" class="form-control" style="width: 100%;" value="<?=$code;?>">
                <?php echo form_error('country_code', '<div class="text-danger">', '</div>'); ?>
              </div>
            </div>

            <div class="col-md-6">
              <div class="form-group">
                <label>Country Name :</label>
                <input type="text" name="country_name" class="form-control" style="width: 100%;" value="<?=$name;?>">
                <?php echo form_error('country_name', '<div class="text-danger">', '</div>'); ?>
              </div>
            </div>  

            <div class="col-md-6">
              <div class="form-group">
                <label>Status :</label>
                <select name="status" id="" class="form-control">
                    <option disabled selected>Select Status</option>
                    <option value="1" <?php if($status == "1"){ echo "selected"; }?>>Active</option>
                    <option value="0" <?php if($status == "0"){ echo "selected"; }?>>Inactive</option>
                </select>
                <?php echo form_error('status', '<div class="text-danger">', '</div>'); ?>
              </div>
            </div>

            
          </div>     
        </div>
          <!-- /.card-body -->
          <div class="card-footer" >
            <div style="margin-left: 43%;">
              <a href="<?php echo base_url('Country/index');?>"><button type="button" class="btn btn-danger">Cancel</button></a>
              <button type="submit" name="submit" class="btn btn-primary">Save</button>
            </div>  
          </div>
      </div>
      </form>
      </div>
      </div>
  
        </div>
        <!-- /.col (right) -->
      </div>
      <!-- /.row -->
    </div><!-- /.container-fluid -->
  </section>
</div>