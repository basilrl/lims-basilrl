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
        <?php } else { ?>background: url('https://basilrl-prod.s3.ap-south-1.amazonaws.com/imgpsh_fullsize_anim.jpeg');
        <?php } ?>margin-top: 2.5cm !important;
        margin-bottom: 1.5cm !important;
        background-position: top center;
        header: html_myHeader;
        footer: html_myFooter;
    }

    * {
        font-size: 10px;
        font-family: Cambria !important;
    }

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
        /* text-transform: uppercase; */
        padding: 3px;

    }

    table tr th {
        /* text-transform: uppercase; */
        padding: 3px;

    }
    body{
      font-size: 12px !important;
      font-weight: normal!important;
    }
    p{margin-top: 8px;}
</style>
<body style=" margin:0 auto; padding:20px; font-size:14px; font-family:Arial, Helvetica, sans-serif; background:#fff; background-size:cover">
<htmlpageheader name="myHeader">
<table width="100%" border="0" cellspacing="0" cellpadding="6" style="margin-bottom:0px; border-bottom:1px solid #646464;">
  <tr>
    <td align="left"><img src="https://basilrl-prod.s3.ap-south-1.amazonaws.com/stationary/logo-basil-pdf.png"></td>
    <td align="center"><h1 style="text-decoration:underline">TEST REPORT</h1></td>
    <?php if ($report_data->accreditation_flag == 1) { ?>
        <td style="text-align: right;"><img src="https://cpslims-prod.s3.ap-south-1.amazonaws.com/format/dubai_logo.png" alt="" style="text-align: right;width:200px;"></td>
    <?php } ?>
  </tr>
  <tr>
    <td align="left" style="border-bottom:1px solid#000"><strong>Test Report No.:</strong> <?php echo $report_data->report_num; ?></td>
    <td style="border-bottom:1px solid#000">&nbsp;</td>
    <td align="right" style="border-bottom:1px solid#000"><strong>Date:</strong> <?php echo date("d M Y", strtotime($report_data->issuance_date)); ?></td>
  </tr>
</table>
</htmlpageheader>
<htmlpagefooter name="myFooter">
<table width="100%" border="0" cellspacing="0" cellpadding="6" style="margin-top:25px; border-top:1px solid #646464;">
  <tr>
    <th align="left" style="border-top:1px solid#000">GC-DXB/REC-56</th>
    <th style="border-top:1px solid#000" align="left">Rev -02,</th>
    <th align="left" style="border-top:1px solid#000">25-10-2020</th>
    <th align="left" style="border-top:1px solid#000">Pages {PAGENO} of {nb}</th>
  </tr>
  <tr>
    <td align="center" colspan="4" style="text-transform:none;"><strong>GEO-CHEM MIDDLE EAST</strong><br />
      Plot No. 18-0, Dubai Investment Park II, Affection Plan # 597-22, EBC Warehouse # 6. Dubai. UAE. <br />
      E-mail : <a href="mailto:cps.dubai@basilrl.com">cps.dubai@basilrl.com</a> Web : <a href="https://basilrl.com">https://basilrl.com</a></td>
  </tr>
</table>
</htmlpagefooter>

<table border="0" cellspacing="0" cellpadding="6" width="100%" style="margin-bottom:25px;">
<tr>
  <td align="center"  style="padding-bottom:10px;"><h1 style="text-decoration:underline">PERRY ELLIS INTERNATIONAL</h1>
  </td>
  </tr>
  <tr>
   <td  align="right">
   <table width="200" border="1" cellspacing="0" cellpadding="6" style="float:rigth;">
  <tr>
    <th colspan="2">Overall Rating</th>
  </tr>
  <tr>
    <td align="center"><?php if($report_data->manual_report_result == 1) { echo "X"; } ?></td>
    <td align="left">Pass</td>
  </tr>
  <tr>
    <td><?php if($report_data->manual_report_result == 2) { echo "X"; } ?></td>
    <td align="left">Fail</td>
  </tr>
  <tr>
    <td><?php if($report_data->manual_report_result == 4) { echo "X"; } ?></td>
    <td align="left">Data</td>
  </tr>
