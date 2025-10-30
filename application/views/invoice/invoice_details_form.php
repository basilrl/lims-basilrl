
<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
  
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
<script>
$(document).ready(function(){
  $('.select-box').select2({
      allowClear: false,
      placeholder: 'Select Proforma Number',
      minimumInputLength: 0,
      multiple : true
  });
})
</script>
    </head>
    <body>
        
       <form action="<?php echo base_url('invoices/generateInvoice'); ?>" method="post" id="save_invoice_details">
           <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">

           <input type="hidden" name="sample_reg_id" value="<?php echo $sample_reg_id;?>">
           <input type="hidden" name="profoma_invoice_id" value="<?php echo $proforma_invoice_id;?>">
           <div class="container">
               <div class="row">
                   <div class="col-sm-6">
                       <select name="proforma_invoice_ids[]" class="form-control form-control-sm select-box" multiple required>
                           <?php foreach ($gc_nums as $pi_no) {?>
                           <option value="<?php echo $pi_no['proforma_invoice_id'];?>"><?php echo $pi_no['proforma_invoice_number'];?></option>
                           <?php }?>
                       </select>  
                   </div>                       
               </div>
               <div class="row">
                   <div class="col-sm-4">
                       <label>Invoice Quantity</label>
                       <input type="number" name="inspection_qty" class="form-control form-control-sm">
                   </div>          
                   <div class="col-sm-4">
                       <label>Inspection Date BL</label>
                       <input type="date" name="inspection_date_bl" value="<?php echo date("Y-m-d");?>" class="form-control form-control-sm" required>
                   </div>          
                   <div class="col-sm-4">
                       <label>Vessel Name</label>
                       <input type="text" name="vessel_name" class="form-control form-control-sm">
                   </div>          
                        

               </div>
               
              <div class="row">
                   <div class="col-sm-4">
                       <label>Sample Rec Date</label>
                       <input type="date" name="sample_rec_date" value="<?php echo isset($invoice_detail['proforma_invoice_date']) ? date("Y-m-d",strtotime($invoice_detail['proforma_invoice_date'])) : '';?>" class="form-control form-control-sm" required>
                   </div>    
                   <div class="col-sm-4">
                       <label>Product</label>
                       <input type="text" name="product" value="<?php echo isset($invoice_detail['sample_name']) ? $invoice_detail['sample_name'] : '';?>" class="form-control form-control-sm">
                   </div>          
                   <div class="col-sm-4">
                       <label>Supply Date</label>
                       <input type="date" name="supply_date" value="<?php echo date("Y-m-d");?>" class="form-control form-control-sm" required>
                   </div>          
                       
                         

               </div>
               
               <div class="row">
                    <div class="col-sm-4">
                       <label>Certificate Report Number</label>
                       <input type="text" value="<?php echo isset($invoice_detail['report_num']) ? $invoice_detail['report_num'] : '';?>" name="certificate_report_no" class="form-control form-control-sm">
                   </div>     
                   <div class="col-sm-4">
                       <label>Contract No</label>
                       <input type="text" name="contract_no" class="form-control form-control-sm">
                   </div>    
                   <div class="col-sm-4">
                      <label>LPO No</label>
                       <input type="text" name="lpo_no" class="form-control form-control-sm"> 
                   </div>
               </div>
               
                <!--ADDED ON 13-10-2021-->
               <div class="row">
                    <div class="col-sm-4">
                       <label>Nomination Contact</label>
                       <input type="text" name="nomination_contact" class="form-control form-control-sm">
                   </div>     
                   <div class="col-sm-4">
                       <label>Customer email address</label>
                       <input type="text" name="customer_email" class="form-control form-control-sm">
                   </div>    
                   <div class="col-sm-4">
                      <label>Contact person name</label>
                       <input type="text"  name="contact_person_name" class="form-control form-control-sm"> 
                   </div>
               </div>
               
               <div class="row">
                    <div class="col-sm-4">
                       <label>Job Reference Number</label>
                       <input type="text" name="job_ref_no" class="form-control form-control-sm">
                   </div>     
                   <div class="col-sm-4">
                       <label>Style Number</label>
                       <input type="text" name="style_no" class="form-control form-control-sm">
                   </div>    
                   <div class="col-sm-4">
                      <label>Quote Reference Number</label>
                       <input type="text" name="quotes_ref_no" class="form-control form-control-sm"> 
                   </div>
               </div>
           
           <!--END-->
     
            
             <button type="button" class="btn btn-secondary mt-2 mb-2" data-bs-dismiss="modal">Close</button>
             <button type="submit" class="btn btn-primary mt-2 mb-2" value="Update">Generate</button>
        </form>

</div>
    </body>
</html>

