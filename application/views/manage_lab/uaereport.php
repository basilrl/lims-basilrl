<?php
$checkUser = $this->session->userdata('user_data');
$this->user = $checkUser->uidnr_admin;
$user = $this->user = $checkUser->uidnr_admin; ?>
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
       <?php if(isset($template_type) && $template_type == 'preview') {?>
         background: url('https://basilrl-prod.s3.ap-south-1.amazonaws.com/draft-report-pdf-bg.jpeg') no-repeat 0 0; */
        <?php } else { ?>
        background : url('https://basilrl-prod.s3.ap-south-1.amazonaws.com/imgpsh_fullsize_anim.jpeg') no-repeat 0 0;
        <?php } ?>
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


    * {
        font-size: 10px;
        font-family: Cambria;
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

    /* .sample_image1 {
        page-break-inside: auto !important;
    } */
</style>

<body>

    <div class="bg_image">
        <htmlpageheader name="myHeader">
            <table style="margin-top:-20px;width:100%;">
                <tr>
                    <td style="text-align:left;"><img src="https://basilrl-prod.s3.ap-south-1.amazonaws.com/stationary/logo-basil-pdf.png" alt="" style="text-align: left;"></td>
                    <td style="text-align: center;font-size:24px;font-weight:600;text-decoration:underline;font-family:calibri;">
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
            <table style="width:100%;font-family:calibri;border-bottom:1px solid black;"> 
                <tr>
                    <th style="text-align: left;width:75%;" >Test Report No.: <?php echo $report_data->report_num; ?></th>
                    <th style="text-align:right!important;width:25%;" >Dated: <?php echo date("d M Y", strtotime($report_data->issuance_date)); ?></th>
                </tr>

            </table>

        </htmlpageheader>
        <htmlpagefooter name="myFooter">
            <hr>
            <table style="width: 100%;text-align:center;font-size:9px;font-family:calibri;
">
                <tr>
                    <td colspan="4">GEO-CHEM MIDDLE EAST</td>
                </tr>
                <tr>
                    <td colspan="4">Plot No. 18-0, Dubai Investment Park II, Affection Plan # 597-22, EBC Warehouse # 6. Dubai. UAE. <br>
                        E-mail :<a href="cps.dubai@basilrl.com"> cps.dubai@basilrl.com </a> :<a href="https://basilrl.com">Web : https://basilrl.com</a>
                    </td>
                </tr>
                <tr class="footer">
                    <td style="opacity:0.3;">GC-DXB/REC-56</td>
                    <td style="opacity:0.3;">Rev -02,</td>
                    <td style="opacity:0.3;">25-10-2020</td>
                    <td style="opacity:0.3;"> Pages {PAGENO} of {nb}</td>
                </tr>

            </table>

        </htmlpagefooter>
        <?php if ($image_sample) { ?>
            <div class="">
                <h3 style="text-align: center;color:blue;font-family:calibri;">SUBMITTED SAMPLE</h3>
                <?php foreach ($image_sample as $key9 => $sample_photo) {  ?>
                    <table style="width:100%;border-collapse:collapse;cellpadding:2px;" border="1">


                        <tr>

                            <td style="text-align: center;font-weight: bold;"><?php echo $sample_photo->comment; ?></td>
                        </tr>
                        <tr>

                            <td style="text-align: center;"><img src="<?php echo $sample_photo->image_file_path; ?>" style="width:70%;text-align: center;" alt="" srcset=""></td>
                        </tr>



                    </table>
            </div>
        <?php } ?>
    <?php } ?>
    <div class="sample_image1 noheader" style="page-break-inside: auto !important;">
        <table style="font-size:10px;font-family:calibri;width:100%; border-bottom:1px solid lightgray;">
            <tr>
                <th style="font-style:italic;text-align:left;" colspan="3">Applicant Details</th>
            </tr>
            <tr>
                <td>Applicant</td>
                <td>:</td>
                <td><?php echo $report_data->customer_name; ?></td>
            </tr>
            <tr>
                <td>Contact Person</td>
                <td>:</td>
                <td><?php echo $report_data->contact_name; ?></td>
            </tr>
            <tr>
                <td>Address</td>
                <td>:</td>
                <td><?php echo $report_data->address; ?></td>
            </tr>
            <tr>
                <td>Contact Details</td>
                <td>:</td>
                <td><?php echo $report_data->customer_name; ?></td>
            </tr>
            <tr>
                <th style="font-style:italic;text-align:left;" colspan="3">Sample Details as Provided by Customer: </th>
            </tr>

            <tr>
                <td>Sample Description</td>
                <td>:</td>
                <td><?php echo $report_data->sample_desc; ?></td>
            </tr>

            <tr>
                <td>Buyer</td>
                <td>:</td>
                <td><?php echo $report_data->buyer_name; ?></td>
            </tr>
            <tr>
                <td>Agent</td>
                <td>:</td>
                <td><?php echo $report_data->agent_name; ?></td>
            </tr>
            <tr>
                <td>End use</td>
                <td>:</td>
                <td><?php echo $report_data->trf_end_use; ?></td>
            </tr>

            <?php if (!empty($report_data->product_custom_fields)) {
                $custom_data = json_decode($report_data->product_custom_fields);
                //                echo "<pre>";
                //                print_r($custom_data);
                //                exit;
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
            <?php } ?>
            <?php if (count($application_data) == 1) {
                if ($app['instruction_name'] != '') {
            ?>
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
            <?php if (count($application_data) > 1) { ?>
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
            <tr>
                <td>Country of Origin</td>
                <td>:</td>
                <td><?php echo $report_data->country_origin; ?></td>
            </tr>
            <tr>
                <td>Country of Destination</td>
                <td>:</td>
                <td><?php echo $report_data->destination; ?></td>
            </tr>
            <tr>
                <td>Sample Received Date</td>
                <td>:</td>
                <td><?php echo date("d/m/y", strtotime($report_data->received_date)); ?></td>
            </tr>
            <tr>
                <td>Testing Period</td>
                <td>:</td>
                <td><?php echo date("d/m/y", strtotime($report_data->received_date)); ?> to <?php echo date("d/m/y", strtotime($report_data->generated_date)); ?></td>
            </tr>
        </table>
    </div>
    <?php if ($test_data) { ?>
        <table border="1" style="width: 100%;border-collapse:collapse;text-align:center;font-size:10px;font-family:calibri;
">
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
    <?php } ?> <br>
    <div style="border: 1px solid black;padding:5px;font-size:10px;font-family:calibri;
">Remark : <?php echo trim(html_entity_decode($report_data->remark)); ?></div>
    <?php $checkUser = $this->session->userdata('user_data'); ?>
    <!--            <p style="line-height: 0;padding:0;margin:0;">For and on behalf of</p>
            <p style="line-height: 0;padding:0;margin:0;"> Geo-Chem Laboratories Pvt. Ltd.</p>-->
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
                                    <td style="text-align:left;"><img src="<?php echo $signature1; ?>" alt="" width="10%;"></td>
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
            <!-- <?php if ($sign_data2->uidnr_admin == $user) { ?>
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
                                        <td style="text-align:right;"><img src="<?php echo $signature2; ?>" alt=""></td>
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
                    <?php } ?> -->
        </tr>

    </table>
    <p style="font-size: 10px;font-family:calibri;
">The test report is based upon and pertains to the sample submitted for testing. This report is made solely on the basis of your instructions and/or information and materials supplied by you. The report is entirely for the samples submitted and does not imply any co-relation with the lot or other material. The results do not extend any warranty and no responsibility is accepted or any liability in-connection with any future loss arising out of this report. Without permission of the tests center, this report is not permitted to be duplicated in extracts. </p>




    <?php if ($record_finding_data != '') { ?>
        <div class="nabl logo chapter2" style="page-break-inside: auto !important;border-bottom:1px solid lightgray;font-size:10px;font-family:calibri;">
            <?php foreach ($record_finding_data as $key => $Data) { ?>

                <h3 style="text-align:left;font-weight:bold;margin-right:20px;"><?php echo $Data['sequence_no']; ?>. <?php echo $Data['test_display_name']; ?> ( <?php echo $Data['test_display_method']; ?> )</h3>

                <?php if (empty($Data['test_type'])) { ?>
                    <table style="width: 100%;border-collapse:collapse;text-align:center;font-size:10px;font-family:calibri;" border="1">
                        <tr>
                            <td>NA</td>
                        </tr>
                    </table>
                <?php } else { ?>
                    <?php if ($Data['nabl_headings'] && !empty($Data['nabl_headings'])) { ?>
                        <table border="1" style="width: 100%;border-collapse: collapse;text-align:center;font-size:10px;font-family:calibri;">
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
                        <table border="1" style="width: 100%;border-collapse: collapse;text-align:center;font-size:10px;font-family:calibri;
">
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
                    <table style="width: 100% !important;border-collapse: collapse;margin:0;padding:0;font-size:10px;font-family:calibri;
">
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
                    <table style="width:100% !important;border-collapse: collapse;margin:0;padding:0;font-size:10px;font-family:calibri;
">
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
                    <table style="width: 100%;border-collapse:collapse;text-align:center;font-size:10px;font-family:calibri;
">
                        <tr>
                            <td style="text-align: center;">

                                <table style="width:100%;border-collapse:collapse;text-align:center!important;cellspacing:2px;">
                                    <?php foreach ($Data['images'] as $key => $value) { ?>
                                        <tr>

                                            <td style="text-align: center;"><img src="<?php echo $value['image_path']; ?>" alt="" srcset="" style="border:1px solid #333;width:300px;"></td>

                                        </tr>
                                    <?php } ?>
                                </table>
                            </td>
                        </tr>
                    <?php } ?>
                    </table>
                <?php } ?>
        </div>
    <?php } ?>


    <p style="text-align: center;font-family:calibri;"> # End of the Test Report #</p>
    </div>



</body>

</html>
