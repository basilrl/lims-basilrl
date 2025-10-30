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
    <title>Document</title>
</head>
<style>
    @page {
        /* Changed by saurabh on 20-08-2021 */
        <?php if (isset($template_type) && $template_type == 'preview') { ?>background: url('https://basilrl-prod.s3.ap-south-1.amazonaws.com/imgpsh_fullsize_anim.jpeg') no-repeat 0 0;
        */ <?php } else { ?>background: url('https://basilrl-prod.s3.ap-south-1.amazonaws.com/imgpsh_fullsize_anim.jpeg') no-repeat 0 0;
        <?php } ?>background-repeat: no-repeat;
        width: 100%;
        opacity: 0.3;
        background-size: cover !important;
        margin-top: 5.5cm !important;
        margin-bottom: 2cm !important;
        background-position: top center;
        header: html_myHeader;
        footer: html_myFooter;

    }


    * {
        font-size: 10px;
        font-family: Cambria !important;
    }

    /* .nabl_non_nabl *{
        margin: 0 auto!important;
        font-family: Cambria !important;
    } */
    .nabl_non_nabl table {
        width: 100% !important;
        margin: 0 auto !important;
        text-align: center;
        font-family: cambria !important;
    }

    .nabl_non_nabl tr td p span span {
        font-family: cambria !important;

    }

    .nabl_non_nabl .MsoTableGrid * {
        font-family: cambria !important;

    }

    .nabl_non_nabl span {
        font-family: cambria !important;
    }

    span {
        font-family: cambria !important;
    }

    .footer td {
        opacity: 0.4;
    }

    div.noheader {
        page-break-before: always;
        /* page: noheader; */
    }

    .sample_image1 {
        page-break-inside: auto !important;
    }

    table tr td {
        text-transform: uppercase;

    }

    table tr th {
        text-transform: uppercase;

    }
</style>

