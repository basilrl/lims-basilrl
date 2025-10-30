

<div class="container mt-10" style="min-height: 68vh;">
    <div class="mt-10 mb-10">
        <div class="container">
            <div class="row">
                Total Customers : <b><?php echo $total_customers;?></b>
            </div>
        </div>
    <?php  
      $oneTimeCustomerSync = 250;
      $links = ceil($total_customers/$oneTimeCustomerSync);
      $remainder = $total_customers % $oneTimeCustomerSync;
      $end = 0;
      for($i = 0; $i < $links; $i++){
        $start = ($i*$oneTimeCustomerSync); 
        if ($i == ($links - 1 )) {
          $end += $remainder;
        }else{
          $end += $oneTimeCustomerSync;   
        }
        
    ?>
    
    <button type="button" class="btn btn-warning">
        <a href="<?php echo base_url('CustomerSync/customer_sync?limit='.$start); ?>" >
         <?php echo  $start. " - ". $end?> 
        </a>
    </button>
      <?php 
      
      }?>
    </div>
</div>