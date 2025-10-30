<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="<?php echo base_url('public/css/bootstrap.min.css') ?>">

</head>

<body>
    <style>
        .font p {
            font-size: 8px;
        }

        .font h6 {
            font-size: 10px;
        }

        .font h2 {
            font-size: 12px;
        }

        .BORDER {
            border: 1px solid grey;
        }

        table {
            width: 100%;
        }

        table td {
            font-size: 8px;
            text-align: center;
        }

        .col-xs-6 {
            padding-top: 5px;
        }

        .border-tr td {
            border: 1px solid grey;
            padding: 5px 0;
        }

        .border-tr td table td {
            border: none;
        }

        @page {
            margin: 190px 25px;
        }

        footer {
            position: fixed;
            bottom: -160px;
            left: 0px;
            right: 0px;
            height: 50px;
            color: grey;
            font-size: 9px;
            text-align: center;
        }

        header {
            position: fixed;
            top: -160px;
            left: 0px;
            right: 0px;
        }
        div.cust_details {
        page-break-before: always;
    }
    </style>
    <header>
        <div class="row font">
            <div class="col-xs-8 text-right">
                <img src="<?php echo base_url('public/img/logo/logo-login.png') ?>" alt="" srcset="" width="50%">
            </div>
            <div class="col-xs-4">
                <div>
                    <h6><b>GEO-CHEM LABORATORIES PVT.LTD.</b></h6>
                    <p class="text-left ">Plot no. 306, Udyog Vihar, Phase - 2,<br>
                        Gurgaon, haryana - 122016, India <br>
                        Tel. : +0124 6250500 <br>
                        Email: insp@basilrl.com, <br>
                        testing@basilrl.com
                    </p>
                </div>
            </div>
        </div>
        <div class="font">
            <p class="text-center">International <br> Independent Inspection <br>& <br> Testing Company </p>
        </div>

    </header>
    <footer>
        Regd. Off.: Geo-Chem Laboratories Pvt. Ltd. Geo-Chem House, 294, Shahid Bhagat Singh Road, Fort, Mumbai 400001, India.<br>
        Tel: +91 22 66383838 Fax: +91 22 66383800 Email: mumbai@basilrl.com CIN: U74220MH1964PTC013022 PAN: AAACH1884B <br>
        www.basilrl.com
    </footer>
    <div class="container height">

        <div class="row text-center font">
            <h2 style="padding: 5px;border:1px solid black;">Payment Slip</h2>
        </div>
        <div class="row BORDER">
            <div class="col-xs-6 text-center">
                <h6>Invoice Details</h6>
            </div>
            <div class="col-xs-6 text-center">
                <h6>Payment Details</h6>
            </div>
        </div>
        <div class="row BORDER">
            <div class="col-xs-6">
                <div class="table-responsive">
                    <table class="table">
                        <tr>
                            <td>Invoice No. </td>
                            <td>:</td>
                            <td><?php echo $customer['invoice_no'] ?></td>
                        </tr>
                        <tr>
                            <td>Invoice Date. </td>
                            <td>:</td>
                            <td><?php echo $customer['Date'] ?></td>
                        </tr>
                        <tr>
                            <td>State/ State Code</td>
                            <td>:</td>
                            <td>HARYANA(06)</td>
                        </tr>
                        <tr>
                            <td>SUPPLIER GSTIN NO.</td>
                            <td>:</td>
                            <td>06AAACG1884B1Z0</td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="col-xs-6">
                <div class="table-responsive">
                    <table class="table">
                        <tr>
                            <td>CARD NO.</td>
                            <td>:</td>
                            <td><?php echo $customer['card_num'] ?></td>
                        </tr>
                        <tr>
                            <td>CARD TYPE.</td>
                            <td>:</td>
                            <td><?php echo $customer['card_type'] ?></td>
                        </tr>
                        <tr>
                            <td>CARD OWNER NAME</td>
                            <td>:</td>
                            <td><?php echo $customer['name_on_card'] ?></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <div class="row BORDER">
            <div class="col-xs-6 text-center">
                <h6>Details of Customer</h6>
            </div>
            <div class="col-xs-6 text-center">
                <h6>Details of Testing</h6>
            </div>
        </div>
        <div class="row BORDER">
            <div class="col-xs-6">
                <div class="table-responsive">
                    <table class="table">
                        <tr>
                            <td>Name</td>
                            <td>:</td>
                            <td><?php echo $customer['customer_name'] ?></td>
                        </tr>
                        <tr>
                            <td>Address</td>
                            <td>:</td>
                            <td><?php echo   $customer['address'] ?></td>
                        </tr>
                        <tr>
                            <td>Country</td>
                            <td>:</td>
                            <td><?php echo $customer['country_name'] ?></td>
                        </tr>
                        <tr>
                            <td>City</td>
                            <td>:</td>
                            <td><?php echo $customer['city'] ?></td>
                        </tr>
                        <tr>
                            <td>Customer GSTIN</td>
                            <td>:</td>
                            <td><?php echo $customer['gstin'] ?></td>
                        </tr>
                        <tr>
                            <td>Customer PAN</td>
                            <td>:</td>
                            <td><?php echo $customer['pan'] ?></td>
                        </tr>
                        <tr>
                            <td>Customer Email</td>
                            <td>:</td>
                            <td><?php echo $customer['email'] ?></td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="col-xs-6">
                <div class="table-responsive">
                    <table class="table">
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="row BORDER cust_details" style="page-break-inside: auto !important;"> 
            <div class="table-responsive">
                <table class="border-tr table">
                    <thead>
                        <tr>
                            <td><strong>Product Name</strong></td>
                            <td class="text-center"><strong>Test Name</strong></td>
                            <td class="text-center"><strong>Test Qty</strong></td>
                            <td class="text-center"><strong>Test Method</strong></td>
                            <!-- <td class="text-center"><strong>Test Price</strong></td> -->
                            <td class="text-center"><strong>Item Quantity</strong></td>
                            <!-- <td class="text-center"><strong>Total</strong></td> -->
                        </tr>
                    </thead>
                    <tbody>

                        <?php $total = 0;

                        foreach ($products as $pro) { ?>
                            <tr>
                                <td><?php echo   $pro['sample_type_name'] ?></td>
                                
                                <td>
                                    <table class="">
                                        <?php $price = 0;
                                        foreach ($pro['tests'] as $test) { ?>
                                            <tr>
                                                <td class="text-center"><?php echo   $test['test_name'] ?></td>
                                            </tr>
                                            <?php $price += $test['price'];
                                            $currency = $test['currency_code']; ?>
                                        <?php } ?>
                                    </table>
                                </td>

                                <td>
                                    <table class="">
                                        <?php $price = 0;
                                        foreach ($pro['tests'] as $test) { ?>
                                            <tr>
                                                <td class="text-center"><?php echo   $test['test_qty'] ?></td>
                                            </tr>
                                            <?php $price += $test['price'];
                                            $currency = $test['currency_code']; ?>
                                        <?php } ?>
                                    </table>
                                </td>

                                <td>
                                    <table class="">
                                        <?php foreach ($pro['tests'] as $test) { ?>
                                            <tr>
                                                <td class="text-center"><?php echo $test['test_method'] ?></td>
                                            </tr>
                                        <?php } ?>
                                    </table>
                                </td>

                                <!-- <td>
                                <table class="">
                                    <?php foreach ($pro['tests'] as $test) { ?>
                                        <tr>
                                            <td class="text-center"><?php echo  $test['currency_code'] . ' ' . $test['price'] ?>/-</td>
                                        </tr>
                                    <?php } ?>
                                </table>
                            </td> -->
                                <td><?php echo $pro['pro_qty'] ?> Unit.</td>
                                <!-- <td class="text-center"><?php echo  $currency . ' ' . $price ?>/-</td> -->
                            </tr>
                        <?php $total += $price;
                        } ?>
                        <!-- <tr>
                        <td class="highrow"></td>
                        <td class="highrow"></td>
                        <td class="highrow"></td>
                        <td class="highrow"></td>
                        <td class="highrow text-center"><strong>Total</strong></td>
                        <td class="highrow text-center"><?php echo ($currency . ' ' . $total) ?>/-</td>
                    </tr> -->
                        <?php if ($customer['city'] == 'gurgaon') { ?>
                            <!-- <tr>
                            <td class="emptyrow"></td>
                            <td class="emptyrow"></td>
                            <td class="emptyrow"></td>
                            <td class="emptyrow"></td>
                            <td class="emptyrow text-center"><strong>CGST Tax</strong></td>
                            <td class="emptyrow text-center"><?php echo  $currency . ' ' . ($total * 0.09) ?>/-</td>
                        </tr> -->
                            <!-- <tr>
                            <td class="emptyrow"></td>
                            <td class="emptyrow"></td>
                            <td class="emptyrow"></td>
                            <td class="emptyrow"></td>
                            <td class="emptyrow text-center"><strong>SGST Tax</strong></td>
                            <td class="emptyrow text-center"><?php echo  $currency . ' ' . ($total * 0.09) ?>/-</td>
                        </tr> -->
                        <?php } else { ?>
                            <!-- <tr>
                            <td class="emptyrow"></td>
                            <td class="emptyrow"></td>
                            <td class="emptyrow"></td>
                            <td class="emptyrow"></td>
                            <td class="emptyrow text-center"><strong>IGST Tax</strong></td>
                            <td class="emptyrow text-center"><?php echo  $currency . ' ' . ($total * 0.18) ?>/-</td>
                        </tr> -->
                        <?php } ?>
                        <!-- <tr>
                        <td colspan="4">
                            Total Invoice Value In Words: <?php echo strtoupper($customer['order_amount_in_word']) ?>
                        </td>
                        <td class="text-center"><strong>Total</strong></td>
                        <td class="text-center"><?php echo  $currency . ' ' . $customer['order_amount'] ?>/-</td>
                    </tr> -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>

</html>