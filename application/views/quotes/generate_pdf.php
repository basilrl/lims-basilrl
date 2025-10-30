<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">


    <style>
        table {
            border: 1px solid black;
            border-collapse: collapse;
            width: 100%;
        }

        .table1 td,.table2 td,.table3 td,.table4 td{
            width: 50px;
        }
    </style>
</head>
<?php

if ($data->version) {
    $version = $data->version;
    if ($version == 0) {
        $version_number = "";
    } else if ($version > 0) {
        $version_number = "_V." . $version;
    }
} else {
    $version_number = "";
}


// pre_r($data);die;
?>
<h2>Quotation</h2>
<table>

    <tr>
        <td colspan="2" style="border: 1px solid black;">

            <p><b>To,</b></p>
            <?php echo ($data->customer_name) ? ($data->customer_name) : ""; ?>
            <p><b>Attn:</b>&nbsp;&nbsp;<?php echo ($data->contact_salutation) ? ($data->contact_salutation) : ""; ?><?php echo ($data->contact_name) ? ($data->contact_salutation) : "" ?></p>

            <p><b>Designation:</b>&nbsp;&nbsp;<?php echo ($data->contacts_designation_id) ? ($data->contacts_designation_id) : "" ?></p>

            <p><b>Email:</b>&nbsp;&nbsp;<?php echo ($data->email) ? ($data->email) : ""  ?></p>

            <p><b>Mobile:</b>&nbsp;&nbsp; <?php echo ($data->mobile_no) ? ($data->mobile_no) : ""  ?></p>

            <p><b>Tel:</b>&nbsp;&nbsp;<?php echo ($data->telephone) ? ($data->telephone) : "" ?></p>

            <p><b>Address:</b>&nbsp;&nbsp;<?php echo ($data->address) ? ($data->address) : "" ?></p>

            <p><b>Country:</b>&nbsp;&nbsp;<?php echo ($data->country_name) ? ($data->country_name) : "" ?></p>


        </td>

        <td style="border: 1px solid black;">
            <p>From,</p>
            <p><b><?php echo ($data->quote_created_user) ? ($data->quote_created_user) : "" ?></b></p>
            <p><?php echo ($data->designation_name) ? ($data->designation_name) : "" ?></p>
            <p><b>Email:</b>&nbsp;&nbsp; <?php echo ($data->admin_email) ? ($data->admin_email) : "" ?></p>
            <p><b>Telephone:</b>&nbsp;&nbsp;<?php echo ($data->admin_telephone) ? ($data->admin_telephone) : "" ?></p>

        </td>
    </tr>



    <tr>
        <td>
            <p><b>Quote Ref No:</b>&nbsp;&nbsp;<?php echo ($data->reference_no) ? ($data->reference_no) : "" ?><?php echo $version_number ?></p>
            <p><b>Discussion Date:</b>&nbsp;&nbsp;<?php echo ($data->discussion_date) ? ($data->discussion_date) : "" ?><?php echo $version_number ?></p>
        </td>

        <td>
            <p><b>Date:</b>&nbsp;&nbsp;<?php echo ($data->quote_date) ? ($data->quote_date) : "" ?></p>
        </td>

        <td>
            <p><b>Valid till date:</b>&nbsp;&nbsp;<?php echo ($data->quote_valid_date) ? ($data->quote_valid_date) : "" ?></p>
        </td>


    </tr>

    <tr>
        <td colspan="2"><b>Sub:</b><span><?php echo ($data->quote_subject) ? html_entity_decode($data->quote_subject) : "" ?></span></td>

        <td>Quote template</td>
    </tr>



    <tr>
        <td colspan="3"><b>Scope of Work</b></td>
    </tr>
    <hr>

    <tr>
        <td colspan="3"><b>Analysis-Test</b></td>
    </tr>

    <hr>

</table>

<?php $total_value = 0; ?>

