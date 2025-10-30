<?php
$checkUser = $this->session->userdata('user_data');
$this->user = $checkUser->uidnr_admin;
$user = $this->user = $checkUser->uidnr_admin;
$bothUserSign = (!empty($report_data->signing_authority) && !empty($report_data->sign_authority_new)) ? true : false;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BD REPORT</title>
</head>
<style>
    @page {
        background: url('https://basilrl-prod.s3.ap-south-1.amazonaws.com/draft-report-pdf-bg.jpeg') no-repeat 0 0;
        background-repeat: no-repeat;
        width: 100%;
        opacity: 0.3;
        background-size: cover !important;
        margin-top: 5.5cm !important;
        margin-bottom: 2cm !important;
        background-position: top center;
        header: html_myHeader;
        footer: html_myFooter;

    }

    @page :first {
        header: html_myHeader1;
        footer: html_myFooter1;
    }

    /* @page chapter2 {
        header: html_myHeader2;
    }
    @page noheader {
        header: _blank;
    } */
    * {
        font-size: 10px;
    }



    .detail_table table {
        width: 100% !important;
        text-align: center;
        border: 1px solid black;
    }

    .detail_table * {
        width: 100% !important;
        text-align: center;
    }

    div.noheader {
        page-break-before: always;
    }

    .sample_image1 {
        page-break-inside: auto !important;
    }
</style>