<body>

    <div class="bg_image">
        <htmlpageheader name="myHeader">
            <table style="margin-top:-20px;width:100%;text-transform:uppercase;">
                <tr>
                    <td style="text-align:left;"><img src="https://cpslims-prod.s3.ap-south-1.amazonaws.com/format/logo-login.png" alt="" style="text-align: left;">
                        <br />
                        <?php if ($bothUserSign) { ?>
                            <?php if (isset($qrcode) && ($report_data->primary_approver_status == 2) && ($report_data->secondary_approver_status == 2)) { ?>
                                <img src="<?php echo $qrcode; ?>" style="text-align:left;margin-top:23px;">
                            <?php } ?>
                        <?php } else { ?>
                            <?php if (isset($qrcode)) { ?>
                                <img src="<?php echo $qrcode; ?>" style="text-align:left;margin-top:23px;">
                            <?php } ?>
                        <?php } ?>
                    </td>
                    <td style="text-align: center;font-size:24px;font-weight:600;text-decoration:underline;font-weight:bold;font-family:Arial;">
                        TEST REPORT
                    </td>
                    <td style="text-align: right;"><img src="https://cpslims-prod.s3.ap-south-1.amazonaws.com/format/dubai_logo.png" alt="" style="text-align: right;width:200px;">
                    </td>
                    <td>
                        <?php if (isset($qrcode)) { ?>
                            <img src="<?php echo $qrcode; ?>" alt="">
                        <?php } ?>
                    </td>
                </tr>
            </table>
            <table style="width:100%;font-family:calibri;border-bottom:1px solid black;text-transform:uppercase;">
                <tr>
                    <td style="text-align: left;width:75%;">Test Report No.: <b> <?php echo $report_data->report_num; ?></b></td>
                    <td style="text-align:right!important;width:25%;">Dated: <b> <?php echo date("d M Y", strtotime($report_data->issuance_date)); ?></b></td>
                </tr>
            </table>
        </htmlpageheader>

        <htmlpagefooter name="myFooter">
            <hr>
            <table style="width: 100%;text-align:center;font-size:9px;font-family:calibri;text-transform:uppercase;">
                <tr>
                    <td colspan="4"><b> GEO-CHEM MIDDLE EAST</b></td>
                </tr>
                <tr>
                    <td colspan="4">Plot No. 18-0, Dubai Investment Park II, Affection Plan # 597-22, EBC Warehouse # 6. Dubai. UAE. <br>
                        E-mail :<a href="cps.dubai@basilrl.com"> cps.dubai@basilrl.com </a> <br>Web : <a href="https://basilrl.com"> https://basilrl.com</a>
                    </td>
                </tr>
                <tr class="footer">
                    <td style="opacity:0.3;"><b> GC-DXB/REC-56-D</b></td>
                    <td style="opacity:0.3;"><b> Rev -00,</b></td>
                    <td style="opacity:0.3;"><b> 02-02-2021</b></td>
                    <td style="opacity:0.3;"><b> Pages {PAGENO} of {nb}</b></td>
                </tr>
            </table>
        </htmlpagefooter>

        <?php if ($image_sample) { ?>
            <div class="" style="text-transform:uppercase;">
                <h3 style="text-align: center;">SUBMITTED SAMPLE</h3>
                <?php foreach ($image_sample as $key9 => $sample_photo) {  ?>
                    <table style="width:100%;border-collapse:collapse;cellpadding:2px;">
                        <tr>
                            <td style="text-align: center;font-weight: bold;"><?php echo $sample_photo->comment; ?></td>
                        </tr>
                        <tr>
                            <td style="text-align: center;"><img src="<?php echo $sample_photo->image_file_path; ?>" style="width:70%;text-align: center;" alt="" srcset=""></td>
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
                <table style="text-transform:uppercase;font-size:10px;;width:90%;margin:0 auto;text-align:left; ">
                    <?php if (!empty($report_data->customer_name)) { ?>
                        <tr>
                            <th style="text-align: left;">Applicant</th>
                            <th style="text-align: left;">:</th>
                            <th style="text-align: left;"><?php echo $report_data->customer_name; ?></th>
                        </tr>
                    <?php } ?>
                    <?php if (!empty($report_data->contact_name)) { ?>
                        <tr>
                            <th style="text-align: left;">Contact Person</th>
                            <th style="text-align: left;">:</th>
                            <th style="text-align: left;"><?php echo $report_data->contact_name; ?></th>
                        </tr>
                    <?php } ?>
                    <?php if (!empty($report_data->address)) { ?>
                        <tr>
                            <th style="text-align: left;">Address</th>
                            <th style="text-align: left;">:</th>
                            <th style="text-align: left;"><?php echo $report_data->address; ?></th>
                        </tr>
                    <?php } ?>
                    <?php if (!empty($report_data->customer_name)) { ?>
                        <tr>
                            <th style="text-align: left;">Contact Details</th>
                            <th style="text-align: left;">:</th>
                            <th style="text-align: left;"><?php echo $report_data->customer_name; ?></th>
                        </tr><?php } ?>
                    <?php if (!empty($report_data->sample_desc)) { ?>
                        <tr>
                            <th style="text-align: left;">Sample Description </th>
                            <th style="text-align: left;">:</th>
                            <th style="text-align: left;"><?php echo $report_data->sample_desc; ?></th>
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

                    <?php if (count($application_data) > 1) { ?>
                        <?php $images = array(); ?>
                        <tr>
                            <td>Application Care Instruction</td>
                            <td>:</td>
                            <td>
                                <?php foreach ($application_data as $key3 => $app) { ?>
                                    <?php if ($app['instruction_name'] != '') { ?>
                                        <img src="<?php echo $app['instruction_image']; ?>" alt="">
                                    <?php } ?>
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
                    <?php } else if (count($application_data) == 1) { ?>
                        <?php $images = array(); ?>
                        <tr>
                            <td>Application Care Instruction</td>
                            <td>:</td>
                            <td>
                                <?php foreach ($application_data as $key3 => $app) {
                                    if ($app['instruction_name'] != '') { ?>
                                        <img src="<?php echo $app['instruction_image']; ?>" alt="">

                                <?php $description[] = $app['instruction_name'];
                                    }
                                } ?>
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
                </div>
                <?php if ($test_data) { ?>
                    <div style="border-top: 1px dashed black ;margin-bottom:2px;"></div>
                    <div style="border-top: 1px dashed black ;"></div>

                    <table border="1" style="text-transform:uppercase;width: 100%;border-collapse:collapse;text-align:center;font-size:10px;margin-top:10px;">

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
                                            <?php echo $td->test_display_name; ?>
                                        <?php  } else { ?>
                                            <?php echo $td->test_name; ?>
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
                    <div style="border-bottom:double;margin-top:10px;"></div>
                <?php } ?>
                <br>
                <?php if ($report_data->part == 'yes') { ?>
                    <?php echo html_entity_decode(base64_decode($report_data->part_details)); ?>
                <?php } ?> <br>
                <table style="width:95%;border-collapse:collapse;text-align:left;font-size:10px;margin:0 auto;">

                    <tr>
                        <td style="width: 12%;">NOTE:</td>
                        <td> M = Meet buyer’s requirement, </td>
                        <td> F = Does Not Meet buyer’s requirement</td>
                    </tr>
                    <tr>
                        <td style="width: 12%;"></td>
                        <td>*= Not Provided, </td>
                        <td>NA = Not Applicable</td>
                    </tr>

                </table>
                <!-- <div style="padding:5px;font-size:10px;;">REMARKS : <?php //echo trim(html_entity_decode($report_data->remark)); 
                                                                            ?></div> -->
                <?php $checkUser = $this->session->userdata('user_data'); ?>

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

                <p style="font-size: 10px;">Test result is drawn according to the kind and extent tests performed. Without permission of the tests center this report is not permitted to be duplicated in extracts. This report does not entitle to carry any safety mark
                    on this or similar products. This test report represents the test parameters as requested by the customer
                    based on submitted sample only.</p>

                <?php if ($record_finding_data != '') { ?>
                    <div class="sample_image1 noheader" style="page-break-inside: auto !important;font-size:10px;text-transform:uppercase;">
                        <?php foreach ($record_finding_data as $key => $Data) { ?>
                            <table style="text-transform:uppercase;width:100%;border-collapse:collapse;font-size:10px;background-color:#bebebe;margin:0 auto;" border="1">
                                <tr>
                                    <td style="text-align:left;padding:5px;">
                                        <?php echo $Data['sequence_no']; ?>. <?php echo $Data['test_display_name']; ?>
                                        <br>
                                        <?php echo $Data['test_display_method']; ?>
                                    </td>
                                </tr>
                            </table>

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

                                    <table border="1" style="text-transform:uppercase;width:100%;border-collapse: collapse;text-align:center;font-size:10px;;padding:0;margin:0 auto;">
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
                                    <table border="1" style="width:100%;border-collapse: collapse;text-align:center;font-size:10px;margin:0 auto;">
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
                                <div class="nabl_non_nabl" style="font-family:cambria;width:100%;margin:0 auto;text-align:center;font-size:10px;padding:0;">

                                    <?php if ($Data['nabl_detail'] != '' && $Data['nabl_detail'] != NULL) { ?>
                                        <?php echo html_entity_decode(base64_decode($Data['nabl_detail'])); ?>
                                    <?php } ?>

                                    <?php if ($Data['nabl_remark'] != '' && $Data['nabl_remark'] != NULL) { ?>
                                        <?php echo html_entity_decode(base64_decode($Data['nabl_remark'])); ?>
                                    <?php } ?>

                                    <?php if ($Data['non_nabl_detail'] != '' && $Data['non_nabl_detail'] != NULL) { ?>
                                        <?php echo html_entity_decode(base64_decode($Data['non_nabl_detail'])); ?>
                                    <?php } ?>
                                    <?php if ($Data['non_nabl_remark'] != '' && $Data['non_nabl_remark'] != NULL) { ?>
                                        <?php echo html_entity_decode(base64_decode($Data['non_nabl_remark'])); ?>
                                    <?php } ?>
                                </div>

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
                <?php if ($reference_sample) { ?>
                    <div class="sample_image noheader" style="page-break-inside: auto !important;">

                        <h4 style="text-align:center;"> REFERENCE IMAGES</h4>
                        <?php foreach ($reference_sample as $key9 => $reference_photo) {  ?>
                            <table style="width:100%;border-collapse:collapse;cellpadding:2px;">
                                <tr>
                                    <td style="text-align: center;"><img src="<?php echo $reference_photo->image_file_path; ?>" style="border:1px solid #333;width:300px;" alt="" srcset=""></td>
                                </tr>
                            </table>
                        <?php } ?>
                    </div>
                <?php } ?>

                <p style="text-align: center;text-transform:uppercase;"> # End of the Test Report #</p>
            </div>

</body>

</html>