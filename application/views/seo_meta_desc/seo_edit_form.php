<!DOCTYPE html>
<html lang="en">
 <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <script src="<?= base_url('ckeditor/ckeditor.js');?>"></script>
<script src="<?php echo base_url('assets/plugins/jquery-validation/jquery.validate.min.js'); ?>"></script>
   <script>
     CKEDITOR.replace('description1');
   </script>

    </head>
    
    <body>
        <?= form_open_multipart('SEOMetaDesc/update_seo', ['id' => 'edit_seo_meta_form']); ?>
        <input type="hidden" name="page_id" value="<?= isset($seo['page_id']) ? $seo['page_id']: '';?>">
                      <div class="row">
                         <div class="col-md-6">
                                <label><span class="text-danger">*</span> Page Title:</label>
                                <input name="page_title" class="form-control" 
                                       value="<?= isset($seo['page_title']) ? $seo['page_title']: '';?>" required>
                         </div>
                      </div>
                        
                        <div class="row">
                         <div class="col-md-6">
                                <label><span class="text-danger">*</span> Keywords:</label>
                                <input name="keywords" class="form-control" value="<?= isset($seo['keywords']) ? $seo['keywords']: '';?>" required>
                            </div>
                        </div>
                        
                        <div class="row">
                         <div class="col-md-8">
                                <label><span class="text-danger">*</span> Page URL:</label>
                                <input name="page_url" class="form-control" value="<?= isset($seo['page_url']) ? $seo['page_url']: '';?>" required>
                            </div>
                        </div>
                        
                        <div class="row">
                         <div class="col-md-10">
                                <label><span class="text-danger">*</span> Description:</label>
                                <textarea class="ckeditor" name="description1"><?= isset($seo['description']) ? html_entity_decode($seo['description']): '';?></textarea>
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