<table class="table2">

    <?php if ($test_data && count($test_data) > 0) { ?>
        <tr>
            <td colspan='7'>TEST DETAILS :</td>
         </tr>
         <tr>
        <td style="border-collapse: collapse;border: 1px solid black;"><b>Product Name </b></td>
        <td style="border-collapse: collapse;border: 1px solid black;"><b>Test Name </b></td>
        <td style="border-collapse: collapse;border: 1px solid black;"><b>Test Method</b></td>
        <td style="border-collapse: collapse;border: 1px solid black;"><b>Rate per Test/Sample</b></td>
        <td style="border-collapse: collapse;border: 1px solid black;"><b>Discount %</b></td>
        <td style="border-collapse: collapse;border: 1px solid black;"><b>Applicable Charge</b></td>
        <td style="border-collapse: collapse;border: 1px solid black;"><b>Total Cost</b></td>

        </tr>

        <?php foreach ($test_data as $key => $value) { ?>
            <tr>
                <td style="border-collapse: collapse;border: 1px solid black;"><?php echo ($value->sample_type_name) ? ($value->sample_type_name) : ""; ?></td>
                <td style="border-collapse: collapse;border: 1px solid black;"><?php echo ($value->name) ? ($value->name) : "" ?></td>
                <td style="border-collapse: collapse;border: 1px solid black;"><?php echo ($value->test_method) ? ($value->test_method) : ""; ?></td>
                <td style="border-collapse: collapse;border: 1px solid black;"><?php echo ($value->price) ? ($value->price) : 0; ?></td>
                <td style="border-collapse: collapse;border: 1px solid black;"><?php echo ($value->discount) ? ($value->discount) : 0; ?></td>
                <td style="border-collapse: collapse;border: 1px solid black;"><?php echo ($value->applicable_charge) ? ($value->applicable_charge) : 0; ?></td>
                <td style="border-collapse: collapse;border: 1px solid black;"><?php echo ($value->applicable_charge) ? ($value->applicable_charge) : 0; ?></td>

            </tr>
            <?php $total_value += $value->applicable_charge; ?>

        <?php }
    } else  ?>
    

</table>

<table class="table3">
 
    <?php if ($package_data && count($package_data) > 0) { ?>
        <tr>
            <td colspan='7'>PACKAGES DETAILS :</td>
        </tr>
        <tr>
        <td style="border-collapse: collapse;border: 1px solid black;"><b>Product Name </b></td>
        <td style="border-collapse: collapse;border: 1px solid black;"><b>Test Name </b></td>
        <td style="border-collapse: collapse;border: 1px solid black;"><b>Test Method</b></td>
        <td style="border-collapse: collapse;border: 1px solid black;"><b>Rate per Test/Sample</b></td>
        <td style="border-collapse: collapse;border: 1px solid black;"><b>Discount %</b></td>
        <td style="border-collapse: collapse;border: 1px solid black;"><b>Applicable Charge</b></td>
        <td style="border-collapse: collapse;border: 1px solid black;"><b>Total Cost</b></td>

        </tr>
        <?php foreach ($package_data as $key => $value) { ?>
        
            <tr>
                <td style="border-collapse: collapse;border: 1px solid black;"><?php echo ($value->sample_type_name) ? ($value->sample_type_name) : ""; ?></td>
                <td style="border-collapse: collapse;border: 1px solid black;"><?php echo ($value->test_name) ? ($value->test_name) : "" ?></td>
                <td style="border-collapse: collapse;border: 1px solid black;"><?php echo ($value->test_method) ? ($value->test_method) : ""; ?></td>

                <?php if ($key == 0) { ?>
                    <td rowspan="<?php echo count($package_data)?>" style="border-collapse: collapse;border: 1px solid black;"><?php echo ($value->rate) ? ($value->rate) : 0; ?></td>
                    <td rowspan="<?php echo count($package_data)?>" style="border-collapse: collapse;border: 1px solid black;"><?php echo ($value->discount) ? ($value->discount) : 0; ?></td>
                    <td rowspan="<?php echo count($package_data)?>" style="border-collapse: collapse;border: 1px solid black;"><?php echo ($value->total_cost) ? ($value->total_cost) : 0; ?></td>
                    <td rowspan="<?php echo count($package_data)?>" style="border-collapse: collapse;border: 1px solid black;"><?php echo ($value->total_cost) ? ($value->total_cost) : 0; ?></td>
                    <?php $total_value += $value->total_cost; ?>
                <?php } ?>


            </tr>



        <?php }
    } else ?>
        

