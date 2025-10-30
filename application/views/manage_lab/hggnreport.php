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
    <title>HGGN REPORT</title>
</head>
<!-- https://cpslims-prod.s3.ap-south-1.amazonaws.com/hgbbjhjjj.jpg -->
<style>
    @page {
        background: url('https://basilrl-prod.s3.ap-south-1.amazonaws.com/imgpsh_fullsize_anim.jpeg') no-repeat 0 0;  
        background-repeat: no-repeat;
        width: 100%;
        opacity: 0.3;
        margin-top: 3.3cm !important;
        margin-bottom: 1cm !important;
        background-position: top center;
        header: html_myHeader2;
        footer: html_myFooter;
    }

    @page :first {
        header: html_myHeader1;
    }

    div.chapter2 {
        page-break-before: always;
        /* page: chapter2; */
    }

    div.noheader {
        page-break-before: always;
        /* page: noheader; */
    }

    .nabl,
    .nonnabl {
        page-break-inside: auto !important;
    }

    * {
        font-size: 12px;
    }

    .sample_image {
        page-break-inside: auto !important;
    }

    .detail_table table {
        width: 100% !important;
        /* text-align: center; */
        border: 1px solid black;
    }

    .detail_table table tbody {
        text-align: center;
    }

    .detail_table * {
        width: 100% !important;
        /* text-align: center; */
    }

    body {
        font-family: "centurygothic" !important;
        font-size: 14px !important;;
        text-align: justify !important;
        color: #000!important;
    }
</style>

