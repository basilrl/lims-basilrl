<?php
$checkUser = $this->session->userdata('user_data');
$this->user = $checkUser->uidnr_admin;
$user = $this->user = $checkUser->uidnr_admin; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AATCC Draft Test Report</title>
</head>
<style>
    @page {
        background: url('https://basilrl-prod.s3.ap-south-1.amazonaws.com/imgpsh_fullsize_anim.jpeg') no-repeat 0 0;
        background-repeat: no-repeat;
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

    .detail_table table {
        width: 100% !important;
        text-align: center;
        border: 1px solid black;
    }

    .detail_table * {
        width: 100% !important;
        text-align: center;
    }
</style>

<body>


    <div class="bg_image">
        <htmlpageheader name="myHeader1">
            <table style="text-align: left;margin-top:-23px;">
                <tr>
                    <td>
                        <?php if (isset($qrcode)) { ?>
                            <img src="<?php echo $qrcode; ?>" alt="" style="text-align:left;margin-top:-23px;">
                        <?php } ?>
                        <?php if ($report_data->nabl_record != 0) { ?>
                            <img src="https://cpslims-prod.s3.ap-south-1.amazonaws.com/format/nabl.png" alt="" width="12%" style="text-align:left;margin-top:-23px;">

                            <img src="https://cpslims-prod.s3.ap-south-1.amazonaws.com/format/ilac.png" alt="" width="11%" style="text-align:left;margin-top:-23px;">
                        <?php } ?>
                    </td>
                </tr>
            </table>
        </htmlpageheader>

        <htmlpagefooter name="myFooter">
            <table style="width: 100%;margin-right:-30px;margin-top:30px;">
                <tr>
                    <td style="text-align:right;"> Pages {PAGENO} of {nb}</td>
                </tr>
            </table>
        </htmlpagefooter>

        <h3 style="text-align: center;">TEST REPORT</h3>

        <table style="width: 100%;">
            <tr>
                <th style="text-align: left;width:70%">Report No.: <?php echo (!empty($report_data->report_num) && $report_data->report_num != "") ? $report_data->report_num : ""; ?></th>
                <th style="text-align: right!important;">Dated: <?php echo (!empty($report_data->issuance_date) && $report_data->issuance_date != "") ? date("d M Y", strtotime($report_data->issuance_date)) : ""; ?></th>
            </tr>
            <?php if ($report_data->nabl_record != 0) { ?>
                <tr>
                    <th style="text-align: left;" colspan="2">ULR No: <?php echo ((!empty($report_data->ulr_no) && $report_data->ulr_no != "") && (!empty($report_data->ulr_no_flag) && $report_data->ulr_no_flag != "")) ? $report_data->ulr_no . $report_data->ulr_no_flag : ""; ?></th>
                </tr>
            <?php } ?>
        </table>

        <table style="font-size:14px;width:100%;">
            <?php if (!empty($report_data->customer_name) && $report_data->customer_name != "") { ?>
                <tr>
                    <td>Applicant</td>
                    <td>:</td>
                    <td><?php echo $report_data->customer_name; ?></td>
                </tr>
            <?php }
            if (!empty($report_data->contact_name) && $report_data->contact_name != "") { ?>
                <tr>
                    <td>Contact Person</td>
                    <td>:</td>
                    <td><?php echo $report_data->contact_name; ?></td>
                </tr>
            <?php }
            if (!empty($report_data->address) && $report_data->address != "") { ?>
                <tr>
                    <td>Address</td>
                    <td>:</td>
                    <td><?php echo $report_data->address; ?></td>
                </tr>
            <?php } ?>
            <tr>
                <th style="font-style:italic; font-weight:bold; text-align:left;" colspan="3">Sample not drawn by Geo-Chem Laboratories Pvt. Ltd.</th>
            </tr>
            <?php if (!empty($report_data->product_custom_fields)) {
                $custom_data = json_decode($report_data->product_custom_fields);
                foreach ($custom_data as $key => $row) { ?>
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
                <?php  } ?>
                <?php }
            if (count($application_data) == 1 && !empty($application_data)) {
                if ($app['instruction_name'] != '') { ?>
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
            <?php }
            } ?>
            <?php if (count($application_data) > 1 && !empty($application_data)) { ?>
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
        </table>

        <?php if ($test_data) { ?>
            <table border="1" style="width: 100%;border-collapse:collapse;text-align:center;">
                <thead>
                    <tr>
                        <th>S.No.</th>
                        <th>Test Conducted</th>
                        <?php if ($report_data->test_total != $test_component->total) { ?>
                            <th>Tested Component</th>
                        <?php } ?>
                        <th>Results</th>
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
        <?php } ?>
        <?php if ($report_data->part == 'yes') { ?>
            <?php echo html_entity_decode(base64_decode($report_data->part_details)); ?>
        <?php } ?>
        <br>
        <div style="margin-top: -10px;">P = Pass F= Fail</div>
        <?php if (!empty($report_data->remark) && $report_data->remark != "") { ?>
            <span style="font-weight: bold; margin-bottom:0px">Remark:</span>
            <div>
                <?php echo trim(html_entity_decode($report_data->remark)); ?>
            </div>
        <?php } ?>
        <?php $checkUser = $this->session->userdata('user_data'); ?>
        <table style="width: 100%;padding:0;margin:0;">
            <tr>
                <?php if ($sign_data1) { ?>
                    <?php if ($sign_data1->uidnr_admin == $user) { ?>
                        <?php if ($signature1) { ?>
                            <td style="width:50%;float:left;">
                                <table style="width:100%">
                                    <tr>
                                        <td>For and on behalf of</td>
                                    </tr>
                                    <tr>
                                        <td>Geo-Chem Laboratories Pvt. Ltd.</td>
                                    </tr>
                                    <tr>
                                        <td style="text-align:left;"><img src="<?php echo $signature1; ?>" alt=""></td>
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
                <?php } ?>
            </tr>
        </table>

        <?php if ($record_finding_data != '' && !empty($record_finding_data)) { ?>
            <div class="nabl logo chapter2" style="page-break-inside: auto !important;border-bottom:1px solid lightgray;">
                <h4>RESULTS</h4>
                <?php foreach ($record_finding_data as $key => $Data) { ?>
                    <!-- updated by millan on 14-07-2021 -->
                    <table style="width: 100%; font-size: 12px; border-collapse: collapse; border:1px solid black; text-align:left !important;">
                        <tr>
                            <th border="1" style="width: 100%; text-align:left !important;  font-size: 12px;"> <?php echo $Data['test_display_name']; echo "<br>"; ?> <?php echo $Data['test_display_method']; echo "<br>"; ?> <?php echo $Data['test_description']; ?> </th>
                        </tr>
                    </table>
                    <!-- updated by millan on 14-07-2021 -->

                    <?php if ($Data['nabl_headings'] && !empty($Data['nabl_headings'])) { ?>
                        <table border="1" style="width: 100%; border-collapse: collapse; text-align:left; border:1px solid black; font-size: 12px; margin-bottom:5px">
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

                    <?php if ($Data['nabl_detail'] != '' && $Data['nabl_detail'] != NULL) { ?>
                        <table style="width: 100% !important;border-collapse: collapse;  font-size: 12px;">
                            <tr>
                                <td><?php echo html_entity_decode(base64_decode($Data['nabl_detail'])); ?></td>
                            </tr>
                        </table>
                    <?php } ?>

                    <?php if ($Data['nabl_remark'] != '' && $Data['nabl_remark'] != NULL) { ?>
                        <table style="width: 100% !important;border-collapse: collapse;  font-size: 12px;">
                            <tr>
                                <td> <?php echo html_entity_decode(base64_decode($Data['nabl_remark'])); ?> </td>
                            </tr>
                        </table>
                    <?php } ?>

                    <!-- added by millan on 09-07-2021 -->
                    <?php if ($Data['test_details'] != '' && !empty($Data['test_details'])) { ?>
                        <table style="width: 102% !important; border-collapse: collapse; margin-bottom:10px; margin-top:-2px; margin-left:-11px; font-size:12px !important">
                            <tr>
                                <td><?php echo html_entity_decode($Data['test_details']); ?></td>
                            </tr>
                        </table>
                        <br>
                    <?php } ?>
                    <!-- added by millan on 09-07-2021 -->

                    <?php if (isset($Data['images']) && $Data['images']) { ?>
                        <table style="width: 100%;border-collapse:collapse;text-align:center;">
                            <tr>
                                <td style="text-align: center;">
                                    <table style="width:100%;border-collapse:collapse;text-align:center!important; cellspacing:2px;">
                                        <?php foreach ($Data['images'] as $key => $value) { ?>
                                            <tr>
                                                <td style="text-align: center;"><img src="<?php echo $value['image_path']; ?>" alt="" srcset="" style="border:1px solid #333;width:300px;"></td>
                                            </tr>
                                        <?php } ?>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    <?php } ?>
                <?php } ?>
            </div>
        <?php } ?>

        <p> ***************************************<b> End of the Test Report</b> ************************************</p>
        <p style="font-size: 10px;">The test report is based upon and pertains to the sample submitted for testing. This report is made solely on the basis of your instructions and/or information and materials supplied by you. The report is entirely for the samples submitted and does not imply any co-relation with the lot or other material. The results do not extend any warranty and no responsibility is accepted or any liability in-connection with any future loss arising out of this report. Without permission of the tests center, this report is not permitted to be duplicated in extracts. </p>
        <hr>
    </div>
</body>

</html>