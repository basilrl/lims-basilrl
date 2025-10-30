/* ! \file RecordFindingPdf.php
\brief Record Findings.

Details.
*/


<?php $report = $data[0]; ?>

<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>WORKSHEET</title>

</head>
<style type="text/css">
  body {
    font-family: Arial, Helvetica, sans-serif;
    font-size: 11px;
  }

  td {
    font-size: 12px;
  }

  h5,
  p {
    margin: 10px 0;
    padding: 5px 0;
  }

  ul {
    list-style: none;
    margin: 0 0 10px 0;
    padding: 0;
    overflow: hidden;
  }

  li {
    padding: 5px 0;
  }

  .title-r {
    font-weight: normal;
  }

  .title-r span {
    font-weight: bold;
  }

  label {
    float: left;
    width: 30%;
    font-weight: normal;
    text-align: right
  }

  .logo {
    text-align: left;
    float: left
  }

  .address h5 {
    border: none;
    padding: 3px 0;
    font-weight: normal
  }

  .address {
    width: 50%;
    float: right;
  }

  .ca-cust {
    clear: both;
  }

  .big {
    font-size: 16px;
    font-weight: bold;
  }

  .vbig {
    font-size: 20px;
    font-weight: bold;
    text-transform: uppercase
  }

  .result-data {
    width: 100%;
    margin: 0 auto;
  }

  .result-data span.det {
    float: left;
    width: 60%;
    text-align: left;
  }

  .sep {
    float: left;
    width: 10%;
    text-align: center;
  }

  .fullbordered td {
    border: 1px solid #DDDDDD;
  }

  .fullbordered {
    border-collapse: collapse;
  }

  h2 {
    page-break-before: always;
  }
</style>

<body>

  <div style="padding:15px;">

    <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td align="left" width="75%">
          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img src="<?php echo base_url("assets/images/logo-login.png"); ?>" alt="arabic" style="max-width:80%;max-width:50%" />
        </td>
        <td colspan=2>
          <table width="100%" border="0" cellpadding="0" cellspacing="2">
            <tr>
              <td class="big">Service:</td>
              <td><?php echo  $report->service ?> </td>
            <tr>
            <tr>
              <td class="big">Login Date:</td>
              <td><?php echo  $report->created_on ?> </td>
            <tr>
            <tr>
              <td class="big">Due Date:</td>
              <td><?php echo  $report->report_date  ?> </td>
            <tr>
          </table>
        </td>
      </tr>
      <tr>
        <td colspan="2" class="big">Report No:<?php echo  $report->temp_no ?> </td>
      </tr>

      <tr>
        <td colspan="2" align="center" class="big"><u>WORKSHEET</u></td>
      </tr>
      <tr>
        <td align="center" width="50%" style="border:none;" colspan=2>
          <table width="100%" border="0" cellpadding="0" cellspacing="10">
            <tr>
              <td colspan="2" width="50%">Sample Description</td>
              <td>:&nbsp;<?php echo  $report->sample_desc ?> </td>
            <tr>


            <tr>
              <td colspan="2" width="50%">End Use</td>
              <td>:&nbsp;<?php echo  $report->end_use ?> </td>
            <tr>
            <tr>
              <td colspan="2" width="50%">Country of Origin</td>
              <td>:&nbsp;<?php echo $report->c_origin ?> </td>
            <tr>
            <tr>
              <td colspan="2" width="50%">Country of Destination</td>
              <td>:&nbsp;<?php echo $report->d_origin ?> </td>
            <tr>
            <tr>
              <td colspan="2" width="50%">Sample Received Date</td>
              <td>:&nbsp;<?php echo  $report->sample_receiving_date ?> </td>
            <tr>
            <tr>
              <td colspan="2" width="50%">Testing Period</td>
              <td>:&nbsp;<?php echo  $report->created_on ?> to <?php echo  $report->report_date; ?></td>
            <tr>
            <tr>
              <td colspan="2" width="50%">Buyer Name</td>
              <td>:&nbsp;<?php echo  $report->buyer ?> </td>
            <tr>
            <tr>
              <td colspan="2" width="50%">Reference No.</td>
              <td>:&nbsp;<?php echo  $report->reference_no ?> </td>
            <tr>
            <tr>
              <td colspan="2" width="50%">Client Name</td>
              <td>:&nbsp;<?php echo $report->customer_name; ?></td>
            <tr>
            <tr>
              <td colspan="2" width="50%">Contact Person</td>
              <td>:&nbsp;<?php echo $report->contact_name ?> </td>
            <tr>
            <tr>
              <td colspan="2" width="50%">No. of samples</td>
              <td>:&nbsp;<?php echo $report->no_of_samples ?> </td>
            <tr>
            <tr>
              <td colspan="2" width="50%">Style No.</td>
              <td>:&nbsp;<?php echo $report->style_no; ?></td>
            <tr>
            <tr>
              <td colspan="2" width="50%">PO No.</td>
              <td>:&nbsp;<?php echo $report->po_no; ?></td>
            <tr>
            <tr>
              <td colspan="2" width="50%">Colour</td>
              <td>:&nbsp;<?php echo $report->colour; ?></td>
            <tr>
            <tr>
              <td colspan="2" width="50%"><b>Remarks<b></td>
            <tr>
            <tr>
              <td colspan="2" width="100%"><?php echo (html_entity_decode($report->remarks)) ?></td>
            <tr>
          </table>
        </td>
      </tr>
    </table>
  </div>
</body>

</html>