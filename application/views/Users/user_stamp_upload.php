
<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">

    </head>
    <body>
        <div class="">
            <form action="<?php echo base_url('users/uploadStamp');?>" method="post" enctype="multipart/form-data" name="upload_signature" id="form_upload_signature">
                <div class="modal-body">
                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                    <input type="hidden" name="admin_id" id="admin_id" value="<?php echo  $userStamp['uidnr_admin'];?>">

                    <div class="col-row">
                        <div class="col-md-12">
                             <?php if(!empty($userStamp['stamp_path'])){?>
                              <img src="<?php echo $userStamp['stamp_path'];?>" >
                             <?php }?>
                        </div>
                        <div class="col-md-12">
                            <label for="">Upload Stamp</label>
                            <input type="file" name="stamp_path" id="stamp" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer ">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">submit</button>
                </div>
            </form>
        </div>
    </body>
</html>

 
   