</table>
   </td>   
  </tr>
</table>

<?php if ($image_sample) { ?>
<table width="100%" border="1" cellspacing="0" cellpadding="10">
  <tr>
    <th align="center" style="color:#092367">SUBMITTED SAMPLE</th>
  </tr>
  <?php foreach ($image_sample as $key9 => $sample_photo) {  ?>
  <tr>
    <td align="center"><img src="<?php echo $sample_photo->image_file_path; ?>" style="width:50%"></td>
  </tr>
  <?php } ?>
</table>
<?php } ?>
<br />
<br />
<div class="sample_image1 noheader" style="page-break-inside: auto !important;">
<h4 style="text-decoration:underline">Applicant Details</h4>
<table width="100%" border="1" cellspacing="0" cellpadding="6">
  <tr>
    <td align="left" width="50%">Applicant</td>
    <td align="left"><?php echo $report_data->customer_name; ?></td>
  </tr>
  <tr>
    <td align="left">Contact Person</td>
    <td align="left"><?php echo $report_data->contact_name; ?></td>
  </tr>
</table>
<h4 style="text-decoration:underline; margin-bottom:0; padding-bottom:0">Sample Details as Provided by Customer: </h4>
<br>
<table width="100%" border="1" cellspacing="0" cellpadding="6">
<?php if (!empty($report_data->product_custom_fields)) {  $custom_data = json_decode($report_data->product_custom_fields); foreach ($custom_data as $key => $row) { ?>
        <tr>
            <?php foreach ($row as $key1 => $value1) { if ($value1 != '') { ?>
                    <?php if ($key1 == 1) { ?>
                    <?php } ?>
                    <td>
                        <?php echo $value1; ?>
                    </td>
            <?php  } } ?>
        </tr>
<?php } } ?>
</table>
</div>
<br>
<table width="100%" border="1" cellspacing="0" cellpadding="6">
<tr>
    <td align="center">Remark: Sample not drawn by Geo-Chem Middle East.</td>
  </tr>
<?php if ($sign_data1) { if ($sign_data1->uidnr_admin == $user) { if ($signature1) { ?>
  <tr>
    <td align="left" width="50%">For and on behalf of <br />
      Geo-Chem Middle East </td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td align="left"><img src="<?php echo $signature1; ?>"></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
  <tr>
    <td><?php echo $sign_data1->name; ?> <br><?php echo $sign_data1->admin_role_name; ?></td>
    <td>&nbsp;</td>
  </tr>
  <?php } } }?>
 
</table>
<br>
<table width="100%" style="border:1px solid#000; margin-bottom:20px;" cellspacing="0" cellpadding="10">

  <tr>
    <td colspan="2" style="text-transform:none;">Test result is drawn according to the kind and extent tests performed. Without permission of the tests center this report is not permitted to be duplicated in extracts. This report does not entitle to carry any safety mark on this or similar products. Statement of conformity and decision rule will be given (if applicable). This test report represents the test parameters as requested by the customer based on submitted sample as Received only. </td>
  </tr>
</table>
<br />
<br />