<body>



    <div class="bg_image">

        <htmlpageheader name="myHeader1">
            <table style="width: 100%;">

                <tr>
                    <td>
                        <img src="https://basilrl-prod.s3.ap-south-1.amazonaws.com/stationary/logo-basil-pdf.png" alt="" style="text-align:left;" width="20%;">
                    </td>
                    <td>
                        <img src="https://cpslims-prod.s3.ap-south-1.amazonaws.com/format/nabl.png" alt="" width="12%" style="text-align:left;">
                    </td>
                    <td>
                        <img src="https://cpslims-prod.s3.ap-south-1.amazonaws.com/format/ilac.png" alt="" width="11%" style="text-align:left;">
                    </td>
                    <?php if ($bothUserSign) { ?>
                        <?php if (isset($qrcode) && ($report_data->primary_approver_status == 2) && ($report_data->secondary_approver_status == 2)) { ?>
                            <img src="<?php echo $qrcode; ?>" style="text-align:left;margin-top:23px;">
                        <?php } ?>
                    <?php } else { ?>
                        <?php if (isset($qrcode)) { ?>
                            <img src="<?php echo $qrcode; ?>" style="text-align:left;margin-top:23px;">
                        <?php } ?>
                    <?php } ?>

                    <td style="text-align: right;font-size:10px;"> GEO CHEM CONSUMER PRODUCTS SERVICES (CPS) LTD. <br>
                        SB Plaza, Plot# 37 (6th & 7th Floor), Sector# 3 <br>
                        Uttara Commercial Area <br>
                        Dhaka-1230, Bangladesh <br>
                        TEL: +88 02-48955461-2 <br>
                        Email: info.cps@basilrl.com
                    </td>
                </tr>
                <tr style="font-size: 12px;">
                    <td style="text-align: left;font-size:12px" colspan="3">REPORT No.: <?php echo $report_data->report_num; ?></td>
                    <!-- <td></td> -->

                    <td style="text-align:right!important;font-size:12px;" align="right" colspan="2">DATE: <?php echo date("d M Y", strtotime($report_data->issuance_date)); ?></td>
                </tr>
            </table>
        </htmlpageheader>

        <htmlpageheader name="myHeader">
            <table style="width:100%;font-size:12px;">
                <tr>
                    <td colspan="2" style="text-align: right;width:100%;">
                        <img src="https://basilrl-prod.s3.ap-south-1.amazonaws.com/stationary/logo-basil-pdf.png" alt="" style="text-align:right;" width="20%;">
                    </td>
                </tr>
                <tr>
                    <td style="text-align: left;width:75%;">REPORT No.: <?php echo $report_data->report_num; ?></td>
                    <td style="text-align:right!important;width:25%;" align="right">DATE: <?php echo date("d M Y", strtotime($report_data->issuance_date)); ?></td>
                </tr>
            </table>
        </htmlpageheader>

        <htmlpagefooter name="myFooter1">
            <table style="width: 100%;text-align:center;">
                <tr>
                    <td style="font-size: 8px;">The report includes all of the tests requested by you and the results on the basis of the instructions and/or information and materials supplied by you. Geo Chem does not accept a duty of care or any other responsibility to any person other than the addressee in respect of this report and only accepts liability to the addressee insofar as is expressly contained in the terms and conditions governing Geo Chem’s provision of services to you. This report sets with results solely with respect to the tested samples identified herein. The report that the results apply to the sample is as received as we are not responsible for the sampling stage. We do not accept any liability to you for any loss arising out of or in connection with this report. Geo Chem does not allow any copying or replication of this full or partial report to or for any other person or entity, or use of our trademark without its prior written permission. The results set forth in this report are only indicative or representative in accordance to the given instruction. Measurement uncertainty is only provided upon request for accredited tests. You have 60 days from date of issuance of this report to notify us of any material error or omission caused by our negligence or if you require measurement uncertainty; provided, however, that such notice shall be in writing and shall specifically address the issue you wish to raise. A failure to raise such issue within the prescribed time shall constitute you unqualified acceptance of the completeness of this report, the tests conducted and the correctness of the report contents. This Test Report cannot be reproduced, except in full, without prior written permission of the company.
                    </td>
                </tr>
                <tr>
                    <td style="font-size: 10px;">Regd. Off: Geo Chem Consumer Products Services (CPS) Ltd. SB Plaza, Plot-37 Sector 3, <br>
                        Uttara Commercial Area, Dhaka-1230, Bangladesh
                    </td>
                </tr>
                <tr>
                    <td style="text-align:right;"> Pages {PAGENO} of {nb}</td>
                </tr>
            </table>
        </htmlpagefooter>

        <htmlpagefooter name="myFooter">
            <table style="width: 100%;text-align:center;font-size: 10px;">
                </tr>
                <tr>
                    <td>Regd. Off: Geo Chem Consumer Products Services (CPS) Ltd. SB Plaza, Plot-37 Sector 3, <br>
                        Uttara Commercial Area, Dhaka-1230, Bangladesh
                    </td>
                </tr>
                <tr>
                    <td style="text-align:right"> Pages {PAGENO} of {nb}</td>
                </tr>
            </table>
        </htmlpagefooter>

        <?php if ($image_sample) { ?>
            <div class="">
                <h4 style="text-align:center;text-decoration:underline;">SUBMITTED SAMPLE IMAGES</h4>
                <?php foreach ($image_sample as $key9 => $sample_photo) {  ?>
                    <table style="width:100%;border-collapse:collapse;cellpadding:2px;">
                        <tr>
                            <td style="text-align: center;font-weight: bold;"><?php echo $sample_photo->comment; ?></td>
                        </tr>
                        <tr>
                            <td style="text-align: center;"><img src="<?php echo $sample_photo->image_file_path; ?>" style="border:1px solid #333;width:300px;" width="30%" alt="" srcset=""></td>
                        </tr>
                    </table>
                <?php } ?>
            </div>
        <?php } ?>

        <?php if ($image_sample) { ?>
            <div class="sample_image1 noheader" style="page-break-inside: auto !important;">
            <?php } else { ?>
                <div>
                <?php } ?>
                <h3 style="text-align: center;">TEST REPORT</h3>
                <table style="font-size:10px;width:100%;">
                    <?php if (!empty($report_data->customer_name)) { ?>
                        <tr>
                            <td>Applicant</td>
                            <td>:</td>
                            <td><?php echo $report_data->customer_name; ?></td>
                        </tr>
                    <?php } ?>
                    <?php if (!empty($report_data->contact_name)) { ?>
                        <tr>
                            <td>Contact Person</td>
                            <td>:</td>
                            <td><?php echo $report_data->contact_name; ?></td>
                        </tr>
                    <?php } ?>
                    <?php if (!empty($report_data->address)) { ?>
                        <tr>
                            <td>Address</td>
                            <td>:</td>
                            <td><?php echo $report_data->address; ?></td>
                        </tr>
                    <?php } ?>
                    <?php if (!empty($report_data->destination)) { ?>
                        <tr>
                            <td>Sample Description</td>
                            <td>:</td>
                            <td><?php echo $report_data->sample_desc; ?></td>
                        </tr>
                    <?php } ?>
                    <?php if (!empty($report_data->buyer_name)) { ?>
                        <tr>
                            <td>Buyer</td>
                            <td>:</td>
                            <td><?php echo $report_data->buyer_name; ?></td>
                        </tr>
                    <?php } ?>
                    <?php if (!empty($report_data->agent_name)) { ?>
                        <tr>
                            <td>Agent</td>
                            <td>:</td>
                            <td><?php echo $report_data->agent_name; ?></td>
                        </tr>
                    <?php } ?>
                    <?php if (!empty($report_data->trf_end_use)) { ?>
                        <tr>
                            <td>End use</td>
                            <td>:</td>
                            <td><?php echo $report_data->trf_end_use; ?></td>
                        </tr>
                    <?php } ?>

                    <?php if (!empty($report_data->product_custom_fields)) {
                        $custom_data = json_decode($report_data->product_custom_fields);
                        foreach ($custom_data as $key => $row) { ?>
                            <?php if (!empty($row[1])) { ?>
                                <tr>
                                    <?php foreach ($row as $key1 => $value1) {
                                        if (!empty($value1)) { ?>
                                            <?php if ($key1 == 1) { ?>
                                                <td>:</td>
                                            <?php } ?>
                                            <td>
                                                <?php echo $value1; ?>
                                            </td>
                                        <?php } ?>
                                    <?php } ?>
                                </tr>
                            <?php } ?>
                        <?php } ?>
                    <?php } ?>

                    <?php if (count($application_data) == 1) { ?>
                        <?php if ($app['instruction_name'] != '') { ?>
                            <?php $images = array(); ?>
                            <tr>
                                <td>Application Care Instruction</td>
                                <td>:</td>
                                <td>
                                    <?php foreach ($application_data as $key3 => $app) { ?>
                                        <img src="<?php echo $app['instruction_image']; ?>" alt="">

                                        <?php $description[] = $app['instruction_name']; ?>
                                    <?php } ?>
                                </td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td>
                                    <?php if (count($description) > 0) { ?>
                                        <?php echo implode(',', $description); ?>
                                    <?php } ?>
                                </td>
                            </tr>
                        <?php } ?>
                    <?php } ?>

                    <?php if (count($application_data) > 1) { ?>
                        <?php $images = array(); ?>
                        <tr>
                            <td>Application Care Instruction</td>
                            <td>:</td>
                            <td>
                                <?php foreach ($application_data as $key3 => $app) {
                                    if ($app['instruction_name'] != '') { ?>
                                        <img src="<?php echo $app['instruction_image']; ?>" alt="">
                                        <?php $description[] = $app['instruction_name']; ?>
                                    <?php } ?>
                                <?php } ?>
                            </td>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td>
                                <?php if (count($description) > 0) { ?>
                                    <?php echo implode(',', $description); ?>
                                <?php } ?>
                            </td>
                        </tr>
                    <?php } ?>

                    <?php if (!empty($report_data->country_origin)) { ?>
                        <tr>
                            <td>Country of Origin</td>
                            <td>:</td>
                            <td><?php echo $report_data->country_origin; ?></td>
                        </tr>
                    <?php } ?>
                    <?php if (!empty($report_data->destination)) { ?>
                        <tr>
                            <td>Country of Destination</td>
                            <td>:</td>
                            <td><?php echo $report_data->destination; ?></td>
                        </tr>
                    <?php } ?>
                    <?php if (!empty($report_data->received_date)) { ?>
                        <tr>
                            <td>Sample Received Date</td>
                            <td>:</td>
                            <td><?php echo date("d/m/y", strtotime($report_data->received_date)); ?></td>
                        </tr>
                    <?php } ?>
                    <?php if (!empty($report_data->received_date) && !empty($report_data->generated_date)) { ?>
                        <tr>
                            <td>Testing Period</td>
                            <td>:</td>
                            <td><?php echo date("d/m/y", strtotime($report_data->received_date)); ?> to <?php echo date("d/m/y", strtotime($report_data->generated_date)); ?></td>
                        </tr>
                    <?php } ?>
                </table>

                <br />
                <table style="width:100%;padding:0;margin:0;font-size:10px;">
                    <tr>
                        <?php if ($bothUserSign && !empty($sign_data1) && !empty($signature1) && ($report_data->primary_approver_status == 2) && !empty($sign_data2) && !empty($signature2) && ($report_data->secondary_approver_status == 2)) { ?>
                            <td style="width:50%;padding:0;margin:0;vertical-align:top;text-align:left;">
                                <table style="width:100%">
                                    <tr>
                                        <td>For and on behalf of</td>
                                    </tr>
                                    <tr>
                                        <td>Geo-Chem Laboratories Pvt. Ltd.</td>
                                    </tr>
                                    <tr>
                                        <td style="text-align:left;"><img src="<?php echo $signature1; ?>" height="100"></td>
                                    </tr>
                                    <tr>
                                        <td style="text-align:left;"> <?php echo $sign_data1->name; ?></td>
                                    </tr>
                                    <tr>
                                        <td style="text-align:left;"> <?php echo $sign_data1->admin_role_name; ?> </td>
                                    </tr>
                                    <tr>
                                        <td style="text-align:left;"> <?php echo "Authorized Signatory"; ?> </td>
                                    </tr>
                                </table>
                            </td>
                            <td style="width:50%;padding:0;margin:0;vertical-align:top;text-align:right;">
                                <table style="width:100%;">
                                    <tr>
                                        <td style="text-align: right;width:100%;">For and on behalf of</td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: right;">Geo-Chem Laboratories Pvt. Ltd.</td>
                                    </tr>
                                    <tr>
                                        <td style="text-align:right;"><img src="<?php echo $signature2; ?>" height="100"></td>
                                    </tr>
                                    <tr>
                                        <td style="text-align:right;"> <?php echo $sign_data2->name; ?></td>
                                    </tr>
                                    <tr>
                                        <td style="text-align:right;"> <?php echo $sign_data2->admin_role_name; ?> </td>
                                    </tr>
                                    <tr>
                                        <td style="text-align:right;"> <?php echo "Authorized Signatory"; ?> </td>
                                    </tr>
                                </table>
                            </td>
                        <?php } else { ?>
                            <?php if (isset($sign_data1->uidnr_admin) && $sign_data1->uidnr_admin == $user) { ?>
                                <?php if ($signature1) { ?>
                                    <td style="width:50%;padding:0;margin:0;vertical-align:top;text-align:left;">
                                        <table style="width:100%">
                                            <tr>
                                                <td>For and on behalf of</td>
                                            </tr>
                                            <tr>
                                                <td>Geo-Chem Laboratories Pvt. Ltd.</td>
                                            </tr>
                                            <tr>
                                                <td style="text-align:left;"><img src="<?php echo $signature1; ?>" height="100"></td>
                                            </tr>
                                            <tr>
                                                <td style="text-align:left;"> <?php echo $sign_data1->name; ?></td>
                                            </tr>
                                            <tr>
                                                <td style="text-align:left;"> <?php echo $sign_data1->admin_role_name; ?> </td>
                                            </tr>
                                            <tr>
                                                <td style="text-align:left;"> <?php echo "Authorized Signatory"; ?> </td>
                                            </tr>
                                        </table>
                                    </td>
                                <?php } ?>
                            <?php } ?>
                            <?php if (isset($sign_data2->uidnr_admin) && $sign_data2->uidnr_admin == $user) { ?>
                                <?php if ($signature2) { ?>
                                    <td style="width:50%;padding:0;margin:0;vertical-align:top;text-align:right;">
                                        <table style="width:100%;">
                                            <tr>
                                                <td style="text-align: right;width:100%;">For and on behalf of</td>
                                            </tr>
                                            <tr>
                                                <td style="text-align: right;">Geo-Chem Laboratories Pvt. Ltd.</td>
                                            </tr>
                                            <tr>
                                                <td style="text-align:right;"><img src="<?php echo $signature2; ?>" height="100"></td>
                                            </tr>
                                            <tr>
                                                <td style="text-align:right;"> <?php echo $sign_data2->name; ?></td>
                                            </tr>
                                            <tr>
                                                <td style="text-align:right;"> <?php echo $sign_data2->admin_role_name; ?> </td>
                                            </tr>
                                            <tr>
                                                <td style="text-align:right;"> <?php echo "Authorized Signatory"; ?> </td>
                                            </tr>
                                        </table>
                                    </td>
                                <?php } ?>
                            <?php } ?>
                        <?php } ?>
                    </tr>
                </table>

                </div>
                <?php if ($test_data) { ?>
                    <div class="sample_image1 noheader" style="page-break-inside: auto !important;font-size:10px;">
                        <h4 style="text-align: center;">SUMMARY OF TEST RESULT</h4>
                        <table border="1" style="font-size:10px;width: 100%;border-collapse:collapse;text-align:center;page-break-inside: auto !important;" class="sample_image1 noheader">
                            <thead>
                                <tr>
                                    <th>S.No.</th>
                                    <th>Test Name</th>
                                    <?php if ($report_data->test_total != $test_component->total) { ?>
                                        <th>Tested Component</th>
                                    <?php   } ?>
                                    <th>CONCLUSION</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 1;
                                foreach ($test_data as $td) {  ?>
                                    <tr>
                                        <td><?php echo $i; ?></td>
                                        <td><?php if (!empty($td->test_display_name)) { ?>
                                                <?php echo $td->test_display_name . ' ( ' . $td->test_method . ' ) '; ?>
                                            <?php  } else { ?>
                                                <?php echo $td->test_name . ' ( ' . $td->test_method . ' ) '; ?>
                                            <?php } ?>
                                        </td>
                                        <?php if ($report_data->test_total != $test_component->total) { ?>
                                            <td><?php echo $td->test_component; ?></td>
                                        <?php } ?>
                                        <td><?php if ($td->test_result == 'Pass') { ?>
                                                <?php echo 'P'; ?>
                                            <?php  } else if ($td->test_result == 'Fail') { ?>
                                                <?php echo 'F'; ?>
                                            <?php } else { ?>
                                                <?php echo $td->test_result; ?>
                                            <?php } ?></td>
                                    </tr>
                                <?php $i++;
                                } ?>
                            </tbody>

                        </table>
                    </div>
                <?php } ?>
                <br>
                <?php if ($report_data->part == 'yes') { ?>
                    <?php echo html_entity_decode(base64_decode($report_data->part_details)); ?>
                <?php } ?>
                <br>
                <div>P = Pass F= Fail</div>
                <div> <?php echo trim(html_entity_decode($report_data->remark)); ?></div>
                <?php $checkUser = $this->session->userdata('user_data'); ?>
                <!--            <p style="line-height: 0;padding:0;margin:0;">For and on behalf of</p>
            <p style="line-height: 0;padding:0;margin:0;"> Geo-Chem Laboratories Pvt. Ltd.</p>-->

                <?php if ($record_finding_data != '') { ?>
                    <div class="sample_image1 noheader" style="page-break-inside: auto !important;font-size:10px;">
                        <?php foreach ($record_finding_data as $key => $Data) { ?>

                            <h3 style="text-align:left;font-weight:bold;margin-right:20px;">
                                <?php echo $Data['sequence_no']; ?>. <?php echo $Data['test_display_name']; ?>
                                <br />
                                <?php echo $Data['test_display_method']; ?>
                            </h3>

                            <!-- Added by CHANDAN --20-05-2022 -->
                            <?php if (!empty($Data['parameters_heading']) && !empty($Data['parameters_body'])) { ?>
                                <?php
                                $isClouse = $isLimitation = false;
                                foreach ($Data['parameters_body'] as $keyx => $valx) {
                                    if (!$isClouse && isset($valx->clouse) && !empty($valx->clouse)) {
                                        $isClouse = true;
                                    }
                                    if (!$isLimitation && isset($valx->limitation) && !empty($valx->limitation)) {
                                        $isLimitation = true;
                                    }
                                }
                                ?>
                                <table border="1" style="width: 100%; border-collapse: collapse;" cellpadding="5">
                                    <thead>
                                        <tr>
                                            <?php if ($isClouse) { ?>
                                                <th align="left"><?php echo $Data['parameters_heading']->clouse; ?></th>
                                            <?php } ?>
                                            <th align="left"><?php echo $Data['parameters_heading']->parameter_name; ?></th>
                                            <?php if ($isLimitation) { ?>
                                                <th><?php echo $Data['parameters_heading']->limitation; ?></th>
                                            <?php } ?>
                                            <th><?php echo $Data['parameters_heading']->requirement; ?></th>
                                            <?php if (!empty($Data['parameters_heading']->result_1)) { ?>
                                                <th><?php echo $Data['parameters_heading']->result_1; ?></th>
                                            <?php } ?>
                                            <?php if (!empty($Data['parameters_heading']->result_2)) { ?>
                                                <th><?php echo $Data['parameters_heading']->result_2; ?></th>
                                            <?php } ?>
                                            <?php if (!empty($Data['parameters_heading']->result_3)) { ?>
                                                <th><?php echo $Data['parameters_heading']->result_3; ?></th>
                                            <?php } ?>
                                            <?php if (!empty($Data['parameters_heading']->result_4)) { ?>
                                                <th><?php echo $Data['parameters_heading']->result_4; ?></th>
                                            <?php } ?>
                                            <?php if (!empty($Data['parameters_heading']->result_5)) { ?>
                                                <th><?php echo $Data['parameters_heading']->result_5; ?></th>
                                            <?php } ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (count($Data['dist_para_cat']) == 0) { ?>
                                            <?php foreach ($Data['parameters_body'] as $key2 => $val2) { ?>
                                                <?php if (!empty($val2->result_1) || !empty($val2->result_2) || !empty($val2->result_3) || !empty($val2->result_4) || !empty($val2->result_5)) { ?>
                                                    <tr>
                                                        <?php if ($isClouse) { ?>
                                                            <td><?php echo $val2->clouse; ?></td>
                                                        <?php } ?>
                                                        <td><?php echo $val2->parameter_name; ?></td>
                                                        <?php if ($isLimitation) { ?>
                                                            <td align="center"><?php echo $val2->limitation; ?></td>
                                                        <?php } ?>
                                                        <td align="center"><?php echo $val2->requirement; ?></td>
                                                        <?php if (!empty($Data['parameters_heading']->result_1)) { ?>
                                                            <td align="center"><?php echo $val2->result_1; ?></td>
                                                        <?php } ?>
                                                        <?php if (!empty($Data['parameters_heading']->result_2)) { ?>
                                                            <td align="center"><?php echo $val2->result_2; ?></td>
                                                        <?php } ?>
                                                        <?php if (!empty($Data['parameters_heading']->result_3)) { ?>
                                                            <td align="center"><?php echo $val2->result_3; ?></td>
                                                        <?php } ?>
                                                        <?php if (!empty($Data['parameters_heading']->result_4)) { ?>
                                                            <td align="center"><?php echo $val2->result_4; ?></td>
                                                        <?php } ?>
                                                        <?php if (!empty($Data['parameters_heading']->result_5)) { ?>
                                                            <td align="center"><?php echo $val2->result_5; ?></td>
                                                        <?php } ?>
                                                    </tr>
                                                <?php } ?>
                                            <?php } ?>
                                        <?php } else { ?>
                                            <?php foreach ($Data['dist_para_cat'] as $category_row) { ?>
                                                <?php
                                                $parameterBody = getParameterForCateogry($Data['record_finding_id'], $category_row);
                                                $dy_colspan = ($isLimitation) ? 4 : 3;
                                                if (!empty($Data['parameters_heading']->result_2)) {
                                                    $dy_colspan = ($isLimitation) ? 5 : 4;
                                                }
                                                if (!empty($Data['parameters_heading']->result_3)) {
                                                    $dy_colspan = ($isLimitation) ? 6 : 5;
                                                }
                                                if (!empty($Data['parameters_heading']->result_4)) {
                                                    $dy_colspan = ($isLimitation) ? 7 : 6;
                                                }
                                                if (!empty($Data['parameters_heading']->result_5)) {
                                                    $dy_colspan = ($isLimitation) ? 8 : 7;
                                                }
                                                ?>
                                                <tr>
                                                    <?php if ($isClouse) { ?>
                                                        <td style="border-right-style:none;"></td>
                                                        <td style="border-left-style:none;" colspan="<?php echo $dy_colspan; ?>">
                                                        <?php } else { ?>
                                                        <td colspan="<?php echo $dy_colspan; ?>">
                                                        <?php } ?>
                                                        <?php if (!empty($category_row->category)) { ?>
                                                            <?php echo $category_row->category; ?>
                                                        <?php } else { ?>
                                                            &nbsp;
                                                        <?php } ?>
                                                        </td>
                                                <tr>
                                                    <?php foreach ($parameterBody as $key2 => $val2) { ?>
                                                        <?php if (!empty($val2->result_1) || !empty($val2->result_2) || !empty($val2->result_3) || !empty($val2->result_4) || !empty($val2->result_5)) { ?>
                                                <tr>
                                                    <?php if ($isClouse) { ?>
                                                        <td><?php echo $val2->clouse; ?></td>
                                                    <?php } ?>
                                                    <td><?php echo $val2->parameter_name; ?></td>
                                                    <?php if ($isLimitation) { ?>
                                                        <td align="center"><?php echo $val2->limitation; ?></td>
                                                    <?php } ?>
                                                    <td align="center"><?php echo $val2->requirement; ?></td>
                                                    <?php if (!empty($Data['parameters_heading']->result_1)) { ?>
                                                        <td align="center"><?php echo $val2->result_1; ?></td>
                                                    <?php } ?>
                                                    <?php if (!empty($Data['parameters_heading']->result_2)) { ?>
                                                        <td align="center"><?php echo $val2->result_2; ?></td>
                                                    <?php } ?>
                                                    <?php if (!empty($Data['parameters_heading']->result_3)) { ?>
                                                        <td align="center"><?php echo $val2->result_3; ?></td>
                                                    <?php } ?>
                                                    <?php if (!empty($Data['parameters_heading']->result_4)) { ?>
                                                        <td align="center"><?php echo $val2->result_4; ?></td>
                                                    <?php } ?>
                                                    <?php if (!empty($Data['parameters_heading']->result_5)) { ?>
                                                        <td align="center"><?php echo $val2->result_5; ?></td>
                                                    <?php } ?>
                                                </tr>
                                            <?php } ?>
                                        <?php } ?>
                                    <?php } ?>
                                <?php } ?>
                                    </tbody>
                                </table>
                                <br />
                                <?php if (!empty($Data['parameters_heading']->notes)) { ?>
                                    <table style="width: 100% !important;border-collapse: collapse;margin:0;padding:0;">
                                        <tr>
                                            <td style="width: 100%;text-align: justify!important;">
                                                <?php echo html_entity_decode(base64_decode($Data['parameters_heading']->notes)); ?>
                                            </td>
                                        </tr>
                                    </table>
                                    <br />
                                <?php } ?>
                            <?php } ?>
                            <!-- End -->

                            <?php if (!empty($Data['test_type'])) { ?>

                                <?php if ($Data['nabl_headings'] && !empty($Data['nabl_headings'])) { ?>
                                    <table border="1" style="width: 100%;border-collapse: collapse;text-align:center;font-size:10px;">
                                        <?php foreach ($Data['nabl_headings'] as $key1 => $table_type) { ?>
                                            <?php if ($key1 == 'heading') { ?>
                                                <thead>
                                                    <?php foreach ($table_type as $key2 => $row) { ?>
                                                        <tr>
                                                            <?php foreach ($row as $key3 => $coloum) { ?>
                                                                <th>
                                                                    <?php echo $coloum; ?>
                                                                </th>
                                                            <?php } ?>
                                                        </tr>
                                                    <?php  } ?>
                                                </thead>
                                            <?php } ?>
                                            <?php if ($key1 == 'body') { ?>
                                                <tbody>
                                                    <?php foreach ($table_type as $key2 => $row) { ?>
                                                        <tr>
                                                            <?php foreach ($row as $key3 => $coloum) { ?>
                                                                <td>
                                                                    <?php echo $coloum; ?>
                                                                </td>
                                                            <?php } ?>
                                                        </tr>
                                                    <?php  } ?>
                                                </tbody>
                                            <?php  } ?>
                                        <?php } ?>
                                    </table>
                                <?php } ?>

                                <?php if ($Data['non_nabl_headings'] && !empty($Data['non_nabl_headings'])) { ?>
                                    <table border="1" style="width: 100%;border-collapse: collapse;text-align:center;font-size:10px;">
                                        <?php foreach ($Data['non_nabl_headings'] as $key1 => $table_type) { ?>
                                            <?php if ($key1 == 'heading') { ?>
                                                <thead>
                                                    <?php foreach ($table_type as $key2 => $row) { ?>
                                                        <tr>
                                                            <?php foreach ($row as $key3 => $coloum) { ?>
                                                                <th>
                                                                    <?php echo $coloum; ?>
                                                                </th>
                                                            <?php } ?>
                                                        </tr>
                                                    <?php  } ?>
                                                </thead>
                                            <?php } ?>
                                            <?php if ($key1 == 'body') { ?>
                                                <tbody>
                                                    <?php foreach ($table_type as $key2 => $row) { ?>
                                                        <tr>
                                                            <?php foreach ($row as $key3 => $coloum) { ?>
                                                                <td>
                                                                    <?php echo $coloum; ?>
                                                                </td>
                                                            <?php } ?>
                                                        </tr>
                                                    <?php  } ?>
                                                </tbody>
                                            <?php  } ?>
                                        <?php } ?>
                                    </table>
                                <?php } ?>
                                <table style="width: 100% !important;border-collapse: collapse;margin:0;padding:0;font-size:10px;">
                                    <?php if ($Data['nabl_detail'] != '' && $Data['nabl_detail'] != NULL) { ?>
                                        <tr>
                                            <td style="width: 100%;" class="detail_table"><?php echo html_entity_decode(base64_decode($Data['nabl_detail'])); ?></td>
                                        </tr>
                                    <?php } ?>
                                    <?php if ($Data['nabl_remark'] != '' && $Data['nabl_remark'] != NULL) { ?>
                                        <tr>
                                            <td style="width: 100%;" class="detail_table"><?php echo html_entity_decode(base64_decode($Data['nabl_remark'])); ?></td>
                                        </tr>
                                    <?php } ?>
                                </table>
                                <table style="width:100% !important;border-collapse: collapse;margin:0;padding:0;font-size:10px;">
                                    <?php if ($Data['non_nabl_detail'] != '' && $Data['non_nabl_detail'] != NULL) { ?>
                                        <tr>
                                            <td style="width: 100%;" class="detail_table"><?php echo html_entity_decode(base64_decode($Data['non_nabl_detail'])); ?></td>
                                        </tr>
                                    <?php } ?>
                                    <?php if ($Data['non_nabl_remark'] != '' && $Data['non_nabl_remark'] != NULL) { ?>
                                        <tr>
                                            <td style="width: 100%;" class="detail_table"><?php echo html_entity_decode(base64_decode($Data['non_nabl_remark'])); ?></td>
                                        </tr>
                                    <?php } ?>
                                </table>
                            <?php } ?>

                            <?php if (isset($Data['images']) && $Data['images']) { ?>
                                <table style="width: 100%;border-collapse:collapse;text-align:center;font-size:10px;">
                                    <tr>
                                        <td style="text-align: center;">
                                            <table style="width:100%;text-align:center!important;" cellspacing="0" cellpadding="8" border="0">
                                                <tr>
                                                    <?php $i = 1;
                                                    foreach ($Data['images'] as $key => $value) { ?>

                                                        <td style="text-align: center;">
                                                            <img src="<?php echo $value['image_path']; ?>" style="border:1px solid #333;width:200px;height: 200px;">
                                                        </td>

                                                        <?php if ($i % 3 == 0) { ?>
                                                </tr>
                                                <tr>
                                                <?php } ?>
                                                <?php $i++; ?>
                                            <?php } ?>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                </table>
                            <?php } ?>
                        <?php } ?>
                    </div>
                <?php } ?>

                <!-- </div> -->

</body>

</html>