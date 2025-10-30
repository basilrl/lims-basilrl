<!DOCTYPE html>
<html lang="en">
 <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        
       <script src="<?php echo base_url('assets/plugins/jquery-validation/jquery.validate.min.js'); ?>"></script>
 </head>
    
    <body>
        <?= form_open_multipart('CVG_Storage/update_cvg', ['id' => 'edit_seo_meta_form']); ?>
        <input type="hidden" name="cvg_storage_id" value="<?= isset($cvg['cvg_storage_id']) ? $cvg['cvg_storage_id']: '';?>">
                      
                <div class="row">
          <div class="col-md-6">
            <label><span class="text-danger">*</span> Title:</label>
            <input name="title" value="<?= isset($cvg['title']) ? $cvg['title']: '';?>" 
                   class="form-control"  required>
          </div>
        </div>

        <div class="row">
          <div class="col-md-6">
            <label><span class="text-danger">*</span> Country:</label>
            <select name="country" class="form-control" required>
            <option value=""></option>
            <?php if(isset($country)) { 
                foreach($country as $ctry){?>
            <option value="<?= $ctry->country_id;?>" <?php if($cvg['country_id'] == $ctry->country_id){echo "selected";}?>>
                <?= $ctry->country_name;?>
            </option>
            <?php } } ?>
            </select>
          </div>
        </div>

        <div class="row">
          <div class="col-md-8">
            <label><span class="text-danger">*</span> Accredited By:</label>
            <select name="accredited_by" class="form-control" required>
                <option value=""></option>
                <?php if(isset($accredited_by) && !empty($accredited_by)){                    
                    foreach ($accredited_by as $acr){?>
                <option value="<?= $acr['accredited_by_id'];?>" <?php if($acr['accredited_by_id'] == $cvg['accredited_by']){echo "selected";};?>>
                    <?= $acr['accredited_by'];?></option>
                <?php }}?>
            </select>
          </div>
        </div>

        <div class="row mt-3">
          <div class="col-md-10">
            <label><span class="text-danger"></span> File Upload:</label>
            <input type="file"  name="document" >
          </div>
        </div>

      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-success">Save</button>
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
      </div>          
               
                    
                     <?= form_close();?>
    </body>
</html>
