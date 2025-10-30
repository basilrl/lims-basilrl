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
        <?php if (isset($template_type) && $template_type == 'preview') { ?>background: url('https://basilrl-prod.s3.ap-south-1.amazonaws.com/draft-report-pdf-bg.jpeg') no-repeat 0 0;
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
        /* text-align: center; */
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

    /* table tr td {
        text-transform: uppercase;

    }

    table tr th {
        text-transform: uppercase;

    } */
    body{
      font-size: 12px !important;
      font-family: cambria !important;
      font-weight: normal!important;
    }
</style>

<body>

    <div class="bg_image">
        <htmlpageheader name="myHeader">
            <table style="margin-top:-20px;width:100%;">
                <tr>
                    <td style="text-align:left;"><img src="https://basilrl-prod.s3.ap-south-1.amazonaws.com/stationary/logo-basil-pdf.png" alt="" style="text-align: left;"></td>
                    <td style="text-align: center;font-size:24px;font-weight:600;text-decoration:underline;font-weight:bold;font-family:Arial;">
                        TEST REPORT
                    </td>
                    <?php if($report_data->accreditation_flag == 1){?>
                    <td style="text-align: right;"><img src="https://cpslims-prod.s3.ap-south-1.amazonaws.com/format/dubai_logo.png" alt="" style="text-align: right;width:200px;"></td>
                    <?php } ?>
                    <td>
                        <?php if (isset($qrcode)) { ?>
                            <img src="<?php echo $qrcode; ?>" alt="">
                        <?php } ?>
                    </td>
                </tr>
            </table>
            <table style="width:100%;font-family:calibri;border-bottom:1px solid black;">
                <tr>
                    <td style="text-align: left;width:75%;">Test Report No.: <b> <?php echo $report_data->report_num; ?></b></td>
                    <td style="text-align:right!important;width:25%;">Dated: <b> <?php echo date("d M Y", strtotime($report_data->issuance_date)); ?></b></td>
                </tr>

            </table>

        </htmlpageheader>
        <htmlpagefooter name="myFooter">
            <hr>
            <table style="width: 100%;text-align:center;font-size:9px;font-family:calibri;">
                <tr>
                    <td colspan="4"><b> GEO-CHEM MIDDLE EAST</b></td>
                </tr>
                <tr>
                    <td colspan="4">Plot No. 18-0, Dubai Investment Park II, Affection Plan # 597-22, EBC Warehouse # 6. Dubai. UAE. <br>
                        E-mail :<a href="cps.dubai@basilrl.com"> cps.dubai@basilrl.com </a> Web :<a href="https://basilrl.com"> https://basilrl.com</a>
                    </td>
                </tr>
                <tr class="footer">
                    <td style="opacity:0.3;"><b> GC-DXB/REC-56-E</b></td>
                    <td style="opacity:0.3;"><b> Rev -00,</b></td>
                    <td style="opacity:0.3;"><b> 27-10-2021</b></td>
                    <td style="opacity:0.3;"><b> Pages {PAGENO} of {nb}</b></td>
                </tr>

            </table>

        </htmlpagefooter>

        <?php if ($image_sample) { ?>
            <div class="" >
                <br/>
                <br/>
                <br/>
                <br/>
                <br/>
                <table cellpadding="6" style="width:100%;border-collapse:collapse;cellpadding:2px;" border="1">
                    <tr>
                        <th style="text-align: center;color:#092367; font-size:14px; font-family:Arial, Helvetica, sans-serif">SUBMITTED SAMPLE</th>
                    </tr>
                    <?php foreach ($image_sample as $key9 => $sample_photo) {  ?>

                        <?php if ($sample_photo->comment) { ?>
                            <tr>

                                <td style="text-align: center;font-weight: bold;"><?php echo $sample_photo->comment; ?></td>
                            </tr>
                        <?php } ?>
                        <tr>

                            <td style="text-align: center; padding:10px"><img src="<?php echo $sample_photo->image_file_path; ?>" style="width:50%;text-align: center;" alt="" srcset=""></td>
                        </tr>



                    <?php } ?>
                </table>
            </div>
        <?php } ?>
        <?php if ($image_sample) { ?>
            <div class="sample_image1 noheader" style="page-break-inside: auto !important;">
            <?php } else { ?>
            <?php } ?>
            <p><strong><u>Applicant Details</u></strong></p>
            <table width="100%" border="0" cellspacing="0" cellpadding="6" style=" font-family:Arial, Helvetica, sans-serif;font-size:11px;width:100%;margin:0 auto;text-align:left;border-collapse:collapse;cellpadding:5px; cellspacing:2px" border="1">
                <tr>
                    <td>Applicant</td>
                    <td>DICE SPORTS & CASUAL WEAR</td>
                </tr>

                <tr>
                    <td>Contact Person</td>
                    <td>Mossad Mortada, Osama</td>
                </tr>

                <tr>
                    <td>Address </td>
                    <td>5. Petro: St. From Gessr El Suez St. Industrial Area – Heliopolis, Cairo, 00202, Egypt.</td>
                </tr>
                <tr>
                    <td>Contact Details </td>
                    <td>Mosaad.mortada@dicefactory.net, osama.kamel@dicefactory.net </td>
                </tr>
            </table>

            <p style=" margin-top:15px; margin-bottom:15px;"><strong><u>Sample Details as Provided by Customer</u></strong></p>

            <table width="100%" border="0" cellspacing="0" cellpadding="6" style="font-family:Arial, Helvetica, sans-serif; font-size:11px;width:100%;margin:0 auto;text-align:left;border-collapse:collapse; cellpadding:5px; cellspacing:2px" border="1">
                <?php if (!empty($report_data->product_custom_fields)) {
                    $custom_data = json_decode($report_data->product_custom_fields);

                    foreach ($custom_data as $key => $row) { ?>
                        <tr>
                            <?php foreach ($row as $key1 => $value1) {
                                ?>
                                    <?php if ($key1 == 1) { ?>

                                    <?php } ?>
                                    <td>
                                        <?php echo $value1; ?>
                                    </td>
                            <?php  
                            } ?>
                        </tr>
                    <?php  } ?>
                <?php } ?>
            </table>

            </div>

            <table width="100%" border="0" cellspacing="0" cellpadding="6" style="font-family:Arial, Helvetica, sans-serif; font-size:11px;width:100%;margin-top:15px; text-align:left;border-collapse:collapse;cellpadding:5px; cellspacing:2px" border="1">
                <tr>
                    <td align="center">Remark : Sample not drawn by Geo-Chem Middle East.</td>
                </tr>
            </table>

            <?php if ($report_data->remark) { ?>

                <div style="width: 100%; font-size:11px; font-family:Arial, Helvetica, sans-serif;"><?php echo trim(html_entity_decode($report_data->remark)); ?></div>
                </tr>
            <?php } ?>
            <?php $checkUser = $this->session->userdata('user_data'); ?>

            <table style="width: 100%;padding:0;margin:0;font-size:11px;">
                <tr>
                    <?php if ($sign_data1) { ?>
                        <?php if ($sign_data1->uidnr_admin == $user) { ?>
                            <?php if ($signature1) { ?>
                                <td style="width:50%;float:left;">
                                    <table width="100%" border="0" cellspacing="0" cellpadding="6" style="font-size:11px;width:100%;margin:0 auto;text-align:left;border-collapse:collapse;cellpadding:5px; cellspacing:2px" border="1">
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
            <p style="font-size: 10px;">Test result is drawn according to the kind and extent tests performed. Without permission of the tests center this report is not permitted to be duplicated in extracts. This report does not entitle to carry any safety mark on this or similar products. Statement of conformity and decision rule will be given (if applicable). This test report represents the test parameters as requested by the customer based on submitted sample as Received only.</p>
            
            <div class="sample_image1 noheader" style="page-break-inside: auto !important;">
            <h3>Conclusion</h3>
            <?php if ($test_data) { ?>

                <table width="100%" border="0" cellspacing="0" cellpadding="6" style="font-size:11px;width:100%;margin:0 auto;text-align:left;border-collapse:collapse;cellpadding:5px; cellspacing:2px" border="1">

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
            </div>

            <table style="width:100%;border-collapse:collapse;text-align:left;font-size:11px;margin-top:15px;">

                <tr>
                    <td style="width: 12%;">NOTE:</td>
                    <td> M = Meets Requirement </td>
                    <td> F = Does not Meet Requirement</td>
                </tr>
                <tr>
                    <td style="width: 12%;"></td>
                    <td>* = Not Provided </td>
                    <td>N/A = Not Applicable </td>
                </tr>
                <tr>
                    <td style="width: 12%;"></td>
                    <td colspan="2"> = The tests are under GAC Accreditation</td>
                </tr>
                <tr>
                    <td style="width: 12%;"></td>
                    <td colspan="2">¥ = This Test Subcontracted to ISO 17025 Accredited Lab</td>
                </tr>
                </table>
            
                <table style="width:100%;border-collapse:collapse;text-align:left;font-size:11px;margin:0 auto; font-family:Arial, Helvetica, sans-serif">
                    <tr>
                        <th>Revision Remark:</th>
                        <td>The Test report number GCDUBTX2102847 has been revised with Inclusion of Failure Items Image as per the Applicant Request.</td>
                    </tr>
                </table>

            
                <?php if ($record_finding_data != '') { ?>
                    <div class="sample_image1 noheader" style="page-break-inside: auto !important;font-size:11px;">
                    <p><strong><u>Test Result</u></strong></p>
                    <?php foreach ($record_finding_data as $key => $Data) { ?>
                        <table style="width:100%;border-collapse:collapse;font-size:11px;margin:0 auto;" border="1">
                            <tr>
                                <th style="text-align:left;padding:6px;"><?php echo $Data['sequence_no']; ?>. <?php echo $Data['test_display_name']; ?> <br>
                                </th>
                            </tr>
                          
                            <tr>
                                <td>
                                <?php if (!empty($Data['test_display_method'])) { ?>
                                        <?php echo $Data['test_display_method']; ?>
                                    <?php } ?>
                                </td>
                            </tr>
                        </table>

                        <?php if (empty($Data['test_type'])) { ?>

                            <table style="width:100%;border-collapse:collapse;text-align:center;font-size:11px;;margin:0 auto;padding:0;" border="1">
                                <tr>
                                    <td>NA</td>
                                </tr>
                            </table>
                            <br>
                        <?php } else { ?>
                            <?php if ($Data['nabl_headings'] && !empty($Data['nabl_headings'])) { ?>

                                <table border="1" style="width:100%;border-collapse: collapse;text-align:center;font-size:11px;;padding:0;margin:0 auto;">
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
                                <table style="width:100%;border-collapse:collapse;font-size:11px;margin:0 auto;" border="1">
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
                            <div class="nabl_non_nabl" style="font-family:cambria;width:100%;margin:0 auto;font-size:11px;padding:0; margin-bottom:20px">
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
                            <table style="width: 100%;border-collapse:collapse;text-align:center;font-size:11px;">
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
                </div><br>
            <?php } ?>
            <?php if (!empty($failure_image)) { ?>
                <div class="sample_image1 noheader" style="page-break-inside: auto !important;">
                <table border="1" style="width:100%; border-collapse:collapse;cellpadding:2px;">
                    <tr>
                        <td style="text-align: center;color:blue; font-size:12px">FAILURE IMAGE</td>
                    </tr>
                    <tr>

                        <td style="text-align: center; padding:10px"><img src="<?php echo $failure_image['image_file_path']; ?>" style="width:70%;text-align: center;" alt="" srcset=""></td>
                    </tr>
                </table>
            </div>
            <?php } ?>

            <?php if ($reference_sample) { ?>
                <div class="sample_image noheader" style="page-break-inside: auto !important;">
                <table   cellpadding="6" style="width:100%; border-collapse:collapse; border:1px solid #000; margin-bottom:-3px; border-bottom:0px;">
                <tr>
                   <td align="center"><h4> FAILURE IMAGE</h4></td> </tr>
                </table>
                
                    <?php foreach ($reference_sample as $key9 => $reference_photo) {  ?>
                        <table border="1" style="width:100%; border-collapse:collapse; cellpadding:2px;" cellpadding="10">
                       


                            <tr>

                                <td style="text-align: center;"><img src="<?php echo $reference_photo->image_file_path; ?>" style="border:1px solid #333; width:85%;" alt="" srcset="" style="border:1px solid #333;width:300px;" alt="" srcset=""></td>
                            </tr>



                        </table>
                    <?php } ?>
                </div>
            <?php } ?>

            <p style="text-align: center;"> # End of the Test Report #</p>
    </div>



</body>

</html>