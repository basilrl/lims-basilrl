
<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">

    </head>
    <body>
        
        <form action="<?php echo base_url('invoice_Controller/saveDynamicTestPrices'); ?>" method="post" id="save_test">
           <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">

           <input type="hidden" name="sample_reg_id" value="<?php echo $sample_reg_id;?>">
           <input type="hidden" name="proforma_invoice_id" value="<?php echo $proforma_invoice_id;?>">
           <div class="container">
               <div class="row">
                   <div class="col-sm-4 mt-2 mb-2">
                       <input id="addRow" onclick="AddRow('dynamic_testPrice_tbl', 'dynamic_testPrice_row')" type="button" class="btn btn-primary" value="Add Row">
                       
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

                <?php }else if (isset($testPrice) && !empty($testPrice)) { ?>
                    <tr id="dynamic_testPrice_row" hidden>
                    
                       <?php $hidden_row(); ?>
                    </tr>

                    <?php foreach ($testPrice as $test) { ?>
                    <tr id="dynamic_testPrice_row">
                         <td>
                                    <button type="button" class="bg-danger" onclick="delRow(this, 'dynamic_testPrice_tbl')">
                                        <i class="fa fa-trash-alt text-white"></i>
                                    </button>
                             <input type="hidden" name="test[inv_dyn_id][]" value="">
                         </td>
                                <td><textarea  name="test[dynamic_heading][]" class="form-control form-control-sm" ><?php echo $test['test_name']; ?></textarea></td>
                                <td><input onkeyup="_calculatePrice()" value="<?php echo $test['price']; ?>" type="number" step="0.01" name="test[dynamic_value][]" class="form-control form-control-sm rt"></td>
                                <td><input onkeyup="_calculatePrice()" value="1" type="number" step="0.01" name="test[quantity][]" class="form-control form-control-sm qt"></td>
                                <td><input onkeyup="_calculatePrice()" value="0" type="number" step="0.01" name="test[discount][]" class="form-control form-control-sm dsc"></td>
                                <td><input onchange="_calculatePrice()" onkeyup="function{ return false;}" value="" type="number" step="0.01" name="test[applicable_charge][]" class="form-control form-control-sm tl" readonly></td>

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
               
               <table class="table-bordered w-100 ">
                   <tr>
                      <td colspan="5" class="text-right text-bold w-75">Total : </td>
                       <td class="">
                           <input name="total_amount" onkeyup="function{ return false;}" id="final_amount" value="<?php echo isset($dynamic_tests[0]['total_amount']) ? $dynamic_tests[0]['total_amount'] : ''; ?>" type="number" step="0.01" class="form-control form-control-sm" readonly></td>
                   </tr> 
               </table>
               
               <table class="table-bordered w-100 ">
                   <tr>
                       <td colspan="2" class="text-bold">Select Currency</td>     <!-- Added by kapri on 07-09-2021 -->
                <td>
                    <div class="form-group">
                        <select class="form-control form-comtrol-sm select-box" id="taxCurrency" name="tax_currency" required>
                            <option value="">Tax Currency</option>
                            <?php foreach ($currency as $value) { ?>
                            <option value="<?php echo $value['currency_id']; ?>"
                                  <?php if(isset($dynamic_tests[0]['tax_currency']) && $dynamic_tests[0]['tax_currency'] == $value['currency_id']){
                                     echo "selected"; 
                                  }?>><?php echo $value['currency_name'] ?></option>
                            <?php } ?>
                        </select>
                        <?php echo form_error('tax_currency', '<div class="text-danger">', '</div>'); ?>
                    </div>
                    
                </td>
                
                <td class="text-bold">Select Tax %</td>
                <td>
                    <select onchange="_calculatePrice()" id="taxPercentage" name="tax_percentage" class="form-control" required>
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
                   <div class="col-md-12">
                       <input type="radio" value="1" name="show_discount" required <?php echo isset($dynamic_tests[0]['show_discount']) && ($dynamic_tests[0]['show_discount'] == 1)? 'checked': ''; ?>> Show Discount
                       <input type="radio" value="0" name="show_discount" required
                        <?php echo isset($dynamic_tests[0]['show_discount']) && ($dynamic_tests[0]['show_discount'] == 0)? 'checked'  : ''; ?>> Don't Show Discount
                   </div>
               </div>
               
               <div class="row">
                   <div class="col-md-12">
                       <div class="form-gorup">
                           <label for="Remark">Remark (If any)</label>
                           <textarea name="invoice_remark" class="form-control form-comtrol-sm"><?php echo isset($dynamic_tests[0]['invoice_remark']) ? $dynamic_tests[0]['invoice_remark']: '';?></textarea>
                       </div>
                   </div>
               </div>
          
     
            
             <button type="button" class="btn btn-secondary mt-2 mb-2" data-bs-dismiss="modal">Close</button>
             <button type="submit" class="btn btn-primary mt-2 mb-2" value="Update">Save Changes</button>
              </div>
        </form>

</div>
    </body>
</html>

<script>
    $(document).ready(function(){
       _calculatePrice();
    });
     $('#save_test').validate({});
     $('#save_test').submit(function(event){
              if($('#save_test').valid()){
                $('button[type=submit]').prop('disabled', true);
              }
         });
    
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
 