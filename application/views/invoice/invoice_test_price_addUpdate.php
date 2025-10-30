
<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">

    </head>
    <body>
        
       <form action="<?php echo base_url('invoices/saveDynamicTestPrices'); ?>" method="post" id="save_test">
           <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">

           <!--<input type="hidden" name="sample_reg_id" value="<?php echo $sample_reg_id;?>">-->
           <input type="hidden" name="invoice_id" value="<?php echo $invoice_id;?>">
           <!--ADDED ON 11-10-2021-->
             <div class="container">
               <div class="row">
                   <div class="col-sm-4">
                       <label>Invoice Quantity</label>
                       <input type="number" value="<?php echo isset($invoice_details->inspection_qty) ? $invoice_details->inspection_qty: ''; ?>" name="inspection_qty" class="form-control form-control-sm">
                   </div>          
                   <div class="col-sm-4">
                       <label>Inspection Date BL</label>
                       <input type="date" name="inspection_date_bl" value="<?php echo  isset($invoice_details->inspection_date_bl) ? $invoice_details->inspection_date_bl: date("Y-m-d");?>" class="form-control form-control-sm" required>
                   </div>          
                   <div class="col-sm-4">
                       <label>Vessel Name</label>
                       <input type="text" value="<?php echo isset($invoice_details->vessel_name) ? $invoice_details->vessel_name: ''; ?>" name="vessel_name" class="form-control form-control-sm">
                   </div>          
                        

               </div>
               
              <div class="row">
                   <div class="col-sm-4">
                       <label>Sample Rec Date</label>
                       <input type="date" name="sample_rec_date" value="<?php echo isset($invoice_details->sample_rec_date) ?  $invoice_details->sample_rec_date : '';?>" class="form-control form-control-sm" required>
                   </div>    
                   <div class="col-sm-4">
                       <label>Product</label>
                       <input type="text" name="product" value="<?php echo isset($invoice_details->product) ? $invoice_details->product : '';?>" class="form-control form-control-sm">
                   </div>          
                   <div class="col-sm-4">
                       <label>Supply Date</label>
                       <input type="date" name="supply_date" value="<?php echo isset($invoice_details->supply_date) ? $invoice_details->supply_date : date("Y-m-d");?>" class="form-control form-control-sm" required>
                   </div>          
                       
                         

               </div>
               
               <div class="row">
                    <div class="col-sm-4">
                       <label>Certificate Report Number</label>
                       <input type="text" value="<?php echo isset($invoice_details->certificate_report_no) ? $invoice_details->certificate_report_no : '';?>" name="certificate_report_no" class="form-control form-control-sm">
                   </div>     
                   <div class="col-sm-4">
                       <label>Contract No</label>
                       <input type="text" value="<?php echo isset($invoice_details->contract_no) ? $invoice_details->contract_no : '';?>"  name="contract_no" class="form-control form-control-sm">
                   </div>    
                   <div class="col-sm-4">
                      <label>LPO No</label>
                       <input type="text" value="<?php echo isset($invoice_details->lpo_no) ? $invoice_details->lpo_no : '';?>" name="lpo_no" class="form-control form-control-sm"> 
                   </div>
               </div>
               
               <div class="row">
                    <div class="col-sm-4">
                       <label>Nomination Contact</label>
                       <input type="text" value="<?php echo isset($invoice_details->nomination_contact) ? $invoice_details->nomination_contact : '';?>" name="nomination_contact" class="form-control form-control-sm">
                   </div>     
                   <div class="col-sm-4">
                       <label>Customer email address</label>
                       <input type="text" value="<?php echo isset($invoice_details->customer_email) ? $invoice_details->customer_email : '';?>"  name="customer_email" class="form-control form-control-sm">
                   </div>    
                   <div class="col-sm-4">
                      <label>Contact person name</label>
                       <input type="text" value="<?php echo isset($invoice_details->contact_person_name) ? $invoice_details->contact_person_name : '';?>" name="contact_person_name" class="form-control form-control-sm"> 
                   </div>
               </div>
               
               <div class="row">
                    <div class="col-sm-4">
                       <label>Job Reference Number</label>
                       <input type="text" value="<?php echo isset($invoice_details->job_ref_no) ? $invoice_details->job_ref_no : '';?>" name="job_ref_no" class="form-control form-control-sm">
                   </div>     
                   <div class="col-sm-4">
                       <label>Style Number</label>
                       <input type="text" value="<?php echo isset($invoice_details->style_no) ? $invoice_details->style_no : '';?>"  name="style_no" class="form-control form-control-sm">
                   </div>    
                   <div class="col-sm-4">
                      <label>Quote Reference Number</label>
                       <input type="text" value="<?php echo isset($invoice_details->quotes_ref_no) ? $invoice_details->quotes_ref_no : '';?>" name="quotes_ref_no" class="form-control form-control-sm"> 
                   </div>
               </div>
       </div>
           <!--END-->
           
           <div class="container">
               <div class="row">
                   <div class="col-sm-4 mt-2 mb-2">
                       <input id="addRow" onclick="AddRow('dynamic_testPrice_tbl', 'dynamic_testPrice_row')" type="button" class="btn btn-primary" value="Add Row">
                       <!--<input type="button" class="btn btn-danger del_row" value="Delete Multiple Row">-->
                   </div>

          </div>
               <table id="dynamic_testPrice_tbl" class="table table-bordered">
              <thead>
                  <tr>
                      <th>#</th>
                      <th>Test Name</th>
                      <th>Rate Per Test</th>
                      <th>Quantity</th>
                      <th>Discount(in %)</th>
                      <th>Applicable Charge</th>
                      
                  </tr>
              </thead>
           
            <tbody>
                  <?php $hidden_row = function() {?>  
                        <td>
                            <button type="button" class="bg-danger" onclick="delRow(this, 'dynamic_testPrice_tbl')">
                                <i class="fa fa-trash-alt text-white"></i>
                            </button>
                            <input type="hidden" name="test[inv_dyn_id][]">
                        </td>
                        <td><textarea  name="test[dynamic_heading][]" class="form-control form-control-sm"></textarea></td>
                        <td><input onkeyup="_calculatePrice()" type="number" step="0.01" name="test[dynamic_value][]" class="form-control form-control-sm rt"></td>
                        <td><input onkeyup="_calculatePrice()" type="number" step="0.01" name="test[quantity][]" class="form-control form-control-sm qt"></td>
                        <td><input onkeyup="_calculatePrice()" type="number" step="0.01" name="test[discount][]" class="form-control form-control-sm dsc"></td>
                        <td><input onchange="_calculatePrice()" onkeyup="function{ return false;}" type="number" step="0.01" name="test[applicable_charge][]" class="form-control form-control-sm tl" readonly></td>
                      <?php };?>
                <?php if (isset($dynamic_tests) && !empty($dynamic_tests)) { ?>
                    <tr id="dynamic_testPrice_row" hidden>
                    
                       <?php $hidden_row(); ?>
                    </tr>

                    <?php foreach ($dynamic_tests as $test) { ?>
                    <tr id="dynamic_testPrice_row">
                         <td>
                                    <button type="button" class="bg-danger" onclick="delRow(this, 'dynamic_testPrice_tbl')">
                                        <i class="fa fa-trash-alt text-white"></i>
                                    </button>
                             <input type="hidden" name="test[inv_dyn_id][]" value="<?php echo $test['inv_dyn_id'];?>">
                         </td>
                                <td><textarea  name="test[dynamic_heading][]" class="form-control form-control-sm" ><?php echo $test['dynamic_heading']; ?></textarea></td>
                                <td><input onkeyup="_calculatePrice()" value="<?php echo $test['dynamic_value']; ?>" type="number" step="0.01" name="test[dynamic_value][]" class="form-control form-control-sm rt"></td>
                                <td><input onkeyup="_calculatePrice()" value="<?php echo $test['quantity']; ?>" type="number" step="0.01" name="test[quantity][]" class="form-control form-control-sm qt"></td>
                                <td><input onkeyup="_calculatePrice()" value="<?php echo $test['discount']; ?>" type="number" step="0.01" name="test[discount][]" class="form-control form-control-sm dsc"></td>
                                <td><input onchange="_calculatePrice()" onkeyup="function{ return false;}" value="<?php echo $test['applicable_charge']; ?>" type="number" step="0.01" name="test[applicable_charge][]" class="form-control form-control-sm tl" readonly></td>

                    </tr>
                    <?php } ?>

                <?php } else { ?>
                    <tr id="dynamic_testPrice_row" hidden>
                       <?php $hidden_row(); ?>
                 </tr>
                 <tr>
                       <?php $hidden_row(); ?>
                 </tr>
                <?php } ?>
                
            </tbody>
            
         
          </table>
               
               <table class="table w-100 ">
                   <tr>
                      <td colspan="5" class="text-right text-bold w-75">Total : </td>
                       <td class="">
                           <input name="total_amount" onkeyup="function{ return false;}" id="final_amount" value="<?php echo isset($dynamic_tests[0]['total_amount']) ? $dynamic_tests[0]['total_amount'] : ''; ?>" type="number" step="0.01" class="form-control form-control-sm" readonly></td>
                   </tr> 
               </table>
               
               <table class="table w-100 ">
                   <tr>
                    <!-- Added by kapri on 07-09-2021 -->
                <td >
                   
                        <label><span class="text-bold"><i class="text-danger">*</i>Select Currency</span></label> 
                        <select class="form-control form-control-sm" id="taxCurrency" name="tax_currency" required>
                            <option value="">Tax Currency</option>
                            <?php foreach ($currency as $value) { ?>
                            <option value="<?php echo $value['currency_id']; ?>"
                                  <?php if(isset($dynamic_tests[0]['tax_currency']) && $dynamic_tests[0]['tax_currency'] == $value['currency_id']){
                                     echo "selected"; 
                                  }?>><?php echo $value['currency_name'] ?></option>
                            <?php } ?>
                        </select>
                        <?php echo form_error('tax_currency', '<div class="text-danger">', '</div>'); ?>
                  
                    
                </td>
                
