<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Print</title>
<script src="<?php echo base_url('assets/plugins/bootstrap/js/bootstrap.bundle.min.js'); ?>"></script>
    </head>
    <style type="text/css">
        body{font-family:Arial, Helvetica, sans-serif; font-size:13px;}
        h5,p{ margin:10px 0; border-bottom:1px solid #ccc; padding:5px 0;font-size:13px;}
        ul{ list-style:none; margin:0 0 10px 0; padding:0; overflow:hidden; }
        li{padding:5px 0;}
        .title-r{ font-weight:normal;}
        .title-r span{ font-weight:bold;}
        label{float:left;width:100px; font-weight:normal;}
        .logo{text-align:left;float:left}
        .address h5{ border:none; padding:3px 0; font-weight:normal}
        .address{width:50%; float:right;}
        .ca-cust{ clear:both;}

        .details-outer,.detail-table {
            padding: 10px;
            width: 95%;
        }
        .details-outer table {
            border-collapse: collapse;
        }
        .details-outer table, .details-outer p, .details-outer div {
            font: 11px/13px arial,tahoma,helvetica,sans-serif;
            margin: 0;
        }

        .details-outer table td, .details-outer table th {
            border-bottom: 1px solid #eee;
            padding: 5px;
        }
        .detail-table td,.detail-table th{border: 1px solid #eee;padding: 5px;font: 11px/13px arial,tahoma,helvetica,sans-serif;
                                          margin: 0; text-align:left;}
        .detail-table table{ border-collapse:collapse}
   .cmtdept {
            color: #4b4b4b;
            float: left;
            font-size: 11px;
            padding: 5px;
        }
        .cmtuser {
            color: #767676;
            float: left;
            font-size: 11px;
            padding: 5px 5px 5px 0;
            text-align: left;
        }
        .cmtdate {
            color: #767676;
            float: left;
            font-size: 11px;
            padding: 5px 0;
        }
        .cmtlabel {
            color: #858585;
            float: left;
            font-size: 11px;
            padding: 0 5px 5px;
        }
        .cmtlabelApprovers {
            color: #858585;
            float: left;
            font-size: 11px;
            padding: 0 1px 5px 5px;
        }
        .cmtlabelApprovers b {
            color: #333;
        }
        .cmtlevel {
            color: #565656;
            float: left;
            font-size: 11px;
            font-weight: bold;
            padding: 0 0 5px;
        }
        .cmtcomments {
            color: #858585;
            float: left;
            font-size: 11px;
            padding-right: 8px;
        }
        .cmtcmts {
            color: #464545;
            float: left;
            font-size: 11px;
            font-style: italic;
            text-align: justify;
            width: 129px;
        }
        .cmtsummary {
            color: #ff0000;
            float: left;
            font-size: 11px;
            font-weight: bold;
        }
        .cmthr {
            background: #a4cced none repeat scroll 0 0;
            border: 0 none;
            clear: both;
            color: #a4cced;
            height: 1px;
        }
        *{margin: 0;
          padding: 0;
        }

        ul li{
            list-style: none;
            padding-bottom: 10px;
            float: left;
            width: 100%;
            font-family: arial;
        }
        ul li p{
            display: none;
            padding: 5px 6px;
            margin: 5px 0;
        }
       	
        h4 {
            width:100%;
        }

        h4 a{
            font-size: 12px;
            font-weight:bold;
            font-family: Arial;
            text-decoration: none;
            float: left;
            width: 75%;
            color: #416AA3;
            outline: none;
            background: url("./resources/images/toggle_button.png") no-repeat;
            padding:0 0 0 5%;
            height: 16px;
            overflow: hidden;
        }
        ul li h4 span{
            text-align: right;
            font-weight: bold;
            font-size: 11px;

        }
        .clicked{
            background: url("./resources/images/toggle_button.png") no-repeat scroll 0 -16px transparent !important;
            height: 16px;
            overflow: hidden;
        }
    </style>
    <body>
<?php // echo "<pre>"; print_r($data['test_data']);?>
        
        <p align="center"><strong>Sample Registration Details</strong></p>

        <div class="details-outer">
            <table width="100%" class="details_view_table">
                
                <tr><td>Branch</td><td ><?php echo $data['data']['branch']; ?></td>
                    <td>Test Specification</td><td ><?php echo $data['data']['test_standard']; ?></td></tr>
                <tr><td>Collection Date</td><td ><?php echo $data['data']['collection_date']; ?></td>
                    <td>Bar code No.</td><td ><img src='<?php echo $data['data']['barcode_path']; ?>' ></td></tr>
                <tr><td>Received date</td><td ><?php echo $data['data']['received_date']; ?></td>
                    <td>Seal no.</td><td ><?php echo $data['data']['seal_no']; ?></td></tr>
                <tr><td>Product</td><td ><?php echo $data['data']['sample_registration_sample_type_id']; ?></td>
                    <td>Quantity received</td><td ><?php echo $data['data']['qty_received']; ?> <?php echo $data['data']['qty_unit']; ?></td></tr>

                <tr><td>Sample Description</td><td width="25%"><?php echo $data['data']['sample_desc']; ?></td>
                    <td>Retain Sample period</td><td ><?php echo $data['data']['sample_retain_period']; ?> (Days)</td>
                </tr>   
                <tr><td>TRF no</td><td ><?php echo $data['data']['trf_ref_no']; ?></td>
                    <td>Basil Report Number</td><td ><?php echo $data['data']['gc_no']; ?></td>
                </tr>
                <tr><td>Applicant</td><td ><?php echo $data['data']['applicant']; ?></td>
                    <td>Contact</td><td ><?php echo $data['data']['applicant_contact']; ?></td>
                </tr>
                <tr><td>Invoice to</td><td ><?php echo $data['data']['invoice_name']; ?></td>
                    <td>Contact</td><td ><?php echo $data['data']['invoice_contact']; ?></td>
                </tr>
                <tr><td><?php if($data['trf_type']['trf_type'] == 'Open TRF'){echo 'Customer name';}else{echo 'Customer name from Quote';}?></td><td ><?php echo $data['data']['quote_cust']; ?></td>
                    <td>Product Type</td><td ><?php echo $data['data']['product_type']; ?></td>
                </tr>
                <tr><td>Quantity Description</td><td ><?php echo $data['data']['quantity_desc']; ?></td></td>                    
                </tr>

                    <?php if (!empty($custome_fields['custome_fields'])) { ?> <tr>

                        <?php
                        $i = 0;
                        $j=0;
                        for ($i; $i < count($custome_fields['custome_fields']); $i++) {
                            ?>
                            <td ><?php echo $custome_fields['custome_fields'][$i]['registration_fields_name']; ?></td>

                            <td ><?php echo $custome_fields['custome_fields'][$i]['field_values']; ?></td>

                            <?php
                            $j=$i+1;
                            if ($j % 2 == 0 && $i > 0) {
                                ?> </tr>  <?php
                        }
                    }
                    if ($i % 2 > 0 && $i <= count($custome_fields['custome_fields'])) {
                        ?> <td></td></tr> <?php
                        }
                    }
                    ?>
                </tr>

            </table>
        </div>
        <div class="detail-table ">


            <table border="1"  width="100%" class="details_view_table" align="left"> 
                <caption style="caption-side: top; width: auto; text-align: left;"><strong>Test Details</strong></caption>
                <th width='30%'>Test Name</th>
                <th width='30%'>Test Method</th>
                <!--<th width='40%'>Test Sub Parameters</th>-->

                <?php
               if (!empty($data['test_data'])) {
                    foreach ($data['test_data'] as $row) {
                        ?>
                        <tr>
                            <td><?= $row['sample_test_test_name']; ?></td>
                            <td><?= $row['sample_test_test_method']; ?></td>


                            <?php
                            $saved_parameters = $this->db->query("SELECT GROUP_CONCAT(parameter_id) "
                                    . "FROM work_analysis_test_parameters WHERE work_analysis_test_id = {$row['sample_test_test_id']}")->row_array();

                            if (!empty($saved_parameters['parameter_id'])) {
                                $saved_parameters = str_replace(';', ',', $saved_parameters['parameter_id']);
                                $params_con = " AND test_parameters_id IN ({$saved_parameters['parameter_id']}) ";


                                $sub_params = $this->db->query("SELECT GROUP_CONCAT(test_parameters_disp_name)"
                                        . " FROM test_parameters WHERE test_parameters_test_id = {$row['sample_test_test_id']}"
                                        . " AND show_in_report ='Yes' {$params_con} ORDER BY test_parameters_disp_name ASC")->row_array();
                                ?>

                                <td><?php //echo $sub_params; ?></td>
                                <?php }?>
                                <?php
                        }
                    }
                    ?>              
                </tr>                   
            </table>

        </div>
    </br>
    </br>
    </br>
    </br>
          
        <div class="cdetails-outer no-border" style="width:95%; margin:0 auto; margin-top: 87px;">
            <p align="center"><strong>User Log Details</strong></p>
            <?php
            $display = '';
            $display1 ='';
                    
            $display .= ''
                    . '<ul class="anexure">';
            if (!empty($data['samplelog'])) {

                foreach ($data['samplelog'] as $key => $value) {
                    $date = date('d-M-Y H:i:s ', strtotime($value['log_activity_on']));
                    $display1 .= '<li>'
                            . '<table width = "100%" border = "0" cellspacing = "0" cellpadding = "0" class = "fullbordered">'
                            . '<tr>';

                    $display1 .= '<td align="left" width="65%">';


                    $display1 .= '<div class="cmtuser"> | <b>' . $value['user'] . '</b></div>'
                            . '<div class="cmtdate"> | ' . $date . '</div></td>'
                            . '<td align="left" ><div class="cmtlabel"> Current Status</div>';

                    $display1 .= '<div class="cmtlevel">' . $value['new_status'] . '</div></td>'
                            . '</tr><tr>'
                            . '<td align="left" >'
                            . '<div class="cmtlabel">Action</div>'
                            . '<div class="cmtsummary">' . $value['action_message'] .'</div></td>'
                            . '<td>'
                            . '<div class="cmtcomments">Previous Status</div>'
                            . '<div class="cmtcmts">' . $value['old_status'] . '</div>'
                            . '</td>'
                            . '</tr>';

                    $display1.= '</ul></table>'
                            . '<hr class="cmthr"></li>';
                }

                $display .= $display1 . '</ul>';
            }
            
// header("Content-type: text/html");
            echo $display;
            ?>
      
        </div>

    </body>
</html>