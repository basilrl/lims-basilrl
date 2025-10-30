
<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">

    </head>
    <body>
         <div class="container">
              
               <table id="dynamic_testPrice_tbl" class="table table-bordered">
              <thead>
                  <tr>
                      <th>S.No.</th>
                      <th>Test Name</th>
                      <th>Rate Per Test</th>
                      <th>Quantity</th>
                      <th>Discount(in %)</th>
                      <th>Applicable Charge</th>
                      
                  </tr>
              </thead>
           
            <tbody>
                 
                <?php if (isset($dynamic_tests) && !empty($dynamic_tests)) { ?>
                 

                    <?php foreach ($dynamic_tests as $k=>$test) { ?>
                    <tr>
                         <td>
                            <?php echo ++$k;?>      
                      
                         </td>
                                <td><?php echo $test['dynamic_heading']; ?></td>
                                <td> <?php echo $test['dynamic_value']; ?> </td>
                                <td> <?php echo $test['quantity']; ?></td>
                                <td> <?php echo $test['discount']; ?></td>
                                <td> <?php echo $test['applicable_charge']; ?></td>

                    </tr>
                    <?php } ?>

                <?php }   ?>
                  
               
                
            </tbody>
            
         
          </table>
               
               <table class="table-bordered w-100 ">
                   <tr>
                       <td colspan="5" class="text-right text-bold w-75">Total : </td>
                       <td class="text-right">
                           <?php echo isset($dynamic_tests[0]['total_amount']) ? $dynamic_tests[0]['total_amount']: '';?></td>
                   </tr> 
                   <tr>
                       <td colspan="5" class="text-right text-bold w-75">Tax % : </td>
                       <td class="text-right">
                           <?php echo isset($dynamic_tests[0]['tax_percentage']) ? $dynamic_tests[0]['tax_percentage']: '';?></td>
                   </tr> 
                   <tr>
                       <td colspan="5" class="text-right text-bold w-75">VAT Prod Posting Group : </td>
                       <td class="text-right">
                           <?php echo isset($dynamic_tests[0]['vat_prod_posting_group']) ? $dynamic_tests[0]['vat_prod_posting_group']: '';?></td>
                   </tr> 
                   <tr>
                       <td colspan="5" class="text-right text-bold w-75">Final Amount After Tax: </td>
                       <td class="text-bold text-right">
                           <?php
                           echo
                           isset($dynamic_tests[0]['currency_code']) && isset($dynamic_tests[0]['final_amount_after_tax']) ? $dynamic_tests[0]['final_amount_after_tax']. " ".$dynamic_tests[0]['currency_code'] : $dynamic_tests[0]['total_amount'];
                           ?>
                       </td>
                   </tr> 
               </table>
 
</div>
    </body>
</html>

 
   