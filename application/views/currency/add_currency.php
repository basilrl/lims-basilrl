<?php
if(empty($currency)){
    $name = set_value('currency_name');
    $code = set_value('currency_code');
    $status = set_value('status');
    } else{
        $name = $currency->currency_name;
        $code = $currency->currency_code;
        $status = $currency->status;
    }
?>

<div class="content-wrapper">
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Currency </h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="<?php echo base_url();?>">Home</a></li>
            <li class="breadcrumb-item"><a href="<?php echo base_url('Currency');?>">Currency</a></li>
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
          <h3 class="card-title"> Currency</h3>
        </div>
        <!-- /.card-header -->
        <form action="" method="post" autocomplete="off">
        <div class="card-body">
        <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
          <div class="row">

          <div class="col-md-6">
                <div class="form-group">
                <label>Currency Code :</label>
                <input type="text" name="currency_code" class="form-control" style="width: 100%;" value="<?=$code;?>">
                <?php echo form_error('currency_code', '<div class="text-danger">', '</div>'); ?>
              </div>
            </div>

            <div class="col-md-6">
              <div class="form-group">
                <label>Currency Name :</label>
                <input type="text" name="currency_name" class="form-control" style="width: 100%;" value="<?=$name;?>">
                <?php echo form_error('currency_name', '<div class="text-danger">', '</div>'); ?>
              </div>
            </div>  

            <div class="col-md-6">
              <div class="form-group">
                <label>Basic Unit :</label>
                <input type="text" name="basic_unit" class="form-control" style="width: 100%;" value="<?=$name;?>">
                <?php echo form_error('basic_unit', '<div class="text-danger">', '</div>'); ?>
              </div>
            </div> 

            <div class="col-md-6">
              <div class="form-group">
                <label>Fractional Point :</label>
                <input type="text" name="fractional_point" class="form-control" style="width: 100%;" value="<?=$name;?>">
                <?php echo form_error('fractional_point', '<div class="text-danger">', '</div>'); ?>
              </div>
            </div>

            <div class="col-md-6">
              <div class="form-group">
                <label>Decimal Point :</label>
                <input type="text" name="decimal_point" class="form-control" style="width: 100%;" value="<?=$name;?>">
                <?php echo form_error('decimal_point', '<div class="text-danger">', '</div>'); ?>
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
              <a href="<?php echo base_url('Currency/index');?>"><button type="button" class="btn btn-danger">Cancel</button></a>
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