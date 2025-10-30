<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<style>
    @page {
        background: url('https://cpslims-prod.s3.ap-south-1.amazonaws.com/format/ggnreport.png') no-repeat 0 0;
        background-repeat: no-repeat;
        width: 100%;
        background-size: cover !important;
        margin-top: 5.5cm !important;
        margin-bottom: 2cm !important;
        background-position: top center;
    }

    table {
        table-layout: fixed;
        width: 100% !important;
    }

    th {
        font-size: 12px;
        text-transform: uppercase !important;
        padding: 5px;
        font-weight: bold;
        text-align: left;
    }

    td {
        font-size: 12px;
        text-align: left;
    }

    * {
        font-size: 12px;
    }
</style>

<body>
    <div class="bg_image">
        <htmlpageheader name="myHeader1">
            <table class="table">
                <tr>
                    <td> </td>
                    <td style="text-align:center">
                        <img src="<?php echo base_url('public/img/lims_logo.gif')?>" width="150px" alt="GEO CHEM LOGO">
                    </td>
                    <td style="text-align:right">
                        <p style="text-align:justify"> Plot No. 306, Udyog Vihar II Rd, Phase II, <br> Udyog Vihar, Sector 20, Gurugram, <br> Haryana 122016 </p>
                    </td>
                </tr>
            </table>
        </htmlpageheader>

        <htmlpagefooter name="myFooter" >
            <table style="width: 100%;margin-right:-30px;margin-top:30px;" >
                <tr>
                    <td style="text-align:right;"> Pages {PAGENO} of {nb}</td>
                </tr>
            </table>
        </htmlpagefooter>

        <h3 style="text-align: center;">LOW ITEM NOTIFICATION REQUIREMENT</h3>
        <h4 style="text-align: center;">APPROVAL FOR <?php echo strtoupper($result->item_name); ?> </h4>
        <h5 style="text-align: center;"> Details regarding <?php echo strtoupper($result->item_name); ?> are provided below:  </h5>
        <?php if( (!empty($result->item_name) && $result->item_name!='') || (!empty($result->category_name) && $result->category_name!='') || (!empty($result->min_quantity_required) && $result->min_quantity_required!='') || (!empty($result->current_requirement) && $result->current_requirement!='') || (!empty($result->requirement_reason) && $result->requirement_reason!='') ) { ?>
            <table style="width:100%;">
                <?php if(!empty($result->item_name) && $result->item_name!='') { ?>
                    <tr>
                        <th>Item Name</th>
                        <td valign="top">:</td>
                        <td valign="top"><?php echo $result->item_name ?></td>
                    </tr>
                <?php } if(!empty($result->category_name) && $result->category_name!='') { ?>
                    <tr>
                        <th>Category Name</th>
                        <td valign="top">:</td>
                        <td valign="top"><?php echo $result->category_name ?></td>
                    </tr>
                <?php } if(!empty($result->min_quantity_required) && $result->min_quantity_required!='') { ?>    
                    <tr>
                        <th>Minimum Quantity Required</th>
                        <td valign="top">:</td>
                        <td valign="top"><?php echo $result->min_quantity_required ?></td>
                    </tr>
                <?php } if(!empty($result->current_requirement) && $result->current_requirement!='') { ?>
                    <tr>
                        <th>Current Quantity Needed</th>
                        <td valign="top">:</td>
                        <td valign="top"><?php echo $result->current_requirement . " " . $result->unit ?></td>
                    </tr>
                <?php } if(!empty($result->requirement_reason) && $result->requirement_reason!='') { ?>
                    <tr>
                        <th>Requirement Reason</th>
                        <td valign="top">:</td>
                        <td valign="top"><?php echo $result->requirement_reason ?></td>
                    </tr>
                <?php } if(!empty($result->created_by) && $result->created_by!='') { ?>
                    <tr>    
                        <th>Requirement Raised By</th>
                        <td valign="top">:</td>
                        <td valign="top"><?php echo $result->created_by ?></td>
                    </tr>
                <?php } else { ?>
                    <tr>    
                        <td colspan="3">NO RECORD FOUND</td>
                    </tr>
                <?php } ?>
            </table> <br>
        <?php } ?>
        <?php if( ( ($result->lab_manager_id > 0) && ($result->lab_manager_status == 'Accepted') ) || ($gm_approver->gm_admin > 0 && !empty($gm_desg) )  ) { ?>
            <table style="width: 100%;">
                <tr>
                    <td style="text-align: left;"><img src="<?php echo $lbmn_sign; ?>" alt="LAB MANAGER APPROVAL"> </td>
                    <td style="text-align: right;"><img src="<?php echo $gm_signature; ?>" alt="GENERAL MANAGER APPROVAL"> </td>
                </tr>
                <tr>
                    <th style="text-align: left; "> <?php echo $result->created_by; ?></th>
                    <th style="text-align: right;"><?php echo $gm_approver->gm_admin; ?></th>
                </tr>
                <tr>
                    <th style="text-align: left;"> ( <?php echo $result->designation_name; ?> ) </th>
                    <th style="text-align: right;"> ( <?php echo $gm_desg->designation_name; ?> ) </th>
                </tr>
            </table>
        <?php } ?>
    </div>
</body>
</html>