<body>
    <div class="bg_image">
        <htmlpageheader name="myHeader1">
            <table width="100%" style="text-align: left; ">
                <tr>
                    <td width="30%" style="vertical-align: top;">
                        <img src="https://cpslims-prod.s3.ap-south-1.amazonaws.com/format/NABL-ILAC-MRA-Symbol.png" alt="" width="15%" style="text-align:left;">
                    </td>
                    <td width="30%" align="center" style="vertical-align: top;"><img width="180px" src="https://basilrl-prod.s3.ap-south-1.amazonaws.com/stationary/logo-basil-pdf.png"></td>
                    <td align="left" width="35%" style="vertical-align: top;">
                        <strong style="margin-top:-50px">Basil Quality Testing Lab Pvt Ltd.</strong><br />
                        642 Avanta Business Centre 6th Floor Park Centra <br />
                        Sector 30 Gurgaon ,Pincode- 122001, India.<br />
                        Tel. : +91 9289723427<br />
                        Email : info@basilrl.com<br />
                    </td>
                </tr>
                <tr>
                    <td style="padding-bottom:30px; vertical-align: top;">
                        <?php if ($bothUserSign) { ?>
                            <?php if (isset($qrcode) && ($report_data->primary_approver_status == 2) && ($report_data->secondary_approver_status == 2)) { ?>
                                <img src="<?php echo $qrcode; ?>" style="float:left">
                            <?php } ?>
                        <?php } else { ?>
                            <?php if (isset($qrcode)) { ?>
                                <img src="<?php echo $qrcode; ?>" style="float:left">
                            <?php } ?>
                        <?php } ?>
                    </td>
                    <td align="center" style="padding-bottom:30px; vertical-align: top;">
                        <strong>International<br />
                            Independent Inspection<br />
                            &<br />
                            Testing Company</strong>
                    </td>
                    <td style="padding-bottom:30px; vertical-align: top;">&nbsp;</td>
                </tr>
            </table>
        </htmlpageheader>

        <htmlpageheader name="myHeader2" style="margin-bottom:0; padding-bottom:0">
            <table style="margin-top:-23px;" width="100%">
                <tr>
                    <td align="right">
                        <img src="https://basilrl-prod.s3.ap-south-1.amazonaws.com/stationary/logo-basil-pdf.png" height="30">
                    </td>
                </tr>
            </table>
            <table style="width:100%; margin-top:20px; margin-bottom:0px">
                <tr>
                    <th width="50%" align="left">REPORT NO.: <?php echo $report_data->report_num; ?></th>
                    <th width="50%" align="right">Dated: <?php echo date("d M Y", strtotime($report_data->issuance_date)); ?></th>
                </tr>
            </table>
        </htmlpageheader>

        <htmlpagefooter name="myFooter">
            <table style="width: 100%;margin-right:-30px;margin-top:30px;">
                <tr>
                    <td style="text-align:center; font-size:11px">Reg. off : Geo-Chem House, 294, Shahid Bhagat Singh Road, Fort, Mumbai 400001, India
                        <br>
                        www.basilrl.com
                    </td>
                </tr>
                <tr>
                    <td style="text-align:right;"> Pages {PAGENO} of {nb}</td>
                </tr>
            </table>
        </htmlpagefooter>

        <h3 style="text-align: center;">TEST REPORT</h3>

        <table style="width: 100%;font-size:14px;">
            <tr>
                <th style="text-align: left;" colspan="2">ULR No: <?php echo $report_data->ulr_no;?></th>
                <?php $report_data->ulr_no_flag; ?>
            </tr>
        </table>
        <table style="font-size:14px;width:100%;">
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
            <tr>
                <th style="font-style:italic;text-align:left;" colspan="3">Sample not drawn by Geo-Chem Laboratories Pvt. Ltd.</th>
            </tr>
            <?php if (!empty($report_data->sample_desc)) { ?>
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
        <?php if ($report_data->part == 'yes') { ?>
            <?php echo html_entity_decode(base64_decode($report_data->part_details)); ?>
        <?php } ?> <br>
        <?php if ($test_data) { ?>
            <table border="1" style="width: 100%;border-collapse:collapse;text-align:center;margin-top:10px;font-size:14px;">
                <thead>
                    <tr>
                        <th>S.No.</th>
                        <th style="text-align: left;">Test Name</th>
                        <?php if ($report_data->test_total != $test_component->total) { ?>
                            <th>Tested Component</th>
                        <?php   } ?>
                        <th>Results</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1;
                    foreach ($test_data as $td) {  ?>
                        <tr>
                            <td><?php echo $i; ?></td>
                            <td style="text-align: left;"><?php if (!empty($td->test_display_name)) { ?>
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
        <?php } ?>
        <div>P = Pass F= Fail</div>
        <?php if ($report_data->remark) { ?>
            <div style="font-style: italic;">Remark: <br> <?php echo trim(html_entity_decode($report_data->remark)); ?></div>
        <?php } ?>
        <?php $checkUser = $this->session->userdata('user_data'); ?>
        <br />
        <table style="width:100%;padding:0;margin:0;font-size:14px;">
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
                                <td style="text-align:left;"><img src="<?php echo $signature1; ?>" height="50"></td>
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
                                <td style="text-align:right;"><img src="<?php echo $signature2; ?>" height="50"></td>
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

        <?php if ($record_finding_data != '') { ?>
            <div class="nabl logo chapter2" style="page-break-inside: auto !important;border-bottom:1px solid lightgray;">
                <?php foreach ($record_finding_data as $key => $Data) { ?>
                    <?php if($Data['result_entry'] == 1){?>
                   
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
                        <!-- Parameters imported table -->
                        <table border="1" style="width: 100%; border-collapse: collapse;" cellpadding="5">
                        <tr>
                            <td style="border: 0;" colspan="10">
                            <h3>
                                <?php echo $Data['sequence_no']; ?>. <?php echo $Data['test_display_name']; ?>
                                <br />
                                <?php echo $Data['test_display_method']; ?>
                            </h3>
                            </td>
                        </tr>

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
                                        </tr>
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
                        <!-- Parameters imported table -->
                        <br />
                        <?php if (!empty($Data['parameters_heading']->notes)) { ?>
                            <table style="width: 100% !important;border-collapse: collapse;margin:0;padding:0;">
                                <tr>
                                    <td style="width: 100%;text-align: justify!important;">
                                        <?php echo html_entity_decode(base64_decode($Data['parameters_heading']->notes)); ?>
                                    </td>
                                </tr>
                            </table>
                        <?php } ?>
                    <?php } ?>
                    <?php } ?>

                    <?php if($Data['result_entry'] == 2){?>
                    <?php if (!empty($Data['test_type'])) { ?>

                        <!-- NABL Dynamic table data start here -->
                        <?php if ($Data['nabl_headings'] && !empty($Data['nabl_headings'])) { ?>
                            <table border="1" style="width: 100%;border-collapse: collapse;text-align:center;">
                            <tr>
                                <td style="border: 0;" colspan="10">
                                <h3>
                                    <?php echo $Data['sequence_no']; ?>. <?php echo $Data['test_display_name']; ?>
                                    <br />
                                    <?php echo $Data['test_display_method']; ?>
                                </h3>
                                </td>
                            </tr>
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
                        <!-- NABL Dynamic table data end here -->

                        <!-- NON-NABL Dynamic table data start here -->
                        <?php if ($Data['non_nabl_headings'] && !empty($Data['non_nabl_headings'])) { ?>
                            <table border="1" style="width: 100%;border-collapse: collapse;text-align:center;">
                            <tr>
                                <td style="border: 0;" colspan="10">
                                <h3>
                                    <?php echo $Data['sequence_no']; ?>. <?php echo $Data['test_display_name']; ?>
                                    <br />
                                    <?php echo $Data['test_display_method']; ?>
                                </h3>
                                </td>
                            </tr>
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
                        <!-- NON-NABL Dynamic table data end here -->

                        <!-- NABL & NON-NABL details and remarks start here -->
                        <table style="width: 100% !important;border-collapse: collapse;margin:0;padding:0;">
                        
                            <?php if ($Data['nabl_detail'] != '' && $Data['nabl_detail'] != NULL) { ?>
                                <tr>
                                <td style="border: 0;" colspan="10">
                                <h3>
                                    <?php echo $Data['sequence_no']; ?>. <?php echo $Data['test_display_name']; ?>
                                    <br />
                                    <?php echo $Data['test_display_method']; ?>
                                </h3>
                                </td>
                            </tr>
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
                        <table style="width:100% !important;border-collapse: collapse;margin:0;padding:0;">
                            <?php if ($Data['non_nabl_detail'] != '' && $Data['non_nabl_detail'] != NULL) { ?>
                                <tr>
                                <td style="border: 0;" colspan="10">
                                <h3>
                                    <?php echo $Data['sequence_no']; ?>. <?php echo $Data['test_display_name']; ?>
                                    <br />
                                    <?php echo $Data['test_display_method']; ?>
                                </h3>
                                </td>
                            </tr>
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
                         <!-- NABL & NON-NABL details and remarks end here -->
                    <?php } ?>
                    <?php } ?>

                    <?php if (isset($Data['images']) && $Data['images']) { ?>
                        <table style="width: 100%;border-collapse:collapse;text-align:center;font-size:14px;">
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

        <?php if ($image_sample) { ?>
            <div class="sample_image noheader" style="page-break-inside: auto !important;">
                <h4 style="text-align:center;">SAMPLE IMAGES</h4>
                <table style="width: 100%;border-collapse:collapse;text-align:center;font-size:14px;">
                    <tr>
                        <?php $i = 1;
                        foreach ($image_sample as $key9 => $sample_photo) { ?>
                            <td style="text-align: center;">
                                <?php if (!empty($sample_photo->comment)) { ?>
                                    <?php echo $sample_photo->comment; ?>
                                    <br />
                                <?php } ?>
                                <img src="<?php echo $sample_photo->image_file_path; ?>" style="border:1px solid #333;width:350px;height:300px;">
                            </td>
                            <?php if ($i % 2 == 0) { ?>
                    </tr>
                    <tr>
                    <?php } ?>
                    <?php $i++; ?>
                <?php } ?>
                    </tr>
                </table>
            </div>
        <?php } ?>

        <?php if ($reference_sample) { ?>
            <div class="sample_image noheader" style="page-break-inside: auto !important;">
                <h4 style="text-align:center;">REFERENCE IMAGES</h4>
                <table style="width: 100%;border-collapse:collapse;text-align:center;font-size:14px;">
                    <tr>
                        <?php $i = 1;
                        foreach ($reference_sample as $key10 => $reference_photo) { ?>
                            <td style="text-align: center;">
                                <img src="<?php echo $reference_photo->image_file_path; ?>" style="border:1px solid #333;width:350px;height:300px;">
                            </td>
                            <?php if ($i % 2 == 0) { ?>
                    </tr>
                    <tr>
                    <?php } ?>
                    <?php $i++; ?>
                <?php } ?>
                    </tr>
                </table>
            </div>
        <?php } ?>

        <p style="text-align: center;text-decoration:underline;"> End of the Test Report</p>

        <!-- <p style="font-size: 10px;">The test report is based upon and pertains to the sample submitted for testing. This report is made solely on the basis of your instructions and/or information and materials supplied by you. The report is entirely for the samples submitted and does not imply any co-relation with the lot or other material. The results do not extend any warranty and no responsibility is accepted or any liability in-connection with any future loss arising out of this report. Without permission of the tests center, this report is not permitted to be duplicated in extracts. </p>
            <hr> -->

    </div>

</body>

</html>