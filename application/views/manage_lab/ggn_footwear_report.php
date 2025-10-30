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
    <title>GGN FOOTWEAR REPORT</title>
</head>
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

    /* div.chapter1 {
        page-break-before: always;
        page: chapter1;
    } */

    div.chapter2 {
        page-break-before: always;
        /* page: chapter1; */
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
        font-size: 14px;
    }

    .sample_image {
        page-break-inside: auto !important;
    }

    .tnc {
        page-break-inside: auto !important;
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
                        <strong style="margin-top:-50px">GEO-CHEM LABORATORIES PVT. LTD.</strong><br />
                        Plot No. 306, Udyog Vihar, Phase-2<br />
                        Gurugram, Haryana - 122016, India.<br />
                        Tel. : +01246250500<br />
                        Email : qa.cp@basilrl.com<br />
                        CIN : U74220MH1964PTC013022
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
        <table style="width: 100%;">
            <tr>
                <th width="70%" style="text-align: left;">Report No.: <?php echo $report_data->report_num; ?></th>
                <th width="30%" style="text-align: right!important;">Dated: <?php echo date("d M Y", strtotime($report_data->issuance_date)); ?></th>
            </tr>
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
                                if ($value1 != '') { ?>
                                    <?php if ($key1 == 1) { ?>
                                        <td>:</td>
                                    <?php } ?>
                                    <td>
                                        <?php echo $value1; ?>
                                    </td>
                            <?php  }
                            } ?>
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

                            <?php $description[] = $app['instruction_name'];
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
        <!-- Changed on 21-06-2021 by saurabh, they want it before test result -->
        <?php if ($report_data->part == 'yes') { ?>
            <h3>Testing Component List</h3>
            <?php echo html_entity_decode(base64_decode($report_data->part_details)); ?>
        <?php } ?>
        <!-- Changed on 21-06-2021 by saurabh, they want it before test result -->
        <!-- Changed on 21-06-2021 by saurabh, if part is availble then it will come -->
        <?php if ($report_data->part == 'yes') { ?>
            <h3>Result Conclusion</h3>
        <?php } ?>
        <!-- Changed on 21-06-2021 by saurabh, if part is availble then it will come -->
        <?php if ($test_data) { ?>
            <table border="1" style="width: 100%;border-collapse:collapse;margin-top:10px;">
                <thead>
                    <tr>
                        <th>S.No.</th>
                        <th>Test Name</th>
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
        <?php } ?>


        <div>P = Pass F= Fail</div>
        <div> <?php echo trim(html_entity_decode($report_data->remark)); ?></div>
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
        <table style="width: 100%;padding:0;margin:0; font-size:14px">
            <tr>
                <td>Test result is drawn according to the kind and extent tests performed.</td>
            </tr>
            <tr>
                <td>Without permission of the tests center, this report is not permitted to be duplicated in extracts. This report does not entitle to carry any safety mark on this or similar products. This test report represents the test parameters as requested by the customer based on submitted sample only.</td>
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

        <p style="text-align: center;"> ***************************************<b> End of the Test Report</b> ************************************</p>

        <p style="font-size:14px;text-align: justify!important;">The test report is based upon and pertains to the sample submitted for testing. This report is made solely on the basis of your instructions and/or information and materials supplied by you. The report is entirely for the samples submitted and does not imply any co-relation with the lot or other material. The results do not extend any warranty and no responsibility is accepted or any liability in-connection with any future loss arising out of this report. Without permission of the tests center, this report is not permitted to be duplicated in extracts. </p>
        <hr>

        <div class="noheader chapter2" style="page-break-inside: auto !important; font-size: 5px;text-align: justify!important;">
            <h4 style="text-align:center;"> TERMS AND CONDITION</h4>
            <p>Geo-Chem Laboratories Pvt. Ltd. Undertakes to provide services (“Work(s)”) to its Customer Subject to the terms and conditions (“Terms”) contained herein. The term of limitation of liability contained herein has been conspicuously marked to
                draw to the attention of the customer. The customer is advised to take separate legal advice and is fully aware of the meaning and the legal significance of this term. The Customer agrees that this term is integral part of this Agreement.
            </p>
            <b>1. INTERPRETATION</b>
            <p>1.1 In this Agreement the following words and phrases shall have the following meanings unless the context otherwise requires:</p>
            <p>(a) Agreement means this agreement enter into between Geo Chem and the Client; <br>
                (b) Charges shall have the meaning given in Clause6.1; <br>
                (c) Confidential Information mean shall information in what every form or manner presented <br>
                which:(a) is disclosed pursue in to or in the course of the provision of Services pursuant to, this Agreement; and (b) (i) is disclosed in writing, electronically, visually, or all other wise howsoever and is marked, stamped or identified by
                any means as confidential by the disclosing party at the time of such disclosure; and/or (ii) is information, howsoever disclosed, which would – reasonably be considered to be confidential by the receiving party.
            </p>
            <p>(d) Intellectual Property Right(s) means copyrights, trademarks (registered or unregistered), patents, patent applications (including the right to apply for a patent), service marks, design rights (register end-run registered), trade secrets
                and other like rights howsoever existing.</p>
            <p>(e) Report(s) shall have the meaning as set out in Clause 5.3below;</p>
            <p>(f) Services means these revises out in any relevant Geo Chem Proposal, any relevant Client purchase order ,or any relevant Geo Chem invoice, as applicable, and may comprise or include the provision by Geo Chem of a Report;</p>
            <p>(g) Proposal means the proposal, estimate or fee quote, if applicable, provided to the Client by Geo Chem relating to the Services; <br>
                1.2 The headings in this Agreement do not affect its interpretation. <br>
            </p>
            <p>
                <b>2. COPYRIGHT COMPLIANCE OR INTELLECTUAL PROPERTY RIGHTS AND DATA PROTECTION</b><br>
                2.1 All Intellectual Property Rights belonging to a party prior to entry into this Agreement shall remain vested in that party. Nothing in this Agreement is intended to transfer any Intellectual Property Rights from either party to the other. <br>
                2.2 Any use by the Client (or the Client's affiliated companies or subsidiaries) of the name "Geo Chem" or any of Geo Chem's trademarks or brand names for any reason must be prior approved in writing by Geo Chem. Any other use of
                Geo Chem‘s trademarks or brand names is strictly prohibited and Geo Chem reserves the right to terminate this Agreement immediately as a result of any such unauthorized use.<br>
                2.3 In the event of provision of certification services, Client agrees and acknowledges that the use of certification marks may be subject to national and international laws and regulations. <br>
                2.4 All Intellectual Property Rights in any Reports, document, graphs, charts, photographs or any other material (in whatever medium) produced by Geo Chem pursuant to this Agreement shall belong to Geo Chem. The Client shall have
                the right to use any such Reports, document, graphs, charts, photographs or other material for the purposes of this Agreement. <br>
                2.5 The Client agrees and acknowledges that Geo Chem retains any and all proprietary rights in concepts, ideas and inventions that may arise during the preparation or provision of any Report (including any deliverables provided by Geo
                Chem to the Client) and the provision of the Services to the Client. <br>
                2.6 Geo Chem shall observe all statutory provisions with regard to data protection including but not limited to the provisions of the Data Protection Act 1998. To the extent that Geo Chem processes or gets access to personal data in
                connection with the Services or otherwise in connection with this Agreement, it shall take all necessary technical and organizational measures to ensure the security of such data (and to guard against unauthorized or unlawful
                processing, accidental loss, destruction or damage to such data).
            </p>
            <p><b>3. OBLIGATION OF CUSTOMERS</b> <br>
                3.1 If the customer intends to make any change(s) to the Work(s) hereunder or assign any other work to Geo Chem QA prior to the completion of Work(s), such a change or new assignment shall only be effective in writing between both of
                the parties. If Geo Chem QA suffers from any loss or damage due to such a change or new assignment the Customer shall compensate the losses and damages. <br>
                3.2 If the Work(s) undertaken by Geo Chem QA hereunder requires any assistance from the Customer. The Customer shall be obliged to provide all necessary and reasonable cooperation and assistance which Geo Chem QA may deem
                it. If the Work(s) undertaken by Geo Chem QA hereunder cannot be completed due to the Customer‟s failure or inadequacy in assistance or cooperation, Geo Chem QA reserves the right to require the Customer to perform its
                obligation within a specified period of time limit for Geo Chem QA to complete its work(s) shall be extended simultaneously. If upon the expiration of specified time period the Customer still fails to perform its obligation to assist Geo
                Chem QA reserves the right to terminate this Agreement without prejudice to any other rights of Geo Chem QA hereunder or under any applicable laws and regulations.

            </p>
            <p><b>4. PATENT RIGHTS</b><br>
                4.1 Any invention made in the performance of Work(s) for the Customer by Geo Chem QA within the field of Work(s) undertaken for the Customer shall belong to the Customer. <br>
                4.2 Geo Chem QA‟s use of the aforesaid inventions shall be free of any royalty fees or other changes provided that the uses of such inventions are confined to the performance of Work(s) for the Customer.
            </p>
            <p><b>5. THE SERVICES</b> <br>
                5.1 Geo Chem shall provide the Services to the Client in accordance with the terms of this Agreement which is expressly incorporated into any Proposal Geo Chem has made and submitted to the Client. <br>
                5.2 In the even to any inconsistency between the terms of this Agreement and the Proposal, the terms of the Proposal shall take precedence. <br>
                5.3 The Services provided by Geo Chem under this Agreement and any memoranda, laboratory
                data, calculations, measurements, estimates notes, certificates and other material prepared by Geo Chem in the course of providing the Services to the Client, together with status summaries or any other communication in any form
                describing the results of any work or services performed (Report(s)) shall be only for the Client's use and benefit. <br>
                5.4 The Client acknowledges and agrees that if in providing the Services Geo Chem is obliged to deliver a Report to a third party, Geo Chem shall be deemed irrevocably authorized to deliver such Report to the applicable third party. For
                the purposes of this clause an obligation shall arise on the instructions of the Client, or where, in the reasonable opinion of Geo Chem, it is implicit from the circumstances, trade, custom, usage or practice. <br>
                5.5 The Client acknowledges and agrees that any Services provided and/or Reports produced by Geo Chem are done so within the limits of the scope of work agreed with the Client in relation to the Proposal and pursuant to the Client's
                specific instructions or, in the absence of such instructions, in accordance with any relevant trade custom, usage or practice. The Client further agrees and acknowledges that the Services are not necessarily designed or intended to
                address all matters of quality, safety, performance or condition of any product, material, services, systems or processes tested, inspected or certified and the scope of work does not necessarily reflect all standards which may apply to
                product, material, services, systems or process tested, inspected or certified. The Client understands that reliance on any Reports issued by Geo Chem is limited to the facts and representations set out in the Reports which represent
                Geo Chem „s review and/or analysis of facts, information, documents, samples and/or other materials in existence at the time of the performance of the Services only. <br>
                5.6 Client is responsible for acting as its refit on the basis of such Report. Neither Geo Chem nor any of its officers, employees, agents or subcontract or shall be liable to Client nor any third party for any actions taken or not taken on the
                basis of such Report. <br>
                5.7 In agreeing to provide the Services pursuant to this Agreement, Geo Chem does not abridge, abrogate or undertake to discharge any duty or obligation of the Client to any other person or any duty or obligation of any person to the
                Client.
            </p>
            <p><b>6. CHARGES, INVOICING AND PAYMENT</b> <br>
                6.1 The Client shall pay Geo Chem the charges set out in the Proposal, if applicable, or as otherwise contemplated for provision of the Services (the Charges). <br>
                6.2 The Charges are expressed exclusive of any applicable taxes. The Client shall pay any applicable taxes on the Charges at the rate and in the manner prescribed by law, on the issue by Geo Chem of a valid invoice. <br>
                6.3 The Client agrees that it will reimburse Geo Chem for any expenses incurred by Geo Chem relating to the provision of the Services and is wholly responsible for any freight or customs clearance fees relating to any testing samples. <br>
                6.4 The Charges represent the total fees to be paid by the Client for the Services pursuant to this Agreement. Any additional work performed by Geo Chem will be charged on a time and material basis. <br>
                6.5 Geo Chem shall invoice the Client for the Charges and expenses, if any. The Client shall pay each invoice within thirty (30) days of receiving it. <br>
                6.6 If any invoice is not paid on the due date for payment, Geo Chem shall have the right to charge, and the Client shall pay, interest on the unpaid amount, calculated from the due date of the invoice to the date of receipt of the amount in
                full at a rate equivalent to 3% per cent per annum above the base rate.
            </p>
            <p><b>7. DATA AND DOCUMENT RETENTION</b> <br>
                7.1 After the work(s) rendered, Geo Chem QA may retain a copy of all documents relating to the Work(s) (“the supporting documents”) for as long as Geo Chem QA gets the sole discretion. <br>
                7.2 Unless otherwise specified or required by the applicable law, the Supporting Documents over three years of age will be automatically destroyed by Geo Chem QA without prior notice to the customer. Should any or a supporting
                documents less than three years are scheduled to be destroyed GEO Chem QA shall give the Customer thirty days written notice to the Customer‟s last known address of the intention to destroy the Supporting Documents. Unless the
                Customer makes a written request to Geo Chem QA which is received by Geo Chem QA before the expiration of the said thirty days seeking delivery of those documents to the Customer at the Customer‟s expenses, those documents
                shall be destroyed. <br>
                7.3 The Customer shall indemnify Geo Chem QA for any costs or expenses in responding to or opposing any claims or losses or for the production of any documents in Court seeking the disclosure of the said documents or any information
                contained therein.
            </p>
            <p><b>8. INDEMNITY</b> <br>
                8.1 The Client shall in dignify and hold harmless Geo Chem, its officers, employees, agents, representatives, contractors and sub-contractors from and against any and all claims, results, liabilities (including costs of litigation and attorney's
                fees) arising, directly or indirectly, out of or in connection with:
                (a) Any claims results by any government all authority or others for any actual or as serrated failure of the Client to comply with any law, ordinance, regulation, rule or order of any government all or judicial authority;
                (b) Claims results for personal injuries, loss of or damage to property, economic loss, and loss of or damage to Intellectual Property rights incurred by or occurring to any person or entity and arising in connection with or related to the
                Services provided here under by Geo Chem, its officers, employees, agents, representatives, contractors an sub-contractors;
                (c) The breach or alleged breach by the Client of any of its obligations set out in Clause4 above;
                (d) Any claims made by any third party for loss, damage or expense of what so ever nature and howsoever arising relating to the performance, purported performance or non- performance of any Services to the extent that the aggregate
                of any such claims relating to any one Service exceeds the limit of liability set out in Clause 10 above;
                (e) Any claims results arising as result of any misuse run authorized use of any Reports issued by Geo Chem or any Intellectual Property Rights belonging to Geo Chem (including trade marks) pursuant to this Agreement; and
                (f) Any claims arising out of or relating to any third party's use of or reliance on any Reports or any reports, analyses, conclusions of the Client (or any third party to whom the Client has provided the Reports) based in whole or in part on
                the Reports, if applicable. <br>
                8.2 The obligations set out in this Clause 11 shall survive termination of this Agreement.
            </p>
            <p><b>9. TERMINATION</b> <br>
                9.1 This Agreement shall commence upon the first day on which the Services are commenced and shall continue, unless terminated earlier in accordance with this Clause 13, until the Services have been provided. <br>
                9.2 This Agreement may be terminated by: <br>
                (a) Either party if the other continues in material breach of any obligation imposed up on it here under for more than thirty (30) days after written notice has been dispatched by that Party by recorded delivery or courier requesting the
                other to remedy such breach; <br>
                (b) Geo Chem on written notice to the Client in the event that the Client fails to pay any invoice by its due date and/or fails to make payment after a further request for payment; or <br>
                (c) Either party on writ ten notice to the other in the event that the other makes any voluntary arrangement with its creditors or becomes subject to an administration order or (being an individual or firm) becomes bankrupt or (being a
                company) goes into liquidation (otherwise than for the purposes of a solvent amalgamation or reconstruction) or an encumbrance takes possession, or a receiver is appointed, of any of the property or assets of the other or the other
                ceases, or threatens to cease, to carry on business. <br>
                9.3 In the event of termination of the Agreement for any reason and without prejudice to any other rights or remedies the parties may have, the Client shall pay Geo Chem for all Services performed up to the date of termination. This
                o b l i g a t i o n s h a l l s u r v i v e termination or expiration of this Agreement. <br>
                9.4 Any termination or expiration of the Agreement shall not affect the accrued rights and obligations of the parties nor shall it affect any provision which is expressly or by implication intended to come in to force or continue in force on or
                after such termination or expiration.
            </p>
            <p><b>10. ASSIGNMENTAND SUB-CONTRACTING</b> <br>
                10.1 G e o C hem reserves the right to delegate the performance of its obligations here under and the provision of the Services to one or more of its affiliates and/or sub-contractors when necessary. G eo C h em may also assign this
                Agreement to any company within the G eo C h em group on notice to the Client.
            </p>
            <p><b>11. MISCELLANEOUS</b> <br>Severability <br>
                11.1 If any provision of this Agreement is or becomes invalid, illegal or unenforceable, such provision shall be severed and the remainder of the provisions shall continue in full force and effect as if this Agreement had been executed
                without the invalid illegal or unenforceable provision. If the in validity, illegality or unenforceability is so fundamental that it prevents the accomplishment of the purpose of this Agreement, Geo Chem and the Client shall immediately
                commence good faith negotiations to agree an alternative arrangement. <br>
                <b>No partnership or agency</b> <br>
                11.2 Nothing in this Agreement and no action taken by the parties under this Agreement shall constitute a partnership, association, joint venture or other co-operative entity between the parties or constitute any party the partner, agent or
                legal representative of the other. <b>Waivers</b> <br>
                11.3 The failure of any party to insist upon strict performance of any provision of this Agreement, or to exercise any right or remedy to which it is entitled, shall not constitute a waiver and shall not cause a diminution of the obligations
                established by this Agreement. A waiver of any breach shall not constitute a waiver of any subsequent breach. <br>
                11.4 No waiver of any right or remedy under this Agreement shall be effective unless it is expressly stated to be a waiver and communicated to the other party in writing. <br>
                <b>Whole Agreement</b> <br>
                11.5 This Agreement and the Proposal contain the whole agreement between the parties relating to the transactions contemplated by this agreement and supersedes all previous agreements, arrangements and understandings between
                the parties relating to those transactions or that subject matter. No purchase order, statement or other similar document will add to or vary the terms of this Agreement. <br>
                11.6 Each party acknowledges that in entering in to this Agreement it has not relied on any representation, warranty, collateral contractor other assurance (except those set out or refer red to in this Agreement) made by or on behalf of any
                other party before the acceptance or signature of this Agreement. Each party waives all rights and remedies that, but for this Clause, might otherwise be available to it in respect of any such representation, warranty, collateral
                contract or other assurance. <br>
                11.7 Nothing in this Agreement limits or excludes any liability for fraudulent misrepresentation.
                <br>
                <b>Third Party Rights</b> <br>
                11.8 A person who is not party to this Agreement has no right under the Contract (Rights of Third Parties) Act 1999 to enforce any of its terms. <br>
                <b>Further Assurance</b> <br>
                11.9 Each party shall, at the cost and request of any other party, execute and deliver such instruments and documents and take such other actions in each cases may be reasonably requested from time to time in order to give full effect to
                its obligations under this Agreement.
            </p>

            <p><b>12 AMENDMENT</b> <br>
                12.1 No amendment to this Agreement shall be effective unless it is in writing, expressly stated to amend this Agreement and signed by an authorized signatory of each party.
            </p>
            <p><b>13 GOVERNING LAW</b> <br>
                13.1 The Agreement and the rights and obligation of the parties shall in all respects be governed, constructed, interpreted and operated with Indian Law.
                These General Conditions shall be Governed and construed in accordance with the substantive laws of the place where the Company renders services and issues reports or certificates, exclusive of any rules with respect of conflicts of
                laws. All Disputes arising in connection with these General Conditions shall be finally settled by recourse to arbitration under the rules of conciliation and arbitration of the International Chamber of Commerce by one or more arbitrators
                appointed in accordance with the said rules. Unless otherwise agreed, the arbitration shall take place in the English language at the place where the Company renders services and issues reports or certificates.
                the said rules. Unless otherwise agreed, the arbitration shall take place in the English language at the place where the Company renders services and issues reports or certificates.

            </p>

        </div>
    </div>



</body>

</html>