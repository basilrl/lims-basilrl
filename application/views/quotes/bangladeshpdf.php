<!DOCTYPE html>
<html lang="en">

<head>
    <title><?php echo ($data->reference_no) ? $data->reference_no : ""; ?></title>
</head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<style>
    @page {
        background: url('https://cpslims-prod.s3.ap-south-1.amazonaws.com/format/quote_banner.png') no-repeat 0 0;
        margin-top: 3.0cm !important;
        margin-bottom: 3.0cm !important;
        background-position: center;
        background-repeat: no-repeat;
        width: 100%;
        opacity: 0.3;
        background-size: cover !important;
        header: html_myHeader;
        footer: html_myFooter;

    }

    table.content {
        text-align: center;
    }

    div.content {
        page-break-before: always;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    thead th,
    tbody td {
        border: 1px solid black;
    }

    tfoot td {
        text-align: right;
    }

    .test_table td,
    .test_table th {
        border: 1px solid black;
        border-collapse: collapse;
        align-self: center;


    }

    div.cust_details {
        page-break-before: always;
    }

    .post_details td {
        border: 1px solid black;
        border-collapse: collapse;
        align-self: center;
    }

    @page :first {
        header: html_firstpageheader;

    }
</style>

<body>

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
    ?>
    <htmlpageheader name="firstpageheader" style="display:none">

    </htmlpageheader>

    <htmlpageheader name="myHeader">
        <table style="width:100%; text-align: right;">

            <tr>
                <td>
                    <img width="160px" height="50px" src="https://cpslims-prod.s3.ap-south-1.amazonaws.com/format/quote_logo.png" alt="">
                </td>
            </tr>

        </table>

    </htmlpageheader>

    <htmlpagefooter name="myFooter">
        <table style="width: 100%;">
            <tr>
                <td style="width:100%; text-align:center;color: grey;">M: <?php echo ($data->admin_email) ? ($data->admin_email) : ""; ?> | T: <?php echo ($data->admin_telephone) ? ($data->admin_telephone) : ""; ?> | W: portal.lims.basilrl.com</td>
            </tr>
            <tr>
                <td style="width:100%; text-align:center;color: grey;"> <?php echo ($data->branch_address) ? ($data->branch_address) : ""; ?></td>
            </tr>
        </table>
    </htmlpagefooter>

    <!-- about us -->
    <?php if (array_key_exists('about_us_details', $data)) { ?>

        <?php if (!empty($data->about_us_details) && $data->about_us_details != NULL) { ?>
            <?php echo $data->about_us_details; ?>
            <div style="page-break-inside: auto !important;" class="cust_details">

        <?php }
    } ?>

            </div>
            <!-- end -->

            <!-- customer details -->

            <!-- end -->
            <!-- <table>
            <tr>
                <td>
                    <p style="text-transform: uppercase;"><b>DEAR &nbsp;&nbsp;<?php echo ($data->contact_name) ? ($data->contact_name) : "" ?>,</b></p>
                    <p><b><?php echo ($data->customer_name) ? ($data->customer_name) : ""; ?>,</b></p>
                    <p><b><?php echo ($data->address) ? ($data->address) : "" ?></b></p>
                </td>
            </tr>
        </table>
        <p>We are pleased to offer you with our special price offer for merchandise quality testing for</p>



        <table class="post_details">
            <tr>
                <td colspan="2"><b>TO : <?php echo ($data->customer_name) ? ($data->customer_name) : ""; ?></b></td>
            </tr>

            <tr>
                <td><b>ATTN : </b>&nbsp;&nbsp;<?php echo ($data->contact_salutation) ? ($data->contact_salutation) : ""; ?>&nbsp;&nbsp;<?php echo ($data->contact_name) ? ($data->contact_name) : "" ?><br>&nbsp;&nbsp;<?php echo ($data->contacts_designation_id) ? ($data->contacts_designation_id) : "" ?></td>

                <td><b>FROM : </b><?php echo ($data->quote_created_user) ? ($data->quote_created_user) : "" ?><br>
                    <?php echo ($data->designation_name) ? ($data->designation_name) : "" ?>
                </td>
            </tr>
            <tr>
                <td><b>QUOTE NO : </b>&nbsp;&nbsp;<?php echo ($data->reference_no) ? ($data->reference_no) : "" ?></td>
                <td><b>PAGES : </b>{nb}</td>
            </tr>
            <tr>
                <td><b>DATE : </b>&nbsp;&nbsp;<?php echo ($data->quote_date) ? ($data->quote_date) : "" ?></td>
                <td><b>TEL NO : </b>&nbsp;&nbsp;<?php echo ($data->admin_telephone) ? ($data->admin_telephone) : "" ?></td>
            </tr>
            <tr>
                <td>
                    <p><b>ADDRESS : </b>&nbsp;&nbsp;<?php echo ($data->address) ? ($data->address) : "" ?></p>
                    <p><b>Tel :</b>&nbsp;&nbsp;<?php echo ($data->telephone) ? ($data->telephone) : "" ?></p>
                    <p><b>Email :</b>&nbsp;&nbsp;<?php echo ($data->email) ? ($data->email) : ""  ?></p>
                </td>
                <td>
                    <b>EMAIL : &nbsp;&nbsp; <?php echo ($data->admin_email) ? ($data->admin_email) : "" ?></b>
                </td>
            </tr>
            <tr>
                <td><b>Discussion Dated :</b>&nbsp;&nbsp;<?php echo ($data->discussion_date) ? ($data->discussion_date) : "" ?><?php echo $version_number ?></td>
                <td><b>Quote Validity :</b>&nbsp;&nbsp;<?php echo ($data->quote_valid_date) ? ($data->quote_valid_date) : "" ?></td>
            </tr>
            <tr>
                <td colspan="2"><b>Remarks : </b><?php echo (html_entity_decode($data->additional_notes)) ? (html_entity_decode($data->additional_notes)) : ""; ?></td>
            </tr>
            <tr>
                <td colspan="2"><b>Buyer/self reference : </b><?php echo ($data->buyer_self_ref) ? ($data->buyer_self_ref) : ""; ?></td>
            </tr>

        </table> -->


            <!-- introduction -->

            <!-- about us -->
            <?php if (array_key_exists('introduction', $data)) { ?>

                <?php if (!empty($data->introduction) && $data->introduction != NULL) { ?>
                    <?php echo $data->introduction; ?>

            <?php }
            } ?>

            <!-- end -->
            <!-- end -->

            <?php $total_value = 0;
            if (array_key_exists('currency_code', $data)) {
                $currency_code = $data->currency_code;
            } else {
                $currency_code = '';
            }

            ?>
            <!-- test table details -->

            <?php if (!empty($test_data[0]->id)) : ?>
                <?php if ($test_data && count($test_data) > 0) { ?>

                    <h3 style="text-align: center;">TEST DETAILS :</h3>
                    <table class="test_table content" style="margin-top:0px;">
                        <thead>
                            <tr>
                                <th>Sr. No.</th>
                                <?php if (array_key_exists('show_products', $data)) {
                                    if ($data->show_products == '1') { ?>
                                        <th>Product Name</th>
                                <?php }
                                } ?>

                                <?php if (array_key_exists('show_division', $data)) {
                                    if ($data->show_division == '1') { ?>
                                        <th>Division Name</th>
                                <?php }
                                } ?>

                                <th>Test Name</th>
                                <?php if (array_key_exists('show_test_method', $data)) {
                                    if ($data->show_test_method == '1') { ?>
                                        <th>Test Method</th>
                                <?php }
                                } ?>

                                <?php if (array_key_exists('show_price', $data)) {
                                    if ($data->show_price == '1') { ?>
                                        <th>Test Price (<?php echo $currency_code; ?>)</th>
                                <?php }
                                } ?>

                                <?php if (array_key_exists('show_discount', $data)) {
                                    if ($data->show_discount == '1') { ?>
                                        <th>Discount</th>
                                <?php }
                                } ?>

                                <th>Geo Chem Discounted Price Proposal (<?php echo $currency_code; ?>)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $sn = 1;
                            foreach ($test_data as $key => $value) { ?>
                                <tr>
                                    <td><?php echo $sn; ?></td>
                                    <?php if (array_key_exists('show_products', $data)) {
                                        if ($data->show_products == '1') { ?>
                                            <td><?php echo ($value->sample_type_name) ? ($value->sample_type_name) : ""; ?></td>
                                    <?php }
                                    } ?>

                                    <?php if (array_key_exists('show_division', $data)) {
                                        if ($data->show_division == '1') { ?>

                                            <td><?php echo ($value->work_division_name) ? ($value->work_division_name) : ""; ?></td>
                                    <?php }
                                    } ?>
                                    <td><?php echo ($value->name) ? ($value->name) : "" ?></td>

                                    <?php if (array_key_exists('show_test_method', $data)) {
                                        if ($data->show_test_method == '1') { ?>
                                            <td><?php echo ($value->test_method) ? ($value->test_method) : "" ?></td>
                                    <?php }
                                    } ?>

                                    <?php if (array_key_exists('show_price', $data)) {
                                        if ($data->show_price == '1') { ?>
                                            <td><?php echo ($value->price) ? number_format((float)$value->price, 2, '.', '') : 0.00; ?></td>
                                    <?php }
                                    } ?>

                                    <?php if (array_key_exists('show_discount', $data)) {
                                        if ($data->show_discount == '1') { ?>
                                            <td><?php echo ($value->discount) ? number_format((float)$value->discount, 2, '.', '') : 0.00; ?></td>
                                    <?php }
                                    } ?>
                                    <td width="20%"><?php echo ($value->applicable_charge) ? number_format((float)$value->applicable_charge, 2, '.', '') : 0.00; ?></td>
                                </tr>
                                <?php $total_value += $value->applicable_charge; ?>
                            <?php $sn++;
                            } ?>
                        </tbody>
                    </table>
                <?php } ?>
            <?php endif; ?>

            <br><br><br>
            <?php if ($protocol_data && count($protocol_data) > 0) { ?>

                <h3 style="text-align: center;">PROTOCOL DETAILS :</h3>
                <h3 style="text-align: center;"><?php echo ($protocol_data['product_name']) ? $protocol_data['product_name'] : ""; ?></h3>

                <table class="test_table content" style="margin-top:0px;">
                    <thead>
                        <tr>
                            <th>Sl No</th>
                            <th>Protocol Name</th>
                            <th>Test</th>
                            <?php if (array_key_exists('show_price', $data)) {
                                if ($data->show_price) { ?>
                                    <th>Protocol Cost (<?php echo $currency_code; ?>)</th>
                            <?php }
                            } ?>

                            <?php if (array_key_exists('show_discount', $data)) {
                                if ($data->show_discount) { ?>
                                    <th>Discount</th>

                            <?php }
                            } ?>
                            <th>Geo Chem Discounted Price Proposal (<?php echo $currency_code; ?>)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $sn = 1; ?>
                        <tr>
                            <td><?php echo $sn; ?></td>
                            <td><?php echo ($protocol_data['product_name']) ? $protocol_data['product_name'] : ""; ?></td>
                            <td style="text-align: left;padding:10px">
                                <?php foreach ($protocol_data['test_name'] as $i => $v) { ?>
                                    <p><?php echo $v ?></p><br>
                                <?php } ?>
                            </td>
                            <?php if (array_key_exists('show_price', $data)) {
                                if ($data->show_price) { ?>
                                    <td><?php echo ($protocol_data['cost']) ? number_format((float)$protocol_data['cost'], 2, '.', '') : ""; ?></td>
                            <?php }
                            } ?>

                            <?php if (array_key_exists('show_discount', $data)) {
                                if ($data->show_discount) { ?>
                                    <td><?php echo ($protocol_data['discount']) ? number_format((float)$protocol_data['discount'], 2, '.', '') : 0.00; ?></td>
                            <?php }
                            } ?>
                            <td width="20%"><?php echo ($protocol_data['total_cost']) ? number_format((float)$protocol_data['total_cost'], 2, '.', '') : ""; ?></td>
                        </tr>
                        <?php $total_value += $protocol_data['total_cost'] ?>
                        <?php  ?>
                    </tbody>
                </table>
            <?php } ?>

            <br><br><br>
            <?php if ($package_data && count($package_data) > 0) { ?>

                <h3 style="text-align: center;">PACKAGE DETAILS :</h3>
                <h3 style="text-align: center;"><?php echo ($package_data['package_name']) ? $package_data['package_name'] : ""; ?></h3>

                <table class="test_table content" style="margin-top:0px;">
                    <thead>
                        <tr>
                            <th>Sl No</th>
                            <th>Package Name</th>
                            <th>Test</th>
                            <?php if (array_key_exists('show_price', $data)) {
                                if ($data->show_price) { ?>
                                    <th>Package Cost (<?php echo $currency_code; ?>)</th>
                            <?php }
                            } ?>

                            <?php if (array_key_exists('show_discount', $data)) {
                                if ($data->show_discount) { ?>
                                    <th>Discount</th>
                            <?php }
                            } ?>
                            <th>Geo Chem Discounted Price Proposal (<?php echo $currency_code; ?>)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $sn = 1; ?>
                        <tr>
                            <td><?php echo $sn; ?></td>
                            <td><?php echo ($package_data['product_name']) ? $package_data['product_name'] : ""; ?></td>
                            <td style="text-align: left;padding:10px">
                                <?php foreach ($package_data['test_name'] as $i => $v) { ?>
                                    <p><?php echo $v ?></p><br>
                                <?php } ?>
                            </td>

                            <?php if (array_key_exists('show_price', $data)) {
                                if ($data->show_price) { ?>
                                    <td><?php echo ($package_data['cost']) ? number_format((float)$package_data['cost'], 2, '.', '') : ""; ?></td>
                            <?php }
                            } ?>

                            <?php if (array_key_exists('show_discount', $data)) {
                                if ($data->show_discount) { ?>
                                    <td><?php echo ($package_data['discount']) ? number_format((float)$package_data['discount'], 2, '.', '') : 0.00; ?></td>
                            <?php }
                            } ?>
                            <td width="20%"><?php echo ($package_data['total_cost']) ? number_format((float)$package_data['total_cost'], 2, '.', '') : ""; ?></td>
                        </tr>
                        <?php $total_value += $package_data['total_cost'] ?>
                        <?php  ?>
                    </tbody>
                </table>
            <?php } ?>


            <?php $sgst = round(($total_value / 100) * 9); ?>
            <?php $cgst = round(($total_value / 100) * 9); ?>

            <?php if (array_key_exists('show_total_amount', $data)) { ?>
                <?php if ($data->show_total_amount == '1') { ?>
                    <table style="width: 100%;" border="1" style="margin-top:-6px;" cellpadding="5">
                        <tr>
                            <td align="right">Total Amount</td>
                            <td width="20%" align="center"><?php echo number_format((float)$total_value, 2, '.', ''); ?></td>
                        </tr>
                    </table>
                <?php } ?>
            <?php } ?>

            <table style="width: 100%;">
                <?php if ($data->gst && count($data->gst) > 0) : ?>
                    <?php echo $data->gst['TAX']; ?>
                <?php endif; ?>
            </table>


            <!-- notes -->
            <?php if (array_key_exists('notes_details', $data)) : ?>
                <?php if (!empty($data->notes_details) && ($data->notes_details != NULL)) : ?>
                    <?php echo $data->notes_details; ?>
                <?php endif; ?>
            <?php endif; ?>

            <!-- end -->


            <!-- contact_details -->
            <?php if (array_key_exists('contact_details', $data)) : ?>
                <?php if (!empty($data->contact_details) && ($data->contact_details != NULL)) : ?>
                    <?php echo $data->contact_details; ?>
                <?php endif; ?>
            <?php endif; ?>

            <!-- end -->


            <table style="margin-top:50px;">
                <tr>
                    <td style="width: 45%;">
                        <table>
                            <tr>
                                <td colspan="2">Prepared by-</td>
                            </tr>
                            <tr>
                                <td colspan="2"><?php echo ($data->quote_created_user) ? ($data->quote_created_user) : ""; ?></td>
                            </tr>
                            <tr>
                                <td colspan='2'>
                                    <img src="<?php echo ($data->admin_signature) ? ($data->admin_signature) : ""; ?>" alt=" SIGNATURE" height="50px" width="200px">
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2"><?php echo ($data->designation_name) ? ($data->designation_name) : ""; ?> | CPS Division</td>
                            </tr>
                            <tr>
                                <td colspan="2">GEO-CHEM LABORATORIES PVT. LTD.</td>
                            </tr>
                            <tr>
                                <td>Telephone :</td>
                                <td><?php echo ($data->branch_telephone) ? ($data->branch_telephone) : ""; ?> </td>
                            </tr>
                            <tr>
                                <td>Mobile :</td>
                                <td><?php echo ($data->admin_telephone) ? ($data->admin_telephone) : ""; ?></td>
                            </tr>
                            <tr>
                                <td>Email :</td>
                                <td><?php echo ($data->admin_email) ? ($data->admin_email) : ""; ?></td>
                            </tr>
                            <tr>
                                <td>Website :</td>
                                <td>
                                    <p>https://basilrl.com</p>                                   
                                </td>
                            </tr>
                            <tr>
                                <td>Address :</td>
                                <td><?php echo ($data->branch_address) ? ($data->branch_address) : ""; ?></td>
                            </tr>
                        </table>
                    </td>
                    <td style="width: 10%;"></td>
                    <td style="width: 45%;">
                        <table>
                            <tr>
                                <td colspan="2">Approved by -</td>
                            </tr>
                            <tr>
                                <td colspan="2"><?php echo ($data->approver) ? ($data->approver) : "" ?></td>
                            </tr>
                            <tr>
                                <td colspan='2'>
                                    <img src="<?php echo ($data->approver_signature) ? ($data->approver_signature) : ""; ?>" alt="APPROVER SIGNATURE" height="50px" width="200px">
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2"><?php echo ($data->approver_designation_name) ? ($data->approver_designation_name) : ""; ?> | CPS Division</td>
                            </tr>
                            <tr>
                                <td colspan="2">GEO-CHEM LABORATORIES PVT. LTD.</td>
                            </tr>
                            <tr>
                                <td>Telephone :</td>
                                <td><?php echo ($data->branch_telephone) ? ($data->branch_telephone) : ""; ?></td>
                            </tr>
                            <tr>
                                <td>Mobile :</td>
                                <td><?php echo ($data->approver_telephone) ? ($data->approver_telephone) : ""; ?></td>
                            </tr>
                            <tr>
                                <td>Email :</td>
                                <td><?php echo ($data->approver_email) ? ($data->approver_email) : ""; ?></td>
                            </tr>
                            <tr>
                                <td>Website :</td>
                                <td>
                                    <p>https://basilrl.com</p>
                                   
                                </td>
                            </tr>
                            <tr>
                                <td>Address :</td>
                                <td><?php echo ($data->branch_address) ? ($data->branch_address) : ""; ?></td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>

            <br>
            <!-- terms_details -->
            <?php if (array_key_exists('terms_details', $data)) : ?>
                <?php if (!empty($data->terms_details) && ($data->terms_details != NULL)) : ?>
                    <?php echo $data->terms_details; ?>

                <?php endif; ?>

            <?php endif; ?>


            <!-- end -->



            <!-- <table style="width: 100%;text-align:left;margin-top:25px;">
            <tr>
                <td style="text-align: left;">
                    <img src="<?php echo ($data->approver_signature) ? ($data->approver_signature) : ""; ?>" alt="APPROVER SIGNATURE">
                </td>
                    </tr>
                    <tr>
                <td style="text-align: left;">
                    <?php echo ($data->approver) ? ($data->approver) : "" ?>
                </td>
            </tr>
            <tr>
                <td style="text-align: left;">
                    Approver
                </td>
                
            </tr>

        </table> -->
            </div>


</body>

</html>