<!--                <td>
                    <label><span class="text-bold"><i class="text-danger">*</i>Vat Prod Posting Group</span></label> 
                    <select name="vat_prod_posting_group" class="form-control form-control-sm" required>
                        <option value="">Select VAT Prod Posting Group</option>
                        <option value="ZERO" <?php if(isset($invoice_details->vat_prod_posting_group) && $invoice_details->vat_prod_posting_group == 'ZERO'){ echo "selected";}?>>ZERO</option>
                        <option value="REDUCED" <?php if(isset($invoice_details->vat_prod_posting_group) && $invoice_details->vat_prod_posting_group == 'REDUCED'){ echo "selected";}?>>REDUCED</option>
                    </select>
                </td>-->
                
                <td>
                     <label><span class="text-bold">Tax %</span></label> 
                    <select onchange="_calculatePrice()" id="taxPercentage" name="tax_percentage" class="form-control form-control-sm" required>
                        <option value="0" <?php if(isset($dynamic_tests[0]['tax_percentage']) && $dynamic_tests[0]['tax_percentage'] == 0){
                          echo "selected";  
                        }?>>0%</option>
                        <option value="5" <?php if(isset($dynamic_tests[0]['tax_percentage']) && $dynamic_tests[0]['tax_percentage'] == 5){
                          echo "selected";  
                        }?>>5%</option>
                    </select>
                </td>
                   
                      <td  class="text-right text-bold ">Final Amount : </td>
                       <td class="">
                           <input name="final_amount_after_tax" onkeyup="function{ return false;}" id="final_amount_after_tax" value="<?php echo isset($dynamic_tests[0]['final_amount_after_tax']) ? $dynamic_tests[0]['final_amount_after_tax'] : ''; ?>" type="number" step="0.01" class="form-control form-control-sm" readonly></td>
                   </tr> 
               </table>
          <div class="row">
 
          </div>
     
            
             <button type="button" class="btn btn-secondary mt-2 mb-2" data-bs-dismiss="modal">Close</button>
             <button type="submit" class="btn btn-primary mt-2 mb-2" value="Update">Save Changes</button>
        </form>

