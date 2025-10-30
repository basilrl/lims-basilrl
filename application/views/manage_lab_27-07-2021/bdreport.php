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
        background: url('https://cpslims-prod.s3.ap-south-1.amazonaws.com/format/bd-report+(1).png') no-repeat 0 0;
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
                        <img src="https://cpslims-prod.s3.ap-south-1.amazonaws.com/format/bd_logo.png" alt="" style="text-align:left;" width="20%;">
                    </td>
                    <td>
                        <img src="https://cpslims-prod.s3.ap-south-1.amazonaws.com/format/nabl.png" alt="" width="12%" style="text-align:left;">
                    </td>
                    <td>
                        <img src="https://cpslims-prod.s3.ap-south-1.amazonaws.com/format/ilac.png" alt="" width="11%" style="text-align:left;">
                    </td>
                    <?php if (isset($qrcode)) { ?> <td>
                            <img src="<?php echo $qrcode; ?>" alt="" style="text-align:left;">
                        </td>
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
                        <img src="https://cpslims-prod.s3.ap-south-1.amazonaws.com/format/bd_logo.png" alt="" style="text-align:right;" width="20%;">
                    </td>
                </tr>
                <tr>
                    <td style="text-align: left;width:75%;" >REPORT No.: <?php echo $report_data->report_num; ?></td>
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
                    </tr><tr>
                    <td style="text-align:right"> Pages {PAGENO} of {nb}</td>
                </tr>
            </table>

        </htmlpagefooter>
       <?php if ($image_sample) { ?>
                <div class="" >

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

            <?php if($image_sample){ ?>
    <div class="sample_image1 noheader" style="page-break-inside: auto !important;">
   <?php }else { ?>
        <div >
   <?php } ?>

        <h3 style="text-align: center;">TEST REPORT</h3>

        <table style="font-size:10px;width:100%;">
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

            <!-- <tr>
                <th style="font-style:italic;text-align:left;" colspan="3">Sample not drawn by Geo-Chem Laboratories Pvt. Ltd.</th>
            </tr> -->

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
        <table style="width: 100%;padding:0;margin:0;font-size:10px;">
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
            </div>
    </div>
        <?php if ($test_data) { ?>
            <div class="sample_image1 noheader" style="page-break-inside: auto !important;font-size:10px;">
            <h4 style="text-align: center;">SUMMARY OF TEST RESULT</h4>
            <table border="1" style="font-size:10px;width: 100%;border-collapse:collapse;text-align:center;page-break-inside: auto !important;" class="sample_image1 noheader">
                <thead>
                    <tr>
                        <th>S.No.</th>
                        <th>Test Name</th>
                        <?php  if($report_data->test_total != $test_component->total){?>
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
                          <?php  if($report_data->test_total != $test_component->total){?>
                            <td><?php echo $td->test_component; ?></td>
                            <?php }?>
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
            <?php if($report_data->part == 'yes'){?>
                <?php echo html_entity_decode(base64_decode($report_data->part_details)); ?>
            <?php }?>
            <br>
            <div>P = Pass F= Fail</div>
            <div> <?php echo trim(html_entity_decode($report_data->remark)); ?></div>
            <?php $checkUser = $this->session->userdata('user_data'); ?>
            <!--            <p style="line-height: 0;padding:0;margin:0;">For and on behalf of</p>
            <p style="line-height: 0;padding:0;margin:0;"> Geo-Chem Laboratories Pvt. Ltd.</p>-->
          
       


            <?php if ($record_finding_data != '') { ?>
            <div class="sample_image1 noheader" style="page-break-inside: auto !important;font-size:10px;" >
                <?php foreach ($record_finding_data as $key => $Data) { ?>
              
                    <h3 style="text-align:left;font-weight:bold;margin-right:20px;"><?php echo $Data['sequence_no'];?>. <?php echo $Data['test_display_name'];?> <?php echo $Data['test_display_method'];?></h3>

                    <?php if(empty($Data['test_type'])) { ?>
                    <table style="width: 100%;border-collapse:collapse;text-align:center;font-size:10px;" border="1">
                    <tr><td>NA</td></tr>
                    </table>
                    <?php } else { ?>
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
                    <table style="width: 100% !important;border-collapse: collapse;margin:0;padding:0;font-size:10px;" >
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
                    <?php }?>
                            <?php if (isset($Data['images']) && $Data['images']) { ?>
                            <table style="width: 100%;border-collapse:collapse;text-align:center;font-size:10px;">
                        <tr>
                            <td style="text-align: center;">
                                
                                    <table style="width:100%;border-collapse:collapse;text-align:center!important;cellspacing:2px;font-size:10px;" >
                                        <?php foreach ($Data['images'] as $key => $value) { ?>
                                        <tr>
                                            
                                                <td style="text-align: center;"><img src="<?php echo $value['image_path']; ?>" alt="" srcset=""  style="border:1px solid #333;width:300px;" width="300px;"></td>
                                                
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

    <!-- </div> -->

</body>

</html>