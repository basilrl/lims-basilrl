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
        background: url('<?php echo base_url('assets/images/new_images/report_uae.png'); ?>') no-repeat 0 0;
        ;
        background-repeat: no-repeat;
        width: 100%;
        opacity: 0.3;
        background-size: cover !important;
        margin-top: 5.5cm !important;
        margin-bottom: 2cm !important;
        background-position: top center;
        header: html_myHeader1;
        footer: html_myFooter;

    }

    * {
        font-size: 14px;
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
            <table style="margin-top:-13px;width:100%;">

                <tr>
                    <td style="text-align: left;"><img src="<?php echo base_url(); ?>public/img/logo/logo-login.png" alt="" width="250px;" style="text-align: left;"></td>
                    <td style="text-align: center;">
                        <h1 style="font-size:25px;">TEST REPORT</h1>
                    </td>
                    <td style="text-align: right;"><img src="<?php echo base_url(); ?>public/img/logo/duabi_logo.jpg" alt="" width="200px;" style="text-align: right;"></td>
                </tr>
                <tr>
                    <th style="text-align: left;width:50%;">Test Report No.: <?php echo $report_data->report_num; ?></th>
                    <th style="text-align: right!important;width:50%;" colspan="2">Dated: <?php echo date("d M Y", strtotime($report_data->issuance_date)); ?></th>
                </tr>

            </table>

        </htmlpageheader>
        <htmlpagefooter name="myFooter">
            <table style="width: 100%;">
                <tr>
                    <td style="width:85%">&nbsp;</td>
                    <td style="float:right;"> Pages {PAGENO} of {nb}</td>
                </tr>
            </table>

        </htmlpagefooter>

        <!-- image content page -->
        <?php if ($report_data->sample_images_flag == 1) {

        ?>
            <?php if ($image_sample) { ?>
                <div class="sample_image noheader">
                    <h3 style="text-align: center;">SUBMITTED SAMPLE</h3>
                    <?php foreach ($image_sample as $key9 => $sample_photo) {  ?>
                        <table style="width:100%;border-collapse:collapse;cellpadding:2px;" border="1">


                            <tr>

                                <td style="text-align: center;font-weight: bold;"><?php echo $sample_photo->comment; ?></td>
                            </tr>
                            <tr>

                                <td style="text-align: center;"><img src="<?php echo $sample_photo->image_file_path; ?>" style="width:100%;" alt="" srcset=""></td>
                            </tr>



                        </table>
                </div>
            <?php } ?>
        <?php } ?>
    <?php } ?>

    <table>
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
    <?php if ($test_data) { ?>
        <table border="1" style="width: 100%;border-collapse:collapse;text-align:center;">
            <thead>
                <tr>
                    <th>S.No.</th>
                    <th>Test Name</th>
                    <th>Tested Component</th>
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
                        <td><?php echo $td->test_component; ?></td>
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

    <div style="border: 1px solid black;">Remark: <?php echo trim(html_entity_decode($report_data->remark)); ?></div>
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
    <p style="font-size: 10px;">The test report is based upon and pertains to the sample submitted for testing. This report is made solely on the basis of your instructions and/or information and materials supplied by you. The report is entirely for the samples submitted and does not imply any co-relation with the lot or other material. The results do not extend any warranty and no responsibility is accepted or any liability in-connection with any future loss arising out of this report. Without permission of the tests center, this report is not permitted to be duplicated in extracts. </p>


    <?php if ($nabl_record != '') { ?>
        <h6 style="text-align:center;font-weight:bold;">TEST RESULTS</h6>
        <?php foreach ($nabl_record as $key => $nablData) { ?>
            <?php if ($nablData['nabl_headings'] && !empty($nablData['nabl_headings'])) { ?>
                <table border="1" style="width: 100%;border-collapse: collapse;text-align:center;">
                    <?php foreach ($nablData['nabl_headings'] as $key1 => $table_type) { ?>
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
            <table style="width: 100% !important;border-collapse: collapse;margin:0;padding:0;">
                <?php if ($nablData['nabl_detail'] != '' && $nablData['nabl_detail'] != NULL) { ?>
                    <tr>
                        <td style="width: 100%;" class="detail_table"><?php echo html_entity_decode(base64_decode($nablData['nabl_detail'])); ?></td>
                    </tr>
                <?php } ?>
                <?php if ($nablData['nabl_remark'] != '' && $nablData['nabl_remark'] != NULL) { ?>
                    <tr>
                        <td style="width: 100%;" class="detail_table"><?php echo html_entity_decode(base64_decode($nablData['nabl_remark'])); ?></td>
                    </tr>
                <?php } ?>

                <?php if (isset($nablData['images']) && $nablData['images']) { ?>
                    <tr>
                        <td style="text-align: center;">

                            <table style="width:100%;border-collapse:collapse;text-align:center!important;cellspacing:2px;">
                                <?php foreach ($nablData['images'] as $key => $value) { ?>
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
    <?php } ?>





    <?php if ($non_nabl_record != '') { ?>

        <h6 style="text-align:center;font-weight:bold;">TEST RESULTS</h6>
        <?php foreach ($non_nabl_record as $key => $nonnablData) { ?>

            <?php if ($non_nabl_record && !empty($nonnablData['non_nabl_headings'])) { ?>
                <table border="1" style="width: 100%;border-collapse: collapse;text-align:center;">
                    <?php foreach ($nonnablData['non_nabl_headings'] as $key1 => $table_type) { ?>
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
            <table style="width:100% !important;border-collapse: collapse;margin:0;padding:0;">
                <?php if ($nonnablData['non_nabl_detail'] != '' && $nonnablData['non_nabl_detail'] != NULL) { ?>
                    <tr>
                        <td style="width: 100%;" class="detail_table"><?php echo html_entity_decode(base64_decode($nonnablData['non_nabl_detail'])); ?></td>
                    </tr>
                <?php } ?>
                <?php if ($nonnablData['non_nabl_remark'] != '' && $nonnablData['non_nabl_remark'] != NULL) { ?>
                    <tr>
                        <td style="width: 100%;" class="detail_table"><?php echo html_entity_decode(base64_decode($nonnablData['non_nabl_remark'])); ?></td>
                    </tr>
                <?php } ?>
                <?php if (isset($nonnablData['images']) && $nonnablData['images']) { ?>
                    <tr>
                        <td style="text-align: center;">
                            <table style="width:100%;border-collapse:collapse;text-align:center!important;cellspacing:2px;">
                                <?php foreach ($nonnablData['images'] as $key => $value) { ?>
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
    <?php } ?>
    <p style="text-align: center;"> # End of the Test Report #</p>
    </div>
</body>

</html>