<div class="sample_image1 noheader" style="page-break-inside: auto !important;">
<br />
<br />
<?php echo html_entity_decode(base64_decode($report_data->part_details)); ?>
<?php if (!empty($test_data)) { ?>
<h3 style="text-decoration:underline; margin-top:20px; margin-bottom:20px;">Conclusion </h3>
<table width="100%" border="1" cellspacing="0" cellpadding="6">
  <tr>
    <th width="10%">S.No.</th>
    <th>Tests Conducted</th>
    <th>Conclusion</th>
  </tr>
  <?php $i = 1; foreach ($test_data as $td) {  ?>
  <tr>
    <td align="center"><?php echo $i; ?></td>
    <td align="left"><?php if (!empty($td->test_display_name)) {  echo $td->test_display_name;  } else {  echo $td->test_name; } ?></td>
    <td align="center"><?php if ($td->test_result == 'Pass') {  echo 'P'; } else if ($td->test_result == 'Fail') {  echo 'F';} else {  echo $td->test_result; }?></td>
  </tr>
<?php } } ?>
</table>
<br/>
<table width="100%" border="0" cellspacing="0" cellpadding="6">
  <tr>
    <td>Note:</td>
    <td>M = Meets Requirement</td>
    <td> F = Does not Meet Requirement</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>* = Not Provided</td>
    <td> NA = Not Applicable</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td colspan="2"> = The tests are under GAC Accreditation </td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td colspan="2">¥   = Test Subcontracted to Geo Chem Group Laboratory / ISO 17025 Accredited Lab</td>
  </tr>
  <tr>
    <td colspan="3">** = Please refer Oeko-Tex Certificate for Zipper & Buttons (Page No. 14 for Zipper, Page No. 15 for Buttons)</td>
  </tr>
</table>
</div>
<br />
<br />