</div>
    </body>
</html>

<script>
   function AddRow(tableId, rowId){
      var table = document.getElementById(tableId);
      var _data = document.getElementById(rowId).innerHTML;
      var row = table.insertRow();
      row.innerHTML = _data;
 }
 
 function delRow(currElement, tableId) {
     var parentRowIndex = currElement.parentNode.parentNode.rowIndex;
     document.getElementById(tableId).deleteRow(parentRowIndex);
     _calculatePrice();
 }

function _calculatePrice(){
        
  var qty_classcount = document.getElementsByClassName("qt");
  var rate_classcount = document.getElementsByClassName("rt");
  var discount_classcount = document.getElementsByClassName("dsc");
  var total_classcount = document.getElementsByClassName("tl");
 
  var values_of_qty =[];
  var values_of_rate =[];
  var values_of_discount =[];
  var values_of_total=[];
 
  for(var i=0; i < qty_classcount.length; i++){
      values_of_qty[i] = qty_classcount[i].value;
      values_of_rate[i] = rate_classcount[i].value; 
      values_of_discount[i] = discount_classcount[i].value; 
      values_of_total[i] =  (values_of_qty[i]*values_of_rate[i]) - (values_of_qty[i]*values_of_rate[i]*values_of_discount[i]/100).toFixed(2);
      total_classcount[i].value = values_of_total[i];   
  }   
  var sum = 0;
  for(r=0; r < total_classcount.length; r++){   
      sum = sum + values_of_total[r];
      document.getElementById("final_amount").value = sum.toFixed(2);
  } 
  var  tax_percentage = document.getElementById("taxPercentage").value;
  var final_amt_after_tax = sum + sum*tax_percentage/100;
  document.getElementById("final_amount_after_tax").value = final_amt_after_tax.toFixed(2);
       
}  

 
</script>
   