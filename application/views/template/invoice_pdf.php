<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<style>
    @page {
        background: url('https://basilrl-prod.s3.ap-south-1.amazonaws.com/basil-letterhead.png') no-repeat;
        width: 100%;
        background-size: 100% !important;
        margin-top: 3.5cm !important;
        margin-bottom: 2cm !important;
        background-position: top center;
        
    }

    * {
        font-size: 13px;
    }
</style>

<body>



    <div class="bg_image">
        <h3 style="text-align: center;">PROFORMA INVOICE</h3>




        <table style="font-size:13px;">
            <tr>
                <th style="width: 10%;text-align:left;"><?php if ($invoice_detail->trf_invoice_to == 'Factory') {
                                                            echo 'Factory';
                                                        } elseif ($invoice_detail->trf_invoice_to == 'Buyer') {
                                                            echo 'Buyer';
                                                        } elseif ($invoice_detail->trf_invoice_to == 'Agent') {
                                                            echo 'Agent';
                                                        } else {
                                                            echo 'Third Party';
                                                        } ?> Name</th>
                <td style="width:2%;">:</td>
                <td style="width: 38%;"><?php echo $invoice_detail->customer ?></td>
                <th style="width: 10%;text-align:left;">Proforma Invoice No.</th>
                <td style="width: 2%;">:</td>
                <td style="width: 38%;"><?php echo $invoice_detail->proforma_invoice_number ?></td>
            </tr>
            <tr>
                <th style="width: 10%;text-align:left;">Contact Name</th>
                <td style="width:2%;">:</td>
                <td style="width: 38%;"><?php echo $invoice_detail->customer_contact ?></td>
                <th style="width: 10%;text-align:left;">Login Date</th>
                <td style="width: 2%;">:</td>
                <td style="width: 38%;"><?php echo date('d-M-Y', strtotime($invoice_detail->proforma_invoice_date)) ?></td>
            </tr>
            <tr>
                <th style="width: 10%;text-align:left;">GSTIN</th>
                <td style="width:2%;">:</td>
                <td style="width: 38%;"><?php echo $invoice_detail->newgstinnumber; ?></td>
                <th style="width: 10%;text-align:left;">Due Date</th>
                <td style="width: 2%;">:</td>
                <td style="width: 38%;"><?php if ($invoice_detail->tat_date != '' && $invoice_detail->tat_date != '0000-00-00 00:00:00') {
                                            $due_date = date('d-M-Y', strtotime($invoice_detail->tat_date));
                                        } else {
                                            $due_date = date('d-M-Y', strtotime($invoice_detail->due_date));
                                        }
                                        echo $due_date; ?></td>
            </tr>
            <tr>
                <th style="width: 10%;text-align:left;">Address</th>
                <td style="width:2%;">:</td>
                <td style="width: 38%;"><?php echo  $invoice_detail->customer_address; ?>, <br><?php echo $invoice_detail->state1; ?>, <?php echo $invoice_detail->country; ?></td>
                <th style="width: 10%;text-align:left;">Applicant Code</th>
                <td style="width: 2%;">:</td>
                <td style="width: 38%;"><?php echo $invoice_detail->applicant_code; ?></td>
            </tr>
            <tr>
                <!-- <th style="width: 10%;text-align:left;">Color</th>
                <td style="width:2%;">:</td>
                <td style="width: 38%;"></td> -->
                <th style="width: 10%;text-align:left;">Service Type</th>
                <td style="width: 2%;">:</td>
                <td style="width: 38%;"><b><?php echo $invoice_detail->service_type ?></b></td>

                <th style="width: 10%;text-align:left;">Basil Report No.</th>
                <td style="width: 2%;">:</td>
                <td style="width: 38%;"><?php echo $invoice_detail->test_report_no ?></td>
            </tr>
            <tr>
                <!-- <th style="width: 10%;text-align:left;">Style No.</th>
                <td style="width:2%;">:</td>
                <td style="width: 38%;"></td> -->
                <th style="width: 10%;text-align:left;">Quotation</th>
                <td style="width: 2%;">:</td>
                <td style="width: 38%;"><?php echo $invoice_detail->quote_ref_no; ?></td>

                <th style="width: 10%;text-align:left;">Agent</th>
                <td style="width:2%;">:</td>
                <td style="width: 38%;"><?php echo $invoice_detail->agent; ?></td>
            </tr>
            <tr>

                <th style="width: 10%;text-align:left;">Buyer</th>
                <td style="width: 2%;">:</td>
                <td style="width: 88%;"><?php echo $invoice_detail->buyer; ?></td>

                <th style="width: 10%;text-align:left;">HSN/SAC</th>
                <td style="width:2%;">:</td>
                <td style="width: 38%;"><?php //echo HSN; 
                                        ?></td>

            </tr>
            <tr>

                <th style="width: 10%;text-align:left;">Sample Desc</th>
                <td style="width: 2%;">:</td>
                <td style="width: 88%;"><?php echo $invoice_detail->sample_desc; ?></td>

            </tr>

        </table>

        <?php if (!empty($fields)) { ?>
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <?php foreach ($fields as $key => $value) { ?>
                    <tr>
                        <th align="left" width="50%"><?php echo $value[0]; ?></th>
                        <td>:&nbsp; <?php echo $value[1]; ?> </td>
                    <tr>
                    <?php } ?>
            </table>
            </td>
            </tr>
        <?php } ?>

        <h3 style="text-align: center;"><?php echo $invoice_detail->division; ?></h3>
        <table border="1" style="width: 100%;border-collapse:collapse;" cellpadding="5" cellspacing="0">
            <tr>
                <th>SL No.</th>
                <th>Test Name</th>
                <!-- <th>Test Method</th> -->
                <th>Rate Per Test (<?php echo $invoice_detail->currency_basic_unit ?>)</th>
                <th>Quantity</th>
                <?php if ($invoice_detail->show_discount == 1) { ?>
                    <th>Discount %</th>
                <?php } ?>
                <th>Applicable Charge (<?php echo $invoice_detail->currency_basic_unit ?>)</th>
            </tr>
            <?php $sno = 1;
            if (!empty($test_details)) {

                foreach ($test_details as $key => $tests) {  ?>
                    <tr>
                        <td><?php echo $sno;
                            $sno++; ?></td>
                        <td><?php echo $tests['dynamic_heading']; ?></td>
                        <!-- <td><?php echo $tests['test_method'] ?></td> -->
                        <td align="right"><?php echo number_format($tests['dynamic_value'], $invoice_detail->currency_decimal); ?> </td>
                        <td align="right"><?php echo number_format($tests['quantity'], $invoice_detail->currency_decimal); ?> </td>
                        <?php if ($invoice_detail->show_discount == 1) { ?>
                            <td align="right"><?php echo number_format($tests['discount'], $invoice_detail->currency_decimal); ?> </td>
                        <?php } ?>
                        <td align="right"><?php echo number_format($tests['applicable_charge'], $invoice_detail->currency_decimal); ?> </td>
                    </tr>
            <?php }
            } ?>
            <?php if (!empty($package_details)) {
                // $sno = 1;
                //pre_r($package_details);
                foreach ($package_details as $key => $package) {
                    $rowspan = count($package_details); ?>
                    <tr>
                        <td><?php echo $sno;
                            $sno++; ?></td>
                        <td><?php echo $package['dynamic_heading']; ?></td>
                        <!-- <td><?php echo $package['test_method'] ?></td> -->
                        <?php if ($key == 0) { ?>
                            <td rowspan="<?php echo $rowspan; ?>" align="right"><?php echo number_format($package['dynamic_value'], $invoice_detail->currency_decimal); ?> </td>
                            <td rowspan="<?php echo $rowspan; ?>" align="right"><?php echo number_format($package['quantity'], $invoice_detail->currency_decimal); ?> </td>
                            <?php if ($invoice_detail->show_discount == 1) { ?>
                                <td rowspan="<?php echo $rowspan; ?>" align="right"><?php echo number_format($package['discount'], $invoice_detail->currency_decimal); ?> </td>
                            <?php } ?>
                            <td rowspan="<?php echo $rowspan; ?>" align="right"><?php echo number_format($package['applicable_charge'], $invoice_detail->currency_decimal); ?> </td>
                        <?php } ?>
                    </tr>
            <?php }
            } ?>
            <?php if (!empty($protocol_details)) {
                //$sno = 1;
                // pre_r($protocol_details);
                foreach ($protocol_details as $key => $protocol) {
                    $rowspan = count($protocol_details); ?>
                    <tr>
                        <td><?php echo $sno;
                            $sno++; ?></td>
                        <td><?php echo $protocol['dynamic_heading']; ?></td>
                        <!-- <td><?php echo $protocol['test_method'] ?></td> -->
                        <?php if ($key == 0) { ?>
                            <td rowspan="<?php echo $rowspan; ?>" align="right"><?php echo number_format($protocol['dynamic_value'], $invoice_detail->currency_decimal); ?> </td>
                            <td rowspan="<?php echo $rowspan; ?>" align="right"><?php echo number_format($protocol['quantity'], $invoice_detail->currency_decimal); ?> </td>
                            <?php if ($invoice_detail->show_discount == 1) { ?>
                                <td rowspan="<?php echo $rowspan; ?>" align="right"><?php echo number_format($protocol['discount'], $invoice_detail->currency_decimal); ?> </td>
                            <?php } ?>
                            <td rowspan="<?php echo $rowspan; ?>" align="right"><?php echo number_format($protocol['applicable_charge'], $invoice_detail->currency_decimal); ?> </td>
                        <?php } ?>
                    </tr>
            <?php }
            } ?>
        </table>
        <table style="width: 100%; border-bottom:1px solid #000; border-left:1px solid #000; border-right:1px solid #000" cellspacing="0" cellpadding="5">
          
            <tr>
                <th style="text-align: right; border-bottom:1px solid #000">Total Amount</th>
                <td style="text-align: right; border-bottom:1px solid #000; border-left:1px solid #000;" width="29%"><?php echo number_format($invoice_detail->total_amount, $invoice_detail->currency_decimal); ?> <?php //echo $invoice_detail->currency_basic_unit
                                                                                                                                                                                                                    ?></td>
            </tr>
    <!-- new surcharge -->
     <?php if(!empty($invoice_detail->surcharge_percentage) || ($invoice_detail->surcharge_percentage != '' )) { ?>
            <tr>
            <th style="text-align: right; border-bottom:1px solid #000">Surcharge Percentage</th>
                <td style="text-align: right; border-bottom:1px solid #000; border-left:1px solid #000;" width="29%"><?php echo number_format($invoice_detail->surcharge_percentage, $invoice_detail->currency_decimal); ?> <?php //echo $invoice_detail->currency_basic_unit
                                                                                                                                                                                                                    ?></td>
            </tr>
            <?php } ?>
            <?php if(!empty($invoice_detail->surcharge_percentage) || ($invoice_detail->surcharge_percentage != '')) { ?>
            <tr>
            <th style="text-align: right; border-bottom:1px solid #000">Surcharge Total</th>
                <td style="text-align: right; border-bottom:1px solid #000; border-left:1px solid #000;" width="29%"><?php echo number_format($invoice_detail->total_amount, $invoice_detail->currency_decimal); ?> <?php //echo $invoice_detail->currency_basic_unit
                                                                                                                                                                                                                    ?></td>
            </tr>
            <?php } ?>
            <!-- end surcharge-->
            <tr>
                <td colspan="2" style="text-align: right; border-bottom:1px solid #00"><?php if (array_key_exists("GST", $invoice_detail->gst)) {
                                                                                            echo $invoice_detail->gst['GST'];
                                                                                        } else {
                                                                                            echo $invoice_detail->gst['VAT'];
                                                                                        } ?></td>
            </tr>
            <tr>
                <th style="text-align: right;"> Total (inc.tax)</th>
                <td style="text-align: right; border-left:1px solid #000;"><?php echo $invoice_detail->final_amount; ?></td>
            </tr>
        </table>

        <!-- Added by Saurabh on 05-08-2021 -->
        <?php if (!empty($invoice_detail->invoice_remark)) { ?>
            <h5>Remark:</h5>
            <p><?php echo $invoice_detail->invoice_remark; ?></p>
        <?php } ?>

        <h5 style="line-height: 0;margin:0;">Amount Chargeable in words</h5>
        <h5 style="line-height: 0;margin:0;"><?php echo $invoice_detail->amount_in_word; ?></h5>
        <div style="page-break-before: always;">
        <table border="0" style="border:1px Solid">
            <tr >

                <th style="border-bottom:1px Solid;border-right:1px Solid; vertical-align:top">Domestic Clients</th>
                <th style="border-bottom:1px Solid; vertical-align:top">International Clients</th>
            </tr>
            <tr>
                <td style="border-right:1px Solid; vertical-align:top">
                    <table border="0" style="font-size:14px;">
                        <tr>
                            <td>Company PAN No.</td>
                            <td>:</td>
                            <td>AAJCB6475A</td>
                        </tr>
                        <tr>
                            <td>Bank</td>
                            <td>:</td>
                            <td>ICICI BANK LTD</td>
                        </tr>
                        <tr>
                            <td> Account Holder Name</td>
                            <td>:</td>
                            <td>Basil Quality Testing Lab Pvt. Ltd.</td>
                        </tr>
                        <tr>
                            <td>Type of Account</td>
                            <td>:</td>
                            <td>Current Account</td>
                        </tr>
                        <tr>
                            <td>Account Number</td>
                            <td>:</td>
                            <td>337405000645</td>
                        </tr>
                        
                        <tr>
                            <td>IFSC /RTGS</td>
                            <td>:</td>
                            <td>ICIC0003374</td>
                        </tr>

                        <tr>
                            <td>Branch & Address</td>
                            <td>:</td>
                            <td>MS Complex Khasra No. 378 Kapashera Old Delhi Gurgaon Road New Delhi 110037</td>
                        </tr>
                        
                        <tr>
                            <td>MICR Code (9 digits)</td>
                            <td>:</td>
                            <td>110229240</td>
                        </tr>
                    </table>
                </td>
                <td>
                    <table border="0">
                        <tr>
                            <td>
                                <table style="font-size:14px;">
                                    <tr>
                                        <td> Company PAN No.</td>
                                        <td>:</td>
                                        <td>AAJCB6475A</td>
                                    </tr>
                                    <tr>
                                        <td>Bank Name</td>
                                        <td>:</td>
                                        <td>ICICI BANK LTD</td>
                                    </tr>
                                    <tr>
                                        <td> Account Holder Name</td>
                                        <td>:</td>
                                        <td>Basil Quality Testing Lab Private Limited</td>
                                    </tr>
                                    <tr>
                                        <td>Type Of Account</td>
                                        <td>:</td>
                                        <td>Current Account</td>
                                    </tr>
                                    <tr>
                                        <td>Account Number</td>
                                        <td>:</td>
                                        <td>337406000004</td>
                                    </tr>
                                   
                                    <tr>
                                        <td>IFSC Code</td>
                                        <td>:</td>
                                        <td>ICIC0003374</td>
                                    </tr>
                                    <tr>
                                        <td>Branch & Address</td>
                                        <td>:</td>
                                        <td>MS Complex Khasra No. 378 Kapashera Old Delhi Gurgaon Road New Delhi 110037</td>
                                    </tr>
                                    <tr>
                                        <td>Swift Code No.</td>
                                        <td>:</td>
                                        <td>ICICINBBCTS</td>
                                    </tr>
                                    <tr>
                                        <td>MICR Code (9 digits)</td>
                                        <td>:</td>
                                        <td></td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        </div>
        <p style="line-height: 0;margin:0;">E. & O.E</p>
        <h4 style="line-height: 0;margin:0;">This is a computer generated Proforma Invoice and does not requires any signatures.</h4>
        <h4 style="line-height: 0;margin:0;">Note:</h4>
        <ol>
            <li style="font-style: italic;">Payment to be made by draft/cheque 'Payee's Account Only', NEFT, RTGS in favour of : Basil Quality Testing Lab Pvt. Ltd.</li>
            <li style="font-style: italic;">Please mention Proforma Invoice Number. When effecting payments.</li>
            <!-- <li style="font-style: italic;">In case of any concern, please revert to customer.support@basilrl.com within 24 hrs or before the due date of test report.</li> -->
        </ol>

        <table style="width: 100%;position:absolute;right:0;margin-left:45%; margin-top:60%" align="right">
            <tr>
                <th style="text-align: center;">FOR BASIL QUALITY TESTING LAB PVT LTD</th>
            </tr>
            <?php if ($invoice_detail->authorized_signatory == 1) { ?>
                <tr>
                    <td style="text-align: center;"><img src="<?php echo $invoice_detail->authorized_signature ?>" alt="" width="15%;"></td>
                </tr>
                <tr>
                    <td style="text-align: center;"><?php echo $invoice_detail->authorized_name ?></td>
                </tr>
                <tr>
                    <td style="text-align: center;"><?php echo $invoice_detail->authorized_designation ?></td>
                </tr>
            <?php } ?>
            <tr>
                <th style="text-align: center;">Authorized Signatory</th>
            </tr>
        </table>
</body>

</html>
