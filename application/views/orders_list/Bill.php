<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Demo html PDF</title>
    <style>
        @page {
            width: 100%;
            opacity: 0.3;
            background-size: cover !important;
            margin-top: 5.5cm !important;
            margin-bottom: 1cm !important;
            background-position: top center;
            header: html_myHeader1;
            footer: html_myFooter;

        }

        div.chapter2 {
            page-break-before: always;
            /* page: chapter2; */
        }

        div.noheader {
            page-break-before: always;
            /* page: noheader; */
        }
    </style>
</head>

<body style="margin:0 auto; padding:30px; color:#000; font-size:14px; font-family:Verdana, Arial, Helvetica, sans-serif">
    <htmlpageheader name="myHeader1">
        <table width="100%" border="0" cellspacing="0" cellpadding="5" style="font-size:14px;">
            <tr>
                <td align="left" width="30%"><img src="https://basilrl.com/public/images/logo.png" style="width:200px"></td>
                <td align="left" width="70%" style="font-size:14px; line-height:22px; text-align:right"><strong>GEO-CHEM LABORATORIES PVT.LTD.</strong><br>
                    <strong>Address: </strong>Plot no. 306, Udyog Vihar, Phase - 2,<br>
                    Gurgaon, haryana - 122016, India <br>
                    <strong> Tel. :</strong> +0124 6250500 <br>
                    <strong>Email:</strong> insp@basilrl.com, <br>
                    testing@basilrl.com
                </td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <th colspan="2" align="center" style="font-size:12px; width:100%; text-align:center">International Independent Inspection & Testing Company</th>
            </tr>
        </table>
    </htmlpageheader>

    <htmlpagefooter name="myFooter">
        <table width="100%" border="0" cellspacing="0" cellpadding="5">
            <tr>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td align="center" style="font-size:12px; color:#333333; border-top:1px solid #333">Regd. Off.: Geo-Chem Laboratories Pvt. Ltd. Geo-Chem House, 294, Shahid Bhagat Singh Road, Fort, Mumbai 400001, India.<br>
                    Tel: +91 22 66383838 Fax: +91 22 66383800 Email: mumbai@basilrl.com CIN: U74220MH1964PTC013022 PAN: AAACH1884B <br>
                    www.basilrl.com</td>
            </tr>
        </table>
    </htmlpagefooter>

    <table width="100%" border="0" cellspacing="0" cellpadding="5">
        <tr>
            <th align="center" style="background:#ddd; color:#000; font-size:16px; padding:10px;">Order Details</th>
        </tr>
        <tr>
            <td>&nbsp;</td>
        </tr>
    </table>


    <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
            <td width="100%" valign="top">
                <table width="100%" border="0" cellspacing="0" cellpadding="8" style="font-size:13px; border:1px solid #ddd">
                    <tr>
                        <th align="center" colspan="3" style="background:#ddd; color:#000; font-size:14px;">Customer Details</th>
                    </tr>
                    <tr>
                        <td width="48%" style="border-bottom:1px solid #ddd">Name</td>
                        <td width="4%" style="border-bottom:1px solid #ddd">:</td>
                        <td width="48%" style="border-bottom:1px solid #ddd"><?php echo $customer['customer_name'] ?></td>
                    </tr>
                    <tr>
                        <td style="border-bottom:1px solid #ddd">Address</td>
                        <td style="border-bottom:1px solid #ddd">:</td>
                        <td style="border-bottom:1px solid #ddd"><?php echo   $customer['address'] ?></td>
                    </tr>
                    <tr>
                        <td style="border-bottom:1px solid #ddd">Country</td>
                        <td style="border-bottom:1px solid #ddd">:</td>
                        <td style="border-bottom:1px solid #ddd"><?php echo $customer['country_name'] ?></td>
                    </tr>
                    <tr>
                        <td style="border-bottom:1px solid #ddd">City</td>
                        <td style="border-bottom:1px solid #ddd">:</td>
                        <td style="border-bottom:1px solid #ddd"><?php echo $customer['city'] ?></td>
                    </tr>
                    <tr>
                        <td style="border-bottom:1px solid #ddd">Customer GSTIN</td>
                        <td style="border-bottom:1px solid #ddd">:</td>
                        <td style="border-bottom:1px solid #ddd"><?php echo $customer['gstin'] ?></td>
                    </tr>
                    <tr>
                        <td style="border-bottom:1px solid #ddd">Customer PAN</td>
                        <td style="border-bottom:1px solid #ddd">:</td>
                        <td style="border-bottom:1px solid #ddd"><?php echo $customer['pan'] ?></td>
                    </tr>
                    <tr>
                        <td style="border-bottom:1px solid #ddd">Customer Email</td>
                        <td style="border-bottom:1px solid #ddd">:</td>
                        <td style="border-bottom:1px solid #ddd"><?php echo $customer['email'] ?></td>
                    </tr>
                    <tr>
                        <td style="border-bottom:1px solid #ddd">Contact Person Name</td>
                        <td style="border-bottom:1px solid #ddd">:</td>
                        <td style="border-bottom:1px solid #ddd"><?php echo $customer['contact_name'] ?></td>
                    </tr>
                    <tr>
                        <td style="border-bottom:1px solid #ddd">Contact Person Designation</td>
                        <td style="border-bottom:1px solid #ddd">:</td>
                        <td style="border-bottom:1px solid #ddd"><?php echo $customer['contacts_designation_id'] ?></td>
                    </tr>
                    <tr>
                        <td style="border-bottom:1px solid #ddd">Contact Person Email</td>
                        <td style="border-bottom:1px solid #ddd">:</td>
                        <td style="border-bottom:1px solid #ddd"><?php echo $customer['contact_email'] ?></td>
                    </tr>
                    <tr>
                        <td style="border-bottom:1px solid #ddd">Contact Person Telephone</td>
                        <td style="border-bottom:1px solid #ddd">:</td>
                        <td style="border-bottom:1px solid #ddd"><?php echo $customer['contact_tel'] ?></td>
                    </tr>
                    <tr>
                        <td style="border-bottom:1px solid #ddd">Contact Person Mobile</td>
                        <td style="border-bottom:1px solid #ddd">:</td>
                        <td style="border-bottom:1px solid #ddd"><?php echo $customer['contact_mob'] ?></td>
                    </tr>
                </table>
            </td>
          
        </tr>

    </table>

    <table width="100%" border="0" cellspacing="0" cellpadding="5">
        <tr>
            <td>&nbsp;</td>
        </tr>
    </table>
    <div class="noheader">
    <table width="100%" border="0" cellspacing="0" cellpadding="5">
        <tr>
            <th align="center" style="background:#ddd; color:#000; font-size:16px; padding:10px;">Test Details</th>
        </tr>
        <tr>
            <td>&nbsp;</td>
        </tr>
    </table>
    <table width="100%" border="0" cellspacing="0" cellpadding="8" style="font-family:14px; border:1px solid #ddd">
    <thead>   
    <tr>
            <th align="center" width="20%" align="center" style="background:#ddd; color:#000; font-size:14px; padding:8px">Product Name</th>
            <th align="center" width="20%" align="center" style="background:#ddd; color:#000; font-size:14px;padding:8px">Test Name</th>
            <th align="center" width="20%" align="center" style="background:#ddd; color:#000; font-size:14px;padding:8px">Test Qty</th>
            <th align="center" width="20%" align="center" style="background:#ddd; color:#000; font-size:14px;padding:8px">Test Method</th>
            <th align="center" width="20%" align="center" style="background:#ddd; color:#000; font-size:14px;padding:8px">Item Quantity</th>
            <th align="center" width="20%" align="center" style="background:#ddd; color:#000; font-size:14px;padding:8px">Lab Location</th>
            <th align="center" width="20%" align="center" style="background:#ddd; color:#000; font-size:14px;padding:8px">Product Destination</th>
            <th align="center" width="20%" align="center" style="background:#ddd; color:#000; font-size:14px;padding:8px">Testing Standard</th>
            <th align="center" width="20%" align="center" style="background:#ddd; color:#000; font-size:14px;padding:8px">Certificate</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach($products as $pro){ foreach($pro['tests'] as $test){?>
        <!-- <tr>
            <td colspan="5" width="100%" align="center">
                <table width="100%" border="0" cellspacing="0" cellpadding="8">-->
                    
                    <tr> 
                        <td align="center" width="20%" style="border:1px solid #ddd"><?php echo $pro['sample_type_name'] ?></td>
                        <td align="center" width="20%" style="border-bottom:1px solid #ddd"><?php echo $test['test_name']; ?></td>
                        <td align="center" width="20%" style="border:1px solid #ddd; text-align:center;">
                        <?php echo $test['test_qty']; ?></td>
                        <td align="center" width="20%" style="border:1px solid #ddd">
                        <?php echo $test['test_method']; ?></td>
                        <td align="center" width="20%" style="border:1px solid #ddd"><?php echo $pro['pro_qty'] ?> Unit.</td>
                        <td align="center" width="20%" style="border:1px solid #ddd"><?php echo $pro['lab_location'] ?></td>
                        <td align="center" width="20%" style="border:1px solid #ddd"><?php echo $pro['product_destination'] ?></td>
                        <td align="center" width="20%" style="border:1px solid #ddd"><?php echo $pro['test_standard_name'] ?> </td>
                        <td align="center" width="20%" style="border:1px solid #ddd"><?php echo $pro['certificate_name'] ?></td>
                    </tr>
                   
                <!-- </table>


            </td>
        </tr> -->
        <?php } }?>
        </tbody>
    </table>
    </div>
    



</body>

</html>