</table>

<table class="table4">
   
    <?php if ($protocol_data && count($protocol_data) > 0) { ?>
        <tr>
             <td colspan='7'>PROTOCOL DETAILS :</td>
        </tr>
        <tr>
        <td style="border-collapse: collapse;border: 1px solid black;"><b>Product Name </b></td>
        <td style="border-collapse: collapse;border: 1px solid black;"><b>Test Name </b></td>
        <td style="border-collapse: collapse;border: 1px solid black;"><b>Test Method</b></td>
        <td style="border-collapse: collapse;border: 1px solid black;"><b>Rate per Test/Sample</b></td>
        <td style="border-collapse: collapse;border: 1px solid black;"><b>Discount %</b></td>
        <td style="border-collapse: collapse;border: 1px solid black;"><b>Applicable Charge</b></td>
        <td style="border-collapse: collapse;border: 1px solid black;"><b>Total Cost</b></td>

        </tr>
        <?php foreach ($protocol_data as $key => $value) { ?>
            <tr>
                <td style="border-collapse: collapse;border: 1px solid black;"><?php echo ($value->sample_type_name) ? ($value->sample_type_name) : ""; ?></td>
                <td style="border-collapse: collapse;border: 1px solid black;"><?php echo ($value->test_name) ? ($value->test_name) : "" ?></td>
                <td style="border-collapse: collapse;border: 1px solid black;"><?php echo ($value->test_method) ? ($value->test_method) : ""; ?></td>

                <?php if ($key == 0) { ?>
                    <td rowspan="<?php echo count($package_data)?>" style="border-collapse: collapse;border: 1px solid black;"><?php echo ($value->rate) ? ($value->rate) : 0; ?></td>
                    <td rowspan="<?php echo count($package_data)?>" style="border-collapse: collapse;border: 1px solid black;"><?php echo ($value->discount) ? ($value->discount) : 0; ?></td>
                    <td rowspan="<?php echo count($package_data)?>" style="border-collapse: collapse;border: 1px solid black;"><?php echo ($value->total_cost) ? ($value->total_cost) : 0; ?></td>
                    <td rowspan="<?php echo count($package_data)?>" style="border-collapse: collapse;border: 1px solid black;"><?php echo ($value->total_cost) ? ($value->total_cost) : 0; ?></td>
                    <?php $total_value += $value->total_cost; ?>
                <?php } ?>


            </tr>

        <?php }
    } else  ?>
        

</table>

<table class="table5">

    <?php $sgst = round(($total_value / 100) * 9); ?>
    

    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td>Total Quote Value:</td>
        <td><?php echo $total_value ?></td>
    </tr>
    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td>SGST @ 9%:</td>
        <td><?php echo $sgst ?></td>
    </tr>
    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td>CGST @ 9%:</td>
        <td><?php echo $sgst ?></td>
    </tr>

    <tr>
        <td><b>Approver's Designation:</b></td>
        <td><?php echo ($data->designation_name) ? ($data->designation_name) : "" ?></td>
        <td><b>Quote Value</b></td>
        <td colspan="4"><?php echo ($data->quote_value) ? ($data->quote_value) : "" ?></td>
    </tr>

    <tr>
        <td><b>Approver</b></td>
        <td><b><?php echo ($data->approver) ? ($data->approver) : "" ?></b></td>
        <td><b>Quote Currency</b></td>
        <td colspan="4"><?php echo ($data->quotes_currency_name) ? ($data->quotes_currency_name) : "" ?></td>
    </tr>
</table>

</body>

</html>