<div class="sample_image1 noheader" style="page-break-inside: auto !important;">
<h3 style="text-decoration:underline; margin-top:20px; margin-bottom:20px;">Test Results</h3>

  <?php if ($record_finding_data != '') { foreach ($record_finding_data as $key => $Data) { ?>
  
    <table width="100%" cellspacing="0" cellpadding="6">
  <tr>

    <th colspan="5" align="left" style="border:1px solid #646464;"><?php echo $Data['sequence_no']; ?>. <?php echo $Data['test_display_name']; ?> </th>
  </tr>
  <tr>
    <td colspan="5" align="left" style="border:1px solid #646464; border-top:0px; border-bottom:0px">(<?php echo $Data['test_display_method']; ?>) </td>
  </tr>
  </table>
  
  <?php if (empty($Data['test_type'])) { ?>
    <table style="text-transform:uppercase;width:100%;border-collapse:collapse;text-align:center;font-size:10px;;margin:0 auto;padding:0;" border="1">
    <tr>
        <td>NA</td>
    </tr>
    </table>
    <br>
<?php } else { ?>
<?php if ($Data['nabl_headings'] && !empty($Data['nabl_headings'])) { ?>

    <table border="1" style="text-transform:uppercase;width:100%;border-collapse: collapse;text-align:center;font-size:10px;padding:0;margin:0 auto;">
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
<div class="nabl_non_nabl" style="font-family:cambria;width:100%;margin-bottom:25px;text-align:center;font-size:10px;padding:0;">

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

            <table style="width:100%;border-collapse:collapse;text-align:center!important;cellspacing:2px;">
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
<?php } }?>

</div>

<div class="sample_image1 noheader" style="page-break-inside: auto !important;">
<table width="100%" border="0" cellspacing="0" cellpadding="6" style="margin-top:25px; line-height:30px;">
  <tr>
    <th align="center"><h3 style="text-decoration:underline; margin-top:20px; margin-bottom:20px;">Laboratory Summary Information for Children’s Product Certificate (CPC)</h3>
    </th>
  </tr>
  <tr>
    <td align="left" style="line-height: 30px;">I (we) hereby confirm based on the test results in this report, that the product or components described below </td>
  </tr>
  <tr>
      <td>were tested and comply with US applicable rules, bans, regulations, and standards for Children's Products mentioned and checked on the list below:</td>
    </tr>
    <tr>
      <td>Product/Component description provided by submitter Sample </td></tr>
      <tr>
      <td>Description: 7" Boys FF Adjustable Waist Solid Short</td></tr>
      <tr>
      <td>Color/Location: Caviar 002</td></tr>
      <tr>
      <td>Specification Number: P.O. Number - SU33068, SU33069, SU33290, SU33297, SU33298</td></tr>
      <tr>
      <td>Style/Item Number: PVBS50M9</td></tr>
      <tr>
      <td>Factory: Ashton Apparel (EPZ)</td></tr>
      <tr>
      <td>Safety Regulation Citations </td></tr>
      <tr>
      <td>
       <img style="width: 12px;" src="https://s3.ap-south-1.amazonaws.com/demo-portal-app.log/check-box-icon.jpg" alt="">
        Wearing Apparel Flammability 16 CFR 1610 </td></tr>
        <tr>
      <td>
        
<img style="width: 12px;" src="https://s3.ap-south-1.amazonaws.com/demo-portal-app.log/check-box-icon.jpg" alt="">
        Wearing Apparel Flammability 16 CFR 1610 (Exempted from testing under FFA regulation section 1610.1(d) due to fabric weight and/ or fiber content.) </td></tr>
        <tr>
      <td>
        
<img style="width: 12px;" src="https://s3.ap-south-1.amazonaws.com/demo-portal-app.log/check-box-icon.jpg" alt="">
        Flammability Standard for Children's Sleepwear 16 CFR 1615 and 1616</td>
        </tr>
        <tr>
        <td>
       <img style="width: 15px;" src="https://s3.ap-south-1.amazonaws.com/demo-portal-app.log/cross-icon.jpg" alt="">
        Small Parts 16 C.F.R. Part 1501 and 1500.50 - 53 </td>
        </tr>
        <tr>
      <td>
      <img style="width: 15px;" src="https://s3.ap-south-1.amazonaws.com/demo-portal-app.log/cross-icon.jpg" alt="">
        Sharp Points and Edges 16 CFR 1500.48 and 49 </td></tr>
        <tr>
        <td>
       <img style="width: 15px;" src="https://s3.ap-south-1.amazonaws.com/demo-portal-app.log/cross-icon.jpg" alt="">
        Phthalates CPSC-CHC1001-09.4: 2018</td>
        </tr>
    
        <tr>
      <td>
       
<img style="width: 12px;" src="https://s3.ap-south-1.amazonaws.com/demo-portal-app.log/check-box-icon.jpg" alt="">
        Lead Paint Ban 16 CFR. 1303</td></tr>
        <tr>
      <td><strong><u>Date of Testing:</u></strong></td>
        </tr>
        <tr>
      <td>Date of Third-Party Testing: 20 Nov 2021</td>
        </tr>
        <tr>
      <td>Date of Third-Party Revision: -</td>
        </tr>
        <tr>
      <td><strong><u>TPCA (Third Party Conformity Assessment Body)</u></strong></td>
        </tr>
        <tr>
      <td>Place of Third-Party Testing: Dubai, UAE</td></tr>
      <tr>
      <td>TPCA Name: Geo Chem Middle East </td></tr>
      <tr>
      <td>TPCA Address: Plot No. 18-0, Dubai Investment Park II, Affection Plan # 597-22, EBC Warehouse # 6. Dubai. UAE.</td>
      </tr>
      <tr>
      <td>TPCA Telephone Number: +971 4 8820527</td>
      </tr>
      <tr>
      <td>The information on this sheet is ONLY applicable for the product tested in this report. This document cannot be considered as a CPC but only as a traceability tool for manufacturer/importer to prepare their CPC</td>
      </tr>
      
  </tr>
</table>
</div>

<?php if ($reference_sample) { ?>
<div class="sample_image noheader" style="page-break-inside: auto !important;">

    <!-- <h4 style="text-align:center;"> REFERENCE IMAGES</h4> -->
    <?php foreach ($reference_sample as $key9 => $reference_photo) {  ?>
        <table style="width:100%;border-collapse:collapse;cellpadding:2px;">
            <tr>
                <td style="text-align: center;"><img  src="<?php echo $reference_photo->image_file_path; ?>" style="border:1px solid #333;width:80%;" alt="" srcset=""></td>
            </tr>
        </table>
    <?php } ?>
</div>
<?php } ?>

<p style="text-align: center;text-transform:uppercase;"> # End of the Test Report #</p>
</body>

</html>