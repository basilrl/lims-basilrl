
<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">

    </head>
    <body>
        <table class="table ">
            <thead>
                <tr>
                    <th>S.No.</th><th>Paid Amount</th><th>Payment Date</th><th>Payment Updated On</th>
                </tr>
            </thead>
            <tbody>
                <?php if(isset($invoice_payment) && !empty($invoice_payment)){
                    foreach ($invoice_payment as $k=>$payment) {?>
                <tr>
                    <td><?php echo ++$k; ?></td>
                    <td><?php echo $payment['payment_amount']; ?></td>
                    <td><?php echo $payment['payment_date']; ?></td>
                    <td><?php echo $payment['created_on']; ?></td>
                </tr>
                    <?php } }?>
            </tbody>
        </table>
    </body>
</html>

 
   