
<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">

    </head>
    <body>
        
        <form action="<?php echo base_url('invoices/updateClient'); ?>" method="post" id="save_test">
            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
           
            <input type="hidden" name="invoice_id" value="<?php echo $invoice_id; ?>">
 
            <div class="container">
                
                <div class="row"> 
                    <div class="col-sm-6">
                        <label>Choose Client</label>
                        <select name="invoice_client_id" class="form-control form-control-sm" required>
                            <?php foreach ($client_detail as $cd){?>
                            <option value="<?php  echo $cd['customer_id'];?>"><?php echo $cd['customer_name'];?> </option>
                            <?php }?>
                        </select>
                    </div>                        
                </div>

                <button type="button" class="btn btn-secondary mt-2 mb-2" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary mt-2 mb-2" value="Update">Save Changes</button>


            </div>
        </form>
    </body>
</html>


   